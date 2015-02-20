<?php

class Superhero_Avatars_Test extends WP_UnitTestCase {

	public $superheroes;

	public function setUp() {
		parent::setUp();
		$opts = array(
			'pubkey' => 'foo',
			'prikey' => 'bar'
		);

		add_option( 'superhero_opts', $opts );

		$this->superheroes = new Superhero_Avatars;
		$this->superheroes->hooks();
	}

	public function tearDown() {
		parent::tearDown();
		unset( $_POST );
	}

	public function test_hooks() {
		$superheroes = $this->superheroes;

		$this->assertEquals( 10, has_action( 'admin_menu', array( $superheroes, 'admin_menu' ), 'Superhero_Avatars::hooks is not attaching to Superhero_Avatars::admin_menu to admin_menu' ) );
		$this->assertEquals( 10, has_action( 'admin_init', array( $superheroes, 'save' ), 'Superhero_Avatars::hooks is not attaching to Superhero_Avatars::save to admin_init' ) );
		$this->assertEquals( 10, has_filter( 'get_avatar', array( $superheroes, 'get_avatar' ), 'Superhero_Avatars::hooks is not attaching to Superhero_Avatars::get_avatar to get_avatar' ) );
		$this->assertEquals( 10, has_action( 'wp_footer', array( $superheroes, 'marvel_attribution' ), 'Superhero_Avatars::hooks is not attaching to Superhero_Avatars::marvel_attribution to wp_footer' ) );
	}

	/*
	public function test_i18n() {
		$ltd = load_plugin_textdomain( 'superhero-avatars', false, dirname( plugin_basename( __FILE__) ) . '/languages/' );
		$this->assertTrue( $ltd );
	}
	*/

	public function test_admin_menu_no_perms() {
		$actual = $this->superheroes->admin_menu();
		$this->assertFalse( $actual );
	}

	public function test_admin_menu_perms() {
		$user = new WP_User( 1 );
		$user->set_role( 'administrator' );
		wp_set_current_user( $user->ID );

		$expected = 'admin_page_superhero-options';
		$actual = $this->superheroes->admin_menu();

		$this->assertEquals( $expected, $actual );
	}

	public function test_save() {
		add_option( 'superhero_opts', array() );
		$nonce = wp_create_nonce();

		$_POST = array(
			'_wp_superhero_nonce' => $nonce,
			'superhero-pubkey' => 'foo',
			'superhero-prikey' => 'bar'
		);

		$expected = array( 'pubkey' => 'foo', 'prikey' => 'bar' );
		$actual = get_option( 'superhero_opts' );
		$this->assertEquals( $expected, $actual );

	}

	public function test_get_avatar_has_meta() {
		$avatar = 'http://example.com/original.jpg';
		$id_or_email = 1;
		$size = 100;
		$default = '';
		$alt = 'foo';

		$user = new WP_User( 1 );
		wp_set_current_user( $user->ID );
		update_user_meta( $user->ID, 'superhero_avatar_url', $avatar );

		$expected = '<img src="http://example.com/original.jpg" height="100" width="100" class="avatar avatar-100" style="height:100px; width: 100px" alt="admin" title="admin" />';
		$actual = $this->superheroes->get_avatar( $avatar, $id_or_email, $size, $default, $alt );

		$this->assertEquals( $expected, $actual );
	}

	public function test_get_avatar_no_meta() {
		$avatar = 'http://example.com/original.jpg';
		$id_or_email = 1;
		$size = 100;
		$default = '';
		$alt = 'foo';

		$obj = array(
			0 => (object) array(
				'thumbnail' => (object) array(
					'path' => 'http://example.com/image',
					'extension' => 'jpg'
				)
			)
		);


		$class = $this->getMockBuilder( 'Superhero_Avatars')
		              ->getMock();

		$class->method( 'get_superheroes' )
			->willReturn( $obj );
		$class->method( 'get_avatar' )
			->with(
				$this->equalTo( $avatar ),
				$this->equalTo( $id_or_email ),
				$this->equalTo( $size ),
				$this->equalTo( $default ),
				$this->equalTo( $alt )
			)
			->willReturn( sprintf( '<img src="%1$s" height="%2$s" width="%2$s" class="avatar avatar-%2$s" style="height:%2$spx; width: %2$spx" alt="%3$s" title="%3$s" />', $avatar, $size, $alt ) );

		$expected = '<img src="http://example.com/original.jpg" height="100" width="100" class="avatar avatar-100" style="height:100px; width: 100px" alt="foo" title="foo" />';
		$actual = $class->get_avatar( $avatar, $id_or_email, $size, $default, $alt );

		$this->assertEquals( $expected, $actual );
	}
}