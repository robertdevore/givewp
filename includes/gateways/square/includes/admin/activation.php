<?php
/**
 * Give Square Gateway Activation
 *
 * @package     Give
 * @sub-package Square Core
 * @copyright   Copyright (c) 2019, GiveWP
 * @license     https://opensource.org/licenses/gpl-license GNU Public License
 * @since       2.6.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Displays the "Give Square Connect" banner.
 *
 * @since 2.6.0
 *
 * @return bool
 */
function give_square_connect_maybe_show_banner() {

	// Don't show if already connected.
	if ( give_square_is_connected() ) {
		return false;
	}

	// Don't show if user wants to use their own API key.
	if ( give_square_is_manual_api_keys_enabled() ) {
		return false;
	}

	// Don't show if on the payment settings section.
	if ( 'square-settings' === give_get_current_setting_section() ) {
		return false;
	}

	// Don't show for non-admins.
	if ( ! current_user_can( 'update_plugins' ) ) {
		return false;
	}

	// Is the notice temporarily dismissed?
	if ( give_square_is_connect_notice_dismissed() ) {
		return false;
	}

	$connect_button = give_square_connect_button();

	$message = sprintf(
		/* translators: 1. Connect Bold Text, 2. Connect Intro Text, 3. Dismiss Text, 4. Connect button html */
		'<strong>%1$s</strong> %2$s %3$s',
		__( 'Square Connect:', 'give' ),
		__( 'You\'re almost ready to start accepting online donations.', 'give' ),
		$connect_button
	);

	Give()->notices->register_notice( array(
		'id'               => 'give-square-connect-banner',
		'description'      => $message,
		'type'             => 'warning',
		'dismissible_type' => 'user',
		'dismiss_interval' => 'shortly',
	) );

	return true;

}

add_action( 'admin_notices', 'give_square_connect_maybe_show_banner' );

/**
 * This function will show notice when business location is not set.
 *
 * @since 2.6.0
 *
 * @return void
 */
function give_square_business_location_notice() {

	if (
		'square-settings' !== give_get_current_setting_section() &&
		give_square_is_connected() &&
		false === give_square_get_location_id()
	) {
		echo sprintf(
			/* translators: 1. Connect Bold Text */
			'<div class="notice notice-error"><p>%1$s <a href="%2$s">%3$s</a></p></div>',
			esc_html__( 'Give - Square is almost ready. Please', 'give' ),
			esc_url_raw( admin_url() . 'edit.php?post_type=give_forms&page=give-settings&tab=gateways&section=square-settings' ),
			esc_html__( 'set your business location.', 'give' )
		);
	}
}

add_action( 'admin_notices', 'give_square_business_location_notice' );
