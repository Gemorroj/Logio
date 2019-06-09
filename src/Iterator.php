<?php

namespace Logio;

use MVar\LogParser\LogIterator;

class Iterator extends LogIterator
{
    /**
     * @var int
     */
    private $seek;
    /**
     * @var string
     */
    private $name;

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Seeks on a file pointer.
     *
     * @param int $seek
     *
     * @return $this
     */
    public function setSeek(int $seek): self
    {
        $this->seek = $seek;

        return $this;
    }

    /**
     * Seeks on a file pointer.
     *
     * @return int
     */
    public function getSeek(): int
    {
        return $this->seek;
    }

    /**
     * Returns the current position of the file read/write pointer.
     *
     * @return int|null
     */
    public function getTell(): ?int
    {
        $fileHandler = parent::getFileHandler();

        $data = \ftell($fileHandler);

        return false === $data ? null : $data;
    }

    /**
     * {@inheritdoc}
     */
    protected function getFileHandler()
    {
        $fileHandler = parent::getFileHandler();

        if (null !== $this->seek) {
            \fseek($fileHandler, $this->seek);
        }

        return $fileHandler;
    }
}
