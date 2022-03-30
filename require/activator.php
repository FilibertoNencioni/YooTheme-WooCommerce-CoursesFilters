<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Activator {

	// Check whether YOOtheme Pro is installed.
	public static function activate() {
		
        $themeData = wp_get_theme('yootheme');
    
        if( !$themeData ) {
            deactivate_plugins( plugin_basename( __FILE__ ) );
            wp_die( __( 'You need YOOtheme Pro ', 'textdomain' ) );
        }
		
	}

}
