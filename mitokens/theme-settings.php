<?php

	/**
	 * @file
	 * Theme setting callbacks for the Mitokens theme.
	 */
	/**
	 * Implements hook_form_FORM_ID_alter().
	 *
	 * @param $form
	 *   The form.
	 * @param $form_state
	 *   The form state.
	 */
	function mitokens_form_system_theme_settings_alter(&$form, $form_state) {
		$form['layout_settings'] = array(
			'#type' => 'fieldset',
			'#title' => t('Layout settings'),
		);
		$form['layout_settings']['column_order_md'] = array(
			'#type' => 'radios',
			'#title' => t('Column Order on Medium Screens'),
			'#description' => t('Choose which order the sidbars display on Medium screens.'),
			'#default_value' => theme_get_setting('column_order_md'),
			'#required' => TRUE,
			'#options' => array(
				'columns-md-left' => t('Both bidebars float left'),
				'columns-md-right' => t('Both sidebars float right'),
			),
		);
		$form['layout_settings']['column_order_lg'] = array(
			'#type' => 'radios',
			'#title' => t('Column Order on Large Screens'),
			'#description' => t('Choose which order the sidbars display on large screens.'),
			'#default_value' => theme_get_setting('column_order_lg'),
			'#required' => TRUE,
			'#options' => array(
				'columns-lg-left-right' => t('1st sidebar floats left, 2nd sidebar floats right'),
				'columns-lg-right-left' => t('1st sidebar floats right, 2nd sidebar floats left'),
			),
		);
	}
