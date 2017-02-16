<?php

defined('TPT_INIT') or die('access denied');



$input = $_GET;
if($tpt_vars['environment']['request_method'] == 'post') {
	$input = $_POST;
}


$types_module = getModule($tpt_vars, 'BandType');
$clipart_module = getModule($tpt_vars, 'BandClipart');




$fl2display = 'display-none';
$fl2undisplay = '';
if(!empty($pgFrontMessage2)) {
	$fl2display = '';
	$fl2undisplay = 'display-none';
}
$bl2display = 'display-none';
$bl2undisplay = '';
if(!empty($pgBackMessage2)) {
	$bl2display = '';
	$bl2undisplay = 'display-none';
}




$bdisplay = 'display-none';
if($tback && !$tcont) {
$bdisplay = 'display-block';
}


$custom_clipart_upload = '<div class="clearBoth" style="margin-top: 10px;padding-left: 3px;">
                    <div style="width: 100%;border-bottom: 1px solid #DE3A3A;"></div>
                    <a class="btn_cca  " id="upload" onclick="$(\'#uploaded\').click();"></a>
                    <span id="status" style="display: block;"></span>
                    <span id="files" style="display: block;"></span>
                    <div style="display: none; clear: both;color: #DE3A3A;width: 111px;margin: 0 auto;">(20 MB max size)</div>
            </div>';

$custom_clipart_convert_check = '';
            
if (isDev('convertclipart')) {
    $custom_clipart_convert_check = '<div class="display-none" id="convert_clipart_check_div"><input type="checkbox" name="convert_clipart_check" id="convert_clipart_check"/> Check this box if the image you have uploaded is not a vector format. We will convert it for you ($9.00)</div>';
} else {
    $custom_clipart_convert_check = '';
}


//var_dump($pgType);die();
$fclipdisplay = '';
$bclipdisplay = '';
//if(!empty($types_module->moduleData['id'][$pgType]['writable'])) {
//    $fclipdisplay = 'display-none';
//}

if(!empty($types_module->moduleData['id'][$pgType]['writable'])) {
    if(($types_module->moduleData['id'][$pgType]['writable'] == 1)) {
	$fclipdisplay = 'display-none';
    } else if(($types_module->moduleData['id'][$pgType]['writable'] == 2)) {
        if(($types_module->moduleData['id'][$pgType]['writable_strip_position'] == 2)) {
            $fclipdisplay = 'display-none';
        } else if(($types_module->moduleData['id'][$pgType]['writable_strip_position'] == 1)) {
            $bclipdisplay = 'display-none';
        }
    }
}

$exclpfl = '';
$exclpfr = '';
if(in_array($pgType, array(31,32,33))) {
    $exclpfl = '<input type="checkbox" id="exclpfl" onclick="extend_clipart(this, document.getElementById(\'clpfl2\'));" /> Extend left clipart both lines';
    $exclpfr = '<input type="checkbox" id="exclpfr" onclick="extend_clipart(this, document.getElementById(\'clpfr2\'));" /> Extend right clipart both lines';
}


$input['clipart_front_left'] = !empty($input['clipart_front_left'])?$input['clipart_front_left']:0;
$input['clipart_front_left_c'] = !empty($input['clipart_front_left_c'])?$input['clipart_front_left_c']:0;
$input['clipart_front_right'] = !empty($input['clipart_front_right'])?$input['clipart_front_right']:0;
$input['clipart_front_right_c'] = !empty($input['clipart_front_right_c'])?$input['clipart_front_right_c']:0;
$input['clipart_back_left'] = !empty($input['clipart_back_left'])?$input['clipart_back_left']:0;
$input['clipart_back_left_c'] = !empty($input['clipart_back_left_c'])?$input['clipart_back_left_c']:0;
$input['clipart_back_right'] = !empty($input['clipart_back_right'])?$input['clipart_back_right']:0;
$input['clipart_back_right_c'] = !empty($input['clipart_back_right_c'])?$input['clipart_back_right_c']:0;
$input['clipart_front_left2'] = !empty($input['clipart_front_left2'])?$input['clipart_front_left2']:0;
$input['clipart_front_left2_c'] = !empty($input['clipart_front_left2_c'])?$input['clipart_front_left2_c']:0;
$input['clipart_front_right2'] = !empty($input['clipart_front_right2'])?$input['clipart_front_right2']:0;
$input['clipart_front_right2_c'] = !empty($input['clipart_front_right2_c'])?$input['clipart_front_right2_c']:0;
$input['clipart_back_left2'] = !empty($input['clipart_back_left2'])?$input['clipart_back_left2']:0;
$input['clipart_back_left2_c'] = !empty($input['clipart_back_left2_c'])?$input['clipart_back_left2_c']:0;
$input['clipart_back_right2'] = !empty($input['clipart_back_right2'])?$input['clipart_back_right2']:0;
$input['clipart_back_right2_c'] = !empty($input['clipart_back_right2_c'])?$input['clipart_back_right2_c']:0;


