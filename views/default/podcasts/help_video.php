<?php
/**
 * Elgg Podcasts Help Video
 *
 * @package Podcasts
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2013
 * @link http://www.thinkglobalschool.com/
 *
 */

$content = <<<HTML
<div id='podcast-help-video'>
	<object width="420" height="315">
		<param name="movie" value="http://www.youtube.com/v/j7V-CBgpsmI?hl=en_US&amp;version=3"></param>
		<param name="allowFullScreen" value="true"></param>
		<param name="allowscriptaccess" value="always"></param>
		<embed src="http://www.youtube.com/v/j7V-CBgpsmI?hl=en_US&amp;version=3" type="application/x-shockwave-flash" width="420" height="315" allowscriptaccess="always" allowfullscreen="true"></embed>
	</object>
</div>
HTML;
echo $content;