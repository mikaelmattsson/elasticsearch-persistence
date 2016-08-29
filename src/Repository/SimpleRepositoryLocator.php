<?php

namespace Seek\Repository;

use Seek\DocumentManager;

class SimpleRepositoryLocator implements RepositoryLocatorInterface
{
    /**
     * @var array
     */
    protected $repositories = [];

    /**
     * @var DocumentManager
     */
    private $documentManager;

    /**
     * SimpleRepositoryLocator constructor.
     *
     * @param DocumentManager $documentManager
     */
    public function __construct(DocumentManager $documentManager)
    {
        $this->documentManager = $documentManager;
    }

    public function get(string $documentClassName) : DefaultRepository
    {
        if (isset($this->repositories[$documentClassName])) {
            return $this->repositories[$documentClassName];
        }
        $repositoryClassName = $documentClassName.'Repository';

        if (class_exists($repositoryClassName)) {
            $repository = new $repositoryClassName($this->documentManager, $documentClassName);
        } else {
            $repository = new DefaultRepository($this->documentManager, $documentClassName);
        }

        return $this->repositories[$documentClassName] = $repository;
    }
}
