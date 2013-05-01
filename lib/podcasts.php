<?php
/**
 * Elgg Podcasts Lib
 *
 * @package Podcasts
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2013
 * @link http://www.thinkglobalschool.com/
 *
 */

/**
 * Get page components to view a podcast
 *
 * @param int $guid GUID of a podcast entity.
 *
 * @return array
 */
function podcasts_get_page_content_view($guid = NULL) {

	$return = array();

	$podcast = get_entity($guid);

	// no header or tabs for viewing an individual podcast
	$return['filter'] = '';

	if (!elgg_instanceof($podcast, 'object', 'podcast')) {
		register_error(elgg_echo('noaccess'));
		$_SESSION['last_forward_from'] = current_page_url();
		forward('');
	}

	$return['title'] = elgg_echo('podcasts:episode_title', array(
		podcasts_get_episode_number($podcast),
		$podcast->title
	));

	$container = $podcast->getContainerEntity();

	$crumbs_title = $container->name;

	if (elgg_instanceof($container, 'group')) {
		elgg_push_breadcrumb($crumbs_title, "podcasts/group/$container->guid/all");
	} else {
		elgg_push_breadcrumb($crumbs_title, "podcasts/owner/$container->username");
	}

	elgg_push_breadcrumb($podcast->title);
	$return['content'] = elgg_view_entity($podcast, array('full_view' => true));

	$return['content'] .= elgg_view_comments($podcast);

	$return['layout'] = 'one_sidebar';

	return $return;
}

/**
 * Get page components to list a user's or all podcasts.
 *
 * @param int $container_guid The GUID of the page owner or NULL for all podcasts
 *
 * @return array
 */
function podcasts_get_page_content_list($container_guid = NULL) {

	$return = array();

	$return['filter_context'] = $container_guid ? 'mine' : 'all';

	$options = array(
		'type' => 'object',
		'subtype' => 'podcast',
		'full_view' => false,
	);

	$current_user = elgg_get_logged_in_user_entity();

	if ($container_guid) {
		// access check for closed groups
		group_gatekeeper();

		$options['container_guid'] = $container_guid;
		$container = get_entity($container_guid);
		if (!$container) {

		}
		$return['title'] = elgg_echo('podcasts:title:owner_podcasts', array($container->name));

		$crumbs_title = $container->name;
		elgg_push_breadcrumb($crumbs_title);

		if ($current_user && ($container_guid == $current_user->guid)) {
			$return['filter_context'] = 'mine';
		} else if (elgg_instanceof($container, 'group')) {
			$return['filter'] = false;
		} else {
			// do not show button or select a tab when viewing someone else's podcasts
			$return['filter_context'] = 'none';
		}

		// Default feed
		$return['feed_description'] = elgg_echo('podcasts:feed:description', array($container->name));
		$return['sidebar'] = elgg_view('podcasts/info_sidebar');

	} else {
		set_input('show_podcast_container', 1);
		$return['filter_context'] = 'all';
		$return['title'] = elgg_echo('podcasts:title:all_podcasts');
		elgg_pop_breadcrumb();
		elgg_push_breadcrumb(elgg_echo('podcasts'));
	}

	elgg_register_title_button();

	$return['content'] = elgg_list_entities($options, 'elgg_get_entities_from_metadata', 'podcasts_view_entity_list');

	return $return;
}

/**
 * Get page components to list of the user's friends' podcasts.
 *
 * @param int $user_guid
 *
 * @return array
 */
function podcasts_get_page_content_friends($user_guid) {

	$user = get_user($user_guid);
	if (!$user) {
		forward('podcast/all');
	}

	$return = array();

	$return['filter_context'] = 'friends';
	$return['title'] = elgg_echo('podcasts:title:friends');

	$crumbs_title = $user->name;
	elgg_push_breadcrumb($crumbs_title, "podcast/owner/{$user->username}");
	elgg_push_breadcrumb(elgg_echo('friends'));

	elgg_register_title_button();

	set_input('show_podcast_container', 1);

	if (!$friends = get_user_friends($user_guid, ELGG_ENTITIES_ANY_VALUE, 0)) {
		$return['content'] .= elgg_echo('friends:none:you');
		return $return;
	} else {
		$options = array(
			'type' => 'object',
			'subtype' => 'podcast',
			'full_view' => FALSE,
		);

		foreach ($friends as $friend) {
			$options['container_guids'][] = $friend->getGUID();
		}

		$return['content'] = elgg_list_entities($options, 'elgg_get_entities_from_metadata', 'podcasts_view_entity_list');
	}


	return $return;
}

