<?php

	namespace Milkwood\LaravelBackupper\Interfaces;

	interface DbBackupEnviromentInterface{

		public function getPath();

		public function getFullPath();

		public function getCloudPath();

	}

?>