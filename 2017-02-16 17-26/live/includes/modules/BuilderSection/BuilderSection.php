<?php

defined('TPT_INIT') or die('access denied');

class tpt_module_BuilderSection extends tpt_Module {
	public $name = array();
	public $pname = array();
	public $add_os = array();
	public $add_ih = array();
	public $add_all = array();
	public $preview_name = array();
	public $builder_name = array();
	public $html_trigger = array();

	function __construct(&$vars, $name, $moduleClassFile, $moduleClass, $moduleTable) {
		$fields = array(
			//db field name|field type|length|options|storage options|control|ctrAttr|default|label|index by|split keys|template
			new tpt_ModuleField('id',    'n', null, 'ai', '',         'sp', '', '', '',           true, false,  'LC', ' `enabled`=1 ORDER BY `group` ASC, `order` ASC'),
			new tpt_ModuleField('name',  's', 255,  '',   '',         'tf', ' style="width: 170px;"', '', 'Field Name (Old Standard)', true, false, 'LC', ' `enabled`=1'),
			new tpt_ModuleField('pname',  's', 255,  '',   '',         'tf', ' style="width: 170px;"', '', 'Field Name (Newest Standard)', true, false, 'LC', ' `enabled`=1'),
			new tpt_ModuleField('target_field',   'i', '',   '',   '', 'tf', ' style="width: 100px;"', '', 'tpt_module_customproductfield id', false, false, 'LC'),
			new tpt_ModuleField('label',  's', 255,  '',   '',         'tf', ' style="width: 170px;"', '', 'Label String', false, false, 'LC'),
			new tpt_ModuleField('preview_name',  's', 255,  '',   '',         'tf', ' style="width: 170px;"', '', 'Preview Generator Varname ($pgconf)', true, false, 'LC'),
			new tpt_ModuleField('builder_name',  's', 255,  '',   '',         'tf', ' style="width: 170px;"', '', 'Builder Input Name', false, false, 'LC'),
			new tpt_ModuleField('toggle_section',   'ti', '',   '',   '', 'tf', ' style="width: 100px;"', '0', 'Toggle Section', false, false, 'LC'),
			new tpt_ModuleField('toggle_sections_ids',  's', 255,  '',   '',         'tf', ' style="width: 170px;"', '', 'Toggle Sections Ids', false, false, 'LC'),
			new tpt_ModuleField('toggle_control_wrappers_ids',  's', 255,  '',   '',         'tf', ' style="width: 170px;"', '', 'Toggle Control Wrappers Ids', false, false, 'LC'),
			new tpt_ModuleField('disable_section_parameter',   'ti', '',   '',   '', 'tf', ' style="width: 100px;"', '0', 'disable Section parameter', false, false, 'LC'),
			new tpt_ModuleField('check_zeroindexselected',   'ti', '',   '',   '', 'tf', ' style="width: 100px;"', '0', 'validate select dropdown', false, false, 'LC'),
			new tpt_ModuleField('check_emptystring',   'ti', '',   '',   '', 'tf', ' style="width: 100px;"', '0', 'validate string', false, false, 'LC'),
			new tpt_ModuleField('check_zerovalue',   'ti', '',   '',   '', 'tf', ' style="width: 100px;"', '0', 'validate integer', false, false, 'LC'),
			new tpt_ModuleField('check_invalid_message',  's', 255,  '',   '',         'tf', ' style="width: 170px;"', '', 'invalid value error message', false, false, 'LC'),
			new tpt_ModuleField('control_type',  's', 8,  '',   '',         'tf', ' style="width: 170px;"', 't', 'Control Type', false, false, 'LC'),
			new tpt_ModuleField('subsection',   'ti', '',   '',   '', 'tf', ' style="width: 100px;"', '0', 'Subsection', false, false, 'LC'),
			new tpt_ModuleField('section_label',  's', 255,  '',   '',         'tf', ' style="width: 170px;"', '', 'Section Label', false, false, 'LC'),
			new tpt_ModuleField('self_function',   'ti', '',   '',   '', 'tf', ' style="width: 100px;"', '0', 'this class\' method', false, false, 'LC'),
			new tpt_ModuleField('module function',   'ti', '',   '',   '', 'tf', ' style="width: 100px;"', '0', 'rendered using a module function', false, false, 'LC'),
			new tpt_ModuleField('module',  's', 255,  '',   '',         'tf', ' style="width: 170px;"', '', 'module name', false, false, 'LC'),
			new tpt_ModuleField('function',  's', 1024,  '',   '',         'tf', ' style="width: 170px;"', '', 'function name', false, false, 'LC'),
			new tpt_ModuleField('group',   'i', '',   '',   '', 'tf', ' style="width: 100px;"', '0', 'group', true, true, 'LC', ' `enabled`=1 ORDER BY `group` ASC, `order` ASC'),
			new tpt_ModuleField('order',   'i', '',   '',   '', 'tf', ' style="width: 100px;"', '0', 'order', false, false, 'LC'),
			new tpt_ModuleField('update_layers',  's', 255,  '',   '',         'tf', ' style="width: 170px;"', '', 'CS list of ids from tpt_module_previewlayer', false, false, 'LC'),
			new tpt_ModuleField('html_classes',  's', 255,  '',   '',         'tf', ' style="width: 170px;"', '', 'html classes', false, false, 'LC'),
			new tpt_ModuleField('isset_html_classes',  's', 255,  '',   '',         'tf', ' style="width: 170px;"', '', 'isset_html classes', false, false, 'LC'),
			new tpt_ModuleField('isset_html_classes_related_section_pname',  's', 255,  '',   '',         'tf', ' style="width: 170px;"', '', 'isset_html classes related section name', false, false, 'LC'),
			new tpt_ModuleField('onfocus',  's', 255,  '',   '',         'tf', ' style="width: 170px;"', '', 'html onfocus attribute', true, false, 'LC'),
			new tpt_ModuleField('onblur',  's', 255,  '',   '',         'tf', ' style="width: 170px;"', '', 'html onblur attribute', false, false, 'LC'),
			new tpt_ModuleField('onclick',  's', 255,  '',   '',         'tf', ' style="width: 170px;"', '', 'html onclick attribute', false, false, 'LC'),
			new tpt_ModuleField('onchange',  's', 255,  '',   '',         'tf', ' style="width: 170px;"', '', 'html onchange attribute', false, false, 'LC'),
			new tpt_ModuleField('oninput',  's', 255,  '',   '',         'tf', ' style="width: 170px;"', '', 'html oninput attribute', false, false, 'LC'),
			new tpt_ModuleField('onpropertychange',  's', 255,  '',   '',         'tf', ' style="width: 170px;"', '', 'html onpropertychange attribute', false, false, 'LC'),
			new tpt_ModuleField('onkeypress',  's', 255,  '',   '',         'tf', ' style="width: 170px;"', '', 'html onkeypress attribute', false, false, 'LC'),
			new tpt_ModuleField('onkeyup',  's', 255,  '',   '',         'tf', ' style="width: 170px;"', '', 'html onkeyup attribute', false, false, 'LC'),
			new tpt_ModuleField('onkeydown',  's', 255,  '',   '',         'tf', ' style="width: 170px;"', '', 'html onkeydown attribute', false, false, 'LC'),
			new tpt_ModuleField('enabled',   'ti', '',   '',   '', 'tf', ' style="width: 100px;"', '1', 'Enabled', false, false, 'LC'),
		);

		$this->name = $vars['db']['handler']->getData($vars, $moduleTable, '*', '', 'name', false);
		$this->pname = $vars['db']['handler']->getData($vars, $moduleTable, '*', '', 'pname', false);

		parent::__construct($vars, $name, $moduleClassFile, $moduleClass, $moduleTable, $fields, 'id');
	}

