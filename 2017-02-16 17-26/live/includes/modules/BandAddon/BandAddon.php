<?php

defined('TPT_INIT') or die('access denied');

class tpt_module_BandAddon extends tpt_Module {

	function __construct(&$vars, $name, $moduleClassFile, $moduleClass, $moduleTable) {
		$fields = array(
			//db field name|field type|length|options|storage options|control|ctrAttr|default|label|index by|split keys|template
			new tpt_ModuleField('id',    'n', null, 'ai', '',         'sp', '', '', '',           false, false,  'LC'),

		);
		parent::__construct($vars, $name, $moduleClassFile, $moduleClass, $moduleTable, $fields, 'id');
	}

	function SB_Section(&$vars, $section, $input=array(), $options=array(), &$vinput=array()) {
		$label = '<label>' . $section['label'] . '</label>';
		$control = tpt_html::createCheckbox($vars, $section['pname'], '1', (!empty($input[$section['pname']])?1:0), ' id="control_'.$section['id'].'" onclick=""');
		$html = '<span class="white-space-nowrap">' . $control . $label . '</span>';

		return $html;
	}
	/*
	function SB_Section(&$vars, $section, $input=array(), $options=array(), &$vinput=array()) {
		$types_module = getModule($vars, 'BandType');
		$styles_module = getModule($vars, 'BandStyle');
		$db = $vars['db']['handler'];

		//$pid = $product['id'];
		$pid = '';

		$data_module = getModule($vars, 'BandData');
		$data = $data_module->typeStyle;
		$cpf_module = getModule($vars, 'CustomProductField');
		$cpf_table = $cpf_module->moduleTable;

		$type = $types_module->getActiveItem($vars, $input, $options);
		$style = $styles_module->getActiveItem($vars, $input, $options);

		$data = !empty($data[$type][$style])?$data[$type][$style]:array();

		$addons = $db->getData($vars, $cpf_table, '*', ' `addon`!=0 AND `enabled`=1');
		$html = array();
		//tpt_dump($addons);
		foreach($addons as $addon) {
			if(empty($data) || (empty($data['pricing_type']) && (($addon['addon'] == 1)) || ($addon['addon'] == 2))) {
				$label = '<label>' . $addon['label'] . '</label>';
				$control = tpt_html::createCheckbox($vars, '', '1', (!empty($product[$addon['pname']])?1:0), ' id="'.$pid.'_control_'.$addon['pname'].'" onclick="update_product_row_field(this);"');
				$html[] = '<span class="white-space-nowrap">' . $label . $control . '</span>';
			}
		}

		$html = implode("&nbsp;&nbsp;\n&nbsp;&nbsp;", $html);


		//$caddsection = $this->getCustomAddonsList($vars, $pid);

		$html = <<< EOT
$html
EOT;


		return $html;
	}
	*/

	function getAddonsSection(&$vars, $qop, $product, $i) {
		$db = $vars['db']['handler'];

		$pid = $product['id'];

		$data_module = getModule($vars, 'BandData');
		$data = $data_module->typeStyle;
		$cpf_module = getModule($vars, 'CustomProductField');
		$cpf_table = $cpf_module->moduleTable;

		$pgType = $qop->data['band_type'];
		$pgStyle = $qop->data['band_style'];

		$data = !empty($data[$pgType][$pgStyle])?$data[$pgType][$pgStyle]:array();

		$addons = $db->getData($vars, $cpf_table, '*', ' `addon`!=0 AND `enabled`=1');
		$html = array();
		foreach($addons as $addon) {
			if(empty($data) || (empty($data['pricing_type']) && (($addon['addon'] == 1)) || ($addon['addon'] == 2))) {
				$label = '<label>' . $addon['label'] . '</label>';
				$control = tpt_html::createCheckbox($vars, '', '1', (!empty($product[$addon['pname']])?1:0), ' id="'.$pid.'_control_'.$addon['pname'].'" onclick="update_product_row_field(this);"');
				$html[] = '<span class="white-space-nowrap">' . $label . $control . '</span>';
			}
		}

		$html = implode("&nbsp;&nbsp;\n&nbsp;&nbsp;", $html);


		//$caddsection = $this->getCustomAddonsList($vars, $pid);

		$html = <<< EOT
$html
EOT;


		return $html;
	}


