<?php

require_once __DIR__ . '/bootstrap/app.php';

$confData = require_once 'config/' . $argv[1] . '.php';

new \Shuttle\Server\Tcp($confData['host'], $confData['port']);
