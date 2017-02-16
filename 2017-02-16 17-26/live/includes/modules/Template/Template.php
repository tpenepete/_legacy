<?php

defined('TPT_INIT') or die('access denied');

class tpt_module_Template extends tpt_Module {

	function __construct(&$vars, $name, $moduleClassFile, $moduleClass, $moduleTable) {
		$fields = array(
			//db field name|field type|length|options|storage options|control|ctrAttr|default|label|index by|split keys|template
			new tpt_ModuleField('id',    'n', null, 'ai', '',         'sp', '', '', '',           true, false,  'LC'),
			new tpt_ModuleField('directory',   's', 80,   '',   '', 'tf', ' style="width: 230px;"', '', 'Directory', false, false, 'LC'),
			new tpt_ModuleField('added_by',   's', 255,   '',   '', 'tf', ' style="width: 230px;"', 'dflt', 'Added By', false, false, 'LC'),
		);

		parent::__construct($vars, $name, $moduleClassFile, $moduleClass, $moduleTable, $fields, 'id');
	}









	function load(&$vars) {
		$template_id = $vars['environment']['page_rule']['template_id'];

		return $this->moduleData['id'][$template_id];
	}
	static function render(&$vars, $template) {
		if(!defined('TPT_BACK')) {
			//tpt_dump('1', true);

			//tpt_dump($tpt_vars['environment']['isAjax'], true);
			if(!$vars['environment']['isAjax']) {



				//tpt_dump($tpt_vars['environment']['isAjax'], true);
				if($vars['template_data']['isFrame']) {
					//die('dddd');
					ob_start();
				}

				//var_dump($tpt_vars['template_data']['hasLeftBar']);die();
				//var_dump($left_bar);die();


				tpt_current_user::setLoggedUserCookies($vars);

				//tpt_content_cache::load_cache($tpt_vars);

				//tpt_dump('asd');
				if(empty($vars['template']['cache'])) {
					switch($vars['template_data']['template_type']) {
						case 'plain' :
							include(TPT_MAIN_TEMPLATES_DIR.DIRECTORY_SEPARATOR.'template-plain.php');
							break;
						default:
							if($vars['environment']['isAdministration']) {
								//die('asdasdasdasd');
								//tpt_dump($tpt_vars['template']['admin_header'], true);
								if(!isset($vars['template']['admin_header'])) {$vars['template']['admin_header'] = ''; }
								$admin_header = $vars['template']['admin_header'];
								include(TPT_ADMIN_BASE_DIR.DIRECTORY_SEPARATOR.'template.php');
							} else {
								if(empty($vars['environment']['mobile_template'])) {
									//tpt_dump('asd');
									include(TPT_MAIN_TEMPLATES_DIR.DIRECTORY_SEPARATOR.$template['directory'].DIRECTORY_SEPARATOR.'template.php');
									//tpt_dump('asd');
								} else if($vars['environment']['mobile_template'] == 1) {
									include(TPT_MAIN_TEMPLATES_DIR.DIRECTORY_SEPARATOR.$template['directory'].DIRECTORY_SEPARATOR.'template-mobile0.php');
								} else {
									include(TPT_MAIN_TEMPLATES_DIR.DIRECTORY_SEPARATOR.$template['directory'].DIRECTORY_SEPARATOR.'template-mobile1.php');
								}
								echo tpt_html_template_variant::main($vars);
							}
					}
					//tpt_dump('asd');


					if($vars['template_data']['isFrame']) {
						//die('dddd');
						echo str_replace('<a', '<a target="_parent"', ob_get_clean());
					}

					//tpt_content_cache::handle_cache();

				} else {
					echo $vars['template']['cache'];
				}
				//tpt_dump('asd');


				//include(dirname(__FILE__).DIRECTORY_SEPARATOR.'template2.php');
			} else {
				//tpt_dump($tpt_vars['environment']['isTask'], true);
				if($vars['environment']['isTask']) {
					//tpt_dump('asdasd', true);
					//var_dump($admin_content);die();
					$update_elements = array();
					$append_html = $vars['environment']['ajax_result']['append_html'];
					if(empty($vars['environment']['ajax_result']['update_elements'])) {
						$update_elements = array(
							//'content'=>$content,
							'tpt_messages'=>$vars['template_data']['messages']
						);
					} else {
						$update_elements = $vars['environment']['ajax_result']['update_elements'];
						if(!isDev('unpackresources') || empty($_GET['unpackresources'])) {
							//$template = tpt_html::sanitize_html($template);
//tpt_dump($styles, true);
//$template = str_replace('<!DOCTYPE html>', '<!DOCTYPE html>'."\n", $template);
							$update_elements = tpt_html::sanitize_html_output($update_elements);
							/*
							$options = array(
								'cssMinifier'=>tpt_html::sanitize_css_output,
								'jsMinifier'=>JSMin::minify
							);
							*/
//$template = Minify_HTML::minify($template);
						}
						//$update_elements = str_replace('&nbsp;', '\\u00a0', $update_elements);
						// [possible problems....]
						//$update_elements = array_map('utf8_encode', $tpt_vars['environment']['ajax_result']['update_elements']);
					}
					$add_style = implode("\n", $vars['environment']['ajax_result']['add_style']);
					$exec_script = implode("\n", $vars['environment']['ajax_result']['exec_script']);
					$response = array(
						'action'=>'update',
						'update_objects'=>array(),
						'update_elements'=>$update_elements,
						'append_html'=>$append_html,
						'add_style'=>$add_style,
						'exec_script'=>$exec_script
					);
					/*
					if($tpt_vars['environment']['isAdmin']) {
						$response['update_elements']['admin_content'] = $admin_content;
						if($tpt_vars['user']['isLogged']) {
							$response['update_objects']['tpt_admin_tabs'] = $admin_tabs;
						}
					}
					*/
					//tpt_dump($response, true);
					tpt_current_user::setLoggedUserCookies($vars);
					//var_dump($response);die();
					//json_encode($response);

					//var_dump(json_last_error());//die();
					//var_dump($response);die();
					//tpt_dump($response, true);
					//tpt_dump(json_encode($response), true);
					//json_encode($response, JSON_UNESCAPED_UNICODE);
					//tpt_dump(json_last_error_msg (), true);
					die(json_encode($response));
				} else {
					tpt_current_user::setLoggedUserCookies($vars);
					die($vars['environment']['ajax_response']);
				}
			}
		}
	}

	static function getMinicartDefault(&$vars) {
		/*
		  if(empty($vars['environment']['mobile_template'])) {
		  return self::getMinicartDefaultDesktop($vars);
		  } else if($vars['environment']['mobile_template'] == 1) {
		  return self::getMinicartDefaultMobile($vars);
		  } else {
		  return self::getMinicartDefaultMobile2($vars);
		  }
		 */
		//tpt_dump('asd', true);
		if (empty($vars['environment']['mobile_device'])) {
			return self::getMinicartDefaultDesktop($vars);
		} else {
			return self::getMinicartDefaultMobile($vars);
		}
	}

	static function getMinicart(&$vars) {
		if (empty($vars['environment']['mobile_template'])) {
			return self::getMinicartDesktop($vars);
		} else if ($vars['environment']['mobile_template'] == 1) {
			return self::getMinicartMobile($vars);
		} else {
			return self::getMinicartMobile2($vars);
		}
	}

	static function getMinicartDefaultDesktop(&$vars) {
		$prCount = amz_cart::$totals['products_count'];
		$sub_total = !empty(amz_cart::$totals['pricing']['html']['customer_price']) ? amz_cart::$totals['pricing']['html']['customer_price'] : HTML_ZERO_PRICE;

		$bands_url = $vars['url']['handler']->wrap($vars, '/holiday-designs/merry-christmas-limited-edition-atc');
		$basket_url = $vars['url']['handler']->wrap($vars, '/your-basket');



// master template
		$content = <<< EOT
<div class="color-white line-height-17">
	<div>
		<a href="$basket_url" title="View Added Products">Your Basket</a>:
	</div>
	<div class="font-style-italic">
		(empty)
	</div>
	<!--a class="font-size-10" href="$bands_url" title="Create Your Custom Design Now!">Choose Your Bands</a-->
</div>
EOT;
		return $content;
	}

	static function getMinicartDesktop(&$vars) {
		$prCount = amz_cart::$totals['products_count'];
		$sub_total = !empty(amz_cart::$totals['pricing']['html']['customer_price']) ? amz_cart::$totals['pricing']['html']['customer_price'] : HTML_ZERO_PRICE;

		$bands_url = $vars['url']['handler']->wrap($vars, '/holiday-designs/merry-christmas-limited-edition-atc');
		$basket_url = $vars['url']['handler']->wrap($vars, '/your-basket');



// master template
		$content = <<< EOT
<div class="color-white line-height-17">
	<div>
		<a href="$basket_url" title="View Added Products">Your Basket</a>:
	</div>
EOT;
		if ($prCount > 0) {
			$content .= <<< EOT
	<div>
		<a href="$basket_url" title="View Added Products">($prCount Products)</a>
	</div>
	<div>
		<a href="$basket_url" title="View Added Products">($sub_total)</a>
	</div>
EOT;
		} else {
			$content .= <<< EOT
	<div class="font-style-italic">
		(empty)
	</div>
	<!--a class="font-size-10" href="$bands_url" title="Create Your Custom Design Now!">Choose Your Bands</a-->
EOT;
		}

		$content .= <<< EOT
</div>
EOT;
		return $content;
	}

	static function getMinicartDefaultMobile(&$vars) {
		$tpt_baseurl = BASE_URL;
		$tpt_imagesurl = TPT_IMAGES_URL;

		$prCount = amz_cart::$totals['products_count'];
		$sub_total = !empty(amz_cart::$totals['pricing']['html']['customer_price']) ? amz_cart::$totals['pricing']['html']['customer_price'] : HTML_ZERO_PRICE;

		$bands_url = $vars['url']['handler']->wrap($vars, '/holiday-designs/merry-christmas-limited-edition-atc');
		$basket_url = $vars['url']['handler']->wrap($vars, '/your-basket');



// master template

		$content = <<< EOT
<div class="color-white">
	<div>
		<a class="font-size-150prc" href="$basket_url" title="View Added Products">Your Basket</a>:
	</div>
	<div class="font-style-italic">
		(empty)
	</div>
	<!--a href="$bands_url" title="Create Your Custom Design Now!">Choose Your Bands</a-->
</div>
EOT;
		return $content;
	}

	static function getMinicartMobile(&$vars) {
		$tpt_baseurl = BASE_URL;
		$tpt_imagesurl = TPT_IMAGES_URL;

		$prCount = amz_cart::$totals['products_count'];
		$sub_total = !empty(amz_cart::$totals['pricing']['html']['customer_price']) ? amz_cart::$totals['pricing']['html']['customer_price'] : HTML_ZERO_PRICE;

		$bands_url = $vars['url']['handler']->wrap($vars, '/holiday-designs/merry-christmas-limited-edition-atc');
		$basket_url = $vars['url']['handler']->wrap($vars, '/your-basket');



// master template

		$content = <<< EOT
<div class="color-white">
	<div>
		<a class="font-size-150prc" href="$basket_url" title="View Added Products">Your Basket</a>:
	</div>
EOT;
		if ($prCount > 0) {
			$content .= <<< EOT
	<div>
		<a class="font-size-150prc" href="$basket_url" title="View Added Products">($prCount Products)</a>
	</div>
	<div>
		<a class="font-size-150prc" href="$basket_url" title="View Added Products">($sub_total)</a>
	</div>
EOT;
		} else {
			$content .= <<< EOT
	<div class="font-style-italic">
		(empty)
	</div>
	<!--a href="$bands_url" title="Create Your Custom Design Now!">Choose Your Bands</a-->
EOT;
		}

		$content .= <<< EOT
</div>
EOT;
		return $content;
	}

	static function getMinicartDefaultMobile2(&$vars) {
		$tpt_baseurl = BASE_URL;
		$tpt_imagesurl = TPT_IMAGES_URL;

		$prCount = amz_cart::$totals['products_count'];
		$sub_total = !empty(amz_cart::$totals['pricing']['html']['customer_price']) ? amz_cart::$totals['pricing']['html']['customer_price'] : HTML_ZERO_PRICE;

		$bands_url = $vars['url']['handler']->wrap($vars, '/holiday-designs/merry-christmas-limited-edition-atc');
		$basket_url = $vars['url']['handler']->wrap($vars, '/your-basket');



// master template

		$content = <<< EOT
<div class="color-white">
	<div>
		<a class="white-space-nowrap font-size-150prc" href="$basket_url" title="View Added Products">Your Basket</a>:
	</div>
	<div class="font-style-italic">
		(empty)
	</div>
	<!--a href="$bands_url" title="Create Your Custom Design Now!">Choose Your Bands</a-->
</div>
EOT;
		return $content;
	}

	static function getMinicartMobile2(&$vars) {
		$tpt_baseurl = BASE_URL;
		$tpt_imagesurl = TPT_IMAGES_URL;

		$prCount = amz_cart::$totals['products_count'];
		$sub_total = !empty(amz_cart::$totals['pricing']['html']['customer_price']) ? amz_cart::$totals['pricing']['html']['customer_price'] : HTML_ZERO_PRICE;

		$bands_url = $vars['url']['handler']->wrap($vars, '/holiday-designs/merry-christmas-limited-edition-atc');
		$basket_url = $vars['url']['handler']->wrap($vars, '/your-basket');



// master template

		$content = <<< EOT
<div class="color-white">
	<div>
		<a class="white-space-nowrap font-size-150prc" href="$basket_url" title="View Added Products">Your Basket</a>:
	</div>
EOT;
		if ($prCount > 0) {
			$content .= <<< EOT
	<div>
		<a class="white-space-nowrap font-size-150prc" href="$basket_url" title="View Added Products">($prCount Products)</a>
	</div>
	<div>
		<a class="white-space-nowrap font-size-150prc" href="$basket_url" title="View Added Products">($sub_total)</a>
	</div>
EOT;
		} else {
			$content .= <<< EOT
	<div class="font-style-italic">
		(empty)
	</div>
	<!--a href="$bands_url" title="Create Your Custom Design Now!">Choose Your Bands</a-->
EOT;
		}

		$content .= <<< EOT
</div>
EOT;
		return $content;
	}

	static function getCachedFrontendHeaderCustomerArea(&$vars) {
		$customer_area = self::getFrontendHeaderCustomerAreaDefault($vars);
		if (isset($_SESSION['customer_area']) && !empty($_SESSION['customer_area'])) {
			if (empty($vars['environment']['mobile_template'])) {
				$customer_area = $_SESSION['customer_area'];
			} else if ($vars['environment']['mobile_template'] == 1) {
				$customer_area = $_SESSION['customer_area_mobile1'];
			} else {
				$customer_area = $_SESSION['customer_area_mobile2'];
			}
		}

		return $customer_area;
	}

	static function getFrontendHeaderCustomerAreaDefault(&$vars) {
		if (empty($vars['environment']['mobile_template'])) {
			return self::getFrontendHeaderCustomerAreaDefaultDesktop($vars);
		} else if ($vars['environment']['mobile_template'] == 1) {
			return self::getFrontendHeaderCustomerAreaDefaultMobile($vars);
		} else {
			return self::getFrontendHeaderCustomerAreaDefaultMobile2($vars);
		}

		/*
		  if(empty($vars['environment']['mobile_device'])) {
		  return self::getFrontendHeaderCustomerAreaDefaultDesktop($vars);
		  } else {
		  return self::getFrontendHeaderCustomerAreaDefaultMobile($vars);
		  }
		 */
	}

	static function getFrontendHeaderCustomerArea(&$vars) {
		if ((isDev('cachetags') && !empty($_GET['cachetags'])) || (isDev('rebuildcache') && !empty($_GET['rebuildcache']))) {
			return TPT_TAG_CUSTOMERAREA;
		}

		if (empty($vars['environment']['mobile_template'])) {
			return self::getFrontendHeaderCustomerAreaDesktop($vars);
		} else if ($vars['environment']['mobile_template'] == 1) {
			return self::getFrontendHeaderCustomerAreaMobile($vars);
		} else {
			return self::getFrontendHeaderCustomerAreaMobile2($vars);
		}
	}

	static function getFrontendHeaderCustomerAreaDefaultDesktop(&$vars) {
		//tpt_dump('asd', true);
		$tpt_baseurl = BASE_URL;
		$tpt_imagesurl = TPT_IMAGES_URL;

		$action_url = $vars['url']['handler']->wrap($vars, '/login-register');
		$logout_url = $vars['url']['handler']->wrap($vars, '/logout');

		$login_link = $vars['url']['handler']->wrap($vars, '/login-register');
		$register_link = $vars['url']['handler']->wrap($vars, '/login-register?register=1');
		$my_account_link = $vars['url']['handler']->wrap($vars, '/my-account');
		$shipping_url = $vars['url']['handler']->wrap($vars, '/shipping-details');

		$minicart = self::getMinicartDefault($vars);

		$content = <<< EOT
	<div id="minicart_wr" class="padding-left-1 float-right">
		<div class="width-96 height-203" style="background: transparent url($tpt_imagesurl/layout-elements1.png) no-repeat scroll -365px 0px;">
			<div class="amz_red height-58" style="padding: 2px 4px;">
				<div class="padding-bottom-5"><a href="$login_link">Login</a></div>
				<div class="padding-top-5"><a class="display-block" href="$register_link" title="Create Your User Profile Now!">Register</a></div>
			</div>
			<div class="height-58" style="padding: 2px 4px;">
				$minicart
			</div>
			<div class="text-align-center color-white" style="padding: 12px 4px;">
				<a class="amz_checkout_link display-inline-block text-align-center width-78" style="background-color: #D8302D;" href="$shipping_url" title="Place Your Order">
					<span class="display-inline-block padding-left-5 padding-right-5 height-18 line-height-14 font-weight-bold">Checkout</span>
				</a>
			</div>
		</div>
	</div>
EOT;

		return $content;
	}

	static function getFrontendHeaderCustomerAreaDesktop(&$vars) {
		$tpt_baseurl = BASE_URL;
		$tpt_imagesurl = TPT_IMAGES_URL;

		$action_url = $vars['url']['handler']->wrap($vars, '/login-register');
		$logout_url = $vars['url']['handler']->wrap($vars, '/logout');

		$login_link = $vars['url']['handler']->wrap($vars, '/login-register');
		$register_link = $vars['url']['handler']->wrap($vars, '/login-register?register=1');
		$my_account_link = $vars['url']['handler']->wrap($vars, '/my-account');
		$shipping_url = $vars['url']['handler']->wrap($vars, '/shipping-details');

		$minicart = self::getMinicart($vars);

		$content = <<< EOT
	<div id="minicart_wr" class="padding-left-1 float-right">
		<div class="width-96 height-203" style="background: transparent url($tpt_imagesurl/layout-elements1.png) no-repeat scroll -365px 0px;">
			<div class="amz_red height-58" style="padding: 2px 4px;">
EOT;
		if (!$vars['user']['isLogged']) {
			$content .= <<< EOT
				<div class="padding-bottom-5"><a href="$login_link">Login</a></div>
				<div class="padding-top-5"><a class="display-block" href="$register_link" title="Create Your User Profile Now!">Register</a></div>
EOT;
		} else {
			//$action_url = $vars['url']['handler']->wrap($vars, '/login-register');
			/* ?>
			  <div>hi,</div>
			  <div><a title="View Your Orders or Update Your Profile Info" href="<?php echo $tpt_vars['url']['handler']->wrap($tpt_vars, '/my-account'); ?>"><?php echo $tpt_vars['user']['data']['fname']; ?></a></div>
			 */
			$content .= <<< EOT
				<div class="padding-bottom-5"><a title="View Your Orders or Update Your Profile Info" href="$my_account_link">My Account</a></div>
EOT;
			/* <div><form method="POST" action="$action_url" accept-charset="utf-8"><input type="hidden" name="task" value="user.logout" /><input class="amz_red plain_input_field text-decoration-underline" type="submit" value="Logout" /></form></div>
			 */
			$content .= <<< EOT
				<div class="padding-top-5"><a title="Logout from the system" href="$logout_url">Logout</a></div>
EOT;
		}
		$content .= <<< EOT
			</div>
			<div class="height-58" style="padding: 2px 4px;">
				$minicart
			</div>
			<div class="text-align-center color-white" style="padding: 12px 4px;">
				<a class="amz_checkout_link display-inline-block text-align-center width-78" style="background-color: #D8302D;" href="$shipping_url" title="Place Your Order">
					<span class="display-inline-block padding-left-5 padding-right-5 height-18 line-height-14 font-weight-bold">Checkout</span>
				</a>
			</div>
		</div>
	</div>
EOT;

		return $content;
	}

	static function getFrontendHeaderCustomerAreaDefaultMobile(&$vars) {
		$tpt_baseurl = BASE_URL;
		$tpt_imagesurl = TPT_IMAGES_URL;

		$action_url = $vars['url']['handler']->wrap($vars, '/login-register');
		$logout_url = $vars['url']['handler']->wrap($vars, '/logout');

		$login_link = $vars['url']['handler']->wrap($vars, '/login-register');
		$register_link = $vars['url']['handler']->wrap($vars, '/login-register?register=1');
		$my_account_link = $vars['url']['handler']->wrap($vars, '/my-account');
		$shipping_url = $vars['url']['handler']->wrap($vars, '/shipping-details');

		$minicart = self::getMinicartDefault($vars);

		$content = <<< EOT
				<div id="minicart_wr" style="border-radius: 5px 5px 5px 5px; border-color: #D72525; border-style: none; border-width: 1px 1px 1px 1px;">
					<div class="amz_yellow_bg" style="border-radius: 5px 5px 5px 5px; border-color: #F4E4CA; border-style: solid; border-width: 1px 1px 1px 1px;">
						<div class="amz_red padding-left-5 padding-right-5">
							<div class="padding-top-5 padding-bottom-5 font-size-150prc"><a class="display-inline-block" href="$login_link">Login</a></div>
							<div class="padding-top-5 padding-bottom-5 font-size-150prc"><a class="display-inline-block" href="$register_link" title="Create Your User Profile Now!">Register</a></div>
						</div>
						<div class="padding-10" style="background: #442A1B none;">
							$minicart
						</div>
						<div class="text-align-center color-white font-size-150prc">
							<a class="amz_checkout_link display-block text-align-center" style="background-color: #D8302D;" href="$shipping_url" title="Place Your Order">
								<span class="display-inline-block padding-left-5 padding-right-5 height-18 line-height-14 font-weight-bold">Checkout</span>
							</a>
						</div>
					</div>
				</div>
EOT;

		return $content;
	}

	static function getFrontendHeaderCustomerAreaMobile(&$vars) {
		$tpt_baseurl = BASE_URL;
		$tpt_imagesurl = TPT_IMAGES_URL;

		$action_url = $vars['url']['handler']->wrap($vars, '/login-register');
		$logout_url = $vars['url']['handler']->wrap($vars, '/logout');

		$login_link = $vars['url']['handler']->wrap($vars, '/login-register');
		$register_link = $vars['url']['handler']->wrap($vars, '/login-register?register=1');
		$my_account_link = $vars['url']['handler']->wrap($vars, '/my-account');
		$shipping_url = $vars['url']['handler']->wrap($vars, '/shipping-details');

		$minicart = self::getMinicart($vars);

		$content = <<< EOT
				<div id="minicart_wr" style="border-radius: 5px 5px 5px 5px; border-color: #D72525; border-style: none; border-width: 1px 1px 1px 1px;">
					<div class="amz_yellow_bg" style="border-radius: 5px 5px 5px 5px; border-color: #F4E4CA; border-style: solid; border-width: 1px 1px 1px 1px;">
						<div class="amz_red padding-left-5 padding-right-5">
EOT;
		if (!$vars['user']['isLogged']) {
			$content .= <<< EOT
							<div class="padding-top-5 padding-bottom-5 font-size-150prc"><a class="display-inline-block" href="$login_link">Login</a></div>
							<div class="padding-top-5 padding-bottom-5 font-size-150prc"><a class="display-inline-block" href="$register_link" title="Create Your User Profile Now!">Register</a></div>
EOT;
		} else {
			//$action_url = $vars['url']['handler']->wrap($vars, '/login-register');
			/* ?>
			  <div>hi,</div>
			  <div><a title="View Your Orders or Update Your Profile Info" href="<?php echo $tpt_vars['url']['handler']->wrap($tpt_vars, '/my-account'); ?>"><?php echo $tpt_vars['user']['data']['fname']; ?></a></div>
			 */
			$content .= <<< EOT
							<div class="padding-top-5 padding-bottom-5 font-size-150prc"><a title="View Your Orders or Update Your Profile Info" href="$my_account_link">My Account</a></div>
EOT;
			/* <div><form method="POST" action="$action_url" accept-charset="utf-8"><input type="hidden" name="task" value="user.logout" /><input class="amz_red plain_input_field text-decoration-underline" type="submit" value="Logout" /></form></div>
			 */
			$content .= <<< EOT
							<div class="padding-top-5 padding-bottom-5 font-size-150prc"><a title="Logout from the system" href="$logout_url">Logout</a></div>
EOT;
		}
		$content .= <<< EOT
						</div>
						<div class="padding-10" style="background: #442A1B none;">
							$minicart
						</div>
						<div class="text-align-center color-white font-size-150prc">
							<a class="amz_checkout_link display-block text-align-center" style="background-color: #D8302D;" href="$shipping_url" title="Place Your Order">
								<span class="display-inline-block padding-left-5 padding-right-5 height-18 line-height-14 font-weight-bold">Checkout</span>
							</a>
						</div>
					</div>
				</div>
EOT;

		return $content;
	}

	static function getFrontendHeaderCustomerAreaDefaultMobile2(&$vars) {
		$tpt_baseurl = BASE_URL;
		$tpt_imagesurl = TPT_IMAGES_URL;

		$action_url = $vars['url']['handler']->wrap($vars, '/login-register');
		$logout_url = $vars['url']['handler']->wrap($vars, '/logout');

		$login_link = $vars['url']['handler']->wrap($vars, '/login-register');
		$register_link = $vars['url']['handler']->wrap($vars, '/login-register?register=1');
		$my_account_link = $vars['url']['handler']->wrap($vars, '/my-account');
		$shipping_url = $vars['url']['handler']->wrap($vars, '/shipping-details');

		$minicart = self::getMinicartDefault($vars);

		$content = <<< EOT
				<div id="minicart_wr" class="opacity-0 float-right " style="border-radius: 0px 0px 0px 0px; border-color: #D72525; border-style: none; border-width: 0px 0px 0px 0px;">
					<div class="amz_yellow_bg" style="border-radius: 0px 0px 0px 0px; border-color: #F4E4CA; border-style: solid; border-width: 0px 0px 0px 0px;">
						<div class="amz_red padding-left-5 padding-right-5" style="">
							<div class="padding-top-5 padding-bottom-5 font-size-150prc"><a class="display-inline-block" href="$login_link">Login</a></div>
							<div class="padding-top-5 padding-bottom-5 font-size-150prc"><a class="display-inline-block" href="$register_link" title="Create Your User Profile Now!">Register</a></div>
						</div>
						<div class="padding-10" style="background: #442A1B none;">
							$minicart
						</div>
						<div class="text-align-center color-white font-size-150prc" style="">
							<a class="amz_checkout_link display-block text-align-center" style="background-color: #D8302D;" href="$shipping_url" title="Place Your Order">
								<span class="display-inline-block padding-left-5 padding-right-5 height-18 line-height-14 font-weight-bold">Checkout</span>
							</a>
						</div>
					</div>
				</div>
EOT;

		return $content;
	}

	static function getFrontendHeaderCustomerAreaMobile2(&$vars) {
		$tpt_baseurl = BASE_URL;
		$tpt_imagesurl = TPT_IMAGES_URL;

		$action_url = $vars['url']['handler']->wrap($vars, '/login-register');
		$logout_url = $vars['url']['handler']->wrap($vars, '/logout');

		$login_link = $vars['url']['handler']->wrap($vars, '/login-register');
		$register_link = $vars['url']['handler']->wrap($vars, '/login-register?register=1');
		$my_account_link = $vars['url']['handler']->wrap($vars, '/my-account');
		$shipping_url = $vars['url']['handler']->wrap($vars, '/shipping-details');

		$minicart = self::getMinicart($vars);

		$content = <<< EOT
				<div id="minicart_wr" class="opacity-0 float-right " style="border-radius: 0px 0px 0px 0px; border-color: #D72525; border-style: none; border-width: 0px 0px 0px 0px;">
					<div class="amz_yellow_bg" style="border-radius: 0px 0px 0px 0px; border-color: #F4E4CA; border-style: solid; border-width: 0px 0px 0px 0px;">
						<div class="amz_red padding-left-5 padding-right-5" style="">
EOT;
		if (!$vars['user']['isLogged']) {
			$content .= <<< EOT
							<div class="padding-top-5 padding-bottom-5 font-size-150prc"><a class="display-inline-block" href="$login_link">Login</a></div>
							<div class="padding-top-5 padding-bottom-5 font-size-150prc"><a class="display-inline-block" href="$register_link" title="Create Your User Profile Now!">Register</a></div>
EOT;
		} else {
			//$action_url = $vars['url']['handler']->wrap($vars, '/login-register');
			/* ?>
			  <div>hi,</div>
			  <div><a class="" title="View Your Orders or Update Your Profile Info" href="<?php echo $tpt_vars['url']['handler']->wrap($tpt_vars, '/my-account'); ?>"><?php echo $tpt_vars['user']['data']['fname']; ?></a></div>
			 */
			$content .= <<< EOT
							<div class="padding-top-5 padding-bottom-5 font-size-150prc"><a class="" title="View Your Orders or Update Your Profile Info" href="$my_account_link">My Account</a></div>
EOT;
			/* <div><form method="POST" action="$action_url" accept-charset="utf-8"><input type="hidden" name="task" value="user.logout" /><input class="amz_red plain_input_field text-decoration-underline" type="submit" value="Logout" /></form></div>
			 */
			$content .= <<< EOT
							<div class="padding-top-5 padding-bottom-5 font-size-150prc"><a class="" title="Logout from the system" href="$logout_url">Logout</a></div>
EOT;
		}
		$content .= <<< EOT
						</div>
						<div class="padding-10" style="background: #442A1B none;">
							$minicart
						</div>
						<div class="text-align-center color-white font-size-150prc" style="">
							<a class="amz_checkout_link display-block text-align-center" style="background-color: #D8302D;" href="$shipping_url" title="Place Your Order">
								<span class="display-inline-block padding-left-5 padding-right-5 height-18 line-height-14 font-weight-bold">Checkout</span>
							</a>
						</div>
					</div>
				</div>
EOT;

		return $content;
	}

	static function getFrontendHeader(&$vars) {
		if (empty($vars['environment']['mobile_template'])) {
			return self::getFrontendHeaderDesktop($vars);
		} else if ($vars['environment']['mobile_template'] == 1) {
			return self::getFrontendHeaderMobile($vars);
		} else {
			return self::getFrontendHeaderMobile2($vars);
		}
	}

