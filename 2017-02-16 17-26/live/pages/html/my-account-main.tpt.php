<?php

defined('TPT_INIT') or die('access denied');


$view_address_url = $tpt_vars['url']['handler']->wrap($tpt_vars, '/my-addresses');
$personal_info_url = $tpt_vars['url']['handler']->wrap($tpt_vars, '/my-account-info');
$my_orders_url = $tpt_vars['url']['handler']->wrap($tpt_vars, '/my-orders');
$re_order_url = $tpt_vars['url']['handler']->wrap($tpt_vars, '/re-order');
$ac_vr_url = $tpt_vars['url']['handler']->wrap($tpt_vars, '/my-abandoned-carts');

$cookie_btn = '';


if(($tpt_vars['user']['data']['usertype'] == 3) && empty($_COOKIE['admin_mode'])) {
    $future = date('r', time()+60*60*24*365);
    $onclick = 'document.cookie = \'admin_mode=1; expires='.$future.'; path=/\';';
    $onclick .= 'alert(\'Admin Cookie set!\');';
    $onclick .= 'document.location.reload();';
    
    $cookie_btn = '<input type="button" value="Enable Admin Cookie" onclick="'.$onclick.'" />';
} else if(($tpt_vars['user']['data']['usertype'] == 3) && !empty($_COOKIE['admin_mode'])) {
    $past = date('r', time()-60*60*24*365);
    $onclick = 'document.cookie = \'admin_mode=\\\'\\\'; expires='.$past.'; path=/\';';
    $onclick .= 'alert(\'Admin Cookie deleted!\');';
    $onclick .= 'document.location.reload();';
    
    $cookie_btn = '<input type="button" value="Delete Admin Cookie" onclick="'.$onclick.'" />';    
}

$tpt_vars['template']['title'] = 'Customer Area';
$tpt_vars['template']['content'] .= <<< EOT
<style type="text/css">
.main-title {
color: #89502F;
text-transform: capitalize;
font-family: TODAYSHOP-BOLDITALIC,Arial;
font-size: 28px;
font-weight: bold;
text-shadow: 1px 1px white, -1px -1px black;
text-align: left;
margin: 0px;
border-bottom: solid 1px #C7C7C7;
}
.green-title {
color: #7BB67F;
height: 45px;
text-transform: capitalize;
font-family: TODAYSHOP-BOLDITALIC,Arial;
font-size: 28px;
font-weight: bold;
padding-left: 6px;
text-shadow: -1px -1px white, 1px 1px black;
text-align: center;
margin: 0px;
}
#error {
	color:red;
	font-size:8px;
	display:none;
}
.needsfilled {
	background:white;
	color:red;
	font-size:10px;
}
.inputtxt-custom {
	background-image:url(images/customize-text-bg.png);
	width:191px;
	height:21px;
	padding-left:3px;
	background-repeat:no-repeat;
	padding-top:2px;
	float:left;
}

.inn-custom {
	width:180px;
	height:15px;
	border: solid 1px #ffffff;
	background-color:#ffffff;
}
.form_titles{
	width: 120px;
    height: 22px;
    color: #56321C;
    font-size: 12px;
    font-weight: bold;
    font-family: Arial, Helvetica, sans-serif;
    padding-top: 3px;
    padding-left: 80px;
    padding-right: 20px;
    text-align: right;
    float: left;
}
.form_titles span{
	color:#F40000;
	font-weight:bold;
}
.form_titles_red{
	width:330px;
	color:#000;
	font-size:11px;
	font-weight:bold;
	font-family:Arial, Helvetica, sans-serif;
	margin-bottom:3px;
	text-align:left;
}
.form_con{
	margin:0px;
	margin-bottom:20px;
}
.steps{
	color:#583823;
	font-size:20px;
	font-family:Georgia, "Times New Roman", Times, serif;
	text-align:left;
	margin-left:10px;
	font-weight:bold;
}
.drop-down{
	width:158px;
	height:22px;
	background-image:url(images/drop-down-bg.png);
	background-repeat:no-repeat;
	padding-top:2px;
	text-align:center;
	float:left;
}
.drop-down select{
	width:150px;
	height:20px;
	border:1px solid #FFF;
}
.cl_btn {
    width: 79px;
    height: 34px;
    display: block;
    cursor: pointer;
}

