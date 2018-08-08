<?php
/**
 * Mini-cart
 *
 * Contains the markup for the mini-cart, used by the cart widget.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/mini-cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<div class="h-cart">
	<i class="demo-icon icon-basket">&#xe801;</i> 
	<?php
	$count = 0; foreach ( WC()->cart->get_cart_item_quantities() as $item){
		$count = $count + $item;
	}
	?>	
	<a class="cart-contents" href="http://aussieglo.loc/cart/" title="View your shopping cart">                    <span class="cart-contents-count"><?php echo $count;?> </span>	
		<?php if ( ! WC()->cart->is_empty() ) : ?>
		<p class="total"><?php echo WC()->cart->get_cart_subtotal(); ?></p>
	<?php endif; ?>
	</a>
	<!-- <?php echo $count;?> товара (ов)		 -->
	<!-- <i class="arrow demo-icon icon-up-open-mini"></i> -->
	<!-- <i class="arrow demo-icon icon-up-open-mini">&#xe803;</i> -->
	<!-- <i class="arrow demo-icon icon-down-open-mini">&#xe802;</i> -->
	<i class="arrow demo-icon icon-down-open-mini"></i>
</div>


