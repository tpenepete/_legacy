<?php

defined('TPT_INIT') or die('access denied');

//var_dump($tpt_vars['template_data']['form_values']);die();
//var_dump($tpt_vars['template_data']['invalid_fields']);die();

// create the registration fields html (uses tpt_html.php)
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
            if(!empty($tpt_vars['template_data']['invalid_fields'][$rf2['name']])) {
                $invalid_class = 'amz_red tpt_invalid_field ';
                $invalid_class2 = ' tpt_invalid_field ';
            }
            $rlabels2 .= '<div id="'.$rf2['name'].'_tptformlabel" class="urontrol urlabel'.$label_width_class.' '.$invalid_class.'height-'.$rf2['row_height'].' line-height-'.$rf2['label_line_height'].' padding-top-4 padding-bottom-4'.$rf2['classes'].'">'.$label2.'</div>';
            $rcontrols2 .= '<div id="'.$rf2['name'].'_tptformcontrol'.'" class="'.$invalid_class2.' urontrol height-'.$rf2['row_height'].' line-height-'.$rf2['control_line_height'].' padding-top-4 padding-bottom-4'.$rf2['classes'].'">';
            $rcontrols2 .= '<div style="background-image: url('.$tpt_imagesurl.'/user-form-field-left.png);" class="padding-left-8 background-position-LC background-repeat-no-repeat">';
            $rcontrols2 .= '<div style="background-image: url('.$tpt_imagesurl.'/user-form-field-right.png);" class="padding-right-8 background-position-RC background-repeat-no-repeat">';
            $rcontrols2 .= '<div style="background-image: url('.$tpt_imagesurl.'/user-form-field-mid.png);" class="background-repeat-repeat-x">';
            $rcontrols2 .= tpt_html::createTextinput($tpt_vars, $rf2['name'], ((empty($rf2['value'])&&!empty($tpt_vars['template_data']['form_values'][$rf2['name']]))?$tpt_vars['template_data']['form_values'][$rf2['name']]:$rf2['value']), ' size="5" class="plain-input-field padding-top-3 padding-bottom-3 font-size-14" '.$rf2['html_attribs']);
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
            if(!empty($tpt_vars['template_data']['invalid_fields'][$rf2['name']])) {
                $invalid_class = 'amz_red tpt_invalid_field ';
                $invalid_class2 = ' tpt_invalid_field ';
            }
            $rlabels2 .= '<div id="'.$rf2['name'].'_tptformlabel" class="urontrol urlabel'.$label_width_class.' '.$invalid_class.'height-'.$rf2['row_height'].' line-height-'.$rf2['label_line_height'].' padding-top-4 padding-bottom-4'.$rf2['classes'].'">'.$label2.'</div>';
            $rcontrols2 .= '<div id="'.$rf2['name'].'_tptformcontrol'.'" class="'.$invalid_class2.' urontrol height-'.$rf2['row_height'].' line-height-'.$rf2['control_line_height'].' padding-top-4 padding-bottom-4'.$rf2['classes'].'">';
            $rcontrols2 .= '<div style="background-image: url('.$tpt_imagesurl.'/user-form-field-left.png);" class="padding-left-8 background-position-LC background-repeat-no-repeat">';
            $rcontrols2 .= '<div style="background-image: url('.$tpt_imagesurl.'/user-form-field-right.png);" class="padding-right-8 background-position-RC background-repeat-no-repeat">';
            $rcontrols2 .= '<div style="background-image: url('.$tpt_imagesurl.'/user-form-field-mid.png);" class="background-repeat-repeat-x">';
            $rcontrols2 .= tpt_html::createPasswordinput($tpt_vars, $rf2['name'], '', ' size="5" class="plain-input-field padding-top-3 padding-bottom-3 font-size-14" '.$rf2['html_attribs']);
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
            if(!empty($tpt_vars['template_data']['invalid_fields'][$rf2['name']])) {
                $invalid_class = 'amz_red tpt_invalid_field ';
                $invalid_class2 = ' tpt_invalid_field ';
            }
            $rlabels2 .= '<div id="'.$rf2['name'].'_tptformlabel" class="urontrol urlabel'.$label_width_class.' '.$invalid_class.'height-'.$rf2['row_height'].' line-height-'.$rf2['label_line_height'].' padding-top-4 padding-bottom-4'.$rf2['classes'].'">'.$label2.':</div>';
            $rcontrols2 .= '<div id="'.$rf2['name'].'_tptformcontrol'.'" class="'.$invalid_class2.' urontrol height-'.$rf2['row_height'].' line-height-'.$rf2['control_line_height'].' padding-top-4 padding-bottom-4'.$rf2['classes'].'">'.tpt_html::createRadiobutton($tpt_vars, $rf2['name'], $rf2['value'], $tpt_vars['template_data']['form_values'][$rf2['name']], $rf2['html_attribs'], $rf2['oncheck']);
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
            if(!empty($tpt_vars['template_data']['invalid_fields'][$rf2['name']])) {
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
                    if(!isset($tpt_vars['template_data']['form_values'][$rf2['name']])) {
                        $checked_html = ' checked="checked"';
                    }
                }
                $rcontrols2 .= '<span>'.$rgcpn[1].'</span>';
                $rcontrols2 .= tpt_html::createRadiobutton($tpt_vars, $rf2['name'], $rgcpn[0], $tpt_vars['template_data']['form_values'][$rf2['name']], $rf2['html_attribs'].$checked_html, $rf2['oncheck']);
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
            if(!empty($tpt_vars['template_data']['invalid_fields'][$rf2['name']])) {
                $invalid_class = 'amz_red tpt_invalid_field ';
                $invalid_class2 = ' tpt_invalid_field ';
            }
