<?php
/*
Plugin Name: bbPress New UI
Description: A great plugin completely changes the entire design bbpress in light or dark color
Version: 2.1
Author: Daniel 4000
Author URI: https://profiles.wordpress.org/daniluk4000/
Contributors: daniluk4000, WPscript

*/
//----------------------------------------
// Constructor
class BBP_NEW_UI {
 function __construct() {
 add_action( 'wp_enqueue_scripts', array( $this, 'register_plugin_styles' ) );
 } 
 public function register_plugin_styles() {
 $val = get_option('bbp_new_ui_option');
 $val = $val['checkbox'];
 if ( $val['checkbox'] == '1'){
 $css_path = plugin_dir_path( __FILE__ ) . '/css/light.css';
 wp_enqueue_style( 'bbp_new_ui', plugin_dir_url( __FILE__ ) . '/css/light.css', filemtime( $css_path ) );
 } 
 else {
 $css_path = plugin_dir_path( __FILE__ ) . '/css/dark.css';
 wp_enqueue_style( 'bbp_new_ui', plugin_dir_url( __FILE__ ) . '/css/dark.css', filemtime( $css_path ) );
 } 
 }
} // end class
// instantiate our plugin's class
$GLOBALS['bbp_new_ui'] = new BBP_NEW_UI();
//----------------------------------------
// Create plugin settings page
function add_plugin_page(){
add_options_page( 'Settings bbPress New UI', ' bbPress New UI', 'manage_options', 'bbp_new_ui', 'bbp_new_ui_options_page_output' );
}
add_action('admin_menu', 'add_plugin_page');
function bbp_new_ui_options_page_output(){
?>
<div class="wrap">
<h2><?php echo get_admin_page_title() ?></h2>
<form action="options.php" method="POST">
<?php settings_fields( 'bbp_new_ui_group' ); ?>
<?php do_settings_sections( 'bbp_new_ui_page' ); ?>
<?php submit_button(); ?>
</form>
</div>
<?
}
// Register Settings
//----------------------------------------
function plugin_settings(){ 
register_setting( 'bbp_new_ui_group', 'bbp_new_ui_option' );
add_settings_section( 'bbp_new_ui_id', 'General Settings bbPress New UI', '', 'bbp_new_ui_page' ); 
add_settings_field('bbp_new_ui_field', 'Change styles', 'fill_bbp_new_ui_field', 'bbp_new_ui_page', 'bbp_new_ui_id' );
}
add_action('admin_init', 'plugin_settings');

function fill_bbp_new_ui_field(){
$val = get_option('bbp_new_ui_option');
$val = $val['checkbox'];
?>
<label><input type="checkbox" name="bbp_new_ui_option[checkbox]" value="1" <? checked( 1, $val ) ?> /> change style</label> <br>
<?
 if ( $val['checkbox'] == '1'){
 echo "Now active light.css";
 } 
 else {
 echo "Now active dark.css";
} 
}