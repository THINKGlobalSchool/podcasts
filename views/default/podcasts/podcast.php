<?php
/**
 * Elgg Podcasts User/Group Podcast View
 * - Modified version of default/user view
 *
 * @package Podcasts
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2013
 * @link http://www.thinkglobalschool.com/
 *
 * @uses $vars['entity']
 */

$entity = elgg_extract('entity', $vars);



// Tweak: allow icon size to be set via input
$size = elgg_extract('size', $vars, get_input('user_gallery_size', 'tiny'));

$icon = elgg_view_entity_icon($entity, $size, $vars);

if (elgg_instanceof($entity, 'group')) {
	$group_podcast_settings = unserialize($entity->podcast_settings);
	if (is_array($group_podcast_settings)) {
		$title = $group_podcast_settings['title'];
		$subtitle = $group_podcast_settings['subtitle'];
	}
	$url = "group/{$entity->guid}/all";
} else {
	$title = elgg_get_plugin_user_setting('podcast_title', $entity->guid, 'podcasts');
	$subtitle = elgg_get_plugin_user_setting('podcast_subtitle', $entity->guid, 'podcasts');
	$url = "owner/{$entity->username}";
}

if (empty($title)) {
	$title = elgg_echo('podcasts:title:owner_podcasts', array($entity->name));
}

$title = "<h3 class='elgg-podcast-title'><a href=\"" . $url  . "\">" . $title . "</a></h3>";

$metadata = elgg_view_menu('entity', array(
	'entity' => $entity,
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz',
	'blah' => 'asdsadsad'
));

if (elgg_in_context('widgets')) {
	$metadata = '';
}
if (elgg_get_context() == 'gallery') {
	// @todo gallery?
} else {
	$params = array(
		'entity' => $entity,
		'title' => $title,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
		'content' => '',
	);

	$list_body = elgg_view('user/elements/summary', $params);

	echo elgg_view_image_block($icon, $list_body, $vars);
}