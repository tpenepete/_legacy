<?php
defined('TPT_INIT') or die('access denied');

class tpt_module_BandStyle extends tpt_Module {
    public $moduleDataSort;

    function __construct(&$vars, $name, $moduleClassFile, $moduleClass, $moduleTable) {
        $fields = array(
                //db field name|field type|length|options|storage options|control|ctrAttr|default|label|index by|split keys|template
            new tpt_ModuleField('id',    'n', null, 'ai', '',         'sp', '', '', '',           true, false,  'LC'),
            new tpt_ModuleField('name',  's', 255,  '',   '',         'tf', ' style="width: 170px;"', '', 'Band Style Name', true, false, 'LC'),
            new tpt_ModuleField('mold',   'ti', '',    '',   'intval10', 'tf', ' style="width: 70px;"', '', 'Has mold?',        false, false, 'LC'),
            new tpt_ModuleField('screen',   'ti', '',    '',   'intval10', 'tf', ' style="width: 70px;"', '', 'Has screen?',        false, false, 'LC'),
            new tpt_ModuleField('message_relief',   'i', '',    '',   'intval10', 'tf', ' style="width: 70px;"', '-1', 'Message Relief Type',        false, false, 'LC'),
            new tpt_ModuleField('sku_comp',  's', 16,  '',   '',         'tf', ' style="width: 170px;"', '', 'Sku Component', false, false, 'LC'),
            new tpt_ModuleField('blank',   'ti', '',    '',   'intval10', 'tf', ' style="width: 70px;"', '0', 'Blank Band',        false, false, 'LC'),
            new tpt_ModuleField('message_color',   'ti', '',    '',   'intval10', 'tf', ' style="width: 70px;"', '0', 'Has message color?',        false, false, 'LC'),
			new tpt_ModuleField('writable',   'i', '',   '',   '', 'tf', ' style="width: 230px;"', 0, 'Writable', false, false, 'LC'),
            new tpt_ModuleField('aka',  's', 64,  '',   '',         'tf', ' style="width: 170px;"', '', 'Alternative Names', false, false, 'LC'),
            new tpt_ModuleField('sdesc',  's', 32,  '',   '',         'tf', ' style="width: 170px;"', '', 'Short Desc', false, false, 'LC'),
        );
        parent::__construct($vars, $name, $moduleClassFile, $moduleClass, $moduleTable, $fields, 'id');
        
        /*
        $isprow = array($this->moduleData['id'][16]);
        $values = array_values($this->moduleData['id']);
        $keys = array_keys($this->moduleData['id']);
        $ispkey = array_search(16, $keys);
        unset($values[$ispkey]);
        array_splice($values, 5, 0, $isprow);
        unset($keys[$ispkey]);
        array_splice($keys, 5, 0, 16);
        $this->moduleDataSort = array_combine($keys, $values);
        */
        $this->moduleDataSort = array_rearrange_key($this->moduleData['id'], 16, 5);
        
        //$this->moduleDataSort = $this->moduleData['id'];
        //$isp = $this->moduleDataSort[16];
        //unset($this->moduleDataSort[16]);
        //tpt_dump($this->moduleDataSort, true);
        //array_insert($this->moduleDataSort, 6, $isp);
        //tpt_dump($this->moduleDataSort, true);
        
        //$this->moduleDataSort = $this->moduleData['id'];
    }

	function _convertOldData(&$vars, $style, &$quote_result) {
		$stable = $this->moduleTable;

		$styleid = 0;
		if(!empty($quote_result['id'])) {
			$qid = intval($quote_result['id'], 10);

			$sq = <<< EOT
		SELECT * FROM `temp_custom_order_products` WHERE `order_id`=$qid
EOT;
			$vars['db']['handler']->query($sq);
			$product = $vars['db']['handler']->fetch_assoc();
			if(!empty($product)) {
				$styleid = $product['style'];
			}
		} else {
			$style = mysql_real_escape_string($style);
			$sq = <<< EOT
		SELECT `id` FROM `$stable` WHERE FIND_IN_SET("$style", CONCAT(`id`, ",", COALESCE(`name`, `id`), ",", COALESCE(`aka`, `id`)))
EOT;
			$vars['db']['handler']->query($sq);
			$sres = $vars['db']['handler']->fetch_assoc_list('id', false);
			if (!empty($sres)) {
				$styleid = reset($styleid);
			}
		}

		return $styleid;
	}

    function userEndData(&$vars) {
        $_temp = array();
        $rArr = $this->moduleData['id'];
        foreach($rArr as $item) {
            $_temp[$item['id']] = array('name'=>$item['name'], 'message_color'=>$item['message_color']);
        }
        //var_dump($rArr);die();

        $rArr = $_temp;
        //var_dump($rArr);die();
        return $rArr;
    }

