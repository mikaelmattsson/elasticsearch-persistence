<?php

namespace ElasticPersistence\Hibernate;

use Elasticsearch\ClientBuilder;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class PersistenceServiceProvider extends ServiceProvider
{
    public function register()
    {
        $app = $this->app;

        $app->singleton(PersistenceService::class, function (Application $app) {
            $hosts = $app['config']->get('elasticsearch.hosts');

            return new PersistenceService(
                ClientBuilder::create()->setHosts($hosts)->build()
            );
        });
    }
}
