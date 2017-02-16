<?php

defined('TPT_INIT') or die('access denied');

class tpt_module_BandType extends tpt_Module {

    function __construct(&$vars, $name, $moduleClassFile, $moduleClass, $moduleTable) {
        $fields = array(
                //db field name|field type|length|options|storage options|control|ctrAttr|default|label|index by|split keys|template
            new tpt_ModuleField('id',    'n', null, 'ai', '',         'sp', '', '', '',           true, false,  'LC'),
            new tpt_ModuleField('name',  's', 255,  '',   '',         'tf', ' style="width: 170px;"', '', 'Band Type Name', false, false, 'LC'),
            new tpt_ModuleField('weight',   'f', '',  '',   'floatval',         'tf', ' style="width: 70px;"', '', 'Weight (OZ)',     false, false, 'LC'),
            //'<div class="tpt_admin_module_section float-left" style="border: 2px solid #FFF;">',
            new tpt_ModuleField('per_bag',   'usi', '',    '',   'intval10', 'tf', ' style="width: 70px;"', '50', 'Items per bag',        false, false, 'LC'),
            new tpt_ModuleField('molds',  'ti', '',  '',   '',         'tf', ' style="width: 170px;"', '0', 'Molds', false, false, 'LC'),
            new tpt_ModuleField('screens',  'ti', '',  '',   '',         'tf', ' style="width: 170px;"', '0', 'Screens', false, false, 'LC'),
            new tpt_ModuleField('mold_fee', 'f', '',    '',   'floatval', 'tf', ' style="width: 70px;"', '', 'Mold fee',      false, false, 'LC'),
            new tpt_ModuleField('screen_fee',  'f', '',    '',   'floatval', 'tf', ' style="width: 70px;"', '', 'Screen fee',       false, false, 'LC'),
            //'</div>',
            new tpt_ModuleField('options_table',   's', 64,   '',   '', 'tf', ' style="width: 130px;"', '', 'Options pricing table', false, false, 'LC'),
            new tpt_ModuleField('available_styles_id',   's', 255,   '',   '', 'tf', ' style="width: 100px;"', '1,2,3,4,5', 'Available styles ids', false, false, 'LC'),
			new tpt_ModuleField('default_style',  'i', '',  '',   '',         'tf', ' style="width: 170px;"', '0', 'Default Style', false, false, 'LC'),
            new tpt_ModuleField('select_entry',  'ti', '',  '',   '',         'tf', ' style="width: 170px;"', '0', 'Selectable Entry', false, false, 'LC'),
            new tpt_ModuleField('os_styles',   's', 255,   '',   '', 'tf', ' style="width: 100px;"', '1,2,3,4,5', 'Available styles ids (OS)', false, false, 'LC'),
            new tpt_ModuleField('ih_styles',   's', 255,   '',   '', 'tf', ' style="width: 100px;"', '1,2,3,4,5', 'Available styles ids (IH)', false, false, 'LC'),
            new tpt_ModuleField('shortname',   's', 16,   '',   '', 'tf', ' style="width: 100px;"', '1,2,3,4,5', 'Short Name', false, false, 'LC'),
            new tpt_ModuleField('sku_comp',  's', 16,  '',   '',         'tf', ' style="width: 170px;"', '', 'Sku Component', false, false, 'LC'),
            new tpt_ModuleField('label2',  's', 255,  '',   '',         'tf', ' style="width: 170px;"', '', 'Alternative name 2', true, false, 'LC'),
            new tpt_ModuleField('width_mm', 'f', '',    '',   '', 'tf', ' style="width: 70px;"', '', 'Width (mm)',      false, false, 'LC'),
            new tpt_ModuleField('width_in', 'f', '',    '',   '', 'tf', ' style="width: 70px;"', '', 'Width (in)',      false, false, 'LC'),
            new tpt_ModuleField('aka',  's', 64,  '',   '',         'tf', ' style="width: 170px;"', '', 'Alternative names2', false, false, 'LC'),
            new tpt_ModuleField('blank',  'ti', '',  '',   '',         'tf', ' style="width: 170px;"', '0', 'Blank Band', false, false, 'LC'),
            new tpt_ModuleField('base_type',  'i', '',  '',   '',         'tf', ' style="width: 170px;"', '0', 'Base Type', false, false, 'LC'),
            new tpt_ModuleField('overseas_blanks',   'ti', '',   '',   '', 'tf', ' style="width: 100px;"', '0', 'Overseas Blanks Inventory', false, false, 'LC'),
            new tpt_ModuleField('label3',  's', 255,  '',   '',         'tf', ' style="width: 170px;"', '', 'Alternative name 3', false, false, 'LC'),
            new tpt_ModuleField('label4',  's', 255,  '',   '',         'tf', ' style="width: 170px;"', '', 'Alternative name 4', false, false, 'LC'),
            new tpt_ModuleField('label5',  's', 255,  '',   '',         'tf', ' style="width: 170px;"', '', 'Alternative name 5', false, false, 'LC'),
            new tpt_ModuleField('sdesc',  's', 32,  '',   '',         'tf', ' style="width: 170px;"', '', 'Short Desc', false, false, 'LC'),
            new tpt_ModuleField('enabled',  'ti', '',  '',   '',         'tf', ' style="width: 170px;"', '1', 'Enabled', false, false, 'LC'),
            //'<div class="float-left padding-top-20 padding-bottom-20 padding-left-10 padding-right-10" style="background-color: #FFF;"><div class="display-inline-block height-10 width-80" style="background-color: #`HEX`; border: 1px solid #000;"></div></div>',
            //'<div class="float-left" style="background-color: #`HEX`; border: 1px solid #000;"><img src="'.$vars['config']['resourceurl'].'/images/Band-Transperent-Preview.png" class="width-80" /></div>',
            //'<div class="float-left" style="background-color: #`HEX`; border: 1px solid #000;"><img src="'.$vars['config']['resourceurl'].'/images/Transparent-Swirl-Band-Preview.png" class="width-80" /></div>'
        );
        parent::__construct($vars, $name, $moduleClassFile, $moduleClass, $moduleTable, $fields, 'id');
    }


	function _convertOldData(&$vars, $type) {
		$ttable = $this->moduleTable;

		$type = mysql_real_escape_string($type);
		$tq = <<< EOT
  SELECT `id` FROM `$ttable` WHERE FIND_IN_SET("$type", CONCAT(`id`, ",", COALESCE(`name`, `id`) , ",", COALESCE(`label2`, `id`), ",", COALESCE(`aka`, `id`)))
EOT;
		$vars['db']['handler']->query($tq);
		//tpt_dump($tq, true);
		$typeid = $vars['db']['handler']->fetch_assoc_list('id', false);
		if(!empty($typeid)) {
			$typeid = reset($typeid);
		}

		return $typeid;
	}

    function userEndData(&$vars) {
        $_temp = array();
        $rArr = $this->moduleData['id'];
        foreach($rArr as $item) {
            $_temp[$item['id']] = array('name'=>$item['name'], 'blank'=>$item['blank']);
        }
        //var_dump($rArr);die();

        $rArr = $_temp;
        //var_dump($rArr);die();
        return $rArr;
    }

