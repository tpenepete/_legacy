<?php

defined('TPT_INIT') or die('access denied');


$stylesheet = '';

$tpt_vars['template']['quote_link'] = '';
$tpt_vars['template']['social_bar'] = '';

//$tpt_vars['template_data']['head'][] = <<< EOT
//<style type="text/css">
//$stylesheet
//</style>
//EOT;

//die('asdasddasasdasd');

/*
$query = <<< EOT
SELECT
    *
FROM
    `information_schema`.`COLUMNS`
WHERE
    `table_schema` = 'amazingw_templay2'
    AND
    `collation_name` != 'utf8_general_ci';
EOT;
$tpt_vars['db']['handler']->query($query, __FILE__);
$allcolumns = $tpt_vars['db']['handler']->fetch_assoc_list('id', false);
foreach($allcolumns as $column) {
    var_dump($column);die();
    $table = $column['TABLE_NAME'];
    $table = $column['TABLE_NAME'];
    $qry = <<< EOT
    ALTER TABLE `$table`
    
EOT;
}
*/

/*
$query = <<< EOT
SELECT * FROM (
    SELECT
        `temp_comment_status`.quote_id
    FROM
        `temp_custom_orders`,
        `temp_comment_status`
    WHERE
        `temp_custom_orders`.`payment_method` LIKE "check%"
        AND
        `temp_custom_orders`.`id`=`temp_comment_status`.`quote_id`
        AND
        `temp_comment_status`.`status`=10
    ORDER BY
        `ptimestamp`
) AS `a1`

EOT;
*/
/*
$query = <<< EOT
SELECT
    *
FROM
    `temp_custom_orders`
WHERE
    `temp_custom_orders`.`payment_method` LIKE "check%"
EOT;
//GROUP BY
//    `quote_id`
//die($query);
$tpt_vars['db']['handler']->query($query, __FILE__);
$allorders = $tpt_vars['db']['handler']->fetch_assoc_list('id', false);


$flds = array(
              'timestamp',
              'data',
              'quote_id',
              'comment_id',
              'customer_billing_address',
              'customer_shipping_address',
              'ptimestamp',
              'invoice',
              'subtotal',
              'tax',
              'discount',
              'shipping',
              'total',
              'currency_code',
              'payment_first_name',
              'payment_last_name',
              'payment_company',
              'payment_email',
              'payment_phone',
              'payment_address_street',
              'payment_address_city',
              'payment_address_state',
              'payment_address_zip',
              'payment_address_country',
              'payment_address_country_code',
              'shipping_first_name',
              'shipping_last_name',
              'shipping_company',
              'shipping_email',
              'shipping_phone',
              'shipping_address_street',
              'shipping_address_city',
              'shipping_address_state',
              'shipping_address_zip',
              'shipping_address_country',
              'shipping_address_country_code',
              'payment_status',
              'added_by',
              'dev',
              );


$flds = implode(', ', $flds);

$i=0;
$j=1;
if(!empty($_GET['stop'])) {
    $j = intval($_GET['stop'], 10);
}

foreach($allorders as $id=>$order) {
    //$id = $order['id'];
    $query = <<< EOT
    SELECT
        *
    FROM
        `temp_comment_status`
    WHERE
        `temp_comment_status`.`quote_id`=$id
EOT;
    $tpt_vars['db']['handler']->query($query, __FILE__);
    $statuses = $tpt_vars['db']['handler']->fetch_assoc_list();
    $ptimestamp = 0;
    $statusid = 0;
    foreach($statuses as $status) {
        $statusid = $status['id'];
        $ptimestamp = $status['timestamp'];
        if($status['status'] == 10) {
            break;
        }
    }
    //GROUP BY
    //    `quote_id`
    //die($query);
    $payment_address = array_map('trim', preg_split('#[\\r]+#', strip_tags($order['customer_billing_address'])));
    $payment_name = array_shift($payment_address);
    $payment_phone = array_pop($payment_address);
    
    $shipping_address = array_map('trim', preg_split('#[\\r]+#', strip_tags($order['customer_shipping_address'])));
    $shipping_name = array_shift($shipping_address);
    $shipping_phone = array_pop($shipping_address);
    //$shipping_address = $order['customer_shipping_address'];
    
    //var_dump($shipping_address);
    //if($i == $j) {
    //    die();
    //}
    //var_dump($order);die();
    $vals = array(
                  'timestamp'=>time(),
                  'data'=>$order['cus_payment_details'],
                  'quote_id'=>$id,
                  'comment_id'=>$statusid,
                  'customer_billing_address'=>$order['customer_billing_address'],
                  'customer_shipping_address'=>$order['customer_shipping_address'],
                  'ptimestamp'=>$ptimestamp,
                  'invoice'=>$order['order_id'],
                  'subtotal'=>floatval($order['Total_Price']),
                  'tax'=>floatval($order['Tax']),
                  'discount'=>floatval($order['Discount']),
                  'shipping'=>floatval($order['Shipping']),
                  'total'=>((floatval($order['Total_Price']) + floatval($order['Tax'])) - floatval($order['Discount'])) + floatval($order['Shipping']),
                  'currency_code'=>'USD',
                  'payment_first_name'=>$payment_name,
                  'payment_last_name'=>'',
                  'payment_company'=>'',
                  'payment_email'=>$order['customer_email_id'],
                  'payment_phone'=>$payment_phone,
                  'payment_address_street'=>'',
                  'payment_address_city'=>'',
                  'payment_address_state'=>'',
                  'payment_address_zip'=>'',
                  'payment_address_country'=>'United States',
                  'payment_address_country_code'=>'US',
                  'shipping_first_name'=>$shipping_name,
                  'shipping_last_name'=>'',
                  'shipping_company'=>'',
                  'shipping_email'=>'',
                  'shipping_phone'=>$shipping_phone,
                  'shipping_address_street'=>'',
                  'shipping_address_city'=>'',
                  'shipping_address_state'=>'',
                  'shipping_address_zip'=>'',
                  'shipping_address_country'=>'United States',
                  'shipping_address_country_code'=>'US',
                  'payment_status'=>'complete',
                  'added_by'=>'0',
                  'dev'=>'init data 08.03.2014',
                  );
    $vals = '"'.implode('", "', array_map('mysql_real_escape_string', $vals)).'"';
    $query = 'INSERT INTO `tpt_pgrq_check_app_transaction_info` ('.$flds.') VALUES('.$vals.')';
    $tpt_vars['db']['handler']->query($query, __FILE__);
    
    $i++;
}
*/
/*
$query = 'SELECT DISTINCT(`order_id`) FROM `temp_custom_orders`';
$tpt_vars['db']['handler']->query($query, __FILE__);
$allorders = $tpt_vars['db']['handler']->fetch_assoc_list('order_id');



////////////////////////////////////////////////////// PAYPAL TRANSACTION SEARCH
// Set up your API credentials, PayPal end point, and API version.
$API_UserName = urlencode(PAYPAL_API_USERNAME);
$API_Password = urlencode(PAYPAL_API_PASSWORD);
$API_Signature = urlencode(PAYPAL_API_SIGNATURE);
$API_Endpoint = PAYPAL_API_ENDPOINT;
$version = urlencode(PAYPAL_API_PROTOCOL_VERSION);
$methodName = 'TransactionSearch';
$startDateStr = '01/01/2010';
$start_time = strtotime($startDateStr);
$iso_start = date('Y-m-d\T00:00:00\Z',  $start_time);
$nvpreq = "METHOD=TransactionSearch&VERSION=$version&PWD=$API_Password&USER=$API_UserName&SIGNATURE=$API_Signature&STARTDATE=$iso_start";

//var_dump($allorders);die();

////////////////////////////////////////////////////// AUTHORIZE.NET TRANSACTION SEARCH
require_once(TPT_INCLUDES_DIR.DIRECTORY_SEPARATOR.'payment_classes'.DIRECTORY_SEPARATOR.'Auth.net'.DIRECTORY_SEPARATOR.'AuthorizeNet.php');


foreach($allorders as $order_id=>$odata) {
    $invnum = intval($_POST['order_id'], 10);
    $nvpStr = "&INVNUM=$order_id";

    
    $result = sendSingleRequest($API_Endpoint, $nvpreq.$nvpStr, 'p');
    
    if(preg_match('#L_TRANSACTIONID[0-9]+#', $result['body'])) {
        $qry = $result['body'];
        $qry = preg_replace('#L_TIMESTAMP[0-9]+#', 'L_TIMESTAMP[]', $qry);
        $qry = preg_replace('#L_TYPE[0-9]+#', 'L_TYPE[]', $qry);
        $qry = preg_replace('#L_NAME[0-9]+#', 'L_NAME[]', $qry);
        $qry = preg_replace('#L_EMAIL[0-9]+#', 'L_EMAIL[]', $qry);
        $qry = preg_replace('#L_STATUS[0-9]+#', 'L_STATUS[]', $qry);
        $qry = preg_replace('#L_AMT[0-9]+#', 'L_AMT[]', $qry);
        $qry = preg_replace('#L_CURRENCYCODE[0-9]+#', 'L_CURRENCYCODE[]', $qry);
        $qry = preg_replace('#L_FEEAMT[0-9]+#', 'L_FEEAMT[]', $qry);
        $qry = preg_replace('#L_NETAMT[0-9]+#', 'L_NETAMT[]', $qry);
        $qry = preg_replace('#L_TRANSACTIONID[0-9]+#', 'L_TRANSACTIONID[]', $qry);
        
        parse_str($qry, $qry);
        //var_dump($qry['L_TRANSACTIONID']);die();
        foreach($qry['L_TRANSACTIONID'] as $tx) {
            $txreq = "METHOD=GetTransactionDetails&VERSION=$version&PWD=$API_Password&USER=$API_UserName&SIGNATURE=$API_Signature";
            //var_dump($txreq.'&TRANSACTIONID='.$tx);die();
            $transaction = sendSingleRequest($API_Endpoint, $txreq.'&TRANSACTIONID='.$tx, 'p');
        }
        //die();
    }
    
    
}


// Get Settled Batch List
$request = new AuthorizeNetTD;

$years = array(
               '2012'=>array('05', '06', '07', '08', '09', '10', '11', '12'),
               '2013'=>array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'),
               '2014'=>array('01', '02'),
               );

$years = array(
               2012=>array(5, 6, 7, 8, 9, 10, 11, 12),
               2013=>array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12),
               2014=>array(1, 2),
               );

die('sdfsdfasdfgsdgsdfgsdf'); 
ob_end_flush();
ob_implicit_flush();
$transactions = array();
foreach($years as $year=>$months) {
    echo 'Year: '.$year.'<br />';
    ob_flush();
    flush();
    foreach($months as $month) {
        echo 'Month: '.$month.'<br />';
        ob_flush();
        flush();
        //$response = $request->getSettledBatchList(false, '2012-'.$month.'-01T00:00:00', '2012-06-01T00:00:00');
        $response = $request->getSettledBatchListForMonth($month, $year);
        //echo count($response->xml->batchList->batch) . " batches\n";
        //var_dump($response);die();
        //foreach ($response->xml->batchList->batch as $batch) {
        //    echo "Batch ID: " . $batch->batchId . "\n";
        //}
        
        foreach ($response->xml->batchList->batch as $batch) {
            //var_dump($batch);die();
            $batch_id = (string)$batch->batchId;
            echo 'Batch ID: '.$batch_id.'<br />';
            ob_flush();
            flush();
            //$request = new AuthorizeNetTD;
            $tran_list = $request->getTransactionList($batch_id);
            //var_dump($tran_list);die();
            $newtrans = $tran_list->xpath("transactions/transaction");
            echo 'Transactions #: '.count($newtrans).'<br />';
            ob_flush();
            flush();
            $transactions = array_merge($transactions, $newtrans);
        }

    }
    
}

var_dump(count($transactions));
die();

//var_dump(count($transactions));die();
// Get Transaction Details
$transactionId = "191811356";
$transaction = $request->getTransactionDetails($transactionId);
var_dump($transaction);die();
*/

