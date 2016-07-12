<?php
/**
 * @author Abhimanyu Sharma <abhimanyusharma003@gmail.com>
 */
namespace App\Repository;

use Auth;
use Illuminate\Http\Request;

interface FavoriteRepositoryInterface
{

    /**
     * @param Request $request
     * @return mixed
     */
    public function favorite(Request $request);
}
