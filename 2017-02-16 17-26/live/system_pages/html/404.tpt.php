<?php
defined('TPT_INIT') or die('access denied');
$base_url = BASE_URL;
$tpt_vars['template_data']['head'][] = <<< EOT
<style>
body, body>div {
}
.main-wrap {

}
</style>
EOT;

$tpt_vars['template']['content'] = <<< EOT
    <ul itemscope="" itemtype="http://schema.org/BreadcrumbList" class="article-nav clear">
        <li itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem" class="first">
            <a itemprop="item" href="$base_url">
                 <span itemprop="name">Home</span>
             </a>
             <meta itemprop="position" content="1">
        </li>
        <li itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
            <span itemprop="name">Not found</span>
            <meta itemprop="position" content="2">
        </li>           
    </ul> 
<div class="spacer4"></div>
<h1 class="product-title font-size-32">Sorry, this page cannot be found.</h2>
<div class="spacer3"></div>
<div class="spacer1"></div>
<div class="text-align-center banner1_brown_txt font-size-27 float-none">The page you are looking for does not exist.
<br />
<a title="Amazing Wristbands Sitemap" href="$base_url/sitemap">Sitemap</a> | <a title="Amazing Wristbands Home" href="$base_url">Home</a>
</div>
EOT;


