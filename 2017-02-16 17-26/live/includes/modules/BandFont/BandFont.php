<?php

defined('TPT_INIT') or die('access denied');

class tpt_module_BandFont extends tpt_Module {
    
    function __construct(&$vars, $name, $moduleClassFile, $moduleClass, $moduleTable) {
        //tpt_dump('before BandFont');
        //tpt_dump(number_format(memory_get_usage()));
        
        $fields = array(
                //db field name|field type|length|options|storage options|control|ctrAttr|default|label|index by|split keys|template
            new tpt_ModuleField('id',    'n', null, 'ai', '',         'sp', '', '', '',          true, false,  'LC'),
            new tpt_ModuleField('name',  's', 255,  '',   '',         'tf', '', '', 'Font Name', false, false, 'LC'),
            new tpt_ModuleField('alt_name',  's', 255,  '',   '',         'tf', '', '', 'Alt Font Name', false, false, 'LC'),
            new tpt_ModuleField('file',  's', 255,  '',   '',         'tf', '', '', 'Filename',  true, false, 'LC'),
            //new tpt_ModuleField('HEX',   's', 16,   '',   '${str_pad(dechex(`red`),2,STR_PAD_LEFT)}${str_pad(dechex(`green`),2,STR_PAD_LEFT)}${str_pad(dechex(`blue`),2,STR_PAD_LEFT)}', 'tf', 'disabled="disabled"', '', 'HEX Value', false, false, 'LC'),
            //'<div class="float-left padding-top-20 padding-bottom-20 padding-left-10 padding-right-10" style="background-color: #FFF;"><div class="display-inline-block height-10 width-80" style="background-color: #`HEX`; border: 1px solid #000;"></div></div>',
            '<div class="float-left width-201 height-27" style="text-align: center; line-height: 241px; vertical-align: middle; background: #15AA15 url('.$vars['config']['resourceurl'].'/images/font-option.png) no-repeat scroll 0 0; border: 1px solid #000;"><img src="'.RESOURCE_URL.'/generate-preview?text=`name`&font=`file`&type=simple" class="" style="/*max-height: 17px; max-width: 221px;*/" /></div>',
            '<div class="float-left width-201 height-27" style="text-align: center; line-height: 241px; vertical-align: middle; background: #15AA15 url('.$vars['config']['resourceurl'].'/images/font-option.png) no-repeat scroll 0 0; border: 1px solid #000;"><img src="'.RESOURCE_URL.'/generate-preview?text=`name`&font=`file`&type=vector" class="" style="/*max-height: 17px; max-width: 221px;*/" /></div>',
            //'<div class="float-left" style="background-color: #`HEX`; border: 1px solid #000;"><img src="'.$vars['config']['resourceurl'].'/images/Transparent-Swirl-Band-Preview.png" class="width-80" /></div>'
        );
        
        //tpt_dump('after BandFont');
        //tpt_dump(number_format(memory_get_usage()));
        parent::__construct($vars, $name, $moduleClassFile, $moduleClass, $moduleTable, $fields, 'id');

        
    }
    
    function getFontName(&$vars, $font) {
        if(empty($font))
            return '';
        
        $font = array_filter(explode('.', $font));
        array_pop($font);
        $fontName = ucfirst(preg_replace('#[\W_]+#', ' ', implode('.', $font)));
        
        return $fontName;
    }

	function getItems(&$vars, $input, $options) {
		return $this->moduleData['id'];
	}
	function getSelectedItem(&$vars, $input, $options) {
		$items = $this->getItems($vars, $input, $options);

		return ((isset($input['font'])&&in_array($input['font'], array_keys($items)))?$input['font']:0);
	}

