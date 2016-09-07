<?php

namespace SeekTest\Domain\User;

use Seek\Document\DocumentInterface;
use Seek\Index\IndexInterface;

class UserIndex implements IndexInterface
{
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

    /**
     * @param DocumentInterface|User $document
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
     * @return DocumentInterface|User
     */
    public function deserialize(array $data, string $id) : DocumentInterface
    {
        return User::create($data, $id);
    }

    /**
     * @return array
     */
    public function getMappings() : array
    {
        return [
            'mappings' => [
                $this->getType() => [
                    '_source'    => [
                        'enabled' => true,
                    ],
                    'properties' => [
                        'name'  => [
                            'type'  => 'string',
                            'index' => 'not_analyzed',
                        ],
                        'email' => [
                            'type'  => 'string',
                            'index' => 'not_analyzed',
                        ],
                    ],
                ],
            ],
        ];
    }
}
