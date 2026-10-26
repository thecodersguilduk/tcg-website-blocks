# TCG Blocks

The Coders Guild's custom Gutenberg blocks and content styles for [thecodersguild.org.uk](https://thecodersguild.org.uk). WordPress installs updates straight from this repo's releases.

## What's in it

| Part | Where |
|---|---|
| **TCG Quote** block: testimonial with photo, name, role | `blocks/quote/` |
| Content styles for core blocks: lists, tables, quotes, pullquotes, FAQs (Details), code, images, cover | `assets/css/content.css` |
| Callout styles on the Group block: *Callout (red / navy / sand)* | registered in `tcg-blocks.php`, styled in `content.css` |
| Code block syntax highlighting (highlight.js 11.11.1, bundled) | `assets/js/`, `assets/css/code-theme.css` |
| `[tcg_block]` shortcode: synced patterns inside Elementor | `includes/pattern-shortcode.php` |
| GitHub update checker (Plugin Update Checker 5.7, bundled) | `vendor/plugin-update-checker/` |

Brand tokens: Navy `#3A405A`, Red `#E4200A`, Teal `#218380`, Sand `#F2D0A4`. Poppins 300/400/500/600.

Every content rule targets a core block class (`.wp-block-*`), so Elementor menus, icon lists, headers and footers are never affected.

## Releasing an update

1. Make your changes.
2. Bump the version in **both** places in `tcg-blocks.php`: the `Version:` header and `TCG_BLOCKS_VERSION`. Also update `Stable tag` and the changelog in `readme.txt`.
3. Commit and push to `main`.
4. On GitHub: **Releases → Draft a new release**. Create a tag that matches the version with a `v` in front (e.g. `v1.2.1`) and publish.
5. The **Build release zip** action attaches `tcg-blocks.zip` to the release. If the tag and version don't match, it fails and tells you.
6. WordPress shows *Update available* on the Plugins screen within about 12 hours. To check straight away, use the plugin's **Check for updates** link on the Plugins screen. Turn on *Enable auto-updates* to have it install itself.

Pre-releases are ignored, so a release marked "pre-release" is a safe way to stage something without it reaching the site.

## First install

Download `tcg-blocks.zip` from the latest release, then in WordPress go to **Plugins → Add New → Upload Plugin**. The folder is `tcg-blocks`, the same as the original plugin, so WordPress offers **Replace current with uploaded**. Choose that.

## Adding a new block

1. Copy `blocks/quote` to `blocks/<new-name>`.
2. In `block.json`, change `name` (keep the `tcg/` prefix, e.g. `tcg/stat-card`), `title`, `description` and `attributes`. Leave `"category": "tcg"`.
3. In `index.js`, change the `registerBlockType( 'tcg/...' )` name to match, then rewrite `edit` and `save`.
4. Restyle in `style.css`, prefixing every class with the block's own name (e.g. `.tcg-stat-card__...`).

No build step: plain JS using `window.wp.*` globals. Dependencies are listed in each block's `index.asset.php`.

### Don't

- Rename an existing block's `name`: posts using it will show "block not supported".
- Change an existing block's `save` output without adding a `deprecated` entry: posts using it will show "unexpected content".

## Using the styles when writing

- **Callout:** add a Group block, then choose *Callout (red)*, *(navy)* or *(sand)* in the Styles panel. The class `tcg-callout` (plus `tcg-callout--navy` / `tcg-callout--sand`) also works, for content written outside the editor.
- **FAQs:** consecutive Details blocks join into one navy panel.
- **Code:** use the standard Code block; the language is detected automatically.
- **Tables:** use a header row (`thead`) and add a caption; tables scroll sideways on small screens.

## Using blocks in Elementor

Gutenberg blocks don't appear in Elementor's widget panel, and Elementor's Text Editor widget is the classic editor, not blocks. There are two routes:

- **Blog posts:** nothing to do. The single-post template's *Post Content* widget renders the post's blocks with all the styles above.
- **Elementor pages:** build the content once as a **synced pattern** in the block editor (select the blocks, then ⋮ → *Create pattern*, with *Synced* on). Then add Elementor's **Shortcode** widget and paste `[tcg_block id="123"]`. The Patterns list (`wp-admin/edit.php?post_type=wp_block`) has a *Shortcode* column to copy from; `[tcg_block slug="pattern-slug"]` works too.

Editing the pattern updates every page that shows it. Only published patterns render; logged-in editors see a notice if the id or slug is wrong, visitors see nothing.
