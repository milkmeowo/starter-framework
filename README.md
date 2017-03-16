# starter-framework

[![Latest Stable Version](https://poser.pugx.org/milkmeowo/starter-framework/v/stable)](https://packagist.org/packages/milkmeowo/starter-framework)
[![Latest Unstable Version](https://poser.pugx.org/milkmeowo/starter-framework/v/unstable)](https://packagist.org/packages/milkmeowo/starter-framework)
[![Total Downloads](https://poser.pugx.org/milkmeowo/starter-framework/downloads)](https://packagist.org/packages/milkmeowo/starter-framework)
[![StyleCI](https://styleci.io/repos/78735158/shield?branch=master)](https://styleci.io/repos/78735158)
[![Code Climate](https://codeclimate.com/github/milkmeowo/starter-framework/badges/gpa.svg)](https://codeclimate.com/github/milkmeowo/starter-framework)
[![Analytics](https://ga-beacon.appspot.com/UA-93763118-1/milkmeowo/starter-framework/readme)](https://packagist.org/packages/milkmeowo/starter-framework)
[![License](https://poser.pugx.org/milkmeowo/starter-framework/license)](https://packagist.org/packages/milkmeowo/starter-framework)

# Overview
This package is suitable for Restful API oriented projects

The Restful APIs as a backend layer which provide simple unified interfaces for frontend: Web and Mobile apps.

It utilized Laravel Passport to authenticate protected resources.

It is fully utilised Repository Design pattern.

# Features

* Dingo Api Support
* l5-Repository Support
* Laravel Passport Support
* ide-helper Support
* clockwork support
* Cors support
* All of the above are supported for laravel & lumen

# Getting started

## Installation via Composer

```
composer require milkmeowo/starter-framework
```

## Register the ServiceProvider

Register the starter-framework service provider by adding it to the providers array.

**Laravel**

```
'providers' => array(
    ...
    Milkmeowo\Framework\Base\Providers\LaravelServiceProvider::class,
)
```

**Lumen**

Modify the bootstrap flow (bootstrap/app.php file)


```
// Enable Facades
$app->withFacades();

// Enable Eloquent
$app->withEloquent();

$providers = [
    ...
    Milkmeowo\Framework\Base\Providers\LumenServiceProvider::class,
];

array_walk($providers, function ($provider) use ($app) {
    $app->register($provider);
});
```

## Publish the configure

then copy config files into your project.you can finish this manually

```
cp -R vendor/milkmeowo/starter-framework/config ./config
```

or for **Laravel** you can use
```
php artisan vendor:publish
```
and change `config/auth.php` api guards driver to passport

```
 'guards' => [
        ...
        'api' => [
            'driver' => 'passport',
            'provider' => 'users',
        ],
    ],
```

## Migrate and install Laravel Passport
```
# Create new tables for Passport
php artisan migrate

# Install encryption keys and other necessary stuff for Passport
php artisan passport:install
```

# Packages Documents

## Dingo
![](https://cloud.githubusercontent.com/assets/829059/9216039/82be51cc-40f6-11e5-88f5-f0cbd07bcc39.png)

**improve**

> Exception Handle Register

add `src/Framework/Dingo/Providers/ExceptionHandlerServiceProvider` to register the common Exception handle

* Illuminate\Auth\Access\AuthorizationException
* Illuminate\Auth\AuthenticationException
* Illuminate\Database\Eloquent\ModelNotFoundException
* Illuminate\Http\Exception\HttpResponseException
* League\OAuth2\Server\Exception\OAuthServerException
* Prettus\Validator\Exceptions\ValidatorException

> ResponseWasMorphed Event Listener

fire `src/Framework/Dingo/Listeners/AddPaginationLinksToResponse`

> Auth Providers

add `Passport` and `improved Oauth2` Auth Providers

you can use with config 
```
// file config/api.php

...
    'auth' => [
        Milkmeowo\Framework\Dingo\Auth\Providers\OAuth2::class,
        Milkmeowo\Framework\Dingo\Auth\Providers\Passport::class,
    ],
...
```

**reference:** 

[Dingo Api](https://github.com/dingo/api)

[Dingo Api Wiki documentation](https://github.com/dingo/api/wiki)

[dingo-api-wiki-zh](https://github.com/liyu001989/dingo-api-wiki-zh).

## l5-Repository
<img src="http://oomusou.io/images/laravel/laravel-architecture/arch002.svg" width="700">

**improve**

> artisan command

```
// example for generate Post Entity Repository
php artisan starter:entity Post
```
auto generate below files

```
modified:   routes/api.php
modified:   app/Providers/RepositoryServiceProvider.php

     add:   app/Api/Controllers/V1/PostsController.php
     add:   app/Models/Post.php
     add:   app/Presenters/PostPresenter.php
     add:   app/Repositories/Criteria/PostCriteria.php
     add:   app/Repositories/Eloquent/PostRepositoryEloquent.php
     add:   app/Repositories/Interfaces/PostRepository.php
     add:   app/Transformers/PostTransformer.php
     add:   app/Validators/PostValidator.php
     add:   database/migrations/2017_01_12_091412_create_posts_table.php
     add:   database/seeds/PostSeeder.php
```

* **Controller**(`index` `store` `show` `update` `destory` `trashedIndex` `trashedShow` `restore`)
* **Models**(BaseModel with ObserveEvent auto track record modify with `create(update/delete)_at(by/ip)`)
* **Presenter**
* **Transformer**(`league/fractal`)
* **Repositories/Criteria**
* **Repositories/Eloquent**(BaseEloquent with ObserverEvents,Cacheable,SoftDeletes Support)
* **Repositories/Interfaces**
* **Validator**(prettus/laravel-validator support)
* **migrations**(new blueprint methods **recordStamps**[`create(update)_at(by/ip)`] **softDeletesRecordStamps**[`delete_at(by/ip)`])
* **Seeder**
* **append Route**(file `routes/api.php(web.php)` with placeholder `//:end-routes:` can auto-inject Restful api)
* **bind Repository Interface and Eloquent**


> Routes Endpoint

files `routes/api.php` with placeholder `//:end-routes:`
```
<?php

$api = app('Dingo\Api\Routing\Router');

// v1 version API
// choose version add this in header    Accept:application/vnd.lumen.v1+json
$api->version('v1', [
    'namespace' => 'App\Api\Controllers\V1',
], function ($api) {
    
    /*
    |------------------------------------------------
    | User Routes
    |------------------------------------------------
    */

    // trashed listing
    $api->get('/users/trashed', ['as' => 'users.trashed.index', 'uses' => 'UsersController@trashedIndex']);

    // show trashed special resource
    $api->get('/users/trashed/{id}', ['as' => 'users.trashed.show', 'uses' => 'UsersController@trashedShow']);

    // restore
    $api->put('/users/{id}/restore', ['as' => 'users.restore', 'uses' => 'UsersController@restore']);

    // listing
    $api->get('/users', ['as' => 'users.index', 'uses' => 'UsersController@index']);

    // create
    $api->post('/users', ['as' => 'users.store', 'uses' => 'UsersController@store']);

    // show
    $api->get('/users/{id}', ['as' => 'users.show', 'uses' => 'UsersController@show']);

    // update
    $api->put('/users/{id}', ['as' => 'users.update', 'uses' => 'UsersController@update']);

    // delete
    $api->delete('/users/{id}', ['as' => 'users.destroy', 'uses' => 'UsersController@destroy']);

    //:end-routes:
});
```


Verb      | URI                  | Action       | Route Name
----------|-----------------------|--------------|---------------------
GET       | `/users`              | index        | users.index
GET       | `/users/trashed`      | trashedIndex | users.trashed.index
POST      | `/users`              | store        | users.store
GET       | `/users/{id}`         | show         | users.show
GET       | `/users/trashed/{id}` | trashedShow  | users.trashed.show
PUT       | `/users/{id}`         | update       | users.update
PUT       | `/users/{id}/restore` | restore      | users.restore
DELETE    | `/users/{id}`         | destroy      | users.destroy

> ObserveEvent

`Models` and `Repositories/Eloquent` has listen below methods events with $priority = 99

* onCreating()
* onCreated()
* onUpdating()
* onUpdated()
* onSaving()
* onSaved()
* onDeleting()
* onDeleted()
* onRestoring()
* onRestored()

**Models** also has default record track event listen 

fields                |    default            |    description 
:--------------------:|:---------------------:|:-----------------:
  $autoRelatedUserId  |  protect / true       | Indicates if the model should be auto set user_id.
  $userstamps         |  protect / true       | Indicates if the model should be recorded users.
  $ipstamps           |  protect / true       | Indicates if the model should be recorded ips.
  RELATED_USER_ID     |  const / 'user_id'    | The name of the "related user id" column.
  CREATED_BY          |  const / 'created_by' | The name of the "created by" column.
  UPDATED_BY          |  const / 'updated_by' | The name of the "updated by" column.
  CREATED_IP          |  const / 'created_ip' | The name of the "created ip" column.
  UPDATED_IP          |  const / 'updated_ip' | The name of the "updated ip" column.
  DELETED_IP          |  const / 'deleted_ip' | The name of the "deleted ip" column.
  DELETED_BY          |  const / 'deleted_by' | The name of the "deleted by" column.

* onCreating()

```
    // auto set related user id
    if ($this->autoRelatedUserId && empty($this->{static::RELATED_USER_ID}) && $this->hasTableColumn(static::RELATED_USER_ID)) {
        $user_id = $this->getAuthUserId();
        if ($user_id > 0) {
            $this->{static::RELATED_USER_ID} = $user_id;
        }
    }
```

* onSaving()

```
    // update ipstamps if true
    if ($this->ipstamps) {
        $this->updateIps();
    
    // update userstamps if true
    if ($this->userstamps) {
        $this->updateUsers();
    }
    
    protected function updateIps()
    {
        $ip = smart_get_client_ip();

        if (! $this->isDirty(static::UPDATED_IP) && $this->hasTableColumn(static::UPDATED_IP)) {
            $this->{static::UPDATED_IP} = $ip;
        }

        if (! $this->exists && ! $this->isDirty(static::CREATED_IP) && $this->hasTableColumn(static::CREATED_IP)) {
            $this->{static::CREATED_IP} = $ip;
        }
    }
    
    protected function updateUsers()
    {
        $user_id = $this->getAuthUserId();
        if (! ($user_id > 0)) {
            return;
        }

        if (! $this->isDirty(static::UPDATED_BY) && $this->hasTableColumn(static::UPDATED_BY)) {
            $this->{static::UPDATED_BY} = $user_id;
        }

        if (! $this->exists && ! $this->isDirty(static::CREATED_BY) && $this->hasTableColumn(static::CREATED_BY)) {
            $this->{static::CREATED_BY} = $user_id;
        }
    }
```

* onDeleting()
```
    if (static::usingSoftDeletes()) {
        if ($this->hasTableColumn(static::DELETED_BY)) {
            $this->{static::DELETED_BY} = $this->getAuthUserId();
        }

        if ($this->hasTableColumn(static::DELETED_IP)) {
            $this->{static::DELETED_IP} = smart_get_client_ip();
        }

        $this->flushEventListeners();
        $this->save();
    }
```

* onRestoring()
```
    if ($this->hasTableColumn(static::DELETED_BY)) {
        $this->{static::DELETED_BY} = null;
    }
    if ($this->hasTableColumn(static::DELETED_IP)) {
        $this->{static::DELETED_IP} = null;
    }
```

> Repositories/Eloquent

* [Cacheable](https://github.com/andersao/l5-repository#cache)

* SoftDeletes Support
  * withTrashed
  * withoutTrashed
  * onlyTrashed
  * restore
  * restoreWhere
  * ForceDelete
  * forceDeleteWhere


**reference:** 

[l5-repository](https://github.com/prettus/l5-repository)

[l5-repository Usage](https://github.com/andersao/l5-repository#usage)

[prettus/laravel-validator](https://github.com/prettus/laravel-validator)

[league/fractal](http://fractal.thephpleague.com/)

[Laravel 的中大型專案架構](http://oomusou.io/laravel/laravel-architecture/).

## Illuminate Database

**improve**

* support fulltext index
* add a set of blueprint methods
  * add ipStamps(`create_ip` `update_ip`)
  * add userStamps(`create_by` `update_by`)
  * add recordStamps(`timestamps` `ipStamps` `userStamps`)
  * add softDeletesStamps(`delete_ip` `delete_by`)
  * add softDeletesRecordStamps(`softDeletes` `softDeletesStamps`)
  * add dropIpStamps
  * add dropUserStamps
  * add dropRecordStamps
  * add dropSoftDeletesStamps
  * add dropSoftDeletesRecordStamps
  
```
<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
            
            $table->recordStamps();
            
            $table->softDeletesRecordStamps();
            
            $table->text('content')->fulltext()
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}

``` 

## laravel passport
<p align="center"><img src="https://laravel.com/assets/img/components/logo-passport.svg"></p>

**Laravel**

**reference:** 

[Laravel Passport](https://github.com/laravel/passport)

[Official Documentation](http://laravel.com/docs/master/passport).

**Lumen**

> Installed routes

Adding this service provider, will mount the following routes:

Verb | Path | NamedRoute | Controller | Action | Middleware
--- | --- | --- | --- | --- | ---
POST   | /oauth/token                             |            | \Laravel\Passport\Http\Controllers\AccessTokenController           | issueToken | -
GET    | /oauth/tokens                            |            | \Laravel\Passport\Http\Controllers\AuthorizedAccessTokenController | forUser    | auth
DELETE | /oauth/tokens/{token_id}                 |            | \Laravel\Passport\Http\Controllers\AuthorizedAccessTokenController | destroy    | auth
POST   | /oauth/token/refresh                     |            | \Laravel\Passport\Http\Controllers\TransientTokenController        | refresh    | auth
GET    | /oauth/clients                           |            | \Laravel\Passport\Http\Controllers\ClientController                | forUser    | auth
POST   | /oauth/clients                           |            | \Laravel\Passport\Http\Controllers\ClientController                | store      | auth
PUT    | /oauth/clients/{client_id}               |            | \Laravel\Passport\Http\Controllers\ClientController                | update     | auth
DELETE | /oauth/clients/{client_id}               |            | \Laravel\Passport\Http\Controllers\ClientController                | destroy    | auth
GET    | /oauth/scopes                            |            | \Laravel\Passport\Http\Controllers\ScopeController                 | all        | auth
GET    | /oauth/personal-access-tokens            |            | \Laravel\Passport\Http\Controllers\PersonalAccessTokenController   | forUser    | auth
POST   | /oauth/personal-access-tokens            |            | \Laravel\Passport\Http\Controllers\PersonalAccessTokenController   | store      | auth
DELETE | /oauth/personal-access-tokens/{token_id} |            | \Laravel\Passport\Http\Controllers\PersonalAccessTokenController   | destroy    | auth

Please note that some of the Laravel Passport's routes had to 'go away' because they are web-related and rely on sessions (eg. authorise pages). Lumen is an
API framework so only API-related routes are present.

**reference:** 

[Lumen Passport](https://github.com/dusterio/lumen-passport)

## Ide-helper
> Register service provider when `APP_ENV != production`

**Automatic phpDoc generation for Laravel Facades**


```
php artisan ide-helper:generate
```

**Automatic phpDocs for models**

```
php artisan ide-helper:models
```

**PhpStorm Meta for Container instances**

```
php artisan ide-helper:meta
```

**reference:** 

[IDE Helper Generator](https://github.com/barryvdh/laravel-ide-helper)

## Clockwork

> Register service provider when `APP_DUBUG = true` and `APP_ENV != production`

**reference:** 

[Clockwork](https://github.com/itsgoingd/clockwork)

## Cors

**Usage**

The ServiceProvider adds a route middleware you can use, called `cors`. You can apply this to a route or group to add CORS support.

```php
Route::group(['middleware' => 'cors'], function(Router $router){
    $router->get('api', 'ApiController@index');
});
```

If you want CORS to apply for all your routes, add it as global middleware in `app/http/Kernel.php`:

```php
protected $middleware = [
    ....
    \Barryvdh\Cors\HandleCors::class
];
```


**reference:** 

[barryvdh/laravel-cors](https://github.com/barryvdh/laravel-cors)

## License

The starter-framework is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