/*
$color_module = getModule($tpt_vars, "BandColor");


include(PHPSVG_PATH.DIRECTORY_SEPARATOR.PHPSVG_LIB_FILE);


$svg = SVGDocument::getInstance( SVG_FONTS_PATH.DIRECTORY_SEPARATOR.'Aclonica.svg' ); //open to edit
//$svg = SVGDocument::getInstance( ); //default read to use
//$rect = #create a new rect with, x and y position, id, width and heigth, and the style
//$rect = SVGRect::getInstance( 0, 5, 'myRect', 228, 185, new SVGStyle( array( 'fill' => 'red', 'stroke' => 'blue' ) ) );
//$svg->addShape( $rect );
$text = SVGText::getInstance( 22, 50, 'tpt_message', 'Na batko teksteca', 'font-family: Aclonica,sans-serif; font-size:25' );
$svg->addShape( $text );

$svg->asXML(SVG_FONTS_PATH.DIRECTORY_SEPARATOR.'Aclonica_phpsvg.svg'); //output to svg file
//$svg->export('output/output.png'); //export as png
//$svg->export('output/thumb32x32.png',32,32); //export thumbnail
//$svg->output(); //echo with header to browser



//$out = tpt_PreviewGenerator::ttf2svg($tpt_vars, 'Aclonica');
tpt_dump($color_module->getColorProps($tpt_vars, '6:25'), true);
*/

