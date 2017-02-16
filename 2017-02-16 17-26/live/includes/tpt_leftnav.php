<?php

defined('TPT_INIT') or die('access denied');

$vars['template']['left_bar'] = '';
$tpt_vars['data']['tpt_navigation_frontend'] = array();
$query = 'SELECT * FROM `tpt_navigation_frontend` WHERE `enabled`=1 ORDER BY `order` ASC';
$db->query($query);
$tpt_vars['data']['tpt_navigation_frontend']['parent_id'] = $db->fetch_assoc_list('parent_id', true);
$query = 'SELECT * FROM `tpt_navigation_frontend` WHERE `enabled`=1 ORDER BY `order` ASC';
$db->query($query);
$tpt_vars['data']['tpt_navigation_frontend']['href'] = $db->fetch_assoc_list('href', false);
$query = 'SELECT `href` FROM `tpt_navigation_frontend` WHERE `enabled`=1 ORDER BY `order` ASC';
$db->query($query);
$leftnav_links = $db->fetch_assoc_list('href', false);
if(is_array($leftnav_links)) {
    foreach($leftnav_links as $key=>$link) {
        $leftnav_links[$key] = $link['href'];
    }
    $tpt_vars['template_data']['links'] = array_merge($leftnav_links, $tpt_vars['template_data']['links']);
}
$query = 'SELECT * FROM `tpt_navigation_frontend` WHERE `enabled`=1 ORDER BY `order` ASC';
$db->query($query);
$tpt_vars['data']['tpt_navigation_frontend']['id'] = $db->fetch_assoc_list('id', false);
class tpt_leftnavClass {
    
    function __construct(&$vars) {
        $vars['environment']['url_processors'][] = $this;
    }
    /*
    function beforeContent(&$vars) {
    }
    */
    
