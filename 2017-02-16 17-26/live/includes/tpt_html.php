<?php
defined('TPT_INIT') or die('Access Denied');

class tpt_html {
    static $sszIndex = 100000;

	static function htmlTag(&$vars, $tag, $htmlAttribs=array(), $innerHTML='') {
		$selfclosing = array(
			'link',
			'img',
			'br',
			'input'
		);
		$htmlAttribs = implode(' ', $htmlAttribs);

		$html = '';
		$html .= '<'.$tag.' '.$htmlAttribs;
		if(in_array($tag, $selfclosing)) {
			$html .= ' />';
		} else {
			$html .= '>'.$innerHTML.'</' . $tag . '>';
		}

		return $html;
	}

	static function image(&$vars, $src, $htmlAttribs=array(), $width=null, $height=null) {
		$style = '';
		if(!is_null($width)) {
			$style .= 'width: '.$width.'px; ';
		}
		if(!is_null($height)) {
			$style .= 'height: '.$height.'px; ';
		}
		if(!empty($style)) {
			$htmlAttribs['style'] = $height.$width;
		}


		return self::htmlTag($vars, 'image', $htmlAttribs);
	}

    
    static function createCheckbox(&$vars, $name, $value='', $checked=0, $htmlAttribs='', $oncheck="", $onuncheck="") {
        $checked_attr = ($checked?' checked="checked"':'');
        if($oncheck && $checked_attr) {
            $vars['template_data']['footer_scripts']['scripts'][] = $oncheck;
        } else if($onuncheck) {
            $vars['template_data']['footer_scripts']['scripts'][] = $onuncheck;
        }
        $html = '';
        $html .= '<input type="checkbox" name="'.$name.'" value="'.$value.'" '.$htmlAttribs.$checked_attr.' />';
        
        return $html;
    }
    
    static function createRadiobutton(&$vars, $name, $value='', $checked=0, $htmlAttribs='', $oncheck="") {
        $checked_attr = ($checked==$value?' checked="checked"':'');
        if($oncheck && $checked_attr) {
            $vars['template_data']['footer_scripts']['scripts'][] = $oncheck;
        }
        $html = '';
        $html .= '<input type="radio" name="'.$name.'" value="'.$value.'" '.$htmlAttribs.$checked_attr.' />';
        
        return $html;
    }
    
    static function createTextinput(&$vars, $name, $value='', $htmlAttribs='') {
        $html = '';
        $html .= '<input type="text" name="'.$name.'" value="'.$value.'" '.$htmlAttribs.' />';
        
        return $html;
    }
    
    static function createTextarea(&$vars, $name, $value='', $htmlAttribs='') {
        $html = '';
        $html .= '<textarea name="'.$name.'" '.$htmlAttribs.'>';
        $html .= $value;
        $html .= '</textarea>';
        
        return $html;
    }
    
    static function createPasswordinput(&$vars, $name, $value='', $htmlAttribs='') {
        $html = '';
        $html .= '<input type="password" name="'.$name.'" value="'.$value.'" '.$htmlAttribs.' />';
        
        return $html;
    }
    
    static function createSelect(&$vars, $name, &$values=array(), $defaultInd=0, $htmlAttribs='') {
        //var_dump($defaultInd);die();
        $html = '';
        if(!empty($values)) {
            $opts = array();
            reset($values);
            $i = key($values);
            foreach($values as $key=>$value) {
                if(!empty($value)) {
                    if(is_numeric($key)) {
                        $selected = '';
                        if($i == $defaultInd) {
                            $selected = ' selected="selected"';
                        }
                        $v = '';
                        $c = '';
                        if(!is_array($value)) {
                            $v = $c = $value;
                        } else {
                            $v = array_shift($value);
                            $c = $v;
                            if(!empty($value)) {
                                $c = array_shift($value);
                            }
                        }
                        $attr = '';
                        if(!empty($value['attr']))
                            $attr = $value['attr'];
                        $opts[] = '<option value="'.htmlentities($v).'"'.$selected.' '.$attr.'>'.htmlentities($c).'</option>';
                        $i++;
                    } else {
                        //var_dump($value);die();
                        $opts[] = '<optgroup label="'.htmlentities($key).'">';
                        foreach($value as $j=>$val) {
                            $selected = '';
                            if($i == $defaultInd) {
                                $selected = ' selected="selected"';
                            }
                            $v = '';
                            $c = '';
                            if(!is_array($val)) {
                                $v = $c = $val;
                            } else {
                                $v = array_shift($val);
                                $c = $v;
                                if(!empty($val)) {
                                    $c = array_shift($val);
                                }
                            }

							$attr = '';
                            if(!empty($value['attr']))
                                $attr = $value['attr'];
                            $opts[] = '<option value="'.htmlentities($v).'"'.$selected.' '.$attr.'>'.htmlentities($c).'</option>';
                            $i++;
                        }
                        $opts[] = '</optgroup>';
                    }
                }
            }
			$html .= '<select '.((isset($name)&&$name!='')?'name="'.$name.'"':'').' '.$htmlAttribs.'>';
            $html .= implode("\n", $opts);
            $html .= '</select>';
        }
        
        return $html;
    }

	static function createPlainSelect(&$vars, $name, &$values=array(), $defaultInd=0, $htmlAttribs='') {
		//tpt_dump($values, true);
		$html = '';
		if(!empty($values)) {
			$opts = array();
			reset($values);
			$i = key($values);
			foreach($values as $key=>$value) {
				if(!empty($value)) {
					$selected = '';
					if($i == $defaultInd) {
						$selected = ' selected="selected"';
					}
					$v = '';
					$c = '';
					if(!is_array($value)) {
						$v = $c = $value;
					} else {
						$v = array_shift($value);
						$c = $v;
						if(!empty($value)) {
							$c = array_shift($value);
						}
					}
					$attr = '';
					if(!empty($value['attr']))
						$attr = $value['attr'];
					$opts[] = '<option value="'.htmlentities($v).'"'.$selected.' '.$attr.'>'.htmlentities($c).'</option>';
					$i++;

				}
			}
			$html .= '<select '.((isset($name)&&$name!='')?'name="'.$name.'"':'').' '.$htmlAttribs.'>';
			$html .= implode("\n", $opts);
			$html .= '</select>';
		}

		return $html;
	}
    

    static function createStyledSelect(&$vars, $name, &$values=array(), $valuesDelimiter="\n", $optionsAnchorsClassSfx=' display-inline-block', $inlineOptionsWrapperWidthCss=' width:151px;', $inlineInnermostWrapperWidthCss=' width:90px;',$activeContentClassSuffix='', $defaultInd=0, $onselect='', $inputId='', $htmlAttribs='', $inlineCss='', $ssDescriptor=array('images'=>array('input-field-1-left.png', 'input-field-1-right.png', 'input-field-1-mid.png'), 'height'=>'35', 'paddings'=>array('12', '35'), 'options_bg_css'=>'background-color:#e6dfb5;')) {
        if(false && $vars['environment']['isMobileDevice']['ipod'] || $vars['environment']['isMobileDevice']['iphone'] ||
           $vars['environment']['isMobileDevice']['ipad'] || $vars['environment']['isMobileDevice']['android'] ||
           $vars['environment']['isMobileDevice']['webos'])
                return self::mobile_createStyledSelect($vars, $name, $values, $valuesDelimiter, $optionsAnchorsClassSfx, $inlineOptionsWrapperWidthCss, $inlineInnermostWrapperWidthCss,$activeContentClassSuffix, $defaultInd, $onselect, $inputId, $htmlAttribs, $inlineCss, $ssDescriptor);
                
        $tpt_res_url = RESOURCE_URL;
        
        $options = array();
        $expanders = array();
        $activeOptionContent = '';
        $activeOptionValue = '';
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
            $expanders[] = '<div class="padding-top-2 padding-bottom-2 padding-left-2 padding-right-2'.$optionsAnchorsClassSfx.'">'.$value[1].'</div>';
        }
        
        $ssLeftImage = $ssDescriptor['images'][0];
        $ssRightImage = $ssDescriptor['images'][1];
        $ssMidImage = $ssDescriptor['images'][2];
        $ssHeight = $ssDescriptor['height'];
        $ssLeftPadding = $ssDescriptor['paddings'][0];
        $ssRightPadding = $ssDescriptor['paddings'][1];
        $ssOptionsBGCss = $ssDescriptor['options_bg_css'];
        
        
        $options = '<div class="styledSelectOptions optionsFolded display-none overflow-hidden position-absolute opacity-0 padding-left-5 padding-right-5 padding-bottom-5" style="z-index: 100000000; max-height: 350px;overflow:auto;border: 1px solid #5b3824;'.$ssOptionsBGCss.'top:100%;height:0px;'.$inlineOptionsWrapperWidthCss.'">'.implode($valuesDelimiter, $options).'</div>';
        $expanders = '<div class="styledSelectExpander position-absolute top-0 right-0 bottom-0 left-0"><div class="padding-left-5 padding-right-5" style="max-height: 350px;overflow:auto;visibility:hidden;'.$inlineOptionsWrapperWidthCss.'">'.implode($valuesDelimiter, $expanders).'</div></div>';
        
        $zIndex = self::$sszIndex;
        
        $html = '';

$html = <<< EOT
<div onclick="toggle_styled_select(this)" class="styledSelectFolded $name display-inline-block padding-left-$ssLeftPadding background-position-LC background-repeat-no-repeat position-relative" style="z-index:$zIndex;cursor:pointer;background-image: url($tpt_res_url/images/$ssLeftImage);$inlineCss" $htmlAttribs>
    <input autocomplete="off" id="$inputId" type="hidden" name="$name" value="$activeOptionValue" />
    <div class="padding-right-$ssRightPadding background-position-RC background-repeat-no-repeat" style="background-image: url($tpt_res_url/images/$ssRightImage);">
        <div class="styledSelectInnermostWrapper position-relative background-repeat-repeat-x height-$ssHeight" style="background-image: url($tpt_res_url/images/$ssMidImage);$inlineInnermostWrapperWidthCss">
            <div class="styledSelectCurrentContent position-absolute top-0 right-0 bottom-0 left-0 line-height-$ssHeight$activeContentClassSuffix">$activeOptionContent</div>
            $expanders
            $options
        </div>
    </div>
</div>
EOT;

        self::$sszIndex -= 1000;

        return $html;
    }
    
    static function mobile_createStyledSelect(&$vars, $name, &$values=array(), $valuesDelimiter="\n", $optionsAnchorsClassSfx=' display-inline-block', $inlineOptionsWrapperWidthCss=' width:151px;', $inlineInnermostWrapperWidthCss=' width:90px;',$activeContentClassSuffix='', $defaultInd=0, $onselect='', $inputId='', $htmlAttribs='', $inlineCss='', $ssDescriptor=array('images'=>array('input-field-1-left.png', 'input-field-1-right.png', 'input-field-1-mid.png'), 'height'=>'35', 'paddings'=>array('12', '35'), 'options_bg_css'=>'background-color:#e6dfb5;')) {
        $tpt_res_url = RESOURCE_URL;
        
        $options = array();
        $expanders = array();
        $activeOptionContent = '';
        $activeOptionValue = '';
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
            $expanders[] = '<div class="padding-top-2 padding-bottom-2 padding-left-2 padding-right-2'.$optionsAnchorsClassSfx.'">'.$value[1].'</div>';
        }
        
        $ssLeftImage = $ssDescriptor['images'][0];
        $ssRightImage = $ssDescriptor['images'][1];
        $ssMidImage = $ssDescriptor['images'][2];
        $ssHeight = $ssDescriptor['height'];
        $ssLeftPadding = $ssDescriptor['paddings'][0];
        $ssRightPadding = $ssDescriptor['paddings'][1];
        $ssOptionsBGCss = $ssDescriptor['options_bg_css'];
        
        
        $options = '<div class="styledSelectOptions optionsFolded display-none overflow-hidden position-absolute opacity-0 padding-left-5 padding-right-5 padding-bottom-5" style="z-index: 100000000; max-height: 350px;overflow:auto;border: 1px solid #5b3824;'.$ssOptionsBGCss.'top:100%;height:0px;'.$inlineOptionsWrapperWidthCss.'">'.implode($valuesDelimiter, $options).'</div>';
        $expanders = '<div class="styledSelectExpander position-absolute top-0 right-0 bottom-0 left-0"><div class="padding-left-5 padding-right-5" style="max-height: 350px;overflow:auto;visibility:hidden;'.$inlineOptionsWrapperWidthCss.'">'.implode($valuesDelimiter, $expanders).'</div></div>';
        
        $zIndex = self::$sszIndex;
        
        $html = '';


