<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
global $as21_debug;

$as21_debug = false; // disable debug functions
/*
if ((bool)$_GET['dev']) $as21_debug = true;
else $as21_debug = false;
*/
if (!function_exists('carbon_get_post_meta'))
{
    function carbon_get_post_meta($id, $name, $type = null)
    {
        return false;
    }
}

if (!function_exists('carbon_get_the_post_meta'))
{
    function carbon_get_the_post_meta($name, $type = null)
    {
        return false;
    }
}

if (!function_exists('carbon_get_theme_option'))
{
    function carbon_get_theme_option($name, $type = null)
    {
        return false;
    }
}

if (!function_exists('carbon_get_term_meta'))
{
    function carbon_get_term_meta($id, $name, $type = null)
    {
        return false;
    }
}

if (!function_exists('carbon_get_user_meta'))
{
    function carbon_get_user_meta($id, $name, $type = null)
    {
        return false;
    }
}

if (!function_exists('carbon_get_comment_meta'))
{
    function carbon_get_comment_meta($id, $name, $type = null)
    {
        return false;
    }
}

use Carbon_Fields\Container;
use Carbon_Fields\Field;

// my global function helpers
function as21_get_cat_by_product_id($product_id)
{

    $terms = get_the_terms($product_id, 'product_cat');
    return $terms[0]->term_id;
}
function as21_get_some_unic_rand_num($max)
{

    $outArray = array(); // хранилище для чисел
    // $max = 10; // максимальное число
    $min = 0; // минимальное число
    $count = 3; // количество чисел
    $i = 0; // счетчик
    while ($i < $count)
    {
        $chislo = mt_rand($min, $max); // генерим случайное число
        if (!in_array($chislo, $outArray))
        { // Проверяем уникальность числа.
            $outArray[$i] = $chislo; // если уникальное, то заисываем его в массив
            $i++;
        }
    }
    return $outArray;
}

function product_ids_by_cat($cat)
{
    global $wpdb;
    $product_ids_by_cat = $wpdb->get_col($wpdb->prepare("(SELECT object_id FROM {$wpdb->prefix}term_relationships WHERE term_taxonomy_id=%d )", (int)$cat));
    return $product_ids_by_cat;
}

//         $set_id    = get_product_id_as_set($prod_ids);
function get_product_id_as_set($arr_prod_ids)
{
    foreach ($arr_prod_ids as $prod_id)
    {
        if (carbon_get_post_meta($prod_id, 'crb_use_as_set') == "yes")
        {
            $set_id = $prod_id;
            break;
        }
    }
    return $set_id;
}

function as21_get_set_all_ids()
{
    global $wpdb;
    $set_all_ids = $wpdb->get_col("SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key='_crb_use_as_set' AND meta_value='yes'");
    return $set_all_ids;
}

// as21_get_set_all_ids();
// exit;
function get_products_from_category_by_ID($category_id)
{

    $products_IDs = new WP_Query(array(
        'post_type' => 'product',
        'post_status' => 'publish',
        'fields' => 'ids',
        'tax_query' => array(
            array(
                'taxonomy' => 'product_cat',
                'field' => 'term_id',
                'terms' => $category_id,
                'operator' => 'IN',
            )
        )
    ));

    return $products_IDs;
}

// work only in hooks
function as21_get_all_parents_cats()
{

    $taxonomy = 'product_cat';
    $orderby = 'name';
    $show_count = 0; // 1 for yes, 0 for no
    $pad_counts = 0; // 1 for yes, 0 for no
    $hierarchical = 1; // 1 for yes, 0 for no
    $title = '';
    $empty = 0;

    $args = array(
        'taxonomy' => $taxonomy,
        'orderby' => $orderby,
        'show_count' => $show_count,
        'pad_counts' => $pad_counts,
        'hierarchical' => $hierarchical,
        'title_li' => $title,
        'hide_empty' => $empty,
        'exclude' => '15,21'
    );
    $all_categories = get_categories($args);
    // deb_last_query();
    foreach ($all_categories as $cat)
    {
        if ($cat->category_parent == 0)
        {
            $category_id = $cat->term_id;
            // echo '<br /><a href="' . get_term_link($cat->slug, 'product_cat') . '">' . $cat->name . '</a>';
            $parent_cats[] = $category_id;
        }
    }
    // echo "<hr>";
    return $parent_cats;
}

function deb_last_query()
{

    global $wpdb;
    echo '<hr>';
    echo "<b>last query:</b> " . $wpdb->last_query . "<br>";
    echo "<b>last result:</b> ";
    echo "<pre>";
    print_r($wpdb->last_result);
    echo "</pre>";
    echo "<b>last error:</b> ";
    echo "<pre>";
    print_r($wpdb->last_error);
    echo "</pre>";
    echo '<hr>';
}

