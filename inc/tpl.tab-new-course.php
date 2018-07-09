<h1>New course</h1>
<form method="post" action="?tab=edit-course">
	<div class="llms-form-field llms-cols-8">
		<input type="text" name="course_title" placeholder="<?php _e( 'Course name', 'wixbu-course-manager' ) ?>">
	</div>

	<div class="llms-form-field llms-cols-4">
		<button name="create_new" value="course" type="submit" class="llms-button-action">
			<?php _e( 'Create', 'wixbu-course-manager' ) ?>
		</button>
	</div>

	<input type="hidden" name="create_course" value="<?php echo wp_create_nonce( "create_course" ); ?>">
</form>