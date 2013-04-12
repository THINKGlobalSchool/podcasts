<?php
/**
 * Skin for jPlayer Plugin (jQuery JavaScript Library)
 * http://www.jplayer.org
 *
 * Skin Name: Blue Monday
 *
 * Copyright (c) 2010-2012 Happyworm Ltd
 * Dual licensed under the MIT and GPL licenses.
 *  - http://www.opensource.org/licenses/mit-license.php
 *  - http://www.gnu.org/copyleft/gpl.html
 *
 * Author: Silvia Benvenuti
 * Skin Version: 4.2 (jPlayer 2.2.0)
 * Date: 22nd October 2012
 */

$img_path = elgg_normalize_url('mod/podcasts/vendors/jplayer/skins/bluemonday/');

?>

div.jp-audio,
div.jp-audio-stream,
div.jp-video {

	/* Edit the font-size to counteract inherited font sizing.
	 * Eg. 1.25em = 1 / 0.8em
	 */

	font-size:1.25em; /* 1.25em for testing in site pages */ /* No parent CSS that can effect the size in the demos ZIP */

	font-family:Verdana, Arial, sans-serif;
	line-height:1.6;
	color: #666;
	border:1px solid #009be3;
	background-color:#eee;
}
div.jp-audio {
	width:420px;
}
div.jp-audio-stream {
	width:182px;
}
div.jp-video-270p {
	width:480px;
}
div.jp-video-360p {
	width:640px;
}
div.jp-video-full {
	/* Rules for IE6 (full-screen) */
	width:480px;
	height:270px;
	/* Rules for IE7 (full-screen) - Otherwise the relative container causes other page items that are not position:static (default) to appear over the video/gui. */
	position:static !important; position:relative
}

/* The z-index rule is defined in this manner to enable Popcorn plugins that add overlays to video area. EG. Subtitles. */
div.jp-video-full div div {
	z-index:1000;
}

div.jp-video-full div.jp-jplayer {
	top: 0;
	left: 0;
	position: fixed !important; position: relative; /* Rules for IE6 (full-screen) */
	overflow: hidden;
}

div.jp-video-full div.jp-gui {
	position: fixed !important; position: static; /* Rules for IE6 (full-screen) */
	top: 0;
	left: 0;
	width:100%;
	height:100%;
	z-index:1001; /* 1 layer above the others. */
}

div.jp-video-full div.jp-interface {
	position: absolute !important; position: relative; /* Rules for IE6 (full-screen) */
	bottom: 0;
	left: 0;
}

div.jp-interface {
	position: relative;
	background-color:#eee;
	width:100%;
}

div.jp-audio div.jp-type-single div.jp-interface {
	height:80px;
}
div.jp-audio div.jp-type-playlist div.jp-interface {
	height:80px;
}

div.jp-audio-stream div.jp-type-single div.jp-interface {
	height:80px;
}

div.jp-video div.jp-interface {
	border-top:1px solid #009be3;
}

/* @group CONTROLS */

div.jp-controls-holder {
	clear: both;
	width:440px;
	margin:0 auto;
	position: relative;
	overflow:hidden;
	top:-8px; /* This negative value depends on the size of the text in jp-currentTime and jp-duration */
}

div.jp-interface ul.jp-controls {
	list-style-type:none;
	margin:0;
	padding: 0;
	overflow:hidden;
}

div.jp-audio ul.jp-controls {
	width: 380px;
	padding:20px 20px 0 20px;
}

div.jp-audio-stream ul.jp-controls {
	width: 142px;
	padding:20px 20px 0 20px;
}

div.jp-video div.jp-type-single ul.jp-controls {
	width: 78px;
	margin-left: 200px;
}

div.jp-video div.jp-type-playlist ul.jp-controls {
	width: 134px;
	margin-left: 172px;
}
div.jp-video ul.jp-controls,
div.jp-interface ul.jp-controls li {
	display:inline;
	float: left;
}

div.jp-interface ul.jp-controls a {
	display:block;
	overflow:hidden;
	text-indent:-9999px;
}
a.jp-play,
a.jp-pause {
	width:40px;
	height:40px;
}

