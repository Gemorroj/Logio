<?php

namespace Logio\Exception;

final class ParserException extends \Exception
{
    private ?string $errorLogLine = null;
    private ?string $pattern = null;

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
