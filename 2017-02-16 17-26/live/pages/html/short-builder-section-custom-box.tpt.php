<?php
// some custom form elements preloaded on the page
// these elements stay hidden and they are cloned where needed
?>
<div class="root-custombox-elements">
<div class="save_button_wrap"><a class="cc_save" href="#" onclick="custom_color_save(); return false;"></a></div>
	
	<?php // custom color type select ?>
	<div class="color_type_selector">
		<div class="lightbox-title">Select Custom Color Type: 
			<label class="amz_brown font-size-14 font-weight-bold" style="font-family: Arial, Helvetica, sans-serif;" for="solid_colors_radio">Solid</label> <input type="radio" name="color_type" value="1" onclick="change_custom_color_type('color_controls_solid');" id="solid_colors_radio">
			<label class="amz_brown font-size-14 font-weight-bold" style="font-family: Arial, Helvetica, sans-serif;" for="swirl_colors_radio">Swirl</label> <input type="radio" name="color_type" value="2" onclick="change_custom_color_type('color_controls_swirl');" id="swirl_colors_radio">
			<label class="amz_brown font-size-14 font-weight-bold" style="font-family: Arial, Helvetica, sans-serif;" for="segmented_colors_radio">Segmented</label> <input type="radio" name="color_type" value="3" onclick="change_custom_color_type('color_controls_segmented');" id="segmented_colors_radio">
		</div>
		<div class="color_type_options" id="color_type_options_id"></div>
	</div>
	
		<?php // custom solid color ?>
		<div class="solid_colors">
			<div class="lightbox-title">Create Custom Solid Color</div>
			<div class="color_controls_solid" id="color_controls_solid">
				<div class="round-orange" style="width: 120px;">Custom Colors :</div>
				<input type="text" id="custom_color_holder" value="" onchange="color_select_by_text(this);" onblur="color_select_by_text(this);" />
				<input type="hidden" id="custom_color_holder_id" value="" />
			</div>
		</div>
	
		<?php // custom swirl ?>
		<div class="swirl_colors">
			<div class="lightbox-title">Create Custom Swirl</div>
			<div class="color_controls_swirl" id="color_controls_swirl">
				
				<div class="custom_color_select_set donor">
					<div class="round-orange">Custom Swirl Color :</div>
					<a class="select-color-btn" href="#" onclick="select_custom_color_array(this); return false;"></a>
					<input type="text" value="" onclick="select_custom_color_array(this);" onchange="color_select_by_text(this);" onblur="color_select_by_text(this);" />
					<a class="remove_cc" href="#" onclick="remove_cc(this); return false;">[X]</a>
				</div>
	
				<div class="custom_color_select_set">
					<div class="round-orange">Custom Swirl Color :</div>
					<a class="select-color-btn" href="#" onclick="select_custom_color_array(this); return false;"></a>
					<input type="text" value="" onclick="select_custom_color_array(this);" onchange="color_select_by_text(this);" onblur="color_select_by_text(this);" />
				</div>
				
				<div class="custom_color_select_set">
					<div class="round-orange">Custom Swirl Color :</div>
					<a class="select-color-btn" href="#" onclick="select_custom_color_array(this); return false;"></a>
					<input type="text" value="" onclick="select_custom_color_array(this);" onchange="color_select_by_text(this);" onblur="color_select_by_text(this);" />
				</div>
				
				<a class="add_new_custom_color" href="#" onclick="add_new_custom_color(); return false;">Add new color</a>
			</div>
		</div>
		
		<?php // custom segment ?>
		<div class="segmented_colors">
			<div class="lightbox-title">Create Custom Segment</div>
			<div class="color_controls_segmented" id="color_controls_segmented">
				
				<div class="custom_color_select_set donor">
					<div class="round-orange">Custom Segment Color :</div>
					<a class="select-color-btn" href="#" onclick="select_custom_color_array(this); return false;"></a>
					<input type="text" value="" onclick="select_custom_color_array(this);" onchange="color_select_by_text(this);" onblur="color_select_by_text(this);" />
					<a class="remove_cc" href="#" onclick="remove_cc(this); return false;">[X]</a>
				</div>
	
				<div class="custom_color_select_set">
					<div class="round-orange">Custom Segment Color :</div>
					<a class="select-color-btn" href="#" onclick="select_custom_color_array(this); return false;"></a>
					<input type="text" value="" onclick="select_custom_color_array(this);" onchange="color_select_by_text(this);" onblur="color_select_by_text(this);" />
				</div>
				
				<div class="custom_color_select_set">
					<div class="round-orange">Custom Segment Color :</div>
					<a class="select-color-btn" href="#" onclick="select_custom_color_array(this); return false;"></a>
					<input type="text" value="" onclick="select_custom_color_array(this);" onchange="color_select_by_text(this);" onblur="color_select_by_text(this);" />
				</div>
				
				<a class="add_new_custom_color" href="#" onclick="add_new_custom_color(); return false;">Add new color</a>
			</div>
		</div>
		
		<div class="message-color-control">
			<div class="round-orange">Message Color :</div>
			<a class="select-color-btn" href="#" onClick="return false;"></a>
		</div>
	
	<?php // all pms table ?>
	<div class="all_pms"></div>
	
	<div class="all_fonts"></div>

	<div class="all_artwork"></div>

	<script type="text/javascript">
		setTimeout(function(){
			$('.root-custombox-elements > .all_pms').load(
				base_url+'/short_builder_ajax',
				{'action':'get_all_pms'},
				function(){$('.root-custombox-elements > .all_pms').prepend(
					'<div class="stdall"><a class="std" href="javascript:StandardColors();">Standard Colors</a> | <a class="ac all" href="javascript:AllColors();">All Colors</a></div>'
					
				)}
			);
		},300);
		<?php
		// populating band and msg colors from the get array ... needed for duplicate and edit
		$GET_band_clr = $GET_msg_clr = '';
		if (!empty($_GET['band_color'])) $GET_band_clr = $_GET['band_color'];
		if (!empty($_GET['message_color'])) $GET_msg_clr = $_GET['message_color'];
		echo "\n".'GET_band_clr = "'.$GET_band_clr.'";'."\n".'GET_msg_clr = "'.$GET_msg_clr.'";'."\n\n";

		// preloading lightbox elements on modern browsers
		$httpUserAgent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
		if (!preg_match('/MSIE (7|8)/', $httpUserAgent)) {
		?>
			setTimeout(function(){
				$('.root-custombox-elements > .all_fonts').load(base_url+'/short_builder_ajax',{'action':'get_all_fonts'});
			},600);
			
			setTimeout(function(){
				$('.root-custombox-elements > .all_artwork').load(base_url+'/short_builder_ajax',{'action':'get_artwork_panel'});
			},900);
		<?php	}	?>
	</script>
</div>
