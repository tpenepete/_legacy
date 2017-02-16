<?php
//templay loader check
defined('TPT_INIT') or die('access denied');

//this class must be instantiated with the 'new tpt_pricing(...)` directive before using the funtions
class amz_pricing {
    // class-wise data vars
    static $sz = array('xs', 'sm', 'm', 'lg', 'xl');

    public $pricing_data = array();
    public $options_pricing_data = array();
    // end class-wise data vars

    //general parameters
    public $type = 0;
    public $style = 0;


    public $qty = array();

    public $options = array();

    public $final_total = null;
    
    public $final_mold = 0;
    public $final_screen = 0;
    public $final_admin = 0;
    public $discount = 0;
    //end general parameters


	public $tcosts = array();
    public $pricingTable;
    public $pricingType;
    public $optionsPricingTable;
    public $options_pricing_row;

    public $sizes = 0;
    public $minQTY = 1;
    public $qtyCheck = array();
    public $total_qty = 0;




    // each function in the class (except the constructor) provides an array with data which is cached in the object
    // these are the pricing components for the overseas products, most of those don't get used for in-house pricing
    public $weights = array();


            public $tcost = array();

            public $mfgcost = array();
            public $optcost = array();
            public $mold = array();
            public $screen = array();

        public $subtotal = array();

            public $euss = array();

            public $ccfee = array();

    public $total = array();


    public $price = array();

    public $profit = array();




    public $html = array();


    //this class must be instantiated with the 'new tpt_pricing(...)' directive before using the functions
    function __construct(&$vars, $type, $style, $qty, $options=array(), $discount=0, $final_total=null) {
        //tpt_dump($options,true);
		$data_module = getModule($vars, 'BandData');
		$types_module = getModule($vars, 'BandType');
		$types = $types_module->moduleData['id'];
		$styles_module = getModule($vars, 'BandStyle');
		$styles = $styles_module->moduleData['id'];

        $this->type = intval((empty($type)?0:$type), 10);
        $this->style = intval((empty($style)?0:$style), 10);


        $this->minQTY = !empty($data_module->typeStyle[$this->type][$this->style]['minimum_quantity'])?$data_module->typeStyle[$this->type][$this->style]['minimum_quantity']:0;
        $this->qty = array();
        if(!is_array($qty))$qty=array('lg'=>$qty);
		//tpt_dump($qty);
        if(!empty($data_module->typeStyle[$this->type][$this->style])) {
            foreach($qty as $size=>$val) {
                $this->qty[$size] = intval((empty($qty[$size])?0:$qty[$size]), 10);
                //$this->qty['xs']_im = intval((empty($qty['xs_im'])?0:$qty['xs_im']), 10);
                //$this->qty['xs']_is = intval((empty($qty['xs_is'])?0:$qty['xs_is']), 10);

                if($this->qty[$size]) {
                    $this->qtyCheck[$size] = true;
                    if($this->qty[$size] < $this->minQTY) {
                        $this->qtyCheck[$size] = false;
                    } else {
                        $this->sizes++;
                        $this->total_qty += $this->qty[$size];
                    }
                }

                $this->html['qty_'.$size] = number_format($this->qty[$size], 0);
            }
        } else {
            foreach(self::$sz as $size) {
                    $this->qty[$size] = 0;
                //$this->qty['xs']_im = intval((empty($qty['xs_im'])?0:$qty['xs_im']), 10);
                //$this->qty['xs']_is = intval((empty($qty['xs_is'])?0:$qty['xs_is']), 10);

                    $this->qtyCheck[$size] = false;

                    $this->html['qty_'.$size] = number_format($this->qty[$size], 0);
            }
        }

        //tpt_dump($options, true, '', '', true);
        $this->options = $options;
        //tpt_dump($this->options);

        $this->discount = $discount;
        
        if(!is_null($final_total))
            $this->final_total = $final_total;
        //tpt_dump($discount, true);





        if(isset($types[$this->type]) && isset($styles[$this->style]) && !empty($this->sizes) && $this->qtyCheck) {
        } else {
            return false;
        }

		$this->tcosts = $vars['db']['handler']->getData($vars, DB_DB_PRICING.'.transit_costs');
        // get db pricing table names for the chosen product
        $this->pricingTable = $data_module->typeStyle[$this->type][$this->style]['table'];
        $this->pricingType = $data_module->typeStyle[$this->type][$this->style]['pricing_type'];
        $this->optionsPricingTable = $types[$this->type]['options_table'];
        if($this->pricingType == 1) {
            $this->optionsPricingTable .= '_inhouse';
        } else {
            $this->optionsPricingTable .= '_overseas';
        }


		if(!empty($this->pricingTable)) {
			$this->pricing_data[$this->pricingTable] = $vars['db']['handler']->getData($vars, DB_DB_PRICING.'.'.$this->pricingTable);
			$this->options_pricing_data[$this->optionsPricingTable] = $vars['db']['handler']->getData($vars, DB_DB_PRICING.'.'.$this->optionsPricingTable);

			//repeat for the options pricing table
            if(!empty($this->options_pricing_data[$this->optionsPricingTable])){
                foreach($this->options_pricing_data[$this->optionsPricingTable] as $oprice) {
                    if($this->total_qty >= intval($oprice['qty'], 10)) {
                        $this->options_pricing_row =  $oprice;
                    } else {
                        break;
                    }
                }
            }
			//done getting options pricing data
		} else {
			//tpt_dump($this->type);
			//tpt_dump($this->style);
		}

        /*
        if($_SERVER['REMOTE_ADDR'] == '109.160.0.218') {
            //var_dump($price_modifiers);die();
            var_dump($this->pricing_data[$this->pricingTable]);//die();
            var_dump($this->options_pricing_data[$this->optionsPricingTable]);//die();
            var_dump($this->pricing_data);//die();
            //var_dump($discount);//die();
            var_dump($this->options_pricing_data);die();
        }
        */


        $this->html['typeName'] = $types[$this->type]['name'];
        $this->html['styleName'] = $styles[$this->style]['name'];
    }


    function regenerate(&$vars, $type=null, $style=null, $qty=null, $options=null, $discount=null, $final_total=null) {
		$data_module = getModule($vars, 'BandData');
		$types_module = getModule($vars, 'BandType');
		$types = $types_module->moduleData['id'];
		$styles_module = getModule($vars, 'BandStyle');
		$styles = $styles_module->moduleData['id'];

        //echo 'hi';
        //var_dump($options);die();
        $this->weights = array();


                $this->tcost = array();

                $this->mfgcost = array();
                $this->optcost = array();
                $this->mold = array();
                $this->screen = array();

            $this->subtotal = array();

                $this->euss = array();

                $this->ccfee = array();

        $this->total = array();


        $this->price = array();

        $this->profit = array();



        $this->type = intval((is_null($type)?$this->type:$type), 10);
        $this->style = intval((is_null($style)?$this->style:$style), 10);
		if(empty($this->tcosts)) {
			$this->tcosts = $vars['db']['handler']->getData($vars, DB_DB_PRICING.'.transit_costs');
		}

        //var_dump($qty);die();

        if(!is_null($qty)) {
            $this->total_qty = 0;

            $this->minQTY = $data_module->typeStyle[$this->type][$this->style]['minimum_quantity'];
            $this->qty = array();
            if(!is_array($qty))$qty=array('lg'=>$qty);
            if(!empty($data_module->typeStyle[$this->type][$this->style])) {
                foreach(self::$sz as $size) {
                    $this->qty[$size] = intval((empty($qty[$size])?0:$qty[$size]), 10);
                    //$this->qty['xs']_im = intval((empty($qty['xs_im'])?0:$qty['xs_im']), 10);
                    //$this->qty['xs']_is = intval((empty($qty['xs_is'])?0:$qty['xs_is']), 10);

                    if($this->qty[$size]) {
                        $this->qtyCheck[$size] = true;
                        if($this->qty[$size] < $this->minQTY) {
                            $this->qtyCheck[$size] = false;
                        } else {
                            $this->sizes++;
                            $this->total_qty += $this->qty[$size];
                        }
                    }

                    $this->html['qty_'.$size] = number_format($this->qty[$size], 0);
                }
            } else {
                foreach(self::$sz as $size) {
                        $this->qty[$size] = 0;
                    //$this->qty['xs']_im = intval((empty($qty['xs_im'])?0:$qty['xs_im']), 10);
                    //$this->qty['xs']_is = intval((empty($qty['xs_is'])?0:$qty['xs_is']), 10);

                        $this->qtyCheck[$size] = false;

                        $this->html['qty_'.$size] = number_format($this->qty[$size], 0);
                }
            }
        }

        if(!is_null($options))
            $this->options = $options;

        if(!is_null($discount))
            $this->discount = $discount;
            
        if(!is_null($final_total))
            $this->final_total = $final_total;






        if(isset($types[$this->type]) && isset($styles[$this->style]) && !empty($this->sizes) && $this->qtyCheck) {
        } else {
            return false;
        }

        // get db pricing table names for the chosen product
        $this->pricingTable = $data_module->typeStyle[$this->type][$this->style]['table'];
        $this->pricingType = $data_module->typeStyle[$this->type][$this->style]['pricing_type'];
        $this->optionsPricingTable = $types[$this->type]['options_table'];
        if($this->pricingType == 1) {
            $this->optionsPricingTable .= '_inhouse';
        } else {
            $this->optionsPricingTable .= '_overseas';
        }

        $this->pricing_data[$this->pricingTable] = $vars['db']['handler']->getData($vars, DB_DB_PRICING.'.'.$this->pricingTable);
        $this->options_pricing_data[$this->optionsPricingTable] = $vars['db']['handler']->getData($vars, DB_DB_PRICING.'.'.$this->optionsPricingTable);



        //repeat for the options pricing table
        foreach($this->options_pricing_data[$this->optionsPricingTable] as $oprice) {
            if($this->total_qty >= intval($oprice['qty'], 10)) {
                $this->options_pricing_row =  $oprice;
            } else {
                break;
            }
        }
        //var_dump($this->options_pricing_row);die();
        //done getting options pricing data

        $this->html['typeName'] = $types[$this->type]['name'];
        $this->html['styleName'] = $styles[$this->style]['name'];
    }

    function getWeights() {
        if(!empty($this->weights)) {
            return $this->weights;
        }

		global $tpt_vars;
		$data_module = getModule($tpt_vars, 'BandData');
		$types_module = getModule($tpt_vars, 'BandType');
		$types = $types_module->moduleData['id'];
		$styles_module = getModule($tpt_vars, 'BandStyle');
		$styles = $styles_module->moduleData['id'];

        if(isset($types[$this->type]) && isset($styles[$this->style]) && !empty($this->sizes) && count(array_filter($this->qtyCheck))) {
        } else {
            return false;
        }

        $html = array();
        $values = array();

        //per band weight
        $html['sOZ'] = $values['sOZ'] = round($types[$this->type]['weight'], 3);
        $html['sgr'] = $values['sgr'] = round($types[$this->type]['weight']*OUNCE_TO_GRAM, 3);
        $html['skg'] = $values['skg'] = sprintf("%.6f", round($values['sgr']*GRAM_TO_KILO, 6));
        $html['slbs'] = $values['slbs'] = round($types[$this->type]['weight']*OUNCE_TO_POUND, 3);

        //the net bands weight
        $html['mOZ'] = $values['mOZ'] = round($this->total_qty*$values['sOZ'], 3);
        $html['mgr'] = $values['mgr'] = round($this->total_qty*$values['sgr'], 3);
        $html['mkg'] = $values['mkg'] = round($this->total_qty*$values['skg'], 6);
        $html['mlbs'] = $values['mlbs'] = round($this->total_qty*$values['slbs'], 3);

        if(!empty($this->pricingType)) { // in-house weights
            $html['bags'] = $values['bags'] = 0;
            $html['boxes'] = $values['boxes'] = 0;
            $html['package_weight'] = $values['package_weight'] = 0;
            $html['packaged_weight'] = '('.$values['mOZ'].' OZ) ('.$values['mlbs'].' lbs) ('.$values['mkg'].' kg) ('.$values['mgr'].' gr)';
            $this->weights = array('values'=>$values, 'html'=>$html);
            return $this->weights;
        }

        // wrappers (bags/boxes) weight

        // number of bags
        $bags = 0;
        foreach(self::$sz as $size) {
			
//			if ($_SERVER['REMOTE_ADDR']=='109.160.0.218') {
//				var_dump($size,$this->qty[$size],self::$types[$this->type]['per_bag'],isset($this->qty[$size]));
//			}
			if (!isset($this->qty[$size])) continue;
			
            $bags += ceil($this->qty[$size]/$types[$this->type]['per_bag']);
        }
        $am_bags = 0;
        foreach(self::$sz as $size) {
            if(!empty($this->qtyCheck[$size]) && !empty($this->qty[$size]) && !empty($this->options['addl_molds'][$size])) {
                //var_dump($op_addl_base);
                //var_dump($op_addl_screens[1]);
                //var_dump($op_addl_multi);
                $am_bags += $this->options['addl_molds'][$size];
            }
        }
        $bags += $am_bags;


        //bags weight
        $html['bags_weight_OZ'] = $values['bags_weight_OZ'] = round($bags*BAG_WEIGHT_OZ, 3);
        $html['bags_weight_lbs'] = $values['bags_weight_lbs'] = round($values['bags_weight_OZ']*OUNCE_TO_POUND, 3);
        $html['bags_weight_gr'] = $values['bags_weight_gr'] = round($values['bags_weight_OZ']*OUNCE_TO_GRAM, 3);
        $html['bags_weight_kg'] = $values['bags_weight_kg'] = round($values['bags_weight_gr']*GRAM_TO_KILO, 3);
        //bags html
        $values['bags'] = $bags;
        $html['bags'] = $values['bags'].' ('.$html['bags_weight_OZ'].' OZ) ('.$html['bags_weight_lbs'].' lbs) ('.$html['bags_weight_kg'].' kg) ('.$html['bags_weight_gr'].' gr)';
        //boxes count
        $values['boxes'] = ceil($values['bags']/BAGS_PER_BOX);
        //boxes weight
        $html['boxes_weight_OZ'] = $values['boxes_weight_OZ'] = round($values['boxes']*BOX_WEIGHT_OZ, 3);
        $html['boxes_weight_lbs'] = $values['boxes_weight_lbs'] = round($values['boxes_weight_OZ']*OUNCE_TO_POUND, 3);
        $html['boxes_weight_gr'] = $values['boxes_weight_gr'] = round($values['boxes_weight_OZ']*OUNCE_TO_GRAM, 3);
        $html['boxes_weight_kg'] = $values['boxes_weight_kg'] = round($values['boxes_weight_gr']*GRAM_TO_KILO, 3);
        //boxes html
        $html['boxes'] = $values['boxes'].' ('.$html['boxes_weight_OZ'].' OZ) ('.$html['boxes_weight_lbs'].' lbs) ('.$html['boxes_weight_kg'].' kg) ('.$html['boxes_weight_gr'].' gr)';
        //wrappers weight
        $html['package_weight_OZ'] = $values['package_weight_OZ'] = $values['bags_weight_OZ']+$values['boxes_weight_OZ'];
        $html['package_weight_lbs'] = $values['package_weight_lbs'] = $values['bags_weight_lbs']+$values['boxes_weight_lbs'];
        $html['package_weight_kg'] = $values['package_weight_kg'] = $values['bags_weight_kg']+$values['boxes_weight_kg'];
        $html['package_weight_gr'] = $values['package_weight_gr'] = $values['bags_weight_gr']+$values['boxes_weight_gr'];
        $html['package_weight'] = '('.$values['package_weight_OZ'].' OZ) ('.$values['package_weight_lbs'].' lbs) ('.$values['package_weight_kg'].' kg) ('.$values['package_weight_gr'].' gr)';
        //total package weight
        $html['packaged_weight_OZ'] = $values['packaged_weight_OZ'] = $values['mOZ']+$values['package_weight_OZ'];
        $html['packaged_weight_lbs'] = $values['packaged_weight_lbs'] = $values['mlbs']+$values['package_weight_lbs'];
        $html['packaged_weight_kg'] = $values['packaged_weight_kg'] = $values['mkg']+$values['package_weight_kg'];
        $html['packaged_weight_gr'] = $values['packaged_weight_gr'] = $values['mgr']+$values['package_weight_gr'];
        $html['packaged_weight'] = '('.$values['packaged_weight_OZ'].' OZ) ('.$values['packaged_weight_lbs'].' lbs) ('.$values['packaged_weight_kg'].' kg) ('.$values['packaged_weight_gr'].' gr)';

        $this->weights = array('values'=>$values, 'html'=>$html);
        return $this->weights;
    }

