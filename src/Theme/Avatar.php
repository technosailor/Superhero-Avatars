<?php
namespace Technosailor\Superheros\Theme;

use Technosailor\Superheros\Api\Api;

class Avatar {
	public function init() {
		add_filter( 'get_avatar', [ $this, 'get_avatar' ], 10, 5 );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue' ] );
	}

	public function enqueue() {
		$superherojs_url = ( defined( 'SCRIPT_DEBUG' ) && false !== SCRIPT_DEBUG ) ? MSH_URL . 'assets/js/superhero-avatars.js' : MSH_URL . 'assets/js/superhero-avatars.min.js';
		wp_register_script( 'superheros', $superherojs_url, [ 'js-cookie' ], filemtime( __FILE__ ) );
		wp_localize_script( 'superheros', 'marvel', [] );
		wp_enqueue_script( 'superheros' );

		wp_enqueue_script( 'js-cookie', MSH_URL . 'assets/js/vendor/js-cookie.js', [], filemtime( __FILE__ ) );
	}

	/**
	 * Handles Avatar generation
	 *
	 * @param $avatar
	 * @param $id_or_email
	 * @param $size
	 * @param $default
	 * @param $alt
	 *
	 * @return string
	 */
	public function get_avatar( $avatar, $id_or_email, $size, $default, $alt ) {
		$current_user = wp_get_current_user();

		$url = get_user_meta( $current_user->ID, 'superhero_avatar_url', true );
		if( ! empty( $url ) ) {
			return $this->create_avatar_html( $url, $size, $current_user->display_name );
		}

		$hero = $this->get_a_hero_image( Api::instance()->superheroes );

		if( is_user_logged_in() ) {
			update_user_meta( $current_user->ID, 'superhero_avatar_url', $hero['image_url'] );
		}
		return $this->create_avatar_html( $hero['image_url'], $size, $hero['name'] );
	}

	/**
	 * @param $url
	 * @param $size
	 * @param $alt
	 *
	 * @return string
	 */
	public function create_avatar_html( $url, $size, $alt ) {
		return sprintf( '<img src="%1$s" height="%2$s" width="%2$s" class="avatar avatar-%2$s" style="height:%2$s; width: %2$s" alt="%3$s" title="%3$s" />',
			esc_url( $url ),
			esc_attr( $size ),
			esc_attr( $alt ) );
	}

	/**
	 * Return a random Superhero Image URL
	 *
	 * @param array $heroes
	 *
	 * @return string
	 */
	public function get_a_hero_image( array $heroes ) {

		$hero = [];

		$random = $heroes[array_rand( $heroes, 1 )];
		$ext = $random['thumbnail']['extension'];
		$path = $random['thumbnail']['path'];
		$image_url = $path . '.' . $ext;
		$hero['image_url']  = $image_url;
		$hero['name']       = $random['name'];

		return $hero;
	}
}