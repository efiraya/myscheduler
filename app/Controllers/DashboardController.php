<?php

namespace App\Controllers;

use App\Models\ActivityModel;
use App\Models\ScheduleModel;
use App\Models\UserModel; // Import the UserModel
use CodeIgniter\Controller;
use App\Models\EmailLog;
use CodeIgniter\Database\Exceptions\DataException;

class DashboardController extends Controller
{
    public function dashboard()
    {
        date_default_timezone_set('Asia/Jakarta');
        $full_name = session()->get('full_name');

        $scheduleModel = new ScheduleModel();
        $today = date('Y-m-d');
        $requiredDuration = 8;

        $selectedActivities = [];
        $totalDuration = 0;
        $processedActivityIds = [];

        $maxDate = date('Y-m-d', strtotime('+30 days', strtotime($today)));

        while ($totalDuration < $requiredDuration) {
            $schedules = $scheduleModel->where('user_id', session('user_id'))
                ->where('deadline >=', date('Y-m-d h:i:s'))
                //->where("DATE(deadline) BETWEEN '$today' AND '$maxDate'")
                ->orderBy("FIELD(urgency, 'critical', 'high', 'medium', 'low')")
                ->findAll();

            foreach ($schedules as $schedule) {
                if (in_array($schedule['activity_id'], $processedActivityIds)) {
                    continue;
                }

                if ($totalDuration + $schedule['duration'] <= $requiredDuration) {
                    $selectedActivities[] = $schedule;
                    $totalDuration += $schedule['duration'];
                    $processedActivityIds[] = $schedule['activity_id'];
                }
            }

            $today = date('Y-m-d', strtotime('+1 day', strtotime($today)));

            if ($today > $maxDate) {
                break;
            }
        }

        $oneWeekAgo = date('Y-m-d H:i:s', strtotime('-1 week'));
        $dataOneWeekAgo = $scheduleModel
            ->groupStart()
            ->where('user_id', session('user_id')) // Add the user_id condition
            ->where('deadline <', date('Y-m-d h:i:s'))
            ->where('deadline >', $oneWeekAgo)
            ->groupEnd()
            ->orGroupStart() // Add another group for the 'overdue' condition
            ->where('user_id', session('user_id')) // Ensure the 'overdue' condition also checks user_id
            ->where('completion_stat', 'overdue')
            ->groupEnd()
            ->findAll();

        return view('/dashboard', [
            'full_name' => $full_name,
            'schedules' => $selectedActivities,
            'oneWeekAgo' => $dataOneWeekAgo,
            'noSchedule' => empty($selectedActivities)
        ]);
    }

    public function help()
    {
        return view('/help');
    }

