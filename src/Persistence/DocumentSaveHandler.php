<?php

namespace Seek\Persistence;

use Elasticsearch\Client;
use Seek\Index\IndexManager;

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
     * @param UnitOfWork $unitOfWork
     */
    public function save(UnitOfWork $unitOfWork)
    {
        $documents = $unitOfWork->getDocumentsForSave();

        $body = [];
        $i = 0;
        foreach ($documents as $document) {
            $index = $this->indexManager->getIndexOfDocument($document);

            $body[] = [
                'index' => [
                    '_index' => $index->getIndex(),
                    '_type' => $index->getType(),
                    '_id' => $document->getId(),
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
