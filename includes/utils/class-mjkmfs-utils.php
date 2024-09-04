<?php
/**
 * Woo Force Sells Utils
 *
 * @author   Jamsheed KM
 * @since    1.0.0
 *
 * @package    woo-force-sells
 * @subpackage woo-force-sells/admin/utils
 */

if(!defined('ABSPATH')){ exit; }

if(!class_exists('MJKMFS_Utils')) :
class MJKMFS_Utils {

    /**
     * 
     * @var array
     */
    private static $synced_types = array(
        'normal' => array(
            'field_name' => 'mjkm_force_sell_ids',
            'meta_name'  => 'mjkm_meta_force_sell_ids',
        ),
        'synced' => array(
            'field_name' => 'mjkm_force_sell_synced_ids',
            'meta_name'  => 'mjkm_meta_force_sell_synced_ids',
        ),
    );

    /**
     * Get the synced types.
     *
     * @return array
     */
    public static function get_synced_types() {
        return self::$synced_types;
    }

    /**
     * Get force sell IDs from a given product ID and force sell type(s).
     *
     * @param int   $product_id Product ID.
     * @param array $types      Force sell types (normal and/or synched).
     *
     * @return array Force sell IDs.
     */
    public static function mjkmfs_get_force_sell_ids( $product_id, $types ) {
        if ( ! is_array( $types ) || empty( $types ) ) {
            return array();
        }

        // Load the product object.
        $product = wc_get_product( $product_id );

        if ( ! $product ) {
            return array(); // Exit if the product is not found.
        }

        $ids = array();

        foreach ( $types as $type ) {
            $new_ids = array();

            if ( isset( self::$synced_types[ $type ] ) ) {
                // Get meta data.
                $new_ids = $product->get_meta( self::$synced_types[ $type ]['meta_name'], true );

                if ( is_array( $new_ids ) && ! empty( $new_ids ) ) {
                    $ids = array_merge( $ids, $new_ids );
                }
            }
        }

        return $ids;
    }
}
endif;