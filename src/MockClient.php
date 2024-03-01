<?php

namespace Helmich\MongoMock;

use ArrayIterator;
use Iterator;
use MongoDB\Database;
use MongoDB\Client;

/**
 * A mocked MongoDB client
 *
 * This class mimicks the behaviour of a MongoDB database (and also extends
 * the actual `MongoDB\Database` class and can be used as a drop-in
 * replacement). All operations are performed in-memory and are not persisted.
 *
 * NOTE: This class is not complete! Many methods are missing and I will only
 * implement them as soon as I need them. Feel free to open an issue or (better)
 * a pull request if you need something.
 *
 * @package Helmich\MongoMock
 */
class MockClient extends Client
{
    private array $databases = [];

    public function __construct(string $uri = 'mongodb://localhost:27017', array $uriOptions = [], array $driverOptions = [])
    {
    }

    public function dropDatabase(string $databaseName, array $options = [])
    {
        if (isset($this->databases[$databaseName])) {
            unset($this->databases[$databaseName]);
        }

        return [];
    }

    public function listDatabaseNames(array $options = []): Iterator
    {
        return new ArrayIterator(array_keys($this->databases));
    }

    public function listDatabases(array $options = []): ArrayIterator
    {
        $databases = [];
        foreach ($this->databases as $name => $database) {
            $databases[] = [
                'name' => $name,
                'sizeOnDisk' => strlen(serialize($database)),
                'empty' => empty($database->listCollections()->toArray()),
            ];
        }

        return new ArrayIterator($databases);
    }

    public function selectCollection(string $databaseName, string $collectionName, array $options = [])
    {
        return $this->selectDatabase($databaseName)->selectCollection($collectionName, $options);
    }

    public function selectDatabase(string $databaseName, array $options = []): Database
    {
        if (!isset($this->databases[$databaseName])) {
            $this->databases[$databaseName] = new MockDatabase($databaseName);
        }

        return $this->databases[$databaseName];
    }

    public function setDatabase(MockDatabase $database)
    {
        $name = $database->getDatabaseName();
        $this->databases[$name] = $database;
        return $this;
    }
}
