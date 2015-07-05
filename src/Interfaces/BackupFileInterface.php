<?php

	namespace Milkwood\LaravelBackupper\Interfaces;

	interface BackupFileInterface{

		public static function getFileEnding();

		public function setCloudDisk();

		public function getFileNameWithPath();

		public function getFileNameWithFullPath();

		public function getFileNameWithCloudPath();

	}

?>