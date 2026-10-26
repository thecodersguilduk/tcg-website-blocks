( function ( blocks, element, blockEditor, components, i18n ) {
	var el = element.createElement;
	var __ = i18n.__;
	var RichText = blockEditor.RichText;
	var InnerBlocks = blockEditor.InnerBlocks;
	var MediaUpload = blockEditor.MediaUpload;
	var MediaUploadCheck = blockEditor.MediaUploadCheck;
	var BlockControls = blockEditor.BlockControls;
	var useBlockProps = blockEditor.useBlockProps;
	var ToolbarGroup = components.ToolbarGroup;
	var ToolbarButton = components.ToolbarButton;
	var Button = components.Button;

	// The quote itself is one or more paragraphs.
	var QUOTE_TEMPLATE = [
		[ 'core/paragraph', { placeholder: __( 'Type the quote…', 'tcg-quote-block' ) } ]
	];

	blocks.registerBlockType( 'tcg/quote', {
		edit: function ( props ) {
			var a = props.attributes;
			var set = props.setAttributes;
			var blockProps = useBlockProps( { className: 'tcg-quote' } );

			function onSelectImage( media ) {
				set( {
					imageId: media.id,
					imageUrl: ( media.sizes && media.sizes.large ? media.sizes.large.url : media.url ),
					imageAlt: media.alt || ''
				} );
			}

			function picker( render ) {
				return el( MediaUploadCheck, null,
					el( MediaUpload, {
						onSelect: onSelectImage,
						allowedTypes: [ 'image' ],
						value: a.imageId,
						render: render
					} )
				);
			}

			return el( element.Fragment, null,
				a.imageUrl && el( BlockControls, null,
					el( ToolbarGroup, null,
						picker( function ( o ) {
							return el( ToolbarButton, { icon: 'format-image', label: __( 'Replace image' ), onClick: o.open } );
						} ),
						el( ToolbarButton, {
							icon: 'trash',
							label: __( 'Remove image' ),
							onClick: function () { set( { imageId: undefined, imageUrl: undefined, imageAlt: '' } ); }
						} )
					)
				),
				el( 'figure', blockProps,
					el( 'div', { className: 'tcg-quote__text' },
						el( 'blockquote', { className: 'tcg-quote__quote' },
							el( InnerBlocks, { allowedBlocks: [ 'core/paragraph' ], template: QUOTE_TEMPLATE, templateLock: false } )
						),
						el( 'figcaption', { className: 'tcg-quote__caption' },
							el( RichText, {
								tagName: 'span', className: 'tcg-quote__name', value: a.name,
								onChange: function ( v ) { set( { name: v } ); },
								placeholder: __( 'Name', 'tcg-quote-block' ), allowedFormats: []
							} ),
							el( RichText, {
								tagName: 'span', className: 'tcg-quote__role', value: a.role,
								onChange: function ( v ) { set( { role: v } ); },
								placeholder: __( 'Role, Organisation', 'tcg-quote-block' ), allowedFormats: [ 'core/link' ]
							} ),
							el( RichText, {
								tagName: 'span', className: 'tcg-quote__desc', value: a.description,
								onChange: function ( v ) { set( { description: v } ); },
								placeholder: __( 'Optional description line (leave blank to hide)', 'tcg-quote-block' ),
								allowedFormats: [ 'core/link', 'core/italic' ]
							} )
						)
					),
					el( 'div', { className: 'tcg-quote__photo' },
						a.imageUrl
							? el( 'img', { src: a.imageUrl, alt: a.imageAlt } )
							: picker( function ( o ) {
								return el( Button, { className: 'tcg-quote__pick', variant: 'secondary', onClick: o.open },
									__( 'Choose image', 'tcg-quote-block' ) );
							} )
					)
				)
			);
		},

		save: function ( props ) {
			var a = props.attributes;
			var blockProps = blockEditor.useBlockProps.save( { className: 'tcg-quote' } );
			var hasDesc = a.description && a.description.replace( /<[^>]*>/g, '' ).trim() !== '';

			return el( 'figure', blockProps,
				el( 'div', { className: 'tcg-quote__text' },
					el( 'blockquote', { className: 'tcg-quote__quote' }, el( InnerBlocks.Content ) ),
					el( 'figcaption', { className: 'tcg-quote__caption' },
						a.name && el( RichText.Content, { tagName: 'span', className: 'tcg-quote__name', value: a.name } ),
						a.role && el( RichText.Content, { tagName: 'span', className: 'tcg-quote__role', value: a.role } ),
						hasDesc && el( RichText.Content, { tagName: 'span', className: 'tcg-quote__desc', value: a.description } )
					)
				),
				a.imageUrl && el( 'div', { className: 'tcg-quote__photo' },
					el( 'img', { src: a.imageUrl, alt: a.imageAlt || '', loading: 'lazy' } )
				)
			);
		}
	} );
} )( window.wp.blocks, window.wp.element, window.wp.blockEditor, window.wp.components, window.wp.i18n );
