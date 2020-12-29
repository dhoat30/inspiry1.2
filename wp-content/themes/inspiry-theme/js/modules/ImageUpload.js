const $ = jQuery;

class ImageUpload {
    constructor() {
        this.events();
    }

    events() {
        $('#upload-image').submit(this.imageProcessor)
    }

    imageProcessor(e) {
        e.preventDefault();

        let data = {
            'my_nonce_field': $('#my_nonce_field').val(),
            '_wp_http_referer': $('#_wp_http_referer').val(),
            'action': $('#action').val(),
            'my_file_field': $('#my_file_field').val()

        }
        console.log(data);
        let url = 'http://localhost/inspiry/wp-admin/admin-ajax.php';


        /*
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
                   

                    


            
                  

                   
                }
            }, 
            error: (response)=>{
            
                console.log('this is a board error');
                console.log(response)
                console.log(response.responseText)
                $('#new-board-form-archive').before(` <div class="error-bg">${response.responseText}</div>`);
            }
        });
        */
    }
}

export default ImageUpload;