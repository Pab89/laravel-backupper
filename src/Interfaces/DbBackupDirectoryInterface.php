<?php

	namespace Milkwood\LaravelBackupper\Interfaces;

	interface DbBackupDirectoryInterface{

		public static function getBackupsToKeep();

		public function cleanUp();

	}

?>