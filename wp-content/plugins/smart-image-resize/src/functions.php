<?php

use WP_Smart_Image_Resize\Helper;

if ( ! function_exists( 'wp_sir_get_settings' ) ) {
    /**
     * Get plugin settings.
     *
     * @return array
     */
    function wp_sir_get_settings()
    {

        $defaultSizes = array_filter(get_intermediate_image_sizes(),
        function($sizeName){
            return ! in_array($sizeName, ['1536x1536', '2048x2048']);
        });

        $defaults = [
            'enable'             => 1,
            'bg_color'           => '#ffffff',
            'jpg_quality'        => 5,
            'sizes'              => $defaultSizes,
            'jpg_convert'        => 0,
            'enable_webp'        => 0,
            'enable_trim'        => 0,
            'processable_images' => [
                'post_types' => [ 'product' ],
                'taxonomies' => [],
            ],
        ];
        _wp_sir_set_compat_settings();

        $settings = wp_parse_args( get_option( 'wp_sir_settings' ), $defaults );

        if ( ! isset( $settings[ 'processable_images' ][ 'post_types' ] ) ) {
            $settings[ 'processable_images' ][ 'post_types' ] = [ 'product' ];
        }

        if ( ! isset( $settings[ 'processable_images' ][ 'taxonomies' ] ) ) {
            $settings[ 'processable_images' ][ 'taxonomies' ] = [];
        }

        return apply_filters( 'wp_sir_settings', $settings );
    }
}

if ( ! function_exists( '_wp_sir_set_compat_settings' ) ) {

    /**
     * Backward compatibility with old versions settings.
     */
    function _wp_sir_set_compat_settings()
    {
        $settings = get_option( 'wp_sir_settings' ) ?: [];
        if ( ! empty( $settings ) ) {
            return;
        }

        $legacy_settings = get_option( 'ppsir_settings' );
        if ( empty( $legacy_settings ) ) {
            return;
        }

        $settings[ 'enable' ] = isset( $legacy_settings[ 'enable' ] )
                                && $legacy_settings[ 'enable' ]
            ? 1 : 0;

        if ( isset( $legacy_settings[ 'bg_color' ] ) ) {
            $settings[ 'bg_color' ] = $legacy_settings[ 'bg_color' ];
        }
        if ( isset( $legacy_settings[ 'jpg_quality' ] ) ) {
            $settings[ 'jpg_quality' ] = 100 - absint( $legacy_settings[ 'jpg_quality' ] );
        }

        add_option( 'wp_sir_settings', $settings );

        delete_option( 'ppsir_settings' );
    }
}

/*
 * Get working images sizes
 *
 * @return array
 */
if ( ! function_exists( 'wp_sir_get_additional_sizes' ) ) :

    /**
     * Return registered sizes' data.
     *
     * @return array
     */
    function wp_sir_get_additional_sizes()
    {
        $sizes = wp_get_additional_image_sizes();
        foreach ( [ 'thumbnail', 'medium', 'medium_large', 'large' ] as $name ) {
            $sizes[ $name ] = [
                'width'  => absint( get_option( "{$name}_size_w" ) ),
                'height' => absint( get_option( "{$name}_size_h" ) ),
            ];
        }

        foreach ( $sizes as $name => $data ) {
            if ( absint( $data[ 'width' ] ) === 0 ) {
                $sizes[ $name ][ 'width' ] = $sizes[ $name ][ 'height' ];
            } elseif ( absint( $data[ 'height' ] ) === 0 ) {
                $sizes[ $name ][ 'height' ] = $sizes[ $name ][ 'width' ];
            }
        }

        return $sizes;
    }
endif;

if ( ! function_exists( 'wp_sir_get_size_dimensions' ) ) :

    /**
     * Get a given size name width/height.
     *
     * @param string $name
     *
     * @return array|null
     */
    function wp_sir_get_size_dimensions( $name )
    {
        $size = null;

        foreach ( wp_sir_get_additional_sizes() as $n => $data ) {
            if ( $n === $name ) {
                $size = $data;
                break;
            }
        }

        if (
            ! $size || ! isset( $size[ 'width' ], $size[ 'height' ] )
            || min( $size[ 'width' ], $size[ 'height' ] ) === 0
        ) {
            return null;
        }

        return $size;
    }
endif;

