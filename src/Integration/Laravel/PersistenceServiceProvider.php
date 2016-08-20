<?php

namespace Seek\Integration\Laravel;

use Elasticsearch\ClientBuilder;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Seek\DocumentManager;

class PersistenceServiceProvider extends ServiceProvider
{
    public function register()
    {
        $app = $this->app;

        $app->singleton(DocumentManager::class, function (Application $app) {
            $hosts = $app['config']->get('elasticsearch.hosts');

            return new DocumentManager(
                ClientBuilder::create()->setHosts($hosts)->build()
            );
        });
    }
}
