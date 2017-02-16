<?php
//templay loader check
defined('TPT_INIT') or die('access denied');

//all static class
class amz_checkout {
	// class-wise data vars

	static $shipping_method = 0;
	static $payment_method = 0;
	static $delivery_notes = '';
	static $card_details = array();

	function __construct() {
		return false;
	}

	static function getCheckoutBar(&$vars, $step=0) {

		$tpt_imagesurl = TPT_IMAGES_URL;

		$html = '';

		$bg = '#CC3333;';
		//$bg = '#FFA131';
		$cl = 'amz_red';
		//$cl = '';
		$st = '';
		$st = 'background-color: #f1ede9;border-radius: 20px;';
		//$st = 'color: #979797;';

$basket_url = $vars['url']['handler']->wrap($vars, '/your-basket');
$html .= <<< EOT
<div class="position-relative height-72 steps clear">
	<div class="clearBoth position-absolute background-repeat-no-repeat background-position-CC top-0 bottom-0 left-0 right-0 z-index-2">
		<div class="clearBoth position-relative padding-left-14 padding-right-14 padding-top-20 padding-bottom-20 z-index-1">
				<div class="float-left width-20prc padding-5 border-radius-20">
					<a title="View What is in Your Basket" href="$basket_url" class="$cl text-decoration-none todayshop-bolditalic display-block height-15 padding-top-5 padding-bottom-5 text-align-center font-size-11" style="$st">Basket</a>
				</div>
EOT;
if($step == 0) {
	$cl = '';
	$st .= 'color: #979797;';
	$bg = '#ffa131';
}
$shipping_url = $vars['url']['handler']->wrap($vars, '/shipping-details');
$html .= <<< EOT
				<div class="float-left width-2prc padding-top-15 padding-bottom-15 bg-color">
					<div class="height-5" style="background-color: $bg;"></div>
				</div>
				<div class="float-left width-20prc padding-5 border-radius-20">
					<a title="Enter Your Shipping Details" href="$shipping_url" class="$cl text-decoration-none todayshop-bolditalic display-block height-15 padding-top-5 padding-bottom-5 text-align-center font-size-11" style="$st">Address</a>
				</div>
EOT;
if($step == 1) {
	$cl = '';
	$st .= 'color: #979797;';
	$bg = '#ffa131;';
}
$html .= <<< EOT
				<div class="float-left width-2prc padding-top-15 padding-bottom-15 bg-color">
					<div class="height-5" style="background-color: $bg;"></div>
				</div>
				<div class="float-left width-20prc padding-5 border-radius-20">
					<span title="Make Payment" class="$cl text-decoration-none todayshop-bolditalic display-block height-15 padding-top-5 padding-bottom-5 text-align-center font-size-11" style="$st">Payment</span>
				</div>
EOT;
if($step == 2) {
	$cl = '';
	$st .= 'color: #979797;';
	$bg = '#ffa131';
}
$html .= <<< EOT
				<div class="float-left width-2prc padding-top-15 padding-bottom-15 bg-color">
					<div class="height-5" style="background-color: $bg;"></div>
				</div>
				<div class="float-left width-20prc padding-5 border-radius-20">
					<span title="View Finalized Order" class="$cl text-decoration-none todayshop-bolditalic display-block height-15 padding-top-5 padding-bottom-5 text-align-center font-size-10" style="$st">Order Confirmation</span>
				</div>
		</div>
	</div>
	<div class="clearBoth position-relative padding-left-14 padding-right-14 padding-top-20 padding-bottom-20 z-index-1">
EOT;

		$bg = '#CC3333';

$html .= <<< EOT
		<div class="float-left width-20prc padding-5 border-radius-20" style="background-color: $bg;">
			<div class=" height-15 padding-top-5 padding-bottom-5"></div>
		</div>
EOT;
if($step == 0) {
	$bg = '#FFA131';
}
$html .= <<< EOT
		<div class="float-left width-2prc padding-top-15 padding-bottom-15" style="background-color: #f1ede9;">
			<div class="height-5" style="background-color: $bg;"></div>
		</div>
		<div class="float-left width-20prc padding-5 border-radius-20" style="background-color: $bg;">
			<div class=" height-15 padding-top-5 padding-bottom-5"></div>
		</div>
EOT;
if($step == 1) {
	$bg = '#FFA131';
}
$html .= <<< EOT
		<div class="float-left width-2prc padding-top-15 padding-bottom-15" style="background-color: #f1ede9;">
			<div class="height-5" style="background-color: $bg;"></div>
		</div>
		<div class="float-left width-20prc padding-5 border-radius-20" style="background-color: $bg;">
			<div class=" height-15 padding-top-5 padding-bottom-5"></div>
		</div>
EOT;
if($step == 2) {
	$bg = '#FFA131';
}
$html .= <<< EOT
		<div class="float-left width-2prc padding-top-15 padding-bottom-15" style="background-color: #f1ede9;">
			<div class="height-5" style="background-color: $bg;"></div>
		</div>
		<div class="float-left width-20prc padding-5 border-radius-20" style="background-color: $bg;">
			<div class=" height-15 padding-top-5 padding-bottom-5"></div>
		</div>
	</div>
</div>
EOT;

		return $html;
	}


