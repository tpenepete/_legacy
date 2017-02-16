<?php

defined('TPT_INIT') or die('access denied');

class tpt_module_BandClipart extends tpt_Module {

    function __construct(&$vars, $name, $moduleClassFile, $moduleClass, $moduleTable) {
        //tpt_dump('before BandClipart');
        //tpt_dump(number_format(memory_get_usage()));
        
        $fields = array(
                //db field name|field type|length|options|storage options|control|ctrAttr|default|label|index by|split keys|template
            new tpt_ModuleField('id',    'n', null, 'ai', '',         'sp', '', '', '',           true, false,  'LC'),
            new tpt_ModuleField('name',  's', 255,  '',   '',         'tf', ' style="width: 170px;"', '', 'Clipart Name', false, false, 'LC'),
            new tpt_ModuleField('image',  's', 255,  '',   '',         'tf', ' style="width: 170px;"', '', 'Clipart PNG File', true, false, 'LC'),
            new tpt_ModuleField('svg',  's', 255,  '',   '',         'tf', ' style="width: 170px;"', '', 'Clipart SVG File', false, false, 'LC'),
            new tpt_ModuleField('category',  'i', '',  '',   'intval10',         'tf', ' style="width: 170px;"', '', 'Parent Category', false, false, 'LC'),
            new tpt_ModuleField('status',   'ti', '',    '',   'intval10', 'tf', ' style="width: 70px;"', '', 'Enabled?',        false, false, 'LC'),
            //'<div class="tpt_admin_module_section float-left" style="border: 2px solid #FFF;">',
            //'</div>',
            //'<div class="float-left padding-top-20 padding-bottom-20 padding-left-10 padding-right-10" style="background-color: #FFF;"><div class="display-inline-block height-10 width-80" style="background-color: #`HEX`; border: 1px solid #000;"></div></div>',
            //'<div class="float-left" style="background-color: #`HEX`; border: 1px solid #000;"><img src="'.$vars['config']['resourceurl'].'/images/Band-Transperent-Preview.png" class="width-80" /></div>',
            //'<div class="float-left" style="background-color: #`HEX`; border: 1px solid #000;"><img src="'.$vars['config']['resourceurl'].'/images/Transparent-Swirl-Band-Preview.png" class="width-80" /></div>'
        );
        
        //tpt_dump('after BandClipart');
        //tpt_dump(number_format(memory_get_usage()));

        $this->load_unindexed = true;
        
        parent::__construct($vars, $name, $moduleClassFile, $moduleClass, $moduleTable, $fields, 'id');
    }


