<?php

namespace Logio;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Yaml\Yaml;

class Config
{
    private $parameters;

    private function __construct(array $data)
    {
        $this->parameters = (new Processor())->processConfiguration(
            new Configuration\LogioConfiguration(),
            [$data]
        );
    }

    /**
     * @return static
     */
    public static function createFromYaml(string $configPath): self
    {
        return new static(Yaml::parseFile($configPath));
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }
}