	function Comments_Section_Value(&$vars, $section, $input=array(), $options=array(), &$vinput=array()) {
		$comments = htmlspecialchars(isset($input['design_notes'])?$input['design_notes']:'');

		$savecommenturl = $vars['config']['ajaxurl'] . '/cartupdateproduct_comments2';
		$saveaction = tpt_ajax::getCall('cart.update_comments2');

		$index = !empty($options['index'])?intval($options['index'], 10):'';

		$updatecommentsform = <<< EOT
<form action="$savecommenturl" method="POST">
<textarea name="comments" class="color-black">$comments</textarea>
<br />
<input type="hidden" name="productindex" value="$index" />
<input type="hidden" name="task" value="cart.update_comments2" />
<input type="button" value="Save" class="color-black" onclick="$saveaction;addClass(this.parentNode.parentNode.parentNode.parentNode, 'display-none');" />
<input type="button" value="Cancel" class="color-black" onclick="addClass(this.parentNode.parentNode.parentNode.parentNode, 'display-none');" />
</form>
EOT;


		/*
		$comments_label = 'Add Your Design Ideas/Comments';
		if (!empty($comments)) {
			$comments_label = 'View/Edit Comments';
		}
		*/

		$ccontent = <<< EOT
<div class="">
	<div class="">
	</div>
	<div class="">
		$updatecommentsform
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

		return $ccontent;
	}
	function Comments_Section(&$vars, $section, $input=array(), $options=array(), &$vinput=array()) {
		$dnotes = !empty($input[$section['pname']])?htmlspecialchars($input[$section['pname']]):'';

		$images_url = TPT_IMAGES_URL;
		return <<< EOT
<textarea style="border: 1px solid #ccc;border-radius: 7px;" class="background-white plain-input-field height-120 width-80prc" name="design_notes" cols="10" rows="10">$dnotes</textarea>
EOT;

	}
	function Separator(&$vars, $section, $input=array(), $options=array(), &$vinput=array()) {
		return <<< EOT
EOT;

	}

