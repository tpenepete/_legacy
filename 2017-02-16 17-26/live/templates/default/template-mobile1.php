<?php

/*
$rq_protocol = strtolower(preg_replace('#[^A-Z]#','',$_SERVER['SERVER_PROTOCOL'])).'://';
$rq_url = $rq_protocol.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$RQA = parse_url($rq_url);

//var_dump($RQA['path']);

if (preg_match('#\.php$#',$RQA['path'])) {
Header( "Status: 301" );
Header( "Location: ".preg_replace('#\.php#','',$_SERVER['REQUEST_URI'],1) );
exit;
}

    <body>
        <div id="tpt_messages" class="position-fixed top-0 left-0 right-0" style="$mtop z-index: 128000;">
            $messages
        </div>


		<div style="display:none;">
         	<img src="$images_url/Click_For_A_Quote2.png" alt="Wristbands Quote" title="Click To Receive A Formal Wristbands Quote" />
            <img src="$images_url/Design_and_Buy_Online3.png" alt="Design Your Wristbands Now" title="Click To Design Wristbands Online" />
         </div>

*/

defined('TPT_INIT') or die('access denied');

class tpt_html_template_variant extends tpt_html_template {
	static function main(&$vars) {
		$images_url = TPT_IMAGES_URL;
		$js_url = TPT_JS_URL;

		$ruleid = isset($vars['environment']['page_rule']['id']) ? $vars['environment']['page_rule']['id'] : '';

		$header = $vars['template']['header'];
		$social_bar = $vars['template']['social_bar'];
		$subpath = $vars['config']['subpath'];
		$left_bar = $vars['template']['left_bar'];

		//$content = $tpt_vars['template']['content'];
		$footer = $vars['template']['footer'];

//tpt_dump($content);
		if(!empty($vars['template']['content'])) {
			$content = $vars['template']['content'];
		}

		$messages = '';
		if(!tpt_request::$redirect) {
			unset($_SESSION['templay']['messages']);
			unset($_SESSION['templay']['execute_onload']);
			tpt_Messages::getMessages($vars);
			$messages = implode("\n", $vars['template_data']['messages']);
			$mtop = ' top: '.intval($vars['admin']['template_data']['panel_max_top_factor'], 0).'px;';

			foreach($vars['environment']['ajax_result']['execute_onload']['head'] as $script) {
				$vars['template_data']['head'][] = $script;
			}

			foreach($vars['environment']['ajax_result']['execute_onload']['footer'] as $script) {
				$vars['template_data']['footer_scripts']['scripts'][] = $script;
			}
		} else {
			$_SESSION['templay']['execute_onload'] = $vars['environment']['ajax_result']['execute_onload'];
			$_SESSION['templay']['messages'] = $vars['environment']['ajax_result']['messages'];
		}

		$tooltips = '';
		if($vars['environment']['isMobileDevice']['ipod'] ||
			$vars['environment']['isMobileDevice']['ipad'] ||
			$vars['environment']['isMobileDevice']['iphone'] ||
			$vars['environment']['isMobileDevice']['android'] ||
			$vars['environment']['isMobileDevice']['webos']) {
// is iStuff

		} else {
			$tooltips = $vars['template']['tooltips'];

		}


		// overrides all head scripts
		if(!empty($vars['template_data']['head']['override'])) {
			$vars['template_data']['head'] = array($vars['template_data']['head']['override']);
		}
		//tpt_dump($tpt_vars['template_data']['head'], true);
		$head_html = implode("\n", $vars['template_data']['head']);
		$meta_html = implode("\n", $vars['template_data']['meta']);
		$title = $vars['template']['title'];

		//tpt_dump($tpt_vars['template']['title'], true);

		// after opening body tag code
		$bts_general = implode("\n", $vars['template_data']['body_tag_start']['content']);

		$bts_scripts = implode("\n", $vars['template_data']['body_tag_start']['scripts']);
		if(!empty($bts_scripts)) {
			$bts_scripts = <<< EOT
    <script type="text/javascript">
    //<![CDATA[
    $bts_scripts
    //]]>
    </script>
EOT;
		}
		//	$tpt_vars['template']['footer_code'] = $footer_code = $footer_styles . "\n" . $footer_scripts . "\n" . $footer_general;
		$vars['template']['body_tag_start_code'] = $bts_code = $bts_scripts . $bts_general;
		// END after opening body tag code

		//$footer_styles."\n". empty styles that breaks the validation
		if(!empty($vars['environment']['page_rule']['cache_enabled']) && !empty($vars['cache']['content']['content'])) {
			$vars['template']['footer_code'] = $footer_code = $vars['cache']['content']['footer_code'];
		} else {
			$footer_general = implode("\n", $vars['template_data']['footer_scripts']['content']);
			//if($_SERVER['REMOTE_ADDR'] == '85.130.3.155') {
			//    var_dump($tpt_vars['template_data']['footer_scripts']['content']);
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
			//	$tpt_vars['template']['footer_code'] = $footer_code = $footer_styles . "\n" . $footer_scripts . "\n" . $footer_general;
			$vars['template']['footer_code'] = $footer_code = "\n" . $footer_scripts . "\n" . $footer_general;

			$vars['template_data']['head'][] = $footer_styles;
		}

		$admin_panel = $vars['template']['admin_panel'];
//tpt_dump($admin_panel, true);
		$admin_content = $vars['template']['admin_content'];

		$tpt_tasks_head_content = tpt_template::getFrontendTptTasksHeadContent($vars);
		$account_area = tpt_template::getFrontendHeaderCustomerArea($vars);
		if ((isDev('cachetags') && !empty($_GET['cachetags'])) || (isDev('rebuildcache') && !empty($_GET['rebuildcache']))) {
			$messages = TPT_TAG_MESSAGES;
		}

		$css_style_body = $vars['template']['css_style']['body'];

//<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		$template = <<< EOT
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>$title</title>
        $meta_html
        $head_html
        $tpt_tasks_head_content
    </head>

    <body class="pagenum_$ruleid" style="$css_style_body">$bts_code
        <script defer type="text/javascript" src="$js_url/tool-tip.js"></script>
        <div id="tpt_overlay" class="display-none position-fixed top-0 left-0 right-0 bottom-0 opacity-70" style="background-color:#000;z-index: 127000;">
            <div id="tpt_lightbox_wrap" class="" style="width:90%;height:90%;position: relative;left:50%;top:50%;">
                <div id="tpt_lightbox" class="" style="background-color: #FFFFFF;width:50%;height:50%;position: relative;left:-25%;top:-25%;">
                </div>
            </div>
        </div>
        <div class="main-wrap position-relative clearFix" style="z-index: 1; margin: 0px auto 0px;">
        	<div style="width: 0px; z-index: 2;
-webkit-box-shadow: -3px 0px 7px -3px rgba(0,0,0,0.6);
-moz-box-shadow: -3px 0px 7px -3px rgba(0,0,0,0.6);
box-shadow: -3px 0px 7px -3px rgba(0,0,0,0.6);
        	" class="minicartFolded position-absolute overflow-hidden right-0" id="minicart_toggle">
				$account_area
        	</div>

        	<div style="width: 0px; z-index: 2;
-webkit-box-shadow: 3px 0px 7px -3px rgba(0,0,0,0.6);
-moz-box-shadow: 3px 0px 7px -3px rgba(0,0,0,0.6);
box-shadow: 3px 0px 7px -3px rgba(0,0,0,0.6);
        	" class="navFolded position-absolute overflow-hidden" id="left_bar_toggle">
				<div id="left_bar" class="opacity-0 float-left amz_yellow_bg">
					$left_bar
				</div>
        	</div>

        	<div class="position-relative" style="z-index: 1;">
				<div id="header" class="position-relative" style="z-index: 2;">
				$header
				</div>
				<div class="outer-wrapper position-relative " id="amz_outwrap" style="z-index: 1; max-width: 100%;">

					<div class="content clearFix text-align-center position-relative">
						<div class="con-middle" id="amz_mcontent">
							<div id="tpt_messages" class="" style="z-index: 128000;">
								$messages
							</div>
							<div id="main_content" class="font-size-12 clearFix">
								$content
							</div>
						</div>
						<div class="height-20"></div>
					</div>
				</div>
			</div>
        </div>
        <div id="footer" class="position-relative" style="z-index: 2; margin: 0 auto;">
        $footer
        </div>
        $footer_code
    </body>
</html>

EOT;

		if (!isDev('unpackresources') || empty($_GET['unpackresources'])) {
			$template = preg_replace('#(>)[\s]*(<)#', '$1$2', $template);

			preg_match_all('#<script[^>]*?>(?:[\s]*(?://<\!\[CDATA\[)|(?:/\* <\!\[CDATA\[ \*/))?([^<]+?.+?)(?:(//\]\]>)|(?:/\* \]\]> \*/)[\s]*)?</script>#s', $template, $scripts, PREG_SET_ORDER);
			foreach ($scripts as $s) {
				$template = str_replace($s[0], '<script type="text/javascript">//<![CDATA[' . "\n" . preg_replace('#(?:\\\\)?/\*.*?\*(?:\\\\)?/#s', '', JSMin::minify($s[1])) . "\n" . '//]]></script>', $template);
			}
//tpt_dump($scripts, true);

			preg_match_all('#([^"\'])<style[^>]*?>([^<]+?.+?)</style>([^"\'])#s', $template, $styles, PREG_SET_ORDER);
			foreach ($styles as $s) {
				$template = str_replace($s[0], $s[1] . '<style type="text/css">' . preg_replace('#(?:\\\\)*/\*.*?\*(?:\\\\)*/#s', '', tpt_html::sanitize_css_output($s[2])) . '</style>' . $s[3], $template);
			}

			$template = preg_replace('#<\!--.*?-->#s', '', $template);
//tpt_dump($styles, true);
//$template = str_replace('<!DOCTYPE html>', '<!DOCTYPE html>'."\n", $template);
//$template = tpt_html::sanitize_html_output($template);
			/*
			$options = array(
				'cssMinifier'=>tpt_html::sanitize_css_output,
				'jsMinifier'=>JSMin::minify
			);
			*/
//$template = Minify_HTML::minify($template);
		}

		if ((isDev('rebuildcache') && !empty($_GET['rebuildcache']))) {
			tpt_template::rebuildCache($vars, $template, 'content_mobile2');
		}

		return $template;
	}
}


/*
if((isDev('rebuildcache') && !empty($_GET['rebuildcache'])) && !defined('TPT_PRE_CACHE_STARTED')) {
	define('TPT_PRE_CACHE_STARTED', 1);
	ob_start();
	$fpath = TPT_BASE_DIR . DIRECTORY_SEPARATOR . 'template.php';
	$evars = tpt_functions::f_get_defined_vars($tpt_vars, get_defined_vars());
	$fvars = tpt_functions::f_include_once($tpt_vars, $fpath, $evars);
//extract($fvars, EXTR_OVERWRITE);
	$cache = ob_get_clean();
	ob_start();
	$fpath = TPT_BASE_DIR . DIRECTORY_SEPARATOR . 'template-mobile.php';
	$evars = tpt_functions::f_get_defined_vars($tpt_vars, get_defined_vars());
	$fvars = tpt_functions::f_include_once($tpt_vars, $fpath, $evars);
//extract($fvars, EXTR_OVERWRITE);
	$cache_mobile1 = ob_get_clean();
	$cache_mobile2 = $template;
	tpt_template::rebuildCache($tpt_vars, $cache, $cache_mobile1, $cache_mobile2);
}
*/
//var_dump($tpt_vars['environment']['login_return_url']);

