<?php

namespace Logio;

class Logio
{
    private $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }
}
