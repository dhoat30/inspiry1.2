<?php

namespace WP_Smart_Image_Resize\Filters;

class Background_Thumbnails_Regeneration extends Base_Filter
{
    public function listen()
    {
        add_filter('woocommerce_regenerate_images_intermediate_image_sizes', [$this, 'add_regeneratable_sizes']);
    }

    /**
     * Hook into WC regenerate tool to add user selected sizes.
     *
     * @param $sizes
     *
     * @return array
     */
    public function add_regeneratable_sizes($sizes)
    {
        $processable_sizes = apply_filters('wp_sir_sizes', wp_sir_get_settings()['sizes']);

        return array_merge($sizes, $processable_sizes);
    }
}
