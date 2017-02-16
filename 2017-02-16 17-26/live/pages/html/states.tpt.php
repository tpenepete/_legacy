<?php

defined('TPT_INIT') or die('access denied');

global $tpt_vars;
//$country = 1;

$controlName = 'state';
if($shipping)
    $controlName = 'shipping_state';

/*
$states = <<< EOT
<div class="urontrol height-22 line-height-22 padding-top-4 padding-bottom-4">
<div class="padding-left-8 background-position-LC background-repeat-no-repeat" style="background-image: url($tpt_imagesurl/user-form-field-left.png);">
<div class="padding-right-8 background-position-RC background-repeat-no-repeat" style="background-image: url($tpt_imagesurl/user-form-field-right.png);">
<div class="background-repeat-repeat-x" style="background-image: url($tpt_imagesurl/user-form-field-mid.png);">
<input type="text" disabled="disabled" style="width: 100%;" autocomplete="off" class="plain-input-field padding-top-3 padding-bottom-3 font-size-14" size="5" value="Select country first" name="$controlName">
</div>
</div>
</div>
</div>
EOT;
*/
$states = <<< EOT
<input type="text" disabled="disabled" style="width: 100%; border: 1px solid #CCCCCC; border-radius: 8px; background: #E5E5E5 none;" autocomplete="off" class="plain-input-field padding-left-2 padding-right-2 padding-top-2 padding-bottom-2 font-size-14" size="5" value="Select country first" name="$controlName">
EOT;

if(!empty($country)) {
    $items = $tpt_vars['db']['handler']->getData($tpt_vars, 'tpt_module_countries', '*', '', 'id', false);
    if(!empty($items[$country])) {
        if(preg_match('#^\{.*\}$#', $items[$country]['states_source'], $mtch)) {
            $text_state = (isset($tpt_vars['template_data']['form_values']['state'])?$tpt_vars['template_data']['form_values']['state']:'');

/*
$states = <<< EOT
<div class="urontrol height-22 line-height-22 padding-top-4 padding-bottom-4">
<div class="padding-left-8 background-position-LC background-repeat-no-repeat" style="background-image: url($tpt_imagesurl/user-form-field-left.png);">
<div class="padding-right-8 background-position-RC background-repeat-no-repeat" style="background-image: url($tpt_imagesurl/user-form-field-right.png);">
<div class="background-repeat-repeat-x" style="background-image: url($tpt_imagesurl/user-form-field-mid.png);">
<input type="text" style="width: 100%;" autocomplete="off" class="plain-input-field padding-top-4 padding-bottom-4 font-size-14" size="5" value="$text_state" name="$controlName">
</div>
</div>
</div>
</div>
EOT;
*/

			$states = <<< EOT
<input type="text" style="width: 100%; border: 1px solid #CCCCCC; border-radius: 8px; background: #E5E5E5 none;" autocomplete="off" class="plain-input-field padding-left-2 padding-right-2 padding-top-2 padding-bottom-2 font-size-14" size="5" value="$text_state" name="$controlName">
EOT;
        } else {
            $stvals = $tpt_vars['db']['handler']->getData($tpt_vars, $items[$country]['states_source'], 'id,state', '');
            
            $values = array();
            //var_dump($stvals);die();
            $title = $items[$country]['select_title'];
            
            $sState = 0;
            $i=1;
            foreach($stvals as $key=>$item) {
                if(!empty($state)) {
                    if($state == $item['id']) {
                        $sState = $key+1;
                    }
                }
                if($i==1) {
                    $values[] = array(0, $title);
                    $i=0;
                }
                
                $values[] = array($item['id'], $item['state']);
            }
            
            $states = tpt_html::createSelect($tpt_vars, $controlName, $values, $sState, ' onclick="removeClass(document.getElementById(this.name+\'_tptformlabel\'), \'amz_red\')" onfocus="removeClass(document.getElementById(this.name+\'_tptformlabel\'), \'amz_red\');" style="width: 100%; background-color: #DDD;" title="'.$title.'"');
        }
    }
}
