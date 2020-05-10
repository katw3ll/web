<?php
////////////////////////////////////////////////////////////////////
// Settig Theme-options
////////////////////////////////////////////////////////////////////
include_once( trailingslashit( get_template_directory() ) . 'lib/plugin-activation.php' );
include_once( trailingslashit( get_template_directory() ) . 'lib/theme-config.php' );
include_once( trailingslashit( get_template_directory() ) . 'lib/metaboxes.php' );
include_once( trailingslashit( get_template_directory() ) . 'lib/include-kirki.php' );
require_once( trailingslashit( get_template_directory() ) . 'lib/customize-pro/class-customize.php' );

add_action( 'after_setup_theme', 'alpha_store_setup' );

if ( !function_exists( 'alpha_store_setup' ) ) :

	function alpha_store_setup() {
		// Theme lang
		load_theme_textdomain( 'alpha-store', get_template_directory() . '/languages' );

		// Add Title Tag Support
		add_theme_support( 'title-tag' );

		// Register Menus
		register_nav_menus(
		array(
			'main_menu'	 => __( 'Main Menu', 'alpha-store' ),
			'top_menu'	 => __( 'Top Menu', 'alpha-store' ),
		)
		);

		add_theme_support( 'post-thumbnails' );
		set_post_thumbnail_size( 300, 300, true );
		add_image_size( 'alpha-store-single', 688, 325, true );
		add_image_size( 'alpha-store-carousel', 270, absint( get_theme_mod( 'carousel-height', 270 ) ), true );
		add_image_size( 'alpha-store-category', 600, 600, true );
		add_image_size( 'alpha-store-widget', 60, 60, true );

		// Add Custom logo Support
		add_theme_support( 'custom-logo', array(
			'height'		 => 100,
			'width'			 => 400,
			'flex-height'	 => true,
			'flex-width'	 => true,
		) );

		// Add Custom Background Support
		$args = array(
			'default-color' => 'ffffff',
		);
		add_theme_support( 'custom-background', $args );

		add_theme_support( 'automatic-feed-links' );

		add_theme_support( 'woocommerce' );
		if ( get_theme_mod( 'woo_gallery_zoom', 0 ) == 1 ) {
			add_theme_support( 'wc-product-gallery-zoom' );
		}
		if ( get_theme_mod( 'woo_gallery_lightbox', 1 ) == 1 ) {
			add_theme_support( 'wc-product-gallery-lightbox' );
		}
		if ( get_theme_mod( 'woo_gallery_slider', 0 ) == 1 ) {
			add_theme_support( 'wc-product-gallery-slider' );
		}
	}

endif;

////////////////////////////////////////////////////////////////////
// Display a admin notices
////////////////////////////////////////////////////////////////////
add_action( 'admin_notices', 'alpha_store_admin_notice' );

function alpha_store_admin_notice() {
	global $current_user;
	$user_id = $current_user->ID;
	/* Check that the user hasn't already clicked to ignore the message */
	if ( !get_user_meta( $user_id, 'alpha_store_ignore_notice' ) ) {
		echo '<div class="updated notice-info point-notice" style="position:relative;"><p>';
		printf( __( 'Like Alpha Store theme? You will <strong>LOVE Alpha Store PRO</strong>! ', 'alpha-store' ) . '<a href="' . esc_url( 'http://themes4wp.com/product/alpha-store-pro/' ) . '" target="_blank">' . __( 'Click here for all the exciting features.', 'alpha-store' ) . '</a><a href="%1$s" class="notice-dismiss dashicons dashicons-dismiss dashicons-dismiss-icon"></a>', '?alpha_store_notice_ignore=0' );
		echo "</p></div>";
	}
}

add_action( 'admin_init', 'alpha_store_notice_ignore' );

function alpha_store_notice_ignore() {
	global $current_user;
	$user_id = $current_user->ID;
	/* If user clicks to ignore the notice, add that to their user meta */
	if ( isset( $_GET[ 'alpha_store_notice_ignore' ] ) && '0' == $_GET[ 'alpha_store_notice_ignore' ] ) {
		add_user_meta( $user_id, 'alpha_store_ignore_notice', 'true', true );
	}
}

////////////////////////////////////////////////////////////////////
// Enqueue Styles (normal style.css and bootstrap.css)
////////////////////////////////////////////////////////////////////
function alpha_store_theme_stylesheets() {
	wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/css/bootstrap.css', array(), '3.3.4', 'all' );
	wp_enqueue_style( 'alpha-store-stylesheet', get_stylesheet_uri(), array(), '1.4.1', 'all' );
	// load Font Awesome css
	wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/css/font-awesome.min.css', array(), '4.7.0' );
	wp_enqueue_style( 'flexslider', get_template_directory_uri() . '/css/flexslider.css', array(), '2.6.3' );
}

add_action( 'wp_enqueue_scripts', 'alpha_store_theme_stylesheets' );

////////////////////////////////////////////////////////////////////
// Register Bootstrap JS with jquery
////////////////////////////////////////////////////////////////////
function alpha_store_theme_js() {
	wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/js/bootstrap.js', array( 'jquery' ), '3.3.4' );
	wp_enqueue_script( 'alpha-store-theme-js', get_template_directory_uri() . '/js/customscript.js', array( 'jquery', 'flexslider' ), '1.3.0' );
	wp_localize_script( 'alpha-store-theme-js', 'objectL10n', array(
		'compare'	 => esc_html__( 'Compare Product', 'alpha-store' ),
		'qview'		 => esc_html__( 'Quick View', 'alpha-store' ),
	) );
	wp_enqueue_script( 'flexslider', get_template_directory_uri() . '/js/jquery.flexslider-min.js', array( 'jquery' ), '2.6.3' );
}

add_action( 'wp_enqueue_scripts', 'alpha_store_theme_js' );

