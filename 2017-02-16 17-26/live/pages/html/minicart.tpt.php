<?php

defined('TPT_INIT') or die('access denied');

//var_dump(get_included_files());
//if(!in_array('amzg-process.php', get_included_files()))
/*
include(TPT_PAGES_DIR.DIRECTORY_SEPARATOR.'amzg-process.php');
$cart = new shoppingCart();
$products = $cart->get_products();
$prCount = count($products);
$sub_total = 0;
for ($i=0, $n=count($products); $i<$n; $i++) {
    $item_total = $products[$i]['quantity'] * $products[$i]['price'];
    $sub_total += $products[$i]['price']; 
}
 
$sub_total = '&#36;'.number_format($sub_total, 2);
*/

$prCount = amz_cart::$totals['products_count'];
$sub_total = amz_cart::$totals['pricing']['html']['customer_price'];

$bands_url = $tpt_vars['url']['handler']->wrap($tpt_vars, '/holiday-designs/merry-christmas-limited-edition-atc');
$basket_url = $tpt_vars['url']['handler']->wrap($tpt_vars, '/your-basket');

// master template
echo <<< EOT
<div class="color-white line-height-17" style="">
    <div class="" style="">
        <a href="$basket_url" title="View Added Products">Your Basket</a>:
    </div>
EOT;
if($prCount>0){
echo <<< EOT
    <div class="" style="">
        <a href="$basket_url" title="View Added Products">($prCount Products)</a>
    </div>
    <div class="" style="">
        <a href="$basket_url" title="View Added Products">($sub_total)</a>
    </div>
EOT;
} else {
echo <<< EOT
    <div class="font-style-italic" style="">
        (empty)
    </div>
    <!--a class="font-size-10" href="$bands_url" title="Create Your Custom Design Now!">Choose Your Bands</a-->
EOT;
}

echo <<< EOT
</div>
EOT;