$id_clipart_front_left = $input['clipart_front_left'];
$id_clipart_front_left_c = $input['clipart_front_left_c'];
$id_clipart_front_right = $input['clipart_front_right'];
$id_clipart_front_right_c = $input['clipart_front_right_c'];
$id_clipart_back_left = $input['clipart_back_left'];
$id_clipart_back_left_c = $input['clipart_back_left_c'];
$id_clipart_back_right = $input['clipart_back_right'];
$id_clipart_back_right_c = $input['clipart_back_right_c'];
$id_clipart_front_left2 = $input['clipart_front_left2'];
$id_clipart_front_left2_c = $input['clipart_front_left2_c'];
$id_clipart_front_right2 = $input['clipart_front_right2'];
$id_clipart_front_right2_c = $input['clipart_front_right2_c'];
$id_clipart_back_left2 = $input['clipart_back_left2'];
$id_clipart_back_left2_c = $input['clipart_back_left2_c'];
$id_clipart_back_right2 = $input['clipart_back_right2'];
$id_clipart_back_right2_c = $input['clipart_back_right2_c'];

if(!empty($input['clipart_front_left']) && empty($input['clipart_front_left_c'])){$name_clipart_front_left = $clipart_module->getClipartName($tpt_vars, $input['clipart_front_left']);} else if(empty($input['clipart_front_left']) && !empty($input['clipart_front_left_c'])){$name_clipart_front_left = 'Custom Upload: ' . $input['clipart_front_left_c'];} else $name_clipart_front_left ='Select Front Left Clipart...';
if(!empty($input['clipart_front_right']) && empty($input['clipart_front_right_c'])){$name_clipart_front_right = $clipart_module->getClipartName($tpt_vars, $input['clipart_front_right']);} else if(empty($input['clipart_front_right']) && !empty($input['clipart_front_right_c'])){$name_clipart_front_right = 'Custom Upload: ' . $input['clipart_front_right_c'];} else $name_clipart_front_right = 'Select Front Right Clipart...';
if(!empty($input['clipart_back_left']) && empty($input['clipart_back_left_c'])){$name_clipart_back_left = $clipart_module->getClipartName($tpt_vars, $input['clipart_back_left']);} else if(empty($input['clipart_back_left']) && !empty($input['clipart_back_left_c'])){$name_clipart_back_left = 'Custom Upload: ' . $input['clipart_back_left_c'];} else $name_clipart_back_left = 'Select Back Left Clipart...';
if(!empty($input['clipart_back_right']) && empty($input['clipart_back_right_c'])){$name_clipart_back_right = $clipart_module->getClipartName($tpt_vars, $input['clipart_back_right']);} else if(empty($input['clipart_back_right']) && !empty($input['clipart_back_right_c'])){$name_clipart_back_right = 'Custom Upload: ' . $input['clipart_back_right_c'];} else $name_clipart_back_right = 'Select Back Right Clipart...';

