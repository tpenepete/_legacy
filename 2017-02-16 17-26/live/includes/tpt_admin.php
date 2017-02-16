<?php

defined('TPT_INIT') or die('access denied');
//var_dump('asd');die();

require_once(TPT_INCLUDES_DIR.DIRECTORY_SEPARATOR.'tpt_admintab.php');
class tpt_Admin {
	var $plinks = array();

	var $tabs;

	function __construct(&$vars) {
		$this->plinks = $vars['template_data']['links'];
		//foreach($vars['template_data']['links'] as $href=>$plink) {
		//    $vars['template_data']['links'][$href] = '/amzjpanel'.$plink;
		//}
		//$vars['template']['home_href'] = '/amzjpanel'.$vars['template']['home_href'];
		$vars['environment']['url_processors'][] = $vars['admin']['handler'] = $this;

		$this->tabs = array();
	}

	function beforeContent(&$vars) {

	}

	function after_content(&$vars) {
		//tpt_dump($vars['environment']['isAdmin'], true);
		$c = (in_array($vars['user']['client_ip'], is_array($vars['config']['dev_console_ips']) ? $vars['config']['dev_console_ips'] : array()));
		//tpt_dump($c, true);
		if (!empty($c) && empty($vars['environment']['mobile_device']) && empty($vars['environment']['mobile_template']) && !empty($vars['environment']['isAdmin'])) {
			//tpt_dump($c, true);

			$tpt_baseurl = $vars['config']['baseurl'];
			$tpt_jsurl = TPT_JS_URL;
			$tpt_cssurl = TPT_CSS_URL;
			$tpt_resourceurl = $vars['config']['resourceurl'];

//if($_SERVER['REMOTE_ADDR'] == '85.130.3.155') {
//    var_dump($tpt_vars['template_data']['footer_scripts']['content']);
//    echo 'fdfds';
//    die();
//}

			$panel_max_top_factor = intval($vars['admin']['template_data']['panel_max_top_factor'], 10);
			$panel_max_top_absolute = intval($vars['config']['admin']['panel_max_top_absolute'], 10);
			$tabs_json = '""';
			//$tabs_json = addslashes(json_encode($vars['admin']['template_data']['admin_tabs']));

			$vars['template_data']['head'][] = <<< EOT
<link rel="stylesheet" type="text/css" href="$tpt_cssurl/tpt_admin_css.css" />
<script type="text/javascript" src="$tpt_jsurl/general.js"></script>
<script type="text/javascript" src="$tpt_jsurl/reposition.js"></script>
<script type="text/javascript" src="$tpt_jsurl/drag_drop.js"></script>
<script type="text/javascript" src="$tpt_jsurl/hyp_tabs.js"></script>
<script type="text/javascript" src="$tpt_jsurl/tpt_pagination.js"></script>

<script type="text/javascript">
//<![CDATA[
var panel_max_top_factor = $panel_max_top_factor;
var panel_max_top_absolute = $panel_max_top_absolute;
var tpt_admin_tabs = JSON.parse('$tabs_json');
//]]>
</script>
EOT;


			if (!$vars['environment']['isAjax']) {
				$vars['template_data']['footer_scripts']['scripts'][] = <<< EOT
var hyp_tabs = getChildElements('btabs');
var hyp_panels = getChildElements('bpanels');
var hyp_paginations = getChildElements('bpaginations');
EOT;
			}

			//tpt_dump($c, true);
			$vars['template_data']['footer_scripts']['scripts'][] = <<< EOT
var hyp_style = document.styleSheets;
for(var i=0; i<hyp_style.length; i++)
if(hyp_style[i].title == 'modifiable') {
	hyp_style = hyp_style[i];
	break;
}

/*var allDivs = document.getElementsByTagName('DIV');
for(var i=0, _len=allDivs.length; i<_len; i++) {
if(allDivs.className.match(''))
}*/

addEvent(document.getElementById('movpan'), 'mouseenter', mousedover, false);
addEvent(document.getElementById('movpan'), 'mouseleave', mousedout, false);

addEvent(document.getElementById('panelopacity'),
	 'input',
	 function(e) {
		if(!e)e = window.event;
		var elm = (e.target?e.target:window.event.srcElement);
		var tmp = parseInt(elm.value, 10);
		if(isNaN(tmp))tmp=0;
		if(tmp>100)tmp=100;
		tmp = Math.floor(tmp/10)*10;
		minimumopacity = tmp;
		var data = new Date();
		data.setFullYear(data.getFullYear()+1);
		data = data.toGMTString().replace('GMT', 'UTC');
		document.cookie = 'panel_opacity='+tmp+'; expires='+data+'; path=/';
	},
	false);

EOT;

			//tpt_dump('test', true);
			//ob_start();
			//var_dump($this->tabs);die();
			//$dumped = ob_get_contents();
			//file_put_contents('result.txt', $dumped);

			$fwcheck = '';
			$ph = '';
			$poverflow = ' overfow: visible;';
			if ($vars['admin']['template_data']['panel_fittow']) {
				$vars['admin']['template_data']['panel_height'] = max($vars['admin']['template_data']['panel_height'], 20);
				$ph = ' height: ' . $vars['admin']['template_data']['panel_height'] . 'px;';
				$fwcheck = ' checked="checked"';
				$poverflow = ' overflow: auto;';
			} else {
				$vars['admin']['template_data']['panel_height'] = '';
			}


			if (true || $vars['user']['isLogged']) {
				$ajax_call = tpt_ajax::getCall('user.logout');
				$vars['template']['admin_content'] = '';

				$stcont = 'You\'re in!<br /><form method="POST" action="index.php" accept-charset="utf-8" class="padding-top-10 padding-right-10 padding-bottom-20 padding-left-10"><input type="button" value="Logout" onclick="' . $ajax_call . '" /></form>';
				//var_dump($this->tabs);
				$status_tab = new tpt_adminTab($vars, 'Status', $stcont);

				array_unshift($this->tabs, $status_tab);
				$vars['admin']['template_data']['admin_tabs'][base64_encode('Status')] = array('title' => 'Status');

				$acont = '';
				$atabs = array();
				$apnls = array();
				$acont .= '';
				//var_dump($this->tabs);die();
				$tkeys = array_keys($this->tabs);

				//var_dump($vars['admin']['template_data']['admin_tabs']);die();
				//var_dump($vars['admin']['template_data']['active_tab']);//die();
				//$active_tab = $this->tabs[$tkeys[0]]->title;
				$active_tab = 'Status';
				if (isset($vars['admin']['template_data']['admin_tabs'][base64_encode($vars['admin']['template_data']['active_tab'])])) {
					$active_tab = $vars['admin']['template_data']['active_tab'];
				}
				//var_dump($active_tab);die();

				$i = -1;
				foreach ($this->tabs as $ind => $tp) {
					$i++;
					$ttl = $tp->title;
					$pc = $tp->content;
					$pgn = $tp->pagination;
					$active_class = '';
					if ($ttl === $active_tab)
						$active_class = ' active';
					$atabs[] = <<< EOT
<span class="display-block padding-top-2 padding-bottom-2">
<a id="tpt_admin_tab_$ind" class="hypTab display-block height-20 line-height-20 padding-left-10 padding-right-10 text-decoration-none$active_class" style="" title="$ttl" href="javascript:void(0)" onclick="activate_panel(this.id);">$i. $ttl</a>
</span>
EOT;
					$apnls[] = <<< EOT
<div id="tpt_admin_panel_$ind" class="hypPanel$active_class">$pc</div>
EOT;
					$apgntns[] = <<< EOT
<div id="tpt_admin_pagination_$ind" class="hypPagination$active_class">$pgn</div>
EOT;
				}

				//var_dump($apgntns);die();
				$tpt_paginations = implode($apgntns);
				//$tpt_admintabs = implode('&nbsp;', $atabs);
				$tpt_admintabs = implode('', $atabs);
				$tpt_adminpanels = implode($apnls);

				/*
				$vars['template']['admin_content'] .= <<< EOT
			<div class="adminContent" id="tpt_admincontent" style="$ph$poverflow">
				<div id="bpaginations" class="hypTabs position-fixed top-0 left-0 right-0" style="z-index: 64000; border: 1px solid #FFF; background: transparent url($tpt_resourceurl/images/semi-black.png) repeat scroll 0 0;">
					$tpt_paginations
				</div>
				<div id="btabs" class="hypTabs" style="z-index: 128000;">
					$tpt_admintabs
				</div>
				<div id="bpanels" class="hypTabs padding-top-10 padding-right-10 padding-bottom-10 padding-left-10" style="z-index: 128000; border: 1px solid #FFF;">
					$tpt_adminpanels
				</div>
			</div>
			EOT;
				*/

				$vars['template']['admin_content'] .= <<< EOT
<div class="adminContent" id="tpt_admincontent" style="$ph$poverflow">
<div id="btabs" class="hypTabs" style="z-index: 128000;">
	$tpt_admintabs
</div>
<div id="bpanels" class="hypTabs padding-top-10 padding-right-10 padding-bottom-10 padding-left-10" style="z-index: 128000; border: 1px solid #FFF;">
	$tpt_adminpanels
</div>
</div>
EOT;


//var_dump($vars['template']['admin_content']);die();
			} else {
				$ajax_call = tpt_ajax::getCall('user.login');
				$vars['template']['admin_content'] = <<< EOT
<div class="adminContent" id="tpt_admincontent" style="$ph$poverflow">
<form method="POST" action="index.php" accept-charset="utf-8" class="padding-left-200">
user: <input type="text" name="username" value="" />
<br />
pass: <input type="password" name="password" value="" />
<br />
<input type="button" value="Login" onclick="$ajax_call" />
</form>
</div>
EOT;
			}

			if ($vars['admin']['template_data']['panel_position'] == 'absolute')
				$vars['admin']['template_data']['panel_top'] = max($vars['admin']['template_data']['panel_max_top_factor'] + $vars['config']['admin']['panel_max_top_absolute'], $vars['admin']['template_data']['panel_top']);
			else
				$vars['admin']['template_data']['panel_top'] = max($vars['admin']['template_data']['panel_max_top_factor'], $vars['admin']['template_data']['panel_top']);

			$pop = $vars['admin']['template_data']['panel_opacity'];
			$pp = $vars['admin']['template_data']['panel_position'];
			$ppcheck = (($pp == 'absolute') ? ' checked="checked"' : '');
			if ($vars['environment']['isMobileDevice']['ipod'] ||
				$vars['environment']['isMobileDevice']['iphone'] ||
				$vars['environment']['isMobileDevice']['ipad'] ||
				$vars['environment']['isMobileDevice']['android'] ||
				$vars['environment']['isMobileDevice']['webos']
			) {
				$pl = ' left: 50%; margin-left: -450px;';
			} else if ($vars['admin']['template_data']['panel_left'] !== '') {
				$pl = ' left: ' . $vars['admin']['template_data']['panel_left'] . 'px; margin-left: 0px;';
			} else {
				$pl = ' left: 50%; margin-left: -450px;';
			}
			$pt = ' top: ' . $vars['admin']['template_data']['panel_top'] . 'px';

			$init_tabs = '';
			$init_tabs .= 'var panel_max_top_factor = ' . $vars['admin']['template_data']['panel_max_top_factor'] . ';';
			$init_tabs .= 'var panel_max_top_absolute = ' . $vars['config']['admin']['panel_max_top_absolute'] . ';';
			$init_tabs .= 'var tpt_admin_tabs = JSON.parse(\'' . addslashes(json_encode($vars['admin']['template_data']['admin_tabs'])) . '\');';

			$admin_content = $vars['template']['admin_content'];
			$vars['template']['admin_panel'] = <<< EOT
<div id="movpan" class="movablePanel width-900 position-$pp opacity-$pop" style="z-index: 64001; border: 1px solid #000;$pl$pt"><div class="moveBar height-30 clear-both" style="cursor: pointer; background: #000 none;"></div>
<div class="position-absolute top-0 right-0">
<span class="display-inline-block float-right color-white">
	Opacity fadeout to: %<input id="panelopacity" value="$pop" size="4" class="color-black" style="margin: 4px;" autocomplete="off" />
</span>
<span class="display-inline-block float-right color-white">Docked&nbsp;<input type="checkbox" id="tpt_admin_panpos" class="" style="margin: 8px 0px;" onclick="reposition(this);" autocomplete="off"$ppcheck />&nbsp;&nbsp;&nbsp;&nbsp;</span>
<span class="display-inline-block float-right color-white">Fit to window height&nbsp;<input type="checkbox" id="tpt_admin_fittow" class="color-black" style="margin: 8px 0px;" onclick="fit_to_window(this);" autocomplete="off"$fwcheck />&nbsp;&nbsp;&nbsp;&nbsp;</span></div>
<div id="admin_content" class="panelContentWrap padding-20" style="background: transparent url($tpt_resourceurl/images/semi-black.png) repeat scroll 0 0;">
	$admin_content
</div>
</div>
EOT;

//$init_tabs .= 'for(var task in tpt_tasks)if(tpt_tasks[task].post_data)tpt_tasks[task].post_data = JSON.parse(tpt_tasks[task].post_data);';

			if (!$vars['environment']['isAjax']) {
				$vars['template_data']['footer_scripts']['scripts'][] = $init_tabs;
			}

			//tpt_dump($vars['environment']['page_rule'], true);
			//$vars['template']['title'] = !empty($vars['environment']['page_rule']['html_title'])?$vars['environment']['page_rule']['html_title']:'';

		}
	}
}
new tpt_Admin($tpt_vars);