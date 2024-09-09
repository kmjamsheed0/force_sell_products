<?php
/**
 * JKM Force Sells Admin
 *
 * @author   Jamsheed KM
 * @since    1.0.0
 *
 * @package    jkm-force-sells
 * @subpackage jkm-force-sells/admin
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if(!class_exists('JKMFS_Admin')) :
class JKMFS_Admin {

    public function jkmfs_write_panel_tab() {
        
        global $post;
        // Add nonce field for security
        wp_nonce_field( 'jkmfs_save_product_meta', 'jkmfs_product_meta_nonce' );
        ?>
        <p class="form-field">
            <label for="jkm_force_sell_ids"><?php _e( 'Optional Add-ons', 'jkm-force-sells' ); ?></label>
            <?php
                $product_ids = JKMFS_Utils::jkmfs_get_force_sell_ids( $post->ID, array( 'normal' ) );
                $json_ids    = array();

                if ( version_compare( WC_VERSION, '3.0', '>=' ) ) { ?>
                    <select id="jkm_force_sell_ids" class="wc-product-search" multiple="multiple" style="width: 50%;" name="jkm_force_sell_ids[]" data-placeholder="<?php esc_attr_e( 'Search for a product&hellip;', 'jkm-force-sells' ); ?>" data-action="woocommerce_json_search_products_and_variations" data-exclude="<?php echo intval( $post->ID ); ?>" data-exclude_type="variable">

                    <?php
                        foreach ( $product_ids as $product_id ) {
                            $product = wc_get_product( $product_id );

                            if ( ! $product ) {
                                continue;
                            }
                    ?>
                            <option value="<?php echo esc_attr( $product_id ); ?>" selected="selected"><?php echo wp_kses_post( $product->get_formatted_name() ); ?></option>
                    <?php } ?>
                    </select>
            <?php } else { ?>
                    <input type="hidden" class="wc-product-search" style="width: 50%;" id="jkm_force_sell_ids" name="jkm_force_sell_ids" data-placeholder="<?php _e( 'Search for a product&hellip;', 'jkm-force-sells' ); ?>" data-action="woocommerce_json_search_products_and_variations" data-multiple="true" data-selected="<?php
                    foreach ( $product_ids as $product_id ) {
                        $product = wc_get_product( $product_id );

                        if ( ! $product ) {
                            continue;
                        }

                        $json_ids[ $product_id ] = wp_kses_post( $product->get_formatted_name() );
                    }
                    $json_ids_data = wp_json_encode( $json_ids );
                    $json_ids_data = function_exists( 'wc_esc_json' ) ? wc_esc_json( $json_ids_data ) : _wp_specialchars( $json_ids_data, ENT_QUOTES, 'UTF-8', true );

                    echo $json_ids_data;
                    ?>" value="<?php echo implode( ',', array_keys( $json_ids ) ); ?>" />
            <?php } ?>
            <?php echo wc_help_tip( __( 'This product can be removed or its quantity changed independently, without affecting the main item in the cart.', 'jkm-force-sells' ) ); ?>
        </p>
        <p class="form-field">
            <label for="jkm_force_sell_synced_ids"><?php _e( 'Mandatory Add-ons', 'jkm-force-sells' ); ?></label>
            <?php
                $product_ids = JKMFS_Utils::jkmfs_get_force_sell_ids( $post->ID, array( 'synced' ) );
                $json_ids    = array();

                if ( version_compare( WC_VERSION, '5.0', '>=' ) ) { ?>
                    <select id="jkm_force_sell_synced_ids" class="wc-product-search" multiple="multiple" style="width: 50%;" name="jkm_force_sell_synced_ids[]" data-placeholder="<?php esc_attr_e( 'Search for a product&hellip;', 'jkm-force-sells' ); ?>" data-action="woocommerce_json_search_products_and_variations" data-exclude="<?php echo intval( $post->ID ); ?>" data-exclude_type="variable">

                    <?php
                        foreach ( $product_ids as $product_id ) {
                            $product = wc_get_product( $product_id );

                            if ( ! $product ) {
                                continue;
                            }
                    ?>
                            <option value="<?php echo esc_attr( $product_id ); ?>" selected="selected"><?php echo wp_kses_post( $product->get_formatted_name() ); ?></option>
                    <?php } ?>
                    </select>
            <?php } else { ?>
                <input type="hidden" class="wc-product-search" style="width: 50%;" id="jkm_force_sell_synced_ids" name="jkm_force_sell_synced_ids" data-placeholder="<?php _e( 'Search for a product&hellip;', 'jkm-force-sells' ); ?>" data-action="woocommerce_json_search_products_and_variations" data-multiple="true" data-selected="<?php
                foreach ( $product_ids as $product_id ) {
                    $product = wc_get_product( $product_id );

                    if ( ! $product ) {
                        continue;
                    }

                    $json_ids[ $product_id ] = wp_kses_post( $product->get_formatted_name() );
                }

                $json_ids_data = wp_json_encode( $json_ids );
                $json_ids_data = function_exists( 'wc_esc_json' ) ? wc_esc_json( $json_ids_data ) : _wp_specialchars( $json_ids_data, ENT_QUOTES, 'UTF-8', true );

                echo $json_ids_data;
                ?>" value="<?php echo implode( ',', array_keys( $json_ids ) ); ?>" />
            <?php } ?>
            <?php echo wc_help_tip( __( 'These products are forcefully added to the cart with the main product, and their quantity is synced with the main product.', 'jkm-force-sells' ) ); ?>
        </p>
        <?php
    }

    public function jkmfs_process_extra_product_meta( $post_id, $post ) {
        // Verify the nonce
        if ( ! isset( $_POST['jkmfs_product_meta_nonce'] ) || ! wp_verify_nonce( $_POST['jkmfs_product_meta_nonce'], 'jkmfs_save_product_meta' ) ) {
            return; // Exit if the nonce is invalid.
        }
        // Load the product object.
        $product = wc_get_product( $post_id );

        if ( ! $product ) {
            return; // Exit if the product is not found.
        }

        foreach ( JKMFS_Utils::get_synced_types() as $key => $value ) {
            if ( isset( $_POST[ $value['field_name'] ] ) ) {
                $force_sells = array();
                // Unslash and sanitize the IDs
                $ids = wp_unslash( $_POST[ $value['field_name'] ] );

                if ( version_compare( WC_VERSION, '2.7.0', '>=' ) && is_array( $ids ) ) {
                    $ids = array_filter( array_map( 'absint', $ids ) );
                } else {
                    $ids = explode( ',', $ids );
                    $ids = array_filter( array_map( 'absint', $ids ) );
                }

                foreach ( $ids as $id ) {
                    if ( $id && $id > 0 ) {
                        $force_sells[] = $id;
                    }
                }

                // Update meta data.
                $product->update_meta_data( $value['meta_name'], $force_sells );
                $product->save();
            } else {
                // Delete meta data.
                $product->delete_meta_data( $value['meta_name'] );
                $product->save();
            }
        }
    }

}
endif;
