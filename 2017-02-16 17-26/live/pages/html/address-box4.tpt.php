<?php

defined('TPT_INIT') or die('access denied');

$address_html .= '<div class="" style="">';
    $address_html .= '<div class="" style="padding: 0px;">';
        $address_html .= '<div class="" style="background-color: #FFF;">';
            $address_html .= '<div class="" style="padding: 0px; background-color: #FFF;">';
                $address_html .= '<div class="line-height-22 font-size-12" style="padding: 5px; background-color: #FFF;">';
                    $address_html .= '<div class="white-space-pre">';
                

/*                
$checked = '';
if($tpt_vars['user']['data']['same_address']) {
    $checked = ' checked="checked"';
}
if(($address_name == 'shipping')) {
                    $address_html .= '
<form method="POST" action="$action_url$addparam" accept-charset="utf-8" class="display-inline">
<div class="height-22 text-align-left" style="">Same As Billing&nbsp;'.tpt_html::createCheckbox($tpt_vars, 'same_address', 1, $tpt_vars['user']['data']['same_address'], ' onclick="if(this.checked){document.location=base_url+\'/manage-address?same_address=1&topayment=1\';}else{document.location=base_url+\'/manage-address?same_address=0&topayment=1\';}" onkeyup="if(this.checked){document.location=base_url+\'/manage-address?same_address=1&topayment=1\';}else{document.location=base_url+\'/manage-address?same_address=0&topayment=1\';}"', '', '').'
<input type="hidden" name="task" value="user.same_address" />
</div>
</form>
';
                    $address_html .= '<div class="height-22 text-align-left" style=""></div>';
}
*/

                    $address_html .= $address;
                
                    $address_html .= '</div>';
                $address_html .= '</div>';
            $address_html .= '</div>';
        $address_html .= '</div>';
    $address_html .= '</div>';
$address_html .= '</div>';

?>