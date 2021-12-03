<?php
/**
 * Plugin Name: WP Admin Extended
 * Description: This plugin extends the functionality of the Wordpress admin panel
 * Version: 0.3
 * Author: Vladislav M
 * Link: https://webton.ru/wp-plugins
 */


add_action( 'after_setup_theme', function(){
	register_nav_menu( 'toolbar', 'Toolbar' );
});
add_action( 'admin_bar_menu', 'wbtn_add_toolbar_menu', 999 );
function wbtn_add_toolbar_menu( $toolbar ){
	$locations = get_nav_menu_locations();

	if( ! isset($locations['toolbar']) ) return;

	$items = wp_get_nav_menu_items( $locations['toolbar'] );

	if( ! $items ) return;

	foreach( $items as $item ){
		$args = array(
			'parent' => $item->menu_item_parent ? 'id_' . $item->menu_item_parent : false,
			'id'     => 'id_'. $item->ID,
			'title'  => $item->title, 
			'href'   => $item->url, 
			'meta'   => array(
				// 'html' - The html used for the node.
				// 'class' - The class attribute for the list item containing the link or text node.
				// 'rel' - The rel attribute.
				// 'onclick' - The onclick attribute for the link. This will only be set if the 'href' argument is present.
				// 'target' - The target attribute for the link. This will only be set if the 'href' argument is present.
				// 'title' - The title attribute. Will be set to the link or to a div containing a text node.
				// 'tabindex' - The tabindex attribute. Will be set to the link or to a div containing a text node. 

				'class'  => implode(' ', $item->classes ),
				'title'  => esc_attr( $item->description ),
				'target' => $item->target,
			)
		);

		$toolbar->add_node( $args );            
	}
}

if( extension_loaded('zlib') && ini_get('output_handler') != 'ob_gzhandler' ){
	add_action('wp', function(){ @ ob_end_clean(); @ ini_set('zlib.output_compression', 'on'); } );
}

add_filter( 'script_loader_src', '_remove_script_version' );

add_filter( 'style_loader_src', '_remove_script_version' );
function _remove_script_version( $src ){
	$parts = explode( '?', $src );
	return $parts[0];
}

if( ! defined('WP_POST_REVISIONS') ) define('WP_POST_REVISIONS', 10);

add_filter('xmlrpc_enabled', '__return_false');
?>