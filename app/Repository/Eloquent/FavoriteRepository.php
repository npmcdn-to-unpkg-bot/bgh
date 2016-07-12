<?php

namespace App\Repository\Eloquent;

use App\Models\Favorite;
use App\Models\Product;
use App\Notifier\ProductNotifier;
use App\Repository\FavoriteRepositoryInterface;
use Illuminate\Http\Request;

class FavoriteRepository extends AbstractRepository implements FavoriteRepositoryInterface
{
    /**
     * @param Favorite $model
     * @param Product $products
     * @param ProductNotifier $notifer
     */
    public function __construct(Favorite $model, Product $products, ProductNotifier $notifer)
    {
        $this->model = $model;
        $this->products = $products;
        $this->notification = $notifer;
    }

    /**
     * @param Request $request
     * @return string
     */
    public function favorite(Request $request)
    {
        $favorite = $this->model->whereProductId($request->get('id'))->whereUserId($request->user()->id);
        if ($favorite->count() >= 1) {
            $favorite->delete();

            return t('Un-Favorited');
        }
        $favorite = $this->getNew();
        $favorite->user_id = $request->user()->id;
        $favorite->image_id = $request->get('id');
        $favorite->save();
        $this->notification->favorite($favorite->image, $request->user());

        return t('Favorited');
    }
}
