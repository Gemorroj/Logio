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
        if (null !== $this->fileHandler) {
            @\fclose($this->fileHandler);
        }
    }

    /**
     * Returns file handler.
     *
     * @throws ParserException
     *
     * @return resource
     */
    protected function getFileHandler()
    {
        if (null === $this->fileHandler) {
            $fileHandler = @\fopen($this->logFile, 'r');

            if (false === $fileHandler) {
                throw new ParserException('Can not open log file.');
            }

            if (null !== $this->seek) {
                \fseek($fileHandler, $this->seek);
            }

            $this->fileHandler = $fileHandler;
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
     */
    public function key(): ?string
    {
        return $this->currentLine;
    }

    public function valid(): bool
    {
        return !\feof($this->getFileHandler()) || $this->currentLine;
    }

    public function rewind(): void
    {
        $fileHandler = $this->getFileHandler();

        if (null !== $this->seek) {
            \fseek($fileHandler, $this->seek);
        } else {
            \rewind($fileHandler);
        }

        $this->currentLine = null;
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
    public function getSeek(): ?int
    {
        return $this->seek;
    }

    /**
     * Returns the current position of the file read/write pointer.
     */
    public function getTell(): ?int
    {
        $fileHandler = $this->getFileHandler();

        $data = \ftell($fileHandler);

        return false === $data ? null : $data;
    }
}
