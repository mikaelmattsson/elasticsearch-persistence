<?php

namespace SeekTest\Integration;

use Seek\Repository\DefaultRepository;
use SeekTest\Domain\User\User;

class PersistenceTest extends AbstractIntegrationTest
{
    public function testSave()
    {
        $this->documentManager->getPersistenceService()->deleteAllIndexes();

        $this->documentManager->prepareIndex(User::class);

        $user = User::create([
            'name' => 'Mr Potato Head',
            'email' => 'potato@potatohead.com',
        ]);

        $this->documentManager->persist($user);

        $this->documentManager->flush();

        sleep(1); // we cant perform searches until Elasticsearch has indexed the data.

        return $user->getId();
    }

    /**
     * @depends testSave
     * @param $id
     */
    public function testFind($id)
    {
        $repository = $this->documentManager->getRepository(User::class);

        $this->assertInstanceOf(DefaultRepository::class, $repository);

        $user = $repository->find($id);

        $this->assertInstanceOf(User::class, $user);

        $this->assertEquals('Mr Potato Head', $user->get('name'));
    }

    /**
     * @depends testSave
     * @param $id
     */
    public function testFindByName($id)
    {
        $repository = $this->documentManager->getRepository(User::class);
        $user = $repository->findOneBy(['name' => 'Mr Potato Head']);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($id, $user->getId());

        return $id;
    }

    /**
     * @depends testSave
     * @param $id
     */
    public function testRemove($id)
    {
        $repository = $this->documentManager->getRepository(User::class);
        $user = $repository->find($id);

        $this->documentManager->remove($user);
        $this->documentManager->flush();

        $userAgain = $repository->find($id);

        $this->assertNull($userAgain);
    }
}
