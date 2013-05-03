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

$error = FALSE;

if ($guid) {
	$podcast = get_entity($guid);
	if (!elgg_instanceof($podcast, 'object', 'podcast') || !$podcast->canEdit()) {
		register_error(elgg_echo('podcasts:error:notfound'));
		forward(get_input('forward', REFERER));
	}
	$new_podcast = FALSE;
} else {
	$podcast = new ElggPodcast();

	// New podcasts require a file
	if (empty($_FILES['upload']['name'])) {
		$error = TRUE;
		register_error(elgg_echo('podcasts:error:missing:file'));
	}
	$new_podcast = TRUE;
}

// Check required fields
$required = array('title', 'description');
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

// We have a new/replacement file for this podcast
if (isset($_FILES['upload']['name']) && !empty($_FILES['upload']['name'])) {
	$file = $_FILES['upload'];
}

// Try saving
try {
	$result = $podcast->save($file);
	if ($result) {	
		// Clear sticky form 
		elgg_clear_sticky_form('podcast');

		system_message(elgg_echo('podcasts:success:save'));

		// Add to river if this is a new podcast
		if (!$guid) {
			// River item
			add_to_river('river/object/podcast/create', 'create', $podcast->owner_guid, $podcast->getGUID());
		}

		$fwd = $podcast->getURL();
	} else {
		register_error(elgg_echo('podcasts:error:save'));
	}
} catch (Exception $ex) {
	// At this point the podcast entity could have been saved.. clean it up we don't want missing metadata
	if ($new_podcast && $podcast->guid) { // check first..
		$podcast->delete();
	}
	register_error($ex->getMessage());
}

if (!$fwd) {
	$fwd = REFERER;
}

// Check XHR
if (elgg_is_xhr()) {
	echo $fwd;
}
forward($fwd);