if(!empty($input['clipart_front_left2']) && empty($input['clipart_front_left2_c'])){$name_clipart_front_left2 = $clipart_module->getClipartName($tpt_vars, $input['clipart_front_left2']);} else if(empty($input['clipart_front_left2']) && !empty($input['clipart_front_left2_c'])){$name_clipart_front_left2 = 'Custom Upload: ' . $input['clipart_front_left2_c'];} else $name_clipart_front_left2 = 'Select Front Left Clipart Ln2...';
if(!empty($input['clipart_front_right2']) && empty($input['clipart_front_right2_c'])){$name_clipart_front_right2 = $clipart_module->getClipartName($tpt_vars, $input['clipart_front_right2']);} else if(empty($input['clipart_front_right2']) && !empty($input['clipart_front_right2_c'])){$name_clipart_front_right2 = 'Custom Upload: ' . $input['clipart_front_right2_c'];} else $name_clipart_front_right2 = 'Select Front Right Clipart Ln2...';
if(!empty($input['clipart_back_left2']) && empty($input['clipart_back_left2_c'])){$name_clipart_back_left2 = $clipart_module->getClipartName($tpt_vars, $input['clipart_back_left2']);} else if(empty($input['clipart_back_left2']) && !empty($input['clipart_back_left2_c'])){$name_clipart_back_left2 = 'Custom Upload: ' . $input['clipart_back_left2_c'];} else $name_clipart_back_left2 = 'Select Back Left Clipart Ln2...';
if(!empty($input['clipart_back_right2']) && empty($input['clipart_back_right2_c'])){$name_clipart_back_right2 = $clipart_module->getClipartName($tpt_vars, $input['clipart_back_right2']);} else if(empty($input['clipart_back_right2']) && !empty($input['clipart_back_right2_c'])){$name_clipart_back_right2 = 'Custom Upload: ' . $input['clipart_back_right2_c'];} else $name_clipart_back_right2 = 'Select Back right Clipart Ln2...';


$value_clipart_front_left = !empty($input['clipart_front_left'])?$input['clipart_front_left']:'';
$value_clipart_front_left_c = !empty($input['clipart_front_left_c'])?$input['clipart_front_left_c']:'';
$value_clipart_front_right = !empty($input['clipart_front_right'])?$input['clipart_front_right']:'';
$value_clipart_front_right_c = !empty($input['clipart_front_right_c'])?$input['clipart_front_right_c']:'';
$value_clipart_back_left = !empty($input['clipart_back_left'])?$input['clipart_back_left']:'';
$value_clipart_back_left_c = !empty($input['clipart_back_left_c'])?$input['clipart_back_left_c']:'';
$value_clipart_back_right = !empty($input['clipart_back_right'])?$input['clipart_back_right']:'';
$value_clipart_back_right_c = !empty($input['clipart_back_right_c'])?$input['clipart_back_right_c']:'';
$value_clipart_front_left2 = !empty($input['clipart_front_left2'])?$input['clipart_front_left2']:'';
$value_clipart_front_left2_c = !empty($input['clipart_front_left2_c'])?$input['clipart_front_left2_c']:'';
$value_clipart_front_right2 = !empty($input['clipart_front_right2'])?$input['clipart_front_right2']:'';
$value_clipart_front_right2_c = !empty($input['clipart_front_right2_c'])?$input['clipart_front_right2_c']:'';
$value_clipart_back_left2 = !empty($input['clipart_back_left2'])?$input['clipart_back_left2']:'';
$value_clipart_back_left2_c = !empty($input['clipart_back_left2_c'])?$input['clipart_back_left2_c']:'';
$value_clipart_back_right2 = !empty($input['clipart_back_right2'])?$input['clipart_back_right2']:'';
$value_clipart_back_right2_c = !empty($input['clipart_back_right2_c'])?$input['clipart_back_right2_c']:'';


