<?php
/**
 * Elgg Podcasts Custom Episode List View
 * - Modified version of page/components/list
 *
 * @package Podcasts
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2013
 * @link http://www.thinkglobalschool.com/
 *
 * @uses $vars['items']       Array of ElggEntity
 * @uses $vars['offset']      Index of the first list item in complete list
 * @uses $vars['limit']       Number of items per page. Only used as input to pagination.
 * @uses $vars['count']       Number of items in the complete list
 * @uses $vars['base_url']    Base URL of list (optional)
 * @uses $vars['pagination']  Show pagination? (default: true)
 * @uses $vars['position']    Position of the pagination: before, after, or both
 * @uses $vars['full_view']   Show the full view of the items (default: false)
 * @uses $vars['list_class']  Additional CSS class for the <ul> element
 * @uses $vars['item_class']  Additional CSS class for the <li> elements
 */

$items = $vars['items'];
$offset = elgg_extract('offset', $vars);
$limit = elgg_extract('limit', $vars);
$count = elgg_extract('count', $vars);
$base_url = elgg_extract('base_url', $vars, '');
$pagination = elgg_extract('pagination', $vars, true);
$offset_key = elgg_extract('offset_key', $vars, 'offset');
$position = elgg_extract('position', $vars, 'after');

if (isset($vars['list_class'])) {
	$list_class = $vars['list_class'];
}

if (isset($vars['item_class'])) {
	$item_class = $vars['item_class'];
}

$html = "";
$nav = "";

if ($pagination && $count) {
	$nav .= elgg_view('navigation/pagination', array(
		'base_url' => $base_url,
		'offset' => $offset,
		'count' => $count,
		'limit' => $limit,
		'offset_key' => $offset_key,
	));
}

if (is_array($items) && count($items) > 0) {
	$html .= "<ul class=\"$list_class\">";
	foreach ($items as $item) {
		if (elgg_instanceof($item, 'user') || elgg_instanceof($item, 'group')) {
			$li = elgg_view('podcasts/podcast', array('entity' => $item));
		} else {
			$li = elgg_view_list_item($item, $vars);
		}
		if ($li) {
			if (elgg_instanceof($item)) {
				$id = "elgg-{$item->getType()}-{$item->getGUID()}";
			} else {
				$id = "item-{$item->getType()}-{$item->id}";
			}
			$html .= "<li id=\"$id\" class=\"$item_class\">$li</li>";
		}
	}
	$html .= '</ul>';
}

if ($position == 'before' || $position == 'both') {
	$html = $nav . $html;
}

if ($position == 'after' || $position == 'both') {
	$html .= $nav;
}

echo $html;
