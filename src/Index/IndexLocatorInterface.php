<?php

namespace ElasticPersistence\Index;

interface IndexLocatorInterface
{
    public function get(string $documentClassName) : IndexInterface;
}
