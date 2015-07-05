<?php

	namespace Milkwood\LaravelBackupper\Facades;

	use Illuminate\Support\Facades\Facade;

	class DbBackupEnviroment extends Facade{

		protected static function getFacadeAccessor() {

			return 'DbBackupEnviroment'; 

		}

	}

?>