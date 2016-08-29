<?php

namespace Seek\Document;

interface DocumentInterface
{
    public function getId() : string;
    public function getProperties() : array;
    public function isDirty() : bool;
}