	static function insertOrder(&$vars) {
		$countries_module = getModule($vars, 'Countries');
		$orders_module = getModule($vars, 'Orders');
		$rushorder_module = getModule($vars, 'RushOrder');

		$shipping_method = amz_checkout::$shipping_method;
		$payment_method = amz_checkout::$payment_method;

		$totals								= amz_checkout::getTotals($vars);
		$vship								= $totals['values']['shipping'];
		$vsubtotal							= $totals['values']['subtotal'];
		$vtotal								= $totals['values']['total'];
		$vtax 								= $totals['values']['tax'];
		$vtotals = compact('vship', 'vsubtotal', 'vtax', 'vtotal');
		//tpt_dump($vtotals, true);

		if ((strtolower($_SERVER['REQUEST_METHOD']) != 'post')) {
				$vars['environment']['ajax_result']['messages'][] = array(
						'Error processing payment.',
						'error'
				);
				$return_url = $vars['url']['handler']->wrap($vars, '/billing-details');
				tpt_request::redirect($vars, $return_url);
				return false;
		}

		//tpt_dump($payment_method, true);
		$products = amz_cart::$products;


		$customerdata = tpt_current_user::getUserDataArray($vars);

		$order_id = $orders_module->createOrderId($vars);


		$paid_mark = 0;
		$status = 0;
		$tax_class = tpt_current_user::get_tax_class($vars);
		//$tax_class = array('1');
		//$tax_class = implode(',', $tax_class);

		$cus_payment_details = '<br>';
		$pmstring = '';
		$pmsales = '';
		if($payment_method == 1) {
				$payment_success = false;

				//include(TPT_PAYMENTSCRIPTS_DIR . DIRECTORY_SEPARATOR . 'tpt-cc.php');


				if(empty($_POST['cc_num']) || empty($_POST['cc_ccv'])) {
					$vars['environment']['ajax_result']['messages'][] = array(
						'Invalid or incomplete credit card details.',
						'error'
					);
					$return_url = $vars['url']['handler']->wrap($vars, '/billing-details');
					tpt_request::redirect($vars, $return_url);
					return false;
				}

				$response = array();
			//tpt_dump($vars['user']['data'], true);
				if(empty($vars['config']['dev']['debuginsertorder_skippayment_cc']) && ($vars['user']['data']['usertype'] != 60)) {
					$response = tpt_AuthNet::pay($vars, array('order_id'=>$order_id), $vtotals, $_POST);
					$cus_payment_details = tpt_AuthNet::generateReceipt($vars, $vtotals['vship'], $order_id, $response);
				} else {
					$response = array_fill(0, 200, 1);
				}

				$response_status = $response[0];
				$response_msg = $response[3];

				if(((strpos($response_msg,'TESTMODE')!==false) || ($response_status != 1))) {
					$vars['environment']['ajax_result']['messages'][] = array(
						'Error processing payment.',
						'error'
					);
					$return_url = $vars['url']['handler']->wrap($vars, '/billing-details');
					tpt_request::redirect($vars, $return_url);
					return false;
				}


				$status = 8;
				$paid_mark = 1;
				$pmsales = 'cc';
				$pmstring = 'Credit Card|' . (!empty($response[50])?$response[50]:'');

				//include(TPT_PAYMENTSCRIPTS_DIR . DIRECTORY_SEPARATOR . 'products.php');

				//tpt_dump($response);
				//tpt_dump(empty($response), true);
				if(empty($response)) {
					$return_url = $vars['url']['handler']->wrap($vars, '/billing-details');
				} else {
					$return_url = $vars['url']['handler']->wrap($vars, '/order-details') . '?order_id=' . $order_id;
				}
		} else {
				$url = $vars['config']['paypal']['api_nvp_endpoint'];
				$ectotal = number_format(round($vtotal, 2), 2);
				$ecsubtotal = number_format(round($vsubtotal, 2), 2);
				$ecshipping = number_format(round($vship, 2), 2);
				$ectax = number_format(round($vtax, 2), 2);
				//tpt_dump($subtotal, true);
				$body = tpt_PayPal::setExpressCheckout($vars, $products, $ecsubtotal, $ecshipping, $ectax, $ectotal, $order_id);

				//tpt_dump($body, true);
				$xchkres = sendSingleRequest($url, $body, 'POST'/*, $resetcookie=true, $headers=array()*/);
				//var_dump($url);
				//var_dump($body);
				//tpt_dump($xchkres, true);
				//die();
				$rsp = array();
				parse_str($xchkres['body'], $rsp);
				tpt_logger::log_paypal_response_setexpresscheckout($vars, __FILE__, http_build_query($body), $rsp);
				//var_dump($rsp);
				//die();

				/*
				$token = $rsp['TOKEN'];
				$details = tpt_PayPal::getExpressCheckoutDetails($vars, $token);
				//tpt_dump($details, true);
				*/

				$return_url = $vars['config']['paypal']['api_endpoint_insertorderredirect'].'?cmd='.$vars['config']['paypal']['api_nvp_param'].'&token='.urlencode($rsp['TOKEN']);

				//include(TPT_PAYMENTSCRIPTS_DIR . DIRECTORY_SEPARATOR . 'tpt-paypal.php');
				$status = 33;
				$paid_mark = 3;
				$pmsales = 'paypal';
		}


		$rush_order = 0;
		$closest_due_rush_order = 0;
		$fproduct = array_shift($products);
		$fproduct_arr = array(&$fproduct);

		$fproductdata = $orders_module->getProductsDataArray($vars, $fproduct_arr);
		$productdata = $orders_module->getProductsDataArray($vars, $products);
		//tpt_dump($productdata, true);



		$forderdata = array(
				'comments'=>self::$delivery_notes,
				'order_purchased'=>date("m-d-Y"),
				//'order_purchased_timestamp',
				'status'=>$status,
				//'tracking_info',
				//'tracking_code',
				'quote_type'=>'detail',
				//'admin_comment',
				//'admin_upload',
				//'problem_upload',
				//'po_upload',
				//'quote_id',
				//'tracking_code_status',
				'order_id'=>$order_id,
				'shipping_method'=>$shipping_method,
				'payment_method'=>$pmstring,
				//'testimonial',
				'cus_payment_details'=>$cus_payment_details,
				//'old_payment_details',
				//'design_proofs',
				//'problem_desc',
				'tax_class'=>implode(',', $tax_class),
				//'po_order',
				'date_q'=>date("Y-m-d"),
				'texas_order'=>tpt_current_user::isTexasBuyer($vars),
				//'tax_exempt',
				//'invoice_sent',
				//'invoice_date',
				'paid_mark'=>$paid_mark,
				//'purchase_order',
				'timestamp'=>$vars['environment']['request_time'],
				'submit_token'=>!empty($_POST['submit_token'])?$_POST['submit_token']:''
										);

		$fpricingdata = $fproduct->getPricingDataArray($vars);
		$fpricingdata['Shipping'] = $vship;
		$fpricingdata['Tax'] = $vtax;

		$fproductdata = reset($fproductdata);
		//tpt_dump($forderdata, true);
		//tpt_dump($customerdata, true);

		//$google_ecommerce_js = '';

		$quote_id = $orders_module->insertOrderRow($vars, $fproductdata, $fpricingdata, $forderdata, $customerdata);
		//tpt_dump($quote_id);
		$prow_id = $orders_module->insertOrderProductRow($vars, $quote_id, $fproduct, $quote_id);
		//tpt_dump($prow_id);
		//$orders_module->insertOrderClipartsRow($vars, $quote_id, $fproduct);
		//$orders_module->insertOrderExtrasRow($vars, $prow_id, $fproduct);
		//$google_ecommerce_js .= tpt_functions::getGoogleEcommerceCode($vars, $order_id, $fproduct, $fpricingdata);

		$orderdata = array(
			//'comments'=>self::$delivery_notes,
			'order_purchased'=>date("m-d-Y"),
			//'order_purchased_timestamp',
			//'status',
			//'tracking_info',
			//'tracking_code',
			'quote_type'=>'detail',
			//'admin_comment',
			//'admin_upload',
			//'problem_upload',
			//'po_upload',
			'quote_id'=>$quote_id,
			//'tracking_code_status',
			//'order_id',
			//'shipping_method'=>$shipping_method,
			//'payment_method'=>$payment_method,
			//'testimonial',
			//'cus_payment_details'=>$cus_payment_details,
			//'old_payment_details',
			//'design_proofs',
			//'problem_desc',
			//'po_order',
			//'date_q',
			//'texas_order',
			//'tax_exempt',
			//'invoice_sent',
			//'invoice_date',
			//'paid_mark',
			//'purchase_order',
			//'timestamp',
			//'submit_token'
									);

		//tpt_dump($products, true);
		if(!empty($fproduct->data['rush_order'])) {
			$rush_order = 1;
			$closest_due_rush_order = $fproduct->data['rush_order'];
		}

		// new order format section
		$tpt                            = array();
		$tpt['totals']                  = array();
		$tpt['order']                   = array();
		$tpt['payment']                 = array();
		$tpt['status']                  = array();
		$tpt['tracking']                = array();
		$tpt['exproducts']              = array();
		$tpt['old_insert_succeeded']    = true;
		$tpt['old_invoice_succeeded']   = true;
		$tpt['old_quote_id']            = $quote_id;
		$tpt['old_order_id']            = $order_id;
		$tpt['ordertime']            = $vars['environment']['request_time'];
		$tpt['exproducts'][]         = array(
			'old_id' => $quote_id,
			'old_product_id' => $prow_id,
			'product' => $fproduct
		);
		// new order format section END
		foreach($products as $key=>$product) {
			if(!empty($product->data['rush_order'])) {
				$rush_order = 1;

				if($closest_due_rush_order > $product->data['rush_order']) {
					$closest_due_rush_order = $product->data['rush_order'];
				}
			}
			$pricingdata = $product->getPricingDataArray($vars);
			$row_id = $orders_module->insertOrderRow($vars, $productdata[$key], $pricingdata, $orderdata, $customerdata);
			$prow_id = $orders_module->insertOrderProductRow($vars, $row_id, $product, $quote_id);
			//$orders_module->insertOrderClipartsRow($vars, $row_id, $product);
			//$orders_module->insertOrderExtrasRow($vars, $prow_id, $product);
			//$google_ecommerce_js .= tpt_functions::getGoogleEcommerceCode($vars, $order_id, $product, $pricingdata);

			// new order format section
			$tpt['exproducts'][]         = array(
				'old_id' => $row_id,
				'old_product_id' => $prow_id,
				'product' => $product
			);
			// new order format section END
		}

		// new order format section
		$tpt['totals']['products_discount'] = GLOBAL_CUSTOMPRODUCT_PRICEOFF_PERCENT;

		$tpt['totals']['subtotal']          = $vsubtotal;
		$tpt['totals']['shipping']          = $vship;
		$tpt['totals']['tax']               = $vtax;
		$tpt['totals']['total']             = $vtotal;

		$tpt['totals']['paypal_tax']        = 0;
		$tpt['totals']['order_discount']    = 0;

		$tpt['totals']['texas_order']       = tpt_current_user::isTexasBuyer($vars);
		$tpt['totals']['tax_exempt']        = 0;


		$tpt['order']['id']                 = 0;
		$tpt['order']['type']               = 1;

		$tpt['shipping']['shipping_method'] = $shipping_method;

		$tpt['payment']['payment_method']  = $payment_method;
		$tpt['payment']['payment_details'] = $cus_payment_details;

		$tpt['status']['status']         = $status;
		$tpt['status']['invoice_sent']  = 0;
		$tpt['status']['customer_email'] = '';
		$tpt['status']['admin_email']    = '';

		$orders_module->insertOrdersData($vars, $tpt['order'], $tpt['exproducts'], $tpt['totals'], $tpt['ordertime'], $tpt['shipping'], $tpt['payment'], $tpt['status'], $tpt['tracking'], $vars['user'], $tpt['old_quote_id'], $tpt['old_order_id']);
		// new order format section END

		$orders_module->insertOrderTotalsRow($vars, $quote_id, $vsubtotal, $vship, $vtax);
		$orders_module->createAdminComment($vars, $quote_id, $status, '', "System: Checkout (tpt_checkout)");
		$orders_module->insertOrderCart($vars, $order_id);
		if(!empty($rush_order) && empty($rushorder_module->moduleData['id'][$closest_due_rush_order]['dummy'])) {
			$orders_module->insertOrderMark($vars, $quote_id, 9);




			// Set Need By Date
			$need_by_timestamp = $rushorder_module->getNextNeedByDateTimestamp($vars, $vars['environment']['request_time'], $closest_due_rush_order);
			$orders_module->setNeedByDate($vars, $quote_id, $need_by_timestamp);
		}

		if($payment_method == 1) {
				//$totals = $auth_response_values[9];
			$orders_module->insertSalesCalcRow($vars, $quote_id, $order_id, $vtotal, $pmsales, $status);

			if(!isDump() || empty($vars['config']['dev']['debuginsertorder_skipemail'])) {
				//include(TPT_PAYMENTSCRIPTS_DIR . DIRECTORY_SEPARATOR . 'send-email.php');
				array_unshift($productdata, $fproductdata);
				array_unshift($products, $fproduct);

				$odata = $forderdata+array('quote_id'=>$orderdata['quote_id']);
				$pdata = array('productdata'=>$productdata, 'products'=>$products);
				$htotals = $vtotals;
				array_walk($htotals, 'format_price_array');
				$orders_module->sendOrderPlacedEmail($vars, $odata, $customerdata, $pdata, $htotals);
			}

			$_SESSION['order_products'] = amz_cart::$products;
			$_SESSION['order_totals'] = $totals;
			if(!isDump() || empty($vars['config']['dev']['debuginsertorder_skipclearcart'])) {
				amz_cart::clear($vars);

				tpt_current_user::update_store_cart_id($vars, 0);
			}


			/*
			$vars['environment']['ajax_result']['execute_onload']['head'][] .= <<< EOT
<script type="text/javascript">
//<![CDATA[
ga('require', 'ecommerce');
$google_ecommerce_js
ga('ecommerce:send');
//]]>
</script>
EOT;
			*/


			$vars['environment']['ajax_result']['messages'][] = array(
				'Order placed.',
				'message'
			);
		} else {
		}



		if(isDump() && !empty($vars['config']['dev']['debuginsertorder_noredirect'])) {
			//tpt_dump($return_url);
			//tpt_dump('----------------------------------------------------------------------------', true);
		} else {
            tpt_request::redirect($vars, $return_url);
        }
	}


