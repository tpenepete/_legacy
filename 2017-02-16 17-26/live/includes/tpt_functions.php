<?php

defined('TPT_INIT') or die('access denied');

class tpt_functions {
	static function f_include(&$vars, $file, $evars=array()) {
		global $tpt_vars;
		extract($evars);
		
		$fpath = $file;
		if(!empty($vars['config']['include_file_dev_ips'][$file][$vars['user']['client_ip']])) {
			//include($vars['config']['ajax_file_dev_ips'][$file][$vars['user']['client_ip']]);
			$fpath = $vars['config']['include_file_dev_ips'][$file][$vars['user']['client_ip']];
		} else {
			//include($file);
		}
		include($fpath);
		
		return self::f_get_defined_vars($vars, get_defined_vars());
	}
	
	static function f_include_once(&$vars, $file, $evars=array()) {
		global $tpt_vars;
		extract($evars);
		
		$fpath = $file;
		if(!empty($vars['config']['include_file_dev_ips'][$file][$vars['user']['client_ip']])) {
			//tpt_dump($vars['config']['ajax_file_dev_ips'][$file][$vars['user']['client_ip']], true);
			//include_once($vars['config']['ajax_file_dev_ips'][$file][$vars['user']['client_ip']]);
			$fpath = $vars['config']['include_file_dev_ips'][$file][$vars['user']['client_ip']];
		} else {
			//include_once($file);
			//tpt_dump(array_keys($GLOBALS['GLOBALS']['GLOBALS']), true);
		}
		//tpt_dump($fpath);
		include_once($fpath);
		
		return self::f_get_defined_vars($vars, get_defined_vars());
	}
	
	static function f_require(&$vars, $file, $evars=array()) {
		global $tpt_vars;
		extract($evars);
		
		$fpath = $file;
		if(!empty($vars['config']['include_file_dev_ips'][$file][$vars['user']['client_ip']])) {
			//require($vars['config']['ajax_file_dev_ips'][$file][$vars['user']['client_ip']]);
			$fpath = $vars['config']['include_file_dev_ips'][$file][$vars['user']['client_ip']];
		} else {
			//require($file);
		}
		require($fpath);
		
		return self::f_get_defined_vars($vars, get_defined_vars());
	}
	
	static function f_require_once(&$vars, $file, $evars=array()) {
		global $tpt_vars;
		extract($evars);
		
		$fpath = $file;
		if(!empty($vars['config']['include_file_dev_ips'][$file][$vars['user']['client_ip']])) {
			//require_once($vars['config']['ajax_file_dev_ips'][$file][$vars['user']['client_ip']]);
			$fpath = $vars['config']['include_file_dev_ips'][$file][$vars['user']['client_ip']];
		} else {
			//require_once($file);
		}
		require_once($fpath);
		
		return self::f_get_defined_vars($vars, get_defined_vars());
	}
	
	static function f_include_urlrule_ajax_file(&$vars, $rule, $evars=array(), $path = TPT_PROC_DIR) {
		//include_once($file);
		global $tpt_vars;
		extract($evars);
		
		$fpath = $path.DIRECTORY_SEPARATOR.$rule['include_file'];
		if(in_array($vars['user']['client_ip'], $vars['config']['urlrule_ajax_dev_file_ips']) && !empty($rule['dev_include_file'])) {
		    //self::f_include_once($vars, TPT_PROC_DIR.DIRECTORY_SEPARATOR.$rule['dev_include_file'], $evars);
		    $fpath = $path.DIRECTORY_SEPARATOR.$rule['dev_include_file'];
		} else {
		    //self::f_include_once($vars, TPT_PROC_DIR.DIRECTORY_SEPARATOR.$rule['include_file'], $evars);
		}
		$fvars = self::f_include_once($vars, $fpath, $evars);
		
		return $fvars;
	}
	
	static function f_include_urlrule_page_file(&$vars, $rule, $evars=array(), $path = TPT_PAGES_DIR) {
		//include_once($file);
		global $tpt_vars;
		//$keys = array_keys($evars);
		//sort($keys);
		//tpt_dump($keys, true);
		//tpt_dump(array_keys($evars), true);
		//tpt_dump(array_keys($vars));
		//tpt_dump(array_keys($vars));
		
		//extract($evars);
		//tpt_dump(array_keys($vars), true);
		//var_dump(array_keys($tpt_vars));
		//die();
		//tpt_dump(array_keys($tpt_vars), true);
		
		$fpath = $path.DIRECTORY_SEPARATOR.$rule['include_file'];
		if(in_array($vars['user']['client_ip'], $vars['config']['urlrule_page_dev_file_ips']) && !empty($rule['dev_include_file'])) {
			//tpt_dump($rule, true);
			$fpath = $path.DIRECTORY_SEPARATOR.$rule['dev_include_file'];
		    //self::f_include_once($vars, TPT_PAGES_DIR.DIRECTORY_SEPARATOR.$rule['dev_include_file'], $evars);
		} else {
			//tpt_dump(TPT_PAGES_DIR.DIRECTORY_SEPARATOR.$rule['include_file'], true);
		    //self::f_include_once($vars, TPT_PAGES_DIR.DIRECTORY_SEPARATOR.$rule['include_file'], $evars);
		}
		
		//tpt_dump($fpath);
		$fvars = self::f_include_once($vars, $fpath, $evars);
		return $fvars;
	}

