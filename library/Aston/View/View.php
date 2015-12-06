<?php

namespace Aston\View;

abstract class View
{
    private $path;

    public function __construct($path)
    {
        $this->setPath($path);

        if (!is_dir($this->getPath())) {
            throw new \Exception(
                'ErrorPath: "' . $this->getPath() . '" not found'
            );
        }
    }

    public function escape($value)
    {
        return htmlentities($value);
    }

    public function getPath()
    {
        return $this->path;
    }

    public function setPath($path)
    {
        $this->path = (string) $path;
        return $this;
    }

    abstract public function render($view, array $data = []);
}
