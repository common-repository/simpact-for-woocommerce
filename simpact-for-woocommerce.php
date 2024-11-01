<?php
/*
* Plugin Name: Simpact for WooCommerce
* Plugin URI: https://simpact.co/
* Description: A free way to make your webshop support charities with Simpact (https://Simpact.co/).
* Version: 1.0.4
* Author: Simpact
* Author URI: https://simpact.co/
*/

/*
* Copyright (c) 2018 Simpact
*
* The name of the Simpact may not be used to endorse or promote products derived from this
* software without specific prior written permission. THIS SOFTWARE IS PROVIDED ``AS IS'' AND
* WITHOUT ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, WITHOUT LIMITATION, THE IMPLIED
* WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE.
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define plugin name
define('wc_plugin_name_simpact', 'Simpact for Woocommerce');

// Define plugin version
define('wc_version_simpact', '1.0.0');

require ( plugin_dir_path(__FILE__) . 'sforwoo-dashboard.php' );


function sforwoo_admin_enqueue_scripts() {
	global $pagenow, $typenow;
	if ($pagenow == 'index.php') {
		wp_enqueue_style('block-style-css', plugins_url("css/block-style.css", __FILE__) );
	}

}
add_action( 'wp_enqueue_scripts', 'sforwoo_admin_enqueue_scripts');

/*
* This function adds the checkbox.
*/
function sforwoo_my_checkbox( $checkout ) {
	global $woocommerce, $carttotal, $donationamount;

	$currency 		= get_woocommerce_currency();
	$symbol   		= get_woocommerce_currency_symbol( $currency );
	$thelogo  		= WP_PLUGIN_URL . '/WooCommerce Simpact/images/MyLogo.png';
	$apikey			= get_option( 'our_api_field' );
	$language 		= get_option( 'our_first_field' );
	$api_url 		= 'https://simpactapi.eu/v1/donate/' . $carttotal . '/' . $language .'/' . $currency . '/';
	
	$response 		= wp_remote_get( $api_url ,
		array(
			'timeout' => 15,
			'headers' => array( 'x_api_key' => $apikey)
		));
	$body 			= wp_remote_retrieve_body( $response );
	$array 			= json_decode( $body, true );

	$donationamount = $array["donation_amount"];
	$logo_url 		= $array["logo_url"];
	$charitylink 	= $array["charity_url"];
	$simpactlink 	= $array["simpact_link"];
	$donationtext 	= esc_html($array["text"]) . '<span class = "link"> <a href="https://simpact.co/" target= _blank>'.  esc_html($simpactlink) . '</a> </span>';

	if(filter_var($donationamount, FILTER_VALIDATE_INT) && filter_var($logo_url, FILTER_VALIDATE_URL) && filter_var($donationtext, FILTER_SANITIZE_STRING)){	

echo '<div class = "sforwoobox">';

echo '<div class = "sforwooleft">';
if (false == empty($donationtext)) {
	woocommerce_form_field( 'sforwoo_custom_checkbox', array(
		'type'          => 'checkbox',
	//	'label'         => __($donationtext),
	), $checkout->get_value( 'sforwoo_custom_checkbox' )) ;
echo '</div>';
	
echo '<div class = "sforwoomiddle">' . $donationtext . '</div>';
}


echo '<a class = "sforwoologo" href = "'. esc_url($charitylink) . '" target = _blank style = "background-image: url('. esc_url($logo_url) . ');"></a>';

echo '</div>';

$md5dash = dirname(__FILE__ ) . '/sforwoo-dashboard.php';
$md5box  = dirname(__FILE__ ) . '/simpact-for-woocommerce.php';

echo '<div id = "don_amount_hidden_checkout_field">
<input type = "hidden" class = "input-hidden" name = "don_amount" id = "don_amount" value = "' . esc_html($donationamount) . '">
<input type = "hidden" class = "input-hidden" name = "md5sha1Checkout" id = "md5sha1Checkout" value = "' . esc_html(md5_file($md5box) . '   '. sha1_file($md5box)) . '">
<input type = "hidden" class = "input-hidden" name = "md5sha1Dashboard" id = "md5sha1Dashboard" value = "' . esc_html(md5_file($md5dash) . '   '. sha1_file($md5dash)) . '">
</div>';

	}
?>
<script type = "text/javascript">
jQuery( document ).ready(function( $ ) {
	$('#sforwoo_custom_checkbox').click(function() {
		jQuery('body').trigger('update_checkout');
	});
});
</script>
<?php
}
add_action('woocommerce_after_order_notes', 'sforwoo_my_checkbox');

