<?php 
    get_header(); 

    echo "<h1>before main content</h1>";
    $post_id = get_the_ID(); 
    echo get_favorites_button($post_id);
?>
<h1>hello</h1>
<?php
    get_footer(); 

?>