    function after_content(&$vars) {
		if(empty($vars['environment']['mobile_template'])) {
			//tpt_dump($vars['template_data']['hasLeftBar'], true);
			//die('asdasdasdasdasdassdas');
			global $tpt_baseurl;


			if (empty($vars['template_data']['hasLeftBar'])) {
				//$vars['template_data']['head'][] = '<link rel="stylesheet" type="text/css" href="'.TPT_CSS_URL.'/full_width.css" />';
				return;
			}

			$vars['template_data']['left_bar'] = array();
			$vars['template_data']['left_bar']['active'] = 0;
			$vars['template_data']['left_bar']['active_parents'] = 0;
			//var_dump($vars['url']);die();
			//var_dump($vars['data']['tpt_navigation_frontend']['href']);die();
			if (isset($vars['data']['tpt_navigation_frontend']['href'][$vars['url']['upath']])) {
				$vars['template_data']['left_bar']['active'] = $vars['data']['tpt_navigation_frontend']['href'][$vars['url']['upath']]['id'];
			}
			$vars['template_data']['left_bar']['active_parents'] = array();
			if ($vars['template_data']['left_bar']['active']) {
				$mitem = $vars['data']['tpt_navigation_frontend']['id'][$vars['template_data']['left_bar']['active']];
				while (isset($vars['data']['tpt_navigation_frontend']['id'][$mitem['parent_id']])) {
					$mitem = $vars['data']['tpt_navigation_frontend']['id'][$mitem['parent_id']];
					$vars['template_data']['left_bar']['active_parents'][] = $mitem['id'];
				}
			}

			$vars['template']['left_bar'] .= <<< EOT
<div class="side-bar-con width-204 float-left">
    <div class="rb position-relative" style="z-index: 3;">
EOT;
//        <div class="rb-tl"><div class="rb-tr"><div class="rb-t"></div></div></div>
			$vars['template']['left_bar'] .= <<< EOT
        <div class="border-radius-12" style="border: 1px solid #d38f17;"><div class="border-radius-12" style="border: 1px solid #F8E0B5;"><div class="padding-top-10 padding-bottom-10 padding-left-8 border-radius-12" style="background: #ddb987 none;">
EOT;
			$vars['template']['left_bar'] .= $this->mitems($vars['data']['tpt_navigation_frontend']['parent_id'], $vars['template_data']['left_bar']['active'], $vars['template_data']['left_bar']['active_parents'], 0, 0);
			$vars['template']['left_bar'] .= <<< EOT
        </div></div></div>
    </div>
	
    <div class="side-bar-payments position-relative z-index-0">
        <div class="rb">
            <div class="border-radius-12" style="border: 1px solid #d38f17;"><div class="border-radius-12" style="border: 1px solid #F8E0B5;"><div class="rb-m text-align-center padding-top-10 padding-bottom-10 border-radius-12">
                <div class="clear"></div>
                <div class="side-bar-payments-card-con text-align-center">
                    <div class="display-inline-block">

                    </div>
                </div>
            </div></div></div>
        </div>
    </div>

<!-- seal -->
<!--<span id="siteseal"></span>-->

</div>

<div class="side-bar-connectors">
    <div class="conn1"></div>
    <div class="conn2"></div>
</div>
EOT;

//var_dump($vars['template']['left_bar']);die();
		} else {
			//tpt_dump($vars['template_data']['hasLeftBar'], true);
			//die('asdasdasdasdasdassdas');
			global $tpt_baseurl;


			if (!$vars['template_data']['hasLeftBar']) {
				//$vars['template_data']['head'][] = '<link rel="stylesheet" type="text/css" href="'.TPT_CSS_URL.'/full_width.css" />';
				return;
			}

			$vars['template_data']['left_bar'] = array();
			$vars['template_data']['left_bar']['active'] = 0;
			$vars['template_data']['left_bar']['active_parents'] = 0;
			//var_dump($vars['url']);die();
			//var_dump($vars['data']['tpt_navigation_frontend']['href']);die();
			if (isset($vars['data']['tpt_navigation_frontend']['href'][$vars['url']['upath']])) {
				$vars['template_data']['left_bar']['active'] = $vars['data']['tpt_navigation_frontend']['href'][$vars['url']['upath']]['id'];
			}
			$vars['template_data']['left_bar']['active_parents'] = array();
			if ($vars['template_data']['left_bar']['active']) {
				$mitem = $vars['data']['tpt_navigation_frontend']['id'][$vars['template_data']['left_bar']['active']];
				while (isset($vars['data']['tpt_navigation_frontend']['id'][$mitem['parent_id']])) {
					$mitem = $vars['data']['tpt_navigation_frontend']['id'][$mitem['parent_id']];
					$vars['template_data']['left_bar']['active_parents'][] = $mitem['id'];
				}
			}

			$vars['template']['left_bar'] .= <<< EOT
	<div style="border-radius: 0px; border: 0px solid #d38f17;">
EOT;
			$vars['template']['left_bar'] .= $this->mitems($vars['data']['tpt_navigation_frontend']['parent_id'], $vars['template_data']['left_bar']['active'], $vars['template_data']['left_bar']['active_parents'], 0, 0);
			$vars['template']['left_bar'] .= <<< EOT
	</div>

	<div class="side-bar-contactus position-relative z-index-0">
	</div>
	<div class="side-bar-payments position-relative z-index-0">
		<div class="rb">
			<div class="border-radius-12" style="border: 1px solid #d38f17;"><div class="border-radius-12" style="border: 1px solid #F8E0B5;"><div class="rb-m text-align-center padding-top-10 padding-bottom-10 border-radius-12">
			</div></div></div>
		</div>
	</div>
EOT;
		}
    }
            