    function getTransitCost() {
        if(!empty($this->tcost)) {
            return $this->tcost;
        }

		global $tpt_vars;
		$data_module = getModule($tpt_vars, 'BandData');
		$types_module = getModule($tpt_vars, 'BandType');
		$types = $types_module->moduleData['id'];
		$styles_module = getModule($tpt_vars, 'BandStyle');
		$styles = $styles_module->moduleData['id'];

        if(isset($types[$this->type]) && isset($styles[$this->style]) && !empty($this->sizes) && count(array_filter($this->qtyCheck))) {
        } else {
            return false;
        }

        if(!empty($this->pricingType)) { // in-house pricing
            $values['stcost'] = 0;
            $values['mtcost'] = 0;
            $html['stcost'] = '&#36;'.number_format($values['stcost'], 4);
            $html['mtcost'] = '&#36;'.number_format($values['mtcost'], 2);

            $this->tcost = array('values'=>$values, 'html'=>$html);
            return $this->tcost;
        }

        if(empty($this->weights)) {
            $this->getWeights();
        }

        $html = array();
        $values = array();


		//tpt_dump(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS));
		//tpt_dump($this->tcosts);
        // determine transit costs from total package weight and according record in the db
        foreach($this->tcosts as $tc) {
            if($this->weights['values']['packaged_weight_kg'] <=floatval($tc['to'])) {
                $values['mtcost'] = $tc['cost'];
                if(empty($values['mtcost']))
                    $values['mtcost'] = $tc['per_kilo']*$this->weights['values']['packaged_weight_kg'];
                $values['stcost'] = round($values['mtcost']/$this->total_qty, 2);
                break;
            }
        }
        if(empty($values['mtcost'])) {
            $mtcost = array_pop($this->tcosts);
            array_push($this->tcosts, $mtcost);
            $values['mtcost'] = $mtcost['per_kilo']*$this->weights['values']['packaged_weight_kg'];
            //$mtcost = 'Call for quote';
            //$stcost = 'Call for quote';
        }

        // SPECIAL CASE! 1000 slap bands cost $117 for transit
        if(($this->type == 5) && ($this->total_qty == 1000)) {
            $values['mtcost'] = 117;
        }

        $values['stcost'] = round($values['mtcost']/$this->total_qty, 4);
        $html['stcost'] = '&#36;'.number_format($values['stcost'], 4);
        $html['mtcost'] = '&#36;'.number_format($values['mtcost'], 2);

        $this->tcost = array('values'=>$values, 'html'=>$html);
        return $this->tcost;
    }

    function getMfgCost() {
        //var_dump(debug_backtrace());
        //debug_print_backtrace();
        //echo 'hi';
        //var_dump($this->pricing_data);
        if(!empty($this->mfgcost)) {
            //var_dump($this->mfgcost);
            //die();
            return $this->mfgcost;
        }

		global $tpt_vars;
		$data_module = getModule($tpt_vars, 'BandData');
		$types_module = getModule($tpt_vars, 'BandType');
		$types = $types_module->moduleData['id'];
		$styles_module = getModule($tpt_vars, 'BandStyle');
		$styles = $styles_module->moduleData['id'];


        if(isset($types[$this->type]) && isset($styles[$this->style]) && !empty($this->sizes) && count(array_filter($this->qtyCheck))) {
        } else {
            return false;
        }

        $html = array();
        $values = array();


        //var_dump($this->pricingTable);die();
        //loop through db data and get the appropriate row
        //tpt_logger::dump($tpt_vars, $this->pricingTable, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), '$this->pricingTable', __FILE__.' '.__LINE__);
        //tpt_logger::dump($tpt_vars, $this->pricing_data[$this->pricingTable], debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), '$this->pricing_data[$this->pricingTable]', __FILE__.' '.__LINE__);
		//tpt_dump($this->pricingTable);
        if(!empty($this->pricing_data[$this->pricingTable])) {
            foreach($this->pricing_data[$this->pricingTable] as $price) {
                if($this->total_qty >= intval($price['qty'], 10)) {
					//tpt_dump($price['mfg_cost_per']);
                    $values['sbase_price_raw'] = round(floatval(str_replace(' ', '', str_replace(',', '.', str_replace('$', '', $price['mfg_cost_per'])))), 2);
                    $values['mbase_price_raw'] = round($this->total_qty*floatval(str_replace(' ', '', str_replace(' ', '', str_replace(',', '.', str_replace('$', '', $price['mfg_cost_per']))))), 2);
                    //$mretail_pofit = '&#36;'.number_format(round(floatval(str_replace(' ', '', str_replace(',', '.', str_replace('$', '', $price['retail_price'])))), 2);
                    /*
                    $mretail_price = round(floatval(str_replace(' ', '', str_replace(',', '.', str_replace('$', '', $price['retail_price'])))), 2);
                    $sretail_price = round(floatval(str_replace(' ', '', str_replace(',', '.', str_replace('$', '', $price['retail_price']))))/$qty_lg, 2);
                    $mcustomer_price = round(floatval(str_replace(' ', '', str_replace(',', '.', str_replace('$', '', $price['customer_price'])))), 2);
                    $scustomer_price = round(floatval(str_replace(' ', '', str_replace(',', '.', str_replace('$', '', $price['customer_price']))))/$qty_lg, 2);
                    $mlowest_price = round(floatval(str_replace(' ', '', str_replace(',', '.', str_replace('$', '', $price['lowest_price'])))), 2);
                    $slowest_price = round(floatval(str_replace(' ', '', str_replace(',', '.', str_replace('$', '', $price['lowest_price']))))/$qty_lg, 2);
                    */

                    //$mretail_pofit = '&#36;'.number_format(round(floatval(str_replace(',', '.', str_replace('$', '', $price['retail_price']))), 2), 2);
                } else {
                    break;
                }
            }
        } else {
            $values['sbase_price_raw'] = 0;
            $values['mbase_price_raw'] = 0;
        }


        $values['mfg_cost_per'] = $values['sbase_price_raw'];
        $values['mfg_cost_total'] = $values['mbase_price_raw'];
        $html['mfg_cost_per'] = '&#36;'.number_format($values['mfg_cost_per'], 2);
        $html['mfg_cost_total'] = '&#36;'.number_format($values['mfg_cost_total'], 2);

        $this->mfgcost = array('values'=>$values, 'html'=>$html);
		//tpt_dump($this->mfgcost);
        /*
        if(($_SERVER['REMOTE_ADDR'] == '109.160.0.218') && ($_GET['debug'] == 'debu')) {
            var_dump($this->pricingTable);//die();
            var_dump($this->total_qty);//die();
            var_dump($this->pricing_data);//die();
            var_dump($this->mfgcost);

        }
        */

        return $this->mfgcost;
        //done getting mfg cost
    }

    function getMoldFees() {
        if(!empty($this->mold)) {
            return $this->mold;
        }

		global $tpt_vars;
		$data_module = getModule($tpt_vars, 'BandData');
		$types_module = getModule($tpt_vars, 'BandType');
		$types = $types_module->moduleData['id'];
		$styles_module = getModule($tpt_vars, 'BandStyle');
		$styles = $styles_module->moduleData['id'];

        if(isset($types[$this->type]) && isset($styles[$this->style]) && !empty($this->sizes) && count(array_filter($this->qtyCheck))) {
        } else {
            return false;
        }

        $html = array();
        $values = array();

        $mold_strings = array(); //tech stats html content array
        $values['base_molds_count'] = 0;
        $values['additional_molds_count'] = 0;
        $molds_labels = '';
        $molds_values = '';
        // get default mold fee data
        $moldsLBL = array();
        $moldsVAL = array();
        //total mold fee
        $values['mold_total'] = 0;
        $values['base_mold_per'] = ((($styles[$this->style]['mold']||$types[$this->type]['molds'])&&empty($this->pricingType))?(!empty($types[$this->type]['mold_fee'])?$types[$this->type]['mold_fee']:MOLD_FEE):0);

        $values['base_mold_per'] = (($styles[$this->style]['mold']&&empty($this->pricingType))?(!empty($data_module->typeStyle[$this->type][$this->style]['mold_fee'])?$data_module->typeStyle[$this->type][$this->style]['mold_fee']:$values['base_mold_per']):0);
        //var_dump($this->options_pricing_row['base_mold']);die();
        $values['base_mold_per'] = (($styles[$this->style]['mold']&&empty($this->pricingType))?((!empty($this->options_pricing_row['base_mold']))?$this->options_pricing_row['base_mold']:$values['base_mold_per']):0);
        //var_dump($values['base_mold_per']);die();
        //var_dump(self::$styles[$this->style]['mold']);//die();
        //var_dump($this->pricingType);//die();
        //var_dump($this->type);//die();
        //var_dump($this->style);//die();
        //var_dump($values['base_mold_per']);die();
        $values['base_mold_total'] = $values['base_mold_per']*$this->sizes;
        if(!empty($values['base_mold_total'])) {
            $values['mold_total'] += $values['base_mold_total'];
            $values['base_mold_count'] = $this->sizes;
            $moldsLBL['base'] = 'Base molds:<br />';
            $html['base_mold_total'] = '&#36;'.number_format(round($values['base_mold_total'], 2), 2);
            $html['base_mold'] = $moldsVAL['base'] = $values['base_mold_count'].' ('.$html['base_mold_total'].')<br />';
            $mold_strings[] = $values['base_mold_count'].' base ('.$html['base_mold_total'].')';
        }

        //tpt_logger::dump($tpt_vars, $this->options['addl_molds'], debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), '$this->options[\'addl_molds\']', __FILE__.' '.__LINE__);
        if(!empty($this->options['addl_molds']) && count(array_filter($this->options['addl_molds']))) {
            $values['addl_mold_per'] = $this->options_pricing_row['addl_mold'];
            $values['addl_mold_count'] = 0;
            $mc = array();
            $values['addl_mold_total'] = 0;
            foreach($this->qty as $size=>$val) {
                if(isset($this->qtyCheck[$size]) && !empty($this->qtyCheck[$size]) && !empty($val) && isset($this->options['addl_molds'][$size]) && !empty($this->options['addl_molds'][$size])) {
                    //var_dump($op_addl_base);
                    //var_dump($op_addl_screens[1]);
                    //var_dump($op_addl_multi);
                    $values['addl_mold_count'] += $this->options['addl_molds'][$size];
                    $mc[] = $this->options['addl_molds'][$size];
                    $values['addl_mold_total'] += $values['addl_mold_per']*$this->options['addl_molds'][$size];
                }
            }
            $moldsLBL['addl'] = 'Additional molds:<br />';
            $html['addl_mold_count'] = implode('+', $mc);
            $html['addl_mold_total'] = '&#36;'.number_format(round($values['addl_mold_total'], 2), 2);
            $mold_strings[] = $html['addl_mold_count'].' additional ('.$html['addl_mold_total'].')';
            $html['addl_mold'] = $moldsVAL['addl'] = $html['addl_mold_count'].' ('.$html['addl_mold_total'].')<br />';
            $values['mold_total'] += $values['addl_mold_total'];
            //var_dump($screen_fee);
        }
        if(!empty($this->options['insd_molds']) && count(array_filter($this->options['insd_molds']))) {
            $values['insd_mold_per'] = $this->options_pricing_row['addl_mold'];
            $values['insd_mold_count'] = 0;
            $mc = array();
            $values['insd_mold_total'] = 0;
            foreach($this->qty as $size=>$val) {
                if(isset($this->qtyCheck[$size]) && isset($this->qty[$size]) && !empty($this->options['insd_molds'][$size])) {
                    //var_dump($op_insd_base);
                    //var_dump($op_insd_screens[1]);
                    //var_dump($op_insd_multi);
                    $values['insd_mold_count'] += $this->options['insd_molds'][$size];
                    $mc[] = $this->options['insd_molds'][$size];
                    $values['insd_mold_total'] += $values['insd_mold_per']*$this->options['insd_molds'][$size];
                }
            }
            $moldsLBL['add'] = 'Inside molds:<br />';
            $html['insd_mold_count'] = implode('+', $mc);
            $html['insd_mold_total'] = '&#36;'.number_format(round($values['insd_mold_total'], 2), 2);
            $mold_strings[] = $html['insd_mold_count'].' inside ('.$html['insd_mold_total'].')';
            $html['insd_mold'] = $moldsVAL['insd'] = $values['insd_mold_count'].' ('.$html['insd_mold_total'].')<br />';
            $values['mold_total'] += $values['insd_mold_total'];
            //var_dump($screen_fee);
        }
        if(!empty($this->options['cstm_mold'])) {
            $values['cstm_mold_per'] = $this->options_pricing_row['cstm_mold'];
            //var_dump($op_addl_base);
            //var_dump($op_addl_screens[1]);
            //var_dump($op_addl_multi);
            $values['cstm_mold_count'] = $this->sizes;
            $values['cstm_mold_total'] = $values['cstm_mold_per']*$values['cstm_mold_count'];
            $values['mold_total'] += $values['cstm_mold_total'];
            $moldsLBL['add'] = 'Custom band molds:<br />';
            $html['cstm_mold_total'] = '&#36;'.number_format(round($values['cstm_mold_total'], 2), 2);
            $html['cstm_mold'] = $moldsVAL['add'] = $values['cstm_mold_count'].' ('.$html['cstm_mold_total'].')<br />';
            $mold_strings[] = $values['cstm_mold_count'].' custom ('.$html['cstm_mold_total'].')';
        }

        $html['mold_total'] = '&#36;'.number_format(round($values['mold_total'], 2), 2);

        // check if the special flat mold fees are set and format the mold values for html
        if(!empty($this->options['final_mold'])) {
            $values['mold_total'] = floatval(str_replace(',', '.', preg_replace('#[^0-9\.]+#', '', $this->options['final_mold'])));
            $html['mold_total'] = '&#36;'.$values['mold_total'];
            $moldsLBL = array('base'=>'<span class="font-style-italic">User input special mold fee:</span><br />');
            $moldsVAL = array('base'=>'<span class="font-style-italic">'.$html['mold_total'].'</span><br />');
            $html['mold_descr'] = 'User input flat fee: '.$html['mold_total'];
        } else if(!empty($values['mold_total'])) {
            $moldsLBL['total'] = '<span class="font-style-italic">Total mold fees:</span><br />';
            $moldsVAL['total'] = '<span class="font-style-italic">'.$html['mold_total'].'</span><br />';
            $html['mold_descr'] = implode(', ', $mold_strings);
        } else {
            $moldsLBL['total'] = '<span class="font-style-italic">Total mold fees:</span><br />';
            $moldsVAL['total'] = '<span class="font-style-italic">'.$html['mold_total'].'</span><br />';
            $html['mold_descr'] = 'No';
        }

        $molds_labels = implode("\n", $moldsLBL);
        $molds_values = implode("\n", $moldsVAL);
        $content = array('labels'=>$moldsLBL, 'values'=>$moldsVAL);

        $this->mold = array('values'=>$values, 'html'=>$html, 'content'=>$content);
        return $this->mold;
    }

