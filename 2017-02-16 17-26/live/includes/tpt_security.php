<?php

defined('TPT_INIT') or die('access denied');

class tpt_security {
    static $ascii_table;
    
    function __construct(&$vars) {
        return false;
    }
    

    static function encode_string(&$vars, $plaintext) {
        # --- ENCRYPTION ---
    
        # the key should be random binary, use scrypt, bcrypt or PBKDF2 to
        # convert a string into a key
        # key is specified using hexadecimal
        $key = $vars['config']['security_key'];
        
        # show key size use either 16, 24 or 32 byte keys for AES-128, 192
        # and 256 respectively
        //$key_size =  strlen($key);
        //echo "Key size: " . $key_size . "\n";
        
        //$plaintext = "This string was AES-256 / CBC / ZeroBytePadding encrypted.";
    
        # create a random IV to use with CBC encoding
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        
        # creates a cipher text compatible with AES (Rijndael block size = 128)
        # to keep the text confidential 
        # only suitable for encoded input that never ends with value 00h
        # (because of default zero padding)
        $ciphertext = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key,
                                     $plaintext, MCRYPT_MODE_CBC, $iv);
    
        # prepend the IV for it to be available for decryption
        $ciphertext = $iv . $ciphertext;
        
        # encode the resulting cipher text so it can be represented by a string
        $ciphertext_base64 = base64_encode($ciphertext);
    
        return  $ciphertext_base64;
    
        # === WARNING ===
    
