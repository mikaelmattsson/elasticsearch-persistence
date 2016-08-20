<?php

namespace Seek\Document;

use Ramsey\Uuid\Uuid;
use Seek\bool;

interface DocumentInterface
{
    public function getUuid() : Uuid;
    public function isDirty() : bool;
}
