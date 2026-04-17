<?php

namespace App\Core;

use MongoDB\Driver\Manager;
use MongoDB\Driver\Query;
use MongoDB\Driver\BulkWrite;
use MongoDB\Driver\Command;

class MongoDatabase
{
    private static ?Manager $manager = null;
    private static string $database = 'quai_antique';

    public static function getManager(): Manager
    {
        if (self::$manager === null) {
            $host = getenv('MONGO_HOST') ?: 'mongo';
            $port = getenv('MONGO_PORT') ?: '27017';
            self::$manager = new Manager("mongodb://$host:$port");
        }

        return self::$manager;
    }

    public static function getNamespace(string $collection): string
    {
        return self::$database . '.' . $collection;
    }

    public static function find(string $collection, array $filter = [], array $options = []): array
    {
        $query = new Query($filter, $options);
        $cursor = self::getManager()->executeQuery(self::getNamespace($collection), $query);

        $results = [];
        foreach ($cursor as $document) {
            $results[] = (array) $document;
        }

        return $results;
    }

    public static function findOne(string $collection, array $filter): ?array
    {
        $results = self::find($collection, $filter, ['limit' => 1]);
        return $results[0] ?? null;
    }

    public static function insert(string $collection, array $document): void
    {
        $bulk = new BulkWrite();
        $bulk->insert($document);
        self::getManager()->executeBulkWrite(self::getNamespace($collection), $bulk);
    }

    public static function update(string $collection, array $filter, array $update): void
    {
        $bulk = new BulkWrite();
        $bulk->update($filter, ['$set' => $update]);
        self::getManager()->executeBulkWrite(self::getNamespace($collection), $bulk);
    }

    public static function delete(string $collection, array $filter): void
    {
        $bulk = new BulkWrite();
        $bulk->delete($filter);
        self::getManager()->executeBulkWrite(self::getNamespace($collection), $bulk);
    }

    public static function count(string $collection, array $filter = []): int
    {
        $command = new Command(['count' => $collection, 'query' => (object) $filter]);
        $result = self::getManager()->executeCommand(self::$database, $command);
        return $result->toArray()[0]->n;
    }

    public static function aggregate(string $collection, array $pipeline): ?float
    {
        $command = new Command([
            'aggregate' => $collection,
            'pipeline' => $pipeline,
            'cursor' => new \stdClass(),
        ]);
        $result = self::getManager()->executeCommand(self::$database, $command);
        $rows = $result->toArray();

        if (empty($rows) || empty($rows[0]->cursor->firstBatch)) {
            return null;
        }

        return round($rows[0]->cursor->firstBatch[0]->avg, 1);
    }
}
