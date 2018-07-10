<?php

$iframe_url = admin_url( 'admin.php?page=llms-course-builder&no_admin_bar&course_id=' . get_the_ID() );

?>
<iframe id="wixbu-cm-course-structure" src="<?php echo $iframe_url ?>"></iframe>