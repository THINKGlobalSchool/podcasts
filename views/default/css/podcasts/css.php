<?php
/**
 * Elgg Podcasts CSS
 *
 * @package Podcasts
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2013
 * @link http://www.thinkglobalschool.com/
 *
 */

$images_url = elgg_get_site_url() . 'mod/podcasts/graphics/';
?>

/* Podcast Entity Container */
.elgg-podcast {
	border: 1px solid #AAAAAA;
	box-shadow: inset 0 0 1px #999999;
	margin-bottom: 10px;	
	background: #FFFFFF;
}

.elgg-podcast .elgg-image-block {
	margin: 6px 0;
	padding-right: 10px;
}

.elgg-podcast-title {
	text-transform: none;
}

.elgg-podcast-description {
	border-top: 1px dotted #BBBBBB;
	padding: 4px 10px 6px;
	margin-top: 5px;
}

/* Podcast Player */
.elgg-podcast-player {
	background: none repeat scroll 0 0 #333333;
	color: #EEEEEE;
	padding: 5px;
	position: relative;
	height: 25px;
	display: none;
}

.elgg-podcast-player a {
	color: #EEEEEE;
	position:relative;
}

.elgg-podcast-player .elgg-podcast-player-buttons {
	float: left;
}

.elgg-podcast-player .elgg-podcast-player-buttons a.elgg-podcast-player-button {
	width: 24px;
	height: 24px;
	display: inline-block;
	cursor: pointer;
	background: transparent url(<?php echo $images_url; ?>button_sprites.png) no-repeat left;
}

.elgg-podcast-player .elgg-podcast-player-buttons a.elgg-podcast-player-button.elgg-podcast-player-play {
	background-position: -24px 0px;
}


.elgg-podcast-player .elgg-podcast-player-buttons a.elgg-podcast-player-button.elgg-podcast-player-play:hover {
	background-position: -24px -24px;
}

.elgg-podcast-player .elgg-podcast-player-buttons a.elgg-podcast-player-button.elgg-podcast-player-play.active,
.elgg-podcast-player .elgg-podcast-player-buttons a.elgg-podcast-player-button.elgg-podcast-player-play:active {
	background-position: -24px -48px;
}


.elgg-podcast-player .elgg-podcast-player-buttons a.elgg-podcast-player-button.elgg-podcast-player-stop {
	background-position: -48px 0px;
}

.elgg-podcast-player .elgg-podcast-player-buttons a.elgg-podcast-player-button.elgg-podcast-player-stop:hover {
	background-position: -48px -24px;
}

.elgg-podcast-player .elgg-podcast-player-buttons a.elgg-podcast-player-button.elgg-podcast-player-stop.active,
.elgg-podcast-player .elgg-podcast-player-buttons a.elgg-podcast-player-button.elgg-podcast-player-stop:active {
	background-position: -48px -48px;
}

.elgg-podcast-player .elgg-podcast-player-buttons a.elgg-podcast-player-button.elgg-podcast-player-pause {
	background-position: 0px 0px;
}

.elgg-podcast-player .elgg-podcast-player-buttons a.elgg-podcast-player-button.elgg-podcast-player-pause:hover {
	background-position: 0px -24px;
}

.elgg-podcast-player .elgg-podcast-player-buttons a.elgg-podcast-player-button.elgg-podcast-player-pause.active,
.elgg-podcast-player .elgg-podcast-player-buttons a.elgg-podcast-player-button.elgg-podcast-player-pause:active {
	background-position: 0px -48px;
}

.elgg-podcast-player .elgg-podcast-player-statusbar {
	border: 2px solid #000000;
	border-radius: 2px 2px 2px 2px;
	-moz-border-radius: 2px 2px 2px 2px;
	-webkit-border-radius: 2px 2px 2px 2px;
	cursor: -moz-grab;
	cursor: -webkit-grab;
	cursor: grab;
	height: 20px;
	margin: 0 94px 0 88px;
	overflow: hidden;
	position: relative;
}

.elgg-podcast-player .elgg-podcast-player-statusbar.dragging {
	cursor: -moz-grabbing;
	cursor: -webkit-grabbing;
	cursor: grabbing;
}

.elgg-podcast-player .elgg-podcast-player-statusbar, 
.elgg-podcast-player .elgg-podcast-player-loading {
	box-shadow: inset 0px 0px 2px #111;
	-webkit-box-shadow: inset 0px 0px 2px #111;
	-moz-box-shadow: inset 0px 0px 2px #111;
}

