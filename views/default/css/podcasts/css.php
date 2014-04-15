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

/* ----------------------------- PODCAST/EPISODE DISPLAY ----------------------------- */
.elgg-podcast-episode {
	border: 1px solid #AAAAAA;
	box-shadow: inset 0 0 1px #999999;
	margin-bottom: 10px;	
	background: #FFFFFF;
}

.elgg-podcast-episode .elgg-image-block {
	padding: 10px 10px 0;
}

.elgg-podcast-title, 
.elgg-podcast-summary-title {
	text-transform: none;
}

.elgg-podcast-episode-description {
	border-top: 1px dotted #BBBBBB;
	padding: 4px 10px 6px;
	margin-top: 5px;
}

.elgg-list-podcast-episodes .elgg-podcast-episode .elgg-podcast-title {
	border-bottom: 2px solid #DDDDDD;
	padding: 5px;
}

.elgg-list-podcast-episodes .elgg-podcast-episode h3.elgg-podcast-title {
	background: none repeat scroll 0 0 #444444;
	border-bottom: medium none;
	padding: 5px 8px;
	text-transform: uppercase;
	-moz-box-shadow: 1px 1px 5px #000000 inset;
	-webkit-box-shadow: 1px 1px 5px #000000 inset;
	box-shadow: 1px 1px 5px #000000 inset;
}

.elgg-list-podcast-episodes .elgg-podcast-episode h3.elgg-podcast-title a {
	color: #FFFFFF;
}

.elgg-list-podcast-episodes .elgg-podcast-episode h3.elgg-podcast-title a:hover {
	text-decoration: underline;
}

.elgg-list-podcast-episodes .elgg-podcast-episode .elgg-podcast-summary-title {
	color: #4690D6;
	font-weight: bold;
}

.elgg-list .elgg-podcast h3.elgg-podcast-title {
	font-size: 1.2em;
}

/* ------------------------------------ END DISPLAY ----------------------------------- */
/* ---------------------------------- PODCAST PLAYER ---------------------------------- */
.elgg-podcast-player {
	padding: 5px;
	position: relative;
	overflow: hidden;
}

.elgg-item .elgg-podcast-player {
	width: 100%;
}

.elgg-podcast-player .elgg-podcast-player-statusbar {
	cursor: -moz-grab;
	cursor: -webkit-grab;
	cursor: grab;
	float: left;
	height: 14px;
	width: 540px;
	position: relative;
	margin: 25px 0;
}

.elgg-podcast-player .elgg-podcast-player-background-bar {
	margin: 20px 10px 10px;
    width: 706px;
}

.elgg-podcast-player .elgg-podcast-player-statusbar.dragging {
	cursor: -moz-grabbing;
	cursor: -webkit-grabbing;
	cursor: grabbing;
}

.elgg-podcast-player .elgg-podcast-player-statusbar .elgg-podcast-player-loading,
.elgg-podcast-player .elgg-podcast-player-statusbar .elgg-podcast-player-position {
	position: absolute;
	left: 0px;
	top: 0px;
	width: 0px;
}

.elgg-podcast-player .elgg-podcast-player-statusbar .elgg-podcast-player-position {
	height: 12px;
}

.elgg-podcast-player .elgg-podcast-player-statusbar .elgg-podcast-player-loading {
	height: 14px;
}

.elgg-podcast-player .elgg-podcast-player-timing-container {
	margin: 20px 0;
	position: absolute;
	right: 5px;
	width: 100px;
}

.elgg-podcast-player .elgg-podcast-player-timing-container .elgg-podcast-player-timing {
	font-size: 11px;
	color: #FFF;
	letter-spacing: 0;
	text-align: center;
	vertical-align: middle;
	margin: 3px;
	line-height: 16px;
}

/* --- Buttons --- */
.elgg-podcast-player .elgg-podcast-player-buttons {
	width: 68px;
	height: 68px;
	float: left;
}