	function getDefaultItem(&$vars, $input, $options) {
		$types_module = getModule($vars, 'BandType');
		$stype = $types_module->getDefaultItem($vars, $input, $options);
		$stdstyle = $types_module->moduleData['id'][$stype]['default_style'];
		if(!empty($options['inhouse'])) {
			$stdstyle = $types_module->moduleData['id'][$stype]['default_style2'];
		}

		//tpt_dump($options);
		//tpt_dump($stdstyle);

		$items = $this->getItems($vars, $input, $options);
		$items_ids = array_keys($items);
		reset($items);
		$fitem = key($items);

		return ((isset($input['style'])&&(in_array($input['style'], array_keys($items)))?$input['style']:(in_array($stdstyle, array_keys($items))?$stdstyle:(in_array(DEFAULT_STYLE, array_keys($items))?DEFAULT_STYLE:$fitem))));
		//tpt_dump($items);
		//tpt_dump($stdstyle);
		//tpt_dump($sitem);

	}
	function getSelectedItem(&$vars, $input, $options) {
		$items = $this->getItems($vars, $input, $options);

		return ((isset($input['style'])&&in_array($input['style'], array_keys($items)))?$input['style']:((count($items)===1)?key($items):0));
	}
	function getActiveItem(&$vars, $input, $options) {
		$items = $this->getItems($vars, $input, $options);
		$ditem = $this->getDefaultItem($vars, $input, $options);
		
		$sItem = ((isset($input['style'])&&(in_array($input['style'], array_keys($items)))?$input['style']:$ditem));

		//tpt_dump($sItem);
		//tpt_dump($ditem);
		//tpt_dump($input['style']);
		//tpt_dump(array_keys($items));
		//tpt_dump((in_array($input['style'], array_keys($items))));

		return $sItem;
	}
	function getItems(&$vars, $input, $options) {
		$types_module = getModule($vars, 'BandType');
		$data_module = getModule($vars, 'BandData');

		//$items = $this->moduleData['id'];
		//$items = (!empty($options['style'])?array_combine(explode(',',$options['style']),explode(',',$options['style'])):$items);
		//$items = array_intersect_key($this->moduleData['id'], $items);
		//$items = (!empty($items)?$items:$this->moduleData['id']);

		$stype = $types_module->getActiveItem($vars, $input, $options);
		$items = $this->moduleData['id'];
		$aitems = array_keys($data_module->typeStyle[$stype]);

		$aitems = array_combine($aitems, $aitems);
		$aitems = array_intersect_key($items, $aitems);
		//tpt_dump($aitems, true);
		if(!empty($aitems)) {
			$items = $aitems;
		}

		return $items;
	}

	function CartView_Value(&$vars, $section, $input=array(), $options=array(), &$vinput=array()) {
		return $this->moduleData['id'][$input[$section['pname']]]['name'];
	}
	function SB_Section(&$vars, $section, $input=array(), $options=array(), &$vinput=array()) {
		$items = $this->getItems($vars, $input, $options);

		$sItem = $this->getSelectedItem($vars, $input, $options);

		tpt_dump($sItem, false, 'R');
		tpt_dump($sItem, false, 'R');
		tpt_dump($options, false, 'R');

		$html = '';
		$values = array();

		$title = 'Choose message style...';

		if(count($items)>1 || (count($items) === 0)) {
			$values[] = array(0, $title);
		}
		$sOpt = 0;
		$i=1;
		//tpt_dump($items, true);
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
		$html .= '<input type="hidden" id="style" name="style" value="'.$sItem.'" />';

		return $html;
	}

