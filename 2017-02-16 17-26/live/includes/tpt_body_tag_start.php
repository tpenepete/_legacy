<?php
defined('TPT_INIT') or die('access denied');

/*
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-M82FT9" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
*/

if(!empty($tpt_vars['environment']['page_rule']['google_tag_manager']) && empty($tpt_vars['environment']['is404']) && empty($tpt_vars['environment']['force404']) && (empty($_GET) || $tpt_vars['config']['seo']['google']['tag_manager']['has_allowed_param'])) {
	$tpt_vars['template_data']['body_tag_start']['content']['google_tag_manager'] = <<< EOT
EOT;
}
