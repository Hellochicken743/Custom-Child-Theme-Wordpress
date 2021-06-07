<?php
/**
 * Genesis Sample.
 *
 * This file adds functions to the Genesis Sample Theme.
 *
 * @package Genesis Sample
 * @author  StudioPress
 * @license GPL-2.0-or-later
 * @link    https://www.studiopress.com/
 */

// Starts the engine.
require_once get_template_directory() . '/lib/init.php';

// Sets up the Theme.
require_once get_stylesheet_directory() . '/lib/theme-defaults.php';

add_action( 'after_setup_theme', 'genesis_sample_localization_setup' );
/**
 * Sets localization (do not remove).
 *
 * @since 1.0.0
 */
function genesis_sample_localization_setup() {

	load_child_theme_textdomain( genesis_get_theme_handle(), get_stylesheet_directory() . '/languages' );

}

// Adds helper functions.
require_once get_stylesheet_directory() . '/lib/helper-functions.php';

// Adds image upload and color select to Customizer.
require_once get_stylesheet_directory() . '/lib/customize.php';

// Includes Customizer CSS.
require_once get_stylesheet_directory() . '/lib/output.php';

// Adds WooCommerce support.
require_once get_stylesheet_directory() . '/lib/woocommerce/woocommerce-setup.php';

// Adds the required WooCommerce styles and Customizer CSS.
require_once get_stylesheet_directory() . '/lib/woocommerce/woocommerce-output.php';

// Adds the Genesis Connect WooCommerce notice.
require_once get_stylesheet_directory() . '/lib/woocommerce/woocommerce-notice.php';

add_action( 'after_setup_theme', 'genesis_child_gutenberg_support' );
/**
 * Adds Gutenberg opt-in features and styling.
 *
 * @since 2.7.0
 */
function genesis_child_gutenberg_support() { // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound -- using same in all child themes to allow action to be unhooked.
	require_once get_stylesheet_directory() . '/lib/gutenberg/init.php';
}

// Registers the responsive menus.
if ( function_exists( 'genesis_register_responsive_menus' ) ) {
	genesis_register_responsive_menus( genesis_get_config( 'responsive-menus' ) );
}

add_action( 'wp_enqueue_scripts', 'genesis_sample_enqueue_scripts_styles' );
/**
 * Enqueues scripts and styles.
 *
 * @since 1.0.0
 */
function genesis_sample_enqueue_scripts_styles() {

	$appearance = genesis_get_config( 'appearance' );

	wp_enqueue_style(
		genesis_get_theme_handle() . '-fonts',
		$appearance['fonts-url'],
		[],
		genesis_get_theme_version()
	);

	wp_enqueue_style( 'dashicons' );

	if ( genesis_is_amp() ) {
		wp_enqueue_style(
			genesis_get_theme_handle() . '-amp',
			get_stylesheet_directory_uri() . '/lib/amp/amp.css',
			[ genesis_get_theme_handle() ],
			genesis_get_theme_version()
		);
	}

}

add_action( 'after_setup_theme', 'genesis_sample_theme_support', 9 );
/**
 * Add desired theme supports.
 *
 * See config file at `config/theme-supports.php`.
 *
 * @since 3.0.0
 */
function genesis_sample_theme_support() {

	$theme_supports = genesis_get_config( 'theme-supports' );

	foreach ( $theme_supports as $feature => $args ) {
		add_theme_support( $feature, $args );
	}

}

add_action( 'after_setup_theme', 'genesis_sample_post_type_support', 9 );
/**
 * Add desired post type supports.
 *
 * See config file at `config/post-type-supports.php`.
 *
 * @since 3.0.0
 */
function genesis_sample_post_type_support() {

	$post_type_supports = genesis_get_config( 'post-type-supports' );

	foreach ( $post_type_supports as $post_type => $args ) {
		add_post_type_support( $post_type, $args );
	}

}

// Adds image sizes.
add_image_size( 'sidebar-featured', 75, 75, true );
add_image_size( 'genesis-singular-images', 702, 526, true );

// Removes header right widget area.
unregister_sidebar( 'header-right' );

// Removes secondary sidebar.
unregister_sidebar( 'sidebar-alt' );

