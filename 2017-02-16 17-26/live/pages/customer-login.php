<?php
ob_start();
include("class/customer-login-class.php");
$content = ob_get_contents();
ob_end_clean();