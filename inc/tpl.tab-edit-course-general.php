<?php
global $wixbu_cm_meta;
if ( ! empty( $_POST['save'] ) && wp_verify_nonce( $_POST['save'], 'wixbu-cm-save-user-data' ) ) {
	$metadata = [
		'course-desc-about',
		'course-desc-learn',
		'course-desc-for-whom',
		'course-desc-skills',
	];

	$id_taxes = [
		'course_difficulty',
		'course_cat',
	];

	$text_taxes = [
		'software_req',
		'course_tags',
	];

	foreach ( $id_taxes as $tax ) {
		wp_set_post_terms( get_the_ID(), [ +$_POST['course-tax'][ $tax ] ], $tax );
	}

	foreach ( $text_taxes as $tax ) {
		wp_set_post_terms( get_the_ID(), $_POST['course-tax'][ $tax ], $tax );
	}

	foreach ( $metadata as $metadatum ) {
		if ( isset( $_POST[ $metadatum ] ) ) {
			Wixbu_Course_Manager::pmeta_update( $metadatum, $_POST[ $metadatum ] );
		}
	}

	wp_update_post( [
		'ID'           => get_the_ID(),
		'post_title'   => $_POST['course_title'],
	] );
}
?>

<h1>Instructor profile</h1>

<form class="wixbu-cm-center-form" method="post" enctype="multipart/form-data">

	<div class="llms-cols-7 llms-form-field">
		<label><?php _e( 'Course title', 'wixbu-cm' ) ?></label>
		<input required="required" type="text" name="course_title" value="<?php the_title(); ?>">
	</div>

	<div class="llms-cols-7 llms-form-field">
		<label><?php _e( 'What is this course about?', 'wixbu-cm' ) ?></label>
		<textarea required="required" type="text" name="course-desc-about"><?php echo Wixbu_Course_Manager::pmeta( 'course-desc-about' ); ?></textarea>
	</div>


	<div class="llms-cols-7 llms-form-field">
		<label><?php _e( 'What will your students learn?', 'wixbu-cm' ) ?></label>
		<textarea required="required" type="text" name="course-desc-learn"><?php echo Wixbu_Course_Manager::pmeta( 'course-desc-learn' ); ?></textarea>
	</div>

	<div class="llms-cols-7 llms-form-field">
		<label><?php _e( 'Who is this course for?', 'wixbu-cm' ) ?></label>
		<input required="required" type="text" name="course-desc-for-whom" value="<?php echo Wixbu_Course_Manager::pmeta( 'course-desc-for-whom' ); ?>">
	</div>

	<div class="llms-cols-7 llms-form-field">
		<label><?php _e( 'What output/skills will your students achieve?', 'wixbu-cm' ) ?></label>
		<input required="required" type="text" name="course-desc-skills" value="<?php echo Wixbu_Course_Manager::pmeta( 'course-desc-skills' ); ?>">
	</div>

	<div class="llms-cols-7 llms-form-field">
		<label><?php _e( 'Course level', 'wixbu-cm' ) ?></label>
		<select required="required" name="course-tax[course_difficulty]">
			<option><?php _e( 'Please choose...', 'wixbu-cm' ) ?></option>
			<?php
			$val = Wixbu_Course_Manager::pterms( 'course_difficulty' );
			$terms = get_terms( array(
				'taxonomy' => 'course_difficulty',
				'hide_empty' => false,
			) );

			/** @var WP_Term $term */
			foreach ( $terms as $term ) {
				$selected = selected( ! empty( $val[ $term->term_id ] ), true, 0 );
				echo "<option value='{$term->term_id}' $selected>{$term->name}</option>";
			}
			?>
		</select>
	</div>

	<div class="llms-cols-7 llms-form-field">
		<label><?php _e( 'Software requirement', 'wixbu-cm' ) ?></label>
		<?php $val = implode( ', ', Wixbu_Course_Manager::pterms( 'software_req' ) ); ?>
		<input required="required" type="text" name="course-tax[software_req]" value="<?php echo $val; ?>"
					 placeholder="<?php _e( 'Software requirement (comma separated)', 'wixbu-cm' ) ?>">
	</div>


	<div class="llms-cols-7 llms-form-field">
		<label><?php _e( 'Course category', 'wixbu-cm' ) ?></label>
		<select required="required" name="course-tax[course_cat]">
			<option><?php _e( 'Please choose...', 'wixbu-cm' ) ?></option>
			<?php
			$val = Wixbu_Course_Manager::pterms( 'course_cat' );
			$terms = get_terms( array(
				'taxonomy' => 'course_cat',
				'hide_empty' => false,
			) );

			/** @var WP_Term $term */
			foreach ( $terms as $term ) {
				$selected = selected( ! empty( $val[ $term->term_id ] ), true, 0 );
				echo "<option value='{$term->term_id}' $selected>{$term->name}</option>";
			}
			?>
		</select>
	</div>

	<div class="llms-cols-7 llms-form-field">
		<label><?php _e( 'Course tags', 'wixbu-cm' ) ?></label>
		<?php $val = implode( ', ', Wixbu_Course_Manager::pterms( 'course_tag' ) ); ?>
		<input required="required" type="text" name="course-tax[course_tags]" value="<?php echo $val; ?>"
					 placeholder="<?php _e( 'Course tags (comma separated)', 'wixbu-cm' ) ?>">
	</div>

	<div class="llms-form-field llms-cols-4">
		<button name="save" value="<?php echo wp_create_nonce( 'wixbu-cm-save-user-data' ) ?>" type="submit" class="llms-button-action">
			<?php _e( 'Save', 'wixbu-course-manager' ) ?>
		</button>
	</div>
</form>