$pgClipartFrontLeft_c_undisplay = 'display-none';
$pgClipartFrontLeft_c_img = '';
if(!empty($pgClipartFrontLeft_c)) {
	$pgClipartFrontLeft_c_undisplay = '';
	$pgClipartFrontLeft_c_img = '
	<img class="upper_img cust_clip_pic_uploaded" src = "'.BASE_URL.'/generate-preview?timestamp='.time().'&image='.urlencode($pgClipartFrontLeft_c).'&type=convertcustomart&pg_x=80&pg_y=80" >
	';
}
$pgClipartFrontRight_c_undisplay = 'display-none';
$pgClipartFrontRight_c_img = '';
if(!empty($pgClipartFrontRight_c)) {
	$pgClipartFrontRight_c_undisplay = '';
	$pgClipartFrontRight_c_img = '
	<img class="upper_img cust_clip_pic_uploaded" src = "'.BASE_URL.'/generate-preview?timestamp='.time().'&image='.urlencode($pgClipartFrontRight_c).'&type=convertcustomart&pg_x=80&pg_y=80" >
	';
}
$pgClipartBackLeft_c_undisplay = 'display-none';
$pgClipartBackLeft_c_img = '';
if(!empty($pgClipartBackLeft_c)) {
	$pgClipartBackLeft_c_undisplay = '';
	$pgClipartBackLeft_c_img = '
	<img class="upper_img cust_clip_pic_uploaded" src = "'.BASE_URL.'/generate-preview?timestamp='.time().'&image='.urlencode($pgClipartBackLeft_c).'&type=convertcustomart&pg_x=80&pg_y=80" >
	';
}
$pgClipartBackRight_c_undisplay = 'display-none';
$pgClipartBackRight_c_img = '';
if(!empty($pgClipartBackRight_c)) {
	$pgClipartBackRight_c_undisplay = '';
	$pgClipartBackRight_c_img = '
	<img class="upper_img cust_clip_pic_uploaded" src = "'.BASE_URL.'/generate-preview?timestamp='.time().'&image='.urlencode($pgClipartBackRight_c).'&type=convertcustomart&pg_x=80&pg_y=80" >
	';
}
$pgClipartFrontLeft2_c_undisplay = 'display-none';
$pgClipartFrontLeft2_c_img = '';
if(!empty($pgClipartFrontLeft2_c)) {
	$pgClipartFrontLeft2_c_undisplay = '';
	$pgClipartFrontLeft2_c_img = '
	<img class="upper_img cust_clip_pic_uploaded" src = "'.BASE_URL.'/generate-preview?timestamp='.time().'&image='.urlencode($pgClipartFrontLeft2_c).'&type=convertcustomart&pg_x=80&pg_y=80" >
	';
}
$pgClipartFrontRight2_c_undisplay = 'display-none';
$pgClipartFrontRight2_c_img = '';
if(!empty($pgClipartFrontRight2_c)) {
	$pgClipartFrontRight2_c_undisplay = '';
	$pgClipartFrontRight2_c_img = '
	<img class="upper_img cust_clip_pic_uploaded" src = "'.BASE_URL.'/generate-preview?timestamp='.time().'&image='.urlencode($pgClipartFrontRight2_c).'&type=convertcustomart&pg_x=80&pg_y=80" >
	';
}
$pgClipartBackLeft2_c_undisplay = 'display-none';
$pgClipartBackLeft2_c_img = '';
if(!empty($pgClipartBackLeft2_c)) {
	$pgClipartBackLeft2_c_undisplay = '';
	$pgClipartBackLeft2_c_img = '
	<img class="upper_img cust_clip_pic_uploaded" src = "'.BASE_URL.'/generate-preview?timestamp='.time().'&image='.urlencode($pgClipartBackLeft2_c).'&type=convertcustomart&pg_x=80&pg_y=80" >
	';
}
$pgClipartBackRight2_c_undisplay = 'display-none';
$pgClipartBackRight2_c_img = '';
if(!empty($pgClipartBackRight2_c)) {
	$pgClipartBackRight2_c_undisplay = '';
	$pgClipartBackRight2_c_img = '
	<img class="upper_img cust_clip_pic_uploaded" src = "'.BASE_URL.'/generate-preview?timestamp='.time().'&image='.urlencode($pgClipartBackRight2_c).'&type=convertcustomart&pg_x=80&pg_y=80" >
	';
}



