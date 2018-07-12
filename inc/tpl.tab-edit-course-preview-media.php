<?php
global $post;
if ( ! empty( $_POST['_wpnonce'] ) && wp_verify_nonce( $_POST['_wpnonce'], 'wixbu-cm-faet-img-upload' ) ) {

	if ( ! function_exists( 'media_handle_upload' ) ) {
		require_once ABSPATH . 'wp-admin/includes/image.php';
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/media.php';
	}
	$media_id = media_handle_upload( 'featured-image', get_the_ID() );

	if( $media_id ) {
		set_post_thumbnail( $post, $media_id );
	}
}

if ( class_exists( 'Vimeo_LLMS' ) ) {

	$vimeo = Vimeo_LLMS::integration();

	$vimeo->enqueue();
	?>
	<div class="course-edit-section llms-form-field llms-cols-6">
		<?php
		$vimeo->check_response();
		wp_enqueue_script( Vimeo_LLMS::$token . '-js' );
		wp_enqueue_style( Vimeo_LLMS::$token . '-css' );
		$url = get_post_meta( get_the_ID(), 'vimeo_video', 'single' );
		if ( $url ) {
			echo $vimeo->iframe_from_url( $url );
		} else {
			echo "<div class='wixbu-cm-media-preview wixbu-cm-video-preview'></div>";
		}
		add_action( 'admin_footer', [ $this, 'upload_video_form' ] );
		?>
		<a href="#vimeo-llms-upload-popup" class="button button-hero"><?php _e( 'Upload Preview Video' ) ?></a>
	</div>

	<?php
	add_action( 'wp_footer', function() {
		Vimeo_LLMS::integration()->upload_video_form( site_url( add_query_arg( array() ) ) );
	} );

} else if ( current_user_can( 'manage_options' ) ) {
	?>
	<h3>Plugin Vimeo for LifterLMS is required.</h3>
	<?php
}
?>
<div class="course-edit-section llms-form-field llms-cols-6">
	<?php
	$url = get_the_post_thumbnail_url( null, 'medium' );
	$bg_img = $url ? "style='background-image:url($url)'" : '';
	echo "<div class='wixbu-cm-media-preview wixbu-cm-img-preview' $bg_img></div>";
	add_action( 'admin_footer', [ $this, 'upload_video_form' ] );
	?>
	<form method="post" enctype="multipart/form-data">
		<label for="wixbu-feat-img">
			<span class="button button-hero">
				<?php _e( 'Upload Featured image', 'wixbu-cm' ) ?>
				<i class="fa fa-refresh fa-spin" aria-hidden="true"></i>
			</span>
		</label>
		<input style="display:none;" type="file" name="featured-image" id="wixbu-feat-img">
		<?php wp_nonce_field( 'wixbu-cm-feat-img-upload' ) ?>
	</form>
</div>