add_action('wp_enqueue_scripts', 'as21_css_js_for_theme', 999);
function as21_css_js_for_theme()
{

    // wp_deregister_script( 'jquery' );
    // wp_enqueue_script('mob-console', get_template_directory_uri()."/custom/yconsole.js",'','',true);
    // <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
    wp_enqueue_style('fonts', "https://fonts.googleapis.com/css?family=Lato|Montserrat|Open+Sans");
    // <link rel='stylesheet' id='font-awesome-css'  href='http://aussieglo3.loc/wp-content/plugins/elementor/assets/lib/font-awesome/css/font-awesome.min.css?ver=4.7.0' type='text/css' media='all' />
    // <link rel='stylesheet' id='elementor-frontend-css'  href='http://aussieglo3.loc/wp-content/plugins/elementor/assets/css/frontend.min.css?ver=2.0.16' type='text/css' media='all' /
    // if no use elementor builder on page
    // if(is_archive()) {
    if (!is_front_page())
    {
        wp_enqueue_style('font-awesome', plugins_url() . "/elementor/assets/lib/font-awesome/css/font-awesome.min.css?ver=4.7.0");
        wp_enqueue_style('elementor-frontend', plugins_url() . "/elementor/assets/css/frontend.min.css");
    }

    wp_enqueue_style('main-custom', get_template_directory_uri() . "/custom/main-custom.css?d=7.8.2018_5");
}

function as21_free_packaging_section($atts)
{
    require_once ('free-packaging.php');
    // return 'shortcode work!';
    return $out_html;
}
add_shortcode('as21_free_packaging_section', 'as21_free_packaging_section');

function as21_new_testimonials_section($atts)
{
    require_once ('new-testimonials.php');
    // return 'shortcode work!';
    return $out_html;
}
add_shortcode('as21_new_testimonials_section', 'as21_new_testimonials_section');

/* old way
Container::make('theme_options', 'Stockists')->add_tab('Stockists', array(
    Field::make('complex', 'crb_stockists_table')
        ->add_fields('row', array(
        Field::make('text', 'col1') ,
        Field::make('text', 'col2') ,
        Field::make('text', 'col3') ,
    ))
));
*/

// Container::make( 'post_meta', 'Author\'s note' )
Container::make( 'post_meta', 'Address and postcode' )
     ->show_on_post_type('stockist') // этот метод можно не писать, так как show_on_post_type('post') по умолчанию
         ->add_fields(array(
             Field::make('text', 'crb_address'),
             Field::make('text', 'crb_postcode'),
         ));



// code for woocommerce


if (class_exists('WooCommerce'))
{
    // code that requires WooCommerce
    Container::make('post_meta', 'Linked set')
        ->show_on_post_type('product')
        ->add_fields(array(
        Field::make("checkbox", "crb_use_as_set", "Use as SET")
            ->set_option_value('yes') ,
        Field::make("image", "crb_card_backend_side", "Image of backend side card:")->set_value_type('url')
        // Field::make( "select", "crb_content_align", "Text alignment" )
        // ->add_options( array(
        //  'left' => 'Left',
        //  'center' => 'Center',
        //  'right' => 'Right',
        //  '1' => $parent_cats[0],
        // ) )
        
    ));

    require_once ('wc-functions.php');

}
else
{
    // you don't appear to have WooCommerce activated
    
}
// end woocommerce functions


add_filter('storefront_register_nav_menus', 'as21_m1');
function as21_m1($menus)
{
    $menus['footer_menu1'] = __('Footer Menu 1', 'storefront');
    $menus['footer_menu2'] = __('Footer Menu 2', 'storefront');
    // print_r($menus);
    // exit;
    return $menus;
}

