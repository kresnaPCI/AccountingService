<?php

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.
// Returns the public 'Enqueue\Client\Meta\QueueMetaRegistry' shared service.

include_once $this->targetDirs[3].'/vendor/enqueue/enqueue/Client/Meta/QueueMetaRegistry.php';

return $this->services['Enqueue\Client\Meta\QueueMetaRegistry'] = new \Enqueue\Client\Meta\QueueMetaRegistry(($this->privates['enqueue.client.config'] ?? $this->load('getEnqueue_Client_ConfigService.php')), array('default' => array('processors' => array(0 => 'Enqueue\\Client\\RouterProcessor')), 'symfony_events' => array('processors' => array(0 => 'symfony_events'), 'transportName' => 'symfony_events')));
