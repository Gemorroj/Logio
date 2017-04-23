<?php
namespace Tests\Logio;

use Logio\Config;

class ConfigTest extends \PHPUnit_Framework_TestCase
{
    private $fixturesDir = __DIR__ . '/../fixtures';

    public function testConfigSuccess()
    {
        $config = Config::createFromYaml($this->fixturesDir . '/config.success.yml');
        $this->assertEquals('Tests/fixtures/apache.log', $config->getParameters()['apache']['path']);
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testConfigError()
    {
        Config::createFromYaml($this->fixturesDir . '/config.error.yml');
    }
}
