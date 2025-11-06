/**
 * GSAP Scroll Pin Widget
 * Handles scroll-triggered pinned title animations with scale effects
 */
(function ($) {
  "use strict";

  /**
   * Initialize GSAP Scroll Pin Widget
   */
  var GSAPScrollPin = {
    init: function () {
      var $elements = $(".gsap-scroll-pin-wrapper[data-gsap-scroll-pin]");

      if ($elements.length === 0) {
        return;
      }

      // Wait for GSAP and ScrollTrigger to be available
      if (typeof gsap === "undefined") {
        console.error("GSAP not loaded");
        return;
      }

      if (typeof ScrollTrigger === "undefined") {
        console.error("ScrollTrigger not loaded");
        return;
      }

      // Register ScrollTrigger plugin
      gsap.registerPlugin(ScrollTrigger);

      // Initialize each element
      $elements.each(function () {
        GSAPScrollPin.initElement($(this));
      });
    },

    initElement: function ($element) {
      // Get the data attribute value
      var dataAttr = $element.attr("data-gsap-scroll-pin");

      if (!dataAttr) {
        console.error("No data-gsap-scroll-pin attribute found");
        return;
      }

      // Parse JSON data
      var data;
      try {
        data = JSON.parse(dataAttr);
      } catch (e) {
        console.error("Failed to parse animation data:", e);
        return;
      }

      // Parse data attributes with proper type conversion
      var config = {
        enableScale: data["enable-scale"] === "true" || data["enable-scale"] === true,
        initialScale: parseFloat(data["initial-scale"]) || 0.6,
        finalScale: parseFloat(data["final-scale"]) || 1,
        duration: parseFloat(data["duration"]) || 4,
        holdDuration: parseFloat(data["hold-duration"]) || 0,
        holdDelay: parseFloat(data["hold-delay"]) || 1,
        triggerStart: data["trigger-start"] || "top center-=350",
        triggerEnd: data["trigger-end"] || "bottom 150%",
        scrub: parseFloat(data["scrub"]),
        markers: data["markers"] === "true" || data["markers"] === true,
        pinSpacing: data["pin-spacing"] === "true" || data["pin-spacing"] === true,
      };

      // If scrub is 0, set to false
      if (config.scrub === 0) {
        config.scrub = false;
      }

      var $title = $element.find(".scroll-pin-title");

      if ($title.length === 0) {
        console.error("No .scroll-pin-title element found");
        return;
      }

      // Set initial scale before creating timeline
      if (config.enableScale) {
        gsap.set($title[0], {
          scale: config.initialScale,
          transformOrigin: "center center",
        });
      }

      // Get the parent element for trigger
      var $parentElement = $element.closest(".e-parent");
      var triggerElement = $parentElement.length ? $parentElement[0] : $element[0];

      // Create the animation timeline
      var timeline = gsap.timeline({
        scrollTrigger: {
          trigger: triggerElement,
          start: config.triggerStart,
          end: config.triggerEnd,
          pin: $element[0],
          pinSpacing: config.pinSpacing,
          scrub: config.scrub,
          markers: config.markers,
          anticipatePin: 1,
          onEnter: function () {
            $element.addClass("gsap-scroll-pin-active");
          },
          onLeave: function () {
            $element.removeClass("gsap-scroll-pin-active");
          },
          onEnterBack: function () {
            $element.addClass("gsap-scroll-pin-active");
          },
          onLeaveBack: function () {
            $element.removeClass("gsap-scroll-pin-active");
          },
        },
      });

      // Add scale animation if enabled
      if (config.enableScale) {
        // Scale animation
        timeline.to($title[0], {
          scale: config.finalScale,
          duration: config.duration,
          ease: "power2.out",
        });

        // Hold at final scale
        if (config.holdDuration > 0) {
          timeline.to($title[0], {
            scale: config.finalScale,
            duration: config.holdDuration,
            delay: config.holdDelay,
          });
        }
      }

      // Store timeline reference for potential cleanup
      $element.data("gsap-timeline", timeline);

      // Refresh ScrollTrigger after initialization
      setTimeout(function () {
        ScrollTrigger.refresh();
      }, 100);
    },

    destroy: function ($element) {
      var timeline = $element.data("gsap-timeline");
      if (timeline && timeline.scrollTrigger) {
        timeline.scrollTrigger.kill();
        timeline.kill();
        $element.removeData("gsap-timeline");
      }
      ScrollTrigger.refresh();
    },
  };

  // Initialize with a slight delay to ensure DOM is ready
  $(document).ready(function () {
    setTimeout(function () {
      GSAPScrollPin.init();
    }, 100);
  });

  // Also initialize on window load for better reliability
  $(window).on("load", function () {
    ScrollTrigger.refresh();
  });

  // Re-initialize on Elementor preview
  if (typeof elementorFrontend !== "undefined" && elementorFrontend.hooks) {
    elementorFrontend.hooks.addAction("frontend/element_ready/mascot-gsap-scroll-pin.default", function ($scope) {
      setTimeout(function () {
        var $element = $scope.find(".gsap-scroll-pin-wrapper[data-gsap-scroll-pin]");
        if ($element.length) {
          // Destroy existing timeline if any
          GSAPScrollPin.destroy($element);
          // Initialize new timeline
          GSAPScrollPin.initElement($element);
        }
      }, 100);
    });
  }

  // Expose to global scope
  window.MascotGSAPScrollPin = GSAPScrollPin;
})(jQuery);