$html = <<< EOT
<div onclick="toggle_styled_select(this)" class="styledSelectFolded $name display-inline-block padding-left-$ssLeftPadding background-position-LC background-repeat-no-repeat position-relative" style="z-index:$zIndex;cursor:pointer;background-image: url($tpt_res_url/images/$ssLeftImage);$inlineCss" $htmlAttribs>
    <input autocomplete="off" id="$inputId" type="hidden" name="$name" value="$activeOptionValue" />
    <div class="padding-right-$ssRightPadding background-position-RC background-repeat-no-repeat" style="background-image: url($tpt_res_url/images/$ssRightImage);">
        <div class="styledSelectInnermostWrapper position-relative background-repeat-repeat-x height-$ssHeight" style="background-image: url($tpt_res_url/images/$ssMidImage);$inlineInnermostWrapperWidthCss">
            <div class="styledSelectCurrentContent position-absolute top-0 right-0 bottom-0 left-0 line-height-$ssHeight$activeContentClassSuffix">$activeOptionContent</div>
            $expanders
            $options
        </div>
    </div>
</div>
EOT;

        self::$sszIndex -= 1000;

        return $html;
    }
    
    static function getPreviewLayersHTML(&$vars, $input) {
        $html = '';
        //tpt_dump($input, true);
        if(!empty($input) && !empty($input['chld'])) {
            foreach($input['chld'] as $inp) {
                $html .= '<div '.$inp['attribs'].'>'.self::getPreviewLayersHTML($vars, $inp).'</div>';
            }
        }
        
        return $html;
    }
    
    static function getAlternatingHTML($input, $tagname="div", $classes=array(), $inpcls=array(), $htmlAttribs=array()) {
        if(empty($input))
            return '';
        
        $html = '';
        foreach($input as $str) {
            $htmlAttr = '';
            if(!empty($htmlAttribs)) {
                if(is_array($htmlAttribs)) {
                    $htmlAttr = ' '.reset($htmlAttribs);
                    $apha = array_shift($htmlAttribs);
                    array_push($htmlAttribs, $apha);
                } else {
                    $htmlAttr = ' '.$htmlAttribs;
                }
            }
            
            if(empty($classes)) {
                $html .= '<'.$tagname.$htmlAttr.'>'.$str.'</'.$tagname.'>';
            } else {
                
                
                $cls = reset($classes);
                $ap = array_shift($classes);
                array_push($classes, $ap);
                
                $icls = '';
                if(!empty($inpcls)) {
                    //var_dump($inpcls);die();
                    $icls = array_shift($inpcls);
                }

                $html .= '<'.$tagname.' class="'.$cls.$icls.'"'.$htmlAttr.'>'.$str.'</'.$tagname.'>';
            }
        }
        
        return $html;
    }

	static function sanitize_html_output($buffer) {

		$search = array(
			'/\>[^\S ]+/s',  // strip whitespaces after tags, except space
			'/[^\S ]+\</s',  // strip whitespaces before tags, except space
			//'/(\s)+/s'       // shorten multiple whitespace sequences
			'/( )+/s'       // shorten multiple whitespace sequences
		);

		$replace = array(
			'>',
			'<',
			'\\1'
		);

		$buffer = preg_replace($search, $replace, $buffer);

		return $buffer;
	}

	static function sanitize_css_output($buffer) {
		$buffer = preg_replace('%/\*.*?\*/%s', '', $buffer);
		$buffer = preg_replace('/<!--.*?-->/s', '', $buffer);
		$buffer = preg_replace('/(?:([\w])[\s]*(\{)[\s]*([\w])?)|(?:(;)[\s]*([\w]))|(?:(;)?[\s]*(\}))|(?:(:)[\s]*([\w\']))|(?:[\s]*(,))|(?:(,)[\s]*)/', '$1$2$3$4$5$6$7$8$9$10$11', $buffer);
		$buffer = preg_replace('/(\})[\s]*([\S])/', '$1$2', $buffer);
		$buffer = str_replace('_display:inline;', "\n" . '_display:inline;' . "\n", $buffer);
		$buffer = str_replace('_display: inline;', "\n" . '_display:inline;' . "\n", $buffer);
		$buffer = str_replace('*display:inline;', "\n" . '*display:inline;' . "\n", $buffer);
		$buffer = str_replace('*display: inline;', "\n" . '*display:inline;' . "\n", $buffer);
		$buffer = str_replace('*zoom:1;', "\n" . '*zoom:1;' . "\n", $buffer);
		$buffer = str_replace('*zoom: 1;', "\n" . '*zoom:1;' . "\n", $buffer);

		return $buffer;
	}

	static function sanitize_html($input) {
		$input = preg_replace('#(>)[\s]*(<)#', '$1$2', $input);

		preg_match_all('#<script[^>]*?>(?:[\s]*(?://<\!\[CDATA\[)|(?:/\* <\!\[CDATA\[ \*/))?([^<]+?.+?)(?:(//\]\]>)|(?:/\* \]\]> \*/)[\s]*)?</script>#s', $input, $scripts, PREG_SET_ORDER);
		foreach ($scripts as $s) {
			$input = str_replace($s[0], '<script type="text/javascript">//<![CDATA[' . "\n" . preg_replace('#(?:\\\\)?/\*.*?\*(?:\\\\)?/#s', '', JSMin::minify($s[1])) . "\n" . '//]]></script>', $input);
		}
//tpt_dump($scripts, true);

		preg_match_all('#([^"\'])<style[^>]*?>([^<]+?.+?)</style>([^"\'])#s', $input, $styles, PREG_SET_ORDER);
		foreach ($styles as $s) {
			$input = str_replace($s[0], $s[1] . '<style type="text/css">' . preg_replace('#(?:\\\\)*/\*.*?\*(?:\\\\)*/#s', '', self::sanitize_css_output($s[2])) . '</style>' . $s[3], $input);
		}

		$input = preg_replace('#<\!--.*?-->#s', '', $input);

		return $input;
	}
	
	static function render_form_fields(&$vars, $fields_data, $fbl=array(), $fbc=array(), $fba=array(), $frl=array(), $frc=array(), $fra=array()) {
		//global $tpt_vars;

		$required_html = '<span class="amz_red">*</span>';

		$label_width_class = ' width-119';

		$rlabels = '';
		$rlabels_before = '';
		$rcontrols = '';
		$rcontrols_before = '';
		$rafter = '';
		$rafter_before = '';
		$sections = array();
		$section = '<div class="tpt_form_section">';
		$section .= '<div>';
		$section .= '<div class="tpt_form_section_title"></div>';
		$section .= '<div class="tpt_form_section_body">';
		foreach($fields_data as $rf) {
		 
			$id_prefix = (isset($rf['name'])) ? $rf['name'] : $rf['id'];
			switch(strtolower($rf['control'])) {
				case 'e' :
					break;
				case 's' :
					$label = $rf['label'];
					if(!empty($label)) {
						$label .= (!empty($rf['required'])?$required_html:'').':';
					}
					$rlabels .= '<div id="'.$id_prefix.'_tptformlabel" class="urontrol urlabel'.$label_width_class.' height-'.$rf['row_height'].' line-height-'.$rf['label_line_height'].' padding-top-4 padding-bottom-4 padding-top-4 padding-bottom-4'.$rf['classes'].'">'.$label.'</div>';
					if(preg_match('#^\{(.*)\}$#', $rf['value'], $mtch)) {
						$ccmp = explode(':', $mtch[1]);

						include(TPT_TEMPLATES_DIR.DIRECTORY_SEPARATOR.$ccmp[1]);

						$rcontrols .= '<div id="'.$id_prefix.'_tptformcontrol'.'" class="urontrol height-'.$rf['row_height'].' line-height-'.$rf['control_line_height'].' padding-top-4 padding-bottom-4'.$rf['classes'].'">'.${$ccmp[0]};
						$rcontrols .= '</div>';
					} else {
						$rcontrols .= '<div id="'.$id_prefix.'_tptformcontrol'.'" class="urontrol height-'.$rf['row_height'].' line-height-'.$rf['control_line_height'].' padding-top-4 padding-bottom-4'.$rf['classes'].'">'.$rf['value'];
						$rcontrols .= '</div>';
					}
					$rafter .= '<div class="urontrol height-'.$rf['row_height'].' line-height-'.$rf['after_line_height'].' padding-top-4 padding-bottom-4 position-relative'.$rf['classes'].'">';
					$rafter .= '<div class="position-relative" style="height: 100%; top: 50%;">';
					$rafter .= '<div class="position-relative" style="height: 100%; top: -50%;">';
					$rafter .= $rf['after_content'];
					$rafter .= '</div>';
					$rafter .= '</div>';
					$rafter .= '</div>';
					break;
				case 'sec' :
					$section_title = $rf['label'];
					if(preg_match('#^\{(.*)\}$#', $rf['value'], $mtch)) {
						$section_class = '';
						$section_opc = '100';
						$valcomp = explode(':', $mtch[1]);
						$contvar = 'section_html';
						$ifile = '';
						if(count($valcomp) > 1) {
							$contvar = $valcomp[0];
							$ifile = $valcomp[1];
						} else {
							$ifile = $valcomp[0];
						}
						include(TPT_TEMPLATES_DIR.DIRECTORY_SEPARATOR.$ifile);

						$section .= '<div class="float-left text-align-right">'.$rlabels.'</div>';
						$section .= '<div class="float-left text-align-left padding-left-10 width-50prc">'.$rcontrols.'</div>';
						$section .= '<div class="overflow-hidden text-align-left padding-left-10">'.$rafter.'</div>';
						$section .= '</div>';
						$section .= '</div>';
						$section .= '</div>';
						$sections[] = $section;
						$rlabels = '';
						$rcontrols = '';
						$rafter = '';
						$secid = '';
						$sectid = '';
						$secbid = '';
						if(!empty($rf['name'])) {
							$secid = ' id="'.$rf['name'].'_form_section"';
							$sectid = ' id="'.$rf['name'].'_form_section_title"';
							$secbid = ' id="'.$rf['name'].'_form_section_body"';
							$sectgid = ' id="'.$rf['name'].'_form_section_toggle"';
						}
						$section = '<div class="tpt_form_section'.$rf['classes'].'" '.$rf['html_attribs'].'>';
						$section .= '<div>';
						$section .= '<div class="tpt_form_section_title display-block"'.$sectid.'>'.$section_title.'</div>';
						if(!empty($fbl)) {
							$rlabels_before .= implode("\n", $fbl);
							$rcontrols_before .= implode("\n", $fbc);
							$rafter_before .= implode("\n", $fba);
							$section_before_content = '';
							$section_before_content .= '<div class="float-left text-align-right">'.$rlabels_before.'</div>';
							$section_before_content .= '<div class="float-left text-align-left padding-left-10 width-50prc">'.$rcontrols_before.'</div>';
							$section_before_content .= '<div class="overflow-hidden text-align-left padding-left-10">'.$rafter_before.'</div>';
							$fbl = array();
							$fbc = array();
							$fba = array();
							$section .= '<div class="tpt_form_section_before display-block">'.$section_before_content.'</div>';
						}
						$section .= '<div class="tpt_form_section_body overflow-hidden left-0 right-0'.$section_class.'"'.$secid.'>';
						$section .= '<span class="tpt_form_section_toggle"><span'.$sectgid.'></span></span>';
						$section .= '<div class="tpt_form_section_content opacity-'.$section_opc.'"'.$secbid.'>';
						$section .= ${$contvar};
						$section .= '</div>';
						$section .= '</div>';
						$section .= '</div>';
						$section .= '</div>';
						$sections[] = $section;

						$section = '<div class="tpt_form_section">';
						$section .= '<div>';
						$section .= '<div class="tpt_form_section_title"></div>';
						$section .= '<div class="tpt_form_section_body">';
						//var_dump($ccmpvars[0]);
					} else {
						$section .= '<div class="float-left text-align-right">'.$rlabels.'</div>';
						$section .= '<div class="float-left text-align-left padding-left-10 width-50prc">'.$rcontrols.'</div>';
						$section .= '<div class="overflow-hidden text-align-left padding-left-10">'.$rafter.'</div>';
						$section .= '</div>';
						$section .= '</div>';
						$section .= '</div>';
						$sections[] = $section;
						$rlabels = '';
						$rcontrols = '';
						$rafter = '';
						$section = '<div class="tpt_form_section'.$rf['classes'].'" '.$rf['html_attribs'].'>';
						$section .= '<div>';
						$section .= '<div class="tpt_form_section_title">'.$section_title.'</div>';
						$section .= '<div class="tpt_form_section_body">';
						//var_dump($ccmpvars[0]);
					}
					break;
				case 't' :
					$label = $rf['label'];
					if(!empty($label)) {
						$label .= (!empty($rf['required'])?$required_html:'').':';
					}
					$invalid_class = '';
					$invalid_class2 = '';
					if(!empty($vars['template_data']['invalid_fields'][$rf['name']])) {
						$invalid_class = 'amz_red tpt_invalid_field ';
						$invalid_class2 = ' tpt_invalid_field ';
					}
					$rlabels .= '<div id="'.$id_prefix.'_tptformlabel" class="urontrol urlabel'.$label_width_class.' '.$invalid_class.'height-'.$rf['row_height'].' line-height-'.$rf['label_line_height'].' padding-top-4 padding-bottom-4'.$rf['classes'].'">'.$label.'</div>';
					$rcontrols .= '<div id="'.$rf['name'].'_tptformcontrol'.'" class="'.$invalid_class2.' urontrol height-'.$rf['row_height'].' line-height-'.$rf['control_line_height'].' padding-top-4 padding-bottom-4'.$rf['classes'].'">';
					//$rcontrols .= '<div style="background-image: url('.$tpt_imagesurl.'/user-form-field-left.png);" class="padding-left-8 background-position-LC background-repeat-no-repeat">';
					//$rcontrols .= '<div style="background-image: url('.$tpt_imagesurl.'/user-form-field-right.png);" class="padding-right-8 background-position-RC background-repeat-no-repeat">';
					//$rcontrols .= '<div style="background-image: url('.$tpt_imagesurl.'/user-form-field-mid.png);" class="background-repeat-repeat-x background-position-CC">';
					$rcontrols .= self::createTextinput($vars, $rf['name'], isset($vars['template_data']['form_values'][$rf['name']])?$vars['template_data']['form_values'][$rf['name']]:'', ' size="5" class="plain-input-field padding-left-2 padding-right-2 padding-top-2 padding-bottom-2 font-size-14" '.$rf['html_attribs']);
					//$rcontrols .= '</div>';
					//$rcontrols .= '</div>';
					//$rcontrols .= '</div>';
					$rcontrols .= '</div>';
					$rafter .= '<div class="urontrol height-'.$rf['row_height'].' line-height-'.$rf['after_line_height'].' padding-top-4 padding-bottom-4 position-relative'.$rf['classes'].'">';
					$rafter .= '<div class="position-relative" style="height: 100%; top: 50%;">';
					$rafter .= '<div class="position-relative" style="height: 100%; top: -50%;">';
					$rafter .= $rf['after_content'];
					$rafter .= '</div>';
					$rafter .= '</div>';
					$rafter .= '</div>';
					break;
				case 'p' :
					$label = $rf['label'];
					if(!empty($label)) {
						$label .= (!empty($rf['required'])?$required_html:'').':';
					}
					$invalid_class = '';
					$invalid_class2 = '';
					if(!empty($vars['template_data']['invalid_fields'][$rf['name']])) {
						$invalid_class = 'amz_red tpt_invalid_field ';
						$invalid_class2 = ' tpt_invalid_field ';
					}
					$rlabels .= '<div id="'.$id_prefix.'_tptformlabel" class="urontrol urlabel'.$label_width_class.' '.$invalid_class.'height-'.$rf['row_height'].' line-height-'.$rf['label_line_height'].' padding-top-4 padding-bottom-4'.$rf['classes'].'">'.$label.'</div>';
					$rcontrols .= '<div id="'.$rf['name'].'_tptformcontrol'.'" class="'.$invalid_class2.' urontrol height-'.$rf['row_height'].' line-height-'.$rf['control_line_height'].' padding-top-4 padding-bottom-4'.$rf['classes'].'">';
					//$rcontrols .= '<div style="background-image: url('.$tpt_imagesurl.'/user-form-field-left.png);" class="padding-left-8 background-position-LC background-repeat-no-repeat">';
					//$rcontrols .= '<div style="background-image: url('.$tpt_imagesurl.'/user-form-field-right.png);" class="padding-right-8 background-position-RC background-repeat-no-repeat">';
					//$rcontrols .= '<div style="background-image: url('.$tpt_imagesurl.'/user-form-field-mid.png);" class="background-repeat-repeat-x background-position-CC">';
					$rcontrols .= self::createPasswordinput($vars, $rf['name'], '', ' size="5" class="plain-input-field padding-left-2 padding-right-2 padding-top-2 padding-bottom-2 font-size-14" '.$rf['html_attribs']);
					//$rcontrols .= '</div>';
					//$rcontrols .= '</div>';
					//$rcontrols .= '</div>';
					$rcontrols .= '</div>';
					$rafter .= '<div class="urontrol height-'.$rf['row_height'].' line-height-'.$rf['after_line_height'].' padding-top-4 padding-bottom-4 position-relative'.$rf['classes'].'">';
					$rafter .= '<div class="position-relative" style="height: 100%; top: 50%;">';
					$rafter .= '<div class="position-relative" style="height: 100%; top: -50%;">';
					$rafter .= $rf['after_content'];
					$rafter .= '</div>';
					$rafter .= '</div>';
					$rafter .= '</div>';
					break;
				case 'r' :
					$label = $rf['label'];
					if(!empty($label)) {
						$label .= (!empty($rf['required'])?$required_html:'').':';
					}
					$invalid_class = '';
					$invalid_class2 = '';
					if(!empty($vars['template_data']['invalid_fields'][$rf['name']])) {
						$invalid_class = 'amz_red tpt_invalid_field ';
						$invalid_class2 = ' tpt_invalid_field ';
					}
					$rlabels .= '<div id="'.$id_prefix.'_tptformlabel" class="urontrol urlabel'.$label_width_class.' '.$invalid_class.'height-'.$rf['row_height'].' line-height-'.$rf['label_line_height'].' padding-top-4 padding-bottom-4'.$rf['classes'].'">'.$label.'</div>';
					$rcontrols .= '<div id="'.$rf['name'].'_tptformcontrol'.'" class="'.$invalid_class2.' urontrol height-'.$rf['row_height'].' line-height-'.$rf['control_line_height'].' padding-top-4 padding-bottom-4'.$rf['classes'].'">'.self::createRadiobutton($vars, $rf['name'], $rf['value'], $vars['template_data']['form_values'][$rf['name']], $rf['html_attribs'], $rf['oncheck']);
					$rcontrols .= '</div>';
					$rafter .= '<div class="urontrol height-'.$rf['row_height'].' line-height-'.$rf['after_line_height'].' padding-top-4 padding-bottom-4 position-relative'.$rf['classes'].'">';
					$rafter .= '<div class="position-relative" style="height: 100%; top: 50%;">';
					$rafter .= '<div class="position-relative" style="height: 100%; top: -50%;">';
					$rafter .= $rf['after_content'];
					$rafter .= '</div>';
					$rafter .= '</div>';
					$rafter .= '</div>';
					break;
				case 'rg' :
					$label = $rf['label'];
					if(!empty($label)) {
						$label .= (!empty($rf['required'])?$required_html:'').':';
					}
					$invalid_class = '';
					$invalid_class2 = '';
					if(!empty($vars['template_data']['invalid_fields'][$rf['name']])) {
						$invalid_class = 'amz_red tpt_invalid_field ';
						$invalid_class2 = ' tpt_invalid_field ';
					}
					$rgroup = explode(',', $rf['value']);
					$rlabels .= '<div id="'.$id_prefix.'_tptformlabel" class="urontrol urlabel'.$label_width_class.' '.$invalid_class.'height-'.$rf['row_height'].' line-height-'.$rf['label_line_height'].' padding-top-4 padding-bottom-4'.$rf['classes'].'">'.$label.'</div>';
					$rcontrols .= '<div id="'.$rf['name'].'_tptformcontrol'.'" class="'.$invalid_class2.' urontrol height-'.$rf['row_height'].' line-height-'.$rf['control_line_height'].' padding-top-4 padding-bottom-4'.$rf['classes'].'">';
					foreach($rgroup as $rg) {
						$rgcpn = explode(':', $rg);
						$checked_html = '';
						if(!empty($rgcpn[2])) {
							if(!isset($vars['template_data']['form_values'][$rf['name']])) {
								$checked_html = ' checked="checked"';
							}
						}
						$rcontrols .= '<span>'.$rgcpn[1].'</span>';
						$rcontrols .= self::createRadiobutton($vars, $rf['name'], $rgcpn[0], $vars['template_data']['form_values'][$rf['name']], $rf['html_attribs'].$checked_html, $rf['oncheck']);
					}
					$rcontrols .= '</div>';
					$rafter .= '<div class="urontrol height-'.$rf['row_height'].' line-height-'.$rf['after_line_height'].' padding-top-4 padding-bottom-4 position-relative'.$rf['classes'].'">';
					$rafter .= '<div class="position-relative" style="height: 100%; top: 50%;">';
					$rafter .= '<div class="position-relative" style="height: 100%; top: -50%;">';
					$rafter .= $rf['after_content'];
					$rafter .= '</div>';
					$rafter .= '</div>';
					$rafter .= '</div>';
					break;
				case 'sl' :
					$label = $rf['label'];
					if(!empty($label)) {
						$label .= (!empty($rf['required'])?$required_html:'').':';
					}
					$invalid_class = '';
					$invalid_class2 = '';
					if(!empty($vars['template_data']['invalid_fields'][$rf['name']])) {
						$invalid_class = 'amz_red tpt_invalid_field ';
						$invalid_class2 = ' tpt_invalid_field ';
					}
//            $select = $vars['modules']['handler']->modules[$rf['value']]->{$rf['name'].'Select'}($vars);
					$select = getModule($vars,$rf['value'])->{$rf['name'].'Select'}($vars);
					$rlabels .= '<div id="'.$id_prefix.'_tptformlabel" class="urontrol urlabel'.$label_width_class.' '.$invalid_class.'height-'.$rf['row_height'].' line-height-'.$rf['label_line_height'].' padding-top-4 padding-bottom-4'.$rf['classes'].'">'.$label.'</div>';
					$rcontrols .= '<div id="'.$rf['name'].'_tptformcontrol'.'" class="'.$invalid_class2.' urontrol height-'.$rf['row_height'].' line-height-'.$rf['control_line_height'].' padding-top-4 padding-bottom-4'.$rf['classes'].'">'.$select;
					$rcontrols .= '</div>';
					$rafter .= '<div class="urontrol height-'.$rf['row_height'].' line-height-'.$rf['after_line_height'].' padding-top-4 padding-bottom-4 position-relative'.$rf['classes'].'">';
					$rafter .= '<div class="position-relative" style="height: 100%; top: 50%;">';
					$rafter .= '<div class="position-relative" style="height: 100%; top: -50%;">';
					$rafter .= $rf['after_content'];
					$rafter .= '</div>';
					$rafter .= '</div>';
					$rafter .= '</div>';
					break;
				case 'stsel' :
					$country = $vars['template_data']['form_values']['country'];
					$state = $vars['template_data']['form_values']['state'];
					$shipping = false;
					if($rf['name'] == 'shipping_state') {
						$country = $vars['template_data']['form_values']['shipping_country'];
						$state = $vars['template_data']['form_values']['shipping_state'];
						$shipping = true;
					}

					include(TPT_TEMPLATES_DIR.DIRECTORY_SEPARATOR.'states.tpt.php');

					$label = $rf['label'];
					if(!empty($label)) {
						$label .= (!empty($rf['required'])?$required_html:'').':';
					}
					$invalid_class = '';
					$invalid_class2 = '';
					if(!empty($vars['template_data']['invalid_fields'][$rf['name']])) {
						$invalid_class = 'amz_red tpt_invalid_field ';
						$invalid_class2 = ' tpt_invalid_field ';
					}

					$rlabels .= '<div id="'.$id_prefix.'_tptformlabel" class="urontrol urlabel'.$label_width_class.' '.$invalid_class.'height-'.$rf['row_height'].' line-height-'.$rf['label_line_height'].' padding-top-4 padding-bottom-4'.$rf['classes'].'">'.$label.'</div>';
					$rcontrols .= '<div id="'.$rf['name'].'_tptformcontrol'.'" class="'.$invalid_class2.' urontrol height-'.$rf['row_height'].' line-height-'.$rf['control_line_height'].' padding-top-4 padding-bottom-4'.$rf['classes'].'">'.$states;
					$rcontrols .= '</div>';
					$rafter .= '<div class="urontrol height-'.$rf['row_height'].' line-height-'.$rf['after_line_height'].' padding-top-4 padding-bottom-4 position-relative'.$rf['classes'].'">';
					$rafter .= '<div class="position-relative" style="height: 100%; top: 50%;">';
					$rafter .= '<div class="position-relative" style="height: 100%; top: -50%;">';
					$rafter .= $rf['after_content'];
					$rafter .= '</div>';
					$rafter .= '</div>';
					$rafter .= '</div>';
					break;
				case 'c' :
				default :
					$label = $rf['label'];
					if(!empty($label)) {
						$label .= (!empty($rf['required'])?$required_html:'').':';
					}
					$invalid_class = '';
					$invalid_class2 = '';
					if(!empty($vars['template_data']['invalid_fields'][$rf['name']])) {
						$invalid_class = 'amz_red tpt_invalid_field ';
						$invalid_class2 = ' tpt_invalid_field ';
					}
					$rlabels .= '<div id="'.$id_prefix.'_tptformlabel" class="urontrol urlabel'.$label_width_class.' '.$invalid_class.'height-'.$rf['row_height'].' line-height-'.$rf['label_line_height'].' padding-top-4 padding-bottom-4'.$rf['classes'].'">'.$label.'</div>';
					$rcontrols .= '<div id="'.$rf['name'].'_tptformcontrol'.'" class="'.$invalid_class2.' urontrol height-'.$rf['row_height'].' line-height-'.$rf['control_line_height'].' padding-top-4 padding-bottom-4'.$rf['classes'].'">'.self::createCheckbox($vars, $rf['name'], $rf['value'], isset($vars['template_data']['form_values'][$rf['name']])?$vars['template_data']['form_values'][$rf['name']]:'', $rf['html_attribs'], $rf['oncheck'], $rf['onuncheck']);
					$rcontrols .= '</div>';
					$rafter .= '<div class="urontrol height-'.$rf['row_height'].' line-height-'.$rf['after_line_height'].' padding-top-4 padding-bottom-4 position-relative'.$rf['classes'].'">';
					$rafter .= '<div class="position-relative" style="height: 100%; top: 50%;">';
					$rafter .= '<div class="position-relative" style="height: 100%; top: -50%;">';
					$rafter .= $rf['after_content'];
					$rafter .= '</div>';
					$rafter .= '</div>';
					$rafter .= '</div>';
					break;
			}
		}

		if(!empty($frl)) {
			$rlabels .= implode("\n", $frl);
			$rcontrols .= implode("\n", $frc);
			$rafter .= implode("\n", $fra);
		}

		$section .= '<div class="float-left text-align-right">'.$rlabels.'</div>';
		$section .= '<div class="float-left text-align-left padding-left-10 width-50prc">'.$rcontrols.'</div>';
		$section .= '<div class="overflow-hidden text-align-left padding-left-10">'.$rafter.'</div>';

		$section .= '</div>';
		$section .= '</div>';
		$section .= '</div>';
		$sections[] = $section;
		$sections[] = '<div class="tpt_form_section"></div>';



		$rfields = '';
		$rfields .= '<div class="">';
		$rfields .= implode("\n", $sections);
		$rfields .= '</div>';

// wrap options section into an expandable js box
		$wrapid = '';
		if(!empty($fwrapid)) {
			//$wrapid = $fwrapid;
			$wrapid = 'id="'.$fwrapid.'"';
		}
		$form_fields = <<< EOT
	<div $wrapid class="clearBoth">
        $rfields
    </div>
EOT;

		return $form_fields;
	}

	static function render_form_fields2(&$vars, $fields_data2, $label_width_class= '') {
		global $tpt_vars;

		$required_html = '<span class="amz_red">*</span>';


		$section_html = '';

		$rlabels2 = '';
		$rcontrols2 = '';
		$rafter2 = '';

		foreach($fields_data2 as $rf2) {
			switch(strtolower($rf2['control'])) {
				case 'e' :
					break;
				case 's' :
					$label2 = $rf2['label'];
					if(!empty($label2)) {
						$label2 .= (!empty($rf2['required'])?$required_html:'').':';
					}
					$rlabels2 .= '<div id="'.$rf2['name'].'_tptformlabel" class="urontrol urlabel'.$label_width_class.' height-'.$rf2['row_height'].' line-height-'.$rf2['label_line_height'].' padding-top-4 padding-bottom-4 padding-top-4 padding-bottom-4'.$rf2['classes'].'">'.$label2.'</div>';
					if(preg_match('#^\{(.*)\}$#', $rf2['value'], $mtch)) {
						$ccmp = explode(':', $mtch[1]);
						include(TPT_TEMPLATES_DIR.DIRECTORY_SEPARATOR.$ccmp[1]);

						$rcontrols2 .= '<div id="'.$rf2['name'].'_tptformcontrol'.'" class="urontrol height-'.$rf2['row_height'].' line-height-'.$rf2['control_line_height'].' padding-top-4 padding-bottom-4'.$rf2['classes'].'">'.${$ccmp[0]};
						$rcontrols2 .= '</div>';
					} else {
						$rcontrols2 .= '<div id="'.$rf2['name'].'_tptformcontrol'.'" class="urontrol height-'.$rf2['row_height'].' line-height-'.$rf2['control_line_height'].' padding-top-4 padding-bottom-4'.$rf2['classes'].'">'.$rf2['value'];
						$rcontrols2 .= '</div>';
					}
					$rafter2 .= '<div class="urontrol height-'.$rf2['row_height'].' line-height-'.$rf2['after_line_height'].' padding-top-4 padding-bottom-4 position-relative'.$rf2['classes'].'">';
					$rafter2 .= '<div class="position-relative" style="height: 100%; top: 50%;">';
					$rafter2 .= '<div class="position-relative" style="height: 100%; top: -50%;">';
					$rafter2 .= $rf2['after_content'];
					$rafter2 .= '</div>';
					$rafter2 .= '</div>';
					$rafter2 .= '</div>';
					break;
				case 't' :
					$label2 = $rf2['label'];
					if(!empty($label2)) {
						$label2 .= (!empty($rf2['required'])?$required_html:'').':';
					}
					$invalid_class = '';
					$invalid_class2 = '';
					if(!empty($vars['template_data']['invalid_fields'][$rf2['name']])) {
						$invalid_class = 'amz_red tpt_invalid_field ';
						$invalid_class2 = ' tpt_invalid_field ';
					}
					$rlabels2 .= '<div id="'.$rf2['name'].'_tptformlabel" class="urontrol urlabel'.$label_width_class.' '.$invalid_class.'height-'.$rf2['row_height'].' line-height-'.$rf2['label_line_height'].' padding-top-4 padding-bottom-4'.$rf2['classes'].'">'.$label2.'</div>';
					$rcontrols2 .= '<div id="'.$rf2['name'].'_tptformcontrol'.'" class="'.$invalid_class2.' urontrol height-'.$rf2['row_height'].' line-height-'.$rf2['control_line_height'].' padding-top-4 padding-bottom-4'.$rf2['classes'].'">';
					//$rcontrols2 .= '<div style="background-image: url('.$tpt_imagesurl.'/user-form-field-left.png);" class="padding-left-8 background-position-LC background-repeat-no-repeat">';
					//$rcontrols2 .= '<div style="background-image: url('.$tpt_imagesurl.'/user-form-field-right.png);" class="padding-right-8 background-position-RC background-repeat-no-repeat">';
					//$rcontrols2 .= '<div style="background-image: url('.$tpt_imagesurl.'/user-form-field-mid.png);" class="background-repeat-repeat-x background-position-CC">';
					$rcontrols2 .= self::createTextinput($vars, $rf2['name'], isset($vars['template_data']['form_values'][$rf2['name']])?$vars['template_data']['form_values'][$rf2['name']]:'', ' size="5" class="plain-input-field padding-left-2 padding-right-2 padding-top-2 padding-bottom-2 font-size-14" '.$rf2['html_attribs']);
					//$rcontrols2 .= '</div>';
					//$rcontrols2 .= '</div>';
					//$rcontrols2 .= '</div>';
					$rcontrols2 .= '</div>';
					$rafter2 .= '<div class="urontrol height-'.$rf2['row_height'].' line-height-'.$rf2['after_line_height'].' padding-top-4 padding-bottom-4 position-relative'.$rf2['classes'].'">';
					$rafter2 .= '<div class="position-relative" style="height: 100%; top: 50%;">';
					$rafter2 .= '<div class="position-relative" style="height: 100%; top: -50%;">';
					$rafter2 .= $rf2['after_content'];
					$rafter2 .= '</div>';
					$rafter2 .= '</div>';
					$rafter2 .= '</div>';
					break;
				case 'p' :
					$label2 = $rf2['label'];
					if(!empty($label2)) {
						$label2 .= (!empty($rf2['required'])?$required_html:'').':';
					}
					$invalid_class = '';
					$invalid_class2 = '';
					if(!empty($vars['template_data']['invalid_fields'][$rf2['name']])) {
						$invalid_class = 'amz_red tpt_invalid_field ';
						$invalid_class2 = ' tpt_invalid_field ';
					}
					$rlabels2 .= '<div id="'.$rf2['name'].'_tptformlabel" class="urontrol urlabel'.$label_width_class.' '.$invalid_class.'height-'.$rf2['row_height'].' line-height-'.$rf2['label_line_height'].' padding-top-4 padding-bottom-4'.$rf2['classes'].'">'.$label2.'</div>';
					$rcontrols2 .= '<div id="'.$rf2['name'].'_tptformcontrol'.'" class="'.$invalid_class2.' urontrol height-'.$rf2['row_height'].' line-height-'.$rf2['control_line_height'].' padding-top-4 padding-bottom-4'.$rf2['classes'].'">';
					//$rcontrols2 .= '<div style="background-image: url('.$tpt_imagesurl.'/user-form-field-left.png);" class="padding-left-8 background-position-LC background-repeat-no-repeat">';
					//$rcontrols2 .= '<div style="background-image: url('.$tpt_imagesurl.'/user-form-field-right.png);" class="padding-right-8 background-position-RC background-repeat-no-repeat">';
					//$rcontrols2 .= '<div style="background-image: url('.$tpt_imagesurl.'/user-form-field-mid.png);" class="background-repeat-repeat-x background-position-CC">';
					$rcontrols2 .= self::createPasswordinput($vars, $rf2['name'], '', ' size="5" class="plain-input-field padding-left-2 padding-right-2 padding-top-2 padding-bottom-2 font-size-14" '.$rf2['html_attribs']);
					//$rcontrols2 .= '</div>';
					//$rcontrols2 .= '</div>';
					//$rcontrols2 .= '</div>';
					$rcontrols2 .= '</div>';
					$rafter2 .= '<div class="urontrol height-'.$rf2['row_height'].' line-height-'.$rf2['after_line_height'].' padding-top-4 padding-bottom-4 position-relative'.$rf2['classes'].'">';
					$rafter2 .= '<div class="position-relative" style="height: 100%; top: 50%;">';
					$rafter2 .= '<div class="position-relative" style="height: 100%; top: -50%;">';
					$rafter2 .= $rf2['after_content'];
					$rafter2 .= '</div>';
					$rafter2 .= '</div>';
					$rafter2 .= '</div>';
					break;
				case 'r' :
					$label2 = $rf2['label'];
					if(!empty($label2)) {
						$label2 .= (!empty($rf2['required'])?$required_html:'').':';
					}
					$invalid_class = '';
					$invalid_class2 = '';
					if(!empty($vars['template_data']['invalid_fields'][$rf2['name']])) {
						$invalid_class = 'amz_red tpt_invalid_field ';
						$invalid_class2 = ' tpt_invalid_field ';
					}
					$rlabels2 .= '<div id="'.$rf2['name'].'_tptformlabel" class="urontrol urlabel'.$label_width_class.' '.$invalid_class.'height-'.$rf2['row_height'].' line-height-'.$rf2['label_line_height'].' padding-top-4 padding-bottom-4'.$rf2['classes'].'">'.$label2.':</div>';
					$rcontrols2 .= '<div id="'.$rf2['name'].'_tptformcontrol'.'" class="'.$invalid_class2.' urontrol height-'.$rf2['row_height'].' line-height-'.$rf2['control_line_height'].' padding-top-4 padding-bottom-4'.$rf2['classes'].'">'.self::createRadiobutton($vars, $rf2['name'], $rf2['value'], $vars['template_data']['form_values'][$rf2['name']], $rf2['html_attribs'], $rf2['oncheck']);
					$rcontrols2 .= '</div>';
					$rafter2 .= '<div class="urontrol height-'.$rf2['row_height'].' line-height-'.$rf2['after_line_height'].' padding-top-4 padding-bottom-4 position-relative'.$rf2['classes'].'">';
					$rafter2 .= '<div class="position-relative" style="height: 100%; top: 50%;">';
					$rafter2 .= '<div class="position-relative" style="height: 100%; top: -50%;">';
					$rafter2 .= $rf2['after_content'];
					$rafter2 .= '</div>';
					$rafter2 .= '</div>';
					$rafter2 .= '</div>';
					break;
				case 'rg' :
					$label2 = $rf2['label'];
					if(!empty($label2)) {
						$label2 .= (!empty($rf2['required'])?$required_html:'').':';
					}
					$invalid_class = '';
					$invalid_class2 = '';
					if(!empty($vars['template_data']['invalid_fields'][$rf2['name']])) {
						$invalid_class = 'amz_red tpt_invalid_field ';
						$invalid_class2 = ' tpt_invalid_field ';
					}
					$rgroup = explode(',', $rf2['value']);
					$rlabels2 .= '<div id="'.$rf2['name'].'_tptformlabel" class="urontrol urlabel'.$label_width_class.' '.$invalid_class.'height-'.$rf2['row_height'].' line-height-'.$rf2['label_line_height'].' padding-top-4 padding-bottom-4'.$rf2['classes'].'">'.$label2.'</div>';
					$rcontrols2 .= '<div id="'.$rf2['name'].'_tptformcontrol'.'" class="'.$invalid_class2.' urontrol height-'.$rf2['row_height'].' line-height-'.$rf2['control_line_height'].' padding-top-4 padding-bottom-4'.$rf2['classes'].'">';
					foreach($rgroup as $rg) {
						$rgcpn = explode(':', $rg);
						$checked_html = '';
						if(!empty($rgcpn[2])) {
							if(!isset($vars['template_data']['form_values'][$rf2['name']])) {
								$checked_html = ' checked="checked"';
							}
						}
						$rcontrols2 .= '<span>'.$rgcpn[1].'</span>';
						$rcontrols2 .= self::createRadiobutton($vars, $rf2['name'], $rgcpn[0], $vars['template_data']['form_values'][$rf2['name']], $rf2['html_attribs'].$checked_html, $rf2['oncheck']);
					}
					$rcontrols2 .= '</div>';
					$rafter2 .= '<div class="urontrol height-'.$rf2['row_height'].' line-height-'.$rf2['after_line_height'].' padding-top-4 padding-bottom-4 position-relative'.$rf2['classes'].'">';
					$rafter2 .= '<div class="position-relative" style="height: 100%; top: 50%;">';
					$rafter2 .= '<div class="position-relative" style="height: 100%; top: -50%;">';
					$rafter2 .= $rf2['after_content'];
					$rafter2 .= '</div>';
					$rafter2 .= '</div>';
					$rafter2 .= '</div>';
					break;
				case 'sl' :
					$label2 = $rf2['label'];
					if(!empty($label2)) {
						$label2 .= (!empty($rf2['required'])?$required_html:'').':';
					}
					$invalid_class = '';
					$invalid_class2 = '';
					if(!empty($vars['template_data']['invalid_fields'][$rf2['name']])) {
						$invalid_class = 'amz_red tpt_invalid_field ';
						$invalid_class2 = ' tpt_invalid_field ';
					}
//            $select = $vars['modules']['handler']->modules[$rf2['value']]->{$rf2['name'].'Select'}($vars);
					$select = getModule($vars,$rf2['value'])->{$rf2['name'].'Select'}($vars);
					$rlabels2 .= '<div id="'.$rf2['name'].'_tptformlabel" class="urontrol urlabel'.$label_width_class.' '.$invalid_class.'height-'.$rf2['row_height'].' line-height-'.$rf2['label_line_height'].' padding-top-4 padding-bottom-4'.$rf2['classes'].'">'.$label2.'</div>';
					$rcontrols2 .= '<div id="'.$rf2['name'].'_tptformcontrol'.'" class="'.$invalid_class2.' urontrol height-'.$rf2['row_height'].' line-height-'.$rf2['control_line_height'].' padding-top-4 padding-bottom-4'.$rf2['classes'].'">'.$select;
					$rcontrols2 .= '</div>';
					$rafter2 .= '<div class="urontrol height-'.$rf2['row_height'].' line-height-'.$rf2['after_line_height'].' padding-top-4 padding-bottom-4 position-relative'.$rf2['classes'].'">';
					$rafter2 .= '<div class="position-relative" style="height: 100%; top: 50%;">';
					$rafter2 .= '<div class="position-relative" style="height: 100%; top: -50%;">';
					$rafter2 .= $rf2['after_content'];
					$rafter2 .= '</div>';
					$rafter2 .= '</div>';
					$rafter2 .= '</div>';
					break;
				case 'stsel' :
					$country = isset($vars['template_data']['form_values']['country'])?$vars['template_data']['form_values']['country']:'';
					$state = isset($vars['template_data']['form_values']['state'])?$vars['template_data']['form_values']['state']:'';
					$shipping = false;
					if($rf2['name'] == 'shipping_state') {
						$country = isset($vars['template_data']['form_values']['shipping_country'])?$vars['template_data']['form_values']['shipping_country']:'';
						$state = isset($vars['template_data']['form_values']['shipping_state'])?$vars['template_data']['form_values']['shipping_state']:'';
						$shipping = true;
					}

					include(TPT_TEMPLATES_DIR.DIRECTORY_SEPARATOR.'states.tpt.php');

					$label2 = $rf2['label'];
					if(!empty($label2)) {
						$label2 .= (!empty($rf2['required'])?$required_html:'').':';
					}
					$invalid_class = '';
					$invalid_class2 = '';
					if(!empty($vars['template_data']['invalid_fields'][$rf2['name']])) {
						$invalid_class = 'amz_red tpt_invalid_field ';
						$invalid_class2 = ' tpt_invalid_field ';
					}

					$rlabels2 .= '<div id="'.$rf2['name'].'_tptformlabel" class="urontrol urlabel'.$label_width_class.' '.$invalid_class.'height-'.$rf2['row_height'].' line-height-'.$rf2['label_line_height'].' padding-top-4 padding-bottom-4'.$rf2['classes'].'">'.$label2.'</div>';
					$rcontrols2 .= '<div id="'.$rf2['name'].'_tptformcontrol'.'" class="'.$invalid_class2.' urontrol height-'.$rf2['row_height'].' line-height-'.$rf2['control_line_height'].' padding-top-4 padding-bottom-4'.$rf2['classes'].'">'.$states;
					$rcontrols2 .= '</div>';
					$rafter2 .= '<div class="urontrol height-'.$rf2['row_height'].' line-height-'.$rf2['after_line_height'].' padding-top-4 padding-bottom-4 position-relative'.$rf2['classes'].'">';
					$rafter2 .= '<div class="position-relative" style="height: 100%; top: 50%;">';
					$rafter2 .= '<div class="position-relative" style="height: 100%; top: -50%;">';
					$rafter2 .= $rf2['after_content'];
					$rafter2 .= '</div>';
					$rafter2 .= '</div>';
					$rafter2 .= '</div>';
					break;
				case 'c' :
				default :
					$label2 = $rf2['label'];
					if(!empty($label2)) {
						$label2 .= (!empty($rf2['required'])?$required_html:'').':';
					}
					$invalid_class = '';
					$invalid_class2 = '';
					if(!empty($vars['template_data']['invalid_fields'][$rf2['name']])) {
						$invalid_class = 'amz_red tpt_invalid_field ';
						$invalid_class2 = ' tpt_invalid_field ';
					}
					$rlabels2 .= '<div id="'.$rf2['name'].'_tptformlabel" class="'.$invalid_class2.' urontrol urlabel'.$label_width_class.' '.$invalid_class.'height-'.$rf2['row_height'].' line-height-'.$rf2['label_line_height'].' padding-top-4 padding-bottom-4'.$rf2['classes'].'">'.$label2.'</div>';
					$rcontrols2 .= '<div id="'.$rf2['name'].'_tptformcontrol'.'" class="urontrol height-'.$rf2['row_height'].' line-height-'.$rf2['control_line_height'].' padding-top-4 padding-bottom-4'.$rf2['classes'].'">'.self::createCheckbox($vars, $rf2['name'], $rf2['value'], $vars['template_data']['form_values'][$rf2['name']], $rf2['html_attribs'], $rf2['oncheck'], $rf2['onuncheck']);
					$rcontrols2 .= '</div>';
					$rafter2 .= '<div class="urontrol height-'.$rf2['row_height'].' line-height-'.$rf2['after_line_height'].' padding-top-4 padding-bottom-4 position-relative'.$rf2['classes'].'">';
					$rafter2 .= '<div class="position-relative" style="height: 100%; top: 50%;">';
					$rafter2 .= '<div class="position-relative" style="height: 100%; top: -50%;">';
					$rafter2 .= $rf2['after_content'];
					$rafter2 .= '</div>';
					$rafter2 .= '</div>';
					$rafter2 .= '</div>';
					break;
			}
		}



		$section_html .= '<div class="float-left text-align-right">'.$rlabels2.'</div>';
		$section_html .= '<div class="float-left text-align-left padding-left-10 width-50prc">'.$rcontrols2.'</div>';
		$section_html .= '<div class="overflow-hidden text-align-left padding-left-10">'.$rafter2.'</div>';

		return $section_html;
	}

	static function render_form_fields3(&$vars, $fields_data3, $label_width_class= '') {
		global $tpt_vars;

		$tpt_imagesurl = TPT_IMAGES_URL;
		
		$required_html = '<span class="amz_red">*</span>';


		$section_html = '';

		$rlabels2 = '';
		$rcontrols2 = '';
		$rafter2 = '';

		if(!empty($fbl3))
			$rlabels2 = implode($fbl3);
		if(!empty($fbc3))
			$rcontrols2 = implode($fbc3);
		if(!empty($fba3))
			$rafter2 = implode($fba3);


		foreach($fields_data3 as $rf2) {
			switch(strtolower($rf2['control'])) {
				case 'e' :
					break;
				case 's' :
					$label2 = $rf2['label'];
					if(!empty($label2)) {
						$label2 .= (!empty($rf2['required'])?$required_html:'').':';
					}
					$rlabels2 .= '<div id="'.$rf2['name'].'_tptformlabel" class="urontrol urlabel'.$label_width_class.' height-'.$rf2['row_height'].' line-height-'.$rf2['label_line_height'].' padding-top-4 padding-bottom-4 padding-top-4 padding-bottom-4'.$rf2['classes'].'">'.$label2.'</div>';
					if(preg_match('#^\{(.*)\}$#', $rf2['value'], $mtch)) {
						$ccmp = explode(':', $mtch[1]);
						include(TPT_TEMPLATES_DIR.DIRECTORY_SEPARATOR.$ccmp[1]);

						$rcontrols2 .= '<div id="'.$rf2['name'].'_tptformcontrol'.'" class="urontrol height-'.$rf2['row_height'].' line-height-'.$rf2['control_line_height'].' padding-top-4 padding-bottom-4'.$rf2['classes'].'">'.${$ccmp[0]};
						$rcontrols2 .= '</div>';
					} else {
						$rcontrols2 .= '<div id="'.$rf2['name'].'_tptformcontrol'.'" class="urontrol height-'.$rf2['row_height'].' line-height-'.$rf2['control_line_height'].' padding-top-4 padding-bottom-4'.$rf2['classes'].'">'.$rf2['value'];
						$rcontrols2 .= '</div>';
					}
					$rafter2 .= '<div class="urontrol height-'.$rf2['row_height'].' line-height-'.$rf2['after_line_height'].' padding-top-4 padding-bottom-4 position-relative'.$rf2['classes'].'">';
					$rafter2 .= '<div class="position-relative" style="height: 100%; top: 50%;">';
					$rafter2 .= '<div class="position-relative" style="height: 100%; top: -50%;">';
					$rafter2 .= $rf2['after_content'];
					$rafter2 .= '</div>';
					$rafter2 .= '</div>';
					$rafter2 .= '</div>';
					break;
				case 't' :
					$label2 = $rf2['label'];
					if(!empty($label2)) {
						$label2 .= (!empty($rf2['required'])?$required_html:'').':';
					}
					$invalid_class = '';
					$invalid_class2 = '';
					if(!empty($vars['template_data']['invalid_fields'][$rf2['name']])) {
						$invalid_class = 'amz_red tpt_invalid_field ';
						$invalid_class2 = ' tpt_invalid_field ';
					}
					$rlabels2 .= '<div id="'.$rf2['name'].'_tptformlabel" class="urontrol urlabel'.$label_width_class.' '.$invalid_class.'height-'.$rf2['row_height'].' line-height-'.$rf2['label_line_height'].' padding-top-4 padding-bottom-4'.$rf2['classes'].'">'.$label2.'</div>';
					$rcontrols2 .= '<div id="'.$rf2['name'].'_tptformcontrol'.'" class="'.$invalid_class2.' urontrol height-'.$rf2['row_height'].' line-height-'.$rf2['control_line_height'].' padding-top-4 padding-bottom-4'.$rf2['classes'].'">';
					$rcontrols2 .= '<div style="background-image: url('.$tpt_imagesurl.'/user-form-field-left.png);" class="padding-left-8 background-position-LC background-repeat-no-repeat">';
					$rcontrols2 .= '<div style="background-image: url('.$tpt_imagesurl.'/user-form-field-right.png);" class="padding-right-8 background-position-RC background-repeat-no-repeat">';
					$rcontrols2 .= '<div style="background-image: url('.$tpt_imagesurl.'/user-form-field-mid.png);" class="background-repeat-repeat-x">';
					$rcontrols2 .= self::createTextinput($vars, $rf2['name'], ((empty($rf2['value'])&&!empty($vars['template_data']['form_values'][$rf2['name']]))?$vars['template_data']['form_values'][$rf2['name']]:$rf2['value']), ' size="5" class="plain-input-field padding-top-3 padding-bottom-3 font-size-14" '.$rf2['html_attribs']);
					$rcontrols2 .= '</div>';
					$rcontrols2 .= '</div>';
					$rcontrols2 .= '</div>';
					$rcontrols2 .= '</div>';
					$rafter2 .= '<div class="urontrol height-'.$rf2['row_height'].' line-height-'.$rf2['after_line_height'].' padding-top-4 padding-bottom-4 position-relative'.$rf2['classes'].'">';
					$rafter2 .= '<div class="position-relative" style="height: 100%; top: 50%;">';
					$rafter2 .= '<div class="position-relative" style="height: 100%; top: -50%;">';
					$rafter2 .= $rf2['after_content'];
					$rafter2 .= '</div>';
					$rafter2 .= '</div>';
					$rafter2 .= '</div>';
					break;
				case 'p' :
					$label2 = $rf2['label'];
					if(!empty($label2)) {
						$label2 .= (!empty($rf2['required'])?$required_html:'').':';
					}
					$invalid_class = '';
					$invalid_class2 = '';
					if(!empty($vars['template_data']['invalid_fields'][$rf2['name']])) {
						$invalid_class = 'amz_red tpt_invalid_field ';
						$invalid_class2 = ' tpt_invalid_field ';
					}
					$rlabels2 .= '<div id="'.$rf2['name'].'_tptformlabel" class="urontrol urlabel'.$label_width_class.' '.$invalid_class.'height-'.$rf2['row_height'].' line-height-'.$rf2['label_line_height'].' padding-top-4 padding-bottom-4'.$rf2['classes'].'">'.$label2.'</div>';
					$rcontrols2 .= '<div id="'.$rf2['name'].'_tptformcontrol'.'" class="'.$invalid_class2.' urontrol height-'.$rf2['row_height'].' line-height-'.$rf2['control_line_height'].' padding-top-4 padding-bottom-4'.$rf2['classes'].'">';
					$rcontrols2 .= '<div style="background-image: url('.$tpt_imagesurl.'/user-form-field-left.png);" class="padding-left-8 background-position-LC background-repeat-no-repeat">';
					$rcontrols2 .= '<div style="background-image: url('.$tpt_imagesurl.'/user-form-field-right.png);" class="padding-right-8 background-position-RC background-repeat-no-repeat">';
					$rcontrols2 .= '<div style="background-image: url('.$tpt_imagesurl.'/user-form-field-mid.png);" class="background-repeat-repeat-x">';
					$rcontrols2 .= self::createPasswordinput($vars, $rf2['name'], '', ' size="5" class="plain-input-field padding-top-3 padding-bottom-3 font-size-14" '.$rf2['html_attribs']);
					$rcontrols2 .= '</div>';
					$rcontrols2 .= '</div>';
					$rcontrols2 .= '</div>';
					$rcontrols2 .= '</div>';
					$rafter2 .= '<div class="urontrol height-'.$rf2['row_height'].' line-height-'.$rf2['after_line_height'].' padding-top-4 padding-bottom-4 position-relative'.$rf2['classes'].'">';
					$rafter2 .= '<div class="position-relative" style="height: 100%; top: 50%;">';
					$rafter2 .= '<div class="position-relative" style="height: 100%; top: -50%;">';
					$rafter2 .= $rf2['after_content'];
					$rafter2 .= '</div>';
					$rafter2 .= '</div>';
					$rafter2 .= '</div>';
					break;
				case 'r' :
					$label2 = $rf2['label'];
					if(!empty($label2)) {
						$label2 .= (!empty($rf2['required'])?$required_html:'').':';
					}
					$invalid_class = '';
					$invalid_class2 = '';
					if(!empty($vars['template_data']['invalid_fields'][$rf2['name']])) {
						$invalid_class = 'amz_red tpt_invalid_field ';
						$invalid_class2 = ' tpt_invalid_field ';
					}
					$rlabels2 .= '<div id="'.$rf2['name'].'_tptformlabel" class="urontrol urlabel'.$label_width_class.' '.$invalid_class.'height-'.$rf2['row_height'].' line-height-'.$rf2['label_line_height'].' padding-top-4 padding-bottom-4'.$rf2['classes'].'">'.$label2.':</div>';
					$rcontrols2 .= '<div id="'.$rf2['name'].'_tptformcontrol'.'" class="'.$invalid_class2.' urontrol height-'.$rf2['row_height'].' line-height-'.$rf2['control_line_height'].' padding-top-4 padding-bottom-4'.$rf2['classes'].'">'.self::createRadiobutton($vars, $rf2['name'], $rf2['value'], $vars['template_data']['form_values'][$rf2['name']], $rf2['html_attribs'], $rf2['oncheck']);
					$rcontrols2 .= '</div>';
					$rafter2 .= '<div class="urontrol height-'.$rf2['row_height'].' line-height-'.$rf2['after_line_height'].' padding-top-4 padding-bottom-4 position-relative'.$rf2['classes'].'">';
					$rafter2 .= '<div class="position-relative" style="height: 100%; top: 50%;">';
					$rafter2 .= '<div class="position-relative" style="height: 100%; top: -50%;">';
					$rafter2 .= $rf2['after_content'];
					$rafter2 .= '</div>';
					$rafter2 .= '</div>';
					$rafter2 .= '</div>';
					break;
				case 'rg' :
					$label2 = $rf2['label'];
					if(!empty($label2)) {
						$label2 .= (!empty($rf2['required'])?$required_html:'').':';
					}
					$invalid_class = '';
					$invalid_class2 = '';
					if(!empty($vars['template_data']['invalid_fields'][$rf2['name']])) {
						$invalid_class = 'amz_red tpt_invalid_field ';
						$invalid_class2 = ' tpt_invalid_field ';
					}
					$rgroup = explode(',', $rf2['value']);
					$rlabels2 .= '<div id="'.$rf2['name'].'_tptformlabel" class="urontrol urlabel'.$label_width_class.' '.$invalid_class.'height-'.$rf2['row_height'].' line-height-'.$rf2['label_line_height'].' padding-top-4 padding-bottom-4'.$rf2['classes'].'">'.$label2.'</div>';
					$rcontrols2 .= '<div id="'.$rf2['name'].'_tptformcontrol'.'" class="'.$invalid_class2.' urontrol height-'.$rf2['row_height'].' line-height-'.$rf2['control_line_height'].' padding-top-4 padding-bottom-4'.$rf2['classes'].'">';
					foreach($rgroup as $rg) {
						$rgcpn = explode(':', $rg);
						$checked_html = '';
						if(!empty($rgcpn[2])) {
							if(!isset($vars['template_data']['form_values'][$rf2['name']])) {
								$checked_html = ' checked="checked"';
							}
						}
						$rcontrols2 .= '<span>'.$rgcpn[1].'</span>';
						$rcontrols2 .= self::createRadiobutton($vars, $rf2['name'], $rgcpn[0], $vars['template_data']['form_values'][$rf2['name']], $rf2['html_attribs'].$checked_html, $rf2['oncheck']);
					}
					$rcontrols2 .= '</div>';
					$rafter2 .= '<div class="urontrol height-'.$rf2['row_height'].' line-height-'.$rf2['after_line_height'].' padding-top-4 padding-bottom-4 position-relative'.$rf2['classes'].'">';
					$rafter2 .= '<div class="position-relative" style="height: 100%; top: 50%;">';
					$rafter2 .= '<div class="position-relative" style="height: 100%; top: -50%;">';
					$rafter2 .= $rf2['after_content'];
					$rafter2 .= '</div>';
					$rafter2 .= '</div>';
					$rafter2 .= '</div>';
					break;
				case 'sl' :
					$label2 = $rf2['label'];
					if(!empty($label2)) {
						$label2 .= (!empty($rf2['required'])?$required_html:'').':';
					}
					$invalid_class = '';
					$invalid_class2 = '';
					if(!empty($vars['template_data']['invalid_fields'][$rf2['name']])) {
						$invalid_class = 'amz_red tpt_invalid_field ';
						$invalid_class2 = ' tpt_invalid_field ';
					}
//            $select = $vars['modules']['handler']->modules[$rf2['value']]->{$rf2['name'].'Select'}($vars);
					$select = getModule($vars,$rf2['value'])->{$rf2['name'].'Select'}($vars);


					$rlabels2 .= '<div id="'.$rf2['name'].'_tptformlabel" class="urontrol urlabel'.$label_width_class.' '.$invalid_class.'height-'.$rf2['row_height'].' line-height-'.$rf2['label_line_height'].' padding-top-4 padding-bottom-4'.$rf2['classes'].'">'.$label2.'</div>';
					$rcontrols2 .= '<div id="'.$rf2['name'].'_tptformcontrol'.'" class="'.$invalid_class2.' urontrol height-'.$rf2['row_height'].' line-height-'.$rf2['control_line_height'].' padding-top-4 padding-bottom-4'.$rf2['classes'].'">'.$select;
					$rcontrols2 .= '</div>';
					$rafter2 .= '<div class="urontrol height-'.$rf2['row_height'].' line-height-'.$rf2['after_line_height'].' padding-top-4 padding-bottom-4 position-relative'.$rf2['classes'].'">';
					$rafter2 .= '<div class="position-relative" style="height: 100%; top: 50%;">';
					$rafter2 .= '<div class="position-relative" style="height: 100%; top: -50%;">';
					$rafter2 .= $rf2['after_content'];
					$rafter2 .= '</div>';
					$rafter2 .= '</div>';
					$rafter2 .= '</div>';
					break;
				case 'stsel' :
					$country = $vars['template_data']['form_values']['country'];
					$state = $vars['template_data']['form_values']['state'];
					$shipping = false;
					if($rf2['name'] == 'shipping_state') {
						$country = $vars['template_data']['form_values']['shipping_country'];
						$state = $vars['template_data']['form_values']['shipping_state'];
						$shipping = true;
					}

					include(TPT_TEMPLATES_DIR.DIRECTORY_SEPARATOR.'states.tpt.php');

					$label2 = $rf2['label'];
					if(!empty($label2)) {
						$label2 .= (!empty($rf2['required'])?$required_html:'').':';
					}
					$invalid_class = '';
					$invalid_class2 = '';
					if(!empty($vars['template_data']['invalid_fields'][$rf2['name']])) {
						$invalid_class = 'amz_red tpt_invalid_field ';
						$invalid_class2 = ' tpt_invalid_field ';
					}

					$rlabels2 .= '<div id="'.$rf2['name'].'_tptformlabel" class="urontrol urlabel'.$label_width_class.' '.$invalid_class.'height-'.$rf2['row_height'].' line-height-'.$rf2['label_line_height'].' padding-top-4 padding-bottom-4'.$rf2['classes'].'">'.$label2.'</div>';
					$rcontrols2 .= '<div id="'.$rf2['name'].'_tptformcontrol'.'" class="'.$invalid_class2.' urontrol height-'.$rf2['row_height'].' line-height-'.$rf2['control_line_height'].' padding-top-4 padding-bottom-4'.$rf2['classes'].'">'.$states;
					$rcontrols2 .= '</div>';
					$rafter2 .= '<div class="urontrol height-'.$rf2['row_height'].' line-height-'.$rf2['after_line_height'].' padding-top-4 padding-bottom-4 position-relative'.$rf2['classes'].'">';
					$rafter2 .= '<div class="position-relative" style="height: 100%; top: 50%;">';
					$rafter2 .= '<div class="position-relative" style="height: 100%; top: -50%;">';
					$rafter2 .= $rf2['after_content'];
					$rafter2 .= '</div>';
					$rafter2 .= '</div>';
					$rafter2 .= '</div>';
					break;
				case 'c' :
				default :
					$label2 = $rf2['label'];
					if(!empty($label2)) {
						$label2 .= (!empty($rf2['required'])?$required_html:'').':';
					}
					$invalid_class = '';
					$invalid_class2 = '';
					if(!empty($vars['template_data']['invalid_fields'][$rf2['name']])) {
						$invalid_class = 'amz_red tpt_invalid_field ';
						$invalid_class2 = ' tpt_invalid_field ';
					}
					$rlabels2 .= '<div id="'.$rf2['name'].'_tptformlabel" class="urontrol urlabel'.$label_width_class.' '.$invalid_class.'height-'.$rf2['row_height'].' line-height-'.$rf2['label_line_height'].' padding-top-4 padding-bottom-4'.$rf2['classes'].'">'.$label2.'</div>';
					$rcontrols2 .= '<div id="'.$rf2['name'].'_tptformcontrol'.'" class="'.$invalid_class2.' urontrol height-'.$rf2['row_height'].' line-height-'.$rf2['control_line_height'].' padding-top-4 padding-bottom-4'.$rf2['classes'].'">'.self::createCheckbox($vars, $rf2['name'], $rf2['value'], $vars['template_data']['form_values'][$rf2['name']], $rf2['html_attribs'], $rf2['oncheck'], $rf2['onuncheck']);
					$rcontrols2 .= '</div>';
					$rafter2 .= '<div class="urontrol height-'.$rf2['row_height'].' line-height-'.$rf2['after_line_height'].' padding-top-4 padding-bottom-4 position-relative'.$rf2['classes'].'">';
					$rafter2 .= '<div class="position-relative" style="height: 100%; top: 50%;">';
					$rafter2 .= '<div class="position-relative" style="height: 100%; top: -50%;">';
					$rafter2 .= $rf2['after_content'];
					$rafter2 .= '</div>';
					$rafter2 .= '</div>';
					$rafter2 .= '</div>';
					break;
			}
		}



		$section_html .= '<div class="float-left text-align-right">'.$rlabels2.'</div>';
		$section_html .= '<div class="float-left text-align-left padding-left-10 width-150">'.$rcontrols2.'</div>';
		$section_html .= '<div class="overflow-hidden text-align-left padding-left-10">'.$rafter2.'</div>';

		return $section_html;
	}

	static function render_form_fields4(&$vars, $fields_data3, $label_width_class= '', $fbl3=array(), $fbc3=array(), $fba3=array()) {
		global $tpt_vars;

		$tpt_imagesurl = TPT_IMAGES_URL;
		
		$required_html = '<span class="amz_red">*</span>';


		$section_html = '';

		$rlabels2 = '';
		$rcontrols2 = '';
		$rafter2 = '';

		if(!empty($fbl3))
			$rlabels2 = implode($fbl3);
		if(!empty($fbc3))
			$rcontrols2 = implode($fbc3);
		if(!empty($fba3))
			$rafter2 = implode($fba3);


		foreach($fields_data3 as $rf2) {
			$rcontrols2 .= '<div>';
			switch(strtolower($rf2['control'])) {
				case 'e' :
					break;
				case 's' :
					$label2 = $rf2['label'];
					if(!empty($label2)) {
						$label2 .= (!empty($rf2['required'])?$required_html:'').':';
					}
					$rlabels2 .= '<div id="'.$rf2['name'].'_tptformlabel" class="urontrol urlabel'.$label_width_class.' height-'.$rf2['row_height'].' line-height-'.$rf2['label_line_height'].' padding-top-4 padding-bottom-4 padding-top-4 padding-bottom-4'.$rf2['classes'].'">'.$label2.'</div>';
					if(preg_match('#^\{(.*)\}$#', $rf2['value'], $mtch)) {
						$ccmp = explode(':', $mtch[1]);
						include(TPT_TEMPLATES_DIR.DIRECTORY_SEPARATOR.$ccmp[1]);

						$rcontrols2 .= '<div id="'.$rf2['name'].'_tptformcontrol'.'" class="urontrol height-'.$rf2['row_height'].' line-height-'.$rf2['control_line_height'].' padding-top-4 padding-bottom-4'.$rf2['classes'].'">'.${$ccmp[0]};
						$rcontrols2 .= '</div>';
					} else {
						$rcontrols2 .= '<div id="'.$rf2['name'].'_tptformcontrol'.'" class="urontrol height-'.$rf2['row_height'].' line-height-'.$rf2['control_line_height'].' padding-top-4 padding-bottom-4'.$rf2['classes'].'">'.$rf2['value'];
						$rcontrols2 .= '</div>';
					}
					$rafter2 .= '<div class="urontrol height-'.$rf2['row_height'].' line-height-'.$rf2['after_line_height'].' padding-top-4 padding-bottom-4 position-relative'.$rf2['classes'].'">';
					$rafter2 .= '<div class="position-relative" style="height: 100%; top: 50%;">';
					$rafter2 .= '<div class="position-relative" style="height: 100%; top: -50%;">';
					$rafter2 .= $rf2['after_content'];
					$rafter2 .= '</div>';
					$rafter2 .= '</div>';
					$rafter2 .= '</div>';
					break;
				case 't' :
					$label2 = $rf2['label'];
					if(!empty($label2)) {
						$label2 .= (!empty($rf2['required'])?$required_html:'').':';
					}
					$invalid_class = '';
					$invalid_class2 = '';
					if(!empty($vars['template_data']['invalid_fields'][$rf2['name']])) {
						$invalid_class = 'amz_red tpt_invalid_field ';
						$invalid_class2 = ' tpt_invalid_field ';
					}
					$rlabels2 .= '<div id="'.$rf2['name'].'_tptformlabel" class="urontrol urlabel'.$label_width_class.' '.$invalid_class.'height-'.$rf2['row_height'].' line-height-'.$rf2['label_line_height'].' padding-top-4 padding-bottom-4'.$rf2['classes'].'">'.$label2.'</div>';
					$rcontrols2 .= '<div id="'.$rf2['name'].'_tptformcontrol'.'" class="'.$invalid_class2.' urontrol height-'.$rf2['row_height'].' line-height-'.$rf2['control_line_height'].' padding-top-4 padding-bottom-4'.$rf2['classes'].'">';
					//$rcontrols2 .= '<div style="background-image: url('.$tpt_imagesurl.'/user-form-field-left.png);" class="padding-left-8 background-position-LC background-repeat-no-repeat">';
					//$rcontrols2 .= '<div style="background-image: url('.$tpt_imagesurl.'/user-form-field-right.png);" class="padding-right-8 background-position-RC background-repeat-no-repeat">';
					//$rcontrols2 .= '<div style="background-image: url('.$tpt_imagesurl.'/user-form-field-mid.png);" class="background-repeat-repeat-x">';
					$rcontrols2 .= self::createTextinput($vars, $rf2['name'], ((empty($rf2['value'])&&!empty($vars['template_data']['form_values'][$rf2['name']]))?$vars['template_data']['form_values'][$rf2['name']]:$rf2['value']), ' size="5" class="plain-input-field padding-top-3 padding-bottom-3 font-size-14" '.$rf2['html_attribs']);
					//$rcontrols2 .= '</div>';
					//$rcontrols2 .= '</div>';
					//$rcontrols2 .= '</div>';
					$rcontrols2 .= '</div>';
					$rafter2 .= '<div class="urontrol height-'.$rf2['row_height'].' line-height-'.$rf2['after_line_height'].' padding-top-4 padding-bottom-4 position-relative'.$rf2['classes'].'">';
					$rafter2 .= '<div class="position-relative" style="height: 100%; top: 50%;">';
					$rafter2 .= '<div class="position-relative" style="height: 100%; top: -50%;">';
					$rafter2 .= $rf2['after_content'];
					$rafter2 .= '</div>';
					$rafter2 .= '</div>';
					$rafter2 .= '</div>';
					break;
				case 'p' :
					$label2 = $rf2['label'];
					if(!empty($label2)) {
						$label2 .= (!empty($rf2['required'])?$required_html:'').':';
					}
					$invalid_class = '';
					$invalid_class2 = '';
					if(!empty($vars['template_data']['invalid_fields'][$rf2['name']])) {
						$invalid_class = 'amz_red tpt_invalid_field ';
						$invalid_class2 = ' tpt_invalid_field ';
					}
					$rlabels2 .= '<div id="'.$rf2['name'].'_tptformlabel" class="urontrol urlabel'.$label_width_class.' '.$invalid_class.'height-'.$rf2['row_height'].' line-height-'.$rf2['label_line_height'].' padding-top-4 padding-bottom-4'.$rf2['classes'].'">'.$label2.'</div>';
					$rcontrols2 .= '<div id="'.$rf2['name'].'_tptformcontrol'.'" class="'.$invalid_class2.' urontrol height-'.$rf2['row_height'].' line-height-'.$rf2['control_line_height'].' padding-top-4 padding-bottom-4'.$rf2['classes'].'">';
					$rcontrols2 .= '<div style="background-image: url('.$tpt_imagesurl.'/user-form-field-left.png);" class="padding-left-8 background-position-LC background-repeat-no-repeat">';
					$rcontrols2 .= '<div style="background-image: url('.$tpt_imagesurl.'/user-form-field-right.png);" class="padding-right-8 background-position-RC background-repeat-no-repeat">';
					$rcontrols2 .= '<div style="background-image: url('.$tpt_imagesurl.'/user-form-field-mid.png);" class="background-repeat-repeat-x">';
					$rcontrols2 .= self::createPasswordinput($vars, $rf2['name'], '', ' size="5" class="plain-input-field padding-top-3 padding-bottom-3 font-size-14" '.$rf2['html_attribs']);
					$rcontrols2 .= '</div>';
					$rcontrols2 .= '</div>';
					$rcontrols2 .= '</div>';
					$rcontrols2 .= '</div>';
					$rafter2 .= '<div class="urontrol height-'.$rf2['row_height'].' line-height-'.$rf2['after_line_height'].' padding-top-4 padding-bottom-4 position-relative'.$rf2['classes'].'">';
					$rafter2 .= '<div class="position-relative" style="height: 100%; top: 50%;">';
					$rafter2 .= '<div class="position-relative" style="height: 100%; top: -50%;">';
					$rafter2 .= $rf2['after_content'];
					$rafter2 .= '</div>';
					$rafter2 .= '</div>';
					$rafter2 .= '</div>';
					break;
				case 'r' :
					$label2 = $rf2['label'];
					if(!empty($label2)) {
						$label2 .= (!empty($rf2['required'])?$required_html:'').':';
					}
					$invalid_class = '';
					$invalid_class2 = '';
					if(!empty($vars['template_data']['invalid_fields'][$rf2['name']])) {
						$invalid_class = 'amz_red tpt_invalid_field ';
						$invalid_class2 = ' tpt_invalid_field ';
					}
					$rlabels2 .= '<div id="'.$rf2['name'].'_tptformlabel" class="urontrol urlabel'.$label_width_class.' '.$invalid_class.'height-'.$rf2['row_height'].' line-height-'.$rf2['label_line_height'].' padding-top-4 padding-bottom-4'.$rf2['classes'].'">'.$label2.':</div>';
					$rcontrols2 .= '<div id="'.$rf2['name'].'_tptformcontrol'.'" class="'.$invalid_class2.' urontrol height-'.$rf2['row_height'].' line-height-'.$rf2['control_line_height'].' padding-top-4 padding-bottom-4'.$rf2['classes'].'">'.self::createRadiobutton($vars, $rf2['name'], $rf2['value'], $vars['template_data']['form_values'][$rf2['name']], $rf2['html_attribs'], $rf2['oncheck']);
					$rcontrols2 .= '</div>';
					$rafter2 .= '<div class="urontrol height-'.$rf2['row_height'].' line-height-'.$rf2['after_line_height'].' padding-top-4 padding-bottom-4 position-relative'.$rf2['classes'].'">';
					$rafter2 .= '<div class="position-relative" style="height: 100%; top: 50%;">';
					$rafter2 .= '<div class="position-relative" style="height: 100%; top: -50%;">';
					$rafter2 .= $rf2['after_content'];
					$rafter2 .= '</div>';
					$rafter2 .= '</div>';
					$rafter2 .= '</div>';
					break;
				case 'rg' :
					$label2 = $rf2['label'];
					if(!empty($label2)) {
						$label2 .= (!empty($rf2['required'])?$required_html:'').':';
					}
					$invalid_class = '';
					$invalid_class2 = '';
					if(!empty($vars['template_data']['invalid_fields'][$rf2['name']])) {
						$invalid_class = 'amz_red tpt_invalid_field ';
						$invalid_class2 = ' tpt_invalid_field ';
					}
					$rgroup = explode(',', $rf2['value']);
					$rlabels2 .= '<div id="'.$rf2['name'].'_tptformlabel" class="urontrol urlabel'.$label_width_class.' '.$invalid_class.'height-'.$rf2['row_height'].' line-height-'.$rf2['label_line_height'].' padding-top-4 padding-bottom-4'.$rf2['classes'].'">'.$label2.'</div>';
					$rcontrols2 .= '<div id="'.$rf2['name'].'_tptformcontrol'.'" class="'.$invalid_class2.' urontrol height-'.$rf2['row_height'].' line-height-'.$rf2['control_line_height'].' padding-top-4 padding-bottom-4'.$rf2['classes'].'">';
					foreach($rgroup as $rg) {
						$rgcpn = explode(':', $rg);
						$checked_html = '';
						if(!empty($rgcpn[2])) {
							if(!isset($vars['template_data']['form_values'][$rf2['name']])) {
								$checked_html = ' checked="checked"';
							}
						}
						$rcontrols2 .= '<span>'.$rgcpn[1].'</span>';
						$rcontrols2 .= self::createRadiobutton($vars, $rf2['name'], $rgcpn[0], $vars['template_data']['form_values'][$rf2['name']], $rf2['html_attribs'].$checked_html, $rf2['oncheck']);
					}
					$rcontrols2 .= '</div>';
					$rafter2 .= '<div class="urontrol height-'.$rf2['row_height'].' line-height-'.$rf2['after_line_height'].' padding-top-4 padding-bottom-4 position-relative'.$rf2['classes'].'">';
					$rafter2 .= '<div class="position-relative" style="height: 100%; top: 50%;">';
					$rafter2 .= '<div class="position-relative" style="height: 100%; top: -50%;">';
					$rafter2 .= $rf2['after_content'];
					$rafter2 .= '</div>';
					$rafter2 .= '</div>';
					$rafter2 .= '</div>';
					break;
				case 'sl' :
					$label2 = $rf2['label'];
					if(!empty($label2)) {
						$label2 .= (!empty($rf2['required'])?$required_html:'').':';
					}
					$invalid_class = '';
					$invalid_class2 = '';
					if(!empty($vars['template_data']['invalid_fields'][$rf2['name']])) {
						$invalid_class = 'amz_red tpt_invalid_field ';
						$invalid_class2 = ' tpt_invalid_field ';
					}
//            $select = $vars['modules']['handler']->modules[$rf2['value']]->{$rf2['name'].'Select'}($vars);
					$select = getModule($vars,$rf2['value'])->{$rf2['name'].'Select'}($vars);
					$rlabels2 .= '<div id="'.$rf2['name'].'_tptformlabel" class="urontrol urlabel'.$label_width_class.' '.$invalid_class.'height-'.$rf2['row_height'].' line-height-'.$rf2['label_line_height'].' padding-top-4 padding-bottom-4'.$rf2['classes'].'">'.$label2.'</div>';
					$rcontrols2 .= '<div id="'.$rf2['name'].'_tptformcontrol'.'" class="'.$invalid_class2.' urontrol height-'.$rf2['row_height'].' line-height-'.$rf2['control_line_height'].' padding-top-4 padding-bottom-4'.$rf2['classes'].'">'.$select;
					$rcontrols2 .= '</div>';
					$rafter2 .= '<div class="urontrol height-'.$rf2['row_height'].' line-height-'.$rf2['after_line_height'].' padding-top-4 padding-bottom-4 position-relative'.$rf2['classes'].'">';
					$rafter2 .= '<div class="position-relative" style="height: 100%; top: 50%;">';
					$rafter2 .= '<div class="position-relative" style="height: 100%; top: -50%;">';
					$rafter2 .= $rf2['after_content'];
					$rafter2 .= '</div>';
					$rafter2 .= '</div>';
					$rafter2 .= '</div>';
					break;
				case 'stsel' :
					$country = $vars['template_data']['form_values']['country'];
					$state = $vars['template_data']['form_values']['state'];
					$shipping = false;
					if($rf2['name'] == 'shipping_state') {
						$country = $vars['template_data']['form_values']['shipping_country'];
						$state = $vars['template_data']['form_values']['shipping_state'];
						$shipping = true;
					}

					include(TPT_TEMPLATES_DIR.DIRECTORY_SEPARATOR.'states.tpt.php');

					$label2 = $rf2['label'];
					if(!empty($label2)) {
						$label2 .= (!empty($rf2['required'])?$required_html:'').':';
					}
					$invalid_class = '';
					$invalid_class2 = '';
					if(!empty($vars['template_data']['invalid_fields'][$rf2['name']])) {
						$invalid_class = 'amz_red tpt_invalid_field ';
						$invalid_class2 = ' tpt_invalid_field ';
					}

					$rlabels2 .= '<div id="'.$rf2['name'].'_tptformlabel" class="urontrol urlabel'.$label_width_class.' '.$invalid_class.'height-'.$rf2['row_height'].' line-height-'.$rf2['label_line_height'].' padding-top-4 padding-bottom-4'.$rf2['classes'].'">'.$label2.'</div>';
					$rcontrols2 .= '<div id="'.$rf2['name'].'_tptformcontrol'.'" class="'.$invalid_class2.' urontrol height-'.$rf2['row_height'].' line-height-'.$rf2['control_line_height'].' padding-top-4 padding-bottom-4'.$rf2['classes'].'">'.$states;
					$rcontrols2 .= '</div>';
					$rafter2 .= '<div class="urontrol height-'.$rf2['row_height'].' line-height-'.$rf2['after_line_height'].' padding-top-4 padding-bottom-4 position-relative'.$rf2['classes'].'">';
					$rafter2 .= '<div class="position-relative" style="height: 100%; top: 50%;">';
					$rafter2 .= '<div class="position-relative" style="height: 100%; top: -50%;">';
					$rafter2 .= $rf2['after_content'];
					$rafter2 .= '</div>';
					$rafter2 .= '</div>';
					$rafter2 .= '</div>';
					break;
				case 'c' :
				default :
					$label2 = $rf2['label'];
					if(!empty($label2)) {
						$label2 .= (!empty($rf2['required'])?$required_html:'').':';
					}
					$invalid_class = '';
					$invalid_class2 = '';
					if(!empty($vars['template_data']['invalid_fields'][$rf2['name']])) {
						$invalid_class = 'amz_red tpt_invalid_field ';
						$invalid_class2 = ' tpt_invalid_field ';
					}
					$rlabels2 .= '<div id="'.$rf2['name'].'_tptformlabel" class="urontrol urlabel'.$label_width_class.' '.$invalid_class.'height-'.$rf2['row_height'].' line-height-'.$rf2['label_line_height'].' padding-top-4 padding-bottom-4'.$rf2['classes'].'">'.$label2.'</div>';
					$rcontrols2 .= '<div id="'.$rf2['name'].'_tptformcontrol'.'" class="'.$invalid_class2.' urontrol height-'.$rf2['row_height'].' line-height-'.$rf2['control_line_height'].' padding-top-4 padding-bottom-4'.$rf2['classes'].'">'.self::createCheckbox($vars, $rf2['name'], $rf2['value'], $vars['template_data']['form_values'][$rf2['name']], $rf2['html_attribs'], $rf2['oncheck'], $rf2['onuncheck']);
					$rcontrols2 .= '</div>';
					$rafter2 .= '<div class="urontrol height-'.$rf2['row_height'].' line-height-'.$rf2['after_line_height'].' padding-top-4 padding-bottom-4 position-relative'.$rf2['classes'].'">';
					$rafter2 .= '<div class="position-relative" style="height: 100%; top: 50%;">';
					$rafter2 .= '<div class="position-relative" style="height: 100%; top: -50%;">';
					$rafter2 .= $rf2['after_content'];
					$rafter2 .= '</div>';
					$rafter2 .= '</div>';
					$rafter2 .= '</div>';
					break;
			}
			$rcontrols2 .= '</div>';
		}



		$section_html .= '<div class="float-left text-align-right">'.$rlabels2.'</div>';
		$section_html .= '<div class="float-left text-align-left padding-left-10 width-150">'.$rcontrols2.'</div>';
		$section_html .= '<div class="overflow-hidden text-align-left padding-left-10">'.$rafter2.'</div>';

		return $section_html;
	}
}




