<?php
defined('TPT_INIT') or die('access denied');

$address_box = '';

if($i == 'left') {
$address_box .= '<div class="clearBoth"></div>';
$address_box .= '<div id="billing-address-container" class="float-left width-40prc">';
$i='right';
} else {
$address_box .= '<div id="shipping-address-container" class="float-right width-40prc">';
$i='left';
}
    $address_box .= '<div class="padding-10">';
        $address_box .= '<div>';
            $address_box .= '<div class="padding-1">';
                $address_box .= '<div class="line-height-22 padding-5 font-size-14 urlabel" >';                
                    if ($address_name != '') {
                        $address_box .= '<div class="height-22">'.$address_name.'</div>';
                        $address_box .= '<div class="height-22"></div>';
                    }
                    if ($address_names != '') {
                        $address_box .= '<div class="height-22">'.$address_names.'</div>';
                        $address_box .= '<div class="height-22"></div>';
                    }
                    if ($address_company != '') {
                        $address_box .= '<div class="height-22">'.$address_company.'</div>';
                        $address_box .= '<div class="height-22"></div>';
                    }
                    if ($address_addr != '') {
                        $address_box .= $address_addr;
                    }
                    $address_box .= '<div class="height-22">'.$address_city.'</div>';
                    $address_box .= '<div class="height-22">'.$address_state.'</div>';
                    $address_box .= '<div class="height-22">'.$address_zip.'</div>';
                    $address_box .= '<div class="height-22">'.$address_country.'</div>';
                    $address_box .= '<div class="height-22"></div>';
                    $address_box .= '<div class="height-22">'.$address_phone.'</div>';
                    $address_box .= '<div class="height-22"></div>';
                    $address_box .= '<div>'.$address_links.'</div>';
                
                $address_box .= '</div>';
            $address_box .= '</div>';
        $address_box .= '</div>';
    $address_box .= '</div>';
$address_box .= '</div>';
?>