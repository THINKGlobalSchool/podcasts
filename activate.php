<?php
/**
 * Elgg Podcasts Activate Script
 *
 * @package Podcasts
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2013
 * @link http://www.thinkglobalschool.com/
 *
 */

// Register ElggPodcast class for object/podcast 
if (get_subtype_id('object', 'podcast')) {
	update_subtype('object', 'podcast', 'ElggPodcast');
} else {
	add_subtype('object', 'podcast', 'ElggPodcast');
}