class Minify_HTML {
	/**
	 * @var boolean
	 */
	protected $_jsCleanComments = true;

	/**
	 * "Minify" an HTML page
	 *
	 * @param string $html
	 *
	 * @param array $options
	 *
	 * 'cssMinifier' : (optional) callback function to process content of STYLE
	 * elements.
	 *
	 * 'jsMinifier' : (optional) callback function to process content of SCRIPT
	 * elements. Note: the type attribute is ignored.
	 *
	 * 'xhtml' : (optional boolean) should content be treated as XHTML1.0? If
	 * unset, minify will sniff for an XHTML doctype.
	 *
	 * @return string
	 */
	public static function minify($html, $options = array()) {
		$min = new self($html, $options);
		return $min->process();
	}


	/**
	 * Create a minifier object
	 *
	 * @param string $html
	 *
	 * @param array $options
	 *
	 * 'cssMinifier' : (optional) callback function to process content of STYLE
	 * elements.
	 *
	 * 'jsMinifier' : (optional) callback function to process content of SCRIPT
	 * elements. Note: the type attribute is ignored.
	 *
	 * 'jsCleanComments' : (optional) whether to remove HTML comments beginning and end of script block
	 *
	 * 'xhtml' : (optional boolean) should content be treated as XHTML1.0? If
	 * unset, minify will sniff for an XHTML doctype.
	 */
	public function __construct($html, $options = array())
	{
		$this->_html = str_replace("\r\n", "\n", trim($html));
		if (isset($options['xhtml'])) {
			$this->_isXhtml = (bool)$options['xhtml'];
		}
		if (isset($options['cssMinifier'])) {
			$this->_cssMinifier = $options['cssMinifier'];
		}
		if (isset($options['jsMinifier'])) {
			$this->_jsMinifier = $options['jsMinifier'];
		}
		if (isset($options['jsCleanComments'])) {
			$this->_jsCleanComments = (bool)$options['jsCleanComments'];
		}
	}


