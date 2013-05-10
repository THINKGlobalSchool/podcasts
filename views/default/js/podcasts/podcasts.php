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

elgg.podcasts.player = null;

/**
 * Podcasts JS init
 */
elgg.podcasts.init = function() {
	// Init SoundManager
	elgg.podcasts.SM = soundManager.setup({
		url: elgg.podcasts.swfPath,
		flashVersion: 9,
		preferFlash: false,
		onready: elgg.podcasts.soundManagerReady
	});

	// Help lightbox
	$('.podcasts-help-lightbox').fancybox({
		onClosed: function() {
			$(this.href).empty();
		}
	});
}

elgg.podcasts.soundManagerReady = function() {
	// Trigger elgg ready hook?
	elgg.trigger_hook('ready', 'soundManager');

	// Initialize Elgg podcast player
	elgg.podcasts.player = new ElggPodcastPlayer();
	elgg.podcasts.player.init({
		initialized: function(player) {
			// Nothing yet..
		}
	});
}

/**
 * ElggPodcastPlayer
 */
function ElggPodcastPlayer() {
	// Define some variables
	var self = this,
		pl = this,
		sm = soundManager, // soundManager instance
		_event,
		playerTemplate = null,
		cleanup
		ua = navigator.userAgent,
		isTouchDevice = (ua.match(/ipad|ipod|iphone/i));

	// Default config
	this.config = {
		// General events
		initialized: $.noop,
		bindPlayerEvents: function(podcast) {
			self._bindPlayerEvents(podcast);
		},

		// Other vars
		emptyTime: '-:--' // null/undefined timer values (before data is available)
	}

	// Init player vars
	this.podcasts = [];
	this.currentPodcast = null;
	this.lastPodcast = null;
	this.player = null;
	this.dragActive = false;
	this.dragExec = new Date();
	this.dragTimer = null;
	this.strings = [];
	this.lastWhilePlayingExec = new Date();
	this.lastWhileLoadingExec = new Date();

	/**
	 * Event trigger shortcut
	 */
	this.trigger = function(event, params) {
		this.config[event](params);
	}

	/**
	 * Merge helper, merges two objects
	 */
	this._merge = function(orig, add) {
		// non-destructive merge
		var obj1 = {}, obj2, i, j; // clone obj1
		for (i in orig) {
			if (orig.hasOwnProperty(i)) {
				obj1[i] = orig[i];
			}
		}
		obj2 = (typeof add === 'undefined'? {} : add);
		for (j in obj2) {
			if (typeof obj1[j] === 'undefined') {
				obj1[j] = obj2[j];
			}
		}
		return obj1;
	};

	/**
	 * Player init
	 */
	this.init = function(config) {
		// Merge defaults and config
		if (config) {
			this.config = this._merge(config, this.config);
		}

		// Init all podcasts
		$('div._elgg_podcast_player').each(function() {
			var $player = $(this);

			// Player id, and audio URL
			id = $player.data('podcast_id');
			url = $player.data('podcast_url');

			// Create podcast 'sounds'
			podcast = soundManager.createSound({
				id:           'podcast-' + id,
				url:          url,
				volume:       75,
				onplay:       self.events.play,
				onstop:       self.events.stop,
				onpause:      self.events.pause,
				onresume:     self.events.resume,
				onfinish:     self.events.finish,
				whileloading: self.events.whileloading,
				whileplaying: self.events.whileplaying,
				onload:       self.events.onload
			});

			// Store elgg data
			title = $player.data('podcast_title');
			owner = $player.data('podcast_owner');
			duration = $player.data('podcast_duration');

			podcast.elgg_data = {
				guid: id,
				url: url,
				owner_name: owner,
				title: title,
				duration: duration
			}

			// Get player template and display
			template = self.player.cloneNode(true);
			$player.replaceWith($(template));
			$player = $(template);

			// Define player elements
			podcast.player = {
				buttons: $player.children('.elgg-podcast-player-buttons'),
				statusBar: $player.children('.elgg-podcast-player-statusbar'),
				loading: $player.find('.elgg-podcast-player-loading'),
				position: $player.find('.elgg-podcast-player-position'),
				timingBox: $player.find('.elgg-podcast-player-timing'),
				timing: $player.find('.elgg-podcast-player-timing').find('.timing-data')
			}

			// Set initial timer stuff (before loading)
	        str = self.strings.timing.replace('%s1',self.config.emptyTime);
	        str = str.replace('%s2', self.getTime(duration, true)); // Set initial duration from Elgg data
	        podcast.player.timing.html(str);

	        // Add this podcast to the player's podcast array
			self.podcasts.push(podcast);

			// Bind events
			self.trigger('bindPlayerEvents', podcast);
		});

		// Done initting
		self.trigger('initialized', pl);
	};

	/**	
	 * Bind player events (pause, play, stop) and statusbar mouse/touch move
	 */
	this._bindPlayerEvents = function(podcast) {
		// Bind play
		$(podcast.player.buttons).find('.elgg-podcast-player-play').bind('click', function() {
			if (podcast.playState !== 1) {	
				// Stop the last podcast from playing (if any)
				if (self.lastPodcast) {
					self.lastPodcast.stop();
				}
				// not yet playing
				self.lastPodcast = podcast;

				if (!podcast.paused) {
					podcast.setPosition(0);
					podcast.player.position.css('width', '0px');
				}
				podcast.play();
            } else {
            	podcast.togglePause();
            }
		});

		// Bind pause
		$(podcast.player.buttons).find('.elgg-podcast-player-pause').bind('click', function() {
			podcast.pause();
		});

		// Bind stop
		$(podcast.player.buttons).find('.elgg-podcast-player-stop').bind('click', function() {
			podcast.stop();
		});

		// Will be binding different events based on device
		var down, up, mousemove;

		if (isTouchDevice) {
			down = 'touchstart';
			up = 'touchend';
			move = 'touchmove';
		} else {
			down = 'mousedown';
			up = 'mouseup';
			move = 'mousemove';
		}

		// Bind mouse/touch-down in status/progress bar
		$(podcast.player.statusBar).bind(down, function(event) {
			if (podcast.playState === 1) {
				// Get proper event for touch device
				if (self.isTouchDevice && event.touches) {
					event = event.touches[0];
				}
				// Dragging
				self.dragActive = true;

				// Set position (click to seek)
				self.setPosition(event);
				
				// Pause when dragging
				podcast.pause();

				// Bind mouse/touchmove
				$(podcast.player.statusBar).bind(move, self.handleMousemove);
				$(podcast.player.statusBar).addClass('dragging');
				event.preventDefault();
			}
		});

		// Bind mouseup in status/progress bar
		$(podcast.player.statusBar).bind(up, function(event) {
			if (self.dragActive) {
				// Done dragging
				self.dragActive = false;

				$(podcast.player.statusBar).removeClass('dragging');

				// Unbind mouse/touch-move
				$(podcast.player.statusBar).unbind(move);

				// Resume playback
				podcast.resume();	
			}
			event.preventDefault();
		});

	}

	/**
	 * Handler for mouse/touch move events
	 */
	this.handleMousemove = function(event) {
		// Get proper event for touch devices
		if (isTouchDevice && event.touches) {
			event = event.touches[0];
		}

		// Set podcast audio position accordingly
		if (self.dragActive) {
			self.setPosition(event);
		}

		event.stopPropagation();
		event.preventDefault();
	}

	/**
	 * Set audio position, called form statusbar control
	 */
	this.setPosition = function(event) {
		// Get the target
		control = self.getTarget(event);

		// Get target parent (the status bar)
		statusBar = $(control).parent();

		// Get status bar x offset
		status_x = $(statusBar).offset().left;

		// Get event offset
		event_x = parseInt(event.pageX, 10);

		// Get podcast
		podcast = self.lastPodcast;

		// Determine position in podcast
		mSecOffset = Math.floor((event_x - status_x) / statusBar.width() * self.getDurationEstimate(podcast));
		if (!isNaN(mSecOffset)) {
			mSecOffset = Math.min(mSecOffset,podcast.duration);
		}

		// Set podcast position
		if (!isNaN(mSecOffset)) {
			podcast.setPosition(mSecOffset);
		}
	};

	/**
	 * Update player time
	 */
	this.updateTime = function(podcast) {
		var str = self.strings.timing.replace('%s1', self.getTime(podcast.position, true));
		str = str.replace('%s2', self.getTime(self.getDurationEstimate(podcast), true));
		podcast.player.timing.html(str);
	};

	/** 
	 * Get event target helper
	 */
	this.getTarget = function(event) {
		return (event.target || (window.event ? window.event.srcElement : null));
	};

	/**
	 * Convert milliseconds to mm:ss
	 */
	this.getTime = function(msec, asString) {
		var sec = Math.floor(msec / 1000),
		min = Math.floor(sec / 60),
		sec = sec - (min * 60);
		return (asString ? (min + ':' + (sec < 10 ? '0' + sec : sec)) : {'min': min,'sec': sec});
	};

	/**
	 * Helper to get podcast duration
	 */
	this.getDurationEstimate = function(podcast) {
		var estimate = podcast.duration ? podcast.duration : podcast.durationEstimate;
		return (estimate >= podcast.elgg_data.duration) ? estimate : podcast.elgg_data.duration;
	};

	/** 
	 * Set player button state
	 */
	this.setButtonState = function(state) {
		switch (state) {
			case 'playing':
				var play = self.lastPodcast.player.buttons.find('.elgg-podcast-player-play');
				play.removeClass('elgg-podcast-player-play');
				play.addClass('elgg-podcast-player-pause');
				break;
			case 'paused':
			case 'stopped':
			default:
				var pause = self.lastPodcast.player.buttons.find('.elgg-podcast-player-pause');
				pause.removeClass('elgg-podcast-player-pause');
				pause.addClass('elgg-podcast-player-play');
				break;
		}
	}

	/**
	 * Podcast (sound) events
	 */
	this.events = {
		// Play event
		play: function() {
			self.setButtonState('playing');
		},
		// Stop event
		stop: function() {
			self.setButtonState('stopped');
			this.player.position.css('width', '0px');
			this.position = 0;
			self.updateTime(this);
		},
		// Pause event
		pause: function() {
			self.setButtonState('paused');
		},
		// Resume event
		resume: function() {
			self.setButtonState('playing');
		},
		// Finish event
		finish: function() {
			self.setButtonState('stopped');
			this.player.position.css('width', '0px');
			this.position = 0;
			self.updateTime(this);
		},
		// Whileloading event
		whileloading: function() {
			var date = new Date();
			if (date && date - self.lastWhileLoadingExec > 50 || this.bytesLoaded === this.bytesTotal) {
				this.player.loading.css('width', (((this.bytesLoaded/this.bytesTotal)*100)+'%'));
				self.lastWhileLoadingExec = date;
			}
		},
		// Whileplaying event
		whileplaying: function() {
			var date = null;
			if (pl.dragActive) {
				self.updateTime(this);
				this.player.position.css('width', (((this.position/self.getDurationEstimate(this))*100)+'%'));
			} else {
				date = new Date();
				if (date - self.lastWhilePlayingExec > 30) {
					self.updateTime(this);
					// Check if we're forcing 'zero' position (flash is goofy..)
					if (this.player.forceZeroPosition && !this.isHTML5) {
						this.player.position.css('width', '0px');
					} else {
						this.player.position.css('width', (((this.position/self.getDurationEstimate(this))*100)+'%'));	
					}
					self.lastWhilePlayingExec = date;
				}
			}
		},
		// Onload event
		onload: function() {
		}
	}

	// Player template
	playerTemplate = document.createElement('div');
	playerTemplate.className = 'elgg-podcast-player';

    playerTemplate.innerHTML = [
    '  <div class="elgg-podcast-player-buttons">',
	'    <a class="elgg-podcast-player-button elgg-podcast-player-play"></a>',
	'  </div>',
	'  <div class="elgg-podcast-player-statusbar elgg-podcast-player-inner-bar">',
	'    <div class="elgg-podcast-player-loading elgg-podcast-player-loading-bar"></div>',
	'    <div class="elgg-podcast-player-position elgg-podcast-player-position-bar"></div>',
	'  </div>',
	'  <div class="elgg-podcast-player-timing-container elgg-podcast-player-bar">',
	'    <div class="elgg-podcast-player-timing elgg-podcast-player-timing-bar">',
	'      <div id="sm2_timing" class="timing-data">',
	'        <span class="sm2_position">%s1</span> / <span class="sm2_total">%s2</span>',
	'      </div>',
	'    </div>',
	'  </div>',
	'  <div class="elgg-podcast-player-bar elgg-podcast-player-background-bar">',
	'  </div>'
	].join('\n');

	// Set podcast player template
	self.player = playerTemplate.cloneNode(true);

	// Set player timing html
	$timing = $(playerTemplate).find('.timing-data');
	self.strings.timing = $timing.html();
}

elgg.podcasts.helpHandler = function(hook, type, params, options) {
	if (params.target.hasClass('elgg-podcasts-help-module')) {
		options.my = 'left top';
		options.at = 'left bottom';
		return options;
	}
	return null;
};

// Elgg podcasts init
elgg.register_hook_handler('init', 'system', elgg.podcasts.init);
elgg.register_hook_handler('getOptions', 'ui.popup', elgg.podcasts.helpHandler);