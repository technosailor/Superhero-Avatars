<?php
namespace Technosailor\Superheros\Service_Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Technosailor\Superheros\Api\Api;

class Api_Service_Provider implements ServiceProviderInterface {

	public function register( Container $container ) {
		$container['api'] = function( Container $container ) {
			return new Api();
		};

		add_action( 'init', function() use ( $container ) {
			$container['api']->init();
		} );
	}
}