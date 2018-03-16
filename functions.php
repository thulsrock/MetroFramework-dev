<?php

require_once "config.php";
require_once "exceptions.php";
require_once "notice.php";

/**
 * Autoloading classes from
 * @var CLASS_PATH defined in config file
 */

set_include_path( get_include_path().PATH_SEPARATOR.CLASS_DIR );
spl_autoload_extensions( ".class.php" );
spl_autoload_register();

require_once "vendor/autoload.php";

function esc( $request ) {
	if( isset( $request ) and !is_array( $request ) ) {
		return filter_var( $request, FILTER_SANITIZE_SPECIAL_CHARS );
	} elseif ( is_array( $request ) ) {
		array_filter( $request, 'trim' );
		filter_var_array( $request, FILTER_SANITIZE_SPECIAL_CHARS );
	}
	return $request;
}
function esc_url( String $str ) {
	return urlencode( $str );
}
function fieldIsEmpty( $value ) {
	if( !isset( $value) || $value == '' ) return TRUE;
	else return FALSE;
}
function fieldIsNotEmpty( $value ) {
	return !fieldIsEmpty($value);
}
function redirectToRoot() {
	header( 'refresh:0;URL=' . ROOT );
	exit;
}
