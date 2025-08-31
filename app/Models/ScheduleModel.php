<?php

namespace App\Models;

use CodeIgniter\Model;

class ScheduleModel extends Model
{
    protected $table = 'schedules';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'activity_id', 'activity_name', 'deadline', 'duration', 'activity_type', 'description', 'daysToDeadline', 'final_weight', 'completion_stat', 'urgency'];
    protected $returnType = 'array';
}
