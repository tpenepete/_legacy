<?php

defined('TPT_INIT') or die('access denied');

$subject = 'AmazingWristbands.com account Password Reset request';

// master template
$html_email_template = <<< EOT
<div>
<p>
A password reset request has been issued for this email address. If you wish to change your password 
click on the link below.
</p>
<a href="$tpt_baseurl/reset-password?token=$token">$tpt_baseurl/reset-password?token=$token</a>
<p></p>
<p>
If you don't need to change your password right now please follow this link:
<a href="$tpt_baseurl/reset-password?token=$token&action=cancel">$tpt_baseurl/reset-password?token=$token&action=cancel</a>
</p>
</div>
EOT;

?>