/**
 * Get page components to edit/create a podcast.
 *
 * @param string  $page     'edit' or 'new'
 * @param int     $guid     GUID of podcast or container
 *
 * @return array
 */
function podcasts_get_page_content_edit($page, $guid = 0) {

	$return = array(
		'filter' => '',
	);

	$vars = array();
	$vars['id'] = 'podcast-edit';
	$vars['class'] = 'elgg-form-alt';
	$vars['enctype'] = 'multipart/form-data';

	$sidebar = '';
	if ($page == 'edit') {
		$podcast = get_entity((int)$guid);

		$title = elgg_echo('podcasts:edit');

		if (elgg_instanceof($podcast, 'object', 'podcast') && $podcast->canEdit()) {
			$vars['entity'] = $podcast;

			$title .= ": \"$podcast->title\"";

			$body_vars = podcasts_prepare_form_vars($podcast, $revision);

			elgg_push_breadcrumb($podcast->title, $podcast->getURL());
			elgg_push_breadcrumb(elgg_echo('edit'));

			$content = elgg_view_form('podcasts/save', $vars, $body_vars);
		} else {
			register_error(elgg_echo('noaccess'));
			$_SESSION['last_forward_from'] = current_page_url();
			forward('');
		}
	} else {
		elgg_push_breadcrumb(elgg_echo('podcasts:add'));
		$body_vars = podcasts_prepare_form_vars(null);

		$title = elgg_echo('podcasts:add');
		$content = elgg_view_form('podcasts/save', $vars, $body_vars);
	}

	$return['title'] = $title;
	$return['content'] = $content;
	$return['sidebar'] = $sidebar;
	return $return;	
}

/**
 * Build content for user settings
 *
 * @return array
 */
function podcasts_get_user_settings_content() {
	// Set the context to settings
	elgg_push_context('settings');

	$user = elgg_get_page_owner_entity();
	
 	$title = elgg_echo('podcasts:title:usersettings');
	
	elgg_pop_breadcrumb();
	elgg_push_breadcrumb(elgg_echo('settings'), "settings/user/$user->username");
	
	$content = elgg_view_form('podcasts/usersettings', array(), array('user' => $user));
	
	$return['title'] = $title;
	$return['content'] = $content;
	$return['layout'] = 'one_sidebar';

	return $return;
}

/**
 * Pull together podcast variables for the save form
 *
 * @param ElggPodcast       $podcast
 *
 * @return array
 */
function podcasts_prepare_form_vars($podcast = NULL) {

	// input names => defaults
	$values = array(
		'title' => NULL,
		'description' => NULL,
		'access_id' => ACCESS_DEFAULT,
		'tags' => NULL,
		'container_guid' => NULL,
		'guid' => NULL,
	);

	if ($podcast) {
		foreach (array_keys($values) as $field) {
			if (isset($podcast->$field)) {
				$values[$field] = $podcast->$field;
			}
		}

		if ($podcast->status == 'draft') {
			$values['access_id'] = $podcast->future_access;
		}
	}

	if (elgg_is_sticky_form('podcast')) {
		$sticky_values = elgg_get_sticky_values('podcast');
		foreach ($sticky_values as $key => $value) {
			$values[$key] = $value;
		}
	}
	
	elgg_clear_sticky_form('podcast');

	if (!$podcast) {
		return $values;
	}

	// load the revision annotation if requested
	if ($revision instanceof ElggAnnotation && $revision->entity_guid == $podcast->getGUID()) {
		$values['revision'] = $revision;
		$values['description'] = $revision->value;
	}

	// display a notice if there's an autosaved annotation
	// and we're not editing it.
	if ($auto_save_annotations = $podcast->getAnnotations('podcast_auto_save', 1)) {
		$auto_save = $auto_save_annotations[0];
	} else {
		$auto_save = false;
	}

	if ($auto_save && $auto_save->id != $revision->id) {
		$values['draft_warning'] = elgg_echo('podcasts:messages:warning:draft');
	}

	return $values;
}

