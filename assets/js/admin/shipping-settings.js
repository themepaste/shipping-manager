var count = 0;

    function addSection() {
        count++;
        jQuery('.tsm-table').append('<tr class="tsm-first-section" id="new_sec_' + count + '">\
        <td style="width: 30%;"><select class="tsm-form-control" name="product_price" id="ProductPrice"><option selected value="">Select Attribute</option><option value="">Select Condition</option></select></td><td style="width: 30%;"><select class="tsm-form-control" name="product_price" id="ProductPrice"><option selected value="">Select Condition</option><option value="">Cart Price</option></select></td><td style="width: 30%;"><input class="tsm-dynamic-input" type="text"  value="Insert a value"></td><td><a onclick="removeSection(' + count + ')" class="tsm-tr-delete-button" >Delete</a></td>');
    }

    function removeSection(id) {
        jQuery('#new_sec_' + id).remove();
    }

    jQuery(document).ready(function() {
        jQuery('.tsm-multiple-select').select2();
    });