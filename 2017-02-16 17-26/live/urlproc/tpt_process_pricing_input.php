<?php
defined('TPT_INIT') or die('access denied');

//var_dump('asfasfasf');die();

if(strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
	$task = $_POST['task'];
	//$tpt_vars = &$tpt_vars;

	/*
	if((strtolower($task) == 'cart.add_stock')) {

		if(isset($_POST['productid'])) {
			$productid = intval($_POST['productid'], 10);
			$qty = max(intval($_POST['qty'], 10), 1);
			$sptype = amz_cart::$stockProductsTypesData[$productid];

			$search_fields = array();

			if(!empty($sptype['qty_control'])) {
			}
			if(!empty($sptype['bandcolor_control'])) {
				if(!empty($sptype['colors'])) {
					$color_definitions = explode('|', $sptype['colors']);
					$cdefs = array();
					foreach($color_definitions as $cdef) {
						$pcdef = explode('^', $cdef);
						$cdefs[$pcdef[0]] = array('value'=>$pcdef[1], 'label'=>$pcdef[2]);
					}

					$query = 'SELECT DISTINCT(`band_color`) FROM `tpt_stock_products` WHERE `product_type_id`='.$productid;
					$tpt_vars['db']['handler']->query($query, __FILE__);
					$band_colors = $tpt_vars['db']['handler']->fetch_assoc_list('band_color', false);
					$band_colors = array_keys($cdefs);

					$bandcolor = intval($_POST['band_color'], 10);

					$search_fields['band_color'] = $cdefs[$bandcolor]['value'];

					if(!in_array($bandcolor, $band_colors)) {
						$tpt_vars['template_data']['valid_form'] = false;
						$tpt_vars['template_data']['invalid_fields']['band_color'] = 1;
						$tpt_vars['environment']['ajax_result']['messages'][] = array('Please choose band color!', 'error');
					}
				} else {
					$query = 'SELECT DISTINCT(`band_color`) FROM `tpt_stock_products` WHERE `product_type_id`='.$productid;
					$tpt_vars['db']['handler']->query($query, __FILE__);
					$band_colors = $tpt_vars['db']['handler']->fetch_assoc_list('band_color', false);
					$band_colors = array_keys($band_colors);

					$bandcolor = intval($_POST['band_color'], 10);

					$search_fields['band_color'] = $bandcolor;

					if(!in_array($bandcolor, $band_colors)) {
						$tpt_vars['template_data']['valid_form'] = false;
						$tpt_vars['template_data']['invalid_fields']['band_color'] = 1;
						$tpt_vars['environment']['ajax_result']['messages'][] = array('Please choose band color!', 'error');
					}
				}
			}
			if(!empty($sptype['bandstyle_control'])) {
				$query = 'SELECT DISTINCT(`band_style`) FROM `tpt_stock_products` WHERE `product_type_id`='.$productid;
				$tpt_vars['db']['handler']->query($query, __FILE__);
				$band_styles = $tpt_vars['db']['handler']->fetch_assoc_list('band_style', false);
				$band_styles = array_keys($band_styles);

				$bandstyle = intval($_POST['band_style'], 10);

				$search_fields['band_style'] = $bandstyle;

				if(!in_array($bandstyle, $band_styles)) {
					$tpt_vars['template_data']['valid_form'] = false;
					$tpt_vars['template_data']['invalid_fields']['band_style'] = 1;
					$tpt_vars['environment']['ajax_result']['messages'][] = array('Please choose message style!', 'error');
				}
			}
			if(!empty($sptype['bandsize_control'])) {
				$query = 'SELECT DISTINCT(`band_size`) FROM `tpt_stock_products` WHERE `product_type_id`='.$productid;
				$tpt_vars['db']['handler']->query($query, __FILE__);
				$band_sizes = $tpt_vars['db']['handler']->fetch_assoc_list('band_size', false);
				$band_sizes = array_keys($band_sizes);

				$bandsize = intval($_POST['band_size'], 10);

				$search_fields['band_size'] = $bandsize;

				if(!in_array($bandsize, $band_sizes)) {
					$tpt_vars['template_data']['valid_form'] = false;
					$tpt_vars['template_data']['invalid_fields']['band_size'] = 1;
					$tpt_vars['environment']['ajax_result']['messages'][] = array('Please choose band size!', 'error');
				}
			}

			if($tpt_vars['template_data']['valid_form']) {
				$spid = 0;
				$qfields = array();
				foreach($search_fields as $fname=>$fvalue) {
					$qfields[] = '`'.$fname.'`='.$fvalue;
				}
				$qfields[] = 'product_type_id='.$productid;
				$qfields = implode(' AND ', $qfields);
				$query = 'SELECT * FROM `tpt_stock_products` WHERE '.$qfields;
				//var_dump($query);die();
				$tpt_vars['db']['handler']->query($query, __FILE__);
				$stock_product = $tpt_vars['db']['handler']->fetch_assoc();

				amz_cart::add($tpt_vars, new amz_stockproduct($tpt_vars, $stock_product['id'], $qty));
			}

		}

		$return_url = $tpt_vars['environment']['go_back_url'];
		tpt_request::redirect($tpt_vars, $return_url);






	} else if((strtolower($task) == 'cart.add_bundle')) {
		//var_dump($_POST);die();
		if(!empty($_POST['bundleid'])) {
			$bundleid = intval($_POST['bundleid'], 10);
			$bundle = amz_cart::$bundlesData[$bundleid];
			$productsTypes = explode(',', $bundle['stock_products_types_ids']);
			//var_dump($productsTypes);die();
			$pids = array();
			foreach($productsTypes as $productId) {
				$productid = intval($productId, 10);
				$qty = max(intval($_POST['qty'], 10), 1);
				$sptype = amz_cart::$stockProductsTypesData[$productid];
				//var_dump($sptype);die();

				$search_fields = array();

				if(!empty($sptype['qty_control'])) {
				}
				if(!empty($sptype['bandcolor_control'])) {
					if(!empty($sptype['colors'])) {
						$color_definitions = explode('|', $sptype['colors']);
						$cdefs = array();
						foreach($color_definitions as $cdef) {
							$pcdef = explode('^', $cdef);
							$cdefs[$pcdef[0]] = array('value'=>$pcdef[1], 'label'=>$pcdef[2]);
						}

						$query = 'SELECT DISTINCT(`band_color`) FROM `tpt_stock_products` WHERE `product_type_id`='.$productid;
						$tpt_vars['db']['handler']->query($query, __FILE__);
						$band_colors = $tpt_vars['db']['handler']->fetch_assoc_list('band_color', false);
						$band_colors = array_keys($cdefs);

						$bandcolor = intval($_POST['band_color'][$productId], 10);

						$search_fields['band_color'] = $cdefs[$bandcolor]['value'];

						if(!in_array($bandcolor, $band_colors)) {
							$tpt_vars['template_data']['valid_form'] = false;
							$tpt_vars['template_data']['invalid_fields']['band_color'] = 1;
							$tpt_vars['environment']['ajax_result']['messages'][] = array('Please choose band color!', 'error');
						}
					} else {
						$query = 'SELECT DISTINCT(`band_color`) FROM `tpt_stock_products` WHERE `product_type_id`='.$productid;
						$tpt_vars['db']['handler']->query($query, __FILE__);
						$band_colors = $tpt_vars['db']['handler']->fetch_assoc_list('band_color', false);
						$band_colors = array_keys($band_colors);

						$bandcolor = intval($_POST['band_color'][$productId], 10);

						$search_fields['band_color'] = $bandcolor;

						if(!in_array($bandcolor, $band_colors)) {
							$tpt_vars['template_data']['valid_form'] = false;
							$tpt_vars['template_data']['invalid_fields']['band_color'] = 1;
							$tpt_vars['environment']['ajax_result']['messages'][] = array('Please choose band color!', 'error');
						}
					}
				}
				if(!empty($sptype['bandstyle_control'])) {
					$query = 'SELECT DISTINCT(`band_style`) FROM `tpt_stock_products` WHERE `product_type_id`='.$productid;
					$tpt_vars['db']['handler']->query($query, __FILE__);
					$band_styles = $tpt_vars['db']['handler']->fetch_assoc_list('band_style', false);
					$band_styles = array_keys($band_styles);

					$bandstyle = intval($_POST['band_style'][$productId], 10);

					$search_fields['band_style'] = $bandstyle;

					if(!in_array($bandstyle, $band_styles)) {
						$tpt_vars['template_data']['valid_form'] = false;
						$tpt_vars['template_data']['invalid_fields']['band_style'] = 1;
						$tpt_vars['environment']['ajax_result']['messages'][] = array('Please choose message style!', 'error');
					}
				}
				if(!empty($sptype['bandsize_control'])) {
					$query = 'SELECT DISTINCT(`band_size`) FROM `tpt_stock_products` WHERE `product_type_id`='.$productid;
					$tpt_vars['db']['handler']->query($query, __FILE__);
					$band_sizes = $tpt_vars['db']['handler']->fetch_assoc_list('band_size', false);
					$band_sizes = array_keys($band_sizes);

					$bandsize = intval($_POST['band_size'][$productId], 10);

					$search_fields['band_size'] = $bandsize;

					if(!in_array($bandsize, $band_sizes)) {
						$tpt_vars['template_data']['valid_form'] = false;
						$tpt_vars['template_data']['invalid_fields']['band_size'] = 1;
						$tpt_vars['environment']['ajax_result']['messages'][] = array('Please choose band size!', 'error');
					}
				}

				if($tpt_vars['template_data']['valid_form']) {
					$spid = 0;
					$qfields = array();
					foreach($search_fields as $fname=>$fvalue) {
						$qfields[] = '`'.$fname.'`='.$fvalue;
					}
					$qfields[] = 'product_type_id='.$productid;
					$qfields = implode(' AND ', $qfields);
					$query = 'SELECT * FROM `tpt_stock_products` WHERE '.$qfields;
					//var_dump($query);die();
					$tpt_vars['db']['handler']->query($query, __FILE__);
					$stock_product = $tpt_vars['db']['handler']->fetch_assoc();

					$pids[] = $stock_product['id'];
				}
			}

			if($tpt_vars['template_data']['valid_form']) {
				amz_cart::add($tpt_vars, new amz_bundle($tpt_vars, $bundleid, $pids, $qty));
			}

		}

		$return_url = $tpt_vars['environment']['go_back_url'];
		tpt_request::redirect($tpt_vars, $return_url);






	} else
	*/
	if((strtolower($task) == 'cart.add_stock')) {

		$products = array();
		foreach ($_POST['qty'] as $id => $qty)
		{
			//if(!empty($qty)) {
			$products[] = new amz_customStockproduct($tpt_vars, $id, $qty);
			//}
			if(!empty($qty)) {
				$fproducts[] = new amz_customStockproduct($tpt_vars, $id, $qty);
			}
		}
		$fproducts = $fproducts + amz_cart::$pArray['stock'];
		//var_dump($csproducts);die();

		//var_dump($products);die();


		$result = amz_pricing::getStockProductPricing($tpt_vars, $products, $fproducts);


		$res = array('update_elements'=>array());
		//$res = array();
		foreach($_POST['pElm'] as $key=>$value) {
			$res['update_elements']['price_'.$key] = '--';
			$ids = explode(',', $value);
			$sttl = 0;
			foreach($ids as $id) {
				$sttl += $result[$id];
			}
			if(!empty($sttl)) {
				$res['update_elements']['price_'.$key] = format_price($sttl);
			}
		}
		$res['update_elements']['price_total'] = format_price($result['total']);

		//echo json_encode($cur_result);
		/* add cur price to subtotals */

		//array_walk($result, 'format_price_array');
		$iid = tpt_logger::log_pricing($tpt_vars, 'tpt_request_pricing', 'add_to_cart_pricing_custom', 0, $products, $result);

		echo json_encode($res);
	}
	if((strtolower($task) == 'cart.add_product')) {

		amz_cart::$pricing_products = amz_cart::$products;

		//tpt_dump($tpt_vars['modules'], true);
		$products = amz_cart::processCustomProductData2($tpt_vars, $_POST, true);

		//var_dump($products);die();

		foreach($products as $p) {
			amz_cart::add_pricing($tpt_vars, $p);
		}

		//tpt_dump(amz_cart::$pricing_products, true);

		$pricing_totals = amz_cart::getPricingTotals($tpt_vars, !empty($_POST['discount'])?$_POST['discount']:'');

		//var_dump($products);die();
		$pricing = array();
		$pricing_subtotal = 0;
		//$subtotal_calculated = false;
		foreach($products as $key=>$product) {
			$pricing[$key] = array();
			if(!empty($_POST['allpricing']) && ($_POST['allpricing'] == '4131')) {
				$bandSize = $tpt_vars['modules']['handler']->modules['BandSize']->moduleData['id'][$product->data['band_size']]['name'];
				$pricing[$key][$bandSize] = array();
				$pricing[$key][$bandSize]['customer'] = $product->price;
				if(is_a($product, 'amz_customproduct') && !$product->pricingObject->pricingType) {
					$retail_pricing = array('html'=>array(
						'sbase_price'=>$product->pricingObject->price['html']['retail_price_per_discounted'],
						'mbase_price'=>$product->pricingObject->price['html']['retail_price_total_discounted']
					),
						'values'=>array(
							'sbase_price'=>$product->pricingObject->price['values']['retail_price_per_discounted'],
							'mbase_price'=>$product->pricingObject->price['values']['retail_price_total_discounted']
						));
					$pricing[$key][$bandSize]['retail'] = $retail_pricing;
					$lowest_pricing = array('html'=>array(
						'sbase_price'=>$product->pricingObject->price['html']['lowest_price_per_discounted'],
						'mbase_price'=>$product->pricingObject->price['html']['lowest_price_total_discounted']
					),
						'values'=>array(
							'sbase_price'=>$product->pricingObject->price['values']['lowest_price_per_discounted'],
							'mbase_price'=>$product->pricingObject->price['values']['lowest_price_total_discounted']
						));
					$pricing[$key][$bandSize]['lowest'] = $lowest_pricing;
				} else {
					$pricing[$key][$bandSize]['retail'] = $product->price;
					$pricing[$key][$bandSize]['lowest'] = $product->price;
				}
			} else if(!empty($_POST['allpricing']) && ($_POST['allpricing'] == 'multi')) {
				$bandSize = $tpt_vars['modules']['handler']->modules['BandSize']->moduleData['id'][$product->data['band_size']]['name'];
				$pricing[$key][$bandSize] = array();
				$pricing[$key][$bandSize] = $product->price;
				//var_dump($product->price['values']['mbase_price']);
				$pricing_subtotal += $product->price['values']['mbase_price'];
			} else {
				$bandSize = $tpt_vars['modules']['handler']->modules['BandSize']->moduleData['id'][$product->data['size']]['id'];
				$pricing[$bandSize] = $product->price;
				//var_dump($product->price['values']['mbase_price']);
				$pricing_subtotal += $product->price['values']['mbase_price'];
				//if(!$subtotal_calculated)
				//    $pricing_subtotal = $pricing_totals['values']['lowest_price'];
				//$subtotal_calculated = true;
			}
		}
		//var_dump($pricing_totals['values']['customer_price']);
		//var_dump(amz_cart::$totals['pricing']['values']['customer_price']);die();
		$values = array('mbase_price'=>$pricing_subtotal);
		$html = $values;
		array_walk($html, 'format_price_array');
		//$pricing['subtotal'] = array('html'=>array('mbase_price'=>format_price($pricing_totals['values']['customer_price']-amz_cart::$totals['pricing']['values']['customer_price'])));
		$pricing['subtotal'] = array('html'=>$html, 'values'=>$values);

		$bulder_id = 0;
		if(!empty($_POST['short_builder']))
			$bulder_id = intval($_POST['short_builder'], 10);
		$iid = tpt_logger::log_pricing($tpt_vars, 'tpt_request_pricing', 'add_to_cart_pricing_custom', $bulder_id, $products, $pricing);

		//var_dump($pricing);die();
		echo json_encode($pricing);
	}
	else if((strtolower($task) == 'pricing.individual_pricing')) {
		$i = 0;
		$product_pricing = array();

		foreach ($_POST['product'] as $p)
		{
			$style = $p['band_style'];
			$type = $p['band_type'];
			$cur_qty = $p['qty_sm'];

			$tmp_prc_obj = new amz_pricing($tpt_vars, $type, $style, $qty=array('lg'=>$cur_qty), $options=array(), '20'/*$discount*/);
			$tmp_prc_obj->getPrice();

			$product_pricing[$i]['pos'] = $i;
			$product_pricing[$i]['price'] = $tmp_prc_obj->price['html']['lowest_price_total_discounted'];
			$product_pricing[$i]['per_band'] = $tmp_prc_obj->price['html']['lowest_price_per_discounted'];

			$i++;
		}

		echo json_encode($product_pricing);
	}
	/*
	else if(strtolower($task) == 'cart.update') {
		$productindex = intval($_POST['productindex'], 10);
		$qty = max(intval($_POST['qty'], 10), 1);

		if(!isset(amz_cart::$products[$productindex])) {
			$tpt_vars['template_data']['valid_form'] = false;
			$tpt_vars['environment']['ajax_result']['messages'][] = array('ERROR!', 'error');
		} else {

			amz_cart::$products[$productindex]->qty = $qty;
			if(is_a(amz_cart::$products[$productindex], 'amz_customproduct')) {
				amz_cart::$products[$productindex]->pricingObject->qty['lg'] = $qty;
				if((amz_cart::$products[$productindex]->data['band_style'] == 1) || (amz_cart::$products[$productindex]->data['band_style'] == 6)) {
					if($qty<50) {
						amz_cart::$products[$productindex]->data['band_style'] = 6;
					} else {
						amz_cart::$products[$productindex]->data['band_style'] = 1;
					}
				}
				$optionsInput = amz_cart::$products[$productindex]->options;
				$pricingObject = new amz_pricing($tpt_vars, amz_cart::$products[$productindex]->data['band_type'], amz_cart::$products[$productindex]->data['band_style'], array('lg'=>$qty), $options_input, $discount);
				amz_cart::$products[$productindex]->pricingObject = $pricingObject;
			}
		}

		$tpt_vars['environment']['ajax_result']['messages'][] = array('Product quantity updated.', 'message');

		$return_url = $tpt_vars['environment']['go_back_url'];
		tpt_request::redirect($tpt_vars, $return_url);
	} else if(strtolower($task) == 'cart.delete') {
		$productindex = intval($_POST['productindex'], 10);

		if(!isset(amz_cart::$products[$productindex])) {
			$tpt_vars['template_data']['valid_form'] = false;
			//$tpt_vars['environment']['ajax_result']['messages'][] = array('Please choose band color!', 'error');
		} else {
			unset(amz_cart::$products[$productindex]);
			amz_cart::$products = array_values(amz_cart::$products);
			//var_dump(amz_cart::$products);die();

			amz_cart::getTotals($tpt_vars);
		}

		$tpt_vars['environment']['ajax_result']['messages'][] = array('Product removed from cart.', 'message');

		$return_url = $tpt_vars['environment']['go_back_url'];
		tpt_request::redirect($tpt_vars, $return_url);
	}
	*/
	/*
	else if((strtolower($task) == 'user.logout2') && !$tpt_vars['user']['isLogged']) {
		$return_url = $tpt_vars['url']['handler']->wrap($tpt_vars, '/login-register');
		tpt_request::redirect($tpt_vars, $return_url);
	} else if(((strtolower($task) == 'user.add_address') || (strtolower($task) == 'user.edit_address') || (strtolower($task) == 'user.edit_shipping_address') || (strtolower($task) == 'user.edit_payment_address') || (strtolower($task) == 'user.delete_address') || (strtolower($task) == 'user.default_address') || (strtolower($task) == 'user.edit_account_info')) && $tpt_vars['template_data']['valid_form']) {
		if(!empty($_GET['tobasket'])) {
			$return_url = $tpt_vars['url']['handler']->wrap($tpt_vars, '/address-shipping');
		} else if(!empty($_GET['topayment'])){
			$return_url = $tpt_vars['url']['handler']->wrap($tpt_vars, '/make-payment');
		} else {
			$return_url = $tpt_vars['url']['handler']->wrap($tpt_vars, '/my-addresses');
		}
		tpt_request::redirect($tpt_vars, $return_url);
	} else if((strtolower($task) == 'user.edit_account_info') && $tpt_vars['template_data']['valid_form']) {
		if(!empty($_GET['tobasket'])) {
			$return_url = $tpt_vars['url']['handler']->wrap($tpt_vars, '/address-shipping');
		} else if(!empty($_GET['topayment'])){
			$return_url = $tpt_vars['url']['handler']->wrap($tpt_vars, '/make-payment');
		} else {
			$return_url = $tpt_vars['url']['handler']->wrap($tpt_vars, '/my-account-info');
		}
		tpt_request::redirect($tpt_vars, $return_url);
	} else if(strtolower($task) == 'user.edit_password2') {
		if(!empty($_GET['tobasket'])) {
			$return_url = $tpt_vars['url']['handler']->wrap($tpt_vars, '/address-shipping');
		} else if(!empty($_GET['topayment'])){
			$return_url = $tpt_vars['url']['handler']->wrap($tpt_vars, '/make-payment');
		} else {
			$return_url = $tpt_vars['url']['handler']->wrap($tpt_vars, '/login-register');
		}
		tpt_request::redirect($tpt_vars, $return_url);
	} else if((strtolower($task) == 'user.same_address')) {
		$return_url = $tpt_vars['url']['handler']->wrap($tpt_vars, '/address-shipping');
		tpt_request::redirect($tpt_vars, $return_url);
	} else if((strtolower($task) == 'user.select_shipping_address') || (strtolower($task) == 'user.select_payment_address')) {
		if(!empty($_GET['tocart'])) {
			$return_url = $tpt_vars['url']['handler']->wrap($tpt_vars, '/address-shipping');
			tpt_request::redirect($tpt_vars, $return_url);
		}
	} else if(strtolower($task) == 'user.open_address') {
		$tpt_vars['template_data']['valid_form'] = false;
		$address_entr = false;
		$address_name = mysql_real_escape_string(base64_decode($_POST['address_name']));
		foreach($tpt_vars['user']['addresses'] as $address) {
			if($address_name == $address['address_name']) {
				$address_entr = $address;
				$tpt_vars['template_data']['valid_form'] = true;
				break;
			}
		}

		if(!$tpt_vars['template_data']['valid_form']) {
			$return_url = $tpt_vars['url']['handler']->wrap($tpt_vars, '/my-addresses');
			tpt_request::redirect($tpt_vars, $return_url);
		}
	} else if(((strtolower($task) == 'user.login') || (strtolower($task) == 'user.edit_password')) && $tpt_vars['user']['isLogged']) {
		//die('aasdasd');
		$return_url = $tpt_vars['environment']['login_return_url'];
		//var_dump($return_url);die();
		tpt_request::redirect($tpt_vars, $return_url);
	}
	*/
}

/*
if(in_array('cartclear', $tpt_vars['url']['bpath'])) {
    amz_cart::$products = array();
    
    $tpt_vars['environment']['ajax_result']['messages'][] = array('Your cart has been cleared.', 'message');
    
    $return_url = $tpt_vars['environment']['go_back_url'];
    tpt_request::redirect($tpt_vars, $return_url);
}
*/
        
        
