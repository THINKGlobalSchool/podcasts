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
	'podcasts:title:owner_podcasts' => '%s\'s Podcast',
	'podcasts:title:all_podcasts' => 'All site podcast episodes',
	'podcasts:title:friends' => 'Friends\' podcast episodes',
	'podcasts:title:usersettings' => 'Podcast Settings',

	// Group
	'podcasts:group' => 'Group podcast',
	'podcasts:enablepodcasts' => 'Enable group podcasts',

	// Labels
	'podcasts:filter:allepisodes' => 'All episodes',
	'podcasts:filter:mypodcast' => 'My Podcast',
	'podcasts:filter:friendsepisodes' => 'Friends\' episodes',
	'podcasts:add' => 'Upload a new episode',
	'podcasts:edit' => 'Edit podcast',
	'podcasts:selectfile' => 'Select File',
	'podcasts:replacefile' => 'Replace File',
	'podcasts:download' => 'Download',
	'podcasts:episode_title' => 'Episode %s: %s',
	'podcasts:copyright' => 'Copyright',
	'podcasts:categories' => 'Categories',
	'podcasts:categories_output' => 'Categories: %s',
	'podcasts:language' => 'Language',
	'podcasts:subtitle' => 'Subtitle',
	'podcasts:editpodcastsettings' => 'Edit Podcast Settings',
	'podcasts:groupsettings' => 'Group Podcast Settings',
	'podcasts:subscribe' => 'Subscribe To Podcast',

	// Admin
	'podcasts:admin:general' => 'General Settings',
	'podcasts:admin:copyright' => 'Default copyright (for iTunes feed)',
	'podcasts:admin:language' => 'Default language (for iTunes feed)',
	'podcasts:admin:exiftool' => 'Path to the exiftool command',

	// Messages
	'podcasts:success:save' => 'Podcast episode saved.',
	'podcasts:success:delete' => 'Podcast episode deleted.',
	'podcasts:success:usersettings' => 'Your podcast settings have been saved.',
	'podcasts:error:save' => 'Cannot save podcast episode.',
	'podcasts:error:delete' => 'Cannot delete podcast episode.',
	'podcasts:error:edit' => 'This podcast episode may not exist or you may not have permissions to edit it.',
	'podcasts:error:notfound' => 'Podcast episode not found.',
	'podcasts:error:missing:title' => 'Please enter an episode title!',
	'podcasts:error:missing:description' => 'Please enter a description for your episode!',
	'podcasts:error:missing:file' => 'Please select a file to upload for this episode!',
	'podcasts:error:partialupload' => 'Error: partial podcast episode file upload',
	'podcasts:error:unknown' => 'Unknown error while uploading podcast episode.',
	'podcasts:error:missing' => 'One or more required fields are missing.',
	'podcasts:error:exiftoolnotfound' => 'Error: exiftool command not found',
	'podcasts:error:exiftoolfailed' => 'Error: exiftool command failed',
	'podcasts:none' => 'No episodes',
	'podcasts:filehelp' => 'MP3 or M4A',
	'podcasts:downloadfailed' => 'Podcast file download failed',

	// Podcast feed
	'podcasts:feed:description' => '%s\'s Podcast',

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
