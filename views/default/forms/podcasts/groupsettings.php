<?php
/**
 * Elgg Podcasts Save Group Podcast Settings Form
 *
 * @package Podcasts
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2013
 * @link http://www.thinkglobalschool.com/
 *
 */

$group = elgg_extract('group', $vars);

$podcast_settings = unserialize($group->podcast_settings);

if (!$podcast_settings) {
	$podcast_settings = array();
}

$title = $podcast_settings['title'];
$subtitle = $podcast_settings['subtitle'];
$description = $podcast_settings['description'];
$language = $podcast_settings['language'];
$categories = $podcast_settings['categories'];
$copyright = $podcast_settings['copyright'];

// Output base seeings form
echo elgg_view_form('podcasts/settings',array('action' => 'action/podcasts/groupsettings'), array(
	'entity' => $group,
	'title' => $title,
	'description' => $description,
	'subtitle' => $subtitle,
	'language' => $language,
	'categories' => $categories,
	'copyright' => $copyright
));