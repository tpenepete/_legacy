<?php

defined('TPT_INIT') or die('access denied');

class tpt_module_BandColor extends tpt_Module
{

    public $by_hex;
    public $by_id;
    public $solid;
    public $swirl;
    public $segment;
    public $special;
    public $led;
    public $sp_multi;
    public $sp_glow;
    public $sp_glitter;
    public $dual;

    public $invalid;

    public $all_colors = array();

    public $colorTypes;

    public $queriesDir;

    static $pgBandColor = false;
    static $bandColorContent = false;
    static $pgMessageColor = false;
    static $messageColorContent = false;


    function __construct(&$vars, $name, $moduleClassFile, $moduleClass, $moduleTable)
    {
        //tpt_dump('before BandColor');
        //tpt_dump(number_format(memory_get_usage()));
        $fields = array(
            //db field name|field type|length|options|storage options|control|ctrAttr|default|label|index by|split keys|template
            new tpt_ModuleField('id', 'n', null, 'ai', '', 'sp', '', '', '', true, false, 'LC'),
            new tpt_ModuleField('name', 's', 255, '', '', 'tf', ' style="width: 70px;"', '', 'Color Name', false, false, 'LC'),
            new tpt_ModuleField('nickname', 's', 255, '', '', 'tf', ' style="width: 70px;"', '', 'Color Nickname', false, false, 'LC'),
            new tpt_ModuleField('hex', 's', 255, '', '${str_pad(dechex(`red`),2,STR_PAD_LEFT)}${str_pad(dechex(`green`),2,STR_PAD_LEFT)}${str_pad(dechex(`blue`),2,STR_PAD_LEFT)}', 'tf', 'disabled="disabled"', '', 'hex Value', false, false, 'LC'),
            '<div class="tpt_admin_module_section float-left" style="border: 2px solid #FFF;">',
            new tpt_ModuleField('red', 'i', '', '', 'intval10', 'tf', ' style="width: 70px;"', '', 'Red', false, false, 'LC'),
            new tpt_ModuleField('green', 'i', '', '', 'intval10', 'tf', ' style="width: 70px;"', '', 'Green', false, false, 'LC'),
            new tpt_ModuleField('blue', 'i', '', '', 'intval10', 'tf', ' style="width: 70px;"', '', 'Blue', false, false, 'LC'),
            '</div>',
            new tpt_ModuleField('hue', 'f', '', '', '', 'tf', ' style="width: 70px;"', '', 'Hue', false, false, 'LC'),
            new tpt_ModuleField('glow', 'i', 1, '', 'intval10', 'tf', ' style="width: 70px;"', '', 'Glow', false, false, 'LC'),
            new tpt_ModuleField('popular', 'i', 1, '', 'intval10', 'tf', ' style="width: 70px;"', '', 'Popular', false, false, 'LC'),
            new tpt_ModuleField('image', 's', 333, '', '', 'tf', ' style="width: 70px;"', '', 'image', false, false, 'LC'),
            new tpt_ModuleField('true_pms', 's', 255, '', '', 'tf', ' style="width: 70px;"', '', 'PMS id', false, false, 'LC'),

            //new tpt_ModuleField('category_id',  'i', '',  '',   'intval10',         'tf', ' style="width: 30px;"', '', 'Parent Category', false, false, 'LC'),
            //'<div class="float-left padding-top-20 padding-bottom-20 padding-left-10 padding-right-10" style="background-color: #FFF;"><div class="display-inline-block height-10 width-80" style="background-color: #`hex`; border: 1px solid #000;"></div></div>',
            '<div class="float-left" style="background-color: #`hex`; border: 1px solid #000;"><img src="' . $vars['config']['resourceurl'] . '/images/Band-Transperent-Preview.png" class="width-80" /></div>',
            '<div class="float-left" style="background-color: #`hex`; border: 1px solid #000;"><img src="' . $vars['config']['resourceurl'] . '/images/Transparent-Swirl-Band-Preview.png" class="width-80" /></div>'
        );
        $moduleTable = 'colors_data';

        $this->colorTypes = array(
            'solidstock1' => array('id' => '1', 'label' => 'Solid', 'name' => 'solid', 'attr' => ''),
            'solidstock2' => array('id' => '1', 'label' => 'Solid', 'name' => 'solid', 'attr' => ''),
            'overseasswirl' => array('id' => '2', 'label' => 'Swirl', 'name' => 'swirl', 'attr' => ''),
            'overseassegmented' => array('id' => '3', 'label' => 'Segmented', 'name' => 'segmented', 'attr' => ''),
            'overseasglitter' => array('id' => '6', 'label' => 'Glitter', 'name' => 'glitter', 'attr' => ''),
            'overseasglow' => array('id' => '7', 'label' => 'Glow', 'name' => 'glow', 'attr' => ''),
            'inhousemulticolor' => array('id' => '5', 'label' => 'Multicolored', 'name' => 'multic', 'attr' => ''),
            'inhouseglitter' => array('id' => '5', 'label' => 'Multicolored', 'name' => 'multic', 'attr' => ''),
            'inhouseglow' => array('id' => '6', 'label' => 'Glitter', 'name' => 'glitter', 'attr' => ''),
            'slapbandmulticolor1' => array('id' => '5', 'label' => 'Multicolored', 'name' => 'multic', 'attr' => ''),
            'slapbandmulticolor2' => array('id' => '5', 'label' => 'Swirl', 'name' => 'multic', 'attr' => ''),
            'slapbandmulticolor2' => array('id' => '5', 'label' => 'Swirl', 'name' => 'multic', 'attr' => ''),
            'duallayersolid' => array('id' => '1', 'label' => 'Solids', 'name' => 'solid', 'attr' => ' disabled="disabled"'),
            'duallayermulticolor1' => array('id' => '5', 'label' => 'Multi-Colored Msg', 'name' => 'multic', 'attr' => ''),
            'duallayermulticolor2' => array('id' => '5', 'label' => 'Multi-Colored Band', 'name' => 'multic', 'attr' => ''),
            'duallayerglitter1' => array('id' => '6', 'label' => 'Glitter Msg', 'name' => 'glitter', 'attr' => ''),
            'duallayerglitter2' => array('id' => '6', 'label' => 'Glitter Band', 'name' => 'glitter', 'attr' => ''),
            'duallayerglow1' => array('id' => '7', 'label' => 'Glow Msg', 'name' => 'glow', 'attr' => ''),
            'duallayerglow2' => array('id' => '7', 'label' => 'Glow Band', 'name' => 'glow', 'attr' => ''),
            'duallayerpowdercoat' => array('id' => '8', 'label' => 'Powder Coated', 'name' => 'powdercoat', 'attr' => ''),
            'duallayeredge' => array('id' => '9', 'label' => 'Edge', 'name' => 'notched', 'attr' => ''),
            'led' => array('id' => '11', 'label' => 'LED', 'name' => 'led', 'attr' => ''),
        );

        $this->queriesDir = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'queries';

        $this->by_hex = $vars['db']['handler']->getData($vars, $moduleTable, '*', '', 'hex', false);
        $this->by_id = $vars['db']['handler']->getData($vars, $moduleTable, '*', '', 'id', false);

        $this->solid = $vars['db']['handler']->getData($vars, 'tpt_color_overseas', '*', '`color_type`=3 AND `enabled`=1 ORDER BY  `label` ASC ', 'id', false);

        $this->swirl = $vars['db']['handler']->getData($vars, 'tpt_color_overseas', '*', '`color_type`=4 AND `enabled`=1 ORDER BY  `label` ASC ', 'id', false);
        //tpt_dump($this->swirl,true);
        $this->segment = $vars['db']['handler']->getData($vars, 'tpt_color_overseas', '*', '`color_type`=5 AND `enabled`=1 ORDER BY  `label` ASC ', 'id', false);
        $this->dual = $vars['db']['handler']->getData($vars, 'tpt_color_duallayer', '*', '`enabled`=1 ORDER BY  `label` ASC ', 'id', false);
        $this->special = $vars['db']['handler']->getData($vars, 'tpt_color_special', '*', '`enabled`=1 ORDER BY  `label` ASC', 'id', false);
        $this->led = $vars['db']['handler']->getData($vars, 'tpt_color_led', '*', '`enabled`=1 ORDER BY  `label` ASC', 'id', false);
        //$this->sp_multi = $vars['db']['handler']->getData($vars, 'tpt_color_special', 'id,label', '`enabled`=1 AND (`color_type`=2 OR `color_type`=3)', 'id', false);
        //$this->sp_glow = $vars['db']['handler']->getData($vars, 'tpt_color_special', 'id,label', '`enabled`=1 AND `glow`=1', 'id', false);
        //$this->sp_glitter = $vars['db']['handler']->getData($vars, 'tpt_color_special', 'id,label', '`enabled`=1 AND `glitter`=1', 'id', false);

        $this->invalid = array(INVALID_COLOR_PRESET_SUBSTITUTE_COLOR => reset($this->dual));
        $this->invalid[INVALID_COLOR_PRESET_SUBSTITUTE_COLOR]['label'] = 'Invalid Color';
        $this->invalid[INVALID_COLOR_PRESET_SUBSTITUTE_COLOR]['enabled'] = '1';

        $this->all_colors = array(
            INVALID_COLOR_PRESET_SUBSTITUTE_TABLE => $this->invalid,
            0 => $this->by_id,
            1 => 'Custom Swirl',
            2 => 'Custom Segment',
            3 => $this->solid,
            4 => $this->swirl,
            5 => $this->segment,
            6 => $this->special,
            7 => null,//$this->sp_multi,
            8 => null,//$this->sp_glow,
            9 => null,//$this->sp_glitter,
            10 => $this->dual,
            11 => $this->led
        );

        //tpt_dump('after BandColor');
        //tpt_dump(number_format(memory_get_usage()));

        parent::__construct($vars, $name, $moduleClassFile, $moduleClass, $moduleTable, $fields, 'id');
    }


    function getColorProps(&$vars, $color, $what = 'default')
    {
        //var_dump($color);die();
        //var_dump('asd');die();
        //var_dump('asd');//die();
        //var_dump(debug_backtrace());die();
        //debug_print_backtrace();
        //die();
        /*
		$color_properties = array(
			1=>array('label'=>'', 'name'=>'glow'),
			2=>array('label'=>'', 'name'=>'glitter'),
			3=>array('label'=>'', 'name'=>'uv')
		);
		*/

        //var_dump($tid);//die();
        //var_dump($colorId);//die();
        //var_dump($color);die();
        //https://www.amazingwristbands.com/live/generate-preview?font=LuckiestGuy.ttf&pg_x=349&type=plain&timestamp=1363739322161&bandType=2&bandStyle=7&textColor=-1:7c85e3&fontSize=46&pg_y=48&text=Front%20Message
        //tpt_dump($vars, true);
        //tpt_dump($vars['modules']['handler'], true);
        //tpt_dump($vars['modules']['handler']->modules, true);
        $data_module = getModule($vars, "BandData");
        $tfield = 'available_types_ids2';

        $product_color = array();
        if (isset($color) && is_string($color)) {
            $product_color = explode(':', $color);
        }
        //$tableId = intval($product_color[0], 10);
		$tableId = (!empty($product_color[0])?intval($product_color[0], 10):0);
        //tpt_dump($color);
        //tpt_logger::dump($vars, $product_color, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), __FILE__, __LINE__);
        //tpt_dump($color, true, var_export(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)).__FILE__, __LINE__);
        $colorId = isset($product_color[1]) ? $product_color[1] : null;


        $cprops = array();
        if (!empty($product_color[2]))
            $cprops = explode(',', $product_color[2]);

        $colordata = array(
            'preset' => null,
            'band_color_type' => array(),
            'band_colors' => array(),
            'band_tableId' => array(),
            'band_colorId' => array(),
            'band_uid' => array(),
            'message_preset' => null,
            'message_colors' => array(),
            'message_hex' => '',
            'message_hexarray' => array(),
            'message_color_type' => '',
            'message_glow' => '',
            'message_glitter' => '',
            'message_uv' => '',
            'message_powdercoat' => '',
            'message_tableId' => '',
            'message_colorId' => '',
            'message_uid' => '',
            'led_color_type' => array(),
            'led_colors' => array(),
            'led_tableId' => array(),
            'led_colorId' => array(),
            'led_uid' => array(),
            'case_color_type' => array(),
            'case_colors' => array(),
            'case_tableId' => array(),
            'case_colorId' => array(),
            'case_uid' => array(),
            'custom_color' => 1,
            'transparent_case' => 0,

        );
        $colordef = false;
        $colorname = null;
        $colorcategory = 'Solid';
        $colortypename = false;
        $gClass = 'Solid';
        $colordefinition = 'N/A';
        $swirl = 0;
        $segmented = 0;
        $led = 0;
        $dual_layer = 0;
        $notched = 0;
        $glow = 0;
        $glitter = 0;
        $uv = 0;
        $powdercoat = 0;
        $transparent_case = 0;
        $custom_color = 0;
        $overseas = 0;
        $overseas_preset = 0;
        $invalid_color = 0;
        $colors_count = 0;
        $hex = '';
        $hexarray = array();
        $id = '';
        $idarray = array();
        $swap_dual_color = 0;


        //$tid = 0;
        //if(!empty($tableId))
        //var_dump($tid);
        //var_dump($colorId);
        $colorProps = array();

        switch ($tableId) {
            case -3:
                $colordef = true;
                $colordefinition = 'Custom HexVal Segmented';
                $colorcategory = 'Custom Segmented';
                $colortypename = 'segmented';
            //$gClass = 'Segmented';
            //$gClass = 'Segmented';
            case -2:
                $colordefinition = empty($colordef) ? 'Custom HexVal Swirl' : $colordefinition;
                $colorcategory = empty($colordef) ? 'Custom Swirl' : $colorcategory;
                $colortypename = empty($colortypename) ? 'swirl' : $colortypename;
                //$gClass = empty($gClass)?'Swirl':$gClass;
                //$gClass = 'Swirl';

                $id = $colorId;
                $idarray = explode(',', $id);
                $colors_count = count($idarray);
                $custom_color = 1;

                //$rawcolors = array_intersect_key($this->by_id, array_combine($idarray, array_flip($idarray)));
                $rawcolors = array();
                foreach ($idarray as $cc) {
                    $rawcolors[] = $cc;
                }
                $hexarray = $rawcolors;
                if (empty($hexarray))
                    $hexarray = array();
                $hex = implode(',', $hexarray);

                if ($tableId == -2) {
                    if ($colors_count > 4) {
                        $swirl = 2;
                    } else {
                        $swirl = 1;
                    }
                } else if ($tableId == -3) {
                    if ($colors_count > 4) {
                        $segmented = 2;
                    } else {
                        $segmented = 1;
                    }
                }

                if (in_array(1, $cprops))
                    $glow = 1;
                if (in_array(2, $cprops))
                    $glitter = 1;
                if (in_array(3, $cprops))
                    $uv = 1;
                if (in_array(4, $cprops))
                    $powdercoat = 1;
            case -1:
                $colordefinition = 'Custom HexVal Solid';
                $colorcategory = 'Custom Solid';
                $colortypename = empty($colortypename) ? 'solid' : $colortypename;
                //$colortypename = 'solid';
                //$gClass = empty($gClass)?'Solid':$gClass;
                //$gClass = 'Solid';

                $colors_count = 1;
                $custom_color = 1;

                $id = $colorId;
                $idarray = explode(',', $id);

                $colordata['band_uid'] = $tableId . ':' . $colorId;
                $colordata['band_color_type'] = 'Solid';
                $colordata['message_uid'] = $tableId . ':' . $colorId;
                $colordata['message_color_type'] = 'Solid';

                //$rawcolors = array_intersect_key($this->by_id, array_combine($idarray, array_flip($idarray)));
                $rawcolors = array();
                foreach ($idarray as $cc) {
                    $rawcolors[] = $cc;
                }
                $hexarray = $rawcolors;
                if (empty($hexarray))
                    $hexarray = array();
                $hex = implode(',', $hexarray);


                if (in_array(1, $cprops))
                    $glow = 1;
                if (in_array(2, $cprops))
                    $glitter = 1;
                if (in_array(3, $cprops))
                    $uv = 1;
                if (in_array(4, $cprops))
                    $powdercoat = 1;
                break;
            case 0:
                //var_dump($color);die();
                $colordefinition = 'Custom Solid';
                $colorcategory = 'Custom Solid';
                $colortypename = 'solid';
                //$gClass = 'Solid';

                $colors_count = 1;
                $custom_color = 1;

                //tpt_dump($colorId, true);
                if (!empty($this->by_id[$colorId])) {
                    $bcdata = $this->by_id[$colorId];
                } else {
                    $bcdata = $this->by_id[INVALID_COLOR_SUBSTITUTE_COLOR];
                    $invalid_color = 1;
                }
                $colorname = $bcdata['name'];
                $colordata['preset'] = $bcdata;
                $colordata['band_colors'][$id] = $bcdata;
                //tpt_dump($colorname);

                $id = $colorId;
                $idarray = array($id);
                $hex = $bcdata['hex'];

                $hexarray = array($hex);
                //var_dump($hexarray);die();
                //tpt_dump($hexarray);

                if (in_array(1, $cprops))
                    $glow = 1;
                if (in_array(2, $cprops))
                    $glitter = 1;
                if (in_array(3, $cprops))
                    $uv = 1;
                if (in_array(4, $cprops))
                    $powdercoat = 1;

                $overseas = 1;

                break;
            case 1:
                $colordef = true;
                $colordefinition = 'Custom Swirl';
                $colorcategory = 'Custom Swirl';
                $colortypename = 'swirl';
            //$gClass = 'Swirl';
            case 2:
                $colordefinition = empty($colordef) ? 'Custom Segmented' : $colordefinition;
                $colorcategory = empty($colordef) ? 'Custom Segmented' : $colorcategory;
                $colortypename = empty($colortypename) ? 'segmented' : $colortypename;
                //$gClass = empty($colordef)?'Segmented':$gClass;

                $id = $colorId;
                $idarray = explode(',', $id);
                $colors_count = count($idarray);
                $custom_color = 1;

                //$rawcolors = array_intersect_key($this->by_id, array_combine($idarray, array_flip($idarray)));
                $rawcolors = array();
                foreach ($idarray as $cc) {
                    if (!empty($this->by_id[$cc])) {
                        $rawcolors[] = $this->by_id[$cc];
                    } else {
                        $rawcolors[] = $this->by_id[INVALID_COLOR_SUBSTITUTE_COLOR];
                        //tpt_dump('invalid');
                        $invalid_color = 1;
                    }
                }
                $hexarray = array_reduce($rawcolors, 'getHexField', array());
                if (empty($hexarray))
                    $hexarray = array();
                $hex = implode(',', $hexarray);

                $overseas = 1;

                if (in_array(1, $cprops))
                    $glow = 1;
                if (in_array(2, $cprops))
                    $glitter = 1;
                if (in_array(3, $cprops))
                    $uv = 1;
                if (in_array(4, $cprops))
                    $powdercoat = 1;


                if ($tableId == 1) {
                    if ($colors_count > 4) {
                        $swirl = 2;
                    } else {
                        $swirl = 1;
                    }
                } else if ($tableId == 2) {
                    if ($colors_count > 4) {
                        $segmented = 2;
                    } else {
                        $segmented = 1;
                    }
                }
                break;

            case 3:
                $colordefinition = 'Suggested Solid';
                $colorcategory = 'Solid';
                $colortypename = 'solid';
                //$gClass = 'Solid';

                //tpt_dump($tableId, false);
                //tpt_dump($colorId, true);
                if (!empty($this->all_colors[$tableId][$colorId])) {
                    $colordata['preset'] = $bcdata = $this->all_colors[$tableId][$colorId];
                } else {
                    $colordata['preset'] = $bcdata = $this->all_colors[INVALID_COLOR_PRESET_SUBSTITUTE_TABLE][INVALID_COLOR_PRESET_SUBSTITUTE_COLOR];
                    $invalid_color = 1;
                }
                $colorname = $bcdata['label'];


                $id = $bcdata['color_id'];
                $idarray = explode(',', $id);
                $colors_count = count($idarray);

                //$rawcolors = array_intersect_key($this->by_id, array_combine($idarray, array_flip($idarray)));
                $rawcolors = array();
                foreach ($idarray as $cc) {
                    $rawcolors[] = $this->by_id[$cc];
                    $colordata['band_colors'][$cc] = $this->by_id[$cc];
                }
                $hexarray = array_reduce($rawcolors, 'getHexField', array());
                if (empty($hexarray))
                    $hexarray = array();
                $hex = implode(',', $hexarray);

                $overseas = 1;
                $overseas_preset = 1;

                $glow = $bcdata['glow'];
                $glitter = $bcdata['glitter'];
                $uv = $bcdata['uv'];
                $powdercoat = $bcdata['powdercoat'];

                //$colors_count=1;
                break;
            case 4:
                $colordef = true;
                $colordefinition = 'Suggested Swirl';
                $colorcategory = 'Swirl';
                $colortypename = 'swirl';
            //$gClass = 'Swirl';
            case 5:
                $colordefinition = empty($colordef) ? 'Suggested Segmented' : $colordefinition;
                $colorcategory = empty($colordef) ? 'Segmented' : $colorcategory;
                $colortypename = empty($colortypename) ? 'segmented' : $colortypename;
                //$gClass = empty($colordef)?'Segmented':$gClass;

                if (!empty($this->all_colors[$tableId][$colorId])) {
                    $colordata['preset'] = $bcdata = $this->all_colors[$tableId][$colorId];
                } else {
                    $colordata['preset'] = $bcdata = $this->all_colors[INVALID_COLOR_PRESET_SUBSTITUTE_TABLE][INVALID_COLOR_PRESET_SUBSTITUTE_COLOR];
                    $invalid_color = 1;
                }
                $colorname = $bcdata['label'];

                $id = $bcdata['color_id'];
                $idarray = explode(',', $id);
                $colors_count = count($idarray);

                //$rawcolors = array_intersect_key($this->by_id, array_combine($idarray, array_flip($idarray)));
                $rawcolors = array();
                foreach ($idarray as $cc) {
                    $rawcolors[] = $this->by_id[$cc];
                    $colordata['band_colors'][$cc] = $this->by_id[$cc];
                }
                $hexarray = array_reduce($rawcolors, 'getHexField', array());
                if (empty($hexarray))
                    $hexarray = array();
                $hex = implode(',', $hexarray);


                if ($tableId == 4) {
                    if ($colors_count > 4) {
                        $swirl = 2;
                    } else {
                        $swirl = 1;
                    }
                } else if ($tableId == 5) {
                    if ($colors_count > 4) {
                        $segmented = 2;
                    } else {
                        $segmented = 1;
                    }
                }

                $overseas = 1;
                $overseas_preset = 1;

                $glow = $bcdata['glow'];
                $glitter = $bcdata['glitter'];
                $uv = $bcdata['uv'];
                $powdercoat = $bcdata['powdercoat'];
                break;
            case 6:
                if (!empty($this->all_colors[$tableId][$colorId])) {
                    $colordata['preset'] = $bcdata = $this->all_colors[$tableId][$colorId];
                } else {
                    $colordata['preset'] = $bcdata = $this->all_colors[INVALID_COLOR_PRESET_SUBSTITUTE_TABLE][INVALID_COLOR_PRESET_SUBSTITUTE_COLOR];
                    $invalid_color = 1;
                }
                if (empty($bcdata)) {
                    $query = 'SELECT * FROM `tpt_color_special` WHERE `id`=' . $colorId;
                    $vars['db']['handler']->query($query, __FILE__);
                    $bcdata = $vars['db']['handler']->fetch_assoc_list('id', false);
                    if (!empty($bcdata)) {
                        $bcdata = reset($bcdata);
                    }
                }
                $colorname = $bcdata['label'];
                //var_dump($tid);//die();
                //var_dump($colorId);//die();
                //var_dump($color);die();
                //debug_backtrace();
                //die();
                //var_dump(debug_backtrace());die();

                $id = $bcdata['color_id'];
                $idarray = explode(',', $id);
                $colors_count = count($idarray);

                $rawcolors = array();
                foreach ($idarray as $cc) {
                    $rawcolors[] = $this->by_id[$cc];
                    $colordata['band_colors'][$cc] = $this->by_id[$cc];
                }
                //$rawcolors = array_intersect_key($this->by_id, array_combine($idarray, array_flip($idarray)));
                $hexarray = array_reduce($rawcolors, 'getHexField', array());
                if (empty($hexarray))
                    $hexarray = array();
                $hex = implode(',', $hexarray);

                if ($bcdata['color_type'] == 4) {
                    if ($colors_count > 4) {
                        $swirl = 2;
                    } else {
                        $swirl = 1;
                    }
                } else if ($bcdata['color_type'] == 5) {
                    //$colortypename = empty($colordef)?'segmented':$colortypename;
                    //$gClass = empty($colordef)?'Segmented':$gClass;
                    if ($colors_count > 4) {
                        $segmented = 2;
                    } else {
                        $segmented = 1;
                    }
                }

                //var_dump($color);//die();
                //var_dump($swirl);//die();
                //var_dump($segmented);//die();

                if (!empty($swirl)) {
                    $colordefinition = 'Stock Swirl';
                    $colorcategory = 'Swirl';
                    $colortypename = 'swirl';
                } else if (!empty($segmented)) {
                    $colordefinition = 'Stock Segmented';
                    $colorcategory = 'Segmented';
                    $colortypename = 'segmented';
                } else {
                    $colordefinition = 'Stock Solid';
                    $colorcategory = 'Solid';
                    $colortypename = 'solid';
                }
                $glow = $bcdata['glow'];
                $glitter = $bcdata['glitter'];
                $uv = $bcdata['uv'];
                $powdercoat = $bcdata['powdercoat'];
                break;
            case 10:
                //var_dump('asdasdasd');die();
                $colordefinition = 'Stock DualLayer';
                $colorcategory = 'Dual Layer';

                if (!empty($this->all_colors[$tableId][$colorId])) {
                    $dl_color = $this->all_colors[$tableId][$colorId];
                } else {
                    $dl_color = $this->all_colors[INVALID_COLOR_PRESET_SUBSTITUTE_TABLE][INVALID_COLOR_PRESET_SUBSTITUTE_COLOR];
                    $invalid_color = 1;
                }
                //tpt_dump($this->all_colors[10], true);
                //tpt_dump($tableId.$colorId, true);
                //tpt_dump($this->all_colors[$tableId][$colorId], true);
                //tpt_dump($this->all_colors[$tableId], true);
                //var_dump($this->all_colors[$tableId]);die();
                //var_dump($this->all_colors[$tableId][$colorId]);die();
                //var_dump($colorId);die();
                //var_dump($dl_color);die();
                $colorname = $dl_color['label'];
                $colordata['preset'] = $dl_color;
                $slapdtypes = $data_module->typeStyle[5];
                /*
				if(!empty($slapdtypes)) {
					foreach($slapdtypes as $slapdtype) {
						if(in_array($slapdtype['id'], explode(',', $dl_color[$tfield]))) {
							$slapdual = 1;
						}
					}
				}
				*/
                if (!empty($dl_color['swap_dual_color'])) {
                    $slapdual = 1;
                }
                if (!empty($dl_color['notched'])) {
                    $notched = 1;
                }
                $bcdata = $this->all_colors[0][$dl_color['color_id']];
                $mcdata = $this->all_colors[6][$dl_color['message_color_id']];
                $idcol = 'id';
                $msgidcol = 'color_id';
                $band_tableId = 0;
                $band_colorId = $dl_color['color_id'];
                $message_tableId = 6;
                $message_colorId = $dl_color['message_color_id'];
                if (!empty($slapdual) xor !empty($notched)) {
                    $_tmp = $mcdata;
                    $mcdata = $bcdata;
                    $bcdata = $_tmp;
                    $idcol = 'color_id';
                    $msgidcol = 'id';
                    $swap_dual_color = 0;
                    $band_tableId = 6;
                    $band_colorId = $dl_color['message_color_id'];
                    $message_tableId = 0;
                    $message_colorId = $dl_color['color_id'];
                    //tpt_dump('spcase',true);

                    $colordata['message_glow'] = 0;
                    $colordata['message_glitter'] = 0;
                    $colordata['message_uv'] = 0;
                    $colordata['message_powdercoat'] = 0;
                } else {
                    $colordata['message_glow'] = $mcdata['glow'];
                    $colordata['message_glitter'] = $mcdata['glitter'];
                    $colordata['message_uv'] = $mcdata['uv'];
                    $colordata['message_powdercoat'] = $mcdata['powdercoat'];
                }
                //tpt_dump($notched);
                //tpt_dump($slapdual,true);
                //tpt_dump($tableId);
                //tpt_dump($colorId);
                //tpt_dump($dl_color);
                //tpt_dump($bcdata);
                //tpt_dump($mcdata,true);

                $id = $bcdata[$idcol];
                $idarray = explode(',', $id);
                $colors_count = count($idarray);


                //tpt_dump($id, true);
                //tpt_dump($bcdata, true);
                foreach ($idarray as $idaid) {
                    $colordata['band_colors'][$idaid] = $this->all_colors[0][$idaid];
                    $hexarray[] = $this->all_colors[0][$idaid]['hex'];
                }
                $hex = implode(',', $hexarray);

                //var_dump($this->all_colors[$tid]);//die();
                //var_dump($this->all_colors[$tid][$colorId]);//die();
                //var_dump($color['color_id']);//die();
                //var_dump($tid);//die();
                //var_dump($colorId);//die();
                //var_dump($color);die();


                //$rawcolors = array_intersect_key($this->by_id, array_combine($idarray, array_flip($idarray)));
                //$hexarray = array_reduce($rawcolors, 'getHexField', array());


                $colordata['message_preset'] = $mcdata;
                $colordata['band_tableId'] = $band_tableId;
                $colordata['band_colorId'] = $band_colorId;
                $colordata['band_uid'] = $band_tableId . ':' . $band_colorId;
                $colordata['message_tableId'] = $message_tableId;
                $colordata['message_colorId'] = $message_colorId;
                $colordata['message_uid'] = $message_tableId . ':' . $message_colorId;
                $msg_idarray = explode(',', $mcdata[$msgidcol]);
                $msg_colors_count = count($msg_idarray);
                $band_color_type = 'solid';
                $msg_color_type = 'solid';
                $msg_hexarray = array();
                //tpt_dump($msgidcol);
                //tpt_dump($mcdata,true);
                //tpt_dump($msg_idarray,true);
                foreach ($msg_idarray as $cc) {
                    $colordata['message_colors'][$cc] = $this->all_colors[0][$cc];
                    $msg_hexarray[] = $this->all_colors[0][$cc]['hex'];
                }
                $colordata['message_hexarray'] = $msg_hexarray;
                $colordata['message_hex'] = implode(',', $msg_hexarray);


                $dual_layer = 1;
                if ($dl_color['notched'])
                    $notched = 1;

                if (!empty($slapdual) xor !empty($notched)) {
                    //die();
                    if ($bcdata['color_type'] == 4) {
                        if ($colors_count > 4) {
                            $swirl = 2;
                        } else {
                            $swirl = 1;
                        }
                        $band_color_type = 'swirl';
                        $gClass = 'Swirl';
                    } else if ($bcdata['color_type'] == 5) {
                        if ($colors_count > 4) {
                            $segmented = 2;
                        } else {
                            $segmented = 1;
                        }
                        $band_color_type = 'segmented';
                        $gClass = 'Segmented';
                    }
                    $glow = $bcdata['glow'];
                    $glitter = $bcdata['glitter'];
                    $uv = $bcdata['uv'];
                    $powdercoat = $bcdata['powdercoat'];
                    //tpt_dump($mcdata,true);
                } else {
                    if ($mcdata['color_type'] == 4) {
                        $msg_color_type = 'swirl';
                    } else if ($mcdata['color_type'] == 5) {
                        $msg_color_type = 'segmented';
                    }
                    $glow = 0;
                    $glitter = 0;
                    $uv = 0;
                    $powdercoat = $dl_color['powdercoat'];
                }
                if (!empty($swirl)) {
                    //$colordefinition = 'Stock Swirl';$colorcategory = 'Swirl';
                    $colortypename = 'swirl';
                } else if (!empty($segmented)) {
                    //$colordefinition = 'Stock Segmented';$colorcategory = 'Segmented';
                    $colortypename = 'segmented';
                } else {
                    //$colordefinition = 'Stock Solid';$colorcategory = 'Solid';
                    $colortypename = 'solid';
                }
                $colordata['band_color_type'] = $band_color_type;
                $colordata['message_color_type'] = $msg_color_type;


                break;
            case 11:
                //var_dump('asdasdasd');die();
                $colordefinition = 'Stock LED Band Color';
                $colorcategory = 'LED Band';

                if (!empty($this->all_colors[$tableId][$colorId])) {
                    $led_color = $this->all_colors[$tableId][$colorId];
                } else {
                    $led_color = $this->all_colors[INVALID_COLOR_PRESET_SUBSTITUTE_TABLE][INVALID_COLOR_PRESET_SUBSTITUTE_COLOR];
                    $invalid_color = 1;
                }
                //tpt_dump($this->all_colors[10], true);
                //tpt_dump($tableId.$colorId, true);
                //tpt_dump($this->all_colors[$tableId][$colorId], true);
                //tpt_dump($this->all_colors[$tableId], true);
                //var_dump($this->all_colors[$tableId]);die();
                //var_dump($this->all_colors[$tableId][$colorId]);die();
                //var_dump($colorId);die();
                //var_dump($dl_color);die();
                $colorname = $led_color['label'];
                $colordata['preset'] = $led_color;
                $slapdtypes = $data_module->typeStyle[5];
                /*
				if(!empty($slapdtypes)) {
					foreach($slapdtypes as $slapdtype) {
						if(in_array($slapdtype['id'], explode(',', $dl_color[$tfield]))) {
							$slapdual = 1;
						}
					}
				}
				*/
                $bandprops = $this->getColorProps($vars, $led_color['color_id']);
                //tpt_dump($bandprops['tableId']);
                //tpt_dump($bandprops['colorId']);
                //tpt_dump($this->all_colors[$bandprops['tableId']][$bandprops['colorId']]);
                $bcdata = (!empty($this->all_colors[$bandprops['tableId']][$bandprops['colorId']]) ? $this->all_colors[$bandprops['tableId']][$bandprops['colorId']] : array());
                $msgprops = $this->getColorProps($vars, $led_color['message_color_id']);
                $mcdata = (!empty($this->all_colors[$msgprops['tableId']][$msgprops['colorId']]) ? $this->all_colors[$msgprops['tableId']][$msgprops['colorId']] : array());
                $caseprops = $this->getColorProps($vars, $led_color['case_color_id']);
                $ccdata = (!empty($this->all_colors[$caseprops['tableId']][$caseprops['colorId']]) ? $this->all_colors[$caseprops['tableId']][$caseprops['colorId']] : array());
                $ledprops = $this->getColorProps($vars, $led_color['led_color_id']);
                $lcdata = (!empty($this->all_colors[$ledprops['tableId']][$ledprops['colorId']]) ? $this->all_colors[$ledprops['tableId']][$ledprops['colorId']] : array());
                $idcol = 'id';
                $msgidcol = 'color_id';
                $ledidcol = 'id';

                $colordata['band_glow'] = (!empty($bcdata['glow']) ? $bcdata['glow'] : 0);
                $colordata['band_glitter'] = (!empty($bcdata['glitter']) ? $bcdata['glitter'] : 0);
                $colordata['band_uv'] = (!empty($bcdata['uv']) ? $bcdata['uv'] : 0);
                $colordata['band_powdercoat'] = (!empty($bcdata['powdercoat']) ? $bcdata['powdercoat'] : 0);
                //tpt_dump($notched);
                //tpt_dump($slapdual,true);
                //tpt_dump($tableId);
                //tpt_dump($colorId);
                //tpt_dump($dl_color);
                //tpt_dump($bcdata);
                //tpt_dump($mcdata,true);

                $id = (!empty($ccdata[$idcol]) ? $ccdata[$idcol] : 0);
                $idarray = explode(',', $id);
                $colors_count = count($idarray);


                //tpt_dump($id, true);
                //tpt_dump($bcdata, true);
                foreach ($idarray as $idaid) {
                    $colordata['colors'][$idaid] = (!empty($this->all_colors[0][$idaid]) ? $this->all_colors[0][$idaid] : 0);
                    $hexarray[] = (!empty($this->all_colors[0][$idaid]['hex']) ? $this->all_colors[0][$idaid]['hex'] : 'transparent');
                }
                $hex = implode(',', $hexarray);

                //var_dump($this->all_colors[$tid]);//die();
                //var_dump($this->all_colors[$tid][$colorId]);//die();
                //var_dump($color['color_id']);//die();
                //var_dump($tid);//die();
                //var_dump($colorId);//die();
                //var_dump($color);die();


                //$rawcolors = array_intersect_key($this->by_id, array_combine($idarray, array_flip($idarray)));
                //$hexarray = array_reduce($rawcolors, 'getHexField', array());


                $colordata['band_preset'] = $led_color;
                $colordata['band_tableId'] = (!empty($led_color['tableId']) ? $led_color['tableId'] : 0);
                $colordata['band_colorId'] = (!empty($led_color['colorId']) ? $led_color['colorId'] : 0);
                $colordata['band_uid'] = $colordata['band_tableId'] . ':' . $colordata['band_colorId'];

                $colordata['message_preset'] = $mcdata;
                $colordata['message_uid'] = $led_color['message_color_id'];
                $msguid = explode(':', $colordata['message_uid']);
                $colordata['message_tableId'] = (isset($msguid[0]) ? $msguid[0] : 0);
                $colordata['message_colorId'] = (isset($msguid[1]) ? $msguid[1] : 0);


                $colordata['led_preset'] = $lcdata;
                $colordata['led_uid'] = $led_color['led_color_id'];
                $leduid = explode(':', $colordata['led_uid']);
                $colordata['led_tableId'] = (isset($leduid[0]) ? $leduid[0] : 0);
                $colordata['led_colorId'] = (isset($leduid[1]) ? $leduid[1] : 0);


                $colordata['case_preset'] = $ccdata;
                $colordata['case_uid'] = $led_color['case_color_id'];
                $cuid = explode(':', $colordata['case_uid']);
                $colordata['case_tableId'] = (isset($cuid[0]) ? $cuid[0] : 0);
                $colordata['case_colorId'] = (isset($cuid[1]) ? $cuid[1] : 0);

                $band_color_type = 'solid';

                $msg_idarray = explode(',', (!empty($mcdata[$msgidcol]) ? $mcdata[$msgidcol] : ''));
                $msg_colors_count = count($msg_idarray);
                $msg_color_type = 'solid';
                $msg_hexarray = array();
                //tpt_dump($msgidcol);
                //tpt_dump($mcdata,true);
                //tpt_dump($msg_idarray,true);
                foreach ($msg_idarray as $cc) {
                    $colordata['message_colors'][$cc] = (!empty($this->all_colors[0][$cc]) ? $this->all_colors[0][$cc] : array());
                    $msg_hexarray[] = (!empty($this->all_colors[0][$cc]['hex']) ? $this->all_colors[0][$cc]['hex'] : 'transparent');
                }
                $colordata['message_hexarray'] = $msg_hexarray;
                $colordata['message_hex'] = implode(',', $msg_hexarray);


                //tpt_dump($lcdata);
                $led_idarray = explode(',', $lcdata[$ledidcol]);
                $led_colors_count = count($led_idarray);
                $led_color_type = 'solid';
                $led_hexarray = array();
                //tpt_dump($msgidcol);
                //tpt_dump($mcdata,true);
                //tpt_dump($msg_idarray,true);
                foreach ($led_idarray as $cc) {
                    $colordata['led_colors'][$cc] = $this->all_colors[0][$cc];
                    $led_hexarray[] = $this->all_colors[0][$cc]['hex'];
                }
                $colordata['led_hexarray'] = $led_hexarray;
                $colordata['led_hex'] = implode(',', $led_hexarray);


                $case_idarray = explode(',', (!empty($ccdata['id']) ? $ccdata['id'] : ''));
                $case_colors_count = count($case_idarray);
                $case_color_type = 'solid';
                $case_hexarray = array();
                //tpt_dump($msgidcol);
                //tpt_dump($mcdata,true);
                //tpt_dump($msg_idarray,true);
                foreach ($case_idarray as $cc) {
                    $colordata['case_colors'][$cc] = (!empty($this->all_colors[0][$cc]) ? $this->all_colors[0][$cc] : 0);
                    $case_hexarray[] = (!empty($this->all_colors[0][$cc]['hex']) ? $this->all_colors[0][$cc]['hex'] : 'transparent');
                }
                $colordata['case_hexarray'] = $case_hexarray;
                $colordata['case_hex'] = implode(',', $case_hexarray);


                if (!empty($mcdata['color_type'])) {
                    if ($mcdata['color_type'] == 4) {
                        $msg_color_type = 'swirl';
                    } else if ($mcdata['color_type'] == 5) {
                        $msg_color_type = 'segmented';
                    }
                }
                $led = 1;
                $glow = 0;
                $glitter = 0;
                $uv = 0;
                $powdercoat = 0;
                $transparent_case = (!empty($led_color['transparent_case']) ? $led_color['transparent_case'] : 0);

                if (!empty($swirl)) {
                    //$colordefinition = 'Stock Swirl';$colorcategory = 'Swirl';
                    $colortypename = 'swirl';
                } else if (!empty($segmented)) {
                    //$colordefinition = 'Stock Segmented';$colorcategory = 'Segmented';
                    $colortypename = 'segmented';
                } else {
                    //$colordefinition = 'Stock Solid';$colorcategory = 'Solid';
                    $colortypename = 'solid';
                }
                $colordata['band_color_type'] = $band_color_type;
                $colordata['message_color_type'] = $msg_color_type;


                break;
            default:
                $colordefinition = '';
                $colorcategory = '';
                $invalid_color = 1;
                $transparent_case = 0;

                /*
				$id = $color;
				$idarray = array($id);
				$hex = $this->by_id[$id]['hex'];
				$hexarray = array($hex);

				$colors_count=1;
				*/

                break;
        }

        if ($swirl) {
            $colortypename = 'swirl';
            $gClass = 'Swirl';
        } else if ($segmented) {
            $colortypename = 'segmented';
            $gClass = 'Segmented';
        } else {
            $gClass = 'Solid';
        }

        $colorProps = array(
            'colorname' => $colorname,
            'colordata' => $colordata,
            'colorcategory' => $colorcategory,
            'colordefinition' => $colordefinition,
            'colortypename' => $colortypename,
            'gClass' => $gClass,
            'swirl' => intval($swirl, 10),
            'segmented' => intval($segmented, 10),
            'led' => intval($led, 10),
            'dual_layer' => intval($dual_layer, 10),
            'notched' => intval($notched, 10),
            'glow' => intval($glow, 10),
            'glitter' => intval($glitter, 10),
            'uv' => intval($uv, 10),
            'powdercoat' => intval($powdercoat, 10),
            'transparent_case' => intval($transparent_case, 10),
            'custom_color' => intval($custom_color, 10),
            'overseas' => intval($overseas, 10),
            'overseas_preset' => intval($overseas_preset, 10),
            'invalid_color' => intval($invalid_color, 10),
            'colors_count' => intval($colors_count, 10),
            'hex' => $hex,
            'hexarray' => $hexarray,
            'id' => $id,
            'idarray' => $idarray,
            'swap_dual_color' => $swap_dual_color,
            'colorId' => $colorId,
            'tableId' => $tableId,
            'uid' => $tableId . ':' . $colorId,
        );

        $colorProps['segments'] = $colorProps['segmented'];
        //tpt_dump($colorProps, true);
        switch (strtolower($what)) {
            case 'swap_dual_color':
                return $colorProps['swap_dual_color'];
                break;
            case 'colordefinition':
            case 'definition':
                return $colorProps['colordefinition'];
                break;
            case 'colortypename':
            case 'color_type_name':
            case 'color_type_string':
                return $colorProps['colortypename'];
                break;
            case 'gClass':
                return $colorProps['gClass'];
                break;
            case 'custom_color':
            case 'custom':
                return $colorProps['custom_color'];
                break;
            case 'ccount':
            case 'count':
            case 'colors_count':
                return $colorProps['colors_count'];
                break;
            case 'swirl':
                return $colorProps['swirl'];
                break;
            case 'segments':
            case 'segmented':
                return $colorProps['segmented'];
                break;
            case 'led':
                return $colorProps['led'];
                break;
            case 'dual_layer':
                return $colorProps['dual_layer'];
                break;
            case 'notched':
                return $colorProps['notched'];
                break;
            case 'glow':
                return $colorProps['glow'];
                break;
            case 'glitter':
                return $colorProps['glitter'];
                break;
            case 'uv':
                return $colorProps['uv'];
                break;
            case 'powdercoat':
                return $colorProps['powdercoat'];
                break;
            case 'transparent_case':
                return $colorProps['transparent_case'];
                break;
            case 'hex' :
                return $colorProps['hex'];
                break;
            case 'hexarray' :
                return $colorProps['hexarray'];
                break;
            case 'id' :
                return $colorProps['id'];
                break;
            case 'idarray' :
                return $colorProps['idarray'];
                break;
            case 'tableid' :
                return $colorProps['tableId'];
                break;
            case 'tid' :
            case 'skutableid' :
                return $colorProps['skuTableId'];
                break;
            case 'colcat' :
            case 'colorcategory' :
                return $colorProps['colorcategory'];
                break;
            case 'colname' :
            case 'colorname' :
                return $colorProps['colorname'];
                break;
            case 'coldata' :
            case 'colordata' :
                return $colorProps['colordata'];
                break;
            default:
                return $colorProps;
        }
        //return $colorCount;
    }


