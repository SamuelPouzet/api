<?php

namespace SamuelPouzet\Api\Plugin;

use Laminas\Mvc\Controller\Plugin\AbstractPlugin;

class ApiProblemPlugin extends AbstractPlugin
{
    public function __invoke()
    {
        die('api problem test');
        //$this->getController()->redirect()->toRoute();
    }
}