<?php

class pyl_WC_Paylot_Gateway_Five extends pyl_WC_Paylot_Custom_Gateway {

	public function __construct() {

		$this->id		   			= 'Paylot-five';

		$gateway_title     			= $this->get_option( 'title' );

        if( empty( $gateway_title ) ) {
            $gateway_title = 'Five';
        }

		$this->method_title 	    = 'Paylot - ' . $gateway_title;
		$this->method_description   = sprintf( 'Paylot provide merchants with the tools and services needed to accept online payments from local and international customers using Mastercard, Visa, Verve and Bank Account. <a href="%1$s" target="_blank">Sign up</a> for a Paylot account, and <a href="%2$s" target="_blank">get your API keys</a>.', 'https://Paylot.com', 'https://dashboard.Paylot.com/#/settings/developer' );

		$this->has_fields           = true;

		$this->supports             = array(
			'products',
			'tokenization',
			'subscriptions',
			'multiple_subscriptions',
			'subscription_cancellation',
			'subscription_suspension',
			'subscription_reactivation',
			'subscription_amount_changes',
			'subscription_date_changes',
			'subscription_payment_method_change',
			'subscription_payment_method_change_customer'
		);

		$this->Paylot_settings 	= get_option( 'woocommerce_Paylot_settings', '' );

		// Get setting values
		$this->title 				= $gateway_title;
		$this->description 			= $this->get_option( 'description' );
		$this->enabled          	= $this->get_option( 'enabled' );

		$this->testmode             = $this->Paylot_settings[ 'testmode' ] === 'yes' ? true : false;

		$this->payment_channels 	= $this->get_option( 'payment_channels' );

		$this->cards 				= $this->get_option( 'cards_allowed' );
		$this->banks 				= $this->get_option( 'banks_allowed' );

		$this->payment_page         = $this->get_option( 'payment_page' );

		$this->test_public_key  	= $this->Paylot_settings[ 'test_public_key' ];
		$this->test_secret_key  	= $this->Paylot_settings[ 'test_secret_key' ];

		$this->live_public_key  	= $this->Paylot_settings[ 'live_public_key' ];
		$this->live_secret_key  	= $this->Paylot_settings[ 'live_secret_key' ];

		$this->saved_cards         	= $this->Paylot_settings[ 'saved_cards' ] === 'yes' ? true : false;

		$this->payment_icons 		= $this->get_option( 'payment_icons' );

		$this->custom_metadata      = $this->get_option( 'custom_metadata' ) === 'yes' ? true : false;

		$this->meta_order_id      	= $this->get_option( 'meta_order_id' ) === 'yes' ? true : false;
		$this->meta_name      		= $this->get_option( 'meta_name' ) === 'yes' ? true : false;
		$this->meta_email      		= $this->get_option( 'meta_email' ) === 'yes' ? true : false;
		$this->meta_phone      		= $this->get_option( 'meta_phone' ) === 'yes' ? true : false;
		$this->meta_billing_address = $this->get_option( 'meta_billing_address' ) === 'yes' ? true : false;
		$this->meta_shipping_address= $this->get_option( 'meta_shipping_address' ) === 'yes' ? true : false;
		$this->meta_products      	= $this->get_option( 'meta_products' ) === 'yes' ? true : false;

		$this->public_key      		= $this->testmode ? $this->test_public_key : $this->live_public_key;
		$this->secret_key      		= $this->testmode ? $this->test_secret_key : $this->live_secret_key;

		// Load the form fields
		$this->init_form_fields();

		// Load the settings
		$this->init_settings();

		add_action( 'wp_enqueue_scripts', array( $this, 'payment_scripts' ) );

		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );

		add_action( 'woocommerce_receipt_' . $this->id, array( $this, 'receipt_page' ) );

		add_filter( 'woocommerce_available_payment_gateways', array( $this, 'add_gateway_to_checkout' ) );