////////////////////////////////////////////////////////////////////
// Register Custom Navigation Walker include custom menu widget to use walkerclass
////////////////////////////////////////////////////////////////////

require_once(trailingslashit( get_template_directory() ) . 'lib/wp_bootstrap_navwalker.php');

////////////////////////////////////////////////////////////////////
// Register Widgets
////////////////////////////////////////////////////////////////////

add_action( 'widgets_init', 'alpha_store_widgets_init' );

function alpha_store_widgets_init() {
	register_sidebar(
	array(
		'name'			 => __( 'Right Sidebar', 'alpha-store' ),
		'id'			 => 'alpha-store-right-sidebar',
		'before_widget'	 => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'	 => '</aside>',
		'before_title'	 => '<h3 class="widget-title">',
		'after_title'	 => '</h3>',
	) );

	register_sidebar(
	array(
		'name'			 => __( 'Left Sidebar', 'alpha-store' ),
		'id'			 => 'alpha-store-left-sidebar',
		'before_widget'	 => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'	 => '</aside>',
		'before_title'	 => '<h3 class="widget-title">',
		'after_title'	 => '</h3>',
	) );
	register_sidebar(
	array(
		'name'			 => __( 'Homepage Sidebar', 'alpha-store' ),
		'id'			 => 'alpha-store-home-sidebar',
		'before_widget'	 => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'	 => '</aside>',
		'before_title'	 => '<h3 class="widget-title">',
		'after_title'	 => '</h3>',
	) );
	register_sidebar(
	array(
		'name'			 => __( 'Footer Section', 'alpha-store' ),
		'id'			 => 'alpha-store-footer-area',
		'description'	 => __( 'Content Footer Section', 'alpha-store' ),
		'before_widget'	 => '<div id="%1$s" class="widget %2$s col-md-' . absint( get_theme_mod( 'footer-sidebar-size', 3 ) ) . '">',
		'after_widget'	 => '</div>',
		'before_title'	 => '<h3 class="widget-title">',
		'after_title'	 => '</h3>',
	) );
}

////////////////////////////////////////////////////////////////////
// Register hook and action to set Main content area col-md- width based on sidebar declarations
////////////////////////////////////////////////////////////////////

add_action( 'alpha_store_main_content_width_hook', 'alpha_store_main_content_width_columns' );

function alpha_store_main_content_width_columns() {

	$columns = '12';

	if ( get_theme_mod( 'rigth-sidebar-check', 1 ) != 0 ) {
		$columns = $columns - absint( get_theme_mod( 'right-sidebar-size', 3 ) );
	}

	if ( get_theme_mod( 'left-sidebar-check', 0 ) != 0 ) {
		$columns = $columns - absint( get_theme_mod( 'left-sidebar-size', 3 ) );
	}

	echo $columns;
}

function alpha_store_main_content_width() {
	do_action( 'alpha_store_main_content_width_hook' );
}

////////////////////////////////////////////////////////////////////
// Theme Info page
////////////////////////////////////////////////////////////////////

if ( is_admin() && !is_child_theme() ) {
	require_once( trailingslashit( get_template_directory() ) . 'lib/welcome/welcome-screen.php' );
}

////////////////////////////////////////////////////////////////////
// Set Content Width
////////////////////////////////////////////////////////////////////

function alpha_store_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'alpha_store_content_width', 800 );
}
add_action( 'after_setup_theme', 'alpha_store_content_width', 0 );

////////////////////////////////////////////////////////////////////
// Schema.org microdata
////////////////////////////////////////////////////////////////////
function alpha_store_tag_schema() {
	$schema = 'http://schema.org/';

	// Is single post

	if ( is_single() ) {
		$type = 'WebPage';
	}
	// Is author page
	elseif ( is_author() ) {
		$type = 'ProfilePage';
	}

	// Is search results page
	elseif ( is_search() ) {
		$type = 'SearchResultsPage';
	} else {
		$type = 'WebPage';
	}

	echo 'itemscope itemtype="' . $schema . $type . '"';
}

if ( !function_exists( 'alpha_store_breadcrumb' ) ) :

