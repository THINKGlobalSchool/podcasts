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
 * - Widgets
 * - Better uploader
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
	$js = elgg_get_simplecache_url('js', 'podcasts/podcasts');
	elgg_register_simplecache_view('js/podcasts/podcasts');
	elgg_register_js('elgg.podcasts', $js);

	// Register podcasts uploader JS
	$js = elgg_get_simplecache_url('js', 'podcasts/uploader');
	elgg_register_simplecache_view('js/podcasts/uploader');
	elgg_register_js('elgg.podcasts.uploader', $js);

	// Register podcasts JS
	$js = elgg_get_simplecache_url('js', 'soundmanager2');
	elgg_register_simplecache_view('js/soundmanager2');
	elgg_register_js('soundmanager2', $js);

	// Register podcasts CSS
	$css = elgg_get_simplecache_url('css', 'podcasts/css');
	elgg_register_simplecache_view('css/podcasts/css');
	elgg_register_css('elgg.podcasts', $css);

	// Register jquery ui widget (for jquery file upload)
	$js = elgg_get_simplecache_url('js', 'jquery_ui_widget');
	elgg_register_simplecache_view('js/jquery_ui_widget');
	elgg_register_js('jquery.ui.widget', $js);
	
	// Register JS File Upload
	$js = elgg_get_simplecache_url('js', 'jquery_file_upload');
	elgg_register_simplecache_view('js/jquery_file_upload');
	elgg_register_js('jquery-file-upload', $js);

	// Register JS jquery.iframe-transport (for jquery-file-upload)
	$js = elgg_get_simplecache_url('js', 'jquery_iframe_transport');
	elgg_register_simplecache_view('js/jquery_iframe_transport');
	elgg_register_js('jquery.iframe-transport', $js);

	// Pagesetup event handler
	elgg_register_event_handler('pagesetup','system','podcasts_pagesetup');

	// Add podcasts to owner block
	elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'podcasts_owner_block_menu');

	// Modify extras menu
	elgg_register_plugin_hook_handler('register', 'menu:extras', 'podcasts_extras_menu');

	// Group options
	add_group_tool_option('podcasts', elgg_echo('podcasts:enablepodcasts'), true);

	// Podcasts url handler
	elgg_register_entity_url_handler('object', 'podcast', 'podcasts_url_handler');

	// Podcasts page handler
	elgg_register_page_handler('podcasts', 'podcasts_page_handler');

	// Register podcasts for notifications
	register_notification_object('object', 'podcast', elgg_echo('podcasts:newpodcast'));
	elgg_register_plugin_hook_handler('notify:entity:message', 'object', 'podcasts_notify_message');

	// Hook into entity menu for podcasts
	elgg_register_plugin_hook_handler('register', 'menu:entity', 'podcasts_setup_entity_menu');

	// Hook into filter menu for podcasts
	elgg_register_plugin_hook_handler('register', 'menu:filter', 'podcasts_setup_filter_menu');

	// Register for view plugin hook to override rss page/default view
	elgg_register_plugin_hook_handler('view', 'page/default', 'podcasts_rss_page_view_handelr');

	// Extend group settings
	elgg_extend_view('groups/edit', 'forms/podcasts/groupsettings', 1000);

	// Actions
	$action_path = elgg_get_plugins_path() . 'podcasts/actions/podcasts';
	elgg_register_action('podcasts/save', "$action_path/save.php");
	elgg_register_action('podcasts/delete', "$action_path/delete.php");
	elgg_register_action("podcasts/usersettings", "$action_path/usersettings.php");
	elgg_register_action("podcasts/groupsettings", "$action_path/groupsettings.php");

	// Ajax Whitelist
	elgg_register_ajax_view('podcasts/help_video');
}

