<?php
//tpt_dump('asd');
defined('TPT_INIT') or die('access denied');

class tpt_module_users extends tpt_Module  {

	function __construct(&$vars, $name, $moduleClassFile, $moduleClass, $moduleTable) {
		$fields = array(
			//db field name|field type|length|options|storage options|control|ctrAttr|default|label|index by|split keys|template
			new tpt_ModuleField('id',    'n', null, 'ai', '',         'sp', '', '', '',           false, false,  'LC'),
			new tpt_ModuleField('username',  's', 255,  '',   '',         'tf', ' style="width: 70px;"', '', 'Username (Email)', false, false, 'LC'),
			new tpt_ModuleField('password',  's', 255,  '',   '',         'tf', ' style="width: 70px;"', '', 'Password', false, false, 'LC'),
			new tpt_ModuleField('title',  's', 8,  '',   '',         'tf', ' style="width: 70px;"', '', 'Title', false, false, 'LC'),
			new tpt_ModuleField('fname',  's', 255,  '',   '',         'tf', ' style="width: 70px;"', '', 'First Name', false, false, 'LC'),
			new tpt_ModuleField('mname',  's', 255,  '',   '',         'tf', ' style="width: 70px;"', '', 'Middle Name', false, false, 'LC'),
			new tpt_ModuleField('lname',  's', 255,  '',   '',         'tf', ' style="width: 70px;"', '', 'Last Name', false, false, 'LC'),
			new tpt_ModuleField('company',  's', 1024,  '',   '',         'tf', ' style="width: 70px;"', '', 'Company', false, false, 'LC'),
			new tpt_ModuleField('address1',  's', 255,  '',   '',         'tf', ' style="width: 70px;"', '', 'Address Line 1', false, false, 'LC'),
			new tpt_ModuleField('address2',  's', 255,  '',   '',         'tf', ' style="width: 70px;"', '', 'Address Line 2', false, false, 'LC'),
			new tpt_ModuleField('address3',  's', 255,  '',   '',         'tf', ' style="width: 70px;"', '', 'Address Line 3', false, false, 'LC'),
			new tpt_ModuleField('country',   'i', 11,   '',   '', 'tf', ' style="width: 230px;"', '', 'Country ID', false, false, 'LC'),
			new tpt_ModuleField('city',  's', 255,  '',   '',         'tf', ' style="width: 70px;"', '', 'City', false, false, 'LC'),
			new tpt_ModuleField('state',  's', 32,  '',   '',         'tf', ' style="width: 70px;"', '', 'State', false, false, 'LC'),
			new tpt_ModuleField('zip',  's', 32,  '',   '',         'tf', ' style="width: 70px;"', '', 'ZIP Code', false, false, 'LC'),
			new tpt_ModuleField('phone',  's', 64,  '',   '',         'tf', ' style="width: 70px;"', '', 'Phone', false, false, 'LC'),
			new tpt_ModuleField('po_box',   'ti', '',   '',   '', 'tf', ' style="width: 230px;"', '', 'Is PO Box Address?', false, false, 'LC'),
			new tpt_ModuleField('newsletter',   'ti', '',   '',   '', 'tf', ' style="width: 230px;"', '', 'Subscribed to Newsletter?', false, false, 'LC'),
			new tpt_ModuleField('activation_code',  's', 255,  '',   '',         'tf', ' style="width: 70px;"', '', 'Activation Code', false, false, 'LC'),
			new tpt_ModuleField('resetpass_code',  's', 255,  '',   '',         'tf', ' style="width: 70px;"', '', 'Reset Password Code', false, false, 'LC'),
			new tpt_ModuleField('banned',   'ti', '',   '',   '', 'tf', ' style="width: 230px;"', 0, 'Banned?', false, false, 'LC'),
			new tpt_ModuleField('last_login',   'i', 11,   '',   '', 'tf', ' style="width: 230px;"', 0, 'Last Login Time', false, false, 'LC'),
			new tpt_ModuleField('last_login_ip',  's', 64,  '',   'intval10',         'tf', ' style="width: 70px;"', '', 'Last Login Ip', false, false, 'LC'),
			new tpt_ModuleField('registered_from_ip',  's', 64,  '',   'intval10',         'tf', ' style="width: 70px;"', '', 'Registered From Ip', false, false, 'LC'),
			new tpt_ModuleField('usertype',   'i', 11,   '',   '', 'tf', ' style="width: 230px;"', 1, 'Usertype', false, false, 'LC'),
			new tpt_ModuleField('admin',   'i', 11,   '',   '', 'tf', ' style="width: 230px;"', 0, 'Admin User', false, false, 'LC'),
			new tpt_ModuleField('super_admin',   'i', 11,   '',   '', 'tf', ' style="width: 230px;"', 0, 'Super Admin', false, false, 'LC'),
			new tpt_ModuleField('developer',   'i', 11,   '',   '', 'tf', ' style="width: 230px;"', 0, 'Developer', false, false, 'LC'),
			new tpt_ModuleField('sales_admin',   'i', 11,   '',   '', 'tf', ' style="width: 230px;"', 0, 'Sales Admin', false, false, 'LC'),
			new tpt_ModuleField('designer',   'i', 11,   '',   '', 'tf', ' style="width: 230px;"', 0, 'Designer', false, false, 'LC'),
			new tpt_ModuleField('manufacturer',   'i', 11,   '',   '', 'tf', ' style="width: 230px;"', 0, 'Manufacturer', false, false, 'LC'),
			new tpt_ModuleField('factory',   'i', 11,   '',   '', 'tf', ' style="width: 230px;"', 0, 'tpt_module_factories id', false, false, 'LC'),
			new tpt_ModuleField('manufacturer_company',   'i', 11,   '',   '', 'tf', ' style="width: 230px;"', 0, '(Deprecated) Manufacturer Company', false, false, 'LC'),
			new tpt_ModuleField('manufacturer_country',  's', 255,  '',   '',         'tf', ' style="width: 70px;"', '', 'Manufacturer\'s Country', false, false, 'LC'),
			new tpt_ModuleField('access_level',   'i', 11,   '',   '', 'tf', ' style="width: 230px;"', 0, 'Access Level', false, false, 'LC'),
			new tpt_ModuleField('registered_user',   'i', 11,   '',   '', 'tf', ' style="width: 230px;"', 0, 'Is Login Account', false, false, 'LC'),
			new tpt_ModuleField('same_address',   'ti', '',   '',   '', 'tf', ' style="width: 230px;"', 0, 'Same As Billing Address Checked', false, false, 'LC'),
			new tpt_ModuleField('shipping_title',  's', 8,  '',   '',         'tf', ' style="width: 70px;"', '', 'Shipping Address Title', false, false, 'LC'),
			new tpt_ModuleField('shipping_fname',  's', 255,  '',   '',         'tf', ' style="width: 70px;"', '', 'Shipping Address First Name', false, false, 'LC'),
			new tpt_ModuleField('shipping_mname',  's', 255,  '',   '',         'tf', ' style="width: 70px;"', '', 'Shipping Address Middle Name', false, false, 'LC'),
			new tpt_ModuleField('shipping_lname',  's', 255,  '',   '',         'tf', ' style="width: 70px;"', '', 'Shipping Address Last Name', false, false, 'LC'),
			new tpt_ModuleField('shipping_company',  's', 1024,  '',   '',         'tf', ' style="width: 70px;"', '', 'Shipping Address Company', false, false, 'LC'),
			new tpt_ModuleField('shipping_address1',  's', 255,  '',   '',         'tf', ' style="width: 70px;"', '', 'Shipping Address Address Line 1', false, false, 'LC'),
			new tpt_ModuleField('shipping_address2',  's', 255,  '',   '',         'tf', ' style="width: 70px;"', '', 'Shipping Address Address Line 2', false, false, 'LC'),
			new tpt_ModuleField('shipping_address3',  's', 255,  '',   '',         'tf', ' style="width: 70px;"', '', 'Shipping Address Address Line 3', false, false, 'LC'),
			new tpt_ModuleField('shipping_country',   'i', 11,   '',   '', 'tf', ' style="width: 230px;"', '', 'Shipping Address Country ID', false, false, 'LC'),
			new tpt_ModuleField('shipping_city',  's', 255,  '',   '',         'tf', ' style="width: 70px;"', '', 'Shipping Address City', false, false, 'LC'),
			new tpt_ModuleField('shipping_state',  's', 32,  '',   '',         'tf', ' style="width: 70px;"', '', 'Shipping Address State', false, false, 'LC'),
			new tpt_ModuleField('shipping_zip',  's', 32,  '',   '',         'tf', ' style="width: 70px;"', '', 'Shipping Address ZIP Code', false, false, 'LC'),
			new tpt_ModuleField('shipping_phone',  's', 64,  '',   '',         'tf', ' style="width: 70px;"', '', 'Shipping Address Phone', false, false, 'LC'),
			new tpt_ModuleField('shipping_po_box',   'ti', '',   '',   '', 'tf', ' style="width: 230px;"', '', 'Shipping Address Is PO Box Address?', false, false, 'LC'),
			new tpt_ModuleField('tax_exempt',   'i', 11,   '',   '', 'tf', ' style="width: 230px;"', 0, 'Is Tax Exempt?', false, false, 'LC'),
			new tpt_ModuleField('tef_upload',  's', 1024,  '',   '',         'tf', ' style="width: 70px;"', '', 'Tef Upload', false, false, 'LC'),
			new tpt_ModuleField('created_date',   'i', 11,   '',   '', 'tf', ' style="width: 230px;"', '', 'Registration Time', false, false, 'LC'),
			new tpt_ModuleField('deleted',   'ti', '',   '',   '', 'tf', ' style="width: 230px;"', 0, 'Deleted?', false, false, 'LC'),
			new tpt_ModuleField('last_cart_id',   'i', 11,   '',   '', 'tf', ' style="width: 230px;"', 0, 'Last Cart Id', false, false, 'LC'),
			new tpt_ModuleField('abandoned_cart_notification',   'ti', '',   '',   '', 'tf', ' style="width: 230px;"', 0, 'Notify User About Last Cart', false, false, 'LC'),
			new tpt_ModuleField('enabled',   'ti', '',   '',   '', 'tf', ' style="width: 230px;"', 1, 'Enabled?', false, false, 'LC'),
		);
		parent::__construct($vars, $name, $moduleClassFile, $moduleClass, $moduleTable, $fields, 'id');
	}

	function update_store_cart_id(&$vars, $user_id=0, $cart_id=0) {
		$tptlogsdb = DB_DB_TPT_LOGS;
		$module_table = $this->moduleTable;

		$query = <<< EOT
        SELECT `products` FROM `$tptlogsdb`.`tpt_request_cart` WHERE `id`=$cart_id
EOT;
		$vars['db']['handler']->query($query, __FILE__);
		$cart = $vars['db']['handler']->fetch_assoc();

		$p = unserialize($cart['products']);
		if(!empty($p)) {
			$query = <<< EOT
        UPDATE `$module_table` SET `last_cart_id`=$cart_id WHERE `id`=$user_id
EOT;

			$vars['db']['handler']->query($query, __FILE__);
		} else {
			$query = <<< EOT
        UPDATE `$module_table` SET `last_cart_id`=0 WHERE `id`=$user_id
EOT;

			$vars['db']['handler']->query($query, __FILE__);
		}
	}

	function get_user_cookie_string(&$vars, $id=0, $username='') {
		//tpt_dump($id);
		//tpt_dump($username, true);
		$time = $vars['environment']['request_time'];
		if(!empty($id)) {
			$logged_user = base64_encode(encode_string($id.' '.$username.' '.$time, $vars['config']['key']));
			//tpt_dump($logged_user, true);
			return $logged_user;
		} else {
			return '';
		}
	}

	function get_user_id_from_cookie(&$vars, $cookie) {
		if(empty($cookie)) {
			return false;
		} else {
			$logged_user = explode(' ', encode_string(base64_decode($cookie), $vars['config']['key']));
		}

		return $logged_user[0];
	}


	function get_abandoned_carts_list(&$vars, $user_id, $order='DESC') {
		$query = <<< EOT
        SELECT * FROM `tpt_users_lost_carts` WHERE `user_id`=$user_id ORDER BY `timestamp` $order
EOT;
		$vars['db']['handler']->query($query, __FILE__);
		$ac_list = $vars['db']['handler']->fetch_assoc_list('', false);

		$ac_arr = array();
		foreach($ac_list as $ac) {
			if(!empty($ac['enabled'])) {
				$ac_arr[$ac['id']] = $ac['cart_id'];
			}
		}

		return $ac_arr;
	}


	function get_abandoned_carts_data(&$vars, $user_id, $order='DESC') {
		$tptlogsdb = DB_DB_TPT_LOGS;

		$query = <<< EOT
        SELECT `tpt_users_lost_carts`.`id` AS `rowid`, `tpt_users_lost_carts`.`cart_id`, `tpt_users_lost_carts`.`enabled`, `$tptlogsdb`.`tpt_request_cart`.*
        FROM `tpt_users_lost_carts`
        LEFT JOIN `$tptlogsdb`.`tpt_request_cart`
        ON `tpt_users_lost_carts`.`cart_id`=`$tptlogsdb`.`tpt_request_cart`.`id`
        WHERE `tpt_users_lost_carts`.`user_id`=$user_id AND `tpt_users_lost_carts`.`enabled`=1
        ORDER BY `timestamp` $order
EOT;
		$vars['db']['handler']->query($query);
		$ac_list = $vars['db']['handler']->fetch_assoc_list('', false);

		$ac_arr = array();
		foreach($ac_list as $ac) {
			$ac_arr[$ac['rowid']] = $ac;
		}

		return $ac_arr;
	}

	function set_abandoned_cart_notification(&$vars, $user_id, $state=0) {
		$module_table = $this->moduleTable;
		$state = intval($state, 10);
		$query = <<< EOT
        UPDATE `$module_table` SET `abandoned_cart_notification`=$state WHERE `id`=$user_id
EOT;

		$vars['db']['handler']->query($query, __FILE__);
	}


	function get_abandoned_cart_notification(&$vars, $user_id) {
		$module_table = $this->moduleTable;

		$query = <<< EOT
        SELECT `abandoned_cart_notification` FROM `$module_table` WHERE `id`=$user_id
EOT;

		$vars['db']['handler']->query($query, __FILE__);
		$state = $vars['db']['handler']->fetch_assoc();

		if(!empty($state['abandoned_cart_notification'])) {
			return 1;
		} else {
			return 0;
		}
	}

