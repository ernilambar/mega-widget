<?php
/**
 * Plugin Name: Mega Widget
 * Description: A small plugin to display all core widgets.
 * Version: 1.0.4
 * Requires at least: 6.3
 * Requires PHP: 7.2
 * Author: Nilambar Sharma
 * Author URI: https://www.nilambar.net/
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: mega-widget
 * Domain Path: /languages
 *
 * @package Mega_Widget
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'MEGA_WIDGET_VERSION', '1.0.4' );
define( 'MEGA_WIDGET_BASENAME', plugin_basename( __FILE__ ) );
define( 'MEGA_WIDGET_PLUGIN_FILE', __FILE__ );
define( 'MEGA_WIDGET_DIR', rtrim( plugin_dir_path( __FILE__ ), '/' ) );
define( 'MEGA_WIDGET_URL', rtrim( plugin_dir_url( __FILE__ ), '/' ) );

/**
 * Register widget.
 *
 * @since 1.0.0
 */
add_action(
	'widgets_init',
	function () {
		require_once MEGA_WIDGET_DIR . '/inc/class-mega-widget.php';

		register_widget( 'Mega_Widget' );
	}
);