    function BandStyle_Panel_DC(&$vars) {
        $tpt_imagesurl = TPT_IMAGES_URL;

        $html = '';

        $types = array(
            1=>array('label'=>'14', 'styles'=>array(1,2,3,4,5)),
            2=>array('label'=>'12', 'styles'=>array(1,2,3,4,5,7)),
            3=>array('label'=>'34', 'styles'=>array(1,2,3,4,5)),
            4=>array('label'=>'1', 'styles'=>array(1,2,3,4,5,7)),
            5=>array('label'=>'slap', 'styles'=>array(1,2,5,8)),
            6=>array('label'=>'snap', 'styles'=>array(1,2,3,4,5)),
            7=>array('label'=>'chain', 'styles'=>array(1,2,3,4,5)),
            //8=>array('label'=>'ring', 'styles'=>array(1,2,3,4,5)),
            8=>array('label'=>'ring', 'styles'=>array(1,2,5)),
            9=>array('label'=>'usb', 'styles'=>array(1,2,3,4,5)),
        );

        $styles = array(
            1=>array(1,2,3,4,6,7,8,9),
            2=>array(1,2,3,4,6,7,8,9),
            //3=>array(1,2,3,4,6,7,8,9),
            3=>array(1,2,3,4,6,7,9),
            //4=>array(1,2,3,4,6,7,8,9),
            4=>array(1,2,3,4,6,7,9),
            5=>array(1,2,3,4,5,6,7,8,9),
            7=>array(1,2),
            8=>array(5),
        );

        $stylesheet = '';
        $stylesheet .= '#design_center_content_step_2 .styleStepOptions {display: none;}'."\n";
        $stylesheet .= '#design_center_content_step_2.zType_All_ID .styleStepOptions {display: block;}'."\n";
        foreach($types as $ptype) {
            $stylesheet .= '#design_center_content_step_2.zType_'.$ptype['label'].'_ID .styleStepOptions.zType_'.$ptype['label'].'_ID {display: block;}'."\n";
        }

        $vars['template_data']['head'][] = <<< EOT
        <style type="text/css">
        $stylesheet
        </style>
EOT;

        $items = $this->moduleData['id'];

        foreach($types as $tkey=>$type) {
            $i = 0;
            $html .= '<div class="styleStepOptions zType_'.$type['label'].'_ID">';

            $item_num = 0;
            $so_margin_top    = array(1 => '0',  2 => '0',  3 => '0',  4 => '20', 5 => '20', 6 => '20');
            $so_margin_bottom = array(1 => '20', 2 => '20', 3 => '20', 4 => '0',  5 => '0',  6 => '0');
            $so_max_height    = array('14' => '70',  '12' => '90',  '34' => '105', '1' => '125', 'snap' => '65',  'chain' => '130', 'ring' => '90',  'slap' => '100');
            $so_min_width     = array('14' => '190', '12' => '190', '34' => '190', '1' => '190', 'snap' => '622', 'chain' => '301', 'ring' => '190', 'slap' => '624');
            foreach($type['styles'] as $item) {
                $item_num++;
                $link = '';

                $is_new_line_needed = '';
                if($item_num % 4 === 0) {
                    $is_new_line_needed .= '<div class="clearFix"></div>';
                }

                $cur_id = 'step_two_'.$type['label'].'_link_'.$item_num;
                $cur_label = $type['label'];

                if (($cur_label == '12' || $cur_label == '1') && $item_num == 4)
                {
                    $cs_margin_left = '35px';
                }
                else
                    $cs_margin_left = '20px';

                if(!empty($item)) {
                    $id = $items[$item]['id'];
                    $name = htmlentities($items[$item]['name']);
                    $link .= <<< EOT
                    $is_new_line_needed
                    <a rel="tooltip[tooltip_slarge_$tkey:$id]" title="$name" style="margin-left:$cs_margin_left; margin-right: 20px; min-width: $so_min_width[$cur_label]px; max-height: $so_max_height[$cur_label]px; margin-top: $so_margin_top[$item_num]px; margin-bottom: $so_margin_bottom[$item_num]px;" class="stylePanelOption amz_brown text-decoration-none height-148 font-size-16 display-inline-block text-align-left todayshop-bold" href="javascript:void(0);" onclick="change_product_style('$cur_id');"  id="$cur_id">
                        <span class="display-block position-relative">
                            <span class="display-block position-relative padding-left-5 padding-right-5 padding-top-5 padding-bottom-5" style="z-index: 2;">
                                <img src="$tpt_imagesurl/design_center/types/$tkey/style_$id.png" />
                                <br />
                                <span class=""><input type="radio" name="trigger_band_style" value="$id" onclick="change_product_style(this);" /> $name</span>
                            </span>

                            <span class="stylePanelOptionBG display-block position-absolute top-0 right-0 bottom-0 left-0" style="z-index: 1;">
                                <span class="display-block position-absolute top-0 right-0 left-0 height-10 padding-left-10 background-repeat-no-repeat background-position-LT" style="background-image: url($tpt_imagesurl/bbox/bbox-tl-10x10.png);">
                                    <span class="display-block padding-right-10 background-repeat-no-repeat background-position-RT" style="background-image: url($tpt_imagesurl/bbox/bbox-tr-10x10.png);">
                                        <span class="display-block height-10 background-repeat-repeat-x background-position-CT" style="background-image: url($tpt_imagesurl/bbox/bbox-t-10x10.png);">
                                        </span>
                                    </span>
                                </span>
                                <span class="display-block position-absolute top-10 right-0 left-0 bottom-10 padding-left-10 background-repeat-repeat-y background-position-LC" style="background-image: url($tpt_imagesurl/bbox/bbox-l-10x10.png);">
                                    <span class="display-block position-absolute top-0 right-0 left-0 bottom-0 padding-right-10 background-repeat-repeat-y background-position-RC" style="background-image: url($tpt_imagesurl/bbox/bbox-r-10x10.png);">
                                        <span class="display-block position-absolute top-0 right-10 left-10 bottom-0 background-repeat-repeat-y background-position-CC" style="background-color: #b1eeea;">
                                        </span>
                                    </span>
                                </span>
                                <span class="display-block position-absolute bottom-0 right-0 left-0 height-10 padding-left-10 background-repeat-no-repeat background-position-LB" style="background-image: url($tpt_imagesurl/bbox/bbox-bl-10x10.png);">
                                    <span class="display-block padding-right-10 background-repeat-no-repeat background-position-RB" style="background-image: url($tpt_imagesurl/bbox/bbox-br-10x10.png);">
                                        <span class="display-block height-10 background-repeat-repeat-x background-position-CB" style="background-image: url($tpt_imagesurl/bbox/bbox-b-10x10.png);">
                                        </span>
                                    </span>
                                </span>
                            </span>
                        </span>
                    </a>
EOT;

                $vars['template']['tooltips'] .= <<< EOT
<div id="tooltip_slarge_$tkey:$id" class="tooltip-wrapper hidden">
    <div class="tooltip-content">
        <img src="$tpt_imagesurl/design_center/types/$tkey/large/large_$id.jpg" />
    </div>
</div>
EOT;
                }

                /*
                if(($i % 4 == 3) || ($i == count($type['styles']) -1)) {
                    $link .= '</div>';
                }
                */
                $html .= $link;

                $i++;
            }
            $html .= '</div>';
        }

        //var_dump($html);die();
        return $html;
    }


