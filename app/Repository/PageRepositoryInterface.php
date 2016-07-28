<?php
namespace App\Repository;

use App\Models\Page;
use Auth;
use Cache;
use DB;
use File;
use Str;

interface PageRepositoryInterface
{

    public function getById($id);

    public function getBySlug($slug);

    public function getByTags($tag);

    public function incrementViews($page);

    public function mostViewed($timeframe = null);

    public function search($input);

}


