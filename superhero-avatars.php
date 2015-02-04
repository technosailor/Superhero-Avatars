<?php
/*
 * Plugin Name: Superhero Avatars
 * Plugin URI: #
 * Author: Aaron Brazell
 * Author URI: http://technosailor.com
 * Description: Uses the <a href="">Marvel&trade; API</a> to change avatars into Superhero icons
 * License: MIT
 * Text Domain: superhero-avatars
 */

class Superhero_Avatars {

	public $pubkey;
	public $prikey;

	const ENDPOINT = 'http://gateway.marvel.com/v1/';

	/**
	 *
	 */
	public function __construct() {
		$superhero_opts = get_option( 'superhero_opts' );

		$this->pubkey = ( array_key_exists( 'pubkey', $superhero_opts ) ) ? (string) $superhero_opts['pubkey'] : false;
		$this->prikey = ( array_key_exists( 'prikey', $superhero_opts ) ) ? (string) $superhero_opts['prikey'] : false;
		$this->hooks();
	}

	/**
	 *
	 */
	public function hooks() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_init', array( $this, 'save' ) );
		add_filter( 'get_avatar', array( $this, 'get_avatar' ), 10, 5 );
		add_action( 'init', array( $this, 'i18n' ) );
		add_action( 'wp_footer', array( $this, 'marvel_attribution' ) );
	}

	/**
	 *
	 */
	public function i18n() {
		load_plugin_textdomain( 'superhero-avatars', false, dirname( plugin_basename( __FILE__) ) . '/languages/' );
	}

	/**
	 *
	 */
	public function admin_menu() {
		add_options_page( __( 'Superhero Avatars', 'superhero-avatars' ), __( 'Superhero Avatars', 'superhero-avatars' ), 'manage_options', 'superhero-options', array( $this, 'admin' ) );
	}

	/**
	 *
	 */
	public function admin() {
		$nonce = wp_create_nonce( 'superhero-settings-save' );
		?>
		<div class="wrap">
			<h2><?php _e( 'Superhero Avatars', 'superhero-avatars' ) ?></h2>
			<form action="" method="post">
				<input type="hidden" name="_wp_superhero_nonce" value="<?php echo $nonce ?>" />
				<table class="form-table">
					<tr valign="top">
						<th scope="row"><?php _e( 'Public Key', 'superhero-avatars' ) ?></th>
						<td>
							<input type="password" name="superhero-pubkey" value="<?php echo esc_attr( $this->pubkey ) ?>" class="regular-text" />
						</td>
					</tr>

					<tr valign="top">
						<th scope="row"><?php _e( 'Private Key', 'superhero-avatars' ) ?></th>
						<td>
							<input type="password" name="superhero-prikey" value="<?php echo esc_attr( $this->prikey ) ?>" class="regular-text" />
						</td>
					</tr>
				</table>
				<?php submit_button(); ?>
			</form>
		</div>
		<?php
	}

	/**
	 * @return bool
	 */
	public function save() {
		if( !wp_verify_nonce( $_POST['_wp_superhero_nonce'], 'superhero-settings-save' ) )
			return false;

		$settings = array();
		if( array_key_exists( 'superhero-pubkey', $_POST ) )
			$settings['pubkey'] = $_POST['superhero-pubkey'];

		if( array_key_exists( 'superhero-prikey', $_POST ) )
			$settings['prikey'] = $_POST['superhero-prikey'];

		update_option( 'superhero_opts', $settings );
	}

	/**
	 * @param $avatar
	 * @param $id_or_email
	 * @param $size
	 * @param $default
	 * @param $alt
	 *
	 * @return string
	 */
	public function get_avatar( $avatar, $id_or_email, $size, $default, $alt ) {
		global $current_user;
		if( $url = get_user_meta( $current_user->ID, 'superhero_avatar_url', true ) )
			return $this->create_avatar_html( $url, $size, $current_user->display_name );

		$s = $this->get_superheroes();

		$random = $s[array_rand( $s, 1 )];
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

	/**
	 * @return array
	 */
	public function get_superheroes() {
		$return = wp_cache_get( 'superheroes', 'superheroes' );
		if( !$return ) {
			$ts   = 1234;
			$hash = md5( $ts . $this->prikey . $this->pubkey );

			$url     = esc_url_raw( self::ENDPOINT . 'public/characters?ts=' . $ts . '&apikey=' . $this->pubkey . '&hash=' . $hash );
			$request = wp_remote_get( $url );

			$body = json_decode( wp_remote_retrieve_body( $request ) );

			$superheroes = $body->data->results;
			$return      = array();
			foreach ( $superheroes as $superhero ) {
				if ( $superhero->thumbnail->path ) {
					$return[] = $superhero;
				}
			}
			wp_cache_set( 'superheroes', $return, 'superheroes', 86400 );
		}
		return $return;
	}

	public function marvel_attribution() {
		echo '<p class="marvel-attribution"><a href="http://marvel.com" rel="nofollow">Data provided by Marvel. © 2015 MARVEL</a></p>';
	}
}

new Superhero_Avatars;