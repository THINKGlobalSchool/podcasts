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

$user = elgg_get_logged_in_user_entity();

$title = elgg_get_plugin_user_setting('podcast_title', $user->guid, 'podcasts');
$subtitle = elgg_get_plugin_user_setting('podcast_subtitle', $user->guid, 'podcasts');
$description = elgg_get_plugin_user_setting('podcast_description', $user->guid, 'podcasts');
$categories = elgg_get_plugin_user_setting('podcast_categories', $user->guid, 'podcasts');
$copyright = elgg_get_plugin_user_setting('podcast_copyright', $user->guid, 'podcasts');

// Set title if empty
if (empty($title)) {
	$title =  elgg_get_config('sitename') . ": " . elgg_echo('podcasts:title:owner_podcasts', array($user->name));
}

// Set description if empty
if (empty($description)) {
	$description = elgg_echo('podcasts:feed:description', array($user->name));
}

// Set copyright if empty
if (empty($copyright)) {
	$copyright = "&#169; " . elgg_get_site_entity()->name . " " . date('Y', time());
}

// Labels/Inputs
$title_label = elgg_echo('title');
$title_input = elgg_view('input/text', array(
	'name' => 'title',
	'id' => 'podcast-title',
	'value' => $title
));

$subtitle_label = elgg_echo('podcasts:subtitle');
$subtitle_input = elgg_view('input/text', array(
	'name' => 'subtitle',
	'id' => 'podcast-subtitle',
	'value' => $subtitle
));

$description_label = elgg_echo('description');
$description_input = elgg_view('input/plaintext', array(
	'name' => 'description',
	'id' => 'podcast-description',
	'value' => $description
));

$categories_label = elgg_echo('description');
$categories_input = elgg_view('input/tags', array(
	'name' => 'categories',
	'id' => 'podcast-categories',
	'value' => $categories
));

$copyright_label = elgg_echo('podcasts:copyright');
$copyright_input = elgg_view('input/text', array(
	'name' => 'copyright',
	'id' => 'podcast-copyright',
	'value' => $copyright
));

$save_input = elgg_view('input/submit', array(
	'name' => 'save',
	'value' => elgg_echo('save'),
));

$content = <<<HTML
	<div>
		<label for="podcast-title">$title_label</label>
		$title_input
	</div>
	<div>
		<label for="podcast-subtitle">$subtitle_label</label>
		$subtitle_input
	</div>
	<div>
		<label for="podcast-title">$description_label</label>
		$description_input
	</div>
	<div>
		<label for="podcast-title">$categories_label</label>
		$categories_input
	</div>
	<div>
		<label for="podcast-title">$copyright_label</label>
		$copyright_input
	</div>
	<div class='elgg-foot'>
		$save_input
	</div>
HTML;

echo $content;