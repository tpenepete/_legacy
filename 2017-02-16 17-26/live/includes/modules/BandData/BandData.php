<?php

defined('TPT_INIT') or die('access denied');

class tpt_module_BandData extends tpt_Module {

    public $writable_basic = array();
    public $typeStyle = array();
    public $typeStyleFE = array();

    function __construct(&$vars, $name, $moduleClassFile, $moduleClass, $moduleTable) {
        //tpt_dump('before BandData');
        //tpt_dump(number_format(memory_get_usage()));

        $fields = array(
                //db field name|field type|length|options|storage options|control|ctrAttr|default|label|index by|split keys|template
            new tpt_ModuleField('id',    'n', null, 'ai', '',         'sp', '', '', '',           false, false,  'LC'),
            new tpt_ModuleField('type',  'i', '',  '',   'intval10',         'tf', ' style="width: 70px;"', '', 'Type id', false, false, 'LC'),
            new tpt_ModuleField('style',  'i', '',  '',   'intval10',         'tf', ' style="width: 70px;"', '', 'Style id', false, false, 'LC'),
            new tpt_ModuleField('inhouse_style',  'i', '',  '',   'intval10',         'tf', ' style="width: 70px;"', '0', 'Inhouse Style id', false, false, 'LC'),
            new tpt_ModuleField('colors_table',   's', 64,   '',   '', 'tf', ' style="width: 230px;"', 'tpt_color_overseas', 'Colors table', false, false, 'LC'),
            new tpt_ModuleField('admin_color_categories_ids',   's', 64,   '',   '', 'tf', ' style="width: 230px;"', 'tpt_color_overseas', 'CS tpt_module_bandcolorcategory ids', false, false, 'LC'),
            new tpt_ModuleField('table',   's', 64,   '',   '', 'tf', ' style="width: 230px;"', '', 'Product pricing table', false, false, 'LC'),
            new tpt_ModuleField('minimum_quantity',  'i', '',  '',   'intval10',         'tf', ' style="width: 170px;"', '', 'Minimum quantity', false, false, 'LC'),
            new tpt_ModuleField('mold_fee', 'f', '',    '',   'floatval', 'tf', ' style="width: 70px;"', '', 'Mold fee',      false, false, 'LC'),
            new tpt_ModuleField('screen_fee',  'f', '',    '',   'floatval', 'tf', ' style="width: 70px;"', '', 'Screen fee',       false, false, 'LC'),
            new tpt_ModuleField('pricing_type',   'si', 6,    '',   'intval10', 'tf', ' style="width: 70px;"', '0', 'Pricing type',        false, false, 'LC'),
			new tpt_ModuleField('has_case',   'ti', '',   '',   '', 'tf', ' style="width: 100px;"', '0', 'has case', false, false, 'LC'),
			new tpt_ModuleField('led',   'ti', '',   '',   '', 'tf', ' style="width: 100px;"', '0', 'is led band', false, false, 'LC'),
            new tpt_ModuleField('molds',  'i', '',  '',   'intval10',         'tf', ' style="width: 70px;"', '', 'Molds', false, false, 'LC'),
            new tpt_ModuleField('screens',  'i', '',  '',   'intval10',         'tf', ' style="width: 70px;"', '', 'Screens', false, false, 'LC'),
            new tpt_ModuleField('writable_screens',  'i', '',  '',   'intval10',         'tf', ' style="width: 70px;"', '', 'Writable Screens', false, false, 'LC'),
            new tpt_ModuleField('blank',  'ti', '',  '',   'intval10',         'tf', ' style="width: 70px;"', '', 'Blank', false, false, 'LC'),
            new tpt_ModuleField('message_color',  'ti', '',  '',   'intval10',         'tf', ' style="width: 70px;"', '0', 'Has Message Color?', false, false, 'LC'),
            new tpt_ModuleField('sku_comp',  's', 64,  '',   'intval10',         'tf', ' style="width: 70px;"', '', 'Sku Component', false, false, 'LC'),
            new tpt_ModuleField('dual_layer',  'i', '',  '',   '',         'tf', ' style="width: 170px;"', '0', 'Is Dual Layer?', false, false, 'LC'),
            new tpt_ModuleField('writable',  'i', '',  '',   '',         'tf', ' style="width: 170px;"', '0', 'Is Writable?', false, false, 'LC'),
            new tpt_ModuleField('full_wrap_strip',  'ti', '',  '',   '',         'tf', ' style="width: 170px;"', '0', 'Full Wrap Strip Writable', false, false, 'LC'),
            new tpt_ModuleField('writable_strip_position',  'i', '',  '',   '',         'tf', ' style="width: 170px;"', '0', 'Full Wrap Strip Writable', false, false, 'LC'),
            new tpt_ModuleField('writable_class',  'i', '',  '',   'intval10',         'tf', ' style="width: 70px;"', '0', 'Band Class', false, false, 'LC'),
            new tpt_ModuleField('base_type',  'i', '',  '',   '',         'tf', ' style="width: 170px;"', '0', 'Base Type', false, false, 'LC'),
            new tpt_ModuleField('preview_folder',   's', 64,   '',   '', 'tf', ' style="width: 100px;"', '', 'Preview images folder', false, false, 'LC'),
            new tpt_ModuleField('preview_width',   'i', '',   '',   '', 'tf', ' style="width: 100px;"', '', 'Preview Width', false, false, 'LC'),
            new tpt_ModuleField('preview_height',   'i', '',   '',   '', 'tf', ' style="width: 100px;"', '', 'Preview Height', false, false, 'LC'),
            new tpt_ModuleField('preview_toppadding',   'i', '',   '',   '', 'tf', ' style="width: 100px;"', '', 'Preview Top Padding', false, false, 'LC'),
            new tpt_ModuleField('preview_bottompadding',   'i', '',   '',   '', 'tf', ' style="width: 100px;"', '', 'Preview Bottom Padding', false, false, 'LC'),
            new tpt_ModuleField('preview_leftpadding',   'i', '',   '',   '', 'tf', ' style="width: 100px;"', '', 'Preview Left Padding', false, false, 'LC'),
            new tpt_ModuleField('preview_rightpadding',   'i', '',   '',   '', 'tf', ' style="width: 100px;"', '', 'Preview Right Padding', false, false, 'LC'),
            new tpt_ModuleField('preview_bg_toppadding',   'i', '',   '',   '', 'tf', ' style="width: 100px;"', '', 'Preview BG Top Padding', false, false, 'LC'),
            new tpt_ModuleField('preview_bg_bottompadding',   'i', '',   '',   '', 'tf', ' style="width: 100px;"', '', 'Preview BG Bottom Padding', false, false, 'LC'),
            new tpt_ModuleField('preview_bg_width',   'i', '',   '',   '', 'tf', ' style="width: 100px;"', '', 'Preview BG Width', false, false, 'LC'),
            new tpt_ModuleField('preview_bg_height',   'i', '',   '',   '', 'tf', ' style="width: 100px;"', '', 'Preview BG Height', false, false, 'LC'),
            new tpt_ModuleField('preview_css_background_fix_x',   's', 64,   '',   '', 'tf', ' style="width: 100px;"', '', 'Preview BG Fix X', false, false, 'LC'),
            new tpt_ModuleField('preview_css_background_fix_y',   's', 64,   '',   '', 'tf', ' style="width: 100px;"', '', 'Preview BG Fix Y', false, false, 'LC'),
            new tpt_ModuleField('preview_css_background_fix_x2',   's', 64,   '',   '', 'tf', ' style="width: 100px;"', '', 'Preview BG Fix X2', false, false, 'LC'),
            new tpt_ModuleField('preview_css_background_fix_y2',   's', 64,   '',   '', 'tf', ' style="width: 100px;"', '', 'Preview BG Fix Y2', false, false, 'LC'),
            new tpt_ModuleField('preview_message_front_fontsize',   'i', '',   '',   '', 'tf', ' style="width: 100px;"', '0', 'Preview Front Message Default Font Size', false, false, 'LC'),
            new tpt_ModuleField('preview_message_front2_fontsize',   'i', '',   '',   '', 'tf', ' style="width: 100px;"', '0', 'Preview Front Line2 Message Default Font Size', false, false, 'LC'),
            new tpt_ModuleField('preview_message_back_fontsize',   'i', '',   '',   '', 'tf', ' style="width: 100px;"', '0', 'Preview Back Message Default Font Size', false, false, 'LC'),
            new tpt_ModuleField('preview_message_back2_fontsize',   'i', '',   '',   '', 'tf', ' style="width: 100px;"', '0', 'Preview Back Line 2 Message Default Font Size', false, false, 'LC'),
            new tpt_ModuleField('preview_xlayer_type',   'i', '',   '',   '', 'tf', ' style="width: 100px;"', '0', 'Dual Layer Extra Layer Type', false, false, 'LC'),
            new tpt_ModuleField('preview_xlayer_class',   's', 64,   '',   '', 'tf', ' style="width: 100px;"', '0', 'Dual Layer Extra Layer Class', false, false, 'LC'),
            new tpt_ModuleField('preview_xlayer_height',   'i', '',   '',   '', 'tf', ' style="width: 100px;"', '0', 'Dual Layer Extra Layer Height', false, false, 'LC'),
            new tpt_ModuleField('available_sizes_id',   's', 255,   '',   '', 'tf', ' style="width: 100px;"', '2,3,4,5', 'Available sizes ids', false, false, 'LC'),
            new tpt_ModuleField('disabled_fonts_ids',   's', 255,   '',   '', 'tf', ' style="width: 100px;"', '', 'CS tpt_module_bandfont ids', false, false, 'LC'),
            new tpt_ModuleField('text_layouts',   'i', '',   '',   '', 'tf', ' style="width: 100px;"', '3', 'Available Text Layouts', false, false, 'LC'),
            new tpt_ModuleField('default_text_layout',   'i', '',   '',   '', 'tf', ' style="width: 100px;"', '2', 'Default Text Layout', false, false, 'LC'),
            new tpt_ModuleField('text_lines_num',   'i', '',   '',   '', 'tf', ' style="width: 100px;"', '2', 'Number of Message Lines', false, false, 'LC'),
            new tpt_ModuleField('text_back_msg',   'ti', '',   '',   '', 'tf', ' style="width: 100px;"', '1', 'Back Message Applicable', false, false, 'LC'),
            new tpt_ModuleField('invert_dual_control',   'ti', '',   '',   '', 'tf', ' style="width: 100px;"', '0', 'Show Invert Dual Checkbox', false, false, 'LC'),
            new tpt_ModuleField('cut_away_control',   'ti', '',   '',   '', 'tf', ' style="width: 100px;"', '0', 'Show Cut-Away Checkbox', false, false, 'LC'),
            new tpt_ModuleField('invert_screenprint_control',   'ti', '',   '',   '', 'tf', ' style="width: 100px;"', '0', 'Show Invert Screenprint Checkbox', false, false, 'LC'),
            new tpt_ModuleField('default_builder',   'i', '',   '',   '', 'tf', ' style="width: 100px;"', '0', 'Default Builder', false, false, 'LC'),
			new tpt_ModuleField('new_preview',   'ti', '',   '',   '', 'tf', ' style="width: 100px;"', '0', 'Use New Preview System', false, false, 'LC'),
			new tpt_ModuleField('preview_layers',   's', 64,   '',   '', 'tf', ' style="width: 100px;"', '', 'CS tpt_module_previewlayer ids', false, false, 'LC'),
			new tpt_ModuleField('old_preview_layers',   's', 64,   '',   '', 'tf', ' style="width: 100px;"', '', 'old CS tpt_module_previewlayer ids', false, false, 'LC'),
			new tpt_ModuleField('clearband_layer',   'ti', '',   '',   '', 'tf', ' style="width: 100px;"', '1', 'Underlay Clearband Layer', false, false, 'LC'),
			new tpt_ModuleField('layouts_ids',   's', 64,   '',   '', 'tf', ' style="width: 230px;"', '', 'CS tpt_module_bandlayout ids', false, false, 'LC'),
			new tpt_ModuleField('builder_sections',   's', 64,   '',   '', 'tf', ' style="width: 100px;"', '0', 'CS tpt_module_buildersection ids', false, false, 'LC'),
			new tpt_ModuleField('enabled',   'i', '',   '',   '', 'tf', ' style="width: 100px;"', '1', 'Enabled', false, false, 'LC'),
			/*
            new tpt_ModuleField('sb_onfocus',   's', 255,   '',   '', 'tf', ' style="width: 100px;"', '', 'Short Builder onfocus property', false, false, 'LC'),
            new tpt_ModuleField('sb_onclick',   's', 255,   '',   '', 'tf', ' style="width: 100px;"', '', 'Short Builder onclick property', false, false, 'LC'),
            new tpt_ModuleField('sb_onchange',   's', 255,   '',   '', 'tf', ' style="width: 100px;"', '', 'Short Builder onchange property', false, false, 'LC'),
            new tpt_ModuleField('sb_oninput',   's', 255,   '',   '', 'tf', ' style="width: 100px;"', '', 'Short Builder oninput property', false, false, 'LC'),
            new tpt_ModuleField('sb_onpropertychange',   's', 255,   '',   '', 'tf', ' style="width: 100px;"', '', 'Short Builder onpropertychange property', false, false, 'LC'),
            new tpt_ModuleField('sb_onkeypress',   's', 255,   '',   '', 'tf', ' style="width: 100px;"', '', 'Short Builder onkeypress property', false, false, 'LC'),
			*/
        );


		$db = $vars['db']['handler'];
		$types_module = getModule($vars, 'BandType');
		$types_table = $types_module->moduleTable;

        $bdata = $vars['db']['handler']->getData($vars, $moduleTable, '*', ' `enabled`=1', 'id', false);
        foreach($bdata as $bdrow) {
            if(empty($this->typeStyle[$bdrow['type']]) || !is_array($this->typeStyle[$bdrow['type']])) {
                $this->typeStyle[$bdrow['type']] = array();
            }
            if(empty($this->typeStyleFE[$bdrow['type']]) || !is_array($this->typeStyleFE[$bdrow['type']])) {
                $this->typeStyleFE[$bdrow['type']] = array();
            }
            $this->typeStyle[$bdrow['type']][$bdrow['style']] = $bdrow;
            $this->typeStyleFE[$bdrow['type']][$bdrow['style']] = array('min_qty'=>$bdrow['minimum_quantity'], 'type'=>$bdrow['type'], 'style'=>$bdrow['style'], 'extralayer'=>intval($bdrow['preview_xlayer_type'],10), 'preview_folder'=>$bdrow['preview_folder'], 'sizes'=>$bdrow['available_sizes_id'], 'preview_layers'=>$bdrow['preview_layers'], 'disabled_fonts_ids'=>$bdrow['disabled_fonts_ids']);
        }

		//$this->writable_basic = $vars['db']['handler']->getData($vars, $moduleTable, '*', ' (`writable_class`=1) ORDER BY `id`', 'base_type', false);
		$query = <<< EOT
SELECT
	`$types_table`.`id`, `$types_table`.`name`, `$moduleTable`.`base_type`
FROM
	`$moduleTable`
LEFT JOIN
	`$types_table`
ON
	`$moduleTable`.`type`=`$types_table`.`id`
WHERE
	`writable`!=0 AND `writable_class`=1
GROUP BY
	`$moduleTable`.`base_type`
EOT;
		$db->query($query);
		$this->writable_basic = $db->fetch_assoc_list('base_type', false);

        //tpt_dump('after BandData');
        //tpt_dump(number_format(memory_get_usage()));

        parent::__construct($vars, $name, $moduleClassFile, $moduleClass, $moduleTable, $fields, 'id');
    }

