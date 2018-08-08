<?php


add_action( 'woocommerce_before_main_content','as21_wc_test1',10 );
function as21_wc_test1(){


	// var_dump(is_product_category() );
	if(is_product_category())
	{
		// global $product;
		// $id = $product->get_id();
		// as21_debug(0,1,'',as21_get_set_all_ids() );
		 // $prod_ids = product_ids_by_cat($category->term_id);
		// as21_debug(0,1,'', as21_get_all_parents_cats() );

		$cat = get_queried_object();
		$cur_catID = $cat->term_id;
		// echo 'current_cat_id:'.$cur_catID;

		$cat_ids = as21_get_all_parents_cats();
		$pos = array_search ($cur_catID, $cat_ids );
		// var_dump($pos);
		$next_pos = $pos + 1;
		$prev_pos = $pos - 1;
		$cat_next_id = $cat_ids[$next_pos];
		$cat_prev_id = $cat_ids[$prev_pos];

		// echo $category_link = get_category_link( $cur_catID );
		$cat_next_link = get_category_link( $cat_next_id );
		$cat_prev_link = get_category_link( $cat_prev_id);
		echo "<div class='wrap_links as21-block-pos-hor-center'>";
		if($cat_prev_link) echo "<a href='".$cat_prev_link."' rel='prev'>Previous set</a>";
		echo "<a class='all-cards' href='/our-cards/'>All sets</a>";
		if($cat_next_link) echo "<a href='".$cat_next_link."' rel='next'>Next set</a>";
		echo '</div>';


	}

}

// add_action('woocommerce_before_main_content','as21_wc_test2',20);
function as21_wc_test2(){
	if(is_product_category()){
		echo 'seciton---';
		echo '</div> <!-- end content-area-->
		</div><!--site-main-->';
	}
}

// add_action('woocommerce_before_shop_loop','as21_wc_test4',999);
function as21_wc_test4(){
	if(is_product_category()){
		echo 'seciton end---';
		
		echo '<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">';
	}
}

// add_action('woocommerce_after_main_content','as21_wc_test3',20);
function as21_wc_test3(){
	if(is_product_category()){
		echo 'seciton end---';
	}
}

