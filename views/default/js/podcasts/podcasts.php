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
			$('div.elgg-podcast-player').each(function() {
				var sound = soundManager.createSound({
					id:           $(this).data('podcast_id'),
					url:          $(this).data('podcast_url'),
					volume:       50,
					onplay:       elgg.podcasts.events.play,
					onstop:       elgg.podcasts.events.stop,
					onpause:      elgg.podcasts.events.pause,
					onresume:     elgg.podcasts.events.resume,
					onfinish:     elgg.podcasts.events.finish,
					whileloading: elgg.podcasts.events.whileloading,
					whileplaying: elgg.podcasts.events.whileplaying,
					onload:       elgg.podcasts.events.onload
				});
			});
		}
	});

	// Delegate play, pause, stop
	$(document).delegate('.elgg-podcast-player-play', 'click', elgg.podcasts.play);
	$(document).delegate('.elgg-podcast-player-pause', 'click', elgg.podcasts.pause);
	$(document).delegate('.elgg-podcast-player-stop', 'click', elgg.podcasts.stop);

}

// Events
elgg.podcasts.events = {
	// Play event
	play: function() {
		console.log('PLAY!!!');
	},
	// Stop event
	stop: function() {
		console.log('STOP!!!');
	},
	// Pause event
	pause: function() {
		console.log('PAUSE!!!');
	},
	// Resume event
	resume: function() {
		console.log('RESUME!!!');
	},
	// Finish event
	finish: function() {
		console.log('FINISH!!!');
	},
	// Whileloading event
	whileloading: function() {
		console.log('WHILE LOADING!!!');
	},
	// Whileplaying event
	whileplaying: function() {
		console.log('WHILE PLAYING!!!');
	},
	// Onload event
	onload: function() {
		console.log('ONLOAD!!!');
	}
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