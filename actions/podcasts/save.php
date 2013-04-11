<?php
/**
 * Elgg Podcasts Save Action
 *
 * @package Podcasts
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2013
 * @link http://www.thinkglobalschool.com/
 *
 */

// Get guid for editing
$guid = get_input('guid');

// Set up sticky form
elgg_make_sticky_form('podcast');

if ($guid) {
	$podcast = get_entity($guid);
	if (!elgg_instanceof($podcast, 'object', 'podcast') || !$podcast->canEdit()) {
		register_error(elgg_echo('podcasts:error:notfound'));
		forward(get_input('forward', REFERER));
	}
} else {
	$podcast = new ElggPodcast();
}

// Check required fields
$required = array('title', 'description');

$error = FALSE;
foreach ($required as $field) {
	$value = get_input($field);
	if (empty($value)) {
		register_error(elgg_echo("podcasts:error:missing:{$field}"));
		$error = TRUE;
	} else {
		$podcast->$field = $value;
	}
}

// Get/check container guid
$container_guid = (int)get_input('container_guid', elgg_get_logged_in_user_guid());
if (!can_write_to_container(elgg_get_logged_in_user_guid(), $container_guid)) {
	register_error(elgg_echo('podcasts:error:edit'));
	$error = TRUE;
}

// There was an error
if ($error) {
	forward(REFERER);
}

$access_id = (int)get_input('access_id', ACCESS_DEFAULT);
$tags = string_to_tag_array(get_input('tags'));

$podcast->tags = $tags;
$podcast->access_id = $access_id;
$podcast->container_guid = $container_guid;

// Try to save
if ($podcast->save()) {
	// Clear sticky form 
	elgg_clear_sticky_form('podcast');

	system_message(elgg_echo('podcasts:success:save'));

	// Add to river if this is a new podcast
	if (!$guid) {
		// River item
		add_to_river('river/object/podcast/create', 'create', $podcast->owner_guid, $podcast->getGUID());
	}
	forward($podcast->getURL());
} else {
	register_error(elgg_echo('podcasts:error:save'));
	forward(REFERER);
}