	/*
	function getSelectedItem(&$vars, $input, $options) {
		$items = $this->getItems($vars, $input, $options);

		$sItem = 0;
		if(!empty($input['type']) && in_array($input['type'], $items)) {
			$sItem = $input['type'];
		}

		return $sItem;
	}
	*/
	function getDefaultItem(&$vars, $input, $options) {
		//$type = (!empty($input['type'])?intval($input['type'], 10):DEFAULT_TYPE);

		//return (!empty($this->moduleTable['id'][$type])?$type:DEFAULT_TYPE);
		$items = $this->getItems($vars, $input, $options);
		//$items_ids = array_keys($items);
		reset($items);
		$fitem = key($items);

		return (in_array(DEFAULT_TYPE, $items)?DEFAULT_TYPE:$fitem);
	}
	function getSelectedItem(&$vars, $input, $options) {
		$items = $this->getItems($vars, $input, $options);
		//tpt_dump($input['type']);
		//tpt_dump($items);

		return ((isset($input['type'])&&in_array($input['type'], array_keys($items)))?$input['type']:((count($items)===1)?key($items):0));
	}
	function getActiveItem(&$vars, $input, $options) {
		$items = $this->getItems($vars, $input, $options);
		$ditem = $this->getDefaultItem($vars, $input, $options);

		$sItem = ((isset($input['type'])&&(in_array($input['type'], array_keys($items)))?$input['type']:$ditem));

		return $sItem;
	}
	function getItems(&$vars, $input, $options) {

		$items = $this->moduleData['id'];
		$items = (!empty($options['type'])?array_combine(explode(',',$options['type']),explode(',',$options['type'])):$items);

        //tpt_dump($this->moduleData['id'], false, 'R' );
        //tpt_dump($items, true, 'R');

		$items = array_intersect_key($this->moduleData['id'], $items);
		$items = (!empty($items)?$items:$this->moduleData['id']);

		return $items;
	}

    function CartView_Value(&$vars, $section, $input=array(), $options=array(), &$vinput=array()) {
		return $this->moduleData['id'][$input[$section['pname']]]['name'];
	}
    function SB_Section(&$vars, $section, $input=array(), $options=array(), &$vinput=array()) {
		$items = $this->getItems($vars, $input, $options);
		$sItem = $this->getSelectedItem($vars, $input, $options);
		//tpt_dump($sItem);
		//tpt_dump($input);

		$html = '';
		$values = array();

		$title = 'Choose product type...';

		if(count($items)>1 || (count($items) === 0)) {
			$values[] = array(0, $title);
		}
		$sOpt = 0;
		$i=1;
		foreach($items as $item) {
			//$values[] = array($item['type'], $this->moduleData['id'][$item['type']]['name']);
			$values[] = array($item['id'], $item['name']);
			//if($sItem == $this->moduleData['id'][$item['type']]['id']) {
			if($sItem == $item['id']) {
				$sOpt = $i;
			}

			$i++;
		}
		if(count($items)===1) {
			$sOpt = 0;
		}

		//$ajax_call = tpt_ajax::getCall('bandtype.change_band_type_sb');

		$onchange = '';
		if(!empty($section['onchange'])) {
			$onchange = trim($section['onchange']);
			if(!empty($onchange)) {
				$onchange = ' onchange="' . htmlspecialchars($section['onchange']) . '"';
			}
		}

		$html = tpt_html::createSelect($vars, '', $values, $sOpt, ' style="background-color: white;border: 1px solid #ccc;border-radius: 12px;outline: 0 none;" class="padding-4" autocomplete="off" title="'.$title.'" id="control_'.$section['id'].'" onfocus="removeClass(this, \'invalid_field\');" '.$onchange);
		$html .= '<input type="hidden" id="type" name="type" value="'.$sItem.'" />';

		return $html;
	}
    function BandType_Panel_DC(&$vars, $sType='') {
        $tpt_imagesurl = TPT_IMAGES_URL;

        $types = array(
            1=>array('label'=>'14', 'styles'=>array(1,2,3,4,5)),
            2=>array('label'=>'12', 'styles'=>array(1,2,3,4,5,7)),
            3=>array('label'=>'34', 'styles'=>array(1,2,3,4,5)),
            4=>array('label'=>'1', 'styles'=>array(1,2,3,4,5,7)),
            5=>array('label'=>'slap', 'styles'=>array(1,2,5,7,8)),
            6=>array('label'=>'snap', 'styles'=>array(1,2,3,4,5)),
            7=>array('label'=>'chain', 'styles'=>array(1,2,3,4,5)),
            //8=>array('label'=>'ring', 'styles'=>array(1,2,3,4,5)),
            8=>array('label'=>'ring', 'styles'=>array(1,2,5)),
            9=>array('label'=>'usb', 'styles'=>array(1,2,3,4,5)),
        );

        $html = '';

        $items = $this->moduleData['id'];

        $items = array($items[1], $items[2], $items[3], $items[4], $items[6], $items[7], $items[8], null, $items[5]);

        $i = 0;
        $item_num = 0;
        $so_margin_left  = array(1 => '0',  2 => '24', 3 => '30', 4 => '7', 5 => '0',  6 => '5',  7 => '0');
        $so_margin_right = array(1 => '20', 2 => '0',  3 => '5',  4 => '0', 5 => '20', 6 => '15', 7 => '0');
        foreach($items as $item) {
            $item_num++;
            $link = '';

            if($i % 4 == 0) {
                $link .= '<div class="clearFix">';
            }

            if ($item_num < 8)
                $so_margin_bottom = '20px';
            else
                $so_margin_bottom = '0px';

            if(!empty($item)) {
                $id = $item['id'];
                $active = '';
                $checked = '';
                if(!empty($sType) && ($sType == $id)) {
                    $checked = ' checked="checked"';
                    $active = 'active';
                }
                $name = htmlentities($item['name']);

                $zType = 'zType_'.$types[$id]['label'].'_ID';

                $link .= <<< EOT
                <a rel="tooltip[tooltip_large_$id]" title="$name" style="min-width: 210px; margin-bottom: $so_margin_bottom;margin-left: $so_margin_left[$item_num]px; margin-right: $so_margin_right[$item_num]px;" class="$active typePanelOption amz_brown text-decoration-none float-left height-148 font-size-16 display-block text-align-center todayshop-bold" href="javascript:void(0);" onclick="change_product_type('step_one_link_$item_num', '$zType');" id="step_one_link_$item_num">
                    <span class="display-block position-relative">
                        <span class="display-block position-relative padding-left-5 padding-right-5 padding-top-5 padding-bottom-5" style="z-index: 2;">
                            <img src="$tpt_imagesurl/design_center/types/thumb_$id.png" />
                            <br />
                            <span class="display-block text-align-left"><input $checked type="radio" name="trigger_band_type" value="$id" onclick="change_product_type(this, '$zType');" /> $name</span>
                        </span>
                        <span class="typePanelOptionBG display-block position-absolute top-0 right-0 bottom-0 left-0" style="z-index: 1;">
                            <span class="display-block position-absolute top-0 right-0 left-0 height-10 padding-left-10 background-repeat-no-repeat background-position-LT" style="background-image: url($tpt_imagesurl/rbox/rbox-tl-10x10.png);">
                                <span class="display-block padding-right-10 background-repeat-no-repeat background-position-RT" style="background-image: url($tpt_imagesurl/rbox/rbox-tr-10x10.png);">
                                    <span class="display-block height-10 background-repeat-repeat-x background-position-CT" style="background-image: url($tpt_imagesurl/rbox/rbox-t-10x10.png);">
                                    </span>
                                </span>
                            </span>
                            <span class="display-block position-absolute top-10 right-0 left-0 bottom-10 padding-left-10 background-repeat-repeat-y background-position-LC" style="background-image: url($tpt_imagesurl/rbox/rbox-l-10x10.png);">
                                <span class="display-block position-absolute top-0 right-0 left-0 bottom-0 padding-right-10 background-repeat-repeat-y background-position-RC" style="background-image: url($tpt_imagesurl/rbox/rbox-r-10x10.png);">
                                    <span class="display-block position-absolute top-0 right-10 left-10 bottom-0 background-repeat-repeat-y background-position-CC" style="background-color: #b7eeb1;">
                                    </span>
                                </span>
                            </span>
                            <span class="display-block position-absolute bottom-0 right-0 left-0 height-10 padding-left-10 background-repeat-no-repeat background-position-LB" style="background-image: url($tpt_imagesurl/rbox/rbox-bl-10x10.png);">
                                <span class="display-block padding-right-10 background-repeat-no-repeat background-position-RB" style="background-image: url($tpt_imagesurl/rbox/rbox-br-10x10.png);">
                                    <span class="display-block height-10 background-repeat-repeat-x background-position-CB" style="background-image: url($tpt_imagesurl/rbox/rbox-b-10x10.png);">
                                    </span>
                                </span>
                            </span>
                        </span>
                    </span>
                </a>
EOT;

                $vars['template']['tooltips'] .= <<< EOT
<div id="tooltip_large_$id" class="tooltip-wrapper hidden">
    <div class="tooltip-content">
        <img src="$tpt_imagesurl/design_center/types/large/large_$id.jpg" />
    </div>
</div>
EOT;
            }

            if(($i % 4 == 3) || ($i == count($items) -1)) {
                $link .= '</div>';
            }
            $html .= $link;

            $i++;
        }

        //var_dump($html);die();
        return $html;
    }

