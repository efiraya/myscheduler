<?php

require_once __DIR__ . '/vendor/autoload.php';

use GO\Scheduler;

$scheduler = new Scheduler();

$scheduler->php(__DIR__ . '/spark activity:update-weights')
    ->daily()
    ->output(__DIR__ . '/logs/activity_update.log');

$scheduler->run();
