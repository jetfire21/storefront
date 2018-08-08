<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked wc_print_notices - 10
 */
do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form(); // WPCS: XSS ok.
	return;
}

$product_id = get_the_ID();


// $terms = get_the_terms ( $product_id, 'product_cat' );
// echo $terms[0]->term_id;
// as21_debug(0,1,'',$terms);

// var_dump(wc_get_product_cat_ids($product_id));
// var_dump($product_id);
$prouct_cat_id = wc_get_product_cat_ids($product_id);
// echo $prouct_cat_id[0];
$cat_link = get_category_link( $prouct_cat_id[0] );

// if ( carbon_get_post_meta($product_id, 'crb_use_as_set') == "yes") {
// 	echo 'yes';
// 	wp_redirect( 'http//:ya.ru', 301);
// 	exit;
// }


// echo '-------content-single-product---------<hr>';
echo "<div class='wrap_links as21-block-pos-hor-center'>";
echo previous_post_link('%link', 'Previous card', TRUE, ' ', 'product_cat');
echo "<a class='all-cards' href='".$cat_link."'>All cards</a>";
echo next_post_link('%link', 'Next card', TRUE, ' ', 'product_cat');
echo '</div>';

$full_title = get_the_title($product_id);
// var_dump($full_title);
$short_title = preg_replace('/#(.*)\s/i', '',$full_title );
// var_dump($full_title);
?>
<!-- <img src="http://aussieglo3.loc/wp-content/uploads/2018/07/animals_fb.jpg" alt=""> -->
<!-- <img src="http://aussieglo3.loc/wp-content/uploads/2018/07/animals.jpg" alt=""> -->

<!-- <div class="elementor-container elementor-column-gap-default as21-block-pos-hor-center">
	<div class="elementor-row wrap-single-card">
		<div class="elementor-column elementor-col-50" data-element_type="column">
			<img src="http://aussieglo3.loc/wp-content/uploads/2018/07/animals.jpg" alt="">
		</div>
		<div class="elementor-column elementor-col-50" data-element_type="column">
			<img src="http://aussieglo3.loc/wp-content/uploads/2018/07/animals.jpg" alt="">
		</div>
	</div>
-->

</div> 
<!-- end .col-full -->
<section data-id="94a7f28" class="elementor-element elementor-element-94a7f28 elementor-section-boxed elementor-section-height-default elementor-section-height-default elementor-section elementor-top-section section-card-back-part" data-element_type="section">
	<div class="elementor-container elementor-column-gap-default">
		<div class="elementor-row">			<h2 class="big-card-title"><?php echo $short_title;?></h2></div>
		<div class="elementor-row">
			<h3 class="as21-big-gray-size-title vert-text">CARD</h3>
			<div data-id="f314522" class="elementor-element elementor-element-f314522 elementor-column elementor-col-50 elementor-top-column" data-element_type="column">
				<div class="elementor-column-wrap elementor-element-populated">
					<div class="elementor-widget-wrap">
						<div data-id="ffacd8b" class="elementor-element elementor-element-ffacd8b elementor-widget elementor-widget-image" data-element_type="image.default">
							<div class="elementor-widget-container">
								<div class="elementor-image">
<!-- 									
<div class="as21-back-card">#58 “Friendship”
										<img src="http://aussieglo3.loc/wp-content/uploads/2018/07/AussieGLO_GREY.png" alt="">
										<p>designed to spark joy &middot; made in australia &middot; aussieglo.com <span>58</span></p>
									</div>
								-->
								<div class="as21-back-card">
									<img src="<?php echo carbon_get_post_meta($product_id, "crb_card_backend_side");?>" alt="">
									<!-- <img src="<?php echo carbon_get_post_meta(40, 'crb_card_backend_side');?>" alt=""> -->
									<!-- <h4>“<?php echo $short_title;?>”</h4> -->
									<!-- <img src="<?php echo  get_template_directory_uri();?>/img/harmony.png" alt=""> -->

								</div>

