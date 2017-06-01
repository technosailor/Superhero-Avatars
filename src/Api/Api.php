<?php
namespace Technosailor\Superheros\Api;

use Technosailor\Superheros\Admin\Options;
use Marvel\Client;

class Api {

	const CACHE_KEY = 'superheros';
	const CACHE_GROUP = 'marvel';

	public $superheroes;

	private $pubkey;
	private $prikey;
	protected $client;

	public function init() {
		$this->pubkey = $this->get_pubkey();
		$this->prikey = $this->get_prikey();

		$this->client = new Client( $this->pubkey, $this->prikey );

		$this->superheroes = $this->get_superheroes();
	}

	/**
	 * Return a usable pubkey
	 *
	 * @return bool|string
	 */
	private function get_pubkey() {
		if( defined( 'MARVEL_PUB_KEY' ) && ! empty( MARVEL_PUB_KEY ) ) {
			return MARVEL_PUB_KEY;
		} else {
			$auth = get_option( Options::NAME );
			if( ! empty( $auth['pubkey'] ) ) {
				return $auth['pubkey'];
			}
		}

		return false;
	}

	/**
	 * Return a usable prikey
	 *
	 * @return bool|string
	 */
	private function get_prikey() {
		if( defined( 'MARVEL_PRI_KEY' ) && ! empty( MARVEL_PRI_KEY ) ) {
			return MARVEL_PRI_KEY;
		} else {
			$auth = get_option( Options::NAME );
			if( ! empty( $auth['prikey'] ) ) {
				return $auth['prikey'];
			}
		}

		return false;
	}

	/**
	 * Retrieves and caches JSON from Marvel
	 *
	 * @param bool $force_cache
	 *
	 * @return bool|mixed
	 */
	protected function get_superheroes( bool $force_cache = false ) {

		$superheroes = wp_cache_get( self::CACHE_KEY, self::CACHE_GROUP );
		if( empty( $superheroes ) || false !== $force_cache ) {
			$superheroes = $this->client->characters->index( 1, 100 )->data;

			if( ! empty( $superheroes ) ) {
				wp_cache_set( self::CACHE_KEY, $superheroes, self::CACHE_GROUP, DAY_IN_SECONDS );
			}
		}

		return $superheroes;
	}

	/**
	 * @return General
	 */
	public static function instance() {
		return superheros()->container()['api'];
	}
}