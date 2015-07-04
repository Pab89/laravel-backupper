<?php

	namespace Milkwood\LaravelBackupper\Classes;

	use Storage;
	use Carbon\Carbon;
	use Milkwood\LaravelBackupper\Classes\BackupFile;	

	class BackupDirectory{

		public $files = [];
		public $path;

		public $filesToIgnore = ['.','..','.gitignore'];
		public $startsWithDateRegex = '/^[0-9]{2}-[0-9]{2}-[0-9]{4}_[0-9]{2}-[0-9]{2}-[0-9]{2}/';

		public static function getBackupsToKeep(){
		
			return config('laravelBackupper.backupsToKeep');
		
		}


		public function __construct($path){

			$this->path = $path;
			$this->setFileVaribles();
			
		}

		public function setFileVaribles(){
		
			$this->setFiles();
		
		}

		public function setFiles(){

			$this->files = [];

			$files = Storage::files( $this->path );

			foreach($files as $file){

				$fileWithoutPath = BackupFile::removePathFromFile( $file );

				if( ! in_array($fileWithoutPath, $this->filesToIgnore) ){

					$this->files[] = BackupFile::createCorrectChildFromFileName( $file );

				}

			}

			usort( $this->files, array($this,'startDateSorter') );
		
		}

		public function startDateSorter($a, $b){

			$aTimestamp = $a->createdAt->timestamp;
			$bTimestamp = $b->createdAt->timestamp;

			if( $aTimestamp == $bTimestamp){
				return 0;
			}

			return ( $aTimestamp > $bTimestamp ) ? 1 : -1;
		
		}

		public function cleanUp(){
		
			$numberOfOldBackupsToDelete = count( $this->files ) - static::getBackupsToKeep();
			$backupsToDelete = array_slice($this->files, 0, $numberOfOldBackupsToDelete);

			foreach($backupsToDelete as $backupToDelete){

				$backupToDelete->deleteLocal();
				$backupToDelete->deleteCloud();
			}

			$this->setFileVaribles();
		
		}

	}

?>