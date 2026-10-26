/* TCG Author: a dynamic block. Nothing is saved in the post; render.php
   builds the box from the post author's profile, so it is always current.
   The editor shows the real server-rendered box. */
( function ( blocks, element, blockEditor, components, data, ServerSideRender ) {
	var el = element.createElement;

	function Empty() {
		return el( components.Placeholder, {
			icon: 'id-alt',
			label: 'TCG Author',
			instructions: 'Shows the post author\'s photo, name, job title, bio and LinkedIn from their WordPress profile (Users → Profile → Author box). Save the post with an author to see it here.'
		} );
	}

	blocks.registerBlockType( 'tcg/author', {
		edit: function () {
			var postId = data.useSelect( function ( select ) {
				var editor = select( 'core/editor' );
				return editor ? editor.getCurrentPostId() : null;
			}, [] );

			return el( 'div', blockEditor.useBlockProps(),
				el( ServerSideRender, {
					block: 'tcg/author',
					urlQueryArgs: postId ? { post_id: postId } : {},
					EmptyResponsePlaceholder: Empty
				} )
			);
		},
		save: function () {
			return null;
		}
	} );
} )( window.wp.blocks, window.wp.element, window.wp.blockEditor, window.wp.components, window.wp.data, window.wp.serverSideRender );
