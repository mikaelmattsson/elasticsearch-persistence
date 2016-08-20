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

    public function get(string $documentClassName) : Repository
    {
        if (isset($this->repositories[$documentClassName])) {
            return $this->repositories[$documentClassName];
        }
        $repositoryClassName = $documentClassName.'Repository';

        if (class_exists($repositoryClassName)) {
            $repository = new $repositoryClassName($documentClassName);
        } else {
            $repository = new Repository($documentClassName);
        }

        return $this->repositories[$documentClassName] = $repository;
    }
}
