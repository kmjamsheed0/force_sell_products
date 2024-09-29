<?php
/**
 * Plugin Name: Force Sells and Smart Bundles for WooCommerce
 * Description: Automatically add selected products to the cart with a main item, creating smart bundles effortlessly.
 * Author:      Jamsheed KM
 * Version:     1.0.0
 * Author URI:  https://github.com/kmjamsheed0
 * Plugin URI:  https://github.com/kmjamsheed0/jkm-force-sells
 * Text Domain: jkm-force-sells
 * Domain Path: /languages
 * License:		GPL-2.0-or-later
 * License URI:	https://www.gnu.org/licenses/gpl-2.0.html
 * WC requires at least: 4.0.0
 * WC tested up to: 9.3
 */

if(!defined('ABSPATH')){ exit; }

// Add HPOS and Remote Logging compatibility declarations
add_action('before_woocommerce_init', function() {
    if (class_exists(\Automattic\WooCommerce\Utilities\FeaturesUtil::class)) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', __FILE__, true);
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('remote_logging', __FILE__, true);
    }
});

// add_action(
//     'woocommerce_layout_template_after_instantiation',
//     function( $layout_template_id, $layout_template_area, $layout_template ) {
//         // Get the 'linked_products' group
//         $linked_products_group = $layout_template->get_group_by_id( 'linked-products' );

//         if ( $linked_products_group ) {

//             // Create a new section after 'product-linked-cross-sells-section'
//             $custom_section = $linked_products_group->add_section(
//                 array(
//                     'id'         => 'custom-force-sell-addons',
//                     'order'      => 15,
//                     'attributes' => array(
//                         'title'       => __( 'Force Sell Add-ons', 'jkm-force-sells' ),
//                         'description' => __( 'Optional and mandatory add-ons for this product', 'jkm-force-sells' ),
//                     ),
//                 )
//             );

//             // Add custom blocks for Optional and Mandatory Add-ons
//             $custom_section->add_block(
//                 [
//                     'id'         => 'optional-add-ons',
//                     'blockName'  => 'jkmfs/optional-add-ons-field',
//                     'attributes' => [],
//                 ]
//             );

//             $custom_section->add_block(
//                 [
//                     'id'         => 'mandatory-add-ons',
//                     'blockName'  => 'jkmfs/mandatory-add-ons-field',
//                     'attributes' => [],
//                 ]
//             );
//         }
//     },
//     10,
//     3
// );


function jkmfs_register_meta_fields() {
    $meta_args = array(
        'show_in_rest' => array(
            'schema' => array(
                'type'  => 'array',
                'items' => array(
                    'type' => 'integer',
                ),
            ),
        ),
        'single'       => true,
        'type'         => 'array',
        'default'      => array(),
    );

    register_post_meta('product', 'jkm_force_sell_ids', $meta_args);
    register_post_meta('product', 'jkm_force_sell_synced_ids', $meta_args);
}
add_action('init', 'jkmfs_register_meta_fields');


function jkmfs_add_custom_fields_to_product_form($layout_template_id, $layout_template_area, $layout_template) {
    $linked_products_group = $layout_template->get_group_by_id('linked-products');
    if ($linked_products_group) {
        $custom_section = $linked_products_group->add_section(
            array(
                'id'         => 'custom-force-sell-addons',
                'order'      => 3,
                'attributes' => array(
                    'title'       => __('Force Sell Add-ons', 'jkm-force-sells'),
                    'description' => __('Optional and mandatory add-ons for this product', 'jkm-force-sells'),
                ),
            )
        );
        $custom_section->add_block(
            [
                'id'         => 'optional-add-ons',
                'blockName'  => 'jkmfs/optional-add-ons-field',
                'attributes' => [],
            ]
        );
        $custom_section->add_block(
            [
                'id'         => 'mandatory-add-ons',
                'blockName'  => 'jkmfs/mandatory-add-ons-field',
                'attributes' => [],
            ]
        );
    }
}
add_action('woocommerce_layout_template_after_instantiation', 'jkmfs_add_custom_fields_to_product_form', 10, 3);

