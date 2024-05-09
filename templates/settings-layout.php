<?php
defined( 'ABSPATH' ) || exit;

$sub_page = isset( $_GET['sub_page'] )
	? sanitize_text_field( wp_unslash( $_GET['sub_page'] ) )
	: 'home';
$sub_page = in_array( $sub_page, [ 'home', 'add' ] ) ? $sub_page : 'home';
?>

<div class="row">
    <div class="col-3 left-menu">
        <div>
            <h2>LOGO</h2>
        </div>
    </div>
    <div class="col-9 main">
        <?php include( $sub_page . '.php' ); ?>
    </div>
</div>



