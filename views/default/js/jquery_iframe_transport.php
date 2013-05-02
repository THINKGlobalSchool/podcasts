<?php
/**
 * Elgg Podcasts jquery iframe transport simplecache view
 *
 * @package Podcasts
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2013
 * @link http://www.thinkglobalschool.com/
 *
 */
$js_path = elgg_get_config('path');

$iframe_transport = "{$js_path}mod/podcasts/vendors/jquery-file-upload/jquery.iframe-transport.js";

include $iframe_transport;