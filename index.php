<?php
/*
 * Plugin Name: FJP Custom Metaboxes
 * Plugin URI: http://www.frankperez.net/
 * Description: Easily create custom metaboxes/custom fields for your wordpress application.
 * Version: 1.0.1
 * Author: Frank Perez
 * Author URI: http://www.frankperez.net/
 *
 */

//    This program is free software: you can redistribute it and/or modify
//    it under the terms of the GNU General Public License as published by
//    the Free Software Foundation, either version 3 of the License, or
//    (at your option) any later version.
//
//    This program is distributed in the hope that it will be useful,
//    but WITHOUT ANY WARRANTY; without even the implied warranty of
//    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//    GNU General Public License for more details.
//
//    You should have received a copy of the GNU General Public License
//    along with this program.  If not, see <http://www.gnu.org/licenses/>.

// START PRE 2.6 Compatibility
if( !defined('WP_CONTENT_URL') )
	define('WP_CONTENT_URL', get_option('siteurl') . '/wp-content');
if( !defined('WP_CONTENT_DIR') )
	define('WP_CONTENT_DIR', ABSPATH . 'wp-content');
if( !defined('WP_PLUGIN_URL') )
	define('WP_PLUGIN_URL', WP_CONTENT_URL . '/plugins');
if( !defined('WP_PLUGIN_DIR') )
	define('WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins');
if( !defined('WP_LANG_DIR') )
	define('WP_LANG_DIR', WP_CONTENT_DIR . '/languages');
// END PRE 2.6 Compatibility

// Defines the constant for the current plugin
define('FJP_CUSTOM_META_DIR', WP_PLUGIN_DIR . '/custom-metaboxes');

require_once(FJP_CUSTOM_META_DIR . '/functions.inc.php');
require_once(FJP_CUSTOM_META_DIR . '/functions.display.php');

// Process Activation / DeActivation
register_activation_hook(__FILE__, 'fjp_install');
register_deactivation_hook(__FILE__, 'fjp_uninstall');

// Initalize Internationalization
add_action('init', 'fjp_init');
add_action('init', 'fjp_disp_posttypes_menu');

// Load Admin JavaScripts/CSS
add_action('admin_head', 'fjp_set_javascript');
add_action('admin_head', 'fjp_set_css');

// Create Admin Menu Item
add_action('admin_menu', 'fjp_create_menu_item');

// Handle Meta Box Display
add_action('admin_init', 'fjp_disp_metaboxes_sections');

// Save MetaBox Data
add_action('save_post', 'fjp_metabox_save_data');


?>
