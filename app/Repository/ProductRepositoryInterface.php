<?php
namespace App\Repository;

use App\Models\Product;
use Auth;
use Cache;
use DB;
use File;
use Str;

interface ProductRepositoryInterface
{

    public function getById($id);

    public function getLatest($category = null, $timeframe = null);

    public function getFeatured($category = null, $timeframe = null);

    public function getByTags($tag);

    public function incrementViews($product);

    // public function mostCommented($category = null, $timeframe = null);

    public function popular($category = null, $timeframe = null);

    // public function mostFavorited($category = null, $timeframe = null);

    public function mostDownloaded($category = null, $timeframe = null);

    public function mostViewed($category = null, $timeframe = null);

    public function search($input, $category = null);

    // public function findNextProduct(Product $product);

    // public function findPreviousProduct(Product $product);

}
