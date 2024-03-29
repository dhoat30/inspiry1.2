jQuery(function($) {
    $('#gd_ie_download_events').click(function(e) {
        if ($(this).data('sample-csv')) {
            window.location.href = $(this).data('sample-csv');
            return false;
        }
    });
    
	if ($(".geodir-delete-post-type.geodir-act-delete").length) {
		$(".geodir-delete-post-type.geodir-act-delete").click(function() {
			var post_type = $(this).closest('tr').find('.gd-has-id').val();
			if (post_type) {
				geodir_cp_delete_post_type(post_type, $(this).closest('tr'));
			}
		});
		geodir_location_show_selected_countries($("input[name=lm_default_country]:checked"));
	}
});

function geodir_cp_delete_post_type(post_type, $el) {
    if (!confirm(geodir_cp_admin_params.confirm_delete_post_type)) {
        return false;
    }

    if (!post_type) {
        return;
    }

    var data = {
        action: 'geodir_ajax_delete_post_type',
        post_type: post_type,
        security: jQuery('.gd-has-id', $el).data('delete-nonce')
    }
    jQuery.ajax({
        url: geodir_params.ajax_url,
        type: 'POST',
        dataType: 'json',
        data: data,
        beforeSend: function() {
            $el.css({
                opacity: 0.6
            });
        },
        success: function(res, textStatus, xhr) {
            if (res.data.message) {
                alert(res.data.message);
            }
            if (res.success) {
                $el.fadeOut();
            } else {
                $el.css({
                    opacity: 1
                });
            }
        },
        error: function(xhr, textStatus, errorThrown) {
            console.log(errorThrown);
            $el.css({
                opacity: 1
            });
        }
    });
}