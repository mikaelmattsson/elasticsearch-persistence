<?php

namespace Seek\Persistence;

use Elasticsearch\Client;
use Seek\Collection\DocumentCollection;
use Seek\Document\DocumentInterface;
use Seek\Index\IndexList;

class PersistenceService
{
    /**
     * @var UnitOfWork
     */
    protected $unitOfWork;

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
     * @param IndexList $indexList
     */
    public function __construct(UnitOfWork $unitOfWork, Client $client, IndexList $indexList)
    {
        $this->unitOfWork = $unitOfWork;
        $this->documentFactory = new DocumentFactory();
        $this->documentSaveHandler = new DocumentSaveHandler($client, $indexList);
        $this->documentFindHandler = new DocumentFindHandler($client, $indexList, $this->documentFactory);
        $this->indexDeleteHandler = new IndexDeleteHandler($client, $indexList);
    }

    /**
     *
     */
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
     * @return DocumentInterface[]|DocumentCollection
     */
    public function findBy(
        string $documentClass,
        string $collectionClass,
        array $query,
        array $orderBy = null,
        $limit = null,
        $offset = null
    ) {
        $result = $this->documentFindHandler->findByProperties($documentClass, $collectionClass,
            $query, $orderBy, $limit, $offset);

        return $result;
    }

    /**
     * @param string $documentClass
     * @param array $criteria
     * @return DocumentInterface
     */
    public function findOneBy(string $documentClass, array $criteria)
    {
        if (isset($criteria['id'])) {
            return $this->documentFindHandler->findOneById($documentClass, $criteria['id']);
        }

        return $this->documentFindHandler->findOneByProperties($documentClass, $criteria);
    }

    /**
     *
     */
    public function deleteAllIndexes()
    {
        $this->indexDeleteHandler->deleteAllIndexes();
    }
}