	function getBuilderSectionsHTML(&$vars, $input, $options=array(), &$vinput=array()) {
		$data_module = getModule($vars, 'BandData');
		$types_module = getModule($vars, 'BandType');
		$styles_module = getModule($vars, 'BandStyle');
		$cpf_module = getModule($vars, 'CustomProductField');
		$cpfsid = $cpf_module->moduleData['id'];
		$sdata = $this->moduleData['group'];

		/*
		$cpfspg = $cpf_module->moduleData['preview_name'];

		//$type = DEFAULT_TYPE;
		//$style = DEFAULT_STYLE;

		$input = array_intersect_key($input, $cpfspg);
		$_input = array();
		foreach($input as $name=>$value) {
			$parname = $cpfspg[$name]['pname'];
			$_input[$parname] = $$parname = $value;
		}
		$input = $_input;
		*/

		//extract($pgconf);

		$type = $types_module->getActiveItem($vars, $input, $options);
		$style = $styles_module->getActiveItem($vars, $input, $options);

		$bdata = $data_module->typeStyle[$type][$style];

		$sections = explode(',', $bdata['builder_sections']);
		//tpt_dump($bdata);
		//tpt_dump($sections);
		$sections = array_combine($sections, $sections);
		$sections = array_intersect_key($this->moduleData['id'], $sections);
		//tpt_dump($type);
		//tpt_dump($style);
		//tpt_dump($sections);
		//tpt_dump($sections);
		//tpt_dump(array_keys($sections));

		$html = array();
		$i=0;
		$closed = 1;
		if($_SERVER['REMOTE_ADDR'] == '120.63.12.2371'){
			echo 'type'.$type;
			echo 'style'.$style.
			var_dump($bdata);die();
		}
		foreach($sdata as $group=>$sdsections) {
			$group = array();
			$group_id = array();
			$group_enabled = 0;
			foreach($sdsections as $section) {
				//tpt_dump($vinput);
				//tpt_dump($section['id']);
				if($section['id'] == 111) {
					//tpt_dump($control, true);
					//tpt_dump('', true);
				}
				
				/*&nbsp;&nbsp;&nbsp;&nbsp;<a class="thickbox text-decoration-none" title="" href="led-guide.php?KeepThis=true&amp;TB_iframe=true&amp;height=300&amp;width=678">
<img onmouseover="Tip('<img src=\'$tpt_imagesurl/led-guide.jpg\' width=\'503\'>')" onmouseout="UnTip()" src="$tpt_imagesurl/what-is-this.png" alt="what's this" style="vertical-align: bottom;" border="0" />
</a>&nbsp;<span class="font-size-14 color-black">What&#39;s this?</span>*/

				if($section['id'] == 112) {
					$tpt_imagesurl = $vars['config']['images_url'];
					$sectionLabel = <<< EOT
&nbsp;&nbsp;&nbsp;&nbsp;<a class="thickbox text-decoration-none" title="" href="led-guide.php?KeepThis=true&amp;TB_iframe=true&amp;height=300&amp;width=100%">
<img onmouseover="Tip('<img src=\'$tpt_imagesurl/LED-bands-guide.png\' width=\'503\'>')" onmouseout="UnTip()" src="$tpt_imagesurl/what-is-this.png" alt="what's this" style="vertical-align: bottom;" border="0" /></a>
&nbsp;<span class="font-size-14 color-black">What&#39;s this?</span>

EOT;
					$section['label'] = $section['label'].$sectionLabel;
				}
				if(isset($sections[$section['id']])) {
					//tpt_dump($section['id']);
					$group_id[] = $section['pname'];
					$group[] = $this->getBuilderSectionHTML($vars, $section, $input, $options, $vinput);

					if(isset($input[$section['pname']])) {
						$group_enabled = 1;
					}
				}
			}
			$fsection = reset($sdsections);
			if(!empty($group)) {
				if(($i==0) || ($i%2==0)) {
					$html[] = '<div class="clearFix tpt_form_sections_row">';
					$closed = 0;
				}
				$group_id = implode('_', $group_id);
				$group = implode("\n", $group);

				$toggle = '';
				$displayclass = '';
				if(!empty($fsection['toggle_section'])) {
					$displayclass = ' display-none';
					$checked = '';
					if(!empty($group_enabled)) {
						$displayclass = '';
						$checked = ' checked="checked"';
					}

					$toggle = <<< EOT
<span class=" amz_brown font-size-14 font-weight-bold padding-top-5 padding-bottom-10">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Yes&nbsp;<input type="checkbox" name="" $checked onclick="toggle_subsection(this);" />
</span>
EOT;
				}
				$label = '';
				if(!empty($fsection['subsection'])) {
					$label = $fsection['section_label'];
					$label = <<< EOT
<div class="todayshop-bold font-size-18 padding-top-5 padding-bottom-10" style="color: #669669;">$label$toggle</div>
EOT;
				}
				$html[] = <<< EOT
<div id="section_$group_id" style="min-width: 50%;/*border-bottom: 1px solid #e2deda;*/" class="float-left text-align-left padding-bottom-20">
	$label
	<div id="subsection_$group_id" class="$displayclass">
	$group
	</div>
</div>
EOT;
				if(($i%2==1)) {
					$html[] = '</div>';
					$closed = 1;
				}
				$i++;
			}
		}

		if(empty($closed)) {
			$html[] = '</div>';
		}

		return implode("\n", $html);
	}

