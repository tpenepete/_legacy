<?php

defined('TPT_INIT') or die('access denied');

$types_module = getModule($tpt_vars, 'BandType');
$styles_module = getModule($tpt_vars, 'BandStyle');
$data_module = getModule($tpt_vars, 'BandData');
$sizes_module = getModule($tpt_vars, 'BandSize');
$colors_module = getModule($tpt_vars, 'BandColor');
$clipart_module = getModule($tpt_vars, 'BandClipart');
$ccat_module = getModule($tpt_vars, 'BandClipartCategory');
$pfields_module = getModule($tpt_vars, 'CustomProductField');
$layers_module = getModule($tpt_vars, 'PreviewLayer');
$builders_module = getModule($tpt_vars, 'Builder');
$sections_module = getModule($tpt_vars, 'BuilderSection');
//tpt_dump($sections_module);



if(!isset($input)) {
	$input = array_replace($_GET, $_POST);
}

if(!isset($options)) {
	$url_builders = $builders_module->moduleData['url_id'];
	$id_builders = $builders_module->moduleData['id'];

	$builder = array();
	$builder_id = 0;
	$url_id = 0;
	$builder_title_new = '';
	if(empty($_POST['short_builder'])) {
		$url_id = $tpt_vars['environment']['page_rule']['id'];
		$builder = $url_builders[$url_id];
	} else {
		$builder_id = intval($_POST['short_builder'], 10);
		$builder = $id_builders[$builder_id];
		$url_id = $builder['url_id'];
	}
	$options = $builder;
}


$dtype = $types_module->getDefaultItem($tpt_vars, $input, $options);
$addcslashes_dtype = addcslashes($dtype, '\'');
$dstyle = $styles_module->getDefaultItem($tpt_vars, $input, $options);
$addcslashes_dstyle = addcslashes($dstyle, '\'');
$type = $types_module->getActiveItem($tpt_vars, $input, $options);
$style = $styles_module->getActiveItem($tpt_vars, $input, $options);

$bdata = $data_module->typeStyle[$type][$style];
//tpt_dump($type.$style, false, 'R');
//tpt_dump($bdata, false, 'R');


if(((isDev('rebuildcontent') && !empty($_GET['rebuildcontent'])))) {
	$types_json = $types_module->userEndData($tpt_vars);
	$styles_json = $styles_module->userEndData($tpt_vars);
	$sizes_json = $sizes_module->userEndData($tpt_vars);
	$data_json = $data_module->userEndData($tpt_vars);

	$pfields_json = $pfields_module->userEndData($tpt_vars);
	$layers_json = $layers_module->userEndData($tpt_vars);
	$sections_json = $sections_module->userEndData($tpt_vars);

    //tpt_dump($sections_json);
	$vsections0_json = $sections_module->userEndData_validate0($tpt_vars);
	$vsections1_json = $sections_module->userEndData_validate1($tpt_vars);
	$vsections2_json = $sections_module->userEndData_validate2($tpt_vars);

	$colors_data = $colors_module->userEndData($tpt_vars);
	$colors_json = $colors_data['stock_to_custom'];
	$default_json = $colors_data['default'];
	$dual_layers_json = $colors_data['dual_layers'];
	$solids_hex = $colors_data['solids_hex'];
	$json = 'var stockToCustomColors = [];'."\n";
	$json .= 'stockToCustomColors[3] = JSON.parse("'.addslashes(json_encode($colors_json[3])).'");'."\n";
	$json .= 'stockToCustomColors[4] = JSON.parse("'.addslashes(json_encode($colors_json[4])).'");'."\n";
	$json .= 'stockToCustomColors[5] = JSON.parse("'.addslashes(json_encode($colors_json[5])).'");'."\n";
	$json .= 'stockToCustomColors[6] = JSON.parse("'.addslashes(json_encode($colors_json[6])).'");'."\n";
	$json .= 'dualLayerData = JSON.parse("'.addslashes(json_encode($dual_layers_json)).'");'."\n";
	$json .= 'defaultData = JSON.parse("'.addslashes(json_encode($default_json)).'");'."\n";

	$json .= 'var solidColorsHEX = JSON.parse("'.addslashes(json_encode($solids_hex)).'");'."\n";

	$json .= 'var typesData = JSON.parse("'.addslashes(json_encode($types_json)).'");'."\n";
	$json .= 'var stylesData = JSON.parse("'.addslashes(json_encode($styles_json)).'");'."\n";
	$json .= 'var sizesData = JSON.parse("'.addslashes(json_encode($sizes_json)).'");'."\n";

	$json .= 'var bandData = JSON.parse("'.addslashes(json_encode($data_json)).'");'."\n";

	$json .= 'var fieldsData = JSON.parse("'.addslashes(json_encode($pfields_json)).'");'."\n";
	$json .= 'var layersData = JSON.parse("'.addslashes(json_encode($layers_json)).'");'."\n";
	$json .= 'var sectionsData = JSON.parse("'.addslashes(json_encode($sections_json)).'");'."\n";
	$json .= 'var vSectionsData0 = JSON.parse("'.addslashes(json_encode($vsections0_json)).'");'."\n";
	$json .= 'var vSectionsData1 = JSON.parse("'.addslashes(json_encode($vsections1_json)).'");'."\n";
	$json .= 'var vSectionsData2 = JSON.parse("'.addslashes(json_encode($vsections2_json)).'");'."\n";

	$builder_json = <<< EOT
$json
var floatingPGPreview = false;
EOT;

	file_put_contents(TPT_JS_DIR.DIRECTORY_SEPARATOR.'builder_json.js', $builder_json);
}





