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
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * Seeks on a file pointer
     *
     * @param int $seek
     * @return $this
     */
    public function setSeek($seek)
    {
        $this->seek = $seek;
        return $this;
    }


    /**
     * Seeks on a file pointer
     *
     * @return int
     */
    public function getSeek()
    {
        return $this->seek;
    }

    /**
     * Returns the current position of the file read/write pointer
     *
     * @return int
     */
    public function getTell()
    {
        $fileHandler = parent::getFileHandler();

        return \ftell($fileHandler);
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
