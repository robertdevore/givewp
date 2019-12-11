<?php
return array(
	/**
	 * Repeatable Field Groups
	 */
	'form_field_options'    => apply_filters( 'give_forms_field_options', array(
		'id'        => 'form_field_options',
		'title'     => __( 'Donation Options', 'give' ),
		'icon-html' => '<span class="give-icon give-icon-heart"></span>',
		'fields'    => apply_filters( 'give_forms_donation_form_metabox_fields', array(
			// Donation Option.
			array(
				'name'        => __( 'Donation Option', 'give' ),
				'description' => __( 'Do you want this form to have one set donation price or multiple levels (for example, $10, $20, $50)?', 'give' ),
				'id'          => $prefix . 'price_option',
				'type'        => 'radio_inline',
				'default'     => 'multi',
				'options'     => apply_filters( 'give_forms_price_options', array(
					'multi' => __( 'Multi-level Donation', 'give' ),
					'set'   => __( 'Set Donation', 'give' ),
				) ),
			),
			array(
				'name'        => __( 'Set Donation', 'give' ),
				'description' => __( 'This is the set donation amount for this form. If you have a "Custom Amount Minimum" set, make sure it is less than this amount.', 'give' ),
				'id'          => $prefix . 'set_price',
				'type'        => 'text_small',
				'data_type'   => 'price',
				'attributes'  => array(
					'placeholder' => $price_placeholder,
					'class'       => 'give-money-field',
				),
			),
			// Display Style.
			array(
				'name'          => __( 'Display Style', 'give' ),
				'description'   => __( 'Set how the donations levels will display on the form.', 'give' ),
				'id'            => $prefix . 'display_style',
				'type'          => 'radio_inline',
				'default'       => 'buttons',
				'options'       => array(
					'buttons'  => __( 'Buttons', 'give' ),
					'radios'   => __( 'Radios', 'give' ),
					'dropdown' => __( 'Dropdown', 'give' ),
				),
				'wrapper_class' => 'give-hidden',
			),
			// Custom Amount.
			array(
				'name'        => __( 'Custom Amount', 'give' ),
				'description' => __( 'Do you want the user to be able to input their own donation amount?', 'give' ),
				'id'          => $prefix . 'custom_amount',
				'type'        => 'radio_inline',
				'default'     => 'disabled',
				'options'     => array(
					'enabled'  => __( 'Enabled', 'give' ),
					'disabled' => __( 'Disabled', 'give' ),
				),
			),
			array(
				'name'          => __( 'Donation Limit', 'give' ),
				'description'   => __( 'Set the minimum and maximum amount for all gateways.', 'give' ),
				'id'            => $prefix . 'custom_amount_range',
				'type'          => 'donation_limit',
				'wrapper_class' => 'give-hidden',
				'data_type'     => 'price',
				'attributes'    => array(
					'placeholder' => $price_placeholder,
					'class'       => 'give-money-field',
				),
				'options'       => array(
					'display_label' => __( 'Donation Limits: ', 'give' ),
					'minimum'       => give_format_decimal( '1.00', false, false ),
					'maximum'       => give_format_decimal( '999999.99', false, false ),
				),
			),
			array(
				'name'          => __( 'Custom Amount Text', 'give' ),
				'description'   => __( 'This text appears as a label below the custom amount field for set donation forms. For multi-level forms the text will appear as it\'s own level (ie button, radio, or select option).', 'give' ),
				'id'            => $prefix . 'custom_amount_text',
				'type'          => 'text_medium',
				'attributes'    => array(
					'rows'        => 3,
					'placeholder' => __( 'Give a Custom Amount', 'give' ),
				),
				'wrapper_class' => 'give-hidden',
			),
			// Donation Levels.
			array(
				'id'            => $prefix . 'donation_levels',
				'type'          => 'group',
				'options'       => array(
					'add_button'    => __( 'Add Level', 'give' ),
					'header_title'  => __( 'Donation Level', 'give' ),
					'remove_button' => '<span class="dashicons dashicons-no"></span>',
				),
				'wrapper_class' => 'give-hidden',
				// Fields array works the same, except id's only need to be unique for this group.
				// Prefix is not needed.
				'fields'        => apply_filters( 'give_donation_levels_table_row', array(
					array(
						'name' => __( 'ID', 'give' ),
						'id'   => $prefix . 'id',
						'type' => 'levels_id',
					),
					array(
						'name'       => __( 'Amount', 'give' ),
						'id'         => $prefix . 'amount',
						'type'       => 'text_small',
						'data_type'  => 'price',
						'attributes' => array(
							'placeholder' => $price_placeholder,
							'class'       => 'give-money-field',
						),
					),
					array(
						'name'       => __( 'Text', 'give' ),
						'id'         => $prefix . 'text',
						'type'       => 'text',
						'attributes' => array(
							'placeholder' => __( 'Donation Level', 'give' ),
							'class'       => 'give-multilevel-text-field',
						),
					),
					array(
						'name' => __( 'Default', 'give' ),
						'id'   => $prefix . 'default',
						'type' => 'give_default_radio_inline',
					),
				) ),
			),
			array(
				'name'  => 'donation_options_docs',
				'type'  => 'docs_link',
				'url'   => 'http://docs.givewp.com/form-donation-options',
				'title' => __( 'Donation Options', 'give' ),
			),
		),
			$post_id
		),
	) ),

	/**
	 * Display Options
	 */
	'form_display_options'  => apply_filters( 'give_form_display_options', array(
			'id'        => 'form_display_options',
			'title'     => __( 'Form Display', 'give' ),
			'icon-html' => '<span class="give-icon give-icon-display"></span>',
			'fields'    => apply_filters( 'give_forms_display_options_metabox_fields', array(
				array(
					'name'    => __( 'Display Options', 'give' ),
					'desc'    => sprintf( __( 'How would you like to display donation information for this form?', 'give' ), '#' ),
					'id'      => $prefix . 'payment_display',
					'type'    => 'radio_inline',
					'options' => array(
						'onpage' => __( 'All Fields', 'give' ),
						'modal'  => __( 'Modal', 'give' ),
						'reveal' => __( 'Reveal', 'give' ),
						'button' => __( 'Button', 'give' ),
					),
					'default' => 'onpage',
				),
				array(
					'id'            => $prefix . 'reveal_label',
					'name'          => __( 'Continue Button', 'give' ),
					'desc'          => __( 'The button label for displaying the additional payment fields.', 'give' ),
					'type'          => 'text_small',
					'attributes'    => array(
						'placeholder' => __( 'Donate Now', 'give' ),
					),
					'wrapper_class' => 'give-hidden',
				),
				array(
					'id'         => $prefix . 'checkout_label',
					'name'       => __( 'Submit Button', 'give' ),
					'desc'       => __( 'The button label for completing a donation.', 'give' ),
					'type'       => 'text_small',
					'attributes' => array(
						'placeholder' => __( 'Donate Now', 'give' ),
					),
				),
				array(
					'name' => __( 'Default Gateway', 'give' ),
					'desc' => __( 'By default, the gateway for this form will inherit the global default gateway (set under GiveWP > Settings > Payment Gateways). This option allows you to customize the default gateway for this form only.', 'give' ),
					'id'   => $prefix . 'default_gateway',
					'type' => 'default_gateway',
				),
				array(
					'name'    => __( 'Name Title Prefix', 'give' ),
					'desc'    => __( 'Do you want to add a name title prefix dropdown field before the donor\'s first name field? This will display a dropdown with options such as Mrs, Miss, Ms, Sir, and Dr for donor to choose from.', 'give' ),
					'id'      => $prefix . 'name_title_prefix',
					'type'    => 'radio_inline',
					'options' => array(
						'global' => __( 'Global Option', 'give' ),
						'required' => __( 'Required', 'give' ),
						'optional' => __( 'Optional', 'give' ),
						'disabled' => __( 'Disabled', 'give' ),
					),
					'default' => 'global',
				),
				array(
					'name'          => __( 'Title Prefixes', 'give' ),
					'desc'          => __( 'Add or remove salutations from the dropdown using the field above.', 'give' ),
					'id'            => $prefix . 'title_prefixes',
					'type'          => 'chosen',
					'data_type'     => 'multiselect',
					'style'         => 'width: 100%',
					'wrapper_class' => 'give-hidden give-title-prefixes-wrap',
					'options'       => give_get_default_title_prefixes(),
				),
				array(
					'name'    => __( 'Company Donations', 'give' ),
					'desc'    => __( 'Do you want a Company field to appear after First Name and Last Name?', 'give' ),
					'id'      => $prefix . 'company_field',
					'type'    => 'radio_inline',
					'default' => 'global',
					'options' => array(
						'global'   => __( 'Global Option', 'give' ),
						'required' => __( 'Required', 'give' ),
						'optional' => __( 'Optional', 'give' ),
						'disabled' => __( 'Disabled', 'give' ),

					),
				),
				array(
					'name'    => __( 'Anonymous Donations', 'give' ),
					'desc'    => __( 'Do you want to provide donors the ability mark themselves anonymous while giving. This will prevent their information from appearing publicly on your website but you will still receive their information for your records in the admin panel.', 'give' ),
					'id'      => "{$prefix}anonymous_donation",
					'type'    => 'radio_inline',
					'default' => 'global',
					'options' => array(
						'global'   => __( 'Global Option', 'give' ),
						'enabled'  => __( 'Enabled', 'give' ),
						'disabled' => __( 'Disabled', 'give' ),
					),
				),
				array(
					'name'    => __( 'Donor Comments', 'give' ),
					'desc'    => __( 'Do you want to provide donors the ability to add a comment to their donation? The comment will display publicly on the donor wall if they do not select to give anonymously.', 'give' ),
					'id'      => "{$prefix}donor_comment",
					'type'    => 'radio_inline',
					'default' => 'global',
					'options' => array(
						'global'   => __( 'Global Option', 'give' ),
						'enabled'  => __( 'Enabled', 'give' ),
						'disabled' => __( 'Disabled', 'give' ),
					),
				),
				array(
					'name'    => __( 'Guest Donations', 'give' ),
					'desc'    => __( 'Do you want to allow non-logged-in users to make donations?', 'give' ),
					'id'      => $prefix . 'logged_in_only',
					'type'    => 'radio_inline',
					'default' => 'enabled',
					'options' => array(
						'enabled'  => __( 'Enabled', 'give' ),
						'disabled' => __( 'Disabled', 'give' ),
					),
				),
				array(
					'name'    => __( 'Registration', 'give' ),
					'desc'    => __( 'Display the registration and login forms in the payment section for non-logged-in users.', 'give' ),
					'id'      => $prefix . 'show_register_form',
					'type'    => 'radio',
					'options' => array(
						'none'         => __( 'None', 'give' ),
						'registration' => __( 'Registration', 'give' ),
						'login'        => __( 'Login', 'give' ),
						'both'         => __( 'Registration + Login', 'give' ),
					),
					'default' => 'none',
				),
				array(
					'name'    => __( 'Floating Labels', 'give' ),
					/* translators: %s: forms http://docs.givewp.com/form-floating-labels */
					'desc'    => sprintf( __( 'Select the <a href="%s" target="_blank">floating labels</a> setting for this GiveWP form. Be aware that if you have the "Disable CSS" option enabled, you will need to style the floating labels yourself.', 'give' ), esc_url( 'http://docs.givewp.com/form-floating-labels' ) ),
					'id'      => $prefix . 'form_floating_labels',
					'type'    => 'radio_inline',
					'options' => array(
						'global'   => __( 'Global Option', 'give' ),
						'enabled'  => __( 'Enabled', 'give' ),
						'disabled' => __( 'Disabled', 'give' ),
					),
					'default' => 'global',
				),
				array(
					'name'  => 'form_display_docs',
					'type'  => 'docs_link',
					'url'   => 'http://docs.givewp.com/form-display-options',
					'title' => __( 'Form Display', 'give' ),
				),
			),
				$post_id
			),
		)
	),

	/**
	 * Donation Goals
	 */
	'donation_goal_options' => apply_filters( 'give_donation_goal_options', array(
		'id'        => 'donation_goal_options',
		'title'     => __( 'Donation Goal', 'give' ),
		'icon-html' => '<span class="give-icon give-icon-target"></span>',
		'fields'    => apply_filters( 'give_forms_donation_goal_metabox_fields', array(
			// Goals
			array(
				'name'        => __( 'Donation Goal', 'give' ),
				'description' => __( 'Do you want to set a donation goal for this form?', 'give' ),
				'id'          => $prefix . 'goal_option',
				'type'        => 'radio_inline',
				'default'     => 'disabled',
				'options'     => array(
					'enabled'  => __( 'Enabled', 'give' ),
					'disabled' => __( 'Disabled', 'give' ),
				),
			),

			array(
				'name'        => __( 'Goal Format', 'give' ),
				'description' => __( 'Do you want to display the total amount raised based on your monetary goal or a percentage? For instance, "$500 of $1,000 raised" or "50% funded" or "1 of 5 donations". You can also display a donor-based goal, such as "100 of 1,000 donors have given".', 'give' ),
				'id'          => $prefix . 'goal_format',
				'type'        => 'donation_form_goal',
				'default'     => 'amount',
				'options'     => array(
					'amount'     => __( 'Amount Raised', 'give' ),
					'percentage' => __( 'Percentage Raised', 'give' ),
					'donation'   => __( 'Number of Donations', 'give' ),
					'donors'     => __( 'Number of Donors', 'give' ),
				),
			),

			array(
				'name'          => __( 'Goal Amount', 'give' ),
				'description'   => __( 'This is the monetary goal amount you want to reach for this form.', 'give' ),
				'id'            => $prefix . 'set_goal',
				'type'          => 'text_small',
				'data_type'     => 'price',
				'attributes'    => array(
					'placeholder' => $price_placeholder,
					'class'       => 'give-money-field',
				),
				'wrapper_class' => 'give-hidden',
			),
			array(
				'id'         => $prefix . 'number_of_donation_goal',
				'name'       => __( 'Donation Goal', 'give' ),
				'desc'       => __( 'Set the total number of donations as a goal.', 'give' ),
				'type'       => 'number',
				'default'    => 1,
				'attributes' => array(
					'placeholder' => 1,
				),
			),
			array(
				'id'         => $prefix . 'number_of_donor_goal',
				'name'       => __( 'Donor Goal', 'give' ),
				'desc'       => __( 'Set the total number of donors as a goal.', 'give' ),
				'type'       => 'number',
				'default'    => 1,
				'attributes' => array(
					'placeholder' => 1,
				),
			),
			array(
				'name'          => __( 'Progress Bar Color', 'give' ),
				'desc'          => __( 'Customize the color of the goal progress bar.', 'give' ),
				'id'            => $prefix . 'goal_color',
				'type'          => 'colorpicker',
				'default'       => '#2bc253',
				'wrapper_class' => 'give-hidden',
			),

			array(
				'name'          => __( 'Close Form', 'give' ),
				'desc'          => __( 'Do you want to close the donation forms and stop accepting donations once this goal has been met?', 'give' ),
				'id'            => $prefix . 'close_form_when_goal_achieved',
				'type'          => 'radio_inline',
				'default'       => 'disabled',
				'options'       => array(
					'enabled'  => __( 'Enabled', 'give' ),
					'disabled' => __( 'Disabled', 'give' ),
				),
				'wrapper_class' => 'give-hidden',
			),
			array(
				'name'          => __( 'Goal Achieved Message', 'give' ),
				'desc'          => __( 'Do you want to display a custom message when the goal is closed?', 'give' ),
				'id'            => $prefix . 'form_goal_achieved_message',
				'type'          => 'wysiwyg',
				'default'       => __( 'Thank you to all our donors, we have met our fundraising goal.', 'give' ),
				'wrapper_class' => 'give-hidden',
			),
			array(
				'name'  => 'donation_goal_docs',
				'type'  => 'docs_link',
				'url'   => 'http://docs.givewp.com/form-donation-goal',
				'title' => __( 'Donation Goal', 'give' ),
			),
		),
			$post_id
		),
	) ),

	/**
	 * Content Field
	 */
	'form_content_options'  => apply_filters( 'give_forms_content_options', array(
		'id'        => 'form_content_options',
		'title'     => __( 'Form Content', 'give' ),
		'icon-html' => '<span class="give-icon give-icon-edit"></span>',
		'fields'    => apply_filters( 'give_forms_content_options_metabox_fields', array(

			// Donation content.
			array(
				'name'        => __( 'Display Content', 'give' ),
				'description' => __( 'Do you want to add custom content to this form?', 'give' ),
				'id'          => $prefix . 'display_content',
				'type'        => 'radio_inline',
				'options'     => array(
					'enabled'  => __( 'Enabled', 'give' ),
					'disabled' => __( 'Disabled', 'give' ),
				),
				'default'     => 'disabled',
			),

			// Content placement.
			array(
				'name'          => __( 'Content Placement', 'give' ),
				'description'   => __( 'This option controls where the content appears within the donation form.', 'give' ),
				'id'            => $prefix . 'content_placement',
				'type'          => 'radio_inline',
				'options'       => apply_filters( 'give_forms_content_options_select', array(
						'give_pre_form'  => __( 'Above fields', 'give' ),
						'give_post_form' => __( 'Below fields', 'give' ),
					)
				),
				'default'       => 'give_pre_form',
				'wrapper_class' => 'give-hidden',
			),
			array(
				'name'          => __( 'Content', 'give' ),
				'description'   => __( 'This content will display on the single give form page.', 'give' ),
				'id'            => $prefix . 'form_content',
				'type'          => 'wysiwyg',
				'wrapper_class' => 'give-hidden',
			),
			array(
				'name'  => 'form_content_docs',
				'type'  => 'docs_link',
				'url'   => 'http://docs.givewp.com/form-content',
				'title' => __( 'Form Content', 'give' ),
			),
		),
			$post_id
		),
	) ),

	/**
	 * Terms & Conditions
	 */
	'form_terms_options'    => apply_filters( 'give_forms_terms_options', array(
		'id'        => 'form_terms_options',
		'title'     => __( 'Terms & Conditions', 'give' ),
		'icon-html' => '<span class="give-icon give-icon-checklist"></span>',
		'fields'    => apply_filters( 'give_forms_terms_options_metabox_fields', array(
			// Donation Option
			array(
				'name'        => __( 'Terms and Conditions', 'give' ),
				'description' => __( 'Do you want to require the donor to accept terms prior to being able to complete their donation?', 'give' ),
				'id'          => $prefix . 'terms_option',
				'type'        => 'radio_inline',
				'options'     => apply_filters( 'give_forms_content_options_select', array(
						'global'   => __( 'Global Option', 'give' ),
						'enabled'  => __( 'Customize', 'give' ),
						'disabled' => __( 'Disable', 'give' ),
					)
				),
				'default'     => 'global',
			),
			array(
				'id'            => $prefix . 'agree_label',
				'name'          => __( 'Agreement Label', 'give' ),
				'desc'          => __( 'The label shown next to the agree to terms check box. Add your own to customize or leave blank to use the default text placeholder.', 'give' ),
				'type'          => 'textarea',
				'attributes'    => array(
					'placeholder' => __( 'Agree to Terms?', 'give' ),
					'rows'        => 1
				),
				'wrapper_class' => 'give-hidden',
			),
			array(
				'id'            => $prefix . 'agree_text',
				'name'          => __( 'Agreement Text', 'give' ),
				'desc'          => __( 'This is the actual text which the user will have to agree to in order to make a donation.', 'give' ),
				'default'       => give_get_option( 'agreement_text' ),
				'type'          => 'wysiwyg',
				'wrapper_class' => 'give-hidden',
			),
			array(
				'name'  => 'terms_docs',
				'type'  => 'docs_link',
				'url'   => 'http://docs.givewp.com/form-terms',
				'title' => __( 'Terms and Conditions', 'give' ),
			),
		),
			$post_id
		),
	) ),
);
