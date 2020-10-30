 let $ = jQuery; 
 //Design board save button
 class DesignBoardSaveBtn{ 
    constructor(){ 
        this.plusBtn = document.querySelectorAll('.design-board-save-btn-container .open-board-container');
        this.closeIcon = $('.choose-board-container .close-icon'); 
        this.showCreateBoardForm = $('.choose-board-container .create-new-board'); 
        this.boardListItems = $('.choose-board-container .board-list li'); 
        this.eventPostID; 
        this.eventPostTitle; 
       this.events(); 
       this.fillHeartIcon(); 
       console.log(`the post id ${this.eventPostID} and the post title ${this.eventPostTitle}`)

     
    }
    //events
    events(){ 
 

        //show choose board container
        //this.plusBtn.forEach((event)=>{ 
           // event.addEventListener('click', this.showChooseBoardContainer.bind(this));
       // });
           $(document).on('click', '.design-board-save-btn-container .open-board-container', this.showChooseBoardContainer);
        //hide choose board container
        this.closeIcon.on('click', this.hideChooseBoardContainer); 
        //show a board form
        this.showCreateBoardForm.on('click', this.showForm); 
        //hide a board form
        $('.project-save-form-section .cancel-btn').on('click', this.hideForm);

        //create a new board 
        $('.project-save-form-section .save-btn').on('click', this.createBoardFunc);

        //add to a board
        this.boardListItems.on('click', this.addToBoard.bind(this)); 
        //delete a pin 
        $('.board-card .delete-btn').on('click', this.deletePin);

        //delete Board
        $('.board-card-archive .delete-board-btn').on('click', this.deleteBoard);
    }

    //functions 
    showChooseBoardContainer(e){ 
        console.log(window.location.href);
        this.eventPostID = $(e.target).closest('.design-board-save-btn-container').siblings('a').attr('data-postid'); 
        this.eventPostTitle = $(e.target).closest('.design-board-save-btn-container').siblings('a').html(); 


        console.log('show board container');
        $('.choose-board-container').show(300);
        $('.overlay').show(300); 
       

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
        $('.project-save-form-section').show();
    }

    hideForm(){ 
        $('.project-save-form-section').hide();
    }

    

    //add project to board
    addToBoard(e){
        console.log(e); 
        let boardID = e.delegateTarget.dataset.boardid; 
        console.log(boardID);

        let postID = this.eventPostID; 
        let postTitle = this.eventPostTitle;

        console.log(`the post id ${postID} and the post title ${postTitle}`)
        //let postID = $('.board-heading-post-id').data('postid');
         
        //let postTitle = $('.board-heading-post-id').html(); 

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
        
        let boardName = $('#board-name').val(); 
       
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
                console.log('this is a success area')
                if(response){ 
                    console.log(response);
                    //show the list board name in the list 
                    $('.choose-board-container .board-list').append(`<li data-board-id=${response}>${boardName}</li>`);
                    //hide the form
                    $('.project-save-form-section').hide();   
                    function addToBoard2(){
                        
                        let postID = $('.board-heading-post-id').data('postid'); 
                        let postTitle = $('.board-heading-post-id').html(); 
                        
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
                                    console.log(response);
                                    $('.design-board-save-btn-container i').addClass('fas fa-heart');

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
                console.log(response)
                $('#new-board-form').before(` <div class="error-bg">Board Already Exists</div>`);
            }
        });
        
    }
    
}

export default DesignBoardSaveBtn; 