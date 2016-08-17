<?php

// Patterns
Route::pattern('id', '[0-9]+');

Route::get('user/{username}', ['as' => 'user', 'uses' => 'UserController@getUser']);
Route::get('user/{username}/favorites', ['as' => 'users.favorites', 'uses' => 'UserController@getFavorites']);
Route::get('user/{username}/followers', ['as' => 'users.followers', 'uses' => 'UserController@getFollowers']);
Route::get('users', ['as' => 'users', 'uses' => 'UserController@getAll']);
Route::get('productos/{category?}', ['as' => 'products', 'uses' => 'ProductCategoryController@getCategory'])->where('category', '(.*)');
Route::get('producto/{id}/{slug?}', ['as' => 'product', 'uses' => 'ProductController@getIndex']);
Route::get('media/{id}/{slug?}', ['as' => 'media', 'uses' => 'MediaController@getMedia']);
Route::get('tag/{tag}', ['as' => 'tags', 'uses' => 'TagsController@getIndex']);
Route::get('notifications', ['as' => 'notifications', 'uses' => 'UserController@getNotifications']);
Route::get('tos', ['as' => 'tos', 'uses' => 'PolicyController@getTos']);
Route::get('privacy', ['as' => 'privacy', 'uses' => 'PolicyController@getPrivacy']);
Route::get('faq', ['as' => 'faq', 'uses' => 'PolicyController@getFaq']);
Route::get('about', ['as' => 'about', 'uses' => 'PolicyController@getAbout']);
Route::get('search', ['as' => 'search', 'uses' => 'ProductController@search']);
Route::get('featured', ['as' => 'products.featured', 'uses' => 'ProductController@featured']);
Route::get('popular', ['as' => 'products.popular', 'uses' => 'ProductController@mostPopular']);
Route::get('most/viewed', ['as' => 'products.most.viewed', 'uses' => 'ProductController@mostViewed']);
Route::get('most/commented', ['as' => 'products.most.commented', 'uses' => 'ProductController@mostCommented']);
Route::get('most/favorites', ['as' => 'products.most.favorites', 'uses' => 'ProductController@mostFavorited']);
Route::get('blogs', ['as' => 'blogs', 'uses' => 'BlogController@getIndex']);
Route::get('blog/{id}/{slug}', ['as' => 'blog', 'uses' => 'BlogController@getBlog']);
Route::get('lang/{lang?}', 'PolicyController@switchLang');
Route::post('queue/receive', 'PolicyController@queue');

/**
 * Guest only visit this section
 */
Route::group(['middleware' => 'guest'], function () {
    Route::get('login', ['as' => 'login', 'uses' => 'Auth\LoginController@getLogin']);
    Route::get('auth/{provider}', 'Auth\LoginController@getSocial');
    Route::get('auth/{provider}/callback', 'Auth\LoginController@getSocialCallback');
    Route::get('registration/{provider}', 'Auth\RegistrationController@getSocialRegister');
    Route::get('registration', ['as' => 'registration', 'uses' => 'Auth\RegistrationController@getIndex']);
    Route::get('registration/activate/{username}/{code}', 'Auth\RegistrationController@validateUser');
    Route::get('password/email', ['as' => 'password.reminder', 'uses' => 'Auth\PasswordController@getEmail']);
    Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
});

/**
 * Guest Post form with csrf protection
 */
Route::group(['middleware' => 'csrf:guest'], function () {
    Route::post('login', 'Auth\LoginController@postLogin');
    Route::post('registration/{provider}', 'Auth\RegistrationController@postSocialRegister');
    Route::post('password/email', 'Auth\PasswordController@postEmail');
    Route::post('password/reset/{token}', 'Auth\PasswordController@postReset');
    Route::post('registration', 'Auth\RegistrationController@postIndex');
});

/*
 * Ajax post
 */
