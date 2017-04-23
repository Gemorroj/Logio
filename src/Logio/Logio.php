<?php

namespace Logio;

use MVar\LogParser\LogIterator;

class Logio
{
    /**
     * @var Parser[]
     */
    private $parsers = [];

    public function __construct(Config $config)
    {
        foreach ($config->getParameters() as $key => $parameters) {
            $this->parsers[$key] = new Parser($parameters);
        }
    }

    /**
     * @return Parser[]
     */
    public function getParsers()
    {
        return $this->parsers;
    }


    public function parse()
    {
        foreach ($this->getParsers() as $key => $parser) {
            echo $key . "\n";
            foreach (new LogIterator($parser->getParameters()['path'], $parser) as $data) {
                \print_r($data);
            }
        }
    }
}
