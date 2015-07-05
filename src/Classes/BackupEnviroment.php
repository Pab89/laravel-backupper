<?php

	namespace Milkwood\LaravelBackupper\Classes;

	use Storage;

	use Milkwood\LaravelBackupper\Classes\BackupDirectory;

	Abstract class BackupEnviroment{

		protected $backupDirectory;

		public function setBackupDirectory(){
		
			$this->backupDirectory = new BackupDirectory( $this );
		
		}

		public function getBackupDirectory(){
		
			if( $this->backupDirectory == null ){

				$this->setBackupDirectory();

			}

			return $this->backupDirectory;
		
		}

		public function getPath(){
		
			return config('laravelBackupper.backupPath');
		
		}

		public function getFullPath(){
		
			return storage_path()."/".$this->getPath();
		
		}

		public function getCloudPath(){
		
			return config('laravelBackupper.projectName')."/".$this->getPath();
		
		}

		public function getCloudDisk(){
		
			return Storage::disk( config('laravelBackupper.cloudService') );
		
		}

		public function createIfDontExist($path){
		
			if( !file_exists( $path )){

				if( $this->pathIsAFile($path) ){

					$file = Storage::put($path,'');
					return $file;

				}else{

					$dir = Storage::makeDirectory($path,0777,true);
					return $dir;

				}
			}
		
		}

		public function pathIsAFile($path){
		
			if( strpos($path,'.') !== false ){

				return true;

			}

			return false;
		
		}

		public function cleanUp(){
		
			$this->getBackupDirectory()->cleanUp();
		
		}

	}

?>