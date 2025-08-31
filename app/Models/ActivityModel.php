<?php

namespace App\Models;

use CodeIgniter\Model;

class ActivityModel extends Model
{
    protected $table = 'activities';
    protected $primaryKey = 'id';
    protected $allowedFields = ['activity_name', 'deadline', 'duration', 'activity_type', 'description', 'user_id', 'fixed_deadline', 'daysToDeadline', 'completion_stat', 'urgency','hard_constraint','soft_constraint'];
    protected $returnType = 'array';
}
