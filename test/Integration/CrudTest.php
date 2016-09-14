<?php

namespace SeekTest\Integration;

use SeekTest\Domain\TestUser\TestUser;

class CrudTest extends AbstractIntegrationTest
{
    public function testCrud()
    {
        $this->documentManager->getPersistenceService()->deleteIndex('test_user', true);

        $this->documentManager->prepareIndex(TestUser::class);

        $repository = $this->documentManager->getRepository(TestUser::class);

        // Create

        $user = TestUser::create([
            'name' => 'Mr Potato Head',
            'email' => 'potato@potatohead.com',
        ]);

        $this->documentManager->save($user);

        // Persisting

        $isTracked = $this->documentManager->contains($user);
        $this->assertFalse($isTracked, 'Should not be tracked');

        // Read / Find

        $user = $repository->find($user->getId());
        $this->assertInstanceOf(TestUser::class, $user, 'User should have been found');

        // Delete

        $this->documentManager->delete($user);
        $user = $repository->find($user->getId());
        $this->assertNull($user, 'User should have been deleted');
    }
}