if ( ! function_exists( 'wp_sir_get_upload_dir' ) ) {

    /**
     * Get WP uploads directory path.
     *
     * @param string $file
     *
     * @return string
     */
    function wp_sir_get_upload_dir( $file = '' )
    {
        return wp_get_upload_dir()[ 'basedir' ] . ( $file === '' ? '' : '/' . $file );
    }
}



if ( ! function_exists( 'wp_sir_webp_supported' ) ) {

    /**
     * Check if WebP format is supported by browser.
     *
     * @return bool
     */
    function wp_sir_is_webp_supported()
    {
        return ! wp_is_mobile() && isset( $_SERVER[ 'HTTP_ACCEPT' ] ) && strpos( $_SERVER[ 'HTTP_ACCEPT' ],
                'image/webp' ) !== false;
    }
}

/**
 * Return processable post types.
 *
 * @param int|null $attachment_id
 *
 * @return array
 */
function wp_sir_get_processable_post_types( $attachment_id = null )
{
    $processable_post_types = (array)wp_sir_get_settings()[ 'processable_images' ][ 'post_types' ];

    $processable_post_types = (array)apply_filters( 'wp_sir_resize_post_type', 'product', $processable_post_types );

    if ( in_array( 'product', $processable_post_types, true ) ) {
        $processable_post_types[] = 'product_variation';
    }

    return $processable_post_types;
}

