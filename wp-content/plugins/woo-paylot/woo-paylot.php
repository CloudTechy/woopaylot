<?php
/*
	Plugin Name:            Paylot WooCommerce Payment Gateway
	Plugin URI:             https://beta.paylot.co
	Description:            WooCommerce payment gateway for Paylot
	Version:                1.0
	Author:                 Onyekelu Chukwuebuka
	Author URI:             https://bosun.me
	License:                GPL-2.0+
	License URI:            http://www.gnu.org/licenses/gpl-2.0.txt
	WC requires at least:   3.0.0
	WC tested up to:        3.4.0
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

//echo("<script>alert('".get_woocommerce_currency()."')</script>");



define( 'WC_PAYLOT_MAIN_FILE', __FILE__ );
define( 'WC_PAYLOT_URL', untrailingslashit( plugins_url( '/', __FILE__ ) ) );

define( 'WC_PAYLOT_VERSION', '5.3.1' );

function pyl_wc_paylot_init() {

	if ( ! class_exists( 'WC_Payment_Gateway' ) ) {
		return;
	}

	if ( class_exists( 'WC_Payment_Gateway_CC' ) ) {

		require_once dirname( __FILE__ ) . '/includes/class-paylot.php';

		require_once dirname( __FILE__ ) . '/includes/class-wc-subscriptions.php';

		require_once dirname( __FILE__ ) . '/includes/class-paylot-custom-gateway.php';

		require_once dirname( __FILE__ ) . '/includes/custom-gateways/class-gateway-one.php';
		require_once dirname( __FILE__ ) . '/includes/custom-gateways/class-gateway-two.php';
		require_once dirname( __FILE__ ) . '/includes/custom-gateways/class-gateway-three.php';
		require_once dirname( __FILE__ ) . '/includes/custom-gateways/class-gateway-four.php';
		require_once dirname( __FILE__ ) . '/includes/custom-gateways/class-gateway-five.php';

	} else{

		require_once dirname( __FILE__ ) . '/includes/class-paylot-deprecated.php';

	}

	require_once dirname( __FILE__ ) . '/includes/polyfill.php';
	//pyl_wc_add_paylot_gateway callback still unknown
	add_filter( 'woocommerce_payment_gateways', 'pyl_wc_add_paylot_gateway', 99 );

}
add_action( 'plugins_loaded', 'pyl_wc_paylot_init', 99 );


/**
* Add Settings link to the plugin entry in the plugins menu
**/
function pyl_woo_paylot_plugin_action_links( $links ) {

    $settings_link = array(
    	'settings' => '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=checkout&section=paylot' ) . '" title="View Paylot WooCommerce Settings">Settings</a>'
    );

    return array_merge( $links, $settings_link );

}
add_filter('plugin_action_links_' . plugin_basename( __FILE__ ), 'pyl_woo_paylot_plugin_action_links' );


/**
* Add Paylot Gateway to WC
**/
function pyl_wc_add_paylot_gateway( $methods ) {

	if ( class_exists( 'WC_Subscriptions_Order' ) && class_exists( 'WC_Payment_Gateway_CC' ) ) {
		$methods[] = 'pyl_WC_Gateway_paylot_Subscription';
	} else {
		$methods[] = 'pyl_WC_Paylot_Gateway';
	}

	if ( class_exists( 'WC_Payment_Gateway_CC' ) ) {

		if ( 'GHS' != get_woocommerce_currency() ) {

			$settings 		 = get_option( 'woocommerce_paylot_settings', '' );
			$custom_gateways = isset( $settings['custom_gateways'] ) ? $settings['custom_gateways'] : '';

			switch ( $custom_gateways ) {
				case '5':
					$methods[] = 'pyl_WC_Paylot_Gateway_One';
					$methods[] = 'pyl_WC_Paylot_Gateway_Two';
					$methods[] = 'pyl_WC_Paylot_Gateway_Three';
					$methods[] = 'pyl_WC_Paylot_Gateway_Four';
					$methods[] = 'pyl_WC_Paylot_Gateway_Five';
				break;
					case '4':
					$methods[] = 'pyl_WC_Paylot_Gateway_One';
					$methods[] = 'pyl_WC_Paylot_Gateway_Two';
					$methods[] = 'pyl_WC_Paylot_Gateway_Three';
					$methods[] = 'pyl_WC_Paylot_Gateway_Four';
				break;
					case '3':
					$methods[] = 'pyl_WC_Paylot_Gateway_One';
					$methods[] = 'pyl_WC_Paylot_Gateway_Two';
					$methods[] = 'pyl_WC_Paylot_Gateway_Three';
				break;
					case '2':
					$methods[] = 'pyl_WC_Paylot_Gateway_One';
					$methods[] = 'pyl_WC_Paylot_Gateway_Two';
					break;
				case '1':
					$methods[] = 'pyl_WC_Paylot_Gateway_One';
					break;

				default:
					break;
			}

		}

	}

	return $methods;

}


/**
* Display the test mode notice
**/
function pyl_WC_Paylot_testmode_notice(){

	$paystack_settings = get_option( 'woocommerce_paystack_settings' );

	$test_mode 	= isset( $paystack_settings['testmode'] ) ? $paystack_settings['testmode'] : '';

	if ( 'yes' == $test_mode ) {
    ?>
	    <div class="update-nag">
	        Paylot testmode is still enabled, Click <a href="<?php echo get_bloginfo('wpurl') ?>/wp-admin/admin.php?page=wc-settings&tab=checkout&section=paylot">here</a> to disable it when you want to start accepting live payment on your site.
	    </div>
    <?php
	}
}
add_action( 'admin_notices', 'pyl_WC_Paylot_testmode_notice' );