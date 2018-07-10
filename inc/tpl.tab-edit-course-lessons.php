<?php
global $post;
if ( ! empty( $_GET['delete_nonce'] ) && wp_verify_nonce( $_GET['delete_nonce'], 'lesson_delete' ) && ! empty( $_GET['l'] ) ) {
	//	wp_delete_post( $_GET['l'], true );
	echo '<h4 class="llms-notice-box">' . sprintf( __( 'Deleted lesson %s', 'wixbu-cm' ), '#' . $_GET['l'] . ' ' . $_GET['title'] ) . '...</h4>';
} else if ( ! empty( $_GET['l'] ) ) {
	include 'tpl.tab-edit-course-lesson.php';
	return;
}

/** @var LLMS_Course $course */
global $course;

$sections = [];

$course_sections = $course->get_sections( 'posts' );

$course_lessons = $course->get_lessons();

foreach ( $course_sections as $section ) {
	$sections[ $section->ID ] = $section->post_title;
}
$delete_nonce = wp_create_nonce( "lesson_delete" );

?>
	<table>
		<tr>
			<th><?php _e( 'Lesson title', 'wixbu-cm' ); ?></th>
			<th><?php _e( 'Course', 'wixbu-cm' ); ?></th>
			<th><?php _e( 'Section', 'wixbu-cm' ); ?></th>
			<th></th>
		</tr>
		<?php
		/** @var LLMS_Lesson $lesson */
		foreach ( $course_lessons as $lesson ) {
			$l_id = $lesson->get( 'id' );
			$title = $lesson->get( 'title' )
			?>
			<tr>
				<td><?php echo $title; ?></td>
				<td><?php echo $course->get( 'title' ); ?></td>
				<td><?php echo $sections[ $lesson->get( 'parent_section' ) ]; ?></td>
				<td>
					<a href="<?php echo add_query_arg( [ 'l' => $l_id, ] ) ?>">
						<?php _e( 'edit', 'wixbu-cm' ) ?></a> <span class="sep">|</span>
					<a href="<?php echo add_query_arg( [
						'l' => $l_id,
						'delete_nonce' => $delete_nonce,
						'title' => $title
					] ) ?>"
						 data-confirm="<?php printf( __( 'Are you sure you want to delete lesson %s..?', 'wixbu-cm' ), $title ) ?>"
						 class="confirm-action">
						<?php _e( 'delete', 'wixbu-cm' ) ?></a>
				</td>
			</tr>
			<?php
		}
		?>
	</table>
	<script>
		jQuery( function( $ ) {
			$( '.confirm-action' ).click( function() {
				if ( ! confirm( $( this ).data( 'confirm' ) ) ) {
					return false;
				}
			} );
		} )
	</script>
<?php
