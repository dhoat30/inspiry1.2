<?php

namespace WP_Smart_Image_Resize\Filters;

use WP_Smart_Image_Resize\Utilities\Request;

class Image_Source extends Base_Filter
{
    public function listen()
    {
        add_filter('wp_get_attachment_image_src', [$this, 'force_intermediate_size'], PHP_INT_MAX, 4);
    }

    /**
     * Remove original image from $sources array.
     *
     * @hook wp_calculate_image_srcset
     *
     * @param $sources
     * @param $sizes
     * @param $image_src
     * @param $image_meta
     * @param $attachment_id
     *
     * @return void
     */
    public function force_intermediate_size($image, $attachment_id, $size, $icon)
    {
        if (! apply_filters('wp_sir_force_intermediate_size', false)) {
            return $image;
        }

        if (! Request::is_front_end()) {
            return $image;
        }

        if ($icon) {
            return $image;
        }

        // Make sure image is not corrupted.
        if (! $image || ! is_array($image) || empty($image)) {
            return $image;
        }

        list($url, $size_width, $size_height, $is_intermediate) = $image;

        // Already an intermediate size, abort.
        if ($is_intermediate) {
            return $image;
        }

        // Find the closest thumbnail.
        $intermediate = $this->get_intermediate_size($attachment_id, $size);

        if (! $intermediate) {
            return $image;
        }

        return [
            $intermediate['url'],
            $intermediate['height'],
            $intermediate['width'],
            true,
        ];
    }

    /**
     * Find the closest thumbnail for the given size.
     *
     * @param int $attachment_id
     * @param array $size [width, height]
     *
     * @return array
     */
    private function get_intermediate_size($attachment_id, $size)
    {
        $image = image_get_intermediate_size($attachment_id, $size);

        // Fallbacks to `woocommerce_thumbnail`.
        if (! is_array($image) || empty($image)) {
            $image = image_get_intermediate_size($attachment_id, apply_filters('wp_sir_fallback_intermediate_size', 'woocommerce_thumbnail'));
        }

        return ! is_array($image) || empty($image) ? false : $image;
    }
}
