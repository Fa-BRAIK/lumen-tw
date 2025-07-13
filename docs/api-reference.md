# API Reference

Reference to the helper functions and functions in `Lumen\TwMerge\Facades\TwMerge` Facade.

## Helper Functions

### `tw_merge`

```php
/** 
 * @param array<string>|string|null $classes  
 */
function tw_merge(array|string|null ...$classes): string
```

Main function to use for merging Tailwind CSS classes. Uses `merge` method from the `Lumen\TwMerge\TwMerge`.

## Facade Methods

> Note: All methods from the facade can also be accessed using service container. 
> For example, using `app('twMerge')->functionName(...)` is equivalent to using `TwMerge::functionName(...)`.

### `TwMerge::merge`

```php
/** 
 * @param array<string>|string|null $classes   
 */
static function merge(array|string|null ...$classes): string
```

### `TwMerge::getDefaultConfig`

```php
/** 
 * @return Lumen\TwMerge\Support\Contracts\Config<string, string>
 */
static function getDefaultConfig(): Lumen\TwMerge\Support\Contracts\Config
```

Creates an instance of the default configuration that can be found in `Lumen\TwMerge\Support\Config`.
This configuration will provide the ability to merge "default" tailwind classes.

### `TwMerge::getFinalConfig`

```php
static function getFinalConfig
```

Resolves the final configuration to be used for merging classes. it combines the default configuration with any provided [additional configuration](#twmergewithadditionalconfig).

### `TwMerge::withAdditionalConfig`

Function to create merge function with custom config which extends the default config. Use this if you use the default Tailwind config and just modified it in some places.

> It's recommended to use this method once in your application, typically in a service provider,
> to avoid performance issues.

Usage of this method is as follows:

```php
use Lumen\TwMerge\Facades\TwMerge;

TwMerge::withAdditionalConfig([
    // ↓ Optional prefix from Tailwind config
    'prefix' => 'tw',

    // ↓ Optional config overrides
    //   Only elements from the second level onwards are overridden
    'override' => [
        // ↓ Theme scales to override
        'theme' => [
            'colors' => ['black', 'white', 'yellow-500'],
        ],
        // ↓ Class groups to override
        'classGroups' => [
            // ↓ The `shadow` key here is the class group ID
            //      ↓ Creates group of classes which have conflicting styles
            //        Classes here: shadow-100, shadow-200, shadow-300, shadow-400, shadow-500
            'shadow' => [['shadow' => ['100', '200', '300', '400', '500']]],
        ],
        // ↓ Conflicts across different groups to override
        'conflictingClassGroups' => [
            // ↓ ID of class group which creates a conflict with…
            //           ↓ …classes from groups with these IDs
            //   Here we remove the default conflict between the font-size and leading class
            //   groups.
            'font-size' => [],
        ],
        // ↓ Conflicts between the postfix modifier of a group and a different class group to
        //   override
        'conflictingClassGroupModifiers' => [
            // You probably won't need this, but it follows the same shape as
            // `conflictingClassGroups`.
        ],
        // ↓ Modifiers whose order among multiple modifiers should be preserved because their
        //   order changes which element gets targeted. Overrides default value.
        'orderSensitiveModifiers' => ['before'],
    ],

    // ↓ Optional config extensions
    //   Follows same shape as the `override` object.
    'extend' => [
        // ↓ Theme scales to extend or create
        'theme' => [
            'spacing' => ['sm', 'md', 'lg'],
        ],
        // ↓ Class groups to extend or create
        'classGroups' => [
            // ↓ The `animate` key here is the class group ID
            //       ↓ Adds class animate-shimmer to existing group with ID `animate` or creates
            //         new class group if it doesn't exist.
            'animate' => ['animate-shimmer'],
            // ↓ Functions can also be used to match classes
            //   They take the class part value as argument and return a boolean defining whether
            //   it is a match.
            //   Here we accept all string classes starting with `aspec-w-` followed by a number.
            // 'aspect-w' => [['aspect-w' => [fn($value) => is_string($value) && !empty($value) && is_numeric($value)]]],
            // 'aspect-h' => [['aspect-h' => [fn($value) => is_string($value) && !empty($value) && is_numeric($value)]]],
            'aspect-reset' => ['aspect-none'],
            // ↓ You can also use validators exported by tailwind-merge
            'prose-size' => [['prose' => ['base', 'validators.isTshirtSize']]],
        ],
        // ↓ Conflicts across different groups to extend or create
        'conflictingClassGroups' => [
            // ↓ ID of class group which creates a conflict with…
            //              ↓ …classes from groups with these IDs
            //   In this case `twMerge('aspect-w-5 aspect-none') → 'aspect-none'`
            'aspect-reset' => ['aspect-w', 'aspect-h'],
        ],
        // ↓ Conflicts between the postfix modifier of a group and a different class group to
        //   extend or create
        'conflictingClassGroupModifiers' => [
            // You probably won't need this, but it follows the same shape as
            // `conflictingClassGroups`.
        ],
        // ↓ Modifiers whose order among multiple modifiers should be preserved because their
        //   order changes which element gets targeted. Extends default value.
        'orderSensitiveModifiers' => ['before'],
    ],
]);
```

## Other Classes

### `ThemeGetter::fromTheme`

```php
static function fromTheme(string $theme): Lumen\TwMerge\Support\ThemeGetter
```

Function to retrieve values from a theme scale, to be used in class groups.

`fromTheme` doesn't return the values from the theme scale, but rather another function which is used by tailwind-merge internally to retrieve the theme values.

Below an example of how to use it:

```php
use Lumen\TwMerge\Support\ThemeGetter;

app('twMerge')->withAdditionalConfig([
    'extend' => [
        'theme' => [
            'my-size' => ['foo', 'bar'],
        ],
        'classGroups' => [
            'px' => [
                [
                    'px' => [
                        ThemeGetter::fromTheme('my-size'),
                    ]
                ]
            ],
        ],
    ]
]);
```

After the above configuration, you can achieve the following:

```php
/** 
 * @var 'px-bar' $result
 */
$result = tw_merge('px-3 px-foo px-bar');
```
