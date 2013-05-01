<?php
/**
 * Elgg Podcasts Save Usersettings Form
 *
 * @package Podcasts
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2013
 * @link http://www.thinkglobalschool.com/
 *
 */

$user = elgg_extract('user', $vars, elgg_get_logged_in_user_entity());

$title = elgg_get_plugin_user_setting('podcast_title', $user->guid, 'podcasts');
$subtitle = elgg_get_plugin_user_setting('podcast_subtitle', $user->guid, 'podcasts');
$description = elgg_get_plugin_user_setting('podcast_description', $user->guid, 'podcasts');
$language = elgg_get_plugin_user_setting('podcast_language', $user->guid, 'podcasts');
$categories = elgg_get_plugin_user_setting('podcast_categories', $user->guid, 'podcasts');
$copyright = elgg_get_plugin_user_setting('podcast_copyright', $user->guid, 'podcasts');

// Output base seeings form
echo elgg_view('forms/podcasts/settings', array(
	'entity' => $user,
	'title' => $title,
	'description' => $description,
	'subtitle' => $subtitle,
	'language' => $language,
	'categories' => $categories,
	'copyright' => $copyright
));