	function CartView_Value(&$vars, $section, $input=array(), $options=array(), &$vinput=array()) {
		$clipart = '';

		$clipart_path = $this->getClipartURL($vars, $input[$section['pname']]);
		$clipart_e = array_filter(explode('/', $clipart_path));
		if (!empty($clipart_e)) {
			$clipart_e = array_filter(explode('.', $clipart_e[count($clipart_e) - 1]));
			array_pop($clipart_e);
			$clipart = htmlentities(preg_replace('#[\W_]+#', ' ', implode('.', $clipart_e)));
		}

		return '<div class="amz_red overflow-hidden">' . $clipart . '</div><div class="height-50 text-align-center"><img style="max-width: 100%; max-height: 100%;" src="' . TPT_RESOURCE_URL . '/clipart/' . $clipart_path . '" /></div>';
	}
	function SB_Section(&$vars, $section, $input=array(), $options=array(), &$vinput=array()) {
		$types_module = getModule($vars, 'BandType');
		$styles_module = getModule($vars, 'BandStyle');
		$data_module = getModule($vars, 'BandData');
		$msg_module = getModule($vars, 'BandMessage');
		$cpf_module = getModule($vars, 'CustomProductField');
		$layouts_module = getModule($vars, 'BandLayout');
        $ccat_module = getModule($vars, 'BandClipartCategory');

		//$layout = (!empty($input['band_layout'])?intval($input['band_layout'], 10):1);
		//$layout = $layouts_module->moduleData['id'][$layout];
		$layout = $layouts_module->getSelectedItem($vars, $input, $options);
		$layout = $layouts_module->moduleData['id'][$layout];


		$type = $types_module->getActiveItem($vars, $input, $options);
		$style = $styles_module->getActiveItem($vars, $input, $options);
		//tpt_dump($type);
		//tpt_dump($style);
		$data = $data_module->typeStyle[$type][$style];



		$label = '';
		$slabel = $section['label'];
		$pname = $section['pname'];
		$sid = $section['id'];

		$displayclass0 = '';
		$displayclass1 = ' display-none';
		$disabled = ' disabled="disabled"';
		$value = '';
		$clpname = '';
		if(!empty($input[$pname]) && !empty($this->moduleData['id'][$input[$pname]])) {
			$value = $input[$pname];
			$clpname = htmlspecialchars($this->moduleData['id'][$value]['name']);
			$disabled = '';
			$displayclass0 = ' display-none';
			$displayclass1 = '';
		}

		$msg = $msg_module->moduleData['pname'][$cpf_module->moduleData['id'][$cpf_module->moduleData['id'][$section['target_field']]['clipart_text_id']]['pname']];
		if(!empty($msg['back']) && empty($layout['back'])) {
			//tpt_dump($layout);
			//tpt_dump($msg);
			$displayclass0 = '';
			$displayclass1 = ' display-none';
			$disabled = ' disabled="disabled"';
			$value = '';
			$clpname = '';
		}

		$label = <<< EOT
<div class="amz_brown font-size-14 font-weight-bold" style="font-family: Arial;height: 20px;padding-top: 10px;">
$slabel:
</div>
EOT;



		$control = <<< EOT
<div class="">
	<div id="wrap_trgr_{$sid}" class="$displayclass0">
		<span id="trgr_{$sid}" class="clip_select_trigger" onclick="openGUI(this);" title="Select $slabel...">Select $slabel...</span>
	</div>
	<div id="wrap_trgr2_{$sid}" class="$displayclass1">
		<span id="trgr2_{$sid}" class="clip_select_trigger" onclick="openGUI(this);" title="Selected Clipart: $clpname">$clpname</span><a href="javascript:void(0);" id="clear_{$sid}" class="amz_red text-decoration-none" onclick="clear_clipart(this, $sid, '$pname')" title="">X</a>
	</div>
</div>
<input type="hidden" id="$pname" name="$pname" value="$value" $disabled />
EOT;

        $preloadData = $ccat_module->BandClipart_Panel3($vars, $sid, 0);

		$html = <<< EOT
<div class="">
$label
$control
<div id="preload_$sid" class="display-none" >$preloadData</div>
</div>
EOT;

		return $html;
	}


	function getConvertCustomArtImageUrl(&$vars, $image, $x='100', $y='100', $format='png') {
		return BASE_URL.'/generate-preview?pg_x='.$x.'&pg_y='.$y.'&type=convertcustomart&image='.$image.'&timestamp='.time();
	}

    function getClipartPath(&$vars, $id, $path='') {
		//tpt_dump($id);

        if(isset($this->moduleData['id'][$id])) {
            $clipart = $this->moduleData['id'][$id];
        } else {
            return false;
        }

        //var_dump($id);
        //var_dump($clipart);
        //die();
        $ccat = getModule($vars, "BandClipartCategory")->moduleData['id'][$clipart['category']];
        $pcat = $ccat;
        while($pcat['parent_id'] != 0) {
            $pcat = getModule($vars, "BandClipartCategory")->moduleData['id'][$pcat['parent_id']];
        }
        //var_dump($clipart);//die();
        //var_dump($clipart);//die();
        //var_dump($ccat);
        //var_dump($pcat);
        //die();
		//tpt_dump(CLIPARTS_PATH);
		$path = !empty($path)?$path:CLIPARTS_PATH;
        $path = $path.DIRECTORY_SEPARATOR.$pcat['folder'].DIRECTORY_SEPARATOR.'regular'.DIRECTORY_SEPARATOR.'SVG'.DIRECTORY_SEPARATOR.$clipart['svg'];
		//tpt_dump($path, true);

        return $path;
    }


