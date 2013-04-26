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

/************** General Configuration Module **************/
$general_label = elgg_echo('podcasts:admin:general');

// Max upload size input
$copyright_label = elgg_echo('podcasts:admin:copyright');
$copyright_input = elgg_view('input/text', array(
	'name' => 'params[podcasts_copyright]', 
	'value' => $copyright
));

$general_body = <<<HTML
	<div>
		<label>$copyright_label</label>
		$copyright_input
	</div>
HTML;

echo elgg_view_module('inline', $general_label, $general_body);
