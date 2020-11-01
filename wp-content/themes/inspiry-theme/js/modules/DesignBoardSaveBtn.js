 let $ = jQuery; 
 //Design board save button
 class DesignBoardSaveBtn{ 
    constructor(){ 
        this.plusBtn = document.querySelectorAll('.design-board-save-btn-container .open-board-container');
        this.boardListItems = $('.choose-board-container .board-list li'); 
       
       this.events(); 
       this.fillHeartIcon();  
    }
    //events
    events(){ 

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
    }

    //functions 
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
            

        
      
        console.log(eventPostID + "and" + eventPostTitle);
        $('.choose-board-container').show(300);
        $('.overlay').show(300); 

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
                'post-title': postTitle
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
       
        let boardID = e.delegateTarget.dataset.pinid; 
       console.log(boardID);
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

       let pinID = e.delegateTarget.dataset.pinid; 

        console.log(pinID);

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
        console.log($(e.target).closest('.btn-container').siblings('#board-name').val());
        console.log($(e.target).closest('.btn-container').siblings('#board-description').val());
        
        let boardName = $('#board-name').val(); 
        console.log(boardName); 
       
        e.preventDefault();
        $('.project-save-form-section .loader').show();

       
        $.ajax({
            beforeSend: (xhr)=>{
                xhr.setRequestHeader('X-WP-NONCE', inspiryData.nonce)
            },
            url: inspiryData.root_url + '/wp-json/inspiry/v1/manageBoard',
            type: 'POST', 
            data: {
                'board-name': boardName
            },
            complete:()=>{
                $('.project-save-form-section .loader').hide();
            },
            success: (response)=>{
                
                if(response){ 
                    console.log(response);
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
                                'post-title': postTitle
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
                                console.log('this is an error');
                                console.log(response)
                            }
                        });
            
                    }

                    addToBoard2();


            
                  

                   
                }
            }, 
            error: (response)=>{
                console.log('this is an error');
                console.log(response.responseText)
                $('#new-board-form').before(` <div class="error-bg">${response.responseText}</div>`);
            }
        });
        
    }
    
}

export default DesignBoardSaveBtn; 