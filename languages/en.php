<?php
/**
 * Elgg Podcasts English Language Translation
 *
 * @package Podcasts
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2013
 * @link http://www.thinkglobalschool.com/
 *
 */

$english = array(
	// General
	'podcast' => 'Podcasts',
	'podcasts:podcast' => 'Podcast',
	'item:object:podcast' => 'Podcasts',

	// Titles
	'podcasts:title:user_podcasts' => '%s\'s podcasts',
	'podcasts:title:all_podcasts' => 'All site podcasts',
	'podcasts:title:friends' => 'Friends\' podcasts',

	// Group
	'podcasts:group' => 'Group podcasts',
	'podcasts:enablepodcasts' => 'Enable group podcasts',

	// Labels
	'podcasts:add' => 'Publish a new podcast',
	'podcasts:edit' => 'Edit podcast',
	'podcasts:selectfile' => 'Select File',
	'podcasts:replacefile' => 'Replace File',

	// Messages
	'podcasts:success:save' => 'Podcast saved.',
	'podcasts:success:delete' => 'Podcast deleted.',
	'podcasts:error:save' => 'Cannot save podcast.',
	'podcasts:error:delete' => 'Cannot delete podcast.',
	'podcasts:error:edit' => 'This podcast may not exist or you may not have permissions to edit it.',
	'podcasts:error:notfound' => 'Podcast not found.',
	'podcasts:error:missing:title' => 'Please enter a podcast title!',
	'podcasts:error:missing:description' => 'Please enter a description for your podcast!',
	'podcasts:error:missing:file' => 'Please select a file to upload for this podcast!',
	'podcasts:error:partialupload' => 'Error: partial podcast file upload',
	'podcasts:error:unknown' => 'Unknown error while uploading podcast.',
	'podcasts:none' => 'No podcasts',
	'podcasts:filehelp' => 'MP3 or M4A',

	// Exceptions
	'InvalidPodcastFileException:InvalidMimeType' => 'Invalid Podcast Mime Type: %s',

	// River
	'river:create:object:podcast' => '%s published a new podcast %s',
	'river:comment:object:podcast' => '%s commented on the podcast %s',

	// Notifications
	'podcasts:newpodcast' => 'A new podcast',
	'podcasts:notification' =>
'
%s published a new podcast

%s
%s

Listen to and comment on this podcast:
%s
',

);

add_translation('en', $english);
