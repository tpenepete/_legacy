<?php

defined('TPT_INIT') or die('access denied');

class tpt_module_BandSize extends tpt_Module {
    
    function __construct(&$vars, $name, $moduleClassFile, $moduleClass, $moduleTable) {
        $fields = array(
                //db field name|field type|length|options|storage options|control|ctrAttr|default|label|index by|split keys|template
            new tpt_ModuleField('id',    'n', null, 'ai', '',         'sp', '', '', '',           true, false,  'LC'),
            new tpt_ModuleField('name',  's', 16,  '',   '',         'tf', ' style="width: 170px;"', '', 'Band Size Name', true, false, 'LC'),
            new tpt_ModuleField('label',  's', 255,  '',   '',         'tf', ' style="width: 170px;"', '', 'Band Size Name', false, false, 'LC'),
            new tpt_ModuleField('inches',  'f', '',  '',   '',         'tf', ' style="width: 170px;"', '', 'Inches', false, false, 'LC'),
            new tpt_ModuleField('milimenters',  'f', '',  '',   '',         'tf', ' style="width: 170px;"', '', 'Milimeters', false, false, 'LC'),
            new tpt_ModuleField('sku_comp',  's', 16,  '',   '',         'tf', ' style="width: 170px;"', '', 'Sku Component', false, false, 'LC'),
            new tpt_ModuleField('aka',  's', 64,  '',   '',         'tf', ' style="width: 170px;"', '', 'Alternative Names', false, false, 'LC'),
            new tpt_ModuleField('aka2',  's', 64,  '',   '',         'tf', ' style="width: 170px;"', '', 'Alternative Names 2', true, false, 'LC'),
            new tpt_ModuleField('post_names',  's', 1024,  '',   '',         'tf', ' style="width: 170px;"', '', 'CS list of possible POST keys translating to this size/qty', false, false, 'LC'),
            new tpt_ModuleField('sdesc',  's', 32,  '',   '',         'tf', ' style="width: 170px;"', '', 'Short Desc', false, false, 'LC'),
        );
        parent::__construct($vars, $name, $moduleClassFile, $moduleClass, $moduleTable, $fields, 'id');
    }
    
    
    function userEndData(&$vars) {
        $_temp = array();
        $rArr = $this->moduleData['id'];
        foreach($rArr as $item) {
            $_temp[$item['id']] = array('name'=>$item['name'], 'label'=>$item['label']);
        }
        //var_dump($rArr);die();
        
        $rArr = $_temp;
        //var_dump($rArr);die();
        return $rArr;
    }


