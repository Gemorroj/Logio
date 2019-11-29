<?php

namespace Logio\Exception;

class ParserException extends \Exception
{
    private $errorLogLine;
    private $pattern;

    public function getErrorLogLine(): ?string
    {
        return $this->errorLogLine;
    }

    /**
     * @return $this
     */
    public function setErrorLogLine(string $errorLogLine): self
    {
        $this->errorLogLine = $errorLogLine;

        return $this;
    }

    public function getPattern(): ?string
    {
        return $this->pattern;
    }

    /**
     * @return $this
     */
    public function setPattern(string $pattern): self
    {
        $this->pattern = $pattern;

        return $this;
    }
}
