<?php

defined('TPT_INIT') or die('access denied');

$persize_lg_options = $persize_controls['lg']['options'];
$persize_controls_nolarge = $persize_controls;
//unset($persize_controls_nolarge['lg']);
$persize_controls_nolarge['lg'] = $persize_controls_nolarge['sync'];
unset($persize_controls_nolarge['sync']);

$n_persize_controls_nolarge = $n_persize_controls;
//unset($persize_controls_nolarge['lg']);
$n_persize_controls_nolarge['lg'] = $n_persize_controls_nolarge['sync'];
unset($n_persize_controls_nolarge['sync']);

$c_persize_controls_nolarge = $c_persize_controls;


echo <<< EOT
<div id="qty_controls">
    <div class="font-weight-bold">Quantity:</div>
    <div>
        <div class="display-inline-block text-align-right">
            <div class="height-22" style="text-align: right;">LG:&nbsp;</div>
        </div>
        <div class="display-inline-block">
            <div class="height-22" style="text-align: right;">$qty_lgInput <span class="imcontrol">$persize_lg_options</span></div>
        </div>
    </div>
    <fieldset class="position-relative sectionFolded overflow-hidden" style="" id="szptions">
        <legend class="font-size-14 font-weight-bold" id="po_title"><a href="javascript:void(0);" onclick="toggle_product_section(this);" style="cursor: pointer; text-decoration: none;">Show sizes:</a></legend>
        <div id="sz_content" class="opacity-0">
        
            <div>
            <div class="display-inline-block text-align-right">
EOT;
            foreach($n_persize_controls_nolarge as $key=>$sz) {
                $sname = strtoupper($key);
echo <<< EOT
                <div class="height-22 width-40" style="text-align: right;">
                    $sname:&nbsp;
                </div>
EOT;
            }
echo <<< EOT
            </div>
            <div class="display-inline-block">
EOT;
            foreach($n_persize_controls_nolarge as $key=>$sz) {

                if(!isset($sz['control'])) { $sz['control'] = ''; }
echo '
                <div class="height-22" style="text-align: right;">'.$sz['control'].' <span class="imcontrol">'.$sz['options'].'</span></div>
';
            }
echo <<< EOT
            </div>
            </div>
            
            
            
            <div class="font-weight-bold">
                Additional product rows:
            </div>
            
            
            
            <div>
            <div class="display-inline-block text-align-right">
EOT;
            foreach($c_persize_controls_nolarge as $key=>$sz) {
                $sname = strtoupper($key);
echo <<< EOT
                <div class="height-22 width-40" style="text-align: right;">
                    <a class="" href="javascript:void(0);" onclick="pcalc_remove_row(this);">X</a>
                    $sname:&nbsp;
                    <input type="hidden" name="csize[$key]" value="$key" />
                </div>
EOT;
            }
echo <<< EOT
            </div>
            <div class="display-inline-block">
EOT;
            foreach($c_persize_controls_nolarge as $key=>$sz) {
                if(!isset($sz['control'])) { $sz['control'] = ''; }
echo '
                <div class="height-22" style="text-align: right;">'.$sz['control'].' <span class="imcontrol">'.$sz['options'].'</span></div>
';
            }
echo <<< EOT
            </div>
            </div>
            
            <div class="">
                <a class="" href="javascript:void(0);" onclick="pcalc_add_row();">Add row</a>
            </div>
            
            
        </div>
    </fieldset>
</div>

EOT;

?>