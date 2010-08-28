<?php 

// Displays the FJP MetaBox Overview
function fjp_custom_metaboxes_overview() {
	echo '<div class="wrap">' . PHP_EOL;
	echo '<h2>Custom Post Types <a href="admin.php?page=fjp_custom_posttypes" class="button add-new-h2">Add New</a></h2>' . PHP_EOL;
	fjp_disp_posttype_records();
	echo '<h2>Custom MetaBoxes / Fields <a href="admin.php?page=fjp_custom_metaboxes" class="button add-new-h2">Add New</a></h2>' . PHP_EOL;
	fjp_disp_metabox_records();
	echo '</div>' . PHP_EOL;
}

// Displays the FJP Post Type Creation Page
function fjp_custom_posttypes_page() {
	global $wpdb;
	$name = '';
	$action = (isset($_GET['action'])) ? $_GET['action'] : '';
	echo '<div class="wrap">' . PHP_EOL;
	echo '<h2>' . __('Post Type Creation Page', 'fjp-custom-metaboxes') . '</h2>' . PHP_EOL;
	
	if($_GET['msg'] == 'reload') {
		fjp_disp_message('You can now view your changes on the left.');
	}
	
	if($_POST) {
		if(fjp_insert_posttype($_POST)) {
			if($action == 'edit') {
				fjp_disp_message('Post Type Updated - <a href="admin.php?page=fjp_custom_posttypes&msg=reload">Click here to refresh and see changes.</a>');
			} else {
				fjp_disp_message('Post Type Added - <a href="admin.php?page=fjp_custom_posttypes&msg=reload">Click here to refresh and see changes.</a>');
			}
		} else {
			fjp_disp_message('Problem Adding Post Type Try Again');
		}
	}	
	
	if($action == 'edit') {
		$table_name = $wpdb->prefix . 'fjp_posttypes';
		$sql = "SELECT * FROM `$table_name` WHERE `id`='" . mysql_real_escape_string($_GET['ref']) . "' LIMIT 1";
		$results = $wpdb->get_results($sql);
		$supports = explode(', ', $results[0]->supports);
		$name = $results[0]->name;
	}
	if($action == 'edit') {
		echo '<form method="post" action="admin.php?page=fjp_custom_posttypes&action=edit&ref=' . $_GET['ref']. '">' . PHP_EOL;
	} else {
		echo '<form method="post" action="admin.php?page=fjp_custom_posttypes">' . PHP_EOL;
	}
		echo '<table class="form-table">' . PHP_EOL;
		
			if($action == 'edit') {
				echo '<input type="hidden" name="fjp_post_type-exists" value="' . $_GET['ref'] . '" />';
			}
			
			echo '<tr valign="top">' . PHP_EOL;
			echo '<th scope="row">' . __('Post Type Name', 'fjp-custom-metaboxes') . '</th>' . PHP_EOL;
			echo '<td colspan="3"><input type="text" name="fjp_posttype_name" id="fjp_posttype_name" value="' . $name .'" /></td>' . PHP_EOL;
			echo '</tr>' . PHP_EOL;
			
			echo '<tr valign="top">' . PHP_EOL;
			echo '<th scope="row">' . __('Title', 'fjp-custom-metaboxes') . '</th>' . PHP_EOL;
			if(in_array('title', $supports)) {
				echo '<td><input type="radio" name="supports[fjp_posttype_title]" value="title" checked /> ' . __('Yes', 'fjp-custom-metaboxes') . ' &nbsp; 
						  <input type="radio" name="supports[fjp_posttype_title]" value="no" /> ' . __('No', 'fjp-custom-metaboxes') . ' </td>' . PHP_EOL;
			} else {
				echo '<td><input type="radio" name="supports[fjp_posttype_title]" value="title" /> ' . __('Yes', 'fjp-custom-metaboxes') . ' &nbsp; 
						  <input type="radio" name="supports[fjp_posttype_title]" value="no" checked /> ' . __('No', 'fjp-custom-metaboxes') . ' </td>' . PHP_EOL;
			}
			
			echo '<th scope="row">' . __('Editor', 'fjp-custom-metaboxes') . '</th>' . PHP_EOL;
			if(in_array('editor', $supports)) {
				echo '<td><input type="radio" name="supports[fjp_posttype_editor]" value="editor" checked /> ' . __('Yes', 'fjp-custom-metaboxes') . ' &nbsp; 
						  <input type="radio" name="supports[fjp_posttype_editor]" value="no" /> ' . __('No', 'fjp-custom-metaboxes') . ' </td>' . PHP_EOL;
			} else {
				echo '<td><input type="radio" name="supports[fjp_posttype_editor]" value="editor" /> ' . __('Yes', 'fjp-custom-metaboxes') . ' &nbsp; 
						  <input type="radio" name="supports[fjp_posttype_editor]" value="no" checked /> ' . __('No', 'fjp-custom-metaboxes') . ' </td>' . PHP_EOL;
			}
			echo '</tr>' . PHP_EOL;
			
			echo '<tr valign="top">' . PHP_EOL;
			echo '<th scope="row">' . __('Author', 'fjp-custom-metaboxes') . '</th>' . PHP_EOL;
			if(in_array('author', $supports)) {
				echo '<td><input type="radio" name="supports[fjp_posttype_author]" value="author" checked /> ' . __('Yes', 'fjp-custom-metaboxes') . ' &nbsp; 
						  <input type="radio" name="supports[fjp_posttype_author]" value="no" /> ' . __('No', 'fjp-custom-metaboxes') . ' </td>' . PHP_EOL;
			} else {
				echo '<td><input type="radio" name="supports[fjp_posttype_author]" value="author" /> ' . __('Yes', 'fjp-custom-metaboxes') . ' &nbsp; 
						  <input type="radio" name="supports[fjp_posttype_author]" value="no" checked /> ' . __('No', 'fjp-custom-metaboxes') . ' </td>' . PHP_EOL;
			}
			
			echo '<th scope="row">' . __('Thumbnail', 'fjp-custom-metaboxes') . '</th>' . PHP_EOL;
			if(in_array('thumbnail', $supports)) {
				echo '<td><input type="radio" name="supports[fjp_posttype_thumbnail]" value="thumbnail" checked /> ' . __('Yes', 'fjp-custom-metaboxes') . ' &nbsp; 
						  <input type="radio" name="supports[fjp_posttype_thumbnail]" value="no" /> ' . __('No', 'fjp-custom-metaboxes') . ' </td>' . PHP_EOL;
			} else {
				echo '<td><input type="radio" name="supports[fjp_posttype_thumbnail]" value="thumbnail" /> ' . __('Yes', 'fjp-custom-metaboxes') . ' &nbsp; 
						  <input type="radio" name="supports[fjp_posttype_thumbnail]" value="no" checked /> ' . __('No', 'fjp-custom-metaboxes') . ' </td>' . PHP_EOL;
			}
			echo '</tr>' . PHP_EOL;
			
			echo '<tr valign="top">' . PHP_EOL;
			echo '<th scope="row">' . __('Excerpt', 'fjp-custom-metaboxes') . '</th>' . PHP_EOL;
			if(in_array('excerpt', $supports)) {
				echo '<td><input type="radio" name="supports[fjp_posttype_excerpt]" value="excerpt" checked /> ' . __('Yes', 'fjp-custom-metaboxes') . ' &nbsp; 
						  <input type="radio" name="supports[fjp_posttype_excerpt]" value="no" /> ' . __('No', 'fjp-custom-metaboxes') . ' </td>' . PHP_EOL;
			} else {
				echo '<td><input type="radio" name="supports[fjp_posttype_excerpt]" value="excerpt" /> ' . __('Yes', 'fjp-custom-metaboxes') . ' &nbsp; 
					  <input type="radio" name="supports[fjp_posttype_excerpt]" value="no" checked /> ' . __('No', 'fjp-custom-metaboxes') . ' </td>' . PHP_EOL;
			}
			
			echo '<th scope="row">' . __('Trackbacks', 'fjp-custom-metaboxes') . '</th>' . PHP_EOL;
			if(in_array('trackbacks', $supports)) {
				echo '<td><input type="radio" name="supports[fjp_posttype_trackbacks]" value="trackbacks" checked /> ' . __('Yes', 'fjp-custom-metaboxes') . ' &nbsp; 
						  <input type="radio" name="supports[fjp_posttype_trackbacks]" value="no" /> ' . __('No', 'fjp-custom-metaboxes') . ' </td>' . PHP_EOL;
			} else {
				echo '<td><input type="radio" name="supports[fjp_posttype_trackbacks]" value="trackbacks" /> ' . __('Yes', 'fjp-custom-metaboxes') . ' &nbsp; 
					  <input type="radio" name="supports[fjp_posttype_trackbacks]" value="no" checked /> ' . __('No', 'fjp-custom-metaboxes') . ' </td>' . PHP_EOL;
			}
			echo '</tr>' . PHP_EOL;
			
			echo '<tr valign="top">' . PHP_EOL;
			echo '<th scope="row">' . __('Custom Fields', 'fjp-custom-metaboxes') . '</th>' . PHP_EOL;
			if(in_array('custom-fields', $supports)) {
				echo '<td><input type="radio" name="supports[fjp_posttype_customfields]" value="custom-fields" checked /> ' . __('Yes', 'fjp-custom-metaboxes') . ' &nbsp; 
						  <input type="radio" name="supports[fjp_posttype_customfields]" value="no" /> ' . __('No', 'fjp-custom-metaboxes') . ' </td>' . PHP_EOL;
			} else {
				echo '<td><input type="radio" name="supports[fjp_posttype_customfields]" value="custom-fields" /> ' . __('Yes', 'fjp-custom-metaboxes') . ' &nbsp; 
					  <input type="radio" name="supports[fjp_posttype_customfields]" value="no" checked /> ' . __('No', 'fjp-custom-metaboxes') . ' </td>' . PHP_EOL;
			}
			
			echo '<th scope="row">' . __('Comments', 'fjp-custom-metaboxes') . '</th>' . PHP_EOL;
			if(in_array('comments', $supports)) {
				echo '<td><input type="radio" name="supports[fjp_posttype_comments]" value="comments" checked /> ' . __('Yes', 'fjp-custom-metaboxes') . ' &nbsp; 
						  <input type="radio" name="supports[fjp_posttype_comments]" value="no" /> ' . __('No', 'fjp-custom-metaboxes') . ' </td>' . PHP_EOL;
			} else {
				echo '<td><input type="radio" name="supports[fjp_posttype_comments]" value="comments" /> ' . __('Yes', 'fjp-custom-metaboxes') . ' &nbsp; 
					  <input type="radio" name="supports[fjp_posttype_comments]" value="no" checked /> ' . __('No', 'fjp-custom-metaboxes') . ' </td>' . PHP_EOL;
			}
			echo '</tr>' . PHP_EOL;
			
			echo '<tr valign="top">' . PHP_EOL;
			echo '<th scope="row">' . __('Revisions', 'fjp-custom-metaboxes') . '</th>' . PHP_EOL;
			if(in_array('revisions', $supports)) {
				echo '<td><input type="radio" name="supports[fjp_posttype_revisions]" value="revisions" checked /> ' . __('Yes', 'fjp-custom-metaboxes') . ' &nbsp; 
						  <input type="radio" name="supports[fjp_posttype_revisions]" value="no" /> ' . __('No', 'fjp-custom-metaboxes') . ' </td>' . PHP_EOL;
			} else {
				echo '<td><input type="radio" name="supports[fjp_posttype_revisions]" value="revisions" /> ' . __('Yes', 'fjp-custom-metaboxes') . ' &nbsp; 
					  <input type="radio" name="supports[fjp_posttype_revisions]" value="no" checked /> ' . __('No', 'fjp-custom-metaboxes') . ' </td>' . PHP_EOL;
			}
			echo '<th scope="row">' . __('Page Attributes', 'fjp-custom-metaboxes') . '</th>' . PHP_EOL;
			if(in_array('page-attributes', $supports)) {
				echo '<td><input type="radio" name="supports[fjp_posttype_page_attributes]" value="page-attributes" checked /> ' . __('Yes', 'fjp-custom-metaboxes') . ' &nbsp; 
						  <input type="radio" name="supports[fjp_posttype_page_attributes]" value="no" /> ' . __('No', 'fjp-custom-metaboxes') . ' </td>' . PHP_EOL;
			} else {
				echo '<td><input type="radio" name="supports[fjp_posttype_page_attributes]" value="page-attributes" /> ' . __('Yes', 'fjp-custom-metaboxes') . ' &nbsp; 
						  <input type="radio" name="supports[fjp_posttype_page_attributes]" value="no" checked /> ' . __('No', 'fjp-custom-metaboxes') . ' </td>' . PHP_EOL;
			}
			echo '</tr>' . PHP_EOL;
			
		echo '</table>' . PHP_EOL;
		if($action == 'edit') {
			echo '<p class="submit"><input type="submit" class="button-primary" value="' . __('Edit Post Type', 'fjp-custom-metaboxes') . '" />' . PHP_EOL;
		} else {
			echo '<p class="submit"><input type="submit" class="button-primary" value="' . __('Add Post Type', 'fjp-custom-metaboxes') . '" />' . PHP_EOL;
		}
	echo '</form>' . PHP_EOL;
	fjp_disp_posttype_records();
	echo '</div>' . PHP_EOL;
}

