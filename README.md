# Dealer - Easily search for data with all the power of Eloquent

[![Latest Version on Packagist](https://img.shields.io/packagist/v/marcus-campos/dealer.svg?style=flat-square)](https://packagist.org/packages/marcus-campos/dealer)
[![Build Status](https://img.shields.io/travis/marcus-campos/dealer/master.svg?style=flat-square)](https://travis-ci.org/marcus-campos/dealer)
[![Quality Score](https://img.shields.io/scrutinizer/g/marcus-campos/dealer.svg?style=flat-square)](https://scrutinizer-ci.com/g/marcus-campos/dealer)
[![Total Downloads](https://img.shields.io/packagist/dt/marcus-campos/dealer.svg?style=flat-square)](https://packagist.org/packages/marcus-campos/dealer)

There is no need for much implementation, just create an endpoint for your searches and start the server of your application and write your query.

## Installation

You can install the package via composer:

```bash
composer require marcus-campos/dealer
```

## Usage

Create a Controller. E.g:

``` php
<?php

namespace App\Http\Controllers;

use App\Http\Search;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Index
     *
     * @param Request $request
     * @return Json
     */
    public function index(Request $request)
    {
        $search = new Search();
        return response()->json($search->resolve($request->query('q')));
    }
}
```

Create a route. E.g:

``` php
Route::get('search', "SearchController@index");
```

Make a query. E.g:

```
GET /api/search?q=user(id,name,email)->filters(filterByName('Marcus'))->orderBy(id,desc)->paginate(30)&page=1 HTTP/1.1
Host: localhost:8080
cache-control: no-cache
```

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email campos.v.marcus@gmail.com instead of using the issue tracker.

## Credits

- [Marcus Vinícius Campos](https://github.com/marcus-campos)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).