<?php

if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
	'key' => 'group_5e850c4b1d759',
	'title' => 'Expiry Tracking',
	'fields' => array(
		array(
			'key' => 'field_5e850c8c2201d',
			'label' => 'Certifications',
			'name' => 'certification_list',
			'type' => 'repeater',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'collapsed' => 'field_5e850ca02201e',
			'min' => 0,
			'max' => 0,
			'layout' => 'table',
			'button_label' => 'Add Certificate',
			'sub_fields' => array(
				array(
					'key' => 'field_5e850ca02201e',
					'label' => 'Certificate',
					'name' => 'certificate',
					'type' => 'post_object',
					'instructions' => 'Choose the course product that issues the certificate.',
					'required' => 1,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'post_type' => array(
						0 => 'product',
					),
					'taxonomy' => '',
					'allow_null' => 0,
					'multiple' => 0,
					'return_format' => 'object',
					'ui' => 1,
				),
				array(
					'key' => 'field_5e850cf92201f',
					'label' => 'Expiry Date',
					'name' => 'expiry_date',
					'type' => 'date_picker',
					'instructions' => '',
					'required' => 1,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'display_format' => 'Y-m-d',
					'return_format' => 'Y-m-d',
					'first_day' => 1,
				),
			),
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'user_form',
				'operator' => '==',
				'value' => 'edit',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => true,
	'description' => '',
));

endif;