	function CartView_Value(&$vars, $section, $input=array(), $options=array(), &$vinput=array()) {
		$font = array_filter(explode('.', (!empty($this->moduleData['id'][$input[$section['pname']]])?$this->moduleData['id'][$input[$section['pname']]]['file']:'')));
		array_pop($font);
		$font = ucfirst(preg_replace('#[\W_]+#', ' ', implode('.', $font)));

		return $font;
	}
	function SB_Section(&$vars, $section, $input=array(), $options=array(), &$vinput=array()) {
		$types_module = getModule($vars, 'BandType');
		$styles_module = getModule($vars, 'BandStyle');
		$data_module = getModule($vars, 'BandData');
		$data = $data_module->typeStyle;

		$type = $types_module->getActiveItem($vars, $input, $options);
		$style = $styles_module->getActiveItem($vars, $input, $options);
		//$sValue = '-1:'.DEFAULT_BAND_COLOR;

		$data = $data[$type][$style];

		$items = $vars['db']['handler']->getData($vars, $this->moduleTable, 'id,name,file', ' (1=1) ORDER BY `name` ASC', 'id', false);
		if(!empty($data['disabled_fonts_ids'])) {
			$dfi = explode(',', $data['disabled_fonts_ids']);
			$dfi = array_combine($dfi, $dfi);
			//$_items = array_intersect_key($items, $dfi);
			//tpt_dump($items);
			//tpt_dump($_items);
			//tpt_dump($dfi);
			$items = array_diff_key($items, $dfi);
		}

		$sItem = $this->getSelectedItem($vars, $input, $options);
		//tpt_dump($sItem);

		$html = '';
		$values = array();

		$title = 'Choose font...';

		//var_dump($sItem);
		$values[] = array(0, 'Choose font...');
		//$values[] = array(-2, 'See Design Notes');
		//$values[] = array(-1, 'Custom Font');

		if (!empty($items['0']) && strtolower(substr($items['0']['file'], -4)) == '.ttf') { // add favorites optiongroup only if it's fonts array passed
			$values['--- Favorites ---'] = '';
		}

		$abc_not_started = true;

		$sOpt=0;
		$i=1;
		foreach($items as $item) {
			$filename = explode('.', preg_replace('#[\'"\(\)]+#', '', $item['file']));
			if(count($filename) > 1) {
				array_pop($filename);
			}
			$filename = implode('.', $filename).'.png';
			$filepath = FONTS_PATH.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.$filename;
			//var_dump($filename);die();

			$src = RESOURCE_URL.'/generate-preview?text='.$item['name'].'&font='.$item['file'].'&type=simple';
			if(is_file($filepath)) {
				$src = TPT_FONTS_URL.'/images/'.$filename;
			}

			//$values[] = array($item['file'], $this->getFontName($vars, $item['file']));

			//only for fonts listning
			if (substr($item['name'], 0, 1) != '*' && $abc_not_started && !empty($items['0']) && strtolower(substr($items['0']['file'], -4)) == '.ttf')
			{
				$abc_not_started = false;
				$values['--- Alphabetically ---'] = '';
			}

			$values[] = array($item['id'], $item['name']);

			if($sItem == $item['id'])
				$sOpt = $i;

			$i++;
		}

		$valuesDelimiter = "\n";

		$html = tpt_html::createSelect($vars, '', $values, $sOpt, ' style="background-color: white;border: 1px solid #ccc;border-radius: 12px;outline: 0 none;" class="padding-4" autocomplete="off" id="control_'.$section['id'].'" title="'.$title.'" onfocus="removeClass(this, \'invalid_field\');" onchange="process_control_input(this);"');
		//return tpt_html::createSelect($vars, 'band_color', $values, $selectedColor, ' title="'.$title.'"');
		$html .= '<input type="hidden" id="font" name="font" value="'.$sItem.'" />';

		return $html;
	}
    
