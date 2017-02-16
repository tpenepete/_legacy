<?php

defined('TPT_INIT') or die('access denied');


/*
$useragent = strtolower($_SERVER['HTTP_USER_AGENT']);
$mobile_agent = false;
if(strpos($useragent, 'mobile') !== false || strpos($useragent, 'android') !== false)
{
	$mobile_agent =  1;
}
else if(preg_match('/android.+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/',$useragent))
{
	$mobile_agent =  1;
}
else if(preg_match('/(bolt\/[0-9]{1}\.[0-9]{3})|nexian(\s|\-)?nx|(e|k)touch|micromax|obigo|kddi\-|;foma;|netfront/',$useragent))
{
	$mobile_agent =  1;
}
else if(preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|e\-|e\/|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(di|rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|xda(\-|2|g)|yas\-|your|zeto|zte\-/',substr($useragent,0,4)))
{
	$mobile_agent =  1;
}
else
	$mobile_agent =  0;

$tpt_vars['environment']['isMobileDevice']['mobile_agent'] = $mobile_agent;

if($mobile_agent){
// IF THE SITE IS LOADED ON MOBILE DEVICE LOADING ADDITIONAL CSS FILE/S
	array_unshift($tpt_vars['template_data']['head'], '<link rel="stylesheet" href="'.TPT_CSS_URL.'/mobile.css" type="text/css" media="screen" />');
}
*/



