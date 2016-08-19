<?php

namespace ElasticPersistence\Hibernate;

use ElasticPersistence\DocumentInterface;
use ElasticPersistence\Index\IndexManager;
use Elasticsearch\Client;

class DocumentSaveHandler
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var IndexManager
     */
    private $indexManager;

    /**
     * DocumentSaveHandler constructor.
     *
     * @param Client       $client
     * @param IndexManager $indexManager
     */
    public function __construct(Client $client, IndexManager $indexManager)
    {
        $this->client = $client;
        $this->indexManager = $indexManager;
    }

    /**
     * @param DocumentInterface[] $documents
     */
    public function save(array $documents)
    {
        $body = [];
        $i = 0;
        foreach ($documents as $document) {
            if (!$document->isDirty()) {
                continue;
            }

            $index = $this->indexManager->getIndexOfDocument($document);

            $body[] = [
                'index' => [
                    '_index' => $index->getIndex(),
                    '_type' => $index->getType(),
                    '_id' => $document->getUuid(),
                ],
            ];

            $body[] = $index->serialize($document);

            if (++$i % 1000 == 0) {
                $this->client->bulk(['body' => $body]);
                $body = [];
            }
        }

        if ($body) {
            $this->client->bulk(['body' => $body]);
        }
    }
}
