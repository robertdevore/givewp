<?php

namespace Give\PaymentGateways\PayPalCheckout;

use Give\PaymentGateways\PaymentGateway;
use Give_Admin_Settings;

class PayPalCheckout implements PaymentGateway {
	/**
	 * @inheritDoc
	 */
	public function getId() {
		return 'paypal-checkout';
	}

	/**
	 * @inheritDoc
	 */
	public function getName() {
		return __( 'PayPal Checkout', 'give' );
	}

	/**
	 * @inheritDoc
	 */
	public function getPaymentMethodLabel() {
		return __( 'Credit Card', 'give' );
	}

	/**
	 * @inheritDoc
	 */
	public function getOptions() {
		return [
			[
				'type'       => 'title',
				'id'         => 'give_title_gateway_settings_2',
				'table_html' => false,
			],
			[
				'name' => __( 'Connect With Paypal', 'give' ),
				'id'   => 'paypal_checkout_account_manger',
				'type' => 'paypal_checkout_account_manger',
			],
			[
				'type'       => 'sectionend',
				'id'         => 'give_title_gateway_settings_2',
				'table_html' => false,
			],
		];
	}

	/**
	 * @inheritDoc
	 */
	public function boot() {
		add_action( 'admin_enqueue_scripts', [ $this, 'loadAdminScripts' ] );
	}

	/**
	 * Load admin scripts
	 *
	 * @since 2.8.0
	 */
	public function loadAdminScripts() {
		if ( Give_Admin_Settings::is_setting_page( 'gateway', 'paypal' ) ) {
			wp_enqueue_script(
				'paypal-partner-js',
				'https://www.sandbox.paypal.com/webapps/merchantboarding/js/lib/lightbox/partner.js',
				[],
				null
			);
		}
	}
}
