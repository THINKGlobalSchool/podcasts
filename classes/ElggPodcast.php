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
			$this->simpletype = "audio";

			// Get mimetype
			$mime_type = ElggPodcast::detectMimeType($data['tmp_name'], $data['type']);

			// Check for valid mime type
			if (ElggPodcast::checkValidMimeType($mime_type)) {
				// Set it
				$this->setMimeType($mime_type);
			} else {
				// Invalid, fail
				$ex = elgg_echo('InvalidPodcastFileException:InvalidMimeType', array($mime_type));
				throw new InvalidPodcastFileException($ex);
			}

			// Remove old file if it exists
			$old_file = $this->getFilenameOnFilestore();
			if (file_exists($old_file) && !is_dir($old_file)) {
				unlink($old_file);
			}

			// Save the file data
			$this->savePodcastFile($data);

			// Populate metadata
			$this->populatePodcastMetadata();
		}

		return TRUE;
	}

	/**
	 * Delete this podcast.
	 *
	 * @return bool
	 */
	public function delete($recursive = TRUE) {
		// Try regular ElggFile delete first
		$fs = $this->getFilestore();
		if ($fs->delete($this)) {
			return parent::delete();
		} else {
			// Couldn't delete the ElggFile
			$filename = $this->getFilenameOnFilestore($file);

			$success = true;

			// Check for a directory this time, and try again
			if (file_exists($filename) && !is_dir($filename)) {
				$success = unlink($filename);
			}

			// Good? Ok, delete it
			if ($success) {
				return delete_entity($this->get('guid'), $recursive);	
			} else {
				// Still an issue..
				return FALSE;
			}
		}
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
	 * Override for ElggFile::detectMimetype
	 * - Detects mime types using exiftool
	 *
	 * @param mixed $file    The full path of the file to check. For uploaded files, use tmp_name.
	 * @param mixed $default A default. Useful to pass what the browser thinks it is.
	 * @since 1.7.12
	 *
	 * @note If $file is provided, this may be called statically
	 *
	 * @return mixed Detected type on success, false on failure.
	 */
	public function detectMimeType($file = null, $default = null) {
		if (!$file) {
			if (isset($this) && $this->filename) {
				$file = $this->filename;
			} else {
				return false;
			}
		}

		// Load podcasts library
		elgg_load_library('elgg:podcasts');
		$mime = podcasts_get_mime_type($file);

		if (!is_string($mime)) {
			if ($mime == 127) {
				$ex = elgg_echo('podcasts:error:exiftoolnotfound');
				throw new PodcastMetadataException($ex);
			} else if ($mime > 0) {
				$ex = elgg_echo('podcasts:error:exiftoolfailed');
				throw new PodcastMetadataException($ex);
			}
		}

		// default
		if (empty($mime)) {
			return $default;
		}

		return $mime;
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
	 * Populate extended file info (duration, etc)
	 * 
	 * @throws PodcastMetadataException
	 * @return bool
	 */
	protected function populatePodcastMetadata() {
		elgg_load_library('elgg:podcasts');
		$return = podcasts_populate_file_info($this);
		if ($return == 127) {
			$ex = elgg_echo('podcasts:error:exiftoolnotfound');
			throw new PodcastMetadataException($ex);
		} else if ($return > 0) {
			$ex = elgg_echo('podcasts:error:exiftoolfailed');
			throw new PodcastMetadataException($ex);
		} else {
			return TRUE;
		}
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