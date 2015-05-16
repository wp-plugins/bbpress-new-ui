<?php
/*
Plugin Name: bbPress New UI
Description: A great plugin completely changes the entire design bbpress in light or dark color
Version: 3.1.1.1
Author: Daniel 4000
Author URI: http://dk4000.com
Contributors: daniluk4000
Text Domain: bbp-new-ui
Domain Path: /languages
*/
//----------------------------------------
// Constructor
include "inc/adminui/answers-topic.php";
include "inc/forumui/functions.php";
class BBP_NEW_UI {
function __construct() {
add_action( 'wp_enqueue_scripts', array( $this, 'register_plugin_styles' ) );
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
} // end class

// Notice
//----------------------------------------
add_action('admin_notices', 'new_ui_admin_notice');

function new_ui_admin_notice() {
	global $current_user ;
        $user_id = $current_user->ID;
        /* Check that the user hasn't already clicked to ignore the message */
	if ( ! get_user_meta($user_id, 'new_ui_ignore_notice') ) {
        echo '<div class="updated"><p>'; 
        printf(__('Want to test the new versions of BBP New UI plugin? Want to add your translation or have an idea/suggestion? Write me in admin@dk4000.com! | <a href="%1$s">Hide notice</a>', 'bbp-new-ui'), '?new_ui_nag_ignore=0');
        echo "</p></div>";
	}
}

add_action('admin_init', 'new_ui_nag_ignore');

function new_ui_nag_ignore() {
	global $current_user;
        $user_id = $current_user->ID;
        /* If user clicks to ignore the notice, add that to their user meta */
        if ( isset($_GET['new_ui_nag_ignore']) && '0' == $_GET['new_ui_nag_ignore'] ) {
             add_user_meta($user_id, 'new_ui_ignore_notice', 'true', true);
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
$locale = get_locale();
$val = $val['1'];
$posts = get_posts();
?>
<label>

<input type="checkbox" name="bbp_new_ui_option[1]" value="1" <?php checked( 1, $val ) ?>  /> <?php if( $locale == "ru_RU" ) :
	  echo 'сменить стиль на темный'; elseif( $locale == "de_DE" ) : echo 'ändere Style zu Dark Theme'; elseif( $locale == "pt_BR" ): echo 'Obscurecer Tema'; else: echo 'change style to Dark Color';
endif; ?></label> <br>
<?php
if ( $val == '1') {
if( $locale == "ru_RU" ) {
_e( 'Сейчас активна темная тема' ); }
elseif( $locale == "de_DE" ) {
_e( 'Jetzt aktives Dark Theme' ); }
elseif( $locale == "pt_BR" ) {
_e( 'Tema Escuro Ativado' ); }
else {
_e( 'Now active Dark theme' );
}
} 
else {
if( $locale == "ru_RU" ) {
_e( 'Сейчас активна светлая тема' ); }
elseif( $locale == "de_DE" ) {
_e( 'Jetzt aktives Light Theme' ); }
elseif( $locale == "pt_BR" ) {
_e( 'Tema Claro Ativado' ); }
else {
_e( 'Now active Light theme' );
}
}
}
// instantiate our plugin's class
$GLOBALS['bbp_new_ui'] = new BBP_NEW_UI();
?>