<?php

namespace Seek\Index;

use Seek\Document\DocumentInterface;

interface IndexInterface
{
    /**
     * Name of the index.
     *
     * @return string
     */
    public function getIndex() : string;

    /**
     * Name of the document type.
     *
     * @return string
     */
    public function getType() : string;

    /**
     * Serialize a documents properties before saving them to the database.
     *
     * @param DocumentInterface $document
     * @return array
     */
    public function serialize(DocumentInterface $document) : array;

    /**
     * Hydrate and instantiate a document with data from the database.
     *
     * @param array $data
     * @param string $id
     * @return DocumentInterface
     */
    public function deserialize(array $data, string $id) : DocumentInterface;
}
