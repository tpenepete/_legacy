<?php

defined('TPT_INIT') or die('access denied');

class tpt_module_BandMessage extends tpt_Module {
    public $out = array();
    public $ofront = array();
    public $ifront = array();
    public $oback = array();
    public $iback = array();
    public $in = array();
    public $idstr = array();

    
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
            new tpt_ModuleField('id',    'n', null, 'ai', '',         'sp', '', '', '',           false, false,  'LC'),
            new tpt_ModuleField('idstr',  's', 16,  '',   '',         'tf', ' style="width: 170px;"', '0', 'idstr', false, false, 'LC'),
            new tpt_ModuleField('pname',  's', 255,  '',   '',         'tf', ' style="width: 170px;"', '0', 'control name (Newest Standard)', true, false, 'LC'),
            new tpt_ModuleField('line2',  'ti', '',  '',   '',         'tf', ' style="width: 170px;"', '', 'Line 2 Message?', false, false, 'LC'),
            new tpt_ModuleField('back',  'ti', '',  '',   '',         'tf', ' style="width: 170px;"', '', 'Back Message?', false, false, 'LC'),
            new tpt_ModuleField('inside',  'ti', '',  '',   '',         'tf', ' style="width: 170px;"', '', 'Inside Message?', false, false, 'LC'),
            new tpt_ModuleField('preview_id',  's', 32,  '',   '',         'tf', ' style="width: 170px;"', '', 'Preview IMG Id', false, false, 'LC'),
            new tpt_ModuleField('ctr_name',  's', 32,  '',   '',         'tf', ' style="width: 170px;"', '', 'Control Name Attr', false, false, 'LC'),
            new tpt_ModuleField('cont_id',  's', 32,  '',   '',         'tf', ' style="width: 170px;"', '', 'Container Id', false, false, 'LC'),
            new tpt_ModuleField('line2_cont_id',  's', 32,  '',   '',         'tf', ' style="width: 170px;"', '', 'Line2 Container Id', false, false, 'LC'),
	    new tpt_ModuleField('line2_db_id',  'i', '',  '',   '',         'tf', ' style="width: 170px;"', '', 'Line2 DB Id', false, false, 'LC'),
            new tpt_ModuleField('title',  's', 64,  '',   '',         'tf', ' style="width: 170px;"', '', 'Message Title', false, false, 'LC'),
            new tpt_ModuleField('jsvarname_timeout',  's', 32,  '',   '',         'tf', ' style="width: 170px;"', '', 'Preview JS Timeout Varname', false, false, 'LC'),
            new tpt_ModuleField('cfgvarname_default_message',  's', 32,  '',   '',         'tf', ' style="width: 170px;"', '', 'Default Message Config Constant Name', false, false, 'LC'),
            new tpt_ModuleField('cfgvarname_pointsize',  's', 32,  '',   '',         'tf', ' style="width: 170px;"', '', 'Default Message Pointsize Config Constant Name', false, false, 'LC'),
            new tpt_ModuleField('var1',  's', 32,  '',   '',         'tf', ' style="width: 170px;"', '', 'var1', false, false, 'LC'),
            new tpt_ModuleField('var2',  's', 32,  '',   '',         'tf', ' style="width: 170px;"', '', 'var2', false, false, 'LC'),
            new tpt_ModuleField('name',  's', 32,  '',   '',         'tf', ' style="width: 170px;"', '', 'tpt_module_CustomProductField name', true, false, 'LC'),
            //'<div class="tpt_admin_module_section float-left" style="border: 2px solid #FFF;">',
            //'</div>',
            //'<div class="float-left padding-top-20 padding-bottom-20 padding-left-10 padding-right-10" style="background-color: #FFF;"><div class="display-inline-block height-10 width-80" style="background-color: #`HEX`; border: 1px solid #000;"></div></div>',
            //'<div class="float-left" style="background-color: #`HEX`; border: 1px solid #000;"><img src="'.$vars['config']['resourceurl'].'/images/Band-Transperent-Preview.png" class="width-80" /></div>',
            //'<div class="float-left" style="background-color: #`HEX`; border: 1px solid #000;"><img src="'.$vars['config']['resourceurl'].'/images/Transparent-Swirl-Band-Preview.png" class="width-80" /></div>'
        );
	
	
        $this->out = $vars['db']['handler']->getData($vars, $moduleTable, '*', ' `inside`=0 ORDER BY `id` ASC', 'idstr', false);
        $this->in = $vars['db']['handler']->getData($vars, $moduleTable, '*', ' `inside`!=0 ORDER BY `id` ASC', 'idstr', false);
		$this->idstr = $this->out+$this->in;
	
        $this->ofront = $vars['db']['handler']->getData($vars, $moduleTable, '*', ' `inside`=0 AND `back`=0 ORDER BY `id` ASC', 'name', false);
        $this->ifront = $vars['db']['handler']->getData($vars, $moduleTable, '*', ' `inside`=1 AND `back`=0 ORDER BY `id` ASC', 'name', false);
        $this->oback = $vars['db']['handler']->getData($vars, $moduleTable, '*', ' `inside`=0 AND `back`=1 ORDER BY `id` ASC', 'name', false);
        $this->iback = $vars['db']['handler']->getData($vars, $moduleTable, '*', ' `inside`=1 AND `back`=1 ORDER BY `id` ASC', 'name', false);
	
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


	function CartView_Value(&$vars, $section, $input=array(), $options=array(), &$vinput=array()) {

		return '<div class="amz_red overflow-hidden font-size-14 font-weight-bold">' . $input[$section['pname']] . '</div>';
	}

	function SB_Section(&$vars, $section, $input=array(), $options=array(), &$vinput=array()) {
		$types_module = getModule($vars, 'BandType');
		$styles_module = getModule($vars, 'BandStyle');
		$data_module = getModule($vars, 'BandData');
		$cpf_module = getModule($vars, 'CustomProductField');
		$layouts_module = getModule($vars, 'BandLayout');

		//$layout = (!empty($input['band_layout'])?intval($input['band_layout'], 10):1);
		//$layout = $layouts_module->moduleData['id'][$layout];
		$layout = $layouts_module->getSelectedItem($vars, $input, $options);
		$layout = $layouts_module->moduleData['id'][$layout];

		$type = $types_module->getActiveItem($vars, $input, $options);
		$style = $styles_module->getActiveItem($vars, $input, $options);
		//tpt_dump($type);
		//tpt_dump($style);
		$data = $data_module->typeStyle[$type][$style];

		$msg = $this->moduleData['pname'][$section['pname']];
		$msgname = $section['pname'];
		$msgid = $section['id'];

		$label = '';
		$toggle_on = '';
		$toggle_on_visibility = '';
		$toggle_off = '';
		$displayclass = '';
		$disabledclass = '';
		$disabled = '';
		$unlock = '';
		if(isset($input[$msgname.'_unlock'])) {
			$unlock = $input[$msgname.'_unlock'];
		}
		if(isset($input[$msgname])) {
		} else {
			$disabledclass = ' disabled_control';
		}
		if(!empty($msg['back']) && empty($layout['back'])) {
			$disabled = ' disabled="disabled"';
		}
		if(!empty($msg['line2'])) {
			if(isset($input[$msgname])) {
				$toggle_on_visibility = ' visibility-hidden';
			} else {
				$displayclass = ' displaynone1';
				$disabled = ' disabled="disabled"';
			}
			$toggle_on = <<< EOT
<div>
	<a class="plain-link $toggle_on_visibility" id="section_toggle_on$msgid" onclick="toggle_section(this, document.getElementById(this.id.replace(new RegExp(/section_toggle(_on|_off)/), 'section_wrapper')), 1); return false;" href="#">[Add 2nd line of text]</a>
</div>
EOT;
			$toggle_off = <<< EOT
&nbsp;&nbsp;<a class="plain-link amz_red" id="section_toggle_off$msgid" onclick="toggle_section(this, document.getElementById(this.id.replace(new RegExp(/section_toggle(_on|_off)/), 'section_wrapper')), 1); return false;" href="#">[ X ]</a>
EOT;
		} else {
			$slabel = $section['label'];
			$label = <<< EOT
<div class="amz_brown font-size-14 font-weight-bold" style="font-family: Arial;height: 20px;padding-top: 10px;">
$slabel:
</div>
EOT;
		}
		
		$text = (isset($input[$msgname])?htmlspecialchars($input[$msgname]):$cpf_module->getDefaults($vars, $input, $options, $msgname));

		$control = <<< EOT
<div class="">
<input 	id="control_$msgid"
	name=""
	value="$text"
	class="$disabledclass plain-input-field height-26 width-80prc line-height-26 padding-left-2 padding-right-2 padding-top-2 padding-bottom-2 font-size-14"
	type="text"
	style="padding: 0px 10px; border: 1px solid #CCCCCC; border-radius: 8px; background-color: #FFF;"
	title=""
	autocomplete="off"
	oncontextmenu="return false"
	onclick=""
	onfocus="unlock_text_control(this);process_control_input(this);"
	onpropertychange="process_control_input(this);"
	oninput="process_control_input(this);"
	onkeypress="" />$toggle_off
<input type="hidden" id="$msgname" name="$msgname" value="$text" $disabled />
<input type="hidden" id="{$msgname}_unlock" name="{$msgname}_unlock" value="$unlock" />
</div>
EOT;

		$html = <<< EOT
$toggle_on
<div id="section_wrapper$msgid" class="$displayclass">
$label
$control
</div>
EOT;

		return $html;
	}
    
    
    
    function BandMessage_Control(&$vars, $idstr, $pgconf=array()) {
	if(empty($idstr)) {
	    return false;
	}
	$mrow = false;
	if(!($mrow = $this->idstr[$idstr])) {
	    return false;
	}
	if(!empty($mrow['line2'])) {
	    return false;
	}
	
	
	extract($pgconf);
	$types_module = getModule($vars, "BandType");
	$data_module = getModule($vars, "BandData");
	$sizes_module = getModule($vars, "BandSize");
	
	$drow = $data_module->typeStyle[$pgType][$pgStyle];
	if(empty($drow)) {
	    return false;
	}
	
	$const = get_defined_constants();
	
	extract($mrow);
	
	
	$ctr_display = true;
	if(!empty($drow['blank']) || (!empty($back) && (empty($drow['text_back_msg'])))) {
	    $ctr_display = false;
	}
	if(!empty($drow['writable'])) {
	    //tpt_dump($drow['writable_class']);
	    if(in_array($drow['writable_class'], array(1,2,4,5))) {
		if(empty($back)) {
		    $ctr_display = false;
		    $alt_label = '<div class="amz_brown font-size-16 font-weight-bold">Writable Strip (only available in White)</div>';
		}
	    } else {
		if(!empty($back)) {
		    $ctr_display = false;
		    $alt_label = '<div class="amz_brown font-size-16 font-weight-bold">Writable Strip (only available in White)</div>';
		}
	    }
	}
	
	
	$alt_label = '';
	$message = false;
	if(isset($_GET[$ctr_name]) || isset($_POST[$ctr_name])) {
	    if(isset($_GET[$ctr_name])) {
		$message = $_GET[$ctr_name];
	    } else if(isset($_POST[$ctr_name])) {
		$message = $_POST[$ctr_name];
	    }
	    
	    /*
	    if(empty($back) || (!empty($drow['text_back_msg']))) {
		$show = true;
	    }
	    */
	} else {
	    $message = $const[$cfgvarname_default_message];
	}
	
	$lntogglefuncparams = 'this';
	if(!empty($drow['writable'])) {
	    $lntogglefuncparams = 'this, true';
	}
	


	
	$l2_ctr_display = false;
	$l2_show = false;
	$l2_message = false;
	$line2_mrow = $this->idstr[$idstr.'2'];
	if(($drow['text_lines_num'] > 1) && !empty($line2_mrow)) {
	    $l2_ctr_display = true;
	    
	    extract($line2_mrow, EXTR_PREFIX_ALL, 'l2');
	    //$arr = array_keys(get_defined_vars());
	    //$arr = get_defined_constants();
	    //var_dump($arr);
	    //tpt_dump($arr, true);
	    //tpt_dump(get_de(), true);
	    
	    if(!empty($_GET[$l2_ctr_name])) {
		$l2_show = true;
		$l2_message = $_GET[$l2_ctr_name];
	    } else if(!empty($_POST[$l2_ctr_name])) {
		$l2_show = true;
		$l2_message = $_POST[$l2_ctr_name];
	    } else {
		$l2_show = true;
		$l2_message = $const[$l2_cfgvarname_default_message];
	    }
	}
	
	$content = '';
	
	
	if($ctr_display) {
	    $cont_class = '';
	    $tpt_res_url = RESOURCE_URL;
	    $UDMessage = urldecode($message);
	    
	    if($back) {
$content .= <<< EOT
<div class="silver-line-separator clear-both"></div>
EOT;
	    }
	    
$content .= <<< EOT
<div class="clear"></div>

<div id="$cont_id" class="$cont_class">
    <div class="background-position-LC background-repeat-no-repeat" style="/*background-image: url($tpt_res_url/images/input-field-1-left.png);*/">
	<div style="font-family: Arial;height: 20px;padding-top: 10px;" class="amz_brown font-size-14 font-weight-bold">$title:&nbsp;</div>
	$alt_label
	
	<div class="padding-right-60 background-position-RC background-repeat-no-repeat" style="background-image: url($tpt_res_url/images/input-bg-ultra-long-2.png);background-position: left top; cursor: pointer;" title="Update $title Preview">
	    <div class="background-repeat-repeat-x" style="/*background-image: url($tpt_res_url/images/input-field-1-mid.png);*/">
		<input oninput="clearTimeout($jsvarname_timeout);$jsvarname_timeout = setTimeout(function(){generate_layers_previews(this, layersData);}, 500);" onpropertychange="clearTimeout($jsvarname_timeout);$jsvarname_timeout = setTimeout(function(){generate_layers_previews(this, layersData);}, 500);" onfocus="removeClass(this.parentNode.parentNode.parentNode.parentNode, 'invalid_field');activate_text_field(this);generate_layers_previews(this, layersData);" oncontextmenu="return false" autocomplete="off" readonly="readonly" id="$ctr_id" class="overflow-visible padding-0 border-none height-32 line-height-26 padding-top-4 padding-bottom-6 font-size-12 width-282 margin-left-10" type="text" name="$ctr_name" value="$UDMessage" title="$title" style="/* background-color: #FFF;*/ background: transparent none;outline: 0; " />
	    </div>
	</div>
    </div>
EOT;


    
	    if(!empty($line2_mrow) && $l2_show && $ctr_display) {
		$addlineid = $idstr.'2add';
		$rmlineid = $idstr.'2remove';
		$l2_UDMessage = urldecode($l2_message);
$content .= <<< EOT
    <div class="font-size-12 clearFix">
	<div class="float-left" id="$addlineid">
	    <a href="#" onclick="add_text_line($lntogglefuncparams); return false;" class="plain-link">[Add 2nd line of text]</a>
	</div>
	<div class="float-right display-none" id="$rmlineid">
	    <a href="#" onclick="remove_text_line($lntogglefuncparams); return false;" class="amz_red plain-link">[X]</a>
	</div>
    </div>
EOT;


if(!empty($l2_UDMessage)){
    $divClass = '';
} else {
    $divClass = 'display-none';
}
$content .= <<< EOT
    <div id="$line2_cont_id" class="$divClass">
	<div class="background-position-LC background-repeat-no-repeat" style="/*background-image: url($tpt_res_url/images/input-field-1-left.png);*/">
	    <div class="padding-right-60 background-position-RC background-repeat-no-repeat" style="background-image: url($tpt_res_url/images/input-bg-ultra-long-2.png);background-position: left top; cursor: pointer;" title="11Update $l2_title Preview">
		<div class="background-repeat-repeat-x" style="/*background-image: url($tpt_res_url/images/input-field-1-mid.png);*/">
		    <input disabled="disabled" oninput="clearTimeout($l2_jsvarname_timeout);$l2_jsvarname_timeout = setTimeout(function(){generate_layers_previews(this, layersData);}, 500);" onpropertychange="clearTimeout($l2_jsvarname_timeout);$l2_jsvarname_timeout = setTimeout(function(){generate_layers_previews(this, layersData);}, 500);" onfocus="removeClass(this.parentNode.parentNode.parentNode.parentNode, 'invalid_field');activate_text_field(this);generate_layers_previews(this, layersData);" oncontextmenu="return false" autocomplete="off" readonly="readonly" id="$l2_ctr_id" class="overflow-visible padding-0 border-none height-32 line-height-26 padding-top-4 padding-bottom-6 font-size-12 width-282 margin-left-10" type="text" name="$l2_ctr_name" value="$l2_UDMessage" title="$l2_title" style="/* background-color: #FFF;*/ background: transparent none;outline: 0; " />
		</div>
	    </div>
	</div>
    </div>
EOT;
	    }

$content .= <<< EOT
</div>
EOT;
	}

	return $content;
	
    }
    
    function BandMessage_Control_init_JS(&$vars) {
	$js = '';
	foreach($this->moduleData['name'] as $message) {
	    $js .= 'var '.$message['jsvarname_timeout'].';'."\n";
	}
	
	return $js;
    }
    
    



    function BandMessage_Control2(&$vars, $idstr, $pgconf=array()) {
	if(empty($idstr)) {
	    return false;
	}
	$mrow = false;
	if(!($mrow = $this->idstr[$idstr])) {
	    return false;
	}
	if(!empty($mrow['line2'])) {
	    return false;
	}
	
	
	extract($pgconf);
	$types_module = getModule($vars, "BandType");
	$data_module = getModule($vars, "BandData");
	$sizes_module = getModule($vars, "BandSize");
	
	$drow = $data_module->typeStyle[$pgType][$pgStyle];
	if(empty($drow)) {
	    return false;
	}
	
	$const = get_defined_constants();
	
	extract($mrow);
	
	
	$ctr_display = true;
	if(!empty($drow['blank']) || (!empty($back) && (empty($drow['text_back_msg'])))) {
	    $ctr_display = false;
	}
	if(!empty($drow['writable'])) {
	    //tpt_dump($drow['writable_class']);
	    if(in_array($drow['writable_class'], array(1,2,4,5))) {
		if(empty($back)) {
		    $ctr_display = false;
		    $alt_label = '<div class="amz_brown font-size-16 font-weight-bold">Writable Strip (only available in White)</div>';
		}
	    } else {
		if(!empty($back)) {
		    $ctr_display = false;
		    $alt_label = '<div class="amz_brown font-size-16 font-weight-bold">Writable Strip (only available in White)</div>';
		}
	    }
	}
	
	
	$alt_label = '';
	$message = false;
	if(isset($_GET[$ctr_name]) || isset($_POST[$ctr_name])) {
	    if(isset($_GET[$ctr_name])) {
		$message = $_GET[$ctr_name];
	    } else if(isset($_POST[$ctr_name])) {
		$message = $_POST[$ctr_name];
	    }
	    
	    /*
	    if(empty($back) || (!empty($drow['text_back_msg']))) {
		$show = true;
	    }
	    */
	} else {
	    $message = $const[$cfgvarname_default_message];
	}
	
	$lntogglefuncparams = 'this';
	if(!empty($drow['writable'])) {
	    $lntogglefuncparams = 'this, true';
	}
	


	
	$l2_ctr_display = false;
	$l2_show = false;
	$l2_message = false;
	$line2_mrow = $this->idstr[$idstr.'2'];
	if(($drow['text_lines_num'] > 1) && !empty($line2_mrow)) {
	    $l2_ctr_display = true;
	    
	    extract($line2_mrow, EXTR_PREFIX_ALL, 'l2');
	    //$arr = array_keys(get_defined_vars());
	    //$arr = get_defined_constants();
	    //var_dump($arr);
	    //tpt_dump($arr, true);
	    //tpt_dump(get_de(), true);
	    
	    if(!empty($_GET[$l2_ctr_name])) {
		$l2_show = true;
		$l2_message = $_GET[$l2_ctr_name];
	    } else if(!empty($_POST[$l2_ctr_name])) {
		$l2_show = true;
		$l2_message = $_POST[$l2_ctr_name];
	    } else {
		$l2_show = true;
		$l2_message = $const[$l2_cfgvarname_default_message];
	    }
	}
	
	$content = '';
	
	
	if($ctr_display) {
	    $cont_class = '';
	    $tpt_res_url = RESOURCE_URL;
	    $UDMessage = urldecode($message);
	    
	    if($back) {
$content .= <<< EOT
<div class="silver-line-separator clear-both"></div>
EOT;
	    }
	    
$content .= <<< EOT
<div class="clear"></div>

<div id="$cont_id" class="$cont_class">
    <div class="background-position-LC background-repeat-no-repeat" style="/*background-image: url($tpt_res_url/images/input-field-1-left.png);*/">
	<div style="font-family: Arial;height: 20px;padding-top: 10px;" class="amz_brown font-size-14 font-weight-bold">$title:&nbsp;</div>
	$alt_label
	
	<div class="padding-right-60 background-position-RC background-repeat-no-repeat" style="background-image: url($tpt_res_url/images/input-bg-ultra-long-2.png);background-position: left top; cursor: pointer;" onclick="tpt_pg_generate_prevew_short('$preview_id');" title="Update $title Preview">
	    <div class="background-repeat-repeat-x" style="/*background-image: url($tpt_res_url/images/input-field-1-mid.png);*/">
		<input oninput="clearTimeout($jsvarname_timeout);$jsvarname_timeout = setTimeout(function(){tpt_pg_generate_prevew_short('$preview_id');}, 500);" onpropertychange="clearTimeout($jsvarname_timeout);$jsvarname_timeout = setTimeout(function(){tpt_pg_generate_prevew_short('$preview_id');}, 500);" onfocus="removeClass(this.parentNode.parentNode.parentNode.parentNode, 'invalid_field');activate_text_field(this);tpt_pg_generate_prevew_short('$preview_id');" oncontextmenu="return false" autocomplete="off" readonly="readonly" id="$ctr_id" class="overflow-visible padding-0 border-none height-32 line-height-26 padding-top-4 padding-bottom-6 font-size-12 width-282 margin-left-10" type="text" name="$ctr_name" value="$UDMessage" title="$title" style="/* background-color: #FFF;*/ background: transparent none;outline: 0; " />
	    </div>
	</div>
    </div>
EOT;


    
	    if(!empty($line2_mrow) && $l2_show && $ctr_display) {
		$addlineid = $idstr.'2add';
		$rmlineid = $idstr.'2remove';
		$l2_UDMessage = urldecode($l2_message);
$content .= <<< EOT
    <div class="font-size-12 clearFix">
	<div class="float-left" id="$addlineid">
	    <a href="#" onclick="add_text_line($lntogglefuncparams); return false;" class="plain-link">[Add 2nd line of text]</a>
	</div>
	<div class="float-right display-none" id="$rmlineid">
	    <a href="#" onclick="remove_text_line($lntogglefuncparams); return false;" class="amz_red plain-link">[X]</a>
	</div>
    </div>
EOT;

if(!empty($l2_UDMessage)){
    $divClass = '';
} else {
    $divClass = 'display-none';
}
$content .= <<< EOT
    <div id="$line2_cont_id" class="$divClass">
	<div class="background-position-LC background-repeat-no-repeat" style="/*background-image: url($tpt_res_url/images/input-field-1-left.png);*/">
	    <div class="padding-right-60 background-position-RC background-repeat-no-repeat" style="background-image: url($tpt_res_url/images/input-bg-ultra-long-2.png);background-position: left top; cursor: pointer;" onclick="tpt_pg_generate_prevew_short('$l2_preview_id');" title="11Update $l2_title Preview">
		<div class="background-repeat-repeat-x" style="/*background-image: url($tpt_res_url/images/input-field-1-mid.png);*/">
		    <input disabled="disabled" oninput="clearTimeout($l2_jsvarname_timeout);$l2_jsvarname_timeout = setTimeout(function(){tpt_pg_generate_prevew_short('$l2_preview_id');}, 500);" onpropertychange="clearTimeout($l2_jsvarname_timeout);$l2_jsvarname_timeout = setTimeout(function(){tpt_pg_generate_prevew_short('$l2_preview_id');}, 500);" onfocus="removeClass(this.parentNode.parentNode.parentNode.parentNode, 'invalid_field');activate_text_field(this);tpt_pg_generate_prevew_short('$l2_preview_id');" oncontextmenu="return false" autocomplete="off" readonly="readonly" id="$l2_ctr_id" class="overflow-visible padding-0 border-none height-32 line-height-26 padding-top-4 padding-bottom-6 font-size-12 width-282 margin-left-10" type="text" name="$l2_ctr_name" value="$l2_UDMessage" title="$l2_title" style="/* background-color: #FFF;*/ background: transparent none;outline: 0; " />
		</div>
	    </div>
	</div>
    </div>
EOT;
	    }

$content .= <<< EOT
</div>
EOT;
	}

	return $content;
	
    }



	function getAdminProductRowMessageControlGroup(&$vars, $pname, $input=array(), $pid='null') {
		$cpf_module = getModule($vars, 'CustomProductField');
		$cpfs = $cpf_module->moduleData['pname'];
		$cpfsctid = $cpf_module->moduleData['clipart_text_id'];
		$messages_module = getModule($vars, 'BandMessage');
		$msgs = $messages_module->moduleData['pname'];
		$clipart_module = getModule($vars, 'BandClipart');
		$data_module = getModule($vars, 'BandData');
		$data = $data_module->typeStyle;

		$cpf = $cpfs[$pname];
		$msg = $msgs[$pname];
		$data = (!empty($data[$input['type']][$input['style']])?$data[$input['type']][$input['style']]:$vars['config']['default_banddata_row']);

		//$value = ${$cpfs[$pname]['preview_name']};

		$displaycls = '';
		if(!empty($msg['line2']) && (!empty($data['text_lines_num']) && ($data['text_lines_num'] == 1))) {
			$displaycls = 'display-none';
		}
		if(!empty($msg['back']) && (empty($data['text_back_msg'])/* && ($data['text_lines_num'] == 1)*/)) {
			$displaycls = 'display-none';
		}
		$value = (isset($input[$pname])?$input[$pname]:'');
		$displaycls2 = '';
		$showctrl = '';
		$hidectrl = '';
		if(!empty($msg['line2'])) {

			if(($value == '')) {
				$displaycls2 = ' display-none';
				$showctrl = '<a onclick="show_message_line2(this); return false;" class="text-decoration-none font-weight-normal font-size-12 white-space-nowrap" href="#">[&nbsp;+&nbsp;]</a>';
				$hidectrl = '<a onclick="hide_message_line2(this); return false;" class="display-none text-decoration-none font-weight-normal font-size-12 white-space-nowrap" href="#">[&nbsp;-&nbsp;]</a>';
			} else {
				$showctrl = '<a onclick="show_message_line2(this); return false;" class="display-none text-decoration-none font-weight-normal font-size-12 white-space-nowrap" href="#">[&nbsp;+&nbsp;]</a>';
				$hidectrl = '<a onclick="hide_message_line2(this); return false;" class="text-decoration-none font-weight-normal font-size-12 white-space-nowrap" href="#">[&nbsp;-&nbsp;]</a>';
			}
		}
		$lalign = ' text-align-center';
		if(!empty($msg['line2'])) {
			if (!empty($msg['back'])) {
				$lalign = ' text-align-left';
			} else {
				$lalign = ' text-align-left';
			}
		}

		$left = 0;
		$right = 0;
		$cliparts = $cpfsctid[$cpfs[$pname]['id']];
		$lclipart = '';
		$rclipart = '';
		foreach($cliparts as $clipart) {
			if(!empty($clipart['clipart'])) {
				if(!empty($clipart['orientation'])) {
					//$right = ${$cpfs[$clipart['pname']]['preview_name']};
					$cclppname = preg_replace('#(i)?clp#', '$1cclp', $clipart['pname']);
					$right = (!empty($input[$clipart['pname']])?$input[$clipart['pname']]:(!empty($input[$cclppname])?!empty($input[$cclppname]):0));
					$ttl = 'Add Clipart';
					$cpname = $clipart['pname'];
					$cdisplay = ' display-none';
					$ccdisplay = '';
					if(!empty($right)) {
						$cdisplay = '';
						$ccdisplay = ' display-none';
						$rclipart = $clipart_module->getFullClipartURL($vars, $right);
						$ttl = $clipart_module->moduleData['id'][$right]['name'];
						$rclipart = '<img style="max-height: 30px;" src="'.$rclipart.'" />';
						//$fcr = $qop['rclipart'];
					}
					$rclipart = <<< EOT
		<a title="$ttl" id="${pid}_${pname}_opengui_$cpname" onclick="openGUI(this); return false;" href="#" class="text-align-center width-100 height-60 line-height-12 display-block amz_grey_btn color-white">
			<span id="${pid}_preview_$cpname" class="display-block height-30 font-size-10">$rclipart</span>
			<span class="display-block height-30 font-size-10">$ttl</span>
		</a>
		<div class="height-14">
			<div class="$ccdisplay">
				<a class="font-size-10" href="#" onclick="openGUI(this); return false;" id="${pid}_addcclp_$cclppname">
					<span>Add custom clipart</span>
					<input type="hidden" value="${pid}_${pname}_$cclppname" />
				</a>
			</div>
			<div class="$cdisplay">
				<a class="font-size-10" href="#" onclick="remove_clipart(this); return false;" id="${pid}_remove_$cpname">
					<span>Remove clipart</span>
					<input type="hidden" value="${pid}_${pname}_opengui_$cpname" />
				</a>
			</div>
		</div>
EOT;
				} else {
					//$left = ${$cpfs[$clipart['pname']]['preview_name']};
					$cclppname = preg_replace('#(i)?clp#', '$1cclp', $clipart['pname']);
					$left = (!empty($input[$clipart['pname']])?$input[$clipart['pname']]:(!empty($input[$cclppname])?!empty($input[$cclppname]):0));
					$ttl = 'Add Clipart';
					$cpname = $clipart['pname'];
					$cdisplay = ' display-none';
					$ccdisplay = '';
					if(!empty($left)) {
						$cdisplay = '';
						$ccdisplay = ' display-none';
						$lclipart = $clipart_module->getFullClipartURL($vars, $left);
						$ttl = $clipart_module->moduleData['id'][$left]['name'];
						$lclipart = '<img style="max-height: 30px;" src="'.$lclipart.'" />';
						//$fcl = $qop['lclipart'];
					}
					$lclipart = <<< EOT
		<a title="$ttl" id="${pid}_${pname}_opengui_$cpname" onclick="openGUI(this); return false;" href="#" class="text-align-center width-100 height-60 line-height-12 display-block amz_grey_btn color-white">
			<span id="${pid}_preview_$cpname" class="display-block height-30 font-size-10">$lclipart</span>
			<span class="display-block height-30 font-size-10">$ttl</span>
		</a>
		<div class="height-14">
			<div class="$ccdisplay">
				<a class=" font-size-10" href="#" onclick="openGUI(this); return false;" id="${pid}_addcclp_$cclppname">
					<span>Add custom clipart</span>
					<input type="hidden" value="${pid}_${pname}_$cclppname" />
				</a>
			</div>
			<div class="$cdisplay">
				<a class=" font-size-10" href="#" onclick="remove_clipart(this); return false;" id="${pid}_remove_$cpname">
					<span>Remove clipart</span>
					<input type="hidden" value="${pid}_${pname}_opengui_$cpname" />
				</a>
			</div>
		</div>
EOT;
				}
			}
		}

		$label = $cpf['label'];
		$c = <<< EOT
<div class="$displaycls">
	<div class="">
		<div class="height-22 line-height-22 font-weight-bold $lalign">
		$label:&nbsp;$showctrl$hidectrl
		</div>
		<div class="$displaycls2 white-space-nowrap clearFix">
			<div style="vertical-align: middle;" class="height-76 float-left">
			$lclipart
			</div>
			<div style="vertical-align: middle;" class="height-76 float-right">
			$rclipart
			</div>
			<div style="vertical-align: middle;" class=" height-76 overflow-hidden padding-left-5 padding-right-5 text-align-center">
					<input autocomplete="off" type="text" id="${pid}_control_$pname" name="" value="$value" class="text-align-center line-height-40 width-100prc height-40" style="box-sizing: border-box; border: 1px solid #888;border-radius: 5px 5px 5px 5px;" onpropertychange="update_product_row_field(this);" oninput="update_product_row_field(this);" />
			</div>
		</div>
	</div>
</div>
EOT;

		return $c;


	}

}

