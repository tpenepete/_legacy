<?php
ob_start();
include("class/customer-account-class.php");
$content = ob_get_contents();
ob_end_clean();