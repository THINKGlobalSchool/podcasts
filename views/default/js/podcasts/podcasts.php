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

elgg.podcasts.swfPath = elgg.get_site_url() + 'mod/podcasts/vendors/soundmanager2/swf/';

elgg.podcasts.SM = null;

/**
 * Podcasts JS init
 */
elgg.podcasts.init = function() {
	elgg.podcasts.initPlayers();
}

/**
 * Init all podcasts
 */
elgg.podcasts.initPlayers = function() {
	elgg.podcasts.SM = soundManager.setup({
		url: elgg.podcasts.swfPath,
		flashVersion: 9, // optional: shiny features (default = 8)// optional: ignore Flash where possible, use 100% HTML5 mode
		preferFlash: false,
		onready: function() {
			// Ready to use; soundManager.createSound() etc. can now be called.
			$('div.elgg-podcast').each(function() {
				var sound = soundManager.createSound({
					id: $(this).data('podcast_id'),
					url: $(this).data('podcast_url'),
					volume: 50
					//onload: function() {}
				});
			});
		}
	});

	// Delegate play, pause, stop
	$(document).delegate('.elgg-podcast-play', 'click', elgg.podcasts.play);
	$(document).delegate('.elgg-podcast-pause', 'click', elgg.podcasts.pause);
	$(document).delegate('.elgg-podcast-stop', 'click', elgg.podcasts.stop);

}

elgg.podcasts.play = function(event) {
	if (elgg.podcasts.SM) {
		elgg.podcasts.SM.getSoundById($(this).parent().data('podcast_id')).play();
	}
	event.preventDefault();
}

elgg.podcasts.pause = function(event) {
	if (elgg.podcasts.SM) {
		elgg.podcasts.SM.getSoundById($(this).parent().data('podcast_id')).pause();
	}
	event.preventDefault();
}

elgg.podcasts.stop = function(event) {
	if (elgg.podcasts.SM) {
		elgg.podcasts.SM.getSoundById($(this).parent().data('podcast_id')).stop();
	}
	event.preventDefault();
}

elgg.register_hook_handler('init', 'system', elgg.podcasts.init);