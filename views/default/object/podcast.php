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

if (!$podcast) {
	return TRUE;
}

$owner = $podcast->getOwnerEntity();
$container = $podcast->getContainerEntity();

$categories = elgg_view('output/categories', $vars);

$owner_icon = elgg_view_entity_icon($owner, 'tiny');
$owner_link = elgg_view('output/url', array(
	'href' => "podcasts/owner/$owner->username",
	'text' => $owner->name,
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

if ($full) {
	$body = elgg_view('output/longtext', array(
		'value' => $podcast->description,
		'class' => 'podcast',
	));

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
	));

} else {
	// Brief view
	$params = array(
		'entity' => $podcast,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
		'content' => $excerpt,
	);

	$params = $params + $vars;
	$list_body = elgg_view('object/elements/summary', $params);

	echo elgg_view_image_block($owner_icon, $list_body);
}
