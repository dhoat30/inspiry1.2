<?php

namespace WP_Smart_Image_Resize;

trait Processable_Trait
{

    /**
     * Check whether the given image is processable.
     *
     * @param int $imageId
     * @param \WP_Smart_Image_Resize\Image_Meta $imageMeta
     *
     * @return bool
     */
    function isProcessable( $imageId, $imageMeta )
    {
        if ( ! $imageMeta->isValid ) {
            return false;
        }
        // Since the original image doesn't exist
        // we cannot process it.
        if ( ! is_readable( $imageMeta->getOriginalFullPath() ) ) {
            return false;
        }

        // Process any request with `_processable_image` parameter.
        // This can be used by developers to integrate with the plugin.
        if ( isset( $_REQUEST[ '_processable_image' ] ) ) {
            return filter_var( $_REQUEST[ '_processable_image' ], FILTER_VALIDATE_BOOLEAN );
        }
        // Process if post type featured image/gallery image is being uploaded.
        // This includes WC REST API requests as well.
        if ( wp_sir_is_post_image_upload( $imageId ) ) {
            return true;
        }

        // Process if term image is being uploaded.
        if ( wp_sir_is_term_image_upload() ) {
            return true;
        }

        // Maybe process images uploaded through Media Library.
        if ( wp_sir_is_media_library_upload() ) {
            return true;
        }

        // Frontend upload (including Dokan).
        if ( wp_sir_is_frontend_upload() ) {
            return true;
        }

        return wp_sir_is_processable( $imageId );
    }
}