<?php

namespace Logio;

use MVar\LogParser\LineParserInterface;

class Parser implements LineParserInterface
{
    protected $name;
    protected $parameters = [];

    /**
     * @param string $name
     * @param array $parameters
     */
    public function __construct($name, array $parameters)
    {
        $this->name = $name;
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
     * @return string
     */
    public function getName()
    {
        return $this->name;
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
     * @param string $className
     * @param string $value
     * @return object
     */
    protected function castData($className, $value)
    {
        if (!\class_exists($className)) {
            throw new \InvalidArgumentException(sprintf('Class "%s" not found', $className));
        }
        return new $className($value);
    }
}
