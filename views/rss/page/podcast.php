<?php
/**
 * Elgg Podcasts iTunes compatible RSS feed
 *
 * - This is a tweaked version of the core rss/page/default view
 *
 * @package Podcasts
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2013
 * @link http://www.thinkglobalschool.com/
 *
 * @uses $vars['body']       The items for the RSS feed as a string
 */

$body = elgg_extract('body', $vars, '');

$extensions = elgg_view('extensions/podcasts/channel', $vars);

// allow caching as required by stupid MS products for https feeds.
header('Pragma: public', true);
header("Content-Type: text/xml");

echo <<<XML
<?xml version='1.0'?>
<rss xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd" version="2.0">
<channel>
	$extensions
	$body
</channel>
</rss>
XML;