////////////////////////////////////////////////////////////////////
// Breadcrumbs
////////////////////////////////////////////////////////////////////
	function alpha_store_breadcrumb() {
		global $post, $wp_query;

		// schema link

		$schema_link = 'http://data-vocabulary.org/Breadcrumb';
		$home		 = esc_html__( 'Главная', 'alpha-store' );
		$delimiter	 = ' &raquo; ';
		$homeLink	 = home_url();
		if ( is_home() || is_front_page() ) {

			// no need for breadcrumbs in homepage
		} else {
			echo '<div id="breadcrumbs" >';
			echo '<div class="breadcrumbs-inner text-right">';

			// main breadcrumbs lead to homepage

			echo '<span itemscope itemtype="' . esc_url( $schema_link ) . '"><a itemprop="url" href="' . esc_url( $homeLink ) . '">' . '<i class="fa fa-home"></i><span itemprop="title">' . $home . '</span>' . '</a></span>' . $delimiter . ' ';

			// if blog page exists

			if ( 'page' == get_option( 'show_on_front' ) && get_option( 'page_for_posts' ) ) {
				echo '<span itemscope itemtype="' . esc_url( $schema_link ) . '"><a itemprop="url" href="' . esc_url( get_permalink( get_option( 'page_for_posts' ) ) ) . '">' . '<span itemprop="title">' . esc_html__( 'Blog', 'alpha-store' ) . '</span></a></span>' . $delimiter . ' ';
			}

			if ( is_category() ) {
				$thisCat = get_category( get_query_var( 'cat' ), false );
				if ( $thisCat->parent != 0 ) {
					$category_link = get_category_link( $thisCat->parent );
					echo '<span itemscope itemtype="' . esc_url( $schema_link ) . '"><a itemprop="url" href="' . esc_url( $category_link ) . '">' . '<span itemprop="title">' . get_cat_name( $thisCat->parent ) . '</span>' . '</a></span>' . $delimiter . ' ';
				}

				$category_id	 = get_cat_ID( single_cat_title( '', false ) );
				$category_link	 = get_category_link( $category_id );
				echo '<span itemscope itemtype="' . esc_url( $schema_link ) . '"><a itemprop="url" href="' . esc_url( $category_link ) . '">' . '<span itemprop="title">' . single_cat_title( '', false ) . '</span>' . '</a></span>';
			} elseif ( is_single() && !is_attachment() ) {
				if ( get_post_type() != 'post' ) {
					$post_type	 = get_post_type_object( get_post_type() );
					$link		 = get_post_type_archive_link( get_post_type() );
					if ( $link ) {
						printf( '<span><a href="%s">%s</a></span>', esc_url( $link ), $post_type->labels->name );
						echo ' ' . $delimiter . ' ';
					}
					echo get_the_title();
				} else {
					$category = get_the_category();
					if ( $category ) {
						foreach ( $category as $cat ) {
							echo '<span itemscope itemtype="' . esc_url( $schema_link ) . '"><a itemprop="url" href="' . esc_url( get_category_link( $cat->term_id ) ) . '">' . '<span itemprop="title">' . $cat->name . '</span>' . '</a></span>' . $delimiter . ' ';
						}
					}

					echo get_the_title();
				}
			} elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() && !is_search() ) {
				$post_type = get_post_type_object( get_post_type() );
				echo $post_type->labels->singular_name;
			} elseif ( is_attachment() ) {
				$parent = get_post( $post->post_parent );
				echo '<span itemscope itemtype="' . esc_url( $schema_link ) . '"><a itemprop="url" href="' . esc_url( get_permalink( $parent ) ) . '">' . '<span itemprop="title">' . $parent->post_title . '</span>' . '</a></span>';
				echo ' ' . $delimiter . ' ' . get_the_title();
			} elseif ( is_page() && !$post->post_parent ) {
				echo '<span itemscope itemtype="' . esc_url( $schema_link ) . '"><a itemprop="url" href="' . esc_url( get_permalink() ) . '">' . '<span itemprop="title">' . get_the_title() . '</span>' . '</a></span>';
			} elseif ( is_page() && $post->post_parent ) {
				$parent_id	 = $post->post_parent;
				$breadcrumbs = array();
				while ( $parent_id ) {
					$page			 = get_page( $parent_id );
					$breadcrumbs[]	 = '<span itemscope itemtype="' . esc_url( $schema_link ) . '"><a itemprop="url" href="' . esc_url( get_permalink( $page->ID ) ) . '">' . '<span itemprop="title">' . get_the_title( $page->ID ) . '</span>' . '</a></span>';
					$parent_id		 = $page->post_parent;
				}

				$breadcrumbs = array_reverse( $breadcrumbs );
				for ( $i = 0; $i < count( $breadcrumbs ); $i++ ) {
					echo $breadcrumbs[ $i ];
					if ( $i != count( $breadcrumbs ) - 1 )
						echo ' ' . $delimiter . ' ';
				}

				echo $delimiter . '<span itemscope itemtype="' . esc_url( $schema_link ) . '"><a itemprop="url" href="' . esc_url( get_permalink() ) . '">' . '<span itemprop="title">' . the_title_attribute( 'echo=0' ) . '</span>' . '</a></span>';
			}
			elseif ( is_tag() ) {
				$tag_id = get_term_by( 'name', single_cat_title( '', false ), 'post_tag' );
				if ( $tag_id ) {
					$tag_link = get_tag_link( $tag_id->term_id );
				}

				echo '<span itemscope itemtype="' . esc_url( $schema_link ) . '"><a itemprop="url" href="' . esc_url( $tag_link ) . '">' . '<span itemprop="title">' . single_cat_title( '', false ) . '</span>' . '</a></span>';
			} elseif ( is_author() ) {
				global $author;
				$userdata = get_userdata( $author );
				echo '<span itemscope itemtype="' . esc_url( $schema_link ) . '"><a itemprop="url" href="' . esc_url( get_author_posts_url( $userdata->ID ) ) . '">' . '<span itemprop="title">' . $userdata->display_name . '</span>' . '</a></span>';
			} elseif ( is_404() ) {
				echo esc_html__( 'Error 404', 'alpha-store' );
			} elseif ( is_search() ) {
				echo esc_html__( 'Search results for', 'alpha-store' ) . ' ' . get_search_query();
			} elseif ( is_day() ) {
				echo '<span itemscope itemtype="' . esc_url( $schema_link ) . '"><a itemprop="url" href="' . esc_url( get_year_link( get_the_time( 'Y' ) ) ) . '">' . '<span itemprop="title">' . get_the_time( 'Y' ) . '</span>' . '</a></span>' . $delimiter . ' ';
				echo '<span itemscope itemtype="' . esc_url( $schema_link ) . '"><a itemprop="url" href="' . esc_url( get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) ) ) . '">' . '<span itemprop="title">' . get_the_time( 'F' ) . '</span>' . '</a></span>' . $delimiter . ' ';
				echo '<span itemscope itemtype="' . esc_url( $schema_link ) . '"><a itemprop="url" href="' . esc_url( get_day_link( get_the_time( 'Y' ), get_the_time( 'm' ), get_the_time( 'd' ) ) ) . '">' . '<span itemprop="title">' . get_the_time( 'd' ) . '</span>' . '</a></span>';
			} elseif ( is_month() ) {
				echo '<span itemscope itemtype="' . esc_url( $schema_link ) . '"><a itemprop="url" href="' . esc_url( get_year_link( get_the_time( 'Y' ) ) ) . '">' . '<span itemprop="title">' . get_the_time( 'Y' ) . '</span>' . '</a></span>' . $delimiter . ' ';
				echo '<span itemscope itemtype="' . esc_url( $schema_link ) . '"><a itemprop="url" href="' . esc_url( get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) ) ) . '">' . '<span itemprop="title">' . get_the_time( 'F' ) . '</span>' . '</a></span>';
			} elseif ( is_year() ) {
				echo '<span itemscope itemtype="' . esc_url( $schema_link ) . '"><a itemprop="url" href="' . esc_url( get_year_link( get_the_time( 'Y' ) ) ) . '">' . '<span itemprop="title">' . get_the_time( 'Y' ) . '</span>' . '</a></span>';
			}

			if ( get_query_var( 'paged' ) ) {
				if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() )
					echo ' (';
				echo esc_html__( 'Page', 'alpha-store' ) . ' ' . get_query_var( 'paged' );
				if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() )
					echo ')';
			}

			echo '</div></div>';
		}
	}

