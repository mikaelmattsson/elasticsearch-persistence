<?php

namespace ElasticPersistence\Index;

class SimpleIndexLocator implements IndexLocatorInterface
{
    public function get(string $documentClassName) : IndexInterface
    {
        $indexClassName = $documentClassName.'Index';

        return new $indexClassName();
    }
}
