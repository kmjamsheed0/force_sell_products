<?php
/**
 * Plugin Name: WooCommerce Force Sells | Smart Bundles
 * Description: Allows you to select products that will be used as force-sells—items automatically added to the cart along with the main item.
 * Author:      Jamsheed KM
 * Version:     1.0.0
 * Author URI:  https://github.com/kmjamsheed0
 * Plugin URI:  https://github.com/kmjamsheed0
 * Text Domain: woo-force-sells
 * Domain Path: /languages
 * WC requires at least: 4.0.0
 * WC tested up to: 9.2
 */

if(!defined('ABSPATH')){ exit; }

if (!function_exists('is_woocommerce_active')){
	function is_woocommerce_active(){
	    $active_plugins = (array) get_option('active_plugins', array());
	    if(is_multisite()){
		   $active_plugins = array_merge($active_plugins, get_site_option('active_sitewide_plugins', array()));
	    }
	    return in_array('woocommerce/woocommerce.php', $active_plugins) || array_key_exists('woocommerce/woocommerce.php', $active_plugins) || class_exists('WooCommerce');
	}
}

if(is_woocommerce_active()) {
	if(!class_exists('MJKM_Force_Sells_Products')){
		class MJKM_Force_Sells_Products {
			const TEXT_DOMAIN = 'woo-force-sells';

			public function __construct(){
				add_action('init', array($this, 'init'));
			}

			public function init() {
				define('MJKMFS_VERSION', '1.0.0');
				!defined('MJKMFS_BASE_NAME') && define('MJKMFS_BASE_NAME', plugin_basename( __FILE__ ));
				!defined('MJKMFS_PATH') && define('MJKMFS_PATH', plugin_dir_path( __FILE__ ));
				!defined('MJKMFS_URL') && define('MJKMFS_URL', plugins_url( '/', __FILE__ ));
				!defined('MJKMFS_ASSETS_URL') && define('MJKMFS_ASSETS_URL', MJKMFS_URL .'assets/');

				$this->load_plugin_textdomain();

				require_once( MJKMFS_PATH . 'includes/class-mjkmfs.php' );
				MJKMFS::instance();
			}

			public function load_plugin_textdomain(){
				$locale = apply_filters('plugin_locale', get_locale(), self::TEXT_DOMAIN);

				load_textdomain(self::TEXT_DOMAIN, WP_LANG_DIR.'/woo-force-sells/'.self::TEXT_DOMAIN.'-'.$locale.'.mo');
				load_plugin_textdomain(self::TEXT_DOMAIN, false, dirname(MJKMFS_BASE_NAME) . '/languages/');
			}
		}
	}
	new MJKM_Force_Sells_Products();
}