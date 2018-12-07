<?php

class pyl_WC_Paylot_Custom_Gateway extends pyl_WC_Gateway_Paylot_Subscription {

	/**
	 * Initialise Gateway Settings Form Fields
	*/
	public function init_form_fields() {

		$this->form_fields = array(
			'enabled' => array(
				'title'       => 'Enable/Disable',
				'label'       => 'Enable Paylot - ' . $this->title,
				'type'        => 'checkbox',
				'description' => 'Enable this gateway as a payment option on the checkout page.',
				'default'     => 'no',
				'desc_tip'    => true
			),
			'title' => array(
				'title' 		=> 'Title',
				'type' 			=> 'text',
				'description' 	=> 'This controls the payment method title which the user sees during checkout.',
    			'desc_tip'      => true,
				'default' 		=> 'Paylot'
			),
			'description' => array(
				'title' 		=> 'Description',
				'type' 			=> 'textarea',
				'description' 	=> 'This controls the payment method description which the user sees during checkout.',
    			'desc_tip'      => true,
				'default' 		=> ''
			),
			'payment_page' => array(
				'title'       => 'Payment Page',
				'type'        => 'select',
				'description' => 'Inline shows the payment popup on the page while Inline Embed shows the payment page directly on the page',
				'default'     => '',
				'desc_tip'    => false,
				'options'     => array(
					''   		=> 'Select One',
					'inline'   	=> 'Inline',
					'embed' 	=> 'Inline Embed'
				)
			),
			'payment_channels' 	  => array(
				'title'       	=> 'Payment Channels',
				'type'    		=> 'multiselect',
				'class'  		=> 'wc-enhanced-select wc-Paylot-payment-channels',
				'description' 	=> 'The payment channels enabled for this gateway',
				'default'     	=> '',
				'desc_tip'  	=> true,
				'select_buttons'=> true,
				'options'   	=> $this->channels(),
				'custom_attributes' => array(
					'data-placeholder' 	=> 'Select payment channels',
				),
			),
			'cards_allowed' => array(
				'title'     	=> 'Allowed Card Brands',
				'type'    		=> 'multiselect',
				'class'  		=> 'wc-enhanced-select wc-Paylot-cards-allowed',
				'description' 	=> 'The card brands allowed for this gateway. This filter only works with the card payment channel.',
				'default'   	=> '',
				'desc_tip'  	=> true,
				'select_buttons'=> true,
				'options'   	=> $this->card_types(),
				'custom_attributes' => array(
					'data-placeholder' 	=> 'Select card brands',
				),
			),
			'banks_allowed' => array(
				'title'     	=> 'Allowed Banks Card',
				'type'    		=> 'multiselect',
				'class'  		=> 'wc-enhanced-select wc-Paylot-banks-allowed',
				'description' 	=> 'The banks whose card should be allowed for this gateway. This filter only works with the card payment channel.',
				'default'     	=> '',
				'desc_tip'    	=> true,
				'select_buttons'=> true,
				'options'     	=> $this->banks(),
				'custom_attributes' => array(
					'data-placeholder' 	=> 'Select banks',
				),
			),
			'payment_icons' => array(
				'title'     	=> 'Payment Icons',
				'type'    		=> 'multiselect',
				'class'  		=> 'wc-enhanced-select wc-Paylot-payment-icons',
				'description' 	=> 'The payment icons to be displayed on the checkout page.',
				'default'     	=> '',
				'desc_tip'    	=> true,
				'select_buttons'=> true,
				'options'     	=> $this->payment_icons(),
				'custom_attributes' => array(
					'data-placeholder' 	=> 'Select payment icons',
				),
			),
			'custom_metadata' 	  => array(
				'title'       => 'Custom Metadata',
				'label'       => 'Enable Custom Metadata',
				'type'        => 'checkbox',
				'class'       => 'wc-Paylot-metadata',
				'description' => 'If enabled, you will be able to send more information about the order to Paylot.',
				'default'     => 'no',
				'desc_tip'    => true
			),
			'meta_order_id'  => array(
				'title'       => 'Order ID',
				'label'       => 'Send Order ID',
				'type'        => 'checkbox',
				'class'       => 'wc-Paylot-meta-order-id',
				'description' => 'If checked, the Order ID will be sent to Paylot',
				'default'     => 'no',
				'desc_tip'    => true
			),
			'meta_name'  => array(
				'title'       => 'Customer Name',
				'label'       => 'Send Customer Name',
				'type'        => 'checkbox',
				'class'       => 'wc-Paylot-meta-name',
				'description' => 'If checked, the customer full name will be sent to Paylot',
				'default'     => 'no',
				'desc_tip'    => true
			),
			'meta_email'  => array(
				'title'       => 'Customer Email',
				'label'       => 'Send Customer Email',
				'type'        => 'checkbox',
				'class'       => 'wc-Paylot-meta-email',
				'description' => 'If checked, the customer email address will be sent to Paylot',
				'default'     => 'no',
				'desc_tip'    => true
			),
			'meta_phone'  => array(
				'title'       => 'Customer Phone',
				'label'       => 'Send Customer Phone',
				'type'        => 'checkbox',
				'class'       => 'wc-Paylot-meta-phone',
				'description' => 'If checked, the customer phone will be sent to Paylot',
				'default'     => 'no',
				'desc_tip'    => true
			),
			'meta_billing_address'  => array(
				'title'       => 'Order Billing Address',
				'label'       => 'Send Order Billing Address',
				'type'        => 'checkbox',
				'class'       => 'wc-Paylot-meta-billing-address',
				'description' => 'If checked, the order billing address will be sent to Paylot',
				'default'     => 'no',
				'desc_tip'    => true
			),
			'meta_shipping_address'  => array(
				'title'       => 'Order Shipping Address',
				'label'       => 'Send Order Shipping Address',
				'type'        => 'checkbox',
				'class'       => 'wc-Paylot-meta-shipping-address',
				'description' => 'If checked, the order shipping address will be sent to Paylot',
				'default'     => 'no',
				'desc_tip'    => true
			),
			'meta_products'  => array(
				'title'       => 'Product(s) Purchased',
				'label'       => 'Send Product(s) Purchased',
				'type'        => 'checkbox',
				'class'       => 'wc-Paylot-meta-products',
				'description' => 'If checked, the product(s) purchased will be sent to Paylot',
				'default'     => 'no',
				'desc_tip'    => true
			),
		);

	}


