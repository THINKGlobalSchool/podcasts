<?php
/**
 * Elgg Podcasts File Download
 *
 * @package Podcasts
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2013
 * @link http://www.thinkglobalschool.com/
 *
 */

// Get the guid
$podcast_guid = get_input("guid");

// Get inline flag
$inline = get_input('inline', 0);

$disposition = $inline ? 'inline' : 'attachment';

// Get the file
$podcast = get_entity($podcast_guid);
if (!$podcast) {
	register_error(elgg_echo("podcasts:downloadfailed"));
	forward();
}

$mime = $podcast->getMimeType();

$podcastname = $podcast->getFileTitle();

$filename = $podcast->getFilenameOnFilestore();

// fix for IE https issue
header("Pragma: public");
header("Content-type: $mime");
header("Content-Disposition: {$disposition}; filename=\"$podcastname\"");
header("Content-length: " . filesize($filename));

ob_clean();
flush();
readfile($filename);
exit;
