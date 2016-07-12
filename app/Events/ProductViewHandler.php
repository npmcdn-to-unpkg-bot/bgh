<?php

namespace App\Events;

use App\Repository\ProductRepositoryInterface;
use App\Events\Event;
use Illuminate\Session\Store;
use Illuminate\Queue\SerializesModels;


class ProductViewHandler extends Event
{
    use SerializesModels;

    public function __construct(ProductRepositoryInterface $products, Store $session)
    {
        $this->session = $session;
        $this->products = $products;
    }

    public function handle($product)
    {
        if (!$this->hasViewedTrick($product)) {
            $product = $this->products->incrementViews($product);
            $this->storeViewedTrick($product);
        }
    }

    protected function hasViewedTrick($product)
    {
        return array_key_exists($product->id, $this->getViewedTricks());
    }

    protected function getViewedTricks()
    {
        return $this->session->get('viewed_products', []);
    }

    protected function storeViewedTrick($product)
    {

        $key = 'viewed_products.' . $product->id;

        $this->session->put($key, time());
    }
}
