<?php

defined('TPT_INIT') or die('access denied');

class tpt_module_PreviewLayer extends tpt_Module {

	function __construct(&$vars, $name, $moduleClassFile, $moduleClass, $moduleTable) {
		$fields = array(
			//db field name|field type|length|options|storage options|control|ctrAttr|default|label|index by|split keys|template
			new tpt_ModuleField('id',    'n', null, 'ai', '',         'sp', '', '', '',           true, false,  'LC', ' (1=1) ORDER BY `order` ASC, `id` ASC'),
			new tpt_ModuleField('description',  's', 32,  '',   '',         'tf', ' style="width: 170px;"', '', 'Description', false, false, 'LC'),
			new tpt_ModuleField('layertype',  's', 32,  '',   '',         'tf', ' style="width: 170px;"', '', 'Layer Type', false, false, 'LC'),
			new tpt_ModuleField('target',  's', 255,  '',   '',         'tf', ' style="width: 170px;"', '', 'target index value', false, false, 'LC'),
			new tpt_ModuleField('preview_params_ids',  's', 255,  '',   '',         'tf', ' style="width: 170px;"', '', 'preview url params (CS tpt_module_customproductfield ids)', false, false, 'LC'),
			new tpt_ModuleField('nullcheck_preview_params_ids',  's', 255,  '',   '',         'tf', ' style="width: 170px;"', '', 'nullcheck preview url params (PS,CS tpt_module_buildersection ids)', false, false, 'LC'),
			new tpt_ModuleField('pType',   'ti', '',   '',   '', 'tf', ' style="width: 100px;"', '0', 'use pType value', false, false, 'LC'),
			new tpt_ModuleField('pStyle',   'ti', '',   '',   '', 'tf', ' style="width: 100px;"', '0', 'use pStyle value', false, false, 'LC'),
			new tpt_ModuleField('options',  's', 255,  '',   '',         'tf', ' style="width: 170px;"', '', 'options', false, false, 'LC'),
			new tpt_ModuleField('led_glow',   'ti', '',   '',   '', 'tf', ' style="width: 100px;"', '0', 'led glow band outline layer', false, false, 'LC'),
			new tpt_ModuleField('order',   'f', '',   '',   '', 'tf', ' style="width: 100px;"', '', 'HTML Depth', false, false, 'LC'),
			new tpt_ModuleField('image',  's', 255,  '',   '',         'tf', ' style="width: 170px;"', '', 'image filename', false, false, 'LC'),
			new tpt_ModuleField('cX',   'i', '',   '',   '', 'tf', ' style="width: 100px;"', '', 'canvas width', false, false, 'LC'),
			new tpt_ModuleField('cY',   'i', '',   '',   '', 'tf', ' style="width: 100px;"', '', 'canvas height', false, false, 'LC'),
			new tpt_ModuleField('cpL',   'i', '',   '',   '', 'tf', ' style="width: 100px;"', '', 'canvas padding left', false, false, 'LC'),
			new tpt_ModuleField('cpR',   'i', '',   '',   '', 'tf', ' style="width: 100px;"', '', 'canvas padding right', false, false, 'LC'),
			new tpt_ModuleField('cpT',   'i', '',   '',   '', 'tf', ' style="width: 100px;"', '', 'canvas padding top', false, false, 'LC'),
			new tpt_ModuleField('cpB',   'i', '',   '',   '', 'tf', ' style="width: 100px;"', '', 'canvas padding bottom', false, false, 'LC'),
			new tpt_ModuleField('tile',   'ti', '',   '',   '', 'tf', ' style="width: 100px;"', '0', 'tile image over canvas', false, false, 'LC'),
			new tpt_ModuleField('defined_area',   'ti', '',   '',   '', 'tf', ' style="width: 100px;"', '0', 'place over predefined canvas area', false, false, 'LC'),
			new tpt_ModuleField('resize',   'ti', '',   '',   '', 'tf', ' style="width: 100px;"', '0', 'resize final image', false, false, 'LC'),
			new tpt_ModuleField('overlay',   'ti', '',   '',   '', 'tf', ' style="width: 100px;"', '0', 'color overlay image', false, false, 'LC'),
			new tpt_ModuleField('overlay_color',  's', 32,  '',   '',         'tf', ' style="width: 170px;"', '', 'Overlay Color', false, false, 'LC'),
			new tpt_ModuleField('snug_fit_label',   'ti', '',   '',   '', 'tf', ' style="width: 100px;"', '0', 'use up all x/y space for the label', false, false, 'LC'),
			new tpt_ModuleField('effects_shadow_inner',   'ti', '',   '',   '', 'tf', ' style="width: 100px;"', '0', 'effects shadow inner', false, false, 'LC'),
			new tpt_ModuleField('effects_shadow_inner_width_top',  's', 64,  '',   '',         'tf', ' style="width: 170px;"', '', 'effects shadow inner width top', false, false, 'LC'),
			new tpt_ModuleField('effects_shadow_inner_width_right',  's', 64,  '',   '',         'tf', ' style="width: 170px;"', '', 'effects shadow inner width right', false, false, 'LC'),
			new tpt_ModuleField('effects_shadow_inner_width_bottom',  's', 64,  '',   '',         'tf', ' style="width: 170px;"', '', 'effects shadow inner width bottom', false, false, 'LC'),
			new tpt_ModuleField('effects_shadow_inner_width_left',  's', 64,  '',   '',         'tf', ' style="width: 170px;"', '', 'effects shadow inner width left', false, false, 'LC'),
			new tpt_ModuleField('effects_shadow_inner_spread_top',  's', 64,  '',   '',         'tf', ' style="width: 170px;"', '', 'effects shadow inner spread top', false, false, 'LC'),
			new tpt_ModuleField('effects_shadow_inner_spread_right',  's', 64,  '',   '',         'tf', ' style="width: 170px;"', '', 'effects shadow inner spread right', false, false, 'LC'),
			new tpt_ModuleField('effects_shadow_inner_spread_bottom',  's', 64,  '',   '',         'tf', ' style="width: 170px;"', '', 'effects shadow inner spread bottom', false, false, 'LC'),
			new tpt_ModuleField('effects_shadow_inner_spread_left',  's', 64,  '',   '',         'tf', ' style="width: 170px;"', '', 'effects shadow inner spread left', false, false, 'LC'),
			new tpt_ModuleField('effects_shadow_inner_color_top',  's', 64,  '',   '',         'tf', ' style="width: 170px;"', '', 'effects shadow inner color top', false, false, 'LC'),
			new tpt_ModuleField('effects_shadow_inner_color_right',  's', 64,  '',   '',         'tf', ' style="width: 170px;"', '', 'effects shadow inner color right', false, false, 'LC'),
			new tpt_ModuleField('effects_shadow_inner_color_bottom',  's', 64,  '',   '',         'tf', ' style="width: 170px;"', '', 'effects shadow inner color bottom', false, false, 'LC'),
			new tpt_ModuleField('effects_shadow_inner_color_left',  's', 64,  '',   '',         'tf', ' style="width: 170px;"', '', 'effects shadow inner color left', false, false, 'LC'),
			new tpt_ModuleField('effects_shadow_inner_opacity_top',  's', 64,  '',   '',         'tf', ' style="width: 170px;"', '', 'effects shadow inner opacity top', false, false, 'LC'),
			new tpt_ModuleField('effects_shadow_inner_opacity_right',  's', 64,  '',   '',         'tf', ' style="width: 170px;"', '', 'effects shadow inner opacity right', false, false, 'LC'),
			new tpt_ModuleField('effects_shadow_inner_opacity_bottom',  's', 64,  '',   '',         'tf', ' style="width: 170px;"', '', 'effects shadow inner opacity bottom', false, false, 'LC'),
			new tpt_ModuleField('effects_shadow_inner_opacity_left',  's', 64,  '',   '',         'tf', ' style="width: 170px;"', '', 'effects shadow inner opacity left', false, false, 'LC'),
			new tpt_ModuleField('inner_shadow',   'ti', '',   '',   '', 'tf', ' style="width: 100px;"', '0', 'has inner shadow', false, false, 'LC'),
			new tpt_ModuleField('inner_shadow_angle',   'f', '',   '',   '', 'tf', ' style="width: 100px;"', '', 'inner shadow angle', false, false, 'LC'),
			new tpt_ModuleField('inner_shadow_distance_x',   'i', '',   '',   '', 'tf', ' style="width: 100px;"', '', 'inner shadow distance x', false, false, 'LC'),
			new tpt_ModuleField('inner_shadow_distance_y',   'i', '',   '',   '', 'tf', ' style="width: 100px;"', '', 'inner shadow distance y', false, false, 'LC'),
			new tpt_ModuleField('inner_shadow_opacity',   'f', '',   '',   '', 'tf', ' style="width: 100px;"', '', 'inner shadow opacity', false, false, 'LC'),
			new tpt_ModuleField('inner_shadow_color',  's', 32,  '',   '',         'tf', ' style="width: 170px;"', '', 'inner shadow color', false, false, 'LC'),
			new tpt_ModuleField('drop_shadow',   'ti', '',   '',   '', 'tf', ' style="width: 100px;"', '0', 'drops shadow', false, false, 'LC'),
			new tpt_ModuleField('drop_shadow_angle',   'f', '',   '',   '', 'tf', ' style="width: 100px;"', '', 'drop shadow angle', false, false, 'LC'),
			new tpt_ModuleField('drop_shadow_distance_x',   'i', '',   '',   '', 'tf', ' style="width: 100px;"', '', 'drop shadow distance x', false, false, 'LC'),
			new tpt_ModuleField('drop_shadow_distance_y',   'i', '',   '',   '', 'tf', ' style="width: 100px;"', '', 'drop shadow distance y', false, false, 'LC'),
			new tpt_ModuleField('drop_shadow_opacity',   'f', '',   '',   '', 'tf', ' style="width: 100px;"', '', 'drop shadow opacity', false, false, 'LC'),
			new tpt_ModuleField('drop_shadow_color',  's', 32,  '',   '',         'tf', ' style="width: 170px;"', '', 'drop shadow color', false, false, 'LC'),
			new tpt_ModuleField('inner_glow',   'ti', '',   '',   '', 'tf', ' style="width: 100px;"', '0', 'has inner glow', false, false, 'LC'),
			new tpt_ModuleField('inner_glow_angle',   'f', '',   '',   '', 'tf', ' style="width: 100px;"', '', 'inner glow angle', false, false, 'LC'),
			new tpt_ModuleField('inner_glow_distance_x',   'i', '',   '',   '', 'tf', ' style="width: 100px;"', '', 'inner glow distance x', false, false, 'LC'),
			new tpt_ModuleField('inner_glow_distance_y',   'i', '',   '',   '', 'tf', ' style="width: 100px;"', '', 'inner glow distance y', false, false, 'LC'),
			new tpt_ModuleField('inner_glow_opacity',   'f', '',   '',   '', 'tf', ' style="width: 100px;"', '', 'inner glow opacity', false, false, 'LC'),
			new tpt_ModuleField('inner_glow_color',  's', 32,  '',   '',         'tf', ' style="width: 170px;"', '', 'inner glow color', false, false, 'LC'),
			new tpt_ModuleField('outer_glow',   'ti', '',   '',   '', 'tf', ' style="width: 100px;"', '0', 'outer glow', false, false, 'LC'),
			new tpt_ModuleField('outer_glow_angle',   'f', '',   '',   '', 'tf', ' style="width: 100px;"', '', 'outer glow angle', false, false, 'LC'),
			new tpt_ModuleField('outer_glow_distance_x',   'i', '',   '',   '', 'tf', ' style="width: 100px;"', '', 'outer glow distance x', false, false, 'LC'),
			new tpt_ModuleField('outer_glow_distance_y',   'i', '',   '',   '', 'tf', ' style="width: 100px;"', '', 'outer glow distance y', false, false, 'LC'),
			new tpt_ModuleField('outer_glow_opacity',   'f', '',   '',   '', 'tf', ' style="width: 100px;"', '', 'outer glow opacity', false, false, 'LC'),
			new tpt_ModuleField('outer_glow_color',  's', 32,  '',   '',         'tf', ' style="width: 170px;"', '', 'outer glow color', false, false, 'LC'),
			new tpt_ModuleField('layout',   'i', '',   '',   '', 'tf', ' style="width: 100px;"', '1', 'band layout', false, false, 'LC'),
			new tpt_ModuleField('text',  's', 512,  '',   '',         'tf', ' style="width: 170px;"', '', 'Text', false, false, 'LC'),
			new tpt_ModuleField('kern',   'f', '',   '',   '', 'tf', ' style="width: 100px;"', '', 'kerning', false, false, 'LC'),
			new tpt_ModuleField('color',  's', 32,  '',   '',         'tf', ' style="width: 170px;"', '', 'Color', false, false, 'LC'),
			new tpt_ModuleField('background',  's', 32,  '',   '',         'tf', ' style="width: 170px;"', '', 'Background', false, false, 'LC'),
			new tpt_ModuleField('stroke',   'ti', '',   '',   '', 'tf', ' style="width: 100px;"', '0', 'Text Stroke', false, false, 'LC'),
			new tpt_ModuleField('stroke_color',  's', 32,  '',   '',         'tf', ' style="width: 170px;"', '', 'Stroke Color', false, false, 'LC'),
			new tpt_ModuleField('stroke_width',   'i', '',   '',   '', 'tf', ' style="width: 100px;"', '', 'Stroke Width', false, false, 'LC'),
			new tpt_ModuleField('gravity',  's', 32,  '',   '',         'tf', ' style="width: 170px;"', '', 'Image Alignment', false, false, 'LC'),
			new tpt_ModuleField('opacity',   'f', '',   '',   '', 'tf', ' style="width: 100px;"', '', 'Layer Opacity', false, false, 'LC'),
			new tpt_ModuleField('html_styles',  's', '',  '',   '',         'tf', ' style="width: 170px;"', '', 'HTML Style Attribute Value', false, false, 'LC'),
			new tpt_ModuleField('html_classes',  's', '',  '',   '',         'tf', ' style="width: 170px;"', '', 'HTML Class Attribute Value', false, false, 'LC'),
			new tpt_ModuleField('class',  's', 64,  '',   '',         'tf', ' style="width: 170px;"', '', 'Preview Class', false, false, 'LC'),
			new tpt_ModuleField('overlapping_layer',   'ti', '',   '',   '', 'tf', ' style="width: 100px;"', '0', 'Sets absolute css positioning and z-index', false, false, 'LC'),
			new tpt_ModuleField('bg_layer',   'ti', '',   '',   '', 'tf', ' style="width: 100px;"', '0', 'background layer', false, false, 'LC'),
			new tpt_ModuleField('gClass',  's', 64,  '',   '',         'tf', ' style="width: 170px;"', '', 'Preview Generator Class', false, false, 'LC'),
		);

		//$this->moduleData['id'] = $vars['db']['handler']->getData($vars, $moduleTable, '*', ' (1=1) ORDER BY `order` ASC', 'id', false);

		//$vars['db']['handler']->getData($vars, $moduleTable, '*', '', 'id', false);

		parent::__construct($vars, $name, $moduleClassFile, $moduleClass, $moduleTable, $fields, 'id');
	}

	function userEndData(&$vars) {
		$_temp = array();
		$rArr = $this->moduleData['id'];
		/*
		foreach($rArr as $item) {
			$_temp[$item['param']] = array('id'=>$item['id'], 'param'=>$item['param'], 'class'=>$item['class'], 'html_id'=>$item['html_id'], 'preview_params_ids'=>$item['preview_params_ids']);
		}
		//var_dump($rArr);die();

		$rArr = $_temp;
		//var_dump($rArr);die();
		*/
		return $rArr;
	}

}
