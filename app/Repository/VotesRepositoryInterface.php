<?php

namespace App\Repository;

use App\Models\Product;

interface VotesRepositoryInterface
{
    public function vote(Product $post);
}