	static function finalizePaypalOrder(&$vars, $token, $payer_id, $qo, $details) {
		$orders_module = getModule($vars, "Orders");
		$users_module = getModule($vars, "Users");

		$order_id = $qo['order_id'];
		$quote_id = $qo['id'];
		$user_id = $qo['customer_id'];

		//$quote_id = $orders_module->getOrderRowId($vars, $order_id);
		//tpt_dump($qo, true);

		$products = $orders_module->convertOldProductsData($vars, $qo['id'], true);
		$products = $products['exproducts'];
		$productdata = $orders_module->getProductsDataArray($vars, $products);

		$totALL = amz_cart::getProductsTotals($vars, $products, false /*$force recalculate=false*/);
		$products_count         = count($products);
		$vtotals = $totALL['pricing']['values']['customer_price'];
		//$totals = $totALL['pricing']['html']['customer_price'];
		$totals                 = amz_checkout::getOrderTotals($vars, $order_id, $vtotals);
		$vship                  = $totals['values']['shipping'];
		$vsubtotal              = $totals['values']['subtotal'];
		$vtotal                 = $totals['values']['total'];
		$vtax                    = $totals['values']['tax'];
		//$tx_ordr                = $totals['values']['texas_order'];
		$vtotals = compact('vship', 'vsubtotal', 'vtax', 'vtotal'
			//, 'tx_ordr'
		);
		$shipping = number_format(round($vship, 2), 2);
		$total = number_format(round($vtotal, 2), 2);
		$taxx = number_format(round($vtax, 2), 2);
		$subtotal = number_format(round($vsubtotal, 2), 2);

		$shipping_method = $qo['shipping_method'];

		if(empty($shipping_method)) {
			$shipping_address = $vars['user']['addresses']['shipping'];
			if($shipping_address['country'] == 1) {
				$shipping_method = 1;
			} else {
				$shipping_method = 2;
			}
		}

		//include(TPT_PAYMENTCLASSES_DIR . DIRECTORY_SEPARATOR . 'process-paypal.php');

		//$token = $_REQUEST['token'];
		//$products = amz_cart::$products;
		//$totals = amz_checkout::getTotals($vars);

		//$details = tpt_PayPal::getExpressCheckoutDetails($tpt_vars, $token, __FILE__);
		//$subtotal = $details['PAYMENTREQUEST_0_AMT'];
		//$invoice_id = $details['PAYMENTREQUEST_0_INVNUM'];
		$invoice_id = $order_id;
		//$payer_id = $details['PAYERID'];
		//$payer_id = $_REQUEST['PayerID'];
		//tpt_dump($subtotal);
		//tpt_dump($shipping);
		//tpt_dump($taxx);
		//tpt_dump($total);
		//tpt_dump($invoice_id);
		//tpt_dump($token, true);
		$body = tpt_PayPal::doExpressCheckoutPayment($vars, $products, $subtotal, $shipping, $taxx, $total, $invoice_id, $token, $payer_id);
		$url = $vars['config']['paypal']['api_nvp_endpoint'];
		$xchkres = sendSingleRequest($url, $body, 'POST'/*, $resetcookie=true, $headers=array()*/);
		$rsp = array();
		parse_str($xchkres['body'], $rsp);
		tpt_logger::log_paypal_response_doexpresscheckoutpayment($vars, __FILE__, http_build_query($body), $rsp);


		$google_ecommerce_js = '';
		foreach($products as $key=>$product) {
			$pricingdata = $product->getPricingDataArray($vars);
			$google_ecommerce_js .= tpt_functions::getGoogleEcommerceCode($vars, $order_id, $product, $pricingdata);
		}
		$vars['environment']['ajax_result']['execute_onload']['head'][] .= <<< EOT
<script type="text/javascript">
//<![CDATA[
ga('require', 'ecommerce');
$google_ecommerce_js
ga('ecommerce:send');
//]]>
</script>
EOT;

		$payment_details = tpt_PayPal::generateReceipt($vars, $qo, $details, $rsp, $payer_id);


		$status = 9;
		$paid_mark = 1;
		$payment_method = 2;
		$pmstring = $pmsales = 'PayPal|Status Complete';

		//include(TPT_PAYMENTSCRIPTS_DIR . DIRECTORY_SEPARATOR . 'products.php');

		$orderdata = array(
			//'comments'=>self::$delivery_notes,
			//'order_purchased'=>date("m-d-Y"),
			//'order_purchased_timestamp',
			//'status'=>$status,
			//'tracking_info',
			//'tracking_code',
			//'quote_type'=>'detail',
			//'admin_comment',
			//'admin_upload',
			//'problem_upload',
			//'po_upload',
			//'quote_id',
			//'tracking_code_status',
			//'order_id'=>$order_id,
			//'shipping_method'=>$shipping_method,
			'payment_type'=>$payment_method,
			'payment_method'=>$pmstring,
			//'testimonial',
			//'cus_payment_details'=>$payment_details,
			//'old_payment_details',
			//'design_proofs',
			//'problem_desc',
			//'tax_class'=>$tax_class,
			//'po_order',
			//'date_q'=>date("Y-m-d"),
			//'texas_order'=>tpt_current_user::isTexasBuyer($vars),
			//'tax_exempt',
			//'invoice_sent',
			//'invoice_date',
			'paid_mark'=>$paid_mark,
			//'purchase_order',
			//'timestamp'=>$vars['environment']['request_time'],
			//'submit_token'=>!empty($_POST['submit_token'])?$_POST['submit_token']:''
		);
		$orders_module->updateOrderRow($vars, array(), array(), $orderdata, array(), $quote_id);
		$orders_module->updateOrderPaymentDetails($vars, $order_id, $payment_details);

		$orders_module->createAdminComment($vars, $quote_id, $status, '', "System: Checkout - PP Payment (tpt_checkout)");
		$pmsales = 'paypal';
		$orders_module->insertSalesCalcRow($vars, $quote_id, $order_id, $vtotal, $pmsales, $status);

		//tpt_dump('1', true);
		//if(empty($_GET['invoice'])) {
		//	include(TPT_PAYMENTSCRIPTS_DIR . DIRECTORY_SEPARATOR . 'send-email.php');
		//}



		/*
		if (!empty($tpt['old_invoice_succeeded'])) {
			$tpt['totals'] = array();
			//$tpt['totals']['products_discount'] = GLOBAL_CUSTOMPRODUCT_PRICEOFF_PERCENT;
			//$tpt['totals']['subtotal'] = $vsubtotal;
			//$tpt['totals']['shipping'] = $vship;
			//$tpt['totals']['tax'] = $tax;
			//$tpt['totals']['order_discount'] = 0;
			//$tpt['totals']['total'] = $vtotal;
			//$tpt['totals']['texas_order'] = $tx_ordr;
			//$tpt['totals']['tax_exempt'] = 0;
			$query			= <<< EOT
SELECT * FROM `$otable` WHERE `old_order_id`=$order_id_new
EOT;
			$vars['db']['handler']->query($query, __FILE__, __LINE__);
			$o = $vars['db']['handler']->fetch_assoc_list('id', false);
			if (!empty($o)) {
				$o = reset($o);

				$tpt['order']			 = array();
				$tpt['order']['id']	 = $o['id'];
				$tpt['order']['type'] = 0;

				$tpt['shipping'] = 0;

				$tpt['payment'] = array(
					'method' => $payment_method,
					'details' => $tpt['payment']['payment_html']
				);

				$tpt['status'] 						= array();
				$tpt['status']['status']			= 9;
				$tpt['status']['invoice_sent']	= 1;
				$tpt['status']['customer_email'] = $email_header_html . $html_email_template;
				$tpt['status']['admin_email'] 	= $admin_email_header_html . $html_email_template . $admin_email_footer_html;

				$tpt['tracking'] = array();

				$orders_module->insertOrdersData($vars, $tpt['order'], $tpt['exproducts'], $tpt['totals'], $tpt['ordertime'], $tpt['shipping'], $tpt['payment'], $tpt['status'], $tpt['tracking'], $vars['user'], $tpt['old_quote_id'], $tpt['old_order_id']);
			}
		}
		*/

		//mysql_query("Insert into tpt_order_carts (order_id, user_id, timestamp, cart_pdt, user_data, cart_totals) values (".$order_id_new.",".$vars['user']['userid'].",".time().",'".$cart_id.",', '".$user_data."', '".$cart_totals."')") or die(mysql_error());

		if(!isDump() || empty($vars['config']['dev']['debuginsertorder_skipemail'])) {
			//include(TPT_PAYMENTSCRIPTS_DIR . DIRECTORY_SEPARATOR . 'send-email.php');
			//$odata = $orderdata+array('quote_id'=>$quote_id);
			$odata = array(
				//'comments'=>self::$delivery_notes,
				'order_purchased'=>$qo['order_purchased'],
				//'order_purchased_timestamp',
				//'status'=>$status,
				//'tracking_info',
				//'tracking_code',
				//'quote_type'=>'detail',
				//'admin_comment',
				//'admin_upload',
				//'problem_upload',
				//'po_upload',
				//'quote_id',
				//'tracking_code_status',
				'order_id'=>$order_id,
				//'shipping_method'=>$shipping_method,
				//'payment_method'=>$pmstring,
				//'testimonial',
				//'cus_payment_details'=>$payment_details,
				//'old_payment_details',
				//'design_proofs',
				//'problem_desc',
				//'tax_class'=>$tax_class,
				//'po_order',
				//'date_q'=>date("Y-m-d"),
				//'texas_order'=>tpt_current_user::isTexasBuyer($vars),
				//'tax_exempt',
				//'invoice_sent',
				//'invoice_date',
				//'paid_mark'=>$paid_mark,
				//'purchase_order',
				//'timestamp'=>$vars['environment']['request_time'],
				//'submit_token'=>!empty($_POST['submit_token'])?$_POST['submit_token']:''
			);
			$pdata = array('productdata'=>$productdata, 'products'=>$products);

			$htotals = $vtotals;
			array_walk($htotals, 'format_price_array');

			$customerdata = $users_module->getUserDataArray($vars, $user_id);
			$orders_module->sendOrderPlacedEmail($vars, $odata, $customerdata, $pdata, $htotals);
		}






		if(!isDump() || empty($vars['config']['dev']['debuginsertorder_skipclearcart'])) {
			amz_cart::clear($vars);
		}

		$vars['environment']['ajax_result']['messages'][] = array(
			'Order placed.',
			'message'
		);
		tpt_current_user::update_store_cart_id($vars, 0);

		$return_url = $vars['url']['handler']->wrap($vars, '/order-details') . '?order_id=' . $order_id;
		if(isDump() && !empty($vars['config']['dev']['debuginsertorder_noredirect'])) {
			//tpt_dump($return_url);
			//tpt_dump('----------------------------------------------------------------------------', true);
		}
		tpt_request::redirect($vars, $return_url);
	}


