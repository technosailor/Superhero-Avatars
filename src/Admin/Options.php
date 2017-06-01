<?php
namespace Technosailor\Superheros\Admin;

class Options {

	const NAME = 'marvel-api-keys';
	const PUB = 'marvel-pubkey';
	const PRI = 'marvel-prikey';

	const OPTION = 'superhero_opts';

	private $credentials;

	public function __construct() {
		$this->credentials = get_option( self::OPTION );
	}

	/**
	 * Initialize class
	 */
	public function init() {
		add_action( 'admin_init', [ $this, 'register_settings' ] );
		add_action( 'admin_init', [ $this, 'save' ] );
	}

	/**
	 * Registers the settings group with WordPress
	 */
	public function register_settings() {
		add_settings_section( self::NAME, __( 'Marvel API Authentication', 'superhero-avatars' ), [
			$this,
			'register_fields'
		], 'discussion' );
	}

	/**
	 * Registers new fields on the Discussion page
	 */
	public function register_fields() {
		add_settings_field( self::PUB, __( 'Public Key', 'superhero-avatars' ), [ $this, 'do_pubkey_field' ], 'discussion', self::NAME );
		add_settings_field( self::PRI, __( 'Private Key', 'superhero-avatars' ), [ $this, 'do_prikey_field' ], 'discussion', self::NAME );

		$this->do_nonce_field();
	}

	/**
	 * Renders the Public key field. If MARVEL_PUB_KEY is set, it will be prepopulated and disabled
	 */
	public function do_pubkey_field() {

		$pubkey = false;
		$constant = false;

		if( defined( 'MARVEL_PUB_KEY' ) && false !== MARVEL_PUB_KEY ) {
			$pubkey = MARVEL_PUB_KEY;
			$constant = 'disabled';
		} else {
			if( ! empty( $this->credentials['pubkey'] ) ) {
				$pubkey = $this->credentials['pubkey'];
			}
		}

		echo sprintf( '<input type="password" name="superhero-pubkey" value="%1$s" class="regular-text" %2$s/>', esc_attr( $pubkey ), $constant );
	}

	/**
	 * Renders the Private key field. If MARVEL_PRI_KEY is set, it will be prepopulated and disabled
	 */
	public function do_prikey_field() {
		$prikey = false;
		$constant = false;

		if( defined( 'MARVEL_PRI_KEY' ) && false !== MARVEL_PRI_KEY ) {
			$prikey = MARVEL_PRI_KEY;
			$constant = 'disabled';
		} else {
			if( ! empty( $this->credentials['prikey'] ) ) {
				$prikey = $this->credentials['prikey'];
			}
		}

		echo sprintf( '<input type="password" name="superhero-prikey" value="%1$s" class="regular-text" %2$s/>', esc_attr( $prikey ), $constant );
	}

	/**
	 * Callback renders a nonce
	 */
	public function do_nonce_field() {
		echo sprintf( '<input type="hidden" name="superhero-nonce" value="%s">', wp_create_nonce( self::OPTION ) );
	}

	/**
	 * Saves Auth data to database
	 *
	 * @return bool
	 */
	public function save() {
		if( empty( $_POST['superhero-nonce'] ) ) {
			return false;
		}

		if( ! wp_verify_nonce( $_POST['superhero-nonce'], self::OPTION ) ) {
			return false;
		}

		$auth_keys = [];
		if( ! empty( $_POST['superhero-pubkey'] ) ) {
			$auth_keys['pubkey'] = $_POST['superhero-pubkey'];
		}
		if( ! empty( $_POST['superhero-prikey'] ) ) {
			$auth_keys['prikey'] = $_POST['superhero-prikey'];
		}

		if( empty( $auth_keys ) ) {
			delete_option( self::OPTION );
		} else {
			update_option( self::OPTION, $auth_keys );
		}
	}
}