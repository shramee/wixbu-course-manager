<h1>Your Courses</h1>
<?php
if ( isset( $_GET['action'] ) && isset( $_GET['nonce'] ) && isset( $_GET['c'] ) ) {
	$action = $_GET['action'];
	$course = $_GET['c'];
	if ( wp_verify_nonce( $_GET['nonce'], "course_action_$course" ) ) {
		switch ( $action ) {
			case 'delete':
				wp_delete_post( $course, true );
				break;
			case 'publish':
				wp_update_post( array(
					'ID'          => $course,
					'post_status' => 'publish'
				) );
				break;
		}
	}
}

$query = new WP_Query( [
	'post_type' => 'course',
	'status' => 'any',
//	'author' => get_current_user_id(),
] );

?>
	<div class="wixbu-cm-your-courses llms-loop">
		<?php
		if ( $query->have_posts() ) {
			?>
			<ul class="llms-loop-list llms-course-list cols-3">
				<?php
				while ( $query->have_posts() ) {
					$query->the_post();

					/** @var WP_Post $post */
					global $post;

					$id = $post->ID;

					$course_nonce = 'nonce=' . wp_create_nonce( "course_action_$id" );

					$course = new LLMS_Course( $post );

					?>
					<li class="llms-loop-item post-42 course type-course status-publish has-post-thumbnail hentry course_difficulty-beginner is-enrolled is-incomplete">
						<div class="llms-loop-item-content">

							<a class="llms-loop-link" href="<?php echo get_the_permalink() ?>">
								<?php the_post_thumbnail( 'medium' ) ?>
								<?php the_title( '<h4 class="llms-loop-title">', '</h4>' ) ?>

								<footer class="llms-loop-item-footer">
									<div class="llms-meta llms-course-length">
										<p><?php printf( __( 'Estimated Time: <span class="length">%s</span>', 'lifterlms' ), $course->get( 'length' ) ); ?></p>
									</div>
								</footer>

							</a><!-- .llms-loop-link -->
						</div><!-- .llms-loop-item-content -->
						<div class="course-actions course-<?php echo get_post_status() ?>">
							<a href="<?php echo "?c=$id&tab=edit-course" ?>">
								<?php _e( 'edit', 'wixbu-cm' ) ?></a> <span class="sep">|</span>
							<a href="<?php echo "?c=$id&$course_nonce&action=delete" ?>" data-confirm="<?php _e( 'Are you sure you want to delete this course..?', 'wixbu-cm' ) ?>" class="confirm-action">
								<?php _e( 'delete', 'wixbu-cm' ) ?></a> <span class="sep">|</span>
							<a href="<?php echo "?c=$id&$course_nonce&action=publish" ?>">
								<?php _e( 'publish', 'wixbu-cm' ) ?></a>
						</div>
					</li>
					<?php
				}
				?>
			</ul>
			<?php
		} else {
			?>
			<h2><?php _e( 'Sorry, you don\'t have any courses yet.' ) ?></h2>
			<?php
		}
		?>
	</div>
</div>

<script>
	jQuery( function( $ ) {
		$( '.confirm-action' ).click( function() {
			if ( ! confirm( $( this ).data( 'confirm' ) ) ) {
				return false;
			}
		} );
	} )
</script>

<div class="llms-form-field new-course-button-wrap">
	<a href="?tab=new-course" class="llms-button-action" id="new-course-button"><?php _e( 'New course' ) ?></a>