	function getCustomClipartPath(&$vars, $id) {
		//tpt_dump($id);
		/*
		$clipart = $this->moduleData['id'][$id];

		//var_dump($id);
		//var_dump($clipart);
		//die();
		$ccat = getModule($vars, "BandClipartCategory")->moduleData['id'][$clipart['category']];
		$pcat = $ccat;
		while($pcat['parent_id'] != 0) {
			$pcat = getModule($vars, "BandClipartCategory")->moduleData['id'][$pcat['parent_id']];
		}
		//var_dump($clipart);//die();
		//var_dump($clipart);//die();
		//var_dump($ccat);
		//var_dump($pcat);
		//die();
		*/
		//tpt_dump(CUSTOM_CLIPART_PATH.DIRECTORY_SEPARATOR.$id, true);

		$path = '';
		if(is_file(CUSTOM_CLIPART_PATH.DIRECTORY_SEPARATOR.$id)) {
			$path = CUSTOM_CLIPART_PATH.DIRECTORY_SEPARATOR.$id;
		}

		//tpt_dump($path, true);

		return $path;
	}

    function getClipartImage(&$vars, $id) {
        $clipart = $this->moduleData['id'][$id];

        //$image = $vars['db']['handler']->getData($vars, 'tpt_module_bandclipart', 'image', ' id = "'.$id.'" ', '', true);

        return $clipart['image'];
    }

	function getClipartName(&$vars, $id) {
		if(empty($id)) {
			return '';
		}
		$clipart = (isset($this->moduleData['id'][$id]['name'])?$this->moduleData['id'][$id]['name']:'');

		//$image = $vars['db']['handler']->getData($vars, 'tpt_module_bandclipart', 'image', ' id = "'.$id.'" ', '', true);

		return $clipart;
	}



    function getFullClipartURL(&$vars, $id, $type='png') {
		//tpt_dump('asd', true);
		return CLIPARTS_URL.'/'.$this->getClipartURL($vars, $id, $type);
	}
    function getClipartURL(&$vars, $id, $type='png') {
		$ccat_module = getModule($vars, 'BandClipartCategory');

        if(!isset($this->moduleData['id'][$id])) {
			return false;
        }
		$clipart = $this->moduleData['id'][$id];

        //var_dump($id);
        //var_dump($clipart);
        //die();
		if(isset($ccat_module->moduleData['id'][$clipart['category']])) {
			$ccat = $ccat_module->moduleData['id'][$clipart['category']];


			//	if(!empty($_SESSION['ADMIN_TESTER'])) {
			//		echo '<pre>';
			//		var_dump('ccat',$ccat,$clipart['category']);
			//		echo '</pre>';
			//	}

			$pcat = $ccat;
			while ($pcat['parent_id'] != 0) {
				$pcat = getModule($vars, "BandClipartCategory")->moduleData['id'][$pcat['parent_id']];
			}
			//var_dump($clipart);//die();
			//var_dump($clipart);//die();
			//var_dump($ccat);
			//var_dump($pcat);
			//die();

			$url = rawurlencode($pcat['folder']) . '/regular/' . rawurlencode(trim($clipart['image']));
			if ($type == 'svg') {
				$url = rawurlencode($pcat['folder']) . '/regular/SVG/' . rawurlencode(trim($clipart['svg']));
			}
			if ($type == '1inch') {
				$url = rawurlencode($pcat['folder']) . '/1inch/' . rawurlencode(trim($clipart['image']));
			}

			return $url;
		} else {
			return false;
		}
    }

