<?php

ini_set('max_execution_time', 300);



function eh($errno, $errstr, $errfile, $errline){
	echo '<pre>';
	var_dump($errno, $errstr, $errfile, $errline);
	echo '</pre>';
}
//set_error_handler('eh');


function t_sel($table, $fields='*', $where='', $indexBy=null, $splitKeys=null, $debug=false) {
	global $tpt_vars;
	if (empty($fields)) $fields='*';
	return $tpt_vars['db']['handler']->getData($tpt_vars, $table, $fields, $where, $indexBy=null, $splitKeys=null, $debug=false);
}


if (empty($_GET['id'])&&!empty($_GET['order_id'])) $_GET['id']=(int)$_GET['order_id'];
if (empty($_GET['order_id'])&&!empty($_GET['id'])) $_GET['order_id']=(int)$_GET['id'];

$ord_data = array();
$ord_select = t_sel('temp_custom_orders','','id='.(int)$_GET['id'].' or quote_id='.(int)$_GET['id'] .' order by `id` asc');
foreach ($ord_select as $ord_result) {
	$ord_data[count($ord_data)] = $ord_result;
	
	!isset($Tax_tot_1) ? $Tax_tot_1 = 0 : '';
	!isset($Total_Price_tot_1) ? $Total_Price_tot_1 = 0 : '';
	!isset($Discount_tot_1) ? $Discount_tot_1 = 0 : '';
	!isset($Shipping_tot_1) ? $Shipping_tot_1 = 0 : '';
	
	$Tax_tot_1 += $ord_result['Tax'];
	$Total_Price_tot_1 += $ord_result['Total_Price'];
	$Discount_tot_1 += $ord_result['Discount'];
	$Shipping_tot_1 += $ord_result['Shipping'];
}

$size_data = array();
$size_sel = t_sel('tpt_module_bandsize','','');
foreach ($size_sel as $size_result) $size_data[$size_result['id']]=$size_result;

$type_data = array();
$type_sel = t_sel('tpt_module_bandtype','','');
foreach ($type_sel as $type_result) $type_data[$type_result['id']]=$type_result;

// For Stock Products
$get_prodcuts_price = mysql_query("select * from temp_ot_total where order_id=".$ord_data[0]['order_id']);

if (mysql_num_rows($get_prodcuts_price)) {
	$final_total = mysql_result($get_prodcuts_price,0,'total');
	$shipping_rate = mysql_result($get_prodcuts_price,0,'shipping');
}
//Total For Stock Products
if($Total_Price_tot_1 == '0'){
	$Total_Price_tot_1 = $final_total;	
}
$total_price_1 = ((($Tax_tot_1 + $Total_Price_tot_1)- $Discount_tot_1) + $Shipping_tot_1);

$ord_ids = array();
$prod_data=array();
for ($i=0;$i<count($ord_data);$i++) {
	$ord_data[$i]['PROD']=array();
	$ord_ids[count($ord_ids)] = $ord_data[$i]['id'];
	$prod_select = t_sel('temp_custom_order_products','','order_id='.$ord_data[$i]['id'].' order by `id` asc');
	foreach ($prod_select as $prod) {
		$prod_data[count($prod_data)] = $prod;
		$ord_data[$i]['PROD'][count($ord_data[$i]['PROD'])]=$prod;
		if (!empty($type_data[$ord_data[$i]["product_id"]])){
			$ord_data[$i]['TYPE']=$type_data[$ord_data[$i]["product_id"]];
		}
		
		//$extra_select = t_sel('temp_custom_order_extras','','product_id='.$prod['id']);
		//if (!empty($extra_select[0])) $ord_data[$i]['PROD'][count($ord_data[$i]['PROD'])-1]['EXTRAS'] = $extra_select[0];
		$ord_data[$i]['PROD'][count($ord_data[$i]['PROD'])-1]['EXTRAS'] = $prod;
	}
}

$tpt_orders_products = array();

//if ($_GET['dump']=='a') var_dump($ord_data[0]["order_id"]);

$tpt_orders_sel = t_sel('tpt_orders','','old_order_id='.$ord_data[0]["order_id"].' order by `id` asc');

//if ($_GET['dump']=='a') var_dump($tpt_orders_sel);

foreach ($tpt_orders_sel as $tpt_orders_result) {
	$tpt_orders_products_sel = t_sel('tpt_orders_products','','order_id='.$tpt_orders_result['id'].' order by `id` asc');
	foreach ($tpt_orders_products_sel as $tpt_orders_products_result) {
//		$tpt_orders_products[count($tpt_orders_products)] = $tpt_orders_products_result;
		$tpt_orders_products[$tpt_orders_products_result['old_product_id']] = $tpt_orders_products_result;
	}
}
$TOP = $tpt_orders_products;

define('dfls',dirname(__FILE__).DIRECTORY_SEPARATOR);
define ('K_PATH_FONTS', dfls.'FONT/');
define ('fonts_work_dir', K_PATH_FONTS);
define ('fonts_source_dir', '/home/amazingw/public_html/live/fonts/new-fonts/');
define ('img_work_dir', dfls.'work_images/');
define ('KCHN_F_B_SPACE', 22);
define ('KCHN_L_SPACE', 20.2);

//require_once('config/tcpdf_config.php');
//require_once(dfls.'config'.DIRECTORY_SEPARATOR.'cfg.php');
require_once(dfls.'tcpdf.php');
require_once(dfls.'svg_helper.php');


if ($_GET['dump']==1) {
	echo '<pre>';
	var_dump($size_data);
	var_dump('=====================================');
	var_dump($tpt_orders_products);
	var_dump('=====================================');
//	var_dump( getModule($tpt_vars, "BandData"));
//	var_dump('=====================================');
//	var_dump($tpt_vars['modules']['hanlder']->modules['BandType']->moduleData['id'][$tpt_orders_products[0]['type']]);
//	var_dump('=====================================');
	var_dump($ord_data);	
	echo '</pre>';
	die();
}

if ($_GET['dump']==3) {

	$_GET['pg_x']=738;
	$_GET['pg_y']=60;
	$_GET['color']='6:7';
	$_GET['type']='segmented';

	$out = tpt_PreviewGenerator::generatePreview($tpt_vars, $_GET);
	//die();
	header('Content-type: image/png');
	echo $out;

}



$pdf = new TCPDF('P', 'mm', array(235.9,279.4), true, 'UTF-8', false);

$pdf->SetTitle('proof template');
$pdf->SetHeaderData();
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetAutoPageBreak(false);



class PR_G {