	function getBuilderSectionHTML(&$vars, $section, $input, $options=array(), &$vinput=array()) {
		$data_module = getModule($vars, 'BandData');
		$cpf_module = getModule($vars, 'CustomProductField');
		$msg_module = getModule($vars, 'BandMessage');
		$layouts_module = getModule($vars, 'BandLayout');

		//$layout = (!empty($input['band_layout'])?intval($input['band_layout'], 10):1);
		//$layout = $layouts_module->moduleData['id'][$layout];
		$layout = $layouts_module->getSelectedItem($vars, $input, $options);
		$layout = $layouts_module->moduleData['id'][$layout];

		$sid = $section['id'];
		$label = $section['label'];
		$field = (!empty($cpf_module->moduleData['id'][$section['target_field']])?$cpf_module->moduleData['id'][$section['target_field']]:array());

		$control = '';
		$vinput = array();
		if(!empty($section['module_function'])) {
			$control = call_user_func_array(array(getModule($vars, $section['module']), $section['function']), array($vars, $section, $input, $options, &$vinput));
			if(is_array($control)) {
				$control = $control['content'];
			}
		} else if(!empty($section['self_function'])) {
			$control = call_user_func_array(array($this, $section['function']), array($vars, $section, $input, $options, &$vinput));
			if(is_array($control)) {
				$control = $control['content'];
			}
			//if($section['id'] == 111) {
			//	tpt_dump($control, true);
			//}
		}
		//tpt_dump($vinput);
		//tpt_dump($vinput);

		//die();
		$displayclass = '';
		if(!empty($field['text'])) {
			$msg = $msg_module->moduleData['pname'][$section['pname']];

			if(!empty($msg['back']) && empty($layout['back'])) {
				//tpt_dump($layout);
				//tpt_dump($msg);
				$displayclass = ' display-none';
			}
		} else if(!empty($field['clipart'])) {
			$msg = $msg_module->moduleData['pname'][$cpf_module->moduleData['id'][$field['clipart_text_id']]['pname']];

			if(!empty($msg['back']) && empty($layout['back'])) {
				//tpt_dump($layout);
				//tpt_dump($msg);
				$displayclass = ' display-none';
			}
		}

		$html = '';
		$html_classes = $section['html_classes'];
		//tpt_dump($section['pname']);
		//tpt_dump($section['isset_html_classes']);
		if((isset($input[$section['pname']]) || isset($input[$section['isset_html_classes_related_section_pname']])) &&  !empty($section['isset_html_classes'])) {
			$html_classes = $section['isset_html_classes'];
		}
		if(empty($section['subsection'])) {
			$html .= <<< EOT
<div class="todayshop-bold font-size-18 padding-top-5 padding-bottom-10" style="color: #669669;">$label</div>
EOT;
		}
		$html .= <<< EOT
<div id="control_wrapper_{$sid}" class="$html_classes $displayclass">
$control
</div>
EOT;


		return $html;
	}

