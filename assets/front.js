/**
 * Plugin front end scripts
 *
 * @package Wixbu_Course_Manager
 * @version 1.0.0
 */
jQuery( function ( $ ) {

	var
		$input = $( '#wixbu-feat-img' ),
		$coursePublishError = $( '.wixbu-cm-course-publish-error' );

	$input.change( function () {
		$input.closest( 'form' ).addClass( 'uploading' ).submit();
	} );

	$( '.cant-publish-course' ).click( function() {
		if ( $coursePublishError.hasClass( 'show-error' ) ) {
			$coursePublishError.removeClass( 'wobble-error' );
			setTimeout( function () {
				$coursePublishError.addClass( 'wobble-error' );
			}, 100 );
		} else {
			$coursePublishError.addClass( 'show-error' );
		}
		$coursePublishError.addClass( 'show-error' );
	} );

} );