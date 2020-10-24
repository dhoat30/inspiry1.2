<?php
/**
 * Comment Images.
 *
 * @ver 1.0.0
 *
 * @var int  $comment_id The comment id.
 * @var array  $comment_imgs An array of image objects.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div id="place-gallery-<?php echo $comment_id; ?>" class="place-gallery geodir-image-container">
	<div class="clearfix reviews_rating_images_all_images">
		<ul class="geodir-gallery geodir-images clearfix">
			<?php
			$image_size = 'medium';
			$link_tag_open = "<a href='%s' class='geodir-lightbox-image' data-lity>";
			$link_tag_close = "<i class=\"fas fa-search-plus\" aria-hidden=\"true\"></i></a>";
			foreach( $comment_imgs as $image ) {
				echo '<li>';
				$img_tag = geodir_get_image_tag($image,$image_size );
				$meta = isset($image->metadata) ? maybe_unserialize($image->metadata) : '';
				$img_tag =  wp_image_add_srcset_and_sizes( $img_tag, $meta , 0 );

				// image link
				$link = geodir_get_image_src($image, 'large');

				// ajaxify images
				$img_tag = geodir_image_tag_ajaxify($img_tag);

				// output image
				echo $link_tag_open ? sprintf($link_tag_open,esc_url($link)) : '';
				echo $img_tag;
				echo $link_tag_close;

				echo '</li>';

			}

			?>
		</ul>
	</div>
</div>