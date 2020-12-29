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
                jQuery('.Success-div').html("Form Submit Successfully")
            },
            error: function(response) {
                console.log(response);
            }

        });

    }
}

export default ImageUpload;