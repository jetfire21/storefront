<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly


add_action( 'woocommerce_before_main_content','as21_wc_prev_next_links',10 );
function as21_wc_prev_next_links(){


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

add_action("storefront_page_after","as21_our_cards");
function as21_our_cards(){
	if( !is_page('our-cards') ) return;
	as21_you_may_also_like_sets();
}

function as21_you_may_also_like_sets(){

		// echo 'as21_you_may_also_like_sets';
	$category = get_queried_object();
		// var_dump($category);

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

// example page: http://aussieglo3.loc/product-category/animals/
add_action('woocommerce_after_main_content','as21_prod_cat_show_block_also_like',5);
function as21_prod_cat_show_block_also_like(){

	if( !is_product_category() ) return;

	as21_you_may_also_like_sets();

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
			<ul class="as21-single-set products columns-1"> 

				<li class="product type-product status-publish has-post-thumbnail product_cat-animals first instock taxable shipping-taxable purchasable product-type-simple">
					<!-- <h3 class="as21-big-gray-size-title vert-text">SET</h3> -->
					<?php
					woocommerce_subcategory_thumbnail($category);
					?>
				</li>
			</ul>
			<h3 class="as21-big-gray-size-title angle-down">INCLUDED</h3>
		</div>         
		<?php

	}

// Shipping selector not shown on cart page (safari,mobile browsers)
// add_filter( 'woocommerce_cart_needs_shipping', '__return_true' );

// function skyverge_output_cart_notices() {
//     echo do_shortcode( '[woocommerce_cart_notice type="all"]' );
// }
// add_action( 'woocommerce_before_main_content', 'skyverge_output_cart_notices' );

	add_action('woocommerce_no_products_found','as21_prod_not_found');
	function as21_prod_not_found(){
		echo '<p>--no_product---</p>';
	}


// sort products in page product category
	add_filter( 'woocommerce_get_catalog_ordering_args', 'custom_woocommerce_get_catalog_ordering_args' );
	function custom_woocommerce_get_catalog_ordering_args( $args ) {

		$args['orderby'] = 'date ID';
		// $args['order'] = 'DESC';
		$args['order'] = 'ASC';
		return $args;
	}
	add_action('wp','as21_redirect_prod_on_cat');
	function as21_redirect_prod_on_cat(){
		// var_dump(is_product());
		if( !is_product() ) return;

		$product_id = get_the_ID();


		// $terms = get_the_terms ( $product_id, 'product_cat' );
		// echo $terms[0]->term_id;
		// as21_debug(0,1,'',$terms);

		// var_dump(wc_get_product_cat_ids($product_id));
		// var_dump($product_id);
		$prouct_cat_id = wc_get_product_cat_ids($product_id);
		// echo $prouct_cat_id[0];
		$cat_link = get_category_link( $prouct_cat_id[0] );

		if ( carbon_get_post_meta($product_id, 'crb_use_as_set') == "yes") {
		// echo 'yes';
			wp_redirect( $cat_link, 301);
			exit;
		}
	}


    // cart


	add_action('wp_footer', 'as21_css_1', 999);
	function as21_css_1()
	{

        // remove_action( 'storefront_header','storefront_header_cart',60 ); 

		?>
		<style>


	</style>
	<?php
}



// add_action('wp_footer', 'as21_test2');
function as21_test2()
{
	echo '------icon cart-------';

        /**
         * Add Cart icon and count to header if WC is active
         */
        function my_wc_cart_count()
        {

        	if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins'))))
        	{

        		$count = WC()
        		->cart->cart_contents_count;
        		?><a class="cart-contents" href="<?php echo WC()
        		->cart
        		->get_cart_url(); ?>" title="<?php _e('View your shopping cart'); ?>"><?php
        		if ($count > 0)
        		{
        			?>
        			<span class="cart-contents-count"><?php echo esc_html($count); ?></span>
        			<?php
        		}
        		?></a><?php
        	}

        }
        add_action('your_theme_header_top', 'my_wc_cart_count');

        do_action('your_theme_header_top');

        /**
         * Ensure cart contents update when products are added to the cart via AJAX
         */
        // function my_header_add_to_cart_fragment( $fragments ) {
        //     ob_start();
        //     $count = WC()->cart->cart_contents_count;
        //
        ?><a class="cart-contents" href="<?php echo WC()
        ->cart
        ->get_cart_url(); ?>" title="<?php _e('View your shopping cart'); ?>"><?php
        //     if ( $count > 0 ) {
        //
        ?>
        //         <span class="cart-contents-count"><?php echo esc_html($count); ?></span>
        //         <?php
        //     }
        //
        ?></a><?php
        //     $fragments['a.cart-contents'] = ob_get_clean();
        //     return $fragments;
        // }
        // add_filter( 'woocommerce_add_to_cart_fragments', 'my_header_add_to_cart_fragment' );
        

        /* Cart icon with order count. Override default implementation. */
        function storefront_header_cart()
        {
        	?>
        	<div class="site-header-cart"><a class="cart-content cart-customlocations" href="<?php echo WC()
        	->cart
        	->get_cart_url(); ?>" title="<?php _e('View your shopping cart'); ?>">
        	<span class="cart-content-count"><?=WC()
        	->cart->cart_contents_count; ?></span></a></div>
        	<?php
        }

        // Ensure cart contents update when products are added to the cart via AJAX (place the following in functions.php).
        // Used in conjunction with https://gist.github.com/DanielSantoro/1d0dc206e242239624eb71b2636ab148
        add_filter('add_to_cart_fragments', 'woocommerce_header_add_to_cart_fragment');

        function woocommerce_header_add_to_cart_fragment($fragments)
        {
        	global $woocommerce;

        	ob_start();

        	?>
        	<a class="cart-content cart-customlocations" href="<?php echo $woocommerce
        	->cart
        	->get_cart_url(); ?>" title="<?php _e('View your shopping cart'); ?>">
        	<span class="cart-content-count"><?=$woocommerce
        	->cart->cart_contents_count; ?></span></a>
        	<?php
        	$fragments['a.cart-customlocations'] = ob_get_clean();

        	return $fragments;
        }

        echo "<hr>";
    }


    // end cart






    /**
     * Hook: woocommerce_before_shop_loop_item_title.
     *
     * @hooked woocommerce_show_product_loop_sale_flash - 10
     * @hooked woocommerce_template_loop_product_thumbnail - 10
     */
    add_action( 'woocommerce_before_shop_loop_item_title',"as21_before_prod_thumbnail",5 );
    function as21_before_prod_thumbnail(){
    	echo '<span class="as21-wc-border-img">
    	<span class="view_all">VIEW</span>';
    }    
    add_action( 'woocommerce_before_shop_loop_item_title',"as21_after_prod_thumbnail",15 );
    function as21_after_prod_thumbnail(){
    	echo '</span>'; }


    /**
     * Hook: woocommerce_after_shop_loop_item.
     *
     * @hooked woocommerce_template_loop_product_link_close - 5
     * @hooked woocommerce_template_loop_add_to_cart - 10
     */
    remove_action( 'woocommerce_after_shop_loop_item','woocommerce_template_loop_add_to_cart',10 );

    /**
     * Hook: woocommerce_after_shop_loop_item_title.
     *
     * @hooked woocommerce_template_loop_rating - 5
     * @hooked woocommerce_template_loop_price - 10
     */
    remove_action( 'woocommerce_after_shop_loop_item_title','woocommerce_template_loop_price',10 );

    remove_filter( 'woocommerce_add_to_cart_fragments', 'storefront_cart_link_fragment' );
    remove_action( 'storefront_header','storefront_primary_navigation',50 ); 
    remove_action( 'storefront_header','storefront_header_cart',60 ); 
    remove_action( 'storefront_header','storefront_primary_navigation_wrapper',42 ); 
    remove_action( 'storefront_header','storefront_primary_navigation_wrapper_close',68 ); 
    remove_action( 'storefront_header','storefront_secondary_navigation',30 ); 
    remove_action('storefront_footer','storefront_credit', 20); 
    remove_action( 'storefront_footer',                  'storefront_handheld_footer_bar',           999 );
    remove_action( 'storefront_header', 'storefront_site_branding',20);
    remove_action( 'woocommerce_after_single_product_summary',    'storefront_single_product_pagination',     30 );
    remove_action( 'storefront_after_footer',                     'storefront_sticky_single_add_to_cart',     999 );


    remove_action( 'woocommerce_after_shop_loop',        'storefront_sorting_wrapper',               9 );
    remove_action( 'woocommerce_after_shop_loop',        'woocommerce_catalog_ordering',             10 );
    remove_action( 'woocommerce_after_shop_loop',        'woocommerce_result_count',                 20 );
    remove_action( 'woocommerce_after_shop_loop',        'woocommerce_pagination',                   30 );
    remove_action( 'woocommerce_after_shop_loop',        'storefront_sorting_wrapper_close',         31 );

    remove_action( 'woocommerce_before_shop_loop',       'storefront_sorting_wrapper',               9 );
    remove_action( 'woocommerce_before_shop_loop',       'woocommerce_catalog_ordering',             10 );
    remove_action( 'woocommerce_before_shop_loop',       'woocommerce_result_count',                 20 );
    remove_action( 'woocommerce_before_shop_loop',       'storefront_woocommerce_pagination',        30 );
    remove_action( 'woocommerce_before_shop_loop',       'storefront_sorting_wrapper_close',         31 );
    // remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb',                   20 );
    remove_action( 'storefront_before_content',          'woocommerce_breadcrumb',                   10 );

    // remove_action( 'woocommerce_before_main_content',    'storefront_before_content',                10 );
    // remove_action( 'woocommerce_after_main_content',     'storefront_after_content',                 10 );

    add_action('as21_wc_before_related_section','storefront_before_content',                10 );
    add_action('as21_wc_after_related_section','storefront_after_content',                10 );

    add_action( 'storefront_header', 'as21_storefront_site_branding',20);

    // add_action( 'storefront_header','as21_storefront_primary_navigation',50 ); 
    add_action( 'storefront_header','as21_storefront_primary_navigation',30 ); 

    

    function as21_storefront_primary_navigation() {
    	?>
    	<nav id="site-navigation" class="main-navigation" role="navigation" aria-label="<?php esc_html_e( 'Primary Navigation', 'storefront' ); ?>">
    		<?php         the_widget( 'WC_Widget_Cart', 'title=' );  ?>
    		<button class="menu-toggle" aria-controls="site-navigation" aria-expanded="false"><span><?php echo esc_attr( apply_filters( 'storefront_menu_toggle_text', __( 'Menu', 'storefront' ) ) ); ?></span></button>
    		<?php
    		wp_nav_menu(
    			array(
    				'theme_location'    => 'primary',
    				'container_class'   => 'primary-navigation',
    			)
    		);

    		wp_nav_menu(
    			array(
    				'theme_location'    => 'handheld',
    				'container_class'   => 'handheld-navigation',
    			)
    		);

/*
            if (is_active_sidebar('header_right')): ?>
            <?php dynamic_sidebar('header_right'); ?>
            <?php
        endif;
*/
        // block mini-cart icon

        ?>

    </nav><!-- #site-navigation -->
    <?php
}


function as21_storefront_site_branding(){
	?>
	<div class="site-branding">
		<?php 
		if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) {
			$logo = get_custom_logo();
			$html = is_home() ? '<h1 class="logo">' . $logo . '</h1>' : $logo;
		}
		if ( '' !== get_bloginfo( 'description' ) ) {
			$html .= '<p class="site-description">' . esc_html( get_bloginfo( 'description', 'display' ) ) . '</p>';
		}
		echo $html;
		?>
	</div>
	<?php

}

function as21_storefront_secondary_navigation() {
	if ( has_nav_menu( 'secondary' ) ) {
		?>
		<nav class="secondary-navigation" role="navigation" aria-label="<?php esc_html_e( 'Secondary Navigation', 'storefront' ); ?>">
			<button class="menu-toggle" aria-controls="site-navigation" aria-expanded="false"><span><?php echo esc_attr( apply_filters( 'storefront_menu_toggle_text', __( 'Menu', 'storefront' ) ) ); ?></span></button>

			<?php
			wp_nav_menu(
				array(
					'theme_location'    => 'secondary',
					'fallback_cb'       => '',
				)
			);
			?>
		</nav><!-- #site-navigation -->
		<?php
	}
}




add_action('wp_footer', 'as21_test1');
add_action('as21_custom_do_action_minicart', 'as21_test1');
function as21_test1()
{
        // echo '===wc active!=====';
	?>
	<?php
        // display widget minicart
	if (is_active_sidebar('header_right')): ?>
	<?php dynamic_sidebar('header_right'); ?>
	<?php
endif;
?>
<?php

}



    /*
    function woocommerce_template_loop_add_to_cart( $args = array() ) {
        global $product;
        var_dump($product);
        return;

        if ( $product or 1==1) {
            $defaults = array(
                'quantity'   => 1,
                'class'      => implode( ' ', array_filter( array(
                    'button',
                    'product_type_' . $product->get_type(),
                    $product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
                    $product->supports( 'ajax_add_to_cart' ) ? 'ajax_add_to_cart' : '',
                ) ) ),
                'attributes' => array(
                    'data-product_id'  => $product->get_id(),
                    'data-product_sku' => $product->get_sku(),
                    'aria-label'       => $product->add_to_cart_description(),
                    'rel'              => 'nofollow',
                ),
            );

            $args = apply_filters( 'woocommerce_loop_add_to_cart_args', wp_parse_args( $args, $defaults ), $product );

            if ( isset( $args['attributes']['aria-label'] ) ) {
                $args['attributes']['aria-label'] = strip_tags( $args['attributes']['aria-label'] );
            }

            wc_get_template( 'loop/add-to-cart.php', $args );
        }
    }
    */


    // add_action( 'woocommerce_before_subcategory_title', 'woocommerce_subcategory_thumbnail', 10 );

    /**
     * Show subcategory thumbnails.
     *
     * @param mixed $category Category.
     display categories-sets and all data
     */
     function woocommerce_subcategory_thumbnail($category)
     {
     	global $as21_debug;
     	if( $category->term_id == 15 or $category->term_id == 21) return;
        // print_r($category);
     	$prod_ids = product_ids_by_cat($category->term_id);
     	$set_id    = get_product_id_as_set($prod_ids);

     	if($as21_debug){
     		echo '<hr> cat_id: '.$category->term_id;
     		as21_debug(0,1,'all prod_ids: ',$prod_ids); 
     	}
        // $set_thumbnail_id = get_woocommerce_term_meta($set_id, 'thumbnail_id', true);
        // var_dump( wp_get_attachment_image( $set_thumbnail_id, 'woocommerce_thumbnail') );
     	echo '<span class="as21-wc-border-img">';
     	if( is_product_category() ) echo '<h3 class="as21-big-gray-size-title vert-text">SET</h3>';
     	echo '<span class="view_all">VIEW SET</span>';
     	echo $set_thumb = get_the_post_thumbnail($set_id, 'woocommerce_thumbnail');
     	echo '</span>';
       // echo do_shortcode("[add_to_cart id='" . $set_id . "']");
     // var_dump(as21_product_add_to_cart( array('id'=>$set_id) ));
     	echo as21_product_add_to_cart( array('id'=>$set_id) );

        // var_dump($set_thumb);
        // var_dump( woocommerce_get_product_thumbnail() );
        // можно было получать только id продуктов у категории,но готовой функции для этого нет
        // $small_thumbnail_size = apply_filters('subcategory_archive_thumbnail_size', 'woocommerce_thumbnail');
        // $dimensions = wc_get_image_size($small_thumbnail_size);
        // $thumbnail_id = get_woocommerce_term_meta($category->term_id, 'thumbnail_id', true);


        // if ($thumbnail_id) {
        //     $image = wp_get_attachment_image_src($thumbnail_id, $small_thumbnail_size);
        //     $image = $image[0];
        //     $image_srcset = function_exists('wp_get_attachment_image_srcset') ? wp_get_attachment_image_srcset($thumbnail_id, $small_thumbnail_size) : false;
        //     $image_sizes = function_exists('wp_get_attachment_image_sizes') ? wp_get_attachment_image_sizes($thumbnail_id, $small_thumbnail_size) : false;
        // } else {
        //     $image = wc_placeholder_img_src();
        //     $image_srcset = false;
        //     $image_sizes = false;
        // }
        // if ($image) {
        //     // Prevent esc_url from breaking spaces in urls for image embeds.
        //     // Ref: https://core.trac.wordpress.org/ticket/23605.
        //     $image = str_replace(' ', '%20', $image);
        //     // Add responsive image markup if available.
        //     if ($image_srcset && $image_sizes) {
        //         echo '<img from-template  src="' . esc_url($image) . '" alt="' . esc_attr($category->name) . '" width="' . esc_attr($dimensions['width']) . '" height="' . esc_attr($dimensions['height']) . '" srcset="' . esc_attr($image_srcset) . '" sizes="' . esc_attr($image_sizes) . '" />';
        //     } else {
        //         echo '<img from-template src="' . esc_url($image) . '" alt="' . esc_attr($category->name) . '" width="' . esc_attr($dimensions['width']) . '" height="' . esc_attr($dimensions['height']) . '" />';
        //     }
        // }

     }



     function as21_product_add_to_cart( $atts ) {
     	global $post;
        // echo '----custom as21_product_add_to_cart()---';

        // if ( empty( $atts ) ) {
        //     return '';
        // }
    // echo '------as21_product_add_to_cart----';

     	$atts = shortcode_atts( array(
     		'id'         => '',
     		'class'      => '',
     		'quantity'   => '1',
     		'sku'        => '',
     		'style'      => 'border:4px solid #ccc; padding: 12px;',
     		'show_price' => 'true',
     	), $atts, 'product_add_to_cart' );

     	if ( ! empty( $atts['id'] ) ) {
     		$product_data = get_post( $atts['id'] );
     	} elseif ( ! empty( $atts['sku'] ) ) {
     		$product_id   = wc_get_product_id_by_sku( $atts['sku'] );
     		$product_data = get_post( $product_id );
     	} else {
     		return '';
     	}
    // var_dump($product_data);
     	$product = is_object( $product_data ) && in_array( $product_data->post_type, array( 'product', 'product_variation' ), true ) ? wc_setup_product_data( $product_data ) : false;

     	if ( ! $product ) {
     		return '';
     	}

     	ob_start();

     	echo '<p class="product woocommerce add_to_cart_inline ' . esc_attr( $atts['class'] ) . '" style="' . ( empty( $atts['style'] ) ? '' : esc_attr( $atts['style'] ) ) . '">';
     	echo '<img class="icon-cart" src="'.get_template_directory_uri().'/img/cart.jpg">';
     	woocommerce_template_loop_add_to_cart( array(
     		'quantity' => $atts['quantity'],
     	) );

     	echo '<div class="as21-cart-qty-wrap">';

     	if ( wc_string_to_bool( $atts['show_price'] ) ) {
            // @codingStandardsIgnoreStart
     		echo $product->get_price_html();
            // @codingStandardsIgnoreEnd
     	}


     	echo '
     	<div class="minus">-</div>
     	<input type="text" id="quantity_5b3cabd7e2376" class="input-text qty text" step="1" min="1" max="" name="quantity" value="1" title="Qty" size="2" pattern="[0-9]*" inputmode="numeric" disabled>
     	<div class="plus">+</div>
     	</div>
     	</p>';

        // Restore Product global in case this is shown inside a product post.
     	wc_setup_product_data( $post );

     	return ob_get_clean();
     }


     add_filter('body_class','my_class_names');
     function my_class_names( $classes ) {
    // добавим класс 'class-name' в массив классов $classes
    // var_dump(is_product());
     	if( is_product() ){  
// storefront-full-width-content, right-sidebar
     		unset($classes[11]);
     		unset($classes[13]);
        // var_dump($classes); 
        // exit;
     	}

     	return $classes;
     }


         // do_action( 'storefront_header' );
    // storefront_product_search  
     remove_action('storefront_header','storefront_product_search',40);
     function woocommerce_template_loop_category_title( $category ) {
     	?>
     	<h2 class="woocommerce-loop-category__title">
     		<?php
     		echo esc_html( $category->name );
     		?>
     	</h2>
     	<?php
     }