	static function getTotals(&$vars) {
		$texas_order = 0;

		$shipping_address = $vars['user']['addresses']['shipping'];
		//var_dump($shipping_address);die();

		$tax = 0;
		$shipping_state = 0;
		if(($shipping_address['country'] == 1) && !empty($shipping_address['state']) && (intval($shipping_address['state'], 10) == 44)) {
				$texas_order = 1;
				$tax = 0.0825;
		}

		$values = array();

		//tpt_dump(self::$shipping_method);
		//tpt_dump(amz_shipping::$methods[self::$shipping_method]);
		//tpt_dump(!is_object(amz_shipping::$methods[self::$shipping_method]), true);
		if(empty(self::$shipping_method) || empty(amz_shipping::$methods[self::$shipping_method]) || !is_object(amz_shipping::$methods[self::$shipping_method])) {
				$values['shipping'] = 0;
		} else {
				$shipping_rates = amz_shipping::$methods[self::$shipping_method]->getRate($vars);
				$values['shipping'] = $shipping_rates['values']['total'];
		}
		$values['texas_order'] = $texas_order;
		$values['subtotal'] = floatval(amz_cart::$totals['pricing']['values']['customer_price']);

		$subtotal = $values['subtotal'] + $values['shipping'];
		$values['tax'] = round($subtotal*$tax, 2);
		$values['total'] = $values['subtotal'] + $values['shipping'] + $values['tax'];

		$html = $values;
		array_walk($html, 'format_price_array');

		return array('html'=>$html, 'values'=>$values);
	}