    function BandStyle_Select(&$vars) {

        $items = $vars['db']['handler']->getData($vars, $this->moduleTable, 'id,name');

        $html = '';
        $values = array();

        $title = 'Choose message style...';

        $i=1;
        foreach($items as $item) {
            if($i==1) {
                $values[] = array(1, '<div class="amz_brown font-size-18 height-15 padding-left-2 padding-right-2 line-height-15 white-space-nowrap" style="font-family: TODAYSHOP-BOLDITALIC,arial;"'./* style="border: 1px solid #555;background-color: #FFF;"*/'>'.$title.'</div>', $title);
                $i=0;
            }
            $values[] = array($item['id'], '<div class="height-15 padding-left-2 padding-right-2 line-height-15 white-space-nowrap" style="border: 1px solid #555;background-color: #FFF;">'.$item['name'].'</div>', $item['name']);
        }

        $valuesDelimiter = "\n";

        $html = tpt_html::createStyledSelect($vars, 'BandStyle', $values, $valuesDelimiter, ' display-block', ' width:210px;', ' width:202px;', ' padding-top-10', 0, '_debossed_tpt_pg_generate_prevew_all', 'tpt_pg_style', ' title="'.$title.'"');

        return $html;
    }


    function BandStyle_PlainSelect(&$vars) {
        $items = $vars['db']['handler']->getData($vars, $this->moduleTable, 'id,name');

        $html = '';
        $values = array();

        $title = 'Choose message style...';

        $values[] = array(0, $title);
        $i=0;
        $i=1;
        foreach($items as $item) {
            $values[] = array($item['id'], $item['name']);
        }

        return tpt_html::createSelect($vars, '', $values, 0, ' title="'.$title.'" onchange="document.getElementById(\'tpt_pg_style\').value = this.value; _short_tpt_pg_generate_prevew_all();"');
        //return tpt_html::createSelect($vars, 'band_color', $values, $selectedColor, ' title="'.$title.'"');

    }
	
