// Load JS when DOM is loaded successfully.
document.addEventListener( 'DOMContentLoaded', event => {

	const formWraps = document.querySelectorAll( '.give-form-wrap' );
	let give_square = {};

	// Loop through the number of forms on the page.
	Array.prototype.forEach.call( formWraps, function( formWrap ) {

		let hiddenBtn      = null;
		const form_element = formWrap.querySelector( '.give-form' );
		const formIdPrefix = form_element.querySelector( 'input[name="give-form-id-prefix"]' ).value;

		if ( null !== form_element.querySelector( '.give-btn-modal' ) ) {
			hiddenBtn = form_element.querySelector( '.give-btn-modal' );
		} else if ( null !== form_element.querySelector( '.give-btn-reveal' ) ) {
			hiddenBtn = form_element.querySelector( '.give-btn-reveal' );
		}

		give_square  = new Give_Square( form_element, hiddenBtn, event );

		// Mount and Un-Mount CC Fields on gateway load.
		jQuery( document ).on( 'give_gateway_loaded', function( event, xhr, settings ) {

			if ( form_element.querySelector( '.give-gateway-option-selected .give-gateway' ).value === 'square' ) {

				give_square.destroy( form_element );

				give_square.create_payment_form( form_element );

				give_square.build( form_element );

			}

		} );

		if ( null !== hiddenBtn ) {
			hiddenBtn.addEventListener( 'click', () => {

				if ( form_element.querySelector( '.give-gateway:checked' ).value === 'square' ) {
					const hiddenBtnPromise = new Promise( function( resolve, reject ) {
						setTimeout( function() {
							resolve( form_element.querySelector( '.give-modal-open' ) );
						}, 300 );
					} );

					hiddenBtnPromise.then( function( value ) {
						give_square.destroy( form_element );
						give_square.create_payment_form( form_element );
						give_square.build( form_element );
						give_square.recalculate( form_element );
					} );
				}
			} );
		}

	});

	// Process Donation using Square on form submission.
	jQuery( 'body' ).on( 'submit', '.give-form', function( event ) {

		const $form     = jQuery( this );
		const $idPrefix = $form.find( 'input[name="give-form-id-prefix"]' ).val();

		if ( 'square' === $form.find( 'input.give-gateway:checked' ).val() ) {
			give_square.paymentForm[ $idPrefix ].requestCardNonce();
			event.preventDefault();
		}

	});
});

class Give_Square {

	/**
	 * Constructor to initialize the Give_Square class.
	 *
	 * @since 1.0.0
	 *
	 * @param {object} form_element Form Element.
	 * @param {object} hiddenBtn    Support for hidden CC fields.
	 * @param {object} event        Event object.
	 */
	constructor( form_element, hiddenBtn, event ) {
		this.defaultGateway = '';
		this.paymentForm    = [];
		this.event        = event;
		this.formID       = form_element.querySelector( 'input[name="give-form-id"]' ).value;
		this.submitButton = form_element.querySelector( '#give-purchase-button' );

		if ( null !== form_element.querySelector( '.give-gateway-option-selected .give-gateway' ).value ) {
			this.defaultGateway = form_element.querySelector( '.give-gateway-option-selected .give-gateway' ).value;
		}

		// Load SQPaymentForm only if the default gateway is Square.
		if ( 'square' === this.defaultGateway && null === hiddenBtn ) {
			this.create_payment_form( form_element );
			this.build( form_element );
		}

	}

