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

require_once trailingslashit( __DIR__ ) . 'vendor/autoload.php';

add_action( 'plugins_loaded', function () {
	superheros()->init();
}, 1, 0 );

function superheros() {
	return \Technosailor\Superheros\Core::instance( new Pimple\Container( [ 'plugin_file' => __FILE__ ] ) );
}