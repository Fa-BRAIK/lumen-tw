<?php

declare(strict_types=1);

namespace Lumen\TwMerge\Tests;

use Lumen\TwMerge\TwMergeServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    public function getEnvironmentSetUp($app): void
    {
        config()->set('database.default', 'testing');
    }

    protected function getPackageProviders($app)
    {
        return [
            TwMergeServiceProvider::class,
        ];
    }
}
