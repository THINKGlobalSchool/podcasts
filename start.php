<?php
/**
 * Elgg Podcasts Start
 *
 * @package Podcasts
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2013
 * @link http://www.thinkglobalschool.com/
 *
 * @todo
 * - Lots of stuff
 * - Widgets
 */

elgg_register_event_handler('init', 'system', 'podcasts_init');

// Init podcasts
function podcasts_init() {
	// Register library
	elgg_register_library('elgg:podcasts', elgg_get_plugins_path() . 'podcasts/lib/podcasts.php');

	// Add podcasts site menu item
	$item = new ElggMenuItem('podcasts', elgg_echo('podcasts'), 'podcasts/all');
	elgg_register_menu_item('site', $item);

	// Register podcasts JS
	$p_js = elgg_get_simplecache_url('js', 'podcasts/podcasts');
	elgg_register_simplecache_view('js/podcasts/podcasts');
	elgg_register_js('elgg.podcasts', $p_js);

	// Register podcasts CSS
	$p_css = elgg_get_simplecache_url('css', 'podcasts/css');
	elgg_register_simplecache_view('css/podcasts/css');
	elgg_register_css('elgg.podcasts', $p_css);

	// Add podcasts to owner block
	elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'podcasts_owner_block_menu');

	// Group options
	add_group_tool_option('podcasts', elgg_echo('podcasts:enablepodcasts'), true);

	// Podcasts url handler
	elgg_register_entity_url_handler('object', 'podcast', 'podcasts_url_handler');

	// Podcasts page handler
	elgg_register_page_handler('podcasts', 'podcasts_page_handler');

	// Register podcasts for notifications
	register_notification_object('object', 'podcast', elgg_echo('podcasts:newpodcast'));
	elgg_register_plugin_hook_handler('notify:entity:message', 'object', 'podcasts_notify_message');

	// Actions
	$action_path = elgg_get_plugins_path() . 'podcasts/actions/podcasts';
	elgg_register_action('podcasts/save', "$action_path/save.php");
	elgg_register_action('podcasts/delete', "$action_path/delete.php");

}

/**
 * Podcasts page handler
 *
 * URL layout
 *  All podcasts:       podcasts/all
 *  User's podcastss:   podcasts/owner/<username>
 *  Friends' podcasts:  podcasts/friends/<username>
 *  View podcast:       podcasts/view/<guid>/<title>
 *  New podcast:        podcasts/add/<guid>
 *  Edit podcast:       podcasts/edit/<guid>/<revision>
 *  Group podcasts:     podcasts/group/<guid>/all
 *
 * Title is ignored
 *
 * @param array $page
 * @return bool
 */
function podcasts_page_handler($page) {
	// Load lib
	elgg_load_library('elgg:podcasts');

	// Load JS
	elgg_load_js('elgg.podcasts');

	// Load CSS
	elgg_load_css('elgg.podcasts');

	// Push an 'all' podcasts breadcrumb
	elgg_push_breadcrumb(elgg_echo('podcast'), "podcasts/all");

	if (!isset($page[0])) {
		$page[0] = 'all';
	}

	$page_type = $page[0];
	switch ($page_type) {
		case 'owner':
			$user = get_user_by_username($page[1]);
			$params = podcasts_get_page_content_list($user->guid);
			break;
		case 'friends':
			$user = get_user_by_username($page[1]);
			$params = podcasts_get_page_content_friends($user->guid);
			break;
		case 'view':
			$params = podcasts_get_page_content_view($page[1]);
			break;
		case 'add':
			gatekeeper();
			$params = podcasts_get_page_content_edit($page_type, $page[1]);
			break;
		case 'edit':
			gatekeeper();
			$params = podcasts_get_page_content_edit($page_type, $page[1], $page[2]);
			break;
		case 'group':
			if ($page[2] == 'all') {
				$params = podcasts_get_page_content_list($page[1]);
			} else {
				$params = podcasts_get_page_content_archive($page[1], $page[3], $page[4]);
			}
			break;
		case 'all':
			$params = podcasts_get_page_content_list();
			break;
		default:
			return false;
	}

	if (isset($params['sidebar'])) {
		$params['sidebar'] .= elgg_view('podcasts/sidebar', array('page' => $page_type));
	} else {
		$params['sidebar'] = elgg_view('podcasts/sidebar', array('page' => $page_type));
	}

	$body = elgg_view_layout('content', $params);

	echo elgg_view_page($params['title'], $body);
	return true;
}

/**
 * Add podcasts menu item to an ownerblock
 */
function podcasts_owner_block_menu($hook, $type, $return, $params) {
	if (elgg_instanceof($params['entity'], 'user')) {
		$url = "podcasts/owner/{$params['entity']->username}";
		$item = new ElggMenuItem('podcast', elgg_echo('podcast'), $url);
		$return[] = $item;
	} else {
		if ($params['entity']->podcasts_enable != "no") {
			$url = "podcasts/group/{$params['entity']->guid}/all";
			$item = new ElggMenuItem('podcast', elgg_echo('podcasts:group'), $url);
			$return[] = $item;
		}
	}

	return $return;
}

/**
 * Format and return the URL for podcasts.
 *
 * @param ElggPodcast $podcast Podcast object
 * @return string URL of podcast.
 */
function podcasts_url_handler($podcast) {
	if (!$podcast->getOwnerEntity()) {
		// default to a standard view if no owner.
		return FALSE;
	}

	$friendly_title = elgg_get_friendly_title($podcast->title);

	return "podcasts/view/{$podcast->guid}/$friendly_title";
}

/**
 * Format podcasts notifications message
 *
 * @param string $hook
 * @param string $type
 * @param bool   $value
 * @param array  $params
 */
function podcasts_notify_message($hook, $type, $value, $params) {
	$entity = $params['entity'];
	$to_entity = $params['to_entity'];
	$method = $params['method'];

	if (elgg_instanceof($entity, 'object', 'podcast')) {
		$descr = $entity->description;
		$title = $entity->title;
		$owner = $entity->getOwnerEntity();
		
		return elgg_echo('podcasts:notification', array(
			$owner->name,
			$title,
			$descr,
			$entity->getURL()
		));
	}
	return null;
}