<?php

namespace Seek\Persistence;

use Elasticsearch\Client;
use Elasticsearch\Common\Exceptions\Missing404Exception;
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

    /**
     * @return array
     */
    public function deleteAllIndexes()
    {
        return $this->client->indices()->delete(['index' => '*']);
    }

    /**
     * @param $documentClass
     * @param $ignoreMissing
     * @return array|bool
     * @throws Missing404Exception
     */
    public function deleteIndex($documentClass, $ignoreMissing)
    {
        $indexName = $this->indexList->getIndexOfClass($documentClass)->getIndex();

        try {
            return $this->client->indices()->delete(['index' => $indexName]);
        } catch (Missing404Exception $e) {
            if ($ignoreMissing) {
                return false;
            }

            throw $e;
        }
    }
}