	static function color_label_convert($sterm) {
		$query = <<< EOT
(SELECT 3 AS `table_id`,`id`, CONCAT("3:", `id`) AS `universal_id`,`label`, 3 AS `color_type`,`color_id`, "" AS `message_color_id`, 0 AS `glow`, 0 AS `glitter`, 0 AS `uv`,`enabled`, "" AS `available_types_ids2`, 0 as `stock`, "tpt_color_overseas" AS `tbl` FROM `tpt_color_overseas` WHERE `label`="$sterm")
UNION
(SELECT 4 AS `table_id`,`id`, CONCAT("4:", `id`) AS `universal_id`,`label`, 4 AS `color_type`,`color_id`, "" AS `message_color_id`, 0 AS `glow`, 0 AS `glitter`, 0 AS `uv`,`enabled`, "" AS `available_types_ids2`, 0 as `stock`, "tpt_color_overseas" AS `tbl` FROM `tpt_color_overseas` WHERE `label`="$sterm")
UNION
(SELECT 5 AS `table_id`,`id`, CONCAT("5:", `id`) AS `universal_id`,`label`, 5 AS `color_type`,`color_id`, "" AS `message_color_id`, 0 AS `glow`, 0 AS `glitter`, 0 AS `uv`,`enabled`, "" AS `available_types_ids2`, 0 as `stock`, "tpt_color_overseas" AS `tbl` FROM `tpt_color_overseas` WHERE `label`="$sterm")
UNION
(SELECT 6 AS `table_id`,`id`, CONCAT("6:", `id`) AS `universal_id`,`label`,`color_type`,`color_id`,`message_color_id`,`glow`,`glitter`,`uv`,`enabled`,`available_types_ids2`, 1 as `stock`, "tpt_color_special" AS `tbl` FROM `tpt_color_special` WHERE `label`="$sterm")
UNION
(SELECT 10 AS `table_id`,`id`, CONCAT("10:", `id`) AS `universal_id`,`label`,`color_type`,`color_id`,`message_color_id`,`glow`,`glitter`,`uv`,`enabled`,`available_types_ids2`, 1 as `stock`, "tpt_color_duallayer" AS `tbl` FROM `tpt_color_duallayer` WHERE `label`="$sterm")
EOT;
		$cq = mysql_query($query);
		$cr = array();
		while ($cf = mysql_fetch_assoc($cq)) {
			$cr[count($cr)] = $cf;
		}
		return $cr;
	}
	
	static function add_extras($prod,$y1,$y2,$TOPP) {
		
		global $pdf;
		global $tpt_orders_products;
		global $tpt_vars;
		
		$data_module = getModule($tpt_vars, "BandData");

		$tp = $TOPP['type'];
		$st = $TOPP['style'];

		if ($_GET['dump']=='s') {
			echo '<pre>';
			var_dump($data_module->typeStyle[$tp][$st]['writable_strip_position']);
			echo '</pre>';
			die();
		}

		
		$yp = 6;

		$pdf->SetLineStyle(array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(241, 171, 39)));
		$pdf->SetFont('helveticaltstdcompressed', '', 10, '', true);
		$pdf->setFontSpacing(0.2);
		
		if (!empty($prod['EXTRAS']['glitter'])) {
			$w = $pdf->GetStringWidth('+ ADD GLITTER','helveticaltstdcompressed','',9);
			$pdf->RoundedRect(149.5, self::v('y')+10.97+$y1+$y2+$yp, $w+13, 4.6, 1, '1111', 'DF', '', array(153,255,255));
			$pdf->ImageSVG(dfls.'img/tick_yellow.svg',150,self::v('y')+11.5+$y1+$y2+$yp,0,0);
			$t = '<p style="line-height:16pt;text-align:left;color:#000000;">+ ADD GLITTER</p>';
			$pdf->writeHTMLCell(0,10,155,self::v('y')+11+$y1+$y2+$yp,$t);
			$yp+= 6;
		}
		
		if (!empty($prod['EXTRAS']['uv'])) {
			$w = $pdf->GetStringWidth('+ ADD UV','helveticaltstdcompressed','',9);
			$pdf->RoundedRect(149.5, self::v('y')+10.97+$y1+$y2+$yp, $w+13, 4.6, 1, '1111', 'DF', '', array(255,255,0));
			$pdf->ImageSVG(dfls.'img/tick_yellow.svg',150,self::v('y')+11.5+$y1+$y2+$yp,0,0);
			$t = '<p style="line-height:16pt;text-align:left;color:#000000;">+ ADD UV</p>';
			$pdf->writeHTMLCell(0,10,155,self::v('y')+11+$y1+$y2+$yp,$t);
			$yp+= 6;
		}
		
		if (!empty($prod['EXTRAS']['glow'])) {
			$w = $pdf->GetStringWidth('+ ADD GLOW','helveticaltstdcompressed','',9);
			$pdf->RoundedRect(149.5, self::v('y')+10.97+$y1+$y2+$yp, $w+13, 4.6, 1, '1111', 'DF', '', array(153,255,153));
			$pdf->ImageSVG(dfls.'img/tick_yellow.svg',150,self::v('y')+11.5+$y1+$y2+$yp,0,0);
			$t = '<p style="line-height:16pt;text-align:left;color:#000000;">+ ADD GLOW</p>';
			$pdf->writeHTMLCell(0,10,155,self::v('y')+11+$y1+$y2+$yp,$t);
			$yp+= 6;
		}
		
		if (!empty($data_module->typeStyle[$tp][$st]['writable_strip_position'])) {
			$w = $pdf->GetStringWidth('+Add Writable Stripe','helveticaltstdcompressed','',9);
			$pdf->RoundedRect(149.5, self::v('y')+10.97+$y1+$y2+$yp, $w+13, 4.6, 1, '1111', 'DF', '', array(227,29,36));
			$pdf->ImageSVG(dfls.'img/tick_yellow.svg',150,self::v('y')+11.5+$y1+$y2+$yp,0,0);
			$t = '<p style="line-height:16pt;text-align:left;color:#ffffff;">+Add Writable Stripe</p>';
			$pdf->writeHTMLCell(0,10,155,self::v('y')+11+$y1+$y2+$yp,$t);
			$yp+= 6;
		}
		
