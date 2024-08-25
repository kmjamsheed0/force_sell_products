<?php
/**
 * Woo Force Sells Admin
 *
 * @author   Jamsheed KM
 * @since    1.0.0
 *
 * @package    woo-force-sells
 * @subpackage woo-force-sells/admin
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if(!class_exists('MJKMFS_Admin')) :
class MJKMFS_Admin {

    public function mjkmfs_write_panel_tab() {
        // Code for adding the Force Sell panel in the product editor.
    }

    public function mjkmfs_process_extra_product_meta( $post_id, $post ) {
        // Code for processing the Force Sell meta fields when the product is saved.
    }
}
endif;
