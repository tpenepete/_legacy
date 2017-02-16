<?php

defined('TPT_INIT') or die('access denied');

class tpt_module_CustomProductField extends tpt_Module {
    public $name = array();
    public $pname = array();
    public $add_os = array();
    public $add_ih = array();
    public $add_all = array();
    public $preview_name = array();
    public $preview_params = array();
    public $builder_name = array();
    public $html_trigger = array();

    function __construct(&$vars, $name, $moduleClassFile, $moduleClass, $moduleTable) {
    	$fields = array(
                //db field name|field type|length|options|storage options|control|ctrAttr|default|label|index by|split keys|template
            new tpt_ModuleField('id',    'n', null, 'ai', '',         'sp', '', '', '',           true, false,  'LC', ' `enabled`=1 ORDER BY `id` ASC'),
            new tpt_ModuleField('name',  's', 255,  '',   '',         'tf', ' style="width: 170px;"', '', 'Field Name (Old Standard)', true, false, 'LC', ' `enabled`=1'),
            new tpt_ModuleField('pname',  's', 255,  '',   '',         'tf', ' style="width: 170px;"', '', 'Field Name (Newest Standard)', true, false, 'LC', ' `enabled`=1 ORDER BY `cart_order` ASC'),
            new tpt_ModuleField('pname_old',  's', 255,  '',   '',         'tf', ' style="width: 170px;"', '', 'Field Name (Newest Standard) Old', true, false, 'LC', ' `enabled`=1'),
			new tpt_ModuleField('cart_show',   'ti', '',   '',   '', 'tf', ' style="width: 100px;"', '1', 'Show In Cart', false, false, 'LC'),
			new tpt_ModuleField('cart_order',   'i', '',   '',   '', 'tf', ' style="width: 100px;"', '', 'cart order', true, true, 'LC', ' (1=1) ORDER BY `cart_order`'),
			new tpt_ModuleField('cart_a_display',   'ti', '',   '',   '', 'tf', ' style="width: 100px;"', '0', 'Always Show in Cart', false, false, 'LC'),
			new tpt_ModuleField('cart_subcategory',   's', 255,   '',   '', 'tf', ' style="width: 100px;"', '', 'Cart Subcategory', true, true, 'LC', ' `enabled`=1 AND `cart_subcategory` IS NOT NULL'),
			new tpt_ModuleField('cart_value_wrapper_class',   's', 255,   '',   '', 'tf', ' style="width: 100px;"', '', 'Cart Value Class', false, false, 'LC'),
			new tpt_ModuleField('altlayout_label',  's', 255,  '',   '',         'tf', ' style="width: 170px;"', '', 'Alternative Cart Layout Label', false, false, 'LC'),
			new tpt_ModuleField('setvalue_label',  's', 255,  '',   '',         'tf', ' style="width: 170px;"', '', 'Label for When Value is Set', false, false, 'LC'),
			new tpt_ModuleField('updater',   'ti', '',   '',   '', 'tf', ' style="width: 100px;"', '0', 'Update Product Row Parameter', false, false, 'LC'),
			new tpt_ModuleField('store',   'ti', '',   '',   '', 'tf', ' style="width: 100px;"', '0', 'Store in Database', false, false, 'LC'),
			new tpt_ModuleField('duplicate',   'ti', '',   '',   '', 'tf', ' style="width: 100px;"', '0', 'Duplicate Product Field', false, false, 'LC'),
            new tpt_ModuleField('label',  's', 255,  '',   '',         'tf', ' style="width: 170px;"', '', 'Label String', false, false, 'LC'),
            new tpt_ModuleField('label2',  's', 255,  '',   '',         'tf', ' style="width: 170px;"', '', 'Label String 2', false, false, 'LC'),
			new tpt_ModuleField('design_specific_ih',   'ti', '',   '',   '', 'tf', ' style="width: 100px;"', '0', 'Design Specific IH', false, false, 'LC'),
			new tpt_ModuleField('design_specific_os',   'ti', '',   '',   '', 'tf', ' style="width: 100px;"', '0', 'Design Specific OS', false, false, 'LC'),
            new tpt_ModuleField('preview_name',  's', 255,  '',   '',         'tf', ' style="width: 170px;"', '', 'Preview Generator Varname ($pgconf)', true, false, 'LC'),
            new tpt_ModuleField('builder_name',  's', 255,  '',   '',         'tf', ' style="width: 170px;"', '', 'Builder Input Name', false, false, 'LC'),
            new tpt_ModuleField('builder_id',  's', 255,  '',   '',         'tf', ' style="width: 170px;"', '', 'Builder Input Id', false, false, 'LC'),
            new tpt_ModuleField('getter',  's', 255,  '',   '',         'tf', ' style="width: 170px;"', '', 'amz_customproduct object property path', false, false, 'LC'),
            new tpt_ModuleField('admin_orders_table',  's', 255,  '',   '',         'tf', ' style="width: 170px;"', '', 'admin order storage table', false, false, 'LC'),
            new tpt_ModuleField('admin_orders_tables_getter',  's', 255,  '',   '',         'tf', ' style="width: 170px;"', '', 'order storage tables and fieldnames map', false, false, 'LC'),
            new tpt_ModuleField('post_names',  's', 1024,  '',   '',         'tf', ' style="width: 170px;"', '', 'CS list of possible POST keys translating to this field', false, false, 'LC'),
	    new tpt_ModuleField('data_array',   'ti', '',   '',   '', 'tf', ' style="width: 100px;"', '0', 'Auto Process Data', false, false, 'LC'),
			new tpt_ModuleField('control_type',  's', 8,  '',   '',         'tf', ' style="width: 170px;"', 't', 'Control Type', false, false, 'LC'),
	    new tpt_ModuleField('ffunc',  's', 1024,  '',   '',         'tf', ' style="width: 170px;"', '', 'Data Processing Code', false, false, 'LC'),
			new tpt_ModuleField('text_layout',   'ti', '',   '',   '', 'tf', ' style="width: 100px;"', '0', 'Text Layout', false, false, 'LC'),
			new tpt_ModuleField('inside',   'ti', '',   '',   '', 'tf', ' style="width: 100px;"', '0', 'Inside Band Related', false, false, 'LC'),
			new tpt_ModuleField('font',   'ti', '',   '',   '', 'tf', ' style="width: 100px;"', '0', 'Font', false, false, 'LC'),
			new tpt_ModuleField('text',   'ti', '',   '',   '', 'tf', ' style="width: 100px;"', '0', 'Text', false, false, 'LC'),
			new tpt_ModuleField('clipart',   'ti', '',   '',   '', 'tf', ' style="width: 100px;"', '0', 'Clipart', false, false, 'LC'),
			new tpt_ModuleField('custom_clipart',   'ti', '',   '',   '', 'tf', ' style="width: 100px;"', '0', 'Custom Clipart', false, false, 'LC'),
			new tpt_ModuleField('clipart_text_id',   'i', '',   '',   '', 'tf', ' style="width: 100px;"', '0', 'id of the parent message row', true, true, 'LC'),
			new tpt_ModuleField('orientation',   'i', '',   '',   '', 'tf', ' style="width: 100px;"', '0', 'left/right etc...', false, false, 'LC'),
			new tpt_ModuleField('band_color',   'ti', '',   '',   '', 'tf', ' style="width: 100px;"', '0', 'Band Color', false, false, 'LC'),
			new tpt_ModuleField('message_color',   'ti', '',   '',   '', 'tf', ' style="width: 100px;"', '0', 'Message Color', false, false, 'LC'),
	    new tpt_ModuleField('addon',   'ti', '',   '',   '', 'tf', ' style="width: 100px;"', '0', 'Is Addon?', false, false, 'LC'),
	    new tpt_ModuleField('price_modifier',   'i', '',   '',   '', 'tf', ' style="width: 100px;"', '0', 'Price Modifier Type', false, false, 'LC'),
	    new tpt_ModuleField('trigger_elm_id',  's', 255,  '',   '',         'tf', ' style="width: 170px;"', '', 'id of html trigger source', true, false, 'LC'),
	    new tpt_ModuleField('update_layers_ids',  's', 255,  '',   '',         'tf', ' style="width: 170px;"', '', 'layers which the builder control updates (CS tpt_module_bandpreviewlayer ids)', false, false, 'LC'),
			new tpt_ModuleField('defaultvalue_module_function',   'ti', '',   '',   '', 'tf', ' style="width: 100px;"', '0', 'get default value using a module method', false, false, 'LC'),
			new tpt_ModuleField('defaultvalue_module',  's', 255,  '',   '',         'tf', ' style="width: 170px;"', '', 'get default value method module name', false, false, 'LC'),
			new tpt_ModuleField('defaultvalue',  's', 255,  '',   '',         'tf', ' style="width: 170px;"', '', 'default value', false, false, 'LC'),
			new tpt_ModuleField('validateactivevalue',   'ti', '',   '',   '', 'tf', ' style="width: 100px;"', '0', 'validate active value using a module method', false, false, 'LC'),
			new tpt_ModuleField('validateactivevalue_module',  's', 255,  '',   '',         'tf', ' style="width: 170px;"', '', 'validate active value method module name', false, false, 'LC'),
			new tpt_ModuleField('validateactivevalue_module_function',  's', 255,  '',   '',         'tf', ' style="width: 170px;"', '', 'validate active value method', false, false, 'LC'),
			new tpt_ModuleField('module',  's', 255,  '',   '',         'tf', ' style="width: 170px;"', '', 'associated module', false, false, 'LC'),
			new tpt_ModuleField('cartview_value_module_function',   's', 255,   '',   '', 'tf', ' style="width: 100px;"', '', 'cartview module method', false, false, 'LC'),
			new tpt_ModuleField('preview_use_layer_default_value_when_empty',   'ti', '',   '',   '', 'tf', ' style="width: 100px;"', '0', 'Preview Use Default Layer Value When Empty', false, false, 'LC'),
			new tpt_ModuleField('enabled',   'ti', '',   '',   '', 'tf', ' style="width: 100px;"', '1', 'Enabled', false, false, 'LC'),
        );
	
	$this->name = $vars['db']['handler']->getData($vars, $moduleTable, '*', '', 'name', false);
	$this->pname = $vars['db']['handler']->getData($vars, $moduleTable, '*', '', 'pname', false);
	$this->add_os = $vars['db']['handler']->getData($vars, $moduleTable, '*', ' `addon`=2', 'price_modifier', true);
	$this->add_ih = $vars['db']['handler']->getData($vars, $moduleTable, '*', ' `addon`=3', 'price_modifier', true);
	$this->add_all = $vars['db']['handler']->getData($vars, $moduleTable, '*', ' `addon`=1', 'price_modifier', true);
	$this->preview_name = $vars['db']['handler']->getData($vars, $moduleTable, '*', '', 'preview_name', false);
	$this->builder_name = $vars['db']['handler']->getData($vars, $moduleTable, '*', '', 'builder_name', false);
	$this->preview_params = $vars['db']['handler']->getData($vars, $moduleTable, '*', ' `update_layers_ids`>\'\'', 'pname', false);
	$htrigger = $vars['db']['handler']->getData($vars, $moduleTable, '*', ' `trigger_elm_id`>\'\'', 'trigger_elm_id', false);
	foreach($htrigger as $key=>$value) {
	    if(strstr($key, ',') !== false) {
		$keycomps = explode(',', $key);
		foreach($keycomps as $keycomp) {
		    $this->html_trigger[$keycomp] = $value;
		}
	    } else {
		$this->html_trigger[$key] = $value;
	    }
	}
	
        parent::__construct($vars, $name, $moduleClassFile, $moduleClass, $moduleTable, $fields, 'id');
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
	$cprops = $color_module->getColorProps($vars, $values['color']);
	
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
		/*
        foreach($rArr as $item) {
            $_temp[$item['name']] = array('id'=>$item['id'], 'name'=>$item['name'], 'preview_name'=>$item['preview_name'], 'builder_id'=>$item['builder_id']);
        }
        //var_dump($rArr);die();

        $rArr = $_temp;
        //var_dump($rArr);die();
		*/
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


	function getDefaults(&$vars, $input, $options, $tkey=null) {
		$msg_module = getModule($vars, 'BandMessage');
		$layouts_module = getModule($vars, 'BandLayout');

		//$layout = (!empty($input['band_layout'])?intval($input['band_layout'], 10):1);
		//$layout = $layouts_module->moduleData['id'][$layout];
		$layout = $layouts_module->getSelectedItem($vars, $input, $options);
		$layout = $layouts_module->moduleData['id'][$layout];

		$ritems = array();

		$items = $this->moduleData['pname'];
		$tkeyset = (!empty($tkey) && in_array($tkey, array_keys($items)));

		if($tkeyset) {
			$tkeys = array($tkey=>$tkey);
			$items = array_intersect_key($items, $tkeys);
		}


		foreach ($items as $pname => $field) {
			if ($field['defaultvalue_module_function']) {
				$module = getModule($vars, $field['defaultvalue_module']);
				$ritems[$pname] = $module->getDefaultItem($vars, $input, $options);
			} else if (!is_null($field['defaultvalue'])) {

				if (!empty($field['text'])) {
					$msg = $msg_module->moduleData['pname'][$field['pname']];
					//tpt_dump($layout['back'],false,'V');
					//tpt_dump($layout['line2'],false,'V');
					//tpt_dump($msg['back'],false,'V');
					//tpt_dump($ritems['msg2'],false,'V');
					//tpt_dump($ritems[$pname],false,'V');
					//if(!empty($msg['back']) && empty($layout['back'])) {
					if (!empty($msg['back']) && empty($layout['back'])) {
						$ritems[$pname] = '';
					} else {
						$ritems[$pname] = $field['defaultvalue'];
						//$ritems['msg2'] ='';
					}
				} else {
					$ritems[$pname] = $field['defaultvalue'];
				}

				//$ritems[$pname] = $field['defaultvalue'];
			}
		}

		if(!empty($tkeyset)) {
			return $ritems[$tkey];
		} else {
			return $ritems;
		}
	}
    

    function SB_Control(&$vars, $fieldId, $pgconf=array(), $builder=0, $params=array()) {
	$iFieldId = intval($fieldId, 10);
	switch($iFieldId) {
	    case 3:
		$colors_module = getModule($vars, "BandColor");
		
		$colors_module->BandColor_Section_SB($vars, $pgconf, $builder, $pgBandColorType);
		$control = '';
		
		break;
	    default:
		break;
	}
    }


}
