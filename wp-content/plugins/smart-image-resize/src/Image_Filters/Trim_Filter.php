<?php

namespace WP_Smart_Image_Resize\Image_Filters;

use \Intervention\Image\Filters\FilterInterface;
use Exception;
use Intervention\Image\Image;

class Trim_Filter implements FilterInterface
{

    /**
     * The image meta helper instance.
     * @var \WP_Smart_Image_Resize\Image_Meta $imageMeta
     */
    protected $imageMeta;

    public function __construct( $imageMeta )
    {
        $this->imageMeta = $imageMeta;
    }

    /**
     * Set trimmed image dimensions.
     */
    private function setNewDimensions( $image )
    {
        $this->imageMeta->setMetaItem( '_trimmed_width', $image->getWidth() );
        $this->imageMeta->setMetaItem( '_trimmed_height', $image->getHeight() );
    }

    /**
     * @param \Intervention\Image\Image $image
     *
     * @return \Intervention\Image\Image
     */
    public function applyFilter( Image $image )
    {
        if ( ! wp_sir_get_settings()[ 'enable_trim' ] ) {

            // Chances the trim feature was re-disabled.
            // In this case, we need revert to original dimensions
            // to prevent zoomed image from being stretshed.
            $this->setNewDimensions( $image );

            return $image;
        }

        try {

            $image->trim(
                apply_filters( 'wp_sir_trim_base', null ),
                apply_filters( 'wp_sir_trim_away', null ),
                apply_filters( 'wp_sir_trim_tolerance', 5 ),
                apply_filters( 'wp_sir_trim_feather', 3 )
            );

        } catch ( Exception $e ) {
        }

        // Change to new dimensions
        // or revert to original ones. 
        $this->setNewDimensions( $image );

        return $image;
    }
}