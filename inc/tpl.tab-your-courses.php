<h1>Your Courses</h1>
<?php
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
							<a href="?course=<?php the_ID() ?>&action=edit"><?php _e( 'edit', 'wixbu-course-manager' ) ?></a> <span class="sep">|</span>
							<a href="?course=<?php the_ID() ?>&action=delete"><?php _e( 'delete', 'wixbu-course-manager' ) ?></a> <span class="sep">|</span>
							<a href="?course=<?php the_ID() ?>&action=publish"><?php _e( 'publish', 'wixbu-course-manager' ) ?></a>
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
<div class="llms-form-field new-course-button-wrap">
	<a href="?tab=new-course" class="llms-button-action" id="new-course-button"><?php _e( 'New course' ) ?></a>