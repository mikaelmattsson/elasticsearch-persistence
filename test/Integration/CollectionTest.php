<?php

namespace SeekTest\Integration;

use SeekTest\Domain\TestUser\TestUser;
use SeekTest\Domain\TestUser\TestUserCollection;

class CollectionTest extends AbstractIntegrationTest
{
    public function testCollection()
    {
        $this->documentManager->getPersistenceService()->deleteIndex(TestUser::class, true);

        $this->documentManager->prepareIndex(TestUser::class);

        $user = TestUser::create([
            'name'  => 'Mr Potato Head',
            'email' => 'potato@potatohead.com',
        ]);

        $this->documentManager->persist($user);

        $user2 = TestUser::create([
            'name'  => 'Mr Potato Head 2',
            'email' => 'potato2@potatohead.com',
        ]);

        $this->documentManager->persist($user2);

        $this->documentManager->flush();

        sleep(1); // we cant perform searches until Elasticsearch has indexed the data. (refresh_interval)

        $repository = $this->documentManager->getRepository(TestUser::class);

        $collection = $repository->findAll();

        $this->assertInstanceOf(TestUserCollection::class, $collection);

        $this->assertCount(2, $collection);
        $this->assertTrue(in_array($collection->first(), [$user, $user2]));

        return [$user, $user2];
    }

    /**
     * @depends testCollection
     * @param TestUser[] $users
     */
    public function testSort($users)
    {
        list($user1, $user2) = $users;

        $repository = $this->documentManager->getRepository(TestUser::class);

        $collection = $repository->findBy([], [['email' => 'desc']]);

        $this->assertEquals($collection->first(), $user1);

        $collection = $repository->findBy([], [['email' => 'asc']]);

        $this->assertEquals($collection->first(), $user2);
    }
}