Route::group(['middleware' => 'auth'], function () {

    //    Ajax Routes
    Route::post('favorite', 'ImageController@postFavorite');
    Route::post('follow', 'UserController@follow');
    Route::post('reply', 'ReplyController@postReply');
    Route::post('votecomment', 'CommentController@vote');
    Route::post('votereply', 'ReplyController@vote');
    Route::post('deletecomment', 'CommentController@postDeleteComment');
    Route::post('deletereply', 'ReplyController@delete');

    //    Non-Ajax Routes
    Route::get('logout', ['as' => 'logout', 'uses' => 'Auth\LoginController@getLogout']);
    Route::get('feeds', ['as' => 'users.feeds', 'uses' => 'UserController@getFeeds']);
    Route::get('user/{username}/following', ['as' => 'users.following', 'uses' => 'UserController@getFollowing']);
    Route::get('media/{any}/download', ['as' => 'media.download', 'uses' => 'MediaController@download']);
    Route::get('settings', ['as' => 'users.settings', 'uses' => 'UserController@getSettings']);
    Route::get('user/{username}/report', ['as' => 'user.report', 'uses' => 'ReportController@getReport']);

});

/**
 * Post Sections CSRF + AUTH both
 */
Route::group(['middleware' => 'csrf:auth'], function () {
    Route::post('product/{id}/{slug?}', 'ProductController@postComment');
    Route::post('settings/changepassword', 'UserController@postChangePassword');
    Route::post('settings/updateprofile', 'UserController@postUpdateProfile');
    Route::post('settings/mailsettings', 'UserController@postMailSettings');
    Route::post('settings/updateavatar', 'UserController@postUpdateAvatar');
    Route::post('user/{username}/report', 'ReportController@postReportUser');
});


