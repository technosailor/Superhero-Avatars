=== Superhero Avatars ===
Contributors: technosailor
Tags: comments, avatars
Requires at least: 4.8
Tested up to: 4.8
Stable tag: trunk
License: MIT
License URI: http://opensource.org/licenses/MIT

Leverages the Marvel Comics(TM) API to replace avatars with Superhero avatars.

== Description ==

Leverages the Marvel Comics(TM) API to replace avatars with Superhero avatars.

*NOTE:* This plugin requires PHP 7+ and it is highly recommended you have object caching int place.

== Installation ==

1. Upload `superhero-avatars` directory to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress 1.
1. Secure API Keys from the <a href="http://developer.marvel.com">Marvel(TM) Developer Portal</a>. 
1. Enter Your API Key info on the Settings > Discussion page
1. Or, define your API keys as constants in `wp-config.php`: `define( 'MARVEL_PUB_KEY', 'your_pub_key' );` and `define( 'MARVEL_PRI_KEY', 'your_private_key' );`


== Frequently Asked Questions ==

= Do I have to pay for this? =

No, but you have to have a <a href="http://developer.marvel.com">Marvel Developers</a> account.

= Avatars are no longer showing up. What's wrong? =

Marvel(TM) has very strict rules about the use of their API and generally don't allow more than 3000 requests a day. It is very important you have caching in place. You should have memcached in place and configured or download and install <a href="http://wordpress.org/plugins/w3-total-cache">W3 Total Cache</a> or <a href="https://wordpress.org/plugins/wp-super-cache/">WP Super Cache</a>. (And no those plugins are not supported by me. Just make sure you have object caching enabled.)
