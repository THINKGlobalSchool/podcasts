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
 * @todo
 * - Get audio length
 * - anything else needed to playback this file
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
	 * Save the podcast
	 *
	 * @param array $data File data to upload, if null then we're just setting attribute
	 * @return bool
	 *
	 * @throws InvalidPodcastFileException|IOException
	 */
	public function save($data = NULL) {
		if (!parent::save()) {
			return FALSE;
		}

		// New Podcast File Data
		if ($data) {
			// Remove old file if it exists
			$old_file = $this->getFilenameOnFilestore();
			if (file_exists($old_file) && !is_dir($old_file)) {
				unlink($old_file);
			}

			$this->simpletype = "audio";

			$mime_type = ElggPodcast::detectMimeType($data['tmp_name'], $data['type']);

			if (ElggPodcast::checkValidMimeType($mime_type)) {
				$this->setMimeType($mime_type);
			} else {
				$ex = elgg_echo('InvalidPodcastFileException:InvalidMimeType', array($mime_type));
				throw new InvalidPodcastFileException($ex);
			}

			$this->savePodcastFile($data);
		}

		return TRUE;
	}

	/**
	 * Return the download url for this podcast
	 * 
	 * @return string
	 */
	public function getDownloadURL() {
		$download_url = elgg_normalize_url("podcasts/download/{$this->guid}/");
		return $download_url . $this->getFileTitle();
	}

	/**
	 * Return the serve url for this podcast
	 * 
	 * @return string
	 */
	public function getServeURL() {
		return elgg_normalize_url("podcasts/serve/{$this->guid}/play.") . $this->getFileExtension();
	}

	/**
	 * Get the file title based on entity title and original filename
	 *
	 * @return string
	 */
	public function getFileTitle() {
		$ext = $this->getFileExtension();
		return elgg_get_friendly_title($this->title) . ".{$ext}";
	}

	/**
	 * Get normalized file extension for this podcast file
	 * 
	 * @return string
	 */
	public function getFileExtension() {
		elgg_load_library('elgg:podcasts');
		return podcasts_get_mime_type_extension($this->getMimeType());
	}


	/**
	 * Check for valid podcast mime type
	 * 
	 * @param string $mime_type
	 * @return bool
	 */
	protected static function checkValidMimeType($mime_type) {
		elgg_load_library('elgg:podcasts');
		if (in_array($mime_type, podcasts_get_valid_mime_types())) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 * Save podcast file data
	 * 
	 * @param array $data File data to save
	 * @return bool
	 */
	protected function savePodcastFile($data) {
		$this->checkUploadErrors($data);

		$prefix = "podcast/";

		$file_name = elgg_strtolower(time() . $data['name']);

		$this->originalfilename = $data['name'];

		$this->setFilename($prefix . $file_name);

		// Open the file to guarantee the directory exists
		$this->open("write");
		$this->close();

		$result = move_uploaded_file($data['tmp_name'], $this->getFilenameOnFilestore());

		if (!$result) {
			$ex = elgg_echo('IOException:UnableToSaveNew', array('podcast'));
			throw new IOException($ex);
		}

		return TRUE;
	}

	/**
	 * Check for upload errors, this could be better..
	 *
	 * @param $data File data to check
	 */
	protected function checkUploadErrors($data) {
		if ($data['error']) {
			if ($data['error'] == UPLOAD_ERR_PARTIAL) {
				throw new IOException(elgg_echo('podcasts:error:partialupload'));
			} else {
				throw new IOException(elgg_echo('podcasts:error:unknown'));
			}
		}
		return;
	}
}