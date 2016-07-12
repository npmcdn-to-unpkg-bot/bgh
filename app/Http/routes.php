<?php

// Patterns
Route::pattern('id', '[0-9]+');

Route::get('/', ['as' => 'home', 'middleware' => 'guest', 'uses' => 'HomeController@getIndex']);
Route::get('user/{username}', ['as' => 'user', 'uses' => 'UserController@getUser']);
Route::get('user/{username}/favorites', ['as' => 'users.favorites', 'uses' => 'UserController@getFavorites']);
Route::get('user/{username}/followers', ['as' => 'users.followers', 'uses' => 'UserController@getFollowers']);
Route::get('user/{username}/rss', ['as' => 'users.rss', 'uses' => 'UserController@getRss']);
Route::get('users', ['as' => 'users', 'uses' => 'UserController@getAll']);
Route::get('productos/{category?}', ['as' => 'products', 'uses' => 'ProductCategoryController@getCategory'])->where('category', '(.*)');
Route::get('producto/{id}/{slug?}', ['as' => 'product', 'uses' => 'ProductController@getIndex']);
Route::get('tag/{tag}', ['as' => 'tags', 'uses' => 'TagsController@getIndex']);
Route::get('tag/{tag}/rss', 'TagsController@getRss');
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

Route::resource('demo', 'DemoController');

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
    Route::get('product/{any}/download', ['as' => 'products.download', 'uses' => 'ProductController@download']);
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

/**
 * Admin section users with admin privileges can access this area
 */
