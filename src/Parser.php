<?php

namespace Logio;

use Logio\Exception\ParserException;
use MVar\LogParser\LineParserInterface;

class Parser implements LineParserInterface
{
    protected $name;
    protected $parameters;

    /**
     * @param string $name
     * @param array  $parameters
     */
    public function __construct(string $name, array $parameters)
    {
        $this->name = $name;
        $this->parameters = $parameters;
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $line
     *
     * @return array|null
     */
    public function parseLine($line): ?array
    {
        $data = [];

        foreach ($this->getParameters()['format'] as $key => $pattern) {
            $result = @\preg_match($pattern.'S', $line, $matches);

            if (false === $result) {
                $this->makeParserException($line, $pattern);
            }
            if (1 !== $result) {
                $data[$key] = null;
                continue;
            }

            if (isset($this->getParameters()['cast'][$key])) {
                $data[$key] = $this->castData(
                    $this->getParameters()['cast'][$key],
                    $matches[1]
                );
            } else {
                $data[$key] = $matches[1];
            }
        }

        return $data ?: null;
    }

    /**
     * @param string $line
     * @param string $pattern
     *
     * @throws ParserException
     */
    protected function makeParserException(string $line, string $pattern): void
    {
        $pregErrorCode = \preg_last_error();
        if (PREG_NO_ERROR !== $pregErrorCode) {
            $exception = new ParserException(\array_flip(\get_defined_constants(true)['pcre'])[$pregErrorCode]);
            $exception->setErrorLogLine($line);
            $exception->setPattern($pattern);
            throw $exception;
        }
        $error = \error_get_last();
        if ($error) {
            $exception = new ParserException($error['message']);
            $exception->setErrorLogLine($line);
            $exception->setPattern($pattern);
            throw $exception;
        }

        $exception = new ParserException('Unknown PCRE error');
        $exception->setErrorLogLine($line);
        $exception->setPattern($pattern);
        throw $exception;
    }

    /**
     * @param string $className
     * @param string $value
     *
     * @return object
     */
    protected function castData(string $className, string $value): object
    {
        if (!\class_exists($className)) {
            throw new \InvalidArgumentException(\sprintf('Class "%s" not found', $className));
        }

        return new $className($value);
    }
}
