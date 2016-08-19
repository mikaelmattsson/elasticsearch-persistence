<?php

namespace ElasticPersistence\Index;

use ElasticPersistence\DocumentInterface;

interface IndexInterface
{
    public function serialize(DocumentInterface $document) : array;

    public function deserialize(array $data) : DocumentInterface;

    public function getIndex() : string;

    public function getType() : string;
}