    public function sendEmailReminder()
    {
        date_default_timezone_set('Asia/Jakarta');

        $today = date('Y-m-d H:i:s');
        $threeDaysLater = date('Y-m-d', strtotime('+3 days', strtotime($today)));
        $oneDayLater = date('Y-m-d', strtotime('+1 day', strtotime($today)));
        
        $scheduleModel = new ScheduleModel();
        $schedulesThreeDay = $scheduleModel->join('users', 'users.id = user_id')
            ->join('activities', 'activities.id = activity_id')
            ->select('schedules.*, users.email as user_email, activities.fixed_deadline, activities.activity_name, activities.activity_type')
            //->where('schedules.user_id =', session('user_id'))
            ->where('DATE(schedules.deadline)', $threeDaysLater)
            ->findAll();

        $scheduleOneDay = $scheduleModel->join('users', 'users.id = user_id')
            ->join('activities', 'activities.id = activity_id')
            ->select('schedules.*, users.email as user_email, activities.fixed_deadline, activities.activity_name, activities.activity_type')
            //->where('schedules.user_id =', session('user_id'))
            ->where('DATE(schedules.deadline)', $oneDayLater)
            ->findAll();   
        
        
        if (!empty($scheduleOneDay)) {
            $groupedSchedules = [];
            foreach ($scheduleOneDay as $schedule) {
                $groupedSchedules[$schedule['user_email']][] = $schedule;
            }
            
            foreach ($groupedSchedules as $userEmail => $activities) {
                // Mulai konten email
                $emailContent = "Dear {$userEmail},\n\n";
                $emailContent .= "Hari ini adalah hari terakhir pengerjaan Aktivitas! Berikut detail dari aktivitas Anda:\n\n";

                foreach ($activities as $activity) {
                    $type = match ($activity['activity_type']) {
                        'work' => 'Kerja',
                        'personal' => 'Personal',
                        'college' => 'Kuliah',
                        default => 'Organisasi',
                    };
                    
                    $fixed = $activity['fixed_deadline'] == 0 ? 'Tidak wajib dipenuhi' : 'Wajib dipenuhi';

                    $emailContent .= "-------------\n";
                    $emailContent .= "Nama Aktivitas: {$activity['activity_name']}\n";
                    $emailContent .= "Deadline: {$activity['deadline']}\n";
                    $emailContent .= "Tipe Aktivitas: {$type}\n";
                    $emailContent .= "Sifat Deadline Aktivitas: {$fixed}\n";
                    $emailContent .= "-------------\n";
                }

                $emailContent .= "\nSegera menyelesaikan atau menjalankan aktivitas ini agar tidak terlewat. Ayo, jangan ditunda dan selesaikan sebelum terlambat!\n\n";
                $email = \Config\Services::email();
                // Kirim email ke pengguna
                $email->setTo($userEmail);
                $email->setSubject('Pengingat Deadline Aktivitas');
                $email->setMessage($emailContent);
                
                if ($email->send()) {
                    echo "Email berhasil dikirim ke {$userEmail}\n";
                    
                } else {
                    echo "Gagal mengirim email ke {$userEmail}\n";
                    echo $email->printDebugger(['headers']);
                    
                }
            }
        } else {
            echo "Tidak ada aktivitas dengan deadline dalam 1 hari ke depan.\n";
            
        }
        

        if (!empty($schedulesThreeDay)) {
            $groupedSchedules = [];
            foreach ($schedulesThreeDay as $schedule) {
                $groupedSchedules[$schedule['user_email']][] = $schedule;
            }

            foreach ($groupedSchedules as $userEmail => $activities) {
                // Mulai konten email
                $emailContent = "Dear {$userEmail},\n\n";
                $emailContent .= "Ingin mengingatkan bahwa deadline untuk beberapa aktivitas Anda sudah semakin dekat! Berikut detail dari aktivitas Anda:\n\n";

                foreach ($activities as $activity) {
                    $type = match ($activity['activity_type']) {
                        'work' => 'Kerja',
                        'personal' => 'Personal',
                        'college' => 'Kuliah',
                        default => 'Organisasi',
                    };

                    $fixed = $activity['fixed_deadline'] == 0 ? 'Tidak wajib dipenuhi' : 'Wajib dipenuhi';

                    $emailContent .= "-------------\n";
                    $emailContent .= "Nama Aktivitas: {$activity['activity_name']}\n";
                    $emailContent .= "Deadline: {$activity['deadline']}\n";
                    $emailContent .= "Tipe Aktivitas: {$type}\n";
                    $emailContent .= "Sifat Deadline Aktivitas: {$fixed}\n";
                    $emailContent .= "-------------\n";
                }

                $emailContent .= "\nSilahkan untuk segera menyelesaikan atau menjalankan aktivitas ini agar tidak terlewat. Jangan ditunda, dan selesaikan sebelum terlambat!\n\n";
                $emaill = \Config\Services::email();
                // Kirim email ke pengguna
                $emaill->setTo($userEmail);
                $emaill->setSubject('Pengingat Deadline Aktivitas');
                $emaill->setMessage($emailContent);

                if ($emaill->send()) {
                    echo "Email berhasil dikirim ke {$userEmail}\n";
                    return;
                } else {
                    echo "Gagal mengirim email ke {$userEmail}\n";
                    return;
                }
            }
        } else {
            echo "Tidak ada aktivitas dengan deadline dalam 3 hari ke depan.\n";
            return;
        }
    }
    
    public function cronJobScheduleActivity()
    {
        $activityModel = new ActivityModel();
        $activities = $activityModel->where('user_id', session('user_id'))->where('completion_stat', 'pending')->findAll();
        foreach ($activities as $activity) {
            $activity['weight'] = $this->calculatePriorityWeight($activity);

            $dateTimeObject1 = date_create($activity['deadline']);
            $dateTimeObject2 = date_create((new \DateTime())->format('Y-m-d H:i:s'));

            $interval = date_diff($dateTimeObject1, $dateTimeObject2);
            if (!$interval->invert) {
                $activity['completion_status'] = 'overdue';
            }

            $activityModel->update($activity['id'], $activity);
        }
        $activities = $activityModel->where('user_id', session('user_id'))->where('completion_stat', 'pending')->where('deadline >=', date('Y-m-d h:i:s'))->findAll();
        $scheduledActivities = $this->applySchedulingAlgorithm($activities);

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
        echo "Email reminders sent successfully.";
        return;
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
