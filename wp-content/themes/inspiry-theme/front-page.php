<?php 
/* Template Name: Places * Template Post Type: post*/ /*The template for displaying full width single posts. */
get_header(); 

?>

<?php 
    $product = new WP_Query(array (
        'post_type'=> 'gd_place'
    )); 

    while($product->have_posts()){ 
        $product->the_post(); 
        the_title();  ?>
        <br>
        
            <?php 
            the_post_thumbnail( );
    }
?>

<h1>home page</h1>
<?php 

get_footer(); 
?>