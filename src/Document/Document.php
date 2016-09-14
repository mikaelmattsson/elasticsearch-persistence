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

    /**
     * @param array $properties
     * @param null $id
     * @return static
     */
    public static function create(array $properties, $id = null)
    {
        return new static($properties, $id);
    }

    /**
     * @return string
     */
    public function getId() : string
    {
        return $this->id;
    }

    /**
     * @param string $property
     * @param mixed $default
     * @return mixed
     */
    public function get($property, $default = null)
    {
        return isset($this->properties[$property]) ? $this->properties[$property] : $default;
    }

    /**
     * @param string $property
     * @param mixed $value
     * @return $this
     */
    public function set($property, $value)
    {
        $this->properties[$property] = $value;

        return $this;
    }

    /**
     * @return array
     */
    public function getProperties() : array
    {
        return $this->properties;
    }
}
