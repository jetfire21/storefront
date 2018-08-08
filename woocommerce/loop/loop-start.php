<?php
/**
 * Product Loop Start
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/loop-start.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// global $as21_rc;
// var_dump($as21_rc);
// echo $related_column;
// var_dump($related_product);
// var_dump(wc_get_loop_prop( 'total' ));
// var_dump(wc_get_loop_prop());
// print_r($GLOBALS['woocommerce_loop']);

// $GLOBALS['woocommerce_loop']['columns'] = 5;
// check (for example: product-category/animals/)
if( is_archive()  ) $GLOBALS['woocommerce_loop']['columns'] = 4;
elseif( is_product() ) {$GLOBALS['woocommerce_loop']['columns'] = 3;}
else $GLOBALS['woocommerce_loop']['columns'] = 7;

if( is_product_category() ):
	// echo 'wc_get_loop_prop( total ) '; var_dump(wc_get_loop_prop( 'total' ) );
	if( wc_get_loop_prop( 'total' ) <2) echo '<p>There are no cards in this category</p>';
endif;
?>
 <ul class="products columns-<?php echo esc_attr( wc_get_loop_prop( 'columns' ) ); ?>"> 
