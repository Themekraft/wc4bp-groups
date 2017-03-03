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

class wc4bp_groups_manager {
	
	private static $plugin_slug = 'wc4bp_groups';
	protected static $version;
	
	public function __construct() {
		wc4bp_groups_required::load_plugins_dependency();
		$plugins_header = get_plugin_data( WC4BP_GROUP_BASE_FILE );
		self::$version  = $plugins_header['Version'];
		
		require_once WC4BP_GROUP_CLASSES_PATH . 'wc4bp_groups_log.php';
		try {
			//loading_dependency
			require_once WC4BP_GROUP_CLASSES_PATH.'wc4bp_groups_woo.php';
			new wc4bp_groups_woo();
			
		} catch ( Exception $ex ) {
			wc4bp_groups_log::log( array(
				'action'         => get_class( $this ),
				'object_type'    => wc4bp_groups_manager::getSlug(),
				'object_subtype' => 'loading_dependency',
				'object_name'    => $ex->getMessage(),
			) );
		}
	}
	
	
	/**
	 * Get plugins version
	 *
	 * @return mixed
	 */
	static function getVersion() {
		return self::$version;
	}
	
	/**
	 * Get plugins slug
	 *
	 * @return string
	 */
	static function getSlug() {
		return self::$plugin_slug;
	}
}