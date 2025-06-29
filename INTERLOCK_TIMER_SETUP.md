# Interlock Timer System Setup

## Overview
Simple timer system that sends WhatsApp notifications when the system remains locked:
- **30 minutes**: First notification with passkey
- **60 minutes**: Second notification to different recipient

## Features
- ✅ Automatic WhatsApp notifications via Fonnte API
- ✅ Different recipients for 30m and 60m notifications
- ✅ Includes passkey in messages
- ✅ Prevents duplicate notifications
- ✅ Resets flags when system is unlocked

## Database Changes
Added to `interlocks` table:
- `notification_30m_sent` (boolean)
- `notification_60m_sent` (boolean)
- `notification_30m_sent_at` (timestamp)
- `notification_60m_sent_at` (timestamp)

## Setup

### 1. Run Migration
```bash
php artisan migrate
```

### 2. Set Up Cron Job
Add this cron job to run every 5 minutes:
```bash
*/5 * * * * php /path/to/your/project/check_interlock_timer.php
```

## WhatsApp Messages

### 30-Minute Notification:
```
🚨 ALERT INTERLOCK - URGENT 🚨

Sistem masih terkunci selama 30 menit
Waktu lock: 29/06/2025 14:30:25
Part Kanban: ABC123
Part FG: XYZ789

Passkey: SaNkEi2011..!
Segera unlock sistem!
```

### 60-Minute Notification:
```
🚨 ALERT INTERLOCK - CRITICAL 🚨

Sistem masih terkunci selama 60 menit
Waktu lock: 29/06/2025 14:30:25
Part Kanban: ABC123
Part FG: XYZ789

Passkey: SaNkEi2011..!
Segera unlock sistem!
```

## Configuration

### Change Phone Numbers
Edit in `app/Console/Commands/CheckInterlockTimer.php`:
```php
// 30-minute notification
$this->sendWhatsAppNotification($interlock, '30m', '082245792234');

// 60-minute notification  
$this->sendWhatsAppNotification($interlock, '60m', '081234567890');
```

### Change API Token
Update in the same file:
```php
'Authorization' => 'YOUR_API_TOKEN',
```

## Manual Testing
```bash
php artisan interlock:check-timer
```

## How It Works
1. System locks → Timer starts
2. 30 minutes → First notification sent
3. 60 minutes → Second notification sent to different recipient
4. System unlocked → All flags reset 