endif;
////////////////////////////////////////////////////////////////////
// Social links
////////////////////////////////////////////////////////////////////
if ( !function_exists( 'alpha_store_social_links' ) ) :

	/**
	 * This function is for social links display on header
	 *
	 * Get links through Theme Options
	 */
	function alpha_store_social_links() {
		$twp_social_links	 = array( 
			'twp_social_facebook'	 => 'facebook',
			'twp_social_twitter'	 => 'twitter', 
			'twp_social_google'		 => 'google-plus',
			'twp_social_instagram'	 => 'instagram',
			'twp_social_pin'		 => 'pinterest',
			'twp_social_youtube'	 => 'youtube',
			'twp_social_reddit'		 => 'reddit',
			'twp_social_linkedin'	 => 'linkedin',
			'twp_social_skype'		 => 'skype',
			'twp_social_vimeo'		 => 'vimeo',
			'twp_social_flickr'		 => 'flickr',
			'twp_social_dribble'	 => 'dribbble',
			'twp_social_envelope-o'	 => 'envelope-o',
			'twp_social_rss'		 => 'rss',
		);
		?>
		<div class="social-links">
			<ul>
				<?php
				$i					 = 0;
				$twp_links_output	 = '';
				foreach ( $twp_social_links as $key => $value ) {
					$link = get_theme_mod( $key, '' );
					if ( !empty( $link ) ) {
						$twp_links_output .=
						'<li><a href="' . esc_url( $link ) . '" target="_blank"><i class="fa fa-' . strtolower( $value ) . '"></i></a></li>';
					}
					$i++;
				}
				echo $twp_links_output;
				?>
			</ul>
		</div><!-- .social-links -->
		<?php
	}

endif;

