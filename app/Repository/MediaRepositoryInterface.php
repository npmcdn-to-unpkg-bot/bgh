<?php
namespace App\Repository;

use App\Models\Media;
use Auth;
use Cache;
use DB;
use File;
use Str;

interface MediaRepositoryInterface
{

    public function getById($id);

    public function getByTags($tag);

    public function search($input);


}