$color_module = getModule($tpt_vars, "BandColor");
$type_module = getModule($tpt_vars, "BandType");
$style_module = getModule($tpt_vars, "BandStyle");
$size_module = getModule($tpt_vars, "BandSize");
$data_module = getModule($tpt_vars, "BandData");
$font_module = getModule($tpt_vars, "BandFont");
$message_module = getModule($tpt_vars, "BandMessage");
$clipart_module = getModule($tpt_vars, "BandClipart");
$cpf_module = getModule($tpt_vars, "CustomProductField");
$wclass_module = getModule($tpt_vars, "WritableClass");
$rushorder_module = getModule($tpt_vars, "RushOrder");


//$products = $cpf_module->normalizeOldProductData($tpt_vars, 17959, 0, 'preview_name' );
//@$products = $cpf_module->normalizeOldProductData($tpt_vars, $_GET['order_id'], $_GET['quote_id'], $_GET['index_by'] );
//tpt_dump($products, true);


/*
$query = 'SELECT `data` FROM `tpt_request_rq_query_errors` WHERE `data` LIKE "%INSERT INTO `tpt_request_cart`%"';
$tpt_vars['db']['handler']->query($query, __FILE__);
$data = $tpt_vars['db']['handler']->fetch_assoc_list('', false);
$i = 0;
$j = 0;
//tpt_dump(count($data), true);
foreach($data as $d) {
    $i++;
    if(preg_match('#(INSERT INTO `tpt_request_cart`[\s]*?\([^\(]+\([^,]+,[^,]+,[^,]+,[^,]+,[\n]+[^\n]+,[\n]+[\s]+)(Array|)(,[^\)]+\))#', $d['data'])) {
        $q = preg_replace('#(INSERT INTO `tpt_request_cart`[\s]*?\([^\(]+\([^,]+,[^,]+,[^,]+,[^,]+,[\n]+[^\n]+,[\n]+[\s]+)(Array|)(,[^\)]+\))#s', '${1}0${3}', $d['data']);
        //tpt_dump($q, true);
        $tpt_vars['db']['handler']->query($q, __FILE__);
        $j++;
    }
}
tpt_dump($i);
tpt_dump($j, true);
*/

