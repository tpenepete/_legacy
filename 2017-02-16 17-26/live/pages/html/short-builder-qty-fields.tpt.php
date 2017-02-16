<?php

defined('TPT_INIT') or die('access denied');
//$fields_data2 = $tpt_vars['db']['handler']->getData($tpt_vars, 'tpt_form_add_billing_address_form_fields', '*', 'enabled=1', 'id', false);

$fbl3 = array(
            '<div class="amz_red todayshop-bold width-90">Size</div>'
            );
$fbc3 = array(
            '<div class="amz_red todayshop-bold text-align-center">Quantity</div>'
            );
$fba3 = array(
            '<div style="position: relative; top:3px !important;" class="amz_red todayshop-bold text-align-center">Price</div>'
            );

$fields_data3 = array();


$types_module = getModule($tpt_vars, "BandType");
$sizes_module = getModule($tpt_vars, "BandSize");
$data_module = getModule($tpt_vars, "BandData");
$bdata = (isset($data_module->typeStyle[$pgType][$pgStyle])?$data_module->typeStyle[$pgType][$pgStyle]:array('available_sizes_id'=>''));
$band_sizes = explode(',', $bdata['available_sizes_id']);

//var_dump($pgSizesQty);die();

$calcprice = false;
// Size/Qty
foreach($band_sizes as $szid) {
	if(!isset($sizes_module->moduleData['id'][$szid])) {
		continue;
	}
	$size = $sizes_module->moduleData['id'][$szid];
    $ssuf = $size['name'];

	$szlbl = $sizes_module->moduleData['id'][$szid]['label'];
	$lblcomp = explode(' / ', $szlbl);
	if(!empty($lblcomp[1])) {
		$lblcomp[1] = preg_replace('#[\s]+(\.|[0-9]+)+"$#', '', $lblcomp[1]);
		$lblcomp[1] = '('.$lblcomp[1].')';
	}
	$lbl = implode('<br />', $lblcomp);
	//preg_replace('#(.*?)[\s]*/[\s]*([a-zA-Z]+).*#', '$1<br /><span class="font-size-11">($2)</span>', getModule($tpt_vars, "BandSize")->moduleData['id'][$szid]['label'])

	$qtyval = '';
	if(!empty($pgSizesQty['qty_'.$ssuf])) {
		$qtyval = $pgSizesQty['qty_'.$ssuf];
		$calcprice = true;
	}
	//var_dump($qtyval);die();

	$fields_row2 = array(
		'id'=>'1',
		'label'=>'<span class="amz_brown">'.$lbl.'</span>',
		'name'=>'qty_'.$ssuf,
		'control'=>'t',
		'classes'=>'',
		'order'=>'',
		'value'=>$qtyval,
		'html_attribs'=>'maxlength="5" onfocus="unhighlight_qty_fields();" onkeypress="return numbersonly(this, event);" id="qty_input_'.$ssuf.'"',
		'oncheck'=>'',
		'onuncheck'=>'',
		'row_height'=>'24',
		'label_line_height'=>'12',
		'control_line_height'=>'24',
		'after_line_height'=>'24',
		'after_content'=>'
		<div class="text-align-center" >
			<span id="update_price_'.$ssuf.'" class="amz_red font-size-16" >  --  </span>
		</div>',
		'required'=>'',
		'validation_regex'=>'',
		'store_field'=>'',
		'enabled'=>''
	);
	$fields_data3[] = $fields_row2;
}

$fields_row2 = array(
    'id'=>'1',
    'label'=>'',
    'name'=>'qty_sttl',
    'control'=>'s',
    'classes'=>'',
    'order'=>'',
    'value'=>'<div class="amz_red text-align-right font-size-20 todayshop-bold">Subtotal:</div>',
    'html_attribs'=>'',
    'oncheck'=>'',
    'onuncheck'=>'',
    'row_height'=>'24',
    'label_line_height'=>'12',
    'control_line_height'=>'24',
    'after_line_height'=>'24',
    'after_content'=>'<div class="text-align-center" ><span id="update_price_subtotal" class="amz_red font-size-16" >  --  </span></div>',
    'required'=>'',
    'validation_regex'=>'',
    'store_field'=>'',
    'enabled'=>'',
);
$fields_data3[] = $fields_row2;

$fields_row2 = array(
    'id'=>'1',
    'label'=>'',
    'name'=>'',
    'control'=>'s',
    'classes'=>'',
    'order'=>'',
    'value'=>'',
    'html_attribs'=>'',
    'oncheck'=>'',
    'onuncheck'=>'',
    'row_height'=>'24',
    'label_line_height'=>'12',
    'control_line_height'=>'24',
    'after_line_height'=>'24',
    'after_content'=>'<div class="text-align-right"><input title="Update Price" class="update_price_btn plain-input-field hoverCB background-position-CT background-repeat-no-repeat update_price_btn width-83 height-21" id="upd_price_btn" type="button" value="Update" onclick="if(validate_short_builder()){amz_update_pricing(this);}" /></div>',
// with validation
    //'after_content'=>'<div class="text-align-right"><input title="Update Price" class="update_price_btn plain-input-field hoverCB background-position-CT background-repeat-no-repeat update_price_btn width-83 height-21"  type="button" value="" onclick="validate_designset()?amz_update_pricing(this):void(\'\');" /></div>',
    'required'=>'',
    'validation_regex'=>'',
    'store_field'=>'',
    'enabled'=>'',
);
$fields_data3[] = $fields_row2;

//$label_width_class = '90';
//include(TPT_TEMPLATES_DIR.DIRECTORY_SEPARATOR.'form-fields4.tpt.php');
//$section_html = tpt_html::render_form_fields4($tpt_vars, $fields_data3, ' width-90');
$section_html = tpt_html::render_form_fields4($tpt_vars, $fields_data3, ' width-119', $fbl3, $fbc3, $fba3);
/*
$section_html = <<< EOT
<div id="bulkpricingmessage" class="visibility-hidden color-red font-weight-bold font-size-14 font-style-italic">You are getting bulk pricing!</div>
$section_html
EOT;
*/

//var_dump($builder['id']);die();



if($calcprice) {
    $tpt_vars['template_data']['footer_scripts']['content'][] = <<< EOT
    <script type="text/javascript">
        amz_update_pricing(document.getElementById('upd_price_btn'));
        //document.getElementById('bulkpricingmessage').style.visibility='visible';
        //addClass();
        removeClass(document.getElementById('bulkpricingmessage'), 'visibility-hidden');
    </script>
EOT;
}


