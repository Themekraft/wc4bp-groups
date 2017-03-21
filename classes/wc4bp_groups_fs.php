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

class wc4bp_groups_fs {
	
	/**
	 * Instance of this class.
	 *
	 * @var object
	 */
	protected static $instance = null;
	
	public function __construct() {
		$this->start_freemius();
	}
	
	public function start_freemius() {
		global $wc4bp_groups_fs;
		
		if ( ! isset( $wc4bp_groups_fs ) ) {
			// Include Freemius SDK.
			require_once dirname( __FILE__ ) . '/resources/freemius/start.php';
			
			$wc4bp_groups_fs = fs_dynamic_init( array(
				'id'                  => '831',
				'slug'                => 'wc4bp-groups',
				'type'                => 'plugin',
				'public_key'          => 'pk_2d2829cb8c200894712200553cf86',
				'is_premium'          => false,
				'has_premium_version' => false,
				'has_paid_plans'      => false,
				'parent'              => array(
					'id'         => '425',
					'slug'       => 'wc4bp',
					'public_key' => 'pk_71d28f28e3e545100e9f859cf8554',
					'name'       => 'WC4BP',
				),
			) );
		}
		
		return $wc4bp_groups_fs;
	}
	
	/**
	 * @return Freemius
	 */
	public static function getFreemius() {
		global $gfirem_fs;
		
		return $gfirem_fs;
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
}