/*
$haha = "58561,58561,57416,59639,59883,59935,59935,58875,58875,57720,60080,59952,59952,59952,59952,60184,42078,60157,60257,60107,60325,60325,60366,60380,60571,60223,60571,60136,60847,60847,60946,60946,60946,58535,61450,61480,61480,61458,59687,61130,61718,61718,61792,60844,60844,61706,61834,61718,61912,61912,61767,62351,61393,62564,61393,61393,61393,61393,61393,61393,61393,62598,62564,62564,62564,62564,62564,62564,62564,62564,62564,62564,62564,62564,62564,62564,62564,62564,62564,62564,62564,62564";
$haha = explode(",", $haha);
$haha = array_unique($haha);
sort($haha);
$haha = implode(",", $haha);
tpt_dump($haha, true);
*/
//$tpt_vars['template']['content'] .= $html;


/*
$allcss = $tpt_vars['db']['handler']->getData($tpt_vars, 'tpt_html_css', '*', '`file`=1', 'id', false );
foreach($allcss as $id=>$css) {
	$c = mysql_real_escape_string(file_get_contents($css['path']));
	$query = <<< EOT
UPDATE `tpt_html_css` SET `content`="$c" WHERE `id`=$id
EOT;
	$tpt_vars['db']['handler']->query($query);
}






$css = array();
$c_css = array();

$css['core'] = $tpt_vars['db']['handler']->getData($tpt_vars, 'tpt_html_css', '*', '`core`=1 ORDER BY `id`', 'id', false );
$c_css['core'] = '';
foreach($css['core'] as $id=>$c) {
	$c_css['core'] .= $c['content']."\n";
}
file_put_contents('/home/amazingw/public_html/live_resources/live/css/all_unpack.css', $c_css['core']);
$c_css['core'] = preg_replace('/(\{|\}|;)[\s]+([a-zA-Z]|\{|\}|;)/', '$1$2', $c_css['core']);
//tpt_dump($c_css['core'], true);
file_put_contents('/home/amazingw/public_html/live_resources/live/css/all.css', $c_css['core']);

$css['frontend'] = $tpt_vars['db']['handler']->getData($tpt_vars, 'tpt_html_css', '*', '`frontend`=1 ORDER BY `id`', 'id', false );
$c_css['frontend'] = '';
foreach($css['frontend'] as $id=>$c) {
	$c_css['frontend'] .= $c['content']."\n";
}
file_put_contents('/home/amazingw/public_html/live_resources/live/css/all1_unpack.css', $c_css['frontend']);
$c_css['frontend'] = preg_replace('/(\{|\}|;)[\s]+([a-zA-Z]|\{|\}|;)/', '$1$2', $c_css['frontend']);
file_put_contents('/home/amazingw/public_html/live_resources/live/css/all1.css', $c_css['frontend']);






$alljs = $tpt_vars['db']['handler']->getData($tpt_vars, 'tpt_html_js', '*', '`file`=1', 'id', false );
foreach($alljs as $id=>$js) {
	$c = mysql_real_escape_string(file_get_contents($js['path']));
	$query = <<< EOT
UPDATE `tpt_html_js` SET `content`="$c" WHERE `id`=$id
EOT;
	$tpt_vars['db']['handler']->query($query);
}


include(TPT_LIB_DIR.DIRECTORY_SEPARATOR.'JShrink'.DIRECTORY_SEPARATOR.'Minifier.php');

$js = array();
$c_js = array();
//tpt_dump('asd', true);
$js['core'] = $tpt_vars['db']['handler']->getData($tpt_vars, 'tpt_html_js', '*', '`core`=1 AND `defer`=0 ORDER BY `id`', 'id', false );
$c_js['core'] = '';
foreach($js['core'] as $id=>$c) {
	$c_js['core'] .= $c['content']."\n";
}
file_put_contents('/home/amazingw/public_html/live_resources/live/js/all_unpack.js', $c_js['core']);
//$c_js['core'] = preg_replace('/(\{|\}|;)[\s]+([a-zA-Z]|\{|\}|;)/', '$1$2', $c_js['core']);
//$c_js['core'] = JShrink::minify($c_js['core']);;
file_put_contents('/home/amazingw/public_html/live_resources/live/js/all.js', $c_js['core']);

$js['frontend'] = $tpt_vars['db']['handler']->getData($tpt_vars, 'tpt_html_js', '*', '`frontend`=1 AND `defer`=0 ORDER BY `id`', 'id', false );
$c_js['frontend'] = '';
foreach($js['frontend'] as $id=>$c) {
	$c_js['frontend'] .= $c['content']."\n";
}
file_put_contents('/home/amazingw/public_html/live_resources/live/js/all1_unpack.js', $c_js['frontend']);
//$c_js['frontend'] = preg_replace('/(\{|\}|;)[\s]+([a-zA-Z]|\{|\}|;)/', '$1$2', $c_js['frontend']);
//$c_js['frontend'] = JShrink::minify($c_js['frontend']);
file_put_contents('/home/amazingw/public_html/live_resources/live/js/all1.js', $c_js['frontend']);

$js['core_defer'] = $tpt_vars['db']['handler']->getData($tpt_vars, 'tpt_html_js', '*', '`core`=1 AND `defer`=1 ORDER BY `id`', 'id', false );
$c_js['core_defer'] = '';
foreach($js['core_defer'] as $id=>$c) {
	$c_js['core_defer'] .= $c['content']."\n";
}
file_put_contents('/home/amazingw/public_html/live_resources/live/js/all2_unpack.js', $c_js['core_defer']);
//$c_js['core_defer'] = preg_replace('/(\{|\}|;)[\s]+([a-zA-Z]|\{|\}|;)/', '$1$2', $c_js['core_defer']);
//$c_js['core_defer'] = JShrink::minify($c_js['core_defer']);
file_put_contents('/home/amazingw/public_html/live_resources/live/js/all2.js', $c_js['core_defer']);

$js['frontend_defer'] = $tpt_vars['db']['handler']->getData($tpt_vars, 'tpt_html_js', '*', '`frontend`=1 AND `defer`=1 ORDER BY `id`', 'id', false );
$c_js['frontend_defer'] = '';
foreach($js['frontend_defer'] as $id=>$c) {
	$c_js['frontend_defer'] .= $c['content']."\n";
}
file_put_contents('/home/amazingw/public_html/live_resources/live/js/all3_unpack.js', $c_js['frontend_defer']);
//$c_js['frontend_defer'] = preg_replace('/(\{|\}|;)[\s]+([a-zA-Z]|\{|\}|;)/', '$1$2', $c_js['frontend_defer']);
//$c_js['frontend_defer'] = JShrink::minify($c_js['frontend_defer']);
file_put_contents('/home/amazingw/public_html/live_resources/live/js/all3.js', $c_js['frontend_defer']);
*/

