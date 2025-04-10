jQuery(function($){
    $('input[name="tpsm-shipping-fee"]').change(function() {
        $('.tpsm-shipping-fees-container').hide();
        $('#' + $(this).val()).show();
    });
});