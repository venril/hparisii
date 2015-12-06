<?php

namespace Aston\Db;

class Connection
{
    private $driver;
    private $host;
    private $username;
    private $password;
    private $port;
    private $dbname;
    private $options;
    private $pdo = null;

    // new Connection() == new __construct()
    public function __construct(
        $driver, $host, $dbname, $username, $password, $options = [])
    {
        $this->setDriver($driver)
             ->setHost($host)
             ->setDbname($dbname)
             ->setUsername($username)
             ->setPassword($password)
             ->setOptions($options);
    }

    public function connect()
    {
        if ($this->pdo === null) {
            $this->pdo = new \PDO(
                $this->getDsn(),
                $this->getUsername(),
                $this->getPassword(),
                $this->getOptions()
            );
        }
        return $this;
    }

    public function getPdo()
    {
        return $this->pdo;
    }

    public function getDsn()
    {
        switch ($this->getDriver()) {
            case 'mysql':
                $dsn  = 'mysql:host='.$this->getHost().';';
                $dsn .= 'dbname='.$this->getDbname().';';
                $dsn .= 'port='.$this->getPort();
                break;
            default:
               $dsn = '';
        }
        return $dsn;
    }

    public function getDriver()
    {
        return $this->driver;
    }

    public function getHost()
    {
        return $this->host;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getPort()
    {
        return $this->port;
    }

    public function getDbname()
    {
        return $this->dbname;
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function setDriver($driver)
    {
        $this->driver = (string) $driver;
        return $this;
    }

    public function setHost($host)
    {
        $this->host = (string) $host;
        return $this;
    }

    public function setUsername($username)
    {
        $this->username = (string) $username;
        return $this;
    }

    public function setPassword($password)
    {
        $this->password = (string) $password;
        return $this;
    }

    public function setPort($port)
    {
        $this->port = (int) $port;
        return $this;
    }

    public function setDbname($dbname)
    {
        $this->dbname = (string) $dbname;
        return $this;
    }

    public function setOptions(array $options)
    {
        $this->options = $options;
        return $this;
    }
}
