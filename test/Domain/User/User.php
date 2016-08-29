<?php

namespace SeekTest\Domain\User;

use Ramsey\Uuid\Uuid;
use Seek\Document\DocumentInterface;

class User implements DocumentInterface
{
    /**
     * @var array
     */
    protected $properties;

    /**
     * @var string
     */
    protected $id;

    /**
     * User constructor.
     *
     * @param array $data
     * @param null $id
     */
    public function __construct(array $data, $id = null)
    {
        $this->id = $id ? $id : Uuid::uuid4()->toString();
        $this->properties = [
            'name' => $data['name'],
            'email' => $data['email'],
        ];
    }

    public function getId() : string
    {
        return $this->id;
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

    public function getProperties() : array
    {
        return $this->properties;
    }
}
