<?php
namespace Technosailor\Superheros\Service_Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Technosailor\Superheros\Admin\Options;

class Admin_Service_Provider implements ServiceProviderInterface {

	public function register( Container $container ) {
		$container['admin.options'] = function( $container ) {
			return new Options();
		};

		add_action( 'init', function() use ( $container ) {
			$container['admin.options']->init();
		} );
	}
}