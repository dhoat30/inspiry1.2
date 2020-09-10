jQuery(function ($) {

    let enable_type = $("#enable_type").val();
    if( enable_type == 'product' ) {
        $('.wfps-enable-product-area').show();
        $('.wfps-enable-category-area').hide();
    } else {
        $('.wfps-enable-product-area').hide();
        $('.wfps-enable-category-area').show();
    }

    let exclude_type = $("#exclude_type").val();
    if( exclude_type == 'product' ) {
        $('.exclude_product_area').show();
        $('.exclude_category_area').hide();
    } else {
        $('.exclude_product_area').hide();
        $('.exclude_category_area').show();
    }

	let manage_stock = $("#wfps-manage-stock").val();
    if( manage_stock == 1 ) {
        $(".wfps-enable-area").show();
    }   
        
    if( $('#disable_limit_per_order').is( ':checked' ) ) {
        $('.limit_per_order_area').hide();
        $('.max_qty_per_order_area').hide();
    }

    $(document).on( 'click', '#disable_limit_per_order', function(){
        if( $('#disable_limit_per_order').is( ':checked' ) ) {
            $('.limit_per_order_area').hide();
            $('.max_qty_per_order_area').hide();
        } else {
            $('.limit_per_order_area').show();
            $('.max_qty_per_order_area').show();
        }   
    });
    
});
