<?php

// name="create_course" wp_create_nonce( "create_course" )
if (
	! empty( $_POST['course_title'] ) &&
	! empty( $_POST['create_course'] ) &&
	wp_verify_nonce( $_POST['create_course'], "create_course" )
) {
	$_GET['c'] = wp_insert_post( array(
		'post_title' => $_POST['course_title'],
		'post_type'  => 'course',
	) );
} else if (
	! empty( $_POST['publish_course'] ) &&
	wp_verify_nonce( $_POST['publish_course'], "wixbu-cm-publish-course" ) ) {
	wp_publish_post( $_GET['c'] );
}

$edit_sections = apply_filters( 'wixbu_course_builder_edit_course_sections', [
	'general'       => [
		'label' => __( 'General Information', 'wixbu-cm' ),
		'done'  => [ 'course-desc-about', 'course-desc-learn', 'course-desc-for-whom', 'course-desc-skills', ],
	],
	'preview-media' => [
		'label' => __( 'Preview Video & Image', 'wixbu-cm' ),
		'done'  => [ '_thumbnail_id', '_llms_video_embed' ],
	],
	'structure'     => [
		'label' => __( 'Structure Builder', 'wixbu-cm' ),
		'done'  => function() {
			global $course;
			return !! $course->get_sections();
		},
	],
	'lessons'        => [
		'label' => __( 'Lessons Editing', 'wixbu-cm' ),
		'done'  => function() {
			global $course;
			return !! $course->get_lessons();
		},
	],
	'plan'          => [
		'label' => __( 'Course Plan', 'wixbu-cm' ),
		'done'  => function() {
			global $course;

			/** @var LLMS_Product $course */
			$course = new LLMS_Product( $course->get( 'id' ) );

			$access_plans = $course->get_access_plans();

			$price = '';

			/** @var LLMS_Access_Plan $access_plan */
			foreach ( $access_plans as $access_plan ) {
				if ( ! $access_plan->can_expire() ) {
					$price = $access_plan->get( 'price' );
				}
			}

			return $price;
		},	],
] );

$current_section = filter_input( INPUT_GET, 'edit' );;

$base_url = '?tab=edit-course&c=' . $_GET['c'];
$file = __DIR__ . "/tpl.tab-edit-course-$current_section.php";

$course_qry = new WP_Query( [
	'post_type' => 'course',
	'post_status' => 'any',
	'post__in'  => [ $_GET['c'] ],
] );

$course_qry->the_post();

global $wixbu_cm_meta, $course, $post;

$course = new LLMS_Course( $post );

$wixbu_cm_meta = get_post_meta( get_the_ID() );

?>

<header>
	<?php
	if ( ! empty( $edit_sections[ $current_section ] ) ) {
		?>
		<a id="back-to-edit-couse" href="<?php echo $base_url ?>"><i class="fa fa-chevron-left"></i></a>
		<h2><small><?php _e( 'Editing', 'wixbu-cm' ) ?></small> #<?php the_ID() ?> <?php the_title(); ?></h2>
		<h3><?php echo $edit_sections[ $current_section ]['label']; ?></h3>
		<?php
	} else {
		?>
		<h2><small><?php _e( 'Editing', 'wixbu-cm' ) ?></small> #<?php the_ID() ?> <?php the_title(); ?></h2>
		<?php
	}
	?>
</header>

<section>
	<?php
	if ( $current_section ) {
		$file = __DIR__ . "/tpl.tab-edit-course-$current_section.php";

		if ( file_exists( $file ) ) {
			require $file;
		}
	} else {

		$course_sections = $course->get_sections( 'posts' );
		$course_lessons = $course->get_lessons();

		$ready_for_publish = true;

		foreach ( $edit_sections as $k => $tab ) {
			$section_ready = true;
			if ( is_callable( $tab['done'] ) ) {
				$section_ready = call_user_func( $tab['done'] );
			} else if ( is_array( $tab['done'] ) ) {
				foreach ( $tab['done'] as $meta ) {
					if ( ! Wixbu_Course_Manager::pmeta( $meta ) ) {
						$section_ready = false;
					}
				}
			}
			?>
			<div class="course-edit-section">
				<a href="<?php echo "$base_url&edit=$k"; ?> ">
					<?php
					echo $tab['label'];
					if ( $section_ready ) {
						?>
						<i class="fa course-edit-section-status fa-fw fa-check-square-o"></i>
						<?php
					} else {
						$ready_for_publish = false;
						?>
						<i class="fa course-edit-section-status fa-fw fa-square-o"></i>
						<?php
					}
					?>
				</a>
			</div>
			<?php
		}

		if ( $ready_for_publish ) {
			if ( 'publish' === get_post_status( get_the_ID() ) ) {
				?>
				<div class="wixbu-cm-center-form">
					<div class="llms-form-field llms-cols-4">
						<span class="button-like">
							<?php _e( 'Course Published', 'wixbu-course-manager' ) ?>
						</span>
					</div>
				</div>
				<?php
			} else {
				?>
				<form class="wixbu-cm-center-form" method="post">
					<div class="llms-form-field llms-cols-4">
						<button name="publish_course" value="<?php echo wp_create_nonce( 'wixbu-cm-publish-course' ) ?>" type="submit" class="llms-button-action">
							<?php _e( 'Publish', 'wixbu-course-manager' ) ?>
						</button>
					</div>
				</form>
				<?php
			}
		}
	}
	?>
</section>

<?php
wp_reset_postdata();