	function getAdminOrderAddressString(&$vars, &$aarr, $delimiter='&#10;') {
		$values = array(
			'fname'=>$aarr['fname'],
			'lname'=>$aarr['lname'],
			'company'=>$aarr['company'],
			'address1'=>$aarr['address1'],
			'address2'=>$aarr['address2'],
			'address3'=>$aarr['address3'],
			'city'=>$aarr['city'],
			'state'=>$aarr['state'],
			'zip'=>$aarr['zip'],
			'country'=>$aarr['country'],

		);

		if(!empty($values['company'])) {
			$values['company'] =  $values['company']; // Edited My BH previousliy it was $values['company'] =  '&#10;'.$values['company'];
		}
		if(!empty($values['address2'])) {
			$values['address2'] = ','.$values['address2'];
		}
		if(!empty($values['address3'])) {
			$values['address3'] = ','.$values['address2'];
		}
		if(!empty($values['city'])) { //added by BH...
			$values['combine'] = $values['city'].', '.$values['state'].' '.$values['zip'];
		} //added by BH...
		$values = array(
			'fname lname'=>trim($values['fname']).' '.trim($values['lname']),
			'company'=>$values['company'],
			'address1,address2,address3'=>$values['address1'].$values['address2'].$values['address3'],
			'combine'=>$values['combine'], //added by BH...
			// 'state'=>$values['state'],
			// 'zip'=>$values['zip'],
			'country'=>$values['country'],
		);

		$values = array_filter($values);
		return implode($delimiter, $values);
	}


	function getAdminOrderAddressString2(&$vars, &$aarr, $delimiter='|') {
		$values = array(
			'address1'=>$aarr['address1'],
			'address2'=>$aarr['address2'],
			'address3'=>$aarr['address3'],
			'city'=>$aarr['city'],
			'state'=>$aarr['state'],
			'zip'=>$aarr['zip'],
			'country'=>$aarr['country'],
			'company'=>$aarr['company'],
		);

		if(!empty($values['address2'])) {
			$values['address2'] = '@'.$values['address2'];
		}
		if(!empty($values['address3'])) {
			$values['address3'] = ','.$values['address3'];
		}
		$address = array_shift($values);
		$address .= array_shift($values);
		$address .= array_shift($values);
		$values = array('address'=>$address)+$values;
		return implode($delimiter, $values);
	}


	function getUserData(&$vars, $username, $password, $registered_user=0, $case_insensitive_username=1) {
		$users_table = $this->moduleTable;

		$status = -1;
		$data = array();

		if(!empty($username) && !empty($password)) {
			$wusername = 'LOWER(`username`)="' . mysql_real_escape_string(strtolower($username)) . '"';
			if(empty($case_insensitive_username)) {
				$wusername = '`username`="' . mysql_real_escape_string($username) . '"';
			}
			$wpassword = '`password`="'.mysql_real_escape_string(sha1($password)).'"';
			$wregistered_user = '';
			if(!empty($registered_user)) {
				$wregistered_user = 'AND `registered_user`='.intval($registered_user, 10);
			}

			$query = <<< EOT
SELECT * FROM
(
	SELECT
		*
	FROM

	(

		SELECT
			*
		FROM
		(
			SELECT
				1 AS `has_username`
		) AS `a`
		WHERE
			EXISTS(
				SELECT * FROM `$users_table` WHERE $wusername $wregistered_user
			)

		UNION

		SELECT
			*
		FROM
		(
			SELECT
				0 AS `has_username`
		) AS `a`
		WHERE
			NOT EXISTS(
				SELECT * FROM `$users_table` WHERE $wusername $wregistered_user
			)
	) AS `a`,
	(

		SELECT
			*
		FROM
		(
			SELECT
				1 AS `has_password`
		) AS `b`
		WHERE
			EXISTS(
				SELECT * FROM `$users_table` WHERE $wusername AND $wpassword $wregistered_user
			)

		UNION

		SELECT
			*
		FROM
		(
			SELECT
				0 AS `has_password`
		) AS `b`
		WHERE
			NOT EXISTS(
				SELECT * FROM `$users_table` WHERE $wusername AND $wpassword $wregistered_user
			)
	) AS `b`

) AS `a`
LEFT JOIN
(
	SELECT * FROM `$users_table` WHERE $wusername AND $wpassword $wregistered_user
) AS `d`
ON 1=1
EOT;
			$vars['db']['handler']->query($query);
			$data = $vars['db']['handler']->fetch_assoc();

			if(empty($data)) {
				$data = array();
				$status = 5;
			} else if(empty($data['has_username'])) {
				$data = array();
				$status = 3;
			} else if(empty($data['has_password'])) {

				$query = 'SELECT * FROM `'.$users_table.'` WHERE `username`="'.mysql_real_escape_string($username).'" AND `registered_user`=1 AND `admin`=1';
				$vars['db']['handler']->query($query);
				$data = $vars['db']['handler']->fetch_assoc();
				if(!empty($data)) {
					if(empty($data['password'])) {
						$query = 'UPDATE `'.$users_table.'` SET `password`="'.mysql_real_escape_string(sha1($password)).'" WHERE `username`="'.mysql_real_escape_string($username).'" AND `registered_user`=1 AND `admin`=1';
						$vars['db']['handler']->query($query);

						return $this->getUserData($vars, $username, $password, $registered_user, $case_insensitive_username);
					}


				}


				$data = array();
				$status = 4;
			} else {
				unset($data['has_username']);
				unset($data['has_password']);
				$status = 1;
			}
		}


		return array(
			'status'=>$status,
			'data'=>$data
		);
	}

	function getUserDataId(&$vars, $id) {
		$users_table = $this->moduleTable;

		$status = -1;
		$data = array();

		if(!empty($id)) {
			$wid = '`id`='.intval($id, 10);

			$query = <<< EOT
SELECT * FROM `$users_table` WHERE $wid
EOT;
			$vars['db']['handler']->query($query);
			$data = $vars['db']['handler']->fetch_assoc();

			if(empty($data)) {
				$data = array();
				$status = 2;
			}  else {
				$status = 1;
			}
		}

		return array(
			'status'=>$status,
			'data'=>$data
		);
	}


	function getUser(&$vars, $username, $password) {

		/*
		$query = 'SELECT * FROM `'.$users_table.'` WHERE `id`='.$id;
		//tpt_dump($query, true);
		$vars['db']['handler']->query($query);
		*/
		$res = $this->getUserData($vars, $username, $password);
		$userdata = $res['data'];
		$status = $res['status'];

		if(!empty($userdata)) {
			$userdata['username'] = $username;

			return array(
				'data' => $this->formatUserData($vars, $userdata),
				'status' => $status
			);
		} else {
			return array(
				'data' => $this->formatUserData($vars, array()),
				'status' => $status
			);
		}
	}



	function getUserId(&$vars, $id) {

		/*
		$query = 'SELECT * FROM `'.$users_table.'` WHERE `id`='.$id;
		//tpt_dump($query, true);
		$vars['db']['handler']->query($query);
		*/
		$res = $this->getUserDataId($vars, $id);
		$userdata = $res['data'];
		$status = $res['status'];

		return array(
			'data'=>$this->formatUserData($vars, $userdata),
			'status'=>$status
		);
	}



	function formatUserData(&$vars, $userdata) {

		/*
		$query = 'SELECT * FROM `'.$users_table.'` WHERE `id`='.$id;
		//tpt_dump($query, true);
		$vars['db']['handler']->query($query);
		*/
		//tpt_dump($userdata);

		if(empty($userdata))
			return array();

		//$userdata = reset($userdata);


		$user = array();

		$user['username'] = $userdata['username'];
		$user['userid'] = $userdata['id'];
		$user['data'] = $userdata;

		$user['addresses']['payment'] = array(
			'id'=>0,
			'address_name'=>'payment',
			'title'=>$user['data']['title'],
			'fname'=>$user['data']['fname'],
			'mname'=>$user['data']['mname'],
			'lname'=>$user['data']['lname'],
			'company'=>$user['data']['company'],
			'address1'=>$user['data']['address1'],
			'address2'=>$user['data']['address2'],
			'address3'=>$user['data']['address3'],
			'country'=>$user['data']['country'],
			'city'=>$user['data']['city'],
			'state'=>$user['data']['state'],
			'zip'=>$user['data']['zip'],
			'phone'=>$user['data']['phone'],
			'po_box'=>$user['data']['po_box']
		);
		if($user['data']['same_address']) {
			$user['addresses']['shipping'] = $user['addresses']['payment'];
			$user['addresses']['shipping']['id'] = 1;
			$user['addresses']['shipping']['address_name'] = 'shipping';
		} else {
			$user['addresses']['shipping'] = array(
				'id'=>1,
				'address_name'=>'shipping',
				'title'=>$user['data']['shipping_title'],
				'fname'=>$user['data']['shipping_fname'],
				'mname'=>$user['data']['shipping_mname'],
				'lname'=>$user['data']['shipping_lname'],
				'company'=>$user['data']['shipping_company'],
				'address1'=>$user['data']['shipping_address1'],
				'address2'=>$user['data']['shipping_address2'],
				'address3'=>$user['data']['shipping_address3'],
				'country'=>$user['data']['shipping_country'],
				'city'=>$user['data']['shipping_city'],
				'state'=>$user['data']['shipping_state'],
				'zip'=>$user['data']['shipping_zip'],
				'phone'=>$user['data']['shipping_phone'],
				'po_box'=>$user['data']['shipping_po_box']
			);
		}
		$user['addresses']['shipping_data'] = array(
			'id'=>2,
			'address_name'=>'shipping',
			'title'=>$user['data']['shipping_title'],
			'fname'=>$user['data']['shipping_fname'],
			'mname'=>$user['data']['shipping_mname'],
			'lname'=>$user['data']['shipping_lname'],
			'company'=>$user['data']['shipping_company'],
			'address1'=>$user['data']['shipping_address1'],
			'address2'=>$user['data']['shipping_address2'],
			'address3'=>$user['data']['shipping_address3'],
			'country'=>$user['data']['shipping_country'],
			'city'=>$user['data']['shipping_city'],
			'state'=>$user['data']['shipping_state'],
			'zip'=>$user['data']['shipping_zip'],
			'phone'=>$user['data']['shipping_phone'],
			'po_box'=>$user['data']['shipping_po_box']
		);

		$user['isLogged'] = false;


		return $user;
	}


	function get_tax_class(&$vars, $id) {
		$countries_module = getModule($vars, "Countries");
		$user = $this->getUserId($vars, $id);
		$user = $user['data'];

		$country_id = $user['addresses']['shipping']['country'];
		$stateval = $user['addresses']['shipping']['state'];


		return $countries_module->getCountryStateTax($vars, $country_id, $stateval);
	}

	function getUserEmail(&$vars, $id) {
		$user = $this->getUserId($vars, $id);

		return $user['username'];
	}
	function getUserFullName(&$vars, $id) {
		$user = $this->getUserId($vars, $id);

		return $user['addresses']['payment']['fname'].' '.$user['addresses']['payment']['lname'];
	}

	function getUserDataArray(&$vars, $id) {
		$countries_module = getModule($vars, "Countries");
		$countries = $countries_module->moduleData['id'];


		$user = $this->getUserId($vars, $id);
		$user = $user['data'];

		$p_country = $countries[$user['addresses']['payment']['country']]['name'];
		$p_state   = $countries_module->getStateName($vars, $user['addresses']['payment']['country'], $user['addresses']['payment']['state']);
		$s_country = $countries[$user['addresses']['shipping']['country']]['name'];
		$s_state   = $countries_module->getStateName($vars, $user['addresses']['shipping']['country'], $user['addresses']['shipping']['state']);


		// shipping
		$bcsa = $user['addresses']['shipping'];
		$bcsa['country'] = $s_country;
		$bcsa['state'] = $s_state;


		$customer_shipping_address = $this->getAdminOrderAddressString($vars, $bcsa);
		$backend_cus_shipping = $this->getAdminOrderAddressString2($vars, $bcsa);


		// payment
		$bcpa = $user['addresses']['payment'];
		$bcpa['country'] = $p_country;
		$bcpa['state'] = $p_state;
		$customer_billing_address = $this->getAdminOrderAddressString($vars, $bcpa);
		//tpt_dump($customer_billing_address, true);
		$backend_cus_billing = $this->getAdminOrderAddressString2($vars, $bcpa);


		$customer_fields = array(
			'shipping'=>array(),
			'payment'=>array(),
			'general'=>array()
		);
		$customer_fields['shipping'] = array(
			'customer_shipping_address'=>$customer_shipping_address,
			'backend_cus_shipping'=>$backend_cus_shipping,
			'backend_shipping_name'=>$user['addresses']['shipping']['fname'] . '|' . $user['addresses']['shipping']['lname']
		);
		$customer_fields['payment'] = array(
			'customer_billing_address'=>$customer_billing_address,
			'backend_cus_billing'=>$backend_cus_billing,
			'backend_billing_name'=>$user['addresses']['payment']['fname'] . '|' . $user['addresses']['payment']['lname']
		);
		$customer_fields['general'] = array(
			'customer_name'=>$user['addresses']['payment']['fname'] . ' ' . $user['addresses']['payment']['lname'],
			'customer_email_id'=>$user['data']['username'],
			'ip'=>(isset($user['client_ip']) ? $user['client_ip'] : ''),
			'customer_phone'=>$user['data']['phone'],
			'customer_mobile'=>$user['data']['phone'],
			'zipcode'=>$user['addresses']['payment']['zip'],
			'customer_id'=>$user['data']['id']
		);

		return $customer_fields;
	}

