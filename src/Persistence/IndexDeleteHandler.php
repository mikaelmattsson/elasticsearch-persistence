<?php

namespace Seek\Persistence;

use Elasticsearch\Client;
use Seek\Index\IndexList;

class IndexDeleteHandler
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

    public function deleteAllIndexes()
    {
        return $this->client->indices()->delete(['index' => '*']);
    }
}