    function Clipart_Select_Dummy(&$vars, $selectedClipart, $name) {
        return '<select name="'.$name.'"></select>';
    }

	function Clipart_Select2(&$vars, $selectedClipart, $type) {
		$items = $this->moduleData['id'];

		$name = '';
		$title = '';
		$onchange = '';
		$id = '';
		$text = '';
		switch(strtolower($type)) {
			case 'fl' :
				$name = 'lclipart';
				$text = 'Front Left ClipArt: ';
				$title = 'Select Front Left Clipart...';
				$onchange = 'change_clipart(this, \'fl\');';
				$id = 'tpt_pg_front_lclipart_ctr';
				break;
			case 'fr' :
				$name = 'rclipart';
				$text = 'Front Right ClipArt: ';
				$title = 'Select Front Right Clipart...';
				$onchange = 'change_clipart(this, \'fr\');';
				$id = 'tpt_pg_front_rclipart_ctr';
				break;
			case 'fl2' :
				$name = 'lclipart2';
				$text = 'Front Left Ln2 ClipArt: ';
				$title = 'Select Front Left Ln2 Clipart...';
				$onchange = 'change_clipart(this, \'fl2\');';
				$id = 'tpt_pg_front2_lclipart_ctr';
				break;
			case 'fr2' :
				$name = 'rclipart2';
				$text = 'Front Right Ln2 ClipArt: ';
				$title = 'Select Front Right Ln2 Clipart...';
				$onchange = 'change_clipart(this, \'fr2\');';
				$id = 'tpt_pg_front2_rclipart_ctr';
				break;
			case 'bl' :
				$name = 'blclipart';
				$text = 'Back Left ClipArt: ';
				$title = 'Select Back Left Clipart...';
				$onchange = 'change_clipart(this, \'bl\');';
				$id = 'tpt_pg_back_lclipart_ctr';
				break;
			case 'br' :
				$name = 'brclipart';
				$text = 'Back Right ClipArt: ';
				$title = 'Select Back Right Clipart...';
				$onchange = 'change_clipart(this, \'br\');';
				$id = 'tpt_pg_back_rclipart_ctr';
				break;
			case 'bl2' :
				$name = 'blclipart2';
				$text = 'Back Left Ln2 ClipArt: ';
				$title = 'Select Back Left Ln2 Clipart...';
				$onchange = 'change_clipart(this, \'bl2\');';
				$id = 'tpt_pg_back2_lclipart_ctr';
				break;
			case 'br2' :
				$name = 'brclipart2';
				$text = 'Back Right Ln2 ClipArt: ';
				$title = 'Select Back Right Ln2 Clipart...';
				$onchange = 'change_clipart(this, \'br2\');';
				$id = 'tpt_pg_back2_rclipart_ctr';
				break;
		}

		$values = array();
		//var_dump($stvals);die();

		$sClipart = 0;
		$values[] = array(0, $title);
		foreach($items as $key=>$item) {
			$ccat = 0;
			if(!empty(getModule($vars, "BandClipartCategory")->moduleData['id'][$item['category']])) {
				$ccat = getModule($vars, "BandClipartCategory")->moduleData['id'][$item['category']];
			}
			if(empty($ccat)) {
				continue;
			}
			$catname = implode(' ', array_map('ucfirst', explode(' ', $ccat['category_name'])));
			//$catkey = base64_encode($catname);
			$catkey = $catname;
			if(empty($values[$catkey]) || !is_array($values[$catkey]))
				$values[$catkey] = array();
			$values[$catkey][] = array($item['id'], $item['name']);
		}

		//var_dump(tpt_html::createSelect($vars, $name, $values, $sClipart, ' title="'.$title.'"'));die();
		//return '<div style="font-family: Arial;" class="amz_brown font-size-14 font-weight-bold">'.$text.'</div><span class="clip_select_trigger" onclick="try{click_artwrk_sel(this,\''.$id.'\');}catch(e){};"></span>'.tpt_html::createSelect($vars, $name, $values, $sClipart, 'title="'.$title.'" id="'.$id.'" onchange="'.$onchange.'"').'<a id="remove_clipart_'.$id.'" href="javascript:;" class="remove_clipart display-none" onclick="$(\'#'.$id.'\').val(0);$(\'#'.$id.'\')[0].onchange();'." addClass(document.getElementById('remove_clipart_".$id."'), 'display-none');".'">[X]</a><br />';
		return tpt_html::createSelect($vars, $name, $values, $sClipart, 'title="'.$title.'" id="'.$id.'" onchange="'.$onchange.'"');
	}