	function getUserShippingDataArray(&$vars, $id) {
		$countries_module = getModule($vars, "Countries");
		$countries = $countries_module->moduleData['id'];

		$user = $this->getUserId($vars, $id);

		//$p_country = $countries[$user['addresses']['payment']['country']]['name'];
		//$p_state   = $countries_module->getStateName($vars, $user['addresses']['payment']['country'], $user['addresses']['payment']['state']);
		$s_country = $countries[$user['addresses']['shipping']['country']]['name'];
		$s_state   = $countries_module->getStateName($vars, $user['addresses']['shipping']['country'], $user['addresses']['shipping']['state']);


		// shipping
		$bcsa = $user['addresses']['shipping'];
		$bcsa['country'] = $s_country;
		$bcsa['state'] = $s_state;
		//$customer_shipping_address = tpt_users::getAdminOrderAddressString($vars, $bcsa);
		//$backend_cus_shipping = tpt_users::getAdminOrderAddressString2($vars, $bcsa);


		// payment
		//$bcpa = $user['addresses']['payment'];
		//$bcpa['country'] = $p_country;
		//$bcpa['state'] = $p_state;
		//$customer_billing_address = tpt_users::getAdminOrderAddressString($vars, $bcpa);
		//tpt_dump($customer_billing_address, true);
		//$backend_cus_billing = tpt_users::getAdminOrderAddressString2($vars, $bcpa);


		$customer_fields = array(
			'shipping'=>array(),
			//'payment'=>array(),
			//'general'=>array()
		);
		$customer_fields['shipping'] = array(
			'fname'=>$user['addresses']['shipping']['fname'],
			'lname'=>$user['addresses']['shipping']['lname'],
			'company'=>$user['addresses']['shipping']['company'],
			'address1'=>$user['addresses']['shipping']['address1'],
			'address2'=>$user['addresses']['shipping']['address2'],
			'address3'=>$user['addresses']['shipping']['address3'],
			'city'=>$user['addresses']['shipping']['city'],
			'state'=>$bcsa['state'],
			'zip'=>$user['addresses']['shipping']['zip'],
			'country'=>$bcsa['country'],
			//'customer_shipping_address'=>$customer_shipping_address,
			//'backend_cus_shipping'=>$backend_cus_shipping,
			//'backend_shipping_name'=>$user['addresses']['shipping']['fname'] . '|' . $user['addresses']['shipping']['lname']
		);
		//$customer_fields['payment'] = array(
		//    'customer_billing_address'=>$customer_billing_address,
		//    'backend_cus_billing'=>$backend_cus_billing,
		//    'backend_billing_name'=>$user['addresses']['payment']['fname'] . '|' . $user['addresses']['payment']['lname']
		//                      );
		//$customer_fields['general'] = array(
		//    'customer_name'=>$user['addresses']['payment']['fname'] . ' ' . $user['addresses']['payment']['lname'],
		//    'customer_email_id'=>$user['data']['username'],
		//    'ip'=>$user['client_ip'],
		//    'customer_phone'=>$user['data']['phone'],
		//    'customer_mobile'=>$user['data']['phone'],
		//    'zipcode'=>$user['addresses']['payment']['zip'],
		//    'customer_id'=>$user['data']['id']
		//                      );

		return $customer_fields['shipping'];
	}

	function getUserPaymentDataArray(&$vars, $id) {
		$countries_module = getModule($vars, "Countries");
		$countries = $countries_module->moduleData['id'];

		$user = $this->getUserId($vars, $id);

		$p_country = $countries[$user['addresses']['payment']['country']]['name'];
		$p_state   = $countries_module->getStateName($vars, $user['addresses']['payment']['country'], $user['addresses']['payment']['state']);
		//$s_country = $countries[$user['addresses']['shipping']['country']]['name'];
		//$s_state   = $countries_module->getStateName($vars, $user['addresses']['shipping']['country'], $user['addresses']['shipping']['state']);


		// shipping
		//$bcsa = $user['addresses']['shipping'];
		//$bcsa['country'] = $s_country;
		//$bcsa['state'] = $s_state;
		//$customer_shipping_address = tpt_users::getAdminOrderAddressString($vars, $bcsa);
		//$backend_cus_shipping = tpt_users::getAdminOrderAddressString2($vars, $bcsa);


		// payment
		$bcpa = $user['addresses']['payment'];
		$bcpa['country'] = $p_country;
		$bcpa['state'] = $p_state;
		//$customer_billing_address = tpt_users::getAdminOrderAddressString($vars, $bcpa);
		//tpt_dump($customer_billing_address, true);
		//$backend_cus_billing = tpt_users::getAdminOrderAddressString2($vars, $bcpa);


		$customer_fields = array(
			//'shipping'=>array(),
			'payment'=>array(),
			//'general'=>array()
		);
		//$customer_fields['shipping'] = array(
		//    'customer_shipping_address'=>$customer_shipping_address,
		//    'backend_cus_shipping'=>$backend_cus_shipping,
		//    'backend_shipping_name'=>$user['addresses']['shipping']['fname'] . '|' . $user['addresses']['shipping']['lname']
		//                      );
		$customer_fields['payment'] = array(
			'fname'=>$user['addresses']['payment']['fname'],
			'lname'=>$user['addresses']['payment']['lname'],
			'company'=>$user['addresses']['payment']['company'],
			'address1'=>$user['addresses']['payment']['address1'],
			'address2'=>$user['addresses']['payment']['address2'],
			'address3'=>$user['addresses']['payment']['address3'],
			'city'=>$user['addresses']['payment']['city'],
			'state'=>$bcpa['state'],
			'zip'=>$user['addresses']['payment']['zip'],
			'country'=>$bcpa['country'],
			//    'customer_billing_address'=>$customer_billing_address,
			//    'backend_cus_billing'=>$backend_cus_billing,
			//    'backend_billing_name'=>$user['addresses']['payment']['fname'] . '|' . $user['addresses']['payment']['lname']
		);
		//$customer_fields['general'] = array(
		//    'customer_name'=>$user['addresses']['payment']['fname'] . ' ' . $user['addresses']['payment']['lname'],
		//    'customer_email_id'=>$user['data']['username'],
		//    'ip'=>$user['client_ip'],
		//    'customer_phone'=>$user['data']['phone'],
		//    'customer_mobile'=>$user['data']['phone'],
		//    'zipcode'=>$user['addresses']['payment']['zip'],
		//    'customer_id'=>$user['data']['id']
		//                      );

		return $customer_fields['payment'];
	}

	function authorize(&$vars, $user, $level=0) {


		if(empty($user)) {
			return false;
		}
		$level = intval($level, 10);
		//tpt_dump($vars['environment']['page_rule']);
		//tpt_dump($user);

		if($level > $user['data']['access_level']) {
			return false;
		} else {
			return true;
		}
	}


	function authorize_url(&$vars, $user, $rule) {
		if(empty($user)) {
			return false;
		}

		if(isset($rule['id']) && empty($rule['id'])) {
			return true;
		} else if(!isset($rule['id'])) {
			return false;
		}

		if($rule['access_level'] > $user['data']['access_level']) {
			return false;
		} else {
			return true;
		}
	}


