<?php
defined('TPT_INIT') or die('access denied');

class tpt_module_Builder extends tpt_Module {

    function __construct(&$vars, $name, $moduleClassFile, $moduleClass, $moduleTable) {
        $fields = array(
                //db field name|field type|length|options|storage options|control|ctrAttr|default|label|index by|split keys|template
            new tpt_ModuleField('id',    'n', null, 'ai', '',         'sp', '', '', '',           true, false,  'LC'),
            new tpt_ModuleField('label',  's', 255,  '',   '',         'tf', ' style="width: 170px;"', '', 'Label String', false, false, 'LC'),
            new tpt_ModuleField('breadcrumb',  's', 255,  '',   '',         'tf', ' style="width: 170px;"', '', 'Breadcrumbs Content', true, false, 'LC'),
            new tpt_ModuleField('style',   'i', '',   '',   '', 'tf', ' style="width: 100px;"', '0', 'Builder Style', false, false, 'LC'),
            new tpt_ModuleField('type',  's', 255,  '',   '',         'tf', ' style="width: 170px;"', '', 'Builder Type', false, false, 'LC'),
            new tpt_ModuleField('preview_class',  's', 255,  '',   '',         'tf', ' style="width: 170px;"', '', 'Builder Preview Class', false, false, 'LC'),
	    new tpt_ModuleField('inhouse',   'ti', '',   '',   '', 'tf', ' style="width: 100px;"', '0', 'Inhouse Builder', false, false, 'LC'),
	    new tpt_ModuleField('writable',   'ti', '',   '',   '', 'tf', ' style="width: 100px;"', '0', 'Writable Builder', false, false, 'LC'),
	    new tpt_ModuleField('cl',   'ti', '',   '',   '', 'tf', ' style="width: 100px;"', '0', 'CL Builder', false, false, 'LC'),
	    new tpt_ModuleField('rush_order',   'ti', '',   '',   '', 'tf', ' style="width: 100px;"', '0', 'Rush Order Builder', false, false, 'LC'),
	    new tpt_ModuleField('url_id',   'i', '',   '',   '', 'tf', ' style="width: 100px;"', '0', 'tpt_module_urlrules ID', true, false, 'LC'),
	    new tpt_ModuleField('standard_url',  's', 512,  '',   '',         'tf', ' style="width: 170px;"', '', 'Builder URL', false, false, 'LC'),
	    new tpt_ModuleField('additional_types',  's', 255,  '',   '',         'tf', ' style="width: 170px;"', '', 'Additional Types', false, false, 'LC'),
	    new tpt_ModuleField('additional_styles',  's', 255,  '',   '',         'tf', ' style="width: 170px;"', '', 'Additional Styles', false, false, 'LC'),
        );
	
	
	
        parent::__construct($vars, $name, $moduleClassFile, $moduleClass, $moduleTable, $fields, 'id');
    }
    
