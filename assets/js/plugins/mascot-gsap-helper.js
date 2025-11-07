/**
 * Mascot GSAP Helper
 * Utility functions for GSAP animations
 */
(function($) {
    'use strict';

    window.MascotGSAP = window.MascotGSAP || {};

    /**
     * Initialize GSAP animations
     */
    MascotGSAP.init = function() {
        console.log('Mascot GSAP Helper Initialized');
    };

    /**
     * Fade In Animation
     * @param {string|HTMLElement} element - Element selector or DOM element
     * @param {object} options - Animation options
     */
    MascotGSAP.fadeIn = function(element, options) {
        var defaults = {
            duration: 1,
            opacity: 1,
            y: 0,
            ease: 'power2.out'
        };
        var settings = $.extend({}, defaults, options);

        gsap.from(element, {
            opacity: 0,
            y: settings.y || 30,
            duration: settings.duration,
            ease: settings.ease,
            stagger: settings.stagger || 0
        });
    };

    /**
     * Fade In on Scroll (using ScrollTrigger)
     * @param {string|HTMLElement} element - Element selector or DOM element
     * @param {object} options - Animation options
     */
    MascotGSAP.fadeInScroll = function(element, options) {
        if (typeof ScrollTrigger === 'undefined') {
            console.warn('ScrollTrigger plugin not loaded');
            return;
        }

        var defaults = {
            duration: 1,
            opacity: 1,
            y: 50,
            ease: 'power2.out',
            trigger: element,
            start: 'top 80%',
            toggleActions: 'play none none none'
        };
        var settings = $.extend({}, defaults, options);

        gsap.from(element, {
            opacity: 0,
            y: settings.y,
            duration: settings.duration,
            ease: settings.ease,
            stagger: settings.stagger || 0,
            scrollTrigger: {
                trigger: settings.trigger,
                start: settings.start,
                toggleActions: settings.toggleActions,
                markers: settings.markers || false
            }
        });
    };

    /**
     * Slide In Animation
     * @param {string|HTMLElement} element - Element selector or DOM element
     * @param {string} direction - Direction: 'left', 'right', 'top', 'bottom'
     * @param {object} options - Animation options
     */
    MascotGSAP.slideIn = function(element, direction, options) {
        var defaults = {
            duration: 1,
            ease: 'power2.out'
        };
        var settings = $.extend({}, defaults, options);

        var fromVars = { opacity: 0, duration: settings.duration, ease: settings.ease };

        switch(direction) {
            case 'left':
                fromVars.x = -100;
                break;
            case 'right':
                fromVars.x = 100;
                break;
            case 'top':
                fromVars.y = -100;
                break;
            case 'bottom':
                fromVars.y = 100;
                break;
        }

        gsap.from(element, fromVars);
    };

    /**
     * Scale Animation
     * @param {string|HTMLElement} element - Element selector or DOM element
     * @param {object} options - Animation options
     */
    MascotGSAP.scaleIn = function(element, options) {
        var defaults = {
            duration: 1,
            scale: 0,
            ease: 'back.out(1.7)'
        };
        var settings = $.extend({}, defaults, options);

        gsap.from(element, {
            scale: settings.scale,
            duration: settings.duration,
            ease: settings.ease,
            stagger: settings.stagger || 0
        });
    };

    /**
     * Stagger Animation
     * @param {string|HTMLElement} elements - Elements selector or NodeList
     * @param {object} options - Animation options
     */
    MascotGSAP.stagger = function(elements, options) {
        var defaults = {
            duration: 1,
            y: 30,
            opacity: 0,
            stagger: 0.2,
            ease: 'power2.out'
        };
        var settings = $.extend({}, defaults, options);

        gsap.from(elements, settings);
    };

    /**
     * Parallax Effect
     * @param {string|HTMLElement} element - Element selector or DOM element
     * @param {object} options - Animation options
     */
    MascotGSAP.parallax = function(element, options) {
        if (typeof ScrollTrigger === 'undefined') {
            console.warn('ScrollTrigger plugin not loaded');
            return;
        }

        var defaults = {
            yPercent: -50,
            ease: 'none'
        };
        var settings = $.extend({}, defaults, options);

        gsap.to(element, {
            yPercent: settings.yPercent,
            ease: settings.ease,
            scrollTrigger: {
                trigger: element,
                start: 'top bottom',
                end: 'bottom top',
                scrub: settings.scrub || 1,
                markers: settings.markers || false
            }
        });
    };

    /**
     * Text Split Animation
     * @param {string|HTMLElement} element - Element selector or DOM element
     * @param {object} options - Animation options
     */
    MascotGSAP.splitText = function(element, options) {
        var defaults = {
            duration: 0.5,
            stagger: 0.05,
            ease: 'power2.out'
        };
        var settings = $.extend({}, defaults, options);

        var $element = $(element);
        var text = $element.text();
        var chars = text.split('');

        $element.empty();
        $.each(chars, function(i, char) {
            $element.append('<span class="char">' + (char === ' ' ? '&nbsp;' : char) + '</span>');
        });

        gsap.from($element.find('.char'), {
            opacity: 0,
            y: 20,
            duration: settings.duration,
            stagger: settings.stagger,
            ease: settings.ease
        });
    };

    /**
     * Counter Animation
     * @param {string|HTMLElement} element - Element selector or DOM element
     * @param {number} endValue - End value for counter
     * @param {object} options - Animation options
     */
    MascotGSAP.counter = function(element, endValue, options) {
        var defaults = {
            duration: 2,
            ease: 'power1.out',
            startValue: 0
        };
        var settings = $.extend({}, defaults, options);

        var obj = { value: settings.startValue };

        gsap.to(obj, {
            value: endValue,
            duration: settings.duration,
            ease: settings.ease,
            onUpdate: function() {
                $(element).text(Math.floor(obj.value));
            }
        });
    };

    /**
     * Reveal Text (Curtain Effect)
     * @param {string|HTMLElement} element - Element selector or DOM element
     * @param {object} options - Animation options
     */
    MascotGSAP.revealText = function(element, options) {
        var defaults = {
            duration: 1.5,
            ease: 'power4.out'
        };
        var settings = $.extend({}, defaults, options);

        gsap.from(element, {
            yPercent: 100,
            duration: settings.duration,
            ease: settings.ease,
            stagger: settings.stagger || 0
        });
    };

    // Initialize on document ready
    $(document).ready(function() {
        MascotGSAP.init();
    });

})(jQuery);

