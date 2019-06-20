<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package alterego
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'alterego' ); ?></a>

	<header id="masthead" class="site-header flex" style="<?php category_header_background(); ?>">

		<nav id="site-navigation" class="main-navigation flex flex-auto items-end relative">

			<a href="<?php echo get_site_url(); ?>" class="logo absolute top-0 left-0">
				<img src="<?php echo get_template_directory_uri() . '/images/alter-ego-logo.svg'; ?>" class="db">
			</a>

			<?php
			wp_nav_menu( array(
				 'theme_location' => 'menu-1',
				 'menu_id' => 'primary-menu',
				 'menu_class' => 'main-menu ma0 pa0 list'
			) );
			?>

		</nav><!-- #site-navigation -->

		<div class="site-branding">
			<img src="<?php get_category_image('home'); ?>" class="db featured-image">
		</div><!-- .site-branding -->

	</header><!-- #masthead -->

	<div id="content" class="site-content">
