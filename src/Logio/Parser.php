<?php

namespace Logio;

use MVar\LogParser\LineParserInterface;

class Parser implements LineParserInterface
{
    protected $parameters = [];

    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param string $line
     *
     * @return array
     */
    public function parseLine($line)
    {
        $data = [];

        foreach ($this->getParameters()['format'] as $key => $pattern) {
            $result = \preg_match($pattern, $line, $matches);

            if (1 !== $result) {
                continue;
            }

            //print_r($matches);
            $data[$key] = $matches[1];
        }

        return $data ?: null;
    }
}