if($tpt_vars['environment']['isMobileDevice']['ipod'] ||
	$tpt_vars['environment']['isMobileDevice']['ipad'] ||
	$tpt_vars['environment']['isMobileDevice']['iphone'] ||
	$tpt_vars['environment']['isMobileDevice']['android'] ||
	$tpt_vars['environment']['isMobileDevice']['webos']) {
// is iStuff

	$tpt_vars['config']['paypal']['api_nvp_param'] = '_express-checkout-mobile';




	$google_mobile_bot = preg_match('#(iPhone|SAMSUNG\-SGH\-E250|DoCoMo).+http://www.google.com/bot\.html#',$_SERVER['HTTP_USER_AGENT']);

//	if ($google_mobile_bot || $_SERVER['REMOTE_ADDR']=='109.160.0.218'|| $_SERVER['REMOTE_ADDR']=='85.130.3.155') {
//	}
	$js_url = TPT_JS_URL;
	$css_url = TPT_CSS_URL;
	$beurl = BASE_URL.'/'.$tpt_vars['config']['dev']['allypass'];
	$admin_path_ = preg_replace('#^.+/#','',preg_replace('#/$#','',$beurl));
	if (strpos($tpt_vars['config']['requesturl'],$admin_path_)==1) {
		// admin layouts and css
		//disabled..
//        $tpt_vars['template_data']['head'][] = '
//			<link href="/css/mobile_friendly_admin.css" type="text/css" rel="stylesheet">
//			<meta name=viewport content="width=1300, initial-scale=0.01">
//		';

	} else {

		// mobile frontend layout javascript fix
		//	if ($_SERVER['REMOTE_ADDR']=='109.160.0.218') {
		$tjs = <<< EOT

		<script type="text/javascript">

			m_template_fix = function() {
				try {
					//if (!$('body').hasClass('mbl_tplt_fix')) {
						//$('body').css('margin-left','-35px');
						//$('body').addClass('mbl_tplt_fix');

						//$('body').append('<div id="tdvfxd" style="top:0;height:0px;width:100%;position:fixed;"></div>');
					//}

					window.foot__ = $('#footer').position().top-400;
					//$('#amz_socbar').css('margin-top',Math.min(foot__,Math.max(202,50+$('#tdvfxd').position().top)))

					if (typeof(mc_wr_oj_ol)=='undefined') {
						window.mc_wr_oj = $('#minicart_wr');
						window.mc_wr_oj_ol = mc_wr_oj.offset().left+1.1;
						document.body.addEventListener("touchmove",m_template_fix_touch);
					}

					//	var hl = $('.header-banners a.display-block.hoverCB').filter(':last');
					var hl = $('#main_content');
//					var hl = $('.header-wrapper.clearFix.clear-both');
					//	if (hl.length==0) return;
//					var hlr = hl.offset().left + hl.width();
					var hlr = hl.offset().left + 688;
					var ld = mc_wr_oj_ol - hlr;
					var sbosl = $('#amz_socbar a.blog-btn').offset().left;
					var sd = mc_wr_oj_ol - sbosl;

					if (0&&ld<2&&sd<2) {
						//	alert('algn');
						clearInterval(m_template_fix_iii_1);
						setTimeout(function(){
							$('#amz_socbar').css('visibility','visible');
						},1000);
					}

					var orcl = parseInt($('.position-relative.outer-wrapper').css('left'));

					$('.position-relative.outer-wrapper').css('left',Math.round(orcl+ld)+'px');

					//	return;
					//	var sbml = parseInt($('#amz_socbar').css('left'));
					//	$('#amz_socbar')[0].style.position = 'absolute !important';

					if (typeof(sbml) == 'undefined') {
						window.sbml = 70;
						window.sbml_ = 1;

						//window.onscroll = function () {
							//				console.log('daaaaadddddd???');
							//	console.log($('#tdvfxd').position().top);
							//$('#amz_socbar').css('margin-top',Math.min(foot__,Math.max(202,50+$('#tdvfxd').position().top)))
						//}
						//	console.log('ddddddd???');
					}

					sbml = sbml + sd*0.05 - 0.1;
//					sbml = (sbml+'').replace(/\.([0-9][0-9])[0-9]/,'.$1');

					if (Math.abs(sbml-sbml_)>0.6) {
						$('#amz_socbar').css('left',sbml+'%');
					} else {
						sbml = sbml_;
					}

				} catch(e) {
					void('');
//					console.log(e.stack);
				}

			}

			/*
			 m_template_fix_touch = function() {
			 window.m_template_fix_touch_tot = setTimeout(function(){
			 try {

			 var sbosl = $('#amz_socbar a.blog-btn').offset().left;

			 var sd = mc_wr_oj_ol - sbosl;

			 var sbml = parseInt($('#amz_socbar').css('margin-left'));

			 $('#amz_socbar').css('left',(sbml+sd-3)+'px');

			 } catch(e){}
			 },100);
			 } */

			window.m_template_fix_iii_1 = setInterval(m_template_fix,250);
			window.m_template_fix_iii_2 = setInterval(m_template_fix,1000);

			setTimeout(function(){
				clearInterval(m_template_fix_iii_1);
			},30000);

			setTimeout(function(){
				$('#amz_socbar').css('opacity',1);
			},6000);

			//			setTimeout(function(){
			//			window.m_template_fix_iii_2 = setInterval(m_template_fix,500);
			//				try{
			//					clearInterval(m_template_fix_iii_1);
			//				}catch(e){}
			//			},7000);

		</script>

		<style type="text/css">

			#amz_socbar {opacity:0.01;}

			#amz_socbar {
			/*    top:0px !important; */
				margin-left:2px !important;
				position:absolute !important;
			}
		</style>
EOT;

		//	ob_get_clean();
	   // DISABLED ... 
	 //   $tpt_vars['template_data']['head'][] = $tjs;


		//////////////////////////////////////////////////////////////


		//}
		// mobile frontend layout javascript fix end


		// front end layouts and css <meta name=viewport content="width=device-width, initial-scale=1">
//		$tpt_vars['template_data']['head'][] =
//			'<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=2.0">'

//			'<link href="/css/mobile_friendly.css" type="text/css" rel="stylesheet">'.
//			'<meta name=viewport content="width=1150, initial-scale=0.01">'
//			'<meta name=viewport content="width=1150, initial-scale=1">'
		;
	}












	// The following code disables the tooltip function (Tip) for touch devices
	//m_device = '.$is_touch_device.';
	//	console.log("dddd???'.$hhhhh.'?dd");
	$tjs = '
<script type="text/javascript">
//<![CDATA[
	//if (m_device)
	setTimeout(function(){
		Tip=function(){}
	},3000);