	/**
	 * Minify the markeup given in the constructor
	 *
	 * @return string
	 */
	public function process()
	{
		if ($this->_isXhtml === null) {
			$this->_isXhtml = (false !== strpos($this->_html, '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML'));
		}

		$this->_replacementHash = 'MINIFYHTML' . md5($_SERVER['REQUEST_TIME']);
		$this->_placeholders = array();

		// replace SCRIPTs (and minify) with placeholders
		$this->_html = preg_replace_callback(
			'/(\\s*)<script(\\b[^>]*?>)([\\s\\S]*?)<\\/script>(\\s*)/i'
			,array($this, '_removeScriptCB')
			,$this->_html);

		// replace STYLEs (and minify) with placeholders
		$this->_html = preg_replace_callback(
			'/\\s*<style(\\b[^>]*>)([\\s\\S]*?)<\\/style>\\s*/i'
			,array($this, '_removeStyleCB')
			,$this->_html);

		// remove HTML comments (not containing IE conditional comments).
		$this->_html = preg_replace_callback(
			'/<!--([\\s\\S]*?)-->/'
			,array($this, '_commentCB')
			,$this->_html);

		// replace PREs with placeholders
		$this->_html = preg_replace_callback('/\\s*<pre(\\b[^>]*?>[\\s\\S]*?<\\/pre>)\\s*/i'
			,array($this, '_removePreCB')
			,$this->_html);

		// replace TEXTAREAs with placeholders
		$this->_html = preg_replace_callback(
			'/\\s*<textarea(\\b[^>]*?>[\\s\\S]*?<\\/textarea>)\\s*/i'
			,array($this, '_removeTextareaCB')
			,$this->_html);

		// trim each line.
		// @todo take into account attribute values that span multiple lines.
		$this->_html = preg_replace('/^\\s+|\\s+$/m', '', $this->_html);

		// remove ws around block/undisplayed elements
		$this->_html = preg_replace('/\\s+(<\\/?(?:area|base(?:font)?|blockquote|body'
			.'|caption|center|col(?:group)?|dd|dir|div|dl|dt|fieldset|form'
			.'|frame(?:set)?|h[1-6]|head|hr|html|legend|li|link|map|menu|meta'
			.'|ol|opt(?:group|ion)|p|param|t(?:able|body|head|d|h||r|foot|itle)'
			.'|ul)\\b[^>]*>)/i', '$1', $this->_html);

		// remove ws outside of all elements
		$this->_html = preg_replace(
			'/>(\\s(?:\\s*))?([^<]+)(\\s(?:\s*))?</'
			,'>$1$2$3<'
			,$this->_html);

		// use newlines before 1st attribute in open tags (to limit line lengths)
		$this->_html = preg_replace('/(<[a-z\\-]+)\\s+([^>]+>)/i', "$1\n$2", $this->_html);

		// fill placeholders
		$this->_html = str_replace(
			array_keys($this->_placeholders)
			,array_values($this->_placeholders)
			,$this->_html
		);
		// issue 229: multi-pass to catch scripts that didn't get replaced in textareas
		$this->_html = str_replace(
			array_keys($this->_placeholders)
			,array_values($this->_placeholders)
			,$this->_html
		);
		return $this->_html;
	}

