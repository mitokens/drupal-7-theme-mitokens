
/*
** Passing '$' into ready()'s anonymous function aliases it with jQuery().
*/
jQuery(window).ready(function($) {
	"use strict";
	
	
	/* FUNCTION DEFENITIONS *****************************************************/
	
	/*
	** Collapse sibling <details> if the parent element has an "accordion" class.
	** If open is true, open this <details> element.
	** Then scroll the page to the top of this <details> element.
	*/
	function accordionShift(details, open) {
		open = (typeof open === 'undefined')? (true): (open);
		var wrapper = details.parent('.accordion');
		if (wrapper.length) {
			wrapper.children('details').removeAttr('open');
		}
		if (open) {
			details.attr('open', 'open');
		}
		if (wrapper.length) {
			var toolbarHeight = $('#toolbar').height();
			toolbarHeight = (toolbarHeight)? (toolbarHeight): (0);
			$('html, body').animate({
				scrollTop: (details.offset().top - toolbarHeight)
			}, 250);
		}
	}
	
	/*
	** Scrolls the window to a target, at a speed.
	*/
	function scrollToPoint(target, speed) {
		speed = parseInt(speed) || 0;
		if (typeof target == 'string') {
			target = parseInt($(target).offset().top || 0);
		}
		if (typeof target == 'object') {
			target = parseInt(target.offset().top || 0);
		}
		target = parseInt(target) || 0;
		$('html, body').animate({scrollTop: (target)}, speed);
	}
	
	
	/* PAGELOAD ACTIONS *********************************************************/
	
	window.setTimeout(function() {
		// remove hrefs from empty hyperlinks
		$('a[href=""], a[href="#"]').removeAttr('href');
	}, 10);
	
	
	/* ELEMENT LISTENERS ********************************************************/
	
	/*
	** Enact accessible dropdown menus.
	** https://www.w3.org/WAI/tutorials/menus/flyout
	*/
	var menuItems = document.querySelectorAll('li.has-submenu');
	Array.prototype.forEach.call(menuItems, function(el, i){
		var windowWidth = window.innerWidth ||
			document.documentElement.clientWidth ||
			document.body.clientWidth ||
			document.body.offsetWidth;
			el.querySelector('a').addEventListener('click',  function(event){
				if (this.parentNode.className.includes('has-submenu')) {
					console.log(this.parentNode.className);
					if (this.parentNode.className.includes('open')) {
						this.parentNode.className = this.parentNode.className.replace('open', '');
						this.setAttribute('aria-expanded', 'false');
					}
					else {
						this.parentNode.className += ' open';
						this.setAttribute('aria-expanded', 'true');
					}
				}
				event.preventDefault();
				return false;
			});
	});
	
	/*
	** Uses Modernizr feature detection.
	** Enable collapsible <details> and <summary>,
	** For browsers that don't support it natively.
	*/
	$('details > summary').click(function() {
			var details = $(this).parent();
			if (!Modernizr.details) {
				if (details.attr('open')) {
					details.removeAttr('open');
				}
				else {
					accordionShift(details);
				}
			}
			else if (!details.attr('open')) {
				accordionShift(details, false);
			}
	});
	
	
});
