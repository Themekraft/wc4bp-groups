<?php

/**
 * Retrieve the translation for the plugins. Wrapper for @see __()
 *
 * @param $str
 *
 * @return string
 */
function _wc4bp_groups( $str ) {
	return __( $str, 'wc4bp_groups' );
}


/**
 * Display the translation for the plugins. Wrapper for @see _e()
 *
 * @param $str
 */
function _e_wc4bp_groups( $str ) {
	_e( $str, 'wc4bp_groups' );
}

/**
 * Display the translation for the plugins.
 *
 * @param $str
 */
function esc_attr_e_wc4bp_groups( $str ) {
	echo esc_attr( _wc4bp_groups( $str ) );
}