<?php

/**
 * Menu Icons
 *
 * @package Menu_Icons
 * @version 0.10.2
 * @author Dzikri Aziz <kvcrvt@gmail.com>
 *
 *
 * Plugin name: Menu Icons
 * Plugin URI:  https://github.com/Codeinwp/wp-menu-icons
 * Description: Spice up your navigation menus with pretty icons, easily.
 * Version:     0.12.8
 * Author:      ThemeIsle
 * Author URI:  https://themeisle.com
 * License:     GPLv2
 * Text Domain: menu-icons
 * Domain Path: /languages
 * WordPress Available:  yes
 * Requires License:    no
 */

if(!defined('JANKX_MENU_ICONS_ROOT')) {
	define('JANKX_MENU_ICONS_ROOT', dirname(__FILE__));
}

/**
 * Main plugin class
 */
final class Menu_Icons {

	const VERSION = '0.12.8';

	/**
	 * Holds plugin data
	 *
	 * @access protected
	 * @since  0.1.0
	 * @var    array
	 */
	protected static $data;


	/**
	 * Get plugin data
	 *
	 * @since  0.1.0
	 * @since  0.9.0  Return NULL if $name is not set in $data.
	 * @param  string $name
	 *
	 * @return mixed
	 */
	public static function get( $name = null ) {
		if ( is_null( $name ) ) {
			return self::$data;
		}

		if ( isset( self::$data[ $name ] ) ) {
			return self::$data[ $name ];
		}

		return null;
	}


	/**
	 * Load plugin
	 *
	 * 1. Load translation
	 * 2. Set plugin data (directory and URL paths)
	 * 3. Attach plugin initialization at icon_picker_init hook
	 *
	 * @since   0.1.0
	 * @wp_hook action plugins_loaded
	 * @link    http://codex.wordpress.org/Plugin_API/Action_Reference/plugins_loaded
	 */
	public static function _load() {
		load_plugin_textdomain( 'menu-icons', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

		$abspath = constant('ABSPATH');
		$menu_icons_root = constant('JANKX_MENU_ICONS_ROOT');

		if (PHP_OS === 'WINNT') {
			$abspath = str_replace('\\', '/', $abspath);
			$menu_icons_root = str_replace('\\', '/', $menu_icons_root);
		}

		self::$data = array(
			'dir'   => $menu_icons_root . '/',
			'url'   => untrailingslashit( str_replace($abspath, site_url('/'), $menu_icons_root) ),
			'types' => array(),
		);

		Icon_Picker::instance();

		require_once self::$data['dir'] . 'includes/library/compat.php';
		require_once self::$data['dir'] . 'includes/library/functions.php';
		require_once self::$data['dir'] . 'includes/meta.php';

		Menu_Icons_Meta::init();

		add_action( 'icon_picker_init', array( __CLASS__, '_init' ), 9 );
	}


	/**
	 * Initialize
	 *
	 * 1. Get registered types from Icon Picker
	 * 2. Load settings
	 * 3. Load front-end functionalities
	 *
	 * @since   0.1.0
	 * @since   0.9.0  Hook into `icon_picker_init`.
	 * @wp_hook action icon_picker_init
	 * @link    http://codex.wordpress.org/Plugin_API/Action_Reference
	 */
	public static function _init() {
		/**
		 * Allow themes/plugins to add/remove icon types
		 *
		 * @since 0.1.0
		 * @param array $types Icon types
		 */
		self::$data['types'] = apply_filters(
			'menu_icons_types',
			Icon_Picker_Types_Registry::instance()->types
		);

		// Nothing to do if there are no icon types registered.
		if ( empty( self::$data['types'] ) ) {
			if ( WP_DEBUG ) {
				trigger_error( esc_html__( 'Menu Icons: No registered icon types found.', 'menu-icons' ) );
			}

			return;
		}

		// Load settings.
		require_once self::$data['dir'] . 'includes/settings.php';
		Menu_Icons_Settings::init();

		// Load front-end functionalities.
		if ( ! is_admin() ) {
			require_once self::$data['dir'] . '/includes/front.php';
			Menu_Icons_Front_End::init();
		}

		do_action( 'menu_icons_loaded' );
	}


	/**
	 * Display notice about missing Icon Picker
	 *
	 * @since   0.9.1
	 * @wp_hook action admin_notice
	 */
	public static function _notice_missing_icon_picker() {
		?>
		<div class="error">
			<p><?php esc_html_e( 'Looks like Menu Icons was installed via Composer. Please activate Icon Picker first.', 'menu-icons' ); ?></p>
		</div>
		<?php
	}
}
add_action( 'after_setup_theme', array( 'Menu_Icons', '_load' ) );

$vendor_file = dirname(__FILE__) . '/vendor/autoload.php';

if ( is_readable( $vendor_file ) ) {
	require_once $vendor_file;
}

add_filter( 'themeisle_sdk_products', 'kucrut_register_sdk', 10, 1 );
function kucrut_register_sdk( $products ) {

	$products[] = __FILE__;
	return $products;
}
