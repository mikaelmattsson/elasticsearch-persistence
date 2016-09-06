<?php

namespace SeekTest\Integration;

use SeekTest\Domain\User\User;

class SimpleTest extends AbstractIntegrationTest
{
    public function testSave()
    {
        $this->documentManager->getPersistenceService()->deleteAllIndexes();

        $this->documentManager->prepareIndex(User::class);

        $repository = $this->documentManager->getRepository(User::class);

        $user = User::create([
            'name' => 'Mr Potato Head',
            'email' => 'potato@potatohead.com',
        ]);

        $this->documentManager->save($user);

        $isTracked = $this->documentManager->contains($user);

        $this->assertFalse($isTracked, 'Should not be tracked');

        $user = $repository->find($user->getId());

        $this->assertInstanceOf(User::class, $user, 'User should have been saved');

        $this->documentManager->delete($user);

        $user = $repository->find($user->getId());

        $this->assertNull($user, 'User should have been deleted');
    }
}
