<?php

namespace WP_Smart_Image_Resize\Events;

use WP_Smart_Image_Resize\Image_Meta;

class Image_Inserted extends Base_Event
{
    public function listen()
    {
       add_action( 'add_attachment', [ $this, 'setMetadata' ] );
    }

    /**
     * Maybe generate thumbnails for the given image.
     * 
     * @param int $imageId
     *
     * @return void
     */
    public function setMetadata( $imageId )
    {
        if ( ! apply_filters( 'wp_sir_force_thumbnails_regeneration', false ) ) {
            return;
        }

        require_once( ABSPATH . 'wp-admin/includes/image.php' );

        $data = wp_generate_attachment_metadata( $imageId, get_attached_file( $imageId ) );
        $imageMeta = new Image_Meta( $imageId, $data );

        if ( $imageMeta->isValid && $imageMeta->getMetaItem('_processed_at')) {
            wp_update_attachment_metadata( $imageId, $data );
        }

    }
}