        # Resulting cipher text has no integrity or authenticity added
        # and is not protected against padding oracle attacks.
        
    }

	static function decode_string(&$vars, $ciphertext_base64) {
		# --- DECRYPTION ---

		$ciphertext_dec = base64_decode($ciphertext_base64);

		# retrieves the IV, iv_size should be created using mcrypt_get_iv_size()
		$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
		$iv_dec = substr($ciphertext_dec, 0, $iv_size);

		# retrieves the cipher text (everything except the $iv_size in the front)
		$ciphertext_dec = substr($ciphertext_dec, $iv_size);

		# may remove 00h valued characters from end of plain text
		$plaintext_dec = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key,
			$ciphertext_dec, MCRYPT_MODE_CBC, $iv_dec);

		return  $plaintext_dec;
	}



	static function encode_string2(&$vars, $plaintext) {
		$db = $vars['db']['handler'];

		$query = 'SELECT * FROM `tpt_param` WHERE `value` LIKE CONCAT("%", (SELECT `name` FROM `tpt_param` WHERE `id`=11101), "%") ORDER BY `id` DESC';
		$db->query($query);
		$res = $db->fetch_assoc_list();
		//$k1 = reset($k1);
		$k1 = '';
		foreach($res as $r) {
			$k1 .= $r['name'].$r['value'];
		}
		//$k1 = $k1['value'];

		# --- ENCRYPTION ---

		# the key should be random binary, use scrypt, bcrypt or PBKDF2 to
		# convert a string into a key
		# key is specified using hexadecimal
		$key = encode_string($vars['config']['security_key'], $k1);

		# show key size use either 16, 24 or 32 byte keys for AES-128, 192
		# and 256 respectively
		//$key_size =  strlen($key);
		//echo "Key size: " . $key_size . "\n";

		//$plaintext = "This string was AES-256 / CBC / ZeroBytePadding encrypted.";

		# create a random IV to use with CBC encoding
		$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
		$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);

		# creates a cipher text compatible with AES (Rijndael block size = 128)
		# to keep the text confidential
		# only suitable for encoded input that never ends with value 00h
		# (because of default zero padding)
		$ciphertext = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key,
			$plaintext, MCRYPT_MODE_CBC, $iv);

		# prepend the IV for it to be available for decryption
		$ciphertext = $iv . $ciphertext;

		# encode the resulting cipher text so it can be represented by a string
		$ciphertext_base64 = base64_encode($ciphertext);

		return  $ciphertext_base64;

		# === WARNING ===

		# Resulting cipher text has no integrity or authenticity added
		# and is not protected against padding oracle attacks.

	}



    
    static function decode_string2(&$vars, $ciphertext_base64) {
        # --- DECRYPTION ---

		$db = $vars['db']['handler'];

		$query = 'SELECT * FROM `tpt_param` WHERE `value` LIKE CONCAT("%", (SELECT `name` FROM `tpt_param` WHERE `id`=11101), "%") ORDER BY `id` DESC';
		$db->query($query);
		$res = $db->fetch_assoc_list();
		//$k1 = reset($k1);
		$k1 = '';
		foreach($res as $r) {
			$k1 .= $r['name'].$r['value'];
		}

		$key = encode_string($vars['config']['security_key'], $k1);
        
        $ciphertext_dec = base64_decode($ciphertext_base64);
        
        # retrieves the IV, iv_size should be created using mcrypt_get_iv_size()
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        $iv_dec = substr($ciphertext_dec, 0, $iv_size);
        
        # retrieves the cipher text (everything except the $iv_size in the front)
        $ciphertext_dec = substr($ciphertext_dec, $iv_size);
    
        # may remove 00h valued characters from end of plain text
        $plaintext_dec = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key,
                                        $ciphertext_dec, MCRYPT_MODE_CBC, $iv_dec);
        
        return  $plaintext_dec;
    }
    
    
    static function getSecureToken(&$vars, $row_id) {
        if(empty($row_id)) {
            die('Unexpected error. Click the back button of your browser or contact technical support if problem persists.');
        }
        
        //$plaintext = $vars['environment']['request_time'].' '.str_pad($row_id, 9, ' ').' '.str_pad($vars['user']['client_ip'], 15, ' ');
        
        //$token = tpt_security::encode_string($vars, $plaintext);
        //$token = base64_encode(encode_string($plaintext, $vars['config']['key']));
        $chars = array();
        
        $rtime = $vars['environment']['request_time'];
        $rptime = str_pad($rtime, 10, '0', STR_PAD_LEFT);
        preg_match_all('#[0-9]{2}#', $rptime, $timearrmtch, PREG_PATTERN_ORDER);
        $timearr = reset($timearrmtch);
        $timearrchr = array_map('chr', $timearr);
        //$timearr1 = chr($timearr[1]);
        $timearr = implode($timearrchr);
        
        //$rip = explode('.', $vars['user']['client_ip']);
        //$iparrmtch = explode('.', $rip);
        //$iparrchr = array_map('chr', $iparrmtch);
        //$iparr = implode($iparrchr);
        $rip = $vars['user']['client_ip'];
        $iparrmtch = explode('.', $rip);
        $iparrmtch1 = array_map(function($var){return str_pad($var, 4, '0', STR_PAD_LEFT);}, $iparrmtch);
        $iparrmtch2 = implode($iparrmtch1);
        preg_match_all('#[0-9]{2}#', $iparrmtch2, $iparrmtch3, PREG_PATTERN_ORDER);
        $iparrmtch3 = reset($iparrmtch3);
        $iparrchr = array_map('chr', $iparrmtch3);
        $iparr = implode($iparrchr);
        
        
        $rorder = $row_id;
        $rporder = str_pad($rorder, 8, '0', STR_PAD_LEFT);
        preg_match_all('#[0-9]{2}#', $rporder, $orderarr, PREG_PATTERN_ORDER);
        $orderarrmtch = reset($orderarr);
        $orderarrchr = array_map('chr', $orderarrmtch);
        //$timearr1 = chr($timearr[1]);
        $orderarr = implode($orderarrchr);
        
        
        $ctext = $timearr.$orderarr.$iparr;
        $result = '';
        for($i=0, $_len=strlen($ctext); $i<$_len; $i++) {
            //var_dump($ctext);die();
            $code = intval(ord($ctext[$i]), 10);
            $result .= self::$ascii_table[$code]['an_rep1'].self::$ascii_table[$code]['an_rep2'];
        }
        
        
        return $result;
    }
    
    
    static function getSubmitToken(&$vars) {
        //$plaintext = $vars['environment']['request_time'].' '.str_pad($row_id, 9, ' ').' '.str_pad($vars['user']['client_ip'], 15, ' ');
        
        //$token = tpt_security::encode_string($vars, $plaintext);
        //$token = base64_encode(encode_string($plaintext, $vars['config']['key']));
        $chars = array();
        
        $rtime = $vars['environment']['request_time'];
        $rptime = str_pad($rtime, 10, '0', STR_PAD_LEFT);
        preg_match_all('#[0-9]{2}#', $rptime, $timearrmtch, PREG_PATTERN_ORDER);
        $timearr = reset($timearrmtch);
        $timearrchr = array_map('chr', $timearr);
        //$timearr1 = chr($timearr[1]);
        $timearr = implode($timearrchr);
        
        //$rip = explode('.', $vars['user']['client_ip']);
        //$iparrmtch = explode('.', $rip);
        //$iparrchr = array_map('chr', $iparrmtch);
        //$iparr = implode($iparrchr);
        $rip = $vars['user']['client_ip'];
        $iparrmtch = explode('.', $rip);
        $iparrmtch1 = array_map(function($var){return str_pad($var, 4, '0', STR_PAD_LEFT);}, $iparrmtch);
        $iparrmtch2 = implode($iparrmtch1);
        preg_match_all('#[0-9]{2}#', $iparrmtch2, $iparrmtch3, PREG_PATTERN_ORDER);
        $iparrmtch3 = reset($iparrmtch3);
        $iparrchr = array_map('chr', $iparrmtch3);
        $iparr = implode($iparrchr);
        
        
        $ctext = $timearr.$iparr;
        $result = '';
        for($i=0, $_len=strlen($ctext); $i<$_len; $i++) {
            //var_dump($ctext);die();
            $code = intval(ord($ctext[$i]), 10);
            $result .= self::$ascii_table[$code]['an_rep1'].self::$ascii_table[$code]['an_rep2'];
        }
        
        
        return $result;
    }
    
    
    function afterContent(&$vars) {

    }
}

tpt_security::$ascii_table = $tpt_vars['db']['handler']->getData($tpt_vars, 'tpt_ascii', '*', '', 'code', false);
$tpt_vars['environment']['url_processors'][] = $tpt_vars['environment']['request']['handler'] = new tpt_security($tpt_vars);