    function getDualLayerMessageId(&$vars, $color)
    {
        $cp = $this->getColorProps($vars, $color);
        if (empty($cp['dual_layer'])) {
            if (strtolower($color) == '-1' . DEFAULT_BAND_COLOR) {
                return '-1' . DEFAULT_MESSAGE_COLOR;
            } else {
                return $color;
            }
        }

        return '6:' . $this->all_colors[$cp['tableId']][$cp['colorId']]['message_color_id'];
    }

    function getDualLayerMessageColorProps(&$vars, $color)
    {
        $cp = $this->getColorProps($vars, $color);
        if (empty($cp['dual_layer'])) {
            if (strtolower($color) == '-1' . DEFAULT_BAND_COLOR) {
                return $this->getColorProps($vars, '-1' . DEFAULT_MESSAGE_COLOR);
            } else {
                return $this->getColorProps($vars, $color);
            }
        }

        $mcolorid = '6:' . $this->all_colors[$cp['tableId']][$cp['colorId']]['message_color_id'];

        return $this->getColorProps($vars, $mcolorid);
    }


    /*
	function getCompoundColorCount(&$vars, $color) {
		$product_color = explode(':', $color);
		$tableId = intval($product_color[0], 10);
		$colorId = $product_color[1];
		if($tableId == -1) {
			return 1;
		}

		//$tid = 0;
		//if(!empty($tableId))
		//var_dump($tid);
		//var_dump($colorId);

		$colorCount = false;
		if(!empty($tid)) {
			switch($tid) {
				case -2:
					$colorCount = count(explode(',', $colorId));
					break;
				case 0:
				case 3:
					$colorCount = 1;
					break;
				case 4:
				case 5:
				case 6:
					$color = $this->all_colors[$tid][$colorId];
					$colorCount = count(explode(',', $color['color_id']));
					break;
				case 10:
					$color = $this->all_colors[$tid][$colorId];
					$color = $this->all_colors[5][$color['color_id']];
					$colorCount = count(explode(',', $color['color_id']));
					break;
			}
		} else {
			$colorCount = 1;
		}

		return $colorCount;
	}
	*/

    function getCompoundColorCount(&$vars, $color)
    {
        return $this->getColorProps($vars, $color, 'ccount');
    }


    // 1.0
    function dualLayerMsgColorIds(&$vars, $color)
    {
        //var_dump($color);die();
        $product_color = explode(':', $color);
        $tableId = intval($product_color[0], 10);
        $colorId = $product_color[1];

        //$message_color = $this->all_colors[5][$this->all_colors[10][$colorId]['message_color_id']];

        //var_dump('test');die();
        //var_dump('6:'.$this->all_colors[10][$colorId]['message_color_id']);die();
        return $this->stockToCustom($vars, '6:' . $this->all_colors[10][$colorId]['message_color_id']);
    }

    /*
	function stockToCustomIds(&$vars, $color) {
		//var_dump($color);die();
		$bandColor = explode(':', $color);
		$tableId = $this->colors_sku_table[$bandColor[0]];
		$colorId = $bandColor[1];
		$color = $this->all_colors[$tableId][$colorId];
		//var_dump($this->all_colors[5]);die();
		//var_dump($tableId);die();
		//var_dump($color);die();
		//var_dump($this->by_id);die();
		$ids = $color['color_id'];

		return $ids;
	}
	*/

    function dualLayerUserData(&$vars)
    {
        $rArr = $vars['db']['handler']->getData($vars, 'tpt_color_duallayer', '`id`, `swap_dual_color`, `notched`', ' `enabled`=1', 'id', false);
        return $rArr;
    }

    function defaultUserData(&$vars)
    {
        $rArr = array();
        $rArr[3] = $vars['db']['handler']->getData($vars, 'tpt_color_overseas', '`id`, `glow`, `glitter`', ' `enabled`=1 AND `color_type`=3', 'id', false);
        $rArr[4] = $vars['db']['handler']->getData($vars, 'tpt_color_overseas', '`id`, `glow`, `glitter`', ' `enabled`=1 AND `color_type`=4', 'id', false);
        $rArr[5] = $vars['db']['handler']->getData($vars, 'tpt_color_overseas', '`id`, `glow`, `glitter`', ' `enabled`=1 AND `color_type`=5', 'id', false);
        $rArr[6] = $vars['db']['handler']->getData($vars, 'tpt_color_special', '`id`, `glow`, `glitter`', ' `enabled`=1', 'id', false);
        return $rArr;
    }

    function stockToCustomIds(&$vars, $color)
    {
        return $this->getColorProps($vars, $color, 'id');
    }

    function stockToCustom(&$vars, $color)
    {
        //var_dump($color);die();
        $colorProps = $this->getColorProps($vars, $color);

        $cprm = array();
        if ($colorProps['glow'])
            $cprm[] = 1;
        if ($colorProps['glitter'])
            $cprm[] = 2;
        if ($colorProps['uv'])
            $cprm[] = 3;

        if (!empty($cprm))
            $cprm = ':' . implode(',', $cprm);
        else
            $cprm = '';

        if ($colorProps['swirl']) {
            return '1:' . $this->stockToCustomIds($vars, $color) . $cprm;
        } else if ($colorProps['segmented']) {
            return '2:' . $this->stockToCustomIds($vars, $color) . $cprm;
        } else {
            return '0:' . $this->stockToCustomIds($vars, $color) . $cprm;
        }
    }

    function stockToCustomArray(&$vars, $catid = 0)
    {
        $rArr = array();
        switch (intval($catid, 0)) {
            case 3:
            case 4:
            case 5:
            case 6:
                $_temp = array();
                $rArr = $this->all_colors[$catid];
                foreach ($rArr as $color) {
                    $_temp[$color['id']] = $this->stockToCustom($vars, $catid . ':' . $color['id']);
                }
                $rArr = $_temp;
                break;
            default :
                $_temp = array();
                $_temp[3] = array();
                $_temp[4] = array();
                $_temp[5] = array();
                $_temp[6] = array();
                $rArr[3] = $this->all_colors[3];
                foreach ($rArr[3] as $color) {
                    $_temp[3][$color['id']] = $this->stockToCustom($vars, '3:' . $color['id']);
                }
                $rArr[4] = $this->all_colors[4];
                foreach ($rArr[4] as $color) {
                    $_temp[4][$color['id']] = $this->stockToCustom($vars, '4:' . $color['id']);
                }
                $rArr[5] = $this->all_colors[5];
                foreach ($rArr[5] as $color) {
                    $_temp[5][$color['id']] = $this->stockToCustom($vars, '5:' . $color['id']);
                }
                $rArr[6] = $this->all_colors[6];
                foreach ($rArr[6] as $color) {
                    $_temp[6][$color['id']] = $this->stockToCustom($vars, '6:' . $color['id']);
                }

                $rArr = $_temp;
                break;
        }
        //tpt_dump($rArr, true);


        return $rArr;
    }

    function solidColorsHEX(&$vars)
    {
        $rArr = array();

        $_temp = array();
        $rArr[3] = $this->all_colors[3];
        foreach ($rArr[3] as $color) {
            $_temp[$color['id']] = $this->all_colors[0][$this->all_colors[3][$color['id']]['color_id']]['hex'];
        }
        //var_dump($rArr);die();
        $rArr[3] = $_temp;


        $_temp = array();
        $rArr[6] = $vars['db']['handler']->getData($vars, 'tpt_color_special', '*', '`enabled`=1 AND `color_type`=3 AND `glitter`=0 AND `powdercoat`=0 ORDER BY  `label` ASC ', 'id', false);
        foreach ($rArr[6] as $color) {
            $_temp[$color['id']] = $this->all_colors[0][$this->all_colors[6][$color['id']]['color_id']]['hex'];
        }
        $rArr[6] = $_temp;


        $_temp = array();
        $query = 'SELECT dl.`id`, dl.`label`, dl.`message_color_id`, dl.`available_types_ids`, sp.`id` AS msgid, sp.`label` AS msglabel, sp.`color_type`, sp.`glow`, sp.`glitter`, sp.`uv` FROM `tpt_color_duallayer` AS dl LEFT JOIN `tpt_color_special` AS sp ON dl.`message_color_id`=sp.`id` WHERE dl.`enabled`=1 AND `dl`.`powdercoat`!=1 ORDER BY `label` ASC';
        $vars['db']['handler']->query($query, __FILE__);
        $rArr[10] = $vars['db']['handler']->fetch_assoc_list('id', false);
        foreach ($rArr[10] as $color) {
            $_temp[$color['id']] = $this->all_colors[0][$this->all_colors[10][$color['id']]['color_id']]['hex'];
        }
        $rArr[10] = $_temp;


        return $rArr;
    }

    function userEndData(&$vars)
    {
        $rArr = $this->stockToCustomArray($vars);
        $rArr1 = $this->solidColorsHEX($vars);
        $rArr2 = $this->defaultUserData($vars);
        $rArr3 = $this->dualLayerUserData($vars);


        return array(
            'stock_to_custom' => $rArr,
            'solids_hex' => $rArr1,
            'default' => $rArr2,
            'dual_layers' => $rArr3,
        );
    }


    function getSkuComponent(&$vars, $bandColor, $messagecolor = false)
    {
        //var_dump($bandColor);
        //tpt_dump($bandColor);
        $skuPref = '';
        $skuSuf = '';
        $color = '';
        $cprops = $this->getColorProps($vars, $bandColor);
        //tpt_dump($cprops,true);
        if (!empty($cprops['notched']) && empty($messagecolor)) {
            $bandColor = $cprops['colordata']['message_uid'];
            //tpt_dump($cprops['message_uid'], true);
            $cprops = $this->getColorProps($vars, $bandColor);
        }
        //$color = array();
        //if(empty($bandColor[2]))
        //    $bandColor[2] = '';
        //$cEffects = explode(',', $bandColor[2]);

        //var_dump($bandColor);die();
        //var_dump($bandColor);die();
        if ($cprops['swirl']) {
            $skuPref = 'W';
        } else if ($cprops['segmented']) {
            $skuPref = 'G';
        } else {
            $skuPref = 'S';
        }

        //tpt_dump($cprops, true);
        $skuSuf = '';
        if ($cprops['glow']) {
            $skuSuf .= '+GLOW';
        }
        if ($cprops['glitter']) {
            $skuSuf .= '+GLIT';
        }
        if ($cprops['uv']) {
            $skuSuf .= '+UV';
        }

        //var_dump($this->by_id);die();
        //$cols = array();
        //$colid = explode(',', $color['color_id']);
        //$fcolid = array_filter($colid);
        //var_dump($colid);//die();
        //var_dump($fcolid);die();
        //tpt_dump($cprops);//die();
        //tpt_dump($cprops, true);//die();
        $cols = array();
        $colid = $cprops['idarray'];
        if (!empty($colid)) {
            foreach ($colid as $cid) {
                if (!empty($this->by_id[$cid])) {
                    $cols[] = strtoupper(preg_replace('/[\s-]+/', '_', str_ireplace('process', '', $this->by_id[$cid]['pms_c'])));
                } else if ($cid == 0) {
                    $skuPref = 'T';
                    $cols[] = 'TRNSPRNT';
                } else {
                    $cols[] = '#' . $cid;
                }
            }
        } else {
            $cols[] = 'CLEAR';
        }
        //var_dump($colid);
        $skuComp = $skuPref . '-' . implode('.', $cols) . $skuSuf;

        return $skuComp;
    }


    function getCustomColorIdNoAddons(&$vars, $bandColor)
    {
        $skuPref = '';
        $color = '';
        $cprops = $this->getColorProps($vars, $bandColor);

        //var_dump($this->by_id);die();
        $cols = array();
        $colid = $cprops['idarray'];
        if (!empty($colid)) {
            foreach ($colid as $cid) {
                $cols[] = str_ireplace('process', '', $this->by_id[$cid]['name']);
            }
        } else {
            $cols[] = "Clear";
        }
        $skuComp = '<br />' . implode('<br />', $cols);

        return $skuComp;
    }

    function getCustomColorId(&$vars, $bandColor)
    {
        $skuSuf = '';
        $cprops = $this->getColorProps($vars, $bandColor);

        if ($cprops['glow']) {
            $skuSuf .= '<br />+ Glow';
        }
        if ($cprops['glitter']) {
            $skuSuf .= '<br />+ Glitter';
        }
        if ($cprops['uv']) {
            $skuSuf .= '<br />+ UV Effect';
        }


        $skuComp = $this->getCustomColorIdNoAddons($vars, $bandColor) . $skuSuf;

        return $skuComp;
    }

    function getCustomColorAddonId(&$vars, $bandColor)
    {
        $skuSuf = '';
        $cprops = $this->getColorProps($vars, $bandColor);

        if ($cprops['glow']) {
            $skuSuf .= '<br />+ Glow';
        }
        if ($cprops['glitter']) {
            $skuSuf .= '<br />+ Glitter';
        }
        if ($cprops['uv']) {
            $skuSuf .= '<br />+ UV Effect';
        }


        $skuComp = $skuSuf;

        return $skuComp;
    }

    /*
	function getBandBGStyle(&$vars, $color, $x, $y) {
		$previewtime = time();
		$bColor = explode(':', $color);
		$colorId = $bColor[1];
		//var_dump($bColor);die();
		$UEcolor = urlencode($color);
		$bandbg = '';
		if(($bColor[0] == 0)) {
			$bandbg = BASE_URL.'/generate-preview?pg_x='.$x.'&amp;pg_y='.$y.'&amp;color='.$UEcolor.'&amp;timestamp='.$previewtime.'&amp;type=solid';
			$bandbg = 'background-image: url('.$bandbg.')';
			//$bandbg = 'background-color: #'.$this->all_colors[0][$colorId]['hex'].';';
		} else if(($bColor[0] == 1) || ($bColor[0] == 4)) {
			$bandbg = BASE_URL.'/generate-preview?pg_x='.$x.'&amp;pg_y='.$y.'&amp;color='.$UEcolor.'&amp;timestamp='.$previewtime.'&amp;type=swirl';
			$bandbg = 'background-image: url('.$bandbg.')';
		} else if(($bColor[0] == 2) || ($bColor[0] == 5)) {
			$bandbg = $tpt_baseurl.'/generate-preview?pg_x='.$x.'&amp;pg_y='.$y.'&amp;color='.$UEcolor.'&amp;timestamp='.$previewtime.'&amp;type=segmented';
			$bandbg = 'background-image: url('.$bandbg.')';
		} else if(($bColor[0] == 10)) {
			$bandbg = $tpt_baseurl.'/generate-preview?pg_x='.$x.'&amp;pg_y='.$y.'&amp;color='.$UEcolor.'&amp;timestamp='.$previewtime.'&amp;type=duallayer';
			$bandbg = 'background-image: url('.$bandbg.')';
		} else {
			//die('asdasf');
			//var_dump($bColor);die();
			if($bColor[0] == -1) {
				$bandbg = 'background-color: #'.$colorId.';';
			} else {
				if($bColor[0] == 0) {
					$bandbg = 'background-color: #'.$this->all_colors[0][$colorId]['hex'].';';
				} else {
					//die('asdas');
					//var_dump($bColor[0]);
					//var_dump($bColor[1]);
					//var_dump($this->all_colors[$bColor[0]]);
					//die('asdas');
					//die($this->all_colors[$bColor[0]][$bColor[1]]);
					$colorId = $this->all_colors[$bColor[0]][$bColor[1]]['color_id'];
					$bandbg = 'background-color: #'.$this->all_colors[0][$colorId]['hex'].';';
				}
			}
		}

		return $bandbg;
	}
	*/

    function getBandXLayerBGStyle(&$vars, $pgType, $pgStyle, $pgBandColor, $pgMessageColor, $forcecss = false)
    {
        $data_module = getModule($vars, "BandData");

        $UEpgBandColor = urlencode($pgBandColor);
        $UEpgMessageColor = urlencode($pgMessageColor);

        $previewtime = time();
        $bandbg = BASE_URL . '/generate-preview?bandType=' . $pgType . '&amp;bandStyle=' . $pgStyle . '&amp;color=' . $UEpgBandColor . '&amp;textColor=' . $UEpgMessageColor . '&amp;timestamp=' . $previewtime . '&amp;type=dualextralayer';

        if ($data_module->typeStyle[$pgType][$pgStyle]['preview_xlayer_type'] == 4) {
            $bandbg = 'background: transparent url(' . $bandbg . ') no-repeat scroll center center;';
        } else {
            $bandbg = 'background: transparent url(' . $bandbg . ') repeat-x scroll center center;';
        }


        return $bandbg;
    }

    function getBandBGStyle(&$vars, $color, $message_color, $x, $y, $forcecss = false)
    {
        $previewtime = time();
        $cprops = $this->getColorProps($vars, $color);

        $UEcolor = urlencode($color);
        $UEmessageColor = urlencode($message_color);
        $bandbg = '';
        //var_dump($cprops);die();
        if (($cprops['swirl'] || $cprops['segmented'] || $cprops['glitter'] || !$forcecss) && !$cprops['dual_layer'] && ($cprops['tableId'] != -1)) {
            $bandbg = BASE_URL . '/generate-preview?pg_x=' . $x . '&amp;pg_y=' . $y . '&amp;color=' . $UEcolor . '&amp;messageColor=' . $UEmessageColor . '&amp;timestamp=' . $previewtime . '&amp;type=' . $cprops['gClass'];
            $bandbg = 'background-image: url(' . $bandbg . ')';
            //$bandbg = 'background-color: #'.$this->all_colors[0][$colorId]['hex'].';';
        } else if ($cprops['dual_layer']) {
            $dualcolor = '';
            if ($cprops['swap_dual_color']) {
                $dualcolor = '&amp;dualcolor=1';
            }
            $bandbg = BASE_URL . '/generate-preview?pg_x=' . $x . '&amp;pg_y=' . $y . '&amp;color=' . $UEcolor . '&amp;messageColor=' . $UEmessageColor . '&amp;timestamp=' . $previewtime . '&amp;type=duallayer' . $dualcolor;
            $bandbg = 'background-image: url(' . $bandbg . ')';
            if ($forcecss && !$cprops['notched'] && !$cprops['swap_dual_color']) {
                $bandbg = 'background-color: #' . $cprops['hex'] . ';';
            }
        } else {
            //die('asdasf');
            //var_dump($bColor);die();
            $bandbg = 'background-color: #' . $cprops['hex'] . ';';
        }

        return $bandbg;
    }

    /*
	function getBandColorPreviewParams(&$vars, $color, $x, $y) {
		$previewtime = time();
		$bColor = explode(':', $color);
		$colorId = $bColor[1];
		//var_dump($bColor);die();
		$params = array();
		if(($bColor[0] == 0)) {
			$params = array('pg_x'=>$x,
							'pg_y'=>$y,
							'color'=>$color,
							'type'=>'solid'
						   );
		} else if(($bColor[0] == 1) || ($bColor[0] == 4)) {
			$params = array('pg_x'=>$x,
							'pg_y'=>$y,
							'color'=>$color,
							'type'=>'swirl'
						   );
		} else if(($bColor[0] == 2) || ($bColor[0] == 5)) {
			$params = array('pg_x'=>$x,
							'pg_y'=>$y,
							'color'=>$color,
							'type'=>'segmented'
						   );
		} else if(($bColor[0] == 10)) {
			$params = array('pg_x'=>$x,
							'pg_y'=>$y,
							'color'=>$color,
							'type'=>'duallayer'
						   );
		} else {
			$params = array('pg_x'=>$x,
				'pg_y'=>$y,
				'color'=>$color,
				'type'=>'solid'
			   );
		}

		return $params;
	}
	*/

    function getBandColorPreviewParams(&$vars, $color, $x, $y, $ignoreswapdualcolor = false)
    {
        $previewtime = time();
        $cprops = $this->getColorProps($vars, $color);
        //tpt_dump($cprops, true);

        $params = array();
        if (!$cprops['swirl'] && !$cprops['segmented'] && !$cprops['dual_layer'] && ($cprops['tableId'] != -1)) {
            $params = array('pg_x' => $x,
                'pg_y' => $y,
                'color' => $color,
                'type' => 'solid'
            );
        } else if ($cprops['swirl']) {
            $params = array('pg_x' => $x,
                'pg_y' => $y,
                'color' => $color,
                'type' => 'swirl'
            );
        } else if ($cprops['segmented']) {
            $params = array('pg_x' => $x,
                'pg_y' => $y,
                'color' => $color,
                'type' => 'segmented'
            );
        } else if ($cprops['dual_layer']) {
            /*
			if($cprops['notched'])
				$color = '0:'.$this->all_colors[$cprops['tid']];
			*/
            $dualcolor = 0;
            if ((!$ignoreswapdualcolor && $cprops['swap_dual_color'])) {
                $dualcolor = 1;
            }
            $params = array('pg_x' => $x,
                'pg_y' => $y,
                'color' => $color,
                'dualcolor' => $dualcolor,
                'type' => 'duallayer'
            );
        } else {
            $params = array('pg_x' => $x,
                'pg_y' => $y,
                'color' => $color,
                'type' => 'solid'
            );
        }

        return $params;
    }

    function BandColor_Panel_DC(&$vars, $pgType)
    {
        $html = '';
        //var_dump($stvals);die();

        $tpt_imagesurl = TPT_IMAGES_URL;
        $tpt_baseurl = BASE_URL;
        $pg_x = 80;
        $pg_y = 50;
        $previewtime = time();
        /*
		$bandbg = '';
		if(isset($product->data['band_color'])) {
			$bColor = explode(':', $product->data['band_color']);
			$colorId = $bColor[1];
			if(($tableId == 1) || ($tableId == 4)) {
				$bandbg = $tpt_baseurl.'/generate-preview?pg_x='.$pg_x.'&pg_y='.$pg_y.'6&color='.$product->data['band_color'].'&timestamp='.$previewtime.'&type=swirl';
				$bandbg = 'background-image: url('.$bandbg.')';
			} else if(($tableId == 2) || ($tableId == 5)) {
				$bandbg = $tpt_baseurl.'/generate-preview?pg_x='.$pg_x.'&pg_y='.$pg_y.'6&color='.$product->data['band_color'].'&timestamp='.$previewtime.'&type=segmented';
				$bandbg = 'background-image: url('.$bandbg.')';
			} else {
				if($tableId == 0) {
					$bandbg = 'background-color: #'.getModule($tpt_vars, "BandColor")->all_colors[0][$colorId]['hex'];
				} else {
					$colorId = getModule($tpt_vars, "BandColor")->all_colors[$tableId][$colorId]['color_id'];
					$bandbg = 'background-color: #'.getModule($tpt_vars, "BandColor")->all_colors[0][$colorId]['hex'];
				}
			}
		}
		*/

        $vars['template_data']['head'][] = <<< EOT
		<style type="text/css">
			#design_center_content_step_3>div {
				display: none;
			}
			#design_center_content_step_3.zStyle_Dual_ID>.zStyle_Dual_ID {
				display: block;
			}
			#design_center_content_step_3.zStyle_Regular_ID>.zStyle_Regular_ID {
				display: block;
			}
		</style>