    function Clipart_Select(&$vars, $selectedClipart, $type) {
        $items = $this->moduleData['id'];

        $name = '';
        $title = '';
        $onchange = '';
        $id = '';
        $text = '';
        switch(strtolower($type)) {
            case 'fl' :
                $name = 'lclipart';
                $text = 'Front Left ClipArt: ';
                $title = 'Select Front Left Clipart...';
                $onchange = 'change_clipart(this, \'fl\');';
                $id = 'tpt_pg_front_lclipart_ctr';
                break;
            case 'fr' :
                $name = 'rclipart';
                $text = 'Front Right ClipArt: ';
                $title = 'Select Front Right Clipart...';
                $onchange = 'change_clipart(this, \'fr\');';
                $id = 'tpt_pg_front_rclipart_ctr';
                break;
            case 'fl2' :
                $name = 'lclipart2';
                $text = 'Front Left Ln2 ClipArt: ';
                $title = 'Select Front Left Ln2 Clipart...';
                $onchange = 'change_clipart(this, \'fl2\');';
                $id = 'tpt_pg_front2_lclipart_ctr';
                break;
            case 'fr2' :
                $name = 'rclipart2';
                $text = 'Front Right Ln2 ClipArt: ';
                $title = 'Select Front Right Ln2 Clipart...';
                $onchange = 'change_clipart(this, \'fr2\');';
                $id = 'tpt_pg_front2_rclipart_ctr';
                break;
            case 'bl' :
                $name = 'blclipart';
                $text = 'Back Left ClipArt: ';
                $title = 'Select Back Left Clipart...';
                $onchange = 'change_clipart(this, \'bl\');';
                $id = 'tpt_pg_back_lclipart_ctr';
                break;
            case 'br' :
                $name = 'brclipart';
                $text = 'Back Right ClipArt: ';
                $title = 'Select Back Right Clipart...';
                $onchange = 'change_clipart(this, \'br\');';
                $id = 'tpt_pg_back_rclipart_ctr';
                break;
            case 'bl2' :
                $name = 'blclipart2';
                $text = 'Back Left Ln2 ClipArt: ';
                $title = 'Select Back Left Ln2 Clipart...';
                $onchange = 'change_clipart(this, \'bl2\');';
                $id = 'tpt_pg_back2_lclipart_ctr';
                break;
            case 'br2' :
                $name = 'brclipart2';
                $text = 'Back Right Ln2 ClipArt: ';
                $title = 'Select Back Right Ln2 Clipart...';
                $onchange = 'change_clipart(this, \'br2\');';
                $id = 'tpt_pg_back2_rclipart_ctr';
                break;
        }

        $values = array();
        //var_dump($stvals);die();

        $sClipart = 0;
        $values[] = array(0, $title);
        foreach($items as $key=>$item) {
            $ccat = 0;
            if(!empty(getModule($vars, "BandClipartCategory")->moduleData['id'][$item['category']])) {
                $ccat = getModule($vars, "BandClipartCategory")->moduleData['id'][$item['category']];
            }
            if(empty($ccat)) {
                continue;
            }
            $catname = implode(' ', array_map('ucfirst', explode(' ', $ccat['category_name'])));
            //$catkey = base64_encode($catname);
            $catkey = $catname;
            if(empty($values[$catkey]) || !is_array($values[$catkey]))
                $values[$catkey] = array();
            $values[$catkey][] = array($item['id'], $item['name']);
        }

        //var_dump(tpt_html::createSelect($vars, $name, $values, $sClipart, ' title="'.$title.'"'));die();
        return '<div style="font-family: Arial;" class="amz_brown font-size-14 font-weight-bold">'.$text.'</div><span class="clip_select_trigger" onclick="try{click_artwrk_sel(this,\''.$id.'\');}catch(e){};"></span>'.tpt_html::createSelect($vars, $name, $values, $sClipart, 'title="'.$title.'" id="'.$id.'" onchange="'.$onchange.'"').'<a id="remove_clipart_'.$id.'" href="javascript:;" class="remove_clipart display-none" onclick="$(\'#'.$id.'\').val(0);$(\'#'.$id.'\')[0].onchange();'." addClass(document.getElementById('remove_clipart_".$id."'), 'display-none');".'">[X]</a><br />';
    }

