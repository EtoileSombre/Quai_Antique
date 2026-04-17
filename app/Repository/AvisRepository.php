<?php

namespace App\Repository;

use App\Core\MongoDatabase;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class AvisRepository
{
    private string $collection = 'avis';

    public function findAll(): array
    {
        $avis = MongoDatabase::find($this->collection, [], ['sort' => ['created_at' => -1]]);
        return array_map([$this, 'normalize'], $avis);
    }

    public function findApproved(): array
    {
        $avis = MongoDatabase::find($this->collection, ['approved' => true], ['sort' => ['created_at' => -1]]);
        return array_map([$this, 'normalize'], $avis);
    }

    public function findLatestApproved(int $limit = 5): array
    {
        $avis = MongoDatabase::find($this->collection, ['approved' => true], [
            'sort' => ['created_at' => -1],
            'limit' => $limit,
        ]);
        return array_map([$this, 'normalize'], $avis);
    }

    public function findById(string $id): ?array
    {
        if (!$this->isValidObjectId($id)) return null;
        $avis = MongoDatabase::findOne($this->collection, ['_id' => new ObjectId($id)]);
        return $avis ? $this->normalize($avis) : null;
    }

    public function create(string $userName, int $rating, string $comment): void
    {
        MongoDatabase::insert($this->collection, [
            'user_name' => $userName,
            'rating' => $rating,
            'comment' => $comment,
            'approved' => false,
            'created_at' => new UTCDateTime(),
        ]);
    }

    public function approve(string $id): void
    {
        if (!$this->isValidObjectId($id)) return;
        MongoDatabase::update($this->collection, ['_id' => new ObjectId($id)], ['approved' => true]);
    }

    public function reject(string $id): void
    {
        if (!$this->isValidObjectId($id)) return;
        MongoDatabase::update($this->collection, ['_id' => new ObjectId($id)], ['approved' => false]);
    }

    public function delete(string $id): void
    {
        if (!$this->isValidObjectId($id)) return;
        MongoDatabase::delete($this->collection, ['_id' => new ObjectId($id)]);
    }

    public function getAverageRating(): ?float
    {
        return MongoDatabase::aggregate($this->collection, [
            ['$match' => ['approved' => true]],
            ['$group' => ['_id' => null, 'avg' => ['$avg' => '$rating']]],
        ]);
    }

    private function isValidObjectId(string $id): bool
    {
        return (bool) preg_match('/^[a-f0-9]{24}$/', $id);
    }

    private function normalize(array $avis): array
    {
        $avis['id'] = (string) $avis['_id'];
        unset($avis['_id']);

        if (isset($avis['created_at']) && $avis['created_at'] instanceof UTCDateTime) {
            $avis['created_at'] = $avis['created_at']->toDateTime()->format('Y-m-d H:i');
        }

        return $avis;
    }
}
