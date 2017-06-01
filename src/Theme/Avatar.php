<?php
namespace Technosailor\Superheros\Theme;

use Technosailor\Superheros\Api\Api;

class Avatar {
	public function init() {
		add_filter( 'get_avatar', array( $this, 'get_avatar' ), 10, 5 );
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
		$current_user = get_current_user();

		if( $url = get_user_meta( $current_user->ID, 'superhero_avatar_url', true ) ) {
			return $this->create_avatar_html( $url, $size, $current_user->display_name );
		}

		$api = Api::instance();
		$superheros = $api->superheros;

		$random = $superheros[array_rand( $superheros, 1 )];
		$ext = $random->thumbnail->extension;
		$path = $random->thumbnail->path;
		$image_url = $path . '.' . $ext;
		if( is_user_logged_in() ) {
			update_user_meta( $current_user, 'superhero_avatar_url', true );
		}
		return $this->create_avatar_html( $image_url, $size, $random->name );
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
}