	static function getFrontendHeaderDesktop(&$vars) {
		//tpt_dump('asdasdsaddas', true);
		/* <div><form method="POST" action="$action_url" accept-charset="utf-8"><input type="hidden" name="task" value="user.logout" /><input class="amz_red plain_input_field text-decoration-underline" type="submit" value="Logout" /></form></div>
		 */

		$tpt_baseurl = BASE_URL;
		$tpt_imagesurl = TPT_IMAGES_URL;

		$home_href = $vars['template']['home_href'];
		$quote_link = $vars['template']['quote_link'];

		$shipping_url = $vars['url']['handler']->wrap($vars, '/shipping-details');

		$contact_link = $vars['url']['handler']->wrap($vars, '/Contact-AmazingWristbands');

		$templates_dir = TPT_TEMPLATES_DIR;
		$fname = !empty($vars['user']['data']['fname']) ? $vars['user']['data']['fname'] : '';

		$account_area = self::getFrontendHeaderCustomerArea($vars);

		$content = <<< EOT
<div class="header-wrapper clearFix clear-both position-relative">
	<div class="float-left">
		<div class="header-row1 clearFix ">
			<div class="float-left margin-top-30 height-84" style="z-index:60000;">
				<a href="$tpt_baseurl" class="position-relative" style=" z-index:999999;">
					 <img src="$tpt_imagesurl/Amazing-Wristbands-logo.png" width="250" height="84" alt="Silicone Wristbands Home"/>
				</a>
			</div>

			<div class="float-left left-contact-info1 padding-left-5 padding-top-12 white-space-nowrap">
				<ul class="margin-0 padding-0">
					<li class="padding-0 margin-0 todayshop-bolditalic font-size-18">Amazingly <span>Quick</span> Production Time!</li>
					<li class="padding-0 margin-0 todayshop-bolditalic font-size-18">Amazingly <span>Fast</span> Delivery!</li>
					<li class="padding-0 margin-0 todayshop-bolditalic font-size-18"><span style="color:#d92929;">Rush</span> Service Available.</li>
					<li class="padding-0 margin-0 todayshop-bolditalic font-size-18">Have Your Order in <span style="color:#d92929;"><a href="/Rush-Order-Wristbands" style="color:inherit;">5-7 Business Days*!</a></span></li>
				</ul>
			</div>

			<div class="float-right text-align-center">
				<div class="clearFix" style="border-radius: 0px 0px 5px 5px; border-color: #D72525; border-style: solid; border-width: 0px 1px 1px 1px;">
					<div class="clearFix padding-bottom-10" style="border-radius: 0px 0px 5px 5px; background: #D72525 none; border-color: #F4E4CA; border-style: solid; border-width: 0px 1px 1px 1px;">
						<div class="call_us_today_left_con" style="overflow:visible;">
							<div class="cutl_item" style="overflow:visible;">Making Magic:</div>
							<div class="cutl_item" style="margin-top:3px;font-size:14px;white-space:nowrap;overflow:visible;">Mon-Fri: 8:30am-9:30pm</div>
							<div class="cutl_item" style="font-size:14px;white-space:nowrap;overflow:visible;">Sat: 10am-4pm</div>
						</div>
						<div class="float-right" style="padding-right: 17px;">
							<div class="magic-call-today float-right">
								<div class="call-today">CALL TODAY</div>
								<div class="call-today-num">(269)</div>
								<div class="call-today-phone">
									<div class="appleLinksWhite">1-800-AMZ-0910</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="height-45 padding-top-6">
					<a href="$contact_link" class="contact_us_new float-right width-236 height-41" style="background-image:url($tpt_imagesurl/layout-elements1.png);">
					</a>
				</div>
			</div>
		</div>


		<div class="header-row2 clearFix">
			<div class="float-left text-align-center width-200 padding-top-33 margin-right-0 margin-bottom-0 margin-left-0">
				<a class="a_home side-bar-home-btn position-relative text-decoration-none width-160 height-39 display-inline-block text-align-center line-height-39 font-size-16 font-weight-bold" style="cursor: pointer; background: #472819 none;border-radius: 8px 8px 0 0;color: #F8ECD4;font-family: Arial, Helvetica, sans-serif;" href="$home_href">HOME</a>
			</div>

			<div class="header-banners  width-692 float-left overflow-hidden margin-right-8" style="margin:0px 0px 0px 8px;">
			   <!-- header banners new section -->
				$quote_link
				<!-- End of header banners -->
			</div>

		</div>


	</div>

	$account_area

</div>
EOT;

		return $content;
	}

	static function getFrontendHeaderMobile(&$vars) {
		//tpt_dump('asdasdsaddas', true);
		/* <div><form method="POST" action="$action_url" accept-charset="utf-8"><input type="hidden" name="task" value="user.logout" /><input class="amz_red plain_input_field text-decoration-underline" type="submit" value="Logout" /></form></div>
		 */

		$tpt_baseurl = BASE_URL;
		$tpt_imagesurl = TPT_IMAGES_URL;

		$home_href = $vars['template']['home_href'];
		$quote_link = $vars['template']['quote_link'];

		$social_bar = $vars['template']['social_bar'];

		$shipping_url = $vars['url']['handler']->wrap($vars, '/shipping-details');

		$contact_link = $vars['url']['handler']->wrap($vars, '/Contact-AmazingWristbands');
		$rush_link = $vars['url']['handler']->wrap($vars, '/Rush-Order-Wristbands');

		$templates_dir = TPT_TEMPLATES_DIR;
		$fname = !empty($vars['user']['data']['fname']) ? $vars['user']['data']['fname'] : '';

		$account_area = self::getFrontendHeaderCustomerArea($vars);
		$feedbackclick = 'setTimeout(function(){$(\'#TB_ajaxContent\').append($(\'<iframe></iframe>\').attr(\'class\',\'feedbackframe\').attr(\'frameborder\',0).attr(\'scrolling\',\'no\').attr(\'src\',\'' . BASE_URL . '/feedback\'))},500)';

		$content = <<< EOT
<div class="header-wrapper clearFix clear-both position-relative">
	<div class="clearFix">
		<div class="width-20prc float-left position-relative">
			<div class="text-align-left padding-5">
				<div class="position-relative">
					<a id="left_bar_button" onclick="
						if(document.getElementById('left_bar_toggle')) {
							if(document.getElementById('left_bar_toggle').className.match(unfoldedClassRegExp)) {
								toggle_navigation(this, 1);
							} else {
								toggle_navigation(this, 2);
							}
						}
						" href="javascript:void(0);" class="display-inline-block width-33 height-32 position-absolute" style="background-image: url($tpt_imagesurl/layout-elements3.png);background-position: -255px 0px;border-radius: 0px;">
					</a>
				</div>
			</div>
		</div>

		<div class="width-30prc float-left" style=" z-index:999999;">
			<div class="padding-5">
				<a href="$tpt_baseurl" class="display-block">
					 <img src="$tpt_imagesurl/Amazing-Wristbands-logo.png" alt="Amazingwristbands Home" class="width-100prc" />
				</a>
			</div>
		</div>

		<div class="float-right width-50prc text-align-center">
			<ul class="margin-0 padding-5 display-inline-block white-space-nowrap text-align-left">
				<li class="padding-0 margin-0 badaboombb amz_green" style="list-style:url($tpt_imagesurl/header-stars-12x11.png) inside;">AMAZINGLY <span class="amz_red">QUICK</span> PRODUCTION TIME!</li>
				<li class="padding-0 margin-0 badaboombb amz_green" style="list-style:url($tpt_imagesurl/header-stars-12x11.png) inside;">AMAZINGLY <span class="amz_red">FAST</span> DELIVERY!</li>
				<li class="padding-0 margin-0 badaboombb amz_green" style="list-style:url($tpt_imagesurl/header-stars-12x11.png) inside;"><span class="amz_red">RUSH</span> SERVICE AVAILABLE.</li>
				<li class="padding-0 margin-0 badaboombb amz_green" style="list-style:url($tpt_imagesurl/header-stars-12x11.png) inside;">HAVE YOUR ORDER IN <a href="$rush_link" class="amz_red">5-7 BUSINESS DAYS*!</a></li>
			</ul>
		</div>
	</div>


	<div class="clearFix">
		<div class="float-left width-65prc">
			<div class="padding-5">
				<div style="border-radius: 5px 5px 0px 0px; border-color: #D72525; border-style: none; border-width: 1px 1px 1px 1px;">
					<div class="clearFix padding-5 font-size-150prc" style="border-radius: 5px 5px 0px 0px; background: #D72525 none; border-color: #F4E4CA; border-style: none; border-width: 1px 1px 1px 1px;">
						<div class="float-left width-50prc text-align-center">
							<div class="display-inline-block text-align-left">
								<div class="color-white todayshop-bolditalic padding-bottom-2">Making Magic:</div>
								<div class="color-white todayshop-bolditalic padding-top-2 padding-bottom-2">Mon-Fri:<br />8:30am-9:30pm</div>
								<div class="color-white todayshop-bolditalic padding-top-2" style="white-space:nowrap;">Sat:<br />10am-4pm</div>
							</div>
						</div>
						<div class="float-right width-50prc text-align-center">
							<div class="display-inline-block text-align-left">
								<div class="padding-left-5">
									<div class="color-white todayshop-bolditalic">CALL TODAY</div>
									<div class="color-white todayshop-bolditalic"></div>
									<div class="color-white todayshop-bolditalic">
										<div class="color-white todayshop-bolditalic"><span class="display-inline-block">1-800-</span><span class="display-inline-block">(269)<br />AMZ</span><span class="display-inline-block">-0910</span></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div>
					<a href="$contact_link" class="display-block text-decoration-none" style="border-radius: 0px 0px 0px 0px; border: 0px none #FFEE9E;">
						<span class="font-size-200prc padding-top-10 padding-bottom-10 text-align-center todayshop-bolditalic amz_red amz_yellow_bg display-block" style="border-radius: 0px; border: 0px none #F4E4CA;text-shadow: 1px 1px 2px rgba(244, 228, 202, 1);">
						CONTACT US
						</span>
					</a>
				</div>
EOT;
		if (false && !defined('TPT_BLOG')) {
			$content .= <<< EOT
				<div id="fdbk">
					<a href="#TB_inline?width=470&amp;height=470&amp;inlineId=_" class="winrf_feedback_frame text-decoration-none text-align-center color-white amz_red_bg thickbox display-block font-size-150prc padding-top-10 padding-bottom-10" style=" border-radius: 0px;">
					SEND US FEEDBACK
					</a>
				</div>
EOT;
		}
		$content .= <<< EOT
			</div>
		</div>

		<div class="width-35prc float-right" style="max-width: 150px;">
			<div class="padding-5">
				$account_area
			</div>

		</div>
	</div>

	<div>
		$social_bar
	</div>

	<div class="padding-left-5 padding-right-5" id="2">
		$quote_link
	</div>
EOT;
		if (!empty($vars['template']['quote_link'])) {
			$content .= <<< EOT
		<div id="fdbk">
			<input onclick="$feedbackclick" id="#TB_inline"  class="winrf_feedback_frame thickbox feedback width-91 height-40 display-inline-block cursor-pointer" style="background: transparent url($tpt_imagesurl/layout-elements2.png) no-repeat scroll 0 0;" type="button" value=" " />
		</div>
EOT;
		}

		$content .= <<< EOT
</div>


EOT;

		return $content;
	}

	static function getFrontendHeaderMobile2(&$vars) {
		//tpt_dump('asdasdsaddas', true);
		/* <div><form method="POST" action="$action_url" accept-charset="utf-8"><input type="hidden" name="task" value="user.logout" /><input class="amz_red plain_input_field text-decoration-underline" type="submit" value="Logout" /></form></div>
		 */

		$tpt_baseurl = BASE_URL;
		$tpt_imagesurl = TPT_IMAGES_URL;

		$home_href = $vars['template']['home_href'];
		$quote_link = $vars['template']['quote_link'];

		$social_bar = $vars['template']['social_bar'];

		$action_url = $vars['url']['handler']->wrap($vars, '/login-register');
		$logout_url = $vars['url']['handler']->wrap($vars, '/logout');
		$shipping_url = $vars['url']['handler']->wrap($vars, '/shipping-details');

		$contact_link = $vars['url']['handler']->wrap($vars, '/Contact-AmazingWristbands');
		$login_link = $vars['url']['handler']->wrap($vars, '/login-register');
		$register_link = $vars['url']['handler']->wrap($vars, '/login-register?register=1');
		$my_account_link = $vars['url']['handler']->wrap($vars, '/my-account');
		$rush_link = $vars['url']['handler']->wrap($vars, '/Rush-Order-Wristbands');

		$templates_dir = TPT_TEMPLATES_DIR;
		$fname = !empty($vars['user']['data']['fname']) ? $vars['user']['data']['fname'] : '';
		$feedbackclick = 'setTimeout(function(){$(\'#TB_ajaxContent\').append($(\'<iframe></iframe>\').attr(\'class\',\'feedbackframe\').attr(\'frameborder\',0).attr(\'scrolling\',\'no\').attr(\'src\',\'' . BASE_URL . '/feedback\'))},500)';

		//$minicart = self::getMinicart($vars);

		$content = <<< EOT
<div class="header-wrapper clearFix clear-both position-relative">
	<div class="clearBoth padding-left-10 padding-right-10">
		<div class="clearBoth">
			<div class="float-left width-15prc text-align-left padding-top-11 padding-bottom-10 position-relative">
				<a id="left_bar_button" onclick="
					if(document.getElementById('left_bar_toggle')) {
						if(document.getElementById('left_bar_toggle').className.match(unfoldedClassRegExp)) {
							toggle_navigation(this, 1);
						} else {
							toggle_navigation(this, 2);
						}
					}
					" href="javascript:void(0);" class="position-absolute display-inline-block width-33 height-32" style="background-image: url($tpt_imagesurl/layout-elements3.png);background-position: -255px 0px;border-radius: 0px;">
				</a>
			</div>
			<div class="float-left width-15prc text-align-center padding-top-11 padding-bottom-10">
				<a onclick="
					if(document.getElementById('search_toggle')) {
						if(document.getElementById('search_toggle').className.match(unfoldedClassRegExp)) {
							toggle_search(this, 1);
						} else {
							toggle_search(this, 2);
						}
					}
				" href="javascript:void(0);" class="display-inline-block width-32 height-32" style="background-image: url($tpt_imagesurl/layout-elements4.png);background-position: -34px -11px;border-radius: 0px;">
				</a>
			</div>
			<div class="float-left width-40prc text-align-center padding-top-11 padding-bottom-10">
				<a href="$tpt_baseurl" class="display-block text-align-center" style="/*background-image: url($tpt_imagesurl/layout-elements4.png);background-position: -66px 0px;border-radius: 0px;*/">
					<img src="$tpt_imagesurl/layout-elements-m-logo.png" alt="Amazingwristbands Home" style="max-width: 100%;" />
				</a>
			</div>
			<div class="float-left width-15prc text-align-center padding-top-11 padding-bottom-10">
				<a onclick="
					if(document.getElementById('account_toggle')) {
						if(document.getElementById('account_toggle').className.match(unfoldedClassRegExp)) {
							toggle_account(this, 1);
						} else {
							toggle_account(this, 2);
						}
					}
				" href="javascript:void(0);" class="display-inline-block width-32 height-32" style="background-image: url($tpt_imagesurl/layout-elements4.png);background-position: -314px -11px;border-radius: 0px;">
				</a>
			</div>
			<div class="float-right width-15prc text-align-right padding-top-11 padding-bottom-10 position-relative">
				<a id="minicart_button" onclick="
					if(document.getElementById('minicart_toggle')) {
						if(document.getElementById('minicart_toggle').className.match(unfoldedClassRegExp)) {
							toggle_minicart(this, 1);
						} else {
							toggle_minicart(this, 2);
						}
					}
				" href="javascript:void(0);" class="position-absolute display-inline-block width-32 height-32 right-0" style="background-image: url($tpt_imagesurl/layout-elements4.png);background-position: -346px -11px;border-radius: 0px;">
				</a>
			</div>
		</div>
	</div>
	<div id="account_toggle" class="accountFolded account_folded overflow-hidden" style="height: 0px;">
		<div class="opacity-0 padding-top-10 padding-bottom-10 padding-left-10 padding-right-10 text-align-center">
EOT;
		if (!$vars['user']['isLogged']) {
			$content .= <<< EOT
							<a class="display-inline-block font-size-150prc" href="$login_link">Login</a>
							&nbsp;
							|
							&nbsp;
							<a class="display-inline-block font-size-150prc" href="$register_link" title="Create Your User Profile Now!">Register</a>
EOT;
		} else {
			//$action_url = $vars['url']['handler']->wrap($vars, '/login-register');
			/* ?>
			  <div>hi,</div>
			  <div><a title="View Your Orders or Update Your Profile Info" href="<?php echo $tpt_vars['url']['handler']->wrap($tpt_vars, '/my-account'); ?>"><?php echo $tpt_vars['user']['data']['fname']; ?></a></div>
			 */
			$content .= <<< EOT
							<a class=" font-size-150prc" title="View Your Orders or Update Your Profile Info" href="$my_account_link">My Account</a>
							&nbsp;
							|
							&nbsp;
EOT;
			/* <div><form method="POST" action="$action_url" accept-charset="utf-8"><input type="hidden" name="task" value="user.logout" /><input class="amz_red plain_input_field text-decoration-underline" type="submit" value="Logout" /></form></div>
			 */
			$content .= <<< EOT
							<a class=" font-size-150prc" title="Logout from the system" href="$logout_url">Logout</a>
EOT;
		}
		$content .= <<< EOT
		</div>
	</div>
	<div id="search_toggle" class="searchFolded search_folded overflow-hidden" style="height: 0px;">
		<div class="opacity-0 padding-top-10 padding-bottom-10 padding-left-10 padding-right-10 text-align-center">
			<input type="text" name="" value="" class="amz_red padding-top-10 padding-bottom-10 padding-left-10 padding-right-10 width-70prc" style="border-radius: 10px; border: 1px solid #d1be9b; background: #f1e8d7 none;" />
		</div>
	</div>
EOT;
		if (!empty($_GET['bannerheader'])) {
			$content .= <<< EOT
	<div class="clearBoth" style="background-color: #09728b;">
		<div class="float-left width-30prc text-align-left" style="max-height: 216px;" >
			<img src="$tpt_imagesurl/layout-elements-m-left.png" alt="" style="max-width: 100%;" />
		</div>
		<div class="float-right width-30prc text-align-right" style = "max-height: 216px;" >
			<img src="$tpt_imagesurl/layout-elements-m-right.png" alt="" style="max-width: 100%;" />
		</div>
		<div class="overflow-hidden text-align-center" style="max-height: 216px;">
			<div class="padding-5" >
				<a href="$tpt_baseurl" class="display-block padding-top-10" >
					<img src="$tpt_imagesurl/Amazing-Wristbands-logo.png" alt="Amazingwristbands Home" style="max-width: 100%;" />
				</a>
			</div>
		</div>
	</div>
EOT;
		} else {
			$content .= <<< EOT
	<div class="clearBoth">
		<img src="$tpt_imagesurl/layout-elements-m-header.png" alt="Amazingwristbands Home" style="width: 100%;" />
	</div>
EOT;
		}

		$content .= <<< EOT
	<div class="padding-left-5 padding-right-5" id="1">
		$quote_link
	</div>
EOT;
		if (!empty($vars['template']['quote_link'])) {
			$content .= <<< EOT
		<div id="fdbk">
			<input onclick="$feedbackclick" id="#TB_inline"  class="winrf_feedback_frame thickbox feedback width-91 height-40 display-inline-block cursor-pointer" style="background: transparent url($tpt_imagesurl/layout-elements2.png) no-repeat scroll 0 0;" type="button" value=" " />
		</div>
EOT;
		}

		$content .= <<< EOT
</div>
EOT;

		return $content;
	}

	static function getFrontendSocialBar(&$vars) {
		//tpt_dump($vars['template_data']);
		if (empty($vars['template_data']['hasSocialBar'])) {
			return '';
		}
		if (empty($vars['environment']['mobile_template'])) {
			return self::getFrontendSocialBarDesktop($vars);
		} else if ($vars['environment']['mobile_template'] == 1) {
			return self::getFrontendSocialBarMobile($vars);
		} else {
			return self::getFrontendSocialBarMobile2($vars);
		}
	}

	static function getFrontendSocialBarDesktop(&$vars) {
		$tpt_imagesurl = TPT_IMAGES_URL;
		$protocol = ($vars['config']['protocol'] == '' ? 'http' : $vars['config']['protocol']);

		$feedbackclick = 'setTimeout(function(){$(\'#TB_ajaxContent\').append($(\'<iframe></iframe>\').attr(\'class\',\'feedbackframe\').attr(\'frameborder\',0).attr(\'scrolling\',\'no\').attr(\'src\',\'' . BASE_URL . '/feedback\'))},500)';

		$content = <<< EOT
<div style="text-align: center;" class="social_media_bar" id="amz_socbar">
	<div class="inlineBlock" style="text-align: center; width: 92px;">
		<div class="social-buttons-con">
			<div class="clearFix">
				<a class="facebook-icon float-left display-block width-44 height-44" style="background-image: url($tpt_imagesurl/layout-elements1.png);" href="$protocol://www.facebook.com/amazingwristbands?filter=1" title="Like Us on Facebook" target="_blank"></a>
				<a class="googleplus-icon float-right display-block width-44 height-44" style="background-image: url($tpt_imagesurl/layout-elements1.png);" href="$protocol://plus.google.com/+Amazingwristbands/posts" title="Follow Us on Google+" target="_blank" rel="publisher"></a>
			</div>
			<div class="clearFix">
				<a class="pintrest-icon float-left display-block width-44 height-44" style="background-image: url($tpt_imagesurl/layout-elements1.png);" href="$protocol://www.pinterest.com/wristbands" title="Follow Us on Pinterest" target="_blank"></a>
				<a class="twitter-icon float-right display-block width-44 height-44" style="background-image: url($tpt_imagesurl/layout-elements1.png);" href="$protocol://twitter.com/AMZG_Wristbands" title="Follow Us on Twitter" target="_blank"></a>
			</div>
			<a class="youtube inlineBlock float-left width-90 height-45" style="background-image: url($tpt_imagesurl/layout-elements1.png);" title="watch us on Youtube" href="https://www.youtube.com/c/AmazingWristbands" target="_blank"></a>
		</div>
	</div>
	<a class="blog-btn width-98 height-84 margin-top-20 left-1 position-relative float-left display-block" style="background-image: url($tpt_imagesurl/layout-elements1.png);" href="https://www.amazingwristbands.com/amazing-silicone-wristbands/" title="Custom Wristbands Blog"></a>

	<div id='fdbk' >
		<input onclick="$feedbackclick" id="#TB_inline?width=470&amp;height=470&amp;inlineId=_"  class="winrf_feedback_frame thickbox feedback width-91 height-40 display-inline-block cursor-pointer" style="background: transparent url($tpt_imagesurl/layout-elements2.png) no-repeat scroll 0 0;" type="button" value=" " />
	</div>
	<div class="sbar_reviews">
	<div style="min-height: 100px; overflow: hidden;" class="shopperapproved_widget sa_rotate sa_vertical sa_count1 sacus_rounded sa_showdate sa_jMY sa_narrow sa_bgBlue sa_colorWhite"></div><script type="text/javascript">var sa_interval = 5000;function saLoadScript(src) { var js = window.document.createElement('script'); js.src = src; js.type = 'text/javascript'; document.getElementsByTagName("head")[0].appendChild(js); } if (typeof(shopper_first) == 'undefined') saLoadScript('//www.shopperapproved.com/widgets/testimonial/3.0/17177-25687420-25140143-25081822-25081822-25651275-25064573.js'); shopper_first = true; </script>
	<div style="text-align:right;"><a href="https://www.shopperapproved.com/reviews/amazingwristbands.com/" rel="nofollow" target="_blank" class="sa_footer"><img class="sa_widget_footer" alt="" src="//www.shopperapproved.com/widgets/widgetfooter-whitelogo.png" style="border: 0;"></a></div>
	</div>
</div>
EOT;

		return $content;
	}

	static function getFrontendSocialBarMobile(&$vars) {

		$tpt_baseurl = BASE_URL;
		$tpt_imagesurl = TPT_IMAGES_URL;
		$protocol = ($vars['config']['protocol'] == '' ? 'http' : $vars['config']['protocol']);

		$content = <<< EOT
<div class="padding-5" id="amz_socbar">
	<div>
		<div>
			<div class="clearFix">
				<div class="float-left width-15prc text-align-center">
					<div class="display-inline-block">
						<a class="display-block width-40 height-40" style="/*max-width: 50px; max-height: 50px; background-size: contain;*/ background-image: url($tpt_imagesurl/layout-elements3.png);background-position: 0px 0px;" href="$protocol://www.facebook.com/amazingwristbands?filter=1" title="Like Us on Facebook" target="_blank"></a>
					</div>
				</div>
				<div class="float-left width-15prc text-align-center">
					<div class="display-inline-block">
						<a class="display-block width-40 height-40" style="/*max-width: 50px; max-height: 50px; background-size: contain;*/ background-image: url($tpt_imagesurl/layout-elements3.png);background-position: -40px 0px;" href="$protocol://plus.google.com/+Amazingwristbands/posts" title="Follow Us on Google+" target="_blank" rel="publisher"></a>
					</div>
				</div>
				<div class="float-left width-15prc text-align-center">
					<div class="display-inline-block">
						<a class="display-block width-40 height-40" style="/*max-width: 50px; max-height: 50px; background-size: contain;*/ background-image: url($tpt_imagesurl/layout-elements3.png);background-position: -80px 0px;" href="$protocol://www.pinterest.com/wristbands" title="Follow Us on Pinterest" target="_blank"></a>
					</div>
				</div>
				<div class="float-left width-15prc text-align-center">
					<div class="display-inline-block">
						<a class="display-block width-40 height-40" style="/*max-width: 50px; max-height: 50px; background-size: contain;*/ background-image: url($tpt_imagesurl/layout-elements3.png);background-position: -120px 0px;" href="$protocol://twitter.com/AMZG_Wristbands" title="Follow Us on Twitter" target="_blank"></a>
					</div>
				</div>
				<div class="float-left width-15prc text-align-center">
					<div class="display-inline-block">
						<a class="display-block width-40 height-40" style="background-image: url($tpt_imagesurl/layout-elements3.png);background-position: -160px 0px;" title="watch us on Youtube" href="https://www.youtube.com/c/AmazingWristbands" target="_blank"></a>
					</div>
				</div>
				<div class="float-left width-25prc text-align-right">
					<div class="display-inline-block">
						<a class="display-block width-55 height-40" style="background-image: url($tpt_imagesurl/layout-elements3.png);background-position: -200px 0px;border-radius: 0px;" href="https://www.amazingwristbands.com/amazing-silicone-wristbands/" title="Custom Wristbands Blog"></a>
					</div>
				</div>
			</div>
		</div>
	</div>

</div>
EOT;

		return $content;
	}

	static function getFrontendSocialBarMobile2(&$vars) {

		$tpt_baseurl = BASE_URL;
		$tpt_imagesurl = TPT_IMAGES_URL;
		$protocol = ($vars['config']['protocol'] == '' ? 'http' : $vars['config']['protocol']);

		$content = <<< EOT
<div class="padding-5" id="amz_socbar">
	<div>
		<div>
			<div class="clearFix">
				<div class="float-left width-15prc text-align-center">
					<div class="display-inline-block">
						<a class="display-block width-40 height-40" style="/*max-width: 50px; max-height: 50px; background-size: contain;*/ background-image: url($tpt_imagesurl/layout-elements3.png);background-position: 0px 0px;" href="$protocol://www.facebook.com/amazingwristbands?filter=1" title="Like Us on Facebook" target="_blank"></a>
					</div>
				</div>
				<div class="float-left width-15prc text-align-center">
					<div class="display-inline-block">
						<a class="display-block width-40 height-40" style="/*max-width: 50px; max-height: 50px; background-size: contain;*/ background-image: url($tpt_imagesurl/layout-elements3.png);background-position: -40px 0px;" href="$protocol://plus.google.com/+Amazingwristbands/posts" title="Follow Us on Google+" target="_blank" rel="publisher"></a>
					</div>
				</div>
				<div class="float-left width-15prc text-align-center">
					<div class="display-inline-block">
						<a class="display-block width-40 height-40" style="/*max-width: 50px; max-height: 50px; background-size: contain;*/ background-image: url($tpt_imagesurl/layout-elements3.png);background-position: -80px 0px;" href="$protocol://www.pinterest.com/wristbands" title="Follow Us on Pinterest" target="_blank"></a>
					</div>
				</div>
				<div class="float-left width-15prc text-align-center">
					<div class="display-inline-block">
						<a class="display-block width-40 height-40" style="/*max-width: 50px; max-height: 50px; background-size: contain;*/ background-image: url($tpt_imagesurl/layout-elements3.png);background-position: -120px 0px;" href="$protocol://twitter.com/AMZG_Wristbands" title="Follow Us on Twitter" target="_blank"></a>
					</div>
				</div>
				<div class="float-left width-15prc text-align-center">
					<div class="display-inline-block">
						<a class="display-block width-40 height-40" style="background-image: url($tpt_imagesurl/layout-elements3.png);background-position: -160px 0px;" title="watch us on Youtube" href="https://www.youtube.com/c/AmazingWristbands" target="_blank"></a>
					</div>
				</div>
				<div class="float-left width-25prc text-align-right">
					<div class="display-inline-block">
						<a class="display-block width-55 height-40" style="background-image: url($tpt_imagesurl/layout-elements3.png);background-position: -200px 0px;border-radius: 0px;" href="https://www.amazingwristbands.com/amazing-silicone-wristbands/" title="Custom Wristbands Blog"></a>
					</div>
				</div>
			</div>
		</div>
	</div>

</div>
EOT;

		return $content;
	}

	static function getFrontendTptTasksHeadContent(&$vars) {
		if ((isDev('cachetags') && !empty($_GET['cachetags'])) || (isDev('rebuildcache') && !empty($_GET['rebuildcache']))) {
			return TPT_TAG_TPTTASKSHEADCONTENT;
		}

		if (isset($vars['data']['tpt_ajax_calls']['task']) && count($vars['data']['tpt_ajax_calls']['task']) > 0) {
			foreach ($vars['data']['tpt_ajax_calls']['task'] as $name => $task) {
				if (!empty($vars['data']['tpt_ajax_calls']['task'][$name]['wait_message'])) {
					$vars['data']['tpt_ajax_calls']['task'][$name]['wait_message_html'] = tpt_Messages::getMessage($vars, $vars['data']['tpt_ajax_calls']['task'][$name]['wait_message'], 'notice');
				}
			}
		}



		$init_ajax = '';
//foreach($tpt_vars['data']['tpt_ajax_calls']['task'] as $task=>$tdata) {
		$init_ajax .= 'var tpt_tasks = JSON.parse(\''.addslashes(json_encode($vars['data']['tpt_ajax_calls']['task'])).'\');';
		$init_ajax .= 'for(var task in tpt_tasks)if(tpt_tasks[task].post_data)tpt_tasks[task].post_data = JSON.parse(tpt_tasks[task].post_data);';
//}
		$dev_console_reposition_offset = $vars['config']['admin']['dev_console_reposition_offset'];
		return <<< EOT
<script type="text/javascript">
//<![CDATA[
var devConsoleRepositionOffset = $dev_console_reposition_offset;
$init_ajax;
//]]>
</script>
EOT;
	}
	static function getBackendTptTasksHeadContent(&$vars) {
		//tpt_dump($vars['data']['tpt_ajax_calls_admin'], true);
		if (isset($vars['data']['tpt_ajax_calls']['task']) && count($vars['data']['tpt_ajax_calls']['task']) > 0) {
			foreach ($vars['data']['tpt_ajax_calls']['task'] as $name => $task) {
				if (!empty($vars['data']['tpt_ajax_calls']['task'][$name]['wait_message'])) {
					$vars['data']['tpt_ajax_calls']['task'][$name]['wait_message_html'] = tpt_Messages::getMessage($vars, $vars['data']['tpt_ajax_calls']['task'][$name]['wait_message'], 'notice');
				}
			}
		}


		$init_ajax = '';
//foreach($vars['data']['tpt_ajax_calls']['task'] as $task=>$tdata) {
		$init_ajax .= 'var tpt_tasks = JSON.parse(\'' . addslashes(json_encode($vars['data']['tpt_ajax_calls_admin']['task'])) . '\');';
		$init_ajax .= 'for(var task in tpt_tasks)if(tpt_tasks[task].post_data)tpt_tasks[task].post_data = JSON.parse(tpt_tasks[task].post_data);';
//}
		$dev_console_reposition_offset = $vars['config']['admin']['dev_console_reposition_offset'];
		return <<< EOT
<script type="text/javascript">
//<![CDATA[
var devConsoleRepositionOffset = $dev_console_reposition_offset;
$init_ajax;
//]]>
</script>
EOT;
	}
	static function getCachedShopperApprovedSchema(&$vars) {
		$db = $vars['db']['handler'];

		$db->prepare('SELECT `data` FROM `'.$vars['config']['db']['database'].'`.`tpt_cache_shopperapprovedschema` ORDER BY `id` DESC LIMIT 1');
		$db->execute();
		$res = $db->fetch();
		return $res['data'];
	}
	static function getFrontendFooter(&$vars) {
		if (empty($vars['environment']['mobile_template'])) {
			return self::getFrontendFooterDesktop($vars);
		} else if ($vars['environment']['mobile_template'] == 1) {
			return self::getFrontendFooterMobile($vars);
		} else {
			return self::getFrontendFooterMobile($vars);
		}
	}

