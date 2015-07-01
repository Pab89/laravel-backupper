<?php

	namespace LaravelBackupper\Classes;

	use LaravelBackupper\Classes\BackupFile;	
	use Carbon\Carbon;

	class BackupDirectory{

		public $entries = [];
		public $entriesDateSorted = [];
		public $path;

		public $entriesToIgnore = ['.','..','.gitignore'];
		public $regexStartsWithDatePattern = '/^[0-9]{2}-[0-9]{2}-[0-9]{4}_[0-9]{2}-[0-9]{2}-[0-9]{2}/';

		public static function getBackupsToKeep(){
		
			return config('laravelBackupper.backupsToKeep');
		
		}


		public function __construct($path){
			
			$this->path = $path;
			$this->setEntryVaribles();
			
		}

		public function setEntryVaribles(){
		
			$this->setEntries();
			$this->setEntriesDateSorted();
		
		}

		public function setEntries(){

			$this->entries = [];
		
			$dh = opendir( $this->path );

			while( ( $entry = readdir($dh) ) !== false ){
				
				if( ! in_array($entry, $this->entriesToIgnore) ){
					$this->entries[] = $entry;
				}

			}
		
		}

		public function setEntriesDateSorted(){

			$this->entriesDateSorted = [];
		
			foreach( $this->entries as $entry ){

				if( preg_match( $this->regexStartsWithDatePattern , $entry) ){

					$this->entriesDateSorted[] = $entry;

				}

			}

			usort( $this->entriesDateSorted, array($this,'startDateSorter') );
		
		}

		public function startDateSorter($a, $b){
		
			preg_match($this->regexStartsWithDatePattern, $a, $matches);
			$aDate = $matches[0];

			preg_match($this->regexStartsWithDatePattern, $b, $matches);
			$bDate = $matches[0];

			$aTimestamp = Carbon::createFromFormat( BackupFile::getFileDateTimeFormat() , $aDate)->timestamp;
			$bTimestamp = Carbon::createFromFormat( BackupFile::getFileDateTimeFormat() , $bDate)->timestamp;

			if( $aTimestamp == $bTimestamp){
				return 0;
			}

			return ( $aTimestamp > $bTimestamp ) ? 1 : -1;
		
		}

		public function cleanUp(){
		
			$numberOfOldBackupsToDelete = count( $this->entriesDateSorted ) - static::getBackupsToKeep();

			for( $i = 0; $i < $numberOfOldBackupsToDelete; $i++ ){

				unlink( $this->path . $this->entriesDateSorted[$i] );

			}

			$this->setEntryVaribles();
		
		}

	}

?>