<?php

namespace Aston\Logger\Store;

interface Handler
{
    public function write($data);
}
