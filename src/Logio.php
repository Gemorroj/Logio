<?php

namespace Logio;

final class Logio
{
    /**
     * @var Parser[]
     */
    private array $parsers = [];

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
     * @throws \InvalidArgumentException
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
     */
    public function runAll(): \Generator
    {
        foreach ($this->getParsers() as $parser) {
            yield (new Iterator($parser->getParameters()['path'], $parser))
                ->setName($parser->getName());
        }
    }
}