//     register_nav_menus( apply_filters( 'storefront_register_nav_menus', array(
//                 'primary'   => __( 'Primary Menu', 'storefront' ),
//                 'secondary' => __( 'Secondary Menu', 'storefront' ),
//                 'handheld'  => __( 'Handheld Menu', 'storefront' ),
//             ) ) );
// }
add_action('wp_footer', 'as21_scripts_inline');
function as21_scripts_inline()
{

?>
        <script>    
            jQuery(document).ready(function($){

                jQuery('.testimonial_rotator').attr('data-cycletwo-speed',500);

        // var add_cart_qty = jQuery('.add_to_cart_button').val();
        var add_cart_qty = 1;
        console.log(  );
        // jQuery('.product-category .qty').on('input',function(){
        //     add_cart_qty = jQuery(this).val();
        //     console.log( jQuery(this).parent().find('.add_to_cart_button').html() );
        //     // jQuery('.add_to_cart_button').attr('data-quantity',add_cart_qty );
        //     jQuery(this).parent().find('.add_to_cart_button').attr('data-quantity',add_cart_qty );
        // });

        $(".as21-cart-qty-wrap .plus").click(function(){
            var count = $(this).parent().find("input").val();
            count++;
            $(this).parent().find("input").val(count);
            jQuery(this).closest('.product').find('.add_to_cart_button').attr('data-quantity',count );
            console.log("btn add_to_cart: "+count);
            console.log(jQuery(this).closest('.product').find('.add_to_cart_button').html());

        });

        $(".as21-cart-qty-wrap .minus").click(function(){
            var count = $(this).parent().find("input").val();
            var prod_type = $(this).next().attr('data-prod-type');
            console.log(prod_type);
            if(prod_type == 'card') { if(count > 6) count--; }
            else {  if(count > 1) count--; }

            $(this).parent().find("input").val(count);
            jQuery(this).closest('.product-category').find('.add_to_cart_button').attr('data-quantity',count );

        });
    });



</script>
<?php
}
// return apply_filters( "{$adjacent}_post_link", $output, $format, $link, $post, $adjacent );
add_filter("next_post_link", 'as21_filter_prev_next_prod_links', 5, 5);
add_filter("previous_post_link", 'as21_filter_prev_next_prod_links', 5, 5);
function as21_filter_prev_next_prod_links($output, $format, $link, $post, $adjacent)
{
    // var_dump($post);
    // as21_debug(0,1,'as21_nav1',$post);
    // var_dump(carbon_get_post_meta($post->ID, 'crb_use_as_set'));
    if (carbon_get_post_meta($post->ID, 'crb_use_as_set') == "yes") return;
    // if($post->ID == 59) return;
    return $output;
}

add_filter('body_class', 'as21_my_body_class_names');
function as21_my_body_class_names($classes)
{
    // добавим класс 'class-name' в массив классов $classes
    if (is_page('our-cards')) $classes[] = 'our-cards';

    return $classes;
}

function as21_stockists($atts)
{
    global $as21_debug;
   /* old ways for page opston stockists
    $stockists_table = carbon_get_theme_option('crb_stockists_table', 'complex');
    // as21_debug(0,1,'',$stockists_table);
    if ($stockists_table)
    {
        $table = '<table class="as21_stockists">
            <tr><th>Name</th><th>Address</th><th>Postcode</th></tr>';
        foreach ($stockists_table as $row)
        {
            $td = '<td>' . $row['col1'] . '</td>';
            $td .= '<td>' . $row['col2'] . '</td>';
            $td .= '<td>' . $row['col3'] . '</td>';
            $tr .= "<tr>" . $td . "</tr>";
        }
        echo $table . $tr . "</table>";
    }
    */

    $params = array('post_type' => 'stockist', 'order'=>'ASC','posts_per_page'=>'-1');
    $wc_query = new WP_Query($params);
    $num = 1000;
    ?>
    <?php if ($wc_query->have_posts()) : ?>
    <?php while ($wc_query->have_posts()) :
                    $wc_query->the_post(); ?>
        <?php
        $postcode = carbon_get_post_meta(get_the_ID(), 'crb_postcode');
         $sts_bypostcode[$postcode+$num]['title'] = get_the_title(); 
         $sts_bypostcode[$postcode+$num]['address'] = carbon_get_post_meta(get_the_ID(), 'crb_address'); 
         $sts_bypostcode[$postcode+$num]['postcode'] = $postcode;  
         $num++;
     endwhile; ?>
    <?php wp_reset_postdata(); ?>
    <?php endif; ?>

    <?php
    // echo count($sts_bypostcode);
    // if($as21_debug) as21_debug(0,1,'',$sts_bypostcode);
    ksort($sts_bypostcode);
     if ($sts_bypostcode) : ?>
        <table class="as21_stockists">
                <tr><th>Name</th><th>Address</th><th>Postcode</th></tr>
    <?php foreach ($sts_bypostcode as $k => $st):?>
    <tr>
    <td><?php echo $st['title']; ?></td>
    <td><?php echo $st['address']; ?></td>
    <td><?php echo $st['postcode'];  ?></td>
    </tr>
    <?php endforeach; ?>
    </table>
    <?php else:  ?>
    <p>
         <?php _e( 'No stockists'); ?>
    </p>
    <?php endif; ?>
    

     <?php /* ?>
    <?php if ($wc_query->have_posts()) : ?>
        <table class="as21_stockists">
                <tr><th>Name</th><th>Address</th><th>Postcode</th></tr>
    <?php while ($wc_query->have_posts()) :
                    $wc_query->the_post(); ?>
    <tr>
    <td><?php the_title(); ?></td>
    <td><?php echo carbon_get_post_meta(get_the_ID(), 'crb_address'); ?></td>
    <td><?php echo carbon_get_post_meta(get_the_ID(), 'crb_postcode');  ?></td>
    </tr>
    <?php endwhile; ?>
    <?php wp_reset_postdata(); ?>
    </table>
    <?php else:  ?>
    <p>
         <?php _e( 'No stockists'); ?>
    </p>
    <?php endif; ?>
    <?php
    */
}

