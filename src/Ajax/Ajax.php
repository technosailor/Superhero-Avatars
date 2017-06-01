<?php
namespace Technosailor\Superheros\Ajax;

use Technosailor\Superheros\Api\Api;
use Technosailor\Superheros\Theme\Avatar;

class Ajax {
	public function init() {
		add_action( 'wp_ajax_get_a_hero', [ $this, 'get_a_hero' ] );
		add_action( 'wp_ajax_nopriv_get_a_hero', [ $this, 'get_a_hero' ] );
	}

	public function get_a_hero() {
		$hero = Avatar::instance()->get_a_hero_image( Api::instance()->superheroes );
		wp_send_json_success( $hero );
	}
}