.elgg-podcast-player .elgg-podcast-player-buttons a.elgg-podcast-player-button {
	width: 68px;
	height: 68px;
	display: inline-block;
	cursor: pointer;
	background: transparent url(<?php echo $images_url; ?>button_sprites.png) no-repeat left;
}

.elgg-podcast-player .elgg-podcast-player-buttons a.elgg-podcast-player-button.elgg-podcast-player-play {
	background-position: 0px 0px;
}

.elgg-podcast-player .elgg-podcast-player-buttons a.elgg-podcast-player-button.elgg-podcast-player-play:hover {
	background-position: 0px -70px;
}

.elgg-podcast-player .elgg-podcast-player-buttons a.elgg-podcast-player-button.elgg-podcast-player-play.active,
.elgg-podcast-player .elgg-podcast-player-buttons a.elgg-podcast-player-button.elgg-podcast-player-play:active {
	background-position: 0px -140px;
}

.elgg-podcast-player .elgg-podcast-player-buttons a.elgg-podcast-player-button.elgg-podcast-player-pause {
	background-position: -70px 0px;
}

.elgg-podcast-player .elgg-podcast-player-buttons a.elgg-podcast-player-button.elgg-podcast-player-pause:hover {
	background-position: -70px -70px;
}

.elgg-podcast-player .elgg-podcast-player-buttons a.elgg-podcast-player-button.elgg-podcast-player-pause.active,
.elgg-podcast-player .elgg-podcast-player-buttons a.elgg-podcast-player-button.elgg-podcast-player-pause:active {
	background-position: -70px -140px;
}
/* --- End Buttons --- */

/* --- Fancy Bars (These styles simple control how the bars look, they are positioned elsewhere) --- */
.elgg-podcast-player-bar {
	/* Firefox v3.5+ */
	-moz-box-shadow:inset 1px 1px 4px rgba(0,0,0,0.65) ,inset 0px 0px 6px rgba(255,255,255,0.28);
	/* Safari v3.0+ and by Chrome v0.2+ */
	-webkit-box-shadow:inset 1px 1px 4px rgba(0,0,0,0.65) ,inset 0px 0px 6px rgba(255,255,255,0.28);
	/* Firefox v4.0+ , Safari v5.1+ , Chrome v10.0+, IE v10+ and by Opera v10.5+ */
	box-shadow:inset 1px 1px 4px rgba(0,0,0,0.65) ,inset 0px 0px 6px rgba(255,255,255,0.28);
	border-style:solid;
	/* Firefox v1.0+ */
	-moz-border-radius:0%;
	/* Safari v3.0+ and by Chrome v0.2+ */
	-webkit-border-radius:0%/3%;
	/* Firefox v4.0+ , Safari v5.0+ , Chrome v4.0+ , Opera v10.5+  and by IE v9.0+ */
	border-radius:0%/3%;
	border-width:1px;
	border-color:rgb(153,153,153);
	height:23px;
	width:299px;
	/* Firefox v3.6+ */
	background-image:-moz-linear-gradient(53% 0% 180deg,rgb(255,255,255) 0%,rgb(244,244,244) 50%,rgb(226,226,226) 50%,rgb(241,241,241) 97%,rgb(242,242,242) 100%); 
	/* safari v4.0+ and by Chrome v3.0+ */
	background-image:-webkit-gradient(linear,53% 0%,53% 100%,color-stop(0, rgb(255,255,255)),color-stop(0.5, rgb(244,244,244)),color-stop(0.5, rgb(226,226,226)),color-stop(0.97, rgb(241,241,241)),color-stop(1, rgb(242,242,242)));
	/* Chrome v10.0+ and by safari nightly build*/
	background-image:-webkit-linear-gradient(180deg,rgb(255,255,255) 0%,rgb(244,244,244) 50%,rgb(226,226,226) 50%,rgb(241,241,241) 97%,rgb(242,242,242) 100%);
	/* Opera v11.10+ */
	background-image:-o-linear-gradient(180deg,rgb(255,255,255) 0%,rgb(244,244,244) 50%,rgb(226,226,226) 50%,rgb(241,241,241) 97%,rgb(242,242,242) 100%);
	/* IE v10+ */
	background-image:-ms-linear-gradient(180deg,rgb(255,255,255) 0%,rgb(244,244,244) 50%,rgb(226,226,226) 50%,rgb(241,241,241) 97%,rgb(242,242,242) 100%);
	background-image:linear-gradient(180deg,rgb(255,255,255) 0%,rgb(244,244,244) 50%,rgb(226,226,226) 50%,rgb(241,241,241) 97%,rgb(242,242,242) 100%);
	-ms-filter:"progid:DXImageTransform.Microsoft.gradient(startColorstr=#ffffffff,endColorstr=#fff2f2f2,GradientType=0)";
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#ffffffff,endColorstr=#fff2f2f2,GradientType=0);
}

