<?php

defined('TPT_INIT') or die('access denied');

class tpt_gclass_Swirl extends tpt_PreviewGenerator {
    
    
    function __construct() {}
    
    function generate(&$vars, $options, &$steps) {
        
	//var_dump($options);die();
	
	/*
        $format = 'jpg:-';
        switch($options['format']) {
            case 'png' :
                $format = 'png:-';
                break;
            default:
                $format = 'jpg:-';
                break;
        }
	*/
        
        //$path = 'export PATH="/usr/local/jdk/bin:/usr/lib64/qt-3.3/bin:/usr/lib/courier-imap/sbin:/usr/lib/courier-imap/bin:/usr/local/sbin:/usr/local/bin:/sbin:/bin:/usr/sbin:/usr/bin:/usr/local/bin:/usr/X11R6/bin:/root/bin:/usr/lib64"; ';
        
        //$IMCommand = 'export PATH="/usr/local/jdk/bin:/usr/lib64/qt-3.3/bin:/usr/lib/courier-imap/sbin:/usr/lib/courier-imap/bin:/usr/local/sbin:/usr/local/bin:/sbin:/bin:/usr/sbin:/usr/bin:/usr/local/bin:/usr/X11R6/bin:/root/bin";';
        //$IMCommand = 'export PATH="/usr/local/bin"; echo $PATH';
        //$this->exec($vars, $steps, 'path', $IMCommand, 2);
        
	if($options['swirlColor'] !== false) {
	    if(count($options['swirlColor']) == 1) {
		$IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
		$IMCommand .= '	-resize '.$options['fullsizeX'].'x'.$options['fullsizeY'].'! ';
		$IMCommand .= '	'.TPT_CACHE_DIR.DIRECTORY_SEPARATOR.'swirl'.DIRECTORY_SEPARATOR.'swirl'.key($options['swirlColor']).'.png ';
		$IMCommand .= '	png:- ';
		$this->exec($vars, $steps, 'SwirlMask', $IMCommand, 2);
		//return $steps['SwirlMask'];
		
		$IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
		$IMCommand .= '	-size '.$options['fullsizeX'].'x'.$options['fullsizeY'].' ';
		$IMCommand .= '	xc:\''.reset($options['swirlColor']).'\' ';
		$IMCommand .= '	png:- ';
		
		//$IMCommand .= '	-alpha Off ';
		$IMCommand .= '	-compose CopyOpacity ';
		$IMCommand .= '	-composite ';
		$IMCommand .= '	png:- ';
		$this->exec($vars, $steps, 'Swirl', $IMCommand, 3, $steps['SwirlMask']);
		//var_dump($steps['errors']['Swirl']);die();
		//var_dump($options['swirlColor']);die();
		//header('Content-type: image/png');
		//return $steps['Swirl'];
	    } else {
		$i=0;//die();
		foreach($options['swirlColor'] as $key=>$swirl) {
		    
		    if($i == 0) {
			$IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
			$IMCommand .= '	-size '.$options['fullsizeX'].'x'.$options['fullsizeY'].' ';
			$IMCommand .= '	xc:\''.$swirl.'\' ';
			$IMCommand .= '	png:- ';
			$this->exec($vars, $steps, 'Swirl'.$key, $IMCommand, 2);
		    } else {
			$IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
			$IMCommand .= '	-resize '.$options['fullsizeX'].'x'.$options['fullsizeY'].'! ';
			$IMCommand .= '	'.TPT_CACHE_DIR.DIRECTORY_SEPARATOR.'swirl'.DIRECTORY_SEPARATOR.'swirl'.$key.'.png ';
			$IMCommand .= '	png:- ';
			$this->exec($vars, $steps, 'SwirlMask'.$key, $IMCommand, 2);
			//return $steps['SwirlMask'];
			
			$IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
			$IMCommand .= '	-size '.$options['fullsizeX'].'x'.$options['fullsizeY'].' ';
			$IMCommand .= '	xc:\''.$swirl.'\' ';
			$IMCommand .= '	png:- ';
			
			//$IMCommand .= '	-alpha Off ';
			$IMCommand .= '	-compose CopyOpacity ';
			$IMCommand .= '	-composite ';
			$IMCommand .= '	png:- ';
			$this->exec($vars, $steps, 'Swirl'.$key, $IMCommand, 3, $steps['SwirlMask'.$key]);
			//var_dump($steps['errors']['Swirl']);die();
			//var_dump($options['swirlColor']);die();
			//if($key==2)
			//header('Content-type: image/png');
			//return $steps['Swirl'.$key];
		    }
		    $i++;
		    
		    /*
		    if($i == 6) {
			header('Content-type: image/'.$options['format']);
			echo $steps['Swirl'.($key)];
			
		    }
		    */
		}
		
		
		$swStepsOut = '';
		
		reset($options['swirlColor']);
		$swStepsOut = $steps['Swirl'.key($options['swirlColor'])];
		$IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
		$IMCommand .= '	png:- ';
		$i=0;
		foreach($options['swirlColor'] as $key=>$swirl) {
		    if($i!=0) {
			$IMCommand .= '	png:- ';
			$IMCommand .= '	-composite ';
			$swStepsOut .= $steps['Swirl'.$key];
		    }
		    $i++;
		}
		$IMCommand .= '	png:- ';
		$this->exec($vars, $steps, 'Swirl', $IMCommand, 3, $swStepsOut);

		//var_dump($IMCommand);die();
		//header('Content-type: image/png');
		//return $steps['Swirl'];
	    }
	    
	    $BandBG[] = $steps['Swirl'];
	}
	    
	if(!empty($BandBG)) {
	    $bbInput = '';
	    $i=0;
	    $IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	    foreach($BandBG as $cmp) {
		if(empty($i)) {
		    $IMCommand .= '	png:- ';
		} else {
		    $IMCommand .= '	png:- ';
		    $IMCommand .= '     -composite ';
		}
		$i++;
		$bbInput .= $cmp;
	    }
	    
	    $IMCommand .= '	png:- ';
	    $this->exec($vars, $steps, 'BandBG', $IMCommand, 3, $bbInput);
	    
	}
	//header('Content-type: image/png');
	//return $steps['BandBG'];
	
	/*
	$IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	$IMCommand .= '	'.escapeshellarg(str_replace('\\', '\\\\', TPT_CACHE_DIR.DIRECTORY_SEPARATOR.'glitter'.DIRECTORY_SEPARATOR.'glitter.png')).' ';
	$IMCommand .= '	png:- ';
	$this->exec($vars, $steps, 'BandBG', $IMCommand, 2);
	header('Content-type: image/png');
	return $steps['BandBG'];
	*/
	
	if(!empty($options['glitter'])) {
	    $flt = intval($options['glitter'], 10);
	    $gfile = 'glitter.png';
	    if($flt == 2) {
		$gfile = 'mc-glitter.png';
	    }
	    $IMCommand = BIN_PATH.'composite ';
	    $IMCommand .= '	'.escapeshellarg(str_replace('\\', '\\\\', TPT_CACHE_DIR.DIRECTORY_SEPARATOR.'glitter'.DIRECTORY_SEPARATOR.$gfile)).' ';
	    $IMCommand .= '	-tile ';
	    $IMCommand .= '	png:- ';
	    $IMCommand .= '	png:- ';
	    $this->exec($vars, $steps, 'BandBG', $IMCommand, 3, $steps['BandBG']);
	    //$BandBG[] = $steps['Glitter'];
	    //return $steps['BandNoText'];
	    //var_dump($steps);die();
	}
	
	if(!empty($options['powdercoat'])) {
	    $flt = intval($options['powdercoat'], 10);
	    $gfile = 'powdercoat.png';
	    $IMCommand = BIN_PATH.'composite ';
	    $IMCommand .= '	'.escapeshellarg(str_replace('\\', '\\\\', TPT_CACHE_DIR.DIRECTORY_SEPARATOR.'powdercoat'.DIRECTORY_SEPARATOR.$gfile)).' ';
	    $IMCommand .= '	png:- ';
	    $IMCommand .= '	png:- ';
	    $this->exec($vars, $steps, 'BandBG', $IMCommand, 3, $steps['BandBG']);
	    //$BandBG[] = $steps['Glitter'];
	    //return $steps['BandNoText'];
	    //var_dump($steps);die();
	}
	
	$cfile = !empty($options['cfile'])?$options['cfile']:'';
	//if($_SERVER['REMOTE_ADDR'] == '109.160.0.218')
	if(!empty($cfile))
	    file_put_contents($cfile, $steps['BandBG']);
	//header('Content-type: image/png');
	//var_dump($steps);die();
	return $steps['BandBG'];
	/*
	else {
	    $steps['BandNoText'] = $steps['Band'];
	}
	*/
	
	/*
	$IMCommand = BIN_PATH.'composite ';
	$IMCommand .= '	png:- ';
	$IMCommand .= '	png:- ';
	$IMCommand .= '	png:- ';
	$this->exec($vars, $steps, 'BandComplete', $IMCommand, 3, $steps['TextReliefDistortExtent'].$steps['BandNoText']);
	*/
	
	//////////////////////////////////////// EMPTY TEXT ////////////////////////////////////////////
	//////////////////////////////////////// EMPTY TEXT ////////////////////////////////////////////
	//////////////////////////////////////// EMPTY TEXT ////////////////////////////////////////////
	//////////////////////////////////////// EMPTY TEXT ////////////////////////////////////////////
	//////////////////////////////////////// EMPTY TEXT ////////////////////////////////////////////
	
	//header('Content-type: image/'.$options['format']);
        return $steps['BandComplete'];
        
    }
    
