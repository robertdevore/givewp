// Thanks to https://codepen.io/k-ivan/pen/XPBdzM
console.clear();
const Toasti = ( () => {

	let toasti;
	let timer;

	const toastihtml = `
    <div class="toasti__body">
      #string#
    </div>
  `;

	const defaults = {
		position: 'top-center',
		modClass: '',
		hideByClick: true,
		delay: 5000
	};

	const transitionEnd = getTransitionEnd();

	function emulateTransitionEnd( el, duration = 250 ) {
		let called = false;
		const elem = el;
		const transitionEnd = getTransitionEnd();

		elem.addEventListener( transitionEnd, function handler( e ) {
			called = true;
			elem.removeEventListener( e.type, handler )
		} );
		const callback = function() {
			if ( !called ) {
				const event = document.createEvent( 'HTMLEvents' );
				event.initEvent( transitionEnd, true, false );
				elem.dispatchEvent( event );
			}
		};
		setTimeout( callback, duration );
	};

	function getTransitionEnd() {
		let transitions = {
			transition: 'transitionend',
			WebkitTransition: 'webkitTransitionEnd',
			MozTransition: 'mozTransitionEnd'
		};
		let root = document.documentElement;
		for ( let name in transitions ) {
			if ( root.style[ name ] !== undefined ) {
				return transitions[ name ];
			}
		}
		return false;
	}

	function show( options ) {
		toasti = document.getElementById( 'toasti' );

		if ( toasti ) {
			// return hide();
			// return hide(function () {
			// 	show(options);
			// });

			toasti.innerHTML += options.message;

			console.log( toasti.innerHTML );

			return;
		}

		const settings = Object.assign( {}, defaults, options );
		const classes = [ `toasti`, `toasti--${settings.position}` ]

		toasti = document.createElement( 'div' );
		toasti.id = 'toasti';
		if ( settings.modClass ) {
			classes.push( settings.modClass );
		}
		classes.forEach( cls => {
			toasti.classList.add( cls );
		} );

		toasti.innerHTML = toastihtml.replace( '#string#', settings.message );
		document.body.appendChild( toasti );

		setTimeout( () => {
			toasti.classList.add( 'toasti-enter' );
		}, 20 );

		if ( settings.delay ) {
			if ( timer ) clearTimeout( timer );
			timer = setTimeout( () => {
				hide()
			}, settings.delay )
		}

		if ( settings.hideByClick ) {
			toasti.addEventListener( 'click', hide );
		}

	}

	function hide( cb ) {
		if ( !toasti ) return;
		if ( timer ) clearTimeout( timer );
		toasti.classList.remove( 'toasti-enter' );
		toasti.removeEventListener( 'click', hide );

		toasti.addEventListener( transitionEnd, function handler( e ) {
			try {
				toasti.removeEventListener( e.type, handler );
				toasti.parentNode.removeChild( toasti );
			} catch ( e ) {
			}
			toasti = null;
			( typeof cb === 'function' ) && cb();
		} );
		emulateTransitionEnd( toasti, 250 );

	}

	return {
		show
	}

} )();

jQuery( window ).load( function() {
	document.querySelectorAll( '.give-notice.updated' ).forEach( function( notice ) {
		var clone = notice.cloneNode( true );
		clone.style.display = 'block';
		Toasti.show( {
			position: 'top-right',
			modClass: 'demo',
			message: clone.outerHTML,
			hideByClick: false,
			delay: 0
		} );
	} );

	// jQuery( document ).on( 'click', '.notice-dismiss', function() {
	// 	if ( !jQuery( this ).parents( '.toasti__body' ) ) {
	// 		return;
	// 	}
	// 	jQuery( this ).parent( '.give-notice' ).slideUp( 'normal', function() {
	// 		jQuery( this ).remove();
	// 	} );
	// } );

	// Place non-Toast notices in menu bar
	document.querySelectorAll( '.give-notice:not(.updated)' ).forEach( function( notice, index ) {
		var clone = notice.cloneNode( true );

		// Add notice count.
		jQuery( '.give-notification-count-value' ).html( index + 1 );

		// Clone notices to container.
		jQuery( '.give-notice-center-inner' ).append( clone );
	} );

	// Custom menu bar.
	jQuery( document ).on( 'click', '.give-notice-center .ab-item', function() {
		jQuery( '.give-notice-center-inner' ).toggle();
	} );


} );