//tpt_template::rebuildHeadContent($tpt_vars);

/*
$input = $_GET;
$url = '';
$furl = '';
$search_text = '';
$fsearch_text = '';
if(!empty($input['submit'])) {
	if(!empty($input['url'])) {
		$url = $input['url'];
		$furl = htmlspecialchars($url);
	}

	if(!empty($input['search_text'])) {
		//tpt_dump($input['search_text'], true);
		$search_text = $input['search_text'];
		$fsearch_text = htmlspecialchars($search_text);
	}

	$results = '';

	//$valdata = 'cmd=_notify-validate&'.$postdata;
	//curl_setopt($c, CURLOPT_HTTPHEADER, array());
	//$vrfres = curl_exec($c);
	//curl_close($c);
	$result = sendSingleRequest($url);

	//$verified = strtolower(trim($result['body']));
	$response = $result['body'];
*/

if(false) {
	preg_match_all('#<link[^>]+href="(.*?)"[^>]*/?>#i', $response, $link, PREG_SET_ORDER);
	preg_match_all('#<a[^>]+href="(.*?)"[^>]*>#i', $response, $a, PREG_SET_ORDER);
	preg_match_all('#<script[^>]+src=".*?"[^>]*>#i', $response, $script, PREG_SET_ORDER);
	preg_match_all('#<img[^>]+src=".*?"[^>]*/?>#i', $response, $img, PREG_SET_ORDER);
}


	//preg_match_all('#<link[^>]+href=".*?"[^>]*/>#i', $response, $mtch, PREG_SET_ORDER);
	//preg_match_all($search_text, $response, $mtch, PREG_SET_ORDER);