Route::group(['middleware' => 'admin', 'namespace' => 'Admin'], function () {

    Route::get('admin', ['as' => 'admin', 'uses' => 'IndexController@getIndex']);

    // Comment Manger
    Route::get('admin/comments', ['as' => 'admin.comments', 'uses' => 'Comment\CommentController@getIndex']);
    Route::get('admin/comments/data', ['as' => 'admin.comments.data', 'uses' => 'Comment\CommentController@getData']);
    Route::get('admin/comments/{id}/edit', ['as' => 'admin.comments.edit', 'uses' => 'Comment\CommentController@getEdit']);
    Route::post('admin/comments/{id}/edit', ['as' => 'admin.comments.edit', 'uses' => 'Comment\CommentController@postEdit']);

    // Product
    Route::get('admin/products', ['as' => 'admin.products', 'uses' => 'Product\ProductController@getList']);
    Route::get('admin/products/data', ['as' => 'admin.products.data', 'uses' => 'Product\ProductController@getData']);
    // mas cercano a REST con verbs
    Route::get('admin/products/{id}/edit', ['as' => 'admin.products.edit', 'uses' => 'Product\ProductController@edit']);
    Route::patch('admin/products/{id}/edit', ['as' => 'admin.products.edit', 'uses' => 'Product\ProductController@patch', 'before' => 'csrf']);
    Route::delete('admin/products/{id}/edit', ['as' => 'admin.products.edit', 'uses' => 'Product\ProductController@delete', 'before' => 'csrf']);
    Route::get('admin/products/{id}/clearcache', ['as' => 'admin.products.clearcache', 'uses' => 'Product\ProductController@clearCache']);
    Route::put('admin/products/create', ['as' => 'admin.products.create', 'uses' => 'Product\ProductController@put', 'before' => 'csrf']);
    Route::post('admin/products/approve', ['as' => 'admin.products.approve', 'uses' => 'Product\ProductController@approve']);
    Route::get('admin/products/{id}/clone', ['as' => 'admin.products.clone', 'uses' => 'Product\ProductController@doClone']);

    // Product Category
    Route::get('admin/productcategories', ['as' => 'admin.productcategories', 'uses' => 'ProductCategory\ProductCategoryController@index']);
    Route::post('admin/productcategories', 'ProductCategory\ProductCategoryController@createCategory'); // Add
    Route::post('admin/productcategories/reorder', 'ProductCategory\ProductCategoryController@reorderCategory');
    Route::get('admin/productcategories/{id}/edit', ['as' => 'admin.productcategories.edit', 'uses' => 'ProductCategory\ProductCategoryController@edit']);
    Route::post('admin/productcategories/{id}/update', ['as' => 'admin.productcategories.update', 'uses' => 'ProductCategory\ProductCategoryController@update']);
    Route::delete('admin/productcategories/{id}/edit', ['as' => 'admin.productcategories.edit', 'uses' => 'ProductCategory\ProductCategoryController@delete']);
    Route::get('admin/productcategories/{id}/items', ['as' => 'admin.productcategories.items', 'uses' => 'ProductCategory\ProductCategoryController@items']);
    Route::post('admin/productcategories/{id}/items', ['as' => 'admin.productcategories.items', 'uses' => 'ProductCategory\ProductCategoryController@itemsupdate']);
    Route::get('admin/productcategories/productlist', ['as' => 'admin.productcategories.productlist', 'uses' => 'ProductCategory\ProductCategoryController@productlist']);


    // Media
    Route::get('admin/media', ['as' => 'admin.media', 'uses' => 'MediaController@getList']);
    Route::get('admin/media/data', ['as' => 'admin.media.data', 'uses' => 'MediaController@getData']);
    // mas cercano a REST con verbs
    Route::get('admin/media/{id}/edit', ['as' => 'admin.media.edit', 'uses' => 'MediaController@edit']);
    Route::patch('admin/media/{id}/edit', ['as' => 'admin.media.edit', 'uses' => 'MediaController@patch', 'before' => 'csrf']);
    Route::delete('admin/media/{id}/edit', ['as' => 'admin.media.edit', 'uses' => 'MediaController@delete', 'before' => 'csrf']);
    Route::get('admin/media/{id}/use', ['as' => 'admin.media.use', 'uses' => 'MediaController@getUse']);
    Route::get('admin/media/{id}/clearcache', ['as' => 'admin.media.clearcache', 'uses' => 'MediaController@clearCache']);
    Route::put('admin/media/create', ['as' => 'admin.media.create', 'uses' => 'MediaController@put', 'before' => 'csrf']);
    Route::get('admin/media/bulkupload', ['as' => 'admin.media.bulkupload', 'uses' => 'MediaController@getBulkUpload']);
    Route::post('admin/media/bulkupload', ['as' => 'admin.media.bulkupload', 'uses' => 'MediaController@postBulkUpload']);


    // Blogs
    Route::get('admin/blogs', ['as' => 'admin.blogs', 'uses' => 'Blog\BlogController@getIndex']);
    Route::get('admin/blogs/data', ['as' => 'admin.blogs.data', 'uses' => 'Blog\BlogController@getData']);
    Route::get('admin/blogs/create', ['as' => 'admin.blogs.create', 'uses' => 'Blog\BlogController@getCreate']);
    Route::post('admin/blogs/create', ['as' => 'admin.blogs.create', 'uses' => 'Blog\BlogController@postCreate']);
    Route::get('admin/blogs/{id}', ['as' => 'admin.blogs.edit', 'uses' => 'Blog\BlogController@getEdit']);
    Route::post('admin/blogs/{id}', ['as' => 'admin.blogs.edit', 'uses' => 'Blog\BlogController@postEdit']);


    Route::group(['middleware' => 'superadmin'], function () {

        // User Manager
        Route::get('admin/users', ['as' => 'admin.users', 'uses' => 'User\UserController@getIndex']);
        Route::get('admin/users/data', ['as' => 'admin.users.data', 'uses' => 'User\UserController@getData']);
        Route::get('admin/users/{id}/edit', ['as' => 'admin.users.edit', 'uses' => 'User\UpdateController@getEdit']);
        Route::get('admin/users/add', ['as' => 'admin.users.add', 'uses' => 'User\UserController@getAddUser']);
        Route::post('admin/users/add', ['as' => 'admin.users.add', 'uses' => 'User\UpdateController@postAddUser']);
        Route::post('admin/users/{id}/edit', ['as' => 'admin.users.edit', 'uses' => 'User\UpdateController@postEdit']);
        Route::post('admin/users/approve', ['as' => 'admin.users.approve', 'uses' => 'User\UpdateController@postApprove']);

        // Profiles
        Route::get('admin/profiles', ['as' => 'admin.profiles', 'uses' => 'ProfileController@getList']);
        Route::get('admin/profiles/data', ['as' => 'admin.profiles.data', 'uses' => 'ProfileController@getData']);
        Route::put('admin/profiles', ['as' => 'admin.profiles', 'uses' => 'ProfileController@create']); // Add
        Route::get('admin/profiles/{id}/edit', ['as' => 'admin.profiles.edit', 'uses' => 'ProfileController@edit']);
        Route::patch('admin/profiles/{id}/edit', ['as' => 'admin.profiles.edit', 'uses' => 'ProfileController@patch']);

        // Site Settings
        Route::get('admin/settings/details', ['as' => 'admin.settings.details', 'uses' => 'Settings\SettingsController@getSiteDetails']);
        Route::post('admin/settings/details', ['as' => 'admin.settings.details', 'uses' => 'Settings\UpdateController@postSiteDetails']);
        Route::get('admin/settings/limits', ['as' => 'admin.settings.limits', 'uses' => 'Settings\SettingsController@getLimitSettings']);
        Route::post('admin/settings/limits', ['as' => 'admin.settings.limits', 'uses' => 'Settings\UpdateController@postLimitSettings']);
        Route::get('admin/settings/cache', ['as' => 'admin.settings.cache', 'uses' => 'Settings\SettingsController@getCacheSettings']);
        Route::post('admin/settings/cache', ['as' => 'admin.settings.cache', 'uses' => 'Settings\UpdateController@postCacheSettings']);
        Route::get('admin/settings/sitemap', ['as' => 'admin.settings.sitemap', 'uses' => 'Settings\UpdateController@updateSiteMap']);

       // Reports
        Route::get('admin/reports', ['as' => 'admin.reports', 'uses' => 'Report\ReportController@getReports']);
        Route::get('admin/reports/data', ['as' => 'admin.reports.data', 'uses' => 'Report\ReportController@getData']);
        Route::get('admin/reports/{id}', ['as' => 'admin.reports.read', 'uses' => 'Report\ReportController@getReadReport']);

        // Pages
        Route::get('admin/pages', ['as' => 'admin.pages', 'uses' => 'PageController@getIndex']);
        Route::get('admin/pages/data', ['as' => 'admin.pages.data', 'uses' => 'PageController@getData']);
        Route::get('admin/pages/{id}/editor', ['as' => 'admin.pages.editor', 'uses' => 'PageController@editor']);
        Route::get('admin/pages/{id}/edit', ['as' => 'admin.pages.edit', 'uses' => 'PageController@edit']);
        Route::patch('admin/pages/{id}/edit', ['as' => 'admin.pages.edit', 'uses' => 'PageController@patch', 'before' => 'csrf']);
        Route::delete('admin/pages/{id}/edit', ['as' => 'admin.pages.edit', 'uses' => 'PageController@delete', 'before' => 'csrf']);
        Route::get('admin/pages/{id}/clone', ['as' => 'admin.pages.clone', 'uses' => 'PageController@doClone']);
        Route::get('admin/pages/create', ['as' => 'admin.pages.create', 'uses' => 'PageController@create']);
        Route::put('admin/pages/create', ['as' => 'admin.pages.create', 'uses' => 'PageController@put', 'before' => 'csrf']);

    });

});

// reb redirecciono el index para que vaya al home que es un Page
// Route::get('/', ['as' => 'home', 'middleware' => 'guest', 'uses' => 'HomeController@getIndex']);
Route::get('/', ['as' => 'home', 'middleware' => 'guest', 'uses' => 'PageController@getSlug']);

// reb tiene que estar al final, porque si no entra al sitio por nada, se fija si existe una page creada con ese nombre
// http://stackoverflow.com/questions/20870899/order-of-route-declarations-in-laravel-package
Route::get('/{slug?}', ['as' => 'page', 'uses' => 'PageController@getSlug'])->where('slug', '(.*)');


// Event::listen('404', function() {
//     $page = URI::current();
//     return Response::error('404');
// });

// REB Debuggear todos los querys que se ejecutan en un request dado
// Event::listen('illuminate.query', function($query)
// {
//     var_dump($query);
// });