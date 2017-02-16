<?php
//var_dump($_GET);die();

defined('TPT_INIT') or die('access denied');

$preview = tpt_PreviewGenerator::previewHTML($tpt_vars, $pgconf);