/**
* This function adds the item to the cart.
*/
function sforwoo_checked_box( $checkout ) {
	global $woocommerce, $cartname, $carttotal;

	if ( is_admin() && ! defined( 'DOING_AJAX' ) )
	return;

	$carttotal = ((wc_format_decimal($woocommerce->cart->cart_contents_total, 2) + wc_format_decimal($woocommerce->cart->shipping_total, 2 )) * 100);

	$language = get_option( 'our_first_field' );
	if ($language == 'en') {
		$cartname  = 'Simpact Donation';
	} elseif ($language == 'nl') {
		$cartname  = 'Simpact Donatie';
	} else {
		$cartname  = 'Simpact Donation';
	}
	if ( isset( $_POST['post_data'] ) ) {
		parse_str( $_POST['post_data'], $post_data );
	} else {
		$post_data = $_POST; // fallback for final checkout (non-ajax)
	}

	if (isset($post_data['sforwoo_custom_checkbox'])) {
		$donationamount = $post_data['don_amount']/100;
		wc_format_decimal($woocommerce->cart->add_fee( esc_html($cartname), esc_html($donationamount), true, '', 2, false ));
	}

}
add_action( 'woocommerce_cart_calculate_fees','sforwoo_checked_box' );

/**
* This function processes the checked box
*/
function sforwoo_process_checkbox( $order_id ) {
	global $woocommerce;
	$order = wc_get_order( $order_id );

	$order_data 	= $order->get_data();
	$firstname      = $order_data['billing']['first_name'];
	$lastname       = $order_data['billing']['last_name'];
	$email          = $order_data['billing']['email'];
	$fullname       = $firstname.$lastname;

	$donationamount = get_post_meta($order_id, "_don_amount", true);
    $checkboxstatus = get_post_meta($order_id, "checkbox name", true);
    $issent			= get_post_meta($order_id, "_post_sent", true);

if(!$issent){
	if ($checkboxstatus) {
		$apikey 	= get_option( 'our_api_field' );
		$url 		= 'https://simpactapi.eu/v1/donate/';
		$response 	= wp_remote_post( $url, array(
			'method' 		=> 'POST',
			'timeout'		=> 45,
			'redirection'	=> 5,
			'httpversion' 	=> '1.0',
			'blocking' 		=> true,
			'headers' 		=> array(
				'Content-Type' 	=> 'application/json; charset=utf-8',
				'x_api_key' 	=> $apikey ),
			'body' 			=> json_encode(array(
				'email' 		=> $email,
				'amount'		=> $donationamount,
				'name' 			=> $fullname )),
			)
		);
    }
    }
update_post_meta( $order_id, '_post_sent', true); 
}
add_action('woocommerce_payment_complete', 'sforwoo_process_checkbox');
add_action('woocommerce_order_status_completed', 'sforwoo_process_checkbox');

function sforwoo_checkout_order_meta( $order_id ) {

		if (filter_has_var(INPUT_POST, 'don_amount')) {
			$donation = $_POST['don_amount'];
			if(filter_var($donation, FILTER_VALIDATE_INT)){
				update_post_meta( $order_id, '_don_amount', $donation); 
			}
	}

		if (filter_has_var(INPUT_POST, 'sforwoo_custom_checkbox')) {
			$checkboxvalue = $_POST['sforwoo_custom_checkbox'];
			if(filter_var($checkboxvalue, FILTER_VALIDATE_INT)){
				update_post_meta( $order_id, 'checkbox name', $checkboxvalue);
		}
    }
    update_post_meta( $order_id, '_post_sent', false); 
	//	if ($_POST['sforwoo_custom_checkbox']) update_post_meta( $order_id, 'checkbox name', esc_attr($_POST['sforwoo_custom_checkbox']));
	// if ( ! empty( $_POST['don_amount'] ) )update_post_meta( $order_id, '_don_amount', sanitize_text_field( $_POST['don_amount'] ) );
}
add_action('woocommerce_checkout_update_order_meta', 'sforwoo_checkout_order_meta');