EOT;

        $html .= '<div class="clearFix zStyle_Regular_ID">';
        $html .= '<div style="background-image: url(' . $tpt_imagesurl . '/design_center/txt_solid.png);" class="height-34 background-position-CC background-repeat-no-repeat padding-top-20 padding-bottom-20"></div>';
        $html .= '<div class="clearFix">';
        $items = $this->solid;
        $i = 0;
        array_unshift($items, array());
        foreach ($items as $item) {
            $link = '';

            /*
			if($i % 4 == 0) {
				$link .= '<div class="clearFix">';
			}
			*/


            if (!empty($item)) {
                $id = $item['id'];
                $name = htmlentities($item['label']);
                $colorId = $this->all_colors[3][$item['id']]['color_id'];
                $hex = $this->all_colors[0][$colorId]['hex'];
                $ttbg = 'background-color: #' . $hex;
                $bandbg = $tpt_baseurl . '/generate-preview?&color=3:' . $id . '&timestamp=' . $previewtime . '&type=coloroption';
                $opts = array();
                $opts['fullsizeX'] = 47;
                $opts['fullsizeY'] = 47;
                $colorcat = 3;
                $colorid = $id;
                $cfile = TPT_IMAGES_DIR . DIRECTORY_SEPARATOR . 'preview' . DIRECTORY_SEPARATOR . 'cached' . DIRECTORY_SEPARATOR . 'coloroption' . DIRECTORY_SEPARATOR . 'coloroption-' . $opts['fullsizeX'] . 'x' . $opts['fullsizeY'] . '-' . $colorcat . '_' . $colorid . '.png';
                if (is_file($cfile)) {
                    $bandbg = $tpt_imagesurl . '/preview/cached/coloroption/coloroption-' . $opts['fullsizeX'] . 'x' . $opts['fullsizeY'] . '-' . $colorcat . '_' . $colorid . '.png';
                }
                $bandbg = 'background-image: url(' . $bandbg . ')';
                $colorsrc = $bandbg;
                $link .= <<< EOT
				<a rel="tooltip[tooltip_color_3_$id]" title="$name" style="background-image: url($tpt_imagesurl/design_center/color-option-bg.png);" class="colorPanelOption width-84 height-97 hoverCB background-position-CT background-repeat-no-repeat text-decoration-none float-left font-size-16 display-block text-align-center" href="#" onclick="change_product_band_color(this); return false;">
					<span class="padding-left-2 padding-right-2 padding-top-0 padding-bottom-0 display-block">
						<span class="display-block position-relative height-50">
							<span style="z-index: 1; $bandbg" class="background-position-CC background-repeat-no-repeat display-block position-absolute top-0 right-0 bottom-0 left-0">
							</span>
						</span>
					</span>
					<span class="amz_brown display-block height-20 line-height-10 font-size-10" style="font-family:Arial, Helvetica, sans-serif;">$name</span>
					<span class="display-block height-21 background-position-CC background-repeat-no-repeat" style="background-image: url($tpt_imagesurl/design_center/select-btn.png);"></span>
					<input type="hidden" value="3:$id">
					<input type="hidden" id="hex_id_3_$id" value="$hex">
				</a>
EOT;

                $vars['template']['tooltips'] .= <<< EOT
<div id="tooltip_color_3_$id" class="tooltip-wrapper hidden">
	<div class="tooltip-content">
		<div class="width-300 height-60 background-repeat-repeat" style="$ttbg">
		</div>
	</div>
</div>
EOT;
            } else {
                $link .= <<< EOT
				<a title="Create Custom Solid Color..." style="background-image: url($tpt_imagesurl/design_center/color-option-bg.png);" class="colorPanelOption width-84 height-97 hoverCB background-position-CT background-repeat-no-repeat text-decoration-none float-left font-size-16 display-block text-align-center" href="#" onclick="return false;">
					<span class="padding-left-2 padding-right-2 padding-top-0 padding-bottom-0 display-block">
						<span class="display-block position-relative height-70">
							<span style="z-index: 1; background-image: url($tpt_imagesurl/design_center/custom-solid-btn.png);" class="background-position-CC background-repeat-no-repeat display-block position-absolute top-0 right-0 bottom-0 left-0">
							</span>
						</span>
					</span>
					<span class="display-block height-21 background-position-CC background-repeat-no-repeat" style="background-image: url($tpt_imagesurl/design_center/select-btn.png);"></span>
				</a>
EOT;
            }

            /*
			if(($i % 4 == 3) || ($i == count($items) -1)) {
				$link .= '</div>';
			}
			*/
            $html .= $link;

            $i++;
        }
        $html .= '</div>';
        $html .= '</div>';


        $html .= '<div class="clearFix zStyle_Regular_ID">';
        $html .= '<div style="background-image: url(' . $tpt_imagesurl . '/design_center/txt_swirls.png);" class="height-34 background-position-CC background-repeat-no-repeat padding-top-20 padding-bottom-20"></div>';
        $html .= '<div class="clearFix">';
        $items = $this->swirl;
        array_unshift($items, array());
        $i = 0;
        foreach ($items as $item) {
            $link = '';

            /*
			if($i % 4 == 0) {
				$link .= '<div class="clearFix">';
			}
			*/


            if (!empty($item)) {
                $id = $item['id'];
                $name = htmlentities($item['label']);
                $ttbg = $tpt_baseurl . '/generate-preview?pg_x=100&pg_y=60&color=4:' . $id . '&timestamp=' . $previewtime . '&type=swirl';


                $c = '4:' . $id;
                $color_module = getModule($vars, "BandColor");
                $colorProps = $color_module->getColorProps($vars, $c);
                $options['glitter'] = $colorProps['glitter'];
                //$options['gClass'] = 'Swirl';

                $colorcat = 4;
                $colorid = $id;

                $cols = array();
                $color = false;
                if (($colorcat == 0) || ($colorcat == 1) || ($colorcat == 2)) {
                    $color = array('color_id' => $colorid);
                } else {
                    $color = getModule($vars, "BandColor")->all_colors[$colorcat][$colorid];
                }
                $colid = explode(',', $color['color_id']);
                foreach ($colid as $cid) {
                    $cols[] = getModule($vars, "BandColor")->by_id[$cid]['hex'];
                }

                $options = array();
                $options['fullsizeX'] = 100;
                $options['fullsizeY'] = 60;

                $glittersuf = '';
                if (!empty($options['glitter']))
                    $glittersuf = '--glitter';
                $colidfr = implode('_', $cols);
                $cfile = TPT_IMAGES_DIR . DIRECTORY_SEPARATOR . 'preview' . DIRECTORY_SEPARATOR . 'cached' . DIRECTORY_SEPARATOR . 'swirl' . DIRECTORY_SEPARATOR . 'swirl-' . $options['fullsizeX'] . 'x' . $options['fullsizeY'] . '-' . $colidfr . $glittersuf . '.png';
                if (is_file($cfile)) {
                    $ttbg = $tpt_imagesurl . '/preview/cached/swirl/swirl-' . $options['fullsizeX'] . 'x' . $options['fullsizeY'] . '-' . $colidfr . $glittersuf . '.png';
                }


                $ttbg = 'background-image: url(' . $ttbg . ')';
                $bandbg = $tpt_baseurl . '/generate-preview?&color=4:' . $id . '&timestamp=' . $previewtime . '&type=coloroption';
                $opts = array();
                $opts['fullsizeX'] = 47;
                $opts['fullsizeY'] = 47;
                $colorcat = 4;
                $colorid = $id;
                $cfile = TPT_IMAGES_DIR . DIRECTORY_SEPARATOR . 'preview' . DIRECTORY_SEPARATOR . 'cached' . DIRECTORY_SEPARATOR . 'coloroption' . DIRECTORY_SEPARATOR . 'coloroption-' . $opts['fullsizeX'] . 'x' . $opts['fullsizeY'] . '-' . $colorcat . '_' . $colorid . '.png';
                if (is_file($cfile)) {
                    $bandbg = $tpt_imagesurl . '/preview/cached/coloroption/coloroption-' . $opts['fullsizeX'] . 'x' . $opts['fullsizeY'] . '-' . $colorcat . '_' . $colorid . '.png';
                }
                $bandbg = 'background-image: url(' . $bandbg . ')';
                $colorsrc = $bandbg;
                $link .= <<< EOT
				<a rel="tooltip[tooltip_color_4_$id]" title="$name" style="background-image: url($tpt_imagesurl/design_center/color-option-bg.png);" class="colorPanelOption width-84 height-97 hoverCB background-position-CT background-repeat-no-repeat text-decoration-none float-left font-size-16 display-block text-align-center" href="#" onclick="change_product_band_color(this); return false;">
					<span class="padding-left-2 padding-right-2 padding-top-0 padding-bottom-0 display-block">
						<span class="display-block position-relative height-50">
							<span style="z-index: 1; $bandbg" class="background-position-CC background-repeat-no-repeat display-block position-absolute top-0 right-0 bottom-0 left-0">
							</span>
						</span>
					</span>
					<span class="amz_brown display-block height-20 line-height-10 font-size-10" style="font-family:Arial, Helvetica, sans-serif;">$name</span>
					<span class="display-block height-21 background-position-CC background-repeat-no-repeat" style="background-image: url($tpt_imagesurl/design_center/select-btn.png);"></span>
					<input type="hidden" value="4:$id">
				</a>
EOT;

                $vars['template']['tooltips'] .= <<< EOT
<div id="tooltip_color_4_$id" class="tooltip-wrapper hidden">
	<div class="tooltip-content">
		<div class="width-300 height-60 background-repeat-repeat" style="$ttbg">
		</div>
	</div>
</div>
EOT;
            } else {
                $link .= <<< EOT
				<a title="Create Custom Swirl Color..." style="background-image: url($tpt_imagesurl/design_center/color-option-bg.png);" class="colorPanelOption width-84 height-97 hoverCB background-position-CT background-repeat-no-repeat text-decoration-none float-left font-size-16 display-block text-align-center" href="#" onclick="return false;">
					<span class="padding-left-2 padding-right-2 padding-top-0 padding-bottom-0 display-block">
						<span class="display-block position-relative height-70">
							<span style="z-index: 1; background-image: url($tpt_imagesurl/design_center/custom-swirl-btn.png);" class="background-position-CC background-repeat-no-repeat display-block position-absolute top-0 right-0 bottom-0 left-0">
							</span>
						</span>
					</span>
					<span class="display-block height-21 background-position-CC background-repeat-no-repeat" style="background-image: url($tpt_imagesurl/design_center/select-btn.png);"></span>
				</a>
EOT;
            }

            /*
			if(($i % 4 == 3) || ($i == count($items) -1)) {
				$link .= '</div>';
			}
			*/
            $html .= $link;

            $i++;
        }
        $html .= '</div>';
        $html .= '</div>';


        $html .= '<div class="clearFix zStyle_Regular_ID">';
        $html .= '<div style="background-image: url(' . $tpt_imagesurl . '/design_center/txt_segmented.png);" class="height-34 background-position-CC background-repeat-no-repeat padding-top-20 padding-bottom-20"></div>';
        $html .= '<div class="clearFix">';
        $items = $this->segment;
        array_unshift($items, array());
        $i = 0;
        foreach ($items as $item) {
            $link = '';

            /*
			if($i % 4 == 0) {
				$link .= '<div class="clearFix">';
			}
			*/


            if (!empty($item)) {
                $id = $item['id'];
                $name = htmlentities($item['label']);
                $ttbg = $tpt_baseurl . '/generate-preview?pg_x=100&pg_y=60&color=5:' . $id . '&timestamp=' . $previewtime . '&type=segmented';


                $c = '5:' . $id;
                $color_module = getModule($vars, "BandColor");
                $colorProps = $color_module->getColorProps($vars, $c);
                $options['glitter'] = $colorProps['glitter'];
                //$options['gClass'] = 'Segmented';

                $colorcat = 5;
                $colorid = $id;

                $cols = array();
                if (($colorcat == 0) || ($colorcat == 1) || ($colorcat == 2)) {
                    $color = array('color_id' => $colorid);
                } else {
                    $color = getModule($vars, "BandColor")->all_colors[$colorcat][$colorid];
                }
                $colid = explode(',', $color['color_id']);
                foreach ($colid as $cid) {
                    $cols[] = getModule($vars, "BandColor")->by_id[$cid]['hex'];
                }

                //$options['segmentColor'] = array_reverse($segmentColor);


                $options['fullsizeX'] = 100;
                $options['fullsizeY'] = 60;

                $glittersuf = '';
                if (!empty($options['glitter']))
                    $glittersuf = '--glitter';
                $colidfr = implode('_', $cols);
                $cfile = TPT_IMAGES_DIR . DIRECTORY_SEPARATOR . 'preview' . DIRECTORY_SEPARATOR . 'cached' . DIRECTORY_SEPARATOR . 'segmented' . DIRECTORY_SEPARATOR . 'segmented-' . $options['fullsizeX'] . 'x' . $options['fullsizeY'] . '-' . $colidfr . $glittersuf . '.png';
                if (is_file($cfile)) {
                    $ttbg = $tpt_imagesurl . '/preview/cached/segmented/segmented-' . $options['fullsizeX'] . 'x' . $options['fullsizeY'] . '-' . $colidfr . $glittersuf . '.png';
                }


                $ttbg = 'background-image: url(' . $ttbg . ')';
                $bandbg = $tpt_baseurl . '/generate-preview?&color=5:' . $id . '&timestamp=' . $previewtime . '&type=coloroption';
                $opts = array();
                $opts['fullsizeX'] = 47;
                $opts['fullsizeY'] = 47;
                $colorcat = 5;
                $colorid = $id;
                $cfile = TPT_IMAGES_DIR . DIRECTORY_SEPARATOR . 'preview' . DIRECTORY_SEPARATOR . 'cached' . DIRECTORY_SEPARATOR . 'coloroption' . DIRECTORY_SEPARATOR . 'coloroption-' . $opts['fullsizeX'] . 'x' . $opts['fullsizeY'] . '-' . $colorcat . '_' . $colorid . '.png';
                if (is_file($cfile)) {
                    $bandbg = $tpt_imagesurl . '/preview/cached/coloroption/coloroption-' . $opts['fullsizeX'] . 'x' . $opts['fullsizeY'] . '-' . $colorcat . '_' . $colorid . '.png';
                }
                $bandbg = 'background-image: url(' . $bandbg . ')';
                $colorsrc = $bandbg;
                $link .= <<< EOT
				<a rel="tooltip[tooltip_color_5_$id]" title="$name" style="background-image: url($tpt_imagesurl/design_center/color-option-bg.png);" class="colorPanelOption width-84 height-97 hoverCB background-position-CT background-repeat-no-repeat text-decoration-none float-left font-size-16 display-block text-align-center" href="#" onclick="change_product_band_color(this); return false;">
					<span class="padding-left-2 padding-right-2 padding-top-0 padding-bottom-0 display-block">
						<span class="display-block position-relative height-50">
							<span style="z-index: 1; $bandbg" class="background-position-CC background-repeat-no-repeat display-block position-absolute top-0 right-0 bottom-0 left-0">
							</span>
						</span>
					</span>
					<span class="amz_brown display-block height-20 line-height-10 font-size-10" style="font-family:Arial, Helvetica, sans-serif;">$name</span>
					<span class="display-block height-21 background-position-CC background-repeat-no-repeat" style="background-image: url($tpt_imagesurl/design_center/select-btn.png);"></span>
					<input type="hidden" value="5:$id">
				</a>
EOT;

                $vars['template']['tooltips'] .= <<< EOT
<div id="tooltip_color_5_$id" class="tooltip-wrapper hidden">
	<div class="tooltip-content">
		<div class="width-300 height-60 background-repeat-repeat" style="$ttbg">
		</div>
	</div>
</div>
EOT;
            } else {
                $link .= <<< EOT
				<a title="Create Custom Segmented Color..." style="background-image: url($tpt_imagesurl/design_center/color-option-bg.png);" class="colorPanelOption width-84 height-97 hoverCB background-position-CT background-repeat-no-repeat text-decoration-none float-left font-size-16 display-block text-align-center" href="#" onclick="return false;">
					<span class="padding-left-2 padding-right-2 padding-top-0 padding-bottom-0 display-block">
						<span class="display-block position-relative height-70">
							<span style="z-index: 1; background-image: url($tpt_imagesurl/design_center/custom-segmented-btn.png);" class="background-position-CC background-repeat-no-repeat display-block position-absolute top-0 right-0 bottom-0 left-0">
							</span>
						</span>
					</span>
					<span class="display-block height-21 background-position-CC background-repeat-no-repeat" style="background-image: url($tpt_imagesurl/design_center/select-btn.png);"></span>
				</a>
EOT;
            }

            /*
			if(($i % 4 == 3) || ($i == count($items) -1)) {
				$link .= '</div>';
			}
			*/
            $html .= $link;

            $i++;
        }
        $html .= '</div>';
        $html .= '</div>';


        $html .= '<div class="clearFix zStyle_Dual_ID">';
        //$html .= '<div style="background-image: url('.$tpt_imagesurl.'/design_center/txt_segmented.png);" class="height-34 background-position-CC background-repeat-no-repeat padding-top-20 padding-bottom-20"></div>';
        $html .= '<div style="background-image: url(https://www.amazingwristbands.com/live/images/design_center/txt_dual_layer.png);" class="height-34 background-position-CC background-repeat-no-repeat padding-top-20 padding-bottom-20"></div>';
        $html .= '<div class="clearFix">';
        //$items = $this->dual;
        $items = $vars['db']['handler']->getData($vars, 'tpt_color_duallayer', '*', '`enabled`=1 AND ' . $pgType . ' IN (`available_types_ids`) ORDER BY  `label` ASC ', 'id', false);
        array_unshift($items, array());
        $i = 0;

        foreach ($items as $item) {
            //if ($item['1_2'] == '0' && $item['1_4'] == '0' && $item['3_4'] == '0' && $item['1'] == '0' && $item['slap'] == '0' && $item['snap'] == '0' && $item['keychain'] == '0' && $item['ring'] == '0')
            {
                $link = '';

                /*
			if($i % 4 == 0) {
				$link .= '<div class="clearFix">';
			}
			*/


                if (!empty($item)) {
                    $id = $item['id'];
                    $name = htmlentities($item['label']);
                    $ttbg = $tpt_baseurl . '/generate-preview?pg_x=100&pg_y=60&color=10:' . $id . '&timestamp=' . $previewtime . '&type=duallayer';


                    $c = '10:' . $id;
                    $color_module = getModule($vars, "BandColor");
                    $colorProps = $color_module->getColorProps($vars, $c);
                    $options['glitter'] = $colorProps['glitter'];
                    //$options['gClass'] = 'Segmented';

                    $colorcat = 10;
                    $colorid = $id;

                    $cols = array();
                    if (($colorcat == 0) || ($colorcat == 1) || ($colorcat == 2)) {
                        $color = array('color_id' => $colorid);
                    } else if ($colorcat == 10) {
                        $color = array('color_id' => getModule($vars, "BandColor")->all_colors[10][$colorid]['color_id']);
                    } else {
                        $color = getModule($vars, "BandColor")->all_colors[$colorcat][$colorid];
                    }
                    $colid = explode(',', $color['color_id']);
                    foreach ($colid as $cid) {
                        $cols[] = getModule($vars, "BandColor")->by_id[$cid]['hex'];
                    }

                    //$options['segmentColor'] = array_reverse($segmentColor);


                    $options['fullsizeX'] = 100;
                    $options['fullsizeY'] = 60;

                    $glittersuf = '';
                    if (!empty($options['glitter']))
                        $glittersuf = '--glitter';
                    $colidfr = implode('_', $cols);
                    $cfile = TPT_IMAGES_DIR . DIRECTORY_SEPARATOR . 'preview' . DIRECTORY_SEPARATOR . 'cached' . DIRECTORY_SEPARATOR . 'solid' . DIRECTORY_SEPARATOR . 'solid-' . $options['fullsizeX'] . 'x' . $options['fullsizeY'] . '-' . $colidfr . $glittersuf . '.png';
                    if (is_file($cfile)) {
                        $ttbg = $tpt_imagesurl . '/preview/cached/solid/solid-' . $options['fullsizeX'] . 'x' . $options['fullsizeY'] . '-' . $colidfr . $glittersuf . '.png';
                    }


                    $ttbg = 'background-image: url(' . $ttbg . ')';
                    $bandbg = $tpt_baseurl . '/generate-preview?&color=10:' . $id . '&timestamp=' . $previewtime . '&type=coloroption';
                    $opts = array();
                    $opts['fullsizeX'] = 47;
                    $opts['fullsizeY'] = 47;
                    $colorcat = 10;
                    $colorid = $id;
                    $cfile = TPT_IMAGES_DIR . DIRECTORY_SEPARATOR . 'preview' . DIRECTORY_SEPARATOR . 'cached' . DIRECTORY_SEPARATOR . 'coloroption' . DIRECTORY_SEPARATOR . 'coloroption-' . $opts['fullsizeX'] . 'x' . $opts['fullsizeY'] . '-' . $colorcat . '_' . $colorid . '.png';
                    if (is_file($cfile)) {
                        $bandbg = $tpt_imagesurl . '/preview/cached/coloroption/coloroption-' . $opts['fullsizeX'] . 'x' . $opts['fullsizeY'] . '-' . $colorcat . '_' . $colorid . '.png';
                    }
                    $bandbg = 'background-image: url(' . $bandbg . ')';
                    $colorsrc = $bandbg;
                    $link .= <<< EOT
				<a rel="tooltip[tooltip_color_10_$id]" title="$name" style="background-image: url($tpt_imagesurl/design_center/color-option-bg.png);" class="colorPanelOption width-84 height-97 hoverCB background-position-CT background-repeat-no-repeat text-decoration-none float-left font-size-16 display-block text-align-center" href="#" onclick="change_product_band_color(this); return false;">
					<span class="padding-left-2 padding-right-2 padding-top-0 padding-bottom-0 display-block">
						<span class="display-block position-relative height-50">
							<span style="z-index: 1; $bandbg" class="background-position-CC background-repeat-no-repeat display-block position-absolute top-0 right-0 bottom-0 left-0">
							</span>
						</span>
					</span>
					<span class="amz_brown display-block height-20 line-height-10 font-size-10" style="font-family:Arial, Helvetica, sans-serif;">$name</span>
					<span class="display-block height-21 background-position-CC background-repeat-no-repeat" style="background-image: url($tpt_imagesurl/design_center/select-btn.png);"></span>
					<input type="hidden" value="10:$id">
				</a>
EOT;

                    $defaultFont = DEFAULT_FONT_NAME;
                    $data = array();
                    $data['font'] = $defaultFont;
                    $data['text'] = 'DualLayer';
                    $data['bandType'] = 2;
                    $data['bandStyle'] = 7;
                    $data['textColor'] = '10:' . $id;
                    $data['pg_x'] = 280;
                    $data['pg_y'] = 60;
                    $data['type'] = 'plain';
                    $options = $data;
                    $options['fullsizeX'] = 280;
                    $options['fullsizeY'] = 60;
                    $options['utext'] = 'DualLayer';
                    $options['pointsize'] = 0;
                    $options['linespacing'] = 0;
                    $cfile = TPT_IMAGES_DIR . DIRECTORY_SEPARATOR . 'preview' . DIRECTORY_SEPARATOR . 'cached' . DIRECTORY_SEPARATOR . 'plain' . DIRECTORY_SEPARATOR . 'plain-' . $options['fullsizeX'] . 'x' . $options['fullsizeY'] . 'x' . $options['pointsize'] . 'x' . $options['linespacing'] . '-' . str_replace('/', '_', base64_encode($data['utext'])) . '-' . str_replace('/', '_', base64_encode($data['font'])) . '-style' . $data['bandStyle'] . '-' . str_replace('/', '_', base64_encode($data['textColor'])) . '.png';
                    if (!is_file($cfile)) {
                        $dlpreview = tpt_PreviewGenerator::generatePreview($vars, $data);
                        file_put_contents($cfile, $dlpreview);
                    }
                    $curl = TPT_IMAGES_URL . '/preview/cached/plain/plain-' . $options['fullsizeX'] . 'x' . $options['fullsizeY'] . 'x' . $options['pointsize'] . 'x' . $options['linespacing'] . '-' . str_replace('/', '_', base64_encode($data['utext'])) . '-' . str_replace('/', '_', base64_encode($data['font'])) . '-style' . $data['bandStyle'] . '-' . str_replace('/', '_', base64_encode($data['textColor'])) . '.png';
                    $vars['template']['tooltips'] .= <<< EOT
<div id="tooltip_color_10_$id" class="tooltip-wrapper hidden">
	<div class="tooltip-content">
		<div class="width-300 height-60 background-repeat-repeat text-align-center" style="$ttbg">
			<img alt="" src="$curl" />
		</div>
	</div>
</div>
EOT;
                } else {
                    /*
				$link .= <<< EOT
				<a title="Create Custom Dual Color..." style="background-image: url($tpt_imagesurl/design_center/color-option-bg.png);" class="colorPanelOption width-84 height-97 hoverCB background-position-CT background-repeat-no-repeat text-decoration-none float-left font-size-16 display-block text-align-center" href="javascript:void(0);" onclick="">
					<span class="padding-left-2 padding-right-2 padding-top-0 padding-bottom-0 display-block">
						<span class="display-block position-relative height-70">
							<span style="z-index: 1; background-image: url($tpt_imagesurl/design_center/custom-segmented-btn.png);" class="background-position-CC background-repeat-no-repeat display-block position-absolute top-0 right-0 bottom-0 left-0">
							</span>
						</span>
					</span>
					<span class="display-block height-21 background-position-CC background-repeat-no-repeat" style="background-image: url($tpt_imagesurl/design_center/select-btn.png);"></span>
				</a>
EOT;
				*/
                }

                /*
			if(($i % 4 == 3) || ($i == count($items) -1)) {
				$link .= '</div>';
			}
			*/
                $html .= $link;

                $i++;
            }
        }
        $html .= '</div>';
        $html .= '</div>';

        //var_dump($html);die();
        return $html;
    }

    /* slap in house colors selects */

    function Solid_SLAP_Color_Select(&$vars, $selectedColor, $title = 'Select Solid Band Color...')
    {
        $all_colors = $this->special;
        $solid_id = '6';

        $values = array();
        //var_dump($stvals);die();

        $sColor = 0;
        $i = 1;
        $values[] = array(0, $title);
        foreach ($all_colors as $key => $item) {
            if ($item['color_type'] == '3' && $item['glow'] == '0' && $item['glitter'] == '0' && $item['slap'] == '1') //only solid colors set inhouse for slap that has no glow or glitter option on
            {
                $optcolor = '#000';
                if ($item['color_id'] == '1093' || $item['color_id'] == '916' || $item['color_id'] == '169' || $item['color_id'] == '312' || $item['color_id'] == '261')
                    $optcolor = '#FFF';

                $bgcolor = '#' . $this->by_id[$item['color_id']]['hex'];
                $values[] = array($solid_id . ':' . $item['id'], $item['label'], 'attr' => ' style="background-color: ' . $bgcolor . '; color: ' . $optcolor . ';"');

                if ($selectedColor == $solid_id . ':' . $item['id'])
                    $sColor = $i;
                $i++;
            }
        }

        return tpt_html::createSelect($vars, '', $values, $sColor, ' title="' . $title . '" id="_bandcolor_select" onfocus="removeClass(this, \'invalid_field\');" onchange="document.getElementById(\'tpt_pg_bandcolor\').value = document.getElementById(\'_bandcolor_select\').options[document.getElementById(\'_bandcolor_select\').selectedIndex].value; clear_custom_color_indicators(); clear_addons_keepgl(); _short_tpt_pg_change_band_fill();"');
    }

    function Multic_SLAP_Color_Select(&$vars, $selectedColor, $title = 'Select Multicolored Band Color...')
    {
        $all_colors = $this->special;
        $solid_id = '6';

        $values = array();
        //var_dump($stvals);die();

        $sColor = 0;
        $i = 1;
        $values[] = array(0, $title);
        foreach ($all_colors as $key => $item) {
            if ($item['color_type'] != '3' && $item['slap'] == '1') //only not solid colors set inhouse for slap
            {

                $optcolor = '#000';
                $bgcolor = '#FFF';
                $values[] = array($solid_id . ':' . $item['id'], $item['label'], 'attr' => ' style="background-color: ' . $bgcolor . '; color: ' . $optcolor . ';"');

                if ($selectedColor == $solid_id . ':' . $item['id'])
                    $sColor = $i;
                $i++;
            }
        }

        return tpt_html::createSelect($vars, '', $values, $sColor, ' title="' . $title . '" id="_bandcolor_select" onfocus="removeClass(this, \'invalid_field\');" onchange="document.getElementById(\'tpt_pg_bandcolor\').value = document.getElementById(\'_bandcolor_select\').options[document.getElementById(\'_bandcolor_select\').selectedIndex].value; clear_custom_color_indicators(); clear_addons_keepgl(); _short_tpt_pg_change_band_fill();"');
    }

    function Glow_SLAP_Color_Select(&$vars, $selectedColor, $title = 'Select Glow Band Color...')
    {
        $all_colors = $this->special;
        $solid_id = '6';

        $values = array();
        //var_dump($stvals);die();

        $sColor = 0;
        $i = 1;
        $values[] = array(0, $title);
        foreach ($all_colors as $key => $item) {
            if ($item['color_type'] == '3' && $item['glow'] == '1' && $item['glitter'] == '0' && $item['slap'] == '1') //only solid colors set inhouse for slap that has no glitter option on
            {
                $optcolor = '#000';
                $bgcolor = '#FFF';
                $values[] = array($solid_id . ':' . $item['id'], $item['label'], 'attr' => ' style="background-color: ' . $bgcolor . '; color: ' . $optcolor . ';"');

                if ($selectedColor == $solid_id . ':' . $item['id'])
                    $sColor = $i;
                $i++;
            }
        }

        return tpt_html::createSelect($vars, '', $values, $sColor, ' title="' . $title . '" id="_bandcolor_select" onfocus="removeClass(this, \'invalid_field\');" onchange="document.getElementById(\'tpt_pg_bandcolor\').value = document.getElementById(\'_bandcolor_select\').options[document.getElementById(\'_bandcolor_select\').selectedIndex].value; clear_custom_color_indicators(); clear_addons_keepgl(); _short_tpt_pg_change_band_fill();"');
    }

    function Glitter_SLAP_Color_Select(&$vars, $selectedColor, $title = 'Select Glitter Band Color...')
    {
        $all_colors = $this->special;
        $solid_id = '6';

        $values = array();
        //var_dump($stvals);die();

        $sColor = 0;
        $i = 1;
        $values[] = array(0, $title);
        foreach ($all_colors as $key => $item) {
            if ($item['color_type'] == '3' && $item['slap'] == '1' && $item['glitter'] == '1') //only solid colors set inhouse for slap and glitter = 1
            {
                $optcolor = '#000';
                $bgcolor = '#FFF';
                $values[] = array($solid_id . ':' . $item['id'], $item['label'], 'attr' => ' style="background-color: ' . $bgcolor . '; color: ' . $optcolor . ';"');

                if ($selectedColor == $solid_id . ':' . $item['id'])
                    $sColor = $i;
                $i++;
            }
        }

        return tpt_html::createSelect($vars, '', $values, $sColor, ' title="' . $title . '" id="_bandcolor_select" onfocus="removeClass(this, \'invalid_field\');" onchange="document.getElementById(\'tpt_pg_bandcolor\').value = document.getElementById(\'_bandcolor_select\').options[document.getElementById(\'_bandcolor_select\').selectedIndex].value; clear_custom_color_indicators(); clear_addons_keepgl(); _short_tpt_pg_change_band_fill();"');
    }

    function Dual_SLAP_Color_Select(&$vars, $selectedColor, $title = 'Select Dual Layer Band Color...')
    {
        $all_colors = $this->dual;
        $solid_id = '10';

        $values = array();
        //var_dump($stvals);die();

        $sColor = 0;
        $i = 1;
        $values[] = array(0, $title);
        foreach ($all_colors as $key => $item) {
            if ($item['slap'] == '1') //only dual layered colors set inhouse for slap
            {
                $optcolor = '#000';
                $bgcolor = '#FFF';
                $values[] = array($solid_id . ':' . $item['id'], $item['label'], 'attr' => ' style="background-color: ' . $bgcolor . '; color: ' . $optcolor . ';"');

                if ($selectedColor == $solid_id . ':' . $item['id'])
                    $sColor = $i;
                $i++;
            }
        }

        return tpt_html::createSelect($vars, '', $values, $sColor, ' title="' . $title . '" id="_bandcolor_select" onfocus="removeClass(this, \'invalid_field\');" onchange="document.getElementById(\'tpt_pg_bandcolor\').value = document.getElementById(\'_bandcolor_select\').options[document.getElementById(\'_bandcolor_select\').selectedIndex].value; clear_custom_color_indicators(); clear_addons_keepgl(); _short_tpt_pg_change_band_fill();"');
    }

    /* slap in house colors selects end */

    /* 1/2" in house colors selects */

    function Solid_1_2_Color_Select(&$vars, $selectedColor, $title = 'Select Solid Band Color...')
    {
        $all_colors = $this->special;
        $solid_id = '6';

        $values = array();

        $sColor = 0;
        $i = 1;
        $values[] = array(0, $title);
        foreach ($all_colors as $key => $item) {
            if ($item['color_type'] == '3' && $item['glow'] == '0' && $item['glitter'] == '0' && $item['1_2'] == '1') {
                $optcolor = '#000';
                if ($item['color_id'] == '1093' || $item['color_id'] == '916' || $item['color_id'] == '169' || $item['color_id'] == '312' || $item['color_id'] == '261')
                    $optcolor = '#FFF';

                $bgcolor = '#' . $this->by_id[$item['color_id']]['hex'];
                $values[] = array($solid_id . ':' . $item['id'], $item['label'], 'attr' => ' style="background-color: ' . $bgcolor . '; color: ' . $optcolor . ';"');

                if ($selectedColor == $solid_id . ':' . $item['id'])
                    $sColor = $i;
                $i++;
            }
        }

        return tpt_html::createSelect($vars, '', $values, $sColor, ' title="' . $title . '" id="_bandcolor_select" onfocus="removeClass(this, \'invalid_field\');" onchange="document.getElementById(\'tpt_pg_bandcolor\').value = document.getElementById(\'_bandcolor_select\').options[document.getElementById(\'_bandcolor_select\').selectedIndex].value; clear_custom_color_indicators(); clear_addons_keepgl(); _short_tpt_pg_change_band_fill();"');
    }

    function Swirl_1_2_Color_Select(&$vars, $selectedColor, $title = 'Select Swirl Band Color...')
    {
        $all_colors = $this->special;
        $solid_id = '6';

        $values = array();
        //var_dump($stvals);die();

        $sColor = 0;
        $i = 1;
        $values[] = array(0, $title);
        foreach ($all_colors as $key => $item) {
            if ($item['color_type'] == '4' && $item['1_2'] == '1') {
                $optcolor = '#000';
                $bgcolor = '#FFF';
                $values[] = array($solid_id . ':' . $item['id'], $item['label'], 'attr' => ' style="background-color: ' . $bgcolor . '; color: ' . $optcolor . ';"');

                if ($selectedColor == $solid_id . ':' . $item['id'])
                    $sColor = $i;
                $i++;
            }
        }

        return tpt_html::createSelect($vars, '', $values, $sColor, ' title="' . $title . '" id="_bandcolor_select" onfocus="removeClass(this, \'invalid_field\');" onchange="document.getElementById(\'tpt_pg_bandcolor\').value = document.getElementById(\'_bandcolor_select\').options[document.getElementById(\'_bandcolor_select\').selectedIndex].value; clear_custom_color_indicators(); clear_addons_keepgl(); _short_tpt_pg_change_band_fill();"');
    }

    function Dual_1_2_Color_Select(&$vars, $selectedColor, $title = 'Select Dual Layer Band Color...')
    {
        $all_colors = $this->dual;
        $solid_id = '10';

        $values = array();
        //var_dump($stvals);die();

        $sColor = 0;
        $i = 1;
        $values[] = array(0, $title);
        foreach ($all_colors as $key => $item) {
            if ($item['1_2'] == '1' && $item['glow'] == '0') {

                $optcolor = '#000';
                $bgcolor = '#FFF';
                $values[] = array($solid_id . ':' . $item['id'], $item['label'], 'attr' => ' style="background-color: ' . $bgcolor . '; color: ' . $optcolor . ';"');

                if ($selectedColor == $solid_id . ':' . $item['id'])
                    $sColor = $i;
                $i++;
            }
        }

        return tpt_html::createSelect($vars, '', $values, $sColor, ' title="' . $title . '" id="_bandcolor_select" onfocus="removeClass(this, \'invalid_field\');" onchange="document.getElementById(\'tpt_pg_bandcolor\').value = document.getElementById(\'_bandcolor_select\').options[document.getElementById(\'_bandcolor_select\').selectedIndex].value; clear_custom_color_indicators(); clear_addons_keepgl(); _short_tpt_pg_change_band_fill();"');
    }

    function Segmented_1_2_Color_Select(&$vars, $selectedColor, $title = 'Select Segmented Band Color...')
    {
        $all_colors = $this->special;
        $solid_id = '6';

        $values = array();
        //var_dump($stvals);die();

        $sColor = 0;
        $i = 1;
        $values[] = array(0, $title);
        foreach ($all_colors as $key => $item) {
            if ($item['color_type'] == '5' && $item['1_2'] == '1') {

                $optcolor = '#000';
                $bgcolor = '#FFF';
                $values[] = array($solid_id . ':' . $item['id'], $item['label'], 'attr' => ' style="background-color: ' . $bgcolor . '; color: ' . $optcolor . ';"');

                if ($selectedColor == $solid_id . ':' . $item['id'])
                    $sColor = $i;
                $i++;
            }
        }

        return tpt_html::createSelect($vars, '', $values, $sColor, ' title="' . $title . '" id="_bandcolor_select" onfocus="removeClass(this, \'invalid_field\');" onchange="document.getElementById(\'tpt_pg_bandcolor\').value = document.getElementById(\'_bandcolor_select\').options[document.getElementById(\'_bandcolor_select\').selectedIndex].value; clear_custom_color_indicators(); clear_addons_keepgl(); _short_tpt_pg_change_band_fill();"');
    }

    function Glow_1_2_Color_Select(&$vars, $selectedColor, $title = 'Select Glow Band Color...')
    {
        $all_colors = $this->special;

        $values = array();
        //var_dump($stvals);die();

        $sColor = 0;
        $i = 1;
        $values[] = array(0, $title);
        $values['Solid colors'] = array();
        $values['Dual Layer colors'] = array();

        foreach ($all_colors as $key => $item) {
            if ($item['color_type'] == '3' && $item['1_2'] == '1' && $item['glow'] == '1') {
                $solid_id = '6';

                $optcolor = '#000';
                $bgcolor = '#FFF';
                $values['Solid colors'][] = array($solid_id . ':' . $item['id'], $item['label'], 'attr' => ' style="background-color: ' . $bgcolor . '; color: ' . $optcolor . ';"');

                if ($selectedColor == $solid_id . ':' . $item['id'])
                    $sColor = $i;
                $i++;
            }

        }

        return tpt_html::createSelect($vars, '', $values, $sColor, ' title="' . $title . '" id="_bandcolor_select" onfocus="removeClass(this, \'invalid_field\');" onchange="document.getElementById(\'tpt_pg_bandcolor\').value = document.getElementById(\'_bandcolor_select\').options[document.getElementById(\'_bandcolor_select\').selectedIndex].value; clear_custom_color_indicators(); clear_addons_keepgl(); _short_tpt_pg_change_band_fill();"');
    }

    function Dual_Glow_1_2_Color_Select(&$vars, $selectedColor, $title = 'Select Dual Layer Glow Band Color...')
    {
        $dual_colors = $this->dual;

        $values = array();
        //var_dump($stvals);die();

        $sColor = 0;
        $i = 1;
        $values[] = array(0, $title);

        foreach ($dual_colors as $key => $item) {

            if ($item['1_2'] == '1' && $item['glow'] == '1') {
                $solid_id = '10';

                $optcolor = '#000';
                $bgcolor = '#FFF';
                $values[] = array($solid_id . ':' . $item['id'], $item['label'], 'attr' => ' style="background-color: ' . $bgcolor . '; color: ' . $optcolor . ';"');

                if ($selectedColor == $solid_id . ':' . $item['id'])
                    $sColor = $i;
                $i++;
            }
        }

        return tpt_html::createSelect($vars, '', $values, $sColor, ' title="' . $title . '" id="_bandcolor_select" onfocus="removeClass(this, \'invalid_field\');" onchange="document.getElementById(\'tpt_pg_bandcolor\').value = document.getElementById(\'_bandcolor_select\').options[document.getElementById(\'_bandcolor_select\').selectedIndex].value; clear_custom_color_indicators(); clear_addons_keepgl(); _short_tpt_pg_change_band_fill();"');
    }

    function Glitter_1_2_Color_Select(&$vars, $selectedColor, $title = 'Select Glitter Band Color...')
    {
        $all_colors = $this->special;
        $solid_id = '6';

        $values = array();
        //var_dump($stvals);die();

        $sColor = 0;
        $i = 1;
        $values[] = array(0, $title);
        foreach ($all_colors as $key => $item) {
            if ($item['color_type'] == '3' && $item['1_2'] == '1' && $item['glitter'] == '1') {

                $optcolor = '#000';
                $bgcolor = '#FFF';
                $values[] = array($solid_id . ':' . $item['id'], $item['label'], 'attr' => ' style="background-color: ' . $bgcolor . '; color: ' . $optcolor . ';"');

                if ($selectedColor == $solid_id . ':' . $item['id'])
                    $sColor = $i;
                $i++;
            }
        }

        return tpt_html::createSelect($vars, '', $values, $sColor, ' title="' . $title . '" id="_bandcolor_select" onfocus="removeClass(this, \'invalid_field\');" onchange="document.getElementById(\'tpt_pg_bandcolor\').value = document.getElementById(\'_bandcolor_select\').options[document.getElementById(\'_bandcolor_select\').selectedIndex].value; clear_custom_color_indicators(); clear_addons_keepgl(); _short_tpt_pg_change_band_fill();"');
    }

    /* 1/2" in house colors selects end */

    /* 1" in house colors selects */

    function Dual_1_Color_Select(&$vars, $selectedColor, $title = 'Select Dual Layer Band Color...')
    {
        $all_colors = $this->dual;
        $solid_id = '10';

        $values = array();
        //var_dump($stvals);die();

        $sColor = 0;
        $i = 1;
        $values[] = array(0, $title);
        foreach ($all_colors as $key => $item) {
            if ($item['1'] == '1' && $item['glow'] == '0') {

                $optcolor = '#000';
                $bgcolor = '#FFF';
                $values[] = array($solid_id . ':' . $item['id'], $item['label'], 'attr' => ' style="background-color: ' . $bgcolor . '; color: ' . $optcolor . ';"');

                if ($selectedColor == $solid_id . ':' . $item['id'])
                    $sColor = $i;
                $i++;
            }
        }

        return tpt_html::createSelect($vars, '', $values, $sColor, ' title="' . $title . '" id="_bandcolor_select" onfocus="removeClass(this, \'invalid_field\');" onchange="document.getElementById(\'tpt_pg_bandcolor\').value = document.getElementById(\'_bandcolor_select\').options[document.getElementById(\'_bandcolor_select\').selectedIndex].value; clear_custom_color_indicators(); clear_addons_keepgl(); _short_tpt_pg_change_band_fill();"');
    }

    function Dual_Glow_1_Color_Select(&$vars, $selectedColor, $title = 'Select Dual Layer Glow Band Color...')
    {
        $all_colors = $this->dual;
        $solid_id = '10';

        $values = array();
        //var_dump($stvals);die();

        $sColor = 0;
        $i = 1;
        $values[] = array(0, $title);
        foreach ($all_colors as $key => $item) {
            if ($item['1'] == '1' && $item['glow'] == '1') {

                $optcolor = '#000';
                $bgcolor = '#FFF';
                $values[] = array($solid_id . ':' . $item['id'], $item['label'], 'attr' => ' style="background-color: ' . $bgcolor . '; color: ' . $optcolor . ';"');

                if ($selectedColor == $solid_id . ':' . $item['id'])
                    $sColor = $i;
                $i++;
            }
        }

        return tpt_html::createSelect($vars, '', $values, $sColor, ' title="' . $title . '" id="_bandcolor_select" onfocus="removeClass(this, \'invalid_field\');" onchange="document.getElementById(\'tpt_pg_bandcolor\').value = document.getElementById(\'_bandcolor_select\').options[document.getElementById(\'_bandcolor_select\').selectedIndex].value; clear_custom_color_indicators(); clear_addons_keepgl(); _short_tpt_pg_change_band_fill();"');
    }

    /* 1" in house colors selects end */

    function CSB_Color_Select(&$vars, $selectedColor)
    {
        $solid = $this->solid;
        $solid_id = array_search($this->solid, $this->all_colors);
        $multic = $this->sp_multi;
        $multic_id = array_search($this->sp_multi, $this->all_colors);
        $gid = $this->sp_glow;
        $gid_id = array_search($this->sp_glow, $this->all_colors);
        $holiday = $this->sp_glitter;
        $holiday_id = array_search($this->sp_glitter, $this->all_colors);

        $values = array();
        //var_dump($stvals);die();
        $title = 'Select Color';

        $sColor = 0;
        $values[] = array(0, $title);
        $values['Solid colors'] = array();
        foreach ($solid as $key => $item) {
            $values['Solid colors'][] = array($solid_id . ':' . $item['id'], $item['label']);
        }

        $values['Multicolored'] = array();
        foreach ($multic as $key => $item) {
            $values['Multicolored'][] = array($multic_id . ':' . $item['id'], $item['label']);
        }

        $values['Glow in the dark'] = array();
        foreach ($gid as $key => $item) {
            $values['Glow in the dark'][] = array($gid_id . ':' . $item['id'], $item['label']);
        }

        $values['Holiday designs'] = array();
        foreach ($holiday as $key => $item) {
            $values['Holiday designs'][] = array($holiday_id . ':' . $item['id'], $item['label']);
        }

        return tpt_html::createSelect($vars, 'band_color', $values, $selectedColor, ' title="' . $title . '"');
    }

    function Message_Color_Select(&$vars, $selectedColor)
    {
        $solid = $this->solid;
        $solid_id = array_search($this->solid, $this->all_colors);

        $values = array();
        //var_dump($stvals);die();
        $title = 'Select Message Color...';

        $sColor = 0;
        $i = 1;
        $values[] = array(0, $title);
        foreach ($solid as $key => $item) {
            $optcolor = '#000';
            if ($item['color_id'] == '1107' || $item['color_id'] == '1146' || $item['color_id'] == '1145' || $item['color_id'] == '1144' || $item['color_id'] == '1134' || $item['color_id'] == '1128')
                $optcolor = '#FFF';

            $bgcolor = '#' . $this->by_id[$item['color_id']]['hex'];
            $values[] = array($solid_id . ':' . $item['id'], $item['label'], 'attr' => ' style="background-color: ' . $bgcolor . '; color: ' . $optcolor . ';"');

            //var_dump($solid_id.':'.$item['id']);
            if ($selectedColor == $solid_id . ':' . $item['id'])
                $sColor = $i;
            $i++;
        }


        //var_dump($solid_id);//die();
        //var_dump($selectedColor);//die();
        //var_dump($sColor);die();
        return tpt_html::createSelect($vars, '', $values, $sColor, ' id="message_color_select" onfocus="removeClass(this, \'invalid_field\');" onchange="document.getElementById(\'tpt_pg_msgcolor\').value = document.getElementById(\'message_color_select\').options[document.getElementById(\'message_color_select\').selectedIndex].value;_short_tpt_pg_generate_prevew_all();" title="' . $title . '"');
    }


    function Standard_Color_Samples(&$vars)
    {
        $solid = $this->solid;
        $solid_id = array_search($this->solid, $this->all_colors);

        $values = array();
        //var_dump($stvals);die();

        $title = 'Select Color...';
        $sColor = $selectedColor = 0;
        $i = 1;
        $values[] = array(0, $title);
        foreach ($solid as $key => $item) {
            $optcolor = '#000';
            if ($item['color_id'] == '1107' || $item['color_id'] == '1146' || $item['color_id'] == '1145' || $item['color_id'] == '1144' || $item['color_id'] == '1134' || $item['color_id'] == '1128')
                $optcolor = '#FFF';

            $bgcolor = '#' . $this->by_id[$item['color_id']]['hex'];
            $values[] = array($solid_id . ':' . $item['id'], $item['label'], 'attr' => ' style="background-color: ' . $bgcolor . '; color: ' . $optcolor . ';"');

            if ($selectedColor == $solid_id . ':' . $item['id'])
                $sColor = $i;
            $i++;
        }


        // var_dump($values);
        $dd = '';

        foreach ($values as $k => $v) {
            if (empty($k)) continue;
            $dd .=

                '<a class="pantone_sample pantones" id="" onclick="cr=stockToCustomColors[3][' . preg_replace('#^[^:]+:#', '', $v[0]) . '];custom_color_select(\'pantone_color_id_\'+cr.replace(/^[^:]+:/,\'\'),cr.replace(/^[^:]+:/,\'\')); return false;" href="#" ' . $v['attr'] . '>' . $v[1] . '</a>';

        }

        return $dd;
    }

    function Standard_Color_Samples_DC(&$vars)
    {
        $solid = $this->solid;
        $solid_id = array_search($this->solid, $this->all_colors);

        $values = array();
        //var_dump($stvals);die();
        $title = 'Select Color...';
        $sColor = $selectedColor = 0;
        $i = 1;
        $values[] = array(0, $title);
        foreach ($solid as $key => $item) {
            $optcolor = '#000';
            if ($item['color_id'] == '1107' || $item['color_id'] == '1146' || $item['color_id'] == '1145' || $item['color_id'] == '1144' || $item['color_id'] == '1134' || $item['color_id'] == '1128')
                $optcolor = '#FFF';

            $bgcolor = '#' . $this->by_id[$item['color_id']]['hex'];
            $values[] = array($solid_id . ':' . $item['id'], $item['label'], 'attr' => ' style="background-color: ' . $bgcolor . '; color: ' . $optcolor . ';"');

            if ($selectedColor == $solid_id . ':' . $item['id'])
                $sColor = $i;
            $i++;
        }


        // var_dump($values);
        $dd = '';

        foreach ($values as $k => $v) {
            if (empty($k)) continue;
            $dd .=

                '<a class="pantone_sample pantones" id="" onclick="cr=stockToCustomColors[3][' . preg_replace('#^[^:]+:#', '', $v[0]) . '];custom_color_select(\'pantone_color_id_\'+cr.replace(/^[^:]+:/,\'\'),cr.replace(/^[^:]+:/,\'\'),' . preg_replace('#^[^:]+:#', '', $v[0]) . '); return false;" href="#" ' . $v['attr'] . '>' . $v[1] . '</a>';

        }

        return $dd;
    }


    function Solid_Color_Select(&$vars, $selectedColor, $title = 'Select Solid Band Color...')
    {
        $solid = $this->solid;
        $solid_id = array_search($this->solid, $this->all_colors);

        $values = array();
        //var_dump($stvals);die();

        $sColor = 0;
        $i = 1;
        $values[] = array(0, $title);
        foreach ($solid as $key => $item) {
            $optcolor = '#000';
            if ($item['color_id'] == '1107' || $item['color_id'] == '1146' || $item['color_id'] == '1145' || $item['color_id'] == '1144' || $item['color_id'] == '1134' || $item['color_id'] == '1128')
                $optcolor = '#FFF';

            $bgcolor = '#' . $this->by_id[$item['color_id']]['hex'];
            $values[] = array($solid_id . ':' . $item['id'], $item['label'], 'attr' => ' style="background-color: ' . $bgcolor . '; color: ' . $optcolor . ';"');

            if ($selectedColor == $solid_id . ':' . $item['id'])
                $sColor = $i;
            $i++;
        }

        return tpt_html::createSelect($vars, '', $values, $sColor, ' title="' . $title . '" id="_bandcolor_select" onfocus="removeClass(this, \'invalid_field\');" onchange="document.getElementById(\'tpt_pg_bandcolor\').value = document.getElementById(\'_bandcolor_select\').options[document.getElementById(\'_bandcolor_select\').selectedIndex].value; clear_custom_color_indicators(); clear_addons_keepgl(); _short_tpt_pg_change_band_fill();"');
    }

    function Swirl_Color_Select(&$vars, $selectedColor, $title = 'Select Swirl Band Color...')
    {
        $swirl = $this->swirl;
        $swirl_id = array_search($this->swirl, $this->all_colors);

        $values = array();
        //var_dump($stvals);die();

        $sColor = 0;
        $i = 1;
        $values[] = array(0, $title);
        foreach ($swirl as $key => $item) {
            //$optcolor = '#000';
            //if($item['color_id']=='1107')
            //    $optcolor = '#FFF';

            //$bgcolor = '#'.$this->by_id[$item['color_id']]['hex'];
            $values[] = array($swirl_id . ':' . $item['id'], $item['label'], 'attr' => ' style="background-color: white; color: black;"');

            if ($selectedColor == $swirl_id . ':' . $item['id'])
                $sColor = $i;
            $i++;
        }

        return tpt_html::createSelect($vars, '', $values, $sColor, ' title="' . $title . '" id="_bandcolor_select" onfocus="removeClass(this, \'invalid_field\');" onchange="document.getElementById(\'tpt_pg_bandcolor\').value = document.getElementById(\'_bandcolor_select\').options[document.getElementById(\'_bandcolor_select\').selectedIndex].value; clear_custom_color_indicators(); clear_addons_keepgl(); _short_tpt_pg_change_band_fill();"');
    }

    function Segmented_Color_Select(&$vars, $selectedColor, $title = 'Select Segmented Band Color...')
    {
        $segmented = $this->segment;
        $segmented_id = array_search($this->segment, $this->all_colors);

        $values = array();
        //var_dump($stvals);die();

        $sColor = 0;
        $i = 1;
        $values[] = array(0, $title);
        foreach ($segmented as $key => $item) {
            //$optcolor = '#000';
            //if($item['color_id']=='1107')
            //    $optcolor = '#FFF';

            //$bgcolor = '#'.$this->by_id[$item['color_id']]['hex'];
            $values[] = array($segmented_id . ':' . $item['id'], $item['label'], 'attr' => ' style="background-color: white; color: black;"');

            if ($selectedColor == $segmented_id . ':' . $item['id'])
                $sColor = $i;
            $i++;
        }

        return tpt_html::createSelect($vars, '', $values, $sColor, ' title="' . $title . '" id="_bandcolor_select" onfocus="removeClass(this, \'invalid_field\');" onchange="document.getElementById(\'tpt_pg_bandcolor\').value = document.getElementById(\'_bandcolor_select\').options[document.getElementById(\'_bandcolor_select\').selectedIndex].value; clear_custom_color_indicators(); clear_addons_keepgl(); _short_tpt_pg_change_band_fill();"');
    }

    function Dual_Color_Select(&$vars, $selectedColor, $selectId = '_bandcolor_select_dual', $title = 'Select Dual Layer Band Color...')
    {
        $segmented = $this->dual;
        $segmented_id = array_search($this->dual, $this->all_colors);

        $values = array();
        //var_dump($stvals);die();

        $sColor = 0;
        $i = 1;
        $values[] = array(0, $title);
        foreach ($segmented as $key => $item) {
            if ($item['1_2'] == '0' && $item['1_4'] == '0' && $item['3_4'] == '0' && $item['1'] == '0' && $item['slap'] == '0' && $item['snap'] == '0' && $item['keychain'] == '0' && $item['ring'] == '0') {
                //$optcolor = '#000';
                //if($item['color_id']=='1107')
                //    $optcolor = '#FFF';

                //$bgcolor = '#'.$this->by_id[$item['color_id']]['hex'];
                $values[] = array($segmented_id . ':' . $item['id'], $item['label'], 'attr' => ' style="background-color: white; color: black;"');

                if ($selectedColor == $segmented_id . ':' . $item['id'])
                    $sColor = $i;
                $i++;
            }
        }

        return tpt_html::createSelect($vars, '', $values, $sColor, ' title="' . $title . '" id="' . $selectId . '" onfocus="removeClass(document.getElementById(\'_bandcolor_select_dual\'), \'invalid_field\');removeClass(document.getElementById(\'_bandcolor_select_dual_msg\'), \'invalid_field\');" onchange="try{general_onchange(this);}catch(e){};document.getElementById(\'tpt_pg_bandcolor\').value = document.getElementById(\'' . $selectId . '\').options[document.getElementById(\'' . $selectId . '\').selectedIndex].value; addons_change(0); document.getElementById(\'tpt_pg_msgcolor\').value = document.getElementById(\'' . $selectId . '\').options[document.getElementById(\'' . $selectId . '\').selectedIndex].value;_short_tpt_pg_change_band_fill();_short_tpt_pg_generate_prevew_all();"');
    }

    function BandColor_Select(&$vars)
    {
        $items = $vars['db']['handler']->getData($vars, $this->moduleTable, 'id,hex,name');

        $html = '';
        $values = array();

        $title = 'Choose band color...';

        $i = 1;
        foreach ($items as $item) {
            preg_match('#[a-zA-Z\s]{3,}#', $item['name'], $mtch);
            if (!empty($mtch)) {
                if ($i == 1) {
                    $values[] = array($item['hex'], '<span class="amz_brown font-size-18 height-15 display-block padding-left-2 padding-right-2 line-height-15 white-space-nowrap" style="font-family: TODAYSHOP-BOLDITALIC,arial;"' ./* style="border: 1px solid #555;background-color: #'.$item['hex'].';color: #'.inverseHex($item['hex']).';"*/
                        '>' . $title . '</span>', $title);
                    $i = 0;
                }
                $values[] = array($item['hex'], '<span class="height-15 display-block padding-left-2 padding-right-2 line-height-15 white-space-nowrap" style="border: 1px solid #555;background-color: #' . $item['hex'] . ';color: #' . inverseHex($item['hex']) . ';">' . $item['name'] . '</span>', $item['name']);
            }
        }

        $valuesDelimiter = "\n";

        $html = tpt_html::createStyledSelect($vars, 'BandColor', $values, $valuesDelimiter, ' display-block', ' width:210px;', ' width:202px;', ' padding-top-10', 0, 'tpt_pg_updateBandColor', 'tpt_pg_color', ' title="' . $title . '"');

        return $html;
    }

    function TextColor_Select(&$vars)
    {
        $items = $vars['db']['handler']->getData($vars, $this->moduleTable, 'id,hex,name');

        $html = '';
        $values = array();

        $title = 'Choose text color...';

        $i = 1;
        foreach ($items as $item) {
            preg_match('#[a-zA-Z\s]{3,}#', $item['name'], $mtch);
            if (!empty($mtch)) {
                if ($i == 1) {
                    $values[] = array($item['hex'], '<span class="amz_brown font-size-18 height-15 display-block padding-left-2 padding-right-2 line-height-15 white-space-nowrap" style="font-family: TODAYSHOP-BOLDITALIC,arial;"' ./* style="border: 1px solid #555;background-color: #'.$item['hex'].';color: #'.inverseHex($item['hex']).';"*/
                        '>' . $title . '</span>', $title);
                    $i = 0;
                }
                $values[] = array($item['hex'], '<span class="height-15 display-block padding-left-2 padding-right-2 line-height-15 white-space-nowrap" style="border: 1px solid #555;background-color: #' . $item['hex'] . ';color: #' . inverseHex($item['hex']) . ';">' . $item['name'] . '</span>', $item['name']);
            }
        }

        $valuesDelimiter = "\n";

        $html = tpt_html::createStyledSelect($vars, 'TextColor', $values, $valuesDelimiter, ' display-block', ' width:210px;', ' width:202px;', ' padding-top-10', 0, '_debossed_tpt_pg_generate_prevew_all', 'tpt_pg_textcolor', ' title="' . $title . '"');

        return $html;
    }

    function SwirlColor_Select(&$vars, $index)
    {
        $items = $vars['db']['handler']->getData($vars, $this->moduleTable, 'id,hex,name');

        $html = '';
        $values = array();

        $title = 'Choose swirl color...';

        $i = 1;
        foreach ($items as $item) {
            preg_match('#[a-zA-Z\s]{3,}#', $item['name'], $mtch);
            if (!empty($mtch)) {
                if ($i == 1) {
                    $values[] = array($item['hex'], '<span class="amz_brown font-size-18 height-15 display-block padding-left-2 padding-right-2 line-height-15 white-space-nowrap" style="font-family: TODAYSHOP-BOLDITALIC,arial;"' ./* style="border: 1px solid #555;background-color: #'.$item['hex'].';color: #'.inverseHex($item['hex']).';"*/
                        '>' . $title . '</span>', $title);
                    $i = 0;
                }
                $values[] = array($item['hex'], '<span class="height-15 display-block padding-left-2 padding-right-2 line-height-15 white-space-nowrap" style="border: 1px solid #555;background-color: #' . $item['hex'] . ';color: #' . inverseHex($item['hex']) . ';">' . $item['name'] . '</span>', $item['name']);
            }
        }

        $valuesDelimiter = "\n";

        $html = tpt_html::createStyledSelect($vars, 'SwirlColor' . $index, $values, $valuesDelimiter, ' display-block', ' width:210px;', ' width:202px;', ' padding-top-10', 0, '_debossed_tpt_pg_generate_prevew_all', 'tpt_pg_sw_color' . $index, ' title="' . $title . '" id="swirl_select' . $index . '"', 'visibility:hidden;');

        return $html;
    }


    function BandColor_Section_SB_admin(&$vars, $pgconf, $builder, $sColorType = 0)
    {

        // ih
        //	$builder['inhouse'] = 1;
        $this->BandColor_Section_SB($vars, $pgconf, $builder, $sColorType);
        self::$bandColorContent = false;
        $cs_data = array();

        foreach ($vars['misc_data_store']['colorSelects'] as $k => $cs) {
            $cs_data[$k] = array();
            preg_match_all('#<option ?(.*) ?>([^>]*)<\/option>#', $cs, $pt);
            if (is_array(@$pt[1])) foreach ($pt[1] as $pk => $atp) {
                preg_match('#value=\"([^"]*)\"#', $atp, $vm);
                if ($vm[1] != '' && $vm[1] != 0) $cs_data[$k][$vm[1]] = $pt[2][$pk];
            }
        }

        $cs_data_ih = $cs_data;
        $types_ih = $vars['misc_data_store']['colorTypes'];

        // os
        $builder['inhouse'] = 0;
        $this->BandColor_Section_SB($vars, $pgconf, $builder, $sColorType);
        self::$bandColorContent = false;
        $cs_data = array();

        foreach ($vars['misc_data_store']['colorSelects'] as $k => $cs) {
            $cs_data[$k] = array();
            preg_match_all('#<option ?(.*) ?>([^>]*)<\/option>#', $cs, $pt);
            if (is_array(@$pt[1])) foreach ($pt[1] as $pk => $atp) {
                preg_match('#value=\"([^"]*)\"#', $atp, $vm);
                if ($vm[1] != '' && $vm[1] != 0) $cs_data[$k][$vm[1]] = $pt[2][$pk];
            }
        }

        $cs_data_os = $cs_data;
        $types_os = $vars['misc_data_store']['colorTypes'];

        return array(
            'data_ih' => $cs_data_ih,
            'data_os' => $cs_data_os,
            'types_ih' => $types_ih,
            'types_os' => $types_os,
        );

    }