////////////////////////////////////////////////////////////////////
// WooCommerce section
////////////////////////////////////////////////////////////////////
if ( class_exists( 'WooCommerce' ) ) {

////////////////////////////////////////////////////////////////////
// WooCommerce header cart
////////////////////////////////////////////////////////////////////
	if ( !function_exists( 'alpha_store_cart_link' ) ) {

		function alpha_store_cart_link() {
			?>	
			<a class="cart-contents text-right" href="<?php echo esc_url( WC()->cart->get_cart_url() ); ?>" title="<?php esc_attr_e( 'View your shopping cart', 'alpha-store' ); ?>">
				<i class="fa fa-shopping-cart"><span class="count"><?php echo absint( WC()->cart->get_cart_contents_count() ); ?></span></i><div class="amount-title"><?php echo esc_html_e( 'Cart ', 'alpha-store' ); ?></div><div class="amount-cart"><?php echo wp_kses_data( WC()->cart->get_cart_subtotal() ); ?></div> 
			</a>
			<?php
		}

	}
	if ( !function_exists( 'alpha_store_head_wishlist' ) ) {

		function alpha_store_head_wishlist() {
			if ( function_exists( 'YITH_WCWL' ) ) {
				$wishlist_url = YITH_WCWL()->get_wishlist_url();
				?>

<div class="top-wishlist text-right ">
	
<aside id="yith-woocompare-widget-2" class="widget yith-woocompare-widget">
	
<a href="/?action=yith-woocompare-view-table&amp;iframe=yes" class="compare added button" rel="nofollow" data-toggle="tooltip" title="" data-original-title="Compare Product">
<div class="fa fa-compare"><div class="count"><span><?php global $yith_woocompare;
echo $yith_woocompare->obj->list_products_html(); ?></span></div></div>

</a>
	            </aside>	
</div>

				<div class="top-wishlist text-right <?php if ( get_theme_mod( 'cart-top-icon', 1 ) == 0 ) { echo 'single-wishlist'; } ?>">
					<a href="<?php echo esc_url( $wishlist_url ); ?>" title="<?php esc_attr_e( 'Wishlist', 'alpha-store' ); ?>" data-toggle="tooltip" data-placement="top">
						<div class="fa fa-heart"><div class="count"><span><?php echo absint( yith_wcwl_count_products() ); ?></span></div></div>
					</a>
					
				
					
					
				</div>
				<?php
			}
		}

	}
	// Header wishlist icon ajax update
	add_action( 'wp_ajax_yith_wcwl_update_single_product_list', 'alpha_store_head_wishlist' );
	add_action( 'wp_ajax_nopriv_yith_wcwl_update_single_product_list', 'alpha_store_head_wishlist' );

	if ( !function_exists( 'alpha_store_header_cart' ) ) {

		function alpha_store_header_cart() {
			?>
			<div class="header-cart text-right col-md-4 text-center-sm text-center-xs no-gutter">
				<div class="header-cart-block">
					<?php if ( get_theme_mod( 'cart-top-icon', 1 ) == 1 ) { ?>
						<div class="header-cart-inner">
							<?php alpha_store_cart_link(); ?>
							<ul class="site-header-cart menu list-unstyled">
								<li>
									<?php the_widget( 'WC_Widget_Cart', 'title=' ); ?>
								</li>
							</ul>
						</div>
					<?php } ?>
					<?php
					if ( get_theme_mod( 'wishlist-top-icon', 0 ) != 0 ) {
						alpha_store_head_wishlist();
					}
					?>
				</div>
			</div>
			<?php
		}

	}
	// Ensure cart contents update when products are added to the cart via AJAX
	if ( !function_exists( 'alpha_store_header_add_to_cart_fragment' ) ) {
		add_filter( 'woocommerce_add_to_cart_fragments', 'alpha_store_header_add_to_cart_fragment' );

		function alpha_store_header_add_to_cart_fragment( $fragments ) {
			ob_start();

			alpha_store_cart_link();

			$fragments[ 'a.cart-contents' ] = ob_get_clean();

			return $fragments;
		}

	}
////////////////////////////////////////////////////////////////////
// Change number of products displayed per page
////////////////////////////////////////////////////////////////////  
	add_filter( 'loop_shop_per_page', 'alpha_store_new_loop_shop_per_page', 20 );

	function alpha_store_new_loop_shop_per_page( $cols ) {
	  // $cols contains the current number of products per page based on the value stored on Options -> Reading
	  // Return the number of products you wanna show per page.
	  $cols = absint( get_theme_mod( 'archive_number_products', 24 ) );
	  return $cols;
	}
////////////////////////////////////////////////////////////////////
// Change number of WooCommerce products per row
////////////////////////////////////////////////////////////////////
	add_filter( 'loop_shop_columns', 'alpha_store_loop_columns' );
	if ( !function_exists( 'alpha_store_loop_columns' ) ) {

		function alpha_store_loop_columns() {
			return absint( get_theme_mod( 'archive_number_columns', 4 ) );
		}

	}

////////////////////////////////////////////////////////////////////
// Archive product wishlist button
////////////////////////////////////////////////////////////////////  
	function alpha_store_wishlist_products() {
		if ( function_exists( 'YITH_WCWL' ) ) {
			global $product;
			$url			 = add_query_arg( 'add_to_wishlist', $product->get_id() );
			$id				 = $product->get_id();
			$wishlist_url	 = YITH_WCWL()->get_wishlist_url();
			?>  
			<div class="add-to-wishlist-custom add-to-wishlist-<?php echo esc_attr( $id ); ?>">
				<div class="yith-wcwl-add-button show" style="display:block" data-toggle="tooltip" data-placement="top" title="<?php esc_attr_e( 'Add to Wishlist', 'alpha-store' ); ?>"> <a href="<?php echo esc_url( $url ); ?>" rel="nofollow" data-product-id="<?php echo esc_attr( $id ); ?>" data-product-type="simple" class="add_to_wishlist"></a><img src="<?php echo get_template_directory_uri() . '/img/loading.gif'; ?>" class="ajax-loading" alt="loading" width="16" height="16"></div>
				<div class="yith-wcwl-wishlistaddedbrowse hide" style="display:none;"> <span class="feedback"><?php esc_html_e( 'Added!', 'alpha-store' ); ?></span> <a href="<?php echo esc_url( $wishlist_url ); ?>"><?php esc_html_e( 'View Wishlist', 'alpha-store' ); ?></a></div>
				<div class="yith-wcwl-wishlistexistsbrowse hide" style="display:none"> <span class="feedback"><?php esc_html_e( 'The product is already in the wishlist!', 'alpha-store' ); ?></span> <a href="<?php echo esc_url( $wishlist_url ); ?>"><?php esc_html_e( 'Browse Wishlist', 'alpha-store' ); ?></a></div>
				<div class="clear"></div>
				<div class="yith-wcwl-wishlistaddresponse"></div>
			</div>
			<?php
		}
	}

	add_action( 'woocommerce_after_shop_loop_item', 'alpha_store_wishlist_products', 20 );
	
	function alpha_store_woocommerce_breadcrumbs() {
		return array(
				'delimiter'   => ' &raquo; ',
				'wrap_before' => '<div id="breadcrumbs" ><div class="breadcrumbs-inner text-right">',
				'wrap_after'  => '</div></div>',
				'before'      => '',
				'after'       => '',
				'home'        => esc_html_x( 'Главная', 'woocommerce breadcrumb', 'alpha-store' ),
			);
	}
	
	add_filter( 'woocommerce_breadcrumb_defaults', 'alpha_store_woocommerce_breadcrumbs' );
	
	if( !function_exists('alpha_store_my_account_text') ) :
		function alpha_store_my_account_text( $myaccount ){
			
			$user_info = wp_get_current_user();

			if ( !empty($user_info->first_name ) ) {
				$user_first_name = $user_info->first_name;
			} else {
				$user_first_name = $user_info->billing_first_name;
			}

			if ( !empty( $user_info->last_name ) ) {
				$user_last_name = $user_info->last_name;
			} else {
				$user_last_name = $user_info->billing_last_name;
			}
			
			return str_replace(
				array('{first-name}', '{last-name}'),
				array($user_first_name, $user_last_name),
				$myaccount
			);

		}
		endif;
	add_filter( 'alpha_store_my_account', 'alpha_store_my_account_text' );
}

////////////////////////////////////////////////////////////////////
// WooCommerce end
////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////
// Excerpt functions
////////////////////////////////////////////////////////////////////
function alpha_store_excerpt_length( $length ) {
	return 25;
}

add_filter( 'excerpt_length', 'alpha_store_excerpt_length', 999 );

function alpha_store_excerpt_more( $more ) {
	return '&hellip;';
}