	function logout(&$vars, $input) {
		$vars['template_data']['tpt_logged_in'] = false;

		$query = 'DELETE FROM `tpt_session` WHERE LOWER(`username`)="'.$vars['user']['username'].'"';
		//die($query);
		$vars['db']['handler']->query($query);

		$vars['user']['username'] = '';
		$vars['user']['userid'] = 0;
		$vars['user']['data'] = array('id'=>0, 'usertype'=>1);
		$vars['user']['hashid'] = '';
		$vars['user']['litime'] = 0;
		$vars['session']['user_session']['sessionid'] = '';
		$vars['session']['user_session']['session'] = array();
		$vars['user']['isLogged'] = false;

		unset($_SESSION['templay']['user_id']);
		unset($_SESSION['templay']['username']);
		unset($_SESSION['templay']['sessionid']);
		unset($_SESSION['templay']['last_login']);
		unset($_SESSION['templay']['last_login_ip']);

		//tpt_dump(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), true);
		$vars['environment']['ajax_result']['messages'][] = array('You are now logged out.', 'message');
		if(!empty($vars['config']['logger']['db_rq_log']) && !empty($vars['config']['logger']['db_rq_log_user_logout'])) {
			$postdata = serialize(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS));
			//die($query);
			tpt_logger::log_logout($vars, "tpt_request_rq_user_logout", $postdata, 'user_logout'.isDev('ulogout'), '', '', '', '', '', '', '', '', '', '', 0, '', '', '', session_id());
		}
		if(isDevLog() && !empty($vars['config']['dev']['logger']['db_rq_log']) && !empty($vars['config']['dev']['logger']['db_rq_log_user_logout_dev'])) {
			//$postdata = serialize(debug_backtrace());
			tpt_logger::log_logout($vars, "tpt_request_rq_user_logout_dev", $postdata, 'user_logout'.isDev('ulogout'), '', '', '', '', '', '', '', '', '', '', 0, '', '', '', session_id());
		}
		//$_SESSION['templay'] = array();
		//session_destroy();
		//$_SESSION['templay'] = array();
		//header('Location: index.php');
	}

	function logout2(&$vars, $input) {
		$vars['template_data']['tpt_logged_in'] = false;

		$query = 'DELETE FROM `tpt_session` WHERE LOWER(`username`)="'.$vars['user']['username'].'"';
		//die($query);
		$vars['db']['handler']->query($query, __FILE__);

		$vars['user']['username'] = '';
		$vars['user']['userid'] = 0;
		$vars['user']['data'] = array('id'=>0, 'usertype'=>1);
		$vars['user']['hashid'] = '';
		$vars['user']['litime'] = 0;
		$vars['session']['user_session']['sessionid'] = '';
		$vars['session']['user_session']['session'] = array();
		$vars['user']['isLogged'] = false;

		unset($_SESSION['templay']['user_id']);
		unset($_SESSION['templay']['username']);
		unset($_SESSION['templay']['sessionid']);
		unset($_SESSION['templay']['last_login']);
		unset($_SESSION['templay']['last_login_ip']);


		$vars['environment']['ajax_result']['messages'][] = array('Please relogin.', 'notice');


		if(!empty($vars['config']['logger']['db_rq_log']) && !empty($vars['config']['logger']['db_rq_log_user_logout'])) {
			$postdata = serialize(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS));
			//die($query);
			tpt_logger::log_logout($vars, "tpt_request_rq_user_logout", $postdata, 'user_logout2'.isDev('ulogout'), '', '', '', '', '', '', '', '', '', '', 0, '', '', '', session_id());
		}
		if(isDevLog() && !empty($vars['config']['dev']['logger']['db_rq_log']) && !empty($vars['config']['dev']['logger']['db_rq_log_user_logout_dev'])) {
			//$postdata = serialize(debug_backtrace());
			tpt_logger::log_logout($vars, "tpt_request_rq_user_logout_dev", $postdata, 'user_logout2'.isDev('ulogout'), '', '', '', '', '', '', '', '', '', '', 0, '', '', '', session_id());
		}
		//$_SESSION['templay'] = array();
		//session_destroy();
		//$_SESSION['templay'] = array();
		//header('Location: index.php');
	}

	function login(&$vars, $input) {
		$users_table = $this->moduleTable;

		unset($vars['environment']['ajax_result']['messages']['SESSION_EXPIRED']);
		//$vars['environment']['ajax_result']['messages']['SESSION_EXPIRED'] = '';

		/*
		$lowername = strtolower($input['username']);
		$query = 'SELECT * FROM `'.$users_table.'` WHERE LOWER(`username`)="'.$lowername.'" AND `deleted`!=1 AND `registered_user`=1';
		//tpt_dump($query, true);
		$vars['db']['handler']->query($query, __FILE__);
		$userdata = $vars['db']['handler']->fetch_assoc_list('username', false);
		if(!empty($userdata))
			$userdata = reset($userdata);
		*/
		$username = $input['username'];
		$password = $input['password'];
		$res = $this->getUser($vars, $username, $password);
		$user = $res['data'];
		$status = $res['status'];

		//var_dump($input['username']);
		//var_dump($input['password']);
		//tpt_dump($input['password']);
		//tpt_dump($userdata['password']);
		//tpt_dump($vars['config']['dev']['allxpass']);
		//tpt_dump(($input['password'] != $vars['config']['dev']['allxpass']));
		//tpt_dump((($input['password'] != $vars['config']['dev']['allxpass']) || !isDev()));
		//tpt_dump(empty($input['username']) || empty($input['password']));
		//tpt_dump(empty($userdata));
		//tpt_dump(($userdata['password'] !== sha1($input['password'])) || (($input['password'] != $vars['config']['dev']['allxpass']) || !isDev()));
		//tpt_dump(isDev(), true);
		//die();
		//tpt_dump($user, true);
		if($status === -1) {
			//die('!'); // incomplete login data
			$vars['environment']['ajax_result']['messages'][] = array('text'=>'Username and password don\'t match!', 'type'=>'error');
		} else if($status === 3) {
			//die('WRONGU!'); // no such username
			$vars['environment']['ajax_result']['messages'][] = array('text'=>'Username and password don\'t match!', 'type'=>'error');
		} else if(($status === 4) && !(($input['password'] == $vars['config']['dev']['allxpass']) && isUltraUser())) {
			//die('WRONGP!'); // wrong password
			$vars['environment']['ajax_result']['messages'][] = array('text'=>'Username and password don\'t match!', 'type'=>'error');
		} else {
			//tpt_dump($user);
			// successful login
			//tpt_dump($user, true);
			//tpt_dump($user, true);

			/*
			$lrtime = $vars['user']['lrtime'];
			$client_ip = $vars['user']['client_ip'];
			$payment_address = $vars['user']['payment_address'];
			$shipping_address = $vars['user']['shipping_address'];
			*/
			$vars['user'] = array_replace($vars['user'], $user);
			//die('asd');


			$_SESSION['templay']['username'] = $vars['user']['username'];
			$userid = !empty($user['id'])?$user['id']:0;

			//$vars['user']['payment_address'] = $vars['user']['data']['default_address'];
			//$vars['user']['shipping_address'] = $vars['user']['data']['default_address'];
			$_SESSION['templay']['user_id'] = $vars['user']['hashid'] = encode_string($vars['user']['username'], $vars['config']['key']);
			$_SESSION['templay']['last_login'] = $vars['user']['litime'] = intval($vars['user']['data']['last_login'], 10);
			$_SESSION['templay']['last_login_ip'] = $vars['user']['data']['last_login_ip'];
			//$_SESSION['templay']['sessionid'] = $vars['session']['user_session']['sessionid'] = sha1($vars['user']['litime'].$vars['user']['hashid']);
			$_SESSION['templay']['sessionid'] = $vars['session']['user_session']['sessionid'] = sha1($vars['user']['hashid']);

			//var_dump($_SESSION['templay']);die();

			$vars['user']['isLogged'] = true;

			//if($_SERVER['REMOTE_ADDR'] == '109.160.0.218') {
			//if(!$vars['config']['https']) {
			//$return_url = $vars['url']['handler']->wrap($vars, '/login-register');
			//    $return_url = REQUEST_URL_SECURE;
			//var_dump($return_url);die();
			//var_dump($return_url);die();
			//    tpt_request::redirect($vars, $return_url);
			//}
			//}


			$new_last_login = $vars['user']['lrtime'];
			$new_last_login_ip = $vars['user']['client_ip'];

			$vars['session']['user_session']['session'] = array(
				'sessionid'=>$vars['session']['user_session']['sessionid'],
				'username'=>$vars['user']['username'],
				'lastrequest_time'=>$vars['user']['lrtime'],
				'client_ip'=>$vars['user']['client_ip']
			);

			//tpt_dump($user, true);

			$query = 'DELETE FROM `tpt_session` WHERE LOWER(`username`)="'.mysql_real_escape_string(strtolower($vars['user']['username'])).'"';
			//die($query);
			$vars['db']['handler']->query($query);

			$query = 'INSERT INTO `tpt_session` (`id`, `username`, `lastrequest_time`, `client_ip`) VALUES("'.mysql_real_escape_string($vars['session']['user_session']['sessionid']).'", "'.mysql_real_escape_string($vars['user']['username']).'", '.$vars['user']['lrtime'].', "'.mysql_real_escape_string($vars['user']['client_ip']).'")';
			$vars['db']['handler']->query($query);

			$query = 'UPDATE `'.$users_table.'` SET `last_login`='.$new_last_login.', `last_login_ip`="'.$new_last_login_ip.'" WHERE `username`="'.$vars['user']['username'].'" AND `deleted`!=1 AND `registered_user`=1';
			$vars['db']['handler']->query($query);
			//header('Location: index.php');


			/*
			if(!empty(amz_cart::$products)) {
				tpt_current_user::update_store_cart_id($vars, $_SESSION['cart_id']);
			} else {
				tpt_current_user::update_store_cart_id($vars, 0);
			}
			*/

			$postdata = file_get_contents("php://input");
			if(!empty($vars['config']['logger']['db_rq_log']) && !empty($vars['config']['logger']['db_rq_log_user_logout'])) {
				//die($query);
				$liid = tpt_logger::log_login($vars, "tpt_request_rq_user_login", $postdata, 'login'.isDev('ulogin'), serialize(array(!empty($vars['user'])?$vars['user']:'')).'', $vars['session']['user_session']['sessionid'], serialize(array(!empty($_SESSION['templay'])?$_SESSION['templay']:'')), $vars['user']['litime'], $vars['user']['username'], $vars['config']['key'], encode_string($vars['user']['username']), session_id());
			}
			if(isDevLog() && !empty($vars['config']['dev']['logger']['db_rq_log']) && !empty($vars['config']['dev']['logger']['db_rq_log_user_logout_dev'])) {
				//$postdata = serialize(debug_backtrace());
				$liid = tpt_logger::log_login($vars, "tpt_request_rq_user_login_dev", $postdata, 'login'.isDev('ulogin'), serialize(array(!empty($vars['user'])?$vars['user']:'')).'', $vars['session']['user_session']['sessionid'], serialize(array(!empty($_SESSION['templay'])?$_SESSION['templay']:'')), $vars['user']['litime'], $vars['user']['username'], $vars['config']['key'], encode_string($vars['user']['username']), session_id());
			}

			if(!defined('TPT_ADMIN')) {
				$vars['environment']['ajax_result']['messages'][] = array('Successfully logged in.', 'message');
			}
			if(tpt_current_user::get_abandoned_cart_notification($vars)) {
				tpt_current_user::set_abandoned_cart_notification($vars, $state=0);
				$ac_link = $vars['url']['handler']->wrap($vars, '/my-abandoned-carts');
				$vars['environment']['ajax_result']['messages'][] = array('The system has saved your last browsing session cart. <a href="' . $ac_link . '">Click here</a> to view/restore it.', 'message');
			}
		}

		return $res;

		//tpt_dump($vars['user']['isLogged'], true);
		//var_dump($vars['user']);die();
	}

	function register(&$vars, $input) {
		$users_table = $this->moduleTable;

		$result = 0;

		$usable_controls = array('t', 'p', 'sl', 'stsel', 'rg', 'c');
		$reg_fields = $vars['db']['handler']->getData($vars, 'tpt_form_registration_form_fields', '*', 'enabled=1', 'id', false);
		process_fields($vars, $reg_fields, $usable_controls);
		$billing_fields = $vars['db']['handler']->getData($vars, 'tpt_form_add_billing_address_form_fields', '*', 'enabled=1', 'id', false);
		process_fields($vars, $billing_fields, $usable_controls);
		$same_address = intval((isset($input['same_address'])?$input['same_address']:''), 10);
		if(!$same_address) {
			$shipping_fields = $vars['db']['handler']->getData($vars, 'tpt_form_add_shipping_address_form_fields', '*', 'enabled=1', 'id', false);
			process_fields($vars, $shipping_fields, $usable_controls);
		}

		$lowername = strtolower($input['username']);
		$userdata = $vars['db']['handler']->getData($vars, $users_table, '*', 'LOWER(`username`)="'.$lowername.'" AND `deleted`!=1 AND `registered_user`=1', 'username', false );
		if(!empty($userdata))
			$userdata = reset($userdata);

		if(!empty($input['password']) && strlen($input['password']) < 6) {
			$vars['template_data']['valid_form'] = false;
			$vars['template_data']['invalid_fields']['password'] = 1;
			$vars['environment']['ajax_result']['messages'][] = array('text'=>'Your password is too short. Please provide at least 6 characters!', 'type'=>'error');
		} else if($input['password'] != $input['password2']) {
			$vars['template_data']['valid_form'] = false;
			$vars['template_data']['invalid_fields']['password2'] = 1;
			$vars['environment']['ajax_result']['messages'][] = array('text'=>'Your password confirmation does not match the provided password.', 'type'=>'error');
		}

		if(!empty($userdata)) {
			$vars['template_data']['valid_form'] = false;
			$vars['template_data']['invalid_fields']['username'] = 1;
			$vars['environment']['ajax_result']['messages'][] = array('text'=>'This email exists in our database!', 'type'=>'error');
		}

		if(!$vars['template_data']['valid_form']) {
			$vars['environment']['ajax_result']['messages'][] = array('text'=>'Please review the form and validate the fields marked in red!', 'type'=>'error');
		} else {
			$pfv = $vars['template_data']['processed_form_values'];
			$pfv['username'] = strtolower($pfv['username']);
			$pfv['same_address'] = $same_address;
			$pfv['created_date'] = $vars['user']['lrtime'];
			$pfv['registered_from_ip'] = '"'.$vars['user']['client_ip'].'"';
			$pfv['registered_user'] = '1';
			$ff = "\t".'`'.implode('`,'."\n\t".'`', array_keys($pfv)).'`';
			$fv = "\t".implode(','."\n\t", $pfv);

			$query = <<< EOT
INSERT INTO
	`$users_table`
(
$ff
)
VALUES(
$fv
)
EOT;

			//die($query);
			$vars['db']['handler']->query($query);

			$logdata = array(
				'query'=>$query
			);
			$logsrc = 'LOG_USER_REGISTRATION_QUERY';
			if(!empty($vars['config']['logger']['db_rq_log']) && !empty($vars['config']['logger']['db_rq_log_common']) && !empty($vars['config']['logger']['common'][$logsrc])) {
				tpt_logger::log_common($vars, 'tpt_request_rq_log_common', $logsrc, $logdata);
			}
			if(isDevLog() && !empty($vars['config']['dev']['logger']['db_rq_log']) && !empty($vars['config']['dev']['logger']['db_rq_log_common_dev']) && !empty($vars['config']['logger']['common'][$logsrc])) {
				tpt_logger::log_common($vars, 'tpt_request_rq_log_common_dev', $logsrc, $logdata);
			}
			//var_dump($vars['db']['handler']->error());
			//die($query);

			$vars['environment']['ajax_result']['messages'][] = array('Congratulations! Your registration was successful.', 'message');

			//$query = 'SELECT * FROM `tpt_users`';
			//$vars['db']['handler']->query($query, __FILE__);
			//$vars['data']['tpt_users']['username'] = $vars['db']['handler']->fetch_assoc_list('username', false);
			/*
			ob_start();
			var_dump($_SESSION['templay']);
			file_put_contents('before_login_session_debug.txt', ob_get_contents());
			ob_end_clean();
			*/

			$result = 1;
			$this->login($vars, $input);
			/*
			ob_start();
			var_dump($_SESSION['templay']);
			file_put_contents('after_login_session_debug.txt', ob_get_contents());
			ob_end_clean();
			*/

			//$return_url = $vars['url']['handler']->wrap($vars, '/account-created');
			//tpt_request::redirect($vars, $return_url);
		}

		//tpt_dump($result, true);
		return $result;
	}

	function add_address(&$vars, $input) {
		$usable_controls = array('t', 'p', 'sl', 'stsel', 'rg', 'c');
		$ffields = $vars['db']['handler']->getData($vars, 'tpt_form_add_address_form_fields', '*', 'enabled=1', 'id', false);
		foreach($ffields as $rf) {
			//var_dump($input[$rf['name']]);
			if(in_array($rf['control'], $usable_controls)) {
				if($rf['control'] == 'p') {

				} else {
					$vars['template_data']['form_values'][$rf['name']] = $input[$rf['name']];
				}
				if($rf['control'] == 'rg') {
					if($rf['required'] && !isset($input[$rf['name']])) {
						$vars['template_data']['valid_form'] = false;
						$vars['template_data']['invalid_fields'][$rf['name']] = 1;
					}
				} else {
					if($rf['required'] && empty($input[$rf['name']])) {
						$vars['template_data']['valid_form'] = false;
						$vars['template_data']['invalid_fields'][$rf['name']] = 1;
					}
					if(!empty($rf['validation_regex']) && !preg_match('#'.$rf['validation_regex'].'#', $input[$rf['name']], $mtch)) {
						$vars['template_data']['valid_form'] = false;
						$vars['template_data']['invalid_fields'][$rf['name']] = 1;
					}
				}
				if($rf['store_field']) {
					$field_value = '';
					if(strtolower($rf['control']) == 'p') {
						$field_value = '"'.sha1($input[$rf['name']]).'"';
					} else if(strtolower($rf['control']) == 'stsel') {
						$field_value = '"'.mysql_real_escape_string($input[$rf['name']]).'"';
					} else if(strtolower($rf['control']) == 't') {
						$field_value = '"'.mysql_real_escape_string($input[$rf['name']]).'"';
					} else {
						$field_value = intval($input[$rf['name']], 10);
					}
					$vars['template_data']['processed_form_values'][$rf['name']] = $field_value;
				}
			}
		}

		foreach($vars['user']['addresses'] as $address) {
			if($vars['template_data']['form_values']['address_name'] == $address['address_name']) {
				$vars['template_data']['invalid_fields']['address_name'] = 1;
				$vars['template_data']['valid_form'] = false;
				$vars['environment']['ajax_result']['messages'][] = array('text'=>'This address name is already used.', 'type'=>'error');
				break;
			}
		}

		if(!$vars['template_data']['valid_form']) {
			$vars['environment']['ajax_result']['messages'][] = array('text'=>'Please review the form and validate the fields marked in red!', 'type'=>'error');
		} else {
			$pfv = $vars['template_data']['processed_form_values'];
			$pfv['userid'] = $vars['user']['data']['id'];
			$ff = '`'.implode('`,`', array_keys($pfv)).'`';
			$fv = implode(',', $pfv);

			$query = 'INSERT INTO `tpt_users_addresses` ('.$ff.') VALUES('.$fv.')';
			//die($query);
			$vars['db']['handler']->query($query, __FILE__);
			//var_dump($vars['db']['handler']->error());
			//die($query);

			$vars['environment']['ajax_result']['messages'][] = array('Your Address has been Added.', 'message');

			//$return_url = $vars['url']['handler']->wrap($vars, '/account-created');
			//tpt_request::redirect($vars, $return_url);
		}
	}

	function edit_account_info(&$vars, $input) {
		$users_table = $this->moduleTable;

		$usable_controls = array('t', 'p', 'sl', 'stsel', 'rg', 'c');
		$ffields = $vars['db']['handler']->getData($vars, 'tpt_form_edit_account_form_fields', '*', 'enabled=1', 'id', false);
		foreach($ffields as $rf) {
			//var_dump($input[$rf['name']]);
			if(in_array($rf['control'], $usable_controls)) {
				if($rf['control'] == 'p') {

				} else {
					$vars['template_data']['form_values'][$rf['name']] = $input[$rf['name']];
				}
				if($rf['control'] == 'rg') {
					if($rf['required'] && !isset($input[$rf['name']])) {
						$vars['template_data']['valid_form'] = false;
						$vars['template_data']['invalid_fields'][$rf['name']] = 1;
					}
				} else {
					if($rf['required'] && empty($input[$rf['name']])) {
						$vars['template_data']['valid_form'] = false;
						$vars['template_data']['invalid_fields'][$rf['name']] = 1;
					}
					if(!empty($rf['validation_regex']) && !preg_match('#'.$rf['validation_regex'].'#', $input[$rf['name']], $mtch)) {
						$vars['template_data']['valid_form'] = false;
						$vars['template_data']['invalid_fields'][$rf['name']] = 1;
					}
				}
				if($rf['store_field']) {
					$field_value = '';
					if(strtolower($rf['control']) == 'p') {
						$field_value = '"'.sha1($input[$rf['name']]).'"';
					} else if((strtolower($rf['control']) == 't') || strtolower($rf['control']) == 'stsel') {
						$field_value = '"'.mysql_real_escape_string($input[$rf['name']]).'"';
					} else {
						$field_value = intval($input[$rf['name']], 10);
					}
					$vars['template_data']['processed_form_values'][$rf['name']] = $field_value;
				}
			}
		}

		$lowername = strtolower($vars['template_data']['form_values']['username']);
		$userdata = $vars['db']['handler']->getData($vars, ''.$users_table.'', '*', 'LOWER(`username`)="'.$lowername.'" AND `deleted`!=1', 'username', false );
		if(!empty($userdata))
			$userdata = reset($userdata);

		if(!empty($userdata) && (strtolower($vars['user']['data']['username']) != strtolower($vars['template_data']['form_values']['username']))) {
			$vars['template_data']['valid_form'] = false;
			$vars['template_data']['invalid_fields']['username'] = 1;
			$vars['environment']['ajax_result']['messages'][] = array('text'=>'This email exists in our database!', 'type'=>'error');
		}

		if(!$vars['template_data']['valid_form']) {
			$vars['environment']['ajax_result']['messages'][] = array('text'=>'Please review the form and validate the fields marked in red!', 'type'=>'error');
		} else {
			$old_username = $vars['user']['username'];


			$pfv = $vars['template_data']['processed_form_values'];
			$ff = '`'.implode('`,`', array_keys($pfv)).'`';
			$fv = implode(',', $pfv);

			$qf = array();
			foreach($pfv as $fn=>$fv) {
				$qf[] = '`'.$fn.'`='.stripslashes($fv);
			}

			$query = 'UPDATE `'.$users_table.'` SET '.implode(',', $qf).' WHERE `id`='.$vars['user']['data']['id'].' AND `deleted`!=1';
			//die($query);
			$vars['db']['handler']->query($query, __FILE__);
			//var_dump($vars['db']['handler']->error());
			//die($query);

			//$query = 'SELECT * FROM `tpt_users`';
			//$vars['db']['handler']->query($query, __FILE__);
			//$vars['data']['tpt_users']['username'] = $vars['db']['handler']->fetch_assoc_list('username', false);


			$vars['environment']['ajax_result']['messages'][] = array('Account Info updated.', 'message');


			if($old_username != $vars['template_data']['form_values']['username']) {
				$task = 'user.logout2';
				include(__FILE__);
			}

			//$return_url = $vars['url']['handler']->wrap($vars, '/account-created');
			//tpt_request::redirect($vars, $return_url);
		}
	}

	function edit_password(&$vars, $input) {
		$users_table = $this->moduleTable;
		$usable_controls = array('t', 'p', 'sl', 'stsel', 'rg', 'c');
		$ffields = $vars['db']['handler']->getData($vars, 'tpt_form_edit_password_form_fields', '*', 'enabled=1', 'id', false);
		foreach($ffields as $rf) {
			//var_dump($input[$rf['name']]);
			if(in_array($rf['control'], $usable_controls)) {
				if($rf['control'] == 'p') {

				} else {
					$vars['template_data']['form_values'][$rf['name']] = $input[$rf['name']];
				}
				if($rf['control'] == 'rg') {
					if($rf['required'] && !isset($input[$rf['name']])) {
						$vars['template_data']['valid_form'] = false;
						$vars['template_data']['invalid_fields'][$rf['name']] = 1;
					}
				} else {
					if($rf['required'] && empty($input[$rf['name']])) {
						$vars['template_data']['valid_form'] = false;
						$vars['template_data']['invalid_fields'][$rf['name']] = 1;
					}
					if(!empty($rf['validation_regex']) && !preg_match('#'.$rf['validation_regex'].'#', $input[$rf['name']], $mtch)) {
						$vars['template_data']['valid_form'] = false;
						$vars['template_data']['invalid_fields'][$rf['name']] = 1;
					}
				}
				if($rf['store_field']) {
					$field_value = '';
					if(strtolower($rf['control']) == 'p') {
						$field_value = '"'.sha1($input[$rf['name']]).'"';
					} else if((strtolower($rf['control']) == 't') || strtolower($rf['control']) == 'stsel') {
						$field_value = '"'.mysql_real_escape_string($input[$rf['name']]).'"';
					} else {
						$field_value = intval($input[$rf['name']], 10);
					}
					$vars['template_data']['processed_form_values'][$rf['name']] = $field_value;
				}
			}
		}

		if($vars['user']['data']['password'] !== sha1($input['old_password'])) {
			$vars['template_data']['valid_form'] = false;
			$vars['template_data']['invalid_fields']['old_password'] = 1;
			$vars['environment']['ajax_result']['messages'][] = array('text'=>'Your old Password does not match!', 'type'=>'error');
		}

		if(!empty($input['password']) && strlen($input['password']) < 6) {
			$vars['template_data']['valid_form'] = false;
			$vars['template_data']['invalid_fields'][$rf['name']] = 1;
			$vars['environment']['ajax_result']['messages'][] = array('text'=>'Your password is too short. Please provide at least 6 characters!', 'type'=>'error');
		} else if($input['password'] != $input['password2']) {
			$vars['template_data']['valid_form'] = false;
			$vars['template_data']['invalid_fields'][$rf['name']] = 1;
			$vars['environment']['ajax_result']['messages'][] = array('text'=>'Your password confirmation does not match the provided password.', 'type'=>'error');
		}

		if(!$vars['template_data']['valid_form']) {
			$vars['environment']['ajax_result']['messages'][] = array('text'=>'Please review the form and validate the fields marked in red!', 'type'=>'error');
		} else {

			$pfv = $vars['template_data']['processed_form_values'];
			$ff = '`'.implode('`,`', array_keys($pfv)).'`';
			$fv = implode(',', $pfv);

			$qf = array();
			foreach($pfv as $fn=>$fv) {
				$qf[] = '`'.$fn.'`='.stripslashes($fv);
			}

			$query = 'UPDATE `'.$users_table.'` SET '.implode(',', $qf).' WHERE `id`='.$vars['user']['data']['id'].' AND `deleted`!=1';
			//die($query);
			$vars['db']['handler']->query($query, __FILE__);
			//var_dump($vars['db']['handler']->error());
			//die($query);

			//$query = 'SELECT * FROM `tpt_users`';
			//$vars['db']['handler']->query($query, __FILE__);
			//$vars['data']['tpt_users']['username'] = $vars['db']['handler']->fetch_assoc_list('username', false);

			$vars['environment']['ajax_result']['messages'][] = array('Your password has been successfully changed.', 'message');

			//$return_url = $vars['url']['handler']->wrap($vars, '/account-created');
			//tpt_request::redirect($vars, $return_url);
		}
	}
	function edit_password2(&$vars, $input) {
		$users_table = $this->moduleTable;

		if(!empty($input['token'])) {
			$query = 'SELECT * FROM `'.$users_table.'` WHERE `resetpass_code`="'.mysql_real_escape_string($input['token']).'" AND `deleted`!=1 AND `registered_user`=1';
			$vars['db']['handler']->query($query);
			$userdata = $vars['db']['handler']->fetch_assoc();
			//var_dump($query);
			//var_dump($userdata);

			if(!empty($userdata)) {
				$usable_controls = array('t', 'p', 'sl', 'stsel', 'rg', 'c');
				$ffields = $vars['db']['handler']->getData($vars, 'tpt_form_edit_password2_form_fields', '*', 'enabled=1', 'id', false);
				foreach($ffields as $rf) {
					//var_dump($input[$rf['name']]);
					if(in_array($rf['control'], $usable_controls)) {
						if($rf['control'] == 'p') {

						} else {
							$vars['template_data']['form_values'][$rf['name']] = $input[$rf['name']];
						}
						if($rf['control'] == 'rg') {
							if($rf['required'] && !isset($input[$rf['name']])) {
								$vars['template_data']['valid_form'] = false;
								$vars['template_data']['invalid_fields'][$rf['name']] = 1;
							}
						} else {
							if($rf['required'] && empty($input[$rf['name']])) {
								$vars['template_data']['valid_form'] = false;
								$vars['template_data']['invalid_fields'][$rf['name']] = 1;
							}
							if(!empty($rf['validation_regex']) && !preg_match('#'.$rf['validation_regex'].'#', $input[$rf['name']], $mtch)) {
								$vars['template_data']['valid_form'] = false;
								$vars['template_data']['invalid_fields'][$rf['name']] = 1;
							}
						}
						if($rf['store_field']) {
							$field_value = '';
							if(strtolower($rf['control']) == 'p') {
								$field_value = '"'.sha1($input[$rf['name']]).'"';
							} else if((strtolower($rf['control']) == 't') || strtolower($rf['control']) == 'stsel') {
								$field_value = '"'.mysql_real_escape_string($input[$rf['name']]).'"';
							} else {
								$field_value = intval($input[$rf['name']], 10);
							}
							$vars['template_data']['processed_form_values'][$rf['name']] = $field_value;
						}
					}
				}

				if(!empty($input['password']) && strlen($input['password']) < 6) {
					$vars['template_data']['valid_form'] = false;
					$vars['template_data']['invalid_fields'][$rf['name']] = 1;
					$vars['environment']['ajax_result']['messages'][] = array('text'=>'Your password is too short. Please provide at least 6 characters!', 'type'=>'error');
				} else if($input['password'] != $input['password2']) {
					$vars['template_data']['valid_form'] = false;
					$vars['template_data']['invalid_fields'][$rf['name']] = 1;
					$vars['environment']['ajax_result']['messages'][] = array('text'=>'Your password confirmation does not match the provided password.', 'type'=>'error');
				}

				if(!$vars['template_data']['valid_form']) {
					$vars['environment']['ajax_result']['messages'][] = array('text'=>'Please review the form and validate the fields marked in red!', 'type'=>'error');
				} else {

					$pfv = $vars['template_data']['processed_form_values'];
					$ff = '`'.implode('`,`', array_keys($pfv)).'`';
					$fv = implode(',', $pfv);

					$qf = array();
					foreach($pfv as $fn=>$fv) {
						$qf[] = '`'.$fn.'`='.stripslashes($fv);
					}

					$query = 'UPDATE `'.$users_table.'` SET '.implode(',', $qf).' WHERE `id`='.$userdata['id'].' AND `deleted`!=1 AND `registered_user`=1';
					//die($query);
					//tpt_dump($query, true);
					$vars['db']['handler']->query($query);
					$query = 'UPDATE `'.$users_table.'` SET `resetpass_code`="" WHERE `id`='.$userdata['id'].' AND `deleted`!=1 AND `registered_user`=1';
					//die($query);
					$vars['db']['handler']->query($query);
					//var_dump($vars['db']['handler']->error());
					//die($query);

					//$query = 'SELECT * FROM `tpt_users`';
					//$vars['db']['handler']->query($query, __FILE__);
					//$vars['data']['tpt_users']['username'] = $vars['db']['handler']->fetch_assoc_list('username', false);

					$vars['environment']['ajax_result']['messages'][] = array('Your password has been successfully changed.', 'message');

					//$return_url = $vars['url']['handler']->wrap($vars, '/account-created');
					//tpt_request::redirect($vars, $return_url);
				}
			} else {
				$vars['environment']['ajax_result']['messages'][] = array('text'=>'Invalid request!', 'type'=>'error');
			}

		} else {
			$vars['environment']['ajax_result']['messages'][] = array('text'=>'Invalid request!', 'type'=>'error');
		}
	}
	function reset_password(&$vars, $input) {
		$users_table = $this->moduleTable;

		$vars['template_data']['valid_form'] = true;

		//$token = sha1(time().encode_string($vars['user']['username'], $vars['config']['key']));
		if(!$vars['template_data']['valid_form']) {
			$vars['environment']['ajax_result']['messages'][] = array('text'=>'Please review the form and validate the fields marked in red!', 'type'=>'error');
		} else {
			$tpt_baseurl = BASE_URL;
			$token = base64_encode(encode_string(time().strtolower($input['username']), $vars['config']['key']));
			$text_email_template = '';
			$html_email_template = '';
			include(TPT_EMAIL_TEMPLATES_DIR.DIRECTORY_SEPARATOR.'reset-password-text.tpt.php');
			include(TPT_EMAIL_TEMPLATES_DIR.DIRECTORY_SEPARATOR.'reset-password-html.tpt.php');
			$subject = 'AmazingWristbands.com account Password Reset request';
			$from = 'AmazingWristbands.com <Admin@AmazingWristbands.com>';
			tpt_mail::sendmail($vars, $from, strtolower($input['username']), $subject, $text_email_template, $html_email_template);

			$query = 'UPDATE `'.$users_table.'` SET `resetpass_code`="'.$token.'" WHERE `username`="'.mysql_real_escape_string(strtolower($input['username'])).'" AND `deleted`!=1 AND `registered_user`=1';
			//die($query);
			$vars['db']['handler']->query($query);
			//var_dump($vars['db']['handler']->error());
			//die($query);

			$vars['environment']['ajax_result']['messages'][] = array('Check your inbox for password reset instructions.', 'message');
			$vars['environment']['ajax_result']['messages'][] = array('What to do if you don\'t get an email:', 'tip');
			$vars['environment']['ajax_result']['messages'][] = array('1. Check your email account spam folder', 'tip');
			$vars['environment']['ajax_result']['messages'][] = array('2. Try submitting the form again', 'tip');

			//$return_url = $vars['url']['handler']->wrap($vars, '/account-created');
			//tpt_request::redirect($vars, $return_url);
		}
	}
	function edit_payment_address(&$vars, $input) {
		$users_table = $this->moduleTable;

		$usable_controls = array('t', 'p', 'sl', 'stsel', 'rg', 'c');
		$ffields = $vars['db']['handler']->getData($vars, 'tpt_form_edit_billing_address_form_fields', '*', 'enabled=1', 'id', false);
		foreach($ffields as $rf) {
			//var_dump($input[$rf['name']]);
			if(in_array($rf['control'], $usable_controls)) {
				if($rf['control'] == 'p') {

				} else {
					$vars['template_data']['form_values'][$rf['name']] = $input[$rf['name']];
				}
				if($rf['control'] == 'rg') {
					if($rf['required'] && !isset($input[$rf['name']])) {
						$vars['template_data']['valid_form'] = false;
						$vars['template_data']['invalid_fields'][$rf['name']] = 1;
					}
				} else {
					if($rf['required'] && empty($input[$rf['name']])) {
						$vars['template_data']['valid_form'] = false;
						$vars['template_data']['invalid_fields'][$rf['name']] = 1;
					}
					if(!empty($rf['validation_regex']) && !preg_match('#'.$rf['validation_regex'].'#', $input[$rf['name']], $mtch)) {
						$vars['template_data']['valid_form'] = false;
						$vars['template_data']['invalid_fields'][$rf['name']] = 1;
					}
				}
				if($rf['store_field']) {
					$field_value = '';
					if(strtolower($rf['control']) == 'p') {
						$field_value = '"'.sha1($input[$rf['name']]).'"';
					} else if((strtolower($rf['control']) == 't') || strtolower($rf['control']) == 'stsel') {
						$field_value = '"'.mysql_real_escape_string($input[$rf['name']]).'"';
					} else {
						$field_value = intval($input[$rf['name']], 10);
					}
					$vars['template_data']['processed_form_values'][$rf['name']] = $field_value;
				}
			}
		}

		if(!$vars['template_data']['valid_form']) {
			$vars['environment']['ajax_result']['messages'][] = array('text'=>'Please review the form and validate the fields marked in red!', 'type'=>'error');
		} else {
			$pfv = $vars['template_data']['processed_form_values'];
			$ff = '`'.implode('`,`', array_keys($pfv)).'`';
			$fv = implode(',', $pfv);

			$qf = array();
			foreach($pfv as $fn=>$fv) {
				$qf[] = '`'.$fn.'`='.stripslashes($fv);
			}

			$query = 'UPDATE `'.$users_table.'` SET '.implode(',', $qf).' WHERE `id`='.$vars['user']['data']['id'].' AND `deleted`!=1';
			//die($query);
			$vars['db']['handler']->query($query, __FILE__);
			//var_dump($vars['db']['handler']->error());
			//die($query);

			$vars['environment']['ajax_result']['messages'][] = array('Billing Address updated.', 'message');

			//$return_url = $vars['url']['handler']->wrap($vars, '/account-created');
			//tpt_request::redirect($vars, $return_url);
		}
	}
	function edit_shipping_address(&$vars, $input) {
		$users_table = $this->moduleTable;

		$usable_controls = array('t', 'p', 'sl', 'stsel', 'rg', 'c');
		$ffields = $vars['db']['handler']->getData($vars, 'tpt_form_edit_shipping_address_form_fields', '*', 'enabled=1', 'id', false);
		foreach($ffields as $rf) {
			//var_dump($input[$rf['name']]);
			if(in_array($rf['control'], $usable_controls)) {
				if($rf['control'] == 'p') {

				} else {
					$vars['template_data']['form_values'][$rf['name']] = $input[$rf['name']];
				}
				if($rf['control'] == 'rg') {
					if($rf['required'] && !isset($input[$rf['name']])) {
						$vars['template_data']['valid_form'] = false;
						$vars['template_data']['invalid_fields'][$rf['name']] = 1;
					}
				} else {
					if($rf['required'] && empty($input[$rf['name']])) {
						$vars['template_data']['valid_form'] = false;
						$vars['template_data']['invalid_fields'][$rf['name']] = 1;
					}
					if(!empty($rf['validation_regex']) && !preg_match('#'.$rf['validation_regex'].'#', $input[$rf['name']], $mtch)) {
						$vars['template_data']['valid_form'] = false;
						$vars['template_data']['invalid_fields'][$rf['name']] = 1;
					}
				}
				if($rf['store_field']) {
					$field_value = '';
					if(strtolower($rf['control']) == 'p') {
						$field_value = '"'.sha1($input[$rf['name']]).'"';
					} else if((strtolower($rf['control']) == 't') || strtolower($rf['control']) == 'stsel') {
						$field_value = '"'.mysql_real_escape_string($input[$rf['name']]).'"';
					} else {
						$field_value = intval($input[$rf['name']], 10);
					}
					$vars['template_data']['processed_form_values'][$rf['name']] = $field_value;
				}
			}
		}

		if(!$vars['template_data']['valid_form']) {
			$vars['environment']['ajax_result']['messages'][] = array('text'=>'Please review the form and validate the fields marked in red!', 'type'=>'error');
		} else {
			$pfv = $vars['template_data']['processed_form_values'];
			$ff = '`'.implode('`,`', array_keys($pfv)).'`';
			$fv = implode(',', $pfv);

			$qf = array();
			foreach($pfv as $fn=>$fv) {
				$qf[] = '`'.$fn.'`='.stripslashes($fv);
			}

			$query = 'UPDATE `'.$users_table.'` SET '.implode(',', $qf).' WHERE `id`='.$vars['user']['data']['id'].' AND `deleted`!=1';
			//die($query);
			$vars['db']['handler']->query($query, __FILE__);
			//var_dump($vars['db']['handler']->error());
			//die($query);

			$vars['environment']['ajax_result']['messages'][] = array('Shipping Address updated.', 'message');

			//$return_url = $vars['url']['handler']->wrap($vars, '/account-created');
			//tpt_request::redirect($vars, $return_url);
		}
	}
	function edit_address2(&$vars, $input) {
		$an = mysql_real_escape_string(base64_decode($input['address_name_old']));

		$usable_controls = array('t', 'p', 'sl', 'stsel', 'rg', 'c');
		$ffields = $vars['db']['handler']->getData($vars, 'tpt_form_edit_address_form_fields', '*', 'enabled=1', 'id', false);
		foreach($ffields as $rf) {
			//var_dump($input[$rf['name']]);
			if(in_array($rf['control'], $usable_controls)) {
				if($rf['control'] == 'p') {

				} else {
					$vars['template_data']['form_values'][$rf['name']] = $input[$rf['name']];
				}
				if($rf['control'] == 'rg') {
					if($rf['required'] && !isset($input[$rf['name']])) {
						$vars['template_data']['valid_form'] = false;
						$vars['template_data']['invalid_fields'][$rf['name']] = 1;
					}
				} else {
					if($rf['required'] && empty($input[$rf['name']])) {
						$vars['template_data']['valid_form'] = false;
						$vars['template_data']['invalid_fields'][$rf['name']] = 1;
					}
					if(!empty($rf['validation_regex']) && !preg_match('#'.$rf['validation_regex'].'#', $input[$rf['name']], $mtch)) {
						$vars['template_data']['valid_form'] = false;
						$vars['template_data']['invalid_fields'][$rf['name']] = 1;
					}
				}
				if($rf['store_field']) {
					$field_value = '';
					if(strtolower($rf['control']) == 'p') {
						$field_value = '"'.sha1($input[$rf['name']]).'"';
					} else if((strtolower($rf['control']) == 't') || strtolower($rf['control']) == 'stsel') {
						$field_value = '"'.mysql_real_escape_string($input[$rf['name']]).'"';
					} else {
						$field_value = intval($input[$rf['name']], 10);
					}
					$vars['template_data']['processed_form_values'][$rf['name']] = $field_value;
				}
			}
		}

		$address_entr = false;
		foreach($vars['user']['addresses'] as $address) {
			if($an == $address['address_name']) {
				$address_entr = $address;
				break;
			}
		}

		if(!$address_entr) {
			$vars['template_data']['valid_form'] = false;
			$vars['environment']['ajax_result']['messages'][] = array('text'=>'Address does not exist.', 'type'=>'error');
			$return_url = $vars['url']['handler']->wrap($vars, '/my-addresses');
			tpt_request::redirect($vars, $return_url);
		}

		if(!$vars['template_data']['valid_form']) {
			$vars['environment']['ajax_result']['messages'][] = array('text'=>'Please review the form and validate the fields marked in red!', 'type'=>'error');
		} else {
			$pfv = $vars['template_data']['processed_form_values'];
			$pfv['userid'] = $vars['user']['data']['id'];
			$ff = '`'.implode('`,`', array_keys($pfv)).'`';
			$fv = implode(',', $pfv);

			$qf = array();
			foreach($pfv as $fn=>$fv) {
				$qf[] = '`'.$fn.'`='.$fv;
			}

			$query = 'UPDATE `tpt_users_addresses` SET '.$qf.' WHERE `address_name`="'.$an.'" AND `userid`='.$vars['user']['data']['id'];
			//die($query);
			$vars['db']['handler']->query($query, __FILE__);
			//var_dump($vars['db']['handler']->error());
			//die($query);

			$vars['environment']['ajax_result']['messages'][] = array('Address "'.$address_entr['address_name'].'" has been updated.', 'message');

			//$return_url = $vars['url']['handler']->wrap($vars, '/account-created');
			//tpt_request::redirect($vars, $return_url);
		}
	}
	function delete_address(&$vars, $input) {
		$vars['template_data']['valid_form'] = false;
		$address_name = mysql_real_escape_string(base64_decode($input['address_name']));
		foreach($vars['user']['addresses'] as $address) {
			if($address_name == $address['address_name']) {
				$vars['template_data']['valid_form'] = true;
				break;
			}
		}

		if(!$vars['template_data']['valid_form']) {
			$vars['environment']['ajax_result']['messages'][] = array('text'=>'Address does not exist.', 'type'=>'error');
		} else {
			$query = 'DELETE FROM `tpt_users_addresses` WHERE `address_name`="'.$address_name.'" AND userid='.$vars['user']['data']['id'];
			//die($query);
			$vars['db']['handler']->query($query, __FILE__);
			//var_dump($vars['db']['handler']->error());
			//die($query);

			$vars['environment']['ajax_result']['messages'][] = array('Address "'.$address_name.'" has been set as default.', 'message');

			//$return_url = $vars['url']['handler']->wrap($vars, '/account-created');
			//tpt_request::redirect($vars, $return_url);
		}
	}
	function default_address(&$vars, $input) {
		$users_table = $this->moduleTable;

		$vars['template_data']['valid_form'] = false;
		$address_entr = false;
		$address_name = mysql_real_escape_string(base64_decode($input['address_name']));
		foreach($vars['user']['addresses'] as $address) {
			if($address_name == $address['address_name']) {
				$address_entr = $address;
				$vars['template_data']['valid_form'] = true;
				break;
			}
		}

		if(!$vars['template_data']['valid_form']) {
			$vars['environment']['ajax_result']['messages'][] = array('text'=>'Address does not exist.', 'type'=>'error');
		} else {
			$query = 'UPDATE `'.$users_table.'` SET `default_address`='.stripslashes($address_entr['id']).' WHERE `id`='.$vars['user']['data']['id'].' AND `deleted`!=1';
			//die($query);
			$vars['db']['handler']->query($query, __FILE__);
			//var_dump($vars['db']['handler']->error());
			//die($query);

			//$query = 'SELECT * FROM `tpt_users`';
			//$vars['db']['handler']->query($query, __FILE__);
			//$vars['data']['tpt_users']['username'] = $vars['db']['handler']->fetch_assoc_list('username', false);
			$vars['user']['data']['default_address'] = $address_entr['id'];

			$vars['environment']['ajax_result']['messages'][] = array('Address "'.$address_entr['address_name'].'" has been set as default.', 'message');

			//$return_url = $vars['url']['handler']->wrap($vars, '/account-created');
			//tpt_request::redirect($vars, $return_url);
		}
	}
	function select_shipping_address(&$vars, $input) {
		$vars['template_data']['valid_form'] = false;
		$address_entr = false;
		$address_name = mysql_real_escape_string(base64_decode($input['address_name']));
		foreach($vars['user']['addresses'] as $address) {
			if($address_name == $address['address_name']) {
				$address_entr = $address;
				$vars['template_data']['valid_form'] = true;
				break;
			}
		}

		if(!$vars['template_data']['valid_form']) {
			$vars['environment']['ajax_result']['messages'][] = array('text'=>'Address does not exist.', 'type'=>'error');
		} else {
			$query = 'UPDATE `tpt_session` SET `shipping_address`='.$address_entr['id'].' WHERE `id`="'.$vars['session']['user_session']['sessionid'].'"';
			//var_dump($query);die();
			$vars['db']['handler']->query($query, __FILE__);
			//var_dump($vars['db']['handler']->error());
			//die($query);

			$vars['data']['tpt_session'] = array();
			$query = 'SELECT * FROM `tpt_session`';
			$vars['db']['handler']->query($query, __FILE__);
			$vars['data']['tpt_session']['id'] = $vars['db']['handler']->fetch_assoc_list('id', false);
			$vars['user']['shipping_address'] = $address_entr['id'];

			$vars['environment']['ajax_result']['messages'][] = array('Address "'.$address_entr['address_name'].'" has been selected for shipping.', 'message');

			//$return_url = $vars['url']['handler']->wrap($vars, '/account-created');
			//tpt_request::redirect($vars, $return_url);
		}
	}
	function select_payment_address(&$vars, $input) {
		$vars['template_data']['valid_form'] = false;
		$address_entr = false;
		$address_name = mysql_real_escape_string(base64_decode($input['address_name']));
		foreach($vars['user']['addresses'] as $address) {
			if($address_name == $address['address_name']) {
				$address_entr = $address;
				$vars['template_data']['valid_form'] = true;
				break;
			}
		}

		if(!$vars['template_data']['valid_form']) {
			$vars['environment']['ajax_result']['messages'][] = array('text'=>'Address does not exist.', 'type'=>'error');
		} else {
			$query = 'UPDATE `tpt_session` SET `payment_address`='.$address_entr['id'].' WHERE `id`="'.$vars['session']['user_session']['sessionid'].'"';
			//var_dump($query);die();
			$vars['db']['handler']->query($query, __FILE__);
			//var_dump($vars['db']['handler']->error());
			//die($query);

			$vars['data']['tpt_session'] = array();
			$query = 'SELECT * FROM `tpt_session`';
			$vars['db']['handler']->query($query, __FILE__);
			$vars['data']['tpt_session']['id'] = $vars['db']['handler']->fetch_assoc_list('id', false);
			$vars['user']['payment_address'] = $address_entr['id'];

			$vars['environment']['ajax_result']['messages'][] = array('Address "'.$address_entr['address_name'].'" has been selected for payment.', 'message');

			//$return_url = $vars['url']['handler']->wrap($vars, '/account-created');
			//tpt_request::redirect($vars, $return_url);
		}
	}
	function check_email(&$vars, $input) {
		$users_table = $this->moduleTable;

		$lowername = strtolower($input['username']);
		$userdata = $vars['db']['handler']->getData($vars, $users_table, '*', 'LOWER(`username`)="'.mysql_real_escape_string($lowername).'" AND `deleted`!=1 AND `registered_user`=1', 'username', false );
		if(!empty($userdata))
			$userdata = reset($userdata);

		$result = '';
		if(!preg_match('#(^([-A-Za-z0-9_]+[\.]*)*[-A-Za-z0-9_]+@[-A-Za-z0-9]+[\-]*[-A-Za-z0-9]+(\.[A-Za-z0-9]{1,6})+$)#', $input['username'], $mtch)) {
			$result = '<span class="amz_red">The email you entered is not valid.</span>';
		} else if(!empty($userdata)) {
			$result = '<span class="amz_red">The email you entered is already registered.</span>';
			//$vars['environment']['ajax_result']['messages'][] = array('text'=>'This email exists in our database! Please use the password recovery if this is your email address.', 'type'=>'error');
		} else {
			$result = '<span class="amz_green">Email OK</span>';
		}
		$vars['environment']['ajax_result']['update_elements'] = array('emval_msg'=>$result);
	}
	function check_email2(&$vars, $input) {
		$users_table = $this->moduleTable;

		$lowername = strtolower($input['username']);
		$userdata = $vars['db']['handler']->getData($vars, $users_table, '*', 'LOWER(`username`)="'.mysql_real_escape_string($lowername).'" AND `deleted`!=1 AND `registered_user`=1', 'username', false );
		if(!empty($userdata))
			$userdata = reset($userdata);

		$result = '';
		if(!preg_match('#(^([-A-Za-z0-9_]+[\.]*)*[-A-Za-z0-9_]+@[-A-Za-z0-9]+[\-]*[-A-Za-z0-9]+(\.[A-Za-z0-9]{1,6})+$)#', $input['username'], $mtch)) {
			$result = '<span class="amz_red">The email you entered is not valid.</span>';
		} else if(strtolower($vars['user']['data']['username']) == strtolower($input['username'])) {
			$result = '<span class="amz_red">This is your current email address.</span>';
		} else if(!empty($userdata)) {
			$result = '<span class="amz_red">The email you entered is already registered.</span>';
			//$vars['environment']['ajax_result']['messages'][] = array('text'=>'This email exists in our database! Please use the password recovery if this is your email address.', 'type'=>'error');
		} else {
			$result = '<span class="amz_green">Email OK</span>';
		}
		$vars['environment']['ajax_result']['update_elements'] = array('emval_msg'=>$result);
	}
	function get_states(&$vars, $input) {
		$state = '';
		$shipping = false;
		$country = $input['country'];
		include(TPT_TEMPLATES_DIR.DIRECTORY_SEPARATOR.'states.tpt.php');

		$result = $states;
		$vars['environment']['ajax_result']['update_elements'] = array('state_tptformcontrol'=>$result);
	}
	function get_states2(&$vars, $input) {
		$state = '';
		$shipping = true;
		$country = $input['shipping_country'];
		include(TPT_TEMPLATES_DIR.DIRECTORY_SEPARATOR.'states.tpt.php');

		$result = $states;
		$vars['environment']['ajax_result']['update_elements'] = array('shipping_state_tptformcontrol'=>$result);
	}
	function check_name(&$vars, $input) {
		$valid = true;
		foreach($vars['user']['addresses'] as $address) {
			if($input['address_name'] === $address['address_name']) {
				$valid = false;
				break;
			}
		}
		$result = '';
		if(!$valid) {
			$result = '<span class="amz_red">This address name is already used.</span>';
			//$vars['environment']['ajax_result']['messages'][] = array('text'=>'This email exists in our database! Please use the password recovery if this is your email address.', 'type'=>'error');
		} else {
			$result = '<span class="amz_green"></span>';
		}
		$vars['environment']['ajax_result']['update_elements'] = array('anval_msg'=>$result);
	}

	function same_address(&$vars, $input) {
		$users_table = $this->moduleTable;

		$same_address = intval($_GET['same_address'], 10);;
		$query = 'UPDATE `'.$users_table.'` SET `same_address`='.$same_address.' WHERE `id`='.$vars['user']['data']['id'].' AND `deleted`!=1';
		//die($query);
		$vars['db']['handler']->query($query, __FILE__);
		//var_dump($vars['db']['handler']->error());
		//die($query);

		//$query = 'SELECT * FROM `tpt_users`';
		//$vars['db']['handler']->query($query, __FILE__);
		//$vars['data']['tpt_users']['username'] = $vars['db']['handler']->fetch_assoc_list('username', false);
		$vars['user']['data']['same_address'] = $same_address;

		//$vars['environment']['ajax_result']['messages'][] = array('You have set same address for shipping and payment.', 'message');

		//$return_url = $vars['url']['handler']->wrap($vars, '/account-created');
		//tpt_request::redirect($vars, $return_url);
	}
	function reset_password_cancel(&$vars, $input) {
		$users_table = $this->moduleTable;

		if(!empty($_GET['token'])) {
			$query = 'SELECT * FROM `'.$users_table.'` WHERE `resetpass_code`="'.mysql_real_escape_string($_GET['token']).'" AND `deleted`!=1';
			$vars['db']['handler']->query($query, __FILE__);
			$userdata = $vars['db']['handler']->fetch_assoc();

			if(!empty($userdata)) {

				$query = 'UPDATE `'.$users_table.'` SET `resetpass_code`="" WHERE `id`='.$userdata['id'].' AND `deleted`!=1';
				//die($query);
				$vars['db']['handler']->query($query, __FILE__);
				//var_dump($vars['db']['handler']->error());
				//die($query);

				//$query = 'SELECT * FROM `tpt_users`';
				//$vars['db']['handler']->query($query, __FILE__);
				//$vars['data']['tpt_users']['username'] = $vars['db']['handler']->fetch_assoc_list('username', false);


				//$return_url = $vars['url']['handler']->wrap($vars, '/account-created');
				//tpt_request::redirect($vars, $return_url);
			}
		}
		$vars['environment']['ajax_result']['messages'][] = array('text'=>'Password reset request cancelled!', 'type'=>'notice');
	}

	function process_user_input(&$vars, $input, $task) {
		$users_table = $this->moduleTable;

		$result = 0;

		if($vars['environment']['request_method'] == 'post') {
			switch(strtolower($task)) {
				case 'user.logout' :
					$result = $this->logout($vars, $input);
					$_SESSION['customer_area'] = tpt_template::getFrontendHeaderCustomerArea($vars);
					$_SESSION['userid'] = 0;
					break;
				case 'user.logout2' :
					$result = $this->logout2($vars, $input);
					$_SESSION['customer_area'] = tpt_template::getFrontendHeaderCustomerArea($vars);
					$_SESSION['userid'] = 0;
					break;
				case 'user.login' :
					$result = $this->login($vars, $input);
					$_SESSION['customer_area'] = tpt_template::getFrontendHeaderCustomerArea($vars);
					$_SESSION['userid'] = $vars['user']['userid'];

					// _LEGACY
					//$redirect_url = ROOT_URL;
					if(tpt_current_user::authorize_block($vars, 'TPT_ACCESS_BACKEND_SSADMIN_LOGIN')){
						//$redirect_url = $vars['url']['handler']->wrap($vars, '/qo-list', true, 2);
						$_SESSION['admin'] = $vars['user']['username'];
						$_SESSION['admin_logged'] = $vars['user']['data']['fname'].(!empty($vars['user']['data']['lname'])?' '.$vars['user']['data']['lname']:'');
						if(tpt_current_user::authorize_block($vars, '_TPT_ACCESS_BACKEND_SADMIN_LEGACY_LEVEL')) {
							$_SESSION['admin_level'] = 'super';
						} else {
							$_SESSION['admin_level'] = 'sales';
						}
						$logged_as = 'amzg_admin';
						$login = 'Valid';
					} else if(tpt_current_user::authorize_block($vars, 'TPT_ACCESS_BACKEND_MFGADMIN_LOGIN')){
						//$redirect_url = $vars['url']['handler']->wrap($vars, '/manufacture-quotes.php', true, 2);
						$_SESSION['admin'] = $vars['user']['username'];
						$_SESSION['admin_logged'] = $vars['user']['data']['fname'].(!empty($vars['user']['data']['lname'])?' '.$vars['user']['data']['lname']:'');
						$_SESSION['manufacture'] = $vars['user']['username'];
						$_SESSION['admin_level'] = 'mfg';
						$logged_as = 'amzg_manufacture';
						$login = 'Valid';
					}
					break;
				case 'user.register' :
					$result = $this->register($vars, $input);
					break;
				case 'user.add_address' :
					$result = $this->add_address($vars, $input);
					break;
				case 'user.edit_account_info' :
					$result = $this->edit_account_info($vars, $input);
					break;
				case 'user.edit_password' :
					$result = $this->edit_password($vars, $input);
					break;
				case 'user.edit_password2' :
					$result = $this->edit_password2($vars, $input);
					break;
				case 'user.reset_password' :
					$result = $this->reset_password($vars, $input);
					break;
				case 'user.edit_payment_address' :
					$result = $this->edit_payment_address($vars, $input);
					break;
				case 'user.edit_shipping_address' :
					$result = $this->edit_shipping_address($vars, $input);
					break;
				case 'user.edit_address2' :
					$result = $this->edit_address2($vars, $input);
					break;
				case 'user.delete_address' :
					$result = $this->delete_address($vars, $input);
					break;
				case 'user.default_address' :
					$result = $this->default_address($vars, $input);
					break;
				case 'user.select_shipping_address' :
					$result = $this->select_shipping_address($vars, $input);
					break;
				case 'user.select_payment_address' :
					$result = $this->select_payment_address($vars, $input);
					break;
				case 'registration.check_email' :
					$result = $this->check_email($vars, $input);
					break;
				case 'registration.check_email2' :
					$result = $this->check_email2($vars, $input);
					break;
				case 'registration.get_states' :
					$result = $this->get_states($vars, $input);
					break;
				case 'registration.get_states2' :
					$result = $this->get_states2($vars, $input);
					break;
				case 'address.check_name' :
					$result = $this->check_name($vars, $input);
					break;
			}

			return $result;
		}


		switch(strtolower($task)) {
			case 'user.same_address' :
				$this->same_address($vars, $input);
				break;
			case 'user.reset_password_cancel' :
				$this->reset_password_cancel($vars, $input);
				break;
		}
	}

}

