<?php
/**
 * Elgg Podcasts Uploader JS Lib
 *
 * @package Podcasts
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2013
 * @link http://www.thinkglobalschool.com/
 *
 */
?>

elgg.provide('elgg.podcasts.uploader');

// Get php upload limit
elgg.podcasts.uploader.post_max_size = <?php echo ini_get("post_max_size"); ?>;

elgg.podcasts.uploader.fileUploader = null;

/**
 * Uploader init
 */
elgg.podcasts.uploader.init = function() {
	// Init the file uploader
	elgg.podcasts.uploader.fileUploader = elgg.podcasts.uploader.initFileUploader();

	// Bind uploader toggle link
	$('.podcasts-toggle-uploader').live('click', elgg.podcasts.uploader.toggle);
}

/**
 * Init the file uploader
 */
elgg.podcasts.uploader.initFileUploader = function() {
	// Click handler for the file submit button
	$('#podcast-save-button').live('click', elgg.podcasts.uploader.saveClick);

	return $('input#podcast-file').fileupload({
		dataType: 'json',
		dropZone: $('#podcast-dropzone'),
		fileInput: $('input#podcast-file'),
		drop: function (event, data) {
			// Remove drag class
			$('#podcast-dropzone').removeClass('podcast-dropzone-dragover');

			// Make sure we're not dropping multiple files
			if (data.files.length > 1) {
				elgg.register_error(elgg.echo('podcasts:error:toomanyfiles'));
				event.preventDefault();
			}

			// Check file size
			if (data.files[0].size > elgg.podcasts.uploader.post_max_size) {
				var max_size = elgg.podcasts.uploader.bytesToSize(elgg.podcasts.uploader.post_max_size);

				elgg.register_error(elgg.echo('podcasts:error:filetoolarge', [max_size]));
				event.preventDefault();
			}

			// Check file type
			var valid_types = [
				'audio/mpeg',
				'audio/x-m4a', 
				'application/octet-stream', // Allowing this.. will check for valid type in upload action
				'audio/m4a', 
				'audio/mp4',
				'audio/mp3'
			];

			if ($.inArray(data.files[0].type, valid_types) === -1) {
				elgg.register_error(elgg.echo('InvalidPodcastFileException:InvalidMimeType', [data.files[0].type]));
				event.preventDefault();
			}
		},
		add: function (event, data) {

			// Get the dropped file
			var file = data.files[0];

			// Set file data on the input, to be used with click event later
			$('input#podcast-file').data('data', data);

			// Remove dropzone classes and display info
			var $div = $('#podcast-dropzone');

			// Clear the dropzone
			$div.children().remove();

			var $drop_name = $(document.createElement('span'));
			$drop_name.addClass('podcast-file-name');
			$drop_name.html(file.name);

			var $drop_size = $(document.createElement('span'));
			$drop_size.addClass('podcast-file-size');
			$drop_size.html(elgg.podcasts.uploader.calculateSize(file.size));

			$div.append($drop_name);
			$div.append($drop_size);
		},
		dragover: function (event, data) {
			// Add fancy dragover class
			$('#podcast-dropzone').addClass('podcast-dropzone-dragover');
		},
		progress: function (event, data) {
			// Update progress for context
			var progress = parseInt(data.loaded / data.total * 100, 10);	
			$("#podcast-upload-progress").progressbar("option", "value", progress);
			$('.ui-dialog-title').html(elgg.echo('podcasts:uploading').replace('$', progress + '%'));
    	},
    	done: function(event, data) {
    		// Prevent the 'are you sure you want to leave' popup
    		window.onbeforeunload = function() {};
    	}
	});
}

/**
 * Click handler for the submit button
 */ 