.elgg-podcast-player .elgg-podcast-player-statusbar .elgg-podcast-player-loading {
	background-color: #666666;

	background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#666666), to(#444444));
	background: -webkit-linear-gradient(top, #444444, #666666);
	background: -moz-linear-gradient(top, #444444, #666666);
	background: -ms-linear-gradient(top, #444444, #666666);
	background: -o-linear-gradient(top, #444444, #666666);
}

.elgg-podcast-player .elgg-podcast-player-statusbar .elgg-podcast-player-position {
	background-color:#336699;
	-webkit-background-size: 15px 15px;
	-moz-background-size: 15px 15px;
	background-size: 15px 15px; /* Controls the size of the stripes */

	box-shadow: 
		inset 0px 0px 2px #111,
		inset 0px 0px 2px #111;
	-webkit-box-shadow:
		inset 0px 0px 2px #111,
		inset 0px 0px 2px #111;
	-moz-box-shadow: 
		inset 0px 0px 2px #111,
		inset 0px 0px 2px #111;

	background-color: #ac0;
	background-image: -webkit-gradient(linear, 0 100%, 100% 0,
							color-stop(.25, rgba(255, 255, 255, .2)), color-stop(.25, transparent),
							color-stop(.5, transparent), color-stop(.5, rgba(255, 255, 255, .2)),
							color-stop(.75, rgba(255, 255, 255, .2)), color-stop(.75, transparent),
	 						to(transparent));

	background-image: -moz-linear-gradient(45deg, rgba(255, 255, 255, .2) 25%, transparent 25%,
						transparent 50%, rgba(255, 255, 255, .2) 50%, rgba(255, 255, 255, .2) 75%,
						transparent 75%, transparent);
	background-image: -ms-linear-gradient(45deg, rgba(255, 255, 255, .2) 25%, transparent 25%,
						transparent 50%, rgba(255, 255, 255, .2) 50%, rgba(255, 255, 255, .2) 75%,
						transparent 75%, transparent);
	background-image: -o-linear-gradient(45deg, rgba(255, 255, 255, .2) 25%, transparent 25%,
						transparent 50%, rgba(255, 255, 255, .2) 50%, rgba(255, 255, 255, .2) 75%,
						transparent 75%, transparent);
	background-image: linear-gradient(45deg, rgba(255, 255, 255, .2) 25%, transparent 25%,
						transparent 50%, rgba(255, 255, 255, .2) 50%, rgba(255, 255, 255, .2) 75%,
						transparent 75%, transparent);
}

.elgg-podcast-player .elgg-podcast-player-statusbar .elgg-podcast-player-loading,
.elgg-podcast-player .elgg-podcast-player-statusbar .elgg-podcast-player-position {
	position: absolute;
	left: 0px;
	top: 0px;
	width: 0px;
	height: 20px;
}

.elgg-podcast-player .elgg-podcast-player-timing {
	font: 11px courier,system;
	letter-spacing: 0;
	padding: 3px 5px;
	position: absolute;
	right: 8px;
	text-align: center;
	top: 8px;
	vertical-align: middle;
	width: auto;
}

.elgg-list-podcasts .elgg-podcast .elgg-podcast-player {

}

.elgg-list-podcasts .elgg-podcast .elgg-podcast-title {
	border-bottom: 2px solid #DDDDDD;
	padding: 5px;
}

.elgg-list-podcasts .elgg-podcast h3.elgg-podcast-title {
	background: none repeat scroll 0 0 #444444;
	border-bottom: medium none;
	padding: 5px 8px;
	text-transform: uppercase;
	-moz-box-shadow: 1px 1px 5px #000000 inset;
	-webkit-box-shadow: 1px 1px 5px #000000 inset;
	box-shadow: 1px 1px 5px #000000 inset;
}

.elgg-list-podcasts .elgg-podcast h3.elgg-podcast-title a {
	color: #FFFFFF;

}

.elgg-list-podcasts .elgg-podcast h3.elgg-podcast-title a:hover {
	text-decoration: underline;
}

/* Help/button/link styles */
.elgg-podcast-edit-button {
	display: block;
	font-size: 1em;
	margin-bottom: 20px;
}

.elgg-podcasts-subscribe-link {
	display: block;
	font-size: 0.8em;
	font-weight: bold;
	margin-bottom: 20px;
	text-transform: uppercase;
}

.elgg-podcasts-subscribe-link span {
 	display: inline-block;
 	vertical-align: bottom;
}

#podcast-edit .elgg-text-help {
	display: inline-block;
}

.elgg-podcasts-help-module {
	width: 250px;
}

/* Uploader */
.podcast-dropzone-dragover {
	-moz-box-shadow: inset 0px 0px 5px Green;
	-webkit-box-shadow: inset 0px 0px 5px Green;
	box-shadow: inset 0px 0px 5px Green;
}

div#podcast-dropzone {
	border: 2px solid #CCCCCC;
	border-radius: 5px;
	-moz-border-radius: 5px;
	-webkit-border-radius: 5px;
	width: 100%;
}

div#podcast-dropzone span {
	display: inline-block;
	padding: 6px;
}

div#podcast-dropzone .podcast-drop {
	display: block;
	font-size: 1.4em;
	font-weight: bold;
	color: #666666;
	text-align: center;
}

div#podcast-dropzone .podcast-file-size {
	color: #666666;
	font-size: 1.2em;
	margin-left: 20px;
}

div#podcast-dropzone .podcast-file-name {
	color: #333333;
	font-size: 1.2em;
	font-weight: bold;
}

div#podcast-dropzone .podcast-file-replace {
	font-size: 1.2em;
	color: #AAAAAA;
	float: right;
}

.podcasts-toggle-uploader {
	width: 100%;
}