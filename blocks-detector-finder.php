<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://elementinvader.com
 * @since             1.0.0
 * @package           Blocks_Plugin_Detector_Finder
 *
 * @wordpress-plugin
 * Plugin Name:       Blocks Detector Finder
 * Plugin URI:        https://elementdetector.com
 * Description:       Detect / Find Gutenberg Blocks used on pages, also detect not used Gutenberg Blocks or Missing Gutenberg Blocks.
 * Version:           1.0.0
 * Author:            ElementInvader & FreelancersTools (Ivica Delić)
 * Author URI:        https://elementinvader.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       blocks-detector-finder
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'BLOCKS_PLUGIN_DETECTOR_FINDER_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-blocks-plugin-detector-finder-activator.php
 */
function activate_blocks_plugin_detector_finder() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-blocks-plugin-detector-finder-activator.php';
	Blocks_Plugin_Detector_Finder_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-blocks-plugin-detector-finder-deactivator.php
 */
function deactivate_blocks_plugin_detector_finder() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-blocks-plugin-detector-finder-deactivator.php';
	Blocks_Plugin_Detector_Finder_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_blocks_plugin_detector_finder' );
register_deactivation_hook( __FILE__, 'deactivate_blocks_plugin_detector_finder' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-blocks-plugin-detector-finder.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_blocks_plugin_detector_finder() {

	$plugin = new Blocks_Plugin_Detector_Finder();
	$plugin->run();

}
run_blocks_plugin_detector_finder();
