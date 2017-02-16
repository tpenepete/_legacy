<?php

defined('TPT_INIT') or die('access denied');

echo <<< EOT
<div id="qty_controls">
    <div class="font-weight-bold">Quantity:</div>
    <div>
        <div class="display-inline-block text-align-right">
            <div class="height-22" style="text-align: right;">LG:&nbsp;</div>
        </div>
        <div class="display-inline-block">
            <div class="height-22" style="text-align: right;">$qty_lgInput <span class="imcontrol">Inside message:&nbsp;</span>$qty_lgInput_im</div>
        </div>
    </div>
    <div class="position-relative sizesFolded overflow-hidden" style="padding: 5px;" id="szptions">
        <div class="font-size-12 font-weight-bold" id="po_title"><a href="javascript:void(0);" onclick="toggle_product_sizes(this);" style="cursor: pointer; text-decoration: none;">Show sizes:</a></div>
        <div id="sz_content" class="opacity-0 position-absolute left-20 right-20">
            <div class="display-inline-block text-align-right">
                <div class="height-22" style="text-align: right;">LG:&nbsp;</div>
                <div class="height-22" style="text-align: right;">XS:&nbsp;</div>
                <div class="height-22" style="text-align: right;">SM:&nbsp;</div>
                <div class="height-22" style="text-align: right;">M:&nbsp;</div>
                <div class="height-22" style="text-align: right;">XL:&nbsp;</div>
            </div>
            <div class="display-inline-block">
                <div class="height-22" style="text-align: right;">$qty_Input <span class="imcontrol">Inside message:&nbsp;</span>$qty_Input_im</div>
                <div class="height-22" style="text-align: right;">$qty_xsInput <span class="imcontrol">Inside message:&nbsp;</span>$qty_xsInput_im</div>
                <div class="height-22" style="text-align: right;">$qty_smInput <span class="imcontrol">Inside message:&nbsp;</span>$qty_smInput_im</div>
                <div class="height-22" style="text-align: right;">$qty_mInput <span class="imcontrol">Inside message:&nbsp;</span>$qty_mInput_im</div>
                <div class="height-22" style="text-align: right;">$qty_xlInput <span class="imcontrol">Inside message:&nbsp;</span>$qty_xlInput_im</div>
            </div>
        </div>
    </div>
</div>

EOT;

?>