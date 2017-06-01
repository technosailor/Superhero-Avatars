<?php
namespace Technosailor\Superheros\Service_Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Technosailor\Superheros\Theme;

class Theme_Service_Provider implements ServiceProviderInterface {

	public function register( Container $container ) {
		$container['theme.avatar'] = function( Container $container ) {
			return new Theme\Avatar();
		};

		$container['theme.marvel'] = function( Container $container ) {
			return new Theme\Marvel();
		};

		add_action( 'init', function() use ( $container ) {
			$container['theme.avatar']->init();
			$container['theme.marvel']->init();
		} );
	}
}