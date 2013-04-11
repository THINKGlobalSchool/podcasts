<?php
/**
 * Elgg Podcasts Save Form
 *
 * @package Podcasts
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2013
 * @link http://www.thinkglobalschool.com/
 *
 * @todo need to check file types.. in action and in js
 */

$podcast = get_entity($vars['guid']);
$vars['entity'] = $podcast;

$draft_warning = $vars['draft_warning'];
if ($draft_warning) {
	$draft_warning = '<span class="mbm elgg-text-help">' . $draft_warning . '</span>';
}

// Set up buttons
$action_buttons = '';
$delete_link = '';

if ($vars['guid']) {
	// Add a delete button if editing
	$delete_url = "action/podcasts/delete?guid={$vars['guid']}";
	$delete_link = elgg_view('output/confirmlink', array(
		'href' => $delete_url,
		'text' => elgg_echo('delete'),
		'class' => 'elgg-button elgg-button-delete float-alt'
	));
	$file_label = elgg_echo("podcasts:replacefile");
} else {
	$file_label = elgg_echo("podcasts:selectfile");
}

$save_button = elgg_view('input/submit', array(
	'value' => elgg_echo('save'),
	'name' => 'save',
));

$action_buttons = $save_button . $delete_link;

// Labels/Inputs
$title_label = elgg_echo('title');
$title_input = elgg_view('input/text', array(
	'name' => 'title',
	'id' => 'podcast-title',
	'value' => $vars['title']
));

$description_label = elgg_echo('description');
$description_input = elgg_view('input/longtext', array(
	'name' => 'description',
	'id' => 'podcast-description',
	'value' => $vars['description']
));

$file_help = elgg_echo('podcasts:filehelp');
$file_input = elgg_view('input/file', array(
	'name' => 'upload',
	'id' => 'podcast-file',
	'accept' => 'audio/*'
));

$tags_label = elgg_echo('tags');
$tags_input = elgg_view('input/tags', array(
	'name' => 'tags',
	'id' => 'podcast-tags',
	'value' => $vars['tags']
));

$access_label = elgg_echo('access');
$access_input = elgg_view('input/access', array(
	'name' => 'access_id',
	'id' => 'podcast-access-id',
	'value' => $vars['access_id']
));

// Categories
$categories_input = elgg_view('input/categories', $vars);

// Hidden guid inputs
$container_guid_input = elgg_view('input/hidden', array('name' => 'container_guid', 'value' => elgg_get_page_owner_guid()));
$guid_input = elgg_view('input/hidden', array('name' => 'guid', 'value' => $vars['guid']));

// Content
$content = <<<HTML
	<div>
		<label for="podcast-title">$title_label</label>
		$title_input
	</div>
	<div>
		<label for="podcast-description">$description_label</label>
		$description_input
	</div>
	<div>
		<label for="podcast-file">$file_label</label><span class='elgg-text-help'>$file_help</span>
		$file_input
	</div>
	<div>
		<label for="podcast-tags">$tags_label</label>
		$tags_input
	</div>
	$categories_input
	<div>
		<label for="podcast-access-id">$access_label</label>
		$access_input
	</div>
	<div class="elgg-foot">
		$guid_input
		$container_guid_input

		$action_buttons
	</div>
HTML;

echo $content;