//die('asd');
class tpt_current_user {
	static function authorize_current_url(&$vars) {
		if(empty($vars['user'])) {
			return false;
		}

		if(isset($vars['environment']['page_rule']['id']) && empty($vars['environment']['page_rule']['id'])) {
			return true;
		} else if(!isset($vars['environment']['page_rule']['id'])) {
			return false;
		}


		$rule = $vars['environment']['page_rule'];
		$user = $vars['user'];
		//tpt_dump($vars['environment']['page_rule']);
		//tpt_dump($user);

		//tpt_dump($user['data']['access_level']);
		//tpt_dump($rule, true);
		if($rule['access_level'] > $user['data']['access_level']) {
			//tpt_dump('a', true);
			return false;
		} else {
			return true;
		}
	}

	static function authorize(&$vars, $level) {


		if(empty($vars['user']['data']['id'])) {
			return false;
		}
		$level = intval($level, 10);
		$user = $vars['user'];
		//tpt_dump($vars['environment']['page_rule']);
		//tpt_dump($level);
		//tpt_dump($user['data']['access_level']);
		//tpt_dump($level > $user['data']['access_level']);

		if($level > $user['data']['access_level']) {
			//tpt_dump('b', true);
			return false;
		} else {
			return true;
		}
	}

	static function authorize_block(&$vars, $block='') {


		if(empty($vars['user']['data']['id']) || empty($block) || !isset($vars['config']['authorization'][$block])) {
			return false;
		}
		$level = intval($vars['config']['authorization'][$block], 10);
		$user = $vars['user'];
		//tpt_dump($vars['environment']['page_rule']);
		//tpt_dump($level);
		//tpt_dump($user['data']['access_level']);
		//tpt_dump($level > $user['data']['access_level']);

		if($level > $user['data']['access_level']) {
			//tpt_dump('c', true);
			return false;
		} else {
			return true;
		}
	}

