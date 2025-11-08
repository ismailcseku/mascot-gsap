(function ($) {
	'use strict';

	var initGsapImageGallery = function () {
		if (typeof window.gsap === 'undefined' || typeof window.ScrollTrigger === 'undefined') {
			return;
		}

		window.gsap.registerPlugin(window.ScrollTrigger);

		$('.gallery-area.mascot-gsap-image-gallery').each(function () {
			var $gallery = $(this);
			var $mainImage = $gallery.find('.gallery-thumb img');

			if (!$mainImage.length) {
				return;
			}

			var breakpoint = parseInt($gallery.attr('data-breakpoint'), 10) || 1200;
			var mediaQuery = window.matchMedia('(min-width: ' + breakpoint + 'px)');

			var getAttr = function (name, defaultValue) {
				var value = $gallery.attr('data-' + name);
				return typeof value !== 'undefined' ? value : defaultValue;
			};

			var timelineOptions = {
				start: getAttr('trigger-start', 'top 30%'),
				end: getAttr('trigger-end', 'bottom 100%'),
				pin: getAttr('pin', 'true') === 'true',
				pinSpacing: getAttr('pin-spacing', 'false') === 'true',
				markers: getAttr('markers', 'false') === 'true',
				scrub: parseFloat(getAttr('scrub', 1)),
				duration: parseFloat(getAttr('duration', 3)),
				finalSize: parseFloat(getAttr('final-size', 580))
			};

			var destroyTimeline = function () {
				var existingTimeline = $gallery.data('gsapImageGalleryTimeline');
				if (existingTimeline) {
					if (existingTimeline.scrollTrigger) {
						existingTimeline.scrollTrigger.kill();
					}
					existingTimeline.kill();
					$gallery.removeData('gsapImageGalleryTimeline');
				}
			};

			var createTimeline = function () {
				destroyTimeline();

				if (!mediaQuery.matches) {
					window.gsap.set($mainImage, { clearProps: 'width,height' });
					return;
				}

				var tl = window.gsap.timeline({
					scrollTrigger: {
						trigger: $gallery.get(0),
						start: timelineOptions.start,
						end: timelineOptions.end,
						pin: timelineOptions.pin,
						pinSpacing: timelineOptions.pinSpacing,
						markers: timelineOptions.markers,
						scrub: isNaN(timelineOptions.scrub) ? 1 : timelineOptions.scrub
					}
				});

				tl.to($mainImage, {
					width: timelineOptions.finalSize + 'px',
					height: timelineOptions.finalSize + 'px',
					duration: isNaN(timelineOptions.duration) ? 3 : timelineOptions.duration,
					ease: 'none'
				});

				$gallery.data('gsapImageGalleryTimeline', tl);
			};

			createTimeline();

			if (mediaQuery.addEventListener) {
				mediaQuery.addEventListener('change', createTimeline);
			} else if (mediaQuery.addListener) {
				mediaQuery.addListener(createTimeline);
			}
		});
	};

	$(document).ready(initGsapImageGallery);

	$(window).on('elementor/frontend/init', function () {
		if (typeof window.elementorFrontend !== 'undefined') {
			window.elementorFrontend.hooks.addAction('frontend/element_ready/mascot-gsap-image-gallery.default', initGsapImageGallery);
		}
	});
})(jQuery);

