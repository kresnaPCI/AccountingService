<?php

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.
// Returns the public 'enqueue.transport.default.connection_factory' shared service.

include_once $this->targetDirs[3].'/vendor/queue-interop/queue-interop/src/PsrConnectionFactory.php';
include_once $this->targetDirs[3].'/vendor/enqueue/null/NullConnectionFactory.php';

return $this->services['enqueue.transport.default.connection_factory'] = new \Enqueue\Null\NullConnectionFactory();
