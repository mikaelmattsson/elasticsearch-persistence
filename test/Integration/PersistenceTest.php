<?php

namespace SeekTest\Integration;

use Seek\Repository\DefaultRepository;
use SeekTest\Domain\TestUser\TestUser;
use SeekTest\Domain\TestUser\TestUserRepository;

class PersistenceTest extends AbstractIntegrationTest
{
    public function testSave()
    {
        $this->documentManager->getPersistenceService()->deleteIndex('test_user', true);

        $this->documentManager->prepareIndex(TestUser::class);

        $user = TestUser::create([
            'name' => 'Mr Potato Head',
            'email' => 'potato@potatohead.com',
        ]);

        $this->documentManager->persist($user);

        $this->documentManager->flush();

        sleep(1); // we cant perform searches until Elasticsearch has indexed the data. (refresh_interval)

        return $user->getId();
    }

    /**
     * @depends testSave
     * @param $id
     */
    public function testFind($id)
    {
        $repository = $this->documentManager->getRepository(TestUser::class);

        $this->assertInstanceOf(TestUserRepository::class, $repository);

        $user = $repository->find($id);

        $this->assertInstanceOf(TestUser::class, $user);

        $this->assertEquals('Mr Potato Head', $user->get('name'));
    }

    /**
     * @depends testSave
     * @param $id
     */
    public function testFindByName($id)
    {
        $repository = $this->documentManager->getRepository(TestUser::class);
        $user = $repository->findOneBy(['name' => 'Mr Potato Head']);

        $this->assertInstanceOf(TestUser::class, $user);
        $this->assertEquals($id, $user->getId());

        return $id;
    }

    /**
     * @depends testSave
     * @param $id
     */
    public function testRemove($id)
    {
        $repository = $this->documentManager->getRepository(TestUser::class);
        $user = $repository->find($id);

        $this->documentManager->remove($user);
        $this->documentManager->flush();

        $userAgain = $repository->find($id);

        $this->assertNull($userAgain);
    }
}
