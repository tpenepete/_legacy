<?php

defined('TPT_INIT') or die('access denied');


// ********************************************** MODULES ************************************/
// BandColor

// function 
define('M_BC_CSC_NOMINPREFIX',  0b00000001);
define('M_BC_CSC_CTYPESUFFIX',  0b00000010);
define('M_BC_CSC_DLSELECT',     0b00000100);

/*
1:
                $solid_select = $this->Create_Combined_Solids_Select2($vars, self::$pgBandColor, $solid_stock_ids, '3', true, false);
            //}
            if($builder['inhouse'] || ($type == 5)) {
                //$solid_select = $this->Create_Stock_Solids_Select($vars, $selectedColor, $solid_stock_ids);

                //if($_SERVER['REMOTE_ADDR'] == '85.130.3.155') {
                    //die();
                    $solid_select = $this->Create_Combined_Solids_Select2($vars, self::$pgBandColor, $solid_stock_ids, '3', false, true);
                //}
            }
            $colorTypes['1'] = array('id'=>'1', 'label'=>'Solid', 'name'=>'solid', 'attr'=>'');
            $colorSelects['1'] = $solid_select;
            
2:
                $vars['db']['handler']->query($query, __FILE__);
                $swirl_stock_ids = $vars['db']['handler']->fetch_assoc_list();
                    //var_dump($swirl_stock_ids);die();
                    //$swirl_suggested_ids = $this->swirl;
                    //$swirl_suggested_labels = $vars['db']['handler']->getData($vars, 'tpt_color_swirl', '*', '`enabled`=1 ORDER BY  `label` ASC ', 'label', false);
                    //$swirl_select = $this->Create_Combined_Select($vars, $selectedColor, $swirl_stock_ids, $swirl_suggested_ids, $swirl_suggested_labels, '4', 'Select Swirl Band Color...');
                    $swirl_select = $this->Create_Combined_Select2($vars, self::$pgBandColor, $swirl_stock_ids, ($style == 1), false, 'Select Swirl Band Color...');
                    //if($builder['inhouse'])
                    //    $swirl_select = $this->Create_Combined_Select2($vars, $selectedColor, $swirl_stock_ids, '4', false, true, 'Select Swirl Band Color...');
                    $colorTypes['2'] = array('id'=>'2', 'label'=>'Swirl', 'name'=>'swirl', 'attr'=>'');
                    $colorSelects['2'] = $swirl_select;
                    
3:
                $vars['db']['handler']->query($query, __FILE__);
                $segmented_stock_ids = $vars['db']['handler']->fetch_assoc_list();
                    //if((in_array($style, array(1, 6)) && isset(getModule($vars, "BandData")->typeStyle[$type]['6']) && getModule($vars, "BandData")->typeStyle[$type]['6']['minimum_quantity'] == 1)) {
                    //$segmented_stock_ids = $vars['db']['handler']->getData($vars, 'tpt_color_special', '*', '`enabled`=1 AND `color_type`=5 AND `glitter`=0 AND `glow`=0 AND FIND_IN_SET(\''.$type.'\', `$tfield`) ORDER BY  `label` ASC ', 'id', false);
                    //}
                    //var_dump($segmented_stock_ids);die();
                    //$segmented_suggested_ids = $this->segment;
                    //$segmented_suggested_labels = $vars['db']['handler']->getData($vars, 'tpt_color_segmented', '*', '`enabled`=1 ORDER BY  `label` ASC ', 'label', false);
                    //$segmented_select = $this->Create_Combined_Select($vars, $selectedColor, $segmented_stock_ids, $segmented_suggested_ids, $segmented_suggested_labels, '5', 'Select Segmented Band Color...');
                    $segmented_select = $this->Create_Combined_Select2($vars, self::$pgBandColor, $segmented_stock_ids, ($style == 1), false, 'Select Segmented Band Color...');
                    //if($builder['inhouse'])
                    //    $segmented_select = $this->Create_Stock_Select($vars, $selectedColor, $segmented_stock_ids, '6', 'Select Segmented Band Color...');
                    $colorTypes['3'] = array('id'=>'3', 'label'=>'Segmented', 'name'=>'segmented', 'attr'=>'');
                    $colorSelects['3'] = $segmented_select;
                    
4:

5:
                    $multicolored_stock_ids = $vars['db']['handler']->getData($vars, 'tpt_color_special', '*', '`enabled`=1 AND (`color_type`=4 OR `color_type`=5) AND '. 'FIND_IN_SET(\''.$dType.'\', `'.$tfield.'`) ORDER BY  `label` ASC ', 'id', false);
                    if(!empty($multicolored_stock_ids)) {
                    $multicolored_select = $this->Create_Stock_Select($vars, self::$pgBandColor, $multicolored_stock_ids, '6', 'Select Swirl/Segmented Band Color...', (!$builder['inhouse'] && in_array($style, array(1, 6))));
                    $colorTypes['5'] = array('id'=>'5', 'label'=>'Multicolored', 'name'=>'multic', 'attr'=>'');
                    $colorSelects['5'] = $multicolored_select;

6:
                    $glitter_stock_ids = $vars['db']['handler']->getData($vars, 'tpt_color_overseas', '*', '`enabled`=1 AND `glitter`!=0 ORDER BY  `label` ASC ', 'id', false);
                    $glitter_select =  $this->Create_Combined_Select2($vars, self::$pgBandColor, $glitter_stock_ids, ($style == 1), false, 'Select Band Color (/w Glitter)...');
                    //$glitter_select =  $this->Create_Stock_Select($vars, self::$pgBandColor, $glitter_stock_ids, '6', 'Select Band Color (/w Glitter)...', (!$builder['inhouse'] && in_array($style, array(1, 6))));
                    $colorTypes['6'] = array('id'=>'6', 'label'=>'Glitter', 'name'=>'glitter', 'attr'=>'');
                    $colorSelects['6'] = $glitter_select;
                    
7:
                    $glow_stock_ids = $vars['db']['handler']->getData($vars, 'tpt_color_overseas', '*', '`enabled`=1 AND `glow`=1 AND `glitter`=0 ORDER BY  `label` ASC ', 'id', false);
                    $glow_select =  $this->Create_Combined_Select2($vars, self::$pgBandColor, $glow_stock_ids, ($style == 1), false, 'Select Band Color (/w Glow in the Dark)...');
                    //$glow_select =  $this->Create_Stock_Select($vars, self::$pgBandColor, $glow_stock_ids, '6', 'Select Band Color (/w Glow in the Dark)...', (!$builder['inhouse'] && in_array($style, array(1, 6))));
                    $colorTypes['7'] = array('id'=>'7', 'label'=>'Glow', 'name'=>'glow', 'attr'=>'');
                    $colorSelects['7'] = $glow_select;
                    
                    
        if($style != 7) {
            if($builder['inhouse'] || ($type == 5)) {
            }

            if(($dTypeArr['type'] != 5) && (empty($dTypeArr['writable']) || ($dTypeArr['base_type'] != 5))) {
                if(!$builder['inhouse']) {
                    if(empty($dTypeArr['writable'])) {
                    } // if NOT writable include segments END
                } else {
                }
            } else {
                if(empty($dTypeArr['writable'])) {
                } else {
                }
            }
            } else  {
            }
        }
        
        
                $query = <<< EOT
SELECT * FROM (

    (
    SELECT
    `id`,
    `label`,
    `color_id`,
    `color_type`,
    `message_color_id`,
    `glow`,
    `glitter`,
    `uv`,
    `1_2`,
    `1_4`,
    `3_4`,
    `1`,
    `slap`,
    `snap`,
    `keychain`,
    `ring`,
    `$tfield`,
    NULL AS `$dfield`,
    1 AS `stock`,
    REPLACE(`label`, " (", "b (") AS `ordlabel`
     FROM `tpt_color_special` WHERE
    `enabled`=1 AND
    `color_type`=3 AND
    `glitter`=0 AND
    FIND_IN_SET('$dIHType', `$tfield`) AND NOT
    `label` REGEXP '(True)'
    )

UNION

    (
    SELECT
    `id`,
    `label`,
    `color_id`,
    `color_type`,
    `message_color_id`,
    `glow`,
    `glitter`,
    `uv`,
    `1_2`,
    `1_4`,
    `3_4`,
    `1`,
    `slap`,
    `snap`,
    `keychain`,
    `ring`,
    `$tfield`,
    NULL AS `$dfield`,
    1 AS `stock`,
    REPLACE(`label`, " (", "a (") AS `ordlabel`
     FROM `tpt_color_special` WHERE
    `enabled`=1 AND
    `color_type`=3 AND
    `glitter`=0 AND
    FIND_IN_SET('$dIHType', `$tfield`) AND
    `label` REGEXP '(True)'
    )

UNION

    (
    SELECT
        `id`,
        `label`,
        `color_id`,
        `color_type`,
        NULL AS `message_color_id`,
        NULL AS `glow`,
        NULL AS `glitter`,
        NULL AS `uv`,
        NULL AS `1_2`,
        NULL AS `1_4`,
        NULL AS `3_4`,
        NULL AS `1`,
        NULL AS `slap`,
        NULL AS `snap`,
        NULL AS `keychain`,
        NULL AS `ring`,
        NULL AS `$tfield`,
        `$dfield`,
        NULL AS `stock`,
        REPLACE(`label`, " (", "b (") AS `ordlabel`
    FROM
        `tpt_color_overseas`
    WHERE
        `color_type`=3
        AND
        `enabled`=1
        AND
        (
            `$dfield` IS NULL
            OR
            `$dfield`=''
            OR
            NOT FIND_IN_SET('$dType', `$dfield`)
        )
        AND
        `label` NOT IN
            (
            SELECT `label`
            FROM `tpt_color_special` WHERE
            `enabled`=1 AND
            `color_type`=3 AND
            `glitter`=0 AND
            FIND_IN_SET('$dType', `$tfield`)
            )
        AND NOT
        `label` REGEXP '(True)'
    )

UNION

    (
    SELECT
        `id`,
        `label`,
        `color_id`,
        `color_type`,
        NULL AS `message_color_id`,
        NULL AS `glow`,
        NULL AS `glitter`,
        NULL AS `uv`,
        NULL AS `1_2`,
        NULL AS `1_4`,
        NULL AS `3_4`,
        NULL AS `1`,
        NULL AS `slap`,
        NULL AS `snap`,
        NULL AS `keychain`,
        NULL AS `ring`,
        NULL AS `$tfield`,
        `$dfield`,
        NULL AS `stock`,
        REPLACE(`label`, " (", "a (") AS `ordlabel`
    FROM
        `tpt_color_overseas`
    WHERE
        `color_type`=3
        AND
        `enabled`=1
        AND
        (
            `$dfield` IS NULL
            OR
            `$dfield`=''
            OR
            NOT FIND_IN_SET('$dType', `$dfield`)
        )
        AND
        `label` NOT IN
            (
            SELECT `label`
            FROM `tpt_color_special` WHERE
            `enabled`=1 AND
            `color_type`=3 AND
            `glitter`=0 AND
            FIND_IN_SET('$dType', `$tfield`)
            )
        AND
        `label` REGEXP '(True)'
)
ORDER BY `ordlabel` ASC) AS `a` GROUP BY `ordlabel`
EOT;

*/