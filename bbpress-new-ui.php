<?php
/*
Plugin Name: bbPress New UI (Dark)
Description: A small plugin completely changes the entire design bbpress.
Version: 1.5.1
Author: Daniel 4000
Author URI: http://vk.com/daniluk4000
Contributors: daniluk4000, Snusmoomrik
*/
class BBP_NEW_UI {

	/*--------------------------------------------*
	 * Constructor
	 *--------------------------------------------*/

	/**
	 * Initializes the plugin by setting localization, filters, and administration functions.
	 */
	function __construct() {

		// register css files
		add_action( 'wp_enqueue_scripts', array( $this, 'register_plugin_styles' ) );

	} // end constructor

	/**
	 * Load the plugin's CSS files
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public function register_plugin_styles() {
		$css_path = plugin_dir_path( __FILE__ ) . '/style.css';
	    wp_enqueue_style( 'bbp_new_ui', plugin_dir_url( __FILE__ ) . '/style.css', filemtime( $css_path ) );
	}

} // end class
// instantiate our plugin's class
$GLOBALS['bbp_new_ui'] = new BBP_NEW_UI();