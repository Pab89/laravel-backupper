<?php

	namespace Milkwood\LaravelBackupper\Classes;

	use Storage;
	use Milkwood\LaravelBackupper\Classes\BackupEnviroment;
	use Milkwood\LaravelBackupper\Classes\DbBackupFile;

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

			Storage::put( DbBackupFile::getPath().'.gitignore', '*' );
		
		}

	}

?>