//            $select = $tpt_vars['modules']['handler']->modules[$rf2['value']]->{$rf2['name'].'Select'}($tpt_vars);
            $select = getModule($tpt_vars,$rf2['value'])->{$rf2['name'].'Select'}($tpt_vars);
            
            
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
            $country = $tpt_vars['template_data']['form_values']['country'];
            $state = $tpt_vars['template_data']['form_values']['state'];
            $shipping = false;
            if($rf2['name'] == 'shipping_state') {
                $country = $tpt_vars['template_data']['form_values']['shipping_country'];
                $state = $tpt_vars['template_data']['form_values']['shipping_state'];
                $shipping = true;
            }
            
            include(TPT_TEMPLATES_DIR.DIRECTORY_SEPARATOR.'states.tpt.php');
            
            $label2 = $rf2['label'];
            if(!empty($label2)) {
                $label2 .= (!empty($rf2['required'])?$required_html:'').':';
            }
            $invalid_class = '';
            $invalid_class2 = '';
            if(!empty($tpt_vars['template_data']['invalid_fields'][$rf2['name']])) {
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
            if(!empty($tpt_vars['template_data']['invalid_fields'][$rf2['name']])) {
                $invalid_class = 'amz_red tpt_invalid_field ';
                $invalid_class2 = ' tpt_invalid_field ';
            }
            $rlabels2 .= '<div id="'.$rf2['name'].'_tptformlabel" class="urontrol urlabel'.$label_width_class.' '.$invalid_class.'height-'.$rf2['row_height'].' line-height-'.$rf2['label_line_height'].' padding-top-4 padding-bottom-4'.$rf2['classes'].'">'.$label2.'</div>';
            $rcontrols2 .= '<div id="'.$rf2['name'].'_tptformcontrol'.'" class="'.$invalid_class2.' urontrol height-'.$rf2['row_height'].' line-height-'.$rf2['control_line_height'].' padding-top-4 padding-bottom-4'.$rf2['classes'].'">'.tpt_html::createCheckbox($tpt_vars, $rf2['name'], $rf2['value'], $tpt_vars['template_data']['form_values'][$rf2['name']], $rf2['html_attribs'], $rf2['oncheck'], $rf2['onuncheck']);
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

