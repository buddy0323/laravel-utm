<?php

namespace Adzbuck\LaravelUTM\Tests;

use Adzbuck\LaravelUTM\ServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            ServiceProvider::class,
        ];
    }
}
