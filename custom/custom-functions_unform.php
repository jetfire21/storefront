<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

global $as21_debug;

// $as21_debug = false; // disable debug functions
if( (bool)$_GET['dev'] ) $as21_debug = true;
 else $as21_debug = false;


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

function as21_get_cat_by_product_id($product_id){

   $terms = get_the_terms ( $product_id, 'product_cat' );
   return $terms[0]->term_id;
}
function as21_get_some_unic_rand_num($max)
{

            $outArray = array(); // хранилище для чисел
            // $max = 10; // максимальное число
            $min = 0; // минимальное число
            $count = 3; // количество чисел
            $i = 0; // счетчик
            while($i<$count){
                $chislo = mt_rand($min, $max); // генерим случайное число
                if(!in_array($chislo, $outArray)){ // Проверяем уникальность числа.
                    $outArray[$i] = $chislo; // если уникальное, то заисываем его в массив
                    $i++;
                }
            }
            return  $outArray;
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
            $set_id = false;
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

        function as21_get_set_all_ids(){
            global $wpdb;
            $set_all_ids = $wpdb->get_col("(SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key='_crb_use_as_set' AND meta_value='yes')");
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
        function as21_get_all_parents_cats(){

            $taxonomy       = 'product_cat';
            $orderby        = 'name';
        $show_count     = 0; // 1 for yes, 0 for no
        $pad_counts     = 0; // 1 for yes, 0 for no
        $hierarchical   = 1; // 1 for yes, 0 for no
        $title          = '';
        $empty          = 0;

        $args           = array(
            'taxonomy'                => $taxonomy,
            'orderby'                => $orderby,
            'show_count'                => $show_count,
            'pad_counts'                => $pad_counts,
            'hierarchical'                => $hierarchical,
            'title_li'                => $title,
            'hide_empty'                => $empty,
            'exclude'                => '15,21'
        );
        $all_categories = get_categories($args);
        // deb_last_query();
        foreach ($all_categories as $cat)
        {
            if ($cat->category_parent == 0)
            {
                $category_id    = $cat->term_id;
                // echo '<br /><a href="' . get_term_link($cat->slug, 'product_cat') . '">' . $cat->name . '</a>';
                $parent_cats[] = $category_id;
            }
        }
        // echo "<hr>";
        return $parent_cats;
    }



    function deb_last_query(){

        global $wpdb;
        echo '<hr>';
        echo "<b>last query:</b> ".$wpdb->last_query."<br>";
        echo "<b>last result:</b> "; echo "<pre>"; print_r($wpdb->last_result); echo "</pre>";
        echo "<b>last error:</b> "; echo "<pre>"; print_r($wpdb->last_error); echo "</pre>";
        echo '<hr>';
    }


    add_action( 'wp_enqueue_scripts', 'as21_css_js_for_theme',999 );
    function as21_css_js_for_theme(){

  // wp_deregister_script( 'jquery' );
   // wp_enqueue_script('mob-console', get_template_directory_uri()."/custom/yconsole.js",'','',true);
// <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
        wp_enqueue_style( 'fonts',"https://fonts.googleapis.com/css?family=Lato|Montserrat|Open+Sans");
    // <link rel='stylesheet' id='font-awesome-css'  href='http://aussieglo3.loc/wp-content/plugins/elementor/assets/lib/font-awesome/css/font-awesome.min.css?ver=4.7.0' type='text/css' media='all' />
// <link rel='stylesheet' id='elementor-frontend-css'  href='http://aussieglo3.loc/wp-content/plugins/elementor/assets/css/frontend.min.css?ver=2.0.16' type='text/css' media='all' /

    // if no use elementor builder on page
    // if(is_archive()) {
        if( !is_front_page()) {
            wp_enqueue_style( 'font-awesome', plugins_url()."/elementor/assets/lib/font-awesome/css/font-awesome.min.css?ver=4.7.0");
            wp_enqueue_style( 'elementor-frontend', plugins_url()."/elementor/assets/css/frontend.min.css");
        }

        wp_enqueue_style( 'main-custom', get_template_directory_uri()."/custom/main-custom.css?d=23.7.2018");
    }


    function as21_free_packaging_section($atts){
        require_once('free-packaging.php');
        // return 'shortcode work!';
        return $out_html;
    }
    add_shortcode('as21_free_packaging_section', 'as21_free_packaging_section');

    function as21_new_testimonials_section($atts){
        require_once('new-testimonials.php');
        // return 'shortcode work!';
        return $out_html;
    }
    add_shortcode('as21_new_testimonials_section', 'as21_new_testimonials_section');




    Container::make( 'theme_options', 'Stockists' )
    ->add_tab( 'Stockists', array(
        Field::make('complex', 'crb_stockists_table')
        ->add_fields('row', array(
            Field::make('text', 'col1'),
            Field::make('text', 'col2'),
            Field::make('text', 'col3'),
        ))
    ) );



// code for woocommerce


    if (class_exists('WooCommerce'))
    {
    // code that requires WooCommerce

        Container::make('post_meta', 'Linked set')
        ->show_on_post_type('product')
        ->add_fields(array(
            Field::make("checkbox", "crb_use_as_set", "Use as SET")
            ->set_option_value('yes') ,
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



    add_filter( 'storefront_register_nav_menus','as21_m1');
    function as21_m1($menus){
        $menus['footer_menu1'] = __( 'Footer Menu 1', 'storefront' );
        $menus['footer_menu2'] = __( 'Footer Menu 2', 'storefront' );
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

    add_action('wp_footer','as21_scripts_inline');
    function as21_scripts_inline(){

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
            if(count > 1) count--;
            $(this).parent().find("input").val(count);
            jQuery(this).closest('.product-category').find('.add_to_cart_button').attr('data-quantity',count );

        });
    });



</script>
<?php
}
// return apply_filters( "{$adjacent}_post_link", $output, $format, $link, $post, $adjacent );
add_filter( "next_post_link", 'as21_filter_prev_next_prod_links',5,5 );
add_filter( "previous_post_link", 'as21_filter_prev_next_prod_links',5,5 );
function as21_filter_prev_next_prod_links($output, $format, $link, $post, $adjacent){
    // var_dump($post);
    // as21_debug(0,1,'as21_nav1',$post);
    // var_dump(carbon_get_post_meta($post->ID, 'crb_use_as_set'));
    if( carbon_get_post_meta($post->ID, 'crb_use_as_set') == "yes") return;
    // if($post->ID == 59) return;
    return $output;
}

add_filter('body_class','as21_my_body_class_names');
function as21_my_body_class_names( $classes ) {
    // добавим класс 'class-name' в массив классов $classes
    if( is_page('our-cards') ) $classes[] = 'our-cards';

    return $classes;
}


    function as21_stockists($atts){
        $stockists_table = carbon_get_theme_option('crb_stockists_table', 'complex');
        // as21_debug(0,1,'',$stockists_table);
        if($stockists_table){
            $table = '<table class="as21_stockists">
            <tr><th>Name</th><th>Address</th><th>Postcode</th></tr>';
            foreach ($stockists_table as $row) {
                $td = '<td>'.$row['col1'] . '</td>';
                $td .= '<td>'.$row['col2'] . '</td>';
                $td .= '<td>'.$row['col3'] . '</td>';
                $tr .= "<tr>".$td."</tr>";
            }
            echo $table.$tr."</table>";
        }
    }
    add_shortcode('as21_stockists', 'as21_stockists');




/* ******************** TMP ********************** */



// add_filter( 'woocommerce_output_related_products_args', 'jk_related_products_args' );
function jk_related_products_args( $args ) {

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
function as21_link_next($output, $format, $link, $post, $adjacent){
    echo '<hr>lala';
    var_dump($output);
    var_dump($format);
    var_dump($link);
    var_dump($post);
    exit;
}

// add_filter( 'the_title', $title, $post->ID );
// add_filter( 'the_title', 'as21_t1' );
function as21_t1($title){
    echo '<hr>t';
    // var_dump($title);
    $title = 
    exit;
}











/* ******************** debug ********************** */



        //  * @hooked storefront_header_cart                      - 60
        //  * @hooked storefront_primary_navigation_wrapper_close - 68
        //  */
        // do_action( 'storefront_header' ); 
// add_action('wp_head','as21_tmp_js',1);
function as21_tmp_js(){

    global $as21_debug;
    if(!$as21_debug) return;

    ?>
    <script type="text/javascript" src="<?php echo get_template_directory_uri()."/custom/yconsole.js";?>"></script>
    <script type="text/javascript">
        YConsole.show();
        console.log('test-------');
        console.log('test-------2');

    </script>
    <?php
}




// remove_action('storefront_header','storefront_header_cart',60);
        // add_action('storefront_header','storefront_header_cart2',60);
function storefront_header_cart2(){
 if (is_active_sidebar('header_right')): ?>
 <?php dynamic_sidebar('header_right'); ?>
 <?php
endif;
}

// add_action("wp_footer", "as21_test3",999);

function as21_test3(){

        // print_r(product_ids_by_cat(17));

        // $prod_ids = get_products_from_category_by_ID( 59 );
        // print_r($prod_ids);
        // $product_ids = get_woocommerce_term_meta( $term_id, 'product_ids', true );
        // $product_ids = get_woocommerce_term_meta( 59, 'product_cat', true );
        // $product_ids = get_woocommerce_term_meta( 17, 'display_type' );
        // $product_ids = woocommerce_get_product_subcategories(17);
        // $product_ids = wc_get_term_product_ids( 59, 'product_cat');
        // var_dump($product_ids);


    $taxonomy       = 'product_cat';
    $orderby        = 'name';
        $show_count     = 0; // 1 for yes, 0 for no
        $pad_counts     = 0; // 1 for yes, 0 for no
        $hierarchical   = 1; // 1 for yes, 0 for no
        $title          = '';
        $empty          = 0;

        $args           = array(
            'taxonomy'                => $taxonomy,
            'orderby'                => $orderby,
            'show_count'                => $show_count,
            'pad_counts'                => $pad_counts,
            'hierarchical'                => $hierarchical,
            'title_li'                => $title,
            'hide_empty'                => $empty,
            'exclude'                => '15,21'
        );
        $all_categories = get_categories($args);
        foreach ($all_categories as $cat)
        {
            if ($cat->category_parent == 0)
            {
                $category_id    = $cat->term_id;
                echo '<br /><a href="' . get_term_link($cat->slug, 'product_cat') . '">' . $cat->name . '</a>';
                $parent_cats[] = $category_id;
            }
        }
        echo "<hr>";
        print_r($parent_cats);
        // echo carbon_get_theme_option('crb_product');
        // echo carbon_get_post_meta( 57, 'crb_use_as_set');
        

        // echo do_shortcode('[products category="17"]');
        $args     = array(
            'post_type'          => 'product',
            'post_status'          => 'publish',
            'ignore_sticky_posts'          => 1,
            'posts_per_page'          => '12',
            'tax_query'          => array(
                array(
                    'taxonomy'          => 'product_cat',
                    'field'          => 'term_id', //This is optional, as it defaults to 'term_id'
                    // 'terms'         => 17,
                    'terms'          => $parent_cats,
                    'operator'          => 'IN'
                    // Possible values are 'IN', 'NOT IN', 'AND'.
                    
                ) ,
                array(
                    'taxonomy'          => 'product_visibility',
                    'field'          => 'slug',
                    'terms'          => 'exclude-from-catalog', // Possibly 'exclude-from-search' too
                    'operator'          => 'NOT IN'
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



    add_action("wp_footer", "wp_get_name_page_template",999);

    function wp_get_name_page_template()
    {

        global $as21_debug;
        global $template;
        if(!$as21_debug) return;

        echo 'needs_shipping:'; var_dump( WC()->cart->needs_shipping() );

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



    function as21_debug ( $show_text = false, $is_arr = false, $title = false, $var, $var_dump = false, $sep = "| "){

    // e.g: alex_debug(0, 1, "name_var", $get_tasks_by_event_id, 1);
        $debug_text = "<br>========Debug MODE==========<br>";
        if( (bool)($show_text) ) echo $debug_text;
        if( (bool)($is_arr) ){
            echo "<br>".$title."-";
            echo "<pre>";
            if($var_dump) var_dump($var); else print_r($var);
            echo "</pre>";
        } else echo $title."-".$var;
        if( is_string($var) ) { if($sep == "l") echo "<hr>"; else echo $sep; }
    }

    /******** меняет кол-во выводимых продуктов на странице shop  ************/
    add_filter('loop_shop_columns', 'loop_columns',999);
    // if (!function_exists('loop_columns')) {
    function loop_columns() {
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



