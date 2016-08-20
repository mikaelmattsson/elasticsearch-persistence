<?php

namespace SeekTest\Domain\User;

use Seek\DocumentInterface;
use Ramsey\Uuid\Uuid;

class User implements DocumentInterface
{
    /**
     * @var array
     */
    protected $properties;

    /**
     * User constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->properties = [
            'uuid' => $data['uuid'],
            'name' => $data['name'],
            'email' => $data['email'],
        ];
    }

    public function getUuid() : Uuid
    {
        return $this->properties['uuid'];
    }

    public function isDirty() : bool
    {
        return true;
    }

    public function get($property)
    {
        return $this->properties[$property];
    }

    public function set($property, $value)
    {
        $this->properties[$property] = $value;

        return $this;
    }
}
