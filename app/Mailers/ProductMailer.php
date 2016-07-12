<?php
/**
 * @author Abhimanyu Sharma <abhimanyusharma003@gmail.com>
 */
namespace App\Mailers;

use App\Models\Product;
use App\Models\User;

class ProductMailer extends Mailer
{

    public function commentMail(User $to, User $sender, $comment, $link)
    {
        if ( ! $to->email_comment) {
            return;
        }

        $subject = "New Comment";
        $view = 'emails.usermailer.comment';
        $data = [
            'fullname' => ucfirst($to->fullname),
            'from'     => ucfirst($sender->fullname),
            'comment'  => $comment,
            'link'     => $link
        ];

        return $this->sendTo($to, $subject, $view, $data);
    }

    public function replyMail(User $to, User $from, Product $on, $reply)
    {
        if ( ! $to->email_reply) {
            return;
        }

        $subject = 'New Reply';
        $view = 'emails.usermailer.reply';
        $data = [
            'senderFullname'    => ucfirst($from->fullname),
            'senderProfileLink' => route('user', ['username' => $from->username]),
            'productLink'         => route('product', ['id' => $on->id, 'slug' => $on->slug]),
            'reply'             => $reply,
        ];

        return $this->sendTo($to, $subject, $view, $data);
    }

    public function favoriteMail(User $to, User $from, Product $on)
    {
        if ( ! $to->email_favorite) {
            return;
        }

        $subject = 'Favorited';
        $view = 'emails.usermailer.favorite';
        $data = [
            'from'  => ucfirst($from->fullname),
            'title' => ucfirst($on->title),
            'link'  => route('product', ['id' => $on->id, 'slug' => $on->slug])
        ];

        return $this->sendTo($to, $subject, $view, $data);
    }
}
