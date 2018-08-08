<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package storefront
 */

?>
<?php if( !is_front_page() ): ;?><h2 class="page-title"><?php the_title(); ?></h2> <?php endif; ?>
<?php if( is_page('stockists') ) echo '<span class="list-post-code">(listed by post code)</span>'; ?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
	/**
	 * Functions hooked in to storefront_page add_action
	 *
	 * @hooked storefront_page_header          - 10
	 * @hooked storefront_page_content         - 20
	 */
	do_action( 'storefront_page' );
	?>
</article><!-- #post-## -->
