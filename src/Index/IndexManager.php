<?php

namespace Seek\Index;

use Seek\DocumentInterface;

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

    public function getIndexOfDocument(DocumentInterface $documentInterface) : IndexInterface
    {
        $className = get_class($documentInterface);

        if (isset($this->indexes[$className])) {
            return $this->indexes[$className];
        }

        return $this->indexes[$className] = $this->indexLocator->get($className);
    }
}