    function BandType_Select(&$vars) {
        $items = $vars['db']['handler']->getData($vars, $this->moduleTable, 'id,name');

        $html = '';
        $values = array();

        $title = 'Choose band type...';

        $i=1;
        foreach($items as $item) {
            if($i==1) {
                $values[] = array(2, '<div class="amz_brown font-size-18 height-15 padding-left-2 padding-right-2 line-height-15 white-space-nowrap" style="font-family: TODAYSHOP-BOLDITALIC,arial;"'./* style="border: 1px solid #555;background-color: #FFF;"*/'>'.$title.'</div>', $title);
                $i=0;
            }
            $values[] = array($item['id'], '<div class="height-15 padding-left-2 padding-right-2 line-height-15 white-space-nowrap" style="border: 1px solid #555;background-color: #FFF;">'.$item['name'].'</div>', $item['name']);
        }

        $valuesDelimiter = "\n";

        $html = tpt_html::createStyledSelect($vars, 'BandType', $values, $valuesDelimiter, ' display-block', ' width:210px;', ' width:202px;', ' padding-top-10', 0, '_debossed_tpt_pg_generate_prevew_all', 'tpt_pg_type', ' title="'.$title.'"');

        return $html;
    }

    function BandType_Select_SB(&$vars, $s, $sItem, $builder) {
        $types_module = getModule($vars, "BandType");



        $rows = $this->moduleData['id'];

        /*
        $types = array(
            1=>array('label'=>'14', 'styles'=>array(1,2,3,4,5)),
            2=>array('label'=>'12', 'styles'=>array(1,2,3,4,5,7)),
            3=>array('label'=>'34', 'styles'=>array(1,2,3,4,5)),
            4=>array('label'=>'1', 'styles'=>array(1,2,3,4,5,7)),
            5=>array('label'=>'slap', 'styles'=>array(1,2,5)),
            6=>array('label'=>'snap', 'styles'=>array(1,2,3,4,5)),
            7=>array('label'=>'chain', 'styles'=>array(1,2,3,4,5)),
            //8=>array('label'=>'ring', 'styles'=>array(1,2,3,4,5)),
            8=>array('label'=>'ring', 'styles'=>array(1,2,5)),
            9=>array('label'=>'usb', 'styles'=>array(1,2,3,4,5)),
        );
        //var_dump($inhouse);die();
        //var_dump($s);die();
        if($inhouse) {
                $types = array(
                    2=>array('label'=>'12', 'styles'=>array(6,7)),
                    4=>array('label'=>'1', 'styles'=>array(6)),
                    5=>array('label'=>'slap', 'styles'=>array(7)),
                );
        }
        if($_SERVER['REMOTE_ADDR'] == '109.160.0.218') {
        */

        //var_dump($writable);die();
        if(!$builder['writable']) {
            //die();
            /*
            $types = array(
                1=>array('label'=>'14', 'styles'=>array(1,2,3,4,5,7)),
                2=>array('label'=>'12', 'styles'=>array(1,2,3,4,5,7)),
                3=>array('label'=>'34', 'styles'=>array(1,2,3,4,5)),
                4=>array('label'=>'1', 'styles'=>array(1,2,3,4,5,7)),
                5=>array('label'=>'slap', 'styles'=>array(1,2,5,7,8)),
                6=>array('label'=>'snap', 'styles'=>array(1,2,3,4,5)),
                7=>array('label'=>'chain', 'styles'=>array(1,2,3,4,5)),
                //8=>array('label'=>'ring', 'styles'=>array(1,2,3,4,5)),
                8=>array('label'=>'ring', 'styles'=>array(1,2,5)),
                //9=>array('label'=>'usb', 'styles'=>array(1,2,3,4,5)),
            );
            
            if($builder['inhouse']) {
                $types = array(
                    1=>array('label'=>'14', 'styles'=>array(6,7)),
                    2=>array('label'=>'12', 'styles'=>array(6,7,11)),
                    4=>array('label'=>'1', 'styles'=>array(7)),
                    5=>array('label'=>'slap', 'styles'=>array(6,7,8,12)),
                );
            }
            */
            //if(isDev()) {
            if(!empty($builder['type']) && (($builder_types = explode(',', $builder['type'])) > 1)) {
                $types = array(
                    1=>array('label'=>'14', 'styles'=>array(1,2,3,4,5,7,16)),
                    2=>array('label'=>'12', 'styles'=>array(1,2,3,4,5,7,16)),
                    3=>array('label'=>'34', 'styles'=>array(1,2,3,4,5,16)),
                    4=>array('label'=>'1', 'styles'=>array(1,2,3,4,5,7,16)),
                    5=>array('label'=>'slap', 'styles'=>array(1,2,5,7,8,16)),
                    6=>array('label'=>'snap', 'styles'=>array(1,2,3,4,5,16)),
                    7=>array('label'=>'chain', 'styles'=>array(1,2,3,4,5,6,7,16)),
                    //8=>array('label'=>'ring', 'styles'=>array(1,2,3,4,5)),
                    8=>array('label'=>'ring', 'styles'=>array(1,2,5,16)),
                    34=>array('label'=>'1_4-ring', 'styles'=>array(1,2,5,16)),
                    //9=>array('label'=>'usb', 'styles'=>array(1,2,3,4,5)),
					37=>array('label'=>'12LEDSTD', 'styles'=>array(19,20)),
					38=>array('label'=>'12LEDSNM', 'styles'=>array(19,20)),
                );
                if($builder['inhouse']) {
                    $types = array(
                        1=>array('label'=>'14', 'styles'=>array(6,7)),
                        2=>array('label'=>'12', 'styles'=>array(6,7,11)),
                        4=>array('label'=>'1', 'styles'=>array(7)),
                        5=>array('label'=>'slap', 'styles'=>array(6,7,8,12)),
                        //6=>array('label'=>'snap', 'styles'=>array(6)),
                        7=>array('label'=>'chain', 'styles'=>array(6,7)),
						37=>array('label'=>'12LEDSTD', 'styles'=>array(19,20)),
						38=>array('label'=>'12LEDSNM', 'styles'=>array(19,20)),
                    );
                }
                $intersect = array_combine($builder_types, $builder_types);
                $types = array_intersect_key($types, $intersect);
            } else {
                $types = array(
                    1=>array('label'=>'14', 'styles'=>array(1,2,3,4,5,7,16)),
                    2=>array('label'=>'12', 'styles'=>array(1,2,3,4,5,7,16)),
                    3=>array('label'=>'34', 'styles'=>array(1,2,3,4,5,16)),
                    4=>array('label'=>'1', 'styles'=>array(1,2,3,4,5,7,16)),
                    5=>array('label'=>'slap', 'styles'=>array(1,2,5,7,8,16)),
                    6=>array('label'=>'snap', 'styles'=>array(1,2,3,4,5,16)),
                    7=>array('label'=>'chain', 'styles'=>array(1,2,3,4,5,6,7,16)),
                    //8=>array('label'=>'ring', 'styles'=>array(1,2,3,4,5)),
                    8=>array('label'=>'ring', 'styles'=>array(1,2,5,16)),
                    34=>array('label'=>'1_4-ring', 'styles'=>array(1,2,5,16)),
                    //9=>array('label'=>'usb', 'styles'=>array(1,2,3,4,5)),
					37=>array('label'=>'12LEDSTD', 'styles'=>array(19,20)),
					38=>array('label'=>'12LEDSNM', 'styles'=>array(19,20)),
                );
                if($builder['inhouse']) {
                    $types = array(
                        1=>array('label'=>'14', 'styles'=>array(6,7)),
                        2=>array('label'=>'12', 'styles'=>array(6,7,11)),
                        4=>array('label'=>'1', 'styles'=>array(7)),
                        5=>array('label'=>'slap', 'styles'=>array(6,7,8,12)),
                        //6=>array('label'=>'snap', 'styles'=>array(6)),
                        7=>array('label'=>'chain', 'styles'=>array(6,7)),
						37=>array('label'=>'12LEDSTD', 'styles'=>array(19,20)),
						38=>array('label'=>'12LEDSNM', 'styles'=>array(19,20)),
                    );
                }
            }
            //}

        } else {
            //die('asdasasdasasddas');
            $types = array(
                9=>array('label'=>'12WB', 'styles'=>array(1,2,3,4,5,16)),
                //10=>array('label'=>'12WFWS', 'styles'=>array(1,2,3,4,5)),
                //11=>array('label'=>'12WBBM', 'styles'=>array(1,2,3,4,5)),
                13=>array('label'=>'19WB', 'styles'=>array(1,2,3,4,5,16)),
                //14=>array('label'=>'19WFWS', 'styles'=>array(1,2,3,4,5)),
                //15=>array('label'=>'19WBBM', 'styles'=>array(1,2,3,4,5)),
                16=>array('label'=>'24WB', 'styles'=>array(1,2,3,4,5,16)),
                //17=>array('label'=>'24WFWS', 'styles'=>array(1,2,3,4,5)),
                //18=>array('label'=>'24WBBM', 'styles'=>array(1,2,3,4,5)),
                19=>array('label'=>'WRTBFM-SLAP', 'styles'=>array(1,2,3,4,5,16)),
                //20=>array('label'=>'WRTBBM-SLAP', 'styles'=>array(1,2,3,4,5)),
                //21=>array('label'=>'WRTB-SLAP', 'styles'=>array(1,2,3,4,5)),
                //23=>array('label'=>'WRTFWS-SLAP', 'styles'=>array(9)),
                24=>array('label'=>'WRTBFM-12SNAP', 'styles'=>array(1,2,3,4,5,16)),
                //25=>array('label'=>'WRTBBM-12SNAP', 'styles'=>array(1,2,3,4,5)),
                //26=>array('label'=>'WRTB-12SNAP', 'styles'=>array(9)),
                //27=>array('label'=>'WRTFWS-12SNAP', 'styles'=>array(9)),
                28=>array('label'=>'WRTB-CHAIN', 'styles'=>array(9)),
                //29=>array('label'=>'WRTFWS-CHAIN', 'styles'=>array(9)),
                //30=>array('label'=>'WRTBBM-CHAIN', 'styles'=>array(1,2,3,4,5)),
                //31=>array('label'=>'IDBRC-S', 'styles'=>array(6)),
                //32=>array('label'=>'IDBRC-ML', 'styles'=>array(6)),
            );
            if($builder['inhouse']) {
                $types = array(
                    9=>array('label'=>'12WB', 'styles'=>array()),
                    12=>array('label'=>'12WBBM', 'styles'=>array(6)),
                    19=>array('label'=>'WRTB-SLAP', 'styles'=>array()),
                    22=>array('label'=>'WRTBBM-SLAP', 'styles'=>array(6)),
                );
            }
        }
        /*}*/


        /*
        $styles = array(
            1=>array('label'=>'Debossed - No min. qty', 'types'=>array(1,2,3,4,5,6,7,8,9)),
            2=>array('label'=>'Ink Filled Deboss - 50 min. qty', 'types'=>array(1,2,3,4,5,6,7,8,9)),
            //3=>array('label'=>'Embossed - 50 min. qty', 'types'=>array(1,2,3,4,6,7,8,9)),
            3=>array('label'=>'Embossed - 50 min. qty', 'types'=>array(1,2,3,4,6,7,9)),
            //4=>array('label'=>'Colorized Emboss - 50 min. qty', 'types'=>array(1,2,3,4,6,7,8,9)),
            4=>array('label'=>'Colorized Emboss - 50 min. qty', 'types'=>array(1,2,3,4,6,7,9)),
            5=>array('label'=>'Screen Printed - 50 min. qty', 'types'=>array(1,2,3,4,5,6,7,8,9)),
            6=>array('label'=>'Laser Debossed - 50 min. qty', 'types'=>array(2,5)),
            7=>array('label'=>'Dual-layer - No min. qty', 'types'=>array(2,4)),
        );
        if($_SERVER['REMOTE_ADDR'] == '109.160.0.218') {
        */
        if(!$builder['writable']) {
            
            /*
            $styles = array(
                1=>array('label'=>'Debossed', 'types'=>array(1,2,3,4,5,6,7,8)),
                2=>array('label'=>'Ink Filled Deboss - 50 min. qty', 'types'=>array(1,2,3,4,5,6,7,8)),
                //3=>array('label'=>'Embossed - 50 min. qty', 'types'=>array(1,2,3,4,6,7,8,9)),
                3=>array('label'=>'Embossed - 50 min. qty', 'types'=>array(1,2,3,4,6,7)),
                //4=>array('label'=>'Colorized Emboss - 50 min. qty', 'types'=>array(1,2,3,4,6,7,8,9)),
                4=>array('label'=>'Colorized Emboss - 50 min. qty', 'types'=>array(1,2,3,4,6,7)),
                5=>array('label'=>'Screen Printed - 50 min. qty', 'types'=>array(1,2,3,4,5,6,7,8)),
                7=>array('label'=>'Dual-layer - No min. qty', 'types'=>array(2,4,5)),
                8=>array('label'=>'Cut-Away - No min. qty', 'types'=>array(5)),
            );
            if($builder['inhouse']) {
                $styles = array(
                    6=>array('label'=>'Debossed - No min. qty', 'types'=>array(1,2,5)),
                    7=>array('label'=>'Dual-layer - No min. qty', 'types'=>array(1,2,4,5)),
                    8=>array('label'=>'Cut-Away - No min. qty', 'types'=>array(5)),
                    11=>array('label'=>'Writable - No min. qty', 'types'=>array(2)),
                    12=>array('label'=>'Writable - No min. qty', 'types'=>array(5)),
                );

            }
            */
            //if(isDev()) {
            if(!empty($builder['type']) && (($builder_types = explode(',', $builder['type'])) > 1)) {
                $intersect = $builder_types;
                $styles = array(
                    1=>array('label'=>'Debossed - 50 min. qty', 'types'=>array_intersect(array(1,2,3,4,5,6,7,8,34), $intersect)),
                    2=>array('label'=>'Ink Filled Deboss - 50 min. qty', 'types'=>array_intersect(array(1,2,3,4,5,6,7,8,34), $intersect)),
                    //3=>array('label'=>'Embossed - 50 min. qty', 'types'=>array_intersect_key(array(1,2,3,4,6,7,8,9), $intersect)),
                    3=>array('label'=>'Embossed - 50 min. qty', 'types'=>array_intersect(array(1,2,3,4,6,7), $intersect)),
                    //4=>array('label'=>'Colorized Emboss - 50 min. qty', 'types'=>array_intersect_key(array(1,2,3,4,6,7,8,9), $intersect)),
                    4=>array('label'=>'Colorized Emboss - 50 min. qty', 'types'=>array_intersect(array(1,2,3,4,6,7), $intersect)),
                    5=>array('label'=>'Screen Printed - 50 min. qty', 'types'=>array_intersect(array(1,2,3,4,5,6,7,8,34,37,38), $intersect)),
                    6=>array('label'=>'Debossed - No min. qty', 'types'=>array_intersect(array(7), $intersect)),
                    7=>array('label'=>'Dual-layer - No min. qty', 'types'=>array_intersect(array(2,4,5,7), $intersect)),
                    8=>array('label'=>'Cut-Away - No min. qty', 'types'=>array_intersect(array(5), $intersect)),
					//9=>array('label'=>'Blank - 100 min. qty', 'types'=>array(37,38)),
                    16=>array('label'=>'Screen Printed (Invert Message) - 50 min. qty', 'types'=>array_intersect(array(1,2,3,4,5,6,7,8,34,37,38), $intersect)),
					18=>array('label'=>'Debossed - 100 min. qty', 'types'=>array(37,38)),
					19=>array('label'=>'Debossed - No min. qty', 'types'=>array(37,38)),
					20=>array('label'=>'Blank - No min. qty', 'types'=>array(37,38)),
                );
                if($builder['inhouse']) {
                    $styles = array(
                        6=>array('label'=>'Debossed - No min. qty', 'types'=>array_intersect(array(1,2,5,6,7), $intersect)),
                        7=>array('label'=>'Dual-layer - No min. qty', 'types'=>array_intersect(array(1,2,4,5,7), $intersect)),
                        8=>array('label'=>'Cut-Away - No min. qty', 'types'=>array_intersect(array(5), $intersect)),
                        11=>array('label'=>'Writable - No min. qty', 'types'=>array_intersect(array(2), $intersect)),
                        12=>array('label'=>'Writable - No min. qty', 'types'=>array_intersect(array(5), $intersect)),
						19=>array('label'=>'Debossed - No min. qty', 'types'=>array(37,38)),
						20=>array('label'=>'Blank - No min. qty', 'types'=>array(37,38)),
                    );
                }
            } else {
                $styles = array(
                    1=>array('label'=>'Debossed - 50 min. qty', 'types'=>array(1,2,3,4,5,6,7,8,34)),
                    2=>array('label'=>'Ink Filled Deboss - 50 min. qty', 'types'=>array(1,2,3,4,5,6,7,8,34)),
                    //3=>array('label'=>'Embossed - 50 min. qty', 'types'=>array(1,2,3,4,6,7,8,9)),
                    3=>array('label'=>'Embossed - 50 min. qty', 'types'=>array(1,2,3,4,6,7)),
                    //4=>array('label'=>'Colorized Emboss - 50 min. qty', 'types'=>array(1,2,3,4,6,7,8,9)),
                    4=>array('label'=>'Colorized Emboss - 50 min. qty', 'types'=>array(1,2,3,4,6,7)),
                    5=>array('label'=>'Screen Printed - 50 min. qty', 'types'=>array(1,2,3,4,5,6,7,8,34)),
                    6=>array('label'=>'Debossed - No min. qty', 'types'=>array(7)),
                    7=>array('label'=>'Dual-layer - No min. qty', 'types'=>array(2,4,5,7)),
                    8=>array('label'=>'Cut-Away - No min. qty', 'types'=>array(5)),
                    16=>array('label'=>'Screen Printed (Invert Message) - 50 min. qty', 'types'=>array(1,2,3,4,5,6,7,8,34)),
					18=>array('label'=>'Debossed - 100 min. qty', 'types'=>array(37,38)),
					19=>array('label'=>'Debossed - No min. qty', 'types'=>array(37,38)),
					20=>array('label'=>'Blank - No min. qty', 'types'=>array(37,38)),
                );
                if($builder['inhouse']) {
                    $styles = array(
                        6=>array('label'=>'Debossed - No min. qty', 'types'=>array(1,2,5,6,7)),
                        7=>array('label'=>'Dual-layer - No min. qty', 'types'=>array(1,2,4,5,7)),
                        8=>array('label'=>'Cut-Away - No min. qty', 'types'=>array(5)),
                        11=>array('label'=>'Writable - No min. qty', 'types'=>array(2)),
                        12=>array('label'=>'Writable - No min. qty', 'types'=>array(5)),
						19=>array('label'=>'Debossed - No min. qty', 'types'=>array(37,38)),
						20=>array('label'=>'Blank - No min. qty', 'types'=>array(37,38)),
                    );
                }
            }
            //}

        } else {
            //die();
            $styles = array(
                //1=>array('label'=>$debossed_label, 'types'=>array(9,10,11,13,14,15,16,17,18,19,20,21,23,24,25,26,27,28,29,30)),
                1=>array('label'=>'Debossed', 'types'=>array(9,13,16,19,24,28)),
                //2=>array('label'=>'Ink Filled Deboss - 50 min. qty', 'types'=>array(9,10,11,13,14,15,16,17,18,19,20,21,23,24,25,26,27,28,29,30)),
                2=>array('label'=>'Ink Filled Deboss', 'types'=>array(9,13,16,19,24,28)),
                //3=>array('label'=>'Embossed - 50 min. qty', 'types'=>array(1,2,3,4,6,7,8,9)),
                //3=>array('label'=>'Embossed - 50 min. qty', 'types'=>array(9,10,11,13,14,15,16,17,18,19,20,21,23,24,25,26,27,28,29,30)),
                3=>array('label'=>'Embossed', 'types'=>array(9,13,16,19,24,28)),
                //4=>array('label'=>'Colorized Emboss - 50 min. qty', 'types'=>array(1,2,3,4,6,7,8,9)),
                //4=>array('label'=>'Colorized Emboss - 50 min. qty', 'types'=>array(9,10,11,13,14,15,16,17,18,19,20,21,23,24,25,26,27,28,29,30)),
                4=>array('label'=>'Colorized Emboss', 'types'=>array(9,13,16,19,24,28)),
                //5=>array('label'=>'Screen Printed - 50 min. qty', 'types'=>array(9,10,11,13,14,15,16,17,18,19,20,21,23,24,25,26,27,28,29,30)),
                5=>array('label'=>'Screen Printed', 'types'=>array(9,13,16,19,24,28)),
                //9=>array('label'=>'Blank - 50 min. qty', 'types'=>array(9,10,11,13,14,15,16,17,18,19,20,21,23,24,25,26,27,28,29,30)),
                9=>array('label'=>'Blank', 'types'=>array(9,13,16,19,24,28)),
            );
            if($builder['inhouse']) {
                //die();
                $styles = array(
                    6=>array('label'=>'Debossed - No min. qty', 'types'=>array(12)),
                );
            }
        }
        /*
        }
        */

        $style = 5;

        $items = array();

        //var_dump($s);die();
        //var_dump(array_flip($style['types']));die();

        if(!$builder['inhouse']) {
            //die();
            $style = $styles[$s];
            //var_dump($s);die();
            //var_dump($styles);die();
            //tpt_dump($style);
            //tpt_dump($styles, true);
            //tpt_dump($style, true);
            $items = array_intersect_key($rows,
                        array_flip($style['types']));
            //var_dump($items);die();
        } else {
            /*
            $styles = array(
                6=>array('label'=>'Debossed - No min. qty', 'types'=>array(1,2,5,9,11,16,18)),
                7=>array('label'=>'Dual-layer - No min. qty', 'types'=>array(1,2,4,5)),
                8=>array('label'=>'Cut-Away - No min. qty', 'types'=>array(5)),
            );
            */
            //if(isDev()) {
                $styles = array(
                    6=>array('label'=>'Debossed - No min. qty', 'types'=>array(1,2,5,6,7,9,11,16,18)),
                    7=>array('label'=>'Dual-layer - No min. qty', 'types'=>array(1,2,4,5,7)),
                    8=>array('label'=>'Cut-Away - No min. qty', 'types'=>array(5)),
                    19=>array('label'=>'Debossed - No min. qty', 'types'=>array(37,38)),
                    20=>array('label'=>'Blank - No min. qty', 'types'=>array(37,38)),
                );
            //}
            //$intersect = array(1=>0, 2=>1, 4=>2, 5=>3/*, 12=>4, 22=>5*/);
            //if(isDev()) {
                $intersect = array(1=>0, 2=>1, 4=>2, 5=>3, 6=>4, 7=>5/*, 12=>4, 22=>5*/);
			if($s == 7) {
				$intersect = array(1=>0, 2=>1, 4=>2, 5=>3/*, 6=>4*/, 7=>5/*, 12=>4, 22=>5*/);
			}
            //}
            if($builder['writable']) {
                $styles = array(
                    6=>array('label'=>'Debossed - No min. qty', 'types'=>array(9,11,16,18)),
                );
                $intersect = array(9=>0, 11=>2, 16=>3, 18=>4);
            }
            if($builder['cl']) {
                $styles = array(
                    6=>array('label'=>'Debossed - No min. qty', 'types'=>array(31,32,33)),
                );
                $intersect = array(31=>1, 32=>2, 33=>3);
            }
            /*
            if($s == 6) {
                $intersect = array(2=>0, 5=>2);
            }
            */
			//tpt_dump($rows);
			//tpt_dump($intersect, true);
            $items = array_intersect_key($rows,
                        $intersect);
        }

        //tpt_dump($rows, true);
        //tpt_dump($items, true);





        $html = '';
        $values = array();

        $title = 'Choose product type...';

        $values[] = array(0, $title);
        $sOpt = 0;
        $i=1;
        foreach($items as $item) {
            $values[] = array($item['id'], $item['name']);
            if($sItem == $item['id']) {
                $sOpt = $i;
            }

            if($builder['writable']) {
                $btype = $types_module->moduleData['id'][$item['id']]['base_type'];
                $btitems = $vars['db']['handler']->getData($vars, $types_module->moduleTable, 'id', '`base_type`='.$btype, 'id', false);

                //var_dump($btitems);die();
                $witems = array_keys($btitems);
                //var_dump($witems);die();
                if(in_array($sItem, $witems)) {
                    //var_dump($witems);die();
                    $sOpt = $i;
                }
            }

            $i++;
        }

        $ajax_call = tpt_ajax::getCall('bandtype.change_band_type_sb');

        return tpt_html::createSelect($vars, '', $values, $sOpt, ' title="'.$title.'" id="product_type_select" onfocus="removeClass(this, \'invalid_field\');" onchange="valid_change(document.getElementById(\'tpt_pg_type\'), this);'.$ajax_call.'"');
        //return tpt_html::createSelect($vars, 'band_color', $values, $selectedColor, ' title="'.$title.'"');

    }



