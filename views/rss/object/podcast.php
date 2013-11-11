<?php
/**
 * Elgg Podcasts RSS Object View
 *
 * @package Podcasts
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2013
 * @link http://www.thinkglobalschool.com/
 *
 */

$podcast = elgg_extract('entity', $vars, FALSE);

if (!elgg_instanceof($podcast, 'object', 'podcast')) {
	return TRUE;
}

$owner = $podcast->getOwnerEntity();
$container = $podcast->getContainerEntity();

$owner_url = "podcasts/owner/{$owner->username}";
$owner_name = $owner->name;

$episode_title = elgg_echo('podcasts:episode_title', array(
	podcasts_get_episode_number($podcast),
	$podcast->title
));

$subtitle = elgg_get_excerpt($podcast->description);

$summary = trim(elgg_strip_tags($podcast->description));

$image = $owner->getIconURL('large');

$image = str_replace("&", "&amp;", $image);

$guid = $podcast->getURL(); // For iTunes

$pubDate = date("r", $podcast->time_created); // RFC 2822

$duration_string = podcasts_sec_toHHMMSS($podcast->duration);

// Build keyword string
if ($podcast->tags) {
	$keywords = "<itunes:keywords>";
	for ($i = 0; $i < count($podcast->tags); $i++) {
		$keywords .= $podcast->tags[$i];
		if ($i != (count($podcast->tags)-1)) {
			$keywords .= ", ";
		}
	}
	$keywords .= "</itunes:keywords>";
}

// Enclosure info
$enclosure_url = $podcast->getServeURL();
$mime = $podcast->getMimeType();
$file = $podcast->getFilenameOnFilestore();
$size = filesize($file);

$content = <<<XML
<item>
		<title><![CDATA[$episode_title]]></title>
		<itunes:author><![CDATA[$owner_name]]></itunes:author>
		<itunes:subtitle><![CDATA[$subtitle]]></itunes:subtitle>
		<itunes:summary><![CDATA[$summary]]></itunes:summary>
		<itunes:image href="$image" />
		<enclosure url="$enclosure_url" length="$size" type="$mime" />
		<guid>$guid</guid>
		<pubDate>$pubDate</pubDate>
		<itunes:duration>$duration_string</itunes:duration>
		$keywords
	</item>
XML;
echo $content;
