<?php

/**
 * File: Router.php.
 * Author: AltoRouter (Danny Vankoteen) http://altorouter.com/
 * Standard: PSR-2. (Use codesniffer or download web code sniffer from www.webcodesniffer.net)
 * Do not change codes without permission.
 * Date: 7/31/2019
 */

namespace System\Core;

use Exception;

class Router
{
    protected $routes = array();

    protected $namedRoutes = array();

    protected $basePath = '';

    protected $matchTypes = array(
        'i' => '[0-9]++',
        'a' => '[0-9A-Za-z]++',
        'h' => '[0-9A-Fa-f]++',
        '*' => '.+?',
        '**' => '.++',
        '' => '[^/\.]++'
    );

    public function __construct($routes = array(), $basePath = '', $matchTypes = array())
    {
        $this->addRoutes($routes);
        $this->setBasePath($basePath);
        $this->addMatchTypes($matchTypes);
    }

    public function addRoutes($routes)
    {
        if (!is_array($routes) && !$routes instanceof Traversable) {
            throw new Exception('Routes should be an array or an instance of Traversable');
        }
        foreach ($routes as $route) {
            call_user_func_array(array($this, 'map'), $route);
        }
    }

    public function setBasePath($basePath)
    {
        $this->basePath = $basePath;
    }

    public function addMatchTypes($matchTypes)
    {
        $this->matchTypes = array_merge($this->matchTypes, $matchTypes);
    }

    public function getRoutes()
    {
        return $this->routes;
    }

    public function map($method, $route, $target, $name = null)
    {
        $this->routes[] = array($method, $route, $target, $name);
        if ($name) {
            if (isset($this->namedRoutes[$name])) {
                throw new Exception("Can not redeclare route '{$name}'");
            } else {
                $this->namedRoutes[$name] = $route;
            }
        }
        return;
    }

    public function generate($routeName, array $params = array())
    {

        if (!isset($this->namedRoutes[$routeName])) {
            throw new Exception("Route '{$routeName}' does not exist.");
        }

        $route = $this->namedRoutes[$routeName];


        $url = $this->basePath . $route;
        if (preg_match_all('`(/|\.|)\[([^:\]]*+)(?::([^:\]]*+))?\](\?|)`', $route, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $index => $match) {
                list($block, $pre, $type, $param, $optional) = $match;
                if ($pre) {
                    $block = substr($block, 1);
                }
                if (isset($params[$param])) {
                    $url = str_replace($block, $params[$param], $url);
                } elseif ($optional && $index !== 0) {
                    $url = str_replace($pre . $block, '', $url);
                } else {
                    $url = str_replace($block, '', $url);
                }
            }
        }
        return $url;
    }

    public function match($requestUrl = null, $requestMethod = null)
    {
        $params = array();
        $match = false;

        if ($requestUrl === null) {
            $requestUrl = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
        }

        $requestUrl = substr($requestUrl, strlen($this->basePath));

        if (($strpos = strpos($requestUrl, '?')) !== false) {
            $requestUrl = substr($requestUrl, 0, $strpos);
        }

        if ($requestMethod === null) {
            $requestMethod = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET';
        }
        foreach ($this->routes as $handler) {
            list($methods, $route, $target, $name) = $handler;
            $method_match = (stripos($methods, $requestMethod) !== false);

            if (!$method_match) continue;
            if ($route === '*') {
                $match = true;
            } elseif (isset($route[0]) && $route[0] === '@') {
                $pattern = '`' . substr($route, 1) . '`u';
                $match = preg_match($pattern, $requestUrl, $params) === 1;
            } elseif (($position = strpos($route, '[')) === false) {
                $match = strcmp($requestUrl, $route) === 0;
            } else {
                if (strncmp($requestUrl, $route, $position) !== 0) {
                    continue;
                }
                $regex = $this->compileRoute($route);
                $match = preg_match($regex, $requestUrl, $params) === 1;
            }
            if ($match) {
                if ($params) {
                    foreach ($params as $key => $value) {
                        if (is_numeric($key)) unset($params[$key]);
                    }
                }
                return array(
                    'target' => $target,
                    'params' => $params,
                    'name' => $name
                );
            }
        }
        return false;
    }

    protected function compileRoute($route)
    {
        if (preg_match_all('`(/|\.|)\[([^:\]]*+)(?::([^:\]]*+))?\](\?|)`', $route, $matches, PREG_SET_ORDER)) {
            $matchTypes = $this->matchTypes;
            foreach ($matches as $match) {
                list($block, $pre, $type, $param, $optional) = $match;
                if (isset($matchTypes[$type])) {
                    $type = $matchTypes[$type];
                }
                if ($pre === '.') {
                    $pre = '\.';
                }
                $optional = $optional !== '' ? '?' : null;

                //Older versions of PCRE require the 'P' in (?P<named>)
                $pattern = '(?:'
                    . ($pre !== '' ? $pre : null)
                    . '('
                    . ($param !== '' ? "?P<$param>" : null)
                    . $type
                    . ')'
                    . $optional
                    . ')'
                    . $optional;
                $route = str_replace($block, $pattern, $route);
            }
        }
        return "`^$route$`u";
    }
}
