<?php
/*
 * Plugin Name: Superhero Avatars
 * Plugin URI: #
 * Author: Aaron Brazell
 * Author URI: http://technosailor.com
 * Description: Uses the <a href="">Marvel&trade; API</a> to change avatars into Superhero icons
 * License: MIT
 * Text Domain: superhero-avatars
 * Version: 2.0
 */

// Useful global constants
define( 'MSH_VERSION', '0.2.0' );
define( 'MSH_URL',     plugin_dir_url( __FILE__ ) );
define( 'MSH_PATH',    dirname( __FILE__ ) . '/' );
define( 'MSH_INC',     MSH_PATH . 'includes/' );

require_once trailingslashit( __DIR__ ) . 'vendor/autoload.php';

add_action( 'plugins_loaded', function () {
	superheros()->init();
}, 1, 0 );

function superheros() {
	return \Technosailor\Superheros\Core::instance( new Pimple\Container( [ 'plugin_file' => __FILE__ ] ) );
}