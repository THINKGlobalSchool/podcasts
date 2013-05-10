<?php
/**
 * Elgg Podcasts Summary Extension
 *
 * @package Podcasts
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2013
 * @link http://www.thinkglobalschool.com/
 *
 */

if (elgg_instanceof($vars['entity'], 'object', 'podcast')) {

	$podcast = $vars['entity'];

	$owner = $podcast->getOwnerEntity();

	$owner_url = "podcasts/owner/{$owner->username}";
	$owner_name = $owner->name;

	$container = $podcast->getContainerEntity();

	if (get_input('show_podcast_container')) {
		if (elgg_instanceof($container, 'group')) {
			$container = $podcast->getContainerEntity();
			$owner_url = "podcasts/group/{$container->guid}/all";
			$owner_name = $container->name;
		}

		$podcasts_link = elgg_view('output/url', array(
			'href' => $owner_url,
			'text' => elgg_echo('podcasts:title:owner_podcasts', array($owner_name)),
			'is_trusted' => true,
		));	
	}
	echo  "<h4 class='elgg-podcast-summary-title'>" . $podcasts_link . "</h4>";
}
