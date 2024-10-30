(function($){

	"use strict";

	function ff_joinus_grid_resize( $grid, width ) {

			$grid.find('.ff-joinus-grid-item').css( {
				'width' : width
			});

	}

	var $grids = $('.ff-joinus-grid');

	$('.ff-joinus-button').on( 'click', function() {
		var $button = $(this),
		$container = $button.parents('.ff-joinus-action'),
		btnText = $button.data('text'),
		btnActiveText = $button.data('active-text');

		$container.toggleClass('open').find('.ff-joinus-join-social-links').css('margin-top', '-' + $button.outerHeight() + 'px' );

		if( $container.hasClass('open') ) {
			$button.text( btnActiveText );
		} else {
			$button.text( btnText );
		}

		return false;
	});

	if( $grids.length ) {

		$grids.each( function() {

			var $grid = $(this),
			colWidth = $grid.data( 'col-width'),
			gutter = parseInt( $grid.data( 'margins') ),
			halfGutter = parseInt( gutter / 2 );

			$grid.find('.ff-joinus-grid-item').css( {
				'padding' : halfGutter + 'px',
				'width' : colWidth
			});

			$grid.css({
				'margin-left' : '-' + halfGutter + 'px',
				'margin-right' : '-' + halfGutter + 'px',
			})

			$grid.waitForImages(function() {

				$(this).masonry({
					itemSelector: '.ff-joinus-grid-item',
					gutter: 0,
					percentPosition: /%$/.test( colWidth ) == true,
					columnWidth: '.ff-joinus-grid-item'
				});

			});

		});

	}

	$(window).resize( function() {

		var screenWidth = $(window).width();

		if( screenWidth <= 480 ) {

			$grids.each( function() {
				var $grid = $(this),
				colWidth = $grid.data('col-width-small');
				ff_joinus_grid_resize( $grid, colWidth );
			});

		} else if( screenWidth <= 995 ) {

			$grids.each( function() {
				var $grid = $(this),
				colWidth = $grid.data('col-width-medium');
				ff_joinus_grid_resize( $grid, colWidth );
			});

		} else {

			$grids.each( function() {
				var $grid = $(this),
				colWidth = $grid.data('col-width');
				ff_joinus_grid_resize( $grid, colWidth );
			});

		}

	});

})( window.jQuery );
