<?php

namespace SeekTest\Integration;

use Seek\Repository\DefaultRepository;
use SeekTest\Domain\TestUser\TestUser;
use SeekTest\Domain\TestUser\TestUserCollection;

class CollectionTest extends AbstractIntegrationTest
{
    public function testCollection()
    {
        $this->documentManager->getPersistenceService()->deleteIndex('test_user', true);

        $this->documentManager->prepareIndex(TestUser::class);

        $user = TestUser::create([
            'name' => 'Mr Potato Head',
            'email' => 'potato@potatohead.com',
        ]);

        $this->documentManager->persist($user);

        $user2 = TestUser::create([
            'name' => 'Mr Potato Head 2',
            'email' => 'potato2@potatohead.com',
        ]);

        $this->documentManager->persist($user2);

        $this->documentManager->flush();

        sleep(1); // we cant perform searches until Elasticsearch has indexed the data. (refresh_interval)

        $repository = $this->documentManager->getRepository(TestUser::class);

        $userCollection = $repository->findAll();
        
        $this->assertInstanceOf(TestUserCollection::class, $userCollection);

        $this->assertCount(2, $userCollection);
        $this->assertTrue(in_array($userCollection->first(), [$user, $user2]));
        $this->assertEquals($userCollection->getValues(), [$user, $user2]);
    }
}
