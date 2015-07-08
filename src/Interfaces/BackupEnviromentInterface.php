<?php

	namespace Milkwood\LaravelBackupper\Interfaces;

	interface BackupEnviromentInterface{

		public function getPath();

		public function getFullPath();

		public function getCloudPath();

	}

?>