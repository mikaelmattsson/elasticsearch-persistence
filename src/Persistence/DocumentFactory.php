<?php

namespace Seek\Persistence;

class DocumentFactory
{

    public function makeOne($documentClass, $result)
    {
        $document = new $documentClass($result['_source'], $result['_id']);

        return $document;
    }
}