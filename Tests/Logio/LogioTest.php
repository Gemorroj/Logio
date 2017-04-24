<?php
namespace Tests\Logio;

use Logio\Config;
use Logio\Iterator;
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
        /** @var Iterator $item */
        foreach ($this->logio->run() as $item) {
            /**
             * @var string $key
             * @var array|null $data
             */
            foreach ($item as $key => $data) {
                $this->assertInternalType('string', $key);
                if (null !== $data) {
                    $this->assertInternalType('array', $data);
                    $this->assertNotEmpty($data);
                }
                //var_dump($key, $data);
                //echo "\n";
            }
        }
    }
}
