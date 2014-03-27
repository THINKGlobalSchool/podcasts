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
 */

// Load JS
elgg_load_js('elgg.podcasts.uploader');
elgg_load_js('jquery.ui.widget');
elgg_load_js('jquery-file-upload');
elgg_load_js('jquery.iframe-transport');

// Css
elgg_load_css('elgg.jquery.ui');

$podcast = get_entity($vars['guid']);
$vars['entity'] = $podcast;

$draft_warning = $vars['draft_warning'];
if ($draft_warning) {
	$draft_warning = '<span class="mbm elgg-text-help">' . $draft_warning . '</span>';
}

// Set up buttons
$action_buttons = '';
$delete_link = '';

// Dropzone label
$file_drop = elgg_echo('podcasts:filedrop');
$file_help = elgg_echo('podcasts:help:file');

// Toggle basic uploader
$toggle_uploader = elgg_view('output/url', array(
	'text' => elgg_echo('podcasts:showbasicuploader'),
	'href' => '#',
	'class' => 'podcasts-toggle-uploader elgg-text-help'
));

if ($vars['guid']) {
	// Add a delete button if editing
	$delete_url = "action/podcasts/delete?guid={$vars['guid']}";
	$delete_link = elgg_view('output/confirmlink', array(
		'href' => $delete_url,
		'text' => elgg_echo('delete'),
		'class' => 'elgg-button elgg-button-delete float-alt'
	));
	$file_label = elgg_echo("podcasts:replacefile");

	$podcastname = $podcast->originalfilename;
	$podcastsize = podcasts_friendly_filesize($podcast->size());
	$replace = elgg_echo('podcasts:replace', array($file_help));

	$dropzone = <<<HTML
		<span class='podcast-file-name'>$podcastname</span>
		<span class='podcast-file-size'>$podcastsize</span>
		<span class='podcast-file-replace'>$replace</span>
HTML;
} else {
	$dropzone = <<<HTML
		<span class='podcast-drop'>$file_drop ($file_help)</span>
HTML;
	$file_label = elgg_echo("podcasts:selectfile");
}

$save_button = elgg_view('input/submit', array(
	'value' => elgg_echo('save'),
	'id' => 'podcast-save-button',
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

$file_input = elgg_view('input/file', array(
	'name' => 'upload',
	'id' => 'podcast-file',
	'accept' => 'audio/*',
	'class' => 'hidden'
));

$tags_label = elgg_echo('tags');
$tags_input = elgg_view('input/tags', array(
	'name' => 'tags',
	'id' => 'podcast-tags',
	'value' => $vars['tags']
));

$access_help_link = elgg_view('output/url', array(
	'text' => elgg_echo('podcasts:help:accesslink'),
	'rel' => 'popup',
	'href' => '#podcasts-access-help',
	'class' => 'elgg-text-help',
));

$access_help_content = elgg_view_module('popup', elgg_echo('podcasts:help:accesstitle'), elgg_echo('podcasts:help:accesscontent'), array(
	'class' => 'hidden elgg-podcasts-help-module',
	'id' => 'podcasts-access-help'
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
		<div id='podcast-dropzone'>
			$dropzone
		</div>
		<span id='podcast-basic-uploader' class='hidden'>
			<label for="podcast-file">$file_label</label>&nbsp;<span class='elgg-text-help'>($file_help)</span>
		</span>
		$file_input
		$toggle_uploader
	</div>
	<div>
		<label for="podcast-tags">$tags_label</label>
		$tags_input
	</div>
	$categories_input
	<div>
		<label for="podcast-access-id">$access_label</label>
		$access_input $access_help_link
		$access_help_content
	</div>
	<div class="elgg-foot">
		$guid_input
		$container_guid_input
		$action_buttons
	</div>
	<div id='podcast-upload-dialog'>
		<div id='podcast-upload-progress'></div>
	</div>
HTML;

echo $content;
