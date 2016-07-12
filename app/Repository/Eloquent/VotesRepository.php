<?php
/**
 * @author Abhimanyu Sharma <abhimanyusharma003@gmail.com>
 */
namespace App\Repository\Eloquent;

use App\Notifier\ImageNotifier;
use Auth;
use App\Models\Product;
use Post;
use Votes;

class VotesRepository extends AbstractRepository implements VotesRepositoryInterface
{


    public function  __construct(Vote $model, ProductNotifier $notifier)
    {
        $this->model = $model;
        $this->notifier = $notifier;
    }


    public function vote(Product $products)
    {
        $vote = $this->getNew();
        $vote->product_id = $products->id;
        $vote->user_id = auth()->user()->id;
        $vote->save();

        $this->notifier->vote($products, auth()->user());

        return true;
    }
}