	static function getFrontendFooterDesktop(&$vars) {
		$year = date("Y");
		$tpt_imagesurl = TPT_IMAGES_URL;
		$tpt_baseurl = BASE_URL;

		$content = <<< EOT
<div class="height-20"></div>
	<div class="footer-wrapper border-radius-10" style="background-color: #DDB987; border: 1px solid #D08600;">
		<div class="footer-wrapper-inner">
            <div class="footer-middle text-align-center">
                <div class="footer-content clearFix display-inline-block">
                    <div class="c-cards">
                        <a title="Paypal" class="display-inline-block side-bar-payments-card-paypal"></a>
                        <a title="American Express" class="display-inline-block side-bar-payments-card-americanexpress"></a>
                        <a title="Master Card" class="display-inline-block side-bar-payments-card-mastercard"></a>
                        <a title="Visa" class="display-inline-block side-bar-payments-card-visa"></a>
                        <a title="Discover" class="display-inline-block side-bar-payments-card-discover"></a>
                    </div>
                    <div class="footer-logo one">
                        <div class="two">
                            <a href="$tpt_baseurl/"><img src="$tpt_imagesurl/footer-logo.png" alt="Custom Wristbands Home" width="115" height="75" /></a>
                            <br />
                            <!-- (c) 2005, 2012. Authorize.Net is a registered trademark of CyberSource Corporation -->
                            <div class="AuthorizeNetSeal" style="margin-left:20px; margin-top:4px;"> <script type="text/javascript">var ANS_customer_id="2c313588-2592-4ebc-a4ba-3429bcc0aa91";</script>
EOT;
		//<script type="text/javascript" src="//verify.authorize.net/anetseal/seal.js" ></script>
		$content .= <<< EOT
                    <script type="text/javascript">
                    // (c) 2006. Authorize.Net is a registered trademark of Lightbridge, Inc.
                    var ANSVerificationURL = "https://verify.authorize.net/anetseal/";  // String must start with "//" and end with "/"
                    var AuthorizeNetSeal =
                    {
                        verification_parameters: "",
                        id_parameter_name:       "pid",
                        url_parameter_name:      "rurl",
                        seal_image_file:         (ANSVerificationURL + "images/secure90x72.gif"),
                        seal_width:              "90",
                        seal_height:             "72",
                        seal_alt_text:           "Authorize.Net Merchant - Click to Verify",
                        display_url:             "http://www.authorize.net/",
                        text_color:              "black",
                        text_size:               "9px",
                        line_spacing:            "10px",
                        new_window_height:       "430",
                        new_window_width:        "600",
                        current_url:             "",
                        display_location:        true,
                        no_click:                false,
                        debug:                   false
                    };

                    document.writeln( '<style type="text/css">' );
                    document.writeln( 'div.AuthorizeNetSeal{text-align:center;margin:0;padding:0;width:' + AuthorizeNetSeal.seal_width + 'px;font:normal ' + AuthorizeNetSeal.text_size + ' arial,helvetica,san-serif;line-height:' + AuthorizeNetSeal.line_spacing + ';}' );
                    document.writeln( 'div.AuthorizeNetSeal a{text-decoration:none;color:' + AuthorizeNetSeal.text_color + ';}' );
                    document.writeln( 'div.AuthorizeNetSeal a:visited{color:' + AuthorizeNetSeal.text_color + ';}' );
                    document.writeln( 'div.AuthorizeNetSeal a:active{color:' + AuthorizeNetSeal.text_color + ';}' );
                    document.writeln( 'div.AuthorizeNetSeal a:hover{text-decoration:underline;color:' + AuthorizeNetSeal.text_color + ';}' );
                    document.writeln( 'div.AuthorizeNetSeal a img{border:0px;margin:0px;text-decoration:none;}' );
                    document.writeln( '</style>' );

                    if( window.ANS_customer_id )
                    {
                        AuthorizeNetSeal.verification_parameters = '?' + AuthorizeNetSeal.id_parameter_name + '=' + escape( ANS_customer_id );
                        if( window.location.href )
                        {
                            AuthorizeNetSeal.current_url = window.location.href;
                        }
                        else if( document.URL )
                        {
                            AuthorizeNetSeal.current_url = document.URL;
                        }

                        if( AuthorizeNetSeal.current_url )
                        {
                            AuthorizeNetSeal.verification_parameters += '&' + AuthorizeNetSeal.url_parameter_name + '=' + escape( AuthorizeNetSeal.current_url );
                        }

                        if( !AuthorizeNetSeal.no_click )
                        {
                            document.write( '<a ' );
                            document.write( 'href="' + ANSVerificationURL  + AuthorizeNetSeal.verification_parameters + '" ' );
                            if( !AuthorizeNetSeal.debug )
                            {
                                document.write( 'onMouseOver="window.status=\'' + AuthorizeNetSeal.display_url + '\'; return true;" ' );
                                document.write( 'onMouseOut="window.status=\'\'; return true;" ' );
                                document.write( 'onClick="window.open(\'' + ANSVerificationURL + AuthorizeNetSeal.verification_parameters + '\',\'AuthorizeNetVerification\',\'' );
                                document.write( 'width=' + AuthorizeNetSeal.new_window_width );
                                document.write( ',height=' + AuthorizeNetSeal.new_window_height );
                                document.write( ',dependent=yes,resizable=yes,scrollbars=yes' );
                                document.write( ',menubar=no,toolbar=no,status=no,directories=no' );
                                if( AuthorizeNetSeal.display_location )
                                {
                                    document.write( ',location=yes' );
                                }
                                document.write( '\'); return false;" ' );
                            }
                            document.write( 'target="_blank"' );
                            document.writeln( '>' );
                        }

                        document.writeln( '<img src="' + AuthorizeNetSeal.seal_image_file + '" width="' + AuthorizeNetSeal.seal_width + '" height="' + AuthorizeNetSeal.seal_height + '" border="0" alt="' + AuthorizeNetSeal.seal_alt_text + '">' );

                        if( !AuthorizeNetSeal.no_click )
                        {
                            document.writeln( '</a>' );
                        }
                    }
                    </script>
EOT;
		$schema_code = self::getCachedShopperApprovedSchema($vars);
		if ((isDev('cachetags') && !empty($_GET['cachetags'])) || (isDev('rebuildcache') && !empty($_GET['rebuildcache']))) {
			$schema_code = TPT_TAG_SHOPPERAPPROVEDSCHEMACACHE;
		}
		$content .= <<< EOT
							</div>
                        </div>
                        	<br />
                            <div class="text-align-center">
                            	<img src="https://c813008.ssl.cf2.rackcdn.com/17177-small.png" style="border: 0" alt="Shopper Award" oncontextmenu="var d = new Date(); alert('Copying Prohibited by Law - This image and all included logos are copyrighted by shopperapproved &copy; '+d.getFullYear()+'.'); return false;" /><script type="text/javascript">(function() { var js = window.document.createElement("script"); js.src = '//www.shopperapproved.com/seals/certificate.js'; js.type = "text/javascript"; document.getElementsByTagName("head")[0].appendChild(js); })();</script>
                            	<div class="sasc">
	                                $schema_code
	                            </div>
                            </div>
                    </div>
                    <div class="footer-main-links one">
                        <div class="two">
                            <a href="$tpt_baseurl">WRISTBANDS HOME</a><br />
                            <a href="$tpt_baseurl/about-us">ABOUT US</a><br />
                            <a href="$tpt_baseurl/Contact-Amazingwristbands">CONTACT US</a><br />
                            <a href="$tpt_baseurl/amazing-wristbands-size-guide">WRISTBANDS SIZE GUIDE</a><br />
                            <a href="$tpt_baseurl/amazing-wristbands-faq">WRISTBANDS FAQ</a>
                        </div>
                    </div>
                    <div class="footer-sub-links one">
                        <div class="two">
                            <a href="$tpt_baseurl/Debossed-Wristbands">Debossed Wristbands</a><br />
                            <a href="$tpt_baseurl/Embossed-Wristbands">Embossed Bracelets</a><br />
                            <a href="$tpt_baseurl/Ink-Filled-Debossed-Wristbands">Ink Fill Deboss Wristbands</a><br />
                            <a href="$tpt_baseurl/Colorized-Emboss-Wristbands">Colorized Emboss Bands</a><br />
                            <a href="$tpt_baseurl/Screen-Printed-Wristbands">Screen Printed Wristband</a><br />
                            <a href="$tpt_baseurl/Slap-Bands">Custom Slap Bands</a><br />
                   <a href="$tpt_baseurl/Silicone-Rings">Silicone Rings</a>
                        </div>
                    </div>
                    <div class="footer-sub-links one">
                        <div class="two">
                        <a href="$tpt_baseurl/Dual-Layer-Wristbands">Dual Layer Wristbands</a><br />
                        <a href="$tpt_baseurl/Adjustable-Snap-Bracelets">Adjustable Bracelets</a><br />
                        <a href="$tpt_baseurl/usb-Bands">USB Wristbands</a><br />
                        <a href="$tpt_baseurl/Writable-Wristbands">Writable Wristbands</a><br />
                            <a href="$tpt_baseurl/wristband-articles/">Wristband Articles </a><br />
                            <a href="$tpt_baseurl/amazing-silicone-wristbands/">Blog Updates</a><br />
                            <!--<a href="$tpt_baseurl/Wristbands-Resources">Resources</a><br/>-->
                            <a href="$tpt_baseurl/sitemap">Sitemap</a>
                        </div>
                    </div>
                    <div class="footer-sub-links lastone">
                        <a href="$tpt_baseurl/extras/Glow-in-the-Dark-Bands">Glow in Dark Bands</a><br />
                        <a href="$tpt_baseurl/extras/Glitter-Bands">Glitter Bracelets</a><br />
                        <a href="$tpt_baseurl/extras/Swirled-Wristbands">Swirled Wristbands</a><br />
                        <a href="$tpt_baseurl/Silicone-Key-Chains">Silicone Key Chains</a><br />
                        <a href="$tpt_baseurl/extras/Segmented-Wristbands">Segmented Wristband</a><br /><br />
                        <a href="$tpt_baseurl/policies#terms" style="color:#860101; text-decoration:none;">Terms &amp; conditions</a><br/>
                        <a href="$tpt_baseurl/policies#privacy" style="color:#860101; text-decoration:none;">Privacy statement</a><br/>
                        <a href="$tpt_baseurl/policies#shipping" style="color:#860101; text-decoration:none;">Shipping policy</a>
                    </div>
                </div>
                <div class="footer-copy-write"><strong>Amazing Wristbands</strong> &copy;
                $year - All Rights Reserved
                </div>
            </div>
        </div>
    </div>
<div class="height-10"></div>
EOT;

		return $content;
	}

	static function getFrontendFooterMobile(&$vars) {
		$year = date("Y");
		$tpt_imagesurl = TPT_IMAGES_URL;
		$tpt_baseurl = BASE_URL;
		$content = '';

		$content = <<< EOT
<div class="height-20"></div>
<div class="padding-10">
	<div class="border-radius-10" style="background-color: #DDB987; border: 1px solid #D08600;>
		<div>
			<div class="text-align-center">
				<div class="clearFix display-inline-block line-height-300prc display-flex flex-wrap justify-content-center">
					<div class="clearFix" style="border-bottom: 1px solid #E5C9A3; width: 80%;">
						<div class="float-left width-50prc">
							<div class="padding-10 text-align-left font-weight-bold" style="border-right: 1px solid #E5C9A3;">
								<a class="text-decoration-none" href="$tpt_baseurl" style="color: #f8ecd4;">WRISTBANDS HOME</a><br />
								<a class="text-decoration-none" href="$tpt_baseurl/about-us" style="color: #f8ecd4;">ABOUT US</a><br />
								<a class="text-decoration-none" href="$tpt_baseurl/Contact-Amazingwristbands" style="color: #f8ecd4;">CONTACT US</a><br />
								<a class="text-decoration-none" href="$tpt_baseurl/amazing-wristbands-size-guide" style="color: #f8ecd4;">WRISTBANDS SIZE GUIDE</a><br />
								<a class="text-decoration-none" href="$tpt_baseurl/amazing-wristbands-faq" style="color: #f8ecd4;">WRISTBANDS FAQ</a>
							</div>
						</div>
						<div class="float-left width-50prc">
							<div class="padding-10 text-align-left" style="border-left: 1px solid #CCA570;">
								<a class="text-decoration-none" href="$tpt_baseurl/Debossed-Wristbands" style="color: #5b3824;">Debossed Wristbands</a><br />
								<a class="text-decoration-none" href="$tpt_baseurl/Embossed-Wristbands" style="color: #5b3824;">Embossed Bracelets</a><br />
								<a class="text-decoration-none" href="$tpt_baseurl/Ink-Filled-Debossed-Wristbands" style="color: #5b3824;">Ink Fill Deboss Wristbands</a><br />
								<a class="text-decoration-none" href="$tpt_baseurl/Colorized-Emboss-Wristbands" style="color: #5b3824;">Colorized Emboss Bands</a><br />
								<a class="text-decoration-none" href="$tpt_baseurl/Screen-Printed-Wristbands" style="color: #5b3824;">Screen Printed Wristband</a><br />
								<a class="text-decoration-none" href="$tpt_baseurl/Slap-Bands" style="color: #5b3824;">Custom Slap Bands</a><br />
								<a class="text-decoration-none" href="$tpt_baseurl/Silicone-Rings" style="color: #5b3824;">Silicone Rings</a>

							</div>
						</div>
					</div>
					<div class="clearFix" style="width: 80%;">
						<div class="float-left width-50prc" style="border-top: 1px solid #CCA570;">
							<div class="padding-10 text-align-left" style="border-right: 1px solid #E5C9A3;">
							<a class="text-decoration-none" style="color: #5b3824;" href="$tpt_baseurl/Dual-Layer-Wristbands">Dual Layer Wristbands</a><br />
							<a class="text-decoration-none" href="$tpt_baseurl/Adjustable-Snap-Bracelets" style="color: #5b3824;">Adjustable Bracelets</a><br />
								<a class="text-decoration-none" href="$tpt_baseurl/usb-Bands" style="color: #5b3824;">USB Wristbands</a><br />
								<a class="text-decoration-none" href="$tpt_baseurl/Writable-Wristbands" style="color: #5b3824;">Writable Wristbands</a><br/>
								<a class="text-decoration-none" href="$tpt_baseurl/wristband-articles/" style="color: #5b3824;">Wristband Articles</a><br />
								<a class="text-decoration-none" href="$tpt_baseurl/amazing-silicone-wristbands/" style="color: #5b3824;">Blog Updates</a><br />
								<a class="text-decoration-none" href="$tpt_baseurl/sitemap" style="color: #5b3824;">Sitemap</a>
							</div>
						</div>
						<div class="float-left width-50prc" style="border-top: 1px solid #CCA570;">
							<div class="padding-10 text-align-left" style="border-left: 1px solid #CCA570;">
								<a class="text-decoration-none" href="$tpt_baseurl/extras/Glow-in-the-Dark-Bands" style="color: #5b3824;">Glow in Dark Bands</a><br />
								<a class="text-decoration-none" href="$tpt_baseurl/extras/Glitter-Bands" style="color: #5b3824;">Glitter Bracelets</a><br />
								<a class="text-decoration-none" href="$tpt_baseurl/extras/Swirled-Wristbands" style="color: #5b3824;">Swirled Wristbands</a><br />
								<a class="text-decoration-none" href="$tpt_baseurl/Silicone-Key-Chains" style="color: #5b3824;">Silicone Key Chains</a><br />
								<a class="text-decoration-none" href="$tpt_baseurl/extras/Segmented-Wristbands" style="color: #5b3824;">Segmented Wristbands</a><br /><br />
								<a class="text-decoration-none" href="$tpt_baseurl/policies#terms" style="color:#860101; text-decoration:none;">Terms &amp; conditions</a><br/>
								<a class="text-decoration-none" href="$tpt_baseurl/policies#privacy" style="color:#860101; text-decoration:none;">Privacy statement</a><br/>
								<a class="text-decoration-none" href="$tpt_baseurl/policies#shipping" style="color:#860101; text-decoration:none;">Shipping policy</a>
							</div>
						</div>
					</div>
					<div class="clearFix">
						<div class="float-left width-50prc">
							<div class="padding-10 text-align-left display-flex flex-wrap justify-content-center" style="border-right: 1px solid #E5C9A3;">
								<a class="text-decoration-none" href="$tpt_baseurl/"><img src="$tpt_imagesurl/footer-logo.png" border="0" alt="Amazing Wristbands" width="115" height="75" /></a>
								<br />
								<br />
								<!-- (c) 2005, 2012. Authorize.Net is a registered trademark of CyberSource Corporation -->
								<div class="AuthorizeNetSeal" style="margin-left:10px; margin-top:4px;"> <script type="text/javascript">var ANS_customer_id="2c313588-2592-4ebc-a4ba-3429bcc0aa91";</script>
EOT;
		//<script type="text/javascript" src="//verify.authorize.net/anetseal/seal.js" ></script>
		$schema_code = self::getCachedShopperApprovedSchema($vars);
		if ((isDev('cachetags') && !empty($_GET['cachetags'])) || (isDev('rebuildcache') && !empty($_GET['rebuildcache']))) {
			$schema_code = TPT_TAG_SHOPPERAPPROVEDSCHEMACACHE;
		}
		$content .= <<< EOT
<script type="text/javascript">
// (c) 2006. Authorize.Net is a registered trademark of Lightbridge, Inc.
var ANSVerificationURL = "https://verify.authorize.net/anetseal/";  // String must start with "//" and end with "/"
var AuthorizeNetSeal =
{
	verification_parameters: "",
	id_parameter_name:       "pid",
	url_parameter_name:      "rurl",
	seal_image_file:         (ANSVerificationURL + "images/secure90x72.gif"),
	seal_width:              "90",
	seal_height:             "72",
	seal_alt_text:           "Authorize.Net Merchant - Click to Verify",
	display_url:             "http://www.authorize.net/",
	text_color:              "black",
	text_size:               "9px",
	line_spacing:            "10px",
	new_window_height:       "430",
	new_window_width:        "600",
	current_url:             "",
	display_location:        true,
	no_click:                false,
	debug:                   false
};

document.writeln( '<style type="text/css">' );
document.writeln( 'div.AuthorizeNetSeal{text-align:center;margin:0;padding:0;width:' + AuthorizeNetSeal.seal_width + 'px;font:normal ' + AuthorizeNetSeal.text_size + ' arial,helvetica,san-serif;line-height:' + AuthorizeNetSeal.line_spacing + ';}' );
document.writeln( 'div.AuthorizeNetSeal a{text-decoration:none;color:' + AuthorizeNetSeal.text_color + ';}' );
document.writeln( 'div.AuthorizeNetSeal a:visited{color:' + AuthorizeNetSeal.text_color + ';}' );
document.writeln( 'div.AuthorizeNetSeal a:active{color:' + AuthorizeNetSeal.text_color + ';}' );
document.writeln( 'div.AuthorizeNetSeal a:hover{text-decoration:underline;color:' + AuthorizeNetSeal.text_color + ';}' );
document.writeln( 'div.AuthorizeNetSeal a img{border:0px;margin:0px;text-decoration:none;}' );
document.writeln( '</style>' );

if( window.ANS_customer_id )
{
	AuthorizeNetSeal.verification_parameters = '?' + AuthorizeNetSeal.id_parameter_name + '=' + escape( ANS_customer_id );
	if( window.location.href )
	{
		AuthorizeNetSeal.current_url = window.location.href;
	}
	else if( document.URL )
	{
		AuthorizeNetSeal.current_url = document.URL;
	}

	if( AuthorizeNetSeal.current_url )
	{
		AuthorizeNetSeal.verification_parameters += '&' + AuthorizeNetSeal.url_parameter_name + '=' + escape( AuthorizeNetSeal.current_url );
	}

	if( !AuthorizeNetSeal.no_click )
	{
		document.write( '<a ' );
		document.write( 'href="' + ANSVerificationURL  + AuthorizeNetSeal.verification_parameters + '" ' );
		if( !AuthorizeNetSeal.debug )
		{
			document.write( 'onMouseOver="window.status=\'' + AuthorizeNetSeal.display_url + '\'; return true;" ' );
			document.write( 'onMouseOut="window.status=\'\'; return true;" ' );
			document.write( 'onClick="window.open(\'' + ANSVerificationURL + AuthorizeNetSeal.verification_parameters + '\',\'AuthorizeNetVerification\',\'' );
			document.write( 'width=' + AuthorizeNetSeal.new_window_width );
			document.write( ',height=' + AuthorizeNetSeal.new_window_height );
			document.write( ',dependent=yes,resizable=yes,scrollbars=yes' );
			document.write( ',menubar=no,toolbar=no,status=no,directories=no' );
			if( AuthorizeNetSeal.display_location )
			{
				document.write( ',location=yes' );
			}
			document.write( '\'); return false;" ' );
		}
		document.write( 'target="_blank"' );
		document.writeln( '>' );
	}

	document.writeln( '<img src="' + AuthorizeNetSeal.seal_image_file + '" width="' + AuthorizeNetSeal.seal_width + '" height="' + AuthorizeNetSeal.seal_height + '" border="0" alt="' + AuthorizeNetSeal.seal_alt_text + '">' );

	if( !AuthorizeNetSeal.no_click )
	{
		document.writeln( '</a>' );
	}
}
</script>
								</div>
							</div>
						</div>
						<div class="float-left width-50prc text-align-left">
							<div class="display-inline-block padding-10 text-align-left" style="border-left: 1px solid #CCA570;">
								<a title="Paypal" class="text-decoration-none display-inline-block width-47 height-30" style="background: url($tpt_imagesurl/card-paypal.jpg) no-repeat scroll 0 0;"></a>
								<a title="American Express" class="text-decoration-none display-inline-block width-47 height-30" style="background: url($tpt_imagesurl/card-americanexpress.jpg) no-repeat scroll 0 0;"></a>
								<a title="Master Card" class="text-decoration-none display-inline-block width-47 height-30" style="background: url($tpt_imagesurl/card-mastercard.jpg) no-repeat scroll 0 0;"></a>
								<a title="Visa" class="text-decoration-none display-inline-block width-47 height-30" style="background: url($tpt_imagesurl/card-visa.jpg) no-repeat scroll 0 0;"></a>
								<a title="Discover" class="text-decoration-none display-inline-block width-47 height-30" style="background: url($tpt_imagesurl/card-discover.jpg) no-repeat scroll 0 0;"></a>
								<div class="text-align-left" style="padding-top: 10px;">
									<a href="https://www.shopperapproved.com/reviews/amazingwristbands.com/" class="shopperlink"><img src="https://c813008.ssl.cf2.rackcdn.com/17177-small.png" style="border: 0" alt="Shopper Award" oncontextmenu="var d = new Date(); alert('Copying Prohibited by Law - This image and all included logos are copyrighted by shopperapproved &copy; '+d.getFullYear()+'.'); return false;" /></a><script type="text/javascript">(function() { var js = window.document.createElement("script"); js.src = '//www.shopperapproved.com/seals/certificate.js'; js.type = "text/javascript"; document.getElementsByTagName("head")[0].appendChild(js); })();</script>
									<div class="sasc" style="line-height: normal;">
										$schema_code
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="footer-copy-write padding-top-10 padding-bottom-10 text-align-center"><strong>Amazing Wristbands</strong> &copy;
				$year - All Rights Reserved
				</div>
			</div>
		</div>
	</div>
</div>
<div></div>
EOT;

		return $content;
	}

	static function getFrontendFooterMobile2(&$vars) {
		$year = date("Y");
		$tpt_imagesurl = TPT_IMAGES_URL;
		$tpt_baseurl = BASE_URL;
		$content = '';

		$content = <<< EOT
<div class="height-20"></div>
<div class="padding-10">
	<div class="border-radius-10" style="background-color: #DDB987; border: 1px solid #D08600;">
		<div>
			<div class="text-align-center">
				<div class="clearFix display-inline-block line-height-300prc">
					<div class="clearFix" style="border-bottom: 1px solid #E5C9A3; width: 80%;">
						<div class="float-left width-50prc">
							<div class="padding-10 text-align-left font-weight-bold" style="border-right: 1px solid #E5C9A3;">
								<a class="text-decoration-none" href="$tpt_baseurl" style="color: #f8ecd4;">WRISTBANDS HOME</a><br />
								<a class="text-decoration-none" href="$tpt_baseurl/about-us" style="color: #f8ecd4;">ABOUT US</a><br />
								<a class="text-decoration-none" href="$tpt_baseurl/Contact-Amazingwristbands" style="color: #f8ecd4;">CONTACT US</a><br />
								<a class="text-decoration-none" href="$tpt_baseurl/amazing-wristbands-size-guide" style="color: #f8ecd4;">WRISTBANDS SIZE GUIDE</a><br />
								<a class="text-decoration-none" href="$tpt_baseurl/amazing-wristbands-faq" style="color: #f8ecd4;">WRISTBANDS FAQ</a>
							</div>
						</div>
						<div class="float-left width-50prc">
							<div class="padding-10 text-align-left" style="border-left: 1px solid #CCA570;">
								<a class="text-decoration-none" href="$tpt_baseurl/Debossed-Wristbands" style="color: #5b3824;">Debossed Wristbands</a><br />
								<a class="text-decoration-none" href="$tpt_baseurl/Embossed-Wristbands" style="color: #5b3824;">Embossed Bracelets</a><br />
								<a class="text-decoration-none" href="$tpt_baseurl/Ink-Filled-Debossed-Wristbands" style="color: #5b3824;">Ink Fill Deboss Wristbands</a><br />
								<a class="text-decoration-none" href="$tpt_baseurl/Colorized-Emboss-Wristbands" style="color: #5b3824;">Colorized Emboss Bands</a><br />
								<a class="text-decoration-none" href="$tpt_baseurl/Screen-Printed-Wristbands" style="color: #5b3824;">Screen Printed Wristband</a><br />
								<a class="text-decoration-none" href="$tpt_baseurl/Slap-Bands" style="color: #5b3824;">Custom Slap Bands</a><br />
								<a class="text-decoration-none" href="$tpt_baseurl/Silicone-Rings" style="color: #5b3824;">Silicone Rings</a>
							</div>
						</div>
					</div>
					<div class="clearFix" style="border-bottom: 1px solid #E5C9A3; width: 80%;">
						<div class="float-left width-50prc" style="border-top: 1px solid #CCA570;">
							<div class="padding-10 text-align-left" style="border-right: 1px solid #E5C9A3;">
								<a class="text-decoration-none" style="color: #5b3824;" href="$tpt_baseurl/Dual-Layer-Wristbands">Dual Layer Wristbands</a><br />
							<a class="text-decoration-none" href="$tpt_baseurl/Adjustable-Snap-Bracelets" style="color: #5b3824;">Adjustable Bracelets</a><br />
								<a class="text-decoration-none" href="$tpt_baseurl/usb-Bands" style="color: #5b3824;">USB Wristbands</a><br />
								<a class="text-decoration-none" href="$tpt_baseurl/Writable-Wristbands" style="color: #5b3824;">Writable Wristbands</a><br/>
								<a class="text-decoration-none" href="$tpt_baseurl/wristband-articles/" style="color: #5b3824;">Wristband Articles</a><br />
								<a class="text-decoration-none" href="$tpt_baseurl/amazing-silicone-wristbands/" style="color: #5b3824;">Blog Updates</a><br />
							</div>
						</div>
						<div class="float-left width-50prc" style="border-top: 1px solid #CCA570;">
							<div class="padding-10 text-align-left" style="border-left: 1px solid #CCA570;">
								<a class="text-decoration-none" href="$tpt_baseurl/extras/Glow-in-the-Dark-Bands" style="color: #5b3824;">Glow in Dark Bands</a><br />
								<a class="text-decoration-none" href="$tpt_baseurl/extras/Glitter-Bands" style="color: #5b3824;">Glitter Bracelets</a><br />
								<a class="text-decoration-none" href="$tpt_baseurl/extras/Swirled-Wristbands" style="color: #5b3824;">Swirled Wristbands</a><br />
								<a class="text-decoration-none" href="$tpt_baseurl/Silicone-Key-Chains" style="color: #5b3824;">Silicone Key Chains</a><br />
								<a class="text-decoration-none" href="$tpt_baseurl/extras/Segmented-Wristbands" style="color: #5b3824;">Segmented Wristbands</a><br /><br />
								<a class="text-decoration-none" href="$tpt_baseurl/policies#terms" style="color:#860101; text-decoration:none;">Terms &amp; conditions</a><br/>
								<a class="text-decoration-none" href="$tpt_baseurl/policies#privacy" style="color:#860101; text-decoration:none;">Privacy statement</a><br/>
								<a class="text-decoration-none" href="$tpt_baseurl/policies#shipping" style="color:#860101; text-decoration:none;">Shipping policy</a>
							</div>
						</div>
					</div>
					<div class="clearFix" style="border-bottom: 1px solid #E5C9A3;">
						<div class="float-left width-50prc" style="border-top: 1px solid #CCA570;">
							<div class="padding-10 text-align-left" style="border-right: 1px solid #E5C9A3;">
								<a class="text-decoration-none" href="$tpt_baseurl/"><img src="$tpt_imagesurl/footer-logo.png" border="0" alt="Amazing Wristbands" width="115" height="75" /></a>
								<br />
								<br />
								<!-- (c) 2005, 2012. Authorize.Net is a registered trademark of CyberSource Corporation -->
								<div class="AuthorizeNetSeal" style="margin-left:10px; margin-top:4px;"> <script type="text/javascript">var ANS_customer_id="2c313588-2592-4ebc-a4ba-3429bcc0aa91";</script>
EOT;
		//<script type="text/javascript" src="//verify.authorize.net/anetseal/seal.js" ></script>
		$schema_code = self::getCachedShopperApprovedSchema($vars);
		if ((isDev('cachetags') && !empty($_GET['cachetags'])) || (isDev('rebuildcache') && !empty($_GET['rebuildcache']))) {
			$schema_code = TPT_TAG_SHOPPERAPPROVEDSCHEMACACHE;
		}
		$content .= <<< EOT
<script type="text/javascript">
// (c) 2006. Authorize.Net is a registered trademark of Lightbridge, Inc.
var ANSVerificationURL = "https://verify.authorize.net/anetseal/";  // String must start with "//" and end with "/"
var AuthorizeNetSeal =
{
	verification_parameters: "",
	id_parameter_name:       "pid",
	url_parameter_name:      "rurl",
	seal_image_file:         (ANSVerificationURL + "images/secure90x72.gif"),
	seal_width:              "90",
	seal_height:             "72",
	seal_alt_text:           "Authorize.Net Merchant - Click to Verify",
	display_url:             "http://www.authorize.net/",
	text_color:              "black",
	text_size:               "9px",
	line_spacing:            "10px",
	new_window_height:       "430",
	new_window_width:        "600",
	current_url:             "",
	display_location:        true,
	no_click:                false,
	debug:                   false
};

document.writeln( '<style type="text/css">' );
document.writeln( 'div.AuthorizeNetSeal{text-align:center;margin:0;padding:0;width:' + AuthorizeNetSeal.seal_width + 'px;font:normal ' + AuthorizeNetSeal.text_size + ' arial,helvetica,san-serif;line-height:' + AuthorizeNetSeal.line_spacing + ';}' );
document.writeln( 'div.AuthorizeNetSeal a{text-decoration:none;color:' + AuthorizeNetSeal.text_color + ';}' );
document.writeln( 'div.AuthorizeNetSeal a:visited{color:' + AuthorizeNetSeal.text_color + ';}' );
document.writeln( 'div.AuthorizeNetSeal a:active{color:' + AuthorizeNetSeal.text_color + ';}' );
document.writeln( 'div.AuthorizeNetSeal a:hover{text-decoration:underline;color:' + AuthorizeNetSeal.text_color + ';}' );
document.writeln( 'div.AuthorizeNetSeal a img{border:0px;margin:0px;text-decoration:none;}' );
document.writeln( '</style>' );

if( window.ANS_customer_id )
{
	AuthorizeNetSeal.verification_parameters = '?' + AuthorizeNetSeal.id_parameter_name + '=' + escape( ANS_customer_id );
	if( window.location.href )
	{
		AuthorizeNetSeal.current_url = window.location.href;
	}
	else if( document.URL )
	{
		AuthorizeNetSeal.current_url = document.URL;
	}

	if( AuthorizeNetSeal.current_url )
	{
		AuthorizeNetSeal.verification_parameters += '&' + AuthorizeNetSeal.url_parameter_name + '=' + escape( AuthorizeNetSeal.current_url );
	}

	if( !AuthorizeNetSeal.no_click )
	{
		document.write( '<a ' );
		document.write( 'href="' + ANSVerificationURL  + AuthorizeNetSeal.verification_parameters + '" ' );
		if( !AuthorizeNetSeal.debug )
		{
			document.write( 'onMouseOver="window.status=\'' + AuthorizeNetSeal.display_url + '\'; return true;" ' );
			document.write( 'onMouseOut="window.status=\'\'; return true;" ' );
			document.write( 'onClick="window.open(\'' + ANSVerificationURL + AuthorizeNetSeal.verification_parameters + '\',\'AuthorizeNetVerification\',\'' );
			document.write( 'width=' + AuthorizeNetSeal.new_window_width );
			document.write( ',height=' + AuthorizeNetSeal.new_window_height );
			document.write( ',dependent=yes,resizable=yes,scrollbars=yes' );
			document.write( ',menubar=no,toolbar=no,status=no,directories=no' );
			if( AuthorizeNetSeal.display_location )
			{
				document.write( ',location=yes' );
			}
			document.write( '\'); return false;" ' );
		}
		document.write( 'target="_blank"' );
		document.writeln( '>' );
	}

	document.writeln( '<img src="' + AuthorizeNetSeal.seal_image_file + '" width="' + AuthorizeNetSeal.seal_width + '" height="' + AuthorizeNetSeal.seal_height + '" border="0" alt="' + AuthorizeNetSeal.seal_alt_text + '">' );

	if( !AuthorizeNetSeal.no_click )
	{
		document.writeln( '</a>' );
	}
}
</script>
								</div>
							</div>
						</div>
						<div class="float-left width-50prc text-align-left">
							<div class="display-inline-block padding-10 text-align-left" style="border-left: 1px solid #CCA570;">
								<a title="Paypal" class="text-decoration-none display-inline-block width-47 height-30" style="background: url($tpt_imagesurl/card-paypal.jpg) no-repeat scroll 0 0;"></a>
								<a title="American Express" class="text-decoration-none display-inline-block width-47 height-30" style="background: url($tpt_imagesurl/card-americanexpress.jpg) no-repeat scroll 0 0;"></a>
								<a title="Master Card" class="text-decoration-none display-inline-block width-47 height-30" style="background: url($tpt_imagesurl/card-mastercard.jpg) no-repeat scroll 0 0;"></a>
								<a title="Visa" class="text-decoration-none display-inline-block width-47 height-30" style="background: url($tpt_imagesurl/card-visa.jpg) no-repeat scroll 0 0;"></a>
								<a title="Discover" class="text-decoration-none display-inline-block width-47 height-30" style="background: url($tpt_imagesurl/card-discover.jpg) no-repeat scroll 0 0;"></a>
							</div>
						</div>
					</div>
				</div>
				<div class="text-align-center" style="padding-top: 10px;">
					<a href="https://www.shopperapproved.com/reviews/amazingwristbands.com/" class="shopperlink"><img src="https://c813008.ssl.cf2.rackcdn.com/17177-small.png" style="border: 0" alt="Shopper Award" oncontextmenu="var d = new Date(); alert('Copying Prohibited by Law - This image and all included logos are copyrighted by shopperapproved &copy; '+d.getFullYear()+'.'); return false;" /></a><script type="text/javascript">(function() { var js = window.document.createElement("script"); js.src = '//www.shopperapproved.com/seals/certificate.js'; js.type = "text/javascript"; document.getElementsByTagName("head")[0].appendChild(js); })();</script>
					<div class="sasc" style="line-height: normal;">
						$schema_code
					</div>
				</div>
				<div class="footer-copy-write padding-bottom-10"><strong>Amazing Wristbands</strong> &copy;
				$year - All Rights Reserved
				</div>
			</div>
		</div>
	</div>
</div>
<div></div>
EOT;

		return $content;
	}

