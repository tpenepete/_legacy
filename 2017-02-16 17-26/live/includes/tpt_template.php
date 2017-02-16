<?php

defined('TPT_INIT') or die('access denied');

class tpt_template {

	static function render(&$vars) {
		
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
			////////return self::getMinicartDefaultDesktop($vars);
			return '';
		} else {
			////////return self::getMinicartDefaultMobile($vars);
			return '';
		}
	}

	static function getMinicart(&$vars) {
		if (empty($vars['environment']['mobile_template'])) {
			////////return self::getMinicartDesktop($vars);
			return '';
		} else if ($vars['environment']['mobile_template'] == 1) {
			////////return self::getMinicartMobile($vars);
			return '';
		} else {
			////////return self::getMinicartMobile2($vars);
			return '';
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

	</div>


	<div class="clearFix">
		<div class="float-left width-65prc">

		</div>

		<div class="width-35prc float-right" style="max-width: 150px;">
			<div class="padding-5">
				$account_area
			</div>

		</div>
	</div>
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
			////////return self::getFrontendSocialBarDesktop($vars);
			return '';
		} else if ($vars['environment']['mobile_template'] == 1) {
			////////return self::getFrontendSocialBarMobile($vars);
			return '';
		} else {
			////////return self::getFrontendSocialBarMobile2($vars);
			return '';
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
		////////return $res['data'];
		return '';
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
			<div class="footer-copy-write">
			$year - All Rights Reserved
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
				<div class="footer-copy-write padding-top-10 padding-bottom-10 text-align-center">
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
				<div class="footer-copy-write padding-bottom-10">
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

		$vars['template_data']['head']['style_script1'] .= <<< EOT
<link rel="stylesheet" type="text/css" href="$tpt_cssurl/tpt_spec_common_messages_header_navigation_social_main_footer_lightbox_template.css" />
EOT;
		if (isDev('unpackresources') && !empty($_GET['unpackresources'])) {
			//tpt_dump('asd', true);
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


			$vars['template_data']['head']['style_script1'] .= <<< EOT
<link rel="stylesheet" type="text/css" href="$css_url/all.css" />
EOT;


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

		}
	}

	static function getFrontendHeadContent(&$vars) {

		$tpt_baseurl = BASE_URL;
		$tpt_imagesurl = TPT_IMAGES_URL;
		$tpt_jsurl = TPT_JS_URL;
		$tpt_cssurl = TPT_CSS_URL;


		/*
<meta name="google-site-verification" content="FQltraLhtXI9CGk5ucmdltPqYsk1hrFCHR2NZP0MFJU" />

<meta name="msvalidate.01" content="E96AC380923C9E287BB48A90C0BD7DA9" />
<meta name="p:domain_verify" content="b81dd0bc0165a36926116704a71c332b"/>
		 */
		if (!defined('TPT_BACK')) {
			$vars['template_data']['head']['start'] = <<< EOT
<meta name=viewport content="width=device-width, initial-scale=1">

<link rel="icon" type="image/png" href="$tpt_imagesurl/ffavicon-16x16.png">
<link rel="apple-touch-icon-precomposed" href="$tpt_imagesurl/ffavicon-16x16.png" />
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="$tpt_imagesurl/ffavicon-72x72.png" />
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="$tpt_imagesurl/ffavicon-114x114.png" />
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="$tpt_imagesurl/ffavicon-144x144.png" />

EOT;
			/*
$ecommerce<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-M82FT9');</script>
<!-- End Google Tag Manager -->
			 */
			if(!empty($vars['environment']['page_rule']['google_tag_manager']) && empty($vars['environment']['is404']) && empty($vars['environment']['force404']) && (empty($_GET) || $vars['config']['seo']['google']['tag_manager']['has_allowed_param'])) {
				$ecommerce = (isset($vars['template_data']['head']['google_tag_manager0'])?$vars['template_data']['head']['google_tag_manager0']:'');
				$vars['template_data']['head']['google_tag_manager'] = <<< EOT
EOT;
			}
			if (isDev('unpackresources') && !empty($_GET['unpackresources'])) {
				//tpt_dump('asd', true);
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


				if (!empty($css['frontend'])) {
					$vars['template_data']['head']['style_script2'] .= <<< EOT
<link rel="stylesheet" type="text/css" href="$css_url/all1.css" />
EOT;
				}

				if ($vars['environment']['mobile_template'] == 1) {
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
				if (!empty($css['page'])) {
					foreach ($css['page'] AS $c) {
						$url = $c['url'];
						$vars['template_data']['head']['style_script2'] .= <<< EOT
<link rel="stylesheet" type="text/css" href="$url" />
EOT;
					}
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
				if (!empty($js['page'])) {
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
		if (!empty($vars['template_data']['tpt_logged_in']) && !$vars['user']['isLogged']) {

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



		$header = $vars['template']['header'];

		$subpath = $vars['config']['subpath'];

		$tpt_baseurl = BASE_URL;
		if (empty($vars['environment']['client']) || ($vars['environment']['client'] == 2)) {
			$admin_header = self::getBackendHeader($vars);

			if (empty($vars['template_data']['hasLeftBar'])) {
				$admin_header = '';
			}
		} else {
			$vars['template']['header'] = self::getFrontendHeader($vars);

			self::getHeadContent($vars);
			self::getFrontendHeadContent($vars);

			$home_href = $vars['template']['home_href'];
			$quote_link = $vars['template']['quote_link'];

			$header = $vars['template']['header'];
			$social_bar = $vars['template']['social_bar'];
			$subpath = $vars['config']['subpath'];
			$left_bar = $vars['template']['left_bar'];
		}



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

		$vars['admin']['handler']->after_content($vars);
		$admin_panel = $vars['template']['admin_panel'];
		$admin_content = $vars['template']['admin_content'];


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


		$head_html = implode("\n", $vars['template_data']['head']);
		$meta_html = implode("\n", $vars['template_data']['meta']);
		$title = $vars['template']['title'];

		$footer_general = implode("\n", $vars['template_data']['footer_scripts']['content']);
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
		$footer_code = $footer_scripts . "\n" . $footer_general;

		if (!$vars['environment']['isAjax']) {
			tpt_current_user::setLoggedUserCookies($vars);

			//tpt_content_cache::load_cache($vars);

			switch ($vars['template_data']['template_type']) {
				case 'plain' :
					include(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'template-plain.php');
					break;
				default:

					if ($vars['environment']['isAdministration']) {
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
				echo str_replace('<a', '<a target="_parent"', ob_get_clean());
			}

		} else {
			if ($vars['environment']['isTask']) {
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



}

function selfURL() {
	$s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
	$protocol = strleft(strtolower($_SERVER["SERVER_PROTOCOL"]), "/") . $s;
	$port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":" . $_SERVER["SERVER_PORT"]);
	return $protocol . "://" . $_SERVER['SERVER_NAME'] . $port . $_SERVER['REQUEST_URI'];
}

function self_page_URL() {
	$s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
	$protocol = strleft(strtolower($_SERVER["SERVER_PROTOCOL"]), "/") . $s;
	$port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":" . $_SERVER["SERVER_PORT"]);
	return $protocol . "://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
}

function strleft($s1, $s2) {
	return substr($s1, 0, strpos($s1, $s2));
}
