<?php

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.
// Returns the private 'argument_resolver.service' shared service.

include_once $this->targetDirs[3].'/vendor/symfony/http-kernel/Controller/ArgumentValueResolverInterface.php';
include_once $this->targetDirs[3].'/vendor/symfony/http-kernel/Controller/ArgumentResolver/ServiceValueResolver.php';

return $this->privates['argument_resolver.service'] = new \Symfony\Component\HttpKernel\Controller\ArgumentResolver\ServiceValueResolver(new \Symfony\Component\DependencyInjection\ServiceLocator(array('App\\Controller\\InvoiceController::create' => function () {
    return ($this->privates['.service_locator.BW31L9o'] ?? $this->load('get_ServiceLocator_BW31L9oService.php'));
}, 'App\\Controller\\InvoiceController:create' => function () {
    return ($this->privates['.service_locator.BW31L9o'] ?? $this->load('get_ServiceLocator_BW31L9oService.php'));
})));
