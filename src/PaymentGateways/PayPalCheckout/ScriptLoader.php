<?php

namespace Give\PaymentGateways\PayPalCheckout;

use Give_Admin_Settings;

/**
 * Class ScriptLoader
 * @package Give\PaymentGateways\PayPalCheckout
 *
 * @since 2.8.0
 */
class ScriptLoader {
	/**
	 * Setup hooks
	 *
	 * @since 2.8.0
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
			$nonce = wp_create_nonce( 'give_paypal_checkout_user_onboarded' );

			wp_enqueue_script(
				'paypal-partner-js',
				'https://www.sandbox.paypal.com/webapps/merchantboarding/js/lib/lightbox/partner.js',
				[],
				null,
				true
			);

			$script = <<<EOT
				(function(d, s, id) {
					var js, ref = d.getElementsByTagName(s)[0];
					if (!d.getElementById(id)) {
						js = d.createElement(s);
						js.id = id;
						js.async = true;
						js.src = "https://www.paypal.com/webapps/merchantboarding/js/lib/lightbox/partner.js";
						ref.parentNode.insertBefore(js, ref);
						}
				}(document, "script", "paypal-js"));
EOT;

			wp_add_inline_script(
				'paypal-partner-js',
				$script
			);
		}
	}
}
