/**
 * Lemongrid Backend Script
 * Author: BEARS Themes
 * Author Url: http://themebears.com
 */

 ! ( function( $ ) {

 	$.fn.serializeObject = function() {
        var o = {};
        var a = this.serializeArray();
        $.each(a, function() {
            if (o[this.name]) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(this.value || '');
            } else {
                o[this.name] = this.value || '';
            }
        });
        return o;
    };

 	var lgBackend = {
 		widget: function() {
 			$( 'body' ).on( 'change', '[data-widget-switch-group]', function() {
 				var groupName = $( this ).val(),
 					content = $( this ).parents( '.widget-content' );

 				content.find( '.lg-group-field' ).each( function() {
 					var group = $( this ).data( 'group' );
 					
 					if( groupName == group )
 						$( this ).fadeIn( 'slow' )
 					else
 						$( this ).fadeOut( 0 )

 				} )

 			} )
 		},
 		loadparambytemplate: function() { 
 			$( 'html' ).on( 'change', '[data-loadparambytemplate-lg]', function() {
 				var $this = $( this ),
 					field_HTML = $this.find( 'option:selected' ).data( 'fieldhtml' );

 				$this.next( '.lg-params-container' ).html( field_HTML );
 			} )

 			/* */
 			$( '#vc_ui-panel-edit-element' ).ajaxComplete( function() {
 				var loadparambytemplateEl = $( this ).find( '[data-loadparambytemplate-lg]' ),
 					self = $( this );
 				
 				if( loadparambytemplateEl.length > 0 ) { 
 					loadparambytemplateEl.each( function() {
 						if( $( this ).data( 'loadparambytemplate-lg' ) != true ) {
 							/* */
 							$( this ).data( 'loadparambytemplate-lg', true ).trigger( 'change' );
 							
 							/* */
 							self.find( '[data-vc-ui-element="button-save"]' ).on( 'mouseenter', function() {
			 					var groupFieldContainer = self.find( '.lg-shortcode-supper-template' );
			 					if( groupFieldContainer.length <= 0 ) return;

			 					groupFieldContainer.each( function() {
			 						var $this = $( this ),
			 							jsonContentEl = $( this ).find( '[data-jsoncontent]' ),
			 							groupField = $this.find( '.lg-template-group-field' );

			 						var objParams = groupField.find('input[name],select[name],textarea[name]').serializeObject();
			 						
			 						jsonContentEl.val( JSON.stringify( objParams ) );
			 						
			 						console.log( jsonContentEl.val() );
			 					} )
			 				} )
 						}
 					} )
 				}
 			} )
 		}
 	}

 	/* DOM ready */
 	$( function() {

 		/* Use widget api */
 		new lgBackend.widget();
 		
 		/* Use loadparambytemplate api */
 		new lgBackend.loadparambytemplate();
 	} )
 } )( jQuery )