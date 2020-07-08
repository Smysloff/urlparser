<?php

/*
The MIT License (MIT)
Copyright © 2020 Alexander Smyslov

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the “Software”), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED “AS IS”, WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

namespace Selby;


/**
 * Small and simple url parser class
 */
class UrlParser
{
    /**
     * 
     * 
     * @var string
     */
    public string $url;


    /**
     * @var string
     */
    public string $link;

    /**
     * url scheme
     * 
     * @var string|null
     */
    public ?string $scheme;
 
    /**
     * url host
     * 
     * @var string|null
     */
    public ?string $host;
    
    /**
     * url port
     * 
     * @var string|null
     */
    public ?string $port;

    /**
     * url user
     * 
     * @var string|null
     */
    public ?string $user;

    /**
     * @var string|null
     */
    public ?string $pass;

    /**
     * url path
     * 
     * @var string|null
     */
    public ?string $path;

    /**
     * url query
     * 
     * @var string|null
     */
    public ?string $query;

    /**
     * @var string|null
     */
    public ?string $fragment;

    /**
     * @var string|null
     */
    public ?string $hostAsLink;

    /**
     * array of url slugs
     * 
     * @var array
     */
    public array $slugs;

    /**
     * get-params
     * 
     * @var object
     */
    public ?object $params;


    /**
     * @param string $url needle url string
     */
    public function __construct(?string $url = null)
    {
        $this->url  = $url ?: $this->getCurrentUrl();
        $this->link = $this->getLink($this->url);
        $components = (object) parse_url($this->link);

        $this->scheme   = $components->scheme   ?? null;
        $this->host     = $components->host     ?? null;
        $this->port     = $components->port     ?? null;
        $this->user     = $components->user     ?? null;
        $this->pass     = $components->pass     ?? null;
        $this->path     = $components->path     ?? null;
        $this->query    = $components->query    ?? null;
        $this->fragment = $components->fragment ?? null;

        $this->hostAsLink = $this->getHostAsLink($this->scheme, $this->host);

        $this->slugs = explode('/', trim($this->path, '/'));

        // $this->params
        if (!empty($this->query)) {
            $this->params = new \stdClass();
            foreach ( explode('&', $this->query) as $part ) {
                $parts = explode('=', $part);
                $param = $parts[0];
                $value = $parts[1] ?? null;
                $this->params->$param = $value;
            }
        } else {
            $this->params = null;
        }
        
    }


    /**
     * Возвращает полный адрес текущей страницы
     * 
     * @return string
     */
    protected function getCurrentUrl(): string
    {
        return ((empty($_SERVER['HTTPS'])) ? 'http' : 'https') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];     
    }

    /**
     * Проверяет url на соответствие формату,
     * который принимается функцией parse_url.
     * При возможности - модифицирует url о приемлемого формата.
     * 
     * @param string|null $url
     * 
     * @return string
     */
    protected function getLink(?string $url = null): ?string
    {
        // если url в приемлемом формате для parse_url,
        // то возвращаем его как он есть
        $pattern = '#^(?:https?)?:?//([^/]+).*$#';
        if ( preg_match($pattern, $url) )
            return $url;

        // если формат не приемлемый, но найден домен
        // возвращаем url, достроенный до приемлемого формата
        $pattern = '#^([^/\@\?\s\.\-\+\!\"\#\№\$\;\^\:\&\*\(\)\_\=\{\}\[\]\|\\\,\<\>\~\`\']).*$#';
        if ( preg_match($pattern, $url) )
            return '//' . $url;

        // иначе это просто строка (возможно REQUEST_URI)
        // возвращаем url как он есть
        return $url;
    }


    /**
     * @param string|null $host
     * @param string|null $scheme
     * 
     * @return string|null
     */
    protected function getHostAsLink(?string $scheme = null, ?string $host = null): ?string
    {
        return !empty($this->host) ? ltrim($this->scheme . '://' . $this->host, ':') : null;
    }

}