	function getItems(&$vars, $input=array(), $options=array()) {
		$types_module = getModule($vars, 'BandType');
		$styles_module = getModule($vars, 'BandStyle');
		$data_module = getModule($vars, 'BandData');

		$type = $types_module->getActiveItem($vars, $input, $options);
		$style = $styles_module->getActiveItem($vars, $input, $options);
		//tpt_dump($type);
		//tpt_dump($style);
		$data = $data_module->typeStyle[$type][$style];
		$sizes = explode(',', $data['available_sizes_id']);
		$sizes = array_combine($sizes, $sizes);

		$items = $this->moduleData['id'];
		$items = array_intersect_key($items, $sizes);

		return $items;
	}
	function SB_Section(&$vars, $section, $input=array(), $options=array(), &$vinput=array()) {
		$items = $this->getItems($vars, $input, $options);



		$html = '';
		$labels = array();
		$controls = array();
		$after = array();
		$labels[] = <<< EOT
<div class="height-20 text-align-right amz_red todayshop-bold">
Size
</div>
EOT;
		$controls[] = <<< EOT
<div class="height-20 text-align-center amz_red todayshop-bold padding-left-10 padding-right-10">
Quantity
</div>
EOT;
		$after[] = <<< EOT
<div class="text-align-center amz_red todayshop-bold width-100 height-20 amz_red">
Price
</div>
EOT;
		foreach($items as $id=>$item) {
			$label = $item['label'];
			$lblcomp = explode(' / ', $label);
			if(!empty($lblcomp[1])) {
				$lblcomp[1] = preg_replace('#[\s]+(\.|[0-9]+)+"$#', '', $lblcomp[1]);
				$lblcomp[1] = '('.$lblcomp[1].')';
			}
			$label = implode('<br />', $lblcomp);
			$labels[] = <<< EOT
<div class="height-40 text-align-right urontrol">
$label:
</div>
EOT;

			$cname = $item['name'];
			$controls[] = <<< EOT
<div class="height-40 urontrol padding-left-10 padding-right-10">
<input id="qty_input_{$id}" style="border: 1px solid #ccc;border-radius: 7px;" class="background-white text-align-center width-70 plain-input-field padding-top-3 padding-bottom-3 font-size-14" type="text" onkeypress="return numbersonly(this, event);" onfocus="unhighlight_qty_fields2();" maxlength="5" size="5" value="" name="qty[{$id}]">
</div>
EOT;

			$after[] = <<< EOT
<div id="price_{$id}" class="urontrol text-align-center width-100 height-40 amz_red font-size-16">
--
</div>
EOT;
		}
		$labels[] = <<< EOT
<div class="height-40 urontrol">
&nbsp;
</div>
EOT;
		$labels[] = '&nbsp';
		$controls[] = <<< EOT
<div class="height-40 text-align-right amz_red font-size-20 todayshop-bold padding-left-10 padding-right-10">
Subtotal:
</div>
EOT;
		$controls[] = '&nbsp';
		$after[] = <<< EOT
<div id="price_subtotal" class="urontrol text-align-center width-100 height-40 amz_red font-size-16">
--
</div>
EOT;
		$after[] = '<div class="text-align-right"><input title="Update Price" class="update_price_btn plain-input-field hoverCB background-position-CT background-repeat-no-repeat update_price_btn width-83 height-21" id="upd_price_btn" type="button" value="Update" onclick="if(validate_short_builder2()){amz_update_pricing2(this);}" /></div>';



		$labels = implode("\n", $labels);
		$controls = implode("\n", $controls);
		$after = implode("\n", $after);

		$html .= <<< EOT
<div>
	<div class="display-inline-block" style="vertical-align: top;">
		$labels
	</div>
	<div class="display-inline-block" style="vertical-align: top;">
		$controls
	</div>
	<div class="display-inline-block" style="vertical-align: top;">
		$after
	</div>
</div>
EOT;


		return $html;
	}
    
    
    function BandSize_PlainSelectSDN(&$vars, $sItem, $sType=2, $sStyle=5, $pid='null') {
        $types_module = getModule($vars, "BandType");
        $data_module = getModule($vars, "BandData");
        $sztbl = $this->moduleTable;
        $tptbl = $types_module->moduleTable;
        $sizeids = !empty($data_module->typeStyle[$sType][$sStyle]['available_sizes_id'])?$data_module->typeStyle[$sType][$sStyle]['available_sizes_id']:'0';
        $query = <<< EOT
        SELECT `id`,`label` FROM `$sztbl` WHERE `id` IN($sizeids)
EOT;
//var_dump($query);die();
        $vars['db']['handler']->query($query);
        $items = $vars['db']['handler']->fetch_assoc_list('id', false);
		if(empty($items)) {
			$items = $this->moduleData['id'];
		}
        $html = '';
        $values = array();
        
        $title = 'Choose size...';

        //var_dump($sItem);
        $values[] = array(0, 'No Details');
        $values[] = array(-1, 'See Design Notes');
        
        $sOpt=0;
        if($sItem == -1)
            $sOpt = 1;
        
        $i=2;
        foreach($items as $item) {
            $values[] = array($item['id'], $item['label']);
  
            if($sItem == $item['id'])
                $sOpt = $i;
                
            $i++;
        }

        $valuesDelimiter = "\n";
        
        return tpt_html::createSelect($vars, '', $values, $sOpt, ' id="'.$pid.'_control_size_id" title="'.$title.'" onfocus="removeClass(this, \'invalid_field\');" onchange="update_product_row(this);"');
        //return tpt_html::createSelect($vars, 'band_color', $values, $selectedColor, ' title="'.$title.'"');
        
        return $html;
    }
    

}
