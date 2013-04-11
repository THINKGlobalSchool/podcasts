<?php
/**
 * Elgg Podcasts Delete Action
 *
 * @package Podcasts
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2013
 * @link http://www.thinkglobalschool.com/
 *
 */

$podcast_guid = get_input('guid');
$podcast = get_entity($podcast_guid);

if (elgg_instanceof($podcast, 'object', 'podcast') && $podcast->canEdit()) {
	$container = get_entity($podcast->container_guid);
	if ($podcast->delete()) {
		system_message(elgg_echo('podcasts:success:delete'));
		if (elgg_instanceof($container, 'group')) {
			forward("podcasts/group/$container->guid/all");
		} else {
			forward("podcasts/owner/$container->username");
		}
	} else {
		register_error(elgg_echo('podcasts:error:delete'));
	}
} else {
	register_error(elgg_echo('podcasts:error:notfound'));
}

forward(REFERER);