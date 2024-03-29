# Pages Package for Laravel

Pages is a static page system for Laravel applications.

## Installation

To get started, use Composer to add the package to your project's dependencies:

```bash
composer require dmyers/laravel-pages
```

## Laravel 4

Use the `1.0` branch or the `v1.*` tags for Laravel 4 support.

## Configuration

Publish the default config file to your application so you can make modifications.

```console
$ php artisan vendor:publish
```

## Usage

To add a page, create a view in your resources directory at `resources/views/pages` and the URL will map to the path. You can nest folders and they will be included in the URL.

In your views there is a helper you can use to link to pages:

```php
page_url('about');
```

Or if you just want the path to the page:

```php
page_path('about');
```