// Displays the FJP MetaBox Creation Page
function fjp_custom_metaboxes_page() {
	global $wpdb;
	$name = '';
	$group = '';
	$type = '';
	$location = '';
	$description = '';
	$action = (isset($_GET['action'])) ? $_GET['action'] : '';

	echo '<div class="wrap">' . PHP_EOL;
	echo '<h2>' . __('MetaBox Creation Page', 'fjp-custom-metaboxes') . '</h2>' . PHP_EOL;
	
	if($_POST) {
		if(fjp_insert_metabox($_POST)) {
			if($action == 'edit') {
				fjp_disp_message('MetaBox / Field Updated');
			} else {
				fjp_disp_message('MetaBox / Field Added');
			}
		} else {
			fjp_disp_message('Problem Adding MetaBox / Field Try Again');
		}
	}	
	
	if($action == 'edit') {
		$table_name = $wpdb->prefix . 'fjp_custommetaboxes';
		$sql = "SELECT * FROM `$table_name` WHERE `id`='" . mysql_real_escape_string($_GET['ref']) . "' LIMIT 1";
		$results = $wpdb->get_results($sql);
		$supports = explode(', ', $results[0]->supports);
		$name = $results[0]->name;
		$group = $results[0]->group;
		$type = $results[0]->type;
		$location = $results[0]->location;
		$description = $results[0]->desc;
	}
	
	if($action == 'edit') {
		echo '<form method="post" action="admin.php?page=fjp_custom_metaboxes&action=edit&ref=' . $_GET['ref']. '">' . PHP_EOL;
	} else {
		echo '<form method="post" action="admin.php?page=fjp_custom_metaboxes">' . PHP_EOL;
	}
	
	echo '<form method="post" action="admin.php?page=fjp_custom_metaboxes">' . PHP_EOL;
		echo '<table class="form-table">' . PHP_EOL;
		
			if($action == 'edit') {
				echo '<input type="hidden" name="fjp_custom_metaboxes-exists" value="' . $_GET['ref'] . '" />';
			}
			
			echo '<tr valign="top">' . PHP_EOL;
			echo '<th scope="row">' . __('Name', 'fjp-custom-metaboxes') . '</th>' . PHP_EOL;
			echo '<td><input type="text" name="fjp_metabox_name" id="fjp_metabox_name" value="' . $name . '" /></td>' . PHP_EOL;
			echo '</tr>' . PHP_EOL;
			
			echo '<tr valign="top">' . PHP_EOL;
			echo '<th scope="row">' . __('Group', 'fjp-custom-metaboxes') . '</th>' . PHP_EOL;
			echo '<td><input type="text" name="fjp_metabox_group" id="fjp_metabox_group" value="' . $group . '" /></td>' . PHP_EOL;
			echo '</tr>' . PHP_EOL;
			
			echo '<tr valign="top">' . PHP_EOL;
			echo '<th scope="row">' . __('Type', 'fjp-custom-metaboxes') . '</th>' . PHP_EOL;
			echo '<td>' . PHP_EOL;
				echo '<select name="fjp_metabox_type" id="fjp_metabox_type">'.PHP_EOL;
					if($type == 'textbox') {
						echo '<option value="textbox" selected>' . __('TextBox', 'fjp-custom-metaboxes') . '</option>' . PHP_EOL;
					} else {
						echo '<option value="textbox">' . __('TextBox', 'fjp-custom-metaboxes') . '</option>' . PHP_EOL;
					}
					if($type == 'textarea') {
						echo '<option value="textarea" selected>' . __('TextArea', 'fjp-custom-metaboxes') . '</option>' . PHP_EOL;
					} else {
						echo '<option value="textarea">' . __('TextArea', 'fjp-custom-metaboxes') . '</option>' . PHP_EOL;
					}
					if($type == 'checkbox') {
						echo '<option value="checkbox" checked>' . __('CheckBox', 'fjp-custom-metaboxes') . '</option>' . PHP_EOL;
					} else {
						echo '<option value="checkbox">' . __('CheckBox', 'fjp-custom-metaboxes') . '</option>' . PHP_EOL;
					}
				echo '</select>'.PHP_EOL;
			echo '</td>' . PHP_EOL;
			echo '</tr>' . PHP_EOL;
		
			echo '<tr valign="top">' . PHP_EOL;
			echo '<th scope="row">' . __('Location', 'fjp-custom-metaboxes') . '</th>' . PHP_EOL;
			echo '<td>' . PHP_EOL;
				echo '<select name="fjp_metabox_location" id="fjp_metabox_location">'.PHP_EOL;
				foreach(get_post_types(array('public' => true, 'show_ui' => true), 'label') as $key => $post_type) {
					if($key == $location) {
						echo '<option value="'.$key.'" selected>' . __($post_type->label, 'fjp-custom-metaboxes') . '</option>' . PHP_EOL;
					} else {
						echo '<option value="'.$key.'">' . __($post_type->label, 'fjp-custom-metaboxes') . '</option>' . PHP_EOL;
					}
				}
				echo '</select>'.PHP_EOL;
			echo '</td>' . PHP_EOL;
			echo '</tr>' . PHP_EOL;
			
			echo '<tr valign="top">' . PHP_EOL;
			echo '<th scope="row">' . __('Description', 'fjp-custom-metaboxes') . '</th>' . PHP_EOL;
			echo '<td><textarea name="fjp_metabox_desc" id="fjp_metabox_desc">' . $description . '</textarea></td>' . PHP_EOL;
			echo '</tr>' . PHP_EOL;
			
		echo '</table>' . PHP_EOL;
		if($action == 'edit') {
			echo '<p class="submit"><input type="submit" class="button-primary" value="' . __('Edit Field', 'fjp-custom-metaboxes') . '" />' . PHP_EOL;
		} else {
			echo '<p class="submit"><input type="submit" class="button-primary" value="' . __('Add Field', 'fjp-custom-metaboxes') . '" />' . PHP_EOL;
		}
	echo '</form>' . PHP_EOL;
	fjp_disp_metabox_records();
	echo '</div>' . PHP_EOL;
}

