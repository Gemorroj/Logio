<?php
namespace Logio\Exception;


class ParserException extends \Exception
{
    private $errorLogLine;
    private $pattern;

    /**
     * @return string|null
     */
    public function getErrorLogLine()
    {
        return $this->errorLogLine;
    }

    /**
     * @param string $errorLogLine
     * @return $this
     */
    public function setErrorLogLine($errorLogLine)
    {
        $this->errorLogLine = $errorLogLine;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPattern()
    {
        return $this->pattern;
    }

    /**
     * @param string $pattern
     * @return $this
     */
    public function setPattern($pattern)
    {
        $this->pattern = $pattern;
        return $this;
    }
}
