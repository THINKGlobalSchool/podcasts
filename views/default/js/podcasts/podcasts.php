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
	elgg.podcasts.SM = soundManager.setup({
		url: elgg.podcasts.swfPath,
		flashVersion: 9,
		preferFlash: false,
		onready: elgg.podcasts.soundManagerReady
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

			podcast.elgg_data = {
				guid: id,
				url: url,
				owner_name: owner,
				title: title
			}

			// Get player template and display
			template = self.player.cloneNode(true);
			$player.replaceWith($(template));
			$player = $(template);
			$player.slideDown();

			// Define player elements
			podcast.player = {
				buttons: $player.children('.elgg-podcast-player-buttons'),
				statusBar: $player.children('.elgg-podcast-player-statusbar'),
				loading: $player.find('.elgg-podcast-player-loading'),
				position: $player.find('.elgg-podcast-player-position'),
				timingBox: $player.children('.elgg-podcast-player-timing'),
				timing: $player.children('.elgg-podcast-player-timing').find('.timing-data')
			}

			// Set initial timer stuff (before loading)
	        str = self.strings.timing.replace('%s1',self.config.emptyTime);
	        str = str.replace('%s2',self.config.emptyTime);
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

				self.dragActive = true;
				
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
		return podcast.duration ? podcast.duration : podcast.durationEstimate;
	};

	/** 
	 * Set player button state
	 */
	this.setButtonState = function(state) {
		var play = self.lastPodcast.player.buttons.find('.elgg-podcast-player-play');
		var pause = self.lastPodcast.player.buttons.find('.elgg-podcast-player-pause');
		var stop = self.lastPodcast.player.buttons.find('.elgg-podcast-player-stop');

		switch (state) {
			case 'playing':
				play.addClass('active');
				pause.removeClass('active');
				break;
			case 'paused':
				pause.addClass('active');
				play.removeClass('active');
				break;
			case 'stopped':
			default:
				pause.removeClass('active');
				stop.removeClass('active');
				play.removeClass('active');
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
	'    <a class="elgg-podcast-player-button elgg-podcast-player-pause"></a>',
	'    <a class="elgg-podcast-player-button elgg-podcast-player-stop"></a>',
	'  </div>',
	'  <div class="elgg-podcast-player-statusbar">',
	'    <div class="elgg-podcast-player-loading"></div>',
	'    <div class="elgg-podcast-player-position"></div>',
	'  </div>',

	'  <div class="elgg-podcast-player-timing">',
	'    <div id="sm2_timing" class="timing-data">',
	'      <span class="sm2_position">%s1</span> / <span class="sm2_total">%s2</span>',
	'    </div>',
	'  </div>'

	].join('\n');

	// Set podcast player template
	self.player = playerTemplate.cloneNode(true);

	// Set player timing html
	$timing = $(playerTemplate).find('.timing-data');
	self.strings.timing = $timing.html();
}

// Elgg podcasts init
elgg.register_hook_handler('init', 'system', elgg.podcasts.init);