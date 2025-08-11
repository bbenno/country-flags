<!--
SPDX-FileCopyrightText: 2025 NONE

SPDX-License-Identifier: CC0-1.0
-->

=== Country Flags ===
Contributors: Benno Bielmeier
Tags: Flags
Requires at least: 6.0
Tested up to: 6.6
Requires PHP: 8.0
Stable tag: 1.0.0
License: EUPL-1.2
License URI: https://joinup.ec.europa.eu/page/eupl-text-11-12

Shortcode that renders SVG country flags in circle style.

== Description ==

Display country flags as clean, circular SVGs via a simple shortcode.
The plugin registers `[flag]` and renders an `<img>` element that points to a flag asset. It is fast, cache-friendly, and accessible by default. You may serve the SVGs from a CDN during development and switch to local, bundled assets for production without changing your content.

**Key features**

* Shortcode `[flag]` with ISO-style codes (e.g. `de`, `gb`, `us`, `gb-eng`, `eu`).
* Lazy loading and async decoding for better performance.
* Filter hook to change the base path for assets (CDN → local files).
* No JavaScript required. Works with classic and block editors.
* Compatible with object/page caches and CDNs.

**Usage**

Place the shortcode anywhere content is accepted:

```
[flag country="de"]
[flag country="gb" title="United Kingdom"]
[flag code="us" class="inline-flag" alt="United States"]
```

Notes:

* Use either `country` or `code` (they are aliases). Lowercase preferred.
* `alt` is optional. When omitted, an empty label is used.
* `title` is optional. Screen readers do not need it if `alt` is set.
* `class` lets you apply site-specific styling.

**Styling and sizing**

SVGs scale naturally through CSS. For example:

```css
.cfs-flag { width: 1.5rem; height: auto; vertical-align: middle; }
```

Avoid fixed pixel heights unless necessary. Let the image scale with the surrounding text or container.

**Security**

* Flags are referenced as external SVG files via `<img src="...">`. They are **not** inlined into the page DOM, which reduces the attack surface.

**Privacy**

* When referencing a third-party CDN, the visitor’s browser makes a request to that host.

**Attribution and licence**

The flag images are sourced from the **Circle Flags** project by HatScripts:
[https://github.com/HatScripts/circle-flags](https://github.com/HatScripts/circle-flags)

Circle Flags is released under the **MIT licence**. When you redistribute the SVGs with your plugin, include a copy of the MIT licence and attribute the project in your documentation or about screen. The MIT licence text is available in the upstream repository. Attribution example:

> Includes flag assets from “Circle Flags” © HatScripts, licensed under the MIT licence.

== Installation ==

1. Upload plugin
2. Activate

== Changelog ==
