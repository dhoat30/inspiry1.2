<?php 
get_header(); 

while(have_posts()){ 
    the_post(); 

    the_title(); 
    the_content();
    echo get_the_id();
}


// Find connected pages
$connected = new WP_Query( array(
  'connected_type' => 'gd_place_to_gd_list',
  'connected_items' => get_queried_object_id(),
  'nopaging' => true,
) );

// Display connected pages
if ( $connected->have_posts() ) :
?>
<h3>Related pages:</h3>
<ul>
<?php while ( $connected->have_posts() ) : $connected->the_post(); ?>
    <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
    <li><a href="<?php the_permalink(); ?>"><?php echo get_the_id(); ?></a></li>
<?php endwhile; ?>
</ul>

<?php 
// Prevent weirdness
wp_reset_postdata();

endif;


get_footer();

?>