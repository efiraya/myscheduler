<?php

namespace App\Controllers;

use App\Models\ActivityModel;
use App\Models\ScheduleModel;
use App\Models\HasilFcfsModel;
use App\Models\UserModel; // Import the UserModel
use CodeIgniter\Controller;
use CodeIgniter\Database\Exceptions\DataException;
use App\Models\EmailLog;
use App\Libraries\FcfsCalculator;

class ActivityController extends Controller
{
    public function activityInput()
    {
        return view('activity/activity_input');
    }

    public function activitySubmit()
    {   
        $activityModel = new ActivityModel();

        $data = [
            'user_id' => session('user_id'),
            'activity_name' => $this->request->getPost('activity_name'),
            'deadline' => $this->request->getPost('deadline_date').' '.$this->request->getPost('deadline_time'),
            'duration' => $this->request->getPost('duration'),
            'activity_type' => $this->request->getPost('activity_type'),
            'description' => $this->request->getPost('description'),
            'fixed_deadline' => (int)$this->request->getPost('fixed_deadline'),
        ];

        // $data['weight'] = $this->calculatePriorityWeight($data);

        if ($activityModel->insert($data)) {
            // session()->remove('calculation_result');
            return redirect()->to('/activity/list')->with('success', 'Activity added successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to add activity.');
        }
    }

    public function activityList($page = 1)
    {
        $activityModel = new ActivityModel();

        $perPage = 5;
        $totalActivities = $activityModel->where('user_id', session('user_id'))->countAllResults();
        $totalPages = ceil($totalActivities / $perPage);

        $offset = ($page - 1) * $perPage;
        $activities = $activityModel->where('user_id', session('user_id'))->findAll();

        return view('activity/activity_list', [
            'activities' => $activities,
            'currentPage' => $page,
            'totalPages' => $totalPages
        ]);
    }

    public function activityEdit($id)
    {
        $activityModel = new ActivityModel();
        $activity = $activityModel->find($id);

        if (!$activity || $activity['user_id'] != session('user_id')) {
            return redirect()->to('/activity/list')->with('error', 'Activity not found or access denied.');
        }

        return view('activity/activity_edit', ['activity' => $activity]);
    }

    public function activityUpdate($id)
    {
        $activityModel = new ActivityModel();

        $activity = $activityModel->find($id);
        if (!$activity || $activity['user_id'] != session('user_id')) {
            return redirect()->to('/activity/list')->with('error', 'Activity not found or access denied.');
        }

        $data = [
            'activity_name' => $this->request->getPost('activity_name'),
            'deadline' => $this->request->getPost('deadline_date').' '.$this->request->getPost('deadline_time'),
            'duration' => $this->request->getPost('duration'),
            'activity_type' => $this->request->getPost('activity_type'),
            'description' => $this->request->getPost('description'),
            'fixed_deadline' => (int)$this->request->getPost('fixed_deadline'),
            'completion_stat' => $this->request->getPost('completion_stat'),
        ];

        if ($activityModel->update($id, $data)) {
            return redirect()->to('/activity/list')->with('success', 'Activity updated successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to update activity.');
        }
    }

    public function activityUpdateStatus($id)
    {
        $activityModel = new ActivityModel();

        $activity = $activityModel->find($id);
        if (!$activity || $activity['user_id'] != session('user_id')) {
            return redirect()->to('/activity/list')->with('error', 'Activity not found or access denied.');
        }

        $data = [
            'completion_stat' => 'completed',
        ];

        if ($activityModel->update($id, $data)) {
            return redirect()->to('/activity/list')->with('success', 'Activity updated successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to update activity.');
        }
    }

    public function activityDelete($id)
    {
        $activityModel = new ActivityModel();
        $activity = $activityModel->find($id);

        if (!$activity || $activity['user_id'] != session('user_id')) {
            return redirect()->to('/activity/list')->with('error', 'Activity not found or access denied.');
        }

        if ($activityModel->delete($id)) {
            return redirect()->to('/activity/list')->with('success', 'Activity deleted successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to delete activity.');
        }
    }

