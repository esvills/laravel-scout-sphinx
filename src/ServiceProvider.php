<?php

namespace Esvills\SphinxScout;

use Foolz\SphinxQL\Drivers\Pdo\Connection;
use Foolz\SphinxQL\SphinxQL;
use Illuminate\Support\ServiceProvider as Provider;
use Laravel\Scout\EngineManager;
use Laravel\Scout\Builder;

class ServiceProvider extends Provider
{
    public function boot()
    {
        resolve(EngineManager::class)->extend('sphinxsearch', function ($app) {
            $options = config('scout.sphinxsearch');
            if (empty($options['socket']))
                unset($options['socket']);
            $connection = new Connection();
            $connection->setParams($options);

            return new SphinxEngine(new SphinxQL($connection));
        });
        Builder::macro('whereIn', function (string $attribute, array $arrayIn) {
            $this->engine()->addWhereIn($attribute, $arrayIn);
            return $this;
        });
    }
}