if ( ! function_exists( 'wp_sir_is_processable' ) ) {

    /**
     * Returns true if the given attachment is attached to the given post type.
     * OPTIMIZEME.
     *
     * @param int $attachment_id
     * @param string|array|null $post_type
     *
     * @return bool
     */
    function wp_sir_is_processable( $attachment_id )
    {
        global $wpdb;

        // Starting 1.4.0 processed images are marked with `_processed_at` meta.
        // That being said, we don't need to do heavy check to determine
        // whether the given image is processable, using `_processed_at`
        // would be enough.
        if ( get_post_meta( $attachment_id, '_processed_at' ) ) {
            return apply_filters( 'wp_sir_is_attached_to', true, $attachment_id );
        }

        $attachment = get_post( $attachment_id );

        // Invalid attachment, skip.
        if ( empty( $attachment ) || is_wp_error( $attachment ) ) {
            return apply_filters( 'wp_sir_is_attached_to', false, $attachment_id );
        }

        // Get processable post type.

        $processable_post_types = wp_sir_get_processable_post_types( $attachment_id );

        // Process images with `post_parent`.
        if ( $attachment->post_parent && in_array( get_post_type( $attachment->post_parent ), $processable_post_types,
                true ) ) {
            return apply_filters( 'wp_sir_is_attached_to', true, $attachment_id );
        }

        // Find images attached using `_thumbnail_id` or `_product_image_gallery`
        $post_type_placeholder = Helper::array_sql_placeholder( $processable_post_types );

        $sql = "SELECT pm.post_id from {$wpdb->postmeta} pm
                JOIN {$wpdb->posts} p
                ON p.ID = pm.post_id
                WHERE pm.meta_key IN ('_thumbnail_id','_product_image_gallery')
                AND FIND_IN_SET(%d, pm.meta_value)
                AND p.post_type IN($post_type_placeholder)";

        $post_ids = $wpdb->get_col( $wpdb->prepare( $sql,
            array_merge( [ $attachment_id ], $processable_post_types ) ) );
        $post_ids = array_unique( array_filter( $post_ids ) );

        // Check whether if given image is attached to processable post types.
        $processable_post_image = count( $post_ids ) > 0;

        if ( $processable_post_image ) {
            return apply_filters( 'wp_sir_is_attached_to', true, $attachment_id );
        }

        // Check whether if given image is attached to processable taxonomies.
        $processable_term_image = wp_sir_is_term_image( $attachment_id );

        if ( $processable_term_image ) {
            return apply_filters( 'wp_sir_is_attached_to', true, $attachment_id );
        }

        return apply_filters( 'wp_sir_is_attached_to', false, $attachment_id );
    }

    /*
     * Returns processable taxonomies.
     * @access private
     * @return array
     */

    if ( ! function_exists( 'wp_sir_get_processable_taxonomies' ) ) {
        function wp_sir_get_processable_taxonomies()
        {
            // Get selected taxonomies.
            $taxonomies = wp_sir_get_settings()[ 'processable_images' ][ 'taxonomies' ];

            // Allow developers to filter processable taxonomies.
            return (array)apply_filters( 'wp_sir_allowed_taxonomies', $taxonomies );
        }
    }

    if ( ! function_exists( 'wp_sir_is_term_image' ) ) {
        /**
         * Check whether the given image is attached to the processable taxonomies.
         * This expect attachment id meta to be named `thumbnail_id`. Otherwise, you can use filter
         * `wp_sir_processable_tax_query_args` and provide two parameters (`$attachment_id` and `taxonomy`) to change meta name.
         *
         * @param int $attachment_id
         * @return bool
         *
         * @todo Other taxonomies will be added including "pwb-brand" (Perfect WooCommerce Brands plugin).
         */
        function wp_sir_is_term_image( $attachment_id )
        {
            if ( ! apply_filters( 'wp_sir_enable_category_image', true ) ) {
                return false;
            }

            $processable_taxonomies = wp_sir_get_processable_taxonomies();

            if( empty( $processable_taxonomies) ){
                return false;
            }

            $args = [
                'hide_empty' => false,
                'meta_query' => [
                    [
                        'key'   => 'thumbnail_id',
                        'value' => $attachment_id,
                    ],
                ],
            ];

            /*
             * @todo optimize query.
             * @since 1.4
             */
            foreach ($processable_taxonomies as $tax ) {
                $count = wp_count_terms( $tax,
                    apply_filters( 'wp_sir_processable_tax_query_args', $args, $tax, $attachment_id ) );

                if ( is_wp_error( $count ) || empty( $count ) ) {
                    continue;
                }
                // At least one taxonomy should processable.
                if ( (int)$count > 0 ) {
                    return true;
                }
            }

            return false;
        }
    }

    /**
     * Determine if the current request is an upload attachment request.
     * @return bool
     * @todo Use screen `async-upload` instead.
     */
    function wp_sir_is_attachment_upload()
    {
        $upload_attachment = filter_input( INPUT_POST, 'action', FILTER_SANITIZE_STRING );

        return 'upload-attachment' === $upload_attachment || isset( $_POST[ 'post_id' ] );
    }

    /**
     * @todo test
     */
    function wp_sir_is_product_image_rest_upload()
    {
        $route = wp_sir_maybe_get_rest_route();

        if ( empty( $route ) ) {
            return false;
        }

        $processable_routes = [
            '/wc/v3/products',
            '/wc/v2/products',
        ];

        return in_array( $route, $processable_routes );
    }

    /**
     * Check whether the image is being uploaded through the frontend.
     * @return bool
     * @todo Optimize frontend detection line:499
     *
     * @see _wp_sir_is_frontend_upload()
     */
    function wp_sir_is_frontend_upload()
    {
        return apply_filters( 'wp_sir_doing_frontend_upload', _wp_sir_is_frontend_upload() );
    }

    function _wp_sir_is_frontend_upload()
    {
        // Upload request ?
        if ( ! wp_sir_is_attachment_upload() ) {
            return false;
        }

        // Dokan frontend upload?
        if ( wp_sir_is_dokan_frontend_upload() ) {
            return true;
        }

        // Can we process any upload through the frontend?
        if ( ! apply_filters( 'wp_sir_process_frontend_upload', false ) ) {
            return false;
        }

        // Upload made through the frontend interface ?
        return ! Helper::is_referer( '/wp-admin/' );
    }

    if ( ! function_exists( 'wp_doing_ajax' ) ) {

        /**
         * @see https://developer.wordpress.org/reference/functions/wp_doing_ajax/
         */
        function wp_doing_ajax()
        {
            apply_filters( 'wp_doing_ajax', defined( 'DOING_AJAX' ) && DOING_AJAX );
        }
    }
    function wp_sir_is_dokan_frontend_upload()
    {

        // Not through the admin interface.
        if ( Helper::is_referer( '/wp-admin/' ) ) {
            return false;
        }

        if( function_exists( 'dokan_is_seller_enabled') ){

            return dokan_is_seller_enabled( get_current_user_id() );
        }

        return get_user_meta( get_current_user_id(), 'dokan_enable_selling', true ) === 'yes';
    }
   

    /**
     * Determine whether image is uploaded through media libray.
     */
    function wp_sir_is_media_library_upload()
    {
        if ( ! apply_filters( 'wp_sir_process_media_library_upload', false ) ) {
            return false;
        }

        // Upload request?
        if ( wp_sir_is_attachment_upload() ) {

            // Through HTML uploader?
            require_once(ABSPATH . 'wp-admin/includes/screen.php');

            if(function_exists( 'get_current_screen' )){
                $screen = get_current_screen();
                
                if($screen && is_object($screen) && $screen->id === 'media' ){
                    return true;
                }
            }
           

            // Or through default uploader.
            return Helper::is_referer( '/media-new.php' );
        }

        // Through WP Rest API?
        $route = wp_sir_maybe_get_rest_route();

        return ! empty( $route ) && $route === '/wp/v2/media';
    }

    /**
     * Get current rest route if present.
     *
     * @return false|string
     */
    function wp_sir_maybe_get_rest_route()
    {
        if ( ! isset( $GLOBALS[ 'wp' ] ) || ! is_object( $GLOBALS[ 'wp' ] ) || ! isset( $GLOBALS[ 'wp' ]->query_vars[ 'rest_route' ] ) ) {
            return false;
        }

        $rest_route = $GLOBALS[ 'wp' ]->query_vars[ 'rest_route' ];

        if ( empty( $rest_route ) ) {
            return false;
        }

        return untrailingslashit( $rest_route );
    }

    /**
     * Check if an post image is being uploaded.
     *
     * @param int $attachment_id
     *
     * @return bool
     */
    function wp_sir_is_post_image_upload( $attachment_id )
    {
        $processable_post_types = wp_sir_get_processable_post_types( $attachment_id );

        // Uploaded trough media library?
        if ( wp_sir_is_attachment_upload() ) {
            $post_type = false;

            if ( isset( $_REQUEST[ 'post_id' ] ) && ! empty( $_REQUEST[ 'post_id' ] ) ) {
                $post_type = get_post_type( $_REQUEST[ 'post_id' ] );
            }

            return in_array( $post_type, $processable_post_types, true );
        }

        // Uploaded through WC Rest API?
        return in_array( 'product', $processable_post_types, true ) && wp_sir_is_product_image_rest_upload();
    }

    function _wp_sir_is_uploading_term_image( $taxonomies )
    {
        // Uploading through Media Library.
        if ( ! wp_sir_is_attachment_upload() ) {
            return false;
        }

        // Get Edit category page url.
        $referer = wp_get_referer();

        if ( ! $referer ) {
            return false;
        }

        $url_parts = wp_parse_args( wp_parse_url( $referer ), [
            'path'  => '',
            'query' => '',
        ] );

        if ( ! Helper::is_referer( [ 'edit-tags.php', 'term.php' ], $url_parts[ 'path' ] ) ) {
            return false;
        }

        wp_parse_str( $url_parts[ 'query' ], $params );

        $params = wp_parse_args( $params, [
            'taxonomy' => '',
        ] );

        return in_array( $params[ 'taxonomy' ], $taxonomies, true );
    }

    function wp_sir_is_term_image_upload( )
    {
        if ( ! apply_filters( 'wp_sir_enable_category_image', true ) ) {
            return false;
        }

        $taxonomies = wp_sir_get_processable_taxonomies();

        if( empty($taxonomies) ){
            return false;
        }

        return apply_filters( 'wpsir_is_uploading_term_image', _wp_sir_is_uploading_term_image($taxonomies ), $taxonomies );
    }

    if ( ! function_exists( 'wp_sir_is_imagick_installed' ) ) {

        /**
         * Return true if ImageMagick is installed on the server.
         * @deprecated
         */
        function wp_sir_is_imagick_installed()
        {
            return extension_loaded( 'imagick' ) && class_exists( 'Imagick' );
        }
    }

    if ( ! function_exists( 'wp_sir_is_webp_installed' ) ) {
        /**
         * Return true if WebP is installed on the server.
         * @return bool
         * @deprecated
         */
        function wp_sir_is_webp_installed()
        {
            return function_exists( 'imagewebp' );
        }
    }

    if ( ! function_exists( 'wp_sir_regen_thumb_active' ) ) {
        function wp_sir_regen_thumb_active()
        {
            return in_array( 'regenerate-thumbnails/regenerate-thumbnails.php',
                apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) );
        }
    }
}