	static function getOrderTotals(&$vars, $orderid, $subtotal=0) {
		if(empty($orderid))
				return false;



		$order = $vars['db']['handler']->getData($vars, 'temp_custom_orders', '*', ' `order_id`='.intval($orderid, 10), 'order_id', false);

		if(is_array($order)) {
				$order = reset($order);
		}

		$order_stats = $vars['db']['handler']->getData($vars, 'tpt_order_carts', '*', ' `order_id`='.intval($orderid, 10), 'order_id', false);

		if(is_array($order_stats)) {
				$order_stats = reset($order_stats);
		}

		//var_dump($order_stats);die();

		if(!empty($order_stats) && (($user = @unserialize($order_stats['user_data'])) !== false)) {
				if(!empty($user)) {

					$shipping_address = $user['addresses']['shipping'];

					$tax = 0;
					$shipping_state = 0;
					if(($shipping_address['country'] == 1) && !empty($shipping_address['state']) && (intval($shipping_address['state'], 10) == 44))
						$tax = 0.0825;

					$values = array();

					$values['shipping'] = 0;
					if(!empty(amz_shipping::$methods[$order['shipping_method']])) {
						$shipping_rates = amz_shipping::$methods[$order['shipping_method']]->getRate($vars);
						$values['shipping'] = $shipping_rates['values']['total'];
					}
					//$values['subtotal'] = floatval(amz_cart::$totals['pricing']['values']['customer_price']);
					$values['subtotal'] = floatval($subtotal);

					$subtotal = $values['subtotal'] + $values['shipping'];
					$values['tax'] = round($subtotal*$tax, 2);
					$values['total'] = $values['subtotal'] + $values['shipping'] + $values['tax'];

					$html = $values;
					array_walk($html, 'format_price_array');

					return array('html'=>$html, 'values'=>$values);
				}
		} else {
				$orders_module = getModule($vars, "Orders");

				$df = array(
					'fields'=>'*',
					'left_join_secondary'=>0,
					'limit'=>1,
					'ordering'=>' `id` DESC',
					'where'=>array(' (`quote_id`=0 OR `quote_id`=\'\' OR `quote_id` IS NULL) AND is_deleted = 0 AND `order_id`='.$orderid)
					);
				$q = $orders_module->getOrdersData($vars, $df, true);
				//tpt_dump($df, true);
				//tpt_dump($q, true);

				$df = array(
					'fields'=>'*',
					'left_join_secondary'=>0,
					'limit'=>0,
					'ordering'=>' `id` DESC',
					//'debug'=>1,
					'where'=>array(' (`id`='.$q['id'].' OR `quote_id`='.$q['id'].') AND is_deleted = 0 ')
					);
				$qos = $orders_module->getOrdersData($vars, $df, false);
				//tpt_dump($qos, true);

				$values = array();

				$values['shipping'] = 0;
				$values['subtotal'] = 0;
				$values['tax'] = 0;
				$values['total'] = 0;
				foreach($qos as $qo) {
					$values['shipping'] += round(floatval($qo['Shipping']), 2);
					$values['subtotal'] += round(floatval($qo['Total_Price']), 2);
					$values['tax'] += round(floatval($qo['Tax']), 2);
					tpt_logger::dump($vars, $qo, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), '$qo', __FILE__.' '.__LINE__);
					tpt_logger::dump($vars, $values['shipping'], debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), '$values[\'shipping\']', __FILE__.' '.__LINE__);
					tpt_logger::dump($vars, $values['subtotal'], debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), '$values[\'subtotal\']', __FILE__.' '.__LINE__);
					tpt_logger::dump($vars, $values['tax'], debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), '$values[\'tax\']', __FILE__.' '.__LINE__);
				}

				$values['total'] = $values['subtotal'] + $values['shipping'] + $values['tax'];

				$html = $values;
				//tpt_dump($values, true);
				array_walk($html, 'format_price_array');

				return array('html'=>$html, 'values'=>$values);
		}
	}


}