	static function rebuildCache(&$vars, $content, $column) {
		if ((isDev('rebuildcache') && !empty($_GET['rebuildcache']))) {
			$db = $vars['db']['handler'];

			$purl = tpt_parse_url($vars['config']['requesturl']);
			//tpt_dump($vars['config']['requesturl']);
			$purl = remove_url_query_parameter($purl, 'rebuildcache');
			$purl = remove_url_query_parameter($purl, 'mobiletest');
			//tpt_dump($url);
			//http_parse_params();
			$url = tpt_build_url($purl);
			$url_id = intval($vars['environment']['page_rule']['id'], 10);

			$columns = $db->show_columns('tpt_cache_pre');
			$vals = array(
				'url' => $url,
				$column => $content,
				'urlrule_id' => $url_id
			);
			$cache = $db->getData($vars, 'tpt_cache_pre', '*', '`url`="' . mysql_real_escape_string($url) . '"', 'id', false);

			//tpt_dump($cache);
			if (!empty($cache)) {
				$cache = reset($cache);
				$iid = $cache['id'];
				//tpt_dump($columns);
				//tpt_dump($columns, true);
				//tpt_dump($vals, true);
				//tpt_die();
				$db->updateData($vars, 'tpt_cache_pre', $columns, $vals, ' `id`=' . $iid);
			} else {
				//tpt_dump($columns);
				//tpt_dump($columns, true);
				//tpt_dump($vals, true);
				//tpt_die();
				$db->insertData($vars, 'tpt_cache_pre', $columns, $vals);
			}
		}
	}

	static function rebuildHeadContent(&$vars) {
		$allcss = $vars['db']['handler']->getData($vars, 'tpt_html_css', '*', '`enabled`=1 AND `file`=1', 'id', false);
		//tpt_dump($allcss, true);
		foreach ($allcss as $id => $css) {
			$c = mysql_real_escape_string(file_get_contents($css['path']));
			$query = <<< EOT
UPDATE `tpt_html_css` SET `content`="$c" WHERE `id`=$id
EOT;
			$vars['db']['handler']->query($query);
		}






		$css = array();
		$c_css = array();

		$css['core'] = $vars['db']['handler']->getData($vars, 'tpt_html_css', '*', '`enabled`=1 AND `core`=1 AND `mobile`=-1 ORDER BY `id`', 'id', false);
		$c_css['core'] = '';
		foreach ($css['core'] as $id => $c) {
			$c_css['core'] .= $c['content'] . "\n";
		}
		if(!empty($c_css['core'])) {
			file_put_contents(TPT_CSS_DIR . DIRECTORY_SEPARATOR . 'all_unpack.css', $c_css['core']);
			//$c_css['core'] = preg_replace('/(\{|\}|;)[\s]+([a-zA-Z]|\{|\}|;)/', '$1$2', $c_css['core']);
			$c_css['core'] = tpt_html::sanitize_css_output($c_css['core']);
			//tpt_dump($c_css['core'], true);
			file_put_contents(TPT_CSS_DIR . DIRECTORY_SEPARATOR . 'all.css', $c_css['core']);
		}

		$css['frontend'] = $vars['db']['handler']->getData($vars, 'tpt_html_css', '*', '`enabled`=1 AND `frontend`=1 AND `mobile`=-1 ORDER BY `id`', 'id', false);
		$c_css['frontend'] = '';
		foreach ($css['frontend'] as $id => $c) {
			$c_css['frontend'] .= $c['content'] . "\n";
		}
		if(!empty($c_css['frontend'])) {
			file_put_contents(TPT_CSS_DIR . DIRECTORY_SEPARATOR . 'all1_unpack.css', $c_css['frontend']);
			//$c_css['frontend'] = preg_replace('/(\{|\}|;)[\s]+([a-zA-Z]|\{|\}|;)/', '$1$2', $c_css['frontend']);
			$c_css['frontend'] = tpt_html::sanitize_css_output($c_css['frontend']);
			file_put_contents(TPT_CSS_DIR . DIRECTORY_SEPARATOR . 'all1.css', $c_css['frontend']);
		}

		$css['page'] = $vars['db']['handler']->getData($vars, 'tpt_html_css', '*', '`enabled`=1 AND `url_id`!=0 AND (`common_id` IS NULL OR `common_id`="") ORDER BY `id`', 'url_id', true);
		//tpt_dump($css['page'], true);
		if (is_array($css['page'])) {
			foreach ($css['page'] as $url_id => $c1) {
				$c_css['page'] = '';
				foreach ($c1 as $c) {
					$c_css['page'] .= $c['content'] . "\n";
				}

				if(!empty($c_css['page'])) {
					file_put_contents(TPT_CSS_DIR . DIRECTORY_SEPARATOR . 'all_' . $url_id . '_unpack.css', $c_css['page']);
					//$c_css['page'] = preg_replace('/(\{|\}|;)[\s]+([a-zA-Z]|\{|\}|;)/', '$1$2', $c_css['page']);
					$c_css['page'] = tpt_html::sanitize_css_output($c_css['page']);
					file_put_contents(TPT_CSS_DIR . DIRECTORY_SEPARATOR . 'all_' . $url_id . '.css', $c_css['page']);
				}
			}
		}


		$css['app'] = $vars['db']['handler']->getData($vars, 'tpt_html_css', '*', '`enabled`=1 AND `common_id`>"" GROUP BY `url` ORDER BY `id`', 'common_id', true);
		//tpt_dump($css['app'], true);
		if (is_array($css['app'])) {
			foreach ($css['app'] as $common_id => $c1) {
				$c_css['app'] = '';
				foreach ($c1 as $c) {
					$c_css['app'] .= $c['content'] . "\n";
				}

				if(!empty($c_css['app'])) {
					file_put_contents(TPT_CSS_DIR . DIRECTORY_SEPARATOR . 'all_' . $common_id . '_unpack.css', $c_css['app']);
					//$c_css['page'] = preg_replace('/(\{|\}|;)[\s]+([a-zA-Z]|\{|\}|;)/', '$1$2', $c_css['page']);
					$c_css['app'] = tpt_html::sanitize_css_output($c_css['app']);
					file_put_contents(TPT_CSS_DIR . DIRECTORY_SEPARATOR . 'all_' . $common_id . '.css', $c_css['app']);
				}
			}
		}





		$css['core_nomobile'] = $vars['db']['handler']->getData($vars, 'tpt_html_css', '*', '`enabled`=1 AND `core`=1 AND `mobile`=0 ORDER BY `id`', 'id', false);
		$c_css['core_nomobile'] = '';
		foreach ($css['core_nomobile'] as $id => $c) {
			$c_css['core_nomobile'] .= $c['content'] . "\n";
		}
		if(!empty($c_css['core_nomobile'])) {
			file_put_contents(TPT_CSS_DIR . DIRECTORY_SEPARATOR . 'all_nm_unpack.css', $c_css['core_nomobile']);
			//$c_css['core'] = preg_replace('/(\{|\}|;)[\s]+([a-zA-Z]|\{|\}|;)/', '$1$2', $c_css['core']);
			$c_css['core_nomobile'] = tpt_html::sanitize_css_output($c_css['core_nomobile']);
			//tpt_dump($c_css['core'], true);
			file_put_contents(TPT_CSS_DIR . DIRECTORY_SEPARATOR . 'all_nm.css', $c_css['core_nomobile']);
		}

		$css['frontend_nomobile'] = $vars['db']['handler']->getData($vars, 'tpt_html_css', '*', '`enabled`=1 AND `frontend`=1 AND `mobile`=0 ORDER BY `id`', 'id', false);
		$c_css['frontend_nomobile'] = '';
		foreach ($css['frontend_nomobile'] as $id => $c) {
			$c_css['frontend_nomobile'] .= $c['content'] . "\n";
		}
		//tpt_dump($c_css['frontend_nomobile']);
		if(!empty($c_css['frontend_nomobile'])) {
			file_put_contents(TPT_CSS_DIR . DIRECTORY_SEPARATOR . 'all1_nm_unpack.css', $c_css['frontend_nomobile']);
			//$c_css['frontend'] = preg_replace('/(\{|\}|;)[\s]+([a-zA-Z]|\{|\}|;)/', '$1$2', $c_css['frontend']);
			$c_css['frontend_nomobile'] = tpt_html::sanitize_css_output($c_css['frontend_nomobile']);
			file_put_contents(TPT_CSS_DIR . DIRECTORY_SEPARATOR . 'all1_nm.css', $c_css['frontend_nomobile']);
		}

		$css['page_nomobile'] = $vars['db']['handler']->getData($vars, 'tpt_html_css', '*', '`enabled`=1 AND `url_id`!=0 AND `mobile`=0 AND (`common_id` IS NULL OR `common_id`="") ORDER BY `id`', 'url_id', true);
		//tpt_dump($css['page'], true);
		if (is_array($css['page_nomobile'])) {
			foreach ($css['page_nomobile'] as $url_id => $c1) {
				$c_css['page_nomobile'] = '';
				foreach ($c1 as $c) {
					$c_css['page_nomobile'] .= $c['content'] . "\n";
				}

				if(!empty($c_css['page_nomobile'])) {
					file_put_contents(TPT_CSS_DIR . DIRECTORY_SEPARATOR . 'all_nm_' . $url_id . '_unpack.css', $c_css['page_nomobile']);
					//$c_css['page_mobile'] = preg_replace('/(\{|\}|;)[\s]+([a-zA-Z]|\{|\}|;)/', '$1$2', $c_css['page_mobile']);
					$c_css['page_nomobile'] = tpt_html::sanitize_css_output($c_css['page_nomobile']);
					file_put_contents(TPT_CSS_DIR . DIRECTORY_SEPARATOR . 'all_nm_' . $url_id . '.css', $c_css['page_nomobile']);
				}
			}
		}





		$css['core_mobile'] = $vars['db']['handler']->getData($vars, 'tpt_html_css', '*', '`enabled`=1 AND `core`=1 AND `mobile`=1 ORDER BY `id`', 'id', false);
		$c_css['core_mobile'] = '';
		foreach ($css['core_mobile'] as $id => $c) {
			$c_css['core_mobile'] .= $c['content'] . "\n";
		}
		if(!empty($c_css['core_mobile'])) {
			file_put_contents(TPT_CSS_DIR . DIRECTORY_SEPARATOR . 'all_m_unpack.css', $c_css['core_mobile']);
			//$c_css['core'] = preg_replace('/(\{|\}|;)[\s]+([a-zA-Z]|\{|\}|;)/', '$1$2', $c_css['core']);
			$c_css['core_mobile'] = tpt_html::sanitize_css_output($c_css['core_mobile']);
			//tpt_dump($c_css['core'], true);
			file_put_contents(TPT_CSS_DIR . DIRECTORY_SEPARATOR . 'all_m.css', $c_css['core_mobile']);
		}

		$css['frontend_mobile'] = $vars['db']['handler']->getData($vars, 'tpt_html_css', '*', '`enabled`=1 AND `frontend`=1 AND `mobile`=1 ORDER BY `id`', 'id', false);
		$c_css['frontend_mobile'] = '';
		foreach ($css['frontend_mobile'] as $id => $c) {
			$c_css['frontend_mobile'] .= $c['content'] . "\n";
		}
		if(!empty($c_css['frontend_mobile'])) {
			file_put_contents(TPT_CSS_DIR . DIRECTORY_SEPARATOR . 'all1_m_unpack.css', $c_css['frontend_mobile']);
			//$c_css['frontend'] = preg_replace('/(\{|\}|;)[\s]+([a-zA-Z]|\{|\}|;)/', '$1$2', $c_css['frontend']);
			$c_css['frontend_mobile'] = tpt_html::sanitize_css_output($c_css['frontend_mobile']);
			file_put_contents(TPT_CSS_DIR . DIRECTORY_SEPARATOR . 'all1_m.css', $c_css['frontend_mobile']);
		}

		$css['page_mobile'] = $vars['db']['handler']->getData($vars, 'tpt_html_css', '*', '`enabled`=1 AND `url_id`!=0 AND `mobile`=1 AND (`common_id` IS NULL OR `common_id`="") ORDER BY `id`', 'url_id', true);
		//tpt_dump($css['page'], true);
		if (is_array($css['page_mobile'])) {
			foreach ($css['page_mobile'] as $url_id => $c1) {
				$c_css['page_mobile'] = '';
				foreach ($c1 as $c) {
					$c_css['page_mobile'] .= $c['content'] . "\n";
				}

				if(!empty($c_css['page_mobile'])) {
					file_put_contents(TPT_CSS_DIR . DIRECTORY_SEPARATOR . 'all_m_' . $url_id . '_unpack.css', $c_css['page_mobile']);
					//$c_css['page_mobile'] = preg_replace('/(\{|\}|;)[\s]+([a-zA-Z]|\{|\}|;)/', '$1$2', $c_css['page_mobile']);
					$c_css['page_mobile'] = tpt_html::sanitize_css_output($c_css['page_mobile']);
					file_put_contents(TPT_CSS_DIR . DIRECTORY_SEPARATOR . 'all_m_' . $url_id . '.css', $c_css['page_mobile']);
				}
			}
		}


		$css['core_mobile2'] = $vars['db']['handler']->getData($vars, 'tpt_html_css', '*', '`enabled`=1 AND `core`=1 AND `mobile`=2 ORDER BY `id`', 'id', false);
		$c_css['core_mobile2'] = '';
		foreach ($css['core_mobile2'] as $id => $c) {
			$c_css['core_mobile2'] .= $c['content'] . "\n";
		}
		if(!empty($c_css['core_mobile2'])) {
			file_put_contents(TPT_CSS_DIR . DIRECTORY_SEPARATOR . 'all_m2_unpack.css', $c_css['core_mobile2']);
			//$c_css['core'] = preg_replace('/(\{|\}|;)[\s]+([a-zA-Z]|\{|\}|;)/', '$1$2', $c_css['core']);
			$c_css['core_mobile2'] = tpt_html::sanitize_css_output($c_css['core_mobile2']);
			//tpt_dump($c_css['core'], true);
			file_put_contents(TPT_CSS_DIR . DIRECTORY_SEPARATOR . 'all_m2.css', $c_css['core_mobile2']);
		}

		$css['frontend_mobile2'] = $vars['db']['handler']->getData($vars, 'tpt_html_css', '*', '`enabled`=1 AND `frontend`=1 AND `mobile`=2 ORDER BY `id`', 'id', false);
		$c_css['frontend_mobile2'] = '';
		foreach ($css['frontend_mobile2'] as $id => $c) {
			$c_css['frontend_mobile2'] .= $c['content'] . "\n";
		}
		if(!empty($c_css['frontend_mobile2'])) {
			file_put_contents(TPT_CSS_DIR . DIRECTORY_SEPARATOR . 'all1_m2_unpack.css', $c_css['frontend_mobile2']);
			//$c_css['frontend'] = preg_replace('/(\{|\}|;)[\s]+([a-zA-Z]|\{|\}|;)/', '$1$2', $c_css['frontend']);
			$c_css['frontend_mobile2'] = tpt_html::sanitize_css_output($c_css['frontend_mobile2']);
			file_put_contents(TPT_CSS_DIR . DIRECTORY_SEPARATOR . 'all1_m2.css', $c_css['frontend_mobile2']);
		}

		$css['page_mobile2'] = $vars['db']['handler']->getData($vars, 'tpt_html_css', '*', '`enabled`=1 AND `url_id`!=0 AND `mobile`=2 AND (`common_id` IS NULL OR `common_id`="") ORDER BY `id`', 'url_id', true);
		//tpt_dump($css['page'], true);
		if (is_array($css['page_mobile2'])) {
			foreach ($css['page_mobile2'] as $url_id => $c1) {
				$c_css['page_mobile2'] = '';
				foreach ($c1 as $c) {
					$c_css['page_mobile2'] .= $c['content'] . "\n";
				}

				if(!empty($c_css['page_mobile2'])) {
					file_put_contents(TPT_CSS_DIR . DIRECTORY_SEPARATOR . 'all_m2_' . $url_id . '_unpack.css', $c_css['page_mobile2']);
					//$c_css['page_mobile2'] = preg_replace('/(\{|\}|;)[\s]+([a-zA-Z]|\{|\}|;)/', '$1$2', $c_css['page_mobile2']);
					$c_css['page_mobile2'] = tpt_html::sanitize_css_output($c_css['page_mobile2']);
					file_put_contents(TPT_CSS_DIR . DIRECTORY_SEPARATOR . 'all_m2_' . $url_id . '.css', $c_css['page_mobile2']);
				}
			}
		}






		$alljs = $vars['db']['handler']->getData($vars, 'tpt_html_js', '*', '`enabled`=1 AND `file`=1', 'id', false);
		foreach ($alljs as $id => $js) {
			$c = mysql_real_escape_string(file_get_contents($js['path']));
			$query = <<< EOT
UPDATE `tpt_html_js` SET `content`="$c" WHERE `id`=$id
EOT;
			$vars['db']['handler']->query($query);
		}


		//include(TPT_LIB_DIR.DIRECTORY_SEPARATOR.'JShrink'.DIRECTORY_SEPARATOR.'Minifier.php');
		//include(TPT_LIB_DIR.DIRECTORY_SEPARATOR.'packer'.DIRECTORY_SEPARATOR.'class.JavaScriptPacker.php');

		$js = array();
		$c_js = array();
		//tpt_dump('asd', true);
		$js['core'] = $vars['db']['handler']->getData($vars, 'tpt_html_js', '*', '`enabled`=1 AND `core`=1 AND `defer`=0 AND `mobile`=-1 ORDER BY `id`', 'id', false);
		$c_js['core'] = '';
		foreach ($js['core'] as $id => $c) {
			$c_js['core'] .= $c['content'] . "\n";
		}
		file_put_contents(TPT_JS_DIR . DIRECTORY_SEPARATOR . 'all_unpack.js', $c_js['core']);
		//$c_js['core'] = preg_replace('/(\{|\}|;)[\s]+([a-zA-Z]|\{|\}|;)/', '$1$2', $c_js['core']);
		//$c_js['core'] = JShrink::minify($c_js['core']);;
		$c_js['core'] = JSMin::minify($c_js['core']);
		;
		//$packer = new JavaScriptPacker($c_js['core']);
		//$c_js['core'] = $packer->pack();
		file_put_contents(TPT_JS_DIR . DIRECTORY_SEPARATOR . 'all.js', $c_js['core']);

		$js['frontend'] = $vars['db']['handler']->getData($vars, 'tpt_html_js', '*', '`enabled`=1 AND `frontend`=1 AND `defer`=0 AND `mobile`=-1 ORDER BY `id`', 'id', false);
		$c_js['frontend'] = '';
		if (!defined('TPT_ADMIN')) {
			$tpt_baseurl = BASE_URL;
			$js_ajaxurl = BASE_URL;
			$tpt_resourceurl = TPT_RESOURCE_URL;
			$tpt_imagesurl = TPT_IMAGES_URL;

			$c_js['frontend'] = <<< EOT
var base_url = '$tpt_baseurl';
var ajax_url = '$js_ajaxurl';
var resource_url = '$tpt_resourceurl';
var tpt_images_url = '$tpt_imagesurl';
EOT;
			file_put_contents(TPT_JS_DIR . DIRECTORY_SEPARATOR . 'url_definitions.js', $c_js['frontend']);
		}

		foreach ($js['frontend'] as $id => $c) {
			$c_js['frontend'] .= $c['content'] . "\n";
		}
		file_put_contents(TPT_JS_DIR . DIRECTORY_SEPARATOR . 'all1_unpack.js', $c_js['frontend']);
		//$c_js['frontend'] = preg_replace('/(\{|\}|;)[\s]+([a-zA-Z]|\{|\}|;)/', '$1$2', $c_js['frontend']);
		//$c_js['frontend'] = JShrink::minify($c_js['frontend']);
		$c_js['frontend'] = JSMin::minify($c_js['frontend']);
		//$packer = new JavaScriptPacker($c_js['frontend']);
		//$c_js['frontend'] = $packer->pack();
		file_put_contents(TPT_JS_DIR . DIRECTORY_SEPARATOR . 'all1.js', $c_js['frontend']);

		$js['core_defer'] = $vars['db']['handler']->getData($vars, 'tpt_html_js', '*', '`enabled`=1 AND `core`=1 AND `defer`=1 AND `mobile`=-1 ORDER BY `id`', 'id', false);
		$c_js['core_defer'] = '';
		foreach ($js['core_defer'] as $id => $c) {
			$c_js['core_defer'] .= $c['content'] . "\n";
		}
		file_put_contents(TPT_JS_DIR . DIRECTORY_SEPARATOR . 'all2_unpack.js', $c_js['core_defer']);
		//$c_js['core_defer'] = preg_replace('/(\{|\}|;)[\s]+([a-zA-Z]|\{|\}|;)/', '$1$2', $c_js['core_defer']);
		//$c_js['core_defer'] = JShrink::minify($c_js['core_defer']);
		$c_js['core_defer'] = JSMin::minify($c_js['core_defer']);
		//$packer = new JavaScriptPacker($c_js['core_defer']);
		//$c_js['core_defer'] = $packer->pack();
		file_put_contents(TPT_JS_DIR . DIRECTORY_SEPARATOR . 'all2.js', $c_js['core_defer']);

		$js['frontend_defer'] = $vars['db']['handler']->getData($vars, 'tpt_html_js', '*', '`enabled`=1 AND `frontend`=1 AND `defer`=1 AND `mobile`=-1 ORDER BY `id`', 'id', false);
		$c_js['frontend_defer'] = '';
		foreach ($js['frontend_defer'] as $id => $c) {
			$c_js['frontend_defer'] .= $c['content'] . "\n";
		}
		file_put_contents(TPT_JS_DIR . DIRECTORY_SEPARATOR . 'all3_unpack.js', $c_js['frontend_defer']);
		//$c_js['frontend_defer'] = preg_replace('/(\{|\}|;)[\s]+([a-zA-Z]|\{|\}|;)/', '$1$2', $c_js['frontend_defer']);
		//$c_js['frontend_defer'] = JShrink::minify($c_js['frontend_defer']);
		$c_js['frontend_defer'] = JSMin::minify($c_js['frontend_defer']);
		//$packer = new JavaScriptPacker($c_js['frontend_defer']);
		//$c_js['frontend_defer'] = $packer->pack();
		file_put_contents(TPT_JS_DIR . DIRECTORY_SEPARATOR . 'all3.js', $c_js['frontend_defer']);

		$js['page'] = $vars['db']['handler']->getData($vars, 'tpt_html_js', '*', '`enabled`=1 AND `url_id`!=0 AND `defer`=0 ORDER BY `id`', 'url_id', true);
		foreach ($js['page'] as $url_id => $c1) {
			$c_js['page'] = '';
			foreach ($c1 as $c) {
				$c_js['page'] .= $c['content'] . "\n";
			}

			file_put_contents(TPT_JS_DIR . DIRECTORY_SEPARATOR . 'all_' . $url_id . '_unpack.js', $c_js['page']);
			//$c_js['page'] = preg_replace('/(\{|\}|;)[\s]+([a-zA-Z]|\{|\}|;)/', '$1$2', $c_js['page']);
			//$c_js['page'] = JShrink::minify($c_js['page']);
			$c_js['page'] = JSMin::minify($c_js['page']);
			//$packer = new JavaScriptPacker($c_js['page']);
			//$c_js['page'] = $packer->pack();
			file_put_contents(TPT_JS_DIR . DIRECTORY_SEPARATOR . 'all_' . $url_id . '.js', $c_js['page']);
		}


		$js['page_defer'] = $vars['db']['handler']->getData($vars, 'tpt_html_js', '*', '`enabled`=1 AND `url_id`!=0 AND `defer`=1 ORDER BY `id`', 'url_id', true);
		$c_js['page_defer'] = '';
		foreach ($js['page_defer'] as $url_id => $c1) {
			$c_js['page_defer'] = '';
			foreach ($c1 as $c) {
				$c_js['page_defer'] .= $c['content'] . "\n";
			}

			file_put_contents(TPT_JS_DIR . DIRECTORY_SEPARATOR . 'all_' . $url_id . '_1_unpack.js', $c_js['page_defer']);
			//$c_js['page_defer'] = preg_replace('/(\{|\}|;)[\s]+([a-zA-Z]|\{|\}|;)/', '$1$2', $c_js['page_defer']);
			//$c_js['page_defer'] = JShrink::minify($c_js['page_defer']);
			$c_js['page_defer'] = JSMin::minify($c_js['page_defer']);
			//$packer = new JavaScriptPacker($c_js['page_defer']);
			//$c_js['page_defer'] = $packer->pack();
			file_put_contents(TPT_JS_DIR . DIRECTORY_SEPARATOR . 'all_' . $url_id . '_1.js', $c_js['page_defer']);
		}




		$js['core_mobile'] = $vars['db']['handler']->getData($vars, 'tpt_html_js', '*', '`enabled`=1 AND `core`=1 AND `defer`=0 AND `mobile`=1 ORDER BY `id`', 'id', false);
		$c_js['core_mobile'] = '';
		foreach ($js['core_mobile'] as $id => $c) {
			$c_js['core_mobile'] .= $c['content'] . "\n";
		}
		file_put_contents(TPT_JS_DIR . DIRECTORY_SEPARATOR . 'all_m_unpack.js', $c_js['core_mobile']);
		//$c_js['core'] = preg_replace('/(\{|\}|;)[\s]+([a-zA-Z]|\{|\}|;)/', '$1$2', $c_js['core']);
		//$c_js['core'] = JShrink::minify($c_js['core']);;
		$c_js['core_mobile'] = JSMin::minify($c_js['core_mobile']);
		;
		//$packer = new JavaScriptPacker($c_js['core']);
		//$c_js['core'] = $packer->pack();
		file_put_contents(TPT_JS_DIR . DIRECTORY_SEPARATOR . 'all_m.js', $c_js['core_mobile']);

		$js['frontend_mobile'] = $vars['db']['handler']->getData($vars, 'tpt_html_js', '*', '`enabled`=1 AND `frontend`=1 AND `defer`=0 AND `mobile`=1 ORDER BY `id`', 'id', false);
		$c_js['frontend_mobile'] = '';
		foreach ($js['frontend_mobile'] as $id => $c) {
			$c_js['frontend_mobile'] .= $c['content'] . "\n";
		}
		file_put_contents(TPT_JS_DIR . DIRECTORY_SEPARATOR . 'all1_m_unpack.js', $c_js['frontend_mobile']);
		//$c_js['frontend'] = preg_replace('/(\{|\}|;)[\s]+([a-zA-Z]|\{|\}|;)/', '$1$2', $c_js['frontend']);
		//$c_js['frontend'] = JShrink::minify($c_js['frontend']);
		$c_js['frontend_mobile'] = JSMin::minify($c_js['frontend_mobile']);
		//$packer = new JavaScriptPacker($c_js['frontend']);
		//$c_js['frontend'] = $packer->pack();
		file_put_contents(TPT_JS_DIR . DIRECTORY_SEPARATOR . 'all1_m.js', $c_js['frontend_mobile']);

		$js['core_defer_mobile'] = $vars['db']['handler']->getData($vars, 'tpt_html_js', '*', '`enabled`=1 AND `core`=1 AND `defer`=1 AND `mobile`=1 ORDER BY `id`', 'id', false);
		$c_js['core_defer_mobile'] = '';
		foreach ($js['core_defer_mobile'] as $id => $c) {
			$c_js['core_defer_mobile'] .= $c['content'] . "\n";
		}
		file_put_contents(TPT_JS_DIR . DIRECTORY_SEPARATOR . 'all2_m_unpack.js', $c_js['core_defer_mobile']);
		//$c_js['core_defer'] = preg_replace('/(\{|\}|;)[\s]+([a-zA-Z]|\{|\}|;)/', '$1$2', $c_js['core_defer']);
		//$c_js['core_defer'] = JShrink::minify($c_js['core_defer']);
		$c_js['core_defer_mobile'] = JSMin::minify($c_js['core_defer_mobile']);
		//$packer = new JavaScriptPacker($c_js['core_defer']);
		//$c_js['core_defer'] = $packer->pack();
		file_put_contents(TPT_JS_DIR . DIRECTORY_SEPARATOR . 'all2_m.js', $c_js['core_defer_mobile']);

		$js['frontend_defer_mobile'] = $vars['db']['handler']->getData($vars, 'tpt_html_js', '*', '`enabled`=1 AND `frontend`=1 AND `defer`=1 AND `mobile`=1 ORDER BY `id`', 'id', false);
		$c_js['frontend_defer_mobile'] = '';
		foreach ($js['frontend_defer_mobile'] as $id => $c) {
			$c_js['frontend_defer_mobile'] .= $c['content'] . "\n";
		}
		file_put_contents(TPT_JS_DIR . DIRECTORY_SEPARATOR . 'all3_m_unpack.js', $c_js['frontend_defer_mobile']);
		//$c_js['frontend_defer'] = preg_replace('/(\{|\}|;)[\s]+([a-zA-Z]|\{|\}|;)/', '$1$2', $c_js['frontend_defer']);
		//$c_js['frontend_defer'] = JShrink::minify($c_js['frontend_defer']);
		$c_js['frontend_defer_mobile'] = JSMin::minify($c_js['frontend_defer_mobile']);
		//$packer = new JavaScriptPacker($c_js['frontend_defer']);
		//$c_js['frontend_defer'] = $packer->pack();
		file_put_contents(TPT_JS_DIR . DIRECTORY_SEPARATOR . 'all3_m.js', $c_js['frontend_defer_mobile']);



		$js['core_mobile2'] = $vars['db']['handler']->getData($vars, 'tpt_html_js', '*', '`enabled`=1 AND `core`=1 AND `defer`=0 AND `mobile`=2 ORDER BY `id`', 'id', false);
		$c_js['core_mobile2'] = '';
		foreach ($js['core_mobile2'] as $id => $c) {
			$c_js['core_mobile2'] .= $c['content'] . "\n";
		}
		file_put_contents(TPT_JS_DIR . DIRECTORY_SEPARATOR . 'all_m2_unpack.js', $c_js['core_mobile2']);
		//$c_js['core'] = preg_replace('/(\{|\}|;)[\s]+([a-zA-Z]|\{|\}|;)/', '$1$2', $c_js['core']);
		//$c_js['core'] = JShrink::minify($c_js['core']);;
		$c_js['core_mobile2'] = JSMin::minify($c_js['core_mobile2']);
		;
		//$packer = new JavaScriptPacker($c_js['core']);
		//$c_js['core'] = $packer->pack();
		file_put_contents(TPT_JS_DIR . DIRECTORY_SEPARATOR . 'all_m2.js', $c_js['core_mobile2']);

		$js['frontend_mobile2'] = $vars['db']['handler']->getData($vars, 'tpt_html_js', '*', '`enabled`=1 AND `frontend`=1 AND `defer`=0 AND `mobile`=2 ORDER BY `id`', 'id', false);
		$c_js['frontend_mobile2'] = '';
		foreach ($js['frontend_mobile2'] as $id => $c) {
			$c_js['frontend_mobile2'] .= $c['content'] . "\n";
		}
		file_put_contents(TPT_JS_DIR . DIRECTORY_SEPARATOR . 'all1_m2_unpack.js', $c_js['frontend_mobile2']);
		//$c_js['frontend'] = preg_replace('/(\{|\}|;)[\s]+([a-zA-Z]|\{|\}|;)/', '$1$2', $c_js['frontend']);
		//$c_js['frontend'] = JShrink::minify($c_js['frontend']);
		$c_js['frontend_mobile2'] = JSMin::minify($c_js['frontend_mobile2']);
		//$packer = new JavaScriptPacker($c_js['frontend']);
		//$c_js['frontend'] = $packer->pack();
		file_put_contents(TPT_JS_DIR . DIRECTORY_SEPARATOR . 'all1_m2.js', $c_js['frontend_mobile2']);

		$js['core_defer_mobile2'] = $vars['db']['handler']->getData($vars, 'tpt_html_js', '*', '`enabled`=1 AND `core`=1 AND `defer`=1 AND `mobile`=2 ORDER BY `id`', 'id', false);
		$c_js['core_defer_mobile2'] = '';
		foreach ($js['core_defer_mobile2'] as $id => $c) {
			$c_js['core_defer_mobile2'] .= $c['content'] . "\n";
		}
		file_put_contents(TPT_JS_DIR . DIRECTORY_SEPARATOR . 'all2_m2_unpack.js', $c_js['core_defer_mobile2']);
		//$c_js['core_defer'] = preg_replace('/(\{|\}|;)[\s]+([a-zA-Z]|\{|\}|;)/', '$1$2', $c_js['core_defer']);
		//$c_js['core_defer'] = JShrink::minify($c_js['core_defer']);
		$c_js['core_defer_mobile2'] = JSMin::minify($c_js['core_defer_mobile2']);
		//$packer = new JavaScriptPacker($c_js['core_defer']);
		//$c_js['core_defer'] = $packer->pack();
		file_put_contents(TPT_JS_DIR . DIRECTORY_SEPARATOR . 'all2_m2.js', $c_js['core_defer_mobile2']);

		$js['frontend_defer_mobile2'] = $vars['db']['handler']->getData($vars, 'tpt_html_js', '*', '`enabled`=1 AND `frontend`=1 AND `defer`=1 AND `mobile`=2 ORDER BY `id`', 'id', false);
		$c_js['frontend_defer_mobile2'] = '';
		foreach ($js['frontend_defer_mobile2'] as $id => $c) {
			$c_js['frontend_defer_mobile2'] .= $c['content'] . "\n";
		}
		file_put_contents(TPT_JS_DIR . DIRECTORY_SEPARATOR . 'all3_m2_unpack.js', $c_js['frontend_defer_mobile2']);
		//$c_js['frontend_defer'] = preg_replace('/(\{|\}|;)[\s]+([a-zA-Z]|\{|\}|;)/', '$1$2', $c_js['frontend_defer']);
		//$c_js['frontend_defer'] = JShrink::minify($c_js['frontend_defer']);
		$c_js['frontend_defer_mobile2'] = JSMin::minify($c_js['frontend_defer_mobile2']);
		//$packer = new JavaScriptPacker($c_js['frontend_defer']);
		//$c_js['frontend_defer'] = $packer->pack();
		file_put_contents(TPT_JS_DIR . DIRECTORY_SEPARATOR . 'all3_m2.js', $c_js['frontend_defer_mobile2']);
	}