		if ( class_exists( 'WC_Subscriptions_Order' ) ) {

			add_action( 'woocommerce_scheduled_subscription_payment_' . $this->id, array( $this, 'scheduled_subscription_payment' ), 10, 2 );

		}

	}


	/**
	 * Display the selected payment icon
	 */
	public function get_icon() {
		$icon_html = '<img src="' . WC_HTTPS::force_https_url( WC_Paylot_URL . '/assets/images/Paylot.png' ) . '" alt="Paylot" style="height: 40px; margin-right: 0.4em;margin-bottom: 0.6em;" />';
		$icon      = $this->payment_icons;

		if( is_array( $icon ) ) {

			foreach ( $icon as $i ) {
				$icon_html .= '<img src="' . WC_HTTPS::force_https_url( WC_Paylot_URL . '/assets/images/'. $i .'.png' ) . '" alt="'. $i .'" style="height: 40px; margin-right: 0.4em;margin-bottom: 0.6em;" />';
			}

		}

		return apply_filters( 'woocommerce_gateway_icon', $icon_html, $this->id );
	}


	/**
	 * Outputs scripts used for Paylot payment
	 */
	public function payment_scripts() {

		if ( ! is_checkout_pay_page() ) {
			return;
		}

		if ( $this->enabled === 'no' ) {
			return;
		}

		$order_key 		= urldecode( $_GET['key'] );
		$order_id  		= absint( get_query_var( 'order-pay' ) );

		$order  		= wc_get_order( $order_id );

		$payment_method = method_exists( $order, 'get_payment_method' ) ? $order->get_payment_method() : $order->payment_method;

		if( $this->id !== $payment_method ) {
			return;
		}

		$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

		wp_enqueue_script( 'jquery' );

		wp_enqueue_script( 'Paylot', 'https://js.Paylot.co/v1/inline.js', array( 'jquery' ), WC_Paylot_VERSION, false );

		wp_enqueue_script( 'wc_Paylot', plugins_url( 'assets/js/Paylot'. $suffix . '.js', WC_Paylot_MAIN_FILE ), array( 'jquery', 'Paylot' ), WC_Paylot_VERSION, false );

		$Paylot_params = array(
			'key'	=> $this->public_key
		);

		if ( is_checkout_pay_page() && get_query_var( 'order-pay' ) ) {

			$email  		= method_exists( $order, 'get_billing_email' ) ? $order->get_billing_email() : $order->billing_email;

			$amount 		= $order->get_total() * 100;

			$txnref		 	= $order_id . '_' .time();

			$the_order_id 	= method_exists( $order, 'get_id' ) ? $order->get_id() : $order->id;
	        $the_order_key 	= method_exists( $order, 'get_order_key' ) ? $order->get_order_key() : $order->order_key;

			if ( $the_order_id == $order_id && $the_order_key == $order_key ) {

				$Paylot_params['email'] 				= $email;
				$Paylot_params['amount']  			= $amount;
				$Paylot_params['txnref']  			= $txnref;
				$Paylot_params['pay_page']  			= $this->payment_page;
				$Paylot_params['currency']  			= get_woocommerce_currency();

			}

			if( in_array( 'bank', $this->payment_channels ) ) {
				$Paylot_params['bank_channel'] = 'true';
			}

			if( in_array( 'card', $this->payment_channels ) ) {
				$Paylot_params['card_channel'] = 'true';
			}

			if( $this->banks ) {

				$Paylot_params['banks_allowed'] = $this->banks;

			}

			if( $this->cards ) {

				$Paylot_params['cards_allowed'] = $this->cards;

			}

			if( $this->custom_metadata ) {

				if( $this->meta_order_id ) {

					$Paylot_params['meta_order_id'] = $order_id;

				}

				if( $this->meta_name ) {

					$first_name  	= method_exists( $order, 'get_billing_first_name' ) ? $order->get_billing_first_name() : $order->billing_first_name;
					$last_name  	= method_exists( $order, 'get_billing_last_name' ) ? $order->get_billing_last_name() : $order->billing_last_name;

					$Paylot_params['meta_name'] = $first_name . ' ' . $last_name;

				}

				if( $this->meta_email ) {

					$Paylot_params['meta_email'] = $email;

				}

				if( $this->meta_phone ) {

					$billing_phone  	= method_exists( $order, 'get_billing_phone' ) ? $order->get_billing_phone() : $order->billing_phone;

					$Paylot_params['meta_phone'] = $billing_phone;

				}

				if( $this->meta_products ) {

					$line_items     = $order->get_items();

					$products 		= '';

					foreach ( $line_items as $item_id => $item ) {
						$name = $item['name'];
						$quantity = $item['qty'];
						$products .= $name .' (Qty: ' . $quantity .')';
						$products .= ' | ';
					}

					$products = rtrim( $products, ' | ' );

					$Paylot_params['meta_products'] = $products;

				}

				if( $this->meta_billing_address ) {

					$billing_address 	= $order->get_formatted_billing_address();
					$billing_address 	= esc_html( preg_replace( '#<br\s*/?>#i', ', ', $billing_address ) );

					$Paylot_params['meta_billing_address'] = $billing_address;

				}

				if( $this->meta_shipping_address ) {

					$shipping_address 	= $order->get_formatted_shipping_address();
					$shipping_address 	= esc_html( preg_replace( '#<br\s*/?>#i', ', ', $shipping_address ) );

					if( empty( $shipping_address ) ) {

						$billing_address 	= $order->get_formatted_billing_address();
						$billing_address 	= esc_html( preg_replace( '#<br\s*/?>#i', ', ', $billing_address ) );

						$shipping_address = $billing_address;

					}

					$Paylot_params['meta_shipping_address'] = $shipping_address;

				}


			}

			update_post_meta( $order_id, '_Paylot_txn_ref', $txnref );

		}

		wp_localize_script( 'wc_Paylot', 'wc_Paylot_params', $Paylot_params );

	}


    /**
     * Add Gateway to checkout page
    */
    public function add_gateway_to_checkout( $available_gateways ) {

		if ( $this->enabled == 'no' ) {
			unset( $available_gateways[ $this->id ] );
		}

		return $available_gateways;

    }

}