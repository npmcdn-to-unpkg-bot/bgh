<?php

namespace App\Repository;

use App\Repository\ImageRepository;

interface ProductCategoryRepositoryInterface
{

	public function getAncestors($slug);

	public function getLink($slug);

    public function getBySlug($slug);

    public function getItems();
}