    function BandFont_Select(&$vars) {
        $items = $vars['db']['handler']->getData($vars, $this->moduleTable, 'id,name,file', ' (1=1) ORDER BY `name` ASC');
        
        $html = '';
        $values = array();
        
        $title = 'Choose font...';
        
        $i=1;
        foreach($items as $item) {
            $filename = explode('.', preg_replace('#[\'"\(\)]+#', '', $item['file']));
            if(count($filename) > 1) {
                array_pop($filename);
            }

            $filename = implode('.', $filename).'.png';
            $filepath = FONTS_PATH.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.$filename;
            //var_dump($filename);die();
            
            $src = RESOURCE_URL.'/generate-preview?text='.$item['name'].'&font='.$item['file'].'&type=simple';
            if(is_file($filepath)) {
                $src = TPT_FONTS_URL.'/images/'.$filename;
            }
            
            if($i==1) {
                $values[] = array($item['file'], '<span class="amz_brown font-size-18 width-201 height-15 display-inline-block padding-left-2 padding-right-2 line-height-15 white-space-nowrap" style="font-family: TODAYSHOP-BOLDITALIC,arial;"'./* style="border: 1px solid #555;"*/'>'.$title.'</span>', $title);
                $i=0;
            }
            $values[] = array($item['file'], '<span class="width-201 height-27 display-inline-block padding-left-2 padding-right-2 line-height-15 white-space-nowrap" style="/*border: 1px solid #555;*/"><img src="'.$src.'" class="" style="/*max-height: 17px; max-width: 221px;*/" /></span>', $item['name']);
        }
        
        $valuesDelimiter = "\n";
        
        $html = tpt_html::createStyledSelect($vars, 'BandFont', $values, $valuesDelimiter, ' display-inline-block', ' width: 652px;left:-350px;', ' width:202px;', ' padding-top-10', 0, '_debossed_tpt_pg_generate_prevew_all', 'tpt_pg_font', ' title="'.$title.'"');
        
        return $html;
    }

