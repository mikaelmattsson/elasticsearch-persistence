<?php

namespace ElasticPersistence;

use Ramsey\Uuid\Uuid;

interface DocumentInterface
{
    public function getUuid() : Uuid;
    public function isDirty() : bool;
}
