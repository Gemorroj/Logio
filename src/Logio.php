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
    public function getParsers(): array
    {
        return $this->parsers;
    }

    /**
     * Iterator object.
     *
     * @param string $name
     *
     * @throws \InvalidArgumentException
     *
     * @return Iterator
     */
    public function run(string $name): Iterator
    {
        foreach ($this->getParsers() as $parser) {
            if ($parser->getName() === $name) {
                return (new Iterator($parser->getParameters()['path'], $parser))
                    ->setName($parser->getName());
            }
        }

        throw new \InvalidArgumentException(\sprintf('Parser for "%s" nof found', $name));
    }

    /**
     * Iterator objects.
     *
     * @return \Generator
     */
    public function runAll(): \Generator
    {
        foreach ($this->getParsers() as $parser) {
            yield (new Iterator($parser->getParameters()['path'], $parser))
                ->setName($parser->getName());
        }
    }
}
