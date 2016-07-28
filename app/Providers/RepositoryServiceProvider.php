<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind(
            'App\Repository\ProfileRepositoryInterface',
            'App\Repository\Eloquent\ProfileRepository'
        );

        $this->app->bind(
            'App\Repository\ProductRepositoryInterface',
            'App\Repository\Eloquent\ProductRepository'
        );

            $this->app->bind(
            'App\Repository\MediaRepositoryInterface',
            'App\Repository\Eloquent\MediaRepository'
        );

        $this->app->bind(
            'App\Repository\PageRepositoryInterface',
            'App\Repository\Eloquent\PageRepository'
        );

        $this->app->bind(
            'App\Repository\UsersRepositoryInterface',
            'App\Repository\Eloquent\UsersRepository'
        );

        $this->app->bind(
            'App\Repository\BlogRepositoryInterface',
            'App\Repository\Eloquent\BlogRepository'
        );

        $this->app->bind(
            'App\Repository\CategoryRepositoryInterface',
            'App\Repository\Eloquent\CategoryRepository'
        );

        $this->app->bind(
            'App\Repository\ProductCategoryRepositoryInterface',
            'App\Repository\Eloquent\ProductCategoryRepository'
        );

        $this->app->bind(
            'App\Repository\FlagsRepositoryInterface',
            'App\Repository\Eloquent\FlagsRepository'
        );

        $this->app->bind(
            'App\Repository\CommentsRepositoryInterface',
            'App\Repository\Eloquent\CommentsRepository'
        );

        $this->app->bind(
            'App\Repository\ReplyRepositoryInterface',
            'App\Repository\Eloquent\ReplyRepository'
        );

        $this->app->bind(
            'App\Repository\VotesRepositoryInterface',
            'App\Repository\Eloquent\VotesRepository'
        );

        $this->app->bind(
            'App\Repository\FollowRepositoryInterface',
            'App\Repository\Eloquent\FollowRepository'
        );

        $this->app->bind(
            'App\Repository\FavoriteRepositoryInterface',
            'App\Repository\Eloquent\FavoriteRepository'
        );
    }
}
