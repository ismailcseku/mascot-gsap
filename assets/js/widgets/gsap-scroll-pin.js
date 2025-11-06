/**
 * GSAP Scroll Pin Widget
 * Handles scroll-triggered pinned title animations with scale effects
 */
(function($) {
    'use strict';

    /**
     * Initialize GSAP Scroll Pin Widget
     */
    var GSAPScrollPin = {
        init: function() {
            var $elements = $('.gsap-scroll-pin-wrapper[data-gsap-scroll-pin]');

            if ($elements.length === 0) {
                return;
            }

            // Wait for GSAP and ScrollTrigger to be available
            if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') {
                console.error('GSAP or ScrollTrigger not loaded');
                return;
            }

            // Register ScrollTrigger plugin
            gsap.registerPlugin(ScrollTrigger);

            // Initialize each element
            $elements.each(function() {
                GSAPScrollPin.initElement($(this));
            });
        },

        initElement: function($element) {
            var data = $element.data('gsap-scroll-pin');

            if (!data) {
                return;
            }

            // Parse data attributes
            var config = {
                enableScale: data['enable-scale'] === 'true' || data['enable-scale'] === true,
                initialScale: parseFloat(data['initial-scale']) || 0.6,
                finalScale: parseFloat(data['final-scale']) || 1,
                duration: parseFloat(data['duration']) || 4,
                holdDuration: parseFloat(data['hold-duration']) || 0,
                holdDelay: parseFloat(data['hold-delay']) || 1,
                triggerStart: data['trigger-start'] || 'top center-=350',
                triggerEnd: data['trigger-end'] || 'bottom 150%',
                scrub: parseFloat(data['scrub']),
                markers: data['markers'] === 'true' || data['markers'] === true,
                pinSpacing: data['pin-spacing'] === 'true' || data['pin-spacing'] === true
            };

            // If scrub is 0, set to false
            if (config.scrub === 0) {
                config.scrub = false;
            }

            var $title = $element.find('.scroll-pin-title');

            if ($title.length === 0) {
                return;
            }

            // Create the animation timeline
            var timeline = gsap.timeline({
                scrollTrigger: {
                    trigger: $element[0],
                    start: config.triggerStart,
                    end: config.triggerEnd,
                    pin: true,
                    pinSpacing: config.pinSpacing,
                    scrub: config.scrub,
                    markers: config.markers,
                    anticipatePin: 1,
                    onEnter: function() {
                        $element.addClass('gsap-scroll-pin-active');
                    },
                    onLeave: function() {
                        $element.removeClass('gsap-scroll-pin-active');
                    },
                    onEnterBack: function() {
                        $element.addClass('gsap-scroll-pin-active');
                    },
                    onLeaveBack: function() {
                        $element.removeClass('gsap-scroll-pin-active');
                    }
                }
            });

            // Add scale animation if enabled
            if (config.enableScale) {
                // Set initial scale
                gsap.set($title[0], {
                    scale: config.initialScale,
                    transformOrigin: 'center center'
                });

                // Scale animation
                timeline.to($title[0], {
                    scale: config.finalScale,
                    duration: config.duration,
                    ease: 'power2.out'
                });

                // Hold at final scale
                if (config.holdDuration > 0) {
                    timeline.to($title[0], {
                        scale: config.finalScale,
                        duration: config.holdDuration,
                        delay: config.holdDelay
                    });
                }
            }

            // Store timeline reference for potential cleanup
            $element.data('gsap-timeline', timeline);
        },

        destroy: function($element) {
            var timeline = $element.data('gsap-timeline');
            if (timeline) {
                timeline.kill();
                $element.removeData('gsap-timeline');
            }
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        GSAPScrollPin.init();
    });

    // Re-initialize on Elementor preview
    if (typeof elementorFrontend !== 'undefined') {
        elementorFrontend.hooks.addAction('frontend/element_ready/mascot-gsap-scroll-pin.default', function($scope) {
            var $element = $scope.find('.gsap-scroll-pin-wrapper[data-gsap-scroll-pin]');
            if ($element.length) {
                // Destroy existing timeline if any
                GSAPScrollPin.destroy($element);
                // Initialize new timeline
                GSAPScrollPin.initElement($element);
            }
        });
    }

    // Expose to global scope
    window.MascotGSAPScrollPin = GSAPScrollPin;

})(jQuery);

