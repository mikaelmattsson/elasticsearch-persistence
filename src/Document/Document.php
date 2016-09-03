<?php

namespace Seek\Document;

use Ramsey\Uuid\Uuid;

abstract class Document implements DocumentInterface
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
        $this->properties = $data;
    }

    public static function create(array $properties, $id = null)
    {
        return new static($properties, $id);
    }

    public function getId() : string
    {
        return $this->id;
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