$clipartpopup_style = $ccat_module->BandClipart_Panel2_Aux_Style($tpt_vars);
$tpt_vars['template_data']['head'][] = <<< EOT
<style type="text/css">
$clipartpopup_style
</style>


<script type="text/javascript">
//<![CDATA[
var pgloading = [];

var pgdefault = [];
pgdefault['type'] = '$addcslashes_dtype';
pgdefault['style'] = '$addcslashes_dstyle';

var pType = $type;
var pStyle = $style;


document.addEventListener("DOMContentLoaded", function(event) {
	///$('a.read-more').click(function(){
	$('#main_content').on('click','a.read-more',function(e){
		$(this).siblings('.more-descr').slideToggle(1000);
		if ($(this).text().match(/more/)) {
			$(this).html('Hide...');
		} else {
			$(this).html('Read more...');
		}
	});
});

// closing modal window on esc
document.onkeydown = function(e){
	if (e == null) { // ie
		keycode = event.keyCode;
	} else { // mozilla
		keycode = e.which;
	}

	if(keycode == 27){ // close
		$('#font_palet > a').each( function() {
			$( this ).attr( 'onmouseover', '' );
		});
		try{UnTip();}catch(e){}
		if ( typeof hide_lightbox === 'function' ) {
			hide_lightbox();
		}
		if ( typeof clear_tlcontent === 'function' ) {
			clear_tlcontent();
		}
	}
};

var csubcatclass_regexp = new RegExp(/clipartMainCategory_[-a-zA-Z0-9]/);
function ccat_click2(src, pname, hasSubcat) {
	if(!src.tagName || (src.tagName.toLowerCase() != 'a')) {
		return;
	}

	if(!document.getElementById('clipartscat')) {
		return;
	}

    
	removeClass(document.getElementById('clipartscat'), csubcatclass_regexp);
	addClass(document.getElementById('clipartscat'), 'clipartMainCategory_'+src.rel);
	
	
	//Renold
	$('.clipartMain').addClass('display-none');
	
	if($('#clipartscat').hasClass('display-none')) {
	    $('#clipartscat').removeClass('display-none');
	}
	
	$('#back-to-main').removeClass('display-none');
	$('#ccategory').html(src.rel);

	if(!hasSubcat) {
	    $('#clipartscat').addClass('display-none');
	    $('#clipartsitems').html('loading...');
	    $('#clipartsitems').removeClass('display-none');
		goGetSome('shortbuilder.list_clipart', src);
	}
}

function back_to_main(){
    $('.clipartMain').removeClass('display-none');
    $('#clipartscat').addClass('display-none');
    $('#clipartsitems').addClass('display-none');
    $('#back-to-main').addClass('display-none');
}

function clear_tlcontent(){
    $('#tpt_lightbox_content').html('loading...');
}



function unlock_text_control(src) {
	var trgtid = src.id.replace('control_', '');
	var section = sectionsData[trgtid];

	if(document.getElementById(section['name']) && document.getElementById(section['name']+'_unlock') && src && (!document.getElementById(section['name']+'_unlock').value)) {
		//src.disabled = false;
		document.getElementById(section['name']).disabled = false;
		removeClass(src, 'disabled_control');
		document.getElementById(section['name']).value = '';
		document.getElementById(section['name']+'_unlock').value = '1';
		src.value = '';
	}
}

