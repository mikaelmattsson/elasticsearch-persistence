<?php

namespace SeekTest\Integration;

use Seek\Repository\DefaultRepository;
use SeekTest\Domain\TestPet\TestPet;

class SimpleTest extends AbstractIntegrationTest
{
    public function testSave()
    {
        $this->documentManager->getPersistenceService()->deleteIndex(TestPet::class, true);

        $this->documentManager->prepareIndex(TestPet::class);

        $pet = TestPet::create([
            'name' => 'Lassie',
        ]);

        $this->documentManager->save($pet);

        sleep(1); // we cant perform searches until Elasticsearch has indexed the data. (refresh_interval)

        return $pet->getId();
    }

    /**
     * @depends testSave
     * @param $id
     */
    public function testFind($id)
    {
        $repository = $this->documentManager->getRepository(TestPet::class);

        $this->assertInstanceOf(DefaultRepository::class, $repository);

        $user = $repository->find($id);

        $this->assertInstanceOf(TestPet::class, $user);

        $this->assertEquals('Lassie', $user->get('name'));
    }

    /**
     * @depends testSave
     * @param $id
     */
    public function testFindByName($id)
    {
        $repository = $this->documentManager->getRepository(TestPet::class);
        $user = $repository->findOneBy(['name' => 'Lassie']);

        $this->assertInstanceOf(TestPet::class, $user);
        $this->assertEquals($id, $user->getId());

        return $id;
    }

    /**
     * @depends testSave
     * @param $id
     */
    public function testRemove($id)
    {
        $repository = $this->documentManager->getRepository(TestPet::class);
        $user = $repository->find($id);

        $this->documentManager->remove($user);
        $this->documentManager->flush();

        $userAgain = $repository->find($id);

        $this->assertNull($userAgain);
    }
}
