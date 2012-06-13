<?php
/*
Plugin Name: Custom Adminbar Menus
Plugin URI: http://tech.lineshjose.com/
Description: This is a simple plugin for adding custom nav menus to your WordPress Adminbar/Toolbar..
Version: 12.06.13
Author: Linesh Jose
Author URI: http://lineshjose.com
License: GPLv2 or later
*/
	$version='12.06.13';
	global $wp_version;
	
	// For admmin side only //
	
	
/** 
	* Adding Custom Adminbar/Toolbar menus
	* This Feature is only  support for WordPress 3.3.0 or above versions
**/
	if ( version_compare( $wp_version, '3.3', '>' ) )
	{
		if (function_exists('register_nav_menus'))
			{
				function reg_bd_admin_menus() {
					register_nav_menus(	array('bd_admin_menus' => __( 'Adminbar Menu','bd' )));
				}
				add_action( 'init', 'reg_bd_admin_menus' );
			}	
			
		// To add custom links to adminbar //
		function lj_custom_admin_menus() 
		{
			global $wp_admin_bar;
			 $menu_name = 'bd_admin_menus';
			 if ( ( $locations = get_nav_menu_locations() ) && isset( $locations[ $menu_name ] ) )
			 {
				$menu = wp_get_nav_menu_object( $locations[ $menu_name ] );
				$menu_items = wp_get_nav_menu_items($menu->term_id);
	
				foreach ( (array) $menu_items as $key => $menu_item )
				{
						if($menu_item->classes){$clasess=implode(' ',$menu_item->classes);} else{$clasess="";}
						$meta=array('class' =>$clasess , 'onclick' => '', target => $menu_item->target, title =>$menu_item->attr_title);
						//print_r($menu_items);
						if ($menu_item->menu_item_parent) 
						{ 
							$wp_admin_bar->add_menu( array(
							'id' => $menu_item->ID,
							'parent' => $menu_item->menu_item_parent, 
							'title' => $menu_item->title,
							'href' => $menu_item->url,
							'meta' =>  $meta ) );
							
						}
						else
						{
							$wp_admin_bar->add_menu( array(
							'id' => $menu_item->ID,
							'title' => $menu_item->title,
							'href' => $menu_item->url,
							'meta' =>  $meta ) );
						}
				}
			}
		}
		add_action( 'wp_before_admin_bar_render', 'lj_custom_admin_menus' );
	}
	else
	{	// Shows as an error message. You could add a link to the right page if you wanted.
		function lj_error()
		{
			echo '<div id="message" class="error fade"><p><strong>Custom Adminbar Menus</strong> plugin is only compatible for <strong>WordPress 3.3</strong> or above versions. Please <strong><a href="./update-core.php">Update your WordPress</a></strong> now</strong></p></div>';
		}	
		add_action('admin_notices', 'lj_error');	
	}
	
	
	
	// Add meta links
	function lj_custom_admin_menus_actions( $links, $file )
	{
		$plugin = plugin_basename(__FILE__);
		if ($file == $plugin) {
			//$links[] = '<a href="' . admin_url( 'options-general.php?page=blueadmin' ) . '">' . __('Settings', TPTN_LOCAL_NAME ) . '</a>';
			$links[] = '<a href="http://bit.ly/go_fund">' . __('Donate', TPTN_LOCAL_NAME ) . '</a>';
		}
		return $links;
	}
	if ( version_compare( $wp_version, '3.2', '>' ) ){add_filter( 'plugin_row_meta', 'lj_custom_admin_menus_actions', 10, 2 ); } // only 2.8 and higher
	else {add_filter( 'plugin_action_links', 'lj_custom_admin_menus_actions', 10, 2 );}

?>