<?php
/**
 * Elgg Podcasts Serve Podcast
 *
 * @package Podcasts
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2013
 * @link http://www.thinkglobalschool.com/
 *
 * Wonderful code from:
 * @link https://groups.google.com/d/msg/jplayer/nSM2UmnSKKA/Hu76jDZS4xcJ
 * @link http://php.net/manual/en/function.readfile.php#86244
 */
elgg_set_ignore_access(TRUE);
// Get the guid
$podcast_guid = get_input("guid");

// Get the file
$podcast = new ElggPodcast($podcast_guid);

// Check for valid podcast
if (!$podcast) {
	header ("HTTP/1.1 404 Not Found");
	return;
}

// Podcast file info
$mime = $podcast->getMimeType();

$podcastname = $podcast->getFileTitle();
$file = $podcast->getFilenameOnFilestore();
$filename = basename($file);

if (!file_exists($file)) {
	header ("HTTP/1.1 404 Not Found");
	return;
}

$size = filesize($file);
$time = date('r', filemtime($file));

$fm	= @fopen($file, 'rb');
if (!$fm) {
	header ("HTTP/1.1 505 Internal server error");
	return;
}

$begin = 0;
$end = $size - 1;

if (isset($_SERVER['HTTP_RANGE'])) {
	if (preg_match('/bytes=\h*(\d+)-(\d*)[\D.*]?/i', $_SERVER['HTTP_RANGE'], $matches)){
		$begin	= intval($matches[1]);
		if (!empty($matches[2])) {
			$end	= intval($matches[2]);
		}
	}
}

if (isset($_SERVER['HTTP_RANGE'])) {
	header('HTTP/1.1 206 Partial Content');
} else {
	header('HTTP/1.1 200 OK');
}

header("Content-Type: $mime"); 
header('Cache-Control: public, must-revalidate, max-age=0');
header('Pragma: no-cache');  
header('Accept-Ranges: bytes');
header('Content-Length:' . (($end - $begin) + 1));
if (isset($_SERVER['HTTP_RANGE'])) {
	header("Content-Range: bytes $begin-$end/$size");
}
header("Content-Disposition: inline; filename=$filename");
header("Content-Transfer-Encoding: binary");
header("Last-Modified: $time");

$cur = $begin;
fseek($fm, $begin, 0);

while(!feof($fm) && $cur <= $end && (connection_status() == 0)) {
	print fread($fm, min(1024 * 16, ($end - $cur) + 1));
	$cur += 1024 * 16;
}
exit;
?>