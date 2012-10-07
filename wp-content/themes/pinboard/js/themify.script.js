if (navigator.userAgent.match(/iPhone/i)) {
	// Fix iPhone viewport scaling bug on orientation change
	// By @mathias, @cheeaun and @jdalton
	( function(doc) {

			var addEvent = 'addEventListener', type = 'gesturestart', qsa = 'querySelectorAll', scales = [1, 1], meta = qsa in doc ? doc[qsa]('meta[name=viewport]') : [];

			function fix() {
				meta.content = 'width=device-width,minimum-scale=' + scales[0] + ',maximum-scale=' + scales[1];
				doc.removeEventListener(type, fix, true);
			}

			if (( meta = meta[meta.length - 1]) && addEvent in doc) {
				fix();
				scales = [.25, 1.6];
				doc[addEvent](type, fix, true);
			}

		}(document));
}

/////////////////////////////////////////////
// Add :nth-of-type() selector to all browsers
/////////////////////////////////////////////
function getNthIndex(cur, dir) {
	var t = cur, idx = 0;
	while ( cur = cur[dir]) {
		if (t.tagName == cur.tagName) {
			idx++;
		}
	}
	return idx;
}

function isNthOf(elm, pattern, dir) {
	var position = getNthIndex(elm, dir), loop;
	if (pattern == "odd" || pattern == "even") {
		loop = 2;
		position -= !(pattern == "odd");
	} else {
		var nth = pattern.indexOf("n");
		if (nth > -1) {
			loop = parseInt(pattern, 10);
			position -= (parseInt(pattern.substring(nth + 1), 10) || 0) - 1;
		} else {
			loop = position + 1;
			position -= parseInt(pattern, 10) - 1;
		}
	}
	return (loop < 0 ? position <= 0 : position >= 0) && position % loop == 0
}

var pseudos = {
	"first-of-type" : function(elm) {
		return getNthIndex(elm, "previousSibling") == 0;
	},
	"last-of-type" : function(elm) {
		return getNthIndex(elm, "nextSibling") == 0;
	},
	"only-of-type" : function(elm) {
		return pseudos["first-of-type"](elm) && pseudos["last-of-type"](elm);
	},
	"nth-of-type" : function(elm, b, match, all) {
		return isNthOf(elm, match[3], "previousSibling");
	},
	"nth-last-of-type" : function(elm, i, match) {
		return isNthOf(elm, match[3], "nextSibling");
	}
}

/////////////////////////////////////////////
// Page Width Calculation
/////////////////////////////////////////////
var ItemBoard = {
	init : function(config) {
		this.config = config;
		this.bindEvents();
	},
	columns : 0,
	itemMargin : 20,
	itemPadding : 0,
	bindEvents : function() {
		var _self = this;
		jQuery(document).ready(function() {
			_self.elementSetup()
		});
		jQuery(window).resize(function() {
			_self.elementSetup()
		});
	},

	elementSetup : function() {
		var item = jQuery(this.config.itemElement), viewport_width = this.viewportWidth();

		this.itemWidthOuter = this.itemWidthInner() + this.itemMargin + this.itemPadding;
		this.columns = parseInt(viewport_width / this.itemWidthOuter);

		fixwidth = this.columns * this.itemWidthInner() + ((this.columns - 1) * this.itemMargin);
		maxWidth = '100%';

		// make exception for width smaller than 480px then dont apply the inline width
		// assume 480 = 1 column item and apply to only viewport <= 505
		if (this.columns <= 1 && viewport_width <= 505) {
			fixwidth = 978;
			maxWidth = '94%';
		}
		jQuery(this.config.appliedTo).each(function() {
			jQuery(this).css({
				'width' : fixwidth + 'px',
				'max-width' : maxWidth
			});
		});
	},

	itemWidthInner : function() {
		var innerwidth = jQuery(this.config.itemElement).width();
		return innerwidth;
	},

	viewportWidth : function() {
		return jQuery(window).width();
	}
};

