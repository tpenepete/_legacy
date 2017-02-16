<?php

defined('TPT_INIT') or die('access denied');

if(empty($tpt_vars)) {
    global $tpt_vars;
}

$items = $tpt_vars['db']['handler']->getData($tpt_vars, 'tpt_color_overseas', '*', '`enabled`=1 AND `glow`=1 AND `glitter`=0 ORDER BY  `label` ASC ', 'id', false);