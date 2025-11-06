# Mascot GSAP Plugin

A WordPress plugin that integrates GSAP (GreenSock Animation Platform) for advanced animations in your WordPress themes and plugins.

## Features

- ✅ GSAP Core Library (v3.12.5)
- ✅ ScrollTrigger Plugin
- ✅ ScrollToPlugin
- ✅ Draggable Plugin
- ✅ MotionPathPlugin
- ✅ EasePack
- ✅ TextPlugin
- ✅ Custom Helper Functions
- ✅ Elementor Integration Ready
- ✅ Easy-to-use API

## Installation

1. Upload the `mascot-gsap` folder to the `/wp-content/plugins/` directory
2. Download GSAP library files from [GreenSock](https://greensock.com/gsap/) and place them in `assets/js/` folder
3. Activate the plugin through the 'Plugins' menu in WordPress

## Required GSAP Files

Place these files in the `assets/js/` folder:

- `gsap.min.js` (Core library)
- `ScrollTrigger.min.js`
- `ScrollToPlugin.min.js`
- `Draggable.min.js`
- `MotionPathPlugin.min.js`
- `EasePack.min.js`
- `TextPlugin.min.js`

## Usage

### Enqueue GSAP in your theme or plugin:

```php
// Enqueue GSAP Core
wp_enqueue_script('gsap');

// Enqueue ScrollTrigger
wp_enqueue_script('gsap-scrolltrigger');

// Enqueue Helper Functions
wp_enqueue_script('mascot-gsap-helper');
```

### Using Helper Functions

#### Fade In Animation
```javascript
MascotGSAP.fadeIn('.my-element', {
    duration: 1,
    y: 30,
    ease: 'power2.out'
});
```

#### Fade In on Scroll
```javascript
MascotGSAP.fadeInScroll('.my-element', {
    duration: 1,
    y: 50,
    start: 'top 80%'
});
```

#### Slide In Animation
```javascript
MascotGSAP.slideIn('.my-element', 'left', {
    duration: 1
});
```

#### Scale Animation
```javascript
MascotGSAP.scaleIn('.my-element', {
    duration: 1,
    scale: 0
});
```

#### Stagger Animation
```javascript
MascotGSAP.stagger('.my-elements', {
    duration: 1,
    y: 30,
    stagger: 0.2
});
```

#### Parallax Effect
```javascript
MascotGSAP.parallax('.my-element', {
    yPercent: -50
});
```

#### Counter Animation
```javascript
MascotGSAP.counter('.counter', 100, {
    duration: 2
});
```

### Direct GSAP Usage

```javascript
// GSAP is available globally
gsap.to('.element', {
    x: 100,
    duration: 1
});

// ScrollTrigger is available globally
ScrollTrigger.create({
    trigger: '.element',
    start: 'top center',
    end: 'bottom center',
    onEnter: () => console.log('entered')
});
```

## Available Scripts

### Core
- `gsap` - GSAP Core Library

### Plugins
- `gsap-scrolltrigger` - ScrollTrigger Plugin
- `gsap-scrollto` - ScrollToPlugin
- `gsap-draggable` - Draggable Plugin
- `gsap-motionpath` - MotionPathPlugin
- `gsap-easepack` - EasePack
- `gsap-text` - TextPlugin

### Helpers
- `mascot-gsap-helper` - Custom helper functions

## Helper Functions API

### `MascotGSAP.fadeIn(element, options)`
Fade in animation for elements.

**Parameters:**
- `element` (string|HTMLElement) - Element selector or DOM element
- `options` (object) - Animation options
  - `duration` (number) - Animation duration (default: 1)
  - `y` (number) - Y offset (default: 30)
  - `ease` (string) - Easing function (default: 'power2.out')
  - `stagger` (number) - Stagger delay (default: 0)

### `MascotGSAP.fadeInScroll(element, options)`
Fade in animation triggered on scroll.

**Parameters:**
- `element` (string|HTMLElement) - Element selector or DOM element
- `options` (object) - Animation options (same as fadeIn + ScrollTrigger options)

### `MascotGSAP.slideIn(element, direction, options)`
Slide in animation from specified direction.

**Parameters:**
- `element` (string|HTMLElement) - Element selector or DOM element
- `direction` (string) - 'left', 'right', 'top', or 'bottom'
- `options` (object) - Animation options

### `MascotGSAP.scaleIn(element, options)`
Scale in animation.

**Parameters:**
- `element` (string|HTMLElement) - Element selector or DOM element
- `options` (object) - Animation options

### `MascotGSAP.stagger(elements, options)`
Stagger animation for multiple elements.

**Parameters:**
- `elements` (string|NodeList) - Elements selector or NodeList
- `options` (object) - Animation options

### `MascotGSAP.parallax(element, options)`
Parallax scrolling effect.

**Parameters:**
- `element` (string|HTMLElement) - Element selector or DOM element
- `options` (object) - Animation options

### `MascotGSAP.counter(element, endValue, options)`
Animated counter.

**Parameters:**
- `element` (string|HTMLElement) - Element selector or DOM element
- `endValue` (number) - Target number
- `options` (object) - Animation options

### `MascotGSAP.splitText(element, options)`
Split text animation (character by character).

**Parameters:**
- `element` (string|HTMLElement) - Element selector or DOM element
- `options` (object) - Animation options

### `MascotGSAP.revealText(element, options)`
Reveal text with curtain effect.

**Parameters:**
- `element` (string|HTMLElement) - Element selector or DOM element
- `options` (object) - Animation options

## Examples

### Example 1: Fade in on page load
```javascript
jQuery(document).ready(function($) {
    MascotGSAP.fadeIn('.hero-title', {
        duration: 1.5,
        y: 50
    });
});
```

### Example 2: Scroll-triggered animations
```javascript
MascotGSAP.fadeInScroll('.feature-box', {
    duration: 1,
    y: 50,
    stagger: 0.2,
    start: 'top 80%'
});
```

### Example 3: Parallax background
```javascript
MascotGSAP.parallax('.parallax-bg', {
    yPercent: -30,
    scrub: 1
});
```

## License

GPL v2 or later

## Credits

- GSAP by [GreenSock](https://greensock.com/)
- Plugin by ThemeMascot

## Support

For support, please visit [ThemeMascot](https://thememascot.com/)

## Changelog

### 1.0.0
- Initial release
- GSAP Core integration
- Helper functions
- ScrollTrigger support
- Multiple plugin support

