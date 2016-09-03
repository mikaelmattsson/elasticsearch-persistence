<?php

namespace Seek\Persistence;

use Elasticsearch\Client;
use Seek\Index\IndexList;

class DocumentSaveHandler
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var IndexList
     */
    private $indexList;

    /**
     * DocumentSaveHandler constructor.
     *
     * @param Client       $client
     * @param IndexList $indexList
     */
    public function __construct(Client $client, IndexList $indexList)
    {
        $this->client = $client;
        $this->indexList = $indexList;
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
            $index = $this->indexList->getIndexOfDocument($document);

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
