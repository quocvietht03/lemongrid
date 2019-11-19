! ( function( $ ) {
	'use strict';

	/**
	 * Dynamics animates
	 */
	function lgAnimFaceIn( $elems, $opts ) {
		/**
		 * Check $elems exist
		 */
		if( $elems.length <= 0 ) 
			return;

		var _opts = $.extend( {
			frequency	: 300,
			friction 	: 435,
			duration 	: 1000,
			delay 		: 100,
		}, $opts );

		$elems.each( function( e ) {
			var index = $( this ).index();

			dynamics.css( this, { opacity: 0, translateY: 40 } )

			dynamics.animate( this, {
		      	opacity: 1, translateY: 0
		    }, {
		      	type 		: dynamics.spring, 
		      	frequency	: _opts.frequency, 
		      	friction 	: _opts.friction, 
		      	duration 	: _opts.duration, 
		      	delay 		: _opts.delay + index * 100
		    } )
		} )
	}

	function lgAnimtranslate( $elems, $opts ) {
		/**
		 * Check $elems exist
		 */
		if( $elems.length <= 0 ) 
			return;

		var _opts = $.extend( {
			frequency	: 0,
			friction 	: 243,
			duration 	: 731,
			delay 		: 100,
		}, $opts );

		$elems.each( function( e ) {
			var index = $( this ).index();

			dynamics.animate( this, {
			    translateY: 0, opacity: 1,
		  	}, {
			    type 		: dynamics.spring, 
			    duration 	: _opts.duration, 
			    friction 	: _opts.friction, 
			    delay 		: _opts.delay + index * 100
		  	} )
		} )
	}

	/**
	 * Lemongrid Animation
	 */
	var lemonGridAnimation = {
		'flipFront': function( elem, opts ) {
			var _opts = $.extend( {
				duration	: 1035, 
				bounciness	: 423, 
				elasticity	: 212,
				delay 		: 50,
				callback	: function() { return; }
			}, opts );

			dynamics.animate( elem, {
			    rotateY: 0,
		  	}, {
			    type		: dynamics.gravity, 
			    duration	: _opts.duration, 
			    bounciness	: _opts.bounciness, 
			    elasticity	: _opts.elasticity, 
			    delay 		: _opts.delay + $( elem ).index() * 100,
			    complete	: function() {
			    	_opts.callback.call( this, elem );
			    }
		  	} )
		},
		'flipBehin': function( elem, opts ) {
			var _opts = $.extend( {
				duration	: 1035, 
				bounciness	: 423, 
				elasticity	: 212,
				delay 		: 50,
				callback	: function() { return; }
			}, opts );

			dynamics.animate( elem, {
			    rotateY: 180,
		  	}, {
			    type		: dynamics.gravity, 
			    duration	: _opts.duration, 
			    bounciness	: _opts.bounciness, 
			    elasticity	: _opts.elasticity, 
			    delay 		: _opts.delay + $( elem ).index() * 100,
			    complete	: function() {
			    	_opts.callback.call( this, elem );
			    }
		  	} )
		},
		'scaleIn': function( elem, opts ) {
			var _opts = $.extend( {
				frequency	: 0,
				friction 	: 243,
				duration 	: 1031,
				delay 		: 50,
				callback	: function() { return; }
			}, opts );

			dynamics.animate( elem, {
			    scale: 1,
		  	}, {
			    type		: dynamics.spring, 
			    friction	: _opts.friction, 
			    duration	: _opts.duration, 
			    delay 		: _opts.delay + $( elem ).index() * 100,
			    complete	: function() {
			    	_opts.callback.call( this, elem );
			    }
		  	} )
		},
		'scaleOut': function( elem, opts ) {
			var _opts = $.extend( {
				frequency	: 0,
				friction 	: 243,
				duration 	: 1031,
				delay 		: 50,
				callback	: function() { return; }
			}, opts );

			dynamics.animate( elem, {
			    scale: 0.5,
		  	}, {
			    type		: dynamics.spring, 
			    friction	: _opts.friction, 
			    duration	: _opts.duration, 
			    delay 		: _opts.delay + $( elem ).index() * 100,
			    complete	: function() {
			    	_opts.callback.call( this, elem );
			    }
		  	} )
		},
		'flipScaleIn': function( elem, opts ) {
			var _opts = $.extend( {
				duration	: 1035, 
				bounciness	: 423, 
				elasticity	: 212,
				delay 		: 50,
				callback	: function() { return; }
			}, opts );

			dynamics.animate( elem, {
			    rotateY: 0,
			    scale: 1,
		  	}, {
			    type		: dynamics.gravity, 
			    duration	: _opts.duration, 
			    bounciness	: _opts.bounciness, 
			    elasticity	: _opts.elasticity, 
			    delay 		: _opts.delay + $( elem ).index() * 100,
			    complete	: function() {
			    	_opts.callback.call( this, elem );
			    }
		  	} )
		},
		'flipScaleOut': function( elem, opts ) {
			var _opts = $.extend( {
				duration	: 1035, 
				bounciness	: 423, 
				elasticity	: 212,
				delay 		: 50,
				callback	: function() { return; }
			}, opts );

			dynamics.animate( elem, {
			    rotateY: 180,
			    scale: 0.5,
		  	}, {
			    type		: dynamics.gravity, 
			    duration	: _opts.duration, 
			    bounciness	: _opts.bounciness, 
			    elasticity	: _opts.elasticity, 
			    delay 		: _opts.delay + $( elem ).index() * 100,
			    complete	: function() {
			    	_opts.callback.call( this, elem );
			    }
		  	} )
		}
	};

	/**
	 * Confirm
	 */
	$.lgConfirm = function( opts, callback ) {
		var content = $( '<div>', {
			class: 'lg-modal-wrap',
			html: '<div class=\'lg-modal-inner\'><p class=\'lg-modal-title lg-dynamics-anim\'>'+ opts.title +'</p><div class=\'lg-modal-body lg-dynamics-anim\'>'+ opts.content +'</div><button class=\'lg-modal-ok lg-dynamics-anim\'>'+ opts.btnText +'</button></div>',
		} );

		/**
		 * Confirm success
		 */
		content.find( '.lg-modal-ok' ).on( 'click', function() {
			callback.call( this, content );
			content.remove();
		} )

		/**
		 * Close Confirm
		 */
		$( content ).on( 'click', function( e ) {
			if( $( e.target ).hasClass( 'lg-modal-wrap' ) )
				content.remove();
		} )

		$( 'body' ).append( content );

		lgAnimFaceIn( content.find( '.lg-dynamics-anim' ) );

		return content;
	}
	
	/**
	 * DynamicsModal
	 */
	$.lgDynamicsModalPhoto = function( opts, callback ) {
		this.opts = opts;
		this.content = $( '<div>', {
			class: 'lg-dynamics-modal-wrap',
			html: '<a href=\'#\' class=\'lg-dynamics-modal-close\'><i class=\'ion-ios-close-empty\'></i></a><div class=\'lg-dynamics-modal-inner\'><div class=\'lg-dynamics-modal-image\'><img src=\''+ opts.photo +'\'/></div><div class=\'lg-dynamics-modal-detail\'>'+ opts.detail +'</div></div>',
		} );

		var self = this;
		this.content.find( 'a.lg-dynamics-modal-close' ).on( 'click', function( e ) {
			e.preventDefault();
			self.close();
		} )

		this.content.on( 'close', function() {
			self.close();
		} )

		$( 'body' ).append( this.content );

		/**
		 * Animate
		 */
		this.modalInner = this.content.find( '.lg-dynamics-modal-inner' );
		this.imgWrap 	= this.content.find( '.lg-dynamics-modal-image' );
		this.detailWrap = this.content.find( '.lg-dynamics-modal-detail' );
		this.closeBtn 	= this.content.find( '.lg-dynamics-modal-close' );

		dynamics.animate( this.content[0], {
		    opacity: 1,
	  	}, {
		    type: dynamics.spring, duration: 1500, frequency: 1, friction: 250
	  	} )

		lgAnimtranslate( self.closeBtn, { duration: 500, delay: 1000 } );

		setTimeout( function() {
			lgAnimtranslate( self.imgWrap );
		}, 200 )

		if( self.detailWrap.find( '.lg-animate-fadein' ).length > 0 ) {
			setTimeout( function() {
				lgAnimFaceIn( self.detailWrap.find( '.lg-animate-fadein' ) );
			}, 300 )
		}

		/**
		 * Check exist callback
		 */
		if( callback )
			callback.call( this, this );

		return this;
	}

	$.lgDynamicsModalPhoto.prototype.close = function() {
		var self = this;
		dynamics.animate( self.imgWrap[0], {
		    translateY 	: 500,
		    opacity 	: 0,
	  	}, {
		    type: dynamics.spring, duration: 1110, frequency: 619, friction: 660, anticipationSize: 268, anticipationStrength: 356
	  	} )

	  	setTimeout( function() {
	  		dynamics.animate( self.content[0], {
			    opacity: 0,
		  	}, {
			    type: dynamics.spring, duration: 1110, frequency: 619, friction: 660, anticipationSize: 268, anticipationStrength: 356,
			    complete: function() {
			    	self.content.remove();
			    }
		  	} )
	  	}, 0 )
	}

	/**
	 * General func
	 */
	function getLemonGridSize( elem ) {
		var result = [];

		/**
		 * Check elem exist
		 */
		if( elem.length <= 0 )
			return;

		elem.each( function( e ) {
	        var node = $( this ).data('_gridstack_node');
	        result.push( {
	        	x: node.x,
	            y: node.y,
	            w: node.width,
	            h: node.height
	        } )
		} )

		return result;
	}

	function lgSaveLemonGrid( data ) {
		console.log( data );
	}

	/**
	 * lemonGrid
	 */

	function lemonGrid( elem, opts ) {
		this.lemonGridItems = $( elem );
		this._init();
	}

	lemonGrid.prototype._opts = function() {

	}


	lemonGrid.prototype._toolbarHandle = function( item ) {
		var toolbarElem = item.find( '.lemongrid-toolbar' );

		/**
		 * Check toolbar exist
		 */
		if( toolbarElem.length <= 0 ) return;

		var self = this,
			saveLayoutElem = toolbarElem.find( '.lg-toolbar-icon--save-layout' ),
			saveAsLayoutElem = toolbarElem.find( '.lg-toolbar-icon--save-as-layout' );

		/**
		 * Save layout
		 */
		$( saveLayoutElem, 'body' ).on( 'click', function( e ) {
			e.preventDefault();
			var lemonGridMap = getLemonGridSize( item.find( '.grid-stack > .grid-stack-item:visible' ) ),
				grid_elementid = $( this ).data( 'grid-elementid' ),
				page_id = $( this ).data( 'pageid' );

			self._request( 'lgApplyLemonGrid', { elemID: grid_elementid, name: grid_elementid, gridMap: lemonGridMap }, lgSaveLemonGrid );
			// self._request( 'lgSaveLayoutLemonGrid', { pageID: page_id,  elemID: grid_elementid, gridMap: lemonGridMap }, lgSaveLemonGrid );
		} )

		/**
		 * Save as layout
		 */
		$( saveAsLayoutElem, 'body' ).on( 'click', function( e ) {
			e.preventDefault();
			var lemonGridMap = getLemonGridSize( item.find( '.grid-stack > .grid-stack-item:visible' ) );

			$.lgConfirm( { 
				title: 'Save as grid layout', 
				content: '<input type=\'text\' id=\'lg-grid-name\' placeholder=\'layout name\'>', 
				btnText: 'Add' }, 
				function( content ) {
					var grid_name = content.find( '#lg-grid-name' ).val();
					self._request( 'lgApplyLemonGrid', { name: grid_name, gridMap: lemonGridMap }, lgSaveLemonGrid );
				} )
		} );
	}

	lemonGrid.prototype._request = function( task, data, callback ) {
		/**
		 * Set action
		 */
		data.action = task;
		var wrapElement = $( '.lemon_grid_id_' + data.elemID );
		wrapElement.addClass( 'lg-handle-ajax' );

		/**
		 * Ajax handle
		 */
		$.ajax( {
			type: 'POST',
			url: lemongridObj.ajaxurl,
			data: data,
			success: function( result ) {
				wrapElement.removeClass( 'lg-handle-ajax' );
				callback.call( this, result );
			}
		} )
	}

	/**
	 * Animate fadeIn
	 */
	lemonGrid.prototype._scrollAnimateFadeIn = function( lemonItem ) {
		
		if( lemonItem.find( '.lg-animate-fadein' ).length > 0 ) {
			$( window ).load( function() {
				var scrollTop = 0,
					winHeight = 0,
					elemInfo = {
						top 	: lemonItem.offset().top,
						height 	: lemonItem.height(),
					};

				$( window ).scroll( function() {
					scrollTop = $( this ).scrollTop();
					winHeight = $( this ).height();

					if( ( scrollTop + winHeight ) >= ( elemInfo.top - 100 ) ) {
						lgAnimFaceIn( lemonItem.find( '.lg-animate-fadein' ) );
						lemonItem.find( '.lg-animate-fadein' ).removeClass( 'lg-animate-fadein' );
					}
				} ).trigger( 'scroll' )
			} )
		}
	}

	lemonGrid.prototype._init = function() {
		/**
		 * Check lemonGridItems exist
		 */
		if( this.lemonGridItems.length <= 0 ) return;

		var self = this;
		this.lemonGridItems.each( function() {
			var lemonItem = $( this ),
				gridStack = lemonItem.find( '.grid-stack' ),
				options = gridStack.data( 'lemongrid-options' );

			/**
			 * Disables widgets moving/resizing.
			 */
			if( ! lemongridObj.gridBuilder ) {
				options.static_grid = true;
			}

			gridStack.gridstack( options );

			gridStack.on('resize', function (event, ui) {
			    var grid = this;
			    var element = event.target;
			});

			self._scrollAnimateFadeIn( lemonItem );
			self._toolbarHandle( lemonItem );
		} )
	}

	/**
	 * get next / prev 
	 */
	function lgGetSiblings( selector, index ) {
		var prevElem = $( selector, 'body' ).eq( index - 1 ),
			nextElem = $( selector, 'body' ).eq( index + 1 );

		return { next: nextElem, prev: prevElem };
	}

	/**
	 * next prev handle modal
	 */
	function handleNextPrevModal( selector, index, callback ) {

		var content = $( '<div>', {
				class: 'lg-next-prev-wrap',
				html: '<a href=\'#\' class=\'lg-btn-prev\' title=\'prev\'><i class=\'ion-ios-arrow-thin-left\'></i></a><a href=\'#\' class=\'lg-btn-next\' title=\'next\'><i class=\'ion-ios-arrow-thin-right\'></i></a>',
			} );

		var prevEl = content.find( '.lg-btn-prev' ),
			nextEl = content.find( '.lg-btn-next' );

		prevEl.on( 'click', function( e ) {
			e.preventDefault();

			var siblings = lgGetSiblings( selector, index ),
				$elem = siblings.prev;

			callback.call( this, $elem, index - 1,'prev' );
		} )

		nextEl.on( 'click', function( e ) {
			e.preventDefault();

			var siblings = lgGetSiblings( selector, index ),
				$elem = siblings.next;

			callback.call( this, $elem, index + 1, 'next' );
		} )

		return content;
	}

	/**
	 * Call modal Instagram
	 */
	function instagramCallModal( $elem, index, direct ) {
		var data = $elem.data( 'instagram' ),
			next_prev_wrap = handleNextPrevModal( '[data-instagram]', index, instagramCallModal );

		if( $( '.lg-dynamics-modal-wrap' ).length > 0 ) {
			$( '.lg-dynamics-modal-wrap' ).trigger( 'close' );
		}

		if( $elem.length <= 0 ) return;

		var params = { photo: data.photo, detail: data.detail_modal };

		/**
		 * Check video exist
		 */
		if( data.video ) params.video = data.video;

		var modal = new $.lgDynamicsModalPhoto( params, instagramAfterModalOpen );

		modal.content.find( '.lg-dynamics-modal-detail' ).prepend( next_prev_wrap );

		return modal;
	}

	/**
	 * instagramAfterModalOpen
	 */
	function instagramAfterModalOpen( modal ) {
		if( ! modal.opts.video ) return;

		var video = $( '<video>', {
				class: 'lg-video lg-ins-video',
				controls: true,
				html: '<source src="'+ modal.opts.video +'" type="video/mp4" />',
			} )

		modal.imgWrap.append( video );
	}

	/**
	 * Call modal flickr
	 */
	function flickrCallModal( $elem, index, direct ) {
		var data = $elem.data( 'flickr' ),
			next_prev_wrap = handleNextPrevModal( '[data-flickr]', index, flickrCallModal );

		if( $( '.lg-dynamics-modal-wrap' ).length > 0 ) {
			$( '.lg-dynamics-modal-wrap' ).trigger( 'close' );
		}

		if( $elem.length <= 0 ) return;

		var modal = new $.lgDynamicsModalPhoto( {
			photo: data.photo,
			detail: data.detail_modal,
		} );

		modal.content.find( '.lg-dynamics-modal-detail' ).prepend( next_prev_wrap );

		/**
		 * Update info
		 */
		$.ajax( {
			type: 'POST',
			url: lemongridObj.ajaxurl,
			data: { action: 'lgUpdateInfoFlickr', data: data },
			success: function( result ) {
				var obj = JSON.parse( result );

				/**
				 * Check description exist
				 */
				var des_str = '';
				if( obj.photo.description._content )
					des_str = obj.photo.description._content;

				var $description = $( '<p>', { class: 'lg-after-animate', html: des_str } );
				modal.content.find( '.description' ).html( $description );

				/**
				 * icon
				 */
				var favorite = obj.photo.isfavorite, comment = obj.photo.comments._content, date = obj.photo.dateuploaded;
				var $favorite = $( '<span>', { class: 'icon-likes lg-after-animate', html: '<i class=\'ion-android-favorite\'></i>' + favorite } );
				var $comment = $( '<span>', { class: 'icon-comments lg-after-animate', html: '<i class=\'ion-android-textsms\'></i>' + comment } );
				var $date = $( '<span>', { class: 'icon-time lg-after-animate', html: '<i class=\'ion-ios-clock\'></i>' + date } );
				modal.content.find( '.icon-wrap' ).html( $favorite ).append( $comment ).append( $date );

				lgAnimFaceIn( modal.content.find( '.lg-after-animate' ) );
			}
		} )

		return modal;
	}

	/**
	 * Lemongrid layout
	 */
	var lemonGridLayout = function() {
		this.init();
	}

	lemonGridLayout.prototype = {
		init: function() {
			var self = this,
				$elems = $( '.lemongrid-wrap[data-lemongrid-layout]' );

			$elems.each( function() {
				var layout = $( this ).data( 'lemongrid-layout' ),
					opts = $( this ).data( 'lemongrid-layout-opts' );

				switch( layout ) {
					case 'filter': self.lemonFilter( this, opts ); break;
				}
			} )
		},
		lemonFilter: function( el, opts ) {
			var self = this,
				$this = $( el );

			$this.on( {
				'filter': function( e, filterData ) { 
					$this.find( '.lemongrid-item' ).each( function() {
						var filterStr = $( this ).data( 'lemonfilter' ),
							filterArr = filterStr.split( ',' );
						
						if( $.inArray( filterData, filterArr ) >= 0 ) {
							/* filter in */
							switch( opts['filter-animate'] ) {
								case 'scale': 
									lemonGridAnimation.scaleIn( this, { callback: function( elem ) {
										$( elem )
										.removeClass( 'lg-filter-in lg-filter-out' )
										.addClass( 'lg-filter-in' );
									} } );
									break;

								case 'flip-scale':
									lemonGridAnimation.flipScaleIn( this, { callback: function( elem ) {
										$( elem )
										.removeClass( 'lg-filter-in lg-filter-out' )
										.addClass( 'lg-filter-in' );
									} } );
									break;

								default: /* Flip */
									lemonGridAnimation.flipFront( this, { callback: function( elem ) {
										$( elem )
										.removeClass( 'lg-filter-in lg-filter-out' )
										.addClass( 'lg-filter-in' );
									} } );
									break;
							}
						}else {
							/* filter out */
							switch( opts['filter-animate'] ) {
								case 'scale': 
									lemonGridAnimation.scaleOut( this, { callback: function( elem ) {
										$( elem )
										.removeClass( 'lg-filter-in lg-filter-out' )
										.addClass( 'lg-filter-out' );
									} } );
									break;

								case 'flip-scale':
									lemonGridAnimation.flipScaleOut( this, { callback: function( elem ) {
										$( elem )
										.removeClass( 'lg-filter-in lg-filter-out' )
										.addClass( 'lg-filter-out' );
									} } );
									break;

								default: /* Flip */
									lemonGridAnimation.flipBehin( this, { callback: function( elem ) {
										$( elem )
										.removeClass( 'lg-filter-in lg-filter-out' )
										.addClass( 'lg-filter-out' );
									} } );
									break;
							}
						}
					} )
				}
			} )

			$this.on( 'click', 'a[data-lemonfiltertitle]', function( e ) {
				e.preventDefault();

				$( this )
				.parent()
				.addClass( 'lg-filter-current' )
				.siblings()
				.removeClass( 'lg-filter-current' );

				var filterData = $( this ).data( 'lemonfiltertitle' );
				$this.trigger( 'filter', [filterData] );
			} )
		}
	};

	/**
	 * DOM load complete
	 */
	$( function() {
		/**
		 * Use lemonGrid
		 */
		new lemonGrid( '.lemongrid--element' );

		/**
		 * call lemonGridLayout
		 */
		new lemonGridLayout();

		/**
		 * Social Modal Instagram
		 */
		$( 'body' ).on( 'click', 'a[data-instagram]', function( e ) {
			e.preventDefault();

			var $thisEl = $( this ),
				index = $( '[data-instagram]', 'body' ).index( this );

			var modal = instagramCallModal( $thisEl, index );
		} )

		/**
		 * Social Modal Instagram
		 */
		$( 'body' ).on( 'click', 'a[data-flickr]', function( e ) {
			e.preventDefault();

			var $thisEl = $( this ),
				index = $( '[data-flickr]', 'body' ).index( this );

			var modal = flickrCallModal( $thisEl, index );
		} )

		/**
		 * Image Lightbox
		 */
		var imagelightboxEl = $( '[data-imagelightbox]' ),
		activityIndicatorOn = function() {
			$( '<div id="imagelightbox-loading"><div></div></div>' ).appendTo( 'body' );
		},
		activityIndicatorOff = function() {
			$( '#imagelightbox-loading' ).remove();
		},
		overlayOn = function() {
			$( '<div id="imagelightbox-overlay"></div>' ).appendTo( 'body' );
		},
		overlayOff = function() {
			$( '#imagelightbox-overlay' ).remove();
		};

		if( imagelightboxEl.length > 0 ) {
			imagelightboxEl.imageLightbox( {
				onStart: 	 function() { overlayOn(); },
				onEnd:	 	 function() { overlayOff(); activityIndicatorOff(); },
				onLoadStart: function() { activityIndicatorOn(); },
				onLoadEnd:	 function() { activityIndicatorOff(); }
			} );
		}
	} )
} )( jQuery )