		if (!empty($prod['EXTRAS']['pack_individual'])) {
			$w = $pdf->GetStringWidth('Individual Packaging','helveticaltstdcompressed','',9);
			$pdf->RoundedRect(149.5, self::v('y')+10.97+$y1+$y2+$yp, $w+13, 4.6, 1, '1111', 'DF', '', array(227,29,36));
			$pdf->ImageSVG(dfls.'img/tick_yellow.svg',150,self::v('y')+11.5+$y1+$y2+$yp,0,0);
			$t = '<p style="line-height:16pt;text-align:left;color:#ffffff;">Individual Packaging</p>';
			$pdf->writeHTMLCell(0,10,155,self::v('y')+11+$y1+$y2+$yp,$t);
			$yp+= 6;
		}
		
	}
	
	static function write_type_style() {

		global $pdf;

		$t = "<p style=\"line-height:16pt; text-align:left; font-size:12pt;\">";
		$i = 0;
		
		foreach ($TS=self::v('type/style') as $t_s=>$v) {
			$t_s_d = json_decode($t_s,true);
			$t_ = $t_s_d['t'];
			$s_ = $t_s_d['s'];
			
			if ($i) $t.= "<br />";
			$t.= $t_;
			$t.= "<br />";
			$t.= $s_;
			
			$i++;
		}
		
		$t.= "</p>";
		
		$pdf->SetTextColor(114,134,132);
		$pdf->setFontSpacing(0);
		$pdf->SetFont('helveticaltstd', '', 14, '', true);
		$pdf->writeHTMLCell(200,10,181.8,41.5,$t);
		
		self::v('type/style',array());
		
	}
	
	static function add_page() {
		
		global $pdf;
		global $ord_data;		
		global $total_price_1;		
		
		if (self::v('p')!=0) self::write_type_style();
		self::v('p','','++');
		
		$pdf->AddPage();
		require(dfls.'template-customer.php');
		
		//$fontname = $pdf->addTTFfont('fonts/HelveticaLTStd-Comp.ttf', '', '', 32);
		//$pdf->SetFont($fontname, '', 14, '', true);
		
		$pdf->SetFont('helveticaltstdcompressed', '', 14, '', true);
		$pdf->SetTextColor(114,134,132);
		$pdf->setFontSpacing(0.2);
				
		$t = html_entity_decode($ord_data[0]["customer_shipping_address"])."\n\nPh: ".$ord_data[0]["customer_phone"];
		
		$pdf->startTransaction();
		$pdf->setCellHeightRatio(1);
		$pdf->MultiCell(56,10,$t,0,'L',0,1,17,41.5);
		$y = $pdf->getY();
		if ($y<72) {
			$pdf->commitTransaction();
		} else {
			$pdf->SetFont('helveticaltstdcompressed', '', 13, '', true);
			$pdf->rollbackTransaction(true);
			$pdf->startTransaction();
			$pdf->setY($y);
			$pdf->setCellHeightRatio(0.86);
			$pdf->MultiCell(56,10,$t,0,'L',0,1,17,41.5);
			$y = $pdf->getY();
			
			if ($y<70) {
				$pdf->commitTransaction();
			} else {
			//	var_dump('ssasa');
				$pdf->rollbackTransaction(true);
				$pdf->SetFont('helveticaltstdcompressed', '', 12.8, '', true);
				$pdf->setCellHeightRatio(0.89);
				$pdf->MultiCell(56,10,$t,0,'L',0,1,17,41.5);
				$y = $pdf->getY();
			}

		}
		
		$pdf->setFontSpacing(0);
		$t = "<b style=\"line-height:12.4pt; text-align:left; font-size:16pt;\">";
		//$t.= '$ '.nl2br(htmlentities($ord_data[0]["Total_Price"]));
		$t.= '$ '.number_format($total_price_1,2);
		$t.= "</b>";
		$pdf->writeHTMLCell(0,10,77,42,$t);
		
		$t = "<b style=\"line-height:12.4pt; text-align:left; font-size:16pt;\">";
		$t.= 'WRISTBAND STYLE:';
		$t.= "</b>";
		$pdf->writeHTMLCell(0,10,142,42,$t);
		
		$t = "<b style=\"line-height:12.4pt; text-align:left; font-size:16pt;\">";
		$t.= 'ORDER#';
		$t.= "</b>";
		$pdf->writeHTMLCell(0,10,165,63.6,$t);
		
		
		$pdf->SetFont('helveticaltstd', '', 14, '', true);
		
		$pdf->setFontSpacing(0.2);
		$t = "<p style=\"line-height:12.4pt; text-align:left; font-size:14pt;\">";
		$t.= 'AMZG-'.$ord_data[0]["order_id"];
		$t.= "</p>";
		$pdf->writeHTMLCell(0,10,181.8,63.6,$t);
		
	}
	
	static function v($n,$v='*n0n',$o='') {
		static $VARZ; 
		
		if (!isset($VARZ)) {
			$VARZ=array();
			$VARZ['top_bound'] = 90;
			$VARZ['bot_bound'] = 242;
			$VARZ['y'] = $VARZ['top_bound'];
			$VARZ['p'] = 0;			
			$VARZ['p1'] = 0;
			$VARZ['b.i'] = 0;
			$VARZ['type/style'] = array();
		}
		
		if ($o=='++') {
			$VARZ[$n]++;
			return $VARZ[$n];
		}
		
		if ($v==='*n0n') {
			if (isset($VARZ[$n])) {
				return $VARZ[$n];
			} else {
				return NULL;
			}
		
		} elseif($o=='') {
			$VARZ[$n] = $v;
			return $VARZ[$n];
			
		} elseif($o=='+') {
			$VARZ[$n]+= $v;
			return $VARZ[$n];
		
		} elseif($o=='k') {
			if (!isset($VARZ[$n])) $VARZ[$n] = array();
			$VARZ[$n] = (array)$VARZ[$n];
			$VARZ[$n][$v] = 1;
			return $VARZ[$n];
		}
	}

	static function calculate_msg_w_h($TOPP,$msg,$result,$k,&$w,&$h) {
		
		global $pdf;
		
//		$pdf->startTransaction();
		
		$fontname = $pdf->addTTFfont(fonts_work_dir.$TOPP['font'], 'TrueTypeUnicode', '', 96);
		
		$pdf->SetFont($fontname, '', $result[$k]['fsi'], '', false);
		$pdf->setFontSpacing($result[$k]['fsp']);
		$pdf->setFontStretching($result[$k]['fst']);
		
		$pdf->SetAbsX(0);
		$pdf->SetY(10);
		
		$x1 = $pdf->GetAbsX();
//		$y1 = $pdf->GetY();
		
	//	$pdf->Text($x1,0,'test message',false,false,true,0,0);
	//	$pdf->Write(10,'test message');
		$pdf->writeHTML('<span>'.$msg.'</span>',false);
				
		$x2 = $pdf->GetAbsX();
//		$y2 = $pdf->GetY();

		$h = $pdf->getStringHeight(2000,$msg);
		$w2 = $pdf->getStringWidth($msg);
		$w1 = $x2-$x1;
	//	var_dump($msg); 
		$w = max($w1,$w2); 
	//	$w = $w1; 
		
//		$pdf->rollbackTransaction(true);
		
	}

	static function calculate_msg_dims($TOPP,$l,$w) {
		
		global $pdf;
		
		$stamp = crc32(json_encode(array($TOPP,$l,$w)));
		
		if (is_array(self::v('msg_dims:'.$stamp))) return self::v('msg_dims:'.$stamp);
		
		if (!is_file(fonts_work_dir.$TOPP['font'])) {
			copy(fonts_source_dir.$TOPP['font'],fonts_work_dir.$TOPP['font']);
		}


	//	if ($_GET['dump']=='a') {
	//		echo '<pre>';
	//		var_dump($TOPP);
	//		echo '</pre>';
	//	}
	
		if ($TOPP['type']==5) {
			//slapbands
			$hpad = 40;
		} else if ($TOPP['type']==6) {
			// adj. snap bands
			if ($TOPP["text_span"]==1) {
				$hpad = 60;
			} else if ($TOPP["text_span"]==0) {
				$hpad = 60;
			}
		} else if ($TOPP['type']==7) {
			//keychain
			if ($TOPP["text_span"]==1) {
				$hpad = 50;
			} else if ($TOPP["text_span"]==0) {
				$hpad = 60;
			}
		} else {
			$hpad = 20;
		}
		
		$height_limit2 = false;
		
		if ($TOPP["text_span"]==1) {
			if (!empty($TOPP['fmsg2'])) {
				$height_limit = ($w-1.5)/2;
			} else {
				$height_limit = $w-2.5;
			} 
			$width_limit = $l-$hpad;
		} elseif ($TOPP["text_span"]==0) {
			if (!empty($TOPP['fmsg2'])) {
				$height_limit = ($w-1.5)/2;
			} else {
				$height_limit = $w-1.5;
			} 
			if (!empty($TOPP['bmsg2'])) {
				$height_limit2 = ($w-1.5)/2;
			} else {
				$height_limit2 = $w-1.5;
			}
			$width_limit = $l/2-$hpad/2;
		}
		
		$result = array();
		
		$font_size = 80;
		$min_font_size = 80;
		$font_spacing = 0;
		$font_stretching = 100;
		$it=0;
		
		$msim1 = !empty($TOPP['fmsg']) && !empty($TOPP['bmsg']) && empty($TOPP['fmsg2']) && empty($TOPP['bmsg2']);
		$msim2 = empty($TOPP['fmsg']) && empty($TOPP['bmsg']) && empty($TOPP['fmsg2']) && empty($TOPP['bmsg2']);
		$msim = $msim1 || $msim2;
		
		$pdf->startTransaction();
		
		do {
			foreach (array_intersect_key($TOPP,array('fmsg'=>0,'fmsg2'=>0,'bmsg'=>0,'bmsg2'=>0)) as $k=>$msg) {
				if (empty($msg)) continue;
				
				isset($result[$k]) ? '' : $result[$k] = array();
				isset($result[$k]['fsp']) ? '' : $result[$k]['fsp'] = $font_spacing;
				isset($result[$k]['fsi']) ? '' : $result[$k]['fsi'] = $font_size;
				isset($result[$k]['fst']) ? '' : $result[$k]['fst'] = $font_stretching;
				

				self::calculate_msg_w_h($TOPP,$msg,$result,$k,$w,$h);
				
				$height_limit_ref = $height_limit;
				if (in_array($k,array('bmsg','bmsg2'))&&$height_limit2!==false) {
					$height_limit_ref = $height_limit2;
				}
				
				if ($h>$height_limit_ref) {
					$result[$k]['fsi']-=0.4;
				
			//	} else if(abs($w-$width_limit)<3) {
			//		$result[$k]['fsi'] = $min_font_size;
			//		continue;
				
				} else if($w<$width_limit) {
					$result[$k]['fsp']+=0.05;
					$result[$k]['fst']+=1;
				} else if($w>$width_limit) {
					$result[$k]['fsi']-=0.3;
					$result[$k]['fsp']-=0.1;
					$result[$k]['fst']-=2;
				}
				
				
				if ($msim) {
					$min_font_size = min($min_font_size,$result[$k]['fsi']);
					$result[$k]['fsi'] = $min_font_size;
				}
				
				self::calculate_msg_w_h($TOPP,$msg,$result,$k,$w,$h);
								
				$result[$k]['w']=$w;
				$result[$k]['h']=$h;
				$result[$k]['wl']=$width_limit;
				$result[$k]['hl']=$height_limit;
				
			}
			$it++;
			
			if ($it%20==0) {
				$pdf->rollbackTransaction(true);
				$pdf->startTransaction();
			}

		} while ($it<200);

		$pdf->rollbackTransaction(true);
		
		self::v('msg_dims:'.$stamp,$result);
		
		return $result;
		
	}

	static function draw_message_item($x,$y,$i,$rgb,$TOPP,$msg_dims,$bl_mm,$bw_mm,$lm,$mif,$ci) {
		global $pdf;
		global $tpt_vars;
		
	//	var_dump($TOPP);
		$im_y = $im_x = 0;
		$il = $bl_mm;
		$iw = $bw_mm;
		$m_fill_x = false;
						
		if ($TOPP['type']==7) {
			// keychains
			$m_fill_x = self::v('lm1+x')+KCHN_L_SPACE;
			if (in_array($i,array('bmsg','bmsg2'))) {
				$im_y = KCHN_F_B_SPACE-2;
				$m_fill_x = self::v('lm1+x')+self::v('bx-w');
			}
		} else if ($TOPP['type']==6) {
			// adj. snap bands
			$m_fill_x = self::v('lm1+x');
			$il = self::v('l.mm');
		}
		
		if ($m_fill_x===false) $m_fill_x = $lm+$im_x;

		if (isset($msg_dims[$i])) {
			$pdf->setFontSize($msg_dims[$i]['fsi']);
			$pdf->setFontSpacing($msg_dims[$i]['fsp']);
			$pdf->setFontStretching($msg_dims[$i]['fst']);
		}
		
		if ($rgb===false) {
			$pdf->StartTransform();
			$pdf->Text($x,$y,$TOPP[$i],false,true);
			$pdf->Image($mif,$m_fill_x, self::v('y')+6+$im_y,$il,$iw,'PNG','','',false);
			$pdf->StopTransform();
		} else {
			$pdf->SetTextColor($ci['red'], $ci['green'], $ci['blue']);
			$pdf->Text($x,$y,$TOPP[$i],false,false);
	//		var_dump($TOPP[$i]);
		}

	}

	static function draw_message($TOPP,$msg_dims,$bl_mm,$bw_mm,$lm,$mc_data) {
		
		if ($_GET['dump']=='aa') {
			echo '<pre>';
			var_dump('TOP::::',$TOPP); //die();
			echo '</pre>';
		}
		
		global $pdf;
		global $tpt_vars;
		
		if (!is_file(fonts_work_dir.$TOPP['font'])) {
			copy(fonts_source_dir.$TOPP['font'],fonts_work_dir.$TOPP['font']);
		}
		$fontname = $pdf->addTTFfont(fonts_work_dir.$TOPP['font'], 'TrueTypeUnicode', '', 96);
		$pdf->SetFont($fontname, '', 16, '', false);
				
		
	//	var_dump($TOPP["style"]); die();
		
		if (!empty($mc_data)&&$TOPP["style"]==2||$TOPP["style"]==5||$TOPP["style"]==7||$TOPP["style"]==4) { 
			// debossed with color fill || screen printed || D.L. laser deboss || colorised emboss
			
			$_G['pg_x']=round($bl_mm*3);
			$_G['pg_y']=round($bw_mm*3);
			$_G['color']=$TOPP['message_color'];
			
			$bc_data = self::v('bc_data');
			if (!empty($bc_data['colordata']['message_color_type'])) {
				$_G['type']=$bc_data['colordata']['message_color_type'];
			} else {
				$_G['type']='solid';
			}
			
			$if = img_work_dir.md5(json_encode($_G)).'.png';
			
			if (!is_file($if)) {
				$img_bin = tpt_PreviewGenerator::generatePreview($tpt_vars, $_G);
				file_put_contents($if,$img_bin);
			}
			$mif = $if;
			$mifb = imagecreatefrompng($if);
			
		} elseif ($TOPP["style"]=="1") { // debossed 2 ?
		
			$mif = img_work_dir.md5(base64_encode(self::v('bimg'))).'_m.png';
			if (!is_file($mif)) file_put_contents($mif,self::v('bimg'));
		
			$mifb = imagecreatefrompng($mif);
			imagefilter($mifb, IMG_FILTER_BRIGHTNESS, -80);
			imagepng($mifb,$mif);

			
			$if = $mif;
		} elseif ($TOPP["style"]=="2") { // debossed
		
			$mif = img_work_dir.md5(base64_encode(self::v('bimg'))).'_m.png';
			if (!is_file($mif)) file_put_contents($mif,self::v('bimg'));
		
			$mifb = imagecreatefrompng($mif);
			imagefilter($mifb, IMG_FILTER_BRIGHTNESS, -80);
			imagepng($mifb,$mif);

			
			$if = $mif;
		
		} elseif ($TOPP["style"]=="3") { // embossed
		
			$mif = img_work_dir.md5(base64_encode(self::v('bimg'))).'_m.png';
			if (!is_file($mif)) file_put_contents($mif,self::v('bimg'));

			$mifb = imagecreatefrompng($mif);
			imagefilter($mifb, IMG_FILTER_BRIGHTNESS, +80);
			imagepng($mifb,$mif);

			$if = $mif;
		}

		if (empty($mif)) return;
		$size=getimagesize($mif);
		
		$rgb = imagecolorat($mifb,0,0);
		$ci = imagecolorsforindex($mifb,$rgb);
		
		for ($i=0;$i<20;$i++) {
			if (imagecolorat($mifb,rand(1,$size[0]-1),rand(1,$size[1]-1))!=$rgb) {
				//single color message not
				$rgb=false;
				break;
			}
		}

		if ($rgb!==false) $rgbar = array($ci['red'], $ci['green'], $ci['blue']);
		
		$pdf->setTextShadow($params = array(
			'enabled'=>false,
			'depth_w'=>0,
			'depth_h'=>0,
			'color'=>false,
			'opacity'=>1,
			'blend_mode'=>'Normal'
		));

		$bm_y = $bm_x = $m_y = $m_x = 0;
		
	//	$m_y = -0.5;
		
		if ($TOPP['type']==7) { //keychains
			if ($TOPP["text_span"]==0) {
				$bm_y = KCHN_F_B_SPACE;
				$bm_x = self::v('l.mm')/-2-15;
				$m_y = -2.5;
				$m_x = self::v('l.mm')/4+4;
			} else if ($TOPP["text_span"]==1) {
				$m_y = -1.7;
				$m_x = 1.5;
			}
		} else if ($TOPP['type']==6) { // snap
			if ($TOPP["text_span"]==0) {
				$m_x = 24;
				$bm_x = -24;
			} else if ($TOPP["text_span"]==1) {
				$m_x = 11.8;
			}
		}

		if ($TOPP["text_span"]==1) {
						
			if (empty($TOPP['fmsg2'])) {
				
				if (!empty($TOPP['fmsg'])) self::draw_message_item(
					-1+$lm+($bl_mm-$msg_dims['fmsg']['w'])/2+$m_x,
					self::v('y')+6-0.25+($bw_mm-$msg_dims['fmsg']['h'])/2+$m_y,
					'fmsg',$rgb,$TOPP,$msg_dims,$bl_mm,$bw_mm,$lm,$mif,$ci
				);
				
			} else {
				
				if (!empty($TOPP['fmsg'])) self::draw_message_item(
					$lm+($bl_mm-$msg_dims['fmsg']['w'])/2+$m_x,
					self::v('y')+6+($bw_mm/2-$msg_dims['fmsg']['h'])/2+$m_y,
					'fmsg',$rgb,$TOPP,$msg_dims,$bl_mm,$bw_mm,$lm,$mif,$ci
				);
				
				if (!empty($TOPP['fmsg2'])) self::draw_message_item(
					$lm+($bl_mm-$msg_dims['fmsg2']['w'])/2+$m_x,
					self::v('y')+6+$bw_mm/2+($bw_mm/2-$msg_dims['fmsg2']['h'])/2+$m_y,
					'fmsg2',$rgb,$TOPP,$msg_dims,$bl_mm,$bw_mm,$lm,$mif,$ci
				);
				
			}
		
		} elseif ($TOPP["text_span"]==0) {
			
			if (empty($TOPP['fmsg2'])) {
				
				if (!empty($TOPP['fmsg'])) self::draw_message_item(
					$lm+($bl_mm/2-$msg_dims['fmsg']['w'])/2+$m_x,
					self::v('y')+6+($bw_mm-$msg_dims['fmsg']['h'])/2+$m_y,
					'fmsg',$rgb,$TOPP,$msg_dims,$bl_mm,$bw_mm,$lm,$mif,$ci
				);
				
			} else {
				
				if (!empty($TOPP['fmsg'])) self::draw_message_item(
					$lm+($bl_mm/2-$msg_dims['fmsg']['w'])/2+$m_x,
					self::v('y')+6+($bw_mm/2-$msg_dims['fmsg']['h'])/2+$m_y,
					'fmsg',$rgb,$TOPP,$msg_dims,$bl_mm,$bw_mm,$lm,$mif,$ci
				);
				
				if (!empty($TOPP['fmsg2'])) self::draw_message_item(
					$lm+($bl_mm/2-$msg_dims['fmsg2']['w'])/2+$m_x,
					-1+self::v('y')+6+$bw_mm/2+($bw_mm/2-$msg_dims['fmsg2']['h'])/2+$m_y,
					'fmsg2',$rgb,$TOPP,$msg_dims,$bl_mm,$bw_mm,$lm,$mif,$ci
				);
				
			}
			
			if (empty($TOPP['bmsg2'])) {
				
				if (!empty($TOPP['bmsg'])) self::draw_message_item(
					$lm+$bl_mm/2+($bl_mm/2-$msg_dims['bmsg']['w'])/2+$bm_x+$m_x,
					self::v('y')+6+($bw_mm-$msg_dims['bmsg']['h'])/2+$bm_y+$m_y,
					'bmsg',$rgb,$TOPP,$msg_dims,$bl_mm,$bw_mm,$lm,$mif,$ci
				);
				
			} else {

				if (!empty($TOPP['bmsg'])) self::draw_message_item(
					$lm+$bl_mm/2+($bl_mm/2-$msg_dims['bmsg']['w'])/2+$bm_x+$m_x,
					self::v('y')+6+($bw_mm/2-$msg_dims['bmsg']['h'])/2+$bm_y+$m_y,
					'bmsg',$rgb,$TOPP,$msg_dims,$bl_mm,$bw_mm,$lm,$mif,$ci
				);
				
				if (!empty($TOPP['bmsg2'])) self::draw_message_item(
					$lm+$bl_mm/2+($bl_mm/2-$msg_dims['bmsg2']['w'])/2+$bm_x+$m_x,
					-1+self::v('y')+6+$bw_mm/2+($bw_mm/2-$msg_dims['bmsg2']['h'])/2+$bm_y+$m_y,
					'bmsg2',$rgb,$TOPP,$msg_dims,$bl_mm,$bw_mm,$lm,$mif,$ci
				);

			}
		}
		
		unlink($mif);
		imagedestroy($mifb);
		
		$pdf->setFontStretching(100);
		$pdf->setFontSpacing(0);
//		$pdf->setTextRenderingMode(0,true,false);
		
	}

	static function band_text($prod,$TOPP) {
		global $pdf;

		$pdf->SetFont('badaboombbi', '', 24, '', true);
		$pdf->SetTextColor(242,171,38);
		$pdf->setFontSpacing(0.2);
		$t = '<p stroke="0.3" fill="true" strokecolor="#858787" style="line-height:16pt;text-align:center;">';
		$t.= strtoupper(htmlentities($prod["size"])).' SIZE';
		$t.= "</p>";
		$pdf->writeHTMLCell(200,10,16.4,self::v('y_t')+0.5,$t);
		
		self::v('y_t',5,'+');
		
		////////////////////////////////////////////////////
				
		//dims
		$pdf->SetFont('helveticaltstdcompressed', '', 10, '', true);
		$pdf->setFontSpacing(0);
		$t = '<p style="text-align:center;color:#b2b3b3;">'
			.'('.self::v('l.mm').' X '.self::v('w.mm').' X 2 mm) '
			.'('.self::v('l.in').' X '.self::v('w.in').' X 0.078 inch)</p>';
		$pdf->writeHTMLCell(200,10,17,self::v('y_t')+2.5,$t);
				
		if ($TOPP['type']==7) { // keychain
			
			$pdf->SetFont('badaboombbi', '', 24, '', true);
			$pdf->SetTextColor(242,171,38);
			$pdf->setFontSpacing(0.2);
			
			if ($TOPP["text_span"]==1) {
				$t = '<p stroke="0.3" fill="true" strokecolor="#858787" style="line-height:16pt;text-align:center;">';
				$t.= strtoupper('Continuous message style');
				$t.= "</p>";
				$pdf->writeHTMLCell(200,10,16.4,self::v('y_t')+8,$t);
			//	self::v('y',5,'+');
			} else if ($TOPP["text_span"]==0) {
				$t = '<p stroke="0.3" fill="true" strokecolor="#858787" style="line-height:16pt;text-align:center;">';
				$t.= strtoupper('Front Message');
				$t.= "</p>";
				$pdf->writeHTMLCell(200,10,16.4,self::v('y_t')+8,$t);
				
				$t = '<p stroke="0.3" fill="true" strokecolor="#858787" style="line-height:16pt;text-align:center;">';
				$t.= strtoupper('Back Message');
				$t.= "</p>";
				$pdf->writeHTMLCell(200,10,16.4,self::v('y_t')+8+KCHN_F_B_SPACE,$t);
			}
		} 

				
	}

	static function draw_band($bc_data,$TOPP,$prod,$x) {
		
		global $pdf;
		
		$lm = $x;
		$v_dim_x = 0;
		$y_svg = $y_svg2 = 0;
		self::v('y_t',self::v('y'));
		self::v('y',5,'+');

		if (!empty($bc_data["colortypename"])) {
			$type=$bc_data["colortypename"];
		} else {
			if (preg_match('#^Segmented$#i',$bc_data["colorcategory"])) $type='segmented';
			if (preg_match('#^Custom Solid$#i',$bc_data["colorcategory"])) $type='solid';
		}
		
		if (!empty($bc_data["colordata"]['band_uid'])) {
			$band_only_uid = $bc_data["colordata"]['band_uid'];
		} else {
			$band_only_uid = self::v('bc_uid');			
		}
		
		self::v('bc_data',$bc_data);
		
		###########################################
		//overwriting some cases
		///////////////////////////////////////////
		
		if ($TOPP['type']==7) {	//keychains
			
			$x-=20;
			$y_svg = -2;
			$y_svg2 = -7;
			
			self::v('y',12,'+');
			
			if ($TOPP['text_span']==1) {
				self::v('lm1',0);
			} else if ($TOPP['text_span']==0) {
				self::v('lm1',self::v('l.mm')/4-5);
				$y_svg2 = -8;
				$v_dim_x = self::v('l.mm')/-2+8;
			}
			
		} else if ($TOPP['type']==6) { // adj. snapbands
			
			self::v('l.mm',10,'+');
			if (self::v('l.mm')==200) self::v('l.mm',202);
			if (self::v('l.mm')==212) self::v('l.mm',215);
			self::v('l.in',round(self::v('l.mm')/25.4,1));
			
			self::v('lm1',-8.5);

		} else if ($TOPP['type']==5) { // slapbands
			
			self::v('w.mm',25.4);
			
		}
		
		$pdf->ImageSVG(svg_band_image(self::v('l.mm'),self::v('w.mm'),$band_only_uid,$type,$band_img,$TOPP),self::v('lm1')+$x,self::v('y')+6+$y_svg2);
		
		self::v('bimg',$band_img);
		self::v('lm1+x',self::v('lm1')+$x);
		
		self::band_text($prod,$TOPP);

		self::v('~y1',$y1=self::v('w.mm'));
		self::v('~y2',$y2=0.5);
		
		//arr h
		if ($TOPP['type']==7 && $TOPP["text_span"]==0) { // keychain , F/B span
			self::v('~y1',22,'+');
		} else {
			$pdf->ImageSVG(dfls.'img/arr_h.svg',self::v('lm1')+$lm,self::v('y')+7.2+$y1,self::v('l.mm'),0);
			$pdf->SetFont('helveticaltstd', '', 11, '', true);
			$t = "<p style=\"color:#c5c6c6;text-align:center\">".self::v('l.mm')."mm / ".self::v('l.in')." inch</p>";
			$pdf->writeHTMLCell(200,10,17,self::v('y')+7+$y1,$t);
		}
		
		//arr v
//		self::v_dim($v_dim_x+self::v('lm1')+$lm+self::v('l.mm'),$y_svg+self::v('y')-0.5,$y1);
		self::v_dim($v_dim_x+self::v('lm1')+$lm+self::v('l.mm'),$y_svg+self::v('y'),$y1);
		if ($TOPP['type']==7 && $TOPP['text_span']==0) {
			self::v_dim(self::v('lm1')+$lm-20,$y_svg+self::v('y')-0.5+KCHN_F_B_SPACE,$y1,true);
		}
	}
	
	static function v_dim($x,$y,$y1,$text_pos=0) {
		global $pdf;
		if (!empty($text_pos)) $text_pos = -10;
		$pdf->Polygon(array( 
			$x+2.3,$y+6,
			$x+3.3,$y+6+2,
			$x+2.4,$y+6+2,
			$x+2.4,$y+6+self::v('w.mm')-2,
			$x+3.3,$y+6+self::v('w.mm')-2,
			$x+2.3,$y+6+self::v('w.mm'),
			$x+1.3,$y+6+self::v('w.mm')-2,
			$x+2.2,$y+6+self::v('w.mm')-2,
			$x+2.2,$y+6+2,
			$x+1.3,$y+6+2,
			$x+2.3,$y+6,
		),'F',array(),array(197,198,198));
		
		$pdf->SetFont('helveticaltstd', '', 11, '', true);
		$pdf->StartTransform();
		$pdf->Rotate(90, 17, $y+$y1+4);
		$pdf->Translate(-73, $x-12+$text_pos);
		$t = "<p style=\"color:#c5c6c6;text-align:center\">".self::v('w.mm')." mm / ".self::v('w.in')." inch</p>";
		$pdf->writeHTMLCell(200,10,0,$y+$y1+4,$t);
		$pdf->StopTransform();

	}
	
	static function add_band($ord,$prod) {
				
		global $pdf;
		global $tpt_vars;
		global $tpt_orders_products;
		global $TOP;

		if ($_GET['dump']=='t') {
			echo '<pre>';
			var_dump($TOP,':::::::::::::::',$prod);
			echo '</pre>';
		}
		
		if (!empty($TOP[$prod['id']])) {
			$TOPP = $TOP[$prod['id']];
		} else {
			//oppaaaaaa
		//	var_dump('oppaaaaaa');
			$bt = 3;
			if ($ord["wristband_type"]=='Key Chains (1/2" wide)') $bt = 7;
		
			$TOPP=array(
				"type"=>$bt,
				"style"=>"2",
				"color"=>"3:11",
				"message_color"=>"3:24",
				"font"=>$ord["font"],
				"fmsg"=>$ord["front_message"],
				"fmsg2"=>$ord["front_message_line2"],
				"bmsg"=>$ord["back_message"],
				"bmsg2"=>$ord["back_message_line2"],
				"text_span"=>empty($ord["back_message"]) && empty($ord["back_message_line2"]) ? 1 : 0,
			);
		}
		
		$TOPP["fmsg"]=trim($TOPP["fmsg"]);
		$TOPP["fmsg2"]=trim($TOPP["fmsg2"]);
		$TOPP["bmsg"]=trim($TOPP["bmsg"]);
		$TOPP["bmsg2"]=trim($TOPP["bmsg2"]);
		
		if (empty($colors_module)) $colors_module = getModule($tpt_vars, "BandColor");
		
		$bc_uid = $prod['table_id'].':'.$prod['color_id'];
		$bc_uid = $bc_uid == '0:0' ? $TOPP['color'] : $prod['table_id'].':'.$prod['color_id'];
		
		self::v('bc_uid',$bc_uid);
		
		$bc_data = $colors_module->getColorProps($tpt_vars,$bc_uid);
		
	//	var_dump($TOPP,$prod['message_color']);
	//	die();
				
		if (empty($prod['message_color'])) {
			$mc_data = array();
		} else {
			if (preg_match('#[a-z]#i',$prod['message_color'])) {
				if (empty($TOP[$prod['id']])) {
					$clc = self::color_label_convert($prod['message_color']);
					$mcom = $colors_module->getColorProps($tpt_vars,$clc[0]['universal_id']);
					if ($mcom['colorname']!==null) {
						$mc_data = $mcom;
						$TOPP['message_color'] = $clc[0]['universal_id'];
					}
				} else {
					$mc_data = $colors_module->getColorProps($tpt_vars,$TOPP['message_color']);
				}
			} else {
				$mc_data = $colors_module->getColorProps($tpt_vars,$prod['message_color']);
			}
		}
		
		preg_match('#^([a-z0-9]+)\-([a-z0-9]+)\-([a-z0-9]+)\-([a-z0-9]+)\-#i',$prod["sku"],$sku_m);
		
		if (!empty($ord["TYPE"])) {
		//	var_dump('dasda',$bw_in);
			$bw_mm = $ord["TYPE"]["width_mm"];
			$bw_in = $ord["TYPE"]["width_in"];
		} elseif(preg_match('#^([1-9/]+)"#',$ord["wristband_type"],$t)) {
			$bw_in = eval('return '.$t[1].';');
			$bw_mm = floor($bw_in*25.4);
		} else {
			$bw_mm = 20;
			$bw_in = round($bw_mm/25.4,1);
		}
				
		//-------------------------------
		$sizes_2 = array(
			"Small / Child"=>array("in"=>7,"mm"=>180),
			"Medium / Youth"=>array("in"=>7.5,"mm"=>190),
			"Large / Adult"=>array("in"=>8,"mm"=>202),
		);
		//===============================
		
		if (!empty($sku_m[4])) {
			$bl_mm = $sku_m[4];
			$bl_in = round($bl_mm/25.4,1);
		} elseif(!empty($sizes_2[$prod['size']])) {
			$bl_mm = $sizes_2[$prod['size']]['mm'];
			$bl_in = $sizes_2[$prod['size']]['in'];
		} else {
			$bl_mm = 200;
			$bl_in = round($bl_mm/25.4,1);
		}
	
		$lm = (235.9-$bl_mm)/2;
			
		if ($_GET['dump']=='a') {
			echo '<pre>';
			var_dump($bl_mm,self::calculate_msg_dims($TOPP,$bl_mm,$bw_mm));
			echo '</pre>';
		}
		
		$msg_dims = self::calculate_msg_dims($TOPP,$bl_mm,$bw_mm);

		self::v('l.mm',$bl_mm);
		self::v('l.in',$bl_in);
		self::v('w.mm',$bw_mm);
		self::v('w.in',$bw_in);
		
		$pdf->startTransaction();
						
		// =========== band ==============

		if ($_GET['dump']==5) {
			echo '<pre>';
//			var_dump($prod['table_id'].':'.$prod['color_id']);
//			var_dump($tpt_orders_products[$prod['id']]['color']);
			var_dump($bc_uid);
//			var_dump($bc_data);
			echo '</pre>';
			die();
		}
		
		self::v('lm1',0);
		
		self::draw_band($bc_data,$TOPP,$prod,$lm);

		self::draw_message($TOPP,$msg_dims,$bl_mm,$bw_mm,$lm,$mc_data);
		
		$y1 = self::v('~y1');
		$y2 = self::v('~y2');
		
		//props
		$pdf->ImageSVG(dfls.'img/tick_yellow.svg',150,self::v('y')+11.5+$y1+$y2,0,0);
		$pdf->SetFont('helveticaltstdcompressed', '', 10, '', true);
		$pdf->setFontSpacing(0);
		$t = '<p style="line-height:16pt;text-align:left;color:#000000;">'.htmlentities($ord["wristband_style"])."</p>";
		$pdf->writeHTMLCell(0,10,155,self::v('y')+11+$y1+$y2,$t);
		$w = $pdf->GetStringWidth($ord["wristband_style"],'helveticaltstdcompressed','',9);
		
		$pdf->SetLineStyle(array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(241, 171, 39)));
		$pdf->RoundedRect(149.5, self::v('y')+10.97+$y1+$y2, $w+13, 4.6, 1, '1111', 'D');
		

		self::add_extras($prod,$y1,$y2,$TOPP);
		
		self::v('_y1',$y1);
		self::v('_y2',$y2);
				
		//qty box
		$pdf->SetFont('helveticaltstdcompressed', '', 10, '', true);
		$pdf->ImageSVG(dfls.'img/qty.svg',200,self::v('y')+10.8+$y1+$y2,20,0);
		$t = '<p style="line-height:10pt;text-align:center;color:#000000;">';
		$t.= 'QUANTITY<br />'.$prod["quantity"];
		$t.= "</p>";
		$pdf->setFontSpacing(0);
		$pdf->writeHTMLCell(20,10,200.5,self::v('y')+11.7+$y1+$y2,$t);
		
		if ($_GET['dump']==2) {
			echo '<pre>';
			var_dump($prod['message_color']);
			var_dump('============================================');
			var_dump($colors_module->getColorProps($tpt_vars,$prod['table_id'].':'.$prod['color_id']));
			var_dump('============================================');
			var_dump($colors_module->getColorProps($tpt_vars,$prod['message_color']));
//			var_dump('============================================');
//			var_dump($prod);
			var_dump('============================================');
			var_dump($ord);
			echo '</pre>';
			die();
		}			
		
		$yc = 0;
		$ymc = 0;
		$i = 1;
		
		$dual_layer_msg_colors = array();
		
		$cinc = 15.4;
		
		$firstcol = false;
		
		
		//band color box
		if (!empty($bc_data["colordata"]["band_colors"])) foreach ($bc_data["colordata"]["band_colors"] as $bc) {
			
			$pdf->ImageSVG(dfls.'img/color_box3.svg',70,self::v('y')+10.5+$y1+$y2+$yc,0,0);
			
			$pdf->setFontSpacing(-0.05);
			
			$pdf->SetFont('helveticaltstd', '', 9, '', true);
			
			$t = '<p style="line-height:10pt;text-align:left;color:#2b2a29;">';
			
//			if ($ord["wristband_style"]=='Debossed with color fill' && count($bc_data["colordata"]["band_colors"])==1) {
			if (empty($bc_data["dual_layer"]) && count($bc_data["colordata"]["band_colors"])==1) {
				$t.= 'Wristband Color';
				
			} elseif ($bc_data["dual_layer"]) {
			//	if (count($bc_data["colordata"]["band_colors"])==1) {
					$t.= 'Layer Color #1';
			//	}
				$dual_layer_msg_colors = $bc_data["colordata"]["message_colors"];
				
			} else {
				if (preg_match('#^segmented$#i',$bc_data["colorcategory"])) $t.= 'Segment #'.$i;
				else if (preg_match('#^swirl$#i',$bc_data["colorcategory"])) $t.= 'Swirl #'.$i;
				else $t.= $bc_data["colorcategory"].' #'.$i;
				
			}
			
			$t.= "</p>";
			
			$pdf->writeHTMLCell(100,10,81.8,self::v('y')+12+$y1+$y2+$yc,$t);
			
			$pdf->SetFont('helveticaltstdcompressed', '', 9.5, '', true);
			$pdf->setFontSpacing(0.1);
			$t = '<p style="line-height:10pt;text-align:left;color:#000000;">'.$bc["name"]."</p>";
			$pdf->writeHTMLCell(100,10,81.8,self::v('y')+16.5+$y1+$y2+$yc,$t);
			
			if (!empty($bc["nickname"]) && strtolower($bc["nickname"])!=strtolower($bc["name"])) {
				$t = '<p style="line-height:10pt;text-align:left;color:#000000;">'.$bc["nickname"]."</p>";
				$pdf->writeHTMLCell(100,10,81.8,self::v('y')+20.5+$y1+$y2+$yc,$t);
			}
			
			if (!empty($bc["hex"])) {
				$pdf->ImageSVG(svg_color_sample($bc["hex"]),70.2,self::v('y')+10.7+$y1+$y2+$yc,11.7,0);
			}
			
			if (empty($firstcol)) $firstcol = $bc;
			
			$i++;
			$yc+=$cinc;
			
		}
		
		
		if (!empty($mc_data["colordata"]["band_colors"])) {
			$msg_colors = $mc_data["colordata"]["band_colors"];
		}
		
