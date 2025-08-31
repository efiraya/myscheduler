<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\ActivityModel;
use App\Models\ScheduleModel;

class ActivityScheduler extends BaseCommand
{
    protected $group = 'Tasks';
    protected $name = 'schedule:activity';
    protected $description = 'Run the scheduling algorithm.';

    public function run(array $params)
    {
        $activityModel = new ActivityModel();
        $activities = $activityModel->where('user_id', session('user_id'))->findAll();

        CLI::write("Running the scheduling algorithm...", 'green');

        foreach ($activities as $activity) {
            $activity['weight'] = $this->calculatePriorityWeight($activities);

            $activityModel->update($activity['id'], [
                'weight' => $activity['weight'],
            ]);
        }



        // TO INSERT ACTIVITY TO SCHEDULE
        // foreach ($scheduledActivities as $activity) {
        //     if (!in_array($activity['id'], $existingActivityIds)) {
        //         $scheduleModel->insert([
        //             'user_id' => session('user_id'),
        //             'activity_id' => $activity['id'],
        //             'activity_name' => $activity['activity_name'],
        //             'deadline' => $activity['deadline'],
        //             'duration' => $activity['duration'],
        //             'activity_type' => $activity['activity_type'],
        //             'description' => $activity['description'],
        //             'daysToDeadline' => $activity['daysToDeadline'],
        //             'final_weight' => $activity['final_weight'],
        //             'completion_stat' => false,
        //             'urgency' => $activity['urgency'],
        //             'created_at' => date('Y-m-d H:i:s'),
        //             'updated_at' => date('Y-m-d H:i:s'),
        //         ]);
        //     }
        // }

        CLI::write("Scheduling complete.", 'green');
    }

    private function applySchedulingAlgorithm($activities)
    {
        $activityModel = new ActivityModel();

        foreach ($activities as &$activity) {
            $activity['scheduled_before_deadline'] = $activity['scheduled_before_deadline'] ?? false;

            $daysToDeadline = (new \DateTime($activity['deadline']))->diff(new \DateTime())->days;
            $deadlineUrgencyAdjustment = (1 / max(1, $daysToDeadline)) * 10;

            // Weight
            $weight = $this->calculatePriorityWeight($activity); // 60% impact

            // Constraints
            $hardConstraintWeight = $this->applyHardConstraint($activity); // 30% impact
            $softConstraintWeight = $this->applySoftConstraint($activities, $activity); // 10% impact
            $constraint_points = $hardConstraintWeight * 0.3 + $softConstraintWeight * 0.1;

            // Final weight
            $final_weight = ($weight * 0.6) + $constraint_points + $deadlineUrgencyAdjustment;
            // $activity['final_score'] = ($weight * 0.6) + ($hardConstraintWeight * 0.3) + ($softConstraintWeight * 0.1) + $deadlineUrgencyAdjustment;

            $activity['weight'] = $weight;
            $activity['hardConstraintWeight'] = $hardConstraintWeight;
            $activity['softConstraintWeight'] = $softConstraintWeight;
            $activity['constraint_points'] = $constraint_points;
            $activity['deadlineUrgencyAdjustment'] = $deadlineUrgencyAdjustment;
            $activity['final_weight'] = $final_weight;

            $data = [
                'weight' => $weight,
                'hard_constraint' => $hardConstraintWeight,
                'soft_constraint' => $softConstraintWeight,
                'constraint_points' => $constraint_points,
                'final_weight' => $final_weight,
            ];

            // Update the activity in the database with calculated values
            // $activityModel->update($activity['id'], [
            //     'weight' => $weight,
            //     'constraint_points' => $constraint_points,
            //     'final_weight' => $final_weight,
            // ]);

            // Insert calculation result to database ['weight', 'constraint_points', 'final_weight']
            // $activityModel->insert($data)
            // $this->

        }

        // Sort activities by final score from highest to lowest
        usort($activities, function ($a, $b) {
            return $b['final_weight'] <=> $a['final_weight'];
        });

        return $activities;
    }

