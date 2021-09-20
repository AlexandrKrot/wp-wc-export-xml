<?php
/**
 * @package utm
 * @version 1
 */
/*
Plugin Name: Pechenki export
Description: Prom xml Generation
Author: Pechenki
Version: 0.1
Author URI: https://pechenki.top/
*/
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


define( 'PH_DIR', plugin_dir_path( __FILE__ ) );
require_once( PH_DIR . 'functions/class.php' );
require_once( PH_DIR . 'functions/html.php' );


$PechenkiExport = PechenkiExport::instance();