add_filter( 'excerpt_more', 'alpha_store_excerpt_more' );

////////////////////////////////////////////////////////////////////
// Schema publisher function
////////////////////////////////////////////////////////////////////
if ( !function_exists( 'alpha_store_entry_publisher' ) ) {
	function alpha_store_entry_publisher() {
		$image_id = get_theme_mod( 'custom_logo' );
		$img	 = wp_get_attachment_image_src( $image_id, 'full' );
		// Uncomment your choice below.
		$publisher = 'https://schema.org/Organization';
		//$publisher = 'https://schema.org/Person';
		$publisher_name =  get_bloginfo( 'name', 'display' );
		$logo = $img[0]; 
		$logo_width = $img[1]; 
		$logo_height = $img[2]; 
		
		if ( ! isset( $publisher ) || ! isset( $logo ) || ! isset( $publisher_name ) ) {
			return;
		}
		printf( '<div itemprop="publisher" itemscope itemtype="%s">', esc_url( $publisher ) );
			echo '<div itemprop="logo" itemscope itemtype="https://schema.org/ImageObject">';
				printf( '<meta itemprop="url" content="%s">', esc_url( $logo ) );
				printf( '<meta itemprop="width" content="%d">', esc_attr( $logo_width ) );
				printf( '<meta itemprop="height" content="%d">', esc_attr( $logo_height ) );
			echo '</div>';
			printf( '<meta itemprop="name" content="%s">', esc_attr( $publisher_name ) );
		echo '</div>';
	}
}



add_filter( 'woocommerce_show_page_title' , 'woo_hide_page_title' );
/**
 * woo_hide_page_title
 *
 * Removes the "shop" title on the main shop page
 *
 * @access      public
 * @since       1.0 
 * @return      void
*/
function woo_hide_page_title() {
		?>

<header class="woocommerce-products-header">
		<h1 class="woocommerce-products-header__title page-title"><?php woocommerce_page_title(); ?></h1>
</header>    
    
<hr><?php

	
}




function exclude_products_from_child_cats( $wp_query ) {
	if ( ! is_admin() && $wp_query->is_main_query()) {
		if (isset( $wp_query->query_vars['product_cat'] )) {
			$tax_query = array(
				array(
					'taxonomy' => 'product_cat',
					'field' => 'slug',
					'terms' => $wp_query->query_vars['product_cat'],
					'include_children' => false
				)
			);
			$wp_query->set( 'tax_query', $tax_query );
		}
	}
}

add_filter( 'pre_get_posts', 'exclude_products_from_child_cats' );


add_filter('woocommerce_product_description_heading', false);


remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);



remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart');
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );



add_filter('woocommerce_empty_price_html', 'empty_price_message');
function empty_price_message() {
	return 'Цена по запросу';
}



add_filter( 'woocommerce_after_shop_loop_item', 'wpspec_show_product_description', 7 );
 
function wpspec_show_product_description() {
	echo '<div class="woo-product-short-desc">' . get_the_excerpt() . '</div>';
}






/**
 * Displays product attributes in the top right of the single product page.
 * 
 * @param $product
 */
function tutsplus_list_attributes( $product ) {
 
global $product;
global $post;
 
$attributes = $product->get_attributes();
 
if ( ! $attributes ) {
 
 return;
 
}
 
foreach ( $attributes as $attribute ) {
 
 // Get the taxonomy.
 $terms = wp_get_post_terms( $product->id, $attribute[ 'name' ], 'all' );
 $taxonomy = $terms[ 0 ]->taxonomy;
 
 // Get the taxonomy object.
 $taxonomy_object = get_taxonomy( $taxonomy );
 
 // Get the attribute label.
 $attribute_label = $taxonomy_object->labels->singular_name;
 
 // Display the label followed by a clickable list of terms.
 echo get_the_term_list( $post->ID, $attribute[ 'name' ] , '<div class="attributes">' . $attribute_label . ': ' , ', ', '</div>' );
 
}
 
}
 
add_action( 'woocommerce_product_meta_end', 'tutsplus_list_attributes' );



function devise_add_text_below_meta($my_info) {
    echo do_shortcode('[viewBuyButton]');
}
add_action('woocommerce_product_meta_end','devise_add_text_below_meta' );


add_filter('jpeg_quality', function($arg){return 100;});



remove_action('woocommerce_after_shop_loop_item','woocommerce_template_single_title',5);

function mycustom_wp_footer() {
?>
<script type="text/javascript">
document.addEventListener( 'wpcf7-submit', function( event ) {
  if ( '125' == event.detail.contactFormId ) {
    alert( "Ваше сообщение отправленно. Спасибо." );
	function visibility() {
	document.getElementById("pum-136").style.display = "none";}
  }
}, false );
</script>
<?php
}


add_action( 'wpcf7_mail_sent', 'bitrix24_send' );

// function bitrix24_send( $contact_form ) {

//    define('CRM_HOST', 'gauranga.bitrix24.ru'); // Ваш домен CRM системы
//    define('CRM_PORT', '443'); // Порт сервера CRM. Установлен по умолчанию
//    define('CRM_PATH', '/crm/configs/import/lead.php'); // Путь к компоненту lead.rest


//    define('CRM_LOGIN', 'dmitriy.ahmetov@gauranga.top');
//    define('CRM_PASSWORD', 'DAkhmetov_86');

//    // Перехватываем данные из Contact Form 7
//    $title = $contact_form->title;
//    $posted_data = $contact_form->posted_data;

//    if ('Контактная форма 1' == $title ) {
//        $submission = WPCF7_Submission::get_instance();
//        $posted_data = $submission->get_posted_data();

//        $firstName = $posted_data['your-name'];
// 	   $phone = $posted_data['tel-98']; 
	   
