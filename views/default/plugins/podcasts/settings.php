<?php
/**
 * Elgg Podcasts Plugin Settings
 *
 * @package Podcasts
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2013
 * @link http://www.thinkglobalschool.com/
 *
 */

$plugin = $vars['entity'];

// Default copyright
$copyright = $plugin->podcasts_copyright;
$language = $plugin->podcasts_language;
$exiftool_root = $plugin->podcasts_exiftool_root;

/************** General Settings Configuration Module **************/
$general_label = elgg_echo('podcasts:admin:general');

$copyright_label = elgg_echo('podcasts:admin:copyright');
$copyright_input = elgg_view('input/text', array(
	'name' => 'params[podcasts_copyright]', 
	'value' => $copyright
));

$exiftool_label = elgg_echo('podcasts:admin:exiftool');
$exiftool_input = elgg_view('input/text', array(
	'name' => 'params[podcasts_exiftool_root]', 
	'value' => $exiftool_root
));

$language_label = elgg_echo('podcasts:admin:language');
$language_input = elgg_view('input/text', array(
	'name' => 'params[podcasts_language]', 
	'value' => $language
));

$general_body = <<<HTML
	<div>
		<label>$copyright_label</label>
		$copyright_input
	</div><br />
	<div>
		<label>$language_label</label>
		$language_input
	</div><br />
	<div>
		<label>$exiftool_label</label>
		$exiftool_input
	</div>
HTML;

echo elgg_view_module('inline', $general_label, $general_body);