    // a function for dumping all fonts and generating tip images
    function tipsimg(&$vars) {
        $items = $this->moduleData['id'];
        
        $html = '';
        $values = array();
        
        $i=1;
        foreach($items as $item) {
            $filename = explode('.', preg_replace('#[\'"\(\)]+#', '', $item['file']));
            if(count($filename) > 1) {
                array_pop($filename);
            }
            $filename = implode('.', $filename).'.png';
            $filepath = FONTS_PATH.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.$filename;
            //var_dump($filename);die();
            
            $src = RESOURCE_URL.'/generate-preview?text='.$item['name'].'&font='.$item['file'].'&type=simple';
            if(is_file($filepath)) {
                $src = TPT_FONTS_URL.'/images/'.$filename;
            }
            
            $values[] = '<img src="'.RESOURCE_URL.'/generate-preview?text='.urlencode($this->getFontName($vars, $item['file'])).'&font='.$item['file'].'&type=simple&pg_x=600&pg_y=800" />';
        }
        
        $valuesDelimiter = "\n";
        $optionsAnchorsClassSfx=' display-inline-block';
                
        $html = implode($valuesDelimiter, $values);

        return $html;
    }

    
    function BandFont_Panel(&$vars) {
        $items = $vars['db']['handler']->getData($vars, $this->moduleTable, 'id,name,file', ' (1=1) ORDER BY `name` ASC');
        
        $html = '';
        $values = array();
        
        $i=1;
        foreach($items as $item) {
            $filename = explode('.', preg_replace('#[\'"\(\)]+#', '', $item['file']));
            if(count($filename) > 1) {
                array_pop($filename);
            }
            $filename = implode('.', $filename).'.png';
            $filepath = FONTS_PATH.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.$filename;
            //var_dump($filename);die();
            
            $src = RESOURCE_URL.'/generate-preview?text='.$item['name'].'&font='.$item['file'].'&type=simple';
            if(is_file($filepath)) {
                $src = TPT_FONTS_URL.'/images/'.$filename;
            }

            $tipsrc = RESOURCE_URL.'generate-preview?text='.urlencode($this->getFontName($vars, $item['file'])).'&font='.$item['file'].'&type=simple&pg_x=600&pg_y=800';
            $tipfilepath = TPT_IMAGES_DIR.'/preview/cached/simple/simple-600x800-hpad5-vpad5-'.$filename;
            if(is_file($tipfilepath)) {
                $tipsrc = TPT_IMAGES_URL.'/preview/cached/simple/simple-600x800-hpad5-vpad5-'.$filename;;
            }

            
//            if(0 && $i==1) {
  //              $values[] = array($item['file'], '<span class="amz_brown font-size-18 width-201 height-15 display-inline-block padding-left-2 padding-right-2 line-height-15 white-space-nowrap" style="font-family: TODAYSHOP-BOLDITALIC,arial;"'./* style="border: 1px solid #555;"*/'>'.$title.'</span>', $title);
   //             $i=0;
     //       }
         //   $values[] = array($item['file'], '<b class="font_item" style="url('.$src.');"></b>', $item['name']);
        //    $values[] = '<b onmouseover="Tip(\'<div class=&quot;tipimig&quot;><img width=&quot;400&quot; src=&quot;'.RESOURCE_URL.'/generate-preview?text='.urlencode($this->getFontName($vars, $item['file'])).'&font='.$item['file'].'&type=simple&pg_x=600&pg_y=800&quot; /></div>\');" onmouseout="UnTip();"  onclick="click_on_font(\''.$item['file'].'\');" class="font_item" style="background-image:url('.$src.');"></b>';
            $values[] = '<b onmouseover="Tip(\'<div class=&quot;tipimig&quot;><img width=&quot;400&quot; src=&quot;'.$tipsrc.'&quot; /></div>\');" onmouseout="UnTip();"  onclick="click_on_font(\''.$item['id'].'\');" class="font_item" style="background-image:url('.$src.');"></b>';
        }
        
        $valuesDelimiter = "\n";
        $optionsAnchorsClassSfx=' display-inline-block';
        
    /*    $options = array();
        foreach($values as $i=>$value) {
            $activeClass = '';
            $optionTitle = '';
            if($i==$defaultInd) {
                $activeClass = ' active';
                $activeOptionContent = $value[1];
                $activeOptionValue = $value[0];
            }
            if(isset($value[2])) {
                $optionTitle = ' title="'.htmlentities($value[2]).'"';
            }
            
            if($i!=0)
                $options[] = '<a class="styledSelectOption padding-top-2 padding-bottom-2 padding-left-2 padding-right-2'.$optionsAnchorsClassSfx.$activeClass.'" rel="'.$value[0].'" onclick="toggle_styled_select(this, 1, '.$onselect.');"'.$optionTitle.'>'.$value[1].'</a>';
        } */
        
        $html = implode($valuesDelimiter, $values);
        
        //$html = tpt_html::createStyledSelect($vars, 'BandFont', $values, $valuesDelimiter, ' display-inline-block', ' width: 652px;left:-350px;', ' width:202px;', ' padding-top-10', 0, '_debossed_tpt_pg_generate_prevew_all', 'tpt_pg_font', ' title="'.$title.'"');
                            //createStyledSelect(&$vars, $name, &$values=array(), $valuesDelimiter="\n", $optionsAnchorsClassSfx=' display-inline-block', $inlineOptionsWrapperWidthCss=' width:151px;', $inlineInnermostWrapperWidthCss=' width:90px;',$activeContentClassSuffix='', $defaultInd=0, $onselect='', $inputId='', $htmlAttribs='', $inlineCss='', $ssDescriptor=array('images'=>array('input-field-1-left.png', 'input-field-1-right.png', 'input-field-1-mid.png'), 'height'=>'35', 'paddings'=>array('12', '35'), 'options_bg_css'=>'background-color:#e6dfb5;')) {
        return $html;
    }

