<?php

namespace Seek\Persistence;

use Seek\Collection\DocumentCollection;
use Seek\Document\DocumentInterface;

class DocumentFactory
{
    /**
     * @param $documentClass
     * @param $result
     * @return DocumentInterface
     */
    public function makeOne($documentClass, $result)
    {
        $document = new $documentClass($result['_source'], $result['_id']);

        return $document;
    }

    /**
     * @param $class
     * @param $collectionClass
     * @param $result
     * @return DocumentInterface[]|DocumentCollection
     */
    public function makeMany($class, $collectionClass, $result)
    {
        $documents = [];
        if (!count($result['hits']['hits']) === 0) {
            foreach ($result['hits']['hits'] as $row) {
                $documents[] = $this->makeOne($class, $row);
            }
        }

        return new $collectionClass($documents);
    }
}