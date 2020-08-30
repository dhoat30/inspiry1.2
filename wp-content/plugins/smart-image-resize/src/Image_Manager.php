<?php

namespace WP_Smart_Image_Resize;

use Exception;
use Intervention\Image\ImageManager;
use WP_Smart_Image_Resize\Utilities\Env;

class Image_Manager extends ImageManager
{
    /**
     * @param string $driver Supported: gd, imagick
     */
    public function __construct()
    {   
        
      $driver = apply_filters('wp_sir_driver', $this->validateDriver());

       parent::__construct(compact('driver'));
    }

    /**
     * We hope Imagick is compiled with libwebp
     * This will make image manipulation fast.
     * 
     * We'll try to check if Imagick can be used
     * whether WebP setting is enabled.
     * 
     * @return bool
     */
    private function validateDriver(){

            if( Env::imagick_loaded() && ! wp_sir_get_settings()[ 'enable_webp' ] ){
                return 'imagick';
            }

            if( Env::imagick_supports_webp() && wp_sir_get_settings()[ 'enable_webp' ]){
                return 'imagick';
            }

            if(Env::imagick_loaded() && ! Env::gd_loaded()){
                return 'imagick';
            }

            if(Env::imagick_loaded() && ! Env::gd_supports_webp() && wp_sir_get_settings()[ 'enable_webp' ]){
                return 'imagick';
            }

            // Unfortunatly, imagick cannot be used.
            return 'gd';
    }
    /**
     * Check if the manager is using Imagick driver.
     * 
     * @return bool
     */

    public function usingImagick()
    {
        return $this->config['driver'] === 'imagick';
    }

    /**
     * Check if the manager is using GD driver.
     * 
     * @return bool
     */
    public function usingGD()
    {
        return $this->config['driver'] === 'gd';
    }
}
