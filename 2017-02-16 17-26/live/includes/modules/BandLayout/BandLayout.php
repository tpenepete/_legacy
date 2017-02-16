<?php

defined('TPT_INIT') or die('access denied');

class tpt_module_BandLayout extends tpt_Module {


	// a cool thing that allows adding methods to a object...
	public function __call($method, $args) {
		if (isset($this->$method)) {
			$func = $this->$method;
			return call_user_func_array($func, $args);
		}
	}


	function __construct(&$vars, $name, $moduleClassFile, $moduleClass, $moduleTable) {
		$fields = array(
			//db field name|field type|length|options|storage options|control|ctrAttr|default|label|index by|split keys|template
			new tpt_ModuleField('id',    'n', null, 'ai', '',         'sp', '', '', '',           true, false,  'LC'),
			new tpt_ModuleField('label',  's', 255,  '',   '',         'tf', ' style="width: 170px;"', '0', 'label', false, false, 'LC'),
			new tpt_ModuleField('pname',  's', 255,  '',   '',         'tf', ' style="width: 170px;"', '0', 'control name (Newest Standard)', false, false, 'LC'),
			new tpt_ModuleField('line2',  'ti', '',  '',   '',         'tf', ' style="width: 170px;"', '', 'Line 2 Message?', false, false, 'LC'),
			new tpt_ModuleField('back',  'ti', '',  '',   '',         'tf', ' style="width: 170px;"', '', 'Back Message?', false, false, 'LC'),
			new tpt_ModuleField('inside',  'ti', '',  '',   '',         'tf', ' style="width: 170px;"', '', 'Inside Message?', false, false, 'LC'),
			new tpt_ModuleField('gravity',  's', 512,  '',   '',         'tf', ' style="width: 170px;"', '', 'Message Gravity', false, false, 'LC'),
			new tpt_ModuleField('text_separator',  's', 32,  '',   '',         'tf', ' style="width: 170px;"', '', 'Preview IMG Id', false, false, 'LC'),
			new tpt_ModuleField('text_frontback',  'ti', '',  '',   '',         'tf', ' style="width: 170px;"', '1', 'Front/Back Layout', false, false, 'LC'),
			new tpt_ModuleField('text_topbottom',  'ti', '',  '',   '',         'tf', ' style="width: 170px;"', '1', 'Top/Bottom Layout', false, false, 'LC'),
			new tpt_ModuleField('clipart_leftright',  'ti', '',  '',   '',         'tf', ' style="width: 170px;"', '1', 'Left/Right Clipart Layout', false, false, 'LC'),
			new tpt_ModuleField('clipart_xpadding',  'i', '',  '',   '',         'tf', ' style="width: 170px;"', '2', 'Clipart Horizontal Padding', false, false, 'LC'),
			new tpt_ModuleField('cont_id',  's', 32,  '',   '',         'tf', ' style="width: 170px;"', '', 'Container Id', false, false, 'LC'),
			new tpt_ModuleField('line2_cont_id',  's', 32,  '',   '',         'tf', ' style="width: 170px;"', '', 'Line2 Container Id', false, false, 'LC'),
			new tpt_ModuleField('title',  's', 64,  '',   '',         'tf', ' style="width: 170px;"', '', 'Message Title', false, false, 'LC'),
			new tpt_ModuleField('onclick',  's', 512,  '',   '',         'tf', ' style="width: 170px;"', '', 'Preview JS Timeout Varname', false, false, 'LC'),
			new tpt_ModuleField('cfgvarname_default_message',  's', 32,  '',   '',         'tf', ' style="width: 170px;"', '', 'Default Message Config Constant Name', false, false, 'LC'),
			new tpt_ModuleField('cfgvarname_pointsize',  's', 32,  '',   '',         'tf', ' style="width: 170px;"', '', 'Default Message Pointsize Config Constant Name', false, false, 'LC'),
			new tpt_ModuleField('var1',  's', 32,  '',   '',         'tf', ' style="width: 170px;"', '', 'var1', false, false, 'LC'),
			new tpt_ModuleField('var2',  's', 32,  '',   '',         'tf', ' style="width: 170px;"', '', 'var2', false, false, 'LC'),
			new tpt_ModuleField('name',  's', 32,  '',   '',         'tf', ' style="width: 170px;"', '', 'tpt_module_CustomProductField name', false, false, 'LC'),
			//'<div class="tpt_admin_module_section float-left" style="border: 2px solid #FFF;">',
			//'</div>',
			//'<div class="float-left padding-top-20 padding-bottom-20 padding-left-10 padding-right-10" style="background-color: #FFF;"><div class="display-inline-block height-10 width-80" style="background-color: #`HEX`; border: 1px solid #000;"></div></div>',
			//'<div class="float-left" style="background-color: #`HEX`; border: 1px solid #000;"><img src="'.$vars['config']['resourceurl'].'/images/Band-Transperent-Preview.png" class="width-80" /></div>',
			//'<div class="float-left" style="background-color: #`HEX`; border: 1px solid #000;"><img src="'.$vars['config']['resourceurl'].'/images/Transparent-Swirl-Band-Preview.png" class="width-80" /></div>'
		);


		parent::__construct($vars, $name, $moduleClassFile, $moduleClass, $moduleTable, $fields, 'id');
	}


