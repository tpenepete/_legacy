<?php

defined('TPT_INIT') or die('access denied');

class tpt_mail {
    
    //static $redirect = false;
    //static $redirect_url = '';
    
    function __construct(&$vars) {
    }
    

    static function sendmail(&$vars, $from, $to,  $subject, $text_template, $html_template, $cc=array(), $bcc=array(), $hh=array()) {
        //$to = 'test@email.com';
        //define the subject of the email
        //$subject = 'Test HTML email';
         
        // Generate a random boundary string
        $mime_boundary = '_x'.sha1(time()).'x';
         

$cc_h = '';
if(!empty($cc))
$cc_h = 'Cc: '.implode(', ', $cc)."\r\n";

$bcc_h = '';
if(!empty($bcc))
$bcc_h = 'Bcc: '.implode(', ', $bcc)."\r\n";

$h_h = '';
if(!empty($hh))
$h_h = implode("\r\n", $hh)."\r\n";

$headers = '';
$headers .= 'From: '.$from."\r\n";
$headers .= 'MIME-Version: 1.0'."\r\n";
$headers .= $cc_h;
$headers .= $bcc_h;
$headers .= $h_h;
$headers .= 'Content-Type: multipart/alternative;boundary="PHP-alt'.$mime_boundary.'"'."\r\n";

$message = '';         
// Use our boundary string to create plain text and HTML versions
$message .= "\r\n";
$message .= '--PHP-alt'.$mime_boundary."\r\n";
$message .= 'Content-Type: text/plain; charset=iso-8859-1'."\r\n";
$message .= 'Content-Transfer-Encoding: 7bit'."\r\n";
$message .= "\r\n";
$message .= $text_template."\r\n";
$message .= '--PHP-alt'.$mime_boundary."\r\n";
$message .= 'Content-type: text/html; charset=iso-8859-1'."\r\n";
$message .= 'Content-Transfer-Encoding: 7bit'."\r\n";
$message .= "\r\n";
$message .= $html_template."\r\n";
$message .= '--PHP-alt'.$mime_boundary.'--';
         
        // Send the message
        return mail($to, $subject, $message, $headers);
        
    }
    
    /*
    function afterContent(&$vars) {
        if(self::$redirect) {
            //var_dump(self::$redirect_url);die();
            header("HTTP/1.1 301 Moved Permanently");
            header('Location: '.self::$redirect_url);
            $vars['template_data']['footer_scripts']['scripts'][] = 'document.location.href = "'.self::$redirect_url.'";';
        }
    }
    */
    
}

//$tpt_vars['environment']['url_processors'][] = $tpt_vars['environment']['request'] = new tpt_request($tpt_vars);
