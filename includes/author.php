<?php
/**
 * Author box support for the TCG Author block (blocks/author).
 *
 * - Adds an "Author box" section to user profiles: job title, LinkedIn URL
 *   and photo. The bio is WordPress's own Biographical Info field.
 * - [tcg_author] shortcode: the same box, for the Elementor single-post
 *   template (Shortcode widget), so every post gets it automatically.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* --------------------------------------------------------------------------
   Profile fields
   -------------------------------------------------------------------------- */

add_action( 'show_user_profile', 'tcg_blocks_author_fields' );
add_action( 'edit_user_profile', 'tcg_blocks_author_fields' );

function tcg_blocks_author_fields( $user ) {
	if ( ! user_can( $user, 'edit_posts' ) ) {
		return; // Only people who can write posts get an author box.
	}

	$title    = get_user_meta( $user->ID, 'tcg_job_title', true );
	$linkedin = get_user_meta( $user->ID, 'tcg_linkedin', true );
	$photo_id = (int) get_user_meta( $user->ID, 'tcg_author_photo', true );
	$photo    = $photo_id ? wp_get_attachment_image_url( $photo_id, 'thumbnail' ) : '';
	?>
	<h2>Author box</h2>
	<p>Shown by the <strong>TCG Author</strong> block on posts you write. The bio is the <em>Biographical Info</em> field above.</p>
	<table class="form-table" role="presentation">
		<tr>
			<th><label for="tcg_job_title">Job title</label></th>
			<td>
				<input type="text" name="tcg_job_title" id="tcg_job_title" class="regular-text" value="<?php echo esc_attr( $title ); ?>" />
				<p class="description">Shown in red under your name, e.g. "CEO, The Coders Guild".</p>
			</td>
		</tr>
		<tr>
			<th><label for="tcg_linkedin">LinkedIn profile</label></th>
			<td>
				<input type="url" name="tcg_linkedin" id="tcg_linkedin" class="regular-text code" placeholder="https://www.linkedin.com/in/..." value="<?php echo esc_attr( $linkedin ); ?>" />
				<p class="description">Leave blank to hide the LinkedIn icon.</p>
			</td>
		</tr>
		<tr>
			<th>Photo</th>
			<td>
				<img id="tcg_author_photo_preview" src="<?php echo esc_url( $photo ); ?>" alt="" width="96" height="96"
					style="display:<?php echo $photo ? 'block' : 'none'; ?>;object-fit:cover;border-radius:12px;margin-bottom:8px" />
				<input type="hidden" name="tcg_author_photo" id="tcg_author_photo" value="<?php echo esc_attr( $photo_id ? $photo_id : '' ); ?>" />
				<button type="button" class="button" id="tcg_author_photo_choose">Choose photo</button>
				<button type="button" class="button-link" id="tcg_author_photo_remove" style="margin-left:8px;<?php echo $photo ? '' : 'display:none'; ?>">Remove</button>
				<p class="description">A square photo, at least 280 × 280px. If empty, your Gravatar is used.</p>
			</td>
		</tr>
	</table>
	<script>
	( function () {
		var frame;
		var input = document.getElementById( 'tcg_author_photo' );
		var preview = document.getElementById( 'tcg_author_photo_preview' );
		var remove = document.getElementById( 'tcg_author_photo_remove' );
		document.getElementById( 'tcg_author_photo_choose' ).addEventListener( 'click', function () {
			if ( ! window.wp || ! wp.media ) { return; }
			if ( ! frame ) {
				frame = wp.media( { title: 'Author photo', button: { text: 'Use this photo' }, library: { type: 'image' }, multiple: false } );
				frame.on( 'select', function () {
					var img = frame.state().get( 'selection' ).first().toJSON();
					input.value = img.id;
					preview.src = ( img.sizes && img.sizes.thumbnail ? img.sizes.thumbnail.url : img.url );
					preview.style.display = 'block';
					remove.style.display = '';
				} );
			}
			frame.open();
		} );
		remove.addEventListener( 'click', function () {
			input.value = '';
			preview.style.display = 'none';
			remove.style.display = 'none';
		} );
	} )();
	</script>
	<?php
}

// The media library picker on the profile screens.
add_action( 'admin_enqueue_scripts', function ( $hook ) {
	if ( in_array( $hook, array( 'profile.php', 'user-edit.php' ), true ) ) {
		wp_enqueue_media();
	}
} );

// Core has already checked the profile form's nonce before these run.
add_action( 'personal_options_update', 'tcg_blocks_save_author_fields' );
add_action( 'edit_user_profile_update', 'tcg_blocks_save_author_fields' );

function tcg_blocks_save_author_fields( $user_id ) {
	if ( ! current_user_can( 'edit_user', $user_id ) || ! isset( $_POST['tcg_job_title'] ) ) {
		return;
	}

	update_user_meta( $user_id, 'tcg_job_title', sanitize_text_field( wp_unslash( $_POST['tcg_job_title'] ) ) );

	$linkedin = isset( $_POST['tcg_linkedin'] ) ? esc_url_raw( trim( wp_unslash( $_POST['tcg_linkedin'] ) ), array( 'https', 'http' ) ) : '';
	update_user_meta( $user_id, 'tcg_linkedin', $linkedin );

	$photo_id = isset( $_POST['tcg_author_photo'] ) ? absint( $_POST['tcg_author_photo'] ) : 0;
	if ( $photo_id && wp_attachment_is_image( $photo_id ) ) {
		update_user_meta( $user_id, 'tcg_author_photo', $photo_id );
	} else {
		delete_user_meta( $user_id, 'tcg_author_photo' );
	}
}

/* --------------------------------------------------------------------------
   [tcg_author]: the author box for the current post, anywhere shortcodes run
   -------------------------------------------------------------------------- */

add_shortcode( 'tcg_author', function () {
	if ( ! WP_Block_Type_Registry::get_instance()->is_registered( 'tcg/author' ) ) {
		return '';
	}
	// render_block() also loads the block's stylesheet.
	return render_block( array(
		'blockName'    => 'tcg/author',
		'attrs'        => array(),
		'innerBlocks'  => array(),
		'innerHTML'    => '',
		'innerContent' => array(),
	) );
} );
