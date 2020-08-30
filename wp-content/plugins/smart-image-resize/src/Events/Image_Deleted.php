<?php

namespace WP_Smart_Image_Resize\Events;

use WP_Smart_Image_Resize\Image_Meta;

class Image_Deleted extends Base_Event
{
    public function listen()
    {
        add_action( 'delete_attachment', [ $this, 'delete_webp_images' ] );
    }

    /**
     * Delete WebP images.
     *
     * @param int $attachmentId
     *
     * @return void
     */
    public function delete_webp_images( $attachmentId )
    {
        if ( ! wp_attachment_is_image( $attachmentId ) ) {
            return;
        }

        $imageMeta = new Image_Meta( $attachmentId );

        if ( ! $imageMeta->isValid ) {
            return;
        }

        foreach ( $imageMeta->getSizes( true ) as $sizeName ) {
            wp_delete_file( $imageMeta->getSizeFullPath( $sizeName, 'webp' ) );
        }
    }
}
