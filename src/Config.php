<?php

namespace Logio;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Yaml\Yaml;

class Config
{
    private $parameters;

    /**
     * @param array $data
     */
    private function __construct(array $data)
    {
        $this->parameters = (new Processor())->processConfiguration(
            new Configuration\LogioConfiguration(),
            [$data]
        );
    }

    /**
     * @param string $configPath
     *
     * @return static
     */
    public static function createFromYaml(string $configPath): self
    {
        return new static(Yaml::parseFile($configPath));
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }
}
