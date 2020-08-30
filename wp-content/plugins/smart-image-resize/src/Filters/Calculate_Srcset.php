<?php

namespace WP_Smart_Image_Resize\Filters;

class Calculate_Srcset extends Base_Filter
{
    public function listen()
    {
        add_filter('wp_calculate_image_srcset', [$this, 'remove_full_size'], 10, 5);
    }

    /**
     * We need to remove original image from $sources array since it is not resized.
     *
     * @param $sources
     * @param $sizes
     * @param $image_src
     * @param $image_meta
     * @param $attachment_id
     *
     * @return void
     */
    public function remove_full_size($sources, $sizes, $image_src, $image_meta, $attachment_id)
    {
        if (! apply_filters('wp_sir_remove_full_size_from_srcset', true)) {
            return $sources;
        }

        if (empty($sources) || ! is_array($image_meta)) {
            return $sources;
        }

        if (empty($image_meta) || ! is_array($image_meta)) {
            return $sources;
        }

        try {
            unset($sources[$image_meta['width']]);
        } catch (\Exception $e) {
        }

        return $sources;
    }
}
