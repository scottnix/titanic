<?php

//
//  Titanic (a child theme for Thematic) Functions
//



// recreates the doctype section, html5boilerplate.com style with conditional classes
// http://scottnix.com/html5-header-with-thematic/
function childtheme_create_doctype() {
    $content = "<!doctype html>" . "\n";
    $content .= '<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" dir="' . get_bloginfo ('text_direction') . '" lang="'. get_bloginfo ('language') . '"> <![endif]-->' . "\n";
    $content .= '<!--[if IE 7]> <html class="no-js lt-ie9 lt-ie8" dir="' . get_bloginfo ('text_direction') . '" lang="'. get_bloginfo ('language') . '"> <![endif]-->'. "\n";
    $content .= '<!--[if IE 8]> <html class="no-js lt-ie9" dir="' . get_bloginfo ('text_direction') . '" lang="'. get_bloginfo ('language') . '"> <![endif]-->' . "\n";
    $content .= "<!--[if gt IE 8]><!-->" . "\n";
    $content .= "<html class=\"no-js\"";
    return $content;
}
add_filter('thematic_create_doctype', 'childtheme_create_doctype', 11);

// creates the head, meta charset and viewport tags
function childtheme_head_profile() {
    $content = "<!--<![endif]-->";
    $content .= "\n" . "<head>" . "\n";
    $content .= "<meta charset=\"utf-8\" />" . "\n";
    $content .= "<meta name=\"viewport\" content=\"width=device-width\" />" . "\n";
    return $content;
}
add_filter('thematic_head_profile', 'childtheme_head_profile', 11);

// remove meta charset tag, now in the above function
function childtheme_create_contenttype() {
    // silence
}
add_filter('thematic_create_contenttype', 'childtheme_create_contenttype', 11);



// remove the index and follow tags from header since it is browser default.
// http://scottnix.com/polishing-thematics-head/
function childtheme_create_robots($content) {
    global $paged;
    if (thematic_seo()) {
        if((is_home() && ($paged < 2 )) || is_front_page() || is_single() || is_page() || is_attachment())
        {
            $content = "";
        } elseif (is_search()) {
            $content = "\t";
            $content .= "<meta name=\"robots\" content=\"noindex,nofollow\" />";
            $content .= "\n\n";
        } else {
            $content = "\t";
            $content .= "<meta name=\"robots\" content=\"noindex,follow\" />";
            $content .= "\n\n";
        }
    return $content;
    }
}
add_filter('thematic_create_robots', 'childtheme_create_robots');



// clear useless garbage for a polished head
// http://scottnix.com/polishing-thematics-head/
// remove really simple discovery
remove_action('wp_head', 'rsd_link');
// remove windows live writer xml
remove_action('wp_head', 'wlwmanifest_link');
// remove index relational link
remove_action('wp_head', 'index_rel_link');
// remove parent relational link
remove_action('wp_head', 'parent_post_rel_link');
// remove start relational link
remove_action('wp_head', 'start_post_rel_link');
// remove prev/next relational link
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');



// remove built in drop down theme javascripts
// thematictheme.com/forums/topic/correct-way-to-prevent-loading-thematic-scripts/
function childtheme_remove_superfish() {
    remove_theme_support('thematic_superfish');
}
add_action('wp_enqueue_scripts', 'childtheme_remove_superfish', 9);


// script manager template for registering and enqueuing files
function childtheme_script_manager() {
    // register google font css styles which are to be queued in the theme
    wp_register_style('google-fonts', 'http://fonts.googleapis.com/css?family=PT+Sans:700');

    // registers modernizr script, stylesheet local path, no dependency, no version, loads in header
    wp_register_script('modernizr-js', get_stylesheet_directory_uri() . '/js/modernizr.js', false, false, false);
    // registers fitvids script, local stylesheet path, yes dependency is jquery, no version, loads in footer
    wp_register_script('fitvids-js', get_stylesheet_directory_uri() . '/js/jquery.fitvids.js', array('jquery'), false, true);
    // registers misc custom script, local stylesheet path, yes dependency is jquery, no version, loads in footer
    wp_register_script('custom-js', get_stylesheet_directory_uri() . '/js/custom.js', array('jquery'), false, true);

    // enqueue the styles for use in theme
    wp_enqueue_style ('google-fonts');

    // enqueue the scripts for use in theme
    wp_enqueue_script ('modernizr-js');
    wp_enqueue_script ('fitvids-js');

        // conditional example for loading styles and scripts on specific pages
        if ( is_front_page() ) {

        }

    //always enqueue custom js last, helps with conflicts
    wp_enqueue_script ('custom-js');
}
add_action('wp_enqueue_scripts', 'childtheme_script_manager');



