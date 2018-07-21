<?php

namespace Swe\CrudBundle\Controller;

use Symfony\Component\DependencyInjection\Container;
use Swe\CoreBundle\Entity\Manager\Interfaces\GenericManagerInterface;

class Configuration {

    protected $container;
    protected $manager;
    protected $resource;

    public function __construct(Container $container, GenericManagerInterface $manager, $resource) {
        $this->container = $container;
        $this->manager = $manager;
        $this->resource = $resource;
    }

    /**
     * @return object
     * Crée un nouveau objet
     */
    public function createNew() {
      
        return new $this->resource();
    }

}
