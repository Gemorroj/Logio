<?php
namespace Tests\Logio;

use Logio\Config;
use Logio\Parser;

class ParserTest extends \PHPUnit_Framework_TestCase
{
    private $fixturesDir = __DIR__ . '/../fixtures';
    /**
     * @var Config
     */
    private $config;

    protected function setUp()
    {
        $this->config = Config::createFromYaml($this->fixturesDir . '/config.success.yml');
    }


    public function testParseLineApache()
    {
        $line = '[Sun Mar 26 03:13:18 2017] [notice] Apache/2.2.15 (Unix) mod_ssl/2.2.15 OpenSSL/1.0.1e-fips mod_perl/2.0.4 Perl/v5.10.1 configured -- resuming normal operations';

        $parser = new Parser('test', $this->config->getParameters()['apache']);
        $data = $parser->parseLine($line);

        $this->assertEquals([
            'date' => new \DateTime('Sun Mar 26 03:13:18 2017'),
            'type' => 'notice',
            'message' => 'Apache/2.2.15 (Unix) mod_ssl/2.2.15 OpenSSL/1.0.1e-fips mod_perl/2.0.4 Perl/v5.10.1 configured -- resuming normal operations',
        ], $data);
    }

    public function testParseLineNginx()
    {
        $line = '2017/04/24 01:08:32 [warn] 21184#21184: *299432 a client request body is buffered to a temporary file /var/cache/nginx/client_temp/0000002912, client: 127.0.0.1, server: localhost, request: "POST /TopbyEDSservices/services/EDSService HTTP/1.1", host: "localhost:8080"';

        $parser = new Parser('test', $this->config->getParameters()['nginx']);
        $data = $parser->parseLine($line);

        $this->assertEquals([
            'date' => new \DateTime('2017/04/24 01:08:32'),
            'type' => 'warn',
            'message' => '*299432 a client request body is buffered to a temporary file /var/cache/nginx/client_temp/0000002912',
            'client' => '127.0.0.1',
            'server' => 'localhost',
            'request' => 'POST /TopbyEDSservices/services/EDSService HTTP/1.1',
            'host' => 'localhost:8080',
        ], $data);
    }

    public function testParseLinePhpFpm()
    {
        $line = '[17-Jul-2016 15:24:26] WARNING: [pool www] seems busy (you may need to increase pm.start_servers, or pm.min/max_spare_servers), spawning 8 children, there are 4 idle, and 21 total children';

        $parser = new Parser('test', $this->config->getParameters()['php_fpm']);
        $data = $parser->parseLine($line);

        $this->assertEquals([
            'date' => new \DateTime('17-Jul-2016 15:24:26'),
            'type' => 'WARNING',
            'pool' => 'www',
            'message' => 'seems busy (you may need to increase pm.start_servers, or pm.min/max_spare_servers), spawning 8 children, there are 4 idle, and 21 total children',
        ], $data);
    }
}
