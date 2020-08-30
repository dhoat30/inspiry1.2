<?php

namespace WP_Smart_Image_Resize;

use Exception;
use WP_Smart_Image_Resize\Image_Filters\Recanvas_Filter;
use WP_Smart_Image_Resize\Image_Filters\Resize_Filter;
use WP_Smart_Image_Resize\Image_Filters\Trim_Filter;
use WP_Smart_Image_Resize\Image_Filters\Webpify_Filter;
use WP_Smart_Image_Resize\Utilities\Env;
use WP_Smart_Image_Resize\Utilities\Request;

/*
 * Class WP_Smart_Image_Resize\Image_Editor
 *
 * @package WP_Smart_Image_Resize\Inc
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( '\WP_Smart_Image_Resize\Image_Editor' ) ) :

    final class Image_Editor
    {
        use Processable_Trait;
        use Runtime_Config_Trait;

        /**
         * @var \WP_Smart_Image_Resize\Image_Editor
         */
        protected static $instance = null;


        /**
         * @return \WP_Smart_Image_Resize\Image_Editor
         */
        public static function get_instance()
        {
            if ( is_null( static::$instance ) ) {
                static::$instance = new self;
            }

            return static::$instance;
        }

        public function getImageManager()
        {
            return new Image_Manager();
        }

        /**
         * Register hooks.
         */
        public function run()
        {

            // Resize thumbnails to match user settings.
            // A low priority < 10 to let plugins optimize thumbnails.
            add_filter( 'wp_generate_attachment_metadata', [ $this, 'process_image' ], 9, 2 );

            // Maybe load WebP.
            add_filter( 'wp_get_attachment_image_src', [ $this, 'serve_webp' ], PHP_INT_MAX, 4 );

            // Modify srcset metadata for WebP.
            add_filter( 'wp_calculate_image_srcset_meta', [ $this, 'modify_srcset_for_webp' ], PHP_INT_MAX, 4 );

            // Prevent WooCommerce from resizeing images on the fly.
            add_filter( 'woocommerce_image_sizes_to_resize', '__return_empty_array' );

            // Disable photon.
            add_filter( 'jetpack_photon_skip_image', '__return_true' );

            // Don't use remotely-resized images with Jetpack Photon.
            add_filter( 'jetpack_photon_override_image_downsize', '__return_true', 19 );

            // Force 1:1 size for single product thumbnail.
            // @see  force_square_woocommerce_single()
            add_filter( 'woocommerce_get_image_size_single', [ $this, 'force_square_woocommerce_single' ] );

            // Force woocommerce single on single product page.
            // @see force_woocommerce_single()
            add_filter( 'woocommerce_gallery_image_size', [ $this, 'force_woocommerce_single' ], PHP_INT_MAX );
        }

        /**
         * Determine whether the given size is selected.
         *
         * @param string $size
         *
         * @return bool
         */
        public function isRegeneratableSize( $sizes )
        {
            if ( ! is_array( $sizes ) ) {
                $sizes = (array)$sizes;
            }

            $selected_sizes = apply_filters( 'wp_sir_sizes', wp_sir_get_settings()[ 'sizes' ] );

            return count( array_intersect( $sizes, $selected_sizes ) ) === count( $sizes );
        }

        /**
         * Use 1:1 for single size when selected.
         *
         * @param string|array
         *
         * @return array
         */
        public function force_square_woocommerce_single( $size )
        {
            if ( ! $this->isRegeneratableSize( [ 'woocommerce_single', 'shop_single' ] ) ) {
                return $size;
            }

            if ( $size[ 'width' ] && ! $size[ 'height' ] ) {
                $size[ 'height' ] = $size[ 'width' ];
            }

            return $size;
        }

        /**
         * Force woocommerce_single size on single product page.
         *
         * @hook woocommerce_gallery_image_size
         *
         * @param string $size
         *
         * @return string
         */
        public function force_woocommerce_single( $size )
        {
            if ( ! apply_filters( 'wp_sir_force_woocommerce_single', true ) ) {
                return $size;
            }

            if ( $this->isRegeneratableSize( [ 'woocommerce_single', 'shop_single' ] ) ) {
                return 'woocommerce_single';
            }

            return $size;
        }

        /**
         * Proceed resize.
         *
         * @param array $metadata
         * @param int $imageId
         *
         * @return array
         */

        public function process_image( $metadata, $imageId )
        {

            try {
                if ( ! wp_sir_get_settings()[ 'enable' ] ) {
                    return $metadata;
                }

                /* __BEGIN_LITE__ */
                if ( Quota::isExceeded() ) {
                    return $metadata;
                }
                /* __END_LITE__ */

                $imageMeta = new Image_Meta( $imageId, $metadata );

                if ( ! $this->isProcessable( $imageId, $imageMeta ) ) {
                    return $metadata;
                }

                $this->checkMemoryLimit();

                $imageManager = $this->getImageManager();

                $originalImage = $imageManager->make( $imageMeta->getOriginalFullPath() );

                $imageMeta->setMimeType( $originalImage->mime() );

                $this->resetTimeLimit();

                $originalImage->filter( new Trim_Filter( $imageMeta ) );

                /* __BEGIN_PRO__ */
/* __END_PRO__ */


                $imageMeta->setBackup();
                
                $imageMeta->clearSizes();

                $regeneratableSizes = $this->getRegeneratableSizes();

                $matchedSizes = [];

                foreach ( $regeneratableSizes as $sizeName => $sizeData ) {
                    $this->resetTimeLimit();

                    // Don't reprocess sizes with same width/height
                    // as this will output the same file.
                    $sizeHash = $sizeData['width']  . '|' . $sizeData['height'];

                    if( isset( $matchedSizes[$sizeHash] ) ){
                        $imageMeta->setSizeData( $sizeName, $imageMeta->getSizeData($matchedSizes[$sizeHash]) );
                        continue;
                    }

                   $originalImageCopy = clone $originalImage;

                   $thumbnail = $originalImageCopy
                                ->filter( new Resize_Filter( $sizeData ) )
                                ->filter( new Recanvas_Filter( $imageManager, $sizeData ) );
                    
                    $newThumbnailPath = $this->getNewThumbnailPath( $originalImage->basePath(), $sizeData, $sizeName, $imageId );
                    
                    $thumbnail->save($newThumbnailPath,
                        $this->getJpgQuality()
                    );
                    
                    $imageMeta->setSizeData( $sizeName, [
                        'width'     => $thumbnail->getWidth(),
                        'height'    => $thumbnail->getHeight(),
                        'file'      => $thumbnail->basename,
                        'mime-type' => $thumbnail->mime(),
                    ] );

                    $matchedSizes[$sizeHash] = $sizeName;

                    $newWebpThumbnailPath = $imageMeta->getSizeFullPath( $sizeName, 'webp' );

                    /* __BEGIN_PRO__ */
/* __END_PRO__ */

                        
                    $thumbnail->destroy();
                }

                $originalImage->destroy();

                $time = time();
                $imageMeta->setMetaItem( '_processed_at', $time );
                update_post_meta( $imageId, '_processed_at', $time );

                /* __BEGIN_LITE__ */
                Quota::consume( $imageId );

                /* __END_LITE__ */

                return $imageMeta->toArray();
            } catch ( Exception $e ) {
                wp_send_json_error( [
                    'message' => "Smart Image Resize: {$e->getMessage()}",
                ] );
            }
        }


        /**
         * Returns quality setting.
         * @return int
         */
        public function getJpgQuality()
        {
            if ( wp_sir_get_settings()[ 'jpg_convert' ] ) {
                return 100 - absint( wp_sir_get_settings()[ 'jpg_quality' ] );
            }

            return null;
        }

        /**
         * Return sizes to resize.
         *
         * @return array
         */
        public function getRegeneratableSizes()
        {
            $sizes = wp_cache_get( 'wp_sir_regeneratable_sizes' );

            if ( $sizes && is_array( $sizes ) && ! empty( $sizes ) ) {
                return apply_filters( 'wp_sir_sizes', $sizes );
            }

            $sizeNames = apply_filters( 'wp_sir_sizes', wp_sir_get_settings()[ 'sizes' ] );

            $sizes = [];

            foreach ( $sizeNames as $sizeName ) {
                $size = wp_sir_get_size_dimensions( $sizeName );

                if( ! empty($size) ){
                    $sizes[ $sizeName ]  = $size;
                }
            }

            wp_cache_add( 'wp_sir_regeneratable_sizes', $sizes );

            return apply_filters( 'wp_sir_sizes', $sizes );
        }

        /**
         * Return canvas color.
         *
         * @return string|null
         */
        public function get_canvas_color()
        {
            return sanitize_hex_color( wp_sir_get_settings()[ 'bg_color' ] ) ?: null;
        }

        /**
         * Match src.
         *
         * @param array $metadata
         * @param array $imageArr
         * @param string $imageSrc
         * @param int $attachmentId
         *
         * @return array
         */
        public function modify_srcset_for_webp( $metadata, $imageArr, $imageSrc, $attachmentId )
        {
            try {
                if ( ! $this->shouldServeWebP() ) {
                    return $metadata;
                }

                $imageMeta = new Image_Meta( $attachmentId, $metadata );

                if ( ! $imageMeta->isValid ) {
                    return $metadata;
                }

                foreach ( $imageMeta->getSizes( true ) as $sizeName ) {
                    $imageMeta->setSizeExtension( $sizeName, 'webp' );
                }

                return $imageMeta->toArray();
            } catch ( Exception $e ) {
                return $metadata;
            }
        }

        /**
         * @param string $sourceFullPath
         * @param array $size
         *
         * @return string
         */
        public function getNewThumbnailPath( $sourceFullPath, $size, $sizeName, $imageId )
        {
            $sourceInfo = pathinfo( $sourceFullPath );

            if ( wp_sir_get_settings()[ 'jpg_convert' ]
                 && ! in_array( $sourceInfo[ 'extension' ], [ 'jpg', 'jpeg' ] ) ) {
                $extension = 'jpg';
            } else {
                $extension = $sourceInfo[ 'extension' ];
            }
            $basename = sprintf(
                '%s-%dx%d.%s',
                $sourceInfo[ 'filename' ],
                $size[ 'width' ],
                $size[ 'height' ],
                $extension
            );

          
            $path = trailingslashit( $sourceInfo[ 'dirname' ] ) . $basename;
            return apply_filters( 'wp_sir_thumbnail_save_path', $path, $sizeName, $imageId);
        }

       

        /**
         * Use WebP if enabled and supported by the browser.
         * Fallback to standard format.
         *
         * @param array|false $image
         * @param int $attachmentId
         * @param string|array $size
         * @param bool $icon
         *
         * @return array|false
         */
        public function serve_webp( $image, $attachmentId, $size, $icon )
        {

            if ( $icon || ! is_array( $image ) || ! $this->shouldServeWebP() ) {
                return $image;
            }

            try {

                $imageMeta = new Image_Meta( $attachmentId, wp_get_attachment_metadata( $attachmentId ) );

                if ( ! $imageMeta->isValid ) {
                    return $image;
                }

                if ( is_array( $size ) ) {

                    $intermediate = image_get_intermediate_size( $attachmentId, $size );

                    $size = $imageMeta->findSizeByFile( $intermediate );

                    if ( empty( $size ) ) {
                        return $image;
                    }
                }

                if ( $imageMeta->hasSize( $size ) ) {

                    $webpFullPath = $imageMeta->getSizeFullPath( $size, 'webp' );

                    if ( ! file_exists( $webpFullPath ) && is_readable( $imageMeta->getOriginalFullPath() ) ) {

                        $this->getImageManager()->make( $imageMeta->getSizeFullPath( $size ) )
                             ->filter( new Webpify_Filter( $webpFullPath ) )
                             ->destroy();
                    }

                    if( ! file_exists( $webpFullPath) ){
                        return $image;
                    }

                    $sizeData = $imageMeta->getSizeData( $size, true );

                    return [
                        $imageMeta->getSizeUrl( $size, 'webp' ),
                        $sizeData[ 'width' ],
                        $sizeData[ 'height' ],
                    ];
                }
            } catch ( Exception $e ) {
            }

            return $image;
        }

        /**
         * Determine whether to load WebP images.
         *
         * @return bool
         */
        public function shouldServeWebP()
        {
            if ( ! wp_sir_get_settings()[ 'enable_webp' ] ) {
                return false;
            }

            if( ! Env::supports_webp() ){
                return false;
            }
            
            if ( ! Request::is_front_end() ) {
                return false;
            }

            return Env::browser_supposts_webp();
        }
    }

endif;