function lock_text_control(src) {
	var trgtid = src.id.replace('control_', '');
	var section = sectionsData[trgtid];

	if(document.getElementById(section['name']) && !document.getElementById(section['name']).disabled) {
		//src.disabled = true;
		document.getElementById(section['name']).disabled = true;
		document.getElementById(section['name']).value = '';
		src.value = '';
	}
}



function toggle_section(src, trgt, option) {
	/*
	var trgt;
	if(!(trgt = src.parentNode)) {
		return;
	}
	if(!(trgt = trgt.parentNode)) {
		return;
	}
	if(!trgt.className) {
		return;
	}
	*/
	if(!trgt) {
		return 0;
	}

	if(trgt.className.match('displaynone1')) {
		unlock_text_control(document.getElementById(trgt.id.replace('section_wrapper', 'control_')));
		if(document.getElementById(trgt.id.replace('section_wrapper', 'control_')).focus) {
			document.getElementById(trgt.id.replace('section_wrapper', 'control_')).focus();
		}
		removeClass(trgt, 'displaynone1');

		if(sectionsData[trgt.id.replace('section_wrapper', '')] && sectionsData[trgt.id.replace('section_wrapper', '')]['toggle_control_wrappers_ids']) {
			var toggle_control_wrappers_ids = sectionsData[trgt.id.replace('section_wrapper', '')]['toggle_control_wrappers_ids'].split(',');
			for(var i in toggle_control_wrappers_ids) {
				if(toggle_control_wrappers_ids.hasOwnProperty(i)) {
					if(document.getElementById('control_wrapper_'+toggle_control_wrappers_ids[i])) {
						removeClass(document.getElementById('control_wrapper_'+toggle_control_wrappers_ids[i]), new RegExp(/^displaynone1$/));
					}
				}
			}
		}
	} else {
		addClass(trgt, 'displaynone1');
		lock_text_control(document.getElementById(trgt.id.replace('section_wrapper', 'control_')));

		if(sectionsData[trgt.id.replace('section_wrapper', '')] && sectionsData[trgt.id.replace('section_wrapper', '')]['toggle_control_wrappers_ids']) {
			var toggle_control_wrappers_ids = sectionsData[trgt.id.replace('section_wrapper', '')]['toggle_control_wrappers_ids'].split(',');
			for(var i in toggle_control_wrappers_ids) {
				if(toggle_control_wrappers_ids.hasOwnProperty(i)) {
					if(document.getElementById('control_wrapper_'+toggle_control_wrappers_ids[i])) {
						addClass(document.getElementById('control_wrapper_'+toggle_control_wrappers_ids[i]), 'displaynone1');
					}
				}
			}
		}
	}

	process_control_input(document.getElementById(trgt.id.replace('section_wrapper', 'control_')));

	if(option) {
		if(option == 1) {
			/*
			if(src.className.match('visibility-hidden')) {
				removeClass(src, 'visibility-hidden');
			} else {
				addClass(src, 'visibility-hidden');
			}
			*/

			if(document.getElementById(trgt.id.replace('section_wrapper', 'section_toggle_on')).className.match('visibility-hidden')) {
				removeClass(document.getElementById(trgt.id.replace('section_wrapper', 'section_toggle_on')), 'visibility-hidden');
			} else {
				addClass(document.getElementById(trgt.id.replace('section_wrapper', 'section_toggle_on')), 'visibility-hidden');
			}
		}
	}

	return 1;
}



function disable_sections(sections) {
	if(!sections || (typeof(sections) != 'object')) {
		return;
	}

	for(var i in sections) {
		if(sections.hasOwnProperty(i)) {
			var section = sectionsData[sections[i]];

			if(document.getElementById('control_wrapper_'+sections[i])) {
				addClass(document.getElementById('control_wrapper_'+sections[i]), 'display-none');
				if(document.getElementById(sectionsData[sections[i]]['name'])) {
					document.getElementById(sectionsData[sections[i]]['name']).disabled = true;
				}
			}
		}
	}
}