//		var_dump($msg_colors $bc_data["colordata"]["message_colors"]);
//		die();
		
		if (empty($msg_colors) && !empty($bc_data["colordata"]["message_colors"])) {
			$msg_colors = $bc_data["colordata"]["message_colors"];
		}
//		if (empty($msg_colors) || $bc_data["dual_layer"]) {
		if (empty($msg_colors) && $bc_data["dual_layer"]) {
			$msg_colors = $dual_layer_msg_colors;
		}
		
		$i=1;
		//msg color box
		if (!empty($msg_colors)) foreach ($msg_colors as $mc) {
			
		//	$pdf->ImageSVG('img/color_box3.svg',70,self::v('y')+10.5+$y1+$y2+$yc,0,0);
			$pdf->ImageSVG(dfls.'img/color_box3.svg',105,self::v('y')+10.5+$y1+$y2+$ymc,0,0);
			
			$pdf->setFontSpacing(-0.05);
			
			$pdf->SetFont('helveticaltstd', '', 9, '', true);
			
			$t = '<p style="line-height:10pt;text-align:left;color:#2b2a29;">';
			
			if ($ord["wristband_style"]=='Debossed with color fill') {
				$t.= 'Ink Filled';
			
			} elseif ($ord["wristband_style"]=='Screen Print') {
				if (count($msg_colors)==1) {
					$t.= 'Screen Print';
				} else {
					$t.= 'Screen Print #'.$i;
				}
				
			} elseif ($ord["wristband_style"]=='Colorized Emboss') {
				if (count($msg_colors)==1) {
					$t.= 'Colorized';
				} else {
					$t.= 'Colorized #'.$i;
				}
				
			} elseif ($bc_data["dual_layer"]) {
				if (count($msg_colors)==1) {
					$t.= 'Layer Color #2';
				} else {
					if (!empty($bc_data["colordata"]["message_color_type"])) {
						$t.= ucfirst($bc_data["colordata"]["message_color_type"]).' #'.$i;
					}
				}
			} else {
				$t.= $bc_data["colorcategory"].' Color #'.$i;
			}
			$t.= "</p>";
			
			$pdf->writeHTMLCell(100,10,116.8,self::v('y')+12+$y1+$y2+$ymc,$t);
			
			$pdf->SetFont('helveticaltstdcompressed', '', 9.5, '', true);
			$pdf->setFontSpacing(0.1);
			$t = '<p style="line-height:10pt;text-align:left;color:#000000;">'.$mc["name"]."</p>";
			$pdf->writeHTMLCell(100,10,116.8,self::v('y')+16.5+$y1+$y2+$ymc,$t);
			
			if (!empty($mc["nickname"]) && strtolower($mc["nickname"])!=strtolower($mc["name"])) {
				$t = '<p style="line-height:10pt;text-align:left;color:#000000;">'.$mc["nickname"]."</p>";
				$pdf->writeHTMLCell(100,10,116.8,self::v('y')+20.5+$y1+$y2+$ymc,$t);
			}
			
			if (!empty($mc["hex"])) {
				$svg_sample = svg_color_sample($mc["hex"]);
				$pdf->ImageSVG($svg_sample,105.2,self::v('y')+10.7+$y1+$y2+$ymc,11.7,0);
			}
			
			$i++;
			$ymc+=$cinc;
			
		}
		
		if ($_GET['dump']=='c') {
			echo '<pre>';
			var_dump($bc_data,'============',$mc_data);
			echo '</pre>';
		}
		

		// thickness
		$pdf->Rect(27,self::v('y')+22+self::v('_y1')+self::v('_y2'),0.2,5,'F',array(),array(197,198,198));
		$pdf->Rect(29,self::v('y')+22+self::v('_y1')+self::v('_y2'),0.2,5,'F',array(),array(197,198,198));
		$pdf->Rect(19,self::v('y')+25+self::v('_y1')+self::v('_y2'),8,0.2,'F',array(),array(197,198,198));
		$pdf->Rect(29,self::v('y')+25+self::v('_y1')+self::v('_y2'),8,0.2,'F',array(),array(197,198,198));
		$pdf->SetFont('helveticaltstd', '', 11, '', true);
		$pdf->setFontSpacing(0);
		$pdf->writeHTMLCell(30,10,37,self::v('y')+23.5+self::v('_y1')+self::v('_y2'),'<b style="color:#c5c6c6;">2mm/0.078 inch</b>');

		$pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(150,150,150)));
		$pdf->SetFillColor($firstcol['red'],$firstcol['green'],$firstcol['blue']);
		$pdf->RoundedRect(27, self::v('y')+10.5+self::v('_y1')+self::v('_y2'), 2, 12, 0.5, '1111', 'DF');

				
		$ty = self::v('y')+29+$y1+$yc-$cinc;
		
		if ($ty<self::v('bot_bound') || self::v('rolled')===true || self::v('b.i')==0) {
			self::v('rolled',false);
			$pdf->commitTransaction();
			self::v('y',$ty);
			self::v('b.i',1,'+');
		
			self::v('type/style',json_encode(array(
				't'=>nl2br(htmlentities($ord["wristband_type"])),
				's'=>nl2br(htmlentities($ord["wristband_style"]))
			)),'k');
		
		} else {
			self::v('rolled',true);
			$pdf->rollbackTransaction(true);
			self::add_page();
			self::v('y',self::v('top_bound'));
			self::add_band($ord,$prod);
		}
		
	
	} 
	
	static function add_all_bands($ord_data) {
		
		global $pdf;
		
		$rush = 0;
		
		if (self::v('p')==0) self::add_page();
				
		foreach ($ord_data as $ord) {
			
			foreach ($ord['PROD'] as $prod) {

				if (!empty($prod["rush_order"])) $rush = 1;
				
			//	echo '<pre>';
			//	var_dump($ord,'--------------',$prod);
			//	echo '</pre>';

				
				self::add_band($ord,$prod);
								
				if (self::v('p')!=self::v('p1')) {
					if ($rush) {
						$pdf->ImageSVG(dfls.'img/rush.svg',77,50,31,0);
						$pdf->ImageSVG(dfls.'img/paid.svg',108,44,31,0);	
					} else {
						$pdf->ImageSVG(dfls.'img/paid.svg',82,42,52,0);
					}
					self::v('p1',self::v('p'));
				}
				
			}
		}
				
		self::write_type_style();
		
		$pdf->Output('template.pdf', 'I');
	}
}

PR_G::add_all_bands($ord_data);




