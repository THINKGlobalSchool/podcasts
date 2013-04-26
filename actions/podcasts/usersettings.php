<?php
/**
 * Elgg Podcasts Save Usersettings Action
 *
 * @package Podcasts
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2013
 * @link http://www.thinkglobalschool.com/
 *
 */

$user = elgg_get_logged_in_user_entity();

$title = get_input('title');
$subtitle = get_input('subtitle');
$description = get_input('description');
$categories = get_input('categories');
$copyright = get_input('copyright');

if (empty($title) || empty($description) || empty($copyright)) {
	register_error(elgg_echo('podcasts:error:missing'));
	forward(REFERER);
}


elgg_set_plugin_user_setting('podcast_title', $title, $user->guid, 'podcasts');
elgg_set_plugin_user_setting('podcast_subtitle', $subtitle, $user->guid, 'podcasts');
elgg_set_plugin_user_setting('podcast_description', $description, $user->guid, 'podcasts');
elgg_set_plugin_user_setting('podcast_categories', $categories, $user->guid, 'podcasts');
elgg_set_plugin_user_setting('podcast_copyright', $copyright, $user->guid, 'podcasts');


system_message(elgg_echo('podcasts:success:usersettings'));
forward(REFERER);