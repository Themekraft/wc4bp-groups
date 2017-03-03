<?php

/**
 * @package        WordPress
 * @subpackage     BuddyPress, Woocommerce, WC4BP
 * @author         ThemKraft Dev Team
 * @copyright      2017, Themekraft
 * @link           http://themekraft.com/store/woocommerce-buddypress-integration-wordpress-plugin/
 * @license        http://www.opensource.org/licenses/gpl-2.0.php GPL License
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class wc4bp_groups_woo {
	
	public function __construct() {
		add_filter( 'woocommerce_product_data_tabs', array( $this, 'addProductOptionSection' ), 10, 1 );//Add section
		add_action( 'woocommerce_product_data_panels', array( $this, 'addProductOptionPanelTab' ) );//Add Section Tab content
		add_action( 'woocommerce_process_product_meta', array( $this, 'saveProductOptionsFields' ), 11, 2 );//Save option
	}
	
	/**
	 * Add new tab to general product tabs
	 *
	 * @param $sections
	 *
	 * @return mixed
	 */
	public function addProductOptionSection( $sections ) {
		$sections[ wc4bp_groups_manager::getSlug() ] = array(
			'label'  => _wc4bp_groups( 'WC4BP Groups' ),
			'target' => wc4bp_groups_manager::getSlug(),
			'class'  => array(),
		);
		
		return $sections;
	}
	
	/**
	 * Add content to generated tab
	 */
	public function addProductOptionPanelTab() {
		global $woocommerce, $post;
		echo '<div id="' . wc4bp_groups_manager::getSlug() . '" class="panel woocommerce_options_panel"><div class="options_group">';
		
		woocommerce_wp_select(
			array(
				'id'      => '_test',
				'label'   => _wc4bp_groups( "Test Option:" ),
				'options' => array(
					'1' => "Yes",
					''  => "No",
				)
			)
		);
		
		echo '</div></div>';
	}
	
	/**
	 * Save option selected into the tabs
	 *
	 * @param $post_id
	 * @param $post
	 */
	public function saveProductOptionsFields( $post_id, $post ) {
		$test_post     = esc_attr( $_POST['_test'] );
		$test_option = get_post_meta( $post_id, '_test', true );
		if ( ! empty( $test_post ) ) {
			if ( $test_post != $test_option ) {
				update_post_meta( $post_id, '_test', esc_attr( $test_post ) );
			}
		} else {
			if ( ! empty( $test_option ) ) {
				delete_post_meta( $post_id, '_test' );
			}
		}
	}
}