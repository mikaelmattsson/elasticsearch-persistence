<?php

namespace SeekTest\Domain\TestUser;

use Seek\Repository\AbstractRepository;

class TestUserRepository extends AbstractRepository
{
    /**
     * @var string
     */
    protected $documentClass = TestUser::class;

    /**
     * @var string
     */
    protected $documentCollectionClass = TestUserCollection::class;
}