    public function scheduleActivity()
    {
        date_default_timezone_set('Asia/Jakarta');
        
        $activityModel = new ActivityModel();
        $activities = $activityModel->where('user_id', session('user_id'))->where('completion_stat', 'pending')->findAll();

        // Error: Undifined array key "deadline"
        // What it suppose to do: update the deadline weight of the activity
        // Maybe should make daysToDeadline to database??
        // Error resolved! Issue was with syntax
        foreach ($activities as $activity) {
            $activity['weight'] = $this->calculatePriorityWeight($activity);

            // Logic Overdue by checking currentTime to deadline
            // $currentDate = date('Y-m-d H:i:s'); 
            $dateTimeObject1 = date_create($activity['deadline']);
            $dateTimeObject2 = date_create((new \DateTime())->format('Y-m-d H:i:s'));

            $interval = date_diff($dateTimeObject1, $dateTimeObject2);
            // echo ("Difference in days is: ");

            // Printing the result in days format
            // echo $interval->format('%R%a days');
            // echo "\n<br/>";
            // $timeToDeadline = $interval->days * 24 * 60;
            // $timeToDeadline += $interval->h * 60;
            // $timeToDeadline += $interval->i * 60;
            // $timeToDeadline += $interval->s;

            // $timeToDeadline = (new \DateTime($activity['deadline']))->diff(new \DateTime())->s;

            // Debugging
            // if ($activity['id'] == 45) {
            //     // dd($timeToDeadline);
            //     dd($interval->invert, $timeToDeadline);
            // }

            if (!$interval->invert) {
                $activity['completion_status'] = 'overdue';
            }

            $activityModel->update($activity['id'], $activity);
        }
        $activities = $activityModel->where('user_id', session('user_id'))->where('completion_stat', 'pending')->where('deadline >=', date('Y-m-d h:i:s'))->findAll();
        // dd($activities);


        // foreach

        $scheduledActivities = $this->applySchedulingAlgorithm($activities);

        // session()->remove('calculation_result');

        // session()->set('calculation_result', $scheduledActivities);

        $scheduleModel = new ScheduleModel();

        $scheduleModel->where('user_id', session('user_id'))->delete();

        $existingSchedules = $scheduleModel->where('user_id', session('user_id'))->findAll();
        $existingActivityIds = array_column($existingSchedules, 'activity_id');

        // Activity name, deadline, duration etc can be called through query
        // Will be doing this using activity id
        foreach ($scheduledActivities as $activity) {

            $daysToDeadline = (new \DateTime($activity['deadline']))->diff(new \DateTime())->days;

            if ($daysToDeadline < 1) { // Critical deadline
                // $weight += 12;
                $urgent = 'critical';
            } elseif ($daysToDeadline <= 3) { // High urgency
                // $weight += 8;
                $urgent = 'high';;
            } elseif ($daysToDeadline <= 7) { // Medium urgency
                // $weight += 5;
                $urgent = 'medium';;
            } else { // Low urgency
                // $weight += 2;
                $urgent = 'low';;
            }
            if (!in_array($activity['id'], $existingActivityIds)) {
                $scheduleModel->insert([
                    'user_id' => session('user_id'),
                    'activity_id' => $activity['id'],
                    'activity_name' => $activity['activity_name'],
                    'deadline' => $activity['deadline'],
                    'duration' => $activity['duration'],
                    'activity_type' => $activity['activity_type'],
                    'description' => $activity['description'],
                    'daysToDeadline' => $activity['daysToDeadline'],
                    'final_weight' => $activity['final_weight'],
                    // 'completion_stat' => false,
                    'completion_stat' => $activity['completion_stat'],
                    'urgency' => $urgent,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            } else {
                // Updates completion stat
                $scheduleModel->find($activity['id'])->update([
                    'user_id' => session('user_id'),
                    'activity_id' => $activity['id'],
                    'activity_name' => $activity['activity_name'],
                    'deadline' => $activity['deadline'],
                    'duration' => $activity['duration'],
                    'activity_type' => $activity['activity_type'],
                    'description' => $activity['description'],
                    'daysToDeadline' => $activity['daysToDeadline'],
                    'final_weight' => $activity['final_weight'],
                    // 'completion_stat' => false,
                    'completion_stat' => $activity['completion_stat'],
                    'urgency' => $urgent,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }

            
        }

        // FCFS
        $fcfs = new FcfsCalculator();
        $result = $fcfs->fcfsWithWeights($activities);
        $fcfsModel = new HasilFcfsModel();
        $fcfsModel->where('user_id', session('user_id'))->delete();
        $existingFcfs = $fcfsModel->where('user_id', session('user_id'))->findAll();
        $existingActivityIds = array_column($existingFcfs, 'activity_id');
        foreach($result as $key => $valResult){
            if (!in_array($valResult['id'], $existingActivityIds)) {
                $fcfsModel->insert([
                    'user_id' => session('user_id'),
                    'activity_id' => $valResult['id'],
                    'activity_name' => $valResult['activity_name'],
                    'deadline' => $valResult['deadline'],
                    'duration' => $valResult['duration'],
                    'activity_type' => $valResult['activity_type'],
                    'description' => $valResult['description'],
                    'daysToDeadline' => $valResult['daysToDeadline'],
                    'final_weight' => $valResult['final_weight'],
                    'hard_constraint' => $valResult['hard_constraint'],
                    'soft_constraint' => $valResult['soft_constraint'],
                    'constraint_points' => $valResult['constraint_points'],
                    // 'completion_stat' => false,
                    'completion_stat' => $valResult['completion_stat'],
                    'urgency' => $urgent,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            } else {
                // Updates completion stat
                $fcfsModel->find($valResult['id'])->update([
                    'user_id' => session('user_id'),
                    'activity_id' => $valResult['id'],
                    'activity_name' => $valResult['activity_name'],
                    'deadline' => $valResult['deadline'],
                    'duration' => $valResult['duration'],
                    'activity_type' => $valResult['activity_type'],
                    'description' => $valResult['description'],
                    'daysToDeadline' => $valResult['daysToDeadline'],
                    'final_weight' => $valResult['final_weight'],
                    'hard_constraint' => $valResult['hard_constraint'],
                    'soft_constraint' => $valResult['soft_constraint'],
                    'constraint_points' => $valResult['constraint_points'],
                    // 'completion_stat' => false,
                    'completion_stat' => $valResult['completion_stat'],
                    'urgency' => $urgent,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }

        return redirect()->to('/calculation/result');
    }

    public function scheduleActivities()
    {
        $activityModel = new ActivityModel();
        $activities = $activityModel->where('user_id', session('user_id'))->where('completion_stat', 0)->findAll();

        // Error: Undifined array key "deadline"
        // What it suppose to do: update the deadline weight of the activity
        // Maybe should make daysToDeadline to database??
        // Error resolved! Issue was with syntax
        foreach ($activities as $activity) {
            $activity['weight'] = $this->calculatePriorityWeight($activity);

            $activityModel->update($activity['id'], $activity);
        }

        // foreach

        $scheduledActivities = $this->applySchedulingAlgorithm($activities);

        // session()->remove('calculation_result');

        // session()->set('calculation_result', $scheduledActivities);

        $scheduleModel = new ScheduleModel();

        $scheduleModel->where('user_id', session('user_id'))->delete();

        $existingSchedules = $scheduleModel->where('user_id', session('user_id'))->findAll();
        $existingActivityIds = array_column($existingSchedules, 'activity_id');

        foreach ($scheduledActivities as $activity) {
            $daysToDeadline = (new \DateTime($activity['deadline']))->diff(new \DateTime())->days;

            if ($daysToDeadline < 1) { // Critical deadline
                // $weight += 12;
                $urgent = 'critical';
            } elseif ($daysToDeadline <= 3) { // High urgency
                // $weight += 8;
                $urgent = 'high';;
            } elseif ($daysToDeadline <= 7) { // Medium urgency
                // $weight += 5;
                $urgent = 'medium';;
            } else { // Low urgency
                // $weight += 2;
                $urgent = 'low';;
            }

            if (!in_array($activity['id'], $existingActivityIds)) {
                $scheduleModel->insert([
                    'user_id' => session('user_id'),
                    'activity_id' => $activity['id'],
                    'activity_name' => $activity['activity_name'],
                    'deadline' => $activity['deadline'],
                    'duration' => $activity['duration'],
                    'activity_type' => $activity['activity_type'],
                    'description' => $activity['description'],
                    'daysToDeadline' => $activity['daysToDeadline'],
                    'final_weight' => $activity['final_weight'],
                    'completion_stat' => false,
                    'urgency' => $urgent,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }

        return redirect()->to('/calculation/result');
    }

    public function updadteScheduleActivity()
    {
        $activityModel = new ActivityModel();
        $activities = $activityModel->where('user_id', session('user_id'))->where('completion_stat', 'pending')->findAll();

        // Error: Undifined array key "deadline"
        // What it suppose to do: update the deadline weight of the activity
        // Maybe should make daysToDeadline to database??
        // Error resolved! Issue was with syntax
        foreach ($activities as $activity) {
            $activity['weight'] = $this->calculatePriorityWeight($activity);

            // Logic Overdue by checking currentTime to deadline
            // $currentDate = date('Y-m-d H:i:s'); 
            $dateTimeObject1 = date_create($activity['deadline']);
            $dateTimeObject2 = date_create((new \DateTime())->format('Y-m-d H:i:s'));

            $interval = date_diff($dateTimeObject1, $dateTimeObject2);
            // echo ("Difference in days is: ");

            // Printing the result in days format
            // echo $interval->format('%R%a days');
            // echo "\n<br/>";
            // $timeToDeadline = $interval->days * 24 * 60;
            // $timeToDeadline += $interval->h * 60;
            // $timeToDeadline += $interval->i * 60;
            // $timeToDeadline += $interval->s;

            // $timeToDeadline = (new \DateTime($activity['deadline']))->diff(new \DateTime())->s;

            // Debugging
            // if ($activity['id'] == 45) {
            //     // dd($timeToDeadline);
            //     dd($interval->invert, $timeToDeadline);
            // }

            if (!$interval->invert) {
                $activity['completion_status'] = 'overdue';
            }

            $activityModel->update($activity['id'], $activity);
        }
        $activities = $activityModel->where('user_id', session('user_id'))->where('completion_stat', 'pending')->findAll();
        // dd($activities);


        // foreach

        $scheduledActivities = $this->applySchedulingAlgorithm($activities);

        // session()->remove('calculation_result');

        // session()->set('calculation_result', $scheduledActivities);

        $scheduleModel = new ScheduleModel();

        $scheduleModel->where('user_id', session('user_id'))->delete();

        $existingSchedules = $scheduleModel->where('user_id', session('user_id'))->findAll();
        $existingActivityIds = array_column($existingSchedules, 'activity_id');

        // Activity name, deadline, duration etc can be called through query
        // Will be doing this using activity id
        foreach ($scheduledActivities as $activity) {

            $daysToDeadline = (new \DateTime($activity['deadline']))->diff(new \DateTime())->days;

            if ($daysToDeadline < 1) { // Critical deadline
                // $weight += 12;
                $urgent = 'critical';
            } elseif ($daysToDeadline <= 3) { // High urgency
                // $weight += 8;
                $urgent = 'high';;
            } elseif ($daysToDeadline <= 7) { // Medium urgency
                // $weight += 5;
                $urgent = 'medium';;
            } else { // Low urgency
                // $weight += 2;
                $urgent = 'low';;
            }
            if (!in_array($activity['id'], $existingActivityIds)) {
                $scheduleModel->insert([
                    'user_id' => session('user_id'),
                    'activity_id' => $activity['id'],
                    'activity_name' => $activity['activity_name'],
                    'deadline' => $activity['deadline'],
                    'duration' => $activity['duration'],
                    'activity_type' => $activity['activity_type'],
                    'description' => $activity['description'],
                    'daysToDeadline' => $activity['daysToDeadline'],
                    'final_weight' => $activity['final_weight'],
                    // 'completion_stat' => false,
                    'completion_status' => $activity['completion_stat'],
                    'urgency' => $urgent,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            } else {
                // Updates completion stat
                $scheduleModel->find($activity['id'])->update([
                    'user_id' => session('user_id'),
                    'activity_id' => $activity['id'],
                    'activity_name' => $activity['activity_name'],
                    'deadline' => $activity['deadline'],
                    'duration' => $activity['duration'],
                    'activity_type' => $activity['activity_type'],
                    'description' => $activity['description'],
                    'daysToDeadline' => $activity['daysToDeadline'],
                    'final_weight' => $activity['final_weight'],
                    // 'completion_stat' => false,
                    'completion_status' => $activity['completion_stat'],
                    'urgency' => $urgent,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }

        echo 'Update Schedule Activity successfully!';
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

    public function calculationResult()
    {
        // Save and show previous schedule if a schedule had already been made before
        // ^ Done in scheduledActivity
        $scheduleModel = new ScheduleModel();
        $schedules = $scheduleModel->where(['user_id' => session('user_id'), 'completion_stat' => 'pending'])->orderBy("FIELD(urgency, 'critical', 'high', 'medium', 'low')", '', false)->findAll();


        $hasilFcfs = new HasilFcfsModel();
        $fcfs = $hasilFcfs->where(['user_id' => session('user_id'), 'completion_stat' => 'pending'])->orderBy("FIELD(urgency, 'critical', 'high', 'medium', 'low')", '', false)->findAll();

        return view('activity/calculation_result', [
            'schedules' => $schedules,
            'fcfs' => $fcfs,
            'noSchedule' => empty($schedules)
        ]);
    }

    public function activityHistory($page = 1)
    {
        // Will show past activities that either has been completed or missed its deadline
        $activityModel = new ActivityModel();

        $perPage = 5;
        $currentDate = date('Y-m-d H:i:s'); // Get the current timestamp

        // Fetch total activities with deadlines that have passed
        $totalActivities = $activityModel
            ->where('user_id', session('user_id'))
            ->groupStart()
            ->where('completion_stat !=', 'pending')
            ->orWhere('deadline <', $currentDate)
            ->groupEnd()
            ->countAllResults();

        $totalPages = ceil($totalActivities / $perPage);
        $offset = ($page - 1) * $perPage;

        // Fetch paginated list of activities with deadlines that have passed
        $activities = $activityModel
            ->where('user_id', session('user_id'))
            ->groupStart()
            ->where('completion_stat !=', 'pending')
            ->orWhere('deadline <', $currentDate)
            ->groupEnd()
            ->orderBy('deadline', 'DESC') // Order by most recent deadline first
            ->findAll();

        return view('activity/activity_history', [
            'activities' => $activities,
            'currentPage' => $page,
            'totalPages' => $totalPages
        ]);

        // return view('activity/activity_history');
    }
}
