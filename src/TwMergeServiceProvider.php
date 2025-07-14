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
        $twMergeMacroName = config('lumen-tw.tw_merge_macro');

        if ($twMergeMacroName) {
            ComponentAttributeBag::macro(
                $twMergeMacroName,
                /**
                 * @param  array<string>|string|null  $args
                 */
                function (array|string|null ...$args): ComponentAttributeBag {
                    /** @var ComponentAttributeBag $this */
                    $classes = $this->get('class', '');

                    if ( ! is_string($classes)) {
                        throw new InvalidArgumentException('The "class" attribute must be a string.');
                    }

                    $this->offsetSet('class', tw_merge($args, $classes)); // @phpstan-ignore-line

                    return $this;
                }
            );
        }

        /** @var string|false|null $twMergeForMacroName */
        $twMergeForMacroName = config('lumen-tw.tw_merge_for_macro');

        if ($twMergeForMacroName) {
            ComponentAttributeBag::macro(
                $twMergeForMacroName,
                /**
                 * @param  array<string>|string|null  $args
                 */
                function (string $for, ...$args): ComponentAttributeBag {
                    /** @var ComponentAttributeBag $this */
                    $attribute = 'class' . ('' !== $for ? ':' . $for : '');

                    $classes = $this->get($attribute, '');

                    if ( ! is_string($classes)) {
                        throw new InvalidArgumentException('The "class" attribute must be a string.');
                    }

                    $this->offsetSet('class', tw_merge($args, $classes)); // @phpstan-ignore-line

                    return $this->only('class');
                }
            );
        }

        /** @var string|false|null $withoutTwMergeClassesMarcoName */
        $withoutTwMergeClassesMarcoName = config('lumen-tw.without_tw_merge_classes_macro');

        if ($withoutTwMergeClassesMarcoName) {
            ComponentAttributeBag::macro(
                $withoutTwMergeClassesMarcoName,
                function (): ComponentAttributeBag {
                    /** @var ComponentAttributeBag $this */
                    return $this->whereDoesntStartWith('class:');
                }
            );
        }

        return $this;
    }
}
