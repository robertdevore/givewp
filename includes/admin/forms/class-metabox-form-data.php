<?php
/**
 * Donation Form Data
 *
 * Displays the form data box, tabbed, with several panels.
 *
 * @package     Give
 * @subpackage  Classes/Give_MetaBox_Form_Data
 * @copyright   Copyright (c) 2016, GiveWP
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.8
 */

/**
 * Give_Meta_Box_Form_Data Class.
 */
class Give_MetaBox_Form_Data {

	/**
	 * @since 2.5.11
	 *
	 * @var Give_MetaBox_Form_Data
	 */
	private static $instance;

	/**
	 * Meta box settings.
	 *
	 * @since 1.8
	 * @var   array
	 */
	private $settings = array();

	/**
	 * Metabox ID.
	 *
	 * @since 1.8
	 * @var   string
	 */
	private $metabox_id;

	/**
	 * Metabox Label.
	 *
	 * @since 1.8
	 * @var   string
	 */
	private $metabox_label;

	/**
	 * Singleton pattern.
	 *
	 * @since  1.0
	 * @access private
	 * Give_Form_Countdown_Metabox_Settings constructor.
	 */
	private function __construct() {
	}


	/**
	 * Get single instance.
	 *
	 * @since  1.0
	 * @access public
	 * @return Give_MetaBox_Form_Data
	 */
	public static function get_instance() {
		if ( null === static::$instance ) {
			static::$instance = new static();
		}

		return static::$instance;
	}