jQuery(document).ready(function($) {

	$.extend($.expr[':'], pseudos);

	/////////////////////////////////////////////
	// Initialize prettyPhoto
	/////////////////////////////////////////////
	if (screen.width >= 480) {
		if ($("a[rel^='prettyPhoto']").length > 0)
			$("a[rel^='prettyPhoto']").prettyPhoto({
				social_tools : false,
				deeplinking : false,
				// To customize theme, use 'themify_overlay_gallery_theme' filter hook
				// Use light_rounded / pp_default / light_square / dark_rounded / dark_square / facebook
				theme : themifyScript.overlayTheme
		});
	}

	/////////////////////////////////////////////
	// HTML5 placeholder fallback
	/////////////////////////////////////////////
	$('[placeholder]').focus(function() {
		var input = $(this);
		if (input.val() == input.attr('placeholder')) {
			input.val('');
			input.removeClass('placeholder');
		}
	}).blur(function() {
		var input = $(this);
		if (input.val() == '' || input.val() == input.attr('placeholder')) {
			input.addClass('placeholder');
			input.val(input.attr('placeholder'));
		}
	}).blur();
	$('[placeholder]').parents('form').submit(function() {
		$(this).find('[placeholder]').each(function() {
			var input = $(this);
			if (input.val() == input.attr('placeholder')) {
				input.val('');
			}
		})
	});

	/////////////////////////////////////////////
	// Scroll to top
	/////////////////////////////////////////////
	$('.back-top a').click(function() {
		$('body,html').animate({
			scrollTop : 0
		}, 800);
		return false;
	});

	/////////////////////////////////////////////
	// Prepend zoom icon to prettyphoto
	/////////////////////////////////////////////
	$('.post-image .lightbox').prepend('<span class="zoom"></span>');

	/////////////////////////////////////////////
	// Toggle menu on mobile
	/////////////////////////////////////////////
	$("#menu-icon").click(function() {
		$("#headerwrap #main-nav").fadeToggle();
		$("#headerwrap #searchform").hide();
		$(this).toggleClass("active");
	});

	/////////////////////////////////////////////
	// Toggle searchform on mobile
	/////////////////////////////////////////////
	$("#search-icon").click(function() {
		$("#headerwrap #searchform").fadeToggle();
		$("#headerwrap #main-nav").hide();
		$('#headerwrap #s').focus();
		$(this).toggleClass("active");
	});

});

jQuery(window).load(function() {
	// auto width
	if( jQuery('#content .grid4, #content .grid3, #content .grid2').length > 0 ){
		ItemBoard.init({
			itemElement : '.AutoWidthElement .post',
			appliedTo   : '.pagewidth'
		});
	}

	var $container = jQuery('#content .grid4, #content .grid3, #content .grid2');

	// isotope init
	$container.isotope({
		itemSelector : '.post',
		transformsEnabled : false
	});
	jQuery(window).resize();
	
	// Get max pages for regular category pages and home
	var scrollMaxPages = themifyScript.maxPages;
	// Get max pages for Query Category pages
	if( typeof qp_max_pages != 'undefined')
		scrollMaxPages = qp_max_pages;

	// infinite scroll
	$container.infinitescroll({
		navSelector  : '#load-more a', 		// selector for the paged navigation
		nextSelector : '#load-more a', 		// selector for the NEXT link (to page 2)
		itemSelector : '#content .post', 	// selector for all items you'll retrieve
		loadingText  : ' ',
		donetext     : ' ',
		loadingImg 	 : themifyScript.loadingImg,
		errorCallback: function(){
			jQuery('#infscr-loading').delay(400).fadeOut(500);
		}
	}, function(newElements) {
		// call Isotope for new elements
		var $newElems = jQuery(newElements).wrap('<div class="new-items" />');
		$newElems.hide().imagesLoaded(function(){
			jQuery(this).show();
			jQuery('#infscr-loading').fadeOut('normal');
			$container.isotope('appended', $newElems );
			if (jQuery("a[rel^='prettyPhoto']", $newElems).length > 0){
				jQuery("a[rel^='prettyPhoto']", $newElems).prettyPhoto({
					social_tools : false,
					deeplinking : false,
					// To customize theme, use 'themify_overlay_gallery_theme' filter hook
					// Use light_rounded / pp_default / light_square / dark_square / facebook
					theme : themifyScript.overlayTheme
				});
				jQuery('.post-image .lightbox', $newElems).prepend('<span class="zoom"></span>');
			}
		});
		//$container.isotope('insert', jQuery(newElements)).delay(400).isotope('reLayout');
		scrollMaxPages = scrollMaxPages - 1;
		if( 1 < scrollMaxPages && 'auto' != themifyScript.autoInfinite)
			jQuery('#load-more a').show();
	});

	// disable auto infinite scroll based on user selection
	if( 'auto' != themifyScript.autoInfinite ){
		jQuery(window).unbind('.infscr');
	}
	if( 'auto' == themifyScript.autoInfinite ){
		jQuery('#load-more, #load-more a').hide();
	}

	jQuery('#load-more a').click(function() {
		jQuery(document).trigger('retrieve.infscr');
		return false;
	});

	// remove the paginator when we're done.
	jQuery(document).ajaxError(function(e, xhr, opt) {
		if (xhr.status == 404)
			jQuery('#load-more').remove();
	});
});

