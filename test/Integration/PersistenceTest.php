<?php

namespace SeekTest\Integration;

use Seek\Repository\DefaultRepository;
use SeekTest\Domain\User\User;
use Ramsey\Uuid\Uuid;

class PersistenceTest extends AbstractIntegrationTest
{
    public function testSave()
    {
        $user = new User([
            'name' => 'Mr Potato Head',
            'email' => 'potato@potatohead.com',
        ]);

        $this->documentManager->persist($user);

        $this->documentManager->flush();

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

        var_dump($user); die();

        $this->assertInstanceOf(User::class, $user);

        $this->assertEquals('Mr Potato Head', $user->getName());
    }

    /**
     * @depends testSave
     * @param $id
     */
    public function testFindByName($id)
    {
        $repository = $this->documentManager->getRepository(User::class);
        $user = $repository->findBy(['name' => 'Mr Potato Head']);

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
    }
}
