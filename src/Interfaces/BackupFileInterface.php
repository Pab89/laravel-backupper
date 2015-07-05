<?php

	namespace Milkwood\LaravelBackupper\Interfaces;

	interface BackupFileInterface{

		public static function getFileEnding();

		public static function isAValidFile($file);

		public function setCloudDisk();

		public function getFileNameWithPath();

		public function getFileNameWithFullPath();

		public function getFileNameWithCloudPath();

	}

?>