// Display Post Type Records
function fjp_disp_posttype_records() {
	global $wpdb;
	
	if(isset($_GET['from']) && $_GET['from'] == 'fjp_custom_posttypes' && isset($_GET['action']) && $_GET['action'] == 'trash' && isset($_GET['ref'])) {
		fjp_process_delete_post_type($_GET['ref']);
	}
	
	$table_name = $wpdb->prefix . 'fjp_posttypes';
	$sql = "SELECT * FROM `$table_name` ORDER BY id ASC";
	$results = $wpdb->get_results($sql);
	$total = count($results);
	echo '<table class="widefat post fixed" cellspacing="0">
			<thead>
				<tr>
					<th scope="col" id="name" class="manage-column column-name" style="">Name</th>
					<th scope="col" id="desc" class="manage-column column-supports" style="">Supports</th>
				</tr>
			</thead>
		
			<tfoot>
				<tr>
					<th scope="col" colspan="2"></th>
				</tr>
			</tfoot>
		
			<tbody>' . PHP_EOL;
	
				if($total > 0) {
					foreach($results as $result) {
						echo '<tr id="post-1" class="alternate author-self status-publish iedit" valign="top">
								<td class="post-title column-name">
									<strong><a class="row-name" href="admin.php?page=fjp_custom_posttypes&action=edit&ref=' . $result->id . '" title="Edit Ò' . __($result->name, 'fjp-custom-metaboxes') . 'Ó">' . __($result->name, 'fjp-custom-metaboxes') . '</a></strong>
									<div class="row-actions">
										<span class="edit"><a href="admin.php?page=fjp_custom_posttypes&action=edit&ref=' . $result->id . '" title="Edit this item">Edit</a> | </span>
										<span class="trash"><a class="submitdelete" title="Delete This Item" href="admin.php?page=fjp_custom_overview&from=fjp_custom_posttypes&action=trash&ref=' . $result->id . '">Delete</a></span>
									</div>
								</td>
								<td class="comments column-supports">
									' . __($result->supports, 'fjp-custom-metaboxes') . '
								</td>
							</tr>' . PHP_EOL;
					}
				} else {
					echo '<tr><td colspan="1">' . __('No custom post types found', 'fjp-custom-metaboxes') . '</td></tr>' . PHP_EOL;
				}
				
	echo '				
			</tbody>
		</table>' . PHP_EOL;
}

