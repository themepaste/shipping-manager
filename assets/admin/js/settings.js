jQuery(function($){
    $('input[name="tpsm-shipping-fee_type"]').change(function() {
        $('.tpsm-shipping-fees-container').hide();
        $('#' + $(this).val()).show();
    });

    $(document).ready(function () {
        $('#tpsm-weight-range-pricing-add').click(function () {
            
            let newRow = `
            <div class="tpsm-repeater-row">
                <div class="tpsm-column-1">
                    <input type="text" name="from[]">
                </div>
                <div class="tpsm-column-2">
                    <input type="text" name="to[]">
                </div>
                <div class="tpsm-column-3">
                    <input type="text" name="fee[]">
                </div>
                <div class="tpsm-column-4">
                    <button type="button" class="delete-row">Delete</button>
                </div>
            </div>`;
            $('.tpsm-shipping-fees-repeater').append(newRow);
        });
    
        $(document).on('click', '.delete-row', function () {
            $(this).closest('.tpsm-repeater-row').remove();
        });
    
    });
});