add_shortcode('as21_stockists', 'as21_stockists');


// add_action('admin_footer','as21_admin_custom_js',999);
function as21_admin_custom_js()
{
?>
<script>

    jQuery( document ).ready(function() {

        setTimeout(function() {
            // console.log('hook event');
            jQuery(".carbon-row").addClass('collapsed');
            jQuery(".carbon-row:first-child").removeClass('collapsed');
                }, (100)); // redirect original product page after go cf7 page
            });

</script>
<?php
}

add_action('wp_footer','as21_after_updated_wc_div_add_total_sum_check_50_if_ajax',999);
function as21_after_updated_wc_div_add_total_sum_check_50_if_ajax()
{

if( !is_cart()) return;
    ?>
    <script>
        jQuery(document).ready(function($){

            $('body').on('updated_wc_div',function(){

                var total_sum = 0;
                console.log('updated_wc_div check min sum 50$');
                $('.cart_item').each(function(){
                    var cur_total = $(this).find('.product-subtotal .amount').text().slice(1);
                    // console.log( cur_total);
                    // console.log( parseFloat(cur_total) );
                     total_sum = total_sum + parseFloat(cur_total);
                });
                // console.log(total_sum);
                $('.min-order-50').remove();
                if(total_sum < 50) $(".woocommerce-cart-form").append('<p class="min-order-50">* Minimum order is 50$, please add more products</p>');

            // console.log( html_str );
            // console.log( update_wc_div );
            });
        });
    </script>
    <?php
}



/* ******************** TMP ********************** */

// add_filter( 'woocommerce_output_related_products_args', 'jk_related_products_args' );
function jk_related_products_args($args)
{

    $args['posts_per_page'] = 4; // количество "Похожих товаров"
    $args['columns'] = 3; // количество колонок
    return $args;
}

// remove_action('woocommerce_after_single_product_summary','woocommerce_output_related_products',20);
// add_action( 'woocommerce_before_single_product', 'bbloomer_prev_next_product' );
// add_action( 'woocommerce_after_single_product', 'bbloomer_prev_next_product' );


// function bbloomer_prev_next_product(){
//     echo '<div class="prev_next_buttons">';
//     // 'product_cat' will make sure to return next/prev from current category
//     $next = next_post_link('%link', '&larr; PREVIOUS', TRUE, '', 'product_cat');
//     $previous = previous_post_link('%link', 'NEXT &rarr;', TRUE, '', 'product_cat');
//     // $previous = previous_post_link('%link', 'NEXT &rarr;', TRUE, array("59"), 'product_cat');
//     echo $previous;
//     echo $next;
//     echo '</div>';
// }
// return apply_filters( "{$adjacent}_post_link", $output, $format, $link, $post, $adjacent );
// add_filter( "next_post_link", 'as21_link_next' );
function as21_link_next($output, $format, $link, $post, $adjacent)
{
    echo '<hr>lala';
    var_dump($output);
    var_dump($format);
    var_dump($link);
    var_dump($post);
    exit;
}

// add_filter( 'the_title', $title, $post->ID );
// add_filter( 'the_title', 'as21_t1' );
function as21_t1($title)
{
    echo '<hr>t';
    // var_dump($title);
    $title = exit;
}

/* ******************** debug ********************** */


add_action('init', 'custom_type_movie');

function custom_type_movie()
{
  $labels = array(
  'name' => 'Stockists', // Основное название типа записи
  'singular_name' => 'Stockist', // отдельное название записи типа Book
  'add_new' => 'Add new',
  'add_new_item' => 'Add new',
  'edit_item' => 'Edit',
  'new_item' => 'New',
  'view_item' => 'View',
  'search_items' => 'Search',
  'not_found' =>  'Not found',
  'not_found_in_trash' => 'No found in trash',
  'parent_item_colon' => '',
  'menu_name' => 'Stockists'

  );
  $args = array(
  'labels' => $labels,
  'public' => true,
  'publicly_queryable' => true,
  'show_ui' => true,
  'show_in_menu' => true,
  'query_var' => true,
  'rewrite' => true,
  'capability_type' => 'post',
  'has_archive' => true,
  'hierarchical' => false,
  'menu_position' => null,
  'supports' => array('title')
  );
  register_post_type('stockist',$args);
}


// add_filter( 'wp_default_scripts', 'remove_jquery_migrate' );

function remove_jquery_migrate( &$scripts){
    // if(!is_admin()){
        $scripts->remove( 'jquery');
        $scripts->add( 'jquery', false, array( 'jquery-core' ) );
    // }
}
    