//]]>
</script>';
	array_push($tpt_vars['template_data']['head'], $tjs);
	// end







	// IF THE SITE IS LOADED ON A MOBILE DEVICE LOAD ADDITIONAL CSS FILE/S
	//array_unshift($tpt_vars['template_data']['head'], '<link rel="stylesheet" href="'.TPT_CSS_URL.'/mobile.css" type="text/css" media="screen" />');
	$mobile_css = file_get_contents(TPT_CSS_DIR.DIRECTORY_SEPARATOR.'mobile.css');
	$mobile_css = <<< EOT
<style type="text/css">
//<![CDATA[
$mobile_css
//]]>
</style>
EOT;
	array_unshift($tpt_vars['template_data']['head'], $mobile_css);







	$tpt_vars['template_data']['footer_scripts']['scripts'][] = <<< EOT

			document.body.addEventListener("touchmove", function(e)
			{
				var yo = window.pageYOffset;
				var chat_box = document.getElementById('habla_beta_container_do_not_rely_on_div_classes_or_names');
				var chat_con_for_fix = document.getElementById('habla_window_div');
				var win_height = document.documentElement.clientHeight;
				var win_width = document.documentElement.clientWidth;
				var small_y;
				var big_y;
				var topoff;

				//ios fix for social media bar moving
				topoff = $(document).scrollTop();

				//document.title = $(document).scrollTop()+' '+document.documentElement.clientHeight//'w:' + win_width + ' h:' + win_height +' st:'+$(document).scrollTop()+' '+
				
				if (document.getElementById('amz_socbar')) {
					if ($(document).scrollTop() > 204) {
						document.getElementById('amz_socbar').style.top = (topoff)+'px';
					} else {
						document.getElementById('amz_socbar').style.top = (204)+'px';
					}
				}

				//ios fix for chat fixing position


				if (win_height > win_width)
				{
					small_y = 15;
					big_y = 175;
				}
				else
				{
					small_y = -50;
					big_y = 180;
				}

				if(chat_box) {
					if ($(document).scrollTop() > 1) {
						chat_box.setAttribute('style', 'top: ' + ($(document).scrollTop() + (win_height + small_y)) + 'px;left:740px !important;position:absolute;height:187px;z-index:7777 !important;' );
					} else {
						chat_box.setAttribute('style', 'top: ' + ($(document).scrollTop() + (win_height - big_y)) + 'px;left:740px !important;position:absolute;height:187px;z-index:7777 !important;' );
					}
				}

				if(chat_con_for_fix) {
					chat_con_for_fix.setAttribute('style', 'bottom:198px !important;left:975px;position:relative;margin:0;');
				}

			}, false);

			 $('#front_left_art_btn').bind('click', function()
				{
				  var popup = document.getElementById('broker');

				  popup.setAttribute('style', 'position: absolute !important;top: '+$(document).scrollTop()+'px !important;z-index: 24001;width: 990px;background-color: #FFFFFF;height: 650px;padding:4px;margin: 25px;');

				});

			 $('#front_right_art_btn').bind('click', function()
				{
				  var popup = document.getElementById('broker');

				  popup.setAttribute('style', 'position: absolute !important;top: '+$(document).scrollTop()+'px !important;z-index: 24001;width: 990px;background-color: #FFFFFF;height: 650px;padding:4px;margin: 25px;');

				});

			 $('#back_left_art_btn').bind('click', function()
				{
				  var popup = document.getElementById('broker');

				  popup.setAttribute('style', 'position: absolute !important;top: '+$(document).scrollTop()+'px !important;z-index: 24001;width: 990px;background-color: #FFFFFF;height: 650px;padding:4px;margin: 25px;');

				});

			 $('#back_right_art_btn').bind('click', function()
				{
				  var popup = document.getElementById('broker');

				  popup.setAttribute('style', 'position: absolute !important;top: '+$(document).scrollTop()+'px !important;z-index: 24001;width: 990px;background-color: #FFFFFF;height: 650px;padding:4px;margin: 25px;');

				});