function enable_sections(sections) {
	if(!sections || (typeof(sections) != 'object')) {
		return;
	}

	for(var i in sections) {
		if(sections.hasOwnProperty(i)) {
			var section = sectionsData[sections[i]];

			if(document.getElementById('control_wrapper_'+sections[i])) {
				removeClass(document.getElementById('control_wrapper_'+sections[i]), 'display-none');
				if(document.getElementById(sectionsData[sections[i]]['name']) && document.getElementById('section_wrapper'+sections[i]) && !document.getElementById('section_wrapper'+sections[i]).className.match('displaynone1')) {
					document.getElementById(sectionsData[sections[i]]['name']).disabled = false;
				}
			}
		}
	}
	if($('#control_15').val() == ''){ $('#control_15').val('Back Message'); $('#msg2').val('Back Message'); }
}




function toggle_subsection(src) {
	var chld = getChildElements(src.parentNode.parentNode.parentNode);
	if(!chld[1]) {
		return;
	}

	if(src.checked) {
		removeClass(chld[1], 'display-none');
	} else {
		addClass(chld[1], 'display-none');
	}
}



function process_control_input(src) {
	var trgtid = src.id.replace('control_', '');
	if(src.type && (src.type == 'radio')) {
		trgtid = trgtid.substr(0, trgtid.indexOf('_'));
	}
	var section = sectionsData[trgtid];

	valid_change(document.getElementById(section['name']), src);
	update_layers(src);
}



