<?php

namespace Rhodium\Services;

use Pimple\Container;

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

class DoctrineService implements Pimple\ServiceProviderInterface
{
    public function register(Core $core)
    {
        $core['doctrine.paths'] = array();
        $core['doctrine.options'] = array();
        $core['doctrine.config'] = Setup::createAnnotationMetadataConfiguration( $core['doctrine.paths'], $core['app.mode'] );
        $core['orm.em'] = function ( $c ) {
            return EntityManager::create( $c['doctrine.options'], $c['doctrine.config'] );
        };
    }
}
