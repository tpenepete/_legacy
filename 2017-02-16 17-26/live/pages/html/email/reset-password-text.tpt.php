<?php

defined('TPT_INIT') or die('access denied');

$subject = 'AmazingWristbands.com account Password Reset request';
// master template
$text_email_template = <<< EOT
A password reset request has been issued for this email address. If you wish to change your password 
please copy the link below in your browsers address bar.
$tpt_baseurl/reset-password?token=$token

If you don't need to change your password right now please follow this link:
$tpt_baseurl/reset-password?token=$token&action=cancel
EOT;

?>
