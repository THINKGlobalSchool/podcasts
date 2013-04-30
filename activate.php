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

// Get the plugin entity
$plugin = elgg_get_plugin_from_id('podcasts');

// Default settings
$defaults = array(
	// Default copyright
	'podcasts_copyright' => "&#169; " . elgg_get_site_entity()->name . " " . date('Y', time()),

	// Default language
	'podcasts_language' => 'en-us',

	// Exiftool location
	'podcasts_exiftool_root' => '/usr/bin/'
);

// Set default config values
foreach ($defaults as $setting => $value) {
	if (!$plugin->getSetting($setting)) {
		$plugin->setSetting($setting, $value);
	}
}