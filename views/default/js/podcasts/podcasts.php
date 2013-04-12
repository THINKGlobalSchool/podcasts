<?php
/**
 * Elgg Podcasts JS Lib
 *
 * @package Podcasts
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2013
 * @link http://www.thinkglobalschool.com/
 *
 */
?>
elgg.provide('elgg.podcasts');

// jPlayer swf location
elgg.podcasts.swfPath = elgg.get_site_url() + 'mod/podcasts/vendors/jplayer' 

/**
 * Podcasts JS init
 */
elgg.podcasts.init = function() {
	elgg.podcasts.initPlayers();
}

/**
 * Init all jPlayer instances
 */
elgg.podcasts.initPlayers = function() {
	$('.elgg-podcast-player').each(function() {
		elgg.podcasts.initPlayer($(this));
	});
}

/**
 * Init a jPlayer instance on given element
 * 
 * @param $player_element The element
 */
elgg.podcasts.initPlayer = function($player_element) {
	// Get type and url from player data
	var type = $player_element.data('file_ext');
	var url = $player_element.data('file_url');

	// Set up media source
	var set_media = {};
	set_media[type] = url; // ie: m4a: 'http://example/myfile.m4a'

	// Unique css ancestor for each jPlayer instance
	var ancestor = "#" + $player_element.next().attr('id')

	// Init player
	$player_element.jPlayer({
		ready: function() {
			// Set media for this player using set_media object
			$(this).jPlayer("setMedia", set_media)
		},
		play: function() {
			// Pause other players if there are multiple instances
       		$(this).jPlayer("pauseOthers");
    	},
		swfPath: elgg.podcasts.swfPath, 
		supplied: type, // ie: mp3 or m4a
		cssSelectorAncestor: ancestor
	});
}

elgg.register_hook_handler('init', 'system', elgg.podcasts.init);