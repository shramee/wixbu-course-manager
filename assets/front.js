/**
 * Plugin front end scripts
 *
 * @package Wixbu_Course_Manager
 * @version 1.0.0
 */
jQuery( function ( $ ) {

	var
		$input = $( '#wixbu-feat-img' );

	$input.change( function () {
		$input.closest( 'form' ).addClass( 'uploading' ).submit();
	} );

} );