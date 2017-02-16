<?php

defined('TPT_INIT') or die('access denied');

$builders_module = getModule($tpt_vars, 'Builder');
$url_builders = $builders_module->moduleData['url_id'];
$id_builders = $builders_module->moduleData['id'];

if(strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
    $result = '';
    
    $colors_module = getModule($tpt_vars, "BandColor");
    $builders_module = getModule($tpt_vars, "Builder");
    
    $builder = array();
    $builder_id = 0;
    $url_id = 0;
    $builder_title_new = '';
    if (empty($_POST['short_builder'])) {
        $url_id = isset($tpt_vars['environment']['page_rule']['id']) ? $tpt_vars['environment']['page_rule']['id'] : 0;
        $builder = isset($url_builders[$url_id]) ? $url_builders[$url_id] : array();
    } else {
        $builder_id = intval($_POST['short_builder'], 10);
        $builder = isset($id_builders[$builder_id]) ? $id_builders[$builder_id] : array();
        $url_id = isset($builder['url_id']) ? $builder['url_id'] : 0;
    }
    

    if(!empty($builder)) {
        $builder_id = $builder['id'];
        
        //var_dump()
        $builder_title_arr = $tpt_vars['db']['handler']->getData($tpt_vars, 'tpt_module_urlrules', 'html_title', '`id`='.$url_id);
        $builder_title_arr = reset($builder_title_arr);
        $builder_title_new = $builder_title_arr['html_title'];
        
        
    } else {
        $builder['type'] = 0;
        $builder['style'] = 0;
        $builder_title_new = $builder['label'] = 'Easy Silicone Wristband Builder';
        $builder['inhouse'] = 0;
        
        //$builder_id = 0;
        //$builder_title_new = '';
    }
    
    $inhouse = $builder['inhouse'];
    
    
    $selected_type = '';
    $initType = $builders_module->initBuilderType($tpt_vars, $builder);
    extract($initType, EXTR_OVERWRITE);
        
    $pgStyle = (!empty($_GET['band_style'])?intval($_GET['band_style'], 10):DEFAULT_STYLE);
    
    
    $pgBandColor = '-1:'.DEFAULT_BAND_COLOR;
    
    
    if(!empty($_GET['band_color'])) {
        $pgBandColor = $_GET['band_color'];
    }
    $pgMessageColor = '-1:'.DEFAULT_MESSAGE_COLOR;
    //$pgMessageColor = '0:534';
    if(!empty($_GET['message_color'])) {
        $pgMessageColor = $_GET['message_color'];
    }
    
    
    
    //tpt_dump($pgType, true);
    $pgStyle = !empty($builder['style'])?$builder['style']:DEFAULT_STYLE;
    //var_dump($pgStyle);die();
    $pgBandColorType = 1;
    //var_dump($pgStyle);die();
    /*
    if(($pgStyle == 7)) {
        $pgBandColorType = 4;
    } else */if(!empty($_POST['color_type'])) {
        $pgBandColorType = intval($_POST['color_type'], 10);  
    }
    $selected_style = 0;
    if(!empty($_POST['band_style'])) {
        $pgStyle = $selected_style = intval($_POST['band_style'], 10);
    }
    if(!empty($types_module->moduleData['id'][$pgType])) {
        $avstyles = explode(',', $types_module->moduleData['id'][$pgType]['available_styles_id']);
        
        if(!empty($builder['inhouse'])) {
            $avstyles = array_flip($avstyles);
            //var_dump($avstyles);die();
            $avstyles = array_intersect_key($avstyles, array(6=>0, 7=>1));
            //var_dump($avstyles);die();
            $avstyles = array_flip($avstyles);
        }
        //var_dump($avstyles);die();
        if(!in_array($pgStyle, $avstyles)) {
            $selected_style = reset($avstyles);
            
            $pgStyle = $selected_style;
            //var_dump($pgStyle);die();
            
            /*
            if(($pgStyle == 7)) {
                $pgBandColorType = 4;
            } else {
                $pgBandColorType = 1;
            }
            */
        }
        
    }
    
    
    if(!empty($_POST['band_color']))
    $pgBandColor = $_POST['band_color'];
    if(!empty($_POST['message_color']))
    $pgMessageColor = $_POST['message_color'];
    
$pgconf = compact(
		'pgType',
		'pgStyle',
		'pgBandColor',
		'pgMessageColor'
		);
    
    $bandcolor = $colors_module->BandColor_Section_SB($tpt_vars, $pgconf, $builder, $pgBandColorType);
    $pgBandColor = $bandcolor['pgBandColor'];
    $messagecolor = $colors_module->MessageColor_Section_SB($tpt_vars, $pgMessageColor, $pgType, $pgStyle, $builder);
    $pgMessageColor = $messagecolor['pgMessageColor'];
    
    
    //$section_band_color = $colors_module->BandColor_Section_SB($tpt_vars, $pgBandColor, $pgType, $pgStyle, $builder, $pgBandColorType);
    //$section_message_color = $colors_module->MessageColor_Section_SB($tpt_vars, $pgMessageColor, $pgType, $pgStyle, $builder);
    
    $section_band_color = $bandcolor['content'];
    $section_message_color = $messagecolor['content'];
    $result = <<< EOT
$section_band_color
$section_message_color
EOT;
    $tpt_vars['environment']['ajax_result']['update_elements'] = array('bandcolor_section'=>$result);
    $tpt_vars['environment']['ajax_result']['exec_script'][] = <<< EOT
//init_client_val();
tb_init('a.thickbox, area.thickbox, input.thickbox');
EOT;
}