	function normalizeOldProductData(&$vars, $order_id=0, $quote_id=0, $index_by=null ) {
		$color_module = getModule($vars, "BandColor");
		$type_module = getModule($vars, "BandType");
		$style_module = getModule($vars, "BandStyle");
		$size_module = getModule($vars, "BandSize");
		$data_module = getModule($vars, "BandData");
		$font_module = getModule($vars, "BandFont");
		$message_module = getModule($vars, "BandMessage");
		$clipart_module = getModule($vars, "BandClipart");
		$cpf_module = $this;
		$wclass_module = getModule($vars, "WritableClass");
		$rushorder_module = getModule($vars, "RushOrder");

		$db = $vars['db']['handler'];

		if(empty($order_id) && empty($quote_id)) {
			return false;
		}

		$query = 'SELECT GROUP_CONCAT(DISTINCT(`admin_orders_table`) SEPARATOR ",") AS `tables` FROM `'.$this->moduleTable.'` WHERE `admin_orders_table`>\'\'';
		$db->query($query);
		$tables = $db->fetch_assoc_list();
		$tables = reset($tables);
		$tables = '`'.implode('`,`', explode(',', $tables['tables'])).'`';


		$query = 'SELECT `'.$index_by.'`, `admin_orders_tables_getter` FROM `'.$this->moduleTable.'` WHERE `admin_orders_tables_getter`>\'\' AND `'.$index_by.'`>\'\' GROUP BY `admin_orders_tables_getter`';
		//tpt_dump($query, true);
		$db->query($query);
		$fields = $db->fetch_assoc_list($index_by, false);
		/*
		$fmap = array();
		$f = array();
		for($i=0, $_len=count($fields); $i<$_len; $i++) {
			if(strstr($fields[$i], '+') !== false) {
			$fields[$i] = explode('+', $fields[$i]);
			foreach($fields[$i] as $ccfld) {
				if(strstr($ccfld, '.') !== false) {
				$field = explode('.', $ccfld);
				}
				$f
			}
			}
		}
		*/
		$ccflds = array();
		foreach($fields as $key=>$field) {
			$ccflds[] = $field['admin_orders_tables_getter'].' AS `'.$key.'`';
		}
		$fields = implode(',', $ccflds);

		/*
		foreach($cpf_module->moduleData['name'] as $cpf) {

		}
		*/

		$qoid = $quote_id;
		if(!empty($order_id)) {
			$query = 'SELECT `id` FROM `temp_custom_orders` WHERE `order_id`='.$order_id.' AND (`quote_id` IS NULL OR `quote_id`=0 OR `quote_id`="")';
			$db->query($query);
			$qoid = $db->fetch_assoc_list();
			$qoid = reset($qoid);
			$qoid = $qoid['id'];
		}

		$query = <<< EOT
SELECT
    $fields
FROM
    `temp_custom_orders`
LEFT JOIN 
    `temp_custom_order_products`
ON
    `temp_custom_orders`.`id`=`temp_custom_order_products`.`order_id`
LEFT JOIN
    `temp_custom_order_extras`
#ON
#    `temp_custom_order_products`.`id`=`temp_custom_order_extras`.`product_id`
WHERE
    (`temp_custom_orders`.`id`=$qoid OR `temp_custom_orders`.`quote_id`=$qoid)
EOT;
		//tpt_dump($query, true);
		$db->query($query);
		$products = $db->fetch_assoc_list();

		return $products;
	}