function update_layers(src) {
	var trgtid = src.id.replace('control_', '');
	if(src.type && (src.type == 'radio')) {
		trgtid = trgtid.substr(0, trgtid.indexOf('_'));
	}
	var section = sectionsData[trgtid];
	if(!section || !section['update_layers']) {
		return;
	}
	
	console.log(section['update_layers']);

	var layers = section['update_layers'].split(',');
	if(!layers) {
		return;
	}

	pgloading = [];
	for(var layerid in layers) {
		if(layers.hasOwnProperty(layerid)) {
			if(document.getElementById('layer'+layers[layerid])) {
				pgloading[layers[layerid]] = 1;

				var query = [];

				var keys = [];

				var ld_params_ids = [];
				if(layersData[layers[layerid]]['use_layer_default_params_ids']) {
					ldpids = layersData[layers[layerid]]['use_layer_default_params_ids'].split(',');
					for(var i in ldpids) {
						if(ldpids.hasOwnProperty(i)) {
							ld_params_ids[ldpids[i]] = 1;
						}
					}
				}

				if(layersData[layers[layerid]]['preview_params_ids']) {
					var params_ids = layersData[layers[layerid]]['preview_params_ids'].split(',');

					if(params_ids) {
						for(var prmid in params_ids) {
							if(params_ids.hasOwnProperty(prmid)) {
								var param = fieldsData[params_ids[prmid]];
								if(fieldsData[params_ids[prmid]]['control_type'] == 'r') {
									if(document.getElementsByName(fieldsData[params_ids[prmid]]['pname'])) {
										query[fieldsData[params_ids[prmid]]['pname']] = 'l[0]['+fieldsData[params_ids[prmid]]['pname']+']='+encodeURIComponent(getCheckedRadio(document.getElementsByName(fieldsData[params_ids[prmid]]['pname'])).value);
										keys[fieldsData[params_ids[prmid]]['pname']] = fieldsData[params_ids[prmid]]['pname'];
									}
								} else {
									if(document.getElementById(fieldsData[params_ids[prmid]]['pname'])) {
										if(!document.getElementById(fieldsData[params_ids[prmid]]['pname']).disabled) {
											if(ld_params_ids[params_ids[prmid]]) {
												if(document.getElementById(fieldsData[params_ids[prmid]]['pname']).value && document.getElementById(fieldsData[params_ids[prmid]]['pname']).value!='0') {
													query[fieldsData[params_ids[prmid]]['pname']] = 'l[0]['+fieldsData[params_ids[prmid]]['pname']+']='+encodeURIComponent(document.getElementById(fieldsData[params_ids[prmid]]['pname']).value);
													keys[fieldsData[params_ids[prmid]]['pname']] = fieldsData[params_ids[prmid]]['pname'];
												} else {
													query[fieldsData[params_ids[prmid]]['pname']] = 'l[0]['+fieldsData[params_ids[prmid]]['pname']+']='+encodeURIComponent(layersData[layers[layerid]][fieldsData[params_ids[prmid]]['pname']]);
													keys[fieldsData[params_ids[prmid]]['pname']] = fieldsData[params_ids[prmid]]['pname'];
												}
											} else {
												query[fieldsData[params_ids[prmid]]['pname']] = 'l[0]['+fieldsData[params_ids[prmid]]['pname']+']='+encodeURIComponent(document.getElementById(fieldsData[params_ids[prmid]]['pname']).value);
												keys[fieldsData[params_ids[prmid]]['pname']] = fieldsData[params_ids[prmid]]['pname'];
											}
										}
									}
								}
							}
						}
					}
				}

				if(layersData[layers[layerid]]['nullcheck_preview_params_ids']) {
					var nullcheck_params_ids = layersData[layers[layerid]]['nullcheck_preview_params_ids'].split('|');

					if(nullcheck_params_ids) {
						for(var ncprmid in nullcheck_params_ids) {
							if(nullcheck_params_ids.hasOwnProperty(ncprmid)) {
								var nc_params_ids = nullcheck_params_ids[ncprmid].split(':');
								nc_params_ids[1] = nc_params_ids[1].split(',');
								if(nc_params_ids[1]) {
									for(var ncpid in nc_params_ids[1]) {
										if(nc_params_ids[1].hasOwnProperty(ncpid)) {
											var param = fieldsData[params_ids[prmid]];
											if(fieldsData[nc_params_ids[1][ncpid]]['control_type'] == 'r') {
												if(document.getElementsByName(fieldsData[nc_params_ids[1][ncpid]]['pname'])) {
													var val = getCheckedRadio(document.getElementsByName(fieldsData[nc_params_ids[1][ncpid]]['pname'])).value;
													if(val && (val != '0')) {
														query[fieldsData[nc_params_ids[0]]['pname']] = 'l[0]['+fieldsData[nc_params_ids[0]]['pname']+']=1';
														keys[fieldsData[nc_params_ids[0]]['pname']] = fieldsData[nc_params_ids[0]]['pname'];
														break;
													}
												}
											} else {
												if(document.getElementById(fieldsData[nc_params_ids[1][ncpid]]['pname'])) {
													if(!document.getElementById(fieldsData[nc_params_ids[1][ncpid]]['pname']).disabled) {
														query[fieldsData[nc_params_ids[0]]['pname']] = 'l[0]['+fieldsData[nc_params_ids[0]]['pname']+']=1';
														keys[fieldsData[nc_params_ids[0]]['pname']] = fieldsData[nc_params_ids[0]]['pname'];
														break;
													}
												}
											}
										}
									}
								}
							}
						}
					}
				}

				for(var lprop in layersData[layers[layerid]]) {
					if(!keys[lprop]) {
						if(layersData[layers[layerid]].hasOwnProperty(lprop) && layersData[layers[layerid]][lprop]) {
							query[lprop] = 'l[0]['+lprop+']='+encodeURIComponent(layersData[layers[layerid]][lprop]);
						} else {
							query[lprop] = 'l[0]['+lprop+']=';
						}
					}
				}

				if(layersData[layers[layerid]]['pType'] != '0') {
					query['type'] = 'l[0][type]='+pType;
				}
				if(layersData[layers[layerid]]['pStyle'] != '0') {
					query['style'] = 'l[0][style]='+pStyle;
				}

				var _qry = [];
				for(var i in query) {
					if(query.hasOwnProperty(i)) {
						_qry[_qry.length] = query[i];
					}
				}


				query = _qry.join('&');
				document.getElementById('layer'+layers[layerid]).src = base_url+'/g-preview?'+query;
			}
		}
	}


	if(document.getElementById('loading_preview') && pgloading.length) {
		removeClass(document.getElementById('loading_preview'), 'visibility-hidden');
	}
}



function hide_loading_message(e) {
	var src = e.target;

	if(pgloading && pgloading.length) {
		var pgl = [];
		for(var i in pgloading) {
			if(pgloading.hasOwnProperty(i)) {
				if(pgloading[i] && (parseInt(src.id.replace('layer', ''), 10) != parseInt(i, 10))) {
					pgl[i] = 1;
				} else {
					pgloading[i] = 0;
				}
			}
		}

		if(!pgl.length) {
			addClass(document.getElementById('loading_preview'), 'visibility-hidden');
		}
	} else {
		addClass(document.getElementById('loading_preview'), 'visibility-hidden');
	}
}




