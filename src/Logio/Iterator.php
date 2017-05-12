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
     * @param int $seek
     */
    public function setSeek($seek)
    {
        $this->seek = $seek;
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