    /**
     * Admin Panel Options
    */
    public function admin_options() {

    	?>

    	<h2>Paylot - <?php echo $this->title; ?>
		<?php
			if ( function_exists( 'wc_back_link' ) ) {
				wc_back_link( 'Return to payments', admin_url( 'admin.php?page=wc-settings&tab=checkout' ) );
			}
		?>
    	</h2>

        <h4>Optional: To avoid situations where bad network makes it impossible to verify transactions, set your webhook URL <a href="https://dashboard.Paylot.co/#/settings/developer" target="_blank" rel="noopener noreferrer">here</a> to the URL below<strong style="color: red"><pre><code><?php echo WC()->api_request_url( 'pyl_WC_Paylot_Webhook' ); ?></code></pre></strong></h4>

        <p>To configure your Paylot API keys and enable/disable test mode, do that <a href="<?php echo get_bloginfo('wpurl') ?>/wp-admin/admin.php?page=wc-settings&tab=checkout&section=Paylot">here</a></p>

        <?php

		if ( $this->is_valid_for_use() ){

            echo '<table class="form-table">';
            $this->generate_settings_html();
            echo '</table>';

        }
		else {	 ?>
			<div class="inline error"><p><strong>Paylot Payment Gateway Disabled</strong>: <?php echo $this->msg ?></p></div>

		<?php }

    }


    /**
     * Payment Channels
    */
    public function channels() {

    	return array (
			'card' 			=> 'Cards',
			'bank'   		=> 'Banks',
    	);

    }


    /**
     * Card Types
    */
    public function card_types() {

    	return array (
			'visa'   		=> 'Visa',
			'verve' 		=> 'Verve',
			'mastercard' 	=> 'Mastercard',
    	);

    }


    /**
     * Banks
    */
    public function banks() {

    	return array (
			'044'   => 'Access Bank',
			'023' 	=> 'Citibank Nigeria',
			'063' 	=> 'Diamond Bank',
			'050' 	=> 'Ecobank Nigeria',
			'084' 	=> 'Enterprise Bank',
			'070' 	=> 'Fidelity Bank',
			'011' 	=> 'First Bank of Nigeria',
			'214' 	=> 'First City Monument Bank',
			'058' 	=> 'Guaranty Trust Bank',
			'030' 	=> 'Heritage Bank',
			'301'	=> 'Jaiz Bank',
			'082' 	=> 'Keystone Bank',
			'526'	=> 'Parallex Bank',
			'101'	=> 'Providus Bank',
			'076' 	=> 'Skye Bank',
			'221' 	=> 'Stanbic IBTC Bank',
			'068' 	=> 'Standard Chartered Bank',
			'232' 	=> 'Sterling Bank',
			'100'	=> 'Suntrust Bank',
			'032' 	=> 'Union Bank of Nigeria',
			'033' 	=> 'United Bank For Africa',
			'215' 	=> 'Unity Bank',
			'035' 	=> 'Wema Bank',
			'057' 	=> 'Zenith Bank',
    	);

    }


    /**
     * Payment Icons
    */
    public function payment_icons() {

    	return array(
    		'verve'			=> 'Verve',
    		'visa'			=> 'Visa',
    		'mastercard'	=> 'Mastercard',
    		'Paylotwhite'	=> 'Secured by Paylot White',
    		'Paylotblue'	=> 'Secured by Paylot Blue',
    		'Paylot-wc'	=> 'Paylot',
			'access'   		=> 'Access Bank',
			'citibank' 		=> 'Citibank Nigeria',
			'diamond' 		=> 'Diamond Bank',
			'ecobank' 		=> 'Ecobank Nigeria',
			'enterprise' 	=> 'Enterprise Bank',
			'fidelity' 		=> 'Fidelity Bank',
			'firstbank' 	=> 'First Bank of Nigeria',
			'fcmb' 			=> 'First City Monument Bank',
			'gtbank' 		=> 'Guaranty Trust Bank',
			'heritage' 		=> 'Heritage Bank',
			'jaiz'			=> 'Jaiz Bank',
			'keystone' 		=> 'Keystone Bank',
			'parallex'		=> 'Parallex Bank',
			'providus'		=> 'Providus Bank',
			'skye' 			=> 'Skye Bank',
			'stanbic' 		=> 'Stanbic IBTC Bank',
			'standard' 		=> 'Standard Chartered Bank',
			'sterling' 		=> 'Sterling Bank',
			'suntrust'		=> 'Suntrust Bank',
			'union' 		=> 'Union Bank of Nigeria',
			'uba' 			=> 'United Bank For Africa',
			'unity' 		=> 'Unity Bank',
			'wema' 			=> 'Wema Bank',
			'zenith' 		=> 'Zenith Bank',
    	);

    }

}