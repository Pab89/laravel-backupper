<?php

	namespace LaravelBackupper\Classes;

	use Storage;
	use LaravelBackupper\Classes\BackupEnviroment;
	use LaravelBackupper\Classes\DbBackupFile;

	class DbBackupEnviroment extends BackupEnviroment{

		public function prepareEnviroment(){
		
			if( ! $this->enviromentExists() ){

				$this->createEnviroment();

			}

			return true;
		
		}

		public function enviromentExists(){
		
			return Storage::exists( DbBackupFile::getPath().'.gitignore' );
		
		}

		public function createEnviroment(){
		
			$this->createIfDontExist( DbBackupFile::getPath() );
			$this->createGitIgnore();
		
		}

		public function createGitIgnore(){
		
			// $fopen = $this->createIfDontExist( DbBackupFile::getPath().'.gitignore' );
			// $content = '*';

			Storage::put( DbBackupFile::getPath().'.gitignore', '*' );
			// fwrite($fopen,$content);
			// fclose($fopen);
		
		}

	}

?>