//  * @hooked storefront_header_cart                      - 60
//  * @hooked storefront_primary_navigation_wrapper_close - 68
//  */
// do_action( 'storefront_header' );
// add_action('admin_footer','as21_tmp_js',999);

// add_action('wp_footer','as21_tmp_js',999);
function as21_tmp_js()
{

    // global $as21_debug;
    // if (!$as21_debug) return;

?>
<script>
    jQuery(document).ready(function($){

        /* show shipping method denended selected state */

        $("#shipping_method li").show();
        console.log('---work shipping method---');

        $("#billing_state" ).on( 'change', function(e) {

            var state = $(this).val();

            console.log("click select");
            // console.log('--------');
            // console.log(state);
            // console.log( $("#shipping_method label").text() );

            function as21(){

                var li = $("#shipping_method li");
                li.hide();
                $(li).each(function(i, val){

                    var label = $(this).find("label").text();
                // console.log(i+" - " +label );
                var re = new RegExp(state);
                if( re.test(label) ) {
                    console.log(i); 
                    $(this).find('input').attr('checked', true);
                    $(this).show();
                    // console.log(i); $(this).show().css({'color':'red'});
                }
                if( label.search(/free/i) != -1 ) { 
                    // console.log('free---'+i);
                    $(this).show();
                }
                 // else console.log('state not found');
             });

            }

            var t = setInterval( as21, 100 );

            setTimeout(function() {
              clearInterval(t);
              console.log( 'стоп' );
          }, 1000);


        });   

        // function hide_shipping_li(){
            var li = $("#shipping_method li");
            li.hide();
        // }

         // var t = setInterval( hide_shipping_li, 400 );

         //    setTimeout(function() {
         //      clearInterval(t);
         //      console.log( 'стоп' );
         //  }, 1500);


         $('body').on('update_checkout',function(e){
             var li = $("#shipping_method li");
             li.hide();
         });


        // update_checkout
        // $('body').unbind('update_checkout');
        // $('body').on('update_checkout',function(){

        // });
/*
        $('body').on('update_checkout',function(e){

            console.log(e);
            console.log('update_checkout');


            function as21(){

                var li = $("#shipping_method li");
                li.hide();



                // li.eq(4).css({'color':'red'});
                // li.eq(0).hide();
                // li.eq(1).hide();
                // li.eq(2).hide();
                // li.eq(3).hide();
            }
            var t = setInterval( as21, 100 );

            setTimeout(function() {
              clearInterval(t);
              console.log( 'стоп' );
          }, 1000);

        });

        */

        // $("#order_review.woocommerce-checkout-review-order").on("DOMSubtreeModified", function() {
        //     console.log('content changed');
        //     var li = $("#shipping_method li");
        //     // li.hide();
        // });


        // console.log(wc_checkout_params);

        /* show shipping method denended selected state */


        // $('body').unbind('wc_fragments_refreshed');
        // $('body').unbind('wc_fragment_refresh');
        // $('body').unbind('updated_wc_div');


    });

</script>

<!-- 
    <script type="text/javascript" src="<?php echo get_template_directory_uri() . "/custom/yconsole.js"; ?>"></script>
    <script type="text/javascript">
        YConsole.show();
        // console.log('test-------');
        // console.log('test-------2');

    </script>
     -->
    <?php
}

// remove_action('storefront_header','storefront_header_cart',60);
// add_action('storefront_header','storefront_header_cart2',60);
function storefront_header_cart2()
{
    if (is_active_sidebar('header_right')): ?>
 <?php dynamic_sidebar('header_right'); ?>
 <?php
    endif;
}