	function getCustomAddonsList(&$vars, $pid) {
		if(empty($pid)) {
			return '';
		}

		$caddtable = ORDERS_PRODUCTS_CUSTOM_ADDONS_TABLE;

		/*
		$status_module = getModule($vars, "OrderStatus");
		$mark_module = getModule($vars, 'OrderMark');
		$marks = $mark_module->moduleData['id'];
		*/

		$query = <<< EOT
		SELECT * FROM `$caddtable` WHERE `pid`=$pid
EOT;
		$vars['db']['handler']->query($query);
		$list = $vars['db']['handler']->fetch_assoc_list('id', false);

		$colnames = array();
		$colnames['#'] = '#';

		$colnames['Name'] = 'Name';
		$colnames['Qty'] = 'Qty';
		$colnames['Subtotal'] = 'Subtotal';
		$colnames['Shipping'] = 'Shipping';
		$colnames['Tax'] = 'Tax';
		$colnames['Discount'] = 'Discount';

		/*
		$styles = array_keys($colnames);
		$styles = array_fill_keys($styles, '');
		$styles['Comments'] = ' style="width: 80%;"';
		$styles['Hide'] = '';
		*/

		$phtml = '<table cellpadding="0" cellspacing="0" border="0" class="width-100prc summ_list cadd_list">';

		$phtml .= '<thead>';
		$phtml .= '<tr>';
		$phtml .= tpt_html::getAlternatingHTML($colnames, 'th', array('header0 padding-left-5 padding-right-5', 'header1 padding-left-5 padding-right-5'));
		$phtml .= '</tr>';
		$phtml .= '</thead>';

		$phtml .= '<tbody>';
		$rows = array();
		$i = 1;
		foreach($list as $c) {
			$inp = array();
			$inp['#'] = $i;
			$i++;
			//$inp['Comment'] = preg_replace('#^(^$|[^"])*?((?:https?://)?(?:(?:www\.)?.*\.(?:com|net|org))[^<\s]*)#', '$1<a href="$2">$2</a>$3', $c['comment']);
			$inp['Name'] = '<div class="inline-block padding-left-2 padding-right-2"><input autocomplete="off" class="width-100prc" style="box-sizing: border-box;" type="text" name="" value="'.$c['name'].'" /></div>';
			$inp['Qty'] = '<input autocomplete="off" class="width-20" type="text" name="" value="'.$c['qty'].'" />';
			$inp['Subtotal'] = '<span class="white-space-nowrap">&#36;<input autocomplete="off" class="width-30" type="text" name="" value="'.number_format($c['price_subtotal'], 2).'" /></span>';
			$inp['Shipping'] = '<span class="white-space-nowrap">&#36;<input autocomplete="off" class="width-30" type="text" name="" value="'.number_format($c['price_shipping'], 2).'" /></span>';
			$inp['Tax'] = '<span class="white-space-nowrap">&#36;<input autocomplete="off" class="width-30" type="text" name="" value="'.number_format($c['price_tax'], 2).'" /></span>';
			$inp['Discount'] = '<span class="white-space-nowrap">&#36;<input autocomplete="off" class="width-30" type="text" name="" value="'.number_format($c['price_discount'], 2).'" /></span>';

			$row = tpt_html::getAlternatingHTML($inp, 'td', array('qos_cell cell0 padding-left-5 padding-right-5', 'qos_cell cell1 padding-left-5 padding-right-5'));
			$rows[] = $row;
		}
		$phtml .= tpt_html::getAlternatingHTML($rows, 'tr', array('qos_row quote_row oddrow', 'qos_row quote_row evenrow'));
		$phtml .= '</tbody>';
		$phtml .= '</table>';

		return <<< EOT
$phtml
<a id="${pid}_add_custom_addon" onclick="add_custom_addon(this);" class="amz_green display-inline-block height-20 line-height-20 padding-left-10 padding-right-10 font-size-100prc" href="javascript:void(0);">+ Add Custom Addon</a>
EOT;

	}
}
