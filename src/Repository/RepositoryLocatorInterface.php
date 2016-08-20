<?php

namespace Seek\Repository;

interface RepositoryLocatorInterface
{
    public function get(string $documentClassName) : Repository;
}
