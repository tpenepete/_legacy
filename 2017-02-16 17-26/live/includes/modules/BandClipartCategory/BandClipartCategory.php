<?php

defined('TPT_INIT') or die('access denied');

class tpt_module_BandClipartCategory extends tpt_Module {
    
    function __construct(&$vars, $name, $moduleClassFile, $moduleClass, $moduleTable) {
        $fields = array(
                //db field name|field type|length|options|storage options|control|ctrAttr|default|label|index by|split keys|template
            new tpt_ModuleField('id',    'n', null, 'ai', '',         'sp', '', '', '',           true, false,  'LC'),
            new tpt_ModuleField('category_name',  's', 255,  '',   '',         'tf', ' style="width: 170px;"', '', 'Clipart Category Name', false, false, 'LC'),
            new tpt_ModuleField('category_image',  's', 255,  '',   '',         'tf', ' style="width: 170px;"', '', 'Clipart PNG Image', false, false, 'LC'),
            new tpt_ModuleField('parent_id',  'i', '',  '',   'intval10',         'tf', ' style="width: 170px;"', '', 'Parent Category', false, false, 'LC'),
            new tpt_ModuleField('category_status',   'ti', '',    '',   'intval10', 'tf', ' style="width: 70px;"', '', 'Enabled?',        false, false, 'LC'),
            new tpt_ModuleField('folder',  's', 255,  '',   '',         'tf', ' style="width: 170px;"', '', 'Directory', false, false, 'LC'),
            //'<div class="tpt_admin_module_section float-left" style="border: 2px solid #FFF;">',
            //'</div>',
            //'<div class="float-left padding-top-20 padding-bottom-20 padding-left-10 padding-right-10" style="background-color: #FFF;"><div class="display-inline-block height-10 width-80" style="background-color: #`HEX`; border: 1px solid #000;"></div></div>',
            //'<div class="float-left" style="background-color: #`HEX`; border: 1px solid #000;"><img src="'.$vars['config']['resourceurl'].'/images/Band-Transperent-Preview.png" class="width-80" /></div>',
            //'<div class="float-left" style="background-color: #`HEX`; border: 1px solid #000;"><img src="'.$vars['config']['resourceurl'].'/images/Transparent-Swirl-Band-Preview.png" class="width-80" /></div>'
        );
        parent::__construct($vars, $name, $moduleClassFile, $moduleClass, $moduleTable, $fields, 'id');
    }
    
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
    
