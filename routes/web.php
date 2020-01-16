<?php

Route::get('/', 'HomeController@index');

Route::get('test', function () {
    Session::flush();
//    print_r(Session::get('cart'));
});

/**
 * Account Routes
 */

Route::group([
    'prefix' => '/account',
    'namespace' => 'Account',
    'middleware' => [
        'auth'
    ]
], function () {
    Route::get('/', 'AccountController@index')->name('account.index');
    Route::get('/orders', 'AccountController@orders')->name('account.orders');
    Route::post('/address', 'AccountController@address')->name('account.address');
    Route::post('/save', 'AccountController@update')->name('account.save');
});

//Route::get('/email', function () {
//    $order = \App\Models\Order::with(['payment', 'address', 'items' => function ($query) {
//        $query->with('options');
//    }])->first();
//    return view('mail.order.confirmation', [
//        'order' => $order
//    ]);
//});

/**
 * Category Routes
 */

Route::group([
    'namespace' => 'Product',
    'prefix' => '/category'
], function () {
    Route::get('/{slug}', 'CategoryController@index')->name('categories.index');
});

/**
 * Product routes
 */
Route::group([
    'namespace' => 'Product',
], function () {
    Route::get('/products', 'ProductController@index')->name('products.index');
    Route::get('/inventory', 'ProductController@index')->name('products.index');
    Route::get('/product/{slug}', 'ProductController@view')->name('products.view');
});

/**
 * Cart Routes
 */
Route::group([
    'prefix' => '/cart',
    'namespace' => 'Cart'
], function () {
    Route::get('/', 'CartController@index')->name('cart.index')->middleware('cart.refresh');
    Route::get('/checkout', 'CheckoutController@index')->name('cart.checkout')->middleware(['cart.refresh', 'cart.empty']);
    Route::post('/checkout', 'CheckoutController@store')->name('cart.checkout.store')->middleware(['cart.refresh', 'cart.empty']);
    Route::get('/checkout/shipping', 'CheckoutController@shipping')->name('cart.checkout.shipping')->middleware(['cart.refresh', 'cart.empty']);
    Route::get('/checkout/payment', 'CheckoutController@payment')->name('cart.checkout.payment')->middleware(['cart.refresh', 'cart.empty']);
    Route::post('/checkout/payment/handle', 'CheckoutController@handle')->name('cart.checkout.handle');
    Route::post('/add/{id}', 'CartController@add')->name('cart.add');
    Route::post('/update', 'CartController@update')->name('cart.update');
});

Route::group([
    'namespace' => 'Order',
    'prefix' => '/order'
], function () {
    Route::get('/{hash}', 'OrderController@view')->name('order.view');
});

/**
 * Authentication Routes
 */
Route::group([
    'namespace' => 'Auth'
], function () {
    Route::get('login', 'LoginController@showLoginForm')->name('login');
    Route::post('login', 'LoginController@login');
    Route::post('logout', 'LoginController@logout')->name('logout');
    Route::get('register', 'RegisterController@showRegistrationForm')->name('register');
    Route::post('register', 'RegisterController@register');
});

/**
 * Administrator Routes
 */

Route::group([
    'namespace' => 'Admin',
    'prefix' => '/dashboard',
    'middleware' => ['auth', 'auth.admin']
], function () {

    /**
     * Product Routes
     */
    Route::group([
        'namespace' => 'Product',
        'prefix' => '/products'
    ], function () {
        Route::get('/', 'ProductController@index')->name('admin.products.index');
        Route::get('/new', 'ProductController@create')->name('admin.products.create');
        Route::post('/store', 'ProductController@store')->name('admin.products.store');
        Route::get('/search', 'ProductController@search')->name('admin.products.search');
        Route::get('/{id}', 'ProductController@edit')->name('admin.products.edit');
        Route::post('/{id}/update', 'ProductController@update')->name('admin.products.update');
    });

    Route::group([
       'namespace' => 'Setting',
       'prefix' => '/settings'
    ], function () {
        Route::get('/', 'SettingsController@index')->name('admin.settings.index');
        Route::post('/', 'SettingsController@update')->name('admin.settings.update');
    });

    /**
     * Category Routes
     */
    Route::group([
        'namespace' => 'Product',
        'prefix' => '/categories'
    ], function () {
        Route::get('/', 'CategoryController@index')->name('admin.categories.index');
        Route::get('/new', 'CategoryController@create')->name('admin.categories.create');
        Route::post('/store', 'CategoryController@store')->name('admin.categories.store');
        Route::get('/search', 'CategoryController@search')->name('admin.categories.search');
        Route::post('/{id}/update', 'CategoryController@update')->name('admin.categories.update');
        Route::post('/{id}/hide', 'CategoryController@hide')->name('admin.categories.hide');
        Route::post('/{id}/delete', 'CategoryController@delete')->name('admin.categories.delete');
        Route::get('/{id}', 'CategoryController@edit')->name('admin.categories.edit');
    });

    /**
     * Order Routes
     */
    Route::group([
        'namespace' => 'Order',
        'prefix' => '/orders'
    ], function () {
        Route::get('/', 'OrderController@index')->name('admin.orders.index');
        Route::get('/search', 'OrderController@search')->name('admin.orders.search');
        Route::get('/{hash}', 'OrderController@edit')->name('admin.orders.edit');
    });

    /**
     * User Routes
     */
    Route::group([
        'namespace' => 'User',
        'prefix' => '/users'
    ], function () {
        Route::get('/', 'UserController@index')->name('admin.users.index');
        Route::get('/{id}', 'UserController@edit')->name('admin.users.edit');
        Route::post('/{id}/update', 'UserController@update')->name('admin.users.update');
        Route::post('/{id}/block', 'UserController@block')->name('admin.users.block');
        Route::post('/{id}/delete', 'UserController@delete')->name('admin.users.delete');
    });
});
