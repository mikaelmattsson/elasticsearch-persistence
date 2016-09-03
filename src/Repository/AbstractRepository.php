<?php

namespace Seek\Repository;

use Doctrine\Common\Persistence\ObjectRepository;
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
     * Finds an object by its primary key / identifier.
     *
     * @param mixed $id The identifier.
     *
     * @return object|null The object.
     */
    public function find($id)
    {
        return $this->findOneBy(['id' => $id]);
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
        $this->documentManager->getPersistenceService()->findBy(
            $this->documentClass,
            $this->documentCollectionClass,
            $criteria,
            $orderBy,
            $limit,
            $offset
        );
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
        return $this->documentManager->getPersistenceService()->findOneBy(
            $this->documentClass,
            $criteria
        );
    }

    /**
     * Returns the class name of the object managed by the repository.
     *
     * @return string
     */
    public function getClassName()
    {
        return $this->documentClass;
    }
}
