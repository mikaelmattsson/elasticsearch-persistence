<?php

namespace Seek\Index;

use Seek\Document\DocumentInterface;

class IndexManager
{
    /**
     * @var IndexInterface[]
     */
    protected $indexes = [];

    /**
     * @var IndexLocatorInterface
     */
    private $indexLocator;

    /**
     * IndexManager constructor.
     *
     * @param IndexLocatorInterface $indexLocator
     */
    public function __construct(IndexLocatorInterface $indexLocator)
    {
        $this->indexLocator = $indexLocator;
    }

    /**
     * @param DocumentInterface $documentInterface
     * @return IndexInterface
     */
    public function getIndexOfDocument(DocumentInterface $documentInterface) : IndexInterface
    {
        return $this->getIndexOfClass(get_class($documentInterface));
    }

    /**
     * @param string $className
     * @return IndexInterface
     */
    public function getIndexOfClass(string $className) : IndexInterface
    {
        if (isset($this->indexes[$className])) {
            return $this->indexes[$className];
        }

        return $this->indexes[$className] = $this->indexLocator->get($className);
    }
}