	static function f_include_404_page_file(&$vars, $evars=array()) {
		//include_once($file);
		global $tpt_vars;
		//$keys = array_keys($evars);
		//sort($keys);
		//tpt_dump($keys, true);
		//tpt_dump(array_keys($evars), true);
		//tpt_dump(array_keys($vars));
		//tpt_dump(array_keys($vars));

		//extract($evars);
		//tpt_dump(array_keys($vars), true);
		//var_dump(array_keys($tpt_vars));
		//die();
		//tpt_dump(array_keys($tpt_vars), true);


		$fpath = TPT_SYSTEM_PAGES_DIR.DIRECTORY_SEPARATOR.'404.php';
		//tpt_dump($fpath);
		$fvars = self::f_include_once($vars, $fpath, $evars);
		return $fvars;
	}

	static function f_include_414_page_file(&$vars, $evars=array()) {
		//include_once($file);
		global $tpt_vars;
		//$keys = array_keys($evars);
		//sort($keys);
		//tpt_dump($keys, true);
		//tpt_dump(array_keys($evars), true);
		//tpt_dump(array_keys($vars));
		//tpt_dump(array_keys($vars));

		//extract($evars);
		//tpt_dump(array_keys($vars), true);
		//var_dump(array_keys($tpt_vars));
		//die();
		//tpt_dump(array_keys($tpt_vars), true);


		$fpath = TPT_SYSTEM_PAGES_DIR.DIRECTORY_SEPARATOR.'414.php';
		//tpt_dump($fpath);
		$fvars = self::f_include_once($vars, $fpath, $evars);
		return $fvars;
	}

	static function f_include_redirect_page_file(&$vars, $evars=array()) {
		//include_once($file);
		global $tpt_vars;
		//$keys = array_keys($evars);
		//sort($keys);
		//tpt_dump($keys, true);
		//tpt_dump(array_keys($evars), true);
		//tpt_dump(array_keys($vars));
		//tpt_dump(array_keys($vars));

		//extract($evars);
		//tpt_dump(array_keys($vars), true);
		//var_dump(array_keys($tpt_vars));
		//die();
		//tpt_dump(array_keys($tpt_vars), true);


		$fpath = TPT_SYSTEM_PAGES_DIR.DIRECTORY_SEPARATOR.'redirect.php';
		//tpt_dump($fpath);
		$fvars = self::f_include_once($vars, $fpath, $evars);
		return $fvars;
	}
	
	static function &f_get_defined_vars(&$vars, $evars) {
		unset($evars['_GET']);
		unset($evars['_POST']);
		unset($evars['_COOKIE']);
		unset($evars['_SESSION']);
		unset($evars['_FILES']);
		unset($evars['_SERVER']);
		unset($evars['_REQUEST']);
		unset($evars['_ENV']);
		unset($evars['GLOBALS']);
		
		//unset($evars['tpt_vars']);
		unset($evars['vars']);
		unset($evars['evars']);
		unset($evars['fvars']);
		unset($evars['fpath']);
		unset($evars['file']);
		//unset($evars['rule']);
		
		return $evars;
	}


	static function getGoogleEcommerceCode(&$vars, $order_id, $product, $totals) {
		$qtty = $product->qty;
		$category = 'Builder Products';
		$sku = $product->getSku($vars);
		$prrice = $product->price['values']['sbase_price'];

		$ga_products_script = '';
		$iid = $order_id;
		$rvn = $totals['Total_Price'];
		$shp = $totals['Shipping'];
		$ttx = $totals['Tax'];
		$ga_products_script .= <<< EOT
ga('ecommerce:addTransaction', {
  'id': '$iid',                     // Transaction ID. Required.
  'affiliation': 'Amazing Wristbands Order',   // Affiliation or store name.
  'revenue': '$rvn',               // Grand Total.
  'shipping': '$shp',                  // Shipping.
  'tax': '$ttx'                     // Tax.
});
EOT;

		$ga_products_script .= <<< EOT
ga('ecommerce:addItem', {
  'id': '$iid',                     // Transacton ID. Required.
  'name': 'AMZG Product',    // Product name. Required.
  'sku': '$sku',                 // SKU/code.
  'category': '$category',         // Category or variation.
  'price': '$prrice',                 // Unit price.
  'quantity': '$qtty'                   // Quantity.
});
EOT;
		return $ga_products_script;
	}
}