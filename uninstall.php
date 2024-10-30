<?php

/**
 * Fired when the plugin is uninstalled.
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

$kp_sip_calculator_tools = get_option('kp_sip_calculator_tools');
if( !empty( $kp_sip_calculator_tools['clean_uninstall'] ) && $kp_sip_calculator_tools['clean_uninstall'] == 1 ) {		
    
    delete_option('kp_sip_calculator_options');
    delete_option('kp_sip_calculator_tools');  
}