# Laravision Crud system (v1.2)

[![Version](https://img.shields.io/packagist/v/laravision/crud.svg)](https://packagist.org/packages/laravision/crud)
[![License](https://poser.pugx.org/laravision/crud/license.svg)](https://packagist.org/packages/laravision/crud)
[![Total Downloads](https://img.shields.io/packagist/dt/laravision/crud.svg)](https://packagist.org/packages/laravision/crud)

Quickly build crud system , controller , models and views using Laravel 5. 
This package expects that you are using Laravel 5.4 or above.

[![Laravision Crud](https://s25.postimg.org/xmojto1lr/crud2.png)](https://github.com/laravision/crud/)

## Install

In order to install Laravel 5.* Laravision Crud :

1) You will need to import the laravision/crud package via composer:

```shell
composer require laravision/crud
```
2) Add the service provider to your `config/app.php` file within the `providers` key:

```php
// ...
'providers' => [
    /*
     * Package Service Providers...
     */

    Laravision\Crud\CrudServiceProvider::class,
],
// ...
```

## Usage

- Create new CRUD : 

```shell
php artisan make:crud post
```

- Create new CRUD with model : 

```shell
php artisan make:crud post --model=Post
```

- Create new CRUD with out default views path :

```shell
php artisan make:crud post --view=admin/post
```

- fixed special controller namespace :

```shell
php artisan make:crud Admin/Post
```
## Help
for more details about this package :

```shell
php artisan make:crud -h
``` 
[![Laravision Crud help](https://s25.postimg.org/ynosisilb/crud1-help.png)](https://github.com/laravision/crud/)
## License

Laravision Crud is free and open-sourced software distributed under the terms of the [MIT license](http://opensource.org/licenses/MIT).

## Note 

Please report any issue you find in the issues page.  
Pull requests are welcome.

**Code By  [Marwen Hlaoui](https://marwenhlaoui.me)**


