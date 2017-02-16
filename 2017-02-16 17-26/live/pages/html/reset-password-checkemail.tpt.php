<?php
//die();
defined('TPT_INIT') or die('access denied');

$hashedtoken = '';
$action_url = $tpt_vars['url']['handler']->wrap($tpt_vars, '/reset-password');

$tpt_vars['template']['content'] .= <<< EOT

EOT;

?>