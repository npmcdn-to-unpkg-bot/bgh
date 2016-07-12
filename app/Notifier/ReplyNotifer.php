<?php

namespace App\Notifier;

use App\Mailers\ProductMailer;
use App\Models\Product;
use App\Models\User;

class ReplyNotifer extends Notifier
{

    public function __construct(ImageMailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function replyNotice(User $to, User $from, Product $on, $reply, $sendEmail = false)
    {
        $this->sendNew($to->id, $from->id, 'reply', $on->id);
        if ($sendEmail === true) {
            $this->mailer->replyMail($to, $from, $on, $reply);
        }
    }
}