	function processInputData(&$vars, &$dataArr, &$input) {

		$color_module = getModule($vars, "BandColor");
		$type_module = getModule($vars, "BandType");
		$style_module = getModule($vars, "BandStyle");
		$size_module = getModule($vars, "BandSize");
		$data_module = getModule($vars, "BandData");
		$font_module = getModule($vars, "BandFont");
		$message_module = getModule($vars, "BandMessage");
		$clipart_module = getModule($vars, "BandClipart");
		$cpf_module = $this;
		$wclass_module = getModule($vars, "WritableClass");
		$rushorder_module = getModule($vars, "RushOrder");


		if(empty($input)) {
			return;
		}
		//tpt_dump(array_keys($this->moduleData), true);
		//tpt_dump($this->moduleData['id'], true);

		//var_dump($this->moduleData['id']);die();
		$values = array();
		foreach($this->moduleData['id'] as $field) {
			//tpt_dump($field);
			//tpt_dump($field['name']);
			//tpt_dump($field['data_array'], true);
			if(empty($field) || empty($field['name']) || !is_string($field['name']) || empty($field['data_array'])) {
				continue;
			}

			$pnames = explode(',', $field['post_names']);
			//var_dump($pnames);//die();
			//var_dump($lcomp);//die();
			$value = null;
			//tpt_dump($pnames, true);
			foreach($pnames as $pname) {
				if(isset($input[$pname])) {
					$value = $input[$pname];
				}
			}

			$values[$field['name']] = $value;
			//var_dump($dataArr);die();
		}
		//tpt_dump($this->moduleData, true);
		//tpt_dump($values, true);

		$bdrow = $data_module->typeStyle[$values['type']][$values['style']];
		$cprops = $color_module->getColorProps($tpt_vars, $values['color']);

		$price_modifiers = array();
		foreach($this->moduleData['id'] as $field) {

			if(empty($field) || empty($field['name']) || !is_string($field['name']) || empty($field['data_array'])) {
				continue;
			}

			if(($field['addon']==0) || ($field['addon']==1) || (($field['addon']==2) && ($bdrow['pricing_type']==0)) || (($field['addon']==3) && ($bdrow['pricing_type']==1))) {
				$value = $values[$field['name']];
				//if($field['name'] == 'bmsg') {
				//tpt_dump('asdasdasdasd', true);
				//tpt_dump($values, true);
				//tpt_dump($value);
				//    $value = stripslashes($value);$value = (($values['type'] != 8))?((!empty($values['text_span']) && ($values['text_span'] == 2))?((empty($values['bmsg'])&&empty($values['bmsg2']))?' ':$value):null):null;
				//tpt_dump($value, true);
				//}
				if(!empty($field['ffunc'])) {
					eval($field['ffunc']);
				}
				//if($field['name'] == 'bmsg') {
				//    tpt_dump($value, true);
				//}
				$comps = explode('|', $field['getter']);
				if(count($comps) < 2) {
					continue;
				}
				$lcomp = array_pop($comps);
				if($lcomp[0] == '>') {
					$lcomp = substr($lcomp, 1);
				}
				array_shift($comps);
				$pnt_dataArr =& $dataArr;
				foreach($comps as $comp) {
					if($comp[0] == '>') {
						$comp = substr($comp, 1);
					}
					//tpt_dump($comp);
					$dataArr =& $dataArr[$comp];
				}
				//if(!empty($lcomp))

				//tpt_dump($lcomp);
				$dataArr[$lcomp] = $value;
				$dataArr =& $pnt_dataArr;

				if(($field['addon']!=0) && !empty($field['price_modifier'])) {
					//var_dump($field);die();
					switch($field['price_modifier']) {
						case 10:
							$price_modifiers[$lcomp] = $value;
							break;
						case 13:
							break;
						case 1:
						default:
							$price_modifiers[$lcomp] = $cprops[$lcomp];
					}
				}
			}
		}
		//tpt_dump($dataArr, true);

		$sizes = array();
		foreach($size_module->moduleData['id'] as $size) {
			if(empty($size) || empty($size['name']) || !is_string($size['name']) || empty($size['post_names'])) {
				continue;
			}

			$pnames = explode(',', $size['post_names']);
			//var_dump($pnames);//die();
			//var_dump($lcomp);//die();
			$value = null;
			foreach($pnames as $pname) {
				if(isset($input[$pname])) {
					$value = $input[$pname];
				}
			}

			$sizes[$size['name']] = $value;
		}

		$dataArr =& $pnt_dataArr;
		//tpt_dump($dataArr);
		//tpt_dump($input);
		//tpt_dump($pnt_dataArr, true);

		$dataArr['sizes'] = $sizes;
		$dataArr['price_modifiers'] = $price_modifiers;
		$dataArr['user_input'] = file_get_contents("php://input");
	}

