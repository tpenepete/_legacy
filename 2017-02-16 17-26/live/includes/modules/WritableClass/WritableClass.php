<?php

defined('TPT_INIT') or die('access denied');



define('DEFAULT_WRITABLE_CLASS', 1);


class tpt_module_WritableClass extends tpt_Module {
    public $wrt = array();

    function __construct(&$vars, $name, $moduleClassFile, $moduleClass, $moduleTable) {
        $fields = array(
                //db field name|field type|length|options|storage options|control|ctrAttr|default|label|index by|split keys|template
            new tpt_ModuleField('id',    'n', null, 'ai', '',         'sp', '', '', '',           true, false,  'LC'),
            new tpt_ModuleField('name',  's', 255,  '',   '',         'tf', ' style="width: 170px;"', '', 'Band Type Name', false, false, 'LC'),
            //'<div class="tpt_admin_module_section float-left" style="border: 2px solid #FFF;">',
            new tpt_ModuleField('molds',  'ti', '',  '',   '',         'tf', ' style="width: 170px;"', '0', 'Molds', false, false, 'LC'),
            new tpt_ModuleField('screens',  'ti', '',  '',   '',         'tf', ' style="width: 170px;"', '0', 'Screens', false, false, 'LC'),
            new tpt_ModuleField('mold_fee', 'f', '',    '',   'floatval', 'tf', ' style="width: 70px;"', '', 'Mold fee',      false, false, 'LC'),
            new tpt_ModuleField('screen_fee',  'f', '',    '',   'floatval', 'tf', ' style="width: 70px;"', '', 'Screen fee',       false, false, 'LC'),
            //'</div>',
            new tpt_ModuleField('available_types_id',   's', 255,   '',   '', 'tf', ' style="width: 100px;"', '1,2,3,4,5', 'Available styles ids', false, false, 'LC'),
            new tpt_ModuleField('text_lines_num',   'i', '',   '',   '', 'tf', ' style="width: 100px;"', '2', 'Number of Message Lines', false, false, 'LC'),
            new tpt_ModuleField('text_back_msg',   'ti', '',   '',   '', 'tf', ' style="width: 100px;"', '1', 'Back Message Applicable', false, false, 'LC'),
            new tpt_ModuleField('text_continuous_msg',   'ti', '',   '',   '', 'tf', ' style="width: 100px;"', '0', 'Continuous by Default', false, false, 'LC'),
            new tpt_ModuleField('sku_comp',  's', 16,  '',   '',         'tf', ' style="width: 170px;"', '', 'Sku Component', false, false, 'LC'),
            new tpt_ModuleField('label2',  's', 255,  '',   '',         'tf', ' style="width: 170px;"', '', 'Alternative name', false, false, 'LC'),
            new tpt_ModuleField('aka',  's', 64,  '',   '',         'tf', ' style="width: 170px;"', '', 'Alternative names2', false, false, 'LC'),
            new tpt_ModuleField('writable',  'i', '',  '',   '',         'tf', ' style="width: 170px;"', '0', 'Is Writable?', false, false, 'LC'),
            new tpt_ModuleField('full_wrap_strip',  'ti', '',  '',   '',         'tf', ' style="width: 170px;"', '0', 'Full Wrap Strip Writable', false, false, 'LC'),
            new tpt_ModuleField('writable_strip_position',  'i', '',  '',   '',         'tf', ' style="width: 170px;"', '0', 'Full Wrap Strip Writable', false, false, 'LC'),
            new tpt_ModuleField('blank',  'ti', '',  '',   '',         'tf', ' style="width: 170px;"', '0', 'Blank Band', false, false, 'LC'),
            new tpt_ModuleField('status',  'ti', '',  '',   '',         'tf', ' style="width: 170px;"', '1', 'Status', false, false, 'LC'),
            //'<div class="float-left padding-top-20 padding-bottom-20 padding-left-10 padding-right-10" style="background-color: #FFF;"><div class="display-inline-block height-10 width-80" style="background-color: #`HEX`; border: 1px solid #000;"></div></div>',
            //'<div class="float-left" style="background-color: #`HEX`; border: 1px solid #000;"><img src="'.$vars['config']['resourceurl'].'/images/Band-Transperent-Preview.png" class="width-80" /></div>',
            //'<div class="float-left" style="background-color: #`HEX`; border: 1px solid #000;"><img src="'.$vars['config']['resourceurl'].'/images/Transparent-Swirl-Band-Preview.png" class="width-80" /></div>'
        );
	
	
        $w = $vars['db']['handler']->getData($vars, 'tpt_module_banddata', '*', ' `writable`!=0 ORDER BY `writable_class` ASC', 'id', false);
	
        foreach($w as $wr) {
            if(empty($this->wrt[$wr['type']]) || !is_array($this->wrt[$wr['type']])) {
                $this->wrt[$wr['type']] = array();
            }
            $this->wrt[$wr['type']] = $wr;
        }
	//tpt_dump($this->wrt, true);
	
        parent::__construct($vars, $name, $moduleClassFile, $moduleClass, $moduleTable, $fields, 'id');
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



    function BandClass_Select(&$vars) {


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

    function getWritableTypesFromType(&$vars, $t, $s=0) {
	$types_module = getModule($vars, "BandType");
	$data_module = getModule($vars, "BandData");

	//tpt_dump($t);
	//tpt_dump($s, true);
	if(empty($t))
	    return false;

	//if(!empty($s)) {
	//    $btype = $data_module->typeStyle[$t][$s]['base_type'];
	//} else {
	    $btype = $this->wrt[$t]['base_type'];
	    //tpt_dump($btype, true);
	    //$btype = $btype['base_type'];
	//}
	//tpt_dump($btype, true);
	if(empty($btype)) {
	    return false;
	}

	
	$btitems = $vars['db']['handler']->getData($vars, $data_module->moduleTable, '*', '`base_type`='.$btype, 'writable_class', false);
//tpt_dump($btitems, true);
	return $btitems;
    }

    function getClassesFromType(&$vars, $t) {
	if(empty($t))
	    return false;

	$btype = $types_module->moduleData['id'][$t]['base_type'];

	if(empty($btype)) {
	    return false;
	}

	$btitems = $vars['db']['handler']->getData($vars, $types_module->moduleTable, 'writable_class', '`base_type`='.$btype, 'writable_class', false);

	$allowed_ids = implode(',', array_keys($btitems));
	$items = $vars['db']['handler']->getData($vars, $this->moduleTable, '*', '`id` IN ('.$allowed_ids.')');

	return $items;
    }

    function WritableClass_Select_SB(&$vars, $t, $s, $sItem = 0, $inhouse = 0) {
	//die();
	if(empty($t))
	    return '';
	$types_module = getModule($vars, "BandType");
	$data_module = getModule($vars, "BandData");
	$btype = $data_module->typeStyle[$t][$s]['base_type'];
	$writable = $data_module->typeStyle[$t][$s]['writable'];
	if(empty($btype) || empty($writable)) {
	    return '';
	}


	$btitems = $vars['db']['handler']->getData($vars, $data_module->moduleTable, 'writable_class', '`base_type`='.$btype, 'writable_class', false);
	$iitems = $vars['db']['handler']->getData($vars, $data_module->moduleTable, 'id', '`base_type`='.$btype, 'id', false);
	$iitems = array_keys($iitems);
	//tpt_dump($btitems, true);
	$btkeys = array_keys($btitems);
	if(($key = array_search(5, $btkeys)) !== false) {
	    unset($btkeys[$key]);
	}
	$allowed_ids = implode(',', $btkeys);
	$items = $vars['db']['handler']->getData($vars, $this->moduleTable, 'id,name', '`id` IN ('.$allowed_ids.')');

	$content = '';
	$i = 0;

        foreach($items as $item) {

	    $checked = '';
            if($item['id'] == $sItem) {
		$checked = ' checked="checked"';
	    }
	    if((($i == 0) && empty($sItem)) || (($i == 0) && (!in_array($sItem, $iitems)))) {
		$checked = ' checked="checked"';
	    }

	    $content .= '<input onclick="document.getElementById(\'tpt_pg_class\').value = this.value;goGetSome(\'bandtype.change_band_type_sb\', this.form);" type="radio" name="bclass" value="'.$item['id'].'" '.$checked.' />&nbsp;'.$item['name'].'<br />';

	    $i++;
        }

	return $content;
	/*
        $rows = $this->moduleData['id'];


        //var_dump($writable);die();
        if(!$writable) {
            //die();
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
            if($inhouse) {
                $types = array(
                    1=>array('label'=>'12', 'styles'=>array(6,7)),
                    2=>array('label'=>'12', 'styles'=>array(6,7)),
                    4=>array('label'=>'1', 'styles'=>array(7)),
                    5=>array('label'=>'slap', 'styles'=>array(6,7,8)),
                );
            }
        } else {
            //die('asdasasdasasddas');
            $types = array(
                9=>array('label'=>'12WB', 'styles'=>array(1,2,3,4,5)),
                10=>array('label'=>'12WFWS', 'styles'=>array(1,2,3,4,5)),
                11=>array('label'=>'12WBBM', 'styles'=>array(1,2,3,4,5)),
                13=>array('label'=>'19WB', 'styles'=>array(1,2,3,4,5)),
                14=>array('label'=>'19WFWS', 'styles'=>array(1,2,3,4,5)),
                15=>array('label'=>'19WBBM', 'styles'=>array(1,2,3,4,5)),
                16=>array('label'=>'24WB', 'styles'=>array(1,2,3,4,5)),
                17=>array('label'=>'24WFWS', 'styles'=>array(1,2,3,4,5)),
                18=>array('label'=>'24WBBM', 'styles'=>array(1,2,3,4,5)),
                19=>array('label'=>'WRTBFM-SLAP', 'styles'=>array(1,2,3,4,5)),
                20=>array('label'=>'WRTBBM-SLAP', 'styles'=>array(1,2,3,4,5)),
                21=>array('label'=>'WRTB-SLAP', 'styles'=>array(1,2,3,4,5)),
                23=>array('label'=>'WRTFWS-SLAP', 'styles'=>array(9)),
                24=>array('label'=>'WRTBFM-12SNAP', 'styles'=>array(1,2,3,4,5)),
                25=>array('label'=>'WRTBBM-12SNAP', 'styles'=>array(1,2,3,4,5)),
                26=>array('label'=>'WRTB-12SNAP', 'styles'=>array(9)),
                27=>array('label'=>'WRTFWS-12SNAP', 'styles'=>array(9)),
                28=>array('label'=>'WRTB-CHAIN', 'styles'=>array(9)),
                29=>array('label'=>'WRTFWS-CHAIN', 'styles'=>array(9)),
                30=>array('label'=>'WRTBBM-CHAIN', 'styles'=>array(1,2,3,4,5)),
            );
            if($inhouse) {
                $types = array(
                    9=>array('label'=>'12WB', 'styles'=>array()),
                    11=>array('label'=>'12WBBM', 'styles'=>array(6)),
                );
            }
        }


        if(!$writable) {
            $styles = array(
                1=>array('label'=>$debossed_label, 'types'=>array(1,2,3,4,5,6,7,8)),
                2=>array('label'=>'Ink Filled Deboss - 50 min. qty', 'types'=>array(1,2,3,4,5,6,7,8)),
                //3=>array('label'=>'Embossed - 50 min. qty', 'types'=>array(1,2,3,4,6,7,8,9)),
                3=>array('label'=>'Embossed - 50 min. qty', 'types'=>array(1,2,3,4,6,7)),
                //4=>array('label'=>'Colorized Emboss - 50 min. qty', 'types'=>array(1,2,3,4,6,7,8,9)),
                4=>array('label'=>'Colorized Emboss - 50 min. qty', 'types'=>array(1,2,3,4,6,7)),
                5=>array('label'=>'Screen Printed - 50 min. qty', 'types'=>array(1,2,3,4,5,6,7,8)),
                7=>array('label'=>'Dual-layer - No min. qty', 'types'=>array(2,4,5)),
                8=>array('label'=>'Cut-Away - No min. qty', 'types'=>array(5)),
            );
            if($inhouse) {
                $styles = array(
                    6=>array('label'=>'Debossed - No min. qty', 'types'=>array(1,2,5,12)),
                    7=>array('label'=>'Dual-layer - No min. qty', 'types'=>array(1,2,4,5)),
                    8=>array('label'=>'Cut-Away - No min. qty', 'types'=>array(5)),
                );
            }
        } else {
            //die();
            $styles = array(
                1=>array('label'=>$debossed_label, 'types'=>array(9,10,11,13,14,15,16,17,18,19,20,21,23,24,25,26,27,28,29,30)),
                2=>array('label'=>'Ink Filled Deboss - 50 min. qty', 'types'=>array(9,10,11,13,14,15,16,17,18,19,20,21,23,24,25,26,27,28,29,30)),
                //3=>array('label'=>'Embossed - 50 min. qty', 'types'=>array(1,2,3,4,6,7,8,9)),
                3=>array('label'=>'Embossed - 50 min. qty', 'types'=>array(9,10,11,13,14,15,16,17,18,19,20,21,23,24,25,26,27,28,29,30)),
                //4=>array('label'=>'Colorized Emboss - 50 min. qty', 'types'=>array(1,2,3,4,6,7,8,9)),
                4=>array('label'=>'Colorized Emboss - 50 min. qty', 'types'=>array(9,10,11,13,14,15,16,17,18,19,20,21,23,24,25,26,27,28,29,30)),
                5=>array('label'=>'Screen Printed - 50 min. qty', 'types'=>array(9,10,11,13,14,15,16,17,18,19,20,21,23,24,25,26,27,28,29,30)),
                9=>array('label'=>'Blank - 50 min. qty', 'types'=>array(9,10,11,13,14,15,16,17,18,19,20,21,23,24,25,26,27,28,29,30)),
            );
            if($inhouse) {
                //die();
                $styles = array(
                    6=>array('label'=>'Debossed - No min. qty', 'types'=>array(12)),
                );
            }
        }

        $style = 5;

        $items = array();

        //var_dump($s);die();
        //var_dump(array_flip($style['types']));die();

        if(!$inhouse) {
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
                6=>array('label'=>'Debossed - No min. qty', 'types'=>array(1,2,5,9,11)),
                7=>array('label'=>'Dual-layer - No min. qty', 'types'=>array(1,2,4,5)),
                8=>array('label'=>'Cut-Away - No min. qty', 'types'=>array(5)),
            );
            $intersect = array(1=>0, 2=>1, 4=>2, 5=>3, 12=>5);
            if($writable) {
                $styles = array(
                    6=>array('label'=>'Debossed - No min. qty', 'types'=>array(9,11)),
                );
                $intersect = array(9=>0, 11=>2);
            }
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
            if($sItem == $item['id'])
                $sOpt = $i;

            $i++;
        }

        $ajax_call = tpt_ajax::getCall('bandtype.change_band_type_sb');

        return tpt_html::createSelect($vars, '', $values, $sOpt, ' title="'.$title.'" id="product_type_select" onfocus="removeClass(this, \'invalid_field\');" onchange="valid_change(document.getElementById(\'tpt_pg_type\'), this);'.$ajax_call.'"');
        //return tpt_html::createSelect($vars, 'band_color', $values, $selectedColor, ' title="'.$title.'"');
	*/


    }



	function WritableClass_PlainSelectSDN(&$vars, $sItem, $pid='null') {
		$items = $this->moduleData['id'];
		$html = '';
		$values = array();

		$title = 'Choose writable class...';

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

		return tpt_html::createSelect($vars, '', $values, $sOpt, ' autocomplete="off" id="'.$pid.'_control_writable_class" title="'.$title.'" onfocus="removeClass(this, \'invalid_field\');" onchange="update_product_row(this);"');
		//return tpt_html::createSelect($vars, 'band_color', $values, $selectedColor, ' title="'.$title.'"');

		//return $html;
	}

}
