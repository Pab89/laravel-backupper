# Installation

### Step 1:

Add the serviceprovider class to config/app.php

```
'providers' => [ Milkwood\LaravelBackupper\LaravelBackupperServiceProvider::class ]
```

### Step 2:

Add the DbBackupEnviroment facade to config/app.php

```
'aliases' => [ 'DbBackupEnviroment' => Milkwood\LaravelBackupper\Facades\DbBackupEnviroment::class ]
```

###Step 3

Remove the app path in the local driver root path in config/filesystem.php

```
'local' => [
    'driver' => 'local',
    'root'   => storage_path(),
]
```

###Step 4
Set up amazone s3 credencials


```
's3' => [
    'driver' => 's3',
    'key'    => 'XXX',
    'secret' => 'XXX',
    'region' => 'XXX',
    'bucket' => 'XXX',
]
```