	static function getHeadContent(&$vars) {
		$tpt_baseurl = BASE_URL;
		$tpt_jsurl = TPT_JS_URL;
		$tpt_cssurl = TPT_CSS_URL;


		$preview_generator_js_file = 'preview-generator.js';
		$functions_js_file = 'tpt_functions.js';
		if (isDev('newpreview')) {

			$preview_generator_js_file = 'preview-generator_VER2.js';
			$functions_js_file = 'tpt_functions_VER2.js';

			if (isDev('newpreview_G')) {
				$preview_generator_js_file = 'preview-generator_VER3G.js';
				$functions_js_file = 'tpt_functions_VER3G.js';
			}
		}

		/*
		  if(false && !isDev('minified_resources') && empty($_GET['minified_resources'])) {
		  $head_content = <<< EOT
		  <link type="text/css" rel="stylesheet" href="$tpt_cssurl/thickbox.css" />

		  <link rel="stylesheet" type="text/css" href="$tpt_cssurl/tpt_common_css.css" />
		  <link rel="stylesheet" type="text/css" href="$tpt_cssurl/tpt_customcontrols_css.css" />
		  <link rel="stylesheet" type="text/css" href="$tpt_cssurl/tpt_tabs_css.css" />
		  <link rel="stylesheet" type="text/css" href="$tpt_cssurl/navigation.css" />
		  EOT;
		  //<script defer type="text/javascript" src="$tpt_jsurl/jquery.min.js"></script>
		  $head_content .= <<< EOT
		  <script type="text/javascript" src="$tpt_jsurl/ajax.js"></script>
		  <script defer type="text/javascript" src="$tpt_jsurl/ajax_functions.js"></script>
		  <script defer type="text/javascript" src="$tpt_jsurl/json2.js"></script>
		  <script defer type="text/javascript" src="$tpt_jsurl/isAChildOf.js"></script>
		  <script type="text/javascript" src="$tpt_jsurl/addEvent.js"></script>
		  <script defer type="text/javascript" src="$tpt_jsurl/getScroll.js"></script>
		  <script defer type="text/javascript" src="$tpt_jsurl/getMousePos.js"></script>
		  <script defer type="text/javascript" src="$tpt_jsurl/scrollPage.js"></script>
		  <script defer type="text/javascript" src="$tpt_jsurl/getDocHeight.js"></script>
		  <script defer type="text/javascript" src="$tpt_jsurl/getWindowSize.js"></script>
		  <script type="text/javascript" src="$tpt_jsurl/getStyle.js"></script>
		  <script defer type="text/javascript" src="$tpt_jsurl/getOffset.js"></script>
		  <script type="text/javascript" src="$tpt_jsurl/fader.js"></script>
		  <script defer type="text/javascript" src="$tpt_jsurl/$preview_generator_js_file"></script>
		  <script defer type="text/javascript" src="$tpt_jsurl/styledContols.js"></script>
		  <script type="text/javascript" src="$tpt_jsurl/getpricing.js"></script>
		  <script defer type="text/javascript" src="$tpt_jsurl/var_dump.js"></script>
		  <script defer type="text/javascript" src="$tpt_jsurl/thickbox.js"></script>

		  <script type="text/javascript" src="$tpt_jsurl/$functions_js_file"></script>
		  EOT;

		  //tpt_dump($vars['template_data']['head'], true);
		  array_unshift($vars['template_data']['head'], $head_content);

		  if ($vars['environment']['isMobileDevice']['ipod'] ||
		  $vars['environment']['isMobileDevice']['ipad'] ||
		  $vars['environment']['isMobileDevice']['iphone'] ||
		  $vars['environment']['isMobileDevice']['webos']
		  ) {
		  // is iStuff

		  } else {
		  $vars['template_data']['head'][] = <<< EOT
		  <link rel="stylesheet" type="text/css" href="$tpt_cssurl/tpt_tooltips.css" />
		  <script defer type="text/javascript" src="$tpt_jsurl/tpt_tooltips.js"></script>
		  EOT;
		  }

		  //ip population for js development
		  //$dev_ips = array('109.160.0.218');
		  $dev_ips = array();
		  if (!empty($_GET['testing'])) {
		  $dev_ips[count($dev_ips)] = $_SERVER['REMOTE_ADDR'];
		  }
		  if (preg_match('#\-testing#', $_SERVER['REQUEST_URI'])) {
		  $dev_ips[count($dev_ips)] = $_SERVER['REMOTE_ADDR'];
		  }
		  foreach ($dev_ips as $ip) {
		  if ($_SERVER['REMOTE_ADDR'] == $ip) {
		  $vars['template_data']['head'][] = '
		  <script type="text/javascript">
		  REMOTE_ADDR="' . $ip . '";
		  </script>
		  ';
		  break;
		  }
		  }
		  unset($dev_ips, $ip);

		  //do something with this information
		  if ($vars['environment']['isMobileDevice']['ipod'] || $vars['environment']['isMobileDevice']['iphone'] || $vars['environment']['isMobileDevice']['ipad'] ||
		  //$Android ||
		  $vars['environment']['isMobileDevice']['webos']
		  ) {

		  //				$vars['template_data']['head'][] = <<< EOT
		  //	<link rel="stylesheet" type="text/css" href="$tpt_cssurl/tpt/ios_fix.css" />
		  //	EOT;

		  }
		  // <script type="text/javascript" src="$tpt_jsurl/tpt/ios_fix.js" />

		  //			else if(){
		  //					//were an iPad -- do something here
		  //			}else if($Android){
		  //					//were an Android device -- do something here
		  //			}else if($webOS){
		  //					//were a webOS device -- do something here
		  //			}

		  }
		 */
		$vars['template_data']['head']['style_script1'] .= <<< EOT
<link rel="stylesheet" type="text/css" href="$tpt_cssurl/tpt_spec_common_messages_header_navigation_social_main_footer_lightbox_template.css" />
EOT;
		if (isDev('unpackresources') && !empty($_GET['unpackresources'])) {
			$js_url = TPT_JS_URL;
			$css_url = TPT_CSS_URL;

			$css = array();

			$css['core'] = $vars['db']['handler']->getData($vars, 'tpt_html_css', '*', '`enabled`=1 AND `file`=1 AND `core`=1 AND `mobile`=-1 ORDER BY `id`', 'id', false);
			foreach ($css['core'] as $id => $c) {
				$url = $c['url'];
				$vars['template_data']['head']['style_script1'] .= <<< EOT
<link type="text/css" rel="stylesheet" href="$url" />
EOT;
			}
			$css['core_inline'] = $vars['db']['handler']->getData($vars, 'tpt_html_css', '*', '`enabled`=1 AND `file`=0 AND `core`=1 AND `mobile`=-1 ORDER BY `id`', 'id', false);
			foreach ($css['core_inline'] as $id => $c) {
				//$url = $c['url'];
				$content = $c['content'];
				$vars['template_data']['head']['style_script1'] .= <<< EOT
<style type="text/css">
//<![CDATA[
$content
//]]>
</style>
EOT;
			}

			if (!empty($vars['environment']['mobile_template'])) {
				$css['core_mobile'] = $vars['db']['handler']->getData($vars, 'tpt_html_css', '*', '`enabled`=1 AND `file`=1 AND `core`=1 AND `mobile`=' . $vars['environment']['mobile_template'] . ' ORDER BY `id`', 'id', false);
				foreach ($css['core_mobile'] as $id => $c) {
					$url = $c['url'];
					$vars['template_data']['head']['style_script1'] .= <<< EOT
<link type="text/css" rel="stylesheet" href="$url" />
EOT;
				}
				$css['core_inline_mobile'] = $vars['db']['handler']->getData($vars, 'tpt_html_css', '*', '`enabled`=1 AND `file`=0 AND `core`=1 AND `mobile`=' . $vars['environment']['mobile_template'] . ' ORDER BY `id`', 'id', false);
				foreach ($css['core_inline_mobile'] as $id => $c) {
					//$url = $c['url'];
					$content = $c['content'];
					$vars['template_data']['head']['style_script1'] .= <<< EOT
<style type="text/css">
//<![CDATA[
$content
//]]>
</style>
EOT;
				}
			}

			$js = array();

			$js['core'] = $vars['db']['handler']->getData($vars, 'tpt_html_js', '*', '`enabled`=1 AND `file`=1 AND `defer`=0 AND `core`=1 AND `mobile`=-1 ORDER BY `id`', 'id', false);
			foreach ($js['core'] as $id => $c) {
				$url = $c['url'];
				$vars['template_data']['head']['style_script1'] .= <<< EOT
<script type="text/javascript" src="$url"></script>
EOT;
			}
			$js['core_inline'] = $vars['db']['handler']->getData($vars, 'tpt_html_js', '*', '`enabled`=1 AND `file`=0 AND `defer`=0 AND `core`=1 AND `mobile`=-1 ORDER BY `id`', 'id', false);
			foreach ($js['core_inline'] as $id => $c) {
				//$url = $c['url'];
				$content = $c['content'];
				$vars['template_data']['head']['style_script1'] .= <<< EOT
<script type="text/javascript">
//<![CDATA[
$content
//]]>
</script>
EOT;
			}

			$js['core_defer'] = $vars['db']['handler']->getData($vars, 'tpt_html_js', '*', '`enabled`=1 AND `file`=1 AND `defer`=1 AND `core`=1 AND `mobile`=-1 ORDER BY `id`', 'id', false);
			foreach ($js['core_defer'] as $id => $c) {
				$url = $c['url'];
				$vars['template_data']['head']['style_script1'] .= <<< EOT
<script defer type="text/javascript" src="$url"></script>
EOT;
			}
			$js['core_inline_defer'] = $vars['db']['handler']->getData($vars, 'tpt_html_js', '*', '`enabled`=1 AND `file`=0 AND `defer`=1 AND `core`=1 AND `mobile`=-1 ORDER BY `id`', 'id', false);
			foreach ($js['core_inline_defer'] as $id => $c) {
				//$url = $c['url'];
				$content = $c['content'];
				$vars['template_data']['head']['style_script1'] .= <<< EOT
<script defer type="text/javascript">
//<![CDATA[
$content
//]]>
</script>
EOT;
			}


			if (!empty($vars['environment']['mobile_template'])) {
				$js['core_mobile'] = $vars['db']['handler']->getData($vars, 'tpt_html_js', '*', '`enabled`=1 AND `file`=1 AND `defer`=0 AND `core`=1 AND `mobile`=' . $vars['environment']['mobile_template'] . ' ORDER BY `id`', 'id', false);
				foreach ($js['core_mobile'] as $id => $c) {
					$url = $c['url'];
					$vars['template_data']['head']['style_script1'] .= <<< EOT
<script type="text/javascript" src="$url"></script>
EOT;
				}
				$js['core_inline_mobile'] = $vars['db']['handler']->getData($vars, 'tpt_html_js', '*', '`enabled`=1 AND `file`=0 AND `defer`=0 AND `core`=1 AND `mobile`=' . $vars['environment']['mobile_template'] . ' ORDER BY `id`', 'id', false);
				foreach ($js['core_inline_mobile'] as $id => $c) {
					//$url = $c['url'];
					$content = $c['content'];
					$vars['template_data']['head']['style_script1'] .= <<< EOT
<script type="text/javascript">
//<![CDATA[
$content
//]]>
</script>
EOT;
				}

				$js['core_defer_mobile'] = $vars['db']['handler']->getData($vars, 'tpt_html_js', '*', '`enabled`=1 AND `file`=1 AND `defer`=1 AND `core`=1 AND `mobile`=' . $vars['environment']['mobile_template'] . ' ORDER BY `id`', 'id', false);
				foreach ($js['core_defer_mobile'] as $id => $c) {
					$url = $c['url'];
					$vars['template_data']['head']['style_script1'] .= <<< EOT
<script defer type="text/javascript" src="$url"></script>
EOT;
				}
				$js['core_inline_defer_mobile'] = $vars['db']['handler']->getData($vars, 'tpt_html_js', '*', '`enabled`=1 AND `file`=0 AND `defer`=1 AND `core`=1 AND `mobile`=' . $vars['environment']['mobile_template'] . ' ORDER BY `id`', 'id', false);
				foreach ($js['core_inline_defer_mobile'] as $id => $c) {
					//$url = $c['url'];
					$content = $c['content'];
					$vars['template_data']['head']['style_script1'] .= <<< EOT
<script defer type="text/javascript">
//<![CDATA[
$content
//]]>
</script>
EOT;
				}
			}
		} else {
			$js_url = TPT_JS_URL;
			$css_url = TPT_CSS_URL;

			/* AIK Edit */
			if ($vars['url']['rurl'] === '/?uncss=1' ||
				$vars['url']['rurl'] === '/?uncss=1&mobiletest=1'
			) {
				if (isset($_GET['mobiletest']) &&
					intval($_GET['mobiletest']) === 1
				) {
					$vars['template_data']['head']['style_script1'] .= <<< EOT
<link rel="stylesheet" type="text/css" href="$css_url/aik_home_uncss_m-min.css" />
EOT;
				} else {
					$vars['template_data']['head']['style_script1'] .= <<< EOT
<link rel="stylesheet" type="text/css" href="$css_url/aik_home_uncss-min.css" />
EOT;
				}
			} else {
				$vars['template_data']['head']['style_script1'] .= <<< EOT
<link rel="stylesheet" type="text/css" href="$css_url/all.css" />
EOT;
			}
			/* AIK Edit End */


			if ($vars['environment']['mobile_template'] == 1) {
				$css['core_mobile'] = $vars['db']['handler']->getData($vars, 'tpt_html_css', '*', '`enabled`=1 AND `core`=1 AND `mobile`=1 ORDER BY `id`', 'id', false);
				if (!empty($css['core_mobile'])) {
					$vars['template_data']['head']['style_script1'] .= <<< EOT
<link rel="stylesheet" type="text/css" href="$css_url/all_m.css" />
EOT;
				}
			} else if ($vars['environment']['mobile_template'] == 2) {
				$css['core_mobile2'] = $vars['db']['handler']->getData($vars, 'tpt_html_css', '*', '`enabled`=1 AND `core`=1 AND `mobile`=2 ORDER BY `id`', 'id', false);
				if (!empty($css['core_mobile2'])) {
					$vars['template_data']['head']['style_script1'] .= <<< EOT
<link rel="stylesheet" type="text/css" href="$css_url/all_m2.css" />
EOT;
				}
			} else {
				$css['core_nomobile'] = $vars['db']['handler']->getData($vars, 'tpt_html_css', '*', '`enabled`=1 AND `core`=1 AND `mobile`=0 ORDER BY `id`', 'id', false);
				if (!empty($css['core_nomobile'])) {
					$vars['template_data']['head']['style_script1'] .= <<< EOT
<link rel="stylesheet" type="text/css" href="$css_url/all_nm.css" />
EOT;
				}
			}


			$vars['template_data']['head']['style_script1'] .= <<< EOT
<script type="text/javascript" src="$js_url/all.js"></script>
<script defer type="text/javascript" src="$js_url/all2.js"></script>
EOT;

			/*
			  if($vars['environment']['mobile_template'] == 1) {
			  $head_content .= <<< EOT
			  <script type="text/javascript" src="$js_url/all_m.js"></script>
			  <script defer type="text/javascript" src="$js_url/all2_m.js"></script>
			  EOT;
			  } else if($vars['environment']['mobile_template'] == 2) {
			  $head_content .= <<< EOT
			  <script type="text/javascript" src="$js_url/all_m2.js"></script>
			  <script defer type="text/javascript" src="$js_url/all2_m2.js"></script>
			  EOT;
			  }
			 */

		}
	}

	static function getFrontendHeadContent(&$vars) {

		/*
		  <link rel="apple-touch-icon" href="apple-touch-iphone.png" />
		  <link rel="apple-touch-icon" sizes="72x72" href="apple-touch-ipad.png" />
		  <link rel="apple-touch-icon" sizes="114x114" href="apple-touch-iphone4.png" />
		  <link rel="apple-touch-icon" sizes="144x144" href="apple-touch-ipad-retina.png" />
		 */
		$tpt_baseurl = BASE_URL;
		$tpt_imagesurl = TPT_IMAGES_URL;
		$tpt_jsurl = TPT_JS_URL;
		$tpt_cssurl = TPT_CSS_URL;

		if (!defined('TPT_BACK')) {
//if (false && !isDev('minified_resources') && empty($_GET['minified_resources'])) {

//} else {
			/*
			$head_content = <<< EOT
<meta name=viewport content="width=1000, initial-scale=1">

<meta name="google-site-verification" content="FQltraLhtXI9CGk5ucmdltPqYsk1hrFCHR2NZP0MFJU" />

<meta name="msvalidate.01" content="E96AC380923C9E287BB48A90C0BD7DA9" />
<meta name="p:domain_verify" content="b81dd0bc0165a36926116704a71c332b"/>

<link rel="icon" type="image/png" href="$tpt_imagesurl/ffavicon-16x16.png">
<link rel="apple-touch-icon-precomposed" href="$tpt_imagesurl/ffavicon-16x16.png" />
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="$tpt_imagesurl/ffavicon-72x72.png" />
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="$tpt_imagesurl/ffavicon-114x114.png" />
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="$tpt_imagesurl/ffavicon-144x144.png" />
EOT;
			*/

			$vars['template_data']['head']['start'] = <<< EOT
<meta name=viewport content="width=device-width, initial-scale=1">

<meta name="google-site-verification" content="FQltraLhtXI9CGk5ucmdltPqYsk1hrFCHR2NZP0MFJU" />

<meta name="msvalidate.01" content="E96AC380923C9E287BB48A90C0BD7DA9" />
<meta name="p:domain_verify" content="b81dd0bc0165a36926116704a71c332b"/>

<link rel="icon" type="image/png" href="$tpt_imagesurl/ffavicon-16x16.png">
<link rel="apple-touch-icon-precomposed" href="$tpt_imagesurl/ffavicon-16x16.png" />
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="$tpt_imagesurl/ffavicon-72x72.png" />
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="$tpt_imagesurl/ffavicon-114x114.png" />
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="$tpt_imagesurl/ffavicon-144x144.png" />

EOT;
			if(!empty($vars['environment']['page_rule']['google_tag_manager']) && empty($vars['environment']['is404']) && empty($vars['environment']['force404']) && (empty($_GET) || $vars['config']['seo']['google']['tag_manager']['has_allowed_param'])) {
				$ecommerce = '';
				////////$ecommerce = (isset($vars['template_data']['head']['google_tag_manager0'])?$vars['template_data']['head']['google_tag_manager0']:'');
				$vars['template_data']['head']['google_tag_manager'] = '';
				/********
				$vars['template_data']['head']['google_tag_manager'] = <<< EOT
				$ecommerce<!-- Google Tag Manager -->
				<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
				new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
				j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
				'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
				})(window,document,'script','dataLayer','GTM-M82FT9');</script>
				<!-- End Google Tag Manager -->
				EOT;
				 ********/
			}
			if (isDev('unpackresources') && !empty($_GET['unpackresources'])) {
				$js_url = TPT_JS_URL;
				$css_url = TPT_CSS_URL;
				$url_id = intval($vars['environment']['page_rule']['id'], 10);

				/*
				  $css = array();
				  $css['page'] = $vars['db']['handler']->getData($vars, 'tpt_html_css', '*', '`url_id`=' . $url_id . ' ORDER BY `id`', 'id', true);

				  $js = array();
				  $js['page'] = $vars['db']['handler']->getData($vars, 'tpt_html_js', '*', '`url_id`=' . $url_id . ' AND `defer`=0 ORDER BY `id`', 'id', true);
				  $js['page_defer'] = $vars['db']['handler']->getData($vars, 'tpt_html_js', '*', '`url_id`=' . $url_id . ' AND `defer`=1 ORDER BY `id`', 'id', true);
				 */

				$ifmobile = (!empty($vars['environment']['mobile_template']) ? ' (`mobile`=-1 OR `mobile`!=' . $vars['environment']['mobile_template'] . ')' : ' (`mobile`=-1 OR `mobile`=0) ');

				$c_url_id = intval($vars['environment']['page_rule']['id'], 10);
				$css = array();
				$css['frontend'] = $vars['db']['handler']->getData($vars, 'tpt_html_css', '*', '`enabled`=1 AND `file`=1 AND `frontend`=1 AND ' . $ifmobile . ' ORDER BY `id`', 'id', false);
				foreach ($css['frontend'] as $id => $c) {
					$url = $c['url'];
					$vars['template_data']['head']['style_script2'] .= <<< EOT
<link type="text/css" rel="stylesheet" href="$url" />
EOT;
				}
				//$css['inline_content'] = '';
				$css['frontend_inline'] = $vars['db']['handler']->getData($vars, 'tpt_html_css', '*', '`enabled`=1 AND `file`=0 AND `frontend`=1 AND ' . $ifmobile . ' ORDER BY `id`', 'id', false);
				foreach ($css['frontend_inline'] as $id => $c) {
					//$url = $c['url'];
					$content = $c['content'];
					$vars['template_data']['head']['style_script2'] .= <<< EOT
<style type="text/css">
//<![CDATA[
$content
//]]>
</style>
EOT;
				}


				$css['page'] = $vars['db']['handler']->getData($vars, 'tpt_html_css', '*', '`enabled`=1 AND `file`=1 AND `url_id`=' . $c_url_id . ' AND ' . $ifmobile . ' ORDER BY `id`', 'url_id', true);
				//die('asd');
				//tpt_dump($css['page'], true);
				if (is_array($css['page'])) {
					foreach ($css['page'] as $url_id => $c1) {
						foreach ($c1 as $c) {
							$url = $c['url'];
							$vars['template_data']['head']['style_script2'] .= <<< EOT
<link type="text/css" rel="stylesheet" href="$url" />
EOT;
						}
					}
				}
				$css['page_inline'] = $vars['db']['handler']->getData($vars, 'tpt_html_css', '*', '`enabled`=1 AND `file`=0 AND `url_id`=' . $c_url_id . ' AND ' . $ifmobile . ' ORDER BY `id`', 'url_id', true);
				//tpt_dump($css['page'], true);
				if (is_array($css['page_inline'])) {
					foreach ($css['page_inline'] as $url_id => $c1) {
						foreach ($c1 as $c) {
							//$url = $c['url'];
							$content = $c['content'];
							$vars['template_data']['head']['style_script2'] .= <<< EOT
<style type="text/css">
//<![CDATA[
$content
//]]>
</style>
EOT;
						}
					}
				}


				if (!empty($vars['environment']['mobile_template'])) {
					$css['frontend_mobile'] = $vars['db']['handler']->getData($vars, 'tpt_html_css', '*', '`enabled`=1 AND `file`=1 AND `frontend`=1 AND `mobile`=' . $vars['environment']['mobile_template'] . ' ORDER BY `id`', 'id', false);
					foreach ($css['frontend_mobile'] as $id => $c) {
						$url = $c['url'];
						$vars['template_data']['head']['style_script2'] .= <<< EOT
<link type="text/css" rel="stylesheet" href="$url" />
EOT;
					}
				}


				$c_url_id = intval($vars['environment']['page_rule']['id'], 10);
				$js = array();
				$js['frontend'] = $vars['db']['handler']->getData($vars, 'tpt_html_js', '*', '`enabled`=1 AND `file`=1 AND `defer`=0 AND `frontend`=1 AND ' . $ifmobile . ' ORDER BY `id`', 'id', false);
				foreach ($js['frontend'] as $id => $c) {
					$url = $c['url'];
					$vars['template_data']['head']['style_script2'] .= <<< EOT
<script type="text/javascript" src="$url"></script>
EOT;
				}
				$js['frontend_inline'] = $vars['db']['handler']->getData($vars, 'tpt_html_js', '*', '`enabled`=1 AND `file`=0 AND `defer`=0 AND `frontend`=1 AND ' . $ifmobile . ' ORDER BY `id`', 'id', false);
				foreach ($js['frontend_inline'] as $id => $c) {
					//$url = $c['url'];
					$content = $c['content'];
					$vars['template_data']['head']['style_script2'] .= <<< EOT
<script type="text/javascript">
//<![CDATA[
$content
//]]>
</script>
EOT;
				}

				$js['page'] = $vars['db']['handler']->getData($vars, 'tpt_html_js', '*', '`enabled`=1 AND `file`=1 AND `defer`=0 AND `url_id`=' . $c_url_id . ' AND ' . $ifmobile . ' ORDER BY `id`', 'url_id', true);
				//tpt_dump($js['page'], true);
				if (is_array($js['page'])) {
					foreach ($js['page'] as $url_id => $c1) {
						foreach ($c1 as $c) {
							$url = $c['url'];
							$vars['template_data']['head']['style_script2'] .= <<< EOT
<script type="text/javascript" src="$url"></script>
EOT;
						}
					}
				}
				$js['page_inline'] = $vars['db']['handler']->getData($vars, 'tpt_html_js', '*', '`enabled`=1 AND `file`=0 AND `defer`=0 AND `url_id`=' . $c_url_id . ' AND ' . $ifmobile . ' ORDER BY `id`', 'url_id', true);
				//tpt_dump($js['page'], true);
				if (is_array($js['page_inline'])) {
					foreach ($js['page_inline'] as $url_id => $c1) {
						foreach ($c1 as $c) {
							//$url = $c['url'];
							$content = $c['content'];
							$vars['template_data']['head']['style_script2'] .= <<< EOT
<script type="text/javascript">
//<![CDATA[
$content
//]]>
</script>
EOT;
						}
					}
				}

				$js['frontend_defer'] = $vars['db']['handler']->getData($vars, 'tpt_html_js', '*', '`enabled`=1 AND `file`=1 AND `defer`=1 AND `frontend`=1 AND ' . $ifmobile . ' ORDER BY `id`', 'id', false);
				foreach ($js['frontend_defer'] as $id => $c) {
					$url = $c['url'];
					$vars['template_data']['head']['style_script2'] .= <<< EOT
<script defer type="text/javascript" src="$url"></script>
EOT;
				}
				$js['frontend_inline_defer'] = $vars['db']['handler']->getData($vars, 'tpt_html_js', '*', '`enabled`=1 AND `file`=0 AND `defer`=1 AND `frontend`=1 AND ' . $ifmobile . ' ORDER BY `id`', 'id', false);
				foreach ($js['frontend_inline_defer'] as $id => $c) {
					//$url = $c['url'];
					$content = $c['content'];
					$vars['template_data']['head']['style_script2'] .= <<< EOT
<script defer type="text/javascript">
//<![CDATA[
$content
//]]>
</script>
EOT;
				}

				$js['page_defer'] = $vars['db']['handler']->getData($vars, 'tpt_html_js', '*', '`enabled`=1 AND `file`=1 AND `defer`=1 AND `url_id`=' . $c_url_id . ' AND ' . $ifmobile . ' ORDER BY `id`', 'url_id', true);
				//tpt_dump($js['page'], true);
				if (is_array($js['page_defer'])) {
					foreach ($js['page_defer'] as $url_id => $c1) {
						foreach ($c1 as $c) {
							$url = $c['url'];
							$vars['template_data']['head']['style_script2'] .= <<< EOT
<script defer type="text/javascript" src="$url"></script>
EOT;
						}
					}
				}
				$js['page_inline_defer'] = $vars['db']['handler']->getData($vars, 'tpt_html_js', '*', '`enabled`=1 AND `file`=0 AND `defer`=1 AND `url_id`=' . $c_url_id . ' AND ' . $ifmobile . ' ORDER BY `id`', 'url_id', true);
				//tpt_dump($js['page'], true);
				if (is_array($js['page_inline_defer'])) {
					foreach ($js['page_inline_defer'] as $url_id => $c1) {
						foreach ($c1 as $c) {
							//$url = $c['url'];
							$content = $c['content'];
							$vars['template_data']['head']['style_script2'] .= <<< EOT
<script defer type="text/javascript">
//<![CDATA[
$content
//]]>
</script>
EOT;
						}
					}
				}


				if (!empty($vars['environment']['mobile_template'])) {
					$js['frontend_defer_mobile'] = $vars['db']['handler']->getData($vars, 'tpt_html_js', '*', '`enabled`=1 AND `file`=1 AND `defer`=1 AND `frontend`=1 AND `mobile`=' . $vars['environment']['mobile_template'] . ' ORDER BY `id`', 'id', false);
					foreach ($js['frontend_defer_mobile'] as $id => $c) {
						$url = $c['url'];
						$vars['template_data']['head']['style_script2'] .= <<< EOT
<script defer type="text/javascript" src="$url"></script>
EOT;
					}
				}

				//$vars['template_data']['head'][] = $head_content;

				//tpt_dump($vars['template_data']['head'], true);
			} else {
				$js_url = TPT_JS_URL;
				$css_url = TPT_CSS_URL;
				$url_id = 0;
				if (isset($vars['environment']['page_rule']['id'])) {
					$url_id = intval($vars['environment']['page_rule']['id'], 10);
				}
				$css = array();
				//$ifmobile = (!empty($vars['environment']['mobile_template']) ? ' AND `mobile`!=0 ' : '');
				$ifmobile = (!empty($vars['environment']['mobile_template']) ? ' (`mobile`=-1 OR `mobile`!=' . $vars['environment']['mobile_template'] . ')' : ' (`mobile`=-1 OR `mobile`=0) ');
				//$css['core'] = $vars['db']['handler']->getData($vars, 'tpt_html_css', '*', '`enabled`=1 AND `core`=1 AND `mobile`=-1 ORDER BY `id`', 'id', false);
				$css['frontend'] = $vars['db']['handler']->getData($vars, 'tpt_html_css', '*', '`enabled`=1 AND `frontend`=1 AND `mobile`=-1 ORDER BY `id`', 'id', false);
				//$css['page'] = $vars['db']['handler']->getData($vars, 'tpt_html_css', '*', '`enabled`=1 AND `url_id`!=0 AND (`common_id` IS NULL OR `common_id`="") ORDER BY `id`', 'url_id', true);
				//$css['app'] = $vars['db']['handler']->getData($vars, 'tpt_html_css', '*', '`enabled`=1 AND `common_id`>"" GROUP BY `url` ORDER BY `id`', 'common_id', true);


				$css['page'] = $vars['db']['handler']->getData($vars, 'tpt_html_css', '*', '`enabled`=1 AND `file`=1 AND `url_id`>"" AND `url_id`=' . $url_id . ' AND `mobile`=-1 ORDER BY `id`', 'id', false);
				$css['page_inline'] = $vars['db']['handler']->getData($vars, 'tpt_html_css', '*', '`enabled`=1 AND `file`=0 AND `url_id`>"" AND `url_id`=' . $url_id . ' AND `mobile`=-1 ORDER BY `id`', 'id', false);

				$js = array();
				$js['page'] = $vars['db']['handler']->getData($vars, 'tpt_html_js', '*', '`enabled`=1 AND `file`=1 AND `url_id`>"" AND `url_id`=' . $url_id . ' AND ' . $ifmobile . ' AND `defer`=0 ORDER BY `id`', 'id', false);
				$js['page_defer'] = $vars['db']['handler']->getData($vars, 'tpt_html_js', '*', '`enabled`=1 AND `file`=1 AND `url_id`>"" AND `url_id`=' . $url_id . ' AND ' . $ifmobile . ' AND `defer`=1 ORDER BY `id`', 'id', false);
				$js['page_inline'] = $vars['db']['handler']->getData($vars, 'tpt_html_js', '*', '`enabled`=1 AND `file`=0 AND `url_id`>"" AND `url_id`=' . $url_id . ' AND ' . $ifmobile . ' AND `defer`=0 ORDER BY `id`', 'id', false);
				$js['page_inline_defer'] = $vars['db']['handler']->getData($vars, 'tpt_html_js', '*', '`enabled`=1 AND `file`=0 AND `url_id`>"" AND `url_id`=' . $url_id . ' AND ' . $ifmobile . ' AND `defer`=1 ORDER BY `id`', 'id', false);

				/* AIK Edit */
				if ($vars['url']['rurl'] === '/?uncss=1' ||
					$vars['url']['rurl'] === '/?uncss=1&mobiletest=1'
				) {
					/* Just skipping loading of all1.css */
				} else {
					if (!empty($css['frontend'])) {
						$vars['template_data']['head']['style_script2'] .= <<< EOT
<link rel="stylesheet" type="text/css" href="$css_url/all1.css" />
EOT;
					}
				}
				/* AIK Edit End */

				if ($vars['environment']['mobile_template'] == 1) {
					/* AIK Edit */
					if ($vars['url']['rurl'] === '/?uncss=1' ||
						$vars['url']['rurl'] === '/?uncss=1&mobiletest=1'
					) {
						/* Just skipping loading of all1_m.css */
					} else {
						$css['frontend_mobile'] = $vars['db']['handler']->getData($vars, 'tpt_html_css', '*', '`enabled`=1 AND `frontend`=1 AND `mobile`=1 ORDER BY `id`', 'id', false);
						$css['page_mobile'] = $vars['db']['handler']->getData($vars, 'tpt_html_css', '*', '`enabled`=1 AND `url_id`>"" AND `url_id`=' . $url_id . ' AND `mobile`=1 AND (`common_id` IS NULL OR `common_id`="") ORDER BY `id`', 'url_id', true);
						if (!empty($css['frontend_mobile'])) {
							$vars['template_data']['head']['style_script2'] .= <<< EOT
<link rel="stylesheet" type="text/css" href="$css_url/all1_m.css" />
EOT;
						}
						if (!empty($css['page_mobile'])) {
							$vars['template_data']['head']['style_script2'] .= <<< EOT
<link rel="stylesheet" type="text/css" href="$css_url/all_m_{$url_id}.css" />
EOT;
						}
					}
					/* AIK Edit End */
				} else if ($vars['environment']['mobile_template'] == 2) {
					$css['frontend_mobile2'] = $vars['db']['handler']->getData($vars, 'tpt_html_css', '*', '`enabled`=1 AND `frontend`=1 AND `mobile`=2 ORDER BY `id`', 'id', false);
					$css['page_mobile2'] = $vars['db']['handler']->getData($vars, 'tpt_html_css', '*', '`enabled`=1 AND `url_id`>"" AND `url_id`=' . $url_id . ' AND `mobile`=2 AND (`common_id` IS NULL OR `common_id`="") ORDER BY `id`', 'url_id', true);
					if (!empty($css['frontend_mobile2'])) {
						$vars['template_data']['head']['style_script2'] .= <<< EOT
<link rel="stylesheet" type="text/css" href="$css_url/all1_m2.css" />
EOT;
					}
					if (!empty($css['page_mobile2'])) {
						$vars['template_data']['head']['style_script2'] .= <<< EOT
<link rel="stylesheet" type="text/css" href="$css_url/all_m2_{$url_id}.css" />
EOT;
					}
				} else {
					$css['frontend_nomobile'] = $vars['db']['handler']->getData($vars, 'tpt_html_css', '*', '`enabled`=1 AND `frontend`=1 AND `mobile`=0 ORDER BY `id`', 'id', false);
					$css['page_nomobile'] = $vars['db']['handler']->getData($vars, 'tpt_html_css', '*', '`enabled`=1 AND `url_id`>"" AND `url_id`=' . $url_id . ' AND `mobile`=0 AND (`common_id` IS NULL OR `common_id`="") ORDER BY `id`', 'url_id', true);
					if (!empty($css['frontend_nomobile'])) {
						$vars['template_data']['head']['style_script2'] .= <<< EOT
<link rel="stylesheet" type="text/css" href="$css_url/all1_nm.css" />
EOT;
					}
					if (!empty($css['page_nomobile'])) {
						$vars['template_data']['head']['style_script2'] .= <<< EOT
<link rel="stylesheet" type="text/css" href="$css_url/all_nm_{$url_id}.css" />
EOT;
					}
				}
				//tpt_dump($css['page'], true);
				if (!empty($css['page'])) {
					/*
					  $head_content .= <<< EOT
					  <link rel="stylesheet" type="text/css" href="$css_url/all_$url_id.css" />
					  EOT;
					 */
					//tpt_dump();
					foreach ($css['page'] AS $c) {
						$url = $c['url'];
						$vars['template_data']['head']['style_script2'] .= <<< EOT
<link rel="stylesheet" type="text/css" href="$url" />
EOT;
					}

					//tpt_dump($css['page']);
				}

				if (!empty($css['page_inline'])) {
					//tpt_dump($css['page']);
					$hcont = '';
					foreach ($css['page_inline'] AS $c) {
						$hcont .= $c['content'];
					}

					$vars['template_data']['head']['style_script2'] .= <<< EOT
<style type="text/css">
$hcont
</style>
EOT;
				}


				//array_unshift($vars['template_data']['head'], $head_content);
//<script type="text/javascript" src="'.$js_url.'/all1.js"></script>
				$vars['template_data']['head']['style_script2'] .= <<< EOT
<script defer type="text/javascript" src="$js_url/all3.js"></script>
EOT;
				if ($vars['environment']['mobile_template'] == 1) {
					$vars['template_data']['head']['style_script2'] .= <<< EOT
<script defer type="text/javascript" src="$js_url/all3_m.js"></script>
EOT;
				} else if ($vars['environment']['mobile_template'] == 2) {
					$vars['template_data']['head']['style_script2'] .= <<< EOT
<script defer type="text/javascript" src="$js_url/all3_m2.js"></script>
EOT;
				}
				//tpt_dump($js['page'], true);
				if (!empty($js['page'])) {
					//tpt_dump('all_'.$url_id);
					//tpt_dump(array_keys($js['page']));
					$vars['template_data']['head']['style_script2'] .= <<< EOT
<script type="text/javascript" src="$js_url/all_$url_id.js"></script>
EOT;
				}
				if (!empty($js['page_defer'])) {
					$vars['template_data']['head']['style_script2'] .= <<< EOT
<script defer type="text/javascript" src="$js_url/all_{$url_id}_1.js"></script>
EOT;
				}
			}
		}
	}

