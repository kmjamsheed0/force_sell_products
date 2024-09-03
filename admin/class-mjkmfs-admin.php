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
        
        global $post;
        ?>
        <p class="form-field">
            <label for="mjkm_force_sell_ids"><?php _e( 'Optional Add-ons', 'woo-force-sells' ); ?></label>
            <?php
                $product_ids = $this->mjkmfs_get_force_sell_ids( $post->ID, array( 'normal' ) );
                $json_ids    = array();

                if ( version_compare( WC_VERSION, '3.0', '>=' ) ) { ?>
                    <select id="mjkm_force_sell_ids" class="wc-product-search" multiple="multiple" style="width: 50%;" name="mjkm_force_sell_ids[]" data-placeholder="<?php esc_attr_e( 'Search for a product&hellip;', 'woo-force-sells' ); ?>" data-action="woocommerce_json_search_products_and_variations" data-exclude="<?php echo intval( $post->ID ); ?>" data-exclude_type="variable">

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
                    <input type="hidden" class="wc-product-search" style="width: 50%;" id="mjkm_force_sell_ids" name="mjkm_force_sell_ids" data-placeholder="<?php _e( 'Search for a product&hellip;', 'woo-force-sells' ); ?>" data-action="woocommerce_json_search_products_and_variations" data-multiple="true" data-selected="<?php
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
            <?php echo wc_help_tip( __( 'Add-on product can be removed or its quantity edited independently of the main product in the cart.', 'woo-force-sells' ) ); ?>
        </p>
        <p class="form-field">
            <label for="mjkm_force_sell_synced_ids"><?php _e( 'Mandatory Add-ons', 'woo-force-sells' ); ?></label>
            <?php
                $product_ids = $this->mjkmfs_get_force_sell_ids( $post->ID, array( 'synced' ) );
                $json_ids    = array();

                if ( version_compare( WC_VERSION, '5.0', '>=' ) ) { ?>
                    <select id="mjkm_force_sell_synced_ids" class="wc-product-search" multiple="multiple" style="width: 50%;" name="mjkm_force_sell_synced_ids[]" data-placeholder="<?php esc_attr_e( 'Search for a product&hellip;', 'woo-force-sells' ); ?>" data-action="woocommerce_json_search_products_and_variations" data-exclude="<?php echo intval( $post->ID ); ?>" data-exclude_type="variable">

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
                <input type="hidden" class="wc-product-search" style="width: 50%;" id="mjkm_force_sell_synced_ids" name="mjkm_force_sell_synced_ids" data-placeholder="<?php _e( 'Search for a product&hellip;', 'woo-force-sells' ); ?>" data-action="woocommerce_json_search_products_and_variations" data-multiple="true" data-selected="<?php
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
            <?php echo wc_help_tip( __( 'These products are forcefully added to the cart with the main product, and their quantity is synced with the main product.', 'woo-force-sells' ) ); ?>
        </p>
        <?php
    }

    /**
     * Get force sell IDs from a given product ID and force sell type(s).
     *
     * @param int   $product_id Product ID.
     * @param array $types      Force sell types (normal and/or synched).
     *
     * @return array Force sell IDs.
     */
    private function mjkmfs_get_force_sell_ids( $product_id, $types ) {
        if ( ! is_array( $types ) || empty( $types ) ) {
            return array();
        }

        $ids = array();

        foreach ( $types as $type ) {
            $new_ids = array();

            if ( isset( $this->synced_types[ $type ] ) ) {
                $new_ids = get_post_meta( $product_id, $this->synced_types[ $type ]['meta_name'], true );

                if ( is_array( $new_ids ) && ! empty( $new_ids ) ) {
                    $ids = array_merge( $ids, $new_ids );
                }
            }
        }

        return $ids;
    }

    public function mjkmfs_process_extra_product_meta( $post_id, $post ) {
        foreach ( $this->synced_types as $key => $value ) {
            if ( isset( $_POST[ $value['field_name'] ] ) ) {
                $force_sells = array();
                $ids         = $_POST[ $value['field_name'] ];

                if ( version_compare( WC_VERSION, '3.0.0', '>=' ) && is_array( $ids ) ) {
                    $ids = array_filter( array_map( 'absint', $ids ) );

                } else {
                    $ids = explode( ',', $ids );
                    $ids = array_filter( $ids );
                }

                foreach ( $ids as $id ) {
                    if ( $id && $id > 0 ) {
                        $force_sells[] = $id;
                    }
                }

                update_post_meta( $post_id, $value['meta_name'], $force_sells );
            } else {
                delete_post_meta( $post_id, $value['meta_name'] );
            }
        }
    }
}
endif;
