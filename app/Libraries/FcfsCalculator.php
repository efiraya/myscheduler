<?php

namespace App\Libraries;

class FcfsCalculator
{
    public function calculateWeight($activity, $activities)
    {
        $hardConstraintPoints = $this->applyHardConstraint($activity);
        $softConstraintPoints = $this->applySoftConstraint($activities, $activity);
        $weight = $hardConstraintPoints + $softConstraintPoints;

        $activity['hard_constraint'] = $hardConstraintPoints;
        $activity['soft_constraint'] = $softConstraintPoints;
        $activity['perhitungan'] = 'fcfs';
        $activity['weight'] = $weight;

        return $activity;
    }

    private function applyHardConstraint($activity)
    {
        $points = 0;
        if (isset($activity['fixed_deadline']) && $activity['fixed_deadline']) {
            $points += 10;
        } else {
            $points -= 10;
        }

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

        if (!$this->checkOverlap($activities, $activity)) {
            $points += 8;
        }

        $sortedActivities = array_column($activities, 'weight');
        if (!empty($sortedActivities) && max($sortedActivities) == $activity['weight']) {
            $points += 5;
        }

        $activityTypes = array_count_values(array_column($activities, 'activity_type'));
        if (!empty($activityTypes) && min($activityTypes) >= 1) {
            $points += 3;
        }

        return $points;
    }

    private function checkOverlap($activities, $activity)
    {
        foreach ($activities as $existingActivity) {
            if ($existingActivity['weight'] === $activity['weight']) {
                $existingDeadline = strtotime($existingActivity['deadline']);
                $newDeadline = strtotime($activity['deadline']);
                $existingDuration = $existingActivity['duration'];
                $newDuration = $activity['duration'];

                $existingEndTime = $existingDeadline + ($existingDuration * 3600);
                $newEndTime = $newDeadline + ($newDuration * 3600);

                if ($newDeadline < $existingEndTime && $existingDeadline < $newEndTime) {
                    return true;
                }

                if ($existingDeadline === $newDeadline) {
                    if ($existingDuration < $newDuration) {
                        return true;
                    } elseif ($existingDuration > $newDuration) {
                        return false;
                    }

                    if ($existingActivity['category'] === $activity['category']) {
                        return $existingActivity['level'] > $activity['level'];
                    }
                }
            }
        }

        return false;
    }

    public function fcfsWithWeights($activities)
    {
        foreach ($activities as &$activity) {
            $activity['scheduled_before_deadline'] = $activity['scheduled_before_deadline'] ?? false;
            $activity = $this->calculateWeight($activity, $activities);
        }

        usort($activities, function ($a, $b) {
            if ($a['weight'] === $b['weight']) {
                return strtotime($a['deadline']) - strtotime($b['deadline']);
            }
            return $b['weight'] - $a['weight'];
        });

        return $activities;
    }
}
?>
