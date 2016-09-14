<?php

namespace Seek\Persistence;

use Elasticsearch\Client;
use Seek\Collection\DocumentCollection;
use Seek\Criteria\Criteria;
use Seek\Document\DocumentInterface;
use Seek\Index\IndexList;

class PersistenceService
{
    /**
     * @var DocumentSaveHandler
     */
    protected $documentSaveHandler;

    /**
     * @var DocumentFindHandler
     */
    protected $documentFindHandler;

    /**
     * @var UnitOfWork
     */
    private $unitOfWork;

    /**
     * @param UnitOfWork $unitOfWork
     * @param Client $client
     * @param IndexList $indexList
     */
    public function __construct(Client $client, IndexList $indexList, UnitOfWork $unitOfWork)
    {
        $this->documentFactory = new DocumentFactory();
        $this->documentSaveHandler = new DocumentSaveHandler($client, $indexList);
        $this->documentFindHandler = new DocumentFindHandler($client, $indexList, $this->documentFactory);
        $this->documentDeleteHandler = new DocumentDeleteHandler($client, $indexList);
        $this->indexDeleteHandler = new IndexDeleteHandler($client, $indexList);
        $this->unitOfWork = $unitOfWork;
    }

    /**
     * @param DocumentInterface[] $documents
     */
    public function save($documents)
    {
        $this->documentSaveHandler->save($documents);
    }

    /**
     * @param DocumentInterface[] $documents
     */
    public function delete($documents)
    {
        $this->documentDeleteHandler->delete($documents);
        foreach ($documents as $document) {
            $this->unitOfWork->detach($document);
        }
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

        $this->unitOfWork->persistMany($result);

        return $result;
    }

    /**
     * @param string $documentClass
     * @param array $properties
     * @return DocumentInterface
     */
    public function findOneBy(string $documentClass, array $properties)
    {
        if (isset($properties['id'])) {
            $result = $this->documentFindHandler->findOneById($documentClass, $properties['id']);

            if ($result) {
                $this->unitOfWork->persist($result);
            }

            return $result;
        }

        $result = $this->documentFindHandler->findOneByProperties($documentClass, $properties);

        if ($result) {
            $this->unitOfWork->persist($result);
        }

        return $result;
    }

    /**
     *
     */
    public function deleteAllIndexes()
    {
        $this->indexDeleteHandler->deleteAllIndexes();
    }

    /**
     * @param string $indexName
     * @param bool $ignoreMissing
     */
    public function deleteIndex($indexName, $ignoreMissing = false)
    {
        $this->indexDeleteHandler->deleteIndex($indexName, $ignoreMissing);
    }

    /**
     * @param string $documentClass
     * @param string $documentCollectionClass
     * @param Criteria $criteria
     * @return DocumentCollection|\Seek\Document\DocumentInterface[]
     */
    public function search($documentClass, $documentCollectionClass, Criteria $criteria)
    {
        return $this->documentFindHandler->search($documentClass, $documentCollectionClass, $criteria);
    }
}