class tpt_checkout_controller {
	function __construct() {}

	function afterContent(&$vars) {

		$_SESSION['templay']['checkout']['shipping_method'] = amz_checkout::$shipping_method;
		$_SESSION['templay']['checkout']['payment_method'] = amz_checkout::$payment_method;
		$_SESSION['templay']['checkout']['delivery_notes'] = amz_checkout::$delivery_notes;
		$_SESSION['templay']['checkout']['card_details'] = amz_checkout::$card_details;
	}
}

$shmethod = 0;
if(!empty($_SESSION['templay']['checkout']['shipping_method'])) {
	$shmethod = intval($_SESSION['templay']['checkout']['shipping_method'], 10);
}
amz_checkout::$shipping_method = $shmethod;

$pm = 2;
if(!empty($_SESSION['templay']['checkout']['payment_method'])) {
	$pm = intval($_SESSION['templay']['checkout']['payment_method'], 10);
}
//var_dump(amz_checkout::$shipping_method);
amz_checkout::$payment_method = empty($pm)?2:$pm;

$dn = '';
if(!empty($_SESSION['templay']['checkout']['delivery_notes'])) {
	$dn = $_SESSION['templay']['checkout']['delivery_notes'];
}
amz_checkout::$delivery_notes = $dn;

$cd = 0;
if(!empty($_SESSION['templay']['checkout']['card_details'])) {
	$cd = intval($_SESSION['templay']['checkout']['card_details'], 10);
}
amz_checkout::$card_details = $cd;
$tpt_vars['environment']['url_processors'][] = new tpt_checkout_controller($tpt_vars);