function change_color_type_radio(src) {
	if(!src.id.match('_color_type')) {
		return;
	}

	var sid = src.id.substr(0, src.id.indexOf('_'));
	var id = sid+'_color_type';
	if(!document.getElementById(id)) {
		return;
	}

	document.getElementById(id).value=src.value;

	//query = get_product_row_fields_query(src);



	goGetSome('shortbuilder.change_color_type_radio', src.form);
}

function show_overlay_container() {
	$("div#lay").css("background-image", "url("+tpt_images_url+"/overlay1.png)");
	$("div#lay").fadeIn("slow");
	$("div#broker").fadeIn("slow");
	$("div#broker").css("height",'800px');
}

function openGUI(src) {
	$( '#tpt_lightbox' ).attr( 'style', 'width: 50%; height: 50%; left: -25%;' );
	show_lightbox();

	var srcid = src.id.replace('trgr_', '').replace('trgr2_', '');

	/* Custom Band Color Pop-Up Loader */
	if( src.id.match( 'custom_color' ) ) {
		var pname = src.id.substr(src.id.indexOf('_') + 1).substr(src.id.substr(src.id.indexOf('_') + 1).indexOf('_') + 1).substr(src.id.substr(src.id.indexOf('_') + 1).substr(src.id.substr(src.id.indexOf('_') + 1).indexOf('_') + 1).indexOf('_') + 1);
		query += '&sid='+src.id;
		query += '&pname='+pname;
		goGetSome('shortbuilder.open_GUI_color', query);
	}

	if(!sectionsData[srcid]) {
		return;
	}

	var query = [];

	if(sectionsData[srcid]['name'].match('clp')) {
	    preloadDataStr = '#preload_'+srcid;
	    console.log(preloadDataStr);
	    preloadData = $(preloadDataStr).html();
	    console.log(preloadData);
	    $('#tpt_lightbox_content').html(preloadData);
	    if($('.clipartMain').hasClass('display-none')) {
	        $('.clipartMain').removeClass('display-none');
	    }
		//query[query.length] = 'sid='+sectionsData[srcid]['id'];
		//goGetSome('shortbuilder.open_GUI_clipart', query.join('&'));
	} else if(sectionsData[srcid]['name'].match('font')) {
		var childs = getChildElements(document.getElementById('tpt_lightbox_content'));
		if(childs && childs[0]) {
			document.getElementById('tpt_lightbox_content').removeChild(childs[0]);
		}
		query[query.length] = 'sid='+sectionsData[srcid]['id'];
		query[query.length] = 'id='+document.getElementById('font').value;
		query[query.length] = 'type='+pType;
		query[query.length] = 'style='+pStyle;
		goGetSome('shortbuilder.open_GUI_font', query.join('&'));
     }

	return;

	//query = get_product_row_fields_query(src);
	query = [];

	if(src.id.match('font')) {
		var pname = src.id.substr(src.id.indexOf('_') + 1).substr(src.id.substr(src.id.indexOf('_') + 1).indexOf('_') + 1);
		query += '&sid='+src.id;
		query += '&pname='+pname;
		goGetSome('admin.open_GUI_font', query);
	} else if(src.id.match('clp')) {
		var pname = src.id.substr(src.id.indexOf('_') + 1).substr(src.id.substr(src.id.indexOf('_') + 1).indexOf('_') + 1).substr(src.id.substr(src.id.indexOf('_') + 1).substr(src.id.substr(src.id.indexOf('_') + 1).indexOf('_') + 1).indexOf('_') + 1);
		query += '&sid='+src.id;
		query += '&pname='+pname;
		goGetSome('admin.open_GUI_clipart', query);
	} else if(src.id.match('color')) {
		var pname = src.id.substr(src.id.indexOf('_') + 1).substr(src.id.substr(src.id.indexOf('_') + 1).indexOf('_') + 1).substr(src.id.substr(src.id.indexOf('_') + 1).substr(src.id.substr(src.id.indexOf('_') + 1).indexOf('_') + 1).indexOf('_') + 1);
		query += '&sid='+src.id;
		query += '&pname='+pname;
		goGetSome('shortbuilder.open_GUI_color', query);
	}

}


function select_font(src, sid, pname) {
	try{UnTip();}catch(e){}

	var val = src.id.replace('font_', '');
	//document.getElementById(pname).disabled = false;
	document.getElementById(pname).value = val;

	var sel = document.getElementById('control_'+sid);
	var opts = sel.options;
	for(var i in opts) {
		if(opts.hasOwnProperty(i)) {
			if(opts[i].value == val) {
				sel.selectedIndex = i;
				break;
			}
		}
	}

	update_layers({'id':'control_'+sid});

	hide_lightbox();
}



