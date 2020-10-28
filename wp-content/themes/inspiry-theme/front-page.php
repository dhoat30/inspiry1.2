<?php 
/* Template Name: Places * Template Post Type: post*/ /*The template for displaying full width single posts. */
get_header(); 
echo "<h1>This is a places post</h1>";

?>

<?php 

$query = new WP_Query( array(
    'post_type' => 'gd_place',
    'posts_per_page' => '5'
 
    
    
));


print_r($categories);
var_dump($categories);
while($query->have_posts()){ 
    $query->the_post(); 
    the_title();  ?> <br><br> <?php
}
?>

<?php 

get_footer(); 
?>