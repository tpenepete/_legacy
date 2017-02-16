<?php

defined('TPT_INIT') or die('access denied');

$bsection_module = getModule($tpt_vars, 'BuilderSection');

//tpt_dump("Testing by renold", false, 'R');
//tpt_dump($bsection_module, false, 'R');
//tpt_dump($input, false, 'R');
//tpt_dump($options, false, 'R');

//die('asd');
$html = $bsection_module->getBuilderSectionsHTML($tpt_vars, $input, $options);
