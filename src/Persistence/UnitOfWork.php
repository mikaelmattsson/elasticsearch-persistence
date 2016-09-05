<?php

namespace Seek\Persistence;

use Seek\Document\DocumentInterface;
use Seek\Index\IndexList;

class UnitOfWork
{
    /**
     * @var DocumentInterface[]
     */
    protected $saveStack = [];

    /**
     * @var DocumentInterface[]
     */
    protected $removeStack = [];

    /**
     * @var string[]
     */
    protected $persistedStates = [];

    /**
     * @var IndexList
     */
    private $indexManager;

    /**
     * UnitOfWork constructor.
     * @param IndexList $indexManager
     */
    public function __construct(IndexList $indexManager)
    {
        $this->indexManager = $indexManager;
    }

    /**
     * @param $object
     * @throws \Exception
     */
    public function persist(DocumentInterface $object)
    {
        $this->updateState($object, 'new');
        unset($this->removeStack[$object->getId()]);
        $this->saveStack[$object->getId()] = $object;
    }

    /**
     * @param DocumentInterface $object
     */
    public function remove(DocumentInterface $object)
    {
        unset($this->saveStack[$object->getId()]);
        $this->removeStack[$object->getId()] = $object;
    }

    /**
     *
     */
    public function clear()
    {
        $this->saveStack = [];
        $this->removeStack = [];
    }

    /**
     * @param DocumentInterface $object
     */
    public function detach(DocumentInterface $object)
    {
        unset($this->saveStack[$object->getId()]);
        unset($this->removeStack[$object->getId()]);
    }

    /**
     * @return DocumentInterface[]
     */
    public function getDocumentsForSave()
    {
        return array_filter(
            $this->saveStack,
            function ($object) {
                return $this->isDirty($object);
            }
        );
    }

    /**
     * @return DocumentInterface[]
     */
    public function getDocumentsForRemoval()
    {
        return $this->removeStack;
    }

    /**
     * @param DocumentInterface $object
     */
    protected function saveState(DocumentInterface $object)
    {
        if (isset($this->persistedStates[$object->getId()])) {
            return;
        }

        $this->updateState($object, md5(serialize($this->serializeDocument($object))));
    }

    /**
     * @param DocumentInterface $object
     * @param string $state
     */
    protected function updateState(DocumentInterface $object, string $state)
    {
        $this->persistedStates[$object->getId()] = $state;
    }

    /**
     * @param DocumentInterface $object
     * @return bool
     */
    public function isDirty(DocumentInterface $object)
    {
        $hash = $object->getId();

        if (!isset($this->persistedStates[$hash])) {
            return true;
        }

        return $this->persistedStates[$hash] !== md5(serialize($this->serializeDocument($object)));
    }

    /**
     * @param DocumentInterface $object
     * @return array
     */
    public function serializeDocument(DocumentInterface $object) : array
    {
        $locator = $this->indexManager->getIndexOfDocument($object);

        return $locator->serialize($object);
    }

    /**
     * @param $object
     * @return bool
     */
    public function contains(DocumentInterface $object)
    {
        return isset($this->saveStack[$object->getId()])
        || isset($this->removeStack[$object->getId()]);
    }
}
