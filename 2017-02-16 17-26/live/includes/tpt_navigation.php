<?php

defined('TPT_INIT') or die('access denied');

class tpt_navigation {
	static function getMenuItemHTML(&$vars, &$items, $active, &$active_parents, $parentid=0, $level=0, $client=0, &$classes=array(), $parent_item_html_appendix='') {
		//global $tpt_vars;
		$code = '';
		if(is_array($items[$parentid])) {
			$code .= '<ul class="padding-0 margin-0 navigation level'.$level.'" style="list-style: none;">';
			foreach($items[$parentid] as $item) {
				$itemStyle = '';
				$itemClass = 'navigation-item';
				$itemClass .= $item['class_suffix'];

				$hasChildren = false;
				if(isset($items[$item['id']]) && is_array($items[$item['id']])) {
					$hasChildren = true;
					$itemClass .= ' navigation-parent';
				}

				$isSeparator = false;
				if($item['name']=='-- separator --') {
					$isSeparator = true;
					$itemClass .= ' navigation-separator';
				} else {
					//$itemClass .= ' hoverCB';
					//$itemStyle = 'border: 1px solid #D08600;';
				}

				if(in_array($item['id'], $active_parents)) {
					$itemClass .= ' navigation-active-parent';
				} else if($item['id'] == $active) {
					$itemClass .= ' navigation-active';
				}

				$href = '';
				if(!empty($item['link_type'])) {
					if(($item['link_type'] == 1)) {
						$href = $vars['url']['handler']->wrap($vars, $item['href'], true, $client);
					} else if(($item['link_type'] == 2)) {
						$href = $item['href'];
					}
				} else {
					$href = $vars['url']['handler']->wrap($vars, $item['href'], true);
				}

				$a_attribs = $item['a_attribs'];

				$nm = ($item['name']=='Dual Layer');


				$code .= '<li class="position-relative '.$itemClass.'" style="'.$itemStyle.'">';
				if(!$isSeparator) {
					if (($item['name']=='No Minimum Quantity') || ($item['name']=='Rush Order Bands'))
						$code .= '<a '.$a_attribs.' href="'.$href.'" class="display-inline-block navigation-link" style="" id="item'.$item['id'].'" title="'.str_replace('"', '&quot;', $item['title']).'">'.$item['name'].($hasChildren?$parent_item_html_appendix:'').'</a>';
					else
						$code .= '<a '.$a_attribs.' href="'.$href.'" class="display-inline-block navigation-link" style="" id="item'.$item['id'].'" title="'.str_replace('"', '&quot;', $item['title']).'">'.$item['name'].($hasChildren?$parent_item_html_appendix:'').'</a>';
					if($hasChildren) {
						$code .= self::getMenuItemHTML($vars, $items, $active, $active_parents, $item['id'], $level+1, $client, $classes, $parent_item_html_appendix);
					}
				}

				//    if ($item['name']=='Debossed'||$item['name']=='Dual Layer') {
				$code .= '</li>';
			}
			$code .= '</ul>';
		}

		return $code;
	}

	static function getMenuHTML(&$vars, $table, $client=0, $classes=array(), $parent_item_html_appendix='') {
		$items = array();
		$caccess = !empty($vars['user']['data']['access_level'])?intval($vars['user']['data']['access_level'], 10):0;

		$query = 'SELECT * FROM `'.$table.'` WHERE `enabled`=1 AND `access_level`<='.$caccess.' ORDER BY `order` ASC';
		$vars['db']['handler']->query($query);

		$items['parent_id'] = $vars['db']['handler']->fetch_assoc_list('parent_id', true);
		$items['href'] = $vars['db']['handler']->fetch_assoc_list('href', false);
		$items['id'] = $vars['db']['handler']->fetch_assoc_list('id', false);

		$items['active'] = 0;

		if(isset($items['href'][$vars['url']['upath']])) {
			$items['active'] = $items['href'][$vars['url']['upath']]['id'];
		}

		$items['active_parents'] = array();
		if($items['active']) {
			$mitem = $items['id'][$items['active']];
			while(isset($items['id'][$mitem['parent_id']])) {
				$mitem = $items['id'][$mitem['parent_id']];
				$items['active_parents'][] = $mitem['id'];
			}
		}

		$navigation = self::getMenuItemHTML($vars, $items['parent_id'], $items['active'], $items['active_parents'], 0, 0, $client, $classes, $parent_item_html_appendix);

		//tpt_dump($navigation, true);
		return $navigation;
	}
}