function select_clipart(src, sid, pname) {
	document.getElementById(pname).disabled = false;
	document.getElementById(pname).value = src.id.replace('clp_', '');

	var chld;
	var chlds = src.getElementsByTagName('span');
	for(var i in chlds) {
		if(chlds.hasOwnProperty(i)) {
			if(chlds[i] && chlds[i].className && chlds[i].className.match('clipart-label')) {
				chld = chlds[i];
			}
		}
	}
	document.getElementById('trgr2_'+sid).innerHTML = chld.innerHTML;
	document.getElementById('trgr2_'+sid).title = 'Selected Clipart: '+chld.innerHTML;

	addClass(document.getElementById('wrap_trgr_'+sid), 'display-none');
	removeClass(document.getElementById('wrap_trgr2_'+sid), 'display-none');

	update_layers({'id':'control_'+sid});

	hide_lightbox();
}

function clear_clipart(src, sid, pname) {
	document.getElementById(pname).disabled = true;
	document.getElementById(pname).value = '';


	document.getElementById('trgr2_'+sid).innerHTML = '';
	document.getElementById('trgr2_'+sid).title = '';

	removeClass(document.getElementById('wrap_trgr_'+sid), 'display-none');
	addClass(document.getElementById('wrap_trgr2_'+sid), 'display-none');

	update_layers({'id':'control_'+sid});
}


var led_flash_tmt;
var _ledflash = 0;

function led_glow() {
	if(_ledflash) {
		clearTimeout(led_flash_tmt);
		_ledflash = 0;
		if(document.getElementById('layer6')) {
			if(!document.getElementById('layer6').className.match('display-none')){
				toggle_led_glow();
			}
		} else if(document.getElementById('layer45')) {
			if(!document.getElementById('layer45').className.match('display-none')){
				toggle_led_glow();
			}
		} else if(document.getElementById('layer47')) {
			if(!document.getElementById('layer47').className.match('display-none')){
				toggle_led_glow();
			}
		}
	} else {
		toggle_led_glow();
	}
}

function toggle_led_glow() {
	if(document.getElementById('layer36')) {
		if(document.getElementById('layer36').className.match('display-none')){
			removeClass(document.getElementById('layer36'), 'display-none');
		}else{
			addClass(document.getElementById('layer36'), 'display-none');
		}
	}

	if(document.getElementById('layer38')) {
		if(document.getElementById('layer38').className.match('display-none')){
			removeClass(document.getElementById('layer38'), 'display-none');
		}else{
			addClass(document.getElementById('layer38'), 'display-none');
		}
	}

	if(document.getElementById('layer39')) {
		if(document.getElementById('layer39').className.match('display-none')){
			removeClass(document.getElementById('layer39'), 'display-none');
		}else{
			addClass(document.getElementById('layer39'), 'display-none');
		}
	}
	
	if(document.getElementById('layer5')) {
		if(document.getElementById('layer5').className.match('display-none')){
			removeClass(document.getElementById('layer5'), 'display-none');
		}else{
			addClass(document.getElementById('layer5'), 'display-none');
		}
	}

	if(document.getElementById('layer6')) {
		if(document.getElementById('layer6').className.match('display-none')){
			removeClass(document.getElementById('layer6'), 'display-none');
		}else{
			addClass(document.getElementById('layer6'), 'display-none');
		}
	}

	if(document.getElementById('layer43')) {
		if(document.getElementById('layer43').className.match('display-none')){
			removeClass(document.getElementById('layer43'), 'display-none');
		}else{
			addClass(document.getElementById('layer43'), 'display-none');
		}
	}

	if(document.getElementById('layer44')) {
		if(document.getElementById('layer44').className.match('display-none')){
			removeClass(document.getElementById('layer44'), 'display-none');
		}else{
			addClass(document.getElementById('layer44'), 'display-none');
		}
	}

	if(document.getElementById('layer45')) {
		if(document.getElementById('layer45').className.match('display-none')){
			removeClass(document.getElementById('layer45'), 'display-none');
		}else{
			addClass(document.getElementById('layer45'), 'display-none');
		}
	}

	if(document.getElementById('layer46')) {
		if(document.getElementById('layer46').className.match('display-none')){
			removeClass(document.getElementById('layer46'), 'display-none');
		}else{
			addClass(document.getElementById('layer46'), 'display-none');
		}
	}

	if(document.getElementById('layer47')) {
		if(document.getElementById('layer47').className.match('display-none')){
			removeClass(document.getElementById('layer47'), 'display-none');
		}else{
			addClass(document.getElementById('layer47'), 'display-none');
		}
	}

	if(document.getElementById('layer48')) {
		if(document.getElementById('layer48').className.match('display-none')){
			removeClass(document.getElementById('layer48'), 'display-none');
		}else{
			addClass(document.getElementById('layer48'), 'display-none');
		}
	}

	if(document.getElementById('layer90')) {
		if(document.getElementById('layer90').className.match('display-none')){
			removeClass(document.getElementById('layer90'), 'display-none');
		}else{
			addClass(document.getElementById('layer90'), 'display-none');
		}
	}

	if(document.getElementById('layer91')) {
		if(document.getElementById('layer91').className.match('display-none')){
			removeClass(document.getElementById('layer91'), 'display-none');
		}else{
			addClass(document.getElementById('layer91'), 'display-none');
		}
	}
	
	if(document.getElementById('layer3')) {
		if(document.getElementById('layer3').className.match('display-none')){
			removeClass(document.getElementById('layer3'), 'display-none');
		}else{
			addClass(document.getElementById('layer3'), 'display-none');
		}
	}
	
	if(document.getElementById('layer4')) {
		if(document.getElementById('layer4').className.match('display-none')){
			removeClass(document.getElementById('layer4'), 'display-none');
		}else{
			addClass(document.getElementById('layer4'), 'display-none');
		}
	}
}

