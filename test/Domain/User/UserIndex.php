<?php

namespace SeekTest\Domain\User;

use Seek\Document\DocumentInterface;
use Seek\Index\IndexInterface;

class UserIndex implements IndexInterface
{
    /**
     * @param DocumentInterface|User $document
     *
     * @return array
     */
    public function serialize(DocumentInterface $document) : array
    {
        return [
            'uuid' => $document->getUuid(),
            'name' => $document->get('name'),
            'email' => $document->get('email'),
        ];
    }

    /**
     * @param array $data
     *
     * @return DocumentInterface|User
     */
    public function deserialize(array $data) : DocumentInterface
    {
        return new User($data);
    }

    /**
     * @return string
     */
    public function getIndex() : string
    {
        return 'user';
    }

    /**
     * @return string
     */
    public function getType() : string
    {
        return 'user';
    }
}
