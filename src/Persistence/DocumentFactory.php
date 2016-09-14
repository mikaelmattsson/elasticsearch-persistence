<?php

namespace Seek\Persistence;

use Seek\Collection\DocumentCollection;
use Seek\Document\DocumentInterface;
use Seek\Index\IndexInterface;

class DocumentFactory
{
    /**
     * @param $documentClass
     * @param $result
     * @param IndexInterface $index
     * @return DocumentInterface
     */
    public function makeOne($documentClass, $result, IndexInterface $index)
    {
        $document = $index->deserialize($result['_source'], $result['_id']);
        $document = new $documentClass($result['_source'], $result['_id']);

        return $document;
    }

    /**
     * @param $class
     * @param $collectionClass
     * @param $result
     * @param IndexInterface $index
     * @return DocumentCollection|\Seek\Document\DocumentInterface[]
     */
    public function makeMany($class, $collectionClass, $result, IndexInterface $index)
    {
        $documents = [];
        if (count($result['hits']['hits']) > 0) {
            foreach ($result['hits']['hits'] as $row) {
                $documents[] = $this->makeOne($class, $row, $index);
            }
        }

        return new $collectionClass($documents);
    }
}
