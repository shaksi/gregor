// Fix iPhone viewport scaling bug on orientation change
// By @mathias, @cheeaun and @jdalton
if(navigator.userAgent.match(/iPhone/i))
{
(function(doc) {

	var addEvent = 'addEventListener',
	    type = 'gesturestart',
	    qsa = 'querySelectorAll',
	    scales = [1, 1],
	    meta = qsa in doc ? doc[qsa]('meta[name=viewport]') : [];

	function fix() {
		meta.content = 'width=device-width,minimum-scale=' + scales[0] + ',maximum-scale=' + scales[1];
		doc.removeEventListener(type, fix, true);
	}

	if ((meta = meta[meta.length - 1]) && addEvent in doc) {
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
	while (cur = cur[dir] ) {
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
	return (loop<0 ? position<=0 : position >= 0) && position % loop == 0
}
var pseudos = {
	"first-of-type": function(elm) {
		return getNthIndex(elm, "previousSibling") == 0;
	},
	"last-of-type": function(elm) { 
		return getNthIndex(elm, "nextSibling") == 0;
	},
	"only-of-type": function(elm) { 
		return pseudos["first-of-type"](elm) && pseudos["last-of-type"](elm);
	},
	"nth-of-type": function(elm, b, match, all) {
		return isNthOf(elm, match[3], "previousSibling");
	},
	"nth-last-of-type": function(elm, i, match) {
		return isNthOf(elm, match[3], "nextSibling");
	}        
}


/////////////////////////////////////////////
// jQuery functions					
/////////////////////////////////////////////
jQuery(document).ready(function($){
	
	$.extend($.expr[':'], pseudos);
		
	/////////////////////////////////////////////
	// Initialize prettyPhoto					
	/////////////////////////////////////////////
	if (screen.width>=480 && ($("a[rel^='prettyPhoto']").length > 0) ) {
		$("a[rel^='prettyPhoto']").prettyPhoto({ social_tools: false, deeplinking: false });
	}

	/////////////////////////////////////////////
	// Set grid post clear							
	/////////////////////////////////////////////
	$(".loops-wrapper.grid4 .post:nth-of-type(4n+1), .loops-wrapper.grid4 .category-section .post:nth-of-type(4n+1)").css({"margin-left":"0"}).before("<div style='clear:both;'></div>");
	$(".loops-wrapper.grid3 .post:nth-of-type(3n+1), .loops-wrapper.grid3 .category-section .post:nth-of-type(3n+1)").css({"margin-left":"0"}).before("<div style='clear:both;'></div>");
	$(".loops-wrapper.grid2 .post:nth-of-type(2n+1), .loops-wrapper.grid2 .category-section .post:nth-of-type(2n+1)").css({"margin-left":"0"}).before("<div style='clear:both;'></div>");
	$(".loops-wrapper.grid2-thumb .post:nth-of-type(2n+1), .loops-wrapper.grid2-thumb .category-section .post:nth-of-type(2n+1)").css({"margin-left":"0"}).before("<div style='clear:both;'></div>");

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
	$('.back-top a').click(function () {
		$('body,html').animate({
			scrollTop: 0
		}, 800);
		return false;
	});

	/////////////////////////////////////////////
	// Prepend zoom icon to prettyphoto 							
	/////////////////////////////////////////////
	$('.post-image .lightbox').prepend('<span class="zoom"></span>');

	/////////////////////////////////////////////
	// Add image-wrap to images for styling 							
	/////////////////////////////////////////////
	$('.post-image img, #slider img, .gallery img, .pagewidth .avatar, .flickr_badge_image img, .attachment img, .feature-posts-list .post-img, img.alignleft, img.aligncenter, img.alignright, img.alignnone, .wp-caption img').each(function() {
		var imgClass = $(this).attr('class');
		$(this).wrap('<span class="image-wrap ' + imgClass + '" style="width: auto; height: auto;"/>');
		$(this).removeAttr('class');
	});

	/////////////////////////////////////////////
	// Photoswipe
	/////////////////////////////////////////////
	if( $(".gallery a").length > 0 ){
		$(".gallery a").photoSwipe({
			enableMouseWheel: true,
			autoStartSlideshow: false,
			slideshowDelay: 5000,
			slideSpeed: 700,
			fadeInSpeed: 500,
			fadeOutSpeed: 500
		});
	}
	/////////////////////////////////////////////
	// Toggle menu on mobile 							
	/////////////////////////////////////////////
	$("#menu-icon").click(function(){
		$("#headerwrap #main-nav").fadeToggle();
		$("#headerwrap #searchform").hide();
		$(this).toggleClass("active");
	});

	/////////////////////////////////////////////
	// Toggle searchform on mobile 							
	/////////////////////////////////////////////
	$("#search-icon").click(function(){
		$("#headerwrap #searchform").fadeToggle();
		$("#headerwrap #main-nav").hide();
		$('#headerwrap #s').focus();
		$(this).toggleClass("active");
	});
});