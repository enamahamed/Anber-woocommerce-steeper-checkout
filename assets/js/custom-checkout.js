//jQuery(document).ready(function($) {
//    $('input[name="returning_documents_options"]').change(function() {
//        var selectedPrice = parseFloat($(this).data('price'));
//        $('#selected_returning_document_price').val(selectedPrice);
//        updateCartTotals(selectedPrice);
//    });
//
//    function updateCartTotals(selectedPrice) {
//        $.ajax({
//            type: 'POST',
//            url: custom_checkout_params.ajax_url,
//            data: {
//                action: 'update_returning_document_price',
//                selected_price: selectedPrice,
//            },
//            success: function(response) {
//                $('body').trigger('updated_cart_totals');
//            }
//        });
//    }
//});
