<?php
/*
Plugin Name: bbPress New UI
Description: A great plugin completely changes the entire design bbpress in light or dark color
Version: 3.4.1
Author: Daniel 4000
Author URI: http://dk4000.com
Contributors: daniluk4000
Text Domain: bbp-new-ui
Domain Path: /languages
*/
//----------------------------------------
// Constructor
include "inc/adminui/bbp-admin-answers.php";
include "inc/forumui/new-forum.php";
include "inc/replyui/functions.php";
include "inc/online-status/online.php";

//----------------------------------------
// Add action Link
add_filter( 'plugin_action_links', 'bbpui_plugin_action_links', 10, 2 );
function bbpui_plugin_action_links( $actions, $plugin_file ){
	if( false === strpos( $plugin_file, basename(__FILE__) ) )
		return $actions;
		
	$settings_link = '<a href="options-general.php?page=bbp_new_ui">'.__( 'Settings', 'bbpress' ).'</a>'; 
	array_unshift( $actions, $settings_link ); 
	return $actions; 
}

//----------------------------------------
// Iniciate our plugin class
class BBP_NEW_UI {
function __construct() {
add_action( 'wp_enqueue_scripts', array( $this, 'register_plugin_styles' ) );
add_action( 'bbp_theme_before_footer_content', array( $this, 'bbpui_hide_the_copirytht' ) );
} 
public function register_plugin_styles() {
$val = get_option('bbp_new_ui_option');
$val = $val['1'];
if ( $val == '1'){
$css_path = plugin_dir_path( __FILE__ ) . '/inc/css/dark.css';
wp_enqueue_style( 'bbp_new_ui', plugin_dir_url( __FILE__ ) . '/inc/css/dark.css', filemtime( $css_path ) );
} 
else {
$css_path = plugin_dir_path( __FILE__ ) . '/inc/css/light.css';
wp_enqueue_style( 'bbp_new_ui', plugin_dir_url( __FILE__ ) . '/inc/css/light.css', filemtime( $css_path ) );
} 
}

public function bbpui_hide_the_copirytht() {
$valnew = get_option('bbp_new_ui_option');
$valnew = $valnew['2'];
if ( $valnew == '1') {
echo'
<style>
#bbpress-forums li.bbp-footer::before {
display:none;
}

#bbpress-forums li.bbp-footer {
    min-height: auto;
}
</style>
';
}
}
} // end class

// Notice
//----------------------------------------
add_action('admin_notices', 'bbp_new_ui_admin_notice');

function bbp_new_ui_admin_notice() {
	global $current_user ;
        $user_id = $current_user->ID;
        /* Check that the user hasn't already clicked to ignore the message */
	if ( ! get_user_meta($user_id, 'bbp_new_ui_ignore_notice') ) {
        echo '<div class="updated"><p>'; 
        printf(__('Want to test the new versions of BBP New UI plugin or to add your translation or have an idea/suggestion? Write me in admin@dk4000.com! | <a href="%1$s">Hide notice</a>', 'bbp-new-ui'), '?bbp_new_ui_nag_ignore=0');
        echo "</p></div>";
	}
}

add_action('admin_init', 'bbp_new_ui_nag_ignore');

function bbp_new_ui_nag_ignore() {
	global $current_user;
        $user_id = $current_user->ID;
        /* If user clicks to ignore the notice, add that to their user meta */
        if ( isset($_GET['bbp_new_ui_nag_ignore']) && '0' == $_GET['bbp_new_ui_nag_ignore'] ) {
             add_user_meta($user_id, 'bbp_new_ui_ignore_notice', 'true', true);
	}
}

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
add_options_page( 'bbPress New UI Settings', ' bbPress New UI', 'manage_options', 'bbp_new_ui', 'bbp_new_ui_options_page_output' );
}

function bbp_new_ui_options_page_output(){
?>
<div class="wrap bbpress-new-ui-wrap" id="bbpui">
<div class="imgclass"><img src="http://plugins.svn.wordpress.org/bbpress-new-ui/assets/banner-1544x500.png"></img></div>
<div class="noimgclass">
<style>
#bbpui .updated {
    display: none;
}
#bbpui img {
    width: 50%;
    border-radius: 3px;
    float: right;
    position: absolute;
    right: 10px;
}
.noimgclass {
    float: left;
    width: 50%;
}
status {
    float: left;
    width: 100%;
    white-space: nowrap;
}
status {
    background: #434A68;
    width: auto;
    padding: 0 5px;
    border: 1px solid #434A68 !important;
    color: #fff;
    margin-top: 2px;
}
.noimgclass .submit input {
    box-shadow: 0px 0px;
    border-radius: 0px;
    border: 0px none;
    background: #434A68;
}
.light {
    background: #ddd;
    color: #000;
}
</style>
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
add_settings_section( 'bbp_new_ui_id', '', '', 'bbp_new_ui_page' ); 
add_settings_field('bbp_new_ui_field', 'Change styles', 'fill_bbp_new_ui_field', 'bbp_new_ui_page', 'bbp_new_ui_id' );
add_settings_field('bbp_new_ui_field_1', 'Hide the copyright', 'fill_bbp_new_ui_field_1', 'bbp_new_ui_page', 'bbp_new_ui_id' );
}
add_action('admin_init', 'plugin_settings');

function fill_bbp_new_ui_field(){
$val = get_option('bbp_new_ui_option');
$locale = get_locale();
$val = $val['1'];
$posts = get_posts();
?>
<label>

<input type="checkbox" name="bbp_new_ui_option[1]" value="1" <?php checked( 1, $val ) ?>  /> <?php _e( 'Change style to Dark color', 'bbp-new-ui'); ?></label> <br>
<?php
if ( $val == '1') {
echo'<status class="dark">';_e( 'Now active Dark Theme', 'bbp-new-ui' );echo'</status>';
} 
else {
echo'<status class="light">';_e( 'Now active Light Theme', 'bbp-new-ui' );echo'</status>';
}
}

function fill_bbp_new_ui_field_1(){
$valnew = get_option('bbp_new_ui_option');
$valnew = $valnew['2'];
?>
<label>

<input type="checkbox" name="bbp_new_ui_option[2]" value="1" <?php checked( 1, $valnew ) ?>  /> <?php _e( 'Hide', 'bbp-new-ui'); ?> </label></div><br>
<?php
}
// instantiate our plugin's class
$GLOBALS['bbp_new_ui'] = new BBP_NEW_UI();
?>