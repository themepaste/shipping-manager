<?php

use Themepaste\ShippingManager\QueryRuleData;
defined('ABSPATH') || exit;

$add_url = home_url(add_query_arg(NULL, NULL)) . '&sub_page=add';
?>
<div class="tsm-row">
    <?php
        // Instantiate the class
        $custom_query = new QueryRuleData();
        // Call the custom query function
        $custom_query->custom_query_function();
     ?>
    
    <div class="tsm-col-3">
        <div class="tsm-add-box ">
            <a class='tsm-py-100' href="<?php echo $add_url ?>">ADD</a>
        </div>
    </div>
</div>