<?php

	namespace Milkwood\LaravelBackupper\Classes;

	use Carbon\Carbon;
	use Milkwood\LaravelBackupper\Classes\BackupFile;
	use Milkwood\LaravelBackupper\Classes\DatabaseBackupper;

	class DbBackupFile extends BackupFile{

		public static $fileEnding = '_dump.sql';

		public static function getPath(){
		
			return config('laravelBackupper.dbBackupPath');	
		
		}

		public static function getFullPath(){
		
			return storage_path().static::getPath();
		
		}

		public static function getCloudPath(){
		
			return config('laravelBackupper.projectName').static::getPath();	
		
		}

		public static function getFileEnding(){
		
			return static::$fileEnding;
		
		}

		public static function isAValidFile($file){
		
			return ( strpos($file, ".sql") !== false);
		
		}

	}

?>