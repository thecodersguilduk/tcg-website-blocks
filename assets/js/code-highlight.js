/* Colour the code inside core Code blocks with highlight.js.
   The language is detected automatically, so authors (and Claude's blog
   skill) can keep using the standard Code block with no extra settings. */
( function () {
	function run() {
		if ( ! window.hljs ) {
			return;
		}
		window.hljs.configure( { ignoreUnescapedHTML: true } );
		document.querySelectorAll( 'pre.wp-block-code code' ).forEach( function ( el ) {
			if ( ! el.classList.contains( 'hljs' ) ) {
				window.hljs.highlightElement( el );
			}
		} );
	}
	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', run );
	} else {
		run();
	}
} )();