function toggle_led_flash() {
	if(_ledflash == 1) {
		clearTimeout(led_flash_tmt);
		_ledflash = 0;
		if(document.getElementById('layer6')) {
			if(document.getElementById('layer6').className.match('display-none')){
				toggle_led_glow();
			}
		}
	} else {
		clearTimeout(led_flash_tmt);
		_ledflash = 1;
		led_flash_tmt = setTimeout(led_flash, 100);
	}
}
function led_flash() {
	toggle_led_glow();
	led_flash_tmt = setTimeout(led_flash, 100);
}

function toggle_led_flash2() {
	if(_ledflash == 2) {
		clearTimeout(led_flash_tmt);
		_ledflash = 0;
		if(document.getElementById('layer6')) {
			if(document.getElementById('layer6').className.match('display-none')){
				toggle_led_glow();
			}
		}
	} else {
		clearTimeout(led_flash_tmt);
		_ledflash = 2;
		led_flash_tmt = setTimeout(led_flash2, 150);
	}
}
function led_flash2() {
	toggle_led_glow();
	led_flash_tmt = setTimeout(led_flash2, 150);
}

function show_led_video(){
	$( '#tpt_lightbox' ).attr( 'style', 'width: 28%; height: 400px; left: -15%;' );
    show_lightbox();
    $('#tpt_lightbox_content').html('<div class="center" style="text-align:center;margin:auto;padding:20px 0px;"><iframe width="560" height="315" src="https://www.youtube.com/embed/dFNnExXU-ME" frameborder="0" allowfullscreen></iframe></div>');
}

/*
$(function() {
	$('input[type="radio"]#control_21_3,input[type="radio"]#control_21_4').change(function(){
		console.log("called");
		$('.mspanactv').removeClass('mspanactv');
		$('input[type="radio"]#control_21_3,input[type="radio"]#control_21_4').filter(':checked').closest('.display-inline').addClass('mspanactv');
	});
	$('input[type="radio"]#control_21_3,input[type="radio"]#control_21_3').filter(':checked').closest('.display-inline').addClass('mspanactv');
});
*/

function msgTitleBg(){
	$('.mspanactv').removeClass('mspanactv');
	$('input[type="radio"]#control_21_3,input[type="radio"]#control_21_4').filter(':checked').closest('.display-inline').addClass('mspanactv');
}

//]]>
</script>
<script type="text/javascript" src="$tpt_jsurl/builder_json.js"></script>

<script defer="defer" type="text/javascript" src="$tpt_jsurl/short_builder.js"></script>
EOT;

$tpt_vars['template_data']['head'][1] = '<script defer="defer" type="text/javascript" src="'.$tpt_jsurl.'/json2.js"></script>'.$tpt_vars['template_data']['head'][1];