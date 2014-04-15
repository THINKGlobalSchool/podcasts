<?php
/**
 * Elgg Podcasts Audio Player
 *
 * @package Podcasts
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2013
 * @link http://www.thinkglobalschool.com/
 *
 * @uses $vars['entity']
 */

$entity_guid = elgg_extract('entity_guid', $vars);

$entity = new ElggPodcast($entity_guid);

if (!elgg_instanceof($entity, 'object', 'podcast')) {
	return FALSE;
}

$guid = $entity->guid;
$title = $entity->title;
$url = $entity->getServeURL();
$owner = $entity->getOwnerEntity()->name;
$milliseconds = (int)$entity->duration * 1000;

?>
<div class='_elgg_podcast_player'
	data-podcast_id="<?php echo $guid; ?>"
	data-podcast_url="<?php echo $url; ?>"
	data-podcast_title="<?php echo $title; ?>"
	data-podcast_owner="<?php echo $owner; ?>"
	data-podcast_duration="<?php echo $milliseconds; ?>"
></div>