<?php
/**
 * Elgg Podcasts Sidebar view
 *
 * @package Podcasts
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2013
 * @link http://www.thinkglobalschool.com/
 *
 */

$page_owner = elgg_get_page_owner_entity();
$podcast_author = $page_owner->name;

// Group Podcast Info
if (elgg_instanceof($page_owner, 'group')) {
	$group_podcast_settings = unserialize($page_owner->podcast_settings);

	if (is_array($group_podcast_settings)) {
		$podcast_title = $group_podcast_settings['title'];
		$podcast_subtitle = $group_podcast_settings['subtitle'];
		$podcast_description = $group_podcast_settings['description'];
	}

	$podcast_settings_url = "groups/edit/{$page_owner->guid}#other";
} 
// User Podcast info
else if (elgg_instanceof($page_owner, 'user')) {
	$podcast_title = elgg_get_plugin_user_setting('podcast_title', $page_owner->guid, 'podcasts');
	$podcast_subtitle = elgg_get_plugin_user_setting('podcast_subtitle', $page_owner->guid, 'podcasts');
	$podcast_description = elgg_get_plugin_user_setting('podcast_description', $page_owner->guid, 'podcasts');

	$podcast_settings_url = "podcasts/settings/{$page_owner->username}";
}

$body .= $podcast_subtitle ? "<div class='elgg-subtext'>{$podcast_subtitle}</div>" : '';
$body .= $podcast_description ? "<div class='elgg-output'>{$podcast_description}</div>" : '';

if ($body) {
	echo elgg_view_module('aside', $podcast_title, $body);
}

if ($page_owner && $page_owner->canEdit()) {
	$params = array(
		'name' => 'podcasts_settings',
		'text' => elgg_echo('podcasts:editpodcastsettings'),
		'href' => $podcast_settings_url,
	);
	elgg_register_menu_item('page', $params);
}
