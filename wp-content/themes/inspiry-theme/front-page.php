<?php 
/* Template Name: Places * Template Post Type: post*/ /*The template for displaying full width single posts. */
get_header(); 
echo "<h1>This is a places post</h1>";
echo do_shortcode('[facetwp facet="categories"]');
get_footer(); 
?>