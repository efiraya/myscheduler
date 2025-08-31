<?php

namespace App\Models;

use CodeIgniter\Model;

class HasilFcfsModel extends Model
{
    protected $table            = 'hasil_fcfs';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['activity_id','activity_name', 'deadline', 'duration', 'activity_type', 'description', 'user_id', 'fixed_deadline', 'daysToDeadline', 'completion_stat', 'urgency','hard_constraint','soft_constraint','weight','constraint_points','final_weight','start_time','completion_time','turnaround_time','waiting_time'];

    protected $returnType = 'array';

}