//        $postData = array(
//           'TITLE' => 'Заявка с сайта ЛАЗЕРНОЕ ОБОРУДОВАНИЕ',
// 		  'NAME' => $firstName,
// 		  'ASSIGNED_BY_ID' => 282,
// 		  'PHONE_WORK' => $phone,
// 	   );
	   
//        // Передаем данные из Contact Form 7 в Bitrix24
//        if (defined('CRM_AUTH')) {
//           $postData['AUTH'] = CRM_AUTH;
//        } else {
//           $postData['LOGIN'] = CRM_LOGIN;
//           $postData['PASSWORD'] = CRM_PASSWORD;
//        }
//        $fp = fsockopen("ssl://".CRM_HOST, CRM_PORT, $errno, $errstr, 30);
//        if ($fp) {
//           $strPostData = '';
//           foreach ($postData as $key => $value)
//              $strPostData .= ($strPostData == '' ? '' : '&').$key.'='.urlencode($value);
//           $str = "POST ".CRM_PATH." HTTP/1.0\r\n";
//           $str .= "Host: ".CRM_HOST."\r\n";
//           $str .= "Content-Type: application/x-www-form-urlencoded\r\n";
//           $str .= "Content-Length: ".strlen($strPostData)."\r\n";
//           $str .= "Connection: close\r\n\r\n";
//           $str .= $strPostData;
//           fwrite($fp, $str);
//           $result = '';
//           while (!feof($fp))
//           {
//              $result .= fgets($fp, 128);
//           }
//           fclose($fp);
//           $response = explode("\r\n\r\n", $result);
//           $output = '<pre>'.print_r($response[1], 1).'</pre>';
//        } else {
//           echo 'Connection Failed! '.$errstr.' ('.$errno.')';
//        }
// 	}
	
// 	if ('Контакты' == $title ) {
// 		$submission = WPCF7_Submission::get_instance();
// 		$posted_data = $submission->get_posted_data();
 
// 		$firstName = $posted_data['text-133'];
// 		$phone = $posted_data['tel-37']; 
// 		$email = $posted_data['email']; 
		
// 		$postData = array(
// 		   'TITLE' => 'Заявка с сайта ЛАЗЕРНОЕ ОБОРУДОВАНИЕ',
// 		   'NAME' => $firstName,
// 		   'ASSIGNED_BY_ID' => 282,
// 		   'PHONE_WORK' => $phone,
// 		   'EMAIL_WORK' => $email,
// 		);
		
// 		// Передаем данные из Contact Form 7 в Bitrix24
// 		if (defined('CRM_AUTH')) {
// 		   $postData['AUTH'] = CRM_AUTH;
// 		} else {
// 		   $postData['LOGIN'] = CRM_LOGIN;
// 		   $postData['PASSWORD'] = CRM_PASSWORD;
// 		}
// 		$fp = fsockopen("ssl://".CRM_HOST, CRM_PORT, $errno, $errstr, 30);
// 		if ($fp) {
// 		   $strPostData = '';
// 		   foreach ($postData as $key => $value)
// 			  $strPostData .= ($strPostData == '' ? '' : '&').$key.'='.urlencode($value);
// 		   $str = "POST ".CRM_PATH." HTTP/1.0\r\n";
// 		   $str .= "Host: ".CRM_HOST."\r\n";
// 		   $str .= "Content-Type: application/x-www-form-urlencoded\r\n";
// 		   $str .= "Content-Length: ".strlen($strPostData)."\r\n";
// 		   $str .= "Connection: close\r\n\r\n";
// 		   $str .= $strPostData;
// 		   fwrite($fp, $str);
// 		   $result = '';
// 		   while (!feof($fp))
// 		   {
// 			  $result .= fgets($fp, 128);
// 		   }
// 		   fclose($fp);
// 		   $response = explode("\r\n\r\n", $result);
// 		   $output = '<pre>'.print_r($response[1], 1).'</pre>';
// 		} else {
// 		   echo 'Connection Failed! '.$errstr.' ('.$errno.')';
// 		}
// 	 }
// }


