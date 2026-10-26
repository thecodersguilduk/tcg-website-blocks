<?php
/**
 * Plugin Name:       TCG Blocks
 * Plugin URI:        https://github.com/thecodersguilduk/tcg-website-blocks
 * Description:       The Coders Guild's custom Gutenberg blocks and content styles. Every folder in /blocks with a block.json is registered automatically. Updates are delivered from GitHub releases.
 * Version:           1.3.0
 * Requires at least: 6.5
 * Requires PHP:      7.4
 * Author:            The Coders Guild
 * Text Domain:       tcg-blocks
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'TCG_BLOCKS_VERSION', '1.3.0' );
define( 'TCG_BLOCKS_URL', plugin_dir_url( __FILE__ ) );
define( 'TCG_BLOCKS_DIR', plugin_dir_path( __FILE__ ) );

/*
 * Updates from GitHub.
 * WordPress checks the repo's releases and offers an update when a release
 * carries a higher Version than the one installed. The release workflow
 * attaches a clean tcg-blocks.zip, which is what gets installed.
 * For a private repo, define TCG_BLOCKS_GITHUB_TOKEN in wp-config.php.
 */
require_once TCG_BLOCKS_DIR . 'vendor/plugin-update-checker/plugin-update-checker.php';

$tcg_blocks_updater = \YahnisElsts\PluginUpdateChecker\v5\PucFactory::buildUpdateChecker(
	'https://github.com/thecodersguilduk/tcg-website-blocks/',
	__FILE__,
	'tcg-blocks'
);
$tcg_blocks_updater->getVcsApi()->enableReleaseAssets( '/tcg-blocks\.zip($|[?&#])/i' );
if ( defined( 'TCG_BLOCKS_GITHUB_TOKEN' ) && TCG_BLOCKS_GITHUB_TOKEN ) {
	$tcg_blocks_updater->setAuthentication( TCG_BLOCKS_GITHUB_TOKEN );
}

// [tcg_block id="123"]: synced patterns inside Elementor (Shortcode widget).
require_once TCG_BLOCKS_DIR . 'includes/pattern-shortcode.php';

// Own category in the inserter, listed first.
add_filter( 'block_categories_all', function ( $categories ) {
	array_unshift( $categories, array(
		'slug'  => 'tcg',
		'title' => 'The Coders Guild',
		'icon'  => null,
	) );
	return $categories;
} );

// Register every block in /blocks/<name>/block.json.
add_action( 'init', function () {
	foreach ( glob( TCG_BLOCKS_DIR . 'blocks/*/block.json' ) as $block_json ) {
		register_block_type( dirname( $block_json ) );
	}
} );

/*
 * One-click callout styles on the core Group block (Styles panel).
 * They add is-style-tcg-callout / -navy / -sand, styled in content.css.
 */
add_action( 'init', function () {
	$styles = array(
		'tcg-callout'      => 'Callout (red)',
		'tcg-callout-navy' => 'Callout (navy)',
		'tcg-callout-sand' => 'Callout (sand)',
	);
	foreach ( $styles as $name => $label ) {
		register_block_style( 'core/group', array(
			'name'  => $name,
			'label' => $label,
		) );
	}
} );

/*
 * Content styles for core blocks (lists, tables, quotes, FAQs, code, images).
 * enqueue_block_assets loads them on the site and inside the post editor,
 * so authors see the real styling while writing.
 */
add_action( 'enqueue_block_assets', function () {
	wp_enqueue_style(
		'tcg-content',
		TCG_BLOCKS_URL . 'assets/css/content.css',
		array(),
		TCG_BLOCKS_VERSION
	);
} );

/*
 * Syntax highlighting for Code blocks (highlight.js, bundled locally).
 * Only loaded on single posts and pages that actually contain a Code block.
 */
add_action( 'wp_enqueue_scripts', function () {
	if ( ! is_singular() || ! has_block( 'core/code' ) ) {
		return;
	}
	wp_enqueue_style(
		'tcg-code-theme',
		TCG_BLOCKS_URL . 'assets/css/code-theme.css',
		array(),
		TCG_BLOCKS_VERSION
	);
	wp_enqueue_script(
		'tcg-highlight',
		TCG_BLOCKS_URL . 'assets/js/highlight.min.js',
		array(),
		'11.11.1',
		true
	);
	wp_enqueue_script(
		'tcg-code-highlight',
		TCG_BLOCKS_URL . 'assets/js/code-highlight.js',
		array( 'tcg-highlight' ),
		TCG_BLOCKS_VERSION,
		true
	);
} );
