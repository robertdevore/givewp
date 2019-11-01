<?php
/**
 * Give Square Gateway Filters
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
 * This function will remove ZIP as a required field on processing donation.
 *
 * @param array $required_fields List of required fields.
 *
 * @since 2.6.0
 *
 * @return mixed
 */
function give_square_remove_zip_required_fields( $required_fields ) {

	unset( $required_fields['card_zip'] );

	return $required_fields;

}

add_filter( 'give_donation_form_required_fields', 'give_square_remove_zip_required_fields' );
