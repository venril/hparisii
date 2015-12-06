<?php
namespace Aston\Logger;

use Exception;

class Manager
{
    private $loggers = [];

    public function addLogger(Logger $logger)
    {
        $this->loggers[] = $logger;
    }

    public function getLoggers()
    {
        return $this->loggers;
    }

    public function log(Exception $e, $level)
    {
        foreach ($this->getLoggers() as $logger) {
            $logger->log($e, $level);
        }
    }
}
