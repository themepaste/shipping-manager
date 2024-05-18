<?php

use Themepaste\ShippingManager\SaveRule;

defined('ABSPATH') || exit;
$output = home_url(add_query_arg(NULL, NULL));
$back = str_replace("&sub_page=add", "", $output);

?>

<div class="tsm-col-12">
    <div class="tsm-pt-15">
        <a class='tsm-back-btn' href="<?php echo $back;  ?>">Back</a>
    </div>
</div>

<div class="tsm-col-9">
        <div class="tsm-col-6">
            <div class="tsm-flex">
                <div class="tsm-actived">
                    <input type="checkbox" id="is_active" name="is_active">
                    <label class="tsm-label" for="is_active"> Activate</label><br>
                </div>

                <button class="tsm-tooltip">?
                    <span class="tsm-tooltiptext">Tooltip text</span>
                </button>
            </div>
        </div>
        <div class="tsm-col-6">
            <div class="tsm-flex tsm-containt-right">
                <label class="tsm-label" for="">Priority<span class="tsm-pl-1">*</span></label>
                <input type="text" class="tsm-form-control" id="priority" placeholder="" />

                <button class="tsm-tooltip">?
                    <span class="tsm-tooltiptext">Tooltip text</span>
                </button>
            </div>
        </div>

        <div class="tsm-col-12">
            <div class="tsm-flex tsm-pb-2">
                <h4 class="tsm-selector-builder tsm-m-0">Selector Builder</h4>
                <button class="tsm-tooltip">?
                    <span class="tsm-tooltiptext">Tooltip text</span>
                </button>
            </div>


            <form action="add.php" method="post">
                <?php echo wp_nonce_field( SaveRule::ADD_RULE_NONCE, SaveRule::ADD_RULE_NONCE ); ?>
                <div class="tsm-row">
                        <div class="">
                            <div class="tsm-container">
                                <table class="tsm-table">
                                    <tr class="tsm-first-section">
                                        <td style="width: 30%;">
                                            <select class="tsm-form-control" name="product_price" id="ProductPrice">
                                                <option selected value="">Category</option>
                                                <option value="">Cart Price</option>
                                            </select>
                                        </td>
                                        <td style="width: 30%;">
                                            <select class="tsm-form-control" name="product_price" id="ProductPrice">
                                                <option selected value="">In</option>
                                                <option value="">Cart Price</option>
                                            </select>
                                        </td>
                                        <td style="width: 30%;">
                                            <select style="width: 100%;" class="tsm-multiple-select" name="sub_categories[]" multiple="multiple" id="">
                                                <option selected value="">Greater Than</option>
                                                <option value="">Less Than</option>
                                            </select>
                                        </td>
                                        <td></td>
                                    </tr>

                                    <tr class="tsm-first-section">
                                        <td style="width: 30%;">
                                            <select class="tsm-form-control" name="product_price" id="ProductPrice">
                                                <option selected value="">Regular Price</option>
                                                <option value="">Cart Price</option>
                                            </select>
                                        </td>
                                        <td style="width: 30%;">
                                            <select class="tsm-form-control" name="product_price" id="ProductPrice">
                                                <option selected value="">IN RANGE</option>
                                                <option value="">Cart Price</option>
                                            </select>
                                        </td>
                                        <td style="width: 30%;" class="tsm-flex tsm-input-td">
                                            <input  type="number" step="0.1" value="12.50">
                                            <input class="tsm-second-input" type="number" step="0.1" value="125.35">
                                        </td>
                                        <td></td>
                                    </tr>

                                    <tr class="tsm-first-section">
                                        <td style="width: 30%;">
                                            <select class="tsm-form-control" name="product_price" id="ProductPrice">
                                                <option selected value="">Manage Inventory</option>
                                                <option value="">Cart Price</option>
                                            </select>
                                        </td>
                                        <td style="width: 30%;">
                                            <select class="tsm-form-control" name="product_price" id="ProductPrice">
                                                <option selected value="">IS</option>
                                                <option value="">Cart Price</option>
                                            </select>
                                        </td>
                                        <td style="width: 30%;">
                                            <select class="tsm-form-control" name="" id="">
                                                <option selected value="">TRUE</option>
                                                <option value="">Less Than</option>
                                            </select>
                                        </td>
                                        <td></td>
                                    </tr>

                                </table>
                                <div class="tsm-add-btn">
                                    <button class="tsm-pointer" type="button" onclick="addSection()">Add New Row</button>
                                </div>
                            </div>
                        </div>
                </div>
                <div class="tsm-save-btn col-6">
                    <input class="tsm-pointer tsm-save-btn-input" type="submit" name="tsm_rules_form" value="Save">
                </div>
            </form>

        </div>

   

</div>

<div class="tsm-col-3"></div>

