=== KO - Google Ads Purchase Conversion ===
Contributors: kevinoneill
Tags: woocommerce, google ads, conversion tracking, ecommerce, marketing
Requires at least: 6.0
Tested up to: 6.8
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Fires a Google Ads purchase conversion on the WooCommerce thank-you page using the real order total, currency, and order ID.

== Description ==

KO - Google Ads Purchase Conversion adds a Google Ads purchase conversion event to the WooCommerce order-received / thank-you page.

This plugin is intended for setups where:

* Google Tag Manager must remain in place
* the site already has a global Google Ads base tag loaded
* the purchase conversion should fire only after a successful order
* the conversion should use the real WooCommerce order total, currency, and order ID

Version 1.0.0 includes:

* thank-you page only conversion firing
* real order total passed as the conversion value
* real WooCommerce currency passed to Google Ads
* real order ID used as the transaction ID
* duplicate-fire protection using order meta
* a noscript image fallback
* developer filters for the send_to value and output behavior

== Important ==

Remove any global purchase conversion event from the site-wide head code.

This plugin is designed to replace a hardcoded purchase conversion snippet like this:

`gtag('event', 'conversion', { 'send_to': 'AW-ADDCODEHERE', 'value': 1.0, 'currency': 'USD', 'transaction_id': '' });`

The global base Google Ads tag may remain in place. The global purchase event should not.

== Installation ==

1. Upload the `ko-google-ads-purchase-conversion` folder to `/wp-content/plugins/`
2. Activate the plugin through the WordPress Plugins screen
3. Remove the existing hardcoded global Google Ads purchase event from the site-wide head/integration code
4. Leave the global Google Ads base tag and Google Tag Manager in place unless your marketing team tells you otherwise
5. Place a test order and confirm the conversion request fires on the WooCommerce order-received page

== Frequently Asked Questions ==

= Does this replace Google Tag Manager? =

No. This plugin does not replace GTM. It only adds the purchase conversion event on the WooCommerce thank-you page.

= Does this replace the global Google Ads base tag? =

No. The Google Ads base tag can remain in the global head code. This plugin replaces only the purchase conversion event.

= Will this fire again if the thank-you page is refreshed? =

No. The plugin stores a small piece of order meta to help prevent duplicate conversion firing on refresh or revisit.

= Can the send_to value be changed later? =

Yes. Developers can edit the plugin constant directly or use the `ko_gads_purchase_send_to` filter.

== Hooks ==

= Filter: `ko_gads_purchase_send_to` =

Change the Google Ads `send_to` value.

Example:

`add_filter( 'ko_gads_purchase_send_to', function( $send_to, $order ) { return 'AW-123456789/AbCdEfGhIjKlMnOpQr'; }, 10, 2 );`

= Filter: `ko_gads_purchase_should_output` =

Short-circuit output if needed.

Example:

`add_filter( 'ko_gads_purchase_should_output', '__return_false', 10, 2 );`

== Changelog ==

= 1.0.0 =
* Initial release
* Adds Google Ads purchase conversion to the WooCommerce thank-you page
* Passes order total, currency, and order ID
* Includes duplicate-fire protection and noscript fallback