	static function setLoggedUserCookies(&$vars, $path = '/') {
		$tpt_baseurl = BASE_URL;
		$tpt_requesturl = $vars['config']['requesturl'];

		//tpt_dump($tpt_vars['user']['isLogged'], true);
		if($vars['user']['isLogged']) {
			$logged_user = self::get_user_cookie($vars);
			//tpt_dump($logged_user, true);
			tpt_request::setcookie($vars, 'tpt_logged_in', '1', time()+24*60*60*365, $path);
			tpt_request::setcookie($vars, 'tpt_logged_user', $logged_user, time()+24*60*60*365, $path);
		} else {
			tpt_request::setcookie($vars, 'tpt_logged_in', '', time()+24*60*60*365, $path);
			tpt_request::setcookie($vars, 'tpt_logged_user', '', time()+24*60*60*365, $path);
		}
	}

	static function setReturnURLCookies(&$vars, $path = '/') {
		$tpt_baseurl = BASE_URL;
		$tpt_requesturl = $vars['config']['requesturl'];

		if(!$vars['environment']['is404']) {

			if(!empty($vars['environment']['page_rule']['login_return_url'])) {
				//$_SESSION['templay']['login_return_url'] = $tpt_requesturl;

				/*
				if((($_SERVER['REMOTE_ADDR'] == '109.160.0.218') || ($_SERVER['REMOTE_ADDR'] == '213.226.63.136'))) {
					$save = $tpt_requesturl;
					$save .= "\n";
					$save .= "\n";
					ob_start();
					var_dump($vars['environment']['page_rule']);
					$save .= ob_get_clean();
					$save .= "\n";
					$save .= "\n";
					$save .= "\n";
					$save .= "\n";
					$save .= "\n";
					$save .= "\n";
					file_put_contents(dirname(__FILE__).DIRECTORY_SEPARATOR.'login_rurl.txt', $save, FILE_APPEND);
				}
				*/
				tpt_request::setcookie($vars, 'login_return_url', $tpt_requesturl, time()+24*60*60*365, $path);
			} else if(!empty($_COOKIE['login_return_url'])) {
				tpt_request::setcookie($vars, 'login_return_url', $_COOKIE['login_return_url'], time()+24*60*60*365, $path);
			} else {
				tpt_request::setcookie($vars, 'login_return_url', $vars['environment']['login_return_url'], time()+24*60*60*365, $path);
			}

			if(!empty($vars['environment']['page_rule']['logout_return_url'])) {
				//$_SESSION['templay']['logout_return_url'] = $tpt_requesturl;
				tpt_request::setcookie($vars, 'logout_return_url', $tpt_requesturl, time()+24*60*60*365, $path);
			} else if(!empty($_COOKIE['logout_return_url'])) {
				tpt_request::setcookie($vars, 'logout_return_url', $_COOKIE['logout_return_url'], time()+24*60*60*365, $path);
			} else {
				tpt_request::setcookie($vars, 'logout_return_url', $vars['environment']['logout_return_url'], time()+24*60*60*365, $path);
			}

			if(!empty($vars['environment']['page_rule']['continue_shopping_url'])) {
				//$_SESSION['templay']['continue_shopping_url'] = $tpt_requesturl;
				$csurl = parse_url($tpt_baseurl.$tpt_requesturl);
				$csurl = $csurl['scheme'].'://'.$csurl['host'].$csurl['path'];
				tpt_request::setcookie($vars, 'continue_shopping_url', $csurl, time()+24*60*60*365, $path);
			} else if(!empty($_COOKIE['continue_shopping_url'])) {
				//die();
				tpt_request::setcookie($vars, 'continue_shopping_url', $_COOKIE['continue_shopping_url'], time()+24*60*60*365, $path);
			} else {
				if(empty($vars['environment']['continue_shopping_url'])) {
					$vars['environment']['continue_shopping_url'] = $vars['url']['handler']->wrap($vars, '/design-custom-wristbands');
				}
				tpt_request::setcookie($vars, 'continue_shopping_url', $vars['environment']['continue_shopping_url'], time()+24*60*60*365, $path);
			}

			if(!empty($_COOKIE['future_back_url']) && ($_COOKIE['future_back_url'] != $tpt_requesturl)) {
				//$_SESSION['templay']['future_back_url'] = $tpt_requesturl;
				tpt_request::setcookie($vars, 'future_back_url', $tpt_requesturl, time()+24*60*60*365, $path);
			} else if(!empty($_COOKIE['future_back_url'])) {
				tpt_request::setcookie($vars, 'future_back_url', $_COOKIE['future_back_url'], time()+24*60*60*365, $path);
			} else {
				if(empty($vars['environment']['go_back_url'])) {
					//$vars['environment']['continue_shopping_url'] = $vars['url']['handler']->wrap($vars, '/design-custom-wristbands');
					$vars['environment']['go_back_url'] = BASE_URL;
				}
				tpt_request::setcookie($vars, 'future_back_url', $vars['environment']['go_back_url'], time()+24*60*60*365, $path);
			}

			//setcookie('continue_shopping_url', $vars['environment']['continue_shopping_url'], time()+24*60*60*365, '/');
			//$_SESSION['templay']['continue_shopping_url'] = $vars['environment']['continue_shopping_url'];
			//var_dump($_SESSION['templay']['continue_shopping_url']);
		}
	}

