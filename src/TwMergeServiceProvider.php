<?php

declare(strict_types=1);

namespace Lumen\TwMerge;

use Override;
use Spatie\LaravelPackageTools\Exceptions\InvalidPackage;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class TwMergeServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('lumen-tw')
            ->hasConfigFile();
    }

    /**
     * @phpstan-return static
     *
     * @throws InvalidPackage
     */
    #[Override]
    public function register()
    {
        parent::register();

        return $this->registerBindings();
    }

    protected function registerBindings(): static
    {
        $this->app->alias(TwMerge::class, 'twMerge');

        return $this;
    }
}