$cccls = 'display-none';
if(
	!empty($pgClipartFrontLeft) ||
	!empty($pgClipartFrontLeft_c) ||
	!empty($pgClipartFrontRight) ||
	!empty($pgClipartFrontRight_c) ||
	!empty($pgClipartBackLeft) ||
	!empty($pgClipartBackLeft_c) ||
	!empty($pgClipartBackRight) ||
	!empty($pgClipartBackRight_c) ||
	!empty($pgClipartFrontLeft2) ||
	!empty($pgClipartFrontLeft2_c) ||
	!empty($pgClipartFrontRight2) ||
	!empty($pgClipartFrontRight2_c) ||
	!empty($pgClipartBackLeft2) ||
	!empty($pgClipartBackLeft2_c) ||
	!empty($pgClipartBackRight2) ||
	!empty($pgClipartBackRight2_c)
) {
	$cccls = '';
}


$custom_clipart_upload_a = '<input class="cust_clip_upload" onclick="cust_clip_upload_handle(this); $(\'#upload\').click();" type="button" value="Upload Custom Clipart..." />';

$custom_clipart_input_combo = '
';

$section_add_artwork_style = <<< EOT

<style type="text/css">
.cust_clip_upload {
border: 0;
margin-bottom: 10px;
border-radius: 10px;
}

img.cust_clip_pic_uploaded.upper_img {
float: right;
position: relative;
top: -25px;
right: 10px;
}

img.cust_clip_pic_uploaded {
max-width:40px;
max-height:40px;
}

.clip_select_trigger {
float: left;
overflow: hidden;
display: block !important;
white-space: nowrap;
}

a.btn_cca {
display:none;
}

a.remove_clipart {
padding-top: 7px;
}

</style>

EOT;

$tpt_vars['template_data']['head'][] = $section_add_artwork_style;

$ccurl = CUSTOM_CLIPART_URL;

$section_add_artwork = <<< EOT

<script type="text/javascript">


window.cus_uploaded_files_cur_pos = false;

window.cus_uploaded_files = {};
	
window.uploaded_check_active = false;

window.uploaded_file_stamp = false;

window.cust_clip_upload_handle = function(el) {

	cus_uploaded_files_cur_pos = $(el).parent().find('input[type="hidden"]').attr('id').replace('_ctr', '_c');
	
	cus_uploaded_files_cur_pos_PRID = $(el).parent().find('input[type="hidden"]').attr('id').replace(/_(l|r)clipart_ctr/,'');
	
	var uploaded_check = function(pass) {
	
		var c = uploaded_check_active==false && pass!=true;
	
		if (!c && uploaded_check_active && pass!=true) return;
		
		if ($('#clipart_container #files').text()=='' || $('#clipart_container #files').text()==uploaded_file_stamp) {
			
			uploaded_check_active = true;
		
			//console.log('not uploaded yet');
			
			setTimeout(uploaded_check,1000,true);
			
		} else {
		
			//console.log('yes uploaded is');
			
			uploaded_check_active = false;
			
			uploaded_file_stamp = $('#clipart_container #files').text();
			
			cus_uploaded_files[cus_uploaded_files_cur_pos] = uploaded_file_stamp;
			
			$('#'+cus_uploaded_files_cur_pos).val(uploaded_file_stamp);
			
			cust_clip_summary_handle(el);

			//tpt_pg_generate_prevew_short(msg_id.replace('_rclipart_c', '').replace('_lclipart_c', ''));
			
		//	console.log($('input#'+cus_uploaded_files_cur_pos_PRID+'_message'),cus_uploaded_files_cur_pos_PRID);
			
		//	tpt_pg_generate_prevew_short(cus_uploaded_files_cur_pos_PRID);
		//	_short_tpt_pg_generate_prevew_all();
			
			$('input#'+cus_uploaded_files_cur_pos_PRID+'_message').focus();
			setTimeout(function(){
				$('input#'+cus_uploaded_files_cur_pos_PRID+'_message').blur();
			},100);



		}
	
	}
	
	uploaded_check();


}