/* Inner status bar */
.elgg-podcast-player-inner-bar {
	/* Firefox v3.5+ */
	-moz-box-shadow:inset 0px 0px 4px rgba(0,0,0,0.65);
	/* Safari v3.0+ and by Chrome v0.2+ */
	-webkit-box-shadow:inset 0px 0px 4px rgba(0,0,0,0.65);
	/* Firefox v4.0+ , Safari v5.1+ , Chrome v10.0+, IE v10+ and by Opera v10.5+ */
	box-shadow:inset 0px 0px 4px rgba(0,0,0,0.65);
	border-style:solid;
	/* Firefox v1.0+ */
	-moz-border-radius:0%;
	/* Safari v3.0+ and by Chrome v0.2+ */
	-webkit-border-radius:0%/6%;
	/* Firefox v4.0+ , Safari v5.0+ , Chrome v4.0+ , Opera v10.5+  and by IE v9.0+ */
	border-radius:0%/6%;
	border-width:1px;
	border-color:rgb(125,135,145);
	height:13px;
	width:253px;
	background-color:rgb(255,255,255);
}

/* Timing Bar */
.elgg-podcast-player-timing-bar {
	border-style:solid;
	border-width:1px;
	border-color:rgb(16,29,39);
	height:16px;
	width:92px;
	/* Firefox v3.6+ */
	background-image:-moz-linear-gradient(13% 0% 180deg,rgb(23,44,61) 0%,rgb(23,44,61) 50%,rgb(13,22,29) 50%,rgb(20,36,50) 98%,rgb(20,37,51) 100%); 
	/* safari v4.0+ and by Chrome v3.0+ */
	background-image:-webkit-gradient(linear,13% 0%,13% 79%,color-stop(0, rgb(23,44,61)),color-stop(0.5, rgb(23,44,61)),color-stop(0.5, rgb(13,22,29)),color-stop(0.98, rgb(20,36,50)),color-stop(1, rgb(20,37,51)));
	/* Chrome v10.0+ and by safari nightly build*/
	background-image:-webkit-linear-gradient(180deg,rgb(23,44,61) 0%,rgb(23,44,61) 50%,rgb(13,22,29) 50%,rgb(20,36,50) 98%,rgb(20,37,51) 100%);
	/* Opera v11.10+ */
	background-image:-o-linear-gradient(180deg,rgb(23,44,61) 0%,rgb(23,44,61) 50%,rgb(13,22,29) 50%,rgb(20,36,50) 98%,rgb(20,37,51) 100%);
	/* IE v10+ */
	background-image:-ms-linear-gradient(180deg,rgb(23,44,61) 0%,rgb(23,44,61) 50%,rgb(13,22,29) 50%,rgb(20,36,50) 98%,rgb(20,37,51) 100%);
	background-image:linear-gradient(180deg,rgb(23,44,61) 0%,rgb(23,44,61) 50%,rgb(13,22,29) 50%,rgb(20,36,50) 98%,rgb(20,37,51) 100%);
	/* Firefox v3.5+ */
	-moz-box-shadow:0px 0px 1px 0px rgba(0,0,0,0.75) ,inset 0px 0px 0px 2px rgba(255,255,255,0.1);
	/* Safari v3.0+ and by Chrome v0.2+ */
	-webkit-box-shadow:0px 0px 1px 0px rgba(0,0,0,0.75) ,inset 0px 0px 0px 2px rgba(255,255,255,0.1);
	/* Firefox v4.0+ , Safari v5.1+ , Chrome v10.0+, IE v10+ and by Opera v10.5+ */
	box-shadow:0px 0px 1px 0px rgba(0,0,0,0.75) ,inset 0px 0px 0px 2px rgba(255,255,255,0.1);
	-ms-filter:"progid:DXImageTransform.Microsoft.dropshadow(OffX = 0,OffY = 0,Color = #bf000000,Positive = true)
	progid:DXImageTransform.Microsoft.gradient(startColorstr=#ff172c3d,endColorstr=#ff142533,GradientType=0)";
	filter:progid:DXImageTransform.Microsoft.dropshadow(OffX = 0,OffY = 0,Color = #bf000000,Positive = true)
	progid:DXImageTransform.Microsoft.gradient(startColorstr=#ff172c3d,endColorstr=#ff142533,GradientType=0);
}

/* Loading Bar */
.elgg-podcast-player-loading-bar {
	/* Firefox v3.5+ */
	-moz-box-shadow:inset 0px 0px 4px rgba(0,0,0,0.65);
	/* Safari v3.0+ and by Chrome v0.2+ */
	-webkit-box-shadow:inset 0px 0px 4px rgba(0,0,0,0.65);
	/* Firefox v4.0+ , Safari v5.1+ , Chrome v10.0+, IE v10+ and by Opera v10.5+ */
	box-shadow:inset 0px 0px 4px rgba(0,0,0,0.65);
	background-color:rgb(238,238,238);
}

/* Position bar */
.elgg-podcast-player-position-bar {
	background-color:rgb(0,84,167);
	width:199px;
	height:13px;
	border-color:rgb(125,135,145);
	border-width:1px;
	/* Firefox v1.0+ */
	-moz-border-radius:0%;
	/* Safari v3.0+ and by Chrome v0.2+ */
	-webkit-border-radius:0%/6%;
	/* Firefox v4.0+ , Safari v5.0+ , Chrome v4.0+ , Opera v10.5+  and by IE v9.0+ */
	border-radius:0%/6%;
	border-style:solid;
	/* Firefox v3.5+ */
	-moz-box-shadow:inset 0px 0px 4px rgba(0,0,0,0.65);
	/* Safari v3.0+ and by Chrome v0.2+ */
	-webkit-box-shadow:inset 0px 0px 4px rgba(0,0,0,0.65);
	/* Firefox v4.0+ , Safari v5.1+ , Chrome v10.0+, IE v10+ and by Opera v10.5+ */
	box-shadow:inset 0px 0px 4px rgba(0,0,0,0.65);
}

/* --- End Fancy Bars --- */

/* ---------------------------------- END PLAYER ---------------------------------- */
/* ----------------------------------- UPLOADER ----------------------------------- */
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

#podcast-upload-dialog {
	display: none;
	height: 100px;
}

/* Fix overlay */
.ui-widget-overlay { 
	background: #000000 !important;
}
/* --------------------------------- END UPLOADER --------------------------------- */
/* ------------------------------------- MISC ------------------------------------- */
.elgg-menu-item-subscribe-link {
	top: 2px;
}
.elgg-podcast-edit-button {
	display: block;
	font-size: 1em;
	margin-bottom: 20px;
}

.elgg-podcasts-subscribe-link {
	display: block;
	font-size: 11px;
	font-weight: bold;
	line-height: 17px;
	margin-bottom: 20px;
	text-transform: uppercase;
}

.elgg-podcasts-subscribe-link span {
 	display: inline-block;
 	vertical-align: bottom;
 	margin-right: 5px;
}

#podcast-edit .elgg-text-help {
	display: inline-block;
}

.elgg-podcasts-help-module {
	width: 250px;
}
/* ----------------------------------- END MISC ----------------------------------- */