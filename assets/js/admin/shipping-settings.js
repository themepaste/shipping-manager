var count = 0;

    function addSection() {
        count++;
        jQuery('.tsm-table').append('<tr id="new_sec_' + count + '">\
            <td>\
                <input type="checkbox" id="" name="" value=""><br>\
                <input type="checkbox" id="" name="" value="">\
            </td>\
            <td><select class="tsm-form-control" name="product_price" id="Product price"><option selected value="">Product Price</option><option value="">Cart Price</option></select></td>\
            <td>\
                <select class="tsm-form-control" name="" id="">\
                    <option selected value="">Greater Than</option>\
                    <option value="">Less Than</option>\
                </select>\
            </td>\
            <td>\
                <input class="tsm-form-control tsm-text-center" type="number" step="0.1" id="" name="" value="23.05">\
            </td>\
            <td><button class="tsm-delete-btn tsm-pointer" onclick="removeSection(' + count + ')" type="button">Del</button></td>\
        </tr>');
    }

    function removeSection(id) {
        jQuery('#new_sec_' + id).remove();
    }