    /*
    function BandClipart_Panel(&$vars) {
        var_dump($this);die();

        $query = 'SELECT `id`, `name` FROM `'.$this->moduleTable.'`';
        $vars['db']['handler']->query($query, __FILE__);
        $items = $this->moduleData['unindexed'];

        $html = '';
        $values = array();

        $i=1;
        foreach($items as $item) {
            if($i==1) {
                $values[] = array(2, '<div class="height-15 padding-left-2 padding-right-2 line-height-15 white-space-nowrap" style="border: 1px solid #555;background-color: #FFF;">Choose band type...</div>', 'Choose band type...');
                $i=0;
            }
            $values[] = array($item['id'], '<div class="height-15 padding-left-2 padding-right-2 line-height-15 white-space-nowrap" style="border: 1px solid #555;background-color: #FFF;">'.$item['name'].'</div>', $item['name']);
        }

        $valuesDelimiter = "\n";

        $html = tpt_html::createStyledSelect($vars, 'BandType', $values, $valuesDelimiter, ' display-block', '', ' width:180px;', ' padding-top-10', 0, '_debossed_tpt_pg_generate_prevew_all', 'tpt_pg_type');

        return $html;
    }
    */

    function BandClipart_Panel_SB(&$vars, $catid) {
        //$catid = 19;
        //var_dump($this);die();
        if(empty($catid))
            return '';

        $tpt_clipartsurl = CLIPARTS_URL;

        $items = $vars['db']['handler']->getData($vars, $this->moduleTable, '*', '`category`='.$catid.' ORDER BY `name` ASC', 'id', false);
        //var_dump($items);

        $html = '';
        $values = array();

        //<img onmouseout="UnTip();" onmouseover="Tip('&lt;img src=\'http://www.amazingwristbands.com/clipart/awareness/regular/SVG/Awareness_Ribbon_003.svg\' width=\'400\' height=\'400\'&gt;');">

        //$i=1;
        foreach($items as $item) {
            $values[] = '<div onmouseover="Tip(\'<div class=&quot;cliptip tipimig'.(preg_match('/MSIE (7|8)/', $_SERVER['HTTP_USER_AGENT'])?'_iecrab':'').'&quot;><img src=&quot;'.$tpt_clipartsurl.'/'.$this->getClipartURL($vars, $item['id'],preg_match('/MSIE (7|8)/', $_SERVER['HTTP_USER_AGENT'])?'1inch':'svg').'&quot; '.(preg_match('/MSIE (7|8)/', $_SERVER['HTTP_USER_AGENT'])?'width=&quot;100&quot;':'width=&quot;400&quot;').' ></div>\');" onmouseout="UnTip();" class="clip_outer" onclick="select_clipart(this,'.$item['id'].')"><div class="clip_inner" style="background-image: url('.$tpt_clipartsurl.'/'.$this->getClipartURL($vars, $item['id']).')"></div><div class="text-align-center">'.$item['name'].'</div></div>';
        }

        $valuesDelimiter = "\n";

        return implode($valuesDelimiter, $values);
    }