    ////////////////////////////////////////////////////////////////
    function getDistortionGuidelines($options, $w, $h) {
        $cpoints = '';
        $cp = array();

        for($i=0; $i*2<$w; $i+=3) {
                $point_offset = round($w/2)-$i;
                $pointstr = '';

                $point = $point_offset.',28 '.$point_offset.','.max(28-round(pow($i, 1.85)*0.002), 0);
                $pointstr .= $point;
                $cp[] = $point;

                $pointstr .= '   ';

                $point = $point_offset.','.($h+28).' '.$point_offset.','.max(($h+28)-round(pow($i, 1.8)*0.0032), 0);
                $pointstr .= $point;
                $cp[] = $point;
                 
                $pointstr .= '   ';

                $point = ($w - $point_offset).',28 '.($w - $point_offset).','.max(28-round(pow($i, 1.85)*0.002), 0);
                $pointstr .= $point;
                $cp[] = $point;

                $pointstr .= '   ';
                 
                $point = ($w - $point_offset).','.($h+28).' '.($w - $point_offset).','.max(($h+28)-round(pow($i, 1.8)*0.0032), 0);
                $pointstr .= $point;
                $cp[] = $point;

                $cpoints .= $pointstr;

                $cpoints .= ' ';
        }

        $distorted = true;
        return array('cp'=>$cp, 'cpoints'=>$cpoints);
    }
    
    /////////////////////////////////////////////////////////
    function getDistortionGuidelines2($options, $w, $h) {
	$cpoints = '';
	$cp = array();
	for($j=30; $j<$options['X']; $j+=5) {
            $pointstr = '';
                    
            $point = ' 0,'.$j.' 15'/*.round(pow(($j/5)*0.5, 3)*0.05)*/.','.$j;
            $pointstr .= $point;
            $cp[] = $point;
                    
            $pointstr .= '   ';
                    
            $point = ' '.$options['X'].','.$j.' '.($options['X']-15/*round(pow(($j/5)*0.5, 3)*0.05)*/).','.$j;
            $pointstr .= $point;
            $cp[] = $point;
                    
            $pointstr .= '   ';
                    
            $cpoints .= $pointstr;
             
            $cpoints .= ' ';
	}
        
        return array('cp'=>$cp, 'cpoints'=>$cpoints);
    }
}