	protected function _commentCB($m)
	{
		return (0 === strpos($m[1], '[') || false !== strpos($m[1], '<!['))
			? $m[0]
			: '';
	}

	protected function _reservePlace($content)
	{
		$placeholder = '%' . $this->_replacementHash . count($this->_placeholders) . '%';
		$this->_placeholders[$placeholder] = $content;
		return $placeholder;
	}

	protected $_isXhtml = null;
	protected $_replacementHash = null;
	protected $_placeholders = array();
	protected $_cssMinifier = null;
	protected $_jsMinifier = null;

	protected function _removePreCB($m)
	{
		return $this->_reservePlace("<pre{$m[1]}");
	}

	protected function _removeTextareaCB($m)
	{
		return $this->_reservePlace("<textarea{$m[1]}");
	}

	protected function _removeStyleCB($m)
	{
		$openStyle = "<style{$m[1]}";
		$css = $m[2];
		// remove HTML comments
		$css = preg_replace('/(?:^\\s*<!--|-->\\s*$)/', '', $css);

		// remove CDATA section markers
		$css = $this->_removeCdata($css);

		// minify
		$minifier = $this->_cssMinifier
			? $this->_cssMinifier
			: 'trim';
		$css = call_user_func($minifier, $css);

		return $this->_reservePlace($this->_needsCdata($css)
			? "{$openStyle}/*<![CDATA[*/{$css}/*]]>*/</style>"
			: "{$openStyle}{$css}</style>"
		);
	}

