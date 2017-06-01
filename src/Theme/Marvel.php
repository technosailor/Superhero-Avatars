<?php
namespace Technosailor\Superheros\Theme;

class Marvel {
	public function init() {
		add_action( 'wp_footer', [ $this, 'marvel_attribution'] );
	}

	/**
	 * Required by Marvel TOS
	 */
	public function marvel_attribution() {
		echo '<p class="marvel-attribution"><a href="http://marvel.com" rel="nofollow">Data provided by Marvel. © 2014 MARVEL</a></p>';
	}
}