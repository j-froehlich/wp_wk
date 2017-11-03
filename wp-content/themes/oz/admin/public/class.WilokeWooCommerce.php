<?php

/**
 * WilokeWooCommerce Class
 *
 * @category Wiloke WooCommerce
 * @package Wiloke Framework
 * @author Wiloke Team
 * @version 1.0
 */

if ( !defined('ABSPATH') ) {
    exit;
}

if( !class_exists('WilokeWooCommerce') ) {
	/**
	* 
	*/
	class WilokeWooCommerce  {
		
		function __construct() {

			// Remove Action Loop 			
			remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10); 
			remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5); 
			remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10); 
			remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);
			remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
			remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);
			remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
			remove_action('woocommerce_review_meta', 'woocommerce_review_display_meta', 10);
			remove_action('woocommerce_review_comment_text', 'woocommerce_review_display_comment_text', 10);



			// Filter Sale Flash
			add_filter('woocommerce_sale_flash', array($this, 'loop_sale_flash'), 10, 3);

			// Thumbnail Loop
			add_action( 'woocommerce_before_shop_loop_item_title', array($this, 'loop_product_thumbnail_open'), 9);
			add_action( 'woocommerce_before_shop_loop_item_title', array($this, 'loop_product_thumbnail_close'), 11); 

			// Action Loop
			add_action( 'woocommerce_before_shop_loop_item_title', array($this, 'loop_product_action_rate_open'), 12); 
			add_action( 'woocommerce_before_shop_loop_item_title', array($this, 'loop_product_rate'), 12); 
			add_action( 'woocommerce_before_shop_loop_item_title', array($this, 'loop_product_action'), 13); 
			add_action( 'woocommerce_before_shop_loop_item_title', array($this, 'loop_product_action_rate_close'), 20); 

			//Title Loop
			add_action( 'woocommerce_shop_loop_item_title', array($this, 'loop_product_title'), 10); 

			// Before Shop Loop 
			add_action( 'woocommerce_before_shop_loop', array($this, 'before_shop_loop_open'), 19); 
			add_action( 'woocommerce_before_shop_loop', array($this, 'before_shop_loop_close'), 31); 

			// Title Single
			add_action( 'woocommerce_single_product_summary', array($this, 'single_product_title'), 5); 

			// Single Excerpt
			add_action( 'woocommerce_single_product_summary', array($this, 'single_product_excerpt'), 20); 

			//Comment Meta
			add_action('woocommerce_review_meta',  array($this, 'single_review_meta'), 10); 

			// Comment Text
			add_action('woocommerce_review_comment_text', array($this, 'single_review_comment_text'), 10); 
			
			// Filter description heading
			add_filter('woocommerce_product_description_heading', array($this, 'single_description_heading'));

		}

		public function before_shop_loop_open() {
			echo '<div class="product-top">';
		}

		public function before_shop_loop_close() {
			echo '</div>';
		}

		public function loop_sale_flash($onsale, $post, $product) {
			$onsale = '<span class="on-sale">' . esc_html__( 'Sale!', 'oz' ) . '</span>';
			return $onsale;
		}

		public function loop_product_thumbnail_open() {
			echo '<a href="' . get_the_permalink() .'" title="'. get_the_title() .'">';
		}

		public function loop_product_thumbnail_close() {
			echo '</a>';
		}

		public function loop_product_action_rate_open() {
			echo '<div class="product__actions-star">';
		}

		public function loop_product_action_rate_close() {
			echo '</div>';
		}

		public function loop_product_rate() { ?>

			<div class="product__rating">
				<?php if ( function_exists('woocommerce_template_loop_rating') ) {
					woocommerce_template_loop_rating();
				} ?>
			</div>

			<?php 
		}

		public function loop_product_action() { ?>

			<div class="product__actions">

				<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><i class="icon-basic-link"></i></a>

				<?php if ( function_exists('woocommerce_template_loop_add_to_cart') ): ?>
					<?php woocommerce_template_loop_add_to_cart(); ?>
				<?php endif ?>
			</div>

			<?php 
		}

		public function loop_product_title() {
			echo '<h2 class="product__title"><a href="' . get_the_permalink() . '" title="'. get_the_title() .'">'. get_the_title() . '</a></h2>';
		}

		public static function loop_product_style() {
			global $wiloke;

			if ( isset($wiloke->aThemeOptions['woocommerce_shop_style']) ) {
				return $wiloke->aThemeOptions['woocommerce_shop_style'];
			}

			return '';
		}

		public function single_product_title() {
			echo '<h1 class="product-single__title">'. get_the_title() .'</h1>';
		}

		public function single_product_excerpt() {

			$excerpt = get_the_excerpt();

			if ( !empty($excerpt) ) : ?>
				<div class="product__description">
					<?php echo $excerpt; ?>
				</div>
			<?php endif;
		}

		public function single_review_meta() {

			global $comment;

			if ( '0' === $comment->comment_approved ) { ?>

				<p class="meta"><?php esc_attr_e( 'Your comment is awaiting approval', 'oz' ); ?></p>

			<?php } else { ?>

				<cite class="fn"><?php comment_author(); ?></cite>

				<span class="comment__date"><?php echo comment_date(); ?></span>

			<?php }

		}

		public function single_description_heading() {
			return false;
		}

		public function single_review_comment_text() {
			echo '<div itemprop="description" class="comment__content">';
				comment_text();
			echo '</div>';
		}
	}

	new WilokeWooCommerce();
}