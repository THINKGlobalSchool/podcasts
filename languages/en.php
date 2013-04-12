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
	'podcast' => 'Podcast',
	'podcasts:podcast' => 'Podcast',
	'podcasts' => 'Podcasts',
	'item:object:podcast' => 'Podcasts',

	// Titles
	'podcasts:title:user_podcasts' => '%s\'s podcast',
	'podcasts:title:all_podcasts' => 'All site podcast episodes',
	'podcasts:title:friends' => 'Friends\' podcast episodes',

	// Group
	'podcasts:group' => 'Group podcast',
	'podcasts:enablepodcasts' => 'Enable group podcasts',

	// Labels
	'podcasts:add' => 'Upload a new episode',
	'podcasts:edit' => 'Edit podcast',
	'podcasts:selectfile' => 'Select File',
	'podcasts:replacefile' => 'Replace File',
	'podcasts:download' => 'Download',

	// Messages
	'podcasts:success:save' => 'Podcast episode saved.',
	'podcasts:success:delete' => 'Podcast episode deleted.',
	'podcasts:error:save' => 'Cannot save podcast.',
	'podcasts:error:delete' => 'Cannot delete podcast.',
	'podcasts:error:edit' => 'This podcast may not exist or you may not have permissions to edit it.',
	'podcasts:error:notfound' => 'Podcast not found.',
	'podcasts:error:missing:title' => 'Please enter a podcast title!',
	'podcasts:error:missing:description' => 'Please enter a description for your podcast!',
	'podcasts:error:missing:file' => 'Please select a file to upload for this podcast!',
	'podcasts:error:partialupload' => 'Error: partial podcast file upload',
	'podcasts:error:unknown' => 'Unknown error while uploading podcast.',
	'podcasts:none' => 'No episodes',
	'podcasts:filehelp' => 'MP3 or M4A',
	'podcasts:downloadfailed' => 'Podcast file download failed',

	// Exceptions
	'InvalidPodcastFileException:InvalidMimeType' => 'Invalid Podcast Mime Type: %s',

	// River
	'river:create:object:podcast' => '%s published a new podcast episode: %s',
	'river:comment:object:podcast' => '%s commented on the podcast episode: %s',

	// Notifications
	'podcasts:newpodcast' => 'A new podcast episode',
	'podcasts:notification' =>
'
%s published a new podcast episode

%s
%s

Listen to and comment on this podcast episode:
%s
',

);

add_translation('en', $english);
