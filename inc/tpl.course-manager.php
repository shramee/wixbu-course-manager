<?php
/**
 * Templates for wixbu-course-manager shortcode
 */

$tabs = apply_filters( 'wixbu_course_builder_tabs', [
	'your-courses' => __( 'Your courses', 'wixbu-cm' ),
	'new-course'   => __( 'New course', 'wixbu-cm' ),
	'profile'        => __( 'Instructor profile', 'wixbu-cm' ),
//	'notifications' => __( 'Notifications', 'wixbu-cm' ),
] );
?>

	<div class="lifterlms">
		<div class="llms-student-dashboard wixbu-cm">
			<header class="llms-sd-header">
				<nav class="llms-sd-nav">
					<ul class="llms-sd-items">
						<?php
						$current = filter_input( INPUT_GET, 'tab' );;

						if ( ! $current || empty( $tabs[ $current ] ) ) {
							$current = key( $tabs );
						}

						foreach ( $tabs as $k => $tab ) {
							?>
							<li class="llms-sd-item edit-account tab-<?php echo $k == $current ? "$k current" : $k; ?>">
								<a class="llms-sd-link" href="?tab=<?php echo $k ?>"><?php echo $tab ?></a>
							</li>
							<?php
						}
						?>
					</ul>


				</nav>
				<h2 class="llms-sd-title">Earnings report</h2>
			</header>
			<div id="wixbu-cm-<?php echo $current ?>" class="wixbu-dash-content-wrap wixbu-cm-content-wrap">
					<?php
					do_action( "wixbu_cm_tab_$current" );
					$file = __DIR__ . "/tpl.tab-$current.php";
					if ( file_exists( $file ) ) {
						require $file;
					}
					?>
			</div>
		</div>
	</div>

<?php
