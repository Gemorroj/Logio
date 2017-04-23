<?php
namespace Tests\Logio;

use Logio\Config;
use Logio\Logio;
use Logio\Parser;

class LogioTest extends \PHPUnit_Framework_TestCase
{
    private $fixturesDir = __DIR__ . '/../fixtures';
    /**
     * @var Logio
     */
    private $logio;

    protected function setUp()
    {
        $config = Config::createFromYaml($this->fixturesDir . '/config.success.yml');
        $this->logio = new Logio($config);
    }

    public function testConstructor()
    {
        $this->assertNotEmpty($this->logio->getParsers());

        foreach ($this->logio->getParsers() as $parser) {
            $this->assertInstanceOf(Parser::class, $parser);
        }
    }


    public function testParse()
    {
        $this->logio->parse();
    }
}