window.cust_clip_remove = function(el) {
	
	$(el).parent().find('img.upper_img.cust_clip_pic_uploaded').remove();
	
	var cst = $(el).parent().find('span.clip_select_trigger');
	
//	console.log($(el).parent()[0],cst[0]);
	
	setTimeout(function(){
		$(cst).text($(cst).attr('title'));
	},300);
	
	var cus_uploaded_files_cur_pos__ = $(el).parent().find('input[type="hidden"]').attr('class');
	
//	console.log($('input[name="'+cus_uploaded_files_cur_pos__+'clipart_c"]')[0]);
	
	$('input[name="'+cus_uploaded_files_cur_pos__+'clipart_c"]').val('');

} 

window.cust_clip_summary_handle = function(el) {

	$(el).parent().find('a.remove_clipart').removeClass('display-none');
	
	$(el).parent().find('img.upper_img.cust_clip_pic_uploaded').remove();
	
	$(el).parent().find('span.clip_select_trigger').text('Custom Upload: '+uploaded_file_stamp);
	
//	$(el).after('<img class="upper_img cust_clip_pic_uploaded" src="$ccurl/'+uploaded_file_stamp+'" />');
	$(el).after('<img class="upper_img cust_clip_pic_uploaded" src="'+base_url+'/generate-preview?timestamp='+(new Date).getTime()+'&image='+uploaded_file_stamp+'&type=convertcustomart&pg_x=80&pg_y=80" />');
		
	if ($('#cus_clip_sum').length==0) {
		$('#clipart_container #files').after('<div id="cus_clip_sum"></div>');
	}
	
	$('#cus_clip_sum').html('');

/*
	$.each(cus_uploaded_files,function(i,v){
		
		var t = $('#cus_clip_sum').html();
		
		var a = '<img class="cust_clip_pic_uploaded" src="$ccurl/'+v+'" /> ' + i + ' : ' + v + "<br />";
		
		$('#cus_clip_sum').html(t+''+a);
		
		
	}); */
	
	$('#clipart_container #files').html('');

}


window.enable_clipart_cb_init_f = function(){
	
	if ($('#enable_clipart_cb.init__').length > 0) return;
	
	$('#enable_clipart_cb').addClass('init__');
	
	$('#enable_clipart_cb').click(function(){
		setTimeout(function(){
			if(!$('#enable_clipart_cb')[0].checked){
				$('a.remove_clipart').not('.display-none').each(function(){
					$(this).click();
				});
			}
		},200);
	});
}





</script>

