<?php
/**
 * Plugin Name: Mega Widget
 * Description: A small plugin to display all core widgets.
 * Version: 1.0.0
 * Author: Nilambar Sharma
 * Author URI: https://www.nilambar.net/
 * License: GPLv2 or later
 * Text Domain: mega-widget
 *
 * @package Mega_Widget
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'MEGA_WIDGET_BASENAME', plugin_basename( __FILE__ ) );
define( 'MEGA_WIDGET_PLUGIN_FILE', __FILE__ );
define( 'MEGA_WIDGET_DIR', rtrim( plugin_dir_path( __FILE__ ), '/' ) );
define( 'MEGA_WIDGET_URL', rtrim( plugin_dir_url( __FILE__ ), '/' ) );

require_once MEGA_WIDGET_DIR . '/inc/class-mega-widget.php';

/**
 * Register widget.
 *
 * @since 1.0.0
 */
function mega_widget_register() {
	register_widget( 'Mega_Widget' );
}

add_action( 'widgets_init', 'mega_widget_register' );