	function BandFont_Panel2(&$vars, $sFont, $pid) {
		$items = $vars['db']['handler']->getData($vars, $this->moduleTable, 'id,name,file', ' (1=1) ORDER BY `name` ASC');

		$html = '';
		$values = array();


		$i=1;
		foreach($items as $item) {
			$scls = '';
			if($item['id'] == $sFont) {
				$scls = ' selected';
			}
			$filename = explode('.', preg_replace('#[\'"\(\)]+#', '', $item['file']));
			if(count($filename) > 1) {
				array_pop($filename);
			}
			$filename = implode('.', $filename).'.png';
			$filepath = FONTS_PATH.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.$filename;
			//var_dump($filename);die();

			$src = RESOURCE_URL.'/generate-preview?text='.$item['name'].'&font='.$item['file'].'&type=simple';
			if(is_file($filepath)) {
				$src = RESOURCE_URL.'/fonts/images/'.$filename;
			}

			$tipsrc = RESOURCE_URL.DS.'generate-preview?text='.urlencode($this->getFontName($vars, $item['file'])).'&font='.$item['file'].'&type=simple&pg_x=600&pg_y=800';
			$tipfilepath = TPT_IMAGES_DIR.'/preview/cached/simple/simple-600x800-hpad5-vpad5-'.$filename;
			if(is_file($tipfilepath)) {
				$tipsrc = TPT_IMAGES_URL.'/preview/cached/simple/simple-600x800-hpad5-vpad5-'.$filename;;
			}


//            if(0 && $i==1) {
			//              $values[] = array($item['file'], '<span class="amz_brown font-size-18 width-201 height-15 display-inline-block padding-left-2 padding-right-2 line-height-15 white-space-nowrap" style="font-family: TODAYSHOP-BOLDITALIC,arial;"'./* style="border: 1px solid #555;"*/'>'.$title.'</span>', $title);
			//             $i=0;
			//       }
			//   $values[] = array($item['file'], '<b class="font_item" style="url('.$src.');"></b>', $item['name']);
			//    $values[] = '<b onmouseover="Tip(\'<div class=&quot;tipimig&quot;><img width=&quot;400&quot; src=&quot;'.RESOURCE_URL.'/generate-preview?text='.urlencode($this->getFontName($vars, $item['file'])).'&font='.$item['file'].'&type=simple&pg_x=600&pg_y=800&quot; /></div>\');" onmouseout="UnTip();"  onclick="click_on_font(\''.$item['file'].'\');" class="font_item" style="background-image:url('.$src.');"></b>';
			$values[] = '<a href="javascript:void(0);" id="'.$pid.'_'.$item['id'].'_control_font" onmouseover="Tip(\'<div class=&quot;tipimig&quot;><img width=&quot;400&quot; src=&quot;'.$tipsrc.'&quot; /></div>\');" onmouseout="UnTip();"  onclick="update_product_row_field(this);update_font_select_choice(this);close_overlay_container();" class="font_item '.$scls.'" style="background-image:url('.$src.');"></a>';
		}

		$valuesDelimiter = "\n";
		$optionsAnchorsClassSfx=' display-inline-block';

		/*    $options = array();
			foreach($values as $i=>$value) {
				$activeClass = '';
				$optionTitle = '';
				if($i==$defaultInd) {
					$activeClass = ' active';
					$activeOptionContent = $value[1];
					$activeOptionValue = $value[0];
				}
				if(isset($value[2])) {
					$optionTitle = ' title="'.htmlentities($value[2]).'"';
				}

				if($i!=0)
					$options[] = '<a class="styledSelectOption padding-top-2 padding-bottom-2 padding-left-2 padding-right-2'.$optionsAnchorsClassSfx.$activeClass.'" rel="'.$value[0].'" onclick="toggle_styled_select(this, 1, '.$onselect.');"'.$optionTitle.'>'.$value[1].'</a>';
			} */

		$html = implode($valuesDelimiter, $values);

		//$html = tpt_html::createStyledSelect($vars, 'BandFont', $values, $valuesDelimiter, ' display-inline-block', ' width: 652px;left:-350px;', ' width:202px;', ' padding-top-10', 0, '_debossed_tpt_pg_generate_prevew_all', 'tpt_pg_font', ' title="'.$title.'"');
		//createStyledSelect(&$vars, $name, &$values=array(), $valuesDelimiter="\n", $optionsAnchorsClassSfx=' display-inline-block', $inlineOptionsWrapperWidthCss=' width:151px;', $inlineInnermostWrapperWidthCss=' width:90px;',$activeContentClassSuffix='', $defaultInd=0, $onselect='', $inputId='', $htmlAttribs='', $inlineCss='', $ssDescriptor=array('images'=>array('input-field-1-left.png', 'input-field-1-right.png', 'input-field-1-mid.png'), 'height'=>'35', 'paddings'=>array('12', '35'), 'options_bg_css'=>'background-color:#e6dfb5;')) {
		return $html;
	}
	function BandFont_Panel3(&$vars, $type, $style, $sid, $sFont=0) {
		$sections_mod = getModule($vars, 'BuilderSection');
		$sections = $sections_mod->moduleData['id'];
		$section = $sections[$sid];
		$pname = $section['pname'];

		$data_module = getModule($vars, 'BandData');
		$data = $data_module->typeStyle;

		//$sValue = '-1:'.DEFAULT_BAND_COLOR;

		$data = $data[$type][$style];

		$items = $vars['db']['handler']->getData($vars, $this->moduleTable, 'id,name,file', ' (1=1) ORDER BY `name` ASC', 'id', false);
		if(!empty($data['disabled_fonts_ids'])) {
			$dfi = explode(',', $data['disabled_fonts_ids']);
			$dfi = array_combine($dfi, $dfi);
			//$_items = array_intersect_key($items, $dfi);
			//tpt_dump($items);
			//tpt_dump($_items);
			//tpt_dump($dfi);
			$items = array_diff_key($items, $dfi);
		}

		//$items = $vars['db']['handler']->getData($vars, $this->moduleTable, 'id,name,file', ' (1=1) ORDER BY `name` ASC');

		$html = '';
		$values = array();


		$i=1;
		foreach($items as $item) {
			$scls = '';
			if($item['id'] == $sFont) {
				$scls = ' selected';
			}
			$filename = explode('.', preg_replace('#[\'"\(\)]+#', '', $item['file']));
			if(count($filename) > 1) {
				array_pop($filename);
			}
			$filename = implode('.', $filename).'.png';
			$filepath = FONTS_PATH.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.$filename;
			//var_dump($filename);die();

			$src = RESOURCE_URL.'/generate-preview?text='.$item['name'].'&font='.$item['file'].'&type=simple';
			if(is_file($filepath)) {
				$src = RESOURCE_URL.'/fonts/images/'.$filename;
			}

			$tipsrc = RESOURCE_URL.DS.'generate-preview?text='.urlencode($this->getFontName($vars, $item['file'])).'&font='.$item['file'].'&type=simple&pg_x=600&pg_y=800';
			$tipfilepath = TPT_IMAGES_DIR.'/preview/cached/simple/simple-600x800-hpad5-vpad5-'.$filename;
			if(is_file($tipfilepath)) {
				$tipsrc = TPT_IMAGES_URL.'/preview/cached/simple/simple-600x800-hpad5-vpad5-'.$filename;;
			}


//            if(0 && $i==1) {
			//              $values[] = array($item['file'], '<span class="amz_brown font-size-18 width-201 height-15 display-inline-block padding-left-2 padding-right-2 line-height-15 white-space-nowrap" style="font-family: TODAYSHOP-BOLDITALIC,arial;"'./* style="border: 1px solid #555;"*/'>'.$title.'</span>', $title);
			//             $i=0;
			//       }
			//   $values[] = array($item['file'], '<b class="font_item" style="url('.$src.');"></b>', $item['name']);
			//    $values[] = '<b onmouseover="Tip(\'<div class=&quot;tipimig&quot;><img width=&quot;400&quot; src=&quot;'.RESOURCE_URL.'/generate-preview?text='.urlencode($this->getFontName($vars, $item['file'])).'&font='.$item['file'].'&type=simple&pg_x=600&pg_y=800&quot; /></div>\');" onmouseout="UnTip();"  onclick="click_on_font(\''.$item['file'].'\');" class="font_item" style="background-image:url('.$src.');"></b>';
			$ID = $item['id'];
			$values[] = <<< EOT
<a href="javascript:void(0);" id="font_{$ID}" onclick="select_font(this, $sid, '$pname');" onmouseover="Tip('<div class=&quot;tipimig&quot;><img width=&quot;400&quot; src=&quot;$tipsrc&quot; /></div>');" onmouseout="UnTip();" class="font_item $scls" style="background-image:url($src);">
	<input type="hidden" id="${ID}_sid" value="$sid" />
	<input type="hidden" id="${ID}_cid" value="$ID" />
</a>
EOT;
		}

		$valuesDelimiter = "\n";
		$optionsAnchorsClassSfx=' display-inline-block';

		/*    $options = array();
			foreach($values as $i=>$value) {
				$activeClass = '';
				$optionTitle = '';
				if($i==$defaultInd) {
					$activeClass = ' active';
					$activeOptionContent = $value[1];
					$activeOptionValue = $value[0];
				}
				if(isset($value[2])) {
					$optionTitle = ' title="'.htmlentities($value[2]).'"';
				}

				if($i!=0)
					$options[] = '<a class="styledSelectOption padding-top-2 padding-bottom-2 padding-left-2 padding-right-2'.$optionsAnchorsClassSfx.$activeClass.'" rel="'.$value[0].'" onclick="toggle_styled_select(this, 1, '.$onselect.');"'.$optionTitle.'>'.$value[1].'</a>';
			} */

		$html = implode($valuesDelimiter, $values);

		//$html = tpt_html::createStyledSelect($vars, 'BandFont', $values, $valuesDelimiter, ' display-inline-block', ' width: 652px;left:-350px;', ' width:202px;', ' padding-top-10', 0, '_debossed_tpt_pg_generate_prevew_all', 'tpt_pg_font', ' title="'.$title.'"');
		//createStyledSelect(&$vars, $name, &$values=array(), $valuesDelimiter="\n", $optionsAnchorsClassSfx=' display-inline-block', $inlineOptionsWrapperWidthCss=' width:151px;', $inlineInnermostWrapperWidthCss=' width:90px;',$activeContentClassSuffix='', $defaultInd=0, $onselect='', $inputId='', $htmlAttribs='', $inlineCss='', $ssDescriptor=array('images'=>array('input-field-1-left.png', 'input-field-1-right.png', 'input-field-1-mid.png'), 'height'=>'35', 'paddings'=>array('12', '35'), 'options_bg_css'=>'background-color:#e6dfb5;')) {
		return $html;
	}
    