	function userEndData(&$vars) {
		$_temp = array();
		$rArr = $this->moduleData['name'];
		foreach($rArr as $item) {
			$_temp[$item['name']] = array('name'=>$item['name'], 'line2'=>$item['line2'], 'back'=>$item['back'], 'inside'=>$item['inside']);
		}
		//var_dump($rArr);die();

		$rArr = $_temp;
		//var_dump($rArr);die();
		return $rArr;
	}

	function getDefaultItem(&$vars, $input, $options) {
		$types_module = getModule($vars, 'BandType');
		$styles_module = getModule($vars, 'BandStyle');
		$data_module = getModule($vars, 'BandData');

		$type = $types_module->getActiveItem($vars, $input, $options);
		$style = $styles_module->getActiveItem($vars, $input, $options);
		$bdata = $data_module->typeStyle[$type][$style];

		//$scs = explode(',', $bdata['builder_sections']);
		//$scs = array_combine($scs, $scs);
		//$scs = array_intersect_key($bsection_module->moduleData['id'], $scs);

		return $bdata['default_layout'];
		//tpt_dump($items);
		//tpt_dump($stdstyle);
		//tpt_dump($sitem);

	}
	function getSelectedItem(&$vars, $input, $options) {
		/*
		$types_module = getModule($vars, 'BandType');
		$styles_module = getModule($vars, 'BandStyle');
		$data_module = getModule($vars, 'BandData');

		$type = $types_module->getActiveItem($vars, $input, $options);
		$style = $styles_module->getActiveItem($vars, $input, $options);
		$bdata = $data_module->typeStyle[$type][$style];
		*/

		//$scs = explode(',', $bdata['builder_sections']);
		//$scs = array_combine($scs, $scs);
		//$scs = array_intersect_key($bsection_module->moduleData['id'], $scs);

		return (isset($input['band_layout'])?intval($input['band_layout'], 10):$this->getDefaultItem($vars, $input, $options));
		//tpt_dump($items);
		//tpt_dump($stdstyle);
		//tpt_dump($sitem);

	}


	function SB_Section(&$vars, $section, $input=array(), $options=array(), &$vinput=array()) {
		$types_module = getModule($vars, 'BandType');
		$styles_module = getModule($vars, 'BandStyle');
		$data_module = getModule($vars, 'BandData');
		$cpf_module = getModule($vars, 'CustomProductField');

		$type = $types_module->getActiveItem($vars, $input, $options);
		$style = $styles_module->getActiveItem($vars, $input, $options);
		//tpt_dump($type);
		//tpt_dump($style);
		$data = $data_module->typeStyle[$type][$style];


		$sItem = $this->getSelectedItem($vars, $input, $options);

		$sid = $section['id'];
		$pname = $section['pname'];

		$layouts = explode(',', $data['layouts_ids']);
		$layouts = array_intersect_key($this->moduleData['id'], array_combine($layouts, $layouts));




		$html = array();
		//tpt_dump($sItem);
		foreach($layouts as $id=>$layout) {
			//tpt_dump($id);

			$selectedClass = $id == $sItem ? 'mspanactv' : '';
			$label = htmlspecialchars($layout['label']);
			$onclick = (!empty($layout['onclick'])?' onclick="'.htmlspecialchars($layout['onclick']).'"':'');
			$control = tpt_html::createRadiobutton($vars, $pname, $id, $sItem, $onclick.' class="margin-0" id="control_'.$sid.'_'.$id.'"');
			$html[] = <<< EOT
			<span class="display-inline highlight_{$sid}_{$id} $selectedClass" >
<label style="font-family: Arial, Helvetica, sans-serif;" class="amz_brown font-size-14 font-weight-bold display-inline-block line-height-16" for="control_{$sid}_{$id}">
	<span>$label</span>
</label>&nbsp;&nbsp;
$control
</span>
EOT;
		}

		//$html = '<span class="display-inline-block width-10">'.implode('</span><span class="display-inline-block width-10">', $html).'<span>';
		$html = implode('<span class="display-inline-block width-10"></span>', $html);

		return $html;
	}





}

