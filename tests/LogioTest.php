<?php

namespace Logio\Tests;

use Logio\Config;
use Logio\Iterator;
use Logio\Logio;
use Logio\Parser;
use PHPUnit\Framework\TestCase;

class LogioTest extends TestCase
{
    private $fixturesDir = __DIR__.'/fixtures';
    /**
     * @var Config
     */
    private $config;

    protected function setUp(): void
    {
        $this->config = Config::createFromYaml($this->fixturesDir.'/config.success.yml');
    }

    public function testConstructor(): void
    {
        $logio = new Logio($this->config);
        $this->assertNotEmpty($logio->getParsers());

        foreach ($logio->getParsers() as $parser) {
            $this->assertInstanceOf(Parser::class, $parser);
        }
    }

    public function testParse(): void
    {
        $logio = new Logio($this->config);

        /** @var Iterator $item */
        foreach ($logio->runAll() as $item) {
            /**
             * @var string
             * @var array|null $data
             */
            foreach ($item as $key => $data) {
                $this->assertIsString($key);
                if (null !== $data) {
                    $this->assertIsArray($data);
                    $this->assertNotEmpty($data);
                }
            }
        }
    }

    public function testSeekParse(): void
    {
        $logio = new Logio($this->config);
        $item = $logio->run('php_fpm');

        $item->setSeek(7353);

        $data = $item->current();

        $this->assertEquals([
            'date' => new \DateTime('18-Jul-2016 15:51:48'),
            'type' => 'WARNING',
            'pool' => 'www',
            'message' => 'seems busy (you may need to increase pm.start_servers, or pm.min/max_spare_servers), spawning 8 children, there are 5 idle, and 14 total children',
            'child' => null,
        ], $data);

        $this->assertEquals(7542, $item->getTell());
    }
}
