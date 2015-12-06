<?php

namespace Aston\Logger\Store\Handler;

use Aston\Logger\Store\Handler;

class File implements Handler
{
    private $filename;

    public function __construct($filename)
    {
        $this->filename = (string) $filename;
    }

    public function write($data)
    {
        file_put_contents($this->filename, $data, FILE_APPEND);
    }
}
