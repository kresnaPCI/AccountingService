<?php

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.
// Returns the public 'Enqueue\Client\RouterProcessor' shared service.

include_once $this->targetDirs[3].'/vendor/queue-interop/queue-interop/src/PsrProcessor.php';
include_once $this->targetDirs[3].'/vendor/enqueue/enqueue/Client/RouterProcessor.php';

return $this->services['Enqueue\Client\RouterProcessor'] = new \Enqueue\Client\RouterProcessor(($this->services['enqueue.client.default_null.driver'] ?? $this->load('getEnqueue_Client_DefaultNull_DriverService.php')), array('__router__' => array(0 => array(0 => 'Enqueue\\Client\\RouterProcessor', 1 => 'default'))), array('symfony_events' => 'symfony_events'));
