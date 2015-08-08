<?php

/*******************************************
since 3.3.0
*******************************************/

$bbp_ui = get_option( 'bbpuos_settings' );

if(!defined('bbpuos_PLUGIN_DIR'))
	define('bbpuos_PLUGIN_DIR', dirname(__FILE__));

add_action('wp', 'update_online_users_status');
function update_online_users_status() {
    if (is_user_logged_in()) {
        if (($logged_in_users = get_transient('users_online')) === false) $logged_in_users = array();
        $current_user = wp_get_current_user();
        $current_user = $current_user->ID;
        $current_time = current_time('timestamp');
        if (!isset($logged_in_users[$current_user]) || ($logged_in_users[$current_user] < ($current_time - (5 * 60)))) {
            $logged_in_users[$current_user] = $current_time;
            set_transient('users_online', $logged_in_users, 30 * 60);
        }
    }
}

function is_user_online($user_id) {
    $logged_in_users = get_transient('users_online');
    return isset($logged_in_users[$user_id]) && ($logged_in_users[$user_id] > (current_time('timestamp') - (15 * 60)));
}

add_action('bbp_theme_between_reply_author_details_new', 'bbp_user_online_status');
function bbp_user_online_status(){
    global $bbp_ui;
    echo '<ul>';
    $user_id = bbp_get_reply_author_id($reply_id);
        echo '<li>';
        if (is_user_online($user_id)) {
            echo '<div class="status_online is_online">'.__('Online', 'bbp-new-ui').'</div>';
        } else {
            echo '<div class="status_online is_not_online">'.__('Offline', 'bbp-new-ui').'</div>';
        }
        echo '</li>';
}

add_action('wp_logout', 'set_user_logged_out');
function set_user_logged_out() {
    global $current_user;
    get_currentuserinfo();
    $logged_in_users = get_transient('users_online');
    unset($logged_in_users[$current_user->ID]);
}

add_action('wp_login', 'set_user_logged_in');
function set_user_logged_in() {
    if (($logged_in_users = get_transient('users_online')) === false) $logged_in_users = array();
    $current_user = wp_get_current_user();
    $current_user = $current_user->ID;
    $current_time = current_time('timestamp');
    if (!isset($logged_in_users[$current_user]) || ($logged_in_users[$current_user] < ($current_time - (5 * 60)))) {
        $logged_in_users[$current_user] = $current_time;
        set_transient('users_online', $logged_in_users, 30 * 60);
    }
}





