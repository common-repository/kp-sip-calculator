jQuery(function($) {

	//display correct section based on URL anchor
	var url_hash = $.trim( window.location.hash );
	if ( url_hash ) {
		$( "#kp-sip-calculator-menu > a.active" ).removeClass( "active" );
		var selectedNav = $( '#kp-sip-calculator-menu > a[href="' + url_hash + '"]' );
		$( "#kp-sip-calculator-options-form" ).attr( "data-kp-sip-calculator-option", selectedNav.attr( "rel" ).split("-")[0] );
		$( selectedNav ).addClass( "active" );
		var activeSection = $( "#kp-sip-calculator-options .section-content.active" );
		activeSection.removeClass( "active" );
		$( "#" + selectedNav.attr( "rel" ) ).addClass( "active" );
	}

	//tab-content display
	$( '#kp-sip-calculator-menu > a' ).click( function( e ) {

		$( '.kp-sip-calculator-button-message' ).hide();
					
		var active_tab = $( this ).closest( '#kp-sip-calculator-menu' ).find( 'a.active' );
		var selected = $( this ).attr( 'rel' );

		active_tab.removeClass( 'active' );
		$( '#' + active_tab.attr( 'rel' ) ).removeClass( 'active' );
					
		$( this ).addClass( 'active' );
		$( '#' + selected ).addClass( 'active' );

		$( '#kp-sip-calculator-options-form' ).attr( 'data-kp-sip-calculator-option', selected.split('-')[0] );		
	});	

    //tooltip display
	$( ".kp-sip-calculator-tooltip" ).hover( function() {
	    $( this ).closest( "tr" ).find( ".kp-sip-calculator-tooltip-text" ).fadeIn( 100 );
	},function() {
	    $( this ).closest( "tr" ).find( ".kp-sip-calculator-tooltip-text" ).fadeOut( 100 );
	});

	//input display control
	$( '.kp-sip-calculator-input-controller input, .kp-sip-calculator-input-controller select' ).change( function() {

		var controller = $( this );

		var inputID = $( this ).attr( 'id' );

		var nestedControllers = [];

		$( '.' + inputID ).each( function() {

			var skipFlag = true;
			var forceHide = false;
			var forceShow = false;
			var optionSelected = false;

			if( $( this ).hasClass( 'kp-sip-calculator-input-controller' ) ) {
				nestedControllers.push( $( this ).find( 'input, select' ).attr( 'id' ) );
			}

			var currentInputContainer = this;

			$.each( nestedControllers, function( index, value ) {
				var currentController = $( '#' + value );

				if( currentController.is( 'input' ) ) {

					var controlChecked = $( '#' + value ).is(':checked');
					var controlReverse = $( '#' + value ).closest( '.kp-sip-calculator-input-controller' ).hasClass( 'kp-sip-calculator-input-controller-reverse' );

		  			if( $( currentInputContainer ).hasClass( value ) && ( controlChecked == controlReverse ) ) {
		  				skipFlag = false;
		  				return false;
		  			}
		  		} else if( currentController.is( 'select' ) ) {
		  			var classNames = currentInputContainer.className.match(/kp-sip-calculator-select-control-([^\s]*)/g);

		  			if( classNames ) {
						var foundClass = ( $.inArray( 'kp-sip-calculator-select-control-' + $( '#' + value ).val(), classNames ) ) >= 0;
						if( !foundClass ) {
							forceHide = true;
						}
					}
		  		}
			});

			if( controller.is( 'select' ) ) {
				var classNames = this.className.match(/kp-sip-calculator-select-control-([^\s]*)/g);
				var foundClass = ( $.inArray( 'kp-sip-calculator-select-control-' + controller.val(), classNames ) ) >= 0;

				if( classNames && ( foundClass != $( this ).hasClass( 'kp-sip-calculator-control-reverse' ) ) ) {
					forceShow = true;
				} else {
					forceHide = true;
				}
			}

			if( skipFlag ) {
				if( ( $( this ).hasClass( 'hidden' ) || forceShow ) && !forceHide ) {
					$( this ).removeClass( 'hidden' );
				} else {
					$( this ).addClass( 'hidden' );
				}
			}

		});
	});

	//validate input
	$( "#kp-sip-calculator-admin [kp_sip_calculator_validate]" ).keypress( function( e ) {

		//grab input and pattern
		var code = e.which;
		var character = String.fromCharCode( code );
		var pattern = $( this ).attr( 'kp_sip_calculator_validate' );

		//prevent input if character is invalid
		if( !character.match( pattern ) ) {
			e.preventDefault();
		}
	});	

	var kpActionButtonTimeouts = [];

	//action button press
	$( 'button[data-kp-sip-calculator-action]' ).click(function( e ) {

		e.preventDefault();

		//confirmation dialog
		var confirmation = $( this ).attr( 'data-kp-sip-calculator-confirmation' );
		if( confirmation && !confirm( confirmation ) ) {
			return;
		}

		//assign variables
		var action = $( this ).attr( 'data-kp-sip-calculator-action' );
		var button = $( this );
		var container = $( this ).closest( '.kp-sip-calculator-button-container' );
		var text = container.find( '.kp-sip-calculator-button-text' );
		var spinner = container.find( '.kp-sip-calculator-button-spinner' );
		var message = container.find( '.kp-sip-calculator-button-message' );

		//reset message
		message.html( '' );
		message.removeClass( 'kp-sip-calculator-error' );
	 	clearTimeout( kpActionButtonTimeouts[action] );

	 	//switch to spinner
	    $( this ).attr( 'disabled', true );
	    text.hide();
	    spinner.css( 'display', 'block' );

	    //setup form data
	    var formData = new FormData();
	    formData.append( 'action', 'kp_sip_calculator_' + action );
	    formData.append( 'nonce', KPSIPCALCULATORSETTINGS.nonce );

	    //additional setup
	    if( action == 'import_settings' ) {
    		formData.append( 'kp_sip_calculator_import_settings_file', document.getElementById( 'kp-sip-calculator-import-settings-file' ).files[0]);
	    } else {
	    	var form = $( this ).closest( 'form' );
			var formSerilizeArray = form.serializeArray();
			$.map( formSerilizeArray, function( data, i ) {
				formData.append( data['name'], data['value'] );
			});
	    }

	    //ajax request
		$.ajax({
	        type: "POST",
	        url: KPSIPCALCULATORSETTINGS.ajaxurl,
	        data: formData,
	        processData: false,
       		contentType: false
	    })
	    .done( function( r ) {

	    	//add message error class
	    	if( !r.success ) {
	    		message.addClass( 'kp-sip-calculator-error' );
	    	}

	    	//export settings
	    	if( action == 'export_settings' && r.data.export ) {
	    		var blob = new Blob([r.data.export], {
			        type: 'application/json'
		      	});
			    var link = document.createElement( 'a' );
			    link.href = window.URL.createObjectURL( blob );

			    var d = new Date();
				var month = d.getMonth()+1;
				var day = d.getDate();
				var dateString = d.getFullYear() + '-' + (month<10 ? '0' : '') + month + '-' + (day<10 ? '0' : '') + day;

			    link.download = 'kp-sip-calculator-settings-export-' + dateString + '.json';
			    link.click();
	    	}
		})
		.fail( function( r ) {
			message.addClass( 'kp-sip-calculator-error' );
			message.html( KPSIPCALCULATORSETTINGS.strings.failed );
		})
		.always( function( r ) {
			
			//show response message
			if( r.data && r.data.message ) {
				message.html( r.data.message );
			}
			message.fadeIn();
			clearTimeout( kpActionButtonTimeouts[action] );
			kpActionButtonTimeouts[action] = setTimeout( function() {
				message.fadeOut();
			}, 2500);

			//re-enable button
			button.attr( 'disabled', false );
			text.show();
	       	spinner.css( 'display', 'none' );
	       	
	       	//reload page
	       	if( r.data && r.data.reload ) {
	       		location.reload();
	       	}
		})
	});
});