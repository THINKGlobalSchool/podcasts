<?php
/**
 * Elgg Podcasts Save Group Settings Action
 *
 * @package Podcasts
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2013
 * @link http://www.thinkglobalschool.com/
 *
 */

$guid = get_input('guid');

$group = get_entity($guid);

// Set up sticky form
elgg_make_sticky_form('podcast-settings');

if (!$group->canEdit()) {
	register_error(elgg_echo('profile:noaccess'));
	forward(REFERER);
}

$fwd = "podcasts/group/{$guid}/all";

$title = get_input('title');
$subtitle = get_input('subtitle');
$description = get_input('description');
$categories = get_input('categories');
$copyright = get_input('copyright');
$language = get_input('language');

if (empty($title) || empty($description) || empty($copyright)) {
	register_error(elgg_echo('podcasts:error:missing'));
	forward($fwd);
}

$podcast_settings = unserialize($group->podcast_settings);

if (!$podcast_settings) {
	$podcast_settings = array();
}

$podcast_settings['title'] = $title;
$podcast_settings['subtitle'] = $subtitle;
$podcast_settings['description'] = $description;
$podcast_settings['categories'] = $categories;
$podcast_settings['copyright'] = $copyright;
$podcast_settings['language'] = $language;

$group->podcast_settings = serialize($podcast_settings);

elgg_clear_sticky_form('podcast-settings');

system_message(elgg_echo('podcasts:success:usersettings'));
forward($fwd);