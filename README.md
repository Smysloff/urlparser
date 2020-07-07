Url Parser
=======

Small and simple url parser library.

System Requirements
-------

You need:

- **PHP >= 7.4** but the latest stable version of PHP is recommended


Installation
--------

```bash
$ composer require selby/urlparser
```

How to use it
--------

### Basic usage

```php
use \Selby\UrlParser;

$url = new UrlParser('http://example.com');

echo $url->host();
```

License
-------

The MIT License (MIT). Please see [License File](LICENSE) for more information.