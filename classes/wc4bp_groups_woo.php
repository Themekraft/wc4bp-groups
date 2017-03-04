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
		?>

        <div id="<?php echo wc4bp_groups_manager::getSlug(); ?>" class="panel woocommerce_options_panel wc-metaboxes-wrapper">
            <div id="message" class="inline notice woocommerce-message">
                <p><?php _e_wc4bp_groups( 'Before you can add a group configuration you need to save the product.' ); ?></p>
            </div>

            <div class="toolbar toolbar-top">
				<?php $this->show_woo_tab_search_for_group(); ?>
            </div>
            <div class="<?php echo wc4bp_groups_manager::getSlug(); ?> wc-metaboxes ui-sortable">
	
	            <?php $this->show_woo_tab_item_for_group(); ?>
                
            </div>

            <div class="toolbar">
					<span class="expand-close">
						<a href="#" class="expand_all"><?php _e_wc4bp_groups( 'Expand' ); ?></a> / <a href="#" class="close_all"><?php _e_wc4bp_groups( 'Close' ); ?></a>
					</span>
                <button type="button" class="button save_groups button-primary"><?php _e_wc4bp_groups( 'Save Groups' ); ?></button>
            </div>
        </div>
		<?php
	}
	
	private function show_woo_tab_search_for_group() {
		global $woocommerce, $post;
		include WC4BP_GROUP_VIEW_PATH . 'woo_tab_search.php';
	}
	
	private function show_woo_tab_item_for_group() {
		include WC4BP_GROUP_VIEW_PATH . 'woo_tab_item.php';
	}
	/**
	 * Save option selected into the tabs
	 *
	 * @param $post_id
	 * @param $post
	 */
	public function saveProductOptionsFields( $post_id, $post ) {
		$test_post   = esc_attr( $_POST['_test'] );
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