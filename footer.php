<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package storefront
 */

?>

</div><!-- .col-full -->
</div><!-- #content -->

<?php do_action( 'storefront_before_footer' ); ?>

<footer id="colophon" class="site-footer" role="contentinfo">
	<div class="col-full">

		<div class="elementor-container elementor-column-gap-default">
			<div class="elementor-row">
				<div class="elementor-column elementor-col-30 foot-logo-column" data-element_type="column">
					<div class="foot-logo">
						<img src="<?php echo get_template_directory_uri();?>/img/AussieGLO_White.png">
						<!-- <span>Aussie made greeting cards</span> -->
						<?php
						if ( '' !== get_bloginfo( 'description' ) ) {
							echo $html .= '<span>' . esc_html( get_bloginfo( 'description', 'display' ) ) . '</span>';
						}
						?>
					</div>
					<p>ABN 72 085 015 621</p>
				</div>
				<div class="elementor-column elementor-col-20" data-element_type="column">
					<?php
					wp_nav_menu(
						array(
							'theme_location'	=> 'footer_menu1',
							'container_class'	=> '',
							'menu_class' => 'menu nav-menu'
						)
					);	

					?>		
				</div>

				<div class="elementor-column elementor-col-20" data-element_type="column">

					<?php

					wp_nav_menu(
						array(
							'theme_location'	=> 'footer_menu2',
							'container_class'	=> '',
							'menu_class' => 'menu nav-menu'

						)
					);
					?>			
				</div>
				<div class="elementor-column elementor-col-30 copyright" data-element_type="column">
					<p>© 2018 Copyright. </p>
					<p>Pacific Highway Marketing Communications Pty Ltd</p>
					<div class="foot-wrap-policy">
					<p><a href="/privacy-policy/">Privacy policy</a></p>
					<p><a href="/refund-and-exchange-policy/">Refund and exchange policy</a></p>
					</div>
				</div>

			</div>
			<?php
			/**
			 * Functions hooked in to storefront_footer action
			 *
			 * @hooked storefront_footer_widgets - 10
			 * @hooked storefront_credit         - 20
			 */
			// do_action( 'storefront_footer' );
			?>
		</div>

	</div><!-- .col-full -->
</footer><!-- #colophon -->

<?php do_action( 'storefront_after_footer' ); ?>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
