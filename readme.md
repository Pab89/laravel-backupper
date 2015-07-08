# Installation

### Step 1:

Add the serviceprovider class to config/app.php

```
'providers' => [ Milkwood\LaravelBackupper\LaravelBackupperServiceProvider::class ]
```

###Step 2

Remove the app path in the local driver root path in config/filesystem.php

```
'local' => [
    'driver' => 'local',
    'root'   => storage_path(),
]
```

###Step 3
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

# Use

### Commands

You have access to the following commands

#### 1: backup:db
Backs up your database both locally in your storage folder and remotely to you s3
```
php artisan backup:db
```

#### 2: backup:cleaner
Cleans up your backups both locally and remotely so that you don't keep out of date backups
```
php artisan backup:cleaner
```

#### 3: backup:report
Sends out a report with the current backup files, so you can check everything has run as supposed
```
php artisan backup:report "receiver email" "receiver name"
```