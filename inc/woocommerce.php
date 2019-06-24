<?php
/**
 * WooCommerce Compatibility File
 *
 * @link https://woocommerce.com/
 *
 * @package alterego
 */

/**
 * WooCommerce setup function.
 *
 * @link https://docs.woocommerce.com/document/third-party-custom-theme-compatibility/
 * @link https://github.com/woocommerce/woocommerce/wiki/Enabling-product-gallery-features-(zoom,-swipe,-lightbox)-in-3.0.0
 *
 * @return void
 */
function alterego_woocommerce_setup() {
	add_theme_support( 'woocommerce' );
	// add_theme_support( 'wc-product-gallery-zoom' );
	// add_theme_support( 'wc-product-gallery-lightbox' );
	// add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'alterego_woocommerce_setup' );

/**
 * Disable the default WooCommerce stylesheet.
 *
 * Removing the default WooCommerce stylesheet and enqueing your own will
 * protect you during WooCommerce core updates.
 *
 * @link https://docs.woocommerce.com/document/disable-the-default-stylesheet/
 */
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

/**
 * Add 'woocommerce-active' class to the body tag.
 *
 * @param  array $classes CSS classes applied to the body tag.
 * @return array $classes modified to include 'woocommerce-active' class.
 */
function alterego_woocommerce_active_body_class( $classes ) {
	$classes[] = 'woocommerce-active';

	return $classes;
}
add_filter( 'body_class', 'alterego_woocommerce_active_body_class' );

/**
 * Products per page.
 *
 * @return integer number of products.
 */
function alterego_woocommerce_products_per_page() {
	return 12;
}
add_filter( 'loop_shop_per_page', 'alterego_woocommerce_products_per_page' );

/**
 * Product gallery thumnbail columns.
 *
 * @return integer number of columns.
 */
function alterego_woocommerce_thumbnail_columns() {
	return 4;
}
add_filter( 'woocommerce_product_thumbnails_columns', 'alterego_woocommerce_thumbnail_columns' );

/**
 * Default loop columns on product archives.
 *
 * @return integer products per row.
 */
function alterego_woocommerce_loop_columns() {
	return 3;
}
add_filter( 'loop_shop_columns', 'alterego_woocommerce_loop_columns' );

/**
 * Related Products Args.
 *
 * @param array $args related products args.
 * @return array $args related products args.
 */
function alterego_woocommerce_related_products_args( $args ) {
	$defaults = array(
		'posts_per_page' => 3,
		'columns'        => 3,
	);

	$args = wp_parse_args( $defaults, $args );

	return $args;
}
add_filter( 'woocommerce_output_related_products_args', 'alterego_woocommerce_related_products_args' );

if ( ! function_exists( 'alterego_woocommerce_product_columns_wrapper' ) ) {
	/**
	 * Product columns wrapper.
	 *
	 * @return  void
	 */
	function alterego_woocommerce_product_columns_wrapper() {
		$columns = alterego_woocommerce_loop_columns();
		echo '<div class="columns-' . absint( $columns ) . '">';
	}
}
add_action( 'woocommerce_before_shop_loop', 'alterego_woocommerce_product_columns_wrapper', 40 );

if ( ! function_exists( 'alterego_woocommerce_product_columns_wrapper_close' ) ) {
	/**
	 * Product columns wrapper close.
	 *
	 * @return  void
	 */
	function alterego_woocommerce_product_columns_wrapper_close() {
		echo '</div>';
	}
}
add_action( 'woocommerce_after_shop_loop', 'alterego_woocommerce_product_columns_wrapper_close', 40 );

/**
 * Remove default WooCommerce wrapper.
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

if ( ! function_exists( 'alterego_woocommerce_wrapper_before' ) ) {
	/**
	 * Before Content.
	 *
	 * Wraps all WooCommerce content in wrappers which match the theme markup.
	 *
	 * @return void
	 */
	function alterego_woocommerce_wrapper_before() {
		?>
		<div id="primary" class="content-area">
			<main id="main" class="site-main" role="main">
			<?php
	}
}
add_action( 'woocommerce_before_main_content', 'alterego_woocommerce_wrapper_before' );

