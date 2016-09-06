<?php

namespace Seek;

use Doctrine\Common\Persistence\ObjectManager;
use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Seek\Document\DocumentInterface;
use Seek\Index\IndexLocatorInterface;
use Seek\Index\IndexList;
use Seek\Index\SimpleIndexLocator;
use Seek\Persistence\PersistenceService;
use Seek\Persistence\UnitOfWork;
use Seek\Repository\AbstractRepository;
use Seek\Repository\SimpleRepositoryLocator;

class DocumentManager implements ObjectManager
{
    /**
     * @var PersistenceService
     */
    protected $persistenceService;

    /**
     * @var IndexList
     */
    protected $indexList;

    /**
     * @var SimpleRepositoryLocator
     */
    protected $repositoryLocator;

    /**
     * @var UnitOfWork
     */
    protected $unitOfWork;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var IndexLocatorInterface
     */
    protected $indexLocator;

    /**
     * DocumentManager constructor.
     *
     * @param array                $hosts
     * @param IndexLocatorInterface $indexLocator
     */
    public function __construct(array $hosts, IndexLocatorInterface $indexLocator = null)
    {
        $this->client = ClientBuilder::create()->setHosts($hosts)->build();
        $this->indexLocator = $indexLocator ? $indexLocator : new SimpleIndexLocator();
        $this->indexList = new IndexList($this->indexLocator);
        $this->unitOfWork = new UnitOfWork($this->indexList);
        $this->persistenceService = new PersistenceService($this->client, $this->indexList);
        $this->repositoryLocator = new SimpleRepositoryLocator($this);
    }

    /**
     * Finds an object by its identifier.
     *
     * This is just a convenient shortcut for getRepository($className)->find($id).
     *
     * @param string $className The class name of the object to find.
     * @param mixed  $id        The identity of the object to find.
     *
     * @return object The found object.
     */
    public function find($className, $id)
    {
        return $this->getRepository($className)->find($id);
    }

    /**
     * Tells the ObjectManager to make an instance save and persistent.
     *
     * The object will be entered into the database as a result of the flush operation.
     *
     * NOTE: The persist operation always considers objects that are not yet known to
     * this ObjectManager as NEW. Do not pass detached objects to the persist operation.
     *
     * @param DocumentInterface $object The instance to make save and persistent.
     *
     * @throws \Exception
     */
    public function persist($object)
    {
        $this->unitOfWork->persist($object);
    }

    /**
     * Removes an object instance.
     *
     * A removed object will be removed from the database as a result of the flush operation.
     *
     * @param DocumentInterface $object The object instance to remove.
     *
     * @throws \Exception
     */
    public function remove($object)
    {
        $this->unitOfWork->remove($object);
    }

    /**
     * Merges the state of a detached object into the persistence context
     * of this ObjectManager and returns the save copy of the object.
     * The object passed to merge will not become associated/save with this ObjectManager.
     *
     * @param DocumentInterface $object
     *
     * @throws \Exception
     *
     * @return object
     */
    public function merge($object)
    {
        if (!$object instanceof DocumentInterface) {
            throw new \Exception('$object is not an instance of '.DocumentInterface::class);
        }
        // TODO: Implement merge() method.
    }

    /**
     * Clears the ObjectManager. All objects that are currently save
     * by this ObjectManager become detached.
     *
     * @param string|null $objectName if given, only objects of this type will get detached.
     *
     * @throws \Exception
     */
    public function clear($objectName = null)
    {
        // TODO: Implement clear() method.
        
        $this->unitOfWork->clear();
    }

    /**
     * Detaches an object from the ObjectManager, causing a save object to
     * become detached. Unflushed changes made to the object if any
     * (including removal of the object), will not be synchronized to the database.
     * Objects which previously referenced the detached object will continue to
     * reference it.
     *
     * @param DocumentInterface $object The object to detach.
     *
     * @throws \Exception
     */
    public function detach($object)
    {
        $this->unitOfWork->detach($object);
    }

    /**
     * Refreshes the persistent state of an object from the database,
     * overriding any local changes that have not yet been persisted.
     *
     * @param DocumentInterface $object The object to refresh.
     *
     * @throws \Exception
     */
    public function refresh($object)
    {
        if (!$object instanceof DocumentInterface) {
            throw new \Exception('$object is not an instance of '.DocumentInterface::class);
        }
        // TODO: Implement refresh() method.
    }

    /**
     * Flushes all changes to objects that have been queued up to now to the database.
     * This effectively synchronizes the in-memory state of save objects with the
     * database.
     */
    public function flush()
    {
        $this->persistenceService->save($this->unitOfWork->getDocumentsForSave());
        $this->persistenceService->delete($this->unitOfWork->getDocumentsForRemoval());
    }

    /**
     * Gets the repository for a class.
     *
     * @param string $className
     *
     * @return AbstractRepository
     */
    public function getRepository($className)
    {
        return $this->repositoryLocator->get($className);
    }

    /**
     * Returns the ClassMetadata descriptor for a class.
     *
     * The class name must be the fully-qualified class name without a leading backslash
     * (as it is returned by get_class($obj)).
     *
     * @param string $className
     *
     * @return \Doctrine\Common\Persistence\Mapping\ClassMetadata
     */
    public function getClassMetadata($className)
    {
        // TODO: Implement getClassMetadata() method.
    }

    /**
     * Gets the metadata factory used to gather the metadata of classes.
     *
     * @return \Doctrine\Common\Persistence\Mapping\ClassMetadataFactory
     */
    public function getMetadataFactory()
    {
        // TODO: Implement getMetadataFactory() method.
    }

    /**
     * Helper method to initialize a lazy loading proxy or persistent collection.
     *
     * This method is a no-op for other objects.
     *
     * @param object $obj
     */
    public function initializeObject($obj)
    {
        // TODO: Implement initializeObject() method.
    }

    /**
     * Checks if the object is part of the current UnitOfWork and therefore save.
     *
     * @param DocumentInterface $object
     *
     * @throws \Exception
     *
     * @return bool
     */
    public function contains($object)
    {
        return $this->unitOfWork->contains($object);
    }

    /**
     * @param string $class
     */
    public function prepareIndex($class)
    {
        $index = $this->indexList->getIndexOfClass($class);
        
        $this->client->indices()->create([
            'index' => $index->getIndex(),
        ]);
    }

    /**
     * Save a document immediately without tracking it.
     *
     * @param DocumentInterface|DocumentInterface[] $document
     */
    public function save($document)
    {
        if ($document instanceof DocumentInterface) {
            $this->persistenceService->save([$document]);
        } else {
            $this->persistenceService->save($document);
        }
    }

    /**
     * Delete a document immediately without tracking it.
     *
     * @param DocumentInterface|DocumentInterface[] $document
     */
    public function delete($document)
    {
        if ($document instanceof DocumentInterface) {
            $this->persistenceService->delete([$document]);
        } else {
            $this->persistenceService->delete($document);
        }
    }

    /**
     * @return PersistenceService
     */
    public function getPersistenceService()
    {
        return $this->persistenceService;
    }
}
