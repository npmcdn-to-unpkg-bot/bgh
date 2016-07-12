<?php
/**
 * @author Abhimanyu Sharma <abhimanyusharma003@gmail.com>
 */
namespace App\Notifier;

use App\Mailers\UserMailer;
use App\Models\User;

class FollowNotifier extends Notifier
{

    public function __construct(UserMailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function follow(User $to, User $from)
    {
        $this->sendNew($to->id, $from->id, 'follow', null);

        $this->mailer->followMail($to, $from);
    }
}
