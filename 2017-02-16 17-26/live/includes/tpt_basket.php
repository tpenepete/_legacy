<?php
//templay loader check
defined('TPT_INIT') or die('access denied');

//all static class
class amz_cart {
	// class-wise data vars
	static $pricing_products = array();
	static $products = array();
	static $pArray = array('stock'=>array(), 'custom'=>array(), 'bundle'=>array());
	static $bundlesData;
	static $stockProductsTypesData;
	static $stockProductsData;

	static $customStockProductsData;
	static $customStockProductsDataSku = array();

	static $totals = array('pricing'=>array('html'=>array(), 'values'=>array()), 'products_count'=>0);


	function __construct() {
		return false;
	}

	static function init(&$vars) {
		/*****************
		$query = 'SELECT * FROM `tpt_stock_products`';
		$vars['db']['handler']->query($query, __FILE__);
		self::$customStockProductsData = $vars['db']['handler']->getData($vars, 'tpt_stock_products', '*', '', 'id', false);


		$query = 'SELECT * FROM `tpt_stock_products_types`';
		$vars['db']['handler']->query($query, __FILE__);
		self::$stockProductsTypesData = $vars['data']['tpt_stock_products_types']['id'] = $vars['db']['handler']->fetch_assoc_list('id', false);

		$query = 'SELECT * FROM `tpt_products_bundles`';
		$vars['db']['handler']->query($query, __FILE__);
		self::$bundlesData = $vars['data']['tpt_products_bundles']['id'] = $vars['db']['handler']->fetch_assoc_list('id', false);
		*/

		$store_products = array();
		if(!empty($_SESSION['templay']['basket']) && ($store_products = unserialize($_SESSION['templay']['basket']))) {
				//var_dump($store_products);die();
				foreach($store_products as $mode=>$products) {
					//var_dump($id);
					//var_dump($product);
					//die();
					foreach($products as $id=>$product) {
						switch($mode) {
							/*****************
								case 'stock' :
									$newp = new $product['productclass']($vars, $id, $product['qty']);
									self::$products[] = $newp;
									self::$pArray['stock'][] = $newp;
									break;
								case 'bundle' :
									$newp = new $product['productclass']($vars, $id, $product['pids'], $product['qty']);
									self::$products[] = $newp;
									self::$pArray['bundle'][] = $newp;
									break;
							*/
								case 'custom' :
									self::$products[] = $product;
									self::$pArray['custom'][] = $product;
									break;
						}
						tpt_logger::dump($vars, $product, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), '$product', __FILE__.' '.__LINE__);
						//tpt_logger::dump($vars, $options_costs, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), '$options_costs', __FILE__.' '.__LINE__);//die();
					}
				}
		} else {
				self::$products = array();
		}

		//var_dump(self::$products);die();



		//if(!is_array(amz_pricing::$tcosts)) {
		//		amz_pricing::$tcosts = $vars['db']['handler']->getData($vars, 'transit_costs');
		//}
		self::getTotals($vars);
	}

	static function processCustomProductPOSTData(&$vars, $allowdiscount=false) {
		$types_module = getModule($vars, 'BandType');
		$sizes_module = getModule($vars, 'BandSize');
		$data_module = getModule($vars, 'BandData');
		$rushorder_module = getModule($vars, 'RushOrder');


		$products = array();

		$iterations = array();
		if(!empty($_POST['product']) && is_array($_POST['product'])) {
				foreach($_POST['product'] as $key=>$p_pr) {
					$iterations[$key] = $_POST['product'][$key];
				}
		} else {
				$iterations = array($_POST);
		}

		//var_dump($_POST['product']);die();
		//var_dump($iterations);die();

		foreach($iterations as $key=>$input) {
				@$type = array_filter(array($input['bandType'], $input['BandType'], $input['type'], $input['band_type'], $input['product_type']));
				$type = intval(reset($type), 10);
				@$style = array_filter(array($input['bandStyle'], $input['BandStyle'], $input['style'], $input['band_style'], $input['message_style']));
				$style = intval(reset($style), 10);
				@$color = array_filter(array($input['bandColor'], $input['BandColor'], $input['color'], $input['band_color']));
				$color = reset($color);
				@$ink_color = array_filter(array($input['inkColor'], $input['InkColor'], $input['inkcolor'], $input['ink_color']));
				$ink_color = reset($ink_color);
				@$message_color = array_filter(array($input['messageColor'], $input['MessageColor'], $input['messagecolor'], $input['message_color'], $input['msg_color']));
				$message_color = reset($message_color);
				if ($style == '1' || $style == '3' || $style == '6' || $style == '8') $message_color = ''; //If debossed or embossed then nullify message_color property
				@$font = array_filter(array($input['bandFont'], $input['BandFont'], $input['font'], $input['band_font'], $input['text_font']));
				$font = reset($font);
				@$size = array_filter(array($input['bandSize'], $input['BandSize'], $input['size'], $input['band_size']));
				$size = intval(reset($size), 10);
				@$fmsg = array_filter(array($input['tpt_pg_FrontMessage'], $input['bandFmessage'], $input['BandFmessage'], $input['fmessage'], $input['band_fmessage'], $input['front_message'], $input['front_msg'], $input['message1'], $input['message1_0']));
				$fmsg = reset($fmsg);
				$fmsg = stripslashes($fmsg);
				@$fmsg2 = array_filter(array($input['tpt_pg_FrontMessage2'], $input['bandFmessage2'], $input['BandFmessage2'], $input['fmessage2'], $input['band_fmessage2'], $input['front_message2'], $input['front_msg2'], $input['message1_1']));
				$fmsg2 = reset($fmsg2);
				$fmsg2 = stripslashes($fmsg2);
				@$bmsg = array_filter(array($input['tpt_pg_BackMessage'], $input['bandBmessage'], $input['BandBmessage'], $input['bmessage'], $input['band_bmessage'], $input['back_message'], $input['back_msg'], $input['message2'], $input['message2_0']));
				$bmsg = reset($bmsg);
				$bmsg = stripslashes($bmsg);
				@$bmsg2 = array_filter(array($input['tpt_pg_BackMessage2'], $input['bandBmessage2'], $input['BandBmessage2'], $input['bmessage2'], $input['band_bmessage2'], $input['back_message2'], $input['back_msg2'], $input['message2_1']));
				$bmsg2 = reset($bmsg2);
				$bmsg2 = stripslashes($bmsg2);

				@$front_clipart_left = array_filter(array($input['bandLClipart'], $input['BandLClipart'], $input['lclipart'], $input['band_lclipart'], $input['left_clipart'], $input['clipart_left'], $input['clipart1_1_l'], $input['art_front_left'], $input['bandFLClipart'], $input['BandFLClipart'], $input['flclipart'], $input['band_flclipart'], $input['front_left_clipart'], $input['front_clipart_left'], $input['tpt_pg_flclipart']));
				$front_clipart_left = reset($front_clipart_left);
			@$front_clipart_left_c = array_filter(array($input['flclipart_c']));
			$front_clipart_left_c = reset($front_clipart_left_c);
				@$front_clipart_right = array_filter(array($input['bandRClipart'], $input['BandRClipart'], $input['rclipart'], $input['band_rclipart'], $input['right_clipart'], $input['clipart_right'], $input['clipart1_1_r'], $input['art_front_right'], $input['bandFRClipart'], $input['BandFRClipart'], $input['frclipart'], $input['band_frclipart'], $input['front_right_clipart'], $input['front_clipart_right'], $input['tpt_pg_frclipart']));
				$front_clipart_right = reset($front_clipart_right);
			@$front_clipart_right_c = array_filter(array($input['frclipart_c']));
			$front_clipart_right_c = reset($front_clipart_right_c);
				@$front_clipart_left2 = array_filter(array($input['bandLClipart2'], $input['BandLClipart2'], $input['lclipart2'], $input['band_lclipart2'], $input['left_clipart2'], $input['clipart_left2'], $input['clipart1_2_l'], $input['art_front_left2'], $input['bandFLClipart2'], $input['BandFLClipart2'], $input['flclipart2'], $input['band_flclipart2'], $input['front_left_clipart2'], $input['front_clipart_left2'], $input['tpt_pg_flclipart2']));
				$front_clipart_left2 = reset($front_clipart_left2);
			@$front_clipart_left2_c = array_filter(array($input['flclipart2_c'], $input['fl2clipart_c']));
			$front_clipart_left2_c = reset($front_clipart_left2_c);
				@$front_clipart_right2 = array_filter(array($input['bandRClipart2'], $input['BandRClipart2'], $input['rclipart2'], $input['band_rclipart2'], $input['right_clipart2'], $input['clipart_right2'], $input['clipart1_2_r'], $input['art_front_right2'], $input['bandFRClipart2'], $input['BandFRClipart2'], $input['frclipart2'], $input['band_frclipart2'], $input['front_right_clipart2'], $input['front_clipart_right2'], $input['tpt_pg_frclipart2']));
				$front_clipart_right2 = reset($front_clipart_right2);
			@$front_clipart_right2_c = array_filter(array($input['frclipart2_c'], $input['fr2clipart_c']));
			$front_clipart_right2_c = reset($front_clipart_right2_c);
				@$back_clipart_left = array_filter(array($input['bandBLClipart'], $input['BandBLClipart'], $input['blclipart'], $input['band_blclipart'], $input['back_left_clipart'], $input['back_clipart_left'], $input['clipart2_1_l'], $input['art_back_left'], $input['tpt_pg_blclipart']));
				$back_clipart_left = reset($back_clipart_left);
			@$back_clipart_left_c = array_filter(array($input['blclipart_c']));
			$back_clipart_left_c = reset($back_clipart_left_c);
				@$back_clipart_right = array_filter(array($input['bandBRClipart'], $input['BandBRClipart'], $input['brclipart'], $input['band_brclipart'], $input['back_right_clipart'], $input['back_clipart_right'], $input['clipart2_1_r'], $input['art_back_right'], $input['tpt_pg_brclipart']));
				$back_clipart_right = reset($back_clipart_right);
			@$back_clipart_right_c = array_filter(array($input['brclipart_c']));
			$back_clipart_right_c = reset($back_clipart_right_c);
				@$back_clipart_left2 = array_filter(array($input['bandBLClipart2'], $input['BandBLClipart2'], $input['blclipart2'], $input['band_blclipart2'], $input['back_left_clipart2'], $input['back_clipart_left2'], $input['clipart2_2_l'], $input['art_back_left2'], $input['tpt_pg_blclipart2']));
				$back_clipart_left2 = reset($back_clipart_left2);
			@$back_clipart_left2_c = array_filter(array($input['blclipart2_c'], $input['bl2clipart_c']));
			$back_clipart_left2_c = reset($back_clipart_left2_c);
				@$back_clipart_right2 = array_filter(array($input['bandBRClipart2'], $input['BandBRClipart2'], $input['brclipart2'], $input['band_brclipart2'], $input['back_right_clipart2'], $input['back_clipart_right2'], $input['clipart2_2_r'], $input['art_back_right2'], $input['tpt_pg_brclipart2']));
				$back_clipart_right2 = reset($back_clipart_right2);
			@$back_clipart_right2_c = array_filter(array($input['brclipart2_c'], $input['br2clipart_c']));
			$back_clipart_right2_c = reset($back_clipart_right2_c);

				@$custom_clipart = array_filter(array($input['custom_clipart'], $input['customclipart'], $input['customClipart'], $input['custom-clipart']));
				$custom_clipart = reset($custom_clipart);
				
				
				@$message_span = array_filter(array($input['message_span']));
				$message_span = reset($message_span);
				if($message_span != 1)
					$message_span = 0;
				
				
				$bdata = $data_module->typeStyle[$type][$style];
				if(empty($message_span) && ($type != 8) && ($type != 34)) {
					if(empty($bmsg) && empty($bmsg2)) {
						$bmsg = ' ';
					}
				}

				if(($message_span == 1)) {
					$bmsg = '';
				}
				if(!empty($bdata['blank'])) {
					$fmsg = '';
					$fmsg2 = '';
					$bmsg = '';
					$bmsg2 = '';

					$front_clipart_left = '';
					$front_clipart_right = '';
					$front_clipart_left2 = '';
					$front_clipart_right2 = '';
					$back_clipart_left = '';
					$back_clipart_right = '';
					$back_clipart_left2 = '';
					$back_clipart_right2 = '';

					$front_clipart_left_c = '';
					$front_clipart_right_c = '';
					$front_clipart_left2_c = '';
					$front_clipart_right2_c = '';
					$back_clipart_left_c = '';
					$back_clipart_right_c = '';
					$back_clipart_left2_c = '';
					$back_clipart_right2_c = '';

					$custom_clipart = '';

					$font = '';
					$message_color = '';
					$ink_color = '';
				}

				//tpt_dump($message_span, true);


				//var_dump($input['rush_order']);die();
				$rush_order = 0;
				if(!empty($input['rush_order'])) {
					if(!$rushorder_module->moduleData['id'][$input['rush_order']]['dummy']) {
						$rush_order = $input['rush_order'];
					}
				}
				$added_by = 0;
				if(!empty($input['short_builder'])) {
					$added_by = $input['short_builder'];
				}
				$reorder = 0;
				if(!empty($input['reorder']))
					$reorder = intval($input['reorder'], 10);

				$reorder_source = '';
				if(!empty($input['reorder_source']))
					$reorder_source = intval($input['reorder_source'], 10);


				@$qty_xs = array_filter(array($input['bandQty_xs'], $input['BandQty_xs'], $input['qty_xs'], $input['band_qty_xs'], $input['quantity_xs'], $input['quantity_extra_small']));
				$qty_xs = intval(reset($qty_xs), 10);
				@$qty_sm = array_filter(array($input['bandQty_sm'], $input['BandQty_sm'], $input['qty_sm'], $input['band_qty_sm'], $input['quantity_sm'], $input['quantity_small'], $input['bandQty_sm']));
				$qty_sm = intval(reset($qty_sm), 10);
				@$qty_m = array_filter(array($input['bandQty_m'], $input['BandQty_m'], $input['qty_m'], $input['band_qty_m'], $input['quantity_m'], $input['quantity_medium']));
				$qty_m = intval(reset($qty_m), 10);
				@$qty_lg = array_filter(array($input['bandQty_lg'], $input['BandQty_lg'], $input['qty_lg'], $input['band_qty_lg'], $input['quantity_lg'], $input['quantity_large'], $input['bandQty'], $input['BandQty'], $input['qty'], $input['band_qty'], $input['quantity']));
				$qty_lg = intval(reset($qty_lg), 10);
				@$qty_xl = array_filter(array($input['bandQty_xl'], $input['BandQty_xl'], $input['qty_xl'], $input['band_qty_xl'], $input['quantity_xl'], $input['quantity_extra_large']));
				$qty_xl = intval(reset($qty_xl), 10);

				@$qty_smr = array_filter(array($input['BandQty_smr'], $input['qty_smr'], $input['band_qty_smr'], $input['quantity_smr'], $input['quantity_small_ring']));
				$qty_smr = intval(reset($qty_smr), 10);
				@$qty_mr = array_filter(array($input['bandQty_mr'], $input['BandQty_mr'], $input['qty_mr'], $input['band_qty_mr'], $input['quantity_mr'], $input['quantity_medium_ring']));
				$qty_mr = intval(reset($qty_mr), 10);
				@$qty_lgr = array_filter(array($input['bandQty_lgr'], $input['BandQty_lgr'], $input['qty_lgr'], $input['band_qty_lgr'], $input['quantity_lgr'], $input['quantity_large_ring']));
				$qty_lgr = intval(reset($qty_lgr), 10);

				@$qty_un = array_filter(array($input['bandQty_un'], $input['BandQty_un'], $input['qty_un'], $input['band_qty_un'], $input['quantity_un'], $input['quantity_universal']));
				$qty_un = intval(reset($qty_un), 10);

				$size = array(
					'xs'=>$qty_xs,
					'sm'=>$qty_sm,
					'm'=>$qty_m,
					'lg'=>$qty_lg,
					'xl'=>$qty_xl,
					'smr'=>$qty_smr,
					'mr'=>$qty_mr,
					'lgr'=>$qty_lgr,
					'un'=>$qty_un,
				);

				//tpt_dump($size, true);


				foreach($size as $k=>$s) {
					$st = $style;
					if(!empty($s)) {
						$sz = $sizes_module->moduleData['name'][$k]['id'];

						if(($s<50) && ($style == 1)) {
								$st = 6;
						}
						if(($s<50) && ($type == 9)) {
								$st = 6;
						}
						if(($s<50) && ($type == 11)) {
								$st = 6;
						}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// process product options parameters
$pparams = $input;
$pparams['type'] = $type;
$pparams['style'] = $style;
$pparams['color'] = $color;
$pparams['message_color'] = $message_color;
$pparams['message_span'] = $message_span;
$pparams['fmsg'] = $fmsg;
$pparams['fmsg2'] = $fmsg2;
$pparams['bmsg'] = $bmsg;
$pparams['bmsg2'] = $bmsg2;

$pparams['front_clipart_left'] = $front_clipart_left;
$pparams['front_clipart_right'] = $front_clipart_right;
$pparams['front_clipart_left2'] = $front_clipart_left2;
$pparams['front_clipart_right2'] = $front_clipart_right2;
$pparams['back_clipart_left'] = $back_clipart_left;
$pparams['back_clipart_right'] = $back_clipart_right;
$pparams['back_clipart_left2'] = $back_clipart_left2;
$pparams['back_clipart_right2'] = $back_clipart_right2;

$pparams['front_clipart_left_c'] = $front_clipart_left_c;
$pparams['front_clipart_right_c'] = $front_clipart_right_c;
$pparams['front_clipart_left2_c'] = $front_clipart_left2_c;
$pparams['front_clipart_right2_c'] = $front_clipart_right2_c;
$pparams['back_clipart_left_c'] = $back_clipart_left_c;
$pparams['back_clipart_right_c'] = $back_clipart_right_c;
$pparams['back_clipart_left2_c'] = $back_clipart_left2_c;
$pparams['back_clipart_right2_c'] = $back_clipart_right2_c;

$pparams['rush_order'] = $rush_order;
$pparams['added_by'] = $added_by;
//tpt_dump($pparams);
$price_modifiers = self::processPriceModifiers($vars, $pparams);


////////////////////////////////////////////////////////////////////////////////////////////////////// END OPTION PRICE MODIFIERS

						/*
						if($_SERVER['REMOTE_ADDR'] == '109.160.0.218') {
								//var_dump($price_modifiers);die();
								var_dump($type);//die();
								var_dump($st);//die();
								var_dump($s);//die();
								//var_dump($discount);//die();
								var_dump($price_modifiers);die();
						}
						*/
						//$discount = $_POST['discount'];
						$discount = (!empty($_POST['discount'])&&(is_numeric($_POST['discount'])&&$allowdiscount)?floatval($_POST['discount']):GLOBAL_CUSTOMPRODUCT_PRICEOFF_PERCENT);
						//tpt_dump($price_modifiers, true, '', '', true);
						$pricingObject = new amz_pricing($vars, $type, $st, array('lg'=>$s), $price_modifiers, $discount);


						$comments = '';
						if(!empty($input['comments'])) {
							$comments = strip_tags($input['comments']);
						}
						/*
						else if(!empty($input['user_design_notes'])) {
							$comments = strip_tags($input['user_design_notes']);
						}
						*/
								
						$invert_dual = 0;
						if(!empty($input['invert_dual']) || !empty($input['invert_message']))
								$invert_dual = 1;

						$cut_away = 0;
						if(!empty($input['cut_away']) || !empty($input['invert_message']))
								$cut_away = 1;



						$data = array(
								'quote_id'=>!empty($input['quote_id'])?$input['quote_id']:null,
								'product_id'=>!empty($input['product_id'])?$input['product_id']:null,
								'messages'=>array('front'=>array_filter(array(stripslashes($fmsg), stripslashes($fmsg2))), 'back'=>array_filter(array(stripslashes($bmsg), stripslashes($bmsg2)))),
								'clipart'=>array(
													'front'=>array(
																array('left'=>stripslashes($front_clipart_left),
																		'right'=>stripslashes($front_clipart_right)
																		),
																array('left'=>stripslashes($front_clipart_left2),
																		'right'=>stripslashes($front_clipart_right2)
																		)
																),
													'back'=>array(
																array('left'=>stripslashes($back_clipart_left),
																		'right'=>stripslashes($back_clipart_right)
																		),
																array('left'=>stripslashes($back_clipart_left2),
																		'right'=>stripslashes($back_clipart_right2)
																		)
																),
													),
							'clipart_c'=>array(
								'front'=>array(
									array('left'=>stripslashes($front_clipart_left_c),
										'right'=>stripslashes($front_clipart_right_c)
									),
									array('left'=>stripslashes($front_clipart_left2_c),
										'right'=>stripslashes($front_clipart_right2_c)
									)
								),
								'back'=>array(
									array('left'=>stripslashes($back_clipart_left_c),
										'right'=>stripslashes($back_clipart_right_c)
									),
									array('left'=>stripslashes($back_clipart_left2_c),
										'right'=>stripslashes($back_clipart_right2_c)
									)
								),
							),
// 							'custom_clipart'=>$custom_clipart,
								'custom_clipart'=>(is_null(@json_decode($custom_clipart))?$custom_clipart:json_decode($custom_clipart)),
								'band_type'=>$type,
								'band_style'=>$st,
								'invert_dual'=>$invert_dual,
								'cut_away'=>$cut_away,
								'band_size'=>$sz,
								'band_color'=>$color,
								'ink_color'=>$ink_color,
								'message_color'=>$message_color,
								'message_span'=>$message_span,
								'band_font'=>$font,
								'text_color'=>!empty($input['TextColor'])?$input['TextColor']:'',
								'comments'=>$comments,
								'rush_order'=>$rush_order,
								'added_by'=>$added_by,
								'reorder'=>$reorder,
								'reorder_source'=>$reorder_source
						);
						//var_dump($data);die();
						if(!empty($input['primg'])) {
								$data['primg'] = $input['primg'];
						}


						$p = new amz_customproduct($vars, $pricingObject, $data);
						if(!empty($_POST['product']) && is_array($_POST['product'])) {
								$products[$key] = $p;
						} else {
								$products[] = $p;
						}

					}
				}

		}


		return $products;

	}











	static function processCustomProductPOSTData2(&$vars, $allowdiscount=false) {
		$types_module = $vars['modules']['handler']->modules['BandType'];
		$data_module = $vars['modules']['handler']->modules['BandData'];
		$cpf_module = $vars['modules']['handler']->modules['CustomProductField'];
		$rushorder_module = $vars['modules']['handler']->modules['RushOrder'];
		//$data_array_fields = $vars['db']['handler']->getData($vars, $cpf_module->moduleTable, '*', ' `data_array`=1', 'id', false);

		$products = array();

		$iterations = array();
		if(is_array($_POST['product'])) {
				foreach($_POST['product'] as $key=>$p_pr) {
					$iterations[$key] = $_POST['product'][$key];
				}
		} else {
				$iterations = array($_POST);
		}


		//var_dump($_POST['product']);die();
		//var_dump($iterations);die();

		foreach($iterations as $key=>$input) {
				$data = array();
				$cpf_module->processInputData($vars, $data, $input);

				//tpt_logger::dump($vars, $data, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), '$data', __FILE__.' '.__LINE__.' '.__FUNCTION__);
				//tpt_dump($input, true);
				//tpt_dump($data, true);


				$discount = ((is_numeric($_POST['discount'])&&$allowdiscount)?floatval($_POST['discount']):GLOBAL_CUSTOMPRODUCT_PRICEOFF_PERCENT);
				$sizes = array_filter($data['sizes']);
				foreach($sizes as $sname=>$qty) {
					$pricingObject = new amz_pricing($vars, $data['band_type'], $data['band_style'], array('lg'=>$qty), $data['pricing_modifiers'], $discount);

					$p = new amz_customproduct($vars, $pricingObject, $data);
					if(is_array($_POST['product'])) {
						$products[$key] = $p;
					} else {
						$products[] = $p;
					}
				}

		}


		return $products;

	}



	static function processCustomProductData2(&$vars, $input, $allowdiscount=false) {
		$types_module = $vars['modules']['handler']->modules['BandType'];
		$data_module = $vars['modules']['handler']->modules['BandData'];
		$cpf_module = $vars['modules']['handler']->modules['CustomProductField'];
		$rushorder_module = $vars['modules']['handler']->modules['RushOrder'];
		//$data_array_fields = $vars['db']['handler']->getData($vars, $cpf_module->moduleTable, '*', ' `data_array`=1', 'id', false);

		$products = array();

		$input['app'] = (!empty($input['short_builder'])?intval($input['short_builder'], 10):0);
		unset($input['short_builder']);
		unset($input['task']);

		$iterations = array();
		if(!empty($input['product']) && is_array($input['product'])) {
			foreach($input['product'] as $key=>$p_pr) {
				$iterations[$key] = $input['product'][$key];
			}
		} else {
			$iterations = array($input);
		}


		//var_dump($_POST['product']);die();
		//var_dump($iterations);die();

		foreach($iterations as $key=>$data) {
			/*
			$cpf_module->processInputData($vars, $data, $input);

			//tpt_logger::dump($vars, $data, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), '$data', __FILE__.' '.__LINE__.' '.__FUNCTION__);
			//tpt_dump($input, true);
			//tpt_dump($data, true);

			*/
			$discount = (!empty($_POST['discount'])&&(is_numeric($_POST['discount'])&&$allowdiscount)?floatval($_POST['discount']):GLOBAL_CUSTOMPRODUCT_PRICEOFF_PERCENT);

			$pparams = array_intersect_key($data, $cpf_module->moduleData['pname']);
			$pparams = $input;
			$pparams['type'] = (!empty($data['type'])?$data['type']:0);
			$pparams['style'] = (!empty($data['style'])?$data['style']:0);
			$pparams['color'] = (!empty($data['color'])?$data['color']:'');
			$pparams['message_color'] = (!empty($data['message_color'])?$data['message_color']:'');
			$pparams['message_span'] = (!empty($data['band_layout']) && in_array($data['band_layout'], array(1, 3))?1:0);
			$pparams['fmsg'] = (!empty($data['msg1'])?$data['msg1']:'');
			$pparams['fmsg2'] = (!empty($data['msg3'])?$data['msg3']:'');
			$pparams['bmsg'] = (!empty($data['msg2'])?$data['msg2']:'');
			$pparams['bmsg2'] = (!empty($data['msg4'])?$data['msg4']:'');

			$pparams['front_clipart_left'] = (!empty($data['clp1'])?$data['clp1']:0);
			$pparams['front_clipart_right'] = (!empty($data['clp3'])?$data['clp3']:0);
			$pparams['front_clipart_left2'] = (!empty($data['clp5'])?$data['clp5']:0);
			$pparams['front_clipart_right2'] = (!empty($data['clp7'])?$data['clp7']:0);
			$pparams['back_clipart_left'] = (!empty($data['clp2'])?$data['clp2']:0);
			$pparams['back_clipart_right'] = (!empty($data['clp4'])?$data['clp4']:0);
			$pparams['back_clipart_left2'] = (!empty($data['clp6'])?$data['clp6']:0);
			$pparams['back_clipart_right2'] = (!empty($data['clp8'])?$data['clp7']:0);

			$pparams['front_clipart_left_c'] = (!empty($data['cclp1'])?$data['cclp1']:'');
			$pparams['front_clipart_right_c'] = (!empty($data['cclp3'])?$data['cclp3']:'');
			$pparams['front_clipart_left2_c'] = (!empty($data['cclp5'])?$data['cclp5']:'');
			$pparams['front_clipart_right2_c'] = (!empty($data['cclp7'])?$data['cclp7']:'');
			$pparams['back_clipart_left_c'] = (!empty($data['cclp2'])?$data['cclp2']:'');
			$pparams['back_clipart_right_c'] = (!empty($data['cclp4'])?$data['cclp4']:'');
			$pparams['back_clipart_left2_c'] = (!empty($data['cclp6'])?$data['cclp6']:'');
			$pparams['back_clipart_right2_c'] = (!empty($data['cclp8'])?$data['cclp8']:'');

			$pparams['rush_order'] = (!empty($data['rush_order'])?$data['rush_order']:0);
			$pparams['added_by'] = (!empty($data['app'])?$data['app']:0);

			/*
$pparams['glow'] = $glow;
$pparams['glitter'] = $glitter;
$pparams['uv'] = $uv;
$pparams['indvl_packaging'] = $indvl_packaging;
$pparams['key_chain'] = $key_chain;
$pparams['invert_message'] = $invert_message;
			*/

//tpt_dump($input);
//tpt_dump($pparams);
			$price_modifiers = self::processPriceModifiers($vars, $pparams);

			$sizes = array_filter($data['qty']);
			$d = $data;
			foreach($sizes as $sid=>$qty) {
				$pricingObject = new amz_pricing($vars, $data['type'], $data['style'], array('lg'=>$qty), $price_modifiers, $discount);
				//$pricingObject = array();

				$d['qty'] = $qty;
				$d['size'] = $sid;
				ksort($d);

				$p = new amz_product2($vars, $pricingObject, $d);
				if(!empty($input['product']) && is_array($input['product'])) {
					$products[$key] = $p;
				} else {
					$products[] = $p;
				}
			}

		}


		return $products;

	}












	static function processCustomProductData(&$vars, $iterations, $allowdiscount=false) {
		$types_module = getModule($vars, 'BandType');
		$styles_module = getModule($vars, 'BandStyle');
		$styles = $styles_module->moduleData['id'];
		$size_module = getModule($vars, 'BandSize');
		$sizes = $size_module->moduleData['id'];
		$data_module = getModule($vars, 'BandData');

		$products = array();

		/*
		$iterations = array();
		if(is_array($_POST['product'])) {
				foreach($_POST['product'] as $key=>$p_pr) {
					$iterations[$key] = $_POST['product'][$key];
				}
		} else {
				$iterations = array($_POST);
		}
		*/

		//var_dump($_POST['product']);die();
		//tpt_dump($iterations, true);

		foreach($iterations as $key=>$input) {
				$type = array_filter(array(!empty($input['bandType'])?$input['bandType']:'', !empty($input['BandType'])?$input['BandType']:'', !empty($input['type'])?$input['type']:'', !empty($input['band_type'])?$input['band_type']:'', !empty($input['product_type'])?$input['product_type']:''));
				$type = intval(reset($type), 10);
				$style = array_filter(array(!empty($input['bandStyle'])?$input['bandStyle']:'', !empty($input['BandStyle'])?$input['BandStyle']:'', !empty($input['style'])?$input['style']:'', !empty($input['band_style'])?$input['band_style']:'', !empty($input['message_style'])?$input['message_style']:''));
				$style = intval(reset($style), 10);
				$class = array_filter(array(!empty($input['class'])?$input['class']:0));
				$class = intval(reset($class), 10);
				$color = array_filter(array(!empty($input['color_nnew'])?$input['color_nnew']:'', !empty($input['bandColor'])?$input['bandColor']:'', !empty($input['BandColor'])?$input['BandColor']:'', !empty($input['color'])?$input['color']:'', !empty($input['band_color'])?$input['band_color']:''));
				$color = reset($color);
				$ink_color = array_filter(array(!empty($input['inkColor'])?$input['inkColor']:'', !empty($input['InkColor'])?$input['InkColor']:'', !empty($input['inkcolor'])?$input['inkcolor']:'', !empty($input['ink_color'])?$input['ink_color']:''));
				$ink_color = reset($ink_color);
				$message_color = array_filter(array(!empty($input['messageColor'])?$input['messageColor']:'', !empty($input['MessageColor'])?$input['MessageColor']:'', !empty($input['messagecolor'])?$input['messagecolor']:'', !empty($input['message_color'])?$input['message_color']:'', !empty($input['msg_color'])?$input['msg_color']:''));
				$message_color = reset($message_color);
				if ($style == '1' || $style == '3' || $style == '6' || $style == '8') $message_color = ''; //If debossed or embossed then nullify message_color property
				$font = array_filter(array(!empty($input['bandFont'])?$input['bandFont']:'', !empty($input['BandFont'])?$input['BandFont']:'', !empty($input['font'])?$input['font']:'', !empty($input['band_font'])?$input['band_font']:'', !empty($input['text_font'])?$input['text_font']:''));
				$font = reset($font);
				$size = array_filter(array(!empty($input['size_id'])?$input['size_id']:0, !empty($input['bandSize'])?$input['bandSize']:0, !empty($input['BandSize'])?$input['BandSize']:0, !empty($input['size'])?$input['size']:0, !empty($input['band_size'])?$input['band_size']:0));
				$size = intval(reset($size), 10);
				$qty = array_filter(array(!empty($input['qty'])?$input['qty']:0, !empty($input['quantity'])?$input['quantity']:0));
				$qty = intval(reset($qty), 10);
				//tpt_dump($size);
				//tpt_dump($input);
				$fmsg = array_filter(array(!empty($input['msg1'])?$input['msg1']:'', !empty($input['tpt_pg_FrontMessage'])?$input['tpt_pg_FrontMessage']:'', !empty($input['bandFmessage'])?$input['bandFmessage']:'', !empty($input['BandFmessage'])?$input['BandFmessage']:'', !empty($input['fmessage'])?$input['fmessage']:'', !empty($input['band_fmessage'])?$input['band_fmessage']:'', !empty($input['front_message'])?$input['front_message']:'', !empty($input['front_msg'])?$input['front_msg']:'', !empty($input['message1'])?$input['message1']:'', !empty($input['message1_0'])?$input['message1_0']:''));
				$fmsg = reset($fmsg);
				$fmsg = stripslashes($fmsg);
				$fmsg2 = array_filter(array(!empty($input['msg3'])?$input['msg3']:'', !empty($input['tpt_pg_FrontMessage2'])?$input['tpt_pg_FrontMessage2']:'', !empty($input['bandFmessage2'])?$input['bandFmessage2']:'', !empty($input['BandFmessage2'])?$input['BandFmessage2']:'', !empty($input['fmessage2'])?$input['fmessage2']:'', !empty($input['band_fmessage2'])?$input['band_fmessage2']:'', !empty($input['front_message2'])?$input['front_message2']:'', !empty($input['front_msg2'])?$input['front_msg2']:'', !empty($input['message1_1'])?$input['message1_1']:''));
				$fmsg2 = reset($fmsg2);
				$fmsg2 = stripslashes($fmsg2);
				$bmsg = array_filter(array(!empty($input['msg2'])?$input['msg2']:'', !empty($input['tpt_pg_BackMessage'])?$input['tpt_pg_BackMessage']:'', !empty($input['bandBmessage'])?$input['bandBmessage']:'', !empty($input['BandBmessage'])?$input['BandBmessage']:'', !empty($input['bmessage'])?$input['bmessage']:'', !empty($input['band_bmessage'])?$input['band_bmessage']:'', !empty($input['back_message'])?$input['back_message']:'', !empty($input['back_msg'])?$input['back_msg']:'', !empty($input['message2'])?$input['message2']:'', !empty($input['message2_0'])?$input['message2_0']:''));
				$bmsg = reset($bmsg);
				$bmsg = stripslashes($bmsg);
				$bmsg2 = array_filter(array(!empty($input['msg4'])?$input['msg4']:'', !empty($input['tpt_pg_BackMessage2'])?$input['tpt_pg_BackMessage2']:'', !empty($input['bandBmessage2'])?$input['bandBmessage2']:'', !empty($input['BandBmessage2'])?$input['BandBmessage2']:'', !empty($input['bmessage2'])?$input['bmessage2']:'', !empty($input['band_bmessage2'])?$input['band_bmessage2']:'', !empty($input['back_message2'])?$input['back_message2']:'', !empty($input['back_msg2'])?$input['back_msg2']:'', !empty($input['message2_1'])?$input['message2_1']:''));
				$bmsg2 = reset($bmsg2);
				$bmsg2 = stripslashes($bmsg2);

				@$front_clipart_left = array_filter(array($input['clp1'], $input['bandLClipart'], $input['BandLClipart'], $input['lclipart'], $input['band_lclipart'], $input['left_clipart'], $input['clipart_left'], $input['clipart1_1_l'], $input['art_front_left'], $input['bandFLClipart'], $input['BandFLClipart'], $input['flclipart'], $input['band_flclipart'], $input['front_left_clipart'], $input['front_clipart_left'], $input['tpt_pg_flclipart']));
				$front_clipart_left = reset($front_clipart_left);
				@$front_clipart_left_c = array_filter(array($input['cclp1'], $input['flclipart_c']));
				$front_clipart_left_c = reset($front_clipart_left_c);
				@$front_clipart_right = array_filter(array($input['clp3'], $input['bandRClipart'], $input['BandRClipart'], $input['rclipart'], $input['band_rclipart'], $input['right_clipart'], $input['clipart_right'], $input['clipart1_1_r'], $input['art_front_right'], $input['bandFRClipart'], $input['BandFRClipart'], $input['frclipart'], $input['band_frclipart'], $input['front_right_clipart'], $input['front_clipart_right'], $input['tpt_pg_frclipart']));
				$front_clipart_right = reset($front_clipart_right);
				@$front_clipart_right_c = array_filter(array($input['cclp3'], $input['frclipart_c']));
				$front_clipart_right_c = reset($front_clipart_right_c);
				@$front_clipart_left2 = array_filter(array($input['clp5'], $input['bandLClipart2'], $input['BandLClipart2'], $input['lclipart2'], $input['band_lclipart2'], $input['left_clipart2'], $input['clipart_left2'], $input['clipart1_2_l'], $input['art_front_left2'], $input['bandFLClipart2'], $input['BandFLClipart2'], $input['flclipart2'], $input['band_flclipart2'], $input['front_left_clipart2'], $input['front_clipart_left2'], $input['tpt_pg_flclipart2']));
				$front_clipart_left2 = reset($front_clipart_left2);
				@$front_clipart_left2_c = array_filter(array($input['cclp5'], $input['flclipart2_c'], $input['fl2clipart_c']));
				$front_clipart_left2_c = reset($front_clipart_left2_c);
				@$front_clipart_right2 = array_filter(array($input['clp7'], $input['bandRClipart2'], $input['BandRClipart2'], $input['rclipart2'], $input['band_rclipart2'], $input['right_clipart2'], $input['clipart_right2'], $input['clipart1_2_r'], $input['art_front_right2'], $input['bandFRClipart2'], $input['BandFRClipart2'], $input['frclipart2'], $input['band_frclipart2'], $input['front_right_clipart2'], $input['front_clipart_right2'], $input['tpt_pg_frclipart2']));
				$front_clipart_right2 = reset($front_clipart_right2);
				@$front_clipart_right2_c = array_filter(array($input['cclp7'], $input['frclipart2_c'], $input['fr2clipart_c']));
				$front_clipart_right2_c = reset($front_clipart_right2_c);
				@$back_clipart_left = array_filter(array($input['clp2'], $input['bandBLClipart'], $input['BandBLClipart'], $input['blclipart'], $input['band_blclipart'], $input['back_left_clipart'], $input['back_clipart_left'], $input['clipart2_1_l'], $input['art_back_left'], $input['tpt_pg_blclipart']));
				$back_clipart_left = reset($back_clipart_left);
				@$back_clipart_left_c = array_filter(array($input['cclp2'], $input['blclipart_c']));
				$back_clipart_left_c = reset($back_clipart_left_c);
				@$back_clipart_right = array_filter(array($input['clp4'], $input['bandBRClipart'], $input['BandBRClipart'], $input['brclipart'], $input['band_brclipart'], $input['back_right_clipart'], $input['back_clipart_right'], $input['clipart2_1_r'], $input['art_back_right'], $input['tpt_pg_brclipart']));
				$back_clipart_right = reset($back_clipart_right);
				@$back_clipart_right_c = array_filter(array($input['cclp4'], $input['brclipart_c']));
				$back_clipart_right_c = reset($back_clipart_right_c);
				@$back_clipart_left2 = array_filter(array($input['clp6'], $input['bandBLClipart2'], $input['BandBLClipart2'], $input['blclipart2'], $input['band_blclipart2'], $input['back_left_clipart2'], $input['back_clipart_left2'], $input['clipart2_2_l'], $input['art_back_left2'], $input['tpt_pg_blclipart2']));
				$back_clipart_left2 = reset($back_clipart_left2);
				@$back_clipart_left2_c = array_filter(array($input['cclp6'], $input['blclipart2_c'], $input['bl2clipart_c']));
				$back_clipart_left2_c = reset($back_clipart_left2_c);
				@$back_clipart_right2 = array_filter(array($input['clp8'], $input['bandBRClipart2'], $input['BandBRClipart2'], $input['brclipart2'], $input['band_brclipart2'], $input['back_right_clipart2'], $input['back_clipart_right2'], $input['clipart2_2_r'], $input['art_back_right2'], $input['tpt_pg_brclipart2']));
				$back_clipart_right2 = reset($back_clipart_right2);
				@$back_clipart_right2_c = array_filter(array($input['cclp8'], $input['brclipart2_c'], $input['br2clipart_c']));
				$back_clipart_right2_c = reset($back_clipart_right2_c);

			/*
				@$glow = array_filter(array($input['glow']));
				$glow = intval(reset($glow), 10);
				@$glitter = array_filter(array($input['glitter']));
				$glitter = intval(reset($glitter), 10);
				@$uv = array_filter(array($input['uv']));
				$uv = intval(reset($uv), 10);
				@$indvl_packaging = array_filter(array($input['indvl_packaging']));
				$indvl_packaging = intval(reset($indvl_packaging), 10);
				@$key_chain = array_filter(array($input['key_chain']));
				$key_chain = intval(reset($key_chain), 10);
				@$key_chain = array_filter(array($input['key_chain']));
				$key_chain = intval(reset($key_chain), 10);
				@$invert_message = array_filter(array($input['invert_message']));
				$invert_message = intval(reset($invert_message), 10);
			*/

				@$custom_clipart = array_filter(array($input['custom_clipart'], $input['customclipart'], $input['customClipart'], $input['custom-clipart']));
				$custom_clipart = reset($custom_clipart);
				
				
				$message_span = array_filter(array(!empty($input['text_layout'])?$input['text_layout']:0, !empty($input['message_span'])?$input['message_span']:0));
				$message_span = intval(reset($message_span), 10);
				if($message_span != 1) {
					$message_span = 0;
				}
				
				
				$bdata = (!empty($data_module->typeStyle[$type][$style])?$data_module->typeStyle[$type][$style]:$vars['config']['default_banddata_row']);
				$blank = (!empty($bdata['blank'])?$bdata['blank']:(!empty($styles[$style]['blank'])?:0));
				//var_dump($message_span);die();
				if(empty($message_span) && ($type != 8)) {
					if(empty($bmsg) && empty($bmsg2)) {
						$bmsg = ' ';
					}
				}

				if(($message_span == 1)) {
					$bmsg = '';
				}
				
				if(!empty($blank)) {
					$fmsg = '';
					$fmsg2 = '';
					$bmsg = '';
					$bmsg2 = '';

					$front_clipart_left = '';
					$front_clipart_right = '';
					$front_clipart_left2 = '';
					$front_clipart_right2 = '';
					$back_clipart_left = '';
					$back_clipart_right = '';
					$back_clipart_left2 = '';
					$back_clipart_right2 = '';

					$front_clipart_left_c = '';
					$front_clipart_right_c = '';
					$front_clipart_left2_c = '';
					$front_clipart_right2_c = '';
					$back_clipart_left_c = '';
					$back_clipart_right_c = '';
					$back_clipart_left2_c = '';
					$back_clipart_right2_c = '';

					$custom_clipart = '';

					$font = '';
					$message_color = '';
					$ink_color = '';
				}



				$rush_order = 0;
				if(!empty($input['rush_order'])) {
					$rushorder_module = $vars['modules']['handler']->modules['RushOrder'];
					if(!$rushorder_module->moduleData['id'][$input['rush_order']]['dummy']) {
						$rush_order = $input['rush_order'];
					}
				}
				$added_by = 0;
				if(!empty($input['short_builder'])) {
					$added_by = $input['short_builder'];
				}
				$reorder = 0;
				if(!empty($input['reorder']))
					$reorder = intval($input['reorder'], 10);

				$reorder_source = '';
				if(!empty($input['reorder_source']))
					$reorder_source = intval($input['reorder_source'], 10);

				if(empty($sizes[$size])) {
					$qty_xs = array_filter(array(!empty($input['bandQty_xs']) ? $input['bandQty_xs'] : '', !empty($input['BandQty_xs']) ? $input['BandQty_xs'] : '', !empty($input['qty_xs']) ? $input['qty_xs'] : '', !empty($input['band_qty_xs']) ? $input['band_qty_xs'] : '', !empty($input['quantity_xs']) ? $input['quantity_xs'] : '', !empty($input['quantity_extra_small']) ? $input['quantity_extra_small'] : ''));
					$qty_xs = intval(reset($qty_xs), 10);
					$qty_sm = array_filter(array(!empty($input['bandQty_sm']) ? $input['bandQty_sm'] : '', !empty($input['BandQty_sm']) ? $input['BandQty_sm'] : '', !empty($input['qty_sm']) ? $input['qty_sm'] : '', !empty($input['band_qty_sm']) ? $input['band_qty_sm'] : '', !empty($input['quantity_sm']) ? $input['quantity_sm'] : '', !empty($input['quantity_small']) ? $input['quantity_small'] : '', !empty($input['bandQty_sm']) ? $input['bandQty_sm'] : ''));
					$qty_sm = intval(reset($qty_sm), 10);
					$qty_m = array_filter(array(!empty($input['bandQty_m']) ? $input['bandQty_m'] : '', !empty($input['BandQty_m']) ? $input['BandQty_m'] : '', !empty($input['qty_m']) ? $input['qty_m'] : '', !empty($input['band_qty_m']) ? $input['band_qty_m'] : '', !empty($input['quantity_m']) ? $input['quantity_m'] : '', !empty($input['quantity_medium']) ? $input['quantity_medium'] : ''));
					$qty_m = intval(reset($qty_m), 10);
					$qty_lg = array_filter(array(!empty($input['bandQty_lg']) ? $input['bandQty_lg'] : '', !empty($input['BandQty_lg']) ? $input['BandQty_lg'] : '', !empty($input['qty_lg']) ? $input['qty_lg'] : '', !empty($input['band_qty_lg']) ? $input['band_qty_lg'] : '', !empty($input['quantity_lg']) ? $input['quantity_lg'] : '', !empty($input['quantity_large']) ? $input['quantity_large'] : '', !empty($input['bandQty']) ? $input['bandQty'] : '', !empty($input['BandQty']) ? $input['BandQty'] : '', !empty($input['band_qty']) ? $input['band_qty'] : '', !empty($input['quantity']) ? $input['quantity'] : ''));
					$qty_lg = intval(reset($qty_lg), 10);
					$qty_xl = array_filter(array(!empty($input['bandQty_xl']) ? $input['bandQty_xl'] : '', !empty($input['BandQty_xl']) ? $input['BandQty_xl'] : '', !empty($input['qty_xl']) ? $input['qty_xl'] : '', !empty($input['band_qty_xl']) ? $input['band_qty_xl'] : '', !empty($input['quantity_xl']) ? $input['quantity_xl'] : '', !empty($input['quantity_extra_large']) ? $input['quantity_extra_large'] : ''));
					$qty_xl = intval(reset($qty_xl), 10);
					$qty_smr = array_filter(array(!empty($input['BandQty_smr']) ? $input['BandQty_smr'] : '', !empty($input['qty_smr']) ? $input['qty_smr'] : '', !empty($input['band_qty_smr']) ? $input['band_qty_smr'] : '', !empty($input['quantity_smr']) ? $input['quantity_smr'] : '', !empty($input['quantity_small_ring']) ? $input['quantity_small_ring'] : ''));
					$qty_smr = intval(reset($qty_smr), 10);
					$qty_mr = array_filter(array(!empty($input['bandQty_mr']) ? $input['bandQty_mr'] : '', !empty($input['BandQty_mr']) ? $input['BandQty_mr'] : '', !empty($input['qty_mr']) ? $input['qty_mr'] : '', !empty($input['band_qty_mr']) ? $input['band_qty_mr'] : '', !empty($input['quantity_mr']) ? $input['quantity_mr'] : '', !empty($input['quantity_medium_ring']) ? $input['quantity_medium_ring'] : ''));
					$qty_mr = intval(reset($qty_mr), 10);
					$qty_lgr = array_filter(array(!empty($input['bandQty_lgr']) ? $input['bandQty_lgr'] : '', !empty($input['BandQty_lgr']) ? $input['BandQty_lgr'] : '', !empty($input['qty_lgr']) ? $input['qty_lgr'] : '', !empty($input['band_qty_lgr']) ? $input['band_qty_lgr'] : '', !empty($input['quantity_lgr']) ? $input['quantity_lgr'] : '', !empty($input['quantity_large_ring']) ? $input['quantity_large_ring'] : ''));
					$qty_lgr = intval(reset($qty_lgr), 10);

					$qty_un = array_filter(array(!empty($input['bandQty_un']) ? $input['bandQty_un'] : '', !empty($input['BandQty_un']) ? $input['BandQty_un'] : '', !empty($input['qty_un']) ? $input['qty_un'] : '', !empty($input['band_qty_un']) ? $input['band_qty_un'] : '', !empty($input['quantity_un']) ? $input['quantity_un'] : '', !empty($input['quantity_universal']) ? $input['quantity_universal'] : ''));
					$qty_un = intval(reset($qty_un), 10);

					$size = array(
						'xs' => $qty_xs,
						'sm' => $qty_sm,
						'm' => $qty_m,
						'lg' => $qty_lg,
						'xl' => $qty_xl,
						'smr' => $qty_smr,
						'mr' => $qty_mr,
						'lgr' => $qty_lgr,
						'un' => $qty_un,
					);
				} else {
					//tpt_dump('asdasdas');
					$size = array(
						$sizes[$size]['name'] => $qty,
					);
				}

				//tpt_dump($iterations);
				//tpt_dump($size);

				foreach($size as $k=>$s) {
					$st = $style;
					if(true) {
						$sz = (!empty($size_module->moduleData['name'][$k]['id'])?$size_module->moduleData['name'][$k]['id']:0);

						if(($s<50) && ($style == 1)) {
								$st = 6;
						}

						if(($s<50) && ($type == 9)) {
								$st = 6;
						}

						if(($s<50) && ($type == 11)) {
								$st = 6;
						}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// process product options parameters
$pparams = $input;
$pparams['type'] = $type;
$pparams['style'] = $style;
$pparams['color'] = $color;
$pparams['message_color'] = $message_color;
$pparams['message_span'] = $message_span;
$pparams['fmsg'] = $fmsg;
$pparams['fmsg2'] = $fmsg2;
$pparams['bmsg'] = $bmsg;
$pparams['bmsg2'] = $bmsg2;

$pparams['front_clipart_left'] = $front_clipart_left;
$pparams['front_clipart_right'] = $front_clipart_right;
$pparams['front_clipart_left2'] = $front_clipart_left2;
$pparams['front_clipart_right2'] = $front_clipart_right2;
$pparams['back_clipart_left'] = $back_clipart_left;
$pparams['back_clipart_right'] = $back_clipart_right;
$pparams['back_clipart_left2'] = $back_clipart_left2;
$pparams['back_clipart_right2'] = $back_clipart_right2;

$pparams['front_clipart_left_c'] = $front_clipart_left_c;
$pparams['front_clipart_right_c'] = $front_clipart_right_c;
$pparams['front_clipart_left2_c'] = $front_clipart_left2_c;
$pparams['front_clipart_right2_c'] = $front_clipart_right2_c;
$pparams['back_clipart_left_c'] = $back_clipart_left_c;
$pparams['back_clipart_right_c'] = $back_clipart_right_c;
$pparams['back_clipart_left2_c'] = $back_clipart_left2_c;
$pparams['back_clipart_right2_c'] = $back_clipart_right2_c;

$pparams['rush_order'] = $rush_order;
$pparams['added_by'] = $added_by;

						/*
$pparams['glow'] = $glow;
$pparams['glitter'] = $glitter;
$pparams['uv'] = $uv;
$pparams['indvl_packaging'] = $indvl_packaging;
$pparams['key_chain'] = $key_chain;
$pparams['invert_message'] = $invert_message;
						*/

//tpt_dump($input);
//tpt_dump($pparams);
$price_modifiers = self::processPriceModifiers($vars, $pparams);
////////////////////////////////////////////////////////////////////////////////////////////////////// END OPTION PRICE MODIFIERS

						/*
						if($_SERVER['REMOTE_ADDR'] == '109.160.0.218') {
								//var_dump($price_modifiers);die();
								var_dump($type);//die();
								var_dump($st);//die();
								var_dump($s);//die();
								//var_dump($discount);//die();
								var_dump($price_modifiers);die();
						}
						*/
						//$discount = $_POST['discount'];
						$discount = ((isset($input['discount']) && is_numeric($input['discount']) && $allowdiscount)?floatval($input['discount']):GLOBAL_CUSTOMPRODUCT_PRICEOFF_PERCENT);
						//tpt_dump($input['final_total'], true);
						$pricingObject = new amz_pricing($vars, $type, $st, array('lg'=>$s), $price_modifiers, $discount, isset($input['final_total'])?$input['final_total']:null);
						//tpt_dump($price_modifiers,true);
						//tpt_dump($pricingObject,true);

						$comments = '';
						//tpt_dump($input, true);
						if(!empty($input['design_notes'])) {
							$comments = strip_tags($input['design_notes']);
						} else if(!empty($input['comments'])) {
							$comments = strip_tags($input['comments']);
						}
						/*
						else if(!empty($input['user_design_notes'])) {
							$comments = strip_tags($input['user_design_notes']);
						}
						*/

						$invert_dual = 0;
						if(!empty($input['invert_dual']) || !empty($input['invert_message']))
								$invert_dual = 1;

						$cut_away = 0;
						if(!empty($input['cut_away']) || !empty($input['invert_message']))
								$cut_away = 1;

						$data = array(
								'quote_id'=>!empty($input['quote_id'])?$input['quote_id']:null,
								'product_id'=>!empty($input['product_id'])?$input['product_id']:null,
								'messages'=>array('front'=>array_filter(array(stripslashes($fmsg), stripslashes($fmsg2))), 'back'=>array_filter(array(stripslashes($bmsg), stripslashes($bmsg2)))),
								'clipart'=>array(
													'front'=>array(
																array('left'=>stripslashes($front_clipart_left),
																		'right'=>stripslashes($front_clipart_right)
																		),
																array('left'=>stripslashes($front_clipart_left2),
																		'right'=>stripslashes($front_clipart_right2)
																		)
																),
													'back'=>array(
																array('left'=>stripslashes($back_clipart_left),
																		'right'=>stripslashes($back_clipart_right)
																		),
																array('left'=>stripslashes($back_clipart_left2),
																		'right'=>stripslashes($back_clipart_right2)
																		)
																),
													),
							'clipart_c'=>array(
								'front'=>array(
									array('left'=>stripslashes($front_clipart_left_c),
										'right'=>stripslashes($front_clipart_right_c)
									),
									array('left'=>stripslashes($front_clipart_left2_c),
										'right'=>stripslashes($front_clipart_right2_c)
									)
								),
								'back'=>array(
									array('left'=>stripslashes($back_clipart_left_c),
										'right'=>stripslashes($back_clipart_right_c)
									),
									array('left'=>stripslashes($back_clipart_left2_c),
										'right'=>stripslashes($back_clipart_right2_c)
									)
								),
							),
				// 			'custom_clipart'=>$custom_clipart,
								'custom_clipart'=>(is_null(@json_decode($custom_clipart))?$custom_clipart:json_decode($custom_clipart)),
								'band_type'=>$type,
								'band_style'=>$st,
								'band_class'=>$class,
								'invert_dual'=>$invert_dual,
								'cut_away'=>$cut_away,
								'band_size'=>$sz,
								'band_color'=>$color,
								'ink_color'=>$ink_color,
								'message_color'=>$message_color,
								'message_span'=>$message_span,
								'band_font'=>$font,
								'text_color'=>!empty($input['TextColor'])?$input['TextColor']:'',
								'comments'=>$comments,
								'rush_order'=>$rush_order,
								'added_by'=>$added_by,
								'reorder'=>$reorder,
								'reorder_source'=>$reorder_source
						);
						if(!empty($input['primg'])) {
								$data['primg'] = $input['primg'];
						}



						$p = new amz_customproduct($vars, $pricingObject, $data);
							tpt_logger::dump($vars, $pricingObject, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), '$pricingObject', __FILE__.' '.__LINE__);
							tpt_logger::dump($vars, $data, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), '$data', __FILE__.' '.__LINE__);
							tpt_logger::dump($vars, $p, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), '$p', __FILE__.' '.__LINE__);
						$products[$key] = $p;

					}
				}

		}


		return $products;

	}




	static function processPriceModifiers(&$vars, $input=array(), $product=array()) {
		$data_module = getModule($vars, 'BandData');
		$color_module = getModule($vars, 'BandColor');

		$price_modifiers = array();

		if(empty($product)) {
				//tpt_dump($type,true);
				if(empty($data_module->typeStyle[$input['type']][$input['style']]['pricing_type'])) {
					// overseas

					if(!empty($input['color'])) {
						$product_color = explode(':', $input['color']);
						$tableId = intval($product_color[0], 10);
						$colorId = isset($product_color[1]) ? $product_color[1] : 0;
						$price_modifiers = $color_module->getColorProps($vars, $input['color']);
						$price_modifiers['glow_ink_fill'] = 0;
					}
					if(!empty($input['message_color'])) {
						$mprice_modifiers = false;
						if (!empty($input['message_color'])) {
							$mprice_modifiers = $color_module->getColorProps($vars, $input['message_color']);
							if (!empty($mprice_modifiers['glow'])) {
								$price_modifiers['glow_ink_fill'] = 1;
							}
						}
					}

					$price_modifiers['key_chain'] = (!empty($input['key_chain'])?$input['key_chain']:0);
					$price_modifiers['key_chain_clasp'] = (!empty($input['key_chain_clasp'])?$input['key_chain_clasp']:0);

					$price_modifiers['plastic_snaps'] = (!empty($input['plastic_snaps'])?$input['plastic_snaps']:0);

					$price_modifiers['glow_ink_fill'] = !empty($input['glow_ink_fill'])?intval($input['glow_ink_fill'], 10):(!empty($price_modifiers['glow_ink_fill'])?$price_modifiers['glow_ink_fill']:0);

					$price_modifiers['glow'] = !empty($input['glow'])?intval($input['glow'], 10):(!empty($price_modifiers['glow'])?$price_modifiers['glow']:0);
					//tpt_dump($input['glow']);
					//tpt_dump($price_modifiers['glow']);
					$price_modifiers['glitter'] = !empty($input['glitter'])?intval($input['glitter'], 10):(!empty($price_modifiers['glitter'])?$price_modifiers['glitter']:0);
					$price_modifiers['uv'] = !empty($input['uv'])?intval($input['uv'], 10):(!empty($price_modifiers['uv'])?$price_modifiers['uv']:0);

				} else {
					//tpt_dump($price_modifiers,true);
					// in-house
					if((!empty($input['bmsg']) && (($input['bmsg'] != ' ')) || (!empty($input['bmsg2']) && ($input['bmsg2'] != ' '))) || !empty($input['back_clipart_left']) || !empty($input['back_clipart_right']) || !empty($input['back_clipart_left2']) || !empty($input['back_clipart_right2']) || !empty($input['back_clipart_left_c']) || !empty($input['back_clipart_right_c']) || !empty($input['back_clipart_left2_c']) || !empty($input['back_clipart_right2_c'])) {
						$price_modifiers['back_msgs']['lg'] = 1;
					}
					$price_modifiers['insd_msgs'] = 0;

					$price_modifiers['rush_order'] = $input['rush_order'];
				}

				if(($input['type'] == 9) || ($input['type'] == 12)) {
					//$price_modifiers['writable'] = 1;
				}

				if($input['type'] == 11) {
					//$price_modifiers['writable_bm'] = 1;
				}

				// universal price modifiers
				$price_modifiers['product_rush'] = 0;
				$price_modifiers['ship_rush'] = 0;
				$price_modifiers['indvl_packaging'] = (!empty($input['indvl_packaging'])?$input['indvl_packaging']:(!empty($input['indvl_pckg'])?$input['indvl_pckg']:0));
				$price_modifiers['indvl_inserts'] = 0;
		} else {
				//tpt_dump($type,true);
			if (is_a($product, 'amz_customproduct')) {
				if (empty($data_module->typeStyle[$product->data['band_type']][$product->data['band_style']]['pricing_type'])) {
					// overseas


					$product_color = explode(':', $product->data['band_color']);
					$tableId = intval($product_color[0], 10);
					$colorId = isset($product_color[1]) ? $product_color[1] : 0;
					$price_modifiers = $color_module->getColorProps($vars, $product->data['band_color']);
					$price_modifiers['glow_ink_fill'] = 0;
					$mprice_modifiers = false;
					if (!empty($product->data['message_color'])) {
						$mprice_modifiers = $color_module->getColorProps($vars, $product->data['message_color']);
						if (!empty($mprice_modifiers['glow'])) {
							$price_modifiers['glow_ink_fill'] = 1;
						}
					}

					$price_modifiers['key_chain'] = (!empty($product->pricingObject->options['key_chain']) ? $product->pricingObject->options['key_chain'] : 0);
					$price_modifiers['key_chain_clasp'] = (!empty($product->pricingObject->options['key_chain_clasp']) ? $product->pricingObject->options['key_chain_clasp'] : 0);

					$price_modifiers['plastic_snaps'] = (!empty($product->pricingObject->options['plastic_snaps']) ? $product->pricingObject->options['plastic_snaps'] : 0);

					$price_modifiers['glow_ink_fill'] = !empty($input['glow_ink_fill']) ? intval($input['glow_ink_fill'], 10) : $price_modifiers['glow_ink_fill'];
					$price_modifiers['glow'] = !empty($input['glow']) ? intval($input['glow'], 10) : $price_modifiers['glow'];
					$price_modifiers['glitter'] = !empty($input['glitter']) ? intval($input['glitter'], 10) : $price_modifiers['glitter'];
					$price_modifiers['uv'] = !empty($input['uv']) ? intval($input['uv'], 10) : $price_modifiers['uv'];

				} else {
					//tpt_logger::dump($vars, $product->data['messages'], debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), '$product->data[\'messages\']', __FILE__.' '.__LINE__);
					// in-house
					if (
						(
							!empty($product->data['messages']['back'][0])
							&&
							(
								$product->data['messages']['back'][0] != ' '
							)
						)
						||
						(
							!empty($product->data['messages']['back'][1])
							&&
							(
								$product->data['messages']['back'][1] != ' '
							)
						)
						||
						!empty($product->data['clipart']['back'][0]['left'])
						||
						!empty($product->data['clipart']['back'][0]['right'])
						||
						!empty($product->data['clipart']['back'][1]['left'])
						||
						!empty($product->data['clipart']['back'][1]['right'])
						||
						!empty($product->data['clipart_c']['back'][0]['left'])
						||
						!empty($product->data['clipart_c']['back'][0]['right'])
						||
						!empty($product->data['clipart_c']['back'][1]['left'])
						||
						!empty($product->data['clipart_c']['back'][1]['right'])
					) {
						$price_modifiers['back_msgs']['lg'] = 1;
					}
					$price_modifiers['insd_msgs'] = 0;

					$price_modifiers['rush_order'] = $product->pricingObject->options['rush_order'];
				}

				if (($product->data['band_type'] == 9) || ($product->data['band_type'] == 12)) {
					//$price_modifiers['writable'] = 1;
				}

				if ($product->data['band_type'] == 11) {
					//$price_modifiers['writable_bm'] = 1;
				}

				// universal price modifiers
				$price_modifiers['product_rush'] = 0;
				$price_modifiers['ship_rush'] = 0;
				$price_modifiers['indvl_packaging'] = (!empty($product->pricingObject->options['indvl_packaging']) ? $product->pricingObject->options['indvl_packaging'] : 0);
				$price_modifiers['indvl_inserts'] = 0;
			} else if (is_a($product, 'amz_product2')) {
				if (empty($data_module->typeStyle[$product->data['type']][$product->data['style']]['pricing_type'])) {
					// overseas


					$product_color = explode(':', $product->data['color']);
					$tableId = intval($product_color[0], 10);
					$colorId = $product_color[1];
					$price_modifiers = $color_module->getColorProps($vars, $product->data['color']);
					$price_modifiers['glow_ink_fill'] = 0;
					$mprice_modifiers = false;
					if (!empty($product->data['message_color'])) {
						$mprice_modifiers = $color_module->getColorProps($vars, $product->data['message_color']);
						if (!empty($mprice_modifiers['glow'])) {
							$price_modifiers['glow_ink_fill'] = 1;
						}
					}

					$price_modifiers['key_chain'] = (!empty($product->pricingObject->options['key_chain']) ? $product->pricingObject->options['key_chain'] : 0);
					$price_modifiers['key_chain_clasp'] = (!empty($product->pricingObject->options['key_chain_clasp']) ? $product->pricingObject->options['key_chain_clasp'] : 0);

					$price_modifiers['plastic_snaps'] = (!empty($product->pricingObject->options['plastic_snaps']) ? $product->pricingObject->options['plastic_snaps'] : 0);

					$price_modifiers['glow_ink_fill'] = !empty($input['glow_ink_fill']) ? intval($input['glow_ink_fill'], 10) : $price_modifiers['glow_ink_fill'];
					$price_modifiers['glow'] = !empty($input['glow']) ? intval($input['glow'], 10) : $price_modifiers['glow'];
					$price_modifiers['glitter'] = !empty($input['glitter']) ? intval($input['glitter'], 10) : $price_modifiers['glitter'];
					$price_modifiers['uv'] = !empty($input['uv']) ? intval($input['uv'], 10) : $price_modifiers['uv'];

				} else {
					//tpt_logger::dump($vars, $product->data['messages'], debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), '$product->data[\'messages\']', __FILE__.' '.__LINE__);
					// in-house
					if (
						(
							!empty($product->data['msg2'])
							&&
							(
								$product->data['msg2'] != ' '
							)
						)
						||
						(
							!empty($product->data['msg4'])
							&&
							(
								$product->data['msg4'] != ' '
							)
						)
						||
						!empty($product->data['clp2'])
						||
						!empty($product->data['clp4'])
						||
						!empty($product->data['clp6'])
						||
						!empty($product->data['clp8'])
						||
						!empty($product->data['cclp2'])
						||
						!empty($product->data['cclp4'])
						||
						!empty($product->data['cclp6'])
						||
						!empty($product->data['cclp8'])
					) {
						$price_modifiers['back_msgs']['lg'] = 1;
					}
					$price_modifiers['insd_msgs'] = 0;

					$price_modifiers['rush_order'] = $product->pricingObject->options['rush_order'];
				}

				if (($product->data['type'] == 9) || ($product->data['type'] == 12)) {
					//$price_modifiers['writable'] = 1;
				}

				if ($product->data['type'] == 11) {
					//$price_modifiers['writable_bm'] = 1;
				}

				// universal price modifiers
				$price_modifiers['product_rush'] = 0;
				$price_modifiers['ship_rush'] = 0;
				$price_modifiers['indvl_packaging'] = (!empty($product->pricingObject->options['indvl_packaging']) ? $product->pricingObject->options['indvl_packaging'] : 0);
				$price_modifiers['indvl_inserts'] = 0;
			}
		}

		//tpt_dump(debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS));
		//tpt_dump($input);
		//tpt_dump($price_modifiers);
		return $price_modifiers;
	}






	static function reorder(&$vars, $order_id=0, $type='order') {
		if($type=='quote') {
			$orders_module = getModule($vars, 'Orders');
			$sarr = $orders_module->convertOldProductsData($vars, $order_id);
			$products = $sarr['exproducts'];

			if(!empty($products)) {
				foreach($products as $product) {
					$product->data['reorder'] = $order_id;
					$product->data['reorder_source'] = 'quote';
					self::$products[] = $product;
				}

				$vars['environment']['ajax_result']['messages']['addtocartmessage'] = array('Product(s) added to your cart.', 'message');
			} else {
				$vars['template_data']['valid_form'] = false;
				$vars['environment']['ajax_result']['messages'][] = array('ERROR! Empty product data!', 'error');
			}

			$builder_id = 0;
			self::store_cart($vars, 'amz_cart::reorder (cart/pricing)', self::$products, $builder_id);
		} else if($type=='cart' || $type=='pricing') {
				$field = 'products';
				if($type=='cart') {
				$order_stats = $vars['db']['handler']->getData($vars, DB_DB_TPT_LOGS.'.tpt_request_cart', '*', ' `id`='.$order_id, 'id', false);
				} else {
				$order_stats = $vars['db']['handler']->getData($vars, DB_DB_TPT_LOGS.'.tpt_request_pricing', '*', ' `id`='.$order_id, 'id', false);
				}

				if(is_array($order_stats)) {
					$order_stats = reset($order_stats);
				}

				//var_dump($order_stats);die();

				if(!empty($order_stats) && (($products = @unserialize($order_stats['products'])) !== false)) {
					if(!empty($products)) {
						foreach($products as $product) {
								$product->data['reorder'] = $order_id;
								$product->data['reorder_source'] = $type;
			//tpt_dump("This data is important:::->".$product->data['reorder']);
								self::$products[] = $product;
						}

						$vars['environment']['ajax_result']['messages']['addtocartmessage'] = array('Product(s) added to your cart.', 'message');
					} else {
						$vars['template_data']['valid_form'] = false;
						$vars['environment']['ajax_result']['messages'][] = array('ERROR! Empty product data!', 'error');
					}
				} else {
					//var_dump($order_stats);die();
					$vars['template_data']['valid_form'] = false;
					$vars['environment']['ajax_result']['messages'][] = array('ERROR!', 'error');
				}

				
				$builder_id = 0;
				/*
				if(!empty($_POST['short_builder']))
					$builder_id = intval($_POST['short_builder'], 10);
				$query = 'INSERT INTO `tpt_request_cart` (`ip`, `userid`, `timestamp`, `action`, `products`, `builder`)
							VALUES (
							"'.$vars['user']['client_ip'].'",
							'.$vars['user']['userid'].',
							'.time().',
							"'.$task.'",
							"'.mysql_real_escape_string(serialize(amz_cart::$products)).'",
							'.$builder_id.'
							)';
				//var_dump($query);die();
				$vars['db']['handler']->query($query, __FILE__);
				*/
				//$iid = tpt_logger::log_cart($vars, 'tpt_request_cart', 'amz_cart::reorder', self::$products, '', $builder_id);
				self::store_cart($vars, 'amz_cart::reorder (cart/pricing)', self::$products, $builder_id);
		} else {
				$order_stats = $vars['db']['handler']->getData($vars, 'tpt_order_carts', '*', ' `order_id`='.$order_id, 'order_id', false);

				if(is_array($order_stats)) {
					$order_stats = reset($order_stats);
				}

				//var_dump($order_stats);die();

				if(!empty($order_stats) && (($products = @unserialize($order_stats['cart_pdt'])) !== false)) {
					if(!empty($products)) {
						foreach($products as $product) {
								$product->data['reorder'] = $order_id;
								$product->data['reorder_source'] = 'order';
								self::$products[] = $product;
						}

						$vars['environment']['ajax_result']['messages']['addtocartmessage'] = array('Product(s) added to your cart.', 'message');
					} else {
						$vars['template_data']['valid_form'] = false;
						$vars['environment']['ajax_result']['messages'][] = array('ERROR! Empty product data!', 'error');
					}
				} else {
					//var_dump($order_stats);die();
					$vars['template_data']['valid_form'] = false;
					$vars['environment']['ajax_result']['messages'][] = array('ERROR!', 'error');
				}

				$builder_id = 0;
				/*
				if(!empty($_POST['short_builder']))
					$builder_id = intval($_POST['short_builder'], 10);
				$query = 'INSERT INTO `tpt_request_cart` (`ip`, `userid`, `timestamp`, `action`, `products`, `builder`)
							VALUES (
							"'.$vars['user']['client_ip'].'",
							'.$vars['user']['userid'].',
							'.time().',
							"'.$task.'",
							"'.mysql_real_escape_string(serialize(amz_cart::$products)).'",
							'.$builder_id.'
							)';
				//var_dump($query);die();
				$vars['db']['handler']->query($query, __FILE__);
				*/
				//$iid = tpt_logger::log_cart($vars, 'tpt_request_cart', 'amz_cart::reorder', self::$products, '', $builder_id);
				self::store_cart($vars, 'amz_cart::reorder (order)', self::$products, $builder_id);
		}

		//$return_url = $vars['environment']['go_back_url'];
		$return_url = $vars['url']['handler']->wrap($vars, '/your-basket');
		tpt_request::redirect($vars, $return_url);
	}

	static function add(&$vars, $param1=false) {
		self::add_to_cart($vars, 'cart', $param1);
	}

	static function add_pricing(&$vars, $param1=false) {
		self::add_to_cart($vars, 'pricing', $param1);
	}

	static function add_to_cart(&$vars, $target='cart', $param1=false) {
		$iid = 0;
		$builder_id = 0;
		if(!empty($_POST['short_builder']))
				$builder_id = intval($_POST['short_builder'], 10);

		if($target == 'pricing') {
				if(is_a($param1, 'amz_product2') || is_a($param1, 'amz_customproduct') || is_a($param1, 'amz_stockproduct') || is_a($param1, 'amz_customStockproduct') || is_a($param1, 'amz_bundle')) {
					//var_dump($param1);die();
					self::$pricing_products[] = $param1;
				}
		} else {
				if(is_a($param1, 'amz_product2') || is_a($param1, 'amz_customproduct') || is_a($param1, 'amz_stockproduct') || is_a($param1, 'amz_customStockproduct') || is_a($param1, 'amz_bundle')) {
					//var_dump($param1);die();
					self::$products[] = $param1;
					//tpt_dump($builder_id, true);
					//$iid = tpt_logger::log_cart($vars, 'tpt_request_cart', 'amz_cart::add_to_cart', self::$products, '', $builder_id);
					
					self::store_cart($vars, 'amz_cart::add_to_cart', self::$products, $builder_id);

					$sku = $param1->getSku($vars);
					$category = 'Builder Products';
					$price = number_format(0, 2);
					$qty = $param1->qty;

					$vars['environment']['ajax_result']['messages']['addtocartmessage'] = array('Product(s) added to your cart.', 'message');
					/*
					$vars['environment']['ajax_result']['execute_onload']['head'][] .= <<< EOT
<script type="text/javascript">
//<![CDATA[
ga('require', 'ecommerce');
ga('ecommerce:addItem', {
  'id': '$iid',							// Transaction ID. Required.
  'name': 'AMZG Product',	// Product name. Required.
  'sku': '$sku',						// SKU/code.
  'category': '$category', 		// Category or variation.
  'price': '$price', 					// Unit price.
  'quantity': '$qty' 						// Quantity.
});
//]]>
</script>
EOT;
*/
				}
		}

		//return $iid;
	}
	
	
	static function store_cart(&$vars, $task='unspecified', $products=array(), $builder_id=0) {
		$cart_id = $_SESSION['cart_id'] = tpt_logger::log_cart($vars, 'tpt_request_cart', $task, $products, '', $builder_id);
		
		tpt_current_user::update_store_cart_id($vars, $cart_id);

	}
	
	
	
	

	static function getTotals(&$vars) {
		//$vars['db']['handler']->query('SELECT * FROM ASDASD fdk');
		//$vars['db']['handler']->getData($vars, 'SELECT * FROM ASDASD fdk');
		//tpt_dump('asdasdass', true);
		$pricingObjects = array();
		$inhouse = array();
		$overseas = array();
		$stockcustom = array();
		//if($_SERVER['REMOTE_ADDR'] == '109.120.245.125') var_dump(self::$products);die();


		foreach(self::$products as $product) {
				//if($_SERVER['REMOTE_ADDR'] == '109.120.245.125') var_dump($product);
				//if (is_a($product, 'amz_customStockproduct')) {
				//}
				if(is_a($product, 'amz_customproduct')) {
					//if($_SERVER['REMOTE_ADDR'] == '109.120.245.125') $pricingObjects[] = $product->pricingObject;
					if(($product->data['band_style'] != 7)) {
						$price_modifiers = self::processPriceModifiers($vars, array(), $product);


						$product->pricingObject->regenerate($vars, null, null, null, $price_modifiers, null);
					}

					/*
					if(($_SERVER['REMOTE_ADDR'] == '109.160.0.218') && ($_GET['debug'] == 'debug')) {
						//var_dump($this->pricingTable);//die();
						//var_dump($this->total_qty);//die();
						var_dump($product->pricingObject);
						//var_dump(count($mfgcosts));//die();
						//var_dump($mfgcosts[count($mfgcosts)-1]);//die();
						//var_dump($mfg_costs);//die();
						//var_dump($options_costs);//die();
						//var_dump($this->mfgcost);

					}
					*/
					$product->pricingObject->getPrice();
					if(!$product->pricingObject->pricingType) {
						$overseas[] = $product;
					} else {
						$inhouse[] = $product;
						/*
						if($_SERVER['REMOTE_ADDR'] != '109.160.0.218') {
						$values = array('sbase_price'=>$product->pricingObject->price['values']['customer_price_per_discounted'], 'mbase_price'=>$product->pricingObject->price['values']['customer_price_total_discounted']);
						$html = $values;
						//var_dump($html);
						array_walk($html, 'format_price_array');
						$product->price = array('html'=>$html, 'values'=>$values);
						}
						*/
					}
				} else if(is_a($product, 'amz_product2')) {
					//if($_SERVER['REMOTE_ADDR'] == '109.120.245.125') $pricingObjects[] = $product->pricingObject;
					if(($product->data['style'] != 7)) {
						$price_modifiers = self::processPriceModifiers($vars, array(), $product);


						$product->pricingObject->regenerate($vars, null, null, null, $price_modifiers, null);
					}

					/*
					if(($_SERVER['REMOTE_ADDR'] == '109.160.0.218') && ($_GET['debug'] == 'debug')) {
						//var_dump($this->pricingTable);//die();
						//var_dump($this->total_qty);//die();
						var_dump($product->pricingObject);
						//var_dump(count($mfgcosts));//die();
						//var_dump($mfgcosts[count($mfgcosts)-1]);//die();
						//var_dump($mfg_costs);//die();
						//var_dump($options_costs);//die();
						//var_dump($this->mfgcost);

					}
					*/
					$product->pricingObject->getPrice();
					if(!$product->pricingObject->pricingType) {
						$overseas[] = $product;
					} else {
						$inhouse[] = $product;
						/*
						if($_SERVER['REMOTE_ADDR'] != '109.160.0.218') {
						$values = array('sbase_price'=>$product->pricingObject->price['values']['customer_price_per_discounted'], 'mbase_price'=>$product->pricingObject->price['values']['customer_price_total_discounted']);
						$html = $values;
						//var_dump($html);
						array_walk($html, 'format_price_array');
						$product->price = array('html'=>$html, 'values'=>$values);
						}
						*/
					}
				} else if (is_a($product, 'amz_customStockproduct')) {
					//$cur_result = amz_pricing::getStockProductPricing($vars, array($product->data['sku'], $product->qty));
					//$product->price = $cur_result;
					$stockcustom[] = $product;

					//$flatfee = true;
				}
				$pricingObjects[] = $product;
		}

		//if($_SERVER['REMOTE_ADDR'] == '109.120.245.125') var_dump($pricingObjects);

		//if($_SERVER['REMOTE_ADDR'] == '109.160.0.218') {
		$precision_subtotal = 0;
		self::$totals['pricing'] = amz_pricing::getBulkPricing($vars, $pricingObjects);
		if(!empty($stockcustom)) {
				//var_dump(amz_pricing::getBulkStockCustomPricing($vars, $stockcustom));die();
				//var_dump(self::$totals['pricing']);die();
				self::$totals['pricing']['values']['retail_price'] += amz_pricing::getBulkStockCustomPricing($vars, $stockcustom);
				self::$totals['pricing']['values']['customer_price'] += amz_pricing::getBulkStockCustomPricing($vars, $stockcustom);
				self::$totals['pricing']['values']['lowest_price'] += amz_pricing::getBulkStockCustomPricing($vars, $stockcustom);
				$precision_subtotal += amz_pricing::getBulkStockCustomPricing($vars, $stockcustom);
				//self::$totals['pricing']['values']['lowest_price_discounted'] += amz_pricing::getBulkStockCustomPricing($vars, $stockcustom);
		}
		//if($_SERVER['REMOTE_ADDR'] == '109.120.245.125') var_dump(self::$totals['pricing']);//die();


		//tpt_logger::dump($vars, self::$totals['pricing'], debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), 'self::$totals[\'pricing\']', __FILE__.' '.__LINE__);
		$inhouse_total = 0;
		$inhouse_quantity = 0;
		$lowest_inhouse = null;
		$lowest_inhouse_products_price = 0;
		$overseas_total = 0;
		$overseas_quantity = 0;
		$lowest_overseas = null;
		$lowest_overseas_products_price = 0;
		//if($_SERVER['REMOTE_ADDR'] == '109.160.0.218') {
		foreach($inhouse as $product) {
				if(is_null($product->pricingObject->final_total) && (self::$totals['pricing']['values']['ihc'] < 1)) {
					if(defined('CART_INHOUSE_PRICE_TOTAL_RECALCULATE') && (CART_INHOUSE_PRICE_TOTAL_RECALCULATE == 1)) {
						$product_price = round($product->pricingObject->price['values']['lowest_price_per_discounted']*self::$totals['pricing']['values']['ihc'], CART_INHOUSE_PRICE_PER_DECIMALS);
						$products_price = round($product_price*$product->qty, CART_INHOUSE_PRICE_TOTAL_DECIMALS);
					} else {
						$products_price = round($product->pricingObject->price['values']['lowest_price_total_discounted']*self::$totals['pricing']['values']['ihc'], CART_INHOUSE_PRICE_TOTAL_DECIMALS);
						$product_price = round($products_price/$product->qty, CART_INHOUSE_PRICE_PER_DECIMALS);
					}
				} else {
					if(defined('CART_INHOUSE_PRICE_TOTAL_RECALCULATE') && (CART_INHOUSE_PRICE_TOTAL_RECALCULATE == 1)) {
						$product_price = round($product->pricingObject->price['values']['lowest_price_per_discounted'], CART_INHOUSE_PRICE_PER_DECIMALS);
						$products_price = round($product_price*$product->qty, CART_INHOUSE_PRICE_TOTAL_DECIMALS);
					} else {
						$products_price = round($product->pricingObject->price['values']['lowest_price_total_discounted'], CART_INHOUSE_PRICE_TOTAL_DECIMALS);
						$product_price = round($products_price/$product->qty, CART_INHOUSE_PRICE_PER_DECIMALS);
					}
				}
				//var_dump($product->pricingObject->price['values']);die();
				$values = array('sbase_price'=>$product_price, 'mbase_price'=>$products_price);
				$html = $values;
				//var_dump($html);
				array_walk($html, 'format_price_array');
				//var_dump($product->pricingObject);die();
				$product->price = array('html'=>$html, 'values'=>$values);
				$precision_subtotal += $products_price;
				$inhouse_total += $products_price;
				$inhouse_quantity += $product->qty;
				if(($lowest_inhouse_products_price==0) || ($products_price<$lowest_inhouse_products_price)) {
					$lowest_inhouse = key($inhouse);
					$lowest_inhouse_products_price = $products_price;
				}
				tpt_logger::dump($vars, $product->pricingObject->options, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), '$product->pricingObject->options', __FILE__.' '.__LINE__);
				tpt_logger::dump($vars, $product->qty.' '.$product_price.' '.$products_price, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), '$product->qty.\' \'.$product_price.\' \'.$products_price', __FILE__.' '.__LINE__);
				//tpt_logger::dump($vars, $product_price, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), '$product_price', __FILE__.' '.__LINE__);
				//tpt_logger::dump($vars, $products_price, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), '$products_price', __FILE__.' '.__LINE__);
		}


		foreach($overseas as $product) {
				if(is_null($product->pricingObject->final_total) && (self::$totals['pricing']['values']['osc'] < 1)) {
					if(defined('CART_OVERSEAS_PRICE_TOTAL_RECALCULATE') && (CART_OVERSEAS_PRICE_TOTAL_RECALCULATE == 1)) {
					$product_price = round($product->pricingObject->price['values']['lowest_price_per_discounted']*self::$totals['pricing']['values']['osc'], CART_OVERSEAS_PRICE_PER_DECIMALS);
					$products_price = round($product_price*$product->qty, CART_OVERSEAS_PRICE_TOTAL_DECIMALS);
					} else {
					$products_price = round($product->pricingObject->price['values']['lowest_price_total_discounted']*self::$totals['pricing']['values']['osc'], CART_OVERSEAS_PRICE_TOTAL_DECIMALS);
					$product_price = round($products_price/$product->qty, CART_OVERSEAS_PRICE_PER_DECIMALS);
					}
				} else {
					if(defined('CART_OVERSEAS_PRICE_TOTAL_RECALCULATE') && (CART_OVERSEAS_PRICE_TOTAL_RECALCULATE == 1)) {
						$product_price = round($product->pricingObject->price['values']['lowest_price_per_discounted'], CART_OVERSEAS_PRICE_PER_DECIMALS);
						$products_price = round($product_price*$product->qty, CART_OVERSEAS_PRICE_TOTAL_DECIMALS);
					} else {
						$products_price = round($product->pricingObject->price['values']['lowest_price_total_discounted'], CART_OVERSEAS_PRICE_TOTAL_DECIMALS);
						$product_price = round($products_price/$product->qty, CART_OVERSEAS_PRICE_PER_DECIMALS);
					}
				}
				//var_dump($product->pricingObject->price['values']);die();
				$values = array('sbase_price'=>$product_price, 'mbase_price'=>$products_price);
				$html = $values;
				//var_dump($html);
				array_walk($html, 'format_price_array');
				//var_dump($product->pricingObject);die();
				$product->price = array('html'=>$html, 'values'=>$values);
				$precision_subtotal += $products_price;
				$overseas_total += $products_price;
				$overseas_quantity += $product->qty;
				if(($lowest_overseas_products_price==0) || ($products_price<$lowest_overseas_products_price)) {
					$lowest_overseas = key($overseas);
					$lowest_overseas_products_price = $products_price;
				}
		}
		//var_dump(self::$totals['pricing']);die();
		//}
		//var_dump(self::$products);die();
		//tpt_dump(self::$totals['pricing'], true);
		//tpt_logger::dump($vars, $precision_subtotal, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), '$precision_subtotal', __FILE__.' '.__LINE__);
		//tpt_logger::dump($vars, $vars['user'], false, __FILE__, __LINE__);
		self::$totals['products_count'] = count(self::$products);


		self::$totals['pricing']['values']['retail_price'] = $precision_subtotal;
		self::$totals['pricing']['values']['customer_price'] = $precision_subtotal;
		self::$totals['pricing']['values']['lowest_price'] = $precision_subtotal;


		$html = self::$totals['pricing']['values'];
		//array_pop($html);
		//tpt_dump($html, true);
		array_walk_recursive($html, 'format_price_array');
		self::$totals['pricing']['html'] = $html;
	}

	static function getProductsTotals(&$vars, $products=array(), $force_recalculate=false) {
		//tpt_dump('asdasdas', true);
		//var_dump($products);die();
		$totals = array();
		$pricingObjects = array();
		$inhouse = array();
		$overseas = array();
		$stockcustom = array();

		if (!empty($products)) {
			foreach ($products as $product) {
				if (is_a($product, 'amz_customproduct')) {
					//die();
					//$pricingObjects[] = $product->pricingObject;
					//var_dump($product);die();
					if (($product->data['band_style'] != 6) && ($product->data['band_style'] != 7)) {
						$price_modifiers = self::processPriceModifiers($vars, array(), $product);
						//tpt_dump('asdas', true);
						//if(is_null($product->pricingObject->final_total))
						tpt_logger::dump($vars, $product->pricingObject, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), 'preregen', __FILE__ . ' ' . __LINE__);
						$product->pricingObject->regenerate($vars, null, null, null, $price_modifiers, null);


					}
					$product->pricingObject->getPrice($force_recalculate);
					tpt_logger::dump($vars, $product->pricingObject, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), 'postregen', __FILE__ . ' ' . __LINE__);
					//var_dump($product);die();
					if (!$product->pricingObject->pricingType) {
						$overseas[] = $product;
					} else {
						$inhouse[] = $product;

						//if($_SERVER['REMOTE_ADDR'] == '109.160.0.218') {
						//$values = array('sbase_price'=>$product->pricingObject->price['values']['customer_price_per_discounted'], 'mbase_price'=>$product->pricingObject->price['values']['customer_price_total_discounted']);
						//$html = $values;
						//var_dump($html);
						//array_walk($html, 'format_price_array');
						//$product->price = array('html'=>$html, 'values'=>$values);
						//}

					}
				} else {
					if (is_a($product, 'amz_product2')) {
						//die();
						//$pricingObjects[] = $product->pricingObject;
						//var_dump($product);die();
						if (($product->data['style'] != 6) && ($product->data['style'] != 7)) {
							$price_modifiers = self::processPriceModifiers($vars, array(), $product);
							//tpt_dump('asdas', true);
							//if(is_null($product->pricingObject->final_total))
							tpt_logger::dump($vars, $product->pricingObject, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), 'preregen', __FILE__ . ' ' . __LINE__);
							$product->pricingObject->regenerate($vars, null, null, null, $price_modifiers, null);


						}
						$product->pricingObject->getPrice($force_recalculate);
						tpt_logger::dump($vars, $product->pricingObject, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), 'postregen', __FILE__ . ' ' . __LINE__);
						//var_dump($product);die();
						if (!$product->pricingObject->pricingType) {
							$overseas[] = $product;
						} else {
							$inhouse[] = $product;

							//if($_SERVER['REMOTE_ADDR'] == '109.160.0.218') {
							//$values = array('sbase_price'=>$product->pricingObject->price['values']['customer_price_per_discounted'], 'mbase_price'=>$product->pricingObject->price['values']['customer_price_total_discounted']);
							//$html = $values;
							//var_dump($html);
							//array_walk($html, 'format_price_array');
							//$product->price = array('html'=>$html, 'values'=>$values);
							//}

						}
					} else {
						if (is_a($product, 'amz_customStockproduct')) {
							//$cur_result = amz_pricing::getStockProductPricing($vars, array($product->data['sku'], $product->qty));
							//$product->price = $cur_result;
							$stockcustom[] = $product;

							//$flatfee = true;
						}
					}
				}
				$pricingObjects[] = $product;
				//}
			}
		}

		//if($_SERVER['REMOTE_ADDR'] == '109.160.0.218') {
		$precision_subtotal = 0;
		$totals['pricing'] = amz_pricing::getBulkPricing($vars, $pricingObjects);
		if(!empty($stockcustom)) {
				$totals['pricing']['values']['retail_price'] += amz_pricing::getBulkStockCustomPricing($vars, $stockcustom);
				$totals['pricing']['values']['customer_price'] += amz_pricing::getBulkStockCustomPricing($vars, $stockcustom);
				$totals['pricing']['values']['lowest_price'] += amz_pricing::getBulkStockCustomPricing($vars, $stockcustom);
				$precision_subtotal += amz_pricing::getBulkStockCustomPricing($vars, $stockcustom);
				//$totals['pricing']['values']['lowest_price_discounted'] += amz_pricing::getBulkStockCustomPricing($vars, $stockcustom);
		}

		//tpt_dump($inhouse);
		//tpt_dump($overseas, true);
		//if($_SERVER['REMOTE_ADDR'] != '109.160.0.218') {
		tpt_logger::dump($vars, $totals['pricing']['values']['ihc'], debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), '$totals[\'pricing\'][\'values\'][\'ihc\']', __FILE__.' '.__LINE__);
		tpt_logger::dump($vars, $totals['pricing']['values']['osc'], debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), '$totals[\'pricing\'][\'values\'][\'osc\']', __FILE__.' '.__LINE__);
		
		foreach($inhouse as $product) {
				if(is_null($product->pricingObject->final_total) && ($totals['pricing']['values']['ihc'] < 1)) {
					if(defined('CART_INHOUSE_PRICE_TOTAL_RECALCULATE') && (CART_INHOUSE_PRICE_TOTAL_RECALCULATE == 1)) {
						$product_price = round($product->pricingObject->price['values']['lowest_price_per_discounted']*$totals['pricing']['values']['ihc'], CART_INHOUSE_PRICE_PER_DECIMALS);
						$products_price = round($product_price*$product->qty, CART_INHOUSE_PRICE_TOTAL_DECIMALS);
					} else {
						$products_price = round($product->pricingObject->price['values']['lowest_price_total_discounted']*$totals['pricing']['values']['ihc'], CART_INHOUSE_PRICE_TOTAL_DECIMALS);
						$product_price = round($products_price/$product->qty, CART_INHOUSE_PRICE_PER_DECIMALS);
					}
				} else {
					if(defined('CART_INHOUSE_PRICE_TOTAL_RECALCULATE') && (CART_INHOUSE_PRICE_TOTAL_RECALCULATE == 1)) {
						$product_price = round($product->pricingObject->price['values']['lowest_price_per_discounted'], CART_INHOUSE_PRICE_PER_DECIMALS);
						$products_price = round($product_price*$product->qty, CART_INHOUSE_PRICE_TOTAL_DECIMALS);
					} else {
						$products_price = round($product->pricingObject->price['values']['lowest_price_total_discounted'], CART_INHOUSE_PRICE_TOTAL_DECIMALS);
						$product_price = round($products_price/$product->qty, CART_INHOUSE_PRICE_PER_DECIMALS);
					}
				}
				//tpt_dump($product_price);
				//tpt_dump($products_price, true);
				//var_dump($product->pricingObject->price['values']);die();
				$values = array('sbase_price'=>$product_price, 'mbase_price'=>$products_price);
				$html = $values;
				//var_dump($html);
				array_walk($html, 'format_price_array');
				//var_dump($product->pricingObject);die();
				$product->price = array('html'=>$html, 'values'=>$values);
				$precision_subtotal += $products_price;
		}

		foreach($overseas as $product) {
				if(is_null($product->pricingObject->final_total) && ($totals['pricing']['values']['osc'] < 1)) {
					if(defined('CART_OVERSEAS_PRICE_TOTAL_RECALCULATE') && (CART_OVERSEAS_PRICE_TOTAL_RECALCULATE == 1)) {
						$product_price = round($product->pricingObject->price['values']['lowest_price_per_discounted']*$totals['pricing']['values']['osc'], CART_OVERSEAS_PRICE_PER_DECIMALS);
						$products_price = round($product_price*$product->qty, CART_OVERSEAS_PRICE_TOTAL_DECIMALS);
					} else {
						$products_price = round($product->pricingObject->price['values']['lowest_price_total_discounted']*$totals['pricing']['values']['osc'], CART_OVERSEAS_PRICE_TOTAL_DECIMALS);
						$product_price = round($products_price/$product->qty, CART_OVERSEAS_PRICE_PER_DECIMALS);
					}
				} else {
					if(defined('CART_OVERSEAS_PRICE_TOTAL_RECALCULATE') && (CART_OVERSEAS_PRICE_TOTAL_RECALCULATE == 1)) {
						$product_price = round($product->pricingObject->price['values']['lowest_price_per_discounted'], CART_OVERSEAS_PRICE_PER_DECIMALS);
						$products_price = round($product_price*$product->qty, CART_OVERSEAS_PRICE_TOTAL_DECIMALS);
					} else {
						$products_price = round($product->pricingObject->price['values']['lowest_price_total_discounted'], CART_OVERSEAS_PRICE_TOTAL_DECIMALS);
						if ($products_price > 0 && $product->qty > 0) {
							$product_price = round($products_price / $product->qty, CART_OVERSEAS_PRICE_PER_DECIMALS);
						} else {
							$product_price = round(0, CART_OVERSEAS_PRICE_PER_DECIMALS);
						}

					}
				}
				//tpt_dump(self::$totals['pricing']['values']['osc']);
				//tpt_dump($product->pricingObject->price['values']['lowest_price_per_discounted']);
				//tpt_dump(CART_OVERSEAS_PRICE_TOTAL_DECIMALS);
				//tpt_dump(CART_OVERSEAS_PRICE_PER_DECIMALS);
				//tpt_dump($product_price);
				//tpt_dump($products_price, true);
				//var_dump($product->pricingObject->price['values']);die();
				$values = array('sbase_price'=>$product_price, 'mbase_price'=>$products_price);
				$html = $values;
				//var_dump($html);
				array_walk($html, 'format_price_array');
				//var_dump($product->pricingObject);die();
				$product->price = array('html'=>$html, 'values'=>$values);
				$precision_subtotal += $products_price;
		}
		

		//var_dump(self::$totals['pricing']);die();
		//}
		//var_dump($products);die();
		//tpt_dump($totals['pricing'], true);
		$totals['products_count'] = count($products);

		$totals['pricing']['values']['retail_price'] = $precision_subtotal;
		$totals['pricing']['values']['customer_price'] = $precision_subtotal;
		$totals['pricing']['values']['lowest_price'] = $precision_subtotal;


		$html = $totals['pricing']['values'];
		array_pop($html);
		array_walk($html, 'format_price_array');
		$totals['pricing'] = array(
				'html'=>$html,
				'values'=>$totals['pricing']['values']
		);

		return $totals;
	}

	static function getPricingTotals(&$vars, $discount=null) {
		$pricingObjects = array();
		$overseas = array();
		$inhouse = array();
		$stockcustom = array();

		foreach(self::$pricing_products as $product) {
				if(is_a($product, 'amz_customproduct')) {
					//$pricingObjects[] = $product->pricingObject;
					if(($product->data['band_style'] != 6) && ($product->data['band_style'] != 7)) {
						$price_modifiers = self::processPriceModifiers($vars, array(), $product);

						$product->pricingObject->regenerate($vars, null, null, null, $price_modifiers, null);
					}
					$product->pricingObject->getPrice();
					if(!$product->pricingObject->pricingType) {
						$overseas[] = $product;
					} else {
						$inhouse[] = $product;
						/*
						if($_SERVER['REMOTE_ADDR'] != '109.160.0.218') {
						$values = array('sbase_price'=>$product->pricingObject->price['values']['customer_price_per_discounted'], 'mbase_price'=>$product->pricingObject->price['values']['customer_price_total_discounted']);
						$html = $values;
						//var_dump($html);
						array_walk($html, 'format_price_array');
						$product->price = array('html'=>$html, 'values'=>$values);
						}
						*/
					}
				} else if(is_a($product, 'amz_product2')) {
					//$pricingObjects[] = $product->pricingObject;
					if(($product->data['style'] != 6) && ($product->data['style'] != 7)) {
						$price_modifiers = self::processPriceModifiers($vars, array(), $product);

						$product->pricingObject->regenerate($vars, null, null, null, $price_modifiers, null);
					}
					$product->pricingObject->getPrice();
					if(!$product->pricingObject->pricingType) {
						$overseas[] = $product;
					} else {
						$inhouse[] = $product;
						/*
						if($_SERVER['REMOTE_ADDR'] != '109.160.0.218') {
						$values = array('sbase_price'=>$product->pricingObject->price['values']['customer_price_per_discounted'], 'mbase_price'=>$product->pricingObject->price['values']['customer_price_total_discounted']);
						$html = $values;
						//var_dump($html);
						array_walk($html, 'format_price_array');
						$product->price = array('html'=>$html, 'values'=>$values);
						}
						*/
					}
				} else if (is_a($product, 'amz_customStockproduct')) {
					//$cur_result = amz_pricing::getStockProductPricing($vars, array($product->data['sku'], $product->qty));
					//$product->price = $cur_result;
					$stockcustom[] = $product;

					//$flatfee = true;
				}
				$pricingObjects[] = $product;
				//}
		}

		//var_dump($pricingObjects);die();
		if(is_numeric($discount)) {
				$discount = floatval($discount);
		} else {
				$discount = GLOBAL_CUSTOMPRODUCT_PRICEOFF_PERCENT;
		}
		$pricing_totals = amz_pricing::getBulkPricing($vars, $pricingObjects, $discount);
		$precision_subtotal = 0;
		//tpt_dump($pricing_totals, true);
		if(!empty($stockcustom)) {
				$pricing_totals['values']['retail_price'] += amz_pricing::getBulkStockCustomPricing($vars, $stockcustom);
				$pricing_totals['values']['customer_price'] += amz_pricing::getBulkStockCustomPricing($vars, $stockcustom);
				$pricing_totals['values']['lowest_price'] += amz_pricing::getBulkStockCustomPricing($vars, $stockcustom);
				$precision_subtotal += amz_pricing::getBulkStockCustomPricing($vars, $stockcustom);
				//$pricing_totals['values']['lowest_price_discounted'] += amz_pricing::getBulkStockCustomPricing($vars, $stockcustom);
		}
		//tpt_dump($pricing_totals, true);

		//if($_SERVER['REMOTE_ADDR'] == '109.160.0.218') {
		
		
		foreach($inhouse as $product) {
				if(is_null($product->pricingObject->final_total) && ($pricing_totals['values']['ihc'] < 1)) {
					if(defined('CART_INHOUSE_PRICE_TOTAL_RECALCULATE') && (CART_INHOUSE_PRICE_TOTAL_RECALCULATE == 1)) {
						$product_price = round($product->pricingObject->price['values']['lowest_price_per_discounted']*$pricing_totals['values']['ihc'], CART_INHOUSE_PRICE_PER_DECIMALS);
						$products_price = round($product_price*$product->qty, CART_INHOUSE_PRICE_TOTAL_DECIMALS);
					} else {
						$products_price = round($product->pricingObject->price['values']['lowest_price_total_discounted']*$pricing_totals['values']['ihc'], CART_INHOUSE_PRICE_TOTAL_DECIMALS);
						$product_price = round($products_price/$product->qty, CART_INHOUSE_PRICE_PER_DECIMALS);
					}
				} else {
					if(defined('CART_INHOUSE_PRICE_TOTAL_RECALCULATE') && (CART_INHOUSE_PRICE_TOTAL_RECALCULATE == 1)) {
						$product_price = round($product->pricingObject->price['values']['lowest_price_per_discounted'], CART_INHOUSE_PRICE_PER_DECIMALS);
						$products_price = round($product_price*$product->qty, CART_INHOUSE_PRICE_TOTAL_DECIMALS);
					} else {
						$products_price = round($product->pricingObject->price['values']['lowest_price_total_discounted'], CART_INHOUSE_PRICE_TOTAL_DECIMALS);
						$product_price = round($products_price/$product->qty, CART_INHOUSE_PRICE_PER_DECIMALS);
					}
				}
				//var_dump($product->pricingObject->price['values']);die();
				$values = array('sbase_price'=>$product_price, 'mbase_price'=>$products_price);
				$html = $values;
				//var_dump($html);
				array_walk($html, 'format_price_array');
				//var_dump($product->pricingObject);die();
				$product->price = array('html'=>$html, 'values'=>$values);
				$precision_subtotal += $products_price;
		}

		foreach($overseas as $product) {
				if(is_null($product->pricingObject->final_total) && ($pricing_totals['values']['osc'] < 1)) {
					if(defined('CART_OVERSEAS_PRICE_TOTAL_RECALCULATE') && (CART_OVERSEAS_PRICE_TOTAL_RECALCULATE == 1)) {
						$product_price = round($product->pricingObject->price['values']['lowest_price_per_discounted']*$pricing_totals['values']['osc'], CART_OVERSEAS_PRICE_PER_DECIMALS);
						$products_price = round($product_price*$product->qty, CART_OVERSEAS_PRICE_TOTAL_DECIMALS);
					} else {
						$products_price = round($product->pricingObject->price['values']['lowest_price_total_discounted']*$pricing_totals['values']['osc'], CART_OVERSEAS_PRICE_TOTAL_DECIMALS);
						$product_price = round($products_price/$product->qty, CART_OVERSEAS_PRICE_PER_DECIMALS);
					}
				} else {
					if(defined('CART_OVERSEAS_PRICE_TOTAL_RECALCULATE') && (CART_OVERSEAS_PRICE_TOTAL_RECALCULATE == 1)) {
						$product_price = round($product->pricingObject->price['values']['lowest_price_per_discounted'], CART_OVERSEAS_PRICE_PER_DECIMALS);
						$products_price = round($product_price*$product->qty, CART_OVERSEAS_PRICE_TOTAL_DECIMALS);
					} else {
						$products_price = round($product->pricingObject->price['values']['lowest_price_total_discounted'], CART_OVERSEAS_PRICE_TOTAL_DECIMALS);
						$product_price = round($products_price/$product->qty, CART_OVERSEAS_PRICE_PER_DECIMALS);
					}
				}
				$values = array('sbase_price'=>$product_price, 'mbase_price'=>$products_price);
				$html = $values;
				//var_dump($html);
				//var_dump($product->pricingObject);die();
				array_walk($html, 'format_price_array');
				$product->price = array('html'=>$html, 'values'=>$values);
				$precision_subtotal += $products_price;
		}

		$pricing_totals['values']['retail_price'] = $precision_subtotal;
		$pricing_totals['values']['customer_price'] = $precision_subtotal;
		$pricing_totals['values']['lowest_price'] = $precision_subtotal;

		$html = $pricing_totals['values'];
		array_pop($html);
		array_walk($html, 'format_price_array');
		$pricing_totals = array(
				'html'=>$html,
				'values'=>$pricing_totals['values']
		);

		return $pricing_totals;
	}

	static function addToCartFormStock(&$vars, $productTypeId=0, $buttonClasses='') {

		$vars['environment']['continue_shopping_url'] = REQUEST_URL;

		$html = '';

		$productid = intval($productTypeId, 10);
		$sptype = self::$stockProductsTypesData[$productid];

		if(empty($sptype)) {
				$html = 'Product not found!';
				return $html;
		}

		$qty_control = '';
        if(!isset($_POST['qty'])) { $_POST['qty'] = ''; }
		if(!empty($sptype['qty_control'])) {
				$qty_control = tpt_html::createTextinput($vars, 'qty', intval($_POST['qty'], 10), ' autocomplete="off" oninput="atc_button_toggle(this);" onpropertychange="atc_button_toggle(this);"');
		}


		$bandcolor_control = '';
		if(!empty($sptype['bandcolor_control'])) {
				$sVal = 0;
				$values = array();
				$title = 'Select band color';

				if(!empty($sptype['colors'])) {
					$color_definitions = explode('|', $sptype['colors']);
					$cdefs = array();
					foreach($color_definitions as $cdef) {
						$pcdef = explode('^', $cdef);
                        if(!isset($pcdef[2])) { $pcdef[2] = ''; }
						$cdefs[$pcdef[0]] = array('value'=>$pcdef[1], 'label'=>$pcdef[2]);
					}

					$query = 'SELECT DISTINCT(`color`) FROM `tpt_stock_products` WHERE `stock_product_type_id`='.$productid;
					$vars['db']['handler']->query($query, __FILE__);
					$band_colors = $vars['db']['handler']->fetch_assoc_list('band_color', false);
					if(is_array($band_colors)) { $band_colors = array_keys($band_colors); }

					$bs = array();
					foreach ((array)$band_colors as $colorid) {
						if (isset($vars['modules']['handler']->modules['BandColor']->moduleData['id'][$colorid])) {
							$bs[] = $vars['modules']['handler']->modules['BandColor']->moduleData['id'][$colorid];
						}
					}

					$selvals = $bs;

					$i=1;
					foreach($cdefs as $key=>$item) {
						/*
						if(!empty($state)) {
								if($state == $item['id']) {
									$sVal = $key+1;
								}
						}
						*/

						if(count($cdefs) > 1) {
								if($i==1) {
									$values[] = array('default', $title);
									$i=0;
								}
						}

						$values[] = array($key, $item['label']);
					}
				} else {
					$query = 'SELECT DISTINCT(`color`) FROM `tpt_stock_products` WHERE `stock_product_type_id`='.$productid;
					$vars['db']['handler']->query($query, __FILE__);
					$band_colors = $vars['db']['handler']->fetch_assoc_list('band_color', false);
					$band_colors = array_keys($band_colors);

					$bs = array();
					foreach($band_colors as $colorid) {
						$bs[] = $vars['modules']['handler']->modules['BandColor']->moduleData['id'][$colorid];
					}

					$selvals = $bs;


					//var_dump($selvals);die();

					$i=1;
					foreach($selvals as $key=>$item) {
						/*
						if(!empty($state)) {
								if($state == $item['id']) {
									$sVal = $key+1;
								}
						}
						*/

						if(count($selvals) > 1) {
								if($i==1) {
									$values[] = array('default', $title);
									$i=0;
								}
						}

						$values[] = array($item['id'], $item['name']);
					}
				}

				$bandcolor_control = tpt_html::createSelect($vars, 'band_color', $values, $sVal, ' style="background-color: #DDD;" class="width-100prc" title="'.$title.'"');
		}

		$bandstyle_control = '';
		if(!empty($sptype['bandstyle_control'])) {
				$query = 'SELECT DISTINCT(`style`) FROM `tpt_stock_products` WHERE `stock_product_type_id`='.$productid;
				$vars['db']['handler']->query($query, __FILE__);
				$band_styles = $vars['db']['handler']->fetch_assoc_list('band_style', false);
				if(is_array($band_styles)) { $band_styles = array_keys($band_styles); }

				$bs = array();
			foreach ((array)$band_styles as $styleid) {
				if (isset($vars['modules']['handler']->modules['BandStyle']->moduleData['id'][$styleid])) {
					$bs[] = $vars['modules']['handler']->modules['BandStyle']->moduleData['id'][$styleid];
				}
			}

				$selvals = $bs;

				$values = array();
				//var_dump($selvals);die();
				$title = 'Select band style';

				$sVal = 0;
				$i=1;
				foreach($selvals as $key=>$item) {
					/*
					if(!empty($state)) {
						if($state == $item['id']) {
								$sVal = $key+1;
						}
					}
					*/
					if(count($selvals) > 1) {
						if($i==1) {
								$values[] = array('default', $title);
								$i=0;
						}
					}

					$values[] = array($item['id'], $item['name']);
				}

				$bandstyle_control = tpt_html::createSelect($tpt_vars, 'band_style', $values, $sVal, ' style="background-color: #DDD;" class="width-100prc" title="'.$title.'"');
		}

		$bandsize_control = '';
		if(!empty($sptype['bandsize_control'])) {
				$query = 'SELECT DISTINCT(`size`) FROM `tpt_stock_products` WHERE `stock_product_type_id`='.$productid;
				$vars['db']['handler']->query($query, __FILE__);
				$band_sizes = $vars['db']['handler']->fetch_assoc_list('band_size', false);
				if(is_array($band_sizes)) { $band_sizes = array_keys($band_sizes); }

				$bs = array();
			foreach ((array)$band_sizes as $sizeid) {
				if (isset($vars['modules']['handler']->modules['BandSize']->moduleData['id'][$sizeid])) {
					$bs[] = $vars['modules']['handler']->modules['BandSize']->moduleData['id'][$sizeid];
				}
			}

				$selvals = $bs;

				$values = array();
				//var_dump($selvals);die();
				$title = 'Select band size';

				$sVal = 0;
				$i=1;
				foreach($selvals as $key=>$item) {
					/*
					if(!empty($state)) {
						if($state == $item['id']) {
								$sVal = $key+1;
						}
					}
					*/
					if(count($selvals) > 1) {
						if($i==1) {
								$values[] = array('default', $title);
								$i=0;
						}
					}

					$values[] = array($item['id'], $item['label']);
				}

				$bandsize_control = tpt_html::createSelect($tpt_vars, 'band_size', $values, $sVal, ' style="background-color: #DDD;" class="width-100prc" title="'.$title.'"');
		}

		$action_url = $vars['url']['handler']->wrap($vars, '/cartaddproduct');


$html = <<< EOT
<form action="$action_url" method="post">
	$bandcolor_control
	$bandstyle_control
	$bandsize_control
	$qty_control
	<input type="hidden" name="productid" value="$productid" />
	<input type="hidden" name="task" value="cart.add_stock" />
	<input name="submit" type="submit" value="Add To Cart" disabled="disabled" class="$buttonClasses" />
</form>
EOT;

		return $html;
	}

	static function addToCartFormBundle(&$vars, $bundleId=0, $buttonClasses='') {

		$vars['environment']['continue_shopping_url'] = REQUEST_URL;

		$html = '';

		$bundleid = intval($bundleId, 10);
		$bundle = self::$bundlesData[$bundleid];

		$productsTypes = explode(',', $bundle['stock_products_types_ids']);

		$pfields = array();

		foreach($productsTypes as $productid) {

				$sptype = self::$stockProductsTypesData[$productid];

				if(empty($sptype)) {
					continue;
				}
				$pfields[] = '<span class="amz_red font-size-14 font-weight-bold">'.$sptype['label'].'</span>';

				$qty_control = '';

				$bandcolor_control = '';
				if(!empty($sptype['bandcolor_control'])) {
					$sVal = 0;
					$values = array();
					$title = 'Select band color';

					if(!empty($sptype['colors'])) {
						$color_definitions = explode('|', $sptype['colors']);
						$cdefs = array();
						foreach($color_definitions as $cdef) {
								$pcdef = explode('^', $cdef);
                                if(!isset($pcdef[2])) { $pcdef[2] = ''; }
								$cdefs[$pcdef[0]] = array('value'=>$pcdef[1], 'label'=>$pcdef[2]);
						}

						$query = 'SELECT DISTINCT(`color`) FROM `tpt_stock_products` WHERE `stock_product_type_id`='.$productid;
						$vars['db']['handler']->query($query, __FILE__);
						$band_colors = $vars['db']['handler']->fetch_assoc_list('band_color', false);
						$bs = array();
						if(is_array($band_colors) && !empty($band_colors)){
							$band_colors = array_keys($band_colors);
							foreach($band_colors as $colorid) {
								$bs[] = $vars['modules']['handler']->modules['BandColor']->moduleData['id'][$colorid];
							}
						}


						$selvals = $bs;

						$i=1;
						foreach($cdefs as $key=>$item) {
								/*
								if(!empty($state)) {
									if($state == $item['id']) {
										$sVal = $key+1;
									}
								}
								*/

								if(count($cdefs) > 1) {
									if($i==1) {
										$values[] = array('default', $title);
										$i=0;
									}
								}

								$values[] = array($key, $item['label']);
						}
					} else {
						$query = 'SELECT DISTINCT(`color`) FROM `tpt_stock_products` WHERE `stock_product_type_id`='.$productid;
						$vars['db']['handler']->query($query, __FILE__);
						$band_colors = $vars['db']['handler']->fetch_assoc_list('band_color', false);
						$band_colors = array_keys($band_colors);

						$bs = array();
						foreach($band_colors as $colorid) {
								$bs[] = $vars['modules']['handler']->modules['BandColor']->moduleData['id'][$colorid];
						}

						$selvals = $bs;


						//var_dump($selvals);die();

						$i=1;
						foreach($selvals as $key=>$item) {
								/*
								if(!empty($state)) {
									if($state == $item['id']) {
										$sVal = $key+1;
									}
								}
								*/

								if(count($selvals) > 1) {
									if($i==1) {
										$values[] = array('default', $title);
										$i=0;
									}
								}

								$values[] = array($item['id'], $item['name']);
						}
					}

					$pfields[] = tpt_html::createSelect($vars, 'band_color['.$productid.']', $values, $sVal, ' style="background-color: #DDD;" class="width-100prc" title="'.$title.'"');
				}

				$bandstyle_control = '';
				if(!empty($sptype['bandstyle_control'])) {
					$query = 'SELECT DISTINCT(`style`) FROM `tpt_stock_products` WHERE `stock_product_type_id`='.$productid;
					$vars['db']['handler']->query($query, __FILE__);
					$band_styles = $vars['db']['handler']->fetch_assoc_list('band_style', false);
					$bs = array();
					if (is_array($band_styles) && !empty($band_styles)) {
						$band_styles = array_keys($band_styles);
						foreach ($band_styles as $styleid) {
							$bs[] = $vars['modules']['handler']->modules['BandStyle']->moduleData['id'][$styleid];
						}
					}
					$selvals = $bs;

					$values = array();
					//var_dump($selvals);die();
					$title = 'Select band style';

					$sVal = 0;
					$i=1;
					foreach($selvals as $key=>$item) {
						/*
						if(!empty($state)) {
								if($state == $item['id']) {
									$sVal = $key+1;
								}
						}
						*/
						if(count($selvals) > 1) {
								if($i==1) {
									$values[] = array('default', $title);
									$i=0;
								}
						}

						$values[] = array($item['id'], $item['name']);
					}

					$pfields[] = tpt_html::createSelect($tpt_vars, 'band_style['.$productid.']', $values, $sVal, ' style="background-color: #DDD;" class="width-100prc" title="'.$title.'"');
				}

				$bandsize_control = '';
				if(!empty($sptype['bandsize_control'])) {
					$query = 'SELECT DISTINCT(`size`) FROM `tpt_stock_products` WHERE `stock_product_type_id`='.$productid;
					$vars['db']['handler']->query($query, __FILE__);
					$band_sizes = $vars['db']['handler']->fetch_assoc_list('band_size', false);
					if(is_array($band_sizes)) { $band_sizes = array_keys($band_sizes); }

					$bs = array();
					foreach ((array)$band_sizes as $sizeid) {
						if (isset($vars['modules']['handler']->modules['BandSize']->moduleData['id'][$sizeid])) {
							$bs[] = $vars['modules']['handler']->modules['BandSize']->moduleData['id'][$sizeid];
						}
					}

					$selvals = $bs;

					$values = array();
					//var_dump($selvals);die();
					$title = 'Select band size';

					$sVal = 0;
					$i=1;
					foreach($selvals as $key=>$item) {
						/*
						if(!empty($state)) {
								if($state == $item['id']) {
									$sVal = $key+1;
								}
						}
						*/
						if(count($selvals) > 1) {
								if($i==1) {
									$values[] = array('default', $title);
									$i=0;
								}
						}

						$values[] = array($item['id'], $item['label']);
					}

					$pfields[] = tpt_html::createSelect($tpt_vars, 'band_size['.$productid.']', $values, $sVal, ' style="background-color: #DDD;" class="width-100prc" title="'.$title.'"');
				}

				$action_url = $vars['url']['handler']->wrap($vars, '/cartaddproduct');

		}

        if(!isset($_POST['qty'])) { $_POST['qty'] = ''; }
		$pfields[] = tpt_html::createTextinput($vars, 'qty', intval($_POST['qty'], 10), ' autocomplete="off" oninput="atc_button_toggle(this);" onpropertychange="atc_button_toggle(this);"');

		$pfields = implode('<br />', $pfields);

$html = <<< EOT
<form action="$action_url" method="post">
	$pfields
	<input type="hidden" name="bundleid" value="$bundleid" />
	<input type="hidden" name="task" value="cart.add_bundle" />
	<input name="submit" type="submit" value="Add To Cart" disabled="disabled" class="$buttonClasses" />
</form>
EOT;

		return $html;
	}

	static function addToCartFormCustom(&$vars, $buttonClasses='', $grabImage=false, $validationJSFunc = '') {

		$vars['environment']['continue_shopping_url'] = REQUEST_URL;

		$html = '';

		$imginput = '';
		$grabimage = '';
		if($grabImage) {
				$imginput = '<input id="primg" type="hidden" name="primg" value="" />';
				$grabimage = 'document.getElementById(\'primg\').value = document.getElementById(\'grab_product_image\').getElementsByTagName(\'IMG\')[0].src;';
		}

		$action_url = $vars['url']['handler']->wrap($vars, '/cartaddproduct');


		$onclick = $grabimage.' this.form.action=\''.$action_url.'\'; this.form.submit();';
		if(!empty($validationJSFunc)) {
				$onclick = 'if('.$validationJSFunc.'()){'.$grabimage.' this.form.action=\''.$action_url.'\'; this.form.submit();}';
		}

$html = <<< EOT
	$imginput
	<input type="hidden" name="task" value="cart.add_custom" />
	<input type="button" value=" " onclick="$onclick" class="$buttonClasses" />
EOT;

		return $html;
	}


	static function clear(&$vars) {
		self::$products = array();
	}
}


abstract class amz_product {
	public $price = array();
	public $data = array();
	public $qty;
	
	abstract function getCachedProductImageUrl(&$vars, $x=0, $y=0);
	abstract function getCachedProductThumbUrl(&$vars, $x=0, $y=0);
	abstract function getCachedImageName(&$vars, $x=0, $y=0);
	abstract function getSku(&$vars);
	abstract function getDesignUrlQuery(&$vars);
	abstract function getDesignUrlQuery2(&$vars);
	abstract function getDesignUrlQuery3(&$vars);
	abstract function getPricingDataArray(&$vars);
	abstract function getDesignNotesString(&$vars);
	abstract function getCartView(&$vars, $index=0);
}




class amz_product2 extends amz_product {
	public $price = array();
	public $data = array();

	function __construct(&$vars, $pricingObject, $data = array()) {
		//if(!is_a($pricingObject, 'amz_pricing')){
		//	return false;
		//}


		$this->qty = $data['qty'];
		$this->pricingObject = $pricingObject;
		$this->data = $data;

		//if($_SERVER['REMOTE_ADDR'] == '109.160.0.218') {
		$this->designidstringIH = $this->getDesignIdStringIH($vars);
		$this->designidstringOS = $this->getDesignIdStringOS($vars);
		//}

		$this->validate($vars);
	}

	function validate(&$vars) {
		$valid_data = false;
		$valid_qty = false;
		$valid_color = false;

		$data_module = getModule($vars, 'BandData');
		$types_module = getModule($vars, 'BandType');
		$wclass_module = getModule($vars, 'WritableClass');
		$styles_module = getModule($vars, 'BandStyle');
		$colors_module = getModule($vars, 'BandColor');
		$sizes_module = getModule($vars, 'BandSize');
		$pfields_module = getModule($vars, 'CustomProductField');
		$layers_module = getModule($vars, 'BandPreviewLayer');
		$msgs_module = getModule($vars, 'BandMessage');
		$builders_module = getModule($vars, 'Builder');


		$bdrow = false;
		if(!empty($data_module->typeStyle[$this->data['type']]) && !empty($data_module->typeStyle[$this->data['type']][$this->data['style']])) {
			$this->valid_data = true;
			$bdrow = $data_module->typeStyle[$this->data['type']][$this->data['style']];

			if($this->data['qty'] >= $bdrow['minimum_quantity']) {
				$this->valid_qty = true;
			}

			$cprops = $colors_module->getColorProps($vars, $this->data['color']);
			//tpt_dump($cprops, true);
			if(empty($cprops['invalid_color'])) {
				if(empty($bdrow['pricing_type']) || (empty($cprops['custom_color']) && in_array($bdrow['id'], explode(',', $cprops['colordata']['preset']['available_types_ids2'])))) {
					$this->valid_color = true;
				}
			}
		}
		//tpt_dump($pgType);
		//tpt_dump($pgStyle);
		//tpt_dump($bdrow, true);

	}

	function getCachedProductImageUrl(&$vars, $x=0, $y=0) {
		$cpf_module = getModule($vars, 'CustomProductField');

        $url = '';

		$query = array_intersect_key($this->data, $cpf_module->preview_params);

		$filename = sha1(http_build_query($query)).'.png';
		//tpt_dump($flatimg, true);
		//tpt_dump($filename, true);
		$cfile = TPT_PREVIEW_CACHE_FLAT_DIR . DIRECTORY_SEPARATOR . $filename;
		if (is_file($cfile)) {
			//tpt_dump('asd');
			//tpt_dump($cfile);
			//header('Content-type: image/png');
			$url = TPT_PREVIEW_CACHE_FLAT_URL . '/' . $filename;
		} else {
			//tpt_dump('asdf');
			//tpt_dump($flatimg);
			//tpt_dump($cfile);
			//tpt_dump($vars['url']['handler']->wrap($vars, '/generate-preview').'?type=flat&'.$this->getDesignUrlQuery($vars).'&pg_x='.$x.'&pg_y='.$y);
			//$url = $vars['url']['handler']->wrap($vars, '/preview') . '?l[0][layertype]=flat&' . $this->getDesignUrlQuery($vars) . '&pg_x=' . $x . '&pg_y=' . $y;
			$url = array('l'=>array($this->data));
			$url = $vars['url']['handler']->wrap($vars, '/g-preview') . '?'.urlencode('l[0][layertype]').'=flat&' . http_build_query($url);
		}

        //tpt_dump($url, true);
        return $url;
	}
	function getCachedProductImage(&$vars, $cache=0) {
		$cpf_module = getModule($vars, 'CustomProductField');

		$image = '';

		$query = array_intersect_key($this->data, $cpf_module->preview_params);
		$filename = sha1(http_build_query($query)).'.png';
		$cfile = TPT_PREVIEW_CACHE_FLAT_DIR.DIRECTORY_SEPARATOR.$filename;
		if(is_file($cfile)) {
			$image = file_get_contents($cfile);
		} else {
			$query = $this->data;
			$query['layertype'] = 'flat';
			$query = array('l'=>array($query));

			$image = tpt_PreviewGenerator::createImage($vars, $query);
			if(!empty($cache)) {
				file_put_contents($cfile, $image);
			}
		}

		return $image;
	}
	function getCachedProductThumbUrl(&$vars, $x=0, $y=0) {

	}
	function getCachedImageName(&$vars, $x=0, $y=0) {

	}
	function getSku(&$vars) {
        $style_module = getModule($vars, 'BandStyle');
        $types_module = getModule($vars, 'BandType');
        $sizes_module = getModule($vars, 'BandSize');
        $color_module = getModule($vars, 'BandColor');
        $data_module = getModule($vars, 'BandData');

        $asku = array();

        $asku[0] = 'OS';
        if(!empty($data_module->typeStyle[$this->data['type']][$this->data['style']]['pricing_type'])) {
            $asku[0] = 'IH';
        }

        if(!empty($data_module->typeStyle[$this->data['type']][$this->data['style']]['writable'])) {
            if($data_module->typeStyle[$this->data['type']][$this->data['style']]['writable_class'] == 5) {
                $asku[0] = 'IH';
            } else {
                $asku[0] = 'OS';
            }

        }

        $asku[1] = $style_module->moduleData['id'][$this->data['style']]['sku_comp'];
        $asku[2] = $types_module->moduleData['id'][$this->data['type']]['sku_comp'];
        if(!empty($this->pricingObject->options['key_chain'])) {
            $asku[2] .= '+CHAIN';
        }
        $asku[3] = $sizes_module->moduleData['id'][$this->data['size']]['sku_comp'];

        $asku[4] = $color_module->getSkuComponent($vars, $this->data['color']);
        $cp = $color_module->getColorProps($vars, $this->data['color']);
        //var_dump($cp);die();
        if($cp['dual_layer']) {
            $mid = $color_module->getDualLayerMessageId($vars, $this->data['color']);
            $asku[4] .= '+'.$color_module->getSkuComponent($vars, $mid, true);
        } else if($cp['led']) {
			//tpt_dump($cp);
			$asku[4] .= '+'.$color_module->getSkuComponent($vars, $cp['colordata']['led_uid']);
        } else if($style_module->moduleData['id'][$this->data['style']]['message_color']) {
            $asku[4] .= '+'.$color_module->getSkuComponent($vars, $this->data['message_color'], true);
        }
        //var_dump($this->data);
        //var_dump($asku);die();
        //tpt_dump($asku, true);

        return implode('-', $asku);
	}
	function getDesignUrlQuery(&$vars) {

	}
	function getDesignUrlQuery2(&$vars) {

	}
	function getDesignUrlQuery3(&$vars) {
		return http_build_query($this->data);
	}
	function getPricingDataArray(&$vars) {
		$pricingdata = array(
			'Shipping'=>0,
			'Discount'=>0,
			'Tax'=>0,
			'Total_Price'=>!empty($this->price['values']['mbase_price'])?floatval($this->price['values']['mbase_price']):0,
			'cost'=>0,
			'Total_Cost'=>0
		);

		return $pricingdata;
	}

	function getDesignIdStringIH(&$vars) {
		return base64_encode(http_build_query($this->data));
	}

	function populateDesignIdArrayIH(&$vars, &$array) {

		//global $tpt_vars;
		//if(empty($vars))
		//	$vars = $tpt_vars;

		if(!is_array($array))
			$array = array();

		//if(empty($this->designidstringIH)) {
		$this->designidstringIH = $this->getDesignIdStringIH($vars);
		//}

		//$styles_module = $vars['modules']['handler']->modules['BandStyle'];

		if(!isset($array[$this->designidstringIH])) {
			$array[$this->designidstringIH] = $this->data['qty'];
		} else {
			$array[$this->designidstringIH] += $this->data['qty'];
		}

	}


	function getDesignIdStringOS(&$vars) {
		return base64_encode(http_build_query($this->data));
	}

	function populateDesignIdArrayOS(&$vars, &$array) {

		//global $tpt_vars;
		//if(empty($vars))
		//	$vars = $tpt_vars;

		if(!is_array($array))
			$array = array();

		//if(empty($this->designidstringOS)) {
		$this->designidstringOS = $this->getDesignIdStringOS($vars);
		//}

		//tpt_dump($this->designidstringOS);

		$types_module = getModule($vars, 'BandType');
		$styles_module = getModule($vars, 'BandStyle');

		if(empty($array[$this->designidstringOS]))
			$array[$this->designidstringOS] = array();

		//var_dump($vars['modules']);die();
		//var_dump($vars);die();
		//var_dump($styles_module->moduleData['id'][$this->data['band_style']]['mold']);die();
		if($styles_module->moduleData['id'][$this->data['style']]['screen'] || $types_module->moduleData['id'][$this->data['type']]['screens']) {
			/*
			if(!is_array($array[$this->designidstring][$this->data['band_size']])) {
				$array[$this->designidstring][$this->data['band_size']] = array();
			}
			*/
			if(empty($array[$this->designidstringOS]['screens'])) {
				$array[$this->designidstringOS]['screens'] = array();
			}
			if(empty($array[$this->designidstringOS]['screens'][$this->data['message_color']])) {
				$array[$this->designidstringOS]['screens'][$this->data['message_color']] = array();
			}
			/*
			if(!is_array($array[$this->designidstring][$this->data['message_color']][$this->data['band_size']])) {
				$array[$this->designidstring][$this->data['message_color']][$this->data['band_size']] = array();
			}
			*/
			$array[$this->designidstringOS]['screens'][$this->data['message_color']][] = $this;
		}

		if($styles_module->moduleData['id'][$this->data['style']]['mold'] || $types_module->moduleData['id'][$this->data['type']]['molds']) {
			if(empty($array[$this->designidstringOS]['molds'])) {
				$array[$this->designidstringOS]['molds'] = array();
			}
			/*
			if(!is_array($array[$this->designidstring]['nocolor'][$this->data['band_size']])) {
				$array[$this->designidstring]['nocolor'][$this->data['band_size']] = array();
			}
			*/
			$array[$this->designidstringOS]['molds'][] = $this;
		}

	}


	function getDesignNotesString(&$vars) {
		$amzg_extras = '';
		if (!empty($this->pricingObject->options['glitter'])) {
			$amzg_extras .= '+Add Glitter<br/>';
		}
		if (!empty($this->pricingObject->options['uv'])) {
			$amzg_extras .= '+Add UV<br/>';
		}
		if (!empty($this->pricingObject->options['glow'])) {
			$amzg_extras .= '+Add Glow<br/>';
		}
		if (!empty($this->pricingObject->options['glow_ink_fill'])) {
			$amzg_extras .= '+Add Glow-in-the-Dark Message<br/>';
		}
		if (!empty($this->pricingObject->options['indvl_packaging'])) {
			$amzg_extras .= '+Add individual Packing<br/>';
		}
		if (!empty($this->pricingObject->options['key_chain'])) {
			$amzg_extras .= '+Make into keychain<br/>';
		}

		return $amzg_extras;
	}
	function getCartView(&$vars, $index=0) {
		$types_module = getModule($vars, 'BandType');
		$styles_module = getModule($vars, 'BandStyle');
		$styles = $styles_module->moduleData['id'];
		$data_module = getModule($vars, 'BandData');
//$colors_module = getModule($vars, "BandColor");
		$colors_module = getModule($vars, 'BandColor');
		$fonts_module = getModule($vars, 'BandFont');
		$fonts = $fonts_module->moduleData['id'];
		$rushorder_module = getModule($vars, 'RushOrder');
		$builder_module = getModule($vars, 'Builder');
		$sbuilders = $builder_module->moduleData['id'];
        $cpf_module = getModule($vars, 'CustomProductField');
        $cpfs = $cpf_module->moduleData['cart_order'];
        $cpfs_cat = $cpf_module->moduleData['cart_subcategory'];
		$cliparts_module = getModule($vars, 'BandClipart');


		$pdata = $data_module->typeStyle[$this->data['type']][$this->data['style']];


        $updateurl = $vars['config']['ajaxurl'] . '/cartupdateproduct';

		$w1 = '406px';
		$w2 = '84px';
		$w3 = '84px';
		$w4 = '98px';
		$w5 = '82px';

		$product_html = '';
//        $labels_width = '114px';
		$labels_width = '80px';


                $sizeHeaderHtml = '<div style="color: #909090; font-family: TODAYSHOP-BOLD,arial; width:'.$w3.'" class="font-size-12 height-35 line-height-35 size-header-display">Size</div>';
                $quantityHeaderHtml = '<div style="color: #909090; font-family: TODAYSHOP-BOLD,arial; width:'.$w4.'" class="font-size-12 height-35 line-height-35 size-header-display">Quantity</div>';
                $subtotalHeaderHtml = '<div style="color: #909090; font-family: TODAYSHOP-BOLD,arial; width:'.$w5.'" class="font-size-12 height-35 line-height-35 size-header-display">Subtotal</div>';

                $subtotal = $subtotalHeaderHtml;
		$subtotal .= !empty($this->price['html']['mbase_price'])?$this->price['html']['mbase_price']:format_price(0);
		//tpt_dump($subtotal);


        $banddesignurl = htmlspecialchars($this->getCachedProductImageUrl($vars));
        $productDesign = <<< EOT
<div class="position-relative padding-left-5 padding-right-5 padding-top-0 padding-bottom-5 text-align-center z-index-3">
    <img src="$banddesignurl" style="border: 1px solid #5B3824;" class="resize" />
</div>
EOT;

        $bandColor = 'Clear';
        if (!empty($this->data['color'])) {
            //var_dump($product->data['band_color']);die();
            $bandColor = explode(':', $this->data['color']);
            //tpt_dump($product->data['band_color'],true);
            //	$props = getModule($tpt_vars, "BandColor")->getColorProps($tpt_vars, $product->data['band_color']);
            $props = getModule($vars, 'BandColor')->getColorProps($vars, $this->data['color']);

            $tableId = $bandColor[0];
            //tpt_dump(getModule($tpt_vars, "BandColor")->all_colors[$tableId],true);
            //var_dump(getModule($tpt_vars, "BandColor")->all_colors[$tableId]);
            //	if (!empty(getModule($tpt_vars, "BandColor")->all_colors[$tableId])) {
            if (!empty(getModule($vars, 'BandColor')->all_colors[$tableId])) {
                $colorId = $bandColor[1];
                $colorCategory = $props['colorcategory'];
                //$colorId = '';

                if (($tableId == 0) || ($tableId == 1) || ($tableId == 2)) {
                    //	$colorId = getModule($tpt_vars, "BandColor")->getCustomColorId($tpt_vars, $product->data['band_color']);
                    //tpt_dump($product->data['band_color'], true);
                    $colorId = getModule($vars, 'BandColor')->getCustomColorId($vars, $this->data['color']);
                } else {
                    //var_dump(getModule($tpt_vars, "BandColor")->all_colors[$tableId]);die();
                    //var_dump($tableId);die();
                    //tpt_dump(getModule($tpt_vars, "BandColor")->all_colors[$tableId],true);
                    //tpt_dump($colorId,true);
                    //	$colorId = getModule($tpt_vars, "BandColor")->all_colors[$tableId][$colorId]['label'];
                    $colorId = getModule($vars, 'BandColor')->all_colors[$tableId][$colorId]['label'];
                }

                //var_dump($colorId);

                if (strtolower(strip_tags($colorId)) == 'clear') {
                    $colorCategory = 'Solid';
                }

                $bandColor = '<span class="font-weight-bold amz_brown">' . $colorCategory . ':</span> ' . $colorId;
            } else {
                $bandColor = 'Clear';
            }
        }

        $bandSize = getModule($vars, "BandSize")->moduleData['id'][$this->data['size']]['label'];
        $bandSizeForm = $bandSize;

        $s_selected = '';
        $m_selected = '';
        $l_selected = '';
        $xl_selected = '';
        switch ($bandSize) {
            case 'Small / Child 7.0"':
                $s_selected = ' selected="selected" ';
                break;
            case 'Medium / Youth 7.5"':
                $m_selected = ' selected="selected" ';
                break;
            case 'Large / Adult 8.0"':
                $l_selected = ' selected="selected" ';
                break;
            case 'Extra Large / XL 8.5"':
                $xl_selected = ' selected="selected" ';
                break;
            case 'Extra Large / 50 minimum 8.5"':
                $xl_selected = ' selected="selected" ';
                break;
            case 'Small / Child 2.4"':
                $s_selected = ' selected="selected" ';
                break;
            case 'Medium / Youth 2.6"':
                $m_selected = ' selected="selected" ';
                break;
            case 'Large / Adult 2.8"':
                $l_selected = ' selected="selected" ';
                break;
        }

        $pos = strrpos($bandSize, "2.");
        if ($pos === false) {
            $is_ring = false;
        } else
            $is_ring = true;

        if ($bandSize == 'Universal') {
            $sizeSelect = '<select name="product_size" class="width-90prc padding-4 border-radius-12" style="background-color: white;  border: 1px solid #CCC;  outline: 0;">
                    <option value="6">Universal</option>
                </select>';
        } else if ($is_ring) {
            $sizeSelect = '<select name="product_size" class="width-90prc padding-2 border-radius-12" style="background-color: white;  border: 1px solid #CCC;  outline: 0;">
                    <option value="8" ' . $s_selected . '>Small / Child 2.4"</option>
                    <option value="9" ' . $m_selected . '>Medium / Youth 2.6"</option>
                    <option value="10" ' . $l_selected . '>Large / Adult 2.8"</option>
                </select>';
        } else if (in_array($this->data['style'], array(6, 7))) {
            $sizeSelect = '<select name="product_size" class="width-90prc padding-2 border-radius-12" style="background-color: white;  border: 1px solid #CCC;  outline: 0;">
                    <option value="2" ' . $s_selected . '>Small / Child 7.0"</option>
                    <option value="3" ' . $m_selected . '>Medium / Youth 7.5"</option>
                    <option value="4" ' . $l_selected . '>Large / Adult 8.0"</option>
                </select>';
        } else {
            $sizeSelect = '<select name="product_size" class="width-90prc padding-2 border-radius-12" style="background-color: white;  border: 1px solid #CCC;  outline: 0;">
                    <option value="2" ' . $s_selected . '>Small / Child 7.0"</option>
                    <option value="3" ' . $m_selected . '>Medium / Youth 7.5"</option>
                    <option value="4" ' . $l_selected . '>Large / Adult 8.0"</option>
                    <option value="5" ' . $xl_selected . '>Extra Large / XL 8.5"</option>
                </select>';
        }

        $updbtnurl = TPT_IMAGES_URL . '/buttons/update-btn.png';
        $updateurl = $vars['url']['handler']->wrap($vars, '/cart_updateproduct');

        $bandSizeForm = <<< EOT
$sizeHeaderHtml                
<form action="$updateurl" method="POST">
$sizeSelect
<input type="hidden" name="productindex" value="$index" />
<input type="hidden" name="task" value="cart.update_size" />
<div class="height-20"></div>
<input type="submit" value="" class="width-74 height-20 plain-input-field hoverCB background-position-CT" style="background-image: url($updbtnurl);" />
</form>
EOT;


        $updbtnurl = TPT_IMAGES_URL . '/buttons/update-btn.png';
        $updateurl = $vars['url']['handler']->wrap($vars, '/cart_updateproduct');
//        $qtybgurl = TPT_IMAGES_URL . '/buttons/cart-qty-field.png';
        $qtybgurl = TPT_IMAGES_URL . '/cart-elem-spr.png';
        $qty = intval($this->data['qty'], 10);
        $qty = '<div class="display-inline-block width-70 height-16 padding-top-2 padding-bottom-7 padding-left-3 padding-right-2" style="background-position: 0px -185px; background-image: url(' . $qtybgurl . ');">' . tpt_html::createTextinput($tpt_vars, 'qty', $this->data['qty'], ' class="amz_red width-70 line-height-24 plain-input-field text-align-center"') . '</div>';
        $updateform = <<< EOT
$quantityHeaderHtml                
<form action="$updateurl" method="POST">
$qty
<input type="hidden" name="productindex" value="$index" />
<input type="hidden" name="task" value="cart.update_qty" />
<div class="height-20"></div>
<input type="submit" value="" class="width-74 height-20 plain-input-field hoverCB background-position-CT" style="background-image: url($updbtnurl);" />
</form>
EOT;

        $delbtnurl = TPT_IMAGES_URL . '/buttons/delete-btn.png';
        $deleteurl = $vars['url']['handler']->wrap($vars, '/cartdeleteproduct');
        $deleteform = <<< EOT
<form action="$deleteurl" method="POST">
<input type="hidden" name="productindex" value="$index" />
<input type="hidden" name="task" value="cart.delete" />
<div class="height-20"></div>
<input type="submit" value="" class="width-74 height-20 plain-input-field hoverCB background-position-CT" style="background-image: url($delbtnurl);" />
</form>
EOT;




        //$labels = array_keys($this->data);
        //array_unshift($labels, 'ID');
        //$labels = '<div>'.implode('</div><div>', $labels).'</div>';
        //$values = $this->data;
        //tpt_dump($this->getSku($vars), true);
        $sku = str_replace('.', '<span class="display-inline-block"></span>.<span class="display-inline-block"></span>', $this->getSku($vars));
        //array_unshift($values, $sku);
        //$values = '<div>'.implode('</div><div>', $values).'</div>';

        $rows = array();
        $rows[] = <<< EOT
<div  class="font-weight-bold float-left text-align-right padding-right-5 urlabel padding-left-15">ID:</div>
<div id="order-details-id" class="amz_red overflow-hidden text-align-left">$sku</div>
EOT;
		//tpt_dump($labels);
		//tpt_dump($labels);
		//tpt_dump(array_intersect_key($cpfs, $this->data));
		//tpt_dump($cpfs);


        foreach($cpfs as $key=>$cpfss) {
			$section_enabled = 0;
			foreach ($cpfss as $cpf) {
				if (/*isset($this->data[$key]) && */
					empty($cpf['cart_subcategory'])) {
					if ((!empty($cpf['cart_a_display']) || !empty($this->data[$cpf['pname']])) && !empty($cpf['cart_show'])) {
						$value = (isset($this->data[$cpf['pname']]) ? $this->data[$cpf['pname']] : '');
						if (!empty($cpf['cartview_value_module_function'])) {
							$value = call_user_func_array(array(getModule($vars, $cpf['module']), $cpf['cartview_value_module_function']), array($vars, array('pname' => $cpf['pname']), $this->data, array('index' => $index)));
						}
						$lbl = $cpf['label2'];
						if (!empty($this->data[$cpf['pname']]) && !empty($cpf['setvalue_label'])) {
							$lbl = $cpf['setvalue_label'];
						}
						$vcls = '';
						if (!empty($cpf['cart_value_wrapper_class'])) {
							$vcls = $cpf['cart_value_wrapper_class'];
						}
						if (empty($cpf['altlayout_label'])) {
							$rows[] = <<< EOT
<div class="font-weight-bold float-left text-align-right padding-right-5 urlabel width-80">$lbl:</div>
<div class="amz_red overflow-hidden text-align-left $vcls">$value</div>
EOT;
						} else {
							$lbl2 = $cpf['altlayout_label'];
							$rows[] = <<< EOT
<div class="text-align-left padding-right-5">$lbl2</div>
<div class="text-align-left padding-right-5">$lbl</div>
<div class="amz_red text-align-left $vcls">$value</div>
EOT;
						}
					}
				} else {
					if (!empty($this->data[$cpf['pname']])) {
						$cat = $cpf['cart_subcategory'];
						if (empty($section_enabled)) {
							$rows[] = <<< EOT
<div class="text-align-left padding-right-5">
<span class="amz_brown font-weight-bold">$cat</span>
</div>
EOT;
							$section_enabled = 1;
						}

						$lbl = $cpf['label2'];
						if (!empty($this->data[$cpf['pname']]) && !empty($cpf['setvalue_label'])) {
							$lbl = $cpf['setvalue_label'];
						}

						$rows[] = <<< EOT
<div class="text-align-left padding-right-5">
	<span class="amz_red font-weight-normal">$lbl</span>
</div>
EOT;
					}
				}
			}
		}

		$rows[] = '&nbsp;';

		$pdae_qry = $this->getDesignUrlQuery3($tpt_vars);;
		//$pdae_url = BASE_URL.$sbuilders[DEFAULT_BUILDER_ID]['standard_url'].'?'.$pdae_qry;
		$pdae_url = '';
		//$pe_url = $pdae_url.'&product='.$index;
		$pe_url = '';
		$builder_id = !empty($this->data['app']) ? $this->data['app'] : '';
		if (!empty($builder_id) && !empty($sbuilders[$builder_id])) {
			//var_dump($dae_qry);die();
			$builder_id = $this->data['app'];
		} else {
			//var_dump($dae_qry);die();
			$builder_id = $pdata['default_builder'];
			if (!empty($this->data['rush_order'])) {
				$builder_id = RUSHORDER_BUILDER_ID;
			}
		}
		if(empty($builder_id)) {
			$builder_id = DEFAULT_BUILDER_ID;
		}
		$pdae_url = BASE_URL . $sbuilders[$builder_id]['standard_url'] . '?' . $pdae_qry;
		$pe_url = $pdae_url . '&product=' . $index;
		$rows[] = <<< EOT
<div class="font-weight-bold padding-right-5 text-align-left">
	<a class="amz_red font-weight-normal" href="$pdae_url">Duplicate & Edit</a>
</div>
EOT;

		if(isDump() && !empty($_GET['debug_php'])) {
			//$dd = var_export($this->data, true).'<br />---------------------------------<br />'.var_export($this->designidstringIH, true).'<br />---------------------------------<br />'.var_export($this->designidstringOS, true);
			$dd = var_export($this->data, true);
			$rows[] = <<< EOT
<pre>
$dd
</pre>
EOT;
		}

		$product_html = tpt_html::getAlternatingHTML($rows, 'div', array('padding-top-2 padding-bottom-2 clearFix'), array(), array('style=""', 'style="background-color: #f2f2f2;"'));
		/*
		$product_html = <<< EOT
<div>
	<div class="display-inline-block text-align-right">
		$labels
	</div>
	<div class="display-inline-block text-align-left padding-left-10">
		$values
	</div>
</div>
EOT;
        */
		$cells = array(
			array('width' => $w1, 'classes' => 'float-left', 'content' => $product_html),
			array('width' => $w2, 'classes' => 'amz_red float-left text-align-center color-display', 'content' => '<div class="display-inline-block text-align-left padding-left-5 padding-right-5">' . $bandColor . '</div>'),
			array('width' => $w3, 'classes' => 'amz_red float-left text-align-center', 'content' => $bandSizeForm),
			array('width' => $w4, 'classes' => 'amz_red float-left text-align-center border-left-right', 'content' => $updateform . $deleteform),
			array('width' => $w5, 'classes' => 'amz_red float-left text-align-center', 'content' => $subtotal),
		);

//		$delimiterurl = TPT_IMAGES_URL . DIRECTORY_SEPARATOR . 'cart-column-delimiter-760.png';
		$delimiterurl = TPT_IMAGES_URL . DIRECTORY_SEPARATOR . 'cart-elem-spr.png';
		//if(!empty($shortcart)) {
		//    $delimiterurl = TPT_IMAGES_URL.DIRECTORY_SEPARATOR.'cart-column-delimiter-560.png';
		//}
		$cells_html = '<div class="padding-top-5 padding-botttom-5 clearFix position-relative" style="width: 100%;">'; //vj edits
		$cells_html .= $productDesign;
//		$cells_html .= '<div class="position-absolute top-0 bottom-0 left-0 right-0 background-repeat-repeat-y z-index-1" style="background-image: url(' . $delimiterurl . ');"></div>';
		$cells_html .= '<div class="position-absolute top-0 bottom-0 left-0 right-0 z-index-1" style="background-position: 0px -210px; background-image: url(' . $delimiterurl . ');"></div>';
		$cells_html .= '<div class="position-relative clearFix z-index-2">';
		foreach ($cells as $params) {
			$cells_html .= '<div style="width:' . $params['width'] . '; font-family: Arial, Helvetica, sans-serif;" class="min-height-1 font-size-12 ' . $params['classes'] . '">' . $params['content'] . '</div>';
		}
		$cells_html .= '</div>';
		$cells_html .= '</div>';

		return $cells_html;

	}
}



class amz_customproduct extends amz_product {
	public $pricingObject;
	public $designidstring;

	public $valid_data = false;
	public $valid_qty = false;
	public $valid_color = false;
	public $vmsg = '';

	function __construct(&$vars, $pricingObject, $data = array()) {
		if(!is_a($pricingObject, 'amz_pricing')){
				return false;
		}
				

		$this->qty = $pricingObject->qty['lg'];
		$this->pricingObject = $pricingObject;
		$this->data = $data;

		//if($_SERVER['REMOTE_ADDR'] == '109.160.0.218') {
		$this->designidstringIH = $this->getDesignIdStringIH($vars);
		$this->designidstringOS = $this->getDesignIdStringOS($vars);
		//}

		$this->validate($vars);
	}

	function validate(&$vars) {
		$valid_data = false;
		$valid_qty = false;
		$valid_color = false;

		$data_module = getModule($vars, 'BandData');
		$types_module = getModule($vars, 'BandType');
		$wclass_module = getModule($vars, 'WritableClass');
		$styles_module = getModule($vars, 'BandStyle');
		$colors_module = getModule($vars, 'BandColor');
		$sizes_module = getModule($vars, 'BandSize');
		$pfields_module = getModule($vars, 'CustomProductField');
		$layers_module = getModule($vars, 'BandPreviewLayer');
		$msgs_module = getModule($vars, 'BandMessage');
		$builders_module = getModule($vars, 'Builder');

		$inhouse = $this->pricingObject->pricingType;
		$qty = $this->qty;
		$pgType = $this->data['band_type'];
		$pgStyle = $this->data['band_style'];
		$pgFont = $this->data['band_font'];
		//$pgFrontRows = (!empty($pgFrontRows)?$pgFrontRows:1);
		//$pgBackRows = (!empty($pgBackRows)?$pgBackRows:1);
		//$pgTextCont = (!empty($pgTextCont)?intval($pgTextCont, 10):0);
		$pgBandColor = $this->data['band_color'];
		$pgMessageColor = $this->data['message_color'];
		$pgFrontMessage = (!empty($this->data['messages']['front'][0])?$this->data['messages']['front'][0]:'');
		$pgFrontMessage2 = (!empty($this->data['messages']['front'][1])?$this->data['messages']['front'][1]:'');
		$pgBackMessage = (!empty($this->data['messages']['back'][0])?$this->data['messages']['back'][0]:'');
		$pgBackMessage2 = (!empty($this->data['messages']['back'][1])?$this->data['messages']['back'][1]:'');

		$pgClipartFrontLeft = (!empty($this->data['clipart']['front'][0]['left'])?$this->data['clipart']['front'][0]['left']:0);
		$pgClipartFrontRight = (!empty($this->data['clipart']['front'][0]['right'])?$this->data['clipart']['front'][0]['right']:0);
		$pgClipartFrontLeft2 = (!empty($this->data['clipart']['front'][1]['left'])?$this->data['clipart']['front'][1]['left']:0);
		$pgClipartFrontRight2 = (!empty($this->data['clipart']['front'][1]['right'])?$this->data['clipart']['front'][1]['right']:0);
		$pgClipartBackLeft = (!empty($this->data['clipart']['back'][0]['left'])?$this->data['clipart']['back'][0]['left']:0);
		$pgClipartBackRight = (!empty($this->data['clipart']['back'][0]['right'])?$this->data['clipart']['back'][0]['right']:0);
		$pgClipartBackLeft2 = (!empty($this->data['clipart']['back'][1]['left'])?$this->data['clipart']['back'][1]['left']:0);
		$pgClipartBackRight2 = (!empty($this->data['clipart']['back'][1]['right'])?$this->data['clipart']['back'][1]['right']:0);
		$pgCutAway = $this->data['cut_away'];

		
		$bdrow = false;
		if(!empty($data_module->typeStyle[$pgType][$pgStyle])) {
				$this->valid_data = true;
				$bdrow = $data_module->typeStyle[$pgType][$pgStyle];

				if($qty >= $bdrow['minimum_quantity']) {
					$this->valid_qty = true;
				}

				$cprops = $colors_module->getColorProps($vars, $pgBandColor);
				//tpt_dump($cprops, true);
				if(empty($cprops['invalid_color'])) {
					if(!$inhouse || (empty($cprops['custom_color']) && in_array($bdrow['id'], explode(',', $cprops['colordata']['preset']['available_types_ids2'])))) {
						$this->valid_color = true;
					}
				}
		}
		//tpt_dump($pgType);
		//tpt_dump($pgStyle);
		//tpt_dump($bdrow, true);

	}

	function getValue(&$vars, $fldname) {
		return $vars['modules']['handler']->modules['CustomProductField']->getValue($vars, $this, $fldname);
	}

	function getDesignIdStringIH(&$vars) {
		$styles_module = getModule($vars, 'BandStyle');

		/*
		$designidstring = array();
		$designidstring[] = 'type'.'_-'.$this->data['band_type'];
		$designidstring[] = 'style'.'_-'.$this->data['band_style'];
		$designidstring[] = 'msgcolor'.'_-'.$this->data['message_color'];
		$designidstring[] = 'font'.'_-'.$this->data['band_font'];
		//tpt_dump($this->data['messages'], true);
		//die();
		$designidstring[] = 'front'.'_-'.implode('::', $this->data['messages']['front']).(!empty($this->data['clipart']['front'][0])?'_-'.implode('::', $this->data['clipart']['front'][0]):'').(!empty($this->data['clipart_c']['front'][0])?'_-'.implode('::', $this->data['clipart_c']['front'][0]):'').(!empty($this->data['clipart']['front'][1])?'_-'.implode('::', $this->data['clipart']['front'][1]):'').(!empty($this->data['clipart_c']['front'][1])?'_-'.implode('::', $this->data['clipart_c']['front'][1]):'');
		$designidstring[] = 'back'.'_-'.implode('::', $this->data['messages']['back']).(!empty($this->data['clipart']['back'][0])?'_-'.implode('::', $this->data['clipart']['back'][0]):'').(!empty($this->data['clipart_c']['back'][0])?'_-'.implode('::', $this->data['clipart_c']['back'][0]):'').(!empty($this->data['clipart']['back'][1])?'_-'.implode('::', $this->data['clipart']['back'][1]):'').(!empty($this->data['clipart_c']['back'][1])?'_-'.implode('::', $this->data['clipart_c']['back'][1]):'');
		//$designidstring[] = 'customclipart'.'_-'.$this->data['custom_clipart'];

		return base64_encode(implode('-:-', $designidstring));
		*/

		$designidstring = array();
		$designidstring['type'] = $this->data['band_type'];
		//$designidstring['relief'] = (!empty($styles_module->moduleData['id'][$this->data['band_style']]['message_relief'])?$styles_module->moduleData['id'][$this->data['band_style']]['message_relief']:0);
		$designidstring['style'] = $this->data['band_style'];
		$designidstring['message_color'] = (!empty($styles_module->moduleData['id'][$this->data['band_style']]['message_color'])?$this->data['message_color']:'');
		$designidstring['font'] = (!empty($this->data['band_font'])?$this->data['band_font']:0);
		//var_dump($this->data['clipart']);
		//die();
		$designidstring['msg1'] = (!empty($this->data['messages']['front'][0])?$this->data['messages']['front'][0]:'');
		$designidstring['msg2'] = (!empty($this->data['messages']['front'][1])?$this->data['messages']['front'][1]:'');
		$designidstring['msg3'] = (!empty($this->data['messages']['back'][0])?$this->data['messages']['back'][0]:'');
		$designidstring['msg4'] = (!empty($this->data['messages']['back'][1])?$this->data['messages']['back'][1]:'');
		$designidstring['clp1'] = (!empty($this->data['clipart']['front'][0]['left'])?$this->data['clipart']['front'][0]['left']:0);
		$designidstring['clp2'] = (!empty($this->data['clipart']['back'][0]['left'])?$this->data['clipart']['back'][0]['left']:0);
		$designidstring['clp3'] = (!empty($this->data['clipart']['front'][0]['right'])?$this->data['clipart']['front'][0]['right']:0);
		$designidstring['clp4'] = (!empty($this->data['clipart']['back'][0]['right'])?$this->data['clipart']['back'][0]['right']:0);
		$designidstring['clp5'] = (!empty($this->data['clipart']['front'][1]['left'])?$this->data['clipart']['front'][1]['left']:0);
		$designidstring['clp6'] = (!empty($this->data['clipart']['back'][1]['left'])?$this->data['clipart']['back'][1]['left']:0);
		$designidstring['clp7'] = (!empty($this->data['clipart']['front'][1]['right'])?$this->data['clipart']['front'][1]['right']:0);
		$designidstring['clp8'] = (!empty($this->data['clipart']['back'][1]['right'])?$this->data['clipart']['back'][1]['right']:0);
		$designidstring['cclp1'] = (!empty($this->data['clipart_c']['front'][0]['left'])?$this->data['clipart_c']['front'][0]['left']:'');
		$designidstring['cclp2'] = (!empty($this->data['clipart_c']['back'][0]['left'])?$this->data['clipart_c']['back'][0]['left']:'');
		$designidstring['cclp3'] = (!empty($this->data['clipart_c']['front'][0]['right'])?$this->data['clipart_c']['front'][0]['right']:'');
		$designidstring['cclp4'] = (!empty($this->data['clipart_c']['back'][0]['right'])?$this->data['clipart_c']['back'][0]['right']:'');
		$designidstring['cclp5'] = (!empty($this->data['clipart_c']['front'][1]['left'])?$this->data['clipart_c']['front'][1]['left']:'');
		$designidstring['cclp6'] = (!empty($this->data['clipart_c']['back'][1]['left'])?$this->data['clipart_c']['back'][1]['left']:'');
		$designidstring['cclp7'] = (!empty($this->data['clipart_c']['front'][1]['right'])?$this->data['clipart_c']['front'][1]['right']:'');
		$designidstring['cclp8'] = (!empty($this->data['clipart_c']['back'][1]['right'])?$this->data['clipart_c']['back'][1]['right']:'');

		return base64_encode(http_build_query($designidstring));
	}

	function populateDesignIdArrayIH(&$vars, &$array) {

		//global $tpt_vars;
		//if(empty($vars))
		//	$vars = $tpt_vars;

		if(!is_array($array))
				$array = array();

		//if(empty($this->designidstringIH)) {
				$this->designidstringIH = $this->getDesignIdStringIH($vars);
		//}

		//$styles_module = $vars['modules']['handler']->modules['BandStyle'];

		if(!isset($array[$this->designidstringIH])) {
				$array[$this->designidstringIH] = $this->qty;
		} else {
				$array[$this->designidstringIH] += $this->qty;
		}

	}


	function getDesignIdStringOS(&$vars) {
		/*
		$designidstring = array();
		$designidstring['type'] = $this->data['band_type'];
		//$designidstring['relief'] = (!empty($styles_module->moduleData['id'][$this->data['band_style']]['message_relief'])?$styles_module->moduleData['id'][$this->data['band_style']]['message_relief']:0);
		$designidstring['style'] = (!empty($styles_module->moduleData['id'][$this->data['band_style']]['message_relief'])?$styles_module->moduleData['id'][$this->data['band_style']]['message_relief']:0);
		$designidstring['size'] = (!empty($this->data['band_size'])?$this->data['band_size']:0);
		$designidstring['message_color'] = (!empty($styles_module->moduleData['id'][$this->data['band_style']]['message_color'])?$this->data['message_color']:'');
		$designidstring['font'] = (!empty($this->data['band_font'])?$this->data['band_font']:0);
		//var_dump($this->data['clipart']);
		//die();
		$designidstring['msg1'] = 'front'.'_-'.implode('::', $this->data['messages']['front']).(!empty($this->data['clipart']['front'][0])?'_-'.implode('::', $this->data['clipart']['front'][0]):'').(!empty($this->data['clipart_c']['front'][0])?'_-'.implode('::', $this->data['clipart_c']['front'][0]):'').(!empty($this->data['clipart']['front'][1])?'_-'.implode('::', $this->data['clipart']['front'][1]):'').(!empty($this->data['clipart_c']['front'][1])?'_-'.implode('::', $this->data['clipart_c']['front'][1]):'');
		$designidstring[] = 'back'.'_-'.implode('::', $this->data['messages']['back']).(!empty($this->data['clipart']['back'][0])?'_-'.implode('::', $this->data['clipart']['back'][0]):'').(!empty($this->data['clipart_c']['back'][0])?'_-'.implode('::', $this->data['clipart_c']['back'][0]):'').(!empty($this->data['clipart']['back'][1])?'_-'.implode('::', $this->data['clipart']['back'][1]):'').(!empty($this->data['clipart_c']['back'][1])?'_-'.implode('::', $this->data['clipart_c']['back'][1]):'');
		$designidstring[] = 'customclipart'.'_-'.$this->data['custom_clipart'];
		*/

		$styles_module = getModule($vars, 'BandStyle');

		$designidstring = array();
		$designidstring['type'] = $this->data['band_type'];
		//$designidstring['relief'] = (!empty($styles_module->moduleData['id'][$this->data['band_style']]['message_relief'])?$styles_module->moduleData['id'][$this->data['band_style']]['message_relief']:0);
		$designidstring['style'] = $this->data['band_style'];
		$designidstring['size'] = (!empty($this->data['band_size'])?$this->data['band_size']:0);
		$designidstring['message_color'] = (!empty($styles_module->moduleData['id'][$this->data['band_style']]['message_color'])?$this->data['message_color']:'');
		$designidstring['font'] = (!empty($this->data['band_font'])?$this->data['band_font']:0);
		//var_dump($this->data['clipart']);
		//die();
		$designidstring['msg1'] = (!empty($this->data['messages']['front'][0])?$this->data['messages']['front'][0]:'');
		$designidstring['msg2'] = (!empty($this->data['messages']['front'][1])?$this->data['messages']['front'][1]:'');
		$designidstring['msg3'] = (!empty($this->data['messages']['back'][0])?$this->data['messages']['back'][0]:'');
		$designidstring['msg4'] = (!empty($this->data['messages']['back'][1])?$this->data['messages']['back'][1]:'');
		$designidstring['clp1'] = (!empty($this->data['clipart']['front'][0]['left'])?$this->data['clipart']['front'][0]['left']:0);
		$designidstring['clp2'] = (!empty($this->data['clipart']['back'][0]['left'])?$this->data['clipart']['back'][0]['left']:0);
		$designidstring['clp3'] = (!empty($this->data['clipart']['front'][0]['right'])?$this->data['clipart']['front'][0]['right']:0);
		$designidstring['clp4'] = (!empty($this->data['clipart']['back'][0]['right'])?$this->data['clipart']['back'][0]['right']:0);
		$designidstring['clp5'] = (!empty($this->data['clipart']['front'][1]['left'])?$this->data['clipart']['front'][1]['left']:0);
		$designidstring['clp6'] = (!empty($this->data['clipart']['back'][1]['left'])?$this->data['clipart']['back'][1]['left']:0);
		$designidstring['clp7'] = (!empty($this->data['clipart']['front'][1]['right'])?$this->data['clipart']['front'][1]['right']:0);
		$designidstring['clp8'] = (!empty($this->data['clipart']['back'][1]['right'])?$this->data['clipart']['back'][1]['right']:0);
		$designidstring['cclp1'] = (!empty($this->data['clipart_c']['front'][0]['left'])?$this->data['clipart_c']['front'][0]['left']:'');
		$designidstring['cclp2'] = (!empty($this->data['clipart_c']['back'][0]['left'])?$this->data['clipart_c']['back'][0]['left']:'');
		$designidstring['cclp3'] = (!empty($this->data['clipart_c']['front'][0]['right'])?$this->data['clipart_c']['front'][0]['right']:'');
		$designidstring['cclp4'] = (!empty($this->data['clipart_c']['back'][0]['right'])?$this->data['clipart_c']['back'][0]['right']:'');
		$designidstring['cclp5'] = (!empty($this->data['clipart_c']['front'][1]['left'])?$this->data['clipart_c']['front'][1]['left']:'');
		$designidstring['cclp6'] = (!empty($this->data['clipart_c']['back'][1]['left'])?$this->data['clipart_c']['back'][1]['left']:'');
		$designidstring['cclp7'] = (!empty($this->data['clipart_c']['front'][1]['right'])?$this->data['clipart_c']['front'][1]['right']:'');
		$designidstring['cclp8'] = (!empty($this->data['clipart_c']['back'][1]['right'])?$this->data['clipart_c']['back'][1]['right']:'');

		return base64_encode(http_build_query($designidstring));
	}

	function populateDesignIdArrayOS(&$vars, &$array) {

		//global $tpt_vars;
		//if(empty($vars))
		//	$vars = $tpt_vars;

		if(!is_array($array))
				$array = array();

		//if(empty($this->designidstringOS)) {
				$this->designidstringOS = $this->getDesignIdStringOS($vars);
		//}

		//tpt_dump($this->designidstringOS);

		$types_module = getModule($vars, 'BandType');
		$styles_module = getModule($vars, 'BandStyle');

		if(empty($array[$this->designidstringOS]))
				$array[$this->designidstringOS] = array();

		//var_dump($vars['modules']);die();
		//var_dump($vars);die();
		//var_dump($styles_module->moduleData['id'][$this->data['band_style']]['mold']);die();
		if($styles_module->moduleData['id'][$this->data['band_style']]['screen'] || $types_module->moduleData['id'][$this->data['band_type']]['screens']) {
				/*
				if(!is_array($array[$this->designidstring][$this->data['band_size']])) {
					$array[$this->designidstring][$this->data['band_size']] = array();
				}
				*/
				if(empty($array[$this->designidstringOS]['screens'])) {
					$array[$this->designidstringOS]['screens'] = array();
				}
				if(empty($array[$this->designidstringOS]['screens'][$this->data['message_color']])) {
					$array[$this->designidstringOS]['screens'][$this->data['message_color']] = array();
				}
				/*
				if(!is_array($array[$this->designidstring][$this->data['message_color']][$this->data['band_size']])) {
					$array[$this->designidstring][$this->data['message_color']][$this->data['band_size']] = array();
				}
				*/
				$array[$this->designidstringOS]['screens'][$this->data['message_color']][] = $this;
		}

		if($styles_module->moduleData['id'][$this->data['band_style']]['mold'] || $types_module->moduleData['id'][$this->data['band_type']]['molds']) {
				if(empty($array[$this->designidstringOS]['molds'])) {
					$array[$this->designidstringOS]['molds'] = array();
				}
				/*
				if(!is_array($array[$this->designidstring]['nocolor'][$this->data['band_size']])) {
					$array[$this->designidstring]['nocolor'][$this->data['band_size']] = array();
				}
				*/
				$array[$this->designidstringOS]['molds'][] = $this;
		}

	}

	function getPreviewHTML(&$vars) {
		$pgType = $this->data['band_type'];
		$pgStyle = $this->data['band_style'];
		$pgFont = $this->data['band_font'];
		//$pgFrontRows = (!empty($pgFrontRows)?$pgFrontRows:1);
		//$pgBackRows = (!empty($pgBackRows)?$pgBackRows:1);
		//$pgTextCont = (!empty($pgTextCont)?intval($pgTextCont, 10):0);
		$pgBandColor = $this->data['band_color'];
		$pgMessageColor = $this->data['message_color'];
		$pgFrontMessage = (!empty($this->data['messages']['front'][0])?$this->data['messages']['front'][0]:'');
		$pgFrontMessage2 = (!empty($this->data['messages']['front'][1])?$this->data['messages']['front'][1]:'');
		$pgBackMessage = (!empty($this->data['messages']['back'][0])?$this->data['messages']['back'][0]:'');
		$pgBackMessage2 = (!empty($this->data['messages']['back'][1])?$this->data['messages']['back'][1]:'');

		$pgClipartFrontLeft = (!empty($this->data['clipart']['front'][0]['left'])?$this->data['clipart']['front'][0]['left']:0);
		$pgClipartFrontLeft_c = (!empty($this->data['clipart_c']['front'][0]['left'])?$this->data['clipart_c']['front'][0]['left']:0);
		$pgClipartFrontRight = (!empty($this->data['clipart']['front'][0]['right'])?$this->data['clipart']['front'][0]['right']:0);
		$pgClipartFrontRight_c = (!empty($this->data['clipart_c']['front'][0]['right'])?$this->data['clipart_c']['front'][0]['right']:0);
		$pgClipartFrontLeft2 = (!empty($this->data['clipart']['front'][1]['left'])?$this->data['clipart']['front'][1]['left']:0);
		$pgClipartFrontLeft2_c = (!empty($this->data['clipart_c']['front'][1]['left'])?$this->data['clipart_c']['front'][1]['left']:0);
		$pgClipartFrontRight2 = (!empty($this->data['clipart']['front'][1]['right'])?$this->data['clipart']['front'][1]['right']:0);
		$pgClipartFrontRight2_c = (!empty($this->data['clipart_c']['front'][1]['right'])?$this->data['clipart_c']['front'][1]['right']:0);
		$pgClipartBackLeft = (!empty($this->data['clipart']['back'][0]['left'])?$this->data['clipart']['back'][0]['left']:0);
		$pgClipartBackLeft_c = (!empty($this->data['clipart_c']['back'][0]['left'])?$this->data['clipart_c']['back'][0]['left']:0);
		$pgClipartBackRight = (!empty($this->data['clipart']['back'][0]['right'])?$this->data['clipart']['back'][0]['right']:0);
		$pgClipartBackRight_c = (!empty($this->data['clipart_c']['back'][0]['right'])?$this->data['clipart_c']['back'][0]['right']:0);
		$pgClipartBackLeft2 = (!empty($this->data['clipart']['back'][1]['left'])?$this->data['clipart']['back'][1]['left']:0);
		$pgClipartBackLeft2_c = (!empty($this->data['clipart_c']['back'][1]['left'])?$this->data['clipart_c']['back'][1]['left']:0);
		$pgClipartBackRight2 = (!empty($this->data['clipart']['back'][1]['right'])?$this->data['clipart']['back'][1]['right']:0);
		$pgClipartBackRight2_c = (!empty($this->data['clipart_c']['back'][1]['right'])?$this->data['clipart_c']['back'][1]['right']:0);
		$pgCutAway = $this->data['cut_away'];

		//$pgWidth = 450;
		//$pgHeight = 60;
		//$pgPaddingTop = 0;
		//$pgPaddingBottom = 0;
		//$pgPaddingRight = 40;

		//$pgOutlineFile = 'plain450x60.png';

		$pgFullPreview = 1;
		$pgEnableJavascript = 0;
		$pgAjaxJavascript = 0;

		$pgconf = compact(
								'pgType',
								'pgStyle',
								'pgFont',
								'pgFrontRows',
								'pgBackRows',
								'pgTextCont',
								'pgBandColor',
								'pgMessageColor',
								'pgFrontMessage',
								'pgClipartFrontLeft',
								'pgClipartFrontLeft_c',
								'pgClipartFrontRight',
								'pgClipartFrontRight_c',
								'pgFrontMessage2',
								'pgClipartFrontLeft2',
								'pgClipartFrontLeft2_c',
								'pgClipartFrontRight2',
								'pgClipartFrontRight2_c',
								'pgBackMessage',
								'pgClipartBackLeft',
								'pgClipartBackLeft_c',
								'pgClipartBackRight',
								'pgClipartBackRight_c',
								'pgBackMessage2',
								'pgClipartBackLeft2',
								'pgClipartBackLeft2_c',
								'pgClipartBackRight2',
								'pgClipartBackRight2_c',
								'pgCutAway',
								'pgWidth',
								'pgHeight',
								'pgPaddingTop',
								'pgPaddingBottom',
								'pgFullPreview',
								'pgEnableJavascript',
								'pgAjaxJavascript'
								);

		$tpt_vars = $vars;
		$tpt_imagesurl = TPT_IMAGES_URL;
		$tpt_baseurl = BASE_URL;
		include(TPT_TEMPLATES_DIR.DIRECTORY_SEPARATOR.'builder-preview.tpt.php');

		return $preview;
	}

	function getDesignUrlQuery(&$vars) {
		$addons = array();
		if(!empty($this->pricingObject->options['key_chain'])) {
				$addons['key_chain'] = 'Make Into Keychain';
		}
		$pgType = $this->data['band_type'];
		if(!empty($addons['key_chain'])) {
				$pType = 7;
		}
		$pgStyle = $this->data['band_style'];
		$invert_dual = intval($this->data['invert_dual'], 10);
		$cut_away = intval($this->data['cut_away'], 10);
		$pgFont = $this->data['band_font'];
		//$pgFrontRows = (!empty($pgFrontRows)?$pgFrontRows:1);
		//$pgBackRows = (!empty($pgBackRows)?$pgBackRows:1);
		//$pgTextCont = (!empty($pgTextCont)?intval($pgTextCont, 10):0);
		$pgBandColor = $this->data['band_color'];
		$pgMessageColor = $this->data['message_color'];
		$pgFrontMessage = (!empty($this->data['messages']['front'][0])?$this->data['messages']['front'][0]:'');
		$pgFrontMessage2 = (!empty($this->data['messages']['front'][1])?$this->data['messages']['front'][1]:'');
		$pgBackMessage = (!empty($this->data['messages']['back'][0])?$this->data['messages']['back'][0]:'');
		$pgBackMessage2 = (!empty($this->data['messages']['back'][1])?$this->data['messages']['back'][1]:'');

		$pgClipartFrontLeft = (!empty($this->data['clipart']['front'][0]['left'])?$this->data['clipart']['front'][0]['left']:0);
		$pgClipartFrontLeft_c = (!empty($this->data['clipart_c']['front'][0]['left'])?$this->data['clipart_c']['front'][0]['left']:0);
		$pgClipartFrontRight = (!empty($this->data['clipart']['front'][0]['right'])?$this->data['clipart']['front'][0]['right']:0);
		$pgClipartFrontRight_c = (!empty($this->data['clipart_c']['front'][0]['right'])?$this->data['clipart_c']['front'][0]['right']:0);
		$pgClipartFrontLeft2 = (!empty($this->data['clipart']['front'][1]['left'])?$this->data['clipart']['front'][1]['left']:0);
		$pgClipartFrontLeft2_c = (!empty($this->data['clipart_c']['front'][1]['left'])?$this->data['clipart_c']['front'][1]['left']:0);
		$pgClipartFrontRight2 = (!empty($this->data['clipart']['front'][1]['right'])?$this->data['clipart']['front'][1]['right']:0);
		$pgClipartFrontRight2_c = (!empty($this->data['clipart_c']['front'][1]['right'])?$this->data['clipart_c']['front'][1]['right']:0);
		$pgClipartBackLeft = (!empty($this->data['clipart']['back'][0]['left'])?$this->data['clipart']['back'][0]['left']:0);
		$pgClipartBackLeft_c = (!empty($this->data['clipart_c']['back'][0]['left'])?$this->data['clipart_c']['back'][0]['left']:0);
		$pgClipartBackRight = (!empty($this->data['clipart']['back'][0]['right'])?$this->data['clipart']['back'][0]['right']:0);
		$pgClipartBackRight_c = (!empty($this->data['clipart_c']['back'][0]['right'])?$this->data['clipart_c']['back'][0]['right']:0);
		$pgClipartBackLeft2 = (!empty($this->data['clipart']['back'][1]['left'])?$this->data['clipart']['back'][1]['left']:0);
		$pgClipartBackLeft2_c = (!empty($this->data['clipart_c']['back'][1]['left'])?$this->data['clipart_c']['back'][1]['left']:0);
		$pgClipartBackRight2 = (!empty($this->data['clipart']['back'][1]['right'])?$this->data['clipart']['back'][1]['right']:0);
		$pgClipartBackRight2_c = (!empty($this->data['clipart_c']['back'][1]['right'])?$this->data['clipart_c']['back'][1]['right']:0);
		//$pgCutAway = $cut_away;

		//var_dump($this->data);

		$query = array(
								'pgType'=>$pgType,
								'pgStyle'=>$pgStyle,
								'invert_dual'=>$invert_dual,
								'cut_away'=>$cut_away,
								'pgFont'=>$pgFont,
								'pgBandColor'=>$pgBandColor,
								'pgMessageColor'=>$pgMessageColor,
								'pgFrontMessage'=>$pgFrontMessage,
								'pgClipartFrontLeft'=>$pgClipartFrontLeft,
								'pgClipartFrontLeft_c'=>$pgClipartFrontLeft_c,
								'pgClipartFrontRight'=>$pgClipartFrontRight,
								'pgClipartFrontRight_c'=>$pgClipartFrontRight_c,
								'pgFrontMessage2'=>$pgFrontMessage2,
								'pgClipartFrontLeft2'=>$pgClipartFrontLeft2,
								'pgClipartFrontLeft2_c'=>$pgClipartFrontLeft2_c,
								'pgClipartFrontRight2'=>$pgClipartFrontRight2,
								'pgClipartFrontRight2_c'=>$pgClipartFrontRight2_c,
								'pgBackMessage'=>$pgBackMessage,
								'pgClipartBackLeft'=>$pgClipartBackLeft,
								'pgClipartBackLeft_c'=>$pgClipartBackLeft_c,
								'pgClipartBackRight'=>$pgClipartBackRight,
								'pgClipartBackRight_c'=>$pgClipartBackRight_c,
								'pgBackMessage2'=>$pgBackMessage2,
								'pgClipartBackLeft2'=>$pgClipartBackLeft2,
								'pgClipartBackLeft2_c'=>$pgClipartBackLeft2_c,
								'pgClipartBackRight2'=>$pgClipartBackRight2,
								'pgClipartBackRight2_c'=>$pgClipartBackRight2_c
								);

		//var_dump($query);die();
		//var_dump($pgType);die();
		$query = http_build_query($query);
		//var_dump($query);die();

		return $query;

	}

	function getDesignUrlQuery2(&$vars) {
		$pgType = $this->data['band_type'];
		$pgStyle = $this->data['band_style'];
		$invert_dual = intval($this->data['invert_dual'], 10);
		$cut_away = intval($this->data['cut_away'], 10);
		$pgFont = $this->data['band_font'];
		//$pgFrontRows = (!empty($pgFrontRows)?$pgFrontRows:1);
		//$pgBackRows = (!empty($pgBackRows)?$pgBackRows:1);
		//$pgTextCont = (!empty($pgTextCont)?intval($pgTextCont, 10):0);
		$pgBandColor = $this->data['band_color'];
		$pgMessageColor = $this->data['message_color'];
		$pgFrontMessage = (!empty($this->data['messages']['front'][0])?$this->data['messages']['front'][0]:'');
		$pgFrontMessage2 = (!empty($this->data['messages']['front'][1])?$this->data['messages']['front'][1]:'');
		$pgBackMessage = (!empty($this->data['messages']['back'][0])?$this->data['messages']['back'][0]:'');
		$pgBackMessage2 = (!empty($this->data['messages']['back'][1])?$this->data['messages']['back'][1]:'');

		$pgClipartFrontLeft = (!empty($this->data['clipart']['front'][0]['left'])?$this->data['clipart']['front'][0]['left']:0);
		$pgClipartFrontLeft_c = (!empty($this->data['clipart_c']['front'][0]['left'])?$this->data['clipart_c']['front'][0]['left']:0);
		$pgClipartFrontRight = (!empty($this->data['clipart']['front'][0]['right'])?$this->data['clipart']['front'][0]['right']:0);
		$pgClipartFrontRight_c = (!empty($this->data['clipart_c']['front'][0]['right'])?$this->data['clipart_c']['front'][0]['right']:0);
		$pgClipartFrontLeft2 = (!empty($this->data['clipart']['front'][1]['left'])?$this->data['clipart']['front'][1]['left']:0);
		$pgClipartFrontLeft2_c = (!empty($this->data['clipart_c']['front'][1]['left'])?$this->data['clipart_c']['front'][1]['left']:0);
		$pgClipartFrontRight2 = (!empty($this->data['clipart']['front'][1]['right'])?$this->data['clipart']['front'][1]['right']:0);
		$pgClipartFrontRight2_c = (!empty($this->data['clipart_c']['front'][1]['right'])?$this->data['clipart_c']['front'][1]['right']:0);
		$pgClipartBackLeft = (!empty($this->data['clipart']['back'][0]['left'])?$this->data['clipart']['back'][0]['left']:0);
		$pgClipartBackLeft_c = (!empty($this->data['clipart_c']['back'][0]['left'])?$this->data['clipart_c']['back'][0]['left']:0);
		$pgClipartBackRight = (!empty($this->data['clipart']['back'][0]['right'])?$this->data['clipart']['back'][0]['right']:0);
		$pgClipartBackRight_c = (!empty($this->data['clipart_c']['back'][0]['right'])?$this->data['clipart_c']['back'][0]['right']:0);
		$pgClipartBackLeft2 = (!empty($this->data['clipart']['back'][1]['left'])?$this->data['clipart']['back'][1]['left']:0);
		$pgClipartBackLeft2_c = (!empty($this->data['clipart_c']['back'][1]['left'])?$this->data['clipart_c']['back'][1]['left']:0);
		$pgClipartBackRight2 = (!empty($this->data['clipart']['back'][1]['right'])?$this->data['clipart']['back'][1]['right']:0);
		$pgClipartBackRight2_c = (!empty($this->data['clipart_c']['back'][1]['right'])?$this->data['clipart_c']['back'][1]['right']:0);
		//$pgCutAway = $cut_away;

		//var_dump($this->data);
		$query = array(
								'band_type'=>$pgType,
								'band_style'=>$pgStyle,
								'invert_dual'=>$invert_dual,
								'cut_away'=>$cut_away,
								'band_font'=>$pgFont,
								'band_color'=>$pgBandColor,
								'message_color'=>$pgMessageColor,
								'message_front'=>$pgFrontMessage,
								'clipart_front_left'=>$pgClipartFrontLeft,
								'clipart_front_left_c'=>$pgClipartFrontLeft_c,
								'clipart_front_right'=>$pgClipartFrontRight,
								'clipart_front_right_c'=>$pgClipartFrontRight_c,
								'message_front2'=>$pgFrontMessage2,
								'clipart_front_left2'=>$pgClipartFrontLeft2,
								'clipart_front_left2_c'=>$pgClipartFrontLeft2_c,
								'clipart_front_right2'=>$pgClipartFrontRight2,
								'clipart_front_right2_c'=>$pgClipartFrontRight2_c,
								'message_back'=>$pgBackMessage,
								'clipart_back_left'=>$pgClipartBackLeft,
								'clipart_back_left_c'=>$pgClipartBackLeft_c,
								'clipart_back_right'=>$pgClipartBackRight,
								'clipart_back_right_c'=>$pgClipartBackRight_c,
								'message_back2'=>$pgBackMessage2,
								'clipart_back_left2'=>$pgClipartBackLeft2,
								'clipart_back_left2_c'=>$pgClipartBackLeft2_c,
								'clipart_back_right2'=>$pgClipartBackRight2,
								'clipart_back_right2_c'=>$pgClipartBackRight2_c
								);
		//var_dump($query);die();
		//var_dump($pgType);die();
		$query = http_build_query($query);
		//var_dump($query);die();

		return $query;

	}

	function getDesignParams3(&$vars) {
		$pgType = $this->data['band_type'];
		$pgStyle = $this->data['band_style'];
		$invert_dual = intval($this->data['invert_dual'], 10);
		$cut_away = intval($this->data['cut_away'], 10);
		$pgFont = $this->data['band_font'];
		//$pgFrontRows = (!empty($pgFrontRows)?$pgFrontRows:1);
		//$pgBackRows = (!empty($pgBackRows)?$pgBackRows:1);
		//$pgTextCont = (!empty($pgTextCont)?intval($pgTextCont, 10):0);
		$pgBandColor = $this->data['band_color'];
		$pgMessageColor = $this->data['message_color'];
		$pgFrontMessage = (!empty($this->data['messages']['front'][0])?$this->data['messages']['front'][0]:'');
		$pgFrontMessage2 = (!empty($this->data['messages']['front'][1])?$this->data['messages']['front'][1]:'');
		$pgBackMessage = (!empty($this->data['messages']['back'][0])?$this->data['messages']['back'][0]:'');
		$pgBackMessage2 = (!empty($this->data['messages']['back'][1])?$this->data['messages']['back'][1]:'');

		$pgClipartFrontLeft = (!empty($this->data['clipart']['front'][0]['left'])?$this->data['clipart']['front'][0]['left']:0);
		$pgClipartFrontLeft_c = (!empty($this->data['clipart_c']['front'][0]['left'])?$this->data['clipart_c']['front'][0]['left']:0);
		$pgClipartFrontRight = (!empty($this->data['clipart']['front'][0]['right'])?$this->data['clipart']['front'][0]['right']:0);
		$pgClipartFrontRight_c = (!empty($this->data['clipart_c']['front'][0]['right'])?$this->data['clipart_c']['front'][0]['right']:0);
		$pgClipartFrontLeft2 = (!empty($this->data['clipart']['front'][1]['left'])?$this->data['clipart']['front'][1]['left']:0);
		$pgClipartFrontLeft2_c = (!empty($this->data['clipart_c']['front'][1]['left'])?$this->data['clipart_c']['front'][1]['left']:0);
		$pgClipartFrontRight2 = (!empty($this->data['clipart']['front'][1]['right'])?$this->data['clipart']['front'][1]['right']:0);
		$pgClipartFrontRight2_c = (!empty($this->data['clipart_c']['front'][1]['right'])?$this->data['clipart_c']['front'][1]['right']:0);
		$pgClipartBackLeft = (!empty($this->data['clipart']['back'][0]['left'])?$this->data['clipart']['back'][0]['left']:0);
		$pgClipartBackLeft_c = (!empty($this->data['clipart_c']['back'][0]['left'])?$this->data['clipart_c']['back'][0]['left']:0);
		$pgClipartBackRight = (!empty($this->data['clipart']['back'][0]['right'])?$this->data['clipart']['back'][0]['right']:0);
		$pgClipartBackRight_c = (!empty($this->data['clipart_c']['back'][0]['right'])?$this->data['clipart_c']['back'][0]['right']:0);
		$pgClipartBackLeft2 = (!empty($this->data['clipart']['back'][1]['left'])?$this->data['clipart']['back'][1]['left']:0);
		$pgClipartBackLeft2_c = (!empty($this->data['clipart_c']['back'][1]['left'])?$this->data['clipart_c']['back'][1]['left']:0);
		$pgClipartBackRight2 = (!empty($this->data['clipart']['back'][1]['right'])?$this->data['clipart']['back'][1]['right']:0);
		$pgClipartBackRight2_c = (!empty($this->data['clipart_c']['back'][1]['right'])?$this->data['clipart_c']['back'][1]['right']:0);
		//$pgCutAway = $cut_away;

		$sizes_module = $vars['modules']['handler']->modules['BandSize'];

		$ssuf = $sizes_module->moduleData['id'][$this->data['band_size']]['name'];
		$pgSizeName = 'qty_'.$ssuf;
		$pgSize = array($pgSizeName=>$this->qty);

		$pgAddons = array();

		if(!empty($this->pricingObject->options)) {
				$usable_addons = array('indvl_packaging'=>1, 'rush_order'=>1, 'key_chain'=>1, 'key_chain_clasp'=>1, 'glow_ink_fill'=>1);
				$pgAddons = array_intersect_key($this->pricingObject->options, $usable_addons);
				//var_dump($pgAddons);die();
		}

		//var_dump($this->data);
		$query = array(
								'band_type'=>$pgType,
								'band_style'=>$pgStyle,
								'invert_dual'=>$invert_dual,
								'cut_away'=>$cut_away,
								'band_font'=>$pgFont,
								'band_color'=>$pgBandColor,
								'message_color'=>$pgMessageColor,
								'message_front'=>$pgFrontMessage,
								'clipart_front_left'=>$pgClipartFrontLeft,
								'clipart_front_left_c'=>$pgClipartFrontLeft_c,
								'clipart_front_right'=>$pgClipartFrontRight,
								'clipart_front_right_c'=>$pgClipartFrontRight_c,
								'message_front2'=>$pgFrontMessage2,
								'clipart_front_left2'=>$pgClipartFrontLeft2,
								'clipart_front_left2_c'=>$pgClipartFrontLeft2_c,
								'clipart_front_right2'=>$pgClipartFrontRight2,
								'clipart_front_right2_c'=>$pgClipartFrontRight2_c,
								'message_back'=>$pgBackMessage,
								'clipart_back_left'=>$pgClipartBackLeft,
								'clipart_back_left_c'=>$pgClipartBackLeft_c,
								'clipart_back_right'=>$pgClipartBackRight,
								'clipart_back_right_c'=>$pgClipartBackRight_c,
								'message_back2'=>$pgBackMessage2,
								'clipart_back_left2'=>$pgClipartBackLeft2,
								'clipart_back_left2_c'=>$pgClipartBackLeft2_c,
								'clipart_back_right2'=>$pgClipartBackRight2,
								'clipart_back_right2_c'=>$pgClipartBackRight2_c,
								'sizes'=>$pgSize,
								'addons'=>$pgAddons
								);
		//var_dump($query);die();
		//var_dump($pgType);die();
		//var_dump($query);die();

		return $query;

	}



	function getDesignUrlQuery3(&$vars) {
		tpt_logger::dump($vars, $this->data, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), '$product->data', __FILE__.' '.__LINE__);

		$pgType = $this->data['band_type'];
		$pgStyle = $this->data['band_style'];
		$invert_dual = intval($this->data['invert_dual'], 10);
		$cut_away = intval($this->data['cut_away'], 10);
		$pgFont = $this->data['band_font'];
		//$pgFrontRows = (!empty($pgFrontRows)?$pgFrontRows:1);
		//$pgBackRows = (!empty($pgBackRows)?$pgBackRows:1);
		//$pgTextCont = (empty($this->data['message_span'])?1:0);
		$pgMessageSpan = !empty($this->data['message_span'])?$this->data['message_span']:0;
		$pgBandColor = $this->data['band_color'];
		$pgMessageColor = $this->data['message_color'];
		$pgFrontMessage = (!empty($this->data['messages']['front'][0])?$this->data['messages']['front'][0]:'');
		$pgFrontMessage2 = (!empty($this->data['messages']['front'][1])?$this->data['messages']['front'][1]:'');
		$pgBackMessage = (!empty($this->data['messages']['back'][0])?$this->data['messages']['back'][0]:'');
		$pgBackMessage2 = (!empty($this->data['messages']['back'][1])?$this->data['messages']['back'][1]:'');

		$pgClipartFrontLeft = (!empty($this->data['clipart']['front'][0]['left'])?$this->data['clipart']['front'][0]['left']:0);
		$pgClipartFrontLeft_c = (!empty($this->data['clipart_c']['front'][0]['left'])?$this->data['clipart_c']['front'][0]['left']:0);
		$pgClipartFrontRight = (!empty($this->data['clipart']['front'][0]['right'])?$this->data['clipart']['front'][0]['right']:0);
		$pgClipartFrontRight_c = (!empty($this->data['clipart_c']['front'][0]['right'])?$this->data['clipart_c']['front'][0]['right']:0);
		$pgClipartFrontLeft2 = (!empty($this->data['clipart']['front'][1]['left'])?$this->data['clipart']['front'][1]['left']:0);
		$pgClipartFrontLeft2_c = (!empty($this->data['clipart_c']['front'][1]['left'])?$this->data['clipart_c']['front'][1]['left']:0);
		$pgClipartFrontRight2 = (!empty($this->data['clipart']['front'][1]['right'])?$this->data['clipart']['front'][1]['right']:0);
		$pgClipartFrontRight2_c = (!empty($this->data['clipart_c']['front'][1]['right'])?$this->data['clipart_c']['front'][1]['right']:0);
		$pgClipartBackLeft = (!empty($this->data['clipart']['back'][0]['left'])?$this->data['clipart']['back'][0]['left']:0);
		$pgClipartBackLeft_c = (!empty($this->data['clipart_c']['back'][0]['left'])?$this->data['clipart_c']['back'][0]['left']:0);
		$pgClipartBackRight = (!empty($this->data['clipart']['back'][0]['right'])?$this->data['clipart']['back'][0]['right']:0);
		$pgClipartBackRight_c = (!empty($this->data['clipart_c']['back'][0]['right'])?$this->data['clipart_c']['back'][0]['right']:0);
		$pgClipartBackLeft2 = (!empty($this->data['clipart']['back'][1]['left'])?$this->data['clipart']['back'][1]['left']:0);
		$pgClipartBackLeft2_c = (!empty($this->data['clipart_c']['back'][1]['left'])?$this->data['clipart_c']['back'][1]['left']:0);
		$pgClipartBackRight2 = (!empty($this->data['clipart']['back'][1]['right'])?$this->data['clipart']['back'][1]['right']:0);
		$pgClipartBackRight2_c = (!empty($this->data['clipart_c']['back'][1]['right'])?$this->data['clipart_c']['back'][1]['right']:0);
		//$pgCutAway = $cut_away;

		$sizes_module = $vars['modules']['handler']->modules['BandSize'];

		$ssuf = $sizes_module->moduleData['id'][$this->data['band_size']]['name'];
		$pgSizeName = 'qty_'.$ssuf;
		$pgSize = array($pgSizeName=>$this->qty);

		$pgAddons = array();

		if(!empty($this->pricingObject->options)) {
				$usable_addons = array('indvl_packaging'=>1, 'rush_order'=>1, 'key_chain'=>1, 'key_chain_clasp'=>1, 'glow_ink_fill'=>1);
				$pgAddons = array_intersect_key($this->pricingObject->options, $usable_addons);
				//var_dump($pgAddons);die();
		}

		//var_dump($this->data);
		$query = array(
								'band_type'=>$pgType,
								'band_style'=>$pgStyle,
								'invert_dual'=>$invert_dual,
								'cut_away'=>$cut_away,
								'band_font'=>$pgFont,
								'band_color'=>$pgBandColor,
								'message_color'=>$pgMessageColor,
								'message_span'=>$pgMessageSpan,
								'message_front'=>$pgFrontMessage,
								'clipart_front_left'=>$pgClipartFrontLeft,
								'clipart_front_left_c'=>$pgClipartFrontLeft_c,
								'clipart_front_right'=>$pgClipartFrontRight,
								'clipart_front_right_c'=>$pgClipartFrontRight_c,
								'message_front2'=>$pgFrontMessage2,
								'clipart_front_left2'=>$pgClipartFrontLeft2,
								'clipart_front_left2_c'=>$pgClipartFrontLeft2_c,
								'clipart_front_right2'=>$pgClipartFrontRight2,
								'clipart_front_right2_c'=>$pgClipartFrontRight2_c,
								'message_back'=>$pgBackMessage,
								'clipart_back_left'=>$pgClipartBackLeft,
								'clipart_back_left_c'=>$pgClipartBackLeft_c,
								'clipart_back_right'=>$pgClipartBackRight,
								'clipart_back_right_c'=>$pgClipartBackRight_c,
								'message_back2'=>$pgBackMessage2,
								'clipart_back_left2'=>$pgClipartBackLeft2,
								'clipart_back_left2_c'=>$pgClipartBackLeft2_c,
								'clipart_back_right2'=>$pgClipartBackRight2,
								'clipart_back_right2_c'=>$pgClipartBackRight2_c,
								'sizes'=>$pgSize,
								'addons'=>$pgAddons
								);
		//var_dump($query);die();
		//var_dump($pgType);die();
		$query = http_build_query($query);
		//var_dump($query);die();

		return $query;

	}


	function getPricingDataArray(&$vars) {
		$pricingdata = array(
				'Shipping'=>0,
				'Discount'=>0,
				'Tax'=>0,
				'Total_Price'=>$this->price['values']['mbase_price'],
				'cost'=>0,
				'Total_Cost'=>0
										);
		
		return $pricingdata;
	}
	
	
	function getDesignNotesString(&$vars) {
		$amzg_extras = '';
		if (!empty($this->pricingObject->options['glitter'])) {
					$amzg_extras .= '+Add Glitter<br/>';
		}
		if (!empty($this->pricingObject->options['uv'])) {
					$amzg_extras .= '+Add UV<br/>';
		}
		if (!empty($this->pricingObject->options['glow'])) {
					$amzg_extras .= '+Add Glow<br/>';
		}
		if (!empty($this->pricingObject->options['glow_ink_fill'])) {
					$amzg_extras .= '+Add Glow-in-the-Dark Message<br/>';
		}
		if (!empty($this->pricingObject->options['indvl_packaging'])) {
					$amzg_extras .= '+Add individual Packing<br/>';
		}
		if (!empty($this->pricingObject->options['key_chain'])) {
					$amzg_extras .= '+Make into keychain<br/>';
		}
		
		return $amzg_extras;
	}


	function getCachedProductImage(&$vars, $x=0, $y=0) {

		/*
		$pgType = $this->data['band_type'];
		$pgStyle = $this->data['band_style'];
		$pgFont = $this->data['band_font'];
		$pgBandColor = $this->data['band_color'];
		$pgMessageColor = $this->data['message_color'];
		$pgFrontMessage = $this->data['messages']['front'][0];
		$pgFrontMessage2 = $this->data['messages']['front'][1];
		$pgBackMessage = $this->data['messages']['back'][0];
		$pgBackMessage2 = $this->data['messages']['back'][1];

		$pgClipartFrontLeft = $this->data['clipart']['front'][0]['left'];
		$pgClipartFrontRight = $this->data['clipart']['front'][0]['right'];
		$pgClipartFrontLeft2 = $this->data['clipart']['front'][1]['left'];
		$pgClipartFrontRight2 = $this->data['clipart']['front'][1]['right'];
		$pgClipartBackLeft = $this->data['clipart']['back'][0]['left'];
		$pgClipartBackRight = $this->data['clipart']['back'][0]['right'];
		$pgClipartBackLeft2 = $this->data['clipart']['back'][1]['left'];
		$pgClipartBackRight2 = $this->data['clipart']['back'][1]['right'];

		$pgWidth = $x;
		$pgHeight = $y;

		$pgconf = compact(
								$pgType,
								$pgStyle,
								$pgFont,
								$pgBandColor,
								$pgMessageColor,
								$pgFrontMessage,
								$pgClipartFrontLeft,
								$pgClipartFrontRight,
								$pgFrontMessage2,
								$pgClipartFrontLeft2,
								$pgClipartFrontRight2,
								$pgBackMessage,
								$pgClipartBackLeft,
								$pgClipartBackRight,
								$pgBackMessage2,
								$pgClipartBackLeft2,
								$pgClipartBackRight2,
								$pgWidth,
								$pgHeight
								);

		*/
		//tpt_dump(!empty($this->data['messages']['front'][1]), true);
		$flatimg = array(
						'pgType'=>$this->data['band_type'],
						'pgStyle'=>$this->data['band_style'],
						'invert_dual'=>intval($this->data['invert_dual'], 10),
						'cut_away'=>intval($this->data['cut_away'], 10),
						'pgFont' => $this->data['band_font'],
						'pgBandColor' => $this->data['band_color'],
						'pgMessageColor' => $this->data['message_color'],
						'pgFrontMessage' => 'f1:'.(!empty($this->data['messages']['front'][0])?$this->data['messages']['front'][0]:''),
						'pgClipartFrontLeft' => !empty($this->data['clipart']['front'][0]['left'])?$this->data['clipart']['front'][0]['left']:0,
						'pgClipartFrontLeft_c' => !empty($this->data['clipart_c']['front'][0]['left'])?$this->data['clipart_c']['front'][0]['left']:0,
						'pgClipartFrontRight' => !empty($this->data['clipart']['front'][0]['right'])?$this->data['clipart']['front'][0]['right']:0,
						'pgClipartFrontRight_c' => !empty($this->data['clipart_c']['front'][0]['right'])?$this->data['clipart_c']['front'][0]['right']:0,
						'pgFrontMessage2' => 'f2:'.(!empty($this->data['messages']['front'][1])?$this->data['messages']['front'][1]:''),
						'pgClipartFrontLeft2' => !empty($this->data['clipart']['front'][1]['left'])?$this->data['clipart']['front'][1]['left']:0,
						'pgClipartFrontLeft2_c' => !empty($this->data['clipart_c']['front'][1]['left'])?$this->data['clipart_c']['front'][1]['left']:0,
						'pgClipartFrontRight2' => !empty($this->data['clipart']['front'][1]['right'])?$this->data['clipart']['front'][1]['right']:0,
						'pgClipartFrontRight2_c' => !empty($this->data['clipart_c']['front'][1]['right'])?$this->data['clipart_c']['front'][1]['right']:0,
						'pgBackMessage' => 'b1:'.(!empty($this->data['messages']['back'][0])?$this->data['messages']['back'][0]:''),
						'pgClipartBackLeft' => !empty($this->data['clipart']['back'][0]['left'])?$this->data['clipart']['back'][0]['left']:0,
						'pgClipartBackLeft_c' => !empty($this->data['clipart_c']['back'][0]['left'])?$this->data['clipart_c']['back'][0]['left']:0,
						'pgClipartBackRight' => !empty($this->data['clipart']['back'][0]['right'])?$this->data['clipart']['back'][0]['right']:0,
						'pgClipartBackRight_c' => !empty($this->data['clipart_c']['back'][0]['right'])?$this->data['clipart_c']['back'][0]['right']:0,
						'pgBackMessage2' => 'b2:'.(!empty($this->data['messages']['back'][1])?$this->data['messages']['back'][1]:''),
						'pgClipartBackLeft2' => !empty($this->data['clipart']['back'][1]['left'])?$this->data['clipart']['back'][1]['left']:0,
						'pgClipartBackLeft2_c' => !empty($this->data['clipart_c']['back'][1]['left'])?$this->data['clipart_c']['back'][1]['left']:0,
						'pgClipartBackRight2' => !empty($this->data['clipart']['back'][1]['right'])?$this->data['clipart']['back'][1]['right']:0,
						'pgClipartBackRight2_c' => !empty($this->data['clipart_c']['back'][1]['right'])?$this->data['clipart_c']['back'][1]['right']:0,
						'pg_x' => $x,
						'pg_y' => $y
						);
		$query = array(
						'type'=>'flat',
						'pgType'=>$this->data['band_type'],
						'pgStyle'=>$this->data['band_style'],
						'invert_dual'=>intval($this->data['invert_dual'], 10),
						'cut_away'=>intval($this->data['cut_away'], 10),
						'pgFont' => $this->data['band_font'],
						'pgBandColor' => $this->data['band_color'],
						'pgMessageColor' => $this->data['message_color'],
						'pgFrontMessage' => (!empty($this->data['messages']['front'][0])?$this->data['messages']['front'][0]:''),
						'pgClipartFrontLeft' => !empty($this->data['clipart']['front'][0]['left'])?$this->data['clipart']['front'][0]['left']:0,
						'pgClipartFrontLeft_c' => !empty($this->data['clipart_c']['front'][0]['left'])?$this->data['clipart_c']['front'][0]['left']:0,
						'pgClipartFrontRight' => !empty($this->data['clipart']['front'][0]['right'])?$this->data['clipart']['front'][0]['right']:0,
						'pgClipartFrontRight_c' => !empty($this->data['clipart_c']['front'][0]['right'])?$this->data['clipart_c']['front'][0]['right']:0,
						'pgFrontMessage2' => (!empty($this->data['messages']['front'][1])?$this->data['messages']['front'][1]:''),
						'pgClipartFrontLeft2' => !empty($this->data['clipart']['front'][1]['left'])?$this->data['clipart']['front'][1]['left']:0,
						'pgClipartFrontLeft2_c' => !empty($this->data['clipart_c']['front'][1]['left'])?$this->data['clipart_c']['front'][1]['left']:0,
						'pgClipartFrontRight2' => !empty($this->data['clipart']['front'][1]['right'])?$this->data['clipart']['front'][1]['right']:0,
						'pgClipartFrontRight2_c' => !empty($this->data['clipart_c']['front'][1]['right'])?$this->data['clipart_c']['front'][1]['right']:0,
						'pgBackMessage' => (!empty($this->data['messages']['back'][0])?$this->data['messages']['back'][0]:''),
						'pgClipartBackLeft' => !empty($this->data['clipart']['back'][0]['left'])?$this->data['clipart']['back'][0]['left']:0,
						'pgClipartBackLeft_c' => !empty($this->data['clipart_c']['back'][0]['left'])?$this->data['clipart_c']['back'][0]['left']:0,
						'pgClipartBackRight' => !empty($this->data['clipart']['back'][0]['right'])?$this->data['clipart']['back'][0]['right']:0,
						'pgClipartBackRight_c' => !empty($this->data['clipart_c']['back'][0]['right'])?$this->data['clipart_c']['back'][0]['right']:0,
						'pgBackMessage2' => (!empty($this->data['messages']['back'][1])?$this->data['messages']['back'][1]:''),
						'pgClipartBackLeft2' => !empty($this->data['clipart']['back'][1]['left'])?$this->data['clipart']['back'][1]['left']:0,
						'pgClipartBackLeft2_c' => !empty($this->data['clipart_c']['back'][1]['left'])?$this->data['clipart_c']['back'][1]['left']:0,
						'pgClipartBackRight2' => !empty($this->data['clipart']['back'][1]['right'])?$this->data['clipart']['back'][1]['right']:0,
						'pgClipartBackRight2_c' => !empty($this->data['clipart_c']['back'][1]['right'])?$this->data['clipart_c']['back'][1]['right']:0,
						'pg_x' => $x,
						'pg_y' => $y
						);

		$image = '';



		$filename = sha1(implode($flatimg)).'.png';
		$cfile = TPT_IMAGES_DIR.DIRECTORY_SEPARATOR.'preview'.DIRECTORY_SEPARATOR.'cached'.DIRECTORY_SEPARATOR.'flat'.DIRECTORY_SEPARATOR.$filename;
		if(is_file($cfile)) {
				//tpt_dump('no');
				//tpt_dump($cfile, true);
				//header('Content-type: image/png');
				$image = file_get_contents($cfile);
		} else {
				//tpt_dump($query);
				//tpt_dump('yeah');
				//tpt_dump($flatimg);
				//tpt_dump($cfile);
				//tpt_dump($query, true);
				$image = tpt_PreviewGenerator::generatePreview($vars, $query);
		}

		return $image;
	}



	function getCachedImageName(&$vars, $x=0, $y=0) {

		/*
		$pgType = $this->data['band_type'];
		$pgStyle = $this->data['band_style'];
		$pgFont = $this->data['band_font'];
		$pgBandColor = $this->data['band_color'];
		$pgMessageColor = $this->data['message_color'];
		$pgFrontMessage = $this->data['messages']['front'][0];
		$pgFrontMessage2 = $this->data['messages']['front'][1];
		$pgBackMessage = $this->data['messages']['back'][0];
		$pgBackMessage2 = $this->data['messages']['back'][1];

		$pgClipartFrontLeft = $this->data['clipart']['front'][0]['left'];
		$pgClipartFrontRight = $this->data['clipart']['front'][0]['right'];
		$pgClipartFrontLeft2 = $this->data['clipart']['front'][1]['left'];
		$pgClipartFrontRight2 = $this->data['clipart']['front'][1]['right'];
		$pgClipartBackLeft = $this->data['clipart']['back'][0]['left'];
		$pgClipartBackRight = $this->data['clipart']['back'][0]['right'];
		$pgClipartBackLeft2 = $this->data['clipart']['back'][1]['left'];
		$pgClipartBackRight2 = $this->data['clipart']['back'][1]['right'];

		$pgWidth = $x;
		$pgHeight = $y;

		$pgconf = compact(
								$pgType,
								$pgStyle,
								$pgFont,
								$pgBandColor,
								$pgMessageColor,
								$pgFrontMessage,
								$pgClipartFrontLeft,
								$pgClipartFrontRight,
								$pgFrontMessage2,
								$pgClipartFrontLeft2,
								$pgClipartFrontRight2,
								$pgBackMessage,
								$pgClipartBackLeft,
								$pgClipartBackRight,
								$pgBackMessage2,
								$pgClipartBackLeft2,
								$pgClipartBackRight2,
								$pgWidth,
								$pgHeight
								);

		*/
		$flatimg = array(
						'pgType'=>$this->data['band_type'],
						'pgStyle'=>$this->data['band_style'],
						'invert_dual'=>intval($this->data['invert_dual'], 10),
						'cut_away'=>intval($this->data['cut_away'], 10),
						'pgFont' => $this->data['band_font'],
						'pgBandColor' => $this->data['band_color'],
						'pgMessageColor' => $this->data['message_color'],
						'pgFrontMessage' => 'f1:'.(!empty($this->data['messages']['front'][0])?$this->data['messages']['front'][0]:''),
						'pgClipartFrontLeft' => (!empty($this->data['clipart']['front'][0]['left'])?$this->data['clipart']['front'][0]['left']:0),
						'pgClipartFrontLeft_c' => (!empty($this->data['clipart_c']['front'][0]['left'])?$this->data['clipart_c']['front'][0]['left']:0),
						'pgClipartFrontRight' => (!empty($this->data['clipart']['front'][0]['right'])?$this->data['clipart']['front'][0]['right']:0),
						'pgClipartFrontRight_c' => (!empty($this->data['clipart_c']['front'][0]['right'])?$this->data['clipart_c']['front'][0]['right']:0),
						'pgFrontMessage2' => 'f2:'.(!empty($this->data['messages']['front'][1])?$this->data['messages']['front'][1]:''),
						'pgClipartFrontLeft2' => (!empty($this->data['clipart']['front'][1]['left'])?$this->data['clipart']['front'][1]['left']:0),
						'pgClipartFrontLeft2_c' => (!empty($this->data['clipart_c']['front'][1]['left'])?$this->data['clipart_c']['front'][1]['left']:0),
						'pgClipartFrontRight2' => (!empty($this->data['clipart']['front'][1]['right'])?$this->data['clipart']['front'][1]['right']:0),
						'pgClipartFrontRight2_c' => (!empty($this->data['clipart_c']['front'][1]['right'])?$this->data['clipart_c']['front'][1]['right']:0),
						'pgBackMessage' => 'b1:'.(!empty($this->data['messages']['back'][0])?$this->data['messages']['back'][0]:''),
						'pgClipartBackLeft' => (!empty($this->data['clipart']['back'][0]['left'])?$this->data['clipart']['back'][0]['left']:0),
						'pgClipartBackLeft_c' => (!empty($this->data['clipart_c']['back'][0]['left'])?$this->data['clipart_c']['back'][0]['left']:0),
						'pgClipartBackRight' => (!empty($this->data['clipart']['back'][0]['right'])?$this->data['clipart']['back'][0]['right']:0),
						'pgClipartBackRight_c' => (!empty($this->data['clipart_c']['back'][0]['right'])?$this->data['clipart_c']['back'][0]['right']:0),
						'pgBackMessage2' => 'b2:'.(!empty($this->data['messages']['back'][1])?$this->data['messages']['back'][1]:''),
						'pgClipartBackLeft2' => (!empty($this->data['clipart']['back'][1]['left'])?$this->data['clipart']['back'][1]['left']:0),
						'pgClipartBackLeft2_c' => (!empty($this->data['clipart_c']['back'][1]['left'])?$this->data['clipart_c']['back'][1]['left']:0),
						'pgClipartBackRight2' => (!empty($this->data['clipart']['back'][1]['right'])?$this->data['clipart']['back'][1]['right']:0),
						'pgClipartBackRight2_c' => (!empty($this->data['clipart_c']['back'][1]['right'])?$this->data['clipart_c']['back'][1]['right']:0),
						'pg_x' => $x,
						'pg_y' => $y
						);
		$query = array(
						'type'=>'flat',
						'pgType'=>$this->data['band_type'],
						'pgStyle'=>$this->data['band_style'],
						'invert_dual'=>intval($this->data['invert_dual'], 10),
						'cut_away'=>intval($this->data['cut_away'], 10),
						'pgFont' => $this->data['band_font'],
						'pgBandColor' => $this->data['band_color'],
						'pgMessageColor' => $this->data['message_color'],
						'pgFrontMessage' => (!empty($this->data['messages']['front'][0])?$this->data['messages']['front'][0]:''),
						'pgClipartFrontLeft' => (!empty($this->data['clipart']['front'][0]['left'])?$this->data['clipart']['front'][0]['left']:0),
						'pgClipartFrontLeft_c' => (!empty($this->data['clipart_c']['front'][0]['left'])?$this->data['clipart_c']['front'][0]['left']:0),
						'pgClipartFrontRight' => (!empty($this->data['clipart']['front'][0]['right'])?$this->data['clipart']['front'][0]['right']:0),
						'pgClipartFrontRight_c' => (!empty($this->data['clipart_c']['front'][0]['right'])?$this->data['clipart_c']['front'][0]['right']:0),
						'pgFrontMessage2' => (!empty($this->data['messages']['front'][1])?$this->data['messages']['front'][1]:''),
						'pgClipartFrontLeft2' => (!empty($this->data['clipart']['front'][1]['left'])?$this->data['clipart']['front'][1]['left']:0),
						'pgClipartFrontLeft2_c' => (!empty($this->data['clipart_c']['front'][1]['left'])?$this->data['clipart_c']['front'][1]['left']:0),
						'pgClipartFrontRight2' => (!empty($this->data['clipart']['front'][1]['right'])?$this->data['clipart']['front'][1]['right']:0),
						'pgClipartFrontRight2_c' => (!empty($this->data['clipart_c']['front'][1]['right'])?$this->data['clipart_c']['front'][1]['right']:0),
						'pgBackMessage' => (!empty($this->data['messages']['back'][0])?$this->data['messages']['back'][0]:''),
						'pgClipartBackLeft' => (!empty($this->data['clipart']['back'][0]['left'])?$this->data['clipart']['back'][0]['left']:0),
						'pgClipartBackLeft_c' => (!empty($this->data['clipart_c']['back'][0]['left'])?$this->data['clipart_c']['back'][0]['left']:0),
						'pgClipartBackRight' => (!empty($this->data['clipart']['back'][0]['right'])?$this->data['clipart']['back'][0]['right']:0),
						'pgClipartBackRight_c' => (!empty($this->data['clipart_c']['back'][0]['right'])?$this->data['clipart_c']['back'][0]['right']:0),
						'pgBackMessage2' => (!empty($this->data['messages']['back'][1])?$this->data['messages']['back'][1]:''),
						'pgClipartBackLeft2' => (!empty($this->data['clipart']['back'][1]['left'])?$this->data['clipart']['back'][1]['left']:0),
						'pgClipartBackLeft2_c' => (!empty($this->data['clipart_c']['back'][1]['left'])?$this->data['clipart_c']['back'][1]['left']:0),
						'pgClipartBackRight2' => (!empty($this->data['clipart']['back'][1]['right'])?$this->data['clipart']['back'][1]['right']:0),
						'pgClipartBackRight2_c' => (!empty($this->data['clipart_c']['back'][1]['right'])?$this->data['clipart_c']['back'][1]['right']:0),
						'pg_x' => $x,
						'pg_y' => $y
						);

		$image = '';


		$filename = sha1(implode($flatimg)).'.png';
		$cfile = $filename;

		return $cfile;
	}





	function getCachedProductImageUrl(&$vars, $x=0, $y=0) {

		/*
		$pgType = $this->data['band_type'];
		$pgStyle = $this->data['band_style'];
		$pgFont = $this->data['band_font'];
		$pgBandColor = $this->data['band_color'];
		$pgMessageColor = $this->data['message_color'];
		$pgFrontMessage = $this->data['messages']['front'][0];
		$pgFrontMessage2 = $this->data['messages']['front'][1];
		$pgBackMessage = $this->data['messages']['back'][0];
		$pgBackMessage2 = $this->data['messages']['back'][1];

		$pgClipartFrontLeft = $this->data['clipart']['front'][0]['left'];
		$pgClipartFrontRight = $this->data['clipart']['front'][0]['right'];
		$pgClipartFrontLeft2 = $this->data['clipart']['front'][1]['left'];
		$pgClipartFrontRight2 = $this->data['clipart']['front'][1]['right'];
		$pgClipartBackLeft = $this->data['clipart']['back'][0]['left'];
		$pgClipartBackRight = $this->data['clipart']['back'][0]['right'];
		$pgClipartBackLeft2 = $this->data['clipart']['back'][1]['left'];
		$pgClipartBackRight2 = $this->data['clipart']['back'][1]['right'];

		$pgWidth = $x;
		$pgHeight = $y;

		$pgconf = compact(
								$pgType,
								$pgStyle,
								$pgFont,
								$pgBandColor,
								$pgMessageColor,
								$pgFrontMessage,
								$pgClipartFrontLeft,
								$pgClipartFrontRight,
								$pgFrontMessage2,
								$pgClipartFrontLeft2,
								$pgClipartFrontRight2,
								$pgBackMessage,
								$pgClipartBackLeft,
								$pgClipartBackRight,
								$pgBackMessage2,
								$pgClipartBackLeft2,
								$pgClipartBackRight2,
								$pgWidth,
								$pgHeight
								);

		*/
		$flatimg = array(
						'pgType'=>$this->data['band_type'],
						'pgStyle'=>$this->data['band_style'],
						'invert_dual'=>intval($this->data['invert_dual'], 10),
						'cut_away'=>intval($this->data['cut_away'], 10),
						'pgFont' => $this->data['band_font'],
						'pgBandColor' => $this->data['band_color'],
						'pgMessageColor' => $this->data['message_color'],
						'pgFrontMessage' => 'f1:'.(!empty($this->data['messages']['front'][0])?$this->data['messages']['front'][0]:''),
						'pgClipartFrontLeft' => (!empty($this->data['clipart']['front'][0]['left'])?$this->data['clipart']['front'][0]['left']:0),
						'pgClipartFrontLeft_c' => (!empty($this->data['clipart_c']['front'][0]['left'])?$this->data['clipart_c']['front'][0]['left']:0),
						'pgClipartFrontRight' => (!empty($this->data['clipart']['front'][0]['right'])?$this->data['clipart']['front'][0]['right']:0),
						'pgClipartFrontRight_c' => (!empty($this->data['clipart_c']['front'][0]['right'])?$this->data['clipart_c']['front'][0]['right']:0),
						'pgFrontMessage2' => 'f2:'.(!empty($this->data['messages']['front'][1])?$this->data['messages']['front'][1]:''),
						'pgClipartFrontLeft2' => (!empty($this->data['clipart']['front'][1]['left'])?$this->data['clipart']['front'][1]['left']:0),
						'pgClipartFrontLeft2_c' => (!empty($this->data['clipart_c']['front'][1]['left'])?$this->data['clipart_c']['front'][1]['left']:0),
						'pgClipartFrontRight2' => (!empty($this->data['clipart']['front'][1]['right'])?$this->data['clipart']['front'][1]['right']:0),
						'pgClipartFrontRight2_c' => (!empty($this->data['clipart_c']['front'][1]['right'])?$this->data['clipart_c']['front'][1]['right']:0),
						'pgBackMessage' => 'b1:'.(!empty($this->data['messages']['back'][0])?$this->data['messages']['back'][0]:''),
						'pgClipartBackLeft' => (!empty($this->data['clipart']['back'][0]['left'])?$this->data['clipart']['back'][0]['left']:0),
						'pgClipartBackLeft_c' => (!empty($this->data['clipart_c']['back'][0]['left'])?$this->data['clipart_c']['back'][0]['left']:0),
						'pgClipartBackRight' => (!empty($this->data['clipart']['back'][0]['right'])?$this->data['clipart']['back'][0]['right']:0),
						'pgClipartBackRight_c' => (!empty($this->data['clipart_c']['back'][0]['right'])?$this->data['clipart_c']['back'][0]['right']:0),
						'pgBackMessage2' => 'b2:'.(!empty($this->data['messages']['back'][1])?$this->data['messages']['back'][1]:''),
						'pgClipartBackLeft2' => (!empty($this->data['clipart']['back'][1]['left'])?$this->data['clipart']['back'][1]['left']:0),
						'pgClipartBackLeft2_c' => (!empty($this->data['clipart_c']['back'][1]['left'])?$this->data['clipart_c']['back'][1]['left']:0),
						'pgClipartBackRight2' => (!empty($this->data['clipart']['back'][1]['right'])?$this->data['clipart']['back'][1]['right']:0),
						'pgClipartBackRight2_c' => (!empty($this->data['clipart_c']['back'][1]['right'])?$this->data['clipart_c']['back'][1]['right']:0),
						'pg_x' => $x,
						'pg_y' => $y
						);
		/*
		$query = array(
						'type'=>'flat',
						'pgType'=>$this->data['band_type'],
						'pgStyle'=>$this->data['band_style'],
						'pgFont' => $this->data['band_font'],
						'pgBandColor' => $this->data['band_color'],
						'pgMessageColor' => $this->data['message_color'],
						'pgFrontMessage' => $this->data['messages']['front'][0],
						'pgClipartFrontLeft' => $this->data['clipart']['front'][0]['left'],
						'pgClipartFrontRight' => $this->data['clipart']['front'][0]['right'],
						'pgFrontMessage2' => $this->data['messages']['front'][1],
						'pgClipartFrontLeft2' => $this->data['clipart']['front'][1]['left'],
						'pgClipartFrontRight2' => $this->data['clipart']['front'][1]['right'],
						'pgBackMessage' => $this->data['messages']['back'][0],
						'pgClipartBackLeft' => $this->data['clipart']['back'][0]['left'],
						'pgClipartBackRight' => $this->data['clipart']['back'][0]['right'],
						'pgBackMessage2' => $this->data['messages']['back'][1],
						'pgClipartBackLeft2' => $this->data['clipart']['back'][1]['left'],
						'pgClipartBackRight2' => $this->data['clipart']['back'][1]['right'],
						'pg_x' => $x,
						'pg_y' => $y
						);
		*/
		$url = '';


		$filename = sha1(implode($flatimg)).'.png';
		//tpt_dump($flatimg, true);
		//tpt_dump($filename, true);
		$cfile = TPT_IMAGES_DIR.DIRECTORY_SEPARATOR.'preview'.DIRECTORY_SEPARATOR.'cached'.DIRECTORY_SEPARATOR.'flat'.DIRECTORY_SEPARATOR.$filename;
		if(is_file($cfile)) {
				//tpt_dump('asd');
				//tpt_dump($cfile);
				//header('Content-type: image/png');
				$url = TPT_IMAGES_URL.'/preview/cached/flat/'.$filename;
		} else {
				//tpt_dump('asdf');
				//tpt_dump($flatimg);
				//tpt_dump($cfile);
				//tpt_dump($vars['url']['handler']->wrap($vars, '/generate-preview').'?type=flat&'.$this->getDesignUrlQuery($vars).'&pg_x='.$x.'&pg_y='.$y);
				$url = $vars['url']['handler']->wrap($vars, '/generate-preview').'?type=flat&'.$this->getDesignUrlQuery($vars).'&pg_x='.$x.'&pg_y='.$y;
		}

		//tpt_dump($url, true);
		return $url;
	}
	
	function getCachedProductThumbUrl(&$vars, $x=0, $y=0) {
		return '';
	}
	
	

	function getSku(&$vars) {
		$style_module = getModule($vars, 'BandStyle');
		$types_module = getModule($vars, 'BandType');
		$sizes_module = getModule($vars, 'BandSize');
		$color_module = getModule($vars, 'BandColor');
		$data_module = getModule($vars, 'BandData');

		$asku = array();

		$asku[0] = 'OS';
		if(!empty($data_module->typeStyle[$this->data['band_type']][$this->data['band_style']]['pricing_type'])) {
			$asku[0] = 'IH';
		}

		if(!empty($data_module->typeStyle[$this->data['band_type']][$this->data['band_style']]['writable'])) {
				if($data_module->typeStyle[$this->data['band_type']][$this->data['band_style']]['writable_class'] == 5) {
					$asku[0] = 'IH';
				} else {
					$asku[0] = 'OS';
				}

		}

		$asku[1] = $style_module->moduleData['id'][$this->data['band_style']]['sku_comp'];
		$asku[2] = $types_module->moduleData['id'][$this->data['band_type']]['sku_comp'];
		if(!empty($this->pricingObject->options['key_chain'])) {
				$asku[2] .= '+CHAIN';
		}
		$asku[3] = $sizes_module->moduleData['id'][$this->data['band_size']]['sku_comp'];

		$asku[4] = $color_module->getSkuComponent($vars, $this->data['band_color']);
		$cp = $color_module->getColorProps($vars, $this->data['band_color']);
		//var_dump($cp);die();
		if($cp['dual_layer']) {
				$mid = $color_module->getDualLayerMessageId($vars, $this->data['band_color']);
				$asku[4] .= '+'.$color_module->getSkuComponent($vars, $mid, true);
		} else if($style_module->moduleData['id'][$this->data['band_style']]['message_color']) {
				$asku[4] .= '+'.$color_module->getSkuComponent($vars, $this->data['message_color'], true);
		}
		//var_dump($this->data);
		//var_dump($asku);die();

		return implode('-', $asku);
	}

	function getCartView(&$vars, $index=0) {
		global $tpt_vars;
		$product = $this;
		$tpt_resourceurl = TPT_RESOURCE_URL;

		$types_module = getModule($tpt_vars, 'BandType');
		$styles_module = getModule($tpt_vars, 'BandStyle');
		$styles = $styles_module->moduleData['id'];
		$data_module = getModule($tpt_vars, 'BandData');
//$colors_module = getModule($tpt_vars, "BandColor");
		$colors_module = getModule($tpt_vars, 'BandColor');
		$fonts_module = getModule($tpt_vars, 'BandFont');
		$fonts = $fonts_module->moduleData['id'];
		$rushorder_module = getModule($tpt_vars, 'RushOrder');
		$builder_module = getModule($tpt_vars, 'Builder');
		$cliparts_module = getModule($tpt_vars, 'BandClipart');
		$sbuilders = $builder_module->moduleData['id'];

		$w1 = '406px';
		$w2 = '84px';
		$w3 = '84px';
		$w4 = '98px';
		$w5 = '82px';

		$product_html = '';
//        $labels_width = '114px';
		$labels_width = '90px';
		$product_details = array();
		$product_details_l = array();
		$product_details_v = array();
		$updateform = '';
		$deleteform = '';
		$qty = 1;
//		$qtybgurl = TPT_IMAGES_URL . '/buttons/cart-qty-field.png';
		$qtybgurl = TPT_IMAGES_URL . '/cart-elem-spr.png';
		$bgurl = TPT_IMAGES_URL . '/cart-elem-spr.png';
		$bandSize = '&nbsp;';
		$bandColor = '&nbsp;';
		//tpt_dump($product->data['band_color'],true);
		//tpt_dump(getModule($tpt_vars, "BandColor")->getColorProps($vars, $product->data['band_color']),true);

		$productDesign = '';

		$name = '';
		$url = '';
		$addons = array();
		$imgurl = '';
		$subtotal = $product->price['html']['mbase_price'];
		//tpt_dump($product->price['html'], true);
		//var_dump($product);die();
		$sptype = array();
		$pdata = array();

		//$pclass = ''; vj edit

		//var_dump($product);die();
		$type = $product->data['band_type'];
		$style = $product->data['band_style'];
		$pdata = $data_module->typeStyle[$type][$style];
		$sku = str_replace('.', '<span class="display-inline-block"></span>.<span class="display-inline-block"></span>', $product->getSku($tpt_vars));

		$typeName = htmlentities(getModule($tpt_vars, "BandType")->moduleData['id'][$product->data['band_type']]['name']);
		if (!empty($types_module->moduleData['id'][$type]['writable'])) {
			$typeName = htmlentities(getModule($tpt_vars, "BandType")->moduleData['id'][$product->data['band_type']]['label2']);
		}
		$styleName = htmlentities(getModule($tpt_vars, "BandStyle")->moduleData['id'][$product->data['band_style']]['name']);

		if (isDev('devcart') && !empty($_GET['devcart'])) {
			$typeName .= ' (' . $product->data['band_type'] . ')';
			$styleName .= ' (' . $product->data['band_style'] . ')';
		}

		$name = $typeName . ', ' . $styleName;
		//$url = $tpt_vars['url']['handler']->wrap($tpt_vars, $product->data['product_url']);
		$descr = '';
		if (!empty($product->data['descr']))
			$descr = $product->data['descr'];


		if (!empty($product->pricingObject->options['glow_ink_fill'])) {
			$addons['glow_ink_fill'] = 'Add Glow-in-the-Dark Message';
		}
		if (!empty($product->data['invert_dual'])) {
			$addons['invert_dual'] = 'Inverted Message';
		}
		if (!empty($product->data['cut_away'])) {
			$addons['cut_away'] = 'Cut-Away Message';
		}
		if (!empty($product->pricingObject->options['key_chain'])) {
			$addons['key_chain'] = 'Make Into Keychain';
		}

		if (!empty($product->pricingObject->options['indvl_packaging'])) {
			$addons['indvl_packaging'] = 'Individual Packaging';
		}
		if (!empty($product->data['rush_order'])) {
			$addons['rush_order'] = 'Rush Order (' . $rushorder_module->moduleData['id'][$product->data['rush_order']]['label2'] . ')';
		}

		$img = '';
		if (empty($product->data['primg'])) {
			$timestamp = time();
			//var_dump($product->data['messages']);die();
			$UEfrontMessage = urlencode(reset($product->data['messages']['front']));
			$UEbandFont = urlencode($product->data['band_font']);

			$pType = $type;
			if (!empty($addons['key_chain'])) {
				$pType = 7;
			}
			$pgStyle = $style;

			//$imgurl = BASE_URL.'/generate-preview?text=%20&amp;font='.$UEbandFont.'&amp;bandType='.$pType.'&amp;bandStyle='.$style.'&amp;type=full&amp;timestamp='.$timestamp;
			$pgDir = $data_module->typeStyle[$pType][$pgStyle]['preview_folder'];
			//tpt_dump($pType);
			//tpt_dump($pgStyle);
			//tpt_dump($data_module->typeStyle[$pType][$pgStyle], true);
			//var_dump($product->data['band_type']);die();
			//var_dump($pgDir);die();
			$imgurl = TPT_IMAGES_URL . '/preview/' . $pgDir . '/band.png';
			if (!empty($types_module->moduleData['id'][$type]['writable'])) {
				if ($types_module->moduleData['id'][$type]['full_wrap_strip']) {
					$imgurl = TPT_IMAGES_URL . '/preview/' . $pgDir . '/band-writable2.png';
				} else {
					$imgurl = TPT_IMAGES_URL . '/preview/' . $pgDir . '/band-writable1.png';
				}
			}
			$img = '<img class="position-relative display-block z-index-2 max-width-100prc max-height-100prc" src="' . $imgurl . '" />';
			//var_dump($product->type);die();
			if ($product->data['band_type'] == 5) {
				$pgType = 5;
				$pgStyle = $product->data['band_style'];
				$pgFont = $product->data['band_font'];
				//$pgFrontRows = (!empty($pgFrontRows)?$pgFrontRows:1);
				//$pgBackRows = (!empty($pgBackRows)?$pgBackRows:1);
				//$pgTextCont = (!empty($pgTextCont)?intval($pgTextCont, 10):0);
				$pgBandColor = $product->data['band_color'];
				$pgMessageColor = $product->data['message_color'];
				$pgFrontMessage = !empty($product->data['messages']['front'][0]) ? $product->data['messages']['front'][0] : '';
				$pgFrontMessage2 = !empty($product->data['messages']['front'][1]) ? $product->data['messages']['front'][1] : '';
				$pgBackMessage = !empty($product->data['messages']['back'][0]) ? $product->data['messages']['back'][0] : '';
				$pgBackMessage2 = !empty($product->data['messages']['back'][1]) ? $product->data['messages']['back'][1] : '';

				$pgClipartFrontLeft = !empty($product->data['clipart']['front'][0]['left']) ? $product->data['clipart']['front'][0]['left'] : 0;
				$pgClipartFrontRight = !empty($product->data['clipart']['front'][0]['right']) ? $product->data['clipart']['front'][0]['right'] : 0;
				$pgClipartFrontLeft2 = !empty($product->data['clipart']['front'][1]['left']) ? $product->data['clipart']['front'][1]['left'] : 0;
				$pgClipartFrontRight2 = !empty($product->data['clipart']['front'][1]['right']) ? $product->data['clipart']['front'][1]['right'] : 0;
				$pgClipartBackLeft = !empty($product->data['clipart']['back'][0]['left']) ? $product->data['clipart']['back'][0]['left'] : 0;
				$pgClipartBackRight = !empty($product->data['clipart']['back'][0]['right']) ? $product->data['clipart']['back'][0]['right'] : 0;
				$pgClipartBackLeft2 = !empty($product->data['clipart']['back'][1]['left']) ? $product->data['clipart']['back'][1]['left'] : 0;
				$pgClipartBackRight2 = !empty($product->data['clipart']['front'][1]['right']) ? $product->data['clipart']['front'][1]['right'] : 0;

				$pgClipartFrontLeft_c = !empty($product->data['clipart_c']['front'][0]['left']) ? $product->data['clipart_c']['front'][0]['left'] : 0;
				$pgClipartFrontRight_c = !empty($product->data['clipart_c']['front'][0]['right']) ? $product->data['clipart_c']['front'][0]['right'] : 0;
				$pgClipartFrontLeft2_c = !empty($product->data['clipart_c']['front'][1]['left']) ? $product->data['clipart_c']['front'][1]['left'] : 0;
				$pgClipartFrontRight2_c = !empty($product->data['clipart_c']['front'][1]['right']) ? $product->data['clipart_c']['front'][1]['right'] : 0;
				$pgClipartBackLeft_c = !empty($product->data['clipart_c']['back'][0]['left']) ? $product->data['clipart_c']['back'][0]['left'] : 0;
				$pgClipartBackRight_c = !empty($product->data['clipart_c']['back'][0]['right']) ? $product->data['clipart_c']['back'][0]['right'] : 0;
				$pgClipartBackLeft2_c = !empty($product->data['clipart_c']['back'][1]['left']) ? $product->data['clipart_c']['back'][1]['left'] : 0;
				$pgClipartBackRight2_c = !empty($product->data['clipart_c']['front'][1]['right']) ? $product->data['clipart_c']['front'][1]['right'] : 0;

				$pgWidth = 450;
				$pgHeight = 60;
				$pgPaddingTop = 0;
				$pgPaddingBottom = 0;
				$pgPaddingLeft = 40;
				$pgPaddingRight = 40;

				$pgOutlineFile = 'plain450x60.png';

				$pgFullPreview = 0;
				$pgEnableJavascript = 0;
				$pgAjaxJavascript = 0;

				$pgconf = compact(
					'pgType',
					'pgStyle',
					'pgFont',
					'pgFrontRows',
					'pgBackRows',
					'pgTextCont',
					'pgBandColor',
					'pgMessageColor',
					'pgFrontMessage',
					'pgClipartFrontLeft',
					'pgClipartFrontRight',
					'pgClipartFrontLeft_c',
					'pgClipartFrontRight_c',
					'pgFrontMessage2',
					'pgClipartFrontLeft2',
					'pgClipartFrontRight2',
					'pgClipartFrontLeft2_c',
					'pgClipartFrontRight2_c',
					'pgBackMessage',
					'pgClipartBackLeft',
					'pgClipartBackRight',
					'pgClipartBackLeft_c',
					'pgClipartBackRight_c',
					'pgBackMessage2',
					'pgClipartBackLeft2',
					'pgClipartBackRight2',
					'pgClipartBackLeft2_c',
					'pgClipartBackRight2_c',
					'pgWidth',
					'pgHeight',
					'pgPaddingTop',
					'pgPaddingBottom',
					'pgFullPreview',
					'pgEnableJavascript',
					'pgAjaxJavascript',
					'pgOutlineFile'
				);
				//var_dump($pgconf);die();

				$preview = '';
				include(TPT_TEMPLATES_DIR . DIRECTORY_SEPARATOR . 'builder-preview.tpt.php');
				$img = <<< EOT
				<div class="position-relative">
					<div class="position-absolute right-0">
						$preview
					</div>
				</div>
EOT;
				//$imgurl = BASE_URL.'/generate-preview?text='.$UEfrontMessage.'&amp;font='.$UEbandFont.'&amp;bandType='.$type.'&amp;bandStyle='.$style.'&amp;type=plain&amp;timestamp='.$timestamp;
			}
			//'.$UEfrontMessage.'

//            } elseif(is_a($product, 'amz_customStoreproduct')) {
//                $imgurl = '/images/'.$product->data['image_filename'];
//                $img = '<img class="position-relative"  src="'.$imgurl.'" />';

		} else {
			$imgurl = $product->data['primg'];
			$img = '<img class="position-relative z-index-2 max-width-100prc  max-height-100prc" src="' . $imgurl . '" />';
		}

		//$product->pricingObject->getPrice();
		//var_dump($product->pricingObject->price);die();
		//var_dump($product->price);die();
		//var_dump($product->price['html']['mbase_price']);die();
		$subtotal = $product->price['html']['mbase_price'];
		if (!$product->pricingObject->pricingType/* && (((amz_cart::$totals['pricing']['values']['oscount'] > 1) && (amz_cart::$totals['pricing']['values']['osc'] < 1)) || (!empty($ordercart) && ($totALL['pricing']['values']['osc'] < 1) && ($totALL['pricing']['values']['oscount'] > 1)))*/) {
			//var_dump($product->pricingObject->price['html']);die();
			$subtotal = '<div class="text-decoration-line-through">' . $product->pricingObject->price['html']['lowest_price_total'] . '</div><div>' . $subtotal . '</div>';
		} else {
			//var_dump($product->pricingObject->price['html']);die();
			$subtotal = '<div class="text-decoration-line-through">' . $product->pricingObject->price['html']['lowest_price_total'] . '</div><div>' . $subtotal . '</div>';
		}

		//var_dump($product->data);die();

		$qty = '<div class="display-inline-block width-70 height-16 padding-top-2 padding-bottom-7 padding-left-3 padding-right-2" style="background-position: 0px -185px; background-image: url(' . $qtybgurl . ');">' . tpt_html::createTextinput($tpt_vars, 'qty', $product->qty, ' class="amz_red width-70 line-height-24 plain-input-field text-align-center"') . '</div>';

		$pg_x = 115;
		$pg_y = 61;
		$previewtime = time();
		$bandbg = '';
		if (isset($product->data['band_color'])) {
			$bandbg = $colors_module->getBandBGStyle($tpt_vars, $product->data['band_color'], $product->data['message_color'], $pg_x, $pg_y);
		}

		if (is_a($product, 'amz_customStockproduct')) {
			$img = '<img class="position-relative z-index-2 max-width-100prc max-height-100prc" src="' . $tmgurl . '" />';
		}

		$image = <<< EOT
<div style="background-color: #F0F0F0;" class="itemid_$index shopping_cart_product_small_preview width-115 height-70 position-relative padding-top-5 padding-bottom-5 overflow-hidden">

	<a class="thickbox winrf_sc_product_big_preview" href="#TB_inline?width=800&amp;height=200&amp;inlineId=_">Open Big Preview</a>

    <div class="position-absolute top-0 left-0 right-0 bottom-0 z-index-3" style="background-image: url($bgurl);"></div>
    <div style="$bandbg;">
        $img
    </div>
</div>
EOT;
		//var_dump($product->price);die();
//big preview popup
		//if($_SERVER['REMOTE_ADDR'] != '109.160.0.218') {
		//$big_preview='<div class="targetid_'.$index.' cart_big_preview">'.$product->getPreviewHTML($tpt_vars).'</div>';
		//} else {
		$banddesignurl = $product->getCachedProductImageUrl($tpt_vars);
		//tpt_dump($banddesignurl, true);
		$big_preview = '<div class="targetid_' . $index . ' cart_big_preview">
                    <img src="' . $banddesignurl . '" />
                </div>';
		$prevHeight = $data_module->typeStyle[$product->data['band_type']][$product->data['band_style']]['preview_height'];
		$productDesign = <<< EOT
<div class="padding-left-5 padding-right-5 padding-top-5 padding-bottom-5">
    <div class="position-relative background-repeat-no-repeat background-position-CC height-$prevHeight z-index-3" style="border: 1px solid #5B3824; background-image: url($banddesignurl);">
    </div>
</div>
EOT;
		$productDesign = <<< EOT
<div class="position-relative padding-left-5 padding-right-5 padding-top-0 padding-bottom-5 text-align-center z-index-3">
    <img src="$banddesignurl" style="border: 1px solid #5B3824;" class="resize" />
</div>
EOT;

		if (is_a($product, 'amz_customStockproduct')) $productDesign = str_replace('text-align-center', 'text-align-left', $productDesign);

		//$productDesign = '';
		//}

		$image .= $big_preview;

		$oddclass = '';
		$evenclass = ' style="background-color: #f2f2f2;"';

		$rowclass = $evenclass;


		if (!empty($secretcart) || (!empty($_GET['debug']) && ($_GET['debug'] == 'delcache'))) {
			//if($_GET['grr']=='grr' || true || false) {
			$rowclass = ($rowclass == $oddclass ? $evenclass : $oddclass);

			$productdesignimage = $tpt_vars['url']['handler']->wrap($tpt_vars, '/generate-preview') . '?type=flat&' . $product->getDesignUrlQuery($tpt_vars);
			$productdesignimage = '<a href="' . $productdesignimage . '" title="' . $productdesignimage . '">Flat Preview</a>';
			$product_details[] = '<div class="padding-top-2 padding-bottom-2"' . $rowclass . '><div class="font-weight-bold float-left text-align-right padding-right-5 urlabel width-80">Flat Img:</div><div class="amz_red overflow-hidden">' . $productdesignimage . '</div></div>';
			$product_details_l[] = 'Product Flat Preview:';
			$product_details_v[] = $productdesignimage;
			//}


			$rowclass = ($rowclass == $oddclass ? $evenclass : $oddclass);

			$productdesignlink = $tpt_vars['url']['handler']->wrap($tpt_vars, '/get-preview') . '?' . $product->getDesignUrlQuery2($tpt_vars);
			$productdesignlink = '<a href="' . $productdesignlink . '" title="' . $productdesignlink . '">Html Preview</a>';
			$product_details[] = '<div class="padding-top-2 padding-bottom-2"' . $rowclass . '><div class="font-weight-bold float-left text-align-right padding-right-5 urlabel width-80">Layered Img:</div><div class="amz_red overflow-hidden">' . $productdesignlink . '</div></div>';
			$product_details_l[] = 'Product Layered Preview:';
			$product_details_v[] = $productdesignlink;


			$rowclass = ($rowclass == $oddclass ? $evenclass : $oddclass);


			$qry = $tpt_vars['url']['qry'];
			$qry['delcached'] = $product->getCachedImageName($tpt_vars);
			$delcachedlink = $tpt_vars['url']['handler']->wrap($tpt_vars, $tpt_vars['url']['upath']) . '?' . http_build_query($qry);
			$delcachedlink = <<< EOT
            <a class="plain-link" href="$delcachedlink" title="Refresh this product&quot;s cached flat image">Refresh cached image</a>
EOT;
			$product_details[] = '<div class="padding-top-2 padding-bottom-2"' . $rowclass . '><div class="font-weight-bold float-left text-align-right padding-right-5 urlabel width-80" >RefCch:</div><div class="amz_red overflow-hidden">' . $delcachedlink . '</div></div>';
			$product_details_l[] = 'Product Layered Preview:';
			$product_details_v[] = $productdesignlink;
		}

		if (!empty($sku)) {
			$rowclass = ($rowclass == $oddclass ? $evenclass : $oddclass);
			$product_details[] = '<div class="padding-top-2 padding-bottom-2"' . $rowclass . '><div class="font-weight-bold float-left padding-right-5 urlabel padding-left-10 width-30">ID</div><div class="font-weight-bold float-left padding-right-5 width-2 ">:</div><div id="order-details-id" class="amz_red overflow-hidden">' . $sku . '</div></div>';
			$product_details_l[] = 'ID:';
			$product_details_v[] = $sku;
		}

		if (!empty($typeName)) {
			$rowclass = ($rowclass == $oddclass ? $evenclass : $oddclass);

			$product_details[] = '<div class="padding-top-2 padding-bottom-2"' . $rowclass . '><div class="font-weight-bold float-left padding-right-5 urlabel padding-left-10 width-30">Type</div><div class="font-weight-bold float-left padding-right-5 width-2 ">:</div><div class="amz_red overflow-hidden">' . $typeName . '</div></div>';
			$product_details_l[] = 'Type:';
			$product_details_v[] = getModule($tpt_vars, "BandType")->moduleData['id'][$product->data['band_type']]['name'];
		}

		if (!empty($styleName)) {
			$rowclass = ($rowclass == $oddclass ? $evenclass : $oddclass);

			$product_details[] = '<div class="padding-top-2 padding-bottom-2"' . $rowclass . '><div class="font-weight-bold float-left padding-right-5 urlabel padding-left-10 width-30">Style</div><div class="font-weight-bold float-left padding-right-5 width-2">:</div><div class="amz_red overflow-hidden">' . $styleName . '</div></div>';
			$product_details_l[] = 'Style:';
			$product_details_v[] = getModule($tpt_vars, "BandStyle")->moduleData['id'][$product->data['band_style']]['name'];
		}

		if (is_a($product, 'amz_bundle')) {
			$rowclass = ($rowclass == $oddclass ? $evenclass : $oddclass);

			$productsNames = array();
			foreach ($product->pids as $pid) {
				$productsNames[] = amz_cart::$stockProductsData[$pid]['label'];
			}
			$productsNames = implode('<br /><br />', $productsNames);

			$product_details[] = '<div class="padding-top-2 padding-bottom-2 clearBoth"' . $rowclass . '><div class="font-weight-bold float-left text-align-right padding-right-5 urlabel" style="width: ' . $labels_width . ';">Desc:</div><div class="amz_red overflow-hidden">' . $productsNames . '</div></div>';
			$product_details_l[] = 'Products:';
			$product_details_v[] = $productsNames;
		} else if (!empty($name)) {
			if (!is_a($product, 'amz_customproduct')) {
				$rowclass = ($rowclass == $oddclass ? $evenclass : $oddclass);

				$product_details[] = '<div class="padding-top-2 padding-bottom-2 clearBoth"' . $rowclass . '><div class="font-weight-bold float-left text-align-right padding-right-5 urlabel" style="width: ' . $labels_width . ';">Desc:</div><div class="amz_red overflow-hidden">' . $name . '</div></div>';
				$product_details_l[] = 'Desc:';
				$product_details_v[] = $name;
			}
		}

		//var_dump($product->data['messages']);die();

		if (is_a($product, 'amz_customproduct')) {
			if (empty($pdata['blank'])) {
				if (!empty($product->data['band_font'])) {
					$font = array_filter(explode('.', (!empty($fonts[$product->data['band_font']])?$fonts[$product->data['band_font']]['file']:'')));
					array_pop($font);
					$font = ucfirst(preg_replace('#[\W_]+#', ' ', implode('.', $font)));
					$rowclass = ($rowclass == $oddclass ? $evenclass : $oddclass);

					$product_details[] = '<div class="padding-top-2 padding-bottom-2 clearBoth"' . $rowclass . '><div class="font-weight-bold float-left padding-left-20 urlabel" style="width: ' . $labels_width . ';">Font</div><div class="font-weight-bold float-left padding-right-5 urlabel">:</div><div class="amz_red overflow-hidden">' . $font . '</div></div>';
					$product_details_l[] = 'Font:';
					$product_details_v[] = $product->data['band_font'];
				}
			}
		}

		//tpt_dump($product);
		if (is_a($product, 'amz_customproduct')) {

			//tpt_dump($product->data['message_color']);
			//tpt_dump($pdata['message_color']);
			//tpt_dump($pdata['blank']);
			//if ((!empty($product->data['message_color']) && empty($pdata['blank']) && !empty($pdata['message_color']))) {
			if ((!empty($product->data['message_color']) && !empty($pdata['message_color']) && empty($pdata['blank']))) {

				$messageColor = explode(':', $product->data['message_color']);
//			$mcprops = getModule($tpt_vars, "BandColor")->getColorProps($tpt_vars, $product->data['message_color']);
				$mcprops = getModule($tpt_vars, 'BandColor')->getColorProps($tpt_vars, $product->data['message_color']);
				$tableId = $messageColor[0];
				$colorId = $messageColor[1];
				if (($tableId == 0) || ($tableId == 1) || ($tableId == 2)) {
//				$messageColor = getModule($tpt_vars, "BandColor")->getCustomColorId($tpt_vars, $product->data['message_color']);
					$messageColor = getModule($tpt_vars, 'BandColor')->getCustomColorId($tpt_vars, $product->data['message_color']);
					$messageColor = str_replace('<br />', '', $messageColor);
				} else {
					if (
						//	!empty(getModule($tpt_vars, "BandColor")->all_colors[$tableId])
						!empty(getModule($tpt_vars, 'BandColor')->all_colors[$tableId])
						&&
						//	!empty(getModule($tpt_vars, "BandColor")->all_colors[$tableId][$colorId])
						!empty(getModule($tpt_vars, 'BandColor')->all_colors[$tableId][$colorId])
					) {
						//	$messageColor = getModule($tpt_vars, "BandColor")->all_colors[$tableId][$colorId]['label'];
						$messageColor = getModule($tpt_vars, 'BandColor')->all_colors[$tableId][$colorId]['label'];
					} else {
						$messageColor = '#' . $colorId;
					}
				}
				$messageColorCategory = $mcprops['colorcategory'];

				if (isDev('devcart') && !empty($_GET['devcart'])) {
					$messageColor .= ' (' . $product->data['message_color'] . ')';
				}


				$rowclass = ($rowclass == $oddclass ? $evenclass : $oddclass);

				$product_details[] = '<div class="padding-top-2 padding-bottom-2 clearBoth"' . $rowclass . '><div class="font-weight-bold float-left padding-left-20 urlabel" style="width: ' . $labels_width . ';">Message Color</div><div class="font-weight-bold float-left padding-right-5 urlabel" style="width:2px;">:</div><div class="amz_red overflow-hidden">' . $messageColor . '</div></div>';
				$product_details_l[] = 'Message Color:';
				$product_details_v[] = $messageColor;
			}

		}
		if ($_SERVER['REMOTE_ADDR'] == '182.65.198.99') { //vj edits 85.130.71.163
			//$flclipart_img = getModule($tpt_vars, "BandClipart")->getClipartImage($tpt_vars, $product->data['clipart']['front'][0]['left']);
			//print('<pre>');print_r($flclipart_img);print_r($product->data);print('</pre>');
			print('<pre>');
			//print_r($product->data['message_color']);
			//print_r($pdata['blank']);
			//print_r($bdata['message_color']);
			print('</pre>');
		}
		//var_dump($product->data);die();


		if (empty($pdata['blank'])) {
			if (!empty($product->data['messages']['front'][0])) {
				$fmessage = htmlentities($product->data['messages']['front'][0]);
				$rowclass = ($rowclass == $oddclass ? $evenclass : $oddclass);

				$product_details[] = '<div class="padding-top-2 padding-bottom-2 clearBoth"' . $rowclass . '><div class="font-weight-bold float-left padding-left-20 urlabel" style="width: ' . $labels_width . ';">Front Message</div><div class="font-weight-bold float-left padding-right-5 urlabel" style="width:2px;">:</div><div class="amz_red overflow-hidden font-size-14 font-weight-bold">' . $fmessage . '</div></div>';
				$product_details_l[] = 'Front message:';
				$product_details_v[] = $fmessage;
			}

			if (!empty($product->data['clipart']['front'][0]['left'])) {
				$flclipart_path = getModule($tpt_vars, "BandClipart")->getClipartURL($tpt_vars, $product->data['clipart']['front'][0]['left']);
				$flclipart_e = array_filter(explode('/', $flclipart_path));
				if (!empty($flclipart_e) && isset($flclipart_e[count($flclipart_e) - 1])) {
					$flclipart_e = array_filter(explode('.', $flclipart_e[count($flclipart_e) - 1]));
					array_pop($flclipart_e);
					$flclipart = htmlentities(preg_replace('#[\W_]+#', ' ', implode('.', $flclipart_e)));
					$rowclass = ($rowclass == $oddclass ? $evenclass : $oddclass);

					$product_details[] = '<div class="padding-top-2 padding-bottom-2 clearBoth"' . $rowclass . '><div class="font-weight-bold float-left padding-left-20 urlabel" style="width: ' . $labels_width . ';">Front Art Left</div><div class="font-weight-bold float-left padding-right-5 urlabel">:</div><div class="amz_red overflow-hidden">' . $flclipart . '</div><div class="height-50 text-align-center max-width-100prc max-height-100prc"><img src="' . CLIPARTS_URL . DIRECTORY_SEPARATOR . $flclipart_path . '" /></div></div>';
					$product_details_l[] = 'Front Art Left:';
					$product_details_v[] = $flclipart;
				}
			}

			if (!empty($product->data['clipart_c']['front'][0]['left'])) {
				/*
				$flclipart_path = getModule($tpt_vars, "BandClipart")->getClipartURL($tpt_vars, $product->data['clipart']['front'][0]['left']);
				$flclipart_e = array_filter(explode('/', $flclipart_path));
				if (!empty($flclipart_e)) {
					$flclipart_e = array_filter(explode('.', $flclipart_e[count($flclipart_e) - 1]));
					array_pop($flclipart_e);
					$flclipart = htmlentities(preg_replace('#[\W_]+#', ' ', implode('.', $flclipart_e)));
					$rowclass = ($rowclass == $oddclass ? $evenclass : $oddclass);

					$product_details[] = '<div class="padding-top-2 padding-bottom-2 clearBoth"' . $rowclass . '><div class="font-weight-bold float-left text-align-right padding-right-5" style="width: ' . $labels_width . ';">Front Art Left:</div><div class="amz_red overflow-hidden">' . $flclipart . '</div><div class="height-50 text-align-center"><img class="max-width-100prc max-height-100prc" src="' . $tpt_resourceurl . '/clipart/' . $flclipart_path . '" /></div></div>';
					$product_details_l[] = 'Front Art Left:';
					$product_details_v[] = $flclipart;
				}
				*/

				$flclipart_c = $product->data['clipart_c']['front'][0]['left'];
				$product_details[] = '<div class="padding-top-2 padding-bottom-2 clearBoth"' . $rowclass . '><div class="font-weight-bold float-left padding-left-20 urlabel" style="width: ' . $labels_width . ';">Front Custom Art Left</div><div class="font-weight-bold float-left padding-right-5 urlabel">:</div><div class="amz_red overflow-hidden">' . $flclipart_c . '</div><div class="height-50 text-align-center"><img class="max-width-99prc max-height-100prc" src="' . $cliparts_module->getConvertCustomArtImageUrl($tpt_vars, $flclipart_c, '80', '80', 'png') .'" /></div></div>';
				$product_details_l[] = 'Front Custom Art Left:';
				$product_details_v[] = $flclipart_c;
			}

			if (!empty($product->data['clipart']['front'][0]['right'])) {
				$frclipart_path = getModule($tpt_vars, "BandClipart")->getClipartURL($tpt_vars, $product->data['clipart']['front'][0]['right']);
				$frclipart_e = array_filter(explode('/', $frclipart_path));
				if (!empty($frclipart_e) && isset($frclipart_e[count($frclipart_e) - 1])) {
					$frclipart_e = array_filter(explode('.', $frclipart_e[count($frclipart_e) - 1]));
					array_pop($frclipart_e);
					$frclipart = htmlentities(preg_replace('#[\W_]+#', ' ', implode('.', $frclipart_e)));
					$rowclass = ($rowclass == $oddclass ? $evenclass : $oddclass);

					$product_details[] = '<div class="padding-top-2 padding-bottom-2 clearBoth"' . $rowclass . '><div class="font-weight-bold float-left padding-left-20 urlabel" style="width: ' . $labels_width . ';">Front Art Right</div><div class="font-weight-bold float-left padding-right-5 urlabel">:</div><div class="amz_red overflow-hidden">' . $frclipart . '</div><div class="height-50 text-align-center"><img class="max-width-98prc max-height-100prc" src="' . CLIPARTS_URL . DIRECTORY_SEPARATOR . $frclipart_path . '" /></div></div>';
					$product_details_l[] = 'Front Art Right:';
					$product_details_v[] = $frclipart;
				}
			}

			if (!empty($product->data['clipart_c']['front'][0]['right'])) {
				/*
				$frclipart_path = getModule($tpt_vars, "BandClipart")->getClipartURL($tpt_vars, $product->data['clipart']['front'][0]['right']);
				$frclipart_e = array_filter(explode('/', $frclipart_path));
				if (!empty($frclipart_e)) {
					$frclipart_e = array_filter(explode('.', $frclipart_e[count($frclipart_e) - 1]));
					array_pop($frclipart_e);
					$frclipart = htmlentities(preg_replace('#[\W_]+#', ' ', implode('.', $frclipart_e)));
					$rowclass = ($rowclass == $oddclass ? $evenclass : $oddclass);

					$product_details[] = '<div class="padding-top-2 padding-bottom-2 clearBoth"' . $rowclass . '><div class="font-weight-bold float-left text-align-right padding-right-5" style="width: ' . $labels_width . ';">Front Art Right:</div><div class="amz_red overflow-hidden">' . $frclipart . '</div><div class="height-50 text-align-center"><img class="max-width-100prc max-height-100prc" src="' . $tpt_resourceurl . '/clipart/' . $frclipart_path . '" /></div></div>';
					$product_details_l[] = 'Front Art Right:';
					$product_details_v[] = $frclipart;
				}
				*/

				$frclipart_c = $product->data['clipart_c']['front'][0]['right'];
				$product_details[] = '<div class="padding-top-2 padding-bottom-2 clearBoth"' . $rowclass . '><div class="font-weight-bold float-left padding-left-20 urlabel" style="width: ' . $labels_width . ';">Front Custom Art Right</div><div class="font-weight-bold float-left padding-right-5 urlabel">:</div><div class="amz_red overflow-hidden">' . $frclipart_c . '</div><div class="height-50 text-align-center"><img class="max-width-97prc max-height-100prc" src="' . $cliparts_module->getConvertCustomArtImageUrl($tpt_vars, $frclipart_c, '80', '80', 'png') .'" /></div></div>';
				$product_details_l[] = 'Front Custom Art Right:';
				$product_details_v[] = $frclipart_c;
			}


			if (!empty($product->data['messages']['front']['1'])) {
				$fmessage2 = htmlentities($product->data['messages']['front']['1']);
				$rowclass = ($rowclass == $oddclass ? $evenclass : $oddclass);

				$product_details[] = '<div class="padding-top-2 padding-bottom-2 clearBoth"' . $rowclass . '><div class="font-weight-bold float-left padding-left-20 urlabel" style="width: ' . $labels_width . ';">Front Message Ln2</div><div class="font-weight-bold float-left padding-right-5 urlabel">:</div><div class="amz_red overflow-hidden font-size-14 font-weight-bold">' . $fmessage2 . '</div></div>';
				$product_details_l[] = 'Front message Ln2:';
				$product_details_v[] = $fmessage2;
			}

			if (!empty($product->data['clipart']['front'][1]['left'])) {
				$flclipart2_path = getModule($tpt_vars, "BandClipart")->getClipartURL($tpt_vars, $product->data['clipart']['front'][1]['left']);
				$flclipart2_e = array_filter(explode('/', $flclipart2_path));
				if (!empty($flclipart2_e) && isset($flclipart2_e[count($flclipart2_e) - 1])) {
					$flclipart2_e = array_filter(explode('.', $flclipart2_e[count($flclipart2_e) - 1]));
					array_pop($flclipart2_e);
					$flclipart2 = htmlentities(preg_replace('#[\W_]+#', ' ', implode('.', $flclipart2_e)));
					$rowclass = ($rowclass == $oddclass ? $evenclass : $oddclass);

					$product_details[] = '<div class="padding-top-2 padding-bottom-2 clearBoth"' . $rowclass . '><div class="font-weight-bold float-left padding-left-20 urlabel" style="width: ' . $labels_width . ';">Front Art Left Ln2</div><div class="font-weight-bold float-left padding-right-5 urlabel">:</div><div class="amz_red overflow-hidden">' . $flclipart2 . '</div><div class="height-50 text-align-center"><img class="max-width-96prc max-height-100prc" src="' . CLIPARTS_URL . DIRECTORY_SEPARATOR . $flclipart2_path . '" /></div></div>';
					$product_details_l[] = 'Front Art Left Ln2:';
					$product_details_v[] = $flclipart2;
				}
			}

			if (!empty($product->data['clipart_c']['front'][1]['left'])) {
				/*
				$flclipart2_path = getModule($tpt_vars, "BandClipart")->getClipartURL($tpt_vars, $product->data['clipart']['front'][1]['left']);
				$flclipart2_e = array_filter(explode('/', $flclipart2_path));
				if (!empty($flclipart2_e)) {
					$flclipart2_e = array_filter(explode('.', $flclipart2_e[count($flclipart2_e) - 1]));
					array_pop($flclipart2_e);
					$flclipart2 = htmlentities(preg_replace('#[\W_]+#', ' ', implode('.', $flclipart2_e)));
					$rowclass = ($rowclass == $oddclass ? $evenclass : $oddclass);

					$product_details[] = '<div class="padding-top-2 padding-bottom-2 clearBoth"' . $rowclass . '><div class="font-weight-bold float-left text-align-right padding-right-5" style="width: ' . $labels_width . ';">Front Art Left Ln2:</div><div class="amz_red overflow-hidden">' . $flclipart2 . '</div><div class="height-50 text-align-center"><img class="max-width-100prc max-height-100prc" src="' . $tpt_resourceurl . '/clipart/' . $flclipart2_path . '" /></div></div>';
					$product_details_l[] = 'Front Art Left Ln2:';
					$product_details_v[] = $flclipart2;
				}
				*/

				$flclipart2_c = $product->data['clipart_c']['front'][1]['left'];
				$product_details[] = '<div class="padding-top-2 padding-bottom-2 clearBoth"' . $rowclass . '><div class="font-weight-bold float-left padding-left-20 urlabel" style="width: ' . $labels_width . ';">Front Custom Art Left Ln2</div><div class="font-weight-bold float-left padding-right-5 urlabel">:</div><div class="amz_red overflow-hidden">' . $flclipart2_c . '</div><div class="height-50 text-align-center"><img class="max-width-95prc max-height-100prc" src="' . $cliparts_module->getConvertCustomArtImageUrl($tpt_vars, $flclipart2_c, '80', '80', 'png') .'" /></div></div>';
				$product_details_l[] = 'Front Custom Art Left Ln2:';
				$product_details_v[] = $flclipart2_c;
			}

			if (!empty($product->data['clipart']['front'][1]['right'])) {
				$frclipart2_path = getModule($tpt_vars, "BandClipart")->getClipartURL($tpt_vars, $product->data['clipart']['front'][1]['right']);
				$frclipart2_e = array_filter(explode('/', $frclipart2_path));
				if (!empty($frclipart2_e) && isset($frclipart2_e[count($frclipart2_e) - 1])) {
					$frclipart2_e = array_filter(explode('.', $frclipart2_e[count($frclipart2_e) - 1]));
					array_pop($frclipart2_e);
					$frclipart2 = htmlentities(preg_replace('#[\W_]+#', ' ', implode('.', $frclipart2_e)));
					$rowclass = ($rowclass == $oddclass ? $evenclass : $oddclass);

					$product_details[] = '<div class="padding-top-2 padding-bottom-2 clearBoth"' . $rowclass . '><div class="font-weight-bold float-left padding-left-20 urlabel" style="width: ' . $labels_width . ';">Front Art Right Ln2</div><div class="font-weight-bold float-left padding-right-5 urlabel">:</div><div class="amz_red overflow-hidden">' . $frclipart2 . '</div><div class="height-50 text-align-center"><img class="max-width-94prc max-height-100prc" src="' . CLIPARTS_URL . DIRECTORY_SEPARATOR . $frclipart2_path . '" /></div></div>';
					$product_details_l[] = 'Front Art Right Ln2:';
					$product_details_v[] = $frclipart2;
				}
			}

			if (!empty($product->data['clipart_c']['front'][1]['right'])) {
				/*
				$frclipart2_path = getModule($tpt_vars, "BandClipart")->getClipartURL($tpt_vars, $product->data['clipart']['front'][1]['right']);
				$frclipart2_e = array_filter(explode('/', $frclipart2_path));
				if (!empty($frclipart2_e)) {
					$frclipart2_e = array_filter(explode('.', $frclipart2_e[count($frclipart2_e) - 1]));
					array_pop($frclipart2_e);
					$frclipart2 = htmlentities(preg_replace('#[\W_]+#', ' ', implode('.', $frclipart2_e)));
					$rowclass = ($rowclass == $oddclass ? $evenclass : $oddclass);

					$product_details[] = '<div class="padding-top-2 padding-bottom-2 clearBoth"' . $rowclass . '><div class="font-weight-bold float-left text-align-right padding-right-5" style="width: ' . $labels_width . ';">Front Art Right Ln2:</div><div class="amz_red overflow-hidden">' . $frclipart2 . '</div><div class="height-50 text-align-center"><img class="max-width-100prc max-height-100prc" src="' . $tpt_resourceurl . '/clipart/' . $frclipart2_path . '" /></div></div>';
					$product_details_l[] = 'Front Art Right Ln2:';
					$product_details_v[] = $frclipart2;
				}
				*/

				$frclipart2_c = $product->data['clipart_c']['front'][1]['right'];
				$product_details[] = '<div class="padding-top-2 padding-bottom-2 clearBoth"' . $rowclass . '><div class="font-weight-bold float-left padding-left-20 urlabel" style="width: ' . $labels_width . ';">Front Custom Art Right Ln2</div><div class="font-weight-bold float-left padding-right-5 urlabel">:</div><div class="amz_red overflow-hidden">' . $frclipart2_c . '</div><div class="height-50 text-align-center"><img class="max-width-93prc max-height-100prc" src="' . $cliparts_module->getConvertCustomArtImageUrl($tpt_vars, $frclipart2_c, '80', '80', 'png') .'" /></div></div>';
				$product_details_l[] = 'Front Custom Art Right Ln2:';
				$product_details_v[] = $frclipart2_c;
			}

			//var_dump($flclipart_e);
			//var_dump($frclipart_e);


			if (!empty($product->data['messages']['back'][0])) {
				$bmessage = htmlentities($product->data['messages']['back'][0]);
				$rowclass = ($rowclass == $oddclass ? $evenclass : $oddclass);

				$product_details[] = '<div class="padding-top-2 padding-bottom-2 clearBoth"' . $rowclass . '><div class="font-weight-bold float-left padding-left-20 urlabel" style="width: ' . $labels_width . ';">Back Message</div><div class="font-weight-bold float-left padding-right-5 urlabel">:</div><div class="amz_red overflow-hidden font-size-14 font-weight-bold">' . $bmessage . '</div></div>';
				$product_details_l[] = 'Back message:';
				$product_details_v[] = $bmessage;
			}


			if (!empty($product->data['clipart']['back'][0]['left'])) {
				$blclipart_path = getModule($tpt_vars, "BandClipart")->getClipartURL($tpt_vars, $product->data['clipart']['back'][0]['left']);
				$blclipart_e = array_filter(explode('/', $blclipart_path));
				if (!empty($blclipart_e) && isset($blclipart_e[count($blclipart_e) - 1])) {
					$blclipart_e = array_filter(explode('.', $blclipart_e[count($blclipart_e) - 1]));
					array_pop($blclipart_e);
					$blclipart = htmlentities(preg_replace('#[\W_]+#', ' ', implode('.', $blclipart_e)));
					$rowclass = ($rowclass == $oddclass ? $evenclass : $oddclass);

					$product_details[] = '<div class="padding-top-2 padding-bottom-2 clearBoth"' . $rowclass . '><div class="font-weight-bold float-left padding-left-20 urlabel" style="width: ' . $labels_width . ';">Back Art Left</div><div class="font-weight-bold float-left padding-right-5 urlabel">:</div><div class="amz_red overflow-hidden">' . $blclipart . '</div><div class="height-50 text-align-center"><img class="max-width-100prc max-height-92prc" src="' . CLIPARTS_URL . DIRECTORY_SEPARATOR . $blclipart_path . '" /></div></div>';
					$product_details_l[] = 'Back Art Left:';
					$product_details_v[] = $blclipart;
				}
			}

			if (!empty($product->data['clipart_c']['back'][0]['left'])) {
				/*
				$blclipart_path = getModule($tpt_vars, "BandClipart")->getClipartURL($tpt_vars, $product->data['clipart']['back'][0]['left']);
				$blclipart_e = array_filter(explode('/', $blclipart_path));
				if (!empty($blclipart_e)) {
					$blclipart_e = array_filter(explode('.', $blclipart_e[count($blclipart_e) - 1]));
					array_pop($blclipart_e);
					$blclipart = htmlentities(preg_replace('#[\W_]+#', ' ', implode('.', $blclipart_e)));
					$rowclass = ($rowclass == $oddclass ? $evenclass : $oddclass);

					$product_details[] = '<div class="padding-top-2 padding-bottom-2 clearBoth"' . $rowclass . '><div class="font-weight-bold float-left text-align-right padding-right-5" style="width: ' . $labels_width . ';">Back Art Left:</div><div class="amz_red overflow-hidden">' . $blclipart . '</div><div class="height-50 text-align-center"><img class="max-width-100prc max-height-100prc" src="' . $tpt_resourceurl . '/clipart/' . $blclipart_path . '" /></div></div>';
					$product_details_l[] = 'Back Art Left:';
					$product_details_v[] = $blclipart;
				}
				*/

				$blclipart_c = $product->data['clipart_c']['back'][0]['left'];
				$product_details[] = '<div class="padding-top-2 padding-bottom-2 clearBoth"' . $rowclass . '><div class="font-weight-bold float-left padding-left-20 urlabel" style="width: ' . $labels_width . ';">Back Custom Art Left</div><div class="font-weight-bold float-left padding-right-5 urlabel">:</div><div class="amz_red overflow-hidden">' . $blclipart_c . '</div><div class="height-50 text-align-center"><img class="max-width-100prc max-height-91prc" src="' . $cliparts_module->getConvertCustomArtImageUrl($tpt_vars, $blclipart_c, '80', '80', 'png') .'" /></div></div>';
				$product_details_l[] = 'Back Custom Art Left:';
				$product_details_v[] = $blclipart_c;
			}

			if (!empty($product->data['clipart']['back'][0]['right'])) {
				$brclipart_path = getModule($tpt_vars, "BandClipart")->getClipartURL($tpt_vars, $product->data['clipart']['back'][0]['right']);
				$brclipart_e = array_filter(explode('/', $brclipart_path));
				if (!empty($brclipart_e) && isset($brclipart_e[count($brclipart_e) - 1])) {
					$brclipart_e = array_filter(explode('.', $brclipart_e[count($brclipart_e) - 1]));
					array_pop($brclipart_e);
					$brclipart = htmlentities(preg_replace('#[\W_]+#', ' ', implode('.', $brclipart_e)));
					$rowclass = ($rowclass == $oddclass ? $evenclass : $oddclass);

					$product_details[] = '<div class="padding-top-2 padding-bottom-2 clearBoth"' . $rowclass . '><div class="font-weight-bold float-left padding-left-20 urlabel" style="width: ' . $labels_width . ';">Back Art Right</div><div class="font-weight-bold float-left padding-right-5 urlabel">:</div><div class="amz_red overflow-hidden">' . $brclipart . '</div><div class="height-50 text-align-center"><img class="max-width-90prc max-height-100prc" src="' . CLIPARTS_URL . DIRECTORY_SEPARATOR . $brclipart_path . '" /></div></div>';
					$product_details_l[] = 'Back Art Right:';
					$product_details_v[] = $brclipart;
				}

			}

			if (!empty($product->data['clipart_c']['back'][0]['right'])) {
				/*
				$brclipart_path = getModule($tpt_vars, "BandClipart")->getClipartURL($tpt_vars, $product->data['clipart']['back'][0]['right']);
				$brclipart_e = array_filter(explode('/', $brclipart_path));
				if (!empty($brclipart_e)) {
					$brclipart_e = array_filter(explode('.', $brclipart_e[count($brclipart_e) - 1]));
					array_pop($brclipart_e);
					$brclipart = htmlentities(preg_replace('#[\W_]+#', ' ', implode('.', $brclipart_e)));
					$rowclass = ($rowclass == $oddclass ? $evenclass : $oddclass);

					$product_details[] = '<div class="padding-top-2 padding-bottom-2 clearBoth"' . $rowclass . '><div class="font-weight-bold float-left text-align-right padding-right-5" style="width: ' . $labels_width . ';">Back Art Right:</div><div class="amz_red overflow-hidden">' . $brclipart . '</div><div class="height-50 text-align-center"><img class="max-width-100prc max-height-100prc" src="' . $tpt_resourceurl . '/clipart/' . $brclipart_path . '" /></div></div>';
					$product_details_l[] = 'Back Art Right:';
					$product_details_v[] = $brclipart;
				}
				*/

				$brclipart_c = $product->data['clipart_c']['back'][0]['right'];
				$product_details[] = '<div class="padding-top-2 padding-bottom-2 clearBoth"' . $rowclass . '><div class="font-weight-bold float-left padding-left-20 urlabel" style="width: ' . $labels_width . ';">Back Custom Art Right</div><div class="font-weight-bold float-left padding-right-5 urlabel">:</div><div class="amz_red overflow-hidden">' . $brclipart_c . '</div><div class="height-50 text-align-center"><img class="max-width-89prc max-height-100prc" src="' . $cliparts_module->getConvertCustomArtImageUrl($tpt_vars, $brclipart_c, '80', '80', 'png') .'" /></div></div>';
				$product_details_l[] = 'Back Custom Art Right:';
				$product_details_v[] = $brclipart_c;
			}


			if (!empty($product->data['messages']['back']['1'])) {
				$bmessage2 = htmlentities($product->data['messages']['back']['1']);
				$rowclass = ($rowclass == $oddclass ? $evenclass : $oddclass);

				$product_details[] = '<div class="padding-top-2 padding-bottom-2 clearBoth"' . $rowclass . '><div class="font-weight-bold float-left padding-left-20 urlabel" style="width: ' . $labels_width . ';">Back Message Ln2</div><div class="font-weight-bold float-left padding-right-5 urlabel">:</div><div class="amz_red overflow-hidden font-size-14 font-weight-bold">' . $bmessage2 . '</div></div>';
				$product_details_l[] = 'Back message Ln2:';
				$product_details_v[] = $bmessage2;
			}

			if (!empty($product->data['clipart']['back'][1]['left'])) {
				$blclipart2_path = getModule($tpt_vars, "BandClipart")->getClipartURL($tpt_vars, $product->data['clipart']['back'][1]['left']);
				$blclipart2_e = array_filter(explode('/', $blclipart2_path));
				if (!empty($blclipart2_e) && isset($blclipart2_e[count($blclipart2_e) - 1])) {
					$blclipart2_e = array_filter(explode('.', $blclipart2_e[count($blclipart2_e) - 1]));
					array_pop($blclipart2_e);
					$blclipart2 = htmlentities(preg_replace('#[\W_]+#', ' ', implode('.', $blclipart2_e)));
					$rowclass = ($rowclass == $oddclass ? $evenclass : $oddclass);

					$product_details[] = '<div class="padding-top-2 padding-bottom-2 clearBoth"' . $rowclass . '><div class="font-weight-bold float-left padding-left-20 urlabel" style="width: ' . $labels_width . ';">Back Art Left Ln2</div><div class="font-weight-bold float-left padding-right-5 urlabel">:</div><div class="amz_red overflow-hidden">' . $blclipart2 . '</div><div class="height-50 text-align-center"><img class="max-width-88prc max-height-100prc" src="' . CLIPARTS_URL . DIRECTORY_SEPARATOR . $blclipart2_path . '" /></div></div>';
					$product_details_l[] = 'Back Art Left Ln2:';
					$product_details_v[] = $blclipart2;
				}
			}

			if (!empty($product->data['clipart_c']['back'][1]['left'])) {
				/*
				$blclipart2_path = getModule($tpt_vars, "BandClipart")->getClipartURL($tpt_vars, $product->data['clipart']['back'][1]['left']);
				$blclipart2_e = array_filter(explode('/', $blclipart2_path));
				if (!empty($blclipart2_e)) {
					$blclipart2_e = array_filter(explode('.', $blclipart2_e[count($blclipart2_e) - 1]));
					array_pop($blclipart2_e);
					$blclipart2 = htmlentities(preg_replace('#[\W_]+#', ' ', implode('.', $blclipart2_e)));
					$rowclass = ($rowclass == $oddclass ? $evenclass : $oddclass);

					$product_details[] = '<div class="padding-top-2 padding-bottom-2 clearBoth"' . $rowclass . '><div class="font-weight-bold float-left text-align-right padding-right-5" style="width: ' . $labels_width . ';">Back Art Left Ln2:</div><div class="amz_red overflow-hidden">' . $blclipart2 . '</div><div class="height-50 text-align-center"><img class="max-width-100prc max-height-100prc" src="' . $tpt_resourceurl . '/clipart/' . $blclipart2_path . '" /></div></div>';
					$product_details_l[] = 'Back Art Left Ln2:';
					$product_details_v[] = $blclipart2;
				}
				*/

				$blclipart2_c = $product->data['clipart_c']['back'][1]['left'];
				$product_details[] = '<div class="padding-top-2 padding-bottom-2 clearBoth"' . $rowclass . '><div class="font-weight-bold float-left padding-left-20 urlabel" style="width: ' . $labels_width . ';">Back Custom Art Left Ln2</div><div class="font-weight-bold float-left padding-right-5 urlabel">:</div><div class="amz_red overflow-hidden">' . $blclipart2_c . '</div><div class="height-50 text-align-center"><img class="max-width-87prc max-height-100prc" src="' . $cliparts_module->getConvertCustomArtImageUrl($tpt_vars, $blclipart2_c, '80', '80', 'png') .'" /></div></div>';
				$product_details_l[] = 'Back Custom Art Left Ln2:';
				$product_details_v[] = $blclipart2_c;
			}

			if (!empty($product->data['clipart']['back'][1]['right'])) {
				$brclipart2_path = getModule($tpt_vars, "BandClipart")->getClipartURL($tpt_vars, $product->data['clipart']['back'][1]['right']);
				$brclipart2_e = array_filter(explode('/', $brclipart2_path));
				if (!empty($brclipart2_e) && isset($brclipart2_e[count($brclipart2_e) - 1])) {
					$brclipart2_e = array_filter(explode('.', $brclipart2_e[count($brclipart2_e) - 1]));
					array_pop($brclipart2_e);
					$brclipart2 = htmlentities(preg_replace('#[\W_]+#', ' ', implode('.', $brclipart2_e)));
					$rowclass = ($rowclass == $oddclass ? $evenclass : $oddclass);

					$product_details[] = '<div class="padding-top-2 padding-bottom-2 clearBoth"' . $rowclass . '><div class="font-weight-bold float-left padding-left-20 urlabel" style="width: ' . $labels_width . ';">Back Art Right Ln2</div><div class="font-weight-bold float-left padding-right-5 urlabel">:</div><div class="amz_red overflow-hidden">' . $brclipart2 . '</div><div class="height-50 text-align-center"><img class="max-width-86prc max-height-100prc" src="' . CLIPARTS_URL . DIRECTORY_SEPARATOR . $brclipart2_path . '" /></div></div>';
					$product_details_l[] = 'Back Art Right Ln2:';
					$product_details_v[] = $brclipart2;
				}
			}

			if (!empty($product->data['clipart_c']['back'][1]['right'])) {
				/*
				$brclipart2_path = getModule($tpt_vars, "BandClipart")->getClipartURL($tpt_vars, $product->data['clipart']['back'][1]['right']);
				$brclipart2_e = array_filter(explode('/', $brclipart2_path));
				if (!empty($brclipart2_e)) {
					$brclipart2_e = array_filter(explode('.', $brclipart2_e[count($brclipart2_e) - 1]));
					array_pop($brclipart2_e);
					$brclipart2 = htmlentities(preg_replace('#[\W_]+#', ' ', implode('.', $brclipart2_e)));
					$rowclass = ($rowclass == $oddclass ? $evenclass : $oddclass);

					$product_details[] = '<div class="padding-top-2 padding-bottom-2 clearBoth"' . $rowclass . '><div class="font-weight-bold float-left text-align-right padding-right-5" style="width: ' . $labels_width . ';">Back Art Right Ln2:</div><div class="amz_red overflow-hidden">' . $brclipart2 . '</div><div class="height-50 text-align-center"><img class="max-width-100prc max-height-100prc" src="' . $tpt_resourceurl . '/clipart/' . $brclipart2_path . '" /></div></div>';
					$product_details_l[] = 'Back Art Right Ln2:';
					$product_details_v[] = $brclipart2;
				}
				*/

				$brclipart2_c = $product->data['clipart_c']['back'][1]['right'];
				$product_details[] = '<div class="padding-top-2 padding-bottom-2 clearBoth"' . $rowclass . '><div class="font-weight-bold float-left padding-left-20 urlabel" style="width: ' . $labels_width . ';">Back Custom Art Right Ln2</div><div class="font-weight-bold float-left padding-right-5 urlabel">:</div><div class="amz_red overflow-hidden">' . $brclipart2_c . '</div><div class="height-50 text-align-center"><img class="max-width-85prc max-height-100prc" src="' . $cliparts_module->getConvertCustomArtImageUrl($tpt_vars, $brclipart2_c, '80', '80', 'png') .'" /></div></div>';
				$product_details_l[] = 'Back Custom Art Right Ln2:';
				$product_details_v[] = $brclipart2_c;
			}


			if (!empty($product->data['custom_clipart'])) {
				// multiple cliparts feature...
				if (is_array($product->data['custom_clipart'])) {

					$product_details[] = '
					<div class="padding-top-2 padding-bottom-2 clearBoth"' . $rowclass . '>
						<div class="font-weight-bold float-left padding-left-10 urlabel" style=width: ' . $labels_width . ';">Custom Art</div><div class="font-weight-bold float-left padding-right-5">:</div>
						<div class="clear"></div>';
					$cc_bh_add = '';
					foreach ($product->data['custom_clipart'] as $ccl) {

						if ((stristr($ccl[1], '.pdf')) || (stristr($ccl[1], '.PDF')))
						{
							$cc_bh_add = '<a href="' . BASE_URL_SECURE . $ccl[1] . '" target="_blank" > <img class="max-width-100prc max-height-100prc" src="' . TPT_IMAGES_URL .'/icons/pdf-icon.png' . '" /></a>';
						}
						elseif ((stristr($ccl[1], '.EPS')) || (stristr($ccl[1], '.eps')))
						{
							$cc_bh_add = '<a href="' . BASE_URL_SECURE . $ccl[1] . '" target="_blank" > <img class="max-width-100prc max-height-100prc" src="' . TPT_IMAGES_URL .'/icons/eps-icon.png' . '" /></a>';
						}
						else
						{
							$cc_bh_add = '<img class="max-width-100prc max-height-100prc" src="' . BASE_URL_SECURE . $ccl[1] . '" />';
						}


						$product_details[] = '
							<div class="amz_red overflow-hidden">' . basename($ccl[1]) . ' : ' . $ccl[0] . '</div>
							<div class="text-align-center">
								'. $cc_bh_add.'
							</div>';
					}

					$product_details[] = '</div>';
					unset($cc_bh_add);

				} else { // old single case

					$cc_bh_add = '';
					if ((stristr($product->data['custom_clipart'], '.pdf')) || (stristr($product->data['custom_clipart'], '.PDF')))
					{
						$cc_bh_add = '<a href="' . CUSTOM_CLIPART_URL . '/' .$product->data['custom_clipart'] . '" target="_blank" > <img class="max-width-100prc max-height-100prc" src="' . TPT_IMAGES_URL .'/icons/pdf-icon.png' . '" /></a>';
					}
					elseif ((stristr($product->data['custom_clipart'], '.EPS')) || (stristr($product->data['custom_clipart'], '.eps')))
					{
						$cc_bh_add = '<a href="' . CUSTOM_CLIPART_URL . '/' . $product->data['custom_clipart'] . '" target="_blank" > <img class="max-width-100prc max-height-100prc" src="' . TPT_IMAGES_URL .'/icons/eps-icon.png' . '" /></a>';
					}
					else
					{
						$cc_bh_add = '<img class="max-width-100prc max-height-100prc" src="' . CUSTOM_CLIPART_URL . '/' . $product->data['custom_clipart'] . '" />';
					}

					$product_details[] = '
					<div class="padding-top-2 padding-bottom-2 clearBoth"' . $rowclass . '>
						<div class="font-weight-bold float-left text-align-right padding-right-5 urlabel" style="width: ' . $labels_width . ';">Custom Art:</div>
						<div class="amz_red overflow-hidden">' . $product->data['custom_clipart'] . '</div>
						<div class="text-align-center">
							'.$cc_bh_add.'
						</div>
					</div>';
					/*				$product_details[] = '
										<div class="padding-top-2 padding-bottom-2 clearBoth"' . $rowclass . '>
											<div class="font-weight-bold float-left text-align-right padding-right-5" style="width: ' . $labels_width . ';">Custom Art:</div>
											<div class="amz_red overflow-hidden">' . $product->data['custom_clipart'] . '</div>
											<div class="height-50 text-align-center">
												<img class="max-width-100prc max-height-100prc" src="' . CUSTOM_CLIPART_URL . '/' . $product->data['custom_clipart'] . '" />
											</div>
										</div>';
					*/
					unset($cc_bh_add);
				}
			}
		}

		if (!empty($addons)) {
			$rowclass = ($rowclass == $oddclass ? $evenclass : $oddclass);

			$product_details[] = '<div class="padding-top-2 padding-bottom-2 clearBoth"' . $rowclass . '><div class="font-weight-bold padding-right-5 urlabel"><span class="amz_brown font-weight-bold">Add-ons:</span></div></div>';
			$product_details_l[] = '<span class="amz_brown font-weight-bold">Add-ons:</span>';
			$product_details_v[] = '';

			foreach ($addons as $addon) {
				$rowclass = ($rowclass == $oddclass ? $evenclass : $oddclass);

				$product_details[] = '<div class="padding-top-2 padding-bottom-2 clearBoth"' . $rowclass . '><div class="font-weight-bold padding-right-5 urlabel"><span class="amz_red font-weight-normal">' . $addon . '</span></div></div>';
				$product_details_l[] = $addon;
				$product_details_v[] = '';
			}
		}

		/*
		if(isDev('devcart') && !empty($_GET['devcart'])) {
			$reorder_id = $product->data['reorder'];
		$rowclass = ($rowclass==$oddclass?$evenclass:$oddclass);

		$product_details[] = '<div class="padding-top-2 padding-bottom-2"'.$rowclass.'><div class="font-weight-bold float-left text-align-right padding-right-5 urlabel width-80">Reorder ID:</div><div class="amz_red overflow-hidden">'.$reorder_id.'</div></div>';
		$product_details_l[] = 'Reorder ID:';
		$product_details_v[] = $reorder_id;
		}
		*/


		if (isDev('devcart') && !empty($_GET['devcart'])) {
			$p = clone $product;
			$p->pricingObject = null;
			$productdata = '<pre>' . var_export($p, true) . '</pre>';
			$rowclass = ($rowclass == $oddclass ? $evenclass : $oddclass);

			$product_details[] = '<div class="padding-top-2 padding-bottom-2"' . $rowclass . '><div class="font-weight-bold float-left text-align-right padding-right-5 urlabel width-80">$p->data:</div><div class="amz_red overflow-hidden">' . $productdata . '</div></div>';
			$product_details_l[] = '$p->data:';
			$product_details_v[] = $productdata;
			/*
			}

			if(isDev('devcart') && !empty($_GET['devcart'])) {
			*/
			$pdataarray = '<pre>' . var_export($pdata, true) . '</pre>';
			//$pdataarray = var_export($pdata, true);
			$rowclass = ($rowclass == $oddclass ? $evenclass : $oddclass);

			$product_details[] = '<div class="padding-top-2 padding-bottom-2"' . $rowclass . '><div class="font-weight-bold float-left text-align-right padding-right-5 urlabel width-80">$pdata:</div><div class="amz_red overflow-hidden">' . $pdataarray . '</div></div>';
			$product_details_l[] = '$pdata:';
			$product_details_v[] = $pdataarray;

			if (!empty($_GET['pricingcart'])) {
				$prc = '<pre>' . var_export($product->pricingObject, true) . '</pre>';
				//$pdataarray = var_export($pdata, true);
				$rowclass = ($rowclass == $oddclass ? $evenclass : $oddclass);

				$product_details[] = '<div class="padding-top-2 padding-bottom-2"' . $rowclass . '><div class="font-weight-bold float-left text-align-right padding-right-5 urlabel width-80">$product->pricingObject:</div><div class="amz_red overflow-hidden">' . $prc . '</div></div>';
				$product_details_l[] = '$product->pricingObject:';
				$product_details_v[] = $prc;
			}

			if (!empty($_GET['colorcart'])) {
				if (!empty($product->data['band_color'])) {
					$cprops = '<pre>' . var_export($colors_module->getColorProps($tpt_vars, $product->data['band_color']), true) . '</pre>';
					//$pdataarray = var_export($pdata, true);
					$rowclass = ($rowclass == $oddclass ? $evenclass : $oddclass);

					$product_details[] = '<div class="padding-top-2 padding-bottom-2"' . $rowclass . '><div class="font-weight-bold float-left text-align-right padding-right-5 urlabel width-80">cprops[color]:</div><div class="amz_red overflow-hidden">' . $cprops . '</div></div>';
					$product_details_l[] = 'cprops[band_color]:';
					$product_details_v[] = $cprops;
				}


				if (!empty($product->data['message_color'])) {
					$cprops = '<pre>' . var_export($colors_module->getColorProps($tpt_vars, $product->data['message_color']), true) . '</pre>';
					//$pdataarray = var_export($pdata, true);
					$rowclass = ($rowclass == $oddclass ? $evenclass : $oddclass);

					$product_details[] = '<div class="padding-top-2 padding-bottom-2"' . $rowclass . '><div class="font-weight-bold float-left text-align-right padding-right-5 urlabel width-80">cprops[mcolor]:</div><div class="amz_red overflow-hidden">' . $cprops . '</div></div>';
					$product_details_l[] = 'cprops[message_color]:';
					$product_details_v[] = $cprops;
				}
			}

			if(!empty($vars['appvars']['quotecart'])) {
				if(!empty($product->data['quote_id'])) {
					$quote_id = $product->data['quote_id'];

					$qry = <<< EOT
SELECT * FROM `temp_custom_orders` WHERE `id`=$quote_id
EOT;
					$vars['db']['handler']->query($qry);
					$temp_custom_orders = $vars['db']['handler']->fetch_assoc();
					$temp_custom_orders = '<pre>' . var_export($temp_custom_orders, true) . '</pre>';
					$rowclass = ($rowclass == $oddclass ? $evenclass : $oddclass);

					$product_details[] = '<div class="padding-top-2 padding-bottom-2"' . $rowclass . '><div class="font-weight-bold float-left text-align-right padding-right-5 urlabel width-80">temp_custom_orders(id:'.$quote_id.'):</div><div class="amz_red overflow-hidden">' . $temp_custom_orders . '</div></div>';
					$product_details_l[] = 'temp_custom_orders(id:'.$quote_id.'):';
					$product_details_v[] = $temp_custom_orders;
				}
				if(!empty($product->data['product_id'])) {
					$product_id = $product->data['product_id'];

					$qry = <<< EOT
SELECT * FROM `temp_custom_order_products` WHERE `id`=$product_id
EOT;
					$vars['db']['handler']->query($qry);
					$temp_custom_order_products = $vars['db']['handler']->fetch_assoc();
					$temp_custom_order_products = '<pre>' . var_export($temp_custom_order_products, true) . '</pre>';
					$rowclass = ($rowclass == $oddclass ? $evenclass : $oddclass);

					$product_details[] = '<div class="padding-top-2 padding-bottom-2"' . $rowclass . '><div class="font-weight-bold float-left text-align-right padding-right-5 urlabel width-80">temp_custom_order_products(id:'.$product_id.'):</div><div class="amz_red overflow-hidden">' . $temp_custom_order_products . '</div></div>';
					$product_details_l[] = 'temp_custom_order_products(id:'.$product_id.'):';
					$product_details_v[] = $temp_custom_order_products;
				}
			}
		}


		$rowclass = ($rowclass == $oddclass ? $evenclass : $oddclass);

		$product_details[] = '<div class="padding-top-2 padding-bottom-2 clearBoth"' . $rowclass . '><div class="font-weight-bold padding-right-5 urlabel"><span class="amz_brown font-weight-bold">Your Comments:</span></div></div>';
		$product_details_l[] = '<span class="amz_brown font-weight-bold">User Comments:</span>';
		$product_details_v[] = '';

		$rowclass = ($rowclass == $oddclass ? $evenclass : $oddclass);

		$comments = $product->data['comments'];

		$savecommenturl = $tpt_vars['config']['ajaxurl'] . '/cartupdateproduct_comments';
		$saveaction = tpt_ajax::getCall('cart.update_comments');

		$updatecommentsform = <<< EOT
<form action="$savecommenturl" method="POST">
<textarea name="comments">$comments</textarea>
<br />
<input type="hidden" name="productindex" value="$index" />
<input type="hidden" name="task" value="cart.update_comments" />
<input type="button" value="Save" onclick="$saveaction;addClass(this.parentNode.parentNode.parentNode.parentNode, 'height-18');" />
<input type="button" value="Cancel" onclick="addClass(this.parentNode.parentNode.parentNode.parentNode, 'height-18');" />
</form>
EOT;


		$comments_label = 'Add Your Design Ideas/Comments';
		if (!empty($comments)) {
			$comments_label = 'View/Edit Comments';
		}

		$ccontent = <<< EOT
<div class="overflow-hidden height-18 padding-top-2 padding-bottom-2 clearBoth"$rowclass>
    <div class="height-20 font-weight-bold padding-right-5 urlabel">
        <a onclick="removeClass(this.parentNode.parentNode, new RegExp(/height-[0-9]+/));" href="javascript:void(0);" class="amz_red font-weight-normal">$comments_label</a>
    </div>
    <div>
        <div>
        </div>
        <div>
            $updatecommentsform
        </div>
    </div>
</div>
EOT;
		if (!empty($ordercart)) {
			if (!empty($comments)) {
				$ccontent = <<< EOT
<div>$comments</div>
EOT;
			} else {
				$ccontent = <<< EOT
<div class="font-style-italic">(none)</div>
EOT;
			}

		}

		$product_details[] = $ccontent;


		//tpt_dump($product->data['added_by'], true);
		//tpt_logger::dump($tpt_vars, $product->data['added_by'], debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), '$product->data[\'added_by\']', __FILE__.' '.__LINE__);
		//$pdae_qry = $product->getDesignUrlQuery3($tpt_vars);
		$pdae_qry = $product->getDesignUrlQuery3($tpt_vars);;
		//$pdae_url = BASE_URL.$sbuilders[DEFAULT_BUILDER_ID]['standard_url'].'?'.$pdae_qry;
		$pdae_url = '';
		//$pe_url = $pdae_url.'&product='.$index;
		$pe_url = '';
		$builder_id = !empty($product->data['added_by']) ? $product->data['added_by'] : '';
		if (!empty($builder_id) && !empty($sbuilders[$builder_id])) {
			//var_dump($dae_qry);die();
			$builder_id = $product->data['added_by'];
		} else {
			//var_dump($dae_qry);die();
			$builder_id = $pdata['default_builder'];
			if (!empty($product->data['rush_order'])) {
				$builder_id = RUSHORDER_BUILDER_ID;
			}
		}
		$pdae_url = BASE_URL . $sbuilders[$builder_id]['standard_url'] . '?' . $pdae_qry;
		$pe_url = $pdae_url . '&product=' . $index;
		$rowclass = ($rowclass == $oddclass ? $evenclass : $oddclass);

		$product_details[] = <<< EOT
        <div class="padding-top-2 padding-bottom-2 clearBoth"$rowclass>
        &nbsp;
        </div>
EOT;


		$rowclass = ($rowclass == $oddclass ? $evenclass : $oddclass);

		$product_details[] = <<< EOT
        <div class="padding-top-2 padding-bottom-2 clearBoth"$rowclass>
            <div class="font-weight-bold padding-right-5">

                <a class="amz_red font-weight-normal" href="$pdae_url">Duplicate & Edit</a>
            </div>
        </div>
EOT;

		//&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;
		//<a class="amz_red font-weight-normal" href="$pe_url">Edit This Design</a>

		$product_details_l[] = '<span class="amz_brown font-weight-bold"></span>';
		$product_details_v[] = '';

                $sizeHeaderHtml = '<div style="color: #909090; font-family: TODAYSHOP-BOLD,arial; width:'.$w3.'" class="font-size-12 height-35 line-height-35 size-header-display">Size</div>';
                $quantityHeaderHtml = '<div style="color: #909090; font-family: TODAYSHOP-BOLD,arial; width:'.$w4.'" class="font-size-12 height-35 line-height-35 size-header-display">Quantity</div>';
                $subtotalHeaderHtml = '<div style="color: #909090; font-family: TODAYSHOP-BOLD,arial; width:'.$w5.'" class="font-size-12 height-35 line-height-35 size-header-display">Subtotal</div>';
		if (is_a($product, 'amz_customStockproduct')) {

			$smatch = preg_replace('#[0-9]#', '', $product->data['sku']);

			$sizesAvail = array();

			foreach (amz_cart::$customStockProductsData as $csp) {
				//	var_dump($csp['type'],$product->data['type'],$smatch,preg_replace('#[0-9]#','',$csp['sku']));
				if ($csp['type'] == $product->data['type'] && $smatch == preg_replace('#[0-9]#', '', $csp['sku'])) {
					$sizesAvail[$csp['size']] = $csp['sku'];
				}
			}


			$sizeSelect = '<select name="product_size" class="width-90prc padding-4 border-radius-12" style="background-color: white;  border: 1px solid #CCC;  outline: 0;">';

			foreach ($sizesAvail as $k => $v) {
				$sizeSelect .= '<option value="' . $v . '" ' . ($k == $product->data['size'] ? 'selected="selected"' : '') . '>'
					. getModule($tpt_vars, "BandSize")->moduleData['id'][$k]['label']
					. '</option>';
			}

			$sizeSelect .= '</select>';


			$updateurl = $tpt_vars['config']['ajaxurl'] . '/cartupdateproduct';

			$bandSizeForm = <<< EOT
$sizeHeaderHtml                                
<form action="$updateurl" method="POST">
$sizeSelect
<input type="hidden" name="productindex" value="$index" />
<input type="hidden" name="task" value="cart.update_size_stockproduct" />
<div class="height-20"></div>
<input type="submit" value="" class="width-74 height-20 plain-input-field hoverCB background-position-CT" style="background-image: url(/images/buttons/update-btn.png);" />
</form>
EOT;

			if (!empty($ordercart)) {
				$bandSizeForm = getModule($tpt_vars, "BandSize")->moduleData['id'][$product->data['size']]['label'];
			}

###########################################################################################

		} else {

			if (!is_a($product, 'amz_bundle')) {
				$bandSize = getModule($tpt_vars, "BandSize")->moduleData['id'][$product->data['band_size']]['label'];

			} else {
				$bandSize = 'Multiple';

			}
			$bandSizeForm = $bandSize;

			$s_selected = '';
			$m_selected = '';
			$l_selected = '';
			$xl_selected = '';
			switch ($bandSize) {
				case 'Small / Child 7.0"':
					$s_selected = ' selected="selected" ';
					break;
				case 'Medium / Youth 7.5"':
					$m_selected = ' selected="selected" ';
					break;
				case 'Large / Adult 8.0"':
					$l_selected = ' selected="selected" ';
					break;
				case 'Extra Large / XL 8.5"':
					$xl_selected = ' selected="selected" ';
					break;
				case 'Extra Large / 50 minimum 8.5"':
					$xl_selected = ' selected="selected" ';
					break;
				case 'Small / Child 2.4"':
					$s_selected = ' selected="selected" ';
					break;
				case 'Medium / Youth 2.6"':
					$m_selected = ' selected="selected" ';
					break;
				case 'Large / Adult 2.8"':
					$l_selected = ' selected="selected" ';
					break;
			}

			$pos = strrpos($bandSize, "2.");
			if ($pos === false) {
				$is_ring = false;
			} else
				$is_ring = true;

			if ($bandSize == 'Universal') {
				$sizeSelect = '<select name="product_size" class="width-90prc padding-4 border-radius-12" style="background-color: white;  border: 1px solid #CCC;  outline: 0;">
                    <option value="6">Universal</option>
                </select>';
			} else if ($is_ring) {
				$sizeSelect = '<select name="product_size" class="width-90prc padding-2 border-radius-12" style="background-color: white;  border: 1px solid #CCC;  outline: 0;">
                    <option value="8" ' . $s_selected . '>Small / Child 2.4"</option>
                    <option value="9" ' . $m_selected . '>Medium / Youth 2.6"</option>
                    <option value="10" ' . $l_selected . '>Large / Adult 2.8"</option>
                </select>';
			} else if (in_array($product->data['band_style'], array(6, 7))) {
				$sizeSelect = '<select name="product_size" class="width-90prc padding-2 border-radius-12" style="background-color: white;  border: 1px solid #CCC;  outline: 0;">
                    <option value="2" ' . $s_selected . '>Small / Child 7.0"</option>
                    <option value="3" ' . $m_selected . '>Medium / Youth 7.5"</option>
                    <option value="4" ' . $l_selected . '>Large / Adult 8.0"</option>
                </select>';
			} else {
				$sizeSelect = '<select name="product_size" class="width-90prc padding-2 border-radius-12" style="background-color: white;  border: 1px solid #CCC;  outline: 0;">
                    <option value="2" ' . $s_selected . '>Small / Child 7.0"</option>
                    <option value="3" ' . $m_selected . '>Medium / Youth 7.5"</option>
                    <option value="4" ' . $l_selected . '>Large / Adult 8.0"</option>
                    <option value="5" ' . $xl_selected . '>Extra Large / XL 8.5"</option>
                </select>';
			}

			$updbtnurl = TPT_IMAGES_URL . '/buttons/update-btn.png';
			$updateurl = $tpt_vars['config']['ajaxurl'] . '/cartupdateproduct';

			$bandSizeForm = <<< EOT
$sizeHeaderHtml                                
<form action="$updateurl" method="POST">
$sizeSelect
<input type="hidden" name="productindex" value="$index" />
<input type="hidden" name="task" value="cart.update_size" />
<div class="height-20"></div>
<input type="submit" value="" class="width-74 height-20 plain-input-field hoverCB background-position-CT" style="background-image: url($updbtnurl);" />
</form>
EOT;

			if (!empty($shortcart) || !empty($ordercart) || ($bandSize == 'Universal')) {
				$bandSizeForm = <<< EOT
<span class="amz_red">$bandSize</span>
EOT;
			}

		}

		if (isDev('devcart') && !empty($_GET['devcart'])) {
			$bandSizeForm .= '<div>(' . $product->data['band_size'] . ')</div>';
		}

		//var_dump($product->data['band_color']);die();
		$bandColor = '&nbsp;';
		if (!is_a($product, 'amz_bundle')) {
			if (is_a($product, 'amz_stockproduct') && !empty($sptype['colors'])) {
				$color_definitions = explode('|', $sptype['colors']);
				$cdefs = array();
				foreach ($color_definitions as $cdef) {
					$pcdef = explode('^', $cdef);
					$cdefs[$pcdef[1]] = array('value' => $pcdef[0], 'label' => $pcdef[2]);
				}

				$bandColor = $cdefs[$product->data['band_color']]['label'];

			} elseif (is_a($product, 'amz_customStockproduct')) {
				$bandColor = $product->data['color'];

			} else {
				if (empty($product->data['band_color'])) {
					$bandColor = 'Clear';
				} else {
					if (is_a($product, 'amz_customproduct')) {
						//var_dump($product->data['band_color']);die();
						$bandColor = explode(':', $product->data['band_color']);
						//tpt_dump($product->data['band_color'],true);
						//	$props = getModule($tpt_vars, "BandColor")->getColorProps($tpt_vars, $product->data['band_color']);
						$props = getModule($tpt_vars, 'BandColor')->getColorProps($tpt_vars, $product->data['band_color']);

						$tableId = $bandColor[0];
						//tpt_dump(getModule($tpt_vars, "BandColor")->all_colors[$tableId],true);
						//var_dump(getModule($tpt_vars, "BandColor")->all_colors[$tableId]);
						//	if (!empty(getModule($tpt_vars, "BandColor")->all_colors[$tableId])) {
						if (!empty(getModule($tpt_vars, 'BandColor')->all_colors[$tableId])) {
							$colorId = $bandColor[1];
							$colorCategory = $props['colorcategory'];
							//$colorId = '';

							if (($tableId == 0) || ($tableId == 1) || ($tableId == 2)) {
								//	$colorId = getModule($tpt_vars, "BandColor")->getCustomColorId($tpt_vars, $product->data['band_color']);
								//tpt_dump($product->data['band_color'], true);
								$colorId = getModule($tpt_vars, 'BandColor')->getCustomColorId($tpt_vars, $product->data['band_color']);
							} else {
								//var_dump(getModule($tpt_vars, "BandColor")->all_colors[$tableId]);die();
								//var_dump($tableId);die();
								//tpt_dump(getModule($tpt_vars, "BandColor")->all_colors[$tableId],true);
								//tpt_dump($colorId,true);
								//	$colorId = getModule($tpt_vars, "BandColor")->all_colors[$tableId][$colorId]['label'];
								$colorId = getModule($tpt_vars, 'BandColor')->all_colors[$tableId][$colorId]['label'];
							}

							//var_dump($colorId);

							if (strtolower(strip_tags($colorId)) == 'clear') {
								$colorCategory = 'Solid';
							}

							$bandColor = '<span class="font-weight-bold amz_brown">' . $colorCategory . ':</span> ' . $colorId;
						} else {
							$bandColor = 'Clear';
						}
					}
				}

			}
		} else {
			$bandColor = 'Multiple';
		}


		if (isDev('devcart') && !empty($_GET['devcart'])) {
			$bandColor .= '<div>(' . $product->data['band_color'] . ')</div>';
		}

		/*
		if(!is_a($product, 'amz_bundle')) {
		if(!empty($bandColor)) {
		$rowclass = ($rowclass==$oddclass?$evenclass:$oddclass);

		$product_details[] = '<div'.$rowclass.' style="clear: both;"><div class="font-weight-bold float-left text-align-right padding-right-5" style="width: '.$labels_width.';">Band Color:</div><div class="amz_red overflow-hidden">'.$bandColor.'</div></div>';
		$product_details_l[] = 'Band Color:';
		$product_details_v[] = $bandColor;
		}
		}


		if(!is_a($product, 'amz_bundle')) {
		if(!empty($bandSize)) {
		$rowclass = ($rowclass==$oddclass?$evenclass:$oddclass);

		$product_details[] = '<div'.$rowclass.' style="clear: both;"><div class="font-weight-bold float-left text-align-right padding-right-5" style="width: '.$labels_width.';">Size:</div><div class="amz_red overflow-hidden">'.$bandSize.'</div></div>';
		$product_details_l[] = 'Size:';
		$product_details_v[] = $bandSize;
		}
		}


		if(!empty($product_details)) {
			//$product_details = '<div class="padding-top-5 padding-bottom-5"><div class="font-weight-bold">Product Details:</div><ul style="padding: 0px; margin: 0px;"><li>'.implode('</li><li>', $product_details).'</li></ul></div>';
			//$product_details_l[] = 'Band Size:';
			//$product_details_v[] = getModule($tpt_vars, "BandSize")->moduleData['id'][$product->data['band_size']]['label'];
		} else {
			//$product_details = '';
		}
		*/

		$updbtnurl = TPT_IMAGES_URL . '/buttons/update-btn.png';
		$updateurl = $tpt_vars['config']['ajaxurl'] . '/cartupdateproduct';
		$updateform = <<< EOT
$quantityHeaderHtml                        
<form action="$updateurl" method="POST">
$qty
<input type="hidden" name="productindex" value="$index" />
<input type="hidden" name="task" value="cart.update" />
<div class="height-20"></div>
<input type="submit" value="" class="width-74 height-20 plain-input-field hoverCB background-position-CT" style="background-image: url($updbtnurl);" />
</form>
EOT;

		$delbtnurl = TPT_IMAGES_URL . '/buttons/delete-btn.png';
		$deleteurl = $tpt_vars['config']['ajaxurl'] . '/cartdeleteproduct';
		$deleteform = <<< EOT
<form action="$deleteurl" method="POST">
<input type="hidden" name="productindex" value="$index" />
<input type="hidden" name="task" value="cart.delete" />
<div class="height-20"></div>
<input type="submit" value="" class="width-74 height-20 plain-input-field hoverCB background-position-CT" style="background-image: url($delbtnurl);" />
</form>
EOT;

		if (!empty($shortcart) || !empty($ordercart)) {
			$prqty = $product->qty;
			$updateform = <<< EOT
<span class="amz_red">$prqty</span>
EOT;
			$deleteform = '';
		}
		/*
		$product_html .=  <<< EOT
		<div class="clearFix" style="width: 100%">
			<div class="float-left" style="width: 45%">
				<div>
					$image
				</div>
				<div class="font-size-11 text-align-center">
					<a class="amz_red" href="$url">$name</a>
				</div>
				<br />
				<div class="font-size-11 text-align-center">
					<span class="font-weight-bold white-space-nowrap">PID: $sku</span>
				</div>
			</div>
			<div class="overflow-hidden padding-left-10 text-align-justify">
				$descr
				$product_details
			</div>
		</div>
		EOT;
		*/


		/*
		$product_details_l = '<div class="height-16">'.implode('</div><div class="height-16">', $product_details_l).'</div>';
		$product_details_v = '<div class="height-16">'.implode('</div><div class="height-16">', $product_details_v).'</div>';


		$product_html .=  <<< EOT
		<div class="clearFix" style="width: 100%">
			<div class="float-left" style="width: 45%">
				<div>
					$image
				</div>
			</div>
			<div class="padding-left-10 text-align-justify">
				<div>
					<div class="display-inline-block text-align-right font-size-12 urlabel">
						$product_details_l
					</div>
					<div class="amz_red display-inline-block font-size-12">
						$product_details_v
					</div>
				</div>
			</div>
		</div>
		EOT;
		*/


		$product_details = implode("\n", $product_details);
		$product_html .= <<< EOT
<div class="width-100prc">
    <div class="clearFix">
EOT;
		if (empty($shortcart)) {
			$product_html .= <<< EOT
        <div class="float-left">
            <div>
                $image
            </div>
        </div>
EOT;
		}
		$product_html .= <<< EOT
        <div class="text-align-left font-size-12">
            $product_details
        </div>
    </div>
</div>
EOT;

		/*
				} else if(is_a($product, 'amz_customproduct')) {

					$name = 'Custom Product Design';
					//var_dump($product->data);die();
					$url = $tpt_vars['url']['handler']->wrap($tpt_vars, '/Custom-Product-Quote');
					$sku = '';
					$qty = tpt_html::createTextinput($tpt_vars, $product->pricingObject->qty['lg'], $product->qty, ' size="5"');

					$bandColor = $product->designParams['band_color'];
					$frontMessage = $product->designParams['messages']['front'];
					$UEfrontMessage = urlencode($frontMessage);
					$backMessage = $product->designParams['messages']['back'];
					$UEbackMessage = urlencode($backMessage);
					$bandFont = urlencode($product->designParams['band_font']);
					$UEbandFont = urlencode($bandFont);
					$bandType = $product->pricingObject->type;
					$bandStyle = $product->pricingObject->style;
					$timestamp = time();
		$image = <<< EOT
		<div style="background-color: #$bandColor;">
			<img style="max-width: 100%;" src="$tpt_baseurl/generate-preview?text=$UEfrontMessage&amp;font=$UEbandFont&amp;bandType=$bandType&amp;bandStyle=$bandStyle&amp;type=full&amp;timestamp=$timestamp">
		</div>
		EOT;
					//var_dump($product->price);die();
					$product->pricingObject->getPrice();
					$subtotal = $product->pricingObject->price['html']['customer_price_total'];//$product->price['html']['mbase_price'];

					$product_details[] = 'Band Type: '.getModule($tpt_vars, "BandType")->moduleData['id'][$bandType]['name'];
					$product_details[] = 'Message Style: '.getModule($tpt_vars, "BandStyle")->moduleData['id'][$bandStyle]['name'];
					$product_details[] = 'Band Color: #'.$bandColor;
					$product_details[] = 'Band Font: '.$bandFont;
					$product_details[] = 'Band Size: Large';
					$product_details[] = 'Front Message: '.$frontMessage;
					$product_details[] = 'Back Message: '.$backMessage;

					if(!empty($product_details) && is_array($product_details)) {
						$product_details = implode('</li><li>', $product_details);
		$product_details = <<< EOT
		<div class="padding-top-5 padding-bottom-5">
			<div class="font-weight-bold">Product Details:</div>
			<ul style="padding: 0px; margin: 0px;">
				<li>$product_details</li>
			</ul>
		</div>
		EOT;
					} else {
						$product_details = '';
					}

					$updateurl = $tpt_vars['config']['ajaxurl'].'/cartupdateproduct';
		$updateform = <<< EOT
		<form action="$updateurl" method="POST">
		$qty
		<input type="hidden" name="productindex" value="$index" />
		<input type="hidden" name="task" value="cart.update_custom" />
		<input type="submit" value="Update" />
		</form>
		EOT;
					$deleteurl = $tpt_vars['config']['ajaxurl'].'/cartdeleteproduct';
		$deleteform = <<< EOT
		<form action="$deleteurl" method="POST">
		<input type="hidden" name="productindex" value="$index" />
		<input type="hidden" name="task" value="cart.delete" />
		<input type="submit" value="Remove" />
		</form>
		EOT;
		$product_html .=  <<< EOT
		<div class="clearFix" style="width: 100%">
			<div class="float-left" style="width: 45%">
				<div>
					$image
				</div>
				<div class="font-size-11 text-align-center">
					<a class="amz_red" href="$url">$name</a>
				</div>
				<br />
				<div class="font-size-11 text-align-center">
					<span class="font-weight-bold white-space-nowrap">PID: $sku</span>
				</div>
			</div>
			<div class="text-align-left">
				$product_details
			</div>
		</div>
		EOT;

				}
		*/

		//var_dump($product->pricingObject->pricingType);
		//var_dump(amz_cart::$totals['pricing']['values']['oscount']);
		//var_dump(amz_cart::$totals['pricing']['values']['osc']);

$subtotal = $subtotalHeaderHtml . $subtotal;
		$cells = array(
			array('width' => $w1, 'classes' => 'float-left', 'content' => $product_html),
			array('width' => $w2, 'classes' => 'amz_red float-left text-align-center color-display', 'content' => '<div class="display-inline-block text-align-left padding-left-5 padding-right-5">' . $bandColor . '</div>'),
			array('width' => $w3, 'classes' => 'amz_red float-left text-align-center', 'content' => $bandSizeForm),
			array('width' => $w4, 'classes' => 'amz_red float-left text-align-center border-left-right', 'content' => $updateform . $deleteform),
			array('width' => $w5, 'classes' => 'amz_red float-left text-align-center', 'content' => $subtotal),
		);

//		$delimiterurl = TPT_IMAGES_URL . DIRECTORY_SEPARATOR . 'cart-column-delimiter-760.png';
		$delimiterurl = TPT_IMAGES_URL . DIRECTORY_SEPARATOR . 'cart-elem-spr.png';
		//if(!empty($shortcart)) {
		//    $delimiterurl = TPT_IMAGES_URL.DIRECTORY_SEPARATOR.'cart-column-delimiter-560.png';
		//}
		$cells_html = '<div class="padding-top-5 padding-botttom-5 clearFix position-relative" style="width: 100%;">'; //vj edits
		$cells_html .= $productDesign;
//		$cells_html .= '<div class="position-absolute top-0 bottom-0 left-0 right-0 background-repeat-repeat-y z-index-1" style="background-image: url(' . $delimiterurl . ');"></div>';
		$cells_html .= '<div class="position-absolute top-0 bottom-0 left-0 right-0 z-index-1" style="background-position: 0px -210px; background-image: url(' . $delimiterurl . ');"></div>';
		$cells_html .= '<div class="position-relative clearFix z-index-2">';
		foreach ($cells as $params) {
			$cells_html .= '<div style="width:' . $params['width'] . '; min-height: 1px; " class="font-size-12 ' . $params['classes'] . '">' . $params['content'] . '</div>';
		}
		$cells_html .= '</div>';
		$cells_html .= '</div>';

		return $cells_html;
	}
}

class amz_customStockproduct {
	public $id;

	function __construct(&$tpt_vars, $id, $qty) {
		if(!is_numeric($id) || empty($id) || !isset(amz_cart::$customStockProductsData[$id]))
				return false;

		//	var_dump(amz_cart::$customStockProductsData[$id]);

		$this->id = $id;
		$this->qty = $qty;
		$this->data = amz_cart::$customStockProductsData[$id];
		//var_dump($this->qty);die();

		/*
		$values = array(
			'sbase_price'=>amz_cart::$customStockProductsData[$id]['price'],
			'mbase_price'=>amz_cart::$customStockProductsData[$id]['price']*$qty
		);

		$html = $values;
		//var_dump($html);
		array_walk($html, 'format_price_array');

		$this->price = array('values'=>$values, 'html'=>$html);
		*/
		$this->price = array('values'=>array(), 'html'=>array());
		
	}

	function getCachedProductImageUrl(&$vars, $x=0, $y=0) {
		return TPT_STOCKPRODUCTIMAGES_URL.'/'.$this->data['image_filename'];
	}
	
	function getCachedProductThumbUrl(&$vars, $x=0, $y=0) {
		return TPT_STOCKPRODUCTIMAGES_URL.'/'.$this->data['thumb_filename'];
	}
	
	function getCachedImageName(&$vars, $x=0, $y=0) {

		/*
		$flatimg = array(
						'pgType'=>$this->data['band_type'],
						'pgStyle'=>$this->data['band_style'],
						'invert_dual'=>intval($this->data['invert_dual'], 10),
						'cut_away'=>intval($this->data['cut_away'], 10),
						'pgFont' => $this->data['band_font'],
						'pgBandColor' => $this->data['band_color'],
						'pgMessageColor' => $this->data['message_color'],
						'pgFrontMessage' => 'f1:'.(!empty($this->data['messages']['front'][0])?$this->data['messages']['front'][0]:''),
						'pgClipartFrontLeft' => (!empty($this->data['clipart']['front'][0]['left'])?$this->data['clipart']['front'][0]['left']:0),
						'pgClipartFrontRight' => (!empty($this->data['clipart']['front'][0]['right'])?$this->data['clipart']['front'][0]['right']:0),
						'pgFrontMessage2' => 'f2:'.(!empty($this->data['messages']['front'][1])?$this->data['messages']['front'][1]:''),
						'pgClipartFrontLeft2' => (!empty($this->data['clipart']['front'][1]['left'])?$this->data['clipart']['front'][1]['left']:0),
						'pgClipartFrontRight2' => (!empty($this->data['clipart']['front'][1]['right'])?$this->data['clipart']['front'][1]['right']:0),
						'pgBackMessage' => 'b1:'.(!empty($this->data['messages']['back'][0])?$this->data['messages']['back'][0]:''),
						'pgClipartBackLeft' => (!empty($this->data['clipart']['back'][0]['left'])?$this->data['clipart']['back'][0]['left']:0),
						'pgClipartBackRight' => (!empty($this->data['clipart']['back'][0]['right'])?$this->data['clipart']['back'][0]['right']:0),
						'pgBackMessage2' => 'b2:'.(!empty($this->data['messages']['back'][1])?$this->data['messages']['back'][1]:''),
						'pgClipartBackLeft2' => (!empty($this->data['clipart']['back'][1]['left'])?$this->data['clipart']['back'][1]['left']:0),
						'pgClipartBackRight2' => (!empty($this->data['clipart']['back'][1]['right'])?$this->data['clipart']['back'][1]['right']:0),
						'pg_x' => $x,
						'pg_y' => $y
						);
		$query = array(
						'type'=>'flat',
						'pgType'=>$this->data['band_type'],
						'pgStyle'=>$this->data['band_style'],
						'invert_dual'=>intval($this->data['invert_dual'], 10),
						'cut_away'=>intval($this->data['cut_away'], 10),
						'pgFont' => $this->data['band_font'],
						'pgBandColor' => $this->data['band_color'],
						'pgMessageColor' => $this->data['message_color'],
						'pgFrontMessage' => (!empty($this->data['messages']['front'][0])?$this->data['messages']['front'][0]:''),
						'pgClipartFrontLeft' => (!empty($this->data['clipart']['front'][0]['left'])?$this->data['clipart']['front'][0]['left']:0),
						'pgClipartFrontRight' => (!empty($this->data['clipart']['front'][0]['right'])?$this->data['clipart']['front'][0]['right']:0),
						'pgFrontMessage2' => (!empty($this->data['messages']['front'][1])?$this->data['messages']['front'][1]:''),
						'pgClipartFrontLeft2' => (!empty($this->data['clipart']['front'][1]['left'])?$this->data['clipart']['front'][1]['left']:0),
						'pgClipartFrontRight2' => (!empty($this->data['clipart']['front'][1]['right'])?$this->data['clipart']['front'][1]['right']:0),
						'pgBackMessage' => (!empty($this->data['messages']['back'][0])?$this->data['messages']['back'][0]:''),
						'pgClipartBackLeft' => (!empty($this->data['clipart']['back'][0]['left'])?$this->data['clipart']['back'][0]['left']:0),
						'pgClipartBackRight' => (!empty($this->data['clipart']['back'][0]['right'])?$this->data['clipart']['back'][0]['right']:0),
						'pgBackMessage2' => (!empty($this->data['messages']['back'][1])?$this->data['messages']['back'][1]:''),
						'pgClipartBackLeft2' => (!empty($this->data['clipart']['back'][1]['left'])?$this->data['clipart']['back'][1]['left']:0),
						'pgClipartBackRight2' => (!empty($this->data['clipart']['back'][1]['right'])?$this->data['clipart']['back'][1]['right']:0),
						'pg_x' => $x,
						'pg_y' => $y
						);

		$image = '';


		$filename = sha1(implode($flatimg)).'.png';
		$cfile = $filename;

		return $cfile;
		*/
		return $this->data['image_filename'];
	}

	function getSku(&$vars) {
				return $this->data['sku'];
	}
	
	
	function getDesignUrlQuery(&$vars) {
		/*
		$addons = array();
		if(!empty($this->pricingObject->options['key_chain'])) {
				$addons['key_chain'] = 'Make Into Keychain';
		}
		$pgType = $this->data['band_type'];
		if(!empty($addons['key_chain'])) {
				$pType = 7;
		}
		$pgStyle = $this->data['band_style'];
		$invert_dual = intval($this->data['invert_dual'], 10);
		$cut_away = intval($this->data['cut_away'], 10);
		$pgFont = $this->data['band_font'];
		//$pgFrontRows = (!empty($pgFrontRows)?$pgFrontRows:1);
		//$pgBackRows = (!empty($pgBackRows)?$pgBackRows:1);
		//$pgTextCont = (!empty($pgTextCont)?intval($pgTextCont, 10):0);
		$pgBandColor = $this->data['band_color'];
		$pgMessageColor = $this->data['message_color'];
		$pgFrontMessage = (!empty($this->data['messages']['front'][0])?$this->data['messages']['front'][0]:'');
		$pgFrontMessage2 = (!empty($this->data['messages']['front'][1])?$this->data['messages']['front'][1]:'');
		$pgBackMessage = (!empty($this->data['messages']['back'][0])?$this->data['messages']['back'][0]:'');
		$pgBackMessage2 = (!empty($this->data['messages']['back'][1])?$this->data['messages']['back'][1]:'');

		$pgClipartFrontLeft = (!empty($this->data['clipart']['front'][0]['left'])?$this->data['clipart']['front'][0]['left']:0);
		$pgClipartFrontRight = (!empty($this->data['clipart']['front'][0]['right'])?$this->data['clipart']['front'][0]['right']:0);
		$pgClipartFrontLeft2 = (!empty($this->data['clipart']['front'][1]['left'])?$this->data['clipart']['front'][1]['left']:0);
		$pgClipartFrontRight2 = (!empty($this->data['clipart']['front'][1]['right'])?$this->data['clipart']['front'][1]['right']:0);
		$pgClipartBackLeft = (!empty($this->data['clipart']['back'][0]['left'])?$this->data['clipart']['back'][0]['left']:0);
		$pgClipartBackRight = (!empty($this->data['clipart']['back'][0]['right'])?$this->data['clipart']['back'][0]['right']:0);
		$pgClipartBackLeft2 = (!empty($this->data['clipart']['back'][1]['left'])?$this->data['clipart']['back'][1]['left']:0);
		$pgClipartBackRight2 = (!empty($this->data['clipart']['back'][1]['right'])?$this->data['clipart']['back'][1]['right']:0);
		//$pgCutAway = $cut_away;

		//var_dump($this->data);

		$query = array(
								'pgType'=>$pgType,
								'pgStyle'=>$pgStyle,
								'invert_dual'=>$invert_dual,
								'cut_away'=>$cut_away,
								'pgFont'=>$pgFont,
								'pgBandColor'=>$pgBandColor,
								'pgMessageColor'=>$pgMessageColor,
								'pgFrontMessage'=>$pgFrontMessage,
								'pgClipartFrontLeft'=>$pgClipartFrontLeft,
								'pgClipartFrontRight'=>$pgClipartFrontRight,
								'pgFrontMessage2'=>$pgFrontMessage2,
								'pgClipartFrontLeft2'=>$pgClipartFrontLeft2,
								'pgClipartFrontRight2'=>$pgClipartFrontRight2,
								'pgBackMessage'=>$pgBackMessage,
								'pgClipartBackLeft'=>$pgClipartBackLeft,
								'pgClipartBackRight'=>$pgClipartBackRight,
								'pgBackMessage2'=>$pgBackMessage2,
								'pgClipartBackLeft2'=>$pgClipartBackLeft2,
								'pgClipartBackRight2'=>$pgClipartBackRight2
								);

		//var_dump($query);die();
		//var_dump($pgType);die();
		$query = http_build_query($query);
		//var_dump($query);die();

		return $query;
		*/
		return '';

	}

	function getDesignUrlQuery2(&$vars) {
		/*
		$pgType = $this->data['band_type'];
		$pgStyle = $this->data['band_style'];
		$invert_dual = intval($this->data['invert_dual'], 10);
		$cut_away = intval($this->data['cut_away'], 10);
		$pgFont = $this->data['band_font'];
		//$pgFrontRows = (!empty($pgFrontRows)?$pgFrontRows:1);
		//$pgBackRows = (!empty($pgBackRows)?$pgBackRows:1);
		//$pgTextCont = (!empty($pgTextCont)?intval($pgTextCont, 10):0);
		$pgBandColor = $this->data['band_color'];
		$pgMessageColor = $this->data['message_color'];
		$pgFrontMessage = (!empty($this->data['messages']['front'][0])?$this->data['messages']['front'][0]:'');
		$pgFrontMessage2 = (!empty($this->data['messages']['front'][1])?$this->data['messages']['front'][1]:'');
		$pgBackMessage = (!empty($this->data['messages']['back'][0])?$this->data['messages']['back'][0]:'');
		$pgBackMessage2 = (!empty($this->data['messages']['back'][1])?$this->data['messages']['back'][1]:'');

		$pgClipartFrontLeft = (!empty($this->data['clipart']['front'][0]['left'])?$this->data['clipart']['front'][0]['left']:0);
		$pgClipartFrontRight = (!empty($this->data['clipart']['front'][0]['right'])?$this->data['clipart']['front'][0]['right']:0);
		$pgClipartFrontLeft2 = (!empty($this->data['clipart']['front'][1]['left'])?$this->data['clipart']['front'][1]['left']:0);
		$pgClipartFrontRight2 = (!empty($this->data['clipart']['front'][1]['right'])?$this->data['clipart']['front'][1]['right']:0);
		$pgClipartBackLeft = (!empty($this->data['clipart']['back'][0]['left'])?$this->data['clipart']['back'][0]['left']:0);
		$pgClipartBackRight = (!empty($this->data['clipart']['back'][0]['right'])?$this->data['clipart']['back'][0]['right']:0);
		$pgClipartBackLeft2 = (!empty($this->data['clipart']['back'][1]['left'])?$this->data['clipart']['back'][1]['left']:0);
		$pgClipartBackRight2 = (!empty($this->data['clipart']['back'][1]['right'])?$this->data['clipart']['back'][1]['right']:0);
		//$pgCutAway = $cut_away;

		//var_dump($this->data);
		$query = array(
								'band_type'=>$pgType,
								'band_style'=>$pgStyle,
								'invert_dual'=>$invert_dual,
								'cut_away'=>$cut_away,
								'band_font'=>$pgFont,
								'band_color'=>$pgBandColor,
								'message_color'=>$pgMessageColor,
								'message_front'=>$pgFrontMessage,
								'clipart_front_left'=>$pgClipartFrontLeft,
								'clipart_front_right'=>$pgClipartFrontRight,
								'message_front2'=>$pgFrontMessage2,
								'clipart_front_left2'=>$pgClipartFrontLeft2,
								'clipart_front_right2'=>$pgClipartFrontRight2,
								'message_back'=>$pgBackMessage,
								'clipart_back_left'=>$pgClipartBackLeft,
								'clipart_back_right'=>$pgClipartBackRight,
								'message_back2'=>$pgBackMessage2,
								'clipart_back_left2'=>$pgClipartBackLeft2,
								'clipart_back_right2'=>$pgClipartBackRight2
								);
		//var_dump($query);die();
		//var_dump($pgType);die();
		$query = http_build_query($query);
		//var_dump($query);die();

		return $query;
		*/
		return '';

	}
	
	
	
	
	function getDesignUrlQuery3(&$vars) {
		return '';
	}
	
	
	function getPricingDataArray(&$vars) {
		$pricingdata = array(
				'Shipping'=>0,
				'Discount'=>0,
				'Tax'=>0,
				'Total_Price'=>$this->price['values']['mbase_price'],
				//'cost',
				//'Total_Cost'
										);
		
		return $pricingdata;
	}
	
	function getDesignNotesString(&$vars) {
		return '';
	}

	function getCartView(&$vars, $index=0) {
		global $tpt_vars;
		$product = $this;
		$tpt_resourceurl = TPT_RESOURCE_URL;

		$types_module = getModule($tpt_vars, 'BandType');
		$styles_module = getModule($tpt_vars, 'BandStyle');
		$styles = $styles_module->moduleData['id'];
		$data_module = getModule($tpt_vars, 'BandData');
//$colors_module = getModule($tpt_vars, "BandColor");
		$colors_module = getModule($tpt_vars, 'BandColor');
		$fonts_module = getModule($tpt_vars, 'BandFont');
		$fonts = $fonts_module->moduleData['id'];
		$rushorder_module = getModule($tpt_vars, 'RushOrder');
		$builder_module = getModule($tpt_vars, 'Builder');
		$cliparts_module = getModule($tpt_vars, 'BandClipart');
		$sbuilders = $builder_module->moduleData['id'];

		$w1 = '406px';
		$w2 = '84px';
		$w3 = '84px';
		$w4 = '98px';
		$w5 = '82px';

		$product_html = '';
		if (isDev('devcart') && !empty($_GET['devcart'])) {
			$product_html = '<pre class="text-align-left">'.var_export($this, true).'</pre>';
		}
//        $labels_width = '114px';
		$labels_width = '80px';
		$product_details = array();
		$product_details_l = array();
		$product_details_v = array();
		$updateform = '';
		$deleteform = '';
		$qty = 1;
//		$qtybgurl = TPT_IMAGES_URL . '/buttons/cart-qty-field.png';
		$qtybgurl = TPT_IMAGES_URL . '/cart-elem-spr.png';
		$bgurl = TPT_IMAGES_URL . '/cart-elem-spr.png';
		$bandSize = '&nbsp;';
		$bandColor = '&nbsp;';
		//tpt_dump($product->data['band_color'],true);
		//tpt_dump(getModule($tpt_vars, "BandColor")->getColorProps($vars, $product->data['band_color']),true);

		$productDesign = '';

		$name = '';
		$url = '';
		$addons = array();
		$imgurl = '';
		$subtotal = $product->price['html']['mbase_price'];
		//tpt_dump($product->price['html'], true);
		//var_dump($product);die();
		$sptype = array();
		$pdata = array();

		//$pclass = ''; vj edit

			//tpt_dump($products, false, 'V');
			$sptype = amz_cart::$stockProductsTypesData[$product->data['stock_product_type_id']];
			$name = $product->data['label'];
//            $url = $tpt_vars['url']['handler']->wrap($tpt_vars, $product->data['product_url']);
			$descr = $product->data['descr'];
			$sku = str_replace('.', '<span class="display-inline-block"></span>.<span class="display-inline-block"></span>', $product->getSku($tpt_vars));
			$imgurl = $product->getCachedProductImageUrl($tpt_vars);
			$tmgurl = $product->getCachedProductThumbUrl($tpt_vars);
			$typeName = htmlentities(getModule($tpt_vars, "BandType")->moduleData['id'][$product->data['type']]['name']);
			$styleName = htmlentities(getModule($tpt_vars, "BandStyle")->moduleData['id'][$product->data['style']]['name']);

			$subtotal = $product->price['html']['mbase_price'];

			//$pclass = ' stockproduct'.$product->id; vj edit

		//var_dump($product->data);die();

		$qty = '<div class="display-inline-block width-70 height-16 padding-top-2 padding-bottom-7 padding-left-3 padding-right-2" style="background-position: 0px -185px; background-image: url(' . $qtybgurl . ');">' . tpt_html::createTextinput($tpt_vars, 'qty', $product->qty, ' class="amz_red width-70 line-height-24 plain-input-field text-align-center"') . '</div>';

		$pg_x = 115;
		$pg_y = 61;
		$previewtime = time();
		$bandbg = '';
		if (isset($product->data['band_color'])) {
			$bandbg = $colors_module->getBandBGStyle($tpt_vars, $product->data['band_color'], $product->data['message_color'], $pg_x, $pg_y);
		}

		if (is_a($product, 'amz_customStockproduct')) {
			$img = '<img class="position-relative z-index-2 max-width-100prc max-height-100prc" src="' . $tmgurl . '" />';
		}

		$image = <<< EOT
<div style="background-color: #F0F0F0;" class="itemid_$index shopping_cart_product_small_preview width-115 height-70 position-relative padding-top-5 padding-bottom-5 overflow-hidden">

	<a class="thickbox winrf_sc_product_big_preview" href="#TB_inline?width=800&amp;height=200&amp;inlineId=_">Open Big Preview</a>

    <div class="position-absolute top-0 left-0 right-0 bottom-0 z-index-3" style="background-image: url($bgurl);"></div>
    <div style="$bandbg;">
        $img
    </div>
</div>
EOT;
		//var_dump($product->price);die();
//big preview popup
		//if($_SERVER['REMOTE_ADDR'] != '109.160.0.218') {
		//$big_preview='<div class="targetid_'.$index.' cart_big_preview">'.$product->getPreviewHTML($tpt_vars).'</div>';
		//} else {
		$banddesignurl = $product->getCachedProductImageUrl($tpt_vars);
		//tpt_dump($banddesignurl, true);
		$big_preview = '<div class="targetid_' . $index . ' cart_big_preview">
                    <img src="' . $banddesignurl . '" />
                </div>';
		$prevHeight = $data_module->typeStyle[$product->data['type']][$product->data['style']]['preview_height'];
		$productDesign = <<< EOT
<div class="padding-left-5 padding-right-5 padding-top-5 padding-bottom-5">
    <div class="position-relative background-repeat-no-repeat background-position-CC height-$prevHeight z-index-3" style="border: 1px solid #5B3824; background-image: url($banddesignurl);">
    </div>
</div>
EOT;
		$productDesign = <<< EOT
<div class="position-relative padding-left-5 padding-right-5 padding-top-0 padding-bottom-5 text-align-center z-index-3">
    <img src="$banddesignurl" style="border: 1px solid #5B3824;" class="resize" />
</div>
EOT;

		if (is_a($product, 'amz_customStockproduct')) $productDesign = str_replace('text-align-center', 'text-align-left', $productDesign);

		//$productDesign = '';
		//}

		$image .= $big_preview;

		$oddclass = '';
		$evenclass = ' style="background-color: #f2f2f2;"';

		$rowclass = $evenclass;


		if (!empty($secretcart) || (!empty($_GET['debug']) && ($_GET['debug'] == 'delcache'))) {
			//if($_GET['grr']=='grr' || true || false) {
			$rowclass = ($rowclass == $oddclass ? $evenclass : $oddclass);

			$productdesignimage = $tpt_vars['url']['handler']->wrap($tpt_vars, '/generate-preview') . '?type=flat&' . $product->getDesignUrlQuery($tpt_vars);
			$productdesignimage = '<a href="' . $productdesignimage . '" title="' . $productdesignimage . '">Flat Preview</a>';
			$product_details[] = '<div class="padding-top-2 padding-bottom-2"' . $rowclass . '><div class="font-weight-bold float-left text-align-right padding-right-5 urlabel width-80">Flat Img:</div><div class="amz_red overflow-hidden">' . $productdesignimage . '</div></div>';
			$product_details_l[] = 'Product Flat Preview:';
			$product_details_v[] = $productdesignimage;
			//}


			$rowclass = ($rowclass == $oddclass ? $evenclass : $oddclass);

			$productdesignlink = $tpt_vars['url']['handler']->wrap($tpt_vars, '/get-preview') . '?' . $product->getDesignUrlQuery2($tpt_vars);
			$productdesignlink = '<a href="' . $productdesignlink . '" title="' . $productdesignlink . '">Html Preview</a>';
			$product_details[] = '<div class="padding-top-2 padding-bottom-2"' . $rowclass . '><div class="font-weight-bold float-left text-align-right padding-right-5 urlabel width-80">Layered Img:</div><div class="amz_red overflow-hidden">' . $productdesignlink . '</div></div>';
			$product_details_l[] = 'Product Layered Preview:';
			$product_details_v[] = $productdesignlink;


			$rowclass = ($rowclass == $oddclass ? $evenclass : $oddclass);


			$qry = $tpt_vars['url']['qry'];
			$qry['delcached'] = $product->getCachedImageName($tpt_vars);
			$delcachedlink = $tpt_vars['url']['handler']->wrap($tpt_vars, $tpt_vars['url']['upath']) . '?' . http_build_query($qry);
			$delcachedlink = <<< EOT
            <a class="plain-link" href="$delcachedlink" title="Refresh this product&quot;s cached flat image">Refresh cached image</a>
EOT;
			$product_details[] = '<div class="padding-top-2 padding-bottom-2"' . $rowclass . '><div class="font-weight-bold float-left text-align-right padding-right-5 urlabel width-80" >RefCch:</div><div class="amz_red overflow-hidden">' . $delcachedlink . '</div></div>';
			$product_details_l[] = 'Product Layered Preview:';
			$product_details_v[] = $productdesignlink;
		}

		if (!empty($sku)) {
			$rowclass = ($rowclass == $oddclass ? $evenclass : $oddclass);
			$product_details[] = '<div class="padding-top-2 padding-bottom-2"' . $rowclass . '><div class="font-weight-bold float-left text-align-right padding-right-5 urlabel padding-left-15">ID:</div><div id="order-details-id" class="amz_red overflow-hidden">' . $sku . '</div></div>';
			$product_details_l[] = 'ID:';
			$product_details_v[] = $sku;
		}

		if (!empty($typeName)) {
			$rowclass = ($rowclass == $oddclass ? $evenclass : $oddclass);

			$product_details[] = '<div class="padding-top-2 padding-bottom-2"' . $rowclass . '><div class="font-weight-bold float-left text-align-right padding-right-5 urlabel padding-left-15">Type:</div><div class="amz_red overflow-hidden">' . $typeName . '</div></div>';
			$product_details_l[] = 'Type:';
			$product_details_v[] = getModule($tpt_vars, "BandType")->moduleData['id'][$product->data['type']]['name'];
		}

		if (!empty($styleName)) {
			$rowclass = ($rowclass == $oddclass ? $evenclass : $oddclass);

			$product_details[] = '<div class="padding-top-2 padding-bottom-2"' . $rowclass . '><div class="font-weight-bold float-left text-align-right padding-right-5 urlabel padding-left-15">Style:</div><div class="amz_red overflow-hidden">' . $styleName . '</div></div>';
			$product_details_l[] = 'Style:';
			$product_details_v[] = getModule($tpt_vars, "BandStyle")->moduleData['id'][$product->data['style']]['name'];
		}

		if (is_a($product, 'amz_bundle')) {
			$rowclass = ($rowclass == $oddclass ? $evenclass : $oddclass);

			$productsNames = array();
			foreach ($product->pids as $pid) {
				$productsNames[] = amz_cart::$stockProductsData[$pid]['label'];
			}
			$productsNames = implode('<br /><br />', $productsNames);

			$product_details[] = '<div class="padding-top-2 padding-bottom-2 clearBoth"' . $rowclass . '><div class="font-weight-bold float-left text-align-right padding-right-5 urlabel" style="width: ' . $labels_width . ';">Desc:</div><div class="amz_red overflow-hidden">' . $productsNames . '</div></div>';
			$product_details_l[] = 'Products:';
			$product_details_v[] = $productsNames;
		} else if (!empty($name)) {
			if (!is_a($product, 'amz_customproduct')) {
				$rowclass = ($rowclass == $oddclass ? $evenclass : $oddclass);

				$product_details[] = '<div class="padding-top-2 padding-bottom-2 clearBoth"' . $rowclass . '><div class="font-weight-bold float-left text-align-right padding-right-5 urlabel" style="width: ' . $labels_width . ';">Desc:</div><div class="amz_red overflow-hidden">' . $name . '</div></div>';
				$product_details_l[] = 'Desc:';
				$product_details_v[] = $name;
			}
		}


		//tpt_dump($product);
		if ($_SERVER['REMOTE_ADDR'] == '182.65.198.99') { //vj edits 85.130.71.163
			//$flclipart_img = getModule($tpt_vars, "BandClipart")->getClipartImage($tpt_vars, $product->data['clipart']['front'][0]['left']);
			//print('<pre>');print_r($flclipart_img);print_r($product->data);print('</pre>');
			print('<pre>');
			//print_r($product->data['message_color']);
			//print_r($pdata['blank']);
			//print_r($bdata['message_color']);
			print('</pre>');
		}
		//var_dump($product->data);die();


		if (empty($pdata['blank'])) {
			if (!empty($product->data['messages']['front'][0])) {
				$fmessage = htmlentities($product->data['messages']['front'][0]);
				$rowclass = ($rowclass == $oddclass ? $evenclass : $oddclass);

				$product_details[] = '<div class="padding-top-2 padding-bottom-2 clearBoth"' . $rowclass . '><div class="font-weight-bold float-left text-align-right padding-right-5 urlabel" style="width: ' . $labels_width . ';">Front Message:</div><div class="amz_red overflow-hidden font-size-14 font-weight-bold">' . $fmessage . '</div></div>';
				$product_details_l[] = 'Front message:';
				$product_details_v[] = $fmessage;
			}

			if (!empty($product->data['clipart']['front'][0]['left'])) {
				$flclipart_path = getModule($tpt_vars, "BandClipart")->getClipartURL($tpt_vars, $product->data['clipart']['front'][0]['left']);
				$flclipart_e = array_filter(explode('/', $flclipart_path));
				if (!empty($flclipart_e) && isset($flclipart_e[count($flclipart_e) - 1])) {
					$flclipart_e = array_filter(explode('.', $flclipart_e[count($flclipart_e) - 1]));
					array_pop($flclipart_e);
					$flclipart = htmlentities(preg_replace('#[\W_]+#', ' ', implode('.', $flclipart_e)));
					$rowclass = ($rowclass == $oddclass ? $evenclass : $oddclass);

					$product_details[] = '<div class="padding-top-2 padding-bottom-2 clearBoth"' . $rowclass . '><div class="font-weight-bold float-left text-align-right padding-right-5 urlabel" style="width: ' . $labels_width . ';">Front Art Left:</div><div class="amz_red overflow-hidden">' . $flclipart . '</div><div class="height-50 text-align-center max-width-100prc max-height-100prc"><img src="' . CLIPARTS_URL . DIRECTORY_SEPARATOR . $flclipart_path . '" /></div></div>';
					$product_details_l[] = 'Front Art Left:';
					$product_details_v[] = $flclipart;
				}
			}

			if (!empty($product->data['clipart_c']['front'][0]['left'])) {
				/*
				$flclipart_path = getModule($tpt_vars, "BandClipart")->getClipartURL($tpt_vars, $product->data['clipart']['front'][0]['left']);
				$flclipart_e = array_filter(explode('/', $flclipart_path));
				if (!empty($flclipart_e)) {
					$flclipart_e = array_filter(explode('.', $flclipart_e[count($flclipart_e) - 1]));
					array_pop($flclipart_e);
					$flclipart = htmlentities(preg_replace('#[\W_]+#', ' ', implode('.', $flclipart_e)));
					$rowclass = ($rowclass == $oddclass ? $evenclass : $oddclass);

					$product_details[] = '<div class="padding-top-2 padding-bottom-2 clearBoth"' . $rowclass . '><div class="font-weight-bold float-left text-align-right padding-right-5" style="width: ' . $labels_width . ';">Front Art Left:</div><div class="amz_red overflow-hidden">' . $flclipart . '</div><div class="height-50 text-align-center"><img class="max-width-100prc max-height-100prc" src="' . $tpt_resourceurl . '/clipart/' . $flclipart_path . '" /></div></div>';
					$product_details_l[] = 'Front Art Left:';
					$product_details_v[] = $flclipart;
				}
				*/

				$flclipart_c = $product->data['clipart_c']['front'][0]['left'];
				$product_details[] = '<div class="padding-top-2 padding-bottom-2 clearBoth"' . $rowclass . '><div class="font-weight-bold float-left text-align-right padding-right-5 urlabel" style="width: ' . $labels_width . ';">Front Custom Art Left:</div><div class="amz_red overflow-hidden">' . $flclipart_c . '</div><div class="height-50 text-align-center"><img class="max-width-99prc max-height-100prc" src="' . $cliparts_module->getConvertCustomArtImageUrl($tpt_vars, $flclipart_c, '80', '80', 'png') .'" /></div></div>';
				$product_details_l[] = 'Front Custom Art Left:';
				$product_details_v[] = $flclipart_c;
			}

			if (!empty($product->data['clipart']['front'][0]['right'])) {
				$frclipart_path = getModule($tpt_vars, "BandClipart")->getClipartURL($tpt_vars, $product->data['clipart']['front'][0]['right']);
				$frclipart_e = array_filter(explode('/', $frclipart_path));
				if (!empty($frclipart_e) && isset($frclipart_e[count($frclipart_e) - 1])) {
					$frclipart_e = array_filter(explode('.', $frclipart_e[count($frclipart_e) - 1]));
					array_pop($frclipart_e);
					$frclipart = htmlentities(preg_replace('#[\W_]+#', ' ', implode('.', $frclipart_e)));
					$rowclass = ($rowclass == $oddclass ? $evenclass : $oddclass);

					$product_details[] = '<div class="padding-top-2 padding-bottom-2 clearBoth"' . $rowclass . '><div class="font-weight-bold float-left text-align-right padding-right-5 urlabel" style="width: ' . $labels_width . ';">Front Art Right:</div><div class="amz_red overflow-hidden">' . $frclipart . '</div><div class="height-50 text-align-center"><img class="max-width-98prc max-height-100prc" src="' . CLIPARTS_URL . DIRECTORY_SEPARATOR . $frclipart_path . '" /></div></div>';
					$product_details_l[] = 'Front Art Right:';
					$product_details_v[] = $frclipart;
				}
			}

			if (!empty($product->data['clipart_c']['front'][0]['right'])) {
				/*
				$frclipart_path = getModule($tpt_vars, "BandClipart")->getClipartURL($tpt_vars, $product->data['clipart']['front'][0]['right']);
				$frclipart_e = array_filter(explode('/', $frclipart_path));
				if (!empty($frclipart_e)) {
					$frclipart_e = array_filter(explode('.', $frclipart_e[count($frclipart_e) - 1]));
					array_pop($frclipart_e);
					$frclipart = htmlentities(preg_replace('#[\W_]+#', ' ', implode('.', $frclipart_e)));
					$rowclass = ($rowclass == $oddclass ? $evenclass : $oddclass);

					$product_details[] = '<div class="padding-top-2 padding-bottom-2 clearBoth"' . $rowclass . '><div class="font-weight-bold float-left text-align-right padding-right-5" style="width: ' . $labels_width . ';">Front Art Right:</div><div class="amz_red overflow-hidden">' . $frclipart . '</div><div class="height-50 text-align-center"><img class="max-width-100prc max-height-100prc" src="' . $tpt_resourceurl . '/clipart/' . $frclipart_path . '" /></div></div>';
					$product_details_l[] = 'Front Art Right:';
					$product_details_v[] = $frclipart;
				}
				*/

				$frclipart_c = $product->data['clipart_c']['front'][0]['right'];
				$product_details[] = '<div class="padding-top-2 padding-bottom-2 clearBoth"' . $rowclass . '><div class="font-weight-bold float-left text-align-right padding-right-5 urlabel" style="width: ' . $labels_width . ';">Front Custom Art Right:</div><div class="amz_red overflow-hidden">' . $frclipart_c . '</div><div class="height-50 text-align-center"><img class="max-width-97prc max-height-100prc" src="' . $cliparts_module->getConvertCustomArtImageUrl($tpt_vars, $frclipart_c, '80', '80', 'png') .'" /></div></div>';
				$product_details_l[] = 'Front Custom Art Right:';
				$product_details_v[] = $frclipart_c;
			}


			if (!empty($product->data['messages']['front']['1'])) {
				$fmessage2 = htmlentities($product->data['messages']['front']['1']);
				$rowclass = ($rowclass == $oddclass ? $evenclass : $oddclass);

				$product_details[] = '<div class="padding-top-2 padding-bottom-2 clearBoth"' . $rowclass . '><div class="font-weight-bold float-left text-align-right padding-right-5 urlabel" style="width: ' . $labels_width . ';">Front Message Ln2:</div><div class="amz_red overflow-hidden font-size-14 font-weight-bold">' . $fmessage2 . '</div></div>';
				$product_details_l[] = 'Front message Ln2:';
				$product_details_v[] = $fmessage2;
			}

			if (!empty($product->data['clipart']['front'][1]['left'])) {
				$flclipart2_path = getModule($tpt_vars, "BandClipart")->getClipartURL($tpt_vars, $product->data['clipart']['front'][1]['left']);
				$flclipart2_e = array_filter(explode('/', $flclipart2_path));
				if (!empty($flclipart2_e) && isset($flclipart2_e[count($flclipart2_e) - 1])) {
					$flclipart2_e = array_filter(explode('.', $flclipart2_e[count($flclipart2_e) - 1]));
					array_pop($flclipart2_e);
					$flclipart2 = htmlentities(preg_replace('#[\W_]+#', ' ', implode('.', $flclipart2_e)));
					$rowclass = ($rowclass == $oddclass ? $evenclass : $oddclass);

					$product_details[] = '<div class="padding-top-2 padding-bottom-2 clearBoth"' . $rowclass . '><div class="font-weight-bold float-left text-align-right padding-right-5 urlabel" style="width: ' . $labels_width . ';">Front Art Left Ln2:</div><div class="amz_red overflow-hidden">' . $flclipart2 . '</div><div class="height-50 text-align-center"><img class="max-width-96prc max-height-100prc" src="' . CLIPARTS_URL . DIRECTORY_SEPARATOR . $flclipart2_path . '" /></div></div>';
					$product_details_l[] = 'Front Art Left Ln2:';
					$product_details_v[] = $flclipart2;
				}
			}

			if (!empty($product->data['clipart_c']['front'][1]['left'])) {
				/*
				$flclipart2_path = getModule($tpt_vars, "BandClipart")->getClipartURL($tpt_vars, $product->data['clipart']['front'][1]['left']);
				$flclipart2_e = array_filter(explode('/', $flclipart2_path));
				if (!empty($flclipart2_e)) {
					$flclipart2_e = array_filter(explode('.', $flclipart2_e[count($flclipart2_e) - 1]));
					array_pop($flclipart2_e);
					$flclipart2 = htmlentities(preg_replace('#[\W_]+#', ' ', implode('.', $flclipart2_e)));
					$rowclass = ($rowclass == $oddclass ? $evenclass : $oddclass);

					$product_details[] = '<div class="padding-top-2 padding-bottom-2 clearBoth"' . $rowclass . '><div class="font-weight-bold float-left text-align-right padding-right-5" style="width: ' . $labels_width . ';">Front Art Left Ln2:</div><div class="amz_red overflow-hidden">' . $flclipart2 . '</div><div class="height-50 text-align-center"><img class="max-width-100prc max-height-100prc" src="' . $tpt_resourceurl . '/clipart/' . $flclipart2_path . '" /></div></div>';
					$product_details_l[] = 'Front Art Left Ln2:';
					$product_details_v[] = $flclipart2;
				}
				*/

				$flclipart2_c = $product->data['clipart_c']['front'][1]['left'];
				$product_details[] = '<div class="padding-top-2 padding-bottom-2 clearBoth"' . $rowclass . '><div class="font-weight-bold float-left text-align-right padding-right-5 urlabel" style="width: ' . $labels_width . ';">Front Custom Art Left Ln2:</div><div class="amz_red overflow-hidden">' . $flclipart2_c . '</div><div class="height-50 text-align-center"><img class="max-width-95prc max-height-100prc" src="' . $cliparts_module->getConvertCustomArtImageUrl($tpt_vars, $flclipart2_c, '80', '80', 'png') .'" /></div></div>';
				$product_details_l[] = 'Front Custom Art Left Ln2:';
				$product_details_v[] = $flclipart2_c;
			}

			if (!empty($product->data['clipart']['front'][1]['right'])) {
				$frclipart2_path = getModule($tpt_vars, "BandClipart")->getClipartURL($tpt_vars, $product->data['clipart']['front'][1]['right']);
				$frclipart2_e = array_filter(explode('/', $frclipart2_path));
				if (!empty($frclipart2_e) && isset($frclipart2_e[count($frclipart2_e) - 1])) {
					$frclipart2_e = array_filter(explode('.', $frclipart2_e[count($frclipart2_e) - 1]));
					array_pop($frclipart2_e);
					$frclipart2 = htmlentities(preg_replace('#[\W_]+#', ' ', implode('.', $frclipart2_e)));
					$rowclass = ($rowclass == $oddclass ? $evenclass : $oddclass);

					$product_details[] = '<div class="padding-top-2 padding-bottom-2 clearBoth"' . $rowclass . '><div class="font-weight-bold float-left text-align-right padding-right-5 urlabel" style="width: ' . $labels_width . ';">Front Art Right Ln2:</div><div class="amz_red overflow-hidden">' . $frclipart2 . '</div><div class="height-50 text-align-center"><img class="max-width-94prc max-height-100prc" src="' . CLIPARTS_URL . DIRECTORY_SEPARATOR . $frclipart2_path . '" /></div></div>';
					$product_details_l[] = 'Front Art Right Ln2:';
					$product_details_v[] = $frclipart2;
				}
			}

			if (!empty($product->data['clipart_c']['front'][1]['right'])) {
				/*
				$frclipart2_path = getModule($tpt_vars, "BandClipart")->getClipartURL($tpt_vars, $product->data['clipart']['front'][1]['right']);
				$frclipart2_e = array_filter(explode('/', $frclipart2_path));
				if (!empty($frclipart2_e)) {
					$frclipart2_e = array_filter(explode('.', $frclipart2_e[count($frclipart2_e) - 1]));
					array_pop($frclipart2_e);
					$frclipart2 = htmlentities(preg_replace('#[\W_]+#', ' ', implode('.', $frclipart2_e)));
					$rowclass = ($rowclass == $oddclass ? $evenclass : $oddclass);

					$product_details[] = '<div class="padding-top-2 padding-bottom-2 clearBoth"' . $rowclass . '><div class="font-weight-bold float-left text-align-right padding-right-5" style="width: ' . $labels_width . ';">Front Art Right Ln2:</div><div class="amz_red overflow-hidden">' . $frclipart2 . '</div><div class="height-50 text-align-center"><img class="max-width-100prc max-height-100prc" src="' . $tpt_resourceurl . '/clipart/' . $frclipart2_path . '" /></div></div>';
					$product_details_l[] = 'Front Art Right Ln2:';
					$product_details_v[] = $frclipart2;
				}
				*/

				$frclipart2_c = $product->data['clipart_c']['front'][1]['right'];
				$product_details[] = '<div class="padding-top-2 padding-bottom-2 clearBoth"' . $rowclass . '><div class="font-weight-bold float-left text-align-right padding-right-5 urlabel" style="width: ' . $labels_width . ';">Front Custom Art Right Ln2:</div><div class="amz_red overflow-hidden">' . $frclipart2_c . '</div><div class="height-50 text-align-center"><img class="max-width-93prc max-height-100prc" src="' . $cliparts_module->getConvertCustomArtImageUrl($tpt_vars, $frclipart2_c, '80', '80', 'png') .'" /></div></div>';
				$product_details_l[] = 'Front Custom Art Right Ln2:';
				$product_details_v[] = $frclipart2_c;
			}

			//var_dump($flclipart_e);
			//var_dump($frclipart_e);


			if (!empty($product->data['messages']['back'][0])) {
				$bmessage = htmlentities($product->data['messages']['back'][0]);
				$rowclass = ($rowclass == $oddclass ? $evenclass : $oddclass);

				$product_details[] = '<div class="padding-top-2 padding-bottom-2 clearBoth"' . $rowclass . '><div class="font-weight-bold float-left text-align-right padding-right-5 urlabel" style="width: ' . $labels_width . ';">Back Message:</div><div class="amz_red overflow-hidden font-size-14 font-weight-bold">' . $bmessage . '</div></div>';
				$product_details_l[] = 'Back message:';
				$product_details_v[] = $bmessage;
			}


			if (!empty($product->data['clipart']['back'][0]['left'])) {
				$blclipart_path = getModule($tpt_vars, "BandClipart")->getClipartURL($tpt_vars, $product->data['clipart']['back'][0]['left']);
				$blclipart_e = array_filter(explode('/', $blclipart_path));
				if (!empty($blclipart_e) && isset($blclipart_e[count($blclipart_e) - 1])) {
					$blclipart_e = array_filter(explode('.', $blclipart_e[count($blclipart_e) - 1]));
					array_pop($blclipart_e);
					$blclipart = htmlentities(preg_replace('#[\W_]+#', ' ', implode('.', $blclipart_e)));
					$rowclass = ($rowclass == $oddclass ? $evenclass : $oddclass);

					$product_details[] = '<div class="padding-top-2 padding-bottom-2 clearBoth"' . $rowclass . '><div class="font-weight-bold float-left text-align-right padding-right-5 urlabel" style="width: ' . $labels_width . ';">Back Art Left:</div><div class="amz_red overflow-hidden">' . $blclipart . '</div><div class="height-50 text-align-center"><img class="max-width-100prc max-height-92prc" src="' . CLIPARTS_URL . DIRECTORY_SEPARATOR . $blclipart_path . '" /></div></div>';
					$product_details_l[] = 'Back Art Left:';
					$product_details_v[] = $blclipart;
				}
			}

			if (!empty($product->data['clipart_c']['back'][0]['left'])) {
				/*
				$blclipart_path = getModule($tpt_vars, "BandClipart")->getClipartURL($tpt_vars, $product->data['clipart']['back'][0]['left']);
				$blclipart_e = array_filter(explode('/', $blclipart_path));
				if (!empty($blclipart_e)) {
					$blclipart_e = array_filter(explode('.', $blclipart_e[count($blclipart_e) - 1]));
					array_pop($blclipart_e);
					$blclipart = htmlentities(preg_replace('#[\W_]+#', ' ', implode('.', $blclipart_e)));
					$rowclass = ($rowclass == $oddclass ? $evenclass : $oddclass);

					$product_details[] = '<div class="padding-top-2 padding-bottom-2 clearBoth"' . $rowclass . '><div class="font-weight-bold float-left text-align-right padding-right-5" style="width: ' . $labels_width . ';">Back Art Left:</div><div class="amz_red overflow-hidden">' . $blclipart . '</div><div class="height-50 text-align-center"><img class="max-width-100prc max-height-100prc" src="' . $tpt_resourceurl . '/clipart/' . $blclipart_path . '" /></div></div>';
					$product_details_l[] = 'Back Art Left:';
					$product_details_v[] = $blclipart;
				}
				*/

				$blclipart_c = $product->data['clipart_c']['back'][0]['left'];
				$product_details[] = '<div class="padding-top-2 padding-bottom-2 clearBoth"' . $rowclass . '><div class="font-weight-bold float-left text-align-right padding-right-5 urlabel" style="width: ' . $labels_width . ';">Back Custom Art Left:</div><div class="amz_red overflow-hidden">' . $blclipart_c . '</div><div class="height-50 text-align-center"><img class="max-width-100prc max-height-91prc" src="' . $cliparts_module->getConvertCustomArtImageUrl($tpt_vars, $blclipart_c, '80', '80', 'png') .'" /></div></div>';
				$product_details_l[] = 'Back Custom Art Left:';
				$product_details_v[] = $blclipart_c;
			}

			if (!empty($product->data['clipart']['back'][0]['right'])) {
				$brclipart_path = getModule($tpt_vars, "BandClipart")->getClipartURL($tpt_vars, $product->data['clipart']['back'][0]['right']);
				$brclipart_e = array_filter(explode('/', $brclipart_path));
				if (!empty($brclipart_e) && isset($brclipart_e[count($brclipart_e) - 1])) {
					$brclipart_e = array_filter(explode('.', $brclipart_e[count($brclipart_e) - 1]));
					array_pop($brclipart_e);
					$brclipart = htmlentities(preg_replace('#[\W_]+#', ' ', implode('.', $brclipart_e)));
					$rowclass = ($rowclass == $oddclass ? $evenclass : $oddclass);

					$product_details[] = '<div class="padding-top-2 padding-bottom-2 clearBoth"' . $rowclass . '><div class="font-weight-bold float-left text-align-right padding-right-5 urlabel" style="width: ' . $labels_width . ';">Back Art Right:</div><div class="amz_red overflow-hidden">' . $brclipart . '</div><div class="height-50 text-align-center"><img class="max-width-90prc max-height-100prc" src="' . CLIPARTS_URL . DIRECTORY_SEPARATOR . $brclipart_path . '" /></div></div>';
					$product_details_l[] = 'Back Art Right:';
					$product_details_v[] = $brclipart;
				}

			}

			if (!empty($product->data['clipart_c']['back'][0]['right'])) {
				/*
				$brclipart_path = getModule($tpt_vars, "BandClipart")->getClipartURL($tpt_vars, $product->data['clipart']['back'][0]['right']);
				$brclipart_e = array_filter(explode('/', $brclipart_path));
				if (!empty($brclipart_e)) {
					$brclipart_e = array_filter(explode('.', $brclipart_e[count($brclipart_e) - 1]));
					array_pop($brclipart_e);
					$brclipart = htmlentities(preg_replace('#[\W_]+#', ' ', implode('.', $brclipart_e)));
					$rowclass = ($rowclass == $oddclass ? $evenclass : $oddclass);

					$product_details[] = '<div class="padding-top-2 padding-bottom-2 clearBoth"' . $rowclass . '><div class="font-weight-bold float-left text-align-right padding-right-5" style="width: ' . $labels_width . ';">Back Art Right:</div><div class="amz_red overflow-hidden">' . $brclipart . '</div><div class="height-50 text-align-center"><img class="max-width-100prc max-height-100prc" src="' . $tpt_resourceurl . '/clipart/' . $brclipart_path . '" /></div></div>';
					$product_details_l[] = 'Back Art Right:';
					$product_details_v[] = $brclipart;
				}
				*/

				$brclipart_c = $product->data['clipart_c']['back'][0]['right'];
				$product_details[] = '<div class="padding-top-2 padding-bottom-2 clearBoth"' . $rowclass . '><div class="font-weight-bold float-left text-align-right padding-right-5 urlabel" style="width: ' . $labels_width . ';">Back Custom Art Right:</div><div class="amz_red overflow-hidden">' . $brclipart_c . '</div><div class="height-50 text-align-center"><img class="max-width-89prc max-height-100prc" src="' . $cliparts_module->getConvertCustomArtImageUrl($tpt_vars, $brclipart_c, '80', '80', 'png') .'" /></div></div>';
				$product_details_l[] = 'Back Custom Art Right:';
				$product_details_v[] = $brclipart_c;
			}


			if (!empty($product->data['messages']['back']['1'])) {
				$bmessage2 = htmlentities($product->data['messages']['back']['1']);
				$rowclass = ($rowclass == $oddclass ? $evenclass : $oddclass);

				$product_details[] = '<div class="padding-top-2 padding-bottom-2 clearBoth"' . $rowclass . '><div class="font-weight-bold float-left text-align-right padding-right-5 urlabel" style="width: ' . $labels_width . ';">Back Message Ln2:</div><div class="amz_red overflow-hidden font-size-14 font-weight-bold">' . $bmessage2 . '</div></div>';
				$product_details_l[] = 'Back message Ln2:';
				$product_details_v[] = $bmessage2;
			}

			if (!empty($product->data['clipart']['back'][1]['left'])) {
				$blclipart2_path = getModule($tpt_vars, "BandClipart")->getClipartURL($tpt_vars, $product->data['clipart']['back'][1]['left']);
				$blclipart2_e = array_filter(explode('/', $blclipart2_path));
				if (!empty($blclipart2_e) && isset($blclipart2_e[count($blclipart2_e) - 1])) {
					$blclipart2_e = array_filter(explode('.', $blclipart2_e[count($blclipart2_e) - 1]));
					array_pop($blclipart2_e);
					$blclipart2 = htmlentities(preg_replace('#[\W_]+#', ' ', implode('.', $blclipart2_e)));
					$rowclass = ($rowclass == $oddclass ? $evenclass : $oddclass);

					$product_details[] = '<div class="padding-top-2 padding-bottom-2 clearBoth"' . $rowclass . '><div class="font-weight-bold float-left text-align-right padding-right-5 urlabel" style="width: ' . $labels_width . ';">Back Art Left Ln2:</div><div class="amz_red overflow-hidden">' . $blclipart2 . '</div><div class="height-50 text-align-center"><img class="max-width-88prc max-height-100prc" src="' . CLIPARTS_URL . DIRECTORY_SEPARATOR . $blclipart2_path . '" /></div></div>';
					$product_details_l[] = 'Back Art Left Ln2:';
					$product_details_v[] = $blclipart2;
				}
			}

			if (!empty($product->data['clipart_c']['back'][1]['left'])) {
				/*
				$blclipart2_path = getModule($tpt_vars, "BandClipart")->getClipartURL($tpt_vars, $product->data['clipart']['back'][1]['left']);
				$blclipart2_e = array_filter(explode('/', $blclipart2_path));
				if (!empty($blclipart2_e)) {
					$blclipart2_e = array_filter(explode('.', $blclipart2_e[count($blclipart2_e) - 1]));
					array_pop($blclipart2_e);
					$blclipart2 = htmlentities(preg_replace('#[\W_]+#', ' ', implode('.', $blclipart2_e)));
					$rowclass = ($rowclass == $oddclass ? $evenclass : $oddclass);

					$product_details[] = '<div class="padding-top-2 padding-bottom-2 clearBoth"' . $rowclass . '><div class="font-weight-bold float-left text-align-right padding-right-5" style="width: ' . $labels_width . ';">Back Art Left Ln2:</div><div class="amz_red overflow-hidden">' . $blclipart2 . '</div><div class="height-50 text-align-center"><img class="max-width-100prc max-height-100prc" src="' . $tpt_resourceurl . '/clipart/' . $blclipart2_path . '" /></div></div>';
					$product_details_l[] = 'Back Art Left Ln2:';
					$product_details_v[] = $blclipart2;
				}
				*/

				$blclipart2_c = $product->data['clipart_c']['back'][1]['left'];
				$product_details[] = '<div class="padding-top-2 padding-bottom-2 clearBoth"' . $rowclass . '><div class="font-weight-bold float-left text-align-right padding-right-5 urlabel" style="width: ' . $labels_width . ';">Back Custom Art Left Ln2:</div><div class="amz_red overflow-hidden">' . $blclipart2_c . '</div><div class="height-50 text-align-center"><img class="max-width-87prc max-height-100prc" src="' . $cliparts_module->getConvertCustomArtImageUrl($tpt_vars, $blclipart2_c, '80', '80', 'png') .'" /></div></div>';
				$product_details_l[] = 'Back Custom Art Left Ln2:';
				$product_details_v[] = $blclipart2_c;
			}

			if (!empty($product->data['clipart']['back'][1]['right'])) {
				$brclipart2_path = getModule($tpt_vars, "BandClipart")->getClipartURL($tpt_vars, $product->data['clipart']['back'][1]['right']);
				$brclipart2_e = array_filter(explode('/', $brclipart2_path));
				if (!empty($brclipart2_e) && isset($brclipart2_e[count($brclipart2_e) - 1])) {
					$brclipart2_e = array_filter(explode('.', $brclipart2_e[count($brclipart2_e) - 1]));
					array_pop($brclipart2_e);
					$brclipart2 = htmlentities(preg_replace('#[\W_]+#', ' ', implode('.', $brclipart2_e)));
					$rowclass = ($rowclass == $oddclass ? $evenclass : $oddclass);

					$product_details[] = '<div class="padding-top-2 padding-bottom-2 clearBoth"' . $rowclass . '><div class="font-weight-bold float-left text-align-right padding-right-5 urlabel" style="width: ' . $labels_width . ';">Back Art Right Ln2:</div><div class="amz_red overflow-hidden">' . $brclipart2 . '</div><div class="height-50 text-align-center"><img class="max-width-86prc max-height-100prc" src="' . CLIPARTS_URL . DIRECTORY_SEPARATOR . $brclipart2_path . '" /></div></div>';
					$product_details_l[] = 'Back Art Right Ln2:';
					$product_details_v[] = $brclipart2;
				}
			}

			if (!empty($product->data['clipart_c']['back'][1]['right'])) {
				/*
				$brclipart2_path = getModule($tpt_vars, "BandClipart")->getClipartURL($tpt_vars, $product->data['clipart']['back'][1]['right']);
				$brclipart2_e = array_filter(explode('/', $brclipart2_path));
				if (!empty($brclipart2_e)) {
					$brclipart2_e = array_filter(explode('.', $brclipart2_e[count($brclipart2_e) - 1]));
					array_pop($brclipart2_e);
					$brclipart2 = htmlentities(preg_replace('#[\W_]+#', ' ', implode('.', $brclipart2_e)));
					$rowclass = ($rowclass == $oddclass ? $evenclass : $oddclass);

					$product_details[] = '<div class="padding-top-2 padding-bottom-2 clearBoth"' . $rowclass . '><div class="font-weight-bold float-left text-align-right padding-right-5" style="width: ' . $labels_width . ';">Back Art Right Ln2:</div><div class="amz_red overflow-hidden">' . $brclipart2 . '</div><div class="height-50 text-align-center"><img class="max-width-100prc max-height-100prc" src="' . $tpt_resourceurl . '/clipart/' . $brclipart2_path . '" /></div></div>';
					$product_details_l[] = 'Back Art Right Ln2:';
					$product_details_v[] = $brclipart2;
				}
				*/

				$brclipart2_c = $product->data['clipart_c']['back'][1]['right'];
				$product_details[] = '<div class="padding-top-2 padding-bottom-2 clearBoth"' . $rowclass . '><div class="font-weight-bold float-left text-align-right padding-right-5 urlabel" style="width: ' . $labels_width . ';">Back Custom Art Right Ln2:</div><div class="amz_red overflow-hidden">' . $brclipart2_c . '</div><div class="height-50 text-align-center"><img class="max-width-85prc max-height-100prc" src="' . $cliparts_module->getConvertCustomArtImageUrl($tpt_vars, $brclipart2_c, '80', '80', 'png') .'" /></div></div>';
				$product_details_l[] = 'Back Custom Art Right Ln2:';
				$product_details_v[] = $brclipart2_c;
			}


			if (!empty($product->data['custom_clipart'])) {
				// multiple cliparts feature...
				if (is_array($product->data['custom_clipart'])) {

					$product_details[] = '
					<div class="padding-top-2 padding-bottom-2 clearBoth"' . $rowclass . '>
						<div class="font-weight-bold float-left text-align-right padding-right-5 urlabel" style=width: ' . $labels_width . ';">Custom Art:</div>
						<div class="clear"></div>';
					$cc_bh_add = '';
					foreach ($product->data['custom_clipart'] as $ccl) {

						if ((stristr($ccl[1], '.pdf')) || (stristr($ccl[1], '.PDF')))
						{
							$cc_bh_add = '<a href="' . BASE_URL_SECURE . $ccl[1] . '" target="_blank" > <img class="max-width-100prc max-height-100prc" src="' . TPT_IMAGES_URL .'/icons/pdf-icon.png' . '" /></a>';
						}
						elseif ((stristr($ccl[1], '.EPS')) || (stristr($ccl[1], '.eps')))
						{
							$cc_bh_add = '<a href="' . BASE_URL_SECURE . $ccl[1] . '" target="_blank" > <img class="max-width-100prc max-height-100prc" src="' . TPT_IMAGES_URL .'/icons/eps-icon.png' . '" /></a>';
						}
						else
						{
							$cc_bh_add = '<img class="max-width-100prc max-height-100prc" src="' . BASE_URL_SECURE . $ccl[1] . '" />';
						}


						$product_details[] = '
							<div class="amz_red overflow-hidden">' . basename($ccl[1]) . ' : ' . $ccl[0] . '</div>
							<div class="text-align-center">
								'. $cc_bh_add.'
							</div>';
					}

					$product_details[] = '</div>';
					unset($cc_bh_add);

				} else { // old single case

					$cc_bh_add = '';
					if ((stristr($product->data['custom_clipart'], '.pdf')) || (stristr($product->data['custom_clipart'], '.PDF')))
					{
						$cc_bh_add = '<a href="' . CUSTOM_CLIPART_URL . '/' .$product->data['custom_clipart'] . '" target="_blank" > <img class="max-width-100prc max-height-100prc" src="' . TPT_IMAGES_URL .'/icons/pdf-icon.png' . '" /></a>';
					}
					elseif ((stristr($product->data['custom_clipart'], '.EPS')) || (stristr($product->data['custom_clipart'], '.eps')))
					{
						$cc_bh_add = '<a href="' . CUSTOM_CLIPART_URL . '/' . $product->data['custom_clipart'] . '" target="_blank" > <img class="max-width-100prc max-height-100prc" src="' . TPT_IMAGES_URL .'/icons/eps-icon.png' . '" /></a>';
					}
					else
					{
						$cc_bh_add = '<img class="max-width-100prc max-height-100prc" src="' . CUSTOM_CLIPART_URL . '/' . $product->data['custom_clipart'] . '" />';
					}

					$product_details[] = '
					<div class="padding-top-2 padding-bottom-2 clearBoth"' . $rowclass . '>
						<div class="font-weight-bold float-left text-align-right padding-right-5 urlabel" style="width: ' . $labels_width . ';">Custom Art:</div>
						<div class="amz_red overflow-hidden">' . $product->data['custom_clipart'] . '</div>
						<div class="text-align-center">
							'.$cc_bh_add.'
						</div>
					</div>';
					/*				$product_details[] = '
										<div class="padding-top-2 padding-bottom-2 clearBoth"' . $rowclass . '>
											<div class="font-weight-bold float-left text-align-right padding-right-5" style="width: ' . $labels_width . ';">Custom Art:</div>
											<div class="amz_red overflow-hidden">' . $product->data['custom_clipart'] . '</div>
											<div class="height-50 text-align-center">
												<img class="max-width-100prc max-height-100prc" src="' . CUSTOM_CLIPART_URL . '/' . $product->data['custom_clipart'] . '" />
											</div>
										</div>';
					*/
					unset($cc_bh_add);
				}
			}
		}

		if (!empty($addons)) {
			$rowclass = ($rowclass == $oddclass ? $evenclass : $oddclass);

			$product_details[] = '<div class="padding-top-2 padding-bottom-2 clearBoth"' . $rowclass . '><div class="font-weight-bold padding-right-5 urlabel"><span class="amz_brown font-weight-bold">Add-ons:</span></div></div>';
			$product_details_l[] = '<span class="amz_brown font-weight-bold">Add-ons:</span>';
			$product_details_v[] = '';

			foreach ($addons as $addon) {
				$rowclass = ($rowclass == $oddclass ? $evenclass : $oddclass);

				$product_details[] = '<div class="padding-top-2 padding-bottom-2 clearBoth"' . $rowclass . '><div class="font-weight-bold padding-right-5 urlabel"><span class="amz_red font-weight-normal">' . $addon . '</span></div></div>';
				$product_details_l[] = $addon;
				$product_details_v[] = '';
			}
		}

		/*
		if(isDev('devcart') && !empty($_GET['devcart'])) {
			$reorder_id = $product->data['reorder'];
		$rowclass = ($rowclass==$oddclass?$evenclass:$oddclass);

		$product_details[] = '<div class="padding-top-2 padding-bottom-2"'.$rowclass.'><div class="font-weight-bold float-left text-align-right padding-right-5 urlabel width-80">Reorder ID:</div><div class="amz_red overflow-hidden">'.$reorder_id.'</div></div>';
		$product_details_l[] = 'Reorder ID:';
		$product_details_v[] = $reorder_id;
		}
		*/


		if (isDev('devcart') && !empty($_GET['devcart'])) {
			$productdata = '<pre>' . var_export($product->data, true) . '</pre>';
			$rowclass = ($rowclass == $oddclass ? $evenclass : $oddclass);

			$product_details[] = '<div class="padding-top-2 padding-bottom-2"' . $rowclass . '><div class="font-weight-bold float-left text-align-right padding-right-5 urlabel width-80">$p->data:</div><div class="amz_red overflow-hidden">' . $productdata . '</div></div>';
			$product_details_l[] = '$p->data:';
			$product_details_v[] = $productdata;
			/*
			}

			if(isDev('devcart') && !empty($_GET['devcart'])) {
			*/
			$pdataarray = '<pre>' . var_export($pdata, true) . '</pre>';
			//$pdataarray = var_export($pdata, true);
			$rowclass = ($rowclass == $oddclass ? $evenclass : $oddclass);

			$product_details[] = '<div class="padding-top-2 padding-bottom-2"' . $rowclass . '><div class="font-weight-bold float-left text-align-right padding-right-5 urlabel width-80">$pdata:</div><div class="amz_red overflow-hidden">' . $pdataarray . '</div></div>';
			$product_details_l[] = '$pdata:';
			$product_details_v[] = $pdataarray;

			if (!empty($_GET['colorcart'])) {
				if (!empty($product->data['band_color'])) {
					$cprops = '<pre>' . var_export($colors_module->getColorProps($tpt_vars, $product->data['band_color']), true) . '</pre>';
					//$pdataarray = var_export($pdata, true);
					$rowclass = ($rowclass == $oddclass ? $evenclass : $oddclass);

					$product_details[] = '<div class="padding-top-2 padding-bottom-2"' . $rowclass . '><div class="font-weight-bold float-left text-align-right padding-right-5 urlabel width-80">cprops[color]:</div><div class="amz_red overflow-hidden">' . $cprops . '</div></div>';
					$product_details_l[] = 'cprops[band_color]:';
					$product_details_v[] = $cprops;
				}


				if (!empty($product->data['message_color'])) {
					$cprops = '<pre>' . var_export($colors_module->getColorProps($tpt_vars, $product->data['message_color']), true) . '</pre>';
					//$pdataarray = var_export($pdata, true);
					$rowclass = ($rowclass == $oddclass ? $evenclass : $oddclass);

					$product_details[] = '<div class="padding-top-2 padding-bottom-2"' . $rowclass . '><div class="font-weight-bold float-left text-align-right padding-right-5 urlabel width-80">cprops[mcolor]:</div><div class="amz_red overflow-hidden">' . $cprops . '</div></div>';
					$product_details_l[] = 'cprops[message_color]:';
					$product_details_v[] = $cprops;
				}
			}

			if(!empty($vars['appvars']['quotecart'])) {
				if(!empty($product->data['quote_id'])) {
					$quote_id = $product->data['quote_id'];

					$qry = <<< EOT
SELECT * FROM `temp_custom_orders` WHERE `id`=$quote_id
EOT;
					$vars['db']['handler']->query($qry);
					$temp_custom_orders = $vars['db']['handler']->fetch_assoc();
					$temp_custom_orders = '<pre>' . var_export($temp_custom_orders, true) . '</pre>';
					$rowclass = ($rowclass == $oddclass ? $evenclass : $oddclass);

					$product_details[] = '<div class="padding-top-2 padding-bottom-2"' . $rowclass . '><div class="font-weight-bold float-left text-align-right padding-right-5 urlabel width-80">temp_custom_orders(id:'.$quote_id.'):</div><div class="amz_red overflow-hidden">' . $temp_custom_orders . '</div></div>';
					$product_details_l[] = 'temp_custom_orders(id:'.$quote_id.'):';
					$product_details_v[] = $temp_custom_orders;
				}
				if(!empty($product->data['product_id'])) {
					$product_id = $product->data['product_id'];

					$qry = <<< EOT
SELECT * FROM `temp_custom_order_products` WHERE `id`=$product_id
EOT;
					$vars['db']['handler']->query($qry);
					$temp_custom_order_products = $vars['db']['handler']->fetch_assoc();
					$temp_custom_order_products = '<pre>' . var_export($temp_custom_order_products, true) . '</pre>';
					$rowclass = ($rowclass == $oddclass ? $evenclass : $oddclass);

					$product_details[] = '<div class="padding-top-2 padding-bottom-2"' . $rowclass . '><div class="font-weight-bold float-left text-align-right padding-right-5 urlabel width-80">temp_custom_order_products(id:'.$product_id.'):</div><div class="amz_red overflow-hidden">' . $temp_custom_order_products . '</div></div>';
					$product_details_l[] = 'temp_custom_order_products(id:'.$product_id.'):';
					$product_details_v[] = $temp_custom_order_products;
				}
			}
		}


		$rowclass = ($rowclass == $oddclass ? $evenclass : $oddclass);

		$product_details[] = '<div class="padding-top-2 padding-bottom-2 clearBoth"' . $rowclass . '><div class="font-weight-bold padding-right-5 urlabel"><span class="amz_brown font-weight-bold">Your Comments:</span></div></div>';
		$product_details_l[] = '<span class="amz_brown font-weight-bold">User Comments:</span>';
		$product_details_v[] = '';

		$rowclass = ($rowclass == $oddclass ? $evenclass : $oddclass);

		$comments = $product->data['user_comments'];

		$savecommenturl = $tpt_vars['config']['ajaxurl'] . '/cartupdateproduct_comments';
		$saveaction = tpt_ajax::getCall('cart.update_comments');

		$updatecommentsform = <<< EOT
<form action="$savecommenturl" method="POST">
<textarea name="comments">$comments</textarea>
<br />
<input type="hidden" name="productindex" value="$index" />
<input type="hidden" name="task" value="cart.update_comments" />
<input type="button" value="Save" onclick="$saveaction;addClass(this.parentNode.parentNode.parentNode.parentNode, 'height-18');" />
<input type="button" value="Cancel" onclick="addClass(this.parentNode.parentNode.parentNode.parentNode, 'height-18');" />
</form>
EOT;


		$comments_label = 'Add Your Design Ideas/Comments';
		if (!empty($comments)) {
			$comments_label = 'View/Edit Comments';
		}

		$ccontent = <<< EOT
<div class="overflow-hidden height-18 padding-top-2 padding-bottom-2 clearBoth"$rowclass>
    <div class="height-20 font-weight-bold padding-right-5 urlabel">
        <a onclick="removeClass(this.parentNode.parentNode, new RegExp(/height-[0-9]+/));" href="javascript:void(0);" class="amz_red font-weight-normal">$comments_label</a>
    </div>
    <div>
        <div>
        </div>
        <div>
            $updatecommentsform
        </div>
    </div>
</div>
EOT;
		if (!empty($ordercart)) {
			if (!empty($comments)) {
				$ccontent = <<< EOT
<div>$comments</div>
EOT;
			} else {
				$ccontent = <<< EOT
<div class="font-style-italic">(none)</div>
EOT;
			}

		}

		$product_details[] = $ccontent;


		/*
		//tpt_dump($product->data['added_by'], true);
		//tpt_logger::dump($tpt_vars, $product->data['added_by'], debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), '$product->data[\'added_by\']', __FILE__.' '.__LINE__);
		//$pdae_qry = $product->getDesignUrlQuery3($tpt_vars);
		$pdae_qry = $product->getDesignUrlQuery3($tpt_vars);;
		//$pdae_url = BASE_URL.$sbuilders[DEFAULT_BUILDER_ID]['standard_url'].'?'.$pdae_qry;
		$pdae_url = '';
		//$pe_url = $pdae_url.'&product='.$index;
		$pe_url = '';
		$builder_id = !empty($product->data['added_by']) ? $product->data['added_by'] : '';
		if (!empty($builder_id) && !empty($sbuilders[$builder_id])) {
			//var_dump($dae_qry);die();
			$builder_id = $product->data['added_by'];
		} else {
			//var_dump($dae_qry);die();
			$builder_id = $pdata['default_builder'];
			if (!empty($product->data['rush_order'])) {
				$builder_id = RUSHORDER_BUILDER_ID;
			}
		}
		$pdae_url = BASE_URL . $sbuilders[$builder_id]['standard_url'] . '?' . $pdae_qry;
		$pe_url = $pdae_url . '&product=' . $index;
		$rowclass = ($rowclass == $oddclass ? $evenclass : $oddclass);

		$product_details[] = <<< EOT
        <div class="padding-top-2 padding-bottom-2 clearBoth"$rowclass>
        &nbsp;
        </div>
EOT;


		$rowclass = ($rowclass == $oddclass ? $evenclass : $oddclass);

		$product_details[] = <<< EOT
        <div class="padding-top-2 padding-bottom-2 clearBoth"$rowclass>
            <div class="font-weight-bold padding-right-5">

                <a class="amz_red font-weight-normal" href="$pdae_url">Duplicate & Edit</a>
            </div>
        </div>
EOT;

		//&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;
		//<a class="amz_red font-weight-normal" href="$pe_url">Edit This Design</a>

		$product_details_l[] = '<span class="amz_brown font-weight-bold"></span>';
		$product_details_v[] = '';
		*/

                $sizeHeaderHtml = '<div style="color: #909090; font-family: TODAYSHOP-BOLD,arial; width:'.$w3.'" class="font-size-12 height-35 line-height-35 size-header-display">Size</div>';
                $quantityHeaderHtml = '<div style="color: #909090; font-family: TODAYSHOP-BOLD,arial; width:'.$w4.'" class="font-size-12 height-35 line-height-35 size-header-display">Quantity</div>';
                $subtotalHeaderHtml = '<div style="color: #909090; font-family: TODAYSHOP-BOLD,arial; width:'.$w5.'" class="font-size-12 height-35 line-height-35 size-header-display">Subtotal</div>';

		

			$smatch = preg_replace('#[0-9]#', '', $product->data['sku']);

			$sizesAvail = array();

			foreach (amz_cart::$customStockProductsData as $csp) {
				//	var_dump($csp['type'],$product->data['type'],$smatch,preg_replace('#[0-9]#','',$csp['sku']));
				if ($csp['type'] == $product->data['type'] && $smatch == preg_replace('#[0-9]#', '', $csp['sku'])) {
					$sizesAvail[$csp['size']] = $csp['sku'];
				}
			}


			$sizeSelect = '<select name="product_size" class="width-90prc padding-4 border-radius-12" style="background-color: white;  border: 1px solid #CCC;  outline: 0;">';

			foreach ($sizesAvail as $k => $v) {
				$sizeSelect .= '<option value="' . $v . '" ' . ($k == $product->data['size'] ? 'selected="selected"' : '') . '>'
					. getModule($tpt_vars, "BandSize")->moduleData['id'][$k]['label']
					. '</option>';
			}

			$sizeSelect .= '</select>';


			$updateurl = $tpt_vars['config']['ajaxurl'] . '/cartupdateproduct';

			$bandSizeForm = <<< EOT
$sizeHeaderHtml                                
<form action="$updateurl" method="POST">
$sizeSelect
<input type="hidden" name="productindex" value="$index" />
<input type="hidden" name="task" value="cart.update_size_stockproduct" />
<div class="height-20"></div>
<input type="submit" value="" class="width-74 height-20 plain-input-field hoverCB background-position-CT" style="background-image: url(/images/buttons/update-btn.png);" />
</form>
EOT;

			if (!empty($ordercart)) {
				$bandSizeForm = getModule($tpt_vars, "BandSize")->moduleData['id'][$product->data['size']]['label'];
			}


		if (isDev('devcart') && !empty($_GET['devcart'])) {
			$bandSizeForm .= '<div>(' . $product->data['size'] . ')</div>';
		}

		//var_dump($product->data['band_color']);die();

		$bandColor = $product->data['color'];


		if (isDev('devcart') && !empty($_GET['devcart'])) {
			$bandColor .= '<div>(' . $product->data['color'] . ')</div>';
		}

		$updbtnurl = TPT_IMAGES_URL . '/buttons/update-btn.png';
		$updateurl = $tpt_vars['config']['ajaxurl'] . '/cartupdateproduct';
		$updateform = <<< EOT
$quantityHeaderHtml                        
<form action="$updateurl" method="POST">
$qty
<input type="hidden" name="productindex" value="$index" />
<input type="hidden" name="task" value="cart.update" />
<div class="height-20"></div>
<input type="submit" value="" class="width-74 height-20 plain-input-field hoverCB background-position-CT" style="background-image: url($updbtnurl);" />
</form>
EOT;

		$delbtnurl = TPT_IMAGES_URL . '/buttons/delete-btn.png';
		$deleteurl = $tpt_vars['config']['ajaxurl'] . '/cartdeleteproduct';
		$deleteform = <<< EOT

<form action="$deleteurl" method="POST">
<input type="hidden" name="productindex" value="$index" />
<input type="hidden" name="task" value="cart.delete" />
<div class="height-20"></div>
<input type="submit" value="" class="width-74 height-20 plain-input-field hoverCB background-position-CT" style="background-image: url($delbtnurl);" />
</form>
EOT;

		if (!empty($shortcart) || !empty($ordercart)) {
			$prqty = $product->qty;
			$updateform = <<< EOT
<span class="amz_red">$prqty</span>
EOT;
			$deleteform = '';
		}

		$product_details = implode("\n", $product_details);
		$product_html .= <<< EOT
<div class="width-100prc">
    <div class="clearFix">
EOT;
		if (empty($shortcart)) {
			$product_html .= <<< EOT
        <div class="float-left">
            <div>
                $image
            </div>
        </div>
EOT;
		}
		$product_html .= <<< EOT
        <div class="text-align-left font-size-12">
            $product_details
        </div>
    </div>
</div>
EOT;

$subtotal = $subtotalHeaderHtml . $subtotal;
		$cells = array(
			array('width' => $w1, 'classes' => 'float-left', 'content' => $product_html),
			array('width' => $w2, 'classes' => 'amz_red float-left text-align-center color-display', 'content' => '<div class="display-inline-block text-align-left padding-left-5 padding-right-5">' . $bandColor . '</div>'),
			array('width' => $w3, 'classes' => 'amz_red float-left text-align-center', 'content' => $bandSizeForm),
			array('width' => $w4, 'classes' => 'amz_red float-left text-align-center border-left-right', 'content' => $updateform . $deleteform),
			array('width' => $w5, 'classes' => 'amz_red float-left text-align-center', 'content' => $subtotal),
		);

//		$delimiterurl = TPT_IMAGES_URL . DIRECTORY_SEPARATOR . 'cart-column-delimiter-760.png';
		$delimiterurl = TPT_IMAGES_URL . DIRECTORY_SEPARATOR . 'cart-elem-spr.png';
		//if(!empty($shortcart)) {
		//    $delimiterurl = TPT_IMAGES_URL.DIRECTORY_SEPARATOR.'cart-column-delimiter-560.png';
		//}
		$cells_html = '<div class="padding-top-5 padding-botttom-5 clearFix position-relative" style="width: 100%;">'; //vj edits
		$cells_html .= $productDesign;
//		$cells_html .= '<div class="position-absolute top-0 bottom-0 left-0 right-0 background-repeat-repeat-y z-index-1" style="background-image: url(' . $delimiterurl . ');"></div>';
		$cells_html .= '<div class="position-absolute top-0 bottom-0 left-0 right-0 z-index-1" style="background-position: 0px -210px; background-image: url(' . $delimiterurl . ');"></div>';
		$cells_html .= '<div class="position-relative clearFix z-index-2">';
		foreach ($cells as $params) {
			$cells_html .= '<div style="width:' . $params['width'] . '; min-height: 1px; " class="font-size-12 ' . $params['classes'] . '">' . $params['content'] . '</div>';
		}
		$cells_html .= '</div>';
		$cells_html .= '</div>';

		return $cells_html;
	}

}

class amz_stockproduct extends amz_product {
	public $id;

	function __construct(&$tpt_vars, $id, $qty) {
		if(!is_numeric($id) || empty($id) || empty($qty) || !isset(amz_cart::$stockProductsData[$id]))
				return false;

		$this->id = $id;
		$this->qty = $qty;
		$this->data = amz_cart::$stockProductsData[$id];
		//var_dump($this->qty);die();
		$values = array('sbase_price'=>amz_cart::$stockProductsData[$id]['price'], 'mbase_price'=>amz_cart::$stockProductsData[$id]['price']*$qty);
		$html = $values;
		//var_dump($html);
		array_walk($html, 'format_price_array');

		$this->price = array('values'=>$values, 'html'=>$html);
	}
	
	function getCachedProductImageUrl(&$vars, $x=0, $y=0) {
		return TPT_STOCKPRODUCTIMAGES_URL.'/'.$this->data['image_filename'];
	}
	
	function getCachedProductThumbUrl(&$vars, $x=0, $y=0) {
		return TPT_STOCKPRODUCTIMAGES_URL.'/'.$this->data['thumb_filename'];
	}
	
	
	function getCachedImageName(&$vars, $x=0, $y=0) {

		/*
		$flatimg = array(
						'pgType'=>$this->data['band_type'],
						'pgStyle'=>$this->data['band_style'],
						'invert_dual'=>intval($this->data['invert_dual'], 10),
						'cut_away'=>intval($this->data['cut_away'], 10),
						'pgFont' => $this->data['band_font'],
						'pgBandColor' => $this->data['band_color'],
						'pgMessageColor' => $this->data['message_color'],
						'pgFrontMessage' => 'f1:'.(!empty($this->data['messages']['front'][0])?$this->data['messages']['front'][0]:''),
						'pgClipartFrontLeft' => (!empty($this->data['clipart']['front'][0]['left'])?$this->data['clipart']['front'][0]['left']:0),
						'pgClipartFrontRight' => (!empty($this->data['clipart']['front'][0]['right'])?$this->data['clipart']['front'][0]['right']:0),
						'pgFrontMessage2' => 'f2:'.(!empty($this->data['messages']['front'][1])?$this->data['messages']['front'][1]:''),
						'pgClipartFrontLeft2' => (!empty($this->data['clipart']['front'][1]['left'])?$this->data['clipart']['front'][1]['left']:0),
						'pgClipartFrontRight2' => (!empty($this->data['clipart']['front'][1]['right'])?$this->data['clipart']['front'][1]['right']:0),
						'pgBackMessage' => 'b1:'.(!empty($this->data['messages']['back'][0])?$this->data['messages']['back'][0]:''),
						'pgClipartBackLeft' => (!empty($this->data['clipart']['back'][0]['left'])?$this->data['clipart']['back'][0]['left']:0),
						'pgClipartBackRight' => (!empty($this->data['clipart']['back'][0]['right'])?$this->data['clipart']['back'][0]['right']:0),
						'pgBackMessage2' => 'b2:'.(!empty($this->data['messages']['back'][1])?$this->data['messages']['back'][1]:''),
						'pgClipartBackLeft2' => (!empty($this->data['clipart']['back'][1]['left'])?$this->data['clipart']['back'][1]['left']:0),
						'pgClipartBackRight2' => (!empty($this->data['clipart']['back'][1]['right'])?$this->data['clipart']['back'][1]['right']:0),
						'pg_x' => $x,
						'pg_y' => $y
						);
		$query = array(
						'type'=>'flat',
						'pgType'=>$this->data['band_type'],
						'pgStyle'=>$this->data['band_style'],
						'invert_dual'=>intval($this->data['invert_dual'], 10),
						'cut_away'=>intval($this->data['cut_away'], 10),
						'pgFont' => $this->data['band_font'],
						'pgBandColor' => $this->data['band_color'],
						'pgMessageColor' => $this->data['message_color'],
						'pgFrontMessage' => (!empty($this->data['messages']['front'][0])?$this->data['messages']['front'][0]:''),
						'pgClipartFrontLeft' => (!empty($this->data['clipart']['front'][0]['left'])?$this->data['clipart']['front'][0]['left']:0),
						'pgClipartFrontRight' => (!empty($this->data['clipart']['front'][0]['right'])?$this->data['clipart']['front'][0]['right']:0),
						'pgFrontMessage2' => (!empty($this->data['messages']['front'][1])?$this->data['messages']['front'][1]:''),
						'pgClipartFrontLeft2' => (!empty($this->data['clipart']['front'][1]['left'])?$this->data['clipart']['front'][1]['left']:0),
						'pgClipartFrontRight2' => (!empty($this->data['clipart']['front'][1]['right'])?$this->data['clipart']['front'][1]['right']:0),
						'pgBackMessage' => (!empty($this->data['messages']['back'][0])?$this->data['messages']['back'][0]:''),
						'pgClipartBackLeft' => (!empty($this->data['clipart']['back'][0]['left'])?$this->data['clipart']['back'][0]['left']:0),
						'pgClipartBackRight' => (!empty($this->data['clipart']['back'][0]['right'])?$this->data['clipart']['back'][0]['right']:0),
						'pgBackMessage2' => (!empty($this->data['messages']['back'][1])?$this->data['messages']['back'][1]:''),
						'pgClipartBackLeft2' => (!empty($this->data['clipart']['back'][1]['left'])?$this->data['clipart']['back'][1]['left']:0),
						'pgClipartBackRight2' => (!empty($this->data['clipart']['back'][1]['right'])?$this->data['clipart']['back'][1]['right']:0),
						'pg_x' => $x,
						'pg_y' => $y
						);

		$image = '';


		$filename = sha1(implode($flatimg)).'.png';
		$cfile = $filename;

		return $cfile;
		*/
		return '';
	}
	
	
	function getDesignUrlQuery(&$vars) {
		/*
		$addons = array();
		if(!empty($this->pricingObject->options['key_chain'])) {
				$addons['key_chain'] = 'Make Into Keychain';
		}
		$pgType = $this->data['band_type'];
		if(!empty($addons['key_chain'])) {
				$pType = 7;
		}
		$pgStyle = $this->data['band_style'];
		$invert_dual = intval($this->data['invert_dual'], 10);
		$cut_away = intval($this->data['cut_away'], 10);
		$pgFont = $this->data['band_font'];
		//$pgFrontRows = (!empty($pgFrontRows)?$pgFrontRows:1);
		//$pgBackRows = (!empty($pgBackRows)?$pgBackRows:1);
		//$pgTextCont = (!empty($pgTextCont)?intval($pgTextCont, 10):0);
		$pgBandColor = $this->data['band_color'];
		$pgMessageColor = $this->data['message_color'];
		$pgFrontMessage = (!empty($this->data['messages']['front'][0])?$this->data['messages']['front'][0]:'');
		$pgFrontMessage2 = (!empty($this->data['messages']['front'][1])?$this->data['messages']['front'][1]:'');
		$pgBackMessage = (!empty($this->data['messages']['back'][0])?$this->data['messages']['back'][0]:'');
		$pgBackMessage2 = (!empty($this->data['messages']['back'][1])?$this->data['messages']['back'][1]:'');

		$pgClipartFrontLeft = (!empty($this->data['clipart']['front'][0]['left'])?$this->data['clipart']['front'][0]['left']:0);
		$pgClipartFrontRight = (!empty($this->data['clipart']['front'][0]['right'])?$this->data['clipart']['front'][0]['right']:0);
		$pgClipartFrontLeft2 = (!empty($this->data['clipart']['front'][1]['left'])?$this->data['clipart']['front'][1]['left']:0);
		$pgClipartFrontRight2 = (!empty($this->data['clipart']['front'][1]['right'])?$this->data['clipart']['front'][1]['right']:0);
		$pgClipartBackLeft = (!empty($this->data['clipart']['back'][0]['left'])?$this->data['clipart']['back'][0]['left']:0);
		$pgClipartBackRight = (!empty($this->data['clipart']['back'][0]['right'])?$this->data['clipart']['back'][0]['right']:0);
		$pgClipartBackLeft2 = (!empty($this->data['clipart']['back'][1]['left'])?$this->data['clipart']['back'][1]['left']:0);
		$pgClipartBackRight2 = (!empty($this->data['clipart']['back'][1]['right'])?$this->data['clipart']['back'][1]['right']:0);
		//$pgCutAway = $cut_away;

		//var_dump($this->data);

		$query = array(
								'pgType'=>$pgType,
								'pgStyle'=>$pgStyle,
								'invert_dual'=>$invert_dual,
								'cut_away'=>$cut_away,
								'pgFont'=>$pgFont,
								'pgBandColor'=>$pgBandColor,
								'pgMessageColor'=>$pgMessageColor,
								'pgFrontMessage'=>$pgFrontMessage,
								'pgClipartFrontLeft'=>$pgClipartFrontLeft,
								'pgClipartFrontRight'=>$pgClipartFrontRight,
								'pgFrontMessage2'=>$pgFrontMessage2,
								'pgClipartFrontLeft2'=>$pgClipartFrontLeft2,
								'pgClipartFrontRight2'=>$pgClipartFrontRight2,
								'pgBackMessage'=>$pgBackMessage,
								'pgClipartBackLeft'=>$pgClipartBackLeft,
								'pgClipartBackRight'=>$pgClipartBackRight,
								'pgBackMessage2'=>$pgBackMessage2,
								'pgClipartBackLeft2'=>$pgClipartBackLeft2,
								'pgClipartBackRight2'=>$pgClipartBackRight2
								);

		//var_dump($query);die();
		//var_dump($pgType);die();
		$query = http_build_query($query);
		//var_dump($query);die();

		return $query;
		*/
		return '';

	}

	function getDesignUrlQuery2(&$vars) {
		/*
		$pgType = $this->data['band_type'];
		$pgStyle = $this->data['band_style'];
		$invert_dual = intval($this->data['invert_dual'], 10);
		$cut_away = intval($this->data['cut_away'], 10);
		$pgFont = $this->data['band_font'];
		//$pgFrontRows = (!empty($pgFrontRows)?$pgFrontRows:1);
		//$pgBackRows = (!empty($pgBackRows)?$pgBackRows:1);
		//$pgTextCont = (!empty($pgTextCont)?intval($pgTextCont, 10):0);
		$pgBandColor = $this->data['band_color'];
		$pgMessageColor = $this->data['message_color'];
		$pgFrontMessage = (!empty($this->data['messages']['front'][0])?$this->data['messages']['front'][0]:'');
		$pgFrontMessage2 = (!empty($this->data['messages']['front'][1])?$this->data['messages']['front'][1]:'');
		$pgBackMessage = (!empty($this->data['messages']['back'][0])?$this->data['messages']['back'][0]:'');
		$pgBackMessage2 = (!empty($this->data['messages']['back'][1])?$this->data['messages']['back'][1]:'');

		$pgClipartFrontLeft = (!empty($this->data['clipart']['front'][0]['left'])?$this->data['clipart']['front'][0]['left']:0);
		$pgClipartFrontRight = (!empty($this->data['clipart']['front'][0]['right'])?$this->data['clipart']['front'][0]['right']:0);
		$pgClipartFrontLeft2 = (!empty($this->data['clipart']['front'][1]['left'])?$this->data['clipart']['front'][1]['left']:0);
		$pgClipartFrontRight2 = (!empty($this->data['clipart']['front'][1]['right'])?$this->data['clipart']['front'][1]['right']:0);
		$pgClipartBackLeft = (!empty($this->data['clipart']['back'][0]['left'])?$this->data['clipart']['back'][0]['left']:0);
		$pgClipartBackRight = (!empty($this->data['clipart']['back'][0]['right'])?$this->data['clipart']['back'][0]['right']:0);
		$pgClipartBackLeft2 = (!empty($this->data['clipart']['back'][1]['left'])?$this->data['clipart']['back'][1]['left']:0);
		$pgClipartBackRight2 = (!empty($this->data['clipart']['back'][1]['right'])?$this->data['clipart']['back'][1]['right']:0);
		//$pgCutAway = $cut_away;

		//var_dump($this->data);
		$query = array(
								'band_type'=>$pgType,
								'band_style'=>$pgStyle,
								'invert_dual'=>$invert_dual,
								'cut_away'=>$cut_away,
								'band_font'=>$pgFont,
								'band_color'=>$pgBandColor,
								'message_color'=>$pgMessageColor,
								'message_front'=>$pgFrontMessage,
								'clipart_front_left'=>$pgClipartFrontLeft,
								'clipart_front_right'=>$pgClipartFrontRight,
								'message_front2'=>$pgFrontMessage2,
								'clipart_front_left2'=>$pgClipartFrontLeft2,
								'clipart_front_right2'=>$pgClipartFrontRight2,
								'message_back'=>$pgBackMessage,
								'clipart_back_left'=>$pgClipartBackLeft,
								'clipart_back_right'=>$pgClipartBackRight,
								'message_back2'=>$pgBackMessage2,
								'clipart_back_left2'=>$pgClipartBackLeft2,
								'clipart_back_right2'=>$pgClipartBackRight2
								);
		//var_dump($query);die();
		//var_dump($pgType);die();
		$query = http_build_query($query);
		//var_dump($query);die();

		return $query;
		*/
		return '';

	}
	
	function getDesignUrlQuery3(&$vars) {
		return '';
	}
	
	
	function getPricingDataArray(&$vars) {
		$pricingdata = array(
				'Shipping'=>0,
				'Discount'=>0,
				'Tax'=>0,
				'Total_Price'=>$this->price['values']['mbase_price'],
				//'cost',
				//'Total_Cost'
										);
		
		return $pricingdata;
	}
	
	function getDesignNotesString(&$vars) {
		return '';
	}
	
	function getSku(&$vars) {
		return '';
	}

	function getCartView(&$vars, $index=0) {
		$html = '';


		return $html;
	}
}

class amz_bundle extends amz_product {
	public $id;
	public $pids;

	function __construct(&$tpt_vars, $id, $pids, $qty) {
		if(!is_numeric($id) || empty($id) || empty($qty) || !isset(amz_cart::$bundlesData[$id]))
				return false;

		$this->id = $id;
		$this->pids = $pids;
		$this->qty = $qty;
		$this->data = amz_cart::$bundlesData[$id];
		//var_dump($this->qty);die();
		$values = array('sbase_price'=>amz_cart::$bundlesData[$id]['price'], 'mbase_price'=>amz_cart::$bundlesData[$id]['price']*$qty);
		$html = $values;
		//var_dump($html);
		array_walk($html, 'format_price_array');

		$this->price = array('values'=>$values, 'html'=>$html);
	}
	
	
	function getCachedProductImageUrl(&$vars, $x=0, $y=0) {
		return TPT_STOCKPRODUCTIMAGES_URL.'/'.$this->data['image_filename'];
	}
	
	function getCachedProductThumbUrl(&$vars, $x=0, $y=0) {
		return TPT_STOCKPRODUCTIMAGES_URL.'/'.$this->data['thumb_filename'];
	}
	
	
	function getCachedImageName(&$vars, $x=0, $y=0) {

		/*
		$flatimg = array(
						'pgType'=>$this->data['band_type'],
						'pgStyle'=>$this->data['band_style'],
						'invert_dual'=>intval($this->data['invert_dual'], 10),
						'cut_away'=>intval($this->data['cut_away'], 10),
						'pgFont' => $this->data['band_font'],
						'pgBandColor' => $this->data['band_color'],
						'pgMessageColor' => $this->data['message_color'],
						'pgFrontMessage' => 'f1:'.(!empty($this->data['messages']['front'][0])?$this->data['messages']['front'][0]:''),
						'pgClipartFrontLeft' => (!empty($this->data['clipart']['front'][0]['left'])?$this->data['clipart']['front'][0]['left']:0),
						'pgClipartFrontRight' => (!empty($this->data['clipart']['front'][0]['right'])?$this->data['clipart']['front'][0]['right']:0),
						'pgFrontMessage2' => 'f2:'.(!empty($this->data['messages']['front'][1])?$this->data['messages']['front'][1]:''),
						'pgClipartFrontLeft2' => (!empty($this->data['clipart']['front'][1]['left'])?$this->data['clipart']['front'][1]['left']:0),
						'pgClipartFrontRight2' => (!empty($this->data['clipart']['front'][1]['right'])?$this->data['clipart']['front'][1]['right']:0),
						'pgBackMessage' => 'b1:'.(!empty($this->data['messages']['back'][0])?$this->data['messages']['back'][0]:''),
						'pgClipartBackLeft' => (!empty($this->data['clipart']['back'][0]['left'])?$this->data['clipart']['back'][0]['left']:0),
						'pgClipartBackRight' => (!empty($this->data['clipart']['back'][0]['right'])?$this->data['clipart']['back'][0]['right']:0),
						'pgBackMessage2' => 'b2:'.(!empty($this->data['messages']['back'][1])?$this->data['messages']['back'][1]:''),
						'pgClipartBackLeft2' => (!empty($this->data['clipart']['back'][1]['left'])?$this->data['clipart']['back'][1]['left']:0),
						'pgClipartBackRight2' => (!empty($this->data['clipart']['back'][1]['right'])?$this->data['clipart']['back'][1]['right']:0),
						'pg_x' => $x,
						'pg_y' => $y
						);
		$query = array(
						'type'=>'flat',
						'pgType'=>$this->data['band_type'],
						'pgStyle'=>$this->data['band_style'],
						'invert_dual'=>intval($this->data['invert_dual'], 10),
						'cut_away'=>intval($this->data['cut_away'], 10),
						'pgFont' => $this->data['band_font'],
						'pgBandColor' => $this->data['band_color'],
						'pgMessageColor' => $this->data['message_color'],
						'pgFrontMessage' => (!empty($this->data['messages']['front'][0])?$this->data['messages']['front'][0]:''),
						'pgClipartFrontLeft' => (!empty($this->data['clipart']['front'][0]['left'])?$this->data['clipart']['front'][0]['left']:0),
						'pgClipartFrontRight' => (!empty($this->data['clipart']['front'][0]['right'])?$this->data['clipart']['front'][0]['right']:0),
						'pgFrontMessage2' => (!empty($this->data['messages']['front'][1])?$this->data['messages']['front'][1]:''),
						'pgClipartFrontLeft2' => (!empty($this->data['clipart']['front'][1]['left'])?$this->data['clipart']['front'][1]['left']:0),
						'pgClipartFrontRight2' => (!empty($this->data['clipart']['front'][1]['right'])?$this->data['clipart']['front'][1]['right']:0),
						'pgBackMessage' => (!empty($this->data['messages']['back'][0])?$this->data['messages']['back'][0]:''),
						'pgClipartBackLeft' => (!empty($this->data['clipart']['back'][0]['left'])?$this->data['clipart']['back'][0]['left']:0),
						'pgClipartBackRight' => (!empty($this->data['clipart']['back'][0]['right'])?$this->data['clipart']['back'][0]['right']:0),
						'pgBackMessage2' => (!empty($this->data['messages']['back'][1])?$this->data['messages']['back'][1]:''),
						'pgClipartBackLeft2' => (!empty($this->data['clipart']['back'][1]['left'])?$this->data['clipart']['back'][1]['left']:0),
						'pgClipartBackRight2' => (!empty($this->data['clipart']['back'][1]['right'])?$this->data['clipart']['back'][1]['right']:0),
						'pg_x' => $x,
						'pg_y' => $y
						);

		$image = '';


		$filename = sha1(implode($flatimg)).'.png';
		$cfile = $filename;

		return $cfile;
		*/
		return '';
	}
	
	function getSku(&$vars) {
		return '';
	}
	
	
	
	function getDesignUrlQuery(&$vars) {
		/*
		$addons = array();
		if(!empty($this->pricingObject->options['key_chain'])) {
				$addons['key_chain'] = 'Make Into Keychain';
		}
		$pgType = $this->data['band_type'];
		if(!empty($addons['key_chain'])) {
				$pType = 7;
		}
		$pgStyle = $this->data['band_style'];
		$invert_dual = intval($this->data['invert_dual'], 10);
		$cut_away = intval($this->data['cut_away'], 10);
		$pgFont = $this->data['band_font'];
		//$pgFrontRows = (!empty($pgFrontRows)?$pgFrontRows:1);
		//$pgBackRows = (!empty($pgBackRows)?$pgBackRows:1);
		//$pgTextCont = (!empty($pgTextCont)?intval($pgTextCont, 10):0);
		$pgBandColor = $this->data['band_color'];
		$pgMessageColor = $this->data['message_color'];
		$pgFrontMessage = (!empty($this->data['messages']['front'][0])?$this->data['messages']['front'][0]:'');
		$pgFrontMessage2 = (!empty($this->data['messages']['front'][1])?$this->data['messages']['front'][1]:'');
		$pgBackMessage = (!empty($this->data['messages']['back'][0])?$this->data['messages']['back'][0]:'');
		$pgBackMessage2 = (!empty($this->data['messages']['back'][1])?$this->data['messages']['back'][1]:'');

		$pgClipartFrontLeft = (!empty($this->data['clipart']['front'][0]['left'])?$this->data['clipart']['front'][0]['left']:0);
		$pgClipartFrontRight = (!empty($this->data['clipart']['front'][0]['right'])?$this->data['clipart']['front'][0]['right']:0);
		$pgClipartFrontLeft2 = (!empty($this->data['clipart']['front'][1]['left'])?$this->data['clipart']['front'][1]['left']:0);
		$pgClipartFrontRight2 = (!empty($this->data['clipart']['front'][1]['right'])?$this->data['clipart']['front'][1]['right']:0);
		$pgClipartBackLeft = (!empty($this->data['clipart']['back'][0]['left'])?$this->data['clipart']['back'][0]['left']:0);
		$pgClipartBackRight = (!empty($this->data['clipart']['back'][0]['right'])?$this->data['clipart']['back'][0]['right']:0);
		$pgClipartBackLeft2 = (!empty($this->data['clipart']['back'][1]['left'])?$this->data['clipart']['back'][1]['left']:0);
		$pgClipartBackRight2 = (!empty($this->data['clipart']['back'][1]['right'])?$this->data['clipart']['back'][1]['right']:0);
		//$pgCutAway = $cut_away;

		//var_dump($this->data);

		$query = array(
								'pgType'=>$pgType,
								'pgStyle'=>$pgStyle,
								'invert_dual'=>$invert_dual,
								'cut_away'=>$cut_away,
								'pgFont'=>$pgFont,
								'pgBandColor'=>$pgBandColor,
								'pgMessageColor'=>$pgMessageColor,
								'pgFrontMessage'=>$pgFrontMessage,
								'pgClipartFrontLeft'=>$pgClipartFrontLeft,
								'pgClipartFrontRight'=>$pgClipartFrontRight,
								'pgFrontMessage2'=>$pgFrontMessage2,
								'pgClipartFrontLeft2'=>$pgClipartFrontLeft2,
								'pgClipartFrontRight2'=>$pgClipartFrontRight2,
								'pgBackMessage'=>$pgBackMessage,
								'pgClipartBackLeft'=>$pgClipartBackLeft,
								'pgClipartBackRight'=>$pgClipartBackRight,
								'pgBackMessage2'=>$pgBackMessage2,
								'pgClipartBackLeft2'=>$pgClipartBackLeft2,
								'pgClipartBackRight2'=>$pgClipartBackRight2
								);

		//var_dump($query);die();
		//var_dump($pgType);die();
		$query = http_build_query($query);
		//var_dump($query);die();

		return $query;
		*/
		return '';

	}

	function getDesignUrlQuery2(&$vars) {
		/*
		$pgType = $this->data['band_type'];
		$pgStyle = $this->data['band_style'];
		$invert_dual = intval($this->data['invert_dual'], 10);
		$cut_away = intval($this->data['cut_away'], 10);
		$pgFont = $this->data['band_font'];
		//$pgFrontRows = (!empty($pgFrontRows)?$pgFrontRows:1);
		//$pgBackRows = (!empty($pgBackRows)?$pgBackRows:1);
		//$pgTextCont = (!empty($pgTextCont)?intval($pgTextCont, 10):0);
		$pgBandColor = $this->data['band_color'];
		$pgMessageColor = $this->data['message_color'];
		$pgFrontMessage = (!empty($this->data['messages']['front'][0])?$this->data['messages']['front'][0]:'');
		$pgFrontMessage2 = (!empty($this->data['messages']['front'][1])?$this->data['messages']['front'][1]:'');
		$pgBackMessage = (!empty($this->data['messages']['back'][0])?$this->data['messages']['back'][0]:'');
		$pgBackMessage2 = (!empty($this->data['messages']['back'][1])?$this->data['messages']['back'][1]:'');

		$pgClipartFrontLeft = (!empty($this->data['clipart']['front'][0]['left'])?$this->data['clipart']['front'][0]['left']:0);
		$pgClipartFrontRight = (!empty($this->data['clipart']['front'][0]['right'])?$this->data['clipart']['front'][0]['right']:0);
		$pgClipartFrontLeft2 = (!empty($this->data['clipart']['front'][1]['left'])?$this->data['clipart']['front'][1]['left']:0);
		$pgClipartFrontRight2 = (!empty($this->data['clipart']['front'][1]['right'])?$this->data['clipart']['front'][1]['right']:0);
		$pgClipartBackLeft = (!empty($this->data['clipart']['back'][0]['left'])?$this->data['clipart']['back'][0]['left']:0);
		$pgClipartBackRight = (!empty($this->data['clipart']['back'][0]['right'])?$this->data['clipart']['back'][0]['right']:0);
		$pgClipartBackLeft2 = (!empty($this->data['clipart']['back'][1]['left'])?$this->data['clipart']['back'][1]['left']:0);
		$pgClipartBackRight2 = (!empty($this->data['clipart']['back'][1]['right'])?$this->data['clipart']['back'][1]['right']:0);
		//$pgCutAway = $cut_away;

		//var_dump($this->data);
		$query = array(
								'band_type'=>$pgType,
								'band_style'=>$pgStyle,
								'invert_dual'=>$invert_dual,
								'cut_away'=>$cut_away,
								'band_font'=>$pgFont,
								'band_color'=>$pgBandColor,
								'message_color'=>$pgMessageColor,
								'message_front'=>$pgFrontMessage,
								'clipart_front_left'=>$pgClipartFrontLeft,
								'clipart_front_right'=>$pgClipartFrontRight,
								'message_front2'=>$pgFrontMessage2,
								'clipart_front_left2'=>$pgClipartFrontLeft2,
								'clipart_front_right2'=>$pgClipartFrontRight2,
								'message_back'=>$pgBackMessage,
								'clipart_back_left'=>$pgClipartBackLeft,
								'clipart_back_right'=>$pgClipartBackRight,
								'message_back2'=>$pgBackMessage2,
								'clipart_back_left2'=>$pgClipartBackLeft2,
								'clipart_back_right2'=>$pgClipartBackRight2
								);
		//var_dump($query);die();
		//var_dump($pgType);die();
		$query = http_build_query($query);
		//var_dump($query);die();

		return $query;
		*/
		return '';

	}
	
	
	function getDesignUrlQuery3(&$vars) {
		return '';
	}
	
	
	function getPricingDataArray(&$vars) {
		$pricingdata = array(
				'Shipping'=>0,
				'Discount'=>0,
				'Tax'=>0,
				'Total_Price'=>$this->price['values']['mbase_price'],
				//'cost',
				//'Total_Cost'
										);
		
		return $pricingdata;
	}
	
	function getDesignNotesString(&$vars) {
		return '';
	}

	function getCartView(&$vars, $index=0) {
		$html = '';


		return $html;
	}
}

class tpt_cart_controller {
	function __construct() {}



	function beforeContent(&$vars) {


		if(!empty($_GET['delcached'])) {
				$filename = preg_split('#[\R\\/]+#', mysql_real_escape_string(!empty($_GET['delcached'])?$_GET['delcached']:''));
				$filename = array_pop($filename);

				$cfile = TPT_IMAGES_DIR.DIRECTORY_SEPARATOR.'preview'.DIRECTORY_SEPARATOR.'cached'.DIRECTORY_SEPARATOR.'flat'.DIRECTORY_SEPARATOR.$filename;

				if(is_file($cfile)) {
					//header('Content-type: image/png');
					//die($cfile);
					unlink($cfile);
				} else {
					$vars['environment']['ajax_result']['messages'][] = array('Error occured.', 'error');
				}

				//var_dump($vars['url']);die();

				$qry = $vars['url']['qry'];
				//var_dump($qry);die();
				unset($qry['delcached']);

				$return_url = $vars['url']['handler']->wrap($vars, $vars['url']['upath']).'?'.http_build_query($qry);
				tpt_request::redirect($vars, $return_url);
		}

	}

	function afterContent(&$vars) {
		$store_products = array();
		$store_products['stock'] = array();
		$store_products['bundle'] = array();
		$store_products['custom'] = array();
		foreach(amz_cart::$products as $product) {
				if(is_a($product, 'amz_stockproduct')) {
					$store_products['stock'][$product->id] = array('productclass'=>get_class($product), 'qty'=>$product->qty);
				} else if(is_a($product, 'amz_bundle')) {
					$store_products['bundle'][$product->id] = array('productclass'=>get_class($product), 'pids'=>$product->pids, 'qty'=>$product->qty);
				} else {
					$store_products['custom'][] = $product;
				}
		}
		//amz_cart::getTotals($vars);
		$_SESSION['customer_area'] = tpt_template::getFrontendHeaderCustomerAreaDesktop($vars);
		$_SESSION['customer_area_mobile1'] = tpt_template::getFrontendHeaderCustomerAreaMobile($vars);
		$_SESSION['customer_area_mobile2'] = tpt_template::getFrontendHeaderCustomerAreaMobile2($vars);
		$_SESSION['templay']['basket'] = serialize($store_products);

	}
}

