<?php

namespace Logio;

use Logio\Exception\ParserException;

final class Iterator implements \Iterator
{
    /**
     * @var resource
     */
    private $fileHandler;
    private ?string $currentLine = null;
    private ?int $seek = null;
    private string $name = '';

    public function __construct(
        private readonly string $logFile,
        private readonly Parser $parser,
        private readonly bool $skipEmptyLines = true,
    ) {
    }

    public function __destruct()
    {
        @\fclose($this->fileHandler);
    }

    /**
     * Returns file handler.
     *
     * @throws ParserException
     *
     * @return resource
     */
    protected function getFileHandler(bool $seek = true)
    {
        if (null === $this->fileHandler) {
            $fileHandler = @\fopen($this->logFile, 'r');

            if (false === $fileHandler) {
                throw new ParserException('Can not open log file.');
            }

            $this->fileHandler = $fileHandler;
        }

        if ($seek && null !== $this->seek) {
            \fseek($this->fileHandler, $this->seek);
        }

        return $this->fileHandler;
    }

    /**
     * Reads single line from file.
     *
     * @throws ParserException
     */
    protected function readLine(): void
    {
        $buffer = '';

        while ('' === $buffer) {
            if (($buffer = \fgets($this->getFileHandler())) === false) {
                $this->currentLine = null;

                return;
            }
            $buffer = \trim($buffer, "\n\r\0");

            if (!$this->skipEmptyLines) {
                break;
            }
        }

        $this->currentLine = $buffer;
    }

    /**
     * Returns parsed current line.
     */
    public function current(): ?array
    {
        if (null === $this->currentLine) {
            $this->readLine();
        }

        if (null === $this->currentLine) {
            return null;
        }

        return $this->parser->parseLine($this->currentLine);
    }

    public function next(): void
    {
        $this->readLine();
    }

    /**
     * Returns current line.
     *
     * @return string|null
     */
    public function key()
    {
        return $this->currentLine;
    }

    public function valid(): bool
    {
        return !\feof($this->getFileHandler()) || $this->currentLine;
    }

    public function rewind(): void
    {
        \rewind($this->getFileHandler());
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Seeks on a file pointer.
     */
    public function setSeek(int $seek): self
    {
        $this->seek = $seek;

        return $this;
    }

    /**
     * Seeks on a file pointer.
     */
    public function getSeek(): int
    {
        return $this->seek;
    }

    /**
     * Returns the current position of the file read/write pointer.
     */
    public function getTell(): ?int
    {
        $fileHandler = $this->getFileHandler(false);

        $data = \ftell($fileHandler);

        return false === $data ? null : $data;
    }
}
