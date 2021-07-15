<?php
/**
 * Plugin Name:  Next Step for LearnDash
 * Plugin URI: https://wptrat.com/learndash-next-step /
 * Description:  Next Step for LearnDash is the best way to change course link for enrolled students and forward them direct to the next step in the course to complete.
 * Author: Luis Rock
 * Author URI: https://wptrat.com/
 * Version: 1.0.0
 * Text Domain: learndash-next-step
 * Domain Path: /languages
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package   Next Step for LearnDash
 */


if ( ! defined( 'ABSPATH' ) ) exit;
		
// Requeiring plugin files
require_once('admin/trns-settings.php');
require_once('includes/functions.php');

//Admin CSS
function trns_enqueue_admin_script( $hook ) {
    global $trns_settings_page;
    if( $hook != $trns_settings_page ) {
        return;
    }
    wp_enqueue_style('trns_admin_style', plugins_url('assets/css/trns-admin.css',__FILE__ ));
}
add_action( 'admin_enqueue_scripts', 'trns_enqueue_admin_script' );



//add hooks and actions
add_filter('post_type_link' , 'trns_link_next_step', 999, 2);