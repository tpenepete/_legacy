<?php

defined('TPT_INIT') or die('access denied');

class tpt_module_Countries extends tpt_Module {
    
    function __construct(&$vars, $name, $moduleClassFile, $moduleClass, $moduleTable) {
        $fields = array(
                //db field name|field type|length|options|storage options|control|ctrAttr|default|label|index by|split keys|template
            new tpt_ModuleField('id',    'n', null, 'ai', '',         'sp', '', '', '',           true, false,  'LC'),
            //'<div class="tpt_admin_module_section float-left" style="border: 2px solid #FFF;">',
            //'</div>',
            new tpt_ModuleField('name',   's', 64,   '',   '', 'tf', ' style="width: 130px;"', '', 'Country Name', false, false, 'LC'),
            new tpt_ModuleField('value',   's', 16,   '',   '', 'tf', ' style="width: 100px;"', '', 'Low Level Value', false, false, 'LC'),
            new tpt_ModuleField('states_source',   's', 64,   '',   '', 'tf', ' style="width: 130px;"', '', 'States Source', false, false, 'LC'),
            new tpt_ModuleField('select_title',   's', 64,   '',   '', 'tf', ' style="width: 130px;"', '', 'Dropdown Title', false, false, 'LC'),
            new tpt_ModuleField('order',  'f', '',    '',   '', 'tf', ' style="width: 70px;"', '', 'Order',       false, false, 'LC'),
            new tpt_ModuleField('enabled',  'ti', 1,    '',   '', 'tf', ' style="width: 70px;"', '', 'enabled',       false, false, 'LC'),
            //'<div class="float-left padding-top-20 padding-bottom-20 padding-left-10 padding-right-10" style="background-color: #FFF;"><div class="display-inline-block height-10 width-80" style="background-color: #`HEX`; border: 1px solid #000;"></div></div>',
            //'<div class="float-left" style="background-color: #`HEX`; border: 1px solid #000;"><img src="'.$vars['config']['resourceurl'].'/images/Band-Transperent-Preview.png" class="width-80" /></div>',
            //'<div class="float-left" style="background-color: #`HEX`; border: 1px solid #000;"><img src="'.$vars['config']['resourceurl'].'/images/Transparent-Swirl-Band-Preview.png" class="width-80" /></div>'
        );
        parent::__construct($vars, $name, $moduleClassFile, $moduleClass, $moduleTable, $fields, 'id');
    }
    
    function countrySelect(&$vars) {
        $items = $vars['db']['handler']->getData($vars, $this->moduleTable, 'id,name,states_source', ' `enabled`=1');
        
        $html = '';
        $values = array();
        
        $title = 'Select Your Country';
        
        $sCountry = 0;
        $i=1;
        foreach($items as $key=>$item) {
            if(!empty($vars['template_data']['form_values']['country'])) {
                if($vars['template_data']['form_values']['country'] == $item['id']) {
                    $sCountry = $key+1;
                    //array_unshift($values, array($item['id'], $item['name']));
                }
            }
            if($i==1) {
                $values[] = array(0, $title);
                $i=0;
            }
            
            $values[] = array($item['id'], $item['name']);
        }
        //var_dump($values);die();
        
        $html = tpt_html::createSelect($vars, 'country', $values, $sCountry, ' onclick="removeClass(document.getElementById(this.name+\'_tptformlabel\'), \'amz_red\')" onfocus="removeClass(document.getElementById(this.name+\'_tptformlabel\'), \'amz_red\');" onchange="goGetSome(\'registration.get_states\', this.form);" onkeyup="goGetSome(\'registration.get_states\', this.form);" style="width: 100%; background-color: #DDD;" title="'.$title.'"')
        ;//.'<input type="hidden" name="country" value="1" />';
        
        return $html;
    }
    
