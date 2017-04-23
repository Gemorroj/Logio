<?php
namespace Logio;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Yaml\Yaml;


class Config
{
    private $config = [];

    /**
     * @param mixed $data
     */
    final private function __construct($data)
    {
        $this->config = (new Processor())->processConfiguration(
            new Configuration\LogioConfiguration(),
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
    public function getConfig()
    {
        return $this->config;
    }
}
