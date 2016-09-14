<?php

namespace SeekTest\Domain\TestPet;

use Seek\Document\DocumentInterface;
use Seek\Index\IndexInterface;

class TestPetIndex implements IndexInterface
{
    /**
     * @return string
     */
    public function getIndex() : string
    {
        return 'test_pet';
    }

    /**
     * @return string
     */
    public function getType() : string
    {
        return 'test_pet';
    }

    /**
     * @param DocumentInterface|TestPet $document
     * @return array
     */
    public function serialize(DocumentInterface $document) : array
    {
        return [
            'name'  => $document->get('name'),
        ];
    }

    /**
     * @param array $data
     * @param string $id
     * @return DocumentInterface|TestPet
     */
    public function deserialize(array $data, string $id) : DocumentInterface
    {
        return TestPet::create($data, $id);
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
                    ],
                ],
            ],
        ];
    }
}
