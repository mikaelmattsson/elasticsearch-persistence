<?php

namespace Seek\Persistence;

use Elasticsearch\Client;
use Seek\Document\DocumentInterface;
use Seek\Index\IndexList;

class DocumentDeleteHandler
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
     * DocumentDeleteHandler constructor.
     * @param Client $client
     * @param IndexList $indexList
     */
    public function __construct(Client $client, IndexList $indexList)
    {
        $this->client = $client;
        $this->indexList = $indexList;
    }

    /**
     * @param DocumentInterface[] $documents
     */
    public function delete(array $documents)
    {
        $body = [];
        $i = 0;
        foreach ($documents as $document) {
            $index = $this->indexList->getIndexOfDocument($document);

            $body[] = [
                'delete' => [
                    '_index' => $index->getIndex(),
                    '_type' => $index->getType(),
                    '_id' => $document->getId(),
                ],
            ];

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
