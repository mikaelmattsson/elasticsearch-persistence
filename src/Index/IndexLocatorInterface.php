<?php

namespace Seek\Index;

interface IndexLocatorInterface
{
    public function get(string $documentClassName) : IndexInterface;
}
