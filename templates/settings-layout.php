<?php
defined( 'ABSPATH' ) || exit;

$sub_page = isset( $_GET['sub_page'] )
	? sanitize_text_field( wp_unslash( $_GET['sub_page'] ) )
	: 'home';
$sub_page = in_array( $sub_page, [ 'home', 'add' ] ) ? $sub_page : 'home';

include( $sub_page . '.php' );