<?php

/**
 * @var string $heading     The heading for the list
 * @var array  $links       Links to add the product to lists. Each link is an associative array with the keys:
 *                              label - The name of the list
 *                              url   - The URL to add the product to the list
 * @var string $create_list The link and template to create a new list
 * @version 1.0.0
 */
?>
	<div class="save-icons-container">
		<div class="wish-list-icon-container">
			<i class="fal fa-heart">
				<div class="product-pages overlay">
					<ul class="bc-pdp-wish-lists wish-list-container" data-js="bc-pdp-wish-lists">
					<i class="fal fa-times"></i>
						<div class="column-s-font margin-elements">Wishlist</div>
						<div class="loader-icon"></div>
						<div class="loader-confirmation">Added</div>
						<?php foreach ( $links as $link ) { ?>
							<li class="bc-wish-lists-item"><a href="<?php echo esc_url( $link['url'] ); ?>" class="bc-wish-list-item-anchor"><?php echo esc_html( $link['label'] ); ?></a></li>
						<?php } ?>
						<li class="bc-wish-lists-item"><?php echo $create_list; ?></li>
					</ul>
				</div>
			</i>
			
			

		</div>
		<div class="design-board-save-btn-container" data-tracking-data='{"post_id":"<?php the_id();?>","name":"<?php echo get_the_title(get_the_id()); ?>"}' <?php echo $link_attributes; ?>>
		<i data-exists='<?php echo $existStatus?>' class="fal fa-plus open-board-container" ></i>
		</div>


	</div>
	


