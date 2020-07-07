Url Parser
=======

Small and simple url parser library.

System Requirements
-------

You need:

- **PHP >= 7.3** but the latest stable version of PHP is recommended


Installation
--------

```bash
$ composer require selby/urlparser
```

How to use it
--------

## Basic usage

Very simple:

```php
use \Selby\UrlParser;

$s = new UrlParser('http://example.com');

echo $s->host();
```

License
-------

The MIT License (MIT). Please see [License File](LICENSE) for more information.