/*
	$urls = array();
	foreach($a as $m) {
		if(preg_match('#https://www\.amazingwristbands\.com/.+#i', $m[1])) {
			$urls[] = $m[1];
		}
	}

	$url_count = count($urls);
	$urls = implode("\n", $urls);
	$query = 'INSERT INTO `tpt_crawler` (`url`, `search_text`, `urls`, `url_count`, `homepage_url_count`) VALUES("'.mysql_real_escape_string($url).'", "'.mysql_real_escape_string($search_text).'", "'.mysql_real_escape_string($urls).'", '.$url_count.', '.$url_count.')';
	$tpt_vars['db']['handler']->query($query);
	$id = $tpt_vars['db']['handler']->last_id();
*/
	//tpt_dump($id);

	/*

	$results .= <<< EOT
<div class="padding-10">
	<div style="border: 1px solid #000;">
		<div class="font-weight-bold font-size-20 text-align-left">
			&lt;link&gt;
		</div>
EOT;
	foreach($link as $m) {
		$match = htmlspecialchars($m[0]);
		$results .= <<< EOT
<div>
$match
</div>

EOT;
	}
	$results .= <<< EOT
	</div>
</div>
EOT;


	$results .= <<< EOT
<div class="padding-10">
	<div style="border: 1px solid #000;">
		<div class="font-weight-bold font-size-20 text-align-left">
			&lt;a&gt;
		</div>
EOT;
	foreach($a as $m) {
		$match = htmlspecialchars($m[0]);
		$results .= <<< EOT
<div>
$match
</div>

EOT;
	}
	$results .= <<< EOT
	</div>
</div>
EOT;


	$results .= <<< EOT
<div class="padding-10">
	<div style="border: 1px solid #000;">
		<div class="font-weight-bold font-size-20 text-align-left">
			&lt;script&gt;
		</div>
EOT;
	foreach($script as $m) {
		$match = htmlspecialchars($m[0]);
		$results .= <<< EOT
<div>
$match
</div>

EOT;
	}
	$results .= <<< EOT
	</div>
</div>
EOT;


	$results .= <<< EOT
<div class="padding-10">
	<div style="border: 1px solid #000;">
		<div class="font-weight-bold font-size-20 text-align-left">
			&lt;img&gt;
		</div>
EOT;
	foreach($img as $m) {
		$match = htmlspecialchars($m[0]);
		$results .= <<< EOT
<div>
$match
</div>

EOT;
	}
	$results .= <<< EOT
	</div>
</div>
EOT;

	*/

/*
	$results = <<< EOT
<script type="text/javascript">
//<![CDATA[
var crawler_id = $id;
goGetSome('crawler.crawl', window);
//]]>
</script>
EOT;

}

$action_url = $tpt_vars['url']['handler']->wrap($tpt_vars, $tpt_vars['config']['requesturl']);

$tpt_vars['template']['content'] = <<< EOT
<form method="GET" accept-encoding="utf8" action="$action_url">
	<div class="text-align-right display-inline-block">
		<div class="height-20 line-height-20">
			URL:&nbsp;
		</div>
		<div class="height-20 line-height-20">
			Search Text:&nbsp;
		</div>
	</div>
	<div class="text-align-right display-inline-block">
		<div class="height-20 line-height-20">
			<input type="text" name="url" value="$furl" class="width-500" />
		</div>
		<div class="height-20 line-height-20">
			<input type="text" name="search_text" value="$fsearch_text" class="width-500" />
		</div>
	</div>
	<br />
	<input type="submit" name="submit" value="Submit" />
</form>

<div id="results">
$results
</div>
EOT;
*/