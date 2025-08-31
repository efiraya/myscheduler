<?php 
namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\ScheduleModel;
use CodeIgniter\Email\Email;

class SendReminders extends BaseCommand
{
    protected $group       = 'Tasks';
    protected $name        = 'tasks:send-reminders';
    protected $description = 'Send email reminders for urgent tasks or upcoming deadlines';

    public function run(array $params)
    {
        $db = \Config\Database::connect();
        $emailLogsTable = $db->table('email_logs');
        $currentDate = date('Y-m-d');
        
        $lastSent = $emailLogsTable->where('email_type', 'daily_reminder')->get()->getRow();
        
        if ($lastSent && date('Y-m-d', strtotime($lastSent->last_sent)) == $currentDate) {
            echo "Email reminder already sent today.";
            return;
        }

        $scheduleModel = new ScheduleModel();
        $schedules = $scheduleModel
                        ->whereIn('urgency', ['critical', 'high'])
                        ->join('users','users.id = user_id')
                        ->select('schedules.*, users.email as user_email')
                        ->orWhere('deadline <=', date('Y-m-d H:i:s', strtotime('+1 day')))
                        ->findAll();

        if (empty($schedules)) {
            echo "No schedule to remind.";
            return;
        }

        $email = \Config\Services::email();
        $email->setFrom('zhietan@yahoo.co.id', 'Activity Reminder');

        $emails = [];
        $emailContent = "Hello, this is your reminder for the following activities:\n\n";

        // Menambahkan konten pengingat untuk setiap aktivitas
        foreach ($schedules as $schedule) {
            // Tambahkan detail aktivitas ke dalam konten email
            $emailContent .= "Activity: {$schedule['activity_name']}\n";
            $emailContent .= "Urgency: {$schedule['urgency']}\n";
            $emailContent .= "Deadline: {$schedule['deadline']}\n";
            $emailContent .= "-----------------------------------\n";
            
            // Tambahkan email pengguna ke dalam array jika belum ada
            if (!in_array($schedule['user_email'], $emails)) {
                $emails[] = $schedule['user_email'];
            }
        }

        // Kirim email ke setiap pengguna dalam array
        foreach ($emails as $emailAddress) {
            $email->setTo($emailAddress);
            $email->setSubject("Reminder: Your Upcoming Activities");
            $email->setMessage($emailContent);

            // Kirim email
            if ($email->send()) {
                CLI::write("Reminder email sent successfully to {$emailAddress}\n");
            } else {
                CLI::write("Failed to send reminder to {$emailAddress}\n");
            }
        }

        $emailLogsTable->replace([
            'email_type' => 'daily_reminder',
            'last_sent'  => date('Y-m-d H:i:s')
        ]);
        CLI::write('Reminders sent successfully.', 'green');
    }
}

?>