<?php
use Themepaste\ShippingManager\QueryRuleData;

defined('ABSPATH') || exit;

$add_url = home_url(add_query_arg(NULL, NULL)) . '&sub_page=add';
?>
<?php foreach( $rules as $rule ): ?>
    <div class="tsm-col-3">
      <div class="tsm-first-box tsm-card">
        <h2><?php echo esc_html( $rule->post_title); ?></h2>
		    <a href="">Edit</a>
      </div>
    </div>
<?php endforeach; ?>
<div class="tsm-row">
    <div class="tsm-col-3">
        <div class="tsm-add-box ">
            <a class='tsm-py-100' href="<?php echo $add_url ?>">ADD</a>
        </div>
    </div>
</div>