a.jp-play {
	background: url("<?php echo $img_path; ?>jplayer.blue.monday.jpg") 0 0 no-repeat;
}
a.jp-play:hover {
	background: url("<?php echo $img_path; ?>jplayer.blue.monday.jpg") -41px 0 no-repeat;
}
a.jp-pause {
	background: url("<?php echo $img_path; ?>jplayer.blue.monday.jpg") 0 -42px no-repeat;
	display: none;
}
a.jp-pause:hover {
	background: url("<?php echo $img_path; ?>jplayer.blue.monday.jpg") -41px -42px no-repeat;
}

a.jp-stop, a.jp-previous, a.jp-next {
	width:28px;
	height:28px;
	margin-top:6px;
}

a.jp-stop {
	background: url("<?php echo $img_path; ?>jplayer.blue.monday.jpg") 0 -83px no-repeat;
	margin-left:10px;
}

a.jp-stop:hover {
	background: url("<?php echo $img_path; ?>jplayer.blue.monday.jpg") -29px -83px no-repeat;
}

a.jp-previous {
	background: url("<?php echo $img_path; ?>jplayer.blue.monday.jpg") 0 -112px no-repeat;
}
a.jp-previous:hover {
	background: url("<?php echo $img_path; ?>jplayer.blue.monday.jpg") -29px -112px no-repeat;
}

a.jp-next {
	background: url("<?php echo $img_path; ?>jplayer.blue.monday.jpg") 0 -141px no-repeat;
}
a.jp-next:hover {
	background: url("<?php echo $img_path; ?>jplayer.blue.monday.jpg") -29px -141px no-repeat;
}

/* @end */

/* @group progress bar */

div.jp-progress {
	overflow:hidden;
	background-color: #ddd;
}
div.jp-audio div.jp-progress {
	position: absolute;
	top:32px;
	height:15px;
}
div.jp-audio div.jp-type-single div.jp-progress {
	left:110px;
	width:186px;
}
div.jp-audio div.jp-type-playlist div.jp-progress {
	left:166px;
	width:130px;
}
div.jp-video div.jp-progress {
	top:0px;
	left:0px;
	width:100%;
	height:10px;
}
div.jp-seek-bar {
	background: url("<?php echo $img_path; ?>jplayer.blue.monday.jpg") 0 -202px repeat-x;
	width:0px;
	height:100%;
	cursor: pointer;
}
div.jp-play-bar {
	background: url("<?php echo $img_path; ?>jplayer.blue.monday.jpg") 0 -218px repeat-x ;
	width:0px;
	height:100%;
}

/* The seeking class is added/removed inside jPlayer */
div.jp-seeking-bg {
	background: url("<?php echo $img_path; ?>jplayer.blue.monday.seeking.gif");
}

/* @end */

/* @group volume controls */


a.jp-mute,
a.jp-unmute,
a.jp-volume-max {
	width:18px;
	height:15px;
	margin-top:12px;
}

div.jp-audio div.jp-type-single a.jp-mute,
div.jp-audio div.jp-type-single a.jp-unmute {
	margin-left: 210px;	
}
div.jp-audio div.jp-type-playlist a.jp-mute,
div.jp-audio div.jp-type-playlist a.jp-unmute {
	margin-left: 154px;
}

div.jp-audio-stream div.jp-type-single a.jp-mute,
div.jp-audio-stream div.jp-type-single a.jp-unmute {
	margin-left:10px;
}

div.jp-audio a.jp-volume-max,
div.jp-audio-stream a.jp-volume-max {
	margin-left: 56px;	
}

div.jp-video a.jp-mute,
div.jp-video a.jp-unmute,
div.jp-video a.jp-volume-max {
	position: absolute;
	top:12px;
	margin-top:0;
}

div.jp-video a.jp-mute,
div.jp-video a.jp-unmute {
	left: 50px;
}

div.jp-video a.jp-volume-max {
	left: 134px;
}

a.jp-mute {
	background: url("<?php echo $img_path; ?>jplayer.blue.monday.jpg") 0 -170px no-repeat;
}
a.jp-mute:hover {
	background: url("<?php echo $img_path; ?>jplayer.blue.monday.jpg") -19px -170px no-repeat;
}
a.jp-unmute {
	background: url("<?php echo $img_path; ?>jplayer.blue.monday.jpg") -60px -170px no-repeat;
	display: none;
}
a.jp-unmute:hover {
	background: url("<?php echo $img_path; ?>jplayer.blue.monday.jpg") -79px -170px no-repeat;
}
a.jp-volume-max {
	background: url("<?php echo $img_path; ?>jplayer.blue.monday.jpg") 0 -186px no-repeat;
}
a.jp-volume-max:hover {
	background: url("<?php echo $img_path; ?>jplayer.blue.monday.jpg") -19px -186px no-repeat;
}

