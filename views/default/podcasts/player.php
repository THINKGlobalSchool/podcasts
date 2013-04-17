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

$entity = elgg_extract('entity', $vars);

if (!elgg_instanceof($entity, 'object', 'podcast')) {
	return FALSE;
}

$podcast_url = $entity->getServeURL();
$podcast_id = 'elgg-podcast-' . $entity->guid;

?>
<br />

<div class='elgg-podcast' data-podcast_id='<?php echo $podcast_id ?>' data-podcast_url="<?php echo $podcast_url; ?>">
	<a href='#' class="elgg-podcast-play">Play</a> | 
	<a href='#' class='elgg-podcast-pause'>Pause</a> | 
	<a href='#' class='elgg-podcast-stop'>Stop</a>
</div>