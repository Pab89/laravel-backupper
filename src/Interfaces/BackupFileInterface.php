<?php

	namespace Milkwood\LaravelBackupper\Interfaces;

	interface BackupFileInterface{


		public static function getPath();

		public static function getFullPath();

		public static function getFileEnding();

		public static function isAValidFile($file);

	}

?>