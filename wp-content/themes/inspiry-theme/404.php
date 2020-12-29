<?php


get_header(); ?>

<div id="primary" class="content-area row-container not-found">
	<div id="content" class="site-content" role="main">

		<header class="page-header">
			<h1 class="lg-font-sz center-align regular margin-row"><?php _e( 'Not Found', 'twentythirteen' ); ?></h1>
		</header>

		<div class="page-wrapper">
			<div class="page-content">
				<h2 class="roboto-font center-align font-s-medium regular">
					<?php _e( 'This is somewhat embarrassing, isn’t it?', 'twentythirteen' ); ?></h2>
				<p class="roboto-font center-align font-s-regular regular">
					<?php _e( 'It looks like nothing was found at this location. Maybe try a search?', 'twentythirteen' ); ?>
				</p>

				<?php get_search_form(); ?>
			</div><!-- .page-content -->
		</div><!-- .page-wrapper -->

	</div><!-- #content -->
</div><!-- #primary -->


<?php
global $post;

// Get WordPress' media upload URL
$upload_link = esc_url( get_upload_iframe_src( 'image', $post->ID ) );

// See if there's a media id already saved as post meta
$your_img_id = get_post_meta( $post->ID, '_your_img_id', true );

// Get the image src
$your_img_src = wp_get_attachment_image_src( $your_img_id, 'full' );

// For convenience, see if the array is valid
$you_have_img = is_array( $your_img_src );
?>

<!-- Your image container, which can be manipulated with js -->
<div class="custom-img-container">
    <?php if ( $you_have_img ) : ?>
        <img src="<?php echo $your_img_src[0] ?>" alt="" style="max-width:100%;" />
    <?php endif; ?>
</div>

<!-- Your add & remove image links -->
<p class="hide-if-no-js">
    <a class="upload-custom-img <?php if ( $you_have_img  ) { echo 'hidden'; } ?>" 
       href="<?php echo $upload_link ?>">
        <?php _e('Set custom image') ?>
    </a>
    <a class="delete-custom-img <?php if ( ! $you_have_img  ) { echo 'hidden'; } ?>" 
      href="#">
        <?php _e('Remove this image') ?>
    </a>
</p>

<!-- A hidden input to set and post the chosen image id -->
<input class="custom-img-id" name="custom-img-id" type="hidden" value="<?php echo esc_attr( $your_img_id ); ?>" />

<script>
	jQuery(function($){

// Set all variables to be used in scope
var frame,
	metaBox = $('#meta-box-id.postbox'), // Your meta box id here
	addImgLink = metaBox.find('.upload-custom-img'),
	delImgLink = metaBox.find( '.delete-custom-img'),
	imgContainer = metaBox.find( '.custom-img-container'),
	imgIdInput = metaBox.find( '.custom-img-id' );

// ADD IMAGE LINK
addImgLink.on( 'click', function( event ){
  
  event.preventDefault();
  
  // If the media frame already exists, reopen it.
  if ( frame ) {
	frame.open();
	return;
  }
  
  // Create a new media frame
  frame = wp.media({
	title: 'Select or Upload Media Of Your Chosen Persuasion',
	button: {
	  text: 'Use this media'
	},
	multiple: false  // Set to true to allow multiple files to be selected
  });

  
  // When an image is selected in the media frame...
  frame.on( 'select', function() {
	
	// Get media attachment details from the frame state
	var attachment = frame.state().get('selection').first().toJSON();

	// Send the attachment URL to our custom image input field.
	imgContainer.append( '<img src="'+attachment.url+'" alt="" style="max-width:100%;"/>' );

	// Send the attachment id to our hidden input
	imgIdInput.val( attachment.id );

	// Hide the add image link
	addImgLink.addClass( 'hidden' );

	// Unhide the remove image link
	delImgLink.removeClass( 'hidden' );
  });

  // Finally, open the modal on click
  frame.open();
});


// DELETE IMAGE LINK
delImgLink.on( 'click', function( event ){

  event.preventDefault();

  // Clear out the preview image
  imgContainer.html( '' );

  // Un-hide the add image link
  addImgLink.removeClass( 'hidden' );

  // Hide the delete image link
  delImgLink.addClass( 'hidden' );

  // Delete the image id from the hidden input
  imgIdInput.val( '' );

});

});
</script>

<?php get_footer(); ?>