	function getValue(&$vars, &$product, $fldname) {
		if(empty($fldname) || !is_string($fldname))
			return null;

		$fld = $this->name[$fldname];
		if(empty($fld))
			return null;

		$comps = explode('|', $fld['getter']);
		if(count($comps) < 2)
			return null;

		$elm = array_shift($comps);
		$elm = isset($product->{$elm})?$product->{$elm}:null;
		if(empty($elm))
			return null;

		foreach($comps as $comp) {
			if($comp[0] == '>') {
				$cmp = substr($comp, 1);
				if(isset($elm->{$cmp})) {
					$elm = $elm->{$cmp};
				} else {
					return null;
				}
			} else {
				if(isset($elm->{$comp})) {
					$elm = $elm->{$comp};
				} else {
					return null;
				}
			}
		}

		return $elm;
	}



	function userEndData(&$vars) {
		$_temp = array();
		$rArr = $this->moduleData['id'];
		foreach($rArr as $item) {
			$_temp[$item['id']] = array('id'=>$item['id'], 'name'=>$item['pname'], 'update_layers'=>$item['update_layers'], 'toggle_sections_ids'=>$item['toggle_sections_ids'], 'toggle_control_wrappers_ids'=>$item['toggle_control_wrappers_ids']);
		}
		//var_dump($rArr);die();

		$rArr = $_temp;
		//var_dump($rArr);die();
		return $rArr;
	}

