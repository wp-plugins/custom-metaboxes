<?php 

// Activate and Install Tables
function fjp_install() {
	global $wp_version;
	if(version_compare($wp_version, "2.6", "<")) {
		deactivate_plugins(basename(__FILE__));  // Deactivate our plugin
		wp_die(__("This plugin requires WordPress version 2.6 or higher.", 'fjp-custom-metaboxes'));
	}
	fjp_create_posttypes();
	fjp_create_custommetaboxes();
}

// DeActivate and Uninstall Tables
function fjp_uninstall() {
	global $wpdb;
	$wpdb->query("DROP TABLE  `" . $wpdb->prefix . "fjp_posttypes`");
	$wpdb->query("DROP TABLE  `" . $wpdb->prefix . "fjp_custommetaboxes`");
	$wpdb->query("DELETE FROM  `" . $wpdb->prefix . "postmeta` WHERE `meta_key` LIKE 'fjp_metabox_field_%'");
}

// Create Post Types Table
function fjp_create_posttypes() {
	global $wpdb;
	$table_name = $wpdb->prefix . 'fjp_posttypes';
	$sql = "CREATE TABLE  " . $table_name . " (
			`id` INT( 32 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			`name` VARCHAR( 200 ) NOT NULL, 
			`supports` TEXT NOT NULL
			)";
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);
}

// Create Custom Meta Box Table
function fjp_create_custommetaboxes() {
	global $wpdb;
	$table_name = $wpdb->prefix . 'fjp_custommetaboxes';
	$sql = "CREATE TABLE  " . $table_name . " (
			`id` INT( 32 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			`name` VARCHAR( 200 ) NOT NULL, 
			`group` VARCHAR( 200 ) NOT NULL,  
			`type` VARCHAR( 200 ) NOT NULL,
			`location` VARCHAR( 100 ) NOT NULL, 
			`desc` TEXT NOT NULL
			)";
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);
}

// Loads Internationalization
function fjp_init() {
	load_plugin_textdomain('fjp-custom-metaboxes', false, plugin_basename(dirname(__FILE__).'/localization'));
}

// Creates Admin Menu Item
function fjp_create_menu_item() {
	add_menu_page(__('FJP Custom MetaBoxes'), __('MetaBoxes'), 'administrator', 'fjp_custom_overview', 'fjp_custom_metaboxes_overview');
	add_submenu_page('fjp_custom_overview', __('Create Custom Post Types'), __('Custom Post Types'), 'administrator', 'fjp_custom_posttypes', 'fjp_custom_posttypes_page');
	add_submenu_page('fjp_custom_overview', __('Create Custom MetaBoxes'), __('Custom MetaBoxes'), 'administrator', 'fjp_custom_metaboxes', 'fjp_custom_metaboxes_page');
}

// Sets the JavaScript for the Plugin
function fjp_set_javascript() {
	echo '<script type="text/javascript">'.PHP_EOL;
	echo '</script>'.PHP_EOL;
}

// Sets the CSS for the Plugin
function fjp_set_css() {
	echo '<style type="text/css">'.PHP_EOL;
	echo '</style>'.PHP_EOL;
}

// Insert PostType
function fjp_insert_posttype($input = array()) {
	global $wpdb;
	$table_name = $wpdb->prefix . 'fjp_posttypes';
	foreach($input as $checkval) {
		if(empty($checkval)) {
			return false;
		}
	}
	$supporttypes = array();
	foreach($input['supports'] as $support) {
		if($support !== 'no') {
			$supporttypes[] = $support;
		}
	}
	if(!empty($supporttypes)) {
		$supports_string = implode(', ', $supporttypes);
	}
	if(!isset($input['fjp_post_type-exists'])) {
		if($wpdb->insert($table_name, array('name' => $input['fjp_posttype_name'], 'supports' => $supports_string), array('%s', '%s'))) {
			return true;
		} else {
			return false;
		}
	} else {
		if($wpdb->update($table_name, array('name' => $input['fjp_posttype_name'], 'supports' => $supports_string), array( 'id' => $input['fjp_post_type-exists'] ), array('%s', '%s', '%d'))) {
			return true;
		} else {
			return false;
		}
	}
}

// Insert MetaBox / Fields
function fjp_insert_metabox($input = array()) {
	global $wpdb;
	$table_name = $wpdb->prefix . 'fjp_custommetaboxes';
	foreach($input as $checkval) {
		if(empty($checkval)) {
			return false;
		}
	}
	if(!isset($input['fjp_custom_metaboxes-exists'])) {
		if($wpdb->insert($table_name, array('name' => $input['fjp_metabox_name'], 'group' => $input['fjp_metabox_group'], 'type' => $input['fjp_metabox_type'], 'location' => $input['fjp_metabox_location'], 'desc' => $input['fjp_metabox_desc']), array('%s', '%s', '%s', '%s', '%s'))) {
			return true;
		} else {
			return false;
		}
	} else {
		if($wpdb->update($table_name, array('name' => $input['fjp_metabox_name'], 'group' => $input['fjp_metabox_group'], 'type' => $input['fjp_metabox_type'], 'location' => $input['fjp_metabox_location'], 'desc' => $input['fjp_metabox_desc']), array( 'id' => $input['fjp_custom_metaboxes-exists'] ), array('%s', '%s', '%s', '%s', '%s', '%d'))) {
			return true;
		} else {
			return false;
		}
	}
}

