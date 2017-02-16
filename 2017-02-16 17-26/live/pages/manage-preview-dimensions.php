<?php
defined('TPT_INIT') or die('access denied');

$db = $tpt_vars['db']['handler'];

$ajax = tpt_ajax::getCall('manage-preview-dimensions.update');

$query = <<< EOT
SELECT * FROM `tpt_module_previewlayer` WHERE `description` LIKE "%Screenprint%"
EOT;
$db->prepare($query);
$db->execute();
$rows = $db->fetchAll();
$rows = array_column($rows, null, 'id');
//tpt_dump($rows);
foreach($rows as $row) {
	$type = 0;
	$pos = 2;
	if(preg_match('#6mm Ring#', $row['description'])) {
		$type = 34;
	} else if(preg_match('#6mm#', $row['description'])) {
		$type = 1;
	} else if(preg_match('#12mm Ring#', $row['description'])) {
		$type = 8;
	} else if(preg_match('#12mm Snap#', $row['description'])) {
		$type = 6;
	} else if(preg_match('#12mm Keychain#', $row['description'])) {
		$type = 7;
	} else if(preg_match('#12mm#', $row['description'])) {
		$type = 2;
	} else if(preg_match('#19mm USB#', $row['description'])) {
		$type = 9999;
	} else if(preg_match('#19mm#', $row['description'])) {
		$type = 3;
	} else if(preg_match('#24mm Slapband#', $row['description'])) {
		$type = 5;
	} else if(preg_match('#24mm#', $row['description'])) {
		$type = 4;
	}

	if(preg_match('#Front#', $row['description'])) {
		$pos = 0;
	} else if(preg_match('#Back#', $row['description'])) {
		$pos = 1;
	}

	${'dims_'.$type.'_'.$pos.'_cX'} = $row['cX'];
	${'dims_'.$type.'_'.$pos.'_cY'} = $row['cY'];
	${'dims_'.$type.'_'.$pos.'_cPT'} = $row['cPT'];
	${'dims_'.$type.'_'.$pos.'_cPR'} = $row['cPR'];
	${'dims_'.$type.'_'.$pos.'_cPB'} = $row['cPB'];
	${'dims_'.$type.'_'.$pos.'_cPL'} = $row['cPL'];
}


