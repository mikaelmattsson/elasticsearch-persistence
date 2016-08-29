<?php

namespace Seek\Persistence;

use Elasticsearch\Client;
use Seek\Index\IndexManager;

class DocumentFindHandler
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
     * @param Client $client
     * @param IndexManager $indexManager
     */
    public function __construct(Client $client, IndexManager $indexManager)
    {
        $this->client = $client;
        $this->indexManager = $indexManager;
    }

    /**
     * @param string $class
     * @param array $criteria
     * @param array $orderBy
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function find(string $class, array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        $index = $this->indexManager->getIndexOfClass($class);

        $params = [
            'index' => $index->getIndex(),
            'type' => $index->getType(),
        ];

        if (isset($criteria['id'])) {
            $params['id'] = $criteria['id'];

            return $this->client->get($params);
        }

        $params['body'] = [
            'query' => [
                'match' => $criteria,
            ],
        ];

        return $this->client->search($params);
    }
}
