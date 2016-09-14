<?php

namespace SeekTest\Domain\TestUser;

use Seek\Document\Document;

class TestUser extends Document
{
    /**
     * @param array $data
     * @param null $id
     * @return \Seek\Document\DocumentInterface|static
     */
    public static function create(array $data, $id = null)
    {
        return parent::create([
            'name'  => $data['name'],
            'email' => $data['email'],
        ], $id);
    }
}