    function mitems(&$items, $active, &$active_parents, $parentid=0, $level=0) {
        global $tpt_vars;

		if(empty($tpt_vars['environment']['mobile_template'])) {
			$code = '';
			if (is_array($items[$parentid])) {
				$code .= '<ul class="padding-0 margin-0 list-style-none left-nav level' . $level . '">';
				foreach ($items[$parentid] as $item) {
					$itemStyle = '';
					$itemClass = 'left-nav-item';
					$itemClass .= $item['class_suffix'];

					$hasChildren = false;
					if (isset($items[$item['id']]) && is_array($items[$item['id']])) {
						$hasChildren = true;
						$itemClass .= ' left-nav-parent';
					}

					$isSeparator = false;
					if ($item['name'] == '-- separator --') {
						$isSeparator = true;
						$itemClass .= ' left-nav-separator';
					} else {
						$itemClass .= ' border';
                        $itemStyle .= '';
					}

					if (in_array($item['id'], $active_parents)) {
						$itemClass .= ' left-nav-active-parent';
					} else if ($item['id'] == $active) {
						$itemClass .= ' left-nav-active';
					}

					$href = $tpt_vars['url']['handler']->wrap($tpt_vars, $item['href']);

					$nm = ($item['name'] == 'Dual Layer');


					$code .= '<li class="display-block position-relative ' . $itemClass . '">';
					if (!$isSeparator) {
						if (($item['name'] == 'No Minimum Quantity') || ($item['name'] == 'Rush Order Bands')) {
							$add = '';
							$line_height = 'line-height-41';
							if ($item['name'] == 'Dual Layer') {
								$add = '<br /><span class="nominimum letter-spacing-0">' . ($item['name'] == 'Debossed' ? '*' : '') . '(no minimum quantity order)</span>';
								$line_height = 'line-height-56';
							}
							if ($item['name'] == 'Key Chains') {
								$add = '<br /><span class="nominimum letter-spacing-0">' . ($item['name'] == 'Debossed' ? '*' : '') . '(no minimum quantity order)</span>';
								$line_height = 'line-height-56';
							}
							if ($item['name'] == 'Rush Order Bands') {
								$add = '<br /><span class="nominimum letter-spacing-0">Delivered in 4 Days or Less</span>';
								$line_height = 'line-height-56';
							}
							if ($item['name'] == 'No Minimum Quantity') {
								$add = '<br /><span class="nominimum letter-spacing-0">Fast Turn Around or Small Orders</span>';
								$line_height = 'line-height-56';
							}

							$code .= '<a href="' . $href . '" class="font-size-18 overflow-hidden display-block left-nav-link padding-left-5 height-41 ' . $line_height . '" id="item' . $item['id'] . '" title="' . str_replace('"', '&quot;', $item['title']) . '">';
							$code .= '<span class="display-inline-block line-height-14">';
							$code .= $item['name'] . ($hasChildren ? ' <span style="vertical-align: middle; cursor: pointer;" class="parrow display-inline-block width-14 height-10"></span>' : '');
							$code .= $add;
							$code .= '</span></a>';
						} else {
							$add = '';
							$line_height = 'line-height-41';
							if ($item['name'] == 'Dual Layer') {
								$add = '<br /><span class="nominimum letter-spacing-0">' . ($item['name'] == 'Debossed' ? '*' : '') . '(no minimum quantity order)</span>';
								$line_height = 'line-height-41';
							}
							if ($item['name'] == 'Key Chains') {
								$add = '<br /><span class="nominimum letter-spacing-0">' . ($item['name'] == 'Debossed' ? '*' : '') . '(no minimum quantity order)</span>';
								$line_height = 'line-height-41';
							}
							if ($item['name'] == 'Rush Order Bands') {
								$add = '<br /><span class="nominimum letter-spacing-0">Delivered in 4 Days or Less</span>';
								$line_height = 'line-height-41';
							}
							if ($item['name'] == 'No Minimum Quantity') {
								$add = '<br /><span class="nominimum letter-spacing-0">Fast Turn Around or Small Orders</span>';
								$line_height = 'line-height-41';
							}

							$code .= '<a href="' . $href . '" class="overflow-hidden display-block left-nav-link padding-left-5 height-41 ' . $line_height . '" id="item' . $item['id'] . '" title="' . str_replace('"', '&quot;', $item['title']) . '">';
							$code .= '<span class="display-inline-block">';
							$code .= $item['name'] . ($hasChildren ? ' <span style="vertical-align: middle; cursor: pointer;" class="parrow display-inline-block width-14 height-10"></span>' : '');
							$code .= $add;
							$code .= '</span></a>';
						}
						if ($hasChildren) {
							$code .= $this->mitems($items, $active, $active_parents, $item['id'], $level + 1);
						}
					}

					$code .= '</li>';
				}
				$code .= '</ul>';
			}
		} else {
			//tpt_dump('asdasdasd', true);
			$code = '';
			if (is_array($items[$parentid])) {
				$code .= '<ul class="padding-0 margin-0 list-style-none left-nav level' . $level . '">';
				foreach ($items[$parentid] as $item) {
					$itemStyle = '';
					$itemStyle .= $item['mobile_style'];
					$itemClass = 'todayshop-bold ';
					$itemClass .= $item['mobile_class_suffix'];
					$linkClass = ' ';
					$linkClass .= $item['mobile_link_class_suffix'];
					$linkStyle = ' ';
					$linkStyle .= $item['mobile_link_style'];

					$hasChildren = false;
					if (isset($items[$item['id']]) && is_array($items[$item['id']])) {
						$hasChildren = true;
						$itemClass .= ' left-nav-parent';
					}

					$isSeparator = false;
					if ($item['name'] == '-- separator --') {
						$isSeparator = true;
						$itemClass .= ' left-nav-separator';
					} else {
						$itemClass .= ' border';
                        $itemStyle .= '';
					}

					if (in_array($item['id'], $active_parents)) {
						$itemClass .= ' left-nav-active-parent';
					} else if ($item['id'] == $active) {
						$itemClass .= ' left-nav-active';
					}

					$href = $tpt_vars['url']['handler']->wrap($tpt_vars, $item['href']);

					$nm = ($item['name'] == 'Dual Layer');

					if (!$isSeparator) {
					$code .= '<li class="padding-left-5 padding-right-5 display-block position-relative ' . $itemClass . '" style="max-width: 200px;">';

						if (($item['name'] == 'No Minimum Quantity') || ($item['name'] == 'Rush Order Bands')) {
							$add = '';
							$line_height = '';
							if ($item['name'] == 'Dual Layer') {
								$add = '<br /><span class="nominimum todayshop-italic letter-spacing-0">' . ($item['name'] == 'Debossed' ? '*' : '') . '(no minimum quantity order)</span>';
								$line_height = '';
							}
							if ($item['name'] == 'Key Chains') {
								$add = '<br /><span class="nominimum todayshop-italic letter-spacing-0">' . ($item['name'] == 'Debossed' ? '*' : '') . '(no minimum quantity order)</span>';
								$line_height = '';
							}
							if ($item['name'] == 'Rush Order Bands') {
								$add = '<br /><span class="nominimum todayshop-italic letter-spacing-0">Delivered in 4 Days or Less</span>';
								$line_height = '';
							}
							if ($item['name'] == 'No Minimum Quantity') {
								$add = '<br /><span class="nominimum todayshop-italic letter-spacing-0">Fast Turn Around or Small Orders</span>';
								$line_height = '';
							}

							$code .= '<a href="' . $href . '" class="'.$linkClass.' padding-top-5 padding-bottom-5 font-size-150prc text-decoration-none white-space-nowrap overflow-hidden display-block left-nav-link ' . $line_height . '" style="border: 0px solid #FCF8F0;'.$linkStyle.'" id="item' . $item['id'] . '" title="' . str_replace('"', '&quot;', $item['title']) . '">';
							$code .= '<span class="display-inline-block">';
							$code .= $item['name'] . ($hasChildren ? ' <span style="vertical-align: middle; cursor: pointer;" class="parrow display-inline-block width-14 height-10"></span>' : '');
							$code .= $add;
							$code .= '</span></a>';
						} else {
							$add = '';
							$line_height = '';
							if ($item['name'] == 'Dual Layer') {
								$add = '<br /><span class="nominimum todayshop-italic letter-spacing-0">' . ($item['name'] == 'Debossed' ? '*' : '') . '(no minimum quantity order)</span>';
								$line_height = '';
							}
							if ($item['name'] == 'Key Chains') {
								$add = '<br /><span class="nominimum todayshop-italic letter-spacing-0">' . ($item['name'] == 'Debossed' ? '*' : '') . '(no minimum quantity order)</span>';
								$line_height = '';
							}
							if ($item['name'] == 'Rush Order Bands') {
								$add = '<br /><span class="nominimum todayshop-italic letter-spacing-0">Delivered in 4 Days or Less</span>';
								$line_height = '';
							}
							if ($item['name'] == 'No Minimum Quantity') {
								$add = '<br /><span class="nominimum todayshop-italic letter-spacing-0">Fast Turn Around or Small Orders</span>';
								$line_height = '';
							}

							$code .= '<a href="' . $href . '" class="'.$linkClass.' padding-top-5 padding-bottom-5 font-size-150prc text-decoration-none white-space-nowrap overflow-hidden display-block left-nav-link ' . $line_height . '" style="border: 0px solid #FCF8F0;'.$linkStyle.'" id="item' . $item['id'] . '" title="' . str_replace('"', '&quot;', $item['title']) . '">';
							$code .= '<span class="display-inline-block">';
							$code .= $item['name'] . ($hasChildren ? ' <span style="vertical-align: middle; cursor: pointer;" class="parrow display-inline-block width-14 height-10"></span>' : '');
							$code .= $add;
							$code .= '</span></a>';
						}
						if ($hasChildren) {
							$code .= $this->mitems($items, $active, $active_parents, $item['id'], $level + 1);
						}


					$code .= '</li>';
					}
				}
				$code .= '</ul>';
			}
		}
        
        return $code;
    }
}
$tpt_vars['navigation']['handler'] = new tpt_leftnavClass($tpt_vars);
