<?php

use Themepaste\ShippingManager\SaveRule;

defined('ABSPATH') || exit;
$output = home_url(add_query_arg(NULL, NULL));
$back = str_replace("&sub_page=add", "", $output);

?>
<a class="tsm-back-btn" href="<?php echo $back;  ?>"> &lt; Back</a>
<form action="add.php" method="post">
    <?php echo wp_nonce_field( SaveRule::ADD_RULE_NONCE, SaveRule::ADD_RULE_NONCE ); ?>
    <div class="tsm-row">
        <div class="tsm-col-6">
            <div class="">
                <input type="checkbox" id="is_active" name="is_active">
                <label for="is_active"> Active</label><br>
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
        <input class="tsm-pointer tsm-add-btn-input" type="submit" name="tsm_rules_form" value="Save">
    </div>
</form>