// add_action("wp_footer", "as21_test3",999);
function as21_test3()
{

    // print_r(product_ids_by_cat(17));
    // $prod_ids = get_products_from_category_by_ID( 59 );
    // print_r($prod_ids);
    // $product_ids = get_woocommerce_term_meta( $term_id, 'product_ids', true );
    // $product_ids = get_woocommerce_term_meta( 59, 'product_cat', true );
    // $product_ids = get_woocommerce_term_meta( 17, 'display_type' );
    // $product_ids = woocommerce_get_product_subcategories(17);
    // $product_ids = wc_get_term_product_ids( 59, 'product_cat');
    // var_dump($product_ids);
    

    $taxonomy = 'product_cat';
    $orderby = 'name';
    $show_count = 0; // 1 for yes, 0 for no
    $pad_counts = 0; // 1 for yes, 0 for no
    $hierarchical = 1; // 1 for yes, 0 for no
    $title = '';
    $empty = 0;

    $args = array(
        'taxonomy' => $taxonomy,
        'orderby' => $orderby,
        'show_count' => $show_count,
        'pad_counts' => $pad_counts,
        'hierarchical' => $hierarchical,
        'title_li' => $title,
        'hide_empty' => $empty,
        'exclude' => '15,21'
    );
    $all_categories = get_categories($args);
    foreach ($all_categories as $cat)
    {
        if ($cat->category_parent == 0)
        {
            $category_id = $cat->term_id;
            echo '<br /><a href="' . get_term_link($cat->slug, 'product_cat') . '">' . $cat->name . '</a>';
            $parent_cats[] = $category_id;
        }
    }
    echo "<hr>";
    print_r($parent_cats);
    // echo carbon_get_theme_option('crb_product');
    // echo carbon_get_post_meta( 57, 'crb_use_as_set');
    

    // echo do_shortcode('[products category="17"]');
    $args = array(
        'post_type' => 'product',
        'post_status' => 'publish',
        'ignore_sticky_posts' => 1,
        'posts_per_page' => '12',
        'tax_query' => array(
            array(
                'taxonomy' => 'product_cat',
                'field' => 'term_id', //This is optional, as it defaults to 'term_id'
                // 'terms'         => 17,
                'terms' => $parent_cats,
                'operator' => 'IN'
                // Possible values are 'IN', 'NOT IN', 'AND'.
                
            ) ,
            array(
                'taxonomy' => 'product_visibility',
                'field' => 'slug',
                'terms' => 'exclude-from-catalog', // Possibly 'exclude-from-search' too
                'operator' => 'NOT IN'
            )
        )
    );

    $products = new WP_Query($args);
    // var_dump($products);
    echo '<h999r>';
    // print_r($products->posts);
    foreach ($products->posts as $product)
    {
        // echo $product->ID;
        if (carbon_get_post_meta($product->ID, 'crb_use_as_set') == "yes")
        {
            echo '<a href="' . get_permalink($product->ID) . '">SETS: id-' . $product->ID . ' ' . $product->post_title . '</a><br>';
        }
        // if( carbon_get_post_meta( $product->ID, 'crb_use_as_set') != "yes")
        // {
        // // echo get_term_link( (int)$product->ID, 'product');
        //  echo '<a href="'.get_permalink( $product->ID ).'">products: id-'.$product->ID.' '.$product->post_title.'</a><br>';
        // }
        // else
        // echo carbon_get_post_meta( 57, 'crb_use_as_set');
        
    }
    echo '<hr>';
}

add_theme_support( 'woocommerce', array(
// 'thumbnail_image_width' => 150,
'single_image_width' => 400,
) );

// function Generate_Featured_Image( $image_url, $post_id  ){
//     $upload_dir = wp_upload_dir();
//     $image_data = file_get_contents($image_url);
//     $filename = basename($image_url);
//     if(wp_mkdir_p($upload_dir['path']))     $file = $upload_dir['path'] . '/' . $filename;
//     else                                    $file = $upload_dir['basedir'] . '/' . $filename;
//     file_put_contents($file, $image_data);

//     $wp_filetype = wp_check_filetype($filename, null );
//     $attachment = array(
//         'post_mime_type' => $wp_filetype['type'],
//         'post_title' => sanitize_file_name($filename),
//         'post_content' => '',
//         'post_status' => 'inherit'
//     );
//     $attach_id = wp_insert_attachment( $attachment, $file, $post_id );
//     require_once(ABSPATH . 'wp-admin/includes/image.php');
//     $attach_data = wp_generate_attachment_metadata( $attach_id, $file );
//     $res1= wp_update_attachment_metadata( $attach_id, $attach_data );
//     $res2= set_post_thumbnail( $post_id, $attach_id );
// }

