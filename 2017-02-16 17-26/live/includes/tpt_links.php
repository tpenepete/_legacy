<?php

$query = 'SELECT `href` FROM `tpt_links`';
$tpt_vars['db']['handler']->query($query);
$tpt_links = $tpt_vars['db']['handler']->fetch_assoc_list('href', false);
if(is_array($tpt_links)) {
    foreach($tpt_links as $key=>$link) {
        $tpt_links[$key] = $link['href'];
    }
    $tpt_vars['template_data']['links'] = array_merge($tpt_links, $tpt_vars['template_data']['links']);
}

