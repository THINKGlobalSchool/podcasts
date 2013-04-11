<?php
/**
 * Elgg Podcasts Podcast Class
 *
 * @package Podcasts
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2013
 * @link http://www.thinkglobalschool.com/
 *
 * @todo Add all the useful things
 */

class ElggPodcast extends ElggFile {
	/**
	 * Set subtype to podcast
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['subtype'] = "podcast";
	}

	/**
	 * Placeholder
	 */
	public function __construct($guid = null) {
		parent::__construct($guid);
	}
}