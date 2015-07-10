<?php

	namespace Milkwood\LaravelBackupper\Interfaces;

	interface DbBackupDirectoryInterface{

		public function getBackupsToKeep();

		public function cleanUp();

	}

?>