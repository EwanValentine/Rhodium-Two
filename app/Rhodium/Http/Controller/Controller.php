<?php

namespace Rhodium\Http\Controller;

use Symfony\Component\Routing\Route;

class Controller
{
    private $route;

    private $routeName;

    private $isFrozen = false;

    public $method;

    public $arguments;

    /**
     * Constructor.
     *
     * @param Route $route
     */
    public function __construct(Route $route, $method = "GET", $arguments = null)
    {
        $this->route = $route;

        $this->method = $method;

        $this->arguments = $arguments;
    }

    public function call()
    {
        if (!method_exists($this->route, $this->method)) {
            throw new \BadMethodCallException(sprintf('Method "%s::%s" does not exist.', get_class($this->route), $this->method));
        }

        call_user_func_array(array($this->route, $this->method), $this->arguments);

        return $this;
    }

    /**
     * Gets the controller's route.
     *
     * @return Route
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * Gets the controller's route name.
     *
     * @return string
     */
    public function getRouteName()
    {
        return $this->routeName;
    }

    /**
     * Sets the controller's route.
     *
     * @param string $routeName
     *
     * @return Controller $this The current Controller instance
     */
    public function bind($routeName)
    {
        $this->routeName = $routeName;

        return $this;
    }

    public function generateRouteName($prefix)
    {
        $requirements = $this->route->getRequirements();
        $method = isset($requirements['_method']) ? $requirements['_method'] : '';

        $routeName = $prefix.$method.$this->route->getPath();
        $routeName = str_replace(array('/', ':', '|', '-'), '_', $routeName);
        $routeName = preg_replace('/[^a-z0-9A-Z_.]+/', '', $routeName);

        return $routeName;
    }
}
