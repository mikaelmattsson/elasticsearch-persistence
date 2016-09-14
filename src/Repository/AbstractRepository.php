<?php

namespace Seek\Repository;

use Doctrine\Common\Persistence\ObjectRepository;
use Seek\Collection\DocumentCollection;
use Seek\Criteria\Criteria;
use Seek\Document\DocumentInterface;
use Seek\DocumentManager;

/**
 * Extend this class when defining custom repositories.
 */
abstract class AbstractRepository implements ObjectRepository
{
    /**
     * @var DocumentManager
     */
    protected $documentManager;

    /**
     * @var string
     */
    protected $documentClass;

    /**
     * @var string
     */
    protected $documentCollectionClass;

    /**
     * DocumentRepository constructor.
     *
     * @param DocumentManager $documentManager
     */
    public function __construct(
        DocumentManager $documentManager
    ) {
        $this->documentManager = $documentManager;
    }

    /**
     * Finds an document by its primary key / identifier.
     * Will return cached object if possible.
     *
     * @param mixed $id The identifier.
     *
     * @return DocumentInterface|null The document.
     */
    public function find($id)
    {
        //First we try to find it in the current unit of work
        if ($doc = $this->documentManager->getUnitOfWork()->find($this->documentClass, $id)) {
            return $doc;
        }

        return $this->findOneBy(['id' => $id]);
    }

    /**
     * Finds all documents in the repository.
     *
     * @return DocumentCollection|DocumentInterface[] The documents.
     */
    public function findAll()
    {
        return $this->search(new Criteria([
            'query' => [
                'match_all' => [],
            ],
            'size' => 10000,
        ]));
    }

    /**
     * Finds documents by a set of properties.
     *
     * Optionally sorting and limiting details can be passed. An implementation may throw
     * an UnexpectedValueException if certain values of the sorting or limiting details are
     * not supported.
     *
     * @param array $properties
     * @param array|null $orderBy
     * @param int|null $limit
     * @param int|null $offset
     *
     * @return DocumentCollection|DocumentInterface[] The documents.
     *
     * @throws \UnexpectedValueException
     */
    public function findBy(array $properties, array $orderBy = null, $limit = null, $offset = null)
    {
        return $this->documentManager->getPersistenceService()->findBy(
            $this->documentClass,
            $this->documentCollectionClass,
            $properties,
            $orderBy,
            $limit,
            $offset
        );
    }

    /**
     * Finds a single document by a set of properties.
     *
     * @param array $properties The criteria.
     *
     * @return DocumentInterface|null The document.
     */
    public function findOneBy(array $properties)
    {
        return $this->documentManager->getPersistenceService()->findOneBy(
            $this->documentClass,
            $properties
        );
    }

    /**
     * @param Criteria $criteria
     * @return DocumentCollection|\Seek\Document\DocumentInterface[]
     */
    public function search(Criteria $criteria)
    {
        return $this->documentManager->getPersistenceService()->search(
            $this->documentClass,
            $this->documentCollectionClass,
            $criteria
        );
    }

    /**
     * Returns the class name of the document managed by the repository.
     *
     * @return string
     */
    public function getClassName()
    {
        return $this->documentClass;
    }
}