	function userEndData_validate0(&$vars) {
		$db = $vars['db']['handler'];
		$_temp = array();
		$rArr = $db->getData($vars, $this->moduleTable, '*', ' `check_zeroindexselected`!=0');
		foreach($rArr as $item) {
			$_temp[$item['id']] = array('id'=>$item['id'], 'name'=>$item['pname'], 'message'=>$item['check_invalid_message']);
		}
		//var_dump($rArr);die();

		$rArr = $_temp;
		//var_dump($rArr);die();
		return $rArr;
	}
	function userEndData_validate1(&$vars) {
		$db = $vars['db']['handler'];
		$_temp = array();
		$rArr = $db->getData($vars, $this->moduleTable, '*', ' `check_emptystring`!=0');
		foreach($rArr as $item) {
			$_temp[$item['id']] = array('id'=>$item['id'], 'name'=>$item['pname'], 'message'=>$item['check_invalid_message']);
		}
		//var_dump($rArr);die();

		$rArr = $_temp;
		//var_dump($rArr);die();
		return $rArr;
	}
	function userEndData_validate2(&$vars) {
		$db = $vars['db']['handler'];
		$_temp = array();
		$rArr = $db->getData($vars, $this->moduleTable, '*', ' `check_zerovalue`!=0');
		foreach($rArr as $item) {
			$_temp[$item['id']] = array('id'=>$item['id'], 'name'=>$item['pname'], 'message'=>$item['check_invalid_message']);
		}
		//var_dump($rArr);die();

		$rArr = $_temp;
		//var_dump($rArr);die();
		return $rArr;
	}


	function userEndData2(&$vars) {
		$_temp = array();
		$rArr = $this->html_trigger;
		foreach($rArr as $key=>$item) {
			$_temp[$key] = array('id'=>$item['id'], 'name'=>$item['name'], 'preview_name'=>$item['preview_name'], 'builder_id'=>$item['builder_id'], 'update_layers_ids'=>$item['update_layers_ids']);
		}
		//var_dump($rArr);die();

		$rArr = $_temp;
		//var_dump($rArr);die();
		return $rArr;
	}

	function userEndData3(&$vars) {
		$_temp = array();
		$rArr = $this->moduleData['pname'];
		foreach($rArr as $item) {
			$_temp[$item['pname']] = array('id'=>$item['id'], 'name'=>$item['name'], 'pname'=>$item['pname'], 'preview_name'=>$item['preview_name'], 'builder_id'=>$item['builder_id']);
		}
		//var_dump($rArr);die();

		$rArr = $_temp;
		//var_dump($rArr);die();
		return $rArr;
	}


	function SB_Control(&$vars, $fieldId, $pgconf=array(), $builder=0, $params=array()) {
		$iFieldId = intval($fieldId, 10);
		switch($iFieldId) {
			case 3:
				$colors_module = getModule($vars, "BandColor");

				$colors_module->BandColor_Section_SB($tpt_vars, $pgconf, $builder, $pgBandColorType);
				$control = '';

				break;
			default:
				break;
		}
	}


}
