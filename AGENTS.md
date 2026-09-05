# AGENTS.md

## Project

Logio — PHP library for parsing log files (apache, nginx, php, php-fpm, mysql).
New parsers are added via regex-based YAML config, no code changes needed.

PHP requirement: >= 8.4 (see `composer.json`).

## Structure

- `src/` — library code (`Logio\` namespace, PSR-4):
  - `Logio.php` — entry point, `run()` returns parser iterator
  - `Config.php` + `Configuration/` — YAML config loading and validation
  - `Parser.php`, `Iterator.php` — parsing logic
  - `Exception/` — custom exceptions
- `tests/` — PHPUnit tests (`Logio\Tests\` namespace)
- `config.yml.dist` — example parser config
- `composer.json`, `phpunit.xml.dist`, `.php-cs-fixer.dist.php` — tooling config

## Commands

```bash
composer install
vendor/bin/phpunit
PHP_CS_FIXER_IGNORE_ENV=1 vendor/bin/php-cs-fixer fix --dry-run --diff
PHP_CS_FIXER_IGNORE_ENV=1 vendor/bin/php-cs-fixer fix
```

## Conventions

- PSR-4 autoloading, strict types where already used.
- Follow existing code style; run php-cs-fixer before committing.
- Config example:

```php
$config = Logio\Config::createFromYaml('/path/to/config.yml');
$logio = new Logio\Logio($config);
foreach ($logio->run('php') as $data) {
    print_r($data);
}
```

- Add/extend tests in `tests/` for parser or config changes.