/**
 * Podcasts page handler
 *
 * URL layout
 *  All podcasts:          podcasts/all
 *  User's podcastss:      podcasts/owner/<username>
 *  Friends' podcasts:     podcasts/friends/<username>
 *  View podcast:          podcasts/view/<guid>/<title>
 *  New podcast:           podcasts/add/<guid>
 *  Edit podcast:          podcasts/edit/<guid>/<revision>
 *  Group podcasts:        podcasts/group/<guid>/all
 *  Download podcast:      podcasts/download/<guid>
 *  Serve podcast:         podcasts/serve/<guid>
 *  User podcast settings: podcasts/settings
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
	elgg_load_js('soundmanager2');

	// Load CSS
	elgg_load_css('elgg.podcasts');

	// Push an 'all' podcasts breadcrumb
	elgg_push_breadcrumb(elgg_echo('podcasts'), "podcasts/all");

	// Pages dir
	$pages_dir = elgg_get_plugins_path() . 'podcasts/pages/podcasts';

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
		case 'download':
			set_input('guid', $page[1]);
			include "$pages_dir/download.php";
			break;
		case 'serve':
			set_input('guid', $page[1]);
			include "$pages_dir/serve.php";
			break;
		case 'settings':
			if (!elgg_is_logged_in()) {
				forward(REFERER);
			}
			$username = $page[1];

			if (!$username) {
				$user = elgg_get_logged_in_user_entity();
			} else {
				$user = get_user_by_username($username);
			}

			if ($user && $user->canEdit()) {
				elgg_set_page_owner_guid($user->guid);
				$params = podcasts_get_user_settings_content();
			} else {
				forward('podcasts/settings/' . elgg_get_logged_in_user_entity()->username);
			}
			break;
		default:
			return false;
	}

	if (isset($params['sidebar'])) {
		$params['sidebar'] .= elgg_view('podcasts/sidebar', array('page' => $page_type));
	} else {
		$params['sidebar'] = elgg_view('podcasts/sidebar', array('page' => $page_type));
	}

	$body = elgg_view_layout($params['layout'] ? $params['layout'] : 'content' , $params);

	// Passing additional description thru vars
	echo elgg_view_page($params['title'], $body, 'default', array('description' => $params['feed_description']));
	return true;
}

// Pagesetup hook
function podcasts_pagesetup($a, $b, $c) {
	// User settings
	if (elgg_in_context('settings') && elgg_get_logged_in_user_guid()) {
		$user = elgg_get_logged_in_user_entity();

		$params = array(
			'name' => 'podcasts_settings',
			'text' => elgg_echo('podcasts:title:usersettings'),
			'href' => "podcasts/settings/{$user->username}",
		);
		elgg_register_menu_item('page', $params);
	}
}

/**
 * Add podcasts menu item to ownerblock menu
 *
 * @param string $hook
 * @param string $type
 * @param bool   $value
 * @param array  $params
 */
function podcasts_owner_block_menu($hook, $type, $value, $params) {
	if (elgg_instanceof($params['entity'], 'user')) {
		$url = "podcasts/owner/{$params['entity']->username}";
		$item = new ElggMenuItem('podcast', elgg_echo('podcast'), $url);
		$value[] = $item;
	} else {
		if ($params['entity']->podcasts_enable != "no") {
			$url = "podcasts/group/{$params['entity']->guid}/all";
			$item = new ElggMenuItem('podcast', elgg_echo('podcasts:group'), $url);
			$value[] = $item;
		}
	}

	return $value;
}

/**
 * Modify extras menu
 *
 * @param string $hook
 * @param string $type
 * @param bool   $value
 * @param array  $params
 */
function podcasts_extras_menu($hook, $type, $value, $params) {
	// Remove RSS
	foreach ($value as $k => $item) {
		if ($item->getName() == 'rss') {
			unset($value[$k]);
		}
	}

	return $value;
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

/**
 * Add items to the podcast entity menu
 *
 * @param string $hook
 * @param string $type
 * @param bool   $value
 * @param array  $params
 *
 * @return array
 */
function podcasts_setup_entity_menu($hook, $type, $value, $params) {
	$entity = $params['entity'];
	
	if (!elgg_instanceof($entity, 'object', 'podcast')) {
		return $value;
	}
	
	// Download link
	$options = array(
		'name' => 'podcasts_download',
		'text' => elgg_echo('podcasts:download'),
		'encode_text' => false,
		'href' => $entity->getDownloadURL(),
	);

	$value[] = ElggMenuItem::factory($options);

	return $value;
}

/**
 * Modify items on the podcasts filter menu
 *
 * @param string $hook
 * @param string $type
 * @param bool   $value
 * @param array  $params
 *
 * @return array
 */
function podcasts_setup_filter_menu($hook, $type, $value, $params) {
	if (elgg_in_context('podcasts')) {
		foreach ($value as $item) {
			if ($item->getName() == 'all') {
				$item->setText(elgg_echo('podcasts:filter:allepisodes'));
			}

			if ($item->getName() == 'mine') {
				$item->setText(elgg_echo('podcasts:filter:mypodcast'));
			}

			if ($item->getName() == 'friend') {
				$item->setText(elgg_echo('podcasts:filter:friendsepisodes'));
			}
		}
	}
	return $value;
}

/**
 * Plugin hook handler intercept rss/page/default
 *
 * @param string $hook
 * @param string $type
 * @param bool   $value
 * @param array  $params
 * 
 * @return array
 */
function podcasts_rss_page_view_handelr($hook, $type, $value, $params) {
	if (elgg_get_viewtype() == 'rss' && elgg_in_context('podcasts')) {
		$value = elgg_view('page/podcast', $params['vars']);
	}
	
	return $value;
}