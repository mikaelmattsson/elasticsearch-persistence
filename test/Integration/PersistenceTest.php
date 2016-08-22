<?php

namespace SeekTest\Integration;

use Seek\Repository\Repository;
use SeekTest\Domain\User\User;
use Ramsey\Uuid\Uuid;

class PersistenceTest extends AbstractIntegrationTest
{
    public function testSave()
    {
        $user = new User([
            'uuid' => Uuid::uuid4(),
            'name' => 'Mr Potato Head',
            'email' => 'potato@potatohead.com',
        ]);

        $this->documentManager->persist($user);

        $this->documentManager->flush();
    }

    /**
     * @depends testSave
     */
    public function testFind()
    {
        $repository = $this->documentManager->getRepository(User::class);

        $this->assertInstanceOf(Repository::class, $repository);

        $this->assertEquals(true, true);
    }
}