div.jp-volume-bar {
	position: absolute;
	overflow:hidden;
	background: url("<?php echo $img_path; ?>jplayer.blue.monday.jpg") 0 -250px repeat-x;
	width:46px;
	height:5px;
	cursor: pointer;
}
div.jp-audio div.jp-volume-bar {
	top:37px;
	left:330px;
}
div.jp-audio-stream div.jp-volume-bar {
	top:37px;
	left:92px;
}
div.jp-video div.jp-volume-bar {
	top:17px;
	left:72px;
}
div.jp-volume-bar-value {
	background: url("<?php echo $img_path; ?>jplayer.blue.monday.jpg") 0 -256px repeat-x;
	width:0px;
	height:5px;
}

/* @end */

/* @group current time and duration */

div.jp-audio div.jp-time-holder {
	position:absolute;
	top:50px;
}
div.jp-audio div.jp-type-single div.jp-time-holder {
	left:110px;
	width:186px;
}
div.jp-audio div.jp-type-playlist div.jp-time-holder {
	left:166px;
	width:130px;
}

div.jp-current-time,
div.jp-duration {
	width:60px;
	font-size:.64em;
	font-style:oblique;
}
div.jp-current-time {
	float: left;
	display:inline;
}
div.jp-duration {
	float: right;
	display:inline;
	text-align: right;
}

div.jp-video div.jp-current-time {
	margin-left:20px;
}
div.jp-video div.jp-duration {
	margin-right:20px;
}

/* @end */

/* @group playlist */

div.jp-title {
	font-weight:bold;
	text-align:center;
}

div.jp-title,
div.jp-playlist {
	width:100%;
	background-color:#ccc;
	border-top:1px solid #009be3;
}
div.jp-type-single div.jp-title,
div.jp-type-playlist div.jp-title,
div.jp-type-single div.jp-playlist {
	border-top:none;
}
div.jp-title ul,
div.jp-playlist ul {
	list-style-type:none;
	margin:0;
	padding:0 20px;
	font-size:.72em;
}

div.jp-title li {
	padding:5px 0;
	font-weight:bold;
}
div.jp-playlist li {
	padding:5px 0 4px 20px;
	border-bottom:1px solid #eee;
}

div.jp-playlist li div {
	display:inline;
}

/* Note that the first-child (IE6) and last-child (IE6/7/8) selectors do not work on IE */

div.jp-type-playlist div.jp-playlist li:last-child {
	padding:5px 0 5px 20px;
	border-bottom:none;
}
div.jp-type-playlist div.jp-playlist li.jp-playlist-current {
	list-style-type:square;
	list-style-position:inside;
	padding-left:7px;
}
div.jp-type-playlist div.jp-playlist a {
	color: #333;
	text-decoration: none;
}
div.jp-type-playlist div.jp-playlist a:hover {
	color:#0d88c1;
}
div.jp-type-playlist div.jp-playlist a.jp-playlist-current {
	color:#0d88c1;
}

div.jp-type-playlist div.jp-playlist a.jp-playlist-item-remove {
	float:right;
	display:inline;
	text-align:right;
	margin-right:10px;
	font-weight:bold;
	color:#666;
}
div.jp-type-playlist div.jp-playlist a.jp-playlist-item-remove:hover {
	color:#0d88c1;
}
div.jp-type-playlist div.jp-playlist span.jp-free-media {
	float:right;
	display:inline;
	text-align:right;
	margin-right:10px;
}
div.jp-type-playlist div.jp-playlist span.jp-free-media a{
	color:#666;
}
div.jp-type-playlist div.jp-playlist span.jp-free-media a:hover{
	color:#0d88c1;
}
span.jp-artist {
	font-size:.8em;
	color:#666;
}

/* @end */