EOT;



} else {

	new tpt_Admin($tpt_vars);
	/*
	if($_SERVER['REMOTE_ADDR'] == '85.130.3.155') {
$tpt_vars['template_data']['footer_scripts']['scripts'][] = <<< EOT
addEvent(window, 'scroll', function() {
	//console.log(getDocHeight() - getScrollTop());
	document.title = getScrollTop();

}, false);
EOT;
	}
	*/


	// regular device
	$tpt_tooltips_js = <<< EOT
	var tmp_as = document.getElementsByTagName('A');
	for(var i=0; i<tmp_as.length; i++) {
		if(tmp_as[i].rel.match('tooltip')) {
			addEvent(tmp_as[i], 'mouseover', display_tooltip, false);
			addEvent(tmp_as[i], 'mouseout', hide_tooltip, false);
			addEvent(tmp_as[i], 'mousemove', repos_tooltip, false);
		} else if(tmp_as[i].rel.match('clicktip')) {
			addEvent(tmp_as[i], 'click', toggle_tooltip, false);
				}
	}
EOT;


	/*
	$tpt_vars['environment']['ajax_result']['exec_script'][] = <<< EOT
	tmp_as = document.getElementsByTagName('A');
	for(var i=0; i<tmp_as.length; i++) {
		if(tmp_as[i].rel.match('tooltip')) {
			hide_tooltip({srcElement:tmp_as[i]});
		}
	}

	var tmp_as = document.getElementsByTagName('A');
	for(var i=0; i<tmp_as.length; i++) {
		if(tmp_as[i].rel.match('tooltip')) {
			addEvent(tmp_as[i], 'mouseover', display_tooltip, false);
			addEvent(tmp_as[i], 'mouseout', hide_tooltip, false);
			addEvent(tmp_as[i], 'mousemove', repos_tooltip, false);
		} else if(tmp_as[i].rel.match('clicktip')) {
			addEvent(tmp_as[i], 'click', toggle_tooltip, false);
				}
	}
EOT;
	*/

//var_dump($tpt_vars['environment']['ajax_result']['exec_script']);die();
	$tpt_vars['template_data']['footer_scripts']['scripts'][] = $tpt_tooltips_js;














	$tpt_vars['template_data']['footer_scripts']['scripts'][] = <<< EOT
if(document.getElementById('amz_socbar')) {
	var compTop = parseInt( getStyle( document.getElementById( 'amz_socbar' ), 'top' ) );
	var socbarChildren = document.getElementById('amz_socbar').children;
	var socBarCompHeight = document.getElementById('amz_socbar').offsetHeight; /* get container height */
	var contentHeight = document.getElementById('amz_mcontent').offsetHeight;
	var contentChildren = document.getElementById('amz_mcontent').children;
	var additionalContentHeight = 0;
	var is_root = location.pathname == "/"; //Equals true if we're at the root

	if ( is_root ) { /* only on home page add about reviews container height to the contentHeight */
		additionalContentHeight = 550;
	}

	/* compute social bar total height */
	for (var i = 0; i < socbarChildren.length; i++) {
		socBarCompHeight += socbarChildren[i].offsetHeight; /* add each children height */
	}

	addEvent(window, 'scroll', socBarOnScroll, false);

	function socBarOnScroll() {
		if ( getScrollTop() < 205 ) {
			document.getElementById('amz_socbar').style.position = 'absolute';
			document.getElementById('amz_socbar').style.top = '204px';
		}
		else if ( contentHeight + additionalContentHeight + parseInt( compTop ) - getScrollTop() - socBarCompHeight < 30 ) {
			document.getElementById('amz_socbar').style.position = 'absolute';
			document.getElementById('amz_socbar').style.top = contentHeight + additionalContentHeight - socBarCompHeight + parseInt( compTop ) - 30 + 'px';
		} else {
			document.getElementById('amz_socbar').style.position = 'fixed';
			document.getElementById('amz_socbar').style.top = '2px';
		}
	}

	socBarOnScroll();
}
EOT;
}
