<?php
/**
 * @author Abhimanyu Sharma <abhimanyusharma003@gmail.com>
 */
namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {

         $this->app->bind(
            'App\Repository\ProductRepositoryInterface',
            'App\Repository\Eloquent\ProductRepository'
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