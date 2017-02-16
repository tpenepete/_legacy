<?php
defined('TPT_INIT') or die('access denied');

$fpath = TPT_INCLUDES_CONFIG_DIR.DIRECTORY_SEPARATOR.'tpt_previewgenerator.cfg.php';
$evars = tpt_functions::f_get_defined_vars($tpt_vars, get_defined_vars());
$fvars = tpt_functions::f_include_once($tpt_vars, $fpath, $evars);
//tpt_dump($fvars['tpt_vars']['config'], true);
extract($fvars);
$fpath = TPT_INCLUDES_CONFIG_DIR.DIRECTORY_SEPARATOR.'fonts.cfg.php';
$evars = tpt_functions::f_get_defined_vars($tpt_vars, get_defined_vars());
$fvars = tpt_functions::f_include_once($tpt_vars, $fpath, $evars);
extract($fvars);
$fpath = TPT_INCLUDES_CONFIG_DIR.DIRECTORY_SEPARATOR.'clipart.cfg.php';
$evars = tpt_functions::f_get_defined_vars($tpt_vars, get_defined_vars());
$fvars = tpt_functions::f_include_once($tpt_vars, $fpath, $evars);
extract($fvars);

define('EMPTY_IMAGE_FILE', TPT_CACHE_DIR.DIRECTORY_SEPARATOR.'empty.png');

//include(TPT_INCLUDES_DIR.DIRECTORY_SEPARATOR.'tpt_gclass.php');

class tpt_PreviewGenerator {

    //var $options;
    static $gClassesDir;

    //function __construct(&$vars/*, $options=array()*/) {
        //$this->options = $options;
    //}

    static function generatePreview(&$vars/*, &$steps=array()*/, $data=array()) {
//var_dump($data);//die();
//var_dump($data['type']);die();
        $data_module = getModule($vars, "BandData");
        $fonts_module = getModule($vars, "BandFont");
        $fonts = $fonts_module->moduleData['id'];

$out = '';

$data['utext']  = (isset($data['text']) && ($data['text'] !== '') && !is_null($data['text']))?$data['text']:null;
$data['text']  = isset( $data['text'] ) ? $data['text'] : '' ;
$data['font']  = (!empty($data['font']) && !empty($fonts[$data['font']]) && is_file(FONTS_PATH.DIRECTORY_SEPARATOR.$fonts[$data['font']]['file']) ) ? $fonts[$data['font']]['file'] : DEFAULT_FONT_NAME ;
$data['bandType']  = (isset($data['bandType']) ) ? $data['bandType'] : DEFAULT_TYPE ;
$data['bandStyle']  = (isset($data['bandStyle']) ) ? $data['bandStyle'] : DEFAULT_STYLE ;
$bandImagesDir = $data_module->typeStyle[$data['bandType']][$data['bandStyle']]['preview_folder'];


//var_dump($fontName);die();

$options = array();

/*
$options = array(
    'gClass'=>'Simple',
    'format'=>'jpg'
);
if($data['format']) {
    preg_match('#png#i', $data['format'], $mtch);
    if(!empty($mtch)) {
        $options['format'] = 'png';
    }
}
*/

if(empty($data['type'])) {
    $options['gClass'] = 'Simple';
    $options['X'] = '201';
    $options['Y'] = '27';
    $options['text'] = ''.escapeshellarg(str_replace('\\', '\\\\', $data['text'])).'';
    //var_dump($options['text']);die();
    $options['font'] = $data['font'];
    $options['bandType'] = $data['bandType'];
    $options['bandStyle'] = $data['bandStyle'];
    $options['linespacing'] = '0';
} else {
    switch(strtolower(trim($data['type']))) {
    /*
        case 'we3d' :

            $options['gClass'] = 'We3d';
            $options['X'] = '253';
            $options['Y'] = '49';
            $options['text'] = ''.escapeshellarg(str_replace('\\', '\\\\', $data['text'])).'';
            //var_dump($options['text']);die();
            $options['font'] = $data['font'];
            $options['linespacing'] = '0';


            $cr = isset($data['color_r'])?intval($data['color_r'], 10):255;
            $cg = isset($data['color_g'])?intval($data['color_g'], 10):255;
            $cb = isset($data['color_b'])?intval($data['color_b'], 10):255;
            $textColor = 'rgb('.$cr.','.$cg.','.$cb.')';
            $options['textColor'] = $textColor;

            $swcr = isset($data['sw_color_r'])?intval($data['sw_color_r'], 10):false;
            $swcg = isset($data['sw_color_g'])?intval($data['sw_color_g'], 10):false;
            $swcb = isset($data['sw_color_b'])?intval($data['sw_color_b'], 10):false;
            if(($swcr !== false) && ($swcg !== false) && ($swcb !== false))
                $swirlColor = 'rgb('.$swcr.','.$swcg.','.$swcb.')';
            else if(isset($data['swirlcolor'])){
                $swirlColor = $data['swirlcolor'];
                $swirlColor = rgb2hex2rgb($swirlColor);
                $swirlColor = 'rgb('.$swirlColor['r'].','.$swirlColor['g'].','.$swirlColor['b'].')';
            } else {
                $swirlColor = false;
            }
            $options['swirlColor'] = $swirlColor;

            if(strlen($data['text']) > 20)
                    $emboss = '1';
            else
                    $emboss = '1';
            $botpadfactor = 0;

            $options['extrude'] = $emboss;
            $initpad = 48;
            $options['botpad'] = $initpad + $botpadfactor;
            $options['toppad'] = 23;
            $options['fullsizeX'] = '313';
            $options['fullsizeY'] = '167';

            $options['perspective'] = 20;
            $options['distort'] = '0.5';

            $options['format'] = 'png';
            break;
        case 'full' :
            $options['gClass'] = 'Full';
            //$options['gClass'] = 'Debossed';
            $options['text'] = ''.escapeshellarg(str_replace('\\', '\\\\', $data['text'])).'';
            //var_dump($options['text']);die();
            $options['font'] = $data['font'];
            $options['bandType'] = $data['bandType'];
            $options['bandStyle'] = $data['bandStyle'];
            $options['bandImagesDir'] = $bandImagesDir;
            $options['linespacing'] = '0';


            $cr = isset($data['color_r'])?intval($data['color_r'], 10):255;
            $cg = isset($data['color_g'])?intval($data['color_g'], 10):255;
            $cb = isset($data['color_b'])?intval($data['color_b'], 10):255;
            $textColor = 'rgb('.$cr.','.$cg.','.$cb.')';
            $options['textColor'] = $textColor;

            $swcr = isset($data['sw_color_r'])?intval($data['sw_color_r'], 10):false;
            $swcg = isset($data['sw_color_g'])?intval($data['sw_color_g'], 10):false;
            $swcb = isset($data['sw_color_b'])?intval($data['sw_color_b'], 10):false;

            $swirlColor = array();
            if(($swcr !== false) && ($swcg !== false) && ($swcb !== false))
                $swirlColor = 'rgb('.$swcr.','.$swcg.','.$swcb.')';
            else if(isset($data['swirlcolor'])){
                foreach($data['swirlcolor'] as $key=>$cldata) {
                    $sc = rgb2hex2rgb($cldata);
                    $swirlColor[$key] = 'rgb('.$sc['r'].','.$sc['g'].','.$sc['b'].')';
                }
            } else {
                $swirlColor = false;
            }
            $options['swirlColor'] = $swirlColor;

            if(isset($data['glitter'])){
                $options['glitter'] = intval($data['glitter'], 10);
            }

            if(strlen($data['text']) > 20)
                    $emboss = '1';
            else
                    $emboss = '1';
            $botpadfactor = 0;

            switch(intval($data['bandType'], 10)) {
                case 1:
                    $options['X'] = '253';
                    $options['Y'] = '20';

                    $options['extrude'] = $emboss;
                    $initpad = 44;
                    $options['botpad'] = $initpad + $botpadfactor;
                    $options['toppad'] = 42;
                    $options['fullsizeX'] = '313';
                    $options['fullsizeY'] = '167';

                    $options['perspective'] = 20;
                    $options['distort'] = '0.5';
                    break;
                case 3:
                    $options['X'] = '253';
                    $options['Y'] = '59';

                    $options['extrude'] = $emboss;
                    $initpad = 32;
                    $options['botpad'] = $initpad + $botpadfactor;
                    $options['toppad'] = 46;
                    $options['fullsizeX'] = '313';
                    $options['fullsizeY'] = '167';

                    $options['perspective'] = 20;
                    $options['distort'] = '0.5';
                    break;
                case 4:
                    $options['X'] = '253';
                    $options['Y'] = '79';

                    $options['extrude'] = $emboss;
                    $initpad = 32;
                    $options['botpad'] = $initpad + $botpadfactor;
                    $options['toppad'] = 46;
                    $options['fullsizeX'] = '313';
                    $options['fullsizeY'] = '167';

                    $options['perspective'] = 20;
                    $options['distort'] = '0.5';
                    break;
                case 6:
                    $options['X'] = '253';
                    $options['Y'] = '20';

                    $options['extrude'] = $emboss;
                    $initpad = 24;
                    $options['botpad'] = $initpad + $botpadfactor;
                    $options['toppad'] = 62;
                    $options['fullsizeX'] = '313';
                    $options['fullsizeY'] = '167';

                    $options['perspective'] = 20;
                    $options['distort'] = '0.5';
                    break;
                case 2:
                case 5:
                default:
                    $options['X'] = '253';
                    $options['Y'] = '49';

                    $options['extrude'] = $emboss;
                    $initpad = 48;
                    $options['botpad'] = $initpad + $botpadfactor;
                    $options['toppad'] = 23;
                    $options['fullsizeX'] = '313';
                    $options['fullsizeY'] = '167';

                    $options['perspective'] = 20;
                    $options['distort'] = '0.5';
                    break;
            }

            $options['format'] = 'png';

            break;
        case 'embossed' :
            $options['gClass'] = 'Embossed';
            $options['X'] = '253';
            $options['Y'] = '49';
            $options['text'] = ''.escapeshellarg(str_replace('\\', '\\\\', $data['text'])).'';
            //var_dump($options['text']);die();
            $options['font'] = $data['font'];
            $options['linespacing'] = '0';


            $cr = isset($data['color_r'])?intval($data['color_r'], 10):255;
            $cg = isset($data['color_g'])?intval($data['color_g'], 10):255;
            $cb = isset($data['color_b'])?intval($data['color_b'], 10):255;
            $textColor = 'rgb('.$cr.','.$cg.','.$cb.')';
            $options['textColor'] = $textColor;

            $swcr = isset($data['sw_color_r'])?intval($data['sw_color_r'], 10):false;
            $swcg = isset($data['sw_color_g'])?intval($data['sw_color_g'], 10):false;
            $swcb = isset($data['sw_color_b'])?intval($data['sw_color_b'], 10):false;
            if(($swcr !== false) && ($swcg !== false) && ($swcb !== false))
                $swirlColor = 'rgb('.$swcr.','.$swcg.','.$swcb.')';
            else if(isset($data['swirlcolor'])){
                $swirlColor = $data['swirlcolor'];
                $swirlColor = rgb2hex2rgb($swirlColor);
                $swirlColor = 'rgb('.$swirlColor['r'].','.$swirlColor['g'].','.$swirlColor['b'].')';
            } else {
                $swirlColor = false;
            }
            $options['swirlColor'] = $swirlColor;

            if(strlen($data['text']) > 20)
                    $emboss = '1';
            else
                    $emboss = '1';
            $botpadfactor = 0;

            $options['extrude'] = $emboss;
            $initpad = 48;
            $options['botpad'] = $initpad + $botpadfactor;
            $options['toppad'] = 23;
            $options['fullsizeX'] = '313';
            $options['fullsizeY'] = '167';

            $options['perspective'] = 20;
            $options['distort'] = '0.5';

            $options['format'] = 'png';

            break;
        case 'vector' :
            $options['gClass'] = 'Vector';
            $options['X'] = '41';
            $options['Y'] = '17';
            $options['text'] = $data['text'];
            $options['font'] = $data['font'];
            $options['linespacing'] = '0';
            break;
        case 'eps' :
            $options['gClass'] = 'EPS';
            $options['X'] = '401';
            $options['Y'] = '87';
            $options['text'] = $data['text'];
            $options['font'] = $data['font'];
            $options['linespacing'] = '0';
            break;
        case 'test' :
            $options['gClass'] = 'test';
            break;
    */
        case 'flat' :
            $types_module = getModule($vars, "BandType");
            $data_module = getModule($vars, "BandData");
            $colors_module = getModule($vars, "BandColor");

            $flatimg = array();
            $options['gClass'] = 'Flat';
            $flatimg[] = $options['type'] = $data['pgType'];
            $flatimg[] = $options['style'] = $data['pgStyle'];
            $flatimg[] = $options['invert_dual'] = intval($data['invert_dual'], 10);
            $flatimg[] = $options['cut_away'] = intval($data['cut_away'], 10);
            //var_dump($options['cut_away']);die();
            $flatimg[] = $options['font'] = $data['pgFont'];
            $flatimg[] = $options['color'] = $data['pgBandColor'];
            $flatimg[] = $options['mcolor'] = $data['pgMessageColor'];
            $options['message1'] = stripslashes($data['pgFrontMessage']);
            $flatimg[] = 'f1:'.$options['message1'];
            $flatimg[] = $options['clipart11'] = !empty($data['pgClipartFrontLeft'])?$data['pgClipartFrontLeft']:0;
			$flatimg[] = $options['clipart11_c'] = !empty($data['pgClipartFrontLeft_c'])?$data['pgClipartFrontLeft_c']:0;
            $flatimg[] = $options['clipart12'] = !empty($data['pgClipartFrontRight'])?$data['pgClipartFrontRight']:0;
			$flatimg[] = $options['clipart12_c'] = !empty($data['pgClipartFrontRight_c'])?$data['pgClipartFrontRight_c']:0;
            $options['message2'] = stripslashes($data['pgFrontMessage2']);
            $flatimg[] = 'f2:'.$options['message2'];
            $flatimg[] = $options['clipart21'] = !empty($data['pgClipartFrontLeft2'])?$data['pgClipartFrontLeft2']:0;
			$flatimg[] = $options['clipart21_c'] = !empty($data['pgClipartFrontLeft2_c'])?$data['pgClipartFrontLeft2_c']:0;
            $flatimg[] = $options['clipart22'] = !empty($data['pgClipartFrontRight2'])?$data['pgClipartFrontRight2']:0;
			$flatimg[] = $options['clipart22_c'] = !empty($data['pgClipartFrontRight2_c'])?$data['pgClipartFrontRight2_c']:0;
            $options['message3'] = stripslashes($data['pgBackMessage']);
            $flatimg[] = 'b1:'.$options['message3'];
            $flatimg[] = $options['clipart31'] = !empty($data['pgClipartBackLeft'])?$data['pgClipartBackLeft']:0;
			$flatimg[] = $options['clipart31_c'] = !empty($data['pgClipartBackLeft_c'])?$data['pgClipartBackLeft_c']:0;
            $flatimg[] = $options['clipart32'] = !empty($data['pgClipartBackRight'])?$data['pgClipartBackRight']:0;
			$flatimg[] = $options['clipart32_c'] = !empty($data['pgClipartBackRight_c'])?$data['pgClipartBackRight_c']:0;
            $options['message4'] = stripslashes($data['pgBackMessage2']);
            $flatimg[] = 'b2:'.$options['message4'];
            $flatimg[] = $options['clipart41'] = !empty($data['pgClipartBackLeft2'])?$data['pgClipartBackLeft2']:0;
			$flatimg[] = $options['clipart41_c'] = !empty($data['pgClipartBackLeft2_c'])?$data['pgClipartBackLeft2_c']:0;
            $flatimg[] = $options['clipart42'] = !empty($data['pgClipartBackRight2'])?$data['pgClipartBackRight2']:0;
			$flatimg[] = $options['clipart42_c'] = !empty($data['pgClipartBackRight2_c'])?$data['pgClipartBackRight2_c']:0;
            $flatimg[] = $data['pg_x'];
            $flatimg[] = $data['pg_y'];

            $filename = sha1(implode($flatimg)).'.png';
			//tpt_dump($filename, true);

            $cfile = TPT_IMAGES_DIR.DIRECTORY_SEPARATOR.'preview'.DIRECTORY_SEPARATOR.'cached'.DIRECTORY_SEPARATOR.'flat'.DIRECTORY_SEPARATOR.$filename;
            //tpt_dump($cfile, true);
            if(is_file($cfile) && empty($_GET['uncache'])) {
                //tpt_dump($cfile, true);
                //header('Content-type: image/png');
                $out = file_get_contents($cfile);
            } else {

                $bdata = $data_module->typeStyle[$options['type']][$options['style']];
				//tpt_dump($options);
				//tpt_dump($bdata, true);


            $options['canvasX'] = $fullWidth = $data_module->typeStyle[$options['type']][$options['style']]['preview_width'];
            $options['canvasY'] = $fullHeight = $data_module->typeStyle[$options['type']][$options['style']]['preview_height'];
            $options['canvasPaddingTop'] = $paddingTop = $data_module->typeStyle[$options['type']][$options['style']]['preview_bg_toppadding'];
            $options['canvasPaddingBottom'] = $paddingBottom = $data_module->typeStyle[$options['type']][$options['style']]['preview_bg_bottompadding'];
            $options['canvasBGWidth'] = $bgWidth = $data_module->typeStyle[$options['type']][$options['style']]['preview_bg_width'];
            $options['canvasBGHeight'] = $bgHeight = $data_module->typeStyle[$options['type']][$options['style']]['preview_bg_height'];
            $options['canvasPaddingBottom'] = $paddingBottom = $data_module->typeStyle[$options['type']][$options['style']]['preview_bg_bottompadding'];
            $options['canvasPaddingLeft'] = $paddingLeft = $data_module->typeStyle[$options['type']][$options['style']]['preview_leftpadding'];
            $options['canvasPaddingRight'] = $paddingRight = $data_module->typeStyle[$options['type']][$options['style']]['preview_rightpadding'];

            $options['messagePaddingTop'] = $mPaddingTop = $data_module->typeStyle[$options['type']][$options['style']]['preview_toppadding'];
            $options['messagePaddingBottom'] = $mPaddingBottom = $data_module->typeStyle[$options['type']][$options['style']]['preview_bottompadding'];

            if((($data['pgType'] == 5) && ($data['pgStyle'] == 7))) {
                $options['canvasPaddingLeft'] = $paddingLeft = 60;
                $options['canvasPaddingRight'] = $paddingRight = 60;
                $options['canvasPaddingTop'] = $paddingTop = 5;
                $options['canvasPaddingBottom'] = $paddingBottom = 5;
                $options['messagePaddingTop'] = $mPaddingTop = 13;
                $options['messagePaddingBottom'] = $mPaddingBottom = 11;
            } else if((($data['pgType'] == 5) && ($data['pgStyle'] == 6) && !empty($options['cut_away'])) || ($data['pgStyle'] == 8)) {
                //die('asdasdassad');
                $options['canvasPaddingLeft'] = $paddingLeft = 60;
                $options['canvasPaddingRight'] = $paddingRight = 60;
                $options['canvasPaddingTop'] = $paddingTop = 5;
                $options['canvasPaddingBottom'] = $paddingBottom = 5;
                $options['messagePaddingTop'] = $mPaddingTop = 13;
                $options['messagePaddingBottom'] = $mPaddingBottom = 11;
            }


            $options['finalX'] = $fullWidth;
            $options['finalY'] = $fullHeight;





            $pgWidthProcessed = $fullWidth - ($paddingLeft + $paddingRight);
            $pg_fx = $pgWidthProcessed;
            $pg_x = $pgWidthProcessed;


            $pgFrontRows = 0;
            if((!empty($options['message1']) || !empty($options['clipart11']) || !empty($options['clipart12']) || !empty($options['clipart11_c']) || !empty($options['clipart12_c']))) {
                $pgFrontRows++;
            } else {
                $pgFrontMessage = DEFAULT_MESSAGE_FRONT;
            }
            if((!empty($options['message2']) || !empty($options['clipart21']) || !empty($options['clipart22']) || !empty($options['clipart21_c']) || !empty($options['clipart22_c']))) {
                $pgFrontRows++;
            } else {
                $pgFrontMessage = DEFAULT_MESSAGE_FRONT2;
            }
            $pgBackRows = 0;
            if((!empty($options['message3']) || !empty($options['clipart31']) || !empty($options['clipart32']) || !empty($options['clipart31_c']) || !empty($options['clipart32_c']))) {
                $pgBackRows++;
            } else {
                $pgBackMessage2 = DEFAULT_MESSAGE_BACK;
            }
            if((!empty($options['message4']) || !empty($options['clipart41']) || !empty($options['clipart42']) || !empty($options['clipart41_c']) || !empty($options['clipart42_c']))) {
                $pgBackRows = 2;
            } else {
                $pgBackMessage2 = DEFAULT_MESSAGE_BACK2;
            }

            //tpt_dump($options['message1']);
            //tpt_dump($options['message2']);
            //tpt_dump($options['message3']);
            //tpt_dump($options['message4'], true);
            //tpt_dump($pgTextCont, true);
            //tpt_dump($pg_x, true);
            $pgTextCont = 0;
            if(empty($pgBackRows)) {
                $pgTextCont = 1;
            }
            //if(($options['type'] == 9) || ($options['type'] == 10)) {
            if(!empty($types_module->moduleData['id'][$options['type']]['full_wrap_strip']) || !empty($types_module->moduleData['id'][$options['type']]['blank'])) {
                $pgTextCont = 1;
            }

            if(!$pgTextCont) {
                $pg_x = round($pg_x/2);
            }


            $pgHeightProcessed = $fullHeight - ($paddingTop + $paddingBottom);
            $pgHeightMessage = $pgHeightProcessed - ($mPaddingTop + $mPaddingBottom);
            $pgHeightMessageHalf = round($pgHeightMessage/2);
            $pg_yf = $pg_yb = $pg_yp = $pgHeightMessage;
            $pg_fy = $fullHeight;

            $message1 = array();
            $message2 = array();
            $message3 = array();
            $message4 = array();

            if($pgFrontRows == 2) {
                $pg_yf = $pgHeightMessageHalf;
                $message2 = array(
                                'pg_x'=>$pg_x,
                                'pg_y'=>$pg_yf,
                                'bandType'=>$options['type'],
                                'bandStyle'=>$options['style'],
                                'text'=>$options['message2'],
                                'color'=>$options['color'],
                                'textColor'=>$options['mcolor'],
                                'font'=>$options['font'],
                                'lclipart'=>$options['clipart21'],
                                'rclipart'=>$options['clipart22'],
								'lclipart_c'=>$options['clipart21_c'],
								'rclipart_c'=>$options['clipart22_c'],
                                'invert_dual'=>$options['invert_dual'],
                                'cut_away'=>$options['cut_away'],
                                'elmid'=>'tpt_pg_front2_message',
                                'type'=>'plain'
                                  );
            }

            $message1 = array(
                              'pg_x'=>$pg_x,
                              'pg_y'=>$pg_yf,
                              'bandType'=>$options['type'],
                              'bandStyle'=>$options['style'],
                              'text'=>$options['message1'],
                              'color'=>$options['color'],
                              'textColor'=>$options['mcolor'],
                              'font'=>$options['font'],
                              'lclipart'=>$options['clipart11'],
                              'rclipart'=>$options['clipart12'],
								'lclipart_c'=>$options['clipart11_c'],
								'rclipart_c'=>$options['clipart12_c'],
                              'invert_dual'=>$options['invert_dual'],
                              'cut_away'=>$options['cut_away'],
                              'elmid'=>'tpt_pg_front_message',
                              'type'=>'plain'
                              );
				//tpt_dump($message1);


            //var_dump($pgTextCont);die();
            if(!$pgTextCont) {
                if($pgBackRows == 2) {
                    $pg_yb = $pgHeightMessageHalf;
                    $message4 = array(
                                'pg_x'=>$pg_x,
                                'pg_y'=>$pg_yb,
                                'bandType'=>$options['type'],
                                'bandStyle'=>$options['style'],
                                'text'=>$options['message4'],
                                'color'=>$options['color'],
                                'textColor'=>$options['mcolor'],
                                'font'=>$options['font'],
                                'lclipart'=>$options['clipart41'],
                                'rclipart'=>$options['clipart42'],
								'lclipart_c'=>$options['clipart41_c'],
								'rclipart_c'=>$options['clipart42_c'],
                                'invert_dual'=>$options['invert_dual'],
                                'cut_away'=>$options['cut_away'],
                                'elmid'=>'tpt_pg_back2_message',
                                'type'=>'plain'
                                      );
                }

                //var_dump($options['message3']);die();
                $message3 = array(
                                'pg_x'=>$pg_x,
                                'pg_y'=>$pg_yb,
                                'bandType'=>$options['type'],
                                'bandStyle'=>$options['style'],
                                'text'=>$options['message3'],
                                'color'=>$options['color'],
                                'textColor'=>$options['mcolor'],
                                'font'=>$options['font'],
                                'lclipart'=>$options['clipart31'],
                                'rclipart'=>$options['clipart32'],
								'lclipart_c'=>$options['clipart31_c'],
								'rclipart_c'=>$options['clipart32_c'],
                                'invert_dual'=>$options['invert_dual'],
                                'cut_away'=>$options['cut_away'],
                                'elmid'=>'tpt_pg_back_message',
                                'type'=>'plain'
                                  );
            }

            $left = array();
            $right = array();

            //var_dump($right);die();
            //tpt_dump($message1, true);
            if(!empty($message1)) {
                $steps = array();
                $left[] = $message_1 = self::generatePreview($vars, $message1);
                //if($_SERVER['REMOTE_ADDR'] == '109.160.0.218') {
				//if(isDump()) {
				//	$out = $message_1;
				//}
                //tpt_dump($message_1, true);
                //}
                //var_dump($left);die();
                //$out = reset($left);

            }

            //tpt_dump($message2);
            if(!empty($message2)) {
                $steps = array();
                $left[] = self::generatePreview($vars, $message2);

            }
            //tpt_dump($left, true);
            //var_dump($right);die();
            //var_dump($message3);die();
            //if(($options['type'] != 9) && ($options['type'] != 10)) {
                if(!empty($message3)) {
                    $steps = array();
                    $right[] = self::generatePreview($vars, $message3);
                    //var_dump($right);die();
                }
                if(!empty($message4)) {
                    $steps = array();
                    $right[] = self::generatePreview($vars, $message4);
                }
            //}
            //header('Content-type: image/png');
            //die(reset($right));
            //var_dump($right);die();

            $bandbg = $colors_module->getBandColorPreviewParams($vars, $options['color'], $bgWidth, $bgHeight);
            $bandbgprops = $colors_module->getColorProps($vars, $options['color']);
            //if($_SERVER['REMOTE_ADDR'] == '109.160.0.218')
            //var_dump($bandbg);die();
            //tpt_dump($bandbg,true);
            $bandbg = self::generatePreview($vars, $bandbg);
				//if(isDump()) {
					//$out = $bandbg;
				//}
            //header('Content-type: image/png');
            //die($bandbg);
            //tpt_dump($bandbg,true);

            $pgDir = $data_module->typeStyle[$options['type']][$options['style']]['preview_folder'];
            $bandoutline = file_get_contents(TPT_IMAGES_DIR.DIRECTORY_SEPARATOR.'preview'.DIRECTORY_SEPARATOR.$pgDir.DIRECTORY_SEPARATOR.'plain.png');

				//if(isDump()) {
				//	$out = $bandoutline;
				//}

            //tpt_dump($bandbgprops,true);
            $options['dualoverlay'] = self::generateEmptyImage($vars);
            if($options['style'] == 7) {
                $dualoverlay = array();

                if($options['type'] == 5) {
                    $dualoverlay['type'] = 'dualslaplayer';
                    $dualoverlay['textColor'] = $options['mcolor'];
                    $dualoverlay['invert_dual'] = $options['invert_dual'];
                    $dualoverlay['pg_x'] = $fullWidth;
                    $dualoverlay['pg_y'] = $pgHeightProcessed;

                    $options['dualoverlay'] = self::generatePreview($vars, $dualoverlay);
                    //tpt_dump($dualoverlay, true);
                    //tpt_dump($options['dualoverlay'], true);
                } else if($bandbgprops['notched']) {
                    //die('fail');
                    $dualoverlay['type'] = 'dualextralayer';
                    $dualoverlay['bandType'] = $options['type'];
                    $dualoverlay['bandStyle'] = $options['style'];
                    $dualoverlay['textColor'] = $options['mcolor'];
                    $dualoverlay['invert_dual'] = $options['invert_dual'];
                    $dualoverlay['pg_x'] = $fullWidth;
                    $dualoverlay['pg_y'] = $pgHeightProcessed;
                    //tpt_dump($dualoverlay, true);
                    $options['del_height'] = $bdata['preview_xlayer_height'];
                    $options['dualoverlay'] = self::generatePreview($vars, $dualoverlay);
                    //$out = $options['dualoverlay'];
                }
            } else if((($options['style'] == 6) && ($options['type'] == 5) && !empty($options['cut_away'])) || ($options['style'] == 8)) {
                $dualoverlay['type'] = 'dualslaplayer';
                $dualoverlay['textColor'] = $options['mcolor'];
                $dualoverlay['invert_dual'] = 0;
                $dualoverlay['pg_x'] = $fullWidth;
                $dualoverlay['pg_y'] = $pgHeightProcessed;

                $options['dualoverlay'] = self::generatePreview($vars, $dualoverlay);

            } else if(($options['style'] == 16)) {
                    //die('fail');
                    $dualoverlay['type'] = 'dualextralayer';
                    $dualoverlay['bandType'] = $options['type'];
                    $dualoverlay['bandStyle'] = $options['style'];
                    $dualoverlay['color'] = $options['color'];
                    $dualoverlay['textColor'] = $options['mcolor'];
                    $dualoverlay['invert_dual'] = $options['invert_dual'];
                    $dualoverlay['pg_x'] = $fullWidth;
                    $dualoverlay['pg_y'] = $pgHeightProcessed;
                    //tpt_dump($dualoverlay, true);
                    //tpt_dump($dualoverlay, true);
                    $options['del_height'] = $bdata['preview_xlayer_height'];
                    $options['dualoverlay'] = self::generatePreview($vars, $dualoverlay);
                    //tpt_dump($options['dualoverlay'], true);
            }
            //tpt_dump($options['dualoverlay'], true);
				//if(isDump()) {
				//	$out = $options['dualoverlay'];
				//}


            //var_dump($left);//die();
            //var_dump($right);//die();
            //var_dump($bandbg);//die();
            //var_dump($bandoutline);die();
            $options['pg_x'] = $pg_x;
            $options['pg_yf'] = $pg_yf;
            $options['pg_yb'] = $pg_yb;
            $options['pg_yp'] = $pg_yp;
            $options['pg_fx'] = $fullWidth;
            $options['pg_fy'] = $fullHeight;
            $options['left'] = $left;
            $options['right'] = $right;
            $options['bg'] = $bandbg;
            $options['outline'] = $bandoutline;
            $options['notched'] = $bandbgprops['notched'];

            //tpt_dump($options, true);


            if(empty($_GET['uncache']))
                $options['cfile'] = $cfile;
            //var_dump($cfile);die();
            //var_dump($out);
            }
            //tpt_dump($options, true);
            break;
        case 'convertcustomart' :
			//tpt_dump('asd', true);
			$options['gClass'] = 'ConvertCustomArt';

			$clipart_module = getModule($vars, 'BandClipart');
			$options['image'] = $data['image'];

			if(!empty($data['image'])) {
				$clipartid = $data['image'];
				$clipartpath = $clipart_module->getCustomClipartPath($vars, $clipartid);
				if(is_file($clipartpath)) {
					$options['image'] = escapeshellarg(str_replace('\\', '\\\\', stripslashes($clipartpath)));
				}
			}

			$options['X'] = intval($data['pg_x'], 10);
			$options['Y'] = intval($data['pg_y'], 10);
			break;
        case 'plain' :
			//die('asd');
            //var_dump();die();
            $colors_module = getModule($vars, 'BandColor');
			$fonts_module = getModule($vars, 'BandFont');
			$fonts = $fonts_module->moduleData['id'];
            $types_module = getModule($vars, 'BandType');
            $data_module = getModule($vars, 'BandData');
            $clipart_module = getModule($vars, 'BandClipart');
            //var_dump($vars);die();

            $options['gClass'] = 'Plain';
            $options['utext'] = $data['utext'];
            $options['text'] = ''.escapeshellarg(str_replace('%', '\\%', str_replace('@', '\\@', str_replace('\\', '\\\\', $data['text'])))).'';
            $options['font'] = $data['font'];
            $options['bandType'] = $data['bandType'];
            $options['bandStyle'] = $data['bandStyle'];
            $options['msgid'] = $data['elmid'];

            $options['bandImagesDir'] = $bandImagesDir;
            $options['linespacing'] = '0';
            $options['pointsize'] = !empty($data['fontSize'])?intval($data['fontSize'], 10):0;

            $options['fullsizeX'] = intval($data['pg_x'], 10);
            $options['fullsizeY'] = intval($data['pg_y'], 10);

                    //var_dump($types_module->moduleData['id'][$options['bandType']]['writable']);//die();

                    //tpt_dump($options['bandStyle']);
                    //tpt_dump($options['bandType'], true);
                    //tpt_dump($options['bandType']);
                    //tpt_dump($data_module->typeStyle[$options['bandType']][$options['bandStyle']]['writable']);
                    //tpt_dump($options['utext'], true);
                    //tpt_dump($options['utext'], true);
                    //tpt_dump($options['bandType'], true);
                    //var_dump($options['utext']);//die();
                    //var_dump($options['msgid'] != 'tpt_pg_back2_message');die();
			//tpt_dump($options, true);
			//tpt_dump()
            if(!empty($options['bandType']) && ($options['utext'] !== '')) {
                //if((($options['bandType'] == 9) || ($options['bandType'] == 10) || ($options['bandType'] == 11) || ($options['bandType'] == 12) || ($options['bandType'] == 13) || ($options['bandType'] == 14)) && ($options['msgid'] != 'tpt_pg_back_message') && ($options['msgid'] != 'tpt_pg_back2_message')) {
                if(!empty($data_module->typeStyle[$options['bandType']][$options['bandStyle']]['writable'])) {
                    if(($data_module->typeStyle[$options['bandType']][$options['bandStyle']]['writable'] == 1) && (($options['msgid'] != 'tpt_pg_back_message') && ($options['msgid'] != 'tpt_pg_back2_message'))) {
                        //die('asdasdasasd');
                        //$wfile = ($options['fullsizeX'] < 400)?'writable-layer-1.png':'writable-layer-2.png';
                        $wfile = 'writable-layer-1.png';
                        if(!empty($data_module->typeStyle[$options['bandType']][$options['bandStyle']]['full_wrap_strip'])) {
                            $wfile = 'writable-layer-2.png';
                            //die('aaa');
                        } else if(empty($data_module->typeStyle[$options['bandType']][$options['bandStyle']]['blank'])) {
                            $wfile = 'writable-layer-3.png';
                            //die('bbb');
                        }
                        //die(TPT_CACHE_DIR.DIRECTORY_SEPARATOR.$options['bandImagesDir'].DIRECTORY_SEPARATOR.$wfile);
                        $out = file_get_contents(TPT_CACHE_DIR.DIRECTORY_SEPARATOR.$options['bandImagesDir'].DIRECTORY_SEPARATOR.$wfile);
                        break;
                    } else if(($data_module->typeStyle[$options['bandType']][$options['bandStyle']]['writable'] == 2)) {
                    //tpt_dump($data_module->typeStyle[$options['bandType']][$options['bandStyle']]['writable_strip_position']);
                    //tpt_dump($options['bandType']);
                    //tpt_dump($options['bandStyle'], true);
                        if(!empty($data_module->typeStyle[$options['bandType']][$options['bandStyle']]['writable_strip_position'])) {
                            if(($data_module->typeStyle[$options['bandType']][$options['bandStyle']]['writable_strip_position'] == 2) && (($options['msgid'] != 'tpt_pg_back_message') && ($options['msgid'] != 'tpt_pg_back2_message'))) {
                                $wfile = 'writable-layer-2.png';
                                //die('aaa');
                                $out = file_get_contents(TPT_CACHE_DIR.DIRECTORY_SEPARATOR.$options['bandImagesDir'].DIRECTORY_SEPARATOR.$wfile);
                                break;
                            } else if(($data_module->typeStyle[$options['bandType']][$options['bandStyle']]['writable_strip_position'] == 1) && (($options['msgid'] != 'tpt_pg_front_message') && ($options['msgid'] != 'tpt_pg_front2_message'))) {
                                $wfile = 'writable-layer-2.png';
                                //die('aaa');
                                $out = file_get_contents(TPT_CACHE_DIR.DIRECTORY_SEPARATOR.$options['bandImagesDir'].DIRECTORY_SEPARATOR.$wfile);
                                break;
                            } else if(($data_module->typeStyle[$options['bandType']][$options['bandStyle']]['writable_strip_position'] == 3) && (($options['msgid'] != 'tpt_pg_back_message') && ($options['msgid'] != 'tpt_pg_back2_message'))) {
                                $wfile = 'writable-layer-3.png';
                                //die('aaa');
                                $out = file_get_contents(TPT_CACHE_DIR.DIRECTORY_SEPARATOR.$options['bandImagesDir'].DIRECTORY_SEPARATOR.$wfile);
                                break;
                            }
                        } else {
                            $wfile = 'writable-layer-1.png';
                            $out = file_get_contents(TPT_CACHE_DIR.DIRECTORY_SEPARATOR.$options['bandImagesDir'].DIRECTORY_SEPARATOR.$wfile);
                            break;
                        }
                    }

                }
            }
//die();
            //var_dump($options['bandStyle']);die();
            if(($options['bandStyle'] != 7) && ($options['bandStyle'] != 8)) {
                if(!empty($data['textColor'])) {
                    /*
                    $colorid = explode(':', $data['textColor']);
                    $colorcat = $colorid[0];
                    $col = false;
                    if($colorcat == -1) {
                        $col = $colorid[1];
                    } else {
                        $colorid = $colorid[1];

                        if($colorcat == 0) {
                            $col = getModule($vars, "BandColor")->by_id[$colorid]['hex'];
                        } else {
                        $color = getModule($vars, "BandColor")->all_colors[$colorcat][$colorid];
                        $colid = $color['color_id'];
                        $col = getModule($vars, "BandColor")->by_id[$colid]['hex'];
                        }
                    }
                    $tc = rgb2hex2rgb($col);
                    $textColor = 'rgb('.$tc['r'].','.$tc['g'].','.$tc['b'].')';
                    $options['textColor'] = $textColor;
                    */
                    $col = $data['textColor'];
                    if(($options['bandStyle'] == 16)) {
                        $col = $data['color'];
                    }
                    $cprops = $colors_module->getColorProps($vars, $col);
                    $opts['type'] = $cprops['colortypename'];//intval($data['pg_x'], 10);
                    $opts['fullsizeX'] = $options['fullsizeX'];//intval($data['pg_x'], 10);
                    $opts['fullsizeY'] = $options['fullsizeY'];//intval($data['pg_y'], 10);
                    $opts['color'] = $col;
                    $opts['pg_x'] = $data['pg_x'];
                    $opts['pg_y'] = $data['pg_y'];
                    //die();
                    //tpt_dump($opts, true);
                    $getPreview = self::generatePreview($vars, $opts);
                    $options['textColor'] = $getPreview;
                    //$out = $getPreview;
                    //tpt_dump($out, true);
                } else {
                    $tc = rgb2hex2rgb(DEFAULT_MESSAGE_COLOR);
                    $textColor = 'rgb('.$tc['r'].','.$tc['g'].','.$tc['b'].')';
                    $options['textColor'] = $textColor;
                }

            } else if(($options['bandStyle'] == 8)) {
$scolor = $data['textColor'];

//tpt_dump($scolor, true);

$colProps = $colors_module->getColorProps($vars, $scolor);
$cols = $colProps['hexarray'];

//var_dump($msgcol);die();
//var_dump($cols);die();




$opts['fullsizeX'] = $options['fullsizeX'];//intval($data['pg_x'], 10);
$opts['fullsizeY'] = $options['fullsizeY'];//intval($data['pg_y'], 10);

    if($colProps['segmented']) {
        $opts['type'] = 'segmented';

    } else if($colProps['swirl']) {
        $opts['type'] = 'swirl';

    } else {
        $opts['type'] = 'solid';

    }

    //var_dump($scolor);die();
    $opts['color'] = $scolor;


//if(!is_array($steps))
    $steps = array();
//if(!is_array($steps['commands']))
    $steps['commands'] = array();
//if(!is_array($steps['errors']))
    $steps['errors'] = array();
//$generator = new self($vars);
//var_dump($opts);die();
$opts['pg_x'] = $data['pg_x'];
$opts['pg_y'] = $data['pg_y'];

//var_dump($colors_module);die();
//var_dump($opts['bandColor']);die();
//tpt_dump($opts);

//header('Content-type: image/png');
//die($getPreview);
//$out = $getPreview;
                //if($_SERVER['REMOTE_ADDR'] == '109.160.0.218') {
                //var_dump($opts);die();
                //}
//var_dump($getPreview);die();


				//$getPreview = self::generatePreview($vars, $opts);
                //$options['textColor'] = $getPreview;
                $options['invert_dual'] = 0;

                $options['bandColor'] = $colors_module->getColorProps($vars, $data['textColor']);
                $options['bandColor'] = rgb2hex2rgb($options['bandColor']['hex']);
                $options['bandColor'] = 'rgb('.$options['bandColor']['r'].','.$options['bandColor']['g'].','.$options['bandColor']['b'].')';
                $options['bandType'] = $data['bandType'];

            } else {
				//tpt_dump($data, true);
                //var_dump($data);die();


                //if(!empty($data['invert_dual'])) {
                //    $scolor = '6:'.getModule($vars, "BandColor")->all_colors[10][$colorid]['message_color_id'];
                //}



                /*
                $col = false;

                $color = getModule($vars, "BandColor")->all_colors[$colorcat][$colorid];
                $bandcolid = $color['color_id'];
                $msgcolid = $color['message_color_id'];
                //var_dump($msgcolid);die();

                $bandcol = getModule($vars, "BandColor")->by_id[$bandcolid];
                $msgcol = getModule($vars, "BandColor")->all_colors[6][$msgcolid];
                $msgcoltype = $msgcol['color_type'];





//var_dump($msgcol);die();
//var_dump($msgcoltype);die();
$opts = array();

$msgcolorcat = $msgcoltype;
$msgcolid = explode(',', $msgcol['color_id']);
$cols = array();
foreach($msgcolid as $cid) {
    $cols[] = getModule($vars, "BandColor")->by_id[$cid]['hex'];
}


$cols = getModule($vars, "BandColor")->getColorProps($vars, '6:'.$msgcol['id']);
$cols = $cols['hexarray'];
*/

//var_dump($colorcat);die();
/*
if($colorcat == -1) {
} else {

    $colorid = explode(':', $scolor);
    $colorcat = $colorid[0];
    $colorid = $colorid[1];

    $color = getModule($vars, "BandColor")->all_colors[$colorcat][$colorid];
    //var_dump($colorcat);//die();
    //var_dump($colorid);//die();
    //var_dump($color['message_color_id']);die();
}
*/

/*
$scolor = $data['textColor'];
$colProps = getModule($vars, "BandColor")->getColorProps($vars, $scolor);
if($colProps['dual_layer']) {
    $scolor = '6:'.getModule($vars, "BandColor")->all_colors[$colProps['tableId']][$colProps['colorId']]['message_color_id'];
}
$invcolor = false;
//var_dump($data['invert_dual']);
//var_dump($colProps['notched']);
//var_dump($options['bandType']);
//die();
if((!empty($data['invert_dual']) xor (!empty($colProps['notched']) && (($options['bandType'] != 2) && ($options['bandType'] != 1))))) {
    //if(!(($options['bandStyle'] == 7) && ($options['bandType'] == 5) && !empty($colProps['notched']))) {
    $scolor = $data['textColor'];
    $invcolor = '6:'.getModule($vars, "BandColor")->all_colors[$colProps['tableId']][$colProps['colorId']]['message_color_id'];

    //var_dump($scolor);
    //var_dump($invcolor);
    //die();

    $invColProps = getModule($vars, "BandColor")->getColorProps($vars, $invcolor);
    $invcols = $invColProps['hexarray'];

    $invcol = false;

    if($invColProps['segmented']) {
        $segmentedColor = array();
        if(!empty($invcols)){
            foreach($invcols as $key=>$cldata) {
                $sc = rgb2hex2rgb($cldata);
                $segmentedColor[$key] = 'rgb('.$sc['r'].','.$sc['g'].','.$sc['b'].')';
            }
        }
        $invcol = array_reverse($segmentedColor);
    } else if($invColProps['swirl']) {
        $swirlColor = array();
        if(!empty($invcols)){
            foreach($invcols as $key=>$cldata) {
                $sc = rgb2hex2rgb($cldata);
                $swirlColor[$key] = 'rgb('.$sc['r'].','.$sc['g'].','.$sc['b'].')';
            }
        }
        $invcol = array_reverse($swirlColor);
    } else {

        $solidColor = array();
        if(!empty($invcols)){
            foreach($invcols as $key=>$cldata) {
                $sc = rgb2hex2rgb($cldata);
                $solidColor[$key] = 'rgb('.$sc['r'].','.$sc['g'].','.$sc['b'].')';
            }
        }
        $invcol = $solidColor;
    }

    $options['invColor'] = $invcol;
    //}
}

//var_dump($scolor);die();

$colProps = getModule($vars, "BandColor")->getColorProps($vars, $scolor);
$cols = $colProps['colordata']['message_hexarray'];

//var_dump($msgcol);die();
tpt_dump($cols, true);




$opts['fullsizeX'] = $options['fullsizeX'];//intval($data['pg_x'], 10);
$opts['fullsizeY'] = $options['fullsizeY'];//intval($data['pg_y'], 10);
//$opts['color'] = '6:'.$msgcol['id'];

    if($colProps['segmented']) {
        $opts['type'] = 'segmented';

        //$segmentedColor = array();
        //if(!empty($cols)){
        //    foreach($cols as $key=>$cldata) {
        //        $sc = rgb2hex2rgb($cldata);
        //        $segmentedColor[$key] = 'rgb('.$sc['r'].','.$sc['g'].','.$sc['b'].')';
        //    }
        //}
        //$options['tColor'] = array_reverse($segmentedColor);

        //if(isset($color['glitter'])){
        //    $options['glitter'] = intval($color['glitter'], 10);
        //}
    } else if($colProps['swirl']) {
        $opts['type'] = 'swirl';

        //$swirlColor = array();
        //if(!empty($cols)){
        //    foreach($cols as $key=>$cldata) {
        //        $sc = rgb2hex2rgb($cldata);
        //        $swirlColor[$key] = 'rgb('.$sc['r'].','.$sc['g'].','.$sc['b'].')';
        //    }
        //}
        //$options['tColor'] = array_reverse($swirlColor);

        //if(isset($color['glitter'])){
        //    $options['glitter'] = intval($color['glitter'], 10);
        //}
    } else {
        $opts['type'] = 'solid';

        //$solidColor = array();
        //if(!empty($cols)){
        //    foreach($cols as $key=>$cldata) {
        //        $sc = rgb2hex2rgb($cldata);
        //        $solidColor[$key] = 'rgb('.$sc['r'].','.$sc['g'].','.$sc['b'].')';
        //    }
        //}
        //$options['tColor'] = $solidColor;
        //var_dump($opts['solidColor']);die();

        //if(isset($color['glitter'])){
        //    $options['glitter'] = intval($color['glitter'], 10);
        //}
    }

    //var_dump($scolor);die();
    $opts['color'] = $scolor;


//if(!is_array($steps))
    $steps = array();
//if(!is_array($steps['commands']))
    $steps['commands'] = array();
//if(!is_array($steps['errors']))
    $steps['errors'] = array();
//$generator = new self($vars);
//var_dump($opts);die();
$opts['pg_x'] = $data['pg_x'];
$opts['pg_y'] = $data['pg_y'];

//var_dump($colors_module);die();
//var_dump($opts['bandColor']);die();
//var_dump($opts);die();
$getPreview = self::generatePreview($vars, $opts);
//header('Content-type: image/png');
//die($getPreview);
//$out = $getPreview;
                //if($_SERVER['REMOTE_ADDR'] == '109.160.0.218') {
                //var_dump($opts);die();
                //}
//var_dump($getPreview);die();


                $options['textColor'] = $getPreview;
                */

                //tpt_dump($getPreview, true);

                $col = $data['textColor'];
                $cprops = $colors_module->getColorProps($vars, $col);
                $opts['type'] = !empty($cprops['colordata']['message_color_type'])?$cprops['colordata']['message_color_type']:'Solid';//intval($data['pg_x'], 10);
                $opts['fullsizeX'] = $options['fullsizeX'];//intval($data['pg_x'], 10);
                $opts['fullsizeY'] = $options['fullsizeY'];//intval($data['pg_y'], 10);
                $opts['color'] = $col;
                $options['invert_dual'] = 0;
                if(!empty($data['invert_dual']) || !empty($cprops['notched'])) {
                    $options['invert_dual'] = 1;
                    $opts['color'] = $cprops['colordata']['band_uid'];
                    $opts['type'] = $cprops['colordata']['band_color_type'];
                    //tpt_dump($cprops, true);
                }
                $opts['pg_x'] = $data['pg_x'];
                $opts['pg_y'] = $data['pg_y'];
                //tpt_dump($data, true);

                //tpt_dump($opts, true);
                $getPreview = null;
                if($cprops['notched']) {
                    $bdid = $data_module->typeStyle[$data['bandType']][$data['bandStyle']];
                    $opts['pg_x'] = $bdid['preview_width'];
                    $opts['pg_y'] = $bdid['preview_height'];
                    $getPreview = self::generatePreview($vars, $opts);
                    //$out = $getPreview;

                    $w = round($bdid['preview_width']/2);
                    $h = round($bdid['preview_height']);
                    $x = 0;
                    $y = 0;
                    if(($data['elmid'] == 'tpt_pg_back_message') || ($data['elmid'] == 'tpt_pg_back2_message')) {
                        $x = $w;
                    }
                    $getPreview = self::crop($vars, $getPreview, $w, $h, $x, $y);
                    //tpt_dump($getPreview, true);
                } else {
                    //tpt_dump($opts, true);
                    $getPreview = self::generatePreview($vars, $opts);
                }
                //tpt_dump($opts, true);
                //tpt_dump($getPreview, true);
                //$out = $getPreview;

                $options['textColor'] = $getPreview;


                $options['cut_away'] = 0;
                if(!empty($data['cut_away']))
                    $options['cut_away'] = 1;

                $options['bandColor'] = rgb2hex2rgb($cprops['hex']);
                $options['bandColor'] = 'rgb('.$options['bandColor']['r'].','.$options['bandColor']['g'].','.$options['bandColor']['b'].')';
                $options['bandType'] = $data['bandType'];
                //var_dump($options['pgType']);die();
            }


			//tpt_dump($data, true);
            if(!empty($data['lclipart'])) {
                $clipartid = intval($data['lclipart'], 10);
                $clipartpath = $clipart_module->getClipartPath($vars, $clipartid);
				//tpt_dump($clipartpath);
                if(is_file($clipartpath)) {
                    $options['lclipart'] = escapeshellarg(str_replace('\\', '\\\\', stripslashes($clipartpath)));
                }
            }
            if(!empty($data['rclipart'])) {
                $clipartid = intval($data['rclipart'], 10);
                $clipartpath = $clipart_module->getClipartPath($vars, $clipartid);
                if(is_file($clipartpath)) {
                    $options['rclipart'] = escapeshellarg(str_replace('\\', '\\\\', stripslashes($clipartpath)));
                }
            }

			if(!empty($data['lclipart_c'])) {
				$clipartid = $data['lclipart_c'];
				$clipartpath = $clipart_module->getCustomClipartPath($vars, $clipartid);
				if(is_file($clipartpath)) {
					$options['lclipart_c'] = escapeshellarg(str_replace('\\', '\\\\', stripslashes($clipartpath)));
				}
			}
			if(!empty($data['rclipart_c'])) {
				$clipartid = $data['rclipart_c'];
				$clipartpath = $clipart_module->getCustomClipartPath($vars, $clipartid);
				if(is_file($clipartpath)) {
					$options['rclipart_c'] = escapeshellarg(str_replace('\\', '\\\\', stripslashes($clipartpath)));
				}
			}

            if(strlen($data['text']) > 20)
                    $emboss = '1';
            else
                    $emboss = '1';
            $botpadfactor = 0;


            $options['X'] = intval($data['pg_x'], 10);
            $options['Y'] = intval($data['pg_y'], 10);

            $options['extrude'] = $emboss;
            $initpad = 0;
            $options['botpad'] = $initpad + $botpadfactor;
            $options['toppad'] = 0;
            $options['fullsizeX'] = intval($data['pg_x'], 10);
            $options['fullsizeY'] = intval($data['pg_y'], 10);

            $options['perspective'] = 20;
            $options['distort'] = '0.5';

            $options['format'] = 'png';

            //if($_SERVER['REMOTE_ADDR'] == '109.160.0.218') {
            //var_dump($message1);
            //die();
            //if(!isset($GLOBALS['somecount']))
            //    $GLOBALS['somecount'] = 0;

            //$GLOBALS['somecount']++;
            //file_put_contents(TPT_RESOURCE_DIR.DIRECTORY_SEPARATOR.'kurec.txt', $GLOBALS['somecount']."\n".$data['utext']."\n"."\n", FILE_APPEND);
            //}

            $default_images = array(DEFAULT_MESSAGE_FRONT, DEFAULT_MESSAGE_FRONT2, DEFAULT_MESSAGE_BACK, DEFAULT_MESSAGE_BACK2);
            //var_dump($default_images);//die();
            //var_dump($data['text']);//die();
			//tpt_dump($data, true);
            if(empty($_GET['debug_uncache']) && !(isDump() && !empty($vars['config']['dev']['debugpreviews_uncache']))) {
            if(in_array($data['utext'], $default_images) && empty($data['lclipart']) && empty($data['rclipart']) && ((($data['bandType'] != 5) && ($data['bandType'] != 1)) || ($data['bandStyle'] != 7)) && empty($data['cut_away'])) {
                $cfile = TPT_IMAGES_DIR.DIRECTORY_SEPARATOR.'preview'.DIRECTORY_SEPARATOR.'cached'.DIRECTORY_SEPARATOR.'plain'.DIRECTORY_SEPARATOR.'plain-'.$options['fullsizeX'].'x'.$options['fullsizeY'].'x'.$options['pointsize'].'x'.$options['linespacing'].'-'.str_replace('/', '_', base64_encode($data['utext'])).'-'.str_replace('/', '_', base64_encode($data['font'])).'-style'.$data['bandStyle'].'-'.str_replace('/', '_', base64_encode($data['color'])).'-'.str_replace('/', '_', base64_encode($data['textColor'])).'.png';
                //var_dump($cfile);die();
                //var_dump(is_file($cfile));die();
//if($_SERVER['REMOTE_ADDR'] == '109.160.0.218') {
//    var_dump(is_file($cfile));//die();
//    var_dump($data['text']);//die();
//    var_dump(str_replace('/', '_', base64_encode($data['text'])));//die();
//    var_dump($cfile);die();
//}
                //if($data['text'] == '////')
                if(is_file($cfile)) {
                    if(!empty($_GET['delcached']) || (isDump() && !empty($vars['config']['dev']['debugpreviews_purge'])) || (!empty($_GET['debug_purge']))) {
                        unlink($cfile);
                    } else {
                    //die('asdasdas');
                    //header('Content-type: image/png');
                    $out = file_get_contents($cfile);
                    }
                }
                $options['cfile'] = $cfile;
                //var_dump($out);
            }
            }
			//tpt_dump($out, true);
            //var_dump($data);//die();
            //tpt_dump($options);
            break;
        case 'dualextralayer' :
            $colors_module = getModule($vars, "BandColor");
            $data_module = getModule($vars, "BandData");

            //tpt_dump($data);
            $type = $data['bandType'];
            $style = $data['bandStyle'];

			if($type != 5) {
				$bdata = $data_module->typeStyle[$type][$style];

				$options['gClass'] = 'XLAYER_HorizStripe';
				$options['height'] = $bdata['preview_xlayer_height'];
				//tpt_dump($bdata);

				//$options['fullsizeX'] = intval($data['pg_x'], 10);
				//$options['fullsizeY'] = intval($data['pg_y'], 10);

				$options['invert_dual'] = 0;
				if (!empty($data['invert_dual']))
					$options['invert_dual'] = 1;

				$color = $data['textColor'];
				//if($style == 16)
				//    $color = $data['color'];


				$cProps = $colors_module->getColorProps($vars, $color);
				if ($style != 16) {
					$options['bandColor'] = reset($cProps['colordata']['message_colors']);
					$options['bandColor'] = rgb2hex2rgb($options['bandColor']['hex']);
				} else {
					$options['bandColor'] = rgb2hex2rgb($cProps['hex']);
				}
				$options['bandColor'] = 'rgb(' . $options['bandColor']['r'] . ',' . $options['bandColor']['g'] . ',' . $options['bandColor']['b'] . ')';
				$options['bandType'] = $data['bandType'];
				$options['bandStyle'] = $data['bandStyle'];

				//$options['X'] = intval($data['pg_x'], 10);
				//$options['Y'] = intval($data['pg_y'], 10);

				//$options['fullsizeX'] = intval($data['pg_x'], 10);
				//$options['fullsizeY'] = intval($data['pg_y'], 10);

				//tpt_dump($options, true);

				$options['format'] = 'png';
			} else {
				//var_dump();die();
				$colors_module = getModule($vars, "BandColor");
				//var_dump($vars);die();

				$options['gClass'] = 'ISPSlapLayer';

				//$options['fullsizeX'] = intval($data['pg_x'], 10);
				//$options['fullsizeY'] = intval($data['pg_y'], 10);

				{
					$colProps = $colors_module->getColorProps($vars, $data['textColor']);
//var_dump($scolor);die();
					if(!empty($data['invert_dual'])) {
						//$scolor = $data['textColor'];
						//$invcolor = $colors_module->getDualLayerMessageId($vars, $scolor);

						//$invColProps = $colors_module->getDualLayerMessageColorProps($vars, $scolor);
						//$invcols = $colProps['colordata']['message_hexarray'];

						//$invcol = false;
						//$invcol = $invcolor;

						$bandColorPreviewParams = $colors_module->getBandColorPreviewParams($vars, $colProps['colordata']['message_uid'], $data['pg_x'], $data['pg_y']);
						//var_dump($invcol);die();
						$bandColor = self::generatePreview($vars, $bandColorPreviewParams);
						$options['invert_dual'] = 1;
						//tpt_dump($invcol,true);


						//$options['invColor'] = $invcol;
					} else {
						//$scolor = $data['textColor'];
						//$invcolor = $scolor;

						//$invColProps = getModule($vars, "BandColor")->getColorProps($vars, $invcolor);
						//$invcols = $invColProps['hexarray'];

						//$invcol = false;
						//$invcol = $invcolor;

						//var_dump($invcol);die();
						$bandColorPreviewParams = $colors_module->getBandColorPreviewParams($vars, $colProps['colordata']['band_uid'], 578, 62, true);
						//var_dump($invcol);die();
						$bandColor = self::generatePreview($vars, $bandColorPreviewParams);


						//$options['invColor'] = $invcol;
						//var_dump($options['invColor']);die();
						//die();
						//header('Content-type: image/png');
						//echo $options['invColor'];
						//die();
					}

//var_dump($options['invColor']);die();
//var_dump($scolor);die();


//var_dump($msgcol);die();
//var_dump($cols);die();



					//$clr = rgb2hex2rgb($colProps['hex']);
					//$options['invert_dual'] = 0;
					//$clr = rgb2hex2rgb($colorProps['colordata']['band_hex']);
					//$clr = rgb2hex2rgb($colProps['colordata']['message_hex']);
					//$options['bandColor'] = 'rgb('.$clr['r'].','.$clr['g'].','.$clr['b'].')';
					$options['bandColor'] = $bandColor;
					//tpt_dump($options['bandColor'],true);


					$options['notched'] = 0;
					if(!empty($colProps['notched']))
						$options['notched'] = 1;



					$options['bandType'] = $data['bandType'];
					$options['bandStyle'] = $data['bandStyle'];
					//var_dump($options['pgType']);die();
				}


				//$options['X'] = intval($data['pg_x'], 10);
				//$options['Y'] = intval($data['pg_y'], 10);

				//$options['fullsizeX'] = intval($data['pg_x'], 10);
				//$options['fullsizeY'] = intval($data['pg_y'], 10);

				$options['format'] = 'png';
			}
            //tpt_dump($options, true);
            break;
        case 'dualquartlayer' :
            //var_dump();die();
            $colors_module = getModule($vars, "BandColor");
            //var_dump($vars);die();

            $options['gClass'] = 'DualQuartLayer';

            $options['fullsizeX'] = intval($data['pg_x'], 10);
            $options['fullsizeY'] = intval($data['pg_y'], 10);

            /*
            {
$msgcolorcat = $msgcoltype;
$msgcolid = explode(',', $msgcol['color_id']);
$cols = array();
foreach($msgcolid as $cid) {
    $cols[] = getModule($vars, "BandColor")->by_id[$cid]['hex'];
}


$cols = getModule($vars, "BandColor")->getColorProps($vars, '6:'.$msgcol['id']);
$cols = $cols['hexarray'];


$scolor = $data['textColor'];
$colProps = getModule($vars, "BandColor")->getColorProps($vars, $scolor);
if($colProps['dual_layer']) {
    $scolor = '6:'.getModule($vars, "BandColor")->all_colors[$colProps['tableId']][$colProps['colorId']]['message_color_id'];
}
$invcolor = false;
if(true || !empty($data['invert_dual']) || (($options['bandStyle'] == 7) && ($options['bandType'] == 1))) {
    $scolor = $data['textColor'];
    $invcolor = '6:'.getModule($vars, "BandColor")->all_colors[$colProps['tableId']][$colProps['colorId']]['message_color_id'];

    //var_dump($scolor);
    //var_dump($invcolor);
    //die();

    $invColProps = getModule($vars, "BandColor")->getColorProps($vars, $invcolor);
    $invcols = $invColProps['hexarray'];

    $invcol = false;

    if($invColProps['segmented']) {
        $segmentedColor = array();
        if(!empty($invcols)){
            foreach($invcols as $key=>$cldata) {
                $sc = rgb2hex2rgb($cldata);
                $segmentedColor[$key] = 'rgb('.$sc['r'].','.$sc['g'].','.$sc['b'].')';
            }
        }
        $invcol = array_reverse($segmentedColor);
    } else if($invColProps['swirl']) {
        $swirlColor = array();
        if(!empty($invcols)){
            foreach($invcols as $key=>$cldata) {
                $sc = rgb2hex2rgb($cldata);
                $swirlColor[$key] = 'rgb('.$sc['r'].','.$sc['g'].','.$sc['b'].')';
            }
        }
        $invcol = array_reverse($swirlColor);
    } else {

        $solidColor = array();
        if(!empty($invcols)){
            foreach($invcols as $key=>$cldata) {
                $sc = rgb2hex2rgb($cldata);
                $solidColor[$key] = 'rgb('.$sc['r'].','.$sc['g'].','.$sc['b'].')';
            }
        }
        $invcol = $solidColor;
    }

    $options['invColor'] = $invcol;
}

//var_dump($scolor);die();

$colProps = getModule($vars, "BandColor")->getColorProps($vars, $scolor);
$cols = $colProps['hexarray'];

//var_dump($msgcol);die();
//var_dump($cols);die();




                $options['invert_dual'] = 0;
                if(!empty($data['invert_dual']))
                    $options['invert_dual'] = 1;

                $options['bandColor'] = $colors_module->getColorProps($vars, $data['textColor']);
                $options['bandColor'] = rgb2hex2rgb($options['bandColor']['hex']);
                $options['bandColor'] = 'rgb('.$options['bandColor']['r'].','.$options['bandColor']['g'].','.$options['bandColor']['b'].')';
                $options['bandType'] = $data['bandType'];
                //var_dump($options['pgType']);die();
            }
            */

                $options['invert_dual'] = 0;
                if(!empty($data['invert_dual']))
                    $options['invert_dual'] = 1;

                $cProps = $colors_module->getColorProps($vars, $data['textColor']);
                $options['bandColor'] = reset($cProps['colordata']['message_colors']);
                $options['bandColor'] = rgb2hex2rgb($options['bandColor']['hex']);
                $options['bandColor'] = 'rgb('.$options['bandColor']['r'].','.$options['bandColor']['g'].','.$options['bandColor']['b'].')';
                $options['bandType'] = $data['bandType'];
                //var_dump($options['pgType']);die();



            $options['X'] = intval($data['pg_x'], 10);
            $options['Y'] = intval($data['pg_y'], 10);

            $options['fullsizeX'] = intval($data['pg_x'], 10);
            $options['fullsizeY'] = intval($data['pg_y'], 10);

            $options['format'] = 'png';
            //var_dump('asdadfghg');//die();
            //var_dump($options['bandColor']);die();

            break;
        case 'dualhalflayer' :
            //var_dump();die();
            $colors_module = getModule($vars, "BandColor");
            //var_dump($vars);die();

            $options['gClass'] = 'DualHalfLayer';

            $options['fullsizeX'] = intval($data['pg_x'], 10);
            $options['fullsizeY'] = intval($data['pg_y'], 10);


            /*
$msgcolorcat = $msgcoltype;
$msgcolid = explode(',', $msgcol['color_id']);
$cols = array();
foreach($msgcolid as $cid) {
    $cols[] = getModule($vars, "BandColor")->by_id[$cid]['hex'];
}



//$colProps = getModule($vars, "BandColor")->getColorProps($vars, '6:'.$msgcol['id']);
//$cols = $colProps['hexarray'];


$scolor = $data['textColor'];
$colProps = getModule($vars, "BandColor")->getColorProps($vars, $scolor);
//if($colProps['dual_layer']) {
//    $scolor = '6:'.getModule($vars, "BandColor")->all_colors[$colProps['tableId']][$colProps['colorId']]['message_color_id'];
//}
$invcolor = false;
if(true || !empty($data['invert_dual']) || (($options['bandStyle'] == 7) && ($options['bandType'] == 2))) {
    //var_dump($data['textColor']);die();
    //$scolor = $data['textColor'];
    //$invcolor = '6:'.getModule($vars, "BandColor")->all_colors[$colProps['tableId']][$colProps['colorId']]['message_color_id'];

    //var_dump($scolor);
    //var_dump($invcolor);
    //die();

    //$invColProps = getModule($vars, "BandColor")->getColorProps($vars, $invcolor);
    //$invcols = $invColProps['hexarray'];
    //var_dump($invcols);die();

    $invcol = false;

    if($invColProps['segmented']) {
        $segmentedColor = array();
        if(!empty($invcols)){
            foreach($invcols as $key=>$cldata) {
                $sc = rgb2hex2rgb($cldata);
                $segmentedColor[$key] = 'rgb('.$sc['r'].','.$sc['g'].','.$sc['b'].')';
            }
        }
        $invcol = array_reverse($segmentedColor);
    } else if($invColProps['swirl']) {
        $swirlColor = array();
        if(!empty($invcols)){
            foreach($invcols as $key=>$cldata) {
                $sc = rgb2hex2rgb($cldata);
                $swirlColor[$key] = 'rgb('.$sc['r'].','.$sc['g'].','.$sc['b'].')';
            }
        }
        $invcol = array_reverse($swirlColor);
    } else {

        $solidColor = array();
        if(!empty($invcols)){
            foreach($invcols as $key=>$cldata) {
                $sc = rgb2hex2rgb($cldata);
                $solidColor[$key] = 'rgb('.$sc['r'].','.$sc['g'].','.$sc['b'].')';
            }
        }
        $invcol = $solidColor;
    }

    $options['invColor'] = $invcol;
}

//var_dump($options['invColor']);die();

//$colProps = getModule($vars, "BandColor")->getColorProps($vars, $data['textColor']);
//$cols = $colProps['hexarray'];

//var_dump($msgcol);die();
//var_dump($cols);die();



            */
                $options['invert_dual'] = 0;
                if(!empty($data['invert_dual']))
                    $options['invert_dual'] = 1;

                $cProps = $colors_module->getColorProps($vars, $data['textColor']);
                $options['bandColor'] = reset($cProps['colordata']['message_colors']);
                $options['bandColor'] = rgb2hex2rgb($options['bandColor']['hex']);
                $options['bandColor'] = 'rgb('.$options['bandColor']['r'].','.$options['bandColor']['g'].','.$options['bandColor']['b'].')';
                $options['bandType'] = $data['bandType'];
                //tpt_dump($options, true);



            $options['X'] = intval($data['pg_x'], 10);
            $options['Y'] = intval($data['pg_y'], 10);

            $options['fullsizeX'] = intval($data['pg_x'], 10);
            $options['fullsizeY'] = intval($data['pg_y'], 10);

            $options['format'] = 'png';

            break;
        case 'dualslaplayer' :
            //var_dump();die();
            $colors_module = getModule($vars, "BandColor");
            //var_dump($vars);die();

            $options['gClass'] = 'DualSlapLayer';

            $options['fullsizeX'] = intval($data['pg_x'], 10);
            $options['fullsizeY'] = intval($data['pg_y'], 10);

            {
$colProps = $colors_module->getColorProps($vars, $data['textColor']);
//var_dump($scolor);die();
if(!empty($data['invert_dual'])) {
    //$scolor = $data['textColor'];
    //$invcolor = $colors_module->getDualLayerMessageId($vars, $scolor);

    //$invColProps = $colors_module->getDualLayerMessageColorProps($vars, $scolor);
    //$invcols = $colProps['colordata']['message_hexarray'];

    //$invcol = false;
    //$invcol = $invcolor;

    $bandColorPreviewParams = $colors_module->getBandColorPreviewParams($vars, $colProps['colordata']['message_uid'], $data['pg_x'], $data['pg_y']);
    //var_dump($invcol);die();
    $bandColor = self::generatePreview($vars, $bandColorPreviewParams);
    $options['invert_dual'] = 1;
    //tpt_dump($invcol,true);


    //$options['invColor'] = $invcol;
} else {
    //$scolor = $data['textColor'];
    //$invcolor = $scolor;

    //$invColProps = getModule($vars, "BandColor")->getColorProps($vars, $invcolor);
    //$invcols = $invColProps['hexarray'];

    //$invcol = false;
    //$invcol = $invcolor;

    //var_dump($invcol);die();
    $bandColorPreviewParams = $colors_module->getBandColorPreviewParams($vars, $colProps['colordata']['band_uid'], $data['pg_x'], $data['pg_y'], true);
    //var_dump($invcol);die();
    $bandColor = self::generatePreview($vars, $bandColorPreviewParams);


    //$options['invColor'] = $invcol;
    //var_dump($options['invColor']);die();
    //die();
    //header('Content-type: image/png');
    //echo $options['invColor'];
    //die();
}

//var_dump($options['invColor']);die();
//var_dump($scolor);die();


//var_dump($msgcol);die();
//var_dump($cols);die();



                //$clr = rgb2hex2rgb($colProps['hex']);
                //$options['invert_dual'] = 0;
                    //$clr = rgb2hex2rgb($colorProps['colordata']['band_hex']);
                //$clr = rgb2hex2rgb($colProps['colordata']['message_hex']);
                //$options['bandColor'] = 'rgb('.$clr['r'].','.$clr['g'].','.$clr['b'].')';
                $options['bandColor'] = $bandColor;
                    //tpt_dump($options['bandColor'],true);


                $options['notched'] = 0;
                if(!empty($colProps['notched']))
                    $options['notched'] = 1;



                $options['bandType'] = $data['bandType'];
                //var_dump($options['pgType']);die();
            }


            $options['X'] = intval($data['pg_x'], 10);
            $options['Y'] = intval($data['pg_y'], 10);

            $options['fullsizeX'] = intval($data['pg_x'], 10);
            $options['fullsizeY'] = intval($data['pg_y'], 10);

            $options['format'] = 'png';

            break;
        case 'solid' :
            //var_dump($data);die();
            $color_module = getModule($vars, "BandColor");

            $colorProps = $color_module->getColorProps($vars, $data['color']);
            //tpt_dump($colorProps,true);

            $options['gClass'] = 'Solid';
            //var_dump($options['glitter']);die();

			//tpt_dump(debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS));
			//tpt_dump(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS));
			//tpt_dump($data['color']);
            $color = (isset($data['color']) && is_string($data['color'])) ? $data['color'] : '0:0';
			$colorid = explode(':', $color);
            if(is_array($colorid)) {
                $colorcat = (isset($colorid[0]) ? $colorid[0] : '');
                $colorid = (isset($colorid[1]) ? $colorid[1] : '');
            }

            $cols = array();
            $color = false;
            if(empty($data['solidColor'])) {

                /*
                if($colorcat != -1) {
                    if(($colorcat == 0) || ($colorcat == 1) || ($colorcat == 2)) {
                    $color = array('color_id'=>$colorid);
                    } else if($colorcat == 10) {
                    $color = getModule($vars, "BandColor")->all_colors[$colorcat][$colorid];
                    //var_dump($color);die();
                    //$color = getModule($vars, "BandColor")->all_colors[0][$color['color_id']];
                    //var_dump($color);die();
                    } else {
                    $color = getModule($vars, "BandColor")->all_colors[$colorcat][$colorid];
                    }
                    $colid = explode(',', $color['color_id']);
                    foreach($colid as $cid) {
                        $cols[] = getModule($vars, "BandColor")->by_id[$cid]['hex'];
                    }
                } else {
                    $cols[] = $colorid;
                }
                */


                $options['glitter'] = $colorProps['glitter'];
                $options['powdercoat'] = $colorProps['powdercoat'];
                $cols = $colorProps['hexarray'];
                if(!empty($colorProps['dual_layer'])) {
                    $cols = $colorProps['colordata']['message_hexarray'];
                    $options['glitter'] = $colorProps['colordata']['message_glitter'];
                    $options['powdercoat'] = $colorProps['colordata']['message_powdercoat'];
                    //tpt_dump($colorProps,true);
                }

                $solidColor = array();
                if(!empty($cols)){
                    foreach($cols as $key=>$cldata) {
                        if($cldata == 'transparent') {
                            $solidColor[$key] = 'transparent';
                        } else {
                            $sc = rgb2hex2rgb($cldata);
                            $solidColor[$key] = 'rgb('.$sc['r'].','.$sc['g'].','.$sc['b'].')';
                        }

                    }
                }
            } else {
                $solidColor = $data['solidColor'];
                $cols = $data['solidColor'];
            }


            $options['solidColor'] = $solidColor;
            //$options['bandColor'] = $data['bandColor'];
            //var_dump($options['solidColor']);die();


            $options['fullsizeX'] = intval($data['pg_x'], 10);
            $options['fullsizeY'] = intval($data['pg_y'], 10);

            $glittersuf = '';
            if(!empty($options['glitter'])) {
                $glittersuf = '--glitter'.$options['glitter'];
            }
            $powdercoatsuf = '';
            if(!empty($options['powdercoat'])) {
                //die();
                $powdercoatsuf = '--powdercoat'.$options['powdercoat'];
            }
            $colidfr = implode('_', $cols);
            $cfile = TPT_IMAGES_DIR.DIRECTORY_SEPARATOR.'preview'.DIRECTORY_SEPARATOR.'cached'.DIRECTORY_SEPARATOR.'solid'.DIRECTORY_SEPARATOR.'solid-'.$options['fullsizeX'].'x'.$options['fullsizeY'].'-'.$colidfr.$glittersuf.$powdercoatsuf.'.png';
            //tpt_dump($colorProps,true);
            //tpt_dump($options,true);
            //var_dump($cfile);die();
            //var_dump($cols);die();
            //$cfile = '';
            if(is_file($cfile)) {
                if(!empty($_GET['delcached'])) {
                    unlink($cfile);
                } else {
                //die('asdasdas');
                //header('Content-type: image/png');
                $out = file_get_contents($cfile);
                }
            }
            $options['cfile'] = $cfile;
            //tpt_dump($options, true);
            //header('Content-type: image/png');
            //die($out);
            break;
        case 'duallayer' :
            $color_module = getModule($vars, "BandColor");
            //$cps = $color_module->getColorProps($vars, $c);
            //var_dump($data['bandType']);die();
            //if($data['dualcolor']) {
            //    $c = '6:'.getModule($vars, "BandColor")->all_colors[$cps['tableId']][$cps['colorId']]['message_color_id'];
            //}

            $colorProps = $color_module->getColorProps($vars, $data['color']);
            //var_dump($c);die();
            //tpt_dump($colorProps, true);
            //var_dump('b');die();
            $options['glitter'] = $colorProps['glitter'];
            $options['powdercoat'] = $colorProps['powdercoat'];
            $options['gClass'] = $colorProps['gClass'];

            $cols = $colorProps['hexarray'];
            if($options['gClass'] == 'Segmented') {
                $cols = array_reverse($cols);
            }
            //tpt_dump($cols, true);
            /*
            if($data['dualcolor']) {
                $cols = array();
                foreach($colorProps['colordata']['message_colors'] as $clr) {
                    $cols[] = $clr['hex'];
                }
                //$cols = reset($colorProps['colordata']['message_colors']);
                //$cols = array($cols['hex']);

            }
            */
            $aColor = array();
            if(!empty($cols)){
                foreach($cols as $key=>$cldata) {
                    $sc = rgb2hex2rgb($cldata);
                    $aColor[$key] = 'rgb('.$sc['r'].','.$sc['g'].','.$sc['b'].')';
                }
            }
            $options[$colorProps['colortypename'].'Color'] = $aColor;
            //$options['bandColor'] = $data['bandColor'];


            $options['fullsizeX'] = intval($data['pg_x'], 10);
            $options['fullsizeY'] = intval($data['pg_y'], 10);


            $glittersuf = '';
            if(!empty($options['glitter'])) {
                $glittersuf = '--glitter'.$options['glitter'];
            }
            $powdercoatsuf = '';
            if(!empty($options['powdercoat'])) {
                $powdercoatsuf = '--powdercoat'.$options['powdercoat'];
            }

            $colidfr = implode('_', $cols);
            //tpt_dump($options, true);

            $cfile = TPT_IMAGES_DIR.DIRECTORY_SEPARATOR.'preview'.DIRECTORY_SEPARATOR.'cached'.DIRECTORY_SEPARATOR.$colorProps['colortypename'].DIRECTORY_SEPARATOR.$colorProps['colortypename'].'-'.$options['fullsizeX'].'x'.$options['fullsizeY'].'-'.$colidfr.$glittersuf.$powdercoatsuf.'.png';
            //tpt_dump($options,true);
            //var_dump($cfile);die();
            if(is_file($cfile)) {
                //tpt_dump($cfile,true);
                if(!empty($_GET['delcached'])) {
                    unlink($cfile);
                } else {
                //die('asdasdas');
                //header('Content-type: image/png');
                $out = file_get_contents($cfile);
                }
            }
            $options['cfile'] = $cfile;

            //die();

            //tpt_dump($options, true);
            break;


        case 'duallayermsgtexture' :
            //var_dump();die();
            $colors_module = getModule($vars, "BandColor");
            //var_dump($vars);die();

            $colorid = explode(':', $data['textColor']);
            $colorcat = $colorid[0];
            $colorid = $colorid[1];
            $col = false;

            $color = getModule($vars, "BandColor")->all_colors[$colorcat][$colorid];
            $colorProps = $colors_module->getColorProps($vars, $data['textColor']);
            $bandcolid = $color['color_id'];
            $msgcolid = $color['message_color_id'];

            $bandcol = getModule($vars, "BandColor")->by_id[$bandcolid];
            $msgcol = getModule($vars, "BandColor")->all_colors[6][$msgcolid];
            $msgcoltype = $msgcol['color_type'];





//var_dump($msgcoltype);die();
$opts = array();

$msgcolorcat = $msgcoltype;
$msgcolid = explode(',', $msgcol['color_id']);
$cols = array();
foreach($msgcolid as $cid) {
    $cols[] = getModule($vars, "BandColor")->by_id[$cid]['hex'];
}



$options['fullsizeX'] = intval($data['pg_x'], 10);
$options['fullsizeY'] = intval($data['pg_y'], 10);
$opts['fullsizeX'] = $options['fullsizeX'];//intval($data['pg_x'], 10);
$opts['fullsizeY'] = $options['fullsizeY'];//intval($data['pg_y'], 10);


if(($msgcolorcat == 2) || ($msgcolorcat == 5)) {
    $opts['type'] = 'segmented';

    $segmentedColor = array();
    if(!empty($cols)){
        foreach($cols as $key=>$cldata) {
            $sc = rgb2hex2rgb($cldata);
            $segmentedColor[$key] = 'rgb('.$sc['r'].','.$sc['g'].','.$sc['b'].')';
        }
    }
    $opts['segmentedColor'] = array_reverse($segmentedColor);
    $opts['bandColor'] = $data['bandColor'];

    if(!empty($colorProps['glitter'])){
        $options['glitter'] = intval($colorProps['glitter'], 10);
    }
    if(!empty($colorProps['powdercoat'])){
        $options['powdercoat'] = intval($colorProps['powdercoat'], 10);
    }
} else if(($msgcolorcat == 1) || ($msgcolorcat == 4)) {
    $opts['type'] = 'swirl';

    $swirlColor = array();
    if(!empty($cols)){
        foreach($cols as $key=>$cldata) {
            $sc = rgb2hex2rgb($cldata);
            $swirlColor[$key] = 'rgb('.$sc['r'].','.$sc['g'].','.$sc['b'].')';
        }
    }
    $opts['swirlColor'] = array_reverse($swirlColor);
    $opts['bandColor'] = $data['bandColor'];

    if(!empty($colorProps['glitter'])){
        $options['glitter'] = intval($colorProps['glitter'], 10);
    }
    if(!empty($colorProps['powdercoat'])){
        $options['powdercoat'] = intval($colorProps['powdercoat'], 10);
    }
} else {
    $opts['type'] = 'solid';

    $solidColor = array();
    if(!empty($cols)){
        foreach($cols as $key=>$cldata) {
            $sc = rgb2hex2rgb($cldata);
            $solidColor[$key] = 'rgb('.$sc['r'].','.$sc['g'].','.$sc['b'].')';
        }
    }
    $opts['solidColor'] = $solidColor;
    $opts['bandColor'] = $data['bandColor'];

    if(!empty($colorProps['glitter'])){
        $options['glitter'] = intval($colorProps['glitter'], 10);
    }
    if(!empty($colorProps['powdercoat'])){
        $options['powdercoat'] = intval($colorProps['powdercoat'], 10);
    }


}


//if(!is_array($steps))
    $steps = array();
//if(!is_array($steps['commands']))
    $steps['commands'] = array();
//if(!is_array($steps['errors']))
    $steps['errors'] = array();
//$generator = new self($vars);
//var_dump($opts);die();
$opts['pg_x'] = $data['pg_x'];
$opts['pg_y'] = $data['pg_y'];
//var_dump($colors_module);die();
$opts['color'] = $colors_module->dualLayerMsgColorIds($vars, $data['textColor']);
//var_dump($opts['color']);die();
$getPreview = self::generatePreview($vars, $opts);
//header('Content-type: image/png');
//die($getPreview);
                //if($_SERVER['REMOTE_ADDR'] == '109.160.0.218') {
                //var_dump($opts);die();
                //}
//var_dump($getPreview);die();


                $options['textColor'] = $getPreview;
                //var_dump($options['textColor']);die();

            $out = $getPreview;

            break;


        case 'swirl' :
            //die('sup');
            $color_module = getModule($vars, "BandColor");
            $colorProps = $color_module->getColorProps($vars, $data['color']);
            //var_dump('c');die();
            $options['glitter'] = $colorProps['glitter'];
            $options['powdercoat'] = $colorProps['powdercoat'];
            $options['gClass'] = 'Swirl';

            $colorid = explode(':', $data['color']);
            $colorcat = $colorid[0];
            $colorid = $colorid[1];

            $cols = array();
            $color = false;
            if(($colorcat == 0) || ($colorcat == 1) || ($colorcat == 2)) {
            $color = array('color_id'=>$colorid);
            } else if($colorcat == 10) {
            $color = getModule($vars, "BandColor")->all_colors[$colorcat][$colorid];
            $color = getModule($vars, "BandColor")->all_colors[6][$color['message_color_id']];
            } else {
            $color = getModule($vars, "BandColor")->all_colors[$colorcat][$colorid];
            }
            $colid = explode(',', $color['color_id']);
            foreach($colid as $cid) {
                $cols[] = getModule($vars, "BandColor")->by_id[$cid]['hex'];
            }

            $swcr = isset($data['sw_color_r'])?intval($data['sw_color_r'], 10):false;
            $swcg = isset($data['sw_color_g'])?intval($data['sw_color_g'], 10):false;
            $swcb = isset($data['sw_color_b'])?intval($data['sw_color_b'], 10):false;

            $swirlColor = array();
            if(!empty($cols)){
                foreach($cols as $key=>$cldata) {
                    $sc = rgb2hex2rgb($cldata);
                    $swirlColor[$key] = 'rgb('.$sc['r'].','.$sc['g'].','.$sc['b'].')';
                }
            }
            //var_dump($swirlColor);die();
            $options['swirlColor'] = $swirlColor;
            $options['bandColor'] = !empty($data['bandColor'])?$data['bandColor']:'';


            $options['fullsizeX'] = intval($data['pg_x'], 10);
            $options['fullsizeY'] = intval($data['pg_y'], 10);
            //var_dump($options);die();

            $glittersuf = '';
            if(!empty($options['glitter'])) {
                $glittersuf = '--glitter'.$options['glitter'];
            }
            $powdercoatsuf = '';
            if(!empty($options['powdercoat'])) {
                $powdercoatsuf = '--powdercoat'.$options['powdercoat'];
            }
            $colidfr = implode('_', $cols);
            $cfile = TPT_IMAGES_DIR.DIRECTORY_SEPARATOR.'preview'.DIRECTORY_SEPARATOR.'cached'.DIRECTORY_SEPARATOR.'swirl'.DIRECTORY_SEPARATOR.'swirl-'.$options['fullsizeX'].'x'.$options['fullsizeY'].'-'.$colidfr.$glittersuf.$powdercoatsuf.'.png';


            if(is_file($cfile)) {
                //var_dump($cfile);die();
                if(!empty($_GET['delcached'])) {
                    unlink($cfile);
                } else {
                //die('asdasdas');
                //header('Content-type: image/png');
                $out = file_get_contents($cfile);
                }
            }

            $options['cfile'] = $cfile;
            break;
        case 'segmented' :
            $color_module = getModule($vars, "BandColor");
            $colorProps = $color_module->getColorProps($vars, $data['color']);
            //tpt_dump($data, true);
            $options['glitter'] = $colorProps['glitter'];
            $options['powdercoat'] = $colorProps['powdercoat'];
            $options['gClass'] = 'Segmented';
            $options['bandType'] = $data['bandType'];

            $colorid = explode(':', $data['color']);
            $colorcat = $colorid[0];
            $colorid = $colorid[1];

            $cols = array();
            if(($colorcat == 0) || ($colorcat == 1) || ($colorcat == 2)) {
            $color = array('color_id'=>$colorid);
            } else if($colorcat == 10) {
            $color = getModule($vars, "BandColor")->all_colors[$colorcat][$colorid];
            //var_dump($color);die();
            $color = getModule($vars, "BandColor")->all_colors[6][$color['message_color_id']];
            //var_dump($color);die();
            } else {
            $color = getModule($vars, "BandColor")->all_colors[$colorcat][$colorid];
            }
            $colid = explode(',', $color['color_id']);
            foreach($colid as $cid) {
                $cols[] = getModule($vars, "BandColor")->by_id[$cid]['hex'];
            }

            $swcr = isset($data['sw_color_r'])?intval($data['sw_color_r'], 10):false;
            $swcg = isset($data['sw_color_g'])?intval($data['sw_color_g'], 10):false;
            $swcb = isset($data['sw_color_b'])?intval($data['sw_color_b'], 10):false;

            $segmentedColor = array();
            if(!empty($cols)){
                foreach($cols as $key=>$cldata) {
                    $sc = rgb2hex2rgb($cldata);
                    $segmentedColor[$key] = 'rgb('.$sc['r'].','.$sc['g'].','.$sc['b'].')';
                }
            }
            $options['segmentedColor'] = array_reverse($segmentedColor);
            //var_dump($colid);die();
            //var_dump($options['segmentedColor']);die();
            $options['bandColor'] = !empty($data['bandColor'])?$data['bandColor']:'';


            $options['fullsizeX'] = intval($data['pg_x'], 10);
            $options['fullsizeY'] = intval($data['pg_y'], 10);

            $glittersuf = '';
            if(!empty($options['glitter'])) {
                $glittersuf = '--glitter'.$options['glitter'];
            }
            $powdercoatsuf = '';
            if(!empty($options['powdercoat'])) {
                $powdercoatsuf = '--powdercoat'.$options['powdercoat'];
            }
            $colidfr = implode('_', $cols);
            $cfile = TPT_IMAGES_DIR.DIRECTORY_SEPARATOR.'preview'.DIRECTORY_SEPARATOR.'cached'.DIRECTORY_SEPARATOR.'segmented'.DIRECTORY_SEPARATOR.'segmented-type'.$options['bandType'].'-'.$options['fullsizeX'].'x'.$options['fullsizeY'].'-'.$colidfr.$glittersuf.$powdercoatsuf.'.png';
            //tpt_dump($cfile, true);
            if(is_file($cfile)) {
                //tpt_dump($cfile, true);
                if(!empty($_GET['delcached'])) {
                    unlink($cfile);
                } else {
                //die('asdasdas');
                //header('Content-type: image/png');
                $out = file_get_contents($cfile);
                }
            }
            $options['cfile'] = $cfile;
            break;
        case 'coloroption' :
            $colors_module = getModule($vars, "BandColor");

            $opts = array();
            $cox = 47;
            $coy = 47;

            $colorid = explode(':', $data['color']);
            $colorcat = $colorid[0];
            $colorid = $colorid[1];

            $cols = array();
            if(($colorcat == 0) || ($colorcat == 1) || ($colorcat == 2)) {
                $color = array('color_id'=>$colorid);
            } else if($colorcat == 10){
                $color = array('color_id'=>getModule($vars, "BandColor")->all_colors[10][$colorid]['color_id']);
            } else {
                $color = getModule($vars, "BandColor")->all_colors[$colorcat][$colorid];
            }
            $colorProps = $colors_module->getColorProps($vars, $data['color']);
            $colid = explode(',', $color['color_id']);
            foreach($colid as $cid) {
                $cols[] = getModule($vars, "BandColor")->by_id[$cid]['hex'];
            }


            $opts['fullsizeX'] = $cox;//intval($data['pg_x'], 10);
            $opts['fullsizeY'] = $coy;//intval($data['pg_y'], 10);


            if(($colorcat == 2) || ($colorcat == 5)) {
                $opts['type'] = 'segmented';

                $segmentedColor = array();
                if(!empty($cols)){
                    foreach($cols as $key=>$cldata) {
                        $sc = rgb2hex2rgb($cldata);
                        $segmentedColor[$key] = 'rgb('.$sc['r'].','.$sc['g'].','.$sc['b'].')';
                    }
                }
                $opts['segmentedColor'] = array_reverse($segmentedColor);
                $opts['bandColor'] = $data['bandColor'];

                if(!empty($colorProps['glitter'])){
                    $options['glitter'] = intval($colorProps['glitter'], 10);
                }
                if(!empty($colorProps['powdercoat'])){
                    $options['powdercoat'] = intval($colorProps['powdercoat'], 10);
                }
            } else if(($colorcat == 1) || ($colorcat == 4)) {
                $opts['type'] = 'swirl';

                $swirlColor = array();
                if(!empty($cols)){
                    foreach($cols as $key=>$cldata) {
                        $sc = rgb2hex2rgb($cldata);
                        $swirlColor[$key] = 'rgb('.$sc['r'].','.$sc['g'].','.$sc['b'].')';
                    }
                }
                $opts['swirlColor'] = array_reverse($swirlColor);
                $opts['bandColor'] = $data['bandColor'];

                if(!empty($colorProps['glitter'])){
                    $options['glitter'] = intval($colorProps['glitter'], 10);
                }
                if(!empty($colorProps['powdercoat'])){
                    $options['powdercoat'] = intval($colorProps['powdercoat'], 10);
                }
            } else if($colorcat == 10) {
                $opts['type'] = 'duallayer';

                $solidColor = array();
                if(!empty($cols)){
                    foreach($cols as $key=>$cldata) {
                        $sc = rgb2hex2rgb($cldata);
                        $solidColor[$key] = 'rgb('.$sc['r'].','.$sc['g'].','.$sc['b'].')';
                    }
                }
                $opts['solidColor'] = $solidColor;
                $opts['bandColor'] = $data['bandColor'];

                if(!empty($colorProps['glitter'])){
                    $options['glitter'] = intval($colorProps['glitter'], 10);
                }
                if(!empty($colorProps['powdercoat'])){
                    $options['powdercoat'] = intval($colorProps['powdercoat'], 10);
                }
            } else {
                $opts['type'] = 'solid';

                $solidColor = array();
                if(!empty($cols)){
                    foreach($cols as $key=>$cldata) {
                        $sc = rgb2hex2rgb($cldata);
                        $solidColor[$key] = 'rgb('.$sc['r'].','.$sc['g'].','.$sc['b'].')';
                    }
                }
                $opts['solidColor'] = $solidColor;
                $opts['bandColor'] = $data['bandColor'];

                if(!empty($colorProps['glitter'])){
                    $options['glitter'] = intval($colorProps['glitter'], 10);
                }
                if(!empty($colorProps['powdercoat'])){
                    $options['powdercoat'] = intval($colorProps['powdercoat'], 10);
                }
            }


            $steps = array();
            $steps['commands'] = array();
            $steps['errors'] = array();
            //$generator = new tpt_PreviewGenerator($vars);
            $opts['pg_x'] = $cox;
            $opts['pg_y'] = $coy;
            $opts['color'] = $data['color'];
            $getPreview = self::generatePreview($vars, $opts);


            $options['gClass'] = 'ColorOption';
            $options['texture'] = $getPreview;

            if($colorcat == 10) {
                $opts = array();
                $opts['font'] = DEFAULT_FONT_NAME;
                $opts['pg_x'] = $cox;
                $opts['pg_y'] = $coy;
                $opts['type'] = 'plain';
                $opts['bandType'] = 2;
                $opts['bandStyle'] = 7;
                $opts['text'] = 'ABC';
                $opts['textColor'] = $data['color'];

                $getPreview = self::generatePreview($vars, $opts);

                $options['texture'] = self::compose($vars, array($options['texture'], $getPreview));
            }

            //header('Content-type: image/png');
            //echo($options['texture']);die();


            $cfile = TPT_IMAGES_DIR.DIRECTORY_SEPARATOR.'preview'.DIRECTORY_SEPARATOR.'cached'.DIRECTORY_SEPARATOR.'coloroption'.DIRECTORY_SEPARATOR.'coloroption-'.$cox.'x'.$coy.'-'.$colorcat.'_'.$colorid.'.png';
            //$cfile = '';
            if(is_file($cfile)) {
                //header('Content-type: image/png');
                $out = file_get_contents($cfile);
            }
            $options['cfile'] = $cfile;

            break;
		case 'version' :
			tpt_dump(self::getImageMagickVersion($vars), true);
			break;
        default :
        		$vars['config']['logger']['db_rq_log'] = false;
            $options['gClass'] = 'Simple';
            $X = intval($data['pg_x'], 10);
            $Y = intval($data['pg_y'], 10);
            $options['X'] = !empty($X)?$X.'':'201';
            $options['Y'] = !empty($Y)?$Y.'':'27';
            $options['text'] = ''.escapeshellarg(str_replace('\\', '\\\\', $data['text'])).'';;
            $options['font'] = $data['font'];
            $options['linespacing'] = '0';
            $options['vpad'] = 5;
            $options['hpad'] = 5;

            $filename = explode('.', preg_replace('#[\'"\(\)]+#', '', $options['font']));
            if(count($filename) > 1) {
                array_pop($filename);
            }
            $filename = implode('.', $filename).'.png';
            $cfile = TPT_IMAGES_DIR.DIRECTORY_SEPARATOR.'preview'.DIRECTORY_SEPARATOR.'cached'.DIRECTORY_SEPARATOR.'simple'.DIRECTORY_SEPARATOR.'simple-'.base64_encode($options['text']).'-=-'.$options['X'].'x'.$options['Y'].'-hpad'.$options['hpad'].'-vpad'.$options['vpad'].'-'.$filename;
            /*
            if(is_file($cfile)) {
                //header('Content-type: image/png');
                $out = file_get_contents($cfile);
            }
            */
                if(is_file($cfile)) {
                    if(!empty($_GET['delcached']) || (isDump() && !empty($vars['config']['dev']['debugpreviews_purge'])) || (!empty($_GET['debug_purge']))) {
                        unlink($cfile);
                    } else {
                    //die('asdasdas');
                    //header('Content-type: image/png');
                    $out = file_get_contents($cfile);
                    }
                }
            $options['cfile'] = $cfile;
            break;
    }
}


        /*
        $out = '';
        if(!class_exists('tpt_gclass_'.$this->options['gClass'])) {
            $gClassFile = self::$gClassesDir.DIRECTORY_SEPARATOR.$this->options['gClass'].'.php';
            $gClass = 'tpt_gclass_'.$this->options['gClass'];
            if(is_file($gClassFile)) {
                include($gClassFile);
                if(class_exists($gClass)) {
                    if($gInstance = new $gClass()) {
                        $out = $gInstance->generate($vars, $this->options, $steps);
                    }
                }
            }
        }
        */
        //tpt_dump($out, true);

		$steps = array();
		$steps['commands'] = array();
		$steps['errors'] = array();
        if(empty($out)) {
            $gClass = 'tpt_gclass_'.$options['gClass'];
            if(!class_exists('tpt_gclass_'.$options['gClass'])) {
                $gClassFile = self::$gClassesDir.DIRECTORY_SEPARATOR.$options['gClass'].'.php';
                if(is_file($gClassFile)) {
                    include($gClassFile);
                    if(class_exists($gClass)) {
                        if($gInstance = new $gClass()) {
                            //if(!is_array($steps))

                            //if(!is_array($steps['commands']))

                            //if(!is_array($steps['errors']))

                            $out = $gInstance->generate($vars, $options, $steps);
                        }
                    }
                }
            } else {
                if($gInstance = new $gClass()) {
                    //if(!is_array($steps))
                        $steps = array();
                    //if(!is_array($steps['commands']))
                        $steps['commands'] = array();
                    //if(!is_array($steps['errors']))
                        $steps['errors'] = array();
                    $out = $gInstance->generate($vars, $options, $steps);
                }
            }
        }
		if(!empty($_GET['debug_php']) || (isDump() && !empty($vars['config']['dev']['debugpreviews_php']))) {
			tpt_dump($steps);
		}


        if(empty($out)) {
            $out = self::emptyImage($vars);
        }

        /*
        if(true || empty($getPreview['errors'])) {
            //echo $out;
        } else {
            //var_dump($getPreview['errors']);
        }
        */

        return $out;
    }



    static function generatePreviewVector(&$vars/*, &$steps=array()*/, $data=array()) {
//var_dump($data);//die();
//var_dump($data['type']);die();
        $data_module = getModule($vars, "BandData");
		$fonts_module = getModule($vars, "BandFont");
		$fonts = $fonts_module->moduleData['id'];

$out = '';

$data['utext']  = !empty($data['text'])?$data['text']:null;
$data['text']  = isset( $data['text'] ) ? $data['text'] : '' ;
$data['font']  = (!empty($fonts[$data['font']]) && is_file(FONTS_PATH.DIRECTORY_SEPARATOR.$fonts[$data['font']]['file']) ) ? $fonts[$data['font']]['file'] : DEFAULT_FONT_NAME ;
$data['bandType']  = (isset($data['bandType']) ) ? $data['bandType'] : DEFAULT_TYPE ;
$data['bandStyle']  = (isset($data['bandStyle']) ) ? $data['bandStyle'] : DEFAULT_STYLE ;
$bandImagesDir = $data_module->typeStyle[$data['bandType']][$data['bandStyle']]['preview_folder'];


//var_dump($fontName);die();

$options = array();

/*
$options = array(
    'gClass'=>'Simple',
    'format'=>'jpg'
);
if($data['format']) {
    preg_match('#png#i', $data['format'], $mtch);
    if(!empty($mtch)) {
        $options['format'] = 'png';
    }
}
*/
//tpt_dump($data['type'], true);

if(empty($data['type'])) {
    $options['gClass'] = 'Simple';
    $options['X'] = '201';
    $options['Y'] = '27';
    $options['text'] = ''.escapeshellarg(str_replace('\\', '\\\\', $data['text'])).'';
    //var_dump($options['text']);die();
    $options['font'] = $data['font'];
    $options['bandType'] = $data['bandType'];
    $options['bandStyle'] = $data['bandStyle'];
    $options['linespacing'] = '0';
} else {
    switch(strtolower(trim($data['type']))) {
        case 'bandlayer' :
            $types_module = getModule($vars, "BandType");
            $data_module = getModule($vars, "BandData");
            $colors_module = getModule($vars, "BandColor");
            $pfields_module = getModule($vars, "CustomProductField");
            $layers_module = getModule($vars, "BandPreviewLayer");

            $lr = $data['layer'];
            $layer = $layers_module->moduleData['param'][$lr];
            $bdata = $data_module->typeStyle[$data['pgType']][$data['pgStyle']];

            $options = $data;
            $options['gClass'] = $layer['gClass'];
            if(!empty($layer['bg_layer'])) {
                $cprops = $colors_module->getColorProps($vars, $data[$pfields_module->moduleData['name']['color']['preview_name']]);
                //tpt_dump($cprops, true);
                $options['gClass'] = $cprops['gClass'];
                $options['fullsizeX'] = $bdata['preview_width'];
                $options['fullsizeY'] = $bdata['preview_height'];
                $cols = array();
                $color = false;
                $ccat = $cprops['colortypename'];

                if(empty($data[$ccat.'Color'])) {
                    $options['glitter'] = $cprops['glitter'];
                    $options['powdercoat'] = $cprops['powdercoat'];
                    $cols = $cprops['hexarray'];
                    if(!empty($cprops['dual_layer'])) {
                        $cols = $cprops['colordata']['message_hexarray'];
                        $options['glitter'] = $cprops['colordata']['message_glitter'];
                        $options['powdercoat'] = $cprops['colordata']['message_powdercoat'];
                        //tpt_dump($colorProps,true);
                    }

                    $ccolor = array();
                    if(!empty($cols)){
                        foreach($cols as $key=>$cldata) {
                            if($cldata == 'transparent') {
                                $ccolor[$key] = 'transparent';
                            } else {
                                $sc = rgb2hex2rgb($cldata);
                                $ccolor[$key] = 'rgb('.$sc['r'].','.$sc['g'].','.$sc['b'].')';
                            }

                        }
                    }
                }


                $options[$ccat.'Color'] = $ccolor;
            }
            //tpt_dump($options, true);
            break;
        case 'svgtext' :
            include(PHPSVG_PATH.DIRECTORY_SEPARATOR.PHPSVG_LIB_FILE);


            $font = normalize_filename($data['font'], 'ttf');

            //var_dump($data['font']);//die();
            //var_dump($font);die();

            $svgfont = self::ttf2svg($vars, $font);
            $fontname = explode('.', $font);
            array_pop($fontname);
            $fontname = implode('.', $fontname);
            //var_dump($svgfont);die();

            $svg = SVGDocument::getInstance( $svgfont['filename'] ); //open to edit
            $fontdef = $svg->getElementById( 'svgfont' ); //open to edit
            $fontdef = $fontdef->getElementById( 'svgfont' ); //open to edit
            foreach ( $fontdef->children() as $line => $child ) {
                $fontname = $child->getAttribute('font-family');
                break;
            }
            //$svg = SVGDocument::getInstance( ); //default read to use
            //$rect = #create a new rect with, x and y position, id, width and heigth, and the style
            //$rect = SVGRect::getInstance( 0, 5, 'myRect', 228, 185, new SVGStyle( array( 'fill' => 'red', 'stroke' => 'blue' ) ) );
            //$svg->addShape( $rect );
            $text = SVGText::getInstance( 22, 50, 'tpt_message', $data['utext'], 'font-family: '.$fontname.',sans-serif; font-size:25' );
            $svg->addShape( $text );

            $out = $svg->asXML(); //get data
            //$svg->asXML(SVG_FONTS_PATH.DIRECTORY_SEPARATOR.'Aclonica_phpsvg.svg'); //output to svg file

            break;
        case 'plain' :

            //var_dump();die();
            $colors_module = getModule($vars, "BandColor");
            $types_module = getModule($vars, "BandType");
            $data_module = getModule($vars, "BandData");
            //tpt_dump($data, true);

            $options['gClass'] = 'Plain';
            $options['utext'] = $data['utext'];
            $options['text'] = ''.escapeshellarg(str_replace('%', '\\%', str_replace('@', '\\@', str_replace('\\', '\\\\', $data['text'])))).'';
            $options['font'] = $data['font'];
            $options['bandType'] = $data['bandType'];
            $options['bandStyle'] = $data['bandStyle'];
            $options['msgid'] = $data['elmid'];

            $options['bandImagesDir'] = $bandImagesDir;
            $options['linespacing'] = '0';
            $options['pointsize'] = !empty($data['fontSize'])?intval($data['fontSize'], 10):0;

            $options['fullsizeX'] = intval($data['pg_x'], 10);
            $options['fullsizeY'] = intval($data['pg_y'], 10);

                    //var_dump($types_module->moduleData['id'][$options['bandType']]['writable']);//die();

                    //tpt_dump($options['bandStyle']);
                    //tpt_dump($options['bandStyle']);
                    //tpt_dump($options['utext'], true);
                    //tpt_dump($options['bandType'], true);
                    //var_dump($options['utext']);//die();
                    //var_dump($options['msgid'] != 'tpt_pg_back2_message');die();
            if(!empty($options['bandType']) && ($options['utext'] !== '') && !is_null($options['utext'])) {
                //if((($options['bandType'] == 9) || ($options['bandType'] == 10) || ($options['bandType'] == 11) || ($options['bandType'] == 12) || ($options['bandType'] == 13) || ($options['bandType'] == 14)) && ($options['msgid'] != 'tpt_pg_back_message') && ($options['msgid'] != 'tpt_pg_back2_message')) {
                if(!empty($data_module->typeStyle[$options['bandType']][$options['bandStyle']]['writable'])) {
                    if(($data_module->typeStyle[$options['bandType']][$options['bandStyle']]['writable'] == 1) && (($options['msgid'] != 'tpt_pg_back_message') && ($options['msgid'] != 'tpt_pg_back2_message'))) {
                        //die('asdasdasasd');
                        //$wfile = ($options['fullsizeX'] < 400)?'writable-layer-1.png':'writable-layer-2.png';
                        $wfile = 'writable-layer-1.png';
                        if(!empty($data_module->typeStyle[$options['bandType']][$options['bandStyle']]['full_wrap_strip'])) {
                            $wfile = 'writable-layer-2.png';
                            //die('aaa');
                        } else if(empty($data_module->typeStyle[$options['bandType']][$options['bandStyle']]['blank'])) {
                            $wfile = 'writable-layer-3.png';
                            //die('bbb');
                        }
                        //die(TPT_CACHE_DIR.DIRECTORY_SEPARATOR.$options['bandImagesDir'].DIRECTORY_SEPARATOR.$wfile);
                        $out = file_get_contents(TPT_CACHE_DIR.DIRECTORY_SEPARATOR.$options['bandImagesDir'].DIRECTORY_SEPARATOR.$wfile);
                        break;
                    } else if(($data_module->typeStyle[$options['bandType']][$options['bandStyle']]['writable'] == 2)) {
                    //tpt_dump($data_module->typeStyle[$options['bandType']][$options['bandStyle']]['writable_strip_position']);
                    //tpt_dump($options['bandType']);
                    //tpt_dump($options['bandStyle'], true);
                        if(!empty($data_module->typeStyle[$options['bandType']][$options['bandStyle']]['writable_strip_position'])) {
                            if(($data_module->typeStyle[$options['bandType']][$options['bandStyle']]['writable_strip_position'] == 2) && (($options['msgid'] != 'tpt_pg_back_message') && ($options['msgid'] != 'tpt_pg_back2_message'))) {
                                $wfile = 'writable-layer-2.png';
                                //die('aaa');
                                $out = file_get_contents(TPT_CACHE_DIR.DIRECTORY_SEPARATOR.$options['bandImagesDir'].DIRECTORY_SEPARATOR.$wfile);
                                break;
                            } else if(($data_module->typeStyle[$options['bandType']][$options['bandStyle']]['writable_strip_position'] == 1) && (($options['msgid'] != 'tpt_pg_front_message') && ($options['msgid'] != 'tpt_pg_front2_message'))) {
                                $wfile = 'writable-layer-2.png';
                                //die('aaa');
                                $out = file_get_contents(TPT_CACHE_DIR.DIRECTORY_SEPARATOR.$options['bandImagesDir'].DIRECTORY_SEPARATOR.$wfile);
                                break;
                            } else if(($data_module->typeStyle[$options['bandType']][$options['bandStyle']]['writable_strip_position'] == 3) && (($options['msgid'] != 'tpt_pg_back_message') && ($options['msgid'] != 'tpt_pg_back2_message'))) {
                                $wfile = 'writable-layer-3.png';
                                //die('aaa');
                                $out = file_get_contents(TPT_CACHE_DIR.DIRECTORY_SEPARATOR.$options['bandImagesDir'].DIRECTORY_SEPARATOR.$wfile);
                                break;
                            }
                        } else {
                            $wfile = 'writable-layer-1.png';
                            $out = file_get_contents(TPT_CACHE_DIR.DIRECTORY_SEPARATOR.$options['bandImagesDir'].DIRECTORY_SEPARATOR.$wfile);
                            break;
                        }
                    }

                }
            }
//die();
            //var_dump($options['bandStyle']);die();
            if(($options['bandStyle'] != 7) && ($options['bandStyle'] != 8)) {
                if(!empty($data['textColor'])) {
                    /*
                    $colorid = explode(':', $data['textColor']);
                    $colorcat = $colorid[0];
                    $col = false;
                    if($colorcat == -1) {
                        $col = $colorid[1];
                    } else {
                        $colorid = $colorid[1];

                        if($colorcat == 0) {
                            $col = getModule($vars, "BandColor")->by_id[$colorid]['hex'];
                        } else {
                        $color = getModule($vars, "BandColor")->all_colors[$colorcat][$colorid];
                        $colid = $color['color_id'];
                        $col = getModule($vars, "BandColor")->by_id[$colid]['hex'];
                        }
                    }
                    $tc = rgb2hex2rgb($col);
                    $textColor = 'rgb('.$tc['r'].','.$tc['g'].','.$tc['b'].')';
                    $options['textColor'] = $textColor;
                    */
                    $cprops = $colors_module->getColorProps($vars, $data['textColor']);
                    $opts['type'] = $cprops['colortypename'];//intval($data['pg_x'], 10);
                    $opts['fullsizeX'] = $options['fullsizeX'];//intval($data['pg_x'], 10);
                    $opts['fullsizeY'] = $options['fullsizeY'];//intval($data['pg_y'], 10);
                    $opts['color'] = $data['textColor'];
                    $opts['pg_x'] = $data['pg_x'];
                    $opts['pg_y'] = $data['pg_y'];
                    //die();
                    //tpt_dump($opts, true);
                    $getPreview = self::generatePreview($vars, $opts);
                    $options['textColor'] = $getPreview;
                    //$out = $getPreview;
                } else {
                    $tc = rgb2hex2rgb(DEFAULT_MESSAGE_COLOR);
                    $textColor = 'rgb('.$tc['r'].','.$tc['g'].','.$tc['b'].')';
                    $options['textColor'] = $textColor;
                }

            } else if(($options['bandStyle'] == 8)) {
$scolor = $data['textColor'];

//var_dump($scolor);die();

$colProps = getModule($vars, "BandColor")->getColorProps($vars, $scolor);
$cols = $colProps['hexarray'];

//var_dump($msgcol);die();
//var_dump($cols);die();




$opts['fullsizeX'] = $options['fullsizeX'];//intval($data['pg_x'], 10);
$opts['fullsizeY'] = $options['fullsizeY'];//intval($data['pg_y'], 10);

    if($colProps['segmented']) {
        $opts['type'] = 'segmented';

    } else if($colProps['swirl']) {
        $opts['type'] = 'swirl';

    } else {
        $opts['type'] = 'solid';

    }

    //var_dump($scolor);die();
    $opts['color'] = $scolor;


//if(!is_array($steps))
    $steps = array();
//if(!is_array($steps['commands']))
    $steps['commands'] = array();
//if(!is_array($steps['errors']))
    $steps['errors'] = array();
//$generator = new self($vars);
//var_dump($opts);die();
$opts['pg_x'] = $data['pg_x'];
$opts['pg_y'] = $data['pg_y'];

//var_dump($colors_module);die();
//var_dump($opts['bandColor']);die();
//var_dump($opts);die();
$getPreview = self::generatePreview($vars, $opts);
//header('Content-type: image/png');
//die($getPreview);
//$out = $getPreview;
                //if($_SERVER['REMOTE_ADDR'] == '109.160.0.218') {
                //var_dump($opts);die();
                //}
//var_dump($getPreview);die();


                $options['textColor'] = $getPreview;
                $options['invert_dual'] = 0;

                $options['bandColor'] = $colors_module->getColorProps($vars, $data['textColor']);
                $options['bandColor'] = rgb2hex2rgb($options['bandColor']['hex']);
                $options['bandColor'] = 'rgb('.$options['bandColor']['r'].','.$options['bandColor']['g'].','.$options['bandColor']['b'].')';
                $options['bandType'] = $data['bandType'];

            } else {
                //var_dump($data);die();


                //if(!empty($data['invert_dual'])) {
                //    $scolor = '6:'.getModule($vars, "BandColor")->all_colors[10][$colorid]['message_color_id'];
                //}



                /*
                $col = false;

                $color = getModule($vars, "BandColor")->all_colors[$colorcat][$colorid];
                $bandcolid = $color['color_id'];
                $msgcolid = $color['message_color_id'];
                //var_dump($msgcolid);die();

                $bandcol = getModule($vars, "BandColor")->by_id[$bandcolid];
                $msgcol = getModule($vars, "BandColor")->all_colors[6][$msgcolid];
                $msgcoltype = $msgcol['color_type'];





//var_dump($msgcol);die();
//var_dump($msgcoltype);die();
$opts = array();

$msgcolorcat = $msgcoltype;
$msgcolid = explode(',', $msgcol['color_id']);
$cols = array();
foreach($msgcolid as $cid) {
    $cols[] = getModule($vars, "BandColor")->by_id[$cid]['hex'];
}


$cols = getModule($vars, "BandColor")->getColorProps($vars, '6:'.$msgcol['id']);
$cols = $cols['hexarray'];
*/

//var_dump($colorcat);die();
/*
if($colorcat == -1) {
} else {

    $colorid = explode(':', $scolor);
    $colorcat = $colorid[0];
    $colorid = $colorid[1];

    $color = getModule($vars, "BandColor")->all_colors[$colorcat][$colorid];
    //var_dump($colorcat);//die();
    //var_dump($colorid);//die();
    //var_dump($color['message_color_id']);die();
}
*/

/*
$scolor = $data['textColor'];
$colProps = getModule($vars, "BandColor")->getColorProps($vars, $scolor);
if($colProps['dual_layer']) {
    $scolor = '6:'.getModule($vars, "BandColor")->all_colors[$colProps['tableId']][$colProps['colorId']]['message_color_id'];
}
$invcolor = false;
//var_dump($data['invert_dual']);
//var_dump($colProps['notched']);
//var_dump($options['bandType']);
//die();
if((!empty($data['invert_dual']) xor (!empty($colProps['notched']) && (($options['bandType'] != 2) && ($options['bandType'] != 1))))) {
    //if(!(($options['bandStyle'] == 7) && ($options['bandType'] == 5) && !empty($colProps['notched']))) {
    $scolor = $data['textColor'];
    $invcolor = '6:'.getModule($vars, "BandColor")->all_colors[$colProps['tableId']][$colProps['colorId']]['message_color_id'];

    //var_dump($scolor);
    //var_dump($invcolor);
    //die();

    $invColProps = getModule($vars, "BandColor")->getColorProps($vars, $invcolor);
    $invcols = $invColProps['hexarray'];

    $invcol = false;

    if($invColProps['segmented']) {
        $segmentedColor = array();
        if(!empty($invcols)){
            foreach($invcols as $key=>$cldata) {
                $sc = rgb2hex2rgb($cldata);
                $segmentedColor[$key] = 'rgb('.$sc['r'].','.$sc['g'].','.$sc['b'].')';
            }
        }
        $invcol = array_reverse($segmentedColor);
    } else if($invColProps['swirl']) {
        $swirlColor = array();
        if(!empty($invcols)){
            foreach($invcols as $key=>$cldata) {
                $sc = rgb2hex2rgb($cldata);
                $swirlColor[$key] = 'rgb('.$sc['r'].','.$sc['g'].','.$sc['b'].')';
            }
        }
        $invcol = array_reverse($swirlColor);
    } else {

        $solidColor = array();
        if(!empty($invcols)){
            foreach($invcols as $key=>$cldata) {
                $sc = rgb2hex2rgb($cldata);
                $solidColor[$key] = 'rgb('.$sc['r'].','.$sc['g'].','.$sc['b'].')';
            }
        }
        $invcol = $solidColor;
    }

    $options['invColor'] = $invcol;
    //}
}

//var_dump($scolor);die();

$colProps = getModule($vars, "BandColor")->getColorProps($vars, $scolor);
$cols = $colProps['colordata']['message_hexarray'];

//var_dump($msgcol);die();
tpt_dump($cols, true);




$opts['fullsizeX'] = $options['fullsizeX'];//intval($data['pg_x'], 10);
$opts['fullsizeY'] = $options['fullsizeY'];//intval($data['pg_y'], 10);
//$opts['color'] = '6:'.$msgcol['id'];

    if($colProps['segmented']) {
        $opts['type'] = 'segmented';

        //$segmentedColor = array();
        //if(!empty($cols)){
        //    foreach($cols as $key=>$cldata) {
        //        $sc = rgb2hex2rgb($cldata);
        //        $segmentedColor[$key] = 'rgb('.$sc['r'].','.$sc['g'].','.$sc['b'].')';
        //    }
        //}
        //$options['tColor'] = array_reverse($segmentedColor);

        //if(isset($color['glitter'])){
        //    $options['glitter'] = intval($color['glitter'], 10);
        //}
    } else if($colProps['swirl']) {
        $opts['type'] = 'swirl';

        //$swirlColor = array();
        //if(!empty($cols)){
        //    foreach($cols as $key=>$cldata) {
        //        $sc = rgb2hex2rgb($cldata);
        //        $swirlColor[$key] = 'rgb('.$sc['r'].','.$sc['g'].','.$sc['b'].')';
        //    }
        //}
        //$options['tColor'] = array_reverse($swirlColor);

        //if(isset($color['glitter'])){
        //    $options['glitter'] = intval($color['glitter'], 10);
        //}
    } else {
        $opts['type'] = 'solid';

        //$solidColor = array();
        //if(!empty($cols)){
        //    foreach($cols as $key=>$cldata) {
        //        $sc = rgb2hex2rgb($cldata);
        //        $solidColor[$key] = 'rgb('.$sc['r'].','.$sc['g'].','.$sc['b'].')';
        //    }
        //}
        //$options['tColor'] = $solidColor;
        //var_dump($opts['solidColor']);die();

        //if(isset($color['glitter'])){
        //    $options['glitter'] = intval($color['glitter'], 10);
        //}
    }

    //var_dump($scolor);die();
    $opts['color'] = $scolor;


//if(!is_array($steps))
    $steps = array();
//if(!is_array($steps['commands']))
    $steps['commands'] = array();
//if(!is_array($steps['errors']))
    $steps['errors'] = array();
//$generator = new self($vars);
//var_dump($opts);die();
$opts['pg_x'] = $data['pg_x'];
$opts['pg_y'] = $data['pg_y'];

//var_dump($colors_module);die();
//var_dump($opts['bandColor']);die();
//var_dump($opts);die();
$getPreview = self::generatePreview($vars, $opts);
//header('Content-type: image/png');
//die($getPreview);
//$out = $getPreview;
                //if($_SERVER['REMOTE_ADDR'] == '109.160.0.218') {
                //var_dump($opts);die();
                //}
//var_dump($getPreview);die();


                $options['textColor'] = $getPreview;
                */

                //tpt_dump($getPreview, true);

                $cprops = $colors_module->getColorProps($vars, $data['textColor']);
                $opts['type'] = $cprops['colordata']['message_color_type'];//intval($data['pg_x'], 10);
                $opts['fullsizeX'] = $options['fullsizeX'];//intval($data['pg_x'], 10);
                $opts['fullsizeY'] = $options['fullsizeY'];//intval($data['pg_y'], 10);
                $opts['color'] = $data['textColor'];
                $options['invert_dual'] = 0;
                if(!empty($data['invert_dual']) || !empty($cprops['notched'])) {
                    $options['invert_dual'] = 1;
                    $opts['color'] = $cprops['colordata']['band_uid'];
                    $opts['type'] = $cprops['colordata']['band_color_type'];
                    //tpt_dump($cprops, true);
                }
                $opts['pg_x'] = $data['pg_x'];
                $opts['pg_y'] = $data['pg_y'];
                //tpt_dump($data, true);

                //tpt_dump($opts, true);
                $getPreview = null;
                if($cprops['notched']) {
                    $bdid = $data_module->typeStyle[$data['bandType']][$data['bandStyle']];
                    $opts['pg_x'] = $bdid['preview_width'];
                    $opts['pg_y'] = $bdid['preview_height'];
                    $getPreview = self::generatePreview($vars, $opts);
                    //$out = $getPreview;

                    $w = round($bdid['preview_width']/2);
                    $h = round($bdid['preview_height']);
                    $x = 0;
                    $y = 0;
                    if(($data['elmid'] == 'tpt_pg_back_message') || ($data['elmid'] == 'tpt_pg_back2_message')) {
                        $x = $w;
                    }
                    $getPreview = self::crop($vars, $getPreview, $w, $h, $x, $y);
                    //tpt_dump($getPreview, true);
                } else {
                    $getPreview = self::generatePreview($vars, $opts);
                }
                //tpt_dump($opts, true);
                //tpt_dump($getPreview, true);
                //$out = $getPreview;

                $options['textColor'] = $getPreview;


                $options['cut_away'] = 0;
                if(!empty($data['cut_away']))
                    $options['cut_away'] = 1;

                $options['bandColor'] = rgb2hex2rgb($cprops['hex']);
                $options['bandColor'] = 'rgb('.$options['bandColor']['r'].','.$options['bandColor']['g'].','.$options['bandColor']['b'].')';
                $options['bandType'] = $data['bandType'];
                //var_dump($options['pgType']);die();
            }


            if(!empty($data['lclipart'])) {
                $clipartid = intval($data['lclipart'], 10);
                $clipartpath = getModule($vars, "BandClipart")->getClipartPath($vars, $clipartid);
                if(is_file($clipartpath)) {
                    $options['lclipart'] = escapeshellarg(str_replace('\\', '\\\\', stripslashes($clipartpath)));
                }
            }
            if(!empty($data['rclipart'])) {
                $clipartid = intval($data['rclipart'], 10);
                $clipartpath = getModule($vars, "BandClipart")->getClipartPath($vars, $clipartid);
                if(is_file($clipartpath)) {
                    $options['rclipart'] = escapeshellarg(str_replace('\\', '\\\\', stripslashes($clipartpath)));
                }
            }

            if(strlen($data['text']) > 20)
                    $emboss = '1';
            else
                    $emboss = '1';
            $botpadfactor = 0;


            $options['X'] = intval($data['pg_x'], 10);
            $options['Y'] = intval($data['pg_y'], 10);

            $options['extrude'] = $emboss;
            $initpad = 0;
            $options['botpad'] = $initpad + $botpadfactor;
            $options['toppad'] = 0;
            $options['fullsizeX'] = intval($data['pg_x'], 10);
            $options['fullsizeY'] = intval($data['pg_y'], 10);

            $options['perspective'] = 20;
            $options['distort'] = '0.5';

            $options['format'] = 'png';

            //if($_SERVER['REMOTE_ADDR'] == '109.160.0.218') {
            //var_dump($message1);
            //die();
            //if(!isset($GLOBALS['somecount']))
            //    $GLOBALS['somecount'] = 0;

            //$GLOBALS['somecount']++;
            //file_put_contents(TPT_RESOURCE_DIR.DIRECTORY_SEPARATOR.'kurec.txt', $GLOBALS['somecount']."\n".$data['utext']."\n"."\n", FILE_APPEND);
            //}

            $default_images = array(DEFAULT_MESSAGE_FRONT, DEFAULT_MESSAGE_FRONT2, DEFAULT_MESSAGE_BACK, DEFAULT_MESSAGE_BACK2);
            //var_dump($default_images);//die();
            //var_dump($data['text']);//die();
            if(in_array($data['utext'], $default_images) && empty($data['lclipart']) && empty($data['rclipart']) && ((($data['bandType'] != 5) && ($data['bandType'] != 1)) || ($data['bandStyle'] != 7)) && empty($data['cut_away'])) {
                $cfile = TPT_IMAGES_DIR.DIRECTORY_SEPARATOR.'preview'.DIRECTORY_SEPARATOR.'cached'.DIRECTORY_SEPARATOR.'plain'.DIRECTORY_SEPARATOR.'plain-'.$options['fullsizeX'].'x'.$options['fullsizeY'].'x'.$options['pointsize'].'x'.$options['linespacing'].'-'.str_replace('/', '_', base64_encode($data['utext'])).'-'.str_replace('/', '_', base64_encode($data['font'])).'-style'.$data['bandStyle'].'-'.str_replace('/', '_', base64_encode($data['textColor'])).'.png';
                //var_dump($cfile);die();
                //var_dump(is_file($cfile));die();
//if($_SERVER['REMOTE_ADDR'] == '109.160.0.218') {
//    var_dump(is_file($cfile));//die();
//    var_dump($data['text']);//die();
//    var_dump(str_replace('/', '_', base64_encode($data['text'])));//die();
//    var_dump($cfile);die();
//}
                //if($data['text'] == '////')
                if(is_file($cfile)) {
                    if(!empty($_GET['delcached'])) {
                        unlink($cfile);
                    } else {
                    //die('asdasdas');
                    //header('Content-type: image/png');
                    $out = file_get_contents($cfile);
                    }
                }
                $options['cfile'] = $cfile;
                //var_dump($out);
            }
            //var_dump($data);//die();
            //var_dump($options);//die();
            break;

        default :
            $options['gClass'] = 'Simple';
            $X = intval($data['pg_x'], 10);
            $Y = intval($data['pg_y'], 10);
            $options['X'] = !empty($X)?$X.'':'201';
            $options['Y'] = !empty($Y)?$Y.'':'27';
            $options['text'] = ''.escapeshellarg(str_replace('\\', '\\\\', $data['text'])).'';;
            $options['font'] = $data['font'];
            $options['linespacing'] = '0';
            $options['vpad'] = 5;
            $options['hpad'] = 5;

            $filename = explode('.', preg_replace('#[\'"\(\)]+#', '', $options['font']));
            if(count($filename) > 1) {
                array_pop($filename);
            }
            $filename = implode('.', $filename).'.png';
            $cfile = TPT_IMAGES_DIR.DIRECTORY_SEPARATOR.'preview'.DIRECTORY_SEPARATOR.'cached'.DIRECTORY_SEPARATOR.'simple'.DIRECTORY_SEPARATOR.'simple-'.$options['X'].'x'.$options['Y'].'-hpad'.$options['hpad'].'-vpad'.$options['vpad'].'-'.$filename;
            if(is_file($cfile)) {
                //header('Content-type: image/png');
                $out = file_get_contents($cfile);
            }
            //tpt_dump($out);
            //tpt_dump($cfile);
            //tpt_dump($options['text'], true);
            $options['cfile'] = $cfile;
            break;
    }
}


        /*
        $out = '';
        if(!class_exists('tpt_gclass_'.$this->options['gClass'])) {
            $gClassFile = self::$gClassesDir.DIRECTORY_SEPARATOR.$this->options['gClass'].'.php';
            $gClass = 'tpt_gclass_'.$this->options['gClass'];
            if(is_file($gClassFile)) {
                include($gClassFile);
                if(class_exists($gClass)) {
                    if($gInstance = new $gClass()) {
                        $out = $gInstance->generate($vars, $this->options, $steps);
                    }
                }
            }
        }
        */

        if(empty($out)) {
            $gClass = 'tpt_gclass_'.$options['gClass'];
            if(!class_exists('tpt_gclass_'.$options['gClass'])) {
                $gClassFile = self::$gClassesDir.DIRECTORY_SEPARATOR.$options['gClass'].'.php';
                if(is_file($gClassFile)) {
                    include($gClassFile);
                    if(class_exists($gClass)) {
                        //tpt_dump($gClass, true);
                        if($gInstance = new $gClass()) {
                            //if(!is_array($steps))
                                $steps = array();
                            //if(!is_array($steps['commands']))
                                $steps['commands'] = array();
                            //if(!is_array($steps['errors']))
                                $steps['errors'] = array();
                            $out = $gInstance->generate($vars, $options, $steps);
                        }
                    }
                }
            } else {
                if($gInstance = new $gClass()) {
                    //if(!is_array($steps))
                        $steps = array();
                    //if(!is_array($steps['commands']))
                        $steps['commands'] = array();
                    //if(!is_array($steps['errors']))
                        $steps['errors'] = array();
                    $out = $gInstance->generate($vars, $options, $steps);
                }
            }
        }


        if(empty($out)) {
            $out = self::emptyImage($vars);
        }

        /*
        if(true || empty($getPreview['errors'])) {
            //echo $out;
        } else {
            //var_dump($getPreview['errors']);
        }
        */

        return $out;
    }



    static function ttf2svg(&$vars, $ttfFontName, $svgFontName='', $args=' -autorange -id svgfont', $ttfDir = '', $svgDir = '') {
        if(empty($ttfFontName)) {
            return false;
        }

        $ttfFontName = normalize_filename($ttfFontName, 'ttf');

        if(empty($svgFontName)) {
            $svgFontName = explode('.', $ttfFontName);
            array_pop($svgFontName);
            array_push($svgFontName, 'svg');
            $svgFontName = implode('.', $svgFontName);
        }

        //$ttfFontName = escapeshellarg($ttfFontName);
        //$svgFontName = escapeshellarg($svgFontName);
        //$args = escapeshellarg($args);

        if(empty($ttfDir)) {
            if(defined('FONTS_PATH')) {
                $ttfDir = FONTS_PATH;
            } else {
                $ttfDir = dirname(__FILE__);
            }
        } else {
            $ttfDir = escapeshellarg($ttfDir);
        }

        //die($svgDir.DIRECTORY_SEPARATOR.$ttfFontName);
        if(!file_exists($ttfDir.DIRECTORY_SEPARATOR.$ttfFontName)) {
            return false;
        }

        if(empty($svgDir)) {
            if(defined('SVG_FONTS_PATH')) {
                $svgDir = SVG_FONTS_PATH;
            } else {
                $svgDir = dirname(__FILE__);
            }
        } else {
            $svgDir = escapeshellarg($svgDir);
        }

        if(!file_exists($svgDir.DIRECTORY_SEPARATOR.$svgFontName)) {
            $steps = array();
            $ABCommand = 'java -jar '.BATIK_BIN_PATH.BATIK_TTF2SVG_EXECUTABLE.' ';
            $ABCommand .= $ttfDir.DIRECTORY_SEPARATOR.$ttfFontName.' ';
            $ABCommand .= '	-o ';
            $ABCommand .= $svgDir.DIRECTORY_SEPARATOR.$svgFontName.' ';
            $ABCommand .= '	'.$args.' ';
            //tpt_dump($ABCommand, true);
            self::convert($vars, $steps, 'svgFont', $ABCommand, 2);
        }

        if(!file_exists($svgDir.DIRECTORY_SEPARATOR.$svgFontName)) {
            return false;
        }

        $svgFont = file_get_contents($svgDir.DIRECTORY_SEPARATOR.$svgFontName);

        return array('data'=>$svgFont, 'filename'=>$svgDir.DIRECTORY_SEPARATOR.$svgFontName);
    }


    static function convert(&$vars, &$steps, $stepId, $command, $pipenum=2, $input='') {

        //$command = escapeshellcmd($command);
        //$command = preg_replace('#\\\\\((.*)\\\\\)#s', '($1)', $command);
        $command = preg_replace('#(label:(?:\'\'|[\s]+))#', 'label:\' \' ', $command);
        $command = str_replace("\r\n", "\n", $command);
        $steps['commands'][$stepId] = $command;
		//if(strstr($command, 'png:-')) {
			//tpt_dump($command);
		//}

        $descriptorspec = array();
        switch($pipenum) {
            case 1 :
                $descriptorspec = array(
                        1 => array("pipe", "w")
                );
                break;
            case 3 :
                $descriptorspec = array(
                        0 => array("pipe", "r"),
                        1 => array("pipe", "w"),
                        2 => array("pipe", "w")
                );
                break;
            default :
                $descriptorspec = array(
                        1 => array("pipe", "w"),
                        2 => array("pipe", "w")
                );
                break;
        }

	$process = proc_open($command, $descriptorspec, $pipes);
        $add = '';
        $error = '';


	if (is_resource($process)) {
            if($pipenum > 2) {
                fwrite($pipes[0], $input);
                fclose($pipes[0]);
            }

	    while (!feof($pipes[1])) {
	        $add .= fgets($pipes[1]);
	    }

	    fclose($pipes[1]);

            if($pipenum > 1) {
                while (!feof($pipes[2])) {
                    $error .= fgets($pipes[2]);
                }

                fclose($pipes[2]);
            }

	    $return_value = proc_close($process);
	}

        $steps[$stepId] = $add;
        if($pipenum > 1) {
            $steps['errors'][$stepId] = $error;
        }

        //tpt_dump($stepId);
        if((!empty($_GET['debug_im']) && ($_GET['debug_im']==$stepId)) || (isDump() && !empty($vars['config']['dev']['debugpreviews_im']) && ($vars['config']['dev']['debugpreviews_im']==$stepId))) {
            //tpt_dump($stepId, true);
            tpt_dump($steps);
        }
        if((!empty($_GET['debug_layer']) && ($_GET['debug_layer']==$stepId)) || (isDump() && !empty($vars['config']['dev']['debugpreviews_layer']) && ($vars['config']['dev']['debugpreviews_layer']==$stepId))) {
            //tpt_dump($stepId, true);
            header('Content-type: image/png');
            die($steps[$stepId]);
        }

        return $return_value;
    }
	static function exec(&$vars, &$steps, $stepId, $command, $pipenum=2, $input='') {

		//$command = escapeshellcmd($command);
		//$command = preg_replace('#\\\\\((.*)\\\\\)#s', '($1)', $command);
		$command = preg_replace('#(label:(?:\'\'|[\s]+))#', 'label:\' \' ', $command);
		$command = str_replace("\r\n", "\n", $command);
		$steps['commands'][$stepId] = $command;
		//if(strstr($command, 'png:-')) {
		//tpt_dump($command);
		//}

		$descriptorspec = array();
		switch($pipenum) {
			case 1 :
				$descriptorspec = array(
					1 => array("pipe", "w")
				);
				break;
			case 3 :
				$descriptorspec = array(
					0 => array("pipe", "r"),
					1 => array("pipe", "w"),
					2 => array("pipe", "w")
				);
				break;
			default :
				$descriptorspec = array(
					1 => array("pipe", "w"),
					2 => array("pipe", "w")
				);
				break;
		}

		$process = proc_open($command, $descriptorspec, $pipes);
		$add = '';
		$error = '';


		if (is_resource($process)) {
			if($pipenum > 2) {
				fwrite($pipes[0], $input);
				fclose($pipes[0]);
			}

			while (!feof($pipes[1])) {
				$add .= fgets($pipes[1]);
			}

			fclose($pipes[1]);

			if($pipenum > 1) {
				while (!feof($pipes[2])) {
					$error .= fgets($pipes[2]);
				}

				fclose($pipes[2]);
			}

			$return_value = proc_close($process);
		}

		$steps[$stepId] = $add;
		if($pipenum > 1) {
			$steps['errors'][$stepId] = $error;
		}

		//tpt_dump($stepId);
		if((!empty($_GET['debug_im']) && ($_GET['debug_im']==$stepId)) || (isDump() && !empty($vars['config']['dev']['debugpreviews_im']) && ($vars['config']['dev']['debugpreviews_im']==$stepId))) {
			//tpt_dump($stepId, true);
			tpt_dump($steps);
		}
		if((!empty($_GET['debug_layer']) && ($_GET['debug_layer']==$stepId)) || (isDump() && !empty($vars['config']['dev']['debugpreviews_layer']) && ($vars['config']['dev']['debugpreviews_layer']==$stepId))) {
			//tpt_dump($stepId, true);
			header('Content-type: image/png');
			die($steps[$stepId]);
		}

		return $return_value;
	}


    static function compose(&$vars, $images=array(), $gravity=array('Center')) {
        if(!is_array($images))
            return self::emptyImage($vars);
        if(count($images)<2) {
            return array_pop($images);
        }

        if(!is_array($gravity))
            $gravity = array($gravity);

        $steps = array();
        $i = 0;
		$IMCommand = BIN_PATH.IMAGEMAGICK_BIN;
		$lInput = '';
		foreach($images as $elm) {
				$grv = 'Center';
				if(count($gravity)<2) {
					$grv = reset($gravity);
				} else {
					$grv = array_shift($gravity);
				}
				if($i>0) {
					$IMCommand .= '	-gravity '.$grv.' ';
				}
				$IMCommand .= '	png:- ';
				$lInput .= $elm;
				if($i>0) {
					$IMCommand .= '	-composite ';
				}
				$i++;
		}
		$IMCommand .= '	png:- ';
		self::convert($vars, $steps, 'compose', $IMCommand, 3, $lInput);

        return $steps['compose'];
    }

    static function getImageMagickVersion(&$vars) {
        $steps = array();
	$IMCommand = BIN_PATH.IMAGEMAGICK_BIN;
	$IMCommand .= '	-version ';
        self::convert($vars, $steps, 'version', $IMCommand, 2);

        return $steps['version'];
    }

    static function emptyImage(&$vars) {
        if(defined(EMPTY_IMAGE_FILE) && is_file(EMPTY_IMAGE_FILE))
            return file_get_contents(EMPTY_IMAGE_FILE);
        else
            return self::generateEmptyImage($vars, 1, 1);
    }

    static function generateEmptyImage(&$vars, $x=1, $y=1) {
        $steps = array();
	$IMCommand = BIN_PATH.IMAGEMAGICK_BIN;
	$IMCommand .= '	-size '.$x.'x'.$y.' ';
	$IMCommand .= '	xc:\'transparent\' ';
	$IMCommand .= '	png:- ';
        self::convert($vars, $steps, 'emptyImage', $IMCommand, 2);

        return $steps['emptyImage'];
    }

    static function crop(&$vars, $imageData, $w, $h, $x, $y) {
        $steps = array();
	$IMCommand = BIN_PATH.IMAGEMAGICK_BIN;
	$IMCommand .= '	png:- ';
	$IMCommand .= '	-crop ';
	$IMCommand .= '	'.$w.'x'.$h.get_number_sign_char($x, true).$x.get_number_sign_char($y, true).$y.' ';
	$IMCommand .= '	png:- ';
        self::convert($vars, $steps, 'croppedImage', $IMCommand, 3, $imageData);

        //tpt_dump($IMCommand, true);
        //tpt_dump($steps, true);
        //return $steps;
        return $steps['croppedImage'];
    }

    static function convertsvg(&$vars, $svgImageData, $w, $h, $savepath='', $format='png', $background='transparent') {
        $steps = array();
	$IMCommand = BIN_PATH.IMAGEMAGICK_BIN;
	$IMCommand .= '	svg:- ';
	$IMCommand .= '	-background transparent ';
	$IMCommand .= '	-size '.$w.'x'.$h.' ';
	$IMCommand .= '	'.$format.':- ';
        self::convert($vars, $steps, 'convertedImage', $IMCommand, 3, $svgImageData);

        //tpt_dump($IMCommand, true);
        //tpt_dump($steps, true);
        //return $steps;
        if(!empty($savepath)) {
            file_put_contents($savepath, $steps['convertedImage']);
        }
        return $steps['convertedImage'];
    }

	static function createImage(&$vars, $image) {
		$background = '';

		$layers = array();
		if(!empty($image['l'])) {
			foreach ($image['l'] as $layer) {
				$layers[] = self::createLayer($vars, $layer);
			}
		}
		//tpt_dump($layers);

		return self::compose($vars, $layers);
	}
	static function createLayer(&$vars, $layer, $format='png') {
		if (!empty($_GET['debug_php'])) {
			tpt_dump($layer);
		}

		$db = $vars['db']['handler'];
		$tptlogsdb = DB_DB_TPT_LOGS;

		$bp = BIN_PATH;
		if(!defined('ALT_BIN_PATH')) {
			define('ALT_BIN_PATH', '/usr/local/ImageMagick-6.9.2-10/bin/');
		}
		$im_bin = IMAGEMAGICK_BIN;

		$cpf_module = getModule($vars, 'CustomProductField');
		$sections_module = getModule($vars, 'BuilderSection');
		$sections = $sections_module->moduleData['pname'];

		//tpt_dump($layer);

		$srcimage = '';
		$format = 'png';
		$command = '';
		$in = '';
		$out = '';
		$steps = array();
		$nooutformat = (!empty($layer['nooutformat'])?1:0);

		switch($layer['layertype']) {
			case '_cmd_':
				if(isUltraUser()) {
					//$command = self::g_cmd_($vars, $layer);

					//tpt_dump($layer);
					$command = $layer['command'];
					if (!empty($layer['imageid'])) {
						$id = intval($layer['imageid'], 10);

						$query = <<< EOT
SELECT * FROM `$tptlogsdb`.`tpt_request_rq_imagemagick_sandbox` WHERE `id`=$id
EOT;
						$db->query($query);
						$srcimage = $db->fetch_assoc();

						if(!empty($srcimage)) {
							$in = $srcimage['data'];
						}
					}
				}
				break;
			case 'imageid':
				self::g_imageid($vars, $layer, $out);
				break;
			case 'label':
				$layer = self::updateLabelLayerBoundaries($vars, $layer);
				$command = self::c_label($vars, $layer);
				break;
			case 'message':
				//$layer = self::updateLabelLayerBoundaries($vars, $layer);
				$command = self::c_message($vars, $layer);
				//tpt_dump($command, true);
				break;
			case 'message_new':
				//$layer = self::updateLabelLayerBoundaries($vars, $layer);
				$command = self::c_message_new($vars, $layer);
				//tpt_dump($command, true);
				break;
			case 'message_combined':
				//$layer = self::updateLabelLayerBoundaries($vars, $layer);
				$command = self::c_message_combined($vars, $layer);
				//tpt_dump($command, true);
				break;
			case 'led_message':
			//$layer = self::updateLabelLayerBoundaries($vars, $layer);
			$command = self::c_led_message($vars, $layer);
			//tpt_dump($command, true);
			break;
			case 'led_message2':
				//$layer = self::updateLabelLayerBoundaries($vars, $layer);
				$command = self::c_led_message2($vars, $layer);
				//tpt_dump($command, true);
				break;
			case 'image':
				$command = self::c_image($vars, $layer);
				break;
			case 'led_effects':
				$command = self::c_led_effects($vars, $layer);
				break;
			case 'bandoutline':
				$command = self::c_bandoutline($vars, $layer);
				break;
			case 'fill':
				//tpt_dump($layer);
				$out = self::g_fill($vars, $layer, $steps);
				break;
            case 'flat':
                //tpt_dump($layer);
                $out = self::g_flat($vars, $layer, $steps);
                break;
		}



		if(!empty($command)) {
			if (!empty($layer['defined_area'])) {
				$command = self::c_definedarea($vars, $layer, $command);
			}

			if(!empty($layer['resize'])) {
				$command = self::c_resize($vars, $layer, $command);
			}
			//tpt_dump($command);
			$command = self::c_canvassize($vars, $layer, $command);
			//tpt_dump($command);
			//tpt_dump($layer['opacity']);
			//tpt_dump($command, true);

			if(isset($layer['opacity']) && is_numeric($layer['opacity'])) {
				$command = self::c_setopacity($vars, $layer, $command);
			}

			$layercommand = $command;
			$out = self::exec_command($vars, $command, $format, $in, $steps, 'Layer', $nooutformat);


			//return $out;
			//tpt_dump($cPL, true);
			//$out = $steps['Layer'];

			if(($layer['layertype'] == '_cmd_') || (isUltraUser() && (!empty($_GET['storeimage']) || !empty($_GET['saveimage'])))) {
				self::storeLayer($vars, $layercommand, $out, $steps, $srcimage);
			}
		} else {
			if(empty($out)) {
				if (isUltraUser()) {
					/*
					self::convert($vars, $steps, 'Version', $bp . convert . ' -v', 2);
					die($steps['Version']);
					*/
					die(self::getImageMagickVersion($vars));
				} else {
					$out = self::generateEmptyImage($vars);
				}
			} else {
				self::o_canvassize($vars, $layer, $out, $out, $steps);
			}
		}
		//tpt_dump($IMCommand);
		if(!empty($_GET['debug_php']) || (isDump() && !empty($vars['config']['dev']['debugpreviews_php']))) {
			tpt_dump($steps);
		}
		//$this->convert($vars, $steps, 'Solid', $IMCommand, 3, $steps['Solid']);

		return $out;
	}
	/*
	function g_cmd_(&$vars, $layer, &$out='', &$steps=array()) {
		$command = '';

		if(isUltraUser()) {
			$db = $vars['db']['handler'];

			//$imageid = (!empty($layer['imageid'])?intval($layer['imageid'], 10):0);
			//$srcimage = self::createLayer($vars, array('layertype'=>'imageid', 'imageid'=>$imageid));

			//tpt_dump($image['data'], true);
			//tpt_dump($image, true);

			$command = $layer['command'];
		}

		return $command;
	}
	*/

	static function g_imageid(&$vars, $layer, &$out='', &$steps=array()) {
		if(isUltraUser()) {
			$db = $vars['db']['handler'];

			$tptlogsdb = DB_DB_TPT_LOGS;

			$id = intval($layer['imageid'], 10);

			$query = <<< EOT
SELECT * FROM `$tptlogsdb`.`tpt_request_rq_imagemagick_sandbox` WHERE `id`=$id
EOT;
			$db->query($query);
			$image = $db->fetch_assoc();
			//tpt_dump($image['data'], true);
			//tpt_dump($image, true);

			$out = $image['data'];
		}
	}
	static function c_label(&$vars, $layer, &$out='', &$steps=array()) {
		$color_module = getModule($vars, 'BandColor');
		$fonts_module = getModule($vars, 'BandFont');
		$fonts = $fonts_module->moduleData['id'];


		//$isfront (empty($message['back']) && empty($message['line2'])) {
		$bp = BIN_PATH;
		if(defined('ALT_BIN_PATH')) {
			$bp = ALT_BIN_PATH;
		}
		$im_bin = IMAGEMAGICK_BIN;

		//tpt_dump($layer);


		$cX = (!empty($layer['cX'])?intval($layer['cX'], 10):1);
		$cY = (!empty($layer['cY'])?intval($layer['cY'], 10):1);

		$cPL = (!empty($layer['cPL'])?intval($layer['cPL'], 10):0);
		$cPR = (!empty($layer['cPR'])?intval($layer['cPR'], 10):0);
		$cPT = (!empty($layer['cPT'])?intval($layer['cPT'], 10):0);
		$cPB = (!empty($layer['cPB'])?intval($layer['cPB'], 10):0);

		$text = ''.escapeshellarg(str_replace('\\', '\\\\', (isset($layer[$layer['target']])?$layer[$layer['target']]:''))).'';

		$font = FONTS_PATH.DIRECTORY_SEPARATOR.(!empty($layer['font'])?$fonts[$layer['font']]['file']:DEFAULT_FONT_NAME);
		$font = <<< EOT
-font '$font'
EOT;


		//$color = '-fill '.((!empty($layer['color']) && ($layer['color'] != 'transparent') && ($layer['color'] != 'none'))?''.escapeshellarg($layer['color']):'none').'';
		//if (!empty($layer['message_color']) && strstr($layer['message_color'], ':')) {
		if ((!empty($layer['color']) && ($layer['color'] != 'transparent') && ($layer['color'] != 'none'))) {
			$fill = escapeshellarg($layer['color']);
			$color = <<< EOT
-fill $fill
EOT;
		} else {
			$color = <<< EOT
-fill none
EOT;
		}
		if (!empty($layer['message_color'])) {
			$cprops = $color_module->getColorProps($vars, $layer['message_color']);
			$fill = (!empty($cprops['hex']) ? escapeshellarg('#' . $cprops['hex']) : 'none');
			$color = <<< EOT
-fill $fill
EOT;
		}
		$bg = <<< EOT
-background 'transparent'
EOT;


		$stroke = '';
		$strokewidth = '';

		$inner_shadow = '';
		$inner_glow = '';
		$drop_shadow = '';
		$outer_glow = '';


		$resize = '';
		if(!empty($layer['snug_fit_label'])) {
			$resize = <<< EOT
-resize {$cX}x{$cY}
EOT;

		}
		//$gravity = '-gravity center';
		$gravity = '';
		if(!empty($layer['gravity'])) {
			$gravity = escapeshellarg($layer['gravity']);
			$gravity = <<< EOT
-gravity $gravity
EOT;
		}

		if(!empty($layer['stroke'])) {
			$stroke = escapeshellarg($layer['stroke_color']);
			$stroke = <<< EOT
-stroke $stroke
EOT;


			if(!empty($layer['stroke_width'])) {
				$strokewidth = intval($layer['stroke_width'], 10);
				$strokewidth = <<< EOT
-strokewidth $strokewidth
EOT;
			}
		}

		if(!empty($layer['inner_shadow'])) {
			$inner_shadow_color = '#333333';
			if(!empty($layer['inner_shadow_color'])) {
				$inner_shadow_color = $layer['inner_shadow_color'];
			}
			$inner_shadow_color = escapeshellarg($inner_shadow_color);
			$inner_shadow_opacity = '';
			if(!empty($layer['inner_shadow_opacity'])) {
				$inner_shadow_opacity = floatval($layer['inner_shadow_opacity']);
				$inner_shadow_opacity = <<< EOT
-alpha set \
-channel a \
-evaluate \
multiply $inner_shadow_opacity \
+channel
EOT;
			}

			$inner_shadow_distance_x = intval($layer['inner_shadow_distance_x'], 10);
			$inner_shadow_distance_x = (($inner_shadow_distance_x>=0)?'+'.$inner_shadow_distance_x:$inner_shadow_distance_x);
			$inner_shadow_distance_y = intval($layer['inner_shadow_distance_y'], 10);
			$inner_shadow_distance_y = (($inner_shadow_distance_y>=0)?'+'.$inner_shadow_distance_y:$inner_shadow_distance_y);
			$inner_shadow = <<< EOT
\( \
-size {$cX}x{$cY} \
-background 'transparent' \
-gravity center \
-fill $inner_shadow_color \
$font \
label:$text \
\
-background 'transparent' \
-gravity center \
-fill 'black' \
$font \
label:$text \
-geometry $inner_shadow_distance_x$inner_shadow_distance_y \
-compose Dst_Out -composite \
\
$inner_shadow_opacity \
\) \
-geometry +0+0 \
-compose Over -composite
EOT;
		}

		if(!empty($layer['inner_glow'])) {
			$inner_glow_color = '#FFFFFF';
			if(!empty($layer['inner_glow_color'])) {
				$inner_glow_color = $layer['inner_glow_color'];
			}
			$inner_glow_color = escapeshellarg($inner_glow_color);
			$inner_glow_opacity = '';
			if(!empty($layer['inner_glow_opacity'])) {
				$inner_glow_opacity = floatval($layer['inner_glow_opacity']);
				$inner_glow_opacity = <<< EOT
-alpha set \
-channel a \
-evaluate \
multiply $inner_glow_opacity \
+channel
EOT;
			}

			$inner_glow_distance_x = intval($layer['inner_glow_distance_x'], 10);
			$inner_glow_distance_x = (($inner_glow_distance_x>=0)?'+'.$inner_glow_distance_x:$inner_glow_distance_x);
			$inner_glow_distance_y = intval($layer['inner_glow_distance_y'], 10);
			$inner_glow_distance_y = (($inner_glow_distance_y>=0)?'+'.$inner_glow_distance_y:$inner_glow_distance_y);
			$inner_glow = <<< EOT
\( \
-size {$cX}x{$cY} \
-background 'transparent' \
-gravity center \
-fill $inner_glow_color \
$font \
label:$text \
-background 'transparent' \
-gravity center \
-fill 'black' \
$font \
label:$text \
-geometry $inner_glow_distance_x$inner_glow_distance_y \
-compose Dst_Out -composite \
\
$inner_glow_opacity \
\) \
-geometry +0+0 \
-compose Over -composite
EOT;
		}

		if(!empty($layer['drop_shadow'])) {
			$drop_shadow_color = '#333333';
			if(!empty($layer['drop_shadow_color'])) {
				$drop_shadow_color = $layer['drop_shadow_color'];
			}
			$drop_shadow_color = escapeshellarg($drop_shadow_color);
			$drop_shadow_opacity = '';
			if(!empty($layer['drop_shadow_opacity'])) {
				$drop_shadow_opacity = floatval($layer['drop_shadow_opacity']);
				$drop_shadow_opacity = <<< EOT
-alpha set \
-channel a \
-evaluate \
multiply $drop_shadow_opacity \
+channel
EOT;
			}

			$drop_shadow_distance_x = intval($layer['drop_shadow_distance_x'], 10);
			$drop_shadow_cast_x = intval($layer['drop_shadow_distance_x'], 10)*-1;
			$drop_shadow_distance_x = (($drop_shadow_distance_x>=0)?'+'.$drop_shadow_distance_x:$drop_shadow_distance_x);
			$drop_shadow_cast_x = (($drop_shadow_cast_x>=0)?'+'.$drop_shadow_cast_x:$drop_shadow_cast_x);
			$drop_shadow_distance_y = intval($layer['drop_shadow_distance_y'], 10);
			$drop_shadow_cast_y = intval($layer['drop_shadow_distance_y'], 10)*-1;
			$drop_shadow_distance_y = (($drop_shadow_distance_y>=0)?'+'.$drop_shadow_distance_y:$drop_shadow_distance_y);
			$drop_shadow_cast_y = (($drop_shadow_cast_y>=0)?'+'.$drop_shadow_cast_y:$drop_shadow_cast_y);
			$drop_shadow = <<< EOT
\( \
-size {$cX}x{$cY} \
-background 'transparent' \
-gravity center \
-fill $drop_shadow_color \
$font \
label:$text \
\
-background 'transparent' \
-gravity center \
-fill 'black' \
$font \
label:$text \
-geometry $drop_shadow_distance_x$drop_shadow_distance_y \
-compose Dst_Out -composite \
\
$drop_shadow_opacity \
\) \
-geometry $drop_shadow_cast_x$drop_shadow_cast_y \
-compose Over -composite
EOT;
		}

		if(!empty($layer['outer_glow'])) {
			$outer_glow_color = '#FFFFFF';
			if(!empty($layer['outer_glow_color'])) {
				$outer_glow_color = $layer['outer_glow_color'];
			}
			$outer_glow_color = escapeshellarg($outer_glow_color);
			$outer_glow_opacity = '';
			if(!empty($layer['outer_glow_opacity'])) {
				$outer_glow_opacity = floatval($layer['outer_glow_opacity']);
				$outer_glow_opacity = <<< EOT
-alpha set \
-channel a \
-evaluate \
multiply $outer_glow_opacity \
+channel
EOT;
			}

			$outer_glow_distance_x = intval($layer['outer_glow_distance_x'], 10);
			$outer_glow_cast_x = intval($layer['outer_glow_distance_x'], 10)*-1;
			$outer_glow_distance_x = (($outer_glow_distance_x>=0)?'+'.$outer_glow_distance_x:$outer_glow_distance_x);
			$outer_glow_cast_x = (($outer_glow_cast_x>=0)?'+'.$outer_glow_cast_x:$outer_glow_cast_x);
			$outer_glow_distance_y = intval($layer['outer_glow_distance_y'], 10);
			$outer_glow_cast_y = intval($layer['outer_glow_distance_y'], 10)*-1;
			$outer_glow_distance_y = (($outer_glow_distance_y>=0)?'+'.$outer_glow_distance_y:$outer_glow_distance_y);
			$outer_glow_cast_y = (($outer_glow_cast_y>=0)?'+'.$outer_glow_cast_y:$outer_glow_cast_y);
			$outer_glow = <<< EOT
\( \
-size {$cX}x{$cY} \
-background 'transparent' \
-gravity center \
-fill $outer_glow_color \
$font \
label:$text \
\
-background 'transparent' \
-gravity center \
-fill 'black' \
$font \
label:$text \
-geometry $outer_glow_distance_x$outer_glow_distance_y \
-compose Dst_Out -composite \
\
$outer_glow_opacity \
\) \
-geometry $outer_glow_cast_x$outer_glow_cast_y \
-compose Over -composite
EOT;
		}

		$addlabel = '';
		if(!empty($color)) {
			$addlabel = <<< EOT
-gravity center \
-size {$cX}x{$cY} \
$stroke \
$strokewidth \
$bg \
\
$color \
$font \
label:$text
EOT;
		} else {
			$addlabel = <<< EOT
-gravity center \
-size {$cX}x{$cY} \
$stroke \
$strokewidth \
$bg \
\
-fill none \
$font \
label:$text
EOT;
		}

		$command = <<< EOT
{$bp}{$im_bin} \
-respect-parenthesis \
$addlabel \
$inner_shadow \
$inner_glow \
$drop_shadow \
$outer_glow \
-trim \
$resize \

EOT;

		//tpt_dump($command);
		return $command;
	}

	static function c_message_old(&$vars, &$layer, &$out='', &$steps=array()) {
		$color_module = getModule($vars, 'BandColor');
		$msg_module = getModule($vars, 'BandMessage');
		$cpf_module = getModule($vars, 'CustomProductField');
		$fonts_module = getModule($vars, 'BandFont');
		$layouts_module = getModule($vars, 'BandLayout');
		$fonts = $fonts_module->moduleData['id'];
		$clipart_module = getModule($vars, 'BandClipart');


		//$isfront (empty($message['back']) && empty($message['line2'])) {
		$bp = BIN_PATH;
		if(defined('ALT_BIN_PATH')) {
			$bp = ALT_BIN_PATH;
		}
		$im_bin = IMAGEMAGICK_BIN;

		//tpt_dump($layer);
		$layout = (!empty($layer['band_layout'])?intval($layer['band_layout'], 10):(!empty($layer['layout'])?intval($layer['layout'], 10):1));
		$layout = $layouts_module->moduleData['id'][$layout];

		$targets = explode(',', $layer['target']);
		$targets = array_combine($targets, $targets);
		$targets = array_intersect_key($cpf_module->moduleData['id'], $targets);

		$messages = array();
		$m = array();
		$clipart = array();
		foreach($targets as $tid=>$target) {
			if(isset($layer[$target['pname']])) {
				if(!empty($target['text'])) {
					$messages[$tid] = $layer[$target['pname']];
				} else if(!empty($target['clipart'])) {
					$clipart[$tid] = $layer[$target['pname']];
				}
			}
		}

		$ncmessages = array();
		$ncparams = explode('|', $layer['nullcheck_preview_params_ids']);
		foreach($ncparams as $ncparam) {
			$ncp = explode(':', $ncparam);
			if(!empty($cpf_module->moduleData['id'][$ncp[0]]) && !empty($cpf_module->moduleData['id'][$ncp[0]]['text'])) {
				$ncmessages[$ncp[0]] = $cpf_module->moduleData['id'][$ncp[0]];
			}
		}

		//tpt_dump($layer['cX']);
		//tpt_dump($layer['cPR']);
		//tpt_dump($layer['cPL']);
		if(!empty($ncmessages)) {
			$ncmsg = reset($ncmessages);
			//tpt_dump($layer[$ncmsg['pname']]);
			//tpt_dump($layout['text_frontback']);
			//tpt_dump($messages, true);
			if (!empty($layout['text_frontback']) && !empty($ncmessages) && !empty($layer[$ncmsg['pname']])) {
				$imsg = reset($messages);
				$imsg = key($messages);
				$cXex = floor($layer['cX'] / 2);
				$layer['cX'] -= ($cXex+5);
				//tpt_dump($msg_module->moduleData['pname'][$cpf_module->moduleData['id'][$imsg]['pname']]);
				if (!empty($cpf_module->moduleData['id'][$imsg]['pname']) && !empty($msg_module->moduleData['pname'][$cpf_module->moduleData['id'][$imsg]['pname']]['back'])) {
					$layer['cPL'] += ($cXex+5);
				} else {
					$layer['cPR'] += ($cXex+5);
				}
			}
		}
		//tpt_dump($layer['cX']);
		//tpt_dump($layer['cPR']);
		//tpt_dump($layer['cPL']);


		$cX = (!empty($layer['cX'])?intval($layer['cX'], 10):1);
		$cY = (!empty($layer['cY'])?intval($layer['cY'], 10):1);

		$cPL = (!empty($layer['cPL'])?intval($layer['cPL'], 10):0);
		$cPR = (!empty($layer['cPR'])?intval($layer['cPR'], 10):0);
		$cPT = (!empty($layer['cPT'])?intval($layer['cPT'], 10):0);
		$cPB = (!empty($layer['cPB'])?intval($layer['cPB'], 10):0);

		/*
		$ncparams = explode(',', $ncparams[1]);
		$ncparams = array_combine($ncparams, $ncparams);
		$ncparams = array_intersect_key($cpf_module->moduleData['id'], $ncparams);
		*/
		//$layer[$nctrgt['pname']] =

		//tpt_dump($cX, true);


		$font = FONTS_PATH.DIRECTORY_SEPARATOR.(!empty($layer['font'])?$fonts[$layer['font']]['file']:DEFAULT_FONT_NAME);
		$font = <<< EOT
-font '$font'
EOT;


		//$color = '-fill '.((!empty($layer['color']) && ($layer['color'] != 'transparent') && ($layer['color'] != 'none'))?''.escapeshellarg($layer['color']):'none').'';
		//if (!empty($layer['message_color']) && strstr($layer['message_color'], ':')) {
		$color = <<< EOT
-fill none
EOT;
		if ((!empty($layer['color']) && ($layer['color'] != 'transparent') && ($layer['color'] != 'none'))) {
			$fill = escapeshellarg($layer['color']);
			$color = <<< EOT
-fill $fill
EOT;
		}
		if (!empty($layer['message_color'])) {
			$cprops = $color_module->getColorProps($vars, $layer['message_color']);
			$fill = (!empty($cprops['hex']) ? escapeshellarg('#' . $cprops['hex']) : 'none');
			$color = <<< EOT
-fill $fill
EOT;
		}
		$bg = <<< EOT
-background 'transparent'
EOT;


		$stroke = '';
		$strokewidth = '';

		$inner_shadow = '';
		$inner_glow = '';
		$drop_shadow = '';
		$outer_glow = '';


		$kern = '';
		if(!empty($layer['kern'])) {
			$kern = escapeshellarg($layer['kern']);
			$kern = <<< EOT
-kerning $kern
EOT;
		}


		$resize = '';
		if(!empty($layer['snug_fit_label'])) {
			$resize = <<< EOT
-resize {$cX}x{$cY}
EOT;

		}
		//$gravity = '-gravity center';
		$gravity = '';
		if(!empty($layer['gravity'])) {
			$gravity = escapeshellarg($layer['gravity']);
			$gravity = <<< EOT
-gravity $gravity
EOT;
		}

		if(!empty($layer['stroke'])) {
			$stroke = escapeshellarg($layer['stroke_color']);
			$stroke = <<< EOT
-stroke $stroke
EOT;


			if(!empty($layer['stroke_width'])) {
				$c_strokewidth = intval($layer['stroke_width'], 10)+2;
				$c_strokewidth = <<< EOT
-strokewidth $c_strokewidth
EOT;

				$strokewidth = intval($layer['stroke_width'], 10);
				$strokewidth = <<< EOT
-strokewidth $strokewidth
EOT;
			}
		}

		$s = array();
		$metrics = array();
		$cYm = $cY;
		if (!empty($layout['text_topbottom']) && (count($messages)>1)) {
			$cYm = floor($layer['cY']/count($messages));
		}

		$pointsize = 0;
		$clp_y = min($cX, $cYm);
		foreach($messages as $tid=>$msg) {
			$cXmm = $cX;
			$msgdata = $cpf_module->moduleData['id'][$tid];


			//tpt_dump($clpnames, true);
			//tpt_dump($clipart, true);

			$text = $msg;
			if(empty($text)) {
				$text = 'W';
			}
			//tpt_dump($text);
			$text = ''.escapeshellarg(str_replace('\\', '\\\\', $text)).'';


			foreach ($clipart as $ctid => $clp) {
				$clpdata = $cpf_module->moduleData['id'][$ctid];
				$cmsg = $cpf_module->moduleData['id'][$clpdata['clipart_text_id']];

				if($tid == $cmsg['id']) {
					if(isset($layer[$clpdata['pname']])) {
						if(!empty($layout['clipart_leftright'])) {
							$cXmm -= ($cYm+2);
						}
					}
				}
			}



			$fsize = <<< EOT
{$bp}{$im_bin} \
-size {$cXmm}x{$cYm} \
$stroke \
$strokewidth \
$bg \
\
-fill 'white' \
$font \
$kern \
label:$text \
-format "%[label:pointsize]|%@" \
info:
EOT;
			if(!empty($_GET['debug_php'])) {
				tpt_dump($fsize);
			}
			$fsize = self::exec_command($vars, $fsize, '', '', $s, $msgdata['pname'], 1);
			$metrics[$msgdata['pname']] = explode('|', $fsize);
			//tpt_dump($metrics, true);
			$metrics[$msgdata['pname']][1] = preg_split('#\+|-#', $metrics[$msgdata['pname']][1]);
			$metrics[$msgdata['pname']][1] = array_shift($metrics[$msgdata['pname']][1]);
			$metrics[$msgdata['pname']][1] = explode('x', $metrics[$msgdata['pname']][1]);
			$metrics[$msgdata['pname']] = array('x'=>$metrics[$msgdata['pname']][1][0], 'y'=>$metrics[$msgdata['pname']][1][1], 'ps'=>$metrics[$msgdata['pname']][0]);

			$dx = $cXmm - $metrics[$msgdata['pname']]['x'];
			$dy = $cYm - $metrics[$msgdata['pname']]['y'];
			$metrics[$msgdata['pname']]['ops'] = $metrics[$msgdata['pname']]['ps'];
			if(false && $dy > 2) {
				$dps = round($metrics[$msgdata['pname']]['ps']) + $dy;
				$fsize2 = <<< EOT
{$bp}{$im_bin} \
-pointsize {$dps} \
$stroke \
$strokewidth \
$bg \
\
-fill 'white' \
$font \
$kern \
label:$text \
-format "%@" \
info:
EOT;
				if(!empty($_GET['debug_php'])) {
					tpt_dump($fsize2);
				}
				$fsize2 = self::exec_command($vars, $fsize2, '', '', $s, $msgdata['pname'], 1);
				$fsize2 = preg_split('#\+|-#', $fsize2);
				$fsize2 = array_shift($fsize2);
				$fsize2 = explode('x', $fsize2);
				if(($fsize2[0] <= $cXmm) && ($fsize2[1] <= $cYm)) {
					$metrics[$msgdata['pname']]['ops'] = $dps;
				}
			}
			//tpt_dump($metrics, true);
			//tpt_dump($fsize, true);
			if(empty($pointsize) || ($pointsize > $metrics[$msgdata['pname']]['ops'])) {
				$pointsize = $metrics[$msgdata['pname']]['ops'];
			}

			if(empty($clp_y) || (!empty($metrics[$msgdata['pname']]['y']) && ($clp_y > $metrics[$msgdata['pname']]['y']))) {
				$clp_y = $metrics[$msgdata['pname']]['y'];
			}

		}
		//tpt_dump($metrics, true);

		if(empty($pointsize)) {
			$pointsize = 10;
		}
		$msgs = array();
		foreach($messages as $tid=>$msg) {
			$cXm = $cX;
			$msgdata = $cpf_module->moduleData['id'][$tid];
			//$text = implode($layout['text_separator'], $messages);
			$text = $msg;
			if(empty($text)) {
				$text = ' ';
			}

			//tpt_dump($text);
			$text = ''.escapeshellarg(str_replace('\\', '\\\\', $text)).'';

			$pgravity = '-gravity Center';
			if (!empty($layout['text_topbottom']) && (count($messages)>1)) {
				if(!empty($msg_module->moduleData['pname'][$msgdata['pname']]['line2'])) {
					$pgravity = '-gravity South';
				} else {
					$pgravity = '-gravity North';
				}
			}


			if(!empty($layer['inner_shadow'])) {
				//tpt_dump('asd', true);
				$inner_shadow_color = '#333333';
				if(!empty($layer['inner_shadow_color'])) {
					$inner_shadow_color = $layer['inner_shadow_color'];
				}
				$inner_shadow_color = escapeshellarg($inner_shadow_color);
				$inner_shadow_opacity = '';
				if(!empty($layer['inner_shadow_opacity'])) {
					$inner_shadow_opacity = floatval($layer['inner_shadow_opacity']);
					$inner_shadow_opacity = <<< EOT
-alpha set \
-channel a \
-evaluate \
multiply $inner_shadow_opacity \
+channel
EOT;
				}

				$inner_shadow_distance_x = intval($layer['inner_shadow_distance_x'], 10);
				$inner_shadow_distance_x = (($inner_shadow_distance_x>=0)?'+'.$inner_shadow_distance_x:$inner_shadow_distance_x);
				$inner_shadow_distance_y = intval($layer['inner_shadow_distance_y'], 10);
				$inner_shadow_distance_y = (($inner_shadow_distance_y>=0)?'+'.$inner_shadow_distance_y:$inner_shadow_distance_y);
				$inner_shadow = <<< EOT
\( \
-pointsize $pointsize \
-background 'transparent' \
-fill $inner_shadow_color \
$font \
$kern \
label:$text \
-trim \
-gravity center \
-extent {$cX}x{$cYm} \
\( \
+clone \
-fill 'white' \
-colorize 100 \
\) \
-geometry $inner_shadow_distance_x$inner_shadow_distance_y \
-compose Dst_Out -composite \
\
$inner_shadow_opacity \
\) \
-gravity center \
-geometry +0+0 \
-compose Over -composite
EOT;
			}

			if(!empty($layer['inner_glow'])) {
				$inner_glow_color = '#FFFFFF';
				if(!empty($layer['inner_glow_color'])) {
					$inner_glow_color = $layer['inner_glow_color'];
				}
				$inner_glow_color = escapeshellarg($inner_glow_color);
				$inner_glow_opacity = '';
				if(!empty($layer['inner_glow_opacity'])) {
					$inner_glow_opacity = floatval($layer['inner_glow_opacity']);
					$inner_glow_opacity = <<< EOT
-alpha set \
-channel a \
-evaluate \
multiply $inner_glow_opacity \
+channel
EOT;
				}

				$inner_glow_distance_x = intval($layer['inner_glow_distance_x'], 10);
				$inner_glow_distance_x = (($inner_glow_distance_x>=0)?'+'.$inner_glow_distance_x:$inner_glow_distance_x);
				$inner_glow_distance_y = intval($layer['inner_glow_distance_y'], 10);
				$inner_glow_distance_y = (($inner_glow_distance_y>=0)?'+'.$inner_glow_distance_y:$inner_glow_distance_y);
				$inner_glow = <<< EOT
\( \
-pointsize $pointsize \
-background 'transparent' \
-fill $inner_glow_color \
$font \
$kern \
label:$text \
-trim \
-gravity center \
-extent {$cX}x{$cYm} \
\( \
+clone \
-fill 'white' \
-colorize 100 \
\) \
-geometry $inner_glow_distance_x$inner_glow_distance_y \
-compose Dst_Out -composite \
\
$inner_glow_opacity \
\) \
-gravity center \
-geometry +0+0 \
-compose Over -composite
EOT;
			}

			if(!empty($layer['drop_shadow'])) {
				$drop_shadow_color = '#333333';
				if(!empty($layer['drop_shadow_color'])) {
					$drop_shadow_color = $layer['drop_shadow_color'];
				}
				$drop_shadow_color = escapeshellarg($drop_shadow_color);
				$drop_shadow_opacity = '';
				if(!empty($layer['drop_shadow_opacity'])) {
					$drop_shadow_opacity = floatval($layer['drop_shadow_opacity']);
					$drop_shadow_opacity = <<< EOT
-alpha set \
-channel a \
-evaluate \
multiply $drop_shadow_opacity \
+channel
EOT;
				}

				$drop_shadow_distance_x = intval($layer['drop_shadow_distance_x'], 10);
				$drop_shadow_cast_x = intval($layer['drop_shadow_distance_x'], 10)*-1;
				$drop_shadow_distance_x = (($drop_shadow_distance_x>=0)?'+'.$drop_shadow_distance_x:$drop_shadow_distance_x);
				$drop_shadow_cast_x = (($drop_shadow_cast_x>=0)?'+'.$drop_shadow_cast_x:$drop_shadow_cast_x);
				$drop_shadow_distance_y = intval($layer['drop_shadow_distance_y'], 10);
				$drop_shadow_cast_y = intval($layer['drop_shadow_distance_y'], 10)*-1;
				$drop_shadow_distance_y = (($drop_shadow_distance_y>=0)?'+'.$drop_shadow_distance_y:$drop_shadow_distance_y);
				$drop_shadow_cast_y = (($drop_shadow_cast_y>=0)?'+'.$drop_shadow_cast_y:$drop_shadow_cast_y);
				$drop_shadow = <<< EOT
\( \
-pointsize $pointsize \
-background 'transparent' \
-fill $drop_shadow_color \
$font \
$kern \
label:$text \
-trim \
-gravity center \
-extent {$cX}x{$cYm} \
\( \
+clone \
-fill 'white' \
-colorize 100 \
\) \
-geometry $drop_shadow_distance_x$drop_shadow_distance_y \
-compose Dst_Out -composite \
\
$drop_shadow_opacity \
\) \
-gravity center \
-geometry $drop_shadow_cast_x$drop_shadow_cast_y \
-compose Over -composite
EOT;
			}

			if(!empty($layer['outer_glow'])) {
				$outer_glow_color = '#FFFFFF';
				if(!empty($layer['outer_glow_color'])) {
					$outer_glow_color = $layer['outer_glow_color'];
				}
				$outer_glow_color = escapeshellarg($outer_glow_color);
				$outer_glow_opacity = '';
				if(!empty($layer['outer_glow_opacity'])) {
					$outer_glow_opacity = floatval($layer['outer_glow_opacity']);
					$outer_glow_opacity = <<< EOT
-alpha set \
-channel a \
-evaluate \
multiply $outer_glow_opacity \
+channel
EOT;
				}

				$outer_glow_distance_x = intval($layer['outer_glow_distance_x'], 10);
				$outer_glow_cast_x = intval($layer['outer_glow_distance_x'], 10)*-1;
				$outer_glow_distance_x = (($outer_glow_distance_x>=0)?'+'.$outer_glow_distance_x:$outer_glow_distance_x);
				$outer_glow_cast_x = (($outer_glow_cast_x>=0)?'+'.$outer_glow_cast_x:$outer_glow_cast_x);
				$outer_glow_distance_y = intval($layer['outer_glow_distance_y'], 10);
				$outer_glow_cast_y = intval($layer['outer_glow_distance_y'], 10)*-1;
				$outer_glow_distance_y = (($outer_glow_distance_y>=0)?'+'.$outer_glow_distance_y:$outer_glow_distance_y);
				$outer_glow_cast_y = (($outer_glow_cast_y>=0)?'+'.$outer_glow_cast_y:$outer_glow_cast_y);
				$outer_glow = <<< EOT
\( \
-pointsize $pointsize \
-background 'transparent' \
-fill $outer_glow_color \
$font \
$kern \
label:$text \
-trim \
-gravity center \
-extent {$cX}x{$cYm} \
\( \
+clone \
-fill 'white' \
-colorize 100 \
\) \
-geometry $outer_glow_distance_x$outer_glow_distance_y \
-compose Dst_Out -composite \
\
$outer_glow_opacity \
\) \
-gravity center \
-geometry $outer_glow_cast_x$outer_glow_cast_y \
-compose Over -composite
EOT;
			}


			//tpt_dump($clipart);
			//tpt_dump($layout);
			$clp_x = max(min($cX, $cYm), floor(($cX - $metrics[$msgdata['pname']]['x'])/max(1, count($clipart))));
			$clpnames = array();
			$clpgrvt = array();
			$clpoffsigns = array();
			$msggrvt = '-gravity Center';
			if (!empty($layout['text_topbottom']) && (count($messages)>1)) {
				foreach ($clipart as $ctid => $clp) {
					$clpdata = $cpf_module->moduleData['id'][$ctid];
					$cmsg = $cpf_module->moduleData['id'][$clpdata['clipart_text_id']];

					if($tid == $cmsg['id']) {
						//tpt_dump('asd');
						//tpt_dump($clpdata['pname'], true);
						if(isset($layer[$clpdata['pname']])) {
							//tpt_dump('asd');
							//tpt_dump($layout, true);
							//tpt_dump($clpdata['pname'], true);
							$clpg = '-gravity East';
							$clpoffsign = '+';
							$msggrvt = '-gravity Center';
							if(!empty($layout['clipart_leftright'])) {
								$cXm -= ($clp_x);
								//tpt_dump($layer[$clpdata['pname']], true);
								if(empty($clpdata['orientation'])) {
									$clpg = '-gravity West';
									$clpoffsign = '-';
									$msggrvt = '-gravity Center';
								}
							}

							$clpoffsigns[$ctid][$layer[$clpdata['pname']]] = $clpoffsign;
							$clpgrvt[$ctid][$layer[$clpdata['pname']]] = $clpg;
							$clpnames[$ctid][$layer[$clpdata['pname']]] = $clipart_module->getClipartPath($vars, $layer[$clpdata['pname']]);
						}
					}


				}
			} else {
				foreach ($clipart as $ctid => $clp) {
					$clpdata = $cpf_module->moduleData['id'][$ctid];
					$cmsg = $cpf_module->moduleData['id'][$clpdata['clipart_text_id']];

					if($tid == $cmsg['id']) {
						//tpt_dump('asd');
						//tpt_dump($clpdata['pname'], true);
						if(isset($layer[$clpdata['pname']])) {
							//tpt_dump('asd');
							//tpt_dump($layout, true);
							//tpt_dump($clpdata['pname'], true);
							$clpg = '-gravity East';
							$clpoffsign = '+';
							$msggrvt = '-gravity Center';
							if(!empty($layout['clipart_leftright'])) {
								$cXm -= ($clp_x);
								//tpt_dump($layer[$clpdata['pname']], true);
								if(empty($clpdata['orientation'])) {
									$clpg = '-gravity West';
									$clpoffsign = '-';
									$msggrvt = '-gravity Center';
								}
							}

							$clpoffsigns[$ctid][$layer[$clpdata['pname']]] = $clpoffsign;
							$clpgrvt[$ctid][$layer[$clpdata['pname']]] = $clpg;
							$clpnames[$ctid][$layer[$clpdata['pname']]] = $clipart_module->getClipartPath($vars, $layer[$clpdata['pname']]);
						}
					}


				}
			}


			if(!empty($layout['clipart_leftright']) && (count($clpnames) > 1)) {
				$msggrvt = '-gravity Center';
			}

			//tpt_dump($clpnames);


			/*
			$clp_xx = $clp_x-5;
			$clp_yy = $clp_y-5;
			-size x{$clp_yy} \
			-resize {$clp_xx}x{$clp_yy} \

+clone \
-compose Over -composite \
+clone \
-compose Over -composite \
+clone \
-compose Over -composite \
+clone \
-compose Over -composite


\( \
-background 'transparent' \
-stroke none \
-strokewidth 0 \
$bg \
$color \
-trim \
+repage \
-density 1200 \
-size x{$clp_yy} \
-resize {$clp_xx}x{$clp_yy} \
$c \
\) \
-compose Over \
-composite \
			*/
			$clp_xx = $clp_x-5;
			$clp_yy = $clp_y-5;
			$clp = array();
			$cmetrics = array();
			if(!empty($clpnames)) {
				if(!empty($layout['clipart_leftright'])) {
					foreach($clpnames as $tid=>$clps) {
						foreach ($clps as $clpid => $c) {
							//$c'[{$clp_sq}x{$clp_sq}]' \
							$clpg = $clpgrvt[$tid][$clpid];
							$c_c = <<< EOT
				\( \
					-stroke none \
					-strokewidth 0 \
					$bg \
					$color \
					-trim \
					+repage \
					-density 1200 \
					-size x{$clp_y} \
					-resize {$clp_xx}x{$clp_yy} \
					$c \
				\)
EOT;
							$c_c2 = <<< EOT
			\( \
				-stroke none \
				-strokewidth 0 \
				$bg \
				$color \
				-trim \
				+repage \
				-density 1200 \
				-size x{$clp_y} \
				-resize {$clp_xx}x{$clp_yy} \
				$c \
			\)
EOT;
							$fsize = <<< EOT
{$bp}{$im_bin} \
-stroke none \
-strokewidth 0 \
$bg \
$color \
-trim \
+repage \
-density 1200 \
-size x{$clp_y} \
-resize {$clp_xx}x{$clp_yy} \
$c \
-format "%@" \
info:
EOT;
							if (!empty($_GET['debug_php'])) {
								tpt_dump($fsize);
							}
							$csize = self::exec_command($vars, $fsize, '', '', $s, 'clipart_metrics_' . $clpid, 1);
							//tpt_dump($csize);
							$cmetrics[$clpid] = preg_split('#\+|-#', $csize);
							//tpt_dump($cmetrics[$clpid]);
							$cmetrics[$clpid] = array_shift($cmetrics[$clpid]);
							//tpt_dump($cmetrics[$clpid]);
							$cmetrics[$clpid] = explode('x', $cmetrics[$clpid]);
							//tpt_dump($cmetrics[$clpid]);
							if (empty($cmetrics[$clpid][0]) || empty($cmetrics[$clpid][1])) {
								//tpt_dump('asd');
								//tpt_dump('asd', true);
								$c_c = <<< EOT
				\( \
					-stroke '#FFFFFF' \
					-strokewidth 1 \
					$bg \
					-trim \
					+repage \
					-density 1200 \
					-size x{$clp_y} \
					-resize {$clp_xx}x{$clp_yy} \
					$c \
				\)
EOT;
								$c_c2 = <<< EOT
			\( \
				-stroke '#FFFFFF' \
				-strokewidth 1 \
				$bg \
				-trim \
				+repage \
				-density 1200 \
				-size x{$clp_y} \
				-resize {$clp_xx}x{$clp_yy} \
				$c \
			\)
EOT;

								$fsize = <<< EOT
{$bp}{$im_bin} \
-stroke '#FFFFFF' \
-strokewidth 1 \
$bg \
-trim \
+repage \
-density 1200 \
-size x{$clp_y} \
-resize {$clp_xx}x{$clp_yy} \
$c \
-format "%@" \
info:
EOT;
								if (!empty($_GET['debug_php'])) {
									tpt_dump($fsize);
								}
								$csize = self::exec_command($vars, $fsize, '', '', $s, 'clipart_metrics2_' . $clpid, 1);
								//tpt_dump($csize);
								$cmetrics[$clpid] = preg_split('#\+|-#', $csize);
								//tpt_dump($cmetrics[$clpid]);
								$cmetrics[$clpid] = array_shift($cmetrics[$clpid]);
								$cmetrics[$clpid] = explode('x', $cmetrics[$clpid]);
							}
/*
							$clp[] = <<< EOT
\( \
-size {$clp_x}x{$clp_y} \
xc:'#FFFFFF' \
\( \
-size {$clp_x}x{$clp_y} \
xc:transparent \
$c_c \
-gravity center \
-geometry -1-1 \
-compose Over -composite \
$c_c \
-gravity center \
-geometry -1-0 \
-compose Over -composite \
$c_c \
-gravity center \
-geometry -1+1 \
-compose Over -composite \
$c_c \
-gravity center \
-geometry +0-1 \
-compose Over -composite \
$c_c \
-gravity center \
-geometry +0+0 \
-compose Over -composite \
$c_c \
-gravity center \
-geometry +0+1 \
-compose Over -composite \
$c_c \
-gravity center \
-geometry +1-1 \
-compose Over -composite \
$c_c \
-gravity center \
-geometry +1+0 \
-compose Over -composite \
$c_c \
-gravity center \
-geometry +1+1 \
-compose Over -composite \
$c_c \
-gravity center \
-geometry +0+0 \
-compose DstOut -composite \
\) \
-compose CopyOpacity -composite \
\) \
-trim \
-gravity Center \
-resize x{$clp_y} \
$clpg \
-compose Over -composite
EOT;
*/
							$clpoffsign = $clpoffsigns[$tid][$clpid];
							$clpoffx = ceil($metrics[$msgdata['pname']]['x']/2);
							//if($clpoffsign == "-") {
								//tpt_dump($cmetrics[$clpid], true);
								$clpoffx += $cmetrics[$clpid][0];
							//}
							//$clpoffx = $metrics[$msgdata['pname']]['x'];
							//tpt_dump($cmetrics[$clpid][0]);
							//tpt_dump($cmetrics[$clpid][0], true);
							//tpt_dump($clpg, true);
							$clp[] = <<< EOT
	\( \
		-respect-parenthesis \
		-background transparent \
		-page {$clp_x}x{$clp_y} \
		\
		\( \
			-size {$clp_x}x{$clp_y} \
			xc:transparent \
			\( \
$c_c \
				\( \
				-size {$clp_x}x{$clp_y} \
				xc:'#FFFFFF' \
				\) \
				-compose SrcIn \
				-composite \
			\) \
			-gravity Center \
			-compose Over \
			-composite \
		\) \
		\
		\( \
		-clone 0 \
		-repage -1-1 \
		\) \
		\( \
		-clone 0 \
		-repage -1+0 \
		\) \
		\( \
		-clone 0 \
		-repage -1+1 \
		\) \
		\( \
		-clone 0 \
		-repage +0-1 \
		\) \
		\( \
		-clone 0 \
		-repage +0+0 \
		\) \
		\( \
		-clone 0 \
		-repage +0+1 \
		\) \
		\( \
		-clone 0 \
		-repage +1-1 \
		\) \
		\( \
		-clone 0 \
		-repage +1+0 \
		\) \
		\( \
		-clone 0 \
		-repage +1+1 \
		\) \
		-compose Over \
		-flatten \
		\( \
$c_c2 \
			\( \
			-size {$clp_x}x{$clp_y} \
			xc:white \
			\) \
			-compose SrcIn \
			-composite \
		\) \
		-gravity Center \
		-compose DstOut \
		-composite \
		-geometry {$clpoffsign}{$clpoffx}+0 \
	\) \
	-compose Over -composite
EOT;
						}
					}
				}
			}
			$clp = implode(' \\'."\n", $clp);
			//tpt_dump($clpnames, true);
			//tpt_dump($clipart, true);
			//tpt_dump($clp, true);
			$cXd = $cX*2;
			$cYmd = $cYm*2;

			if (!empty($color)) {
				$msgs[$msgdata['pname']] = <<< EOT
\( \
	-size {$cX}x{$cYm} \
	xc:transparent \
	\( \
		\( \
			-size {$cXd}x{$cYmd} \
			xc:transparent \
			-pointsize $pointsize \
			$stroke \
			$strokewidth \
			$bg \
			\
			$color \
			$font \
			$kern \
			label:$text \
			-trim \
			-gravity center \
			-extent {$cXm}x{$cYm} \
			-compose Over -composite \
			$inner_shadow \
			$inner_glow \
			$drop_shadow \
			$outer_glow \
		\) \
		-trim \
	\) \
	$msggrvt \
	-compose Over -composite \
$clp \
\) \
$pgravity \
-compose Over -composite
EOT;
			} else {
				$msgs[$msgdata['pname']] = <<< EOT
\( \
-size {$cX}x{$cYm} \
xc:transparent \
\( \
\( \
-size {$cXm}x{$cYm} \
xc:transparent \
-pointsize $pointsize \
$stroke \
$strokewidth \
$bg \
\
-fill none \
$font \
$kern \
label:$text \
-gravity center \
-compose Over -composite \
$inner_shadow \
$inner_glow \
$drop_shadow \
$outer_glow \
\) \
-trim \
\) \
$msggrvt \
-compose Over -composite \
$clp \
\) \
$pgravity \
-compose Over -composite
EOT;
			}
		}
		//tpt_dump($metrics, true);

		if(count($msgs) > 1) {
			//$addlabel = '\\( ' . implode(' \\' . "\n" , $msgs) . ' \\)';
			$addlabel =  implode(' \\' . "\n" , $msgs) ;
		} else {
			$addlabel = implode($msgs);
		}


		$command = <<< EOT
{$bp}{$im_bin} \
-respect-parenthesis \
-size {$cX}x{$cY} \
xc:transparent \
$addlabel \

EOT;
/*
		$command = <<< EOT
{$bp}{$im_bin} \
-respect-parenthesis \
-size {$cX}x{$cY} \
xc:transparent \
$addlabel \
-trim \
+repage \
$resize \

EOT;
*/

		return $command;
	}

	static function c_message2_old(&$vars, &$layer, &$out='', &$steps=array()) {
		$color_module = getModule($vars, 'BandColor');
		$msg_module = getModule($vars, 'BandMessage');
		$cpf_module = getModule($vars, 'CustomProductField');
		$fonts_module = getModule($vars, 'BandFont');
		$layouts_module = getModule($vars, 'BandLayout');
		$fonts = $fonts_module->moduleData['id'];
		$clipart_module = getModule($vars, 'BandClipart');


		//$isfront (empty($message['back']) && empty($message['line2'])) {
		$bp = BIN_PATH;
		if(defined('ALT_BIN_PATH')) {
			$bp = ALT_BIN_PATH;
		}
		$im_bin = IMAGEMAGICK_BIN;

		//tpt_dump($layer);
		$layout = (!empty($layer['band_layout'])?intval($layer['band_layout'], 10):(!empty($layer['layout'])?intval($layer['layout'], 10):1));
		$layout = $layouts_module->moduleData['id'][$layout];

		$targets = explode(',', $layer['target']);
		$targets = array_combine($targets, $targets);
		$targets = array_intersect_key($cpf_module->moduleData['id'], $targets);

		$messages = array();
		$m = array();
		$clipart = array();
		foreach($targets as $tid=>$target) {
			if(isset($layer[$target['pname']])) {
				if(!empty($target['text'])) {
					$messages[$tid] = $layer[$target['pname']];
				} else if(!empty($target['clipart'])) {
					$clipart[$tid] = $layer[$target['pname']];
				}
			}
		}

		$ncmessages = array();
		$ncparams = explode('|', $layer['nullcheck_preview_params_ids']);
		foreach($ncparams as $ncparam) {
			$ncp = explode(':', $ncparam);
			if(!empty($cpf_module->moduleData['id'][$ncp[0]]) && !empty($cpf_module->moduleData['id'][$ncp[0]]['text'])) {
				$ncmessages[$ncp[0]] = $cpf_module->moduleData['id'][$ncp[0]];
			}
		}

		//tpt_dump($layer['cX']);
		//tpt_dump($layer['cPR']);
		//tpt_dump($layer['cPL']);
		if(!empty($ncmessages)) {
			$ncmsg = reset($ncmessages);
			//tpt_dump($layer[$ncmsg['pname']]);
			//tpt_dump($layout['text_frontback']);
			//tpt_dump($messages, true);
			if (!empty($layout['text_frontback']) && !empty($ncmessages) && !empty($layer[$ncmsg['pname']])) {
				$imsg = reset($messages);
				$imsg = key($messages);
				$cXex = floor($layer['cX'] / 2);
				$layer['cX'] -= ($cXex+5);
				//tpt_dump($msg_module->moduleData['pname'][$cpf_module->moduleData['id'][$imsg]['pname']]);
				if (!empty($cpf_module->moduleData['id'][$imsg]['pname']) && !empty($msg_module->moduleData['pname'][$cpf_module->moduleData['id'][$imsg]['pname']]['back'])) {
					$layer['cPL'] += ($cXex+5);
				} else {
					$layer['cPR'] += ($cXex+5);
				}
			}
		}
		//tpt_dump($layer['cX']);
		//tpt_dump($layer['cPR']);
		//tpt_dump($layer['cPL']);


		$cX = (!empty($layer['cX'])?intval($layer['cX'], 10):1);
		$cY = (!empty($layer['cY'])?intval($layer['cY'], 10):1);

		$cPL = (!empty($layer['cPL'])?intval($layer['cPL'], 10):0);
		$cPR = (!empty($layer['cPR'])?intval($layer['cPR'], 10):0);
		$cPT = (!empty($layer['cPT'])?intval($layer['cPT'], 10):0);
		$cPB = (!empty($layer['cPB'])?intval($layer['cPB'], 10):0);

		/*
		$ncparams = explode(',', $ncparams[1]);
		$ncparams = array_combine($ncparams, $ncparams);
		$ncparams = array_intersect_key($cpf_module->moduleData['id'], $ncparams);
		*/
		//$layer[$nctrgt['pname']] =

		//tpt_dump($cX, true);


		$font = FONTS_PATH.DIRECTORY_SEPARATOR.(!empty($layer['font'])?$fonts[$layer['font']]['file']:DEFAULT_FONT_NAME);
		$font = <<< EOT
-font '$font'
EOT;


		//$color = '-fill '.((!empty($layer['color']) && ($layer['color'] != 'transparent') && ($layer['color'] != 'none'))?''.escapeshellarg($layer['color']):'none').'';
		//if (!empty($layer['message_color']) && strstr($layer['message_color'], ':')) {
		if ((!empty($layer['color']) && ($layer['color'] != 'transparent') && ($layer['color'] != 'none'))) {
			$fill = escapeshellarg($layer['color']);
			$color = <<< EOT
-fill $fill \
-colorize 100
EOT;
		} else {
			$color = <<< EOT
-fill none
EOT;
		}
		if (!empty($layer['message_color'])) {
			$cprops = $color_module->getColorProps($vars, $layer['message_color']);
			$fill = (!empty($cprops['hex']) ? escapeshellarg('#' . $cprops['hex']) : 'none');
			$color = <<< EOT
-fill $fill \
-colorize 100
EOT;
		}
		$bg = <<< EOT
-background 'transparent'
EOT;


		$stroke = '';
		$strokewidth = '';

		$inner_shadow = '';
		$inner_glow = '';
		$drop_shadow = '';
		$outer_glow = '';


		$resize = '';
		if(!empty($layer['snug_fit_label'])) {
			$resize = <<< EOT
-resize {$cX}x{$cY}
EOT;

		}
		//$gravity = '-gravity center';
		$gravity = '';
		if(!empty($layer['gravity'])) {
			$gravity = escapeshellarg($layer['gravity']);
			$gravity = <<< EOT
-gravity $gravity
EOT;
		}

		if(!empty($layer['stroke'])) {
			$stroke = escapeshellarg($layer['stroke_color']);
			$stroke = <<< EOT
-stroke $stroke
EOT;


			if(!empty($layer['stroke_width'])) {
				$c_strokewidth = intval($layer['stroke_width'], 10)+2;
				$c_strokewidth = <<< EOT
-strokewidth $c_strokewidth
EOT;

				$strokewidth = intval($layer['stroke_width'], 10);
				$strokewidth = <<< EOT
-strokewidth $strokewidth
EOT;
			}
		}

		$s = array();
		$metrics = array();
		$cYm = $cY;
		if (!empty($layout['text_topbottom']) && (count($messages)>1)) {
			$cYm = floor($layer['cY']/count($messages));
		}

		$pointsize = 0;
		$clp_y = min($cX, $cYm);
		foreach($messages as $tid=>$msg) {
			$cXmm = $cX;
			$msgdata = $cpf_module->moduleData['id'][$tid];


			//tpt_dump($clpnames, true);
			//tpt_dump($clipart, true);

			$text = $msg;
			if(empty($text)) {
				$text = 'W';
			}
			//tpt_dump($text);
			$text = ''.escapeshellarg(str_replace('\\', '\\\\', $text)).'';


			foreach ($clipart as $ctid => $clp) {
				$clpdata = $cpf_module->moduleData['id'][$ctid];
				$cmsg = $cpf_module->moduleData['id'][$clpdata['clipart_text_id']];

				if($tid == $cmsg['id']) {
					if(isset($layer[$clpdata['pname']])) {
						if(!empty($layout['clipart_leftright'])) {
							$cXmm -= ($cYm+2);
						}
					}
				}
			}



			$fsize = <<< EOT
{$bp}{$im_bin} \
-size {$cXmm}x{$cYm} \
$stroke \
$strokewidth \
$bg \
\
-fill 'white' \
$font \
label:$text \
-format "%[label:pointsize]|%@" \
info:
EOT;
			if(!empty($_GET['debug_php'])) {
				tpt_dump($fsize);
			}
			$fsize = self::exec_command($vars, $fsize, '', '', $s, $msgdata['pname'], 1);
			$metrics[$msgdata['pname']] = explode('|', $fsize);
			//tpt_dump($metrics, true);
			$metrics[$msgdata['pname']][1] = preg_split('#\+|-#', $metrics[$msgdata['pname']][1]);
			$metrics[$msgdata['pname']][1] = array_shift($metrics[$msgdata['pname']][1]);
			$metrics[$msgdata['pname']][1] = explode('x', $metrics[$msgdata['pname']][1]);
			$metrics[$msgdata['pname']] = array('x'=>$metrics[$msgdata['pname']][1][0], 'y'=>$metrics[$msgdata['pname']][1][1], 'ps'=>$metrics[$msgdata['pname']][0]);

			$dx = $cXmm - $metrics[$msgdata['pname']]['x'];
			$dy = $cYm - $metrics[$msgdata['pname']]['y'];
			$metrics[$msgdata['pname']]['ops'] = $metrics[$msgdata['pname']]['ps'];
			if(false && $dy > 2) {
				$dps = round($metrics[$msgdata['pname']]['ps']) + $dy;
				$fsize2 = <<< EOT
{$bp}{$im_bin} \
-pointsize {$dps} \
$stroke \
$strokewidth \
$bg \
\
-fill 'white' \
$font \
label:$text \
-format "%@" \
info:
EOT;
				if(!empty($_GET['debug_php'])) {
					tpt_dump($fsize2);
				}
				$fsize2 = self::exec_command($vars, $fsize2, '', '', $s, $msgdata['pname'], 1);
				$fsize2 = preg_split('#\+|-#', $fsize2);
				$fsize2 = array_shift($fsize2);
				$fsize2 = explode('x', $fsize2);
				if(($fsize2[0] <= $cXmm) && ($fsize2[1] <= $cYm)) {
					$metrics[$msgdata['pname']]['ops'] = $dps;
				}
			}
			//tpt_dump($metrics, true);
			//tpt_dump($fsize, true);
			if(empty($pointsize) || ($pointsize > $metrics[$msgdata['pname']]['ops'])) {
				$pointsize = $metrics[$msgdata['pname']]['ops'];
			}

			if(empty($clp_y) || (!empty($metrics[$msgdata['pname']]['y']) && ($clp_y > $metrics[$msgdata['pname']]['y']))) {
				$clp_y = $metrics[$msgdata['pname']]['y'];
			}

		}
		//tpt_dump($metrics, true);

		if(empty($pointsize)) {
			$pointsize = 10;
		}
		$msgs = array();
		foreach($messages as $tid=>$msg) {
			$cXm = $cX;
			$msgdata = $cpf_module->moduleData['id'][$tid];
			//$text = implode($layout['text_separator'], $messages);
			$text = $msg;
			if(empty($text)) {
				$text = ' ';
			}

			//tpt_dump($text);
			$text = ''.escapeshellarg(str_replace('\\', '\\\\', $text)).'';

			$pgravity = '';
			if (!empty($layout['text_topbottom']) && (count($messages)>1)) {
				if(!empty($msg_module->moduleData['pname'][$msgdata['pname']]['line2'])) {
					$pgravity = '-gravity South';
				} else {
					$pgravity = '-gravity North';
				}
			}


			if(!empty($layer['inner_shadow'])) {
				//tpt_dump('asd', true);
				$inner_shadow_color = '#333333';
				if(!empty($layer['inner_shadow_color'])) {
					$inner_shadow_color = $layer['inner_shadow_color'];
				}
				$inner_shadow_color = escapeshellarg($inner_shadow_color);
				$inner_shadow_opacity = '';
				if(!empty($layer['inner_shadow_opacity'])) {
					$inner_shadow_opacity = floatval($layer['inner_shadow_opacity']);
					$inner_shadow_opacity = <<< EOT
-alpha set \
-channel a \
-evaluate \
multiply $inner_shadow_opacity \
+channel
EOT;
				}

				$inner_shadow_distance_x = intval($layer['inner_shadow_distance_x'], 10);
				$inner_shadow_distance_x = (($inner_shadow_distance_x>=0)?'+'.$inner_shadow_distance_x:$inner_shadow_distance_x);
				$inner_shadow_distance_y = intval($layer['inner_shadow_distance_y'], 10);
				$inner_shadow_distance_y = (($inner_shadow_distance_y>=0)?'+'.$inner_shadow_distance_y:$inner_shadow_distance_y);
				$inner_shadow = <<< EOT
\( \
-pointsize $pointsize \
-background 'transparent' \
-fill $inner_shadow_color \
$font \
label:$text \
-trim \
-gravity center \
-extent {$cX}x{$cYm} \
\( \
+clone \
-fill 'white' \
-colorize 100 \
\) \
-geometry $inner_shadow_distance_x$inner_shadow_distance_y \
-compose Dst_Out -composite \
\
$inner_shadow_opacity \
\) \
-gravity center \
-geometry +0+0 \
-compose Over -composite
EOT;
			}

			if(!empty($layer['inner_glow'])) {
				$inner_glow_color = '#FFFFFF';
				if(!empty($layer['inner_glow_color'])) {
					$inner_glow_color = $layer['inner_glow_color'];
				}
				$inner_glow_color = escapeshellarg($inner_glow_color);
				$inner_glow_opacity = '';
				if(!empty($layer['inner_glow_opacity'])) {
					$inner_glow_opacity = floatval($layer['inner_glow_opacity']);
					$inner_glow_opacity = <<< EOT
-alpha set \
-channel a \
-evaluate \
multiply $inner_glow_opacity \
+channel
EOT;
				}

				$inner_glow_distance_x = intval($layer['inner_glow_distance_x'], 10);
				$inner_glow_distance_x = (($inner_glow_distance_x>=0)?'+'.$inner_glow_distance_x:$inner_glow_distance_x);
				$inner_glow_distance_y = intval($layer['inner_glow_distance_y'], 10);
				$inner_glow_distance_y = (($inner_glow_distance_y>=0)?'+'.$inner_glow_distance_y:$inner_glow_distance_y);
				$inner_glow = <<< EOT
\( \
-pointsize $pointsize \
-background 'transparent' \
-fill $inner_glow_color \
$font \
label:$text \
-trim \
-gravity center \
-extent {$cX}x{$cYm} \
\( \
+clone \
-fill 'white' \
-colorize 100 \
\) \
-geometry $inner_glow_distance_x$inner_glow_distance_y \
-compose Dst_Out -composite \
\
$inner_glow_opacity \
\) \
-gravity center \
-geometry +0+0 \
-compose Over -composite
EOT;
			}

			if(!empty($layer['drop_shadow'])) {
				$drop_shadow_color = '#333333';
				if(!empty($layer['drop_shadow_color'])) {
					$drop_shadow_color = $layer['drop_shadow_color'];
				}
				$drop_shadow_color = escapeshellarg($drop_shadow_color);
				$drop_shadow_opacity = '';
				if(!empty($layer['drop_shadow_opacity'])) {
					$drop_shadow_opacity = floatval($layer['drop_shadow_opacity']);
					$drop_shadow_opacity = <<< EOT
-alpha set \
-channel a \
-evaluate \
multiply $drop_shadow_opacity \
+channel
EOT;
				}

				$drop_shadow_distance_x = intval($layer['drop_shadow_distance_x'], 10);
				$drop_shadow_cast_x = intval($layer['drop_shadow_distance_x'], 10)*-1;
				$drop_shadow_distance_x = (($drop_shadow_distance_x>=0)?'+'.$drop_shadow_distance_x:$drop_shadow_distance_x);
				$drop_shadow_cast_x = (($drop_shadow_cast_x>=0)?'+'.$drop_shadow_cast_x:$drop_shadow_cast_x);
				$drop_shadow_distance_y = intval($layer['drop_shadow_distance_y'], 10);
				$drop_shadow_cast_y = intval($layer['drop_shadow_distance_y'], 10)*-1;
				$drop_shadow_distance_y = (($drop_shadow_distance_y>=0)?'+'.$drop_shadow_distance_y:$drop_shadow_distance_y);
				$drop_shadow_cast_y = (($drop_shadow_cast_y>=0)?'+'.$drop_shadow_cast_y:$drop_shadow_cast_y);
				$drop_shadow = <<< EOT
\( \
-clone 0 \
\( \
-clone 0 \
-fill $drop_shadow_color \
-colorize 100 \
\) \
\( \
-clone 0 \
-fill 'white' \
-colorize 100 \
\) \
-geometry $drop_shadow_distance_x$drop_shadow_distance_y \
-compose Dst_Out -composite \
\
$drop_shadow_opacity \
\) \
-delete 0 \
-gravity center \
-geometry $drop_shadow_cast_x$drop_shadow_cast_y \
-compose Over -composite
EOT;
			}

			if(!empty($layer['outer_glow'])) {
				$outer_glow_color = '#FFFFFF';
				if(!empty($layer['outer_glow_color'])) {
					$outer_glow_color = $layer['outer_glow_color'];
				}
				$outer_glow_color = escapeshellarg($outer_glow_color);
				$outer_glow_opacity = '';
				if(!empty($layer['outer_glow_opacity'])) {
					$outer_glow_opacity = floatval($layer['outer_glow_opacity']);
					$outer_glow_opacity = <<< EOT
-alpha set \
-channel a \
-evaluate \
multiply $outer_glow_opacity \
+channel
EOT;
				}

				$outer_glow_distance_x = intval($layer['outer_glow_distance_x'], 10);
				$outer_glow_cast_x = intval($layer['outer_glow_distance_x'], 10)*-1;
				$outer_glow_distance_x = (($outer_glow_distance_x>=0)?'+'.$outer_glow_distance_x:$outer_glow_distance_x);
				$outer_glow_cast_x = (($outer_glow_cast_x>=0)?'+'.$outer_glow_cast_x:$outer_glow_cast_x);
				$outer_glow_distance_y = intval($layer['outer_glow_distance_y'], 10);
				$outer_glow_cast_y = intval($layer['outer_glow_distance_y'], 10)*-1;
				$outer_glow_distance_y = (($outer_glow_distance_y>=0)?'+'.$outer_glow_distance_y:$outer_glow_distance_y);
				$outer_glow_cast_y = (($outer_glow_cast_y>=0)?'+'.$outer_glow_cast_y:$outer_glow_cast_y);
				$outer_glow = <<< EOT
\( \
-clone 0 \
\( \
-clone 0 \
-fill $outer_glow_color \
-colorize 100 \
\) \
\( \
-clone 0 \
-fill 'white' \
-colorize 100 \
\) \
-geometry $outer_glow_distance_x$outer_glow_distance_y \
-compose Dst_Out -composite \
\
$outer_glow_opacity \
\) \
-delete 0 \
-gravity center \
-geometry $outer_glow_cast_x$outer_glow_cast_y \
-compose Over -composite
EOT;
			}


			//tpt_dump($clipart);
			//tpt_dump($layout);
			$clp_x = max(min($cX, $cYm), floor(($cX - $metrics[$msgdata['pname']]['x'])/max(1, count($clipart))));
			$clpnames = array();
			$clpgrvt = array();
			$msggrvt = '-gravity Center';
			if (!empty($layout['text_topbottom']) && (count($messages)>1)) {
				foreach ($clipart as $ctid => $clp) {
					$clpdata = $cpf_module->moduleData['id'][$ctid];
					$cmsg = $cpf_module->moduleData['id'][$clpdata['clipart_text_id']];

					if($tid == $cmsg['id']) {
						//tpt_dump('asd');
						//tpt_dump($clpdata['pname'], true);
						if(isset($layer[$clpdata['pname']])) {
							//tpt_dump('asd');
							//tpt_dump($layout, true);
							//tpt_dump($clpdata['pname'], true);
							$clpg = '-gravity East';
							$msggrvt = '-gravity West';
							if(!empty($layout['clipart_leftright'])) {
								$cXm -= ($clp_x);
								//tpt_dump($layer[$clpdata['pname']], true);
								if(empty($clpdata['orientation'])) {
									$clpg = '-gravity West';
									$msggrvt = '-gravity East';
								}
							}

							$clpgrvt[$ctid][$layer[$clpdata['pname']]] = $clpg;
							$clpnames[$ctid][$layer[$clpdata['pname']]] = $clipart_module->getClipartPath($vars, $layer[$clpdata['pname']]);
						}
					}


				}
			} else {
				foreach ($clipart as $ctid => $clp) {
					$clpdata = $cpf_module->moduleData['id'][$ctid];
					$cmsg = $cpf_module->moduleData['id'][$clpdata['clipart_text_id']];

					if($tid == $cmsg['id']) {
						//tpt_dump('asd');
						//tpt_dump($clpdata['pname'], true);
						if(isset($layer[$clpdata['pname']])) {
							//tpt_dump('asd');
							//tpt_dump($layout, true);
							//tpt_dump($clpdata['pname'], true);
							$clpg = '-gravity East';
							$msggrvt = '-gravity West';
							if(!empty($layout['clipart_leftright'])) {
								$cXm -= ($clp_x);
								//tpt_dump($layer[$clpdata['pname']], true);
								if(empty($clpdata['orientation'])) {
									$clpg = '-gravity West';
									$msggrvt = '-gravity East';
								}
							}

							$clpgrvt[$ctid][$layer[$clpdata['pname']]] = $clpg;
							$clpnames[$ctid][$layer[$clpdata['pname']]] = $clipart_module->getClipartPath($vars, $layer[$clpdata['pname']]);
						}
					}


				}
			}


			if(!empty($layout['clipart_leftright']) && (count($clpnames) > 1)) {
				$msggrvt = '-gravity Center';
			}

			//tpt_dump($clpnames);


			/*
			$clp_xx = $clp_x-5;
			$clp_yy = $clp_y-5;
			-size x{$clp_yy} \
			-resize {$clp_xx}x{$clp_yy} \

+clone \
-compose Over -composite \
+clone \
-compose Over -composite \
+clone \
-compose Over -composite \
+clone \
-compose Over -composite


\( \
-background 'transparent' \
-stroke none \
-strokewidth 0 \
$bg \
$color \
-trim \
+repage \
-density 1200 \
-size x{$clp_yy} \
-resize {$clp_xx}x{$clp_yy} \
$c \
\) \
-compose Over \
-composite \
			*/
			$clp_xx = $clp_x-5;
			$clp_yy = $clp_y-5;
			$clp = array();
			$cmetrics = array();
			if(!empty($clpnames)) {
				if(!empty($layout['clipart_leftright'])) {
					foreach($clpnames as $tid=>$clps) {
						foreach ($clps as $clpid => $c) {
							//$c'[{$clp_sq}x{$clp_sq}]' \
							$clpg = $clpgrvt[$tid][$clpid];
							$c_c = <<< EOT
\( \
-stroke none \
-strokewidth 0 \
$bg \
$color \
-trim \
+repage \
-density 1200 \
-size x{$clp_y} \
-resize {$clp_xx}x{$clp_yy} \
$c \
\)
EOT;
							$csize = <<< EOT
{$bp}{$im_bin} \
-stroke none \
-strokewidth 0 \
$bg \
$color \
-trim \
+repage \
-density 1200 \
-size x{$clp_y} \
-resize {$clp_xx}x{$clp_yy} \
$c \
-format "%@" \
info:
EOT;
							if (!empty($_GET['debug_php'])) {
								tpt_dump($csize);
							}
							$csize = self::exec_command($vars, $fsize, '', '', $s, 'clipart_metrics_' . $clpid, 1);
							$cmetrics[$clpid] = preg_split('#\+|-#', $csize);
							$cmetrics[$clpid] = array_shift($cmetrics[$clpid]);
							$cmetrics[$clpid] = explode('x', $cmetrics[$clpid]);
							if (empty($cmetrics[$clpid][0]) || empty($cmetrics[$clpid][1])) {
								$c_c = <<< EOT
\( \
-stroke '#FFFFFF' \
-strokewidth 1 \
$bg \
-trim \
+repage \
-density 1200 \
-size x{$clp_y} \
-resize {$clp_xx}x{$clp_yy} \
$c \
\)
EOT;
							}
							$clp[] = <<< EOT
\( \
-size {$clp_x}x{$clp_y} \
xc:'#FFFFFF' \
\( \
-size {$clp_x}x{$clp_y} \
xc:transparent \
$c_c \
-gravity center \
-geometry -1-1 \
-compose Over -composite \
$c_c \
-gravity center \
-geometry -1-0 \
-compose Over -composite \
$c_c \
-gravity center \
-geometry -1+1 \
-compose Over -composite \
$c_c \
-gravity center \
-geometry +0-1 \
-compose Over -composite \
$c_c \
-gravity center \
-geometry +0+0 \
-compose Over -composite \
$c_c \
-gravity center \
-geometry +0+1 \
-compose Over -composite \
$c_c \
-gravity center \
-geometry +1-1 \
-compose Over -composite \
$c_c \
-gravity center \
-geometry +1+0 \
-compose Over -composite \
$c_c \
-gravity center \
-geometry +1+1 \
-compose Over -composite \
$c_c \
-gravity center \
-geometry +0+0 \
-compose DstOut -composite \
\) \
-compose CopyOpacity -composite \
\) \
$clpg \
-compose Over -composite
EOT;
						}
					}
				}
			}
			$clp = implode(' \\'."\n", $clp);
			//tpt_dump($clpnames, true);
			//tpt_dump($clipart, true);
			//tpt_dump($clp, true);
			$cXd = $cX*2;
			$cYmd = $cYm*2;

			if (empty($color)) {
				$color = '-fill none';
			}
			/*
$inner_shadow \
$inner_glow \
$drop_shadow \
$outer_glow \
			 */
			$msgmask = <<< EOT
\( \
-size {$cXd}x{$cYmd} \
xc:transparent \
-pointsize $pointsize \
$stroke \
$strokewidth \
$bg \
\
-fill 'white' \
$font \
label:$text \
-gravity center \
-compose Over -composite \
-trim \
-resize {$cXm}x{$cYm} \
-extent {$cXm}x{$cYm} \
\)
EOT;

			$msgs[$msgdata['pname']] = <<< EOT
$msgmask \
\( \
-clone 0 \
$color \
\) \
\( \
-clone 0 \
-size {$cXm}x{$cYm} \
xc:transparent \
$drop_shadow \
$outer_glow \
\) \
-delete 0 \
+repage \
-flatten
EOT;

		}
		//tpt_dump($metrics, true);

		if(count($msgs) > 1) {
			//$addlabel = '\\( ' . implode(' \\' . "\n" , $msgs) . ' \\)';
			$addlabel =  implode(' \\' . "\n" , $msgs) ;
		} else {
			$addlabel = implode($msgs);
		}



		$command = <<< EOT
{$bp}{$im_bin} \
-respect-parenthesis \
-background transparent \
$addlabel \
-trim \
+repage \
$resize \

EOT;

		return $command;
	}


	static function cc_e_opacity(&$vars, $opacity, $cc_source='') {
		$opacity = floatval($opacity);

		$command = <<< EOT
\( \
$cc_source
	-alpha set \
	-channel a \
	-evaluate \
	multiply $opacity \
	+channel \
\) \
EOT;
		return $command;
	}
	static function cc_e_color_overlay(&$vars, $color='#FFFFFF', $cc_source='') {
		$color = '-fill '.escapeshellarg($color);

		$command = <<< EOT
\( \
$cc_source
	\( -clone 0 $color -colorize 100 \) \
	-delete 0 \
\) \
EOT;
		return $command;
	}
	static function cc_e_shadow_inner(&$vars, $width, $spread, $color, $opacity, $cc_source='') {
		$command = array();

		$width_top = (isset($width['top'])?$width['top']:$width[0]);
		if(!empty($width_top)) {
			for($i=1; $i<=$width_top; $i++) {
				$comp = <<< EOT
\( \
$cc_source
	\( \
		-clone 0 \
		-geometry +0+$i \
	\) \
	-compose DstOut \
	-composite \
\) \
EOT;
				$command[] = self::cc_e_opacity($vars, (isset($opacity['top'])?$opacity['top']:$opacity[0]), self::cc_e_color_overlay($vars, (isset($color['top'])?$color['top']:$color[0]), $comp));
			}
		}

		$width_right = (isset($width['right'])?$width['right']:$width[1]);
		if(!empty($width_right)) {
			for($i=1; $i<=$width_right; $i++) {
				$comp = <<< EOT
\( \
$cc_source
	\( \
		-clone 0 \
		-geometry -$i+0 \
	\) \
	-compose DstOut \
	-composite \
\) \
EOT;
				$command[] = self::cc_e_opacity($vars, (isset($opacity['right'])?$opacity['right']:$opacity[1]), self::cc_e_color_overlay($vars, (isset($color['right'])?$color['right']:$color[1]), $comp));
			}
		}

		$width_bottom = (isset($width['bottom'])?$width['bottom']:$width[2]);
		if(!empty($width_bottom)) {
			for($i=1; $i<=$width_bottom; $i++) {
				$comp = <<< EOT
\( \
$cc_source
	\( \
		-clone 0 \
		-geometry +0-$i \
	\) \
	-compose DstOut \
	-composite \
\) \
EOT;
				$command[] = self::cc_e_opacity($vars, (isset($opacity['bottom'])?$opacity['bottom']:$opacity[2]), self::cc_e_color_overlay($vars, (isset($color['bottom'])?$color['bottom']:$color[2]), $comp));
			}
		}

		$width_left = (isset($width['left'])?$width['left']:$width[3]);
		if(!empty($width_left)) {
			for($i=1; $i<=$width_left; $i++) {
				$comp = <<< EOT
\( \
$cc_source
	\( \
		-clone 0 \
		-geometry +$i+0 \
	\) \
	-compose DstOut \
	-composite \
\) \
EOT;
				$command[] = self::cc_e_opacity($vars, (isset($opacity['left'])?$opacity['left']:$opacity[3]), self::cc_e_color_overlay($vars, (isset($color['left'])?$color['left']:$color[3]), $comp));
			}
		}


		$command = implode("\n", $command);
		$command = <<< EOT
\( \
$command
-background transparent \
-compose Over \
-flatten \
\) \
EOT;

		return $command;

	}
	static function cc_e_stroke_inner(&$vars, $width, $color='#FFFFFF', $cc_source='') {
		$width = abs(intval($width, 10));
		$stroke = '-stroke '.escapeshellarg($color);

		$command = <<< EOT
\( \
$cc_source
	\( \
		-clone 0 \
		-clone 0 -geometry -$width-0 \
		-compose DstOut \
		-composite \
	\) \
	\( \
		-clone 0 \
		-clone 0 -geometry -$width-$width \
		-compose DstOut \
		-composite \
	\) \
	\( \
		-clone 0 \
		-clone 0 -geometry -0-$width \
		-compose DstOut \
		-composite \
	\) \
	\
	\( \
		-clone 0 \
		-clone 0 -geometry +$width-0 \
		-compose DstOut \
		-composite \
	\) \
	\( \
		-clone 0 \
		-clone 0 -geometry +$width+$width \
		-compose DstOut \
		-composite \
	\) \
	\( \
		-clone 0 \
		-clone 0 -geometry +0+$width \
		-compose DstOut \
		-composite \
	\) \
	-delete 0 \
	-compose Over \
	-flatten \
\) \
EOT;

		return $command;
	}
	static function cc_m_text(&$vars, $x, $y, $text, $params=array(), $cc_effects=array(), &$s=array(), $stepname='cc_m_text') {
		if(!empty($_GET['debug_php'])) {
			tpt_dump('cc_m_text');
		}
		$fonts_module = getModule($vars, 'BandFont');
		$fonts = $fonts_module->moduleData['id'];

		$background = '-background transparent';
		if(isset($params['background']) && !empty($params['background'])) {
			$background = '-background '.escapeshellarg($params['background']).'';
		}
		$fill = '-fill \'#FFF\'';
		if(isset($params['fill']) && !empty($params['fill'])) {
			$background = '-fill '.escapeshellarg($params['fill']).'';
		}

		$kern = '';
		if(isset($params['kern']) && !empty($params['kern'])) {
			$kern = '-kern '.escapeshellarg($params['kern']).'';
		}

		$font = '-font \''.FONTS_PATH.DIRECTORY_SEPARATOR.(!empty($params['font'])?$fonts[$params['font']]['file']:DEFAULT_FONT_NAME).'\'';

		$label = 'label:'.escapeshellarg($text).'';

		if(!empty($cc_effects)) {
			$cc_effects = implode("\n".$cc_effects);
		} else {
			$cc_effects = '\\';
		}

		$metrics = self::a_get_text_metrics($vars, $x, $y, $text, $params, $cc_effects, null, null, $s, $stepname.'->a_get_text_metrics0', $stepname.'->a_get_text_metrics1');
		$pointsize = $metrics['ps'];

		$msgx_double = $x*2;
		$msgy_double = $y*2;

		$command = <<< EOT
\( \
	\( \
		-size {$msgx_double}x{$msgy_double} \
		-gravity Center \
		$font \
		-pointsize $pointsize \
		$kern \
		$background \
		-fill '#FFFFFF' \
		$label \
		-trim \
		+repage \
		-extent {$x}x{$y} \
	\) \
$cc_effects
\) \
EOT;
		return array(
			'command'=>$command,
			'metrics'=>$metrics
		);

	}
	static function cc_m_text_ps(&$vars, $x, $y, $pointsize, $space_width, $text, $params=array(), $cc_effects=array(), &$s=array(), $stepname='cc_m_text') {
		if(!empty($_GET['debug_php'])) {
			tpt_dump('cc_m_text_ps');
		}
		$fonts_module = getModule($vars, 'BandFont');
		$fonts = $fonts_module->moduleData['id'];

		$bp = BIN_PATH;
		if(defined('ALT_BIN_PATH')) {
			$bp = ALT_BIN_PATH;
		}
		$im_bin = IMAGEMAGICK_BIN;

		$background = '-background transparent';
		if(isset($params['background']) && !empty($params['background'])) {
			$background = '-background '.escapeshellarg($params['background']).'';
		}
		$fill = '-fill \'#FFF\'';
		if(isset($params['fill']) && !empty($params['fill'])) {
			$background = '-fill '.escapeshellarg($params['fill']).'';
		}

		$kern = '';
		if(isset($params['kern']) && !empty($params['kern'])) {
			$kern = '-kern '.escapeshellarg($params['kern']).'';
		}

		$font = '-font \''.FONTS_PATH.DIRECTORY_SEPARATOR.(!empty($params['font'])?$fonts[$params['font']]['file']:DEFAULT_FONT_NAME).'\'';

		if(!empty($cc_effects)) {
			$cc_effects = implode("\n".$cc_effects);
		} else {
			$cc_effects = '\\';
		}

		preg_match('#^(\s*)(.*?)(\s*)$#', $text, $m);
		if(!empty($_GET['debug_php'])) {
			tpt_dump($text);
			tpt_dump($m[1]);
			tpt_dump($m[2]);
			tpt_dump($m[3]);
		}
		$spaces_left = strlen($m[1]);
		$spaces_right = strlen($m[3]);
		$text = trim($text);
		if($text === '') {
			$text = ' ';
		}
		$label = 'label:'.escapeshellarg($text).'';
		$space_left = $spaces_left*$space_width;
		$space_right = $spaces_right*$space_width;

		$msgx_double = $x*2;
		$msgy_double = $y*2;

		$fsize = <<< EOT
{$bp}{$im_bin} \
-size {$msgx_double}x{$msgy_double} \
-gravity Center \
$font \
-pointsize {$pointsize} \
$kern \
$background \
-fill '#FFFFFF' \
$label \
-trim \
+repage \
-format "%@" \
info:
EOT;
		if(!empty($_GET['debug_php'])) {
			tpt_dump($fsize);
		}
		$fsize = self::exec_command($vars, $fsize, '', '', $s, 'cc_m_text_ps-metrics', 1);
		if(!empty($_GET['debug_php'])) {
			tpt_dump($fsize);
		}
		//tpt_dump($metrics, true);
		$metrics = preg_split('#\+|-#', $fsize);
		$metrics = array_shift($metrics);
		$metrics = explode('x', $metrics);
		$metrics_x = $metrics[0]+$space_left+$space_right;
		$metrics = array('x'=>$metrics_x, 'xt'=>$metrics[0], 'y'=>$metrics[1], 'proportion'=>(!empty($metrics[1])?$metrics_x/$metrics[1]:0), 'ps'=>$pointsize, 'space_width'=>$space_width, 'space_left'=>$space_left, 'space_right'=>$space_right);
		$xt = $metrics['xt'];
		$xleft = $xt+$space_left;
		$xfull = $xleft+$space_right;

		$command = <<< EOT
\( \
	\( \
		-size {$msgx_double}x{$msgy_double} \
		-gravity Center \
		$font \
		-pointsize {$pointsize} \
		$kern \
		$background \
		-fill '#FFFFFF' \
		$label \
		-trim \
		+repage \
		-gravity East \
		-extent {$xleft}x{$y} \
		-gravity West \
		-extent {$xfull}x{$y} \
	\) \
$cc_effects
\) \
EOT;
		return array(
			'command'=>$command,
			'metrics'=>$metrics
		);

	}

	static function get_text_metrics(&$vars, $cid, $text, $x, $y, $stroke, $strokewidth, $bg, $font, $kern, &$s) {
		if(!empty($_GET['debug_php'])) {
			tpt_dump('get_text_metrics');
		}
		$cpf_module = getModule($vars, 'CustomProductField');
		$fld = $cpf_module->moduleData['id'][$cid];

		$bp = BIN_PATH;
		if(defined('ALT_BIN_PATH')) {
			$bp = ALT_BIN_PATH;
		}
		$im_bin = IMAGEMAGICK_BIN;

		$yd = $y*2;
		if (empty($text)) {
			$text = 'W';
		}
		$label = 'label:'.escapeshellarg($text).'';

		$fsize = <<< EOT
{$bp}{$im_bin} \
-size {$x}x{$y} \
-gravity Center \
$stroke \
$strokewidth \
$bg \
\
-fill 'white' \
$font \
$kern \
$label \
-format "%[label:pointsize]|%@" \
info:
EOT;
		if(!empty($_GET['debug_php'])) {
			tpt_dump($fsize);
		}
		$fsize = self::exec_command($vars, $fsize, '', '', $s, $fld['pname'], 1);
		if(!empty($_GET['debug_php'])) {
			tpt_dump($fsize);
		}
		$metrics = array();
		$metrics = explode('|', $fsize);
		//tpt_dump($metrics, true);
		$metrics[1] = preg_split('#\+|-#', $metrics[1]);
		$metrics[1] = array_shift($metrics[1]);
		$metrics[1] = explode('x', $metrics[1]);
		$metrics = array('x'=>$metrics[1][0], 'y'=>$metrics[1][1], 'proportion'=>$metrics[1][0]/$metrics[1][1], 'ps'=>$metrics[0]);

		$dx = $x - $metrics['x'];
		$dy = $y - $metrics['y'];

		$low = -9;
		$high = 18;
		$i = $low;
		$fsize2 = array();
		do {
			$ps = $metrics['ps']+$i;
			$fsize2[] = <<< EOT
-size {$x}x{$yd} \
-gravity Center \
-pointsize {$ps} \
$stroke \
$strokewidth \
$bg \
\
-fill 'white' \
$font \
$kern \
$label \
-format "\\n%@" \
EOT;
			$i++;
		} while(($i <= $high));
		$fsize2 = implode("\n", $fsize2);
		$fsize2 = <<< EOT
{$bp}{$im_bin} \
$fsize2
info:
EOT;
		if (!empty($_GET['debug_php'])) {
			tpt_dump($fsize2);
		}
		$fsize2 = self::exec_command($vars, $fsize2, '', '', $s, $fld['pname'], 1);
		if (!empty($_GET['debug_php'])) {
			tpt_dump($fsize2);
		}
		$fsize2 = preg_split('#\R#', trim($fsize2));
		$fskeys = range($low,$high);
		$fsize2 = array_combine($fskeys, $fsize2);
		$ps = $metrics['ps'];
		//tpt_dump($fsize2, true);
		if (!empty($_GET['debug_php'])) {
			tpt_dump('$x='.$x.', $y='.$y);
		}
		foreach($fsize2 as $i=>$fs) {
			$ps2 = $ps+$i;
			$fs2 = preg_split('#\+|-#', $fs);
			$fs2 = array_shift($fs2);
			$fs2 = explode('x', $fs2);
			$dx = $x - $fs2[0];
			$dy = $y - $fs2[1];
			//tpt_dump($clp_y);
			if (($dx >= 0) && ($dy >= 0)) {
				$metrics = array('x' => $fs2[0], 'y' => $fs2[1], 'proportion'=>$fs2[0]/$fs2[1], 'ps' => $ps2);
			}

			if (!empty($_GET['debug_php'])) {
				tpt_dump($i.': '.$metrics['x'].'x'.$metrics['y'].' '.$metrics['proportion'].' '.$metrics['ps']);
			}
			if(($dx <= 0) || ($dy <= 0)) {
				if (!empty($_GET['debug_php'])) {
					tpt_dump('BREAK');
				}
				break;
			}

		}

		return $metrics;
	}
	static function a_get_text_metrics(&$vars, $x, $y, $text, $params=array(), $cc_effects=array(), $low=null, $high=null, &$s=array(), $stepname0='a_get_text_metrics0', $stepname1='a_get_text_metrics1') {
		if(!empty($_GET['debug_php'])) {
			tpt_dump('a_get_text_metrics');
		}
		$fonts_module = getModule($vars, 'BandFont');
		$fonts = $fonts_module->moduleData['id'];

		$bp = BIN_PATH;
		if(defined('ALT_BIN_PATH')) {
			$bp = ALT_BIN_PATH;
		}
		$im_bin = IMAGEMAGICK_BIN;

		$kern = '';
		if(isset($params['kern']) && !empty($params['kern'])) {
			$kern = '-kern '.escapeshellarg($params['kern']).'';
		}

		$font = '-font \''.FONTS_PATH.DIRECTORY_SEPARATOR.(!empty($params['font'])?$fonts[$params['font']]['file']:DEFAULT_FONT_NAME).'\'';

		$yd = $y*2;
		if (empty($text)) {
			$text = 'W';
		}
		$label = 'label:'.escapeshellarg($text).'';

		$fsize = <<< EOT
{$bp}{$im_bin} \
-size {$x}x{$y} \
-gravity Center \
-background 'transparent' \
-fill 'white' \
$font \
$kern \
$label \
-format "%[label:pointsize]|%@" \
info:
EOT;
		if(!empty($_GET['debug_php'])) {
			tpt_dump($fsize);
		}
		$fsize = self::exec_command($vars, $fsize, '', '', $s, $stepname0, 1);
		if(!empty($_GET['debug_php'])) {
			tpt_dump($fsize);
		}
		$metrics = array();
		$metrics = explode('|', $fsize);
		//tpt_dump($metrics, true);
		$metrics[1] = preg_split('#\+|-#', $metrics[1]);
		$metrics[1] = array_shift($metrics[1]);
		$metrics[1] = explode('x', $metrics[1]);
		$metrics = array('x'=>$metrics[1][0], 'y'=>$metrics[1][1], 'proportion'=>(!empty($metrics[1][1])?$metrics[1][0]/$metrics[1][1]:0), 'ps'=>$metrics[0]);

		$dx = $x - $metrics['x'];
		$dy = $y - $metrics['y'];

		$low = (!is_null($low)?$low:(isset($vars['config']['pGenerator']['defaults']['a_get_text_metrics']['loop_low'])?$vars['config']['pGenerator']['defaults']['a_get_text_metrics']['loop_low']:-9));
		$high = (!is_null($high)?$high:(isset($vars['config']['pGenerator']['defaults']['a_get_text_metrics']['loop_high'])?$vars['config']['pGenerator']['defaults']['a_get_text_metrics']['loop_high']:12));
		if(($dx > 0) && ($dy > 0)) {
			$low = 0;
		}
		$i = $low;
		$fsize2 = array();
		do {
			$ps = $metrics['ps']+$i;
			$fsize2[] = <<< EOT
-gravity Center \
-background 'transparent' \
-pointsize {$ps} \
-fill 'white' \
$font \
$kern \
$label \
-format "\\n%@" \
EOT;
			$i++;
		} while(($i <= $high));
		$fsize2 = implode("\n", $fsize2);
		$fsize2 = <<< EOT
{$bp}{$im_bin} \
$fsize2
info:
EOT;
		if (!empty($_GET['debug_php'])) {
			tpt_dump($fsize2);
		}
		$fsize2 = self::exec_command($vars, $fsize2, '', '', $s, $stepname1, 1);
		if (!empty($_GET['debug_php'])) {
			tpt_dump($fsize2);
		}
		$fsize2 = preg_split('#\R#', trim($fsize2));
		$fskeys = range($low,$high);
		$fsize2 = array_combine($fskeys, $fsize2);
		$ps = $metrics['ps'];
		//tpt_dump($fsize2, true);
		if (!empty($_GET['debug_php'])) {
			tpt_dump('$x='.$x.', $y='.$y);
		}
		foreach($fsize2 as $i=>$fs) {
			$ps2 = $ps+$i;
			$fs2 = preg_split('#\+|-#', $fs);
			$fs2 = array_shift($fs2);
			$fs2 = explode('x', $fs2);
			$dx = $x - $fs2[0];
			$dy = $y - $fs2[1];
			//tpt_dump($clp_y);
			if (($dx >= 0) && ($dy >= 0)) {
				$metrics = array('x' => $fs2[0], 'y' => $fs2[1], 'proportion'=>(!empty($fs2[1])?$fs2[0]/$fs2[1]:0), 'ps' => $ps2);
			}

			if (!empty($_GET['debug_php'])) {
				tpt_dump($i.': '.$metrics['x'].'x'.$metrics['y'].' '.$metrics['proportion'].' '.$metrics['ps']);
			}
			if(($dx <= 0) || ($dy <= 0)) {
				if (!empty($_GET['debug_php'])) {
					tpt_dump('BREAK');
				}
				break;
			}

		}


		return $metrics;
	}
	static function a_get_text_metrics2(&$vars, $x, $y, $text, $params=array(), $cc_effects=array(), $low=null, $high=null, &$s=array(), $stepname0='a_get_text_metrics0', $stepname1='a_get_text_metrics1') {
		if(!empty($_GET['debug_php'])) {
			tpt_dump('a_get_text_metrics');
			tpt_dump('x: '.$x);
			tpt_dump('y: '.$y);
		}
		$fonts_module = getModule($vars, 'BandFont');
		$fonts = $fonts_module->moduleData['id'];

		$bp = BIN_PATH;
		if(defined('ALT_BIN_PATH')) {
			$bp = ALT_BIN_PATH;
		}
		$im_bin = IMAGEMAGICK_BIN;

		$kern = '';
		if(isset($params['kern']) && !empty($params['kern'])) {
			$kern = '-kern '.escapeshellarg($params['kern']).'';
		}

		$font = '-font \''.FONTS_PATH.DIRECTORY_SEPARATOR.(!empty($params['font'])?$fonts[$params['font']]['file']:DEFAULT_FONT_NAME).'\'';

		preg_match('#^(\s*)(.*?)(\s*)$#', $text, $m);
		if(!empty($_GET['debug_php'])) {
			tpt_dump($text);
			tpt_dump($m[1]);
			tpt_dump($m[2]);
			tpt_dump($m[3]);
		}
		$spaces_left = strlen($m[1]);
		$spaces_right = strlen($m[3]);
		$text = str_repeat($vars['config']['pGenerator']['space_character_replace'], $spaces_left).$m[2].str_repeat($vars['config']['pGenerator']['space_character_replace'], $spaces_right);
		if($text === '') {
			$text = ' ';
		}
		$label = 'label:'.escapeshellarg($text).'';
		$text_trim = trim($text);
		if($text_trim === '') {
			$text_trim = ' ';
		}
		$label_trim = 'label:'.escapeshellarg($text_trim).'';

		$fsize = <<< EOT
{$bp}{$im_bin} \
-size {$x}x{$y} \
-gravity Center \
-background 'transparent' \
-fill 'white' \
$font \
$kern \
$label \
-format "%[label:pointsize]|%@" \
info:
EOT;
		if(!empty($_GET['debug_php'])) {
			tpt_dump($fsize);
		}
		$fsize = self::exec_command($vars, $fsize, '', '', $s, $stepname0, 1);
		if(!empty($_GET['debug_php'])) {
			tpt_dump($fsize);
		}
		$metrics = array();
		$metrics = explode('|', $fsize);
		//tpt_dump($metrics, true);
		$metrics[1] = preg_split('#\+|-#', $metrics[1]);
		$metrics[1] = array_shift($metrics[1]);
		$metrics[1] = explode('x', $metrics[1]);
		$metrics = array('x'=>$metrics[1][0], 'y'=>$metrics[1][1], 'proportion'=>(!empty($metrics[1][1])?$metrics[1][0]/$metrics[1][1]:0), 'ps'=>$metrics[0]);

		$space_label = 'label:'.escapeshellarg(' ').'';

		$dx = $x - $metrics['x'];
		$dy = $y - $metrics['y'];

		$low = (!is_null($low)?$low:(isset($vars['config']['pGenerator']['defaults']['a_get_text_metrics']['loop_low'])?$vars['config']['pGenerator']['defaults']['a_get_text_metrics']['loop_low']:-9));
		$high = (!is_null($high)?$high:(isset($vars['config']['pGenerator']['defaults']['a_get_text_metrics']['loop_high'])?$vars['config']['pGenerator']['defaults']['a_get_text_metrics']['loop_high']:12));
		/*
		if(($dx > 0) && ($dy > 0)) {
			$low = 0;
		}
		*/
		$temp_ps = $metrics['ps'];
		$xd = $x*2;
		$yd = $y*2;
		$i = $low;
		$fsize2 = array();
		do {
			$ps = $temp_ps+$i;

			$space_width = <<< EOT
{$bp}{$im_bin} \
-gravity Center \
-background 'transparent' \
-pointsize {$ps} \
-fill 'white' \
$font \
$kern \
$space_label \
-format "%w" \
info:
EOT;
			if(!empty($_GET['debug_php'])) {
				tpt_dump($space_width);
			}
			$space_width = self::exec_command($vars, $space_width, '', '', $s, $stepname0.'-'.$i.'-space-width', 1);
			if(!empty($_GET['debug_php'])) {
				tpt_dump($space_width);
			}

			$fsize2 = <<< EOT
{$bp}{$im_bin} \
-size {$xd}x{$yd} \
-gravity Center \
-background 'transparent' \
-pointsize {$ps} \
-fill 'white' \
$font \
$kern \
$label_trim \
-format "%@" \
info:
EOT;
			if (!empty($_GET['debug_php'])) {
				tpt_dump($fsize2);
			}
			$fsize2 = self::exec_command($vars, $fsize2, '', '', $s, $stepname1, 1);
			if (!empty($_GET['debug_php'])) {
				tpt_dump($fsize2);
			}
			$fs2 = preg_split('#\+|-#', $fsize2);
			$fs2 = array_shift($fs2);
			$fs2 = explode('x', $fs2);
			$dx = $x - ($fs2[0] + $spaces_left*$space_width + $spaces_right*$space_width);
			$dy = $y - $fs2[1];
			if (($dx >= 0) && ($dy >= 0)) {
				$metrics = array('x' => $fs2[0], 'y' => $fs2[1], 'proportion'=>(!empty($fs2[1])?$fs2[0]/$fs2[1]:0), 'ps' => $ps, 'space_width'=>$space_width);
				if (!empty($_GET['debug_php'])) {
					tpt_dump($i.': target: '.$x.'x'.$y.', metrics: '.(!empty($spaces_left)?'('.$spaces_left.'*'.$space_width.'='.$spaces_left*$space_width.')+':'').$metrics['x'].(!empty($spaces_right)?'+('.$spaces_right.'*'.$space_width.'='.$spaces_right*$space_width.')':'').'x'.$metrics['y'].', proportion: '.$metrics['proportion'].', ps: '.$metrics['ps'].', space_width: '.$metrics['space_width']);
				}
			} else {
				if (!empty($_GET['debug_php'])) {
					tpt_dump('BREAK');
				}
				break;
			}
			$i++;
		} while(($i <= $high));


		return $metrics;
	}
	static function a_get_clipart_metrics($vars, $ctid, $clp, $x, $y) {
		if(!empty($_GET['debug_php'])) {
			tpt_dump('get_clipart_metrics');
		}

		$cpf_module = getModule($vars, 'CustomProductField');
		$clipart_module = getModule($vars, 'BandClipart');

		$clpdata = $cpf_module->moduleData['id'][$ctid];

		$bp = BIN_PATH;
		if(defined('ALT_BIN_PATH')) {
			$bp = ALT_BIN_PATH;
		}
		$im_bin = IMAGEMAGICK_BIN;

		$c = $clipart_module->getClipartPath($vars, $clp, TPT_RESOURCE_DIR.DIRECTORY_SEPARATOR.'edited_clipart');
		if(!empty($_GET['debug_php'])) {
			tpt_dump('CLIPART: ($ctid: '.$ctid.', $pname: '.$clpdata['pname'].', clp: '.$c.')');
		}

		$fsize = <<< EOT
{$bp}{$im_bin} \
-density 1200 \
-resize {$x}x{$y} \
$c \
-format "%@" \
info:
EOT;
		if(!empty($_GET['debug_php'])) {
			tpt_dump($fsize);
		}
		$fsize = self::exec_command($vars, $fsize, '', '', $s, 'size_'.$clpdata['pname'], 1);
		if(!empty($_GET['debug_php'])) {
			tpt_dump($fsize);
		}
		$metrics = preg_split('#\+|-#', $fsize);
		$metrics = array_shift($metrics);
		$metrics = explode('x', $metrics);
		$metrics = array('x'=>$metrics[0], 'y'=>$metrics[1], 'proportion'=>$metrics[0]/$metrics[1]);
		if(!empty($_GET['debug_php'])) {
			tpt_dump($metrics['x'].'/'.$metrics['y'].'='.$metrics['proportion']);
		}

		return $metrics;
	}

	static function get_cYelement(&$vars, $cY, $msgid, $layout, $layer) {
		$cpf_module = getModule($vars, 'CustomProductField');
		$cYelement = $cY;

		if (!empty($layout['text_topbottom'])) {
			if(
				(($cpf_module->moduleData['id'][$msgid]['pname'] == 'txt1') && (isset($layer[$cpf_module->moduleData['pname']['txt3']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp5']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp7']['pname']])))
				||
				(($cpf_module->moduleData['id'][$msgid]['pname'] == 'txt3') && (isset($layer[$cpf_module->moduleData['pname']['txt1']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp1']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp3']['pname']])))
				||
				(($cpf_module->moduleData['id'][$msgid]['pname'] == 'txt2') && (isset($layer[$cpf_module->moduleData['pname']['txt4']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp6']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp8']['pname']])))
				||
				(($cpf_module->moduleData['id'][$msgid]['pname'] == 'txt4') && (isset($layer[$cpf_module->moduleData['pname']['txt2']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp2']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp4']['pname']])))
			) {
				$cYelement = floor($cY / 2);
				if (!empty($_GET['debug_php'])) {
					tpt_dump('$cYelement = floor($cY / 2); ' . $cYelement . ' = floor(' . $layer['cY'] . '/2);');
				}
			}
		}

		return $cYelement;
	}
	
	static function get_element_gravity(&$vars, $layout, $layer, $elmid) {
		$cpf_module = getModule($vars, 'CustomProductField');
		
		$lgravity = json_decode($layout['gravity'], true);
		$pgravity = '-gravity Center';
		if (($cpf_module->moduleData['id'][$elmid]['pname'] == 'txt1')) {
			if(isset($layer[$cpf_module->moduleData['pname']['txt3']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp5']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp7']['pname']])) {
				if(isset($layer[$cpf_module->moduleData['pname']['txt2']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp2']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp4']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['txt4']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp6']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp8']['pname']])) {
					$pgravity = '-gravity '.$lgravity['msg1']['two_line_left_right'];
				} else {
					$pgravity = '-gravity '.$lgravity['msg1']['two_line'];
				}
				/*
				if (!empty($layout['text_topbottom'])) {
					$cYelement = floor($layer['cY'] / 2);
					if (!empty($_GET['debug_php'])) {
						tpt_dump('$cYelement = floor($layer[\'cY\']/2); ' . $cYelement . ' = floor(' . $layer['cY'] . '/2);');
					}
				}
				*/
			} else {
				if(isset($layer[$cpf_module->moduleData['pname']['txt2']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp2']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp4']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['txt4']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp6']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp8']['pname']])) {
					$pgravity = '-gravity '.$lgravity['msg1']['left_right'];
				} else {
					$pgravity = '-gravity '.$lgravity['msg1']['single'];
				}
			}
		} else if (($cpf_module->moduleData['id'][$elmid]['pname'] == 'txt2')) {
			if(isset($layer[$cpf_module->moduleData['pname']['txt4']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp6']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp8']['pname']])) {
				if(isset($layer[$cpf_module->moduleData['pname']['txt1']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp1']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp3']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['txt3']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp5']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp7']['pname']])) {
					$pgravity = '-gravity '.$lgravity['msg2']['two_line_left_right'];
				} else {
					$pgravity = '-gravity '.$lgravity['msg2']['two_line'];
				}
				/*
				if (!empty($layout['text_topbottom'])) {
					$cYelement = floor($layer['cY'] / 2);
					if (!empty($_GET['debug_php'])) {
						tpt_dump('$cYelement = floor($layer[\'cY\']/2); ' . $cYelement . ' = floor(' . $layer['cY'] . '/2);');
					}
				}
				*/
			} else {
				if(isset($layer[$cpf_module->moduleData['pname']['txt1']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp1']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp3']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['txt3']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp5']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp7']['pname']])) {
					$pgravity = '-gravity '.$lgravity['msg2']['left_right'];
				} else {
					$pgravity = '-gravity '.$lgravity['msg2']['single'];
				}
			}
		} else if (($cpf_module->moduleData['id'][$elmid]['pname'] == 'txt3')) {
			if(isset($layer[$cpf_module->moduleData['pname']['txt1']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp1']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp3']['pname']])) {
				if(isset($layer[$cpf_module->moduleData['pname']['txt2']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp2']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp4']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['txt4']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp6']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp8']['pname']])) {
					$pgravity = '-gravity '.$lgravity['msg3']['two_line_left_right'];
				} else {
					$pgravity = '-gravity '.$lgravity['msg3']['two_line'];
				}
				/*
				if (!empty($layout['text_topbottom'])) {
					$cYelement = floor($layer['cY'] / 2);
					if (!empty($_GET['debug_php'])) {
						tpt_dump('$cYelement = floor($layer[\'cY\']/2); ' . $cYelement . ' = floor(' . $layer['cY'] . '/2);');
					}
				}
				*/
			} else {
				if(isset($layer[$cpf_module->moduleData['pname']['txt2']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp2']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp4']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['txt4']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp6']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp8']['pname']])) {
					$pgravity = '-gravity '.$lgravity['msg3']['left_right'];
				} else {
					$pgravity = '-gravity '.$lgravity['msg3']['single'];
				}
			}
		} else if (($cpf_module->moduleData['id'][$elmid]['pname'] == 'txt4')) {
			if(isset($layer[$cpf_module->moduleData['pname']['txt2']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp2']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp4']['pname']])) {
				if(isset($layer[$cpf_module->moduleData['pname']['txt1']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp1']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp3']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['txt3']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp5']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp7']['pname']])) {
					$pgravity = '-gravity '.$lgravity['msg4']['two_line_left_right'];
				} else {
					$pgravity = '-gravity '.$lgravity['msg4']['two_line'];
				}
				/*
				if (!empty($layout['text_topbottom'])) {
					$cYelement = floor($layer['cY'] / 2);
					if (!empty($_GET['debug_php'])) {
						tpt_dump('$cYelement = floor($layer[\'cY\']/2); ' . $cYelement . ' = floor(' . $layer['cY'] . '/2);');
					}
				}
				*/
			} else {
				if(isset($layer[$cpf_module->moduleData['pname']['txt1']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp1']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp3']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['txt3']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp5']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp7']['pname']])) {
					$pgravity = '-gravity '.$lgravity['msg4']['left_right'];
				} else {
					$pgravity = '-gravity '.$lgravity['msg4']['single'];
				}
			}
		}
		
		return $pgravity;
	}




	static function c_message_combined(&$vars, &$layer, &$out='', &$steps=array()) {
		/*
		tpt_dump(
			json_encode(array(
				'msg1'=>array(
					'single'=>'Center',
					'left_right'=>'West',
					'two_line'=>'North',
					'two_line_left_right'=>'NorthWest',
				),
				'msg2'=>array(
					'single'=>'Center',
					'left_right'=>'East',
					'two_line'=>'North',
					'two_line_left_right'=>'NorthEast',
				),
				'msg3'=>array(
					'single'=>'Center',
					'left_right'=>'West',
					'two_line'=>'South',
					'two_line_left_right'=>'SouthWest',
				),
				'msg4'=>array(
					'single'=>'Center',
					'left_right'=>'East',
					'two_line'=>'South',
					'two_line_left_right'=>'SouthEast',
				),
			))
		, true);
		*/
		$color_module = getModule($vars, 'BandColor');
		$msg_module = getModule($vars, 'BandMessage');
		$cpf_module = getModule($vars, 'CustomProductField');
		$fonts_module = getModule($vars, 'BandFont');
		$layouts_module = getModule($vars, 'BandLayout');
		$fonts = $fonts_module->moduleData['id'];
		$clipart_module = getModule($vars, 'BandClipart');

		$bp = BIN_PATH;
		if(defined('ALT_BIN_PATH')) {
			$bp = ALT_BIN_PATH;
		}
		$im_bin = IMAGEMAGICK_BIN;

		parse_str((isset($layer['options'])?$layer['options']:''), $options);

		//tpt_dump($layer);
		$layout = (!empty($layer['band_layout'])?intval($layer['band_layout'], 10):(!empty($layer['layout'])?intval($layer['layout'], 10):1));
		$layout = $layouts_module->moduleData['id'][$layout];

		$cX = (!empty($layer['cX'])?intval($layer['cX'], 10):1);
		if(!empty($_GET['debug_php'])) {
			tpt_dump('$cX = (!empty($layer[\'cX\'])?intval($layer[\'cX\'], 10):1); '.$cX.' = (!empty('.$layer['cX'].')?intval('.$layer['cX'].', 10):1);');
		}
		$cY = (!empty($layer['cY'])?intval($layer['cY'], 10):1);
		if(!empty($_GET['debug_php'])) {
			tpt_dump('$cY = (!empty($layer[\'cY\'])?intval($layer[\'cY\'], 10):1); '.$cY.' = (!empty('.$layer['cY'].')?intval('.$layer['cY'].', 10):1);');
		}

		$cPL = (!empty($layer['cPL'])?intval($layer['cPL'], 10):0);
		if(!empty($_GET['debug_php'])) {
			tpt_dump('$cPL = (!empty($layer[\'cPL\'])?intval($layer[\'cPL\'], 10):0); '.$cPL.' = (!empty('.$layer['cPL'].')?intval('.$layer['cPL'].', 10):1);');
		}
		$cPR = (!empty($layer['cPR'])?intval($layer['cPR'], 10):0);
		if(!empty($_GET['debug_php'])) {
			tpt_dump('$cPR = (!empty($layer[\'cPR\'])?intval($layer[\'cPR\'], 10):0); '.$cPR.' = (!empty('.$layer['cPR'].')?intval('.$layer['cPR'].', 10):1);');
		}
		$cPT = (!empty($layer['cPT'])?intval($layer['cPT'], 10):0);
		if(!empty($_GET['debug_php'])) {
			tpt_dump('$cPT = (!empty($layer[\'cPT\'])?intval($layer[\'cPT\'], 10):0); '.$cPT.' = (!empty('.$layer['cPT'].')?intval('.$layer['cPT'].', 10):1);');
		}
		$cPB = (!empty($layer['cPB'])?intval($layer['cPB'], 10):0);
		if(!empty($_GET['debug_php'])) {
			tpt_dump('$cPB = (!empty($layer[\'cPB\'])?intval($layer[\'cPB\'], 10):0); '.$cPB.' = (!empty('.$layer['cPB'].')?intval('.$layer['cPB'].', 10):1);');
		}


		$targets = explode(',', $layer['target']);
		$targets = array_combine($targets, $targets);
		$targets = array_intersect_key($cpf_module->moduleData['id'], $targets);

		$messages = array();
		$clipart = array();
		$felms = 0;
		$belms = 0;
		foreach($targets as $tid=>$target) {
			if(isset($layer[$target['pname']])) {
				if(!empty($target['text'])) {
					$messages[$tid] = $layer[$target['pname']];
					if(!empty($cpf_module->moduleData['id'][$tid]['text'])) {
						if(!empty($msg_module->moduleData['pname'][$cpf_module->moduleData['id'][$tid]['pname']]['back'])) {
							$belms++;
						} else {
							$felms++;
						}
					}
				} else if(!empty($target['clipart'])) {
					$clipart[$tid] = $layer[$target['pname']];
					if(!empty($cpf_module->moduleData['id'][$tid]['clipart'])) {
						if(!empty($msg_module->moduleData['id'][$cpf_module->moduleData['pname'][$cpf_module->moduleData['id'][$tid]['pname']]['clipart_text_id']]['back'])) {
							$belms++;
						} else {
							$felms++;
						}
					}
				}
			}
		}

		$cPLelement = $cPL;
		$cPRelement = $cPR;
		if(!empty($_GET['debug_php'])) {
			tpt_dump('$cPLelement = $cPL; '.$cPLelement.' = '.$cPL.';');
			tpt_dump('$cPRelement = $cPR; '.$cPRelement.' = '.$cPR.';');
		}


		$cXelement = $cX;
		if (!empty($layout['text_frontback']) && !empty($felms) && !empty($belms)) {
			$cXex = floor($layer['cX']/2);
			$cXelement = (floor($layer['cX']/2) - 5);
			if(!empty($_GET['debug_php'])) {
				tpt_dump('floor($layer[\'cX\']/2)='.floor($layer['cX']/2));
				tpt_dump('$cXex = floor($layer[\'cX\']/2); '.$cXex.' = floor('.$layer['cX'].'/2);');
				tpt_dump('$cXelement = (floor($layer[\'cX\']/2) - 5); '.$cXelement.' = (floor('.$layer['cX'].'/2) - 5);');
			}
		}

		$cYelement = $cYelement_min = $cYelement_trim = $cY;

		$s = array();
		$partsjoin = array();
		if (!empty($layout['clipart_leftright'])) {
			/* GET SMALLEST FONT SIZE ELEMENT ID */
			$metrics = array();
			$ps_element = null;
			$ps_element_id = null;
			foreach ($messages as $tid => $msg) {
				$msgfld = $cpf_module->moduleData['id'][$tid];

				$cYelement = self::get_cYelement($vars, $cY, $tid, $layout, $layer);
				$cYelement_min = (($cYelement < $cYelement_min) ? $cYelement : $cYelement_min);

				$xsum = 0;
				foreach ($clipart as $ctid => $clp) {
					$clpdata = $cpf_module->moduleData['id'][$ctid];
					if ($tid == $clpdata['clipart_text_id']) {
						if (isset($layer[$clpdata['pname']])) {
							$metrics[$ctid] = self::a_get_clipart_metrics($vars, $ctid, $clp, $cXelement, $cYelement);

							$xsum += $metrics[$ctid]['x'];
						}
					}
				}

				$msgx = $cXelement - $xsum;

				if (($msg === '')) {
					continue;
				}
				$s = array();
				$metrics[$tid] = self::a_get_text_metrics2($vars, $msgx, $cYelement, $msg, array('font' => $layer['font']), '\\', null, null, $s, 'a_get_text_metrics-pre0', 'a_get_text_metrics-pre0');
				/*
				$xsum = $metrics[$tid]['x'];
				if(!empty($_GET['debug_php'])) {
					tpt_dump('$xsum = $clptxt[$tid][\'x\']; '.$xsum.' = '.$metrics[$tid]['x'].'; ($tid='.$tid.', $pname: '.$msgfld['pname'].')');
				}
				*/
				$ps_element = (is_null($ps_element) ? $metrics[$tid]['ps'] : (($metrics[$tid]['ps'] < $ps_element) ? $metrics[$tid]['ps'] : $ps_element));
				$ps_element_id = (is_null($ps_element_id) ? $tid : (($metrics[$tid]['ps'] == $ps_element) ? $tid : $ps_element_id));
			}
			if (!empty($_GET['debug_php'])) {
				tpt_dump('$ps_element = '.$ps_element);
				tpt_dump('$ps_element_id = '.$ps_element_id);
			}
			/* END * GET SMALLEST FONT SIZE ELEMENT ID */

			/* GET MAX ELEMENT FONT SIZE AND HEIGHT */
			$fontsize = null;
			$space_width = null;
			if (!is_null($ps_element)) {
				$temp_metrics = self::a_get_text_metrics2($vars, $cXelement, $cYelement_min, $messages[$ps_element_id], array('font' => $layer['font']), '\\', null, null, $s, 'a_get_text_metrics-temp0', 'a_get_text_metrics-temp0');
				$xsum = $temp_metrics['x'];
				foreach ($clipart as $ctid => $clp) {
					$clpdata = $cpf_module->moduleData['id'][$ctid];

					if ($ps_element_id == $clpdata['clipart_text_id']) {
						if (isset($layer[$clpdata['pname']])) {
							$metrics[$ctid] = self::a_get_clipart_metrics($vars, $ctid, $clp, $cXelement, $temp_metrics['y']);

							if (!empty($_GET['debug_php'])) {
								tpt_dump('$xsum; ' . $xsum);
							}
							$xsum += $metrics[$ctid]['x'];
							if (!empty($_GET['debug_php'])) {
								tpt_dump('$xsum += $clptxt[$ctid][\'x\']; ' . $xsum . ' = ' . ($xsum - $metrics[$ctid]['x']) . '+' . $metrics[$ctid]['x'] . '; ($ctid=' . $ctid . ', $pname=' . $clpdata['pname'] . ')');
							}
						}
					}
				}

				//tpt_dump($xsum);
				$diff_proportion = 1;
				if ($cXelement < $xsum) {
					$diff_proportion = $cXelement / $xsum;
				}

				$msgx = floor($temp_metrics['x'] * $diff_proportion);
				$msgy = (!empty($temp_metrics['proportion']) ? floor($msgx / $temp_metrics['proportion']) : 0);

				$temp_metrics = self::a_get_text_metrics2($vars, $msgx, $msgy, $messages[$ps_element_id], array('font' => $layer['font']), '\\', null, null, $s, 'a_get_text_metrics-final0', 'a_get_text_metrics-final0');
				$cYelement_trim = $temp_metrics['y'];
				$fontsize = $temp_metrics['ps'];
				$space_width = $temp_metrics['space_width'];
			}
			if (!empty($_GET['debug_php'])) {
				tpt_dump('$cYelement_trim = '.$cYelement_trim);
				tpt_dump('$fontsize = '.$fontsize);
			}
			/* END * GET MAX ELEMENT FONT SIZE AND HEIGHT */

			foreach($messages as $tid=>$msg) {
				$msgfld = $cpf_module->moduleData['id'][$tid];

				$pgravity = self::get_element_gravity($vars, $layout, $layer, $tid);

				$clptxt = array();

				$clptxt[$tid] = self::cc_m_text_ps($vars, $cXelement, $cYelement_trim, $fontsize, $space_width, $msg, array('font'=>$layer['font']));
				$xsum = $clptxt[$tid]['metrics']['x'];
				if(!empty($_GET['debug_php'])) {
					tpt_dump('$xsum = $clptxt[$tid][\'metrics\'][\'x\']; '.$xsum.' = '.$clptxt[$tid]['metrics']['x'].'; ($tid='.$tid.', $pname: '.$msgfld['pname'].')');
				}

				foreach ($clipart as $ctid => $clp) {
					$clpdata = $cpf_module->moduleData['id'][$ctid];
					//$cmsg = $cpf_module->moduleData['id'][$clpdata['clipart_text_id']];

					if($tid == $clpdata['clipart_text_id']) {
						$metrics[$ctid] = self::a_get_clipart_metrics($vars, $ctid, $clp, $cXelement, $cYelement_trim);

						$clptxt[$ctid] = $metrics[$ctid];
						$xsum += $clptxt[$ctid]['x'];
						if(!empty($_GET['debug_php'])) {
							tpt_dump('$xsum += $clptxt[$ctid][\'x\']; '.$xsum.' = '.($xsum-$clptxt[$ctid]['x']).'+'.$clptxt[$ctid]['x'].'; ($ctid='.$ctid.', $pname='.$clpdata['pname'].')');
						}
					}
				}

				$parts = array();
				$msggrvt = '-gravity Center';

				$clips = array();
				$pmsg = null;
				foreach($clptxt as $clptxtid=>$ct) {
					$fld = $cpf_module->moduleData['id'][$clptxtid];
					if(!empty($_GET['debug_php'])) {
						tpt_dump('TEXT/CLIPART: ($clptxtid: '.$clptxtid.', $pname: '.$fld['pname'].')');
					}
					if($fld['text']) {
						$pmsg = $ct['command'];
					} else {
						$clpgrvt = '-gravity West';
						if(!empty($fld['orientation'])) {
							$clpgrvt = '-gravity East';
						}

						$clips[$fld['orientation']] = $clptxt[$clptxtid];
						$clpx = $clptxt[$clptxtid]['x'];

						$c = $clipart_module->getClipartPath($vars, $clipart[$clptxtid], TPT_RESOURCE_DIR.DIRECTORY_SEPARATOR.'edited_clipart');

						$parts[$clptxtid] = <<< EOT
\( \
-trim \
-background 'transparent' \
-fill '#FFFFFF' \
-density 1200 \
-size x{$cYelement_trim} \
-resize {$clpx}x{$cYelement_trim} \
MSVG:$c \
\) \
$clpgrvt \
-compose Over \
-composite \
EOT;
					}
				}

				if(isset($clips[0]) && isset($clips[1])) {
					$msgoffset = $clips[0]['x'] - $clips[1]['x'];
					if(!empty($_GET['debug_php'])) {
						tpt_dump('$msgoffset = $clips[0][\'x\'] - $clips[1][\'x\']; '.$msgoffset.' = '.$clips[0]['x'].' - '.$clips[1]['x'].';');
					}
					$msggrvt = '-gravity Center -geometry '.sprintf("%+d",floor($msgoffset/2)).'+0';
				} else if(isset($clips[0])) {
					$msggrvt = '-gravity East';
				} else if(isset($clips[1])) {
					$msggrvt = '-gravity West';
				}

				if(!empty($pmsg)) {
					$parts[$tid] = <<< EOT
$pmsg
$msggrvt \
-compose Over \
-composite \
-geometry +0+0 \
EOT;
				}
				//tpt_dump($clptxt);
				$parts = implode("\n", $parts);
				if(!empty($_GET['debug_outline'])) {
					$cXelement_minus = $cXelement-1;
					$cYelement_minus = $cYelement-1;
					$parts = <<< EOT
\( \
	-size {$xsum}x{$cYelement_min} \
	xc:transparent \
	$parts
	-fill none \
	-strokewidth 1 \
	-stroke black \
	-draw "rectangle 0,0 {$cXelement_minus},{$cYelement_minus}" \
\) \
EOT;
				} else {
					$parts = <<< EOT
\( \
	-size {$xsum}x{$cYelement_min} \
	xc:transparent \
	$parts
\) \
EOT;
				}



				//tpt_dump($parts, true);
				$effects = array();

				if(!empty($layer['message_color'])) {
					$cprops = $color_module->getColorProps($vars, $layer['message_color']);
					$col = (!empty($cprops['hex']) ? '#' . $cprops['hex'] : 'none');
					$effects[] = self::cc_e_color_overlay($vars, $col, $parts);
				} else if ((!empty($layer['color']) && ($layer['color'] != 'transparent') && ($layer['color'] != 'none'))) {
					$effects[] = self::cc_e_color_overlay($vars, $layer['color'], $parts);
				}
				if(!empty($layer['effects_shadow_inner'])) {
					$width_top = explode(',', $layer['effects_shadow_inner_width_top']);
					$width_right = explode(',', $layer['effects_shadow_inner_width_right']);
					$width_bottom = explode(',', $layer['effects_shadow_inner_width_bottom']);
					$width_left = explode(',', $layer['effects_shadow_inner_width_left']);
					$spread_top = explode(',', $layer['effects_shadow_inner_spread_top']);
					$spread_right = explode(',', $layer['effects_shadow_inner_spread_right']);
					$spread_bottom = explode(',', $layer['effects_shadow_inner_spread_bottom']);
					$spread_left = explode(',', $layer['effects_shadow_inner_spread_left']);
					$color_top = explode(',', $layer['effects_shadow_inner_color_top']);
					$color_right = explode(',', $layer['effects_shadow_inner_color_right']);
					$color_bottom = explode(',', $layer['effects_shadow_inner_color_bottom']);
					$color_left = explode(',', $layer['effects_shadow_inner_color_left']);
					$opacity_top = explode(',', $layer['effects_shadow_inner_opacity_top']);
					$opacity_right = explode(',', $layer['effects_shadow_inner_opacity_right']);
					$opacity_bottom = explode(',', $layer['effects_shadow_inner_opacity_bottom']);
					$opacity_left = explode(',', $layer['effects_shadow_inner_opacity_left']);
					foreach($color_top as $key=>$val) {
						$effects[] = self::cc_e_shadow_inner($vars, array($width_top[$key], $width_right[$key], $width_bottom[$key], $width_left[$key]), array($spread_top[$key], $spread_right[$key], $spread_bottom[$key], $spread_left[$key]), array($color_top[$key], $color_right[$key], $color_bottom[$key], $color_left[$key]), array($opacity_top[$key], $opacity_right[$key], $opacity_bottom[$key], $opacity_left[$key]), $parts);
					}
				}
				if (!empty($_GET['debug_php'])) {
					tpt_dump('Effects:');
					tpt_dump($effects);
				}
				$effects = implode("\n", $effects);
				$partsjoin[] = <<< EOT
\( \
	-size {$cXelement}x{$cYelement_min} \
	xc:transparent \
	\( \
$effects
		-background transparent \
		-compose Over \
		-flatten \
	\) \
	-gravity Center \
	-compose Over \
	-composite \
\) \
$pgravity \
-compose Over \
-composite \
EOT;
				//$parts[$tid] = self::cc_e_stroke_inner($vars, 1, '#FFFFFF', $text_mask);
			}
		}

		if (!empty($_GET['debug_php'])) {
			tpt_dump('Messages:');
			tpt_dump($partsjoin);
		}
		$partsjoin = implode("\n", $partsjoin);
		$pjtrim = trim($partsjoin);
		if(empty($pjtrim)) {
			$partsjoin = '\\';
		}
		//tpt_dump($partsjoin, true);

		if(!empty($_GET['debug_outline'])) {
			$cX_minus = $cX-1;
			$cY_minus = $cY-1;
			$command = <<< EOT
{$bp}{$im_bin} \
-respect-parenthesis \
\( \
	\( \
		\( \
			-size {$cX}x{$cY} \
			xc:transparent \
		\) \
$partsjoin
	\) \
\) \
-fill none \
-strokewidth 1 \
-stroke black \
-draw "rectangle 0,0 {$cX_minus},{$cY_minus}" \

EOT;
		} else {
			$command = <<< EOT
{$bp}{$im_bin} \
-respect-parenthesis \
\( \
	\( \
		\( \
			-size {$cX}x{$cY} \
			xc:transparent \
		\) \
$partsjoin
	\) \
\) \

EOT;
		}

		return $command;

	}
	static function c_message_combined_old(&$vars, &$layer, &$out='', &$steps=array()) {
		/*
		tpt_dump(
			json_encode(array(
				'msg1'=>array(
					'single'=>'Center',
					'left_right'=>'West',
					'two_line'=>'North',
					'two_line_left_right'=>'NorthWest',
				),
				'msg2'=>array(
					'single'=>'Center',
					'left_right'=>'East',
					'two_line'=>'North',
					'two_line_left_right'=>'NorthEast',
				),
				'msg3'=>array(
					'single'=>'Center',
					'left_right'=>'West',
					'two_line'=>'South',
					'two_line_left_right'=>'SouthWest',
				),
				'msg4'=>array(
					'single'=>'Center',
					'left_right'=>'East',
					'two_line'=>'South',
					'two_line_left_right'=>'SouthEast',
				),
			))
		, true);
		*/
		$color_module = getModule($vars, 'BandColor');
		$msg_module = getModule($vars, 'BandMessage');
		$cpf_module = getModule($vars, 'CustomProductField');
		$fonts_module = getModule($vars, 'BandFont');
		$layouts_module = getModule($vars, 'BandLayout');
		$fonts = $fonts_module->moduleData['id'];
		$clipart_module = getModule($vars, 'BandClipart');

		$bp = BIN_PATH;
		if(defined('ALT_BIN_PATH')) {
			$bp = ALT_BIN_PATH;
		}
		$im_bin = IMAGEMAGICK_BIN;

		parse_str((isset($layer['options'])?$layer['options']:''), $options);

		//tpt_dump($layer);
		$layout = (!empty($layer['band_layout'])?intval($layer['band_layout'], 10):(!empty($layer['layout'])?intval($layer['layout'], 10):1));
		$layout = $layouts_module->moduleData['id'][$layout];

		$cX = (!empty($layer['cX'])?intval($layer['cX'], 10):1);
		if(!empty($_GET['debug_php'])) {
			tpt_dump('$cX = (!empty($layer[\'cX\'])?intval($layer[\'cX\'], 10):1); '.$cX.' = (!empty('.$layer['cX'].')?intval('.$layer['cX'].', 10):1);');
		}
		$cY = (!empty($layer['cY'])?intval($layer['cY'], 10):1);
		if(!empty($_GET['debug_php'])) {
			tpt_dump('$cY = (!empty($layer[\'cY\'])?intval($layer[\'cY\'], 10):1); '.$cY.' = (!empty('.$layer['cY'].')?intval('.$layer['cY'].', 10):1);');
		}

		$cPL = (!empty($layer['cPL'])?intval($layer['cPL'], 10):0);
		if(!empty($_GET['debug_php'])) {
			tpt_dump('$cPL = (!empty($layer[\'cPL\'])?intval($layer[\'cPL\'], 10):0); '.$cPL.' = (!empty('.$layer['cPL'].')?intval('.$layer['cPL'].', 10):1);');
		}
		$cPR = (!empty($layer['cPR'])?intval($layer['cPR'], 10):0);
		if(!empty($_GET['debug_php'])) {
			tpt_dump('$cPR = (!empty($layer[\'cPR\'])?intval($layer[\'cPR\'], 10):0); '.$cPR.' = (!empty('.$layer['cPR'].')?intval('.$layer['cPR'].', 10):1);');
		}
		$cPT = (!empty($layer['cPT'])?intval($layer['cPT'], 10):0);
		if(!empty($_GET['debug_php'])) {
			tpt_dump('$cPT = (!empty($layer[\'cPT\'])?intval($layer[\'cPT\'], 10):0); '.$cPT.' = (!empty('.$layer['cPT'].')?intval('.$layer['cPT'].', 10):1);');
		}
		$cPB = (!empty($layer['cPB'])?intval($layer['cPB'], 10):0);
		if(!empty($_GET['debug_php'])) {
			tpt_dump('$cPB = (!empty($layer[\'cPB\'])?intval($layer[\'cPB\'], 10):0); '.$cPB.' = (!empty('.$layer['cPB'].')?intval('.$layer['cPB'].', 10):1);');
		}


		$targets = explode(',', $layer['target']);
		$targets = array_combine($targets, $targets);
		$targets = array_intersect_key($cpf_module->moduleData['id'], $targets);

		$messages = array();
		$m = array();
		$clipart = array();
		$metrics = array();
		$cmetrics = array();
		$felms = 0;
		$belms = 0;
		foreach($targets as $tid=>$target) {
			if(isset($layer[$target['pname']])) {
				if(!empty($target['text'])) {
					$messages[$tid] = $layer[$target['pname']];
					if(!empty($cpf_module->moduleData['id'][$tid]['text'])) {
						if(!empty($msg_module->moduleData['pname'][$cpf_module->moduleData['id'][$tid]['pname']]['back'])) {
							$belms++;
						} else {
							$felms++;
						}
					}
				} else if(!empty($target['clipart'])) {
					$clipart[$tid] = $layer[$target['pname']];
					if(!empty($cpf_module->moduleData['id'][$tid]['clipart'])) {
						if(!empty($msg_module->moduleData['id'][$cpf_module->moduleData['pname'][$cpf_module->moduleData['id'][$tid]['pname']]['clipart_text_id']]['back'])) {
							$belms++;
						} else {
							$felms++;
						}
					}
				}
			}
		}

		$cPLelement = $cPL;
		$cPRelement = $cPR;
		if(!empty($_GET['debug_php'])) {
			tpt_dump('$cPLelement = $cPL; '.$cPLelement.' = '.$cPL.';');
			tpt_dump('$cPRelement = $cPR; '.$cPRelement.' = '.$cPR.';');
		}


		if (!empty($layout['text_frontback']) && !empty($felms) && !empty($belms)) {
			$cXex = floor($layer['cX']/2);
			$cXelement = (floor($layer['cX']/2) - 5);
			if(!empty($_GET['debug_php'])) {
				tpt_dump('floor($layer[\'cX\']/2)='.floor($layer['cX']/2));
				tpt_dump('$cXex = floor($layer[\'cX\']/2); '.$cXex.' = floor('.$layer['cX'].'/2);');
				tpt_dump('$cXelement = (floor($layer[\'cX\']/2) - 5); '.$cXelement.' = (floor('.$layer['cX'].'/2) - 5);');
			}
		}


		$cXelement = $cX;
		if(!empty($_GET['debug_php'])) {
			tpt_dump('$cXelement = $cX; '.$cXelement.' = '.$cX.';');
		}


		$s = array();
		$partsjoin = array();
		$fontsize = 0;
		$cYmax = $cY;
		foreach($messages as $tid=>$msg) {
			$cYelement = $cY;
			if(!empty($_GET['debug_php'])) {
				tpt_dump('$cYelement = $cY; '.$cYelement.' = '.$cY.';');
			}

			$cYelement = self::get_cYelement($vars, $cY, $tid, $layout, $layer);
			$cYelement_double = $cYelement*2;
			if(!empty($_GET['debug_php'])) {
				tpt_dump('$cYelement_double = $cYelement*2; '.$cYelement_double.' = '.$cYelement.'*2;');
			}

			$msgfld = $cpf_module->moduleData['id'][$tid];

			$text = $msg;
			if(empty($text)) {
				$text = ' ';
			}

			$text_mask = self::cc_m_text($vars, $cXelement, $cYelement, $text, array('font'=>$layer['font']));
			$metrics[$tid] = $text_mask['metrics'];
			$text_mask = $text_mask['command'];

			$clpymax = (!empty($metrics[$tid]['y'])?$metrics[$tid]['y']:$cYelement);
			if(!empty($_GET['debug_php'])) {
				tpt_dump('$clpymax = (!empty($metrics[$tid][\'y\'])?$metrics[$tid][\'y\']:$cYelement); '.$clpymax);
			}

			$clptxt = array();
			$clptxt[$tid] = $metrics[$tid];
			$xsum = $clptxt[$tid]['x'];
			if(!empty($_GET['debug_php'])) {
				tpt_dump('$xsum = $clptxt[$tid][\'x\']; '.$xsum.' = '.$clptxt[$tid]['x'].'; ($tid='.$tid.', $pname: '.$msgfld['pname'].')');
			}
			foreach ($clipart as $ctid => $clp) {
				$clpdata = $cpf_module->moduleData['id'][$ctid];
				$metrics[$ctid] = self::a_get_clipart_metrics($vars, $ctid, $clp, $cXelement, $clpymax);

				//tpt_dump($tid);
				//tpt_dump($clpdata['clipart_text_id']);
				if($tid == $clpdata['clipart_text_id']) {
					if(isset($layer[$clpdata['pname']])) {
						$clptxt[$ctid] = $metrics[$ctid];
						if(!empty($_GET['debug_php'])) {
							tpt_dump('$xsum; '.$xsum);
						}
						$xsum += $clptxt[$ctid]['x'];
						if(!empty($_GET['debug_php'])) {
							tpt_dump('$xsum += $clptxt[$ctid][\'x\']; '.$xsum.' = '.($xsum-$clptxt[$ctid]['x']).'+'.$clptxt[$ctid]['x'].'; ($ctid='.$ctid.', $pname='.$clpdata['pname'].')');
						}
					}
				}
			}

			$parts = array();
			$msggrvt = '-gravity Center';
			if(!empty($layout['clipart_leftright'])) {
				//tpt_dump($xsum);
				$diff_proportion = 1;
				$diff = 0;
				$diff_part = 0;
				if(!empty($_GET['debug_php'])) {
					tpt_dump('$diff_proportion = 1;');
					tpt_dump('$diff = 0;');
					tpt_dump('$diff_part = 0;');
				}
				if($cXelement < $xsum) {
					$diff_proportion = $cXelement/$xsum;
					$diff = $xsum - $cXelement;
					$diff_part = ceil($diff/count($clptxt));
					if(!empty($_GET['debug_php'])) {
						tpt_dump('$diff_proportion = $cXelement/$xsum; '.$diff_proportion.' = '.$cXelement.'/'.$xsum.';');
						tpt_dump('$diff = $xsum - $cXelement; '.$diff.' = '.$xsum.' - '.$cXelement.';');
						tpt_dump('$diff_part = ceil($diff/count($clptxt)); '.$diff_part.' = ceil('.$diff.'/'.count($clptxt).');');
					}
				}
				/*
				if(!empty($_GET['debug_php'])) {
					tpt_dump('$xsum -= ($diff_part*count($clptxt)); '.$xsum.' = '.($xsum+($diff_part*count($clptxt))).' - ('.$diff_part.'*'.count($clptxt).');');
				}
				*/

				$clips = array();
				$pmsg = null;
				$xsum = 0;
				foreach($clptxt as $clptxtid=>$ct) {
					$fld = $cpf_module->moduleData['id'][$clptxtid];
					if(!empty($_GET['debug_php'])) {
						tpt_dump('TEXT/CLIPART: ($clptxtid: '.$clptxtid.', $pname: '.$fld['pname'].')');
					}
					if($fld['text']) {
						$pmsg = $ct;

						$msgx = floor($ct['x']*$diff_proportion);
						$msgy = (!empty($ct['proportion'])?floor($msgx/$ct['proportion']):0);

						$metrics[$tid] = self::a_get_text_metrics($vars, $msgx, $msgy, $text, array('font'=>$layer['font']), '\\', null, null, $s, 'a_get_text_metrics-final0', 'a_get_text_metrics-final0');
					}
				}

				if(!empty($pmsg)) {
					$msgx = $metrics[$tid]['x'];
					$msgy = $metrics[$tid]['y'];
					$pointsize = $metrics[$tid]['ps'];
					$xsum += $msgx;

					$text_mask = self::cc_m_text($vars, $msgx, $msgy, $text, array('font'=>$layer['font']));
					$metrics[$tid] = $text_mask['metrics'];
					if(!empty($_GET['debug_php'])) {
						tpt_dump('Msg: '.$text);
						tpt_dump('Font size: '.$fontsize);
					}
					if((empty($fontsize) || ($fontsize >=  $metrics[$tid]['ps'])) && !preg_match('#^\s*$#', $text) && ($text !== '')) {
						$fontsize = $metrics[$tid]['ps'];
						$cYmax = min($metrics[$tid]['y'], $cYmax);
						if(!empty($_GET['debug_php'])) {
							tpt_dump('Msg: '.$text);
							tpt_dump('Font size: '.$fontsize);
						}
					}
				}
			}
		}
		foreach($messages as $tid=>$msg) {
			$cYelement = $cY;
			if(!empty($_GET['debug_php'])) {
				tpt_dump('$cYelement = $cY; '.$cYelement.' = '.$cY.';');
			}

			$lgravity = json_decode($layout['gravity'], true);
			$pgravity = '-gravity Center';
			if (($cpf_module->moduleData['id'][$tid]['pname'] == 'txt1')) {
				if(isset($layer[$cpf_module->moduleData['pname']['txt3']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp5']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp7']['pname']])) {
					if(isset($layer[$cpf_module->moduleData['pname']['txt2']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp2']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp4']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['txt4']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp6']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp8']['pname']])) {
						$pgravity = '-gravity '.$lgravity['msg1']['two_line_left_right'];
					} else {
						$pgravity = '-gravity '.$lgravity['msg1']['two_line'];
					}
					if (!empty($layout['text_topbottom'])) {
						$cYelement = floor($layer['cY'] / 2);
						if (!empty($_GET['debug_php'])) {
							tpt_dump('$cYelement = floor($layer[\'cY\']/2); ' . $cYelement . ' = floor(' . $layer['cY'] . '/2);');
						}
					}
				} else {
					if(isset($layer[$cpf_module->moduleData['pname']['txt2']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp2']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp4']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['txt4']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp6']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp8']['pname']])) {
						$pgravity = '-gravity '.$lgravity['msg1']['left_right'];
					} else {
						$pgravity = '-gravity '.$lgravity['msg1']['single'];
					}
				}
			} else if (($cpf_module->moduleData['id'][$tid]['pname'] == 'txt2')) {
				if(isset($layer[$cpf_module->moduleData['pname']['txt4']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp6']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp8']['pname']])) {
					if(isset($layer[$cpf_module->moduleData['pname']['txt1']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp1']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp3']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['txt3']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp5']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp7']['pname']])) {
						$pgravity = '-gravity '.$lgravity['msg2']['two_line_left_right'];
					} else {
						$pgravity = '-gravity '.$lgravity['msg2']['two_line'];
					}
					if (!empty($layout['text_topbottom'])) {
						$cYelement = floor($layer['cY'] / 2);
						if (!empty($_GET['debug_php'])) {
							tpt_dump('$cYelement = floor($layer[\'cY\']/2); ' . $cYelement . ' = floor(' . $layer['cY'] . '/2);');
						}
					}
				} else {
					if(isset($layer[$cpf_module->moduleData['pname']['txt1']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp1']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp3']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['txt3']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp5']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp7']['pname']])) {
						$pgravity = '-gravity '.$lgravity['msg2']['left_right'];
					} else {
						$pgravity = '-gravity '.$lgravity['msg2']['single'];
					}
				}
			} else if (($cpf_module->moduleData['id'][$tid]['pname'] == 'txt3')) {
				if(isset($layer[$cpf_module->moduleData['pname']['txt1']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp1']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp3']['pname']])) {
					if(isset($layer[$cpf_module->moduleData['pname']['txt2']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp2']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp4']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['txt4']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp6']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp8']['pname']])) {
						$pgravity = '-gravity '.$lgravity['msg3']['two_line_left_right'];
					} else {
						$pgravity = '-gravity '.$lgravity['msg3']['two_line'];
					}
					if (!empty($layout['text_topbottom'])) {
						$cYelement = floor($layer['cY'] / 2);
						if (!empty($_GET['debug_php'])) {
							tpt_dump('$cYelement = floor($layer[\'cY\']/2); ' . $cYelement . ' = floor(' . $layer['cY'] . '/2);');
						}
					}
				} else {
					if(isset($layer[$cpf_module->moduleData['pname']['txt2']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp2']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp4']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['txt4']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp6']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp8']['pname']])) {
						$pgravity = '-gravity '.$lgravity['msg3']['left_right'];
					} else {
						$pgravity = '-gravity '.$lgravity['msg3']['single'];
					}
				}
			} else if (($cpf_module->moduleData['id'][$tid]['pname'] == 'txt4')) {
				if(isset($layer[$cpf_module->moduleData['pname']['txt2']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp2']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp4']['pname']])) {
					if(isset($layer[$cpf_module->moduleData['pname']['txt1']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp1']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp3']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['txt3']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp5']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp7']['pname']])) {
						$pgravity = '-gravity '.$lgravity['msg4']['two_line_left_right'];
					} else {
						$pgravity = '-gravity '.$lgravity['msg4']['two_line'];
					}
					if (!empty($layout['text_topbottom'])) {
						$cYelement = floor($layer['cY'] / 2);
						if (!empty($_GET['debug_php'])) {
							tpt_dump('$cYelement = floor($layer[\'cY\']/2); ' . $cYelement . ' = floor(' . $layer['cY'] . '/2);');
						}
					}
				} else {
					if(isset($layer[$cpf_module->moduleData['pname']['txt1']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp1']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp3']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['txt3']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp5']['pname']]) || isset($layer[$cpf_module->moduleData['pname']['clp7']['pname']])) {
						$pgravity = '-gravity '.$lgravity['msg4']['left_right'];
					} else {
						$pgravity = '-gravity '.$lgravity['msg4']['single'];
					}
				}
			}

			$cYelement_double = $cYelement*2;
			if(!empty($_GET['debug_php'])) {
				tpt_dump('$cYelement_double = $cYelement*2; '.$cYelement_double.' = '.$cYelement.'*2;');
			}

			$msgfld = $cpf_module->moduleData['id'][$tid];

			$text = $msg;
			if(empty($text)) {
				$text = ' ';
			}

			$text_mask = self::cc_m_text_ps($vars, $cXelement, $cYelement, $fontsize, $text, array('font'=>$layer['font']));
			$metrics[$tid] = $text_mask['metrics'];
			$text_mask = $text_mask['command'];

			$clpymax = $cYmax;
			if(!empty($_GET['debug_php'])) {
				tpt_dump('$clpymax = $cYmax; '.$clpymax);
			}

			$clptxt = array();
			$clptxt[$tid] = $metrics[$tid];
			$xsum = $clptxt[$tid]['x'];
			if(!empty($_GET['debug_php'])) {
				tpt_dump('$xsum = $clptxt[$tid][\'x\']; '.$xsum.' = '.$clptxt[$tid]['x'].'; ($tid='.$tid.', $pname: '.$msgfld['pname'].')');
			}
			foreach ($clipart as $ctid => $clp) {
				$clpdata = $cpf_module->moduleData['id'][$ctid];
				//$cmsg = $cpf_module->moduleData['id'][$clpdata['clipart_text_id']];

				$c = $clipart_module->getClipartPath($vars, $clp);
				if(!empty($_GET['debug_php'])) {
					tpt_dump('CLIPART: ($ctid: '.$ctid.', $pname: '.$clpdata['pname'].', clp: '.$c.')');
				}

				$fsize = <<< EOT
{$bp}{$im_bin} \
-density 1200 \
-resize {$cXelement}x{$clpymax} \
$c \
-format "%@" \
info:
EOT;
				if(!empty($_GET['debug_php'])) {
					tpt_dump($fsize);
				}
				$fsize = self::exec_command($vars, $fsize, '', '', $s, 'size_'.$clpdata['pname'], 1);
				if(!empty($_GET['debug_php'])) {
					tpt_dump($fsize);
				}
				$metrics[$ctid] = preg_split('#\+|-#', $fsize);
				$metrics[$ctid] = array_shift($metrics[$ctid]);
				$metrics[$ctid] = explode('x', $metrics[$ctid]);
				$metrics[$ctid] = array('x'=>$metrics[$ctid][0], 'y'=>$metrics[$ctid][1], 'proportion'=>$metrics[$ctid][0]/$metrics[$ctid][1]);
				if(!empty($_GET['debug_php'])) {
					tpt_dump($metrics[$ctid]['x'].'/'.$metrics[$ctid]['y'].'='.$metrics[$ctid]['proportion']);
				}

				//tpt_dump($tid);
				//tpt_dump($clpdata['clipart_text_id']);
				if($tid == $clpdata['clipart_text_id']) {
					if(isset($layer[$clpdata['pname']])) {
						$clptxt[$ctid] = $metrics[$ctid];
						if(!empty($_GET['debug_php'])) {
							tpt_dump('$xsum; '.$xsum);
						}
						$xsum += $clptxt[$ctid]['x'];
						if(!empty($_GET['debug_php'])) {
							tpt_dump('$xsum += $clptxt[$ctid][\'x\']; '.$xsum.' = '.($xsum-$clptxt[$ctid]['x']).'+'.$clptxt[$ctid]['x'].'; ($ctid='.$ctid.', $pname='.$clpdata['pname'].')');
						}
					}
				}
			}

			$parts = array();
			$msggrvt = '-gravity Center';
			if(!empty($layout['clipart_leftright'])) {
				//tpt_dump($xsum);
				$diff_proportion = 1;
				$diff = 0;
				$diff_part = 0;
				if(!empty($_GET['debug_php'])) {
					tpt_dump('$diff_proportion = 1;');
					tpt_dump('$diff = 0;');
					tpt_dump('$diff_part = 0;');
				}
				if($cXelement < $xsum) {
					$diff_proportion = $cXelement/$xsum;
					$diff = $xsum - $cXelement;
					$diff_part = ceil($diff/count($clptxt));
					if(!empty($_GET['debug_php'])) {
						tpt_dump('$diff_proportion = $cXelement/$xsum; '.$diff_proportion.' = '.$cXelement.'/'.$xsum.';');
						tpt_dump('$diff = $xsum - $cXelement; '.$diff.' = '.$xsum.' - '.$cXelement.';');
						tpt_dump('$diff_part = ceil($diff/count($clptxt)); '.$diff_part.' = ceil('.$diff.'/'.count($clptxt).');');
					}
				}
				/*
				if(!empty($_GET['debug_php'])) {
					tpt_dump('$xsum -= ($diff_part*count($clptxt)); '.$xsum.' = '.($xsum+($diff_part*count($clptxt))).' - ('.$diff_part.'*'.count($clptxt).');');
				}
				*/

				$clips = array();
				$pmsg = null;
				$xsum = 0;
				foreach($clptxt as $clptxtid=>$ct) {
					$fld = $cpf_module->moduleData['id'][$clptxtid];
					if(!empty($_GET['debug_php'])) {
						tpt_dump('TEXT/CLIPART: ($clptxtid: '.$clptxtid.', $pname: '.$fld['pname'].')');
					}
					if($fld['text']) {
						$pmsg = $ct;

						$msgx = floor($ct['x']*$diff_proportion);
						$msgy = (!empty($ct['proportion'])?floor($msgx/$ct['proportion']):0);

						$metrics[$tid] = self::a_get_text_metrics($vars, $msgx, $msgy, $text, array('font'=>$layer['font']), '\\', null, null, $s, 'a_get_text_metrics-final0', 'a_get_text_metrics-final0');
					} else {
						$clpgrvt = '-gravity West';
						if(!empty($fld['orientation'])) {
							$clpgrvt = '-gravity East';
						}
						$c = $clipart_module->getClipartPath($vars, $clipart[$clptxtid], TPT_RESOURCE_DIR.DIRECTORY_SEPARATOR.'edited_clipart');
						$clpx = floor($ct['x']*$diff_proportion);
						$clpy = floor($clpx/$ct['proportion']);
						if(!empty($_GET['debug_php'])) {
							tpt_dump('$clpx = floor($ct[\'x\']*$diff_proportion); ' . $clpx.' = floor('.$ct['x'].'*'.$diff_proportion.');');
							tpt_dump('$clpy = floor($clpx/$ct[\'proportion\']); ' . $clpy.' = floor('.$clpx.'/'.$ct['proportion'].');');
						}
						$clpy = (!empty($metrics[$tid]['y'])?min($clpy, $metrics[$tid]['y']):$clpy);
						if(!empty($_GET['debug_php'])) {
							tpt_dump('$clpy = (!empty($metrics[$tid][\'y\'])?min($clpy, $metrics[$tid][\'y\']):$clpy); ' . $clpy.' = (!empty('.$metrics[$tid]['y'].')?min('.$clpy.', '.$metrics[$tid]['y'].'):'.$clpy.');');
						}

						$fsize = <<< EOT
{$bp}{$im_bin} \
-background 'transparent' \
-fill '#FFFFFF' \
-trim \
+repage \
-density 1200 \
-size x{$clpy} \
-resize {$clpx}x{$clpy} \
$c \
-format "%@" \
info:
EOT;
						if (!empty($_GET['debug_php'])) {
							tpt_dump($fsize);
						}
						$csize = self::exec_command($vars, $fsize, '', '', $s, 'clipart_metrics_' . $fld['pname'], 1);
						if (!empty($_GET['debug_php'])) {
							tpt_dump($csize);
						}
						$cmetrics[$clptxtid] = preg_split('#\+|-#', $csize);
						$cmetrics[$clptxtid] = array_shift($cmetrics[$clptxtid]);
						$cmetrics[$clptxtid] = explode('x', $cmetrics[$clptxtid]);
						$cmetrics[$clptxtid] = array('x'=>$cmetrics[$clptxtid][0], 'y'=>$cmetrics[$clptxtid][1]); // no proportion because of possible division by zero

						$clips[$fld['orientation']] = $cmetrics[$clptxtid];
						$clpx = $cmetrics[$clptxtid]['x'];
						if (!empty($_GET['debug_php'])) {
							tpt_dump('$clpx = $cmetrics[$clptxtid][\'x\']; '.$clpx);
						}
						$xsum += $clpx;
						if (!empty($_GET['debug_php'])) {
							tpt_dump('$xsum = $xsum + $clpx; '.$xsum.' = '.($xsum-$clpx).' + '.$clpx.';');
						}

						$parts[$clptxtid] = <<< EOT
\( \
	-trim \
	-background 'transparent' \
	-fill '#FFFFFF' \
	-density 1200 \
	-size x{$clpy} \
	-resize {$clpx}x{$clpy} \
	MSVG:$c \
\) \
$clpgrvt \
-compose Over \
-composite \
EOT;
					}
				}

				if(isset($clips[0]) && isset($clips[1])) {
					$msgoffset = $clips[0]['x'] - $clips[1]['x'];
					if(!empty($_GET['debug_php'])) {
						tpt_dump('$msgoffset = $clips[0][\'x\'] - $clips[1][\'x\']; '.$msgoffset.' = '.$clips[0]['x'].' - '.$clips[1]['x'].';');
					}
					$msggrvt = '-gravity Center -geometry '.sprintf("%+d",floor($msgoffset/2)).'+0';
				} else if(isset($clips[0])) {
					$msggrvt = '-gravity East';
				} else if(isset($clips[1])) {
					$msggrvt = '-gravity West';
				}

				if(!empty($pmsg)) {
					$msgx = $metrics[$tid]['x'];
					$msgy = $metrics[$tid]['y'];
					$pointsize = $metrics[$tid]['ps'];
					$xsum += $msgx;

					$text_mask = self::cc_m_text($vars, $msgx, $msgy, $text, array('font'=>$layer['font']));
					$metrics[$tid] = $text_mask['metrics'];
					$tm = $text_mask['command'];
					$parts[$tid] = <<< EOT
$tm
$msggrvt \
-compose Over \
-composite \
-geometry +0+0 \
EOT;
				}
			}
			//tpt_dump($clptxt);
			$parts = implode("\n", $parts);
			if(!empty($_GET['debug_outline'])) {
				$cXelement_minus = $cXelement-1;
				$cYelement_minus = $cYelement-1;
				$parts = <<< EOT
\( \
	-size {$xsum}x{$cYelement} \
	xc:transparent \
	$parts
	-fill none \
	-strokewidth 1 \
	-stroke black \
	-draw "rectangle 0,0 {$cXelement_minus},{$cYelement_minus}" \
\) \
EOT;
			} else {
				$parts = <<< EOT
\( \
	-size {$xsum}x{$cYelement} \
	xc:transparent \
	$parts
\) \
EOT;
			}



			//tpt_dump($parts, true);
			$effects = array();

			if(!empty($layer['message_color'])) {
				$cprops = $color_module->getColorProps($vars, $layer['message_color']);
				$col = (!empty($cprops['hex']) ? '#' . $cprops['hex'] : 'none');
				$effects[] = self::cc_e_color_overlay($vars, $col, $parts);
			} else if ((!empty($layer['color']) && ($layer['color'] != 'transparent') && ($layer['color'] != 'none'))) {
				$effects[] = self::cc_e_color_overlay($vars, $layer['color'], $parts);
			}
			if(!empty($layer['effects_shadow_inner'])) {
				$width_top = explode(',', $layer['effects_shadow_inner_width_top']);
				$width_right = explode(',', $layer['effects_shadow_inner_width_right']);
				$width_bottom = explode(',', $layer['effects_shadow_inner_width_bottom']);
				$width_left = explode(',', $layer['effects_shadow_inner_width_left']);
				$spread_top = explode(',', $layer['effects_shadow_inner_spread_top']);
				$spread_right = explode(',', $layer['effects_shadow_inner_spread_right']);
				$spread_bottom = explode(',', $layer['effects_shadow_inner_spread_bottom']);
				$spread_left = explode(',', $layer['effects_shadow_inner_spread_left']);
				$color_top = explode(',', $layer['effects_shadow_inner_color_top']);
				$color_right = explode(',', $layer['effects_shadow_inner_color_right']);
				$color_bottom = explode(',', $layer['effects_shadow_inner_color_bottom']);
				$color_left = explode(',', $layer['effects_shadow_inner_color_left']);
				$opacity_top = explode(',', $layer['effects_shadow_inner_opacity_top']);
				$opacity_right = explode(',', $layer['effects_shadow_inner_opacity_right']);
				$opacity_bottom = explode(',', $layer['effects_shadow_inner_opacity_bottom']);
				$opacity_left = explode(',', $layer['effects_shadow_inner_opacity_left']);
				foreach($color_top as $key=>$val) {
					$effects[] = self::cc_e_shadow_inner($vars, array($width_top[$key], $width_right[$key], $width_bottom[$key], $width_left[$key]), array($spread_top[$key], $spread_right[$key], $spread_bottom[$key], $spread_left[$key]), array($color_top[$key], $color_right[$key], $color_bottom[$key], $color_left[$key]), array($opacity_top[$key], $opacity_right[$key], $opacity_bottom[$key], $opacity_left[$key]), $parts);
				}
			}
			if (!empty($_GET['debug_php'])) {
				tpt_dump('Effects:');
				tpt_dump($effects);
			}
			$effects = implode("\n", $effects);
			$partsjoin[] = <<< EOT
\( \
	-size {$cXelement}x{$cYelement} \
	xc:transparent \
	\( \
$effects
		-background transparent \
		-compose Over \
		-flatten \
	\) \
	-gravity Center \
	-compose Over \
	-composite \
\) \
$pgravity \
-compose Over \
-composite \
EOT;
			//$parts[$tid] = self::cc_e_stroke_inner($vars, 1, '#FFFFFF', $text_mask);
		}
		if (!empty($_GET['debug_php'])) {
			tpt_dump('Messages:');
			tpt_dump($partsjoin);
		}
		$partsjoin = implode("\n", $partsjoin);
		$pjtrim = trim($partsjoin);
		if(empty($pjtrim)) {
			$partsjoin = '\\';
		}
		//tpt_dump($partsjoin, true);

		if(!empty($_GET['debug_outline'])) {
			$cX_minus = $cX-1;
			$cY_minus = $cY-1;
			$command = <<< EOT
{$bp}{$im_bin} \
-respect-parenthesis \
\( \
	\( \
		\( \
			-size {$cX}x{$cY} \
			xc:transparent \
		\) \
$partsjoin
	\) \
\) \
-fill none \
-strokewidth 1 \
-stroke black \
-draw "rectangle 0,0 {$cX_minus},{$cY_minus}" \

EOT;
		} else {
			$command = <<< EOT
{$bp}{$im_bin} \
-respect-parenthesis \
\( \
	\( \
		\( \
			-size {$cX}x{$cY} \
			xc:transparent \
		\) \
$partsjoin
	\) \
\) \

EOT;
		}

		return $command;

	}

	static function c_message_new(&$vars, &$layer, &$out='', &$steps=array()) {
		$color_module = getModule($vars, 'BandColor');
		$msg_module = getModule($vars, 'BandMessage');
		$cpf_module = getModule($vars, 'CustomProductField');
		$fonts_module = getModule($vars, 'BandFont');
		$layouts_module = getModule($vars, 'BandLayout');
		$fonts = $fonts_module->moduleData['id'];
		$clipart_module = getModule($vars, 'BandClipart');

		$bp = BIN_PATH;
		if(defined('ALT_BIN_PATH')) {
			$bp = ALT_BIN_PATH;
		}
		$im_bin = IMAGEMAGICK_BIN;

		parse_str((isset($layer['options'])?$layer['options']:''), $options);

		//tpt_dump($layer);
		$layout = (!empty($layer['band_layout'])?intval($layer['band_layout'], 10):(!empty($layer['layout'])?intval($layer['layout'], 10):1));
		$layout = $layouts_module->moduleData['id'][$layout];

		$cX = (!empty($layer['cX'])?intval($layer['cX'], 10):1);
		if(!empty($_GET['debug_php'])) {
			tpt_dump('$cX = (!empty($layer[\'cX\'])?intval($layer[\'cX\'], 10):1); '.$cX.' = (!empty('.$layer['cX'].')?intval('.$layer['cX'].', 10):1);');
		}
		$cY = (!empty($layer['cY'])?intval($layer['cY'], 10):1);
		if(!empty($_GET['debug_php'])) {
			tpt_dump('$cY = (!empty($layer[\'cY\'])?intval($layer[\'cY\'], 10):1); '.$cY.' = (!empty('.$layer['cY'].')?intval('.$layer['cY'].', 10):1);');
		}

		$cPL = (!empty($layer['cPL'])?intval($layer['cPL'], 10):0);
		if(!empty($_GET['debug_php'])) {
			tpt_dump('$cPL = (!empty($layer[\'cPL\'])?intval($layer[\'cPL\'], 10):0); '.$cPL.' = (!empty('.$layer['cPL'].')?intval('.$layer['cPL'].', 10):1);');
		}
		$cPR = (!empty($layer['cPR'])?intval($layer['cPR'], 10):0);
		if(!empty($_GET['debug_php'])) {
			tpt_dump('$cPR = (!empty($layer[\'cPR\'])?intval($layer[\'cPR\'], 10):0); '.$cPR.' = (!empty('.$layer['cPR'].')?intval('.$layer['cPR'].', 10):1);');
		}
		$cPT = (!empty($layer['cPT'])?intval($layer['cPT'], 10):0);
		if(!empty($_GET['debug_php'])) {
			tpt_dump('$cPT = (!empty($layer[\'cPT\'])?intval($layer[\'cPT\'], 10):0); '.$cPT.' = (!empty('.$layer['cPT'].')?intval('.$layer['cPT'].', 10):1);');
		}
		$cPB = (!empty($layer['cPB'])?intval($layer['cPB'], 10):0);
		if(!empty($_GET['debug_php'])) {
			tpt_dump('$cPB = (!empty($layer[\'cPB\'])?intval($layer[\'cPB\'], 10):0); '.$cPB.' = (!empty('.$layer['cPB'].')?intval('.$layer['cPB'].', 10):1);');
		}


		$targets = explode(',', $layer['target']);
		$targets = array_combine($targets, $targets);
		$targets = array_intersect_key($cpf_module->moduleData['id'], $targets);

		$messages = array();
		$m = array();
		$clipart = array();
		$metrics = array();
		$cmetrics = array();
		foreach($targets as $tid=>$target) {
			if(isset($layer[$target['pname']])) {
				if(!empty($target['text'])) {
					$messages[$tid] = $layer[$target['pname']];
				} else if(!empty($target['clipart'])) {
					$clipart[$tid] = $layer[$target['pname']];
				}
			}
		}

		$ncmessages = array();
		$ncparams = explode('|', (isset($layer['nullcheck_preview_params_ids'])?$layer['nullcheck_preview_params_ids']:''));
		foreach($ncparams as $ncparam) {
			$ncp = explode(':', $ncparam);
			if(!empty($cpf_module->moduleData['id'][$ncp[0]]) && !empty($cpf_module->moduleData['id'][$ncp[0]]['text'])) {
				$ncmessages[$ncp[0]] = $cpf_module->moduleData['id'][$ncp[0]];
			}
		}




		$cXelement = $cX;
		$cYelement = $cY;
		if(!empty($_GET['debug_php'])) {
			tpt_dump('$cXelement = $cX; '.$cXelement.' = '.$cX.';');
			tpt_dump('$cYelement = $cY; '.$cYelement.' = '.$cY.';');
		}
		if (!empty($layout['text_topbottom']) && (count($messages)>1)) {
			$cYelement = floor($layer['cY']/count($messages));
			if(!empty($_GET['debug_php'])) {
				tpt_dump('$cYelement = floor($layer[\'cY\']/count($messages)); '.$cYelement.' = floor('.$layer['cY'].'/'.count($messages).');');
			}
		}
		$cYelement_double = $cYelement*2;
		if(!empty($_GET['debug_php'])) {
			tpt_dump('$cYelement_double = $cYelement*2; '.$cYelement_double.' = '.$cYelement.'*2;');
		}
		$cPLelement = $cPL;
		$cPRelement = $cPR;
		if(!empty($_GET['debug_php'])) {
			tpt_dump('$cPLelement = $cPL; '.$cPLelement.' = '.$cPL.';');
			tpt_dump('$cPRelement = $cPR; '.$cPRelement.' = '.$cPR.';');
		}
		if(!empty($ncmessages)) {
			$ncmsg = reset($ncmessages);
			//tpt_dump($layer[$ncmsg['pname']]);
			//tpt_dump($layout['text_frontback']);
			//tpt_dump($messages, true);
			if (!empty($layout['text_frontback']) && !empty($ncmessages) && !empty($layer[$ncmsg['pname']])) {
				$imsg = reset($messages);
				$imsg = key($messages);
				$cXex = floor($layer['cX']/2);
				$cXelement = (floor($layer['cX']/2) - 5);
				if(!empty($_GET['debug_php'])) {
					tpt_dump('floor($layer[\'cX\']/2)='.floor($layer['cX']/2));
					tpt_dump('$cXex = floor($layer[\'cX\']/2); '.$cXex.' = floor('.$layer['cX'].'/2);');
					tpt_dump('$cXelement = (floor($layer[\'cX\']/2) - 5); '.$cXelement.' = (floor('.$layer['cX'].'/2) - 5);');
				}
				//tpt_dump($msg_module->moduleData['pname'][$cpf_module->moduleData['id'][$imsg]['pname']]);
				if (!empty($cpf_module->moduleData['id'][$imsg]['pname']) && !empty($msg_module->moduleData['pname'][$cpf_module->moduleData['id'][$imsg]['pname']]['back'])) {
					$cPLelement += (floor($layer['cX']/2)+5);
					if(!empty($_GET['debug_php'])) {
						tpt_dump('$cPLelement += (floor($layer[\'cX\']/2) + 5); '.$cPLelement.' = '.($cPLelement-(floor($layer['cX']/2)+5)).' + (floor('.$layer['cX'].'/2) + 5);');
					}
				} else {
					$cPRelement += (floor($layer['cX']/2)+5);
					if(!empty($_GET['debug_php'])) {
						tpt_dump('$cPRelement += (floor($layer[\'cX\']/2) + 5); '.$cPRelement.' = '.($cPRelement-(floor($layer['cX']/2)+5)).' + (floor('.$layer['cX'].'/2) + 5);');
					}
				}
			}
		}
		$layer['cX'] = $cXelement;
		$layer['cPL'] = $cPLelement;
		$layer['cPR'] = $cPRelement;
		if(!empty($_GET['debug_php'])) {
			tpt_dump('$layer[\'cX\'] = $cXelement; '.$cXelement);
			tpt_dump('$layer[\'cPL\'] = $cPLelement; '.$cPLelement);
			tpt_dump('$layer[\'cPR\'] = $cPRelement; '.$cPRelement);
		}

		$s = array();
		$partsjoin = array();
		foreach($messages as $tid=>$msg) {
			$msgfld = $cpf_module->moduleData['id'][$tid];

			$text = $msg;
			if(empty($text)) {
				$text = ' ';
			}

			$text_mask = self::cc_m_text($vars, $cXelement, $cYelement, $text, array('font'=>$layer['font']));
			$metrics[$tid] = $text_mask['metrics'];
			$text_mask = $text_mask['command'];

			$clpymax = $metrics[$tid]['y'];
			if(!empty($_GET['debug_php'])) {
				tpt_dump('$clpymax = $metrics[$tid][\'y\']; '.$clpymax);
			}

			$clptxt = array();
			$clptxt[$tid] = $metrics[$tid];
			$xsum = $clptxt[$tid]['x'];
			if(!empty($_GET['debug_php'])) {
				tpt_dump('$xsum = $clptxt[$tid][\'x\']; '.$xsum.' = '.$clptxt[$tid]['x'].'; ($tid='.$tid.', $pname: '.$msgfld['pname'].')');
			}
			foreach ($clipart as $ctid => $clp) {
				$clpdata = $cpf_module->moduleData['id'][$ctid];
				//$cmsg = $cpf_module->moduleData['id'][$clpdata['clipart_text_id']];

				$c = $clipart_module->getClipartPath($vars, $clp);
				if(!empty($_GET['debug_php'])) {
					tpt_dump('CLIPART: ($ctid: '.$ctid.', $pname: '.$clpdata['pname'].', clp: '.$c.')');
				}

				$fsize = <<< EOT
{$bp}{$im_bin} \
-density 1200 \
-resize {$cXelement}x{$clpymax} \
$c \
-format "%@" \
info:
EOT;
				if(!empty($_GET['debug_php'])) {
					tpt_dump($fsize);
				}
				$fsize = self::exec_command($vars, $fsize, '', '', $s, 'size_'.$clpdata['pname'], 1);
				if(!empty($_GET['debug_php'])) {
					tpt_dump($fsize);
				}
				$metrics[$ctid] = preg_split('#\+|-#', $fsize);
				$metrics[$ctid] = array_shift($metrics[$ctid]);
				$metrics[$ctid] = explode('x', $metrics[$ctid]);
				$metrics[$ctid] = array('x'=>$metrics[$ctid][0], 'y'=>$metrics[$ctid][1], 'proportion'=>$metrics[$ctid][0]/$metrics[$ctid][1]);
				if(!empty($_GET['debug_php'])) {
					tpt_dump($metrics[$ctid]['x'].'/'.$metrics[$ctid]['y'].'='.$metrics[$ctid]['proportion']);
				}

				//tpt_dump($tid);
				//tpt_dump($clpdata['clipart_text_id']);
				if($tid == $clpdata['clipart_text_id']) {
					if(isset($layer[$clpdata['pname']])) {
						$clptxt[$ctid] = $metrics[$ctid];
						if(!empty($_GET['debug_php'])) {
							tpt_dump('$xsum; '.$xsum);
						}
						$xsum += $clptxt[$ctid]['x'];
						if(!empty($_GET['debug_php'])) {
							tpt_dump('$xsum += $clptxt[$ctid][\'x\']; '.$xsum.' = '.($xsum-$clptxt[$ctid]['x']).'+'.$clptxt[$ctid]['x'].'; ($ctid='.$ctid.', $pname='.$clpdata['pname'].')');
						}
					}
				}
			}

			$parts = array();
			$msggrvt = '-gravity Center';
			if(!empty($layout['clipart_leftright'])) {
				//tpt_dump($xsum);
				$diff_proportion = 1;
				$diff = 0;
				$diff_part = 0;
				if(!empty($_GET['debug_php'])) {
					tpt_dump('$diff_proportion = 1;');
					tpt_dump('$diff = 0;');
					tpt_dump('$diff_part = 0;');
				}
				if($cXelement < $xsum) {
					$diff_proportion = $cXelement/$xsum;
					$diff = $xsum - $cXelement;
					$diff_part = ceil($diff/count($clptxt));
					if(!empty($_GET['debug_php'])) {
						tpt_dump('$diff_proportion = $cXelement/$xsum; '.$diff_proportion.' = '.$cXelement.'/'.$xsum.';');
						tpt_dump('$diff = $xsum - $cXelement; '.$diff.' = '.$xsum.' - '.$cXelement.';');
						tpt_dump('$diff_part = ceil($diff/count($clptxt)); '.$diff_part.' = ceil('.$diff.'/'.count($clptxt).');');
					}
				}
				/*
				if(!empty($_GET['debug_php'])) {
					tpt_dump('$xsum -= ($diff_part*count($clptxt)); '.$xsum.' = '.($xsum+($diff_part*count($clptxt))).' - ('.$diff_part.'*'.count($clptxt).');');
				}
				*/

				$clips = array();
				$pmsg = null;
				$xsum = 0;
				foreach($clptxt as $clptxtid=>$ct) {
					$fld = $cpf_module->moduleData['id'][$clptxtid];
					if(!empty($_GET['debug_php'])) {
						tpt_dump('TEXT/CLIPART: ($clptxtid: '.$clptxtid.', $pname: '.$fld['pname'].')');
					}
					if($fld['text']) {
						$pmsg = $ct;

						$msgx = floor($ct['x']*$diff_proportion);
						$msgy = (!empty($ct['proportion'])?floor($msgx/$ct['proportion']):0);

						$metrics[$tid] = self::a_get_text_metrics($vars, $msgx, $msgy, $text, array(), '\\', null, null, $s, 'a_get_text_metrics-final0', 'a_get_text_metrics-final0');
					} else {
						$clpgrvt = '-gravity West';
						if(!empty($fld['orientation'])) {
							$clpgrvt = '-gravity East';
						}
						$c = $clipart_module->getClipartPath($vars, $clipart[$clptxtid], TPT_RESOURCE_DIR.DIRECTORY_SEPARATOR.'edited_clipart');
						$clpx = floor($ct['x']*$diff_proportion);
						$clpy = floor($clpx/$ct['proportion']);
						if(!empty($_GET['debug_php'])) {
							tpt_dump('$clpx = floor($ct[\'x\']*$diff_proportion); ' . $clpx.' = floor('.$ct['x'].'*'.$diff_proportion.');');
							tpt_dump('$clpy = floor($clpx/$ct[\'proportion\']); ' . $clpy.' = floor('.$clpx.'/'.$ct['proportion'].');');
						}
						$clpy = min($clpy, $metrics[$tid]['y']);

						$fsize = <<< EOT
{$bp}{$im_bin} \
-background 'transparent' \
-fill '#FFFFFF' \
-trim \
+repage \
-density 1200 \
-size x{$clpy} \
-resize {$clpx}x{$clpy} \
$c \
-format "%@" \
info:
EOT;
						if (!empty($_GET['debug_php'])) {
							tpt_dump($fsize);
						}
						$csize = self::exec_command($vars, $fsize, '', '', $s, 'clipart_metrics_' . $fld['pname'], 1);
						if (!empty($_GET['debug_php'])) {
							tpt_dump($csize);
						}
						$cmetrics[$clptxtid] = preg_split('#\+|-#', $csize);
						$cmetrics[$clptxtid] = array_shift($cmetrics[$clptxtid]);
						$cmetrics[$clptxtid] = explode('x', $cmetrics[$clptxtid]);
						$cmetrics[$clptxtid] = array('x'=>$cmetrics[$clptxtid][0], 'y'=>$cmetrics[$clptxtid][1]); // no proportion because of possible division by zero

						$clips[$fld['orientation']] = $cmetrics[$clptxtid];
						$clpx = $cmetrics[$clptxtid]['x'];
						if (!empty($_GET['debug_php'])) {
							tpt_dump('$clpx = $cmetrics[$clptxtid][\'x\']; '.$clpx);
						}
						$xsum += $clpx;
						if (!empty($_GET['debug_php'])) {
							tpt_dump('$xsum = $xsum + $clpx; '.$xsum.' = '.($xsum-$clpx).' + '.$clpx.';');
						}

						$parts[$clptxtid] = <<< EOT
\( \
	-trim \
	-background 'transparent' \
	-fill '#FFFFFF' \
	-density 1200 \
	-size x{$clpy} \
	-resize {$clpx}x{$clpy} \
	MSVG:$c \
\) \
$clpgrvt \
-compose Over \
-composite \
EOT;
					}
				}

				if(isset($clips[0]) && isset($clips[1])) {
					$msgoffset = $clips[0]['x'] - $clips[1]['x'];
					if(!empty($_GET['debug_php'])) {
						tpt_dump('$msgoffset = $clips[0][\'x\'] - $clips[1][\'x\']; '.$msgoffset.' = '.$clips[0]['x'].' - '.$clips[1]['x'].';');
					}
					$msggrvt = '-gravity Center -geometry '.sprintf("%+d",floor($msgoffset/2)).'+0';
				} else if(isset($clips[0])) {
					$msggrvt = '-gravity East';
				} else if(isset($clips[1])) {
					$msggrvt = '-gravity West';
				}

				if(!empty($pmsg)) {
					$msgx = $metrics[$tid]['x'];
					$msgy = $metrics[$tid]['y'];
					$pointsize = $metrics[$tid]['ps'];
					$xsum += $msgx;

					$text_mask = self::cc_m_text($vars, $msgx, $msgy, $text, array('font'=>$layer['font']));
					$metrics[$tid] = $text_mask['metrics'];
					$tm = $text_mask['command'];
					$parts[$tid] = <<< EOT
$tm
$msggrvt \
-compose Over \
-composite \
-geometry +0+0 \
EOT;
				}
			}
			//tpt_dump($clptxt);
			$parts = implode("\n", $parts);
			$parts = <<< EOT
\( \
	-size {$xsum}x{$cYelement} \
	xc:transparent \
	$parts
\) \
EOT;


			$pgravity = '-gravity Center';
			if (!empty($layout['text_topbottom']) && (count($messages)>1)) {
				if(!empty($msg_module->moduleData['pname'][$msgfld['pname']]['line2'])) {
					$pgravity = '-gravity South';
				} else {
					$pgravity = '-gravity North';
				}
			}


			//tpt_dump($parts, true);
			$effects = array();

			if(!empty($layer['message_color'])) {
				$cprops = $color_module->getColorProps($vars, $layer['message_color']);
				$col = (!empty($cprops['hex']) ? '#' . $cprops['hex'] : 'none');
				$effects[] = self::cc_e_color_overlay($vars, $col, $parts);
			} else if ((!empty($layer['color']) && ($layer['color'] != 'transparent') && ($layer['color'] != 'none'))) {
				$effects[] = self::cc_e_color_overlay($vars, $layer['color'], $parts);
			}
			if(!empty($layer['effects_shadow_inner'])) {
				$width_top = explode(',', $layer['effects_shadow_inner_width_top']);
				$width_right = explode(',', $layer['effects_shadow_inner_width_right']);
				$width_bottom = explode(',', $layer['effects_shadow_inner_width_bottom']);
				$width_left = explode(',', $layer['effects_shadow_inner_width_left']);
				$spread_top = explode(',', $layer['effects_shadow_inner_spread_top']);
				$spread_right = explode(',', $layer['effects_shadow_inner_spread_right']);
				$spread_bottom = explode(',', $layer['effects_shadow_inner_spread_bottom']);
				$spread_left = explode(',', $layer['effects_shadow_inner_spread_left']);
				$color_top = explode(',', $layer['effects_shadow_inner_color_top']);
				$color_right = explode(',', $layer['effects_shadow_inner_color_right']);
				$color_bottom = explode(',', $layer['effects_shadow_inner_color_bottom']);
				$color_left = explode(',', $layer['effects_shadow_inner_color_left']);
				$opacity_top = explode(',', $layer['effects_shadow_inner_opacity_top']);
				$opacity_right = explode(',', $layer['effects_shadow_inner_opacity_right']);
				$opacity_bottom = explode(',', $layer['effects_shadow_inner_opacity_bottom']);
				$opacity_left = explode(',', $layer['effects_shadow_inner_opacity_left']);
				foreach($color_top as $key=>$val) {
					$effects[] = self::cc_e_shadow_inner($vars, array($width_top[$key], $width_right[$key], $width_bottom[$key], $width_left[$key]), array($spread_top[$key], $spread_right[$key], $spread_bottom[$key], $spread_left[$key]), array($color_top[$key], $color_right[$key], $color_bottom[$key], $color_left[$key]), array($opacity_top[$key], $opacity_right[$key], $opacity_bottom[$key], $opacity_left[$key]), $parts);
				}
			}
			if (!empty($_GET['debug_php'])) {
				tpt_dump('Effects:');
				tpt_dump($effects);
			}
			$effects = implode("\n", $effects);
			$partsjoin[] = <<< EOT
\( \
	-size {$xsum}x{$cYelement} \
	xc:transparent \
	\( \
$effects
		-background transparent \
		-compose Over \
		-flatten \
	\) \
	-gravity Center \
	-compose Over \
	-composite \
\) \
$pgravity \
-compose Over \
-composite \
EOT;
			//$parts[$tid] = self::cc_e_stroke_inner($vars, 1, '#FFFFFF', $text_mask);
		}
		//tpt_dump($partsjoin);
		$partsjoin = implode("\n", $partsjoin);
		$pjtrim = trim($partsjoin);
		if(empty($pjtrim)) {
			$partsjoin = '\\';
		}
		//tpt_dump($partsjoin, true);

		$command = <<< EOT
{$bp}{$im_bin} \
-respect-parenthesis \
\( \
	\( \
		\( \
			-size {$cXelement}x{$cY} \
			xc:transparent \
		\) \
$partsjoin
	\) \
\) \

EOT;

		return $command;

	}


	static function c_message(&$vars, &$layer, &$out='', &$steps=array()) {
		//tpt_dump($layer, true);

		$color_module = getModule($vars, 'BandColor');
		$msg_module = getModule($vars, 'BandMessage');
		$cpf_module = getModule($vars, 'CustomProductField');
		$fonts_module = getModule($vars, 'BandFont');
		$layouts_module = getModule($vars, 'BandLayout');
		$fonts = $fonts_module->moduleData['id'];
		$clipart_module = getModule($vars, 'BandClipart');

		$bp = BIN_PATH;
		if(defined('ALT_BIN_PATH')) {
			$bp = ALT_BIN_PATH;
		}
		$im_bin = IMAGEMAGICK_BIN;

		parse_str((isset($layer['options'])?$layer['options']:''), $options);

		//tpt_dump($layer);
		$layout = (!empty($layer['band_layout'])?intval($layer['band_layout'], 10):(!empty($layer['layout'])?intval($layer['layout'], 10):1));
		$layout = $layouts_module->moduleData['id'][$layout];

		$cX = (!empty($layer['cX'])?intval($layer['cX'], 10):1);
		if(!empty($_GET['debug_php'])) {
			tpt_dump('$cX = (!empty($layer[\'cX\'])?intval($layer[\'cX\'], 10):1); '.$cX.' = (!empty('.$layer['cX'].')?intval('.$layer['cX'].', 10):1);');
		}
		$cY = (!empty($layer['cY'])?intval($layer['cY'], 10):1);
		if(!empty($_GET['debug_php'])) {
			tpt_dump('$cY = (!empty($layer[\'cY\'])?intval($layer[\'cY\'], 10):1); '.$cY.' = (!empty('.$layer['cY'].')?intval('.$layer['cY'].', 10):1);');
		}

		$cPL = (!empty($layer['cPL'])?intval($layer['cPL'], 10):0);
		if(!empty($_GET['debug_php'])) {
			tpt_dump('$cPL = (!empty($layer[\'cPL\'])?intval($layer[\'cPL\'], 10):0) '.$cPL.' = (!empty('.$layer['cPL'].')?intval('.$layer['cPL'].', 10):1);');
		}
		$cPR = (!empty($layer['cPR'])?intval($layer['cPR'], 10):0);
		if(!empty($_GET['debug_php'])) {
			tpt_dump('$cPR = (!empty($layer[\'cPR\'])?intval($layer[\'cPR\'], 10):0) '.$cPR.' = (!empty('.$layer['cPR'].')?intval('.$layer['cPR'].', 10):1);');
		}
		$cPT = (!empty($layer['cPT'])?intval($layer['cPT'], 10):0);
		if(!empty($_GET['debug_php'])) {
			tpt_dump('$cPT = (!empty($layer[\'cPT\'])?intval($layer[\'cPT\'], 10):0) '.$cPT.' = (!empty('.$layer['cPT'].')?intval('.$layer['cPT'].', 10):1);');
		}
		$cPB = (!empty($layer['cPB'])?intval($layer['cPB'], 10):0);
		if(!empty($_GET['debug_php'])) {
			tpt_dump('$cPB = (!empty($layer[\'cPB\'])?intval($layer[\'cPB\'], 10):0) '.$cPB.' = (!empty('.$layer['cPB'].')?intval('.$layer['cPB'].', 10):1);');
		}

		$font = FONTS_PATH.DIRECTORY_SEPARATOR.(!empty($layer['font'])?$fonts[$layer['font']]['file']:DEFAULT_FONT_NAME);
		$font = <<< EOT
-font '$font'
EOT;

		$color = <<< EOT
-fill none
EOT;
		$strokecolor = escapeshellarg('#FFFFFF');
		$stroke = '-stroke '.$strokecolor;
		if (!empty($layer['color'])) {
			$cprops = $color_module->getColorProps($vars, $layer['color']);
			//tpt_dump($cprops);
			$strokecolor = (!empty($cprops['colordata']['led_hex']) ? '#' . $cprops['colordata']['led_hex'] : 'none');
			$strokecolor = escapeshellarg($strokecolor);
			$stroke = <<< EOT
-stroke $strokecolor
EOT;
		}

		$bg = <<< EOT
-background 'transparent'
EOT;

		$gravity = '';
		if(!empty($layer['gravity'])) {
			$gravity = escapeshellarg($layer['gravity']);
			$gravity = <<< EOT
-gravity $gravity
EOT;
		}

		$strokewidth = '';
		if(!empty($layer['stroke'])) {
			/*
			$stroke = escapeshellarg($layer['stroke_color']);
			$stroke = <<< EOT
-stroke $stroke
EOT;
			*/


			if(!empty($layer['stroke_width'])) {
				$c_strokewidth = intval($layer['stroke_width'], 10)+2;
				$c_strokewidth = <<< EOT
-strokewidth $c_strokewidth
EOT;

				$strokewidth = intval($layer['stroke_width'], 10);
				$strokewidth = <<< EOT
-strokewidth $strokewidth
EOT;
			}
		}

		$inner_shadow = '';
		$inner_glow = '';
		$drop_shadow = '';
		$outer_glow = '';


		$kern = '';
		if(!empty($layer['kern'])) {
			$kern = escapeshellarg($layer['kern']);
			$kern = <<< EOT
-kerning $kern
EOT;
		}

		$targets = explode(',', $layer['target']);
		$targets = array_combine($targets, $targets);
		$targets = array_intersect_key($cpf_module->moduleData['id'], $targets);

		$messages = array();
		$m = array();
		$clipart = array();
		$metrics = array();
		$cmetrics = array();
		foreach($targets as $tid=>$target) {
			if(isset($layer[$target['pname']])) {
				if(!empty($target['text'])) {
					$messages[$tid] = $layer[$target['pname']];
				} else if(!empty($target['clipart'])) {
					$clipart[$tid] = $layer[$target['pname']];
				}
			}
		}

		$ncmessages = array();
		$ncparams = explode('|', $layer['nullcheck_preview_params_ids']);
		foreach($ncparams as $ncparam) {
			$ncp = explode(':', $ncparam);
			if(!empty($cpf_module->moduleData['id'][$ncp[0]]) && !empty($cpf_module->moduleData['id'][$ncp[0]]['text'])) {
				$ncmessages[$ncp[0]] = $cpf_module->moduleData['id'][$ncp[0]];
			}
		}

		$cXelement = $cX;
		$cYelement = $cY;
		if(!empty($_GET['debug_php'])) {
			tpt_dump('$cXelement = $cX; '.$cXelement.' = '.$cX.';');
			tpt_dump('$cYelement = $cY; '.$cYelement.' = '.$cY.';');
		}
		if (!empty($layout['text_topbottom']) && (count($messages)>1)) {
			$cYelement = floor($layer['cY']/count($messages));
			if(!empty($_GET['debug_php'])) {
				tpt_dump('$cYelement = floor($layer[\'cY\']/count($messages)); '.$cYelement.' = floor('.$layer['cY'].'/'.count($messages).');');
			}
		}
		$cYelement_double = $cYelement*2;
		if(!empty($_GET['debug_php'])) {
			tpt_dump('$cYelement_double = $cYelement*2; '.$cYelement_double.' = '.$cYelement.'*2;');
		}
		$cPLelement = $cPL;
		$cPRelement = $cPR;
		if(!empty($_GET['debug_php'])) {
			tpt_dump('$cPLelement = $cPL; '.$cPLelement.' = '.$cPL.';');
			tpt_dump('$cPRelement = $cPR; '.$cPRelement.' = '.$cPR.';');
		}
		if(!empty($ncmessages)) {
			$ncmsg = reset($ncmessages);
			//tpt_dump($layer[$ncmsg['pname']]);
			//tpt_dump($layout['text_frontback']);
			//tpt_dump($messages, true);
			if (!empty($layout['text_frontback']) && !empty($ncmessages) && !empty($layer[$ncmsg['pname']])) {
				$imsg = reset($messages);
				$imsg = key($messages);
				$cXex = floor($layer['cX']/2);
				$cXelement = (floor($layer['cX']/2) - 5);
				if(!empty($_GET['debug_php'])) {
					tpt_dump('floor($layer[\'cX\']/2)='.floor($layer['cX']/2));
					tpt_dump('$cXex = floor($layer[\'cX\']/2); '.$cXex.' = floor('.$layer['cX'].'/2);');
					tpt_dump('$cXelement = (floor($layer[\'cX\']/2) - 5); '.$cXelement.' = (floor('.$layer['cX'].'/2) - 5);');
				}
				//tpt_dump($msg_module->moduleData['pname'][$cpf_module->moduleData['id'][$imsg]['pname']]);
				if (!empty($cpf_module->moduleData['id'][$imsg]['pname']) && !empty($msg_module->moduleData['pname'][$cpf_module->moduleData['id'][$imsg]['pname']]['back'])) {
					$cPLelement += (floor($layer['cX']/2)+5);
					if(!empty($_GET['debug_php'])) {
						tpt_dump('$cPLelement += (floor($layer[\'cX\']/2) + 5); '.$cPLelement.' = '.($cPLelement-(floor($layer['cX']/2)+5)).' + (floor('.$layer['cX'].'/2) + 5);');
					}
				} else {
					$cPRelement += (floor($layer['cX']/2)+5);
					if(!empty($_GET['debug_php'])) {
						tpt_dump('$cPRelement += (floor($layer[\'cX\']/2) + 5); '.$cPRelement.' = '.($cPRelement-(floor($layer['cX']/2)+5)).' + (floor('.$layer['cX'].'/2) + 5);');
					}
				}
			}
		}
		$layer['cX'] = $cXelement;
		$layer['cPL'] = $cPLelement;
		$layer['cPR'] = $cPRelement;
		if(!empty($_GET['debug_php'])) {
			tpt_dump('$layer[\'cX\'] = $cXelement; '.$cXelement);
			tpt_dump('$layer[\'cPL\'] = $cPLelement; '.$cPLelement);
			tpt_dump('$layer[\'cPR\'] = $cPRelement; '.$cPRelement);
		}


		/*
		$resize = '';
		if(!empty($layer['snug_fit_label'])) {
			$resize = <<< EOT
-resize {$cX}x{$cY}
EOT;
		}
		*/

		$pointsize = 0;
		//tpt_dump($messages);
		//tpt_dump($clipart);
		$s = array();
		$partsjoin = array();
		//tpt_dump($messages, true);
		foreach($messages as $tid=>$msg) {
			$msgdata = $cpf_module->moduleData['id'][$tid];
			if(!empty($_GET['debug_php'])) {
				tpt_dump('TEXT: ($tid: '.$tid.', $pname: '.$msgdata['pname'].', text: '.$msg.')');
			}

			$metrics[$tid] = self::get_text_metrics($vars, $tid, $msg, $cXelement, $cYelement, $stroke, $strokewidth, $bg, $font, $kern, $s);
			$clpymax = $metrics[$tid]['y'];
			if(!empty($_GET['debug_php'])) {
				tpt_dump('$clpymax = $metrics[$tid][\'y\']; '.$clpymax);
			}

			$parts = array();
			$clptxt = array();
			$clptxt[$tid] = $metrics[$tid];
			$xsum = $clptxt[$tid]['x'];
			if(!empty($_GET['debug_php'])) {
				tpt_dump('$xsum = $clptxt[$tid][\'x\']; '.$xsum.' = '.$clptxt[$tid]['x'].'; ($tid='.$tid.', $pname: '.$msgdata['pname'].')');
			}
			foreach ($clipart as $ctid => $clp) {
				$clpdata = $cpf_module->moduleData['id'][$ctid];
				//$cmsg = $cpf_module->moduleData['id'][$clpdata['clipart_text_id']];

				$c = $clipart_module->getClipartPath($vars, $clp);
				if(!empty($_GET['debug_php'])) {
					tpt_dump('CLIPART: ($ctid: '.$ctid.', $pname: '.$clpdata['pname'].', clp: '.$c.')');
				}

				$fsize = <<< EOT
{$bp}{$im_bin} \
-density 1200 \
-resize {$cXelement}x{$clpymax} \
$c \
-format "%@" \
info:
EOT;
				if(!empty($_GET['debug_php'])) {
					tpt_dump($fsize);
				}
				$fsize = self::exec_command($vars, $fsize, '', '', $s, 'size_'.$clpdata['pname'], 1);
				if(!empty($_GET['debug_php'])) {
					tpt_dump($fsize);
				}
				$metrics[$ctid] = preg_split('#\+|-#', $fsize);
				$metrics[$ctid] = array_shift($metrics[$ctid]);
				$metrics[$ctid] = explode('x', $metrics[$ctid]);
				$metrics[$ctid] = array('x'=>$metrics[$ctid][0], 'y'=>$metrics[$ctid][1], 'proportion'=>$metrics[$ctid][0]/$metrics[$ctid][1]);
				if(!empty($_GET['debug_php'])) {
					tpt_dump($metrics[$ctid]['x'].'/'.$metrics[$ctid]['y'].'='.$metrics[$ctid]['proportion']);
				}

				//tpt_dump($tid);
				//tpt_dump($clpdata['clipart_text_id']);
				if($tid == $clpdata['clipart_text_id']) {
					if(isset($layer[$clpdata['pname']])) {
						$clptxt[$ctid] = $metrics[$ctid];
						if(!empty($_GET['debug_php'])) {
							tpt_dump('$xsum; '.$xsum);
						}
						$xsum += $clptxt[$ctid]['x'];
						if(!empty($_GET['debug_php'])) {
							tpt_dump('$xsum += $clptxt[$ctid][\'x\']; '.$xsum.' = '.($xsum-$clptxt[$ctid]['x']).'+'.$clptxt[$ctid]['x'].'; ($ctid='.$ctid.', $pname='.$clpdata['pname'].')');
						}
					}
				}
			}

			//tpt_dump($clptxt);
			if(!empty($layout['clipart_leftright'])) {
				//tpt_dump($xsum);
				$diff_proportion = 1;
				$diff = 0;
				$diff_part = 0;
				if(!empty($_GET['debug_php'])) {
					tpt_dump('$diff_proportion = 1;');
					tpt_dump('$diff = 0;');
					tpt_dump('$diff_part = 0;');
				}
				if($cXelement < $xsum) {
					$diff_proportion = $cXelement/$xsum;
					$diff = $xsum - $cXelement;
					$diff_part = ceil($diff/count($clptxt));
					if(!empty($_GET['debug_php'])) {
						tpt_dump('$diff_proportion = $cXelement/$xsum; '.$diff_proportion.' = '.$cXelement.'/'.$xsum.';');
						tpt_dump('$diff = $xsum - $cXelement; '.$diff.' = '.$xsum.' - '.$cXelement.';');
						tpt_dump('$diff_part = ceil($diff/count($clptxt)); '.$diff_part.' = ceil('.$diff.'/'.count($clptxt).');');
					}
				}
				if(!empty($_GET['debug_php'])) {
					tpt_dump('$xsum -= ($diff_part*count($clptxt)); '.$xsum.' = '.($xsum+($diff_part*count($clptxt))).' - ('.$diff_part.'*'.count($clptxt).');');
				}
				$clips = array();
				$pmsg = null;
				$xsum = 0;
				foreach($clptxt as $clptxtid=>$ct) {
					$fld = $cpf_module->moduleData['id'][$clptxtid];
					if(!empty($_GET['debug_php'])) {
						tpt_dump('TEXT/CLIPART: ($clptxtid: '.$clptxtid.', $pname: '.$fld['pname'].')');
					}
					if($fld['text']) {
						$pmsg = $ct;
						$text = $msg;
						if(empty($text)) {
							$text = ' ';
						}
						$text = ''.escapeshellarg(str_replace('\\', '\\\\', $text)).'';
						if(!empty($_GET['debug_php'])) {
							tpt_dump('DIMS BEFORE: ($ct[\'x\']='.$ct['x'].', $ct[\'y\']='.$ct['y'].')');
						}
						//$msgx = $ct['x'] - $diff_part;
						//$msgy = $ct['y'] - ceil($diff_part/$ct['proportion']);
						$msgx = floor($ct['x']*$diff_proportion);
						$msgy = floor($msgx/$ct['proportion']);
						if(!empty($_GET['debug_php'])) {
							//tpt_dump('$msgx = $ct[\'x\'] - $diff_part; ' . $msgx.' = '.$ct['x'].' - '.$diff_part.';');
							//tpt_dump('$msgy = $ct[\'y\'] - ceil($diff_part/$ct[\'proportion\']); ' . $msgy.' = '.$ct['y'].' - ceil('.$diff_part.'/'.$ct['proportion'].');');
							tpt_dump('$msgx = floor($ct[\'x\']*$diff_proportion); ' . $msgx.' = floor('.$ct['x'].'*'.$diff_proportion.');');
							tpt_dump('$msgy = floor($msgx/$ct[\'proportion\']); ' . $msgy.' = floor('.$msgx.'/'.$ct['proportion'].');');
							tpt_dump('BEST FIT METRICS BEFORE: ($tid='.$tid.', $msg='.$msg.', $msgx='.$msgx.', $msgy='.$msgy.')');
							tpt_dump($metrics[$clptxtid]['x'].'/'.$metrics[$clptxtid]['y'].'='.$metrics[$clptxtid]['proportion']);
						}
						$metrics[$clptxtid] = self::get_text_metrics($vars, $tid, $msg, $msgx, $msgy, $stroke, $strokewidth, $bg, $font, $kern, $s);

						/*
						$parts[$clptxtid] = <<< EOT
			\( \
				-size {$msgx_double}x{$msgy_double} \
				-gravity Center \
				-pointsize $pointsize \
				-stroke '#FFFFFF' \
				$strokewidth \
				$bg \
				\
				-fill none \
				$font \
				$kern \
				label:$text \
				-trim \
				+repage \
				-extent {$msgx}x{$msgy} \
			\) \
			$msggrvt \
			-compose Over \
			-composite \
			-geometry +0+0 \
EOT;
						*/
					} else {
						$clpgrvt = '-gravity West';
						if(!empty($fld['orientation'])) {
							$clpgrvt = '-gravity East';
						}
						$c = $clipart_module->getClipartPath($vars, $clipart[$clptxtid]);
						//$clpx = $ct['x'] - $diff_part;
						////$clpx = $ct['x'];
						//$clpy = $ct['y'] - ceil($diff_part/$ct['proportion']);
						$clpx = floor($ct['x']*$diff_proportion);
						$clpy = floor($clpx/$ct['proportion']);
						if(!empty($_GET['debug_php'])) {
							//tpt_dump('$clpx = $ct[\'x\'] - $diff_part; ' . $clpx.' = '.$ct['x'].' - '.$diff_part.';');
							//tpt_dump('$clpy = $ct[\'y\'] - ceil($diff_part/$ct[\'proportion\']); ' . $clpy.' = '.$ct['y'].' - ceil('.$diff_part.'/'.$ct['proportion'].');');
							tpt_dump('$clpx = floor($ct[\'x\']*$diff_proportion); ' . $clpx.' = floor('.$ct['x'].'*'.$diff_proportion.');');
							tpt_dump('$clpy = floor($clpx/$ct[\'proportion\']); ' . $clpy.' = floor('.$clpx.'/'.$ct['proportion'].');');
						}
						$clpy = min($clpy, $metrics[$tid]['y']);
						$clpy_shave = $clpy-2;
						if(!empty($_GET['debug_php'])) {
							tpt_dump('$clpy = min($clpy, $metrics[$tid][\'y\']); ' . $clpy.' = min('.$clpy.', '.$metrics[$tid]['y'].');');
							tpt_dump('$clpy_shave = $clpy-2; ' . $clpy_shave.' = '.$clpy.'-2;');
						}

						$c_c = <<< EOT
						\( \
							-stroke none \
							-strokewidth 0 \
							$bg \
							$color \
							-trim \
							+repage \
							-density 1200 \
							-size x{$clpy_shave} \
							-resize {$clpx}x{$clpy_shave} \
							$c \
						\)
EOT;
						$c_c2 = <<< EOT
					\( \
						-stroke none \
						-strokewidth 0 \
						$bg \
						$color \
						-trim \
						+repage \
						-density 1200 \
						-size x{$clpy_shave} \
						-resize {$clpx}x{$clpy_shave} \
						$c \
					\)
EOT;
						$fsize = <<< EOT
{$bp}{$im_bin} \
-stroke none \
-strokewidth 0 \
$bg \
$color \
-trim \
+repage \
-density 1200 \
-size x{$clpy} \
-resize {$clpx}x{$clpy} \
$c \
-format "%@" \
info:
EOT;
						if (!empty($_GET['debug_php'])) {
							tpt_dump($fsize);
						}
						$csize = self::exec_command($vars, $fsize, '', '', $s, 'clipart_metrics_' . $fld['pname'], 1);
						if (!empty($_GET['debug_php'])) {
							tpt_dump($csize);
						}
						//tpt_dump($csize);
						$cmetrics[$clptxtid] = preg_split('#\+|-#', $csize);
						//tpt_dump($cmetrics[$clpid]);
						$cmetrics[$clptxtid] = array_shift($cmetrics[$clptxtid]);
						//tpt_dump($cmetrics[$clpid]);
						$cmetrics[$clptxtid] = explode('x', $cmetrics[$clptxtid]);
						$cmetrics[$clptxtid] = array('x'=>$cmetrics[$clptxtid][0], 'y'=>$cmetrics[$clptxtid][1]); // no proportion because of possible division by zero
						//tpt_dump($cmetrics[$clpid]);
						if (empty($cmetrics[$clptxtid]['x']) || empty($cmetrics[$clptxtid]['y'])) {
							//tpt_dump('asd');
							//tpt_dump('asd', true);
							$c_c = <<< EOT
						\( \
							-stroke '#FFFFFF' \
							-strokewidth 1 \
							$bg \
							-trim \
							+repage \
							-density 1200 \
							-size x{$clpy_shave} \
							-resize {$clpx}x{$clpy_shave} \
							$c \
						\)
EOT;
							$c_c2 = <<< EOT
					\( \
						-stroke '#FFFFFF' \
						-strokewidth 1 \
						$bg \
						-trim \
						+repage \
						-density 1200 \
						-size x{$clpy_shave} \
						-resize {$clpx}x{$clpy_shave} \
						$c \
					\)
EOT;

							$fsize = <<< EOT
{$bp}{$im_bin} \
-stroke '#FFFFFF' \
-strokewidth 1 \
$bg \
-trim \
+repage \
-density 1200 \
-size x{$clpy} \
-resize {$clpx}x{$clpy} \
$c \
-format "%@" \
info:
EOT;
							if (!empty($_GET['debug_php'])) {
								tpt_dump($fsize);
							}
							$csize = self::exec_command($vars, $fsize, '', '', $s, 'clipart_metrics2_' . $fld['pname'], 1);
							if (!empty($_GET['debug_php'])) {
								tpt_dump($csize);
							}
							//tpt_dump($csize);
							$cmetrics[$clptxtid] = preg_split('#\+|-#', $csize);
							//tpt_dump($cmetrics[$clpid]);
							$cmetrics[$clptxtid] = array_shift($cmetrics[$clptxtid]);
							$cmetrics[$clptxtid] = explode('x', $cmetrics[$clptxtid]);
							$cmetrics[$clptxtid] = array('x'=>$cmetrics[$clptxtid][0], 'y'=>$cmetrics[$clptxtid][1], 'proportion'=>$cmetrics[$clptxtid][0]/$cmetrics[$clptxtid][1]);
						}
						$clips[$fld['orientation']] = $cmetrics[$clptxtid];
						$clpx = $cmetrics[$clptxtid]['x'];
						if (!empty($_GET['debug_php'])) {
							tpt_dump('$clpx = $cmetrics[$clptxtid][\'x\']; '.$clpx);
						}
						$xsum += $clpx;
						if (!empty($_GET['debug_php'])) {
							tpt_dump('$xsum = $xsum + $clpx; '.$xsum.' = '.($xsum-$clpx).' + '.$clpx.';');
						}
						$c_c = <<< EOT
						\( \
							-stroke '#FFFFFF' \
							-strokewidth 1 \
							$bg \
							-trim \
							+repage \
							-density 1200 \
							-size x{$clpy_shave} \
							-resize {$clpx}x{$clpy_shave} \
							$c \
						\)
EOT;
						$c_c2 = <<< EOT
					\( \
						-stroke '#FFFFFF' \
						-strokewidth 1 \
						$bg \
						-trim \
						+repage \
						-density 1200 \
						-size x{$clpy_shave} \
						-resize {$clpx}x{$clpy_shave} \
						$c \
					\)
EOT;

						$parts[$clptxtid] = <<< EOT
			\( \
				-respect-parenthesis \
				-background transparent \
				-page {$clpx}x{$clpy} \
				\
				\( \
					-size {$clpx}x{$clpy} \
					xc:transparent \
					\( \
$c_c \
						\( \
							-size {$clpx}x{$clpy_shave} \
							xc:'#FFFFFF' \
						\) \
						-gravity Center \
						-compose SrcIn \
						-composite \
					\) \
					-gravity Center \
					-compose Over \
					-composite \
				\) \
				\( -clone 0 -repage -1-1 \) \( -clone 0 -repage -1+0 \) \( -clone 0 -repage -1+1 \) \
				\( -clone 0 -repage +0-1 \) \( -clone 0 -repage +0+0 \) \( -clone 0 -repage +0+1 \) \
				\( -clone 0 -repage +1-1 \) \( -clone 0 -repage +1+0 \) \( -clone 0 -repage +1+1 \) \
				-compose Over \
				-flatten \
				\( \
$c_c2 \
					\( \
						-size {$clpx}x{$clpy_shave} \
						xc:white \
					\) \
					-gravity Center \
					-compose SrcIn \
					-composite \
				\) \
				-gravity Center \
				-compose DstOut \
				-composite \
			\) \
			$clpgrvt \
			-compose Over \
			-composite \
EOT;
					}
				}
				if(!empty($pmsg)) {
					$msgx = $metrics[$tid]['x'];
					$msgy = $metrics[$tid]['y'];
					$pointsize = $metrics[$tid]['ps'];
					$xsum += $msgx;
					if(!empty($_GET['debug_php'])) {
						tpt_dump('$msgx = $metrics[$tid][\'x\']; ' . $msgx.' = '.$metrics[$tid]['x'].';');
						tpt_dump('$msgy = $metrics[$tid][\'y\']; ' . $msgy.' = '.$metrics[$tid]['y'].';');
						tpt_dump('$pointsize = $metrics[$tid][\'ps\']; ' . $pointsize.' = '.$metrics[$tid]['ps'].';');
						tpt_dump('$xsum = $xsum + $msgx; ' . $xsum.' = '.($xsum-$msgx).' + '.$msgx.';');
						tpt_dump('BEST FIT METRICS AFTER: ($tid='.$tid.', $msg='.$msg.', $msgx='.$msgx.', $msgy='.$msgy.')');
						//tpt_dump($metrics[$clptxtid]);
						tpt_dump($metrics[$tid]['x'].'/'.$metrics[$tid]['y'].'='.$metrics[$tid]['proportion']);
					}

					$msgx_double = $msgx*2;
					$msgy_double = $msgy*2;

					$msggrvt = '-gravity Center';
					if(isset($clips[0]) && isset($clips[1])) {
						$msgoffset = $clips[0]['x'] - $clips[1]['x'];
						if(!empty($_GET['debug_php'])) {
							tpt_dump('$msgoffset = $clips[0][\'x\'] - $clips[1][\'x\']; '.$msgoffset.' = '.$clips[0]['x'].' - '.$clips[1]['x'].';');
						}
						$msggrvt = '-gravity Center -geometry '.sprintf("%+d",floor($msgoffset/2)).'+0';
					} else if(isset($clips[0])) {
						$msggrvt = '-gravity East';
					} else if(isset($clips[1])) {
						$msggrvt = '-gravity West';
					}

					$parts[$tid] = <<< EOT
			\( \
				-size {$msgx_double}x{$msgy_double} \
				-gravity Center \
				-pointsize $pointsize \
				-stroke '#FFFFFF' \
				$strokewidth \
				$bg \
				\
				-fill none \
				$font \
				$kern \
				label:$text \
				-trim \
				+repage \
				-extent {$msgx}x{$msgy} \
			\) \
			$msggrvt \
			-compose Over \
			-composite \
			-geometry +0+0 \
EOT;
				}
			}
			//tpt_dump($clptxt);
			$parts = implode("\n", $parts);

			$pgravity = '-gravity Center';
			if (!empty($layout['text_topbottom']) && (count($messages)>1)) {
				if(!empty($msg_module->moduleData['pname'][$msgdata['pname']]['line2'])) {
					$pgravity = '-gravity South';
				} else {
					$pgravity = '-gravity North';
				}
			}

			$partsjoin[] = <<< EOT
		\( \
			-size {$xsum}x{$cYelement} \
			xc:transparent \
$parts
		\) \
		$pgravity \
		-compose Over \
		-composite \
EOT;
		}
		//tpt_dump('', true);

		$led_glow = '';
		if(isset($options['led_glow']) && !empty($options['led_glow'])) {
			//tpt_dump($options, true);
			$led_glow = <<< EOT
	\( \
		-clone 0 \
		\( \
			-size {$cX}x{$cY} \
			xc: \
			+noise Random \
			-channel G \
			-threshold 55% \
			-separate \
			-transparent white \
			-channel All \
			-blur 1x1 \
		\) \
		-compose SrcIn \
		-composite \
		\( \
			-size {$cX}x{$cY} \
			xc:$strokecolor \
		\) \
		-compose SrcIn \
		-composite \
	\) \
	-background transparent \
	-compose Hard_Light \
	-flatten
EOT;
		}

		$partsjoin = implode("\n", $partsjoin);
		$command = <<< EOT
{$bp}{$im_bin} \
-respect-parenthesis \
\( \
	\( \
		\( \
			-size {$cXelement}x{$cY} \
			xc:transparent \
		\) \
$partsjoin \
	\) \
$led_glow \
\) \

EOT;
		//tpt_dump($command, true);

		//tpt_dump($command, true);
		return $command;
	}
	static function c_led_message2(&$vars, &$layer, &$out='', &$steps=array()) {
		//tpt_dump($layer, true);

		$color_module = getModule($vars, 'BandColor');
		$msg_module = getModule($vars, 'BandMessage');
		$cpf_module = getModule($vars, 'CustomProductField');
		$fonts_module = getModule($vars, 'BandFont');
		$layouts_module = getModule($vars, 'BandLayout');
		$fonts = $fonts_module->moduleData['id'];
		$clipart_module = getModule($vars, 'BandClipart');

		$bp = BIN_PATH;
		if(defined('ALT_BIN_PATH')) {
			$bp = ALT_BIN_PATH;
		}
		$im_bin = IMAGEMAGICK_BIN;

		//tpt_dump($layer);
		$layout = (!empty($layer['band_layout'])?intval($layer['band_layout'], 10):(!empty($layer['layout'])?intval($layer['layout'], 10):1));
		$layout = $layouts_module->moduleData['id'][$layout];

		$cX = (!empty($layer['cX'])?intval($layer['cX'], 10):1);
		if(!empty($_GET['debug_php'])) {
			tpt_dump('$cX = (!empty($layer[\'cX\'])?intval($layer[\'cX\'], 10):1); '.$cX.' = (!empty('.$layer['cX'].')?intval('.$layer['cX'].', 10):1);');
		}
		$cY = (!empty($layer['cY'])?intval($layer['cY'], 10):1);
		if(!empty($_GET['debug_php'])) {
			tpt_dump('$cY = (!empty($layer[\'cY\'])?intval($layer[\'cY\'], 10):1); '.$cY.' = (!empty('.$layer['cY'].')?intval('.$layer['cY'].', 10):1);');
		}

		$cPL = (!empty($layer['cPL'])?intval($layer['cPL'], 10):0);
		if(!empty($_GET['debug_php'])) {
			tpt_dump('$cPL = (!empty($layer[\'cPL\'])?intval($layer[\'cPL\'], 10):0) '.$cPL.' = (!empty('.$layer['cPL'].')?intval('.$layer['cPL'].', 10):1);');
		}
		$cPR = (!empty($layer['cPR'])?intval($layer['cPR'], 10):0);
		if(!empty($_GET['debug_php'])) {
			tpt_dump('$cPR = (!empty($layer[\'cPR\'])?intval($layer[\'cPR\'], 10):0) '.$cPR.' = (!empty('.$layer['cPR'].')?intval('.$layer['cPR'].', 10):1);');
		}
		$cPT = (!empty($layer['cPT'])?intval($layer['cPT'], 10):0);
		if(!empty($_GET['debug_php'])) {
			tpt_dump('$cPT = (!empty($layer[\'cPT\'])?intval($layer[\'cPT\'], 10):0) '.$cPT.' = (!empty('.$layer['cPT'].')?intval('.$layer['cPT'].', 10):1);');
		}
		$cPB = (!empty($layer['cPB'])?intval($layer['cPB'], 10):0);
		if(!empty($_GET['debug_php'])) {
			tpt_dump('$cPB = (!empty($layer[\'cPB\'])?intval($layer[\'cPB\'], 10):0) '.$cPB.' = (!empty('.$layer['cPB'].')?intval('.$layer['cPB'].', 10):1);');
		}

		$font = FONTS_PATH.DIRECTORY_SEPARATOR.(!empty($layer['font'])?$fonts[$layer['font']]['file']:DEFAULT_FONT_NAME);
		$font = <<< EOT
-font '$font'
EOT;

		$color = <<< EOT
-fill none
EOT;
		$strokecolor = escapeshellarg('#FFFFFF');
		$stroke = '-stroke '.$strokecolor;
		if (!empty($layer['color'])) {
			$cprops = $color_module->getColorProps($vars, $layer['color']);
			//tpt_dump($cprops);
			$strokecolor = (!empty($cprops['colordata']['led_hex']) ? '#' . $cprops['colordata']['led_hex'] : 'none');
			$strokecolor = escapeshellarg($strokecolor);
			$stroke = <<< EOT
-stroke $strokecolor
EOT;
		}

		$bg = <<< EOT
-background 'transparent'
EOT;

		$gravity = '';
		if(!empty($layer['gravity'])) {
			$gravity = escapeshellarg($layer['gravity']);
			$gravity = <<< EOT
-gravity $gravity
EOT;
		}

		$strokewidth = '';
		if(!empty($layer['stroke'])) {
			/*
			$stroke = escapeshellarg($layer['stroke_color']);
			$stroke = <<< EOT
-stroke $stroke
EOT;
			*/


			if(!empty($layer['stroke_width'])) {
				$c_strokewidth = intval($layer['stroke_width'], 10)+2;
				$c_strokewidth = <<< EOT
-strokewidth $c_strokewidth
EOT;

				$strokewidth = intval($layer['stroke_width'], 10);
				$strokewidth = <<< EOT
-strokewidth $strokewidth
EOT;
			}
		}

		$inner_shadow = '';
		$inner_glow = '';
		$drop_shadow = '';
		$outer_glow = '';


		$kern = '';
		if(!empty($layer['kern'])) {
			$kern = escapeshellarg($layer['kern']);
			$kern = <<< EOT
-kerning $kern
EOT;
		}

		$targets = explode(',', $layer['target']);
		$targets = array_combine($targets, $targets);
		$targets = array_intersect_key($cpf_module->moduleData['id'], $targets);

		$messages = array();
		$m = array();
		$clipart = array();
		$metrics = array();
		$cmetrics = array();
		foreach($targets as $tid=>$target) {
			if(isset($layer[$target['pname']])) {
				if(!empty($target['text'])) {
					$messages[$tid] = $layer[$target['pname']];
				} else if(!empty($target['clipart'])) {
					$clipart[$tid] = $layer[$target['pname']];
				}
			}
		}

		$ncmessages = array();
		$ncparams = explode('|', $layer['nullcheck_preview_params_ids']);
		foreach($ncparams as $ncparam) {
			$ncp = explode(':', $ncparam);
			if(!empty($cpf_module->moduleData['id'][$ncp[0]]) && !empty($cpf_module->moduleData['id'][$ncp[0]]['text'])) {
				$ncmessages[$ncp[0]] = $cpf_module->moduleData['id'][$ncp[0]];
			}
		}

		$cXelement = $cX;
		$cYelement = $cY;
		if(!empty($_GET['debug_php'])) {
			tpt_dump('$cXelement = $cX; '.$cXelement.' = '.$cX.';');
			tpt_dump('$cYelement = $cY; '.$cYelement.' = '.$cY.';');
		}
		if (!empty($layout['text_topbottom']) && (count($messages)>1)) {
			$cYelement = floor($layer['cY']/count($messages));
			if(!empty($_GET['debug_php'])) {
				tpt_dump('$cYelement = floor($layer[\'cY\']/count($messages)); '.$cYelement.' = floor('.$layer['cY'].'/'.count($messages).');');
			}
		}
		$cYelement_double = $cYelement*2;
		if(!empty($_GET['debug_php'])) {
			tpt_dump('$cYelement_double = $cYelement*2; '.$cYelement_double.' = '.$cYelement.'*2;');
		}
		$cPLelement = $cPL;
		$cPRelement = $cPR;
		if(!empty($_GET['debug_php'])) {
			tpt_dump('$cPLelement = $cPL; '.$cPLelement.' = '.$cPL.';');
			tpt_dump('$cPRelement = $cPR; '.$cPRelement.' = '.$cPR.';');
		}
		if(!empty($ncmessages)) {
			$ncmsg = reset($ncmessages);
			//tpt_dump($layer[$ncmsg['pname']]);
			//tpt_dump($layout['text_frontback']);
			//tpt_dump($messages, true);
			if (!empty($layout['text_frontback']) && !empty($ncmessages) && !empty($layer[$ncmsg['pname']])) {
				$imsg = reset($messages);
				$imsg = key($messages);
				$cXex = floor($layer['cX']/2);
				$cXelement = (floor($layer['cX']/2) - 5);
				if(!empty($_GET['debug_php'])) {
					tpt_dump('floor($layer[\'cX\']/2)='.floor($layer['cX']/2));
					tpt_dump('$cXex = floor($layer[\'cX\']/2); '.$cXex.' = floor('.$layer['cX'].'/2);');
					tpt_dump('$cXelement = (floor($layer[\'cX\']/2) - 5); '.$cXelement.' = (floor('.$layer['cX'].'/2) - 5);');
				}
				//tpt_dump($msg_module->moduleData['pname'][$cpf_module->moduleData['id'][$imsg]['pname']]);
				if (!empty($cpf_module->moduleData['id'][$imsg]['pname']) && !empty($msg_module->moduleData['pname'][$cpf_module->moduleData['id'][$imsg]['pname']]['back'])) {
					$cPLelement += (floor($layer['cX']/2)+5);
					if(!empty($_GET['debug_php'])) {
						tpt_dump('$cPLelement += (floor($layer[\'cX\']/2) + 5); '.$cPLelement.' = '.($cPLelement-(floor($layer['cX']/2)+5)).' + (floor('.$layer['cX'].'/2) + 5);');
					}
				} else {
					$cPRelement += (floor($layer['cX']/2)+5);
					if(!empty($_GET['debug_php'])) {
						tpt_dump('$cPRelement += (floor($layer[\'cX\']/2) + 5); '.$cPRelement.' = '.($cPRelement-(floor($layer['cX']/2)+5)).' + (floor('.$layer['cX'].'/2) + 5);');
					}
				}
			}
		}
		$layer['cX'] = $cXelement;
		$layer['cPL'] = $cPLelement;
		$layer['cPR'] = $cPRelement;
		if(!empty($_GET['debug_php'])) {
			tpt_dump('$layer[\'cX\'] = $cXelement; '.$cXelement);
			tpt_dump('$layer[\'cPL\'] = $cPLelement; '.$cPLelement);
			tpt_dump('$layer[\'cPR\'] = $cPRelement; '.$cPRelement);
		}


		/*
		$resize = '';
		if(!empty($layer['snug_fit_label'])) {
			$resize = <<< EOT
-resize {$cX}x{$cY}
EOT;
		}
		*/

		$pointsize = 0;
		//tpt_dump($messages);
		//tpt_dump($clipart);
		$s = array();
		$partsjoin = array();
		//tpt_dump($messages, true);
		foreach($messages as $tid=>$msg) {
			$msgdata = $cpf_module->moduleData['id'][$tid];
			if(!empty($_GET['debug_php'])) {
				tpt_dump('TEXT: ($tid: '.$tid.', $pname: '.$msgdata['pname'].', text: '.$msg.')');
			}

			$metrics[$tid] = self::get_text_metrics($vars, $tid, $msg, $cXelement, $cYelement, $stroke, $strokewidth, $bg, $font, $kern, $s);

			$clips = array();
			$parts = array();
			$clptxt = array();
			$clptxt[$tid] = $metrics[$tid];
			$xsum = $clptxt[$tid]['x'];
			if(!empty($_GET['debug_php'])) {
				tpt_dump('$xsum = $clptxt[$tid][\'x\']; '.$xsum.' = '.$clptxt[$tid]['x'].'; ($tid='.$tid.', $pname: '.$msgdata['pname'].')');
			}
			foreach ($clipart as $ctid => $clp) {
				$clpdata = $cpf_module->moduleData['id'][$ctid];
				//$cmsg = $cpf_module->moduleData['id'][$clpdata['clipart_text_id']];

				$c = $clipart_module->getClipartPath($vars, $clp);
				if(!empty($_GET['debug_php'])) {
					tpt_dump('CLIPART: ($ctid: '.$ctid.', $pname: '.$clpdata['pname'].', clp: '.$c.')');
				}

				$fsize = <<< EOT
{$bp}{$im_bin} \
-density 1200 \
-resize {$cXelement}x{$cYelement} \
$c \
-format "%@" \
info:
EOT;
				if(!empty($_GET['debug_php'])) {
					tpt_dump($fsize);
				}
				$fsize = self::exec_command($vars, $fsize, '', '', $s, 'size_'.$clpdata['pname'], 1);
				if(!empty($_GET['debug_php'])) {
					tpt_dump($fsize);
				}
				$metrics[$ctid] = preg_split('#\+|-#', $fsize);
				$metrics[$ctid] = array_shift($metrics[$ctid]);
				$metrics[$ctid] = explode('x', $metrics[$ctid]);
				$metrics[$ctid] = array('x'=>$metrics[$ctid][0], 'y'=>$metrics[$ctid][1], 'proportion'=>$metrics[$ctid][0]/$metrics[$ctid][1]);
				if(!empty($_GET['debug_php'])) {
					tpt_dump($metrics[$ctid]['x'].'/'.$metrics[$ctid]['y'].'='.$metrics[$ctid]['proportion']);
				}

				//tpt_dump($tid);
				//tpt_dump($clpdata['clipart_text_id']);
				if($tid == $clpdata['clipart_text_id']) {
					if(isset($layer[$clpdata['pname']])) {
						$clptxt[$ctid] = $metrics[$ctid];
						$clips[$clpdata['orientation']] = 1;
						if(!empty($_GET['debug_php'])) {
							tpt_dump('$xsum; '.$xsum);
						}
						$xsum += $clptxt[$ctid]['x'];
						if(!empty($_GET['debug_php'])) {
							tpt_dump('$xsum += $clptxt[$ctid][\'x\']; '.$xsum.' = '.($xsum-$clptxt[$ctid]['x']).'+'.$clptxt[$ctid]['x'].'; ($ctid='.$ctid.', $pname='.$clpdata['pname'].')');
						}
					}
				}
			}

			//tpt_dump($clptxt);
			if(!empty($layout['clipart_leftright'])) {
				//tpt_dump($xsum);
				$diff = 0;
				$diff_part = 0;
				if(!empty($_GET['debug_php'])) {
					tpt_dump('$diff = 0;');
					tpt_dump('$diff_part = 0;');
				}
				if($cXelement < $xsum) {
					$diff = $xsum - $cXelement;
					$diff_part = ceil($diff/count($clptxt));
					if(!empty($_GET['debug_php'])) {
						tpt_dump('$diff = $xsum - $cXelement; '.$diff.' = '.$xsum.' - '.$cXelement.';');
						tpt_dump('$diff_part = ceil($diff/count($clptxt)); '.$diff_part.' = ceil('.$diff.'/'.count($clptxt).');');
					}
					//if(!empty($_GET['debug_php'])) {
					//	tpt_dump('$diff = ceil(($xsum - $cXelement)/count($clptxt)); ' . $diff . ' = ceil((' . $xsum . ' - ' . $cXelement . ')/' .count( $clptxt ). ');');
					//}
				}
				$xsum -= ($diff_part*count($clptxt));
				//$xsum -= $diff;
				//$xsum = $cXelement;
				if(!empty($_GET['debug_php'])) {
					tpt_dump('$xsum -= ($diff_part*count($clptxt)); '.$xsum.' = '.($xsum+($diff_part*count($clptxt))).' - ('.$diff_part.'*'.count($clptxt).');');
					//tpt_dump('$xsum -= $diff; '.$xsum.' = '.($xsum+$diff).' - '.$diff.';');
				}
				//tpt_dump($diff);
				//tpt_dump($clp);
				$msggrvt = '-gravity Center';
				if(isset($clips[0]) && isset($clips[1])) {

				} else if(isset($clips[0])) {
					$msggrvt = '-gravity East';
				} else if(isset($clips[1])) {
					$msggrvt = '-gravity West';
				}
				foreach($clptxt as $clptxtid=>$ct) {
					$fld = $cpf_module->moduleData['id'][$clptxtid];
					if(!empty($_GET['debug_php'])) {
						tpt_dump('TEXT/CLIPART: ($clptxtid: '.$clptxtid.', $pname: '.$fld['pname'].')');
					}
					if($fld['text']) {
						$text = $msg;
						if(empty($text)) {
							$text = ' ';
						}
						$text = ''.escapeshellarg(str_replace('\\', '\\\\', $text)).'';
						if(!empty($_GET['debug_php'])) {
							tpt_dump('DIMS BEFORE: ($ct[\'x\']='.$ct['x'].', $ct[\'y\']='.$ct['y'].')');
						}
						$msgx = $ct['x'] - $diff_part;
						$msgy = $ct['y'] - ceil($diff_part/$ct['proportion']);
						if(!empty($_GET['debug_php'])) {
							tpt_dump('$msgx = $ct[\'x\'] - $diff_part; ' . $msgx.' = '.$ct['x'].' - '.$diff_part.';');
							tpt_dump('$msgy = $ct[\'y\'] - ceil($diff_part/$ct[\'proportion\']); ' . $msgy.' = '.$ct['y'].' - ceil('.$diff_part.'/'.$ct['proportion'].');');
							tpt_dump('BEST FIT METRICS BEFORE: ($tid='.$tid.', $msg='.$msg.', $msgx='.$msgx.', $msgy='.$msgy.')');
							tpt_dump($metrics[$clptxtid]['x'].'/'.$metrics[$clptxtid]['y'].'='.$metrics[$clptxtid]['proportion']);
						}
						$metrics[$clptxtid] = self::get_text_metrics($vars, $tid, $msg, $msgx, $msgy, $stroke, $strokewidth, $bg, $font, $kern, $s);
						$msgx = $metrics[$clptxtid]['x'];
						$msgy = $metrics[$clptxtid]['y'];
						$pointsize = $metrics[$clptxtid]['ps'];
						if(!empty($_GET['debug_php'])) {
							tpt_dump('$msgx = $metrics[$clptxtid][\'x\']; ' . $msgx.' = '.$metrics[$clptxtid]['x'].';');
							tpt_dump('$msgy = $metrics[$clptxtid][\'y\']; ' . $msgy.' = '.$metrics[$clptxtid]['y'].';');
							tpt_dump('$pointsize = $metrics[$clptxtid][\'ps\']; ' . $msgy.' = '.$metrics[$clptxtid]['ps'].';');
							tpt_dump('BEST FIT METRICS AFTER: ($tid='.$tid.', $msg='.$msg.', $msgx='.$msgx.', $msgy='.$msgy.')');
							//tpt_dump($metrics[$clptxtid]);
							tpt_dump($metrics[$clptxtid]['x'].'/'.$metrics[$clptxtid]['y'].'='.$metrics[$clptxtid]['proportion']);
						}

						$parts[$clptxtid] = <<< EOT
			\( \
				-size {$msgx}x{$msgy} \
				-gravity Center \
				-pointsize $pointsize \
				-stroke '#FFFFFF' \
				$strokewidth \
				$bg \
				\
				-fill none \
				$font \
				$kern \
				label:$text \
			\) \
			$msggrvt \
			-compose Over \
			-composite \
EOT;
					} else {
						$clpgrvt = '-gravity West';
						if(!empty($fld['orientation'])) {
							$clpgrvt = '-gravity East';
						}
						$c = $clipart_module->getClipartPath($vars, $clipart[$clptxtid]);
						$clpx = $ct['x'] - $diff_part;
						$clpy = $ct['y'] - ceil($diff_part/$ct['proportion']);
						$clpy_shave = $clpy-2;
						if(!empty($_GET['debug_php'])) {
							tpt_dump('$clpx = $ct[\'x\'] - $diff_part; ' . $clpx.' = '.$ct['x'].' - '.$diff_part.';');
							tpt_dump('$clpy = $ct[\'y\'] - ceil($diff_part/$ct[\'proportion\']); ' . $clpy.' = '.$ct['y'].' - ceil('.$diff_part.'/'.$ct['proportion'].');');
						}

						$c_c = <<< EOT
						\( \
							-stroke none \
							-strokewidth 0 \
							$bg \
							$color \
							-trim \
							+repage \
							-density 1200 \
							-size x{$clpy_shave} \
							-resize {$clpx}x{$clpy_shave} \
							$c \
						\)
EOT;
						$c_c2 = <<< EOT
					\( \
						-stroke none \
						-strokewidth 0 \
						$bg \
						$color \
						-trim \
						+repage \
						-density 1200 \
						-size x{$clpy_shave} \
						-resize {$clpx}x{$clpy_shave} \
						$c \
					\)
EOT;
						$fsize = <<< EOT
{$bp}{$im_bin} \
-stroke none \
-strokewidth 0 \
$bg \
$color \
-trim \
+repage \
-density 1200 \
-size x{$clpy} \
-resize {$clpx}x{$clpy} \
$c \
-format "%@" \
info:
EOT;
						if (!empty($_GET['debug_php'])) {
							tpt_dump($fsize);
						}
						$csize = self::exec_command($vars, $fsize, '', '', $s, 'clipart_metrics_' . $fld['pname'], 1);
						if (!empty($_GET['debug_php'])) {
							tpt_dump($csize);
						}
						//tpt_dump($csize);
						$cmetrics[$clptxtid] = preg_split('#\+|-#', $csize);
						//tpt_dump($cmetrics[$clpid]);
						$cmetrics[$clptxtid] = array_shift($cmetrics[$clptxtid]);
						//tpt_dump($cmetrics[$clpid]);
						$cmetrics[$clptxtid] = explode('x', $cmetrics[$clptxtid]);
						//tpt_dump($cmetrics[$clpid]);
						if (empty($cmetrics[$clptxtid][0]) || empty($cmetrics[$clptxtid][1])) {
							//tpt_dump('asd');
							//tpt_dump('asd', true);
							$c_c = <<< EOT
						\( \
							-stroke '#FFFFFF' \
							-strokewidth 1 \
							$bg \
							-trim \
							+repage \
							-density 1200 \
							-size x{$clpy_shave} \
							-resize {$clpx}x{$clpy_shave} \
							$c \
						\)
EOT;
							$c_c2 = <<< EOT
					\( \
						-stroke '#FFFFFF' \
						-strokewidth 1 \
						$bg \
						-trim \
						+repage \
						-density 1200 \
						-size x{$clpy_shave} \
						-resize {$clpx}x{$clpy_shave} \
						$c \
					\)
EOT;

							$fsize = <<< EOT
{$bp}{$im_bin} \
-stroke '#FFFFFF' \
-strokewidth 1 \
$bg \
-trim \
+repage \
-density 1200 \
-size x{$clpy} \
-resize {$clpx}x{$clpy} \
$c \
-format "%@" \
info:
EOT;
							if (!empty($_GET['debug_php'])) {
								tpt_dump($fsize);
							}
							$csize = self::exec_command($vars, $fsize, '', '', $s, 'clipart_metrics2_' . $fld['pname'], 1);
							if (!empty($_GET['debug_php'])) {
								tpt_dump($csize);
							}
							//tpt_dump($csize);
							$cmetrics[$clptxtid] = preg_split('#\+|-#', $csize);
							//tpt_dump($cmetrics[$clpid]);
							$cmetrics[$clptxtid] = array_shift($cmetrics[$clptxtid]);
							$cmetrics[$clptxtid] = explode('x', $cmetrics[$clptxtid]);
						}
						$clpx = $cmetrics[$clptxtid][0];
						$c_c = <<< EOT
						\( \
							-stroke '#FFFFFF' \
							-strokewidth 1 \
							$bg \
							-trim \
							+repage \
							-density 1200 \
							-size x{$clpy_shave} \
							-resize {$clpx}x{$clpy_shave} \
							$c \
						\)
EOT;
						$c_c2 = <<< EOT
					\( \
						-stroke '#FFFFFF' \
						-strokewidth 1 \
						$bg \
						-trim \
						+repage \
						-density 1200 \
						-size x{$clpy_shave} \
						-resize {$clpx}x{$clpy_shave} \
						$c \
					\)
EOT;

						$parts[$clptxtid] = <<< EOT
			\( \
				-respect-parenthesis \
				-background transparent \
				-page {$clpx}x{$clpy} \
				\
				\( \
					-size {$clpx}x{$clpy} \
					xc:transparent \
					\( \
$c_c \
						\( \
							-size {$clpx}x{$clpy_shave} \
							xc:'#FFFFFF' \
						\) \
						-gravity Center \
						-compose SrcIn \
						-composite \
					\) \
					-gravity Center \
					-compose Over \
					-composite \
				\) \
				\( -clone 0 -repage -1-1 \) \( -clone 0 -repage -1+0 \) \( -clone 0 -repage -1+1 \) \
				\( -clone 0 -repage +0-1 \) \( -clone 0 -repage +0+0 \) \( -clone 0 -repage +0+1 \) \
				\( -clone 0 -repage +1-1 \) \( -clone 0 -repage +1+0 \) \( -clone 0 -repage +1+1 \) \
				-compose Over \
				-flatten \
				\( \
$c_c2 \
					\( \
						-size {$clpx}x{$clpy_shave} \
						xc:white \
					\) \
					-gravity Center \
					-compose SrcIn \
					-composite \
				\) \
				-gravity Center \
				-compose DstOut \
				-composite \
			\) \
			$clpgrvt \
			-compose Over \
			-composite \
EOT;
					}
				}
			}
			//tpt_dump($clptxt);
			$parts = implode("\n", $parts);

			$pgravity = '-gravity Center';
			if (!empty($layout['text_topbottom']) && (count($messages)>1)) {
				if(!empty($msg_module->moduleData['pname'][$msgdata['pname']]['line2'])) {
					$pgravity = '-gravity South';
				} else {
					$pgravity = '-gravity North';
				}
			}

			$partsjoin[] = <<< EOT
		\( \
			-size {$xsum}x{$cYelement} \
			xc:transparent \
$parts
		\) \
		$pgravity \
		-compose Over \
		-composite \
EOT;
		}
		//tpt_dump('', true);

		$partsjoin = implode("\n", $partsjoin);
		$command = <<< EOT
{$bp}{$im_bin} \
-respect-parenthesis \
\( \
	\( \
		\( \
			-size {$cXelement}x{$cY} \
			xc:transparent \
		\) \
$partsjoin \
	\) \
\) \

EOT;
		//tpt_dump($command, true);

		//tpt_dump($command, true);
		return $command;
	}
	static function c_led_message_old(&$vars, &$layer, &$out='', &$steps=array()) {
		$color_module = getModule($vars, 'BandColor');
		$msg_module = getModule($vars, 'BandMessage');
		$cpf_module = getModule($vars, 'CustomProductField');
		$fonts_module = getModule($vars, 'BandFont');
		$layouts_module = getModule($vars, 'BandLayout');
		$fonts = $fonts_module->moduleData['id'];
		$clipart_module = getModule($vars, 'BandClipart');


		//$isfront (empty($message['back']) && empty($message['line2'])) {
		$bp = BIN_PATH;
		if(defined('ALT_BIN_PATH')) {
			$bp = ALT_BIN_PATH;
		}
		$im_bin = IMAGEMAGICK_BIN;

		//tpt_dump($layer);
		$layout = (!empty($layer['band_layout'])?intval($layer['band_layout'], 10):(!empty($layer['layout'])?intval($layer['layout'], 10):1));
		$layout = $layouts_module->moduleData['id'][$layout];

		$targets = explode(',', $layer['target']);
		$targets = array_combine($targets, $targets);
		$targets = array_intersect_key($cpf_module->moduleData['id'], $targets);

		$messages = array();
		$m = array();
		$clipart = array();
		foreach($targets as $tid=>$target) {
			if(isset($layer[$target['pname']])) {
				if(!empty($target['text'])) {
					$messages[$tid] = $layer[$target['pname']];
				} else if(!empty($target['clipart'])) {
					$clipart[$tid] = $layer[$target['pname']];
				}
			}
		}

		$ncmessages = array();
		$ncparams = explode('|', $layer['nullcheck_preview_params_ids']);
		foreach($ncparams as $ncparam) {
			$ncp = explode(':', $ncparam);
			if(!empty($cpf_module->moduleData['id'][$ncp[0]]) && !empty($cpf_module->moduleData['id'][$ncp[0]]['text'])) {
				$ncmessages[$ncp[0]] = $cpf_module->moduleData['id'][$ncp[0]];
			}
		}

		//tpt_dump($layer['cX']);
		//tpt_dump($layer['cPR']);
		//tpt_dump($layer['cPL']);
		if(!empty($ncmessages)) {
			$ncmsg = reset($ncmessages);
			//tpt_dump($layer[$ncmsg['pname']]);
			//tpt_dump($layout['text_frontback']);
			//tpt_dump($messages, true);
			if (!empty($layout['text_frontback']) && !empty($ncmessages) && !empty($layer[$ncmsg['pname']])) {
				$imsg = reset($messages);
				$imsg = key($messages);
				$cXex = floor($layer['cX'] / 2);
				$layer['cX'] -= ($cXex+5);
				//tpt_dump($msg_module->moduleData['pname'][$cpf_module->moduleData['id'][$imsg]['pname']]);
				if (!empty($cpf_module->moduleData['id'][$imsg]['pname']) && !empty($msg_module->moduleData['pname'][$cpf_module->moduleData['id'][$imsg]['pname']]['back'])) {
					$layer['cPL'] += ($cXex+5);
				} else {
					$layer['cPR'] += ($cXex+5);
				}
			}
		}
		//tpt_dump($layer['cX']);
		//tpt_dump($layer['cPR']);
		//tpt_dump($layer['cPL']);


		$cX = (!empty($layer['cX'])?intval($layer['cX'], 10):1);
		if(!empty($_GET['debug_php'])) {
			tpt_dump('$cX = (!empty($layer[\'cX\'])?intval($layer[\'cX\'], 10):1); '.$cX.' = (!empty('.$layer['cX'].')?intval('.$layer['cX'].', 10):1);');
		}
		$cY = (!empty($layer['cY'])?intval($layer['cY'], 10):1);
		if(!empty($_GET['debug_php'])) {
			tpt_dump('$cY = (!empty($layer[\'cY\'])?intval($layer[\'cY\'], 10):1); '.$cY.' = (!empty('.$layer['cY'].')?intval('.$layer['cY'].', 10):1);');
		}

		$cPL = (!empty($layer['cPL'])?intval($layer['cPL'], 10):0);
		if(!empty($_GET['debug_php'])) {
			tpt_dump('$cPL = (!empty($layer[\'cPL\'])?intval($layer[\'cPL\'], 10):0) '.$cPL.' = (!empty('.$layer['cPL'].')?intval('.$layer['cPL'].', 10):1);');
		}
		$cPR = (!empty($layer['cPR'])?intval($layer['cPR'], 10):0);
		if(!empty($_GET['debug_php'])) {
			tpt_dump('$cPR = (!empty($layer[\'cPR\'])?intval($layer[\'cPR\'], 10):0) '.$cPR.' = (!empty('.$layer['cPR'].')?intval('.$layer['cPR'].', 10):1);');
		}
		$cPT = (!empty($layer['cPT'])?intval($layer['cPT'], 10):0);
		if(!empty($_GET['debug_php'])) {
			tpt_dump('$cPT = (!empty($layer[\'cPT\'])?intval($layer[\'cPT\'], 10):0) '.$cPT.' = (!empty('.$layer['cPT'].')?intval('.$layer['cPT'].', 10):1);');
		}
		$cPB = (!empty($layer['cPB'])?intval($layer['cPB'], 10):0);
		if(!empty($_GET['debug_php'])) {
			tpt_dump('$cPB = (!empty($layer[\'cPB\'])?intval($layer[\'cPB\'], 10):0) '.$cPB.' = (!empty('.$layer['cPB'].')?intval('.$layer['cPB'].', 10):1);');
		}

		/*
		$ncparams = explode(',', $ncparams[1]);
		$ncparams = array_combine($ncparams, $ncparams);
		$ncparams = array_intersect_key($cpf_module->moduleData['id'], $ncparams);
		*/
		//$layer[$nctrgt['pname']] =

		//tpt_dump($cX, true);


		$font = FONTS_PATH.DIRECTORY_SEPARATOR.(!empty($layer['font'])?$fonts[$layer['font']]['file']:DEFAULT_FONT_NAME);
		$font = <<< EOT
-font '$font'
EOT;


		//$color = '-fill '.((!empty($layer['color']) && ($layer['color'] != 'transparent') && ($layer['color'] != 'none'))?''.escapeshellarg($layer['color']):'none').'';
		//if (!empty($layer['message_color']) && strstr($layer['message_color'], ':')) {
		$color = <<< EOT
-fill none
EOT;
		$strokecolor = escapeshellarg('#FFFFFF');
		$stroke = '-stroke '.$strokecolor;
		if (!empty($layer['color'])) {
			$cprops = $color_module->getColorProps($vars, $layer['color']);
			//tpt_dump($cprops);
			$strokecolor = (!empty($cprops['colordata']['led_hex']) ? '#' . $cprops['colordata']['led_hex'] : 'none');
			$strokecolor = escapeshellarg($strokecolor);
			$stroke = <<< EOT
-stroke $strokecolor
EOT;
		}
		$bg = <<< EOT
-background 'transparent'
EOT;



		$strokewidth = '';

		$inner_shadow = '';
		$inner_glow = '';
		$drop_shadow = '';
		$outer_glow = '';


		$kern = '';
		if(!empty($layer['kern'])) {
			$kern = escapeshellarg($layer['kern']);
			$kern = <<< EOT
-kerning $kern
EOT;
		}


		//$gravity = '-gravity center';
		$gravity = '';
		if(!empty($layer['gravity'])) {
			$gravity = escapeshellarg($layer['gravity']);
			$gravity = <<< EOT
-gravity $gravity
EOT;
		}

		if(!empty($layer['stroke'])) {
			/*
			$stroke = escapeshellarg($layer['stroke_color']);
			$stroke = <<< EOT
-stroke $stroke
EOT;
			*/


			if(!empty($layer['stroke_width'])) {
				$c_strokewidth = intval($layer['stroke_width'], 10)+2;
				$c_strokewidth = <<< EOT
-strokewidth $c_strokewidth
EOT;

				$strokewidth = intval($layer['stroke_width'], 10);
				$strokewidth = <<< EOT
-strokewidth $strokewidth
EOT;
			}
		}

		$s = array();
		$metrics = array();
		$cYm = $cY;
		if(!empty($_GET['debug_php'])) {
			tpt_dump('$cYm = $cY; '.$cYm.' = '.$cX.';');
		}
		if (!empty($layout['text_topbottom']) && (count($messages)>1)) {
			$cYm = floor($layer['cY']/count($messages));
			if(!empty($_GET['debug_php'])) {
				tpt_dump('$cYm = floor($layer[\'cY\']/count($messages)); '.$cYm.' = floor('.$layer['cY'].'/'.count($messages).');');
			}
		}
		$dcYm = $cYm*2;
		if(!empty($_GET['debug_php'])) {
			tpt_dump('$dcYm = $cYm*2; '.$dcYm.' = '.$cYm.'*2;');
		}
		$resize = '';
		if(!empty($layer['snug_fit_label'])) {
			$resize = <<< EOT
-resize {$cX}x{$cYm}
EOT;
		}

		$pointsize = 0;
		$clp_y = min($cX, $cYm);
		if(!empty($_GET['debug_php'])) {
			tpt_dump('$clp_y = min($cX, $cYm); '.$clp_y.' = min('.$cX.', '.$cYm.');');
		}
		//tpt_dump($clp_y);
		$cXmm = $cX;
		if(!empty($_GET['debug_php'])) {
			tpt_dump('$cXmm = $cX; '.$cXmm.' = '.$cX.';');
		}
		foreach($messages as $tid=>$msg) {
			$msgdata = $cpf_module->moduleData['id'][$tid];


			//tpt_dump($clpnames, true);
			//tpt_dump($clipart, true);

			$text = $msg;
			if(empty($text)) {
				$text = 'W';
			}
			//tpt_dump($text);
			$text = ''.escapeshellarg(str_replace('\\', '\\\\', $text)).'';

			//tpt_dump($clp_y);
			foreach ($clipart as $ctid => $clp) {
				$clpdata = $cpf_module->moduleData['id'][$ctid];
				$cmsg = $cpf_module->moduleData['id'][$clpdata['clipart_text_id']];

				$c = $clipart_module->getClipartPath($vars, $layer[$clpdata['pname']]);

				$fsize = <<< EOT
{$bp}{$im_bin} \
$c \
-format "%@" \
info:
EOT;
				if(!empty($_GET['debug_php'])) {
					tpt_dump($fsize);
				}
				$fsize = self::exec_command($vars, $fsize, '', '', $s, 'size_'.$clpdata['pname'], 1);
				if(!empty($_GET['debug_php'])) {
					tpt_dump($fsize);
				}
				$metrics[$clpdata['pname']] = preg_split('#\+|-#', $fsize);
				$metrics[$clpdata['pname']] = array_shift($metrics[$clpdata['pname']]);
				$metrics[$clpdata['pname']] = explode('x', $metrics[$clpdata['pname']]);
				$metrics[$clpdata['pname']] = array('x'=>$metrics[$clpdata['pname']][0], 'y'=>$metrics[$clpdata['pname']][1], 'proportion'=>$metrics[$clpdata['pname']][0]/$metrics[$clpdata['pname']][1]);
				if(!empty($_GET['debug_php'])) {
					tpt_dump($metrics[$clpdata['pname']]);
				}

				if($tid == $cmsg['id']) {
					if(isset($layer[$clpdata['pname']])) {
						if(!empty($layout['clipart_leftright'])) {
							if(!empty($_GET['debug_php'])) {
								tpt_dump('$cXmm; '.$cXmm);
							}
							$cXmm -= ($cYm+2);
							if(!empty($_GET['debug_php'])) {
								tpt_dump('$cXmm -= ($cYm+2); '.$cXmm.' -= ('.$cYm.'+2);');
							}
							/*
							if(!empty($_GET['debug_php'])) {
								tpt_dump('$cXmm; '.$cXmm);
							}
							$cXmm -= ceil($cYm*$metrics[$clpdata['pname']]['proportion'])+2;
							if(!empty($_GET['debug_php'])) {
								tpt_dump('$cXmm -= ceil($cYm*$metrics[$clpdata[\'pname\']][\'proportion\'])+2; '.$cXmm.' -= ceil('.$cYm.'*'.$metrics[$clpdata['pname']]['proportion'].')+2;');
							}
							*/
						}
					}
				}
			}



			$fsize = <<< EOT
{$bp}{$im_bin} \
-size {$cXmm}x{$cYm} \
$stroke \
$strokewidth \
$bg \
\
-fill 'white' \
$font \
$kern \
label:$text \
-format "%[label:pointsize]|%@" \
info:
EOT;
			if(!empty($_GET['debug_php'])) {
				tpt_dump($fsize);
			}
			$fsize = self::exec_command($vars, $fsize, '', '', $s, $msgdata['pname'], 1);
			if(!empty($_GET['debug_php'])) {
				tpt_dump($fsize);
			}
			$metrics[$msgdata['pname']] = explode('|', $fsize);
			//tpt_dump($metrics, true);
			$metrics[$msgdata['pname']][1] = preg_split('#\+|-#', $metrics[$msgdata['pname']][1]);
			$metrics[$msgdata['pname']][1] = array_shift($metrics[$msgdata['pname']][1]);
			$metrics[$msgdata['pname']][1] = explode('x', $metrics[$msgdata['pname']][1]);
			$metrics[$msgdata['pname']] = array('x'=>$metrics[$msgdata['pname']][1][0], 'y'=>$metrics[$msgdata['pname']][1][1], 'ps'=>$metrics[$msgdata['pname']][0]);
			if(!empty($_GET['debug_php'])) {
				tpt_dump($metrics[$msgdata['pname']]);
			}


			$dx = $cXmm - $metrics[$msgdata['pname']]['x'];
			if(!empty($_GET['debug_php'])) {
				tpt_dump('$dx = $cXmm - $metrics[$msgdata[\'pname\']][\'x\']; '.$dx.' = '.$cXmm.' - '.$metrics[$msgdata['pname']]['x'].');');
			}
			$dy = $cYm - $metrics[$msgdata['pname']]['y'];
			if(!empty($_GET['debug_php'])) {
				tpt_dump('$dy = $cYm - $metrics[$msgdata[\'pname\']][\'y\']; '.$dy.' = '.$cYm.' - '.$metrics[$msgdata['pname']]['y'].');');
			}

			$i=-9;
			$fsize2 = array();
			do {
				$ps = $metrics[$msgdata['pname']]['ps']+$i;
				$fsize2[] = <<< EOT
-size {$cX}x{$dcYm} \
-pointsize {$ps} \
$stroke \
$strokewidth \
$bg \
\
-fill 'white' \
$font \
$kern \
label:$text \
-format "\\n%@" \
EOT;
				$i++;
			} while(($i < 10));
			$fsize2 = implode("\n", $fsize2);
			$fsize2 = <<< EOT
{$bp}{$im_bin} \
$fsize2
info:
EOT;

			if(!empty($_GET['debug_php'])) {
				tpt_dump($fsize2);
			}
			$fsize2 = self::exec_command($vars, $fsize2, '', '', $s, $msgdata['pname'], 1);
			if(!empty($_GET['debug_php'])) {
				tpt_dump($fsize2);
			}
			$fsize2 = preg_split('#\R#', trim($fsize2));
			$fskeys = array(-9,-8,-7,-6,-5,-4,-3,-2,-1,0,1,2,3,4,5,6,7,8,9);
			$fsize2 = array_combine($fskeys, $fsize2);
			$ps = $metrics[$msgdata['pname']]['ps'];
			//tpt_dump($fsize2, true);
			foreach($fsize2 as $i=>$fs) {
				$ps2 = $ps+$i;
				$fs2 = preg_split('#\+|-#', $fs);
				$fs2 = array_shift($fs2);
				$fs2 = explode('x', $fs2);
				$dx = $cXmm - $fs2[0];
				if(!empty($_GET['debug_php'])) {
					tpt_dump('$dx = $cXmm - $fs2[0]; '.$dx.' = '.$cXmm.' - '.$fs2[0].');');
				}
				$dy = $cYm - $fs2[1];
				if(!empty($_GET['debug_php'])) {
					tpt_dump('$dy = $cYm - $fs2[1]; '.$dy.' = '.$cYm.' - '.$fs2[1].');');
				}
				//tpt_dump($clp_y);
				if (($dx >= 0) && ($dy >= 0)) {
					$metrics[$msgdata['pname']] = array('x' => $fs2[0], 'y' => $fs2[1], 'ps' => $ps2);
				}


				if(($dx <= 0) || ($dy <= 0)) {
					break;
				}
				if (!empty($_GET['debug_php'])) {
					tpt_dump($metrics[$msgdata['pname']]);
				}
			}
			//tpt_dump($metrics, true);
			//tpt_dump($fsize, true);

			//tpt_dump($clp_y);
			if(empty($clp_y) || (!empty($metrics[$msgdata['pname']]['y']) && ($clp_y > $metrics[$msgdata['pname']]['y']))) {
				$clp_y = $metrics[$msgdata['pname']]['y'];
				if(!empty($_GET['debug_php'])) {
					tpt_dump('$clp_y = $metrics[$msgdata[\'pname\']][\'y\']; '.$clp_y.' = '.$metrics[$msgdata['pname']]['y'].';');
				}
			}
			//tpt_dump($metrics[$msgdata['pname']]['y']);
			//tpt_dump($clp_y);

			if(empty($pointsize) || ($pointsize>$metrics[$msgdata['pname']]['ps'])) {
				$pointsize = $metrics[$msgdata['pname']]['ps'];
			}
		}
		//tpt_dump($metrics, true);

		$msgs = array();
		foreach($messages as $tid=>$msg) {
			$cXm = $cX;
			if(!empty($_GET['debug_php'])) {
				tpt_dump('$cXm = $cX; '.$cXm.' = '.$cX.';');
			}
			$msgdata = $cpf_module->moduleData['id'][$tid];
			//$text = implode($layout['text_separator'], $messages);
			$text = $msg;
			if(empty($text)) {
				$text = ' ';
			}

			//tpt_dump($text);
			$text = ''.escapeshellarg(str_replace('\\', '\\\\', $text)).'';

			$pgravity = '-gravity Center';
			if (!empty($layout['text_topbottom']) && (count($messages)>1)) {
				if(!empty($msg_module->moduleData['pname'][$msgdata['pname']]['line2'])) {
					$pgravity = '-gravity South';
				} else {
					$pgravity = '-gravity North';
				}
			}


			//tpt_dump($clipart);
			//tpt_dump($layout);
			$clp_x = max(min($cX, $cYm), floor(($cX - $metrics[$msgdata['pname']]['x'])/max(1, count($clipart))));
			if(!empty($_GET['debug_php'])) {
				tpt_dump('$clp_x = max(min($cX, $cYm), floor(($cX - $metrics[$msgdata[\'pname\']][\'x\'])/max(1, count($clipart)))); '.$clp_x.' = max(min('.$cX.', '.$cYm.'), floor(('.$cX.' - '.$metrics[$msgdata['pname']]['x'].')/max(1, '.count($clipart).')));');
			}
			$clpnames = array();
			$clpgrvt = array();
			$clpoffsigns = array();
			$msggrvt = '-gravity Center';
			if (!empty($layout['text_topbottom']) && (count($messages)>1)) {
				foreach ($clipart as $ctid => $clp) {
					$clpdata = $cpf_module->moduleData['id'][$ctid];
					$cmsg = $cpf_module->moduleData['id'][$clpdata['clipart_text_id']];

					if($tid == $cmsg['id']) {
						//tpt_dump('asd');
						//tpt_dump($clpdata['pname'], true);
						if(isset($layer[$clpdata['pname']])) {
							//tpt_dump('asd');
							//tpt_dump($layout, true);
							//tpt_dump($clpdata['pname'], true);
							$clpg = '-gravity East';
							$clpoffsign = '+';
							$msggrvt = '-gravity West';
							if(!empty($layout['clipart_leftright'])) {
								$cXm -= ($clp_x);
								if(!empty($_GET['debug_php'])) {
									tpt_dump('$cXm -= ($clp_x); '.$cXm.' -= ('.$clp_x.');');
								}
								//tpt_dump($layer[$clpdata['pname']], true);
								if(empty($clpdata['orientation'])) {
									$clpg = '-gravity West';
									$clpoffsign = '-';
									$msggrvt = '-gravity East';
								}
							}

							$clpoffsigns[$ctid][$layer[$clpdata['pname']]] = $clpoffsign;
							$clpgrvt[$ctid][$layer[$clpdata['pname']]] = $clpg;
							$clpnames[$ctid][$layer[$clpdata['pname']]] = $clipart_module->getClipartPath($vars, $layer[$clpdata['pname']]);
						}
					}


				}
			} else {
				foreach ($clipart as $ctid => $clp) {
					$clpdata = $cpf_module->moduleData['id'][$ctid];
					$cmsg = $cpf_module->moduleData['id'][$clpdata['clipart_text_id']];

					if($tid == $cmsg['id']) {
						//tpt_dump('asd');
						//tpt_dump($clpdata['pname'], true);
						if(isset($layer[$clpdata['pname']])) {
							//tpt_dump('asd');
							//tpt_dump($layout, true);
							//tpt_dump($clpdata['pname'], true);
							$clpg = '-gravity East';
							$clpoffsign = '+';
							$msggrvt = '-gravity West';
							if(!empty($layout['clipart_leftright'])) {
								$cXm -= ($clp_x);
								if(!empty($_GET['debug_php'])) {
									tpt_dump('$cXm -= ($clp_x); '.$cXm.' -= ('.$clp_x.');');
								}
								//tpt_dump($layer[$clpdata['pname']], true);
								if(empty($clpdata['orientation'])) {
									$clpg = '-gravity West';
									$clpoffsign = '-';
									$msggrvt = '-gravity East';
								}
							}

							$clpoffsigns[$ctid][$layer[$clpdata['pname']]] = $clpoffsign;
							$clpgrvt[$ctid][$layer[$clpdata['pname']]] = $clpg;
							$clpnames[$ctid][$layer[$clpdata['pname']]] = $clipart_module->getClipartPath($vars, $layer[$clpdata['pname']]);
						}
					}


				}
			}


			if(!empty($layout['clipart_leftright']) && (count($clpnames) > 1)) {
				$msggrvt = '-gravity Center';
			}

			//tpt_dump($clpnames);


			/*
			$clp_xx = $clp_x-5;
			$clp_yy = $clp_y-5;
			-size x{$clp_yy} \
			-resize {$clp_xx}x{$clp_yy} \

+clone \
-compose Over -composite \
+clone \
-compose Over -composite \
+clone \
-compose Over -composite \
+clone \
-compose Over -composite


\( \
-background 'transparent' \
-stroke none \
-strokewidth 0 \
$bg \
$color \
-trim \
+repage \
-density 1200 \
-size x{$clp_yy} \
-resize {$clp_xx}x{$clp_yy} \
$c \
\) \
-compose Over \
-composite \
			*/
			$clp_xx = $clp_x-5;
			if(!empty($_GET['debug_php'])) {
				tpt_dump('$clp_xx = $clp_x-5; '.$clp_xx.' = '.$clp_x.'-5;');
			}
			$clp_yy = $clp_y-5;
			if(!empty($_GET['debug_php'])) {
				tpt_dump('$clp_yy = $clp_y-5; '.$clp_yy.' = '.$clp_y.'-5;');
			}
			$clp = array();
			$cmetrics = array();
			if(!empty($clpnames)) {
				if(!empty($layout['clipart_leftright'])) {
					foreach($clpnames as $tid=>$clps) {
						foreach ($clps as $clpid => $c) {
							//$c'[{$clp_sq}x{$clp_sq}]' \
							$clpg = $clpgrvt[$tid][$clpid];
							$c_c = <<< EOT
						\( \
							-stroke none \
							-strokewidth 0 \
							$bg \
							$color \
							-trim \
							+repage \
							-density 1200 \
							-size x{$clp_y} \
							-resize {$clp_xx}x{$clp_yy} \
							$c \
						\)
EOT;
							$c_c2 = <<< EOT
					\( \
						-stroke none \
						-strokewidth 0 \
						$bg \
						$color \
						-trim \
						+repage \
						-density 1200 \
						-size x{$clp_y} \
						-resize {$clp_xx}x{$clp_yy} \
						$c \
					\)
EOT;
							$fsize = <<< EOT
{$bp}{$im_bin} \
-stroke none \
-strokewidth 0 \
$bg \
$color \
-trim \
+repage \
-density 1200 \
-size x{$clp_y} \
-resize {$clp_xx}x{$clp_yy} \
$c \
-format "%@" \
info:
EOT;
							if (!empty($_GET['debug_php'])) {
								tpt_dump($fsize);
							}
							$csize = self::exec_command($vars, $fsize, '', '', $s, 'clipart_metrics_' . $clpid, 1);
							//tpt_dump($csize);
							$cmetrics[$clpid] = preg_split('#\+|-#', $csize);
							//tpt_dump($cmetrics[$clpid]);
							$cmetrics[$clpid] = array_shift($cmetrics[$clpid]);
							//tpt_dump($cmetrics[$clpid]);
							$cmetrics[$clpid] = explode('x', $cmetrics[$clpid]);
							//tpt_dump($cmetrics[$clpid]);
							if (empty($cmetrics[$clpid][0]) || empty($cmetrics[$clpid][1])) {
								//tpt_dump('asd');
								//tpt_dump('asd', true);
								$c_c = <<< EOT
						\( \
							-stroke '#FFFFFF' \
							-strokewidth 1 \
							$bg \
							-trim \
							+repage \
							-density 1200 \
							-size x{$clp_y} \
							-resize {$clp_xx}x{$clp_yy} \
							$c \
						\)
EOT;
								$c_c2 = <<< EOT
					\( \
						-stroke '#FFFFFF' \
						-strokewidth 1 \
						$bg \
						-trim \
						+repage \
						-density 1200 \
						-size x{$clp_y} \
						-resize {$clp_xx}x{$clp_yy} \
						$c \
					\)
EOT;

								$fsize = <<< EOT
{$bp}{$im_bin} \
-stroke '#FFFFFF' \
-strokewidth 1 \
$bg \
-trim \
+repage \
-density 1200 \
-size x{$clp_y} \
-resize {$clp_xx}x{$clp_yy} \
$c \
-format "%@" \
info:
EOT;
								if (!empty($_GET['debug_php'])) {
									tpt_dump($fsize);
								}
								$csize = self::exec_command($vars, $fsize, '', '', $s, 'clipart_metrics2_' . $clpid, 1);
								//tpt_dump($csize);
								$cmetrics[$clpid] = preg_split('#\+|-#', $csize);
								//tpt_dump($cmetrics[$clpid]);
								$cmetrics[$clpid] = array_shift($cmetrics[$clpid]);
								$cmetrics[$clpid] = explode('x', $cmetrics[$clpid]);
							}
							/*
														$clp[] = <<< EOT
							\( \
							-size {$clp_x}x{$clp_y} \
							xc:'#FFFFFF' \
							\( \
							-size {$clp_x}x{$clp_y} \
							xc:transparent \
							$c_c \
							-gravity center \
							-geometry -1-1 \
							-compose Over -composite \
							$c_c \
							-gravity center \
							-geometry -1-0 \
							-compose Over -composite \
							$c_c \
							-gravity center \
							-geometry -1+1 \
							-compose Over -composite \
							$c_c \
							-gravity center \
							-geometry +0-1 \
							-compose Over -composite \
							$c_c \
							-gravity center \
							-geometry +0+0 \
							-compose Over -composite \
							$c_c \
							-gravity center \
							-geometry +0+1 \
							-compose Over -composite \
							$c_c \
							-gravity center \
							-geometry +1-1 \
							-compose Over -composite \
							$c_c \
							-gravity center \
							-geometry +1+0 \
							-compose Over -composite \
							$c_c \
							-gravity center \
							-geometry +1+1 \
							-compose Over -composite \
							$c_c \
							-gravity center \
							-geometry +0+0 \
							-compose DstOut -composite \
							\) \
							-compose CopyOpacity -composite \
							\) \
							-trim \
							-gravity Center \
							-resize x{$clp_y} \
							$clpg \
							-compose Over -composite
							EOT;
							*/
							$clpoffsign = $clpoffsigns[$tid][$clpid];
							if(!empty($_GET['debug_php'])) {
								tpt_dump('$clpoffsign = $clpoffsigns[$tid][$clpid]; '.$clpoffsign);
							}
							$clpoffx = ceil($metrics[$msgdata['pname']]['x']/2);
							if(!empty($_GET['debug_php'])) {
								tpt_dump('$clpoffx = ceil($metrics[$msgdata[\'pname\']][\'x\']/2); '.$clpoffx.' = ceil('.$metrics[$msgdata['pname']]['x'].'/2);');
							}
							//if($clpoffsign == "-") {
							//tpt_dump($cmetrics[$clpid], true);
							$clpoffx += $cmetrics[$clpid][0];
							if(!empty($_GET['debug_php'])) {
								tpt_dump('$clpoffx += $cmetrics[$clpid][0]; '.$clpoffx.' += $cmetrics[$clpid][0];');
							}
							//}
							//$clpoffx = $metrics[$msgdata['pname']]['x'];
							//tpt_dump($cmetrics[$clpid][0]);
							//tpt_dump($cmetrics[$clpid][0], true);
							//tpt_dump($clpg, true);
							$clp[] = <<< EOT
			\( \
				-respect-parenthesis \
				-background transparent \
				-page {$clp_x}x{$clp_y} \
				\
				\( \
					-size {$clp_x}x{$clp_y} \
					xc:transparent \
					\( \
$c_c \
						\( \
						-size {$clp_x}x{$clp_y} \
						xc:'#FFFFFF' \
						\) \
						-compose SrcIn \
						-composite \
					\) \
					-gravity Center \
					-compose Over \
					-composite \
				\) \
				\
				\( \
				-clone 0 \
				-repage -1-1 \
				\) \
				\( \
				-clone 0 \
				-repage -1+0 \
				\) \
				\( \
				-clone 0 \
				-repage -1+1 \
				\) \
				\( \
				-clone 0 \
				-repage +0-1 \
				\) \
				\( \
				-clone 0 \
				-repage +0+0 \
				\) \
				\( \
				-clone 0 \
				-repage +0+1 \
				\) \
				\( \
				-clone 0 \
				-repage +1-1 \
				\) \
				\( \
				-clone 0 \
				-repage +1+0 \
				\) \
				\( \
				-clone 0 \
				-repage +1+1 \
				\) \
				-compose Over \
				-flatten \
				\( \
$c_c2 \
					\( \
					-size {$clp_x}x{$clp_y} \
					xc:white \
					\) \
					-compose SrcIn \
					-composite \
				\) \
				-gravity Center \
				-compose DstOut \
				-composite \
				-geometry {$clpoffsign}{$clpoffx}+0 \
			\) \
			-geometry +0+0 \
			$clpg \
			-compose Over -composite
EOT;
							//tpt_dump($clpgrvt);
							//tpt_dump($clpg);
						}
					}
				}
			}
			//tpt_dump($clpnames);
			//tpt_dump($clpgrvt);
			$clp = implode(' \\'."\n", $clp);
			//tpt_dump($clpnames, true);
			//tpt_dump($clipart, true);
			//tpt_dump($clp, true);
			$cXd = $cX*2;
			if(!empty($_GET['debug_php'])) {
				tpt_dump('$cXd = $cX*2; '.$cXd.' = '.$cX.'*2;');
			}
			$cYmd = $cYm*2;
			if(!empty($_GET['debug_php'])) {
				tpt_dump('$cYmd = $cYm*2; '.$cYmd.' = '.$cYm.'*2;');
			}
			/*
						$msgs[$msgdata['pname']] = <<< EOT
					\( \
						\( \
							-pointsize $pointsize \
							-stroke '#FFFFFF' \
							$strokewidth \
							$bg \
							\
							-fill none \
							$font \
							$kern \
							label:$text \
							-trim \
							-gravity Center \
							-extent {$cXm}x{$cYm} \
							$clp \
						\) \
						-trim \
						+repage \
						$resize \
					\) \
					$pgravity \
					-compose Over -composite
			EOT;
			*/
			$msgs[$msgdata['pname']] = <<< EOT
		\( \
			-size {$cX}x{$dcYm} \
			-gravity North \
			-pointsize $pointsize \
			-stroke '#FFFFFF' \
			$strokewidth \
			$bg \
			\
			-fill none \
			$font \
			$kern \
			label:$text \
			-trim \
			$msggrvt \
			-extent {$cX}x{$cYm} \
$clp \
		\) \
		$pgravity \
		-compose Over -composite
EOT;
		}
		//tpt_dump($metrics, true);

		if(count($msgs) > 1) {
			//$addlabel = '\\( ' . implode(' \\' . "\n" , $msgs) . ' \\)';
			$addlabel =  implode(' \\' . "\n" , $msgs) ;
		} else {
			$addlabel = implode($msgs);
		}


		$command = <<< EOT
{$bp}{$im_bin} \
-respect-parenthesis \
\( \
	\( \
		\( \
			-size {$cX}x{$cY} \
			xc:transparent \
		\) \
$addlabel \
	\) \
	\( \
		-clone 0 \
		\( \
			-size {$cX}x{$cY} \
			xc: \
			+noise Random \
			-channel G \
			-threshold 55% \
			-separate \
			-transparent white \
			-channel All \
			-blur 1x1 \
		\) \
		-compose SrcIn \
		-composite \
		\( \
			-size {$cX}x{$cY} \
			xc:$strokecolor \
		\) \
		-compose SrcIn \
		-composite \
	\) \
	-background transparent \
	-compose Hard_Light \
	-flatten \
\) \

EOT;
		/*
				$command = <<< EOT
		{$bp}{$im_bin} \
		-respect-parenthesis \
		-size {$cX}x{$cY} \
		xc:transparent \
		$addlabel \
		-trim \
		+repage \
		$resize \

		EOT;
		*/

		return $command;
	}
	static function c_led_message2_old(&$vars, &$layer, &$out='', &$steps=array()) {
		$color_module = getModule($vars, 'BandColor');
		$msg_module = getModule($vars, 'BandMessage');
		$cpf_module = getModule($vars, 'CustomProductField');
		$fonts_module = getModule($vars, 'BandFont');
		$layouts_module = getModule($vars, 'BandLayout');
		$fonts = $fonts_module->moduleData['id'];
		$clipart_module = getModule($vars, 'BandClipart');


		//$isfront (empty($message['back']) && empty($message['line2'])) {
		$bp = BIN_PATH;
		if(defined('ALT_BIN_PATH')) {
			$bp = ALT_BIN_PATH;
		}
		$im_bin = IMAGEMAGICK_BIN;

		//tpt_dump($layer);
		$layout = (!empty($layer['band_layout'])?intval($layer['band_layout'], 10):(!empty($layer['layout'])?intval($layer['layout'], 10):1));
		$layout = $layouts_module->moduleData['id'][$layout];

		$targets = explode(',', $layer['target']);
		$targets = array_combine($targets, $targets);
		$targets = array_intersect_key($cpf_module->moduleData['id'], $targets);

		$messages = array();
		$m = array();
		$clipart = array();
		foreach($targets as $tid=>$target) {
			if(isset($layer[$target['pname']])) {
				if(!empty($target['text'])) {
					$messages[$tid] = $layer[$target['pname']];
				} else if(!empty($target['clipart'])) {
					$clipart[$tid] = $layer[$target['pname']];
				}
			}
		}

		$ncmessages = array();
		$ncparams = explode('|', $layer['nullcheck_preview_params_ids']);
		foreach($ncparams as $ncparam) {
			$ncp = explode(':', $ncparam);
			if(!empty($cpf_module->moduleData['id'][$ncp[0]]) && !empty($cpf_module->moduleData['id'][$ncp[0]]['text'])) {
				$ncmessages[$ncp[0]] = $cpf_module->moduleData['id'][$ncp[0]];
			}
		}

		//tpt_dump($layer['cX']);
		//tpt_dump($layer['cPR']);
		//tpt_dump($layer['cPL']);
		if(!empty($ncmessages)) {
			$ncmsg = reset($ncmessages);
			//tpt_dump($layer[$ncmsg['pname']]);
			//tpt_dump($layout['text_frontback']);
			//tpt_dump($messages, true);
			if (!empty($layout['text_frontback']) && !empty($ncmessages) && !empty($layer[$ncmsg['pname']])) {
				$imsg = reset($messages);
				$imsg = key($messages);
                if (isset($layer['cX'])) {
                    $cXex = floor($layer['cX'] / 2);
                } else {
                    $cXex = 0;
                    $layer['cX'] = 0;
                }
				$layer['cX'] -= ($cXex+5);
				//tpt_dump($msg_module->moduleData['pname'][$cpf_module->moduleData['id'][$imsg]['pname']]);
				if (!empty($cpf_module->moduleData['id'][$imsg]['pname']) && !empty($msg_module->moduleData['pname'][$cpf_module->moduleData['id'][$imsg]['pname']]['back'])) {
					$layer['cPL'] += ($cXex+5);
				} else {
					$layer['cPR'] += ($cXex+5);
				}
			}
		}
		//tpt_dump($layer['cX']);
		//tpt_dump($layer['cPR']);
		//tpt_dump($layer['cPL']);


		$cX = (!empty($layer['cX'])?intval($layer['cX'], 10):1);
		if(!empty($_GET['debug_php'])) {
			tpt_dump('$cX = (!empty($layer[\'cX\'])?intval($layer[\'cX\'], 10):1); '.$cX.' = (!empty('.$layer['cX'].')?intval('.$layer['cX'].', 10):1);');
		}
		$cY = (!empty($layer['cY'])?intval($layer['cY'], 10):1);
		if(!empty($_GET['debug_php'])) {
			tpt_dump('$cY = (!empty($layer[\'cY\'])?intval($layer[\'cY\'], 10):1); '.$cY.' = (!empty('.$layer['cY'].')?intval('.$layer['cY'].', 10):1);');
		}

		$cPL = (!empty($layer['cPL'])?intval($layer['cPL'], 10):0);
		if(!empty($_GET['debug_php'])) {
			tpt_dump('$cPL = (!empty($layer[\'cPL\'])?intval($layer[\'cPL\'], 10):0) '.$cPL.' = (!empty('.$layer['cPL'].')?intval('.$layer['cPL'].', 10):1);');
		}
		$cPR = (!empty($layer['cPR'])?intval($layer['cPR'], 10):0);
		if(!empty($_GET['debug_php'])) {
			tpt_dump('$cPR = (!empty($layer[\'cPR\'])?intval($layer[\'cPR\'], 10):0) '.$cPR.' = (!empty('.$layer['cPR'].')?intval('.$layer['cPR'].', 10):1);');
		}
		$cPT = (!empty($layer['cPT'])?intval($layer['cPT'], 10):0);
		if(!empty($_GET['debug_php'])) {
			tpt_dump('$cPT = (!empty($layer[\'cPT\'])?intval($layer[\'cPT\'], 10):0) '.$cPT.' = (!empty('.$layer['cPT'].')?intval('.$layer['cPT'].', 10):1);');
		}
		$cPB = (!empty($layer['cPB'])?intval($layer['cPB'], 10):0);
		if(!empty($_GET['debug_php'])) {
			tpt_dump('$cPB = (!empty($layer[\'cPB\'])?intval($layer[\'cPB\'], 10):0) '.$cPB.' = (!empty('.$layer['cPB'].')?intval('.$layer['cPB'].', 10):1);');
		}

		/*
		$ncparams = explode(',', $ncparams[1]);
		$ncparams = array_combine($ncparams, $ncparams);
		$ncparams = array_intersect_key($cpf_module->moduleData['id'], $ncparams);
		*/
		//$layer[$nctrgt['pname']] =

		//tpt_dump($cX, true);


		$font = FONTS_PATH.DIRECTORY_SEPARATOR.(!empty($layer['font'])?$fonts[$layer['font']]['file']:DEFAULT_FONT_NAME);
		$font = <<< EOT
-font '$font'
EOT;


		//$color = '-fill '.((!empty($layer['color']) && ($layer['color'] != 'transparent') && ($layer['color'] != 'none'))?''.escapeshellarg($layer['color']):'none').'';
		//if (!empty($layer['message_color']) && strstr($layer['message_color'], ':')) {
		$color = <<< EOT
-fill none
EOT;
		$strokecolor = escapeshellarg('#FFFFFF');
		$stroke = '-stroke '.$strokecolor;
		/*
		if (!empty($layer['color'])) {
			$cprops = $color_module->getColorProps($vars, $layer['color']);
			//tpt_dump($cprops);
			$strokecolor = (!empty($cprops['colordata']['led_hex']) ? '#' . $cprops['colordata']['led_hex'] : 'none');
			$strokecolor = escapeshellarg($strokecolor);
			$stroke = <<< EOT
-stroke $strokecolor
EOT;
		}
		*/
		$bg = <<< EOT
-background 'transparent'
EOT;



		$strokewidth = '';

		$inner_shadow = '';
		$inner_glow = '';
		$drop_shadow = '';
		$outer_glow = '';


		$kern = '';
		if(!empty($layer['kern'])) {
			$kern = escapeshellarg($layer['kern']);
			$kern = <<< EOT
-kerning $kern
EOT;
		}


		//$gravity = '-gravity center';
		$gravity = '';
		if(!empty($layer['gravity'])) {
			$gravity = escapeshellarg($layer['gravity']);
			$gravity = <<< EOT
-gravity $gravity
EOT;
		}

		if(!empty($layer['stroke'])) {
			/*
			$stroke = escapeshellarg($layer['stroke_color']);
			$stroke = <<< EOT
-stroke $stroke
EOT;
			*/


			if(!empty($layer['stroke_width'])) {
				$c_strokewidth = intval($layer['stroke_width'], 10)+2;
				$c_strokewidth = <<< EOT
-strokewidth $c_strokewidth
EOT;

				$strokewidth = intval($layer['stroke_width'], 10);
				$strokewidth = <<< EOT
-strokewidth $strokewidth
EOT;
			}
		}

		$s = array();
		$metrics = array();
		$cYm = $cY;
		if(!empty($_GET['debug_php'])) {
			tpt_dump('$cYm = $cY; '.$cYm.' = '.$cX.';');
		}
		if (!empty($layout['text_topbottom']) && (count($messages)>1)) {
			$cYm = floor($layer['cY']/count($messages));
			if(!empty($_GET['debug_php'])) {
				tpt_dump('$cYm = floor($layer[\'cY\']/count($messages)); '.$cYm.' = floor('.$layer['cY'].'/'.count($messages).');');
			}
		}
		$dcYm = $cYm*2;
		if(!empty($_GET['debug_php'])) {
			tpt_dump('$dcYm = $cYm*2; '.$dcYm.' = '.$cYm.'*2;');
		}
		$resize = '';
		if(!empty($layer['snug_fit_label'])) {
			$resize = <<< EOT
-resize {$cX}x{$cYm}
EOT;
		}

		$pointsize = 0;
		$clp_y = min($cX, $cYm);
		if(!empty($_GET['debug_php'])) {
			tpt_dump('$clp_y = min($cX, $cYm); '.$clp_y.' = min('.$cX.', '.$cYm.');');
		}
		//tpt_dump($clp_y);
		$cXmm = $cX;
		if(!empty($_GET['debug_php'])) {
			tpt_dump('$cXmm = $cX; '.$cXmm.' = '.$cX.';');
		}
		foreach($messages as $tid=>$msg) {
			$msgdata = $cpf_module->moduleData['id'][$tid];


			//tpt_dump($clpnames, true);
			//tpt_dump($clipart, true);

			$text = $msg;
			if(empty($text)) {
				$text = 'W';
			}
			//tpt_dump($text);
			$text = ''.escapeshellarg(str_replace('\\', '\\\\', $text)).'';

			//tpt_dump($clp_y);
			foreach ($clipart as $ctid => $clp) {
				$clpdata = $cpf_module->moduleData['id'][$ctid];
				$cmsg = $cpf_module->moduleData['id'][$clpdata['clipart_text_id']];

				$c = $clipart_module->getClipartPath($vars, $layer[$clpdata['pname']]);

				$fsize = <<< EOT
{$bp}{$im_bin} \
$c \
-format "%@" \
info:
EOT;
				if(!empty($_GET['debug_php'])) {
					tpt_dump($fsize);
				}
				$fsize = self::exec_command($vars, $fsize, '', '', $s, 'size_'.$clpdata['pname'], 1);
				if(!empty($_GET['debug_php'])) {
					tpt_dump($fsize);
				}
				$metrics[$clpdata['pname']] = preg_split('#\+|-#', $fsize);
				$metrics[$clpdata['pname']] = array_shift($metrics[$clpdata['pname']]);
				$metrics[$clpdata['pname']] = explode('x', $metrics[$clpdata['pname']]);
				$metrics[$clpdata['pname']] = array('x'=>$metrics[$clpdata['pname']][0], 'y'=>$metrics[$clpdata['pname']][1], 'proportion'=>$metrics[$clpdata['pname']][0]/$metrics[$clpdata['pname']][1]);
				if(!empty($_GET['debug_php'])) {
					tpt_dump($metrics[$clpdata['pname']]);
				}

				if($tid == $cmsg['id']) {
					if(isset($layer[$clpdata['pname']])) {
						if(!empty($layout['clipart_leftright'])) {
							if(!empty($_GET['debug_php'])) {
								tpt_dump('$cXmm; '.$cXmm);
							}
							$cXmm -= ($cYm+2);
							if(!empty($_GET['debug_php'])) {
								tpt_dump('$cXmm -= ($cYm+2); '.$cXmm.' -= ('.$cYm.'+2);');
							}
							/*
							if(!empty($_GET['debug_php'])) {
								tpt_dump('$cXmm; '.$cXmm);
							}
							$cXmm -= ceil($cYm*$metrics[$clpdata['pname']]['proportion'])+2;
							if(!empty($_GET['debug_php'])) {
								tpt_dump('$cXmm: '.$cXmm);
							}
							*/
						}
					}
				}
			}



			$fsize = <<< EOT
{$bp}{$im_bin} \
-size {$cXmm}x{$cYm} \
$stroke \
$strokewidth \
$bg \
\
-fill 'white' \
$font \
$kern \
label:$text \
-format "%[label:pointsize]|%@" \
info:
EOT;
			if(!empty($_GET['debug_php'])) {
				tpt_dump($fsize);
			}
			$fsize = self::exec_command($vars, $fsize, '', '', $s, $msgdata['pname'], 1);
			if(!empty($_GET['debug_php'])) {
				tpt_dump($fsize);
			}
			$metrics[$msgdata['pname']] = explode('|', $fsize);
			//tpt_dump($metrics, true);
			$metrics[$msgdata['pname']][1] = preg_split('#\+|-#', $metrics[$msgdata['pname']][1]);
			$metrics[$msgdata['pname']][1] = array_shift($metrics[$msgdata['pname']][1]);
			$metrics[$msgdata['pname']][1] = explode('x', $metrics[$msgdata['pname']][1]);
			$metrics[$msgdata['pname']] = array('x'=>$metrics[$msgdata['pname']][1][0], 'y'=>$metrics[$msgdata['pname']][1][1], 'ps'=>$metrics[$msgdata['pname']][0]);
			if(!empty($_GET['debug_php'])) {
				tpt_dump($metrics[$msgdata['pname']]);
			}
			/*
			for($i=1;$i<6;$i++) {
				$ps = $metrics[$msgdata['pname']]['ps']+$i;
				$fsize2 = <<< EOT
{$bp}{$im_bin} \
-pointsize $ps \
$stroke \
$strokewidth \
$bg \
\
-fill 'white' \
$font \
$kern \
label:$text \
-format "%@" \
info:
EOT;
				if(!empty($_GET['debug_php'])) {
					tpt_dump($fsize2);
				}
				$fsize2 = self::exec_command($vars, $fsize2, '', '', $s, $msgdata['pname'], 1);
				$metrics2 = preg_split('#\+|-#', $fsize2);
				$metrics2 = array_shift($metrics2);
				$metrics2 = explode('x', $metrics2);
				$metrics2 = array('x'=>$metrics2[0], 'y'=>$metrics2[1]);
				if($metrics2['x']<=$cXmm && $metrics2['y']<=$cYm) {
					$metrics[$msgdata['pname']] = array('x'=>$metrics2['x'], 'y'=>$metrics2['y'], 'ps'=>$ps);
				} else {
					break;
				}
			}
			*/

			$dx = $cXmm - $metrics[$msgdata['pname']]['x'];
			if(!empty($_GET['debug_php'])) {
				tpt_dump('$dx = $cXmm - $metrics[$msgdata[\'pname\']][\'x\']; '.$dx.' = '.$cXmm.' - '.$metrics[$msgdata['pname']]['x'].');');
			}
			$dy = $cYm - $metrics[$msgdata['pname']]['y'];
			if(!empty($_GET['debug_php'])) {
				tpt_dump('$dy = $cYm - $metrics[$msgdata[\'pname\']][\'y\']; '.$dy.' = '.$cYm.' - '.$metrics[$msgdata['pname']]['y'].');');
			}
			//$metrics[$msgdata['pname']]['ops'] = $metrics[$msgdata['pname']]['ps'];
			//tpt_dump($clp_y);
			/*
			$ps = $metrics[$msgdata['pname']]['ps']-1;
			$fsize2 .= <<< EOT
-size {$cX}x{$dcYm} \
-pointsize {$ps} \
$stroke \
$strokewidth \
$bg \
\
-fill 'white' \
$font \
$kern \
label:$text \
-format "%@" \
EOT;
			*/
			$i=-9;
			$fsize2 = array();
			do {
				$ps = $metrics[$msgdata['pname']]['ps']+$i;
				$fsize2[] = <<< EOT
-size {$cX}x{$dcYm} \
-pointsize {$ps} \
$stroke \
$strokewidth \
$bg \
\
-fill 'white' \
$font \
$kern \
label:$text \
-format "\\n%@" \
EOT;
				$i++;
			} while(($i < 10));
			$fsize2 = implode("\n", $fsize2);
			$fsize2 = <<< EOT
{$bp}{$im_bin} \
$fsize2
info:
EOT;

			if(!empty($_GET['debug_php'])) {
				tpt_dump($fsize2);
			}
			$fsize2 = self::exec_command($vars, $fsize2, '', '', $s, $msgdata['pname'], 1);
			if(!empty($_GET['debug_php'])) {
				tpt_dump($fsize2);
			}
			$fsize2 = preg_split('#\R#', trim($fsize2));
			$fskeys = array(-9,-8,-7,-6,-5,-4,-3,-2,-1,0,1,2,3,4,5,6,7,8,9);
			$fsize2 = array_combine($fskeys, $fsize2);
			$ps = $metrics[$msgdata['pname']]['ps'];
			//tpt_dump($fsize2, true);
			foreach($fsize2 as $i=>$fs) {
				$ps2 = $ps+$i;
				$fs2 = preg_split('#\+|-#', $fs);
				$fs2 = array_shift($fs2);
				$fs2 = explode('x', $fs2);
				$dx = $cXmm - $fs2[0];
				if(!empty($_GET['debug_php'])) {
					tpt_dump('$dx = $cXmm - $fs2[0]; '.$dx.' = '.$cXmm.' - '.$fs2[0].');');
				}
				$dy = $cYm - $fs2[1];
				if(!empty($_GET['debug_php'])) {
					tpt_dump('$dy = $cYm - $fs2[1]; '.$dy.' = '.$cYm.' - '.$fs2[1].');');
				}
				//tpt_dump($clp_y);
				if (($dx >= 0) && ($dy >= 0)) {
					$metrics[$msgdata['pname']] = array('x' => $fs2[0], 'y' => $fs2[1], 'ps' => $ps2);
				}

				if(($dx <= 0) || ($dy <= 0)) {
					break;
				}
				if (!empty($_GET['debug_php'])) {
					tpt_dump($metrics[$msgdata['pname']]);
				}
			}
			//tpt_dump($metrics, true);
			//tpt_dump($fsize, true);

			//tpt_dump($clp_y);
			if(empty($clp_y) || (!empty($metrics[$msgdata['pname']]['y']) && ($clp_y > $metrics[$msgdata['pname']]['y']))) {
				$clp_y = $metrics[$msgdata['pname']]['y'];
				if(!empty($_GET['debug_php'])) {
					tpt_dump('$clp_y = $metrics[$msgdata[\'pname\']][\'y\']; '.$clp_y.' = '.$metrics[$msgdata['pname']]['y'].';');
				}
			}
			//tpt_dump($metrics[$msgdata['pname']]['y']);
			//tpt_dump($clp_y);

			if(empty($pointsize) || ($pointsize>$metrics[$msgdata['pname']]['ps'])) {
				$pointsize = $metrics[$msgdata['pname']]['ps'];
			}
		}
		//tpt_dump($metrics, true);

		$msgs = array();
		foreach($messages as $tid=>$msg) {
			$cXm = $cX;
			if(!empty($_GET['debug_php'])) {
				tpt_dump('$cXm = $cX; '.$cXm.' = '.$cX.';');
			}
			$msgdata = $cpf_module->moduleData['id'][$tid];
			//$text = implode($layout['text_separator'], $messages);
			$text = $msg;
			if(empty($text)) {
				$text = ' ';
			}

			//tpt_dump($text);
			$text = ''.escapeshellarg(str_replace('\\', '\\\\', $text)).'';

			$pgravity = '-gravity Center';
			if (!empty($layout['text_topbottom']) && (count($messages)>1)) {
				if(!empty($msg_module->moduleData['pname'][$msgdata['pname']]['line2'])) {
					$pgravity = '-gravity South';
				} else {
					$pgravity = '-gravity North';
				}
			}


			//tpt_dump($clipart);
			//tpt_dump($layout);
			$clp_x = max(min($cX, $cYm), floor(($cX - $metrics[$msgdata['pname']]['x'])/max(1, count($clipart))));
			if(!empty($_GET['debug_php'])) {
				tpt_dump('$clp_x = max(min($cX, $cYm), floor(($cX - $metrics[$msgdata[\'pname\']][\'x\'])/max(1, count($clipart)))); '.$clp_x.' = max(min('.$cX.', '.$cYm.'), floor(('.$cX.' - '.$metrics[$msgdata['pname']]['x'].')/max(1, '.count($clipart).')));');
			}
			$clpnames = array();
			$clpgrvt = array();
			$clpoffsigns = array();
			$msggrvt = '-gravity Center';
			if (!empty($layout['text_topbottom']) && (count($messages)>1)) {
				foreach ($clipart as $ctid => $clp) {
					$clpdata = $cpf_module->moduleData['id'][$ctid];
					$cmsg = $cpf_module->moduleData['id'][$clpdata['clipart_text_id']];

					if($tid == $cmsg['id']) {
						//tpt_dump('asd');
						//tpt_dump($clpdata['pname'], true);
						if(isset($layer[$clpdata['pname']])) {
							//tpt_dump('asd');
							//tpt_dump($layout, true);
							//tpt_dump($clpdata['pname'], true);
							$clpg = '-gravity East';
							$clpoffsign = '+';
							$msggrvt = '-gravity West';
							if(!empty($layout['clipart_leftright'])) {
								$cXm -= ($clp_x);
								if(!empty($_GET['debug_php'])) {
									tpt_dump('$cXm -= ($clp_x); '.$cXm.' -= ('.$clp_x.');');
								}
								//tpt_dump($layer[$clpdata['pname']], true);
								if(empty($clpdata['orientation'])) {
									$clpg = '-gravity West';
									$clpoffsign = '-';
									$msggrvt = '-gravity East';
								}
							}

							$clpoffsigns[$ctid][$layer[$clpdata['pname']]] = $clpoffsign;
							$clpgrvt[$ctid][$layer[$clpdata['pname']]] = $clpg;
							$clpnames[$ctid][$layer[$clpdata['pname']]] = $clipart_module->getClipartPath($vars, $layer[$clpdata['pname']]);
						}
					}


				}
			} else {
				foreach ($clipart as $ctid => $clp) {
					$clpdata = $cpf_module->moduleData['id'][$ctid];
					$cmsg = $cpf_module->moduleData['id'][$clpdata['clipart_text_id']];

					if($tid == $cmsg['id']) {
						//tpt_dump('asd');
						//tpt_dump($clpdata['pname'], true);
						if(isset($layer[$clpdata['pname']])) {
							//tpt_dump('asd');
							//tpt_dump($layout, true);
							//tpt_dump($clpdata['pname'], true);
							$clpg = '-gravity East';
							$clpoffsign = '+';
							$msggrvt = '-gravity West';
							if(!empty($layout['clipart_leftright'])) {
								$cXm -= ($clp_x);
								if(!empty($_GET['debug_php'])) {
									tpt_dump('$cXm -= ($clp_x); '.$cXm.' -= ('.$clp_x.');');
								}
								//tpt_dump($layer[$clpdata['pname']], true);
								if(empty($clpdata['orientation'])) {
									$clpg = '-gravity West';
									$clpoffsign = '-';
									$msggrvt = '-gravity East';
								}
							}

							$clpoffsigns[$ctid][$layer[$clpdata['pname']]] = $clpoffsign;
							$clpgrvt[$ctid][$layer[$clpdata['pname']]] = $clpg;
							$clpnames[$ctid][$layer[$clpdata['pname']]] = $clipart_module->getClipartPath($vars, $layer[$clpdata['pname']]);
						}
					}


				}
			}


			if(!empty($layout['clipart_leftright']) && (count($clpnames) > 1)) {
				$msggrvt = '-gravity Center';
			}

			//tpt_dump($clpnames);


			/*
			$clp_xx = $clp_x-5;
			$clp_yy = $clp_y-5;
			-size x{$clp_yy} \
			-resize {$clp_xx}x{$clp_yy} \

+clone \
-compose Over -composite \
+clone \
-compose Over -composite \
+clone \
-compose Over -composite \
+clone \
-compose Over -composite


\( \
-background 'transparent' \
-stroke none \
-strokewidth 0 \
$bg \
$color \
-trim \
+repage \
-density 1200 \
-size x{$clp_yy} \
-resize {$clp_xx}x{$clp_yy} \
$c \
\) \
-compose Over \
-composite \
			*/
			$clp_xx = $clp_x-5;
			if(!empty($_GET['debug_php'])) {
				tpt_dump('$clp_xx = $clp_x-5; '.$clp_xx.' = '.$clp_x.'-5;');
			}
			$clp_yy = $clp_y-5;
			if(!empty($_GET['debug_php'])) {
				tpt_dump('$clp_yy = $clp_y-5; '.$clp_yy.' = '.$clp_y.'-5;');
			}
			$clp = array();
			$cmetrics = array();
			if(!empty($clpnames)) {
				if(!empty($layout['clipart_leftright'])) {
					foreach($clpnames as $tid=>$clps) {
						foreach ($clps as $clpid => $c) {
							//$c'[{$clp_sq}x{$clp_sq}]' \
							$clpg = $clpgrvt[$tid][$clpid];
							$c_c = <<< EOT
						\( \
							-stroke none \
							-strokewidth 0 \
							$bg \
							$color \
							-trim \
							+repage \
							-density 1200 \
							-size x{$clp_y} \
							-resize {$clp_xx}x{$clp_yy} \
							$c \
						\)
EOT;
							$c_c2 = <<< EOT
					\( \
						-stroke none \
						-strokewidth 0 \
						$bg \
						$color \
						-trim \
						+repage \
						-density 1200 \
						-size x{$clp_y} \
						-resize {$clp_xx}x{$clp_yy} \
						$c \
					\)
EOT;
							$fsize = <<< EOT
{$bp}{$im_bin} \
-stroke none \
-strokewidth 0 \
$bg \
$color \
-trim \
+repage \
-density 1200 \
-size x{$clp_y} \
-resize {$clp_xx}x{$clp_yy} \
$c \
-format "%@" \
info:
EOT;
							if (!empty($_GET['debug_php'])) {
								tpt_dump($fsize);
							}
							$csize = self::exec_command($vars, $fsize, '', '', $s, 'clipart_metrics_' . $clpid, 1);
							//tpt_dump($csize);
							$cmetrics[$clpid] = preg_split('#\+|-#', $csize);
							//tpt_dump($cmetrics[$clpid]);
							$cmetrics[$clpid] = array_shift($cmetrics[$clpid]);
							//tpt_dump($cmetrics[$clpid]);
							$cmetrics[$clpid] = explode('x', $cmetrics[$clpid]);
							//tpt_dump($cmetrics[$clpid]);
							if (empty($cmetrics[$clpid][0]) || empty($cmetrics[$clpid][1])) {
								//tpt_dump('asd');
								//tpt_dump('asd', true);
								$c_c = <<< EOT
						\( \
							-stroke '#FFFFFF' \
							-strokewidth 1 \
							$bg \
							-trim \
							+repage \
							-density 1200 \
							-size x{$clp_y} \
							-resize {$clp_xx}x{$clp_yy} \
							$c \
						\)
EOT;
								$c_c2 = <<< EOT
					\( \
						-stroke '#FFFFFF' \
						-strokewidth 1 \
						$bg \
						-trim \
						+repage \
						-density 1200 \
						-size x{$clp_y} \
						-resize {$clp_xx}x{$clp_yy} \
						$c \
					\)
EOT;

								$fsize = <<< EOT
{$bp}{$im_bin} \
-stroke '#FFFFFF' \
-strokewidth 1 \
$bg \
-trim \
+repage \
-density 1200 \
-size x{$clp_y} \
-resize {$clp_xx}x{$clp_yy} \
$c \
-format "%@" \
info:
EOT;
								if (!empty($_GET['debug_php'])) {
									tpt_dump($fsize);
								}
								$csize = self::exec_command($vars, $fsize, '', '', $s, 'clipart_metrics2_' . $clpid, 1);
								//tpt_dump($csize);
								$cmetrics[$clpid] = preg_split('#\+|-#', $csize);
								//tpt_dump($cmetrics[$clpid]);
								$cmetrics[$clpid] = array_shift($cmetrics[$clpid]);
								$cmetrics[$clpid] = explode('x', $cmetrics[$clpid]);
							}
							/*
														$clp[] = <<< EOT
							\( \
							-size {$clp_x}x{$clp_y} \
							xc:'#FFFFFF' \
							\( \
							-size {$clp_x}x{$clp_y} \
							xc:transparent \
							$c_c \
							-gravity center \
							-geometry -1-1 \
							-compose Over -composite \
							$c_c \
							-gravity center \
							-geometry -1-0 \
							-compose Over -composite \
							$c_c \
							-gravity center \
							-geometry -1+1 \
							-compose Over -composite \
							$c_c \
							-gravity center \
							-geometry +0-1 \
							-compose Over -composite \
							$c_c \
							-gravity center \
							-geometry +0+0 \
							-compose Over -composite \
							$c_c \
							-gravity center \
							-geometry +0+1 \
							-compose Over -composite \
							$c_c \
							-gravity center \
							-geometry +1-1 \
							-compose Over -composite \
							$c_c \
							-gravity center \
							-geometry +1+0 \
							-compose Over -composite \
							$c_c \
							-gravity center \
							-geometry +1+1 \
							-compose Over -composite \
							$c_c \
							-gravity center \
							-geometry +0+0 \
							-compose DstOut -composite \
							\) \
							-compose CopyOpacity -composite \
							\) \
							-trim \
							-gravity Center \
							-resize x{$clp_y} \
							$clpg \
							-compose Over -composite
							EOT;
							*/
							$clpoffsign = $clpoffsigns[$tid][$clpid];
							if(!empty($_GET['debug_php'])) {
								tpt_dump('$clpoffsign = $clpoffsigns[$tid][$clpid]; '.$clpoffsign);
							}
							$clpoffx = ceil($metrics[$msgdata['pname']]['x']/2);
							if(!empty($_GET['debug_php'])) {
								tpt_dump('$clpoffx = ceil($metrics[$msgdata[\'pname\']][\'x\']/2); '.$clpoffx.' = ceil('.$metrics[$msgdata['pname']]['x'].'/2);');
							}
							//if($clpoffsign == "-") {
							//tpt_dump($cmetrics[$clpid], true);
							$clpoffx += $cmetrics[$clpid][0];
							if(!empty($_GET['debug_php'])) {
								tpt_dump('$clpoffx += $cmetrics[$clpid][0]; '.$clpoffx.' += $cmetrics[$clpid][0];');
							}
							//}
							//$clpoffx = $metrics[$msgdata['pname']]['x'];
							//tpt_dump($cmetrics[$clpid][0]);
							//tpt_dump($cmetrics[$clpid][0], true);
							//tpt_dump($clpg, true);
							$clp[] = <<< EOT
			\( \
				-respect-parenthesis \
				-background transparent \
				-page {$clp_x}x{$clp_y} \
				\
				\( \
					-size {$clp_x}x{$clp_y} \
					xc:transparent \
					\( \
$c_c \
						\( \
						-size {$clp_x}x{$clp_y} \
						xc:'#FFFFFF' \
						\) \
						-compose SrcIn \
						-composite \
					\) \
					-gravity Center \
					-compose Over \
					-composite \
				\) \
				\
				\( \
				-clone 0 \
				-repage -1-1 \
				\) \
				\( \
				-clone 0 \
				-repage -1+0 \
				\) \
				\( \
				-clone 0 \
				-repage -1+1 \
				\) \
				\( \
				-clone 0 \
				-repage +0-1 \
				\) \
				\( \
				-clone 0 \
				-repage +0+0 \
				\) \
				\( \
				-clone 0 \
				-repage +0+1 \
				\) \
				\( \
				-clone 0 \
				-repage +1-1 \
				\) \
				\( \
				-clone 0 \
				-repage +1+0 \
				\) \
				\( \
				-clone 0 \
				-repage +1+1 \
				\) \
				-compose Over \
				-flatten \
				\( \
$c_c2 \
					\( \
					-size {$clp_x}x{$clp_y} \
					xc:white \
					\) \
					-compose SrcIn \
					-composite \
				\) \
				-gravity Center \
				-compose DstOut \
				-composite \
				-geometry {$clpoffsign}{$clpoffx}+0 \
			\) \
			-geometry +0+0 \
			$clpg \
			-compose Over -composite
EOT;
						}
					}
				}
			}
			$clp = implode(' \\'."\n", $clp);
			//tpt_dump($clpnames, true);
			//tpt_dump($clipart, true);
			//tpt_dump($clp, true);
			$cXd = $cX*2;
			if(!empty($_GET['debug_php'])) {
				tpt_dump('$cXd = $cX*2; '.$cXd.' = '.$cX.'*2;');
			}
			$cYmd = $cYm*2;
			if(!empty($_GET['debug_php'])) {
				tpt_dump('$cYmd = $cYm*2; '.$cYmd.' = '.$cYm.'*2;');
			}
			/*
						$msgs[$msgdata['pname']] = <<< EOT
					\( \
						\( \
							-pointsize $pointsize \
							-stroke '#FFFFFF' \
							$strokewidth \
							$bg \
							\
							-fill none \
							$font \
							$kern \
							label:$text \
							-trim \
							-gravity Center \
							-extent {$cXm}x{$cYm} \
							$clp \
						\) \
						-trim \
						+repage \
						$resize \
					\) \
					$pgravity \
					-compose Over -composite
			EOT;
			*/
			$msgs[$msgdata['pname']] = <<< EOT
		\( \
			-size {$cX}x{$dcYm} \
			-gravity North \
			-pointsize $pointsize \
			-stroke '#FFFFFF' \
			$strokewidth \
			$bg \
			\
			-fill none \
			$font \
			$kern \
			label:$text \
			-trim \
			$msggrvt \
			-extent {$cX}x{$cYm} \
$clp \
		\) \
		$pgravity \
		-compose Over -composite
EOT;
		}
		//tpt_dump($metrics, true);

		if(count($msgs) > 1) {
			//$addlabel = '\\( ' . implode(' \\' . "\n" , $msgs) . ' \\)';
			$addlabel =  implode(' \\' . "\n" , $msgs) ;
		} else {
			$addlabel = implode($msgs);
		}


		$command = <<< EOT
{$bp}{$im_bin} \
-respect-parenthesis \
\( \
	\( \
		\( \
			-size {$cX}x{$cY} \
			xc:transparent \
		\) \
$addlabel \
	\) \
\) \

EOT;
		/*
				$command = <<< EOT
		{$bp}{$im_bin} \
		-respect-parenthesis \
		-size {$cX}x{$cY} \
		xc:transparent \
		$addlabel \
		-trim \
		+repage \
		$resize \

		EOT;
		*/

		return $command;
	}
	/*
	static function c_led_message2(&$vars, &$layer, &$out='', &$steps=array()) {
		$color_module = getModule($vars, 'BandColor');
		$msg_module = getModule($vars, 'BandMessage');
		$cpf_module = getModule($vars, 'CustomProductField');
		$fonts_module = getModule($vars, 'BandFont');
		$layouts_module = getModule($vars, 'BandLayout');
		$fonts = $fonts_module->moduleData['id'];
		$clipart_module = getModule($vars, 'BandClipart');


		//$isfront (empty($message['back']) && empty($message['line2'])) {
		$bp = BIN_PATH;
		if(defined('ALT_BIN_PATH')) {
			$bp = ALT_BIN_PATH;
		}
		$im_bin = IMAGEMAGICK_BIN;

		//tpt_dump($layer);
		$layout = (!empty($layer['band_layout'])?intval($layer['band_layout'], 10):(!empty($layer['layout'])?intval($layer['layout'], 10):1));
		$layout = $layouts_module->moduleData['id'][$layout];

		$targets = explode(',', $layer['target']);
		$targets = array_combine($targets, $targets);
		$targets = array_intersect_key($cpf_module->moduleData['id'], $targets);

		$messages = array();
		$m = array();
		$clipart = array();
		foreach($targets as $tid=>$target) {
			if(isset($layer[$target['pname']])) {
				if(!empty($target['text'])) {
					$messages[$tid] = $layer[$target['pname']];
				} else if(!empty($target['clipart'])) {
					$clipart[$tid] = $layer[$target['pname']];
				}
			}
		}

		$ncmessages = array();
		$ncparams = explode('|', $layer['nullcheck_preview_params_ids']);
		foreach($ncparams as $ncparam) {
			$ncp = explode(':', $ncparam);
			if(!empty($cpf_module->moduleData['id'][$ncp[0]]) && !empty($cpf_module->moduleData['id'][$ncp[0]]['text'])) {
				$ncmessages[$ncp[0]] = $cpf_module->moduleData['id'][$ncp[0]];
			}
		}

		//tpt_dump($layer['cX']);
		//tpt_dump($layer['cPR']);
		//tpt_dump($layer['cPL']);
		if(!empty($ncmessages)) {
			$ncmsg = reset($ncmessages);
			//tpt_dump($layer[$ncmsg['pname']]);
			//tpt_dump($layout['text_frontback']);
			//tpt_dump($messages, true);
			if (!empty($layout['text_frontback']) && !empty($ncmessages) && !empty($layer[$ncmsg['pname']])) {
				$imsg = reset($messages);
				$imsg = key($messages);
				$cXex = floor($layer['cX'] / 2);
				$layer['cX'] -= ($cXex+5);
				//tpt_dump($msg_module->moduleData['pname'][$cpf_module->moduleData['id'][$imsg]['pname']]);
				if (!empty($cpf_module->moduleData['id'][$imsg]['pname']) && !empty($msg_module->moduleData['pname'][$cpf_module->moduleData['id'][$imsg]['pname']]['back'])) {
					$layer['cPL'] += ($cXex+5);
				} else {
					$layer['cPR'] += ($cXex+5);
				}
			}
		}
		//tpt_dump($layer['cX']);
		//tpt_dump($layer['cPR']);
		//tpt_dump($layer['cPL']);


		$cX = (!empty($layer['cX'])?intval($layer['cX'], 10):1);
		$cY = (!empty($layer['cY'])?intval($layer['cY'], 10):1);

		$cPL = (!empty($layer['cPL'])?intval($layer['cPL'], 10):0);
		$cPR = (!empty($layer['cPR'])?intval($layer['cPR'], 10):0);
		$cPT = (!empty($layer['cPT'])?intval($layer['cPT'], 10):0);
		$cPB = (!empty($layer['cPB'])?intval($layer['cPB'], 10):0);


		//$layer[$nctrgt['pname']] =

		//tpt_dump($cX, true);


		$font = FONTS_PATH.DIRECTORY_SEPARATOR.(!empty($layer['font'])?$fonts[$layer['font']]['file']:DEFAULT_FONT_NAME);
		$font = <<< EOT
-font '$font'
EOT;


		//$color = '-fill '.((!empty($layer['color']) && ($layer['color'] != 'transparent') && ($layer['color'] != 'none'))?''.escapeshellarg($layer['color']):'none').'';
		//if (!empty($layer['message_color']) && strstr($layer['message_color'], ':')) {
		$color = <<< EOT
-fill none
EOT;
		$strokecolor = escapeshellarg('#FFFFFF');
		$stroke = '-stroke '.$strokecolor;
		if (!empty($layer['color'])) {
			$cprops = $color_module->getColorProps($vars, $layer['color']);
			//tpt_dump($cprops);
			$strokecolor = (!empty($cprops['colordata']['led_hex']) ? '#' . $cprops['colordata']['led_hex'] : 'none');
			$strokecolor = escapeshellarg($strokecolor);
			$stroke = <<< EOT
-stroke $strokecolor
EOT;
		}
		$bg = <<< EOT
-background 'transparent'
EOT;



		$strokewidth = '';

		$inner_shadow = '';
		$inner_glow = '';
		$drop_shadow = '';
		$outer_glow = '';


		$kern = '';
		if(!empty($layer['kern'])) {
			$kern = escapeshellarg($layer['kern']);
			$kern = <<< EOT
-kerning $kern
EOT;
		}


		//$gravity = '-gravity center';
		$gravity = '';
		if(!empty($layer['gravity'])) {
			$gravity = escapeshellarg($layer['gravity']);
			$gravity = <<< EOT
-gravity $gravity
EOT;
		}

		if(!empty($layer['stroke'])) {
			if(!empty($layer['stroke_width'])) {
				$c_strokewidth = intval($layer['stroke_width'], 10)+2;
				$c_strokewidth = <<< EOT
-strokewidth $c_strokewidth
EOT;

				$strokewidth = intval($layer['stroke_width'], 10);
				$strokewidth = <<< EOT
-strokewidth $strokewidth
EOT;
			}
		}

		$s = array();
		$metrics = array();
		$cYm = $cY;
		if (!empty($layout['text_topbottom']) && (count($messages)>1)) {
			$cYm = floor($layer['cY']/count($messages));
		}
		$resize = '';
		if(!empty($layer['snug_fit_label'])) {
			$resize = <<< EOT
-resize {$cX}x{$cYm}
EOT;
		}

		$pointsize = 0;
		$clp_y = min($cX, $cYm);
		$cXmm = $cX;
		foreach($messages as $tid=>$msg) {
			$msgdata = $cpf_module->moduleData['id'][$tid];


			//tpt_dump($clpnames, true);
			//tpt_dump($clipart, true);

			$text = $msg;
			if(empty($text)) {
				$text = 'W';
			}
			//tpt_dump($text);
			$text = ''.escapeshellarg(str_replace('\\', '\\\\', $text)).'';


			foreach ($clipart as $ctid => $clp) {
				$clpdata = $cpf_module->moduleData['id'][$ctid];
				$cmsg = $cpf_module->moduleData['id'][$clpdata['clipart_text_id']];

				if($tid == $cmsg['id']) {
					if(isset($layer[$clpdata['pname']])) {
						if(!empty($layout['clipart_leftright'])) {
							$cXmm -= ($cYm+2);
						}
					}
				}
			}



			$fsize = <<< EOT
{$bp}{$im_bin} \
-size {$cXmm}x{$cYm} \
$stroke \
$strokewidth \
$bg \
\
-fill 'white' \
$font \
$kern \
label:$text \
-format "%[label:pointsize]|%@" \
info:
EOT;
			if(!empty($_GET['debug_php'])) {
				tpt_dump($fsize);
			}
			$fsize = self::exec_command($vars, $fsize, '', '', $s, $msgdata['pname'], 1);
			$metrics[$msgdata['pname']] = explode('|', $fsize);
			//tpt_dump($metrics, true);
			$metrics[$msgdata['pname']][1] = preg_split('#\+|-#', $metrics[$msgdata['pname']][1]);
			$metrics[$msgdata['pname']][1] = array_shift($metrics[$msgdata['pname']][1]);
			$metrics[$msgdata['pname']][1] = explode('x', $metrics[$msgdata['pname']][1]);
			$metrics[$msgdata['pname']] = array('x'=>$metrics[$msgdata['pname']][1][0], 'y'=>$metrics[$msgdata['pname']][1][1], 'ps'=>$metrics[$msgdata['pname']][0]);

			$dx = $cXmm - $metrics[$msgdata['pname']]['x'];
			$dy = $cYm - $metrics[$msgdata['pname']]['y'];
			$metrics[$msgdata['pname']]['ops'] = $metrics[$msgdata['pname']]['ps'];
			if(false && $dy > 2) {
				$dps = round($metrics[$msgdata['pname']]['ps']) + $dy;
				$fsize2 = <<< EOT
{$bp}{$im_bin} \
-pointsize {$dps} \
$stroke \
$strokewidth \
$bg \
\
-fill 'white' \
$font \
$kern \
label:$text \
-format "%@" \
info:
EOT;
				if(!empty($_GET['debug_php'])) {
					tpt_dump($fsize2);
				}
				$fsize2 = self::exec_command($vars, $fsize2, '', '', $s, $msgdata['pname'], 1);
				$fsize2 = preg_split('#\+|-#', $fsize2);
				$fsize2 = array_shift($fsize2);
				$fsize2 = explode('x', $fsize2);
				if(($fsize2[0] <= $cXmm) && ($fsize2[1] <= $cYm)) {
					$metrics[$msgdata['pname']]['ops'] = $dps;
				}
			}
			//tpt_dump($metrics, true);
			//tpt_dump($fsize, true);
			if(empty($pointsize) || ($pointsize > $metrics[$msgdata['pname']]['ops'])) {
				$pointsize = $metrics[$msgdata['pname']]['ops'];
			}

			if(empty($clp_y) || (!empty($metrics[$msgdata['pname']]['y']) && ($clp_y > $metrics[$msgdata['pname']]['y']))) {
				$clp_y = $metrics[$msgdata['pname']]['y'];
			}

		}
		//tpt_dump($metrics, true);

		if(empty($pointsize)) {
			$pointsize = 10;
		}
		$msgs = array();
		foreach($messages as $tid=>$msg) {
			$cXm = $cX;
			$msgdata = $cpf_module->moduleData['id'][$tid];
			//$text = implode($layout['text_separator'], $messages);
			$text = $msg;
			if(empty($text)) {
				$text = ' ';
			}

			//tpt_dump($text);
			$text = ''.escapeshellarg(str_replace('\\', '\\\\', $text)).'';

			$pgravity = '-gravity Center';
			if (!empty($layout['text_topbottom']) && (count($messages)>1)) {
				if(!empty($msg_module->moduleData['pname'][$msgdata['pname']]['line2'])) {
					$pgravity = '-gravity South';
				} else {
					$pgravity = '-gravity North';
				}
			}


			if(!empty($layer['inner_shadow'])) {
				//tpt_dump('asd', true);
				$inner_shadow_color = '#333333';
				if(!empty($layer['inner_shadow_color'])) {
					$inner_shadow_color = $layer['inner_shadow_color'];
				}
				$inner_shadow_color = escapeshellarg($inner_shadow_color);
				$inner_shadow_opacity = '';
				if(!empty($layer['inner_shadow_opacity'])) {
					$inner_shadow_opacity = floatval($layer['inner_shadow_opacity']);
					$inner_shadow_opacity = <<< EOT
-alpha set \
-channel a \
-evaluate \
multiply $inner_shadow_opacity \
+channel
EOT;
				}

				$inner_shadow_distance_x = intval($layer['inner_shadow_distance_x'], 10);
				$inner_shadow_distance_x = (($inner_shadow_distance_x>=0)?'+'.$inner_shadow_distance_x:$inner_shadow_distance_x);
				$inner_shadow_distance_y = intval($layer['inner_shadow_distance_y'], 10);
				$inner_shadow_distance_y = (($inner_shadow_distance_y>=0)?'+'.$inner_shadow_distance_y:$inner_shadow_distance_y);
				$inner_shadow = <<< EOT
\( \
-pointsize $pointsize \
-background 'transparent' \
-fill $inner_shadow_color \
$font \
$kern \
label:$text \
-trim \
-gravity center \
-extent {$cX}x{$cYm} \
\( \
+clone \
-fill 'white' \
-colorize 100 \
\) \
-geometry $inner_shadow_distance_x$inner_shadow_distance_y \
-compose Dst_Out -composite \
\
$inner_shadow_opacity \
\) \
-gravity center \
-geometry +0+0 \
-compose Over -composite
EOT;
			}

			if(!empty($layer['inner_glow'])) {
				$inner_glow_color = '#FFFFFF';
				if(!empty($layer['inner_glow_color'])) {
					$inner_glow_color = $layer['inner_glow_color'];
				}
				$inner_glow_color = escapeshellarg($inner_glow_color);
				$inner_glow_opacity = '';
				if(!empty($layer['inner_glow_opacity'])) {
					$inner_glow_opacity = floatval($layer['inner_glow_opacity']);
					$inner_glow_opacity = <<< EOT
-alpha set \
-channel a \
-evaluate \
multiply $inner_glow_opacity \
+channel
EOT;
				}

				$inner_glow_distance_x = intval($layer['inner_glow_distance_x'], 10);
				$inner_glow_distance_x = (($inner_glow_distance_x>=0)?'+'.$inner_glow_distance_x:$inner_glow_distance_x);
				$inner_glow_distance_y = intval($layer['inner_glow_distance_y'], 10);
				$inner_glow_distance_y = (($inner_glow_distance_y>=0)?'+'.$inner_glow_distance_y:$inner_glow_distance_y);
				$inner_glow = <<< EOT
\( \
-pointsize $pointsize \
-background 'transparent' \
-fill $inner_glow_color \
$font \
$kern \
label:$text \
-trim \
-gravity center \
-extent {$cX}x{$cYm} \
\( \
+clone \
-fill 'white' \
-colorize 100 \
\) \
-geometry $inner_glow_distance_x$inner_glow_distance_y \
-compose Dst_Out -composite \
\
$inner_glow_opacity \
\) \
-gravity center \
-geometry +0+0 \
-compose Over -composite
EOT;
			}

			if(!empty($layer['drop_shadow'])) {
				$drop_shadow_color = '#333333';
				if(!empty($layer['drop_shadow_color'])) {
					$drop_shadow_color = $layer['drop_shadow_color'];
				}
				$drop_shadow_color = escapeshellarg($drop_shadow_color);
				$drop_shadow_opacity = '';
				if(!empty($layer['drop_shadow_opacity'])) {
					$drop_shadow_opacity = floatval($layer['drop_shadow_opacity']);
					$drop_shadow_opacity = <<< EOT
-alpha set \
-channel a \
-evaluate \
multiply $drop_shadow_opacity \
+channel
EOT;
				}

				$drop_shadow_distance_x = intval($layer['drop_shadow_distance_x'], 10);
				$drop_shadow_cast_x = intval($layer['drop_shadow_distance_x'], 10)*-1;
				$drop_shadow_distance_x = (($drop_shadow_distance_x>=0)?'+'.$drop_shadow_distance_x:$drop_shadow_distance_x);
				$drop_shadow_cast_x = (($drop_shadow_cast_x>=0)?'+'.$drop_shadow_cast_x:$drop_shadow_cast_x);
				$drop_shadow_distance_y = intval($layer['drop_shadow_distance_y'], 10);
				$drop_shadow_cast_y = intval($layer['drop_shadow_distance_y'], 10)*-1;
				$drop_shadow_distance_y = (($drop_shadow_distance_y>=0)?'+'.$drop_shadow_distance_y:$drop_shadow_distance_y);
				$drop_shadow_cast_y = (($drop_shadow_cast_y>=0)?'+'.$drop_shadow_cast_y:$drop_shadow_cast_y);
				$drop_shadow = <<< EOT
\( \
-pointsize $pointsize \
-background 'transparent' \
-fill $drop_shadow_color \
$font \
$kern \
label:$text \
-trim \
-gravity center \
-extent {$cX}x{$cYm} \
\( \
+clone \
-fill 'white' \
-colorize 100 \
\) \
-geometry $drop_shadow_distance_x$drop_shadow_distance_y \
-compose Dst_Out -composite \
\
$drop_shadow_opacity \
\) \
-gravity center \
-geometry $drop_shadow_cast_x$drop_shadow_cast_y \
-compose Over -composite
EOT;
			}

			if(!empty($layer['outer_glow'])) {
				$outer_glow_color = '#FFFFFF';
				if(!empty($layer['outer_glow_color'])) {
					$outer_glow_color = $layer['outer_glow_color'];
				}
				$outer_glow_color = escapeshellarg($outer_glow_color);
				$outer_glow_opacity = '';
				if(!empty($layer['outer_glow_opacity'])) {
					$outer_glow_opacity = floatval($layer['outer_glow_opacity']);
					$outer_glow_opacity = <<< EOT
-alpha set \
-channel a \
-evaluate \
multiply $outer_glow_opacity \
+channel
EOT;
				}

				$outer_glow_distance_x = intval($layer['outer_glow_distance_x'], 10);
				$outer_glow_cast_x = intval($layer['outer_glow_distance_x'], 10)*-1;
				$outer_glow_distance_x = (($outer_glow_distance_x>=0)?'+'.$outer_glow_distance_x:$outer_glow_distance_x);
				$outer_glow_cast_x = (($outer_glow_cast_x>=0)?'+'.$outer_glow_cast_x:$outer_glow_cast_x);
				$outer_glow_distance_y = intval($layer['outer_glow_distance_y'], 10);
				$outer_glow_cast_y = intval($layer['outer_glow_distance_y'], 10)*-1;
				$outer_glow_distance_y = (($outer_glow_distance_y>=0)?'+'.$outer_glow_distance_y:$outer_glow_distance_y);
				$outer_glow_cast_y = (($outer_glow_cast_y>=0)?'+'.$outer_glow_cast_y:$outer_glow_cast_y);
				$outer_glow = <<< EOT
\( \
-pointsize $pointsize \
-background 'transparent' \
-fill $outer_glow_color \
$font \
$kern \
label:$text \
-trim \
-gravity center \
-extent {$cX}x{$cYm} \
\( \
+clone \
-fill 'white' \
-colorize 100 \
\) \
-geometry $outer_glow_distance_x$outer_glow_distance_y \
-compose Dst_Out -composite \
\
$outer_glow_opacity \
\) \
-gravity center \
-geometry $outer_glow_cast_x$outer_glow_cast_y \
-compose Over -composite
EOT;
			}


			//tpt_dump($clipart);
			//tpt_dump($layout);
			$clp_x = max(min($cX, $cYm), floor(($cX - $metrics[$msgdata['pname']]['x'])/max(1, count($clipart))));
			$clpnames = array();
			$clpgrvt = array();
			$clpoffsigns = array();
			$msggrvt = '-gravity Center';
			if (!empty($layout['text_topbottom']) && (count($messages)>1)) {
				foreach ($clipart as $ctid => $clp) {
					$clpdata = $cpf_module->moduleData['id'][$ctid];
					$cmsg = $cpf_module->moduleData['id'][$clpdata['clipart_text_id']];

					if($tid == $cmsg['id']) {
						//tpt_dump('asd');
						//tpt_dump($clpdata['pname'], true);
						if(isset($layer[$clpdata['pname']])) {
							//tpt_dump('asd');
							//tpt_dump($layout, true);
							//tpt_dump($clpdata['pname'], true);
							$clpg = '-gravity East';
							$clpoffsign = '+';
							$msggrvt = '-gravity Center';
							if(!empty($layout['clipart_leftright'])) {
								$cXm -= ($clp_x);
								//tpt_dump($layer[$clpdata['pname']], true);
								if(empty($clpdata['orientation'])) {
									$clpg = '-gravity West';
									$clpoffsign = '-';
									$msggrvt = '-gravity Center';
								}
							}

							$clpoffsigns[$ctid][$layer[$clpdata['pname']]] = $clpoffsign;
							$clpgrvt[$ctid][$layer[$clpdata['pname']]] = $clpg;
							$clpnames[$ctid][$layer[$clpdata['pname']]] = $clipart_module->getClipartPath($vars, $layer[$clpdata['pname']]);
						}
					}


				}
			} else {
				foreach ($clipart as $ctid => $clp) {
					$clpdata = $cpf_module->moduleData['id'][$ctid];
					$cmsg = $cpf_module->moduleData['id'][$clpdata['clipart_text_id']];

					if($tid == $cmsg['id']) {
						//tpt_dump('asd');
						//tpt_dump($clpdata['pname'], true);
						if(isset($layer[$clpdata['pname']])) {
							//tpt_dump('asd');
							//tpt_dump($layout, true);
							//tpt_dump($clpdata['pname'], true);
							$clpg = '-gravity East';
							$clpoffsign = '+';
							$msggrvt = '-gravity Center';
							if(!empty($layout['clipart_leftright'])) {
								$cXm -= ($clp_x);
								//tpt_dump($layer[$clpdata['pname']], true);
								if(empty($clpdata['orientation'])) {
									$clpg = '-gravity West';
									$clpoffsign = '-';
									$msggrvt = '-gravity Center';
								}
							}

							$clpoffsigns[$ctid][$layer[$clpdata['pname']]] = $clpoffsign;
							$clpgrvt[$ctid][$layer[$clpdata['pname']]] = $clpg;
							$clpnames[$ctid][$layer[$clpdata['pname']]] = $clipart_module->getClipartPath($vars, $layer[$clpdata['pname']]);
						}
					}


				}
			}


			if(!empty($layout['clipart_leftright']) && (count($clpnames) > 1)) {
				$msggrvt = '-gravity Center';
			}

			//tpt_dump($clpnames);


			$clp_xx = $clp_x-5;
			$clp_yy = $clp_y-5;
			$clp = array();
			$cmetrics = array();
			if(!empty($clpnames)) {
				if(!empty($layout['clipart_leftright'])) {
					foreach($clpnames as $tid=>$clps) {
						foreach ($clps as $clpid => $c) {
							//$c'[{$clp_sq}x{$clp_sq}]' \
							$clpg = $clpgrvt[$tid][$clpid];
							$c_c = <<< EOT
				\( \
					-stroke none \
					-strokewidth 0 \
					$bg \
					$color \
					-trim \
					+repage \
					-density 1200 \
					-size x{$clp_y} \
					-resize {$clp_xx}x{$clp_yy} \
					$c \
				\)
EOT;
							$c_c2 = <<< EOT
			\( \
				-stroke none \
				-strokewidth 0 \
				$bg \
				$color \
				-trim \
				+repage \
				-density 1200 \
				-size x{$clp_y} \
				-resize {$clp_xx}x{$clp_yy} \
				$c \
			\)
EOT;
							$fsize = <<< EOT
{$bp}{$im_bin} \
-stroke none \
-strokewidth 0 \
$bg \
$color \
-trim \
+repage \
-density 1200 \
-size x{$clp_y} \
-resize {$clp_xx}x{$clp_yy} \
$c \
-format "%@" \
info:
EOT;
							if (!empty($_GET['debug_php'])) {
								tpt_dump($fsize);
							}
							$csize = self::exec_command($vars, $fsize, '', '', $s, 'clipart_metrics_' . $clpid, 1);
							//tpt_dump($csize);
							$cmetrics[$clpid] = preg_split('#\+|-#', $csize);
							//tpt_dump($cmetrics[$clpid]);
							$cmetrics[$clpid] = array_shift($cmetrics[$clpid]);
							//tpt_dump($cmetrics[$clpid]);
							$cmetrics[$clpid] = explode('x', $cmetrics[$clpid]);
							//tpt_dump($cmetrics[$clpid]);
							if (empty($cmetrics[$clpid][0]) || empty($cmetrics[$clpid][1])) {
								//tpt_dump('asd');
								//tpt_dump('asd', true);
								$c_c = <<< EOT
				\( \
					-stroke '#FFFFFF' \
					-strokewidth 1 \
					$bg \
					-trim \
					+repage \
					-density 1200 \
					-size x{$clp_y} \
					-resize {$clp_xx}x{$clp_yy} \
					$c \
				\)
EOT;
								$c_c2 = <<< EOT
			\( \
				-stroke '#FFFFFF' \
				-strokewidth 1 \
				$bg \
				-trim \
				+repage \
				-density 1200 \
				-size x{$clp_y} \
				-resize {$clp_xx}x{$clp_yy} \
				$c \
			\)
EOT;

								$fsize = <<< EOT
{$bp}{$im_bin} \
-stroke '#FFFFFF' \
-strokewidth 1 \
$bg \
-trim \
+repage \
-density 1200 \
-size x{$clp_y} \
-resize {$clp_xx}x{$clp_yy} \
$c \
-format "%@" \
info:
EOT;
								if (!empty($_GET['debug_php'])) {
									tpt_dump($fsize);
								}
								$csize = self::exec_command($vars, $fsize, '', '', $s, 'clipart_metrics2_' . $clpid, 1);
								//tpt_dump($csize);
								$cmetrics[$clpid] = preg_split('#\+|-#', $csize);
								//tpt_dump($cmetrics[$clpid]);
								$cmetrics[$clpid] = array_shift($cmetrics[$clpid]);
								$cmetrics[$clpid] = explode('x', $cmetrics[$clpid]);
							}

							$clpoffsign = $clpoffsigns[$tid][$clpid];
							$clpoffx = ceil($metrics[$msgdata['pname']]['x']/2);
							//if($clpoffsign == "-") {
								//tpt_dump($cmetrics[$clpid], true);
								$clpoffx += $cmetrics[$clpid][0];
							//}
							//$clpoffx = $metrics[$msgdata['pname']]['x'];
							//tpt_dump($cmetrics[$clpid][0]);
							//tpt_dump($cmetrics[$clpid][0], true);
							//tpt_dump($clpg, true);
							$clp[] = <<< EOT
	\( \
		-respect-parenthesis \
		-background transparent \
		-page {$clp_x}x{$clp_y} \
		\
		\( \
			-size {$clp_x}x{$clp_y} \
			xc:transparent \
			\( \
$c_c \
				\( \
				-size {$clp_x}x{$clp_y} \
				xc:'#FFFFFF' \
				\) \
				-compose SrcIn \
				-composite \
			\) \
			-gravity Center \
			-compose Over \
			-composite \
		\) \
		\
		\( \
		-clone 0 \
		-repage -1-1 \
		\) \
		\( \
		-clone 0 \
		-repage -1+0 \
		\) \
		\( \
		-clone 0 \
		-repage -1+1 \
		\) \
		\( \
		-clone 0 \
		-repage +0-1 \
		\) \
		\( \
		-clone 0 \
		-repage +0+0 \
		\) \
		\( \
		-clone 0 \
		-repage +0+1 \
		\) \
		\( \
		-clone 0 \
		-repage +1-1 \
		\) \
		\( \
		-clone 0 \
		-repage +1+0 \
		\) \
		\( \
		-clone 0 \
		-repage +1+1 \
		\) \
		-compose Over \
		-flatten \
		\( \
$c_c2 \
			\( \
			-size {$clp_x}x{$clp_y} \
			xc:white \
			\) \
			-compose SrcIn \
			-composite \
		\) \
		-gravity Center \
		-compose DstOut \
		-composite \
		-geometry {$clpoffsign}{$clpoffx}+0 \
	\) \
	-compose Over -composite
EOT;
						}
					}
				}
			}
			$clp = implode(' \\'."\n", $clp);
			//tpt_dump($clpnames, true);
			//tpt_dump($clipart, true);
			//tpt_dump($clp, true);
			$cXd = $cX*2;
			$cYmd = $cYm*2;


				$msgs[$msgdata['pname']] = <<< EOT
		\( \
			\( \
				-size {$cX}x{$cYm} \
				xc:transparent \
				\( \
					\( \
						-size {$cXm}x{$cYm} \
						xc:transparent \
						-pointsize $pointsize \
						-stroke '#FFFFFF' \
						$strokewidth \
						$bg \
						\
						-fill none \
						$font \
						$kern \
						label:$text \
						-gravity center \
						-compose Over -composite \
						$inner_shadow \
						$inner_glow \
						$drop_shadow \
						$outer_glow \
					\) \
					-trim \
				\) \
				$msggrvt \
				-compose Over -composite \
				$clp \
			\) \
			-trim \
			+repage \
			$resize \
		\) \
		$pgravity \
		-compose Over -composite
EOT;
		}
		//tpt_dump($metrics, true);

		if(count($msgs) > 1) {
			//$addlabel = '\\( ' . implode(' \\' . "\n" , $msgs) . ' \\)';
			$addlabel =  implode(' \\' . "\n" , $msgs) ;
		} else {
			$addlabel = implode($msgs);
		}


		$command = <<< EOT
{$bp}{$im_bin} \
-respect-parenthesis \
\( \
	\( \
		-size {$cX}x{$cY} \
		xc:transparent \
$addlabel \
	\) \
\) \

EOT;


		return $command;
	}
	*/


	static function c_led_effects(&$vars, $layer, &$out='', &$steps=array()) {
		$color_module = getModule($vars, 'BandColor');

		$cX = (!empty($layer['cX'])?intval($layer['cX'], 10):1);
		$cY = (!empty($layer['cY'])?intval($layer['cY'], 10):1);

		$cPL = (!empty($layer['cPL'])?intval($layer['cPL'], 10):0);
		$cPR = (!empty($layer['cPR'])?intval($layer['cPR'], 10):0);
		$cPT = (!empty($layer['cPT'])?intval($layer['cPT'], 10):0);
		$cPB = (!empty($layer['cPB'])?intval($layer['cPB'], 10):0);

		$bp = BIN_PATH;
		if(defined('ALT_BIN_PATH')) {
			$bp = ALT_BIN_PATH;
		}
		$im_bin = IMAGEMAGICK_BIN;

		//tpt_dump($imgpath, true);
		$size = '';
		$xc = '';
		$alpha = '';
		$compose = '';
		$composite = '';
		$opacity = '';

		$composite = '';
		//tpt_dump($layer['color']);
		//tpt_dump($layer['overlay']);

		/*
		if(!empty($layer['target']) && (strstr($layer['target'], '->') !== false)) {
			$target = explode('->', $layer['target']);

			if(!empty($target[1]) && ($target[1] == 'colordata') && !empty($target[2])) {
				$hex = $cprops['colordata'][$target[2]];
				//tpt_dump($target[2]);
				//tpt_dump($cprops['colordata'][$target[2]], true);
			}
		}
		*/



		$resize = '';
		if(!empty($layer['resize'])) {
			$resize = <<< EOT
-resize '{$cX}x{$cY}'
EOT;
		}



		$imgp = escapeshellarg(TPT_PREVIEWRESOURCES_DIR.DIRECTORY_SEPARATOR.'led-x-0-BG.png');
		$layer_0 = <<< EOT
$imgp
EOT;
		$imgp = escapeshellarg(TPT_PREVIEWRESOURCES_DIR.DIRECTORY_SEPARATOR.'led-x-1-Color.png');
		/*
		$layer_1 = <<< EOT
$imgp \
-compose Overlay \
-composite
EOT;
		*/
		$layer_1 = <<< EOT
EOT;

		$layer_2 = '';
		$imgp = escapeshellarg(TPT_PREVIEWRESOURCES_DIR.DIRECTORY_SEPARATOR.'led-x-4-Color_Dodge-Overlay.png');
		$layer_4 = <<< EOT
$imgp \
-compose Color_Dodge \
-composite
EOT;
		$layer_8 = '';
		$imgname = $layer['image'];
		if(true || !empty($layer['color'])) {
			$color = !empty($layer['color'])?$layer['color']:DEFAULT_BAND_COLOR_LED;
			$cprops = $color_module->getColorProps($vars, $color);
			//tpt_dump($cprops);

			$hex = $cprops['hex'];
			$ovlcolor = escapeshellarg('#'.$hex);
			$hex = $cprops['colordata']['led_hex'];
			$led_ovlcolor = escapeshellarg('#'.$hex);

			$imgp = escapeshellarg(TPT_PREVIEWRESOURCES_DIR.DIRECTORY_SEPARATOR.'led-x-1-Ov-Blend-Overlay.png');
			$layer_1 = <<< EOT
\( \
xc:$led_ovlcolor \
$imgp \
-compose CopyOpacity \
-composite \
\) \
-compose Overlay \
-composite \
\( \
xc:$led_ovlcolor \
$imgp \
-compose CopyOpacity \
-composite \
\) \
-compose blend \
-define compose:args=40x100 \
-composite
EOT;
			if(empty($cprops['transparent_case'])) {
				/*
				$layer_2_opacity = '';
				$layer_2_opacity = <<< EOT
	-alpha set \
	-channel a \
	-evaluate \
	set 0% \
	+channel
	EOT;
				*/
				$imgp = escapeshellarg(TPT_PREVIEWRESOURCES_DIR . DIRECTORY_SEPARATOR . 'led-x-2-Over-Overlay.png');
				$layer_2 = <<< EOT
\( \
xc:$ovlcolor \
$imgp \
-compose CopyOpacity \
-composite \
\) \
-compose Over \
-composite
EOT;

			} else {
				//tpt_dump('asdasddsfgsdhgfsdhgisdhf');
				$imgname = explode('.', $imgname);
				array_pop($imgname);
				$imgname = implode($imgname).'-transparentcase.png';

				$imgp = escapeshellarg(TPT_PREVIEWRESOURCES_DIR.DIRECTORY_SEPARATOR.'led-x-8-Lighten-Overlay.png');
				$layer_8 = <<< EOT
\( \
xc:$led_ovlcolor \
$imgp \
-compose CopyOpacity \
-composite \
\) \
-compose Lighten \
-composite
EOT;
			}


			$imgp = escapeshellarg(TPT_PREVIEWRESOURCES_DIR.DIRECTORY_SEPARATOR.'led-x-4-Color_Dodge-Overlay.png');
			$layer_4 = <<< EOT
\( \
xc:$led_ovlcolor \
$imgp \
-compose CopyOpacity \
-composite \
$imgp \
-compose Lighten \
-composite \
\) \
-compose PinLight \
-composite
EOT;
		} else {
			$imgname = explode('.', $imgname);
			array_pop($imgname);
			$imgname = implode($imgname).'-transparentcase.png';
		}
		$imgp = escapeshellarg(TPT_PREVIEWRESOURCES_DIR.DIRECTORY_SEPARATOR.$imgname);
		$layer_3 = <<< EOT
$imgp \
-compose Over \
-composite
EOT;

		//$cprops = $color_module->getColorProps($vars, (!empty($layer['color'])?$layer['color']:0));
		//tpt_dump($cprops, true);

		$imgp = escapeshellarg(TPT_PREVIEWRESOURCES_DIR.DIRECTORY_SEPARATOR.'led-x-5-Divide.png');
		$layer_5 = <<< EOT
$imgp \
-compose SoftLight \
-composite
EOT;
		$imgp = escapeshellarg(TPT_PREVIEWRESOURCES_DIR.DIRECTORY_SEPARATOR.'led-x-6-Color_Burn.png');
		$layer_6 = <<< EOT
$imgp \
-compose Color_Burn \
-composite
EOT;
		$imgp = escapeshellarg(TPT_PREVIEWRESOURCES_DIR.DIRECTORY_SEPARATOR.'led-x-7-Over.png');
		$layer_7 = <<< EOT
$imgp \
-compose Over \
-composite
EOT;

		$size = '-size '.$cX.'x'.$cY;
		$command = <<< EOT
{$bp}{$im_bin} \
{$size} \
{$layer_0} \
{$layer_1} \
{$layer_2} \
{$layer_3} \
{$layer_5} \
{$layer_6} \
{$layer_7} \
{$layer_8} \
EOT;
		if(empty($layer['led_glow'])) {
			$command = <<< EOT
{$bp}{$im_bin} \
{$size} \
{$layer_0} \
{$layer_2} \
{$layer_3} \
EOT;
		}

		return $command;
	}

	static function c_image(&$vars, $layer, &$out='', &$steps=array()) {
		$color_module = getModule($vars, 'BandColor');

		$cX = (!empty($layer['cX'])?intval($layer['cX'], 10):1);
		$cY = (!empty($layer['cY'])?intval($layer['cY'], 10):1);

		$cPL = (!empty($layer['cPL'])?intval($layer['cPL'], 10):0);
		$cPR = (!empty($layer['cPR'])?intval($layer['cPR'], 10):0);
		$cPT = (!empty($layer['cPT'])?intval($layer['cPT'], 10):0);
		$cPB = (!empty($layer['cPB'])?intval($layer['cPB'], 10):0);

		$bp = BIN_PATH;
		if(defined('ALT_BIN_PATH')) {
			$bp = ALT_BIN_PATH;
		}
		$im_bin = IMAGEMAGICK_BIN;

		$imgname = $layer['image'];
		if(!empty($layer['led_glow'])) {
			$cprops = $color_module->getColorProps($vars, (!empty($layer['color'])?$layer['color']:0));
			//tpt_dump($cprops, true);
			if((empty($layer['color']) || !empty($cprops['transparent_case']))) {
				$imgname = explode('.', $imgname);
				array_pop($imgname);
				$imgname = implode($imgname).'-transparentcase.png';
			}

		}
		$imgp = escapeshellarg(TPT_PREVIEWRESOURCES_DIR.DIRECTORY_SEPARATOR.$imgname);
		$imgpath = <<< EOT
$imgp
EOT;

		//tpt_dump($imgpath, true);
		$size = '';
		$xc = '';
		$tile = '';
		$alpha = '';
		$compose = '';
		$composite = '';
		$opacity = '';
		if(!empty($layer['tile'])) {
			$size = <<< EOT
-size {$cX}x{$cY}
EOT;
			$xc = <<< EOT
xc:transparent
EOT;
			$imgpath = <<< EOT
tile:$imgp
EOT;
			$composite = <<< EOT
-composite
EOT;
		}

		$composite = '';
		//tpt_dump($layer['color']);
		//tpt_dump($layer['overlay']);
		if(!empty($layer['overlay'])) {
			if(empty($layer['color']) && (empty($layer['overlay_color']) || ($layer['overlay_color'] == 'transparent') || ($layer['overlay_color'] == 'none'))) {
				$opacity = <<< EOT
-alpha set \
-channel a \
-evaluate \
set 0% \
+channel
EOT;
			} else {
				$ovlcolor = escapeshellarg($layer['overlay_color']);
				if(!empty($layer['color'])) {
					$cprops = $color_module->getColorProps($vars, $layer['color']);

					$hex = $cprops['hex'];
					if(!empty($layer['target']) && (strstr($layer['target'], '->') !== false)) {
						$target = explode('->', $layer['target']);

						if(!empty($target[1]) && ($target[1] == 'colordata') && !empty($target[2])) {
							$hex = $cprops['colordata'][$target[2]];
							//tpt_dump($target[2]);
							//tpt_dump($cprops['colordata'][$target[2]], true);
						}
					}

					//tpt_dump($hex, true);
					//tpt_dump($cprops);
					if($hex != 'transparent') {
						$ovlcolor = escapeshellarg('#' . $hex);

						$size = <<< EOT
-size {$cX}x{$cY}
EOT;

						$xc = <<< EOT
xc:'$ovlcolor'
EOT;
						$compose = <<< EOT
$alpha \
-compose CopyOpacity
EOT;
						$composite = <<< EOT
-composite
EOT;
					} else {
						$opacity = <<< EOT
-alpha set \
-channel a \
-evaluate \
set 0% \
+channel
EOT;
					}
				} else {

					$size = <<< EOT
-size {$cX}x{$cY}
EOT;

					$xc = <<< EOT
xc:'$ovlcolor'
EOT;
					$compose = <<< EOT
$alpha \
-compose CopyOpacity
EOT;
					$composite = <<< EOT
-composite
EOT;
				}
			}
		}

		$resize = '';
		if(!empty($layer['resize'])) {
			$resize = <<< EOT
-resize '{$cX}x{$cY}'
EOT;
		}

		$command = <<< EOT
{$bp}{$im_bin} \
{$size} \
{$xc} \
{$imgpath} \
{$compose} \
{$resize} \
{$composite} \
{$opacity} \

EOT;

		return $command;
	}

	static function c_bandoutline(&$vars, $layer, &$out='', &$steps=array()) {
		$data_module = getModule($vars, 'BandData');
		$color_module = getModule($vars, 'BandColor');

		$type = $layer['type'];
		$style = $layer['style'];

		$bp = BIN_PATH;
		if(defined('ALT_BIN_PATH')) {
			$bp = ALT_BIN_PATH;
		}
		$im_bin = IMAGEMAGICK_BIN;

		$data = $data_module->typeStyle[$type][$style];
		$pdir = $data['preview_folder'];
		$imgname = 'plain.png';
		$cprops = $color_module->getColorProps($vars, (!empty($layer['color'])?$layer['color']:0));
		//tpt_dump($cprops, true);
		if(!empty($data['has_case']) && (empty($layer['color']) || !empty($cprops['transparent_case']))) {
			$imgname = 'plain-transparentcase.png';
		}
		$imgpath = escapeshellarg(TPT_IMAGES_DIR.DIRECTORY_SEPARATOR.'preview'.DIRECTORY_SEPARATOR.$pdir.DIRECTORY_SEPARATOR.$imgname);

		$command = <<< EOT
{$bp}{$im_bin} \
$imgpath \

EOT;

		return $command;
	}


	static function g_fill(&$vars, $layer, &$steps=array(), $step='', $format='png') {

		$color_module = getModule($vars, 'BandColor');
		$cprops = $color_module->getColorProps($vars, $layer['color']);
		//tpt_dump($cprops);
		$out = '';

		if($cprops['colortypename'] == 'swirl') {
			$out = self::g_swirl($vars, $layer, $steps);
		} else if($cprops['colortypename'] == 'segmented') {
			$out = self::g_segmented($vars, $layer, $steps);
		} else {


			$cX = (!empty($layer['cX']) ? intval($layer['cX'], 10) : 1);
			$cY = (!empty($layer['cY']) ? intval($layer['cY'], 10) : 1);

			$cPL = (!empty($layer['cPL']) ? intval($layer['cPL'], 10) : 0);
			$cPR = (!empty($layer['cPR']) ? intval($layer['cPR'], 10) : 0);
			$cPT = (!empty($layer['cPT']) ? intval($layer['cPT'], 10) : 0);
			$cPB = (!empty($layer['cPB']) ? intval($layer['cPB'], 10) : 0);

			$bp = BIN_PATH;
			if(defined('ALT_BIN_PATH')) {
				$bp = ALT_BIN_PATH;
			}
		$im_bin = IMAGEMAGICK_BIN;

			$color = (!empty($layer['color']) ? escapeshellarg($layer['color']) : 'transparent');
			if (strstr($layer['color'], ':')) {
				$cprops = $color_module->getColorProps($vars, $layer['color']);
				$color = (!empty($cprops['hex']) ? escapeshellarg('#' . $cprops['hex']) : 'transparent');
			}
			//$opacity = $layer['opacity'];

			$command = <<< EOT
{$bp}{$im_bin} \
-size '{$cX}x{$cY}' \
xc:'$color' \

EOT;

			$out = self::exec_command($vars, $command, $format, '', $steps, __FUNCTION__.' '.$step);
		}

		if(!empty($cprops['glitter'])) {
			$gfile = 'glitter.png';
			if($cprops['glitter'] == 2) {
				$gfile = 'mc-glitter.png';
			}
			$layer['image'] = 'preview'.DIRECTORY_SEPARATOR.'glitter'.DIRECTORY_SEPARATOR.$gfile;
			$out = self::o_tileoverlay($vars, $layer, $out, $steps);
		}

		return $out;
	}



    static function g_flat(&$vars, $layer, &$steps=array(), $step='', $format='png') {

        /*
        $color_module = getModule($vars, 'BandColor');
        $cprops = $color_module->getColorProps($vars, $layer['color']);
        //tpt_dump($cprops);
        $out = '';

        if($cprops['colortypename'] == 'swirl') {
            $out = self::g_swirl($vars, $layer, $steps);
        } else if($cprops['colortypename'] == 'segmented') {
            $out = self::g_segmented($vars, $layer, $steps);
        } else {


            $cX = (!empty($layer['cX']) ? intval($layer['cX'], 10) : 1);
            $cY = (!empty($layer['cY']) ? intval($layer['cY'], 10) : 1);

            $cPL = (!empty($layer['cPL']) ? intval($layer['cPL'], 10) : 0);
            $cPR = (!empty($layer['cPR']) ? intval($layer['cPR'], 10) : 0);
            $cPT = (!empty($layer['cPT']) ? intval($layer['cPT'], 10) : 0);
            $cPB = (!empty($layer['cPB']) ? intval($layer['cPB'], 10) : 0);

            $bp = BIN_PATH;
		$im_bin = IMAGEMAGICK_BIN;

            $color = (!empty($layer['color']) ? escapeshellarg($layer['color']) : 'transparent');
            if (strstr($layer['color'], ':')) {
                $cprops = $color_module->getColorProps($vars, $layer['color']);
                $color = (!empty($cprops['hex']) ? escapeshellarg('#' . $cprops['hex']) : 'transparent');
            }
            //$opacity = $layer['opacity'];

            $command = <<< EOT
{$bp}{$im_bin} \
-size '{$cX}x{$cY}' \
xc:'$color' \

EOT;


            $out = self::exec_command($vars, $command, $format, '', $steps, __FUNCTION__.' '.$step);
        }
        */

        $data_module = getModule($vars, 'BandData');
        $types_module = getModule($vars, 'BandType');
        $styles_module = getModule($vars, 'BandStyle');
        $cpf_module = getModule($vars, 'CustomProductField');
        $fields = $cpf_module->moduleData['id'];
        $cpfsname = $cpf_module->moduleData['pname'];
        $layers_module = getModule($vars, 'PreviewLayer');
        $layers = $layers_module->moduleData['id'];

        $tpt_imagesurl = TPT_IMAGES_URL;

        $html = '';

        /*
        if(empty($pgconf)) {
            return '';
        }
        */

        /*
        $input = array_intersect_key($pgconf, $cpfspg);
        $_input = array();
        foreach($input as $name=>$value) {
            $parname = $cpfspg[$name]['pname'];
            $_input[$parname] = $$parname = $value;
        }
        $input = $_input;
        */

        /*
        $pgType = 2;
        $pgStyle = 5;
        */

        $input = $layer;
        $options = array();
        extract(array_intersect_key($input, $cpfsname), EXTR_OVERWRITE);
        $type = $layer['type'];
        $style = $layer['style'];
        //tpt_dump($type);
        //tpt_dump($style);

        $bdata = $data_module->typeStyle[$type][$style];

        $bg = (empty($bdata['clearband_layer'])?'background: transparent none;':'background: transparent url('.$tpt_imagesurl.'/clearband.png) repeat scroll 0 0;');

        $blayers = explode(',', $bdata['preview_layers']);
        $blayers = array_combine($blayers, $blayers);
        $layers = array_intersect_key($layers, $blayers);

        $pgid = (isset($pgconf['pgid'])?$pgconf['pgid']:'');

        $imgs = array();
        foreach($layers as $layer) {
            /*
            if($layer['layertype'] == 'bandoutline') {
                $layer['type'] = $type;
                $layer['style'] = $style;
            }
            */
            if(!empty($layer['preview_params_ids'])) {
                $params = explode(',', $layer['preview_params_ids']);
                //tpt_dump($layer['preview_params_ids']);
                //tpt_dump($input);
                foreach($params as $parid) {
                    $fld = $fields[$parid];
                    $fldname = $fld['pname'];
                    if(isset($input[$fldname])) {
                        if(!empty($fld['validateactivevalue'])) {
                            $module = getModule($vars, $fld['validateactivevalue_module']);
                            $layer[$fldname] = $module->getActiveItem($vars, $input, $options);
                        } else {
                            $layer[$fldname] = $input[$fldname];
                        }
                    }
                }
                //tpt_dump($layer);
            }
            $ncparams = array();
            if(!empty($layer['nullcheck_preview_params_ids'])) {
                $ncps = explode('|', $layer['nullcheck_preview_params_ids']);
                //tpt_dump($ncps);
                foreach($ncps as $ncp) {
                    $ncparam = explode(':', $ncp);
                    if(!empty($fields[$ncparam[0]])) {
                        $ncparam[1] = explode(',', $ncparam[1]);
                        foreach($ncparam[1] as $ncpfid) {
                            if(isset($input[$fields[$ncpfid]['pname']])) {
                                $layer[$fields[$ncparam[0]]['pname']] = 1;
                                break;
                            }
                        }
                    }
                }
            }
            $imgs[] = self::createLayer($vars, $layer);
        }

        /*
        $imgs[] = self::createImageHTML($vars, array(
            'layertype'=>'image',
            'cX'=>738,
            'cY'=>114,
            'image'=>'clearband.png',
            'tile'=>1,
        ));
        */
        /*
        $imgs[] = self::createImageHTML($vars, array(
            'layertype'=>'bandoutline',
            'type'=>$type,
            'style'=>$style
        ));
        */

        //$imgs = implode('', $imgs);



        return self::compose($vars, $imgs);
    }




	static function c_transparent(&$vars, $layer, &$out='', &$steps=array()) {
		$cX = (!empty($layer['cX'])?intval($layer['cX'], 10):1);
		$cY = (!empty($layer['cY'])?intval($layer['cY'], 10):1);

		$cPL = (!empty($layer['cPL'])?intval($layer['cPL'], 10):0);
		$cPR = (!empty($layer['cPR'])?intval($layer['cPR'], 10):0);
		$cPT = (!empty($layer['cPT'])?intval($layer['cPT'], 10):0);
		$cPB = (!empty($layer['cPB'])?intval($layer['cPB'], 10):0);

		$bp = BIN_PATH;
		if(defined('ALT_BIN_PATH')) {
			$bp = ALT_BIN_PATH;
		}
		$im_bin = IMAGEMAGICK_BIN;

		$command = <<< EOT
{$bp}{$im_bin} \
-size '{$cX}x{$cY}' \
xc:'none' \

EOT;
		return $command;
	}

	static function g_transparent(&$vars, $layer, &$steps=array(), $step='', $format='png') {
		$command = self::c_transparent($vars, $layer, $out, $steps);

		return self::exec_command($vars, $command, $format, '', $steps, __FUNCTION__.' '.$step);
	}

	static function g_segmented(&$vars, $layer, &$steps=array()) {
		$color_module = getModule($vars, 'BandColor');

		$bp = BIN_PATH;
		if(defined('ALT_BIN_PATH')) {
			$bp = ALT_BIN_PATH;
		}

		//$commands = array();
		$BandBG = array();

		$cX = (!empty($layer['cX'])?intval($layer['cX'], 10):1);
		$cY = (!empty($layer['cY'])?intval($layer['cY'], 10):1);

		$cPL = (!empty($layer['cPL'])?intval($layer['cPL'], 10):0);
		$cPR = (!empty($layer['cPR'])?intval($layer['cPR'], 10):0);
		$cPT = (!empty($layer['cPT'])?intval($layer['cPT'], 10):0);
		$cPB = (!empty($layer['cPB'])?intval($layer['cPB'], 10):0);

		$color = (!empty($layer['color'])?$layer['color']:'transparent');
		if(strstr($layer['color'], ':')) {
			$cprops = $color_module->getColorProps($vars, $layer['color']);
			$color = (!empty($cprops['hexarray'])?$cprops['hexarray']:'transparent');
			$color = array_reverse($color);
		}

		if($color !== false) {
			if(count($color) == 1) {
				$IMCommand = $bp.IMAGEMAGICK_BIN;
				$IMCommand .= '	-resize '.$cX.'x'.$cY.'! ';
				$IMCommand .= '	'.TPT_CACHE_DIR.DIRECTORY_SEPARATOR.'segmented'.DIRECTORY_SEPARATOR.'segmented_mask.png ';
				$IMCommand .= '	 -geometry -200-0 ';
				$IMCommand .= '	png:- ';
				self::convert($vars, $steps, 'segmentMask', $IMCommand, 2);
				//$commands['segmentMask'] = $IMCommand;
				//return $steps['segmentMask'];

				$IMCommand = $bp.IMAGEMAGICK_BIN;
				$IMCommand .= '	-size '.$cX.'x'.$cY.' ';
				$IMCommand .= '	xc:\'#'.reset($color).'\' ';
				$IMCommand .= '	png:- ';

				//$IMCommand .= '	-alpha Off ';
				$IMCommand .= '	-compose CopyOpacity ';
				$IMCommand .= '	-composite ';
				$IMCommand .= '	png:- ';
				self::convert($vars, $steps, 'segment', $IMCommand, 3, $steps['segmentMask']);
				//$commands['segment'] = $IMCommand;
				//var_dump($steps['errors']['segment']);die();
				//var_dump($options['segmentedColor']);die();
				//header('Content-type: image/png');
				//return $steps['segment'];
				$BandBG[] = $steps['segment'];
			} else {
				$i=0;
				//var_dump($options['segmentedColor']);die();
				foreach($color as $key=>$segment) {
					if($i == 0) {
						$IMCommand = $bp.IMAGEMAGICK_BIN;
						$IMCommand .= '	-size '.$cX.'x'.$cY.' ';
						$IMCommand .= '	xc:\'#'.$segment.'\' ';
						$IMCommand .= '	png:- ';
						self::convert($vars, $steps, 'segment'.$key, $IMCommand, 2);
						//$commands['segment'.$key] = $IMCommand;
					} else {
						$IMCommand = $bp.IMAGEMAGICK_BIN;
						$resVal = ($cX-round($cX/count($color))*($i));
						/*
						if(in_array($options['bandType'], array(32, 33))) {
							if($i==1) {
								$resVal += 35;
							} else if($i==2) {
								$resVal += 5;
							}
						} else if(in_array($options['bandType'], array(31))) {
							if($i==1) {
								$resVal -= 21;
							} else if($i==2) {
								$resVal += 55;
							}
						}
						*/
						$IMCommand .= '	-resize '.$resVal.'x'.$cY.'! ';

						$IMCommand .= '	'.TPT_CACHE_DIR.DIRECTORY_SEPARATOR.'segmented'.DIRECTORY_SEPARATOR.'segmented_mask.png ';
						$IMCommand .= '	png:- ';
						self::convert($vars, $steps, 'segmentMask'.$key, $IMCommand, 2);
						//$commands['segmentMask'.$key] = $IMCommand;
						//header('Content-type: image/png');
						//return $steps['segmentMask'];

						$IMCommand = $bp.IMAGEMAGICK_BIN;
						$IMCommand .= '	-size '.$cX.'x'.$cY.' ';
						$IMCommand .= '	xc:\'#'.$segment.'\' ';
						//$IMCommand .= '	 -geometry -100-100 ';
						$IMCommand .= '	png:- ';
						//$IMCommand .= '	 -region -0-300 ';
						//$IMCommand .= '	 -gravity Center ';
						//$IMCommand .= '	-alpha Off ';
						$IMCommand .= '	-compose CopyOpacity ';
						$IMCommand .= '	-composite ';
						$IMCommand .= '	png:- ';
						self::convert($vars, $steps, 'segment'.$key, $IMCommand, 3, $steps['segmentMask'.$key]);
						//$commands['segment'.$key] = $IMCommand;
						//var_dump($steps['errors']['segment']);die();
						//var_dump($options['segmentedColor']);die();
						//if($key==2)
						//header('Content-type: image/png');
						//return $steps['segment'.$key];
					}
					$BandBG[] = $steps['segment'.$key];

					$i++;
				}


				/*
				$swStepsOut = '';

				reset($color);
				$swStepsOut = $steps['segment'.key($color)];
				$IMCommand = BIN_PATH.IMAGEMAGICK_BIN;
				$IMCommand .= '	png:- ';
				$IMCommand .= '	-gravity East ';
				$i=0;
				foreach($color as $key=>$segment) {
					if($i!=0) {
						$IMCommand .= '	png:- ';
						$IMCommand .= '	-composite ';
						$swStepsOut .= $steps['segment'.$key];
					}
					$i++;
				}
				$IMCommand .= '	png:- ';
				self::convert($vars, $steps, 'segment', $IMCommand, 3, $swStepsOut);
				*/

				//var_dump($IMCommand);die();
				//header('Content-type: image/png');
				//return $steps['segment'];
			}

		} else {
			$BandBG[] = self::g_transparent($vars, $layer);
		}

		return self::compose($vars, $BandBG);
	}
	static function g_swirl(&$vars, $layer, &$steps=array()) {
		$color_module = getModule($vars, 'BandColor');

		$bp = BIN_PATH;
		if(defined('ALT_BIN_PATH')) {
			$bp = ALT_BIN_PATH;
		}

		//$commands = array();
		$BandBG = array();

		$cX = (!empty($layer['cX'])?intval($layer['cX'], 10):1);
		$cY = (!empty($layer['cY'])?intval($layer['cY'], 10):1);

		$cPL = (!empty($layer['cPL'])?intval($layer['cPL'], 10):0);
		$cPR = (!empty($layer['cPR'])?intval($layer['cPR'], 10):0);
		$cPT = (!empty($layer['cPT'])?intval($layer['cPT'], 10):0);
		$cPB = (!empty($layer['cPB'])?intval($layer['cPB'], 10):0);

		$color = (!empty($layer['color'])?$layer['color']:'transparent');
		if(strstr($layer['color'], ':')) {
			$cprops = $color_module->getColorProps($vars, $layer['color']);
			$color = (!empty($cprops['hexarray'])?$cprops['hexarray']:'transparent');
		}
		//tpt_dump($color);

		if(!empty($color) &&  is_array($color)) {
			if(count($color) == 1) {
				$IMCommand = $bp.IMAGEMAGICK_BIN;
				$IMCommand .= '	-resize '.$cX.'x'.$cY.'! ';
				$IMCommand .= '	'.TPT_CACHE_DIR.DIRECTORY_SEPARATOR.'swirl'.DIRECTORY_SEPARATOR.'swirl'.key($color).'.png ';
				$IMCommand .= '	png:- ';
				self::convert($vars, $steps, 'SwirlMask', $IMCommand, 2);
				//return $steps['SwirlMask'];

				$IMCommand = $bp.IMAGEMAGICK_BIN;
				$IMCommand .= '	-size '.$cX.'x'.$cY.' ';
				$IMCommand .= '	xc:\'#'.reset($color).'\' ';
				$IMCommand .= '	png:- ';

				//$IMCommand .= '	-alpha Off ';
				$IMCommand .= '	-compose CopyOpacity ';
				$IMCommand .= '	-composite ';
				$IMCommand .= '	png:- ';
				self::convert($vars, $steps, 'Swirl', $IMCommand, 3, $steps['SwirlMask']);
				//$commands['Swirl'] = $IMCommand;
				//var_dump($steps['errors']['Swirl']);die();
				//var_dump($options['swirlColor']);die();
				//header('Content-type: image/png');
				//return $steps['Swirl'];
				$BandBG[] = $steps['Swirl'];
			} else {
				$i=0;//die();
				foreach($color as $key=>$swirl) {

					if($i == 0) {
						$IMCommand = $bp.IMAGEMAGICK_BIN;
						$IMCommand .= '	-size '.$cX.'x'.$cY.' ';
						$IMCommand .= '	xc:\'#'.$swirl.'\' ';
						$IMCommand .= '	png:- ';
						self::convert($vars, $steps, 'Swirl'.$key, $IMCommand, 2);
						//$commands['Swirl'.$key] = $IMCommand;
					} else {
						$IMCommand = $bp.IMAGEMAGICK_BIN;
						$IMCommand .= '	-resize '.$cX.'x'.$cY.'! ';
						$IMCommand .= '	'.TPT_CACHE_DIR.DIRECTORY_SEPARATOR.'swirl'.DIRECTORY_SEPARATOR.'swirl'.$key.'.png ';
						$IMCommand .= '	png:- ';
						self::convert($vars, $steps, 'SwirlMask'.$key, $IMCommand, 2);
						//$commands['SwirlMask'.$key] = $IMCommand;
						//return $steps['SwirlMask'];

						$IMCommand = $bp.IMAGEMAGICK_BIN;
						$IMCommand .= '	-size '.$cX.'x'.$cY.' ';
						$IMCommand .= '	xc:\'#'.$swirl.'\' ';
						$IMCommand .= '	png:- ';

						//$IMCommand .= '	-alpha Off ';
						$IMCommand .= '	-compose CopyOpacity ';
						$IMCommand .= '	-composite ';
						$IMCommand .= '	png:- ';
						self::convert($vars, $steps, 'Swirl'.$key, $IMCommand, 3, $steps['SwirlMask'.$key]);
						//$commands['Swirl'.$key] = $IMCommand;
						//var_dump($steps['errors']['Swirl']);die();
						//var_dump($options['swirlColor']);die();
						//if($key==2)
						//header('Content-type: image/png');
						//return $steps['Swirl'.$key];
					}

					$BandBG[] = $steps['Swirl'.$key];
					$i++;

					/*
					if($i == 6) {
					header('Content-type: image/'.$options['format']);
					echo $steps['Swirl'.($key)];

					}
					*/
				}


				/*
				$swStepsOut = '';

				reset($color);
				$swStepsOut = $steps['Swirl'.key($color)];
				$IMCommand = BIN_PATH.IMAGEMAGICK_BIN;
				$IMCommand .= '	png:- ';
				$i=0;
				foreach($color as $key=>$swirl) {
					if($i!=0) {
						$IMCommand .= '	png:- ';
						$IMCommand .= '	-composite ';
						$swStepsOut .= $steps['Swirl'.$key];
					}
					$i++;
				}
				$IMCommand .= '	png:- ';
				self::convert($vars, $steps, 'Swirl', $IMCommand, 3, $swStepsOut);
				*/
				//var_dump($IMCommand);die();
				//header('Content-type: image/png');
				//return $steps['Swirl'];
			}

			//tpt_dump($key);

		} else {
			$BandBG[] = self::g_transparent($vars, $layer);
		}

		//tpt_dump($BandBG);
		//return $BandBG[0];

		return self::compose($vars, $BandBG);
	}

	static function updateLabelLayerBoundaries(&$vars, $layer) {
		$messages_module = getModule($vars, 'BandMessage');
		$messages = $messages_module->moduleData['pname'];
		$message = $messages[$layer['target']];

		$layout = (!empty($layer['layout'])?intval($layer['layout'], 10):1);

		if($layout == 1) {
			$padtop = 0;
			$padbottom = 0;
			$padleft = 0;
			$padright = 0;
			foreach($messages as $msg=>$msgdata) {
				if(isset($layer[$msg])) {
					if(($msg != $layer['target']) && ($msgdata['inside'] == $message['inside'])) {
						if($message['back'] != $msgdata['back']) {
							if (empty($message['back']) && !empty($msgdata['back'])) {
								$padright = 1;
							} else if (!empty($message['back']) && empty($msgdata['back'])) {
								$padleft = 1;
							}
						} else {
							if(empty($message['line2']) && !empty($msgdata['line2'])) {
								$padbottom = 1;
							} else if(!empty($message['line2']) && empty($msgdata['line2'])) {
								$padtop = 1;
							}
						}
					}
				}
			}
			//tpt_dump($padtop);
			//tpt_dump($padbottom);
			//tpt_dump($padleft);
			//tpt_dump($padright);
			//tpt_dump($cPT);
			//tpt_dump($cPB);
			if($padtop) {
				$cYex = $layer['cY'] - floor($layer['cY']/2);
				$layer['cY'] = $layer['cY'] - $cYex+5;
				$layer['cPT'] += $cYex;
			} else if($padbottom) {
				$cYex = $layer['cY'] - floor($layer['cY']/2);
				$layer['cY'] = $layer['cY'] - $cYex;
				$layer['cPB'] += $cYex;
			}
			//tpt_dump($cPT);
			//tpt_dump($cPB);

			if($padleft) {
				$cXex = $layer['cX'] - floor($layer['cX']/2);
				$layer['cX'] = $layer['cX'] - ($cXex+5);
				$layer['cPL'] += ($cXex+5);
			} else if($padright) {
				$cXex = $layer['cX'] - floor($layer['cX']/2);
				$layer['cX'] = $layer['cX'] - ($cXex+5);
				$layer['cPR'] += ($cXex+5);
			}
		}

		return $layer;
	}

	static function c_definedarea(&$vars, $layer, $in='') {
		$bp = BIN_PATH;
		if(defined('ALT_BIN_PATH')) {
			$bp = ALT_BIN_PATH;
		}
		$im_bin = IMAGEMAGICK_BIN;

		$cX = (!empty($layer['cX'])?intval($layer['cX'], 10):1);
		$cY = (!empty($layer['cY'])?intval($layer['cY'], 10):1);

		$cPL = (!empty($layer['cPL'])?intval($layer['cPL'], 10):0);
		$cPR = (!empty($layer['cPR'])?intval($layer['cPR'], 10):0);
		$cPT = (!empty($layer['cPT'])?intval($layer['cPT'], 10):0);
		$cPB = (!empty($layer['cPB'])?intval($layer['cPB'], 10):0);



		$command = <<< EOT
-background transparent \
-gravity center \
-extent {$cX}x{$cY} \

EOT;
		if(!empty($in)) {
			$command = <<< EOT
$in $command
EOT;
		}

		return $command;
	}
	static function o_definedarea(&$vars, $layer, $in='', &$out='', &$steps=array(), $step='', $format='png') {
		$bp = BIN_PATH;
		if(defined('ALT_BIN_PATH')) {
			$bp = ALT_BIN_PATH;
		}
		$im_bin = IMAGEMAGICK_BIN;

		$cX = (!empty($layer['cX'])?intval($layer['cX'], 10):1);
		$cY = (!empty($layer['cY'])?intval($layer['cY'], 10):1);

		$cPL = (!empty($layer['cPL'])?intval($layer['cPL'], 10):0);
		$cPR = (!empty($layer['cPR'])?intval($layer['cPR'], 10):0);
		$cPT = (!empty($layer['cPT'])?intval($layer['cPT'], 10):0);
		$cPB = (!empty($layer['cPB'])?intval($layer['cPB'], 10):0);

		$command = <<< EOT
{$bp}{$im_bin} \
- \
-background transparent \
-gravity center \
-extent {$cX}x{$cY} \

EOT;
		$out = self::exec_command($vars, $command, $format, $in, $steps, __FUNCTION__.' '.$step);

		return $command;
	}

	static function c_resize(&$vars, $layer, $in='') {
		$bp = BIN_PATH;
		if(defined('ALT_BIN_PATH')) {
			$bp = ALT_BIN_PATH;
		}
		$im_bin = IMAGEMAGICK_BIN;

		$cX = (!empty($layer['cX'])?intval($layer['cX'], 10):1);
		$cY = (!empty($layer['cY'])?intval($layer['cY'], 10):1);

		$cPL = (!empty($layer['cPL'])?intval($layer['cPL'], 10):0);
		$cPR = (!empty($layer['cPR'])?intval($layer['cPR'], 10):0);
		$cPT = (!empty($layer['cPT'])?intval($layer['cPT'], 10):0);
		$cPB = (!empty($layer['cPB'])?intval($layer['cPB'], 10):0);



		$command = <<< EOT
 -adaptive-resize {$cX}x{$cY}\

EOT;
		if(!empty($in)) {
			$command = <<< EOT
$in $command
EOT;
		}

		return $command;
	}
	static function o_resize(&$vars, $layer, $in='', &$out='', &$steps=array(), $step='', $format='png') {
		$bp = BIN_PATH;
		if(defined('ALT_BIN_PATH')) {
			$bp = ALT_BIN_PATH;
		}
		$im_bin = IMAGEMAGICK_BIN;

		$cX = (!empty($layer['cX'])?intval($layer['cX'], 10):1);
		$cY = (!empty($layer['cY'])?intval($layer['cY'], 10):1);

		$cPL = (!empty($layer['cPL'])?intval($layer['cPL'], 10):0);
		$cPR = (!empty($layer['cPR'])?intval($layer['cPR'], 10):0);
		$cPT = (!empty($layer['cPT'])?intval($layer['cPT'], 10):0);
		$cPB = (!empty($layer['cPB'])?intval($layer['cPB'], 10):0);

		$command = <<< EOT
{$bp}{$im_bin} \
- \
-adaptive-resize {$cX}x{$cY} \

EOT;
		$out = self::exec_command($vars, $command, $format, $in, $steps, __FUNCTION__.' '.$step);
		//return $out;
		return $command;
	}

	static function c_tileoverlay(&$vars, $layer) {

		$cX = (!empty($layer['cX'])?intval($layer['cX'], 10):1);
		$cY = (!empty($layer['cY'])?intval($layer['cY'], 10):1);

		$cPL = (!empty($layer['cPL'])?intval($layer['cPL'], 10):0);
		$cPR = (!empty($layer['cPR'])?intval($layer['cPR'], 10):0);
		$cPT = (!empty($layer['cPT'])?intval($layer['cPT'], 10):0);
		$cPB = (!empty($layer['cPB'])?intval($layer['cPB'], 10):0);


		$bp = BIN_PATH;
		if(defined('ALT_BIN_PATH')) {
			$bp = ALT_BIN_PATH;
		}
		$im_bin = IMAGEMAGICK_BIN;

		$imgname = $layer['image'];
		$imgpath = escapeshellarg(TPT_IMAGES_DIR.DIRECTORY_SEPARATOR.$imgname);

		$command = <<< EOT
{$bp}{$im_bin} \
- \
\( -size {$cX}x{$cY} -background transparent tile:$imgpath \) \
-composite \

EOT;
		//tpt_dump($command);
		return $command;
	}
	static function o_tileoverlay(&$vars, $layer, $in='', &$steps=array(), $step='', $format='png') {
		$command = self::c_tileoverlay($vars, $layer);

		return self::exec_command($vars, $command, $format, $in, $steps, __FUNCTION__.' '.$step);
	}
	static function c_canvassize(&$vars, $layer, $in='') {
		$bp = BIN_PATH;
		if(defined('ALT_BIN_PATH')) {
			$bp = ALT_BIN_PATH;
		}
		$im_bin = IMAGEMAGICK_BIN;

		//$commands = array();
		//tpt_dump($layer);

		$cX = (!empty($layer['cX'])?intval($layer['cX'], 10):1);
		$cY = (!empty($layer['cY'])?intval($layer['cY'], 10):1);

		$cPL = (!empty($layer['cPL'])?intval($layer['cPL'], 10):0);
		$cPR = (!empty($layer['cPR'])?intval($layer['cPR'], 10):0);
		$cPT = (!empty($layer['cPT'])?intval($layer['cPT'], 10):0);
		$cPB = (!empty($layer['cPB'])?intval($layer['cPB'], 10):0);

		$command = '';


		if(!empty($cPL)) {
			$cX += $cPL;
			/*
			$IMCommand = <<< EOT
-background transparent
-gravity west
-splice {$cPL}x0
$format
EOT;
			*/
			$command = <<< EOT
$command -background transparent \
-gravity east \
-extent {$cX}x{$cY} \

EOT;
			//tpt_dump($IMCommand, true);
			//return $out;
		}
		if(!empty($cPR)) {
			$cX += $cPR;
			/*
			$IMCommand = <<< EOT
{$bp}{$im_bin}
-
-background transparent
-gravity east
-splice {$cPR}x0
$format
EOT;
			*/
			$command = <<< EOT
$command -background transparent \
-gravity west \
-extent {$cX}x{$cY} \

EOT;
			//tpt_dump($IMCommand, true);
		}
		if(!empty($cPT)) {
			$cY += $cPT;
			/*
			$IMCommand = <<< EOT
{$bp}{$im_bin}
-
-background transparent
-gravity south
-splice 0x{$cPT}
$format
EOT;
			*/
			$command = <<< EOT
$command -background transparent \
-gravity south \
-extent {$cX}x{$cY} \

EOT;
		}
		if(!empty($cPB)) {
			$cY += $cPB;
			/*
			$IMCommand = <<< EOT
{$bp}{$im_bin}
-
-background transparent
-gravity north
-splice 0x{$cPB}
$format
EOT;
			*/
			$command = <<< EOT
$command -background transparent \
-gravity north \
-extent {$cX}x{$cY} \

EOT;
		}

		if(!empty($in)) {
			$command = <<< EOT
$in $command
EOT;
		}

		return $command;
	}
	static function o_canvassize(&$vars, $layer, $in='', &$out='', &$steps=array(), $step='', $format='png') {
		$bp = BIN_PATH;
		if(defined('ALT_BIN_PATH')) {
			$bp = ALT_BIN_PATH;
		}
		$im_bin = IMAGEMAGICK_BIN;

		$commands = array();
		//tpt_dump($layer);

		$cX = (!empty($layer['cX'])?intval($layer['cX'], 10):1);
		$cY = (!empty($layer['cY'])?intval($layer['cY'], 10):1);

		$cPL = (!empty($layer['cPL'])?intval($layer['cPL'], 10):0);
		$cPR = (!empty($layer['cPR'])?intval($layer['cPR'], 10):0);
		$cPT = (!empty($layer['cPT'])?intval($layer['cPT'], 10):0);
		$cPB = (!empty($layer['cPB'])?intval($layer['cPB'], 10):0);

		$input = $in;

		if(!empty($cPL)) {
			$cX += $cPL;
			/*
			$IMCommand = <<< EOT
{$bp}{$im_bin}
-
-background transparent
-gravity west
-splice {$cPL}x0
$format
EOT;
			*/
			$command = <<< EOT
{$bp}{$im_bin} \
- \
-background transparent \
-gravity east \
-extent {$cX}x{$cY} \

EOT;
			//tpt_dump($IMCommand, true);
			$commands[__FUNCTION__.' LeftPad'.$step] = $command;
			$input = $out = self::exec_command($vars, $command, $format, $input, $steps, __FUNCTION__.' LeftPad'.$step);
			//return $out;
		}
		if(!empty($cPR)) {
			$cX += $cPR;
			/*
			$IMCommand = <<< EOT
{$bp}{$im_bin}
-
-background transparent
-gravity east
-splice {$cPR}x0
$format
EOT;
			*/
			$command = <<< EOT
{$bp}{$im_bin} \
- \
-background transparent \
-gravity west \
-extent {$cX}x{$cY} \

EOT;
			//tpt_dump($IMCommand, true);
			$commands[__FUNCTION__.' RightPad'.$step] = $command;
			$input = $out = self::exec_command($vars, $command, $format, $input, $steps, __FUNCTION__.' RightPad'.$step);
		}
		if(!empty($cPT)) {
			$cY += $cPT;
			/*
			$IMCommand = <<< EOT
{$bp}{$im_bin}
-
-background transparent
-gravity south
-splice 0x{$cPT}
$format
EOT;
			*/
			$command = <<< EOT
{$bp}{$im_bin} \
- \
-background transparent \
-gravity south \
-extent {$cX}x{$cY} \

EOT;
			$commands[__FUNCTION__.' TopPad'.$step] = $command;
			$input = $out = self::exec_command($vars, $command, $format, $input, $steps, __FUNCTION__.' TopPad'.$step);
		}
		if(!empty($cPB)) {
			$cY += $cPB;
			/*
			$IMCommand = <<< EOT
{$bp}{$im_bin}
-
-background transparent
-gravity north
-splice 0x{$cPB}
$format
EOT;
			*/
			$command = <<< EOT
{$bp}{$im_bin} \
- \
-background transparent \
-gravity north \
-extent {$cX}x{$cY} \

EOT;
			$commands[__FUNCTION__.' BottomPad'.$step] = $command;
			$input = $out = self::exec_command($vars, $command, $format, $input, $steps, __FUNCTION__.' BottomPad'.$step);
		}

		return $commands;
	}

	static function c_setopacity(&$vars, $layer, $in='') {
		$bp = BIN_PATH;
		if(defined('ALT_BIN_PATH')) {
			$bp = ALT_BIN_PATH;
		}
		$im_bin = IMAGEMAGICK_BIN;

		$cX = (!empty($layer['cX'])?intval($layer['cX'], 10):1);
		$cY = (!empty($layer['cY'])?intval($layer['cY'], 10):1);

		$cPL = (!empty($layer['cPL'])?intval($layer['cPL'], 10):0);
		$cPR = (!empty($layer['cPR'])?intval($layer['cPR'], 10):0);
		$cPT = (!empty($layer['cPT'])?intval($layer['cPT'], 10):0);
		$cPB = (!empty($layer['cPB'])?intval($layer['cPB'], 10):0);

		$opacity = floatval($layer['opacity']);

		//tpt_dump($in);
		$command = <<< EOT
-alpha set \
-channel a \
-evaluate \
multiply $opacity \
+channel \

EOT;
		if(!empty($in)) {
			$command = <<< EOT
$in $command
EOT;
		}

		return $command;
	}
	static function o_setopacity(&$vars, $layer, $in='', &$out='', &$steps=array(), $step='', $format='png') {
		$bp = BIN_PATH;
		if(defined('ALT_BIN_PATH')) {
			$bp = ALT_BIN_PATH;
		}
		$im_bin = IMAGEMAGICK_BIN;

		$opacity = floatval($layer['opacity']);

		$command = <<< EOT
{$bp}{$im_bin} \
- \
-alpha set \
-channel a \
-evaluate \
multiply $opacity \
+channel \

EOT;

		$out = self::exec_command($vars, $command, $format, $in, $steps, __FUNCTION__.' '.$step);

		return $command;
	}

	static function exec_command(&$vars, $command, $output_format='png', $input='', &$steps=array(), $step='', $nooutformat=0) {
		$out = '';

		$streams = 2;
		if(!empty($input)) {
			$streams = 3;
		}

		$command = trim($command);
		if(!empty($command)) {
			if ($output_format == 'png') {
				$output_format = 'png:-';
			}
			if(!empty($nooutformat)) {
				$output_format = '';
			}
			$command = <<< EOT
$command
$output_format
EOT;
			//tpt_dump($srcimage, true);
			//$command = preg_replace('#([\r\n]+)#', ' \\\\$1 ', $command);

			$step = (empty($step)?base64_encode($command).' '.$vars['environment']['request_time']:$step);
				//tpt_dump($srcimage['data']);
			self::convert($vars, $steps, $step, $command, $streams, $input);
			//tpt_dump($step);
			//tpt_dump($steps);
			$out = $steps[$step];
		}

		//tpt_dump($steps[$step]);
		//tpt_dump($out);
		return $out;
	}

	static function storeLayer(&$vars, $command, $out, $steps, $srcimage=array()) {
		$db = $vars['db']['handler'];

		$sdata = var_export($steps, true);
		if(!empty($srcimage)) {
			$srcsdata = $srcimage['steps'];
			$sdata = <<< EOT
$srcsdata
------------------------------------------
$sdata
EOT;
		}

		$tptlogsdb = DB_DB_TPT_LOGS;

		$sdata = mysql_real_escape_string($sdata);
		$command = mysql_real_escape_string($command);
		$data = mysql_real_escape_string($out);

		$timestamp = $vars['environment']['request_time'];
		$query = <<< EOT
INSERT INTO
	`$tptlogsdb`.`tpt_request_rq_imagemagick_sandbox` (
		`command`,
		`data`,
		`steps`,
		`timestamp`
	)
	VALUES(
		"$command",
		"$data",
		"$sdata",
		$timestamp
	)
EOT;
		$db->query($query);
	}



	static function getImageURL(&$vars, $layer) {
		//$l = array('l'=>array(0=>array_filter($layer, function($a){return !is_null($a);})));
		$pdir = '';
		$purl = '';
		if(isset($layer['layertype'])) {
			$pdir = $layer['layertype'].DIRECTORY_SEPARATOR;
			$purl = $layer['layertype'].'/';
		}
		$l = array('l'=>array(0=>$layer));
		$dir = self::getCachedImageDir($vars, $l);
		$filename = self::getCachedImageName($vars, $l);

		//$filename = sha1(http_build_query($l)).'.png';
		//tpt_dump($l);
		//tpt_dump(TPT_PREVIEW_CACHE_LAYER_DIR.DIRECTORY_SEPARATOR.$filename);
		//tpt_dump($vars['config']['pGenerator']['cache']['disable']['use']['general'], true);
		if((!isset($vars['config']['pGenerator']['cache']['disable']['use']['general'])||empty($vars['config']['pGenerator']['cache']['disable']['use']['general'])) && (!isset($vars['config']['pGenerator']['cache']['disable']['use']['layertype'][$layer['layertype']])||empty($vars['config']['pGenerator']['cache']['disable']['use']['layertype'][$layer['layertype']])) && file_exists($dir.DIRECTORY_SEPARATOR.$filename)) {
			return self::getCachedURL($vars, $l).'/'.$filename;
		} else {
			return self::getUncachedURL($vars, $layer, ((!isset($vars['config']['pGenerator']['cache']['disable']['storage']['layertype'][$layer['layertype']])||empty($vars['config']['pGenerator']['cache']['disable']['storage']['layertype'][$layer['layertype']]))?0:1));
		}
	}
	static function getCachedURL(&$vars, $input) {
		if (isset($input['l'][0]['layertype']) && (count($input['l'])==1)) {
			return TPT_PREVIEW_CACHE_LAYER_URL.'/'.$input['l'][0]['layertype'];
		} else {
			return TPT_PREVIEW_CACHE_LAYER_URL;
		}

	}
	static function getUncachedURL(&$vars, $layer, $cache=0) {
		$cX = (!empty($layer['cX'])?'width: '.$layer['cX'].'px;':'');
		$cY = (!empty($layer['cY'])?'height: '.$layer['cY'].'px;':'');

		//$cBG = $layer['cY'];
		$layer = array('l'=>array(0=>$layer));
		return $vars['url']['handler']->wrap($vars, '/g-preview') . '?' . htmlspecialchars(http_build_query($layer)) . (!empty($cache) ? '&amp;cache=1' : '');
	}
	static function getCachedImageDir(&$vars, $input) {
		if (isset($input['l'][0]['layertype']) && (count($input['l'])==1)) {
			return TPT_PREVIEW_CACHE_LAYER_DIR . DIRECTORY_SEPARATOR .$input['l'][0]['layertype'];
		} else {
			return TPT_PREVIEW_CACHE_LAYER_DIR;
		}

	}
	static function getCachedImageName(&$vars, $input) {
		$layers_module = getModule($vars, 'PreviewLayer');
		$layers = $layers_module->moduleData['id'];

		$ldiff = array();
		foreach ($input['l'] as $key=>$layer) {
			if(isset($layer['id']) && !empty($layer['id'])) {
				$lrow = $layers[$layer['id']];

				$ldiff[$key] = array_diff($layer, $lrow);
				$ldiff[$key]['id'] = $layer['id'];
			}
		}

		return Base32::encode(http_build_query($ldiff)).'.png';
	}
	static function createImageHTML(&$vars, $layer) {
		$layers_module = getModule($vars, 'PreviewLayer');
		$layers = $layers_module->moduleData['id'];
		$id = $layer['id'];
		$l = $layers[$id];

		$position = (!empty($l['overlapping_layer'])?' position-absolute':' position-relative');
		$classes = (!empty($l['html_classes'])?$l['html_classes']:'');
		//$position = ' position-relative';
		//$top = (!empty($l['overlapping_layer'])?' top: -100%;':'');
		//$top = (!empty($l['overlapping_layer'])?' position-absolute':' position-relative');
		$zindex = ' z-index: '.$l['order'].';';


		$src = self::getImageURL($vars, $layer);
		//return '<img class="'.$position.'" src="'.$src.'" style="top: 0px; left: 0px;'.$zindex.$cX.' '.$cY.'" />';
		return '<img onload="hide_loading_message(event);" id="layer'.$id.'" class="'.$position.$classes.'" src="'.$src.'" style="vertical-align: top; width: 100%; top: 0px; bottom: 0; left: 0px; right:0; '.$zindex.'" />';
	}

    static function previewHTML2(&$vars, $input=array(), $options=array(), &$vinput=array()) {
		$data_module = getModule($vars, 'BandData');
		$types_module = getModule($vars, 'BandType');
		$styles_module = getModule($vars, 'BandStyle');
		$cpf_module = getModule($vars, 'CustomProductField');
		$fields = $cpf_module->moduleData['id'];
		$cpfsname = $cpf_module->moduleData['pname'];
		$layers_module = getModule($vars, 'PreviewLayer');
		$layers = $layers_module->moduleData['id'];

		$tpt_imagesurl = TPT_IMAGES_URL;

		$html = '';

		/*
		if(empty($pgconf)) {
			return '';
		}
		*/

		/*
		$input = array_intersect_key($pgconf, $cpfspg);
		$_input = array();
		foreach($input as $name=>$value) {
			$parname = $cpfspg[$name]['pname'];
			$_input[$parname] = $$parname = $value;
		}
		$input = $_input;
		*/

		/*
		$pgType = 2;
		$pgStyle = 5;
		*/

		extract(array_intersect_key($input, $cpfsname), EXTR_OVERWRITE);
		$type = $types_module->getActiveItem($vars, $input, $options);
		$style = $styles_module->getActiveItem($vars, $input, $options);
		//tpt_dump($type);
		//tpt_dump($style);

		$bdata = $data_module->typeStyle[$type][$style];

		$bg = (empty($bdata['clearband_layer'])?'background: transparent none;':'background: transparent url('.$tpt_imagesurl.'/clearband.png) repeat scroll 0 0;');

		$blayers = explode(',', $bdata['preview_layers']);
		$blayers = array_combine($blayers, $blayers);
		$layers = array_intersect_key($layers, $blayers);

		$pgid = (isset($pgconf['pgid'])?$pgconf['pgid']:'');

		$imgs = array();
		foreach($layers as $layer) {
			/*
			if($layer['layertype'] == 'bandoutline') {
				$layer['type'] = $type;
				$layer['style'] = $style;
			}
			*/
			if(!empty($layer['preview_params_ids'])) {
				$params = explode(',', $layer['preview_params_ids']);
				//tpt_dump($layer['preview_params_ids']);
				//tpt_dump($input);
				foreach($params as $parid) {
					$fld = $fields[$parid];
					$fldname = $fld['pname'];
					if(isset($input[$fldname])) {
						if(empty($input[$fldname]) && !empty($fld['preview_use_default_layer_value_when_empty'])) {

						} else if(!empty($fld['validateactivevalue'])) {
							$module = getModule($vars, $fld['validateactivevalue_module']);
							$layer[$fldname] = $module->getActiveItem($vars, $input, $options);
						} else {
							$layer[$fldname] = $input[$fldname];
						}
					}
				}
				//tpt_dump($layer);
			}
			$ncparams = array();
			if(!empty($layer['nullcheck_preview_params_ids'])) {
				$ncps = explode('|', $layer['nullcheck_preview_params_ids']);
				//tpt_dump($ncps);
				foreach($ncps as $ncp) {
					$ncparam = explode(':', $ncp);
					if(!empty($fields[$ncparam[0]])) {
						$ncparam[1] = explode(',', $ncparam[1]);
						foreach($ncparam[1] as $ncpfid) {
							if(isset($input[$fields[$ncpfid]['pname']])) {
								$layer[$fields[$ncparam[0]]['pname']] = 1;
								break;
							}
						}
					}
				}
			}
			//tpt_dump($layer);
			$imgs[] = self::createImageHTML($vars, $layer);
		}

		/*
		$imgs[] = self::createImageHTML($vars, array(
			'layertype'=>'image',
			'cX'=>738,
			'cY'=>114,
			'image'=>'clearband.png',
			'tile'=>1,
		));
		*/
		/*
		$imgs[] = self::createImageHTML($vars, array(
			'layertype'=>'bandoutline',
			'type'=>$type,
			'style'=>$style
		));
		*/

		$imgs = implode('', $imgs);

		$html .= <<< EOT
<div id="preview$pgid">
	<div class="position-relative" style="$bg min-height: 106px;">$imgs</div>
</div>
EOT;


		return $html;
	}

    static function previewHTML(&$vars, $pgconf=array()) {
        //var_dump($pgconf);die();
        $tpt_baseurl = $vars['config']['baseurl'];
        $tpt_jsurl = TPT_JS_URL;
        $tpt_cssurl = TPT_CSS_URL;
        $tpt_imagesurl = $vars['config']['images_url'];


$types_module = getModule($vars, 'BandType');
$data_module = getModule($vars, 'BandData');
$fonts_module = getModule($vars, 'BandFont');
$fonts = $fonts_module->moduleData['id'];
$sizes_module = getModule($vars, 'BandSize');
$msg_module = getModule($vars, 'BandMessage');

//var_dump($pgconf);die();
//var_dump($pgTextCont);die();
extract($pgconf);

$previewtime = time();

$default_type = DEFAULT_TYPE;
$default_style = DEFAULT_STYLE;
$default_font = DEFAULT_FONT_NAME;
$default_band_color = DEFAULT_BAND_COLOR;
$default_message_color = DEFAULT_MESSAGE_COLOR;

$lights_off_color = LIGHTS_OFF_COLOR;
$default_foreground_color = DEFAULT_FOREGROUND_COLOR;
$green_glow_color = GREEN_GLOW_COLOR;
$blue_glow_color = BLUE_GLOW_COLOR;

$pgType = (!empty($pgType)?$pgType:DEFAULT_TYPE);
$pgStyle = (!empty($pgStyle)?$pgStyle:DEFAULT_STYLE);
//var_dump($pgStyle);die();
$pgFont = (!empty($pgFont)?$pgFont:DEFAULT_FONT_ID);
//var_dump($pgFont);die();
$pgFrontRows = (!empty($pgFrontRows)?$pgFrontRows:1);
$pgBackRows = (!empty($pgBackRows)?$pgBackRows:1);
//var_dump($pgTextCont);die();
$pgTextCont = (!empty($pgTextCont)?intval($pgTextCont, 10):0);
$pgTextBackMsg = (!empty($pgTextBackMsg)?intval($pgTextBackMsg, 10):0);
$pgBandColor = (!empty($pgBandColor)?$pgBandColor:'-1:'.DEFAULT_BAND_COLOR);
$pgMessageColor = (!empty($pgMessageColor)?$pgMessageColor:'-1:'.DEFAULT_MESSAGE_COLOR);
$pgFrontMessage = isset($pgFrontMessage)?urlencode($pgFrontMessage):'';
$pgFrontMessage2 = isset($pgFrontMessage2)?urlencode($pgFrontMessage2):'';
$pgBackMessage = isset($pgBackMessage)?urlencode($pgBackMessage):'';
$pgBackMessage2 = isset($pgBackMessage2)?urlencode($pgBackMessage2):'';
/*
if(empty($bm) && empty($bm2)) {
    $pgTextCont = 1;
}
*/
		$pgFrontRows = 1;
if(!empty($pgFrontMessage2)) {
    $pgFrontRows = 2;
} else {
    $pgFrontMessage2 = DEFAULT_MESSAGE_FRONT2;
}
		$pgBackRows = 1;
if(!empty($pgBackMessage2)) {
    $pgBackRows = 2;
} else {
    $pgBackMessage2 = DEFAULT_MESSAGE_BACK2;
}


$pgClipartFrontLeft = !empty($pgClipartFrontLeft)?intval($pgClipartFrontLeft, 10):0;
$pgClipartFrontLeft_c = !empty($pgClipartFrontLeft_c)?$pgClipartFrontLeft_c:'';
$pgClipartFrontRight = !empty($pgClipartFrontRight)?intval($pgClipartFrontRight, 10):0;
$pgClipartFrontRight_c = !empty($pgClipartFrontRight_c)?$pgClipartFrontRight_c:'';
$pgClipartFrontLeft2 = !empty($pgClipartFrontLeft2)?intval($pgClipartFrontLeft2, 10):0;
$pgClipartFrontLeft2_c = !empty($pgClipartFrontLeft2_c)?$pgClipartFrontLeft2_c:'';
$pgClipartFrontRight2 = !empty($pgClipartFrontRight2)?intval($pgClipartFrontRight2, 10):0;
$pgClipartFrontRight2_c = !empty($pgClipartFrontRight2_c)?$pgClipartFrontRight2_c:'';
$pgClipartBackLeft = !empty($pgClipartBackLeft)?intval($pgClipartBackLeft, 10):0;
$pgClipartBackLeft_c = !empty($pgClipartBackLeft_c)?$pgClipartBackLeft_c:'';
$pgClipartBackRight = !empty($pgClipartBackRight)?intval($pgClipartBackRight, 10):0;
$pgClipartBackRight_c = !empty($pgClipartBackRight_c)?$pgClipartBackRight_c:'';
$pgClipartBackLeft2 = !empty($pgClipartBackLeft2)?intval($pgClipartBackLeft2, 10):0;
$pgClipartBackLeft2_c = !empty($pgClipartBackLeft2_c)?$pgClipartBackLeft2_c:'';
$pgClipartBackRight2 = !empty($pgClipartBackRight2)?intval($pgClipartBackRight2, 10):0;
$pgClipartBackRight2_c = !empty($pgClipartBackRight2_c)?$pgClipartBackRight2_c:'';

$pgCutAway = !empty($pgCutAway)?intval($pgCutAway, 10):0;


$pgDir = $data_module->typeStyle[$pgType][$pgStyle]['preview_folder'];
$x_bg = '100%';
$y_bg = '100%';
$x2_bg = '-100px';
$y2_bg = '-100px';

$plainimage = 'plain.png';
/*
if($pgStyle == 7)
    $plainimage = 'plain-dual.png';
*/
$pgBandImg = TPT_IMAGES_DIR.DIRECTORY_SEPARATOR.'preview'.DIRECTORY_SEPARATOR.$pgDir.DIRECTORY_SEPARATOR.$plainimage;

if(is_file($pgBandImg)) {
$pgBandImgSize = getimagesize($pgBandImg);
//var_dump($pgBandImgSize);die();

$x_bg = intval($pgBandImgSize[0], 10).'px';
$y_bg = intval($pgBandImgSize[1], 10).'px';
}

$band_sizes = explode(',', $data_module->typeStyle[$pgType][$pgStyle]['available_sizes_id']);
$initsize = reset($band_sizes);

//var_dump($initsize);die();
$band_length = 0;
if(!empty($sizes_module->moduleData['id'][$initsize])) {
$band_length = intval($sizes_module->moduleData['id'][$initsize]['milimeters'], 10);
}
$band_width = floatval($types_module->moduleData['id'][$pgType]['width_mm']);
$scale = 4;

//tpt_dump($pgType);
//tpt_dump($pgStyle, true);
//tpt_dump($data_module->typeStyle[$pgType][$pgStyle], true);
$hardW = $data_module->typeStyle[$pgType][$pgStyle]['preview_width'];
$hardH = $data_module->typeStyle[$pgType][$pgStyle]['preview_height'];
$hardPT = $data_module->typeStyle[$pgType][$pgStyle]['preview_bg_toppadding'];
$hardPB = $data_module->typeStyle[$pgType][$pgStyle]['preview_bg_bottompadding'];
$hardBGW = $data_module->typeStyle[$pgType][$pgStyle]['preview_bg_width'];
$hardBGH = $data_module->typeStyle[$pgType][$pgStyle]['preview_bg_height'];
$hardPL = $data_module->typeStyle[$pgType][$pgStyle]['preview_leftpadding'];
$hardPR = $data_module->typeStyle[$pgType][$pgStyle]['preview_rightpadding'];
//tpt_dump($hardPL);
//tpt_dump($hardPR, true);


$pgMPadTop = $data_module->typeStyle[$pgType][$pgStyle]['preview_toppadding'];
$pgMPadBottom = $data_module->typeStyle[$pgType][$pgStyle]['preview_bottompadding'];

/*
if((($pgStyle == 7) && ($pgType == 5)) || ($pgStyle == 8)) {
    $hardPL = 60;
    $hardPR = 60;
    $pgMPadTop = 13;
    $pgMPadBottom = 11;
    $hardPT = 5;
    $hardPB = 5;
} else if(($pgType == 1) && ($pgStyle == 7)) {
    //$hardPT = 50;
    //$hardPB = 50;
}
*/

//die('asdasdasdas');
if(!empty($data_module->typeStyle[$pgType][$pgStyle]['preview_css_background_fix_x']))
        $x_bg = $data_module->typeStyle[$pgType][$pgStyle]['preview_css_background_fix_x'];
if(!empty($data_module->typeStyle[$pgType][$pgStyle]['preview_css_background_fix_y']))
        $y_bg = $data_module->typeStyle[$pgType][$pgStyle]['preview_css_background_fix_y'];
if(!empty($data_module->typeStyle[$pgType][$pgStyle]['preview_css_background_fix_x2']))
        $x2_bg = $data_module->typeStyle[$pgType][$pgStyle]['preview_css_background_fix_x2'];
if(!empty($data_module->typeStyle[$pgType][$pgStyle]['preview_css_background_fix_y2']))
        $y2_bg = $data_module->typeStyle[$pgType][$pgStyle]['preview_css_background_fix_y2'];


$pgWidth = isset($pgWidth)?intval($pgWidth, 10):$hardW;
//$pgWidth = isset($pgWidth)?intval($pgWidth, 10):round($band_length*$scale);
$pgHeight = isset($pgHeight)?intval($pgHeight, 10):$hardH;
//$pgHeight = isset($pgHeight)?intval($pgHeight, 10):round($band_width*$scale);
$pgPaddingTop = isset($pgPaddingTop)?intval($pgPaddingTop, 10):$hardPT;
$pgPaddingBottom = isset($pgPaddingBottom)?intval($pgPaddingBottom, 10):$hardPB;
$pgPaddingLeft = isset($pgPaddingLeft)?intval($pgPaddingLeft, 10):$hardPL;
$pgPaddingRight = isset($pgPaddingRight)?intval($pgPaddingRight, 10):$hardPR;

$plainimage = 'plain.png';
/*
if($pgStyle == 7)
    $plainimage = 'plain-dual.png';
*/
$pgOutlineFile = isset($pgOutlineFile)?$pgOutlineFile:$plainimage;


$pgFullPreview = isset($pgFullPreview)?intval($pgFullPreview, 10):1;
$pgEnableJavascript = isset($pgEnableJavascript)?intval($pgEnableJavascript, 10):0;
$pgAjaxJavascript = isset($pgAjaxJavascript)?intval($pgAjaxJavascript, 10):0;






$UEpgBandColor = urlencode($pgBandColor);
$UEpgMessageColor = urlencode($pgMessageColor);

$pgWidthProcessed = $pgWidth - ($pgPaddingLeft + $pgPaddingRight);
$pg_fx = $pgWidthProcessed;
$pg_x = $pgWidthProcessed;






$pgHeightProcessed = $pgHeight - ($pgPaddingTop + $pgPaddingBottom);
$pgHeightMessage = $pgHeightProcessed - ($pgMPadTop + $pgMPadBottom);
$pgHeightMessageHalf = floor($pgHeightMessage/2);
$pg_yf = $pg_yb = $pgHeightMessage;
$pg_y = $pg_fy = $pgHeight;

/*
if(($pgType == 5) && $pgFullPreview) {
    $x_bg = '694px';
    $y_bg = '92px';
    $x2_bg = '-99px';
    $y2_bg = '-99px';

    $scale = 3;
    $pgWidth = $band_length*$scale;
    $pg_fx = $pgWidthProcessed = 695;
    $pgWidth = 660;
    $pg_x = 695;

    $pgPaddingTop = 20;
    $pgPaddingBottom = 20;
    $pgHeight = round(98);
    $pgHeightProcessed = $pgHeight - ($pgPaddingTop + $pgPaddingBottom);
    $pgHeightProcessedHalf = round($pgHeightProcessed/2);
    $pg_y = $pg_yf = $pg_yb = $pgHeightProcessed;
    $pg_fy = $pgHeight;

}
*/



$pgSeparatorImg = 'none';
//$pgFattr = ' class="frontPreview height-'.$pgHeightProcessed.'" style=""';
//$pgFattr = ' class="frontPreview padding-top-'.$pgMPadTop.' padding-bottom-'.$pgMPadBottom.' height-'.$pgHeightProcessed.'" style=""';
//$pgFattr = ' class="frontPreview height-'.$pgHeightProcessed.'" style=""';
$pgFattr = ' class="frontPreview height-'.$pgHeightMessage.'" style=""';
//$pgBattr = ' class="backPreview display-none float-right '.$bdisplay.' height-'.$pgHeightProcessed.'" style="width: 50%;"';
//$pgBattr = ' class="backPreview display-none float-right '.$bdisplay.' padding-top-'.$pgMPadTop.' padding-bottom-'.$pgMPadBottom.' height-'.$pgHeightProcessed.'" style="width: 50%;"';
//$pgBattr = ' class="backPreview display-none float-right '.$bdisplay.' height-'.$pgHeightProcessed.'" style="width: 50%;"';
$pgBattr = ' class="backPreview display-none float-right height-'.$pgHeightMessage.'" style="width: 50%;"';
//tpt_dump($pgTextBackMsg, false);
//tpt_dump($pgTextCont, true);
if($pgTextBackMsg && !$pgTextCont) {
    //$pgFattr = ' class="frontPreview float-left height-'.$pgHeightProcessed.'" style="width: 50%;"';
    //$pgFattr = ' class="frontPreview float-left padding-top-'.$pgMPadTop.' padding-bottom-'.$pgMPadBottom.' height-'.$pgHeightProcessed.'" style="width: 50%;"';
    //$pgFattr = ' class="frontPreview float-left height-'.$pgHeightProcessed.'" style="width: 50%;"';
    $pgFattr = ' class="frontPreview float-left height-'.$pgHeightMessage.'" style="width: 50%;"';
    //$pgBattr = ' class="backPreview float-right '.$bdisplay.' height-'.$pgHeightProcessed.'" style="width: 50%;"';
    //$pgBattr = ' class="backPreview float-right '.$bdisplay.' padding-top-'.$pgMPadTop.' padding-bottom-'.$pgMPadBottom.' height-'.$pgHeightProcessed.'" style="width: 50%;"';
    //$pgBattr = ' class="backPreview float-right '.$bdisplay.' height-'.$pgHeightProcessed.'" style="width: 50%;"';
    $pgBattr = ' class="backPreview float-right height-'.$pgHeightMessage.'" style="width: 50%;"';
    $pg_x = floor($pg_x/2);
    $pgSeparatorImg = 'url('.TPT_IMAGES_URL.'/preview/separator-1x1.png)';
}

//var_dump($pgFrontRows);
//var_dump($pgBackRows);
//die();

$emptyimg = "$tpt_imagesurl/preview/empty.png";
$dm1 = DEFAULT_MESSAGE_FRONT;
$fs1 = defined(DEFAULT_MESSAGE_FRONT_POINTSIZE)?intval(DEFAULT_MESSAGE_FRONT_POINTSIZE, 10):0;
$fs1 = $data_module->typeStyle[$pgType][$pgStyle]['preview_message_front_fontsize'];
//var_dump($fs1);die();
$img1id = 'elmid=tpt_pg_front_message';
$img1numlines = 'num_lines='.$pgFrontRows;
$img1 = "$tpt_baseurl/generate-preview?bandType=$pgType&amp;pg_x=$pg_x&amp;pg_y=$pg_yf&amp;fontSize=$fs1&amp;text=$pgFrontMessage&amp;font=$pgFont&amp;bandType=$pgType&amp;bandStyle=$pgStyle&amp;textColor=$UEpgMessageColor&amp;color=$UEpgBandColor&amp;lclipart=$pgClipartFrontLeft&amp;lclipart_c=$pgClipartFrontLeft_c&amp;rclipart=$pgClipartFrontRight&amp;rclipart_c=$pgClipartFrontRight_c&amp;type=plain&amp;$img1id&amp;$img1numlines&amp;timestamp=$previewtime";
$cfile = TPT_IMAGES_DIR.DIRECTORY_SEPARATOR.'preview'.DIRECTORY_SEPARATOR.'cached'.DIRECTORY_SEPARATOR.'plain'.DIRECTORY_SEPARATOR.'plain-'.$pg_x.'x'.$pg_yf.'x'.$fs1.'x0'.'-'.str_replace('/', '_', base64_encode(urldecode($pgFrontMessage))).'-'.str_replace('/', '_', base64_encode($pgFont)).'-style'.$pgStyle.'-'.str_replace('/', '_', base64_encode($pgMessageColor)).'.png';
//if($_SERVER['REMOTE_ADDR'] == '109.160.0.218') {
//    var_dump(is_file($cfile));//die();
//    var_dump(urldecode($pgFrontMessage));//die();
//    var_dump(str_replace('/', '_', base64_encode(urldecode($pgFrontMessage))));//die();
//    var_dump($cfile);//die();
//}
if(empty($_GET) && is_file($cfile) && (($pgType != 5) || ($pgStyle != 7))) {
    $filename = 'plain-'.$pg_x.'x'.$pg_yf.'x'.$fs1.'x0'.'-'.str_replace('/', '_', base64_encode(urldecode($pgFrontMessage))).'-'.str_replace('/', '_', base64_encode($pgFont)).'-style'.$pgStyle.'-'.str_replace('/', '_', base64_encode($pgMessageColor)).'.png';
    //header('Content-type: image/png');
    $img1 = "$tpt_imagesurl/preview/cached/plain/".urlencode($filename);
}
$dm2 = DEFAULT_MESSAGE_FRONT2;
$fs2 = defined(DEFAULT_MESSAGE_FRONT2_POINTSIZE)?intval(DEFAULT_MESSAGE_FRONT2_POINTSIZE, 10):0;
$fs2 = $data_module->typeStyle[$pgType][$pgStyle]['preview_message_front2_fontsize'];
$img2 = $emptyimg;

$dm3 = DEFAULT_MESSAGE_BACK;
$fs3 = defined(DEFAULT_MESSAGE_BACK_POINTSIZE)?intval(DEFAULT_MESSAGE_BACK_POINTSIZE, 10):0;
$fs3 = $data_module->typeStyle[$pgType][$pgStyle]['preview_message_back_fontsize'];
$img3id = 'elmid=tpt_pg_back_message';
$img3numlines = 'num_lines='.$pgBackRows;
$img3 = "$tpt_baseurl/generate-preview?bandType=$pgType&amp;pg_x=$pg_x&amp;pg_y=$pg_yb&amp;fontSize=$fs3&amp;text=$pgBackMessage&amp;font=$pgFont&amp;bandType=$pgType&amp;bandStyle=$pgStyle&amp;textColor=$UEpgMessageColor&amp;color=$UEpgBandColor&amp;lclipart=$pgClipartBackLeft&amp;lclipart_c=$pgClipartBackLeft_c&amp;rclipart=$pgClipartBackRight&amp;rclipart_c=$pgClipartBackRight_c&amp;type=plain&amp;$img3id&amp;$img3numlines&amp;timestamp=$previewtime";
$cfile = TPT_IMAGES_DIR.DIRECTORY_SEPARATOR.'preview'.DIRECTORY_SEPARATOR.'cached'.DIRECTORY_SEPARATOR.'plain'.DIRECTORY_SEPARATOR.'plain-'.$pg_x.'x'.$pg_yb.'x'.$fs3.'x0'.'-'.str_replace('/', '_', base64_encode(urldecode($pgBackMessage))).'-'.str_replace('/', '_', base64_encode($pgFont)).'-style'.$pgStyle.'-'.str_replace('/', '_', base64_encode($pgMessageColor)).'.png';
if(empty($_GET) && is_file($cfile) && (($pgType != 5) || ($pgStyle != 7))) {
    $filename = 'plain-'.$pg_x.'x'.$pg_yb.'x'.$fs3.'x0'.'-'.str_replace('/', '_', base64_encode(urldecode($pgBackMessage))).'-'.str_replace('/', '_', base64_encode($pgFont)).'-style'.$pgStyle.'-'.str_replace('/', '_', base64_encode($pgMessageColor)).'.png';
    //header('Content-type: image/png');
    $img3 = "$tpt_imagesurl/preview/cached/plain/".urlencode($filename);
}
$dm4 = DEFAULT_MESSAGE_BACK2;
$fs4 = defined(DEFAULT_MESSAGE_BACK2_POINTSIZE)?intval(DEFAULT_MESSAGE_BACK2_POINTSIZE, 10):0;
$fs4 = $data_module->typeStyle[$pgType][$pgStyle]['preview_message_back2_fontsize'];
$img4 = $emptyimg;
$pgHeightFront = 'height-'.$pgHeightMessage;
$pgHeightFront2 = 'height-'.$pgHeightMessageHalf;
$pgClassFront2 = 'display-none';

if($pgFrontRows == 2) {
    $pgHeightFront = $pgHeightFront2;
    $pgClassFront2 = 'display-block';
    $pg_yf = $pgHeightMessageHalf;
    $img2id = 'elmid=tpt_pg_front2_message';
    $img2numlines = 'num_lines=2';
    $img2 = "$tpt_baseurl/generate-preview?bandType=$pgType&amp;pg_x=$pg_x&amp;pg_y=$pg_yf&amp;fontSize=$fs2&amp;text=$pgFrontMessage2&amp;font=$pgFont&amp;bandType=$pgType&amp;bandStyle=$pgStyle&amp;textColor=$UEpgMessageColor&amp;color=$UEpgBandColor&amp;lclipart=$pgClipartFrontLeft2&amp;lclipart_c=$pgClipartFrontLeft2_c&amp;rclipart=$pgClipartFrontRight2&amp;rclipart_c=$pgClipartFrontRight2_c&amp;type=plain&amp;$img2id&amp;$img2numlines&amp;timestamp=$previewtime";
    $cfile = TPT_IMAGES_DIR.DIRECTORY_SEPARATOR.'preview'.DIRECTORY_SEPARATOR.'cached'.DIRECTORY_SEPARATOR.'plain'.DIRECTORY_SEPARATOR.'plain-'.$pg_x.'x'.$pg_yf.'x'.$fs2.'x0'.'-'.str_replace('/', '_', base64_encode(urldecode($pgFrontMessage2))).'-'.str_replace('/', '_', base64_encode($pgFont)).'-style'.$pgStyle.'-'.str_replace('/', '_', base64_encode($pgMessageColor)).'.png';
    if(empty($_GET) && is_file($cfile) && (($pgType != 5) || ($pgStyle != 7))) {
        $filename = 'plain-'.$pg_x.'x'.$pg_yf.'x'.$fs2.'x0'.'-'.str_replace('/', '_', base64_encode(urldecode($pgFrontMessage2))).'-'.str_replace('/', '_', base64_encode($pgFont)).'-style'.$pgStyle.'-'.str_replace('/', '_', base64_encode($pgMessageColor)).'.png';
        //header('Content-type: image/png');
        $img2 = "$tpt_imagesurl/preview/cached/plain/".urlencode($filename);
    }
}


$pgHeightBack = 'height-'.$pgHeightMessage;
$pgHeightBack2 = 'height-'.$pgHeightMessageHalf;
$pgClassBack2 = 'display-none';

if($pgBackRows == 2) {
    $pgHeightBack = $pgHeightBack2;
    $pgClassBack2 = 'display-block';
    $pg_yb = $pgHeightMessageHalf;
    $img4id = 'elmid=tpt_pg_back2_message';
    $img4numlines = 'num_lines=2';
    $img4 = "$tpt_baseurl/generate-preview?bandType=$pgType&amp;pg_x=$pg_x&amp;pg_y=$pg_yb&amp;fontSize=$fs4&amp;text=$pgBackMessage2&amp;font=$pgFont&amp;bandType=$pgType&amp;bandStyle=$pgStyle&amp;textColor=$UEpgMessageColor&amp;color=$UEpgBandColor&amp;lclipart=$pgClipartBackLeft2&amp;lclipart_c=$pgClipartBackLeft2_c&amp;rclipart=$pgClipartBackRight2&amp;rclipart_c=$pgClipartBackRight2_c&amp;type=plain&amp;$img4id&amp;$img4numlines&amp;timestamp=$previewtime";
    $cfile = TPT_IMAGES_DIR.DIRECTORY_SEPARATOR.'preview'.DIRECTORY_SEPARATOR.'cached'.DIRECTORY_SEPARATOR.'plain'.DIRECTORY_SEPARATOR.'plain-'.$pg_x.'x'.$pg_yb.'x'.$fs4.'x0'.'-'.str_replace('/', '_', base64_encode(urldecode($pgBackMessage2))).'-'.str_replace('/', '_', base64_encode($pgFont)).'-style'.$pgStyle.'-'.str_replace('/', '_', base64_encode($pgMessageColor)).'.png';
    if(empty($_GET) && is_file($cfile) && (($pgType != 5) || ($pgStyle != 7))) {
        $filename = 'plain-'.$pg_x.'x'.'x'.$fs4.'x0'.$pg_yb.'-'.str_replace('/', '_', base64_encode(urldecode($pgBackMessage2))).'-'.str_replace('/', '_', base64_encode($pgFont)).'-style'.$pgStyle.'-'.str_replace('/', '_', base64_encode($pgMessageColor)).'.png';
        //header('Content-type: image/png');
        $img4 = "$tpt_imagesurl/preview/cached/plain/".urlencode($filename);
    }
}


//$pgx4 = round($pg_x*2.2);
//$pgy4 = round($pg_y*2.2);
//$pgx4 = round($pg_x*1.6);
//$pgy4 = round($pg_y*1.6);
$pgx4 = 0;
$pgy4 = 0;
//$pgx4 = 0;
//$pgy4 = 0;


$bgWidth = $pgWidth;
if($pgType == 8) {
    $bgWidth = 280;
}

$pgBandBG = getModule($vars, "BandColor")->getBandBGStyle($vars, $pgBandColor, $pgMessageColor, $hardBGW, $hardBGH);
$pgBandBGProps = getModule($vars, "BandColor")->getColorProps($vars, $pgBandColor);
		//tpt_dump($pgBandColor);
		//tpt_dump($pgType);
		//tpt_dump($pgStyle);
		//tpt_dump($pgBandBGProps['notched']);
$pgExtraDualLayerBG = 'background: transparent none no-repeat scroll center center;';
if(/*(($pgStyle == 7) && ($pgType == 5)) || */(($pgStyle == 7) && ($pgType == 5)) || ($pgStyle == 8) || !empty($pgCutAway)) {
    //var_dump(43);die();
    $time = time();
    $pgExtraDualLayerBG = 'background: transparent url('.BASE_URL.'/generate-preview?pg_x=595&amp;pg_y=71&amp;type=dualslaplayer&amp;timestamp'.$time.'&amp;textColor='.$UEpgMessageColor.'&amp;invert_dual=0) no-repeat scroll center center;';
    //var_dump($pgExtraDualLayerBG);die();
} else if(($pgStyle == 7) && ($pgType == 1) && $pgBandBGProps['notched']) {
    $time = time();
    $pgExtraDualLayerBG = 'background: transparent url('.BASE_URL.'/generate-preview?type=dualquartlayer&amp;timestamp'.$time.'&amp;textColor='.$UEpgMessageColor.'&invert_dual=0) repeat-x scroll center center;';
} else if(($pgType == 2) && $pgBandBGProps['notched']) {
	//tpt_dump('wtf');
    $time = time();
    $pgExtraDualLayerBG = 'background: transparent url('.BASE_URL.'/generate-preview?type=dualextralayer&amp;timestamp='.$time.'&amp;textColor='.$UEpgMessageColor.'&amp;invert_dual=0) repeat-x scroll center center;';
} else if(($pgStyle == 16)) {
    $time = time();
    //$pgExtraDualLayerBG = 'background: transparent url('.BASE_URL.'/generate-preview?type=dualquartlayer&amp;timestamp'.$time.'&amp;textColor='.$UEpgMessageColor.'&invert_dual=0) repeat-x scroll center center;';
    $pgExtraDualLayerBG = getModule($vars, "BandColor")->getBandXLayerBGStyle($vars, $pgType, $pgStyle, $pgBandColor, $pgMessageColor);
}

//var_dump($pgBandColor);//die();
//var_dump($pgBandBG);die();

$preview = '';

//<div class="amz_green font-size-16 padding-top-10" style="text-align:center;font-family: TODAYSHOP-BOLDITALIC,arial;">Front Preview</div>
$preview .= <<< EOT
    <div class="width-$pgWidth position-relative overflow-hidden clearBoth" id="pg_container">
        <div id="pg_separator" class="display-none top-0 right-0 bottom-0 left-0 position-absolute background-position-CC" style="z-index: 3; background-image: $pgSeparatorImg; background-repeat: no-repeat;"></div>
        <div id="pg_band_outline" class="top-0 right-0 bottom-0 left-0 position-absolute" style="z-index: 2; background-image: url($tpt_imagesurl/preview/$pgDir/$pgOutlineFile); background-repeat: no-repeat; background-position: center center;"></div>
EOT;

if($pgFullPreview) {
$preview .= <<< EOT
    <div id="pg_fg_right" class="top-0 bottom-0 position-absolute width-100" style="left: $x_bg; z-index: 2; background-color: #$default_foreground_color;"></div>
    <div id="pg_fg_bottom" class="right-0 left-0 position-absolute height-100" style="top: $y_bg; z-index: 2; background-color: #$default_foreground_color;"></div>
    <div id="pg_fg_left" class="top-0 bottom-0 position-absolute width-100" style="left: $x2_bg; z-index: 2; background-color: #$default_foreground_color;"></div>
    <div id="pg_fg_top" class="right-0 left-0 position-absolute height-100" style="top: $y2_bg; z-index: 2; background-color: #$default_foreground_color;"></div>
EOT;
}
$preview .= <<< EOT
    <div class="position-relative background-repeat-repeat clearFix" style="z-index: 1;background-image: url($tpt_imagesurl/clearband.png);" id="pg_subcontainer">
        <div class="position-relative padding-top-$pgPaddingTop padding-bottom-$pgPaddingBottom clearFix background-position-CC background-repeat-no-repeat" style="z-index: 1;$pgBandBG" id="pg_bg">
            <div class=" padding-top-$pgMPadTop padding-bottom-$pgMPadBottom padding-left-$pgPaddingLeft padding-right-$pgPaddingRight position-relative clearFix background-position-CC background-repeat-no-repeat" style="$pgExtraDualLayerBG" id="pg_dl_extra">
                <div$pgFattr id="tpt_pg_front_container">
                    <div id="tpt_pg_front_parent" class="$pgHeightFront">
                        <img title="Front Preview" Alt="Front Preview" id="tpt_pg_front" style="max-width: 100%; max-height: 100%;" src="$img1" />
                    </div>
                    <div id="tpt_pg_front2_parent" class="$pgClassFront2 $pgHeightFront2">
                        <img title="Front Line 2 Preview" Alt="Front Line 2 Preview" id="tpt_pg_front2" style="max-width: 100%; max-height: 100%;" src="$img2" />
                    </div>
                </div>
                <div$pgBattr id="tpt_pg_back_container">
                    <div id="tpt_pg_back_parent" class="$pgHeightBack">
                        <img title="Back Preview" Alt="Back Preview" id="tpt_pg_back" style="max-width: 100%; max-height: 100%;" src="$img3" />
                    </div>
                    <div id="tpt_pg_back2_parent" class="$pgClassBack2 $pgHeightBack2">
                        <img title="Back Line 2 Preview" Alt="Back Line 2 Preview" id="tpt_pg_back2" style="max-width: 100%; max-height: 100%;" src="$img4" />
                    </div>
                </div>
            </div>

        </div>
    </div>
EOT;
//if($pgFullPreview) {
$preview .= <<< EOT
</div>
EOT;
//}


if($pgEnableJavascript) {
if(!$pgAjaxJavascript) {


$script = <<< EOT
var preview_bgs = ['pg_bg'];
var preview_ids = [];
EOT;

if(isDev('newmsgcontrols')) {
    foreach($msg_module->idstr as $msg) {
        $tmtvar = $msg['jsvarname_timeout'];
$script .= <<< EOT
var $tmtvar;
EOT;
    }

} else {
$script .= <<< EOT
var front_tmt;
var front2_tmt;
var back_tmt;
var back2_tmt;
EOT;
}

$script .= <<< EOT
//var pgx4 = $pgx4;
//var pgy4 = $pgy4;
var pgBGWidth = $hardBGW;
var pgBGHeight = $hardBGH;

var pg_x = $pg_x;
//var pg_y = $pg_y;

var pg_yp = $pgHeightMessage;
var pg_yf = $pg_yf;
var pg_yb = $pg_yb;
var pg_fx = $pg_fx;
var pg_fy = $pg_fy;
//var pg_ffy = $pg_y;
var pg_tpad = $pgPaddingTop;
var pg_bpad = $pgPaddingBottom;

var pg_default_fg_color = '$default_foreground_color';
var lights_off_color = '$lights_off_color';
var green_glow_color = '$green_glow_color';
var blue_glow_color = '$blue_glow_color';
var pg_defaulttype = $default_type;
var pg_defaultstyle = $default_style;
var pg_defaultfont = '$default_font';
var pg_defaultbandcolor = '$default_band_color';
var pg_defaultmessagecolor = '$default_message_color';


var preview_fss = [];
preview_fss['tpt_pg_front'] = '$fs1';
preview_fss['tpt_pg_front2'] = '$fs2';
preview_fss['tpt_pg_back'] = '$fs3';
preview_fss['tpt_pg_back2'] = '$fs4';
var preview_dms = [];
preview_dms['tpt_pg_front'] = '$dm1';
preview_dms['tpt_pg_front2'] = '$dm2';
preview_dms['tpt_pg_back'] = '$dm3';
preview_dms['tpt_pg_back2'] = '$dm4';

var front_rows = $pgFrontRows;
var back_rows = $pgBackRows;


var all_preview_ids = ['tpt_pg_front', 'tpt_pg_front2', 'tpt_pg_back', 'tpt_pg_back2'];

if(front_rows == 1) {
    if(back_rows == 1) {
        preview_ids = ['tpt_pg_front', 'tpt_pg_back'];
    } else {
        preview_ids = ['tpt_pg_front', 'tpt_pg_back', 'tpt_pg_back2'];
    }
} else {
    if(back_rows == 1) {
        preview_ids = ['tpt_pg_front', 'tpt_pg_front2', 'tpt_pg_back'];
    } else {
        preview_ids = ['tpt_pg_front', 'tpt_pg_front2', 'tpt_pg_back', 'tpt_pg_back2'];
    }

}
EOT;
/*<script type="text/javascript" src="$tpt_jsurl/preview-generator.js"></script>*/
$vars['template_data']['head'][] = <<< EOT

<script type="text/javascript">
$script
</script>
EOT;
} else {
    $dec = 'var ';
$script = <<< EOT

if(floatingPGPreview) {
    dc_steps[1] = {node:document.getElementById('dc_step1')};
    dc_steps[2] = {node:document.getElementById('dc_step2')};
    dc_steps[3] = {node:document.getElementById('dc_step3')};
    dc_steps[4] = {node:document.getElementById('dc_step4')};

    setFixedPreview();
    /*
    $('html, body').animate({scrollTop: dc_steps[2].node.offsetTop + init_v_offset + 'px' }, 2000,function(){ correctPreviewPos(); });
    */
}

$dec preview_bgs = ['pg_bg'];
$dec preview_ids = [];
EOT;

if(isDev()) {
    foreach($msg_module->idstr as $msg) {
        $tmtvar = $msg['jsvarname_timeout'];
$script .= <<< EOT
$dec $tmtvar;
EOT;
    }

} else {
$script .= <<< EOT
$dec front_tmt;
$dec front2_tmt;
$dec back_tmt;
$dec back2_tmt;
EOT;
}

//$dec pgx4 = $pgx4;
//$dec pgy4 = $pgy4;
$script .= <<< EOT
$dec pgBGWidth = $hardBGW;
$dec pgBGHeight = $hardBGH;

$dec pg_x = $pg_x;
//$dec pg_y = $pg_y;
$dec pg_yp = $pgHeightMessage;
$dec pg_yf = $pg_yf;
$dec pg_yb = $pg_yb;
$dec pg_fx = $pg_fx;
$dec pg_fy = $pg_fy;
//$dec pg_ffy = $pg_y;
$dec pg_tpad = $pgPaddingTop;
$dec pg_bpad = $pgPaddingBottom;

$dec pg_default_fg_color = '$default_foreground_color';
$dec lights_off_color = '$lights_off_color';
$dec green_glow_color = '$green_glow_color';
$dec blue_glow_color = '$blue_glow_color';
$dec pg_defaulttype = $default_type;
$dec pg_defaultstyle = $default_style;
$dec pg_defaultfont = '$default_font';
$dec pg_defaultbandcolor = '$default_band_color';
$dec pg_defaultmessagecolor = '$default_message_color';


$dec preview_fss = [];
preview_fss['tpt_pg_front'] = '$fs1';
preview_fss['tpt_pg_front2'] = '$fs2';
preview_fss['tpt_pg_back'] = '$fs3';
preview_fss['tpt_pg_back2'] = '$fs4';
$dec preview_dms = [];
preview_dms['tpt_pg_front'] = '$dm1';
preview_dms['tpt_pg_front2'] = '$dm2';
preview_dms['tpt_pg_back'] = '$dm3';
preview_dms['tpt_pg_back2'] = '$dm4';

$dec front_rows = $pgFrontRows;
$dec back_rows = $pgBackRows;

$dec all_preview_ids = ['tpt_pg_front', 'tpt_pg_front2', 'tpt_pg_back', 'tpt_pg_back2'];

if(front_rows == 1) {
    if(back_rows == 1) {
        preview_ids = ['tpt_pg_front', 'tpt_pg_back'];
    } else {
        preview_ids = ['tpt_pg_front', 'tpt_pg_back', 'tpt_pg_back2'];
    }
} else {
    if(back_rows == 1) {
        preview_ids = ['tpt_pg_front', 'tpt_pg_front2', 'tpt_pg_back'];
    } else {
        preview_ids = ['tpt_pg_front', 'tpt_pg_front2', 'tpt_pg_back', 'tpt_pg_back2'];
    }

}

init_client_val();
tb_init('a.thickbox, area.thickbox, input.thickbox');
EOT;

$vars['environment']['ajax_result']['exec_script'][] = $script;
}
}

        return $preview;
    }




    static function messagePreviewHTML(&$vars, $pgconf=array()) {
        //var_dump($pgconf);die();
        $tpt_baseurl = $vars['config']['baseurl'];
        $tpt_imagesurl = $vars['config']['images_url'];


$types_module = getModule($vars, "BandType");
$data_module = getModule($vars, "BandData");
$sizes_module = getModule($vars, "BandSize");

//var_dump($pgconf);die();
//var_dump($pgTextCont);die();
extract($pgconf);

$previewtime = time();

$default_type = DEFAULT_TYPE;
$default_style = DEFAULT_STYLE;
$default_font = DEFAULT_FONT_NAME;

$pgType = (!empty($pgType)?$pgType:DEFAULT_TYPE);
$pgStyle = (!empty($pgStyle)?$pgStyle:DEFAULT_STYLE);
//var_dump($pgStyle);die();
$pgFont = (!empty($pgFont)?$pgFont:DEFAULT_FONT_NAME);
//var_dump($pgFont);die();
$pgFrontRows = (!empty($pgFrontRows)?$pgFrontRows:1);
$pgBackRows = (!empty($pgBackRows)?$pgBackRows:1);
//var_dump($pgTextCont);die();
$pgTextCont = (!empty($pgTextCont)?intval($pgTextCont, 10):0);
$pgTextBackMsg = (!empty($pgTextBackMsg)?intval($pgTextBackMsg, 10):0);
$pgBandColor = (!empty($pgBandColor)?$pgBandColor:'-1:'.DEFAULT_BAND_COLOR);
$pgMessageColor = (!empty($pgMessageColor)?$pgMessageColor:'-1:'.DEFAULT_MESSAGE_COLOR);
$pgMessage = isset($pgMessage)?urlencode($pgMessage):'';
/*
if(empty($bm) && empty($bm2)) {
    $pgTextCont = 1;
}
*/
if(!empty($pgFrontMessage2)) {
    $pgFrontRows = 2;
} else {
    $pgFrontMessage2 = DEFAULT_MESSAGE_FRONT2;
}
if(!empty($pgBackMessage2)) {
    $pgBackRows = 2;
} else {
    $pgBackMessage2 = DEFAULT_MESSAGE_BACK2;
}


$pgClipartLeft = !empty($pgClipartFrontLeft)?intval($pgClipartFrontLeft, 10):0;
$pgClipartRight = !empty($pgClipartFrontRight)?intval($pgClipartFrontRight, 10):0;

$pgCutAway = !empty($pgCutAway)?intval($pgCutAway, 10):0;


$pgDir = $data_module->typeStyle[$pgType][$pgStyle]['preview_folder'];
$x_bg = '100%';
$y_bg = '100%';
$x2_bg = '-100px';
$y2_bg = '-100px';

$plainimage = 'plain.png';
/*
if($pgStyle == 7)
    $plainimage = 'plain-dual.png';
*/
$pgBandImg = TPT_IMAGES_DIR.DIRECTORY_SEPARATOR.'preview'.DIRECTORY_SEPARATOR.$pgDir.DIRECTORY_SEPARATOR.$plainimage;

if(is_file($pgBandImg)) {
$pgBandImgSize = getimagesize($pgBandImg);
//var_dump($pgBandImgSize);die();

$x_bg = intval($pgBandImgSize[0], 10).'px';
$y_bg = intval($pgBandImgSize[1], 10).'px';
}

$band_sizes = explode(',', $types_module->moduleData['id'][$pgType]['available_sizes_id']);
$initsize = reset($band_sizes);

//var_dump($initsize);die();
$band_length = intval($sizes_module->moduleData['id'][$initsize]['milimeters'], 10);
$band_width = floatval($types_module->moduleData['id'][$pgType]['width_mm']);
$scale = 4;

//tpt_dump($pgType);
//tpt_dump($pgStyle, true);
//tpt_dump($data_module->typeStyle[$pgType][$pgStyle], true);
$hardW = $data_module->typeStyle[$pgType][$pgStyle]['preview_width'];
$hardH = $data_module->typeStyle[$pgType][$pgStyle]['preview_height'];
$hardPT = $data_module->typeStyle[$pgType][$pgStyle]['preview_bg_toppadding'];
$hardPB = $data_module->typeStyle[$pgType][$pgStyle]['preview_bg_bottompadding'];
$hardPL = $data_module->typeStyle[$pgType][$pgStyle]['preview_leftpadding'];
$hardPR = $data_module->typeStyle[$pgType][$pgStyle]['preview_rightpadding'];
//tpt_dump($hardPL);
//tpt_dump($hardPR, true);


$pgMPadTop = $data_module->typeStyle[$pgType][$pgStyle]['preview_toppadding'];
$pgMPadBottom = $data_module->typeStyle[$pgType][$pgStyle]['preview_bottompadding'];

/*
if((($pgStyle == 7) && ($pgType == 5)) || ($pgStyle == 8)) {
    $hardPL = 60;
    $hardPR = 60;
    $pgMPadTop = 13;
    $pgMPadBottom = 11;
    $hardPT = 5;
    $hardPB = 5;
} else if(($pgType == 1) && ($pgStyle == 7)) {
    //$hardPT = 50;
    //$hardPB = 50;
}
*/

//die('asdasdasdas');
if(!empty($data_module->typeStyle[$pgType][$pgStyle]['preview_css_background_fix_x']))
        $x_bg = $data_module->typeStyle[$pgType][$pgStyle]['preview_css_background_fix_x'];
if(!empty($data_module->typeStyle[$pgType][$pgStyle]['preview_css_background_fix_y']))
        $y_bg = $data_module->typeStyle[$pgType][$pgStyle]['preview_css_background_fix_y'];
if(!empty($data_module->typeStyle[$pgType][$pgStyle]['preview_css_background_fix_x2']))
        $x2_bg = $data_module->typeStyle[$pgType][$pgStyle]['preview_css_background_fix_x2'];
if(!empty($data_module->typeStyle[$pgType][$pgStyle]['preview_css_background_fix_y2']))
        $y2_bg = $data_module->typeStyle[$pgType][$pgStyle]['preview_css_background_fix_y2'];


$pgWidth = isset($pgWidth)?intval($pgWidth, 10):$hardW;
//$pgWidth = isset($pgWidth)?intval($pgWidth, 10):round($band_length*$scale);
$pgHeight = isset($pgHeight)?intval($pgHeight, 10):$hardH;
//$pgHeight = isset($pgHeight)?intval($pgHeight, 10):round($band_width*$scale);
$pgPaddingTop = isset($pgPaddingTop)?intval($pgPaddingTop, 10):$hardPT;
$pgPaddingBottom = isset($pgPaddingBottom)?intval($pgPaddingBottom, 10):$hardPB;
$pgPaddingLeft = isset($pgPaddingLeft)?intval($pgPaddingLeft, 10):$hardPL;
$pgPaddingRight = isset($pgPaddingRight)?intval($pgPaddingRight, 10):$hardPR;

$plainimage = 'plain.png';
/*
if($pgStyle == 7)
    $plainimage = 'plain-dual.png';
*/
$pgOutlineFile = isset($pgOutlineFile)?$pgOutlineFile:$plainimage;


$pgFullPreview = isset($pgFullPreview)?intval($pgFullPreview, 10):1;
$pgEnableJavascript = isset($pgEnableJavascript)?intval($pgEnableJavascript, 10):0;
$pgAjaxJavascript = isset($pgAjaxJavascript)?intval($pgAjaxJavascript, 10):0;






$UEpgBandColor = urlencode($pgBandColor);
$UEpgMessageColor = urlencode($pgMessageColor);

$pgWidthProcessed = $pgWidth - ($pgPaddingLeft + $pgPaddingRight);
$pg_fx = $pgWidthProcessed;
$pg_x = $pgWidthProcessed;






$pgHeightProcessed = $pgHeight - ($pgPaddingTop + $pgPaddingBottom);
$pgHeightMessage = $pgHeightProcessed - ($pgMPadTop + $pgMPadBottom);
$pgHeightMessageHalf = floor($pgHeightMessage/2);
$pg_yf = $pg_yb = $pgHeightMessage;
$pg_fy = $pgHeight;

/*
if(($pgType == 5) && $pgFullPreview) {
    $x_bg = '694px';
    $y_bg = '92px';
    $x2_bg = '-99px';
    $y2_bg = '-99px';

    $scale = 3;
    $pgWidth = $band_length*$scale;
    $pg_fx = $pgWidthProcessed = 695;
    $pgWidth = 660;
    $pg_x = 695;

    $pgPaddingTop = 20;
    $pgPaddingBottom = 20;
    $pgHeight = round(98);
    $pgHeightProcessed = $pgHeight - ($pgPaddingTop + $pgPaddingBottom);
    $pgHeightProcessedHalf = round($pgHeightProcessed/2);
    $pg_y = $pg_yf = $pg_yb = $pgHeightProcessed;
    $pg_fy = $pgHeight;

}
*/



$pgSeparatorImg = 'none';
//$pgFattr = ' class="frontPreview height-'.$pgHeightProcessed.'" style=""';
//$pgFattr = ' class="frontPreview padding-top-'.$pgMPadTop.' padding-bottom-'.$pgMPadBottom.' height-'.$pgHeightProcessed.'" style=""';
//$pgFattr = ' class="frontPreview height-'.$pgHeightProcessed.'" style=""';
$pgFattr = ' class="frontPreview height-'.$pgHeightMessage.'" style=""';
//$pgBattr = ' class="backPreview display-none float-right '.$bdisplay.' height-'.$pgHeightProcessed.'" style="width: 50%;"';
//$pgBattr = ' class="backPreview display-none float-right '.$bdisplay.' padding-top-'.$pgMPadTop.' padding-bottom-'.$pgMPadBottom.' height-'.$pgHeightProcessed.'" style="width: 50%;"';
//$pgBattr = ' class="backPreview display-none float-right '.$bdisplay.' height-'.$pgHeightProcessed.'" style="width: 50%;"';
$pgBattr = ' class="backPreview display-none float-right height-'.$pgHeightMessage.'" style="width: 50%;"';
//tpt_dump($pgTextBackMsg, false);
//tpt_dump($pgTextCont, true);
if($pgTextBackMsg && !$pgTextCont) {
    //$pgFattr = ' class="frontPreview float-left height-'.$pgHeightProcessed.'" style="width: 50%;"';
    //$pgFattr = ' class="frontPreview float-left padding-top-'.$pgMPadTop.' padding-bottom-'.$pgMPadBottom.' height-'.$pgHeightProcessed.'" style="width: 50%;"';
    //$pgFattr = ' class="frontPreview float-left height-'.$pgHeightProcessed.'" style="width: 50%;"';
    $pgFattr = ' class="frontPreview float-left height-'.$pgHeightMessage.'" style="width: 50%;"';
    //$pgBattr = ' class="backPreview float-right '.$bdisplay.' height-'.$pgHeightProcessed.'" style="width: 50%;"';
    //$pgBattr = ' class="backPreview float-right '.$bdisplay.' padding-top-'.$pgMPadTop.' padding-bottom-'.$pgMPadBottom.' height-'.$pgHeightProcessed.'" style="width: 50%;"';
    //$pgBattr = ' class="backPreview float-right '.$bdisplay.' height-'.$pgHeightProcessed.'" style="width: 50%;"';
    $pgBattr = ' class="backPreview float-right height-'.$pgHeightMessage.'" style="width: 50%;"';
    $pg_x = floor($pg_x/2);
    $pgSeparatorImg = 'url('.TPT_IMAGES_URL.'/preview/separator-1x1.png)';
}

//var_dump($pgFrontRows);
//var_dump($pgBackRows);
//die();

$emptyimg = "$tpt_imagesurl/preview/empty.png";
$dm1 = DEFAULT_MESSAGE_FRONT;
$fs1 = defined(DEFAULT_MESSAGE_FRONT_POINTSIZE)?intval(DEFAULT_MESSAGE_FRONT_POINTSIZE, 10):0;
$fs1 = $types_module->moduleData['id'][$pgType]['preview_message_front_fontsize'];
//var_dump($fs1);die();
$img1id = 'elmid=tpt_pg_front_message';
$img1 = "$tpt_baseurl/generate-preview?bandType=$pgType&amp;pg_x=$pg_x&amp;pg_y=$pg_yf&amp;fontSize=$fs1&amp;text=$pgFrontMessage&amp;font=$pgFont&amp;bandType=$pgType&amp;bandStyle=$pgStyle&amp;textColor=$UEpgMessageColor&amp;lclipart=$pgClipartFrontLeft&amp;rclipart=$pgClipartFrontRight&amp;type=plain&amp;$img1id&amp;timestamp=$previewtime";
$cfile = TPT_IMAGES_DIR.DIRECTORY_SEPARATOR.'preview'.DIRECTORY_SEPARATOR.'cached'.DIRECTORY_SEPARATOR.'plain'.DIRECTORY_SEPARATOR.'plain-'.$pg_x.'x'.$pg_yf.'x'.$fs1.'x0'.'-'.str_replace('/', '_', base64_encode(urldecode($pgFrontMessage))).'-'.str_replace('/', '_', base64_encode($pgFont)).'-style'.$pgStyle.'-'.str_replace('/', '_', base64_encode($pgMessageColor)).'.png';
//if($_SERVER['REMOTE_ADDR'] == '109.160.0.218') {
//    var_dump(is_file($cfile));//die();
//    var_dump(urldecode($pgFrontMessage));//die();
//    var_dump(str_replace('/', '_', base64_encode(urldecode($pgFrontMessage))));//die();
//    var_dump($cfile);//die();
//}
if(empty($_GET) && is_file($cfile) && (($pgType != 5) || ($pgStyle != 7))) {
    $filename = 'plain-'.$pg_x.'x'.$pg_yf.'x'.$fs1.'x0'.'-'.str_replace('/', '_', base64_encode(urldecode($pgFrontMessage))).'-'.str_replace('/', '_', base64_encode($pgFont)).'-style'.$pgStyle.'-'.str_replace('/', '_', base64_encode($pgMessageColor)).'.png';
    //header('Content-type: image/png');
    $img1 = "$tpt_imagesurl/preview/cached/plain/".urlencode($filename);
}
$dm2 = DEFAULT_MESSAGE_FRONT2;
$fs2 = defined(DEFAULT_MESSAGE_FRONT2_POINTSIZE)?intval(DEFAULT_MESSAGE_FRONT2_POINTSIZE, 10):0;
$fs2 = $types_module->moduleData['id'][$pgType]['preview_message_front2_fontsize'];
$img2 = $emptyimg;

$dm3 = DEFAULT_MESSAGE_BACK;
$fs3 = defined(DEFAULT_MESSAGE_BACK_POINTSIZE)?intval(DEFAULT_MESSAGE_BACK_POINTSIZE, 10):0;
$fs3 = $types_module->moduleData['id'][$pgType]['preview_message_back_fontsize'];
$img3id = 'elmid=tpt_pg_back_message';
$img3 = "$tpt_baseurl/generate-preview?bandType=$pgType&amp;pg_x=$pg_x&amp;pg_y=$pg_yb&amp;fontSize=$fs3&amp;text=$pgBackMessage&amp;font=$pgFont&amp;bandType=$pgType&amp;bandStyle=$pgStyle&amp;textColor=$UEpgMessageColor&amp;lclipart=$pgClipartBackLeft&amp;rclipart=$pgClipartBackRight&amp;type=plain&amp;$img3id&amp;timestamp=$previewtime";
$cfile = TPT_IMAGES_DIR.DIRECTORY_SEPARATOR.'preview'.DIRECTORY_SEPARATOR.'cached'.DIRECTORY_SEPARATOR.'plain'.DIRECTORY_SEPARATOR.'plain-'.$pg_x.'x'.$pg_yb.'x'.$fs3.'x0'.'-'.str_replace('/', '_', base64_encode(urldecode($pgBackMessage))).'-'.str_replace('/', '_', base64_encode($pgFont)).'-style'.$pgStyle.'-'.str_replace('/', '_', base64_encode($pgMessageColor)).'.png';
if(empty($_GET) && is_file($cfile) && (($pgType != 5) || ($pgStyle != 7))) {
    $filename = 'plain-'.$pg_x.'x'.$pg_yb.'x'.$fs3.'x0'.'-'.str_replace('/', '_', base64_encode(urldecode($pgBackMessage))).'-'.str_replace('/', '_', base64_encode($pgFont)).'-style'.$pgStyle.'-'.str_replace('/', '_', base64_encode($pgMessageColor)).'.png';
    //header('Content-type: image/png');
    $img3 = "$tpt_imagesurl/preview/cached/plain/".urlencode($filename);
}
$dm4 = DEFAULT_MESSAGE_BACK2;
$fs4 = defined(DEFAULT_MESSAGE_BACK2_POINTSIZE)?intval(DEFAULT_MESSAGE_BACK2_POINTSIZE, 10):0;
$fs4 = $types_module->moduleData['id'][$pgType]['preview_message_back2_fontsize'];
$img4 = $emptyimg;
$pgHeightFront = 'height-'.$pgHeightMessage;
$pgHeightFront2 = 'height-'.$pgHeightMessageHalf;
$pgClassFront2 = 'display-none';

if($pgFrontRows == 2) {
    $pgHeightFront = $pgHeightFront2;
    $pgClassFront2 = 'display-block';
    $pg_yf = $pgHeightMessageHalf;
    $img2id = 'elmid=tpt_pg_front2_message';
    $img2 = "$tpt_baseurl/generate-preview?bandType=$pgType&amp;pg_x=$pg_x&amp;pg_y=$pg_yf&amp;fontSize=$fs2&amp;text=$pgFrontMessage2&amp;font=$pgFont&amp;bandType=$pgType&amp;bandStyle=$pgStyle&amp;textColor=$UEpgMessageColor&amp;lclipart=$pgClipartFrontLeft2&amp;rclipart=$pgClipartFrontRight2&amp;type=plain&amp;$img2id&amp;timestamp=$previewtime";
    $cfile = TPT_IMAGES_DIR.DIRECTORY_SEPARATOR.'preview'.DIRECTORY_SEPARATOR.'cached'.DIRECTORY_SEPARATOR.'plain'.DIRECTORY_SEPARATOR.'plain-'.$pg_x.'x'.$pg_yf.'x'.$fs2.'x0'.'-'.str_replace('/', '_', base64_encode(urldecode($pgFrontMessage2))).'-'.str_replace('/', '_', base64_encode($pgFont)).'-style'.$pgStyle.'-'.str_replace('/', '_', base64_encode($pgMessageColor)).'.png';
    if(empty($_GET) && is_file($cfile) && (($pgType != 5) || ($pgStyle != 7))) {
        $filename = 'plain-'.$pg_x.'x'.$pg_yf.'x'.$fs2.'x0'.'-'.str_replace('/', '_', base64_encode(urldecode($pgFrontMessage2))).'-'.str_replace('/', '_', base64_encode($pgFont)).'-style'.$pgStyle.'-'.str_replace('/', '_', base64_encode($pgMessageColor)).'.png';
        //header('Content-type: image/png');
        $img2 = "$tpt_imagesurl/preview/cached/plain/".urlencode($filename);
    }
}


$pgHeightBack = 'height-'.$pgHeightMessage;
$pgHeightBack2 = 'height-'.$pgHeightMessageHalf;
$pgClassBack2 = 'display-none';

if($pgBackRows == 2) {
    $pgHeightBack = $pgHeightBack2;
    $pgClassBack2 = 'display-block';
    $pg_yb = $pgHeightMessageHalf;
    $img4id = 'elmid=tpt_pg_back2_message';
    $img4 = "$tpt_baseurl/generate-preview?bandType=$pgType&amp;pg_x=$pg_x&amp;pg_y=$pg_yb&amp;fontSize=$fs4&amp;text=$pgBackMessage2&amp;font=$pgFont&amp;bandType=$pgType&amp;bandStyle=$pgStyle&amp;textColor=$UEpgMessageColor&amp;lclipart=$pgClipartBackLeft2&amp;rclipart=$pgClipartBackRight2&amp;type=plain&amp;$img4id&amp;timestamp=$previewtime";
    $cfile = TPT_IMAGES_DIR.DIRECTORY_SEPARATOR.'preview'.DIRECTORY_SEPARATOR.'cached'.DIRECTORY_SEPARATOR.'plain'.DIRECTORY_SEPARATOR.'plain-'.$pg_x.'x'.$pg_yb.'x'.$fs4.'x0'.'-'.str_replace('/', '_', base64_encode(urldecode($pgBackMessage2))).'-'.str_replace('/', '_', base64_encode($pgFont)).'-style'.$pgStyle.'-'.str_replace('/', '_', base64_encode($pgMessageColor)).'.png';
    if(empty($_GET) && is_file($cfile) && (($pgType != 5) || ($pgStyle != 7))) {
        $filename = 'plain-'.$pg_x.'x'.'x'.$fs4.'x0'.$pg_yb.'-'.str_replace('/', '_', base64_encode(urldecode($pgBackMessage2))).'-'.str_replace('/', '_', base64_encode($pgFont)).'-style'.$pgStyle.'-'.str_replace('/', '_', base64_encode($pgMessageColor)).'.png';
        //header('Content-type: image/png');
        $img4 = "$tpt_imagesurl/preview/cached/plain/".urlencode($filename);
    }
}


//$pgx4 = round($pg_x*2.2);
//$pgy4 = round($pg_y*2.2);
//$pgx4 = round($pg_x*1.6);
//$pgy4 = round($pg_y*1.6);


$bgWidth = $pgWidth;
if($pgType == 8) {
    $bgWidth = 280;
}
$pgBandBG = getModule($vars, "BandColor")->getBandBGStyle($vars, $pgBandColor, $pgMessageColor, $bgWidth, $pgHeightProcessed);
$pgBandBGProps = getModule($vars, "BandColor")->getColorProps($vars, $pgBandColor);
$pgExtraDualLayerBG = 'background: transparent none no-repeat scroll center center;';
if((($pgStyle == 7) && ($pgType == 5)) || ($pgStyle == 8) || !empty($pgCutAway)) {
    //var_dump(43);die();
    $time = time();
    $pgExtraDualLayerBG = 'background: transparent url('.BASE_URL.'/generate-preview?pg_x=595&amp;pg_y=71&amp;type=dualslaplayer&amp;timestamp'.$time.'&amp;textColor='.$UEpgMessageColor.'&amp;invert_dual=0) no-repeat scroll center center;';
    //var_dump($pgExtraDualLayerBG);die();
} else if(($pgType == 1) && $pgBandBGProps['notched']) {
    $time = time();
    $pgExtraDualLayerBG = 'background: transparent url('.BASE_URL.'/generate-preview?type=dualquartlayer&amp;timestamp'.$time.'&amp;textColor='.$UEpgMessageColor.'&invert_dual=0) repeat-x scroll center center;';
} else if(($pgType == 2) && $pgBandBGProps['notched']) {
    $time = time();
    $pgExtraDualLayerBG = 'background: transparent url('.BASE_URL.'/generate-preview?type=dualhalflayer&amp;timestamp='.$time.'&amp;textColor='.$UEpgMessageColor.'&amp;invert_dual=0) repeat-x scroll center center;';
}

//var_dump($pgBandColor);//die();
//var_dump($pgBandBG);die();

$lights_off_color = LIGHTS_OFF_COLOR;
$default_foreground_color = DEFAULT_FOREGROUND_COLOR;
$green_glow_color = GREEN_GLOW_COLOR;
$blue_glow_color = BLUE_GLOW_COLOR;

$preview = '';

//<div class="amz_green font-size-16 padding-top-10" style="text-align:center;font-family: TODAYSHOP-BOLDITALIC,arial;">Front Preview</div>
$preview .= <<< EOT
    <div class="width-$pgWidth position-relative overflow-hidden clearBoth" id="pg_container">
        <div id="pg_separator" class="display-none top-0 right-0 bottom-0 left-0 position-absolute background-position-CC" style="z-index: 3; background-image: $pgSeparatorImg; background-repeat: no-repeat;"></div>
        <div id="pg_band_outline" class="top-0 right-0 bottom-0 left-0 position-absolute" style="z-index: 2; background-image: url($tpt_imagesurl/preview/$pgDir/$pgOutlineFile); background-repeat: no-repeat;"></div>
EOT;

if($pgFullPreview) {
$preview .= <<< EOT
    <div id="pg_fg_right" class="top-0 bottom-0 position-absolute width-100" style="left: $x_bg; z-index: 2; background-color: #$default_foreground_color;"></div>
    <div id="pg_fg_bottom" class="right-0 left-0 position-absolute height-100" style="top: $y_bg; z-index: 2; background-color: #$default_foreground_color;"></div>
    <div id="pg_fg_left" class="top-0 bottom-0 position-absolute width-100" style="left: $x2_bg; z-index: 2; background-color: #$default_foreground_color;"></div>
    <div id="pg_fg_top" class="right-0 left-0 position-absolute height-100" style="top: $y2_bg; z-index: 2; background-color: #$default_foreground_color;"></div>
EOT;
}
$preview .= <<< EOT
    <div class="position-relative background-repeat-repeat clearFix" style="z-index: 1;background-image: url($tpt_imagesurl/clearband.png);" id="pg_subcontainer">
        <div class="position-relative padding-top-$pgPaddingTop padding-bottom-$pgPaddingBottom clearFix background-position-CC background-repeat-no-repeat" style="z-index: 1;$pgBandBG" id="pg_bg">
            <div class=" padding-top-$pgMPadTop padding-bottom-$pgMPadBottom padding-left-$pgPaddingLeft padding-right-$pgPaddingRight position-relative clearFix background-position-CC background-repeat-no-repeat" style="$pgExtraDualLayerBG" id="pg_dl_extra">
                <div$pgFattr id="tpt_pg_front_container">
                    <div id="tpt_pg_front_parent" class="$pgHeightFront">
                        <img title="Front Preview" id="tpt_pg_front" style="max-width: 100%; max-height: 100%;" src="$img1" />
                    </div>
                    <div id="tpt_pg_front2_parent" class="$pgClassFront2 $pgHeightFront2">
                        <img title="Front Line 2 Preview" id="tpt_pg_front2" style="max-width: 100%; max-height: 100%;" src="$img2" />
                    </div>
                </div>
                <div$pgBattr id="tpt_pg_back_container">
                    <div id="tpt_pg_back_parent" class="$pgHeightBack">
                        <img title="Back Preview" id="tpt_pg_back" style="max-width: 100%; max-height: 100%;" src="$img3" />
                    </div>
                    <div id="tpt_pg_back2_parent" class="$pgClassBack2 $pgHeightBack2">
                        <img title="Back Line 2 Preview" id="tpt_pg_back2" style="max-width: 100%; max-height: 100%;" src="$img4" />
                    </div>
                </div>
            </div>

        </div>
    </div>
EOT;
//if($pgFullPreview) {
$preview .= <<< EOT
</div>
EOT;
//}


if($pgEnableJavascript) {
if(!$pgAjaxJavascript) {


$script = <<< EOT
var preview_bgs = ['pg_bg'];
var preview_ids = [];

var front_tmt;
var front2_tmt;
var back_tmt;
var back2_tmt;

//var pgx4 = $pgx4;
//var pgy4 = $pgy4;
var pgBGWidth = $pgWidth;
var pgBGHeight = $pgHeightProcessed;

var pg_x = $pg_x;
//var pg_y = $pg_y;

var pg_yp = $pgHeightMessage;
var pg_yf = $pg_yf;
var pg_yb = $pg_yb;
var pg_fx = $pg_fx;
var pg_fy = $pg_fy;
//var pg_ffy = $pg_y;
var pg_tpad = $pgPaddingTop;
var pg_bpad = $pgPaddingBottom;

var pg_default_fg_color = '$default_foreground_color';
var lights_off_color = '$lights_off_color';
var green_glow_color = '$green_glow_color';
var blue_glow_color = '$blue_glow_color';
var pg_defaulttype = $default_type;
var pg_defaultstyle = $default_style;
var pg_defaultfont = '$default_font';


var preview_fss = [];
preview_fss['tpt_pg_front'] = '$fs1';
preview_fss['tpt_pg_front2'] = '$fs2';
preview_fss['tpt_pg_back'] = '$fs3';
preview_fss['tpt_pg_back2'] = '$fs4';
var preview_dms = [];
preview_dms['tpt_pg_front'] = '$dm1';
preview_dms['tpt_pg_front2'] = '$dm2';
preview_dms['tpt_pg_back'] = '$dm3';
preview_dms['tpt_pg_back2'] = '$dm4';

var front_rows = $pgFrontRows;
var back_rows = $pgBackRows;


var all_preview_ids = ['tpt_pg_front', 'tpt_pg_front2', 'tpt_pg_back', 'tpt_pg_back2'];

if(front_rows == 1) {
    if(back_rows == 1) {
        preview_ids = ['tpt_pg_front', 'tpt_pg_back'];
    } else {
        preview_ids = ['tpt_pg_front', 'tpt_pg_back', 'tpt_pg_back2'];
    }
} else {
    if(back_rows == 1) {
        preview_ids = ['tpt_pg_front', 'tpt_pg_front2', 'tpt_pg_back'];
    } else {
        preview_ids = ['tpt_pg_front', 'tpt_pg_front2', 'tpt_pg_back', 'tpt_pg_back2'];
    }

}
EOT;

$vars['template_data']['head'][] = <<< EOT
<script type="text/javascript" src="$tpt_baseurl/js/preview-generator.js"></script>
<script type="text/javascript">
$script
</script>
EOT;
} else {
    $dec = 'var ';
$vars['environment']['ajax_result']['exec_script'][] = <<< EOT

if(floatingPGPreview) {
    dc_steps[1] = {node:document.getElementById('dc_step1')};
    dc_steps[2] = {node:document.getElementById('dc_step2')};
    dc_steps[3] = {node:document.getElementById('dc_step3')};
    dc_steps[4] = {node:document.getElementById('dc_step4')};

    setFixedPreview();
    /*
    $('html, body').animate({scrollTop: dc_steps[2].node.offsetTop + init_v_offset + 'px' }, 2000,function(){ correctPreviewPos(); });
    */
}

$dec preview_bgs = ['pg_bg'];
$dec preview_ids = [];

$dec front_tmt;
$dec front2_tmt;
$dec back_tmt;
$dec back2_tmt;

//$dec pgx4 = $pgx4;
//$dec pgy4 = $pgy4;

$dec pgBGWidth = $bgWidth;
$dec pgBGHeight = $pgHeightProcessed;

$dec pg_x = $pg_x;
//$dec pg_y = $pg_y;
$dec pg_yp = $pgHeightMessage;
$dec pg_yf = $pg_yf;
$dec pg_yb = $pg_yb;
$dec pg_fx = $pg_fx;
$dec pg_fy = $pg_fy;
//$dec pg_ffy = $pg_y;
$dec pg_tpad = $pgPaddingTop;
$dec pg_bpad = $pgPaddingBottom;

$dec pg_default_fg_color = '$default_foreground_color';
$dec lights_off_color = '$lights_off_color';
$dec green_glow_color = '$green_glow_color';
$dec blue_glow_color = '$blue_glow_color';
$dec pg_defaulttype = $default_type;
$dec pg_defaultstyle = $default_style;
$dec pg_defaultfont = '$default_font';


$dec preview_fss = [];
preview_fss['tpt_pg_front'] = '$fs1';
preview_fss['tpt_pg_front2'] = '$fs2';
preview_fss['tpt_pg_back'] = '$fs3';
preview_fss['tpt_pg_back2'] = '$fs4';
$dec preview_dms = [];
preview_dms['tpt_pg_front'] = '$dm1';
preview_dms['tpt_pg_front2'] = '$dm2';
preview_dms['tpt_pg_back'] = '$dm3';
preview_dms['tpt_pg_back2'] = '$dm4';

$dec front_rows = $pgFrontRows;
$dec back_rows = $pgBackRows;

$dec all_preview_ids = ['tpt_pg_front', 'tpt_pg_front2', 'tpt_pg_back', 'tpt_pg_back2'];

if(front_rows == 1) {
    if(back_rows == 1) {
        preview_ids = ['tpt_pg_front', 'tpt_pg_back'];
    } else {
        preview_ids = ['tpt_pg_front', 'tpt_pg_back', 'tpt_pg_back2'];
    }
} else {
    if(back_rows == 1) {
        preview_ids = ['tpt_pg_front', 'tpt_pg_front2', 'tpt_pg_back'];
    } else {
        preview_ids = ['tpt_pg_front', 'tpt_pg_front2', 'tpt_pg_back', 'tpt_pg_back2'];
    }

}

init_client_val();
tb_init('a.thickbox, area.thickbox, input.thickbox');
EOT;
}
}

        return $preview;
    }
}





tpt_PreviewGenerator::$gClassesDir = TPT_INCLUDES_DIR.DIRECTORY_SEPARATOR.'gClasses';