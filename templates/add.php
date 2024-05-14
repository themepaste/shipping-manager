<?php
defined('ABSPATH') || exit;
$output = home_url(add_query_arg(NULL, NULL));
$back = str_replace("&sub_page=add", "", $output);

if ($_POST) {
    // Sanitize form data
    $rule_title = sanitize_text_field($_POST['rule_title']);
    $is_active = sanitize_text_field($_POST['is_active']);

    // Create a new post (or update an existing one)
    $post_args = array(
        'post_title'    => 'TSM Rule', // You can change this to a more descriptive title
        'post_content'  => '', // You can add content here if needed
        'post_status'   => 'publish',
        'post_type'     => 'tsm_custom_rule' // Change this to your desired post type
    );

    $post_id = wp_insert_post($post_args);

    // Save form data as post meta
    update_post_meta($post_id, 'rule_title', $rule_title);
    update_post_meta($post_id, 'is_active', $is_active);

    // Redirect user after form submission
    wp_redirect(home_url('/wp-admin/admin.php?page=wc-settings&tab=shipping&section=tsm_shipping_settings'));
    exit();
}
?>
<a class="tsm-back-btn" href="<?php echo $back;  ?>"> &lt; Back</a>
<form action="add.php">
    <div class="tsm-row">
        <div class="tsm-col-6">
            <div class="">
                <input type="checkbox" id="vehicle1" name="vehicle1" value="Bike">
                <label for="vehicle1"> Active</label><br>
            </div>
            <div class="">
                <h4>Product Rule</h4>
                <div class="tsm-container">
                    <div class="">
                        <label for="">Rule Title</label>
                        <input class="tsm-rule-title" type="text" name="rule_title" id="rule_title" />
                    </div>
                    <table class="tsm-table">
                        <tr class="tsm-first-section">
                            <td>
                                <input type="checkbox" id="" name="" value=""><br>
                                <input type="checkbox" id="" name="" value="">
                            </td>
                            <td>
                                <select class="tsm-form-control" name="product_price" id="ProductPrice">
                                    <option selected value="">Product Price</option>
                                    <option value="">Cart Price</option>
                                </select>
                            </td>
                            <td>
                                <select class="tsm-form-control" name="" id="">
                                    <option selected value="">Greater Than</option>
                                    <option value="">Less Than</option>
                                </select>
                            </td>
                            <td>
                                <input class="tsm-form-control tsm-text-center" type="number" step="0.1" id="" name="" value="23.05">

                            </td>
                            <td></td>
                        </tr>
                    </table>
                    <div class="tsm-add-btn">
                        <button class="tsm-pointer" type="button" onclick="addSection()">Add</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="tsm-col-6">
            <div class="tsm-flex tsm-containt-right">
                <label for="">priority</label>
                <input type="text" class="tsm-form-control" id="priority" placeholder="" />
            </div>
        </div>
    </div>
    <div class="tsm-add-btn col-6">
        <button class="tsm-pointer" type="submit">Save</button>
    </div>
</form>