# WordPress Integration Guide

This guide shows you how to use Mascot GSAP plugin in your WordPress themes and plugins.

## Quick Start

### 1. Activate the Plugin

Go to WordPress Admin → Plugins → Activate "Mascot GSAP"

### 2. Enqueue Scripts in Your Theme

Add this to your theme's `functions.php`:

```php
function my_theme_enqueue_gsap() {
    // Enqueue GSAP Core
    wp_enqueue_script('gsap');

    // Enqueue ScrollTrigger (if needed)
    wp_enqueue_script('gsap-scrolltrigger');

    // Enqueue Helper functions
    wp_enqueue_script('mascot-gsap-helper');

    // Your custom animation script
    wp_enqueue_script('my-animations', get_template_directory_uri() . '/js/animations.js', array('mascot-gsap-helper'), '1.0', true);
}
add_action('wp_enqueue_scripts', 'my_theme_enqueue_gsap');
```

### 3. Create Your Animation Script

Create `js/animations.js` in your theme:

```javascript
jQuery(document).ready(function($) {

    // Fade in hero section
    MascotGSAP.fadeIn('.hero-title', {
        duration: 1.5,
        y: 50
    });

    // Scroll-triggered animations
    MascotGSAP.fadeInScroll('.feature-box', {
        duration: 1,
        y: 50,
        stagger: 0.2
    });

    // Parallax effect
    if ($('.parallax-bg').length) {
        MascotGSAP.parallax('.parallax-bg', {
            yPercent: -30
        });
    }

});
```

## Integration Examples

### Example 1: Elementor Widget Integration

```php
class My_Elementor_Widget extends \Elementor\Widget_Base {

    public function get_script_depends() {
        return ['gsap', 'gsap-scrolltrigger', 'mascot-gsap-helper'];
    }

    protected function render() {
        ?>
        <div class="my-animated-element">
            <h2>Animated Title</h2>
        </div>

        <script>
        jQuery(document).ready(function($) {
            MascotGSAP.fadeInScroll('.my-animated-element', {
                duration: 1,
                y: 50
            });
        });
        </script>
        <?php
    }
}
```

### Example 2: Gutenberg Block Integration

```php
function register_my_animated_block() {
    wp_register_script(
        'my-animated-block',
        get_template_directory_uri() . '/blocks/animated-block.js',
        array('wp-blocks', 'wp-element', 'mascot-gsap-helper')
    );

    register_block_type('my-theme/animated-block', array(
        'editor_script' => 'my-animated-block'
    ));
}
add_action('init', 'register_my_animated_block');
```

### Example 3: Custom Post Type Animation

```php
function animate_custom_post_type() {
    if (is_singular('portfolio')) {
        wp_enqueue_script('gsap');
        wp_enqueue_script('gsap-scrolltrigger');
        wp_enqueue_script('mascot-gsap-helper');

        wp_add_inline_script('mascot-gsap-helper', '
            jQuery(document).ready(function($) {
                MascotGSAP.fadeInScroll(".portfolio-item", {
                    duration: 1,
                    y: 50,
                    stagger: 0.2
                });
            });
        ');
    }
}
add_action('wp_enqueue_scripts', 'animate_custom_post_type');
```

### Example 4: WooCommerce Product Animation

```php
function animate_woo_products() {
    if (is_shop() || is_product_category()) {
        wp_enqueue_script('gsap');
        wp_enqueue_script('mascot-gsap-helper');

        wp_add_inline_script('mascot-gsap-helper', '
            jQuery(document).ready(function($) {
                MascotGSAP.fadeInScroll(".products .product", {
                    duration: 0.8,
                    y: 30,
                    stagger: 0.1
                });
            });
        ');
    }
}
add_action('wp_enqueue_scripts', 'animate_woo_products');
```

### Example 5: Menu Animation

