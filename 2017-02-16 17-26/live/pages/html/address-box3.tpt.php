<?php

defined('TPT_INIT') or die('access denied');

if($i == 'left') {
$address_html .= '<div class="clearBoth" style=""></div>';
$address_html .= '<div class="" style="">';
$i='right';
} else {
$address_html .= '<div class="" style="">';
$i='left';
}
    $address_html .= '<div class="" style="">';
        $address_html .= '<div class="" style="background-color: #FFF;">';
            $address_html .= '<div class="" style="background-color: #FFF;">';
                $address_html .= '<div class="line-height-22 font-size-12 font-weight-bold" style="background-color: #FFF;">';
                
$checked = '';
if($tpt_vars['user']['data']['same_address']) {
    $checked = ' checked="checked"';
}
//var_dump($address_state);
if(!(($address_name == 'shipping') && $tpt_vars['user']['data']['same_address'])) {
    if ($address_names != '') {
        $address_html .= '<div class="height-22 font-size-12 text-align-left" style="">' . $address_names . '</div>';
    }
    if (!empty($address_company)) {
        $address_html .= '<div class="height-22 font-size-12 text-align-left" style="">' . $address_company . '</div>';
    }
    $address_html .= $address_addr;
    if ($address_city != '' || $address_state != '' || $address_zip != '') {
        $address_html .= '<div class="height-22" style="">' . $address_city . ', ' . $address_state . '  ' . $address_zip . '</div>';
    }
    if ($address_country != '') {
        $address_html .= '<div class="height-22" style="">' . $address_country . '</div>';
    }
    if ($address_phone != '') {
        $address_html .= '<div class="height-22" style=""></div>';
        $address_html .= '<div class="height-22" style="">' . $address_phone . '</div>';
    }
    if ($address_phone != '' || $address_links != '') {
        $address_html .= '<div class="height-22" style=""></div>';
        $address_html .= '<div class="" style="">' . $address_links . '</div>';
    }
} else {
                    //$address_html .= '<div class="height-22 text-align-left" style="">Same</div>';
}

                $address_html .= '</div>';
            $address_html .= '</div>';
        $address_html .= '</div>';
    $address_html .= '</div>';
$address_html .= '</div>';

?>