$tpt_vars['template']['content'] = <<< EOT
<script type="text/javascript">
function change_band_width_control(src) {
var grps=document.getElementById('groups');
//var chldrn=getChildElements(document.getElementById('groups'));
//var gchldrn=getChildElements(chldrn[0]);
var chckbxs = grps.getElementsByTagName('input');
	for(var i in chckbxs) {
		if(chckbxs.hasOwnProperty(i)) {
			chckbxs[i].checked = false;
		}
	}
}
</script>
<form>
	<select name="band_width" onchange="/*change_band_width_control(this);*/">
		<option value="1">1/4</option>
		<option value="2">1/2</option>
		<option value="3">3/4</option>
		<option value="4">1</option>
		<option value="5">Slap</option>
	</select>
	<!--div id="groups">
		<div id="group_1">
			Band&nbsp;<input type="checkbox" name="product[1]" value="1" />
			Ring&nbsp;<input type="checkbox" name="product[2]" value="2" />
		</div>
		<div id="group_2">
			Band&nbsp;<input type="checkbox" name="product[3]" value="3" />
			Ring&nbsp;<input type="checkbox" name="product[4]" value="4" />
			Snap&nbsp;<input type="checkbox" name="product[5]" value="5" />
			Chain&nbsp;<input type="checkbox" name="product[6]" value="6" />
		</div>
		<div id="group_3">
			Band&nbsp;<input type="checkbox" name="product[7]" value="7" />
		</div>
		<div id="group_4">
			Band&nbsp;<input type="checkbox" name="product[8]" value="8" />
		</div>
		<div id="group_5">
			Band&nbsp;<input type="checkbox" name="product[9]" value="9" />
		</div>
	</div-->
	<br />
	<div>
		<div class="display-inline-block">
			<div class="height-40">
			1/4 Band - Front
			</div>
			<div class="height-40">
			1/4 Band - Back
			</div>
			<div class="height-40">
			1/4 Band - Combined
			</div>

			<div class="height-40"></div>

			<div class="height-40">
			1/4 Ring - Front
			</div>

			<div class="height-40"></div>

			<div class="height-40">
			1/2 Band - Front
			</div>
			<div class="height-40">
			1/2 Band - Back
			</div>
			<div class="height-40">
			1/2 Band - Combined
			</div>

			<div class="height-40"></div>

			<div class="height-40">
			1/2 Snap - Front
			</div>
			<div class="height-40">
			1/2 Snap - Back
			</div>
			<div class="height-40">
			1/2 Snap - Combined
			</div>

			<div class="height-40"></div>

			<div class="height-40">
			1/2 Keychain - Front
			</div>
			<div class="height-40">
			1/2 Keychain - Back
			</div>
			<div class="height-40">
			1/2 Keychain - Combined
			</div>

			<div class="height-40"></div>

			<div class="height-40">
			1/2 Ring - Front
			</div>

			<div class="height-40"></div>

			<div class="height-40">
			3/4 Band - Front
			</div>
			<div class="height-40">
			3/4 Band - Back
			</div>
			<div class="height-40">
			3/4 Band - Combined
			</div>

			<div class="height-40"></div>

			<div class="height-40">
			3/4 USB - Front
			</div>
			<div class="height-40">
			3/4 USB - Back
			</div>
			<div class="height-40">
			3/4 USB - Combined
			</div>

			<div class="height-40"></div>

			<div class="height-40">
			1 Band - Front
			</div>
			<div class="height-40">
			1 Band - Back
			</div>
			<div class="height-40">
			1 Band - Combined
			</div>

			<div class="height-40"></div>

			<div class="height-40">
			Slapband - Front
			</div>
			<div class="height-40">
			Slapband - Back
			</div>
			<div class="height-40">
			Slapband - Combined
			</div>
		</div>
		<div class="display-inline-block">
			<div class="height-40"><!-- 1/4 Band - Front -->
				<div class="display-inline-block">
				cX&nbsp;<input type="text" name="dims[1][0][cX]" value="$dims_1_0_cX" class="width-50" />
				</div>
				<div class="display-inline-block">
				cY&nbsp;<input type="text" name="dims[1][0][cY]" value="$dims_1_0_cY" class="width-50" />
				</div>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<div class="display-inline-block">
				cPT&nbsp;<input type="text" name="dims[1][0][cPT]" value="$dims_1_0_cPT" class="width-50" />
				</div>
				<div class="display-inline-block">
				cPR&nbsp;<input type="text" name="dims[1][0][cPR]" value="$dims_1_0_cPR" class="width-50" />
				</div>
				<div class="display-inline-block">
				cPB&nbsp;<input type="text" name="dims[1][0][cPB]" value="$dims_1_0_cPB" class="width-50" />
				</div>
				<div class="display-inline-block">
				cPL&nbsp;<input type="text" name="dims[1][0][cPL]" value="$dims_1_0_cPL" class="width-50" />
				</div>
			</div>
			<div class="height-40"><!-- 1/4 Band - Back -->
				<div class="display-inline-block">
				cX&nbsp;<input type="text" name="dims[1][1][cX]" value="$dims_1_1_cX" class="width-50" />
				</div>
				<div class="display-inline-block">
				cY&nbsp;<input type="text" name="dims[1][1][cY]" value="$dims_1_1_cY" class="width-50" />
				</div>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<div class="display-inline-block">
				cPT&nbsp;<input type="text" name="dims[1][1][cPT]" value="$dims_1_1_cPT" class="width-50" />
				</div>
				<div class="display-inline-block">
				cPR&nbsp;<input type="text" name="dims[1][1][cPR]" value="$dims_1_1_cPR" class="width-50" />
				</div>
				<div class="display-inline-block">
				cPB&nbsp;<input type="text" name="dims[1][1][cPB]" value="$dims_1_1_cPB" class="width-50" />
				</div>
				<div class="display-inline-block">
				cPL&nbsp;<input type="text" name="dims[1][1][cPL]" value="$dims_1_1_cPL" class="width-50" />
				</div>
			</div>
			<div class="height-40"><!-- 1/4 Band - Combined -->
				<div class="display-inline-block">
				cX&nbsp;<input type="text" name="dims[1][2][cX]" value="$dims_1_2_cX" class="width-50" />
				</div>
				<div class="display-inline-block">
				cY&nbsp;<input type="text" name="dims[1][2][cY]" value="$dims_1_2_cY" class="width-50" />
				</div>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<div class="display-inline-block">
				cPT&nbsp;<input type="text" name="dims[1][2][cPT]" value="$dims_1_2_cPT" class="width-50" />
				</div>
				<div class="display-inline-block">
				cPR&nbsp;<input type="text" name="dims[1][2][cPR]" value="$dims_1_2_cPR" class="width-50" />
				</div>
				<div class="display-inline-block">
				cPB&nbsp;<input type="text" name="dims[1][2][cPB]" value="$dims_1_2_cPB" class="width-50" />
				</div>
				<div class="display-inline-block">
				cPL&nbsp;<input type="text" name="dims[1][2][cPL]" value="$dims_1_2_cPL" class="width-50" />
				</div>
			</div>

			<div class="height-40"></div>

			<div class="height-40"><!-- 1/4 Ring - Front -->
				<div class="display-inline-block">
				cX&nbsp;<input type="text" name="dims[34][0][cX]" value="$dims_34_0_cX" class="width-50" />
				</div>
				<div class="display-inline-block">
				cY&nbsp;<input type="text" name="dims[34][0][cY]" value="$dims_34_0_cY" class="width-50" />
				</div>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<div class="display-inline-block">
				cPT&nbsp;<input type="text" name="dims[34][0][cPT]" value="$dims_34_0_cPT" class="width-50" />
				</div>
				<div class="display-inline-block">
				cPR&nbsp;<input type="text" name="dims[34][0][cPR]" value="$dims_34_0_cPR" class="width-50" />
				</div>
				<div class="display-inline-block">
				cPB&nbsp;<input type="text" name="dims[34][0][cPB]" value="$dims_34_0_cPB" class="width-50" />
				</div>
				<div class="display-inline-block">
				cPL&nbsp;<input type="text" name="dims[34][0][cPL]" value="$dims_34_0_cPL" class="width-50" />
				</div>
			</div>

			<div class="height-40"></div>

			<div class="height-40"><!-- 1/2 Band - Front -->
				<div class="display-inline-block">
				cX&nbsp;<input type="text" name="dims[2][0][cX]" value="$dims_2_0_cX" class="width-50" />
				</div>
				<div class="display-inline-block">
				cY&nbsp;<input type="text" name="dims[2][0][cY]" value="$dims_2_0_cY" class="width-50" />
				</div>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<div class="display-inline-block">
				cPT&nbsp;<input type="text" name="dims[2][0][cPT]" value="$dims_2_0_cPT" class="width-50" />
				</div>
				<div class="display-inline-block">
				cPR&nbsp;<input type="text" name="dims[2][0][cPR]" value="$dims_2_0_cPR" class="width-50" />
				</div>
				<div class="display-inline-block">
				cPB&nbsp;<input type="text" name="dims[2][0][cPB]" value="$dims_2_0_cPB" class="width-50" />
				</div>
				<div class="display-inline-block">
				cPL&nbsp;<input type="text" name="dims[2][0][cPL]" value="$dims_2_0_cPL" class="width-50" />
				</div>
			</div>
			<div class="height-40"><!-- 1/2 Band - Back -->
				<div class="display-inline-block">
				cX&nbsp;<input type="text" name="dims[2][1][cX]" value="$dims_2_1_cX" class="width-50" />
				</div>
				<div class="display-inline-block">
				cY&nbsp;<input type="text" name="dims[2][1][cY]" value="$dims_2_1_cY" class="width-50" />
				</div>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<div class="display-inline-block">
				cPT&nbsp;<input type="text" name="dims[2][1][cPT]" value="$dims_2_1_cPT" class="width-50" />
				</div>
				<div class="display-inline-block">
				cPR&nbsp;<input type="text" name="dims[2][1][cPR]" value="$dims_2_1_cPR" class="width-50" />
				</div>
				<div class="display-inline-block">
				cPB&nbsp;<input type="text" name="dims[2][1][cPB]" value="$dims_2_1_cPB" class="width-50" />
				</div>
				<div class="display-inline-block">
				cPL&nbsp;<input type="text" name="dims[2][1][cPL]" value="$dims_2_1_cPL" class="width-50" />
				</div>
			</div>
			<div class="height-40"><!-- 1/2 Band - Combined -->
				<div class="display-inline-block">
				cX&nbsp;<input type="text" name="dims[2][2][cX]" value="$dims_2_2_cX" class="width-50" />
				</div>
				<div class="display-inline-block">
				cY&nbsp;<input type="text" name="dims[2][2][cY]" value="$dims_2_2_cY" class="width-50" />
				</div>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<div class="display-inline-block">
				cPT&nbsp;<input type="text" name="dims[2][2][cPT]" value="$dims_2_2_cPT" class="width-50" />
				</div>
				<div class="display-inline-block">
				cPR&nbsp;<input type="text" name="dims[2][2][cPR]" value="$dims_2_2_cPR" class="width-50" />
				</div>
				<div class="display-inline-block">
				cPB&nbsp;<input type="text" name="dims[2][2][cPB]" value="$dims_2_2_cPB" class="width-50" />
				</div>
				<div class="display-inline-block">
				cPL&nbsp;<input type="text" name="dims[2][2][cPL]" value="$dims_2_2_cPL" class="width-50" />
				</div>
			</div>

			<div class="height-40"></div>

			<div class="height-40"><!-- 1/2 Snap - Front -->
				<div class="display-inline-block">
				cX&nbsp;<input type="text" name="dims[6][0][cX]" value="$dims_6_0_cX" class="width-50" />
				</div>
				<div class="display-inline-block">
				cY&nbsp;<input type="text" name="dims[6][0][cY]" value="$dims_6_0_cY" class="width-50" />
				</div>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<div class="display-inline-block">
				cPT&nbsp;<input type="text" name="dims[6][0][cPT]" value="$dims_6_0_cPT" class="width-50" />
				</div>
				<div class="display-inline-block">
				cPR&nbsp;<input type="text" name="dims[6][0][cPR]" value="$dims_6_0_cPR" class="width-50" />
				</div>
				<div class="display-inline-block">
				cPB&nbsp;<input type="text" name="dims[6][0][cPB]" value="$dims_6_0_cPB" class="width-50" />
				</div>
				<div class="display-inline-block">
				cPL&nbsp;<input type="text" name="dims[6][0][cPL]" value="$dims_6_0_cPL" class="width-50" />
				</div>
			</div>
			<div class="height-40"><!-- 1/2 Snap - Back -->
				<div class="display-inline-block">
				cX&nbsp;<input type="text" name="dims[6][1][cX]" value="$dims_6_1_cX" class="width-50" />
				</div>
				<div class="display-inline-block">
				cY&nbsp;<input type="text" name="dims[6][1][cY]" value="$dims_6_1_cY" class="width-50" />
				</div>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<div class="display-inline-block">
				cPT&nbsp;<input type="text" name="dims[6][1][cPT]" value="$dims_6_1_cPT" class="width-50" />
				</div>
				<div class="display-inline-block">
				cPR&nbsp;<input type="text" name="dims[6][1][cPR]" value="$dims_6_1_cPR" class="width-50" />
				</div>
				<div class="display-inline-block">
				cPB&nbsp;<input type="text" name="dims[6][1][cPB]" value="$dims_6_1_cPB" class="width-50" />
				</div>
				<div class="display-inline-block">
				cPL&nbsp;<input type="text" name="dims[6][1][cPL]" value="$dims_6_1_cPL" class="width-50" />
				</div>
			</div>
			<div class="height-40"><!-- 1/2 Snap - Combined -->
				<div class="display-inline-block">
				cX&nbsp;<input type="text" name="dims[6][2][cX]" value="$dims_6_2_cX" class="width-50" />
				</div>
				<div class="display-inline-block">
				cY&nbsp;<input type="text" name="dims[6][2][cY]" value="$dims_6_2_cY" class="width-50" />
				</div>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<div class="display-inline-block">
				cPT&nbsp;<input type="text" name="dims[6][2][cPT]" value="$dims_6_2_cPT" class="width-50" />
				</div>
				<div class="display-inline-block">
				cPR&nbsp;<input type="text" name="dims[6][2][cPR]" value="$dims_6_2_cPR" class="width-50" />
				</div>
				<div class="display-inline-block">
				cPB&nbsp;<input type="text" name="dims[6][2][cPB]" value="$dims_6_2_cPB" class="width-50" />
				</div>
				<div class="display-inline-block">
				cPL&nbsp;<input type="text" name="dims[6][2][cPL]" value="$dims_6_2_cPL" class="width-50" />
				</div>
			</div>

			<div class="height-40"></div>

			<div class="height-40"><!-- 1/2 Keychain - Front -->
				<div class="display-inline-block">
				cX&nbsp;<input type="text" name="dims[7][0][cX]" value="$dims_7_0_cX" class="width-50" />
				</div>
				<div class="display-inline-block">
				cY&nbsp;<input type="text" name="dims[7][0][cY]" value="$dims_7_0_cY" class="width-50" />
				</div>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<div class="display-inline-block">
				cPT&nbsp;<input type="text" name="dims[7][0][cPT]" value="$dims_7_0_cPT" class="width-50" />
				</div>
				<div class="display-inline-block">
				cPR&nbsp;<input type="text" name="dims[7][0][cPR]" value="$dims_7_0_cPR" class="width-50" />
				</div>
				<div class="display-inline-block">
				cPB&nbsp;<input type="text" name="dims[7][0][cPB]" value="$dims_7_0_cPB" class="width-50" />
				</div>
				<div class="display-inline-block">
				cPL&nbsp;<input type="text" name="dims[7][0][cPL]" value="$dims_7_0_cPL" class="width-50" />
				</div>
			</div>
			<div class="height-40"><!-- 1/2 Keychain - Back -->
				<div class="display-inline-block">
				cX&nbsp;<input type="text" name="dims[7][1][cX]" value="$dims_7_1_cX" class="width-50" />
				</div>
				<div class="display-inline-block">
				cY&nbsp;<input type="text" name="dims[7][1][cY]" value="$dims_7_1_cY" class="width-50" />
				</div>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<div class="display-inline-block">
				cPT&nbsp;<input type="text" name="dims[7][1][cPT]" value="$dims_7_1_cPT" class="width-50" />
				</div>
				<div class="display-inline-block">
				cPR&nbsp;<input type="text" name="dims[7][1][cPR]" value="$dims_7_1_cPR" class="width-50" />
				</div>
				<div class="display-inline-block">
				cPB&nbsp;<input type="text" name="dims[7][1][cPB]" value="$dims_7_1_cPB" class="width-50" />
				</div>
				<div class="display-inline-block">
				cPL&nbsp;<input type="text" name="dims[7][1][cPL]" value="$dims_7_1_cPL" class="width-50" />
				</div>
			</div>
			<div class="height-40"><!-- 1/2 Keychain - Combined -->
				<div class="display-inline-block">
				cX&nbsp;<input type="text" name="dims[7][2][cX]" value="$dims_7_2_cX" class="width-50" />
				</div>
				<div class="display-inline-block">
				cY&nbsp;<input type="text" name="dims[7][2][cY]" value="$dims_7_2_cY" class="width-50" />
				</div>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<div class="display-inline-block">
				cPT&nbsp;<input type="text" name="dims[7][2][cPT]" value="$dims_7_2_cPT" class="width-50" />
				</div>
				<div class="display-inline-block">
				cPR&nbsp;<input type="text" name="dims[7][2][cPR]" value="$dims_7_2_cPR" class="width-50" />
				</div>
				<div class="display-inline-block">
				cPB&nbsp;<input type="text" name="dims[7][2][cPB]" value="$dims_7_2_cPB" class="width-50" />
				</div>
				<div class="display-inline-block">
				cPL&nbsp;<input type="text" name="dims[7][2][cPL]" value="$dims_7_2_cPL" class="width-50" />
				</div>
			</div>

			<div class="height-40"></div>

			<div class="height-40"><!-- 1/2 Ring - Front -->
				<div class="display-inline-block">
				cX&nbsp;<input type="text" name="dims[8][0][cX]" value="$dims_8_0_cX" class="width-50" />
				</div>
				<div class="display-inline-block">
				cY&nbsp;<input type="text" name="dims[8][0][cY]" value="$dims_8_0_cY" class="width-50" />
				</div>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<div class="display-inline-block">
				cPT&nbsp;<input type="text" name="dims[8][0][cPT]" value="$dims_8_0_cPT" class="width-50" />
				</div>
				<div class="display-inline-block">
				cPR&nbsp;<input type="text" name="dims[8][0][cPR]" value="$dims_8_0_cPR" class="width-50" />
				</div>
				<div class="display-inline-block">
				cPB&nbsp;<input type="text" name="dims[8][0][cPB]" value="$dims_8_0_cPB" class="width-50" />
				</div>
				<div class="display-inline-block">
				cPL&nbsp;<input type="text" name="dims[8][0][cPL]" value="$dims_8_0_cPL" class="width-50" />
				</div>
			</div>

			<div class="height-40"></div>

			<div class="height-40"><!-- 3/4 Band - Front -->
				<div class="display-inline-block">
				cX&nbsp;<input type="text" name="dims[3][0][cX]" value="$dims_3_0_cX" class="width-50" />
				</div>
				<div class="display-inline-block">
				cY&nbsp;<input type="text" name="dims[3][0][cY]" value="$dims_3_0_cY" class="width-50" />
				</div>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<div class="display-inline-block">
				cPT&nbsp;<input type="text" name="dims[3][0][cPT]" value="$dims_3_0_cPT" class="width-50" />
				</div>
				<div class="display-inline-block">
				cPR&nbsp;<input type="text" name="dims[3][0][cPR]" value="$dims_3_0_cPR" class="width-50" />
				</div>
				<div class="display-inline-block">
				cPB&nbsp;<input type="text" name="dims[3][0][cPB]" value="$dims_3_0_cPB" class="width-50" />
				</div>
				<div class="display-inline-block">
				cPL&nbsp;<input type="text" name="dims[3][0][cPL]" value="$dims_3_0_cPL" class="width-50" />
				</div>
			</div>
			<div class="height-40"><!-- 3/4 Band - Back -->
				<div class="display-inline-block">
				cX&nbsp;<input type="text" name="dims[3][1][cX]" value="$dims_3_1_cX" class="width-50" />
				</div>
				<div class="display-inline-block">
				cY&nbsp;<input type="text" name="dims[3][1][cY]" value="$dims_3_1_cY" class="width-50" />
				</div>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<div class="display-inline-block">
				cPT&nbsp;<input type="text" name="dims[3][1][cPT]" value="$dims_3_1_cPT" class="width-50" />
				</div>
				<div class="display-inline-block">
				cPR&nbsp;<input type="text" name="dims[3][1][cPR]" value="$dims_3_1_cPR" class="width-50" />
				</div>
				<div class="display-inline-block">
				cPB&nbsp;<input type="text" name="dims[3][1][cPB]" value="$dims_3_1_cPB" class="width-50" />
				</div>
				<div class="display-inline-block">
				cPL&nbsp;<input type="text" name="dims[3][1][cPL]" value="$dims_3_1_cPL" class="width-50" />
				</div>
			</div>
			<div class="height-40"><!-- 3/4 Band - Combined -->
				<div class="display-inline-block">
				cX&nbsp;<input type="text" name="dims[3][2][cX]" value="$dims_3_2_cX" class="width-50" />
				</div>
				<div class="display-inline-block">
				cY&nbsp;<input type="text" name="dims[3][2][cY]" value="$dims_3_2_cY" class="width-50" />
				</div>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<div class="display-inline-block">
				cPT&nbsp;<input type="text" name="dims[3][2][cPT]" value="$dims_3_2_cPT" class="width-50" />
				</div>
				<div class="display-inline-block">
				cPR&nbsp;<input type="text" name="dims[3][2][cPR]" value="$dims_3_2_cPR" class="width-50" />
				</div>
				<div class="display-inline-block">
				cPB&nbsp;<input type="text" name="dims[3][2][cPB]" value="$dims_3_2_cPB" class="width-50" />
				</div>
				<div class="display-inline-block">
				cPL&nbsp;<input type="text" name="dims[3][2][cPL]" value="$dims_3_2_cPL" class="width-50" />
				</div>
			</div>

			<div class="height-40"></div>

			<div class="height-40"><!-- 3/4 USB - Front -->
				<div class="display-inline-block">
				cX&nbsp;<input type="text" name="dims[9999][0][cX]" value="$dims_9999_0_cX" class="width-50" />
				</div>
				<div class="display-inline-block">
				cY&nbsp;<input type="text" name="dims[9999][0][cY]" value="$dims_9999_0_cY" class="width-50" />
				</div>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<div class="display-inline-block">
				cPT&nbsp;<input type="text" name="dims[9999][0][cPT]" value="$dims_9999_0_cPT" class="width-50" />
				</div>
				<div class="display-inline-block">
				cPR&nbsp;<input type="text" name="dims[9999][0][cPR]" value="$dims_9999_0_cPR" class="width-50" />
				</div>
				<div class="display-inline-block">
				cPB&nbsp;<input type="text" name="dims[9999][0][cPB]" value="$dims_9999_0_cPB" class="width-50" />
				</div>
				<div class="display-inline-block">
				cPL&nbsp;<input type="text" name="dims[9999][0][cPL]" value="$dims_9999_0_cPL" class="width-50" />
				</div>
			</div>
			<div class="height-40"><!-- 3/4 USB - Back -->
				<div class="display-inline-block">
				cX&nbsp;<input type="text" name="dims[9999][1][cX]" value="$dims_9999_1_cX" class="width-50" />
				</div>
				<div class="display-inline-block">
				cY&nbsp;<input type="text" name="dims[9999][1][cY]" value="$dims_9999_1_cY" class="width-50" />
				</div>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<div class="display-inline-block">
				cPT&nbsp;<input type="text" name="dims[9999][1][cPT]" value="$dims_9999_1_cPT" class="width-50" />
				</div>
				<div class="display-inline-block">
				cPR&nbsp;<input type="text" name="dims[9999][1][cPR]" value="$dims_9999_1_cPR" class="width-50" />
				</div>
				<div class="display-inline-block">
				cPB&nbsp;<input type="text" name="dims[9999][1][cPB]" value="$dims_9999_1_cPB" class="width-50" />
				</div>
				<div class="display-inline-block">
				cPL&nbsp;<input type="text" name="dims[9999][1][cPL]" value="$dims_9999_1_cPL" class="width-50" />
				</div>
			</div>
			<div class="height-40"><!-- 3/4 USB - Combined -->
				<div class="display-inline-block">
				cX&nbsp;<input type="text" name="dims[9999][2][cX]" value="$dims_9999_2_cX" class="width-50" />
				</div>
				<div class="display-inline-block">
				cY&nbsp;<input type="text" name="dims[9999][2][cY]" value="$dims_9999_2_cY" class="width-50" />
				</div>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<div class="display-inline-block">
				cPT&nbsp;<input type="text" name="dims[9999][2][cPT]" value="$dims_9999_2_cPT" class="width-50" />
				</div>
				<div class="display-inline-block">
				cPR&nbsp;<input type="text" name="dims[9999][2][cPR]" value="$dims_9999_2_cPR" class="width-50" />
				</div>
				<div class="display-inline-block">
				cPB&nbsp;<input type="text" name="dims[9999][2][cPB]" value="$dims_9999_2_cPB" class="width-50" />
				</div>
				<div class="display-inline-block">
				cPL&nbsp;<input type="text" name="dims[9999][2][cPL]" value="$dims_9999_2_cPL" class="width-50" />
				</div>
			</div>

			<div class="height-40"></div>

			<div class="height-40"><!-- 1 Band - Front -->
				<div class="display-inline-block">
				cX&nbsp;<input type="text" name="dims[4][0][cX]" value="$dims_4_0_cX" class="width-50" />
				</div>
				<div class="display-inline-block">
				cY&nbsp;<input type="text" name="dims[4][0][cY]" value="$dims_4_0_cY" class="width-50" />
				</div>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<div class="display-inline-block">
				cPT&nbsp;<input type="text" name="dims[4][0][cPT]" value="$dims_4_0_cPT" class="width-50" />
				</div>
				<div class="display-inline-block">
				cPR&nbsp;<input type="text" name="dims[4][0][cPR]" value="$dims_4_0_cPR" class="width-50" />
				</div>
				<div class="display-inline-block">
				cPB&nbsp;<input type="text" name="dims[4][0][cPB]" value="$dims_4_0_cPB" class="width-50" />
				</div>
				<div class="display-inline-block">
				cPL&nbsp;<input type="text" name="dims[4][0][cPL]" value="$dims_4_0_cPL" class="width-50" />
				</div>
			</div>
			<div class="height-40"><!-- 1 Band - Back -->
				<div class="display-inline-block">
				cX&nbsp;<input type="text" name="dims[4][1][cX]" value="$dims_4_1_cX" class="width-50" />
				</div>
				<div class="display-inline-block">
				cY&nbsp;<input type="text" name="dims[4][1][cY]" value="$dims_4_1_cY" class="width-50" />
				</div>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<div class="display-inline-block">
				cPT&nbsp;<input type="text" name="dims[4][1][cPT]" value="$dims_4_1_cPT" class="width-50" />
				</div>
				<div class="display-inline-block">
				cPR&nbsp;<input type="text" name="dims[4][1][cPR]" value="$dims_4_1_cPR" class="width-50" />
				</div>
				<div class="display-inline-block">
				cPB&nbsp;<input type="text" name="dims[4][1][cPB]" value="$dims_4_1_cPB" class="width-50" />
				</div>
				<div class="display-inline-block">
				cPL&nbsp;<input type="text" name="dims[4][1][cPL]" value="$dims_4_1_cPL" class="width-50" />
				</div>
			</div>
			<div class="height-40"><!-- 1 Band - Combined -->
				<div class="display-inline-block">
				cX&nbsp;<input type="text" name="dims[4][2][cX]" value="$dims_4_2_cX" class="width-50" />
				</div>
				<div class="display-inline-block">
				cY&nbsp;<input type="text" name="dims[4][2][cY]" value="$dims_4_2_cY" class="width-50" />
				</div>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<div class="display-inline-block">
				cPT&nbsp;<input type="text" name="dims[4][2][cPT]" value="$dims_4_2_cPT" class="width-50" />
				</div>
				<div class="display-inline-block">
				cPR&nbsp;<input type="text" name="dims[4][2][cPR]" value="$dims_4_2_cPR" class="width-50" />
				</div>
				<div class="display-inline-block">
				cPB&nbsp;<input type="text" name="dims[4][2][cPB]" value="$dims_4_2_cPB" class="width-50" />
				</div>
				<div class="display-inline-block">
				cPL&nbsp;<input type="text" name="dims[4][2][cPL]" value="$dims_4_2_cPL" class="width-50" />
				</div>
			</div>

			<div class="height-40"></div>

			<div class="height-40"><!-- 1 Slapband - Front -->
				<div class="display-inline-block">
				cX&nbsp;<input type="text" name="dims[5][0][cX]" value="$dims_5_0_cX" class="width-50" />
				</div>
				<div class="display-inline-block">
				cY&nbsp;<input type="text" name="dims[5][0][cY]" value="$dims_5_0_cY" class="width-50" />
				</div>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<div class="display-inline-block">
				cPT&nbsp;<input type="text" name="dims[5][0][cPT]" value="$dims_5_0_cPT" class="width-50" />
				</div>
				<div class="display-inline-block">
				cPR&nbsp;<input type="text" name="dims[5][0][cPR]" value="$dims_5_0_cPR" class="width-50" />
				</div>
				<div class="display-inline-block">
				cPB&nbsp;<input type="text" name="dims[5][0][cPB]" value="$dims_5_0_cPB" class="width-50" />
				</div>
				<div class="display-inline-block">
				cPL&nbsp;<input type="text" name="dims[5][0][cPL]" value="$dims_5_0_cPL" class="width-50" />
				</div>
			</div>
			<div class="height-40"><!-- 1 Slapband - Back -->
				<div class="display-inline-block">
				cX&nbsp;<input type="text" name="dims[5][1][cX]" value="$dims_5_1_cX" class="width-50" />
				</div>
				<div class="display-inline-block">
				cY&nbsp;<input type="text" name="dims[5][1][cY]" value="$dims_5_1_cY" class="width-50" />
				</div>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<div class="display-inline-block">
				cPT&nbsp;<input type="text" name="dims[5][1][cPT]" value="$dims_5_1_cPT" class="width-50" />
				</div>
				<div class="display-inline-block">
				cPR&nbsp;<input type="text" name="dims[5][1][cPR]" value="$dims_5_1_cPR" class="width-50" />
				</div>
				<div class="display-inline-block">
				cPB&nbsp;<input type="text" name="dims[5][1][cPB]" value="$dims_5_1_cPB" class="width-50" />
				</div>
				<div class="display-inline-block">
				cPL&nbsp;<input type="text" name="dims[5][1][cPL]" value="$dims_5_1_cPL" class="width-50" />
				</div>
			</div>
			<div class="height-40"><!-- 1 Slapband - Combined -->
				<div class="display-inline-block">
				cX&nbsp;<input type="text" name="dims[5][2][cX]" value="$dims_5_2_cX" class="width-50" />
				</div>
				<div class="display-inline-block">
				cY&nbsp;<input type="text" name="dims[5][2][cY]" value="$dims_5_2_cY" class="width-50" />
				</div>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<div class="display-inline-block">
				cPT&nbsp;<input type="text" name="dims[5][2][cPT]" value="$dims_5_2_cPT" class="width-50" />
				</div>
				<div class="display-inline-block">
				cPR&nbsp;<input type="text" name="dims[5][2][cPR]" value="$dims_5_2_cPR" class="width-50" />
				</div>
				<div class="display-inline-block">
				cPB&nbsp;<input type="text" name="dims[5][2][cPB]" value="$dims_5_2_cPB" class="width-50" />
				</div>
				<div class="display-inline-block">
				cPL&nbsp;<input type="text" name="dims[5][2][cPL]" value="$dims_5_2_cPL" class="width-50" />
				</div>
			</div>
		</div>
	</div>
	<input type="button" onclick="$ajax" value="Update" />
</form>
EOT;
