<?php
defined( 'ABSPATH' ) || exit;

$sub_page = isset( $_GET['sub_page'] )
	? sanitize_text_field( wp_unslash( $_GET['sub_page'] ) )
	: 'home';
$sub_page = in_array( $sub_page, [ 'home', 'add' ] ) ? $sub_page : 'home';
?>
<div class="tsm-body">
    
<div class="tsm-row">
    <div class="tsm-col-3 tsm-left-menu">
        <div>
            <h2>Shipping Manager</h2>
            <p>by  THEMEPASTE</p>
        </div>
    </div>
    <div class="tsm-col-9 tsm-main">
        <?php include( $sub_page . '.php' ); ?>
    </div>
</div>
</div>


