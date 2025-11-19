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
      // Find the parent section with class e-parent
      var $parent = $this.closest(".e-parent");

      // Check if required elements exist
      if ($title.length === 0) {
        console.warn("Scroll pin title element not found");
        return;
      }

      // Check if parent section with e-parent class exists
      if ($parent.length === 0) {
        console.warn("Parent section with class 'e-parent' not found for scroll pin");
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
          "init-delay": 2000,
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

      // Get initialization delay (default 2000ms)
      var initDelay = settings["init-delay"] ? parseInt(settings["init-delay"], 10) : 2000;

      // Initialize function - wait for window load to ensure correct positioning
      var initScrollPin = function () {
        // Create the timeline
        // trigger should be the parent section with class e-parent
        var projectText = gsap.timeline({
          scrollTrigger: {
            trigger: $parent[0], // Parent section with class e-parent
            start: settings["trigger-start"],
            end: settings["trigger-end"],
            pin: $title[0],
            markers: markersValue,
            pinSpacing: pinSpacingValue,
            scrub: scrubValue,
            invalidateOnRefresh: true, // Force recalculation on refresh
            refreshPriority: -1, // Refresh after other ScrollTriggers
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

        // Store ScrollTrigger instance for later refresh
        var scrollTriggerInstance = projectText.scrollTrigger;
        $this.data("scroll-trigger-instance", scrollTriggerInstance);

        // Mark as initialized to prevent duplicate initialization
        $this.data("gsap-scroll-pin-initialized", true);
      };

      // Check if window is already loaded (page refresh scenario)
      if (document.readyState === "complete") {
        // Window already loaded, initialize after delay
        setTimeout(initScrollPin, initDelay);
      } else {
        // Wait for window load event (all resources loaded) before initializing
        $(window).on("load.scrollpin", function () {
          setTimeout(initScrollPin, initDelay);
        });
      }
    });
  };

  /**
   * Initialize on document ready and window load
   */
  $(document).ready(function () {
    MascotGSAPScrollPin();

    // Refresh ScrollTrigger after all resources are loaded (fixes refresh position calculation)
    $(window).on("load.scrollpinrefresh", function () {
      if (typeof ScrollTrigger !== "undefined") {
        // Small delay to ensure all layout calculations are complete
        setTimeout(function () {
          ScrollTrigger.refresh();
        }, 100);
      }
    });

    // Refresh ScrollTrigger on resize (with debounce)
    var resizeTimer;
    $(window).on("resize.scrollpin", function () {
      clearTimeout(resizeTimer);
      resizeTimer = setTimeout(function () {
        if (typeof ScrollTrigger !== "undefined") {
          ScrollTrigger.refresh();
        }
      }, 250);
    });

    // Handle page refresh - refresh ScrollTrigger when coming back to page
    $(window).on("pageshow.scrollpin", function (event) {
      if (event.originalEvent.persisted) {
        // Page was loaded from cache (back/forward navigation)
        setTimeout(function () {
          if (typeof ScrollTrigger !== "undefined") {
            ScrollTrigger.refresh();
          }
        }, 100);
      }
    });
  });

  /**
   * Reinitialize on Elementor preview mode
   */
  $(window).on("elementor/frontend/init", function () {
    if (typeof elementorFrontend !== "undefined") {
      elementorFrontend.hooks.addAction("frontend/element_ready/mascot-gsap-scroll-pin.default", function ($scope) {
        setTimeout(function () {
          MascotGSAPScrollPin();
          // Refresh ScrollTrigger after initialization to ensure correct marker positions
          if (typeof ScrollTrigger !== "undefined") {
            ScrollTrigger.refresh();
          }
        }, 2000);
      });
    }
  });
})(jQuery);
