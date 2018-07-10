<?php

if ( ! empty( $_POST['save'] ) && wp_verify_nonce( $_POST['save'], 'wixbu-cm-save-lesson' ) ) {
	wp_update_post( [
		'ID'           => $_GET['l'],
		'post_title'   => $_POST['lesson_title'],
		'post_excerpt'   => $_POST['lesson_excerpt'],
	] );
}

$qry = new WP_Query( [
	'post_type' => 'lesson',
	'post__in'  => [ $_GET['l'] ],
] );

$qry->the_post();

?>
	<div class="llms-cols-7 llms-form-field">
		<h3>#<?php the_ID() ?> <?php the_title(); ?></h3>
	</div>
	<form class="wixbu-cm-center-form" method="post">

		<?php
		if ( class_exists( 'Vimeo_LLMS' ) ) {

			$vimeo = Vimeo_LLMS::integration();

			$vimeo->enqueue();
			add_action( 'wp_footer', function () {
				Vimeo_LLMS::integration()->upload_video_form( site_url( add_query_arg( array() ) ) );
			} );
			?>
			<div class="llms-cols-7 llms-form-field">
				<?php
				$vimeo->check_response();
				wp_enqueue_style( Vimeo_LLMS::$token . '-css' );
				wp_enqueue_script( Vimeo_LLMS::$token . '-js' );
				$url = get_post_meta( get_the_ID(), 'vimeo_video', 'single' );
				if ( $url ) {
					echo $vimeo->iframe_from_url( $url );
				}
				add_action( 'admin_footer', [ $this, 'upload_video_form' ] );
				?>
				<a href="#vimeo-llms-upload-popup" class="button"><?php _e( 'Upload Lesson Video' ) ?></a>
			</div>
			<?php
		} else if ( current_user_can( 'manage_options' ) ) {
			?>
			<h3>Plugin Vimeo for LifterLMS is required.</h3>
			<?php
		}
		?>

		<div class="llms-cols-7 llms-form-field">
			<label><?php _e( 'Lesson title', 'wixbu-cm' ) ?></label>
			<input required="required" type="text" name="lesson_title" value="<?php the_title(); ?>">
		</div>

		<div class="llms-cols-7 llms-form-field">
			<label><?php _e( 'Brief Description', 'wixbu-cm' ) ?></label>
			<textarea required="required" type="text" name="lesson_excerpt"><?php echo get_the_excerpt(); ?></textarea>
		</div>

		<div class="llms-form-field llms-cols-4">
			<button name="save" value="<?php echo wp_create_nonce( 'wixbu-cm-save-lesson' ) ?>" type="submit" class="llms-button-action">
				<?php _e( 'Save', 'wixbu-course-manager' ) ?>
			</button>
		</div>

	</form>
