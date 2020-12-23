 let $ = jQuery; 
 //Design board save button
 class DesignBoardSaveBtn{ 
    constructor(){ 
        this.statusCheck = ''; 
        this.plusBtn = document.querySelectorAll('.design-board-save-btn-container .open-board-container');
        this.boardListItems = $('.choose-board-container .board-list li'); 
        this.checkboxInput = $('.tgl input[type="checkbox"]');
       this.events(); 
       this.fillHeartIcon();  
    }
    //events
    events(){ 
        //public and private check
        //this.checkboxInput.change(this.toggleBtnProcessor)


        $(document).on('click', '.design-board-save-btn-container .open-board-container', this.showChooseBoardContainer);
        //hide choose board container
        $(document).on('click', '.choose-board-container .close-icon', this.hideChooseBoardContainer);

        //show a board form
        $(document).on('click', '.choose-board-container .create-new-board', this.showForm); 
        //hide a board form
        $(document).on('click', '.project-save-form-section .cancel-btn', this.hideForm);

        //create a new board 
        $(document).on('click', '.project-save-form-section .save-btn', this.createBoardFunc); 

        //add to a board
        $(document).on('click', ('.choose-board-container .board-list li'), this.addToBoard); 

        //delete a pin 
        $(document).on('click', '.board-card .delete-btn', this.deletePin);

        //delete Board
        $(document).on('click', '.board-card-archive .delete-board-btn', this.deleteBoard);

        //show icon only in quick view 
        $(document).on('click', '.bc-quickview-trigger--hover-label', this.showIconQuickView); 

        //show icon in the top of product card on archive page on hover
        $('.bc-product-card').hover( this.showPinIconOnHover, this.hidePinIconOnMouseOut); 
       // $('.bc-quickview-trigger--hover').mouseleave( this.hidePinIconOnMouseOut); 
       //$('.post-type-archive-bigcommerce_product .design-board-save-btn-container').hover( this.showPinIconOnHover, this.hidePinIconOnMouseOut); 
       //$('.post-type-archive-bigcommerce_product .design-board-save-btn-container').mouseleave( this.hidePinIconOnMouseOut); 
        //create a board on an archive page
        $(document).on('click', '.board-archive .create-board', this.showCreateBoardArchive)
        $('.board-archive .archive-save-btn').on( 'click', this.createBoardArchive);
       
        //update a board 
        $(document).on('click', '.board-archive .edit-board', this.showUpdateArchive)
        $(document).on('click', '.update-btn', this.updateBoardArchive);

    }
    //update container show
    showUpdateArchive(e){
        let postID = $(e.target).attr('data-pinid');
       
        $('.project-update').show(300);
        $('.project-update-overlay').show(300);

        //clicking a save button 
        
        //hide the overlay and create board container
        $(document).on('click', '.board-archive .cancel-btns', (e)=>{
            $('.board-archive  .project-update').hide(300);
            $('.board-archive .overlay').hide(300); 

            //delete a value from last api call
            $('.board-archive  .project-update input').val('Waiting...');
            $('#update-board-description').val('Waiting...')
        } ); 

        //get post information 
        $.ajax({
            beforeSend: (xhr)=>{
                xhr.setRequestHeader('X-WP-NONCE', inspiryData.nonce)
            },
            url: inspiryData.root_url + '/wp-json/inspiry/v1/board',
            type: 'POST', 
            data: {
                'id': postID
            },
            complete:()=>{
                $('.project-save-form-section .loader').hide();
            },
            success: (response)=>{
                console.log(response)
                //set values in an update form 
                $('#update-board-name').val(response[0].title)
                $('#update-board-description').val(response[0].description)
                if(response[0].status == 'private'){
                    $("#update-status").prop("checked", true);
                    $('.toggle-status').html('<i class="fal fa-lock"></i>  Private'); 
                    $('.toggle-status-info').html('Private boards cannot be shared with the general public.'); 
                }
                else{
                    $("#update-status").prop("checked", false);
                    $('.toggle-status').html('<i class="fal fa-lock-open"></i> Public'); 
                    $('.toggle-status-info').html(' Public boards can be shared with the general public.'); 
                }
               
            }, 
            error: (response)=>{
                console.log(response)
            }
        });
        $('.archive-update-btn').on('click', this.updateBoardArchive); 
    }
    //crate a board on archive page
    //show archive add board container
    showCreateBoardArchive(e){
        $('.board-archive .project-save-form-section').show(300);
        $('.board-archive .board-overlay').show(300); 

        //clicking a save button 
        
        //hide the overlay and create board container
        $(document).on('click', '.board-archive .cancel-btns', (e)=>{
            $('.board-archive  .project-save-form-section').hide(300);
            $('.board-archive .overlay').hide(300); 
        } ); 
    }

    

    //update board function 
    updateBoardArchive(e){ 
        console.log('starting request')
        let statusCheck;
        if($('.tgl input[type="checkbox"]').is(":checked")){
            statusCheck = 'private';
            $('.toggle-status').html('Private');  
        }
        else{
            statusCheck = 'publish'; 
            $('.toggle-status').html('Public'); 
        }
        console.log('this  is a status check' + statusCheck)
        let boardName = $(e.target).closest('.btn-container').siblings('#board-name').val();
   
        let boardDescription; 
        if($(e.target).closest('.btn-container').siblings('#board-description').val()){ 
            boardDescription = $(e.target).closest('.btn-container').siblings('#board-description').val();
        }
        else{ 
            console.log('no description');
        }
       
        e.preventDefault();
        $('.project-save-form-section .loader').show();

       
        $.ajax({
            beforeSend: (xhr)=>{
                xhr.setRequestHeader('X-WP-NONCE', inspiryData.nonce)
            },
            url: inspiryData.root_url + '/wp-json/inspiry/v1/updateBoard',
            type: 'POST', 
            data: {
                'board-id': 13819, 
                'board-name': 'gurpreet2', 
                'board-description': 'desciption of a board',
                'status': 'publish' 

            },
            complete:()=>{
                $('.project-save-form-section .loader').hide();
            },
            success: (response)=>{
                console.log(response)
                if(response){ 
                    console.log(response);
                    //reload a window
                    //location.reload();

                    //show the list board name in the list 
                    $('.choose-board-container .board-list').append(`<li data-board-id=${response}>${boardName}</li>`);
                    //hide the form
                    $('.project-save-form-section').hide();   
                    function addToBoard2(){
                        
                    //add a post into baord
                        let postID = $('.choose-board-container').attr('data-post-id');
                        let postTitle = $('.choose-board-container').attr('data-post-title'); 
                        
                        
            
                    }

                    addToBoard2();


            
                  

                   
                }
            }, 
            error: (response)=>{
            
                console.log('this is a board error');
                console.log(response)
                console.log(response.responseText)
                $('#new-board-form').before(` <div class="error-bg">${response.responseText}</div>`);
            }
        });
        
    }
    //create board function 
    createBoardArchive(e){ 
        console.log('sending a request'); 
        let statusCheck;
        if($('.board-archive .tgl input[type="checkbox"]').is(":checked")){
            statusCheck = 'private'; 
        }
        else{
            statusCheck = 'publish'; 
        }
        console.log('this  is a status check' + statusCheck)
        let boardName = $(e.target).closest('.btn-container').siblings('#board-name-archive').val();
   
        let boardDescription; 
        if($(e.target).closest('.btn-container').siblings('#board-description-archive').val()){ 
            boardDescription = $(e.target).closest('.btn-container').siblings('#board-description-archive').val();
        }
        else{ 
            console.log('no description');
        }
       
        e.preventDefault();
        $('.project-save-form-section .loader').show();

       
        $.ajax({
            beforeSend: (xhr)=>{
                xhr.setRequestHeader('X-WP-NONCE', inspiryData.nonce)
            },
            url: inspiryData.root_url + '/wp-json/inspiry/v1/manageBoard',
            type: 'POST', 
            data: {
                'board-name': boardName, 
                'board-description': boardDescription,
                'status': statusCheck 

            },
            complete:()=>{
                $('.project-save-form-section .loader').hide();
            },
            success: (response)=>{
                console.log(response)
                if(response){ 
                    console.log(response);
                    //reload a window
                     location.reload();

                     //hide overloay
                     $('.board-archive  .project-save-form-section').hide(300);
                        $('.board-archive .overlay').hide(300); 

                    //show the list board name in the list 
                    $('.choose-board-container .board-list').append(`<li data-board-id=${response}>${boardName}</li>`);
                    //hide the form
                    $('.project-save-form-section').hide();   
                    function addToBoard2(){
                        
                    //add a post into baord
                        let postID = $('.choose-board-container').attr('data-post-id');
                        let postTitle = $('.choose-board-container').attr('data-post-title'); 
                        
                        
            
                    }

                    addToBoard2();


            
                  

                   
                }
            }, 
            error: (response)=>{
            
                console.log('this is a board error');
                console.log(response)
                console.log(response.responseText)
                $('#new-board-form').before(` <div class="error-bg">${response.responseText}</div>`);
            }
        });
        
    }
    
    toggleBtnProcessor(e){
        e.preventDefault();
        console.log('this button is working');
        console.log($('.tgl input[type="checkbox"]').val());
        
        
    }
    //functions 
    //show icon on hover on product archive
    showPinIconOnHover(e){ 
        let designBoard = $(e.target).closest('.bc-product-card').find('.design-board-save-btn-container'); 
        designBoard.css('opacity', 1);
    }
    hidePinIconOnMouseOut(e){ 
        let designBoard = $(e.target).closest('.bc-product-card').find('.design-board-save-btn-container');
        designBoard.css('opacity', 0);
      
    }

    showIconQuickView(e){ 
        console.log('working'); 
        console.log($(e.target).closest('.bc-quickview-trigger').siblings('.bc-product__meta').find('.open-board-container'));
        let plusButton = $(e.target).closest('.bc-quickview-trigger').siblings('.bc-product__meta').find('.design-board-save-btn-container');
       
    }


    //show board
    showChooseBoardContainer(e){ 
        let eventPostID; 
        let eventPostTitle;

        let templateNameCheck = $('.bc-product__title').attr('data-archive');
        //check the page and assign the id and title value
       
        
            let eventPostData = $(e.target).closest('.design-board-save-btn-container').attr('data-tracking-data'); 

            //parsing json to javascript object
            eventPostData = JSON.parse(eventPostData);
            eventPostID = eventPostData.post_id
            eventPostTitle = eventPostData.name; 
            

        
        $('.choose-board-container').show(300);
        $('.board-overlay').show(300); 

        let postID = $('.choose-board-container').attr('data-post-id', eventPostID); 
       let postTitle = $('.choose-board-container').attr('data-post-title', eventPostTitle); 
 
    }

    //hide container function 
    hideChooseBoardContainer(){ 
        $('.choose-board-container').hide(300);
        $('.overlay').hide(300); 
    }

    //fill heart icon
    fillHeartIcon(){
        if($('.design-board-save-btn-container i').attr('data-exists') == 'yes'){ 
            $('.design-board-save-btn-container i').addClass('fas fa-heart');
        } 
    }

    //show create boad form
    showForm(){ 
        console.log('create form');
        $('.project-save-form-section').show();
    }

    hideForm(){ 
        $('.project-save-form-section').hide();
    }

    

    //add project to board
    addToBoard(e){
   
        let boardID = $(e.target).attr('data-boardid'); 
        let boardPostStatus = $(e.target).attr('data-postStatus');

        let postID = $('.choose-board-container').attr('data-post-id');
        let postTitle = $('.choose-board-container').attr('data-post-title'); 
    

        //show loader icon
        $(e.target).closest('.board-list-item').find('.loader').addClass('loader--visible');
        $.ajax({
            beforeSend: (xhr)=>{
                xhr.setRequestHeader('X-WP-NONCE', inspiryData.nonce)
            },
            url: inspiryData.root_url + '/wp-json/inspiry/v1/addToBoard',
            type: 'POST', 
            data: {
                'board-id': boardID, 
                'post-id': postID, 
                'post-title': postTitle, 
                'status': boardPostStatus
            },
            complete: () =>{
                $(e.target).closest('.board-list-item').find('.loader').removeClass('loader--visible');
            },
            success: (response)=>{
                console.log('this is a success area')
                if(response){ 
                    console.log(response);
                    $('.project-detail-page .design-board-save-btn-container i').attr('data-exists', 'yes');

                    //fill heart
                    $('.design-board-save-btn-container i').addClass('fas fa-heart');

                }
            }, 
            error: (response)=>{
                console.log('this is an error');
                console.log(response)
                $(e.target).closest('.board-list-item').find('.loader').removeClass('loader--visible');

            }
        });

       
    }
    //delete board
    deleteBoard(e){
       //
       
        let boardID = $(e.target).attr('data-pinid');
       console.log(boardID);
       $(e.target).html('<div class="loader" style="display:block;"></div> ');

      $.ajax({
       beforeSend: (xhr)=>{
           xhr.setRequestHeader('X-WP-NONCE', inspiryData.nonce)
       },
       url: inspiryData.root_url + '/wp-json/inspiry/v1/deleteBoard',
       data: {
           'board-id': boardID, 
       },
       type: 'DELETE',
       success: (response)=>{
           console.log('this is a success area')
           if(response){ 
               console.log(response);
               $(e.target).closest('.board-card-archive').remove();
           }
       }, 
       error: (response)=>{
           console.log('this is an error');
           console.log(response)
       }
   });
         
       
    }
    //delete pin 
    deletePin(e){
       console.log('delete is working'); 

       //find a pin id 
   
       let pinID = $(e.target).attr('data-pinid')

       $(e.target).html('<div class="loader" style="display:block;"></div> ');


     
       $.ajax({
        beforeSend: (xhr)=>{
            xhr.setRequestHeader('X-WP-NONCE', inspiryData.nonce)
        },
        url: inspiryData.root_url + '/wp-json/inspiry/v1/manageBoard',
        data: {
            'pin-id': pinID, 
        },
        type: 'DELETE',
        success: (response)=>{
            console.log('this is a success area')
            if(response){ 
                console.log(response);
                $(e.target).closest('.board-card').remove();
            }
        }, 
        error: (response)=>{
            console.log('this is an error');
            console.log(response)
        }
    });
       

       
    }

    //create board function 
    createBoardFunc(e){ 

        let statusCheck;
        if($('.tgl input[type="checkbox"]').is(":checked")){
            statusCheck = 'private'; 
        }
        else{
            statusCheck = 'publish'; 
        }
        console.log('this  is a status check' + statusCheck)
        let boardName = $(e.target).closest('.btn-container').siblings('#board-name').val();
   
        let boardDescription; 
        if($(e.target).closest('.btn-container').siblings('#board-description').val()){ 
            boardDescription = $(e.target).closest('.btn-container').siblings('#board-description').val();
        }
        else{ 
            console.log('no description');
        }
       
        e.preventDefault();
        $('.project-save-form-section .loader').show();

       
        $.ajax({
            beforeSend: (xhr)=>{
                xhr.setRequestHeader('X-WP-NONCE', inspiryData.nonce)
            },
            url: inspiryData.root_url + '/wp-json/inspiry/v1/manageBoard',
            type: 'POST', 
            data: {
                'board-name': boardName, 
                'board-description': boardDescription,
                'status': statusCheck 

            },
            complete:()=>{
                $('.project-save-form-section .loader').hide();
            },
            success: (response)=>{
                console.log(response)
                if(response){ 
                    console.log(response);
                    //reload a window
                    location.reload();

                    //show the list board name in the list 
                    $('.choose-board-container .board-list').append(`<li data-board-id=${response}>${boardName}</li>`);
                    //hide the form
                    $('.project-save-form-section').hide();   
                    function addToBoard2(){
                        
                    //add a post into baord
                        let postID = $('.choose-board-container').attr('data-post-id');
                        let postTitle = $('.choose-board-container').attr('data-post-title'); 
                        
                        $.ajax({
                            beforeSend: (xhr)=>{
                                xhr.setRequestHeader('X-WP-NONCE', inspiryData.nonce)
                            },
                            url: inspiryData.root_url + '/wp-json/inspiry/v1/addToBoard',
                            type: 'POST', 
                            data: {
                                'board-id': response, 
                                'post-id': postID, 
                                'post-title': postTitle,
                                'status': statusCheck 
                            },
                            success: (response)=>{
                                console.log('this is a success area')
                                if(response){ 
                                    
                                   if($('body').attr('data-archive') == 'product-archive'){ 
                                        $('.choose-board-container').hide(300);
                                        $('.overlay').hide(300); 
                                        location.reload();
                                   }
                                   
                                       
                                 
                                   
                                    

                                }
                            }, 
                            error: (response)=>{
                                
                                console.log(response)
                            }
                        });
            
                    }

                    addToBoard2();


            
                  

                   
                }
            }, 
            error: (response)=>{
            
                console.log('this is a board error');
                console.log(response)
                console.log(response.responseText)
                $('#new-board-form').before(` <div class="error-bg">${response.responseText}</div>`);
            }
        });
        
    }

    
}

export default DesignBoardSaveBtn; 