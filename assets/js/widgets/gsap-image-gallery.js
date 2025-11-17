(function ($) {
  "use strict";

  var initGsapImageGallery = function () {
    if (typeof window.gsap === "undefined" || typeof window.ScrollTrigger === "undefined") {
      return;
    }

    window.gsap.registerPlugin(window.ScrollTrigger);

    $(".gallery-area.mascot-gsap-image-gallery").each(function () {
      var $gallery = $(this);
      var $mainImage = $gallery.find(".gallery-thumb img");

      if (!$mainImage.length) {
        return;
      }

      var breakpoint = parseInt($gallery.attr("data-breakpoint"), 10) || 1200;
      var mediaQuery = window.matchMedia("(min-width: " + breakpoint + "px)");

      var getAttr = function (name, defaultValue) {
        var value = $gallery.attr("data-" + name);
        return typeof value !== "undefined" ? value : defaultValue;
      };

      var timelineOptions = {
        start: getAttr("trigger-start", "top 30%"),
        end: getAttr("trigger-end", "bottom 100%"),
        pin: getAttr("pin", "true") === "true",
        pinSpacing: getAttr("pin-spacing", "false") === "true",
        markers: getAttr("markers", "false") === "true",
        scrub: parseFloat(getAttr("scrub", 1)),
        duration: parseFloat(getAttr("duration", 3)),
        finalSize: parseFloat(getAttr("final-size", 580)),
        reverse: getAttr("reverse", "false") === "true",
        initDelay: parseInt(getAttr("init-delay", 2000), 10),
      };

      if (isNaN(timelineOptions.finalSize) || timelineOptions.finalSize <= 0) {
        timelineOptions.finalSize = 580;
      }

      var destroyTimeline = function () {
        var existingTimeline = $gallery.data("gsapImageGalleryTimeline");
        if (existingTimeline) {
          if (existingTimeline.scrollTrigger) {
            existingTimeline.scrollTrigger.kill();
          }
          existingTimeline.kill();
          $gallery.removeData("gsapImageGalleryTimeline");
        }
      };

      var createTimeline = function () {
        destroyTimeline();

        if (!mediaQuery.matches) {
          window.gsap.set($mainImage, { clearProps: "width,height" });
          return;
        }

        var mainImageElement = $mainImage.get(0);

        if (!mainImageElement) {
          return;
        }

        if (!mainImageElement.complete || mainImageElement.naturalWidth === 0) {
          if (!$gallery.data("gsapImageGalleryWaitForLoad")) {
            $gallery.data("gsapImageGalleryWaitForLoad", true);
            var onLoadHandler = function handleGsapImageGalleryLoad() {
              mainImageElement.removeEventListener("load", handleGsapImageGalleryLoad);
              $gallery.removeData("gsapImageGalleryWaitForLoad");
              createTimeline();
            };
            mainImageElement.addEventListener("load", onLoadHandler);
          }
          return;
        }

        window.gsap.set(mainImageElement, { clearProps: "width,height" });

        var initialRect = mainImageElement.getBoundingClientRect();
        var initialWidth = initialRect.width;
        var initialHeight = initialRect.height;

        if (!isFinite(initialWidth) || initialWidth <= 0) {
          initialWidth = mainImageElement.naturalWidth || timelineOptions.finalSize;
        }

        if (!isFinite(initialHeight) || initialHeight <= 0) {
          initialHeight = mainImageElement.naturalHeight || timelineOptions.finalSize;
        }

        var animationDuration = isNaN(timelineOptions.duration) ? 3 : timelineOptions.duration;

        if (timelineOptions.reverse) {
          window.gsap.set(mainImageElement, {
            width: timelineOptions.finalSize + "px",
            height: timelineOptions.finalSize + "px",
          });
        }

        var tl = window.gsap.timeline({
          scrollTrigger: {
            trigger: $gallery.get(0),
            start: timelineOptions.start,
            end: timelineOptions.end,
            pin: timelineOptions.pin,
            pinSpacing: timelineOptions.pinSpacing,
            markers: timelineOptions.markers,
            scrub: isNaN(timelineOptions.scrub) ? 1 : timelineOptions.scrub,
          },
        });

        if (timelineOptions.reverse) {
          tl.to($mainImage, {
            width: initialWidth + "px",
            height: initialHeight + "px",
            duration: animationDuration,
            ease: "none",
          });
        } else {
          tl.to($mainImage, {
            width: timelineOptions.finalSize + "px",
            height: timelineOptions.finalSize + "px",
            duration: animationDuration,
            ease: "none",
          });
        }

        $gallery.data("gsapImageGalleryTimeline", tl);

        // Refresh ScrollTrigger after initialization to ensure correct marker positions
        if (typeof window.ScrollTrigger !== "undefined") {
          window.ScrollTrigger.refresh();
        }
      };

      // Initialize with delay to ensure correct marker positioning
      setTimeout(function() {
        createTimeline();
      }, timelineOptions.initDelay);

      if (mediaQuery.addEventListener) {
        mediaQuery.addEventListener("change", createTimeline);
      } else if (mediaQuery.addListener) {
        mediaQuery.addListener(createTimeline);
      }
    });
  };

  $(document).ready(initGsapImageGallery);

  $(window).on("elementor/frontend/init", function () {
    if (typeof window.elementorFrontend !== "undefined") {
      window.elementorFrontend.hooks.addAction("frontend/element_ready/mascot-gsap-image-gallery.default", initGsapImageGallery);
    }
  });
})(jQuery);