function bitrix24_send( $contact_form ) {
	$title = $contact_form->title;

	$submission = WPCF7_Submission::get_instance();
	$posted_data = $submission->get_posted_data();

	$queryUrl = 'https://gauranga.bitrix24.ru/rest/152/fceakngud7aq20e3/crm.lead.add.json';
	
	if(!session_id()) session_start();

	$arrUtm = [];
	if (isset($_SESSION['utm'])) {
		$arrUtm = json_decode($_SESSION['utm']);    
	}

	if ('Контакты' == $title ) {

		$firstName = $posted_data['text-133'];
		$phone = $posted_data['tel-37']; 
		$email = $posted_data['email']; 
		
		$queryData = http_build_query(array(
			'fields' => array(
				'TITLE' => 'Заявка с сайта ЛАЗЕРНОЕ ОБОРУДОВАНИЕ',
				'NAME' => $firstName,
				'OPENED' => 'Y', // Доступно для всех
				'SOURCE_ID' => "WEB", //Источник вебсайт

				"UTM_SOURCE" => $arrUtm->utm_source,
				'UTM_MEDIUM' => $arrUtm->utm_medium,
				'HTTP_HOST' => $arrUtm->utm_medium,
				'UTM_CAMPAIGN' => $arrUtm->utm_campaign,
				'UTM_CONTENT' => $arrUtm->utm_content,
				'UTM_TERM' => $arrUtm->utm_term,

				'ASSIGNED_BY_ID' => 282,
				"PHONE" => array(
					array(
						"VALUE" => $phone,
						"VALUE_TYPE" => "WORK"
					)
				),
				"EMAIL" => array(
					array(
						"VALUE" => $email,
						"VALUE_TYPE" => "WORK"
					)
				)
			),
			'params' => array("REGISTER_SONET_EVENT" => "Y")
		));


		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_POST => 1,
			CURLOPT_HEADER => 0,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => $queryUrl,
			CURLOPT_POSTFIELDS => $queryData,
		));

		$result = curl_exec($curl);
		curl_close($curl);
		$result = json_decode($result, 1);
		if (array_key_exists('error', $result)) 
			echo "Ошибка при сохранении лида: ".$result['error_description']."<br/>";
	}

	if ('Контактная форма 1' == $title ) {

		$firstName = $posted_data['your-name'];
		$phone = $posted_data['tel-98']; 
		
		$queryData = http_build_query(array(
			'fields' => array(
				'TITLE' => 'Заявка с сайта ЛАЗЕРНОЕ ОБОРУДОВАНИЕ',
				'NAME' => $firstName,
				'OPENED' => 'Y', // Доступно для всех
				'SOURCE_ID' => "WEB", //Источник вебсайт

				"UTM_SOURCE" => $arrUtm->utm_source,
				'UTM_MEDIUM' => $arrUtm->utm_medium,
				'HTTP_HOST' => $arrUtm->utm_medium,
				'UTM_CAMPAIGN' => $arrUtm->utm_campaign,
				'UTM_CONTENT' => $arrUtm->utm_content,
				'UTM_TERM' => $arrUtm->utm_term,

				'ASSIGNED_BY_ID' => 282,
				"PHONE" => array(
					array(
						"VALUE" => $phone,
						"VALUE_TYPE" => "WORK"
					)
				),
			),
			'params' => array("REGISTER_SONET_EVENT" => "Y")
		));


		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_POST => 1,
			CURLOPT_HEADER => 0,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => $queryUrl,
			CURLOPT_POSTFIELDS => $queryData,
		));

		$result = curl_exec($curl);
		curl_close($curl);
		$result = json_decode($result, 1);
		if (array_key_exists('error', $result)) 
			echo "Ошибка при сохранении лида: ".$result['error_description']."<br/>";
	}

}


// $queryUrl = 'https://gauranga.bitrix24.ru/rest/152/fceakngud7aq20e3/crm.lead.add.json';
// // формируем параметры для создания лида в переменной $queryData
// $queryData = http_build_query(array(
//   'fields' => array(
//     'TITLE' => 'Название лида',
//   ),
//   'params' => array("REGISTER_SONET_EVENT" => "Y")
// ));
// // обращаемся к Битрикс24 при помощи функции curl_exec
// $curl = curl_init();
// curl_setopt_array($curl, array(
//   CURLOPT_SSL_VERIFYPEER => 0,
//   CURLOPT_POST => 1,
//   CURLOPT_HEADER => 0,
//   CURLOPT_RETURNTRANSFER => 1,
//   CURLOPT_URL => $queryUrl,
//   CURLOPT_POSTFIELDS => $queryData,
// ));
// $result = curl_exec($curl);
// curl_close($curl);
// $result = json_decode($result, 1);
// if (array_key_exists('error', $result)) echo "Ошибка при сохранении лида: ".$result['error_description']."<br/>";



add_action( 'buy_click_new_order', 'my_custom_tracking');
function my_custom_tracking($order_id) {

	if(!session_id()) session_start();

	$arrUtm = [];
	if (isset($_SESSION['utm'])) {
		$arrUtm = json_decode($_SESSION['utm']);    
	}

	$queryData = http_build_query(array(
		'fields' => array(
			'TITLE' => 'Заявка с сайта ЛАЗЕРНОЕ ОБОРУДОВАНИЕ',
			'NAME' => $_POST['txtname'],
			'OPENED' => 'Y', // Доступно для всех
			'SOURCE_ID' => "WEB", //Источник вебсайт

			"UTM_SOURCE" => $arrUtm->utm_source,
			'UTM_MEDIUM' => $arrUtm->utm_medium,
			'HTTP_HOST' => $arrUtm->utm_medium,
			'UTM_CAMPAIGN' => $arrUtm->utm_campaign,
			'UTM_CONTENT' => $arrUtm->utm_content,
			'UTM_TERM' => $arrUtm->utm_term,

			'ASSIGNED_BY_ID' => 282,
			"PHONE" => array(
				array(
					"VALUE" => $_POST['txtphone'],
					"VALUE_TYPE" => "WORK"
				)
			),
			"EMAIL" => array(
				array(
					"VALUE" => $_POST['txtemail'],
					"VALUE_TYPE" => "WORK"
				)
			),
			'UF_CRM_1584460500' => strip_tags($_POST['nametovar']),	
		),
		'params' => array("REGISTER_SONET_EVENT" => "Y")
	));

	$queryUrl = 'https://gauranga.bitrix24.ru/rest/152/fceakngud7aq20e3/crm.lead.add.json';
	
	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_SSL_VERIFYPEER => 0,
		CURLOPT_POST => 1,
		CURLOPT_HEADER => 0,
		CURLOPT_RETURNTRANSFER => 1,
		CURLOPT_URL => $queryUrl,
		CURLOPT_POSTFIELDS => $queryData,
	));
	$result = curl_exec($curl);
	curl_close($curl);
	$result = json_decode($result, 1);
	if (array_key_exists('error', $result)) echo "Ошибка при сохранении лида: ".$result['error_description']."<br/>";
  
}


function get_the_utms() { 
	?>
<script> 
	<?php 
if (isset($_GET['utm_source'])) {
    $_SESSION['utm'] = json_encode($_GET);	
	$sess = $_SESSION['utm'];
	?>	
	console.log('Сессия обновлена');
	console.log('$_SESSION[\'utm\'] = <?php print_r($sess);?>');  
	<?php
} else { ?>
	console.log("UTM Get пустой. Сессия не обновлена.");
	<?php
	};
	?>
</script>
<?php
}