    function BandFont_PlainSelect(&$vars, $sItem) {
        $items = $vars['db']['handler']->getData($vars, $this->moduleTable, 'id,name,file', ' (1=1) ORDER BY `name` ASC');
                    
        $html = '';
        $values = array();
        
        $title = 'Choose font...';

        $values[] = array(0, $title);
        
        if (strtolower(substr($items['0']['file'], -4)) == '.ttf') // add favorites optiongroup only if it's fonts array passed
            $values['--- Favorites ---'] = '';
        
        $abc_not_started = true;
        
        $sOpt=0;        
        $i=1;
        foreach($items as $item) {
            $filename = explode('.', preg_replace('#[\'"\(\)]+#', '', $item['file']));
            if(count($filename) > 1) {
                array_pop($filename);
            }
            $filename = implode('.', $filename).'.png';
            $filepath = FONTS_PATH.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.$filename;
            //var_dump($filename);die();
            
            $src = RESOURCE_URL.'/generate-preview?text='.$item['name'].'&font='.$item['file'].'&type=simple';
            if(is_file($filepath)) {
                $src = RESOURCE_URL.'/fonts/images/'.$filename;
            }

            //$values[] = array($item['file'], $this->getFontName($vars, $item['file']));
            
            //only for fonts listning
            if (substr($item['name'], 0, 1) != '*' && $abc_not_started && strtolower(substr($items['0']['file'], -4)) == '.ttf')
            {
                $abc_not_started = false;
                $values['--- Alphabetically ---'] = '';
            }
            
            $values[] = array($item['id'], $item['name']);
  
            if($sItem === $item['id']) {
				//tpt_dump($sItem);
				//tpt_dump($item['file']);
				//tpt_dump(1 == $item['file'], true);
				$sOpt = $i;
			}
                
            $i++;
        }
        //tpt_dump($sOpt, true);

        $valuesDelimiter = "\n";
        if(isDev('newpreview')) {
        return tpt_html::createSelect($vars, 'BandFont', $values, $sOpt, ' id="band_font_select" title="'.$title.'" onfocus="removeClass(this, \'invalid_field\');" onchange="valid_change(document.getElementById(\'tpt_pg_font\'), this);generate_layers_previews(this, layersData);"');
        } else {
        return tpt_html::createSelect($vars, 'BandFont', $values, $sOpt, ' id="band_font_select" title="'.$title.'" onfocus="removeClass(this, \'invalid_field\');" onchange="valid_change(document.getElementById(\'tpt_pg_font\'), this);_short_tpt_pg_generate_prevew_all();"');
        }
        //return tpt_html::createSelect($vars, 'band_color', $values, $selectedColor, ' title="'.$title.'"');
        
        return $html;
    }
    
    
    