/**
 * Returns a rendered list of podcasts with pagination. This function should be
 * called by wrapper functions.
 *
 * This is a modified version of elgg_view_entity_list
 *
 * @see elgg_list_entities()
 * @see list_user_friends_objects()
 * @see elgg_list_entities_from_metadata()
 * @see elgg_list_entities_from_relationships()
 * @see elgg_list_entities_from_annotations()
 *
 * @param array $entities Array of entities
 * @param array $vars     Display variables
 *		'count'            The total number of entities across all pages
 *		'offset'           The current indexing offset
 *		'limit'            The number of entities to display per page
 *		'full_view'        Display the full view of the entities?
 *		'list_class'       CSS class applied to the list
 *		'item_class'       CSS class applied to the list items
 *		'pagination'       Display pagination?
 *		'list_type'        List type: 'list' (default), 'gallery'
 *		'list_type_toggle' Display the list type toggle?
 *
 * @return string The rendered list of entities
 * @access private
 */
function podcasts_view_entity_list($entities, $vars = array(), $offset = 0, $limit = 10, $full_view = true,
$list_type_toggle = true, $pagination = true) {

	if (!is_int($offset)) {
		$offset = (int)get_input('offset', 0);
	}

	// list type can be passed as request parameter
	$list_type = get_input('list_type', 'list');

	$defaults = array(
		'items' => $entities,
		'list_class' => 'elgg-list-entity',
		'full_view' => true,
		'pagination' => true,
		'list_type' => $list_type,
		'list_type_toggle' => false,
		'offset' => $offset,
	);

	$vars = array_merge($defaults, $vars);

	if (!elgg_in_context('widgets') && elgg_get_viewtype() !== 'rss') {
		unset($vars['list_class']);
		return elgg_view('podcasts/podcasts', $vars);
	} else {
		if ($vars['list_type'] != 'list') {
			return elgg_view('page/components/gallery', $vars);
		} else {
			return elgg_view('page/components/list', $vars);
		}
	}
}

/**
 * Get podcast episode number
 * 
 * @param ElggPodcast The podcast
 * 
 * @return int
 */
function podcasts_get_episode_number($podcast) {
	$options = array(
		'type' => 'object',
		'subtype' => 'podcast',
		'container_guid' => $podcast->container_guid,
		'order_by' => "e.guid",
		'callback' => 'podcasts_guid_callback',
		'limit' => ELGG_ENTITIES_NO_VALUE,
	);
	
	$podcasts = elgg_get_entities($options);

	return array_search($podcast->guid, $podcasts) + 1;
}

/**
 * Returns just a guid from a database $row. Used in elgg_get_entities()'s callback.
 *
 * @param stdClass $row
 * @return type
 */
function podcasts_guid_callback($row) {
	return ($row->guid) ? $row->guid : false;
}


/**
 * Get valid podcast mimetypes
 * 
 * @return array
 */
function podcasts_get_valid_mime_types() {
	return array(
		'audio/mpeg',
		'audio/m4a',
		'audio/mp4'
	);
}

/**
 * Get normalized extension for given mime type
 *
 * @param string $mime_type
 *
 * @return string|bool
 */
function podcasts_get_mime_type_extension($mime_type) {
	switch ($mime_type) {
		case 'audio/mpeg':
			return 'mp3';
			break;
		case 'audio/m4a':
		case 'audio/mp4':
			return 'm4a';
			break;
		default:
			return FALSE;
	}
}

/**
 * Get file info using exiftool
 *
 * @param ElggPodcast $podcast
 *
 * @return mixed  - 0 on success or error message 
 */
function podcasts_populate_file_info($podcast) {
	// Get exiftool command path
	$cmd_path = elgg_get_plugin_setting('podcasts_exiftool_root', 'podcasts');
	
	// Get podcast filename
	$filename = $podcast->getFilenameOnFilestore();

	// Set up exif tool command
	$command = $cmd_path . "exiftool -S -Duration -n \"$filename\"";
	$output = array();
	$return = 0;

	// Run command
	exec($command, $output, $return);

	// If we have valid output..
	if ($return == 0 && count($output) >= 1) {
		$duration = $output[0]; // This will be something like: Duration: 134.22424 (in seconds)
		$duration = (int)round((float)trim(substr($duration, strpos($duration, ":") + 1)));
		
		// Got a valid number? Set duration.
		if (is_int($duration)) {
			$podcast->duration = $duration;
		} else {
			// Something wrong
			$return = 1;
		}
	} 
	
	// Return value
	return $return;
}

/**
 * Convert number of seconds to HH:MM:SS time format
 *
 * @param int|float $seconds
 *
 * @return $string
 */
function podcasts_sec_toHHMMSS($seconds) {
	$t = round($seconds);
  	return sprintf('%02d:%02d:%02d', ($t/3600),($t/60%60), $t%60);
}