<?php

	namespace LaravelBackupper\Classes;

	use Storage;
	use Carbon\Carbon;
	use LaravelBackupper\Classes\BackupFile;	

	class BackupDirectory{

		public $files = [];
		public $filesDateSorted = [];
		public $path;

		public $filesToIgnore = ['.','..','.gitignore'];
		public $startsWithDateRegex = '/^[0-9]{2}-[0-9]{2}-[0-9]{4}_[0-9]{2}-[0-9]{2}-[0-9]{2}/';

		public static function getBackupsToKeep(){
		
			return config('laravelBackupper.backupsToKeep');
		
		}


		public function __construct($path, $disk = 'local'){

			$this->disk = Storage::disk($disk);
			$this->path = $path;
			$this->setFileVaribles();
			
		}

		public function setFileVaribles(){
		
			$this->setFiles();
			$this->setFilesDateSorted();
		
		}

		public function setFiles(){

			$this->files = [];

			$files = $this->disk->files( $this->path );

			foreach($files as $file){

				if( ! in_array($file, $this->filesToIgnore) ){

					$this->files[] = BackupFile::removePathFromFile($file);

				}

			}
		
		}

		public function setFilesDateSorted(){

			$this->filesDateSorted = [];
		
			foreach( $this->files as $File ){

				if( preg_match( $this->startsWithDateRegex , $File) ){

					$this->filesDateSorted[] = $File;

				}

			}

			usort( $this->filesDateSorted, array($this,'startDateSorter') );
		
		}

		public function startDateSorter($a, $b){
		
			preg_match($this->startsWithDateRegex, $a, $matches);
			$aDate = $matches[0];

			preg_match($this->startsWithDateRegex, $b, $matches);
			$bDate = $matches[0];

			$aTimestamp = Carbon::createFromFormat( BackupFile::getFileDateTimeFormat() , $aDate)->timestamp;
			$bTimestamp = Carbon::createFromFormat( BackupFile::getFileDateTimeFormat() , $bDate)->timestamp;

			if( $aTimestamp == $bTimestamp){
				return 0;
			}

			return ( $aTimestamp > $bTimestamp ) ? 1 : -1;
		
		}

		public function cleanUp(){
		
			$numberOfOldBackupsToDelete = count( $this->filesDateSorted ) - static::getBackupsToKeep();
			$backupsToDelete = array_slice($this->filesDateSorted, 0, $numberOfOldBackupsToDelete);

			foreach($backupsToDelete as $backupToDelete){
				$this->disk->delete( $this->path.$backupToDelete );
			}

			$this->setFileVaribles();
		
		}

	}

?>