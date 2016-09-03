<?php

namespace Seek\Document;

interface DocumentInterface
{
    public static function create(array $properties, $id = null);
    public function getId() : string;
    public function getProperties() : array;
}