// Display MetaBox Records
function fjp_disp_metabox_records() {
	global $wpdb;
	
	if(isset($_GET['from']) && $_GET['from'] == 'fjp_custom_metaboxes' && isset($_GET['action']) && $_GET['action'] == 'trash' && isset($_GET['ref'])) {
		fjp_process_delete_custommeta($_GET['ref']);
	}
	
	$table_name = $wpdb->prefix . 'fjp_custommetaboxes';
	$sql = "SELECT * FROM `$table_name` ORDER BY id ASC";
	$results = $wpdb->get_results($sql);
	$total = count($results);
	echo '<table class="widefat post fixed" cellspacing="0">
			<thead>
				<tr>
					<th scope="col" id="name" class="manage-column column-name" style="">Name</th>
					<th scope="col" id="group" class="manage-column column-group" style="">Group</th>
					<th scope="col" id="type" class="manage-column column-type" style="">Type</th>
					<th scope="col" id="location" class="manage-column column-location" style="">Location</th>
					<th scope="col" id="desc" class="manage-column column-desc" style="">Description</th>
				</tr>
			</thead>
		
			<tfoot>
				<tr>
					<th scope="col" colspan="5"></th>
				</tr>
			</tfoot>
		
			<tbody>' . PHP_EOL;
	
				if($total > 0) {
					foreach($results as $result) {
						echo '<tr id="post-1" class="alternate author-self status-publish iedit" valign="top">
								<td class="post-title column-name">
									<strong><a class="row-name" href="admin.php?page=fjp_custom_metaboxes&action=edit&ref=' . $result->id . '" title="Edit Ò' . __($result->name, 'fjp-custom-metaboxes') . 'Ó">' . __($result->name, 'fjp-custom-metaboxes') . '</a></strong>
									<div class="row-actions">
										<span class="edit"><a href="admin.php?page=fjp_custom_metaboxes&action=edit&ref=' . $result->id . '" title="Edit this item">Edit</a> | </span>
										<span class="trash"><a class="submitdelete" title="Delete This Item" href="admin.php?page=fjp_custom_overview&from=fjp_custom_metaboxes&action=trash&ref=' . $result->id . '">Delete</a></span>
									</div>
								</td>
								<td class="author column-group">' . __($result->group, 'fjp-custom-metaboxes') . '</td>
								<td class="categories column-type">' . __($result->type, 'fjp-custom-metaboxes') . '</td>
								<td class="tags column-location">' . __($result->location, 'fjp-custom-metaboxes') . '</td>
								<td class="comments column-desc">
									' . __($result->desc, 'fjp-custom-metaboxes') . '
								</td>	
							</tr>' . PHP_EOL;
					}
				} else {
					echo '<tr><td colspan="5">' . __( 'No custom metaboxes found', 'fjp-custom-metaboxes') . '</td></tr>' . PHP_EOL;
				}
				
	echo '				
			</tbody>
		</table>' . PHP_EOL;
}

