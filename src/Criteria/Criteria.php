<?php

namespace Seek\Criteria;

class Criteria
{
    /**
     * @var array
     */
    private $body;

    /**
     * Criteria constructor.
     * @param array $body
     */
    public function __construct(array $body)
    {
        $this->body = $body;
    }

    /**
     * @return array
     */
    public function getBody()
    {
        return $this->body;
    }
}
