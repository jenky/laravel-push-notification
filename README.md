# Laravel 5 Push Notification

######Laravel package for sending push notification.

## Installation
Require this package with composer:

```
composer require jenky/laravel-push-notification
```

or add this to `composer.json`

```
"jenky/laravel-push-notification": "^1.0"
```

After updating composer, add the ServiceProvider to the providers array in `config/app.php`
```php
Jenky\LaravelPushNotification\PushNotificationServiceProvider::class,
```

Add this to your facades in `config/app.php` (optional):

```php
'PushNotification' => Jenky\LaravelPushNotification\Facades\PushNotification::class,
```

Copy the package config to your local config with the publish command:

```
php artisan vendor:publish
```
or
```
php artisan vendor:publish --provider="Jenky\LaravelPushNotification\PushNotificationServiceProvider"
```


## Usage

WIP
