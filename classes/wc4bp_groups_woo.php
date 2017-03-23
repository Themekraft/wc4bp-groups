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
		if ( bp_is_active( 'groups' ) ) {
			add_action( 'woocommerce_after_add_to_cart_button', array( $this, 'add_field_to_product_page' ) ); //Add field to the product page
			// filters for cart actions
			add_filter( 'woocommerce_add_cart_item_data', array( $this, 'add_cart_item_data' ), 10, 2 );
			add_filter( 'woocommerce_get_cart_item_from_session', array( $this, 'get_cart_item_from_session' ), 10, 2 );
			add_filter( 'woocommerce_get_item_data', array( $this, 'get_item_data' ), 10, 2 );
			add_action( 'woocommerce_add_order_item_meta', array( $this, 'add_order_item_meta' ), 10, 2 );
		}
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
		include WC4BP_GROUP_VIEW_PATH . 'woo_tab_conatiner.php';
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
		if ( bp_is_active( 'groups' ) ) {
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
	
	/**
	 * Add the view to select the group in the product page
	 */
	public function add_field_to_product_page() {
		global $product;
		$groups_json    = get_post_meta( $product->get_id(), '_wc4bp_groups_json', true );
		$groups_json    = html_entity_decode( $groups_json );
		$groups_to_show = array();
		if ( ! empty( $groups_json ) ) {
			$groups = json_decode( $groups_json );
			if ( is_array( $groups ) ) {
				foreach ( $groups as $group ) {
					if ( $group->is_optional == '1' ) {
						$groups_to_show[ $group->group_id ] = $group->group_name;
					}
				}
			}
		}
		
		if ( ! empty( $groups_to_show ) ) {
			$this->output_checkbox( array(
				'id'            => '_bp_group[]',
				'wrapper_class' => '_bp_group_field',
				'label'         => _wc4bp_groups( "Select BuddyPress Group" ),
				'options'       => $groups_to_show,
			) );
			wp_enqueue_style( 'wc4bp-groups', WC4BP_GROUP_CSS_PATH . 'wc4bp-groups.css', array(), wc4bp_groups_manager::getVersion() );
		}
	}
	
	/**
	 * Output a checkbox input box.
	 *
	 * @param array $field
	 */
	public function output_checkbox( $field ) {
		global $thepostid, $post;
		
		$thepostid              = empty( $thepostid ) ? $post->ID : $thepostid;
		$field['class']         = isset( $field['class'] ) ? $field['class'] : 'select short';
		$field['style']         = isset( $field['style'] ) ? $field['style'] : '';
		$field['wrapper_class'] = isset( $field['wrapper_class'] ) ? $field['wrapper_class'] : '';
		$field['value']         = isset( $field['value'] ) ? $field['value'] : get_post_meta( $thepostid, $field['id'], true );
		$field['name']          = isset( $field['name'] ) ? $field['name'] : $field['id'];
		
		echo '<fieldset class="form-field ' . esc_attr( $field['id'] ) . '_field ' . esc_attr( $field['wrapper_class'] ) . '"><legend>' . wp_kses_post( $field['label'] ) . '</legend><ul class="wc4bp-group-radios">';
		
		foreach ( $field['options'] as $key => $value ) {
			
			echo '<li><label><input
				name="' . esc_attr( $field['name'] ) . '"
				value="' . esc_attr( $key ) . '"
				type="checkbox"
				class="' . esc_attr( $field['class'] ) . '"
				style="' . esc_attr( $field['style'] ) . '"
				' . checked( esc_attr( $field['value'] ), esc_attr( $key ), false ) . '
				/> ' . esc_html( $value ) . '</label>
		</li>';
		}
		echo '</ul>';
		
		if ( ! empty( $field['description'] ) ) {
			
			if ( isset( $field['desc_tip'] ) && false !== $field['desc_tip'] ) {
				echo wc_help_tip( $field['description'] );
			} else {
				echo '<span class="description">' . wp_kses_post( $field['description'] ) . '</span>';
			}
		}
		
		echo '</fieldset>';
	}
	
	/**
	 * When added to cart, save any forms data
	 *
	 * @param mixed $cart_item_meta
	 * @param mixed $product_id
	 *
	 * @return mixed
	 */
	public function add_cart_item_data( $cart_item_meta, $product_id ) {
		if ( ! empty( $_POST['_bp_group'] ) ) {
			$groups = array();
			if ( is_array( $_POST['_bp_group'] ) ) {
				$groups = array_map( 'esc_attr', $_POST['_bp_group'] );
			} else {
				$groups[] = esc_attr( $_POST['_bp_group'] );
			}
			if ( ! empty( $groups ) ) {
				$cart_item_meta['_bp_group'] = $groups;
			}
		}
		
		return $cart_item_meta;
	}
	
	/**
	 * Add field data to cart item
	 *
	 * @modifiers GFireM
	 *
	 * @param mixed $cart_item
	 * @param mixed $values
	 *
	 * @return mixed
	 */
	public function get_cart_item_from_session( $cart_item, $values ) {
		if ( ! empty( $values['_bp_group'] ) ) {
			$cart_item['_bp_group'] = $values['_bp_group'];
		}
		
		return $cart_item;
	}
	
	/**
	 * Get item data
	 *
	 * @param $item_data
	 * @param $cart_item
	 *
	 * @return array
	 */
	public function get_item_data( $item_data, $cart_item ) {
		$item_data = $this->add_data_as_meta( $item_data, $cart_item );
		
		return $item_data;
	}
	
	/**
	 * After ordering, add the data to the order line item
	 *
	 * @param mixed $item_id
	 * @param $cart_item
	 *
	 */
	public function add_order_item_meta( $item_id, $cart_item ) {
		$item_data = $this->add_data_as_meta( array(), $cart_item );
		
		if ( empty ( $item_data ) ) {
			return;
		}
		
		foreach ( $item_data as $key => $value ) {
			wc_add_order_item_meta( $item_id, strip_tags( $value['key'] ), strip_tags( $value['value'] ) );
		}
	}
	
	/**
	 * Process the data to create the stream into the cart and the order
	 *
	 * @param $item_data
	 * @param $cart_item
	 *
	 * @return array
	 */
	private function add_data_as_meta( $item_data, $cart_item ) {
		if ( isset( $cart_item['_bp_group'] ) ) {
			$groups = $this->get_product_groups( $cart_item['product_id'] );
			if ( ! empty( $groups ) ) {
				foreach ( $groups as $group ) {
					if ( in_array( $group->group_id, $cart_item['_bp_group'] ) ) {
						$groups_str[] = $group->group_name;
					}
				}
				$groups_str  = implode( ', ', $groups_str );
				$item_data[] = array(
					'key'   => '<strong>' . _wc4bp_groups( "BuddyPress Group" ) . '</strong>',
					'value' => $groups_str
				);
			}
		}
		
		return $item_data;
	}
	
	
	/**
	 * Get the groups configuration associated to a product
	 *
	 * @param $product_id
	 *
	 * @return array
	 */
	private function get_product_groups( $product_id ) {
		$groups_json = get_post_meta( $product_id, '_wc4bp_groups_json', true );
		$groups_json = html_entity_decode( $groups_json );
		$result      = array();
		if ( ! empty( $groups_json ) ) {
			$groups = json_decode( $groups_json );
			if ( is_array( $groups ) ) {
				$result = $groups;
			}
		}
		
		return $result;
	}
}