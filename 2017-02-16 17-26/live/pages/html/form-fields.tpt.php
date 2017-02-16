<?php

defined('TPT_INIT') or die('access denied');

//var_dump($tpt_vars['template_data']['form_values']);die();
//var_dump($tpt_vars['template_data']['invalid_fields']);die();

// create the registration fields html (uses tpt_html.php)
$required_html = '<span class="amz_red">*</span>';

$label_width_class = ' width-119';

$rlabels = '';
$rcontrols = '';
$rafter = '';
$sections = array();
$section = '<div class="tpt_form_section">';
    $section .= '<div>';
        $section .= '<div class="tpt_form_section_title"></div>';
        $section .= '<div class="tpt_form_section_body">';
foreach($fields_data as $rf) {
    switch(strtolower($rf['control'])) {
        case 'e' :
            break;
        case 's' :
            $label = $rf['label'];
            if(!empty($label)) {
                $label .= (!empty($rf['required'])?$required_html:'').':';
            }
            $rlabels .= '<div id="'.$rf['name'].'_tptformlabel" class="urontrol urlabel'.$label_width_class.' height-'.$rf['row_height'].' line-height-'.$rf['label_line_height'].' padding-top-4 padding-bottom-4 padding-top-4 padding-bottom-4'.$rf['classes'].'">'.$label.'</div>';
            if(preg_match('#^\{(.*)\}$#', $rf['value'], $mtch)) {
                $ccmp = explode(':', $mtch[1]);
                
                include(TPT_TEMPLATES_DIR.DIRECTORY_SEPARATOR.$ccmp[1]);
                
                $rcontrols .= '<div id="'.$rf['name'].'_tptformcontrol'.'" class="urontrol height-'.$rf['row_height'].' line-height-'.$rf['control_line_height'].' padding-top-4 padding-bottom-4'.$rf['classes'].'">'.${$ccmp[0]};
                $rcontrols .= '</div>';
            } else {
                $rcontrols .= '<div id="'.$rf['name'].'_tptformcontrol'.'" class="urontrol height-'.$rf['row_height'].' line-height-'.$rf['control_line_height'].' padding-top-4 padding-bottom-4'.$rf['classes'].'">'.$rf['value'];
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
                            $section .= '<div class="float-left text-align-left padding-left-10 width-234" style="width: 50%;">'.$rcontrols.'</div>';
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
                        $section .= '<span class="tpt_form_section_title display-block"'.$sectid.'>'.$section_title.'</span>';
                        if(!empty($fbl)) {
                            $rlabels_before .= implode("\n", $fbl);
                            $rcontrols_before .= implode("\n", $fbc);
                            $rafter_before .= implode("\n", $fba);
                            $section_before_content = '';
                            $section_before_content .= '<div class="float-left text-align-right">'.$rlabels_before.'</div>';
                            $section_before_content .= '<div class="float-left text-align-left padding-left-10 width-234" style="width: 50%;">'.$rcontrols_before.'</div>';
                            $section_before_content .= '<div class="overflow-hidden text-align-left padding-left-10">'.$rafter_before.'</div>';
                            $fbl = array();
                            $fbc = array();
                            $fba = array();
                            $section .= '<span class="tpt_form_section_before display-block">'.$section_before_content.'</span>';
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
                            $section .= '<div class="float-left text-align-left padding-left-10 width-234" style="width: 50%;">'.$rcontrols.'</div>';
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
            if(!empty($tpt_vars['template_data']['invalid_fields'][$rf['name']])) {
                $invalid_class = 'amz_red tpt_invalid_field ';
                $invalid_class2 = ' tpt_invalid_field ';
            }
            $rlabels .= '<div id="'.$rf['name'].'_tptformlabel" class="urontrol urlabel'.$label_width_class.' '.$invalid_class.'height-'.$rf['row_height'].' line-height-'.$rf['label_line_height'].' padding-top-4 padding-bottom-4'.$rf['classes'].'">'.$label.'</div>';
            $rcontrols .= '<div id="'.$rf['name'].'_tptformcontrol'.'" class="'.$invalid_class2.' urontrol height-'.$rf['row_height'].' line-height-'.$rf['control_line_height'].' padding-top-4 padding-bottom-4'.$rf['classes'].'">';
            //$rcontrols .= '<div style="background-image: url('.$tpt_imagesurl.'/user-form-field-left.png);" class="padding-left-8 background-position-LC background-repeat-no-repeat">';
            //$rcontrols .= '<div style="background-image: url('.$tpt_imagesurl.'/user-form-field-right.png);" class="padding-right-8 background-position-RC background-repeat-no-repeat">';
            //$rcontrols .= '<div style="background-image: url('.$tpt_imagesurl.'/user-form-field-mid.png);" class="background-repeat-repeat-x background-position-CC">';
            $rcontrols .= tpt_html::createTextinput($tpt_vars, $rf['name'], isset($tpt_vars['template_data']['form_values'][$rf['name']])?$tpt_vars['template_data']['form_values'][$rf['name']]:'', ' size="5" class="plain-input-field padding-left-2 padding-right-2 padding-top-2 padding-bottom-2 font-size-14" '.$rf['html_attribs']);
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
            if(!empty($tpt_vars['template_data']['invalid_fields'][$rf['name']])) {
                $invalid_class = 'amz_red tpt_invalid_field ';
                $invalid_class2 = ' tpt_invalid_field ';
            }
            $rlabels .= '<div id="'.$rf['name'].'_tptformlabel" class="urontrol urlabel'.$label_width_class.' '.$invalid_class.'height-'.$rf['row_height'].' line-height-'.$rf['label_line_height'].' padding-top-4 padding-bottom-4'.$rf['classes'].'">'.$label.'</div>';
            $rcontrols .= '<div id="'.$rf['name'].'_tptformcontrol'.'" class="'.$invalid_class2.' urontrol height-'.$rf['row_height'].' line-height-'.$rf['control_line_height'].' padding-top-4 padding-bottom-4'.$rf['classes'].'">';
            //$rcontrols .= '<div style="background-image: url('.$tpt_imagesurl.'/user-form-field-left.png);" class="padding-left-8 background-position-LC background-repeat-no-repeat">';
            //$rcontrols .= '<div style="background-image: url('.$tpt_imagesurl.'/user-form-field-right.png);" class="padding-right-8 background-position-RC background-repeat-no-repeat">';
            //$rcontrols .= '<div style="background-image: url('.$tpt_imagesurl.'/user-form-field-mid.png);" class="background-repeat-repeat-x background-position-CC">';
            $rcontrols .= tpt_html::createPasswordinput($tpt_vars, $rf['name'], '', ' size="5" class="plain-input-field padding-left-2 padding-right-2 padding-top-2 padding-bottom-2 font-size-14" '.$rf['html_attribs']);
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
            if(!empty($tpt_vars['template_data']['invalid_fields'][$rf['name']])) {
                $invalid_class = 'amz_red tpt_invalid_field ';
                $invalid_class2 = ' tpt_invalid_field ';
            }
            $rlabels .= '<div id="'.$rf['name'].'_tptformlabel" class="urontrol urlabel'.$label_width_class.' '.$invalid_class.'height-'.$rf['row_height'].' line-height-'.$rf['label_line_height'].' padding-top-4 padding-bottom-4'.$rf['classes'].'">'.$label.'</div>';
            $rcontrols .= '<div id="'.$rf['name'].'_tptformcontrol'.'" class="'.$invalid_class2.' urontrol height-'.$rf['row_height'].' line-height-'.$rf['control_line_height'].' padding-top-4 padding-bottom-4'.$rf['classes'].'">'.tpt_html::createRadiobutton($tpt_vars, $rf['name'], $rf['value'], $tpt_vars['template_data']['form_values'][$rf['name']], $rf['html_attribs'], $rf['oncheck']);
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
            if(!empty($tpt_vars['template_data']['invalid_fields'][$rf['name']])) {
                $invalid_class = 'amz_red tpt_invalid_field ';
                $invalid_class2 = ' tpt_invalid_field ';
            }
            $rgroup = explode(',', $rf['value']);
            $rlabels .= '<div id="'.$rf['name'].'_tptformlabel" class="urontrol urlabel'.$label_width_class.' '.$invalid_class.'height-'.$rf['row_height'].' line-height-'.$rf['label_line_height'].' padding-top-4 padding-bottom-4'.$rf['classes'].'">'.$label.'</div>';
            $rcontrols .= '<div id="'.$rf['name'].'_tptformcontrol'.'" class="'.$invalid_class2.' urontrol height-'.$rf['row_height'].' line-height-'.$rf['control_line_height'].' padding-top-4 padding-bottom-4'.$rf['classes'].'">';
            foreach($rgroup as $rg) {
                $rgcpn = explode(':', $rg);
                $checked_html = '';
                if(!empty($rgcpn[2])) {
                    if(!isset($tpt_vars['template_data']['form_values'][$rf['name']])) {
                        $checked_html = ' checked="checked"';
                    }
                }
                $rcontrols .= '<span>'.$rgcpn[1].'</span>';
                $rcontrols .= tpt_html::createRadiobutton($tpt_vars, $rf['name'], $rgcpn[0], $tpt_vars['template_data']['form_values'][$rf['name']], $rf['html_attribs'].$checked_html, $rf['oncheck']);
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
            if(!empty($tpt_vars['template_data']['invalid_fields'][$rf['name']])) {
                $invalid_class = 'amz_red tpt_invalid_field ';
                $invalid_class2 = ' tpt_invalid_field ';
            }
//            $select = $tpt_vars['modules']['handler']->modules[$rf['value']]->{$rf['name'].'Select'}($tpt_vars);
            $select = getModule($tpt_vars,$rf['value'])->{$rf['name'].'Select'}($tpt_vars);
            $rlabels .= '<div id="'.$rf['name'].'_tptformlabel" class="urontrol urlabel'.$label_width_class.' '.$invalid_class.'height-'.$rf['row_height'].' line-height-'.$rf['label_line_height'].' padding-top-4 padding-bottom-4'.$rf['classes'].'">'.$label.'</div>';
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
            $country = $tpt_vars['template_data']['form_values']['country'];
            $state = $tpt_vars['template_data']['form_values']['state'];
            $shipping = false;
            if($rf['name'] == 'shipping_state') {
                $country = $tpt_vars['template_data']['form_values']['shipping_country'];
                $state = $tpt_vars['template_data']['form_values']['shipping_state'];
                $shipping = true;
            }
            
            include(TPT_TEMPLATES_DIR.DIRECTORY_SEPARATOR.'states.tpt.php');
            
            $label = $rf['label'];
            if(!empty($label)) {
                $label .= (!empty($rf['required'])?$required_html:'').':';
            }
            $invalid_class = '';
            $invalid_class2 = '';
            if(!empty($tpt_vars['template_data']['invalid_fields'][$rf['name']])) {
                $invalid_class = 'amz_red tpt_invalid_field ';
                $invalid_class2 = ' tpt_invalid_field ';
            }
            
            $rlabels .= '<div id="'.$rf['name'].'_tptformlabel" class="urontrol urlabel'.$label_width_class.' '.$invalid_class.'height-'.$rf['row_height'].' line-height-'.$rf['label_line_height'].' padding-top-4 padding-bottom-4'.$rf['classes'].'">'.$label.'</div>';
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
            if(!empty($tpt_vars['template_data']['invalid_fields'][$rf['name']])) {
                $invalid_class = 'amz_red tpt_invalid_field ';
                $invalid_class2 = ' tpt_invalid_field ';
            }
            $rlabels .= '<div id="'.$rf['name'].'_tptformlabel" class="urontrol urlabel'.$label_width_class.' '.$invalid_class.'height-'.$rf['row_height'].' line-height-'.$rf['label_line_height'].' padding-top-4 padding-bottom-4'.$rf['classes'].'">'.$label.'</div>';
            $rcontrols .= '<div id="'.$rf['name'].'_tptformcontrol'.'" class="'.$invalid_class2.' urontrol height-'.$rf['row_height'].' line-height-'.$rf['control_line_height'].' padding-top-4 padding-bottom-4'.$rf['classes'].'">'.tpt_html::createCheckbox($tpt_vars, $rf['name'], $rf['value'], isset($tpt_vars['template_data']['form_values'][$rf['name']])?$tpt_vars['template_data']['form_values'][$rf['name']]:'', $rf['html_attribs'], $rf['oncheck'], $rf['onuncheck']);
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
        $section .= '<div class="float-left text-align-left padding-left-10 width-234" style="width: 50%;">'.$rcontrols.'</div>';
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
    $wrapid = $fwrapid;
}
$form_fields = <<< EOT
    <div id="$wrapid" class="clearBoth">
        $rfields
    </div>
EOT;



