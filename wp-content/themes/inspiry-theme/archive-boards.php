<?php 
get_header(); 
?>
<div class="body-container archive">

<div class="row-container board-page board-archive">
    <h1 class="lg-font-sz playfair-fonts regular">Design Boards</h1>
    <?php 
        $boardLoop = new WP_Query(array(
            'post_type' => 'boards', 
            'post_parent' => 0, 
            'posts_per_page' => -1, 
            'author' => get_current_user_id()
        ));
        ?>
        <div class="font-s-regular roboto-font post-count light-grey font-s-med">
            <?php  echo $boardLoop->found_posts;?>
            boards
        </div>
        <div class="create-board-btn-container">
            <a class='create-board rm-txt-dec button btn-dk-green'><i class="fal fa-pencil-ruler"></i> Create New Board </a> 
            
        </div>
    <div class="archive-cards-flex-container">
    
        <?php
        while($boardLoop->have_posts()){
            $boardLoop->the_post(); 
            ?>  
                
            
                
                <div class="board-card-archive" data-imageexists=<?php echo get_field('image_upload_exists');?>>
                    <i class="fas fa-ellipsis-h option-icon"></i>
                    <div class="pin-options-container box-shadow">
                        <ul class="dark-grey">
                            <li class="edit-board" data-pinid='<?php the_ID();?>'><i class="fal fa-edit"></i> Edit Board</li>
                            <li class="delete-board-btn" data-pinid='<?php the_ID();?>'><i class="fal fa-trash"></i> Delete</li>
                        </ul>
                    </div>

                    <a class="design-board-card rm-txt-dec" class="rm-txt-dec" href="<?php the_permalink(); ?>">   
                    
                        <?php 
                        //GET THE CHILD ID
                            //Instead of calling and passing query parameter differently, we're doing it exclusively
                            $all_locations = get_pages( array(
                                'post_type'         => 'boards', //here's my CPT
                                'post_status'       => array( 'private', 'pending', 'publish') //my custom choice
                            ) );

                            //Using the function
                            $parent_id = get_the_id();
                            $inherited_locations = get_page_children( $parent_id, $all_locations );
                            $pinCount = count($inherited_locations);

                            // echo what we get back from WP to the browser (@bhlarsen's part :) )
                            $child_id = $inherited_locations[0]->ID;
                            $childThumbnailImageID =  get_field('saved_image_id', $child_id); 
                            $childThumbnail = get_field('saved_project_id', $child_id); 
                            ?>

                            <?php 
                                if(get_the_post_thumbnail($childThumbnail)){
                                    ?>
                                        <div class="img-div"><?php echo get_the_post_thumbnail($childThumbnail);?></div>
                                    <?php
                                    
                                }
                                elseif ($childThumbnailImageID){
                                    $img =  wp_get_attachment_image_src($childThumbnailImageID, 'large');

                                    ?>
                                        <div class="img-div">
                                        <img src="<?php echo $img[0] ?>" alt="<?php echo get_the_title();?>">
                                        </div>
                                    <?php
                                }
                                else{
                                    ?>
                                     <div class="img-div"><img src="<?php echo get_site_url(); ?>/wp-content/uploads/2020/12/icon-card@2x.jpg" alt=""></div>

                                    <?php
                                }
                            ?>
                        <h5 class="font-s-med board-title"><?php the_title();?></h5>
                        <div class="pin-info">
                            <div class="padlock">
                            <?php $postStatus =  get_post_status(); 
                                if($postStatus == 'private'){
                                    ?>
                                        <i class="fal fa-lock"></i>
                                    <?php
                                }
                                else{
                                    ?>
                                    <i class="fal fa-lock-open"></i>
                                    <?php
                                }
                                ?>
                            </div>
                            
                            <div class="pin-count gray roboto-font thin"><?php echo $pinCount;
                                if($pinCount <= 1){ 
                                    echo ' Pin';
                                }
                                else{
                                    echo ' Pins';
                                }
                            ?></div>
                        </div>
                        

                         <div class="roboto-font font-s-regular"><?php if( '' !== get_post()->post_content ) { 
                               
                                echo get_the_content();
                                 }
                            ?></div>

                    </a>
                                 

                </div>
        
               
            <?php
           
    
        }
        wp_reset_query();
        ?>


    </div>


    <!-- update board container--> 

    <div class="board-overlay overlay project-update-overlay">
                                    

                                    <div class="project-save-form-section project-update" data-postid='20'>
       
                                    <div class="project-save-form-container"> 
                                        <div class="roboto-font regular form-title font-s-med">Create Board</div>
                                        <div class="form-underline"></div>
                                        <div class="form">
                                            <form id="new-board-form-update">
                                               
                                                <label for="name">Give your board a title*</label>
                                                <input type="text" name="board-name" required id='update-board-name' value="">
                                                <label for="description">Description</label>
                                                <textarea name="board-description"  id="update-board-description"  cols="30" rows="10" value=""></textarea>
                                                <div class="toggle-btn-container">
                                                    <label class="tgl tgl-gray" style="font-size:30px">  
                                                      <input id="update-status" type="checkbox" checked />
                                                      <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span>
                                                    </label>
                                                    <div class="toggle-status roboto-font thin"><i class="fal fa-lock"></i> Private
                                                    </div>
                                               
                                                </div>
                                                <div class="toggle-status-info roboto-font font-s-regular regular">
                                                  Private boards cannot be shared with the general public. 
                                                </div>
                                               
                                                <div class="btn-container">
                                                    <button type="button" class="btn cancel-btns"> Cancel</button>
                                                    <button type="button" class="btn btn-dk-green archive-update-btn"> Save</button>
                                                  
                                                    <div class="loader"></div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
    </div>
        <!-- create board container--> 
        <div class="board-overlay overlay">
                                    

                                <div class="project-save-form-section">
   
                                <div class="project-save-form-container"> 
                                    <div class="roboto-font regular form-title font-s-med">Create Board</div>
                                    <div class="form-underline"></div>
                                    <div class="form">
                                        <form id="new-board-form-archive">
                                            <label for="name">Give your board a title*</label>
                                            <input type="text" name="board-name" required id="board-name-archive" >
                                            <label for="description">Description</label>
                                            <textarea name="board-description"  id="board-description-archive" cols="30" rows="10"></textarea>
                                            <div class="toggle-btn-container">
                                                <label class="tgl tgl-gray" style="font-size:30px">  
                                                  <input type="checkbox" checked />
                                                  <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span>
                                                </label>
                                                <div class="toggle-status roboto-font thin"><i class="fal fa-lock"></i> Private
                                                </div>
                                           
                                            </div>
                                            <div class="toggle-status-info roboto-font font-s-regular regular">
                                              Private boards cannot be shared with the general public. 
                                            </div>
                                           
                                            <div class="btn-container">
                                                <button type="button" class="btn cancel-btns"> Cancel</button>
                                                <button type="submit" class="btn btn-dk-green archive-save-btn"> Save</button>
                                              
                                                <div class="loader"></div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
        </div>

        
</div>
</div>
<div class="overlay"></div>                       
<div class="share-icon-container box-shadow">
                        <div class="roboto-font regular font-s-med"> Share this pin </div>
                        <div class="underline"></div>
                        <div>
                            <?php echo do_shortcode('[Sassy_Social_Share  url="http:'.get_the_permalink(get_field('saved_project_id')).'"]');?>
                        </div>
                        <span>X</span>

</div> 

<div class="ajax-result">

</div>

<?php

get_footer();
?>