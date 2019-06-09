<?php

namespace Logio\Tests;

use Logio\Config;
use Logio\Parser;
use PHPUnit\Framework\TestCase;

class ParserTest extends TestCase
{
    private $fixturesDir = __DIR__.'/fixtures';
    /**
     * @var Config
     */
    private $config;

    protected function setUp()
    {
        $this->config = Config::createFromYaml($this->fixturesDir.'/config.success.yml');
    }

    public function testParseLineApache()
    {
        $parser = new Parser('test', $this->config->getParameters()['apache']);

        $data = $parser->parseLine('[Sun Mar 26 03:13:18 2017] [notice] Apache/2.2.15 (Unix) mod_ssl/2.2.15 OpenSSL/1.0.1e-fips mod_perl/2.0.4 Perl/v5.10.1 configured -- resuming normal operations');
        $this->assertEquals([
            'date' => new \DateTime('Sun Mar 26 03:13:18 2017'),
            'type' => 'notice',
            'message' => 'Apache/2.2.15 (Unix) mod_ssl/2.2.15 OpenSSL/1.0.1e-fips mod_perl/2.0.4 Perl/v5.10.1 configured -- resuming normal operations',
            'client' => null,
        ], $data);
    }

    public function testParseLineNginx()
    {
        $parser = new Parser('test', $this->config->getParameters()['nginx']);

        $data = $parser->parseLine('2017/04/24 01:08:32 [warn] 21184#21184: *299432 a client request body is buffered to a temporary file /var/cache/nginx/client_temp/0000002912, client: 127.0.0.1, server: localhost, request: "POST /TopbyEDSservices/services/EDSService HTTP/1.1", host: "localhost:8080"');
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
        $parser = new Parser('test', $this->config->getParameters()['php_fpm']);

        $data = $parser->parseLine('[17-Jul-2016 15:24:26] WARNING: [pool www] seems busy (you may need to increase pm.start_servers, or pm.min/max_spare_servers), spawning 8 children, there are 4 idle, and 21 total children');
        $this->assertEquals([
            'date' => new \DateTime('17-Jul-2016 15:24:26'),
            'type' => 'WARNING',
            'pool' => 'www',
            'message' => 'seems busy (you may need to increase pm.start_servers, or pm.min/max_spare_servers), spawning 8 children, there are 4 idle, and 21 total children',
            'child' => null,
        ], $data);
    }

    public function testParseLinePhp()
    {
        $parser = new Parser('test', $this->config->getParameters()['php']);

        $data = $parser->parseLine('[21-Feb-2017 21:20:36 Europe/Moscow] PHP Notice:  Undefined index: captcha_keystring in /var/www/forum/register.php on line 70');
        $this->assertEquals([
            'date' => new \DateTime('21-Feb-2017 21:20:36 Europe/Moscow'),
            'type' => 'PHP Notice',
            'message' => 'Undefined index: captcha_keystring',
            'file' => '/var/www/forum/register.php',
            'line' => '70',
        ], $data);

        $data = $parser->parseLine('[21-Feb-2017 15:54:52 Europe/Moscow] PHP Fatal error:  Uncaught InvalidArgumentException: The directory "/var/www/wapinet/var/cache/prod/vich_uploader" is not writable. in /var/www/wapinet/vendor/jms/metadata/src/Metadata/Cache/FileCache.php:17');
        $this->assertEquals([
            'date' => new \DateTime('21-Feb-2017 15:54:52 Europe/Moscow'),
            'type' => 'PHP Fatal error',
            'message' => 'Uncaught InvalidArgumentException: The directory "/var/www/wapinet/var/cache/prod/vich_uploader" is not writable.',
            'file' => '/var/www/wapinet/vendor/jms/metadata/src/Metadata/Cache/FileCache.php',
            'line' => '17',
        ], $data);
    }

    public function testParseLineMysqlFpm()
    {
        $parser = new Parser('test', $this->config->getParameters()['mysql']);

        $data = $parser->parseLine('2016-03-01T22:22:39.957490Z 10 [ERROR] Column count of performance_schema.events_stages_history_long is wrong. Expected 12, found 10. Created with MySQL 50629, now running 50711. Please use mysql_upgrade to fix this error.');
        $this->assertEquals([
            'date' => new \DateTime('2016-03-01T22:22:39.957490Z'),
            'thread' => '10',
            'type' => 'ERROR',
            'message' => 'Column count of performance_schema.events_stages_history_long is wrong. Expected 12, found 10. Created with MySQL 50629, now running 50711. Please use mysql_upgrade to fix this error.',
        ], $data);

        $data = $parser->parseLine('2016-03-01T22:22:36.332172Z mysqld_safe Starting mysqld daemon with databases from /var/lib/mysql');
        $this->assertEquals([
            'date' => new \DateTime('2016-03-01T22:22:36.332172Z'),
            'thread' => null,
            'type' => null,
            'message' => 'mysqld_safe Starting mysqld daemon with databases from /var/lib/mysql',
        ], $data);
    }
}
