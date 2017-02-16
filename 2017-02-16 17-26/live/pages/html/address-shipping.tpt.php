<?php
defined('TPT_INIT') or die('access denied');

$delivery_notes = amz_checkout::$delivery_notes;
$checkoutbar = amz_checkout::getCheckoutBar($tpt_vars, 1);

$tpt_vars['template']['content'] .= $checkoutbar;

// master template
$tpt_vars['template']['content'] .= <<< EOT
<div class="padding-top-0">
<div class="height-45 padding-top-5 padding-bottom-5 background-position-CC background-repeat-no-repeat background-size-80prc" style="background-image: url($tpt_imagesurl/confirm-address-and-shipping.png);"></div>
</div>
EOT;

include(TPT_TEMPLATES_DIR.DIRECTORY_SEPARATOR.'current-addresses.tpt.php');
$tpt_vars['template']['content'] .= $current_addresses;

$action_url = $tpt_vars['url']['handler']->wrap($tpt_vars, '/billing-details');
$tpt_vars['template']['content'] .= <<< EOT
<form method="POST" action="$action_url" accept-charset="utf-8"  onsubmit="if(!document.getElementById('accept_tac').checked){alert('You must accept our Terms and Conditions to continue.');return false;}">

<div class="amz_basket position-relative clearFix z-index-2">
<div class="clearFix white-box padding-10">
EOT;

$tpt_vars['template']['content'] .= <<< EOT
    <div class="clearFix">
EOT;

//////// TERMS AND CONDITIONS
include(TPT_TEMPLATES_DIR.DIRECTORY_SEPARATOR.'terms-and-conditions.tpt.php');
$tpt_vars['template']['content'] .= <<< EOT
        <div class="font-size-18 todayshop-bolditalic clearBoth text-align-left padding-bottom-5" style="color: #919191;">Terms and Conditions</div>
        <div class="float-left clearFix width-300 padding-left-30 padding-right-30" >
            <div class="grey-box padding-10">    
                <div class="overflow-auto height-250 text-align-left font-size-13 line-height-14" style="color: #4B4B4B;">
                $terms_and_conditions
                </div>
            </div>
            <div class="text-align-left padding-top-5 clear" style="color: #919191;">
                <span class="roundedOne display-block float-left">
                    <input type="checkbox" id="accept_tac"  />
                    <label for="accept_tac"></label>
                </span>
                <span class="font-size-16 font-weight-bold todayshop-bolditalic amz_red display-block float-left line-height-28">&nbsp; Accept our Terms and Conditions</span>
            </div>
        </div>
EOT;
//////// TERMS AND CONDITIONS :::::: END

$tpt_vars['template']['content'] .= <<< EOT
        <div class="float-left text-align-left overflow-hidden padding-left-2 ">
            <div class="position-relative padding-left-120 padding-bottom-10 padding-top-10">
                <div class="position-absolute top-11 left-0 bottom-0 width-110 text-align-right font-size-13" style="color: #919191;">
                    <div class="position-relative height-14">
                        Delivery Method:
                    </div>
                </div>
                <div class="overflow-hidden">
                    $shipping_rates
                </div>
            </div>
            <div class="clearBoth position-relative padding-left-120 padding-top-10">
                <div class="position-absolute top-0 left-0 bottom-0 width-110 text-align-right font-size-13" style="color: #919191;">
                    <div class="height-14" style="margin-top: 15px;">
                        Delivery Notes:
                    </div>
                </div>
                <div class="clearFix float-left" >
                    <div class="grey-box padding-10">
                    <div class="text-align-left font-size-13 line-height-14" style="color: #4B4B4B;">
                        <textarea name="delivery_notes" class="plain-input-field height-150 width-100prc">$delivery_notes</textarea>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
EOT;

$tpt_vars['template']['content'] .= <<< EOT
</div>
</div>
EOT;

$tpt_vars['template']['content'] .= <<< EOT
<div class="height-16 clearBoth"></div>
<div class="clearBoth padding-left-10 padding-right-10" >	
<input type="submit" value="" title="Submit Shipping Details and Proceed to Payment" class="plain-input-field float-right height-35 width-168 hoverCB" style="background-image: url($tpt_imagesurl/proceed-to-payment.png);" />
</div>

</form>
EOT;

?>