<!-- 		
							<img width="324" height="323" src="http://aussieglo3.loc/wp-content/uploads/2018/07/animals.jpg" class="attachment-large size-large" alt="" srcset="http://aussieglo3.loc/wp-content/uploads/2018/07/animals.jpg 324w, http://aussieglo3.loc/wp-content/uploads/2018/07/animals-150x150.jpg 150w, http://aussieglo3.loc/wp-content/uploads/2018/07/animals-300x300.jpg 300w, http://aussieglo3.loc/wp-content/uploads/2018/07/animals-100x100.jpg 100w" sizes="(max-width: 324px) 100vw, 324px">	
						-->									
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div data-id="43fbf94" class="elementor-element elementor-element-43fbf94 elementor-column elementor-col-50 elementor-top-column" data-element_type="column">
	<div class="elementor-column-wrap elementor-element-populated">
		<div class="elementor-widget-wrap">
			<div data-id="1b7fc21" class="elementor-element elementor-element-1b7fc21 elementor-widget elementor-widget-image" data-element_type="image.default">
				<div class="elementor-widget-container">
					<div class="elementor-image">
						<!-- <img width="324" height="323" src="http://aussieglo3.loc/wp-content/uploads/2018/07/animals.jpg" class="attachment-large size-large" alt="" srcset="http://aussieglo3.loc/wp-content/uploads/2018/07/animals.jpg 324w, http://aussieglo3.loc/wp-content/uploads/2018/07/animals-150x150.jpg 150w, http://aussieglo3.loc/wp-content/uploads/2018/07/animals-300x300.jpg 300w, http://aussieglo3.loc/wp-content/uploads/2018/07/animals-100x100.jpg 100w" sizes="(max-width: 324px) 100vw, 324px">		 -->
						<?php        
						// echo $set_thumb = get_the_post_thumbnail( $product_id, 'woocommerce_thumbnail');
						echo $set_thumb = get_the_post_thumbnail( $product_id, 'single_image_width');
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
<div class="clearfix"></div>
<div class="product wrap-add-to-cart"><?php echo as21_product_add_to_cart( array('id'=>$product_id) ); ?></div>

</div>

</section>

<div class="col-full">
	<?php do_action( 'as21_wc_before_related_section' ); ?>

	<?php //echo do_shortcode('[related_products limit="3"]'); ?>


	<?php
		/**
		 * Hook: woocommerce_before_single_product_summary.
		 *
		 * @hooked woocommerce_show_product_sale_flash - 10
		 * @hooked woocommerce_show_product_images - 20
		 */
		// do_action( 'woocommerce_before_single_product_summary' );
		?>

		<?php
			/**
			 * Hook: woocommerce_single_product_summary.
			 *
			 * @hooked woocommerce_template_single_title - 5
			 * @hooked woocommerce_template_single_rating - 10
			 * @hooked woocommerce_template_single_price - 10
			 * @hooked woocommerce_template_single_excerpt - 20
			 * @hooked woocommerce_template_single_add_to_cart - 30
			 * @hooked woocommerce_template_single_meta - 40
			 * @hooked woocommerce_template_single_sharing - 50
			 * @hooked WC_Structured_Data::generate_product_data() - 60
			 */
			// do_action( 'woocommerce_single_product_summary' );
			?>

			<?php
		/**
		 * Hook: woocommerce_after_single_product_summary.
		 *
		 * @hooked woocommerce_output_product_data_tabs - 10
		 * @hooked woocommerce_upsell_display - 15
		 * @hooked woocommerce_output_related_products - 20
		 */
		// do_action( 'woocommerce_after_single_product_summary' );
		?>

		<?php do_action( 'woocommerce_after_single_product_summary' ); ?>

		<?php do_action( 'woocommerce_after_single_product' ); ?>
		<?php do_action( 'as21_wc_after_related_section' ); ?>
