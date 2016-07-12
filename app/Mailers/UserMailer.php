<?php
/**
 * @author Abhimanyu Sharma <abhimanyusharma003@gmail.com>
 */
namespace App\Mailers;

use App\Models\User;

class UserMailer extends Mailer
{

    public function activation(User $user, $activationCode)
    {
        $subject = "Welcome";
        $view = 'emails.registration.confirmation';
        $data = [
            'fullname'       => $user->fullname,
            'username'       => $user->username,
            'activationcode' => $activationCode
        ];

        return $this->sendTo($user, $subject, $view, $data);
    }


    public function followMail(User $to, User $from)
    {
        if ( ! $to->email_follow) {
            return;
        }

        $subject = "New Follower";
        $view = 'emails.usermailer.follow';
        $data = [
            'senderFullname'    => ucfirst($from->fullname),
            'senderProfileLink' => route('user', ['username' => $from->username])
        ];

        return $this->sendTo($to, $subject, $view, $data);
    }

}
