<?php
	
	/*
	** Uses drupal_add_css().
	** Adds an array of filenames to the stylesheet queue.
	** @param $filenames
	**   The array of filenames to add.
	**   Any filenames missing ".css" will have ".tpl.css" appended.
	** @param $base_path
	**   The directory where the files are stored.
	**   Gets prepended to each of $filenames.
	**   For any empty strings in $filenames, $base_path is trimmed of dashes.
	** @param $options
	**   See drupal_add_css().
	**   Default group set to CSS_THEME.
	*/
	function mitokens_add_css($filenames, $base_path="", $options=NULL) {
		$results = array();
		if (!$options) {
			$options = array();
		}
		if (!isset($options["group"])) {
			$options["group"] = CSS_THEME;
		}
		if (!$base_path) {
			$base_path = path_to_theme() . "/styles/";
		}
		if (is_array($filenames) && is_string($base_path) && is_array($options)) {
			foreach ($filenames as $filekey => $filename) {
				if (is_string($filename)) {
					$prepend = ($filename)? trim($base_path): trim(trim($base_path), '-_');
					if (strlen($filename) < 4 || substr_compare($filename, ".css", -4, 4, TRUE) !== 0) {
						$filename .= ".tpl.css";
					}
					$results = drupal_add_css($prepend . $filename, $options);
				}
			}
		}
		return $results;
	}
	
	/* 
	** Uses drupal_set_message().
	** @param $message
	**   The message to output.
	**   $message is converted to a string if it's an Array or an Object.
	** @param $access
	**   Determines if the current user has access to the $message.
	**   $access can match a Role ID, Role Name, or Permission Name.
	**   $access is inverted if it's a negative number or begins with "!".
	*/
	function mitokens_set_message($message, $access=2, $type="status", $repeat=TRUE) {
		// Convert $message to a preformatted HTML string.
		if (is_object($message) || is_array($message)) {
			$message = "<pre>" . print_r($message, TRUE) . "</pre>";
		}
		// Determine if the current user has correct $access to the message.
		$access_verdict = FALSE;
		if (is_string($access)) {
			drupal_set_message("access by string");
			// If $access starts with "!", negate $access.
			$access_negate = ($access[0] == '!');
			if ($access_negate) {
				$access = substr($access, 1);
			}
			$access_role = array_search($access, user_roles());
			$access_permission = user_access($access);
			if (($access_role || $access_permission) xor $access_negate) {
				$access_verdict = TRUE;
			}
		}
		elseif (is_int($access)) {
			// If $access is negative, negate $access.
			$access_negate = ($access < 0);
			$access_role = user_has_role(abs($access));
			//drupal_set_message("access by int: " . var_export($access_role, TRUE));
			if ($access_role xor $access_negate) {
				$access_verdict = TRUE;
			}
		}
		// Set a message only if the current user has the correct $access.
		if ($access_verdict) {
			drupal_set_message($message, $type, $repeat);
		}
	}
	
	/*
	** Implements theme_file_icon().
	*/
	function mitokens_file_icon($variables) {
		$file = $variables['file'];
		$icon_directory = path_to_theme() . '/images/icons';
		$mime = check_plain($file->filemime);
		$icon_url = file_icon_url($file, $icon_directory);
		return '<img alt="' . t('file icon !mime', array('!mime' => $mime)) . '" class="file-icon" src="' . $icon_url . '"/>';
	}
	
	/*
	** Implements theme_file_link().
	*/
	function mitokens_file_link(&$variables) {
		$output = '';
		$file = $variables['file'];
		$variables['icon_directory'] = path_to_theme() . '/images/icons';
		$icon_directory = $variables['icon_directory'];
		$url = file_create_url($file->uri);
		$mime_name = array(
			'application/msword' => t('Microsoft Office document icon'),
			'application/vnd.ms-excel' => t('Office spreadsheet icon'),
			'application/vnd.ms-powerpoint' => t('Office presentation icon'),
			'application/pdf' => t('PDF icon'),
			'video/quicktime' => t('Movie icon'),
			'audio/mpeg' => t('Audio icon'),
			'audio/wav' => t('Audio icon'),
			'image/jpeg' => t('Image icon'),
			'image/png' => t('Image icon'),
			'image/gif' => t('Image icon'),
			'application/zip' => t('Package icon'),
			'text/html' => t('HTML icon'),
			'text/plain' => t('Plain text icon'),
			'application/octet-stream' => t('Binary Data'),
		);
		$mimetype = file_get_mimetype($file->uri);
		$icon = theme('file_icon', array(
			'file' => $file,
			'icon_directory' => $icon_directory,
			'alt' => !empty($mime_name[$mimetype]) ? $mime_name[$mimetype] : t('File'),
		));
		$options = array(
			'attributes' => array(
				'type' => $file->filemime . '; length=' . $file->filesize,
				'target' => '_blank',
			),
			'html' => TRUE,
		);
		if (empty($file->description)) {
			$link_text = $file->filename;
		}
		else {
			$link_text = $file->description;
			$options['attributes']['title'] = check_plain($file->filename);
		}
		$link_text = $icon . ' ' . t($link_text);
		$output .= '<span class="file"> ' . l($link_text, $url, $options) . '</span>';
		return $output;
	}
	
	/*
	** Modifies body fields in a form.
	** Disables formatting help text below the WYSIWYG editor.
	*/
	function mitokens_form_after_build(&$form) {
		$form[LANGUAGE_NONE][0]['format']['guidelines']['#access'] = false;
		$form[LANGUAGE_NONE][0]['format']['help']['#access'] = false;
		$form[LANGUAGE_NONE][0]['format']['#theme_wrappers'] = NULL;
		return $form;
	}
	
	/*
	** Implements hook_form_alter().
	*/
	function mitokens_form_alter(&$form, &$form_state, &$form_id) {
		$form['field_body']['#after_build'][] = 'mitokens_form_after_build';
	}
	
	/*
	** Implements hook_form_comment_form_alter().
	*/
	function mitokens_form_comment_form_alter(&$form, &$form_state, &$form_id) {
		$form['comment_body']['#after_build'][] = 'mitokens_form_after_build';
	}
	
	/*
	** Implements theme_menu_link().
	*/
	function mitokens_menu_link(&$variables) {
		$element = $variables['element'];
		if ($element['#original_link']['menu_name'] == 'main-menu' && $element['#original_link']['depth'] > 2) {
			return '';
		}
		$active_mitem = menu_get_item();
		// unset($active_mitem['page_arguments']);
		$mlid = $element['#original_link']['mlid'];
		$plid = $element['#original_link']['plid'];
		$element['#attributes']['class'][] = 'mlid-' . $mlid;
		$element['#localized_options']['attributes']['class'][] = 'mlid-' . $mlid;
		if ($element['#original_link']['link_path'] == $active_mitem['tab_root_href'] || $element['#original_link']['link_path'] == $_GET['q']) {
			$element['#attributes']['class'][] = 'active';
		}
		$sub_menu = '';
		$dropdown = '';
		if ($element['#below'] && $element['#original_link']['depth'] < 2) {
			$element['#attributes']['class'][] = 'has-submenu';
			$sub_menu = drupal_render($element['#below']);
			$tooltip = t('toggle menu for ' . $element['#title']);
			if ($element['#original_link']['link_path'] != '<front>') {
				$dropdown = '<input aria-expanded="false" aria-haspopup="true" id="mlid-' . $mlid . '" title="' . $tooltip .'" type="checkbox"/>';
			}
		}
		if ($sub_menu && $element['#original_link']['link_path'] == '<front>') {
			$element['#href'] = '';
			$element['#localized_options']['attributes']['aria-expanded'] = 'false';
			$element['#localized_options']['attributes']['aria-haspopup'] = 'true';
			$element['#localized_options']['attributes']['name'] = 'mlid-' . $mlid;
			$element['#localized_options']['fragment'] = '';
			$link = l($element['#title'], $element['#href'],  $element['#localized_options']);
		}
		else {
			// $element['#localized_options']['fragment'] = 'page-header';
			$link = l($element['#title'], $element['#href'], $element['#localized_options']);
		}
		$output = '<li' . drupal_attributes($element['#attributes']) . '>' . $link . $dropdown . $sub_menu . "</li>";
		return $output;
	}
	
	/*
	** Implements theme_menu_tree().
	*/
	function mitokens_menu_tree(&$variables) {
		$tree = reset($variables['#tree']);
		$classes = ($tree['#original_link']['depth'] > 1)? ('menu submenu'): ('menu');
		$classes .= ($tree['#original_link']['menu_name'] == 'menu-footer-menu')? (' tabs'): ('');
		return '<ul class="' . $classes . '">' . $variables['tree'] . '</ul>';
	}
	
	/*
	** Implements template_preprocess_block().
	*/
	function mitokens_preprocess_block(&$variables) {
		// Add tpl.CSS files based on variables identifying the block.
		$tpl_paths_base = path_to_theme() . "/styles/block--";
		$tpl_paths[] = ""; // Results in block.tpl.css
		$tpl_paths[] = drupal_clean_css_identifier($variables['block']->bid);
		$tpl_paths[] = drupal_clean_css_identifier($variables['block']->module);
		$tpl_paths[] = drupal_clean_css_identifier($variables['block']->module) . '--' . drupal_clean_css_identifier($variables['block']->delta);
		mitokens_add_css($tpl_paths, $tpl_paths_base);
	}
	
	/*
	** Implements template_preprocess_html().
	*/
	function mitokens_preprocess_html(&$variables) {
		// Generate classes to add to the <html> element.
		$currentUser = $GLOBALS['user'];
		$variables['classes_html'] = 'drupal';
		$variables['classes_html'] .= ' user-' . $currentUser->uid;
		$variables['classes_html'] .= ' ' . str_replace(',', ' ', str_replace(' ', '-', strtolower(implode(',', $currentUser->roles))));
	}
	
	/*
	** Implements template_preprocess_node().
	*/
	function mitokens_preprocess_node(&$variables) {
		// Add tpl.CSS files based on variables identifying the node.
		$tpl_paths_base = path_to_theme() . "/styles/node--";
		$tpl_paths[] = ""; // Results in node.tpl.css
		$tpl_paths[] = drupal_clean_css_identifier($variables['node']->nid);
		$tpl_paths[] = drupal_clean_css_identifier($variables['node']->type);
		mitokens_add_css($tpl_paths, $tpl_paths_base);
	}
	
	/*
	** Implements template_preprocess_page().
	*/
	function mitokens_preprocess_page(&$variables) {
		// Add variables for the page's columns.
		$variables['column_order'] = theme_get_setting('column_order_md');
		$variables['column_order'] .= ' ' . theme_get_setting('column_order_lg');
		$column_count = 0;
		foreach (system_region_list($GLOBALS['theme'], REGIONS_VISIBLE) as $region_key => $region_value) {
			if ($variables['page'][$region_key] && preg_match('/column_/i', $region_key)) {
				$column_count += 1;
			}
		}
		$variables['column_count'] = "columns-" . (string)$column_count;
	}
	
	/*
	** Implements template_preprocess_region().
	*/
	function mitokens_preprocess_region(&$variables) {
		// Enable extra variables to be used in region templates
		$variables['messages'] = theme('status_messages');
		$variables['tabs'] = menu_local_tabs();
		$variables['action_links'] = menu_local_actions();
		$variables['page_title'] = drupal_get_title();
		$variables['breadcrumb'] = theme('breadcrumb', array('breadcrumb' => drupal_get_breadcrumb()));
	}
	
	/*
	** Implements template_preprocess_views_view().
	*/
	function mitokens_preprocess_views_view(&$variables) {
		// Add tpl.CSS files based on variables identifying the view.
		$tpl_paths_base = path_to_theme() . "/styles/views-view--";
		$tpl_paths[] = ""; // Results in views-view.tpl.css
		$tpl_paths[] = drupal_clean_css_identifier($variables["view"]->vid);
		$tpl_paths[] = drupal_clean_css_identifier($variables["view"]->name);
		$tpl_paths[] = drupal_clean_css_identifier($variables["view"]->current_display);
		mitokens_add_css($tpl_paths, $tpl_paths_base);
	}
	