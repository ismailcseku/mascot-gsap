(function ($) {
  "use strict";

  /**
   * GSAP Scroll Pin Animation
   * Initialize scroll-triggered animation with pinned title effect
   */
  var MascotGSAPScrollPin = function () {
    // Check if GSAP and ScrollTrigger are loaded
    if (typeof gsap === "undefined" || typeof ScrollTrigger === "undefined") {
      console.warn("GSAP or ScrollTrigger not loaded. Please ensure GSAP library is included.");
      return;
    }

    // Register ScrollTrigger plugin
    gsap.registerPlugin(ScrollTrigger);

    // Initialize animation for each instance
    $(".mascot-gsap-scroll-pin .scroll-pin-wrapper[data-gsap-scroll-pin]").each(function () {
      var $this = $(this);

      // Skip if already initialized
      if ($this.data("gsap-scroll-pin-initialized")) {
        return;
      }

      var $title = $this.find(".scroll-pin-title");
      var $widget = $this.closest(".mascot-gsap-scroll-pin");
      var $parent = $this.closest(".e-parent");

      // Check if required elements exist
      if ($title.length === 0) {
        console.warn("Scroll pin title element not found");
        return;
      }

      // Get animation settings from data attribute
      var settings = $this.data("gsap-scroll-pin");
      // Default settings if not provided
      if (!settings) {
        settings = {
          "enable-scale": true,
          "initial-scale": 0.6,
          "final-scale": 1,
          duration: 4,
          "hold-duration": 4,
          "hold-delay": 4,
          "trigger-start": "top center-=350",
          "trigger-end": "bottom 30%",
          scrub: 1,
          markers: false,
          "pin-spacing": false,
        };
      }

      // Parse scrub value
      var scrubValue = settings.scrub;
      if (scrubValue === "false" || scrubValue === false) {
        scrubValue = false;
      } else {
        scrubValue = parseFloat(scrubValue);
      }

      // Parse markers value
      var markersValue = settings.markers === "true" || settings.markers === true;

      // Parse pin-spacing value
      var pinSpacingValue = settings["pin-spacing"] === "true" || settings["pin-spacing"] === true;

      // Parse scale animation value
      var scaleEnabled = settings["enable-scale"] === "true" || settings["enable-scale"] === true;

      //Create the timeline
      var projectText = gsap.timeline({
        scrollTrigger: {
          trigger: $parent[0],
          start: settings["trigger-start"],
          end: settings["trigger-end"],
          pin: $title[0],
          markers: markersValue,
          pinSpacing: pinSpacingValue,
          scrub: scrubValue,
        },
      });

      // Set initial state (scale)
      var initialState = {
        duration: settings.duration,
      };
      if (scaleEnabled) {
        initialState.scale = settings["initial-scale"];
      }
      projectText.set($title[0], initialState);

      // Animate to final state (scale)
      var finalState = {
        duration: settings.duration,
      };
      if (scaleEnabled) {
        finalState.scale = settings["final-scale"];
      }
      projectText.to($title[0], finalState);

      // Hold at final state
      var holdState = {
        duration: settings["hold-duration"] || settings.duration,
      };
      if (scaleEnabled) {
        holdState.scale = settings["final-scale"];
      }
      var holdDelay = settings["hold-delay"] || settings.duration;
      projectText.to($title[0], holdState, "+=" + holdDelay);

      // Mark as initialized to prevent duplicate initialization
      $this.data("gsap-scroll-pin-initialized", true);
    });
  };

  /**
   * Initialize on document ready
   */
  $(document).ready(function () {
    MascotGSAPScrollPin();
  });

  /**
   * Reinitialize on Elementor preview mode
   */
  $(window).on("elementor/frontend/init", function () {
    if (typeof elementorFrontend !== "undefined") {
      elementorFrontend.hooks.addAction("frontend/element_ready/mascot-gsap-scroll-pin.default", function ($scope) {
        MascotGSAPScrollPin();
      });
    }
  });
})(jQuery);