// Add compatibility with classic editor
function jkmfs_add_custom_fields_to_classic_editor() {
    global $post;
    
    if ('product' !== $post->post_type) {
        return;
    }

    wp_nonce_field('jkmfs_save_product_meta', 'jkmfs_product_meta_nonce');

    $optional_addons = get_post_meta($post->ID, 'jkm_force_sell_ids', true);
    $mandatory_addons = get_post_meta($post->ID, 'jkm_force_sell_synced_ids', true);

    woocommerce_wp_select_multiple(
        array(
            'id' => 'jkm_force_sell_ids',
            'label' => __('Optional Add-ons', 'jkm-force-sells'),
            'description' => __('Select optional add-on products', 'jkm-force-sells'),
            'value' => $optional_addons,
            'options' => jkmfs_get_product_options(),
        )
    );

    woocommerce_wp_select_multiple(
        array(
            'id' => 'jkm_force_sell_synced_ids',
            'label' => __('Mandatory Add-ons', 'jkm-force-sells'),
            'description' => __('Select mandatory add-on products', 'jkm-force-sells'),
            'value' => $mandatory_addons,
            'options' => jkmfs_get_product_options(),
        )
    );
}
// add_action('woocommerce_product_options_related', 'jkmfs_add_custom_fields_to_classic_editor');

function jkmfs_get_product_options() {
    $products = wc_get_products(array('status' => 'publish', 'limit' => -1));
    $options = array();
    foreach ($products as $product) {
        $options[$product->get_id()] = $product->get_name();
    }
    return $options;
}

function jkmfs_save_product_meta($post_id) {
    if (!isset($_POST['jkmfs_product_meta_nonce']) || !wp_verify_nonce($_POST['jkmfs_product_meta_nonce'], 'jkmfs_save_product_meta')) {
        return;
    }

    $fields = array('jkm_force_sell_ids', 'jkm_force_sell_synced_ids');

    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            $product_ids = array_map('intval', (array) $_POST[$field]);
            update_post_meta($post_id, $field, $product_ids);
        } else {
            delete_post_meta($post_id, $field);
        }
    }
}
// add_action('woocommerce_process_product_meta', 'jkmfs_save_product_meta');


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
	if(!class_exists('JKM_Force_Sells_Products')){
		class JKM_Force_Sells_Products {
			const TEXT_DOMAIN = 'jkm-force-sells';

			public function __construct(){
				add_action('init', array($this, 'init'));
			}

			public function init() {
				define('JKMFS_VERSION', '1.0.0');
				!defined('JKMFS_BASE_NAME') && define('JKMFS_BASE_NAME', plugin_basename( __FILE__ ));
				!defined('JKMFS_PATH') && define('JKMFS_PATH', plugin_dir_path( __FILE__ ));
				!defined('JKMFS_URL') && define('JKMFS_URL', plugins_url( '/', __FILE__ ));
				!defined('JKMFS_ASSETS_URL') && define('JKMFS_ASSETS_URL', JKMFS_URL .'assets/');

				$this->load_plugin_textdomain();

				require_once( JKMFS_PATH . 'includes/class-jkmfs.php' );
				JKMFS::instance();
			}

			public function load_plugin_textdomain(){
				$locale = apply_filters('plugin_locale', get_locale(), self::TEXT_DOMAIN);

				load_textdomain(self::TEXT_DOMAIN, WP_LANG_DIR.'/jkm-force-sells/'.self::TEXT_DOMAIN.'-'.$locale.'.mo');
				load_plugin_textdomain(self::TEXT_DOMAIN, false, dirname(JKMFS_BASE_NAME) . '/languages/');
			}
		}
	}
	new JKM_Force_Sells_Products();
}