<div class="$cccls" id="clipart_container">
    <div id="tpt_pg_front_clipart_wrap" class="$fclipdisplay">
        <div id="tpt_pg_front_clipart">
            <div id="clpfl">
                <div class="arial-black amz_brown font-size-14 font-weight-bold">Front Left Clipart: </div>
                <span class="clip_select_trigger" id="front_left_trigger" title="Select Front Left Clipart..." onclick="try{click_artwrk_sel(this,'tpt_pg_front_lclipart_ctr');}catch(e){};">$name_clipart_front_left</span>
                <input class="fl" type="hidden" name="flclipart" title="Select Front Left Clipart..." id="tpt_pg_front_lclipart_ctr" value="$value_clipart_front_left" />
                <a id="remove_clipart_tpt_pg_front_lclipart_ctr" href="#" class="remove_clipart $pgClipartFrontLeft_c_undisplay" onclick="cust_clip_remove(this); $('#tpt_pg_front_lclipart_ctr').val(''); addClass(document.getElementById('remove_clipart_tpt_pg_front_lclipart_ctr'), 'display-none'); return false;">[X]</a>
                $custom_clipart_upload_a
                $pgClipartFrontLeft_c_img
                <br />
                $exclpfl
            </div>
            <div id="clpfr">
                <div class="arial-black amz_brown font-size-14 font-weight-bold">Front Right Clipart: </div>
                <span class="clip_select_trigger" id="front_right_trigger" title="Select Front Right Clipart..." onclick="try{click_artwrk_sel(this,'tpt_pg_front_rclipart_ctr');}catch(e){};">$name_clipart_front_right</span>
                <input class="fr" type="hidden" name="frclipart" title="Select Front Right Clipart..." id="tpt_pg_front_rclipart_ctr" value="$value_clipart_front_right" />
                <a id="remove_clipart_tpt_pg_front_rclipart_ctr" href="#" class="remove_clipart $pgClipartFrontRight_c_undisplay" onclick="cust_clip_remove(this); $('#tpt_pg_front_rclipart_ctr').val(''); addClass(document.getElementById('remove_clipart_tpt_pg_front_rclipart_ctr'), 'display-none'); return false;">[X]</a>
                $custom_clipart_upload_a
                $pgClipartFrontRight_c_img
                <br />
                $exclpfr
            </div>
        </div>
        <div id="tpt_pg_front2_clipart" class="$fl2display">
            <div id="clpfl2">
                <div class="arial-black amz_brown font-size-14 font-weight-bold">Front Left Clipart Ln2: </div>
                <span class="clip_select_trigger" id="front_left_trigger2" title="Select Front Left Clipart Ln2..." onclick="try{click_artwrk_sel(this,'tpt_pg_front2_lclipart_ctr');}catch(e){};">$name_clipart_front_left2</span>
                <input class="fl2" type="hidden" name="flclipart2" title="Select Front Left Clipart Ln2..." id="tpt_pg_front2_lclipart_ctr" value="$value_clipart_front_left2" />
                <a id="remove_clipart_tpt_pg_front2_lclipart_ctr" href="#" class="remove_clipart $pgClipartFrontLeft2_c_undisplay" onclick="cust_clip_remove(this); $('#tpt_pg_front2_lclipart_ctr').val(''); addClass(document.getElementById('remove_clipart_tpt_pg_front2_lclipart_ctr'), 'display-none'); return false;">[X]</a>
				$custom_clipart_upload_a
				$pgClipartFrontLeft2_c_img
            </div>
            <div id="clpfr2">
                <div class="arial-black amz_brown font-size-14 font-weight-bold">Front Right Clipart Ln2: </div>
                <span class="clip_select_trigger" id="front_right_trigger2" title="Select Front Right Clipart Ln2..." onclick="try{click_artwrk_sel(this,'tpt_pg_front2_rclipart_ctr');}catch(e){};">$name_clipart_front_right2</span>
                <input class="fr2" type="hidden" name="frclipart2" title="Select Front Right Clipart Ln2..." id="tpt_pg_front2_rclipart_ctr" value="$value_clipart_front_right2" />
                <a id="remove_clipart_tpt_pg_front2_rclipart_ctr" href="#" class="remove_clipart $pgClipartFrontRight2_c_undisplay" onclick="cust_clip_remove(this); $('#tpt_pg_front2_rclipart_ctr').val(''); addClass(document.getElementById('remove_clipart_tpt_pg_front2_rclipart_ctr'), 'display-none'); return false;">[X]</a>
				$custom_clipart_upload_a
				$pgClipartFrontRight2_c_img
            </div>
        </div>
    </div>
    <div id="tpt_pg_back_clipart_wrap" class="$bdisplay $bclipdisplay">
        <div id="tpt_pg_back_clipart">
            <div id="clpbl">
                <div class="arial-black amz_brown font-size-14 font-weight-bold">Back Left Clipart: </div>
                <span class="clip_select_trigger" id="back_left_trigger" title="Select Back Left Clipart..." onclick="try{click_artwrk_sel(this,'tpt_pg_back_lclipart_ctr');}catch(e){};">$name_clipart_back_left</span>
                <input class="bl" type="hidden" name="blclipart" title="Select Back Left Clipart..." id="tpt_pg_back_lclipart_ctr" value="$value_clipart_back_left" />
                <a id="remove_clipart_tpt_pg_back_lclipart_ctr" href="#" class="remove_clipart $pgClipartBackLeft_c_undisplay" onclick="cust_clip_remove(this); $('#tpt_pg_back_lclipart_ctr').val(''); addClass(document.getElementById('remove_clipart_tpt_pg_back_lclipart_ctr'), 'display-none'); return false;">[X]</a>
				$custom_clipart_upload_a
				$pgClipartBackLeft_c_img
            </div>
            <div id="clpbr">
                <div class="arial-black amz_brown font-size-14 font-weight-bold">Back Right Clipart: </div>
                <span class="clip_select_trigger" id="back_right_trigger" title="Select Back Right Clipart..." onclick="try{click_artwrk_sel(this,'tpt_pg_back_rclipart_ctr');}catch(e){};">$name_clipart_back_right</span>
                <input class="br" type="hidden" name="brclipart" title="Select Back Right Clipart..." id="tpt_pg_back_rclipart_ctr" value="$value_clipart_back_right" />
                <a id="remove_clipart_tpt_pg_back_rclipart_ctr" href="#" class="remove_clipart $pgClipartBackRight_c_undisplay" onclick="cust_clip_remove(this); $('#tpt_pg_back_rclipart_ctr').val(''); addClass(document.getElementById('remove_clipart_tpt_pg_back_rclipart_ctr'), 'display-none'); return false;">[X]</a>
				$custom_clipart_upload_a
				$pgClipartBackRight_c_img
            </div>
        </div>
        <div id="tpt_pg_back2_clipart" class="$bl2display">
            <div id="clpbl2">
                <div class="arial-black amz_brown font-size-14 font-weight-bold">Back Left Clipart Ln2: </div>
                <span class="clip_select_trigger" id="back_left_trigger2" title="Select Back Left Clipart Ln2..." onclick="try{click_artwrk_sel(this,'tpt_pg_back2_lclipart_ctr');}catch(e){};">$name_clipart_back_left2</span>
                <input class="bl2" type="hidden" name="blclipart2" title="Select Back Left Clipart Ln2..." id="tpt_pg_back2_lclipart_ctr" value="$value_clipart_back_left2" />
                <a id="remove_clipart_tpt_pg_back2_lclipart_ctr" href="#" class="remove_clipart $pgClipartBackLeft2_c_undisplay" onclick="cust_clip_remove(this); $('#tpt_pg_back2_lclipart_ctr').val(''); addClass(document.getElementById('remove_clipart_tpt_pg_back2_lclipart_ctr'), 'display-none'); return false;">[X]</a>
				$custom_clipart_upload_a
				$pgClipartBackLeft2_c_img
            </div>
            <div id="clpbr2">
                <div class="arial-black amz_brown font-size-14 font-weight-bold">Back Right Clipart Ln2: </div>
                <span class="clip_select_trigger" id="back_right_trigger2" title="Select Back Right Clipart Ln2..." onclick="try{click_artwrk_sel(this,'tpt_pg_back2_rclipart_ctr');}catch(e){};">$name_clipart_back_right2</span>
                <input class="br2" type="hidden" name="brclipart2" title="Select Back Right Clipart Ln2..." id="tpt_pg_back2_rclipart_ctr" value="$value_clipart_back_right2" />
                <a id="remove_clipart_tpt_pg_back2_rclipart_ctr" href="#" class="remove_clipart $pgClipartBackRight2_c_undisplay" onclick="cust_clip_remove(this); $('#tpt_pg_back2_rclipart_ctr').val(''); addClass(document.getElementById('remove_clipart_tpt_pg_back2_rclipart_ctr'), 'display-none'); return false;">[X]</a>
				$custom_clipart_upload_a
				$pgClipartBackRight2_c_img
            </div>
        </div>
    </div>
    
    $custom_clipart_input_combo
    
    $custom_clipart_upload
    $custom_clipart_convert_check
</div>



<div class="ccc_wr">
<a class="thickbox view-all-artwork" href="javascript:/*TB_inline?width=900&amp;height=550&amp;inlineId=_*/">All Artwork</a>
</div>


EOT;

