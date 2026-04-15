<?php
/**
 * Plugin Name: KO - Google Ads Purchase Conversion
 * Plugin URI:  https://kevinoneill.us/
 * Description: Fires a Google Ads purchase conversion on the WooCommerce thank-you page using the real order total, currency, and order ID.
 * Version:     1.0.0
 * Author:      Kevin O'Neill / ChatGPT
 * Author URI:  https://kevinoneill.us/
 * License:     GPL-2.0+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: ko-google-ads-purchase-conversion
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'KO_Google_Ads_Purchase_Conversion' ) ) {

	final class KO_Google_Ads_Purchase_Conversion {

		/**
		 * Meta key used to prevent duplicate conversion firing.
		 */
		const META_KEY_FIRED = '_ko_google_ads_conversion_fired';

		/**
		 * Google Ads send_to value.
		 *
		 * Example: AW-123456789/AbCdEfGhIjKlMnOpQr
		 */
		const SEND_TO = 'ADD GOOGLE ADS CONVERSION TARGET HERE';

		/**
		 * Bootstrap.
		 */
		public static function init() {
			add_action( 'woocommerce_thankyou', array( __CLASS__, 'output_conversion_script' ), 20 );
		}

		/**
		 * Output the Google Ads conversion event on the WooCommerce thank-you page.
		 *
		 * @param int $order_id WooCommerce order ID.
		 * @return void
		 */
		public static function output_conversion_script( $order_id ) {
			if ( empty( $order_id ) || ! function_exists( 'wc_get_order' ) ) {
				return;
			}

			$order = wc_get_order( $order_id );

			if ( ! $order instanceof WC_Order ) {
				return;
			}

			// Prevent duplicate conversion events on page refresh / revisit.
			if ( self::has_already_fired( $order_id ) ) {
				return;
			}

			$order_total = (float) $order->get_total();
			$currency    = (string) $order->get_currency();
			$send_to     = (string) apply_filters( 'ko_gads_purchase_send_to', self::SEND_TO, $order );
			$order_key   = (string) $order->get_order_key();

			if ( '' === $send_to ) {
				return;
			}

			/**
			 * Allow developers to short-circuit output.
			 *
			 * @param bool     $should_output Whether to output the conversion script.
			 * @param WC_Order $order         WooCommerce order object.
			 */
			$should_output = apply_filters( 'ko_gads_purchase_should_output', true, $order );

			if ( ! $should_output ) {
				return;
			}

			self::mark_as_fired( $order_id );
			?>
			<!-- KO - Google Ads Purchase Conversion -->
			<script>
				window.dataLayer = window.dataLayer || [];
				window.gtag = window.gtag || function(){dataLayer.push(arguments);};
				gtag('event', 'conversion', {
					'send_to': <?php echo wp_json_encode( $send_to ); ?>,
					'value': <?php echo wp_json_encode( $order_total ); ?>,
					'currency': <?php echo wp_json_encode( $currency ); ?>,
					'transaction_id': <?php echo wp_json_encode( (string) $order_id ); ?>
				});
			</script>
			<noscript>
				<img
					height="1"
					width="1"
					style="display:none;"
					alt=""
					src="https://www.googleadservices.com/pagead/conversion/<?php echo esc_attr( self::get_conversion_id() ); ?>/?value=<?php echo rawurlencode( number_format( $order_total, 2, '.', '' ) ); ?>&amp;currency_code=<?php echo rawurlencode( $currency ); ?>&amp;label=<?php echo rawurlencode( self::get_conversion_label() ); ?>&amp;guid=ON&amp;script=0&amp;transaction_id=<?php echo rawurlencode( (string) $order_id ); ?>&amp;ord=<?php echo rawurlencode( $order_key ? $order_key : (string) $order_id ); ?>"
				/>
			</noscript>
			<!-- /KO - Google Ads Purchase Conversion -->
			<?php
		}

		/**
		 * Has this order already fired the Google Ads conversion?
		 *
		 * @param int $order_id Order ID.
		 * @return bool
		 */
		private static function has_already_fired( $order_id ) {
			return (bool) get_post_meta( $order_id, self::META_KEY_FIRED, true );
		}

		/**
		 * Mark the order as having fired the conversion.
		 *
		 * @param int $order_id Order ID.
		 * @return void
		 */
		private static function mark_as_fired( $order_id ) {
			update_post_meta( $order_id, self::META_KEY_FIRED, 1 );
		}

		/**
		 * Extract the Google Ads conversion ID from SEND_TO.
		 *
		 * @return string
		 */
		private static function get_conversion_id() {
			$parts = explode( '/', self::SEND_TO );
			$id    = isset( $parts[0] ) ? $parts[0] : '';

			return str_replace( 'AW-', '', $id );
		}

		/**
		 * Extract the Google Ads conversion label from SEND_TO.
		 *
		 * @return string
		 */
		private static function get_conversion_label() {
			$parts = explode( '/', self::SEND_TO );

			return isset( $parts[1] ) ? $parts[1] : '';
		}
	}

	KO_Google_Ads_Purchase_Conversion::init();
}
