<?php
/**
 * @author Abhimanyu Sharma <abhimanyusharma003@gmail.com>
 */
namespace App\Notifier;

use App\Mailers\ProductMailer;
use App\Models\Product;
use App\Models\User;

class ProductNotifier extends Notifier
{
    public function __construct(ProductMailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function comment(Product $product, User $from, $comment)
    {
        $this->sendNew($product->user_id, $from->id, 'comment', $product->id);

        $to = $product->user;
        $comment = $comment;
        $link = route('product', ['id' => $product->id, 'slug' => $product->slug]);

        $this->mailer->commentMail($to, $from, $comment, $link);
    }

    public function favorite(Product $product, User $from)
    {
        if ($product->user_id !== $from->id) {
            $this->sendNew($product->user_id, $from->id, 'like', $product->id);
        }

        $this->mailer->favoriteMail($product->user, $from, $product);
    }
}
