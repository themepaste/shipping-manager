var count = 0;

    function addSection() {
        jQuery('.tsm-table').append('<tr class="tsm-first-section" >\
        <td style="width: 30%;"><select class="tsm-form-control" name="product_price" id="ProductPrice"><option selected value="">Select Attribute</option><option value="">Select Condition</option></select></td><td style="width: 30%;"><select class="tsm-form-control" name="product_price" id="ProductPrice"><option selected value="">Select Condition</option><option value="">Cart Price</option></select></td><td style="width: 30%;"><input class="tsm-dynamic-input" type="text"  value="Insert a value"></td><td><a  class="tsm-tr-delete-button" >Delete</a></td></tr>');
    }

    function addPricing() {
        jQuery('.tsm-pricing-table').append('<tr class="tsm-first-section"><td style="width: 15%;"><select class="tsm-form-control" name="product_price" id="ProductPrice"><option selected value="">Select</option><option value="">Cart Price</option></select></td><td style="width: 15%;"><select class="tsm-form-control" name="product_price" id="ProductPrice"><option selected value="">Each</option><option value="">Cart Price</option></select></td><td style="width: 15%;"><select class="tsm-form-control" name="product_price" id="ProductPrice"><option selected value="">Percent</option><option value="">Cart Price</option></select></td><td style="width: 15%;"><input class="tsm-dynamic-input tsm-form-control" type="number" step="0.1"  value="3.75"></td><td style="width: 10%;"><a  class="tsm-tr-delete-button" >Delete</a></td><td style="width: 15%;"></td><td style="width: 15%;"></td></tr>');
    }


    jQuery(document).ready(function () {
        jQuery("body").on("click", ".tsm-tr-delete-button", function(){
            jQuery(this).closest('tr').remove();
        });
    });


    jQuery(document).ready(function() {
        jQuery('.tsm-multiple-select').select2();
    });