<?php

namespace SeekTest\Domain\User;

use Seek\Document\Document;

class User extends Document
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