elgg.podcasts.uploader.saveClick = function(event) {
	// Get file data
	var data = $('input#podcast-file').data('data');

	// If we're editing a podcast, check to see if a new file has been added..
	// data will equal 'undefined' if not.. in that case we're just updating
	// the podcast title/desc/tags/etc.. go ahead with a normal submit
	if ($('input[name="guid"]').val() && data == undefined) {
		return true;
	}

	// Make sure tinymce inputs have set the text
	if (typeof(tinyMCE) != 'undefined') {
		tinyMCE.triggerSave();
	}

	var valid = true;

	// Make sure we've got a file
	if (!data) {
		elgg.register_error(elgg.echo('podcasts:error:missing:file'));
		valid = false;
	}

	// Check for title
	if (!$('#podcast-title').val()) {
		elgg.register_error(elgg.echo('podcasts:error:missing:title'));	
		valid = false;
	}

	// Check for description
	if (!$('#podcast-description').val()) {
		elgg.register_error(elgg.echo('podcasts:error:missing:description'));
		valid = false;
	}

	// Missing info.. return 
	if (!valid) {
		return false;
	}

	// Store the button
	var $button = $(this);

	// Show a little spinner
	$(this).replaceWith("<div id='podcast-upload-spinner' class='elgg-ajax-loader'></div>");

	$("#podcast-upload-dialog").dialog({
		modal: true,
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		title: elgg.echo('podcasts:uploading'),
		height: 85,
		open: function(event, ui) {
			$(".ui-dialog-titlebar-close").remove();
			$("#podcast-upload-progress").progressbar({ value: 0 });
			$('.ui-dialog-title').html(elgg.echo('podcasts:uploading').replace('$', '0%'));
		}
	});

	// Send files with promise
	var jqXHR = $('input#podcast-file').fileupload('send',{files: data.files})
		.success(function (result, textStatus, jqXHR) {
			// Success/done check elgg status's
			if (result.status != -1) {
				// Display success
				elgg.system_message(result.system_messages.success);
				window.parent.location = result.output;
			} else {
				// There was an error, display it
				elgg.register_error(result.system_messages.error);

				// Enable the button (try again?)
				$('#podcast-upload-spinner').replaceWith($button);
			}
		})
    	.error(function (jqXHR, textStatus, errorThrown) {
			// If we're here, there was an error making the request
			// or we got some screwy response.. display an error and log it for debugging
			elgg.register_error(elgg.echo('podcasts:error:uploadfailedxhr'));

			// Enable the button
			$('#podcast-upload-spinner').replaceWith($button);
		})
    	.complete(function (result, textStatus, jqXHR) {
			// Just keeping this here for future use/testing
		});

		event.preventDefault();
}

/**
 * Click handler for upload toggle link
 */
elgg.podcasts.uploader.toggle = function(event) {
	event.preventDefault();

	if (elgg.podcasts.uploader.fileUploader) {
		elgg.podcasts.uploader.fileUploader.fileupload('destroy');
		$('#podcast-save-button').die('click');
		elgg.podcasts.uploader.fileUploader = null;
		$(this).html(elgg.echo('podcasts:hidebasicuploader'));
	} else {
		elgg.podcasts.uploader.fileUploader = elgg.podcasts.uploader.initFileUploader();
		$(this).html(elgg.echo('podcasts:showbasicuploader'));
	}

	$('#podcast-basic-uploader').toggle();
	$('#podcast-file').toggle();
	$('#podcast-dropzone').toggle();
}

/**
 * Convert number of bytes into human readable format
 *
 * @param integer bytes     Number of bytes to convert
 * @param integer precision Number of digits after the decimal separator
 * @return string
 */
elgg.podcasts.uploader.bytesToSize = function(bytes) {
	var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
	if (bytes == 0) return 'n/a';
	var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
	return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
};

/**
 * Calculate file size for display
 * 
 * @param integer
 * @return string
 */
elgg.podcasts.uploader.calculateSize = function(size) {
	if (typeof size !== 'number') {
		return '';
	}
	if (size >= 1000000000) {
		return (size / 1000000000).toFixed(2) + ' GB';
	}
	if (size >= 1000000) {
		return (size / 1000000).toFixed(2) + ' MB';
	}
	return (size / 1000).toFixed(2) + ' KB';
}

// Elgg podcasts uploader init
elgg.register_hook_handler('init', 'system', elgg.podcasts.uploader.init);