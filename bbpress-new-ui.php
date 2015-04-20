<?php
/*
Plugin Name: bbPress New UI
Description: A great plugin completely changes the entire design bbpress in light or dark color
Version: 3.0.3beta
Author: Daniel 4000
Author URI: http://dk4000.com
Contributors: daniluk4000
Text Domain: bbp-new-ui
Domain Path: /languages
*/
//----------------------------------------
// Constructor
include "inc/answers-topic.php";
class BBP_NEW_UI {
function __construct() {
add_action( 'wp_enqueue_scripts', array( $this, 'register_plugin_styles' ) );
} 
public function register_plugin_styles() {
$val = get_option('bbp_new_ui_option');
$val = $val['checkbox'];
if ( $val == '1'){
$css_path = plugin_dir_path( __FILE__ ) . '/inc/css/dark.css';
wp_enqueue_style( 'bbp_new_ui', plugin_dir_url( __FILE__ ) . '/inc/css/dark.css', filemtime( $css_path ) );
} 
else {
$css_path = plugin_dir_path( __FILE__ ) . '/inc/css/light.css';
wp_enqueue_style( 'bbp_new_ui', plugin_dir_url( __FILE__ ) . '/inc/css/light.css', filemtime( $css_path ) );
} 
}
} // end class

// Load
//----------------------------------------
function bbp_new_ui_load_plugin_textdomain() {
    load_plugin_textdomain( 'bbp-new-ui', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'bbp_new_ui_load_plugin_textdomain' );

//----------------------------------------
// Create plugin settings page
add_action('admin_menu', 'add_plugin_page');

function add_plugin_page(){
add_options_page( 'Settings bbPress New UI', ' bbPress New UI', 'manage_options', 'bbp_new_ui', 'bbp_new_ui_options_page_output' );
}

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
<?php
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
<label>
<input type="checkbox" name="bbp_new_ui_option[checkbox]" value="1" <?php checked( 1, $val['checkbox'] ) ?>  /> <?php _e( 'change style ' ); ?></label> <br>
<?php
if ( $val == '1') {
_e( 'Now active Dark theme' );
} 
else {
_e( 'Now active Light theme' );
}
}
// instantiate our plugin's class
$GLOBALS['bbp_new_ui'] = new BBP_NEW_UI();
?>
