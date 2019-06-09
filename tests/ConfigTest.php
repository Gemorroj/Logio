<?php

namespace Logio\Tests;

use Logio\Config;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    private $fixturesDir = __DIR__.'/fixtures';

    public function testConfigSuccess()
    {
        $config = Config::createFromYaml($this->fixturesDir.'/config.success.yml');
        $this->assertEquals('tests/fixtures/apache.log', $config->getParameters()['apache']['path']);
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testConfigError()
    {
        Config::createFromYaml($this->fixturesDir.'/config.error.yml');
    }
}