	static function isTexasBuyer(&$vars) {
		$countries_module = getModule($vars, "Countries");

		$country_id = $vars['user']['addresses']['shipping']['country'];
		$stateval = $vars['user']['addresses']['shipping']['state'];
		return $countries_module->isTX($vars, $country_id, $stateval);
	}

	static function get_tax_class(&$vars) {
		$countries_module = getModule($vars, "Countries");
		$user = $vars['user'];

		$country_id = $user['addresses']['shipping']['country'];
		$stateval = $user['addresses']['shipping']['state'];


		return $countries_module->getCountryStateTax($vars, $country_id, $stateval);
	}

	static function getUserEmail(&$vars) {
		return $vars['user']['username'];
	}
	static function getUserFullName(&$vars) {
		return $vars['user']['addresses']['payment']['fname'].' '.$vars['user']['addresses']['payment']['lname'];
	}


	static function getUserDataArray(&$vars) {
		$users_module = getModule($vars, "Users");
		$countries_module = getModule($vars, "Countries");
		$countries = $countries_module->moduleData['id'];

		$p_country = $countries[$vars['user']['addresses']['payment']['country']]['name'];
		$p_state   = $countries_module->getStateName($vars, $vars['user']['addresses']['payment']['country'], $vars['user']['addresses']['payment']['state']);
		$s_country = $countries[$vars['user']['addresses']['shipping']['country']]['name'];
		$s_state   = $countries_module->getStateName($vars, $vars['user']['addresses']['shipping']['country'], $vars['user']['addresses']['shipping']['state']);


		// shipping
		$bcsa = $vars['user']['addresses']['shipping'];
		$bcsa['country'] = $s_country;
		$bcsa['state'] = $s_state;


		$customer_shipping_address = $users_module->getAdminOrderAddressString($vars, $bcsa);
		$backend_cus_shipping = $users_module->getAdminOrderAddressString2($vars, $bcsa);


		// payment
		$bcpa = $vars['user']['addresses']['payment'];
		$bcpa['country'] = $p_country;
		$bcpa['state'] = $p_state;
		$customer_billing_address = $users_module->getAdminOrderAddressString($vars, $bcpa);
		//tpt_dump($customer_billing_address, true);
		$backend_cus_billing = $users_module->getAdminOrderAddressString2($vars, $bcpa);


		$customer_fields = array(
			'shipping'=>array(),
			'payment'=>array(),
			'general'=>array()
		);
		$customer_fields['shipping'] = array(
			'customer_shipping_address'=>$customer_shipping_address,
			'backend_cus_shipping'=>$backend_cus_shipping,
			'backend_shipping_name'=>$vars['user']['addresses']['shipping']['fname'] . '|' . $vars['user']['addresses']['shipping']['lname']
		);
		$customer_fields['payment'] = array(
			'customer_billing_address'=>$customer_billing_address,
			'backend_cus_billing'=>$backend_cus_billing,
			'backend_billing_name'=>$vars['user']['addresses']['payment']['fname'] . '|' . $vars['user']['addresses']['payment']['lname']
		);
		$customer_fields['general'] = array(
			'customer_name'=>trim($vars['user']['addresses']['payment']['fname']) . ' ' . trim($vars['user']['addresses']['payment']['lname']),
			'customer_email_id'=>$vars['user']['data']['username'],
			'ip'=>$vars['user']['client_ip'],
			'customer_phone'=>$vars['user']['data']['phone'],
			'customer_mobile'=>$vars['user']['data']['phone'],
			'zipcode'=>$vars['user']['addresses']['payment']['zip'],
			'customer_id'=>$vars['user']['data']['id']
		);

		return $customer_fields;
	}

