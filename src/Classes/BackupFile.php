<?php

	namespace Milkwood\LaravelBackupper\Classes;

	use Storage;
	use Carbon\Carbon;
	use Milkwood\LaravelBackupper\Classes\DbBackupFile;

	use Milkwood\LaravelBackupper\Interfaces\BackupFileInterface;

	abstract class BackupFile implements BackupFileInterface{

		public $fileName;
		public $fileSize;
		public $createdAt;
		public $fileNameWithoutDateTime;
		public $fileNameWithPath;

		public $disk;

		public static function removePathFromFile($file){
		
			preg_match('/[^\/]+$/', $file, $matches);
			return $matches[0];
		
		}

		public static function removeFileNameFromPath($file){
		
			$path = preg_replace('/[^\/]+$/','',$file);
			return $path;
		
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

		public static function createCorrectChildFromFileName($fileWithPath){

			if( strpos( $fileWithPath, DbBackupFile::getPath()) !== false ){

				return new DbBackupFile($fileWithPath);

			}
		
		}

		public function __construct($fileName = false){
			
			if($fileName){
				$this->setFileName($fileName);
				$this->splitFileToParts();
			}
			
		}

		public function setFileName($fileName){

			$this->fileName = static::removePathFromFile( $fileName );
		
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

		public function getFileNameWithPath(){
		
			return static::getPath().$this->getFileName();
		
		}

		public function getFileNameWithCloudPath(){
		
			return static::getCloudPath().$this->getFileName();
		
		}

		public function getFileNameWithFullPath(){
		
			return static::getFullPath().$this->getFileName();
		
		}

		protected function setFileNameWithoutDateTime(){
		
			$this->fileNameWithoutDateTime = preg_replace('/^.*_/','',$this->fileName);
		
		}

		protected function setFileSize(){
		
			$this->fileSize = Storage::size( $this->getFileNameWithPath() );

		}

		public function deleteLocal(){
		
			Storage::delete( $this->getFileNameWithPath() );
		
		}

		public function deleteCloud(){

			Storage::disk( config('laravelBackupper.cloudService') )->delete( $this->getFileNameWithCloudPath() );

		}

		public function getFileSizeWithUnits(){

			$fileUnits = [ 'GB' => 1000000000, 'MB' => 1000000, 'KB' => 1000, 'B' => 1 ];

			foreach($fileUnits as $unit => $numberOfBytesInUnit){

				// Check if the file size is bigger then 1/10 of the unit. ex. 0.1 gb
				if( $this->fileSize > ($numberOfBytesInUnit/10) ){

					$bytesDivededWithUnit = $this->fileSize / $numberOfBytesInUnit;
					return number_format($bytesDivededWithUnit,'2',',','.') . " " .$unit;

				}				

			}

		}

		protected function getFormattedDateTime(){

			$nowFormatted = Carbon::now()->format( static::getFileDateTimeFormat() );
			return $nowFormatted;

		}

		public function existsInCloud(){
		
			return Storage::disk( config('laravelBackupper.cloudService') )->exists( $this->getFileNameWithCloudPath() );
		
		}


	}

?>