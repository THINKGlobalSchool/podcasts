<?php
/**
 * Elgg Podcasts Download Podcast
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

// Get the file
$podcast = new ElggPodcast($podcast_guid);

// Check for valid podcast
if (!$podcast) {
	register_error(elgg_echo("podcasts:downloadfailed"));
	forward(REFERER);
}

// Podcast file info
$mime = $podcast->getMimeType();
$podcastname = $podcast->getFileTitle();
$filename = $podcast->getFilenameOnFilestore();

if (!file_exists($filename)) {
	register_error(elgg_echo("podcasts:notfound"));
	forward(REFERER);
}

$size = filesize($filename);

// Output file
header("Pragma: public"); // fix for IE https issue
header("Content-Type: $mime");
header("Content-Disposition: attachment; filename=\"$podcastname\"");
header("Content-Length: " . $size);
ob_clean();
flush();
readfile($filename);
exit;
?>