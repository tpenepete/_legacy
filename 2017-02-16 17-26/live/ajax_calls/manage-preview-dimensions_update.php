<?php
defined('TPT_INIT') or die('access denied');

$input = $_POST;

$dbo = $tpt_vars['db']['handler'];

$maindb = $tpt_vars['config']['db']['database'];

$table0 = 'tpt_module_previewlayer';
$table1 = 'tpt_module_previewlayer_source';



$ids = array(
	1=>array(
		0=>array(7,10,13,16,19,22,25),
		1=>array(8,11,14,17,20,23,26),
		2=>array(9,12,15,18,21,24,27)
	),
	2=>array(
		0=>array(32,35,38,41,44,47,50),
		1=>array(33,36,39,42,45,48,51),
		2=>array(34,37,40,43,46,49,52)
	),
	3=>array(
		0=>array(57,60,63,66,69,72,75),
		1=>array(58,61,64,67,70,73,76),
		2=>array(59,62,65,68,71,74,77)
	),
	4=>array(
		0=>array(82,85,88,91,94,97,100),
		1=>array(83,86,89,92,95,98,101),
		2=>array(84,87,90,93,96,99,102),
	),
	5=>array(
		0=>array(107,110,113,116,119,122,125),
		1=>array(108,111,114,117,120,123,126),
		2=>array(109,112,115,118,121,124,127)
	),
	6=>array(
		0=>array(132,135,138,141,144,147,150),
		1=>array(133,136,139,142,145,148,151),
		2=>array(134,137,140,143,146,149,152)
	),
	7=>array(
		0=>array(157,160,163,166,169,172,175),
		1=>array(158,161,164,167,170,173,176),
		2=>array(159,162,165,168,171,174,177)
	),
	8=>array(
		0=>array(191,192,193,194,195,196,197)
	),
	34=>array(
		0=>array(182,183,184,185,186,187,188)
	),
	9999=>array(
		0=>array(200,203,206,209,212,215,218),
		1=>array(201,204,207,210,213,216,219),
		2=>array(202,205,208,211,214,217,220)
	)
);

/*
foreach($input['dims'] as $key=>$dim) {
	tpt_dump($key);
	tpt_dump($dim);
}
die();
*/

$i=0;
foreach($input['dims'] as $type=>$dimpos) {
	foreach($dimpos as $pos=>$dim) {
		foreach ($ids[$type][$pos] as $id) {
			//tpt_dump($type.' '.$pos);

			$query = <<< EOT
UPDATE
	`{$maindb}`.`{$table0}`
SET
	`{$maindb}`.`{$table0}`.`cX`=:cX,
	`{$maindb}`.`{$table0}`.`cY`=:cY,
	`{$maindb}`.`{$table0}`.`cPT`=:cPT,
	`{$maindb}`.`{$table0}`.`cPR`=:cPR,
	`{$maindb}`.`{$table0}`.`cPB`=:cPB,
	`{$maindb}`.`{$table0}`.`cPL`=:cPL
WHERE
	`{$maindb}`.`{$table0}`.`id`=:id
EOT;


			if (!$db->prepare($query)) {
			}
			/*
			$d = array();
			foreach($dim as $key=>$val) {
				$d[$key] = (int)$val;
			}
			*/
			$db->bindParam(":cX", 700, PDO::PARAM_INT);
			//$db->bindParam(":cX", (int)$dim['cX'], PDO::PARAM_INT);
			$db->bindParam(":cY", (int)$dim['cY'], PDO::PARAM_INT);
			$db->bindParam(":cPT", (int)$dim['cPT'], PDO::PARAM_INT);
			$db->bindParam(":cPR", (int)$dim['cPR'], PDO::PARAM_INT);
			$db->bindParam(":cPB", (int)$dim['cPB'], PDO::PARAM_INT);
			$db->bindParam(":cPL", (int)$dim['cPL'], PDO::PARAM_INT);
			$db->bindParam(":id", (int)$id);
			if (!$db->execute()) {
			}


			//tpt_dump($query);

			$query = <<< EOT
UPDATE
	`{$maindb}`.`{$table1}`
SET
	`{$maindb}`.`{$table1}`.`cX`=:cX,
	`{$maindb}`.`{$table1}`.`cY`=:cY,
	`{$maindb}`.`{$table1}`.`cPT`=:cPT,
	`{$maindb}`.`{$table1}`.`cPR`=:cPR,
	`{$maindb}`.`{$table1}`.`cPB`=:cPB,
	`{$maindb}`.`{$table1}`.`cPL`=:cPL
WHERE
	`{$maindb}`.`{$table1}`.`id`=:id
EOT;


			if (!$db->prepare($query)) {
			}
			//tpt_dump((int)$dim['cX']);
			$db->bindParam(":cX", 700, PDO::PARAM_INT);
			//$db->bindParam(":cX", (int)$dim['cX'], PDO::PARAM_INT);
			$db->bindParam(":cY", (int)$dim['cY'], PDO::PARAM_INT);
			$db->bindParam(":cPT", (int)$dim['cPT'], PDO::PARAM_INT);
			$db->bindParam(":cPR", (int)$dim['cPR'], PDO::PARAM_INT);
			$db->bindParam(":cPB", (int)$dim['cPB'], PDO::PARAM_INT);
			$db->bindParam(":cPL", (int)$dim['cPL'], PDO::PARAM_INT);
			$db->bindParam(":id", (int)$id);
			if (!$db->execute()) {
			}


			//tpt_dump($pos);
			//tpt_dump($query);
			//$i++;
		}
	}
}

//tpt_dump($i);