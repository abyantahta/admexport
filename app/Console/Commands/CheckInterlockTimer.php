<?php

namespace App\Console\Commands;

use App\Models\Interlock;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class CheckInterlockTimer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'interlock:check-timer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for locked interlocks and send WhatsApp notifications at 30m and 60m';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $lockedInterlocks = Interlock::where('isLocked', true)->get();

        foreach ($lockedInterlocks as $interlock) {
            $createdAt = Carbon::parse($interlock->created_at);
            $now = Carbon::now();
            $minutesElapsed = abs($now->diffInMinutes($createdAt));

            // Send notification after 30 minutes if not already sent
            if ($minutesElapsed >= 2 && !$interlock->notification_30m_sent) {
                $this->sendWhatsAppNotification($interlock, '2m', '082245792234');
                $interlock->update([
                    'notification_30m_sent' => true,
                    'notification_30m_sent_at' => $now
                ]);
                $this->info("30-minute notification sent for interlock ID: {$interlock->id}");
            }

            // Send notification after 60 minutes if not already sent
            if ($minutesElapsed >= 4 && !$interlock->notification_60m_sent) {
                $this->sendWhatsAppNotification($interlock, '4m', '082245792234'); // Different recipient
                $interlock->update([
                    'notification_60m_sent' => true,
                    'notification_60m_sent_at' => $now
                ]);
                $this->info("60-minute notification sent for interlock ID: {$interlock->id}");
            }
        }

        $this->info('Interlock timer check completed.'.$minutesElapsed);
    }

    private function sendWhatsAppNotification($interlock, $timeFrame, $phoneNumber)
    {
        $message = $this->buildNotificationMessage($interlock, $timeFrame);
        
        try {
            $response = Http::withHeaders([
                'Authorization' => 'DcjkiWJ9gwbp7scYKowe',
            ])->withOptions(['verify' => false])->post('https://api.fonnte.com/send', [
                'target' => $phoneNumber,
                'message' => $message,
                'delay' => '2'
            ]);

            if ($response->successful()) {
                $this->info("WhatsApp notification sent successfully for {$timeFrame} timeframe to {$phoneNumber}");
            } else {
                $this->error("Failed to send WhatsApp notification: " . $response->body());
            }
        } catch (\Exception $e) {
            $this->error("Exception occurred while sending WhatsApp notification: " . $e->getMessage());
        }
    }

    private function buildNotificationMessage($interlock, $timeFrame)
    {
        $timeText = $timeFrame === '2m' ? '30 menit' : '60 menit';
        $urgency = $timeFrame === '2m' ? 'URGENT' : 'CRITICAL';
        
        return "🚨 ALERT INTERLOCK - {$urgency} 🚨\n\n" .
               "Sistem masih terkunci selama {$timeText}\n" .
               "Waktu lock: " . Carbon::parse($interlock->created_at)->format('d/m/Y H:i:s') . "\n" .
               "Part Kanban: {$interlock->part_no_kanban}\n" .
               "Part FG: {$interlock->part_no_fg}\n\n" .
            //    "Passkey: SaNkEi2011..!\n" .
               "Segera unlock sistem!";
    }
}
