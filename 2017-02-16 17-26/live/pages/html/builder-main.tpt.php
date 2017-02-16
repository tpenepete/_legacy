<?php

defined('TPT_INIT') or die('access denied');

$tpt_vars['template']['content'] .= <<< EOT
<div class="amz_builder clearFix">
    <div class="width-341 float-left templay-align-left clearFix">
        <div class="padding-10" style="border: 4px solid #d31d2c;">
            <div class="amz_red font-size-19 font-weight-bold padding-10" style="text-align:center;font-family: TODAYSHOP-BOLDITALIC,arial;">SEE YOUR AMAZING DESIGN HERE</div>
            <div class="frontPreview">
                <div class="amz_green font-size-16 padding-top-10" style="text-align:center;font-family: TODAYSHOP-BOLDITALIC,arial;">Front Preview</div>
                <div id="tpt_pg_front_color" class="" style="background-color: #A98765;"><img id="tpt_pg_front" src="http://www.amazingwristbands.com/generate-preview?text=Front%20Message&font=Michroma.ttf&color_r=255&color_g=0&color_b=41&bandType=2&bandStyle=1&type=full&timestamp=$time" /></div>
            </div>
            <div class="backPreview">
                <div class="amz_green font-size-16" style="text-align:center;font-family: TODAYSHOP-BOLDITALIC,arial;">Back Preview</div>
                <div id="tpt_pg_back_color" class="" style="background-color: #A98765;"><img id="tpt_pg_back" src="http://www.amazingwristbands.com/generate-preview?text=Back%20Message&font=Michroma.ttf&color_r=255&color_g=0&color_b=41&bandType=2&bandStyle=1&type=full&timestamp=$time" /></div>
            </div>
            <div class="pgControls" style="text-align:center;">
                $adjustElementsButton
            </div>
        </div>
        <div class="padding-top-25 padding-bottom-10">
            <a class="display-inline-block width-325 height-70" title="Create Your Own Design" href="javascript:void(0)" style="background-image: url($tpt_imagesurl/buttons/create-your-own-design.png);"></a>
        </div>
        <br />
        <div style="text-align: center;">
            <div class="display-inline-block">
                <div class="amz_green font-size-24 height-22 line-height-22 padding-bottom-20" style="font-family:TODAYSHOP-BOLDITALIC,arial;">Popular Add-Ons</div>
                <div class="line-height-22">
                    <div>
                        <input class="float-left" id="addons_rush_production" type="checkbox" name="addons[rush_production]" value="yes" />
                        <div class="overflow-hidden">
                            <label class="amz_green font-size-18" for="addons_rush_production">Rush production</label>
                            <br />
                            <span class="amz_red font-size-12">(Add $ 0.00)</span>&nbsp;&nbsp;
                            <a class="amz_green font-size-12 text-decoration-underline" title="Learn more" href="javascript:void(0);">Learn more</a>
                        </div>
                    </div>
                    
                    <div class="padding-top-20 padding-bottom-20">
                        <input class="float-left" id="addons_keychain" type="checkbox" name="addons[keychain]" value="yes" />
                        <div class="overflow-hidden">
                            <label class="amz_green font-size-18" for="addons_keychain">Make into a keychain</label>
                            <br />
                            <span class="amz_red font-size-12">(Add $ 0.00)</span>&nbsp;&nbsp;
                            <a class="amz_green font-size-12 text-decoration-underline" title="Learn more" href="javascript:void(0);">Learn more</a>
                        </div>
                    </div>
                    
                    <div>
                        <input class="float-left" id="addons_individual_packaging" type="checkbox" name="addons[individual_packaging]" value="yes" />
                        <div class="overflow-hidden">
                            <label class="amz_green font-size-18" for="addons_individual_packaging">Add individual packaging</label>
                            <br />
                            <span class="amz_red font-size-12">(Add $ 0.00)</span>&nbsp;&nbsp;
                            <a class="amz_green font-size-12 text-decoration-underline" title="Learn more" href="javascript:void(0);">Learn more</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="overflow-visible templay-align-left clearFix" style="margin-left: 341px;">
        <div class="padding-left-15">
            <h1 class="amz_green font-size-28 height-22 line-height-22" style="padding:0px;margin:0px;font-family:TODAYSHOP-BOLDITALIC,arial;">Wristband Builder</h1>
            <div class="width-110 height-17 padding-top-15 background-position-LC background-repeat-no-repeat" style="background-image: url($tpt_imagesurl/ratings.png);"></div>
            <div class="padding-top-10 padding-bottom-10">
                <a href="javascript:void(0)" title="Read the Product Reviews">Read all (N) reviews</a>
                <br />
                <a href="javascript:void(0)" title="Rate and review">Rate and review</a>
            </div>
            <div class="height-1" style="background-color: #d4d0c9; border-bottom: 1px solid #FFF;"></div>
            $pricing
            <div class="padding-top-10 padding-bottom-15 zoomFix">
                <a class="amz_green hoverCB display-inline-block line-height-25" href="javascript:void(0)" title="View Our Pricing">
                    <span class="padding-left-6 display-inline-block background-repeat-no-repeat background-position-LT" style="background-image: url($tpt_imagesurl/buttons/btn-1-left.png);"><span class="padding-right-6 display-inline-block background-repeat-no-repeat background-position-RT" style="background-image: url($tpt_imagesurl/buttons/btn-1-right.png);"><span class="display-inline-block height-25 background-repeat-repeat-x" style="background-image: url($tpt_imagesurl/buttons/btn-1-mid.png);">
                        View Our Pricing
                    </span></span></span>
                </a>
                <div class="amz_red display-inline-block height-25 line-height-25">FREE Air Shipping to the USA</div>
            </div>
            <div class="height-1" style="background-color: #d4d0c9; border-bottom: 1px solid #FFF;"></div>
            
            
            
            <div class="height-21 padding-top-10 padding-bottom-10 background-position-CC background-repeat-no-repeat" style="background-image: url($tpt_imagesurl/quick-builder.png);"></div>
            
            $typeSelect
            <div class="height-10"></div>
            $styleSelect
            <div class="height-10"></div>
            $colorSelect
            <div class="height-10"></div>
            $fontSelect
            <div class="height-10"></div>
            $allFontsButton
            <div class="height-10"></div>
            $textcolorSelect
            <div class="height-10"></div>
            <br />
            $addElementButton
            
            <div class="height-10"></div>
