<?php
/*
Plugin Name: Wixbu Course Manager
Plugin URI: http://shramee.me/
Description: Simple plugin starter for quick delivery
Author: Shramee
Version: 1.0.0
Author URI: http://shramee.me/
@developer shramee <shramee.srivastav@gmail.com>
*/

/** Plugin admin class */
require 'inc/class-admin.php';
/** Plugin public class */
require 'inc/class-public.php';

/**
 * Wixbu Course Manager main class
 * @static string $token Plugin token
 * @static string $file Plugin __FILE__
 * @static string $url Plugin root dir url
 * @static string $path Plugin root dir path
 * @static string $version Plugin version
 */
class Wixbu_Course_Manager {

	/** @var Wixbu_Course_Manager Instance */
	private static $_instance = null;

	/** @var string Token */
	public static $token;

	/** @var string Version */
	public static $version;

	/** @var string Plugin main __FILE__ */
	public static $file;

	/** @var string Plugin directory url */
	public static $url;

	/** @var string Plugin directory path */
	public static $path;

	/** @var Wixbu_Course_Manager_Admin Instance */
	public $admin;

	/** @var Wixbu_Course_Manager_Public Instance */
	public $public;

	/**
	 * Return class instance
	 * @return Wixbu_Course_Manager instance
	 */
	public static function instance( $file ) {
		if ( null == self::$_instance ) {
			self::$_instance = new self( $file );
		}
		return self::$_instance;
	}

	/**
	 * Get user meta, defaults to current user for user id
	 * @param string $metakey Meta key to get info for
	 * @param int $user User ID
	 * @return mixed|string User meta
	 */
	public static function umeta( $metakey, $user = 0 ) {
		if ( ! $user ) {
			$user = get_current_user_id();
		}
		if ( $user ) {
			return get_user_meta( $user, $metakey, 'single' );
		}

		return '';
	}

	/**
	 * Get post meta, defaults to current post for post id
	 * @param string $metakey Meta key to get info for
	 * @param int $post Post ID
	 * @return mixed|string User meta
	 */
	public static function pmeta( $metakey, $post = 0 ) {
		global $wixbu_cm_meta;
		if ( $wixbu_cm_meta ) {
			return empty( $wixbu_cm_meta[ $metakey ] ) ? '' : $wixbu_cm_meta[ $metakey ][0];
		}
		if ( ! $post ) {
			$post = get_the_ID();
		}
		if ( $post ) {
			return get_user_meta( $post, $metakey, 'single' );
		}

		return '';
	}

	/**
	 * Get post meta, defaults to current post for post id
	 * @param string $metakey Meta key to get info for
	 * @param int $post Post ID
	 * @return mixed|string User meta
	 */
	public static function pmeta_update( $metakey, $value, $post = 0 ) {
		global $wixbu_cm_meta;

		$wixbu_cm_meta[ $metakey ][0] = $value;
		if ( ! $post ) {
			$post = get_the_ID();
		}
		if ( $post ) {
			return update_post_meta( $post, $metakey, $value );
		}

		return '';
	}

	/**
	 * Get post terms for taxonomy
	 * @param string $taxonomy Taxonomy
	 * @param int $post Post ID
	 * @return mixed|string User meta
	 */
	public static function pterms( $taxonomy, $post = 0 ) {

		$terms = wp_get_post_terms( get_the_ID(), $taxonomy );

		$ret = [];

		/** @var WP_Term $term */
		if ( $terms instanceof WP_Error ) {
			/** @var WP_Error $term */
			echo '<h3 style="color: red;">' . $terms->get_error_message() . '</h3>';
		} else {
			foreach ( $terms as $term ) {
				$ret[ $term->term_id ] = $term->name;
			}
		}

		return $ret;
	}

	/**
	 * Constructor function.
	 * @param string $file __FILE__ of the main plugin
	 * @access  private
	 * @since   1.0.0
	 */
	private function __construct( $file ) {

		self::$token   = 'wixbu-cm';
		self::$file    = $file;
		self::$url     = plugin_dir_url( $file );
		self::$path    = plugin_dir_path( $file );
		self::$version = '1.0.0';

		$this->_admin(); //Initiate admin
		$this->_public(); //Initiate public

	}

	/**
	 * Initiates admin class and adds admin hooks
	 */
	private function _admin() {
		//Instantiating admin class
		$this->admin = Wixbu_Course_Manager_Admin::instance();

		//Enqueue admin end JS and CSS
		add_action( 'admin_enqueue_scripts',	array( $this->admin, 'enqueue' ) );

	}

	/**
	 * Initiates public class and adds public hooks
	 */
	private function _public() {
		//Instantiating public class
		$this->public = Wixbu_Course_Manager_Public::instance();

		//Enqueue front end JS and CSS
		add_action( 'wp_enqueue_scripts',	array( $this->public, 'enqueue' ) );
		add_action( 'init',	array( $this->public, 'register_tax' ) );
		add_shortcode( 'wixbu-course-manager',	array( $this->public, 'course_manager' ) );

	}
}

/** Intantiating main plugin class */
Wixbu_Course_Manager::instance( __FILE__ );
