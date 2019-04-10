![Screenshot](public/img/logo.png)

## What is Octopy Framework?

Octopy is a lightweight PHP web framework inspired by Laravel Framework and its use is almost like Laravel.

With several features such as:

* Octopy console
* Octopy template engine
* Middleware
* Exception handler
* Debugbar

and much more.

In addition to the features above this framework is very light, only measuring less than 1.5 megabytes because there is no dependency on other packages, but you are free to use packages from outside using composer.

## Installation
### Composer
  `composer create-project supianidz/octopyframework OctopyFramework dev-master`

  `cd OctopyFramework`

  `php octopy serve`
  
### GIT
  `git clone https://github.com/SupianIDz/OctopyFramework.git`

  `cd OctopyFramework`

  `php octopy serve`

Open http://127.0.0.1:1337 in your browser.

## Routing Example

### HTTP

```php
// GET http://127.0.0.1:1337/example
$this->get('/example', function(){
  return 'Hi People!';
});

// GET http://127.0.0.1:1337/example/supianidz
$this->get('/example/:name', function($name){
  return 'Hi ' . $name;
});

// POST http://127.0.0.1:1337/example
$this->post('/example', 'ControllerName@index')->name('mycontroller');
```

ControllerName.php
```php
<?php

namespace App\HTTP\Controller;

use App\HTTP\Controller;
use Octopy\HTTP\Request;

class ControllerName extends Controller
{ 
  /**
   * @param  Request $request
   */
    public function index(Request $request)
    {
      dd($request);
    }
}
```
### Console

```php
$this->command('mycommand', function(Octopy\Console\Argv $argv, Octopy\Console\Output $output){
  return $output->success('Hallo friend !!!');
})->describe('Some Text');
```