//////////////////////////////////////////////////////////
// /// /// /// BandColor_Section_SB_admin end
//\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\


    function BandColor_Section_SB(&$vars, $pgconf, $builder, $sColorType = 0)
    {

//	    if ($_SERVER['REMOTE_ADDR']=='109.160.0.218') {
//			var_dump("iiiiiiiiii",$colorSelects);
//		}


        //die();
        //debug_print_backtrace();die();
        //xdebug_print_function_stack('asd');

        //tpt_dump($pgconf);
        extract($pgconf);
        $type = $pgType;
        $style = $pgStyle;

        $types_module = getModule($vars, "BandType");
        $data_module = getModule($vars, "BandData");
        //$dType = $type;
        //$tfield = 'available_types_ids';
        //if(isDev()) {
        $dTypeArr = (isset($data_module->typeStyle[$type][$style])?$data_module->typeStyle[$type][$style]:array('id'=>0, 'pricing_type'=>0, 'type'=>0, 'style'=>0, 'writable'=>0));
        $dType = $dTypeArr['id'];
        $dIHType = $dType;
        //if(!empty($dTypeArr['inhouse_style'])){$dIHType = $data_module->typeStyle[$type][$dTypeArr['inhouse_style']]['id'];}
        $tfield = 'available_types_ids2';
        $dfield = 'disabled_types_ids2';
        //}
        //tpt_dump($dType);
        //tpt_dump($type);
        //tpt_dump($style, true);
        if (!empty($dTypeArr['writable'])) {
            $type = $dTypeArr['base_type'];
        }


        $sColorProps = $this->getColorProps($vars, $pgBandColor);
        if ((($style == 7) && empty($sColorProps['dual_layer'])) || (!empty($dTypeArr['pricing_type']) && !empty($sColorProps['custom_color'])) || (($style != 7) && !empty($sColorProps['dual_layer']))) {
            self::$pgBandColor = '-1:' . DEFAULT_BAND_COLOR;
        } else {
            self::$pgBandColor = $pgBandColor;
        }

        //tpt_dump();
        //tpt_dump($pgconf);
        //tpt_dump($selectedColor);
        //tpt_dump($pgBandColor);
        //tpt_dump(self::$pgBandColor, true);


        if ((self::$bandColorContent === false) || (self::$pgBandColor === false)) {
            $html = '';

            $ajax_call = tpt_ajax::getCall('color.change_color_type');

            $checkedRadio = 1;
            //var_dump($sColorType);die();
            if (!empty($sColorType)) {
                $checkedRadio = $sColorType;
            } else {
                $checkedRadio = $this->BandColor_ColorType($vars, self::$pgBandColor, $type, $style, $builder);
                $sColorType = $checkedRadio;
            }

            //tpt_dump($sColorType);
            //tpt_dump($checkedRadio, true);
            $colorTypes = array();
            $colorSelects = array();
            $solid_select = '';
            $swirl_select = '';
            $segmented_select = '';
            $multicolored_select = '';
            $glitter_select = '';
            $glow_select = '';
            $duallayer_select = '';
            $multicolored_select = '';

            //tpt_dump($style);
            if (($style != 7) && ($style != 17)) {
                //$solid_stock_ids = array();
                //if(($type == 5) || in_array($style, array(1, 6)) && isset(getModule($vars, "BandData")->typeStyle[$type]['6']) && (getModule($vars, "BandData")->typeStyle[$type]['6']['minimum_quantity'] == 1)) {
                //$solid_stock_ids = $vars['db']['handler']->getData($vars, 'tpt_color_special', '*', '`enabled`=1 AND `color_type`=3 AND `glitter`=0 AND `glow`=0 AND FIND_IN_SET(\''.$type.'\', `available_types_ids`) ORDER BY  `label` ASC ', 'id', false);
                //}

                //if($_SERVER['REMOTE_ADDR'] == '85.130.3.155') {
                //die();
                $query = <<< EOT
SELECT * FROM (

	(
	SELECT
	`id`,
	`label`,
	`color_id`,
	`color_type`,
	`message_color_id`,
	`glow`,
	`glitter`,
	`uv`,
	`1_2`,
	`1_4`,
	`3_4`,
	`1`,
	`slap`,
	`snap`,
	`keychain`,
	`ring`,
	`$tfield`,
	NULL AS `$dfield`,
	1 AS `stock`,
	REPLACE(`label`, " (", "b (") AS `ordlabel`
	 FROM `tpt_color_special` WHERE
	`enabled`=1 AND
	`color_type`=3 AND
	`glitter`=0 AND
	FIND_IN_SET('$dIHType', `$tfield`) AND NOT
	`label` REGEXP '(True)'
	)

UNION

	(
	SELECT
	`id`,
	`label`,
	`color_id`,
	`color_type`,
	`message_color_id`,
	`glow`,
	`glitter`,
	`uv`,
	`1_2`,
	`1_4`,
	`3_4`,
	`1`,
	`slap`,
	`snap`,
	`keychain`,
	`ring`,
	`$tfield`,
	NULL AS `$dfield`,
	1 AS `stock`,
	REPLACE(`label`, " (", "a (") AS `ordlabel`
	 FROM `tpt_color_special` WHERE
	`enabled`=1 AND
	`color_type`=3 AND
	`glitter`=0 AND
	FIND_IN_SET('$dIHType', `$tfield`) AND
	`label` REGEXP '(True)'
	)

UNION

	(
	SELECT
		`id`,
		`label`,
		`color_id`,
		`color_type`,
		NULL AS `message_color_id`,
		NULL AS `glow`,
		NULL AS `glitter`,
		NULL AS `uv`,
		NULL AS `1_2`,
		NULL AS `1_4`,
		NULL AS `3_4`,
		NULL AS `1`,
		NULL AS `slap`,
		NULL AS `snap`,
		NULL AS `keychain`,
		NULL AS `ring`,
		NULL AS `$tfield`,
		`$dfield`,
		NULL AS `stock`,
		REPLACE(`label`, " (", "b (") AS `ordlabel`
	FROM
		`tpt_color_overseas`
	WHERE
		`color_type`=3
		AND
		`enabled`=1
		AND
		(
			`$dfield` IS NULL
			OR
			`$dfield`=''
			OR
			NOT FIND_IN_SET('$dType', `$dfield`)
		)
		AND
		`label` NOT IN
			(
			SELECT `label`
			FROM `tpt_color_special` WHERE
			`enabled`=1 AND
			`color_type`=3 AND
			`glitter`=0 AND
			FIND_IN_SET('$dType', `$tfield`)
			)
		AND NOT
		`label` REGEXP '(True)'
	)

UNION

	(
	SELECT
		`id`,
		`label`,
		`color_id`,
		`color_type`,
		NULL AS `message_color_id`,
		NULL AS `glow`,
		NULL AS `glitter`,
		NULL AS `uv`,
		NULL AS `1_2`,
		NULL AS `1_4`,
		NULL AS `3_4`,
		NULL AS `1`,
		NULL AS `slap`,
		NULL AS `snap`,
		NULL AS `keychain`,
		NULL AS `ring`,
		NULL AS `$tfield`,
		`$dfield`,
		NULL AS `stock`,
		REPLACE(`label`, " (", "a (") AS `ordlabel`
	FROM
		`tpt_color_overseas`
	WHERE
		`color_type`=3
		AND
		`enabled`=1
		AND
		(
			`$dfield` IS NULL
			OR
			`$dfield`=''
			OR
			NOT FIND_IN_SET('$dType', `$dfield`)
		)
		AND
		`label` NOT IN
			(
			SELECT `label`
			FROM `tpt_color_special` WHERE
			`enabled`=1 AND
			`color_type`=3 AND
			`glitter`=0 AND
			FIND_IN_SET('$dType', `$tfield`)
			)
		AND
		`label` REGEXP '(True)'
)
ORDER BY `ordlabel` ASC) AS `a` GROUP BY `ordlabel`
EOT;

//tpt_dump($query, true);
                $vars['db']['handler']->query($query);
                $solid_stock_ids = $vars['db']['handler']->fetch_assoc_list();

                //var_dump($solid_stock_ids);
                //die();
                //}

                //$solid_stock_labels = $vars['db']['handler']->getData($vars, 'tpt_color_special', '*', '`enabled`=1 AND `color_type`=3 AND FIND_IN_SET(\''.$type.'\', `$tfield`) ORDER BY  `label` ASC ', 'label', false);
                //$solid_suggested_ids = $this->solid;
                //$solid_suggested_labels = $vars['db']['handler']->getData($vars, 'tpt_color_solid', '*', '`enabled`=1 ORDER BY  `label` ASC ', 'label', false);
                //$solid_select = $this->Create_Combined_Solids_Select($vars, $selectedColor, $solid_stock_ids, $solid_suggested_ids, $solid_suggested_labels, '3');
                //if($_SERVER['REMOTE_ADDR'] == '85.130.3.155') {
                //die();
                $solid_select = $this->Create_Combined_Solids_Select2($vars, self::$pgBandColor, $solid_stock_ids, '3', true, false);
                //}
                if ($builder['inhouse'] || ($type == 5)) {
                    //$solid_select = $this->Create_Stock_Solids_Select($vars, $selectedColor, $solid_stock_ids);

                    //if($_SERVER['REMOTE_ADDR'] == '85.130.3.155') {
                    //die();
                    $solid_select = $this->Create_Combined_Solids_Select2($vars, self::$pgBandColor, $solid_stock_ids, '3', false, true);
                    //}
                }
                $colorTypes['1'] = array('id' => '1', 'label' => 'Solid', 'name' => 'solid', 'attr' => '');
                $colorSelects['1'] = $solid_select;


                if (($dTypeArr['type'] != 5) && (empty($dTypeArr['writable']) || ($dTypeArr['base_type'] != 5))) {
                    if (!$builder['inhouse']) {
                        //$swirl_stock_ids = array();
                        //if((in_array($style, array(1, 6)) && isset(getModule($vars, "BandData")->typeStyle[$type]['6']) && getModule($vars, "BandData")->typeStyle[$type]['6']['minimum_quantity'] == 1)) {
                        //$swirl_stock_ids = $vars['db']['handler']->getData($vars, 'tpt_color_special', '*', '`enabled`=1 AND `color_type`=4 AND `glitter`=0 AND `glow`=0 AND FIND_IN_SET(\''.$type.'\', `$tfield`) ORDER BY  `label` ASC ', 'id', false);
                        //}
                        $query = <<< EOT
SELECT * FROM (

	(
	SELECT
	`id`,
	`label`,
	`color_type`,
	`color_id`,
	`message_color_id`,
	`glow`,
	`glitter`,
	`uv`,
	`1_2`,
	`1_4`,
	`3_4`,
	`1`,
	`slap`,
	`snap`,
	`keychain`,
	`ring`,
	`$tfield`,
	NULL AS `$dfield`,
	1 AS `stock`
	 FROM `tpt_color_special` WHERE
	`enabled`=1 AND
	`color_type`=4 AND
	FIND_IN_SET('$dIHType', `$tfield`)
	)

UNION

	(
	SELECT
		`id`,
		`label`,
		`color_type`,
		`color_id`,
		NULL AS `message_color_id`,
		NULL AS `glow`,
		NULL AS `glitter`,
		NULL AS `uv`,
		NULL AS `1_2`,
		NULL AS `1_4`,
		NULL AS `3_4`,
		NULL AS `1`,
		NULL AS `slap`,
		NULL AS `snap`,
		NULL AS `keychain`,
		NULL AS `ring`,
		NULL AS `$tfield`,
		`$dfield`,
		NULL AS `stock`
	FROM
		`tpt_color_overseas`
	WHERE
		`color_type`=4
		AND
		`enabled`=1
		AND
		(
			`$dfield` IS NULL
			OR
			`$dfield`=''
			OR
			NOT FIND_IN_SET('$dType', `$dfield`)
		)
		AND
		`label` NOT IN
			(
			SELECT `label`
			FROM `tpt_color_special` WHERE
			`enabled`=1 AND
			`color_type`=4 AND
			FIND_IN_SET('$dType', `$tfield`)
			)
)
ORDER BY `label` ASC) AS `a` GROUP BY `label`
EOT;
                        //tpt_dump($query, true);
                        $vars['db']['handler']->query($query, __FILE__);
                        $swirl_stock_ids = $vars['db']['handler']->fetch_assoc_list();
                        //var_dump($swirl_stock_ids);die();
                        //$swirl_suggested_ids = $this->swirl;
                        //$swirl_suggested_labels = $vars['db']['handler']->getData($vars, 'tpt_color_swirl', '*', '`enabled`=1 ORDER BY  `label` ASC ', 'label', false);
                        //$swirl_select = $this->Create_Combined_Select($vars, $selectedColor, $swirl_stock_ids, $swirl_suggested_ids, $swirl_suggested_labels, '4', 'Select Swirl Band Color...');
                        $swirl_select = $this->Create_Combined_Select2($vars, self::$pgBandColor, $swirl_stock_ids, ($style == 1), false, 'Select Swirl Band Color...');
                        //if($builder['inhouse'])
                        //    $swirl_select = $this->Create_Combined_Select2($vars, $selectedColor, $swirl_stock_ids, '4', false, true, 'Select Swirl Band Color...');
                        $colorTypes['2'] = array('id' => '2', 'label' => 'Swirl', 'name' => 'swirl', 'attr' => '');
                        $colorSelects['2'] = $swirl_select;

                        //$segmented_stock_ids = array();
                        //var_dump($style); die();
                        //var_dump($type); die();


                        // if NOT writable include segments
                        if (empty($dTypeArr['writable'])) {
                            //die();
                            $query = <<< EOT
SELECT * FROM (

	(
	SELECT
	`id`,
	`label`,
	`color_type`,
	`color_id`,
	`message_color_id`,
	`glow`,
	`glitter`,
	`uv`,
	`1_2`,
	`1_4`,
	`3_4`,
	`1`,
	`slap`,
	`snap`,
	`keychain`,
	`ring`,
	`$tfield`,
	NULL AS `$dfield`,
	1 AS `stock`
	 FROM `tpt_color_special` WHERE
	`enabled`=1 AND
	`color_type`=5 AND
	FIND_IN_SET('$dIHType', `$tfield`)
	)

UNION

	(
	SELECT
		`id`,
		`label`,
		`color_type`,
		`color_id`,
		NULL AS `message_color_id`,
		NULL AS `glow`,
		NULL AS `glitter`,
		NULL AS `uv`,
		NULL AS `1_2`,
		NULL AS `1_4`,
		NULL AS `3_4`,
		NULL AS `1`,
		NULL AS `slap`,
		NULL AS `snap`,
		NULL AS `keychain`,
		NULL AS `ring`,
		NULL AS `$tfield`,
		`$dfield`,
		NULL AS `stock`
	FROM
		`tpt_color_overseas`
	WHERE
		`color_type`=5
		AND
		`enabled`=1
		AND
		(
			`$dfield` IS NULL
			OR
			`$dfield`=''
			OR
			NOT FIND_IN_SET('$dType', `$dfield`)
		)
		AND
		`label` NOT IN
			(
			SELECT `label`
			FROM `tpt_color_special` WHERE
			`enabled`=1 AND
			`color_type`=5 AND
			FIND_IN_SET('$dType', `$tfield`)
			)
)
ORDER BY `label` ASC) AS `a` GROUP BY `label`
EOT;


                            //tpt_dump($query, true);
                            $vars['db']['handler']->query($query);
                            $segmented_stock_ids = $vars['db']['handler']->fetch_assoc_list();
                            //if((in_array($style, array(1, 6)) && isset(getModule($vars, "BandData")->typeStyle[$type]['6']) && getModule($vars, "BandData")->typeStyle[$type]['6']['minimum_quantity'] == 1)) {
                            //$segmented_stock_ids = $vars['db']['handler']->getData($vars, 'tpt_color_special', '*', '`enabled`=1 AND `color_type`=5 AND `glitter`=0 AND `glow`=0 AND FIND_IN_SET(\''.$type.'\', `$tfield`) ORDER BY  `label` ASC ', 'id', false);
                            //}
                            //var_dump($segmented_stock_ids);die();
                            //$segmented_suggested_ids = $this->segment;
                            //$segmented_suggested_labels = $vars['db']['handler']->getData($vars, 'tpt_color_segmented', '*', '`enabled`=1 ORDER BY  `label` ASC ', 'label', false);
                            //$segmented_select = $this->Create_Combined_Select($vars, $selectedColor, $segmented_stock_ids, $segmented_suggested_ids, $segmented_suggested_labels, '5', 'Select Segmented Band Color...');
                            $segmented_select = $this->Create_Combined_Select2($vars, self::$pgBandColor, $segmented_stock_ids, ($style == 1), false, 'Select Segmented Band Color...');
                            //if($builder['inhouse'])
                            //    $segmented_select = $this->Create_Stock_Select($vars, $selectedColor, $segmented_stock_ids, '6', 'Select Segmented Band Color...');
                            $colorTypes['3'] = array('id' => '3', 'label' => 'Segmented', 'name' => 'segmented', 'attr' => '');
                            $colorSelects['3'] = $segmented_select;
                        } // if NOT writable include segments END

                        $glitter_stock_ids = $vars['db']['handler']->getData($vars, 'tpt_color_overseas', '*', '`enabled`=1 AND `glitter`!=0 ORDER BY  `label` ASC ', 'id', false);
                        $glitter_select = $this->Create_Combined_Select2($vars, self::$pgBandColor, $glitter_stock_ids, ($style == 1), false, 'Select Band Color (/w Glitter)...');
                        //$glitter_select =  $this->Create_Stock_Select($vars, self::$pgBandColor, $glitter_stock_ids, '6', 'Select Band Color (/w Glitter)...', (!$builder['inhouse'] && in_array($style, array(1, 6))));
                        /*
					if($builder['inhouse'])
						$multicolored_select = $this->Create_Stock_Select($vars, $selectedColor, $swirl_stock_ids, '6', 'Select Swirl Band Color...');
					*/
                        $colorTypes['6'] = array('id' => '6', 'label' => 'Glitter', 'name' => 'glitter', 'attr' => '');
                        $colorSelects['6'] = $glitter_select;


                        $glow_stock_ids = $vars['db']['handler']->getData($vars, 'tpt_color_overseas', '*', '`enabled`=1 AND `glow`=1 AND `glitter`=0 ORDER BY  `label` ASC ', 'id', false);
                        $glow_select = $this->Create_Combined_Select2($vars, self::$pgBandColor, $glow_stock_ids, ($style == 1), false, 'Select Band Color (/w Glow in the Dark)...');
                        //$glow_select =  $this->Create_Stock_Select($vars, self::$pgBandColor, $glow_stock_ids, '6', 'Select Band Color (/w Glow in the Dark)...', (!$builder['inhouse'] && in_array($style, array(1, 6))));
                        /*
					if($builder['inhouse'])
						$multicolored_select = $this->Create_Stock_Select($vars, $selectedColor, $swirl_stock_ids, '6', 'Select Swirl Band Color...');
					*/
                        $colorTypes['7'] = array('id' => '7', 'label' => 'Glow', 'name' => 'glow', 'attr' => '');
                        $colorSelects['7'] = $glow_select;
                    } else {
                        $multicolored_stock_ids = $vars['db']['handler']->getData($vars, 'tpt_color_special', '*', '`enabled`=1 AND (`color_type`=4 OR `color_type`=5) AND ' ./*`glitter`=0 AND `glow`=0 AND*/
                            'FIND_IN_SET(\'' . $dType . '\', `' . $tfield . '`) ORDER BY  `label` ASC ', 'id', false);
                        if (!empty($multicolored_stock_ids)) {
                            $multicolored_select = $this->Create_Stock_Select($vars, self::$pgBandColor, $multicolored_stock_ids, '6', 'Select Swirl/Segmented Band Color...', (!$builder['inhouse'] && in_array($style, array(1, 6))));
                            /*
					if($builder['inhouse'])
						$multicolored_select = $this->Create_Stock_Select($vars, $selectedColor, $swirl_stock_ids, '6', 'Select Swirl Band Color...');
					*/
                            $colorTypes['5'] = array('id' => '5', 'label' => 'Multicolored', 'name' => 'multic', 'attr' => '');
                            $colorSelects['5'] = $multicolored_select;
                        }


                        $glitter_stock_ids = $vars['db']['handler']->getData($vars, 'tpt_color_special', '*', '`enabled`=1 AND `glitter`=1 AND FIND_IN_SET(\'' . $dType . '\', `' . $tfield . '`) ORDER BY  `label` ASC ', 'id', false);
                        if (!empty($glitter_stock_ids)) {
                            $glitter_select = $this->Create_Stock_Select($vars, self::$pgBandColor, $glitter_stock_ids, '6', 'Select Band Color (/w Glitter)...', (!$builder['inhouse'] && in_array($style, array(1, 6))));
                            /*
					if($builder['inhouse'])
						$multicolored_select = $this->Create_Stock_Select($vars, $selectedColor, $swirl_stock_ids, '6', 'Select Swirl Band Color...');
					*/
                            $colorTypes['6'] = array('id' => '6', 'label' => 'Glitter', 'name' => 'glitter', 'attr' => '');
                            $colorSelects['6'] = $glitter_select;
                        }


                        $glow_stock_ids = $vars['db']['handler']->getData($vars, 'tpt_color_special', '*', '`enabled`=1 AND `glow`=1 AND `glitter`=0 AND FIND_IN_SET(\'' . $dType . '\', `' . $tfield . '`) ORDER BY  `label` ASC ', 'id', false);
                        if (!empty($glow_stock_ids)) {
                            $glow_select = $this->Create_Stock_Select($vars, self::$pgBandColor, $glow_stock_ids, '6', 'Select Band Color (/w Glow in the Dark)...', (!$builder['inhouse'] && in_array($style, array(1, 6))));
                            /*
					if($builder['inhouse'])
						$multicolored_select = $this->Create_Stock_Select($vars, $selectedColor, $swirl_stock_ids, '6', 'Select Swirl Band Color...');
					*/
                            $colorTypes['7'] = array('id' => '7', 'label' => 'Glow', 'name' => 'glow', 'attr' => '');
                            $colorSelects['7'] = $glow_select;
                        }
                    }
                } else {
                    //tpt_dump($dType, true);
                    $multicolored_stock_ids = $vars['db']['handler']->getData($vars, 'tpt_color_special', '*', '`enabled`=1 AND (`color_type`=4 OR `color_type`=5) AND `glitter`=0 AND `glow`=0 AND FIND_IN_SET(\'' . $dType . '\', `' . $tfield . '`) ORDER BY  `label` ASC ', 'id', false);
                    if (empty($dTypeArr['writable'])) {
                        $multicolored_select = $this->Create_Stock_Select($vars, self::$pgBandColor, $multicolored_stock_ids, '6', 'Select Swirl/Segmented Band Color...', (!$builder['inhouse'] && in_array($style, array(1, 6))), true);
                        $colorTypes['5'] = array('id' => '5', 'label' => 'Multicolored', 'name' => 'multic', 'attr' => '');
                    } else {
                        $multicolored_select = $this->Create_Stock_Select($vars, self::$pgBandColor, $multicolored_stock_ids, '6', 'Select Swirl Band Color...', (!$builder['inhouse'] && in_array($style, array(1, 6))), true);
                        $colorTypes['5'] = array('id' => '5', 'label' => 'Swirl', 'name' => 'multic', 'attr' => '');
                    }
                    /*
				if($builder['inhouse'])
					$multicolored_select = $this->Create_Stock_Select($vars, $selectedColor, $swirl_stock_ids, '6', 'Select Swirl Band Color...');
				*/
                    /*
				if(empty($dTypeArr['writable'])) {
				$colorTypes['5'] = array('id'=>'5', 'label'=>'Multicolored', 'name'=>'multic', 'attr'=>'');
				} else {
				$colorTypes['5'] = array('id'=>'5', 'label'=>'Swirl', 'name'=>'multic', 'attr'=>'');
				}
				*/
                    $colorSelects['5'] = $multicolored_select;

                    $glitter_stock_ids = $vars['db']['handler']->getData($vars, 'tpt_color_special', '*', '`enabled`=1 AND `glitter`!=0 AND FIND_IN_SET(\'' . $dType . '\', `' . $tfield . '`) ORDER BY  `label` ASC ', 'id', false);
                    $glitter_select = $this->Create_Stock_Select($vars, self::$pgBandColor, $glitter_stock_ids, '6', 'Select Band Color (/w Glitter)...', (!$builder['inhouse'] && in_array($style, array(1, 6))));
                    /*
				if($builder['inhouse'])
					$multicolored_select = $this->Create_Stock_Select($vars, $selectedColor, $swirl_stock_ids, '6', 'Select Swirl Band Color...');
				*/
                    $colorTypes['6'] = array('id' => '6', 'label' => 'Glitter', 'name' => 'glitter', 'attr' => '');
                    $colorSelects['6'] = $glitter_select;


                    $glow_stock_ids = $vars['db']['handler']->getData($vars, 'tpt_color_special', '*', '`enabled`=1 AND `glow`=1 AND `glitter`=0 AND FIND_IN_SET(\'' . $dType . '\', `' . $tfield . '`) ORDER BY  `label` ASC ', 'id', false);
                    $glow_select = $this->Create_Stock_Select($vars, self::$pgBandColor, $glow_stock_ids, '6', 'Select Band Color (/w Glow in the Dark)...', (!$builder['inhouse'] && in_array($style, array(1, 6))));
                    /*
				if($builder['inhouse'])
					$multicolored_select = $this->Create_Stock_Select($vars, $selectedColor, $swirl_stock_ids, '6', 'Select Swirl Band Color...');
				*/
                    $colorTypes['7'] = array('id' => '7', 'label' => 'Glow', 'name' => 'glow', 'attr' => '');
                    $colorSelects['7'] = $glow_select;
                }
            } else {
                $labelcmp = 'dl.`label`';
                //if($type == 5) {
                //    $labelcmp = 'CONCAT(dl.`label`, " Msg") AS `label`';
                //}
                //$sColorType = '4';
                $colorTypes = array();

                $query = 'SELECT dl.`id`, ' . $labelcmp . ', dl.`message_color_id`, dl.`' . $tfield . '`, sp.`id` AS msgid, sp.`label` AS msglabel, sp.`color_type`, sp.`glow`, sp.`glitter`, sp.`uv` FROM `tpt_color_duallayer` AS dl LEFT JOIN `tpt_color_special` AS sp ON dl.`message_color_id`=sp.`id` WHERE dl.`enabled`=1 AND FIND_IN_SET(\'' . $dType . '\', dl.`' . $tfield . '`) AND sp.`color_type`=3 AND sp.`glitter`=0 AND sp.`uv`=0 ORDER BY `label` ASC';
                //tpt_dump($query);
                $vars['db']['handler']->query($query);
                //tpt_dump($query);
                $solid_stock_ids = $vars['db']['handler']->fetch_assoc_list('id', false);
                //tpt_dump($solid_stock_ids);
                //tpt_dump($dType);
                //tpt_dump($type);
                //tpt_dump($query, true);
                //$duallayer_stock_ids = $vars['db']['handler']->getData($vars, 'tpt_color_duallayer', '*', '`enabled`=1 AND FIND_IN_SET(\''.$type.'\', `$tfield`) ORDER BY  `label` ASC ', 'id', false);
                //tpt_dump($solid_stock_ids, true);
                $duallayer_select = $this->Create_Stock_Select($vars, self::$pgBandColor, $solid_stock_ids, '10', 'Select Dual Layer Solids Color Set...', false, true, true);
                $colorTypes['1'] = array('id' => '1', 'label' => 'Solids', 'name' => 'solid', 'attr' => ' disabled="disabled"');
                $colorSelects['1'] = $duallayer_select;
                //$checkedRadio = '4';


                $query = 'SELECT dl.`id`, ' . $labelcmp . ', dl.`message_color_id`, dl.`' . $tfield . '`, sp.`id` AS msgid, sp.`label` AS msglabel, sp.`color_type`, sp.`glow`, sp.`glitter`, sp.`uv` FROM `tpt_color_duallayer` AS dl LEFT JOIN `tpt_color_special` AS sp ON dl.`message_color_id`=sp.`id` WHERE dl.`enabled`=1 AND FIND_IN_SET(\'' . $dType . '\', dl.`' . $tfield . '`) AND (sp.`color_type`=4 OR sp.`color_type`=5) ORDER BY `label` ASC';
                $vars['db']['handler']->query($query);
                $multicolored_stock_ids = $vars['db']['handler']->fetch_assoc_list('id', false);
                //tpt_dump($solid_stock_ids);
                if (!empty($multicolored_stock_ids)) {
                    //$multicolored_stock_ids = $vars['db']['handler']->getData($vars, 'tpt_color_duallayer', '*', '`enabled`=1 AND (`color_type`=4 OR `color_type`=5) AND `glitter`=0 AND `glow`=0 AND FIND_IN_SET(\''.$type.'\', `$tfield`) ORDER BY  `label` ASC ', 'id', false);
                    $multicolored_select = $this->Create_Stock_Select($vars, self::$pgBandColor, $multicolored_stock_ids, '10', 'Select Dual Layer Color Set (/w Multicolored Msg)...', false, true, true);
                    /*
			if($builder['inhouse'])
				$multicolored_select = $this->Create_Stock_Select($vars, $selectedColor, $swirl_stock_ids, '6', 'Select Swirl Band Color...');
			*/
                    $multilabel = 'Multi-Colored Msg';
                    if ($type == 5) {
                        $multilabel = 'Multi-Colored Band';
                    }
                    $colorTypes['5'] = array('id' => '5', 'label' => $multilabel, 'name' => 'multic', 'attr' => '');
                    $colorSelects['1'] = $solid_select;
                    $colorSelects['2'] = $swirl_select;
                    $colorSelects['3'] = $segmented_select;
                    $colorSelects['5'] = $multicolored_select;
                    $colorSelects['6'] = $glitter_select;
                    $colorSelects['7'] = $glow_select;
                    $colorSelects['5'] = $multicolored_select;
                    $colorSelects['6'] = $glitter_select;
                    $colorSelects['7'] = $glow_select;
                    $colorSelects['1'] = $duallayer_select;
                    $colorSelects['5'] = $multicolored_select;
                }

                $query = 'SELECT dl.`id`, ' . $labelcmp . ', dl.`message_color_id`, dl.`' . $tfield . '`, sp.`id` AS msgid, sp.`label` AS msglabel, sp.`color_type`, sp.`glow`, sp.`glitter`, sp.`uv` FROM `tpt_color_duallayer` AS dl LEFT JOIN `tpt_color_special` AS sp ON dl.`message_color_id`=sp.`id` WHERE dl.`enabled`=1 AND FIND_IN_SET(\'' . $dType . '\', dl.`' . $tfield . '`) AND sp.`glitter`!=0 ORDER BY `label` ASC';
                $vars['db']['handler']->query($query);

                //tpt_dump($query, true);
                $glitter_stock_ids = $vars['db']['handler']->fetch_assoc_list('id', false);
                //tpt_dump($glitter_stock_ids);
                if (!empty($glitter_stock_ids)) {
                    $glitter_select = $this->Create_Stock_Select($vars, self::$pgBandColor, $glitter_stock_ids, '10', 'Select Band Color (/w Glitter)...', false, false, true);
                    /*
			if($builder['inhouse'])
				$multicolored_select = $this->Create_Stock_Select($vars, $selectedColor, $swirl_stock_ids, '6', 'Select Swirl Band Color...');
			*/
                    $glitterlabel = 'Glitter Msg';
                    if ($type == 5) {
                        $glitterlabel = 'Glitter Band';
                    }
                    $colorTypes['6'] = array('id' => '6', 'label' => $glitterlabel, 'name' => 'glitter', 'attr' => '');
                    $colorSelects['6'] = $glitter_select;

                }


                $query = 'SELECT dl.`id`, ' . $labelcmp . ', dl.`message_color_id`, dl.`' . $tfield . '`, sp.`id` AS msgid, sp.`label` AS msglabel, sp.`color_type`, sp.`glow`, sp.`glitter`, sp.`uv` FROM `tpt_color_duallayer` AS dl LEFT JOIN `tpt_color_special` AS sp ON dl.`message_color_id`=sp.`id` WHERE dl.`enabled`=1 AND FIND_IN_SET(\'' . $dType . '\', dl.`' . $tfield . '`) AND sp.`color_type`=3 AND sp.`glitter`=0 AND sp.`glow`=1 ORDER BY `label` ASC';
                $vars['db']['handler']->query($query, __FILE__);
                $glow_stock_ids = $vars['db']['handler']->fetch_assoc_list('id', false);
                //tpt_dump($glow_stock_ids);
                if (!empty($glow_stock_ids)) {
                    $glow_select = $this->Create_Stock_Select($vars, self::$pgBandColor, $glow_stock_ids, '10', 'Select Dual Layer Color Set (/w Glow Message)...', false, true, true);
                    /*
			if($builder['inhouse'])
				$multicolored_select = $this->Create_Stock_Select($vars, $selectedColor, $swirl_stock_ids, '6', 'Select Swirl Band Color...');
			*/
                    $glowlabel = 'Glow Msg';
                    if ($type == 5) {
                        $glowlabel = 'Glow Band';
                    }
                    $colorTypes['7'] = array('id' => '7', 'label' => $glowlabel, 'name' => 'glow', 'attr' => '');
                    $colorSelects['7'] = $glow_select;
                }

                $query = 'SELECT dl.`id`, ' . $labelcmp . ', dl.`message_color_id`, dl.`' . $tfield . '`, sp.`id` AS msgid, sp.`label` AS msglabel, sp.`color_type`, sp.`glow`, sp.`glitter`, sp.`uv` FROM `tpt_color_duallayer` AS dl LEFT JOIN `tpt_color_special` AS sp ON dl.`message_color_id`=sp.`id` WHERE dl.`enabled`=1 AND FIND_IN_SET(\'' . $dType . '\', dl.`' . $tfield . '`) AND dl.`powdercoat`!=0 ORDER BY `label` ASC';
                $vars['db']['handler']->query($query, __FILE__);
                $powdercoat_stock_ids = $vars['db']['handler']->fetch_assoc_list('id', false);
                //tpt_dump($powdercoat_stock_ids);
                if (!empty($powdercoat_stock_ids)) {
                    $powdercoat_select = $this->Create_Stock_Select($vars, self::$pgBandColor, $powdercoat_stock_ids, '10', 'Select Dual Layer Color Set (Powder Coated)...', false, true, true);
                    /*
			if($builder['inhouse'])
				$multicolored_select = $this->Create_Stock_Select($vars, $selectedColor, $swirl_stock_ids, '6', 'Select Swirl Band Color...');
			*/
                    $powdercoatlabel = 'Powder Coated';
                    $colorTypes['8'] = array('id' => '8', 'label' => $powdercoatlabel, 'name' => 'powdercoat', 'attr' => '');
                    $colorSelects['8'] = $powdercoat_select;
                }

                $query = 'SELECT dl.`id`, ' . $labelcmp . ', dl.`message_color_id`, dl.`' . $tfield . '`, sp.`id` AS msgid, sp.`label` AS msglabel, sp.`color_type`, sp.`glow`, sp.`glitter`, sp.`uv` FROM `tpt_color_duallayer` AS dl LEFT JOIN `tpt_color_special` AS sp ON dl.`message_color_id`=sp.`id` WHERE dl.`enabled`=1 AND FIND_IN_SET(\'' . $dType . '\', dl.`' . $tfield . '`) AND dl.`notched`!=0 ORDER BY `label` ASC';
                //tpt_dump($query, true);
                $vars['db']['handler']->query($query, __FILE__);
                $notched_stock_ids = $vars['db']['handler']->fetch_assoc_list('id', false);
                //tpt_dump($notched_stock_ids);
                if (!empty($notched_stock_ids)) {
                    $notched_select = $this->Create_Stock_Select($vars, self::$pgBandColor, $notched_stock_ids, '10', 'Select Dual Layer - Edge Color Set...', false, true, true);
                    /*
			if($builder['inhouse'])
				$multicolored_select = $this->Create_Stock_Select($vars, $selectedColor, $swirl_stock_ids, '6', 'Select Swirl Band Color...');
			*/
                    $notchedlabel = 'Edge';
                    $colorTypes['9'] = array('id' => '9', 'label' => $notchedlabel, 'name' => 'notched', 'attr' => '');
                    $colorSelects['9'] = $notched_select;
                }

            }

            //tpt_dump($colorTypes);
            //tpt_dump($colorTypes[$sColorType], true);
            if (empty($sColorType) || !isset($colorTypes[$sColorType])) {
                $rs = reset($colorTypes);
                $checkedRadio = $rs['id'];
                $sColorType = key($colorTypes);
            }


            $colorRadios = array();

            // used in the admin version of the function
            $vars['misc_data_store'] = array();
            $vars['misc_data_store']['colorTypes'] = $colorTypes;
            $vars['misc_data_store']['colorSelects'] = $colorSelects;

//        if ($_SERVER['REMOTE_ADDR']=='109.160.0.218') {
//			var_dump("TEEEEEEEIIIILLLLLEEEEEEEE",$colorSelects);
//		}

            foreach ($colorTypes as $key => $ct) {
                $activecls = '';
                if ($ct['id'] == $checkedRadio) {
                    $activecls = 'active';
                }
                $label = '<label class="amz_brown font-size-14 font-weight-bold" style="text-shadow: 1px 1px rgba(32, 32, 32, 0.4); font-family: Arial, Helvetica, sans-serif;" for="' . $ct['name'] . '_colors">' . $ct['label'] . '</label> ';
                $radio = tpt_html::createRadiobutton($vars, 'color_type'/*name*/, $ct['id']/*control value*/, $checkedRadio/*checked value*/, ' onclick="var chld = getChildElements(this.parentNode.parentNode.parentNode);for(var i=0, _len=chld.length; i<_len; i++){removeClass(chld[i], \'active\');}addClass(this.parentNode.parentNode, \'active\');' . $ajax_call . '" id="' . $ct['name'] . '_colors"'/*html attribs*/, ''/*oncheck*/);
                $control = <<< EOT
<div class="padding-top-2 padding-right-2 padding-bottom-2 padding-left-2 display-inline-block colortype-radio $activecls" style="">
<div class="white-space-nowrap display-inline-block color-white padding-left-4 padding-right-4 padding-top-2 padding-bottom-2" style="border-radius: 20px;">
$label$radio
</div>
</div>
EOT;
                $colorRadios[$key] = $control;
            }


            //$solid_radio = tpt_html::createRadiobutton($vars, 'color_type'/*name*/, '1'/*control value*/, '1'/*checked value*/, ' onclick="'.$ajax_call.'" id="solid_colors"'/*html attribs*/, ''/*oncheck*/);
            //$swirl_radio = tpt_html::createRadiobutton($vars, 'color_type'/*name*/, '2'/*control value*/, $sColorType/*checked value*/, ' onclick="'.$ajax_call.'" id="swirl_colors"'/*html attribs*/, ''/*oncheck*/);
            //$segmented_radio = tpt_html::createRadiobutton($vars, 'color_type'/*name*/, '3'/*control value*/, $sColorType/*checked value*/, ' onclick="'.$ajax_call.'" id="segmented_colors"'/*html attribs*/, ''/*oncheck*/);
            //$ds_attr = '';
            //if($builder['style'] == 7) {
            //    $ds_attr = ' disabled="disabled"';
            //}
            //$dual_radio = tpt_html::createRadiobutton($vars, 'color_type'/*name*/, '4'/*control value*/, $sColorType/*checked value*/, ' onclick="'.$ajax_call.'" id="dual_colors" '.$ds_attr/*html attribs*/, ''/*oncheck*/);


            $radios = implode("\n", $colorRadios);

            $intermsg = '';/*
		if(!$builder['inhouse'] && ($style == 1) && isset(getModule($vars, "BandData")->typeStyle[$type]['6']) && (getModule($vars, "BandData")->typeStyle[$type]['6']['minimum_quantity'] == 1) && empty($types_module->moduleData['id'][$dType]['writable'])) {
			$intermsg = '<span class="amz_red font-size-12">*No minimum order colors</span>';
		}
		*/
            $selectContent = $colorSelects[$sColorType];

            //if(self::$pgBandColor === false) {
            //    self::$pgBandColor = $selectedColor;
            //}
            //tpt_dump($pgconf);
            //tpt_dump($selectedColor);
            //tpt_dump(self::$pgBandColor, true);

//        $html = <<< EOT
//        <input type="hidden" name="band_color" id="tpt_pg_bandcolor" value="$pgBandColor" />
//EOT;

            $html = <<< EOT
<div style="text-align: left !important;" class="band_color_radios">
$radios
</div>
$intermsg
<div id="color_type_container" style="text-align: left !important;">
$selectContent
</div>
EOT;

            if (empty($builder['inhouse']) && ($style != 7)) {
                $custom_options_class = 'solid_colors';
                if ($checkedRadio == 2) {
                    $custom_options_class = 'swirl_colors';
                } else if ($checkedRadio == 3) {
                    $custom_options_class = 'segmented_colors';
                }

                $glowcheck = !empty($sColorProps['glow']) ? ' checked="checked"' : '';
                $glowdisabled = (empty($sColorProps['custom_color']) && !empty($sColorProps['glow'])) ? ' disabled="disabled"' : '';
                $glittercheck = !empty($sColorProps['glitter']) ? ' checked="checked"' : '';
                $glitterdisabled = (empty($sColorProps['custom_color']) && !empty($sColorProps['glitter'])) ? ' disabled="disabled"' : '';

                $customcolor = '<div class="custom_band_color_indicator"></div><div class="clear"></div>';
                if (!empty($sColorProps['custom_color'])) {
                    $customcolor = $this->getCustomColorPreview_SB($vars, $pgBandColor);

                    $customcolor = preg_replace('#Custom HexVal [a-z]+:#imsU', '', $customcolor);
                    //			if ($_SERVER["REMOTE_ADDR"] == '89.253.189.155') {
                    //	var_dump(htmlentities(preg_replace('#Custom HexVal [a-z]+:#imsU','',$customcolor)));
                    //			}
                }

//	<a class="thickbox create_custom_color_band plain-link" href="javascript:/*TB_inline?width=900&amp;height=500&amp;inlineId=_*/"><span class="choose_or_change_band_col">Create</span> Custom Color</a>
                $html .= <<< EOT
<div class="ccc_wr $custom_options_class ">
	<a class="thickbox TBinline_900_500 create_custom_color_band plain-link" href="javascript:;"><span class="choose_or_change_band_col">Create</span> Custom Color</a>
</div>

$customcolor

<div class="clr-extra padding-top-10 $custom_options_class " id="color_addons_wrapper">
	<input $glowcheck $glowdisabled onclick="addons_change(this);" class="color_extra" value="1" type="checkbox" id="pg_addon1" name="create_glow" />
	<label class="" for="pg_addon1">Add Glow</label>
	/
	<input $glittercheck $glitterdisabled onclick="addons_change(this);" class="color_extra" value="2" type="checkbox" id="pg_addon2" name="create_glitter" />
	<label for="pg_addon2">Add Glitter</label>

EOT;
                /* disable UV temporarily
	/
	<input onclick="addons_change(this);" class="color_extra" value="3" type="checkbox" id="pg_addon3" name="create_uv" />
	<label for="pg_addon3">Add UV effect</label>
*/
                $glcls = 'display-none';
                if (!empty($glowcheck)) {
                    $glcls = '';
                }
                $html .= <<< EOT
		<div id="addon_glow_controls" class="$glcls">
			<span class="amz_red" style="background-color: #ffff33;">**Dark Colors Will Not Glow**</span>
			<br />
			<a href="#" onclick="see_green_glow(); return false;">See Green Glow</a>&nbsp;
			<a href="#" onclick="see_blue_glow(); return false;">See Blue Glow</a>&nbsp;
			<a href="#" onclick="hide_glow(); return false;">Hide Glow</a>&nbsp;
		</div>
</div>
EOT;
            }

            if (!empty($dTypeArr['invert_dual_control'])) {
                $html .= <<< EOT
		<br />
		<input type="checkbox" name="invert_dual" id="invert_dual_id" value="1" onclick="_short_tpt_pg_change_band_fill();" />&nbsp;Invert Message
EOT;


                /*
		$html .= <<< EOT
		<br />
		<input type="checkbox" name="invert_dual" id="invert_dual_id" value="1" onclick="if(this.checked){document.getElementById('tpt_pg_style').value='17';goGetSome('bandtype.change_band_type_sb', this.form);}else{document.getElementById('tpt_pg_style').value='7';goGetSome('bandtype.change_band_type_sb', this.form);}" />&nbsp;Invert Message
EOT;
		*/
            } else if (!empty($dTypeArr['cut_away_control'])) {
                $cut_away_checked = '';
                $cut_away_checked = ($style == 8) ? ' checked="checked"' : '';
                /*
		$html .= <<< EOT
		<br />
		<input type="checkbox" name="cut_away" id="cut_away_id" value="1" onclick="_short_tpt_pg_change_band_fill();" />&nbsp;Cut-Away Style Message
		EOT;
		*/
                $html .= <<< EOT
		<br />
		<input $cut_away_checked type="checkbox" name="cut_away" id="cut_away_id" value="1" onclick="if(this.checked){goGetSome('bandtype.change_band_type_sb', this.form);}else{document.getElementById('tpt_pg_style').value='6';goGetSome('bandtype.change_band_type_sb', this.form);}" />&nbsp;Cut-Away Style Message
EOT;
            } else if (false && !empty($dTypeArr['invert_screenprint_control'])) {
                //tpt_dump($style, true);
                $invert_screenprint_checked = '';
                $invert_screenprint_checked = ($style == 16) ? ' checked="checked"' : '';
                /*
		$html .= <<< EOT
		<br />
		<input type="checkbox" name="cut_away" id="cut_away_id" value="1" onclick="_short_tpt_pg_change_band_fill();" />&nbsp;Cut-Away Style Message
		EOT;
		*/
                $html .= <<< EOT
		<br />
		<input $invert_screenprint_checked type="checkbox" name="invert_screenprint" id="invert_screenprint_id" value="1" onclick="if(this.checked){document.getElementById('tpt_pg_style').value='16';goGetSome('bandtype.change_band_type_sb', this.form);}else{document.getElementById('tpt_pg_style').value='5';goGetSome('bandtype.change_band_type_sb', this.form);}" />&nbsp;Invert Message
EOT;
            }


            self::$bandColorContent = $html;
        }

        return array('content' => self::$bandColorContent, 'pgBandColor' => self::$pgBandColor);
    }


    function BandColor_ColorType(&$vars, $selectedColor, $type, $style, $builder)
    {

        $cp = $this->getColorProps($vars, $selectedColor);
        //tpt_dump($cp, true);

        /*
					$colorSelects['1'] = $solid_select;
					$colorSelects['2'] = $swirl_select;
					$colorSelects['3'] = $segmented_select;
					$colorSelects['5'] = $multicolored_select;
					$colorSelects['6'] = $glitter_select;
					$colorSelects['7'] = $glow_select;
				$colorSelects['5'] = $multicolored_select;
				$colorSelects['6'] = $glitter_select;
				$colorSelects['7'] = $glow_select;
			$colorSelects['1'] = $duallayer_select;
			$colorSelects['5'] = $multicolored_select;
			$colorSelects['6'] = $glitter_select;
			$colorSelects['7'] = $glow_select;
		*/
        $colorType = 1;

        if ($style != 7) {
            if (($type != 5) && empty($builder['inhouse'])) {
                //die('asdasdsa');
                //var_dump($builder);die();
                if ($cp['glitter']) {
                    $colorType = 6;
                } else if ($cp['glow']) {
                    $colorType = 7;
                } else if ($cp['swirl']) {
                    $colorType = 2;
                } else if ($cp['segmented']) {
                    $colorType = 3;
                } else {
                    $colorType = 1;
                }
            } else {
                //die('asdasdsa');
                if ($cp['glitter']) {
                    $colorType = 6;
                } else if ($cp['glow']) {
                    $colorType = 7;
                } else if ($cp['swirl'] || $cp['segmented']) {
                    $colorType = 5;
                    //die();
                } else {
                    $colorType = 1;
                }
            }
        } else {
            $mid = $this->getDualLayerMessageId($vars, $selectedColor);
            //var_dump($mid);die();

            $cp = $this->getColorProps($vars, $mid);
            //tpt_dump($selectedColor, true);
            //var_dump($mid);die();
            if ($cp['glitter']) {
                $colorType = 6;
            } else if ($cp['glow']) {
                $colorType = 7;
            } else if ($cp['swirl'] || $cp['segmented']) {
                $colorType = 5;
            } else {
                $colorType = 1;
            }
        }


        //var_dump($colorType);die();
        return $colorType;
    }


    /*
	//////////////////////////////////////////////////////////////////////////////
	function BandColor_ColorType(&$vars, $selectedColor, $type, $style, $builder) {
		$sColorProps = $this->getColorProps($vars, $selectedColor);


		//$checkedRadio = $sColorType;

		//var_dump($style);die();
		$colorTypes = array();
		$colorSelects = array();

			$query = <<< EOT
SELECT *, 1 as `sctype` FROM (

	(
	SELECT *,
	1 AS `stock`,
	REPLACE(`label`, " (", "b (") AS `ordlabel`
	 FROM `tpt_color_special` WHERE
	`enabled`=1 AND
	`color_type`=3 AND
	`glitter`=0 AND
	`glow`=0 AND
	FIND_IN_SET('$type', `available_types_ids`) AND NOT
	`label` REGEXP '(True)'
	)

UNION

	(
	SELECT *,
	1 AS `stock`,
	REPLACE(`label`, " (", "a (") AS `ordlabel`
	 FROM `tpt_color_special` WHERE
	`enabled`=1 AND
	`color_type`=3 AND
	`glitter`=0 AND
	`glow`=0 AND
	FIND_IN_SET('$type', `available_types_ids`) AND
	`label` REGEXP '(True)'
	)

UNION

	(
	SELECT
	`id`,
	`label`,
	NULL AS `color_type`,
	`color_id`,
	NULL AS `color_type`,
	NULL AS `message_color_id`,
	NULL AS `glow`,
	NULL AS `glitter`,
	NULL AS `uv`,
	NULL AS `1_2`,
	NULL AS `1_4`,
	NULL AS `3_4`,
	NULL AS `1`,
	NULL AS `slap`,
	NULL AS `snap`,
	NULL AS `keychain`,
	NULL AS `ring`,
	NULL AS `available_types_ids`,
	NULL AS `stock`,
	REPLACE(`label`, " (", "b (") AS `ordlabel`
	FROM `tpt_color_solid` WHERE `enabled`=1 AND
	`label` NOT IN
		(
		SELECT `label`
		FROM `tpt_color_special` WHERE
		`enabled`=1 AND
		`color_type`=3 AND
		`glitter`=0 AND
		`glow`=0 AND
		FIND_IN_SET('$type', `available_types_ids`)
		) AND NOT
	`label` REGEXP '(True)'
	)

UNION

	(
	SELECT
	`id`,
	`label`,
	NULL AS `color_type`,
	`color_id`,
	NULL AS `color_type`,
	NULL AS `message_color_id`,
	NULL AS `glow`,
	NULL AS `glitter`,
	NULL AS `uv`,
	NULL AS `1_2`,
	NULL AS `1_4`,
	NULL AS `3_4`,
	NULL AS `1`,
	NULL AS `slap`,
	NULL AS `snap`,
	NULL AS `keychain`,
	NULL AS `ring`,
	NULL AS `available_types_ids`,
	NULL AS `stock`,
	REPLACE(`label`, " (", "a (") AS `ordlabel`
	FROM `tpt_color_solid` WHERE `enabled`=1 AND
	`label` NOT IN
		(
		SELECT `label`
		FROM `tpt_color_special` WHERE
		`enabled`=1 AND
		`color_type`=3 AND
		`glitter`=0 AND
		`glow`=0 AND
		FIND_IN_SET('$type', `available_types_ids`)
		) AND
	`label` REGEXP '(True)'
	)
ORDER BY `ordlabel` ASC) AS `a` GROUP BY `ordlabel`



						UNION



SELECT *, 2 as `sctype` FROM (

	(
	SELECT *,
	1 AS `stock`
	 FROM `tpt_color_special` WHERE
	`enabled`=1 AND
	`color_type`=4 AND
	FIND_IN_SET('$type', `available_types_ids`)
	)

UNION

	(
	SELECT
	`id`,
	`label`,
	NULL AS `color_type`,
	`color_id`,
	NULL AS `color_type`,
	NULL AS `message_color_id`,
	NULL AS `glow`,
	NULL AS `glitter`,
	NULL AS `uv`,
	NULL AS `1_2`,
	NULL AS `1_4`,
	NULL AS `3_4`,
	NULL AS `1`,
	NULL AS `slap`,
	NULL AS `snap`,
	NULL AS `keychain`,
	NULL AS `ring`,
	NULL AS `available_types_ids`,
	NULL AS `stock`
	FROM `tpt_color_swirl` WHERE `enabled`=1 AND
	`label` NOT IN
		(
		SELECT `label`
		FROM `tpt_color_special` WHERE
		`enabled`=1 AND
		`color_type`=4 AND
		FIND_IN_SET('$type', `available_types_ids`)
		)
	)
ORDER BY `label` ASC) AS `a` GROUP BY `label`




					UNION





SELECT *, 3 as `sctype` FROM (

	(
	SELECT *,
	1 AS `stock`
	 FROM `tpt_color_special` WHERE
	`enabled`=1 AND
	`color_type`=5 AND
	FIND_IN_SET('$type', `available_types_ids`)
	)

UNION

	(
	SELECT
	`id`,
	`label`,
	NULL AS `color_type`,
	`color_id`,
	NULL AS `color_type`,
	NULL AS `message_color_id`,
	NULL AS `glow`,
	NULL AS `glitter`,
	NULL AS `uv`,
	NULL AS `1_2`,
	NULL AS `1_4`,
	NULL AS `3_4`,
	NULL AS `1`,
	NULL AS `slap`,
	NULL AS `snap`,
	NULL AS `keychain`,
	NULL AS `ring`,
	NULL AS `available_types_ids`,
	NULL AS `stock`
	FROM `tpt_color_segment` WHERE `enabled`=1 AND
	`label` NOT IN
		(
		SELECT `label`
		FROM `tpt_color_special` WHERE
		`enabled`=1 AND
		`color_type`=5 AND
		FIND_IN_SET('$type', `available_types_ids`)
		)
	)
ORDER BY `label` ASC) AS `a` GROUP BY `label`




						UNION



SELECT *, 5 as `sctype` FROM (
	(
	SELECT *,
	1 AS `stock`
	 FROM `tpt_color_special` WHERE
	`enabled`=1 AND
	(`color_type`=4 OR `color_type`=5) AND
	FIND_IN_SET("$type", `available_types_ids`)
	)
ORDER BY `label` ASC) AS `a` GROUP BY `label`




						UNION




SELECT *, 6 as `sctype` FROM (
	(
	SELECT *,
	1 AS `stock`
	 FROM `tpt_color_special` WHERE
	`enabled`=1 AND
	`glitter`=1 AND
	FIND_IN_SET("$type", `available_types_ids`)
	)
ORDER BY `label` ASC) AS `a` GROUP BY `label`




						UNION




SELECT *, 7 as `sctype` FROM (
	(
	SELECT *,
	1 AS `stock`
	 FROM `tpt_color_special` WHERE
	`enabled`=1 AND
	`glitter`=1 AND
	FIND_IN_SET("$type", `available_types_ids`)
	)
ORDER BY `label` ASC) AS `a` GROUP BY `label`




						UNION




SELECT *, 5 as `sctype` FROM (
	(
	SELECT *,
	1 AS `stock`
	 FROM `tpt_color_special` WHERE
	`enabled`=1 AND
	(`color_type`=4 OR `color_type`=5) AND
	`glitter`=0 AND
	`glow`=0 AND
	FIND_IN_SET("$type", `available_types_ids`)
	)
ORDER BY `label` ASC) AS `a` GROUP BY `label`




						UNION




SELECT *, 6 as `sctype` FROM (
	(
	SELECT *,
	1 AS `stock`
	 FROM `tpt_color_special` WHERE
	`enabled`=1 AND
	`glitter`=1 AND
	FIND_IN_SET("$type", `available_types_ids`)
	)
ORDER BY `label` ASC) AS `a` GROUP BY `label`




						UNION




SELECT *, 7 as `sctype` FROM (
	(
	SELECT *,
	1 AS `stock`
	 FROM `tpt_color_special` WHERE
	`enabled`=1 AND
	`glow`=1 AND `glitter`=0 AND
	FIND_IN_SET("$type", `available_types_ids`)
	)
ORDER BY `label` ASC) AS `a` GROUP BY `label`

			$colorTypes = array();
			$query = 'SELECT dl.`id`, dl.`label`, dl.`message_color_id`, dl.`available_types_ids`, sp.`id` AS msgid, sp.`label` AS msglabel, sp.`color_type`, sp.`glow`, sp.`glitter`, sp.`uv` FROM `tpt_color_duallayer` AS dl LEFT JOIN `tpt_color_special` AS sp ON dl.`message_color_id`=sp.`id` WHERE dl.`enabled`=1 AND FIND_IN_SET(\''.$type.'\', dl.`available_types_ids`) AND sp.`color_type`=3 AND sp.`glow`=0 AND sp.`glitter`=0 AND sp.`uv`=0 ORDER BY `label` ASC';
			$vars['db']['handler']->query($query, __FILE__);
			$solid_stock_ids = $vars['db']['handler']->fetch_assoc_list('id', false);
			//var_dump($query);die();
			//$duallayer_stock_ids = $vars['db']['handler']->getData($vars, 'tpt_color_duallayer', '*', '`enabled`=1 AND FIND_IN_SET(\''.$type.'\', `available_types_ids`) ORDER BY  `label` ASC ', 'id', false);
			$duallayer_select = $this->Create_Stock_Select($vars, $selectedColor, $solid_stock_ids, '10', 'Select Dual Layer Solids Color Set...', false, true, true);
			$colorTypes['1'] = array('id'=>'1', 'label'=>'Solids', 'name'=>'solid', 'attr'=>' disabled="disabled"');
			$colorSelects['1'] = $duallayer_select;
			//$checkedRadio = '4';


			$query = 'SELECT dl.`id`, dl.`label`, dl.`message_color_id`, dl.`available_types_ids`, sp.`id` AS msgid, sp.`label` AS msglabel, sp.`color_type`, sp.`glow`, sp.`glitter`, sp.`uv` FROM `tpt_color_duallayer` AS dl LEFT JOIN `tpt_color_special` AS sp ON dl.`message_color_id`=sp.`id` WHERE dl.`enabled`=1 AND FIND_IN_SET(\''.$type.'\', dl.`available_types_ids`) AND (sp.`color_type`=4 OR sp.`color_type`=5) AND sp.`glow`=0 AND sp.`glitter`=0 AND sp.`uv`=0 ORDER BY `label` ASC';
			$vars['db']['handler']->query($query, __FILE__);
			$multicolored_stock_ids = $vars['db']['handler']->fetch_assoc_list('id', false);
			if(!empty($multicolored_stock_ids)) {
			//$multicolored_stock_ids = $vars['db']['handler']->getData($vars, 'tpt_color_duallayer', '*', '`enabled`=1 AND (`color_type`=4 OR `color_type`=5) AND `glitter`=0 AND `glow`=0 AND FIND_IN_SET(\''.$type.'\', `available_types_ids`) ORDER BY  `label` ASC ', 'id', false);
			$multicolored_select = $this->Create_Stock_Select($vars, $selectedColor, $multicolored_stock_ids, '10', 'Select Swirl/Segmented Band Color...', false, true, true);
			$multilabel = 'Multi-Colored Msg';
			if($type == 5) {
				$multilabel = 'Multi-Colored Band';
			}
			$colorTypes['5'] = array('id'=>'5', 'label'=>$multilabel, 'name'=>'multic', 'attr'=>'');
			$colorSelects['5'] = $multicolored_select;
			}

			$query = 'SELECT dl.`id`, dl.`label`, dl.`message_color_id`, dl.`available_types_ids`, sp.`id` AS msgid, sp.`label` AS msglabel, sp.`color_type`, sp.`glow`, sp.`glitter`, sp.`uv` FROM `tpt_color_duallayer` AS dl LEFT JOIN `tpt_color_special` AS sp ON dl.`message_color_id`=sp.`id` WHERE dl.`enabled`=1 AND FIND_IN_SET(\''.$type.'\', dl.`available_types_ids`) AND sp.`color_type`=3 AND sp.`glitter`=1 ORDER BY `label` ASC';
			$vars['db']['handler']->query($query, __FILE__);
			$glitter_stock_ids = $vars['db']['handler']->fetch_assoc_list('id', false);
			if(!empty($glitter_stock_ids)) {
			$glitter_select =  $this->Create_Stock_Select($vars, $selectedColor, $glitter_stock_ids, '10', 'Select Band Color (/w Glitter)...', false, false, true);
			$colorTypes['6'] = array('id'=>'6', 'label'=>'Glitter Msg', 'name'=>'glitter', 'attr'=>'');
			$colorSelects['6'] = $glitter_select;
			}


			$query = 'SELECT dl.`id`, dl.`label`, dl.`message_color_id`, dl.`available_types_ids`, sp.`id` AS msgid, sp.`label` AS msglabel, sp.`color_type`, sp.`glow`, sp.`glitter`, sp.`uv` FROM `tpt_color_duallayer` AS dl LEFT JOIN `tpt_color_special` AS sp ON dl.`message_color_id`=sp.`id` WHERE dl.`enabled`=1 AND FIND_IN_SET(\''.$type.'\', dl.`available_types_ids`) AND sp.`color_type`=3 AND sp.`glitter`=0 AND sp.`glow`=1 ORDER BY `label` ASC';
			$vars['db']['handler']->query($query, __FILE__);
			$glow_stock_ids = $vars['db']['handler']->fetch_assoc_list('id', false);
			if(!empty($glow_stock_ids)) {
			$glow_select =  $this->Create_Stock_Select($vars, $selectedColor, $glow_stock_ids, '10', 'Select Dual Layer Color Set (/w Glow Message)...', false, true, true);
			$colorTypes['7'] = array('id'=>'7', 'label'=>'Glow Msg', 'name'=>'glow', 'attr'=>'');
			$colorSelects['7'] = $glow_select;
			}
		}




		return array('content'=>self::$bandColorContent, 'pgBandColor'=>self::$pgBandColor);
	}
	*/


    function BandColor_Section_Admin_old(&$vars, $sColorType, $selectedColor, $type, $style, $builder)
    {
        $sColorProps = $this->getColorProps($vars, $selectedColor);
        if ((($style == 7) && (empty($sColorProps['dual_layer']) || !empty($sColorProps['custom_color']))) || (($style != 7) && !empty($sColorProps['dual_layer']))) {
            self::$pgBandColor = '-1:' . DEFAULT_BAND_COLOR;
        }

        if ((self::$bandColorContent === false) || (self::$pgBandColor === false)) {
            $html = '';

            $ajax_call = tpt_ajax::getCall('color.change_color_type');

            $checkedRadio = $sColorType;

            //var_dump($style);die();
            $colorTypes = array();
            $colorSelects = array();

            if ($style != 7) {
                //$solid_stock_ids = array();
                //if(($type == 5) || in_array($style, array(1, 6)) && isset(getModule($vars, "BandData")->typeStyle[$type]['6']) && (getModule($vars, "BandData")->typeStyle[$type]['6']['minimum_quantity'] == 1)) {
                //$solid_stock_ids = $vars['db']['handler']->getData($vars, 'tpt_color_special', '*', '`enabled`=1 AND `color_type`=3 AND `glitter`=0 AND `glow`=0 AND FIND_IN_SET(\''.$type.'\', `available_types_ids`) ORDER BY  `label` ASC ', 'id', false);
                //}

                //if($_SERVER['REMOTE_ADDR'] == '85.130.3.155') {
                //die();
                $query = <<< EOT
SELECT * FROM (

	(
	SELECT *,
	1 AS `stock`,
	REPLACE(`label`, " (", "b (") AS `ordlabel`
	 FROM `tpt_color_special` WHERE
	`enabled`=1 AND
	`color_type`=3 AND
	`glitter`=0 AND
	`glow`=0 AND
	FIND_IN_SET('$type', `available_types_ids`) AND NOT
	`label` REGEXP '(True)'
	)

UNION

	(
	SELECT *,
	1 AS `stock`,
	REPLACE(`label`, " (", "a (") AS `ordlabel`
	 FROM `tpt_color_special` WHERE
	`enabled`=1 AND
	`color_type`=3 AND
	`glitter`=0 AND
	`glow`=0 AND
	FIND_IN_SET('$type', `available_types_ids`) AND
	`label` REGEXP '(True)'
	)

UNION

	(
	SELECT
	`id`,
	`label`,
	`color_type`,
	`color_id`,
	NULL AS `color_type`,
	NULL AS `message_color_id`,
	NULL AS `glow`,
	NULL AS `glitter`,
	NULL AS `uv`,
	NULL AS `1_2`,
	NULL AS `1_4`,
	NULL AS `3_4`,
	NULL AS `1`,
	NULL AS `slap`,
	NULL AS `snap`,
	NULL AS `keychain`,
	NULL AS `ring`,
	NULL AS `available_types_ids`,
	NULL AS `stock`,
	REPLACE(`label`, " (", "b (") AS `ordlabel`
	FROM `tpt_color_overseas` WHERE `color_type`=3 AND `enabled`=1 AND
	`label` NOT IN
		(
		SELECT `label`
		FROM `tpt_color_special` WHERE
		`enabled`=1 AND
		`color_type`=3 AND
		`glitter`=0 AND
		`glow`=0 AND
		FIND_IN_SET('$type', `available_types_ids`)
		) AND NOT
	`label` REGEXP '(True)'
	)

UNION

	(
	SELECT
	`id`,
	`label`,
	`color_type`,
	`color_id`,
	NULL AS `color_type`,
	NULL AS `message_color_id`,
	NULL AS `glow`,
	NULL AS `glitter`,
	NULL AS `uv`,
	NULL AS `powdercoat`,
	NULL AS `1_2`,
	NULL AS `1_4`,
	NULL AS `3_4`,
	NULL AS `1`,
	NULL AS `slap`,
	NULL AS `snap`,
	NULL AS `keychain`,
	NULL AS `ring`,
	NULL AS `available_types_ids`,
	NULL AS `stock`,
	REPLACE(`label`, " (", "a (") AS `ordlabel`
	FROM `tpt_color_overseas` WHERE `color_type`=3 AND `enabled`=1 AND
	`label` NOT IN
		(
		SELECT `label`
		FROM `tpt_color_special` WHERE
		`enabled`=1 AND
		`color_type`=3 AND
		`glitter`=0 AND
		`glow`=0 AND
		FIND_IN_SET('$type', `available_types_ids`)
		) AND
	`label` REGEXP '(True)'
	)
ORDER BY `ordlabel` ASC) AS `a` GROUP BY `ordlabel`
EOT;
//var_dump($query);die();
                //$vars['db']['handler']->query($query, __FILE__);
                //$solid_stock_ids = $vars['db']['handler']->fetch_assoc_list();
                $solid_stock_ids = array();

                //var_dump($solid_stock_ids);
                //die();
                //}

                //$solid_stock_labels = $vars['db']['handler']->getData($vars, 'tpt_color_special', '*', '`enabled`=1 AND `color_type`=3 AND FIND_IN_SET(\''.$type.'\', `available_types_ids`) ORDER BY  `label` ASC ', 'label', false);
                //$solid_suggested_ids = $this->solid;
                //$solid_suggested_labels = $vars['db']['handler']->getData($vars, 'tpt_color_solid', '*', '`enabled`=1 ORDER BY  `label` ASC ', 'label', false);
                //$solid_select = $this->Create_Combined_Solids_Select($vars, $selectedColor, $solid_stock_ids, $solid_suggested_ids, $solid_suggested_labels, '3');
                //if($_SERVER['REMOTE_ADDR'] == '85.130.3.155') {
                //die();
                $solid_select = $this->Create_Combined_Solids_Select2($vars, $selectedColor, $solid_stock_ids, '3', true, false);
                //}
                if ($builder['inhouse'] || ($type == 5)) {
                    //$solid_select = $this->Create_Stock_Solids_Select($vars, $selectedColor, $solid_stock_ids);

                    //if($_SERVER['REMOTE_ADDR'] == '85.130.3.155') {
                    //die();
                    $solid_select = $this->Create_Combined_Solids_Select2($vars, $selectedColor, $solid_stock_ids, '3', false, true);
                    //}
                }
                $colorTypes['1'] = array('id' => '1', 'label' => 'Solid', 'name' => 'solid', 'attr' => '');
                $colorSelects['1'] = $solid_select;

                //tpt_dump($type);
                //tpt_dump($builder['inhouse'],true);
                if (($type != 5)) {
                    if (!$builder['inhouse']) {
                        //$swirl_stock_ids = array();
                        //if((in_array($style, array(1, 6)) && isset(getModule($vars, "BandData")->typeStyle[$type]['6']) && getModule($vars, "BandData")->typeStyle[$type]['6']['minimum_quantity'] == 1)) {
                        //$swirl_stock_ids = $vars['db']['handler']->getData($vars, 'tpt_color_special', '*', '`enabled`=1 AND `color_type`=4 AND `glitter`=0 AND `glow`=0 AND FIND_IN_SET(\''.$type.'\', `available_types_ids`) ORDER BY  `label` ASC ', 'id', false);
                        //}
                        $query = <<< EOT
SELECT * FROM (

	(
	SELECT *,
	1 AS `stock`
	 FROM `tpt_color_special` WHERE
	`enabled`=1 AND
	`color_type`=4 AND
	FIND_IN_SET('$type', `available_types_ids`)
	)

UNION

	(
	SELECT
	`id`,
	`label`,
	`color_type`,
	`color_id`,
	NULL AS `message_color_id`,
	NULL AS `glow`,
	NULL AS `glitter`,
	NULL AS `uv`,
	NULL AS `powdercoat`,
	NULL AS `1_2`,
	NULL AS `1_4`,
	NULL AS `3_4`,
	NULL AS `1`,
	NULL AS `slap`,
	NULL AS `snap`,
	NULL AS `keychain`,
	NULL AS `ring`,
	NULL AS `available_types_ids`,
	NULL AS `stock`
	FROM `tpt_color_swirl` WHERE `color_type`=4 AND `enabled`=1 AND
	`label` NOT IN
		(
		SELECT `label`
		FROM `tpt_color_special` WHERE
		`enabled`=1 AND
		`color_type`=4 AND
		FIND_IN_SET('$type', `available_types_ids`)
		)
	)
ORDER BY `label` ASC) AS `a` GROUP BY `label`
EOT;
                        //$vars['db']['handler']->query($query, __FILE__);
                        //$swirl_stock_ids = $vars['db']['handler']->fetch_assoc_list();
                        $swirl_stock_ids = array();
                        //var_dump($swirl_stock_ids);die();
                        //$swirl_suggested_ids = $this->swirl;
                        //$swirl_suggested_labels = $vars['db']['handler']->getData($vars, 'tpt_color_swirl', '*', '`enabled`=1 ORDER BY  `label` ASC ', 'label', false);
                        //$swirl_select = $this->Create_Combined_Select($vars, $selectedColor, $swirl_stock_ids, $swirl_suggested_ids, $swirl_suggested_labels, '4', 'Select Swirl Band Color...');
                        $swirl_select = $this->Create_Combined_Select2($vars, $selectedColor, $swirl_stock_ids, '4', ($style == 1), false, 'Select Swirl Band Color...');
                        //if($builder['inhouse'])
                        //    $swirl_select = $this->Create_Combined_Select2($vars, $selectedColor, $swirl_stock_ids, '4', false, true, 'Select Swirl Band Color...');
                        $colorTypes['2'] = array('id' => '2', 'label' => 'Swirl', 'name' => 'swirl', 'attr' => '');
                        $colorSelects['2'] = $swirl_select;

                        //$segmented_stock_ids = array();
                        $query = <<< EOT
SELECT * FROM (

	(
	SELECT *,
	1 AS `stock`
	 FROM `tpt_color_special` WHERE
	`enabled`=1 AND
	`color_type`=5 AND
	FIND_IN_SET('$type', `available_types_ids`)
	)

UNION

	(
	SELECT
	`id`,
	`label`,
	`color_type`,
	`color_id`,
	NULL AS `message_color_id`,
	NULL AS `glow`,
	NULL AS `glitter`,
	NULL AS `uv`,
	NULL AS `powdercoat`,
	NULL AS `1_2`,
	NULL AS `1_4`,
	NULL AS `3_4`,
	NULL AS `1`,
	NULL AS `slap`,
	NULL AS `snap`,
	NULL AS `keychain`,
	NULL AS `ring`,
	NULL AS `available_types_ids`,
	NULL AS `stock`
	FROM `tpt_color_overseas` WHERE `color_type`=5 AND `enabled`=1 AND
	`label` NOT IN
		(
		SELECT `label`
		FROM `tpt_color_special` WHERE
		`enabled`=1 AND
		`color_type`=5 AND
		FIND_IN_SET('$type', `available_types_ids`)
		)
	)
ORDER BY `label` ASC) AS `a` GROUP BY `label`
EOT;
                        //$vars['db']['handler']->query($query, __FILE__);
                        //$segmented_stock_ids = $vars['db']['handler']->fetch_assoc_list();
                        $segmented_stock_ids = array();
                        //if((in_array($style, array(1, 6)) && isset(getModule($vars, "BandData")->typeStyle[$type]['6']) && getModule($vars, "BandData")->typeStyle[$type]['6']['minimum_quantity'] == 1)) {
                        //$segmented_stock_ids = $vars['db']['handler']->getData($vars, 'tpt_color_special', '*', '`enabled`=1 AND `color_type`=5 AND `glitter`=0 AND `glow`=0 AND FIND_IN_SET(\''.$type.'\', `available_types_ids`) ORDER BY  `label` ASC ', 'id', false);
                        //}
                        //var_dump($segmented_stock_ids);die();
                        //$segmented_suggested_ids = $this->segment;
                        //$segmented_suggested_labels = $vars['db']['handler']->getData($vars, 'tpt_color_segmented', '*', '`enabled`=1 ORDER BY  `label` ASC ', 'label', false);
                        //$segmented_select = $this->Create_Combined_Select($vars, $selectedColor, $segmented_stock_ids, $segmented_suggested_ids, $segmented_suggested_labels, '5', 'Select Segmented Band Color...');
                        $segmented_select = $this->Create_Combined_Select2($vars, $selectedColor, $segmented_stock_ids, '5', ($style == 1), false, 'Select Segmented Band Color...');
                        //if($builder['inhouse'])
                        //    $segmented_select = $this->Create_Stock_Select($vars, $selectedColor, $segmented_stock_ids, '6', 'Select Segmented Band Color...');
                        $colorTypes['3'] = array('id' => '3', 'label' => 'Segmented', 'name' => 'segmented', 'attr' => '');
                        $colorSelects['3'] = $segmented_select;

                        $glitter_stock_ids = $vars['db']['handler']->getData($vars, 'tpt_color_overseas', '*', '`enabled`=1 AND `glitter`=1 ORDER BY  `label` ASC ', 'id', false);
                        $glitter_select = $this->Create_Stock_Select($vars, $selectedColor, $glitter_stock_ids, '6', 'Select Band Color (/w Glitter)...', (!$builder['inhouse'] && in_array($style, array(1, 6))));
                        /*
					if($builder['inhouse'])
						$multicolored_select = $this->Create_Stock_Select($vars, $selectedColor, $swirl_stock_ids, '6', 'Select Swirl Band Color...');
					*/
                        $colorTypes['6'] = array('id' => '6', 'label' => 'Glitter', 'name' => 'glitter', 'attr' => '');
                        $colorSelects['6'] = $glitter_select;


                        $glow_stock_ids = $vars['db']['handler']->getData($vars, 'tpt_color_overseas', '*', '`enabled`=1 AND `glow`=1 AND `glitter`=0 ORDER BY  `label` ASC ', 'id', false);
                        //tpt_dump($glow_stock_ids,true);
                        $glow_select = $this->Create_Stock_Select($vars, $selectedColor, $glow_stock_ids, '6', 'Select Band Color (/w Glow in the Dark)...', (!$builder['inhouse'] && in_array($style, array(1, 6))));
                        /*
					if($builder['inhouse'])
						$multicolored_select = $this->Create_Stock_Select($vars, $selectedColor, $swirl_stock_ids, '6', 'Select Swirl Band Color...');
					*/
                        $colorTypes['7'] = array('id' => '7', 'label' => 'Glow', 'name' => 'glow', 'attr' => '');
                        $colorSelects['7'] = $glow_select;
                    } else {
                        $multicolored_stock_ids = $vars['db']['handler']->getData($vars, 'tpt_color_special', '*', '`enabled`=1 AND (`color_type`=4 OR `color_type`=5) AND ' ./*`glitter`=0 AND `glow`=0 AND*/
                            'FIND_IN_SET(\'' . $type . '\', `available_types_ids`) ORDER BY  `label` ASC ', 'id', false);
                        if (!empty($multicolored_stock_ids)) {
                            $multicolored_select = $this->Create_Stock_Select($vars, $selectedColor, $multicolored_stock_ids, '6', 'Select Swirl/Segmented Band Color...', (!$builder['inhouse'] && in_array($style, array(1, 6))));
                            /*
					if($builder['inhouse'])
						$multicolored_select = $this->Create_Stock_Select($vars, $selectedColor, $swirl_stock_ids, '6', 'Select Swirl Band Color...');
					*/
                            $colorTypes['5'] = array('id' => '5', 'label' => 'Multicolored', 'name' => 'multic', 'attr' => '');
                            $colorSelects['5'] = $multicolored_select;
                        }


                        $glitter_stock_ids = $vars['db']['handler']->getData($vars, 'tpt_color_special', '*', '`enabled`=1 AND `glitter`=1 AND FIND_IN_SET(\'' . $type . '\', `available_types_ids`) ORDER BY  `label` ASC ', 'id', false);
                        if (!empty($glitter_stock_ids)) {
                            $glitter_select = $this->Create_Stock_Select($vars, $selectedColor, $glitter_stock_ids, '6', 'Select Band Color (/w Glitter)...', (!$builder['inhouse'] && in_array($style, array(1, 6))));
                            /*
					if($builder['inhouse'])
						$multicolored_select = $this->Create_Stock_Select($vars, $selectedColor, $swirl_stock_ids, '6', 'Select Swirl Band Color...');
					*/
                            $colorTypes['6'] = array('id' => '6', 'label' => 'Glitter', 'name' => 'glitter', 'attr' => '');
                            $colorSelects['6'] = $glitter_select;
                        }


                        $glow_stock_ids = $vars['db']['handler']->getData($vars, 'tpt_color_special', '*', '`enabled`=1 AND `glow`=1 AND `glitter`=0 AND FIND_IN_SET(\'' . $type . '\', `available_types_ids`) ORDER BY  `label` ASC ', 'id', false);
                        if (!empty($glow_stock_ids)) {
                            $glow_select = $this->Create_Stock_Select($vars, $selectedColor, $glow_stock_ids, '6', 'Select Band Color (/w Glow in the Dark)...', (!$builder['inhouse'] && in_array($style, array(1, 6))));
                            /*
					if($builder['inhouse'])
						$multicolored_select = $this->Create_Stock_Select($vars, $selectedColor, $swirl_stock_ids, '6', 'Select Swirl Band Color...');
					*/
                            $colorTypes['7'] = array('id' => '7', 'label' => 'Glow', 'name' => 'glow', 'attr' => '');
                            $colorSelects['7'] = $glow_select;
                        }
                    }
                } else {
                    $multicolored_stock_ids = $vars['db']['handler']->getData($vars, 'tpt_color_special', '*', '`enabled`=1 AND (`color_type`=4 OR `color_type`=5) AND `glitter`=0 AND `glow`=0 AND FIND_IN_SET(\'' . $type . '\', `available_types_ids`) ORDER BY  `label` ASC ', 'id', false);
                    $multicolored_select = $this->Create_Stock_Select($vars, $selectedColor, $multicolored_stock_ids, '6', 'Select Swirl/Segmented Band Color...', (!$builder['inhouse'] && in_array($style, array(1, 6))), true);
                    /*
				if($builder['inhouse'])
					$multicolored_select = $this->Create_Stock_Select($vars, $selectedColor, $swirl_stock_ids, '6', 'Select Swirl Band Color...');
				*/
                    $colorTypes['5'] = array('id' => '5', 'label' => 'Multicolored', 'name' => 'multic', 'attr' => '');
                    $colorSelects['5'] = $multicolored_select;

                    $glitter_stock_ids = $vars['db']['handler']->getData($vars, 'tpt_color_special', '*', '`enabled`=1 AND `glitter`=1 AND FIND_IN_SET(\'' . $type . '\', `available_types_ids`) ORDER BY  `label` ASC ', 'id', false);
                    $glitter_select = $this->Create_Stock_Select($vars, $selectedColor, $glitter_stock_ids, '6', 'Select Band Color (/w Glitter)...', (!$builder['inhouse'] && in_array($style, array(1, 6))));
                    /*
				if($builder['inhouse'])
					$multicolored_select = $this->Create_Stock_Select($vars, $selectedColor, $swirl_stock_ids, '6', 'Select Swirl Band Color...');
				*/
                    $colorTypes['6'] = array('id' => '6', 'label' => 'Glitter', 'name' => 'glitter', 'attr' => '');
                    $colorSelects['6'] = $glitter_select;


                    $glow_stock_ids = $vars['db']['handler']->getData($vars, 'tpt_color_special', '*', '`enabled`=1 AND `glow`=1 AND `glitter`=0 AND FIND_IN_SET(\'' . $type . '\', `available_types_ids`) ORDER BY  `label` ASC ', 'id', false);
                    $glow_select = $this->Create_Stock_Select($vars, $selectedColor, $glow_stock_ids, '6', 'Select Band Color (/w Glow in the Dark)...', (!$builder['inhouse'] && in_array($style, array(1, 6))));
                    /*
				if($builder['inhouse'])
					$multicolored_select = $this->Create_Stock_Select($vars, $selectedColor, $swirl_stock_ids, '6', 'Select Swirl Band Color...');
				*/
                    $colorTypes['7'] = array('id' => '7', 'label' => 'Glow', 'name' => 'glow', 'attr' => '');
                    $colorSelects['7'] = $glow_select;
                }
            } else {

                //$sColorType = '4';
                $colorTypes = array();
                $query = 'SELECT dl.`id`, dl.`label`, dl.`message_color_id`, dl.`available_types_ids`, sp.`id` AS msgid, sp.`label` AS msglabel, sp.`color_type`, sp.`glow`, sp.`glitter`, sp.`uv` FROM `tpt_color_duallayer` AS dl LEFT JOIN `tpt_color_special` AS sp ON dl.`message_color_id`=sp.`id` WHERE dl.`enabled`=1 AND FIND_IN_SET(\'' . $type . '\', dl.`available_types_ids`) AND sp.`color_type`=3 AND sp.`glow`=0 AND sp.`glitter`=0 AND sp.`uv`=0 ORDER BY `label` ASC';
                $vars['db']['handler']->query($query, __FILE__);
                $solid_stock_ids = $vars['db']['handler']->fetch_assoc_list('id', false);
                //var_dump($query);die();
                //$duallayer_stock_ids = $vars['db']['handler']->getData($vars, 'tpt_color_duallayer', '*', '`enabled`=1 AND FIND_IN_SET(\''.$type.'\', `available_types_ids`) ORDER BY  `label` ASC ', 'id', false);
                $duallayer_select = $this->Create_Stock_Select($vars, $selectedColor, $solid_stock_ids, '10', 'Select Dual Layer Solids Color Set...', false, true, true);
                $colorTypes['1'] = array('id' => '1', 'label' => 'Solids', 'name' => 'solid', 'attr' => ' disabled="disabled"');
                $colorSelects['1'] = $duallayer_select;
                //$checkedRadio = '4';


                $query = 'SELECT dl.`id`, dl.`label`, dl.`message_color_id`, dl.`available_types_ids`, sp.`id` AS msgid, sp.`label` AS msglabel, sp.`color_type`, sp.`glow`, sp.`glitter`, sp.`uv` FROM `tpt_color_duallayer` AS dl LEFT JOIN `tpt_color_special` AS sp ON dl.`message_color_id`=sp.`id` WHERE dl.`enabled`=1 AND FIND_IN_SET(\'' . $type . '\', dl.`available_types_ids`) AND (sp.`color_type`=4 OR sp.`color_type`=5) AND sp.`glow`=0 AND sp.`glitter`=0 AND sp.`uv`=0 ORDER BY `label` ASC';
                $vars['db']['handler']->query($query, __FILE__);
                $multicolored_stock_ids = $vars['db']['handler']->fetch_assoc_list('id', false);
                if (!empty($multicolored_stock_ids)) {
                    //$multicolored_stock_ids = $vars['db']['handler']->getData($vars, 'tpt_color_duallayer', '*', '`enabled`=1 AND (`color_type`=4 OR `color_type`=5) AND `glitter`=0 AND `glow`=0 AND FIND_IN_SET(\''.$type.'\', `available_types_ids`) ORDER BY  `label` ASC ', 'id', false);
                    $multicolored_select = $this->Create_Stock_Select($vars, $selectedColor, $multicolored_stock_ids, '10', 'Select Swirl/Segmented Band Color...', false, true, true);
                    /*
			if($builder['inhouse'])
				$multicolored_select = $this->Create_Stock_Select($vars, $selectedColor, $swirl_stock_ids, '6', 'Select Swirl Band Color...');
			*/
                    $multilabel = 'Multi-Colored Msg';
                    if ($type == 5) {
                        $multilabel = 'Multi-Colored Band';
                    }
                    $colorTypes['5'] = array('id' => '5', 'label' => $multilabel, 'name' => 'multic', 'attr' => '');
                    $colorSelects['5'] = $multicolored_select;
                }

                $query = 'SELECT dl.`id`, dl.`label`, dl.`message_color_id`, dl.`available_types_ids`, sp.`id` AS msgid, sp.`label` AS msglabel, sp.`color_type`, sp.`glow`, sp.`glitter`, sp.`uv` FROM `tpt_color_duallayer` AS dl LEFT JOIN `tpt_color_special` AS sp ON dl.`message_color_id`=sp.`id` WHERE dl.`enabled`=1 AND FIND_IN_SET(\'' . $type . '\', dl.`available_types_ids`) AND sp.`color_type`=3 AND sp.`glitter`=1 ORDER BY `label` ASC';
                $vars['db']['handler']->query($query, __FILE__);
                $glitter_stock_ids = $vars['db']['handler']->fetch_assoc_list('id', false);
                if (!empty($glitter_stock_ids)) {
                    $glitter_select = $this->Create_Stock_Select($vars, $selectedColor, $glitter_stock_ids, '10', 'Select Band Color (/w Glitter)...', false, false, true);
                    /*
			if($builder['inhouse'])
				$multicolored_select = $this->Create_Stock_Select($vars, $selectedColor, $swirl_stock_ids, '6', 'Select Swirl Band Color...');
			*/
                    $colorTypes['6'] = array('id' => '6', 'label' => 'Glitter Msg', 'name' => 'glitter', 'attr' => '');
                    $colorSelects['6'] = $glitter_select;
                }


                $query = 'SELECT dl.`id`, dl.`label`, dl.`message_color_id`, dl.`available_types_ids`, sp.`id` AS msgid, sp.`label` AS msglabel, sp.`color_type`, sp.`glow`, sp.`glitter`, sp.`uv` FROM `tpt_color_duallayer` AS dl LEFT JOIN `tpt_color_special` AS sp ON dl.`message_color_id`=sp.`id` WHERE dl.`enabled`=1 AND FIND_IN_SET(\'' . $type . '\', dl.`available_types_ids`) AND sp.`color_type`=3 AND sp.`glitter`=0 AND sp.`glow`=1 ORDER BY `label` ASC';
                $vars['db']['handler']->query($query, __FILE__);
                $glow_stock_ids = $vars['db']['handler']->fetch_assoc_list('id', false);
                if (!empty($glow_stock_ids)) {
                    $glow_select = $this->Create_Stock_Select($vars, $selectedColor, $glow_stock_ids, '10', 'Select Dual Layer Color Set (/w Glow Message)...', false, true, true);
                    /*
			if($builder['inhouse'])
				$multicolored_select = $this->Create_Stock_Select($vars, $selectedColor, $swirl_stock_ids, '6', 'Select Swirl Band Color...');
			*/
                    $colorTypes['7'] = array('id' => '7', 'label' => 'Glow Msg', 'name' => 'glow', 'attr' => '');
                    $colorSelects['7'] = $glow_select;
                }
            }

            //var_dump($sColorType);die();
            if (empty($sColorType) || !isset($colorTypes[$sColorType])) {
                $rs = reset($colorTypes);
                $checkedRadio = $rs['id'];
                $sColorType = key($colorTypes);
            }


            $colorRadios = array();

            foreach ($colorTypes as $key => $ct) {
                $label = '<label class="amz_brown font-size-14 font-weight-bold" style="font-family: Arial, Helvetica, sans-serif;" for="' . $ct['name'] . '_colors">' . $ct['label'] . '</label> ';
                $radio = tpt_html::createRadiobutton($vars, 'color_type'/*name*/, $ct['id']/*control value*/, $checkedRadio/*checked value*/, ' onclick="' . $ajax_call . '" id="' . $ct['name'] . '_colors"'/*html attribs*/, ''/*oncheck*/);
                $control = $label . $radio;
                $colorRadios[$key] = $control;
            }


            //$solid_radio = tpt_html::createRadiobutton($vars, 'color_type'/*name*/, '1'/*control value*/, '1'/*checked value*/, ' onclick="'.$ajax_call.'" id="solid_colors"'/*html attribs*/, ''/*oncheck*/);
            //$swirl_radio = tpt_html::createRadiobutton($vars, 'color_type'/*name*/, '2'/*control value*/, $sColorType/*checked value*/, ' onclick="'.$ajax_call.'" id="swirl_colors"'/*html attribs*/, ''/*oncheck*/);
            //$segmented_radio = tpt_html::createRadiobutton($vars, 'color_type'/*name*/, '3'/*control value*/, $sColorType/*checked value*/, ' onclick="'.$ajax_call.'" id="segmented_colors"'/*html attribs*/, ''/*oncheck*/);
            //$ds_attr = '';
            //if($builder['style'] == 7) {
            //    $ds_attr = ' disabled="disabled"';
            //}
            //$dual_radio = tpt_html::createRadiobutton($vars, 'color_type'/*name*/, '4'/*control value*/, $sColorType/*checked value*/, ' onclick="'.$ajax_call.'" id="dual_colors" '.$ds_attr/*html attribs*/, ''/*oncheck*/);


            $radios = implode("\n", $colorRadios);

            $intermsg = '';/*
		if(!$builder['inhouse'] && ($style == 1) && isset(getModule($vars, "BandData")->typeStyle[$type]['6']) && (getModule($vars, "BandData")->typeStyle[$type]['6']['minimum_quantity'] == 1)) {
			$intermsg = '<span class="amz_red font-size-12">*No minimum order colors</span>';
		}
		*/
            $selectContent = $colorSelects[$sColorType];


            if (!empty($sColorProps['custom_color'])) {
                $cdef = $sColorProps['colordefinition'];
                $selectContent .= <<< EOT
			<div class="font-style-italic">
			$cdef
			</div>
EOT;
                foreach ($sColorProps['idarray'] as $id) {
                    $hex = $this->by_id[$id]['hex'];
                    $name = $this->by_id[$id]['name'];
                    $selectContent .= <<< EOT
<a style="border-radius: 4px 4px 4px 4px;box-shadow: 1px 1px 2px #888888;display: block;margin: 2px 0;text-align: center;width: 120px;background-color: #$hex;">$name</a>
EOT;
                }
            }


            if (self::$pgBandColor === false) {
                //tpt_dump($selectedColor, true);
                self::$pgBandColor = $selectedColor;
            }

//        $html = <<< EOT
//        <input type="hidden" name="band_color" id="tpt_pg_bandcolor" value="$pgBandColor" />
//EOT;

            $html = <<< EOT
<div style="text-align: left !important;" class="band_color_radios">
$radios
</div>
$intermsg
<div id="color_type_container" style="text-align: left !important;">
$selectContent
</div>
EOT;

            if (empty($builder['inhouse']) && ($style != 7)) {
                $custom_options_class = '';

                $html .= <<< EOT
<div class="ccc_wr $custom_options_class ">
	<a class="thickbox TBinline_900_500 create_custom_color_band plain-link" href="javascript:;"><span class="choose_or_change_band_col">Create</span> Custom Color</a>
</div>

<div class="custom_band_color_indicator"></div><div class="clear"></div>

<div class="clr-extra padding-top-10 $custom_options_class " id="color_addons_wrapper">
	<input onclick="addons_change(this);" class="color_extra" value="1" type="checkbox" id="pg_addon1" name="create_glow" />
	<label class="" for="pg_addon1">Add Glow</label>
	/
	<input onclick="addons_change(this);" class="color_extra" value="2" type="checkbox" id="pg_addon2" name="create_glitter" />
	<label for="pg_addon2">Add Glitter</label>

EOT;
                /* disable UV temporarily
	/
	<input onclick="addons_change(this);" class="color_extra" value="3" type="checkbox" id="pg_addon3" name="create_uv" />
	<label for="pg_addon3">Add UV effect</label>
*/
                $html .= <<< EOT
		<div id="addon_glow_controls" class="display-none">
			<span class="amz_red" style="background-color: #ffff33;">**Dark Colors Will Not Glow**</span>
			<br />
			<a href="#" onclick="see_green_glow(); return false;">See Green Glow</a>&nbsp;
			<a href="#" onclick="see_blue_glow(); return false;">See Blue Glow</a>&nbsp;
			<a href="#" onclick="hide_glow(); return false;">Hide Glow</a>&nbsp;
		</div>
</div>
EOT;
            }

            if ((/*($type == 1) ||*/
                ($type == 5)) && ($style == 7)
            ) {
                $html .= <<< EOT
<br />
<input type="checkbox" name="invert_dual" id="invert_dual_id" value="1" onclick="_short_tpt_pg_change_band_fill();" />&nbsp;Invert Message
EOT;
            } else if (($type == 5) && ($style == 6)/* && ($_SERVER['REMOTE_ADDR'] == '109.160.0.218') */) {
                $html .= <<< EOT
<br />
<input type="checkbox" name="cut_away" id="cut_away_id" value="1" onclick="_short_tpt_pg_change_band_fill();" />&nbsp;Cut-Away Style Message
EOT;
            }


            self::$bandColorContent = $html;
        }

        return array('content' => self::$bandColorContent, 'pgBandColor' => self::$pgBandColor);
    }


    function CustomColor_Panel(&$vars, $sItem, $pid)
    {
        $db = $vars['db']['handler'];
        $colors = $db->getData($vars, $this->moduleTable, '`id`, ``hex`, `pms_c`', ' (1=1) ORDER BY `name`');
        //$colors = $this->moduleData['id'];

        $items = array();
        foreach ($colors as $pms) {
            $id = $pms['id'];
            $name = $pms['pms_c'];
            $hex = $pms['hex'];
            $tcolor = inverseHex($pms['hex']);

            $items[] = <<< EOT
<div class="padding-2 float-left">
	<a onclick="add_pms_color(this); return false;" href="#" class="display-block height-40 width-100 text-align-center" style="background-color: #$hex; color: #$tcolor;">
		<span>$name</span>
		<input type="hidden" name="" value="$id" />
	</a>
</div>
EOT;
        }

        $items = implode('', $items);
        $items = <<< EOT
<div class="clearFix padding-top-10 padding-bottom-10 padding-left-5 padding-right-5">
	<div class="float-left">
		<div class="">
			<select onchange="change_custom_color_type_GUI(this);" id="custom_color_type">
				<option value="0" selected="selected">Solid</option>
				<option value="1">Swirl</option>
				<option value="2">Segmented</option>
			</select>
		</div>
		<div class="padding-top-5">
			<a id="${pid}_1control_color_nnew" onclick="set_custom_color(this); return false;" href="#" class="display-inline-block height-20 line-height-20 text-align-center amz_grey_btn color-white padding-left-5 padding-right-5 text-decoration-none">Use color</a>
		</div>
	</div>
	<div class="overflow-hidden">
		<div class="padding-left-10 height-44" id="custom_colors">
		</div>
	</div>
</div>
<div class="clearFix ">
$items
</div>
EOT;


        return $items;
    }


    function getSelectedItem(&$vars, $input, $options)
    {
        //$items = $this->getItems($vars, $input, $options);

        return (isset($input['color']) ? $input['color'] : 0);
    }

    function getSelectedItem2(&$vars, $input, $options)
    {
        //$items = $this->getItems($vars, $input, $options);

        return (isset($input['message_color']) ? $input['message_color'] : 0);
    }

    function SB_Section(&$vars, $section, $input = array(), $options = array(), &$vinput = array())
    {
        $types_module = getModule($vars, 'BandType');
        $styles_module = getModule($vars, 'BandStyle');
        $db = $vars['db']['handler'];

        $data_module = getModule($vars, 'BandData');
        $data = $data_module->typeStyle;

        $type = $types_module->getActiveItem($vars, $input, $options);
        $style = $styles_module->getActiveItem($vars, $input, $options);
        $sItem = $this->getSelectedItem($vars, $input, $options);
        //$sValue = '-1:'.DEFAULT_BAND_COLOR;
        $sid = $section['id'];
        $sColorType = (isset($input['color_type'][$sid]) ? $input['color_type'][$sid] : null);

        $sname = $section['pname'];

        $data = $data[$type][$style];

        $ih_table = 'tpt_color_special';
        $os_table = 'tpt_color_overseas';
        $dl_table = 'tpt_color_duallayer';
        $led_table = 'tpt_color_led';
        $dwhere1 = '';
        $dwhere2 = '';
        $dwhere3 = '';
        $dwhere4 = '';
        $ccolorlink = '';
        //tpt_dump($data, true);
        if (!empty($data['id'])) {
            $did = $data['id'];
            $dwhere1 = ' AND NOT FIND_IN_SET("' . $did . '", COALESCE(`' . $os_table . '`.`disabled_types_ids2`, ""))';
            $dwhere2 = ' AND FIND_IN_SET("' . $did . '", COALESCE(`' . $ih_table . '`.`available_types_ids2`, ""))';
            $dwhere3 = ' AND FIND_IN_SET("' . $did . '", COALESCE(`' . $dl_table . '`.`available_types_ids2`, ""))';
            $dwhere4 = ' AND FIND_IN_SET("' . $did . '", COALESCE(`' . $led_table . '`.`available_types_ids2`, ""))';
            if (!empty($data['writable'])) {
                $dwhere1 .= ' AND `' . $os_table . '`.`color_type`!=5';
                $dwhere2 .= ' AND `' . $ih_table . '`.`color_type`!=5';
                $dwhere3 .= ' AND `' . $dl_table . '`.`color_type`!=5';
                $dwhere4 .= ' AND `' . $led_table . '`.`color_type`!=5';
            }

            if (!empty($data['pricing_type'])) {
                if (($data['pricing_type']) == 1) {
                    if (!empty($data['dual_layer'])) {
                        $dwhere1 = ' AND FALSE';
                        $dwhere2 = ' AND FALSE';
                        $dwhere3 = ' AND FIND_IN_SET("' . $did . '", COALESCE(`' . $dl_table . '`.`available_types_ids2`, ""))';
                        $dwhere4 = ' AND FIND_IN_SET("' . $did . '", COALESCE(`' . $led_table . '`.`available_types_ids2`, ""))';
                    } else {
                        $dwhere1 = ' AND FALSE';
                        $dwhere2 = ' AND FIND_IN_SET("' . $did . '", COALESCE(`' . $ih_table . '`.`available_types_ids2`, ""))';
                        $dwhere3 = ' AND FIND_IN_SET("' . $did . '", COALESCE(`' . $dl_table . '`.`available_types_ids2`, ""))';
                        $dwhere4 = ' AND FIND_IN_SET("' . $did . '", COALESCE(`' . $led_table . '`.`available_types_ids2`, ""))';
                    }
                } else {
                    $dwhere1 = ' HAVING `table`="tpt_color_overseas"';
                    $dwhere2 = ' HAVING `table`="tpt_color_overseas"';
                    $dwhere3 = ' HAVING `table`="tpt_color_overseas"';
                    $dwhere4 = ' HAVING `table`="tpt_color_overseas"';
                }
            } else {
                $ccolorlink = <<< EOT
<div>
<a class="font-size-10" href="#" id="${sid}_custom_color" onclick="openGUI(this); return false;">Add custom color</a>
</div>
EOT;
            }
        }

        $query = <<< EOT
SELECT * FROM (

	SELECT
		`$os_table`.`id`,
		`$os_table`.`label`,
		`$os_table`.`color_type`,
		`$os_table`.`color_id`,
		`$os_table`.`glow`,
		`$os_table`.`glitter`,
		`$os_table`.`uv`,
		CONCAT(`$os_table`.`color_type`, ":", `$os_table`.`id`) AS `ctid`,
		CASE
			WHEN `$os_table`.`glitter`=1 THEN "glitter"
			WHEN `$os_table`.`glow`=1 THEN "glow"
			WHEN `$os_table`.`color_type`=3 THEN "solid"
			WHEN `$os_table`.`color_type`=4 THEN "swirl"
			WHEN `$os_table`.`color_type`=5 THEN "segmented"
		END
		AS `ccat`,
		CASE
			WHEN `$os_table`.`glitter`=1 THEN 5
			WHEN `$os_table`.`glow`=1 THEN 4
			WHEN `$os_table`.`color_type`=3 THEN 1
			WHEN `$os_table`.`color_type`=4 THEN 2
			WHEN `$os_table`.`color_type`=5 THEN 3
		END
		AS `ccatid`,
		"$os_table" AS `table`,
		`$os_table`.`available_types_ids2`,
		`$os_table`.`disabled_types_ids2`,
		`$os_table`.`enabled`
	FROM
		`$os_table`
	WHERE
		`$os_table`.`enabled`=1
		$dwhere1

	UNION

	SELECT
		`$ih_table`.`id`,
		`$ih_table`.`label`,
		`$ih_table`.`color_type`,
		`$ih_table`.`color_id`,
		`$ih_table`.`glow`,
		`$ih_table`.`glitter`,
		`$ih_table`.`uv`,
		CONCAT("6:", `$ih_table`.`id`) AS `ctid`,
		CASE
			WHEN `$ih_table`.`glitter`=1 THEN "glitter"
			WHEN `$ih_table`.`glow`=1 THEN "glow"
			WHEN `$ih_table`.`color_type`=3 THEN "solid"
			WHEN `$ih_table`.`color_type`=4 THEN "multicolored"
			WHEN `$ih_table`.`color_type`=5 THEN "multicolored"
		END
		AS `ccat`,
		CASE
			WHEN `$ih_table`.`glitter`=1 THEN 9
			WHEN `$ih_table`.`glow`=1 THEN 8
			WHEN `$ih_table`.`color_type`=3 THEN 6
			WHEN `$ih_table`.`color_type`=4 THEN 7
			WHEN `$ih_table`.`color_type`=5 THEN 7
		END
		AS `ccatid`,
		"$ih_table" AS `table`,
		`$ih_table`.`available_types_ids2`,
		`$ih_table`.`disabled_types_ids2`,
		`$ih_table`.`enabled`
	FROM
		`$ih_table`
	WHERE
		`$ih_table`.`enabled`=1
		$dwhere2

	UNION

	SELECT
		`$dl_table`.`id`,
		`$dl_table`.`label`,
		`$dl_table`.`color_type`,
		`$dl_table`.`color_id`,
		`$dl_table`.`glow`,
		`$dl_table`.`glitter`,
		`$dl_table`.`uv`,
		CONCAT("10:", `$dl_table`.`id`) AS `ctid`,
		CASE
			WHEN `$dl_table`.`notched`=1 THEN "edge"
			WHEN `$dl_table`.`powdercoat`=1 THEN "powder coated"
			WHEN `$ih_table`.`glow`=1 THEN "glow msg"
			WHEN `$ih_table`.`glitter`=1 THEN "glitter msg"
			WHEN `$ih_table`.`color_type`=3 THEN "multi-colored msg"
			WHEN `$ih_table`.`color_type`=4 THEN "multi-colored msg"
			WHEN `$ih_table`.`color_type`=5 THEN "multi-colored msg"
		END
		AS `ccat`,
		CASE
			WHEN `$dl_table`.`notched`=1 THEN 14
			WHEN `$dl_table`.`powdercoat`=1 THEN 13
			WHEN `$ih_table`.`glow`=1 THEN 11
			WHEN `$ih_table`.`glitter`=1 THEN 12
			WHEN `$ih_table`.`color_type`=3 THEN 10
			WHEN `$ih_table`.`color_type`=4 THEN 10
			WHEN `$ih_table`.`color_type`=5 THEN 10
		END
		AS `ccatid`,
		"$dl_table" AS `table`,
		`$dl_table`.`available_types_ids2`,
		`$dl_table`.`disabled_types_ids2`,
		`$dl_table`.`enabled`
	FROM
		`$dl_table`
	LEFT JOIN
		`$ih_table`
	ON
		`$dl_table`.`message_color_id`=`$ih_table`.`id`
	WHERE
		`$dl_table`.`enabled`=1
		$dwhere3

	UNION

	SELECT
		`$led_table`.`id`,
		`$led_table`.`label`,
		`$led_table`.`color_type`,
		`$led_table`.`color_id`,
		`$led_table`.`glow`,
		`$led_table`.`glitter`,
		`$led_table`.`uv`,
		CONCAT("11:", `$led_table`.`id`) AS `ctid`,
		#CASE
		#	WHEN `$led_table`.`glitter`=1 THEN "glitter"
		#	WHEN `$led_table`.`glow`=1 THEN "glow"
		#	WHEN `$led_table`.`color_type`=3 THEN "solid"
		#	WHEN `$led_table`.`color_type`=4 THEN "swirl"
		#	WHEN `$led_table`.`color_type`=5 THEN "segmented"
		#END
		"LED Band Colors" AS `ccat`,
		#CASE
		#	WHEN `$os_table`.`glitter`=1 THEN 5
		#	WHEN `$os_table`.`glow`=1 THEN 4
		#	WHEN `$os_table`.`color_type`=3 THEN 1
		#	WHEN `$os_table`.`color_type`=4 THEN 2
		#	WHEN `$os_table`.`color_type`=5 THEN 3
		#END
		11 AS `ccatid`,
		"$led_table" AS `table`,
		`$led_table`.`available_types_ids2`,
		`$led_table`.`disabled_types_ids2`,
		`$led_table`.`enabled`
	FROM
		`$led_table`
	WHERE
		`$led_table`.`enabled`=1
		$dwhere4


) AS `a`
ORDER BY `ccatid`, `label`
EOT;
        $db->query($query);
        $groups = $db->fetch_assoc_list('ccatid', true);

        $radios = array();
        $selects = array();
        $active = reset($groups);
        $active = reset($active);
        $active = $active['ccatid'];
        //$ajax_call = tpt_ajax::getCall('admin.change_color_type_radio');

        foreach ($groups as $ccatid => $items) {
            $ccat = reset($items);
            //tpt_dump( $ccat, false, 'A' );
            $ccat = str_replace(' Colors', '', $ccat['ccat']);
            //tpt_dump( $ccat, false, 'A' );

            $values = array();

            $title = 'Choose ' . $ccat . ' color...';
            $values[] = array(0, $title);
            //tpt_dump( $title, false, 'A' );

            $sOpt = 0;

            $i = 1;
            foreach ($items as $item) {
                $values[] = array($item['ctid'], $item['label']);

                if ($sItem == $item['ctid']) {
                    $sOpt = $i;
                    $active = $ccatid;
                }

                $i++;
            }

            $valuesDelimiter = "\n";

            $selects[$ccatid] = tpt_html::createSelect($vars, '', $values, $sOpt, ' style="max-width:80%; background-color: white; border: 1px solid #ccc; border-radius: 12px; outline: 0 none; margin-top: 10px;" class="padding-4" autocomplete="off" id="control_' . $sid . '" title="' . $title . '" onfocus="removeClass(this, \'invalid_field\');" onchange="if(document.getElementById(\'' . $sid . '_custom_color_preview\') && getChildElements(document.getElementById(\'' . $sid . '_custom_color_preview\'))[0]){document.getElementById(\'' . $sid . '_custom_color_preview\').removeChild(getChildElements(document.getElementById(\'' . $sid . '_custom_color_preview\'))[0]);}process_control_input(this);"');

            //tpt_dump($ccatid);
            //tpt_dump($active);

        }
        if (!is_null($sColorType) && !empty($selects[$sColorType])) {
            $active = $sColorType;
        }

        $rows = array();
        $cells = array();
        $i = 0;
        $citems = count($groups);
        //tpt_dump($groups);
        //tpt_dump($citems);
        foreach ($groups as $ccatid => $items) {
            if (($i == 0) || ($i % 4 == 0)) {
                $cells = array();
            }
            $ccat = reset($items);
            $ccat = $ccat['ccat'];

            $label = '<label class="amz_brown font-size-14 font-weight-bold" style="font-family: Arial, Helvetica, sans-serif;" for="' . $ccatid . '_colors">' . $ccat . '</label>';
            $radio = tpt_html::createRadiobutton($vars, 'color_type[' . $sid . ']', $ccatid, $active, ' onclick="change_color_type_radio(this);" id="' . $sid . '_' . $ccatid . '_color_type" autocomplete="off"', '');
            $control = '<span class="white-space-nowrap">' . $label . $radio . '</span>';
            $radios[$ccatid] = $control;
            $cells[] = $control;
            if (($i % 4 == 3) || ($i >= $citems - 1)) {
                //tpt_dump($i);
                $rows[] = '<div class="clearFix">' . implode("&nbsp;&nbsp;\n&nbsp;&nbsp;", $cells) . '</div>';
            }
            $i++;

        }


        $intermsg = '';
        //$radios = implode("&nbsp;&nbsp;\n&nbsp;&nbsp;", $radios);
        $radios = implode("\n", $rows);
        //tpt_dump(htmlspecialchars($radios));
        $selectContent = $selects[$active];

        if (!empty($data['pricing_type'])) {
        } else {
            $ccolorpreview = $this->getCustomColorPreview($vars, $sItem);
            $ccolorlink = <<< EOT
<div>
	<a class="font-size-10" href="#" id="${sid}_custom_color" onclick="openGUI(this); return false;">Add custom color</a>
	<div id="${sid}_custom_color_preview">
		$ccolorpreview
	</div>
</div>
EOT;
        }

        $showled = '';

       if (!empty($data['led'])) {
			if($type == 38 ){
            $showled = <<< EOT
<br/>
<a class="plain-link" id="showledvideolink" onclick="show_led_video(); return false;" href="#">View Sample Video</a>
<br/>
<a class="plain-link" onclick="led_glow(); return false;" href="#">Toggle LED Glow On/Off</a>
<br />

EOT;
			} else {
			$showled = <<< EOT
<br/>
<a class="plain-link" onclick="led_glow(); return false;" href="#">Toggle LED Glow On/Off</a>
<br />
<a class="plain-link" onclick="toggle_led_flash(); return false;" href="#">Toggle LED Flash On/Off</a>
<br />
<a class="plain-link" onclick="toggle_led_flash2(); return false;" href="#">Toggle LED Flash 2 On/Off</a>
<br />

EOT;
			}
        }

        $content = <<< EOT
<div style="text-align: left !important;" class="band_color_radios">
$radios
<input type="hidden" id="{$sid}_color_type" name="color_type[{$sid}]" value="$sColorType" />
</div>

<div id="{$sname}_type_container" style="text-align: left !important;">
$selectContent
<input type="hidden" id="$sname" name="$sname" value="$sItem" />
</div>

$ccolorlink
$showled
EOT;

        return array(
            'content' => $content,
            'sColorType' => $active
        );

    }


    function SB_Section2(&$vars, $section, $input = array(), $options = array(), &$vinput = array())
    {
        $values = array();
        //var_dump($stvals);die();

        $title = 'Select Message Color...';

        $sid = $section['id'];

        $suggested_items_ids = $this->solid;
        $suggested_items_labels = $vars['db']['handler']->getData($vars, 'tpt_color_overseas', '*', '`enabled`=1 AND `color_type`=3 ORDER BY  `label` ASC ', 'label', false);

        $suggested_items_ids = array_filter($suggested_items_ids);

        $sItem = $this->getSelectedItem2($vars, $input, $options);

        $sOpt = 0;
        $i = 1;
        $values[] = array(0, $title);
        foreach ($suggested_items_ids as $key => $item) {
            $optcolor = '#000';
            $whiteopts = array(
                '1152',
                '1146',
                '1145',
                '1144',
                '1134',
                '1133',
                '1128',
                '1107',
                '1109',
                '1114',
                '1135',
                '1143',
                '1149',
                '1150',
                '1151',
                '1153',
                '1154',
                '1093',
                '916',
                '596',
                '433',
                '319',
                '312',
                '304',
                '295',
                '261',
                '223',
                '169',
                '160',
                '139',
                '137',
                '130',
                '150',
                '162',
                '287',
                '308',
                '423',
                '440',
                '517',
                '558',
                '574',
                '575',
                '648',
                '86',
                '127',
            );
            if (in_array($item['color_id'], $whiteopts))
                $optcolor = '#FFF';

            $bgcolor = '#' . $this->by_id[$item['color_id']]['hex'];
            $values[] = array('3:' . $item['id'], $item['label'], 'attr' => ' style="background-color: ' . $bgcolor . '; color: ' . $optcolor . ';"');

            if ($sItem == '3:' . $item['id']) {
                $sOpt = $i;
            }
            $i++;
        }

        $html = tpt_html::createSelect($vars, '', $values, $sOpt, 'style="max-width:80%; background-color: white;border: 1px solid #ccc;border-radius: 12px;outline: 0 none;" class="padding-4" autocomplete="off" id="control_' . $sid . '" onfocus="removeClass(this, \'invalid_field\');" onchange="if(document.getElementById(\'' . $sid . '_custom_color_preview\') && getChildElements(document.getElementById(\'' . $sid . '_custom_color_preview\'))[0]){document.getElementById(\'' . $sid . '_custom_color_preview\').removeChild(getChildElements(document.getElementById(\'' . $sid . '_custom_color_preview\'))[0]);}process_control_input(this);" title="' . $title . '"');
        $html .= '<input type="hidden" id="message_color" name="message_color" value="' . $sItem . '" />';

        return $html;
    }


    function BandColor_Section_Admin(&$vars, $sItem, $pgData, $pid = 'null', $sColorType = null)
    {
        $db = $vars['db']['handler'];

        $data_module = getModule($vars, 'BandData');
        $data = $data_module->typeStyle;
        $data = $pgData;
        //tpt_dump($pgData);

        $ih_table = 'tpt_color_special';
        $os_table = 'tpt_color_overseas';
        $dl_table = 'tpt_color_duallayer';
        $dwhere1 = '';
        $dwhere2 = '';
        $dwhere3 = '';
        $ccolorlink = '';
        //tpt_dump($data, true);
        if (!empty($data['id'])) {
            $did = $data['id'];
            $dwhere1 = ' AND NOT FIND_IN_SET("' . $did . '", COALESCE(`' . $os_table . '`.`disabled_types_ids2`, ""))';
            $dwhere2 = ' AND FIND_IN_SET("' . $did . '", COALESCE(`' . $ih_table . '`.`available_types_ids2`, ""))';
            $dwhere3 = ' AND FIND_IN_SET("' . $did . '", COALESCE(`' . $dl_table . '`.`available_types_ids2`, ""))';
            if (!empty($data['writable'])) {
                $dwhere1 .= ' AND `' . $os_table . '`.`color_type`!=5';
                $dwhere2 .= ' AND `' . $ih_table . '`.`color_type`!=5';
                $dwhere3 .= ' AND `' . $dl_table . '`.`color_type`!=5';
            }

            if (!empty($data['pricing_type'])) {
                if (!empty($data['dual_layer'])) {
                    $dwhere1 = ' AND FALSE';
                    $dwhere2 = ' AND FALSE';
                    $dwhere3 = ' AND FIND_IN_SET("' . $did . '", COALESCE(`' . $dl_table . '`.`available_types_ids2`, ""))';
                } else {
                    $dwhere1 = ' AND FALSE';
                    $dwhere2 = ' AND FIND_IN_SET("' . $did . '", COALESCE(`' . $ih_table . '`.`available_types_ids2`, ""))';
                    $dwhere3 = ' AND FIND_IN_SET("' . $did . '", COALESCE(`' . $dl_table . '`.`available_types_ids2`, ""))';
                }
            } else {
                $ccolorlink = <<< EOT
<div>
<a class="font-size-10" href="#" id="${pid}_custom_color" onclick="openGUI(this); return false;">Add custom color</a>
</div>
EOT;

            }
        }

        $query = <<< EOT
SELECT * FROM (

	SELECT
		`$os_table`.`id`,
		`$os_table`.`label`,
		`$os_table`.`color_type`,
		`$os_table`.`color_id`,
		`$os_table`.`glow`,
		`$os_table`.`glitter`,
		`$os_table`.`uv`,
		CONCAT(`$os_table`.`color_type`, ":", `$os_table`.`id`) AS `ctid`,
		CASE
			WHEN `$os_table`.`glitter`=1 THEN "glitter"
			WHEN `$os_table`.`glow`=1 THEN "glow"
			WHEN `$os_table`.`color_type`=3 THEN "solid"
			WHEN `$os_table`.`color_type`=4 THEN "swirl"
			WHEN `$os_table`.`color_type`=5 THEN "segmented"
		END
		AS `ccat`,
		CASE
			WHEN `$os_table`.`glitter`=1 THEN 5
			WHEN `$os_table`.`glow`=1 THEN 4
			WHEN `$os_table`.`color_type`=3 THEN 1
			WHEN `$os_table`.`color_type`=4 THEN 2
			WHEN `$os_table`.`color_type`=5 THEN 3
		END
		AS `ccatid`,
		"$os_table" AS `table`,
		`$os_table`.`available_types_ids2`,
		`$os_table`.`disabled_types_ids2`,
		`$os_table`.`enabled`
	FROM
		`$os_table`
	WHERE
		`$os_table`.`enabled`=1
		$dwhere1

	UNION

	SELECT
		`$ih_table`.`id`,
		`$ih_table`.`label`,
		`$ih_table`.`color_type`,
		`$ih_table`.`color_id`,
		`$ih_table`.`glow`,
		`$ih_table`.`glitter`,
		`$ih_table`.`uv`,
		CONCAT("6:", `$ih_table`.`id`) AS `ctid`,
		CASE
			WHEN `$ih_table`.`glitter`=1 THEN "glitter"
			WHEN `$ih_table`.`glow`=1 THEN "glow"
			WHEN `$ih_table`.`color_type`=3 THEN "solid"
			WHEN `$ih_table`.`color_type`=4 THEN "multicolored"
			WHEN `$ih_table`.`color_type`=5 THEN "multicolored"
		END
		AS `ccat`,
		CASE
			WHEN `$ih_table`.`glitter`=1 THEN 9
			WHEN `$ih_table`.`glow`=1 THEN 8
			WHEN `$ih_table`.`color_type`=3 THEN 6
			WHEN `$ih_table`.`color_type`=4 THEN 7
			WHEN `$ih_table`.`color_type`=5 THEN 7
		END
		AS `ccatid`,
		"$ih_table" AS `table`,
		`$ih_table`.`available_types_ids2`,
		`$ih_table`.`disabled_types_ids2`,
		`$ih_table`.`enabled`
	FROM
		`$ih_table`
	WHERE
		`$ih_table`.`enabled`=1
		$dwhere2

	UNION

	SELECT
		`$dl_table`.`id`,
		`$dl_table`.`label`,
		`$dl_table`.`color_type`,
		`$dl_table`.`color_id`,
		`$dl_table`.`glow`,
		`$dl_table`.`glitter`,
		`$dl_table`.`uv`,
		CONCAT("10:", `$dl_table`.`id`) AS `ctid`,
		CASE
			WHEN `$dl_table`.`notched`=1 THEN "edge"
			WHEN `$dl_table`.`powdercoat`=1 THEN "powder coated"
			WHEN `$ih_table`.`glow`=1 THEN "glow msg"
			WHEN `$ih_table`.`glitter`=1 THEN "glitter msg"
			WHEN `$ih_table`.`color_type`=3 THEN "multi-colored msg"
			WHEN `$ih_table`.`color_type`=4 THEN "multi-colored msg"
			WHEN `$ih_table`.`color_type`=5 THEN "multi-colored msg"
		END
		AS `ccat`,
		CASE
			WHEN `$dl_table`.`notched`=1 THEN 14
			WHEN `$dl_table`.`powdercoat`=1 THEN 13
			WHEN `$ih_table`.`glow`=1 THEN 11
			WHEN `$ih_table`.`glitter`=1 THEN 12
			WHEN `$ih_table`.`color_type`=3 THEN 10
			WHEN `$ih_table`.`color_type`=4 THEN 10
			WHEN `$ih_table`.`color_type`=5 THEN 10
		END
		AS `ccatid`,
		"$dl_table" AS `table`,
		`$dl_table`.`available_types_ids2`,
		`$dl_table`.`disabled_types_ids2`,
		`$dl_table`.`enabled`
	FROM
		`$dl_table`
	LEFT JOIN
		`$ih_table`
	ON
		`$dl_table`.`message_color_id`=`$ih_table`.`id`
	WHERE
		`$dl_table`.`enabled`=1
		$dwhere3

) AS `a`
ORDER BY `ccatid`, `label`
EOT;
        //tpt_dump($query, true);
        $db->query($query);
        $groups = $db->fetch_assoc_list('ccatid', true);
        //tpt_dump($groups, true);
        $radios = array();
        $selects = array();
        $active = reset($groups);
        $active = reset($active);
        $active = $active['ccatid'];
        //$ajax_call = tpt_ajax::getCall('admin.change_color_type_radio');

        //tpt_dump($sItem);
        //tpt_dump($groups);
        foreach ($groups as $ccatid => $items) {
            $ccat = reset($items);
            $ccat = $ccat['ccat'];

            $values = array();

            $title = 'Choose ' . $ccat . ' color...';
            $values[] = array(0, $title);
            //var_dump($sItem);

            $sOpt = 0;

            $i = 1;
            foreach ($items as $item) {
                $values[] = array($item['ctid'], $item['label']);

                if ($sItem == $item['ctid']) {
                    $sOpt = $i;
                    $active = $ccatid;
                }

                $i++;
            }

            $valuesDelimiter = "\n";

            $selects[$ccatid] = tpt_html::createSelect($vars, '', $values, $sOpt, ' autocomplete="off" id="' . $pid . '_control_color_nnew" title="' . $title . '" onfocus="removeClass(this, \'invalid_field\');" onchange="if(document.getElementById(\'' . $pid . '_custom_color_preview\') && getChildElements(document.getElementById(\'' . $pid . '_custom_color_preview\'))[0]){document.getElementById(\'' . $pid . '_custom_color_preview\').removeChild(getChildElements(document.getElementById(\'' . $pid . '_custom_color_preview\'))[0]);}update_product_row_field(this);"');

            //tpt_dump($ccatid);
            //tpt_dump($active);

        }
        if (!is_null($sColorType) && !empty($selects[$sColorType])) {
            $active = $sColorType;
        }

        $rows = array();
        $cells = array();
        $i = 0;
        $citems = count($groups);
        //tpt_dump($groups);
        //tpt_dump($citems);
        foreach ($groups as $ccatid => $items) {
            if (($i == 0) || ($i % 4 == 0)) {
                $cells = array();
            }
            $ccat = reset($items);
            $ccat = $ccat['ccat'];

            $label = '<label class="amz_brown font-size-14 font-weight-bold" style="font-family: Arial, Helvetica, sans-serif;" for="' . $ccatid . '_colors">' . $ccat . '</label>';
            $radio = tpt_html::createRadiobutton($vars, 'color_type[' . $pid . ']', $ccatid, $active, ' onclick="change_color_type_radio(this);" id="' . $pid . '_' . $ccatid . '_color_type" autocomplete="off"', '');
            $control = '<span class="white-space-nowrap">' . $label . $radio . '</span>';
            $radios[$ccatid] = $control;
            $cells[] = $control;
            if (($i % 4 == 3) || ($i >= $citems - 1)) {
                //tpt_dump($i);
                $rows[] = '<div class="clearFix">' . implode("&nbsp;&nbsp;\n&nbsp;&nbsp;", $cells) . '</div>';
            }
            $i++;

        }


        $intermsg = '';
        //$radios = implode("&nbsp;&nbsp;\n&nbsp;&nbsp;", $radios);
        $radios = implode("\n", $rows);
        //tpt_dump(htmlspecialchars($radios));
        $selectContent = $selects[$active];

        if (!empty($data['pricing_type'])) {
        } else {
            $ccolorpreview = $this->getCustomColorPreview($vars, $sItem);
            $ccolorlink = <<< EOT
<div>
	<a class="font-size-10" href="#" id="${pid}_custom_color" onclick="openGUI(this); return false;">Add custom color</a>
	<div id="${pid}_custom_color_preview">
		$ccolorpreview
	</div>
</div>
EOT;
        }

        $content = <<< EOT
<div style="text-align: left !important;" class="band_color_radios">
$radios
</div>

<div id="color_type_container" style="text-align: left !important;">
$selectContent
</div>

$ccolorlink
EOT;

        return array(
            'content' => $content,
            'sColorType' => $active
        );

    }


    function getCustomColorPreview(&$vars, $color)
    {
        $cprops = $this->getColorProps($vars, $color);

        //tpt_dump($cprops, true);
        //tpt_dump($color, true);
        if (empty($color) || ($color == '0:0') || empty($cprops['custom_color'])) {
            return '';
        }

        $html = '';
        $items = array();

        foreach ($cprops['idarray'] as $id) {
            $pms = (!empty($this->by_id[$id]) ? $this->by_id[$id] : array());
            $name = (!empty($pms['pms_c']) ? $pms['pms_c'] : '');
            $hex = (!empty($pms['hex']) ? $pms['hex'] : $id);
            $tcolor = inverseHex($hex);

            $items[] = <<< EOT
<div class="float-left padding-1 height-40 line-height-40">
	<div class="display-inline-block text-align-center font-size-60prc width-80 line-height-20" style="vertical-align: middle; box-shadow: 1px 1px 2px #888; border-radius: 4px; background-color: #$hex; color: #$tcolor;">
		$name
	</div>
</div>
EOT;
        }
        $items = implode('', $items);
        $cdef = $cprops['colordefinition'];

        $html = <<< EOT
<div class="">
	<div class="">
		$cdef
	</div>
	<div class="clearFix">
		$items
	</div>
</div>
EOT;

        return $html;

    }

    function getCustomColorPreview_SB(&$vars, $color)
    {
        $cprops = $this->getColorProps($vars, $color);

        //tpt_dump($cprops, true);
        //tpt_dump($color, true);
        if (empty($color) || ($color == '0:0') || empty($cprops['custom_color'])) {
            return '';
        }

        $html = '';
        $items = array();

        foreach ($cprops['idarray'] as $id) {
            $pms = (!empty($this->by_id[$id]) ? $this->by_id[$id] : array());
            //$name = (!empty($pms['pms_c'])?$pms['pms_c']:'');
            $name = (!empty($pms['name']) ? $pms['name'] : '');
            $hex = (!empty($pms['hex']) ? $pms['hex'] : $id);
            $tcolor = inverseHex($hex);

            $items[] = <<< EOT
<div class="selected_custom_color" style="background-color: #$hex; color: #$tcolor;">
	$name
</div>
EOT;
        }
        $items = implode('', $items);
        $cdef = $cprops['colordefinition'];

        $html = <<< EOT
<div class="custom_band_color_indicator">
	<div class="head">
		$cdef:
	</div>
	$items
</div>
EOT;

        return $html;

    }

    function getCustomMessageColorPreview_SB(&$vars, $color)
    {
        $cprops = $this->getColorProps($vars, $color);

        //tpt_dump($cprops, true);
        //tpt_dump($color, true);
        if (empty($color) || ($color == '0:0') || empty($cprops['custom_color'])) {
            return '';
        }

        $html = '';
        $items = array();

        foreach ($cprops['idarray'] as $id) {
            $pms = (!empty($this->by_id[$id]) ? $this->by_id[$id] : array());
            //$name = (!empty($pms['pms_c'])?$pms['pms_c']:'');
            $name = (!empty($pms['name']) ? $pms['name'] : '');
            $hex = (!empty($pms['hex']) ? $pms['hex'] : $id);
            $tcolor = inverseHex($hex);

            $items[] = <<< EOT
<div class="selected_custom_color" style="background-color: #$hex; color: #$tcolor;">
	$name
</div>
EOT;
        }
        $items = implode('', $items);
        $cdef = $cprops['colordefinition'];

        $html = <<< EOT
<div class="custom_message_color_indicator">
	$items
</div>
EOT;

        return $html;

    }


    function MessageColor_Section_Admin(&$vars, $pname, $selectedColor, $product, $pid = 'null')
    {
        $values = array();
        //var_dump($stvals);die();

        $title = 'Select Message Color...';

        $suggested_items_ids = $this->solid;
        $suggested_items_labels = $vars['db']['handler']->getData($vars, 'tpt_color_overseas', '*', '`enabled`=1 AND `color_type`=3 ORDER BY  `label` ASC ', 'label', false);

        $suggested_items_ids = array_filter($suggested_items_ids);

        $sColor = 0;
        $i = 1;
        $values[] = array(0, $title);
        foreach ($suggested_items_ids as $key => $item) {
            $optcolor = '#000';
            $whiteopts = array(
                '1152',
                '1146',
                '1145',
                '1144',
                '1134',
                '1133',
                '1128',
                '1107',
                '1109',
                '1114',
                '1135',
                '1143',
                '1149',
                '1150',
                '1151',
                '1153',
                '1154',
                '1093',
                '916',
                '596',
                '433',
                '319',
                '312',
                '304',
                '295',
                '261',
                '223',
                '169',
                '160',
                '139',
                '137',
                '130',
                '150',
                '162',
                '287',
                '308',
                '423',
                '440',
                '517',
                '558',
                '574',
                '575',
                '648',
                '86',
                '127',
            );
            if (in_array($item['color_id'], $whiteopts))
                $optcolor = '#FFF';

            $bgcolor = '#' . $this->by_id[$item['color_id']]['hex'];
            $values[] = array('3:' . $item['id'], $item['label'], 'attr' => ' style="background-color: ' . $bgcolor . '; color: ' . $optcolor . ';"');

            if ($selectedColor == '3:' . $item['id']) {
                $sColor = $i;
            }
            $i++;
        }

        return tpt_html::createSelect($vars, '', $values, $sColor, ' id="' . $pid . '_control_' . $pname . '" onfocus="removeClass(this, \'invalid_field\');" onchange="update_product_row_field(this);" title="' . $title . '"');
    }


    function getColorSelectType(&$vars, $selectedColor, $type, $style, $builder)
    {
        $sColorProps = $this->getColorProps($vars, $selectedColor);

        $ret = '1';

        if ($style != 7) {
            if ($type != 5) {
                if (!$builder['inhouse']) {
                    if (!empty($sColorProps['glitter'])) {
                        $ret = '6';
                    } else if (!empty($sColorProps['glow'])) {
                        $ret = '7';
                    } else if (!empty($sColorProps['swirl'])) {
                        $ret = '2';
                    } else if (!empty($sColorProps['segmented'])) {
                        $ret = '3';
                    } else {
                        $ret = '1';
                    }
                } else {
                    if (!empty($sColorProps['glitter'])) {
                        $ret = '6';
                    } else if (!empty($sColorProps['glow'])) {
                        $ret = '7';
                    } else if (!empty($sColorProps['swirl'])) {
                        $ret = '5';
                    } else if (!empty($sColorProps['segmented'])) {
                        $ret = '5';
                    } else {
                        $ret = '1';
                    }
                }
            } else {
                if (!empty($sColorProps['glitter'])) {
                    $ret = '6';
                } else if (!empty($sColorProps['glow'])) {
                    $ret = '7';
                } else if (!empty($sColorProps['swirl'])) {
                    $ret = '5';
                } else if (!empty($sColorProps['segmented'])) {
                    $ret = '5';
                } else {
                    $ret = '1';
                }
            }
        } else {
            $query = 'SELECT `dl`.`id`,`dl`.`label`,`dl`.`color_id`,`dl`.`message_color_id`,`msg`.`id` AS `mid`,`msg`.`color_type` AS `ctype` FROM `tpt_color_duallayer` AS `dl` LEFT JOIN `tpt_color_special` AS `msg` ON `dl`.`message_color_id`=`msg`.`id` WHERE `dl`.`id`=' . $sColorProps['colorId'];
            //var_dump($query);die();
            $vars['db']['handler']->query($query, __FILE__);
            $col = $vars['db']['handler']->fetch_assoc_list('id', false);
            $mmsg = 0;
            if (!empty($col)) {
                $col = reset($col);
                if (($col['ctype'] == 3) || ($col['ctype'] == 4)) {
                    $mmsg = 1;
                }
            }

            if (!empty($sColorProps['glitter'])) {
                $ret = '6';
            } else if (!empty($sColorProps['glow'])) {
                $ret = '7';
            } else if (!empty($mmsg)) {
                $ret = '5';
            } else {
                $ret = '1';
            }
        }

        return $ret;

    }

    function getColorFromString(&$vars, $selectedColor, $type, $style, $inhouse = false)
    {

        $ret = '-1:' . DEFAULT_BAND_COLOR;

        if ($style != 7) {
            if ($type != 5) {
                if (!$inhouse) {
                    $query = <<< EOT
					SELECT * FROM (
					(SELECT `id`,`label`, `color_type`,`color_id`, "" AS `message_color_id`, 0 AS `glow`, 0 AS `glitter`, 0 AS `uv`, 0 AS `powdercoat`,`enabled`, "" AS `available_types_ids`, 0 as `stock`, 3 as `tbl` FROM `tpt_color_overseas` WHERE `color_type`=3 AND LOWER(`label`)=LOWER("$selectedColor"))
					UNION
					(SELECT `id`,`label`, `color_type`,`color_id`, "" AS `message_color_id`, 0 AS `glow`, 0 AS `glitter`, 0 AS `uv`, 0 AS `powdercoat`,`enabled`, "" AS `available_types_ids`, 0 as `stock`, 4 as `tbl` FROM `tpt_color_overseas` WHERE `color_type`=4 AND LOWER(`label`)=LOWER("$selectedColor"))
					UNION
					(SELECT `id`,`label`, `color_type`,`color_id`, "" AS `message_color_id`, 0 AS `glow`, 0 AS `glitter`, 0 AS `uv`, 0 AS `powdercoat`,`enabled`, "" AS `available_types_ids`, 0 as `stock`, 5 as `tbl` FROM `tpt_color_overseas` WHERE `color_type`=5 AND LOWER(`label`)=LOWER("$selectedColor"))
					UNION
					(SELECT `id`,`label`,`color_type`,`color_id`,`message_color_id`,`glow`,`glitter`,`uv`,`powdercoat`,`enabled`,`available_types_ids`, 1 as `stock`, 6 as `tbl` FROM `tpt_color_special` WHERE LOWER(`label`)=LOWER("$selectedColor") AND FIND_IN_SET('$type', `available_types_ids`))
					) AS `ss` ORDER BY `stock` DESC LIMIT 1
EOT;
//var_dump($query);die();
                    $vars['db']['handler']->query($query, __FILE__);
                    $colors = $vars['db']['handler']->fetch_assoc_list();
                    if (!empty($colors)) {
                        $ret = reset($colors);
                        $ret = $ret['tbl'] . ':' . $ret['id'];
                    }

                } else {
                    $query = <<< EOT
					SELECT `id`,`label`,`color_type`,`color_id`,`message_color_id`,`glow`,`glitter`,`uv`,`powdercoat`,`enabled`,`available_types_ids`, 1 as `stock`, 6 as `tbl` FROM `tpt_color_special` WHERE LOWER(`label`)=LOWER("$selectedColor") AND FIND_IN_SET('$type', `available_types_ids`) LIMIT 1
EOT;
//var_dump($query);die();
                    $vars['db']['handler']->query($query, __FILE__);
                    $colors = $vars['db']['handler']->fetch_assoc_list();
                    if (!empty($colors)) {
                        $ret = reset($colors);
                        $ret = $ret['tbl'] . ':' . $ret['id'];
                    }
                }
            } else {
                $query = <<< EOT
				SELECT `id`,`label`,`color_type`,`color_id`,`message_color_id`,`glow`,`glitter`,`uv`,`powdercoat`,`enabled`,`available_types_ids`, 1 as `stock`, 6 as `tbl` FROM `tpt_color_special` WHERE LOWER(`label`)=LOWER("$selectedColor") AND FIND_IN_SET('$type', `available_types_ids`) LIMIT 1
EOT;
//var_dump($query);die();
                $vars['db']['handler']->query($query, __FILE__);
                $colors = $vars['db']['handler']->fetch_assoc_list();
                if (!empty($colors)) {
                    $ret = reset($colors);
                    $ret = $ret['tbl'] . ':' . $ret['id'];
                }
            }
        } else {
            $query = <<< EOT
			SELECT `id`,`label`,`color_type`,`color_id`,`message_color_id`,`glow`,`glitter`,`uv`,`powdercoat`,`enabled`,`available_types_ids`, 1 as `stock`, 10 as `tbl` FROM `tpt_color_duallayer` WHERE LOWER(`label`)=LOWER("$selectedColor") AND FIND_IN_SET('$type', `available_types_ids`) LIMIT 1
EOT;
//var_dump($query);die();
            $vars['db']['handler']->query($query, __FILE__);
            $colors = $vars['db']['handler']->fetch_assoc_list();
            if (!empty($colors)) {
                $ret = reset($colors);
                $ret = $ret['tbl'] . ':' . $ret['id'];
            }
        }

        return $ret;

    }


    function MessageColor_Section_SB(&$vars, $selectedColor, $type, $style, $builder)
    {
        $types_module = getModule($vars, "BandType");
        $data_module = getModule($vars, "BandData");

		$dTypeArr = (isset($data_module->typeStyle[$type][$style])?$data_module->typeStyle[$type][$style]:array('id'=>0, 'pricing_type'=>0, 'type'=>0, 'style'=>0, 'writable'=>0));
		$dType = $dTypeArr['id'];
        $dIHType = $dType;

        if (!empty($dTypeArr['writable'])) {
            $type = $dTypeArr['base_type'];
        }


        $sColorProps = $this->getColorProps($vars, $selectedColor);
        if ((($style == 7) && (empty($sColorProps['dual_layer']) || !empty($sColorProps['custom_color']))) || (($style != 7) && !empty($sColorProps['dual_layer']))) {
            self::$pgMessageColor = '-1:' . DEFAULT_MESSAGE_COLOR;
        }

        if ((self::$messageColorContent === false) || (self::$pgMessageColor === false)) {
            $html = '';

            //var_dump($style);die();
            $colorTypes = array();
            $colorSelects = array();

            if (self::$pgMessageColor === false) {
                self::$pgMessageColor = $selectedColor;
            }

            if (in_array($style, array(2, 4, 5, 16)) && !$builder['inhouse']) {
                $custom_options_class = '';

                $solid_stock_ids = array();
                //$solid_stock_labels = $vars['db']['handler']->getData($vars, 'tpt_color_special', '*', '`enabled`=1 AND `color_type`=3 AND FIND_IN_SET(\''.$type.'\', `available_types_ids`) ORDER BY  `label` ASC ', 'label', false);
                if (isset($builder['standard_url']) && $builder['standard_url'] == '/Ink-Filled-Debossed-Wristbands') {
                    $solid_suggested_ids = $vars['db']['handler']->getData($vars, 'tpt_color_overseas', '*', ' `color_type`=3 AND `Glitter`=0 AND `uv`=0 AND `enabled`=1 ORDER BY  `label` ASC ', 'id', false);
                    $solid_suggested_labels = $vars['db']['handler']->getData($vars, 'tpt_color_overseas', '*', ' `color_type`=3 AND `Glitter`=0 AND `uv`=0 AND `enabled`=1 ORDER BY  `label` ASC ', 'label', false);
                } else {
                    $solid_suggested_ids = $vars['db']['handler']->getData($vars, 'tpt_color_overseas', '*', ' `color_type`=3 AND `glow`=0 AND `glitter`=0 AND `enabled`=1 ORDER BY  `label` ASC ', 'id', false);
                    $solid_suggested_labels = $vars['db']['handler']->getData($vars, 'tpt_color_overseas', '*', ' `color_type`=3 AND `glow`=0 AND `glitter`=0 AND `enabled`=1 ORDER BY  `label` ASC ', 'label', false);
                }
                $solid_select = $this->Create_Combined_Solids_Select($vars, $selectedColor, $solid_stock_ids, $solid_suggested_ids, $solid_suggested_labels, '3', 'Select Message Color...', true);
                $colorSelects['1'] = $solid_select;

                $selectContent = $colorSelects['1'];

                $html .= <<< EOT

<div class="height-20"></div>

<div class="tpt_form_section_title display-block">
	<div class="todayshop-bold font-size-18 padding-top-5 padding-bottom-10" style="color: #669669;text-align: left !important;">
		Message Color
	</div>
</div>

<div id="message_color_container" style="text-align: left !important;">
$selectContent
EOT;
                if (!empty($dTypeArr['invert_screenprint_control'])) {
                    //tpt_dump($style, true);
                    $invert_screenprint_checked = '';
                    $invert_screenprint_checked = ($style == 16) ? ' checked="checked"' : '';
                    /*
				$html .= <<< EOT
				<br />
				<input type="checkbox" name="cut_away" id="cut_away_id" value="1" onclick="_short_tpt_pg_change_band_fill();" />&nbsp;Cut-Away Style Message
				EOT;
				*/
                    $html .= <<< EOT
		<!--br /-->
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input $invert_screenprint_checked type="checkbox" name="invert_screenprint" id="invert_screenprint_id" value="1" onclick="if(this.checked){document.getElementById('tpt_pg_style').value='16';goGetSome('bandtype.change_band_type_sb', this.form);}else{document.getElementById('tpt_pg_style').value='5';goGetSome('bandtype.change_band_type_sb', this.form);}" />&nbsp;Invert Print
EOT;
                }
                $html .= <<< EOT
</div>
EOT;

                $customcolor = '<div class="custom_message_color_indicator"></div><div class="clear"></div>';
                if (!empty($sColorProps['custom_color'])) {
                    $customcolor = $this->getCustomMessageColorPreview_SB($vars, $selectedColor);
                }

                $html .= <<< EOT
<div class="ccc_wr $custom_options_class ">
	<a class="thickbox TBinline_900_500 create_custom_color_message plain-link" href="javascript:;"><span class="choose_or_change_message_col">Create</span> Custom <strong>Message</strong> Color</a>
</div>

$customcolor
EOT;

                if (isDev() || !empty($_GET['glowmessage'])) {
                    if ($style == 2) {
                        $custom_options_class = '';

                        $glowcheck = !empty($sColorProps['glow']) ? ' checked="checked"' : '';
                        $html .= <<< EOT
<div class="clr-extra padding-top-10 $custom_options_class " id="message_color_addons_wrapper">
	<input $glowcheck onclick="addons_change(this);" class="color_extra" value="3" type="checkbox" id="pg_addon3" name="create_message_glow" />
	<label class="" for="pg_addon3">Add Glow-in-the-Dark Message</label>
EOT;
                        $glcls = 'display-none';
                        if (!empty($glowcheck)) {
                            $glcls = '';
                        }

                        $html .= <<< EOT
		<div id="addon_message_glow_controls" class="$glcls">
			<span class="amz_red" style="background-color: #ffff33;">**Dark Colors Will Not Glow**</span>
		</div>
</div>
EOT;
                    }
                }


            }


            //$html .= '<input type="hidden" name="message_color" id="tpt_pg_msgcolor" value="'.self::$pgMessageColor.'" />';

            self::$messageColorContent = $html;
        }
        //var_dump($sColorType);die();


        return array('content' => self::$messageColorContent, 'pgMessageColor' => self::$pgMessageColor);
    }


    /*
	function Get_Available_Band_Color_Value(&$vars, $sColorType, $sColor, $type, $style, $builder) {
	}
	*/


    function Create_Stock_Solids_Select(&$vars, $selectedColor, $stock_items_ids, $title = 'Select Solid Band Color...')
    {
        $solid = $items;
        //$solid_id = array_search($this->solid, $this->all_colors);

        $values = array();
        //var_dump($stvals);die();

        $sColor = 0;
        $i = 1;
        $values[] = array(0, $title);
        foreach ($stock_items_ids as $key => $item) {
            $optcolor = '#000';
            if ($item['color_id'] == '1107' || $item['color_id'] == '1146' || $item['color_id'] == '1145' || $item['color_id'] == '1144' || $item['color_id'] == '1134' || $item['color_id'] == '1128' || $item['color_id'] == '1093' || $item['color_id'] == '169' || $item['color_id'] == '312' || $item['color_id'] == '261' || $item['color_id'] == '319')
                $optcolor = '#FFF';

            $bgcolor = '#' . $this->by_id[$item['color_id']]['hex'];
            $values[] = array('6:' . $item['id'], $item['label'], 'attr' => ' style="background-color: ' . $bgcolor . '; color: ' . $optcolor . ';"');

            if ($selectedColor == '6:' . $item['id'])
                $sColor = $i;
            $i++;

        }

        return tpt_html::createSelect($vars, '', $values, $sColor, ' title="' . $title . '" id="_bandcolor_select" onfocus="removeClass(this, \'invalid_field\');" onchange="update_color1(this);"');
    }

    function Create_Combined_Solids_Select(&$vars, $selectedColor, $stock_items_ids, $suggested_items_ids, $suggested_items_labels, $cCat, $title = 'Select Solid Band Color...', $messageSelect = false)
    {
        $values = array();
        //var_dump($stvals);die();

        $sColor = 0;
        $i = 1;
        $values[] = array(0, $title);
        foreach ($stock_items_ids as $key => $item) {

            $optcolor = '#000';
            if ($item['color_id'] == '1107' || $item['color_id'] == '1146' || $item['color_id'] == '1145' || $item['color_id'] == '1144' || $item['color_id'] == '1134' || $item['color_id'] == '1128' || $item['color_id'] == '1093' || $item['color_id'] == '169' || $item['color_id'] == '312' || $item['color_id'] == '261' || $item['color_id'] == '319')
                $optcolor = '#FFF';

            $bgcolor = '#' . $this->by_id[$item['color_id']]['hex'];
            $values[] = array('6:' . $item['id'], '*' . $item['label'], 'attr' => ' style="background-color: ' . $bgcolor . '; color: ' . $optcolor . ';"');

            if ($selectedColor == '6:' . $item['id'])
                $sColor = $i;
            $i++;


            if (isset($suggested_items_labels[$item['label']])) {
                $removeid = $suggested_items_labels[$item['label']]['id'];
                unset($suggested_items_ids[$removeid]);
            }
        }

        $suggested_items_ids = array_filter($suggested_items_ids);

        foreach ($suggested_items_ids as $key => $item) {
            $optcolor = '#000';
            $whiteopts = array(
                '1152',
                '1146',
                '1145',
                '1144',
                '1134',
                '1133',
                '1128',
                '1107',
                '1109',
                '1114',
                '1135',
                '1143',
                '1149',
                '1150',
                '1151',
                '1153',
                '1154',
                '1093',
                '916',
                '596',
                '433',
                '319',
                '312',
                '304',
                '295',
                '261',
                '223',
                '169',
                '160',
                '139',
                '137',
                '130',
                '150',
                '162',
                '287',
                '308',
                '423',
                '440',
                '517',
                '558',
                '574',
                '575',
                '648',
                '86',
                '127',
            );
            if (in_array($item['color_id'], $whiteopts))
                $optcolor = '#FFF';

            $bgcolor = '#' . $this->by_id[$item['color_id']]['hex'];
            $values[] = array($cCat . ':' . $item['id'], $item['label'], 'attr' => ' style="background-color: ' . $bgcolor . '; color: ' . $optcolor . ';"');

            if ($selectedColor == $cCat . ':' . $item['id'])
                $sColor = $i;
            $i++;
        }

        if (!$messageSelect) {
            return tpt_html::createSelect($vars, '', $values, $sColor, ' title="' . $title . '" id="_bandcolor_select" onfocus="removeClass(this, \'invalid_field\');" onchange="update_color1(this);"');
        } else {
            return tpt_html::createSelect($vars, '', $values, $sColor, ' id="message_color_select" onfocus="removeClass(this, \'invalid_field\');" onchange="update_message_color(this);" title="' . $title . '"');
        }
    }


    function Create_Combined_Solids_Select2(&$vars, $selectedColor, $stock_items_ids, $cCat, $prefix = false, $stockonly = false, $title = 'Select Solid Band Color...')
    {
        $values = array();
        //var_dump($stvals);die();

        $sColor = 0;
        $i = 1;
        $values[] = array(0, $title);
        foreach ($stock_items_ids as $key => $item) {
            if ($stockonly && !$item['stock'])
                continue;

            $optcolor = '#000';
            $whiteopts = array(
                '1152',
                '1146',
                '1145',
                '1144',
                '1134',
                '1133',
                '1128',
                '1107',
                '1109',
                '1114',
                '1135',
                '1143',
                '1149',
                '1150',
                '1151',
                '1153',
                '1154',
                '1093',
                '916',
                '596',
                '433',
                '319',
                '312',
                '304',
                '295',
                '261',
                '223',
                '169',
                '160',
                '139',
                '137',
                '130',
                '150',
                '162',
                '287',
                '308',
                '423',
                '440',
                '517',
                '558',
                '574',
                '575',
                '648',
                '86',
                '127',
            );
            if (in_array($item['color_id'], $whiteopts))
                $optcolor = '#FFF';

            //$hexid =
            //var_dump($item['color_id']);
            $bgcolor = '#' . $this->by_id[$item['color_id']]['hex'];

            $p = '';
            if ($prefix && $item['stock'])
                $p = '*';

            $ct = $cCat . ':';
            if ($item['stock'])
                $ct = '6:';

            $values[] = array($ct . $item['id'], $p . $item['label'], 'attr' => ' style="background-color: ' . $bgcolor . '; color: ' . $optcolor . ';"');

            if ($selectedColor == $ct . $item['id'])
                $sColor = $i;
            $i++;


            //if(isset($suggested_items_labels[$item['label']])) {
            //    $removeid = $suggested_items_labels[$item['label']]['id'];
            //    unset($suggested_items_ids[$removeid]);
            //}
        }

        //var_dump($values);die();

        //$suggested_items_ids = array_filter($suggested_items_ids);

        /*
			foreach($suggested_items_ids as $key=>$item) {
				$optcolor = '#000';
				if($item['color_id']=='1107' || $item['color_id']=='1146' || $item['color_id']=='1145' || $item['color_id']=='1144' || $item['color_id']=='1134' || $item['color_id']=='1128' || $item['color_id']=='1093' || $item['color_id']=='169' || $item['color_id']=='312' || $item['color_id']=='261' || $item['color_id']=='319')
					$optcolor = '#FFF';

				$bgcolor = '#'.$this->by_id[$item['color_id']]['hex'];
				$values[] = array($cCat.':'.$item['id'], $item['label'], 'attr'=>' style="background-color: '.$bgcolor.'; color: '.$optcolor.';"');

				if($selectedColor == $cCat.':'.$item['id'])
					$sColor = $i;
				$i++;
			}
			*/

        //if(!$messageSelect) {
        return tpt_html::createSelect($vars, '', $values, $sColor, ' title="' . $title . '" id="_bandcolor_select" onfocus="removeClass(this, \'invalid_field\');" onchange="update_color1(this);"');
        //} else {
        //    return tpt_html::createSelect($vars, '', $values, $sColor, ' id="message_color_select" onfocus="removeClass(this, \'invalid_field\');" onchange="document.getElementById(\'tpt_pg_msgcolor\').value = document.getElementById(\'message_color_select\').options[document.getElementById(\'message_color_select\').selectedIndex].value;_short_tpt_pg_generate_prevew_all();" title="'.$title.'"');
        //}
    }


    function Get_Selected_Color(&$vars, $pgconf)
    {
        $types_module = getModule($vars, "BandType");
        $data_module = getModule($vars, "BandData");

        extract($pgconf);
        $type = $pgType;
        $style = $pgStyle;
        $dTypeArr = $data_module->typeStyle[$type][$style];
        if (!empty($dTypeArr['writable'])) {
            $type = $dTypeArr['base_type'];
        }


        $sColorProps = $this->getColorProps($vars, $pgBandColor);
        if ((($style == 7) && empty($sColorProps['dual_layer'])) || (!empty($dTypeArr['pricing_type']) && !empty($sColorProps['custom_color'])) || (($style != 7) && !empty($sColorProps['dual_layer']))) {
            self::$pgBandColor = '-1:' . DEFAULT_BAND_COLOR;
        } else {
            self::$pgBandColor = $pgBandColor;
        }

        return self::$pgBandColor;
    }

    function Get_Builder_Color_Types(&$vars, $pgconf, $builder)
    {
        $types_module = getModule($vars, "BandType");
        $data_module = getModule($vars, "BandData");

        extract($pgconf);
        $type = $pgType;
        $style = $pgStyle;
        $dTypeArr = $data_module->typeStyle[$type][$style];
        if (!empty($dTypeArr['writable'])) {
            $type = $dTypeArr['base_type'];
        }
        $dType = $data_module->typeStyle[$type][$style]['id'];
        $dIHType = $dType;


        $tfield = 'available_types_ids2';
        $dfield = 'disabled_types_ids2';

        /*
		$colorTypes = array();
		$colorSelects = array();
		$solid_select = '';
		$swirl_select = '';
		$segmented_select = '';
		$multicolored_select = '';
		$glitter_select = '';
		$glow_select = '';
		$duallayer_select = '';
		$multicolored_select = '';

		$colorTypes['1'] = array('id'=>'1', 'label'=>'Solid', 'name'=>'solid', 'attr'=>'');
		$colorSelects['1'] = $solid_select;
		$colorTypes['2'] = array('id'=>'2', 'label'=>'Swirl', 'name'=>'swirl', 'attr'=>'');
		$colorSelects['2'] = $swirl_select;
		$colorTypes['3'] = array('id'=>'3', 'label'=>'Segmented', 'name'=>'segmented', 'attr'=>'');
		$colorSelects['3'] = $segmented_select;
		$colorTypes['5'] = array('id'=>'5', 'label'=>'Multicolored', 'name'=>'multic', 'attr'=>'');
		$colorSelects['5'] = $multicolored_select;
		$colorTypes['6'] = array('id'=>'6', 'label'=>'Glitter', 'name'=>'glitter', 'attr'=>'');
		$colorSelects['6'] = $glitter_select;
		$colorTypes['7'] = array('id'=>'7', 'label'=>'Glow', 'name'=>'glow', 'attr'=>'');
		$colorSelects['7'] = $glow_select;
		$colorTypes['8'] = array('id'=>'8', 'label'=>'Powder Coated', 'name'=>'powdercoat', 'attr'=>'');
		$colorSelects['8'] = $powdercoat_select;
		$colorTypes['9'] = array('id'=>'9', 'label'=>'Edge', 'name'=>'notched', 'attr'=>'');
		$colorSelects['9'] = $notched_select;
		*/

        $iCCat = intval($cCat);

        $sCCat = array();
        if ($style != 7) {
            if ($builder['inhouse'] || ($type == 5)) {
                $sCCat[] = 'solidstock2';
            } else {
                $sCCat[] = 'solidstock1';
            }

            if (($dTypeArr['type'] != 5) && (empty($dTypeArr['writable']) || ($dTypeArr['base_type'] != 5))) {
                if (!$builder['inhouse']) {
                    $sCCat[] = 'overseasswirl';

                    if (empty($dTypeArr['writable'])) {
                        $sCCat[] = 'overseassegmented';
                    }

                    $sCCat[] = 'overseasglitter';
                    $sCCat[] = 'overseasglow';
                } else {
                    $sCCat[] = 'inhousemulticolor';
                    $sCCat[] = 'inhouseglitter';
                    $sCCat[] = 'inhouseglow';
                }
            } else {
                if (empty($dTypeArr['writable'])) {
                    $sCCat[] = 'slapbandmulticolor1';
                } else {
                    $sCCat[] = 'slapbandmulticolor2';
                }
                $sCCat[] = 'inhouseglitter';
                $sCCat[] = 'inhouseglow';
            }
        } else {
            $sCCat[] = 'duallayersolid';
            if ($type == 5) {
                $sCCat[] = 'duallayermulticolor1';
                $sCCat[] = 'duallayerglitter1';
                $sCCat[] = 'duallayerglow1';
            } else {
                $sCCat[] = 'duallayermulticolor2';
                $sCCat[] = 'duallayerglitter2';
                $sCCat[] = 'duallayerglow2';
            }
            $sCCat[] = 'duallayerpowdercoat';
            $sCCat[] = 'duallayeredge';
        }

        return $sCCat;
    }

    function Color_Select_Control(&$vars, $sCCat, $pgconf, $selectedColor, $builder)
    {
        $types_module = getModule($vars, "BandType");
        $data_module = getModule($vars, "BandData");

        extract($pgconf);
        $type = $pgType;
        $style = $pgStyle;

        $control = '';
        switch ($sCCat) {
            case 'solidstock1':
                $items = array();
                include($this->queriesDir . DIRECTORY_SEPARATOR . 'solidstock.php');

                $control = $this->Create_Combined_Solids_Select2($vars, $selectedColor, $items, '3', true, false);
                break;

            case 'solidstock2':
                $items = array();
                include($this->queriesDir . DIRECTORY_SEPARATOR . 'solidstock.php');

                $control = $this->Create_Combined_Solids_Select2($vars, $selectedColor, $items, '3', false, true);
                break;

            case 'overseasswirl':
                $items = array();
                include($this->queriesDir . DIRECTORY_SEPARATOR . 'overseasswirl.php');

                $control = $this->Create_Combined_Select2($vars, $selectedColor, $items, ($style == 1), false, 'Select Swirl Band Color...');
                break;

            case 'overseassegmented':
                $items = array();
                include($this->queriesDir . DIRECTORY_SEPARATOR . 'overseassegmented.php');

                $control = $this->Create_Combined_Select2($vars, $selectedColor, $items, ($style == 1), false, 'Select Segmented Band Color...');
                break;

            case 'overseasglitter':
                $items = array();
                include($this->queriesDir . DIRECTORY_SEPARATOR . 'overseasglitter.php');

                $control = $this->Create_Combined_Select2($vars, $selectedColor, $items, ($style == 1), false, 'Select Band Color (/w Glitter)...');
                break;

            case 'overseasglow':
                $items = array();
                include($this->queriesDir . DIRECTORY_SEPARATOR . 'overseasglow.php');

                $control = $this->Create_Combined_Select2($vars, $selectedColor, $items, ($style == 1), false, 'Select Band Color (/w Glow in the Dark)...');
                break;

            case 'inhousemulticolor':
                $items = array();
                include($this->queriesDir . DIRECTORY_SEPARATOR . 'inhousemulticolor.php');

                $control = $this->Create_Stock_Select($vars, $selectedColor, $items, '6', 'Select Swirl/Segmented Band Color...', (!$builder['inhouse'] && in_array($style, array(1, 6))));
                break;

            case 'inhouseglitter':
                $items = array();
                include($this->queriesDir . DIRECTORY_SEPARATOR . 'inhouseglitter.php');

                $control = $this->Create_Stock_Select($vars, $selectedColor, $items, '6', 'Select Band Color (/w Glitter)...', (!$builder['inhouse'] && in_array($style, array(1, 6))));
                break;

            case 'inhouseglow':
                $items = array();
                include($this->queriesDir . DIRECTORY_SEPARATOR . 'inhouseglow.php');

                $control = $this->Create_Stock_Select($vars, $selectedColor, $items, '6', 'Select Band Color (/w Glow in the Dark)...', (!$builder['inhouse'] && in_array($style, array(1, 6))));
                break;

            case 'slapbandmulticolor1':
                $items = array();
                include($this->queriesDir . DIRECTORY_SEPARATOR . 'slapbandmulticolor.php');

                $control = $this->Create_Stock_Select($vars, $selectedColor, $items, '6', 'Select Swirl/Segmented Band Color...', (!$builder['inhouse'] && in_array($style, array(1, 6))), true);
                break;

            case 'slapbandmulticolor2':
                $items = array();
                include($this->queriesDir . DIRECTORY_SEPARATOR . 'slapbandmulticolor.php');

                $control = $this->Create_Stock_Select($vars, $selectedColor, $items, '6', 'Select Swirl Band Color...', (!$builder['inhouse'] && in_array($style, array(1, 6))), true);
                break;

            case 'duallayersolid':
                $items = array();
                include($this->queriesDir . DIRECTORY_SEPARATOR . 'duallayersolid.php');

                $control = $this->Create_Stock_Select($vars, $selectedColor, $items, '10', 'Select Dual Layer Solids Color Set...', false, true, true);
                break;

            case 'duallayermulticolor1':
                $items = array();
                include($this->queriesDir . DIRECTORY_SEPARATOR . 'duallayermulticolor.php');

                $control = $this->Create_Stock_Select($vars, $selectedColor, $items, '10', 'Select Dual Layer Color Set (/w Multicolored Msg)...', false, true, true);
                break;

            case 'duallayermulticolor2':
                $items = array();
                include($this->queriesDir . DIRECTORY_SEPARATOR . 'duallayermulticolor.php');

                $control = $this->Create_Stock_Select($vars, $selectedColor, $items, '10', 'Select Dual Layer Color Set (/w Multicolored Msg)...', false, true, true);
                break;

            case 'duallayerglitter1':
                $items = array();
                include($this->queriesDir . DIRECTORY_SEPARATOR . 'duallayerglitter.php');

                $control = $this->Create_Stock_Select($vars, $selectedColor, $items, '10', 'Select Dual Layer Color Set (/w Glitter)...', false, false, true);
                break;

            case 'duallayerglitter2':
                $items = array();
                include($this->queriesDir . DIRECTORY_SEPARATOR . 'duallayerglitter.php');

                $control = $this->Create_Stock_Select($vars, $selectedColor, $items, '10', 'Select Dual Layer Color Set (/w Glitter)...', false, false, true);
                break;

            case 'duallayerglow1':
                $items = array();
                include($this->queriesDir . DIRECTORY_SEPARATOR . 'duallayerglow.php');

                $control = $this->Create_Stock_Select($vars, $selectedColor, $items, '10', 'Select Dual Layer Color Set (/w Glow Message)...', false, true, true);
                break;

            case 'duallayerglow2':
                $items = array();
                include($this->queriesDir . DIRECTORY_SEPARATOR . 'duallayerglow.php');

                $control = $this->Create_Stock_Select($vars, $selectedColor, $items, '10', 'Select Dual Layer Color Set (/w Glow Message)...', false, true, true);
                break;

            case 'duallayerpowdercoat':
                $items = array();
                include($this->queriesDir . DIRECTORY_SEPARATOR . 'duallayerpowdercoat.php');

                $control = $this->Create_Stock_Select($vars, $selectedColor, $items, '10', 'Select Dual Layer Color Set (Powder Coated)...', false, true, true);
                break;

            case 'duallayeredge':
                $items = array();
                include($this->queriesDir . DIRECTORY_SEPARATOR . 'duallayeredge.php');

                $control = $this->Create_Stock_Select($vars, $selectedColor, $items, '10', 'Select Dual Layer - Edge Color Set...', false, true, true);
                break;


        }

        return $control;
    }

    function Get_Checked_Color_Type_Radio_Index(&$vars, $pgconf, $builder, $sColorType = 0)
    {
        $sColor = $this->Get_Selected_Color($vars, $pgconf);


        $checkedRadio = 1;
        //var_dump($sColorType);die();
        if (!empty($sColorType)) {
            $checkedRadio = $sColorType;
        } else {
            $checkedRadio = $this->BandColor_ColorType($vars, $sColor, $type, $style, $builder);
            $sColorType = $checkedRadio;
        }

        return $checkedRadio;
    }

    function Get_Builder_Color_Type_Radio(&$vars, $radio, $checkedIndex)
    {
        $ajax_call = tpt_ajax::getCall('color.change_color_type');

        $activecls = '';
        if ($checkedIndex == $radio['id']) {
            $activecls = 'active';
        }

        $label = '<label class="amz_brown font-size-14 font-weight-bold" style="text-shadow: 2px 1px rgba(32, 32, 32, 0.4); font-family: Arial, Helvetica, sans-serif;" for="' . $radio['name'] . '_colors">' . $radio['label'] . '</label> ';
        $radio = tpt_html::createRadiobutton($vars, 'color_type'/*name*/, $radio['id']/*control value*/, $checkedRadio/*checked value*/, ' onclick="var chld = getChildElements(this.parentNode.parentNode.parentNode);for(var i=0, _len=chld.length; i<_len; i++){removeClass(chld[i], \'active\');}addClass(this.parentNode.parentNode, \'active\');' . $ajax_call . '" id="' . $radio['name'] . '_colors"'/*html attribs*/, ''/*oncheck*/);
        $control = <<< EOT
<div class="padding-top-2 padding-right-2 padding-bottom-2 padding-left-2 display-inline-block colortype-radio $activecls" style="">
<div class="white-space-nowrap display-inline-block color-white padding-left-4 padding-right-4 padding-top-2 padding-bottom-2" style="border-radius: 20px;">
$label$radio
</div>
</div>
EOT;
        return $control;
    }

    function Get_Builder_Color_Type_Radios(&$vars, $pgconf, $builder, $sColorType = 0)
    {
        $sCCats = $this->Get_Builder_Color_Types($vars, $pgconf, $builder);
        $sCCRIndex = $this->Get_Checked_Color_Type_Radio_Index($vars, $pgconf, $builder, $sColorType = 0);


        extract($pgconf);
        $type = $pgType;
        $style = $pgStyle;


        $colorRadios = array();

        foreach ($sCCats as $sCCat) {
            $this->Get_Builder_Color_Types($vars, $pgconf, $builder);

            $colorRadios[$key] = $control;
        }
    }

    function Create_Stock_Select(&$vars, $selectedColor, $stock_items_ids, $cCat, $title = 'Select Swirl Band Color...', $pref = false, $sssuf = false, $dualSelect = false)
    {
        //$solid = $items;
        //$solid_id = array_search($this->solid, $this->all_colors);

        $values = array();
        //var_dump($stvals);die();

        $sColor = 0;
        $i = 1;
        $values[] = array(0, $title);
        $pf = '';
        if ($pref) {
            $pf = '*';
        }
        foreach ($stock_items_ids as $key => $item) {
            $suf = '';
            //$cProps = $this->getColorProps($vars, $cCat.':'.$item['id']);
            if ($sssuf) {
                if (($item['color_type'] == 4) && (strstr(strtolower($item['label']), 'swirl') === false)) {
                    $suf = ' Swirl';
                }

                if (($item['color_type'] == 5) && (strstr(strtolower($item['label']), 'segment') === false)) {
                    $suf = ' Segmented';
                }
            }

            //$optcolor = '#000';
            //if($item['color_id']=='1107' || $item['color_id']=='1146' || $item['color_id']=='1145' || $item['color_id']=='1144' || $item['color_id']=='1134' || $item['color_id']=='1128' || $item['color_id']=='1093' || $item['color_id']=='169' || $item['color_id']=='312' || $item['color_id']=='261' || $item['color_id']=='319')
            //    $optcolor = '#FFF';

            //$bgcolor = '#'.$this->by_id[$item['color_id']]['hex'];
            $values[] = array($cCat . ':' . $item['id'], $pf . $item['label'] . $suf, 'attr' => ' style="background-color: white; color: black;"');

            if ($selectedColor == $cCat . ':' . $item['id'])
                $sColor = $i;
            $i++;

        }

        if (!$dualSelect) {
            return tpt_html::createSelect($vars, '', $values, $sColor, ' title="' . $title . '" id="_bandcolor_select" onfocus="removeClass(this, \'invalid_field\');" onchange="update_color1(this);"');
        } else {
            return tpt_html::createSelect($vars, '', $values, $sColor, ' title="' . $title . '" id="_bandcolor_select" onfocus="removeClass(this, \'invalid_field\');" onchange="update_color2(this);"');
        }
    }

    function Create_Combined_Select(&$vars, $selectedColor, $stock_items_ids, $suggested_items_ids, $suggested_items_labels, $cCat, $title = 'Select Swirl Band Color...')
    {
        $values = array();
        //var_dump($stvals);die();

        $sColor = 0;
        $i = 1;
        $values[] = array(0, $title);
        foreach ($stock_items_ids as $key => $item) {
            //$optcolor = '#000';
            //if($item['color_id']=='1107')
            //    $optcolor = '#FFF';

            //$bgcolor = '#'.$this->by_id[$item['color_id']]['hex'];
            $values[] = array('6:' . $item['id'], '*' . $item['label'], 'attr' => ' style="background-color: white; color: black;"');

            if ($selectedColor == '6:' . $item['id'])
                $sColor = $i;
            $i++;

            if (isset($suggested_items_labels[$item['label']])) {
                $removeid = $suggested_items_labels[$item['label']]['id'];
                unset($suggested_items_ids[$removeid]);
            }
        }

        $suggested_items_ids = array_filter($suggested_items_ids);

        foreach ($suggested_items_ids as $key => $item) {
            //$optcolor = '#000';
            //if($item['color_id']=='1107' || $item['color_id']=='1146' || $item['color_id']=='1145' || $item['color_id']=='1144' || $item['color_id']=='1134' || $item['color_id']=='1128' || $item['color_id']=='1093' || $item['color_id']=='169' || $item['color_id']=='312' || $item['color_id']=='261' || $item['color_id']=='319')
            //    $optcolor = '#FFF';

            //$bgcolor = '#'.$this->by_id[$item['color_id']]['hex'];
            $values[] = array($cCat . ':' . $item['id'], $item['label'], 'attr' => ' style="background-color: white; color: black;"');

            if ($selectedColor == $cCat . ':' . $item['id'])
                $sColor = $i;
            $i++;
        }


        return tpt_html::createSelect($vars, '', $values, $sColor, ' title="' . $title . '" id="_bandcolor_select" onfocus="removeClass(this, \'invalid_field\');" onchange="update_color1(this);"');
    }


    function Create_Combined_Select2(&$vars, $selectedColor, $stock_items_ids, $prefix = false, $stockonly = false, $title = 'Select Swirl Band Color...')
    {
        $values = array();
        //var_dump($stvals);die();

        $sColor = 0;
        $i = 1;
        $values[] = array(0, $title);
        foreach ($stock_items_ids as $key => $item) {
            if ($stockonly && !$item['stock'])
                continue;

            //$optcolor = '#000';
            //if($item['color_id']=='1107' || $item['color_id']=='1146' || $item['color_id']=='1145' || $item['color_id']=='1144' || $item['color_id']=='1134' || $item['color_id']=='1128' || $item['color_id']=='1093' || $item['color_id']=='169' || $item['color_id']=='312' || $item['color_id']=='261' || $item['color_id']=='319')
            //    $optcolor = '#FFF';

            //$hexid =
            //var_dump($item['color_id']);
            $bgcolor = '#FFF';
            if (strstr($item['color_id'], ',') === false) {
                $bgcolor = '#' . $this->by_id[$item['color_id']]['hex'];
            }

            $p = '';
            if ($prefix && !empty($item['stock']))
                $p = '*';


            $ct = $item['color_type'] . ':';
            if (!empty($item['stock']))
                $ct = '6:';

            $values[] = array($ct . $item['id'], $p . $item['label']);

            if ($selectedColor == $ct . $item['id'])
                $sColor = $i;
            $i++;


            //if(isset($suggested_items_labels[$item['label']])) {
            //    $removeid = $suggested_items_labels[$item['label']]['id'];
            //    unset($suggested_items_ids[$removeid]);
            //}
        }

        //$suggested_items_ids = array_filter($suggested_items_ids);

        /*
			foreach($suggested_items_ids as $key=>$item) {
				//$optcolor = '#000';
				//if($item['color_id']=='1107' || $item['color_id']=='1146' || $item['color_id']=='1145' || $item['color_id']=='1144' || $item['color_id']=='1134' || $item['color_id']=='1128' || $item['color_id']=='1093' || $item['color_id']=='169' || $item['color_id']=='312' || $item['color_id']=='261' || $item['color_id']=='319')
				//    $optcolor = '#FFF';

				//$bgcolor = '#'.$this->by_id[$item['color_id']]['hex'];
				$values[] = array($cCat.':'.$item['id'], $item['label'], 'attr'=>' style="background-color: white; color: black;"');

				if($selectedColor == $cCat.':'.$item['id'])
					$sColor = $i;
				$i++;
			}
			*/


        return tpt_html::createSelect($vars, '', $values, $sColor, ' title="' . $title . '" id="_bandcolor_select" onfocus="removeClass(this, \'invalid_field\');" onchange="update_color1(this);"');
    }


    function getColorTableDataByName(&$vars, $table)
    {
        return $vars['db']['handler']->getData($vars, $table, '*', '', 'label', false);
    }


    function getColorIdByName(&$vars, $sterm, $colortype = 'solid')
    {
        $colortype = strtolower(preg_replace('#.*(solid|swirl|segment|dual).*#', '$1', $colortype));
        $terms = array();
        if (strstr($sterm, '/') !== false) {
            $terms = preg_split('#[\s]*/[\s]*#', $sterm);
        }
        if (empty($terms)) {
            $terms = array($term);
        }
        foreach ($terms as $term) {
            if (strstr($term, ' ') !== false) {
                $tex = preg_split('#[\s]+#', $term);
                $sterm = preg_replace('', '', $term);
            } else if (preg_match('##', $term)) {
            }
        }

        switch ($colortype) {
            case 'solid':
                $query = <<< EOT
(SELECT 3 AS `table_id`,`id`, CONCAT("3:", `id`) AS `universal_id`,`label`, `color_type`,`color_id`, "" AS `message_color_id`, 0 AS `glow`, 0 AS `glitter`, 0 AS `uv`,`enabled`, "" AS `available_types_ids`, 0 as `stock`, "tpt_color_overseas" AS `tbl` FROM `tpt_color_overseas` WHERE `color_type`=3 AND `label`="$sterm")
EOT;
                break;
            case 'swirl':
                $query = <<< EOT
(SELECT 4 AS `table_id`,`id`, CONCAT("4:", `id`) AS `universal_id`,`label`, `color_type`,`color_id`, "" AS `message_color_id`, 0 AS `glow`, 0 AS `glitter`, 0 AS `uv`,`enabled`, "" AS `available_types_ids`, 0 as `stock`, "tpt_color_overseas" AS `tbl` FROM `tpt_color_overseas` WHERE `color_type`=4 AND `label`="$sterm")
EOT;
                break;
            case 'segment':
                $query = <<< EOT
(SELECT 5 AS `table_id`,`id`, CONCAT("5:", `id`) AS `universal_id`,`label`, `color_type`,`color_id`, "" AS `message_color_id`, 0 AS `glow`, 0 AS `glitter`, 0 AS `uv`,`enabled`, "" AS `available_types_ids`, 0 as `stock`, "tpt_color_overseas" AS `tbl` FROM `tpt_color_overseas` WHERE `color_type`=5 AND `label`="$sterm")
EOT;
                break;
            case 'dual':
                $query = <<< EOT
(SELECT 10 AS `table_id`,`id`, CONCAT("10:", `id`) AS `universal_id`,`label`,`color_type`,`color_id`,`message_color_id`,`glow`,`glitter`,`uv`,`enabled`,`available_types_ids`, 1 as `stock`, "tpt_color_duallayer" AS `tbl` FROM `tpt_color_duallayer` WHERE `label`="$sterm")
EOT;
                break;
            default:
                $query = <<< EOT
(SELECT 3 AS `table_id`,`id`, CONCAT("3:", `id`) AS `universal_id`,`label`, `color_type`,`color_id`, "" AS `message_color_id`, 0 AS `glow`, 0 AS `glitter`, 0 AS `uv`,`enabled`, "" AS `available_types_ids`, 0 as `stock`, "tpt_color_overseas" AS `tbl` FROM `tpt_color_overseas` WHERE `color_type`=3 AND `label`="$sterm")
UNION
(SELECT 4 AS `table_id`,`id`, CONCAT("4:", `id`) AS `universal_id`,`label`, `color_type`,`color_id`, "" AS `message_color_id`, 0 AS `glow`, 0 AS `glitter`, 0 AS `uv`,`enabled`, "" AS `available_types_ids`, 0 as `stock`, "tpt_color_overseas" AS `tbl` FROM `tpt_color_overseas` WHERE `color_type`=4 AND `label`="$sterm")
UNION
(SELECT 5 AS `table_id`,`id`, CONCAT("5:", `id`) AS `universal_id`,`label`, `color_type`,`color_id`, "" AS `message_color_id`, 0 AS `glow`, 0 AS `glitter`, 0 AS `uv`,`enabled`, "" AS `available_types_ids`, 0 as `stock`, "tpt_color_overseas" AS `tbl` FROM `tpt_color_overseas` WHERE `color_type`=5 AND `label`="$sterm")
UNION
(SELECT 6 AS `table_id`,`id`, CONCAT("6:", `id`) AS `universal_id`,`label`,`color_type`,`color_id`,`message_color_id`,`glow`,`glitter`,`uv`,`enabled`,`available_types_ids`, 1 as `stock`, "tpt_color_special" AS `tbl` FROM `tpt_color_special` WHERE `label`="$sterm")
UNION
(SELECT 10 AS `table_id`,`id`, CONCAT("10:", `id`) AS `universal_id`,`label`,`color_type`,`color_id`,`message_color_id`,`glow`,`glitter`,`uv`,`enabled`,`available_types_ids`, 1 as `stock`, "tpt_color_duallayer" AS `tbl` FROM `tpt_color_duallayer` WHERE `label`="$sterm")
EOT;
                break;
        }
    }

    function getSPTypeColorLabel(&$vars, $color, $sptcolors)
    {


        $color_definitions = explode('|', $sptcolors);
        $cdefs = array();
        foreach ($color_definitions as $cdef) {
            $pcdef = explode('^', $cdef);
            $cdefs[$pcdef[1]] = array('value' => $pcdef[0], 'label' => $pcdef[2]);
        }

        return $cdefs[$color]['label'];
    }


}


function getHexField($v, $w)
{
    if (!is_array($v))
        $v = array();

    //var_dump($w);die();
    $v[] = $w['hex'];

    return $v;
}

?>