    function BandClipart_Panel(&$vars, $catid) {
        //$catid = 19;
        //var_dump($this);die();
        if(empty($catid))
            return '';

        $tpt_clipartsurl = CLIPARTS_URL;
        /*
        $query = 'SELECT `id`, `name` FROM `'.$this->moduleTable.'`';
        $vars['db']['handler']->query($query, __FILE__);
        $items = $this->moduleData['unindexed'];
        */
        $items = $vars['db']['handler']->getData($vars, $this->moduleTable, '*', '`category`='.$catid, 'id', false);
        //var_dump($items);

        $html = '';
        $values = array();

        //$i=1;
        foreach($items as $item) {
            $values[] = '<div class="padding-left-2 padding-right-2 padding-top-2 padding-bottom-2 float-left" style="border: 1px solid #555;background-color: #FFF;"><div class="width-80 height-80 background-position-CC background-repeat-no-repeat" style="background-image: url('.$tpt_clipartsurl.'/'.$this->getClipartURL($vars, $item['id']).')"></div><div>'.$item['name'].'</div></div>';
        }

        $valuesDelimiter = "\n";

        //$html = tpt_html::createStyledSelect($vars, 'BandType', $values, $valuesDelimiter, ' display-block', '', ' width:180px;', ' padding-top-10', 0, '_debossed_tpt_pg_generate_prevew_all', 'tpt_pg_type');

        return implode($valuesDelimiter, $values);
    }

	function BandClipart_Panel2(&$vars, $sClip, $id) {
		//$catid = 19;
		//var_dump($this);die();
		if(empty($catid))
			return '';

		$tpt_clipartsurl = CLIPARTS_URL;

		$items = $vars['db']['handler']->getData($vars, $this->moduleTable, '*', '`category`='.$catid.' ORDER BY `name` ASC', 'id', false);
		//var_dump($items);

		$html = '';
		$values = array();

		//<img onmouseout="UnTip();" onmouseover="Tip('&lt;img src=\'http://www.amazingwristbands.com/clipart/awareness/regular/SVG/Awareness_Ribbon_003.svg\' width=\'400\' height=\'400\'&gt;');">

		//$i=1;
		foreach($items as $item) {
			$values[] = '<div onmouseover="Tip(\'<div class=&quot;cliptip tipimig'.(preg_match('/MSIE (7|8)/', $_SERVER['HTTP_USER_AGENT'])?'_iecrab':'').'&quot;><img src=&quot;'.$tpt_clipartsurl.'/'.$this->getClipartURL($vars, $item['id'],preg_match('/MSIE (7|8)/', $_SERVER['HTTP_USER_AGENT'])?'1inch':'svg').'&quot; '.(preg_match('/MSIE (7|8)/', $_SERVER['HTTP_USER_AGENT'])?'width=&quot;100&quot;':'width=&quot;400&quot;').' ></div>\');" onmouseout="UnTip();" class="clip_outer" onclick="select_clipart(this,'.$item['id'].')"><div class="clip_inner" style="background-image: url('.$tpt_clipartsurl.'/'.$this->getClipartURL($vars, $item['id']).')"></div><div class="text-align-center">'.$item['name'].'</div></div>';
		}

		$valuesDelimiter = "\n";

		return implode($valuesDelimiter, $values);
	}



