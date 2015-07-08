<?php

	namespace Milkwood\LaravelBackupper\Interfaces;

	interface BackupFileInterface{

		public function getFileEnding();

		public function getFileNameWithPath();

		public function getFileNameWithFullPath();

		public function getFileNameWithCloudPath();

	}

?>