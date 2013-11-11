<?php
/**
 * Elgg Podcasts RSS Channel extension
 *
 * @package Podcasts
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2013
 * @link http://www.thinkglobalschool.com/
 *
 * @uses $vars['description']
 */

$page_owner = elgg_get_page_owner_entity();
$podcast_author = $page_owner->name;
$podcast_image = $page_owner->getIconURL('large');
$podcast_image = str_replace("&", "&amp;", $podcast_image);

// Group podcast info
if (elgg_instanceof($page_owner, 'group')) {
	$podcast_settings = unserialize($page_owner->podcast_settings);

	// If we've got podcast settings for this group
	if (is_array($podcast_settings)) {
		$podcast_title = $podcast_settings['title'];
		$podcast_subtitle = $podcast_settings['subtitle'];
		$podcast_description = $podcast_settings['description'];
		$podcast_copyright = $podcast_settings['copyright'];
		$podcast_language = $podcast_settings['language'];
		$podcast_categories = $podcast_settings['categories'];
	}

	$podcast_link = elgg_normalize_url("podcasts/group/{$page_owner->guid}/all");
	$podcast_email = $page_owner->getOwnerEntity()->email;
}
// User podcast info
else {
	$podcast_link = elgg_normalize_url("podcasts/owner/{$page_owner->username}");
	$podcast_title = elgg_get_plugin_user_setting('podcast_title', $page_owner->guid, 'podcasts');
	$podcast_subtitle = elgg_get_plugin_user_setting('podcast_subtitle', $page_owner->guid, 'podcasts');
	$podcast_description = elgg_get_plugin_user_setting('podcast_description', $page_owner->guid, 'podcasts');
	$podcast_categories = elgg_get_plugin_user_setting('podcast_categories', $page_owner->guid, 'podcasts');
	$podcast_language = elgg_get_plugin_user_setting('podcast_language', $page_owner->guid, 'podcasts');
	$podcast_copyright = elgg_get_plugin_user_setting('podcast_copyright', $page_owner->guid, 'podcasts');
	$podcast_email = $page_owner->email;
}

// Check for empty values and populate with plugin defaults
if (!$podcast_copyright) {
	$podcast_copyright = elgg_get_plugin_setting('podcasts_copyright', 'podcasts');
}
if (!$podcast_language) {
	$podcast_language = elgg_get_plugin_setting('podcasts_language', 'podcasts');
}

// Set title if empty
if (empty($podcast_title)) {
	$podcast_title = elgg_get_config('sitename') . ": " . $vars['title'];
}

// Set description if empty
if (empty($podcast_description)) {
	$podcast_description = elgg_extract('description', $vars, '');
}

// Format categories
$categories = string_to_tag_array($podcast_categories);

if (count($categories)) {
	$categories_xml = '';
	foreach ($categories as $category) {
		$categories_xml .= '<itunes:category text="' . $category . '" />';
	}
}


if (elgg_in_context('podcasts')) {
	$content = <<<XML
<title><![CDATA[$podcast_title]]></title>
	<link>$podcast_link</link>
	<language><![CDATA[$podcast_language]]></language>
	<description><![CDATA[$podcast_description]]></description>
	<itunes:summary><![CDATA[$podcast_description]]></itunes:summary>
	<copyright><![CDATA[$podcast_copyright]]></copyright>
	<itunes:subtitle><![CDATA[$podcast_subtitle]]></itunes:subtitle>
	<itunes:author><![CDATA[$podcast_author]]></itunes:author>
	<itunes:owner>
		<itunes:name><![CDATA[$podcast_author]]></itunes:name>
		<itunes:email><![CDATA[$podcast_email]]></itunes:email>
	</itunes:owner>
	<itunes:image href="$podcast_image" />
	$categories_xml
XML;

	echo $content;
}