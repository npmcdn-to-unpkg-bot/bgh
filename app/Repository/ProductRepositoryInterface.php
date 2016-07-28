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

    public function getLatest($timeframe = null);

    public function getFeatured($timeframe = null);

    public function getByTags($tag);

    public function incrementViews($product);

    public function popular($timeframe = null);

    public function mostViewed($timeframe = null);

    public function search($input);


}
