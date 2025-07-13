# CssClassBuilder

CssClassBuilder is a utility for building CSS class lists, allowing you to easily manage adn add classes conditionally.

# Usage

A basic usage example is shown below:

```php
/** @var 'text-center text-lg font-semibold' $classes */
$classes = css_classes_builder('text-center', [null])
    ->add('text-lg', [['font-semibold']])
    ->build();

/** @var 'text-center font-semibold text-lg' $classes */
$classes = build_css_classes('text-center', [['font-semibold']], null, 'text-lg');
```

Or you can use the `CssClassBuilder` class directly:

```php
use Lumen\TwMerge\Support\CssClassBuilder;

/** @var 'text-center text-lg font-semibold' $classes */
$classes = new CssClassBuilder('text-center', [null])
    ->add('text-lg', [['font-semibold']])
    ->build();
```

It can also be used without the need to create a new instance:

```php
use Lumen\TwMerge\Support\CssClassBuilder;

/** @var 'text-center text-lg font-semibold' $classes */
$classes = CssClassBuilder::staticBuild(
    'text-center',
    [['text-lg']],
    'font-semibold'
);
```

It can also be used to conditionally add classes:

```php
use Lumen\TwMerge\Support\CssClassBuilder;

$isH1 = false;
$isH2 = true;

/** @var 'text-center text-xl font-medium' $classes */
$classes = new CssClassBuilder('text-center')
    ->addIf($isH1, ['text-2xl', 'font-semibold'])
    ->addIf(static fn () => $isH2, ['text-xl', 'font-medium'])
```
