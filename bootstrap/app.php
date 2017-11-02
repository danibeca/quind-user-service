<?php

require_once __DIR__ . '/../vendor/autoload.php';

try
{
    (new Dotenv\Dotenv(__DIR__ . '/../'))->load();
} catch (Dotenv\Exception\InvalidPathException $e)
{
    //
}

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| Here we will load the environment and create the application instance
| that serves as the central piece of this framework. We'll use this
| application as an "IoC" container and router for this framework.
|
*/

$app = new Laravel\Lumen\Application(
    realpath(__DIR__ . '/../')
);

//$app->instance('path.storage', app()->basePath() . DIRECTORY_SEPARATOR . 'storage');
//$app->instance('config_path', app()->basePath() . DIRECTORY_SEPARATOR . 'config');

$app->withFacades(true);
/*$app->withFacades(true, [
    'Illuminate\Support\Facades\Request' => 'Request',
    'Spatie\Activitylog\ActivitylogFacade' => 'Activity'

]);*/

//'Illuminate\Support\Facades\Notification' => 'Notification',

$app->withEloquent();

/*
|--------------------------------------------------------------------------
| Register Container Bindings
|--------------------------------------------------------------------------
|
| Now we will register a few bindings in the service container. We will
| register the exception handler and the console kernel. You may add
| your own bindings here if you like or you can make another file.
|
*/

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

/*
|--------------------------------------------------------------------------
| Register Middleware
|--------------------------------------------------------------------------
|
| Next, we will register the middleware with the application. These can
| be global middleware that run before and after each request into a
| route or middleware that'll be assigned to some specific routes.
|
*/

//$app->middleware([
//    App\Http\Middleware\ExampleMiddleware::class
// ]);

$app->routeMiddleware([
    'auth' => App\Http\Middleware\Authenticate::class,
    'rtoken' => App\Http\Middleware\ReadToken::class,
    'cors' => \Barryvdh\Cors\HandleCors::class,
    'log'  => App\Http\Middleware\LogActivity::class
]);


/*
|--------------------------------------------------------------------------
| Register Configures
|--------------------------------------------------------------------------
|
| Next, we will register the middleware with the application. These can
| be global middleware that run before and after each request into a
| route or middleware that'll be assigned to some specific routes.
|
*/

$app->configure('mail');
$app->configure('cors');
$app->configure('services');
/*
|--------------------------------------------------------------------------
| Register Service Providers
|--------------------------------------------------------------------------
|
| Here we will register all of the application's service providers which
| are used to bind services into the container. Service providers are
| totally optional, so you are not required to uncomment this line.
|
*/

$app->register(App\Providers\AppServiceProvider::class);
$app->register(App\Providers\AuthServiceProvider::class);
$app->register(\Illuminate\Auth\Passwords\PasswordResetServiceProvider::class);
// $app->register(App\Providers\EventServiceProvider::class);

// Register auth service providers - original one and Lumen adapter
$app->register(Laravel\Passport\PassportServiceProvider::class);
$app->register(Dusterio\LumenPassport\PassportServiceProvider::class);


$app->register(Barryvdh\Cors\ServiceProvider::class);
$app->register(Illuminate\Mail\MailServiceProvider::class);
$app->register(Illuminate\Notifications\NotificationServiceProvider::class);


$app->register(Sichikawa\LaravelSendgridDriver\MailServiceProvider::class);

$app->alias('mailer', \Illuminate\Contracts\Mail\Mailer::class);


/*
|--------------------------------------------------------------------------
| Load The Application Routes
|--------------------------------------------------------------------------
|
| Next we will include the routes file so that they can all be added to
| the application. This will provide all of the URLs the application
| can respond to, as well as the controllers that may handle them.
|
*/

// Call the LumenPassport::routes method within the boot method of your application.
// This method will register the routes necessary to issue access tokens
// and revoke access tokens, clients, and personal access tokens:
Dusterio\LumenPassport\LumenPassport::routes($app->router, ['prefix' => 'api/v1/oauth', 'middleware' => ['rtoken','cors', 'log']]);

$app->router->group([
    'namespace' => 'App\Http\Controllers',
], function ($router) {
    require __DIR__ . '/../routes/api.php';
});


return $app;