.cl_btn.send {
    background-image: url(images/btn_send.png);
}

.cl_btn.cancel {
    background-image: url(images/btn_cancel.png);
    margin-top: -34px;
    margin-left: 85px;
}

/* --- NEW DESIGN ---*/

.my-account {
	color:#cbc5bf;
	font-family:TODAYSHOP-BOLDITALIC;
	font-size:26px;
	font-weight:bolder;
	padding:10px 0px 8px 0px;
	text-shadow: -1px -1px #FFFFFF, 1px 1px #9a9591;
}
.top-line-sep {
	background-image:url(images/account-top-line.png);
	background-repeat:repeat-x;
	width:438px;
	height:4px;
	margin:0px auto;
}
.acc-dashboad-top {
	width:676px;
	height:19px;
	background-image:url(images/dashboad-top-bg.png);
	background-repeat:no-repeat;
	background-position:center;
	overflow:hidden;
}
.acc-dashboad-middle {
	width:676px;
	background-image:url(images/dashboad-middle-bg.png);
	background-repeat:repeat-y;
	background-position:center;
	overflow:hidden;
}
.acc-dashboad-bottom {
	width:676px;
	height:19px;
	background-image:url(images/dashboad-bottom-bg.png);
	background-repeat:no-repeat;
	background-position:center;
	overflow:hidden;
}
.dashboad-left {
	width:410px;

}
.dashboad-left p {
	 font-size:15px;
	 text-align:left;
	 font-family:TODAYSHOP-BOLDITALIC;
	 font-size:18px;
	 color:#909090;
	 font-weight:bold;
 }
 .acc-rows{
	 color:#909090;
 }
.dashboad-left .acc-rows {
	margin-bottom:20px !important;
	text-align:center;
	height:20px;
}
.dashboad-left .acc-rows1 {
	margin-bottom:4px !important;
	text-align:center;
	height:20px;
}
.dashboad-right .acc-rows {
	margin-bottom:20px !important;
	text-align:center;
	height:20px;
}
.dashboad-right .acc-rows1 {
	margin-bottom:4px !important;
	text-align:center;
	height:20px;
}
.dashboad-middle {
	width:14px;
	border-left:solid 1px #cdcdcd;
}
.dashboad-right {
	width:236px;
}
.dashboad-right ul {
	margin-left:20px;
	padding:0px;
}
.dashboad-right ul li {
	padding-top:6px;
	list-style:none;
}
.dashboad-right ul li a {
	color:#df3939;
	text-decoration:underline;
	font-family:Arial;
	font-size:13px;
	font-weight:normal;
}
.welcome {
	width:676px;
	margin:0px auto;
	background-repeat:no-repeat;
}
.globe-left {
	width:110px;
	height:140px;
	background-image:url(images/globe-left.png);
	background-repeat:no-repeat;
	position:absolute;
	margin-top:-60px;
	z-index:80;
}
.globe-right {
	width:119px;
	height:143px;
	background-image:url(images/globe-right.png);
	background-repeat:no-repeat;
	position:absolute;
	margin-top:-50px;
	margin-left:-10px;
}
.welcome-text {
	background-image:url(images/welcome-to-account.png);
	background-repeat:no-repeat;
	background-position:center;
	width:490px;
	height:59px;
	position:absolute;
	margin-left:-33px;
	z-index:90;
}


.globe-hele {
	background-image:url(images/globe-hele.png);
	background-repeat:no-repeat;
	background-position:center;
	width:165px;
	height:249px;
	position:absolute;
	margin-left:60px;
	margin-top:-40px;
}
.acc-title {
	width:170px;
	float:left;
	font-family:Arial, Helvetica, sans-serif !important;
	font-size:14px !important;
	color:#919191 !important;
	text-align:right;
	padding:0px 10px 0px 0px;

}
.acc-title span{
	color:#F20000;
}
.acc-field {
	width:174px;
	height:22px;
	float:left;
	text-align:left;
	padding:0px 10px 0px 4px;
}
.acc-field span{
	color:#de3a3a;
	font-family:Arial, Helvetica, sans-serif !important;
	font-size:14px !important;
}

