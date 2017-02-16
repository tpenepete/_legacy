<?php

defined('TPT_INIT') or die('access denied');


//die();

if(strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
    $enabled = $_POST['clipart_enable'];
    if ($enabled && strtolower($enabled) !== "false") {
       $enabled = true;
    } else {
       $enabled = false;
    }
    $message_span = intval($_POST['message_span'], 10);
    $front_rows = intval($_POST['front_rows'], 10);
    $back_rows = intval($_POST['back_rows'], 10);
    
    if($enabled) {
        $fl_select = getModule($tpt_vars, "BandClipart")->Clipart_Select($tpt_vars, 0, 'fl' );
        $fr_select = getModule($tpt_vars, "BandClipart")->Clipart_Select($tpt_vars, 0, 'fr');
        $fl2_select = getModule($tpt_vars, "BandClipart")->Clipart_Select($tpt_vars, 0, 'fl2');
        $fr2_select = getModule($tpt_vars, "BandClipart")->Clipart_Select($tpt_vars, 0, 'fr2');
        $bl_select = getModule($tpt_vars, "BandClipart")->Clipart_Select($tpt_vars, 0, 'bl');
        $br_select = getModule($tpt_vars, "BandClipart")->Clipart_Select($tpt_vars, 0, 'br');
        $bl2_select = getModule($tpt_vars, "BandClipart")->Clipart_Select($tpt_vars, 0, 'bl2');
        $br2_select = getModule($tpt_vars, "BandClipart")->Clipart_Select($tpt_vars, 0, 'br2');
        $fclip = '';
        $fclip2 = '';
        $bclip = '';
        $bclip2 = '';
        switch($message_span) {
            case 2:
                $fclip = $fl_select.$fr_select;
                $bclip = $bl_select.$br_select;
                if($front_rows == 2) {
                    $fclip2 = $fl2_select.$fr2_select;
                } else {
                    $fclip2 = '';
                }
                if($back_rows == 2) {
                    $bclip2 = $bl2_select.$br2_select;
                } else {
                    $bclip2 = '';
                }
                break;
            case 1:
            default:
                $fclip = $fl_select.$fr_select;
                $bclip = '';
                $bclip2 = '';
                if($front_rows == 2) {
                    $fclip2 = $fl2_select.$fr2_select;
                } else {
                    $fclip2 = '';
                }
                break;
        }
    } else {
        $fclip = '';
        $fclip2 = '';
        $bclip = '';
        $bclip2 = '';
    }
        
    $tpt_vars['environment']['ajax_result']['update_elements'] = array(
                                                                       'fclip'=>$fclip,
                                                                       'fclip2'=>$fclip2,
                                                                       'bclip'=>$bclip,
                                                                       'bclip2'=>$bclip2
                                                                       );
}