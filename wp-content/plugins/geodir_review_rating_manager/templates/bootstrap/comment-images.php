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
<div id="place-gallery-<?php echo $comment_id; ?>" class="geodir-images row row-cols-1 row-cols-md-3 mt-3 mb-n4 border-top pt-3">
	<?php
	$image_size = 'large';
	$link_tag_open = "<a href='%s' class='geodir-lightbox-image embed-has-action embed-responsive embed-responsive-16by9 d-block' data-lity>";
	$link_tag_close = "<i class=\"fas fa-search-plus\" aria-hidden=\"true\"></i></a>";
	foreach( $comment_imgs as $image ) {
		echo '<div class="col mb-4">';
		echo '<div class="card m-0 p-0 overflow-hidden">';
		$img_tag = geodir_get_image_tag($image,$image_size, '',' embed-responsive-item embed-item-cover-xy ' );
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


		$title = '';
		$caption = '';
		if( !empty($image->title)){
			$title = esc_attr( stripslashes_deep( $image->title ) );
		}

		if( !empty( $image->caption ) ) {
			$caption .= esc_attr( stripslashes_deep( $image->caption ) );
		}

		if($title || $caption){
			?>
			<div class="carousel-caption d-none d-md-block p-0 m-0 py-1 w-100 rounded-bottom sr-only" style="bottom: 0;left:0;background: #00000060">
				<h5 class="m-0 p-0 h6 font-weight-bold text-white"><?php echo $title;?></h5>
				<p class="m-0 p-0 h6 text-white"><?php echo $caption;?></p>
			</div>
			<?php
		}

		echo '</div>';
		echo '</div>';

	}
	?>
</div>