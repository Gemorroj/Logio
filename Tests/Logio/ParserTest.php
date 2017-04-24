<?php
namespace Tests\Logio;

use Logio\Config;
use Logio\Parser;

class ParserTest extends \PHPUnit_Framework_TestCase
{
    private $fixturesDir = __DIR__ . '/../fixtures';
    /**
     * @var Parser
     */
    private $parser;

    protected function setUp()
    {
        $config = Config::createFromYaml($this->fixturesDir . '/config.success.yml');
        $this->parser = new Parser('test', $config->getParameters()['apache']);
    }


    public function testParseLineApache()
    {
        $line = '[Sun Mar 26 03:13:18 2017] [notice] Apache/2.2.15 (Unix) mod_ssl/2.2.15 OpenSSL/1.0.1e-fips mod_perl/2.0.4 Perl/v5.10.1 configured -- resuming normal operations';

        $data = $this->parser->parseLine($line);

        $this->assertEquals([
            'date' => new \DateTime('Sun Mar 26 03:13:18 2017'),
            'type' => 'notice',
            'message' => 'Apache/2.2.15 (Unix) mod_ssl/2.2.15 OpenSSL/1.0.1e-fips mod_perl/2.0.4 Perl/v5.10.1 configured -- resuming normal operations',
        ], $data);
    }
}
