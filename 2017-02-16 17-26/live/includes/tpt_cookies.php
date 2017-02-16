<?php

defined('TPT_INIT') or die('access denied');



if(isset($_COOKIE['panel_opacity'])) {
    tpt_request::setcookie($tpt_vars, 'panel_opacity', $_COOKIE['panel_opacity'], time()+24*60*60*365, '/');
    $pop = ''.intval($_COOKIE['panel_opacity'], 10);
$tpt_vars['template_data']['footer_scripts']['scripts'][] = <<< EOT
minimumopacity=$pop;
EOT;
    $tpt_vars['admin']['template_data']['panel_opacity'] = $pop;
} else {
    $pop = '10';
    if( $iPod || $iPhone || $iPad || $Android || $webOS) {
        $pop = '100';
    }
    tpt_request::setcookie($tpt_vars, 'panel_opacity', $pop, time()+24*60*60*365, '/');

$tpt_vars['template_data']['footer_scripts']['scripts'][] = <<< EOT
minimumopacity=$pop;
EOT;
    $tpt_vars['admin']['template_data']['panel_opacity'] = $pop;
}

if(isset($_COOKIE['panel_position'])) {
    tpt_request::setcookie($tpt_vars, 'panel_position', $_COOKIE['panel_position'], time()+24*60*60*365, '/');
    $pp = 'fixed';
    if($_COOKIE['panel_position'] == 'absolute')
        $pp = 'absolute';

    $tpt_vars['admin']['template_data']['panel_position'] = $pp;
} else {
    tpt_request::setcookie($tpt_vars, 'panel_position', 'fixed', time()+24*60*60*365, '/');
    $pp = 'fixed';

    $tpt_vars['admin']['template_data']['panel_position'] = $pp;
}


if(isset($_COOKIE['panel_left'])) {
    tpt_request::setcookie($tpt_vars, 'panel_left', $_COOKIE['panel_left'], time()+24*60*60*365, '/');
    $pl = ''.intval($_COOKIE['panel_left'], 10);

    $tpt_vars['admin']['template_data']['panel_left'] = $pl;
} else {
    $pl = '';

    $tpt_vars['admin']['template_data']['panel_left'] = $pl;
}

//var_dump($_COOKIE['panel_top']);die();
if(isset($_COOKIE['panel_top'])) {
    tpt_request::setcookie($tpt_vars, 'panel_top', $_COOKIE['panel_top'], time()+24*60*60*365, '/');
    if($tpt_vars['admin']['template_data']['panel_position'] == 'absolute')
        $panel_top = max(intval($_COOKIE['panel_top'], 10), $tpt_vars['config']['admin']['panel_max_top_absolute']);
    else
        $panel_top = max(intval($_COOKIE['panel_top'], 10), 0);

    $tpt_vars['admin']['template_data']['panel_top'] = $panel_top;
} else {

    $top_offset = 100;
    $panel_top = '';
    if($tpt_vars['admin']['template_data']['panel_position'] == 'absolute')
        $panel_top = ''.($top_offset+$tpt_vars['config']['admin']['panel_max_top_absolute']);
    else
       $panel_top =  ''.$top_offset;

    $tpt_vars['admin']['template_data']['panel_top'] = $panel_top;
}
//var_dump($tpt_vars['admin']['template_data']['panel_top']);die();

$pmtf = 0;
//var_dump($_COOKIE['panel_max_top_factor']);die();
if(isset($_COOKIE['panel_max_top_factor'])) {
    $pmtf = max(0, $_COOKIE['panel_max_top_factor']);
    tpt_request::setcookie($tpt_vars, 'panel_max_top_factor', $pmtf, time()+24*60*60*365, '/');
} else {
    tpt_request::setcookie($tpt_vars, 'panel_max_top_factor', $pmtf, time()+24*60*60*365, '/');
}
$tpt_vars['admin']['template_data']['panel_max_top_factor'] = $pmtf;
//var_dump($tpt_vars['admin']['template_data']['panel_max_top_factor']);die();

