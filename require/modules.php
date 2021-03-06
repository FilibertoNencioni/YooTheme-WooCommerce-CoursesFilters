<?php
// If this file is not called by WordPress, die.
if ( !defined( 'WPINC' ) )
	die;

use YOOtheme\Application;

// The admin-specific functionality of the plugin.
class Filter_Courses_Modules {

    // The ID of this plugin.
    private $plugin_name;

    // The version of this plugin.
    private $version;

    // Initialize the class and set its properties.
    public function __construct( $plugin_name, $version ) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    // Loaded the custom modules.
    public function load_modules() {

        // Check if YOOtheme Pro is loaded
        if (!class_exists(Application::class, false)) {
            return;
        }

        // Load all modules
        $app = Application::getInstance();
        $app->load(__DIR__ . '/bootstrap.php');

        // var_dump($app);

    }

}