// Setup Post Types Menu
function fjp_disp_posttypes_menu() {
	global $wpdb;
	$table_name = $wpdb->prefix . 'fjp_posttypes';
	$sql = "SELECT * FROM `$table_name` ORDER BY id ASC";
	$results = $wpdb->get_results($sql);
	$total = count($results);
	if($total > 0) {
		foreach($results as $result) {
			if(!empty($result->supports)) {
				$supports = explode(', ', $result->supports);
			} else {
				$supports = array();
			}
			register_post_type('fjp_posttype_' . $result->id, 
			array('labels' => array('name' => __($result->name, 'fjp-custom-metaboxes'), 'singular_name' => __($result->name, 'fjp-custom-metaboxes')), 'public' => true, 'supports' => $supports));
		}
	}
}

// Setup MetaBoxes
function fjp_disp_metaboxes_sections() {
	global $wpdb, $post;

	if ( !isset($_GET['post_type']) )
		$post_type = 'post';
	elseif ( in_array( $_GET['post_type'], get_post_types( array('show_ui' => true ) ) ) )
		$post_type = $_GET['post_type'];
	else
		wp_die( __('Invalid post type') );
		
	if( isset($_GET['post']) && ctype_digit($_GET['post'])) {
		$post_type = fjp_metabox_figure_out_posttype($_GET['post']);
	}
		
	$table_name = $wpdb->prefix . 'fjp_custommetaboxes';
	$sql = "SELECT `group` FROM `$table_name` WHERE `location`='" . $post_type . "' GROUP BY `group`";

	$results = $wpdb->get_results($sql);
	$total = count($results);

	if($total > 0) {
		foreach($results as $result) {
			$metaboxes[] = $result->group; 
		}
		foreach($metaboxes as $section) {
			add_meta_box('fjp_metabox_'.convert_to_id($section), __($section, 'fjp-custom-metaboxes'), 'fjp_metabox_handle_fields', $post_type, 'advanced', 'high', $section);
		}
	}
	
}

// Handle MetaBox Fields Admin
function fjp_metabox_handle_fields($input, $fieldset) {
	global $wpdb, $post;
	
	if ( !isset($_GET['post_type']) )
		$post_type = 'post';
	elseif ( in_array( $_GET['post_type'], get_post_types( array('show_ui' => true ) ) ) )
		$post_type = $_GET['post_type'];
	else
		wp_die( __('Invalid post type') );
		
	if( isset($_GET['post']) && ctype_digit($_GET['post'])) {
		$post_type = fjp_metabox_figure_out_posttype($_GET['post']);
	}
	
	$table_name = $wpdb->prefix . 'fjp_custommetaboxes';
	$sql = "SELECT * FROM `$table_name` WHERE `location`='" . $post_type . "' AND `group`='" . $fieldset['args'] . "'";

	$results = $wpdb->get_results($sql);
	$total = count($results);

	if($total > 0) {
		foreach($results as $result) {
			$id = 'fjp_metabox_field_'.convert_to_id($result->id);
			$meta = get_post_meta($post->ID, $id, true);
			$meta = (empty($meta)) ? '' : $meta;
			fjp_metabox_formfield($id, $result->name, $result->type, $meta, $result->desc);
		}
	}
}

function fjp_metabox_figure_out_posttype($id) {
	global $wpdb;
	$table_name = $wpdb->prefix . 'posts';
	$sql = "SELECT `post_type` FROM `$table_name` WHERE `ID`='".$id."' LIMIT 1";
	$data = $wpdb->get_row($sql);
	if(count($data) > 0) {
		return $data->post_type;
	}
}

// Save / Update MetaBox Data
function fjp_metabox_save_data( $post_id ) {
	foreach($_POST['fjp'] as $key => $fjppost) {		
		$old = get_post_meta($post_id, $key, true);
    	$new = $_POST['fjp'][$key];
		if ($new && $new != $old) {
	    	update_post_meta($post_id, $key, $new);
	    } elseif ('' == $new && $old) {
	    	delete_post_meta($post_id, $key, $old);
	    }
	}
	
}

// Convert Any Text to ID
function convert_to_id($input) {
	$outid = ereg_replace("[^A-Za-z0-9]", "", $input); 
	$outid = strtolower($outid);
	return $outid;
}

// Delete Post Type Section
function fjp_process_delete_post_type($id) {
	global $wpdb;
	$wpdb->query("DELETE FROM  `" . $wpdb->prefix . "fjp_posttypes` WHERE `id` = '" . mysql_real_escape_string($id) . "'");
	fjp_disp_message('Successfully Deleted Post Type');
}

// Delete Post Type Section
function fjp_process_delete_custommeta($id) {
	global $wpdb;
	$wpdb->query("DELETE FROM  `" . $wpdb->prefix . "fjp_custommetaboxes` WHERE `id` = '" . mysql_real_escape_string($id) . "'");
	fjp_disp_message('Successfully Deleted The MetaBox');
}

// Display Message
function fjp_disp_message($msg) {
	echo '<div class="updated below-h2" id="message"><p>' . $msg . '<br></p></div>';
}

?>