.acc-title1 {
	width:120px;
	float:left;
	font-family:Arial, Helvetica, sans-serif !important;
	font-size:14px !important;
	color:#919191 !important;
	text-align:right;
	padding:0px 5px 0px 0px;

}
.acc-title1 span{
	color:#F20000;
}
.acc-field1 {
	width:164px;
	height:22px;
	float:left;
	text-align:left;
	padding:0px 5px 0px 4px;
}
.acc-field1 span{
	color:#de3a3a;
	font-family:Arial, Helvetica, sans-serif !important;
	font-weight:bold !important;
	font-size:13px !important;
}


.txt-field {
	background-image:url(images/acc-textbox-bg.png);
	background-repeat:no-repeat;
	background-color:none;
	width:166px;
	height:20px;
	border:none;
	padding:0px 0px 2px 6px;
}
.sele-field {
	background-image:url(images/acc-textbox-bg.png);
	background-repeat:no-repeat;
	background-color:none;
	width:183px;
	height:23px;
	border:none;
	padding:3px 20px 8px 6px;
	margin-left:10px;
}
.submit-field {
	width:161px;
	height:30px;
	padding-top:7px;
	background-image:url(images/acc-submit-bg.png);
	background-repeat:no-repeat;
	text-align:center;
	font-family:TODAYSHOP-BOLDITALIC;
	font-size:18px;
	font-weight:bold;
	color:#5c3925;
	text-decoration:none;
}
.submit-field:hover {
	color:#e0a14c;
}

.dashboad-left-1 {
	width:230px;
	min-height:320px;


}
.dashboad-middle-1 {
	width:166px;
}
.dashboad-right-1 {
	width:259px;
}
.dashboad-left-1 .acc-rows {
	margin-bottom:20px !important;
	text-align:center;
	height:20px;
}
.dashboad-right-1 .acc-rows {
	margin-bottom:20px !important;
	text-align:center;
	height:20px;
}
.globe-para {
	width:127px;
	height:301px;
	background-image:url(images/globe-para.PNG);
	background-repeat:no-repeat;
	position:absolute;
	margin-left:30px;
	margin-top:-40px;
}
.address-titles {
	font-family:TODAYSHOP-BOLDITALIC;
	font-weight:bolder;
	color:#cd3636;
	font-size:18px;
	padding-bottom:18px;
}
.no-wishlist {
	width:231px;
	height:82px;
	background-image:url(images/no-wishlist.png);
	background-repeat:no-repeat;
}
.submit-wishlist {
	width:209px;
	height:30px;
	background-image:url(images/acc-submit-yellow-bg.png);
	background-repeat:no-repeat;
	text-align:center;
	font-family:TODAYSHOP-BOLDITALIC;
	font-size:18px;
	font-weight:bold;
	color:#5c3925;
	text-decoration:none;
	padding-top:7px;
}
.submit-wishlist:hover {
	color:#ffa131;
	text-decoration:none;
}
.wishlist {
	width:261px;
	height:147px;
	background-image:url(images/wish-list-img.png);
	background-repeat:no-repeat;
	position:absolute;
	margin-top:-90px;
}
.wishlist-dashboad-top {
	background-image:url(images/wishlist-bg-top.png);
	background-repeat:no-repeat;
	width:670px;
	height:24px;
	overflow:hidden;
	text-align:center;
	font-family:TODAYSHOP-BOLDITALIC;
	font-size:14px;
	font-weight:normal;
	color:#9d9c9b;
	padding-top:10px;
}
.wishlist-dashboad-top td{
	font-family:TODAYSHOP-BOLDITALIC;
	font-size:18px;
	font-weight:bold;
	color:#ce3537;
	padding:0px 0px 8px 30px;
}
.wishlist-dashboad-middle {
	background-image:url(images/wishlist-bg-middle.png);
	background-repeat:repeat-y;
	width:670px;
	overflow:hidden;
}
.acc-list-orders td{
	font-family:Arial, Helvetica, sans-serif;
	font-size:14px;
	font-weight:normal;
	color:#929292;
	padding:8px 0px 8px 30px;
}
.wishlist-dashboad-bottom {
	background-image:url(images/wishlist-bg-bottom.png);
	background-repeat:no-repeat;
	width:670px;
	height:17px;
	text-align:right;
}
.qty {
	padding:4px 0px 4px 0px;
	width:322px;
	float:left;
	height:35px;
	overflow:hidden;
}