// Removes site layouts.
genesis_unregister_layout( 'content-sidebar-sidebar' );
genesis_unregister_layout( 'sidebar-content-sidebar' );
genesis_unregister_layout( 'sidebar-sidebar-content' );

// Repositions primary navigation menu.
remove_action( 'genesis_after_header', 'genesis_do_nav' );
add_action( 'genesis_header', 'genesis_do_nav', 12 );

// Repositions the secondary navigation menu.
remove_action( 'genesis_after_header', 'genesis_do_subnav' );
add_action( 'genesis_footer', 'genesis_do_subnav', 10 );

add_filter( 'wp_nav_menu_args', 'genesis_sample_secondary_menu_args' );
/**
 * Reduces secondary navigation menu to one level depth.
 *
 * @since 2.2.3
 *
 * @param array $args Original menu options.
 * @return array Menu options with depth set to 1.
 */
function genesis_sample_secondary_menu_args( $args ) {

	if ( 'secondary' === $args['theme_location'] ) {
		$args['depth'] = 1;
	}

	return $args;

}

add_filter( 'genesis_author_box_gravatar_size', 'genesis_sample_author_box_gravatar' );
/**
 * Modifies size of the Gravatar in the author box.
 *
 * @since 2.2.3
 *
 * @param int $size Original icon size.
 * @return int Modified icon size.
 */
function genesis_sample_author_box_gravatar( $size ) {

	return 90;

}

add_filter( 'genesis_comment_list_args', 'genesis_sample_comments_gravatar' );
/**
 * Modifies size of the Gravatar in the entry comments.
 *
 * @since 2.2.3
 *
 * @param array $args Gravatar settings.
 * @return array Gravatar settings with modified size.
 */
function genesis_sample_comments_gravatar( $args ) {

	$args['avatar_size'] = 60;
	return $args;

}


/**Show currency selector widget on cart page*/

genesis_register_sidebar(array (
'id' => 'currency-selector-widget',
	'name' => __( 'Currency Selector Widget', 'theme-prefix' ),
	'description' => __( 'This is the currency selector widget on cart pages.', 'theme-prefix' ),
));

add_action( 'woocommerce_before_cart_totals', 'currency_selector' );

function currency_selector() {
 
	echo '<div class="currency-selector"><div class="wrap">';
 
	genesis_widget_area( 'currency-selector-widget', array(
		'before' => '<div class="currency-selector-widget">',
		'after' => '</div>',
	) );
}

/** Register Utility Bar Widget Areas. */
genesis_register_sidebar( array(
	'id' => 'utility-bar-left',
	'name' => __( 'Utility Bar Left', 'theme-prefix' ),
	'description' => __( 'This is the left utility bar above the header.', 'theme-prefix' ),
) );

genesis_register_sidebar( array(
	'id' => 'utility-bar-right',
	'name' => __( 'Utility Bar Right', 'theme-prefix' ),
	'description' => __( 'This is the right utility bar above the header.', 'theme-prefix' ),
) );

add_action( 'genesis_before_header', 'utility_bar' );
/**
* Add utility bar above header.
*
* @author Carrie Dils
* @copyright Copyright (c) 2013, Carrie Dils
* @license GPL-2.0+
*/
function utility_bar() {
 
	echo '<div class="utility-bar"><div class="wrap">';
 
	genesis_widget_area( 'utility-bar-left', array(
		'before' => '<div class="utility-bar-left">',
		'after' => '</div>',
	) );
 
	genesis_widget_area( 'utility-bar-right', array(
		'before' => '<div class="utility-bar-right">',
		'after' => '</div>',
	) );
 
	echo '</div></div>';
 
}


/**ADD FUNCTION TO CALL HEADER TEXT*/

/** Register Header Text Widget Area. */
genesis_register_sidebar( array(
	'id' => 'header-text',
	'name' => __( 'Header Text Over Banner', 'theme-prefix' ),
	'description' => __( 'This is the text on top of the header banner. Surround in <div class="header-text-home> to get underline', 'theme-prefix' ),
) );


/*Function to call header text area*/
add_action( 'genesis_header', 'header_text' );

function header_text() {
 if ( is_front_page() ) {

	echo '<div class="header-background"><div class="header-text"><div class="wrap">';
 
	genesis_widget_area( 'header-text', array(
		'before' => '<div class="header-text">',
		'after' => '</div>',
	) );
 
	echo '</div></div></div>';
 }
}

   
/**ADD FUNCTION FOR HOME PAGE CURVE SEPARATOR*/

