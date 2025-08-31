<?php

namespace App\Config;

use CodeIgniter\Config\BaseConfig;
use App\Commands\SendReminders;

class Commands extends BaseConfig
{
    public $commands = [
        'tasks:send-reminders' => SendReminders::class,
    ];
}
?>