    private function calculatePriorityWeight($activity)
    {
        $weight = 0;

        // Calculate Deadline
        $daysToDeadline = (new \DateTime($activity['deadline']))->diff(new \DateTime())->days;
        if ($daysToDeadline < 1) { // Critical deadline
            // $weight += 12;
            $deadlineWeight = 12;
        } elseif ($daysToDeadline <= 3) { // High urgency
            // $weight += 8;
            $deadlineWeight = 8;
        } elseif ($daysToDeadline <= 7) { // Medium urgency
            // $weight += 5;
            $deadlineWeight = 5;
        } else { // Low urgency
            // $weight += 2;
            $deadlineWeight = 2;
        }

        // Calculate Duration
        if ($activity['duration'] <= 1) {
            // $weight += 5;
            $durationWeight = 5;
        } elseif ($activity['duration'] <= 3) {
            // $weight += 3;
            $durationWeight = 3;
        } else {
            // $weight += 2;
            $durationWeight = 2;
        }

        // Calculate Activity Type
        switch ($activity['activity_type']) {
            case 'college':
                // $weight += 5;
                $activityTypeWeight = 5;
                break;
            case 'work':
                // $weight += 4;
                $activityTypeWeight = 4;
                break;
            case 'organization':
                // $weight += 3;
                $activityTypeWeight = 3;
                break;
            case 'personal':
                // $weight += 2;
                $activityTypeWeight = 2;
                break;
        }

        $weight = $deadlineWeight + $durationWeight + $activityTypeWeight;

        return $weight;
    }

    private function applyHardConstraint($activity)
    {
        $points = 0;

        // Check if the deadline must be met
        if (isset($activity['fixed_deadline']) && $activity['fixed_deadline']) {
            $points += 10;
        } else {
            $points -= 10;
        }

        // Adjusted Deadline Proximity
        $daysToDeadline = (new \DateTime($activity['deadline']))->diff(new \DateTime())->days;
        if (($daysToDeadline < 1 || $daysToDeadline <= 2) && $activity['scheduled_before_deadline']) {
            $points += 8;
        } else {
            $points -= 8;
        }

        return $points;
    }

    private function applySoftConstraint($activities, $activity)
    {
        $points = 0;

        // Overlap check 
        if (!$this->checkOverlap($activities, $activity)) {
            $points += 8;
        }

        // Enhanced activity priority
        $sortedActivities = array_column($activities, 'weight');
        if ($sortedActivities[0] == $activity['weight']) {
            $points += 5;
        }

        // Balance of activity types
        $activityTypes = array_count_values(array_column($activities, 'activity_type'));
        if (min($activityTypes) >= 1) {
            $points += 3;
        }

        return $points;
    }

    private function checkOverlap($activities, $activity)
    {
        // If the total weight are the same, add points to the activity that has a closer deadline.
        // If the deadline are also the same then add points to the activity that has less duration for completion
        // If deadline and duration are the same, check category type and which one has the higher level. 

        foreach ($activities as $existingActivity) {
            // Check if the weights are the same
            if ($existingActivity['weight'] === $activity['weight']) {
                // Compare deadlines
                $existingDeadline = strtotime($existingActivity['deadline']);
                $newDeadline = strtotime($activity['deadline']);
                $existingDuration = $existingActivity['duration'];
                $newDuration = $activity['duration'];

                // Calculate the end time for both activities
                $existingEndTime = $existingDeadline + $existingDuration;
                $newEndTime = $newDeadline + $newDuration;

                // Check if the time intervals overlap
                if ($newDeadline < $existingEndTime && $existingDeadline < $newEndTime) {
                    return true;
                }

                // If deadlines are the same, check duration
                if ($existingDeadline === $newDeadline) {
                    if ($existingDuration < $newDuration) {
                        return true;
                    } elseif ($existingDuration > $newDuration) {
                        return false;
                    }

                    // If duration is also the same, check category type and level
                    if ($existingActivity['category'] === $activity['category']) {
                        if ($existingActivity['level'] > $activity['level']) {
                            return true;
                        } else {
                            return false;
                        }
                    }
                }
            }
        }

        return false;
    }
}
