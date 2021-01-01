const $ = jQuery;

class ImageUpload {
    constructor() {
        this.events();
    }

    events() {
        //show container    
        $('.img-upload').on('click', this.showContainer)
        $('.image-upload-container .cancel-btn').on('click', () => {
                $('.image-upload-container .project-save-form-container').hide();
                $('.overlay').hide();
            })
            //save a photo
        $('#upload-image').submit(this.imageProcessor)
    }

    showContainer(e) {
        $('.image-upload-container .project-save-form-container').show();
        $('.overlay').show();
    }

    imageProcessor(e) {
        e.preventDefault();

        let data = {
            action: $('#action').val(),
            my_file_field: $('#image').prop('files')[0]

        }
        console.log(data);
        let url = 'http://localhost/inspiry/wp-admin/admin-ajax.php';


        var form_data = new FormData();
        form_data.append('my_file_field', data.my_file_field);
        form_data.append('action', 'my_file_upload');


        jQuery.ajax({
            url: url,
            type: 'post',
            contentType: false,
            processData: false,
            data: form_data,
            success: function(response) {
                console.log(response)


                //add image to board


                let boardID = $('.image-upload-container').attr('data-parentid');
                let boardPostStatus = $('.single-board').attr('data-poststatus');


                let postImageID = response.slice(0, -1)
                    //let postTitle = 'private upload';


                //show loader icon
                $(e.target).closest('.board-list-item').find('.loader').addClass('loader--visible');
                $.ajax({
                    beforeSend: (xhr) => {
                        xhr.setRequestHeader('X-WP-NONCE', inspiryData.nonce)
                    },
                    url: inspiryData.root_url + '/wp-json/inspiry/v1/addToBoard',
                    type: 'POST',
                    data: {
                        'board-id': boardID,
                        'post-image-id': postImageID,
                        'status': boardPostStatus
                    },
                    complete: () => {
                        $(e.target).closest('.board-list-item').find('.loader').removeClass('loader--visible');
                    },
                    success: (response) => {
                        console.log('this is a success area')
                        if (response) {
                            console.log(response);
                            location.reload();



                        }
                    },
                    error: (response) => {
                        console.log('this is an error');
                        console.log(response)
                        $(e.target).closest('.board-list-item').find('.loader').removeClass('loader--visible');

                    }
                });



            },
            error: function(response) {
                console.log(response);
            }

        });

    }
}

export default ImageUpload;