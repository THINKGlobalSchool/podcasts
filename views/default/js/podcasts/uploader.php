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
				'audio/mp4'
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

	// Store the button
	var $button = $(this);

	// Show a little spinner
	$(this).replaceWith("<div id='podcast-upload-spinner' class='elgg-ajax-loader'></div>");

	// Make sure tinymce inputs have set the text
	if (typeof(tinyMCE) != 'undefined') {
		tinyMCE.triggerSave();
	}

	// Returns an object, with these fancy callbacks
	var jqXHR = $('input#podcast-file').fileupload('send',{files: data.files})
		.done(function (result, textStatus, jqXHR) {
			// Success/done check elgg status's
			if (result.status != -1) {
				// Display success
				elgg.system_message(result.system_messages.success);

				// Prevent the 'are you sure you want to leave' popup
				window.onbeforeunload = function() {};

				// Good to go, forward to output
				window.location = result.output;
			} else {
				// There was an error, display it
				elgg.register_error(result.system_messages.error);

				// Enable the button (try again?)
				$('#podcast-upload-spinner').replaceWith($button);
			}
		})
    	.fail(function (jqXHR, textStatus, errorThrown) {
			// If we're here, there was an error making the request
			// or we got some screwy response.. display an error and log it for debugging
			elgg.register_error(elgg.echo('podcasts:error:uploadfailedxhr'));
			console.log(errorThrown);
			console.log(textStatus);
			console.log(jqXHR);

			// Enable the button
			$('#podcast-upload-spinner').replaceWith($button);
		})
    	.always(function (result, textStatus, jqXHR) {
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