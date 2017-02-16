<?php

defined('TPT_INIT') or die('access denied');

if(empty($tpt_vars)) {
    global $tpt_vars;
}

$items = $tpt_vars['db']['handler']->getData($tpt_vars, 'tpt_color_special', '*', '`enabled`=1 AND `glitter`=1 AND FIND_IN_SET(\''.$dType.'\', `'.$tfield.'`) ORDER BY  `label` ASC ', 'id', false);