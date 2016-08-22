<?php

namespace Seek\Repository;

use Doctrine\Common\Persistence\ObjectRepository;
use Seek\Collection\DocumentCollection;
use Seek\DocumentManager;

class Repository implements ObjectRepository
{
    /**
     * @var DocumentManager
     */
    private $documentManager;

    /**
     * @var string
     */
    private $documentClass;

    /**
     * @var string
     */
    private $documentCollectionClass;

    /**
     * DocumentRepository constructor.
     *
     * @param DocumentManager $documentManager
     * @param string $documentClass
     * @param string $documentCollectionClass
     */
    public function __construct(
        DocumentManager $documentManager,
        string $documentClass,
        string $documentCollectionClass = DocumentCollection::class
    ) {
        $this->documentManager = $documentManager;
        $this->documentClass = $documentClass;
        $this->documentCollectionClass = $documentCollectionClass;
    }

    /**
     * Finds an object by its primary key / identifier.
     *
     * @param mixed $id The identifier.
     *
     * @return object|null The object.
     */
    public function find($id)
    {
        // TODO: Implement find() method.
    }

    /**
     * Finds all objects in the repository.
     *
     * @return array The objects.
     */
    public function findAll()
    {
        // TODO: Implement findAll() method.
    }

    /**
     * Finds objects by a set of criteria.
     *
     * Optionally sorting and limiting details can be passed. An implementation may throw
     * an UnexpectedValueException if certain values of the sorting or limiting details are
     * not supported.
     *
     * @param array $criteria
     * @param array|null $orderBy
     * @param int|null $limit
     * @param int|null $offset
     *
     * @return array The objects.
     *
     * @throws \UnexpectedValueException
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        // TODO: Implement findBy() method.
    }

    /**
     * Finds a single object by a set of criteria.
     *
     * @param array $criteria The criteria.
     *
     * @return object|null The object.
     */
    public function findOneBy(array $criteria)
    {
        // TODO: Implement findOneBy() method.
    }

    /**
     * Returns the class name of the object managed by the repository.
     *
     * @return string
     */
    public function getClassName()
    {
        // TODO: Implement getClassName() method.
    }
}
