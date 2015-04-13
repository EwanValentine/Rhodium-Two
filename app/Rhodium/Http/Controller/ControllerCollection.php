<?php

namespace Rhodium\Http\Controller;

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Route;

class ControllerCollection
{
    protected $controllers = array();

    protected $defaultRoute;

    protected $defaultController;

    protected $prefix;


    /**
     * Constructor.
     */
    public function __construct(Route $defaultRoute)
    {
        $this->defaultRoute = $defaultRoute;
        $this->defaultController = function (Request $request) {
            throw new \LogicException(sprintf('The "%s" route must have code to run when it matches.', $request->attributes->get('_route')));
        };
    }

    /**
     * Mounts controllers under the given route prefix.
     *
     * @param string               $prefix      The route prefix
     * @param ControllerCollection $controllers A ControllerCollection instance
     */
    public function mount($prefix, ControllerCollection $controllers)
    {
        $controllers->prefix = $prefix;

        $this->controllers[] = $controllers;
    }

    /**
     * Maps a pattern to a callable.
     *
     * You can optionally specify HTTP methods that should be matched.
     *
     * @param string $pattern Matched route pattern
     * @param mixed  $to      Callback that returns the response when matched
     *
     * @return Controller
     */
    public function match($pattern, $method, $to = null)
    {
        $route = clone $this->defaultRoute;

        $route->setPath($pattern);
        $this->controllers[] = $controller = new Controller($route, $method, $to);

        $route->setDefault('_controller', null === $to ? $this->defaultController : $to);

        return $controller;
    }

    /**
     * Maps a GET request to a callable.
     *
     * @param string $pattern Matched route pattern
     * @param mixed  $to      Callback that returns the response when matched
     *
     * @return Controller
     */
    public function get($pattern, $to = null)
    {
        return $this->match($pattern, "get", $to)->call();
    }

    /**
     * Maps a POST request to a callable.
     *
     * @param string $pattern Matched route pattern
     * @param mixed  $to      Callback that returns the response when matched
     *
     * @return Controller
     */
    public function post($pattern, $to = null)
    {
        return $this->match($pattern, $to)->method('POST');
    }

    /**
     * Maps a PUT request to a callable.
     *
     * @param string $pattern Matched route pattern
     * @param mixed  $to      Callback that returns the response when matched
     *
     * @return Controller
     */
    public function put($pattern, $to = null)
    {
        return $this->match($pattern, $to)->method('PUT');
    }

    /**
     * Maps a DELETE request to a callable.
     *
     * @param string $pattern Matched route pattern
     * @param mixed  $to      Callback that returns the response when matched
     *
     * @return Controller
     */
    public function delete($pattern, $to = null)
    {
        return $this->match($pattern, $to)->method('DELETE');
    }

    /**
     * Maps a PATCH request to a callable.
     *
     * @param string $pattern Matched route pattern
     * @param mixed  $to      Callback that returns the response when matched
     *
     * @return Controller
     */
    public function patch($pattern, $to = null)
    {
        return $this->match($pattern, $to)->method('PATCH');
    }

    public function __call($method, $arguments)
    {
        if (!method_exists($this->defaultRoute, $method)) {
            throw new \BadMethodCallException(sprintf('Method "%s::%s" does not exist.', get_class($this->defaultRoute), $method));
        }

        call_user_func_array(array($this->defaultRoute, $method), $arguments);

        foreach ($this->controllers as $controller) {
            if ($controller instanceof Controller) {
                call_user_func_array(array($controller, $method), $arguments);
            }
        }

        return $this;
    }

    /**
     * Persists and freezes staged controllers.
     *
     * @param string $prefix
     *
     * @return RouteCollection A RouteCollection instance
     */
    public function flush($prefix = '')
    {
        $routes = new RouteCollection();

        foreach ($this->controllers as $controller) {
            if ($controller instanceof Controller) {
                if (!$name = $controller->getRouteName()) {
                    $name = $controller->generateRouteName($prefix);
                    while ($routes->get($name)) {
                        $name .= '_';
                    }
                    $controller->bind($name);
                }
                $routes->add($name, $controller->getRoute());
                $controller->freeze();
            } else {
                $routes->addCollection($controller->flush($controller->prefix));
            }
        }

        $routes->addPrefix($prefix);

        $this->controllers = array();

        return $routes;
    }
}