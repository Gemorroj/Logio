<?php

namespace Logio\Tests;

use Logio\Config;
use Logio\Iterator;
use Logio\Logio;
use Logio\Parser;
use PHPUnit\Framework\TestCase;

final class LogioTest extends TestCase
{
    private string $fixturesDir = __DIR__.'/fixtures';
    private Config $config;

    protected function setUp(): void
    {
        $this->config = Config::createFromYaml($this->fixturesDir.'/config.success.yml');
    }

    public function testConstructor(): void
    {
        $logio = new Logio($this->config);
        self::assertNotEmpty($logio->getParsers());

        foreach ($logio->getParsers() as $parser) {
            self::assertInstanceOf(Parser::class, $parser);
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
                self::assertIsString($key);
                if (null !== $data) {
                    self::assertIsArray($data);
                    self::assertNotEmpty($data);
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

        self::assertEquals([
            'date' => new \DateTime('18-Jul-2016 15:51:48'),
            'type' => 'WARNING',
            'pool' => 'www',
            'message' => 'seems busy (you may need to increase pm.start_servers, or pm.min/max_spare_servers), spawning 8 children, there are 5 idle, and 14 total children',
            'child' => null,
        ], $data);

        self::assertEquals(7542, $item->getTell());
    }

    public function testRunUnknownParser(): void
    {
        $logio = new Logio($this->config);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Parser for "unknown" not found');

        $logio->run('unknown');
    }

    public function testGetSeekIsNullByDefault(): void
    {
        $logio = new Logio($this->config);

        self::assertNull($logio->run('php_fpm')->getSeek());
    }

    public function testDestructWithoutReadingDoesNotFail(): void
    {
        $logio = new Logio($this->config);

        $item = $logio->run('php_fpm');
        unset($item);

        self::assertTrue(true);
    }

    public function testIteratesForwardAfterSeek(): void
    {
        $logio = new Logio($this->config);
        $item = $logio->run('php_fpm');
        $item->setSeek(7353);

        $positions = [];
        foreach ($item as $line) {
            $positions[] = $item->getTell();
            if (\count($positions) >= 3) {
                break;
            }
        }

        self::assertCount(3, \array_unique($positions));
    }
}
