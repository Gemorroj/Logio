<?php

namespace Logio\Tests;

use Logio\Config;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

class ConfigTest extends TestCase
{
    private string $fixturesDir = __DIR__.'/fixtures';

    public function testConfigSuccess(): void
    {
        $config = Config::createFromYaml($this->fixturesDir.'/config.success.yml');
        self::assertEquals('tests/fixtures/apache.log', $config->getParameters()['apache']['path']);
    }

    public function testConfigError(): void
    {
        $this->expectException(InvalidConfigurationException::class);
        Config::createFromYaml($this->fixturesDir.'/config.error.yml');
    }
}