	static function render_backend(&$vars) {
		$users_module = getModule($vars, "Users");

		$template = '';

// this override key ovverrides all head scripts
		if (!empty($vars['template_data']['head']['override'])) {
			$vars['template_data']['head'] = array($vars['template_data']['head']['override']);
		}
///////////// END INCLUDE PAGE SCRIPTS OR 404.php */
// include js according to the page ////
		if (is_file($dfl = dirname(__FILE__) . '/js/dyn/' . $vars['environment']['page_rule']['id'] . '.js')) {
			$vars['template_data']['head'][] = '<script type="text/javascript" src="'
				. BASE_URL . '/js/dyn/' . $vars['environment']['page_rule']['id']
				. '.js"></script>';
		}

		$vars['template_data']['head'][] = self::getBackendTptTasksHeadContent($vars);
//////////////////////////////////////
//************ ADMIN SECTION
//var_dump($vars['template_data']['tpt_logged_in']);die();
		tpt_logger::dump($vars, $vars['environment']['isAdmin'], debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), '$vars[\'environment\'][\'isAdmin\']', __FILE__ . ' ' . __LINE__);
		$uid = $users_module->get_user_id_from_cookie($vars, (!empty($_COOKIE['tpt_logged_user']) ? $_COOKIE['tpt_logged_user'] : ''));
		tpt_logger::dump($vars, (!empty($_COOKIE['tpt_logged_user']) ? $_COOKIE['tpt_logged_user'] : '') . ' ' . $uid, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), '$_COOKIE[\'tpt_logged_user\'].\' \'.$uid', __FILE__ . ' ' . __LINE__);
//tpt_dump('asdadsasdasd', true);
		if (!empty($vars['template_data']['tpt_logged_in']) && !$vars['user']['isLogged']) {
			//if($vars['environment']['isAdmin']) {
			//$vars['environment']['ajax_result']['messages']['SESSION_EXPIRED'] = array('text'=>'Your session has expired. Please login.', 'type'=>'notice');
			//}


			/*
			  if(isUltraUser()) {
			  $vars['environment']['ajax_result']['messages'][] = array('text'=>'DEBUG: Your session has expired. Please login.', 'type'=>'notice');
			  }
			 */
		}

////////////// END ADMIN SECTION */
//************ PREP AND RENDER TEMPLATE
		$js_ajaxurl = BASE_URL;



		$tooltips = '';
		if ($vars['environment']['isMobileDevice']['ipod'] ||
			$vars['environment']['isMobileDevice']['ipad'] ||
			$vars['environment']['isMobileDevice']['iphone'] ||
			$vars['environment']['isMobileDevice']['android'] ||
			$vars['environment']['isMobileDevice']['webos']) {
// is iStuff
		} else {
			$tooltips = $vars['template']['tooltips'];
		}

		$home_href = $vars['template']['home_href'];
		$quote_link = $vars['template']['quote_link'];
		/*
		  $con_top = $vars['template']['con_top'];
		  $con_bottom = $vars['template']['con_bottom'];
		 */


		$header = $vars['template']['header'];
		//tpt_dump($vars['template_data']);

		$subpath = $vars['config']['subpath'];

		$tpt_baseurl = BASE_URL;
		//$navigation = tpt_navigation$vars['template']['navigation'];
		if (empty($vars['environment']['client']) || ($vars['environment']['client'] == 2)) {
			//if(isDev('newadmin')) {
			$admin_header = self::getBackendHeader($vars);
			//} else {
			//	$admin_header = self::getOldBackendHeader($vars);
			//}
			if (empty($vars['template_data']['hasLeftBar'])) {
				$admin_header = '';
			}
		} else {
			//$vars['template_data']['head'] = array();

			$vars['template']['header'] = self::getFrontendHeader($vars);
			//tpt_dump($vars['template']['header'], true);

			self::getHeadContent($vars);
			self::getFrontendHeadContent($vars);

			$home_href = $vars['template']['home_href'];
			$quote_link = $vars['template']['quote_link'];
			/*
			  $con_top = $vars['template']['con_top'];
			  $con_bottom = $vars['template']['con_bottom'];
			 */


			$header = $vars['template']['header'];
			$social_bar = $vars['template']['social_bar'];
			$subpath = $vars['config']['subpath'];
			$left_bar = $vars['template']['left_bar'];
		}



//$content = $vars['template']['content'];
		$footer = $vars['template']['footer'];

		$subpath = $vars['config']['subpath'];
		$content = '';
		if (!empty($vars['template']['content'])) {
			$content = $vars['template']['content'];
		}

		$ruleid = $vars['environment']['page_rule']['id'];



		if (!empty($_GET['asyncload'])) {
			die($content);
		}

//tpt_dump($vars['modules']['handler'], true);die();
		/*
		  if(($_SERVER['REMOTE_ADDR'] == '109.160.0.218') && ($_GET['debug'] == 'debu')) {
		  //var_dump($this->pricingTable);//die();
		  //var_dump($this->total_qty);//die();
		  //var_dump(self::$pricing_data);//die();
		  //var_dump($this->mfgcost);
		  die('eeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee');

		  }
		 */

		$vars['admin']['handler']->after_content($vars);
		$admin_panel = $vars['template']['admin_panel'];
//tpt_dump($admin_panel, true);
		$admin_content = $vars['template']['admin_content'];
		//$admin_tabs = json_encode($vars['admin']['template_data']['admin_tabs']);
//var_dump($vars['template']['admin_content']);
//tpt_dump($vars['user']['isLogged'], true);
		//tpt_dump('1', true);

		$messages = '';
		if (!tpt_request::$redirect) {
			unset($_SESSION['templay']['messages']);
			unset($_SESSION['templay']['execute_onload']);
			tpt_Messages::getMessages($vars);
			$messages = implode("\n", $vars['template_data']['messages']);
			//tpt_dump($messages);
			if (!empty($messages)) {
				if (empty($vars['environment']['client']) || ($vars['environment']['client'] == 2)) {
					$messages = <<< EOT
<div style="-webkit-box-shadow: inset 0px 2px 7px 1px rgba(82,82,82,1);
-moz-box-shadow: inset 0px 2px 7px 1px rgba(82,82,82,1);
box-shadow: inset 0px 2px 7px 1px rgba(82,82,82,1);" class="padding-top-8 padding-bottom-12" id="tpt_messages">
$messages
</div>
EOT;
				} else {

				}
			}

			$mtop = ' top: ' . intval($vars['admin']['template_data']['panel_max_top_factor'], 0) . 'px;';

			foreach ($vars['environment']['ajax_result']['execute_onload']['head'] as $script) {
				$vars['template_data']['head'][] = $script;
			}

			foreach ($vars['environment']['ajax_result']['execute_onload']['footer'] as $script) {
				$vars['template_data']['footer_scripts']['scripts'][] = $script;
			}
		} else {
			$_SESSION['templay']['execute_onload'] = $vars['environment']['ajax_result']['execute_onload'];
			$_SESSION['templay']['messages'] = $vars['environment']['ajax_result']['messages'];
		}
		//tpt_dump($messages);


		$head_html = implode("\n", $vars['template_data']['head']);
		$meta_html = implode("\n", $vars['template_data']['meta']);
		$title = $vars['template']['title'];

		//tpt_dump($vars['template']['title'], true);

		$footer_general = implode("\n", $vars['template_data']['footer_scripts']['content']);
		//if($_SERVER['REMOTE_ADDR'] == '85.130.3.155') {
		//    var_dump($vars['template_data']['footer_scripts']['content']);
		//    die();
		//}
		$footer_styles = implode("\n", $vars['template_data']['footer_scripts']['style']);
		$footer_styles = <<< EOT
<style type="text/css">
$footer_styles
</style>
EOT;



		$footer_scripts = implode("\n", $vars['template_data']['footer_scripts']['scripts']);
		$footer_scripts = <<< EOT
