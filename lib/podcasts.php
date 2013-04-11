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

	$return['title'] = $podcast->title;

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

	return $return;
}

/**
 * Get page components to list a user's or all podcasts.
 *
 * @param int $container_guid The GUID of the page owner or NULL for all podcasts
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
		$return['title'] = elgg_echo('podcasts:title:user_podcasts', array($container->name));

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
	} else {
		$return['filter_context'] = 'all';
		$return['title'] = elgg_echo('podcasts:title:all_podcasts');
		elgg_pop_breadcrumb();
		elgg_push_breadcrumb(elgg_echo('podcast'));
	}

	elgg_register_title_button();

	$list = elgg_list_entities_from_metadata($options);
	if (!$list) {
		$return['content'] = elgg_echo('podcasts:none');
	} else {
		$return['content'] = $list;
	}

	return $return;
}

/**
 * Get page components to list of the user's friends' podcasts.
 *
 * @param int $user_guid
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

		$list = elgg_list_entities_from_metadata($options);
		if (!$list) {
			$return['content'] = elgg_echo('podcasts:none');
		} else {
			$return['content'] = $list;
		}
	}

	return $return;
}

/**
 * Get page components to edit/create a podcast.
 *
 * @param string  $page     'edit' or 'new'
 * @param int     $guid     GUID of podcast or container
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
 * Pull together podcast variables for the save form
 *
 * @param ElggPodcast       $podcast
 * @return array
 */
function podcasts_prepare_form_vars($podcast = NULL, $revision = NULL) {

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