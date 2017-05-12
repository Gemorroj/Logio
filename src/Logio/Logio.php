<?php

namespace Logio;


class Logio
{
    /**
     * @var Parser[]
     */
    private $parsers = [];

    /**
     * @param Config $config
     */
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
     * Iterator object
     *
     * @param string $name
     * @return Iterator
     * @throws \InvalidArgumentException
     */
    public function run($name)
    {
        foreach ($this->getParsers() as $parser) {
            if ($parser->getName() === $name) {
                return (new Iterator($parser->getParameters()['path'], $parser))
                    ->setName($parser->getName());
            }
        }

        throw new \InvalidArgumentException(\sprintf('Parser for "%s" nof found.', $name));
    }

    /**
     * Iterator objects
     *
     * @return \Generator
     */
    public function runAll()
    {
        foreach ($this->getParsers() as $parser) {
            yield (new Iterator($parser->getParameters()['path'], $parser))
                ->setName($parser->getName());
        }
    }
}
