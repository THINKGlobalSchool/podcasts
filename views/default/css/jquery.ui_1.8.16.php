<?php
/**
 * Elgg Podcasts jquery ui css (1.8.16) simplecache view
 *
 * @package Podcasts
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2013
 * @link http://www.thinkglobalschool.com/
 *
 */

$css_path = elgg_get_config('path');
$css_path = "{$css_path}mod/podcasts/vendors/jquery-ui-css/jquery-ui-1.8.16.css";

$graphics_path = elgg_get_site_url() . 'mod/podcasts/graphics/jquery-ui/';

ob_start();
include $css_path;
$contents = ob_get_clean();
$contents = str_replace('images/', $graphics_path, $contents);
echo $contents; 