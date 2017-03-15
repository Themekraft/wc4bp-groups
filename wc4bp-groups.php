<?php
/**
 * Plugin Name: WC4BP -> Groups
 * Plugin URI:  http://themekraft.com/store/woocommerce-buddypress-integration-wordpress-plugin/
 * Description: Handle a WooCommerce installation with a BuddyPress social network
 * Author:      WC4BP Integration Dev Team ;)
 * Version:     1.0.0
 * Licence:     GPLv3
 * Text Domain: wc4bp
 * Domain Path: /languages
 *
 * @package wc4bp_groups
 *
 *****************************************************************************
 *
 * This script is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 ****************************************************************************
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'wc4bp_groups' ) ) {
	
	require_once dirname( __FILE__ ) . '/classes/wc4bp_groups_fs.php';
	new wc4bp_groups_fs();
	
	class wc4bp_groups {
		
		/**
		 * Instance of this class.
		 *
		 * @var object
		 */
		protected static $instance = null;
		
		/**
		 * Initialize the plugin.
		 */
		public function __construct() {
			define( 'WC4BP_GROUP_BASE_NAME', plugin_basename( __FILE__ ) );
			define( 'WC4BP_GROUP_BASE_FILE', trailingslashit( wp_normalize_path( plugin_dir_path( __FILE__ ) ) ) . 'wc4bp-groups.php' );
			define( 'WC4BP_GROUP_URLPATH', plugin_dir_url( __FILE__ ) );
			define( 'WC4BP_GROUP_CSS_PATH', WC4BP_GROUP_URLPATH . 'assets/css/' );
			define( 'WC4BP_GROUP_JS_PATH', WC4BP_GROUP_URLPATH . 'assets/js/' );
			define( 'WC4BP_GROUP_VIEW_PATH', dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR );
			define( 'WC4BP_GROUP_CLASSES_PATH', dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR );
			define( 'WC4BP_GROUP_RESOURCES_PATH', WC4BP_GROUP_CLASSES_PATH . 'resources' . DIRECTORY_SEPARATOR );
			
			$this->load_plugin_textdomain();
			require_once WC4BP_GROUP_CLASSES_PATH . 'wc4bp_groups_override.php';
			
			require_once WC4BP_GROUP_RESOURCES_PATH . 'class-tgm-plugin-activation.php';
			require_once WC4BP_GROUP_CLASSES_PATH . 'wc4bp_groups_required.php';
			new wc4bp_groups_required();
			
			if ( wc4bp_groups_required::is_buddypress_active() && wc4bp_groups_required::is_woocommerce_active() && wc4bp_groups_required::is_wc4bp_active() ) {

//				self::$freemius = $this->wc4bp_fs();
				
				require_once WC4BP_GROUP_CLASSES_PATH . 'wc4bp_groups_manager.php';
				new wc4bp_groups_manager();

//				register_activation_hook( __FILE__, array( $this, 'activation' ) );
//				register_deactivation_hook( __FILE__, array( $this, 'deactivation' ) );
//				self::getFreemius()->add_action('after_uninstall', array($this, 'uninstall_cleanup') );
			}
		}
		
		/**
		 * Return an instance of this class.
		 *
		 * @return object A single instance of this class.
		 */
		public static function get_instance() {
			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self;
			}
			
			return self::$instance;
		}
		
		/**
		 * Load the plugin text domain for translation.
		 */
		public function load_plugin_textdomain() {
			load_plugin_textdomain( 'wc4bp_groups', false, basename( dirname( __FILE__ ) ) . '/languages' );
		}
	}
	
	add_action( 'plugins_loaded', array( 'wc4bp_groups', 'get_instance' ) );
}