// add_action("wp_footer", "as21_feat_img");
function as21_feat_img(){

    echo "add program prod image---<br>";
    // $list_cards = 'tmp/only_name_cards.txt';
    $local_dir = dirname(__FILE__)."/tmp";

    $list_cards = file_get_contents($local_dir."/only_name_cards.txt");
    // var_dump($list_cards);
    $card_titles = explode("\r", $list_cards);
    as21_debug(0,1,'',$card_titles);


    // exit;

        global $wpdb;
        // $q1 = $wpdb->get_col("SELECT post_title FROM {$wpdb->post} WHERE post_type='product' AND meta_value='yes'");
        //$q1 = $wpdb->get_col("SELECT post_title FROM {$wpdb->posts} WHERE post_type='product'");
        $q1 = $wpdb->get_results("SELECT ID,post_title FROM {$wpdb->posts} WHERE post_type='product'");
        // as21_debug(0,1,'',$q1);
        // exit;

        $num_r = 1;
        foreach ($q1 as $k => $title) {
            // if($k >= 2) break;
            // if($k == 5) {

            if(preg_match("/#/i", $title->post_title)) {
             $titles[] = strtolower($title->post_title); 
             foreach ($card_titles as $card) {
                  $card_no_ext = str_replace(".jpg", "", $card);
                 if( preg_match("/".$card_no_ext."/i", $title->post_title) ){
                    echo $num_r." - ".$title->ID." : ".$title->post_title." - ".$card."<br>";
                     // as21_add_product_img_program_multi($title->ID, $card_no_ext);
                    $num_r++;
                    break;
                 }
             }
         }

         // }

        }
        as21_debug(0,1,'',$titles);
        echo "count prods, no set prod:". (count($titles));
        // return;
        // exit;

}


        function as21_add_product_img_program_multi($post_id, $card_no_ext){

            global $wpdb;
            // if we are in frontend
            require_once ABSPATH . 'wp-admin/includes/media.php';
            require_once ABSPATH . 'wp-admin/includes/file.php';
            require_once ABSPATH . 'wp-admin/includes/image.php';

            // $url = 'http://b2b.berghoffworldwide.ru/catalog_xml_export/productsPhoto/1399843/01.jpg';
            // $url = 'http://yroki-kompa.ru/aus-imgs/test-2.jpg';
            echo $url_f = 'http://yroki-kompa.ru/front_title/'.$card_no_ext.'_400f.jpg';
            echo $url_b = 'http://yroki-kompa.ru/back_title/'.$card_no_ext.'_400b.jpg';
            echo "<br>";
            $desc = $card_no_ext;

            // Download an image from the specified URL and attach it to a post
            // $attachment = media_sideload_image( $url, $post_id, $desc,'id' ); // arg 'id' only with wp 4.8
            // echo $add_meta = add_post_meta($post_id, '_thumbnail_id', $attachment);
            $attachment_id = media_sideload_image( $url_f, $post_id, $desc,'id' ); // arg 'id' only with wp 4.8
            echo $add_meta = add_post_meta($post_id, '_thumbnail_id', $attachment_id);
            // set featured image to post
            if($add_meta) echo 'added successfully!';
            else echo "error when added";
            echo "<hr>";
            var_dump($attachment_id);
            set_post_thumbnail( $post_id, $attachment_id );
            // set_post_thumbnail( $post_id, $attachment_id );
            // set_post_thumbnail( $post_id, $attach_id );

          $attachment_id = media_sideload_image( $url_b, $post_id, $desc,'id' ); // arg 'id' only with wp 4.8
            echo $add_meta = add_post_meta($post_id, '_thumbnail_id', $attachment_id);
            // set featured image to post
            if($add_meta) echo 'added successfully!';
            else echo "error when added";
            var_dump($attachment_id);
            // set_post_thumbnail( $post_id, $attachment_id );
            echo $img_url = wp_get_attachment_url( $attachment_id );

            $wpdb->insert(
            $wpdb->postmeta,
            array( 'post_id' => $post_id, 'meta_key'=>'_crb_card_backend_side','meta_value'=> $img_url),
            array( '%d','%s', '%s' )
             );
            // add card backend side img
                        echo "<hr>";
        }

// add_action("init", "as21_get_all_image_sizes", 999);

    /**
 * Get all the registered image sizes along with their dimensions
 *
 * @global array $_wp_additional_image_sizes
 *
 * @link http://core.trac.wordpress.org/ticket/18947 Reference ticket
 *
 * @return array $image_sizes The image sizes
 */
function as21_get_all_image_sizes() {
    echo 'images';
    global $_wp_additional_image_sizes;

    $default_image_sizes = get_intermediate_image_sizes();

    foreach ( $default_image_sizes as $size ) {
        $image_sizes[ $size ][ 'width' ] = intval( get_option( "{$size}_size_w" ) );
        $image_sizes[ $size ][ 'height' ] = intval( get_option( "{$size}_size_h" ) );
        $image_sizes[ $size ][ 'crop' ] = get_option( "{$size}_crop" ) ? get_option( "{$size}_crop" ) : false;
    }

    if ( isset( $_wp_additional_image_sizes ) && count( $_wp_additional_image_sizes ) ) {
        $image_sizes = array_merge( $image_sizes, $_wp_additional_image_sizes );
    }

    return $image_sizes;
}

// add_action("wp_footer", "wp_get_name_page_template", 999);

