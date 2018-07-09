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
}

$edit_sections = apply_filters( 'wixbu_course_builder_edit_course_sections', [
	'general'       => [
		'label' => __( 'General Information', 'wixbu-cm' ),
		'meta'  => [ '' ],
	],
	'preview-media' => [
		'label' => __( 'Preview Video & Image', 'wixbu-cm' ),
		'meta'  => [ '_thumbnail_id', '_llms_video_embed' ],
	],
	'structure'     => [
		'label' => __( 'Structure Builder', 'wixbu-cm' ),
		'meta'  => [ '' ],
	],
	'lesson'        => [
		'label' => __( 'Lesson Editing', 'wixbu-cm' ),
		'meta'  => [ '' ],
	],
	'plan'          => [
		'label' => __( 'Course Plan', 'wixbu-cm' ),
		'meta'  => [ '' ],
	],
] );

$current_section = filter_input( INPUT_GET, 'edit' );;

$base_url = '?tab=edit-course&c=' . $_GET['c'];
$file = __DIR__ . "/tpl.tab-edit-course-$current_section.php";

$course_qry = new WP_Query( [
	'post_type' => 'course',
	'post__in'  => [ $_GET['c'] ],
] );

$course_qry->the_post();

global $wixbu_cm_meta;

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
		foreach ( $edit_sections as $k => $tab ) {
			$section_ready = true;
			foreach ( $tab['meta'] as $meta ) {
				if ( ! Wixbu_Course_Manager::umeta( $meta ) ) {
					$section_ready = false;
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
						?>
						<i class="fa course-edit-section-status fa-fw fa-square-o"></i>
						<?php
					}
					?>
				</a>
			</div>
			<?php
		}
	}
	?>
</section>

<?php
wp_reset_postdata();