//tpt_request::setcookie($tpt_vars, 'tpt_admin_active_tab', 'Status', time()-1);die();
//var_dump($_COOKIE['tpt_admin_active_tab']);die();
$tpt_vars['admin']['template_data']['active_tab'] = '';
if(isset($_COOKIE['tpt_admin_active_tab'])) {
    tpt_request::setcookie($tpt_vars, 'tpt_admin_active_tab', $_COOKIE['tpt_admin_active_tab'], time()+24*60*60*365, '/');

    $tpt_vars['admin']['template_data']['active_tab'] = $_COOKIE['tpt_admin_active_tab'];
}
//var_dump($tpt_vars['admin']['template_data']['active_tab']);//die();


$pftw = 0;
//var_dump($_COOKIE['panel_fittow']);die();
if(isset($_COOKIE['panel_fittow'])) {
    $pftw = $_COOKIE['panel_fittow'];
    tpt_request::setcookie($tpt_vars, 'panel_fittow', $pftw, time()+24*60*60*365, '/');
}
$tpt_vars['admin']['template_data']['panel_fittow'] = $pftw;
//var_dump($tpt_vars['admin']['template_data']['panel_fittow']);die();

$pheight = '';
if($tpt_vars['admin']['template_data']['panel_fittow']) {
    //var_dump($_COOKIE['panel_max_top_factor']);die();
    if(isset($_COOKIE['panel_height'])) {
        $pheight = intval($_COOKIE['panel_height'], 10);
        $pheight = max($pheight, 20);
        tpt_request::setcookie($tpt_vars, 'panel_height', $pheight, time()+24*60*60*365, '/');
    } else {
        $pheight = 20;
        tpt_request::setcookie($tpt_vars, 'panel_height', $pheight, time()+24*60*60*365, '/');
    }
} else {
    tpt_request::setcookie($tpt_vars, 'panel_height', '', time()-1, '/');
}
$tpt_vars['admin']['template_data']['panel_height'] = $pheight;

$tpt_vars['template_data']['tpt_logged_in'] = 0;
$tpt_vars['template_data']['tpt_logged_user'] = '';
if(isset($_COOKIE['tpt_logged_user'])) {
    if(!empty($_COOKIE['tpt_logged_user'])) {
        $tpt_vars['template_data']['tpt_logged_in'] = 1;
        $tpt_vars['template_data']['tpt_logged_user'] = $logged_user = $_COOKIE['tpt_logged_user'];
        tpt_request::setcookie($tpt_vars, 'tpt_logged_in', '1', time()+24*60*60*365, '/');
        tpt_request::setcookie($tpt_vars, 'tpt_logged_user', $logged_user, time()+24*60*60*365, '/');
    } else {
        $tpt_vars['template_data']['tpt_logged_in'] = 0;
        $tpt_vars['template_data']['tpt_logged_user'] = '';
        tpt_request::setcookie($tpt_vars, 'tpt_logged_in', '', time()+24*60*60*365, '/');
        tpt_request::setcookie($tpt_vars, 'tpt_logged_user', '', time()+24*60*60*365, '/');
    }
} else {
    $tpt_vars['template_data']['tpt_logged_in'] = 0;
    $tpt_vars['template_data']['tpt_logged_user'] = '';
}


if(isset($_COOKIE['login_return_url'])) {
    $tpt_vars['environment']['login_return_url'] = $_COOKIE['login_return_url'];
} else {
    $tpt_vars['environment']['login_return_url'] = $tpt_vars['url']['handler']->wrap($tpt_vars, '/');
}
//tpt_dump($tpt_vars['environment']['login_return_url']);

if(isset($_COOKIE['logout_return_url'])) {
    $tpt_vars['environment']['logout_return_url'] = $_COOKIE['logout_return_url'];
} else {
    $tpt_vars['environment']['logout_return_url'] = $tpt_vars['url']['handler']->wrap($tpt_vars, '/');
}

if(isset($_COOKIE['future_back_url'])) {
    $tpt_vars['environment']['go_back_url'] = $_COOKIE['future_back_url'];
} else {
    $tpt_vars['environment']['go_back_url'] = $tpt_vars['url']['handler']->wrap($tpt_vars, '/');
}

if(isset($_COOKIE['continue_shopping_url'])) {
    $tpt_vars['environment']['continue_shopping_url'] = $_COOKIE['continue_shopping_url'];
} else {
    $tpt_vars['environment']['continue_shopping_url'] = $tpt_vars['url']['handler']->wrap($tpt_vars, '/');
}
