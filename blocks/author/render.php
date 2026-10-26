<?php
/**
 * TCG Author block: server render.
 * Reads the post author's profile: display name, Biographical Info, and the
 * Author box fields added by includes/author.php (job title, LinkedIn, photo).
 *
 * @var array    $attributes
 * @var string   $content
 * @var WP_Block $block
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$tcg_post_id   = ! empty( $block->context['postId'] ) ? (int) $block->context['postId'] : (int) get_the_ID();
$tcg_author_id = $tcg_post_id ? (int) get_post_field( 'post_author', $tcg_post_id ) : 0;
$tcg_user      = $tcg_author_id ? get_userdata( $tcg_author_id ) : false;

if ( ! $tcg_user ) {
	return;
}

$tcg_name     = $tcg_user->display_name;
$tcg_role     = get_user_meta( $tcg_author_id, 'tcg_job_title', true );
$tcg_bio      = get_user_meta( $tcg_author_id, 'description', true );
$tcg_linkedin = get_user_meta( $tcg_author_id, 'tcg_linkedin', true );
$tcg_photo_id = (int) get_user_meta( $tcg_author_id, 'tcg_author_photo', true );

// The name sits next to the photo, so the photo itself is decorative.
$tcg_photo = $tcg_photo_id
	? wp_get_attachment_image( $tcg_photo_id, 'medium', false, array( 'alt' => '', 'loading' => 'lazy' ) )
	: get_avatar( $tcg_author_id, 280, '', '', array( 'loading' => 'lazy' ) );

if ( $tcg_linkedin ) {
	wp_enqueue_style( 'dashicons' ); // WordPress's own icon font, for the LinkedIn icon.
}
?>
<aside <?php echo get_block_wrapper_attributes( array( 'class' => 'tcg-author' ) ); ?> aria-label="<?php echo esc_attr( 'About the author, ' . $tcg_name ); ?>">
	<?php if ( $tcg_photo ) : ?>
		<div class="tcg-author__photo"><?php echo $tcg_photo; // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
	<?php endif; ?>
	<div class="tcg-author__body">
		<p class="tcg-author__label">Written by</p>
		<p class="tcg-author__name"><?php echo esc_html( $tcg_name ); ?></p>
		<?php if ( $tcg_role ) : ?>
			<p class="tcg-author__role"><?php echo esc_html( $tcg_role ); ?></p>
		<?php endif; ?>
		<?php if ( $tcg_bio ) : ?>
			<div class="tcg-author__bio"><?php echo wpautop( wp_kses_post( $tcg_bio ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
		<?php endif; ?>
		<?php if ( $tcg_linkedin ) : ?>
			<a class="tcg-author__linkedin" href="<?php echo esc_url( $tcg_linkedin ); ?>" target="_blank" rel="noopener">
				<span class="dashicons dashicons-linkedin" aria-hidden="true"></span>
				<span class="tcg-author__sr"><?php echo esc_html( $tcg_name . ' on LinkedIn (opens in a new tab)' ); ?></span>
			</a>
		<?php endif; ?>
	</div>
</aside>
