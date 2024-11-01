<?php
/**
 * @package WP Poker Tournaments
 * @author Key Holdings Inc. <keyholding.randolf@gmail.com>
 * @license GPL-2.0+
 * @copyright 2013 Key Holdings Inc.
 *
 * @wordpress-plugin
 * Plugin Name: WP Poker Tournaments
 * Description: This plugin creates a list of upcoming poker tournaments (FreeRolls, SnG tournaments, …) at many well known poker rooms. Get high-quality content and returning visitors for your website! On top of that you can insert your own affiliate links under individual poker rooms and start making money!
 * Version: 1.0.2
 * Author: Key Holdings Inc.
 * Text Domain: wp-pokertournaments
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /wp-pokertournaments
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/*
 *  Require classes
 */
require_once( plugin_dir_path( __FILE__ ) . 'class-wp-pokertournaments.php' );
require_once( plugin_dir_path( __FILE__ ) . 'class-wp-pokertournaments-admin.php' );

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 *
 */
register_activation_hook( __FILE__, array( 'WP_Pokertournaments', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'WP_Pokertournaments', 'deactivate' ) );

/*
 */
add_action( 'plugins_loaded', array( 'WP_Pokertournaments', 'get_instance' ) );
add_action( 'plugins_loaded', array( 'WP_Pokertournaments_Admin', 'get_instance' ) );