	function BandType_PlainSelectSDN(&$vars, $sItem, $class=null, $pid='null') {
		$data_module = getModule($vars, 'BandData');
		$data_table = $data_module->moduleTable;
		$types_table = $this->moduleTable;

		$db = $vars['db']['handler'];


		//tpt_dump($class, true);

		if(is_null($class)) {
			$items = $vars['db']['handler']->getData($vars, $data_module->moduleTable, '*', ' (1=1) GROUP BY `writable_class`', 'id', false);
		} else if($class == 2) {
			//$items = $vars['db']['handler']->getData($vars, $data_module->moduleTable, '*', '`writable`=0 GROUP BY `writable_class`', 'id', false);
			/*
			$query = <<< EOT
SELECT
	`$types_table`.`id`, `$types_table`.`name`
FROM
	`$data_table`
LEFT JOIN
	`$types_table`
ON
	`$data_table`.`base_type`=`$types_table`.`id`
WHERE
	`writable`!=0
GROUP BY
	`$data_table`.`base_type`
EOT;
			$db->query($query);
			$items = $db->fetch_assoc_list('id', false);
			*/
			$items = $data_module->writable_basic;
			//tpt_dump($items, true);
		} else {
			//$items = $vars['db']['handler']->getData($vars, $data_module->moduleTable, '*', '`writable`!=0 GROUP BY `writable_class`', 'id', false);
			$query = <<< EOT
SELECT
	`$types_table`.`id`, `$types_table`.`name`
FROM
	`$data_table`
LEFT JOIN
	`$types_table`
ON
	`$data_table`.`type`=`$types_table`.`id`
WHERE
	`writable`=0
EOT;
			$db->query($query);
			$items = $db->fetch_assoc_list('id', false);
		}





		$html = '';
		$values = array();

		$title = 'Choose product type...';

		$values[] = array(0, $title);
		$sOpt = 0;
		$i=1;
		foreach($items as $item) {
			//$values[] = array($item['type'], $this->moduleData['id'][$item['type']]['name']);
			$values[] = array($item['id'], $item['name']);
			//if($sItem == $this->moduleData['id'][$item['type']]['id']) {
			if($sItem == $item['id']) {
				$sOpt = $i;
			}

			$i++;
		}

		//$ajax_call = tpt_ajax::getCall('bandtype.change_band_type_sb');

		return tpt_html::createSelect($vars, '', $values, $sOpt, ' autocomplete="off" title="'.$title.'" id="'.$pid.'_control_type" onfocus="removeClass(this, \'invalid_field\');" onchange="/*valid_change(document.getElementById(\'tpt_pg_type\'), this);*/update_product_row(this);"');
		//return tpt_html::createSelect($vars, 'band_color', $values, $selectedColor, ' title="'.$title.'"');

	}


    
    
    
    
    
    function BandType_Select_QF(&$vars, $s, $sItem) {
        $types_module = getModule($vars, "BandType");



        $rows = $this->moduleData['id'];

        /*
        $types = array(
            1=>array('label'=>'14', 'styles'=>array(1,2,3,4,5)),
            2=>array('label'=>'12', 'styles'=>array(1,2,3,4,5,7)),
            3=>array('label'=>'34', 'styles'=>array(1,2,3,4,5)),
            4=>array('label'=>'1', 'styles'=>array(1,2,3,4,5,7)),
            5=>array('label'=>'slap', 'styles'=>array(1,2,5)),
            6=>array('label'=>'snap', 'styles'=>array(1,2,3,4,5)),
            7=>array('label'=>'chain', 'styles'=>array(1,2,3,4,5)),
            //8=>array('label'=>'ring', 'styles'=>array(1,2,3,4,5)),
            8=>array('label'=>'ring', 'styles'=>array(1,2,5)),
            9=>array('label'=>'usb', 'styles'=>array(1,2,3,4,5)),
        );
        //var_dump($inhouse);die();
        //var_dump($s);die();
        if($inhouse) {
                $types = array(
                    2=>array('label'=>'12', 'styles'=>array(6,7)),
                    4=>array('label'=>'1', 'styles'=>array(6)),
                    5=>array('label'=>'slap', 'styles'=>array(7)),
                );
        }
        if($_SERVER['REMOTE_ADDR'] == '109.160.0.218') {
        */

        //var_dump($writable);die();
        if(!$builder['writable']) {
            //die();
            $types = array(
                1=>array('label'=>'14', 'styles'=>array(1,2,3,4,5,7)),
                2=>array('label'=>'12', 'styles'=>array(1,2,3,4,5,7)),
                3=>array('label'=>'34', 'styles'=>array(1,2,3,4,5)),
                4=>array('label'=>'1', 'styles'=>array(1,2,3,4,5,7)),
                5=>array('label'=>'slap', 'styles'=>array(1,2,5,7,8)),
                //6=>array('label'=>'snap', 'styles'=>array(1,2,3,4,5)),
                7=>array('label'=>'chain', 'styles'=>array(1,2,3,4,5)),
                //8=>array('label'=>'ring', 'styles'=>array(1,2,3,4,5)),
                8=>array('label'=>'ring', 'styles'=>array(1,2,5)),
                //9=>array('label'=>'usb', 'styles'=>array(1,2,3,4,5)),
            );
            if(isDev()) {
                $types = array(
                    1=>array('label'=>'14', 'styles'=>array(1,2,3,4,5,7)),
                    2=>array('label'=>'12', 'styles'=>array(1,2,3,4,5,7)),
                    3=>array('label'=>'34', 'styles'=>array(1,2,3,4,5)),
                    4=>array('label'=>'1', 'styles'=>array(1,2,3,4,5,7)),
                    5=>array('label'=>'slap', 'styles'=>array(1,2,5,7,8)),
                    //6=>array('label'=>'snap', 'styles'=>array(1,2,3,4,5)),
                    7=>array('label'=>'chain', 'styles'=>array(1,2,3,4,5,7)),
                    //8=>array('label'=>'ring', 'styles'=>array(1,2,3,4,5)),
                    8=>array('label'=>'ring', 'styles'=>array(1,2,5)),
                    //9=>array('label'=>'usb', 'styles'=>array(1,2,3,4,5)),
                );
            }
            if($builder['inhouse']) {
                $types = array(
                    1=>array('label'=>'14', 'styles'=>array(6,7)),
                    2=>array('label'=>'12', 'styles'=>array(6,7,11)),
                    4=>array('label'=>'1', 'styles'=>array(7)),
                    5=>array('label'=>'slap', 'styles'=>array(6,7,8,12)),
                );
                if(isDev()) {
                    $types = array(
                        1=>array('label'=>'14', 'styles'=>array(6,7)),
                        2=>array('label'=>'12', 'styles'=>array(6,7,11)),
                        4=>array('label'=>'1', 'styles'=>array(7)),
                        5=>array('label'=>'slap', 'styles'=>array(6,7,8,12)),
                        //6=>array('label'=>'snap', 'styles'=>array(6)),
                        7=>array('label'=>'chain', 'styles'=>array(6,7)),
                    );
                }
            }
        } else {
            //die('asdasasdasasddas');
            $types = array(
                9=>array('label'=>'12WB', 'styles'=>array(1,2,3,4,5)),
                //10=>array('label'=>'12WFWS', 'styles'=>array(1,2,3,4,5)),
                //11=>array('label'=>'12WBBM', 'styles'=>array(1,2,3,4,5)),
                13=>array('label'=>'19WB', 'styles'=>array(1,2,3,4,5)),
                //14=>array('label'=>'19WFWS', 'styles'=>array(1,2,3,4,5)),
                //15=>array('label'=>'19WBBM', 'styles'=>array(1,2,3,4,5)),
                16=>array('label'=>'24WB', 'styles'=>array(1,2,3,4,5)),
                //17=>array('label'=>'24WFWS', 'styles'=>array(1,2,3,4,5)),
                //18=>array('label'=>'24WBBM', 'styles'=>array(1,2,3,4,5)),
                19=>array('label'=>'WRTBFM-SLAP', 'styles'=>array(1,2,3,4,5)),
                //20=>array('label'=>'WRTBBM-SLAP', 'styles'=>array(1,2,3,4,5)),
                //21=>array('label'=>'WRTB-SLAP', 'styles'=>array(1,2,3,4,5)),
                //23=>array('label'=>'WRTFWS-SLAP', 'styles'=>array(9)),
                24=>array('label'=>'WRTBFM-12SNAP', 'styles'=>array(1,2,3,4,5)),
                //25=>array('label'=>'WRTBBM-12SNAP', 'styles'=>array(1,2,3,4,5)),
                //26=>array('label'=>'WRTB-12SNAP', 'styles'=>array(9)),
                //27=>array('label'=>'WRTFWS-12SNAP', 'styles'=>array(9)),
                28=>array('label'=>'WRTB-CHAIN', 'styles'=>array(9)),
                //29=>array('label'=>'WRTFWS-CHAIN', 'styles'=>array(9)),
                //30=>array('label'=>'WRTBBM-CHAIN', 'styles'=>array(1,2,3,4,5)),
                //31=>array('label'=>'IDBRC-S', 'styles'=>array(6)),
                //32=>array('label'=>'IDBRC-ML', 'styles'=>array(6)),
            );
            if($builder['inhouse']) {
                $types = array(
                    9=>array('label'=>'12WB', 'styles'=>array()),
                    12=>array('label'=>'12WBBM', 'styles'=>array(6)),
                    19=>array('label'=>'WRTB-SLAP', 'styles'=>array()),
                    22=>array('label'=>'WRTBBM-SLAP', 'styles'=>array(6)),
                );
            }
        }
        /*}*/


        /*
        $styles = array(
            1=>array('label'=>'Debossed - No min. qty', 'types'=>array(1,2,3,4,5,6,7,8,9)),
            2=>array('label'=>'Ink Filled Deboss - 50 min. qty', 'types'=>array(1,2,3,4,5,6,7,8,9)),
            //3=>array('label'=>'Embossed - 50 min. qty', 'types'=>array(1,2,3,4,6,7,8,9)),
            3=>array('label'=>'Embossed - 50 min. qty', 'types'=>array(1,2,3,4,6,7,9)),
            //4=>array('label'=>'Colorized Emboss - 50 min. qty', 'types'=>array(1,2,3,4,6,7,8,9)),
            4=>array('label'=>'Colorized Emboss - 50 min. qty', 'types'=>array(1,2,3,4,6,7,9)),
            5=>array('label'=>'Screen Printed - 50 min. qty', 'types'=>array(1,2,3,4,5,6,7,8,9)),
            6=>array('label'=>'Laser Debossed - 50 min. qty', 'types'=>array(2,5)),
            7=>array('label'=>'Dual-layer - No min. qty', 'types'=>array(2,4)),
        );
        if($_SERVER['REMOTE_ADDR'] == '109.160.0.218') {
        */
        if(!$builder['writable']) {
            
            
            $styles = array(
                1=>array('label'=>'Debossed', 'types'=>array(1,2,3,4,5,6,7,8)),
                2=>array('label'=>'Ink Filled Deboss - 50 min. qty', 'types'=>array(1,2,3,4,5,6,7,8)),
                //3=>array('label'=>'Embossed - 50 min. qty', 'types'=>array(1,2,3,4,6,7,8,9)),
                3=>array('label'=>'Embossed - 50 min. qty', 'types'=>array(1,2,3,4,6,7)),
                //4=>array('label'=>'Colorized Emboss - 50 min. qty', 'types'=>array(1,2,3,4,6,7,8,9)),
                4=>array('label'=>'Colorized Emboss - 50 min. qty', 'types'=>array(1,2,3,4,6,7)),
                5=>array('label'=>'Screen Printed - 50 min. qty', 'types'=>array(1,2,3,4,5,6,7,8)),
                7=>array('label'=>'Dual-layer - No min. qty', 'types'=>array(2,4,5)),
                8=>array('label'=>'Cut-Away - No min. qty', 'types'=>array(5)),
            );
            if(isDev()) {
                $styles = array(
                    1=>array('label'=>'Debossed', 'types'=>array(1,2,3,4,5,6,7,8)),
                    2=>array('label'=>'Ink Filled Deboss - 50 min. qty', 'types'=>array(1,2,3,4,5,6,7,8)),
                    //3=>array('label'=>'Embossed - 50 min. qty', 'types'=>array(1,2,3,4,6,7,8,9)),
                    3=>array('label'=>'Embossed - 50 min. qty', 'types'=>array(1,2,3,4,6,7)),
                    //4=>array('label'=>'Colorized Emboss - 50 min. qty', 'types'=>array(1,2,3,4,6,7,8,9)),
                    4=>array('label'=>'Colorized Emboss - 50 min. qty', 'types'=>array(1,2,3,4,6,7)),
                    5=>array('label'=>'Screen Printed - 50 min. qty', 'types'=>array(1,2,3,4,5,6,7,8)),
                    7=>array('label'=>'Dual-layer - No min. qty', 'types'=>array(2,4,5,7)),
                    8=>array('label'=>'Cut-Away - No min. qty', 'types'=>array(5)),
                );
            }
            if($builder['inhouse']) {
                $styles = array(
                    6=>array('label'=>'Debossed - No min. qty', 'types'=>array(1,2,5)),
                    7=>array('label'=>'Dual-layer - No min. qty', 'types'=>array(1,2,4,5)),
                    8=>array('label'=>'Cut-Away - No min. qty', 'types'=>array(5)),
                    11=>array('label'=>'Writable - No min. qty', 'types'=>array(2)),
                    12=>array('label'=>'Writable - No min. qty', 'types'=>array(5)),
                );
                if(isDev()) {
                    $styles = array(
                        6=>array('label'=>'Debossed - No min. qty', 'types'=>array(1,2,5,6,7)),
                        7=>array('label'=>'Dual-layer - No min. qty', 'types'=>array(1,2,4,5,7)),
                        8=>array('label'=>'Cut-Away - No min. qty', 'types'=>array(5)),
                        11=>array('label'=>'Writable - No min. qty', 'types'=>array(2)),
                        12=>array('label'=>'Writable - No min. qty', 'types'=>array(5)),
                    );
                }
            }
        } else {
            //die();
            $styles = array(
                //1=>array('label'=>$debossed_label, 'types'=>array(9,10,11,13,14,15,16,17,18,19,20,21,23,24,25,26,27,28,29,30)),
                1=>array('label'=>'Debossed', 'types'=>array(9,13,16,19,24,28)),
                //2=>array('label'=>'Ink Filled Deboss - 50 min. qty', 'types'=>array(9,10,11,13,14,15,16,17,18,19,20,21,23,24,25,26,27,28,29,30)),
                2=>array('label'=>'Ink Filled Deboss', 'types'=>array(9,13,16,19,24,28)),
                //3=>array('label'=>'Embossed - 50 min. qty', 'types'=>array(1,2,3,4,6,7,8,9)),
                //3=>array('label'=>'Embossed - 50 min. qty', 'types'=>array(9,10,11,13,14,15,16,17,18,19,20,21,23,24,25,26,27,28,29,30)),
                3=>array('label'=>'Embossed', 'types'=>array(9,13,16,19,24,28)),
                //4=>array('label'=>'Colorized Emboss - 50 min. qty', 'types'=>array(1,2,3,4,6,7,8,9)),
                //4=>array('label'=>'Colorized Emboss - 50 min. qty', 'types'=>array(9,10,11,13,14,15,16,17,18,19,20,21,23,24,25,26,27,28,29,30)),
                4=>array('label'=>'Colorized Emboss', 'types'=>array(9,13,16,19,24,28)),
                //5=>array('label'=>'Screen Printed - 50 min. qty', 'types'=>array(9,10,11,13,14,15,16,17,18,19,20,21,23,24,25,26,27,28,29,30)),
                5=>array('label'=>'Screen Printed', 'types'=>array(9,13,16,19,24,28)),
                //9=>array('label'=>'Blank - 50 min. qty', 'types'=>array(9,10,11,13,14,15,16,17,18,19,20,21,23,24,25,26,27,28,29,30)),
                9=>array('label'=>'Blank', 'types'=>array(9,13,16,19,24,28)),
            );
            if($builder['inhouse']) {
                //die();
                $styles = array(
                    6=>array('label'=>'Debossed - No min. qty', 'types'=>array(12)),
                );
            }
        }
        /*
        }
        */

        $style = 5;

        $items = array();

        //var_dump($s);die();
        //var_dump(array_flip($style['types']));die();

        if(!$builder['inhouse']) {
            //die();
            $style = $styles[$s];
            //var_dump($s);die();
            //var_dump($styles);die();
            //var_dump($style);die();
            $items = array_intersect_key($rows,
                        array_flip($style['types']));
            //var_dump($items);die();
        } else {
            $styles = array(
                6=>array('label'=>'Debossed - No min. qty', 'types'=>array(1,2,5,9,11,16,18)),
                7=>array('label'=>'Dual-layer - No min. qty', 'types'=>array(1,2,4,5)),
                8=>array('label'=>'Cut-Away - No min. qty', 'types'=>array(5)),
            );
            if(isDev()) {
                $styles = array(
                    6=>array('label'=>'Debossed - No min. qty', 'types'=>array(1,2,5,6,7,9,11,16,18)),
                    7=>array('label'=>'Dual-layer - No min. qty', 'types'=>array(1,2,4,5,7)),
                    8=>array('label'=>'Cut-Away - No min. qty', 'types'=>array(5)),
                );
            }
            $intersect = array(1=>0, 2=>1, 4=>2, 5=>3/*, 12=>4, 22=>5*/);
            if(isDev()) {
                $intersect = array(1=>0, 2=>1, 4=>2, 5=>3, 6=>4, 7=>5/*, 12=>4, 22=>5*/);
            }
            if($builder['writable']) {
                $styles = array(
                    6=>array('label'=>'Debossed - No min. qty', 'types'=>array(9,11,16,18)),
                );
                $intersect = array(9=>0, 11=>2, 16=>3, 18=>4);
            }
            if($builder['cl']) {
                $styles = array(
                    6=>array('label'=>'Debossed - No min. qty', 'types'=>array(31,32,33)),
                );
                $intersect = array(31=>1, 32=>2, 33=>3);
            }
            /*
            if($s == 6) {
                $intersect = array(2=>0, 5=>2);
            }
            */
            $items = array_intersect_key($rows,
                        $intersect);
        }

        //var_dump($items);die();





        $html = '';
        $values = array();

        $title = 'Choose product type...';

        $values[] = array(0, $title);
        $sOpt = 0;
        $i=1;
        foreach($items as $item) {
            $values[] = array($item['id'], $item['name']);
            if($sItem == $item['id']) {
                $sOpt = $i;
            }

            if($builder['writable']) {
                $btype = $types_module->moduleData['id'][$item['id']]['base_type'];
                $btitems = $vars['db']['handler']->getData($vars, $types_module->moduleTable, 'id', '`base_type`='.$btype, 'id', false);

                //var_dump($btitems);die();
                $witems = array_keys($btitems);
                //var_dump($witems);die();
                if(in_array($sItem, $witems)) {
                    //var_dump($witems);die();
                    $sOpt = $i;
                }
            }

            $i++;
        }

        $ajax_call = tpt_ajax::getCall('bandtype.change_band_type_sb');

        return tpt_html::createSelect($vars, '', $values, $sOpt, ' title="'.$title.'" id="product_type_select" onfocus="removeClass(this, \'invalid_field\');" onchange="valid_change(document.getElementById(\'tpt_pg_type\'), this);'.$ajax_call.'"');
        //return tpt_html::createSelect($vars, 'band_color', $values, $selectedColor, ' title="'.$title.'"');

    }


	function Admin_Filters_Select(&$vars, $sItem, $htmlAttribs='') {
		//die();
		$otbl = ORDERS_TABLE;
		$ptbl = ORDERS_PRODUCTS_TABLE;

		$items = $vars['db']['handler']->getData($vars, $this->moduleTable, '`id`, `name`', ' `base_type`=0 AND `enabled`=1');
		//$items = $this->moduleData['id'];

		$html = '';
		$values = array();

		$title = '-- Product Type --';

		$values[] = array('null', $title);

		$sOpt=0;
		$i=1;
		foreach($items as $item) {
			$values[] = array($item['id'], $item['name']);

			if($sItem == $item['id'])
				$sOpt = $i;

			$i++;
		}

		$valuesDelimiter = "\n";

		return tpt_html::createSelect($vars, 'tables['.$ptbl.'][type][exists]', $values, $sOpt, ' id="type_select" title="'.$title.'" onchange="filters_submit(this, document.getElementById(\'filter\'));" '.$htmlAttribs);
		//return tpt_html::createSelect($vars, 'band_color', $values, $selectedColor, ' title="'.$title.'"');

		return $html;
	}

}
