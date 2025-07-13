<?php

declare(strict_types=1);

namespace Lumen\TwMerge;

use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\ComponentAttributeBag;
use InvalidArgumentException;
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
    public function register(): static
    {
        parent::register();

        return $this->registerBindings()
            ->registerBladeBindings()
            ->registerAttributesBagMacros();
    }

    protected function registerBindings(): static
    {
        $this->app->alias(TwMerge::class, 'twMerge');

        return $this;
    }

    protected function registerBladeBindings(): static
    {
        $this->app->afterResolving('blade.compiler', function (BladeCompiler $compiler): void {
            /** @var string|false|null $twMergeDirectiveName */
            $twMergeDirectiveName = config('lumen-tw.tw_merge_directive', 'twMerge');

            if (false === $twMergeDirectiveName || null === $twMergeDirectiveName) {
                return;
            }

            $compiler->directive(
                $twMergeDirectiveName,
                fn (?string $expression): string => "<?php echo tw_merge({$expression}); ?>"
            );
        });

        return $this;
    }

    protected function registerAttributesBagMacros(): static
    {
        /** @var string|false|null $twMergeMacroName */
        $twMergeMacroName = config('lumen-tw.tw_merge_macro', 'twMerge');

        if (false === $twMergeMacroName || null === $twMergeMacroName) {
            return $this;
        }

        ComponentAttributeBag::macro(
            $twMergeMacroName,
            /**
             * @param  array<string>|string|null  $args
             */
            function (array|string|null ...$args): ComponentAttributeBag {
                $class = $this->get('class', '');

                if ( ! is_string($class)) {
                    throw new InvalidArgumentException('The "class" attribute must be a string.');
                }

                /** @var ComponentAttributeBag $this */
                $this->offsetSet('class', tw_merge($class, ...$args)); // @phpstan-ignore-line

                return $this;
            }
        );

        return $this;
    }
}