    function processInputData(&$vars, &$dataArr, &$input) {
	$values = array();
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
	
	
	//var_dump($this->moduleData['id']);die();
	foreach($this->moduleData['id'] as $field) {
	    if(empty($field) || empty($field['name']) || !is_string($field['name']) || empty($field['data_array'])) {
		continue;
	    }
    
	    $pnames = explode(',', $field['post_names']);
	    //var_dump($pnames);//die();
	    //var_dump($lcomp);//die();
	    $value = null;
	    foreach($pnames as $pname) {
		if(isset($input[$pname])) {
		    $value = $input[$pname];
		}
	    }
	    
	    $values[$field['name']] = $value;
	    //var_dump($dataArr);die();
	}
	
	$bdrow = $data_module->typeStyle[$values['type']][$values['style']];
	$cprops = $color_module->getColorProps($vars, $values['color']);
	
	$price_modifiers = array();
	foreach($this->moduleData['id'] as $field) {
	    if(empty($field) || empty($field['name']) || !is_string($field['name']) || empty($field['data_array'])) {
		continue;
	    }
	    
	    if(($field['addon']==0) || ($field['addon']==1) || (($field['addon']==2) && ($bdrow['pricing_type']==0)) || (($field['addon']==3) && ($bdrow['pricing_type']==1))) {
		$value = $values[$field['name']];
		if(!empty($field['ffunc'])) {
		    eval($field['ffunc']);
		}
		
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
		    $dataArr =& $dataArr[$comp];
		}
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
    
    
    function initBuilderType(&$vars, $builder) {
	$pgAjaxJavascript = 0;
	$builder_types = array();
	$selected_type = 0;
	if(!empty($_POST['band_type'])) {
	    $selected_type = intval($_POST['band_type'], 10);
	    $pgAjaxJavascript = 1;
	} else if(!empty($_GET['band_type'])) {
		$selected_type = intval($_GET['band_type'], 10);
	}
	
	
	$default_type = !empty($builder['writable'])?DEFAULT_WRITABLE_TYPE:DEFAULT_TYPE;
	$default_type = !empty($builder['cl'])?DEFAULT_CL_TYPE:$default_type;
	if(!empty($builder['type']) && (($builder_types = explode(',', $builder['type'])) > 1)) {
	    //tpt_dump('asd', true);
	    $default_type = reset($builder_types);
	}
	
	
	
	$pgType = (!empty($_GET['band_type'])?intval($_GET['band_type'], 10):$default_type);
	if(!empty($selected_type)) {
	    $pgType = $selected_type;
	}
	
	if(!empty($builder['type']) && (($builder_types = explode(',', $builder['type'])) > 1)) {
	} else {
	    $pgType = !empty($builder['type'])?$builder['type']:$pgType;
	}
	
	//tpt_dump($pgType, true);
	return array(
		     'pgType'=>$pgType,
		     'selected_type'=>$selected_type,
		     'default_type'=>$default_type,
		     'pgAjaxJavascript'=>$pgAjaxJavascript,
		     'builder_types'=>$builder_types
		     );
    }
    
    
    function getBuilderUrl($vars, $builder_id) {
	$url = $this->moduleData['id'][$builder_id]['standard_url'];
	return $vars['url']['handler']->wrap($vars, $url);
    }

    
    
    function userEndData(&$vars) {
        $_temp = array();
        $rArr = $this->moduleData['name'];
        foreach($rArr as $item) {
            $_temp[$item['name']] = array('name'=>$item['name'], 'preview_name'=>$item['preview_name'], 'builder_id'=>$item['builder_id']);
        }
        //var_dump($rArr);die();

        $rArr = $_temp;
        //var_dump($rArr);die();
        return $rArr;
    }



	function getBuilder(&$vars) {
		$url_builders = $this->moduleData['url_id'];
		$id_builders = $this->moduleData['id'];

		$builder = array();
		$builder_id = 0;
		$url_id = 0;
		$builder_title_new = '';
		if(empty($_POST['short_builder'])) {
			$url_id = $vars['environment']['page_rule']['id'];
			$builder = $url_builders[$url_id];
			$builder_id = $builder['id'];
		} else {
			$builder_id = intval($_POST['short_builder'], 10);
			$builder = $id_builders[$builder_id];
			$url_id = $builder['url_id'];
		}


		return $builder;
	}


	function add_products_button(&$vars, $buttonClasses='', $validationJSFunc = '') {

		$vars['environment']['continue_shopping_url'] = REQUEST_URL;

		$html = '';

		//$action_url = $vars['url']['handler']->wrap($vars, '/cart_addproduct');

		$onclick = ' this.form.submit();';
		if(!empty($validationJSFunc)) {
			$onclick = 'if('.$validationJSFunc.'()){this.form.submit();}';
		}

		$html = <<< EOT
	<input type="hidden" name="task" value="cart.add_product" />
	<input type="button" value="" onclick="$onclick" class="$buttonClasses" />
EOT;

		return $html;
	}

}