if ( ! function_exists( 'alterego_woocommerce_wrapper_after' ) ) {
	/**
	 * After Content.
	 *
	 * Closes the wrapping divs.
	 *
	 * @return void
	 */
	function alterego_woocommerce_wrapper_after() {
			?>
			</main><!-- #main -->
		</div><!-- #primary -->
		<?php
	}
}
add_action( 'woocommerce_after_main_content', 'alterego_woocommerce_wrapper_after' );

/**
 * Sample implementation of the WooCommerce Mini Cart.
 *
 * You can add the WooCommerce Mini Cart to header.php like so ...
 *
	<?php
		if ( function_exists( 'alterego_woocommerce_header_cart' ) ) {
			alterego_woocommerce_header_cart();
		}
	?>
 */

if ( ! function_exists( 'alterego_woocommerce_cart_link_fragment' ) ) {
	/**
	 * Cart Fragments.
	 *
	 * Ensure cart contents update when products are added to the cart via AJAX.
	 *
	 * @param array $fragments Fragments to refresh via AJAX.
	 * @return array Fragments to refresh via AJAX.
	 */
	function alterego_woocommerce_cart_link_fragment( $fragments ) {
		ob_start();
		alterego_woocommerce_cart_link();
		$fragments['a.cart-contents'] = ob_get_clean();

		return $fragments;
	}
}
add_filter( 'woocommerce_add_to_cart_fragments', 'alterego_woocommerce_cart_link_fragment' );

if ( ! function_exists( 'alterego_woocommerce_cart_link' ) ) {
	/**
	 * Cart Link.
	 *
	 * Displayed a link to the cart including the number of items present and the cart total.
	 *
	 * @return void
	 */
	function alterego_woocommerce_cart_link() {
		?>
		<a class="cart-contents" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php esc_attr_e( 'View your shopping cart', 'alterego' ); ?>">
			<?php
			$item_count_text = sprintf(
				/* translators: number of items in the mini cart. */
				_n( '%d item', '%d items', WC()->cart->get_cart_contents_count(), 'alterego' ),
				WC()->cart->get_cart_contents_count()
			);
			?>
			<span class="amount"><?php echo wp_kses_data( WC()->cart->get_cart_subtotal() ); ?></span> <span class="count"><?php echo esc_html( $item_count_text ); ?></span>
		</a>
		<?php
	}
}

if ( ! function_exists( 'alterego_woocommerce_header_cart' ) ) {
	/**
	 * Display Header Cart.
	 *
	 * @return void
	 */
	function alterego_woocommerce_header_cart() {
		if ( is_cart() ) {
			$class = 'current-menu-item';
		} else {
			$class = '';
		}
		?>
		<ul id="site-header-cart" class="site-header-cart">
			<li class="<?php echo esc_attr( $class ); ?>">
				<?php alterego_woocommerce_cart_link(); ?>
			</li>
			<li>
				<?php
				$instance = array(
					'title' => '',
				);

				the_widget( 'WC_Widget_Cart', $instance );
				?>
			</li>
		</ul>
		<?php
	}
}

// This is going to find us the current category id
function get_category_id() {
	$category = get_queried_object();
	return $category->term_id;
}

function category_header_background() {
	// get the category
	$category_id = get_category_id();
	$bg_color = get_field('background_color', 'product_cat_' . $category_id);
	echo 'background-color: ' . $bg_color;
}

// Remove woocommerce sidebar from ALL templates
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

// Removes Add to Cart buttons from our loop products
remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );

// Returns full sized image for our product archive images (vs. low res version)
add_filter('single_product_archive_thumbnail_size', function( $size ) {
	return 'full';
} );

remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );

// Remove notices from default positions
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_output_all_notices', 10 );
remove_action( 'woocommerce_before_single_product', 'wc_print_notices', 10 );

// Set featured image by home and category page
function get_category_image($term) {

	$category_id = get_category_id();

	if (empty($category_id)) {
		$category = get_term_by( 'slug', $term, 'product_cat' );
		$category_id = $category->term_id;
	}

	$thumbnail_id = get_woocommerce_term_meta( $category_id, 'thumbnail_id', true );
	echo wp_get_attachment_url($thumbnail_id);
}

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );

function single_header_background() {
	$post_id->ID;
	$bg_color = get_field('background_color', $post_id);
	echo 'background-color: ' . $bg_color;
}

// removes in the product loop
remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10);
// removes on the product page itself
remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10);
