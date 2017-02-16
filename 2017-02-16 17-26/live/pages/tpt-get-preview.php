<?php

defined('TPT_INIT') or die('access denied');

$pgType = (!empty($_GET['band_type'])?intval($_GET['band_type'], 10):DEFAULT_TYPE);
$pgStyle = (!empty($_GET['band_style'])?intval($_GET['band_style'], 10):DEFAULT_STYLE);
$pgFont = (!empty($_GET['band_font'])?$_GET['band_font']:DEFAULT_FONT_NAME);
$pgFrontRows = 0;
$pgBackRows = 0;
$pgTextCont = 1;
$pgBandColor = '-1:'.DEFAULT_BAND_COLOR;
$pgFrontMessage = (!empty($_GET['message_front'])?$_GET['message_front']:'');
$pgClipartFrontLeft = (!empty($_GET['clipart_front_left'])?$_GET['clipart_front_left']:'');
$pgClipartFrontRight = (!empty($_GET['clipart_front_right'])?$_GET['clipart_front_right']:'');
$pgFrontMessage2 = (!empty($_GET['message_front2'])?$_GET['message_front2']:'');
$pgClipartFrontLeft2 = (!empty($_GET['clipart_front_left2'])?$_GET['clipart_front_left2']:'');
$pgClipartFrontRight2 = (!empty($_GET['clipart_front_right2'])?$_GET['clipart_front_right2']:'');
$pgBackMessage = (!empty($_GET['message_back'])?$_GET['message_back']:'');
$pgClipartBackLeft = (!empty($_GET['clipart_back_left'])?$_GET['clipart_back_left']:'');
$pgClipartBackRight = (!empty($_GET['clipart_back_right'])?$_GET['clipart_back_right']:'');
$pgBackMessage2 = (!empty($_GET['message_back2'])?$_GET['message_back2']:'');
$pgClipartBackLeft2 = (!empty($_GET['clipart_back_left2'])?$_GET['clipart_back_left2']:'');
$pgClipartBackRight2 = (!empty($_GET['clipart_back_right2'])?$_GET['clipart_back_right2']:'');
if(empty($_GET['text_cont'])) {
    $filter = array_filter(array($pgBackMessage, $pgBackMessage2));
    if(!empty($filter)) {
        $pgTextCont = 0;
    } else {
        $pgTextCont = getModule($tpt_vars, "BandData")->typeStyle[$pgType][$pgStyle]['text_continuous_msg'];
    }
} else {
    $pgTextCont = intval($_GET['text_cont'], 10);
}
if(empty($_GET['front_rows'])) {
    $pgFrontRows = max(count(array_filter(array($pgFrontMessage, $pgFrontMessage2))), 1);
} else {
    $pgFrontRows = intval($_GET['front_rows'], 10);
}
if(empty($_GET['back_rows'])) {
    $pgBackRows = max(count(array_filter(array($pgBackMessage, $pgBackMessage2))), 1);
} else {
    $pgBackRows = intval($_GET['back_rows'], 10);
}
//var_dump(array_filter(array($pgFrontMessage, $pgFrontMessage2)));
//var_dump(count(array_filter(array($pgBackMessage, $pgBackMessage2))));
//die();
if(!empty($_GET['band_color'])) {
    $pgBandColor = urldecode($_GET['band_color']);
}
$pgMessageColor = '-1:'.DEFAULT_MESSAGE_COLOR;
if(!empty($_GET['message_color'])) {
    $pgMessageColor = urldecode($_GET['message_color']);
}

$pgFullPreview = 1;
$pgEnableJavascript = 0;
$pgAjaxJavascript = 0;


$pgconf = compact(
		'pgType',
		'pgStyle',
		'pgFont',
		'pgFrontRows',
		'pgBackRows',
		'pgTextCont',
		'pgBandColor',
		'pgMessageColor',
                'pgFrontMessage',
                'pgClipartFrontLeft',
                'pgClipartFrontRight',
                'pgFrontMessage2',
                'pgClipartFrontLeft2',
                'pgClipartFrontRight2',
                'pgBackMessage',
                'pgClipartBackLeft',
                'pgClipartBackRight',
                'pgBackMessage2',
                'pgClipartBackLeft2',
                'pgClipartBackRight2',
                'pgFullPreview',
                'pgEnableJavascript',
                'pgAjaxJavascript'
		);

//var_dump($pgBandColor);
//var_dump($pgMessageColor);
//die();
include(TPT_TEMPLATES_DIR.DIRECTORY_SEPARATOR.'builder-preview.tpt.php');

$tpt_vars['template_data']['template_type'] = 'plain';
$tpt_vars['template']['content'] .= $preview;