	/**
	 * Give_MetaBox_Form_Data constructor.
	 */
	public function init() {
		$this->metabox_id    = 'give-metabox-form-data';
		$this->metabox_label = __( 'Donation Form Options', 'give' );

		// Setup.
		add_action( 'admin_init', array( $this, 'setup' ) );

		// Add metabox.
		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ), 10 );

		// Save form meta.
		add_action( 'save_post_give_forms', array( $this, 'handle_form_save' ), 10, 2 );

		// cmb2 old setting loaders.
		// add_filter( 'give_metabox_form_data_settings', array( $this, 'cmb2_metabox_settings' ) );
		// Add offline donations options.
		add_filter( 'give_metabox_form_data_settings', array( $this, 'add_offline_donations_setting_tab' ), 0, 1 );

		// Maintain active tab query parameter after save.
		add_filter( 'redirect_post_location', array( $this, 'maintain_active_tab' ), 10, 2 );
	}

	/**
	 * Setup metabox related data.
	 *
	 * @since 1.8
	 *
	 * @return void
	 */
	function setup() {
		$this->settings = $this->get_settings();
	}


	/**
	 * Get metabox settings
	 *
	 * @since 1.8
	 *
	 * @return array
	 */
	function get_settings() {
		$post_id           = give_get_admin_post_id();
		$price_placeholder = give_format_decimal( '1.00', false, false );

		// Start with an underscore to hide fields from custom fields list
		$prefix = '_give_';

		$settings = include 'form-metabox-settings.php';

		/**
		 * Filter the metabox tabbed panel settings.
		 */
		$settings = apply_filters( 'give_metabox_form_data_settings', $settings, $post_id );

		// Output.
		return $settings;
	}

	/**
	 * Add metabox.
	 *
	 * @since 1.8
	 *
	 * @return void
	 */
	public function add_meta_box() {
		add_meta_box(
			$this->get_metabox_ID(),
			$this->get_metabox_label(),
			array( $this, 'output' ),
			array( 'give_forms' ),
			'normal',
			'high'
		);

		// Show Goal Metabox only if goal is enabled.
		if ( give_is_setting_enabled( give_get_meta( give_get_admin_post_id(), '_give_goal_option', true ) ) ) {
			add_meta_box(
				'give-form-goal-stats',
				__( 'Goal Statistics', 'give' ),
				array( $this, 'output_goal' ),
				array( 'give_forms' ),
				'side',
				'low'
			);
		}

	}


	/**
	 * Enqueue scripts.
	 *
	 * @since 1.8
	 *
	 * @return void
	 */
	function enqueue_script() {
		global $post;

		if ( is_object( $post ) && 'give_forms' === $post->post_type ) {

		}
	}

	/**
	 * Get metabox id.
	 *
	 * @since 1.8
	 *
	 * @return string
	 */
	function get_metabox_ID() {
		return $this->metabox_id;
	}

	/**
	 * Get metabox label.
	 *
	 * @since 1.8
	 *
	 * @return string
	 */
	function get_metabox_label() {
		return $this->metabox_label;
	}


	/**
	 * Get metabox tabs.
	 *
	 * @since 1.8
	 *
	 * @return array
	 */
	public function get_tabs() {
		$tabs = array();

		if ( ! empty( $this->settings ) ) {
			foreach ( $this->settings as $setting ) {
				if ( ! isset( $setting['id'] ) || ! isset( $setting['title'] ) ) {
					continue;
				}
				$tab = array(
					'id'        => $setting['id'],
					'label'     => $setting['title'],
					'icon-html' => ( ! empty( $setting['icon-html'] ) ? $setting['icon-html'] : '' ),
				);

				if ( $this->has_sub_tab( $setting ) ) {
					if ( empty( $setting['sub-fields'] ) ) {
						$tab = array();
					} else {
						foreach ( $setting['sub-fields'] as $sub_fields ) {
							$tab['sub-fields'][] = array(
								'id'        => $sub_fields['id'],
								'label'     => $sub_fields['title'],
								'icon-html' => ( ! empty( $sub_fields['icon-html'] ) ? $sub_fields['icon-html'] : '' ),
							);
						}
					}
				}

				if ( ! empty( $tab ) ) {
					$tabs[] = $tab;
				}
			}
		}

		return $tabs;
	}

	/**
	 * Output metabox settings.
	 *
	 * @since 1.8
	 *
	 * @return void
	 */
	public function output() {
		// Bailout.
		if ( $form_data_tabs = $this->get_tabs() ) :
			$active_tab = ! empty( $_GET['give_tab'] ) ? give_clean( $_GET['give_tab'] ) : 'form_field_options';
			wp_nonce_field( 'give_save_form_meta', 'give_form_meta_nonce' );
			?>
			<input id="give_form_active_tab" type="hidden" name="give_form_active_tab">
			<div class="give-metabox-panel-wrap">
				<ul class="give-form-data-tabs give-metabox-tabs">
					<?php foreach ( $form_data_tabs as $index => $form_data_tab ) : ?>
						<?php
						// Determine if current tab is active.
						$is_active = $active_tab === $form_data_tab['id'] ? true : false;
						?>
						<li class="<?php echo "{$form_data_tab['id']}_tab" . ( $is_active ? ' active' : '' ) . ( $this->has_sub_tab( $form_data_tab ) ? ' has-sub-fields' : '' ); ?>">
							<a href="#<?php echo $form_data_tab['id']; ?>"
							   data-tab-id="<?php echo $form_data_tab['id']; ?>">
								<?php if ( ! empty( $form_data_tab['icon-html'] ) ) : ?>
									<?php echo $form_data_tab['icon-html']; ?>
								<?php else : ?>
									<span class="give-icon give-icon-default"></span>
								<?php endif; ?>
								<span class="give-label"><?php echo $form_data_tab['label']; ?></span>
							</a>
							<?php if ( $this->has_sub_tab( $form_data_tab ) ) : ?>
								<ul class="give-metabox-sub-tabs give-hidden">
									<?php foreach ( $form_data_tab['sub-fields'] as $sub_tab ) : ?>
										<li class="<?php echo "{$sub_tab['id']}_tab"; ?>">
											<a href="#<?php echo $sub_tab['id']; ?>"
											   data-tab-id="<?php echo $sub_tab['id']; ?>">
												<?php if ( ! empty( $sub_tab['icon-html'] ) ) : ?>
													<?php echo $sub_tab['icon-html']; ?>
												<?php else : ?>
													<span class="give-icon give-icon-default"></span>
												<?php endif; ?>
												<span class="give-label"><?php echo $sub_tab['label']; ?></span>
											</a>
										</li>
									<?php endforeach; ?>
								</ul>
							<?php endif; ?>
						</li>
					<?php endforeach; ?>
				</ul>

				<?php foreach ( $this->settings as $setting ) : ?>
					<?php do_action( "give_before_{$setting['id']}_settings" ); ?>
					<?php
					// Determine if current panel is active.
					$is_active = $active_tab === $setting['id'] ? true : false;
					?>
					<div id="<?php echo $setting['id']; ?>"
						 class="panel give_options_panel<?php echo( $is_active ? ' active' : '' ); ?>">
						<?php if ( ! empty( $setting['fields'] ) ) : ?>
							<?php foreach ( $setting['fields'] as $field ) : ?>
								<?php give_render_field( $field ); ?>
							<?php endforeach; ?>
						<?php endif; ?>
					</div>
					<?php do_action( "give_after_{$setting['id']}_settings" ); ?>


					<?php if ( $this->has_sub_tab( $setting ) ) : ?>
						<?php if ( ! empty( $setting['sub-fields'] ) ) : ?>
							<?php foreach ( $setting['sub-fields'] as $index => $sub_fields ) : ?>
								<div id="<?php echo $sub_fields['id']; ?>" class="panel give_options_panel give-hidden">
									<?php if ( ! empty( $sub_fields['fields'] ) ) : ?>
										<?php foreach ( $sub_fields['fields'] as $sub_field ) : ?>
											<?php give_render_field( $sub_field ); ?>
										<?php endforeach; ?>
									<?php endif; ?>
								</div>
							<?php endforeach; ?>
						<?php endif; ?>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>
		<?php
		endif; // End if().
	}

	/**
	 * Output Goal meta-box settings.
	 *
	 * @param object $post Post Object.
	 *
	 * @access public
	 * @since  2.1.0
	 *
	 * @return void
	 */
	public function output_goal( $post ) {

		echo give_admin_form_goal_stats( $post->ID );

	}

	/**
	 * Check if setting field has sub tabs/fields
	 *
	 * @param array $field_setting Field Settings.
	 *
	 * @since 1.8
	 *
	 * @return bool
	 */
	private function has_sub_tab( $field_setting ) {
		$has_sub_tab = false;
		if ( array_key_exists( 'sub-fields', $field_setting ) ) {
			$has_sub_tab = true;
		}

		return $has_sub_tab;
	}

	/**
	 * CMB2 settings loader.
	 *
	 * @since 1.8
	 *
	 * @return array
	 */
	function cmb2_metabox_settings() {
		$all_cmb2_settings   = apply_filters( 'cmb2_meta_boxes', array() );
		$give_forms_settings = $all_cmb2_settings;

		// Filter settings: Use only give forms related settings.
		foreach ( $all_cmb2_settings as $index => $setting ) {
			if ( ! in_array( 'give_forms', $setting['object_types'] ) ) {
				unset( $give_forms_settings[ $index ] );
			}
		}

		return $give_forms_settings;

	}

	/**
	 * Check if we're saving, the trigger an action based on the post type.
	 *
	 * @param int        $post_id Post ID.
	 * @param WP_Post $post    Post Object.
	 *
	 * @since 1.8
	 *
	 * @return void
	 */
	public function handle_form_save( $post_id, $post ) {

		// $post_id and $post are required.
		if ( empty( $post_id ) || ! ( $post_id instanceof WP_Post ) ) {
			return;
		}

		// Don't save meta boxes for revisions or autosaves.
		if ( defined( 'DOING_AUTOSAVE' ) || is_int( wp_is_post_revision( $post ) ) || is_int( wp_is_post_autosave( $post ) ) ) {
			return;
		}

		// Check the nonce.
		if ( empty( $_POST['give_form_meta_nonce'] ) || ! wp_verify_nonce( $_POST['give_form_meta_nonce'], 'give_save_form_meta' ) ) {
			return;
		}

		// Check the post being saved == the $post_id to prevent triggering this call for other save_post events.
		if ( empty( $_POST['post_ID'] ) || $_POST['post_ID'] != $post_id ) {
			return;
		}

		// Check user has permission to edit.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		$this->save( $post, $_POST );
	}


	/**
	 * Save form setting
	 *
	 * @since 2.5.11
	 *
	 * @param WP_Post $form
	 * @param array $new_form_settings
	 */
	private function save( $form, $new_form_settings ){
		// Fire action before saving form meta.
		do_action( 'give_pre_process_give_forms_meta', $form->ID, $form );

		/**
		 * Filter the meta key to save.
		 * Third party addon developer can remove there meta keys from this array to handle saving data on there own.
		 */
		$form_meta_keys = apply_filters( 'give_process_form_meta_keys', $this->get_meta_keys_from_settings() );

		// Save form meta data.
		if ( ! empty( $form_meta_keys ) ) {
			foreach ( $form_meta_keys as $form_meta_key ) {

				// Set default value for checkbox fields.
				if (
					! isset( $new_form_settings[ $form_meta_key ] ) &&
					in_array( $this->get_field_type( $form_meta_key ), array( 'checkbox', 'chosen' ) )
				) {
					$new_form_settings[ $form_meta_key ] = '';
				}

				if ( isset( $new_form_settings[ $form_meta_key ] ) ) {
					$setting_field = $this->get_setting_field( $form_meta_key );
					if ( ! empty( $setting_field['type'] ) ) {
						switch ( $setting_field['type'] ) {
							case 'textarea':
							case 'wysiwyg':
								$form_meta_value = wp_kses_post( $new_form_settings[ $form_meta_key ] );
								break;

							case 'donation_limit' :
								$form_meta_value = $new_form_settings[ $form_meta_key ];
								break;

							case 'group':
								$form_meta_value = array();

								foreach ( $new_form_settings[ $form_meta_key ] as $index => $group ) {

									// Do not save template input field values.
									if ( '{{row-count-placeholder}}' === $index ) {
										continue;
									}

									$group_meta_value = array();
									foreach ( $group as $field_id => $field_value ) {
										switch ( $this->get_field_type( $field_id, $form_meta_key ) ) {
											case 'wysiwyg':
												$group_meta_value[ $field_id ] = wp_kses_post( $field_value );
												break;

											default:
												$group_meta_value[ $field_id ] = give_clean( $field_value );
										}
									}

									if ( ! empty( $group_meta_value ) ) {
										$form_meta_value[ $index ] = $group_meta_value;
									}
								}

								// Arrange repeater field keys in order.
								$form_meta_value = array_values( $form_meta_value );
								break;

							default:
								$form_meta_value = give_clean( $new_form_settings[ $form_meta_key ] );
						}// End switch().

						/**
						 * Filter the form meta value before saving
						 *
						 * @since 1.8.9
						 */
						$form_meta_value = apply_filters(
							'give_pre_save_form_meta_value',
							$this->sanitize_form_meta( $form_meta_value, $setting_field ),
							$form_meta_key,
							$this,
							$form->ID
						);

						// Range slider.
						if ( 'donation_limit' === $setting_field['type'] ) {

							// Sanitize amount for db.
							$form_meta_value = array_map( 'give_sanitize_amount_for_db', $form_meta_value );

							// Store it to form meta.
							give_update_meta( $form->ID, $form_meta_key . '_minimum', $form_meta_value['minimum'] );
							give_update_meta( $form->ID, $form_meta_key . '_maximum', $form_meta_value['maximum'] );
						} else {
							// Save data.
							give_update_meta( $form->ID, $form_meta_key, $form_meta_value );
						}

						// Verify and delete form meta based on the form status.
						give_set_form_closed_status( $form->ID );

						// Fire after saving form meta key.
						do_action( "give_save_{$form_meta_key}", $form_meta_key, $form_meta_value, $form->ID, $form );
					}// End if().
				}// End if().
			}// End foreach().
		}// End if().

		// Update the goal progress for donation form.
		give_update_goal_progress( $form->ID );

		// Fire action after saving form meta.
		do_action( 'give_post_process_give_forms_meta', $form->ID, $form );
	}


	/**
	 * Get field ID.
	 *
	 * @param array $field Array of Fields.
	 *
	 * @since 1.8
	 *
	 * @return string
	 */
	private function get_field_id( $field ) {
		$field_id = '';

		if ( array_key_exists( 'id', $field ) ) {
			$field_id = $field['id'];

		}

		return $field_id;
	}

	/**
	 * Get fields ID.
	 *
	 * @param array $setting Array of settings.
	 *
	 * @since 1.8
	 *
	 * @return array
	 */
	private function get_fields_id( $setting ) {
		$meta_keys = array();

		if (
			! empty( $setting )
			&& array_key_exists( 'fields', $setting )
			&& ! empty( $setting['fields'] )
		) {
			foreach ( $setting['fields'] as $field ) {
				if ( $field_id = $this->get_field_id( $field ) ) {
					$meta_keys[] = $field_id;
				}
			}
		}

		return $meta_keys;
	}

	/**
	 * Get sub fields ID.
	 *
	 * @param array $setting Array of settings.
	 *
	 * @since 1.8
	 *
	 * @return array
	 */
	private function get_sub_fields_id( $setting ) {
		$meta_keys = array();

		if ( $this->has_sub_tab( $setting ) && ! empty( $setting['sub-fields'] ) ) {
			foreach ( $setting['sub-fields'] as $fields ) {
				if ( ! empty( $fields['fields'] ) ) {
					foreach ( $fields['fields'] as $field ) {
						if ( $field_id = $this->get_field_id( $field ) ) {
							$meta_keys[] = $field_id;
						}
					}
				}
			}
		}

		return $meta_keys;
	}


	/**
	 * Get all setting field ids.
	 *
	 * @since 1.8
	 *
	 * @return array
	 */
	private function get_meta_keys_from_settings() {
		$meta_keys = array();

		foreach ( $this->settings as $setting ) {
			$meta_key = $this->get_fields_id( $setting );

			if ( $this->has_sub_tab( $setting ) ) {
				$meta_key = array_merge( $meta_key, $this->get_sub_fields_id( $setting ) );
			}

			$meta_keys = array_merge( $meta_keys, $meta_key );
		}

		return $meta_keys;
	}


	/**
	 * Get field type.
	 *
	 * @param string $field_id Field ID.
	 * @param string $group_id Field Group ID.
	 *
	 * @since 1.8
	 *
	 * @return string
	 */
	function get_field_type( $field_id, $group_id = '' ) {
		$field = $this->get_setting_field( $field_id, $group_id );

		$type = array_key_exists( 'type', $field )
			? $field['type']
			: '';

		return $type;
	}


	/**
	 * Get Field
	 *
	 * @param array  $setting  Settings array.
	 * @param string $field_id Field ID.
	 *
	 * @since 1.8
	 *
	 * @return array
	 */
	private function get_field( $setting, $field_id ) {
		$setting_field = array();

		if ( ! empty( $setting['fields'] ) ) {
			foreach ( $setting['fields'] as $field ) {
				if ( array_key_exists( 'id', $field ) && $field['id'] === $field_id ) {
					$setting_field = $field;
					break;
				}
			}
		}

		return $setting_field;
	}

	/**
	 * Get Sub Field
	 *
	 * @param array  $setting  Settings array.
	 * @param string $field_id Field ID.
	 *
	 * @since 1.8
	 *
	 * @return array
	 */
	private function get_sub_field( $setting, $field_id ) {
		$setting_field = array();

		if ( ! empty( $setting['sub-fields'] ) ) {
			foreach ( $setting['sub-fields'] as $fields ) {
				if ( $field = $this->get_field( $fields, $field_id ) ) {
					$setting_field = $field;
					break;
				}
			}
		}

		return $setting_field;
	}

	/**
	 * Get setting field.
	 *
	 * @param string $field_id Field ID.
	 * @param string $group_id Get sub field from group.
	 *
	 * @since 1.8
	 *
	 * @return array
	 */
	function get_setting_field( $field_id, $group_id = '' ) {
		$setting_field = array();

		$_field_id = $field_id;
		$field_id  = empty( $group_id ) ? $field_id : $group_id;

		if ( ! empty( $this->settings ) ) {
			foreach ( $this->settings as $setting ) {
				if (
					( $this->has_sub_tab( $setting ) && ( $setting_field = $this->get_sub_field( $setting, $field_id ) ) )
					|| ( $setting_field = $this->get_field( $setting, $field_id ) )
				) {
					break;
				}
			}
		}

		// Get field from group.
		if ( ! empty( $group_id ) ) {
			foreach ( $setting_field['fields'] as $field ) {
				if ( array_key_exists( 'id', $field ) && $field['id'] === $_field_id ) {
					$setting_field = $field;
				}
			}
		}

		return $setting_field;
	}


	/**
	 * Add offline donations setting tab to donation form options metabox.
	 *
	 * @param array $settings List of form settings.
	 *
	 * @since 1.8
	 *
	 * @return mixed
	 */
	function add_offline_donations_setting_tab( $settings ) {
		if ( give_is_gateway_active( 'offline' ) ) {
			$settings['offline_donations_options'] = apply_filters( 'give_forms_offline_donations_options', array(
				'id'        => 'offline_donations_options',
				'title'     => __( 'Offline Donations', 'give' ),
				'icon-html' => '<span class="give-icon give-icon-purse"></span>',
				'fields'    => apply_filters( 'give_forms_offline_donations_metabox_fields', array() ),
			) );
		}

		return $settings;
	}


	/**
	 * Sanitize form meta values before saving.
	 *
	 * @param mixed $meta_value    Meta Value for sanitizing before saving.
	 * @param array $setting_field Setting Field.
	 *
	 * @since  1.8.9
	 * @access public
	 *
	 * @return mixed
	 */
	function sanitize_form_meta( $meta_value, $setting_field ) {
		switch ( $setting_field['type'] ) {
			case 'group':
				if ( ! empty( $setting_field['fields'] ) ) {
					foreach ( $setting_field['fields'] as $field ) {
						if ( empty( $field['data_type'] ) || 'price' !== $field['data_type'] ) {
							continue;
						}

						foreach ( $meta_value as $index => $meta_data ) {
							if ( ! isset( $meta_value[ $index ][ $field['id'] ] ) ) {
								continue;
							}

							$meta_value[ $index ][ $field['id'] ] = ! empty( $meta_value[ $index ][ $field['id'] ] ) ?
								give_sanitize_amount_for_db( $meta_value[ $index ][ $field['id'] ] ) :
								( ( '_give_amount' === $field['id'] && empty( $field_value ) ) ?
									give_sanitize_amount_for_db( '1.00' ) :
									0 );
						}
					}
				}
				break;

			default:
				if ( ! empty( $setting_field['data_type'] ) && 'price' === $setting_field['data_type'] ) {
					$meta_value = $meta_value ?
						give_sanitize_amount_for_db( $meta_value ) :
						( in_array( $setting_field['id'], array(
							'_give_set_price',
							'_give_custom_amount_minimum',
							'_give_set_goal'
						) ) ?
							give_sanitize_amount_for_db( '1.00' ) :
							0 );
				}
		}

		return $meta_value;
	}

	/**
	 * Maintain the active tab after save.
	 *
	 * @param string $location The destination URL.
	 * @param int    $post_id  The post ID.
	 *
	 * @since  1.8.13
	 * @access public
	 *
	 * @return string The URL after redirect.
	 */
	public function maintain_active_tab( $location, $post_id ) {
		if (
			'give_forms' === get_post_type( $post_id ) &&
			! empty( $_POST['give_form_active_tab'] )
		) {
			$location = add_query_arg( 'give_tab', give_clean( $_POST['give_form_active_tab'] ), $location );
		}

		return $location;
	}
}

Give_MetaBox_Form_Data::get_instance()->init();

