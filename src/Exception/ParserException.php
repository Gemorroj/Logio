<?php

namespace Logio\Exception;

class ParserException extends \Exception
{
    private $errorLogLine;
    private $pattern;

    /**
     * @return string|null
     */
    public function getErrorLogLine(): ?string
    {
        return $this->errorLogLine;
    }

    /**
     * @param string $errorLogLine
     *
     * @return $this
     */
    public function setErrorLogLine(string $errorLogLine): self
    {
        $this->errorLogLine = $errorLogLine;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPattern(): ?string
    {
        return $this->pattern;
    }

    /**
     * @param string $pattern
     *
     * @return $this
     */
    public function setPattern(string $pattern): self
    {
        $this->pattern = $pattern;

        return $this;
    }
}
