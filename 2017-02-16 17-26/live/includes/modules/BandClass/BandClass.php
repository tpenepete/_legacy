<?php

defined('TPT_INIT') or die('access denied');

class tpt_module_BandClass extends tpt_Module {

	function __construct(&$vars, $name, $moduleClassFile, $moduleClass, $moduleTable) {
		$fields = array(
			//db field name|field type|length|options|storage options|control|ctrAttr|default|label|index by|split keys|template
			new tpt_ModuleField('id',    'n', null, 'ai', '',         'sp', '', '', '',           true, false,  'LC'),
			new tpt_ModuleField('band_class',   'i', '',   '',   '', 'tf', ' style="width: 230px;"', '', 'Product Class Value (Real ID)', true, false, 'LC'),
			new tpt_ModuleField('writable',   'i', '',   '',   '', 'tf', ' style="width: 230px;"', 0, 'Writable', false, false, 'LC'),
			new tpt_ModuleField('name',  's', 255,  '',   '',         'tf', ' style="width: 170px;"', '', 'Band Size Name', false, false, 'LC'),
			new tpt_ModuleField('types_query',  's', 512,  '',   '',         'tf', ' style="width: 170px;"', '', 'Types Query', false, false, 'LC'),

		);
		parent::__construct($vars, $name, $moduleClassFile, $moduleClass, $moduleTable, $fields, 'id');
	}


	function userEndData(&$vars) {
		$_temp = array();
		$rArr = $this->moduleData['id'];
		foreach($rArr as $item) {
			$_temp[$item['id']] = array('name'=>$item['name'], 'label'=>$item['label']);
		}
		//var_dump($rArr);die();

		$rArr = $_temp;
		//var_dump($rArr);die();
		return $rArr;
	}


	function BandClass_PlainSelectSDN(&$vars, $sItem, $pid='null') {
		$items = $this->moduleData['id'];
		$html = '';
		$values = array();

		$title = 'Choose product class...';

		//var_dump($sItem);

		$sOpt=0;

		$i=0;
		foreach($items as $item) {
			$values[] = array($item['id'], $item['name']);

			if($sItem == $item['id'])
				$sOpt = $i;

			$i++;
		}

		$valuesDelimiter = "\n";

		return tpt_html::createSelect($vars, '', $values, $sOpt, ' autocomplete="off" id="'.$pid.'_control_class" title="'.$title.'" onfocus="removeClass(this, \'invalid_field\');" onchange="update_product_row(this);"');
		//return tpt_html::createSelect($vars, 'band_color', $values, $selectedColor, ' title="'.$title.'"');

		return $html;
	}


}