EOT;
    
    /*
    $tpt_vars['template']['content'] .= <<< EOT
            <div class="padding-left-12 background-position-LC background-repeat-no-repeat" style="background-image: url($tpt_imagesurl/input-field-1-left.png);">
                <div class="padding-right-60 background-position-RC background-repeat-no-repeat" style="background-image: url($tpt_imagesurl/input-field-1-preview.png); cursor: pointer;" onclick="tpt_pg_generate_prevew('tpt_pg_fmessage', 'tpt_pg_front');" title="Update Front Preview">
                    <div class="background-repeat-repeat-x" style="background-image: url($tpt_imagesurl/input-field-1-mid.png);">
                        <input oninput="tpt_pg_generate_prevew('tpt_pg_fmessage', 'tpt_pg_front');" onpropertychange="tpt_pg_generate_prevew('tpt_pg_fmessage', 'tpt_pg_front');" onfocus="activate_text_field(this);" oncontextmenu="return false" autocomplete="off" readonly="readonly" id="tpt_pg_fmessage" class="amz_brown plain-input-field height-26 line-height-26 padding-top-4 padding-bottom-6 font-size-18" type="text" name="tpt_pg_FrontMessage" value="Front Message" style="width: 100%; font-family: TODAYSHOP-BOLDITALIC,arial;" />
                    </div>
                </div>
            </div>
            
            <div class="height-10"></div>
            
            <div class="padding-left-12 background-position-LC background-repeat-no-repeat" style="background-image: url($tpt_imagesurl/input-field-1-left.png);">
                <div class="padding-right-60 background-position-RC background-repeat-no-repeat" style="background-image: url($tpt_imagesurl/input-field-1-preview.png); cursor: pointer;" onclick="tpt_pg_generate_prevew('tpt_pg_bmessage', 'tpt_pg_back');" title="Update Back Preview">
                    <div class="background-repeat-repeat-x" style="background-image: url($tpt_imagesurl/input-field-1-mid.png);">
                        <input oninput="tpt_pg_generate_prevew('tpt_pg_bmessage', 'tpt_pg_back');" onpropertychange="tpt_pg_generate_prevew('tpt_pg_bmessage', 'tpt_pg_back');" onfocus="activate_text_field(this);" oncontextmenu="return false" autocomplete="off" readonly="readonly" id="tpt_pg_bmessage" class="amz_brown plain-input-field height-26 line-height-26 padding-top-4 padding-bottom-6 font-size-18" type="text" name="tpt_pg_BackMessage" value="Back Message" style="width: 100%; font-family: TODAYSHOP-BOLDITALIC,arial;" />
                    </div>
                </div>
            </div>
    EOT;
    */
    
    $tpt_vars['template']['content'] .= '<div class="padding-left-12 background-position-LC background-repeat-no-repeat" style="background-image: url('.$tpt_res_url.'/images/input-field-1-left.png);">';
    $tpt_vars['template']['content'] .= '<div class="padding-right-12 background-position-RC background-repeat-no-repeat" style="background-image: url('.$tpt_res_url.'/images/input-field-1-preview.png); cursor: pointer;" onclick="tpt_pg_generate_prevew(\'tpt_pg_fmessage\', \'tpt_pg_front\');" title="Enter Front Message">';
    $tpt_vars['template']['content'] .= '<div class="background-repeat-repeat-x" style="background-image: url('.$tpt_res_url.'/images/input-field-1-mid.png);">';
    $tpt_vars['template']['content'] .= '<input oninput="tpt_pg_generate_prevew(\'tpt_pg_fmessage\', \'tpt_pg_front\');" onpropertychange="tpt_pg_generate_prevew(\'tpt_pg_fmessage\', \'tpt_pg_front\');" onfocus="activate_text_field(this);" oncontextmenu="return false" autocomplete="off" readonly="readonly" id="tpt_pg_fmessage" class="amz_brown plain-input-field line-height-26 padding-top-6 padding-bottom-8 font-size-18" type="text" name="tpt_pg_FrontMessage" value="Front Message" style="width: 100%; font-family: TODAYSHOP-BOLDITALIC,arial;" />';
    $tpt_vars['template']['content'] .= '</div>';
    $tpt_vars['template']['content'] .= '</div>';
    $tpt_vars['template']['content'] .= '</div>';
            
    $tpt_vars['template']['content'] .= <<< EOT
            <div class="height-10"></div>
