<?php
/**
 * Woo Force Sells Public
 *
 * @author   Jamsheed KM
 * @since    1.0.0
 *
 * @package    woo-force-sells
 * @subpackage woo-force-sells/public
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if(!class_exists('MJKMFS_Public')) :
class MJKMFS_Public {

    public function show_force_sell_products() {
        // Code to display Force Sell products on the product page.
    }

    public function add_force_sell_items_to_cart( $cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data ) {
        // Code to add Force Sell products to the cart.
    }

    public function update_force_sell_quantity_in_cart( $cart_item_key, $quantity ) {
        // Code to update the quantity of Force Sell products in the cart.
    }

    public function get_cart_item_from_session( $cart_item, $values ) {
        // Code to retrieve Force Sell items from the session.
    }

    public function get_linked_to_product_data( $data, $cart_item ) {
        // Code to display linked product data in the cart.
    }

    public function remove_orphan_force_sells() {
        // Code to remove orphan Force Sell items from the cart.
    }

    public function maybe_remove_duplicate_force_sells() {
        // Code to remove duplicate Force Sell items from the cart.
    }

    public function cart_item_remove_link( $link, $cart_item_key ) {
        // Code to prevent removal of Force Sell items from the cart.
    }

    public function cart_item_quantity( $quantity, $cart_item_key ) {
        // Code to prevent quantity changes for Force Sell items in the cart.
    }

    public function cart_item_removed( $cart_item_key ) {
        // Code to handle when a Force Sell item is removed from the cart.
    }

    public function cart_item_restored( $cart_item_key ) {
        // Code to handle when a Force Sell item is restored to the cart.
    }
}
endif;
