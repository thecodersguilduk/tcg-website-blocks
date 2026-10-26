<?php
/**
 * [tcg_block] shortcode: show a synced pattern anywhere a shortcode works,
 * mainly Elementor's Shortcode widget.
 *
 * Build the content once in the block editor as a synced pattern
 * (Appearance > Patterns, or "Create pattern" from a block's toolbar menu),
 * then drop it into Elementor with:
 *
 *   [tcg_block id="123"]          by pattern ID
 *   [tcg_block slug="my-pattern"] by pattern slug
 *
 * Editing the pattern updates every page that shows it.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_shortcode( 'tcg_block', function ( $atts ) {
	static $rendering = array(); // stops a pattern including itself

	$atts = shortcode_atts( array( 'id' => 0, 'slug' => '' ), $atts, 'tcg_block' );

	$pattern = null;
	if ( absint( $atts['id'] ) ) {
		$pattern = get_post( absint( $atts['id'] ) );
	} elseif ( '' !== $atts['slug'] ) {
		$pattern = get_page_by_path( sanitize_title( $atts['slug'] ), OBJECT, 'wp_block' );
	}

	if ( ! $pattern || 'wp_block' !== $pattern->post_type || 'publish' !== $pattern->post_status ) {
		// Tell editors why nothing shows; visitors see nothing.
		return current_user_can( 'edit_posts' )
			? '<p><strong>[tcg_block: no published pattern found for that id or slug]</strong></p>'
			: '';
	}

	if ( isset( $rendering[ $pattern->ID ] ) ) {
		return '';
	}
	$rendering[ $pattern->ID ] = true;

	// Core block CSS (columns, group layouts, buttons). Performance plugins
	// sometimes drop it on pages built in Elementor, so ask for it here.
	wp_enqueue_style( 'wp-block-library' );

	$html = do_blocks( $pattern->post_content );

	unset( $rendering[ $pattern->ID ] );

	return '<div class="tcg-block-embed">' . $html . '</div>';
} );

/*
 * Patterns list (wp-admin/edit.php?post_type=wp_block): a column with the
 * ready-to-copy shortcode for each pattern.
 */
add_filter( 'manage_wp_block_posts_columns', function ( $columns ) {
	$columns['tcg_shortcode'] = 'Shortcode';
	return $columns;
} );

add_action( 'manage_wp_block_posts_custom_column', function ( $column, $post_id ) {
	if ( 'tcg_shortcode' === $column ) {
		printf(
			'<input type="text" readonly value="%s" onfocus="this.select()" style="width:100%%;max-width:14em;font-family:monospace" />',
			esc_attr( '[tcg_block id="' . (int) $post_id . '"]' )
		);
	}
}, 10, 2 );