    function shipping_countrySelect(&$vars) {
        $items = $vars['db']['handler']->getData($vars, $this->moduleTable, 'id,name,states_source', ' `enabled`=1');
        
        $html = '';
        $values = array();
        
        $title = 'Select Your Country';
        
        $sCountry = 0;
        $i=1;
        foreach($items as $key=>$item) {
            if(!empty($vars['template_data']['form_values']['shipping_country'])) {
                if($vars['template_data']['form_values']['shipping_country'] == $item['id']) {
                    $sCountry = $key+1;
                    //array_unshift($values, array($item['id'], $item['name']));
                }
            }
            if($i==1) {
                $values[] = array(0, $title);
                $i=0;
            }
            
            $values[] = array($item['id'], $item['name']);
        }
        //var_dump($values);die();
        
        $html = tpt_html::createSelect($vars, 'shipping_country', $values, $sCountry, ' onclick="removeClass(document.getElementById(this.name+\'_tptformlabel\'), \'amz_red\')" onfocus="removeClass(document.getElementById(this.name+\'_tptformlabel\'), \'amz_red\');" onchange="goGetSome(\'registration.get_states2\', this.form);" onkeyup="goGetSome(\'registration.get_states2\', this.form);" style="width: 100%; background-color: #DDD;" title="'.$title.'"')
        ;//.'<input type="hidden" name="shipping_country" value="1" />';
        
        return $html;
    }
    
    function StyledSelect(&$vars) {
        $items = $vars['db']['handler']->getData($vars, $this->moduleTable, 'id,name', ' `enabled`=1');
        
        $html = '';
        $values = array();
        
        $title = 'Select Your Country';
        
        $sCountry = 0;
        $i=1;
        foreach($items as $key=>$item) {
            if(!empty($vars['template_data']['form_values']['country'])) {
                if($vars['template_data']['form_values']['country'] == $item['id']) {
                    $sCountry = $key+1;
                    array_unshift($values, array($item['id'], '<div class="font-size-14 height-22 padding-left-2 padding-right-2 line-height-22 white-space-nowrap" style=""'./* style="border: 1px solid #555;background-color: #FFF;"*/'>'.$item['name'].'</div>', $title));
                }
            } else {
                if($i==1) {
                    $values[] = array(0, '<div class="font-size-14 height-22 padding-left-2 padding-right-2 line-height-22 white-space-nowrap" style=""'./* style="border: 1px solid #555;background-color: #FFF;"*/'>'.$title.'</div>', $title);
                    $i=0;
                }
            }
            
            $values[] = array($item['id'], '<div class="height-22 padding-left-2 padding-right-2 line-height-22 white-space-nowrap" style="">'.$item['name'].'</div>', $item['name']);
        }
        
        $valuesDelimiter = "\n";
        $ssDescriptor=array('images'=>array('user-form-field-left.png', 'user-form-field-selectbutton.png', 'user-form-field-mid.png'), 'height'=>'22', 'paddings'=>array('8', '24'), 'options_bg_css'=>'background-color: #CCC;');
        
        
        $html = tpt_html::createStyledSelect($vars, 'country', $values, $valuesDelimiter, ' display-block', ' width:210px;', ' width:202px;', ' padding-top-0', $sCountry, 'change_country', 'tpt_country', ' title="'.$title.'"', '', $ssDescriptor);
        
        return $html;
    }


    function getStateName(&$vars, $country_id = 0, $stateval = '') {
        $countries = $this->moduleData['id'];
        $state = '';
        if (isset($countries[$country_id]['states_source'])) {
            if (preg_match('#^\{.*\}$#', $countries[$country_id]['states_source'], $mtch)) {
                $state = $stateval;
            } else {
                $stvals = $vars['db']['handler']->getData($vars, $countries[$country_id]['states_source'], '*', '', 'id', false);
                $state = $stvals[$stateval]['state'];
                if ($country_id == 1) {
                    $state = $stvals[$stateval]['state_code'];
                }
            }
        }
        return $state;
    }


    function getCountryStateTax(&$vars, $country_id, $stateval) {
        $countries = $this->moduleData['id'];

        $tax = array('1');

        $state   = '';
        //if(!empty($country_id) && !empty($stateval)) {
        if (preg_match('#^\{.*\}$#', $countries[$country_id]['states_source'], $mtch)) {
            $state = $stateval;
        } else {
            $stvals  = $vars['db']['handler']->getData($vars, $countries[$country_id]['states_source'], '*', '', 'id', false);
            $state = $stvals[$stateval];
            if(!empty($state['tax_class'])) {
                $tax = explode(',', $state['tax_class']);
            }
        }

        return $tax;
    }


    
    function isTX(&$vars, $country_id=0, $stateval='') {
        if(($country_id == 1) && ($stateval == 44)) {
            return 1;
        } else {
            return 0;
        }
    }

}

?>