    function BandFont_PlainSelectSDN(&$vars, $sItem, $id='') {
        $items = $vars['db']['handler']->getData($vars, $this->moduleTable, 'id,name,file', ' (1=1) ORDER BY `name` ASC');
                    
        $html = '';
        $values = array();
        
        $title = 'Choose font...';

        //var_dump($sItem);
        $values[] = array(0, 'No Details');
        $values[] = array(-2, 'See Design Notes');
        $values[] = array(-1, 'Custom Font');

        if (strtolower(substr($items['0']['file'], -4)) == '.ttf') // add favorites optiongroup only if it's fonts array passed
            $values['--- Favorites ---'] = '';
        
        $abc_not_started = true;
        
        $sOpt=0;
        if($sItem == -1) {
			$sOpt = 2;
		} else if($sItem == -2) {
			$sOpt = 1;
		}
        
        $i=3;
        foreach($items as $item) {
            $filename = explode('.', preg_replace('#[\'"\(\)]+#', '', $item['file']));
            if(count($filename) > 1) {
                array_pop($filename);
            }
            $filename = implode('.', $filename).'.png';
            $filepath = FONTS_PATH.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.$filename;
            //var_dump($filename);die();
            
            $src = RESOURCE_URL.'/generate-preview?text='.$item['name'].'&font='.$item['file'].'&type=simple';
            if(is_file($filepath)) {
                $src = RESOURCE_URL.'/fonts/images/'.$filename;
            }

            //$values[] = array($item['file'], $this->getFontName($vars, $item['file']));
            
            //only for fonts listning
            if (substr($item['name'], 0, 1) != '*' && $abc_not_started && strtolower(substr($items['0']['file'], -4)) == '.ttf')
            {
                $abc_not_started = false;
                $values['--- Alphabetically ---'] = '';
            }
            
            $values[] = array($item['id'], $item['name']);
  
            if($sItem == $item['id'])
                $sOpt = $i;
                
            $i++;
        }

        $valuesDelimiter = "\n";
        
        return tpt_html::createSelect($vars, '', $values, $sOpt, ' autocomplete="off" id="'.$id.'" title="'.$title.'" onfocus="removeClass(this, \'invalid_field\');" onchange="/*valid_change(document.getElementById(\'tpt_pg_font\'), this);*/update_product_row_field(this);"');
        //return tpt_html::createSelect($vars, 'band_color', $values, $selectedColor, ' title="'.$title.'"');
        
        return $html;
    }

}