/** Register CURVE Widget Area. */
genesis_register_sidebar( array(
	'id' => 'separator-curve',
	'name' => __( 'Separator curve below header', 'theme-prefix' ),
	'description' => __( 'This is the curve under the header image', 'theme-prefix' ),
) );

//* Hook after header widget area below header
add_action( 'genesis_after_header', 'separator_curve' );

function separator_curve() {
if ( is_front_page() ) {

	echo '<div class="separator-curve"><div class="wrap">';
	genesis_widget_area( 'separator-curve', array(
		'before' => '<div class="separator-curve">',
		'after'  => '</div>',
	) );
}
}


//*Adjust Header

//*Add a wrap around the title and header widget area
function themeprefix_header_wrap () {
	echo '<div class="bg-gradient"><div class="headwrap">';
}
add_action( 'genesis_header','themeprefix_header_wrap', 5 );

function themeprefix_after_header_wrap () {
	echo '</div></div>';
}
add_action( 'genesis_after_header','themeprefix_after_header_wrap' );



add_action( 'genesis_header', 'logo_swap' );
function logo_swap (){   if ( is_front_page() ) {

echo '<div class="home-logo"><a href="https://www.airbornefit.com"><img src="/wp-content/uploads/2019/12/Airborne-default.png" alt="Airborne Fit Shop"></a></div>';
	
 } else { 
echo '<div class="sub-logo"><a href="http://www.airbornefit.com"><img src="/wp-content/uploads/2019/11/Airborne-Fit-Logo-Black.png" alt="Airborne Fit Shop"></a></div>';
 }
	} 



//* ADD LOWER PAGE CURVE SEPARATOR 
add_action( 'genesis_before_footer', 'separator_curve2', 5 );

function separator_curve2() {
	echo '<div class="separator-curve2"> </div>';
	
}

//* ADD LOGO TO FOOTER 
add_action( 'genesis_footer', 'footer_logo' );

function footer_logo() {
	echo '<div class="footer-logo"><a href="http://www.airbornefit.com"><img src="/wp-content/uploads/2019/11/Airborne-Fit-Logo-White@2x.png" alt="Airborne Fit Shop"></a></div>';
	
}

//* ADD shopping icons to header 
add_action( 'genesis_before', 'shopping_icons' );

function shopping_icons() {

echo '<div class="shopping-icons">
	<!--<i class="fa fa-shopping-cart" style="font-size: 16px;;margin-right: 10px;"> <a href="/cart">CART</a></i>-->
<i class="fa fa-user" style="font-size: 16px;margin-left: 20px;margin-right: 10px;"> <a href="/my-account">MY SHOP ACCOUNT</a></i> 
</div>';
}



//* HOOKS FOR WOOCOMMERCE PAGES
  
add_action ('genesis_before_content_sidebar_wrap', 'category_buttons');

function category_buttons(){
	if ( is_front_page() ) {
 echo '<div class="shop-categories"><h1 class="woocommerce-products-header__title category-title">SHOP BY CATEGORY</h1>
<div class="page-description"><div class="wp-block-woocommerce-product-categories"><div class="wc-block-product-categories is-list"><ul><li><a href="http://shop.airbornefit.com/product-category/womens-sportswear/">Womens</a>  </li><li><a href="http://shop.airbornefit.com/product-category/mens-sportswear/">Mens</a></li> <li><a href="http://shop.airbornefit.com/product-category/accessories/">Accessories</a>  </li></ul></div></div></div>
</div>';
} 
}
	  




// Changing "Default Sorting" to "ORDER BY" on shop and product settings pages
function sip_update_sorting_name( $catalog_orderby ) {
$catalog_orderby = str_replace("Default sorting", "Order By", $catalog_orderby);
return $catalog_orderby;
}
add_filter( 'woocommerce_catalog_orderby', 'sip_update_sorting_name' );
add_filter( 'woocommerce_default_catalog_orderby_options', 'sip_update_sorting_name' );



//change attribute drop down text to Select Size

add_filter( 'woocommerce_dropdown_variation_attribute_options_args', 'cinchws_filter_dropdown_args', 10 );

