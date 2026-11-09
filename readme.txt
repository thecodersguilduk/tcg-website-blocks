=== TCG Blocks ===
Contributors: thecodersguild
Requires at least: 6.5
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 1.4.1
License: GPLv2 or later

The Coders Guild's custom Gutenberg blocks and content styles for thecodersguild.org.uk.

== Description ==

* TCG Quote block: testimonial with photo, name and role.
* TCG Author block: "Written by" box built from the post author's profile, also available as [tcg_author].
* Content styles for core blocks: lists, tables, quotes, pullquotes, FAQs (Details), code, images and cover blocks. Loaded on the site and in the post editor.
* Callout styles for the Group block: red, navy and sand.
* Syntax highlighting for Code blocks (highlight.js, bundled).
* [tcg_block id="123"] shortcode: shows a synced pattern in Elementor or anywhere else shortcodes work.
* Updates are delivered from GitHub releases.

== Changelog ==

= 1.4.1 =
* Line length: body paragraphs and lists are capped at 50ch, so no line runs past about 14 words. Change --tcg-measure in content.css to adjust.
* Author box is centred and capped at 54rem, for use below the post content in the Elementor Article Template ([tcg_author] in a Shortcode widget).
* [tcg_author] stays hidden from visitors until the author has a bio (Users → Profile → Biographical Info). Logged-in editors always see it.

= 1.4.0 =
* New TCG Author block: photo, name, job title, bio and LinkedIn from the post author's WordPress profile. Nothing is stored in the post, so profile edits show everywhere.
* New Author box section on user profiles: job title, LinkedIn URL and photo (the bio is Biographical Info).
* New [tcg_author] shortcode, to put the author box in the Elementor single-post template.
* Author card matches the Figma (One Column v3): photo and "Written by" sit over the card's top edge.
* Tables restyled to the Figma v3: navy header, grey and pale-blue zebra rows, thin white cell lines, rounded corners.
* Quote text is much larger (up to 3.4rem), as in the Figma. Pullquotes now share the quote style, since the Figma has no separate pullquote.
* Fix: callout text no longer has squashed line spacing (Elementor's em line-height was inheriting as a fixed pixel value).
* Code blocks: pure black panel with more padding, as in the Figma.

= 1.3.0 =
* New [tcg_block] shortcode to show a synced pattern (by id or slug) in Elementor's Shortcode widget. The Patterns list gains a Shortcode column.
* Link and inline-code styles now also apply inside [tcg_block] embeds.
* Fix: links, list markers and inline code inside callouts now take the callout's text colour (links and bullets were red on the red callout).

= 1.2.0 =
* Content styles from the Figma blog mock-up (One Column v2), loaded site-wide and in the editor.
* TCG Quote restyled to the Figma: semibold quote, small upright name and role, soft-cornered photo.
* Callout block styles (red, navy, sand) on the Group block.
* Code block syntax highlighting in the Figma colours.
* Automatic updates from GitHub releases.

= 1.1.0 =
* TCG Quote block.
