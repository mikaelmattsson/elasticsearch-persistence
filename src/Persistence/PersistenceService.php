<?php

namespace Seek\Persistence;

use Elasticsearch\Client;
use Seek\Document\DocumentInterface;
use Seek\Index\IndexManager;

class PersistenceService
{
    /**
     * @var UnitOfWork
     */
    private $unitOfWork;

    /**
     * @var DocumentSaveHandler
     */
    protected $documentSaveHandler;

    /**
     * @var DocumentFindHandler
     */
    protected $documentFindHandler;

    /**
     * @param UnitOfWork $unitOfWork
     * @param Client $client
     * @param IndexManager $indexManager
     */
    public function __construct(UnitOfWork $unitOfWork, Client $client, IndexManager $indexManager)
    {
        $this->unitOfWork = $unitOfWork;
        $this->documentSaveHandler = new DocumentSaveHandler($client, $indexManager);
        $this->documentFindHandler = new DocumentFindHandler($client, $indexManager);
        $this->documentFactory = new DocumentFactory();
    }

    public function save()
    {
        $this->documentSaveHandler->save($this->unitOfWork);
    }

    /**
     * @param string $documentClass
     * @param string $collectionClass
     * @param array $query
     * @param array $orderBy
     * @param null $limit
     * @param null $offset
     * @return array
     */
    public function find(
        string $documentClass,
        string $collectionClass,
        array $query,
        array $orderBy = null,
        $limit = null,
        $offset = null
    ) {
        $result = $this->documentFindHandler->find($documentClass, $query, $orderBy, $limit, $offset);


        return;
    }

    /**
     * @param string $documentClass
     * @param array $criteria
     * @return DocumentInterface
     */
    public function findOne(string $documentClass, array $criteria)
    {
        $result = $this->documentFindHandler->find($documentClass, $criteria);

        return $this->documentFactory->makeOne($documentClass, $result);
    }
}
