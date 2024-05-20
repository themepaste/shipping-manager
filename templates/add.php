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

        <div class="tsm-col-12">
            <div class="tsm-col-6 tsm-p-0">
                <div class="tsm-flex tsm-containt-right">
                    <label class="tsm-label" for="">Title<span class="tsm-pl-1">*</span></label>
                    <input type="text" class="tsm-form-control" id="Title " placeholder="" />

                    <button class="tsm-tooltip">?
                        <span class="tsm-tooltiptext">Tooltip text</span>
                    </button>
                </div>
            </div>
        </div>

        <div class="tsm-col-12">
            <div class="tsm-col-6 tsm-p-0">
                <div class="tsm-containt-right">
                    <div class="tsm-flex">
                        <label class="tsm-label" for="">Descrption</label> <br>
                        <button class="tsm-tooltip">?
                            <span class="tsm-tooltiptext">Tooltip text</span>
                        </button>
                    </div>
                    <textarea class="tsm-form-control tsm-mt-1" rows="3" name="" id=""></textarea>
                </div>
            </div>
        </div>



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
                                            <select style="width: 100%;" class="tsm-multiple-select" name="sub_categories"  id="">
                                                <option selected value="">Greater Than</option>
                                                <option value="">Less Than</option>
                                                <option value="">Less Than</option>
                                                <option value="">Less Than</option>
                                                <option value="">Less Than</option>
                                            </select>
                                        </td>
                                        <td><a  class="tsm-tr-delete-button" >Delete</a></td>
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
                                        <td><a  class="tsm-tr-delete-button" >Delete</a></td>
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
                                        <td><a  class="tsm-tr-delete-button" >Delete</a></td>
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


        <div class="tsm-col-12">
            <div class="tsm-flex tsm-pb-2">
                <h4 class="tsm-selector-builder tsm-m-0">Pricing</h4>
                <button class="tsm-tooltip">?
                    <span class="tsm-tooltiptext">Tooltip text</span>
                </button>
            </div>
                <div class="tsm-row">
                        <div class="">
                            <div class="tsm-container">
                                <table class="tsm-pricing-table">
                                    <tr class="tsm-first-section">
                                        <td style="width: 15%;">
                                            <select class="tsm-form-control" name="product_price" id="ProductPrice">
                                                <option selected value="">Quantity</option>
                                                <option value="">Cart Price</option>
                                            </select>
                                        </td>
                                        <td style="width: 15%;">
                                            <select class="tsm-form-control" name="product_price" id="ProductPrice">
                                                <option selected value="">Each</option>
                                                <option value="">Cart Price</option>
                                            </select>
                                        </td>
                                        <td style="width: 15%;">
                                            <select class="tsm-form-control" name="product_price" id="ProductPrice">
                                                <option selected value="">Percent</option>
                                                <option value="">Cart Price</option>
                                            </select>
                                        </td>
                                        <td style="width: 15%;">
                                            <input class="tsm-dynamic-input tsm-form-control" type="number" step="0.1"  value="3.75">
                                        </td>
                                        <td style="width: 10%;"><a  class="tsm-tr-delete-button" >Delete</a></td>
                                        <td style="width: 15%;"></td>
                                        <td style="width: 15%;"></td>
                                    </tr>

                                    <tr class="tsm-first-section">
                                        <td style="width: 15%;">
                                            <select class="tsm-form-control" name="product_price" id="ProductPrice">
                                                <option selected value="">Quantity</option>
                                                <option value="">Cart Price</option>
                                            </select>
                                        </td>
                                        <td style="width: 15%;">
                                            <select class="tsm-form-control" name="product_price" id="ProductPrice">
                                                <option selected value="">Range</option>
                                                <option value="">Cart Price</option>
                                            </select>
                                        </td>
                                        <td style="width: 15%;">
                                            <select class="tsm-form-control" name="product_price" id="ProductPrice">
                                                <option selected value="">Flat</option>
                                                <option value="">Cart Price</option>
                                            </select>
                                        </td>
                                        <td style="width: 15%;">
                                            <input class="tsm-dynamic-input tsm-form-control" step="0.1" type="number"  value="0.45">
                                        </td>
                                        <td style="width: 15%;">
                                            <input class="tsm-dynamic-input tsm-form-control"  type="text"  placeholder="From: 5">
                                        </td>
                                        <td style="width: 15%;">
                                            <input class="tsm-dynamic-input tsm-form-control"  type="text"  placeholder="To: 25">
                                        </td>
                                        <td style="width: 10%;"><a  class="tsm-tr-delete-button" >Delete</a></td>
                                    </tr>
                                    <tr class="tsm-first-section">
                                        <td style="width: 15%;">
                                            <select class="tsm-form-control" name="product_price" id="ProductPrice">
                                                <option selected value="">Weight</option>
                                                <option value="">Cart Price</option>
                                            </select>
                                        </td>
                                        <td style="width: 15%;">
                                            <select class="tsm-form-control" name="product_price" id="ProductPrice">
                                                <option selected value="">Each</option>
                                                <option value="">Cart Price</option>
                                            </select>
                                        </td>
                                        <td style="width: 15%;">
                                            <select class="tsm-form-control" name="product_price" id="ProductPrice">
                                                <option selected value="">Percent</option>
                                                <option value="">Cart Price</option>
                                            </select>
                                        </td>
                                        <td style="width: 15%;">
                                            <input class="tsm-dynamic-input tsm-form-control" step="0.1" type="number"  placeholder="3.75">
                                        </td>
                                        <td style="width: 10%;"><a  class="tsm-tr-delete-button" >Delete</a></td>
                                        <td style="width: 15%;">
                                        </td>
                                        <td style="width: 15%;">
                                        </td>
                                    </tr>

                                    
                                    <tr class="tsm-first-section">
                                        <td style="width: 15%;">
                                            <select class="tsm-form-control" name="product_price" id="ProductPrice">
                                                <option selected value="">Weight</option>
                                                <option value="">Cart Price</option>
                                            </select>
                                        </td>
                                        <td style="width: 15%;">
                                            <select class="tsm-form-control" name="product_price" id="ProductPrice">
                                                <option selected value="">Range</option>
                                                <option value="">Cart Price</option>
                                            </select>
                                        </td>
                                        <td style="width: 15%;">
                                            <select class="tsm-form-control" name="product_price" id="ProductPrice">
                                                <option selected value="">Flat</option>
                                                <option value="">Cart Price</option>
                                            </select>
                                        </td>
                                        <td style="width: 15%;">
                                            <input class="tsm-dynamic-input tsm-form-control" step="0.1" type="number"  placeholder="0.45">
                                        </td>
                                        <td style="width: 15%;">
                                            <input class="tsm-dynamic-input tsm-form-control"  type="text"  placeholder="From: 5">
                                        </td>
                                        <td style="width: 15%;">
                                            <input class="tsm-dynamic-input tsm-form-control"  type="text"  placeholder="To: 25">
                                        </td>
                                        <td style="width: 10%;"><a  class="tsm-tr-delete-button" >Delete</a></td>
                                    </tr>

                                    <tr class="tsm-first-section">
                                        <td style="width: 15%;">
                                            <select class="tsm-form-control" name="product_price" id="ProductPrice">
                                                <option selected value="">Select</option>
                                                <option value="">Cart Price</option>
                                            </select>
                                        </td>
                                        <td style="width: 15%;">
                                            <select class="tsm-form-control" name="product_price" id="ProductPrice">
                                                <option selected value="">Each</option>
                                                <option value="">Cart Price</option>
                                            </select>
                                        </td>
                                        <td style="width: 15%;">
                                            <select class="tsm-form-control" name="product_price" id="ProductPrice">
                                                <option selected value="">Percent</option>
                                                <option value="">Cart Price</option>
                                            </select>
                                        </td>
                                        <td style="width: 15%;">
                                            <input class="tsm-dynamic-input tsm-form-control" type="number" step="0.1"  value="3.75">
                                        </td>
                                        <td style="width: 10%;"><a  class="tsm-tr-delete-button" >Delete</a></td>
                                        <td style="width: 15%;"></td>
                                        <td style="width: 15%;"></td>
                                    </tr>

                                </table>
                                <div class="tsm-add-btn">
                                    <button class="tsm-pointer" type="button" onclick="addPricing()">Add Pricing</button>
                                </div>
                            </div>
                        </div>
                </div>

        </div>

   

</div>

<div class="tsm-col-3"></div>