<script type="text/javascript">
//<![CDATA[
$footer_scripts
//]]>
</script>
EOT;
		//$footer_styles."\n". empty styles that breaks the validation
		$footer_code = $footer_scripts . "\n" . $footer_general;

		//tpt_dump($vars['environment']['isAjax'], true);
		if (!$vars['environment']['isAjax']) {
			//tpt_dump($vars['environment']['isAjax'], true);
			tpt_current_user::setLoggedUserCookies($vars);

			//tpt_content_cache::load_cache($vars);

			switch ($vars['template_data']['template_type']) {
				case 'plain' :
					include(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'template-plain.php');
					break;
				case 'plain-ios-preview-frame' :
					include(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'template-plain-ios-preview-frame.php');
					break;
				case 'ios-builder-frame' :
					include(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'template-ios-builder-frame.php');
					break;
				case 'ios-dc' :
					include(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'template-ios-designcenter.php');
					break;
				default:

					if ($vars['environment']['isAdministration']) {
						//die('asdasdasdasd');
						//tpt_dump('ddddd', true);
						$admin_header = $vars['template']['admin_header'];
						include(TPT_ADMIN_BASE_DIR . DIRECTORY_SEPARATOR . 'template.php');
					} else {
						if (!empty($vars['environment']['client'])) {
							if (($vars['environment']['client'] == 2)) {
								include(TPT_BASE_DIR . DIRECTORY_SEPARATOR . 'template-backend.php');
							} else if (($vars['environment']['client'] == 1)) {
								//tpt_dump($header, true);
								include(TPT_BASE_DIR . DIRECTORY_SEPARATOR . 'template.php');
							}
						} else {
							include(TPT_BASE_DIR . DIRECTORY_SEPARATOR . 'template-backend.php');
						}
					}
			}


			if ($vars['template_data']['isFrame']) {
				//die('dddd');
				echo str_replace('<a', '<a target="_parent"', ob_get_clean());
			}


			//include(dirname(__FILE__).DIRECTORY_SEPARATOR.'template2.php');
		} else {
			//tpt_dump($vars['environment']['isTask'], true);
			if ($vars['environment']['isTask']) {
				//var_dump($admin_content);die();
				$update_elements = array();
				$append_html = $vars['environment']['ajax_result']['append_html'];
				if (empty($vars['environment']['ajax_result']['update_elements'])) {
					$update_elements = array(
						//'content'=>$content,
						'tpt_messages' => $messages
					);
				} else {
					$update_elements = array_map('utf8_encode', $vars['environment']['ajax_result']['update_elements']);
				}
				$add_style = implode("\n", $vars['environment']['ajax_result']['add_style']);
				$exec_script = implode("\n", $vars['environment']['ajax_result']['exec_script']);
				$response = array(
					'action' => 'update',
					'update_objects' => array(),
					'update_elements' => $update_elements,
					'append_html' => $append_html,
					'add_style' => $add_style,
					'exec_script' => $exec_script
				);
				/*
				  if($vars['environment']['isAdmin']) {
				  $response['update_elements']['admin_content'] = $admin_content;
				  if($vars['user']['isLogged']) {
				  $response['update_objects']['tpt_admin_tabs'] = $admin_tabs;
				  }
				  }
				 */
				tpt_current_user::setLoggedUserCookies($vars);
				//var_dump($response);die();
				//json_encode($response);
				//var_dump(json_last_error());//die();
				//var_dump($response);die();
				$template = json_encode($response);
			} else {
				tpt_current_user::setLoggedUserCookies($vars);
				$template = $vars['environment']['ajax_response'];
			}
		}

		return $template;
	}

	static function getOldBackendHeader(&$vars) {
		ob_start();
		global $tpt_vars;
		include(TPT_BACKEND_INCLUDES_DIR . DIRECTORY_SEPARATOR . 'admin-header.php');

		return ob_get_clean();
	}

	static function getBackendHeaderTopmostMfg(&$vars) {
		$orders_module = getModule($vars, 'Orders');
		$status_module = getModule($vars, 'OrderStatus');
		$sts = $status_module->moduleData['id'];
		$mark_module = getModule($vars, 'OrderMark');
		$mrks = $mark_module->moduleData['id'];
		$statuses = $status_module->moduleData['id'];
		$factories_module = getModule($vars, 'Factories');
		$factories = $factories_module->moduleData['id'];
		$mfg_module = getModule($vars, 'Manufacturers');
		$mfgs = $mfg_module->moduleData['id'];

		$mfg = '';
		$user_factory = intval($vars['user']['data']['factory'], 10);
		if (empty($factories[$user_factory])) {
			die('Error');
		} else {
			if (empty($mfgs[$factories[$user_factory]['mfg_id']])) {
				die('Error');
			}
			$mfg = $mfgs[$factories[$user_factory]['mfg_id']];
		}

		define('TPT_STATUS_PROOF_DESIGN_READY', '29');
		define('TPT_STATUS_PROOF_SENT_PENDING', '13');
		define('TPT_STATUS_CALLED_AND_EMAILED_PENDING', '6');
		define('TPT_STATUS_EMAILED_PENDING', '5');
		define('TPT_STATUS_NEEDS_FOLLOW_UP', '24');
		define('TPT_MARK_CALL_TODAY', '7');
		define('TPT_MARK_AWAITING_PAYMENT', '3');
		define('TPT_MARK_AWAITING_PAYMENT_PO', '22');
		define('TPT_STATUS_PAYPAL_PENDING', '33');
		define('TPT_STATUS_ORDERED_PENDING', '30');
		define('TPT_STATUS_PROOF_APPROVED', '14');
		define('TPT_STATUS_SUBMITTED_ONLINE', '1');
		define('TPT_STATUS_OS_ORDER_PROBLEM', '38');

		define('TPT_STATUS_IH_PRINTED_PREPRODUCTION', '44');
		define('TPT_STATUS_IH_OUT_OF_STOCK_CROSS', '47');
		define('TPT_STATUS_IH_IN_PRODUCTION', '27');

		define('TPT_STATUS_IH_POST_PRODUCTION', '28');


		define('TPT_STATUS_OS_MFG_REVIEW', '15');
		define('TPT_STATUS_OS_IN_PRODUCTION', '16');
		define('TPT_STATUS_OS_SHIPPED_TO_AMZG', '17');
		define('TPT_STATUS_OS_SHIPPED_TO_CUSTOMER', '18');
		define('TPT_STATUS_AMZG_RECEIVED', '19');
		define('TPT_STATUS_CUSTOMER_RECEIVED', '20');
		//define('TPT_STATUS_OS_ORDER_PROBLEM', '38');
		define('TPT_STATUS_OS_RE_PRODUCTION', '40');
		define('TPT_STATUS_OS_RE_SHIPPED', '42');




		$statuses = array(
			/*
			  'TPT_STATUS_SUBMITTED_ONLINE' => array(
			  TPT_STATUS_SUBMITTED_ONLINE,
			  //'/qo-list?tables[temp_custom_orders][status][equals]=1&tables[temp_custom_orders][mark_as][equals]=null&tables[temp_custom_orders][assigned_to][equals]=null&tables[temp_custom_order_products][type][exists]=null&tables[temp_custom_orders][tax_class][equals]=null&tables[temp_custom_orders][problem_order][equals]=null&tables[temp_custom_orders][rma_flag][equals]=null&page=1',
			  '/qo-list?tables[temp_custom_orders][status][equals]=1&tables[temp_custom_orders][mark_as][equals]=null&tables[temp_custom_orders][assigned_to][equals]=null&tables[temp_custom_order_products][type][exists]=null&tables[temp_custom_orders][texas_order%2Ctax_exempt][equals_and]=null%2Cnull&tables[temp_custom_orders][problem_order][equals]=null&tables[temp_custom_orders][rma_flag][equals]=null&page=1',
			  '',
			  0
			  ),
			  'TPT_STATUS_PROOF_DESIGN_READY' => array(
			  TPT_STATUS_PROOF_DESIGN_READY,
			  //'/qo-list?tables[temp_custom_orders][status][equals]=29&tables[temp_custom_orders][mark_as][equals]=0&tables[temp_custom_orders][assigned_to][equals]=0&tables[temp_custom_order_products][type][exists]=0&tables[temp_custom_orders][tax_class][equals]=0&tables[temp_custom_orders][problem_order][equals]=0&tables[temp_custom_orders][rma_flag][equals]=0&page=1',
			  '/qo-list?tables[temp_custom_orders][status][equals]=29&tables[temp_custom_orders][mark_as][equals]=null&tables[temp_custom_orders][assigned_to][equals]=null&tables[temp_custom_order_products][type][exists]=null&tables[temp_custom_orders][texas_order%2Ctax_exempt][equals_and]=null%2Cnull&tables[temp_custom_orders][problem_order][equals]=null&tables[temp_custom_orders][rma_flag][equals]=null&page=1',
			  '',
			  0
			  ),
			  'TPT_STATUS_PROOF_SENT_PENDING' => array(
			  TPT_STATUS_PROOF_SENT_PENDING,
			  //'/qo-list?tables[temp_custom_orders][status][equals]=13&tables[temp_custom_orders][mark_as][equals]=0&tables[temp_custom_orders][assigned_to][equals]=0&tables[temp_custom_order_products][type][exists]=0&tables[temp_custom_orders][tax_class][equals]=0&tables[temp_custom_orders][problem_order][equals]=0&tables[temp_custom_orders][rma_flag][equals]=0&page=1',
			  '/qo-list?tables[temp_custom_orders][status][equals]=13&tables[temp_custom_orders][mark_as][equals]=null&tables[temp_custom_orders][assigned_to][equals]=null&tables[temp_custom_order_products][type][exists]=null&tables[temp_custom_orders][texas_order%2Ctax_exempt][equals_and]=null%2Cnull&tables[temp_custom_orders][problem_order][equals]=null&tables[temp_custom_orders][rma_flag][equals]=null&page=1',
			  '',
			  0
			  ),
			  'TPT_STATUS_CALLED_AND_EMAILED_PENDING' => array(
			  TPT_STATUS_CALLED_AND_EMAILED_PENDING,
			  //'/qo-list?tables[temp_custom_orders][status][equals]=6&tables[temp_custom_orders][mark_as][equals]=0&tables[temp_custom_orders][assigned_to][equals]=0&tables[temp_custom_order_products][type][exists]=0&tables[temp_custom_orders][tax_class][equals]=0&tables[temp_custom_orders][problem_order][equals]=0&tables[temp_custom_orders][rma_flag][equals]=0&page=1',
			  '/qo-list?tables[temp_custom_orders][status][equals]=6&tables[temp_custom_orders][mark_as][equals]=null&tables[temp_custom_orders][assigned_to][equals]=null&tables[temp_custom_order_products][type][exists]=null&tables[temp_custom_orders][texas_order%2Ctax_exempt][equals_and]=null%2Cnull&tables[temp_custom_orders][problem_order][equals]=null&tables[temp_custom_orders][rma_flag][equals]=null&page=1',
			  '',
			  0
			  ),
			  'TPT_STATUS_EMAILED_PENDING' => array(
			  TPT_STATUS_EMAILED_PENDING,
			  //'/qo-list?tables[temp_custom_orders][status][equals]=5&tables[temp_custom_orders][mark_as][equals]=0&tables[temp_custom_orders][assigned_to][equals]=0&tables[temp_custom_order_products][type][exists]=0&tables[temp_custom_orders][tax_class][equals]=0&tables[temp_custom_orders][problem_order][equals]=0&tables[temp_custom_orders][rma_flag][equals]=0&page=1',
			  '/qo-list?tables[temp_custom_orders][status][equals]=5&tables[temp_custom_orders][mark_as][equals]=null&tables[temp_custom_orders][assigned_to][equals]=null&tables[temp_custom_order_products][type][exists]=null&tables[temp_custom_orders][texas_order%2Ctax_exempt][equals_and]=null%2Cnull&tables[temp_custom_orders][problem_order][equals]=null&tables[temp_custom_orders][rma_flag][equals]=null&page=1',
			  '',
			  0
			  ),
			  'TPT_STATUS_NEEDS_FOLLOW_UP' => array(
			  TPT_STATUS_NEEDS_FOLLOW_UP,
			  //'/qo-list?tables[temp_custom_orders][status][equals]=24&tables[temp_custom_orders][mark_as][equals]=0&tables[temp_custom_orders][assigned_to][equals]=0&tables[temp_custom_order_products][type][exists]=0&tables[temp_custom_orders][tax_class][equals]=0&tables[temp_custom_orders][problem_order][equals]=0&tables[temp_custom_orders][rma_flag][equals]=0&page=1',
			  '/qo-list?tables[temp_custom_orders][status][equals]=24&tables[temp_custom_orders][mark_as][equals]=null&tables[temp_custom_orders][assigned_to][equals]=null&tables[temp_custom_order_products][type][exists]=null&tables[temp_custom_orders][texas_order%2Ctax_exempt][equals_and]=null%2Cnull&tables[temp_custom_orders][problem_order][equals]=null&tables[temp_custom_orders][rma_flag][equals]=null&page=1',
			  '',
			  0
			  ),
			  'TPT_STATUS_PAYPAL_PENDING' => array(
			  TPT_STATUS_PAYPAL_PENDING,
			  //'/qo-list?tables[temp_custom_orders][status][equals]=33&tables[temp_custom_orders][mark_as][equals]=0&tables[temp_custom_orders][assigned_to][equals]=0&tables[temp_custom_order_products][type][exists]=0&tables[temp_custom_orders][tax_class][equals]=0&tables[temp_custom_orders][problem_order][equals]=0&tables[temp_custom_orders][rma_flag][equals]=0&page=1',
			  '/qo-list?tables[temp_custom_orders][status][equals]=33&tables[temp_custom_orders][mark_as][equals]=null&tables[temp_custom_orders][assigned_to][equals]=null&tables[temp_custom_order_products][type][exists]=null&tables[temp_custom_orders][texas_order%2Ctax_exempt][equals_and]=null%2Cnull&tables[temp_custom_orders][problem_order][equals]=null&tables[temp_custom_orders][rma_flag][equals]=null&page=1',
			  '',
			  0
			  ),
			  'TPT_STATUS_ORDERED_PENDING' => array(
			  TPT_STATUS_ORDERED_PENDING,
			  //'/qo-list?tables[temp_custom_orders][status][equals]=30&tables[temp_custom_orders][mark_as][equals]=0&tables[temp_custom_orders][assigned_to][equals]=0&tables[temp_custom_order_products][type][exists]=0&tables[temp_custom_orders][tax_class][equals]=0&tables[temp_custom_orders][problem_order][equals]=0&tables[temp_custom_orders][rma_flag][equals]=0&page=1',
			  '/qo-list?tables[temp_custom_orders][status][equals]=30&tables[temp_custom_orders][mark_as][equals]=null&tables[temp_custom_orders][assigned_to][equals]=null&tables[temp_custom_order_products][type][exists]=null&tables[temp_custom_orders][texas_order%2Ctax_exempt][equals_and]=null%2Cnull&tables[temp_custom_orders][problem_order][equals]=null&tables[temp_custom_orders][rma_flag][equals]=null&page=1',
			  '',
			  0
			  ),
			  //		'TPT_STATUS_PROOF_APPROVED' => array(
			  //			TPT_STATUS_PROOF_APPROVED,
			  //			'/custom-quotes.php?filter_status=14&page=1&id_sort=&name_sort=&email_sort=&band_type=&o_date=&mfg_sort=&need_date_sort=&search_term=&mfg=&marked_status=&product_type=&tax=&pbm_status=&rma_order=',
			  //			'',
			  //			0
			  //		),

			  'TPT_STATUS_IH_PRINTED_PREPRODUCTION' => array(
			  TPT_STATUS_IH_PRINTED_PREPRODUCTION,
			  //'/qo-list?tables[temp_custom_orders][status][equals]=44&tables[temp_custom_orders][mark_as][equals]=0&tables[temp_custom_orders][assigned_to][equals]=0&tables[temp_custom_order_products][type][exists]=0&tables[temp_custom_orders][tax_class][equals]=0&tables[temp_custom_orders][problem_order][equals]=0&tables[temp_custom_orders][rma_flag][equals]=0&page=1',
			  '/qo-list?tables[temp_custom_orders][status][equals]=44&tables[temp_custom_orders][mark_as][equals]=null&tables[temp_custom_orders][assigned_to][equals]=null&tables[temp_custom_order_products][type][exists]=null&tables[temp_custom_orders][texas_order%2Ctax_exempt][equals_and]=null%2Cnull&tables[temp_custom_orders][problem_order][equals]=null&tables[temp_custom_orders][rma_flag][equals]=null&page=1',
			  '',
			  0
			  ),
			  'TPT_STATUS_IH_OUT_OF_STOCK_CROSS' => array(
			  TPT_STATUS_IH_OUT_OF_STOCK_CROSS,
			  //'/qo-list?tables[temp_custom_orders][status][equals]=47&tables[temp_custom_orders][mark_as][equals]=0&tables[temp_custom_orders][assigned_to][equals]=0&tables[temp_custom_order_products][type][exists]=0&tables[temp_custom_orders][tax_class][equals]=0&tables[temp_custom_orders][problem_order][equals]=0&tables[temp_custom_orders][rma_flag][equals]=0&page=1',
			  '/qo-list?tables[temp_custom_orders][status][equals]=47&tables[temp_custom_orders][mark_as][equals]=null&tables[temp_custom_orders][assigned_to][equals]=null&tables[temp_custom_order_products][type][exists]=null&tables[temp_custom_orders][texas_order%2Ctax_exempt][equals_and]=null%2Cnull&tables[temp_custom_orders][problem_order][equals]=null&tables[temp_custom_orders][rma_flag][equals]=null&page=1',
			  '',
			  0
			  ),
			  'TPT_STATUS_IH_IN_PRODUCTION' => array(
			  TPT_STATUS_IH_IN_PRODUCTION,
			  //'/qo-list?tables[temp_custom_orders][status][equals]=27&tables[temp_custom_orders][mark_as][equals]=0&tables[temp_custom_orders][assigned_to][equals]=0&tables[temp_custom_order_products][type][exists]=0&tables[temp_custom_orders][tax_class][equals]=0&tables[temp_custom_orders][problem_order][equals]=0&tables[temp_custom_orders][rma_flag][equals]=0&page=1',
			  '/qo-list?tables[temp_custom_orders][status][equals]=27&tables[temp_custom_orders][mark_as][equals]=null&tables[temp_custom_orders][assigned_to][equals]=null&tables[temp_custom_order_products][type][exists]=null&tables[temp_custom_orders][texas_order%2Ctax_exempt][equals_and]=null%2Cnull&tables[temp_custom_orders][problem_order][equals]=null&tables[temp_custom_orders][rma_flag][equals]=null&page=1',
			  '',
			  0
			  ),

			  ////////////////////////////////////////////////
			  'TPT_STATUS_IH_POST_PRODUCTION' => array(
			  TPT_STATUS_IH_POST_PRODUCTION,
			  //'/qo-list?tables[temp_custom_orders][status][equals]=28&tables[temp_custom_orders][mark_as][equals]=0&tables[temp_custom_orders][assigned_to][equals]=0&tables[temp_custom_order_products][type][exists]=0&tables[temp_custom_orders][tax_class][equals]=0&tables[temp_custom_orders][problem_order][equals]=0&tables[temp_custom_orders][rma_flag][equals]=0&page=1',
			  '/qo-list?tables[temp_custom_orders][status][equals]=28&tables[temp_custom_orders][mark_as][equals]=null&tables[temp_custom_orders][assigned_to][equals]=null&tables[temp_custom_order_products][type][exists]=null&tables[temp_custom_orders][texas_order%2Ctax_exempt][equals_and]=null%2Cnull&tables[temp_custom_orders][problem_order][equals]=null&tables[temp_custom_orders][rma_flag][equals]=null&page=1',
			  '',
			  0
			  ),

			  'TPT_STATUS_OS_ORDER_PROBLEM' => array(
			  TPT_STATUS_OS_ORDER_PROBLEM,
			  //'/qo-list?tables[temp_custom_orders][status][equals]=38&tables[temp_custom_orders][mark_as][equals]=0&tables[temp_custom_orders][assigned_to][equals]=0&tables[temp_custom_order_products][type][exists]=0&tables[temp_custom_orders][tax_class][equals]=0&tables[temp_custom_orders][problem_order][equals]=0&tables[temp_custom_orders][rma_flag][equals]=0&page=1',
			  '/qo-list?tables[temp_custom_orders][status][equals]=38&tables[temp_custom_orders][mark_as][equals]=null&tables[temp_custom_orders][assigned_to][equals]=null&tables[temp_custom_order_products][type][exists]=null&tables[temp_custom_orders][texas_order%2Ctax_exempt][equals_and]=null%2Cnull&tables[temp_custom_orders][problem_order][equals]=null&tables[temp_custom_orders][rma_flag][equals]=null&page=1',
			  '',
			  0
			  ),
			 */
			'TPT_STATUS_OS_MFG_REVIEW' => array(
				TPT_STATUS_OS_MFG_REVIEW,
				//'/qo-list?tables[temp_custom_orders][status][equals]=38&tables[temp_custom_orders][mark_as][equals]=0&tables[temp_custom_orders][assigned_to][equals]=0&tables[temp_custom_order_products][type][exists]=0&tables[temp_custom_orders][tax_class][equals]=0&tables[temp_custom_orders][problem_order][equals]=0&tables[temp_custom_orders][rma_flag][equals]=0&page=1',
				'/qo-list?tables[temp_custom_orders][status][equals]=15&tables[temp_custom_order_products][type][exists]=null&page=1&tables[temp_custom_orders][order_id][order]=desc&tables[temp_custom_orders][customer_name][order]=null&tables[temp_custom_orders][timestamp][order]=null',
				'',
				0
			),
			'TPT_STATUS_OS_IN_PRODUCTION' => array(
				TPT_STATUS_OS_IN_PRODUCTION,
				//'/qo-list?tables[temp_custom_orders][status][equals]=38&tables[temp_custom_orders][mark_as][equals]=0&tables[temp_custom_orders][assigned_to][equals]=0&tables[temp_custom_order_products][type][exists]=0&tables[temp_custom_orders][tax_class][equals]=0&tables[temp_custom_orders][problem_order][equals]=0&tables[temp_custom_orders][rma_flag][equals]=0&page=1',
				'/qo-list?tables[temp_custom_orders][status][equals]=16&tables[temp_custom_order_products][type][exists]=null&page=1&tables[temp_custom_orders][order_id][order]=desc&tables[temp_custom_orders][customer_name][order]=null&tables[temp_custom_orders][timestamp][order]=null',
				'',
				0
			),
			'TPT_STATUS_OS_SHIPPED_TO_AMZG' => array(
				TPT_STATUS_OS_SHIPPED_TO_AMZG,
				//'/qo-list?tables[temp_custom_orders][status][equals]=38&tables[temp_custom_orders][mark_as][equals]=0&tables[temp_custom_orders][assigned_to][equals]=0&tables[temp_custom_order_products][type][exists]=0&tables[temp_custom_orders][tax_class][equals]=0&tables[temp_custom_orders][problem_order][equals]=0&tables[temp_custom_orders][rma_flag][equals]=0&page=1',
				'/qo-list?tables[temp_custom_orders][status][equals]=17&tables[temp_custom_order_products][type][exists]=null&page=1&tables[temp_custom_orders][order_id][order]=desc&tables[temp_custom_orders][customer_name][order]=null&tables[temp_custom_orders][timestamp][order]=null',
				'',
				0
			),
			'TPT_STATUS_OS_SHIPPED_TO_CUSTOMER' => array(
				TPT_STATUS_OS_SHIPPED_TO_CUSTOMER,
				//'/qo-list?tables[temp_custom_orders][status][equals]=38&tables[temp_custom_orders][mark_as][equals]=0&tables[temp_custom_orders][assigned_to][equals]=0&tables[temp_custom_order_products][type][exists]=0&tables[temp_custom_orders][tax_class][equals]=0&tables[temp_custom_orders][problem_order][equals]=0&tables[temp_custom_orders][rma_flag][equals]=0&page=1',
				'/qo-list?tables[temp_custom_orders][status][equals]=18&tables[temp_custom_order_products][type][exists]=null&page=1&tables[temp_custom_orders][order_id][order]=desc&tables[temp_custom_orders][customer_name][order]=null&tables[temp_custom_orders][timestamp][order]=null',
				'',
				0
			),
			/*
			  'TPT_STATUS_AMZG_RECEIVED' => array(
			  TPT_STATUS_AMZG_RECEIVED,
			  //'/qo-list?tables[temp_custom_orders][status][equals]=38&tables[temp_custom_orders][mark_as][equals]=0&tables[temp_custom_orders][assigned_to][equals]=0&tables[temp_custom_order_products][type][exists]=0&tables[temp_custom_orders][tax_class][equals]=0&tables[temp_custom_orders][problem_order][equals]=0&tables[temp_custom_orders][rma_flag][equals]=0&page=1',
			  '/qo-list?tables[temp_custom_orders][status][equals]=19&tables[temp_custom_order_products][type][exists]=null&page=1&tables[temp_custom_orders][order_id][order]=desc&tables[temp_custom_orders][customer_name][order]=null&tables[temp_custom_orders][timestamp][order]=null',
			  '',
			  0
			  ),
			  'TPT_STATUS_CUSTOMER_RECEIVED' => array(
			  TPT_STATUS_CUSTOMER_RECEIVED,
			  //'/qo-list?tables[temp_custom_orders][status][equals]=38&tables[temp_custom_orders][mark_as][equals]=0&tables[temp_custom_orders][assigned_to][equals]=0&tables[temp_custom_order_products][type][exists]=0&tables[temp_custom_orders][tax_class][equals]=0&tables[temp_custom_orders][problem_order][equals]=0&tables[temp_custom_orders][rma_flag][equals]=0&page=1',
			  '/qo-list?tables[temp_custom_orders][status][equals]=19&tables[temp_custom_order_products][type][exists]=null&page=1&tables[temp_custom_orders][order_id][order]=desc&tables[temp_custom_orders][customer_name][order]=null&tables[temp_custom_orders][timestamp][order]=null',
			  '',
			  0
			  ),
			 */
			'TPT_STATUS_OS_ORDER_PROBLEM' => array(
				TPT_STATUS_OS_ORDER_PROBLEM,
				//'/qo-list?tables[temp_custom_orders][status][equals]=38&tables[temp_custom_orders][mark_as][equals]=0&tables[temp_custom_orders][assigned_to][equals]=0&tables[temp_custom_order_products][type][exists]=0&tables[temp_custom_orders][tax_class][equals]=0&tables[temp_custom_orders][problem_order][equals]=0&tables[temp_custom_orders][rma_flag][equals]=0&page=1',
				'/qo-list?tables[temp_custom_orders][status][equals]=38&tables[temp_custom_order_products][type][exists]=null&page=1&tables[temp_custom_orders][order_id][order]=desc&tables[temp_custom_orders][customer_name][order]=null&tables[temp_custom_orders][timestamp][order]=null',
				'',
				0
			),
			'TPT_STATUS_OS_RE_PRODUCTION' => array(
				TPT_STATUS_OS_RE_PRODUCTION,
				//'/qo-list?tables[temp_custom_orders][status][equals]=38&tables[temp_custom_orders][mark_as][equals]=0&tables[temp_custom_orders][assigned_to][equals]=0&tables[temp_custom_order_products][type][exists]=0&tables[temp_custom_orders][tax_class][equals]=0&tables[temp_custom_orders][problem_order][equals]=0&tables[temp_custom_orders][rma_flag][equals]=0&page=1',
				'/qo-list?tables[temp_custom_orders][status][equals]=40&tables[temp_custom_order_products][type][exists]=null&page=1&tables[temp_custom_orders][order_id][order]=desc&tables[temp_custom_orders][customer_name][order]=null&tables[temp_custom_orders][timestamp][order]=null',
				'',
				0
			),
			'TPT_STATUS_OS_RE_SHIPPED' => array(
				TPT_STATUS_OS_RE_SHIPPED,
				//'/qo-list?tables[temp_custom_orders][status][equals]=38&tables[temp_custom_orders][mark_as][equals]=0&tables[temp_custom_orders][assigned_to][equals]=0&tables[temp_custom_order_products][type][exists]=0&tables[temp_custom_orders][tax_class][equals]=0&tables[temp_custom_orders][problem_order][equals]=0&tables[temp_custom_orders][rma_flag][equals]=0&page=1',
				'/qo-list?tables[temp_custom_orders][status][equals]=42&tables[temp_custom_order_products][type][exists]=null&page=1&tables[temp_custom_orders][order_id][order]=desc&tables[temp_custom_orders][customer_name][order]=null&tables[temp_custom_orders][timestamp][order]=null',
				'',
				0
			),
		);

		$marks = array(
			/*
			  'TPT_MARK_CALL_TODAY' => array(
			  TPT_MARK_CALL_TODAY,
			  //'/qo-list?tables[temp_custom_orders][status][equals]=0&tables[temp_custom_orders][mark_as][equals]=7&tables[temp_custom_orders][assigned_to][equals]=0&tables[temp_custom_order_products][type][exists]=0&tables[temp_custom_orders][tax_class][equals]=0&tables[temp_custom_orders][problem_order][equals]=0&tables[temp_custom_orders][rma_flag][equals]=0&page=1',
			  '/qo-list?tables[temp_custom_orders][status][equals]=null&tables[temp_custom_orders][mark_as][equals]=7&tables[temp_custom_orders][assigned_to][equals]=null&tables[temp_custom_order_products][type][exists]=null&tables[temp_custom_orders][texas_order%2Ctax_exempt][equals_and]=null%2Cnull&tables[temp_custom_orders][problem_order][equals]=null&tables[temp_custom_orders][rma_flag][equals]=null&page=1',
			  '',
			  0
			  ),
			  'TPT_MARK_AWAITING_PAYMENT' => array(
			  TPT_MARK_AWAITING_PAYMENT,
			  //'/qo-list?tables[temp_custom_orders][status][equals]=0&tables[temp_custom_orders][mark_as][equals]=3&tables[temp_custom_orders][assigned_to][equals]=0&tables[temp_custom_order_products][type][exists]=0&tables[temp_custom_orders][tax_class][equals]=0&tables[temp_custom_orders][problem_order][equals]=0&tables[temp_custom_orders][rma_flag][equals]=0&page=1',
			  '/qo-list?tables[temp_custom_orders][status][equals]=null&tables[temp_custom_orders][mark_as][equals]=3&tables[temp_custom_orders][assigned_to][equals]=null&tables[temp_custom_order_products][type][exists]=null&tables[temp_custom_orders][texas_order%2Ctax_exempt][equals_and]=null%2Cnull&tables[temp_custom_orders][problem_order][equals]=null&tables[temp_custom_orders][rma_flag][equals]=null&page=1',
			  '',
			  0
			  ),
			  'TPT_MARK_AWAITING_PAYMENT_PO' => array(
			  TPT_MARK_AWAITING_PAYMENT_PO,
			  //'/qo-list?tables[temp_custom_orders][status][equals]=0&tables[temp_custom_orders][mark_as][equals]=22&tables[temp_custom_orders][assigned_to][equals]=0&tables[temp_custom_order_products][type][exists]=0&tables[temp_custom_orders][tax_class][equals]=0&tables[temp_custom_orders][problem_order][equals]=0&tables[temp_custom_orders][rma_flag][equals]=0&page=1',
			  //'/manage-orders.php?filter_status=&page=1&id_sort=&name_sort=&email_sort=&band_type=&o_date=&mfg_sort=&need_date_sort=&search_term=&mfg=&tax=&w_class=&marked_status=22&page=1',
			  '/qo-list?tables[temp_custom_orders][status][equals]=null&tables[temp_custom_orders][mark_as][equals]=22&tables[temp_custom_orders][assigned_to][equals]=null&tables[temp_custom_order_products][type][exists]=null&tables[temp_custom_orders][texas_order%2Ctax_exempt][equals_and]=null%2Cnull&tables[temp_custom_orders][problem_order][equals]=null&tables[temp_custom_orders][rma_flag][equals]=null&page=1',
			  '',
			  0
			  )
			 */
		);
//$proof_ready = $tpt_vars['db']['handler']->getData($tpt_vars, '', '');
		$where_mfg = ' AND `assigned_to`="' . mysql_real_escape_string($mfg['name']) . '"';

		$sstats = array();
		$sshtml = '';
		foreach ($statuses as $key => $e) {
			$s = $e[0];
			$link = $vars['url']['handler']->wrap($vars, $e[1], true, 2);


			$df = array(
				'fields' => '*',
				'left_join_secondary' => 0,
				'count' => 1,
				'debug' => $e[3],
				//'limit'=>1,
				//'ordering'=>' `id` DESC',
				//'where' => array(str_replace('##s##', $s, $e[2]))
				'where' => array(' (`status`=' . $s . ' AND `quote_id`=0 AND `is_deleted`=0 AND `order_deleted`=0 ' . $where_mfg . ')')
			);
			if (!empty($e[2])) {
				$df['where'] = array($e[2]);
			}
			//tpt_dump($df, true);
			$q = $orders_module->getOrdersData($vars, $df, true);

			$sstats[$key] = $q['count'];
			//tpt_dump($statuses, true);
			$sname = $sts[$s]['status'];
			$scount = $q['count'];
			//tpt_dump($sname);
			//tpt_dump($scount);
			$sshtml .= <<< EOT
<a href="$link" class="padding-left-10 padding-right-10 display-inline-block color-white">
$sname: $scount
</a>
EOT;
		}

		foreach ($marks as $key => $e) {
			$s = $e[0];
			$link = $vars['url']['handler']->wrap($vars, $e[1], true, 2);
			$df = array(
				'fields' => '*',
				'left_join_secondary' => 0,
				'count' => 1,
				//'debug' => 1,
				//'limit'=>1,
				//'ordering'=>' `id` DESC',
				'where' => array(' (`mark_as`=' . $s . ' AND `quote_id`=0 AND `is_deleted`=0 ' . $where_mfg . ')')
			);
			//tpt_dump($df, true);
			$q = $orders_module->getOrdersData($vars, $df, true);
			//tpt_dump($q);

			$sstats[$key] = $q['count'];
			//tpt_dump($statuses, true);
			$sname = $mrks[$s]['label'];
			$scount = $q['count'];
			//tpt_dump($sname);
			//tpt_dump($scount);
			$sshtml .= <<< EOT
<a href="$link" class="padding-left-10 padding-right-10 display-inline-block color-white">
$sname: $scount
</a>
EOT;
		}

		$sshtml = <<< EOT
<div class="clearBoth text-align-center padding-top-5 padding-bottom-5" style="background: #000 none;">
$sshtml
</div>
EOT;
		return $sshtml;
	}

	static function getBackendHeaderTopmost(&$vars) {
		$orders_module = getModule($vars, 'Orders');
		$status_module = getModule($vars, 'OrderStatus');
		$sts = $status_module->moduleData['id'];
		$mark_module = getModule($vars, 'OrderMark');
		$mrks = $mark_module->moduleData['id'];

		define('TPT_STATUS_PROOF_DESIGN_READY', '29');
		define('TPT_STATUS_PROOF_SENT_PENDING', '13');
		define('TPT_STATUS_CALLED_AND_EMAILED_PENDING', '6');
		define('TPT_STATUS_EMAILED_PENDING', '5');
		define('TPT_STATUS_NEEDS_FOLLOW_UP', '24');
		define('TPT_MARK_CALL_TODAY', '7');
		define('TPT_MARK_AWAITING_PAYMENT', '3');
		define('TPT_MARK_AWAITING_PAYMENT_PO', '22');
		define('TPT_STATUS_PAYPAL_PENDING', '33');
		define('TPT_STATUS_ORDERED_PENDING', '30');
		define('TPT_STATUS_PROOF_APPROVED', '14');
		define('TPT_STATUS_SUBMITTED_ONLINE', '1');
		define('TPT_STATUS_OS_ORDER_PROBLEM', '38');
		define('TPT_STATUS_AWAITING_PROOF', '12');

		define('TPT_STATUS_IH_PRINTED_PREPRODUCTION', '44');
		define('TPT_STATUS_IH_OUT_OF_STOCK_CROSS', '47');
		define('TPT_STATUS_IH_IN_PRODUCTION', '27');

		define('TPT_STATUS_IH_POST_PRODUCTION', '28');




		$statuses = array(
			'TPT_STATUS_SUBMITTED_ONLINE' => array(
				TPT_STATUS_SUBMITTED_ONLINE,
				//'/qo-list?tables[temp_custom_orders][status][equals]=1&tables[temp_custom_orders][mark_as][equals]=null&tables[temp_custom_orders][assigned_to][equals]=null&tables[temp_custom_order_products][type][exists]=null&tables[temp_custom_orders][tax_class][equals]=null&tables[temp_custom_orders][problem_order][equals]=null&tables[temp_custom_orders][rma_flag][equals]=null&page=1',
				'/qo-list?tables[temp_custom_orders][status][equals]=1&tables[temp_custom_orders][mark_as][equals]=null&tables[temp_custom_orders][assigned_to][equals]=null&tables[temp_custom_order_products][type][exists]=null&tables[temp_custom_orders][texas_order%2Ctax_exempt][equals_and]=null%2Cnull&tables[temp_custom_orders][problem_order][equals]=null&tables[temp_custom_orders][rma_flag][equals]=null&page=1',
				'',
				0
			),
			'TPT_STATUS_AWAITING_PROOF' => array(
				TPT_STATUS_AWAITING_PROOF,
				//'/qo-list?tables[temp_custom_orders][status][equals]=12&tables[temp_custom_orders][mark_as][equals]=null&tables[temp_custom_orders][assigned_to][equals]=null&tables[temp_custom_order_products][type][exists]=null&tables[temp_custom_orders][tax_class][equals]=null&tables[temp_custom_orders][problem_order][equals]=null&tables[temp_custom_orders][rma_flag][equals]=null&page=1',
				'/qo-list?tables[temp_custom_orders][status][equals]=12&tables[temp_custom_orders][mark_as][equals]=null&tables[temp_custom_orders][assigned_to][equals]=null&tables[temp_custom_order_products][type][exists]=null&tables[temp_custom_orders][texas_order%2Ctax_exempt][equals_and]=null%2Cnull&tables[temp_custom_orders][problem_order][equals]=null&tables[temp_custom_orders][rma_flag][equals]=null&page=1',
				'',
				0
			),
			'TPT_STATUS_PROOF_APPROVED' => array(
				TPT_STATUS_PROOF_APPROVED,
				//'/qo-list?tables[temp_custom_orders][status][equals]=14&tables[temp_custom_orders][mark_as][equals]=0&tables[temp_custom_orders][assigned_to][equals]=0&tables[temp_custom_order_products][type][exists]=0&tables[temp_custom_orders][tax_class][equals]=0&tables[temp_custom_orders][problem_order][equals]=0&tables[temp_custom_orders][rma_flag][equals]=0&page=1',
				'/qo-list?tables[temp_custom_orders][status][equals]=14&tables[temp_custom_orders][mark_as][equals]=null&tables[temp_custom_orders][assigned_to][equals]=null&tables[temp_custom_order_products][type][exists]=null&tables[temp_custom_orders][texas_order%2Ctax_exempt][equals_and]=null%2Cnull&tables[temp_custom_orders][problem_order][equals]=null&tables[temp_custom_orders][rma_flag][equals]=null&page=1',
				'',
				0
			),
			'TPT_STATUS_PROOF_DESIGN_READY' => array(
				TPT_STATUS_PROOF_DESIGN_READY,
				//'/qo-list?tables[temp_custom_orders][status][equals]=29&tables[temp_custom_orders][mark_as][equals]=0&tables[temp_custom_orders][assigned_to][equals]=0&tables[temp_custom_order_products][type][exists]=0&tables[temp_custom_orders][tax_class][equals]=0&tables[temp_custom_orders][problem_order][equals]=0&tables[temp_custom_orders][rma_flag][equals]=0&page=1',
				'/qo-list?tables[temp_custom_orders][status][equals]=29&tables[temp_custom_orders][mark_as][equals]=null&tables[temp_custom_orders][assigned_to][equals]=null&tables[temp_custom_order_products][type][exists]=null&tables[temp_custom_orders][texas_order%2Ctax_exempt][equals_and]=null%2Cnull&tables[temp_custom_orders][problem_order][equals]=null&tables[temp_custom_orders][rma_flag][equals]=null&page=1',
				'',
				0
			),
			'TPT_STATUS_PROOF_SENT_PENDING' => array(
				TPT_STATUS_PROOF_SENT_PENDING,
				//'/qo-list?tables[temp_custom_orders][status][equals]=13&tables[temp_custom_orders][mark_as][equals]=0&tables[temp_custom_orders][assigned_to][equals]=0&tables[temp_custom_order_products][type][exists]=0&tables[temp_custom_orders][tax_class][equals]=0&tables[temp_custom_orders][problem_order][equals]=0&tables[temp_custom_orders][rma_flag][equals]=0&page=1',
				'/qo-list?tables[temp_custom_orders][status][equals]=13&tables[temp_custom_orders][mark_as][equals]=null&tables[temp_custom_orders][assigned_to][equals]=null&tables[temp_custom_order_products][type][exists]=null&tables[temp_custom_orders][texas_order%2Ctax_exempt][equals_and]=null%2Cnull&tables[temp_custom_orders][problem_order][equals]=null&tables[temp_custom_orders][rma_flag][equals]=null&page=1',
				'',
				0
			),
			'TPT_STATUS_CALLED_AND_EMAILED_PENDING' => array(
				TPT_STATUS_CALLED_AND_EMAILED_PENDING,
				//'/qo-list?tables[temp_custom_orders][status][equals]=6&tables[temp_custom_orders][mark_as][equals]=0&tables[temp_custom_orders][assigned_to][equals]=0&tables[temp_custom_order_products][type][exists]=0&tables[temp_custom_orders][tax_class][equals]=0&tables[temp_custom_orders][problem_order][equals]=0&tables[temp_custom_orders][rma_flag][equals]=0&page=1',
				'/qo-list?tables[temp_custom_orders][status][equals]=6&tables[temp_custom_orders][mark_as][equals]=null&tables[temp_custom_orders][assigned_to][equals]=null&tables[temp_custom_order_products][type][exists]=null&tables[temp_custom_orders][texas_order%2Ctax_exempt][equals_and]=null%2Cnull&tables[temp_custom_orders][problem_order][equals]=null&tables[temp_custom_orders][rma_flag][equals]=null&page=1',
				'',
				0
			),
			'TPT_STATUS_EMAILED_PENDING' => array(
				TPT_STATUS_EMAILED_PENDING,
				//'/qo-list?tables[temp_custom_orders][status][equals]=5&tables[temp_custom_orders][mark_as][equals]=0&tables[temp_custom_orders][assigned_to][equals]=0&tables[temp_custom_order_products][type][exists]=0&tables[temp_custom_orders][tax_class][equals]=0&tables[temp_custom_orders][problem_order][equals]=0&tables[temp_custom_orders][rma_flag][equals]=0&page=1',
				'/qo-list?tables[temp_custom_orders][status][equals]=5&tables[temp_custom_orders][mark_as][equals]=null&tables[temp_custom_orders][assigned_to][equals]=null&tables[temp_custom_order_products][type][exists]=null&tables[temp_custom_orders][texas_order%2Ctax_exempt][equals_and]=null%2Cnull&tables[temp_custom_orders][problem_order][equals]=null&tables[temp_custom_orders][rma_flag][equals]=null&page=1',
				'',
				0
			),
			'TPT_STATUS_NEEDS_FOLLOW_UP' => array(
				TPT_STATUS_NEEDS_FOLLOW_UP,
				//'/qo-list?tables[temp_custom_orders][status][equals]=24&tables[temp_custom_orders][mark_as][equals]=0&tables[temp_custom_orders][assigned_to][equals]=0&tables[temp_custom_order_products][type][exists]=0&tables[temp_custom_orders][tax_class][equals]=0&tables[temp_custom_orders][problem_order][equals]=0&tables[temp_custom_orders][rma_flag][equals]=0&page=1',
				'/qo-list?tables[temp_custom_orders][status][equals]=24&tables[temp_custom_orders][mark_as][equals]=null&tables[temp_custom_orders][assigned_to][equals]=null&tables[temp_custom_order_products][type][exists]=null&tables[temp_custom_orders][texas_order%2Ctax_exempt][equals_and]=null%2Cnull&tables[temp_custom_orders][problem_order][equals]=null&tables[temp_custom_orders][rma_flag][equals]=null&page=1',
				'',
				0
			),
			'TPT_STATUS_PAYPAL_PENDING' => array(
				TPT_STATUS_PAYPAL_PENDING,
				//'/qo-list?tables[temp_custom_orders][status][equals]=33&tables[temp_custom_orders][mark_as][equals]=0&tables[temp_custom_orders][assigned_to][equals]=0&tables[temp_custom_order_products][type][exists]=0&tables[temp_custom_orders][tax_class][equals]=0&tables[temp_custom_orders][problem_order][equals]=0&tables[temp_custom_orders][rma_flag][equals]=0&page=1',
				'/qo-list?tables[temp_custom_orders][status][equals]=33&tables[temp_custom_orders][mark_as][equals]=null&tables[temp_custom_orders][assigned_to][equals]=null&tables[temp_custom_order_products][type][exists]=null&tables[temp_custom_orders][texas_order%2Ctax_exempt][equals_and]=null%2Cnull&tables[temp_custom_orders][problem_order][equals]=null&tables[temp_custom_orders][rma_flag][equals]=null&page=1',
				'',
				0
			),
			'TPT_STATUS_ORDERED_PENDING' => array(
				TPT_STATUS_ORDERED_PENDING,
				//'/qo-list?tables[temp_custom_orders][status][equals]=30&tables[temp_custom_orders][mark_as][equals]=0&tables[temp_custom_orders][assigned_to][equals]=0&tables[temp_custom_order_products][type][exists]=0&tables[temp_custom_orders][tax_class][equals]=0&tables[temp_custom_orders][problem_order][equals]=0&tables[temp_custom_orders][rma_flag][equals]=0&page=1',
				'/qo-list?tables[temp_custom_orders][status][equals]=30&tables[temp_custom_orders][mark_as][equals]=null&tables[temp_custom_orders][assigned_to][equals]=null&tables[temp_custom_order_products][type][exists]=null&tables[temp_custom_orders][texas_order%2Ctax_exempt][equals_and]=null%2Cnull&tables[temp_custom_orders][problem_order][equals]=null&tables[temp_custom_orders][rma_flag][equals]=null&page=1',
				'',
				0
			),
//		'TPT_STATUS_PROOF_APPROVED' => array(
//			TPT_STATUS_PROOF_APPROVED,
//			'/custom-quotes.php?filter_status=14&page=1&id_sort=&name_sort=&email_sort=&band_type=&o_date=&mfg_sort=&need_date_sort=&search_term=&mfg=&marked_status=&product_type=&tax=&pbm_status=&rma_order=',
//			'',
//			0
//		),
			'TPT_STATUS_IH_PRINTED_PREPRODUCTION' => array(
				TPT_STATUS_IH_PRINTED_PREPRODUCTION,
				//'/qo-list?tables[temp_custom_orders][status][equals]=44&tables[temp_custom_orders][mark_as][equals]=0&tables[temp_custom_orders][assigned_to][equals]=0&tables[temp_custom_order_products][type][exists]=0&tables[temp_custom_orders][tax_class][equals]=0&tables[temp_custom_orders][problem_order][equals]=0&tables[temp_custom_orders][rma_flag][equals]=0&page=1',
				'/qo-list?tables[temp_custom_orders][status][equals]=44&tables[temp_custom_orders][mark_as][equals]=null&tables[temp_custom_orders][assigned_to][equals]=null&tables[temp_custom_order_products][type][exists]=null&tables[temp_custom_orders][texas_order%2Ctax_exempt][equals_and]=null%2Cnull&tables[temp_custom_orders][problem_order][equals]=null&tables[temp_custom_orders][rma_flag][equals]=null&page=1',
				'',
				0
			),
			'TPT_STATUS_IH_OUT_OF_STOCK_CROSS' => array(
				TPT_STATUS_IH_OUT_OF_STOCK_CROSS,
				//'/qo-list?tables[temp_custom_orders][status][equals]=47&tables[temp_custom_orders][mark_as][equals]=0&tables[temp_custom_orders][assigned_to][equals]=0&tables[temp_custom_order_products][type][exists]=0&tables[temp_custom_orders][tax_class][equals]=0&tables[temp_custom_orders][problem_order][equals]=0&tables[temp_custom_orders][rma_flag][equals]=0&page=1',
				'/qo-list?tables[temp_custom_orders][status][equals]=47&tables[temp_custom_orders][mark_as][equals]=null&tables[temp_custom_orders][assigned_to][equals]=null&tables[temp_custom_order_products][type][exists]=null&tables[temp_custom_orders][texas_order%2Ctax_exempt][equals_and]=null%2Cnull&tables[temp_custom_orders][problem_order][equals]=null&tables[temp_custom_orders][rma_flag][equals]=null&page=1',
				'',
				0
			),
			'TPT_STATUS_IH_IN_PRODUCTION' => array(
				TPT_STATUS_IH_IN_PRODUCTION,
				//'/qo-list?tables[temp_custom_orders][status][equals]=27&tables[temp_custom_orders][mark_as][equals]=0&tables[temp_custom_orders][assigned_to][equals]=0&tables[temp_custom_order_products][type][exists]=0&tables[temp_custom_orders][tax_class][equals]=0&tables[temp_custom_orders][problem_order][equals]=0&tables[temp_custom_orders][rma_flag][equals]=0&page=1',
				'/qo-list?tables[temp_custom_orders][status][equals]=27&tables[temp_custom_orders][mark_as][equals]=null&tables[temp_custom_orders][assigned_to][equals]=null&tables[temp_custom_order_products][type][exists]=null&tables[temp_custom_orders][texas_order%2Ctax_exempt][equals_and]=null%2Cnull&tables[temp_custom_orders][problem_order][equals]=null&tables[temp_custom_orders][rma_flag][equals]=null&page=1',
				'',
				0
			),
			////////////////////////////////////////////////
			'TPT_STATUS_IH_POST_PRODUCTION' => array(
				TPT_STATUS_IH_POST_PRODUCTION,
				//'/qo-list?tables[temp_custom_orders][status][equals]=28&tables[temp_custom_orders][mark_as][equals]=0&tables[temp_custom_orders][assigned_to][equals]=0&tables[temp_custom_order_products][type][exists]=0&tables[temp_custom_orders][tax_class][equals]=0&tables[temp_custom_orders][problem_order][equals]=0&tables[temp_custom_orders][rma_flag][equals]=0&page=1',
				'/qo-list?tables[temp_custom_orders][status][equals]=28&tables[temp_custom_orders][mark_as][equals]=null&tables[temp_custom_orders][assigned_to][equals]=null&tables[temp_custom_order_products][type][exists]=null&tables[temp_custom_orders][texas_order%2Ctax_exempt][equals_and]=null%2Cnull&tables[temp_custom_orders][problem_order][equals]=null&tables[temp_custom_orders][rma_flag][equals]=null&page=1',
				'',
				0
			),
			'TPT_STATUS_OS_ORDER_PROBLEM' => array(
				TPT_STATUS_OS_ORDER_PROBLEM,
				//'/qo-list?tables[temp_custom_orders][status][equals]=38&tables[temp_custom_orders][mark_as][equals]=0&tables[temp_custom_orders][assigned_to][equals]=0&tables[temp_custom_order_products][type][exists]=0&tables[temp_custom_orders][tax_class][equals]=0&tables[temp_custom_orders][problem_order][equals]=0&tables[temp_custom_orders][rma_flag][equals]=0&page=1',
				'/qo-list?tables[temp_custom_orders][status][equals]=38&tables[temp_custom_orders][mark_as][equals]=null&tables[temp_custom_orders][assigned_to][equals]=null&tables[temp_custom_order_products][type][exists]=null&tables[temp_custom_orders][texas_order%2Ctax_exempt][equals_and]=null%2Cnull&tables[temp_custom_orders][problem_order][equals]=null&tables[temp_custom_orders][rma_flag][equals]=null&page=1',
				'',
				0
			),
		);

		$marks = array(
			'TPT_MARK_CALL_TODAY' => array(
				TPT_MARK_CALL_TODAY,
				//'/qo-list?tables[temp_custom_orders][status][equals]=0&tables[temp_custom_orders][mark_as][equals]=7&tables[temp_custom_orders][assigned_to][equals]=0&tables[temp_custom_order_products][type][exists]=0&tables[temp_custom_orders][tax_class][equals]=0&tables[temp_custom_orders][problem_order][equals]=0&tables[temp_custom_orders][rma_flag][equals]=0&page=1',
				'/qo-list?tables[temp_custom_orders][status][equals]=null&tables[temp_custom_orders][mark_as][equals]=7&tables[temp_custom_orders][assigned_to][equals]=null&tables[temp_custom_order_products][type][exists]=null&tables[temp_custom_orders][texas_order%2Ctax_exempt][equals_and]=null%2Cnull&tables[temp_custom_orders][problem_order][equals]=null&tables[temp_custom_orders][rma_flag][equals]=null&page=1',
				'',
				0
			),
			'TPT_MARK_AWAITING_PAYMENT' => array(
				TPT_MARK_AWAITING_PAYMENT,
				//'/qo-list?tables[temp_custom_orders][status][equals]=0&tables[temp_custom_orders][mark_as][equals]=3&tables[temp_custom_orders][assigned_to][equals]=0&tables[temp_custom_order_products][type][exists]=0&tables[temp_custom_orders][tax_class][equals]=0&tables[temp_custom_orders][problem_order][equals]=0&tables[temp_custom_orders][rma_flag][equals]=0&page=1',
				'/qo-list?tables[temp_custom_orders][status][equals]=null&tables[temp_custom_orders][mark_as][equals]=3&tables[temp_custom_orders][assigned_to][equals]=null&tables[temp_custom_order_products][type][exists]=null&tables[temp_custom_orders][texas_order%2Ctax_exempt][equals_and]=null%2Cnull&tables[temp_custom_orders][problem_order][equals]=null&tables[temp_custom_orders][rma_flag][equals]=null&page=1',
				'',
				0
			),
			'TPT_MARK_AWAITING_PAYMENT_PO' => array(
				TPT_MARK_AWAITING_PAYMENT_PO,
				//'/qo-list?tables[temp_custom_orders][status][equals]=0&tables[temp_custom_orders][mark_as][equals]=22&tables[temp_custom_orders][assigned_to][equals]=0&tables[temp_custom_order_products][type][exists]=0&tables[temp_custom_orders][tax_class][equals]=0&tables[temp_custom_orders][problem_order][equals]=0&tables[temp_custom_orders][rma_flag][equals]=0&page=1',
				//'/manage-orders.php?filter_status=&page=1&id_sort=&name_sort=&email_sort=&band_type=&o_date=&mfg_sort=&need_date_sort=&search_term=&mfg=&tax=&w_class=&marked_status=22&page=1',
				'/qo-list?tables[temp_custom_orders][status][equals]=null&tables[temp_custom_orders][mark_as][equals]=22&tables[temp_custom_orders][assigned_to][equals]=null&tables[temp_custom_order_products][type][exists]=null&tables[temp_custom_orders][texas_order%2Ctax_exempt][equals_and]=null%2Cnull&tables[temp_custom_orders][problem_order][equals]=null&tables[temp_custom_orders][rma_flag][equals]=null&page=1',
				'',
				0
			)
		);
//$proof_ready = $tpt_vars['db']['handler']->getData($tpt_vars, '', '');

		$sstats = array();
		$sshtml = '';
		foreach ($statuses as $key => $e) {
			$s = $e[0];
			$link = $vars['url']['handler']->wrap($vars, $e[1], true, 2);
			$df = array(
				'fields' => '*',
				'left_join_secondary' => 0,
				'count' => 1,
				'debug' => $e[3],
				//'limit'=>1,
				//'ordering'=>' `id` DESC',
				//'where' => array(str_replace('##s##', $s, $e[2]))
				'where' => array(' (`status`=' . $s . ' AND `quote_id`=0 AND `is_deleted`=0)')
			);
			if (!empty($e[2])) {
				$df['where'] = array($e[2]);
			}
			//tpt_dump($df, true);
			$q = $orders_module->getOrdersData($vars, $df, true);

			$sstats[$key] = $q['count'];
			//tpt_dump($statuses, true);
			$sname = $sts[$s]['status'];
			$scount = $q['count'];
			//tpt_dump($sname);
			//tpt_dump($scount);
			$sshtml .= <<< EOT
<a href="$link" class="padding-left-10 padding-right-10 display-inline-block color-white">
$sname: $scount
</a>
EOT;
		}

		foreach ($marks as $key => $e) {
			$s = $e[0];
			$link = $vars['url']['handler']->wrap($vars, $e[1], true, 2);
			$df = array(
				'fields' => '*',
				'left_join_secondary' => 0,
				'count' => 1,
				//'debug' => 1,
				//'limit'=>1,
				//'ordering'=>' `id` DESC',
				'where' => array(' (`mark_as`=' . $s . ' AND `quote_id`=0 AND `is_deleted`=0)')
			);
			//tpt_dump($df, true);
			$q = $orders_module->getOrdersData($vars, $df, true);
			//tpt_dump($q);

			$sstats[$key] = $q['count'];
			//tpt_dump($statuses, true);
			$sname = $mrks[$s]['label'];
			$scount = $q['count'];
			//tpt_dump($sname);
			//tpt_dump($scount);
			$sshtml .= <<< EOT
<a href="$link" class="padding-left-10 padding-right-10 display-inline-block color-white">
$sname: $scount
</a>
EOT;
		}

		$sshtml = <<< EOT
<div class="clearBoth text-align-center" style="background: #000 none;">
$sshtml
</div>
EOT;
		return $sshtml;
	}

	static function getBackendHeader(&$vars) {
		$orders_module = getModule($vars, 'Orders');
		$status_module = getModule($vars, 'OrderStatus');
		$mark_module = getModule($vars, 'OrderMark');
		$mfg_module = getModule($vars, 'Manufacturers');
		$tax_module = getModule($vars, 'Tax');
		$type_module = getModule($vars, 'BandType');
		$db = $vars['db']['handler'];
		//tpt_dump($vars['environment']);
		//tpt_dump($vars['user']['username']);
		//tpt_dump($vars['user']['data']['access_level']);
		if (!tpt_current_user::authorize_block($vars, 'TPT_ACCESS_BACKEND_HEADER_HTML')) {
			return 'ACCESS DENIED';
		}

		$input = $_GET;

		$sshtml = '';
		if (tpt_current_user::authorize_block($vars, 'TPT_ACCESS_BACKEND_HEADER_TOPMOST_HTML')) {
			$sshtml = self::getBackendHeaderTopmost($vars);
		} else {
			$sshtml = self::getBackendHeaderTopmostMfg($vars);
		}

		$logout_url = $vars['url']['handler']->wrap($vars, '/index.php?action=logout&type=admin', true, 2);

		$page = max(intval((!empty($vars['template_data']['page']) ? $vars['template_data']['page'] + 1 : 1), 10), 1);
		if (empty($page)) {
			$page = '';
		}

		$select_class = ' class="width-220"';
		$sstatus = !empty($input['tables']['temp_custom_orders']['status']['equals']) ? $input['tables']['temp_custom_orders']['status']['equals'] : '';
		$status_select = $status_module->Admin_Filters_Select($vars, $sstatus, $select_class);
		$smark = !empty($input['tables']['temp_custom_orders']['mark_as']['equals']) ? $input['tables']['temp_custom_orders']['mark_as']['equals'] : '';
		$mark_select = $mark_module->Admin_Filters_Select($vars, $smark, $select_class);
		$smfg = !empty($input['tables']['temp_custom_orders']['assigned_to']['equals']) ? $input['tables']['temp_custom_orders']['assigned_to']['equals'] : '';
		$mfg_select = $mfg_module->Admin_Filters_Select($vars, $smfg, $select_class);
		$stype = !empty($input['tables']['temp_custom_order_products']['type']['exists']) ? $input['tables']['temp_custom_order_products']['type']['exists'] : '';
		$type_select = $type_module->Admin_Filters_Select($vars, $stype, $select_class);
		$stax = !empty($input['tables']['temp_custom_orders']['texas_order,tax_exempt']['equals_and']) ? $input['tables']['temp_custom_orders']['texas_order,tax_exempt']['equals_and'] : '';
		$tax_select = $tax_module->Admin_Filters_Select($vars, $stax, $select_class);
		$sproblem = !empty($input['tables']['temp_custom_orders']['problem_order']['equals']) ? $input['tables']['temp_custom_orders']['problem_order']['equals'] : '';
		$problem_select = $orders_module->Admin_Filters_Select_Problem($vars, $sproblem, $select_class);
		$srma = !empty($input['tables']['temp_custom_orders']['rma_flag']['equals']) ? $input['tables']['temp_custom_orders']['rma_flag']['equals'] : '';
		$rma_select = $orders_module->Admin_Filters_Select_RMA($vars, $srma, $select_class);


		//$search = !empty($input['search_input'])?htmlspecialchars($input['search_input']):'';
		//$search_name = !empty($input['search_by'])?htmlspecialchars($input['search_by']):'SEARCH_ALL';
		//tpt_dump($vars['template_data']['ordering'], true);
		$order_customer_name = '';
		$order_timestamp = '';
		$order_id = '';
		$order_order_id = 'desc';
		$order_customer_name = '';
		$order_timestamp = '';
		$order_assigned_to = '';
		if (!empty($vars['template_data']['ordering'])) {
			//extract($vars['template_data']['ordering'], EXTR_PREFIX_ALL, 'order_');
			foreach ($vars['template_data']['ordering'] as $name => $order) {
				${'order_' . $name} = $order;
			}
		} else {
			//extract($vars['config']['backend']['header']['order_by'], EXTR_PREFIX_ALL, 'order_');
			foreach ($vars['config']['backend']['header']['order_by'] as $name => $order) {
				${'order_' . $name} = $order;
			}
		}
		//tpt_dump(get_defined_vars(), true);

		$search = '';
		$search_name = '';
		$search_opt_index = 0;
		$i = 1;
		$sopts = $vars['config']['backend']['header']['search_by_options_mfg'];
		$s = $vars['config']['backend']['header']['search_by_options_mfg'];
		if (tpt_current_user::authorize_block($vars, 'TPT_ACCESS_BACKEND_ADVANCED_LIST_QUOTE_PAGE_LAYOUT')) {
			$sopts = $vars['config']['backend']['header']['search_by_options'];
			$s = $vars['config']['backend']['header']['search_by_options'];
		}
		//tpt_dump($input);
		if (!empty($input['SEARCH_ALL'])) {
			$search = htmlspecialchars($input['SEARCH_ALL']);
			$search_name = 'SEARCH_ALL';
		} else {
			array_shift($s);
			foreach ($s as $option) {
				$rel = explode('[', substr($option[0], 0, strlen($option[0]) - 1));
				/*

				  while($key = array_pop()) {
				  if (isset($input[$rel])) {
				  $search = htmlspecialchars($input[$rel]);
				  $search_name = htmlspecialchars($rel);
				  }
				  }
				 */
				$_tbls = array_shift($rel);
				$table = array_shift($rel);
				$table = substr($table, 0, strlen($table) - 1);
				$field = array_shift($rel);
				$field = substr($field, 0, strlen($field) - 1);
				$rel = array_shift($rel);
				//$rel = substr($rel, 0, strlen($rel)-1);
				//tpt_dump($input);
				//tpt_dump($_tbls);
				//tpt_dump($table);
				//tpt_dump($field);
				//tpt_dump($rel);
				/*
				  switch($table) {
				  case 'temp_custom_orders':
				  break;
				  case 'temp_custom_order_products':
				  break;
				  }
				 */
				if (isset($input[$_tbls][$table][$field][$rel])) {
					$search = htmlspecialchars($input[$_tbls][$table][$field][$rel]);
					$search_name = htmlspecialchars($_tbls . '[' . $table . '][' . $field . '][' . $rel . ']');
					$search_opt_index = $i;
				}

				$i++;
			}
		}
		//tpt_dump($input, true);


		$search_by_select = tpt_html::createPlainSelect($vars, 'search_by', $sopts, $search_opt_index, 'id="search_by" onchange="document.getElementById(\'search\').name=this.options[this.selectedIndex].value;"');
		//tpt_dump($search_by_select, true);
		//tpt_dump($_SESSION);

		$query = <<< EOT
SELECT
	`Admin_Name`
FROM
	`temp_admins`
WHERE
	`Admin_Role` = "sales"
	AND
	`Admin_Status` != 0
EOT;
		$db->query($query);
		$adm_level = $db->fetch_assoc_list();
		$sp_select = '';
		if (!empty($_SESSION['admin_level']) && ($_SESSION['admin_level'] == 'super')) {
			$res = array();
			foreach ($adm_level as $adm_level_res) {
				$adm_name = $adm_level_res['Admin_Name'];
				$status_q = <<< EOT
SELECT
	*
FROM
	`info`
WHERE
	`fullname` = "$adm_name"
ORDER BY
	`timestamp` DESC
LIMIT 1
EOT;
				$db->query($status_q);

				if ($db->num_rows()) {
					$status_q_res = $db->fetch_array();
					$inout = $status_q_res['inout'];
					if ($inout == 'in') {
						$span_color = '#009900';
					} else if ($inout == 'out') {
						$span_color = '#FF0000';
					} else if ($inout == 'break') {
						$span_color = '#FF9900';
					} else if ($inout == 'lunch') {
						$span_color = '#0000FF';
					}

					$res[] = '<option value="' . $adm_level_res['Admin_Name'] . ' :: ' . $status_q_res['inout'] . '" style="background-color:' . $span_color . ';">' . $adm_level_res['Admin_Name'] . ' :: ' . $status_q_res['inout'] . '</opption>';
				}
			}
			$res = implode($res);
			$sp_select .= <<< EOT
<select class="style" name="admin_status" id="admin_status">
	<option value="">View...</option>
	$res
</select>
EOT;
		}

		$timeclock_url = $vars['url']['handler']->wrap($vars, '/timeclock/timeclock.php', true, 2);
		$logout_action_url = $vars['url']['handler']->wrap($vars, '/login-register');
		$goto_action_url = $vars['url']['handler']->wrap($vars, '/qo-edit');
		$filter_action_url = $vars['url']['handler']->wrap($vars, '/qo-list', true, 2);
		$clear_url = $vars['url']['handler']->wrap($vars, '/qo-list', true, 2);
		$base_url = TPT_BACKEND_BASE_URL;
		$images_url = TPT_BACKEND_IMAGES_URL;

		$tdate = date('m-d-Y g:i A');

		$admin_name = $vars['user']['data']['fname'];

		$classes = array(
		);
		$phtml = <<< EOT
&nbsp;<img width="9px" border="0" style="vertical-align: middle;" src="$images_url/white-down.png">
EOT;
		$navigation = '';
		//if(tpt_current_user::authorize_block($vars, 'TPT_ACCESS_NEW_BACKEND_NAVIGATION')) {
		//	$navigation = tpt_navigation::getMenuHTML($vars, 'tpt_navigation_backend2', 2, $classes, $phtml);
		//} else {
		$navigation = tpt_navigation::getMenuHTML($vars, 'tpt_navigation_backend', 2, $classes, $phtml);
		//}



		$filters = <<< EOT
						<div class="padding-top-2 padding-bottom-2 padding-left-4">
							<form id="filter" method="get" accept-encoding="utf8" action="$filter_action_url">
							<div>

								<div class="clearFix padding-top-2 padding-bottom-2">
									<div class="width-80 float-left height-22 line-height-22 white-space-nowrap text-align-right">Order Status:</div>
									<div class=" float-left height-22">
										$status_select
									</div>
									<div class="clear-left width-80 float-left height-22 line-height-22 white-space-nowrap text-align-right">Rush Orders:</div>
									<div class=" float-left height-22">
										$mark_select
									</div>
									<div class="clear-left width-80 float-left height-22 line-height-22 white-space-nowrap text-align-right">Product Filter:</div>
									<div class=" float-left height-22">
										$type_select
									</div>
									<div class="clear-left width-80 float-left height-22 line-height-22 white-space-nowrap text-align-right"></div>
									<div class="float-right height-22 padding-right-20">
										<a href="$clear_url">Clear</a>
									</div>
								</div>

							</div>


							<input id="page" type="hidden" name="page" value="$page" />
							<input id="search" type="hidden" name="$search_name" value="$search" />

							<input id="order_order_id" type="hidden" name="tables[temp_custom_orders][order_id][order]" value="$order_order_id" />
							<input id="order_customer_name" type="hidden" name="tables[temp_custom_orders][customer_name][order]" value="$order_customer_name" />
							<input id="order_timestamp" type="hidden" name="tables[temp_custom_orders][timestamp][order]" value="$order_timestamp" />
							</form>

						</div>
EOT;
		if (tpt_current_user::authorize_block($vars, 'TPT_ACCESS_ALL_BACKEND_FILTERS')) {
			$filters = <<< EOT
						<div class="padding-top-2 padding-bottom-2 padding-left-4">
							<form id="filter" method="get" accept-encoding="utf8" action="$filter_action_url">
							<div>

								<div class="clearFix padding-top-2 padding-bottom-2">
									<div class="width-80 float-left height-22 line-height-22 white-space-nowrap text-align-right">Order Status:</div>
									<div class=" float-left height-22">
										$status_select
									</div>
									<div class="clear-left width-80 float-left height-22 line-height-22 white-space-nowrap text-align-right">Marked As:</div>
									<div class=" float-left height-22">
										$mark_select
									</div>
									<div class="clear-left width-80 float-left height-22 line-height-22 white-space-nowrap text-align-right">MFG Filter:</div>
									<div class=" float-left height-22">
										$mfg_select
									</div>
									<div id="add_filters_trigger1" class="clear-left width-80 float-left height-22 line-height-22 white-space-nowrap">&nbsp;</div>
									<div id="add_filters_trigger2" class=" float-left height-22">
										<a class="display-inline-block" href="javascript:void(0);" onclick="addClass(document.getElementById('add_filters_trigger1'), 'display-none');addClass(document.getElementById('add_filters_trigger2'), 'display-none'); removeClass(document.getElementById('add_filters'), 'display-none');">
											Show All Filters
											<img width="7px" border="0" style="vertical-align: middle;" src="$images_url/arrow-down.png">
										</a> &nbsp; &nbsp; &nbsp; <a href="$clear_url">Clear</a>
									</div>
									<div class="display-none" id="add_filters">
										<div class="clear-left width-80 float-left height-22 line-height-22 white-space-nowrap text-align-right">Product Filter:</div>
										<div class=" float-left height-22">
											$type_select
										</div>
										<div class="clear-left width-80 float-left height-22 line-height-22 white-space-nowrap text-align-right">Tax Filter:</div>
										<div class=" float-left height-22">
											$tax_select
										</div>
										<div class="clear-left width-80 float-left height-22 line-height-22 white-space-nowrap text-align-right">Problem Filter:</div>
										<div class=" float-left height-22">
											$problem_select
										</div>
										<div class="clear-left width-80 float-left height-22 line-height-22 white-space-nowrap text-align-right">RMA Filter:</div>
										<div class=" float-left height-22">
											$rma_select
										</div>

										<div class="clear-left width-80 float-left height-22 line-height-22 white-space-nowrap text-align-right"></div>
										<div class="float-right height-22 padding-right-80">
											<a href="$clear_url">Clear</a>
										</div>
									</div>
								</div>

							</div>


							<input id="page" type="hidden" name="page" value="$page" />
							<input id="search" type="hidden" name="$search_name" value="$search" />

							<input id="order_id" type="hidden" name="tables[temp_custom_orders][id][order]" value="$order_id" />
							<input id="order_order_id" type="hidden" name="tables[temp_custom_orders][order_id][order]" value="$order_order_id" />
							<input id="order_customer_name" type="hidden" name="tables[temp_custom_orders][customer_name][order]" value="$order_customer_name" />
							<input id="order_timestamp" type="hidden" name="tables[temp_custom_orders][timestamp][order]" value="$order_timestamp" />
							<input id="order_assigned_to" type="hidden" name="tables[temp_custom_orders][assigned_to][order]" value="$order_assigned_to" />
							</form>

						</div>
EOT;
		}

		$goto_forms = <<< EOT
								<form id="goto" method="get" accept-encoding="utf8" action="">
									<div class="clearFix padding-top-2 padding-bottom-2">
										<div class="float-left width-90 height-22 text-align-right">
											<div class="height-22 line-height-22">Go to Order ID:</div>
										</div>
										<div class="float-left height-22">
											<input type="text" id="go_to_order" name="go_to_order" class="style width-95">
										</div>
									</div>
								</form>
EOT;
		if (tpt_current_user::authorize_block($vars, 'TPT_ACCESS_ALL_BACKEND_GOTO_FORMS')) {
			//<form id="goto" method="get" accept-encoding="utf8" action="$goto_action_url" target="_blank">
			$goto_forms = <<< EOT
								<form id="goto" method="get" accept-encoding="utf8" action="">
									<div class="clearFix padding-top-2 padding-bottom-2 white-space-nowrap">
										<div>
											<div class="float-left width-90 height-22 line-height-22 text-align-right">Go to Quote ID:</div>
											<div class="float-left height-22">
												<input id="go_to_quote" type="text" name="go_to_quote" class="style width-95">
											</div>
											<div class="float-left clear-left width-90 height-22 line-height-22 text-align-right">Go to Order ID:</div>
											<div class="float-left height-22">
												<input type="text" id="go_to_order" name="go_to_order" class="style width-95">
											</div>
											<div class="float-left clear-left width-90 height-22 line-height-22 text-align-right">Go to Cart ID:</div>
											<div class="float-left height-22">
												<input id="go_to_cart" type="text" name="go_to_cart" class="style width-95">
											</div>
										</div>
									</div>
								</form>
EOT;
		}

		$sales_admin_links = '';
		if (tpt_current_user::authorize_block($vars, 'TPT_ACCESS_BACKEND_HEADER_SALES_ADMIN_LINKS')) {
			$sales_admin_links = <<< EOT
				<div>
					<div class="padding-top-2 padding-bottom-2">
						<span class="font-weight-bold font-size-11">Sales person status&nbsp;-&nbsp;</span>
						$sp_select
					</div>
					<div class="padding-top-2 padding-bottom-2 font-weight-bold">
						<span>$tdate</span>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<a style="text-decoration:none; color:#231E66;" href="$timeclock_url">Punch <span style="font-size:14px;">In/Out</span> Time</a>
					</div>
				</div>
EOT;
		}

		//var_dump($vars['admin']);
		$templ = '';
		$fpath = '';
		if (tpt_current_user::authorize_block($vars, 'TPT_ACCESS_BACKEND_HEADER_SALES_ADMIN_LINKS')) {
			$fpath = TPT_BACKEND_TEMPLATES_DIR . DIRECTORY_SEPARATOR . 'list-quotes-orders-legend.tpt.php';
		} else {
			$fpath = TPT_BACKEND_TEMPLATES_DIR . DIRECTORY_SEPARATOR . 'list-quotes-orders-legend-mfg.tpt.php';
		}
		$evars = tpt_functions::f_get_defined_vars($vars, get_defined_vars());
		$fvars = tpt_functions::f_include_once($vars, $fpath, $evars);
		extract($fvars);
		$legend = $templ;



		$admin_header = <<< EOT
$sshtml
<div class="clearBoth" style="background: #f4e7cf none;">


	<div class="float-right">
		<div class="padding-left-2 padding-right-5 float-right">
			<div class="font-size-11">
				<div class="padding-top-2 padding-bottom-2 clearFix">
					<div class="float-right">
						<div class="padding-left-5">
							<div class="padding-top-6 padding-bottom-6 white-space-nowrap text-align-right">
								Welcome back, <span class="font-weight-bold">$admin_name</span>
							</div>
							<div class="clearFix">
								<div class="float-right">
									<!--form accept-charset="utf-8" action="$logout_action_url" class="display-inline-block" method="POST">
										<input type="hidden" value="user.logout" name="task">
										<input type="submit" style="transition: background-color 0.3s ease 0s, color 0.3s ease 0s, width 0.3s ease 0s, border-width 0.3s ease 0s, border-color 0.3s ease 0s;background-color: #9e7e4f;border: 1px solid #8f6e40;color: #ffffff;" class="margin-0 padding-top-0 padding-right-6 padding-bottom-2 padding-left-6 font-weight-bold font-size-13 text-align-center" href="$base_url/index.php?action=logout&amp;type=admin" value="Logout" />
									</form-->
									<a class="linkbtn" href="$logout_url">Logout</a>
								</div>
							</div>
						</div>
					</div>
					<div class="float-right">
						<a href="$base_url/qo-list" class="display-block">
							<img style="vertical-align:text-top;" order="0" src="$images_url/footer-logo.png">
						</a>
					</div>
				</div>
				$sales_admin_links
			</div>
		</div>

	</div>



	<div class="float-right" style="max-width: 29%;">
		$legend
	</div>



	<div class="overflow-hidden clearFix" style="background: #E0D4D4 none;border-right:solid 1px #c4c4c4;border-left:solid 1px #c4c4c4;">
		<div class="float-left" style="max-width: 56%;">
			<div class="padding-left-2 padding-right-2" style=" border-right: 1px solid #FFF;">
				$filters
			</div>
		</div>
		<div class="float-left" style="max-width: 44%;">
			<div class="padding-left-10 padding-right-10" style="border-left: 1px solid #FFF; margin-left: -1px;">

				<div class="padding-top-5 padding-bottom-10 clearFix" style="border-bottom: 1px solid #FFF;">
					<div class="float-left">
						<div class="display-inline-block">
							Search&nbsp;:&nbsp;
						</div>
						<div class="display-inline-block">
							<input type="text" value="$search" onkeypress="search_submit(event);" id="search_input" name="search_input" class="style width-150">
						</div>
					</div>
					<div class="float-left padding-left-5">
						<div class="display-inline-block">
							$search_by_select
						</div>
					</div>
				</div>

				<div class="padding-top-10 padding-bottom-2 clearFix">


					<div class="float-left">
						$goto_forms
					</div>

					<div class="float-left padding-left-10">
						<button class="iconbtn" id="go" onclick="filters_submit(this);">Go</button>&nbsp;<a href="$clear_url" class="iconbtn">Clear</a>
					</div>

				</div>

			</div>
		</div>
	</div>

</div>


<div style="background: #333 none;">
$navigation
</div>
EOT;
		return $admin_header;
	}










}

interface tpt_html_template {
	static function main(&$vars);
}