function wp_get_name_page_template()
{

    // global $as21_debug;
    // global $template;
    // if (!$as21_debug) return;
    global $wpdb;

    $titles = $wpdb->get_col("SELECT post_title FROM {$wpdb->posts} WHERE post_type = 'stockist' ");
    as21_debug(0,1,'',$titles);
    // exit;

    echo __FILE__;
    echo __DIR__;
    require_once 'PHPExcel.php';
    $excel = PHPExcel_IOFactory::load(__DIR__.'/stock.xls');
    var_dump($excel->getSheetCount());

        // as21_debug(0,1,'',$excel->setActiveSheetIndex(0) );
    // Получаем активный лист
    // $sheet = $excel->getActiveSheet();
    // // var_dump($sheet);
    // exit;
    $i = 1;
        // формируем массив из всех листов
    foreach($excel ->getWorksheetIterator() as $worksheet) {
       $lists[] = $worksheet->toArray();
         // as21_debug(0,1,'',$worksheet);
         // as21_debug(0,1,'',$worksheet->toArray());
         // as21_debug(0,1,'',$lists);
   }
        // as21_debug(0,1,'',$excel);
     // $new_arr = array_diff($lists, array(''));
     // $lists=array_filter(array_map('array_filter', $lists));
   $i = 0;
   foreach ($lists[0] as $list) {
        // echo $list[0];
       if(!empty($list[0])) { $lists2[] = $list; $i++; $titles_xls[] = $list[0];}
   }
   echo '<br>количество строк (c учетом пустых) -'.count($lists[0]);
   echo '<br>количество заполненых строк -'.($i-1);

    unset($lists2[0]);
   // as21_debug(0,1,'',$lists2);
   as21_debug(0,1,'',$titles_xls);
    as21_debug(0,1,'',array_diff($titles,$titles_xls) );
     as21_debug(0,1,'',array_diff($titles_xls,$titles) );
   exit;


   /*
    function get_last_add_post_id(){
        global $wpdb;
        $get_id = $wpdb->get_row("SHOW TABLE STATUS LIKE '{$wpdb->posts}'"); 
           // var_dump($get_id);
        return $last_post_id = $get_id->Auto_increment-1;
    }

       $k = 1;
       foreach ($lists2 as $list) {
            // if($k == 3) break;

        $wpdb->insert(
            $wpdb->posts,
            array( 'post_author'=>'1', 'post_title'=>$list[0], 'post_date'=>current_time('mysql'),'post_type' => 'stockist','comment_status'=>'closed','ping_status'=>'closed'),
            array( '%d','%s','%s', '%s', '%s', '%s' )
        );
        $last_post_id = get_last_add_post_id();
        $wpdb->insert(
            $wpdb->postmeta,
            array( 'post_id'=>$last_post_id, 'meta_key'=>'_crb_address', 'meta_value'=>$list[1]),
            array( '%d','%s','%s' )
        ); 
           $wpdb->insert(
            $wpdb->postmeta,
            array( 'post_id'=>$last_post_id, 'meta_key'=>'_crb_postcode', 'meta_value'=>$list[2]),
            array( '%d','%s','%d' )
        );
        echo $list[0]."<br>";
        $k++;
     }
     */




   // exit;

    echo 'needs_shipping:';
    var_dump(WC()
        ->cart
        ->needs_shipping());
}

// add_action("wp_footer", "wp_get_name_page_template2", 999);

function wp_get_name_page_template2(){

    as21_debug(0, 1, "session", $_SESSION);
    as21_debug(0, 1, "COOKIE", $_COOKIE);

    // echo basename($template);
    // полный путь с названием шаблона страницы
    echo "1- " . $template;

    echo "<br>2- " . $page_template = get_page_template_slug(get_queried_object_id()) . " | ";
    // echo $template = get_post_meta( $post->ID, '_wp_page_template', true );
    // echo $template = get_post_meta( get_queried_object_id(), '_wp_page_template', true );
    // echo "id= ".get_queried_object_id();
    echo "<br>3- " . $_SERVER['PHP_SELF'];
    echo "<br>4- " . __FILE__;
    echo "<br>5- " . $_SERVER["SCRIPT_NAME"];
    echo "<br>6- " . $_SERVER['DOCUMENT_ROOT'];
    print_r($_SERVER);
}

function as21_debug($show_text = false, $is_arr = false, $title = false, $var, $var_dump = false, $sep = "| ")
{

    // e.g: alex_debug(0, 1, "name_var", $get_tasks_by_event_id, 1);
    $debug_text = "<br>========Debug MODE==========<br>";
    if ((bool)($show_text)) echo $debug_text;
    if ((bool)($is_arr))
    {
        echo "<br>" . $title . "-";
        echo "<pre>";
        if ($var_dump) var_dump($var);
        else print_r($var);
        echo "</pre>";
    }
    else echo $title . "-" . $var;
    if (is_string($var))
    {
        if ($sep == "l") echo "<hr>";
        else echo $sep;
    }
}

/******** меняет кол-во выводимых продуктов на странице shop  ************/
add_filter('loop_shop_columns', 'loop_columns', 999);
// if (!function_exists('loop_columns')) {
function loop_columns()
{
    return 5; // 3 products per row
    // }
    
}
/******** меняет кол-во выводимых продуктов на странице shop  ************/
// $GLOBALS['woocommerce_loop'][ 'columns' ] = 5;
// remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
// add_filter('loop_shop_columns', 'loop_columns');
//  if(!function_exists('loop_columns')) { function loop_columns() { return 5; }}
//  if ( empty( $woocommerce_loop['columns'] ) ) { $woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 5 );}


// remove theme functions


