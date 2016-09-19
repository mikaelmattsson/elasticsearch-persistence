<?php

namespace SeekTest\Domain\TestUser;

use Seek\Document\DocumentInterface;
use Seek\Index\IndexInterface;

class TestUserIndex implements IndexInterface
{
    /**
     * @return string
     */
    public function getIndex() : string
    {
        return 'test_user';
    }

    /**
     * @return string
     */
    public function getType() : string
    {
        return 'test_user';
    }

    /**
     * @param DocumentInterface|TestUser $document
     * @return array
     */
    public function serialize(DocumentInterface $document) : array
    {
        return [
            'name'  => $document->get('name'),
            'email' => $document->get('email'),
        ];
    }

    /**
     * @param array $data
     * @param string $id
     * @return DocumentInterface|TestUser
     */
    public function deserialize(array $data, string $id) : DocumentInterface
    {
        return TestUser::create($data, $id);
    }

    /**
     * @return array
     */
    public function getMappings() : array
    {
        return [
            'properties' => [
                'name'  => ['type' => 'string', 'index' => 'not_analyzed'],
                'email' => ['type' => 'string', 'index' => 'not_analyzed'],
            ],
        ];
    }
}
