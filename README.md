# OctopyFramework

## Octopy Syntax Highlighter
https://github.com/SupianIDz/OctopySyntaxHighlighter

## Installation
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