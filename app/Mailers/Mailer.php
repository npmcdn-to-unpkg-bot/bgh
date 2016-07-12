<?php
/**
 * @author Abhimanyu Sharma <abhimanyusharma003@gmail.com>
 */
namespace App\Mailers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;

abstract class Mailer
{

    public function sendTo($user, $subject, $view, $data = [])
    {
        if (Config::get('mail.from.address')) {
            $email = $user->email;
            Mail::queue($view, $data, function ($message) use ($email, $subject) {
                $message->to($email)->subject($subject);
            });
        }
    }
}