function cinchws_filter_dropdown_args( $args ) {
    $args['show_option_none'] = 'Select size';
    return $args;
}


// Add shop nav bar

function register_additional_menu() {
  
register_nav_menu( 'shop-menu' ,__( 'Shop Navigation Menu' ));
     
}
add_action( 'init', 'register_additional_menu' );


// adds extra menu to single product page
add_action( 'woocommerce_before_single_product_summary', 'add_shop_nav_genesis' ); 

function add_shop_nav_genesis() {

wp_nav_menu( array( 'theme_location' => 'shop-menu', 'container_class' => 'genesis-nav-menu' ) );

}

// adds extra menu to category page
add_action ('woocommerce_archive_description', 'single_category_buttons');

function single_category_buttons(){
	wp_nav_menu( array( 'theme_location' => 'shop-menu', 'container_class' => 'genesis-nav-menu' ) );
	}


// adds Note to My Account pages

add_action ('woocommerce_before_customer_login_form', 'my_account_notice');

function my_account_notice(){
	echo '<div class="book-classes">LOOKING TO REGISTER FOR CLASSES? To register or login to book classes please go <a href="https://www.airbornefit.com/classes">HERE</a>.</div> ';
	echo '<p>Please login to your shop account or register for your shop account below:</p>';
	}

add_action ('woocommerce_account_content', 'my_account_notice2');

function my_account_notice2(){
	echo '<div class="book-classes">LOOKING TO REGISTER FOR CLASSES? To register or login to book classes please go <a href="https://www.airbornefit.com/classes">HERE</a>.</div> ';
		}


add_filter( 'woocommerce_checkout_login_message', 'return_customer_message' );
 
function return_customer_message() {
return 'Already registered for a Shop Account?  ';
		
}

//adds a new privacy policy link to checkout
add_action( 'woocommerce_review_order_before_submit', 'add_privacy_checkbox', 9 );
function add_privacy_checkbox() {
woocommerce_form_field( 'privacy_policy', array(
'type' => 'checkbox',
'class' => array('form-row privacy'),
'label_class' => array('woocommerce-form__label woocommerce-form__label-for-checkbox checkbox'),
'input_class' => array('woocommerce-form__input woocommerce-form__input-checkbox input-checkbox'),
'required' => true,
'label' => 'I\'ve read and accept the <a href="https://www.airbornefit.com/privacy-policy">Privacy Policy</a>',
));
}
add_action( 'woocommerce_checkout_process', 'privacy_checkbox_error_message' );
function privacy_checkbox_error_message() {
if ( ! (int) isset( $_POST['privacy_policy'] ) ) {
wc_add_notice( __( 'You have to agree to our privacy policy in order to proceed' ), 'error' );
}
}


/**
 * @snippet       Show Additional Content on the My Account Page
 * @how-to        Get CustomizeWoo.com FREE
 * @sourcecode    https://businessbloomer.com/?p=19113
 * @author        Rodolfo Melogli (improved by Tom Lambie)
 * @compatible    WooCommerce 3.5.1
 * @donate $9     https://businessbloomer.com/bloomer-armada/
 */
 
add_action( 'woocommerce_login_form_start','bbloomer_add_login_text' );
 
function bbloomer_add_login_text() {
   echo '<h3 class="bb-login-subtitle">Registered Customers</h3><p class="bb-login-description">If you have an account with us, log in using your email address.</p>';
}
 
add_action( 'woocommerce_register_form_start','bbloomer_add_reg_text' );
 
function bbloomer_add_reg_text() {
   echo '<h3 class="bb-register-subtitle">New Customers</h3><p class="bb-register-description">By creating an account with our store, you will be able to move through the checkout process faster, store multiple shipping addresses, view and track your orders in your account and more.</p>
   <p>Your personal data will be used to support your experience throughout this website, to manage access to your account, and for other purposes described in our <a href="https://www.airbornefit.com/privacy-policy">privacy policy</a>.</p>';
}


// adds size guide link to single product page
add_action( 'woocommerce_before_add_to_cart_form', 'add_size_guide' ); 

function add_size_guide() {
echo 'Please check the <a class="size-link" href="/size-guide">size guide</a> for your perfect fit.';
echo '<p>&nbsp;</p>';
}

