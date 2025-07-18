<?php

declare(strict_types=1);

namespace Lumen\TwMerge\Tests;

use Illuminate\Foundation\Testing\Concerns\InteractsWithViews;
use Lumen\TwMerge\Support\Config;
use Lumen\TwMerge\TwMergeServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Override;

class TestCase extends Orchestra
{
    use InteractsWithViews;

    #[Override]
    protected function tearDown(): void
    {
        parent::tearDown();

        Config::wipeDefaultInstance();
    }

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
