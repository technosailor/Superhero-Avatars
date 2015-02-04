=== Superhero Avatars ===
Contributors: technosailor
Tags: comments, avatars
Requires at least: 4.1
Tested up to: 4.1
Stable tag: trunk
License: MIT
License URI: http://opensource.org/licenses/MIT

Leverages the Marvel Comics(TM) API to replace avatars with Superhero avatars.

== Description ==

Leverages the Marvel Comics(TM) API to replace avatars with Superhero avatars.

== Installation ==

1. Upload `superhero-avatars` directory to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Secure API Keys from the <a href="http://developer.marvel.com">Marvel(TM) Developer Portal</a>.

== Frequently Asked Questions ==

= Do I have to pay for this? =

No, but you have to have a <a href="http://developer.marvel.com">Marvel Developers</a> account.

= Avatars are no longer showing up. What's wrong? =

Marvel(TM) has very strict rules about the use of their API and generally don't allow more than 1000 requests a day. It is very important you have caching in place. Please download and install <a href="http://wordpress.org/plugins/w3-total-cache">W3 Total Cache</a> or <a href="https://wordpress.org/plugins/wp-super-cache/">WP Super Cache</a>. (And no those plugins are not supported by me. Just make sure you have object caching enabled.)