Route::group(['middleware' => 'admin', 'namespace' => 'Admin'], function () {
    Route::get('admin', ['as' => 'admin', 'uses' => 'IndexController@getIndex']);

// User Manager
    Route::get('admin/users', ['as' => 'admin.users', 'uses' => 'User\UserController@getIndex']);
    Route::get('admin/users/data', ['as' => 'admin.users.data', 'uses' => 'User\UserController@getData']);
    Route::get('admin/users/{id}/edit', ['as' => 'admin.users.edit', 'uses' => 'User\UpdateController@getEdit']);
    Route::get('admin/users/add', ['as' => 'admin.users.add', 'uses' => 'User\UserController@getAddUser']);
    Route::post('admin/users/add', ['as' => 'admin.users.add', 'uses' => 'User\UpdateController@postAddUser']);
    Route::post('admin/users/{id}/edit', ['as' => 'admin.users.edit', 'uses' => 'User\UpdateController@postEdit']);
    Route::post('admin/users/approve', ['as' => 'admin.users.approve', 'uses' => 'User\UpdateController@postApprove']);

// Comment Manger
    Route::get('admin/comments', ['as' => 'admin.comments', 'uses' => 'Comment\CommentController@getIndex']);
    Route::get('admin/comments/data', ['as' => 'admin.comments.data', 'uses' => 'Comment\CommentController@getData']);
    Route::get('admin/comments/{id}/edit', ['as' => 'admin.comments.edit', 'uses' => 'Comment\CommentController@getEdit']);
    Route::post('admin/comments/{id}/edit', ['as' => 'admin.comments.edit', 'uses' => 'Comment\CommentController@postEdit']);

// Product
    Route::get('admin/products', ['as' => 'admin.products', 'uses' => 'Product\ProductController@getIndex']);
    Route::get('admin/products/data', ['as' => 'admin.products.data', 'uses' => 'Product\ProductController@getData']);

    Route::get('admin/products/{id}/edit', ['as' => 'admin.products.edit', 'uses' => 'Product\ProductController@getEdit']);
    Route::post('admin/products/{id}/edit', ['as' => 'admin.products.edit', 'uses' => 'Product\ProductController@postEdit']);
    Route::get('admin/products/new', ['as' => 'admin.products.new', 'uses' => 'Product\ProductController@getNew']);
    Route::post('admin/products/new', ['as' => 'admin.products.new', 'uses' => 'Product\ProductController@postNew']);

    Route::post('admin/products/approve', ['as' => 'admin.products.approve', 'uses' => 'Product\ProductController@approve']);
    Route::post('admin/products/clearcache', ['as' => 'admin.products.clearcache', 'uses' => 'Product\ProductController@clearCache']);
    Route::get('admin/products/bulkupload', ['as' => 'admin.products.bulkupload', 'uses' => 'Product\ProductController@getBulkUpload']);
    Route::post('admin/products/bulkupload', ['as' => 'admin.products.bulkupload', 'uses' => 'Product\ProductController@postBulkUpload']);

// Product Category
    Route::get('admin/productcategories', ['as' => 'admin.productcategories', 'uses' => 'ProductCategory\ProductCategoryController@index']);
    Route::post('admin/productcategories', 'ProductCategory\ProductCategoryController@createCategory');
    Route::post('admin/productcategories/reorder', 'ProductCategory\ProductCategoryController@reorderCategory');
    Route::post('admin/productcategories/update', 'ProductCategory\ProductCategoryController@updateCategory');

    Route::get('admin/productcategories/{id}/edit', ['as' => 'admin.productcategories.edit', 'uses' => 'ProductCategory\ProductCategoryController@edit']);
    Route::post('admin/productcategories/{id}/update', ['as' => 'admin.productcategories.update', 'uses' => 'ProductCategory\ProductCategoryController@update']);
    Route::get('admin/productcategories/{id}/order', ['as' => 'admin.productcategories.order', 'uses' => 'ProductCategory\ProductCategoryController@order']);
    Route::post('admin/productcategories/{id}/reorder', ['as' => 'admin.productcategories.reorder', 'uses' => 'ProductCategory\ProductCategoryController@reorder']);
    Route::get('admin/productcategories/productlist', ['as' => 'admin.productcategories.productlist', 'uses' => 'ProductCategory\ProductCategoryController@productlist']);

// Site Settings
    Route::get('admin/settings/details', ['as' => 'admin.settings.details', 'uses' => 'Settings\SettingsController@getSiteDetails']);
    Route::post('admin/settings/details', ['as' => 'admin.settings.details', 'uses' => 'Settings\UpdateController@postSiteDetails']);
    Route::get('admin/settings/limits', ['as' => 'admin.settings.limits', 'uses' => 'Settings\SettingsController@getLimitSettings']);
    Route::post('admin/settings/limits', ['as' => 'admin.settings.limits', 'uses' => 'Settings\UpdateController@postLimitSettings']);
    Route::get('admin/settings/cache', ['as' => 'admin.settings.cache', 'uses' => 'Settings\SettingsController@getCacheSettings']);
    Route::post('admin/settings/cache', ['as' => 'admin.settings.cache', 'uses' => 'Settings\UpdateController@postCacheSettings']);
    Route::get('admin/settings/sitemap', ['as' => 'admin.settings.sitemap', 'uses' => 'Settings\UpdateController@updateSiteMap']);

// Blogs
    Route::get('admin/blogs', ['as' => 'admin.blogs', 'uses' => 'Blog\BlogController@getIndex']);
    Route::get('admin/blogs/data', ['as' => 'admin.blogs.data', 'uses' => 'Blog\BlogController@getData']);
    Route::get('admin/blogs/create', ['as' => 'admin.blogs.create', 'uses' => 'Blog\BlogController@getCreate']);
    Route::post('admin/blogs/create', ['as' => 'admin.blogs.create', 'uses' => 'Blog\BlogController@postCreate']);
    Route::get('admin/blogs/{id}', ['as' => 'admin.blogs.edit', 'uses' => 'Blog\BlogController@getEdit']);
    Route::post('admin/blogs/{id}', ['as' => 'admin.blogs.edit', 'uses' => 'Blog\BlogController@postEdit']);

// Reports
    Route::get('admin/reports', ['as' => 'admin.reports', 'uses' => 'Report\ReportController@getReports']);
    Route::get('admin/reports/data', ['as' => 'admin.reports.data', 'uses' => 'Report\ReportController@getData']);
    Route::get('admin/reports/{id}', ['as' => 'admin.reports.read', 'uses' => 'Report\ReportController@getReadReport']);
});


// REB Debuggear todos los querys que se ejecutan en un request dado
// Event::listen('illuminate.query', function($query)
// {
//     var_dump($query);
// });