	function BandClipart_Panel3(&$vars, $pname, $catid, $pid, $sid='') {
		//$catid = 19;
		//var_dump($this);die();
		if(empty($catid))
			return '';

		$tpt_clipartsurl = CLIPARTS_URL;
		/*
		$query = 'SELECT `id`, `name` FROM `'.$this->moduleTable.'`';
		$vars['db']['handler']->query($query, __FILE__);
		$items = $this->moduleData['unindexed'];
		*/
		$items = $vars['db']['handler']->getData($vars, $this->moduleTable, '*', '`category`='.$catid, 'id', false);
		//var_dump($items);

		$html = '';
		$values = array();

		//$i=1;
		foreach($items as $item) {
			$id = $item['id'];
			$name = $item['name'];
			$url = $this->getFullClipartURL($vars, $item['id']);
			$values[] = <<< EOT
<a href="javascript:void(0);" id="${pid}_${id}_control_$pname" onclick="update_product_row_field(this);update_clipart_control_preview(this, '$sid');close_overlay_container();" class="display-block padding-left-2 padding-right-2 padding-top-2 padding-bottom-2 float-left" style="border: 1px solid #555;background-color: #FFF;">
	<span class="display-block width-80 height-80 background-position-CC background-repeat-no-repeat" style="background-image: url($url)"></span>
	<span class="clipart-label display-block">$name</span>
</a>
EOT;
		}

		$valuesDelimiter = "\n";

		//$html = tpt_html::createStyledSelect($vars, 'BandType', $values, $valuesDelimiter, ' display-block', '', ' width:180px;', ' padding-top-10', 0, '_debossed_tpt_pg_generate_prevew_all', 'tpt_pg_type');

		return implode($valuesDelimiter, $values);
	}

	function BandClipart_Panel4(&$vars, $sid, $catid) {
		$sections_mod = getModule($vars, 'BuilderSection');
		$sections = $sections_mod->moduleData['id'];
		$section = $sections[$sid];
		$pname = $section['pname'];

		//$catid = 19;
		//var_dump($this);die();
		if(empty($catid))
			return '';

		$tpt_clipartsurl = CLIPARTS_URL;
		/*
		$query = 'SELECT `id`, `name` FROM `'.$this->moduleTable.'`';
		$vars['db']['handler']->query($query, __FILE__);
		$items = $this->moduleData['unindexed'];
		*/
		$items = $vars['db']['handler']->getData($vars, $this->moduleTable, '*', '`category`='.$catid, 'id', false);
		//var_dump($items);

		$html = '';
		$values = array();

		//$i=1;
		foreach($items as $item) {
			$id = $item['id'];
			$name = $item['name'];
			$url = $this->getFullClipartURL($vars, $item['id']);
			$values[] = <<< EOT
<a href="javascript:void(0);" id="clp_$id" onclick="select_clipart(this, $sid, '$pname');" class="bci-outer display-block ci_wrapper padding-left-2 padding-right-2 padding-top-2 padding-bottom-2 float-left" style="border: 1px solid #555;background-color: #FFF;">
	<span class="display-block width-80 height-80 background-position-CC background-repeat-no-repeat" style="background-image: url($url)"></span>
	<span class="clipart-label display-block">$name</span>
</a>
EOT;
		}

		$valuesDelimiter = "\n";

		//$html = tpt_html::createStyledSelect($vars, 'BandType', $values, $valuesDelimiter, ' display-block', '', ' width:180px;', ' padding-top-10', 0, '_debossed_tpt_pg_generate_prevew_all', 'tpt_pg_type');

		return implode($valuesDelimiter, $values);
	}



    function getClipartIdFromField(&$vars, $value, $field='`image`') {
        //var_dump($value);die();
        if($field == '`image`') {
            $value = explode('/', $value);
            $value = array_pop($value);
        }
        $res = $vars['db']['handler']->getData($vars, $this->moduleTable, '*', ' '.$field.'="'.$value.'"', '', false);
        //var_dump($res);die();
        if(!empty($res)) {
            $res = reset($res);
            return $res['id'];
        } else {
            return false;
        }
    }

}

