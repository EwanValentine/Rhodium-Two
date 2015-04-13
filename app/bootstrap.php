<?php

namespace Rhodium {

    require_once __DIR__.'/Bin/functions.php';

    use Rhodium\Core;

    use Symfony\Component\Config\FileLocator;
    use Symfony\Component\Routing\RouteCollection;
    use Symfony\Component\Routing\Loader\ClosureLoader;

    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;

    class AppKernel
    {
        public function __construct()
        {
            Request::enableHttpMethodParameterOverride();

            $this->core = new Core;

            $this->registerRoutes();
            // $this->registerServices();
            // $this->registerBundles();
            // $this->registerMiddlewares();

            $this->core->go();

            return $this->core;
        }

        public function registerRoutes()
        {
            $this->core->get('/test/{name}', function($name) {
                return new Response($name);
            });

            $this->core->get('/', function() {
                return 'shit';
            });
        }

        public function registerServices()
        {
            $this->core->register( new Rhodium\Services\TwigService(), array(
                'twig.path' => __DIR__ . '/../src/',
                'twig.options' => array (
                    'debug' => true,
                    'cache' => false,
                    'strict_values' => false
                )
            ));

            $this->core->register(new Rhodium\Services\DoctrineService());

            $this->core->register(new Rhodium\Services\ConsoleService(), array (
                'console.name'		=> 'Rhodium',
                'console.version'	=> 1,
                'console.project.directory' => __DIR__.'/'
            ));

            $this->core['doctrine.options'] = array(
                'driver' => 'pdo_mysql',
                'dbname' => 'test',
                'user' => 'root',
                'password' => 'drudkh'
            );
        }

        public function registerBundles()
        {
            //$this->core->bundle(new TestBundle\Config\TestBundle());
            //$this->core->bundle(new NewBundle\Config\NewBundle());
        }

        public function registerMiddlewares()
        {
            //$this->core['middleware']->push( new Rhodium\Middleware\InspectResponseMiddleware() );
        }

        public function registerEventListeners()
        {
            $this->core->eventListener(new TestBundle\Config\TestSubscriber());
        }
    }
}
