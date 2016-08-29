<?php

namespace Seek\Repository;

use Seek\Collection\DocumentCollection;
use Seek\DocumentManager;

/**
 * The default repository for documents
 */
class DefaultRepository extends AbstractRepository
{

    /**
     * @param DocumentManager $documentManager
     * @param string $documentClass
     * @param string $documentCollectionClass
     */
    public function __construct(
        DocumentManager $documentManager,
        string $documentClass,
        string $documentCollectionClass = DocumentCollection::class
    ) {
        parent::__construct($documentManager);
        $this->documentClass = $documentClass;
        $this->documentCollectionClass = $documentCollectionClass;
    }
}
