	<?php

	if ( ! empty( $_POST['save'] ) && wp_verify_nonce( $_POST['save'], 'wixbu-cm-save-user-data' ) ) {
		$metadata = [
			'description',
			'behance',
			'instagram',
			'linkedin',
			'facebook',
			'membership_cost',
		];

		foreach ( $metadata as $metadatum ) {
			if ( isset( $_POST[ $metadatum ] ) ) {
				update_user_meta( get_current_user_id(), $metadatum, $_POST[ $metadatum ] );
			}
		}

		if ( $_FILES['profile-image']['size'] ) {
			require_once( ABSPATH . "wp-admin" . '/includes/image.php' );
			require_once( ABSPATH . "wp-admin" . '/includes/file.php' );
			require_once( ABSPATH . "wp-admin" . '/includes/media.php' );

			$attachment_id = media_handle_upload( 'profile-image', 0 );
			update_user_meta(
				get_current_user_id(),
				'profile-image-id',
				$attachment_id
			);
			update_user_meta(
				get_current_user_id(),
				'profile-image',
				wp_get_attachment_image_src( $attachment_id, 'medium' )[0]
			);
			update_user_meta(
				get_current_user_id(),
				'profile-image-large',
				wp_get_attachment_image_src( $attachment_id, 'large' )[0]
			);
		}
	}
	$profile_pic = Wixbu_Course_Manager::umeta( 'profile-image' );
	?>

	<h1>Instructor profile</h1>

	<form class="wixbu-cm-center-form" method="post" enctype="multipart/form-data">

		<div class="profile-photo llms-cols-7 llms-form-field">
			<label>
				<input type="file" name="profile-image" style="display:none;">
				<?php
				if ( $profile_pic ) {
					echo "<img class='fa' src='$profile_pic'>";
				} else {
					echo '<span class="fa fa-camera"></span>';
				}
				?>
				<div class="tar">
					<?php _e( 'Upload photo', 'wixbu-cm' ) ?>
				</div>
			</label>
		</div>

		<div class="instructor-about llms-cols-7 llms-form-field">
			<h3><?php _e( 'About yourself', 'wixbu-cm' ) ?></h3>
			<div class="content">
				<textarea name="description" rows="7" placeholder=""><?php echo Wixbu_Course_Manager::umeta( 'description' ); ?></textarea>
			</div>
		</div>

		<div class="instructor-social llms-cols-7 llms-form-field">
			<h3><?php _e( 'Your social accounts', 'wixbu-cm' ) ?></h3>
			<div class="content">
				<div class="social-account">
					<span class="fa fa-behance fa-fw" style="color:#191919;"></span>
					<input type="url" name="behance" value="<?php echo Wixbu_Course_Manager::umeta( 'behance' ); ?>" placeholder="Behance account link">
				</div>
				<div class="social-account">
					<span class="fa fa-instagram fa-fw" style="color:#003569;"></span>
					<input type="url" name="instagram" value="<?php echo Wixbu_Course_Manager::umeta( 'instagram' ); ?>" placeholder="Instagram account link">
				</div>
				<div class="social-account">
					<span class="fa fa-linkedin fa-fw" style="color:#0077b5;"></span>
					<input type="url" name="linkedin" value="<?php echo Wixbu_Course_Manager::umeta( 'linkedin' ); ?>" placeholder="Linkedin account link">
				</div>
				<div class="social-account">
					<span class="fa fa-facebook fa-fw" style="color:#3b5998;"></span>
					<input type="url" name="facebook" value="<?php echo Wixbu_Course_Manager::umeta( 'facebook' ); ?>" placeholder="Facebook account link">
				</div>
			</div>
		</div>


		<div class="instructor-plans llms-cols-7 llms-form-field">
			<h3><?php _e( 'Subscription monthly charges', 'wixbu-cm' ) ?></h3>
			<h5><?php _e( 'For access to all your courses for a monthly fee', 'wixbu-cm' ) ?></h5>
			<div class="content">
				<div class="social-account">
					<span class="fa fa-euro fa-fw" style="color:#191919;"></span>
					<input type="number" name="membership_cost" value="<?php echo Wixbu_Course_Manager::umeta( 'membership_cost' ); ?>" placeholder="Charges in Euros">
				</div>
			</div>
		</div>

		<div class="llms-form-field llms-cols-4">
			<button name="save" value="<?php echo wp_create_nonce( 'wixbu-cm-save-user-data' ) ?>" type="submit" class="llms-button-action">
				<?php _e( 'Save', 'wixbu-course-manager' ) ?>
			</button>
		</div>
	</form>
