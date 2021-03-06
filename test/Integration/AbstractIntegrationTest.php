<?php

namespace SeekTest\Integration;

use Seek\DocumentManager;
use Elasticsearch\ClientBuilder;

abstract class AbstractIntegrationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var DocumentManager
     */
    protected $documentManager;

    public function setUp()
    {
        $hosts = ['elasticsearch']; // docker container host name.

        $this->documentManager = new DocumentManager($hosts);

        parent::setUp();
    }
}
