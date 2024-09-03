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

    /**
     * Display the add-ons products list
     * 
     * 
     */
    public function mjkmfs_show_force_sell_products() {
        global $post;

        $product_ids = MJKMFS_Utils::mjkmfs_get_force_sell_ids( $post->ID, array( 'normal', 'synced' ) );
        $titles      = array();

        //Check Product exist or not and avoid duplicate ids.
        foreach ( array_values( array_unique( $product_ids ) ) as $key => $product_id ) {
            $product = wc_get_product( $product_id );

            if ( $product && $product->exists() && 'trash' !== $product->get_status() ) {
                $titles[] = version_compare( WC_VERSION, '3.0', '>=' ) ? $product->get_title() : get_the_title( $product_id );
            }
        }

        if ( ! empty( $titles ) ) {
            // Get the view type from the settings (default to 'list')
            $view_type = apply_filters( 'mjkmfs_products_list_view_type', 'list' ); // Default to 'list'.

            echo '<div class="clear"></div>';
            echo '<div class="mjkmfs-wc-force-sells">';
            echo '<p>' . esc_html__( 'This will also add the following products to your cart:', 'woo-force-sells' ) . '</p>';

            // Switch case to handle different view types
            switch ( $view_type ) {
                case 'grid':
                    echo '<div class="mjkmfs-force-sells-grid">';
                    foreach ( $titles as $title ) {
                        echo '<div class="mjkmfs-force-sell-item">' . esc_html( $title ) . '</div>';
                    }
                    echo '</div>';
                    break;

                case 'list':
                default:
                    echo '<ul class="mjkmfs-force-sells-list">';
                    foreach ( $titles as $title ) {
                        echo '<li class="mjkmfs-force-sell-item">' . esc_html( $title ) . '</li>';
                    }
                    echo '</ul>';
                    break;
            }

            echo '</div>';
        }
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
