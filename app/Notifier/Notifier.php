<?php
/**
 * @author Abhimanyu Sharma <abhimanyusharma003@gmail.com>
 */
namespace App\Notifier;

use App\Models\Notification;

abstract class Notifier
{

    public function sendNew($to, $from, $type, $on_id)
    {
        $this->notification = new Notification();
        $this->notification->user_id = $to;
        $this->notification->from_id = $from;
        $this->notification->type = $type;
        $this->notification->on_id = $on_id;
        $this->notification->save();
    }
}
