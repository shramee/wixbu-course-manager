<?php
global $wixbu_cm_meta;

if ( ! empty( $_POST['save'] ) && wp_verify_nonce( $_POST['save'], 'wixbu-cm-course-plans' ) ) {
	$course = new LLMS_Product( get_the_ID() );

	$access_plans = $course->get_access_plans();

	$price = '';

	$plan_found = false;

	/** @var LLMS_Access_Plan $access_plan */
	foreach ( $access_plans as $access_plan ) {

		if ( ! $access_plan->can_expire() ) {
			$plan_found = true;
			$access_plan->set( 'price', $_POST['price'] );
		}
	}

	if ( ! $plan_found ) {
		$plan = new LLMS_Access_Plan( 'new', [ 'post_title' => __( 'One-Time Payment', 'lifterlms' ), ] );
		$props = [
			'post_title'                => __( 'One-Time Payment', 'lifterlms' ),
			'access_expiration'         => 'lifetime',
			'availability'              => 'open',
			'availability_restrictions' => array(),
			'content'                   => '',
			'enroll_text'               => __( 'Enroll', 'lifterlms' ),
			'featured'                  => 'no',
			'frequency'                 => 0,
			'is_free'                   => 'no',
			'product_id'                => $course->get( 'id' ),
			'sku'                       => 'course-' . $course->get( 'id' ) . '-purchase',
			'price'                     => $_POST['price'],
			'trial_offer'               => 'no',
		];

		foreach ( $props as $prop => $val ) {
			$plan->set( $prop, $val );
		}
		$plan->set( 'product_id', $course->get( 'id' ) );
		$access_plans = $course->get_access_plans();
	}

}

/** @var LLMS_Product $course */
$course = new LLMS_Product( get_the_ID() );

$access_plans = $course->get_access_plans();

$price = '';

/** @var LLMS_Access_Plan $access_plan */
foreach ( $access_plans as $access_plan ) {
	if ( ! $access_plan->can_expire() ) {
		$price = $access_plan->get( 'price' );
	}
}

?>
<form class="wixbu-cm-center-form" method="post" enctype="multipart/form-data">

	<div class="llms-cols-7 llms-form-field">
		<label><?php _e( 'Course cost', 'wixbu-cm' ) ?></label>
		<input required="required" step=".01" type="number" name="price" value="<?php echo $price; ?>">
	</div>

	<div class="llms-form-field llms-cols-4">
		<button name="save" value="<?php echo wp_create_nonce( 'wixbu-cm-course-plans' ) ?>" type="submit" class="llms-button-action">
			<?php _e( 'Save', 'wixbu-course-manager' ) ?>
		</button>
	</div>
</form>