	// !! this function makes updates to tpt_orders_products !!! please check it carefully before using it
    function BandStyle_Select_Admin(&$vars, $quote_result) {
		
		static $tpt_module_bandstyle;
		
		if (empty($quote_result['id'])) return;
		
		$db = $vars['db']['handler'];
		$ss_name = 'b_style_'.$quote_result['id'];
		$ss_name_id = $ss_name.'_SID_';
			
		if (empty($tpt_module_bandstyle)) {
			$tpt_module_bandstyle = $db->getData($vars, $this->moduleTable, '*', '1 ORDER BY `name`', false, false);
		}
		
		$t_o_p = $db->getData($vars,'tpt_orders_products','`type`,`style`','old_quote_id = '.$quote_result['id'],false,false);
		$selected = @$t_o_p[0]['style'];
		
		if (!empty($_POST[$ss_name_id])) {
			
			if ($_POST[$ss_name_id]!=$selected) {
				
				// !!! UPDATING tpt_orders_products table !!!
				$db->query('UPDATE tpt_orders_products 
				SET `style`='.(int)$_POST[$ss_name_id].' 
				WHERE `old_quote_id` = '.$quote_result['id']);
				
			//	var_dump('!!! UPDATING');
				$selected = $_POST[$ss_name_id];
			}
			
		}
				
		$unique_items = array();
		$s_items = array();
		
		foreach ($tpt_module_bandstyle as $row) {
			$unique_items[$row['name']] = isset($unique_items[$row['name']]) ? $unique_items[$row['name']] + 1 : 1;
			$s_items[$row['id']] = $row;
		}
		
		$selhtml = '';
		
		$qrs__ = false;
		
		foreach($s_items as $id=>$si) {
			if ($unique_items[$si['name']]==1) $nm = $si['name'];
			else $nm = $si['aka'];
			$qrs__ = $qrs__ || $selected==$id;
			$COND = empty($selected) && $quote_result['wristband_style'] == $nm;
			$selhtml.= '<option class="B_STYLEOPT_'.$quote_result['id'].'_'.$id.'" '.(($selected==$id||$COND)?'selected="selected"':'').' value="'.$nm.'" >'.$nm.'</option>';
		}
		
		if ($qrs__ && empty($selected)) $quote_result['wristband_style'] = false;
		
		ob_start();
		?>
		<option <?php if($quote_result['wristband_style']=='See Design Notes') echo 'selected="selected"'; ?> value="See Design Notes" >See Design Notes</option>
		<option <?php if($quote_result['wristband_style']=='No Details') echo 'selected="selected"'; ?> value="No Details">No Details</option>
		<?php
		$blank_items = ob_get_clean();

//		var_dump(@$_POST[$ss_name]);

		$seltag1 = '<select name="'.$ss_name.'" id="'.$ss_name.'" class="style">';
		$seltag2 = '</select>';
		$seltag3 = '<input type="hidden" value="'.$selected.'" name="'.$ss_name_id.'" id="'.$ss_name_id.'">';
		$seltagjs = '<script type="text/javascript">
			$("#'.$ss_name.'").change(function(){
				try { var sid = $(this).find("option").filter(":selected").attr("class").match(/[0-9]+/g)[1];
				} catch(e) { var sid = -1; }
				$("#'.$ss_name_id.'").val(sid);
			});
		</script>
		';

		return $seltag1 . $blank_items . $selhtml . $seltag2 . $seltag3. $seltagjs;
		
	}
		
		
    function BandStyle_Select_SB(&$vars, $t, $sItem = 0, $builder) {
        //$items = $this->moduleData['id'];
        $items = $this->moduleDataSort;
        //var_dump($inhouse);die();
		//tpt_dump($sItem);die();

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
        if($inhouse) {
            $types = array(
                2=>array('label'=>'12', 'styles'=>array(6,7)),
                4=>array('label'=>'1', 'styles'=>array(7)),
                5=>array('label'=>'slap', 'styles'=>array(6)),
            );
        }
        if($_SERVER['REMOTE_ADDR'] == '109.160.0.218') {
        */
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
            9=>array('label'=>'12WB', 'styles'=>array(9)),
            10=>array('label'=>'12WFWS', 'styles'=>array(9)),
            11=>array('label'=>'12WBBM', 'styles'=>array(1,2,3,4,5)),
            //12=>array('label'=>'12WNM', 'styles'=>array(6)),
            13=>array('label'=>'19WB', 'styles'=>array(9)),
            14=>array('label'=>'19WFWS', 'styles'=>array(9)),
            15=>array('label'=>'19WBBM', 'styles'=>array(1,2,3,4,5)),
            16=>array('label'=>'24WB', 'styles'=>array(9)),
            17=>array('label'=>'24WFWS', 'styles'=>array(9)),
            18=>array('label'=>'24WBBM', 'styles'=>array(1,2,3,4,5)),
            19=>array('label'=>'WRTB-SLAP', 'styles'=>array(9)),
            20=>array('label'=>'WRTBFM-SLAP', 'styles'=>array(1,2,5)),
            21=>array('label'=>'WRTBBM-SLAP', 'styles'=>array(1,2,5)),
            //22=>array('label'=>'WRTB-SLAP', 'styles'=>array(6)),
            23=>array('label'=>'WRTFWS-SLAP', 'styles'=>array(9)),
            24=>array('label'=>'WRTB-12SNAP', 'styles'=>array(9)),
            25=>array('label'=>'WRTBFM-12SNAP', 'styles'=>array(1,2,3,4,5)),
            26=>array('label'=>'WRTBBM-12SNAP', 'styles'=>array(1,2,3,4,5)),
            27=>array('label'=>'WRTFWS-12SNAP', 'styles'=>array(9)),
            28=>array('label'=>'WRTB-CHAIN', 'styles'=>array(9)),
            29=>array('label'=>'WRTFWS-CHAIN', 'styles'=>array(9)),
            30=>array('label'=>'WRTBBM-CHAIN', 'styles'=>array(1,2,3,4,5)),
        );
        if($builder['inhouse']) {
            $types = array(
                1=>array('label'=>'14', 'styles'=>array(6,7)),
                2=>array('label'=>'12', 'styles'=>array(6,7,11)),
                4=>array('label'=>'1', 'styles'=>array(7)),
                5=>array('label'=>'slap', 'styles'=>array(6,7,8,12)),
                //9=>array('label'=>'12WB', 'styles'=>array(9)),
                //11=>array('label'=>'12WBBM', 'styles'=>array(6)),
                //12=>array('label'=>'12NM', 'styles'=>array(6)),
            );

        }
        */
        //if(isDev()) {
            $types = array(
                1=>array('label'=>'14', 'styles'=>array(1,2,3,4,5,16,7)),
                2=>array('label'=>'12', 'styles'=>array(1,2,3,4,5,16,7)),
                3=>array('label'=>'34', 'styles'=>array(1,2,3,4,5,16)),
                4=>array('label'=>'1', 'styles'=>array(1,2,3,4,5,16,7)),
                5=>array('label'=>'slap', 'styles'=>array(1,2,5,16,7,8)),
                6=>array('label'=>'snap', 'styles'=>array(1,2,3,4,5,16)),
                7=>array('label'=>'chain', 'styles'=>array(1,2,3,4,5,16,7,6)),
                //8=>array('label'=>'ring', 'styles'=>array(1,2,3,4,5)),
                8=>array('label'=>'ring', 'styles'=>array(1,2,5,16)),
                9=>array('label'=>'12WB', 'styles'=>array(9)),
                10=>array('label'=>'12WFWS', 'styles'=>array(9)),
                11=>array('label'=>'12WBBM', 'styles'=>array(1,2,3,4,5,16)),
                //12=>array('label'=>'12WNM', 'styles'=>array(6)),
                13=>array('label'=>'19WB', 'styles'=>array(9)),
                14=>array('label'=>'19WFWS', 'styles'=>array(9)),
                15=>array('label'=>'19WBBM', 'styles'=>array(1,2,3,4,5,16)),
                16=>array('label'=>'24WB', 'styles'=>array(9)),
                17=>array('label'=>'24WFWS', 'styles'=>array(9)),
                18=>array('label'=>'24WBBM', 'styles'=>array(1,2,3,4,5,16)),
                19=>array('label'=>'WRTB-SLAP', 'styles'=>array(9)),
                20=>array('label'=>'WRTBFM-SLAP', 'styles'=>array(1,2,5,16)),
                21=>array('label'=>'WRTBBM-SLAP', 'styles'=>array(1,2,5,16)),
                //22=>array('label'=>'WRTB-SLAP', 'styles'=>array(6)),
                23=>array('label'=>'WRTFWS-SLAP', 'styles'=>array(9)),
                24=>array('label'=>'WRTB-12SNAP', 'styles'=>array(9)),
                25=>array('label'=>'WRTBFM-12SNAP', 'styles'=>array(1,2,3,4,5,16)),
                26=>array('label'=>'WRTBBM-12SNAP', 'styles'=>array(1,2,3,4,5,16)),
                27=>array('label'=>'WRTFWS-12SNAP', 'styles'=>array(9)),
                28=>array('label'=>'WRTB-CHAIN', 'styles'=>array(9)),
                29=>array('label'=>'WRTFWS-CHAIN', 'styles'=>array(9)),
                30=>array('label'=>'WRTBBM-CHAIN', 'styles'=>array(1,2,3,4,5,16)),
                34=>array('label'=>'WRTBBM-CHAIN', 'styles'=>array(1,2,5,16)),
                37=>array('label'=>'12LEDSTD', 'styles'=>array(19,20)),
                38=>array('label'=>'12LEDSNM', 'styles'=>array(19,20)),
            );
            if($builder['inhouse']) {
                $types = array(
                    1=>array('label'=>'14', 'styles'=>array(6,7)),
                    2=>array('label'=>'12', 'styles'=>array(6,7,11)),
                    4=>array('label'=>'1', 'styles'=>array(7)),
                    5=>array('label'=>'slap', 'styles'=>array(6,7,8,12)),
                    6=>array('label'=>'snap', 'styles'=>array(6)),
                    7=>array('label'=>'chain', 'styles'=>array(6,7)),
                    37=>array('label'=>'12LEDSTD', 'styles'=>array(19,20)),
                    38=>array('label'=>'12LEDSNM', 'styles'=>array(19,20)),
                    //9=>array('label'=>'12WB', 'styles'=>array(9)),
                    //11=>array('label'=>'12WBBM', 'styles'=>array(6)),
                    //12=>array('label'=>'12NM', 'styles'=>array(6)),
                );
            }
        //}

        /*
        }
        */

        $type = (isset($types[$t])?$types[$t]:array('label'=>'N/A', 'styles'=>array()));

        $debossed_label = 'Debossed - 50 min. qty';
        /*
        switch(intval($t, 10)) {
            case 1:
            case 2:
            case 5:
            case 6:
            case 7:
                $debossed_label = 'Debossed - No min. qty';
                break;
        }
        */
        /*
        $styles = array(
            1=>array('label'=>$debossed_label, 'types'=>array(1,2,3,4,6,7,8,9)),
            2=>array('label'=>'Ink Filled Deboss - 50 min. qty', 'types'=>array(1,2,3,4,6,7,8,9)),
            //3=>array('label'=>'Embossed - 50 min. qty', 'types'=>array(1,2,3,4,6,7,8,9)),
            3=>array('label'=>'Embossed - 50 min. qty', 'types'=>array(1,2,3,4,6,7,9)),
            //4=>array('label'=>'Colorized Emboss - 50 min. qty', 'types'=>array(1,2,3,4,6,7,8,9)),
            4=>array('label'=>'Colorized Emboss - 50 min. qty', 'types'=>array(1,2,3,4,6,7,9)),
            5=>array('label'=>'Screen Printed - 50 min. qty', 'types'=>array(1,2,3,4,5,6,7,8,9)),
            7=>array('label'=>'Dual-layer - No min. qty', 'types'=>array(2,4)),
        );
        if($inhouse) {
            $styles = array(
                6=>array('label'=>'Debossed - No min. qty', 'types'=>array(2,5)),
                7=>array('label'=>'Dual-layer - No min. qty', 'types'=>array(2,4)),
            );
        }
        if($_SERVER['REMOTE_ADDR'] == '109.160.0.218') {
        */
        /*
        $styles = array(
            1=>array('label'=>$debossed_label, 'types'=>array(1,2,3,4,6,7,8,9,10,11,13,14,15,16,17,18,19,20,21,23,24,25,26,27,28,29,30)),
            2=>array('label'=>'Ink Filled Deboss - 50 min. qty', 'types'=>array(1,2,3,4,6,7,8,9,10,11,13,14,15,16,17,18,19,20,21,23,24,25,26,27,28,29,30)),
            //3=>array('label'=>'Embossed - 50 min. qty', 'types'=>array(1,2,3,4,6,7,8,9)),
            3=>array('label'=>'Embossed - 50 min. qty', 'types'=>array(1,2,3,4,6,7,9,10,11,13,14,15,16,17,18,19,23,24,25,26,27,28,29,30)),
            //4=>array('label'=>'Colorized Emboss - 50 min. qty', 'types'=>array(1,2,3,4,6,7,8,9)),
            4=>array('label'=>'Colorized Emboss - 50 min. qty', 'types'=>array(1,2,3,4,6,7,9,10,11,13,14,15,16,17,18,19,23,24,25,26,27,28,29,30)),
            5=>array('label'=>'Screen Printed - 50 min. qty', 'types'=>array(1,2,3,4,5,6,7,8,9,10,11,13,14,15,16,17,18,19,20,21,23,24,25,26,27,28,29,30)),
            7=>array('label'=>'Dual-layer - No min. qty', 'types'=>array(1,2,4,5)),
            8=>array('label'=>'Cut-Away - No min. qty', 'types'=>array(5)),
        );
        if($builder['inhouse']) {
            $styles = array(
                6=>array('label'=>'Debossed - No min. qty', 'types'=>array(1,2,5,12)),
                7=>array('label'=>'Dual-layer - No min. qty', 'types'=>array(1,2,4,5)),
                8=>array('label'=>'Cut-Away - No min. qty', 'types'=>array(5)),
                11=>array('label'=>'Writable - No min. qty', 'types'=>array(2)),
                12=>array('label'=>'Writable - No min. qty', 'types'=>array(5)),
            );
        }
        */
        //if(isDev()) {
            $styles = array(
                1=>array('label'=>'Debossed - 50 min. qty', 'types'=>array(1,2,3,4,6,7,8,9,10,11,13,14,15,16,17,18,19,20,21,23,24,25,26,27,28,29,30,34)),
                2=>array('label'=>'Ink Filled Deboss - 50 min. qty', 'types'=>array(1,2,3,4,6,7,8,9,10,11,13,14,15,16,17,18,19,20,21,23,24,25,26,27,28,29,30,34)),
                //3=>array('label'=>'Embossed - 50 min. qty', 'types'=>array(1,2,3,4,6,7,8,9)),
                3=>array('label'=>'Embossed - 50 min. qty', 'types'=>array(1,2,3,4,6,7,9,10,11,13,14,15,16,17,18,19,23,24,25,26,27,28,29,30)),
                //4=>array('label'=>'Colorized Emboss - 50 min. qty', 'types'=>array(1,2,3,4,6,7,8,9)),
                4=>array('label'=>'Colorized Emboss - 50 min. qty', 'types'=>array(1,2,3,4,6,7,9,10,11,13,14,15,16,17,18,19,23,24,25,26,27,28,29,30)),
                5=>array('label'=>'Screen Printed - 50 min. qty', 'types'=>array(1,2,3,4,5,6,7,8,9,10,11,13,14,15,16,17,18,19,20,21,23,24,25,26,27,28,29,30,34,37,38)),
                16=>array('label'=>'*New Inverted Screen Print - 50 min. qty', 'types'=>array(1,2,3,4,5,6,7,8,9,10,11,13,14,15,16,17,18,19,20,21,23,24,25,26,27,28,29,30,34)),
                6=>array('label'=>'Debossed - No min. qty', 'types'=>array(7)),
                7=>array('label'=>'Dual-layer - No min. qty', 'types'=>array(1,2,4,5,7)),
                8=>array('label'=>'Cut-Away - No min. qty', 'types'=>array(5)),

                //18=>array('label'=>'Debossed - 100 min. qty', 'types'=>array(37,38)),
                19=>array('label'=>'Debossed - No min. qty', 'types'=>array(37,38)),
				20=>array('label'=>'Blank - No min. qty', 'types'=>array(37,38)),

            );
            if($builder['inhouse']) {
                $styles = array(
                    6=>array('label'=>'Debossed - No min. qty', 'types'=>array(1,2,5,6,7,12)),
                    7=>array('label'=>'Dual-layer - No min. qty', 'types'=>array(1,2,4,5,7)),
                    8=>array('label'=>'Cut-Away - No min. qty', 'types'=>array(5)),
                    11=>array('label'=>'Writable - No min. qty', 'types'=>array(2)),
                    12=>array('label'=>'Writable - No min. qty', 'types'=>array(5)),
					19=>array('label'=>'Debossed - No min. qty', 'types'=>array(37,38)),
					20=>array('label'=>'Blank - No min. qty', 'types'=>array(37,38)),
                );
            }
        //}

        /*
        }
        */

        $builder_styles = explode(',', $builder['style']);
        $builder_styles = array_combine($builder_styles, $builder_styles);
        
        $items = array_intersect_key($items,
                    array_flip($type['styles']));
        if(count($builder_styles) > 1) {
            $items = array_intersect_key($items,
                    $builder_styles);
        }
        //tpt_dump($items, true);
        if(!$builder['inhouse'] && isset($items[6])) {
            $elm = $items[6];
            unset($items[6]);
            array_push($items, $elm);
        }

        $html = '';
        $values = array();

        $title = 'Choose message style...';

        $sOpt = 0;
        if(count($items) > 1) {
            $values[] = array(0, $title);
        } else {
            $rs = reset($items);
            $sItem = $rs['id'];
        }
        $i=1;
		//tpt_dump($sItem);
		//tpt_dump($styles);
        foreach($items as $item) {
            $values[] = array($item['id'], htmlentities($styles[$item['id']]['label']));

            if($sItem == $item['id'])
                $sOpt = $i;

            $i++;
        }

        $ajax_call = tpt_ajax::getCall('bandtype.change_band_type_sb');

        return tpt_html::createSelect($vars, '', $values, $sOpt, ' title="'.$title.'" id="msg_style_select" onfocus="removeClass(this, \'invalid_field\');" onchange="valid_change(document.getElementById(\'tpt_pg_style\'), this, pg_defaultstyle); '.$ajax_call.'"');
        //return tpt_html::createSelect($vars, '', $values, $sOpt, ' title="'.$title.'" id="msg_style_select" onfocus="removeClass(this, \'invalid_field\');" onchange="valid_change(document.getElementById(\'tpt_pg_style\'), this, pg_defaultstyle); msg_col_check(); _short_tpt_pg_generate_prevew_all();if(this.value == \'7\') {_short_tpt_pg_change_band_fill();}"');
        //return tpt_html::createSelect($vars, 'band_color', $values, $selectedColor, ' title="'.$title.'"');

    }


	function BandStyle_PlainSelectSDN(&$vars, $t, $sItem = 0, $builder, $id='') {
		$sttable = $this->moduleTable;
		//$items = $this->moduleData['id'];
		$items = $this->moduleDataSort;
		//tpt_dump($items, true);

		/*
		$query = <<< EOT
SELECT
	`*`
FROM
	`$sttable`
WHERE
	`writable`=0
EOT;
		*/


		//tpt_dump($items, true);
		$items = $vars['db']['handler']->getData($vars, $this->moduleTable, 'id,name', ' (`writable`=0)');


		$html = '';
		$values = array();

		$title = 'Choose message style...';

		$sOpt = 0;
		if(count($items) > 1) {
			$values[] = array(0, $title);
		} else {
			$rs = reset($items);
			$sItem = $rs['id'];
		}
		$i=1;
		//tpt_dump($styles, true);
		foreach($items as $item) {
			$values[] = array($item['id'], htmlentities($item['name']));

			if($sItem == $item['id'])
				$sOpt = $i;

			$i++;
		}

		$ajax_call = tpt_ajax::getCall('bandtype.change_band_type_sb');

		return tpt_html::createSelect($vars, '', $values, $sOpt, ' autocomplete="off" title="'.$title.'" id="'.$id.'" onfocus="removeClass(this, \'invalid_field\');" onchange="/*valid_change(document.getElementById(\'tpt_pg_style\'), this, pg_defaultstyle);*/update_product_row(this);"');
		//return tpt_html::createSelect($vars, '', $values, $sOpt, ' title="'.$title.'" id="msg_style_select" onfocus="removeClass(this, \'invalid_field\');" onchange="valid_change(document.getElementById(\'tpt_pg_style\'), this, pg_defaultstyle); msg_col_check(); _short_tpt_pg_generate_prevew_all();if(this.value == \'7\') {_short_tpt_pg_change_band_fill();}"');
		//return tpt_html::createSelect($vars, 'band_color', $values, $selectedColor, ' title="'.$title.'"');

	}

}

