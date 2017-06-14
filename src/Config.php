<?php
namespace Logio;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Yaml\Yaml;


class Config
{
    private $parameters = [];

    /**
     * @param array $data
     */
    final private function __construct(array $data)
    {
        $this->parameters = (new Processor())->processConfiguration(
            new Configuration\LogioConfiguration($data),
            [$data]
        );
    }

    /**
     * @param string $configPath
     * @return static
     */
    public static function createFromYaml($configPath)
    {
        return new static(Yaml::parse(\file_get_contents($configPath)));
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }
}
