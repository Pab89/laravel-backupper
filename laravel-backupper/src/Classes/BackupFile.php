<?php

	namespace LaravelBackupper\Classes;

	use Carbon\Carbon;
	use LaravelBackupper\Interfaces\BackupFileInterface;

	abstract class BackupFile implements BackupFileInterface{

		public $file;
		public $fileName;
		public $fileSize;
		public $createdAt;
		public $fileNameWithoutDateTime;
		public $fileNameWithPath;

		public function __construct($fileName = false){
			
			if($fileName){
				$this->fileName = $fileName;
				$this->splitFileToParts();
			}
			
		}

		public static function getFileDateTimeFormat(){
		
			return config('laravelBackupper.fileTimeFormat');	
		
		}

		public static function getFileDateFormat(){
		
			$dateTimeExploded = explode("_", static::getFileDateTimeFormat() );
			return $dateTimeExploded[0];
		
		}

		public static function getFileTimeFormat(){
		
			$dateTimeExploded = explode("_", static::getFileDateTimeFormat() );
			return $dateTimeExploded[1];
		
		}

		public function splitFileToParts(){
		
			$this->setFileSize();
			$this->setCreatedAt();
			$this->setFileNameWithoutDateTime();
			
		}

		protected function setCreatedAt(){
		
			$createdAt = preg_replace('/_[^_]*$/', '', $this->fileName);
			$createdAt = Carbon::createFromFormat( static::getFileDateTimeFormat() ,$createdAt);
			$this->createdAt = $createdAt;
		
		}

		protected function createFileName(){
		
			$nowFormatted = $this->getFormattedDateTime();
			$this->fileName = $nowFormatted.static::getFileEnding();
		
		}

		public function getFileName(){
		
			if( strlen($this->fileName) == 0 ){
				$this->createFileName();
			}

			return $this->fileName;
		
		}

		protected function setFileNameWithoutDateTime(){
		
			$this->fileNameWithoutDateTime = preg_replace('/^.*_/','',$this->fileName);
		
		}

		public function getFileNameWithPath(){
		
			return static::getPath().$this->getFileName();
		
		}

		protected function setFileSize(){
		
			$this->fileSize = fileSize( $this->getFileNameWithPath() );

		}

		public function getFileSizeWithUnits(){

			$GbInB = 1000000000;
			$MbInB = 1000000;
			$KbInB = 1000;

			$sizeInCorrectUnit;
			$unit;

			if( $this->fileSize > ($GbInB/10) ){

				$sizeInCorrectUnit = $this->fileSize / $GbInB;
				$unit = "GB";

			}else if( $this->fileSize >= ($MbInB/10) ){

				$sizeInCorrectUnit = $this->fileSize / $MbInB;
				$unit = "Mb";

			}else if( $this->fileSize >= ($KbInB/10) ){

				$sizeInCorrectUnit = $this->fileSize / $KbInB;
				$unit = "KB";

			}else{

				$sizeInCorrectUnit = $this->fileSize;
				$unit = "B";
			}

			return number_format($sizeInCorrectUnit,'2',',','.') . " " .$unit;

		}

		protected function getFormattedDateTime(){

			$nowFormatted = Carbon::now()->format( static::getFileDateTimeFormat() );
			return $nowFormatted;

		}


	}

?>