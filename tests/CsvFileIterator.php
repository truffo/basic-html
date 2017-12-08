<?php

class CsvFileIterator implements Iterator
{
    /**
     * @var resource
     */
    protected $file;

    /**
     * @var int
     */
    protected $key = 0;

    /**
     * @var
     */
    protected $current;

    /**
     * CsvFileIterator constructor.
     *
     * @param $file
     */
    public function __construct($file)
    {
        $this->file = fopen($file, 'r');
    }

    public function __destruct()
    {
        fclose($this->file);
    }

    public function rewind()
    {
        rewind($this->file);
        $this->current = fgetcsv($this->file);
        $this->key = 0;
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return !feof($this->file);
    }

    /**
     * @return int
     */
    public function key()
    {
        return $this->key;
    }

    /**
     * @return mixed
     */
    public function current()
    {
        return $this->current;
    }

    public function next()
    {
        $this->current = fgetcsv($this->file);
        ++$this->key;
    }
}