    function userEndData(&$vars) {
        return $this->typeStyleFE;
    }

    /*
    function BandColor_Select(&$vars) {
        $query = 'SELECT `id`, `HEX`, `name` FROM `'.$this->moduleTable.'`';
        $vars['db']['handler']->query($query, __FILE__);
        $colors = $vars['db']['handler']->fetch_assoc_list();

        $html = '';
        $values = array();

        foreach($colors as $color) {
            preg_match('#[a-zA-Z\s]{3,}#', $color['name'], $mtch);
            if(!empty($mtch))
                $values[] = array($color['HEX'], '<span class="width-75 height-15 display-inline-block padding-left-2 padding-right-2 line-height-15 white-space-nowrap" style="border: 1px solid #555;background-color: #'.$color['HEX'].';">'.$color['name'].'</span>', $color['name']);
        }

        $valuesDelimiter = "\n";

        $html = tpt_html::createStyledSelect($vars, 'BandColor', $values, $valuesDelimiter, ' width:151px;', ' width:90px;', ' padding-top-10', 0, 'tpt_pg_updateBandColor', 'tpt_pg_color');

        return $html;
    }

    function SwirlColor_Select(&$vars) {
        $query = 'SELECT `id`, `HEX`, `name` FROM `'.$this->moduleTable.'`';
        $vars['db']['handler']->query($query, __FILE__);
        $colors = $vars['db']['handler']->fetch_assoc_list();

        $html = '';
        $values = array();

        foreach($colors as $color) {
            preg_match('#[a-zA-Z\s]{3,}#', $color['name'], $mtch);
            if(!empty($mtch))
                $values[] = array($color['HEX'], '<span class="width-75 height-15 display-inline-block padding-left-2 padding-right-2 line-height-15 white-space-nowrap" style="border: 1px solid #555;background-color: #'.$color['HEX'].';">'.$color['name'].'</span>', $color['name']);
        }

        $valuesDelimiter = "\n";

        $html = tpt_html::createStyledSelect($vars, 'SwirlColor', $values, $valuesDelimiter, ' width:151px;', ' width:90px;', ' padding-top-10', 0, 'tpt_pg_updateSwirlColor', 'tpt_pg_sw_color');

        return $html;
    }
    */

}
