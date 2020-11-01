<?php 
/* Template Name: Places * Template Post Type: post*/ /*The template for displaying full width single posts. */
get_header(); 

?>



<h1>home page</h1>
<div class="save-icons-container">
    <div class="wish-list-icon-container">
        <i class="fal fa-heart"></i>
    </div>
    <div class="design-board-save-btn-container">
    <i data-exists='<?php echo $existStatus?>' class="fal fa-plus open-board-container" ></i>
    </div>
</div>
<?php 

get_footer(); 
?>