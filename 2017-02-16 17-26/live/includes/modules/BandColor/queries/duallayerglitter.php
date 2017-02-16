<?php

defined('TPT_INIT') or die('access denied');

if(empty($tpt_vars)) {
    global $tpt_vars;
}

$labelcmp = 'dl.`label`';
//if($type == 5) {
//    $labelcmp = 'CONCAT(dl.`label`, " Msg") AS `label`';
//}
//$sColorType = '4';

$query = 'SELECT dl.`id`, '.$labelcmp.', dl.`message_color_id`, dl.`'.$tfield.'`, sp.`id` AS msgid, sp.`label` AS msglabel, sp.`color_type`, sp.`glow`, sp.`glitter`, sp.`uv` FROM `tpt_color_duallayer` AS dl LEFT JOIN `tpt_color_special` AS sp ON dl.`message_color_id`=sp.`id` WHERE dl.`enabled`=1 AND FIND_IN_SET(\''.$dType.'\', dl.`'.$tfield.'`) AND sp.`glitter`!=0 ORDER BY `label` ASC';
$tpt_vars['db']['handler']->query($query, __FILE__);
//tpt_dump($query, true);
$items = $tpt_vars['db']['handler']->fetch_assoc_list('id', false);
