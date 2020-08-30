<?php

namespace WP_Smart_Image_Resize\Image_Filters;

use Intervention\Image\Filters\FilterInterface;
use \Intervention\Image\Image;

class Resize_Filter implements FilterInterface
{

    /**
     * The target thumbnail width/height.
     * @var array $size
     */
    protected $size;


    public function __construct( $size )
    {
        $this->size = $size;
    }

    /**
     * @param \Intervention\Image\Image $image
     *
     * @return \Intervention\Image\Image
     */
    public function applyFilter( Image $image )
    {
        $image->resize( $this->size[ 'width' ], $this->size[ 'height' ], function (
            $constraint
        ) {
            // Preserve the original aspect-ratio of the given image.
            $constraint->aspectRatio();
            // Let user decide whether to upscale image.
            // By default, upscaling image is disabled.
            if (  ! apply_filters( 'wp_sir_maybe_upscale', true ) ) {
                $constraint->upsize();
            }
        } );

        return $image;

    }
}