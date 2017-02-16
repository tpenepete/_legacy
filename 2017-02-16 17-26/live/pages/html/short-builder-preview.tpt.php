<?php

defined('TPT_INIT') or die('access denied');

//tpt_dump($vinput);
$html = tpt_PreviewGenerator::previewHTML2($tpt_vars, $actives, $builder, $vinput);