<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//Route::get('/test', 'HomeController@test')->name('test');

Route::any('/logout', 'Auth\CustomLoginController@logout')->middleware(['auth'])->name('logout');

//Landing info
Route::get('/landing/info', 'HomeController@landingInfo')->name('landing.info');

Route::middleware(['guest'])->group(function () {
    //User authentication
    Route::get('/', 'HomeController@index')->name('login');
    Route::get('/register/owner', 'HomeController@ownerView')->name('register.owner');
    Route::get('/register/driver', 'HomeController@driverView')->name('register.driver');
    Route::post('/register/owner', 'HomeController@registernewowner')->name('register.owner');
    Route::get('/register', 'HomeController@register')->name('register');
    Route::post('/register/driver', 'HomeController@registernewdriver')->name('register.driver');
    Route::get('/verify/email', 'HomeController@verifyemail')->name('verify.email');
    Route::post('/login', 'Auth\CustomLoginController@login')->name('login.submit');
});

Route::middleware(['auth', 'owner'])->group(function () {
    //Pet Owner
    Route::get('/owner', 'OwnerController@index')->name('owner.home');
    Route::get('/owner/transport', 'OwnerController@createTransport')->name('owner.create.transport');
    Route::get('/owner/transports/{transport}', 'OwnerController@transportDetails')->name('owner.transport.details');
    Route::get('/owner/transports/{transport}/payout-code', 'OwnerController@showPayoutCode')->name('owner.transport.payout.code');
    Route::get('/owner/transports/{bid}/driver', 'OwnerController@showDriverData')->name('owner.bid.driver');

    Route::get('/owner/transports/{id}/edit', 'OwnerController@update')->name('owner.transport.edit');
    Route::get('/driver-profile/{driver}', 'OwnerController@showDriverProfile')->name('owner.driver.profile');

    Route::put('/owner/transports/{id}', 'OwnerController@updateTransport')->name('owner.put.transport');

    Route::post('/owner/pet', 'OwnerController@addPet')->name('owner.add.pet');
    Route::post('/owner/transport', 'OwnerController@transport')->name('owner.post.transport');
    Route::post('/owner/bid/{bid}/finish', 'OwnerController@finishTransport')->name('owner.transport.finish');
    Route::post('/owner/bid/{bid}/accept', 'OwnerController@acceptBid')->name('owner.bid.accept');
    Route::delete('owner/bid/{id}', 'OwnerController@deleteBid')->name('owner.delete.bid');
});

Route::middleware(['auth', 'driver'])->group(function () {
    //Driver
    Route::get('/driver', 'DriverController@index')->name('driver.home');
    Route::get('/driver/search', 'DriverController@baseSearch')->name('driver.search.base');
    Route::get('/driver/search/geo', 'DriverController@geoSearch')->name('driver.search.geo');
    Route::get('/driver/cashout', 'DriverController@showCashout')->name('driver.show.cashout');
    Route::get('/driver/transport/{transport}', 'DriverController@transportDetails')->name('driver.transportDetails');

    Route::get('/driver/owner-details/{transport}', 'DriverController@transportOwnerDetails');

    Route::post('/driver/bid/{transport}', 'DriverController@bid')->name('driver.bid');
    // Route::post('/driver/buy/{transport}', 'DriverController@buy')->name('driver.buy');
    Route::post('/driver/cashout/{bid}', 'StripeController@payout')->name('driver.cashout');

});

Route::middleware(['auth'])->group(function () {
    //Profile
    Route::get('/profile', 'ProfileController@index')->name('profile.index');
    Route::post('/profile/update', 'ProfileController@update')->name('profile.update');

    //Stripe
    Route::post('/stripe/getpayment/{bid}', 'StripeController@Getpayment')-> name('stripe.Getpayment');
    Route::get('/stripe/connect', 'StripeController@showConnect')->name('stripe.connect');
    Route::get('/stripe/connect/verify', 'StripeController@verifyConnect')->name('stripe.connect.post');
    Route::post('/transport/{transport}/comment', 'CommentController@postComment')->name('comment.post');
    Route::post('/notification/{id}', 'ProfileController@readNotification')->name('notification.read');
});

Route::prefix('/admin')->namespace('Admin')->middleware(['auth', 'admin'])->group(function () {
//Route::prefix('/admin')->namespace('Admin')->middleware(['admin'])->group(function () {
    Route::get('/', 'AdminController@index')->name('admin.index');
    Route::get('/owners', 'AdminController@getAllOwners')->name('admin.owners');
    Route::get('/drivers', 'AdminController@getAllDrivers')->name('admin.drivers');
    Route::delete('/users/{id}', 'AdminController@deleteUser')->name('admin.user.delete');
    Route::get('/transportations', 'AdminController@getAllTransportations')->name('admin.transportations');
    Route::delete('/transportations/{id}', 'AdminController@deleteTransport');
});

Route::get('/about-us', 'HomeController@aboutUs')->name('about-us');
Route::get('/contact-us', 'HomeController@contactUs')->name('contact-us');
Route::post('/contact-us/submit', 'HomeController@contactUsSubmit')->name('contact-us.submit');
Route::get('/thank-you', 'HomeController@thankYou')->name('thank-you');
Route::get('/faq', 'HomeController@faq')->name('faq');