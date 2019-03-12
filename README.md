# Pages Package for Laravel 5

Pages is a static page system for Laravel 5 applications.

## Installation via Composer

Add this to you composer.json file, in the require object:

```javascript
"dmyers/laravel-pages": "dev-master"
```

After that, run composer install to install Pages.

Add the service provider to `app/config/app.php`, within the `providers` array.

```php
'providers' => [
    // ...
    Dmyers\Pages\PagesServiceProvider::class,
]
```

Add a class alias to `app/config/app.php`, within the `aliases` array.

```php
'aliases' => [
    // ...
    'Pages' => Dmyers\Pages\Facade::class,
]
```

## Laravel 4

Use the `1.0` branch or the `v1.*` tags for Laravel 4 support.

## Configuration

Publish the default config file to your application so you can make modifications.

```console
$ php artisan vendor:publish
```

## Usage

To add a page, create a view in your app directory at `views/pages/` and the URL will map to the path. You can nest folders and they will be included in the URL.

In your views there is a helper you can use to link to pages:

```php
page_url('about');
```

Or if you just want the path to the page:

```php
page_path('about');
```
