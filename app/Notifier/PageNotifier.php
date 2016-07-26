<?php

namespace App\Notifier;

use App\Mailers\PageMailer;
use App\Models\Page;
use App\Models\User;

class PageNotifier extends Notifier
{
    public function __construct(PageMailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function comment(Page $page, User $from, $comment)
    {
        $this->sendNew($page->user_id, $from->id, 'comment', $page->id);

        $to = $page->user;
        $comment = $comment;
        $link = route('page', ['id' => $page->id, 'slug' => $page->slug]);

        $this->mailer->commentMail($to, $from, $comment, $link);
    }

    public function favorite(Page $page, User $from)
    {
        if ($page->user_id !== $from->id) {
            $this->sendNew($page->user_id, $from->id, 'like', $page->id);
        }

        $this->mailer->favoriteMail($page->user, $from, $page);
    }
}
