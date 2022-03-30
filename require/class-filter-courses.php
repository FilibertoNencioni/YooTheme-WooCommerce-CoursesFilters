<?php

 // If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Filter_Courses {

	//The loader that's responsible for maintaining and registering all hooks that power the plugin.	
	protected $loader;

	//The unique identifier of this plugin.
	protected $plugin_name;

	//The current version of the plugin.	
	protected $version;

	//Define the core functionality of the plugin.
	public function __construct() {
		
        $this->version = '1.0.0';
		
		$this->plugin_name = 'filter-courses';

		$this->load_dependencies();		
		$this->define_modules_hooks();

	}

	//Load the required dependencies for this plugin.
	private function load_dependencies() {

		// The class responsible for orchestrating the actions and filters of the core plugin.
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'require/loader.php';

		// The class responsible for loading YOOtheme Pro modules.
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'require/modules.php';

		$this->loader = new Filter_Courses_Loader();

	}
	
	// Register all of the hooks related to the YOOtheme Pro modules functionality of the plugin.
    private function define_modules_hooks() {
        $plugin_modules = new Filter_Courses_Modules( $this->get_plugin_name(), $this->get_version() );
        $this->loader->add_action( 'after_setup_theme', $plugin_modules, 'load_modules' );
    }	

	// Run the loader to execute all of the hooks with WordPress.
    public function run() {
        $this->loader->run();
    }

    // The name of the plugin used to uniquely identify it within the context of WordPress.
    public function get_plugin_name() {
        return $this->plugin_name;
    }

    // The reference to the class that orchestrates the hooks with the plugin.
    public function get_loader() {
        return $this->loader;
    }

    // Retrieve the version number of the plugin.
    public function get_version() {
        return $this->version;
    }

}