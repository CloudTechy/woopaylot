<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class pyl_WC_Gateway_Paylot_Subscription extends pyl_WC_Paylot_Gateway {

	/**
	 * Constructor
	*/
	public function __construct() {

		parent::__construct();

		if ( class_exists( 'WC_Subscriptions_Order' ) ) {

			add_action( 'woocommerce_scheduled_subscription_payment_' . $this->id, array( $this, 'scheduled_subscription_payment' ), 10, 2 );

		}
	}


	/**
	 * Check if an order contains a subscription
	 */
	public function order_contains_subscription( $order_id ) {

		return function_exists( 'wcs_order_contains_subscription' ) && ( wcs_order_contains_subscription( $order_id ) || wcs_order_contains_renewal( $order_id ) );

	}


	/**
	 * Process a trial subscription order with 0 total
	 */
	public function process_payment( $order_id ) {

		$order = wc_get_order( $order_id );

		// Check for trial subscription order with 0 total
		if ( $this->order_contains_subscription( $order ) && $order->get_total() == 0 ) {

			$order->payment_complete();

			$order->add_order_note( 'This subscription has a free trial, reason for the 0 amount' );

			return array(
				'result'   => 'success',
				'redirect' => $this->get_return_url( $order )
			);

		} else {

			return parent::process_payment( $order_id );

		}

	}


	/**
	 * Process a subscription renewal
	 */
	public function scheduled_subscription_payment( $amount_to_charge, $renewal_order ) {

		$response = $this->process_subscription_payment( $renewal_order, $amount_to_charge );

		if ( is_wp_error( $response ) ) {

			$renewal_order->update_status( 'failed', sprintf( 'Paylot Transaction Failed (%s)', $response->get_error_message() ) );

		}

	}


	/**
	 * Process a subscription renewal payment
	 */
	public function process_subscription_payment( $order = '', $amount = 0 ) {

		$order_id  = method_exists( $order, 'get_id' ) ? $order->get_id() : $order->id;

		$auth_code = get_post_meta( $order_id, '_Paylot_token', true );

		if ( $auth_code ) {

			$email          = method_exists( $order, 'get_billing_email' ) ? $order->get_billing_email() : $order->billing_email;

			$order_amount   = $amount * 100;

			$Paylot_url   = 'https://api.Paylot.co/transaction/charge_authorization';

			$headers = array(
				'Content-Type'	=> 'application/json',
				'Authorization' => 'Bearer ' . $this->secret_key
			);

			$body = array(
				'email'						=> $email,
				'amount'					=> $order_amount,
				'authorization_code'		=> $auth_code
			);

			$args = array(
				'body'		=> json_encode( $body ),
				'headers'	=> $headers,
				'timeout'	=> 60
			);

			$request = wp_remote_post( $Paylot_url, $args );

	        if ( ! is_wp_error( $request ) && 200 == wp_remote_retrieve_response_code( $request ) ) {

            	$Paylot_response = json_decode( wp_remote_retrieve_body( $request ) );

				if ( 'success' == $Paylot_response->data->status ) {

	        		$Paylot_ref 	= $Paylot_response->data->reference;

					$order->payment_complete( $Paylot_ref );

					$message = sprintf( 'Payment via Paylot successful (Transaction Reference: %s)', $Paylot_ref );

					$order->add_order_note( $message );

					return true;

				} else {

					$gateway_response = 'Paylot payment failed.';

					if( isset( $Paylot_response->data->gateway_response ) && ! empty ( $Paylot_response->data->gateway_response ) ) {
						$gateway_response = 'Paylot payment failed. Reason: ' . $Paylot_response->data->gateway_response;
					}

					return new WP_Error( 'Paylot_error', $gateway_response );

				}

	        }
		}

		return new WP_Error( 'Paylot_error', 'This subscription can\'t be renewed automatically. The customer will have to login to his account to renew his subscription' );

	}

}