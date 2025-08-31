<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\ActivityModel;
use App\Models\ScheduleModel;

class UpdateActivityWeights extends BaseCommand
{
    protected $group = 'Tasks';
    protected $name = 'activity:update-weights';
    protected $description = 'Recalculates activity weights based on deadlines.';

    public function run(array $params)
    {
        $activityModel = new ActivityModel();
        $activities = $activityModel->findAll();

        foreach ($activities as &$activity) {
            $daysToDeadline = (new \DateTime($activity['deadline']))->diff(new \DateTime())->days;
            $deadlineUrgencyAdjustment = (1 / max(1, $daysToDeadline)) * 10;

            $weight = $this->calculatePriorityWeight($activity);
            $finalWeight = ($weight * 0.6) + $deadlineUrgencyAdjustment;

            $activityModel->update($activity['id'], ['final_weight' => $finalWeight]);
        }

        CLI::write('Activity weights updated successfully.', 'green');
    }

    private function calculatePriorityWeight($activity)
    {
        $weight = 0;

        // Calculate Deadline
        $daysToDeadline = (new \DateTime($activity['deadline']))->diff(new \DateTime())->days;
        if ($daysToDeadline < 1) {
            $weight += 12; // Critical deadline
            $deadlineWeight = 12;
        } elseif ($daysToDeadline <= 3) {
            $weight += 8; // High urgency
            $deadlineWeight = 12;
        } elseif ($daysToDeadline <= 7) {
            $weight += 5; // Medium urgency
            $deadlineWeight = 12;
        } else {
            $weight += 2; // Low urgency
            $deadlineWeight = 12;
        }

        // Calculate Duration
        if ($activity['duration'] <= 1) {
            $weight += 5;
            $durationWeight = 5;
        } elseif ($activity['duration'] <= 3) {
            $weight += 3;
            $durationWeight = 3;
        } else {
            $weight += 2;
            $durationWeight = 2;
        }

        // Calculate Activity Type
        switch ($activity['activity_type']) {
            case 'college':
                $weight += 5;
                $activityTypeWeight = 5;
                break;
            case 'work':
                $weight += 4;
                $activityTypeWeight = 4;
                break;
            case 'organization':
                $weight += 3;
                $activityTypeWeight = 3;
                break;
            case 'personal':
                $weight += 2;
                $activityTypeWeight = 2;
                break;
        }

        // $weight = $deadlineWeight + $durationWeight + $activityTypeWeight;

        return $weight;
    }
}
