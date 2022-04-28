<?php 
/**
 *
 *  @wordpress-plugin
 * Plugin Name: Filter courses by Emm&mmE Informatica
 * Plugin UTI:
 * Tags:
 * Version: 1.2.2
 * Author: Filiberto Nencioni
 * License: 
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

//Viene eseguito durante l'attivazione del plugin
function activate_filter_courses() {
	require_once plugin_dir_path( __FILE__ ) . 'require/activator.php';
	Activator::activate();
}

//Viene eseguito durante la disattivazione del plugin
function deactivate_filter_courses() {
	require_once plugin_dir_path( __FILE__ ) . 'require/deactivator.php';
	Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_filter_courses' );
register_deactivation_hook( __FILE__, 'deactivate_filter_courses' );

//The core plugin class that is used to define hooks.
require plugin_dir_path( __FILE__ ) . 'require/class-filter-courses.php';

//Begins execution of the plugin.
function build_filter_courses() {

	$plugin = new Filter_Courses();
	$plugin->run();

}
build_filter_courses();
?>