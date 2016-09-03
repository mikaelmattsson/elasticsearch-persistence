<?php

namespace Seek\Persistence;

use Elasticsearch\Client;
use Seek\Collection\DocumentCollection;
use Seek\Document\DocumentInterface;
use Seek\Index\IndexList;

class DocumentFindHandler
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
     * @var DocumentFactory
     */
    private $documentFactory;

    /**
     * DocumentSaveHandler constructor.
     *
     * @param Client $client
     * @param IndexList $indexList
     * @param DocumentFactory $documentFactory
     */
    public function __construct(Client $client, IndexList $indexList, DocumentFactory $documentFactory)
    {
        $this->client = $client;
        $this->indexList = $indexList;
        $this->documentFactory = $documentFactory;
    }

    /**
     * @param string $class
     * @param string $collectionClass
     * @param array $properties
     * @param array $orderBy
     * @param int $limit
     * @param int $offset
     * @return DocumentInterface[]|DocumentCollection
     */
    public function findByProperties(
        string $class,
        string $collectionClass,
        array $properties,
        array $orderBy = null,
        $limit = null,
        $offset = null
    ) {
        $index = $this->indexList->getIndexOfClass($class);

        $result = $this->client->search([
            'index' => $index->getIndex(),
            'type'  => $index->getType(),
            'body'  => [
                'query' => [
                    'match' => $properties,
                ],
            ],
        ]);

        return $this->documentFactory->makeMany($class, $collectionClass, $result);
    }

    /**
     * @param string $class
     * @param array $properties
     * @return DocumentInterface
     */
    public function findOneByProperties(string $class, array $properties)
    {
        $index = $this->indexList->getIndexOfClass($class);

        $result = $this->client->search([
            'index' => $index->getIndex(),
            'type'  => $index->getType(),
            'body'  => [
                'query' => [
                    'match' => $properties,
                ],
            ],
        ]);

        if (count($result['hits']['hits']) === 0) {
            return null;
        }

        return $this->documentFactory->makeOne($class, $result['hits']['hits'][0]);
    }

    /**
     * @param string $class
     * @param $id
     * @return DocumentInterface
     */
    public function findOneById(string $class, $id)
    {
        $index = $this->indexList->getIndexOfClass($class);

        $params = [
            'index' => $index->getIndex(),
            'type'  => $index->getType(),
            'id'    => $id,
        ];

        $result = $this->client->get($params);

        return $this->documentFactory->makeOne($class, $result);
    }
}