// add favicon to site, add 16x16 or 32x32 "favicon.ico" or .png image to child themes main folder
function childtheme_add_favicon() { ?>
<link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/favicon.ico" />
<?php }
add_action('wp_head', 'childtheme_add_favicon');



// register two additional custom menu slots
function childtheme_register_menus() {
    if ( function_exists('register_nav_menu')) {
        register_nav_menu('secondary-menu', 'Secondary Menu');
        register_nav_menu('tertiary-menu', 'Tertiary Menu');
    }
}
add_action('thematic_child_init', 'childtheme_register_menus');



// add 4th subsidiary aside widget, currently set up to be a footer widget underneath the 3 subs
function childtheme_add_subsidiary($content) {
    $content['Footer Widget Aside'] = array(
        'admin_menu_order' => 550,
        'args' => array (
        'name' => 'Footer Aside',
        'id' => '4th-subsidiary-aside',
        'description' => __('The 4th bottom widget area in the footer.', 'thematic'),
        'before_widget' => thematic_before_widget(),
        'after_widget' => thematic_after_widget(),
        'before_title' => thematic_before_title(),
        'after_title' => thematic_after_title(),
            ),
        'action_hook'   => 'widget_area_subsidiaries',
        'function'      => 'childtheme_4th_subsidiary_aside',
        'priority'      => 90
        );
    return $content;
}
add_filter('thematic_widgetized_areas', 'childtheme_add_subsidiary', 50);

// set structure for the 4th subsidiary aside
function childtheme_4th_subsidiary_aside() {
    if ( is_active_sidebar('4th-subsidiary-aside') ) {
        echo thematic_before_widget_area('footer-widget');
        dynamic_sidebar('4th-subsidiary-aside');
        echo thematic_after_widget_area('footer-widget');
    }
}



// hide unused widget areas inside the WordPress admin
function childtheme_hide_widgetized_areas($content) {
//    unset($content['Primary Aside']);
//    unset($content['Secondary Aside']);
//    unset($content['1st Subsidiary Aside']);
//    unset($content['2nd Subsidiary Aside']);
//    unset($content['3rd Subsidiary Aside']);
    unset($content['Index Top']);
    unset($content['Index Insert']);
    unset($content['Index Bottom']);
    unset($content['Single Top']);
    unset($content['Single Insert']);
    unset($content['Single Bottom']);
    unset($content['Page Top']);
    unset($content['Page Bottom']);
    return $content;
}
add_filter('thematic_widgetized_areas', 'childtheme_hide_widgetized_areas');



// remove user agent sniffing from thematic theme
// this is what applies classes to the browser type and version body classes
function childtheme_show_bc_browser() {
    return FALSE;
}
add_filter('thematic_show_bc_browser', 'childtheme_show_bc_browser');



// removes the H1 on main page which is duplicated when a page is used as a front page
// also adds the content into a more semantic paragraph tag, where before it was just a div
function childtheme_override_blogdescription() {
    $blogdesc = '"blog-description">' . get_bloginfo('description', 'display');
    echo "\t<p id=$blogdesc</p>\n\n";
}



// move the access inside the branding
function childtheme_move_access() {
    remove_action('thematic_header', 'thematic_access', 9);
}
add_action('thematic_child_init', 'childtheme_move_access');
add_action('thematic_header', 'thematic_access', 6);



