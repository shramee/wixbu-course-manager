<?php

/**
 * Wixbu Course Manager public class
 */
class Wixbu_Course_Manager_Public{

	/** @var Wixbu_Course_Manager_Public Instance */
	private static $_instance = null;

	/* @var string $token Plugin token */
	public $token;

	/* @var string $url Plugin root dir url */
	public $url;

	/* @var string $path Plugin root dir path */
	public $path;

	/* @var string $version Plugin version */
	public $version;

	/**
	 * Wixbu Course Manager public class instance
	 * @return Wixbu_Course_Manager_Public instance
	 */
	public static function instance() {
		if ( null == self::$_instance ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Constructor function.
	 * @access  private
	 * @since   1.0.0
	 */
	private function __construct() {
		$this->token   =   Wixbu_Course_Manager::$token;
		$this->url     =   Wixbu_Course_Manager::$url;
		$this->path    =   Wixbu_Course_Manager::$path;
		$this->version =   Wixbu_Course_Manager::$version;
	}

	/**
	 * Adds front end stylesheet and js
	 * @action wp_enqueue_scripts
	 */
	public function enqueue() {
		$token = $this->token;
		$url = $this->url;

		wp_enqueue_style( $token . '-css', $url . '/assets/front.css' );

		wp_enqueue_script( $token . '-js', $url . '/assets/front.js', array( 'jquery' ) );
	}

	public function register_tax() {
		$labels = array(
			'name'              => _x( 'Software requirements', 'taxonomy general name', 'textdomain' ),
			'singular_name'     => _x( 'Software requirement', 'taxonomy singular name', 'textdomain' ),
			'search_items'      => __( 'Search Software requirements', 'textdomain' ),
			'all_items'         => __( 'All Software requirements', 'textdomain' ),
			'parent_item'       => __( 'Parent Software requirement', 'textdomain' ),
			'parent_item_colon' => __( 'Parent Software requirement:', 'textdomain' ),
			'edit_item'         => __( 'Edit Software requirement', 'textdomain' ),
			'update_item'       => __( 'Update Software requirement', 'textdomain' ),
			'add_new_item'      => __( 'Add New Software requirement', 'textdomain' ),
			'new_item_name'     => __( 'New Software requirement Name', 'textdomain' ),
			'menu_name'         => __( 'Software requirement', 'textdomain' ),
		);

		$args = array(
			'hierarchical'      => false,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'software_req' ),
		);

		register_taxonomy( 'software_req', array( 'course' ), $args );
	}

	/**
	 * Renders wixbu-course-manager shortcode
	 */
	public function course_manager() {
		$this->enqueue();
		include 'tpl.course-manager.php';
	}
}