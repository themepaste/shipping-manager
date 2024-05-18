<?php
defined('ABSPATH') || exit;

$add_url = home_url(add_query_arg(NULL, NULL)) . '&sub_page=add';
?>
<div class="tsm-col-12">
    <div class="tsm-pt-15">
        <a class='tsm-add-button' href="<?php echo $add_url ?>">Add New</a>

    </div>
</div>

<?php foreach( $rules as $rule ): ?>
    <div class="tsm-col-4">
      <div class="tsm-first-box tsm-card">
        <h2>
            <?php echo esc_html( get_post_meta( $rule->ID, 'rule_title', true ) /* $rule->post_title */); ?>
        </h2>
        <p>Genera rule to allow the customer free shipping, when they have more that $1025 ...</p>
		<div>
            <a class="button-left" href="">Edit</a>
            <a class="button-right" href="">Delete</a>
        </div>
      </div>
    </div>
<?php endforeach; ?>