```php
function animate_navigation() {
    wp_add_inline_script('mascot-gsap-helper', '
        jQuery(document).ready(function($) {
            // Animate menu items on page load
            MascotGSAP.fadeIn(".main-navigation li", {
                duration: 0.5,
                y: -20,
                stagger: 0.05
            });

            // Animate mobile menu
            $(".mobile-menu-toggle").on("click", function() {
                gsap.from(".mobile-menu li", {
                    opacity: 0,
                    x: -50,
                    duration: 0.3,
                    stagger: 0.05
                });
            });
        });
    ');
}
add_action('wp_enqueue_scripts', 'animate_navigation');
```

## Available Scripts to Enqueue

| Handle | Description | Dependencies |
|--------|-------------|--------------|
| `gsap` | GSAP Core Library | none |
| `gsap-scrolltrigger` | ScrollTrigger Plugin | gsap |
| `gsap-scrollto` | ScrollTo Plugin | gsap |
| `gsap-draggable` | Draggable Plugin | gsap |
| `gsap-motionpath` | MotionPath Plugin | gsap |
| `gsap-easepack` | EasePack Plugin | gsap |
| `gsap-text` | Text Plugin | gsap |
| `mascot-gsap-helper` | Helper Functions | jquery, gsap |

## Best Practices

### 1. Only Load What You Need

```php
// Only load GSAP on pages that need it
function conditional_gsap_loading() {
    if (is_front_page()) {
        wp_enqueue_script('gsap');
        wp_enqueue_script('mascot-gsap-helper');
    }
}
add_action('wp_enqueue_scripts', 'conditional_gsap_loading');
```

### 2. Check if GSAP is Loaded

```javascript
if (typeof gsap !== 'undefined') {
    // GSAP is loaded, safe to use
    gsap.to('.element', {x: 100});
}
```

### 3. Use ScrollTrigger for Performance

```javascript
// Better performance with ScrollTrigger
MascotGSAP.fadeInScroll('.element', {
    start: 'top 80%', // Only animate when visible
    once: true // Only animate once
});
```

### 4. Combine with WordPress Hooks

```php
function add_gsap_to_footer() {
    if (is_home()) {
        ?>
        <script>
        jQuery(document).ready(function($) {
            MascotGSAP.stagger('.post', {
                duration: 0.8,
                y: 50,
                stagger: 0.1
            });
        });
        </script>
        <?php
    }
}
add_action('wp_footer', 'add_gsap_to_footer');
```

## Common Use Cases

### Smooth Page Transitions

```javascript
// Add to your animations.js
jQuery(document).ready(function($) {
    // Fade in page content
    gsap.from('body', {
        opacity: 0,
        duration: 0.5,
        ease: 'power2.out'
    });
});
```

### Animated Header on Scroll

```javascript
jQuery(window).on('scroll', function() {
    var scroll = jQuery(window).scrollTop();

    if (scroll > 100) {
        gsap.to('.site-header', {
            backgroundColor: 'rgba(255,255,255,0.95)',
            boxShadow: '0 2px 10px rgba(0,0,0,0.1)',
            duration: 0.3
        });
    } else {
        gsap.to('.site-header', {
            backgroundColor: 'transparent',
            boxShadow: 'none',
            duration: 0.3
        });
    }
});
```

### Loading Animation

```javascript
jQuery(window).on('load', function() {
    gsap.to('.loading-screen', {
        opacity: 0,
        duration: 0.5,
        onComplete: function() {
            jQuery('.loading-screen').hide();
        }
    });
});
```

## Debugging

Enable GSAP debugging by adding this to your script:

```javascript
// Check GSAP version
console.log('GSAP Version:', gsap.version);

// Check if ScrollTrigger is loaded
console.log('ScrollTrigger loaded:', typeof ScrollTrigger !== 'undefined');

// View all registered ScrollTriggers
if (typeof ScrollTrigger !== 'undefined') {
    console.log('ScrollTriggers:', ScrollTrigger.getAll());
}
```

## Support

For issues or questions:
- Check the [README.md](README.md) file
- View [examples.html](examples.html) for live examples
- Visit [GSAP Documentation](https://greensock.com/docs/)

## License

This plugin is GPL v2 or later. GSAP library has its own license from GreenSock.

