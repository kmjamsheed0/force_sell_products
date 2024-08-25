<?php
/**
 * Woo Force Sells Settings
 *
 * @author   Jamsheed KM
 * @since    1.0.0
 *
 * @package    woo-force-sells
 * @subpackage woo-force-sells/includes
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if(!class_exists('MJKMFS')) :
class MJKMFS {
    
    private static $instance = null;

    private $synced_types = array(
        'normal' => array(
            'field_name' => 'force_sell_ids',
            'meta_name'  => '_force_sell_ids',
        ),
        'synced' => array(
            'field_name' => 'force_sell_synced_ids',
            'meta_name'  => '_force_sell_synced_ids',
        ),
    );

    private function __construct() {
        $this->load_dependencies();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    public static function instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function load_dependencies() {
        require_once MJKMFS_PATH . 'admin/class-mjkmfs-admin.php';
        require_once MJKMFS_PATH . 'public/class-mjkmfs-public.php';
    }

    private function define_admin_hooks() {
        $admin = new MJKMFS_Admin();
        add_action( 'woocommerce_product_options_related', array( $admin, 'mjkmfs_write_panel_tab' ) );
        add_action( 'woocommerce_process_product_meta', array( $admin, 'mjkmfs_process_extra_product_meta' ), 1, 2 );
    }

    private function define_public_hooks() {
        $public = new MJKMFS_Public();
        add_action( 'woocommerce_after_add_to_cart_button', array( $public, 'show_force_sell_products' ) );
        add_action( 'woocommerce_add_to_cart', array( $public, 'add_force_sell_items_to_cart' ), 11, 6 );
        add_action( 'woocommerce_after_cart_item_quantity_update', array( $public, 'update_force_sell_quantity_in_cart' ), 1, 2 );
        add_action( 'woocommerce_remove_cart_item', array( $public, 'update_force_sell_quantity_in_cart' ), 1, 1 );
        add_filter( 'woocommerce_get_cart_item_from_session', array( $public, 'get_cart_item_from_session' ), 10, 2 );
        add_filter( 'woocommerce_get_item_data', array( $public, 'get_linked_to_product_data' ), 10, 2 );
        add_action( 'woocommerce_cart_loaded_from_session', array( $public, 'remove_orphan_force_sells' ) );
        add_action( 'woocommerce_cart_loaded_from_session', array( $public, 'maybe_remove_duplicate_force_sells' ) );
        add_filter( 'woocommerce_cart_item_remove_link', array( $public, 'cart_item_remove_link' ), 10, 2 );
        add_filter( 'woocommerce_cart_item_quantity', array( $public, 'cart_item_quantity' ), 10, 2 );
        add_action( 'woocommerce_cart_item_removed', array( $public, 'cart_item_removed' ), 30 );
        add_action( 'woocommerce_cart_item_restored', array( $public, 'cart_item_restored' ), 30 );
    }
}
endif;