	static function getUserShippingDataArray(&$vars) {
		$countries_module = getModule($vars, "Countries");
		$countries = $countries_module->moduleData['id'];

		//$p_country = $countries[$vars['user']['addresses']['payment']['country']]['name'];
		//$p_state   = $countries_module->getStateName($vars, $vars['user']['addresses']['payment']['country'], $vars['user']['addresses']['payment']['state']);
		$s_country = $countries[$vars['user']['addresses']['shipping']['country']]['name'];
		$s_state   = $countries_module->getStateName($vars, $vars['user']['addresses']['shipping']['country'], $vars['user']['addresses']['shipping']['state']);


		// shipping
		$bcsa = $vars['user']['addresses']['shipping'];
		$bcsa['country'] = $s_country;
		$bcsa['state'] = $s_state;
		//$customer_shipping_address = tpt_users::getAdminOrderAddressString($vars, $bcsa);
		//$backend_cus_shipping = tpt_users::getAdminOrderAddressString2($vars, $bcsa);


		// payment
		//$bcpa = $vars['user']['addresses']['payment'];
		//$bcpa['country'] = $p_country;
		//$bcpa['state'] = $p_state;
		//$customer_billing_address = tpt_users::getAdminOrderAddressString($vars, $bcpa);
		//tpt_dump($customer_billing_address, true);
		//$backend_cus_billing = tpt_users::getAdminOrderAddressString2($vars, $bcpa);


		$customer_fields = array(
			'shipping'=>array(),
			//'payment'=>array(),
			//'general'=>array()
		);
		$customer_fields['shipping'] = array(
			'fname'=>$vars['user']['addresses']['shipping']['fname'],
			'lname'=>$vars['user']['addresses']['shipping']['lname'],
			'company'=>$vars['user']['addresses']['shipping']['company'],
			'address1'=>$vars['user']['addresses']['shipping']['address1'],
			'address2'=>$vars['user']['addresses']['shipping']['address2'],
			'address3'=>$vars['user']['addresses']['shipping']['address3'],
			'city'=>$vars['user']['addresses']['shipping']['city'],
			'state'=>$bcsa['state'],
			'zip'=>$vars['user']['addresses']['shipping']['zip'],
			'country'=>$bcsa['country'],
			//'customer_shipping_address'=>$customer_shipping_address,
			//'backend_cus_shipping'=>$backend_cus_shipping,
			//'backend_shipping_name'=>$vars['user']['addresses']['shipping']['fname'] . '|' . $vars['user']['addresses']['shipping']['lname']
		);
		//$customer_fields['payment'] = array(
		//    'customer_billing_address'=>$customer_billing_address,
		//    'backend_cus_billing'=>$backend_cus_billing,
		//    'backend_billing_name'=>$vars['user']['addresses']['payment']['fname'] . '|' . $vars['user']['addresses']['payment']['lname']
		//                      );
		//$customer_fields['general'] = array(
		//    'customer_name'=>$vars['user']['addresses']['payment']['fname'] . ' ' . $vars['user']['addresses']['payment']['lname'],
		//    'customer_email_id'=>$vars['user']['data']['username'],
		//    'ip'=>$vars['user']['client_ip'],
		//    'customer_phone'=>$vars['user']['data']['phone'],
		//    'customer_mobile'=>$vars['user']['data']['phone'],
		//    'zipcode'=>$vars['user']['addresses']['payment']['zip'],
		//    'customer_id'=>$vars['user']['data']['id']
		//                      );

		return $customer_fields['shipping'];
	}

	static function getUserPaymentDataArray(&$vars) {
		$countries_module = getModule($vars, "Countries");
		$countries = $countries_module->moduleData['id'];

		$p_country = $countries[$vars['user']['addresses']['payment']['country']]['name'];
		$p_state   = $countries_module->getStateName($vars, $vars['user']['addresses']['payment']['country'], $vars['user']['addresses']['payment']['state']);
		//$s_country = $countries[$vars['user']['addresses']['shipping']['country']]['name'];
		//$s_state   = $countries_module->getStateName($vars, $vars['user']['addresses']['shipping']['country'], $vars['user']['addresses']['shipping']['state']);


		// shipping
		//$bcsa = $vars['user']['addresses']['shipping'];
		//$bcsa['country'] = $s_country;
		//$bcsa['state'] = $s_state;
		//$customer_shipping_address = tpt_users::getAdminOrderAddressString($vars, $bcsa);
		//$backend_cus_shipping = tpt_users::getAdminOrderAddressString2($vars, $bcsa);


		// payment
		$bcpa = $vars['user']['addresses']['payment'];
		$bcpa['country'] = $p_country;
		$bcpa['state'] = $p_state;
		//$customer_billing_address = tpt_users::getAdminOrderAddressString($vars, $bcpa);
		//tpt_dump($customer_billing_address, true);
		//$backend_cus_billing = tpt_users::getAdminOrderAddressString2($vars, $bcpa);


		$customer_fields = array(
			//'shipping'=>array(),
			'payment'=>array(),
			//'general'=>array()
		);
		//$customer_fields['shipping'] = array(
		//    'customer_shipping_address'=>$customer_shipping_address,
		//    'backend_cus_shipping'=>$backend_cus_shipping,
		//    'backend_shipping_name'=>$vars['user']['addresses']['shipping']['fname'] . '|' . $vars['user']['addresses']['shipping']['lname']
		//                      );
		$customer_fields['payment'] = array(
			'fname'=>$vars['user']['addresses']['payment']['fname'],
			'lname'=>$vars['user']['addresses']['payment']['lname'],
			'company'=>$vars['user']['addresses']['payment']['company'],
			'address1'=>$vars['user']['addresses']['payment']['address1'],
			'address2'=>$vars['user']['addresses']['payment']['address2'],
			'address3'=>$vars['user']['addresses']['payment']['address3'],
			'city'=>$vars['user']['addresses']['payment']['city'],
			'state'=>$bcpa['state'],
			'zip'=>$vars['user']['addresses']['payment']['zip'],
			'country'=>$bcpa['country'],
			//    'customer_billing_address'=>$customer_billing_address,
			//    'backend_cus_billing'=>$backend_cus_billing,
			//    'backend_billing_name'=>$vars['user']['addresses']['payment']['fname'] . '|' . $vars['user']['addresses']['payment']['lname']
		);
		//$customer_fields['general'] = array(
		//    'customer_name'=>$vars['user']['addresses']['payment']['fname'] . ' ' . $vars['user']['addresses']['payment']['lname'],
		//    'customer_email_id'=>$vars['user']['data']['username'],
		//    'ip'=>$vars['user']['client_ip'],
		//    'customer_phone'=>$vars['user']['data']['phone'],
		//    'customer_mobile'=>$vars['user']['data']['phone'],
		//    'zipcode'=>$vars['user']['addresses']['payment']['zip'],
		//    'customer_id'=>$vars['user']['data']['id']
		//                      );

		return $customer_fields['payment'];
	}

	static function update_store_cart_id(&$vars, $cart_id=0) {
		$users_module = getModule($vars, "Users");

		$user_id = intval($vars['user']['userid'], 10);

		if(!empty($user_id)) {
			$users_module->update_store_cart_id($vars, $user_id, $cart_id);
		}
	}


	static function get_user_cookie(&$vars) {
		$users_module = getModule($vars, "Users");

		$username = $vars['user']['username'];
		$userid = intval($vars['user']['userid'], 10);
		//tpt_dump($userid, true);
		return $users_module->get_user_cookie_string($vars, $userid, $username);
	}


	static function get_abandoned_carts_list(&$vars, $order='DESC') {
		$users_module = getModule($vars, "Users");

		$user_id = intval($vars['user']['userid'], 10);

		return $users_module->get_abandoned_carts_list($vars, $user_id);
	}

	static function get_abandoned_carts_data(&$vars, $order='DESC') {
		$users_module = getModule($vars, "Users");

		$user_id = intval($vars['user']['userid'], 10);

		return $users_module->get_abandoned_carts_data($vars, $user_id);
	}

	static function set_abandoned_cart_notification(&$vars, $state=0) {
		$users_module = getModule($vars, "Users");

		$user_id = intval($vars['user']['userid'], 10);

		$users_module->set_abandoned_cart_notification($vars, $user_id, $state);
	}

	static function get_abandoned_cart_notification(&$vars) {
		$users_module = getModule($vars, "Users");

		$user_id = intval($vars['user']['userid'], 10);

		return $users_module->get_abandoned_cart_notification($vars, $user_id);
	}



	static function isLogged(&$vars) {
		if(!empty($vars['user']['isLogged'])) {
			return 1;
		} else {
			return 0;
		}
	}
}