    // short builder version of the method...
    function BandClipartCategory_Panel_SB(&$vars) {
        //var_dump($this);die();

        $items = $vars['db']['handler']->getData($vars, $this->moduleTable, '*', ' (1=1) ORDER BY `category_name` ASC', 'parent_id', true);

        $html = '';
        $values = array();

        $buttonUrl = TPT_IMAGES_URL.'/clipart-button.png';

        $rowDelimiter = '</div><div>';

        $stylesheet = '';
        $stylesheet .= '#clipartscat>a {display: none;}'."\n";
        $parents = array('content'=>array(), 'ids'=>array());
        $i=0;
        foreach($items[0] as $item) {
            $itemUrl = CLIPARTS_URL.'/categories/'.urlencode($item['category_image']);
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

     /*       if($i%4 == 0) {
                $parents['content'][$item['id']] .= '<div class="clipartRow">';
            }
       */

       $ID=$item['id'];

$parents['content'][$item['id']] .= <<< EOT
<div class="height-110 width-70 float-left" style="padding: 2px;">
    <a class="hoverCB" href="javascript:;" onclick="click_artwork_cat(this,$ID);" rel="$itemId" title="$itemName">
        <span class="display-block height-70" style="background-image: url($buttonUrl);">
            <span class="display-block height-70" style="background-image: url($itemUrl); background-position: center center;">
            </span>
        </span>
        <span class="cat_name text-align-center">
            $itemName
        </span>
    </a>
</div>
EOT;
/*
            if(($i%4 == 3) || ($i == count($items[0])-1)) {
                $parents['content'][$item['id']] .= '</div>';
            } */

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
                    $itemClass = empty($parents['ids'][$item['parent_id']]) ? '' : $parents['ids'][$item['parent_id']];

                    $ID=$item['id'];

//$childs[$parent][] = <<< EOT
$childs[] = <<< EOT
<a class="hoverCB clipartMainCategory_$itemClass" href="javascript:;" onclick="click_artwork_cat(this,$ID);" title="$itemName">
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
    <div class="clipartMain float-left">
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


	function BandClipart_Panel2_Aux_Style(&$vars) {
		//var_dump($this);die();

		$items = $vars['db']['handler']->getData($vars, $this->moduleTable, '*', ' (1=1) ORDER BY `parent_id`,`category_name`', 'parent_id', true);

		$html = '';

		$stylesheet = '';
		$stylesheet .= '#clipartscat div.ccat {display: none;}'."\n";
		foreach($items[0] as $item) {
			$itemId = preg_replace('#[\W]+#', '-', $item['category_name']);
			$itemId = preg_replace('#[-]+#', '-', $itemId);
			$itemId = strtolower($itemId);

			$stylesheet .= '#clipartscat.clipartMainCategory_'.$itemId.' div.ccat.clipartMainCategory_'.$itemId.' {display: block;}'."\n";

		}


		$head = <<< EOT
$stylesheet
EOT;
		//tpt_dump($head, true);

		//$vars['template_data']['head'][] = <<< EOT

//EOT;


		return $head;
	}
	function BandClipart_Panel2(&$vars, $pname, $sItem=0, $pid=0, $sid='') {
		//var_dump($this);die();

		$items = $vars['db']['handler']->getData($vars, $this->moduleTable, '*', ' (1=1) ORDER BY `parent_id`,`category_name`', 'parent_id', true);

		$html = '';
		$values = array();

		$buttonUrl = TPT_IMAGES_URL.'/clipart-button.png';

		$rowDelimiter = '</div><div>';

		$parents = array('content'=>array(), 'ids'=>array());
		$prows = array();
		$cells = array();
		$i=0;
		$citems = count($items[0]);
		foreach($items[0] as $item) {
			if(($i==0) || ($i%4==0)) {
				$cells = array();
			}

			$itemUrl = CLIPARTS_URL.'/categories/'.urlencode($item['category_image']);
			$_temp = explode(' ', $item['category_name']);
			$_temp = array_map('strtolower', $_temp);
			$_temp = array_map('ucfirst', $_temp);
			$itemName = implode(' ', $_temp);
			$itemId = preg_replace('#[\W]+#', '-', $item['category_name']);
			$itemId = preg_replace('#[-]+#', '-', $itemId);
			$itemId = strtolower($itemId);
			$parents['ids'][$item['id']] = strtolower($itemId);

			$parents['content'][$item['id']] = '';

			/*       if($i%4 == 0) {
						$parents['content'][$item['id']] .= '<div class="clipartRow">';
					}
			   */

			$ID=$item['id'];
			$has_subcat = '';
			if(!empty($items[$ID])) {
				$has_subcat = ', true';
			}

			$parents['content'][$item['id']] .= <<< EOT
<div class="height-110 width-70 float-left" style="padding: 2px;">
    <a class="hoverCB" href="javascript:;" onclick="ccat_click(this$has_subcat);" rel="$itemId" title="$itemName">
        <span class="display-block height-70" style="background-image: url($buttonUrl);">
            <span class="display-block height-70" style="background-image: url($itemUrl); background-position: center center;">
            </span>
        </span>
        <span class="cat_name text-align-center">
            $itemName
        </span>
        <input type="hidden" id="${pid}_${ID}_pname" value="$pname" />
		<input type="hidden" id="${pid}_${ID}_pid" value="$pid" />
		<input type="hidden" id="${pid}_${ID}_cid" value="$ID" />
		<input type="hidden" id="${pid}_${ID}_sid" value="$sid" />
    </a>
</div>
EOT;
			$cells[] = $parents['content'][$item['id']];
			if(($i%4==3) || ($i>=$citems-1)) {
				$prows[] = '<div class="clearFix">'.implode('', $cells).'</div>';
			}
			/*
						if(($i%4 == 3) || ($i == count($items[0])-1)) {
							$parents['content'][$item['id']] .= '</div>';
						} */

			$i++;
		}


		$childs = array();
		$crows = array();
		foreach($items as $parent=>$elms) {
			if(($parent!=0)) {
				$i=0;
				$citems = count($elms);
				$cells = array();
				foreach($elms as $item) {
					if(!empty($parents['ids'][$item['parent_id']])) {
						if(($i==0) || ($i%4==0)) {
							$cells = array();
						}
						//if(!is_array($childs[$parent])) {
						//$childs[$parent] = array();
						//}

						$itemUrl = CLIPARTS_URL . '/categories/' . $item['category_image'];
						$itemName = $item['category_name'];
						$itemClass = $parents['ids'][$item['parent_id']];

						$ID = $item['id'];

//$childs[$parent][] = <<< EOT
						$childs[] = <<< EOT
<div class="height-110 width-70 float-left" style="padding: 2px;">
	<a class="hoverCB display-inline-block ccat clipartMainCategory_$itemClass" href="javascript:;" onclick="ccat_click(this);" title="$itemName">
		<span class="display-block width-70 height-70" style="background-image: url($buttonUrl);">
			<span class="display-block width-70 height-70 background-repeat-no-repeat" style="background-image: url($itemUrl); background-position: center center;">
			</span>
		</span>
		<span class="display-block width-70 height-20">
			$itemName
		</span>
		<input type="hidden" id="${pid}_${ID}_pname" value="$pname" />
		<input type="hidden" id="${pid}_${ID}_pid" value="$pid" />
		<input type="hidden" id="${pid}_${ID}_cid" value="$ID" />
		<input type="hidden" id="${pid}_${ID}_sid" value="$sid" />
	</a>
</div>
EOT;
						$cells[] = $childs[count($childs)-1];
						if(($i%4==3) || ($i>=$citems-1)) {
							$crows[] = '<div class="clearFix">'.implode('', $cells).'</div>';
							//$crows[] = implode('', $cells);
						}

						$i++;
					}
				}
			}
		}



		$childsHTML = implode("\n", $crows);
		$parentsHTML = implode("\n", $prows);

		$html = <<< EOT

<div class="clearFix text-align-center">
    <div class="clipartMain float-left width-33prc">
    	<div class="display-inline-block">
        	$parentsHTML
        </div>
    </div>
    <div id="clipartscat" class="clipartSecondary clipartMainCategory_None float-left width-33prc">
    	<div class="display-inline-block">
        	$childsHTML
    	</div>
    </div>
    <div id="clipartsitems" class="clipartItems float-left width-33prc">
    </div>
</div>
EOT;


		//$vars['template_data']['head'][] = <<< EOT

//EOT;


		return $html;
	}





	function BandClipart_Panel3(&$vars, $sid, $sItem=0) {
		//var_dump($this);die();
		$sections_mod = getModule($vars, 'BuilderSection');
		$sections = $sections_mod->moduleData['id'];
		$section = $sections[$sid];
		$pname = ', \''.$section['pname'].'\'';

		$items = $vars['db']['handler']->getData($vars, $this->moduleTable, '*', ' (1=1) ORDER BY `parent_id`,`category_name`', 'parent_id', true);

		$html = '';
		$values = array();

		$buttonUrl = TPT_IMAGES_URL.'/clipart-button.png';

		$rowDelimiter = '</div><div>';

		$parents = array('content'=>array(), 'ids'=>array());
		$prows = array();
		$cells = array();
		$i=0;
		$citems = count($items[0]);
		foreach($items[0] as $item) {
			if(($i==0) || ($i%10==0)) {
				$cells = array();
			}

			$itemUrl = CLIPARTS_URL.'/categories/'.urlencode($item['category_image']);
			$_temp = explode(' ', $item['category_name']);
			$_temp = array_map('strtolower', $_temp);
			$_temp = array_map('ucfirst', $_temp);
			$itemName = implode(' ', $_temp);
			$itemId = preg_replace('#[\W]+#', '-', $item['category_name']);
			$itemId = preg_replace('#[-]+#', '-', $itemId);
			$itemId = strtolower($itemId);
			$parents['ids'][$item['id']] = strtolower($itemId);

			$parents['content'][$item['id']] = '';

			/*       if($i%4 == 0) {
						$parents['content'][$item['id']] .= '<div class="clipartRow">';
					}
			   */

			$ID=$item['id'];
			$has_subcat = '';
			if(!empty($items[$ID])) {
				$has_subcat = ', true';
			}

			$parents['content'][$item['id']] .= <<< EOT
<div class="height-110 width-70 float-left" style="padding: 2px;">
    <a class="hoverCB" href="javascript:;" onclick="ccat_click2(this$pname$has_subcat);" rel="$itemId" title="$itemName">
        <span class="display-block height-70" style="background-image: url($buttonUrl);">
            <span class="display-block height-70" style="background-image: url($itemUrl); background-position: center center;">
            </span>
        </span>
        <span class="cat_name text-align-center">
            $itemName
        </span>
        <input type="hidden" id="${ID}_sid" value="$sid" />
        <input type="hidden" id="${ID}_cid" value="$ID" />
    </a>
</div>
EOT;
			$cells[] = $parents['content'][$item['id']];
			if(($i%10==9) || ($i>=$citems-1)) {
				$prows[] = implode('', $cells);
			}
			/*
						if(($i%4 == 3) || ($i == count($items[0])-1)) {
							$parents['content'][$item['id']] .= '</div>';
						} */

			$i++;
		}


		$childs = array();
		$crows = array();
		foreach($items as $parent=>$elms) {
			if(($parent!=0)) {
				$i=0;
				$citems = count($elms);
				$cells = array();
				foreach($elms as $item) {
					if(!empty($parents['ids'][$item['parent_id']])) {
						if(($i==0) || ($i%10==0)) {
							$cells = array();
						}
						//if(!is_array($childs[$parent])) {
						//$childs[$parent] = array();
						//}

						$itemUrl = CLIPARTS_URL . '/categories/' . $item['category_image'];
						$itemName = $item['category_name'];
						$itemClass = $parents['ids'][$item['parent_id']];

						$ID = $item['id'];

//$childs[$parent][] = <<< EOT
						$childs[] = <<< EOT
<div class="height-110 width-70 float-left ccat clipartMainCategory_$itemClass" style="padding: 2px;">
	<a class="hoverCB display-inline-block " href="javascript:;" onclick="ccat_click2(this);" rel="$itemName" title="$itemName">
		<span class="display-block width-70 height-70" style="background-image: url($buttonUrl);">
			<span class="display-block width-70 height-70 background-repeat-no-repeat" style="background-image: url($itemUrl); background-position: center center;">
			</span>
		</span>
		<span class="display-block width-70 height-20">
			$itemName
		</span>
		<input type="hidden" id="${ID}_sid" value="$sid" />
		<input type="hidden" id="${ID}_cid" value="$ID" />
	</a>
</div>
EOT;
						$cells[] = $childs[count($childs)-1];
						if(($i%10==9) || ($i>=$citems-1)) {
							$crows[] = '<div class="clearFix">'.implode('', $cells).'</div>';
							//$crows[] = implode('', $cells);
						}

						$i++;
					}
				}
			}
		}



		$childsHTML = implode("\n", $crows);
		$parentsHTML = implode("\n", $prows);

		$html = <<< EOT

<div class="clearFix">
    <div id="back-to-main" class="display-none" >
        <a href="javascript::" onclick="back_to_main(this)" class="float-right" >Back to main</a> 
        <div class="heading" >Category Name: <span id="ccategory" ></span></div>
    </div>
    <div class="clipartMain clipartWrapper">
        <div class="heading">Categories:</div>
    	<div class="display-inline-block">
        	$parentsHTML
        </div>
    </div>
    <div id="clipartscat" class="clipartWrapper clipartSecondary clipartMainCategory_None">
    	<div class="display-inline-block">
        	$childsHTML
    	</div>
    </div>
    <div id="clipartsitems" class="clipartWrapper clipartItems display-none">
    </div>
</div>
EOT;


		//$vars['template_data']['head'][] = <<< EOT

//EOT;


		return $html;
	}

}

