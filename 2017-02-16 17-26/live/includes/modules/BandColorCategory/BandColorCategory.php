<?php

defined('TPT_INIT') or die('access denied');

class tpt_module_BandColorCategory extends tpt_Module {
    
    function __construct(&$vars, $name, $moduleClassFile, $moduleClass, $moduleTable) {
        $fields = array(
                //db field name|field type|length|options|storage options|control|ctrAttr|default|label|index by|split keys|template
            new tpt_ModuleField('id',    'n', null, 'ai', '',         'sp', '', '', '',           true, false,  'LC'),
            new tpt_ModuleField('name',  's', 255,  '',   '',         'tf', ' style="width: 170px;"', '', 'Color Category Name', false, false, 'LC'),
            new tpt_ModuleField('label',  's', 255,  '',   '',         'tf', ' style="width: 170px;"', '', 'Color Category Label', false, false, 'LC'),
            //new tpt_ModuleField('category_image',  's', 255,  '',   '',         'tf', ' style="width: 170px;"', '', 'Clipart PNG Image', false, false, 'LC'),
            //new tpt_ModuleField('parent_id',  'i', '',  '',   'intval10',         'tf', ' style="width: 170px;"', '', 'Parent Category', false, false, 'LC'),
            //new tpt_ModuleField('category_status',   'ti', '',    '',   'intval10', 'tf', ' style="width: 70px;"', '', 'Enabled?',        false, false, 'LC'),
            //new tpt_ModuleField('folder',  's', 255,  '',   '',         'tf', ' style="width: 170px;"', '', 'Directory', false, false, 'LC'),
            //'<div class="tpt_admin_module_section float-left" style="border: 2px solid #FFF;">',
            //'</div>',
            //'<div class="float-left padding-top-20 padding-bottom-20 padding-left-10 padding-right-10" style="background-color: #FFF;"><div class="display-inline-block height-10 width-80" style="background-color: #`HEX`; border: 1px solid #000;"></div></div>',
            //'<div class="float-left" style="background-color: #`HEX`; border: 1px solid #000;"><img src="'.$vars['config']['resourceurl'].'/images/Band-Transperent-Preview.png" class="width-80" /></div>',
            //'<div class="float-left" style="background-color: #`HEX`; border: 1px solid #000;"><img src="'.$vars['config']['resourceurl'].'/images/Transparent-Swirl-Band-Preview.png" class="width-80" /></div>'
        );
        parent::__construct($vars, $name, $moduleClassFile, $moduleClass, $moduleTable, $fields, 'id');
    }
    
    /*
    function BandClipartCategory_Panel(&$vars) {
        //var_dump($this);die();
        
        $items = $vars['db']['handler']->getData($vars, $this->moduleTable, '*', '', 'parent_id', true);
        
        $html = '';
        $values = array();
        
        $buttonUrl = TPT_IMAGES_URL.'/clipart-button.png';
        
        $rowDelimiter = '</div><div>';
        
        $stylesheet = '';
        $stylesheet .= '#clipartscat>a {display: none;}'."\n";
        $parents = array('content'=>array(), 'ids'=>array());
        $i=0;
        foreach($items[0] as $item) {
            $itemUrl = CLIPARTS_URL.'/categories/'.$item['category_image'];
            $_temp = explode(' ', $item['category_name']);
            $_temp = array_map('strtolower', $_temp);
            $_temp = array_map('ucfirst', $_temp);
            $itemName = implode(' ', $_temp);
            $itemId = preg_replace('#[\W]+#', '-', $item['category_name']);
            $itemId = preg_replace('#[-]+#', '-', $itemId);
            $itemId = strtolower($itemId);
            $parents['ids'][$item['id']] = strtolower($itemId);
            
            $stylesheet .= '#clipartscat.clipartMainCategory_'.$itemId.'>a.clipartMainCategory_'.$itemId.' {display: block;}'."\n";
            
            $parents['content'][$item['id']] = '';
            
            if($i%4 == 0) {
                $parents['content'][$item['id']] .= '<div class="clipartRow">';
            }
            
$parents['content'][$item['id']] .= <<< EOT
<div class="height-110 width-70 float-left" style="padding: 2px;">
    <a class="hoverCB" href="javascript:void(0);" onclick="clipartMainCat(this);" rel="$itemId" title="$itemName">
        <span class="display-block width-70 height-70" style="background-image: url($buttonUrl);">
            <span class="display-block width-70 height-70" style="background-image: url($itemUrl); background-position: center center;">
            </span>
        </span>
        <span class="display-block width-70 height-40 line-height-20">
            $itemName
        </span>
    </a>
</div>
EOT;

            if(($i%4 == 3) || ($i == count($items[0])-1)) {
                $parents['content'][$item['id']] .= '</div>';
            }
            
            $i++;
        }
        
        
        $childs = array();
        foreach($items as $parent=>$elms) {
            if($parent!=0) {
                foreach($elms as $item) {
                    //if(!is_array($childs[$parent])) {
                        //$childs[$parent] = array();
                    //}
                        
                    $itemUrl = CLIPARTS_URL.'/categories/'.$item['category_image'];
                    $itemName = $item['category_name'];
                    $itemClass = $parents['ids'][$item['parent_id']];
                    
//$childs[$parent][] = <<< EOT
$childs[] = <<< EOT
<a class="hoverCB clipartMainCategory_$itemClass" href="javascript:void(0);" onclick="" title="$itemName">
    <span class="display-block width-70 height-70" style="background-image: url($buttonUrl);">
        <span class="display-block width-70 height-70 background-repeat-no-repeat" style="background-image: url($itemUrl); background-position: center center;">
        </span>
    </span>
    <span class="display-block width-70 height-20 line-height-20">
        $itemName
    </span>
</a>
EOT;
                }
            }
        }
        
        
        
        $childsHTML = implode("\n", $childs);
        $parentsHTML = implode("\n", $parents['content']);
        
$html = <<< EOT
<div class="clearFix">
    <div class="clipartMain float-left width-300">
        $parentsHTML
    </div>
    
    <div class="clipartSecondary overflow-hidden clipartMainCategory_None width-200" id="clipartscat">
        $childsHTML
    </div>
    
    <div class="clipartItems">
    </div>
</div>
EOT;


$vars['template_data']['head'][] = <<< EOT
<style type="text/css">
$stylesheet
</style>
EOT;


        return $html;
    }
    */

}
