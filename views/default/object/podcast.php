<?php
/**
 * Elgg Podcasts Object View
 *
 * @package Podcasts
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2013
 * @link http://www.thinkglobalschool.com/
 *
 */

$full = elgg_extract('full_view', $vars, FALSE);
$podcast = elgg_extract('entity', $vars, FALSE);

if (!elgg_instanceof($podcast, 'object', 'podcast')) {
	return TRUE;
}

$owner = $podcast->getOwnerEntity();

$owner_url = "podcasts/owner/{$owner->username}";
$owner_name = $owner->name;

$categories = elgg_view('output/categories', $vars);

$owner_icon = elgg_view_entity_icon($owner, 'tiny');
$owner_link = elgg_view('output/url', array(
	'href' => $owner_url,
	'text' => $owner_name,
	'is_trusted' => true,
));

$author_text = elgg_echo('byline', array($owner_link));
$date = elgg_view_friendly_time($podcast->time_created);

$comments_count = $podcast->countComments();

// If theres commments, show the link
if ($comments_count != 0) {
	$text = elgg_echo("comments") . " ($comments_count)";
	$comments_link = elgg_view('output/url', array(
		'href' => $podcast->getURL() . '#podcast-comments',
		'text' => $text,
		'is_trusted' => true,
	));
} else {
	$comments_link = '';
}

$metadata = elgg_view_menu('entity', array(
	'entity' => $vars['entity'],
	'handler' => 'podcasts',
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz',
));

$subtitle = "$author_text $date $comments_link $categories";

// Don't show metadata in widgets view
if (elgg_in_context('widgets')) {
	$metadata = '';
}

$player = elgg_view('podcasts/player', array('entity_guid' => $podcast->guid));

$episode_title = elgg_echo('podcasts:episode_title', array(
	podcasts_get_episode_number($podcast),
	$podcast->title
));

$podcast_title = elgg_view('output/url', array(
		'text' => $episode_title,
		'href' => $podcast->getURL()
));

if ($full) {
	$body = elgg_view('output/longtext', array(
		'value' => $podcast->description,
		'class' => 'elgg-podcast-episode-description'
	));

	$body .= $player;

	$params = array(
		'entity' => $podcast,
		'title' => false,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
	);

	$params = $params + $vars;
	$summary = elgg_view('object/elements/summary', $params);

	echo elgg_view('object/elements/full', array(
		'summary' => $summary,
		'icon' => $owner_icon,
		'body' => $body,
		'class' => 'elgg-podcast-episode',
		'title' => 'sd',
	));
} else {
	// Brief view
	$params = array(
		'entity' => $podcast,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
		'title' => false
	);

	$params = $params + $vars;
	$list_body = elgg_view('object/elements/summary', $params);

	$body .= "<h3 class='elgg-podcast-title'>" . $podcast_title . "</h3>";

	$body .= elgg_view_image_block($owner_icon, $list_body);

	$body .= elgg_view('output/longtext', array(
		'value' => elgg_get_excerpt($podcast->description),
		'class' => 'elgg-podcast-episode-description'
	));

	$body .= $player;

	echo $body;
}