EOT;
        
    $tpt_vars['template']['content'] .= '<div class="padding-left-12 background-position-LC background-repeat-no-repeat" style="background-image: url('.$tpt_res_url.'/images/input-field-1-left.png);">';
    $tpt_vars['template']['content'] .= '<div class="padding-right-12 background-position-RC background-repeat-no-repeat" style="background-image: url('.$tpt_res_url.'/images/input-field-1-preview.png); cursor: pointer;" onclick="tpt_pg_generate_prevew(\'tpt_pg_bmessage\', \'tpt_pg_back\');" title="Enter Back Message">';
    $tpt_vars['template']['content'] .= '<div class="background-repeat-repeat-x" style="background-image: url('.$tpt_res_url.'/images/input-field-1-mid.png);">';
    $tpt_vars['template']['content'] .= '<input oninput="tpt_pg_generate_prevew(\'tpt_pg_bmessage\', \'tpt_pg_back\');" onpropertychange="tpt_pg_generate_prevew(\'tpt_pg_bmessage\', \'tpt_pg_back\');" onfocus="activate_text_field(this);" oncontextmenu="return false" autocomplete="off" readonly="readonly" id="tpt_pg_bmessage" class="amz_brown plain-input-field line-height-26 padding-top-6 padding-bottom-8 font-size-18" type="text" name="tpt_pg_BackMessage" value="Back Message" style="width: 100%; font-family: TODAYSHOP-BOLDITALIC,arial;" />';
    $tpt_vars['template']['content'] .= '</div>';
    $tpt_vars['template']['content'] .= '</div>';
    $tpt_vars['template']['content'] .= '</div>';
    
    $tpt_vars['template']['content'] .= <<< EOT
            <div class="padding-top-5 padding-top-10">
                <div class="amz_red padding-top-5 padding-top-5 font-size-18 height-18 line-height-18" style="font-family:TODAYSHOP-LIGHT,arial;">
                    Add Glitter&nbsp;$glitterCheckbox
                </div>
                <div class="amz_red padding-top-5 padding-top-5 font-size-18 height-18 line-height-18" style="font-family:TODAYSHOP-LIGHT,arial;">
                    Create custom swirl
                </div>
                $swirlsHtml
            </div>
            
            $qty_panel
            
            
        </div>
    </div>
    
    $bandClipartPanel
</div>
EOT;

?>