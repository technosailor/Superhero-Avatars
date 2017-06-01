<?php
namespace Technosailor\Superheros\Service_Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Technosailor\Superheros\Ajax\Ajax;

class Ajax_Service_Provider implements ServiceProviderInterface {

	public function register( Container $container ) {
		$container['ajax'] = function( Container $container ) {
			return new Ajax();
		};

		add_action( 'init', function() use ( $container ) {
			$container['ajax']->init();
		} );
	}
}