	protected function _removeScriptCB($m)
	{
		$openScript = "<script{$m[2]}";
		$js = $m[3];

		// whitespace surrounding? preserve at least one space
		$ws1 = ($m[1] === '') ? '' : ' ';
		$ws2 = ($m[4] === '') ? '' : ' ';

		// remove HTML comments (and ending "//" if present)
		if ($this->_jsCleanComments) {
			$js = preg_replace('/(?:^\\s*<!--\\s*|\\s*(?:\\/\\/)?\\s*-->\\s*$)/', '', $js);
		}

		// remove CDATA section markers
		$js = $this->_removeCdata($js);

		// minify
		$minifier = $this->_jsMinifier
			? $this->_jsMinifier
			: 'trim';
		$js = call_user_func($minifier, $js);

		return $this->_reservePlace($this->_needsCdata($js)
			? "{$ws1}{$openScript}/*<![CDATA[*/{$js}/*]]>*/</script>{$ws2}"
			: "{$ws1}{$openScript}{$js}</script>{$ws2}"
		);
	}

	protected function _removeCdata($str)
	{
		return (false !== strpos($str, '<![CDATA['))
			? str_replace(array('<![CDATA[', ']]>'), '', $str)
			: $str;
	}

	protected function _needsCdata($str)
	{
		return ($this->_isXhtml && preg_match('/(?:[<&]|\\-\\-|\\]\\]>)/', $str));
	}
}
