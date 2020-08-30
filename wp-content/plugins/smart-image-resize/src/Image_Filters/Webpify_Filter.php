<?php

namespace WP_Smart_Image_Resize\Image_Filters;

use Intervention\Image\Filters\FilterInterface;
use Intervention\Image\Image;
use Exception;

class Webpify_Filter implements FilterInterface
{

    /**
     * The output image full path.
     *
     * @var string $path
     */
    protected $path;

    public function __construct( $path )
    {
        $this->path = $path;
    }

    public function applyFilter( Image $image )
    {
        if ( ! wp_sir_get_settings()[ 'enable_webp' ] ) {
            return $image;
        }

        try {
            $imageCopy = clone $image;
            $imageCopy->save( $this->path )->destroy();
        } catch ( Exception $e ) {
        }

        return $image;

    }
}