	/**
	 * This function creates a new payment form.
	 *
	 * @since 1.0.0
	 */
	create_payment_form( form_element ) {

		const formIDPrefix = form_element.querySelector( 'input[name="give-form-id-prefix"]' ).value;

		// Create and initialize payment form object.
		this.paymentForm[ formIDPrefix ] = new SqPaymentForm( {

			// Initialize the payment form elements.
			applicationId: giveSquareLocaliseVars.applicationID,
			locationId: giveSquareLocaliseVars.locationID,
			inputClass: 'give-square-cc-fields',
			autoBuild: false,

			// Customize the CSS of iFrame elements.
			inputStyles: [
				giveSquareLocaliseVars.inputStyles
			],

			// Initialize Apple Pay.
			applePay: false,

			// Initialize MasterPass.
			masterpass: false,

			// Initialize the CC placeholders.
			cardNumber: {
				elementId: 'give-card-number-field-' + formIDPrefix,
				placeholder: giveSquareLocaliseVars.cardNumberPlaceholder
			},
			cvv: {
				elementId: 'give-card-cvc-field-' + formIDPrefix,
				placeholder: giveSquareLocaliseVars.cvcPlaceholder
			},
			expirationDate: {
				elementId: 'give-card-expiration-field-' + formIDPrefix,
				placeholder: giveSquareLocaliseVars.cardExpiryPlaceholder
			},
			postalCode: {
				elementId: 'give-square-card-zip-' + formIDPrefix,
				placeholder: giveSquareLocaliseVars.postalCodePlaceholder
			},

			// Callback functions.
			callbacks: {

				// Trigger Events related to CC Input Fields.
				inputEventReceived: ( inputEvent ) => {

					switch ( inputEvent.eventType ) {
						case 'cardBrandChanged':

							let cardBrandClass = inputEvent.cardBrand.toLowerCase();
							if ('discoverdiners' === inputEvent.cardBrand.toLowerCase()) {
								cardBrandClass = 'dinersclub';
							} else if ('americanexpress' === inputEvent.cardBrand.toLowerCase()) {
								cardBrandClass = 'amex';
							}

							form_element.querySelector('.card-type').className = `card-type ${cardBrandClass}`;

							break;
						case 'postalCodeChanged':
							form_element.querySelector( '#give-square-card-zip-hidden-' + formIDPrefix ).value = inputEvent.postalCodeValue;
							break;
					}
				},

				// Trigger Events when successful paymentForm request received.
				cardNonceResponseReceived: ( errors, nonce, cardData ) => {

					if ( null !== errors && errors.length > 0 ) {

						let error_message = '';

						// Log nonce generation errors to console.
						errors.forEach( function( error ) {
							error_message += '<div class="give_errors"><p class="give_error">' + error.message + '</p></div>';
						} );

						// Display all errors on the form.
						form_element.querySelector( '#give-square-payment-errors-' + formIDPrefix ).innerHTML = error_message;

						// Reset Donate Button.
						if ( give_global_vars.complete_purchase ) {
							this.submitButton.value = give_global_vars.complete_purchase;
						} else {
							this.submitButton.value = this.submitButton.getAttribute( 'data-before-validation-label' );
						}

						this.submitButton.removeAttribute( 'disabled' );

						// Hide the loading animation.
						form_element.querySelector( '.give-submit-button-wrap' )
							.querySelector( '.give-loading-animation' ).style.display = 'none';

					} else {

						// Assign the nonce value to the hidden form field.
						form_element.querySelector( `#card-nonce-${formIDPrefix}` ).value = nonce;

						form_element.submit();
					}

				},

				// Trigger event when unsupported browser detected.
				unsupportedBrowserDetected: () => {
					alert( giveSquareLocaliseVars.unsupportedBrowserText );

				},
			}
		});

	}

	/**
	 * This function will load/build the payment form once object is created.
	 *
	 * @since 1.0.0
	 */
	build( form_element ) {

		const formIDPrefix = form_element.querySelector( 'input[name="give-form-id-prefix"]' ).value;

		// Build New Payment Form.
		this.paymentForm[ formIDPrefix ].build();
	}

	/**
	 * This function will destroy existing payment form including its object.
	 *
	 * @since 1.0.0
	 */
	destroy( form_element ) {

		const formIDPrefix = form_element.querySelector( 'input[name="give-form-id-prefix"]' ).value;

		// Destroy Existing Payment Form, if exists.
		if ( 'undefined' !== typeof this.paymentForm[ formIDPrefix ] ) {
			this.paymentForm[ formIDPrefix ].destroy();
		}
	}

	/**
	 * This function will recalculate size of existing payment form when hidden.
	 *
	 * @since 1.0.0
	 */
	recalculate( form_element ) {

		const formIDPrefix = form_element.querySelector( 'input[name="give-form-id-prefix"]' ).value;

		// Destroy Existing Payment Form, if exists.
		if ( 'undefined' !== typeof this.paymentForm[ formIDPrefix ] ) {
			this.paymentForm[ formIDPrefix ].recalculateSize();
		}
	}

}
