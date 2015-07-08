<?php

	namespace Milkwood\LaravelBackupper\Traits;

	use Carbon\Carbon;

	trait Timestampable{

		public $createdAt;

		public static function getDateTimeFormat(){
		
			return static::getDateFormat()." ".static::getTimeFormat();
		
		}

		public static function getDateTimeFormatForFiles(){
		
			return static::getDateFormatForFiles()."_".static::getTimeFormatForFiles();
		
		}

		public static function getDateFormat(){

			return config('laravelBackupper.timestampDateFormat');
		
		}

		public static function getDateFormatForFiles(){
		
			return static::getDateFormat();
		
		}

		public static function getTimeFormat(){

			return config('laravelBackupper.timestampTimeFormat');
		
		}

		public static function getTimeFormatForFiles(){
		
			return str_replace( ':', '-', static::getTimeFormat() );
		
		}

		public static function getNowFormatted(){

			 return Carbon::now()->format( static::getDateTimeFormat() );

		}

		public static function getNowFormattedForFiles(){
		
			return Carbon::now()->format( static::getDateTimeFormatForFiles() );			
		
		}

		public function setCreatedAtFromFileName(){
		
			$createdAt = preg_replace('/_[^_]*$/', '', $this->fileName);
			$createdAt = Carbon::createFromFormat( static::getDateTimeFormatForFiles() ,$createdAt);
			$this->createdAt = $createdAt;
		
		}

	}

?>