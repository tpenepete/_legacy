<?php

defined('TPT_INIT') or die('access denied');

class tpt_gclass_ConvertCustomArt extends tpt_PreviewGenerator {


	function __construct() {}

	function generate(&$vars, $options, &$steps) {
		$IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
		$IMCommand .= ' '.$options['image'].' ';
		$IMCommand .= '	-adaptive-resize '.$options['X'].'x'.$options['Y'];
		$IMCommand .= '	png:-';
		$this->exec($vars, $steps, 'Converted', $IMCommand, 2);
		//var_dump($steps);die();
		//tpt_dump($IMCommand, true);
		//header('Content-type: image/png');
		//return $steps['RightClip'];

		return $steps['Converted'];
	}

}