// example page: http://aussieglo3.loc/product-category/animals/
add_action('woocommerce_after_main_content','as21_prod_cat_show_cat2',5);
function as21_prod_cat_show_cat2(){

	if( !is_product_category() or !is_page('our-cards') ) return;

	$category= get_queried_object();

	function as21_you_may_also_like_sets($category){

		// as21_get_set_all_ids();
		$set_all_ids = as21_get_set_all_ids();
		// as21_debug(0,1,': ',$set_all_ids); 

		$prod_ids = product_ids_by_cat($category->term_id);
		$cur_set_id    = get_product_id_as_set($prod_ids);
		$pos = array_search($cur_set_id, $set_all_ids );
		unset($set_all_ids[$pos]);
		$set_all_ids = array_values($set_all_ids);

		$count_set_all_ids = count($set_all_ids)-1;

		// $sets_like[0] = $set_all_ids[$pos];
		// if($pos+1 != $count_set_all_ids) {
		// 	if($set_all_ids[$pos+1]) $sets_like[1] = $set_all_ids[$pos+1];
		// }
		$rand_nums = as21_get_some_unic_rand_num($count_set_all_ids);
		// echo $columns = count($rand_nums);

		// as21_debug(0,1,'del cur set id',$set_all_ids); 
		// echo 'CUR_SET_ID: '.$cur_set_id."<BR>";
		// echo 'RADN NUMS: '; print_r($rand_nums);
		// echo '<hr>';

		foreach ($rand_nums as $num) {
			if( $set_all_ids[$num] ){
				// echo 'SET PROD ID: '.$set_all_ids[$num]."<br>";
			}
		}
		?>
		<section class="related products">

			<h2 class="section-title">You may also like</h2>

			<ul class="products columns-3"> 
				<?php 	
				foreach ($rand_nums as $num):
					$like_set_id = $set_all_ids[$num];
					if( $like_set_id ):
						$cat_id = as21_get_cat_by_product_id($like_set_id);
						// var_dump($cat_id);
						 $category_link = get_category_link( $cat_id );
						    // $cat_id    = get_product_id_as_set($like_set_id);
						?>

						<li class="post-40 product type-product status-publish has-post-thumbnail product_cat-animals instock taxable shipping-taxable purchasable product-type-simple">
							<a href="<?php echo $category_link; ?>" class="woocommerce-LoopProduct-link woocommerce-loop-product__link"><h2 class="woocommerce-loop-product__title">
								<?php echo get_the_title($like_set_id);?>
							</h2><span class="as21-wc-border-img">
								<span class="view_all">VIEW</span>
								<?php        echo $set_thumb = get_the_post_thumbnail($like_set_id, 'woocommerce_thumbnail'); ?>
							</span></a>
<?php 
echo as21_product_add_to_cart( array('id'=>$like_set_id) );
      // echo do_shortcode("[add_to_cart id='" . $like_set_id . "']"); 
?>
							</li>
								<?php
							endif;
						endforeach;
						?>
					</ul>
				</section>
				<?php


		// print_r( array_slice($set_all_ids, 13, 3,3));
		// if($pos == )

				$set_thumb = get_the_post_thumbnail($set_id, 'woocommerce_thumbnail');
		// var_dump($set_thumb);
		// echo do_shortcode("[add_to_cart id='" . $set_id . "']");
         // as21_debug(0,1,': ',$category); 

		// if($as21_debug){
				// as21_debug(0,1,'also like:',$sets_like);
				// echo '<hr> cat_id: '.$category->term_id;
				// echo 'set_id:'.$set_id;
				// as21_debug(0,1,'all prod_ids: ',$prod_ids); 
		// }

			}

			as21_you_may_also_like_sets($category);

		}
		add_action('woocommerce_before_shop_loop','as21_prod_cat_show_cat');
		function as21_prod_cat_show_cat(){

	// if(!is_archive()) return;
			if(!is_product_category()) return;

        // echo 'as21_prod_cat_show_cat';
	// echo '----aaaa---';do_shortcode('[related_products limit="12" columns="4"]');
	// echo do_shortcode('[product_categories]');

			$category= get_queried_object();
        // echo $category->term_id;
         // as21_debug(0,1,': ',$category); 
	// echo $set_id = $category->term_id;
		// echo  $set_thumb = get_the_post_thumbnail($set_id, 'woocommerce_thumbnail');
		// echo $set_thumb = get_the_post_thumbnail($set_id);



			?>
			<div class="wrap_set_cat_page">
				<div class="as21-wrap-bg-full-width-center"></div>

				<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
					<!-- <h1 class="woocommerce-products-header__title page-title"><?php woocommerce_page_title(); ?></h1> -->
					<h2 class="big-card-title"><?php woocommerce_page_title(); ?></h2>
				<?php endif; ?>
				<!-- <ul class="as21-single-set products columns-4">  -->
					<ul class="as21-single-set products columns-"1> 
						<li class="product type-product status-publish has-post-thumbnail product_cat-animals first instock taxable shipping-taxable purchasable product-type-simple">
							<?php
							woocommerce_subcategory_thumbnail($category);
							?>
						</li>
					</ul>
					<h3 class="as21-big-gray-size-title">INCLUDED</h3>
				</div>
<!-- 
        <ul class="products columns-4"> 
            <li class="post-313 product type-product status-publish has-post-thumbnail product_cat-animals first instock taxable shipping-taxable purchasable product-type-simple">
                <a href="http://aussieglo3.loc/product/10-power/" class="woocommerce-LoopProduct-link woocommerce-loop-product__link"><h2 class="woocommerce-loop-product__title">#10 Power</h2><span class="as21-wc-border-img">
                   <span class="view_all">VIEW</span><img width="324" height="322" src="http://aussieglo3.loc/wp-content/uploads/2018/07/10_power.jpg" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail wp-post-image" alt="" srcset="http://aussieglo3.loc/wp-content/uploads/2018/07/10_power.jpg 324w, http://aussieglo3.loc/wp-content/uploads/2018/07/10_power-150x150.jpg 150w, http://aussieglo3.loc/wp-content/uploads/2018/07/10_power-300x298.jpg 300w, http://aussieglo3.loc/wp-content/uploads/2018/07/10_power-100x100.jpg 100w" sizes="(max-width: 324px) 100vw, 324px"></span></a><p class="product woocommerce add_to_cart_inline " style="border:4px solid #ccc; padding: 12px;"><a href="http://aussieglo3.loc/product/10-power/" class="woocommerce-LoopProduct-link woocommerce-loop-product__link"><img class="icon-cart" src="http://aussieglo3.loc/wp-content/themes/storefront/img/cart.jpg"></a><a href="/product-category/animals/?add-to-cart=313" data-quantity="1" class="button product_type_simple add_to_cart_button ajax_add_to_cart" data-product_id="313" data-product_sku="" aria-label="Add “#10 Power” to your cart" rel="nofollow">Add to cart</a></p><div class="as21-cart-qty-wrap"><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span>2.75</span>
                    <div class="minus">-</div>
                    <input type="text" id="quantity_5b3cabd7e2376" class="input-text qty text" step="1" min="1" max="" name="quantity" value="1" title="Qty" size="2" pattern="[0-9]*" inputmode="numeric" disabled="">
                    <div class="plus">+</div>
                </div>
                <p></p></li>
            </ul>
        -->            
        <?php

    }
