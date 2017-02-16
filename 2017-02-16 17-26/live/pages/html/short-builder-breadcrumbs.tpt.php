<?php

defined('TPT_INIT') or die('access denied');

$breadcrumb_url = self_page_URL();
$builder_breadcrumb = $builder['breadcrumb'];

$html = <<< EOT
<div class="spacer1 clearBoth">
	<ul itemscope="" itemtype="http://schema.org/BreadcrumbList" class="article-nav">
		<li itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem" class="first">
			<a itemprop="item" href="$tpt_rooturl">
				<span itemprop="name" >Home</span>
			</a>
			<meta itemprop="position" content="1">
		</li>
		<li itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem" >
				<span itemprop="name" >$builder_breadcrumb</span>
			<meta itemprop="position" content="2">
		</li>
	</ul>
</div>
EOT;