    function getScreenFees() {
        if(!empty($this->screen)) {
            return $this->screen;
        }

		global $tpt_vars;
		$data_module = getModule($tpt_vars, 'BandData');
		$types_module = getModule($tpt_vars, 'BandType');
		$types = $types_module->moduleData['id'];
		$styles_module = getModule($tpt_vars, 'BandStyle');
		$styles = $styles_module->moduleData['id'];

        if(isset($types[$this->type]) && isset($styles[$this->style]) && !empty($this->sizes) && count(array_filter($this->qtyCheck))) {
        } else {
            return false;
        }

        $html = array();
        $values = array();

        $screen_strings = array(); //tech stats html content array
        $values['base_screens_count'] = 0;
        $values['additional_screens_count'] = 0;
        $values['inside_screens_count'] = !empty($this->iScreens)?implode('+', $this->iScreens):'0';
        $screens_labels = '';
        $screens_values = '';
        // get default screen fee data
        $screensLBL = array();
        $screensVAL = array();
        //total screen fee
        $values['screen_total'] = 0;
        $values['base_screen_per'] = (!empty($types[$this->type]['screen_fee'])?$types[$this->type]['screen_fee']:SCREEN_FEE);
        $values['base_screen_per'] = (!empty($data_module->typeStyle[$this->type][$this->style]['screen_fee'])?$data_module->typeStyle[$this->type][$this->style]['screen_fee']:$values['base_screen_per']);
        if(($styles[$this->style]['screen']||$types[$this->type]['screens'])&&empty($this->pricingType)) {
            $mlt = 0;
            if(!empty($styles[$this->style]['screen'])) {
                $mlt++;
            }
            if(!empty($types[$this->type]['screens'])) {
                $mlt += $types[$this->type]['screens'];
            }
            //$values['base_screen_count'] += $mlt;
            $values['base_screen_total'] = $mlt*$values['base_screen_per']*$this->sizes;
            //var_dump($mlt);//die();
            //var_dump($values['base_screen_per']);//die();
            //var_dump($this->sizes);//die();
            //var_dump($values['base_screen_total']);die();
            if(!empty($types[$this->type]['screens']) && ($types[$this->type]['screens'] == 1)) {
                //die();
                $values['base_screen_total'] += 5*$this->sizes;
            }

        }
        //var_dump($svalues['base_screen_total']);die();
        if(!empty($values['base_screen_total'])) {
            $values['screen_total'] += $values['base_screen_total'];
            $values['base_screen_count'] = $this->sizes*$mlt;
            $screensLBL['base'] = 'Base screens:<br />';
            $html['base_screen_total'] = '&#36;'.number_format(round($values['base_screen_total'], 2), 2);
            $screensVAL['base'] = $values['base_screen_count'].' ('.$html['base_screen_total'].')<br />';
            $screen_strings[] = $values['base_screen_count'].' base ('.$html['base_screen_total'].')';
        }
        if(!empty($this->options['addl_screens']) && count(array_filter($this->options['addl_screens']))) {
            $values['addl_screen_per'] = $values['base_screen_per'];

            if(!empty($this->options_pricing_row['addl_screen_1'])) {
            $values['op_addl_screens_1'] = $this->options_pricing_row['addl_screen_1'];
            $values['op_addl_screens_1'] = explode('+', $values['op_addl_screens_1']);
            $values['addl_screen_per_base_1'] = floatval($values['op_addl_screens_1'][0]);
            $values['addl_screen_per_multi_1'] = floatval($values['op_addl_screens_1'][1]);
            } else {
            $values['op_addl_screens_1'] = $values['addl_screen_per'];
            $values['addl_screen_per_base_1'] = floatval($values['addl_screen_per']);
            $values['addl_screen_per_multi_1'] = 0;
            }
            $values['op_addl_screens'] = $this->options_pricing_row['addl_screen'];
            $values['op_addl_screens'] = explode('+', $values['op_addl_screens']);
            $values['addl_screen_per_base'] = floatval($values['op_addl_screens'][0]);
            $values['addl_screen_per_multi'] = floatval($values['op_addl_screens'][1]);

            $values['addl_screen_count'] = 0;
            $mc = array();
            $values['addl_screen_total'] = 0;
            foreach($this->qty as $size=>$val) {
                if(!empty($this->qtyCheck[$size]) && $this->qty[$size] && !empty($this->options['addl_screens'][$size])) {
                    //var_dump($op_addl_base);
                    //var_dump($op_addl_screens[1]);
                    //var_dump($op_addl_multi);
                    $values['addl_screen_count'] += $this->options['addl_screens'][$size];
                    $mc[] = $this->options['addl_screens'][$size];
                    $addl_screen_add = 0;
                    if(($this->options['addl_screens'][$size] == 1)) {
                        $addl_screen_add = ($values['addl_screen_per_base_1']+$values['addl_screen_per_multi_1']*$this->qty[$size])*($this->options['addl_screens'][$size]);
                    } else {
                        $addl_screen_add = ($values['addl_screen_per_base']+$values['addl_screen_per_multi']*$this->qty[$size])*($this->options['addl_screens'][$size]);
                    }
                    $values['addl_screen_total'] += $addl_screen_add;
                }
            }
            $screensLBL['addl'] = 'Additional screens:<br />';
            $html['addl_screen_count'] = implode('+', $mc);
            $html['addl_screen_total'] = '&#36;'.number_format(round($values['addl_screen_total'], 2), 2);
            $screen_strings[] = $html['addl_screen_count'].' additional ('.$html['addl_screen_total'].')';
            $html['addl_screen'] = $screensVAL['addl'] = $html['addl_screen_count'].' ('.$html['addl_screen_total'].')<br />';
            $values['screen_total'] += $values['addl_screen_total'];
            //var_dump($screen_fee);
        }

        tpt_logger::dump($tpt_vars, !empty($this->options['insd_screens'])?$this->options['insd_screens']:0, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), '$this->options[\'insd_screens\']', __FILE__.' '.__LINE__);
        if(!empty($this->options['insd_screens']) && count(array_filter($this->options['insd_screens']))) {
            $values['insd_screen_per'] = $values['base_screen_per'];

            if(!empty($this->options_pricing_row['addl_screen_1'])) {
            $values['op_insd_screens_1'] = $this->options_pricing_row['addl_screen_1'];
            $values['op_insd_screens_1'] = explode('+', $values['op_insd_screens_1']);
            $values['insd_screen_per_base_1'] = floatval($values['op_insd_screens_1'][0]);
            $values['insd_screen_per_multi_1'] = floatval($values['op_insd_screens_1'][1]);
            } else {
            $values['op_insd_screens_1'] = $values['insd_screen_per'];
            $values['insd_screen_per_base_1'] = floatval($values['insd_screen_per']);
            $values['insd_screen_per_multi_1'] = 0;
            }
            
            $values['op_insd_screens'] = $this->options_pricing_row['addl_screen'];
            $values['op_insd_screens'] = explode('+', $values['op_insd_screens']);
            $values['insd_screen_per_base'] = floatval($values['op_insd_screens'][0]);
            $values['insd_screen_per_multi'] = floatval($values['op_insd_screens'][1]);

            $values['insd_screen_count'] = 0;
            $mc = array();
            $values['insd_screen_total'] = 0;
            foreach($this->qty as $size=>$val) {
                if(!empty($this->qtyCheck[$size]) && $this->qty[$size] && !empty($this->options['insd_screens'][$size])) {
                    //var_dump($op_insd_base);
                    //var_dump($op_insd_screens[1]);
                    //var_dump($op_insd_multi);
                    $values['insd_screen_count'] += $this->options['insd_screens'][$size];
                    $mc[] = $this->options['insd_screens'][$size];
                    $insd_screen_add = 0;
                    if(($this->options['insd_screens'][$size] == 1)) {
                        $insd_screen_add = ($values['insd_screen_per_base_1']+$values['insd_screen_per_multi_1']*$this->qty[$size])*($this->options['insd_screens'][$size]);
                    } else {
                        $insd_screen_add = ($values['insd_screen_per_base']+$values['insd_screen_per_multi']*$this->qty[$size])*($this->options['insd_screens'][$size]);
                    }
                    $values['insd_screen_total'] += $insd_screen_add;
                    tpt_logger::dump($tpt_vars, $values['insd_screen_per_base_1'], debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), '$values[\'insd_screen_per_base_1\']', __FILE__.' '.__LINE__);
                    tpt_logger::dump($tpt_vars, $values['insd_screen_per_multi_1'], debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), '$values[\'insd_screen_per_multi_1\']', __FILE__.' '.__LINE__);
                    tpt_logger::dump($tpt_vars, $values['insd_screen_per_base'], debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), '$values[\'insd_screen_per_base\']', __FILE__.' '.__LINE__);
                    tpt_logger::dump($tpt_vars, $values['insd_screen_per_multi'], debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), '$values[\'insd_screen_per_multi\']', __FILE__.' '.__LINE__);
                    tpt_logger::dump($tpt_vars, $this->options['insd_screens'], debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), '$this->options[\'insd_screens\']', __FILE__.' '.__LINE__);
                    tpt_logger::dump($tpt_vars, $this->qty[$size], debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), '$this->qty[$size]', __FILE__.' '.__LINE__);
                    tpt_logger::dump($tpt_vars, $insd_screen_add, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), '$insd_screen_add', __FILE__.' '.__LINE__);
                    tpt_logger::dump($tpt_vars, $values['insd_screen_total'], debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), '$values[\'insd_screen_total\']', __FILE__.' '.__LINE__);
                }
            }
            $screensLBL['insd'] = 'Inside screens:<br />';
            $html['insd_screen_count'] = implode('+', $mc);
            $html['insd_screen_total'] = '&#36;'.number_format(round($values['insd_screen_total'], 2), 2);
            $screen_strings[] = $html['insd_screen_count'].' inside ('.$html['insd_screen_total'].')';
            $html['insd_screen'] = $screensVAL['insd'] = $html['insd_screen_count'].' ('.$html['insd_screen_total'].')<br />';
            $values['screen_total'] += $values['insd_screen_total'];
            //var_dump($screen_fee);
        }


        $html['screen_total'] = '&#36;'.number_format(round($values['screen_total'], 2), 2);
        // check if the special flat screen fees are set and format the screen values for html
        if(!empty($this->options['final_screen'])) {
            $values['screen_total'] = floatval(str_replace(',', '.', preg_replace('#[^0-9\.]+#', '', $this->options['final_screen'])));
            $html['screen_total'] = '&#36;'.$values['screen_total'];
            $screensLBL = array('base'=>'<span class="font-style-italic">User input flat screen fee:</span><br />');
            $screensVAL = array('base'=>'<span class="font-style-italic">'.$html['screen_total'].'</span><br />');
            $html['screen_descr'] = 'User input flat fee: '.$html['screen_total'];
        } else if(!empty($values['screen_total'])) {
            $screensLBL['total'] = '<span class="font-style-italic">Total screen fees:</span><br />';
            $screensVAL['total'] = '<span class="font-style-italic">'.$html['screen_total'].'</span><br />';
            $html['screen_descr'] = implode(', ', $screen_strings);
        } else {
            $screensLBL['total'] = '<span class="font-style-italic">Total screen fees:</span><br />';
            $screensVAL['total'] = '<span class="font-style-italic">'.$html['screen_total'].'</span><br />';
            $html['screen_descr'] = 'No';
        }
        $screens_labels = implode("\n", $screensLBL);
        $screens_values = implode("\n", $screensVAL);
        $content = array('labels'=>$screensLBL, 'values'=>$screensVAL);

        $this->screen = array('values'=>$values, 'html'=>$html, 'content'=>$content);
        return $this->screen;
    }

    function getOptionsCost() {
        global $tpt_vars;
        //tpt_dump($this->options,true);
        //tpt_logger::dump($tpt_vars, $this->optcost, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), '$this->optcost', __FILE__.' '.__LINE__);
        //tpt_logger::dump($tpt_vars, $this->options, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), '$this->options', __FILE__.' '.__LINE__);
        if(!empty($this->optcost)) {
            return $this->optcost;
        } else if(empty($this->options)) {
            $this->optcost = array('values'=>array('options_per'=>0, 'options_total'=>0));
            return $this->optcost;
        }

		global $tpt_vars;
		$data_module = getModule($tpt_vars, 'BandData');
		$types_module = getModule($tpt_vars, 'BandType');
		$types = $types_module->moduleData['id'];
		$styles_module = getModule($tpt_vars, 'BandStyle');
		$styles = $styles_module->moduleData['id'];

        if(isset($types[$this->type]) && isset($styles[$this->style]) && !empty($this->sizes) && count(array_filter($this->qtyCheck))) {
        } else {
            return false;
        }
        //tpt_dump($this->options, true);

        $html = array();
        $values = array();

        //process options
        //html containers
        $optsResultsLBL = ''; //left side
        $optsResultsVAL = ''; //right side

        //some arrays for easier management
        $values['options_per'] = 0; // s total options cost
        $values['options_total'] = 0;
        $sop = array(); // db table values for each option
        $mop = array();
        // formatted content for html output
        $sopLBL = array(); // s labels
        $mopLBL = array(); // m labels
        $sopVAL = array(); // s values
        $mopVAL = array(); // m values
        //tpt_dump($this->options['glow'], true);
        //tpt_dump($this->options_pricing_row, true);
        if(!empty($this->options['glow'])) {
            $values['glow_per'] = $this->options_pricing_row['glow'];
            $values['glow_total'] = $values['glow_per']*$this->total_qty;
            $values['options_per'] += $values['glow_per'];
            $values['options_total'] += $values['glow_total'];
            $html['glow_per'] = '&#36;'.number_format(round($values['glow_per'], 4), 4);
            $html['glow_total'] = '&#36;'.number_format(round($values['glow_total'], 2), 2);
            $sopLBL['glow'] = 'Glow Band:<br />';
            $sopVAL['glow'] = $html['glow_per'].'<br />';
            $mopLBL['glow'] = 'Glow Band:<br />';
            $mopVAL['glow'] = $html['glow_total'].'<br />';
        }
        if(!empty($this->options['uv'])) {
            $values['uv_per'] = $this->options_pricing_row['uv'];
            $values['uv_total'] = $values['uv_per']*$this->total_qty;
            $values['options_per'] += $values['uv_per'];
            $values['options_total'] += $values['uv_total'];
            $html['uv_per'] = '&#36;'.number_format(round($values['uv_per'], 4), 4);
            $html['uv_total'] = '&#36;'.number_format(round($values['uv_total'], 2), 2);
            $sopLBL['uv'] = 'UV Band:<br />';
            $sopVAL['uv'] = $html['uv_per'].'<br />';
            $mopLBL['uv'] = 'UV Band:<br />';
            $mopVAL['uv'] = $html['uv_total'].'<br />';
        }
        if(!empty($this->options['glitter'])) {
            $values['glitter_per'] = $this->options_pricing_row['glitter'];
            $values['glitter_total'] = $values['glitter_per']*$this->total_qty;
            $values['options_per'] += $values['glitter_per'];
            $values['options_total'] += $values['glitter_total'];
            $html['glitter_per'] = '&#36;'.number_format(round($values['glitter_per'], 4), 4);
            $html['glitter_total'] = '&#36;'.number_format(round($values['glitter_total'], 2), 2);
            $sopLBL['glitter'] = 'Glitter:<br />';
            $sopVAL['glitter'] = $html['glitter_per'].'<br />';
            $mopLBL['glitter'] = 'Glitter:<br />';
            $mopVAL['glitter'] = $html['glitter_total'].'<br />';
        }
        if(!empty($this->options['powdercoat'])) {
            $values['powdercoat_per'] = $this->options_pricing_row['powdercoat'];
            $values['powdercoat_total'] = $values['powdercoat_per']*$this->total_qty;
            $values['options_per'] += $values['powdercoat_per'];
            $values['options_total'] += $values['powdercoat_total'];
            $html['powdercoat_per'] = '&#36;'.number_format(round($values['powdercoat_per'], 4), 4);
            $html['powdercoat_total'] = '&#36;'.number_format(round($values['powdercoat_total'], 2), 2);
            $sopLBL['powdercoat'] = 'Powder coated:<br />';
            $sopVAL['powdercoat'] = $html['powdercoat_per'].'<br />';
            $mopLBL['powdercoat'] = 'Powder coated:<br />';
            $mopVAL['powdercoat'] = $html['powdercoat_total'].'<br />';
        }
        //var_dump($this->options['swirl']);
        if(!empty($this->options['swirl'])) {

            switch($this->options['swirl']) {
                case 2 :
                    $values['swirl_per'] = $this->options_pricing_row['swirl-5_7'];
                    $values['swirl_total'] = $values['swirl_per']*$this->total_qty;
                    $values['options_per'] += $values['swirl_per'];
                    $values['options_total'] += $values['swirl_total'];
                    $html['swirl_per'] = '&#36;'.number_format(round($values['swirl_per'], 4), 4);
                    $html['swirl_total'] = '&#36;'.number_format(round($values['swirl_total'], 2), 2);
                    $sopLBL['swirl'] = 'Swirl (5-7 colors):<br />';
                    $sopVAL['swirl'] = $html['swirl_per'].'<br />';
                    $mopLBL['swirl'] = 'Swirl (5-7 colors):<br />';
                    $mopVAL['swirl'] = $html['swirl_total'].'<br />';
                    break;
                case 1 :
                default :
                    $values['swirl_per'] = $this->options_pricing_row['swirl-2_4'];
                    $values['swirl_total'] = $values['swirl_per']*$this->total_qty;
                    $values['options_per'] += $values['swirl_per'];
                    $values['options_total'] += $values['swirl_total'];
                    //var_dump($values['options_total']);
                    $html['swirl_per'] = '&#36;'.number_format(round($values['swirl_per'], 4), 4);
                    $html['swirl_total'] = '&#36;'.number_format(round($values['swirl_total'], 2), 2);
                    $sopLBL['swirl'] = 'Swirl (2-4 colors):<br />';
                    $sopVAL['swirl'] = $html['swirl_per'].'<br />';
                    $mopLBL['swirl'] = 'Swirl (2-4 colors):<br />';
                    $mopVAL['swirl'] = $html['swirl_total'].'<br />';
                    break;
            }
        }
        if(!empty($this->options['segments'])) {
            switch($this->options['segments']) {
                case 2 :
                    $values['segments_per'] = $this->options_pricing_row['segments-5_7'];
                    $values['segments_total'] = $values['segments_per']*$this->total_qty;
                    $values['options_per'] += $values['segments_per'];
                    $values['options_total'] += $values['segments_total'];
                    $html['segments_per'] = '&#36;'.number_format(round($values['segments_per'], 4), 4);
                    $html['segments_total'] = '&#36;'.number_format(round($values['segments_total'], 2), 2);
                    $sopLBL['segments'] = 'Segments (5-7 colors):<br />';
                    $sopVAL['segments'] = $html['segments_per'].'<br />';
                    $mopLBL['segments'] = 'Segments (5-7 colors):<br />';
                    $mopVAL['segments'] = $html['segments_total'].'<br />';
                    break;
                case 1 :
                default :
                    $values['segments_per'] = $this->options_pricing_row['segments-2_4'];
                    $values['segments_total'] = $values['segments_per']*$this->total_qty;
                    $values['options_per'] += $values['segments_per'];
                    $values['options_total'] += $values['segments_total'];
                    $html['segments_per'] = '&#36;'.number_format(round($values['segments_per'], 4), 4);
                    $html['segments_total'] = '&#36;'.number_format(round($values['segments_total'], 2), 2);
                    $sopLBL['segments'] = 'Segments (2-4 colors):<br />';
                    $sopVAL['segments'] = $html['segments_per'].'<br />';
                    $mopLBL['segments'] = 'Segments (2-4 colors):<br />';
                    $mopVAL['segments'] = $html['segments_total'].'<br />';
                    break;
            }
        }
        /*
        if(!empty($this->options['ink_spray'])) {
            $values['ink_spray_per'] = $this->options_pricing_row['ink_spray'];
            $values['ink_spray_total'] = $values['ink_spray_per']*$this->total_qty;
            $values['options_per'] += $values['ink_spray_per'];
            $values['options_total'] += $values['ink_spray_total'];
            $sopLBL['ink_spray'] = 'Glow Band:<br />';
            $sopVAL['ink_spray'] = $html['ink_spray_per'].'<br />';
            $mopLBL['ink_spray'] = 'Glow Band:<br />';
            $mopVAL['ink_spray'] = $html['ink_spray_total'].'<br />';
        }
        */
        if(!empty($this->options['ink_fill'])) {
            $values['ink_fill_per'] = $this->options_pricing_row['ink_fill'];
            $values['ink_fill_total'] = $values['ink_fill_per']*$this->total_qty;
            $values['options_per'] += $values['ink_fill_per'];
            $values['options_total'] += $values['ink_fill_total'];
            $html['ink_fill_per'] = '&#36;'.number_format(round($values['ink_fill_per'], 4), 4);
            $html['ink_fill_total'] = '&#36;'.number_format(round($values['ink_fill_total'], 2), 2);
            $sopLBL['ink_fill'] = 'Ink Fill:<br />';
            $sopVAL['ink_fill'] = $html['ink_fill_per'].'<br />';
            $mopLBL['ink_fill'] = 'Ink Fill:<br />';
            $mopVAL['ink_fill'] = $html['ink_fill_total'].'<br />';
        }
        if(!empty($this->options['addl_ink_fill'])) {
            $values['addl_ink_fill_per'] = $this->options_pricing_row['addl_ink_fill']*$this->options['addl_ink_fill'];
            $values['addl_ink_fill_total'] = $values['addl_ink_fill_per']*$this->total_qty;
            $values['options_per'] += $values['addl_ink_fill_per'];
            $values['options_total'] += $values['addl_ink_fill_total'];
            $html['addl_ink_fill_per'] = '&#36;'.number_format(round($values['addl_ink_fill_per'], 4), 4);
            $html['addl_ink_fill_total'] = '&#36;'.number_format(round($values['addl_ink_fill_total'], 2), 2);
            $sopLBL['addl_ink_fill'] = 'Additional Ink Fill ('.$this->options['addl_ink_fill'].' colors):<br />';
            $sopVAL['addl_ink_fill'] = $html['addl_ink_fill_per'].'<br />';
            $mopLBL['addl_ink_fill'] = 'Additional Ink Fill ('.$this->options['addl_ink_fill'].' colors):<br />';
            $mopVAL['addl_ink_fill'] = $html['addl_ink_fill_total'].'<br />';
        }
        if(!empty($this->options['glow_ink_fill'])) {
            //die('asdasdasdasd');
            $values['glow_ink_fill_per'] = $this->options_pricing_row['glow_ink_fill']*$this->options['glow_ink_fill'];
            $values['glow_ink_fill_total'] = $values['glow_ink_fill_per']*$this->total_qty;
            $values['options_per'] += $values['glow_ink_fill_per'];
            $values['options_total'] += $values['glow_ink_fill_total'];
            $html['glow_ink_fill_per'] = '&#36;'.number_format(round($values['glow_ink_fill_per'], 4), 4);
            $html['glow_ink_fill_total'] = '&#36;'.number_format(round($values['glow_ink_fill_total'], 2), 2);
            $sopLBL['glow_ink_fill'] = 'Glow Ink Fill ('.$this->options['glow_ink_fill'].' colors):<br />';
            $sopVAL['glow_ink_fill'] = $html['glow_ink_fill_per'].'<br />';
            $mopLBL['glow_ink_fill'] = 'Glow Ink Fill ('.$this->options['glow_ink_fill'].' colors):<br />';
            $mopVAL['glow_ink_fill'] = $html['glow_ink_fill_total'].'<br />';
        }
        if(!empty($this->options['key_chain'])) {
            switch($this->options['key_chain']) {
                case 2 :
                    $values['key_chain_per'] = $this->options_pricing_row['lg_key_chain'];
                    $values['key_chain_total'] = $values['key_chain_per']*$this->total_qty;
                    $values['options_per'] += $values['key_chain_per'];
                    $values['options_total'] += $values['key_chain_total'];
                    $html['key_chain_per'] = '&#36;'.number_format(round($values['key_chain_per'], 4), 4);
                    $html['key_chain_total'] = '&#36;'.number_format(round($values['key_chain_total'], 2), 2);
                    $sopLBL['key_chain'] = 'Key Chain (large):<br />';
                    $sopVAL['key_chain'] = $html['key_chain_per'].'<br />';
                    $mopLBL['key_chain'] = 'Key Chain (large):<br />';
                    $mopVAL['key_chain'] = $html['key_chain_total'].'<br />';
                    break;
                case 1 :
                default :
                    $values['key_chain_per'] = $this->options_pricing_row['sm_key_chain'];
                    $values['key_chain_total'] = $values['key_chain_per']*$this->total_qty;
                    $values['options_per'] += $values['key_chain_per'];
                    $values['options_total'] += $values['key_chain_total'];
                    $html['key_chain_per'] = '&#36;'.number_format(round($values['key_chain_per'], 4), 4);
                    $html['key_chain_total'] = '&#36;'.number_format(round($values['key_chain_total'], 2), 2);
                    $sopLBL['key_chain'] = 'Key Chain (small):<br />';
                    $sopVAL['key_chain'] = $html['key_chain_per'].'<br />';
                    $mopLBL['key_chain'] = 'Key Chain (small):<br />';
                    $mopVAL['key_chain'] = $html['key_chain_total'].'<br />';
                    break;
            }
        }
        if(!empty($this->options['key_chain_clasp'])) {
            $values['key_chain_clasp_per'] = $this->options_pricing_row['key_chain_clasp'];
            $values['key_chain_clasp_total'] = $values['key_chain_clasp_per']*$this->total_qty;
            $values['options_per'] += $values['key_chain_clasp_per'];
            $values['options_total'] += $values['key_chain_clasp_total'];
            $html['key_chain_clasp_per'] = '&#36;'.number_format(round($values['key_chain_clasp_per'], 4), 4);
            $html['key_chain_clasp_total'] = '&#36;'.number_format(round($values['key_chain_clasp_total'], 2), 2);
            $sopLBL['key_chain_clasp'] = 'Key Chain Clasp:<br />';
            $sopVAL['key_chain_clasp'] = $html['key_chain_clasp_per'].'<br />';
            $mopLBL['key_chain_clasp'] = 'Key Chain Clasp:<br />';
            $mopVAL['key_chain_clasp'] = $html['key_chain_clasp_total'].'<br />';
        }
        if(!empty($this->options['plastic_snaps'])) {
            $values['plastic_snaps_per'] = $this->options_pricing_row['plastic_snaps'];
            $values['plastic_snaps_total'] = $values['plastic_snaps_per']*$this->total_qty;
            $values['options_per'] += $values['plastic_snaps_per'];
            $values['options_total'] += $values['plastic_snaps_total'];
            $html['plastic_snaps_per'] = '&#36;'.number_format(round($values['plastic_snaps_per'], 4), 4);
            $html['plastic_snaps_total'] = '&#36;'.number_format(round($values['plastic_snaps_total'], 2), 2);
            $sopLBL['plastic_snaps'] = 'Plastic Snaps:<br />';
            $sopVAL['plastic_snaps'] = $html['plastic_snaps_per'].'<br />';
            $mopLBL['plastic_snaps'] = 'Plastic Snaps:<br />';
            $mopVAL['plastic_snaps'] = $html['plastic_snaps_total'].'<br />';
        }
        //tpt_dump($this->options, true);
        if(!empty($this->options['product_rush'])) {
            $values['op_product_rush'] = $this->options_pricing_row['product_rush'];
            $values['op_product_rush'] = explode('+', $values['op_product_rush']);
            $values['product_rush_per_base'] = floatval($values['op_product_rush'][0]);
            //var_dump($op_addl_base);
            $values['product_rush_per_multi'] = floatval($values['op_product_rush'][1]);
            //var_dump($op_addl_screens[1]);
            //var_dump($op_addl_multi);

            $values['product_rush_total'] = $values['product_rush_per_base']+$values['product_rush_per_multi']*$this->total_qty;
            $values['product_rush_per'] = round($values['product_rush_total']/$this->total_qty, 4);
            $values['options_per'] += $values['product_rush_per'];
            $values['options_total'] += $values['product_rush_total'];
            $html['product_rush_per'] = '&#36;'.number_format(round($values['product_rush_per'], 4), 4);
            $html['product_rush_total'] = '&#36;'.number_format(round($values['product_rush_total'], 2), 2);
            $sopLBL['product_rush'] = 'Product Rush:<br />';
            $sopVAL['product_rush'] = $html['product_rush_per'].'<br />';
            $mopLBL['product_rush'] = 'Product Rush:<br />';
            $mopVAL['product_rush'] = $html['product_rush_total'].'<br />';
        }
        //var_dump($this->options);die();
        if(!empty($this->options['writable'])) {
            $values['op_writable'] = $this->options_pricing_row['writable'];
            $values['op_writable'] = explode('+', $values['op_writable']);
            $values['writable_per_base'] = floatval($values['op_writable'][0]);
            //var_dump($op_addl_base);
            $values['writable_per_multi'] = floatval($values['op_writable'][1]);
            //var_dump($op_addl_screens[1]);
            //var_dump($op_addl_multi);

            $values['writable_total'] = $values['writable_per_base']+$values['writable_per_multi']*$this->total_qty;
            $values['writable_per'] = round($values['writable_total']/$this->total_qty, 4);
            $values['options_per'] += $values['writable_per'];
            $values['options_total'] += $values['writable_total'];
            $html['writable_per'] = '&#36;'.number_format(round($values['writable_per'], 4), 4);
            $html['writable_total'] = '&#36;'.number_format(round($values['writable_total'], 2), 2);
            $sopLBL['writable'] = 'Writable (Basic):<br />';
            $sopVAL['writable'] = $html['writable_per'].'<br />';
            $mopLBL['writable'] = 'Writable (Basic):<br />';
            $mopVAL['writable'] = $html['writable_total'].'<br />';
        }
        //var_dump($html['writable_total']);die();
        if(!empty($this->options['writable_bm'])) {
            $values['op_writable_bm'] = $this->options_pricing_row['writable_bm'];
            $values['op_writable_bm'] = explode('+', $values['op_writable_bm']);
            $values['writable_per_base'] = floatval($values['op_writable_bm'][0]);
            //var_dump($op_addl_base);
            $values['writable_bm_per_multi'] = floatval($values['op_writable_bm'][1]);
            //var_dump($op_addl_screens[1]);
            //var_dump($op_addl_multi);

            $values['writable_bm_total'] = $values['writable_bm_per_base']+$values['writable_bm_per_multi']*$this->total_qty;
            $values['writable_bm_per'] = round($values['writable_bm_total']/$this->total_qty, 4);
            $values['options_per'] += $values['writable_bm_per'];
            $values['options_total'] += $values['writable_bm_total'];
            $html['writable_bm_per'] = '&#36;'.number_format(round($values['writable_bm_per'], 4), 4);
            $html['writable_bm_total'] = '&#36;'.number_format(round($values['writable_bm_total'], 2), 2);
            $sopLBL['writable_bm'] = 'Writable (Back Message):<br />';
            $sopVAL['writable_bm'] = $html['writable_bm_per'].'<br />';
            $mopLBL['writable_bm'] = 'Writable (Back Message):<br />';
            $mopVAL['writable_bm'] = $html['writable_bm_total'].'<br />';
        }
        //tpt_dump($this->qty, true);
        //tpt_dump($this->options, true);
        //tpt_dump($this->qtyCheck, true);
        if(!empty($this->options['back_msgs'])) {
            $values['back_msg_per'] = $this->options_pricing_row['back_msg'];
            $values['op_back_msg'] = $values['back_msg_per'];
            $values['op_back_msg'] = explode('+', $values['op_back_msg']);
            $values['back_msg_per_base'] = floatval($values['op_back_msg'][0]);
            //var_dump($op_addl_base);
            $values['back_msg_per_multi'] = floatval(!empty($values['op_back_msg'][1])?$values['op_back_msg'][1]:0);
            //var_dump($op_addl_screens[1]);
            //var_dump($op_addl_multi);
            $values['back_msg_total'] = 0;
            foreach($this->qty as $size=>$val) {
                if(!empty($this->qtyCheck[$size]) && !empty($this->qty[$size]) && !empty($this->options['back_msgs'][$size])) {
                    $values['back_msg_total'] += $values['back_msg_per_base']+$values['back_msg_per_multi']*$this->qty[$size];
                }
            }
            $values['options_per'] += round($values['back_msg_total']/$this->total_qty, 2);
            $values['options_total'] += $values['back_msg_total'];
            $html['back_msg_per'] = '&#36;'.number_format(round($values['back_msg_per_base'], 2), 2).'+'.'&#36;'.number_format(round($values['back_msg_per_multi'], 2), 2).' per band';
            $html['back_msg_total'] = '&#36;'.number_format(round($values['back_msg_total'], 2), 2);
            $sopLBL['back_msg'] = 'Back Message:<br />';
            $sopVAL['back_msg'] = $html['back_msg_per'].'<br />';
            $mopLBL['back_msg'] = 'Back Message:<br />';
            $mopVAL['back_msg'] = $html['back_msg_total'].'<br />';
        }
        if(!empty($this->options['insd_msgs'])) {
            $values['insd_msg_per'] = $this->mfgcost['values']['mfg_cost_per'];
            $values['insd_msg_total'] = 0;
            foreach($this->qty as $size=>$val) {
                if (isset($this->qtyCheck[$size]) && isset($this->qty[$size]) && isset($this->options['insd_msgs'][$size]) && !empty($this->options['insd_msgs'][$size])) {
                    $values['insd_msg_total'] += $values['insd_msg_per']*$this->qty[$size];
                }
            }
            $values['options_per'] += $values['insd_msg_per'];
            $values['options_total'] += $values['insd_msg_total'];
            $html['insd_msg_per'] = '&#36;'.number_format(round($values['insd_msg_per'], 2), 2);
            $html['insd_msg_total'] = '&#36;'.number_format(round($values['insd_msg_total'], 2), 2);
            $sopLBL['insd_msg'] = 'Inside Message:<br />';
            $sopVAL['insd_msg'] = $html['insd_msg_per'].'<br />';
            $mopLBL['insd_msg'] = 'Inside Message:<br />';
            $mopVAL['insd_msg'] = $html['insd_msg_total'].'<br />';
        }
        if(!empty($this->options['ship_rush'])) {
            $values['ship_rush_per'] = $this->options_pricing_row['ship_rush'];
            $values['ship_rush_total'] = $values['ship_rush_per']*$this->total_qty;
            $values['options_per'] += $values['ship_rush_per'];
            $values['options_total'] += $values['ship_rush_total'];
            $html['ship_rush_per'] = '&#36;'.number_format(round($values['ship_rush_per'], 4), 4);
            $html['ship_rush_total'] = '&#36;'.number_format(round($values['ship_rush_total'], 2), 2);
            $sopLBL['ship_rush'] = 'Ship Rush:<br />';
            $sopVAL['ship_rush'] = $html['ship_rush_per'].'<br />';
            $mopLBL['ship_rush'] = 'Ship Rush:<br />';
            $mopVAL['ship_rush'] = $html['ship_rush_total'].'<br />';
        }
        if(!empty($this->options['indvl_packaging'])) {
            $values['indvl_packaging_per'] = $this->options_pricing_row['indvl_packaging'];
            $values['indvl_packaging_total'] = $values['indvl_packaging_per']*$this->total_qty;
            $values['options_per'] += $values['indvl_packaging_per'];
            $values['options_total'] += $values['indvl_packaging_total'];
            $html['indvl_packaging_per'] = '&#36;'.number_format(round($values['indvl_packaging_per'], 4), 4);
            $html['indvl_packaging_total'] = '&#36;'.number_format(round($values['indvl_packaging_total'], 2), 2);
            $sopLBL['indvl_packaging'] = 'Individual Packaging:<br />';
            $sopVAL['indvl_packaging'] = $html['indvl_packaging_per'].'<br />';
            $mopLBL['indvl_packaging'] = 'Individual Packaging:<br />';
            $mopVAL['indvl_packaging'] = $html['indvl_packaging_total'].'<br />';
        }
        if(!empty($this->options['indvl_inserts'])) {
            $values['indvl_inserts_per'] = $this->options_pricing_row['indvl_inserts'];
            $values['indvl_inserts_total'] = $values['indvl_inserts_per']*$this->total_qty;
            $values['options_per'] += $values['indvl_inserts_per'];
            $values['options_total'] += $values['indvl_inserts_total'];
            $html['indvl_inserts_per'] = '&#36;'.number_format(round($values['indvl_inserts_per'], 4), 4);
            $html['indvl_inserts_total'] = '&#36;'.number_format(round($values['indvl_inserts_total'], 2), 2);
            $sopLBL['indvl_inserts'] = 'Individual Inserts:<br />';
            $sopVAL['indvl_inserts'] = $html['indvl_inserts_per'].'<br />';
            $mopLBL['indvl_inserts'] = 'Individual Inserts:<br />';
            $mopVAL['indvl_inserts'] = $html['indvl_inserts_total'].'<br />';
        }

        //var_dump($this->options['rush_order']);die();
        if(!empty($tpt_vars) && !empty($this->options['rush_order'])) {
            //var_dump('asd');die();
            if(empty($this->mfgcost)) {
                $this->getMfgCost();
            }


            $rushorder_module = getModule($tpt_vars, "RushOrder");
            $rushorder_type = $rushorder_module->moduleData['id'][$this->options['rush_order']];
            $rushorder_label = $rushorder_module->moduleData['id'][$this->options['rush_order']]['label2'];
            $values['rush_order_percentage'] = floatval($rushorder_type['surcharge_prct']);
            $values['rush_order_shipping_per'] = floatval($rushorder_type['shipping_surcharge'])/$this->total_qty;
            $values['rush_order_shipping_total'] = floatval($rushorder_type['shipping_surcharge']);
            $values['rush_order_per'] = ($this->mfgcost['values']['mfg_cost_per']+$values['options_per'])*$rushorder_type['surcharge_prct']/100 + $values['rush_order_shipping_per'];
            //var_dump($rushorder_type);die();
            //var_dump($rushorder_type['surcharge_prct']);die();
            $values['rush_order_total'] = $values['rush_order_per']*$this->total_qty;
            $values['options_per'] += $values['rush_order_per'];
            $values['options_total'] += $values['rush_order_total'];
            $html['rush_order_percentage'] = $values['rush_order_percentage'].'%';

            $html['rush_order_shipping_per'] = '&#36;'.number_format(round($values['rush_order_shipping_per'], 4), 4);
            $html['rush_order_shipping_total'] = '&#36;'.number_format(round($values['rush_order_shipping_total'], 2), 2);
            $html['rush_order_per'] = '&#36;'.number_format(round($values['rush_order_per'], 4), 4);
            $html['rush_order_total'] = '&#36;'.number_format(round($values['rush_order_total'], 2), 2);
            $sopLBL['rush_order'] = 'Rush Order Surcharge Percentage ('.$rushorder_label.'):<br />';
            $sopVAL['rush_order'] = $html['rush_order_percentage'].'<br />';
            $sopLBL['rush_order1'] = 'Rush Order Shipping Surcharge Per ('.$rushorder_label.'):<br />';
            $sopVAL['rush_order1'] = $html['rush_order_shipping_per'].'<br />';
            $sopLBL['rush_order2'] = 'Rush Order Surcharge Per Band:<br />';
            $sopVAL['rush_order2'] = $html['rush_order_per'].'<br />';

            $mopLBL['rush_order'] = 'Rush Order Surcharge Percentage ('.$rushorder_label.'):<br />';
            $mopVAL['rush_order'] = $html['rush_order_percentage'].'<br />';
            $mopLBL['rush_order1'] = 'Rush Order Shipping Surcharge Total ('.$rushorder_label.'):<br />';
            $mopVAL['rush_order1'] = $html['rush_order_shipping_total'].'<br />';
            $mopLBL['rush_order2'] = 'Rush Order Surcharge Total:<br />';
            $mopVAL['rush_order2'] = $html['rush_order_total'].'<br />';
        }

        $content_per = array('labels'=>$sopLBL, 'values'=>$sopVAL);
        $content_total = array('labels'=>$mopLBL, 'values'=>$mopVAL);

        //var_dump($this->options);die();

        $this->optcost = array('values'=>$values, 'html'=>$html, 'content_per'=>$content_per, 'content_total'=>$content_total);
        //var_dump($this->optcost);die();
        return $this->optcost;

    }

    function getCostSubtotal() {
        if(!empty($this->subtotal)) {
            return $this->subtotal;
        }

		global $tpt_vars;
		$data_module = getModule($tpt_vars, 'BandData');
		$types_module = getModule($tpt_vars, 'BandType');
		$types = $types_module->moduleData['id'];
		$styles_module = getModule($tpt_vars, 'BandStyle');
		$styles = $styles_module->moduleData['id'];

        if(isset($types[$this->type]) && isset($styles[$this->style]) && !empty($this->sizes) && count(array_filter($this->qtyCheck))) {
        } else {
            return false;
        }

        if(!empty($this->pricingType)) { // in-house pricing
            $values['subtotal'] = 0;
            $values['subtotal_per'] = 0;
            $html['subtotal'] = '&#36;'.number_format($values['subtotal'], 2);
            $html['subtotal_per'] = '&#36;'.number_format($values['subtotal_per'], 2);

            $this->subtotal = array('values'=>$values, 'html'=>$html);
            return $this->subtotal;
        }


        if(empty($this->mfgcost)) {
            $this->getMfgCost();
        }

        if(empty($this->tcost)) {
            $this->getTransitCost();
        }

        if(empty($this->mold)) {
            $this->getMoldFees();
        }
        //var_dump($this->screen);die();
        if(empty($this->screen)) {
            $this->getScreenFees();
        }

        if(empty($this->optcost)) {
            $this->getOptionsCost();
        }

        $html = array();
        $values = array();

        $values['subtotal'] = $this->mfgcost['values']['mfg_cost_total']+
                              $this->tcost['values']['mtcost']+
                              $this->mold['values']['mold_total']+
                              $this->screen['values']['screen_total']+
                              $this->optcost['values']['options_total'];
        $values['subtotal_per'] = round($values['subtotal']/$this->total_qty, 2);

        $html['subtotal'] = '&#36;'.number_format($values['subtotal'], 2);
        $html['subtotal_per'] = '&#36;'.number_format($values['subtotal_per'], 2);

        $this->subtotal = array('values'=>$values, 'html'=>$html);
        return $this->subtotal;

    }

    function getEUSS() {
        if(!empty($this->euss)) {
            return $this->euss;
        }

		global $tpt_vars;
		$data_module = getModule($tpt_vars, 'BandData');
		$types_module = getModule($tpt_vars, 'BandType');
		$types = $types_module->moduleData['id'];
		$styles_module = getModule($tpt_vars, 'BandStyle');
		$styles = $styles_module->moduleData['id'];

        if(isset($types[$this->type]) && isset($styles[$this->style]) && !empty($this->sizes) && count(array_filter($this->qtyCheck))) {
        } else {
            return false;
        }

        if(!empty($this->pricingType)) { // in-house pricing
            $values['euss'] = 0;
            $html['euss'] = '&#36;'.number_format($values['euss'], 2);

            $this->euss = array('values'=>$values, 'html'=>$html);
            return $this->euss;
        }

        if(empty($this->subtotal)) {
            $this->getCostSubtotal();
        }

        $html = array();
        $values = array();

        $values['euss'] = round($this->subtotal['values']['subtotal']*EST_US_SHIP_MODIFIER, 2);
        $html['euss'] = '&#36;'.number_format($values['euss'], 2);

        $this->euss = array('values'=>$values, 'html'=>$html);
        return $this->euss;

    }

    function getCCFee() {
        if(!empty($this->ccfee)) {
            return $this->ccfee;
        }

		global $tpt_vars;
		$data_module = getModule($tpt_vars, 'BandData');
		$types_module = getModule($tpt_vars, 'BandType');
		$types = $types_module->moduleData['id'];
		$styles_module = getModule($tpt_vars, 'BandStyle');
		$styles = $styles_module->moduleData['id'];

        if(isset($types[$this->type]) && isset($styles[$this->style]) && !empty($this->sizes) && count(array_filter($this->qtyCheck))) {
        } else {
            return false;
        }

        if(!empty($this->pricingType)) { // in-house pricing
            $values['ccfee'] = 0;
            $html['ccfee'] = '&#36;'.number_format($values['ccfee'], 2);

            $this->ccfee = array('values'=>$values, 'html'=>$html);
            return $this->ccfee;
        }

        if(empty($this->subtotal)) {
            $this->getCostSubtotal();
        }

        if(empty($this->euss)) {
            $this->getEUSS();
        }

        $html = array();
        $values = array();

        $values['ccfee'] = round(($this->subtotal['values']['subtotal']+$this->euss['values']['euss'])*CC_FEE_MULTIPLIER, 2);
        $html['ccfee'] = '&#36;'.number_format($values['ccfee'], 2);

        $this->ccfee = array('values'=>$values, 'html'=>$html);
        return $this->ccfee;

    }

    function getCostTotal() {
        if(!empty($this->total)) {
            return $this->total;
        }

		global $tpt_vars;
		$data_module = getModule($tpt_vars, 'BandData');
		$types_module = getModule($tpt_vars, 'BandType');
		$types = $types_module->moduleData['id'];
		$styles_module = getModule($tpt_vars, 'BandStyle');
		$styles = $styles_module->moduleData['id'];

        if(isset($types[$this->type]) && isset($styles[$this->style]) && !empty($this->sizes) && count(array_filter($this->qtyCheck))) {
        } else {
            return false;
        }

        if(!empty($this->pricingType)) { // in-house pricing
            $values['total'] = 0;
            $values['total_per'] = 0;
            $html['total'] = '&#36;'.number_format($values['total'], 2);
            $html['total_per'] = '&#36;'.number_format($values['total_per'], 2);

            $this->total = array('values'=>$values, 'html'=>$html);
            return $this->total;
        }

        if(empty($this->subtotal)) {
            $this->getCostSubtotal();
        }

        if(empty($this->euss)) {
            $this->getEUSS();
        }

        if(empty($this->ccfee)) {
            $this->getCCFee();
        }

        $html = array();
        $values = array();

        $values['total'] = round($this->subtotal['values']['subtotal']+$this->euss['values']['euss']+$this->ccfee['values']['ccfee'], 2);
        $values['total_per'] = round($values['total']/$this->total_qty, 2);
        $html['total'] = '&#36;'.number_format($values['total'], 2);
        $html['total_per'] = '&#36;'.number_format($values['total_per'], 2);

        $this->total = array('values'=>$values, 'html'=>$html);
        return $this->total;

    }

    function getPrice($force_recalculate=false) {
        //var_dump($this);die();
        if(!empty($this->price) && !$force_recalculate) {
            //tpt_dump($this->price, true);
            return $this->price;
        }

		global $tpt_vars;
		$data_module = getModule($tpt_vars, 'BandData');
		$types_module = getModule($tpt_vars, 'BandType');
		$types = $types_module->moduleData['id'];
		$styles_module = getModule($tpt_vars, 'BandStyle');
		$styles = $styles_module->moduleData['id'];

		$html = array();
		$values = array();

        if(isset($types[$this->type]) && isset($styles[$this->style]) && !empty($this->sizes) && count(array_filter($this->qtyCheck))) {
        } else {
			$values['lowest_price_total'] = 0;
			//$html['lowest_price_total'] = format_price($in_house_price_total);
			$values['lowest_price_per'] = 0;
			$html = $values;
			array_walk_recursive($html, 'format_price_array');
			$this->price = array('values'=>$values, 'html'=>$html);

            return $this->price;
        }


        if(is_null($this->final_total)) {
            if(!empty($this->pricingType)) {
                // in-house pricing
                if(empty($this->weights)) {
                    $this->getWeights();
                }
    
                if(empty($this->mfgcost)) {
                    $this->getMfgCost();
                }
    
                if(empty($this->optcost)) {
                    $this->getOptionsCost();
                }
    
                //$in_house_price_total = $this->mfgcost['values']['mfg_cost_total']+$this->optcost['values']['options_total'];
                //$in_house_price_per = round($in_house_price_total/$this->total_qty, 4);
    
                //tpt_logger::dump($tpt_vars, $this->mfgcost['values']['mfg_cost_per'], debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), __FILE__, __LINE__);
                $in_house_price_per = $this->mfgcost['values']['mfg_cost_per']+$this->optcost['values']['options_per'];
                $in_house_price_total = round($in_house_price_per*$this->total_qty, 4);
    
                // calculate pricing and profits
                $values['retail_price_total'] = $in_house_price_total;
                $values['retail_price_per'] = $in_house_price_per;
                $values['retail_price_total_discounted'] = round($values['retail_price_total']-abs($values['retail_price_total'])*$this->discount/100, 2);
                $values['retail_price_per_discounted'] = round($values['retail_price_total_discounted']/$this->total_qty, 2);
                //if($_SERVER['REMOTE_ADDR'] == '85.130.3.155')
                    //var_dump($sretail_price);
                $values['customer_price_total'] = $in_house_price_total;
                $values['customer_price_per'] = $in_house_price_per;
                $values['customer_price_total_discounted'] = round($values['customer_price_total']-abs($values['customer_price_total'])*$this->discount/100, 2);
                $values['customer_price_per_discounted'] = round($values['customer_price_total_discounted']/$this->total_qty, 2);
                $values['lowest_price_total'] = $in_house_price_total;
                //$html['lowest_price_total'] = format_price($in_house_price_total);
                $values['lowest_price_per'] = $in_house_price_per;
                //$html['lowest_price_per'] = format_price($in_house_price_per);
                $values['lowest_price_total_discounted'] = round($values['lowest_price_total']-abs($values['lowest_price_total'])*$this->discount/100, INHOUSE_LOWEST_PRICE_TOTAL_DECIMALS);
                $values['lowest_price_per_discounted'] = round(floatval($values['lowest_price_total_discounted'])/$this->total_qty, INHOUSE_LOWEST_PRICE_PER_DECIMALS);
    
                //$html['retail_price_total_discounted'] = format_price($values['retail_price_total_discounted']);
                //$html['retail_price_per_discounted'] = format_price($values['retail_price_per_discounted']);
                //$html['customer_price_total_discounted'] = format_price($values['customer_price_total_discounted']);
                //$html['customer_price_per_discounted'] = format_price($values['customer_price_per_discounted']);
                //$html['lowest_price_total_discounted'] = format_price($values['lowest_price_total_discounted']);
                //$html['lowest_price_per_discounted'] = format_price($values['lowest_price_per_discounted']);
                $html = $values;
                array_walk_recursive($html, 'format_price_array');
            } else {
                if(empty($this->total)) {
                    $this->getCostTotal();
                }
    
                // calculate pricing and profits
                $values['retail_price_total'] = round(floatval($this->total['values']['total'])*3, 2);
                $values['retail_price_per'] = round($values['retail_price_total']/$this->total_qty, 2);
                $values['retail_price_total_discounted'] = round($values['retail_price_total']-abs($values['retail_price_total'])*$this->discount/100, 2);
                $values['retail_price_per_discounted'] = round($values['retail_price_total_discounted']/$this->total_qty, 2);
                $values['retail_price_total_discounted'] = round(floatval($values['retail_price_per_discounted'])*$this->total_qty, 2);
                //if($_SERVER['REMOTE_ADDR'] == '85.130.3.155')
                    //var_dump($sretail_price);
                $values['customer_price_total'] = round(floatval($this->tcost['values']['mtcost']*2+$this->mfgcost['values']['mfg_cost_total']*3+$this->mold['values']['mold_total']*2+$this->screen['values']['screen_total']*2+$this->euss['values']['euss']*2)*CUSTOMER_PRICE_MODIFIER, 2);
                $values['customer_price_per'] = round($values['customer_price_total']/$this->total_qty, 2);
                $values['customer_price_total_discounted'] = round($values['customer_price_total']-abs($values['customer_price_total'])*$this->discount/100, 2);
                $values['customer_price_per_discounted'] = round($values['customer_price_total_discounted']/$this->total_qty, 2);
                $values['customer_price_total_discounted'] = round(floatval($values['customer_price_per_discounted'])*$this->total_qty, 2);
    
                $values['lowest_price_total'] = round(floatval($this->total['values']['total'])*2, 2);
                //$html['lowest_price_total'] = format_price($values['lowest_price_total']);
                $values['lowest_price_per'] = round($values['lowest_price_total']/$this->total_qty, 2);
                //tpt_dump($this->discount, true);
                $values['lowest_price_total_discounted'] = round($values['lowest_price_total']-abs($values['lowest_price_total'])*$this->discount/100, OVERSEAS_LOWEST_PRICE_PER_DECIMALS);
                $values['lowest_price_per_discounted'] = round(floatval($values['lowest_price_total_discounted'])/$this->total_qty, OVERSEAS_LOWEST_PRICE_TOTAL_DECIMALS);
                if(defined('OVERSEAS_LOWEST_PRICE_TOTAL_RECALCULATE') && (OVERSEAS_LOWEST_PRICE_TOTAL_RECALCULATE == 1)) {
                    $values['lowest_price_total_discounted'] = round(floatval($values['lowest_price_per_discounted'])*$this->total_qty, OVERSEAS_LOWEST_PRICE_PER_DECIMALS);
                }
                //tpt_dump($values['lowest_price_per_discounted']);
    
                //$html['retail_price_total_discounted'] = '&#36;'.number_format($values['retail_price_total_discounted'], 2);
                //$html['retail_price_per_discounted'] = '&#36;'.number_format($values['retail_price_per_discounted'], 2);
                //$html['customer_price_total_discounted'] = '&#36;'.number_format($values['customer_price_total_discounted'], 2);
                //$html['customer_price_per_discounted'] = '&#36;'.number_format($values['customer_price_per_discounted'], 2);
                //$html['lowest_price_total_discounted'] = '&#36;'.number_format($values['lowest_price_total_discounted'], 2);
                //$html['lowest_price_per_discounted'] = '&#36;'.number_format($values['lowest_price_per_discounted'], 2);
                $html = $values;
                array_walk_recursive($html, 'format_price_array');
            }
    
            if(!empty($this->options['final_admin'])) {
                $values['admin_retail_price_total'] = round($values['retail_price_total']+floatval($this->options['final_admin']), 2);
                $values['admin_retail_price_total_discounted'] = round($values['admin_retail_price_total']-$values['admin_retail_price_total']*$this->discount/100, 2);
                $values['admin_retail_price_per'] = round($values['admin_retail_price_total']/$this->total_qty, 2);
                $values['admin_retail_price_per_discounted'] = round($values['admin_retail_price_total_discounted']/$this->total_qty, 2);
                $values['admin_customer_price_total'] = round($values['customer_price_total']+floatval($this->options['final_admin']), 2);
                $values['admin_customer_price_total_discounted'] = round($values['admin_customer_price_total']-$values['admin_customer_price_total']*$this->discount/100, 2);
                $values['admin_customer_price_per'] = round($values['admin_customer_price_total']/$this->total_qty, 2);
                $values['admin_customer_price_per_discounted'] = round($values['admin_customer_price_total_discounted']/$this->total_qty, 2);
                $values['admin_lowest_price_total'] = round($values['lowest_price_total']+floatval($this->options['final_admin']), 2);
                $values['admin_lowest_price_total_discounted'] = round($values['admin_lowest_price_total']-$values['admin_lowest_price_total']*$this->discount/100, 2);
                $values['admin_lowest_price_per'] = round($values['admin_lowest_price_total']/$this->total_qty, 2);
                $values['admin_lowest_price_per_discounted'] = round($values['admin_lowest_price_total_discounted']/$this->total_qty, 2);
    
    
                //$html['admin_retail_price_total_discounted'] = '&#36;'.number_format($values['admin_retail_price_total_discounted'], 2);
                //$html['admin_retail_price_per_discounted'] = '&#36;'.number_format($values['admin_retail_price_per_discounted'], 2);
                //$html['admin_customer_price_total_discounted'] = '&#36;'.number_format($values['admin_customer_price_total_discounted'], 2);
                //$html['admin_customer_price_per_discounted'] = '&#36;'.number_format($values['admin_customer_price_per_discounted'], 2);
                //$html['admin_lowest_price_total_discounted'] = '&#36;'.number_format($values['admin_lowest_price_total_discounted'], 2);
                //$html['admin_lowest_price_per_discounted'] = '&#36;'.number_format($values['admin_lowest_price_per_discounted'], 2);
    
                $html = $values;
                array_walk_recursive($html, 'format_price_array');
                $this->html['final_admin'] = '&#36;'.floatval(str_replace(',', '.', preg_replace('#[^0-9\.]+#', '', $this->options['final_admin'])));
            }
        
        } else {
                $values['retail_price_total'] = round($this->final_total, 2);
                $values['retail_price_per'] = round($this->final_total/$this->total_qty, 2);
                $values['retail_price_total_discounted'] = round($this->final_total, 2);
                $values['retail_price_per_discounted'] = round($this->final_total/$this->total_qty, 2);
                //if($_SERVER['REMOTE_ADDR'] == '85.130.3.155')
                    //var_dump($sretail_price);
                $values['customer_price_total'] = round($this->final_total, 2);
                $values['customer_price_per'] = round($this->final_total/$this->total_qty, 2);
                $values['customer_price_total_discounted'] = round($this->final_total, 2);
                $values['customer_price_per_discounted'] = round($this->final_total/$this->total_qty, 2);
                $values['lowest_price_total'] = round($this->final_total, 2);
                //$html['lowest_price_total'] = format_price($in_house_price_total);
                $values['lowest_price_per'] = round($this->final_total/$this->total_qty, 2);
                //$html['lowest_price_per'] = format_price($in_house_price_per);
                $values['lowest_price_total_discounted'] = round($this->final_total, FINAL_LOWEST_PRICE_TOTAL_DECIMALS);
                $values['lowest_price_per_discounted'] = round($this->final_total/$this->total_qty, FINAL_LOWEST_PRICE_PER_DECIMALS);
                
                $html = $values;
                array_walk_recursive($html, 'format_price_array');
        }

        $this->price = array('values'=>$values, 'html'=>$html);

        //tpt_logger::dump($tpt_vars, $this->price, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), __FILE__, __LINE__);
        //tpt_logger::dump($tpt_vars, $product_price, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), '$product_price', __FILE__.' '.__LINE__);
        tpt_logger::dump($tpt_vars, $this->price, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), '$this->price', __FILE__.' '.__LINE__);
        //tpt_dump($this->price, true, __FILE__, __LINE__);

        return $this->price;

    }


    function getProfit() {
        if(!empty($this->profit)) {
            return $this->profit;
        }

        if(!empty($this->pricingType)) { // in-house pricing
            return false;
        }

		global $tpt_vars;
		$data_module = getModule($tpt_vars, 'BandData');
		$types_module = getModule($tpt_vars, 'BandType');
		$types = $types_module->moduleData['id'];
		$styles_module = getModule($tpt_vars, 'BandStyle');
		$styles = $styles_module->moduleData['id'];

        if(isset($types[$this->type]) && isset($styles[$this->style]) && !empty($this->sizes) && count(array_filter($this->qtyCheck))) {
        } else {
            return false;
        }

        if(empty($this->price)) {
            $this->getPrice();
        }

        $html = array();
        $values = array();

        // calculate profits
        $values['retail_profit_total'] = round($this->price['values']['retail_price_total_discounted']-$this->total['values']['total'], 2);
        $values['retail_profit_per'] = round($this->price['values']['retail_price_per_discounted']-$this->total['values']['total_per'], 2);
        $values['customer_profit_total'] = round($this->price['values']['customer_price_total_discounted']-$this->total['values']['total'], 2);
        $values['customer_profit_per'] = round($this->price['values']['customer_price_per_discounted']-$this->total['values']['total_per'], 2);
        $values['lowest_profit_total'] = round($this->price['values']['lowest_price_total_discounted']-$this->total['values']['total'], 2);
        $values['lowest_profit_per'] = round($this->price['values']['lowest_price_per_discounted']-$this->total['values']['total_per'], 2);

        $html['retail_profit_total'] = '&#36;'.number_format($values['retail_profit_total'], 2);
        $html['retail_profit_per'] = '&#36;'.number_format($values['retail_profit_per'], 2);
        $html['customer_profit_total'] = '&#36;'.number_format($values['customer_profit_total'], 2);
        $html['customer_profit_per'] = '&#36;'.number_format($values['customer_profit_per'], 2);
        $html['lowest_profit_total'] = '&#36;'.number_format($values['lowest_profit_total'], 2);
        $html['lowest_profit_per'] = '&#36;'.number_format($values['lowest_profit_per'], 2);

        if(!empty($this->options['final_admin'])) {
            $values['admin_retail_profit_total'] = round($this->price['values']['admin_retail_price_total_discounted']-$this->total['values']['total'], 2);
            $values['admin_retail_profit_per'] = round($this->price['values']['admin_retail_price_per_discounted']-$this->total['values']['total_per'], 2);
            $values['admin_customer_profit_total'] = round($this->price['values']['admin_customer_price_total_discounted']-$this->total['values']['total'], 2);
            $values['admin_customer_profit_per'] = round($this->price['values']['admin_customer_price_per_discounted']-$this->total['values']['total_per'], 2);
            $values['admin_lowest_profit_total'] = round($this->price['values']['admin_lowest_price_total_discounted']-$this->total['values']['total'], 2);
            $values['admin_lowest_profit_per'] = round($this->price['values']['admin_lowest_price_per_discounted']-$this->total['values']['total_per'], 2);

            $html['admin_retail_profit_total'] = '&#36;'.number_format($values['admin_retail_profit_total'], 2);
            $html['admin_retail_profit_per'] = '&#36;'.number_format($values['admin_retail_profit_per'], 2);
            $html['admin_customer_profit_total'] = '&#36;'.number_format($values['admin_customer_profit_total'], 2);
            $html['admin_customer_profit_per'] = '&#36;'.number_format($values['admin_customer_profit_per'], 2);
            $html['admin_lowest_profit_total'] = '&#36;'.number_format($values['admin_lowest_profit_total'], 2);
            $html['admin_lowest_profit_per'] = '&#36;'.number_format($values['admin_lowest_profit_per'], 2);

            $this->html['final_admin'] = '&#36;'.floatval(str_replace(',', '.', preg_replace('#[^0-9\.]+#', '', $this->options['final_admin'])));
        }

        $this->profit = array('values'=>$values, 'html'=>$html);
        return $this->profit;

    }

    /* AIK Edits : The New Function For Stock Products Pricing! */

    static function getBulkStockCustomPricing(&$vars, $products, $custom_discount=NULL) {
        $total = 0;

        if(empty($products))
            return 0;

        //$csp = $vars['db']['handler']->getData($vars, 'tpt_custom_stock_products', '*', '', 'sku', false);
        //$csppt = $vars['db']['handler']->getData($vars, 'tpt_custom_stock_products', '*', '', 'stock_product_type_id', false);
        //$sptypes = $vars['db']['handler']->getData($vars, 'tpt_stock_products_types', '*', '', 'id', false);

        $result = array();
        $ffex = 0;
        if(!empty($products)) {
            $ffex = round(2.99/count($products), 2);
        }

        foreach($products as $product) {
            $id = $product->id;
            $sku = $product->data['sku'];
            $qty = $product->qty;

            $pdata = amz_cart::$customStockProductsData[$id];
            if(!empty($qty) && !empty($pdata)) {
                /* get the id of current stock product */

                //var_dump($stock_product);die();

                $cur_id = $pdata['stock_product_type_id'];

                /* get stock price based on qty and product id */
                $query = 'SELECT `price` FROM `tpt_stock_product_pricing` WHERE product_id = "'.$cur_id.'" AND qty <= '.$qty.' ORDER BY `qty` DESC LIMIT 1 ';
                $vars['db']['handler']->query($query, __FILE__);
                $prc = $vars['db']['handler']->fetch_assoc();

                $sprice = array('price'=>0);
                if(!empty($prc)) {
                    $sprice = $prc;
                }

                //var_dump($sprice);die();
                $mbase_price = $sprice['price'] * $qty + $ffex;
                $sbase_price = $mbase_price/$qty;

                $total += $mbase_price;
                $result[$pdata['id']] = $mbase_price;
                $product->price = array('values'=>array('sbase_price'=>$sbase_price, 'mbase_price'=>$mbase_price));
                $html = $product->price['values'];
                //array_pop($html);
                array_walk($html, 'format_price_array');
                $product->price['html'] = $html;
            } else {
                $result[$pdata['id']] = 0;
            }



        }


        return $total;
    }

    static function getStockProductPricing(&$vars, $products, $fproducts, $custom_discount=NULL) {
        $total = 0;

        if(empty($products))
            return 0;

        //$csp = $vars['db']['handler']->getData($vars, 'tpt_custom_stock_products', '*', '', 'sku', false);
        //$csppt = $vars['db']['handler']->getData($vars, 'tpt_custom_stock_products', '*', '', 'stock_product_type_id', false);
        //$sptypes = $vars['db']['handler']->getData($vars, 'tpt_stock_products_types', '*', '', 'id', false);

        $result = array();
        $ffex = 0;
        if(!empty($fproducts)) {
            $ffex = round(2.99/count($fproducts), 2);
        }

        foreach($products as $product) {
            $id = $product->id;
            $sku = $product->data['sku'];
            $qty = $product->qty;

            $pdata = amz_cart::$customStockProductsData[$id];
            if(!empty($qty) && !empty($pdata)) {
                /* get the id of current stock product */

                //var_dump($stock_product);die();

                $cur_id = $pdata['stock_product_type_id'];

                /* get stock price based on qty and product id */
                $query = 'SELECT `price` FROM `tpt_stock_product_pricing` WHERE product_id = "'.$cur_id.'" AND qty <= '.$qty.' ORDER BY `qty` DESC LIMIT 1 ';
                $vars['db']['handler']->query($query, __FILE__);
                $prc = $vars['db']['handler']->fetch_assoc();

                $sprice = array('price'=>0);
                if(!empty($prc)) {
                    $sprice = $prc;
                }

                //var_dump($sprice);die();
                $mbase_price = $sprice['price'] * $qty + $ffex;
                $sbase_price = $mbase_price/$qty;

                $total += $mbase_price;
                $result[$pdata['id']] = $mbase_price;
            } else {
                $result[$pdata['id']] = 0;
            }



        }
        $result['total'] = $total;
        //var_dump($result);die();

        return $result;
    }

    static function getStockProductPricing2(&$vars, $product, $custom_discount=NULL) {
        if(empty($product))
            return false;

        $csp = $vars['db']['handler']->getData($vars, 'tpt_custom_stock_products', '*', '', 'sku', false);

        $ffex = round(2.99/(amz_cart::$totals['products_count']+1), 2);

        $k = $product->data['sku'];
        $qty = $product->qty;

        $values = array();

        /* get the id of current stock product */
        $stock_product = $csp[$k];

        $cur_id = $stock_product['id'];

        /* get stock price based on qty and product id */
        $query = 'SELECT price FROM `tpt_stock_product_pricing` WHERE product_id = "'.$cur_id.'" AND qty_min <= '.$qty.' AND qty_max >= '.$qty;
        $vars['db']['handler']->query($query, __FILE__);
        $sprice = $vars['db']['handler']->fetch_assoc();

        //var_dump($ffex);die();
        //var_dump($sprice);die();
        //var_dump((count(amz_cart::$totals['products_count'])+1));die();
        //var_dump(round(2.99/(count(amz_cart::$totals['products_count'])+1), 2));die();
        //var_dump(amz_cart::$totals['products_count']);die();
        $values['mbase_price'] = $sprice['price'] * $qty + $ffex;
        $values['sbase_price'] = $values['mbase_price']/$qty;

        $html = $values;
        //array_pop($html);
        array_walk($html, 'format_price_array');


        return array(
                'html'=>$html,
                'values'=>$values
            );
    }

    /* AIK Edits End : The New Function For Stock Products Pricing! */


    static function getBulkPricing(&$vars, $obj=array(),$custom_discount=NULL) {
        //tpt_dump($obj, true);

        if(empty($obj))
            return false;

        //var_dump($custom_discount);die();

        $objects = array('inhouse'=>array(), 'overseas'=>array(), 'stock'=>array(), 'bundle'=>array());
        $totals = array();

        $totals = array();

        //var_dump($obj);die();
        $html = array();
        $values = array();

        foreach($obj as $product) {
            if(!is_a($product, 'amz_customproduct') && !is_a($product, 'amz_product2')) {
                if(is_a($product, 'amz_stockproduct')) {
                    $objects['stock'][] = $product->pricingObject;
                } else if(is_a($product, 'amz_bundle')) {
                    $objects['bundle'][] = $product;
                }
                else if (is_a($product, 'amz_customStockproduct'))
                {
                    $objects['stock'][] = $product;
                }
            } else {
                if($product->pricingObject->pricingType) {
                    $objects['inhouse'][] = $product;
                } else {
                    $objects['overseas'][] = $product;
                }
            }
        }

        $weights = array();
        $mfgcosts = array();
        //$moldfees = array();
        //$screenfees = array();
        $moldscreenfees = array();
        $optcosts = array();


        //$packaged_weight = 0;
        //$transit_costs = 0;
        //$mfg_costs = 0;
        //$mold_fees = 0;
        //$screen_fees = 0;
        //$options_costs = 0;
        //$costs_subtotal = 0;
        $costs_subtotal = 0;
        $cc_fee = 0;
        $est_uss = 0;
        $costs_total = 0;

        $retail_price = 0;
        $customer_price = 0;
        $lowest_price = 0;
        $in_house_price_total = 0;
        $in_house_price_total2 = 0;
        $overseas_total1 = 0;
        $overseas_total2 = 0;
        $moldscreen_fees = 0;
        $oscount = 0;
        $ihcount = 0;
        //tpt_dump($objects);
		//tpt_dump(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS));
        foreach($objects as $pt=>$pobjects) {
			//tpt_dump($pt);
            switch($pt) {
                case 'stock' :
                    if(!empty($pobjects)) {
                        foreach($pobjects as $po) {
                            //var_dump('a');
                            $retail_price += (isset($po->price['values']['mbase_price']) ? $po->price['values']['mbase_price'] : 0);
                            $customer_price += (isset($po->price['values']['mbase_price']) ? $po->price['values']['mbase_price'] : 0);
                            $lowest_price += (isset($po->price['values']['mbase_price']) ? $po->price['values']['mbase_price'] : 0);
                        }
                    }
                    break;
                case 'bundle' :
                    if(!empty($pobjects)) {
                        foreach($pobjects as $po) {
                            //var_dump('b');
                            $retail_price += (isset($po->price['values']['mbase_price']) ? $po->price['values']['mbase_price'] : 0);
                            $customer_price += (isset($po->price['values']['mbase_price']) ? $po->price['values']['mbase_price'] : 0);
                            $lowest_price += (isset($po->price['values']['mbase_price']) ? $po->price['values']['mbase_price'] : 0);
                        }
                    }
                    break;
                case 'inhouse' :
                    //var_dump($pobjects);die();
                    if(!empty($pobjects)) {
                        $ihcount = count($pobjects);
                        $ihdesigns = array();

                        $options_total = 0;
                        foreach($pobjects as $pt) {
                            //tpt_dump($pt, true);
                            $po = $pt->pricingObject;
                            $mfg_costs = 0;
                            $options_costs = 0;

                            $weights[] = $po->getWeights();
                            //$packaged_weight += $weights[count($weights)-1]['values']['packaged_weight_kg'];
                            $mfgcosts[] = $po->getMfgCost();
                            $mfg_costs = $mfgcosts[count($mfgcosts)-1]['values']['mfg_cost_total'];
                            $optcosts[] = $options_costs = $po->getOptionsCost();
                            $options_costs = $options_costs['values']['options_total'];
                            tpt_logger::dump($vars, $options_costs, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), '$options_costs', __FILE__.' '.__LINE__);//die();
                            $options_total += $options_costs;
                            //var_dump($mfg_costs);
                            //var_dump($options_costs);
                            //die();

                            $in_house_price_total += $mfg_costs+$options_costs;

                            /*
                            if(($_SERVER['REMOTE_ADDR'] == '109.160.0.218') && ($_GET['debug'] == 'debug')) {
                                //var_dump($this->pricingTable);//die();
                                //var_dump($this->total_qty);//die();
                                var_dump($po);
                                var_dump(count($mfgcosts));//die();
                                var_dump($mfgcosts[count($mfgcosts)-1]);//die();
                                var_dump($mfg_costs);//die();
                                var_dump($options_costs);//die();
                                //var_dump($this->mfgcost);

                            }
                            */

                            $pt->populateDesignIdArrayIH($vars, $ihdesigns);
                        }


                            //die('asdasdasd');
                        //tpt_dump($ihdesigns, true);
                        $mfg_costs = self::getBulkInhouseMfgCost($vars, $ihdesigns);
                        /*
                        global $tpt_vars;
                        if(!empty($tpt_vars) && !empty($this->options['rush_order'])) {
                            //var_dump('asd');die();
                            if(empty($this->mfgcost)) {
                                $this->getMfgCost();
                            }


                            $rushorder_module = getModule($tpt_vars, "RushOrder");
                            $rushorder_type = $rushorder_module->moduleData['id'][$this->options['rush_order']];
                            $rushorder_label = $rushorder_module->moduleData['id'][$this->options['rush_order']]['label2'];
                            //var_dump($rushorder_type);die();
                            //var_dump($rushorder_type['surcharge_prct']);die();
                            $values['rush_order_total'] = $values['rush_order_per']*$this->total_qty;
                        }
                        */

                        //if($_SERVER['REMOTE_ADDR'] == '109.160.0.218') {
                        //var_dump($mfg_costs);die();
                        //}
                        /*
                        if(($_SERVER['REMOTE_ADDR'] == '109.160.0.218') && ($_GET['debug'] == 'debug')) {
                            //var_dump($this->pricingTable);//die();
                            //var_dump($this->total_qty);//die();
                            var_dump($in_house_price_total);die();
                            //var_dump($this->mfgcost);

                        }
                        */

                        tpt_logger::dump($vars, $mfg_costs.' '.$options_total, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), '$mfg_costs.\' \'.$options_total', __FILE__.' '.__LINE__);//die();
                        $in_house_price_total2 = $mfg_costs+$options_total;


                    //    $discount = GLOBAL_CUSTOMPRODUCT_PRICEOFF_PERCENT/100;
                        //var_dump($custom_discount);die();
                        $discount = is_numeric($custom_discount) ? $custom_discount/100 : GLOBAL_CUSTOMPRODUCT_PRICEOFF_PERCENT/100;

                        $ipo = $in_house_price_total;
                        $in_house_price_total = round($ipo-$ipo*$discount, 2);
                        $ipo2 = $in_house_price_total2;
                        $in_house_price_total2 = round($ipo2-$ipo2*$discount, 2);
                        //if($_SERVER['REMOTE_ADDR'] != '109.160.0.218') {
                        $retail_price += $in_house_price_total2;
                        $customer_price += $in_house_price_total2;
                        $lowest_price += $in_house_price_total2;
                        //} else {
                        //$retail_price += $in_house_price_total;
                        //$customer_price += $in_house_price_total;
                        //$lowest_price += $in_house_price_total;
                        //}
                    }
                    //var_dump($pobjects);die();
                    break;
                case 'overseas' :
                default :
                    //var_dump($pobjects);die();
                    //var_dump($customer_price);die();
                    if(!empty($pobjects)) {
                        //var_dump('d');
                        $oscount = count($pobjects);

                        $packaged_weight = 0;
                        $bags = 0;
                        $mfg_costs = 0;
                        $options_costs = 0;
                        //$mold_fees = 0;
                        //$screen_fees = 0;
                        $osdesigns = array();
                        foreach($pobjects as $pt) {
                            $po = $pt->pricingObject;
                            $weights[] = $po->getWeights();
                            //var_dump($po->weights['values']);die();
                            $bags += (isset($po->weights['values']['bags']) ? $po->weights['values']['bags'] : 0);
                            $packaged_weight += $weights[count($weights)-1]['values']['bags_weight_kg'];
                            $packaged_weight += $weights[count($weights)-1]['values']['mkg'];
                            $mfgcosts[] = $po->getMfgCost();
                            $mfg_costs += $mfgcosts[count($mfgcosts)-1]['values']['mfg_cost_total'];
                            //var_dump($mfgcosts);die();
                            //var_dump($mfg_costs);die();
                            //$moldfees[] = $po->getMoldFees();
                            //$mold_fees += $moldfees[count($moldfees)-1]['values']['mold_total'];
                            //$screenfees[] = $po->getScreenFees();
                            //$screen_fees += $screenfees[count($screenfees)-1]['values']['screen_total'];
                            $optcosts[] = $ocosts = $po->getOptionsCost();
                            $options_costs += $ocosts['values']['options_total'];
                            //var_dump($ocosts);//var_dump($options_costs);
                            //$costs_subtotal += $moldfees['values']['options_total'];
                            //$costs_subtotal += $mfg_costs+$mold_fees+$screen_fees+$options_cost+$transit_costs;

                            $po->getPrice();
                            if (isset($po->price['values']['lowest_price_total_discounted'])) {
                                $overseas_total2 += round($po->price['values']['lowest_price_total_discounted'], 2);
                            }

                            $pt->populateDesignIdArrayOS($vars, $osdesigns);
                        }
						$fpo = $oneslap = reset($pobjects);
                        // SPECIAL CASE! 1000 slap bands cost $117 for transit
                        if((count($pobjects) == 1) && is_a($oneslap->pricingObject, 'amz_pricing') && ($oneslap->pricingObject->type == 5) && ($oneslap->pricingObject->total_qty == 1000)) {
                            $transit_costs = 117;
                        } else {

                            //boxes count
                            $boxes = ceil($bags/BAGS_PER_BOX);
                            //boxes weight
                            //$html['boxes_weight_OZ'] = $values['boxes_weight_OZ'] = round($values['boxes']*BOX_WEIGHT_OZ, 3);
                            //$html['boxes_weight_lbs'] = $values['boxes_weight_lbs'] = round($values['boxes_weight_OZ']*OUNCE_TO_POUND, 3);
                            //$html['boxes_weight_gr'] = $values['boxes_weight_gr'] = round($values['boxes_weight_OZ']*OUNCE_TO_GRAM, 3);
                            //$html['boxes_weight_kg'] = $values['boxes_weight_kg'] = round($values['boxes_weight_gr']*GRAM_TO_KILO, 3);
                            $boxes_weight_OZ = round($boxes*BOX_WEIGHT_OZ, 3);
                            $boxes_weight_gr = round($boxes_weight_OZ*OUNCE_TO_GRAM, 3);
                            $boxes_weight_kg = round($boxes_weight_gr*GRAM_TO_KILO, 3);
                            $packaged_weight += $boxes_weight_kg;

                            $tcost = $fpo->pricingObject->getBulkTransitCost($packaged_weight);
                            //var_dump($packaged_weight);die();
                            //var_dump($packaged_weight);die();
                            $transit_costs = $tcost['values']['mtcost'];
                        }
                        $moldscreen_fees = self::getBulkMoldScreenFees($osdesigns);
                        //if($_SERVER['REMOTE_ADDR'] == '109.160.0.218') {
                        //    var_dump($moldscreen_fees);
                        //var_dump($mfg_costs);die();
                        //}
                        //var_dump($mold_fees);//die();
                        //var_dump($moldscreen_fees['molds']['mold_total']);//die();
                        //var_dump($moldscreen_fees);die();
                        //var_dump($screen_fees);//die();
                        //var_dump($moldscreen_fees['screens']['screen_total']);//die();
                        //var_dump($options_costs);//die();
                        //var_dump($transit_costs);die();

                        //var_dump($osdesigns);//die();
                        //var_dump($moldscreen_fees);die();
                        $costs_subtotal = $mfg_costs+$moldscreen_fees['molds']['mold_total']+$moldscreen_fees['screens']['screen_total']+$options_costs+$transit_costs;
                        //var_dump($costs_subtotal);die();
                        $est_uss = round($costs_subtotal*EST_US_SHIP_MODIFIER, 2);
                        $cc_fee = round(($costs_subtotal+$est_uss)*CC_FEE_MULTIPLIER, 2);
                        $costs_total = $costs_subtotal+$est_uss+$cc_fee;
                        //var_dump($costs_total);
                        $retail_price += round(floatval($costs_total*3), 2);

                        //$customer_price += round(floatval($transit_costs*2+$mfg_costs*3+$mold_fees*2+$screen_fees*2+$est_uss*2)*CUSTOMER_PRICE_MODIFIER, 2);
                        $customer_price_overseas = round(floatval($transit_costs*2+$mfg_costs*3+$moldscreen_fees['molds']['mold_total']*2+$moldscreen_fees['screens']['screen_total']*2+$est_uss*2)*CUSTOMER_PRICE_MODIFIER, 2);

                  //      $discount = GLOBAL_CUSTOMPRODUCT_PRICEOFF_PERCENT/100;
                        //var_dump($custom_discount);die();
                        $discount = is_numeric($custom_discount) ? $custom_discount/100 : GLOBAL_CUSTOMPRODUCT_PRICEOFF_PERCENT/100;

                        $lpo = floatval($costs_total*2);
                        $lowest_price_overseas = round($lpo, 2);
                        $lowest_price_overseas = round($lpo-$lpo*$discount, 2);
                        //var_dump($lowest_price_overseas);die();
                        $customer_price += $lowest_price_overseas;
                        $overseas_total1 += $lowest_price_overseas;
                        $lowest_price += $lowest_price_overseas;
                        //var_dump($customer_price);die();
                    }
                    break;
            }
        }

        $ihc = 1;

        //if($_SERVER['REMOTE_ADDR'] == '109.160.0.218') {
        //$in_house_price_total2 = $in_house_price_total;
        //}
        if(!empty($in_house_price_total) && !empty($in_house_price_total2)) {
            //if($_SERVER['REMOTE_ADDR'] == '109.160.0.218') {
            tpt_logger::dump($vars, $in_house_price_total2.' '.$in_house_price_total, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), '$in_house_price_total2.\' \'.$in_house_price_total', __FILE__.' '.__LINE__);//die();
            //var_dump($in_house_price_total);die();
            //}
            $ihc = $in_house_price_total2/$in_house_price_total;
            //var_dump($osc);die();
        }


        $osc = 1;
        if(!empty($overseas_total1) && !empty($overseas_total2)) {

            $osc = $overseas_total1/$overseas_total2;
            //if($_SERVER['REMOTE_ADDR'] == '109.160.0.218') {
            //var_dump($overseas_total1);//die();
            //var_dump($overseas_total2);//die();
            //var_dump($osc);die();
            //}
        }

        //var_dump($overseas_total1);//die();
        //var_dump($overseas_total2);//die();
        //var_dump($customer_price);die();

        $values = array('costs_subtotal'=>$costs_subtotal, 'est_uss'=>$est_uss, 'cc_fee'=>$cc_fee, 'costs_total'=>$costs_total, 'retail_price'=>$retail_price, 'customer_price'=>$customer_price, 'lowest_price'=>$lowest_price, 'osc'=>$osc, 'oscount'=>$oscount, 'ihc'=>$ihc, 'ihcount'=>$ihcount, 'overseas_total1'=>$overseas_total1, 'overseas_total2'=>$overseas_total2, 'inhouse_total1'=>$in_house_price_total2, 'inhouse_total2'=>$in_house_price_total, 'moldscreen_fees'=>$moldscreen_fees);
        $html = $values;
        array_pop($html);
        array_walk($html, 'format_price_array');

        return array(
            'html'=>$html,
            'values'=>$values
        );
    }

    function getBulkTransitCost($packaged_weight_kg) {
        $html = array();
        $values = array();

        //var_dump($packaged_weight_kg);die();
        // determine transit costs from total package weight and according record in the db
        foreach($this->tcosts as $tc) {
            if($packaged_weight_kg <=floatval($tc['to'])) {
                $values['mtcost'] = $tc['cost'];
                if(empty($values['mtcost']))
                    $values['mtcost'] = $tc['per_kilo']*$packaged_weight_kg;
                break;
            }
        }
        if(empty($values['mtcost'])) {
            $mtcost = array_pop($this->tcosts);
            array_push($this->tcosts, $mtcost);
            $values['mtcost'] = $mtcost['per_kilo']*$packaged_weight_kg;
            //$mtcost = 'Call for quote';
            //$stcost = 'Call for quote';
        }

        //$values['stcost'] = round($values['mtcost']/$this->total_qty, 4);
        //$html['stcost'] = '&#36;'.number_format($values['stcost'], 4);
        $html['mtcost'] = '&#36;'.number_format($values['mtcost'], 2);

        return array('values'=>$values, 'html'=>$html);
    }

    static function getBulkMoldScreenFees(&$osdesigns) {
        global $tpt_vars;

		$data_module = getModule($tpt_vars, 'BandData');
		$types_module = getModule($tpt_vars, 'BandType');
		$types = $types_module->moduleData['id'];
		$styles_module = getModule($tpt_vars, 'BandStyle');
		$styles = $styles_module->moduleData['id'];

        tpt_logger::dump($tpt_vars, array_keys($osdesigns), debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), 'array_keys($osdesigns)', __FILE__.' '.__LINE__);
        //var_dump($osdesigns);die();
        $mhtml = array();
        $shtml = array();
        $mvalues = array();
        $svalues = array();
        $mvalues['base_molds_count'] = 0;
        $svalues['base_screens_count'] = 0;
        $mvalues['mold_total'] = 0;
        $svalues['screen_total'] = 0;

        foreach($osdesigns as $design) {
            $this_product = null;
            $this_qty = 0;
            if(!empty($design['molds'])) {
                $this_product = reset($design['molds']);
                foreach($design['molds'] as $p) {
                    $this_qty += $p->qty;
                }
            } else if(!empty($design['screens'])) {
                $this_product = reset($design['screens']);
                $this_product = reset($this_product);
            }
            $this_type = isset($this_product->data['band_type'])?$this_product->data['band_type']:$this_product->data['type'];
            $this_style = isset($this_product->data['band_style'])?$this_product->data['band_style']:$this_product->data['style'];
            $this_pricingType = $this_product->pricingObject->pricingType;

            $this_pricingTable = $this_product->pricingObject->pricingTable;
            $this_optionsPricingTable = $this_product->pricingObject->optionsPricingTable;


            $this_pricing_data = reset($this_product->pricingObject->pricing_data);
            $this_options_pricing_data = reset($this_product->pricingObject->options_pricing_data);

            $this_options_pricing_row = $this_product->pricingObject->options_pricing_row;

            //repeat for the options pricing table
            //foreach($this_options_pricing_data as $oprice) {

                //if($this_qty >= intval($this_options_pricing_data['qty'], 10)) {
                    //if(!isset($oprice['qty'])) {

                    //    var_dump($this_options_pricing_data);die();
                    //    var_dump($oprice);die();
                    //}
                //    $this_options_pricing_row =  $oprice;
                //} else {
                //    break;
                //}
            //}



            ////////////////////// MOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOO
            if(!empty($design['molds'])) {
                $mvalues['base_mold_count'] = 0;
                // get default mold fee data

                //total mold fee
                $mvalues['base_mold_per'] = ((($styles[$this_style]['mold']||$types[$this_type]['molds'])&&empty($this_pricingType))?(!empty($types[$this_type]['mold_fee'])?$types[$this_type]['mold_fee']:MOLD_FEE):0);
                $mvalues['base_mold_per'] = (($styles[$this_style]['mold']&&empty($this_pricingType))?(!empty($data_module->typeStyle[$this_type][$this_style]['mold_fee'])?$data_module->typeStyle[$this_type][$this_style]['mold_fee']:$mvalues['base_mold_per']):0);
                $mvalues['base_mold_per'] = (($styles[$this_style]['mold']&&empty($this_pricingType))?((!empty($this_options_pricing_row['base_mold']))?$this_options_pricing_row['base_mold']:$mvalues['base_mold_per']):0);
                $mvalues['base_mold_total'] = $mvalues['base_mold_per'];
                if(!empty($mvalues['base_mold_total'])) {
                    $mvalues['mold_total'] += $mvalues['base_mold_total'];
                    $mvalues['base_mold_count']++;
                }

                //var_dump($mvalues['base_mold_per']);die();
            }
            ///////////////////////////////////////////////////////////////////////////////////////////////////////////






            ////////////////// SCREEEEEEEEEEEEEEEEE
            if(!empty($design['screens'])) {
                $svalues['base_screen_count'] = 0;
                //$svalues['inside_screens_count'] = !empty($this->iScreens)?implode('+', $this->iScreens):'0';
                // get default screen fee data

                //var_dump($this_type);die();
                //total screen fee
                $svalues['base_screen_per'] = (!empty($types[$this_type]['screen_fee'])?$types[$this_type]['screen_fee']:SCREEN_FEE);
                $svalues['base_screen_per'] = (!empty($data_module->typeStyle[$this_type][$this_style]['screen_fee'])?$data_module->typeStyle[$this_type][$this_style]['screen_fee']:$svalues['base_screen_per']);
                $hasScreens = ($styles[$this_style]['screen'] || $types[$this_type]['screens']) && empty($this_product->pricingObject->pricingType);
                //var_dump($design['screens']);die();
                $svalues['base_screen_total'] = $svalues['base_screen_per'];
                foreach($design['screens'] as $mcolor=>$screen_design) {
                    //var_dump();
                    if(!empty($hasScreens)) {
                        $mlt = 0;
                        if(!empty($styles[$this_style]['screen'])) {
                            $mlt++;
                        }
                        if(!empty($types[$this_type]['screens'])) {
                            $mlt += $types[$this_type]['screens'];
                        }
                        $svalues['screen_total'] += $mlt*$svalues['base_screen_per'];
                        if(!empty($types[$this_type]['screens']) && ($types[$this_type]['screens'] == 1)) {
                            $svalues['screen_total'] += 5;
                        }
                        $svalues['base_screen_count'] += $mlt;
                    }

                }
            }
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
        }
        //var_dump($mvalues);
        //var_dump($svalues);
        //die();
        //var_dump($mold);
        //var_dump($mvalues);
        //var_dump($screen);
        //var_dump($svalues);
        //die();

        return array('molds'=>$mvalues, 'screens'=>$svalues);
    }


    static function getBulkInhouseMfgCost(&$vars, &$ihdesigns, $discount=0) {
        //var_dump($osdesigns);die();
        $mhtml = array();
        $shtml = array();
        $mvalues = array();
        $svalues = array();
        $cost_total = 0;

        //tpt_dump($ihdesigns);
        //var_dump($osdesigns);die();

        foreach($ihdesigns as $ihid=>$design) {
			//tpt_dump(base64_decode($ihid), false, true);
            $ihprops = base64_decode($ihid);
			$type = 0;
			$style = 0;
			parse_str($ihprops);
            //$type = explode('_-',$ihprops[0]);
            //$this_type = $type[1];
            $this_type = $type;
            //$style = explode('_-',$ihprops[1]);
            //$this_style = $style[1];
            $this_style = $style;
            $qty = $design;
            $ihproduct = new self($vars,$this_type, $this_style, array('lg'=>$qty), array(), $discount);
            $ihproduct->getMfgCost();
            //if($_SERVER['REMOTE_ADDR'] == '109.160.0.218') {
            //var_dump($ihprops);die();
            //var_dump($ihproduct);die();
            //var_dump($ihproduct);die();
            //var_dump($ihproduct);die();
            //}
            $cost_total += $ihproduct->mfgcost['values']['mfg_cost_total'];
        }


        return $cost_total;
    }


}



