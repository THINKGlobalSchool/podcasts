<?php
/**
 * Elgg Podcasts Base Settings Form
 *
 * @package Podcasts
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2013
 * @link http://www.thinkglobalschool.com/
 *
 */

// Get Entity
$entity = elgg_extract('entity', $vars);

// Values w/ defaults
$values = array(
	'title' => elgg_get_config('sitename') . ": " . elgg_echo('podcasts:title:owner_podcasts', array($entity->name)),
	'description' => elgg_echo('podcasts:feed:description', array($entity->name)),
	'copyright' => "&#169; " . elgg_get_site_entity()->name . " " . date('Y', time()),
	'language' => elgg_get_plugin_setting('podcasts_language', 'podcasts'),
	'subtitle' => '',
	'categories' => ''
);

foreach ($values as $k => $v) {
	$values[$k] = elgg_extract($k, $vars, $v, false);
}

// Check for sticky form
if (elgg_is_sticky_form('podcast-settings')) {
	$sticky_values = elgg_get_sticky_values('podcast-settings');
	foreach ($sticky_values as $key => $value) {
		$values[$key] = $value;
	}
}

extract($values);
 
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

$categories_label = elgg_echo('podcasts:categories');
$categories_input = elgg_view('input/tags', array(
	'name' => 'categories',
	'id' => 'podcast-categories',
	'value' => $categories
));

$language_label = elgg_echo('podcasts:language');
$language_input = elgg_view('input/text', array(
	'name' => 'language',
	'id' => 'podcast-language',
	'value' => $language
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

$guid_input = elgg_view('input/hidden', array(
	'name' => 'guid', 
	'value' => $entity->guid,
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
		<label for="podcast-description">$description_label</label>
		$description_input
	</div>
	<div>
		<label for="podcast-categories">$categories_label</label>
		$categories_input
	</div>
	<div>
		<label for="podcast-language">$language_label</label>
		$language_input
	</div>
	<div>
		<label for="podcast-copyright">$copyright_label</label>
		$copyright_input
	</div>
	<div class='elgg-foot'>
		$save_input
		$guid_input
	</div>
HTML;

echo $content;