<?php
namespace Technosailor\Superheros;

use Technosailor\Superheros\Service_Providers\Admin_Service_Provider;
use Technosailor\Superheros\Service_Providers\Api_Service_Provider;
use Technosailor\Superheros\Service_Providers\Theme_Service_Provider;

class Core {

	protected static $_instance;

	/** @var \Pimple\Container */
	protected $container = null;

	public function __construct( $container ) {
		$this->container = $container;
	}

	public function init() {
		$this->load_service_providers();
	}

	public function container() {
		return $this->container;
	}

	public function load_service_providers() {
		$this->container->register( new Api_Service_Provider() );
		$this->container->register( new Admin_Service_Provider() );
		$this->container->register( new Theme_Service_Provider() );
	}

	/**
	 * @param null|\ArrayAccess $container
	 *
	 * @return Core
	 * @throws \Exception
	 */
	public static function instance( $container = null ) {
		if ( ! isset( self::$_instance ) ) {
			if ( empty( $container ) ) {
				throw new \Exception( 'You need to provide a Pimple container' );
			}
			$className       = __CLASS__;
			self::$_instance = new $className( $container );
		}
		return self::$_instance;
	}

}