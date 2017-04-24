<?php

namespace Logio;


class Logio
{
    /**
     * @var Parser[]
     */
    private $parsers = [];

    public function __construct(Config $config)
    {
        foreach ($config->getParameters() as $name => $parameters) {
            $this->parsers[] = new Parser($name, $parameters);
        }
    }

    /**
     * @return Parser[]
     */
    public function getParsers()
    {
        return $this->parsers;
    }

    /**
     * Iterator objects
     *
     * @return \Generator
     */
    public function run()
    {
        foreach ($this->getParsers() as $parser) {
            yield (new Iterator($parser->getParameters()['path'], $parser))->setName($parser->getName());
        }
    }
}