// creates the skipto/jump menu that shoots to the bottom
// removed text, if no CSS is visible, this link becomes useless, so text isn't needed
function childtheme_add_menu_skipto() {
    echo '<div class="menu-skip"><a href="#jump-menu"></a></div>';
}
add_action('thematic_header', 'childtheme_add_menu_skipto', 4);

// top nav access menus
function childtheme_override_access() {
    if ( ( function_exists("has_nav_menu") ) && ( has_nav_menu( apply_filters('thematic_primary_menu_id', 'primary-menu') ) ) ) {
        echo  wp_nav_menu(thematic_nav_menu_args());
    } else {
        echo  thematic_add_menuclass(wp_page_menu(thematic_page_menu_args()));
    }
}

// top nav access custom
// sets classes for default WP menu (when no custom menu is set)
function childtheme_menu_args( $args ) {
    $args['container'] = 'nav';
    $args['container_class'] = 'access';
    $args['menu_class'] = 'menu';
    return $args;
}
add_filter( 'thematic_page_menu_args', 'childtheme_menu_args' );

// top nav access default
// sets classes for custom WP menu
function childtheme_nav_args( $args ) {
    $args['container'] = 'nav';
    $args['container_class'] = 'access';
    $args['menu_class'] = 'menu';
    return $args;
}
add_filter( 'thematic_nav_menu_args', 'childtheme_nav_args' );

// footer access menu
function childtheme_override_siteinfoopen() {
    if ( ( function_exists("has_nav_menu") ) && ( has_nav_menu( apply_filters('thematic_primary_menu_id', 'primary-menu') ) ) ) {
        echo  wp_nav_menu( array( 'container' => 'nav', 'container_id' => 'jump-menu' ) );
    } else {
       echo  wp_page_menu( array( 'container' => 'nav', 'container_id' => 'jump-menu' ) );
    } ?>
    <div id="siteinfo">
    <?php
}



// completely remove nav above functionality
function childtheme_override_nav_above() {
    // silence
}

// remove single page nav below functionality
function childtheme_override_nav_below() {
    if ( ! is_single() ) { ?>
        <div id="nav-below" class="navigation"> <?php
            if ( function_exists( 'wp_pagenavi' ) ) {
                wp_pagenavi();
             } else { ?>
            <div class="nav-previous"><?php next_posts_link(sprintf('<span class="meta-nav">&laquo;</span> %s', __('Older posts', 'thematic') ) ) ?></div>
            <div class="nav-next"><?php previous_posts_link(sprintf('%s <span class="meta-nav">&raquo;</span>',__( 'Newer posts', 'thematic') ) ) ?></div>
            <?php } ?>
        </div>  <?php
    }
}



// featured image thumbnail sizing
function childtheme_post_thumb_size($size) {
    $size = array(300,225);
    return $size;
}
add_filter('thematic_post_thumb_size', 'childtheme_post_thumb_size');



// show excerpt on home page (blog) and front page (static home page)
function childtheme_thematic_content($content) {
    if ( is_home() || is_front_page() ) {
        $content= 'excerpt';
    }
    return $content;
}
add_filter('thematic_content', 'childtheme_thematic_content');



// modify excerpt [...] to add a read more link instead
function childtheme_modify_excerpt($text) {
   return str_replace('[...]', '.... <a href="'.get_permalink().'" class="more-link">Read More &raquo;</a>', $text);
}
add_filter('get_the_excerpt', 'childtheme_modify_excerpt');



// cuts the default size of the search input field down to cut overlap
// css sizes this fine, but it could be placed in things other than aside, this is back up. ;)
function childtheme_thematic_search_form_length() {
    return "16";
}
add_filter('thematic_search_form_length', 'childtheme_thematic_search_form_length');



// change the default search box text
function childtheme_search_field_value() {
    return "Search";
}
add_filter('search_field_value', 'childtheme_search_field_value');



// just because, wrap the site info in a p tag automatically
function childtheme_override_siteinfo() {
    echo "\t\t<p>" . do_shortcode( thematic_get_theme_opt( 'footer_txt' ) ) . "</p>\n";
}