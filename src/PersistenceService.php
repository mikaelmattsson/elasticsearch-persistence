<?php

namespace Seek;

use Elasticsearch\Client;

class PersistenceService
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var PersistenceWrapper[]
     */
    protected $persisting = [];

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;

        register_shutdown_function([$this, 'flush']);
    }

    /**
     * @param DocumentInterface $document
     * @param MapperInterface   $repository
     *
     * @return PersistenceService
     */
    public function persist(DocumentInterface $document, MapperInterface $repository) : PersistenceService
    {
        $key = spl_object_hash($document);

        if (!isset($this->persisting[$key])) {
            $this->persisting[$key] = new PersistenceWrapper($document, $repository);
        }

        return $this;
    }

    /**
     * @param DocumentInterface[] $documents
     * @param MapperInterface     $mapper
     *
     * @return PersistenceService
     */
    public function persistMultiple(array $documents, MapperInterface $mapper) : PersistenceService
    {
        foreach ($documents as $document) {
            $this->persist($document, $mapper);
        }

        return $this;
    }

    /**
     *
     */
    public function flush()
    {
    }

    public function get($params, MapperInterface $mapper) : Result
    {
        return new Result($this->client->get($params), $mapper);
    }

    public function search($params, MapperInterface $mapper) : Result
    {
        return new Result($this->client->search($params), $mapper);
    }
}
