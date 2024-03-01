<?php

namespace Helmich\MongoMock;

use Iterator;
use MongoDB\Driver\CursorInterface;

class MockCursor implements CursorInterface, Iterator
{
    private $store = [];
    private $position = 0;

    public function __construct(array $documents = [])
    {
        $this->store = $documents;
    }

    public function toArray()
    {
        return $this->store;
    }

    public function rewind()
    {
        $this->position = 0;
    }

    public function current()
    {
        return $this->store[$this->position];
    }

    public function key()
    {
        return $this->position;
    }

    public function next()
    {
        ++$this->position;
    }

    public function valid()
    {
        return isset($this->store[$this->position]);
    }

    public function getId()
    {
        // TODO: Implement getId() method.
    }

    public function getServer()
    {
        // TODO: Implement getServer() method.
    }

    public function isDead()
    {
        // TODO: Implement isDead() method.
    }

    public function setTypeMap(array $typemap)
    {
        // TODO: Implement setTypeMap() method.
    }
}
