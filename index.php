<?php

require_once __DIR__ . '/autoload.php';

$tree = new App\Tree(__DIR__ . '/storage/app');

App\Backup::backup($tree, __DIR__ . '/storage/backup', date('Y-m-d') . '_backup.json');
//App\Backup::restore(__DIR__ . '/storage/backup/2021-12-08_backup.json', __DIR__ . '/storage/app2');
