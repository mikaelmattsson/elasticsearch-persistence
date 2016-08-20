<?php

namespace Seek\Index;

use Seek\Exception\ClassNotFoundException;

class SimpleIndexLocator implements IndexLocatorInterface
{
    protected $indexes = [];

    public function get(string $documentClassName) : IndexInterface
    {
        if (isset($this->indexes[$documentClassName])) {
            return $this->indexes[$documentClassName];
        }

        $indexClassName = $documentClassName.'Index';

        if (class_exists($indexClassName)) {
            $index = new $indexClassName($documentClassName);
        } else {
            throw new ClassNotFoundException("Class `$indexClassName` not found.");
        }

        return $this->indexes[$documentClassName] = $index;
    }
}
