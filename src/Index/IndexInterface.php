<?php

namespace Seek\Index;

use Seek\Document\DocumentInterface;

interface IndexInterface
{
    public function serialize(DocumentInterface $document) : array;

    public function deserialize(array $data) : DocumentInterface;

    public function getIndex() : string;

    public function getType() : string;
}