.subtotal {
	padding:4px 0px 4px 0px;
	padding:4px 0px 4px 0px;
	width:322px;
	float:left;
	height:35px;
	overflow:hidden;
}
.preorder {
	width:167px;
	height:28px;
	background-image:url(images/preorder.png);
	background-repeat:no-repeat;
	font-family:TODAYSHOP-BOLDITALIC;
	font-size:18px;
	font-weight:bold;
	color:#5c3925;
	text-decoration:none;
	padding-top:6px;
}
.preorder:hover {
	color:#4a872f;
	text-decoration:none;
}
.remove-wishlist {
	width:85px;
	height:25px;
	background-image:url(images/remove-wishlist.png);
	background-repeat:no-repeat;
	position:absolute;
	margin-left:580px;
	margin-top:9px;
}
.show_orders {
	width:199px;
	height:148px;
	background-image:url(images/acc-no-orders.png);
	background-repeat:no-repeat;
	position:absolute;
	margin-top:-20px;
}
/*--- END ---*/
</style>
EOT;

if($tpt_vars['environment']['mobile_template']){
    $tpt_vars['template']['content'] .= <<< EOT
<div align="center" style="width:100%; margin:0px auto; min-height:300px;">
    <div class="my-account">My Account</div>
    <div class="top-line-sep" style="width:100%;"></div>
    <div class="amz_login white-box clearFix padding-10">
        <table width="100%" cellspacing="0" cellpadding="0" align="center">
            <tbody>
            <tr>
                <td align="center" class="dashboad-left">
                    <p align="center">Please select an option to manage your accounts:</p>
                </td>
            </tr>
            <tr>
                <td align="left" class="dashboad-right">
                    <ul>
                        <li><a href="$personal_info_url">Change Personal Info/Password</a></li>
                        <li><a href="$view_address_url">My Addresses</a></li>
                        <li><a href="$my_orders_url">My Orders</a></li>
                        <li><a href="$re_order_url">*New Feature - ReOrder</a></li>
                        <li><a href="$ac_vr_url">View/Restore Abandoned Carts</a></li>
                    </ul>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
EOT;
} else {
    $tpt_vars['template']['content'] .= <<< EOT
<div align="center" style="width:688px; margin:0px auto; min-height:300px;">
    <div class="my-account">My Account</div>
    <div class="top-line-sep"></div>
    <div class="welcome">
        <table width="100%" cellspacing="0" cellpadding="0" align="center">
            <tbody>
            <tr>
                <td width="17%" height="143" align="left">
                    <div class="globe-left"></div>
                </td>
                <td width="65%" align="center">
                    <div class="welcome-text"></div>
                </td>
                <td width="18%" align="right">
                    <div class="globe-right"></div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="acc-dashboad-top"></div>
    <div class="acc-dashboad-middle">
        <div style="width:98%; margin:0px auto;">
            <table width="100%" cellspacing="0" cellpadding="0" align="center">
                <tbody>
                <tr>
                    <td align="center" class="dashboad-left">
                        <p align="center">Please select an option to manage your accounts:</p>

                    </td>
                    <td align="center" class="dashboad-middle">&nbsp;</td>
                    <td align="left" class="dashboad-right">
                        <ul>
                            <li><a href="$personal_info_url">Change Personal Info/Password</a></li>
                            <li><a href="$view_address_url">My Addresses</a></li>
                            <li><a href="$my_orders_url">My Orders</a></li>
                            <li><a href="$re_order_url">*New Feature - ReOrder</a></li>
                            <li><a href="$ac_vr_url">View/Restore Abandoned Carts</a></li>
                        </ul>
                    </td>
                </tr>
                </tbody>
            </table>
            $cookie_btn
        </div>
    </div>
    <div class="acc-dashboad-bottom"></div>
</div>
EOT;
}
?>