div.jp-video-play {
	width:100%;
	overflow:hidden; /* Important for nested negative margins to work in modern browsers */
	cursor:pointer;
	background-color:rgba(0,0,0,0); /* Makes IE9 work with the active area over the whole video area. IE6/7/8 only have the button as active area. */
}
div.jp-video-270p div.jp-video-play {
	margin-top:-270px;
	height:270px;
}
div.jp-video-360p div.jp-video-play {
	margin-top:-360px;
	height:360px;
}
div.jp-video-full div.jp-video-play {
	height:100%;
}
a.jp-video-play-icon {
	position:relative;
	display:block;
	width: 112px;
	height: 100px;

	margin-left:-56px;
	margin-top:-50px;
	left:50%;
	top:50%;

	background: url("<?php echo $img_path; ?>jplayer.blue.monday.video.play.png") 0 0 no-repeat;
	text-indent:-9999px;
}
div.jp-video-play:hover a.jp-video-play-icon {
	background: url("<?php echo $img_path; ?>jplayer.blue.monday.video.play.png") 0 -100px no-repeat;
}





div.jp-jplayer audio,
div.jp-jplayer {
	width:0px;
	height:0px;
}

div.jp-jplayer {
	background-color: #000000;
}





/* @group TOGGLES */

/* The audio toggles are nested inside jp-time-holder */

ul.jp-toggles {
	list-style-type:none;
	padding:0;
	margin:0 auto;
	overflow:hidden;
}

div.jp-audio .jp-type-single ul.jp-toggles {
	width:25px;
}
div.jp-audio .jp-type-playlist ul.jp-toggles {
	width:55px;
	margin: 0;
	position: absolute;
	left: 325px;
	top: 50px;
}

div.jp-video ul.jp-toggles {
	margin-top:10px;
	width:100px;
}

ul.jp-toggles li {
	display:block;
	float:right;
}

ul.jp-toggles li a {
	display:block;
	width:25px;
	height:18px;
	text-indent:-9999px;
	line-height:100%; /* need this for IE6 */
}

a.jp-full-screen {
	background: url("<?php echo $img_path; ?>jplayer.blue.monday.jpg") 0 -310px no-repeat;
	margin-left: 20px;
}

a.jp-full-screen:hover {
	background: url("<?php echo $img_path; ?>jplayer.blue.monday.jpg") -30px -310px no-repeat;
}

a.jp-restore-screen {
	background: url("<?php echo $img_path; ?>jplayer.blue.monday.jpg") -60px -310px no-repeat;
	margin-left: 20px;
}

a.jp-restore-screen:hover {
	background: url("<?php echo $img_path; ?>jplayer.blue.monday.jpg") -90px -310px no-repeat;
}

a.jp-repeat {
	background: url("<?php echo $img_path; ?>jplayer.blue.monday.jpg") 0 -290px no-repeat;
}

a.jp-repeat:hover {
	background: url("<?php echo $img_path; ?>jplayer.blue.monday.jpg") -30px -290px no-repeat;
}

a.jp-repeat-off {
	background: url("<?php echo $img_path; ?>jplayer.blue.monday.jpg") -60px -290px no-repeat;
}

a.jp-repeat-off:hover {
	background: url("<?php echo $img_path; ?>jplayer.blue.monday.jpg") -90px -290px no-repeat;
}

a.jp-shuffle {
	background: url("<?php echo $img_path; ?>jplayer.blue.monday.jpg") 0 -270px no-repeat;
	margin-left: 5px;
}

a.jp-shuffle:hover {
	background: url("<?php echo $img_path; ?>jplayer.blue.monday.jpg") -30px -270px no-repeat;
}

a.jp-shuffle-off {
	background: url("<?php echo $img_path; ?>jplayer.blue.monday.jpg") -60px -270px no-repeat;
	margin-left: 5px;
}

a.jp-shuffle-off:hover {
	background: url("<?php echo $img_path; ?>jplayer.blue.monday.jpg") -90px -270px no-repeat;
}


/* @end */

/* @group NO SOLUTION error feedback */

.jp-no-solution {
	padding:5px;
	font-size:.8em;
	background-color:#eee;
	border:2px solid #009be3;
	color:#000;
	display:none;
}

.jp-no-solution a {
	color:#000;
}

.jp-no-solution span {
	font-size:1em;
	display:block;
	text-align:center;
	font-weight:bold;
}

/* @end */
