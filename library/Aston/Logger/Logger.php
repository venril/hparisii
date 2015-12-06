<?php

namespace Aston\Logger;

use Aston\Logger\Store\Handler as StoreHandler;
use Exception;

class Logger
{
    const LEVEL_NOTICE  = 1;
    const LEVEL_WARNING = 2;
    const LEVEL_FATAL   = 3;

    private $levelTypes = array(
        Logger::LEVEL_NOTICE  => 'notice',
        Logger::LEVEL_WARNING => 'warning',
        Logger::LEVEL_FATAL   => 'fatal',
    );

    private $storeHandler;
    private $exception;

    public function __construct(StoreHandler $storeHandler)
    {
        $this->setStoreHandler($storeHandler);
    }

    public function log(Exception $e, $level)
    {
        if (!array_key_exists($level, $this->levelTypes)) {
            throw new \InvalidArgumentException('Invalid level');
        }

        $this->setException($e);
        $datetime = new \DateTime();

        // [warning] ; 2015-11-30 ; 00:00:00 ; 500 ; Argument non valide ; test.php 56
        $msg = sprintf(
            "[%s];%s;%s;%d;%s;%s;%d\n",
            $this->levelTypes[$level],
            $datetime->format('Y-m-d'),
            $datetime->format('H:i:s'),
            $e->getCode(),
            $e->getMessage(),
            $e->getFile(),
            $e->getLine()
        );

        $this->getStoreHandler()->write($msg);
    }

    public function getStoreHandler()
    {
        return $this->storeHandler;
    }

    public function setStoreHandler(StoreHandler $storeHandler)
    {
        $this->storeHandler = $storeHandler;
        return $this;
    }
    
    public function getException()
    {
        return $this->exception;
    }

    public function setException(Exception $exception)
    {
        $this->exception = $exception;
        return $this;
    }
}
