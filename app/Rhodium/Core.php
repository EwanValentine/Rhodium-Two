<?php

namespace Rhodium;

use Rhodium\Container\Container;
use Rhodium\StandardLibrary\EventDispatcher\EventManager;

use Pimple\ServiceProviderInterface;

use Composer\Autoload\ClassLoader;

use Symfony\Component\Routing\Route;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\TerminableInterface;

class Core extends Container implements HttpKernelInterface, TerminableInterface
{
    public function __construct()
    {
        parent::__construct();

        $core = $this;

        $core['app.mode'] = 'dev';
        $core['charset'] = 'utf-8';

        $core['context'] = function() {
            return new \Symfony\Component\Routing\RequestContext;
        };

        $core['routes'] = function () {
            return new \Symfony\Component\Routing\RouteCollection();
        };

        $core['route.class'] = 'Rhodium\\Routing\\Route';
        $core['route.factory'] = $core->factory(function() use ($core) {
            // return new $core['route.class']();
            return new \Symfony\Component\Routing\Route($core['route.class']);
        });

        $core['controllers'] = function () use ($core) {
            return $core['controller.factory'];
        };

        $core['class.loader'] = $core->factory(function() use ($core) {
            return new Autoload;
        });

        $core['controller.factory'] = $core->factory(function() use ($core) {
            return new \Rhodium\Http\Controller\ControllerCollection($core['route.factory']);
        });

        $core['matcher'] = function() use ($core) {
            return new \Symfony\Component\Routing\Matcher\UrlMatcher($core['routes'], $core['context']);
        };

        $core['resolver'] = function ($c) {
            return new \Symfony\Component\HttpKernel\Controller\ControllerResolver;
        };

        $core['listener.router'] = function ($c) {
            return new \Symfony\Component\HttpKernel\EventListener\RouterListener($c['matcher']);
        };

        $core['listener.response'] = function ($c) {
            return new \Symfony\Component\HttpKernel\EventListener\ResponseListener('utf-8');
        };

        $core['listener.exception'] = function ($c) {
            return new \Symfony\Component\HttpKernel\EventListener\ExceptionListener('Rhodium\\EventListeners\\ExceptionListener::exception');
        };

        $core['dispatcher'] = function ($c) {
            $dispatcher = new \Symfony\Component\EventDispatcher\EventDispatcher;

            $dispatcher->addSubscriber($c['listener.router']);
            $dispatcher->addSubscriber($c['listener.response']);
            $dispatcher->addSubscriber($c['listener.exception']);

            return $dispatcher;
        };

        $core['kernel'] = function ($c) {
            return new HttpKernel( $c['dispatcher'], $c['resolver'], $c['request.stack'] );
        };

        $core['request.stack'] = function ($c) {
            if (class_exists('Symfony\Component\HttpFoundation\RequestStack')) {
                return new RequestStack();
            }
        };

        $core['request.error'] = $core->protect(function () {
            throw new \RuntimeException('Accessed request service outside of request scope. Try moving that call to a before handler or controller.');
        });

        $core['middleware'] = function ( $c ) {
            return new \Rhodium\Http\MiddlewareKernel;
        };

        $core['event.manager'] = function($c) {
            return new EventManager();
        };
    }

    public function handle(Request $request, $type = HttpKernelInterface::MASTER_REQUEST, $catch = true)
    {
        // create a context using the current request
        $context = $this['context'];
        $context->fromRequest($request);

        $matcher = new \Symfony\Component\Routing\Matcher\UrlMatcher($this['routes'], $this['context']);

        try {
            $attributes = $matcher->match($request->getPathInfo());
            $controller = $attributes['controller'];

            unset($attributes['controller']);
            $response = call_user_func_array($controller, $attributes);
        } catch (ResourceNotFoundException $e) {
            $response = new Response('Not found!', Response::HTTP_NOT_FOUND);
        }

        return $response->send();
    }

    public function go(Request $request = null)
    {
        if (null === $request) {
            $request = Request::createFromGlobals();
        }

        $response = $this->handle( $request );
    }

    public function registerContainer($name, $container)
    {
        // @todo - this should be done with namespaces not filepaths
        $class = '\\' . $container;

        $this[$name] = new $class($this);

        return $this[$name]->getServices();
    }

    public function registerEntities( $src )
    {
        // @todo - this should be done with namespaces not filepaths
        return $this['doctrine.paths'] = array (
            '../src/' . $src . '/Entities/'
        );
    }

    public function bundle(BundleInterface $bundle)
    {
        $this->bundles[] = $bundle;

        $this->registerContainer( $bundle->getName(), $bundle->register( $this ) );
        $this->registerEntities( $bundle->getName() );

        return $this;
    }

    public function get($path, $controller)
    {
        $this->addRoute($path, $controller, "GET");
    }

    public function post($path, $controller)
    {
        $this->addRoute($path, $controller, "POST");
    }

    public function put($path, $controller)
    {
        $this->addRoute($path, $controller, "PUT");
    }

    public function patch($path, $controller)
    {
        $this->addRoute($path, $controller, "PATCH");
    }

    public function delete($path, $controller)
    {
        $this->addRoute($path, $controller, "DELETE");
    }

    public function addRoute($path, $controller, $method)
    {
        $this['routes']->add($path, new Route(
            $path,
            ['controller' => $controller],
            [],
            [],
            '',
            [],
            $method
        ));
    }

    public function terminate(Request $request, Response $response)
    {
        $this['kernel']->terminate( $request, $response );
    }

    public function eventListener(SubscriberInterface $subscriber)
    {
        $this['event.manager']->addSubscriber($subscriber);
    }
}
