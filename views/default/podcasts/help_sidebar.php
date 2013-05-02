<?php
/**
 * Elgg Podcasts Help Sidebar view
 *
 * @package Podcasts
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2013
 * @link http://www.thinkglobalschool.com/
 *
 */

elgg_load_js('lightbox');
elgg_load_css('lightbox');

$help_video = elgg_normalize_url('ajax/view/podcasts/help_video');

$content = <<<HTML
<div class='elgg-output'>
	<p>A podcast is an episodic program delivered via the Internet using an XML protocol called RSS.</p>
	<h5>About Podcasts</h5>
	<ul>
		<li>
			<a class="podcasts-help-lightbox" href="$help_video">Podcasting in Plain English</a>
		</li>
		<li>
			<a target="_blank" href="http://www.apple.com/ca/itunes/podcasts/fanfaq.html">Apple FAQ for Podcast Fans</a>
		</li>
		<li>
			<a target="_blank" href="http://www.apple.com/ca/itunes/podcasts/creatorfaq.html">Apple FAQ for Podcast Creators</a>
		</li>
	</ul>
	<h5>Submitting to iTunes</h5>
	<ul>
		<li>
			<a target="_blank" href="http://www.apple.com/itunes/podcasts/specs.html#submitting">How to Submit</a>
		</li>
	</ul>
</div>
HTML;

$body = elgg_view('output/longtext', array(
	'value' => $content
));

echo elgg_view_module('aside', elgg_echo('podcasts:help:whatis'), $content);