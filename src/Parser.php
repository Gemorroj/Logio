<?php

namespace Logio;

use Logio\Exception\ParserException;

class Parser
{
    protected $name;
    protected $parameters;

    public function __construct(string $name, array $parameters)
    {
        $this->name = $name;
        $this->parameters = $parameters;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Parses single log line.
     */
    public function parseLine(string $line): ?array
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
     * @throws ParserException
     */
    protected function makeParserException(string $line, string $pattern): void
    {
        $pregErrorCode = \preg_last_error();
        if (\PREG_NO_ERROR !== $pregErrorCode) {
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

    protected function castData(string $className, string $value): object
    {
        if (!\class_exists($className)) {
            throw new \InvalidArgumentException(\sprintf('Class "%s" not found', $className));
        }

        return new $className($value);
    }
}