function fjp_metabox_formfield($id, $name, $type, $data = '', $desc = '') {
	switch($type) {
		case 'textbox':
			echo '<table class="form-table"><tr><th style="width:20%" nowrap>'.$name.':</th><td><input type="text" name="fjp['.$id.']" id="'.$id.'" value="'.$data.'" class="fieldfjp" size="30" style="width:97%" />
				  <br /><p style="font-size: 10px;">'.$desc.'</p></td></tr></table>';
			break;
		case 'textarea':
			echo '<table class="form-table"><tr><th style="width:20%" nowrap>'.$name.':</th><td><textarea name="fjp['.$id.']" id="'.$id.'" class="fieldfjp" cols="60" rows="4" style="width:97%">'.$data.'</textarea>
				  <br /><p style="font-size: 10px;">'.$desc.'</p></td></tr></table>';
			break;
		case 'checkbox':
			echo '<input type="hidden" name="fjp['.$id.']" id="'.$id.'" value="no" />'.PHP_EOL;
			if($data == 'yes') {
				echo '<table class="form-table"><tr><th style="width:20%" nowrap>'.$name.':</th><td><input type="checkbox" name="fjp['.$id.']" id="'.$id.'" value="yes" class="fieldfjp" checked />
					  <br /><p style="font-size: 10px;">'.$desc.'</p></td></tr></table>';
			} else {
				echo '<table class="form-table"><tr><th style="width:20%" nowrap>'.$name.':</th><td><input type="checkbox" name="fjp['.$id.']" id="'.$id.'" value="yes" class="fieldfjp" />
					  <br /><p style="font-size: 10px;">'.$desc.'</p></td></tr></table>';
			}
			break;
	}
}

?>