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
		$groups_json = get_post_meta( $post->ID, '_wc4bp_groups_json', true );
		$groups_json = html_entity_decode( $groups_json );
		if ( ! empty( $groups_json ) ) {
			$groups = json_decode( $groups_json );
		}
		?>

        <div id="<?php echo wc4bp_groups_manager::getSlug(); ?>" class="panel woocommerce_options_panel wc-metaboxes-wrapper">

            <div class="toolbar toolbar-top">
				<?php $this->show_woo_tab_search_for_group(); ?>
            </div>
            <div class="<?php echo wc4bp_groups_manager::getSlug(); ?> wc4bp-group-container wc-metaboxes ui-sortable">
				<?php
				$added_groups = array();
				if ( ! empty( $groups ) ) {
					foreach ( $groups as $group ) {
						$added_groups[] = $group->group_id;
						$this->show_woo_tab_item_for_group( $post->ID, $group );
					}
				}
				?>
            </div>

            <div class="toolbar wc4bp-bottom-toolbar">
                <span class="expand-close">
                    <a href="#" class="expand_all"><?php _e_wc4bp_groups( 'Expand' ); ?></a> / <a href="#" class="close_all"><?php _e_wc4bp_groups( 'Close' ); ?></a>
                </span>
            </div>
            <input type="hidden" id="wc4bp_groups_existing_ids" value="<?php echo esc_attr( json_encode( $added_groups ) ); ?>">
			<?php
			woocommerce_wp_hidden_input( array(
				'id'    => '_wc4bp_groups_json',
				'class' => wc4bp_groups_manager::getSlug()
			) );
			?>
        </div>
		<?php
	}
	
	private function show_woo_tab_search_for_group() {
		global $woocommerce, $post;
		include WC4BP_GROUP_VIEW_PATH . 'woo_tab_search.php';
	}
	
	private function show_woo_tab_item_for_group( $post_id, $group ) {
		include WC4BP_GROUP_VIEW_PATH . 'woo_tab_item.php';
	}
	
	/**
	 * Save option selected into the tabs
	 *
	 * @param $post_id
	 * @param $post
	 */
	public function saveProductOptionsFields( $post_id, $post ) {
		$wc4bp_groups_json     = esc_attr( $_POST['_wc4bp_groups_json'] );
		$wc4bp_groups_json_old = get_post_meta( $post_id, '_wc4bp_groups_json', true );
		if ( ! empty( $wc4bp_groups_json ) ) {
			if ( $wc4bp_groups_json != $wc4bp_groups_json_old ) {
				update_post_meta( $post_id, '_wc4bp_groups_json', esc_attr( $wc4bp_groups_json ) );
			}
		} else {
			if ( ! empty( $wc4bp_groups_json_old ) ) {
				delete_post_meta( $post_id, '_wc4bp_groups_json' );
			}
		}
	}
}